<?php
/**
 * @file ControllerGame.class.php
 * @brief Fichier de déclaration et définition de la classe ControllerGame
 * @author Conchez-Boueytou Robin, ESPIET Lucas
 * @date 23/12/2024
 * @version 0.3
 */

namespace ComusParty\Controllers;

use ComusParty\App\EloCalculator;
use ComusParty\App\Exceptions\GameSettingsException;
use ComusParty\App\Exceptions\GameUnavailableException;
use ComusParty\App\Exceptions\MalformedRequestException;
use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\App\Exceptions\UnauthorizedAccessException;
use ComusParty\App\MessageHandler;
use ComusParty\App\Validator;
use ComusParty\Models\GameDAO;
use ComusParty\Models\GameRecord;
use ComusParty\Models\GameRecordDAO;
use ComusParty\Models\GameRecordState;
use ComusParty\Models\GameState;
use ComusParty\Models\PenaltyDAO;
use ComusParty\Models\PlayerDAO;
use DateMalformedStringException;
use DateTime;
use Error;
use Exception;
use Random\RandomException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerGame
 * @details Contrôleur permettant de gérer l'affichage des jeux sur différente page.
 */
class ControllerGame extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerGame
     * @param FilesystemLoader $loader Loader Twig
     * @param Environment $twig Environnement Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Affiche la page d'accueil avec la liste des jeux
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showHomePage()
    {
        $gameManager = new GameDAO($this->getPdo());
        $games = $gameManager->findAllWithTags();
        $games = array_filter($games, fn($game) => $game->getState() == GameState::AVAILABLE);
        $template = $this->getTwig()->load('player/home.twig');
        echo $template->render(array(
            "games" => $games
        ));
    }

    /**
     * @brief Permet d'initialiser le jeu après validation des paramètres et que les joueurs soient prêts
     *
     * @param string $code Code de la partie
     * @param array|null $settings Paramètres du jeu
     * @throws GameSettingsException Exception levée si les paramètres du jeu ne sont pas valides
     * @throws GameUnavailableException Exception levée si le jeu n'est pas disponible
     * @throws MalformedRequestException Exception levée si la partie est déjà commencée ou terminée
     * @throws NotFoundException Exception levée si la partie n'existe pas
     * @throws Exception Exception levée en cas d'erreur avec la base de données
     */
    public function initGame(string $code, ?array $settings): void
    {
        try {
            $gameRecord = (new GameRecordDAO($this->getPdo()))->findByCode($code);

            if ($gameRecord == null) {
                throw new NotFoundException("La partie n'existe pas");
            }

            if ($gameRecord->getState() != GameRecordState::WAITING) {
                throw new MalformedRequestException("La partie a déjà commencé ou est terminée");
            }

            if ($gameRecord->getHostedBy()->getUuid() != $_SESSION['uuid']) {
                throw new UnauthorizedAccessException("Vous n'êtes pas l'hôte de la partie");
            }

            $game = $gameRecord->getGame();

            if ($game->getState() != GameState::AVAILABLE) {
                throw new GameUnavailableException("Le jeu n'est pas disponible");
            }

            $gameSettings = $this->getGameSettings($game->getId());
            if (sizeof($gameSettings) == 0) {
                throw new GameUnavailableException("Les paramètres du jeu ne sont pas disponibles");
            }

            $nbPlayers = sizeof($gameRecord->getPlayers());
            if ($nbPlayers < $gameSettings["settings"]["minPlayers"] || $nbPlayers > $gameSettings["settings"]["maxPlayers"]) {
                throw new GameSettingsException("Le nombre de joueurs est de " . $nbPlayers . " alors que le jeu nécessite entre " . $gameSettings["settings"]["minPlayers"] . " et " . $gameSettings["settings"]["maxPlayers"] . " joueurs");
            }

            if (in_array("MODIFIED_SETTING_DATA", $gameSettings["neededParametersFromComus"])) {
                if (sizeof($settings) != sizeof($gameSettings["modifiableSettings"])) {
                    throw new GameSettingsException("Les paramètres du jeu ne sont pas valides");
                }

                $rules = [];
                foreach ($settings as $key => $value) {
                    if (!array_key_exists($key, $gameSettings["modifiableSettings"])) {
                        throw new GameSettingsException("Les paramètres du jeu ne sont pas valides");
                    }

                    $neededSetting = $gameSettings["modifiableSettings"][$key];

                    if ($neededSetting["type"] == "select" && !in_array($value, array_map(fn($option) => $option["value"], $neededSetting["options"]))) {
                        throw new GameSettingsException("Le paramètre $key doit être une des valeurs suivantes : " . implode(", ", $neededSetting["values"]));
                    } elseif ($neededSetting["type"] == "select") {
                        continue;
                    }

                    if ($neededSetting["type"] == "checkbox" && !is_bool($value)) {
                        throw new GameSettingsException("Le paramètre $key doit être un booléen");
                    } elseif ($neededSetting["type"] == "checkbox") {
                        continue;
                    }

                    $rules[$key] = [
                        "required" => true,
                        "type" => $neededSetting["type"] == "number" ? "numeric" : "string",
                        ...(array_key_exists("min", $neededSetting) ? ["min-value" => $neededSetting["min"]] : []),
                        ...(array_key_exists("max", $neededSetting) ? ["max-value" => $neededSetting["max"]] : []),
                        ...(array_key_exists("pattern", $neededSetting) ? ["format" => $neededSetting["pattern"]] : [])
                    ];
                }
                $validator = new Validator($rules);

                if (!$validator->validate($settings)) {
                    throw new GameSettingsException("Les paramètres du jeu ne sont pas valides.\r\n" . implode("\r\n", array_map(fn(string $key, array $value) => "[$key] " . implode(", ", $value), array_keys($validator->getErrors()), $validator->getErrors())));
                }
            } else {
                $settings = [];
            }

            $baseUrl = $this->getGameUrl($game->getId());

            $players = $gameRecord->getPlayers();
            foreach ($players as &$player) {
                $player["token"] = bin2hex(random_bytes(8));
            }
            $gameRecord->setPlayers($players);
            (new GameRecordDAO($this->getPdo()))->updatePlayers($gameRecord->getCode(), $gameRecord->getPlayers());

            $token = $gameRecord->generateToken();

            $data = [
                "token" => $token,
                "code" => $gameRecord->getCode(),
            ];

            if (in_array("MODIFIED_SETTING_DATA", $gameSettings["neededParametersFromComus"])) {
                $data["settings"] = $settings;
            }

            if (in_array("PLAYER_UUID", $gameSettings["neededParametersFromComus"])) {
                $data["players"] = array_map(function ($player) use ($gameSettings) {
                    return [
                        'uuid' => $player["player"]->getUuid(),
                        ...(in_array("PLAYER_NAME", $gameSettings["neededParametersFromComus"]) ? ['username' => $player["player"]->getUsername()] : []),
                        ...(in_array('PLAYER_STYLE', $gameSettings["neededParametersFromComus"]) ? ['style' => [
                            "profilePicture" => $player["player"]->getActivePfp(),
                            "banner" => $player["player"]->getActiveBanner(),
                        ]] : []),
                        'token' => $player["token"]
                    ];
                }, $gameRecord->getPlayers());
            }

            $ch = curl_init($baseUrl . "/" . $gameRecord->getCode() . "/init");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Envoyer le JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Obtenir la réponse
            curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json", // Indiquer que les données sont au format JSON
            ]);

            // Exécuter la requête
            $response = curl_exec($ch);

            // Vérifier les erreurs
            if (curl_errno($ch)) {
                MessageHandler::sendJsonCustomException(500, curl_error($ch));
            } else {
                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
                    MessageHandler::sendJsonCustomException(curl_getinfo($ch, CURLINFO_HTTP_CODE), "Erreur lors de l'initialisation du jeu");
                }

                $response = json_decode($response, true);
                if (!$response["success"]) {
                    MessageHandler::sendJsonCustomException(
                        array_key_exists("code", $response) ? $response["code"] : 500,
                        "Erreur lors de l'initialisation du jeu." . (array_key_exists("message", $response) ? " " . $response["message"] : "")
                    );
                }
            }

            // Fermer la connexion cURL
            curl_close($ch);

            $gameRecord->setState(GameRecordState::STARTED);
            $gameRecord->setUpdatedAt(new DateTime());
            (new GameRecordDAO($this->getPdo()))->update($gameRecord);

            MessageHandler::sendJsonMessage("La partie a bien été initialisée", [
                "game" => [
                    "code" => $code,
                    "gameId" => $game->getId(),
                ],
            ]);
            exit;
        } catch (Exception|Error $e) {
            MessageHandler::sendJsonException($e);
        }
    }

    /**
     * @brief Récupère les paramètres du jeu dont l'ID est passé en paramètre
     *
     * @param int $id ID du jeu
     * @return array Tableau associatif contenant les paramètres du jeu
     * @throws GameUnavailableException Exception levée si le fichier de paramètres du jeu n'existe pas
     */
    private function getGameSettings(int $id): array
    {
        $gameFolder = $this->getGameFolder($id);
        $settingsFile = "$gameFolder/settings.json";
        if (!file_exists($settingsFile)) {
            throw new GameUnavailableException("Le fichier $settingsFile n'existe pas. Impossible de récupérer les informations du jeu.");
        }

        return json_decode(file_get_contents($settingsFile), true);
    }

    /**
     * @brief Récupère le dossier du jeu dont l'ID est passé en paramètre
     *
     * @param int $id
     * @return string Chemin du dossier du jeu
     */
    private function getGameFolder(int $id): string
    {
        return realpath(__DIR__ . "/../..") . "/games/game$id";
    }

    private function getGameUrl(int $id): string
    {
        $gameSettings = $this->getGameSettings($id);
        if ($gameSettings["settings"]["serveByComus"]) {
            return "https://games.comus-party.com/game" . $id;
        } else {
            return $gameSettings["settings"]["serverAddress"] . ":" . $gameSettings["settings"]["serverPort"];
        }
    }

    /**
     * @brief Récupère les informations d'un jeu et le retourne au format JSON
     * @param int|null $id L'identifiant du jeu
     * @return void
     */
    public function getGameInformations(?int $id)
    {
        $gameManager = new GameDAO($this->getPdo());
        $game = $gameManager->findWithDetailsById($id);
        echo MessageHandler::sendJsonMessage("Informations du jeu récupérées", [
            "game" => [
                "id" => $game->getId(),
                "name" => $game->getName(),
                "img" => $game->getPathImg(),
                "description" => $game->getDescription(),
                "tags" => $game->getTags(),
            ],
        ]);
        exit;
    }

    /**
     * @brief Affiche la page de la partie dont le code est passé en paramètre
     * @param string $code Code de la partie
     * @return void
     * @throws NotFoundException Exception levée si la partie n'existe pas
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws Exception Exception levée en cas d'erreur avec la base de données
     */
    public function showGame(string $code): void
    {
        $gameRecord = (new GameRecordDAO($this->getPdo()))->findByCode($code);
        if ($gameRecord == null || $gameRecord->getGame()->getState() != GameState::AVAILABLE) {
            throw new NotFoundException("La partie n'existe pas");
        }

        if (is_null($gameRecord->getPlayers())) {
            (new GameRecordDAO($this->getPdo()))->delete($gameRecord->getCode());
            throw new NotFoundException("La partie n'existe pas");
        }

        if ($gameRecord->getState() == GameRecordState::WAITING) {
            $this->showGameSettings($gameRecord);
        } else if ($gameRecord->getState() == GameRecordState::STARTED) {
            $this->showInGame($gameRecord);
        } else {
            throw new Exception("Cette partie est terminée", 404);
        }

        exit;
    }

    /**
     * @brief Affiche la page des paramètres de la partie
     * @param GameRecord $gameRecord Instance de GameRecord
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws GameUnavailableException|NotFoundException Exception levée si la partie n'existe pas ou si le jeu n'est pas disponible
     * @throws Exception Exception levée en cas d'erreur avec la base de données
     */
    private function showGameSettings(GameRecord $gameRecord): void
    {
        if (!in_array((new PlayerDAO($this->getPdo()))->findByUuid($_SESSION['uuid']), array_map(fn($player) => $player['player'], $gameRecord->getPlayers()))) {
            $this->joinGameWithCode('GET', $gameRecord->getCode());
            $gameRecord = (new GameRecordDAO($this->getPdo()))->findByCode($gameRecord->getCode());
        }

        $gameSettings = $this->getGameSettings($gameRecord->getGame()->getId());
        if (in_array("MODIFIED_SETTING_DATA", $gameSettings["neededParametersFromComus"])) {
            $settings = $this->getGameModifiableSettings($gameRecord->getGame()->getId());
        } else {
            $settings = [];
        }

        $template = $this->getTwig()->load('player/game-settings.twig');
        echo $template->render([
            "code" => $gameRecord->getCode(),
            "isHost" => $gameRecord->getHostedBy()->getUuid() == $_SESSION['uuid'],
            "players" => array_map(fn($player) => $player['player'], $gameRecord->getPlayers()),
            "game" => $gameRecord->getGame(),
            "chat" => $gameSettings["settings"]["allowChat"],
            "gameFileInfos" => $gameSettings["game"],
            "settings" => $settings,
            "isPrivate" => $gameRecord->isPrivate(),
        ]);
    }

    /**
     * @brief Rejoint une partie avec un code (Méthode GET ou POST autorisée)
     * @param string $method Méthode HTTP utilisée
     * @param string $code Code de la partie
     * @throws GameUnavailableException Exception levée si le jeu n'est pas disponible
     * @throws NotFoundException Exception levée si la partie n'existe pas
     */
    public function joinGameWithCode(string $method, string $code): void
    {
        if ($method == 'POST') {
            try {
                echo $this->joinGame($code, $_SESSION['uuid']);
            } catch (Exception $e) {
                MessageHandler::sendJsonException($e);
            }
        } elseif ($method == 'GET') {
            $this->joinGame($code, $_SESSION['uuid']);
        }
    }

    /**
     * @brief Rejoint une partie avec un code
     * @param string $code Code de la partie
     * @param string|null $playerUuid UUID de l'utilisateur, null si l'utilisateur n'est pas connecté
     * @return string Réponse au format JSON
     * @throws GameUnavailableException Exception levée si la partie n'est pas disponible
     * @throws NotFoundException Exception levée si la partie n'existe pas
     * @throws Exception Exception levée en cas d'erreur avec la base de données
     */
    private function joinGame(string $code, ?string $playerUuid = null): string
    {
        $gameRecordManager = new GameRecordDAO($this->getPdo());
        $gameRecord = $gameRecordManager->findByCode($code);

        if ($gameRecord == null) {
            throw new NotFoundException("La partie n'existe pas");
        }

        if ($this->getGameSettings($gameRecord->getGame()->getId())['settings']['maxPlayers'] <= sizeof($gameRecord->getPlayers())) {
            throw new GameUnavailableException("La partie est pleine");
        }

        $player = (new PlayerDAO($this->getPdo()))->findByUuid($playerUuid);

        // TODO: Fonctionnement pour un joueur non connecté a insérer ici

        if ($gameRecord->getState() != GameRecordState::WAITING) {
            throw new GameUnavailableException("La partie a déjà commencé");
        }

        if (!in_array($playerUuid, array_map(fn($player) => $player['player']->getUuid(), $gameRecord->getPlayers()))) {
            $gameRecordManager->addPlayer($gameRecord, $player);
        }

        return json_encode([
            "success" => true,
            "game" => [
                "code" => $code,
                "gameId" => $gameRecord->getGame()->getId(),
            ],
        ]);
    }

    /**
     * @brief Récupère les paramètres modifiables du jeu dont l'ID est passé en paramètre
     * @param int $id ID du jeu
     * @return array Tableau associatif contenant les paramètres modifiables du jeu
     */
    private function getGameModifiableSettings(int $id): array
    {
        $allSettings = $this->getGameSettings($id);
        return $allSettings["modifiableSettings"];
    }

    /**
     * @brief Affiche la page de la partie en cours
     * @param GameRecord $gameRecord Instance de GameRecord
     * @return void
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws UnauthorizedAccessException Exception levée si l'utilisateur n'est pas dans la partie
     */
    private function showInGame(GameRecord $gameRecord): void
    {
        $players = $gameRecord->getPlayers();
        if (!in_array($_SESSION['uuid'], array_map(fn($player) => $player["player"]->getUuid(), $players))) {
            throw new UnauthorizedAccessException("Vous n'êtes pas dans la partie");
        }

        $baseUrl = $this->getGameUrl($gameRecord->getGame()->getId());
        $token = null;
        foreach ($players as $player) {
            if ($player["player"]->getUuid() == $_SESSION['uuid']) {
                $token = $player["token"];
                break;
            }
        }
        $template = $this->getTwig()->load('player/in-game.twig');
        echo $template->render([
            "code" => $gameRecord->getCode(),
            "isHost" => $gameRecord->getHostedBy()->getUuid() == $_SESSION['uuid'],
            "players" => $gameRecord->getPlayers(),
            "game" => $gameRecord->getGame(),
            "chat" => $this->getGameSettings($gameRecord->getGame()->getId())["settings"]["allowChat"],
            "iframe" => $baseUrl . "/" . $gameRecord->getCode() . "/" . $token
        ]);
    }

    /**
     * @brief Permet de changer la visibilité d'une partie
     * @param string $code Code de la partie
     * @param bool $isPrivate Vrai si la partie doit être privée, faux sinon
     * @return void
     */
    public function changeVisibility(string $code, bool $isPrivate): void
    {
        try {
            $gameRecordManager = new GameRecordDAO($this->getPdo());
            $gameRecord = $gameRecordManager->findByCode($code);

            if ($gameRecord == null) {
                throw new NotFoundException("La partie n'existe pas");
            }

            if ($gameRecord->getHostedBy()->getUuid() != $_SESSION['uuid']) {
                throw new UnauthorizedAccessException("Vous n'êtes pas l'hôte de la partie");
            }

            $gameRecord->setPrivate($isPrivate);
            $gameRecordManager->update($gameRecord);

            echo MessageHandler::sendJsonMessage("La visibilité de la partie a bien été modifiée");
            exit;
        } catch (Exception|Error $e) {
            MessageHandler::sendJsonException($e);
        }
    }

    /**
     * @brief Rejoint une partie suite à une recherche de partie
     * @param int $gameId Identifiant du jeu à rejoindre
     * @return void
     * @throws Exception Exception levée en cas d'erreur avec la base de données
     */
    public function joinGameFromSearch(int $gameId): void
    {
        try {
            $game = (new GameDAO($this->getPdo()))->findById($gameId);

            if ($game->getState() != GameState::AVAILABLE) {
                throw new GameUnavailableException("Le jeu n'est pas disponible");
            }

            $gameRecordManager = new GameRecordDAO($this->getPdo());
            $gameRecords = $gameRecordManager->findByGameId($gameId);

            $eloForGame = [];
            foreach ($gameRecords as $gameRecord) {
                if ($gameRecord->getState() == GameRecordState::WAITING && !$gameRecord->isPrivate()) {
                    $players = $gameRecord->getPlayers();

                    $totalElo = 0;
                    $nbPlayers = 0;

                    if (is_null($players)) {
                        continue;
                    }

                    foreach ($players as $player) {
                        $totalElo += $player['player']->getElo();
                        $nbPlayers++;
                    }

                    $eloForGame[$gameRecord->getCode()] = $totalElo / $nbPlayers;
                }
            }

            if (sizeof($eloForGame) == 0) {
                throw new GameUnavailableException("Aucune partie n'est disponible");
            }

            $playerElo = (new PlayerDAO($this->getPdo()))->findByUuid($_SESSION['uuid'])->getElo();
            $bestGame = null;

            foreach ($eloForGame as $gameCode => $gameElo) {
                if ($bestGame == null || abs($gameElo - $playerElo) < abs($eloForGame[$bestGame] - $playerElo)) {
                    $bestGame = $gameCode;
                }
            }

            echo $this->joinGame($bestGame, $_SESSION['uuid']);
            exit;
        } catch (Exception $e) {
            MessageHandler::sendJsonException($e);
        }
    }

    /**
     * @brief Quitte une partie
     * @param string $code UUID de la partie à quitter
     * @param string $playerUuid UUID du joueur qui quitte la partie
     * @return void
     * @throws NotFoundException Exception levée si la partie n'existe pas
     * @throws Exception Exception levée en cas d'erreur avec la base de données
     */
    public function quitGame(string $code, string $playerUuid): void
    {
        $gameRecordManager = new GameRecordDAO($this->getPdo());
        $gameRecord = $gameRecordManager->findByCode($code);

        if ($gameRecord == null) {
            throw new NotFoundException("La partie n'existe pas");
        }

        $gameRecordManager->removePlayer($code, $playerUuid);

        if ($gameRecord->getHostedBy()->getUuid() == $playerUuid) {
            if (sizeof($gameRecord->getPlayers()) > 0) {
                $gameRecord->setHostedBy($gameRecord->getPlayers()[0]["player"]);
                $gameRecordManager->update($gameRecord);
            } else {
                $gameRecordManager->delete($code);
            }
        }

        echo MessageHandler::sendJsonMessage("Vous avez bien quitté la partie");
        exit;
    }

    /**
     * @brief Crée une partie en base de données pour un jeu donné
     * @param int $gameId Identifiant du jeu
     * @return void
     * @throws GameUnavailableException Exception levée si le jeu n'est pas disponible
     * @throws RandomException Exceptions levée en cas d'erreur lors de la génération du code
     * @throws Exception Exceptions levée en cas d'erreur avec la base de données
     */
    public function createGame(int $gameId): void
    {
        $game = (new GameDAO($this->getPdo()))->findById($gameId);

        if ($game->getState() != GameState::AVAILABLE) {
            throw new GameUnavailableException("Le jeu n'est pas disponible");
        }

        $host = (new PlayerDAO($this->getPdo()))->findByUuid($_SESSION['uuid']);
        $generatedCode = bin2hex(random_bytes(16));

        $gameRecord = new GameRecord(
            $generatedCode,
            $game,
            $host,
            null,
            GameRecordState::WAITING,
            true,
            null,
            new DateTime(),
            new DateTime(),
            null
        );

        $gameRecordManager = new GameRecordDAO($this->getPdo());
        $gameRecordManager->insert($gameRecord);
        $gameRecordManager->addPlayer($gameRecord, $host);

        echo MessageHandler::sendJsonMessage("La partie a bien été créée", [
            "game" => [
                "code" => $generatedCode,
                "gameId" => $gameId,
            ],
        ]);
        exit;
    }

    /**
     * @brief Termine une partie et met à jour les scores et les gagnants
     * @param string $code Code de la partie
     * @param string $token Token de la partie
     * @param array|null $results Résultats de la partie si le jeu les renvoies
     * @return void
     */
    public function endGame(string $code, string $token, ?array $results = null): void
    {
        try {
            $gameRecordManager = new GameRecordDAO($this->getPdo());
            $playerManager = new PlayerDAO($this->getPdo());
            $gameRecord = $gameRecordManager->findByCode($code);

            if ($gameRecord == null) {
                throw new NotFoundException("La partie n'existe pas");
            }

            if ($gameRecord->getState() != GameRecordState::STARTED) {
                throw new MalformedRequestException("La partie n'a pas commencé ou est déjà terminée");
            }

            if ($gameRecord->getToken() != hash("sha256", $token)) {
                throw new UnauthorizedAccessException("Impossible d'authentifier le serveur de jeu");
            }

            if (!empty($results)) {
                $actualPlayerTokenInRecord = [];
                foreach ($gameRecord->getPlayers() as $player) {
                    $actualPlayerTokenInRecord[$player["player"]->getUuid()] = $player["token"];
                }

                $gameSettings = $this->getGameSettings($gameRecord->getGame()->getId());

                foreach ($results as $playerUuid => $playerData) {
                    if (!isset($actualPlayerTokenInRecord[$playerUuid]) || $actualPlayerTokenInRecord[$playerUuid] !== $playerData["token"]) {
                        throw new MalformedRequestException("Le joueur $playerUuid n'est pas dans la partie ou le token est invalide");
                    }
                }

                $allWinner = [];
                $allLooser = [];
                $allPlayers = [];
                foreach ($results as $playerUuid => $playerData) {
                    if (in_array("SCORES", $gameSettings["returnParametersToComus"])) {
                        // TODO: Traiter le score des joueurs
                    }

                    if (in_array("WINNERS", $gameSettings["returnParametersToComus"])) {
                        if (!isset($playerData["winner"])) {
                            throw new MalformedRequestException("L'attribut \"winner\" n'est pas présent");
                        }

                        // TODO: Fix array_filter($gameRecord->getPlayers(), fn($player) => $player["player"]->getUuid() == $playerUuid)[0]["player"]
                        $player = $playerManager->findByUuid($playerUuid);
                        if ($playerData["winner"]) {
                            $allWinner[] = $player;
                            $gameRecordManager->addWinner($code, $playerUuid);
                        } else {
                            $allLooser[] = $player;
                        }
                        $allPlayers[] = $player;
                    }
                }

                if (!$gameRecord->isPrivate() && in_array("WINNERS", $gameSettings["returnParametersToComus"])) {
                    $this->calculateAndUpdateElo($allPlayers, $allWinner, $allLooser);
                }
            }

            $gameRecord->setState(GameRecordState::FINISHED);
            $gameRecord->setFinishedAt(new DateTime());
            $gameRecordManager->update($gameRecord);

            echo MessageHandler::sendJsonMessage("La partie a bien été terminée");
            exit;
        } catch (Exception|Error $e) {
            MessageHandler::sendJsonException($e);
        }
    }

    /**
     * @brief Calcule et met à jour les scores Elo des joueurs
     * @param array $allPlayers Tableau des joueurs
     * @param array $winners Tableau des joueurs gagnants
     * @param array $looser Tableau des joueurs perdants
     * @return void
     */
    private function calculateAndUpdateElo(array $allPlayers, array $winners, array $looser): void
    {
        $averageEloLooser = $this->averageElo($looser);
        $averageEloWinner = $this->averageElo($winners);

        $playerManager = new PlayerDAO($this->getPdo());
        foreach ($allPlayers as $player) {
            $elo = $player->getElo();
            if (sizeof($winners) == 0) {
                $newElo = EloCalculator::calculateNewElo($elo, $averageEloLooser, 0.5);
            } else if (in_array($player, $winners)) {
                $newElo = EloCalculator::calculateNewElo($elo, $averageEloLooser, 1);
            } else {
                $newElo = EloCalculator::calculateNewElo($elo, $averageEloWinner, 0);
            }
            if ($newElo < 0)
                $newElo = 0;
            $player->setElo(round($newElo));
            $playerManager->update($player);
        }
    }

    /**
     * @param array $players Tableau des joueurs
     * @return float Moyenne des scores Elo des joueurs
     */
    private function averageElo(array $players): float
    {
        $averageElo = 0;
        foreach ($players as $player) {
            $averageElo += $player->getElo();
        }
        return $averageElo / sizeof($players);
    }

    /**
     * @brief Vérifie si un joueur est mute
     * @param string $playerUsername Nom d'utilisateur du joueur
     * @return void
     * @throws DateMalformedStringException
     */
    public function isPlayerMuted(string $playerUsername): void
    {
        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findByUsername($playerUsername);

        $penaltyManager = new PenaltyDAO($this->getPdo());
        $penalty = $penaltyManager->findLastMutedByPlayerUuid($player->getUuid());

        if (isset($penalty)) {
            $endDate = $penalty->getCreatedAt()->modify("+" . $penalty->getDuration() . "hour");
            if ($endDate > new DateTime()) {
                echo MessageHandler::sendJsonMessage("Le joueur est encore mute");
                exit;
            }
        }

        MessageHandler::sendJsonCustomException(404, "Le joueur n'est pas mute");
    }
}