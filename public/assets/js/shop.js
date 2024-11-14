let pfps = document.querySelectorAll(".pfp");
let modalWindow = document.getElementById("modale")
let closeModalBtn = document.getElementById("closeModalBtn")
let addBasketBtn = document.getElementById("addBasketBtn")

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

    let flex = "flex "

    modalWindow.className = flex + '' + modalWindow.className


    // if(article.type == "ProfilePicture"){
    //
    //     modalWindow.children[0].classList.remove("w-full")
    //     modalWindow.children[0].className = "w-32 " + modalWindow.children[0].className
    //     modalWindow.children[0].src = modalWindow.children[0].src + "pfp/" + article.name +".png"
    // }else if(article.type == "Banner"){
    //
    //
    //     modalWindow.children[0].classList.remove("w-32")
    //     modalWindow.children[0].className = "w-full " + modalWindow.children[0].className
    // }

    modalWindow.classList.remove("hidden")
    imgArticle.src = ""
    console.log(article.filePath)
    imgArticle.src = article.filePath + article.name + ".png"
    nomArticle.innerText = article.name
    descriptionArticle.innerText = article.description

    prixComusArticle.innerText = article.pricePoint + " Comus - "
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
    modalWindow.classList.add("hidden")
    modalWindow.classList.remove("flex")

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