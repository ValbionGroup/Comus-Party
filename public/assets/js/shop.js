/**
 * @file    shop.js
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page basket
 * @date    14/11/2024
 * @version 0.4
 */


let pfps = document.querySelectorAll(".pfp");
let modalWindowForPfp = document.getElementById("modalForPfp")
let modalWindowForBanner = document.getElementById("modalForBanner")
let modalWindow = document.getElementById("modalForBanner")
let closeModalBtn = document.getElementById("closeModalBtn")
let addBasketBtn = document.getElementById("addBasketBtn")
let overlay = document.getElementById("overlay")
let notification = document.getElementById('notification');
let notificationMessage = document.getElementById("notificationMessage")

let modals = document.querySelectorAll('.modal')
let closeModalBtns = document.querySelectorAll('.closeModalBtn')
/*
Déclaration des attributs des articles présent dans le twig
 */

let imgArticlePfp = document.getElementById("imgArticlePfp")
let nomArticlePfp = document.getElementById("nomArticlePfp")
let descriptionArticlePfp = document.getElementById("descriptionArticlePfp")
let prixComusArticlePfp = document.getElementById("prixComusArticlePfp")
let prixEuroArticlePfp = document.getElementById("prixEuroArticlePfp")

let imgArticleBanner = document.getElementById("imgArticleBanner")
let nomArticleBanner = document.getElementById("nomArticleBanner")
let descriptionArticleBanner = document.getElementById("descriptionArticleBanner")
let prixComusArticleBanner = document.getElementById("prixComusArticleBanner")
let prixEuroArticleBanner = document.getElementById("prixEuroArticleBanner")


let logoPanierVide = document.getElementById("logoPanierVide")
let logoPanierRempli = document.getElementById("logoPanierRempli")
let nbrArticleDansPanier = document.getElementById("nbrArticleDansPanier")


/**
 * @brief Permet de vérifier si des articles sont dans le panier.
 * @details Si ils y a des articles dans le panier alors le logo du panier est mis à jour à chaque fois que la page est raffraichie
 */

function testArticleDansPanier(){
    let nbrArticles = nbrArticleDansPanier.textContent
    if(nbrArticles > 0){
        logoPanierRempli.classList.remove("hidden")
        logoPanierVide.classList.add("hidden")
        nbrArticleDansPanier.textContent = nbrArticles
    }else{
        logoPanierVide.classList.remove("hidden")
        logoPanierRempli.classList.add("hidden")
    }
}
testArticleDansPanier()


modals.forEach(modal => {
    modal.addEventListener('click', function (){
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
    imgArticlePfp.src = "assets" + article.filePath
    nomArticlePfp.innerText = article.name
    descriptionArticlePfp.innerText = article.description
    prixComusArticlePfp.innerText = article.pricePoint
    prixEuroArticlePfp.innerText = article.priceEuro + " €"

    addBasketBtn.onclick = function (){
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/shop/basket/add", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Envoyer les données sous forme de paire clé=valeur
        xhr.send("id_article=" + article.id);

        // Gérer la réponse du serveur
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response =  JSON.parse(xhr.responseText)
                if(response.taillePanier > 0){
                    logoPanierRempli.classList.remove("hidden")
                    logoPanierVide.classList.add("hidden")
                    nbrArticleDansPanier.textContent = response.taillePanier
                }else{
                    logoPanierVide.classList.remove("hidden")
                    logoPanierRempli.classList.add("hidden")
                }
                // Préparer la notification si l'article a été supprimé du panier
                if (response.success) {
                    notificationMessage.textContent = "Article ajouté au panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";   if(response.taillePanier > 0){
                    }
                } else {
                    notificationMessage.textContent = "Article déjà présent dans le panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-red-300 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";
                }
                // Afficher la notification
                notification.classList.remove('opacity-0', 'scale-90');
                notification.classList.add('opacity-100', 'scale-100');

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
    imgArticleBanner.src = "assets" + article.filePath
    nomArticleBanner.innerText = article.name
    descriptionArticleBanner.innerText = article.description
    prixComusArticleBanner.innerText = article.pricePoint
    prixEuroArticleBanner.innerText = article.priceEuro + " €"

    addBasketBtn.onclick = function (){
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/shop/basket/add", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Envoyer les données sous forme de paire clé=valeur
        xhr.send("id_article=" + article.id);

        // Gérer la réponse du serveur
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response =  JSON.parse(xhr.responseText)
                if(response.taillePanier > 0){
                    logoPanierRempli.classList.remove("hidden")
                    logoPanierVide.classList.add("hidden")
                    nbrArticleDansPanier.textContent = response.taillePanier
                }else{
                    logoPanierVide.classList.remove("hidden")
                    logoPanierRempli.classList.add("hidden")
                }
                // Préparer la notification si l'article a été supprimé du panier
                if (response.success) {
                    notificationMessage.textContent = "Article ajouté au panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";   if(response.taillePanier > 0){
                    }
                } else {
                    notificationMessage.textContent = "Article déjà présent dans le panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-red-300 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";
                }
                // Afficher la notification
                notification.classList.remove('opacity-0', 'scale-90');
                notification.classList.add('opacity-100', 'scale-100');

                // Masquer la notification après 5 secondes
                setTimeout(() => {
                    notification.classList.remove('opacity-100', 'scale-100');
                    notification.classList.add('opacity-0', 'scale-90');
                }, 5000);
            }
        };
    }


}
closeModalBtns.forEach(closeModalBtn => {
    closeModalBtn.addEventListener("click", function (){
    overlay.classList.remove('opacity-100'); // Disparition de l'overlay
    modalWindowForPfp.classList.remove('opacity-100', 'translate-y-0'); // Disparition et glissement de la modale
    // Ajouter les classes de départ après un léger délai pour permettre la transition de fermeture
    setTimeout(() => {
        overlay.classList.add('hidden', 'opacity-0');
        modalWindowForPfp.classList.add('hidden', 'opacity-0', 'translate-y-4');
    }, 300); // Durée de la transition
})
})