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

/**
 * @brief Affiche une notification
 * @param {string} title - Titre de la notification
 * @param {string} message - Message de la notification
 * @param {string} color - Couleur de la notification
 */
function showNotification(title, message, color) {
    const notificationClone = notification.cloneNode(true);
    notificationClone.classList.remove('hidden');
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