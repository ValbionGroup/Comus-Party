<?php
/**
 * @file controllerGame.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe ControllerGame
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
use ComusParty\Models\GameDAO;
use ComusParty\Models\GameRecord;
use ComusParty\Models\GameRecordDAO;
use ComusParty\Models\GameRecordState;
use ComusParty\Models\GameState;
use ComusParty\Models\PlayerDAO;
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

                foreach ($settings as $key => $value) {
                    if (!array_key_exists($key, $gameSettings["modifiableSettings"])) {
                        throw new GameSettingsException("Les paramètres du jeu ne sont pas valides");
                    }
                }
            } else {
                $settings = [];
            }

            $baseUrl = $gameSettings["settings"]["serverPort"] != null ? $gameSettings["settings"]["serverAddress"] . ":" . $gameSettings["settings"]["serverPort"] : $gameSettings["settings"]["serverAddress"];

            $players = $gameRecord->getPlayers();
            foreach ($players as &$player) {
                $player["token"] = bin2hex(random_bytes(8));
            }
            $gameRecord->setPlayers($players);
            (new GameRecordDAO($this->getPdo()))->updatePlayers($gameRecord->getCode(), $gameRecord->getPlayers());

            $data = [];

            if (in_array("MODIFIED_SETTING_DATA", $gameSettings["neededParametersFromComus"])) {
                $data[] = [
                    "settings" => $settings,
                ];
            }

            if (in_array("PLAYER_UUID", $gameSettings["neededParametersFromComus"])) {
                $data[] = [
                    "players" => array_map(function ($player) use ($gameSettings) {
                        return [
                            'uuid' => $player["player"]->getUuid(),
                            ...(in_array("PLAYER_NAME", $gameSettings["returnParametersToComus"]) ? ['username' => $player["player"]->getUsername()] : []),
                            ...(in_array('PLAYER_STYLE', $gameSettings["returnParametersToComus"]) ? ['style' => [
                                "profilePicture" => $player["player"]->getActivePfp(),
                                "banner" => $player["player"]->getActiveBanner(),
                            ]] : []),
                            'token' => $player["token"]
                        ];
                    }, $gameRecord->getPlayers()),
                ];
            }

            $ch = curl_init($baseUrl . "/" . $gameRecord->getCode() . "/init");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Envoyer le JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Obtenir la réponse
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json", // Indiquer que les données sont au format JSON
            ]);

            // Exécuter la requête
            $response = curl_exec($ch);

            // Vérifier les erreurs
            if (curl_errno($ch)) {
                echo json_encode([
                    "success" => false,
                    "message" => curl_error($ch),
                    "code" => null
                ]);
                exit;
            } else {
                // Afficher la réponse
                echo "Réponse : " . $response;
            }

            // Fermer la connexion cURL
            curl_close($ch);

            $gameRecord->setState(GameRecordState::STARTED);
            $gameRecord->setUpdatedAt(new DateTime());
            (new GameRecordDAO($this->getPdo()))->update($gameRecord);

            echo json_encode([
                "success" => true,
                "game" => [
                    "code" => $code,
                    "gameId" => $game->getId(),
                ],
            ]);
        } catch (Exception|Error $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
                "code" => $e->getCode(),
            ]);
        }
    }

    /**
     * @brief Récupère les paramètres du jeu dont l'ID est passé en paramètre
     *
     * @param int $id ID du jeu
     * @return array Tableau associatif contenant les paramètres du jeu
     */
    private function getGameSettings(int $id): array
    {
        $gameFolder = $this->getGameFolder($id);
        $settingsFile = "$gameFolder/settings.json";
        if (!file_exists($settingsFile)) {
            return [];
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

    /**
     * @brief Récupère les informations d'un jeu et le retourne au format JSON
     * @param int|null $id L'identifiant du jeu
     * @return void
     */
    public function getGameInformations(?int $id)
    {
        $gameManager = new GameDAO($this->getPdo());
        $game = $gameManager->findWithDetailsById($id);
        echo json_encode([
            "success" => true,
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
                echo json_encode([
                    "success" => false,
                    "message" => $e->getMessage(),
                ]);
                exit;
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

    private function showInGame(GameRecord $gameRecord): void
    {
        $players = $gameRecord->getPlayers();
        if (!in_array($_SESSION['uuid'], array_map(fn($player) => $player["player"]->getUuid(), $players))) {
            throw new UnauthorizedAccessException("Vous n'êtes pas dans la partie");
        }

        $gameSettings = $this->getGameSettings($gameRecord->getGame()->getId());
        $baseUrl = $gameSettings["settings"]["serverPort"] != null ? $gameSettings["settings"]["serverAddress"] . ":" . $gameSettings["settings"]["serverPort"] : $gameSettings["settings"]["serverAddress"];
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

            echo json_encode([
                "success" => true,
            ]);
            exit;
        } catch (Exception|Error $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
                "code" => $e->getCode(),
            ]);
            exit;
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
        $game = (new GameDAO($this->getPdo()))->findById($gameId);

        if ($game->getState() != GameState::AVAILABLE) {
            echo json_encode([
                "success" => false,
                "message" => "Le jeu n'est pas disponible",
            ]);
            exit;
        }

        $gameRecordManager = new GameRecordDAO($this->getPdo());
        $gameRecords = $gameRecordManager->findByGameId($gameId);

        try {
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
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
            exit;
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
            $gameRecordManager->delete($code);
        }

        echo json_encode([
            "success" => true,
        ]);
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
            new DateTime(),
            new DateTime(),
            null
        );

        $gameRecordManager = new GameRecordDAO($this->getPdo());
        $gameRecordManager->insert($gameRecord);
        $gameRecordManager->addPlayer($gameRecord, $host);


        echo json_encode([
            "success" => true,
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
     * @param array|null $winner Tableau associatif contenant les UUID des joueurs gagnants
     * @param array|null $scores Tableau associatif contenant les scores des joueurs
     * @return void
     */
    public function endGame(string $code, ?array $winner = null, ?array $scores = null): void
    {
        try {
            $gameRecordManager = new GameRecordDAO($this->getPdo());
            $gameRecord = $gameRecordManager->findByCode($code);

            if ($gameRecord == null) {
                throw new NotFoundException("La partie n'existe pas");
            }

            if ($gameRecord->getState() != GameRecordState::STARTED) {
                throw new MalformedRequestException("La partie n'a pas commencé ou est déjà terminée");
            }

            foreach ($scores as $playerUuid => $playerScore) {
                if (!in_array($playerUuid, array_map(fn($player) => $player["player"]->getUuid(), $gameRecord->getPlayers()))) {
                    throw new MalformedRequestException("Le joueur $playerUuid n'est pas dans la partie");
                }
            }

            $gameRecord->setState(GameRecordState::FINISHED);
            $gameRecord->setFinishedAt(new DateTime());
            $gameRecordManager->update($gameRecord);

            $gameSettings = $this->getGameSettings($gameRecord->getGame()->getId());

            if (in_array("WINNER_UUID", $gameSettings["returnParametersToComus"])) {
                if (!is_null($winner)) {
                    foreach ($winner as $playerUuid) {
                        $gameRecordManager->addWinner($code, $playerUuid);
                    }
                } else {
                    $winner = [];
                }

                $players = $gameRecord->getPlayers();
                if (!$gameRecord->isPrivate()) {
                    $allWinner = array_map(
                        fn($player) => $player["player"],
                        array_filter($players,
                            fn($player) => in_array($player["player"]->getUuid(), $winner)
                        )
                    );
                    $allLooser = array_map(
                        fn($player) => $player["player"],
                        array_filter($players,
                            fn($player) => !in_array($player["player"]->getUuid(), $winner)
                        )
                    );
                    $allPlayers = array_map(fn($player) => $player["player"], $players);

                    $this->calculateAndUpdateElo($allPlayers, $allWinner, $allLooser);
                }
            }

            echo json_encode([
                "success" => true,
            ]);
            exit;
        } catch (Exception|Error $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
            exit;
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
            $player->setElo($newElo);
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
}