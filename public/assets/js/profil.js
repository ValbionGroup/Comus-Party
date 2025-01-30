/**
 * @file    profil.js
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier js de la page profil
 * @date    18/12/2024
 * @version 0.1
 */

// Déclaration des variables
let profileBtn = document.getElementById('btnProfile');
let settingsBtn = document.getElementById('btnSettings');
let statisticsBtn = document.getElementById('btnStatistics');

let profileBlock = document.getElementById('profile');
let settingsBlock = document.getElementById('settings');
let statisticsBlock = document.getElementById('statistics');

let modal = document.getElementById('modalConfirmationSuppression');
let pfpTitle = document.getElementById("pfpTitle");
let pfpDescription = document.getElementById("pfpDescription");

let bannerTitle = document.getElementById("bannerTitle");
let bannerDescription = document.getElementById("bannerDescription");

let equipButton = document.getElementById("equipButton");
let modalPfp = document.getElementById("modalPfp");
let modalBanner = document.getElementById("modalBanner");

let modals = document.querySelectorAll(".modal");
let pfps = document.querySelectorAll(".pfp");
let playerPfp = document.getElementById("pfpPlayer");
let playerBanner = document.getElementById("bannerPlayer");
let pfpPlayerInHeader = document.getElementById("pfpPlayerInHeader");
let defaultPfp = document.getElementById("defaultPfp");
let inputSelectedArticleId = document.getElementById("selectedArticleId");
let inputSelectedArticleType = document.getElementById("selectedArticleType");


let inputNewPassword = document.getElementById("newPassword");
let inputNewPasswordConfirm = document.getElementById("newPasswordConfirm");
let confirmPasswordBtn = document.getElementById("confirmPasswordBtn")
let divConfirmPasswordBtn = document.getElementById("divConfirmPasswordBtn")
let modalEditUsername = document.getElementById("modalEditUsername");
let newUsername = document.getElementById("newUsername");
let pUsername = document.getElementById("pUsername");
let headerUsername = document.getElementById("headerUsername");

let modalEditEmail = document.getElementById("modalEditEmail");
let newEmail = document.getElementById("newEmail");
let pEmail = document.getElementById("pEmail");

function activeShadowOnPfp(pfp) {
    pfps.forEach(pfp => pfp.classList.remove("shadow-lg"))
    pfp.classList.add("shadow-lg")
}

function infoArticlePfp(article) {
    pfpTitle.textContent = article.name
    pfpDescription.textContent = article.description
    inputSelectedArticleId.value = article.id
    inputSelectedArticleType.value = article.type

}

function infoArticleBanner(article) {
    bannerTitle.textContent = article.name
    bannerDescription.textContent = article.description
    inputSelectedArticleId.value = article.id
    inputSelectedArticleType.value = article.type
}

function showModalPfp() {
    modalPfp.classList.remove("hidden")
    showBackgroundModal()
}

function showModalBanner() {
    modalBanner.classList.remove("hidden")
    showBackgroundModal()
}

function closeModal() {
    pfps.forEach(pfp => {
        if (pfp.classList.contains("shadow-lg")) {
            pfp.classList.remove("shadow-lg")
        }
    })
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });

    closeBackgroundModal();
}


