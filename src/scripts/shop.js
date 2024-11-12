let pfps = document.querySelectorAll(".pfp");
let modaleWindow = document.getElementById("modale")
let closeModalBtn = document.getElementById("closeModalBtn")
function showModale(article){
    // modaleWindow.classList.add("flex")
    let flex = "flex "

    modaleWindow.className = flex + '' + modaleWindow.className
    console.log(modaleWindow.children)
    console.log(modaleWindow.children[0])
    let pathImg = article.pathImg
    let matchPfp = pathImg.match(/\/pfp\//)
    let matchBanner = pathImg.match(/\/banner\//)
    if(matchPfp){
        modaleWindow.children[0].classList.remove("w-full")
        modaleWindow.children[0].className = "w-32 " + modaleWindow.children[0].className
    }else if(matchBanner){
        console.log(modaleWindow)

        modaleWindow.children[0].classList.remove("w-32")
        modaleWindow.children[0].className = "w-full " + modaleWindow.children[0].className
    }

    modaleWindow.classList.remove("hidden")

    modaleWindow.children[0].src = article.pathImg
    modaleWindow.children[1].innerText = article.name
    modaleWindow.children[2].innerText = article.description

    modaleWindow.children[3].children[0].innerText = article.pricePoint + " Comus - "
    modaleWindow.children[3].children[1].innerText = article.priceEuro + " €"



}


closeModalBtn.addEventListener("click", ()=>{
    modaleWindow.classList.add("hidden")
    modaleWindow.classList.remove("flex")

})