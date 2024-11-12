let pfps = document.querySelectorAll(".pfp");
let modalWindow = document.getElementById("modale")
let closeModalBtn = document.getElementById("closeModalBtn")

/*
showModale
But : Affiche la fenêtre modale
 */
function showModale(article){

    let flex = "flex "

    modalWindow.className = flex + '' + modalWindow.className

    let pathImg = article.pathImg
    let matchPfp = pathImg.match(/\/pfp\//)
    let matchBanner = pathImg.match(/\/banner\//)
    if(matchPfp){
        modalWindow.children[0].classList.remove("w-full")
        modalWindow.children[0].className = "w-32 " + modalWindow.children[0].className
    }else if(matchBanner){


        modalWindow.children[0].classList.remove("w-32")
        modalWindow.children[0].className = "w-full " + modalWindow.children[0].className
    }

    modalWindow.classList.remove("hidden")

    modalWindow.children[0].src = article.pathImg
    modalWindow.children[1].innerText = article.name
    modalWindow.children[2].innerText = article.description

    modalWindow.children[3].children[0].innerText = article.pricePoint + " Comus - "
    modalWindow.children[3].children[1].innerText = article.priceEuro + " €"



}



// Permet de fermer la fenêtre modale
closeModalBtn.addEventListener("click", ()=>{
    modalWindow.classList.add("hidden")
    modalWindow.classList.remove("flex")

})