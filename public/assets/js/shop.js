/**
 * @file    shop.js
 * @author  Mathis Rivrais--Nowakowski
 * @brief    Le fichier contient le JS nécessaire au bon fonctionnement de la page basket
 * @date    14/11/2024
 * @version 0.4
 */


let pfps = document.querySelectorAll(".pfp");
let modalWindow = document.getElementById("modale")
let closeModalBtn = document.getElementById("closeModalBtn")
let addBasketBtn = document.getElementById("addBasketBtn")
let overlay = document.getElementById("overlay")
let notification = document.getElementById('notification');
let notificationMessage = document.getElementById("notificationMessage")


/*
Déclaration des attributs des articles présent dans le twig
 */

let imgArticle = document.getElementById("imgArticle")
let nomArticle = document.getElementById("nomArticle")
let descriptionArticle = document.getElementById("descriptionArticle")
let prixComusArticle = document.getElementById("prixComusArticle")
let prixEuroArticle = document.getElementById("prixEuroArticle")



/*
showModale
But : Affiche la fenêtre modale
 */

function showModal(article) {


    overlay.classList.remove('hidden');
    modalWindow.classList.remove('hidden');

// Forcer le reflow pour s'assurer que les transitions s'appliquent
    overlay.offsetHeight; // Reflow
    modalWindow.offsetHeight;

    overlay.classList.add('opacity-100'); // Apparition de l'overlay
    modalWindow.classList.add('opacity-100'); // Apparition et glissement de la modale
    modalWindow.classList.remove('opacity-0'); // Retirer les classes d'animation de départ



    imgArticle.src = ""
    imgArticle.src = "assets" + article.filePath
    nomArticle.innerText = article.name
    descriptionArticle.innerText = article.description

    prixComusArticle.innerText = article.pricePoint + " Comus"
    prixEuroArticle.innerText = article.priceEuro + " €"


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
                if (response.success) {
                    notificationMessage.textContent = "Article ajouté au panier"
                    notification.className = "z-50 fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform scale-90 transition-all duration-300 ease-in-out";


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



// Permet de fermer la fenêtre modale
closeModalBtn.addEventListener("click", ()=>{


    // modalWindow.classList.remove("flex")
    overlay.classList.remove('opacity-100'); // Disparition de l'overlay
    modalWindow.classList.remove('opacity-100', 'translate-y-0'); // Disparition et glissement de la modale

    // Ajouter les classes de départ après un léger délai pour permettre la transition de fermeture
    setTimeout(() => {
        overlay.classList.add('hidden', 'opacity-0');
        modalWindow.classList.add('hidden', 'opacity-0', 'translate-y-4');
    }, 300); // Durée de la transition
})
