/**
 * @file    sign-up.js
 * @author  Enzo HAMID et Estéban DESESSARD
 * @brief   Le fichier contient les fonctions de vérification des champs de formulaire
 *          de l'authentification.
 * @date    14/11/2024
 * @version 2.0
 */

const INPUT_USERNAME = document.getElementById("username");
const INPUT_EMAIL = document.getElementById("email");
const INPUT_PASSWORD = document.getElementById("password");
const INPUT_CONFIRM_PASSWORD = document.getElementById("passwordConfirm");
const INPUT_TERMS_OF_SERVICE = document.getElementById("termsOfService");
const INPUT_PRIVACY_POLICY = document.getElementById("privacyPolicy");
const TOGGLE_PASSWORD_BUTTON = document.getElementById('togglePassword');
const EYE_OPEN_ICON = document.getElementById('eyeOpenIcon');
const EYE_CLOSED_ICON = document.getElementById('eyeClosedIcon');
const TOGGLE_PASSWORD_CONFIRM_BUTTON = document.getElementById('togglePasswordConfirm');
const EYE_OPEN_ICON_CONFIRM = document.getElementById('eyeOpenIconConfirm');
const EYE_CLOSED_ICON_CONFIRM = document.getElementById('eyeClosedIconConfirm');
const SUBMIT_BUTTON = document.getElementById("submitButton");


/**
 * @brief Fonction executée lorsque la page est chargée.
 *
 * @details Elle permet de binder les fonctions de verification des champs
 * de formulaire aux events "input" des inputs correspondants.
 *
 * @return void
 */
