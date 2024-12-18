<?php
/**
 * @file    suggestion.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Suggestion.
 * @date    16/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

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
     * @brief Le constructeur de la classe Suggestion
     * @param string|null $content
     * @param string|null $authorUuid
     */
    public function __construct(
        ?string $content = null,
        ?string $authorUuid = null
    )
    {
        $this->content = $content;
        $this->authorUuid = $authorUuid;
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
}