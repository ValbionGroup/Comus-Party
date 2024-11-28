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
