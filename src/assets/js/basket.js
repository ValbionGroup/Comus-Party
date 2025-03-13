/**
 * @file    basket.js
 * @author  Mathis Rivrais--Nowakowski & Estéban DESESSARD
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page panier
 * @date    14/11/2024
 * @version 2.0
 */

let removeButtons = document.querySelectorAll('.remove-btn');

let basket = document.getElementById("basket")
let emptyBasket = document.getElementById("emptyBasket")
let totalPriceBasket = document.getElementById("totalPriceBasket")
let subTotalBasket = document.getElementById("subTotalBasket")
let paymentBtn = document.getElementById("paymentBtn")

/**
 ** @brief Vérifie le contenu du panier
 ** @details Si le panier ne contient aucun article, affiche un message indiquant que celui-ci est vide.
 */

function testArticleInBasket() {
    let articles = document.querySelectorAll(".article")
    if(articles.length === 0){
        emptyBasket.classList.add("flex")
        emptyBasket.classList.remove("hidden")
        paymentBtn.disabled = true
        paymentBtn.classList.add("opacity-50")
    }else{
        emptyBasket.classList.add("hidden")
        emptyBasket.classList.remove("flex")
        paymentBtn.disabled = false
        paymentBtn.classList.remove("opacity-50")
    }
}

testArticleInBasket()
/**
 * @brief Permet de supprimer un article du panier
 */
removeButtons.forEach(button => {
    button.addEventListener('click', function () {

        // Supprimer la div parente du bouton
        const parentDiv = this.parentElement.parentElement;
        parentDiv.remove();
        testArticleInBasket();
        showNotification("Génial !", "Article retiré du panier", "green");
    });
});

/**
 * @brief Permet de supprimer un article du panier dans la base de données ainsi que de mettre à jour le prix
 *  @param id L'id de l'article qu'il faut supprimer
 */
function removeArticle(id) {
    makeRequest("DELETE", `/shop/basket/remove/${id}`, (response) => {
        response = JSON.parse(response);
        let actualTotalPriceBasket = totalPriceBasket.textContent
        // Le parseint permet de récupérer que la valeur numérique du prix actuel du basket, c'est-à-dire sans le sigle "€"
        totalPriceAfterDeletingArticle = parseFloat(actualTotalPriceBasket)  - response.priceEuroArticle;
        subTotalBasket.textContent = totalPriceAfterDeletingArticle +"€"
        totalPriceBasket.textContent = totalPriceAfterDeletingArticle+"€"
        if (response.numberArticlesInBasket === 0) {
            paymentBtn.disabled = true
        } else {
            paymentBtn.disabled = false
        }
    });
}

