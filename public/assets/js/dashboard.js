/**
 *   @file dashboard.js
 *   @author Estéban DESESSARD
 *   @brief Fichier JS permettant de gérer les différentes interactions sur le dashboard
 *   @details
 *   @date 19/12/2024
 *   @version 0.1
 */

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
    let modal = document.getElementById(`modalSuggestion`);
    let spanIdSuggest = document.getElementById(`spanIdSuggest`);
    let spanObjectSuggest = document.getElementById(`spanObjectSuggest`);
    let spanAuthorSuggest = document.getElementById(`spanAuthorSuggest`);
    let spanContentSuggest = document.getElementById(`spanContentSuggest`);

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/suggest/${suggestId}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                spanIdSuggest.innerHTML = response.suggestion.id;
                switch (response.suggestion.object) {
                    case "BUG":
                        spanObjectSuggest.innerText = "🐛 Bug";
                        break;
                    case "GAME":
                        spanObjectSuggest.innerText = "🎮 Jeu";
                        break;
                    case "UI":
                        spanObjectSuggest.innerText = "🎨 Interface";
                        break;
                    case "OTHER":
                        spanObjectSuggest.innerText = "🔧 Autres";
                        break;
                }
                spanAuthorSuggest.innerHTML = response.suggestion.author_username;
                spanContentSuggest.innerHTML = response.suggestion.content;
            }
        }
    };


    modal.classList.remove("hidden");
    showBackgroundModal();
}

function denySuggest(e) {
    let id = e.parentNode.parentNode.parentNode.parentNode.id.slice(15);
    const xhr = new XMLHttpRequest();
    xhr.open("PUT", `/suggest/deny/${id}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            console.log(response);
            if (response.success) {
                closeModal();
                location.reload();
            }
        }
    };
}