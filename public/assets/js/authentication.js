/**
 * @file    authentication.js
 * @author  Enzo HAMID
 * @brief   Le fichier contient les fonctions de vérification des champs de formulaire
 *          de l'authentification.
 * @date    14/11/2024
 * @version 1.0
 */



/**
 * @brief Fonction executée lorsque la page est chargée.
 *
 * @details Elle permet de binder les fonctions de verification des champs
 * de formulaire aux events "input" des inputs correspondants.
 *
 * @return void
 */
window.onload = function() {
    // Récupère les inputs du formulaire
    // Ajoute un listener sur l'event "input" des inputs
    const INPUT_USERNAME = document.getElementById("username");
    INPUT_USERNAME.addEventListener("input", checkUsernameRequirements);

    const INPUT_EMAIL = document.getElementById("email");
    INPUT_EMAIL.addEventListener("input", checkEmailRequirements);

    const INPUT_PASSWORD = document.getElementById("password");
    INPUT_PASSWORD.addEventListener("input", checkPasswordRequirements);

    const INPUT_CONFIRM_PASSWORD = document.getElementById("passwordConfirm");
    INPUT_CONFIRM_PASSWORD.addEventListener("input", checkPasswordsMatch);
}


/**
 * @brief Vérifie si le nom d'utilisateur entré par l'utilisateur contient 
 *        au moins 3 caractères et ne contient pas de caractères spéciaux.
 *
 * @details La fonction est appelée lorsque l'utilisateur écrit dans le champ
 * de formulaire "nom d'utilisateur".
 * Elle vérifie si le nom d'utilisateur contient au moins 3 caractères
 * et si le nom d'utilisateur ne contient pas de caractères spéciaux.
 * Si le nom d'utilisateur contient moins de 3 caractères, le bouton
 * de soumission est désactivé et un message d'erreur est affiché.
 * Si le nom d'utilisateur contient des caractères spéciaux, le bouton
 * de soumission est désactivé et un message d'erreur est affiché.
 * Si le nom d'utilisateur est valide, le bouton de soumission est activé.
 *
 * @return void
 */
function checkUsernameRequirements() {
    // Constantes
    const MIN_USERNAME_LENGTH = 3; // Longueur minimale du nom d'utilisateur
    const FORBIDDEN_CHARACTERS = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/; // Caractères interdits
    const USERNAME = document.getElementById("username").value; // Nom d'utilisateur
    // Variables
    let usernameTooShort = document.getElementById("usernameTooShort");
    let usernameForbiddenCharacters = document.getElementById("usernameForbiddenCharacters");
    let submitButton = document.getElementById("submitButton");

    // Vérifications
    // Le nom d'utilisateur doit contenir au moins 3 caractères
    if (USERNAME.length < MIN_USERNAME_LENGTH) {
        submitButton.disabled = true;
        usernameTooShort.innerHTML="Le nom d'utilisateur doit contenir au moins " + MIN_USERNAME_LENGTH + " caractères";
        usernameTooShort.style.display = "block";
    } else { usernameTooShort.style.display = "none"; }

    // Le nom d'utilisateur ne doit pas contenir de caractères speciaux
    if (FORBIDDEN_CHARACTERS.test(USERNAME)) {
        submitButton.disabled = true;
        usernameForbiddenCharacters.innerHTML="Le nom d'utilisateur ne doit pas contenir de caractères speciaux";
        usernameForbiddenCharacters.style.display = "block";
    } else { usernameForbiddenCharacters.style.display = "none"; }

    // Tout est bon, le bouton de soumission est activé
    if (!USERNAME.length < MIN_USERNAME_LENGTH && !FORBIDDEN_CHARACTERS.test(USERNAME))
    { submitButton.disabled = false; }
}

