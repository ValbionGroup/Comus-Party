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
let background = document.getElementById('backgroundModal');

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
let confirmEmailBtn = document.getElementById("confirmEmailBtn");

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
    background.classList.remove("hidden")
}

function showModalBanner() {
    modalBanner.classList.remove("hidden")
    background.classList.remove("hidden")
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

    background.classList.add("hidden");
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
    background.classList.remove("hidden");
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
    if(inputNewPassword.value === ""){

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

        confirmPasswordBtn.classList.remove("btn-disabled")
        confirmPasswordBtn.classList.add("btn-success")
        updateErrorMessage(inputNewPasswordConfirm, "notMachingPasswords", true, "");

    } else {
        confirmPasswordBtn.disabled = true
        confirmPasswordBtn.classList.add("btn-disabled");
        confirmPasswordBtn.classList.remove("btn-success");
        updateErrorMessage(inputNewPasswordConfirm, "notMachingPasswords", inputNewPassword.value === inputNewPasswordConfirm.value, "Les mots de passe ne correspondent pas");
    }
}

/**
 * @brief Met à jour le mot de passe.
 */
function updatePassword() {
    loading(confirmPasswordBtn);
    const isPasswordValid = inputNewPassword.value.length >= MIN_PASSWORD_LENGTH &&
        inputNewPassword.value.length <= MAX_PASSWORD_LENGTH &&
        UPPERCASE_LETTER.test(inputNewPassword.value) &&
        LOWERCASE_LETTER.test(inputNewPassword.value) &&
        NUMBERS.test(inputNewPassword.value) &&
        SPECIAL_CHARACTER.test(inputNewPassword.value);
    if (isPasswordValid) {
        makeRequest("POST", `/profile/update-password`, (response) => {
            response = JSON.parse(response)
            if (response.success) {
                inputNewPassword.value = "";
                inputNewPasswordConfirm.value = "";
                confirmPasswordBtn.disabled = true;
                confirmPasswordBtn.classList.add("btn-disabled");
                confirmPasswordBtn.classList.remove("btn-success");
                confirmPasswordBtn.innerHTML = "Confirmer";
                showNotification("Tout est bon !", "Votre mot de passe a bien été modifié", "green");
            } else {
                confirmPasswordBtn.disabled = true;
                confirmPasswordBtn.classList.add("btn-disabled");
                confirmPasswordBtn.classList.remove("btn-success");
                confirmPasswordBtn.innerHTML = "Confirmer";
                showNotification("Oups...", "Vous ne pouvez pas mettre un mot de passe identique", "red");
            }
        }, `newPassword=${inputNewPassword.value}`);
    }
}

function showModalUsernameEdit() {
    modalEditUsername.classList.remove("hidden");
    background.classList.remove("hidden");
}

function showModalEditEmail() {
    modalEditEmail.classList.remove("hidden");
    background.classList.remove("hidden");
}

function checkUsername() {
    let username = newUsername.value;
    let usernameError = document.getElementById("usernameError");
    let submitButton = document.getElementById("submitButtonUsername");

    if (username.length === 0 ) {
        usernameError.classList.add("hidden");
        usernameError.innerHTML = "";
        submitButton.disabled = true;
        submitButton.classList.add("btn-disabled");
        submitButton.classList.remove("btn-success");
        return;
    }
    if (!/^[a-zA-Z0-9_-]+$/.test(username)) {
        usernameError.classList.remove("hidden");
        usernameError.innerHTML = "Votre nom d'utilisateur ne doit contenir que des lettres, des chiffres ou des underscores";
        submitButton.disabled = true;
        submitButton.classList.add("btn-disabled");
        submitButton.classList.remove("btn-success");
        return;
    }
    if (username.length < 3 || username.length > 120) {
        usernameError.classList.remove("hidden");
        usernameError.innerHTML = "Votre nouveau nom d'utilisateur doit contenir entre 3 et 120 caractères";
        submitButton.disabled = true;
        submitButton.classList.add("btn-disabled");
        submitButton.classList.remove("btn-success");
        return;
    }
    else {
        usernameError.classList.add("hidden");
        usernameError.innerHTML = "";
        submitButton.disabled = false;
        submitButton.classList.remove("btn-disabled");
        submitButton.classList.add("btn-success");
    }
}

