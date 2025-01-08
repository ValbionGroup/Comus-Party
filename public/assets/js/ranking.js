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
            console.log(response);
            spanTopUsername.innerText = response.player.username;
            imgPfp.src = `/assets/img/pfp/${response.activePfp}`;
            spanUsername.innerText = response.player.username;
            spanElo.innerText = response.player.elo;
            spanExp.innerText = response.player.xp;
            spanGamesPlayed.innerText = response.player.statistics.gamesPlayed;
            spanGamesWon.innerText = response.player.statistics.gamesWon;
            spanCreatedAt.innerText = new Date(response.player.createdAt.date).toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    };
    playerInfoDiv.classList.remove("hidden");

    showBackgroundModal();
}