function afficher(section) {
    switch (section) {
        case 'profile' :
            profileBlock.classList.remove("hidden");
            profileBtn.classList.add("active");
            profileBtn.classList.remove("hover:scale-105");

            settingsBlock.classList.add("hidden");
            settingsBtn.classList.remove("active");
            settingsBtn.classList.add("hover:scale-105");

            statisticsBlock.classList.add("hidden");
            statisticsBtn.classList.remove("active");
            statisticsBtn.classList.add("hover:scale-105");
            break;

        case 'settings' :
            profileBlock.classList.add("hidden");
            profileBtn.classList.remove("active");
            profileBtn.classList.add("hover:scale-105");

            settingsBlock.classList.remove("hidden");
            settingsBtn.classList.add("active");
            settingsBtn.classList.remove("hover:scale-105");

            statisticsBlock.classList.add("hidden");
            statisticsBtn.classList.remove("active");
            statisticsBtn.classList.add("hover:scale-105");
            break;

        case 'statistics' :
            profileBlock.classList.add("hidden");
            profileBtn.classList.remove("active");
            profileBtn.classList.add("hover:scale-105");

            settingsBlock.classList.add("hidden");
            settingsBtn.classList.remove("active");
            settingsBtn.classList.add("hover:scale-105");

            statisticsBlock.classList.remove("hidden");
            statisticsBtn.classList.add("active");
            statisticsBtn.classList.remove("hover:scale-105");
            break;

        default :
            break;
    }
}

// Fonction permettant d'équiper un article

