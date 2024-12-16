/**
 *   @file checkout.js
 *   @author Conchez-Boueytou Robin
 *   @brief Fichier js permettant de gérer le formulaire de paiement
 *   @details
 *   @date 16/12/2024
 *   @version 0.1
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


function removeArticle(id){
    let article = document.getElementById(id);

    article.remove();
    price();
}


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
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    let currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function() {
        let a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;


        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items absolute top-[72px] w-full bg-night-1 rounded-b-lg");

        this.parentNode.appendChild(a);

        if(arr !== null){
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].label.substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].label.substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].label.substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
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
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        let x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode === 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode === 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode === 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (let i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
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
