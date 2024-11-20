/**
 * @file    basket.js
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page basket
 * @date    14/11/2024
 * @version 0.4
 */

function removeArticle(id){
    const xhr = new XMLHttpRequest();
    xhr.open("DELETE", `/shop/basket/remove/${id}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            notificationMessage.textContent = "Article retiré du panier"
            notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";
            notification.classList.remove('opacity-0', 'scale-90');
            notification.classList.add('opacity-100', 'scale-100');

            // Masquer la notification après 5 secondes
            setTimeout(() => {
                notification.classList.remove('opacity-100', 'scale-100');
                notification.classList.add('opacity-0', 'scale-90');
                document.getElementById("eltPanier").style.display = "none";
                location.reload()
            }, 3000);



        }
    };
}