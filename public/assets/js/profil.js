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

let pfpTitle = document.getElementById("pfpTitle")
let pfpDescription = document.getElementById("pfpDescription")

let bannerTitle = document.getElementById("bannerTitle")
let bannerDescription = document.getElementById("bannerDescription")

let equipButton = document.getElementById("equipButton")
let modalPfp = document.getElementById("modalPfp")
let modalBanner = document.getElementById("modalBanner")

let modals = document.querySelectorAll(".modal")
let pfps = document.querySelectorAll(".pfp")
let playerPfp = document.getElementById("pfpPlayer")
let playerBanner = document.getElementById("bannerPlayer")
let pfpPlayerInHeader = document.getElementById("pfpPlayerInHeader")
let defaultPfp = document.getElementById("defaultPfp")
let inputSelectedArticleId = document.getElementById("selectedArticleId")
let inputSelectedArticleType = document.getElementById("selectedArticleType")
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
    pfps.forEach(pfp =>{
        if(pfp.classList.contains("shadow-lg")){
            pfp.classList.remove("shadow-lg")
        }
    })
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });

    background.classList.add("hidden")
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
    console.log(typeArticle)
    const xhr = new XMLHttpRequest();
    xhr.open("POST", `/profile/updateStyle/${idArticle}/${typeArticle}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();
    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText)
            console.log(response)
            if(typeArticle === "banner"){
                playerBanner.src = "/assets/img/banner/" + response.articlePath
            }else if(typeArticle === "pfp"){
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