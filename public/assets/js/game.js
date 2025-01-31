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
        let flag = document.createElement("div");
        flag.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 20 20">\n' +
            '                <g fill="currentColor">\n' +
            '                    <path fill-rule="evenodd"\n' +
            '                          d="m6.804 2.632l-.637.264A3.51 3.51 0 0 0 4 6.137v4.386a1.46 1.46 0 0 0 2.167 1.276l.227-.126a4 4 0 0 1 3.88 0l.453.251a4 4 0 0 0 3.88 0l.734-.407A3.22 3.22 0 0 0 17 8.7V4.638a1.605 1.605 0 0 0-2.07-1.534l-.893.272a4 4 0 0 1-2.693-.131l-1.482-.613a4 4 0 0 0-3.058 0m4.893 7.543l-.454-.251A6 6 0 0 0 6 9.644V6.136c0-.61.368-1.16.931-1.393l.638-.263a2 2 0 0 1 1.529 0l1.481.612a6 6 0 0 0 4.04.196L15 5.173V8.7c0 .444-.241.853-.63 1.068l-.733.407a2 2 0 0 1-1.94 0"\n' +
            '                          clip-rule="evenodd"/>\n' +
            '                    <rect width="2" height="16" x="4" y="2" rx="1"/>\n' +
            '                </g>\n' +
            '            </svg>'

        newDiv.className = "flex flex-row gap-3 items-center overflow-hidden group hover:cursor-pointer";

        pfp.className = "size-8 rounded-full";
        pfp.src = "/assets/img/pfp/" + player.pfp;
        pfp.onclick = () => showProfile("uuid", player.uuid);

        pseudo.className = "text-lg group-hover:border-b-2";
        pseudo.textContent = player.username;
        pseudo.onclick = () => showProfile("uuid", player.uuid);

        flag.className = "hover:text-red-500";
        flag.onclick = () => showReportForm();

        newDiv.appendChild(pfp);
        newDiv.appendChild(pseudo);
        newDiv.appendChild(flag);
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