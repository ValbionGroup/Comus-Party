<?php
/**
 * @file controllerGame.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe ControllerGame
 * @author Conchez-Boueytou Robin
 * @date 13/11/2024
 * @version 0.2
 */

namespace ComusParty\Controllers;

use ComusParty\Models\Exceptions\GameSettingsException;
use ComusParty\Models\Exceptions\GameUnavailableException;
use ComusParty\Models\GameDao;
use ComusParty\Models\GameState;
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
        $games = $gameManager->findAll();
        $template = $this->getTwig()->load('home.twig');
        echo $template->render(array(
            "games" => $games
        ));
    }

    /**
     * @brief Permet d'initialiser le jeu après validation des paramètres et que les joueurs soient prêts
     *
     * @param int $id ID du jeu
     * @param array|null $settings Paramètres du jeu
     * @param array $players Joueurs de la partie
     * @throws GameSettingsException Exception levée si les paramètres du jeu ne sont pas valides
     * @throws GameUnavailableException Exception levée si le jeu n'est pas disponible
     */
    public function initGame(int $id, array $players, ?array $settings): void
    {
        $game = (new GameDao($this->getPdo()))->findById($id);

        if ($game->getState() != GameState::AVAILABLE) {
            throw new GameUnavailableException("Le jeu n'est pas disponible");
        }

        $gameFolder = $this->getGameFolder($id);
        $gameSettings = $this->getGameSettings($id);

        if (sizeof($gameSettings) == 0) {
            throw new GameUnavailableException("Les paramètres du jeu ne sont pas disponibles");
        }

        if (in_array("MODIFIED_SETTING_DATA", $gameSettings["neededParametersFromComus"])) {
            if (sizeof($settings) != sizeof($gameSettings["modifiableSettings"])) {
                throw new GameSettingsException("Les paramètres du jeu ne sont pas valides");
            }

            foreach ($settings as $key => $value) {
                if (!array_key_exists($key, $gameSettings["modifiableSettings"])) {
                    throw new GameSettingsException("Les paramètres du jeu ne sont pas valides");
                }

                if (!in_array($value, $gameSettings["modifiableSettings"][$key])) {
                    throw new GameSettingsException("Les paramètres du jeu ne sont pas valides");
                }
            }
        } else {
            $settings = [];
        }
    }

    /**
     * @brief Récupère le dossier du jeu dont l'UUID est passé en paramètre
     *
     * @param int $id
     * @return string Chemin du dossier du jeu
     */
    private function getGameFolder(int $id): string
    {
        return realpath(__DIR__ . "/../..") . "/games/game$id";
    }

    /**
     * @brief Récupère les paramètres du jeu dont l'UUID est passé en paramètre
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
}