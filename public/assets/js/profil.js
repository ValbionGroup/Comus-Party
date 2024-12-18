// Déclaration des variables
let profileBtn = document.getElementById('btnProfile');
let settingsBtn = document.getElementById('btnSettings');
let statisticsBtn = document.getElementById('btnStatistics');

let profileBlock = document.getElementById('profile');
let settingsBlock = document.getElementById('settings');
let statisticsBlock = document.getElementById('statistics');

let modal = document.getElementById('modalConfirmationSuppression');
let background = document.getElementById('backgroundModal');

let pfpTitle = document.getElementById("pfpTitle")
let pfpDescription = document.getElementById("pfpDescription")

let equipButton = document.getElementById("equipButton")
let modalPfp = document.getElementById("modalPfp")

let modals = document.querySelectorAll(".modal")
let pfps = document.querySelectorAll(".pfp")



function activeBorderOnPfp(pfp){
    console.log(pfp.classList)

    pfps.forEach(pfp=> pfp.classList.remove("activePfp"))
    pfp.classList.add("activePfp")
}

function infoArticlePfp(article){
    pfpTitle.textContent = article.name
    pfpDescription.textContent = article.description

}
function showModalPfp(){
    modalPfp.classList.remove("hidden")
    background.classList.remove("hidden")
}
function closeModal(){
    modals.forEach(modal => {
        if (!modal.classList.contains("hidden")) {
            modal.classList.add("hidden");
        }
    });

    background.classList.add("hidden")
}


function afficher(section) {
    switch (section) {
        case 'profile' :
            profileBlock.classList.remove("hidden");
            profileBtn.classList.add("active");
            profileBtn.classList.remove("hover:scale-105");

            settingsBlock.classList.add("hidden");
            settingsBtn.classList.remove("active");
            settingsBtn.classList.add("hover:scale-105");

            statisticsBlock.classList.add("hidden");
            statisticsBtn.classList.remove("active");
            statisticsBtn.classList.add("hover:scale-105");
            break;

        case 'settings' :
            profileBlock.classList.add("hidden");
            profileBtn.classList.remove("active");
            profileBtn.classList.add("hover:scale-105");

            settingsBlock.classList.remove("hidden");
            settingsBtn.classList.add("active");
            settingsBtn.classList.remove("hover:scale-105");

            statisticsBlock.classList.add("hidden");
            statisticsBtn.classList.remove("active");
            statisticsBtn.classList.add("hover:scale-105");
            break;

        case 'statistics' :
            profileBlock.classList.add("hidden");
            profileBtn.classList.remove("active");
            profileBtn.classList.add("hover:scale-105");

            settingsBlock.classList.add("hidden");
            settingsBtn.classList.remove("active");
            settingsBtn.classList.add("hover:scale-105");

            statisticsBlock.classList.remove("hidden");
            statisticsBtn.classList.add("active");
            statisticsBtn.classList.remove("hover:scale-105");
            break;

        default :
            break;
    }
}


function equipArticle(){

    pfps.forEach(pfp=> {

        if(pfp.classList.contains("activePfp")){
            console.log(pfp.id)
            let idArticle = parseInt(pfp.id, 10)
            const xhr = new XMLHttpRequest();
            xhr.open("POST", `/profile/updateStyle/${pfp.id}`, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            // Envoyer les données sous forme de paire clé=valeur
            xhr.send();
            // Gérer la réponse du serveur
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let response =  JSON.parse(xhr.responseText)
                    console.log(response)
                }
            };
        }

    })

}

function showModalSuppression() {
    modal.classList.remove("hidden");
    background.classList.remove("hidden");
}