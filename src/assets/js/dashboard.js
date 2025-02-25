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
                idSuggestion.value = response.suggestion.id;
                spanAuthorSuggest.innerHTML = response.suggestion.author_username;
                spanContentSuggest.innerHTML = response.suggestion.content;
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

}

function denyReport(e) {
    dashboardConnection.send(JSON.stringify({command: 'updateReports'}));
}

function acceptReport(e) {
    dashboardConnection.send(JSON.stringify({command: 'updateReports'}));
}

// WebSocket
const dashboardConnection = new WebSocket('ws://localhost:8315/dashboard');
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
    makeRequest('GET', '/reports', (response) => {
        response = JSON.parse(response);
        if (response.success) {
            let reports = response.reports;
            let reportList = document.getElementById('reportList');
            reportList.innerText = "";
            if (reports === null) {
                reportList.classList.add("justify-center");
                let noReports = document.createElement('p');
                noReports.classList.add("flex","flex-col", "items-center", "text-center");
                let spanCongratulations = document.createElement('span');
                spanCongratulations.classList.add("text-xl", "font-bold");
                spanCongratulations.innerText = "Félicitations !";
                let spanNoReport = document.createElement('span');
                spanNoReport.innerText = "Aucune signalement en attente de traitement";
                noReports.appendChild(spanCongratulations);
                noReports.appendChild(spanNoReport);
                reportList.appendChild(noReports);
            }
            else {
                reportList.classList.remove("justify-center");
                reports.forEach(report => {
                    let reportItem = document.createElement('div');
                    reportItem.id = report.id;
                    reportItem.classList.add("flex", "p-2", "bg-night-base", "rounded-md", "hover:cursor-pointer", "hover:scale-105", "transition-all", "ease-in-out", "justify-between");
                    reportItem.addEventListener('click', () => showModalReport(reportItem));

                    let reportInfo = document.createElement('div');
                    reportInfo.classList.add("flex");

                    let reportObjectTitle = document.createElement('p');
                    reportObjectTitle.classList.add("underline", "mr-1");
                    reportObjectTitle.innerText = "Thème :";

                    let reportObject = document.createElement('p');
                    switch (report.object) {
                        case "LANGUAGE":
                            reportObject.innerText = "🗣️ Langage";
                            break;
                        case "SPAM":
                            reportObject.innerText = "💬 Spam";
                            break;
                        case "LINKS":
                            reportObject.innerText = "📢 Publicité";
                            break;
                        case "FAIRPLAY":
                            reportObject.innerText = "👥 Fairplay";
                            break;
                        case "OTHER":
                            reportObject.innerText = " Autres";
                            break;
                    }

                    let reportTime = document.createElement('p');
                    reportTime.innerText = timeAgo(report.created_at);

                    reportInfo.appendChild(reportObjectTitle);
                    reportInfo.appendChild(reportObject);
                    reportItem.appendChild(reportInfo);
                    reportItem.appendChild(reportTime);
                    reportList.appendChild(reportItem);
                });
            }
        } else {
            console.error("Erreur lors de la récupération des signalements");
        }
    });
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