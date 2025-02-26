/**
 *   @file dashboard.js
 *   @author Estéban DESESSARD
 *   @brief Fichier JS permettant de gérer les différentes interactions sur le dashboard
 *   @details
 *   @date 19/12/2024
 *   @version 0.1
 */

const modalPenalty = document.getElementById('modalPenaltyForm');
console.log(modalPenalty);

function showModalSuggest(e) {
    let suggestId = e.id;
    let modal = document.getElementById(`modalSuggestion`);
    let spanIdSuggest = document.getElementById(`spanIdSuggest`);
    let spanObjectSuggest = document.getElementById(`spanObjectSuggest`);
    let spanAuthorSuggest = document.getElementById(`spanAuthorSuggest`);
    let spanContentSuggest = document.getElementById(`spanContentSuggest`);
    let idSuggestion = document.getElementById(`idSuggestion`);

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
                spanIdSuggest.innerText = response.suggestion.id;
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
                idSuggestion.value = response.suggestion.id;
                spanAuthorSuggest.innerText = response.suggestion.author_username;
                spanContentSuggest.innerText = response.suggestion.content;
            }
        }
    };

    modal.classList.remove("hidden");
    showBackgroundModal();
}

function denySuggest(e) {
    let id = e.parentNode.children[0].value;
    const xhr = new XMLHttpRequest();
    xhr.open("PUT", `/suggest/deny/${id}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                closeModal();
                location.reload();
            }
        }
    };
}

function acceptSuggest(e) {
    let id = e.parentNode.children[0].value;
    const xhr = new XMLHttpRequest();
    xhr.open("PUT", `/suggest/accept/${id}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                closeModal();
                location.reload();
            }
        }
    };
}

function showModalReport(e) {
    let reportId = e.id;
    let modal = document.getElementById(`modalReport`);
    let spanIdReport = document.getElementById(`spanIdReport`);
    let spanObjectReport = document.getElementById(`spanObjectReport`);
    let spanAuthorReport = document.getElementById(`spanAuthorReport`);
    let spanDescriptionReport = document.getElementById(`spanDescriptionReport`);
    let spanReportedPlayer = document.getElementById(`spanReportedPlayer`);
    let idReport = document.getElementById(`idSuggestion`);

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/report/${reportId}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                spanIdReport.innerText = response.report.id;
                switch (response.report.object) {
                    case "LANGUAGE":
                        spanObjectReport.innerText = "🐛 Langage";
                        break;
                    case "SPAM":
                        spanObjectReport.innerText = "🎮 Jeu";
                        break;
                    case "LINKS":
                        spanObjectReport.innerText = "🎨 Interface";
                        break;
                    case "FAIRPLAY":
                        spanObjectReport.innerText = "🎨 Interface";
                        break;
                    case "OTHER":
                        spanObjectReport.innerText = "🔧 Autres";
                        break;
                }
                idReport.value = response.report.id;
                spanAuthorReport.innerText = response.report.author_username;
                spanDescriptionReport.innerText = response.report.description;
                spanReportedPlayer.innerText = `${response.report.reported_username} (${response.report.reported_uuid})`;
            }
        }
    };

    modal.classList.remove("hidden");
    showBackgroundModal();
}

function denyReport(e) {

}

function acceptReport(e) {
    console.log(modalPenalty);
    closeModal();
    modalPenalty.classList.remove("hidden");
    showBackgroundModal();
}