<?php
/**
 * @file    controllerProfile.class.php
 * @brief   Ce fichier contient la déclaration & définition de la classe ControllerProfile.
 * @author  Estéban DESESSARD
 * @date    15/11/2024
 * @version 0.2
 */

namespace ComusParty\Controllers;

use ComusParty\App\Exceptions\ControllerNotFoundException;
use ComusParty\App\Exceptions\MethodNotFoundException;
use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\App\Exceptions\UnauthorizedAccessException;
use ComusParty\App\Mailer;
use ComusParty\App\Validator;
use ComusParty\Models\ArticleDAO;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\UserDAO;
use DateMalformedStringException;
use Exception;
use Random\RandomException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerProfile
 * @details Contrôleur de la page profile, utilisé pour afficher le profil d'un joueur sous différents angles (vu par lui-même, par un autre joueur, ou par un modérateur)
 */
class ControllerProfile extends Controller
{

    /**
     * @brief Constructeur de la classe ControllerProfile
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Affiche le profil du joueur le demandant
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws NotFoundException|SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showByPlayer(?string $player_uuid): void
    {
        if (is_null($player_uuid)) {
            throw new NotFoundException('Player not found');
        }
        $playerManager = new PlayerDAO($this->getPdo());
        $userManager = new UserDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($player_uuid);
        if (is_null($player)) {
            throw new NotFoundException('Player not found');
        }
        $user = $userManager->findById($player->getUserId());
        $articleManager = new ArticleDAO($this->getPdo());
        $pfp = $articleManager->findActivePfpByPlayerUuid($player->getUuid());
        if (is_null($pfp)) {
            $pfpPath = 'default-pfp.jpg';
        } else {
            $pfpPath = $pfp->getFilePath();
        }
        $banner = $articleManager->findActiveBannerByPlayerUuid($player->getUuid());
        if (is_null($banner)) {
            $bannerPath = 'default-banner.jpg';
        } else {
            $bannerPath = $banner->getFilePath();
        }
        $pfpsOwned = $articleManager->findAllPfpsOwnedByPlayer($player->getUuid());
        $bannersOwned = $articleManager->findAllBannersOwnedByPlayer($player->getUuid());
        $template = $this->getTwig()->load('player/profil.twig');
        echo $template->render(array(
            "player" => $player,
            "user" => $user,
            "pfp" => $pfpPath,
            "banner" => $bannerPath,
            "pfpsOwned" => $pfpsOwned,
            "bannersOwned" => $bannersOwned
        ));
    }

    /**
     * @param string|null $uuid L'UUID du joueur à désactiver
     * @return void
     * @throws NotFoundException Exception levée dans le cas où le joueur n'est pas trouvé
     * @throws UnauthorizedAccessException Exception levée dans le cas où l'utilisateur n'est pas autorisé à effectuer cette action
     * @throws ControllerNotFoundException Exception levée dans le cas où le contrôleur n'est pas trouvé
     * @throws MethodNotFoundException Exception levée dans le cas où la méthode n'est pas trouvée
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function disableAccount(?string $uuid)
    {
        if (is_null($uuid)) {
            throw new NotFoundException('Player not found');
        }
        if ($_SESSION['uuid'] != $uuid) {
            throw new UnauthorizedAccessException('Vous n\'êtes pas autorisé à effectuer cette action');
        }
        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($uuid);
        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findById($player->getUserId());
        if (is_null($user)) {
            throw new NotFoundException('User not found');
        }
        $userManager->disableAccount($user->getId());
        ControllerFactory::getController('auth', $this->getLoader(), $this->getTwig())->call('logout');
        header('Location: /');
    }

    /**
     * @brief Permet de mettre à jour la photo de profil ou la bannière d'un joueur
     * @param string|null $player_uuid L'UUID du joueur à désactiver
     * @param string $idArticle L'id de l'article à activer
     * @param string $typeArticle Le type de l'article à activer
     * @return void
     */
    public function updateStyle(?string $player_uuid, string $idArticle): void
    {
        if (is_null($player_uuid)) {
            throw new NotFoundException('Player not found');
        }
        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($player_uuid);
        if (is_null($player)) {
            throw new NotFoundException('Player not found');
        }
        $idArticle = intval($idArticle);
        $articleManager = new ArticleDAO($this->getPdo());
        // 0 représente la photo de profil par défaut et -1 la bannière par défaut
        if ($idArticle >= 1) {
            $typeArticle = $articleManager->findById($idArticle)->getType()->name;
        }

        if ($idArticle == 0) {

            $articleManager->deleteActiveArticleForPfp($player->getUuid());
            $_SESSION['pfpPath'] = "default-pfp.jpg";
            echo json_encode([
                'articlePath' => "default-pfp.jpg",
            ]);
        }
        if ($idArticle == -1) {
            $articleManager->deleteActiveArticleForBanner($player->getUuid());
            $_SESSION['bannerPath'] = "default-banner.jpg";
            echo json_encode([
                'articlePath' => "default-banner.jpg",
            ]);
        }
        if ($idArticle != 0 && $idArticle != -1) {
            $articleManager->updateActiveArticle($player->getUuid(), $idArticle, $typeArticle);
            $article = $articleManager->findById($idArticle);

            echo json_encode([
                'articlePath' => $article->getFilePath(),
                'idArticle' => $idArticle
            ]);
        }
    }

