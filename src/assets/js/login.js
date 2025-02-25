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
    const cfToken = turnstile.getResponse();
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

const captchaStatus = document.getElementById('captcha-status');
const captchaStatusIcon = document.getElementById('captcha-status-icon');
const captchaStatusText = document.getElementById('captcha-status-text');
const defaultClass = "flex mb-2 gap-2";

function turnstileSuccessCallback() {
    captchaStatusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.586 2.1a2 2 0 0 1 2.7-.116l.128.117L15.314 4H18a2 2 0 0 1 1.994 1.85L20 6v2.686l1.9 1.9a2 2 0 0 1 .116 2.701l-.117.127l-1.9 1.9V18a2 2 0 0 1-1.85 1.995L18 20h-2.685l-1.9 1.9a2 2 0 0 1-2.701.116l-.127-.116l-1.9-1.9H6a2 2 0 0 1-1.995-1.85L4 18v-2.686l-1.9-1.9a2 2 0 0 1-.116-2.701l.116-.127l1.9-1.9V6a2 2 0 0 1 1.85-1.994L6 4h2.686zm4.493 6.883l-4.244 4.244l-1.768-1.768a1 1 0 0 0-1.414 1.415l2.404 2.404a1.1 1.1 0 0 0 1.556 0l4.88-4.881a1 1 0 0 0-1.414-1.414"/></g></svg>';
    captchaStatus.classList = defaultClass + " text-green-500";
    captchaStatusText.innerText = "Test de Captcha réussi";
}

function turnstileErrorCallback() {
    captchaStatusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m0-4q.425 0 .713-.288T13 12V8q0-.425-.288-.712T12 7t-.712.288T11 8v4q0 .425.288.713T12 13m0 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"/></svg>';
    captchaStatus.classList = defaultClass + " text-red-500";
    captchaStatusText.innerText = "Erreur lors du test Captcha";
}