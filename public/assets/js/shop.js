let pfps = document.querySelectorAll(".pfp");
let modalWindow = document.getElementById("modale")
let closeModalBtn = document.getElementById("closeModalBtn")
let addBasketBtn = document.getElementById("addBasketBtn")
let overlay = document.getElementById("overlay")





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

function showModale(article){


    overlay.classList.remove('hidden');
    modalWindow.classList.remove('hidden');

// Forcer le reflow pour s'assurer que les transitions s'appliquent
    overlay.offsetHeight; // Reflow
    modalWindow.offsetHeight;

    overlay.classList.add('opacity-100'); // Apparition de l'overlay
    modalWindow.classList.add('opacity-100', 'translate-y-0'); // Apparition et glissement de la modale
    modalWindow.classList.remove('opacity-0', 'translate-y-4'); // Retirer les classes d'animation de départ



    imgArticle.src = ""
    console.log(article.filePath)
    imgArticle.src = article.filePath + article.name + ".png"
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
                alert(xhr.responseText);  // Affiche la réponse (par exemple : "Article ajouté au panier !")
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


function addPanier(id){
    // Création de la requête AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/shop/basket/add", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Envoyer les données sous forme de paire clé=valeur
    xhr.send("id_article=" + id);

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);  // Affiche la réponse (par exemple : "Article ajouté au panier !")
        }
    };

}