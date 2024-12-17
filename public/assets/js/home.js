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

function showBakgroundModal() {
    let background = document.getElementById('backgroundModal');
    background.classList.remove("hidden");
}

function showModalSuggestion() {
    let modal = document.getElementById('modalSuggestion');
    modal.classList.remove("hidden");
    showBakgroundModal();
}

function showModalGame(e) {
    let gameId = e.parentNode.parentNode.id;
    let modal = document.getElementById(`modalGame${gameId}`);
    modal.classList.remove("hidden");
    showBakgroundModal();
}