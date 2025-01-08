function showBackgroundModal() {
    let background = document.getElementById('backgroundModal');
    background.classList.remove("hidden");
}

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

function showModalPlayerInfo(playerUuid) {
    const playerInfoDiv = document.getElementById('modalPlayerInfo');
    const spanTopUsername = document.getElementById('spanTopUsername');
    const imgPfp = document.getElementById('imgPfp');
    const spanUsername = document.getElementById('spanUsername');
    const spanElo = document.getElementById('spanElo');
    const spanExp = document.getElementById('spanExp');
    const spanGamesPlayed = document.getElementById('spanGamesPlayed');
    const spanGamesWon = document.getElementById('spanGamesWon');
    const spanCreatedAt = document.getElementById('spanCreatedAt');

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/player/informations/${playerUuid}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            spanTopUsername.innerText = response.username;
            imgPfp.src = `/assets/img/pfp/${response.activePfp}`;
            spanUsername.innerText = response.username;
            spanElo.innerText = response.elo;
            spanExp.innerText = response.xp;
            spanGamesPlayed.innerText = response.statistics.gamesPlayed;
            spanGamesWon.innerText = response.statistics.gamesWon;
            spanCreatedAt.innerText = new Date(response.createdAt.date).toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    };
    playerInfoDiv.classList.remove("hidden");

    showBackgroundModal();
}

const headers = document.querySelectorAll('th');
const tableBody = document.querySelector('tbody');
const tableContainer = document.getElementById('tableContainer');

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