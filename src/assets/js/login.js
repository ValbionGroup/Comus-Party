/**
 * @file    login.js
 * @author  Lucas ESPIET et Estéban DESESSARD
 * @brief   Le fichier contient les différentes fonctions relatvies à la page de connexion
 * @date    03/02/2025
 * @version 1.0
 */

window.onload = function () {
    const INPUT_EMAIL = document.getElementById('email');
    const INPUT_PASSWORD = document.getElementById('password');
    INPUT_EMAIL.addEventListener("input", checkEmailRequirements);
    INPUT_EMAIL.addEventListener("focusout", checkEmailRequirements);
    INPUT_EMAIL.addEventListener("change", checkEmailRequirements);
    INPUT_PASSWORD.addEventListener("input", checkPasswordRequirements);
    INPUT_PASSWORD.addEventListener("focusout", checkPasswordRequirements);
    INPUT_PASSWORD.addEventListener("change", checkPasswordRequirements);

    // Initialisation des variables — Obligatoire pour fonctionner avec Turnstile
    document.validateButton = document.getElementById("submitButton");
    document.validateInputs = {
        emailState: false,
        passwordState: false
    };
}

/**
 * @brief Vérifie si l'email respecte les exigences spécifiées.
 *
 * @details La fonction vérifie si l'email a un format valide.
 *
 * Si l'email ne respecte pas ces exigences, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkEmailRequirements() {
    // Constantes
    const BUTTON = document.getElementById("submitButton"); // Bouton de soumission
    const EMAIL = document.getElementById("email").value; // Email
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex pour valider le format de l'email
    // Variables
    let inputEmail = document.getElementById('email');
    let incorrectEmailFormat = document.getElementById("incorrectEmailFormat");

    // Vérifications
    if (!EMAIL_REGEX.test(EMAIL)) {
        document.validateInputs.emailState = false;
        inputEmail.classList.add("input-error");
        incorrectEmailFormat.classList.remove("hidden");
        changeButtonState(BUTTON, Object.values(document.validateInputs), true);
    } else {
        document.validateInputs.emailState = true;
        inputEmail.classList.remove("input-error");
        incorrectEmailFormat.classList.add("hidden");
        changeButtonState(BUTTON, Object.values(document.validateInputs), true);
    }
}

function checkPasswordRequirements() {
    const BUTTON = document.getElementById("submitButton");
    const PASSWORD = document.getElementById("password").value;
    let inputPassword = document.getElementById('password');
    let incorrectPasswordFormat = document.getElementById("incorrectPasswordFormat");

    if (PASSWORD.length < 8) {
        document.validateInputs.passwordState = false;
        inputPassword.classList.add("input-error");
        incorrectPasswordFormat.classList.remove("hidden");
        changeButtonState(BUTTON, Object.values(document.validateInputs), true);
    } else {
        document.validateInputs.passwordState = true;
        inputPassword.classList.remove("input-error");
        incorrectPasswordFormat.classList.add("hidden");
        changeButtonState(BUTTON, Object.values(document.validateInputs), true);
    }
}

function signIn(e) {
    loading(e);
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('rememberMe').checked;

    const url = window.location.href
    let redirect = '/';
    if (url.split('?')[1] !== undefined) {
        if (url.split('?')[1].indexOf('redirect=') !== -1) {
            redirect = decodeURIComponent(url.split('redirect=')[1].split('&')[0]);
        }
    }

    const cfToken = turnstile.getResponse();
    makeRequest('POST', '/login', (response) => {
        response = JSON.parse(response);
        if (response.success) {
            window.location.href = url.split('/login')[0] +
                (redirect.at(0) === '/' ? redirect : '/' + redirect);
        } else {
            e.innerHTML = "Se connecter";
            e.classList.remove("btn-disabled");
            e.classList.add("btn-primary");
            e.disabled = false;
            changeButtonState(document.getElementById('submitButton'), Object.values(document.validateInputs), true);
            showNotification("Oups...", response.message, "red");
            turnstileExpiredCallback();
        }
    }, `email=${email}&password=${password}&rememberMe=${rememberMe}&cfToken=${cfToken}`);
}