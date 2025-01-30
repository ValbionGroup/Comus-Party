/**
 * @file    authentication.js
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
    INPUT_USERNAME.addEventListener("input", checkConditions);

    const INPUT_EMAIL = document.getElementById("email");
    INPUT_EMAIL.addEventListener("input", checkConditions);

    const INPUT_PASSWORD = document.getElementById("password");
    INPUT_PASSWORD.addEventListener("input", checkConditions);

    const INPUT_CONFIRM_PASSWORD = document.getElementById("passwordConfirm");
    INPUT_CONFIRM_PASSWORD.addEventListener("input", checkConditions);

    const INPUT_TERMS_OF_SERVICE = document.getElementById("termsOfService");
    INPUT_TERMS_OF_SERVICE.addEventListener("change", checkConditions);

    const INPUT_PRIVACY_POLICY = document.getElementById("privacyPolicy");
    INPUT_PRIVACY_POLICY.addEventListener("change", checkConditions);

    addPasswordVisibilityListeners();
}

/**
 * @brief Ajoute des listeners aux boutons permettant de toggler la visibilité des mots de passe
 *        dans le formulaire d'inscription.
 *
 * @details Les boutons sont "togglePassword" et "togglePasswordConfirm" dans le formulaire
 *          d'inscription.
 *
 * @return void
 */
function addPasswordVisibilityListeners()
{
    const INPUT_PASSWORD = document.getElementById("password");
    const TOGGLE_PASSWORD_BUTTON = document.getElementById('togglePassword');
    const EYE_OPEN_ICON = document.getElementById('eyeOpenIcon');
    const EYE_CLOSED_ICON = document.getElementById('eyeClosedIcon');

    const INPUT_CONFIRM_PASSWORD = document.getElementById("passwordConfirm");
    const TOGGLE_PASSWORD_CONFIRM_BUTTON = document.getElementById('togglePasswordConfirm');
    const EYE_OPEN_ICON_CONFIRM = document.getElementById('eyeOpenIconConfirm');
    const EYE_CLOSED_ICON_CONFIRM = document.getElementById('eyeClosedIconConfirm');

    TOGGLE_PASSWORD_BUTTON.addEventListener('click', () => {
        // Toggle visibilité password
        const isPasswordHidden = INPUT_PASSWORD.type === 'password';
        INPUT_PASSWORD.type = isPasswordHidden ? 'text' : 'password';

        // Toggle l'icone
        EYE_OPEN_ICON.classList.toggle('hidden');
        EYE_CLOSED_ICON.classList.toggle('hidden');
    });

    TOGGLE_PASSWORD_CONFIRM_BUTTON.addEventListener('click', () => {
        // Toggle visibilité password
        const isPasswordHidden = INPUT_CONFIRM_PASSWORD.type === 'password';
        INPUT_CONFIRM_PASSWORD.type = isPasswordHidden ? 'text' : 'password';

        // Toggle l'icone
        EYE_OPEN_ICON_CONFIRM.classList.toggle('hidden');
        EYE_CLOSED_ICON_CONFIRM.classList.toggle('hidden');
    });
}

/**
 * @brief Vérifie si toutes les conditions sont remplies pour activer le bouton de soumission.
 *
 * @details La fonction vérifie si tous les champs requis sont remplis et valides,
 * ainsi que si la politique de confidentialité et les conditions d'utilisation sont acceptées.
 *
 * @param {Event} event L'événement déclenché par l'input.
 *
 * @return void
 */
