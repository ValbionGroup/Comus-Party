<?php
/**
 * @file    ControllerProfile.class.php
 * @brief   Fichier de déclaration et définition de la classe ControllerProfile
 * @author  Estéban DESESSARD
 * @date    15/11/2024
 * @version 0.2
 */

namespace ComusParty\Controllers;

use ComusParty\App\Exceptions\AuthenticationException;
use ComusParty\App\Exceptions\ControllerNotFoundException;
use ComusParty\App\Exceptions\MethodNotFoundException;
use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\App\Exceptions\UnauthorizedAccessException;
use ComusParty\App\Mailer;
use ComusParty\App\MessageHandler;
use ComusParty\App\Validator;
use ComusParty\Models\ArticleDAO;
use ComusParty\Models\Penalty;
use ComusParty\Models\PenaltyDAO;
use ComusParty\Models\PenaltyType;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\Report;
use ComusParty\Models\ReportDAO;
use ComusParty\Models\ReportObject;
use ComusParty\Models\UserDAO;
use DateMalformedStringException;
use DateTime;
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
     * @param string|null $player_uuid
     * @return void
     * @throws DateMalformedStringException
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws NotFoundException Exception levée dans le cas d'une erreur de syntaxe
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
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
        $template = $this->getTwig()->load('player/profile.twig');
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
     * @throws ControllerNotFoundException Exception levée dans le cas où le contrôleur n'est pas trouvé
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas où une erreur de chargement survient
     * @throws MethodNotFoundException Exception levée dans le cas où la méthode n'est pas trouvée
     * @throws NotFoundException Exception levée dans le cas où le joueur n'est pas trouvé
     * @throws RuntimeError Exception levée dans le cas où une erreur d'exécution survient
     * @throws SyntaxError Exception levée dans le cas où une erreur de syntaxe survient
     * @throws UnauthorizedAccessException Exception levée dans le cas où l'utilisateur n'est pas autorisé à effectuer cette action
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
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws NotFoundException Exception levée dans le cas où le joueur n'est pas trouvé
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
            echo MessageHandler::sendJsonMessage("Photo de profil mise à jour avec succès", [
                'articlePath' => "default-pfp.jpg",
            ]);
            exit;
        }
        if ($idArticle == -1) {
            $articleManager->deleteActiveArticleForBanner($player->getUuid());
            $_SESSION['bannerPath'] = "default-banner.jpg";
            echo MessageHandler::sendJsonMessage("Bannière mise à jour avec succès", [
                'articlePath' => "default-banner.jpg",
            ]);
            exit;
        }
        if ($idArticle != 0 && $idArticle != -1) {
            $articleManager->updateActiveArticle($player->getUuid(), $idArticle, $typeArticle);
            $article = $articleManager->findById($idArticle);
            echo MessageHandler::sendJsonMessage("Style mis à jour avec succès", [
                'articlePath' => $article->getFilePath(),
                'idArticle' => $idArticle
            ]);
            exit;
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
                'max-length' => 25,
                'type' => 'string',
                'format' => '/^[a-zA-Z0-9_-]+$/'
            ]
        ]);
        if (!$validator->validate(['username' => $username])) {
            MessageHandler::sendJsonCustomException(400, $validator->getErrors()['username']);
        }
        $playerManager = new PlayerDAO($this->getPdo());
        if (!is_null($playerManager->findByUsername($username))) {
            MessageHandler::sendJsonCustomException(400, 'Ce nom d\'utilisateur est déjà pris');
        }
        $player = $playerManager->findByUuid($_SESSION['uuid']);
        $player->setUsername($username);
        $playerManager->update($player);
        $_SESSION['username'] = $username;
        echo MessageHandler::sendJsonMessage("Nom d'utilisateur mis à jour avec succès");
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
                echo MessageHandler::sendJsonMessage("Informations du joueur récupérées avec succès", [
                    'player' => $playerArray
                ]);
                break;
            case "username":
                $playerManager = new PlayerDAO($this->getPdo());
                $player = $playerManager->findWithDetailByUsername($data);
                $playerArray = $player->toArray();
                echo MessageHandler::sendJsonMessage("Informations du joueur récupérées avec succès", [
                    'player' => $playerArray
                ]);
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
            MessageHandler::sendJsonCustomException(400, $validator->getErrors()['email']);
        }

        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);

        if (!is_null($user)) {
            MessageHandler::sendJsonCustomException(400, 'Cet e-mail est déjà utilisé');
        }

        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findByUuid($_SESSION['uuid']);
        $user = $userManager->findById($player->getUserId());
        if (is_null($user)) {
            MessageHandler::sendJsonCustomException(500, 'Utilisateur non trouvé');
        }


        $emailVerifToken = bin2hex(random_bytes(30));
        $user->setEmail($email);
        $user->setEmailVerifyToken($emailVerifToken);
        $user->setEmailVerifiedAt(null);
        $userManager->update($user);

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
            MessageHandler::sendJsonCustomException(500, 'Erreur lors de l\'envoi du mail de confirmation');
        }

        echo MessageHandler::sendJsonMessage("Email mis à jour avec succès");
        exit;
    }


    /**
     * @brief Permet de modifier le mot de passe d'un utilisateur et lui envoie un mail pour lui confirmer
     * @param string $newPassword
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws AuthenticationException Exception levée dans le cas d'une erreur d'authentification
     * @throws Exception Exception levée dans le cas d'une erreur
     */
    public function editPassword(string $newPassword): void
    {
        $userManager = new UserDAO($this->getPdo());
        $playerManager = new PlayerDAO($this->getPdo());
        $user = $userManager->findById($playerManager->findByUuid($_SESSION['uuid'])->getUserId());
        $rules = [
            'password' => [
                'required' => true,
                'type' => 'string',
                'min-length' => 8,
                'max-length' => 120,
                'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?_&^#])[A-Za-z\d@$!%*?&^_#]{8,}$/'
            ]
        ];

        $validator = new Validator($rules);

        if (!$validator->validate(['password' => $newPassword])) {
            throw new AuthenticationException("Mot de passe invalide");
        }


        if (password_verify($newPassword, $user->getPassword())) {
            MessageHandler::sendJsonCustomException(400, 'Le nouveau mot de passe ne peut pas être identique à l\'ancien');
        }
        $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->setPassword($newPasswordHashed);
        if (!$userManager->update($user)) {
            throw new Exception("Erreur lors de la mise à jour du mot de passe", 500);
        } else {
            try {
                $to = $userManager->findById($playerManager->findByUuid($_SESSION['uuid'])->getUserId())->getEmail();
                $subject = 'Modification de mot-de-passe';
                $message = '<p>Vous venez de modifier votre mot de passe sur Comus Party !</p>';
                $mailer = new Mailer([$to], $subject, $message);
                $mailer->generateHTMLMessage();
                $mailer->send();
                echo MessageHandler::sendJsonMessage("Mot de passe modifié avec succès");
                exit;
            } catch (Exception $e) {
                MessageHandler::sendJsonException($e);
            }

        }
    }

    /**
     * @brief Permet de pénaliser un joueur
     * @param string $createdBy L'UUID de l'utilisateur ayant créé la sanction
     * @param string $penalizedUuid L'UUID du joueur pénalisé
     * @param string $reason La raison de la sanction
     * @param int $duration La durée de la sanction
     * @param string $durationType Le type de durée de la sanction
     * @param PenaltyType $penaltyType Le type de sanction
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function penalizePlayer(string $createdBy, string $penalizedUuid, string $reason, int $duration, string $durationType, PenaltyType $penaltyType, string $reportId)
    {

        $duration = match ($durationType) {
            'minutes' => $duration * 60,
            'hours' => $duration * 3600,
            'days' => $duration * 86400,
            'months' => $duration * 2628000,
            'years' => $duration * 31536000,
            default => null,
        };

        $penaltyManager = new PenaltyDAO($this->getPdo());

        $lastPenalty = $penaltyManager->findLastPenaltyByPlayerUuid($penalizedUuid);
        if (isset($lastPenalty) && ($lastPenalty->getDuration() >= $duration || $lastPenalty->getDuration() == null)) {
            MessageHandler::sendJsonCustomException(500, 'Le joueur a déjà une sanction plus longue ou égale à celle-ci');
        }

        $penalty = new Penalty();
        $penalty->setCreatedBy($createdBy);
        $penalty->setPenalizedUuid($penalizedUuid);
        $penalty->setReason($reason);
        $penalty->setDuration($duration);
        $penalty->setType($penaltyType);
        $penalty->setCreatedAt(new DateTime());
        $penalty->setUpdatedAt(new DateTime());
        if (!$penaltyManager->createPenalty($penalty)) {
            MessageHandler::sendJsonCustomException(500, 'Erreur lors de la création de la sanction');
        }

        if ($penaltyType == PenaltyType::BANNED) {
            $playerManager = new PlayerDAO($this->getPdo());
            $player = $playerManager->findByUuid($penalizedUuid);
            $userManager = new UserDAO($this->getPdo());
            $user = $userManager->findById($player->getUserId());
            $user->setDisabled(1);
            $userManager->update($user);
        }

        $reportManager = new ReportDAO($this->getPdo());
        $report = $reportManager->findById($reportId);

        $report->setTreatedBy($createdBy);

        $reportManager->update($report);

        echo MessageHandler::sendJsonMessage("Sanction appliquée avec succès");
        exit;
    }

    /**
     * @brief Permet de signaler un joueur
     * @param ReportObject $object L'objet du signalement
     * @param string $description La description du signalement
     * @param string $reportedUuid L'UUID du joueur signalé
     * @param string $senderUuid L'UUID du joueur ayant effectué le signalement
     * @return void
     */
    public function reportPlayer(ReportObject $object, string $description, string $reportedUuid, string $senderUuid)
    {
        $reportManager = new ReportDAO($this->getPdo());
        $report = new Report();
        $report->setObject($object);
        $report->setDescription($description);
        $report->setReportedUuid($reportedUuid);
        $report->setSenderUuid($senderUuid);
        $report->setCreatedAt(new DateTime());
        $report->setUpdatedAt(new DateTime());
        if (!$reportManager->insert($report)) {
            MessageHandler::sendJsonCustomException(500, 'Erreur lors de la création du signalement');
        }
        echo MessageHandler::sendJsonMessage("Signalement envoyé avec succès");
        exit;
    }
}