/**
 *   @file home.js
 *   @author Conchez-Boueytou Robin & Lucas ESPIET
 *   @brief Filtrer les jeux avec une animation de déplacement des cartes
 *   @date 09/01/2025
 *   @version 0.4
 */

// Barre de recherche
let searchBar = document.getElementById('searchBar');

/**
 * @brief Filtrer les jeux et appliquer une animation de déplacement
 * @param e
 */
searchBar.addEventListener('input', (e) => {
    let searchValue = e.target.value.toLowerCase(); // Récupérer la valeur de recherche
    let games = document.querySelectorAll('.game');

    games.forEach(game => {
        if (game.innerText.toLowerCase().includes(searchValue)) {
            // Rendre la carte visible
            game.classList.remove('hidden');
            game.classList.add('visible');
        } else {
            // Masquer la carte
            game.classList.remove('visible');
            game.classList.add('hidden');
        }
    });

    // Réorganiser les cartes (le navigateur gère automatiquement la disposition des grilles)
});


function showModalSuggestion() {
    let modal = document.getElementById('modalSuggestion');
    modal.classList.remove("hidden");
    showBackgroundModal();
}

function showModalGame(e) {
    let gameId = e.parentNode.parentNode.id;
    let modal = document.getElementById(`modalGame`);
    let spanGameName = document.getElementById('spanGameName');
    let spanGameDescription = document.getElementById('spanGameDescription');
    let divGameTags = document.getElementById('divGameTags');
    let createGameButton = document.getElementById('createGameModalButton');
    let searchGameButton = document.getElementById('findGameModalButton');
    let imgGame = document.getElementById('imgGame');

    makeRequest("GET", `/game/informations/${gameId}`, (response) => {
        response = JSON.parse(response);
        if (response.success) {
            spanGameName.innerText = response.game.name;
            spanGameDescription.innerText = response.game.description;
            imgGame.src = `/assets/img/games/${response.game.img}`;
            divGameTags.innerHTML = "";
            response.game.tags.forEach(tag => {
                divGameTags.innerHTML += `<p class="border-2 rounded-full border-blue-violet-base py-0.5 px-2 text-center">${tag}</p>`;
            });
            createGameButton.setAttribute("onclick", `createGame(${gameId})`);
            searchGameButton.setAttribute("onclick", `searchGame(${gameId})`);
            modal.classList.remove("hidden");
            showBackgroundModal();
        }
    });
}

function searchGame(id) {
    makeRequest("POST", `/game/search/${id}`, (response) => {
        handleGameControllerResponse(response);
    });
}

function createGame(id) {
    makeRequest("POST", `/game/create/${id}`, (response) => {
        handleGameControllerResponse(response);
    });
}

/**
 * @brief Gérer la réponse du contrôleur de jeu
 * @param {string} response - Réponse du contrôleur de jeu
 */
function handleGameControllerResponse(response) {
    let responseJson = JSON.parse(response);
    if (responseJson.success) {
        window.location.href = "/game/" + responseJson.game.code;
    } else {
        showNotification("Ouch...", responseJson.message, "red");
    }
}

/**
 * @brief Envoi une suggestion en vérifiant les conditions au préalable
 */
function sendSuggestion() {
    let suggestObject = document.getElementById('suggestObject');
    let suggestContent = document.getElementById('suggestContent');
    if (!checkObject()) {
        showNotification("Ouch...", "Veuillez choisir un objet de suggestion valide", "red");
    }
    else if (!checkContent()) {
        showNotification("Ouch...", "Votre suggestion doit contenir au moins 10 caractères", "red");
    }
    else {
        makeRequest("POST", "/", (response) => {
            let responseJson = JSON.parse(response);
            console.log(responseJson);
            if (responseJson.success) {
                showNotification("Merci !", "Votre suggestion a bien été envoyée", "green");
                closeModal();
            } else {
                showNotification("Ouch...", responseJson.message, "red");
            }
        }, `object=${suggestObject.value}&suggestion=${suggestContent.value}`);
    }
}

/**
 * @brief Vérifier si l'objet de suggestion est valide
 * @returns {boolean} - true si l'objet est valide, false sinon
 */
function checkObject() {
    let suggestObject = document.getElementById('suggestObject');
    if (["bug", "game", "ui", "other"].includes(suggestObject.value)) {
        suggestObject.classList.remove("input-error");
        return true;
    }
    else {
        suggestObject.classList.add("input-error");
        return false;
    }
}

function checkContent() {
    let suggestContent = document.getElementById('suggestContent');
    if (suggestContent.value.length >= 10) {
        suggestContent.classList.remove("input-error");
        return true;
    }
    else {
        suggestContent.addEventListener('input', () => {
            checkContent();
        });
        suggestContent.classList.add("input-error");
        return false;
    }
}