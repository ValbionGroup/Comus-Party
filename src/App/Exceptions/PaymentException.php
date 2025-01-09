<?php
/**
 * @brief Gère les exceptions relatives au paiement
 * @file PaymentException.php
 * @author Estéban DESESSARD
 * @version 1.1
 * @date 2024-11-28
 */


namespace ComusParty\App\Exceptions;

use Exception;
use Throwable;

/**
 * @brief Classe PaymentException
 * @details La classe PaymentException permet de gérer les exceptions liées au paiement
 */
class PaymentException extends Exception
{
    /**
     * @brief Constructeur de la classe PaymentException
     * @param string $message Message d'erreur
     * @param int $code Code d'erreur
     * @param Throwable|null $previous Exception précédente
     */
    public function __construct(string $message = "", int $code = 402, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @brief Retourne une chaîne de caractère affichant le message d'erreur
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}