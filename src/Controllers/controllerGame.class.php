<?php
/**
 * @file controllerGame.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe ControllerGame
 * @author Conchez-Boueytou Robin, ESPIET Lucas
 * @date 23/12/2024
 * @version 0.3
 */

namespace ComusParty\Controllers;

use ComusParty\App\Exceptions\GameSettingsException;
use ComusParty\App\Exceptions\GameUnavailableException;
use ComusParty\App\Exceptions\MalformedRequestException;
use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\App\Exceptions\UnauthorizedAccessException;
use ComusParty\Models\EloCalculator;
use ComusParty\Models\GameDAO;
use ComusParty\Models\GameRecord;
use ComusParty\Models\GameRecordDAO;
use ComusParty\Models\GameRecordState;
use ComusParty\Models\GameState;
use ComusParty\Models\PlayerDAO;
use DateTime;
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
     */
    private function showGameSettings(GameRecord $gameRecord): void
    {
        $gameSettings = $this->getGameSettings($gameRecord->getGame()->getId());
        if (in_array("MODIFIED_SETTING_DATA", $gameSettings["neededParametersFromComus"])) {
            $settings = $this->getGameModiableSettings($gameRecord->getGame()->getId());
        } else {
            $settings = [];
        }

        $template = $this->getTwig()->load('player/game-settings.twig');
        echo $template->render([
            "code" => $gameRecord->getCode(),
            "isHost" => $gameRecord->getHostedBy()->getUuid() == $_SESSION['uuid'],
            "players" => $gameRecord->getPlayers(),
            "game" => $gameRecord->getGame(),
            "chat" => $gameSettings["settings"]["allowChat"],
            "gameFileInfos" => $gameSettings["game"],
            "settings" => $settings,
        ]);
    }

    /**
     * @brief Récupère les paramètres modifiables du jeu dont l'ID est passé en paramètre
     * @param int $id ID du jeu
     * @return array Tableau associatif contenant les paramètres modifiables du jeu
     */
    private function getGameModiableSettings(int $id): array
    {
        $allSettings = $this->getGameSettings($id);
        return $allSettings["modifiableSettings"];
    }

    private function showInGame(GameRecord $gameRecord): void
    {
        $players = $gameRecord->getPlayers();
        if (!in_array($_SESSION['uuid'], array_map(fn($player) => $player->getUuid(), $players))) {
            throw new UnauthorizedAccessException("Vous n'êtes pas dans la partie");
        }

        $gameSettings = $this->getGameSettings($gameRecord->getGame()->getId());
        $baseUrl = $gameSettings["settings"]["serverPort"] != null ? "https://" . $gameSettings["settings"]["serverAddress"] . ":" . $gameSettings["settings"]["serverPort"] : "https://" . $gameSettings["settings"]["serverAddress"];

        $template = $this->getTwig()->load('player/in-game.twig');
        echo $template->render([
            "code" => $gameRecord->getCode(),
            "isHost" => $gameRecord->getHostedBy()->getUuid() == $_SESSION['uuid'],
            "players" => $gameRecord->getPlayers(),
            "game" => $gameRecord->getGame(),
            "chat" => $this->getGameSettings($gameRecord->getGame()->getId())["settings"]["allowChat"],
            "iframe" => $baseUrl . "/" . $gameRecord->getCode(),
        ]);
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

    public function endGame(string $code, ?array $winner, array $scores): void
    {
        $gameRecordManager = new GameRecordDAO($this->getPdo());
        $gameRecord = $gameRecordManager->findByCode($code);

        if ($gameRecord == null) {
            throw new NotFoundException("La partie n'existe pas");
        }

        if ($gameRecord->getState() != GameRecordState::STARTED) {
            throw new MalformedRequestException("La partie n'a pas commencé");
        }

        foreach ($scores as $player) {
            if (!in_array($player["uuid"], array_map(fn($player) => $player["player"]->getUuid(), $gameRecord->getPlayers()))) {
                throw new MalformedRequestException("Le joueur " . $player["uuid"] . " n'est pas dans la partie");
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
            }

            $players = $gameRecord->getPlayers();
            $winners = [];
            foreach ($players as $player) {
                if (in_array($player["player"]->getUuid(), $winner)) {
                    $winners[] = $player;
                }
            }

            /**
             * @todo Ajouter la vérification de la confidentialité de la partie (privée ou publique)
             * Si la partie est privée, le calcul d'Elo n'est pas effectué
             */

            $averageElo = 0;
            foreach ($winners as $player) {
                $averageElo += $player->getElo();
            }
            $averageElo /= sizeof($players);

            foreach ($players as $player) {
                $elo = $player["player"]->getElo();
                if (is_null($winner)) {
                    $result = 0.5;
                } else if (in_array($player["player"]->getUuid(), $winner)) {
                    $result = 1;
                } else {
                    $result = 0;
                }
                $newElo = EloCalculator::calculateNewElo($elo, $averageElo, $result);
                $player->setElo($newElo);
                (new PlayerDAO($this->getPdo()))->update($player);
            }
        }

        echo json_encode([
            "success" => true,
        ]);
        exit;
    }
}