function equipArticle() {
    let idArticle = inputSelectedArticleId.value
    let typeArticle = inputSelectedArticleType.value
    const xhr = new XMLHttpRequest();
    xhr.open("PUT", `/profile/update-style/${idArticle}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();
    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText)
            if (typeArticle === "banner") {
                playerBanner.src = "/assets/img/banner/" + response.articlePath
            } else if (typeArticle === "pfp") {
                playerPfp.src = "/assets/img/pfp/" + response.articlePath
                pfpPlayerInHeader.src = "/assets/img/pfp/" + response.articlePath
            }

        }
    };
    closeModal()
}

function showModalSuppression() {
    modal.classList.remove("hidden");
    showBackgroundModal();
}

// MODIFICATION MOT DE PASSE

const MIN_PASSWORD_LENGTH = 8;
const MAX_PASSWORD_LENGTH = 64;
const UPPERCASE_LETTER = /[A-Z]/;
const LOWERCASE_LETTER = /[a-z]/;
const NUMBERS = /\d/;
const SPECIAL_CHARACTER = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;

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

/**
 * @brief Vérifie si le mot de passe est valide.
 */

function verifPassword() {

    confirmPasswordBtn.disabled = true
    divConfirmPasswordBtn.classList.add("opacity-50")
    if (inputNewPassword.value === "") {

        updateErrorMessage(inputNewPassword, "passwordTooShort", true, "");
        updateErrorMessage(inputNewPassword, "passwordTooLong", true, "");
        updateErrorMessage(inputNewPassword, "passwordNoUppercase", true, "");
        updateErrorMessage(inputNewPassword, "passwordNoLowercase", true, "");
        updateErrorMessage(inputNewPassword, "passwordNoNumber", true, "");
        updateErrorMessage(inputNewPassword, "passwordNoSpecialCharacter", true, "");
    } else {
        updateErrorMessage(inputNewPassword, "passwordTooShort", inputNewPassword.value.length >= MIN_PASSWORD_LENGTH, "Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères");
        updateErrorMessage(inputNewPassword, "passwordTooLong", inputNewPassword.value.length <= MAX_PASSWORD_LENGTH, "Le mot de passe doit être au maximum de " + MAX_PASSWORD_LENGTH + " caractères");
        updateErrorMessage(inputNewPassword, "passwordNoUppercase", UPPERCASE_LETTER.test(inputNewPassword.value), "Le mot de passe doit contenir au moins une majuscule");
        updateErrorMessage(inputNewPassword, "passwordNoLowercase", LOWERCASE_LETTER.test(inputNewPassword.value), "Le mot de passe doit contenir au moins une minuscule");
        updateErrorMessage(inputNewPassword, "passwordNoNumber", NUMBERS.test(inputNewPassword.value), "Le mot de passe doit contenir au moins un chiffre");
        updateErrorMessage(inputNewPassword, "passwordNoSpecialCharacter", SPECIAL_CHARACTER.test(inputNewPassword.value), "Le mot de passe doit contenir au moins un caractère spécial");
    }

}

/**
 * @brief Vérifie si le mot de passe de confirmation est valide.
 */
function matchPassword() {
    if (inputNewPasswordConfirm.value === inputNewPassword.value) {
        confirmPasswordBtn.disabled = false
        divConfirmPasswordBtn.classList.remove("opacity-50")
        updateErrorMessage(inputNewPasswordConfirm, "notMachingPasswords", true, "");

    } else {
        confirmPasswordBtn.disabled = true
        updateErrorMessage(inputNewPasswordConfirm, "notMachingPasswords", inputNewPassword.value === inputNewPasswordConfirm.value, "Les mots de passe ne correspondent pas");
    }
}

/**
 * @brief Met à jour le mot de passe.
 */
function updatePassword() {
    const isPasswordValid = inputNewPassword.value.length >= MIN_PASSWORD_LENGTH &&
        inputNewPassword.value.length <= MAX_PASSWORD_LENGTH &&
        UPPERCASE_LETTER.test(inputNewPassword.value) &&
        LOWERCASE_LETTER.test(inputNewPassword.value) &&
        NUMBERS.test(inputNewPassword.value) &&
        SPECIAL_CHARACTER.test(inputNewPassword.value);
    showNotification("En attente...", "Veuillez patienter", "yellow");
    if (isPasswordValid) {
        makeRequest("POST", `/profile/update-password`, (response) => {
            response = JSON.parse(response)
            if (response.success) {
                inputNewPassword.value = ""
                inputNewPasswordConfirm.value = ""
                confirmPasswordBtn.disabled = true
                divConfirmPasswordBtn.classList.add("opacity-50")
                showNotification("Mot de passe modifié", "Votre mot de passe a bien été modifié", "green");
            } else {
                inputNewPassword.value = ""
                inputNewPasswordConfirm.value = ""
                confirmPasswordBtn.disabled = true
                divConfirmPasswordBtn.classList.add("opacity-50")
                showNotification("Mot de passe similaire", "Vous ne pouvez pas mettre un mot de passe similaire", "red");
            }
        }, `newPassword=${inputNewPassword.value}`);


    }
}


function showModalUsernameEdit() {
    modalEditUsername.classList.remove("hidden");
    showBackgroundModal();
}

function showModalEditEmail() {
    modalEditEmail.classList.remove("hidden");
    showBackgroundModal();
}

function editUsername() {
    let username = newUsername.value;
    newUsername.value = '';
    if (username.length < 3 || username.length > 120) {
        showNotification("Oups...", "Votre nom d'utilisateur doit contenir entre 3 et 120 caractères", "red");
        return;
    } else if (!/^[a-zA-Z0-9_]*$/.test(username)) {
        showNotification("Oups...", "Votre nom d'utilisateur ne doit contenir que des lettres, des chiffres ou des underscores", "red");
        return
    }

    const xhr = new XMLHttpRequest();
    xhr.open("PUT", `/profile/update-username/${username}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();
    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                pUsername.textContent = username;
                headerUsername.textContent = username;
                showNotification("Tout est bon !", "Votre nom d'utilisateur a été modifié", "green");
                modalEditUsername.classList.add("hidden");
                closeBackgroundModal()
            } else {
                showNotification("Oups...", response.error, "red");
            }
        }
    };
}

function editMail() {
    let email = newEmail.value;
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showNotification("Oups...", "Votre adresse email n'est pas valide", 'red');
        return;
    }
    showNotification('Attendez !', 'Verification de votre adresse email', 'yellow');
    makeRequest(
        'POST',
        `/profile/update-email`,
        (response) => {
            response = JSON.parse(response);
            if (response.success) {
                newEmail.value = "";
                pEmail.textContent = email;
                showNotification('Parfait !', 'Votre adresse email a bien été modifié', 'green');
                modalEditEmail.classList.add("hidden");
                closeBackgroundModal();
            } else {
                showNotification('Oups...', response.error, 'red');
            }
        },
        `newEmail=${email}`
    );
}