    /**
     * @brief Permet de mettre à jour le nom d'utilisateur d'un joueur
     * @param string $username Le nouveau nom d'utilisateur
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function updateUsername(string $username)
    {
        $validator = new Validator([
            'username' => [
                'required' => true,
                'min-length' => 3,
                'max-length' => 120,
                'type' => 'string',
                'format' => '/^[a-zA-Z0-9_-]+$/'
            ]
        ]);
        if (!$validator->validate(['username' => $username])) {
            echo json_encode([
                'success' => false,
                'error' => $validator->getErrors()['username']
            ]);
            exit;
        }
        $playerManager = new PlayerDAO($this->getPdo());
        if (!is_null($playerManager->findByUsername($username))) {
            echo json_encode([
                'success' => false,
                'error' => 'Ce nom d\'utilisateur est déjà pris'
            ]);
            exit;
        }
        $player = $playerManager->findByUuid($_SESSION['uuid']);
        $player->setUsername($username);
        $playerManager->update($player);
        $_SESSION['username'] = $username;
        echo json_encode([
            'success' => true
        ]);
        exit;
    }

    /**
     * @brief Renvoi les informations de profil d'un joueur en JSON
     * @param string $searchBy Le moyen de recherche
     * @param string $data La valeur permettant la recherche
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function getPlayerInformations(string $searchBy, string $data)
    {
        switch ($searchBy) {
            case "uuid":
                $playerManager = new PlayerDAO($this->getPdo());
                $player = $playerManager->findWithDetailByUuid($data);
                $playerArray = $player->toArray();
                echo json_encode($playerArray);
                break;
            case "username":
                $playerManager = new PlayerDAO($this->getPdo());
                $player = $playerManager->findWithDetailByUsername($data);
                $playerArray = $player->toArray();
                echo json_encode($playerArray);
                break;
        }
    }

    /**
     * @brief Permet de mettre à jour l'email d'un joueur
     * @param string $email Le nouvel email
     * @return void
     * @throws DateMalformedStringException
     * @throws RandomException
     */
    public function updateEmail(string $email): void
    {
        $validator = new Validator([
            'email' => [
                'required' => true,
                'type' => 'string',
                'format' => FILTER_VALIDATE_EMAIL
            ]
        ]);

        if (!$validator->validate(['email' => $email])) {
            echo json_encode([
                'success' => false,
                'error' => $validator->getErrors()['email']
            ]);
            exit;
        }

        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);

        if (!is_null($user)) {
            echo json_encode([
                'success' => false,
                'error' => 'Cet e-mail est déjà utilisé'
            ]);
            exit;
        }

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findByUuid($_SESSION['uuid']);
        $user = $userManager->findById($player->getUserId());
        if (is_null($user)) {
            echo json_encode([
                'success' => false,
                'error' => 'Utilisateur non trouvé'
            ]);
            exit;
        }

        
        $emailVerifToken = bin2hex(random_bytes(30));
        $userManager->updateEmail($user->getId(), $email, $emailVerifToken);

        $subject = 'Modification de votre adresse e-mail';
        $message =
            '<p>Confirmer votre nouvelle adresse email.</p>
                <p>Pour pouvoir continuer à jouer et rejoindre nos parties endiablées, il ne vous reste plus qu\'une étape :</p>
                <a href="' . BASE_URL . '/confirm-email/' . urlencode($emailVerifToken) . '">✅ Confirmer votre nouvelle adresse email</a>
                <p>À très bientôt dans l’arène ! 🎲,<br>
                L\'équipe Comus Party 🚀</p>';
        try {
            $confirmMail = new Mailer(array($email), $subject, $message);
            $confirmMail->generateHTMLMessage();
            $confirmMail->send();
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Une erreur est survenue lors de l\'envoi du mail de confirmation'
            ]);
            exit;
        }


        echo json_encode([
            'success' => true
        ]);
        exit;
    }
}