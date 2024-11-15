// let deleteArticle = document.getElementById("deleteArticle")
console.log("jieji")



function removeArticle(id){
    const xhr = new XMLHttpRequest();
    xhr.open("DELETE", `/shop/basket/remove/${id}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Envoyer les données sous forme de paire clé=valeur
    xhr.send();

    // Gérer la réponse du serveur
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);  // Affiche la réponse (par exemple : "Article ajouté au panier !")
            document.getElementById("eltPanier").style.display = "none";
            location.reload()
        }
    };
}