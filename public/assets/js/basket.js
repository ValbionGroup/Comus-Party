/**
 * @file    basket.js
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page basket
 * @date    14/11/2024
 * @version 0.4
 */

let removeButtons = document.querySelectorAll('.remove-btn');
let notification = document.getElementById('notification');

let basket = document.getElementById("basket")
let emptyBasket = document.getElementById("emptyBasket")
let totalPriceBasket = document.getElementById("totalPriceBasket")
let subTotalBasket = document.getElementById("subTotalBasket")
let paymentBtn = document.getElementById("paymentBtn")

/**
 ** @brief Vérifie le contenu du basket
 ** @details Si le basket ne contient aucun article, affiche un message indiquant que celui-ci est vide.
 */

function testArticleInBasket(){
    let articles = document.querySelectorAll(".article")
    if(articles.length === 0){
        emptyBasket.classList.add("flex")
        emptyBasket.classList.remove("hidden")

    }else{
        emptyBasket.classList.add("hidden")
        emptyBasket.classList.remove("flex")

    }
}
testArticleInBasket()
/**
 * @brief Permet de supprimer un article du basket
 */
removeButtons.forEach(button => {
    button.addEventListener('click', function () {

        // Supprimer la div parente du bouton
        const parentDiv = this.parentElement.parentElement;
        parentDiv.remove();
        testArticleDansPanier()
        notificationMessage.textContent = "Article retiré du basket"
        notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";
        // Afficher la notification
        notification.classList.remove('opacity-0', 'scale-90');
        notification.classList.add('opacity-100', 'scale-100');

        // Masquer la notification après 5 secondes
        setTimeout(() => {
            notification.classList.remove('opacity-100', 'scale-100');
            notification.classList.add('opacity-0', 'scale-90');
        }, 5000);

    });
});

/**
 * @brief Permet de supprimer un article du basket dans la base de données ainsi que de mettre à jour le prix
 *  @param id L'id de l'article qu'il faut supprimer
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
            let response =  JSON.parse(xhr.responseText)
            let prixTotalPanierActuel = totalPriceBasket.textContent
            // Le parseint permet de récupérer que la valeur numérique du prix actuel du basket, c'est-à-dire sans le sigle "€"
            prixTotalPanierApresSuppressionArticle = parseFloat(prixTotalPanierActuel)  - response.prixArticle;
            subTotalBasket.textContent = prixTotalPanierApresSuppressionArticle +"€"
            totalPriceBasket.textContent = prixTotalPanierApresSuppressionArticle+"€"
            if(response.numberArticlesInBasket == 0){
                paymentBtn.disabled = true
            }else{
                paymentBtn.disabled = false
            }
        }
    };
}

