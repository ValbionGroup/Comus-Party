const gameCode = document.getElementById('gameCode').value;

function setVisibilityPublic(gameCode, isPublic) {
    const visibilityButton = document.getElementById('visibilityBtn');

    makeRequest(
        'POST',
        `/game/${gameCode}/visibility`,
        (response) => {
            response = JSON.parse(response);
            if (response.success) {
                visibilityButton.textContent = isPublic ? 'Rendre privée' : 'Rendre publique';
                visibilityButton.onclick = () => setVisibilityPublic(gameCode, !isPublic);
                visibilityButton.classList.replace(isPublic ? 'btn-success' : 'btn-warning', isPublic ? 'btn-warning' : 'btn-success');

                showNotification('Parfait !', 'La partie à changé d\'état', 'green');
            } else {
                showNotification('Oups...', response.message, 'red');
            }
        },
        `isPrivate=${!isPublic}`
    );
}

function quitGameAndBackHome(gameCode) {
    fetch(`/game/${gameCode}/quit`, {
        method: 'DELETE',
    })
        .then((response) => response.json())
        .then((response) => {
            if (response.success) {
                window.location.href = '/';
            } else {
                showNotification('Oups...', `Une erreur est survenue lors de la suppression de la partie\n${response.message}`, 'red');
            }
        });
}

function startGame(gameCode) {
    const settingsPanel = document.getElementById('settingsPanel');
    const inputs = settingsPanel.querySelectorAll('input');
    const selects = settingsPanel.querySelectorAll('select');
    const settings = {};

    inputs.forEach((input) => {
        settings[input.name] = input.value;
    });

    selects.forEach((select) => {
        settings[select.name] = select.value;
    });
    const data = new FormData();
    data.append('settings', JSON.stringify(settings));
    fetch(`/game/${gameCode}/start`, {
        method: 'POST',
        body: data,
    }).then((response) => response.json()
    ).then((response) => {
        if (response.success) {
            window.location.href = `/game/${gameCode}`;
        } else {
            showNotification('Oups...', response.message, 'red')
        }
    });
}

function sendChatMessage() {
    const messageInput = document.getElementById('chatInput');
    const content = messageInput.value;
    const username = document.getElementById('headerUsername').textContent;

    conn.send(JSON.stringify({
        author: username,
        content: content,
        game: gameCode,
    }));

    messageInput.value = '';
}

function receiveChatMessage(message) {
    message = JSON.parse(message);
    const messages = document.getElementById('chatContent');
    const messageItem = document.createElement('p');
    messageItem.classList.add("flex");

    const usernameItem = document.createElement('p');
    usernameItem.textContent = `${message.author}: `;
    usernameItem.classList.add('font-semibold');
    usernameItem.classList.add('hover:cursor-pointer');
    usernameItem.onclick = () => showProfile(message.author);

    const contentItem = document.createElement('span');
    contentItem.textContent = message.content;

    messageItem.appendChild(usernameItem);
    messageItem.appendChild(contentItem);
    messages.appendChild(messageItem);
}

const background = document.getElementById('backgroundModal');
const modals = document.querySelectorAll(".modal");

function showProfile(username) {

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
    xhr.open("POST", `/player/informations`, true);

    // Create a FormData object and append the data
    const formData = new FormData();
    formData.append("searchBy", "username");
    formData.append("data", username);

    // Send the FormData
    xhr.send(formData);

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            console.log(response);
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

function showBackgroundModal() {
    background.classList.remove("hidden");
}

function closeModal() {
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });
    background.classList.add("hidden");
}

// WebSocket
const conn = new WebSocket('wss://sockets.comus-party.com/chat/' + gameCode);
conn.onopen = function (e) {
    console.log("Connexion établie !");
};
conn.onmessage = function (e) {
    receiveChatMessage(e.data);
};
document.getElementById('sendChat').onclick = sendChatMessage;
document.getElementById('chatInput').addEventListener("keydown", function (e) {
    if (e.code === "Enter") {
        sendChatMessage();
    }
});

window.addEventListener('message', function (event) {
    if (event.data === 'redirectHome') {
        window.location.href = '/';
    }
});