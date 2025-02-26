/**
 * @brief Contient des fonctions communes à l'ensemble du site
 * @file common.js
 * @version 1.0
 * @date 09/01/2025
 * @autor Lucas ESPIET
 */

const notificationContainer = document.getElementById('jsNotification');
const notification = document.createElement('div');
notification.classList.add('hidden', 'absolute', 'bottom-6', 'right-6', 'px-5', 'py-3', 'shadow-md', 'rounded-2xl');
notification.innerHTML = `
    <p class="font-semibold"></p>
    <span></span>
`;
const background = document.getElementById('backgroundModal');
const reportForm = document.getElementById('modalReportForm');
const modalPlayerInfo = document.getElementById('modalPlayerInfo');

/**
 * @brief Affiche une notification
 * @param {string} title - Titre de la notification
 * @param {string} message - Message de la notification
 * @param {string} color - Couleur de la notification
 */
function showNotification(title, message, color) {
    const notificationClone = notification.cloneNode(true);
    notificationClone.classList.remove('hidden');
    // Couleurs possibles :
    // green : bg-green-500 et text-green-200
    // red : bg-red-500 et text-red-200
    // yellow : bg-yellow-500 et text-yellow-200
    notificationClone.classList.add(`bg-${color}-500`, `text-${color}-200`);
    notificationClone.querySelector('p').textContent = title;
    notificationClone.querySelector('span').textContent = message;
    notificationContainer.appendChild(notificationClone);

    setTimeout(() => {
        notificationClone.style.transition = "transform 0.7s ease-out";
        notificationClone.style.transform = "translateX(500px)";
        notificationClone.addEventListener('transitionend', () => {
            notificationClone.remove();
        });
    }, 5000);
}

/**
 * @brief Permet de faire une requête HTTP
 * @param {string} method - Méthode HTTP
 * @param {string} url - URL de la requête
 * @param {function} callback - Fonction à appeler après la requête
 * @param {string | null} body - Corps de la requête
 * @return void
 */
function makeRequest(method, url, callback, body = null) {
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(body);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(xhr.responseText);
        }
    };
}

function showBackgroundModal() {
    background.classList.remove("hidden");
}

function closeBackgroundModal() {
    background.classList.add("hidden");
}

function closeModal() {
    let modals = document.querySelectorAll(".modal")
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });
    closeBackgroundModal();
}

function showProfile(searchBy, data) {
    const playerInfoDiv = document.getElementById('modalPlayerInfo');
    const spanTopUsername = document.getElementById('spanTopUsername');
    const imgPfp = document.getElementById('imgPfp');
    const spanUsername = document.getElementById('spanUsername');
    const spanElo = document.getElementById('spanElo');
    const spanExp = document.getElementById('spanExp');
    const spanGamesPlayed = document.getElementById('spanGamesPlayed');
    const spanGamesWon = document.getElementById('spanGamesWon');
    const spanCreatedAt = document.getElementById('spanCreatedAt');

    const xhr = new XMLHttpRequest();
    xhr.open("POST", `/player/informations`, true);

    // Create a FormData object and append the data
    const formData = new FormData();
    formData.append("searchBy", searchBy);
    formData.append("data", data);

    // Send the FormData
    xhr.send(formData);

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            spanTopUsername.innerText = response.username;
            imgPfp.src = `/assets/img/pfp/${response.activePfp}`;
            spanUsername.innerText = response.username;
            spanElo.innerText = response.elo;
            spanExp.innerText = response.xp;
            spanGamesPlayed.innerText = response.statistics.gamesPlayed;
            spanGamesWon.innerText = response.statistics.gamesWon;
            spanCreatedAt.innerText = new Date(response.createdAt.date).toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            if (response.username === document.getElementById('headerUsername').innerText) {
                document.getElementById('flag').classList.add("hidden");
            } else {
                document.getElementById('flag').classList.remove("hidden");
            }
            playerInfoDiv.classList.remove("hidden");
            showBackgroundModal();
        }
    };
}

function showReportForm() {
    closeModal();
    reportForm.classList.remove("hidden");
    showBackgroundModal();
}

/**
 * @brief Passe un bouton en état de chargement
 * @details Cette méthode permet à l'utilisateur d'avoir un meilleur feedback sur les actions qu'il effectue.
 *  Lorsqu'il clique sur un bouton, cette méthode est obligatoirement appelé, ce qui passe le bouton dans un état de chargement
 * @param e L'élément HTML (le bouton) sur lequel l'utilisateur a cliqué et qui doit passer en état de chargement
 */
function loading(e) {
    e.disabled = true;
    e.classList.add("btn-disabled");
    e.classList.remove("btn-primary");
    e.classList.remove("btn-secondary");
    e.classList.remove("btn-danger");
    e.classList.remove("btn-success");
    e.innerHTML = '<div class="loader"></div>'
}