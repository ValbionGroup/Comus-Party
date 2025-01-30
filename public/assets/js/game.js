const gameCode = document.getElementById('gameCode').value;
const playerUuid = document.getElementById('localPlayerUuid').value;

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
                gameConnection.send(JSON.stringify({uuid: playerUuid, command: 'quitGame', game: gameCode}));
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
    chatConnection.send(messageItem.textContent);
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
    usernameItem.onclick = () => showProfile("username", message.author);

    const contentItem = document.createElement('span');
    contentItem.textContent = message.content;

    messageItem.appendChild(usernameItem);
    messageItem.appendChild(contentItem);
    messages.appendChild(messageItem);
}

const modals = document.querySelectorAll(".modal");

function closeModal() {
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });
    closeBackgroundModal();
}

// WebSocket
const chatConnection = new WebSocket('ws://sockets.comus-party.com/chat/' + gameCode);
chatConnection.onopen = function (e) {
    console.log("Connexion établie avec CHAT_SOCKET !");
};
chatConnection.onmessage = function (e) {
    receiveChatMessage(e.data);
};

const gameConnection = new WebSocket('ws://localhost:8315/game/' + gameCode);
gameConnection.onopen = function (e) {
    console.log("Connexion établie avec GAME_SOCKET !");

    gameConnection.send(JSON.stringify({uuid: playerUuid, command: 'joinGame', game: gameCode}));
};
gameConnection.onmessage = function (e) {
    const data = JSON.parse(e.data);
    const players = JSON.parse(data.content);
    let div = document.getElementById('players');
    Array.from(div.childNodes).forEach((child) => {
        child.remove();
    });
    players.forEach((player) => {
        let newDiv = document.createElement('div');
        let pfp = document.createElement('img');
        let pseudo = document.createElement('p');

        newDiv.className = "flex flex-row gap-3 items-center";

        pfp.className = "size-8 rounded-full";
        pfp.src = "/assets/img/pfp/" + player.pfp;

        pseudo.className = "text-lg";
        pseudo.textContent = player.username;

        newDiv.appendChild(pfp);
        newDiv.appendChild(pseudo);
        div.appendChild(newDiv);
    });
}


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