function checkConditions(event) {
    // Variables
    let submitButton = document.getElementById("submitButton");
    let inputUsername = document.getElementById("username");
    let inputEmail = document.getElementById("email");
    let inputPassword = document.getElementById("password");
    let inputConfirmPassword = document.getElementById("passwordConfirm");
    let inputTermsOfService = document.getElementById("termsOfService");
    let inputPrivacyPolicy = document.getElementById("privacyPolicy");

    // Constantes
    const MIN_USERNAME_LENGTH = 3;
    const MAX_USERNAME_LENGTH = 120;
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 64;
    const UPPERCASE_LETTER = /[A-Z]/;
    const LOWERCASE_LETTER = /[a-z]/;
    const NUMBERS = /\d/;
    const SPECIAL_CHARACTER = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;
    const ALLOWED_USERNAME_CHARACTERS = /^[a-zA-Z0-9_-]+$/;

    // Vérifications
    const isUsernameValid = inputUsername.value.length >= MIN_USERNAME_LENGTH && ALLOWED_USERNAME_CHARACTERS.test(inputUsername.value) && inputUsername.value.length <= MAX_USERNAME_LENGTH;
    const isEmailValid = EMAIL_REGEX.test(inputEmail.value);
    const isPasswordValid = inputPassword.value.length >= MIN_PASSWORD_LENGTH &&
                            inputPassword.value.length <= MAX_PASSWORD_LENGTH &&
                            UPPERCASE_LETTER.test(inputPassword.value) &&
                            LOWERCASE_LETTER.test(inputPassword.value) &&
                            NUMBERS.test(inputPassword.value) &&
                            SPECIAL_CHARACTER.test(inputPassword.value);
    const isPasswordMatch = inputPassword.value === inputConfirmPassword.value;
    const isTermsAccepted = inputTermsOfService.checked;
    const isPrivacyPolicyAccepted = inputPrivacyPolicy.checked;

    // Activer le bouton de soumission si toutes les conditions sont remplies
    if (isUsernameValid && isEmailValid && isPasswordValid && isPasswordMatch && isTermsAccepted && isPrivacyPolicyAccepted) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }

    // Met à jour le message d'erreur et les styles des inputs uniquement pour l'input modifié
    const input = event.target;
    if (input.value === "") {
        updateErrorMessage(input, "usernameTooShort", true, "");
        updateErrorMessage(input, "usernameForbiddenCharacters", true, "");
        updateErrorMessage(input, "usernameTooLong", true, "");
        updateErrorMessage(input, "incorrectEmailFormat", true, "");
        updateErrorMessage(input, "passwordTooShort", true, "");
        updateErrorMessage(input, "passwordTooLong", true, "");
        updateErrorMessage(input, "passwordNoUppercase", true, "");
        updateErrorMessage(input, "passwordNoLowercase", true, "");
        updateErrorMessage(input, "passwordNoNumber", true, "");
        updateErrorMessage(input, "passwordNoSpecialCharacter", true, "");
        updateErrorMessage(input, "notMachingPasswords", true, "");
    } else {
        if (input === inputUsername) {
            updateErrorMessage(input, "usernameTooShort", input.value.length >= MIN_USERNAME_LENGTH, "Le nom d'utilisateur doit contenir au moins " + MIN_USERNAME_LENGTH + " caractères");
            updateErrorMessage(input, "usernameForbiddenCharacters", ALLOWED_USERNAME_CHARACTERS.test(input.value), "Le nom d'utilisateur ne doit contenir que des caractères alphanumériques, des tirets et des underscores");
            updateErrorMessage(input, "usernameTooLong", input.value.length <= MAX_USERNAME_LENGTH, "Le nom d'utilisateur doit contenir au plus " + MAX_USERNAME_LENGTH + " caractères");
        } else if (input === inputEmail) {
            updateErrorMessage(input, "incorrectEmailFormat", EMAIL_REGEX.test(input.value), "Format d'email incorrect");
        } else if (input === inputPassword) {
            updateErrorMessage(input, "passwordTooShort", input.value.length >= MIN_PASSWORD_LENGTH, "Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères");
            updateErrorMessage(input, "passwordTooLong", input.value.length <= MAX_PASSWORD_LENGTH, "Le mot de passe doit être au maximum de " + MAX_PASSWORD_LENGTH + " caractères");
            updateErrorMessage(input, "passwordNoUppercase", UPPERCASE_LETTER.test(input.value), "Le mot de passe doit contenir au moins une majuscule");
            updateErrorMessage(input, "passwordNoLowercase", LOWERCASE_LETTER.test(input.value), "Le mot de passe doit contenir au moins une minuscule");
            updateErrorMessage(input, "passwordNoNumber", NUMBERS.test(input.value), "Le mot de passe doit contenir au moins un chiffre");
            updateErrorMessage(input, "passwordNoSpecialCharacter", SPECIAL_CHARACTER.test(input.value), "Le mot de passe doit contenir au moins un caractère spécial");
        } else if (input === inputConfirmPassword) {
            updateErrorMessage(input, "notMachingPasswords", inputPassword.value === input.value, "Les mots de passe ne correspondent pas");
        }
    }
}

/**
 * @brief Met à jour les messages d'erreur et les styles des inputs.
 *
 * @param {HTMLElement} input L'élément input à vérifier.
 * @param {string} errorElementId L'ID de l'élément de message d'erreur.
 * @param {boolean} condition La condition à vérifier.
 * @param {string} errorMessage Le message d'erreur à afficher si la condition n'est pas remplie.
 *
 * @return void
 */
function updateErrorMessage(input, errorElementId, condition, errorMessage) {
    let errorElement = document.getElementById(errorElementId);
    if (!condition) {
        input.classList.add("input-error");
        errorElement.classList.add("block");
        errorElement.classList.remove("hidden");
        errorElement.innerHTML = errorMessage;
    } else {
        input.classList.remove("input-error");
        errorElement.classList.remove("block");
        errorElement.classList.add("hidden");
        errorElement.innerHTML = "";
    }
}