/**
 * @brief Vérifie si l'email respecte les exigences spécifiées.
 *
 * @details La fonction vérifie si l'email:
 * - a un format valide
 * Si l'email ne respecte pas ces exigences, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkEmailRequirements() {
    // Constantes
    const EMAIL = document.getElementById("email").value; // Email
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex pour valider le format de l'email
    // Variables
    let incorrectEmailFormat = document.getElementById("incorrectEmailFormat");
    let submitButton = document.getElementById("submitButton");
    
    // Vérifications
    if (!EMAIL_REGEX.test(EMAIL)) {
        submitButton.disabled = true;
        incorrectEmailFormat.innerHTML = "Format d'email invalide, veuillez entrer un email valide.";
        incorrectEmailFormat.style.display = "block";
    } else {
        submitButton.disabled = false;
        incorrectEmailFormat.style.display = "none";
    }
}


/**
 * @brief Vérifie si le mot de passe respecte les exigences spécifiées.
 *
 * @details La fonction vérifie si le mot de passe:
 * - a une longueur minimale de 8 caractères
 * - contient au moins une majuscule
 * - contient au moins une minuscule
 * - contient au moins un chiffre
 * - contient au moins un caractère special
 * Si le mot de passe ne respecte pas ces exigences, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkPasswordRequirements() {
    // Constantes
    const MIN_PASSWORD_LENGTH = 8; // Longueur minimale du mot de passe
    const UPPERCASE_LETTER = /[A-Z]/; // Majuscule
    const LOWERCASE_LETTER = /[a-z]/; // Minuscule
    const NUMBERS = /\d/; // Chiffre
    const SPECIAL_CHARACTER = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/; // Caractère special
    const PASSWORD = document.getElementById("password").value; // Mot de passe
    // Variables
    let passwordTooShort = document.getElementById("passwordTooShort");
    let passwordNoUppercase = document.getElementById("passwordNoUppercase");
    let passwordNoLowercase = document.getElementById("passwordNoLowercase");
    let passwordNoNumber = document.getElementById("passwordNoNumber");
    let passwordNoSpecialCharacter = document.getElementById("passwordNoSpecialCharacter");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    // Le mot de passe doit contenir au moins 8 caractères
    if (PASSWORD.length < MIN_PASSWORD_LENGTH) {
        submitButton.disabled = true;
        passwordTooShort.innerHTML="Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères";
        passwordTooShort.style.display = "block";
    } else { passwordTooShort.style.display = "none"; }

    // Le mot de passe doit contenir au moins une majuscule
    if (!UPPERCASE_LETTER.test(PASSWORD)) {
        submitButton.disabled = true;
        passwordNoUppercase.innerHTML="Le mot de passe doit contenir au moins une majuscule";
        passwordNoUppercase.style.display = "block";
    } else { passwordNoUppercase.style.display = "none"; }

    // Le mot de passe doit contenir au moins une minuscule
    if (!LOWERCASE_LETTER.test(PASSWORD)) {
        submitButton.disabled = true;
        passwordNoLowercase.innerHTML="Le mot de passe doit contenir au moins une minuscule";
        passwordNoLowercase.style.display = "block";
    } else { passwordNoLowercase.style.display = "none"; }

    // Le mot de passe doit contenir au moins un chiffre
    if (!NUMBERS.test(PASSWORD)) {
        submitButton.disabled = true;
        passwordNoNumber.innerHTML="Le mot de passe doit contenir au moins un chiffre";
        passwordNoNumber.style.display = "block";
    } else { passwordNoNumber.style.display = "none"; }

    // Le mot de passe doit contenir au moins un caractère special
    if (!SPECIAL_CHARACTER.test(PASSWORD)) {
        submitButton.disabled = true;
        passwordNoSpecialCharacter.innerHTML="Le mot de passe doit contenir au moins un caractère special";
        passwordNoSpecialCharacter.style.display = "block";
    } else { passwordNoSpecialCharacter.style.display = "none"; }

    // Tout est bon, le bouton de soumission est activé
    if (PASSWORD.length >= MIN_PASSWORD_LENGTH &&
        UPPERCASE_LETTER.test(PASSWORD) &&
        LOWERCASE_LETTER.test(PASSWORD) &&
        NUMBERS.test(PASSWORD) &&
        SPECIAL_CHARACTER.test(PASSWORD))
        { submitButton.disabled = false; }
}


/**
 * @brief Vérifie si les mots de passe entrés sont identiques.
 *
 * @details La fonction vérifie si le mot de passe et la confirmation du mot de passe
 * sont identiques. Si les mots de passe ne sont pas identiques, le bouton de soumission
 * est désactivé et un message d'erreur est affiché. Si les mots de passe sont identiques,
 * le bouton de soumission est activé.
 *
 * @return void
 */
function checkPasswordsMatch() {
    // Constantes
    const PASSWORD = document.getElementById("password").value;
    const CONFIRM_PASSWORD = document.getElementById("passwordConfirm").value;
    // Variables
    let notMachingPasswords = document.getElementById("notMachingPasswords");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    if (!(CONFIRM_PASSWORD == PASSWORD)) {
        submitButton.disabled = true;
        notMachingPasswords.innerHTML="Les mots de passe ne sont pas identiques";
        notMachingPasswords.style.display = "block";
    } else { notMachingPasswords.style.display = "none"; }

    // Tout est bon, le bouton de soumission est activé
    if(CONFIRM_PASSWORD == PASSWORD)
    { submitButton.disabled = false; }
}
