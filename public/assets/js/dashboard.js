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

function showBackgroundModal() {
    let background = document.getElementById('backgroundModal');
    background.classList.remove("hidden");
}

function showModalSuggest(e) {
    let suggestId = e.id;
    let modal = document.getElementById(`modalSuggestion${suggestId}`);
    modal.classList.remove("hidden");
    showBackgroundModal();
}