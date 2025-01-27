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

function showModalUsernameEdit() {
    modalEditUsername.classList.remove("hidden");
    background.classList.remove("hidden");
}

function showModalEditEmail() {
    modalEditEmail.classList.remove("hidden");
    background.classList.remove("hidden");
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
                background.classList.add("hidden");
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
                background.classList.add("hidden");
            } else {
                showNotification('Oups...', response.error, 'red');
            }
        },
        `newEmail=${email}`
    );
}