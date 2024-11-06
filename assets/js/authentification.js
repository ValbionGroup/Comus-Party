

/**
 * Fonction executée lorsque la page est chargée.
 *
 * Elle permet de binder les fonctions de verification des champs
 * de formulaire aux events "input" des inputs correspondants.
 *
 * @return void
 */
window.onload = function() {
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
 * Vérifie si le nom d'utilisateur respecte les exigences spécifiées.
 *
 * La fonction vérifie si le nom d'utilisateur:
 * - a une longueur minimale définie
 * - ne contient pas de caractères spéciaux interdits
 *
 * Si le nom d'utilisateur ne respecte pas ces exigences, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkUsernameRequirements() {
    // Constantes
    const MIN_USERNAME_LENGTH = 3;
    const FORBIDDEN_CHARACTERS = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;
    const USERNAME = document.getElementById("username").value;
    // Variables
    let incorrectUsernameFormat = document.getElementById("incorrectUsernameFormat");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    if (USERNAME.length < MIN_USERNAME_LENGTH) {
        submitButton.disabled = true;
        incorrectUsernameFormat.innerHTML="Le nom d'utilisateur doit contenir au moins " + MIN_USERNAME_LENGTH + " caractères";
        incorrectUsernameFormat.style.display = "block";
    } else if (FORBIDDEN_CHARACTERS.test(USERNAME)) {
        submitButton.disabled = true;
        incorrectUsernameFormat.innerHTML="Le nom d'utilisateur ne doit pas contenir de caractères speciaux";
        incorrectUsernameFormat.style.display = "block";
    } else {
        submitButton.disabled = false;
        incorrectUsernameFormat.style.display = "none";
    }
}


/**
 * Verifie si l'email est valide.
 *
 * La fonction verifie si l'email contient un @.
 *
 * Si l'email ne contient pas de @, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkEmailRequirements() {
    // Constantes
    const EMAIL = document.getElementById("email").value;
    // Variables
    let incorrectEmailFormat = document.getElementById("incorrectEmailFormat");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    if (!EMAIL.includes("@")) {
        submitButton.disabled = true;
        incorrectEmailFormat.innerHTML="L'email doit contenir un @";
        incorrectEmailFormat.style.display = "block";
    } else {
        submitButton.disabled = false;
        incorrectEmailFormat.style.display = "none";
    }
}

/**
 * Verifie si le mot de passe correspond aux critères de sécurité.
 *
 * La fonction verifie si le mot de passe:
 * - contient au moins 8 caracteres
 * - contient au moins une majuscule
 * - contient au moins une minuscule
 * - contient au moins un chiffre
 * - contient au moins un caractère spécial
 *
 * Si le mot de passe ne correspond pas aux critères, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkPasswordRequirements() {
    // Constantes
    const MIN_PASSWORD_LENGTH = 8;
    const UPPERCASE_LETTER = /[A-Z]/;
    const LOWERCASE_LETTER = /[a-z]/;
    const NUMBERS = /\d/;
    const SPECIAL_CHARACTER = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;
    const PASSWORD = document.getElementById("password").value;
    // Variables
    let incorrectPasswordFormat = document.getElementById("incorrectPasswordFormat");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    if (PASSWORD.length < MIN_PASSWORD_LENGTH) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères";
        incorrectPasswordFormat.style.display = "block";
    } else if (!UPPERCASE_LETTER.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins une majuscule";
        incorrectPasswordFormat.style.display = "block";
    } else if (!LOWERCASE_LETTER.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins une minuscule";
        incorrectPasswordFormat.style.display = "block";
    } else if (!NUMBERS.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins un chiffre";
        incorrectPasswordFormat.style.display = "block";
    } else if (!SPECIAL_CHARACTER.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins un caractère special";
        incorrectPasswordFormat.style.display = "block";
    } else {
        submitButton.disabled = false;
        incorrectPasswordFormat.style.display = "none";
    }
}

/**
 * Vérifie si les mots de passe sont identiques.
 *
 * Si le mot de passe de confirmation n'est pas identique au mot de passe,
 * le bouton de soumission est désactivé et un message d'erreur est affiché.
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
    if (CONFIRM_PASSWORD !== PASSWORD) {
        submitButton.disabled = true;
        notMachingPasswords.innerHTML="Les mots de passe ne sont pas identiques";
        notMachingPasswords.style.display = "block";
    } else {
        submitButton.disabled = false;
        notMachingPasswords.style.display = "none";
    }
}



