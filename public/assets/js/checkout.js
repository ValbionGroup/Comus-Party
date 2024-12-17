/**
 *   @file checkout.js
 *   @author Conchez-Boueytou Robin
 *   @brief Fichier js permettant de gérer le formulaire de paiement
 *   @details
 *   @date 16/12/2024
 *   @version 0.2
 */

function formatZipCode(input) {
    input.value = input.value
        .replace(/\D/g, '') // Supprimer les caractères non numériques
}

function formatCardNumber(input) {
    input.value = input.value
        .replace(/\D/g, '') // Supprimer les caractères non numériques
        .replace(/(\d{4})(?=\d)/g, '$1 '); // Ajouter des espaces après chaque groupe de 4 chiffres
}

function formatCryptogram(input) {
    input.value = input.value
        .replace(/\D/g, '') // Supprimer les caractères non numériques
}

function formatExpirationDate(input) {
    input.value = input.value
        .replace(/\D/g, '') // Supprimer les caractères non numériques
        .replace(/(\d{2})(?=\d)/g, '$1/') // Ajouter un / après les 2 premiers chiffres
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
        if (event.key === "Enter") {
            if (form.checkValidity()) {
                return true;
            } else {
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


const getData = async (searchTerm) => {
    if (searchTerm.length < 3) {
        return null;
    }

    return fetch(`https://api-adresse.data.gouv.fr/search/?q=${searchTerm}&type=housenumber&autocomplete=1`, {
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json"
        }
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (myJson) {
            return myJson.features.map((adr) => {
                return {
                    label: adr.properties.label,
                    street: adr.properties.name,
                    city: adr.properties.city,
                    zipCode: adr.properties.postcode,
                };
            });
        })
        .catch(function (error) {
            console.error(error);
        });
};

function autocomplete(inp, arr) {
    let currentFocus;
    inp.addEventListener("input", function() {
        let a, b, i, val = this.value;
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;


        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items absolute top-[72px] w-full bg-night-1 rounded-b-lg");

        this.parentNode.appendChild(a);

        if(arr !== null){
            for (i = 0; i < arr.length; i++) {
                if (arr[i].label.substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + arr[i].label.substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].label.substr(val.length);
                    b.innerHTML += "<input type='hidden' value='" + arr[i].street + "'>";

                    b.addEventListener("click", chooseAdress,false);
                    b.myParam = arr[i];
                    function chooseAdress(arr){
                        inp.value = this.getElementsByTagName("input")[0].value;
                        document.getElementById("city").value = arr.currentTarget.myParam.city;
                        document.getElementById("zipCode").value = arr.currentTarget.myParam.zipCode;
                        document.getElementById("country").value = "France";
                        closeAllLists();
                    }

                    inp.setAttribute("class", "!rounded-b-none input h-10 w-full mt-2");
                    b.setAttribute("class", "ml-2");
                    a.appendChild(b);
                }
            }
        }
    });
    inp.addEventListener("keydown", function(e) {
        let x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode === 40) {
            currentFocus++;
            addActive(x);
        } else if (e.keyCode === 38) {
            currentFocus--;
            addActive(x);
        } else if (e.keyCode === 13) {
            e.preventDefault();
            if (currentFocus > -1) {
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        for (let i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        let x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
            if (elmnt !== x[i] && elmnt !== inp) {
                x[i].parentNode.removeChild(x[i]);
                inp.setAttribute("class", "!rounded-b-lg input h-10 w-full mt-2");
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

adressInput = document.getElementById("adresse");
adressInput.addEventListener("input", async function () {
    autocomplete(document.getElementById("adresse"), await getData(adressInput.value));
});
