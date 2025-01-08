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

    spanUsername.innerText = playerUuid;
    playerInfoDiv.classList.remove("hidden");

    showBackgroundModal();
}
