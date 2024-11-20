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

            document.getElementById("eltPanier").style.display = "none";
            location.reload()
        }
    };
}