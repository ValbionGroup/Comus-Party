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
    const messages = document.getElementById('chatContent');
    const messageItem = document.createElement('p');
    messageItem.textContent = messageInput.value;
    messages.appendChild(messageItem);
    messageInput.value = '';
    conn.send(messageItem.textContent);
}

function receiveChatMessage(message) {
    const messages = document.getElementById('chatContent');
    const messageItem = document.createElement('p');
    messageItem.textContent = message;
    messages.appendChild(messageItem);
}

// WebSocket
// TODO: Modifier le lien du socket
const conn = new WebSocket('ws://sockets.comus-party.com:port/chat/token');
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