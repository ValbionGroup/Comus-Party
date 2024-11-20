/**
 *   @file home.js
 *   @author Conchez-Boueytou Robin
 *   @brief
 *   @details
 *   @date 18/11/2024
 *   @version 0.1
 */

// Barre de recherche
let searchBar = document.getElementById('searchBar');

/**
 * @brief Fonction qui permet de filtrer les jeux en fonction de la recherche
 * @param e
 */
searchBar.addEventListener('input', (e) => {
    let searchValue = e.target.value;
    let games = document.querySelectorAll('.game');

    games.forEach(game => {
        if (game.innerText.toLowerCase().includes(searchValue.toLowerCase())) {
            game.style.display = 'block';
        } else {
            game.style.display = 'none';
        }
    });
});