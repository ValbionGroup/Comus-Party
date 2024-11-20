/**
 * @file    basket.js
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient le JS nécessaire au bon fonctionnement de la page basket
 * @date    14/11/2024
 * @version 0.4
 */

let removeButtons = document.querySelectorAll('.remove-btn');
let notification = document.getElementById('notification');

let panier = document.getElementById("panier")
let panierVide = document.getElementById("panierVide")



function testArticleDansPanier(){
    let articles = document.querySelectorAll(".article")
    console.log("test")
    console.log(articles)
    if(articles.length === 0){
        panierVide.classList.add("flex")
        panierVide.classList.remove("hidden")
        console.log("pas d'articles")
    }else{
        panierVide.classList.add("hidden")
        panierVide.classList.remove("flex")
        console.log("articles")
    }
}


testArticleDansPanier()

removeButtons.forEach(button => {
    button.addEventListener('click', function () {

        // Supprimer la div parente du bouton
        const parentDiv = this.parentElement.parentElement;
        parentDiv.remove();
        testArticleDansPanier()

    });
});
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

        }
    };
}

