<?php
/**
 * @file    suggestion.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Suggestion.
 * @date    16/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateTime;

/**
 * @brief Classe Suggestion
 * @details La classe Suggestion répertorie une suggestion effectuée par un utilisateur de la plateforme
 */
class Suggestion
{
    /**
     * @brief Le contenu de la suggestion
     * @var string|null
     */
    private ?string $content;

    /**
     * @brief L'UUID de l'auteur de la suggestion
     * @var string|null
     */
    private ?string $authorUuid;

    /**
     * @brief Le nom d'utilisateur de l'auteur de la suggestion
     * @var string|null
     */
    private ?string $authorUsername;

    /**
     * @brief La date de création de la suggestion
     * @var DateTime|null
     */
    private ?DateTime $createdAt;

    /**
     * @brief L'UUID du modérateur ayant traité la suggestion
     * @var string|null
     */
    private ?string $treatedBy;

    /**
     * @brief Le constructeur de la classe Suggestion
     * @param string|null $content Le contenu de la suggestion
     * @param string|null $authorUuid L'UUID de l'auteur de la suggestion
     * @param string|null $authorUsername Le nom d'utilisateur de l'auteur de la suggestion
     * @param DateTime|null $createdAt La date de création de la suggestion
     * @param string|null $treatedBy L'UUID du modérateur ayant traité la suggestion
     */
    public function __construct(
        ?string   $content = null,
        ?string   $authorUuid = null,
        ?string   $authorUsername = null,
        ?DateTime $createdAt = null,
        ?string   $treatedBy = null
    )
    {
        $this->content = $content;
        $this->authorUuid = $authorUuid;
        $this->authorUsername = $authorUsername;
        $this->createdAt = $createdAt;
        $this->treatedBy = $treatedBy;
    }

    /**
     * @brief Permet de récupérer le contenu de la suggestion
     * @return string|null Le contenu de la suggestion
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @brief Permet de définir le contenu de la suggestion
     * @param string|null $content Le contenu de la suggestion
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @brief Permet de récupérer l'UUID de l'auteur de la suggestion
     * @return string|null L'UUID de l'auteur de la suggestion
     */
    public function getAuthorUuid(): ?string
    {
        return $this->authorUuid;
    }

    /**
     * @brief Permet de définir l'UUID de l'auteur de la suggestion
     * @param string|null $authorUuid L'UUID de l'auteur de la suggestion
     * @return void
     */
    public function setAuthorUuid(?string $authorUuid): void
    {
        $this->authorUuid = $authorUuid;
    }

    /**
     * @brief Permet de récupérer le nom d'utilisateur de l'auteur de la suggestion
     * @return string|null Le nom d'utilisateur de l'auteur de la suggestion
     */
    public function getAuthorUsername(): ?string
    {
        return $this->authorUsername;
    }

    /**
     * @brief Permet de définir le nom d'utilisateur de l'auteur de la suggestion
     * @param string|null $authorUsername Le nom d'utilisateur de l'auteur de la suggestion
     * @return void
     */
    public function setAuthorUsername(?string $authorUsername): void
    {
        $this->authorUsername = $authorUsername;
    }

    /**
     * @brief Permet de récupérer la date de création de la suggestion
     * @return DateTime|null La date de création de la suggestion
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Permet de définir la date de création de la suggestion
     * @param DateTime|null $createdAt La date de création de la suggestion
     * @return void
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Permet de récupérer l'UUID du modérateur ayant traité la suggestion
     * @return string|null L'UUID du modérateur ayant traité la suggestion
     */
    public function getTreatedBy(): ?string
    {
        return $this->treatedBy;
    }

    /**
     * @brief Permet modifier l'UUID du modérateur ayant traité la suggestion
     * @param string|null $treatedBy L'UUID du modérateur ayant traité la suggestion
     * @return void
     */
    public function setTreatedBy(?string $treatedBy): void
    {
        $this->treatedBy = $treatedBy;
    }
}