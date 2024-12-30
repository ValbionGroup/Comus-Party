/**
 *   @file home.js
 *   @author Conchez-Boueytou Robin
 *   @brief Filtrer les jeux avec une animation de déplacement des cartes
 *   @date 18/11/2024
 *   @version 0.3
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

function closeModal() {
    let modals = document.querySelectorAll(".modal")
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });
    let background = document.getElementById('backgroundModal');
    background.classList.add("hidden");
}

function showBackgroundModal() {
    let background = document.getElementById('backgroundModal');
    background.classList.remove("hidden");
}

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

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/game/informations/${gameId}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                spanGameName.innerText = response.game.name;
                spanGameDescription.innerText = response.game.description;
                divGameTags.innerHTML = "";
                response.game.tags.forEach(tag => {
                    divGameTags.innerHTML += `<p class="border-2 rounded-full border-blue-violet-base py-0.5 px-2 text-center">${tag}</p>`;
                });
                showBackgroundModal();
                modal.classList.remove("hidden");
            }
        }
    };
}

createGameButton.addEventListener('click', createGame(gameId));

function createGame(id) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", `/game/create/${id}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                window.location.href = "/game/" + response.game.code;
            }
        }
    };
}