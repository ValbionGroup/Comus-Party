let pfps = document.querySelectorAll(".pfp");
let modaleWindow = document.getElementById("modale")
let closeModalBtn = document.getElementById("closeModalBtn")
function showModale(article){
    // modaleWindow.classList.add("flex")
    let flex = "flex "

    modaleWindow.className = flex + '' + modaleWindow.className

    modaleWindow.classList.remove("hidden")
    console.log(modaleWindow.children[3])
    modaleWindow.children[0].src = article.pathImg
    modaleWindow.children[1].innerText = article.name
    modaleWindow.children[2].innerText = article.description
    console.log(modaleWindow.children[3].children)
    modaleWindow.children[3].children[0].innerText = article.pricePoint + " Comus | "
    modaleWindow.children[3].children[1].innerText = article.priceEuro + " €"



}


closeModalBtn.addEventListener("click", ()=>{
    modaleWindow.classList.add("hidden")
    modaleWindow.classList.remove("flex")

})