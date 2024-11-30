/**
 *   @file checkout.js
 *   @author Conchez-Boueytou Robin
 *   @brief Fichier js permettant de gérer le formulaire de paiement
 *   @details
 *   @date 29/11/2024
 *   @version 0.1
 */

function formatCardNumber(input) {
    input.value = input.value
        .replace(/\D/g, '') // Supprimer les caractères non numériques
        .replace(/(\d{4})(?=\d)/g, '$1 '); // Ajouter des espaces après chaque groupe de 4 chiffres

}

function formatCryptogram(input) {
    input.value = input.value
        .replace(/\D/g, '') // Supprimer les caractères non numériques
}

function price(){

    let prices = document.querySelectorAll('.articlePrice');

    let totalPrice = 0;
    prices.forEach(price => {
        totalPrice += parseFloat(price.textContent);
    });
    document.getElementById('total').innerText = totalPrice + "€";
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#checkoutForm');
    const submitButton = document.querySelector('#submit');
    document.getElementById("dateCC").valueAsDate = new Date();

    // Fonction pour vérifier si le formulaire est valide
    function checkFormValidity() {
        // Si le formulaire est valide, active le bouton submit
        if (form.checkValidity()) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    // Écouteurs d'événements sur les champs de formulaire pour vérifier la validité
    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', checkFormValidity);
    });

    // Vérifie la validité du formulaire au chargement de la page
    checkFormValidity();

    // Empêche l'envoi du formulaire par "Entrée" si le formulaire est invalide
    form.addEventListener('keydown', function (event) {
        // Si la touche pressée est "Entrée" (keyCode 13) et que le formulaire est valide
        if (event.key === "Enter") {
            if (form.checkValidity()) {
                // Laisser le formulaire se soumettre
                return true;
            } else {
                // Empêche la soumission si le formulaire n'est pas valide
                event.preventDefault();
            }
        }
    });

    // Soumettre le formulaire lorsqu'on clique sur le bouton si valide
    submitButton.addEventListener('click', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();  // Si le formulaire n'est pas valide, on empêche l'envoi
        }
    });

    price();
});


function removeArticle(id){
    let article = document.getElementById(id);

    article.remove();
    price();
}
