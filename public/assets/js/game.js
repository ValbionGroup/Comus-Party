function quitGameAndBackHome(gameCode) {
    fetch(`/game/${gameCode}/quit`, {
        method: 'DELETE',
    }).then((response) => {
        if (response.ok) {
            window.location.href = '/';
        }
        throw new Error('Failed to quit game');
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

const conn = new WebSocket('ws://localhost:8315/chat/945290a1a2e2d723da48640e73c5d76d');
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