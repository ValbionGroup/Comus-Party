/**
 * @file    shop.js
 * @author  Mathis Rivrais--Nowakowski & Estéban DESESSARD
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page panier
 * @date    14/11/2024
 * @version 2.0
 */


let pfps = document.querySelectorAll(".pfp");;
let modalWindowForPfp = document.getElementById("modalForPfp");
let modalWindowForBanner = document.getElementById("modalForBanner");
let modalWindow = document.getElementById("modalForBanner");
let modalsContent = document.querySelectorAll(".modalContent");
let closeModalBtn = document.getElementById("closeModalBtn");
let addBasketBtnPfp = document.getElementById("addBasketBtnPfp");
let addBasketBtnBanner = document.getElementById("addBasketBtnBanner");

let modals = document.querySelectorAll('.modal')
let closeModalBtnsClass = document.querySelectorAll('.closeModalBtnClass')
/*
Déclaration des attributs des articles présent dans le twig
 */

let imgArticlePfp = document.getElementById("imgArticlePfp")
let nameArticlePfp = document.getElementById("nameArticlePfp")
let descriptionArticlePfp = document.getElementById("descriptionArticlePfp")
let priceComusArticlePfp = document.getElementById("priceComusArticlePfp")
let priceEuroArticlePfp = document.getElementById("priceEuroArticlePfp")

let imgArticleBanner = document.getElementById("imgArticleBanner")
let nameArticleBanner = document.getElementById("nameArticleBanner")
let descriptionArticleBanner = document.getElementById("descriptionArticleBanner")
let priceComusArticleBanner = document.getElementById("priceComusArticleBanner")
let priceEuroArticleBanner = document.getElementById("priceEuroArticleBanner")


let emptyBasketLogo = document.getElementById("emptyBasketLogo")
let filledBasketLogo = document.getElementById("filledBasketLogo")
let numberArticlesInBasket = document.getElementById("numberArticlesInBasket")


/**
 * @brief Permet de vérifier si des articles sont dans le panier.
 * @details Si ils y a des articles dans le panier alors le logo du panier est mis à jour à chaque fois que la page est raffraichie
 */

function testArticleInBasket(){
    let nbrArticles = numberArticlesInBasket.textContent
    if(nbrArticles > 0){
        filledBasketLogo.classList.remove("hidden")
        emptyBasketLogo.classList.add("hidden")
        numberArticlesInBasket.textContent = nbrArticles
    }else{
        emptyBasketLogo.classList.remove("hidden")
        filledBasketLogo.classList.add("hidden")
    }
}

testArticleInBasket()

closeModalBtnsClass.forEach(btn => {
    btn.addEventListener('click', function (){
        closeModal();
    })
})

modals.forEach(modal => {
    modal.addEventListener("click", function (){
        closeModal();
    })
})
modalsContent.forEach(modal => {
    modal.addEventListener("click", function (){
        event.stopPropagation(); // Empêche la propagation à la div parent
    })
})


/**
 * @brief Permet d'afficher la fenêtre modale de l'article Pfp correspondant
 * @param article L'article qui doit être affiché dans la fenêtre modale
 */

function showModalPfp(article) {
    showBackgroundModal();
    modalWindowForPfp.classList.remove('hidden');
    // Forcer le reflow pour s'assurer que les transitions s'appliquent
    modalWindowForPfp.offsetHeight;
    modalWindowForPfp.classList.add('opacity-100'); // Apparition et glissement de la modale
    modalWindowForPfp.classList.remove('opacity-0'); // Retirer les classes d'animation de départ
    imgArticlePfp.src = ""
    imgArticlePfp.src = "assets/img/pfp/" + article.filePath
    nameArticlePfp.innerText = article.name
    descriptionArticlePfp.innerText = article.description
    priceComusArticlePfp.innerText = article.pricePoint
    priceEuroArticlePfp.innerText = article.priceEuro + " €"

    // Le joueur ne possédant pas l'article, il peut donc l'ajouter au panier
    if(article.owned == false) {
        addBasketBtnPfp.textContent = "Ajouter au panier"
        addBasketBtnPfp.disabled = false
        addBasketBtnPfp.onclick = function () {
            makeRequest("POST", "/shop/basket/add", (response) => {
                response = JSON.parse(response);
                if (response.numberArticlesInBasket > 0) {
                    filledBasketLogo.classList.remove("hidden");
                    emptyBasketLogo.classList.add("hidden");
                    numberArticlesInBasket.textContent = response.numberArticlesInBasket;
                } else {
                    emptyBasketLogo.classList.remove("hidden");
                    filledBasketLogo.classList.add("hidden");
                }
                // Préparer la notification si l'article a été supprimé du basket
                if (response.success) {
                    showNotification("Génial !", "Article ajouté au panier", "green");
                } else {
                    showNotification("Oups...", "Article déjà présent dans le panier", "red");
                }
                closeModal();
            }, "id_article=" + article.id);
        }
    } else {
        addBasketBtnPfp.textContent = "Déjà possédé";
        addBasketBtnPfp.disabled = true;
    }
}

/**
 * @brief Permet d'afficher la fenêtre modale de l'article Banner correspondant
 * @param article L'article qui doit être affiché dans la fenêtre modale
 */


function showModalBanner(article) {
    showBackgroundModal();
    modalWindowForBanner.classList.remove('hidden');
    // Forcer le reflow pour s'assurer que les transitions s'appliquent
    modalWindowForBanner.offsetHeight;
    modalWindowForBanner.classList.add('opacity-100'); // Apparition et glissement de la modale
    modalWindowForBanner.classList.remove('opacity-0'); // Retirer les classes d'animation de départ

    imgArticleBanner.src = ""
    imgArticleBanner.src = "assets/img/banner/" + article.filePath
    nameArticleBanner.innerText = article.name
    descriptionArticleBanner.innerText = article.description
    priceComusArticleBanner.innerText = article.pricePoint
    priceEuroArticleBanner.innerText = article.priceEuro + " €"

    if(article.owned == false){
        addBasketBtnBanner.textContent = "Ajouter au panier"
        addBasketBtnBanner.disabled = false
        addBasketBtnBanner.onclick = function (){
            makeRequest("POST", "/shop/basket/add", (response) => {
                response =  JSON.parse(response);
                if (response.numberArticlesInBasket > 0) {
                    filledBasketLogo.classList.remove("hidden");
                    emptyBasketLogo.classList.add("hidden");
                    numberArticlesInBasket.textContent = response.numberArticlesInBasket;
                } else {
                    emptyBasketLogo.classList.remove("hidden");
                    filledBasketLogo.classList.add("hidden");
                }
                // Préparer la notification si l'article a été supprimé du basket
                if (response.success) {
                    showNotification("Génial !", "Article ajouté au panier", "green");
                } else {
                    showNotification("Oups...", "Article déjà présent dans le panier", "red");
                }
                closeModal();
            }, "id_article=" + article.id);
        }
    } else {
        addBasketBtnBanner.textContent = "Déjà possédé";
        addBasketBtnBanner.disabled = true;
    }
}
