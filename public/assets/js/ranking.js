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
    const spanUsername = document.getElementById('spanUsername');

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/player/informations/${playerUuid}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);

        }
    };

    spanUsername.innerText = playerUuid;
    playerInfoDiv.classList.remove("hidden");

    showBackgroundModal();
}
