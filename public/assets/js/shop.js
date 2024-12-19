/**
 * @file    shop.js
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page panier
 * @date    14/11/2024
 * @version 0.4
 */


let pfps = document.querySelectorAll(".pfp");
let modalWindowForPfp = document.getElementById("modalForPfp")
let modalWindowForBanner = document.getElementById("modalForBanner")
let modalWindow = document.getElementById("modalForBanner")
let modalsContent = document.querySelectorAll(".modalContent")
let closeModalBtn = document.getElementById("closeModalBtn")
let addBasketBtnPfp = document.getElementById("addBasketBtnPfp")
let addBasketBtnBanner = document.getElementById("addBasketBtnBanner")
let overlay = document.getElementById("overlay")
let notification = document.getElementById('notification');
let notificationMessage = document.getElementById("notificationMessage")

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
        // modalWindow.classList.remove("flex")
        overlay.classList.remove('opacity-100'); // Disparition de l'overlay
        this.parentElement.parentElement.parentElement.parentElement.classList.remove('opacity-100', 'translate-y-0'); // Disparition et glissement de la modale
        // Ajouter les classes de départ après un léger délai pour permettre la transition de fermeture
        setTimeout(() => {
            overlay.classList.add('hidden', 'opacity-0');
            this.parentElement.parentElement.parentElement.parentElement.classList.add('hidden', 'opacity-0', 'translate-y-4');
        }, 300); // Durée de la transition
    })
})

modals.forEach(modal => {
    modal.addEventListener("click", function (){
        // modalWindow.classList.remove("flex")
        overlay.classList.remove('opacity-100'); // Disparition de l'overlay
        modal.classList.remove('opacity-100', 'translate-y-0'); // Disparition et glissement de la modale
        // Ajouter les classes de départ après un léger délai pour permettre la transition de fermeture
        setTimeout(() => {
            overlay.classList.add('hidden', 'opacity-0');
            modal.classList.add('hidden', 'opacity-0', 'translate-y-4');
        }, 300); // Durée de la transition
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
    overlay.classList.remove('hidden');
    modalWindowForPfp.classList.remove('hidden');
    // Forcer le reflow pour s'assurer que les transitions s'appliquent
    overlay.offsetHeight; // Reflow
    modalWindowForPfp.offsetHeight;
    overlay.classList.add('opacity-100'); // Apparition de l'overlay
    modalWindowForPfp.classList.add('opacity-100'); // Apparition et glissement de la modale
    modalWindowForPfp.classList.remove('opacity-0'); // Retirer les classes d'animation de départ
    imgArticlePfp.src = ""
    imgArticlePfp.src = "assets/img/pfp/" + article.filePath
    nameArticlePfp.innerText = article.name
    descriptionArticlePfp.innerText = article.description
    priceComusArticlePfp.innerText = article.pricePoint
    priceEuroArticlePfp.innerText = article.priceEuro + " €"

    addBasketBtnPfp.onclick = function (){

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/shop/basket/add", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Envoyer les données sous forme de paire clé=valeur
        xhr.send("id_article=" + article.id);

        // Gérer la réponse du serveur
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response =  JSON.parse(xhr.responseText)
                if(response.numberArticlesInBasket > 0){
                    filledBasketLogo.classList.remove("hidden")
                    emptyBasketLogo.classList.add("hidden")
                    numberArticlesInBasket.textContent = response.numberArticlesInBasket
                }else{
                    emptyBasketLogo.classList.remove("hidden")
                    filledBasketLogo.classList.add("hidden")
                }
                // Préparer la notification si l'article a été supprimé du basket
                if (response.success) {
                    notificationMessage.textContent = "Article ajouté au panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";   if(response.numberArticlesInBasket > 0){
                    }
                } else {
                    notificationMessage.textContent = "Article déjà présent dans le panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-red-300 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";
                }
                // Afficher la notification
                notification.classList.remove('opacity-0', 'scale-90');
                notification.classList.add('opacity-100', 'scale-100');

                overlay.classList.remove('opacity-100'); // Disparition de l'overlay
                modalWindowForPfp.classList.remove('opacity-100', 'translate-y-0'); // Disparition et glissement de la modale
                // Ajouter les classes de départ après un léger délai pour permettre la transition de fermeture
                setTimeout(() => {
                    overlay.classList.add('hidden', 'opacity-0');
                    modalWindowForPfp.classList.add('hidden', 'opacity-0', 'translate-y-4');
                }, 300); // Durée de la transition

                // Masquer la notification après 5 secondes
                setTimeout(() => {
                    notification.classList.remove('opacity-100', 'scale-100');
                    notification.classList.add('opacity-0', 'scale-90');
                }, 5000);
            }
        };
    }
}

/**
 * @brief Permet d'afficher la fenêtre modale de l'article Banner correspondant
 * @param article L'article qui doit être affiché dans la fenêtre modale
 */


function showModalBanner(article) {
    overlay.classList.remove('hidden');
    modalWindowForBanner.classList.remove('hidden');
    // Forcer le reflow pour s'assurer que les transitions s'appliquent
    overlay.offsetHeight; // Reflow
    modalWindowForBanner.offsetHeight;
    overlay.classList.add('opacity-100'); // Apparition de l'overlay
    modalWindowForBanner.classList.add('opacity-100'); // Apparition et glissement de la modale
    modalWindowForBanner.classList.remove('opacity-0'); // Retirer les classes d'animation de départ

    imgArticleBanner.src = ""
    imgArticleBanner.src = "assets/img/banner/" + article.filePath
    nameArticleBanner.innerText = article.name
    descriptionArticleBanner.innerText = article.description
    priceComusArticleBanner.innerText = article.pricePoint
    priceEuroArticleBanner.innerText = article.priceEuro + " €"

    addBasketBtnBanner.onclick = function (){
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/shop/basket/add", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Envoyer les données sous forme de paire clé=valeur
        xhr.send("id_article=" + article.id);
        // Gérer la réponse du serveur
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {

                let response =  JSON.parse(xhr.responseText)
                if(response.numberArticlesInBasket > 0){
                    filledBasketLogo.classList.remove("hidden")
                    emptyBasketLogo.classList.add("hidden")
                    numberArticlesInBasket.textContent = response.numberArticlesInBasket
                }else{
                    emptyBasketLogo.classList.remove("hidden")
                    filledBasketLogo.classList.add("hidden")
                }
                // Préparer la notification si l'article a été supprimé du basket
                if (response.success) {
                    notificationMessage.textContent = "Article ajouté au basket"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";   if(response.numberArticlesInBasket > 0){
                    }
                } else {
                    notificationMessage.textContent = "Article déjà présent dans le panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-red-300 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";
                }
                // Afficher la notification
                notification.classList.remove('opacity-0', 'scale-90');
                notification.classList.add('opacity-100', 'scale-100');

                overlay.classList.remove('opacity-100'); // Disparition de l'overlay
                modalWindowForBanner.classList.remove('opacity-100', 'translate-y-0'); // Disparition et glissement de la modale
                // Ajouter les classes de départ après un léger délai pour permettre la transition de fermeture
                setTimeout(() => {
                    overlay.classList.add('hidden', 'opacity-0');
                    this.parentElement.parentElement.parentElement.parentElement.classList.add('hidden', 'opacity-0', 'translate-y-4');
                }, 300); // Durée de la transition
                // Masquer la notification après 5 secondes
                setTimeout(() => {
                    notification.classList.remove('opacity-100', 'scale-100');
                    notification.classList.add('opacity-0', 'scale-90');
                }, 5000);
            }
        };
    }



}
