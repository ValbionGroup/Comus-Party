/**
 *   @file dashboard.js
 *   @author Estéban DESESSARD
 *   @brief Fichier JS permettant de gérer les différentes interactions sur le dashboard
 *   @details
 *   @date 19/12/2024
 *   @version 0.1
 */


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
                dashboardConnection.send(JSON.stringify({command: 'updateSuggests'}));
                showNotification("Génial !", "La suggestion a bien été refusée", "green");
            }
            else {
                showNotification("Oups...", "La suggestion n'a pas pu être refusée", "red");
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
                dashboardConnection.send(JSON.stringify({command: 'updateSuggests'}));
                showNotification("Génial !", "La suggestion a bien été acceptée", "green");
            }
            else {
                showNotification("Oups...", "La suggestion n'a pas pu être acceptée", "red");
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

}

// WebSocket
const dashboardConnection = new WebSocket('wss://sockets.comus-party.com/dashboard');
dashboardConnection.onopen = function (e) {
    console.log("Connexion établie avec DASHBOARD_SOCKET !");
    updateSuggests();
    updateReports();
};
dashboardConnection.onmessage = function (e) {
    let data = JSON.parse(e.data);
    if (data.message === "updateSuggests") {
        updateSuggests();
    }
    else if (data.message === "updateReports") {
        updateReports();
    }
    else {
        console.log("Message reçu : " + e.data);
    }
};

function updateSuggests() {
    makeRequest('GET', '/suggests', (response) => {
        response = JSON.parse(response);
        if (response.success) {
            let suggests = response.suggestions;
            let suggestList = document.getElementById('suggestList');
            suggestList.innerText = "";
            if (suggests === null) {
                suggestList.classList.add("justify-center");
                let noSuggest = document.createElement('p');
                noSuggest.classList.add("flex","flex-col", "items-center", "text-center");
                let spanCongratulations = document.createElement('span');
                spanCongratulations.classList.add("text-xl", "font-bold");
                spanCongratulations.innerText = "Félicitations !";
                let spanNoSuggest = document.createElement('span');
                spanNoSuggest.innerText = "Aucune suggestion en attente de traitement";
                noSuggest.appendChild(spanCongratulations);
                noSuggest.appendChild(spanNoSuggest);
                suggestList.appendChild(noSuggest);
            }
            else {
                suggestList.classList.remove("justify-center");
                suggests.forEach(suggest => {
                    let suggestItem = document.createElement('div');
                    suggestItem.id = suggest.id;
                    suggestItem.classList.add("flex", "p-2", "bg-night-base", "rounded-md", "hover:cursor-pointer", "hover:scale-105", "transition-all", "ease-in-out", "justify-between");
                    suggestItem.onclick = () => showModalSuggest(suggestItem);

                    let suggestInfo = document.createElement('div');
                    suggestInfo.classList.add("flex");

                    let suggestObjectTitle = document.createElement('p');
                    suggestObjectTitle.classList.add("underline", "mr-1");
                    suggestObjectTitle.innerText = "Thème :";

                    let suggestObject = document.createElement('p');
                    switch (suggest.object) {
                        case "BUG":
                            suggestObject.innerText = "🐛 Bug";
                            break;
                        case "GAME":
                            suggestObject.innerText = "🎮 Jeu";
                            break;
                        case "UI":
                            suggestObject.innerText = "🎨 Interface";
                            break;
                        case "OTHER":
                            suggestObject.innerText = "🔧 Autres";
                            break;
                    }

                    let suggestTime = document.createElement('p');
                    suggestTime.innerText = timeAgo(suggest.created_at);

                    suggestInfo.appendChild(suggestObjectTitle);
                    suggestInfo.appendChild(suggestObject);
                    suggestItem.appendChild(suggestInfo);
                    suggestItem.appendChild(suggestTime);
                    suggestList.appendChild(suggestItem);
                });
            }
        } else {
            console.error("Erreur lors de la récupération des suggestions");
        }
    });
}

function updateReports() {

}

function timeAgo(timestamp) {
    const now = new Date();
    const past = new Date(timestamp);
    const diffInSeconds = Math.floor((now - past) / 1000);

    // Définir les intervalles en secondes
    const minute = 60;
    const hour = minute * 60;
    const day = hour * 24;
    const month = day * 30;
    const year = day * 365;

    // Déterminer l'intervalle approprié
    if (diffInSeconds < minute) {
        return diffInSeconds === 1 ? "il y a 1 seconde" : `il y a ${diffInSeconds} secondes`;
    } else if (diffInSeconds < hour) {
        const minutes = Math.floor(diffInSeconds / minute);
        return minutes === 1 ? "il y a 1 minute" : `il y a ${minutes} minutes`;
    } else if (diffInSeconds < day) {
        const hours = Math.floor(diffInSeconds / hour);
        return hours === 1 ? "il y a 1 heure" : `il y a ${hours} heures`;
    } else if (diffInSeconds < month) {
        const days = Math.floor(diffInSeconds / day);
        return days === 1 ? "il y a 1 jour" : `il y a ${days} jours`;
    } else if (diffInSeconds < year) {
        const months = Math.floor(diffInSeconds / month);
        return months === 1 ? "il y a 1 mois" : `il y a ${months} mois`;
    } else {
        const years = Math.floor(diffInSeconds / year);
        return years === 1 ? "il y a 1 an" : `il y a ${years} ans`;
    }
}