function editUsername(e) {
    let username = newUsername.value;
    newUsername.value = '';

    loading(e);

    if (username === pUsername.textContent) {
        showNotification("Oups...", "Votre nom d'utilisateur est déjà celui-ci", "red");
        e.innerHTML = "Confirmer";
        e.classList.add("btn-disabled");
        e.classList.remove("btn-primary");
        e.disabled = true;
        return;
    }

    if (username.length < 3 || username.length > 120) {
        showNotification("Oups...", "Votre nom d'utilisateur doit contenir entre 3 et 120 caractères", "red");
        return;
    } else if (!/^[a-zA-Z0-9_]*$/.test(username)) {
        showNotification("Oups...", "Votre nom d'utilisateur ne doit contenir que des lettres, des chiffres ou des underscores", "red");
        return
    }

    makeRequest('PUT', `/profile/update-username/${username}`, (response) => {
        response = JSON.parse(response);
        if (response.success) {
            pUsername.textContent = username;
            headerUsername.textContent = username;
            showNotification("Tout est bon !", "Votre nom d'utilisateur a été modifié", "green");
            modalEditUsername.classList.add("hidden");
            background.classList.add("hidden");
            e.innerHTML = "Confirmer";
            e.classList.add("btn-disabled");
            e.classList.remove("btn-primary");
            e.disabled = true;
        }
        else {
            e.innerHTML = "Confirmer";
            e.classList.remove("btn-disabled");
            e.classList.add("btn-primary");
            e.disabled = false;
            showNotification("Oups...", response.message, "red");
        }
    });
}

//MODIFICATION EMAIL

const EMAIL_AT = /@/;
const EMAIL_DOT = /\./;
const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

newEmail.addEventListener("input", () => {  // Vérifie si l'email est valide
    let email = newEmail.value;
    if (email === "") {
        updateErrorMessage(newEmail, "incorrectEmailAt", true, "");
        updateErrorMessage(newEmail, "incorrectEmailDot", true, "");
        updateErrorMessage(newEmail, "incorrectEmailRegex", true, "");
    } else {
        updateErrorMessage(newEmail, "incorrectEmailAt", EMAIL_AT.test(email), "L'adresse email doit contenir un @");
        updateErrorMessage(newEmail, "incorrectEmailDot", EMAIL_DOT.test(email), "L'adresse email doit contenir un .");
        updateErrorMessage(newEmail, "incorrectEmailRegex", EMAIL_REGEX.test(email), "Format d'email attendu : john.doe@example.com");
    }
    if (EMAIL_AT.test(email) && EMAIL_DOT.test(email) && EMAIL_REGEX.test(email)) {
        confirmEmailBtn.disabled = false;
        confirmEmailBtn.classList.remove("btn-disabled");
        confirmEmailBtn.classList.add("btn-success");
    } else {
        confirmEmailBtn.disabled = true;
        confirmEmailBtn.classList.add("btn-disabled");
        confirmEmailBtn.classList.remove("btn-success");
    }
});

function editMail() {
    loading(confirmEmailBtn);
    let email = newEmail.value;
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showNotification("Oups...", "Votre adresse email n'est pas valide", 'red');
        return;
    }
    makeRequest(
        'POST',
        `/profile/update-email`,
        (response) => {
            response = JSON.parse(response);
            if (response.success) {
                newEmail.value = "";
                pEmail.textContent = email;
                showNotification('Parfait !', 'Votre adresse email a bien été modifiée', 'green');
                modalEditEmail.classList.add("hidden");
                background.classList.add("hidden");
                confirmEmailBtn.innerHTML = "Confirmer";
                confirmEmailBtn.classList.add("btn-disabled");
                confirmEmailBtn.classList.remove("btn-success");
                confirmEmailBtn.disabled = true;
            } else {
                showNotification('Oups...', response.error, 'red');
                confirmEmailBtn.innerHTML = "Confirmer";
                confirmEmailBtn.classList.remove("btn-disabled");
                confirmEmailBtn.classList.add("btn-success");
                confirmEmailBtn.disabled = false;
            }
        },
        `newEmail=${email}`
    );
}