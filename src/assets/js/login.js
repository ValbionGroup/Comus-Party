/**
 * @file    login.js
 * @author  Lucas ESPIET et Estéban DESESSARD
 * @brief   Le fichier contient les différentes fonctions relatvies à la page de connexion
 * @date    03/02/2025
 * @version 1.0
 */

window.onload = function () {
    const INPUT_EMAIL = document.getElementById('email');
    INPUT_EMAIL.addEventListener("input", checkEmailRequirements);
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
        submitButton.disabled = true;
        submitButton.classList.add("btn-disabled");
        submitButton.classList.remove("btn-primary");
        inputEmail.classList.add("input-error");
        incorrectEmailFormat.classList.remove("hidden");
    } else {
        submitButton.disabled = false;
        inputEmail.classList.remove("input-error");
        submitButton.classList.remove("btn-disabled");
        submitButton.classList.add("btn-primary");
        incorrectEmailFormat.classList.add("hidden");
    }
}

function signIn(e) {
    loading(e);
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const cfToken = window.turnstile.getResponse();
    makeRequest('POST', '/login', (response) => {
        response = JSON.parse(response);
        if (response.success) {
            window.location.href = '/';
        } else {
            e.innerHTML = "Se connecter";
            e.classList.remove("btn-disabled");
            e.classList.add("btn-primary");
            e.disabled = false;
            showNotification("Oups...", response.message, "red");
        }
    }, `email=${email}&password=${password}&cfToken=${cfToken}`);
}

function turnstileSuccessCallback() {
}

function turnstileErrorCallback() {

}