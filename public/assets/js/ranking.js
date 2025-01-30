/**
 *   @file ranking.js
 *   @author Estéban DESESSARD
 *   @brief Fichier JS permettant de gérer les interactions côté client de la page de classement
 *   @details
 *   @date 08/01/2025
 *   @version 0.1
 */

const background = document.getElementById('backgroundModal');
const modals = document.querySelectorAll(".modal");
const headers = document.querySelectorAll('th');
const tableBody = document.querySelector('tbody');
const tableContainer = document.getElementById('tableContainer');


function closeModal() {
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });
    background.classList.add("hidden");
}

// Ajoute des écouteurs d'événements aux colonnes triables
headers.forEach((header, index) => {
    // Ajoute le tri uniquement pour les colonnes Elo, XP, Parties gagnées et Parties jouées
    if (index >= 2) { // Ignore les colonnes Avatar et Nom d'utilisateur
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => {
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const isNumeric = true;

            tableContainer.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Trie les lignes en fonction du contenu de la colonne
            rows.sort((a, b) => {
                const aValue = a.children[index].textContent.trim();
                const bValue = b.children[index].textContent.trim();

                return isNumeric ?
                    parseFloat(bValue) - parseFloat(aValue) : // Ordre décroissant
                    bValue.localeCompare(aValue);
            });

            // Supprime les lignes actuelles
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }

            // Ajoute les lignes triées au tableau
            rows.forEach(row => tableBody.appendChild(row));

            // Met à jour les emojis de classement
            updateRankingEmojis();
        });
    }
});

// Fonction pour mettre à jour les emojis de classement après le tri
function updateRankingEmojis() {
    const usernameCells = tableBody.querySelectorAll('tr td:nth-child(2)');
    usernameCells.forEach((cell, index) => {
        // Supprime les emojis existants
        cell.textContent = cell.textContent.replace(/[🥇🥈🥉]/g, '');
        // Ajoute le nouvel emoji selon la position
        if (index === 0) {
            cell.textContent = '🥇' + cell.textContent;
        } else if (index === 1) {
            cell.textContent = '🥈' + cell.textContent;
        } else if (index === 2) {
            cell.textContent = '🥉' + cell.textContent;
        }
    });
}