window.onload = function () {
    // Ajoute un listener sur l'event "input" des inputs
    INPUT_USERNAME.addEventListener("input", checkConditions);
    INPUT_EMAIL.addEventListener("input", checkConditions);
    INPUT_PASSWORD.addEventListener("input", checkConditions);
    INPUT_CONFIRM_PASSWORD.addEventListener("input", checkConditions);
    INPUT_TERMS_OF_SERVICE.addEventListener("change", checkConditions);
    INPUT_PRIVACY_POLICY.addEventListener("change", checkConditions);

    addPasswordVisibilityListeners();

    document.validateInputs = {
        usernameState: false,
        emailState: false,
        passwordState: false,
        passwordMatch: false,
        termsAccepted: false,
        privacyPolicyAccepted: false
    }
    document.validateButton = SUBMIT_BUTTON;
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
function addPasswordVisibilityListeners() {
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
    // Constantes
    const MIN_USERNAME_LENGTH = 3;
    const MAX_USERNAME_LENGTH = 25;
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 120;
    const UPPERCASE_LETTER = /[A-Z]/;
    const LOWERCASE_LETTER = /[a-z]/;
    const NUMBERS = /\d/;
    const SPECIAL_CHARACTER = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;
    const ALLOWED_USERNAME_CHARACTERS = /^[a-zA-Z0-9_-]+$/;

    // Vérifications
    const isUsernameValid = INPUT_PASSWORD.value.length >= MIN_USERNAME_LENGTH && ALLOWED_USERNAME_CHARACTERS.test(INPUT_USERNAME.value) && INPUT_USERNAME.value.length <= MAX_USERNAME_LENGTH;
    const isEmailValid = EMAIL_REGEX.test(INPUT_EMAIL.value);
    const isPasswordValid = INPUT_PASSWORD.value.length >= MIN_PASSWORD_LENGTH &&
        INPUT_PASSWORD.value.length <= MAX_PASSWORD_LENGTH &&
        UPPERCASE_LETTER.test(INPUT_PASSWORD.value) &&
        LOWERCASE_LETTER.test(INPUT_PASSWORD.value) &&
        NUMBERS.test(INPUT_PASSWORD.value) &&
        SPECIAL_CHARACTER.test(INPUT_PASSWORD.value);
    const isPasswordMatch = INPUT_PASSWORD.value === INPUT_CONFIRM_PASSWORD.value;
    const isTermsAccepted = INPUT_TERMS_OF_SERVICE.checked;
    const isPrivacyPolicyAccepted = INPUT_PRIVACY_POLICY.checked;

    // TODO: A modifier ! L'entièreté du fichier doit être revue pour être conforme à la structure du projet.
    document.validateInputs = {
        usernameState: isUsernameValid,
        emailState: isEmailValid,
        passwordState: isPasswordValid,
        passwordMatch: isPasswordMatch,
        termsAccepted: isTermsAccepted,
        privacyPolicyAccepted: isPrivacyPolicyAccepted
    }
    document.validateButton = SUBMIT_BUTTON;

    // Activer le bouton de soumission si toutes les conditions sont remplies
    changeButtonState(SUBMIT_BUTTON, Object.values(document.validateInputs), true);

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
        updateErrorMessage(input, "notMatchingPasswords", true, "");
    } else {
        if (input === INPUT_USERNAME) {
            updateErrorMessage(input, "usernameTooShort", input.value.length >= MIN_USERNAME_LENGTH, "Le nom d'utilisateur doit contenir au moins " + MIN_USERNAME_LENGTH + " caractères");
            updateErrorMessage(input, "usernameForbiddenCharacters", ALLOWED_USERNAME_CHARACTERS.test(input.value), "Le nom d'utilisateur ne doit contenir que des caractères alphanumériques, des tirets et des underscores");
            updateErrorMessage(input, "usernameTooLong", input.value.length <= MAX_USERNAME_LENGTH, "Le nom d'utilisateur doit contenir au plus " + MAX_USERNAME_LENGTH + " caractères");
        } else if (input === INPUT_EMAIL) {
            updateErrorMessage(input, "incorrectEmailFormat", EMAIL_REGEX.test(input.value), "Format d'email incorrect");
        } else if (input === INPUT_PASSWORD) {
            updateErrorMessage(input, "passwordTooShort", input.value.length >= MIN_PASSWORD_LENGTH, "Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères");
            updateErrorMessage(input, "passwordTooLong", input.value.length <= MAX_PASSWORD_LENGTH, "Le mot de passe doit être au maximum de " + MAX_PASSWORD_LENGTH + " caractères");
            updateErrorMessage(input, "passwordNoUppercase", UPPERCASE_LETTER.test(input.value), "Le mot de passe doit contenir au moins une majuscule");
            updateErrorMessage(input, "passwordNoLowercase", LOWERCASE_LETTER.test(input.value), "Le mot de passe doit contenir au moins une minuscule");
            updateErrorMessage(input, "passwordNoNumber", NUMBERS.test(input.value), "Le mot de passe doit contenir au moins un chiffre");
            updateErrorMessage(input, "passwordNoSpecialCharacter", SPECIAL_CHARACTER.test(input.value), "Le mot de passe doit contenir au moins un caractère spécial");
        } else if (input === INPUT_CONFIRM_PASSWORD) {
            updateErrorMessage(input, "notMatchingPasswords", INPUT_PASSWORD.value === input.value, "Les mots de passe ne correspondent pas");
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

function signUp(e) {
    loading(e);
    const cfToken = turnstile.getResponse();
    makeRequest('POST', '/register', (response) => {
        response = JSON.parse(response);
        if (response.success) {
            e.innerHTML = "Valider";
            e.classList.add("btn-disabled");
            e.classList.remove("btn-primary");
            showNotification("Inscription réussie", response.message, "green");
            setTimeout(() => {
                window.location.href = "/login";
            }, 3500);
        } else {
            e.innerHTML = "Valider";
            e.classList.remove("btn-disabled");
            e.classList.add("btn-primary");
            e.disabled = false;
            showNotification("Oups...", response.message, "red");
            turnstileExpiredCallback();
        }
    }, `username=${INPUT_USERNAME.value}&email=${INPUT_EMAIL.value}&password=${encodeURIComponent(INPUT_PASSWORD.value)}&passwordConfirm=${encodeURIComponent(INPUT_CONFIRM_PASSWORD.value)}&termsOfService=${INPUT_TERMS_OF_SERVICE.checked}&privacyPolicy=${INPUT_PRIVACY_POLICY.checked}&cfToken=${cfToken}`);
}