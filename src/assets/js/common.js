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

const captchaStatus = document.getElementById('captcha-status');
const captchaStatusIcon = document.getElementById('captcha-status-icon');
const captchaStatusText = document.getElementById('captcha-status-text');
const defaultClass = "flex mb-2 gap-2 transition-all duration-200";

/**
 * @brief Fonction appelée lors de la réussite du test de Captcha
 * @return void
 */
function turnstileSuccessCallback() {
    captchaStatusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.586 2.1a2 2 0 0 1 2.7-.116l.128.117L15.314 4H18a2 2 0 0 1 1.994 1.85L20 6v2.686l1.9 1.9a2 2 0 0 1 .116 2.701l-.117.127l-1.9 1.9V18a2 2 0 0 1-1.85 1.995L18 20h-2.685l-1.9 1.9a2 2 0 0 1-2.701.116l-.127-.116l-1.9-1.9H6a2 2 0 0 1-1.995-1.85L4 18v-2.686l-1.9-1.9a2 2 0 0 1-.116-2.701l.116-.127l1.9-1.9V6a2 2 0 0 1 1.85-1.994L6 4h2.686zm4.493 6.883l-4.244 4.244l-1.768-1.768a1 1 0 0 0-1.414 1.415l2.404 2.404a1.1 1.1 0 0 0 1.556 0l4.88-4.881a1 1 0 0 0-1.414-1.414"/></g></svg>';
    captchaStatus.classList = defaultClass + " text-green-500";
    captchaStatusText.innerText = "Captcha validé avec succès";
}

/**
 * @brief Fonction appelée lors d'une demande manuelle de validation du Captcha
 * @return void
 */
function turnstileManualCallback() {
    captchaStatusIcon.innerHTML = '<i class="loader" style="width: 22px; height: 22px;"></i>';
    captchaStatusText.innerText = "Vérification manuelle en attente...";
    captchaStatus.classList = defaultClass + " text-gray-600 dark:text-gray-400";
}

/**
 * @brief Fonction appelée lors d'une demande manuelle de validation du Captcha
 * @return void
 */
function turnstileLoadingCallback() {
    captchaStatusIcon.innerHTML = '<i class="loader" style="width: 22px; height: 22px;"></i>';
    captchaStatusText.innerText = "Vérification Captcha en cours...";
    captchaStatus.classList = defaultClass + " text-gray-600 dark:text-gray-400";
}

/**
 * @brief Fonction appelée lors de l'échec du test de Captcha
 * @return void
 */
function turnstileErrorCallback() {
    captchaStatusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m0-4q.425 0 .713-.288T13 12V8q0-.425-.288-.712T12 7t-.712.288T11 8v4q0 .425.288.713T12 13m0 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"/></svg>';
    captchaStatus.classList = defaultClass + " text-red-500";
    captchaStatusText.innerText = "Erreur lors de la validation du Captcha";
}

function turnstileExpiredCallback() {
    captchaStatusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m0-4q.425 0 .713-.288T13 12V8q0-.425-.288-.712T12 7t-.712.288T11 8v4q0 .425.288.713T12 13m0 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"/></svg>';
    captchaStatus.classList = defaultClass + " text-red-500";
    captchaStatusText.innerText = "Le Captcha a expiré";

    // Relancer le Captcha
    setTimeout(() => {
        turnstileLoadingCallback();
        turnstile.reset();
    }, 2000);
}