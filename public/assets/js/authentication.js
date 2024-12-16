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

    const INPUT_TERMS_OF_SERVICE = document.getElementById("termsOfService");
    INPUT_TERMS_OF_SERVICE.addEventListener("change", checkTermsOfService);

    const INPUT_PRIVACY_POLICY = document.getElementById("privacyPolicy");
    INPUT_PRIVACY_POLICY.addEventListener("change", checkPrivacyPolicy);
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
    let inputUsername = document.getElementById('username');
    let usernameTooShort = document.getElementById("usernameTooShort");
    let usernameForbiddenCharacters = document.getElementById("usernameForbiddenCharacters");
    let submitButton = document.getElementById("submitButton");

    // Vérifications
    // Le nom d'utilisateur doit contenir au moins 3 caractères
    if (USERNAME.length < MIN_USERNAME_LENGTH) {
        submitButton.classList.add("disabled");
        inputUsername.classList.add("input-error");
        usernameTooShort.classList.add("block");
        usernameTooShort.classList.remove("hidden");
        usernameTooShort.innerHTML="Le nom d'utilisateur doit contenir au moins " + MIN_USERNAME_LENGTH + " caractères";
    } else {
        inputUsername.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        usernameTooShort.classList.remove("block");
        usernameTooShort.classList.add("hidden");
    }

    // Le nom d'utilisateur ne doit pas contenir de caractères speciaux
    if (FORBIDDEN_CHARACTERS.test(USERNAME)) {
        submitButton.classList.add("disabled");
        inputUsername.classList.add("input-error");
        usernameForbiddenCharacters.classList.add("block");
        usernameForbiddenCharacters.classList.remove("hidden");
    } else {
        inputUsername.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        usernameForbiddenCharacters.classList.remove("block");
        usernameForbiddenCharacters.classList.add("hidden");
    }

    // Tout est bon, le bouton de soumission est activé
    if (!USERNAME.length < MIN_USERNAME_LENGTH && !FORBIDDEN_CHARACTERS.test(USERNAME))
    { submitButton.classList.remove("disabled"); }
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
    let inputEmail = document.getElementById('email');
    let incorrectEmailFormat = document.getElementById("incorrectEmailFormat");
    let submitButton = document.getElementById("submitButton");
    
    // Vérifications
    if (!EMAIL_REGEX.test(EMAIL)) {
        submitButton.classList.add("disabled");
        inputEmail.classList.add("input-error");
        incorrectEmailFormat.classList.add("block");
        incorrectEmailFormat.classList.remove("hidden");
    } else {
        inputEmail.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        incorrectEmailFormat.classList.remove("block");
        incorrectEmailFormat.classList.add("hidden");
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
    let inputPassword = document.getElementById('password');
    let passwordTooShort = document.getElementById("passwordTooShort");
    let passwordNoUppercase = document.getElementById("passwordNoUppercase");
    let passwordNoLowercase = document.getElementById("passwordNoLowercase");
    let passwordNoNumber = document.getElementById("passwordNoNumber");
    let passwordNoSpecialCharacter = document.getElementById("passwordNoSpecialCharacter");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    // Le mot de passe doit contenir au moins 8 caractères
    if (PASSWORD.length < MIN_PASSWORD_LENGTH) {
        submitButton.classList.add("disabled");
        inputPassword.classList.add("input-error");
        passwordTooShort.classList.add("block");
        passwordTooShort.classList.remove("hidden");
        passwordTooShort.innerHTML="Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères";
    } else {
        inputPassword.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        passwordTooShort.classList.remove("block");
        passwordTooShort.classList.add("hidden");
    }

    // Le mot de passe doit contenir au moins une majuscule
    if (!UPPERCASE_LETTER.test(PASSWORD)) {
        submitButton.classList.add("disabled");
        inputPassword.classList.add("input-error");
        passwordNoUppercase.classList.add("block");
        passwordNoUppercase.classList.remove("hidden");
    } else {
        inputPassword.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        passwordNoUppercase.classList.remove("block");
        passwordNoUppercase.classList.add("hidden");
    }

    // Le mot de passe doit contenir au moins une minuscule
    if (!LOWERCASE_LETTER.test(PASSWORD)) {
        submitButton.classList.add("disabled");
        inputPassword.classList.add("input-error");
        passwordNoLowercase.classList.add("block");
        passwordNoLowercase.classList.remove("hidden");
    } else {
        inputPassword.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        passwordNoLowercase.classList.remove("block");
        passwordNoLowercase.classList.add("hidden");
    }

    // Le mot de passe doit contenir au moins un chiffre
    if (!NUMBERS.test(PASSWORD)) {
        submitButton.classList.add("disabled");
        inputPassword.classList.add("input-error");
        passwordNoNumber.classList.add("block");
        passwordNoNumber.classList.remove("hidden");
    } else {
        inputPassword.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        passwordNoNumber.classList.remove("block");
        passwordNoNumber.classList.add("hidden");
    }

    // Le mot de passe doit contenir au moins un caractère special
    if (!SPECIAL_CHARACTER.test(PASSWORD)) {
        submitButton.classList.add("disabled");
        inputPassword.classList.add("input-error");
        passwordNoSpecialCharacter.classList.add("block");
        passwordNoSpecialCharacter.classList.remove("hidden");
    } else {
        inputPassword.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        passwordNoSpecialCharacter.classList.remove("block");
        passwordNoSpecialCharacter.classList.add("hidden");
    }

    // Tout est bon, le bouton de soumission est activé
    if (PASSWORD.length >= MIN_PASSWORD_LENGTH &&
        UPPERCASE_LETTER.test(PASSWORD) &&
        LOWERCASE_LETTER.test(PASSWORD) &&
        NUMBERS.test(PASSWORD) &&
        SPECIAL_CHARACTER.test(PASSWORD))
        { submitButton.classList.remove("disabled"); }
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
    let inputPassword = document.getElementById('password');
    let notMachingPasswords = document.getElementById("notMachingPasswords");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    if (!(CONFIRM_PASSWORD == PASSWORD)) {
        submitButton.classList.add("disabled");
        inputPassword.classList.add("input-error");
        notMachingPasswords.classList.add("block");
        notMachingPasswords.classList.remove("hidden");
    } else {
        inputPassword.classList.remove("input-error");
        submitButton.classList.remove("disabled");
        notMachingPasswords.classList.remove("block");
        notMachingPasswords.classList.add("hidden");
    }

    // Tout est bon, le bouton de soumission est activé
    if(CONFIRM_PASSWORD == PASSWORD)
    { submitButton.classList.remove("disabled"); }
}

function checkTermsOfService() {
    // Variables
    let submitButton = document.getElementById("submitButton");
    let inputTermsOfService = document.getElementById("termsOfService");

    // Vérifications
    if (inputTermsOfService.checked) {
        submitButton.classList.remove("disabled");
    } else {
        submitButton.classList.add("disabled");
    }
}

function checkPrivacyPolicy() {
    // Variables
    let submitButton = document.getElementById("submitButton");
    let inputPrivacyPolicy = document.getElementById("privacyPolicy");

    // Vérifications
    if (inputPrivacyPolicy.checked) {
        submitButton.classList.remove("disabled");
    } else {
        submitButton.classList.add("disabled");
    }
}