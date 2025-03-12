<?php
/**
 * @file    Validator.php
 * @brief   Fichier de déclaration et définition de la classe Validator.
 * @date    02/12/2024
 * @version 1.1
 * @author  Lucas ESPIET
 */

namespace ComusParty\App;

/**
 * @brief Classe Validator
 * @details La classe Validator permet de valider les données reçues par les formulaires.
 */
class Validator
{
    /**
     * @details Les règles de validation sont stockées dans un tableau associatif. Les paramètres possibles par champ sont :
     *  - required : booléen indiquant si le champ est obligatoire
     *  - type : type de la valeur attendue (string, integer, numeric)
     *  - format : format de la valeur attendue (expression régulière, FILTER_VALIDATE_EMAIL, FILTER_VALIDATE_URL, FILTER_VALIDATE_IP)
     *  - min-length : longueur minimale de la valeur
     *  - max-length : longueur maximale de la valeur
     *  - exact-length : longueur exacte de la valeur
     *  - min-value : valeur minimale
     *  - max-value : valeur maximale
     *
     * @var array $rules Tableau contenant les règles de validation
     */
    private array $rules;

    /**
     * @var array $errors Tableau contenant les erreurs de validation
     */
    private array $errors;

    /**
     * @brief Constructeur de la classe Validator
     * @param array $rules Tableau associatif contenant les règles de validation pour chaque champ
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @brief La méthode validate permet de valider les données reçues par les formulaires
     * @param array $datas Tableau associatif contenant les données à valider
     * @return bool Retourne true si les données sont valides, false sinon
     */
    public function validate(array $datas): bool
    {
        $valid = true;
        $this->errors = [];

        foreach ($this->rules as $input => $inputRules) {
            $value = $datas[$input] ?? null;

            if (!$this->validateInput($input, $value, $inputRules)) {
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * @brief Valide un champ spécifique en fonction de ses règles
     *
     * @details La méthode validateInput permet de valider un champ spécifique en fonction de ses règles
     * Les règles peuvent être de plusieurs types :
     *  - required : booléen indiquant si le champ est obligatoire
     *  - type : type de la valeur attendue (string, integer, numeric)
     *  - format : format de la valeur attendue (expression régulière, FILTER_VALIDATE_EMAIL, FILTER_VALIDATE_URL, FILTER_VALIDATE_IP)
     *  - min-length : longueur minimale de la valeur
     *  - max-length : longueur maximale de la valeur
     *  - exact-length : longueur exacte de la valeur
     *  - min-value : valeur minimale
     *  - max-value : valeur maximale
     *
     * @param string $input Nom du champ à valider
     * @param mixed $value Valeur du champ à valider
     * @param array $inputRules Tableau contenant les règles de validation pour le champ
     * @return bool Retourne true si le champ est valide, false sinon
     */
    public function validateInput(string $input, mixed $value, array $inputRules): bool
    {
        $valid = true;

        if (isset($inputRules['required']) && $inputRules['required'] && empty($value) && $value !== '0') {
            $this->errors[$input][] = "Le champ $input est obligatoire";
            return false;
        }

        if (empty($value) && (!isset($inputRules['required']) || !$inputRules['required'])) {
            return true;
        }

        foreach ($inputRules as $rule => $ruleValue) {
            switch ($rule) {
                case 'type':
                    if ($ruleValue === 'string' && !is_string($value)) {
                        $this->errors[$input][] = "Doit être une chaîne de caractères";
                        $valid = false;
                    } elseif ($ruleValue === 'integer' && !filter_var($value, FILTER_VALIDATE_INT)) {
                        $this->errors[$input][] = "Doit être un entier";
                        $valid = false;
                    } elseif ($ruleValue === 'numeric' && !is_numeric($value)) {
                        $this->errors[$input][] = "Doit être un nombre";
                        $valid = false;
                    }
                    break;
                case 'format':
                    if (is_string($ruleValue)) {
                        if ($ruleValue[0] !== '/' && $ruleValue[-1] !== '/') {
                            $ruleValue = '/' . $ruleValue . '/';
                        }

                        if (!preg_match($ruleValue, $value)) {
                            $this->errors[$input][] = "Format invalide";
                            $valid = false;
                        }
                    } elseif ($ruleValue === FILTER_VALIDATE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$input][] = "Adresse e-mail invalide";
                        $valid = false;
                    } elseif ($ruleValue === FILTER_VALIDATE_URL && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $this->errors[$input][] = "URL invalide";
                        $valid = false;
                    } elseif ($ruleValue === FILTER_VALIDATE_IP && !filter_var($value, FILTER_VALIDATE_IP)) {
                        $this->errors[$input][] = "Adresse IP invalide";
                        $valid = false;
                    }
                    break;
                case 'min-length':
                    if (strlen($value) < $ruleValue) {
                        $this->errors[$input][] = "Doit contenir au moins $ruleValue caractères";
                        $valid = false;
                    }
                    break;
                case 'max-length':
                    if (strlen($value) > $ruleValue) {
                        $this->errors[$input][] = "Doit contenir au maximum $ruleValue caractères";
                        $valid = false;
                    }
                    break;
                case 'exact-length':
                    if (strlen($value) !== $ruleValue) {
                        $this->errors[$input][] = "Doit contenir exactement $ruleValue caractères";
                        $valid = false;
                    }
                    break;
                case 'min-value':
                    if ($value < $ruleValue) {
                        $this->errors[$input][] = "Doit être supérieur à $ruleValue";
                        $valid = false;
                    }
                    break;
                case 'max-value':
                    if ($value > $ruleValue) {
                        $this->errors[$input][] = "Doit être inférieur à $ruleValue";
                        $valid = false;
                    }
                    break;
            }
        }

        return $valid;
    }

    /**
     * @brief Retourne les messages d'erreurs générés lors de la validation
     * @return array Tableau contenant les messages d'erreurs pour chaque champ non valide
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}