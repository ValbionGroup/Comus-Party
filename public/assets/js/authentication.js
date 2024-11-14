

/**
 * @brief Fonction executée lorsque la page est chargée.
 *
 * @details Elle permet de binder les fonctions de verification des champs
 * de formulaire aux events "input" des inputs correspondants.
 *
 * @return void
 */
window.onload = function() {
    // Récupère les inputs du formulaire
    // Ajoute un listener sur l'event "input" des inputs
    const INPUT_USERNAME = document.getElementById("username");
    INPUT_USERNAME.addEventListener("input", checkUsernameRequirements);

    const INPUT_EMAIL = document.getElementById("email");
    INPUT_EMAIL.addEventListener("input", checkEmailRequirements);

    const INPUT_PASSWORD = document.getElementById("password");
    INPUT_PASSWORD.addEventListener("input", checkPasswordRequirements);

    const INPUT_CONFIRM_PASSWORD = document.getElementById("passwordConfirm");
    INPUT_CONFIRM_PASSWORD.addEventListener("input", checkPasswordsMatch);

    // Ajoute un listener sur l'event "submit" du formulaire
    const FORM = document.getElementById("registrationForm");
    FORM.addEventListener("submit", function(event) {
        // Empeche la soumission du formulaire
        event.preventDefault();
        // Envoie les données d'inscription
        sendAuthData(); });
}

/**
 * @brief Vérifie si le nom d'utilisateur respecte les exigences spécifiées.
 *
 * @details La fonction vérifie si le nom d'utilisateur:
 * - a une longueur minimale définie
 * - ne contient pas de caractères spéciaux interdits
 * Si le nom d'utilisateur ne respecte pas ces exigences, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkUsernameRequirements() {
    // Constantes
    const MIN_USERNAME_LENGTH = 3; // Longueur minimale du nom d'utilisateur
    const FORBIDDEN_CHARACTERS = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/; // Caractères interdits
    const USERNAME = document.getElementById("username").value; // Nom d'utilisateur
    // Variables
    let incorrectUsernameFormat = document.getElementById("incorrectUsernameFormat");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    // Le nom d'utilisateur doit contenir au moins 3 caractères
    if (USERNAME.length < MIN_USERNAME_LENGTH) {
        submitButton.disabled = true;
        incorrectUsernameFormat.innerHTML="Le nom d'utilisateur doit contenir au moins " + MIN_USERNAME_LENGTH + " caractères";
        incorrectUsernameFormat.style.display = "block";
    // Le nom d'utilisateur ne doit pas contenir de caractères speciaux
    } else if (FORBIDDEN_CHARACTERS.test(USERNAME)) {
        submitButton.disabled = true;
        incorrectUsernameFormat.innerHTML="Le nom d'utilisateur ne doit pas contenir de caractères speciaux";
        incorrectUsernameFormat.style.display = "block";
    // Tout est bon, le bouton de soumission est activé
    } else {
        submitButton.disabled = false;
        incorrectUsernameFormat.style.display = "none";
    }
}

/**
 * @brief Verifie si l'email est valide (contient un @).
 *
 * @details Si l'email ne contient pas de @, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkEmailRequirements() {
    // Constantes
    const EMAIL = document.getElementById("email").value; // Email
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex pour valider le format de l'email
    // Variables
    let incorrectEmailFormat = document.getElementById("incorrectEmailFormat");
    let submitButton = document.getElementById("submitButton");
    
    // Vérifications
    if (EMAIL_REGEX.test(EMAIL)) {
        incorrectEmailFormat.style.display = "none";
        submitButton.disabled = false;
    } else {
        incorrectEmailFormat.innerHTML = "Format d'email invalide, veuillez entrer un email valide.";
        incorrectEmailFormat.style.display = "block";
        submitButton.disabled = true;
    }
}

/**
 * @brief Verifie si le mot de passe correspond aux critères de sécurité.
 *
 * @details La fonction verifie si le mot de passe:
 * - contient au moins 8 caracteres
 * - contient au moins une majuscule
 * - contient au moins une minuscule
 * - contient au moins un chiffre
 * - contient au moins un caractère spécial
 * Si le mot de passe ne correspond pas aux critères, le bouton de soumission est désactivé
 * et un message d'erreur est affiché.
 *
 * @return void
 */
function checkPasswordRequirements() {
    // Constantes
    const MIN_PASSWORD_LENGTH = 8; // Longueur minimale du mot de passe
    const UPPERCASE_LETTER = /[A-Z]/; // Majuscule
    const LOWERCASE_LETTER = /[a-z]/; // Minuscule
    const NUMBERS = /\d/; // Chiffre
    const SPECIAL_CHARACTER = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/; // Caractère special
    const PASSWORD = document.getElementById("password").value; // Mot de passe
    // Variables
    let incorrectPasswordFormat = document.getElementById("incorrectPasswordFormat");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    // Le mot de passe doit contenir au moins 8 caractères
    if (PASSWORD.length < MIN_PASSWORD_LENGTH) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit être au moins de " + MIN_PASSWORD_LENGTH + " caractères";
        incorrectPasswordFormat.style.display = "block";
    // Le mot de passe doit contenir au moins une majuscule
    } else if (!UPPERCASE_LETTER.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins une majuscule";
        incorrectPasswordFormat.style.display = "block";
    // Le mot de passe doit contenir au moins une minuscule
    } else if (!LOWERCASE_LETTER.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins une minuscule";
        incorrectPasswordFormat.style.display = "block";
    // Le mot de passe doit contenir au moins un chiffre
    } else if (!NUMBERS.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins un chiffre";
        incorrectPasswordFormat.style.display = "block";
    // Le mot de passe doit contenir au moins un caractère special
    } else if (!SPECIAL_CHARACTER.test(PASSWORD)) {
        submitButton.disabled = true;
        incorrectPasswordFormat.innerHTML="Le mot de passe doit contenir au moins un caractère special";
        incorrectPasswordFormat.style.display = "block";
    // Tout est bon, le bouton de soumission est activé
    } else {
        submitButton.disabled = false;
        incorrectPasswordFormat.style.display = "none";
    }
}

/**
 * @brief Vérifie si les mots de passe sont identiques.
 *
 * @details Si le mot de passe de confirmation n'est pas identique au mot de passe,
 * le bouton de soumission est désactivé et un message d'erreur est affiché.
 *
 * @return void
 */
function checkPasswordsMatch() {
    // Constantes
    const PASSWORD = document.getElementById("password").value;
    const CONFIRM_PASSWORD = document.getElementById("passwordConfirm").value;
    // Variables
    let notMachingPasswords = document.getElementById("notMachingPasswords");
    let submitButton = document.getElementById("submitButton");
        
    // Vérifications
    if (CONFIRM_PASSWORD !== PASSWORD) {
        submitButton.disabled = true;
        notMachingPasswords.innerHTML="Les mots de passe ne sont pas identiques";
        notMachingPasswords.style.display = "block";
    } else {
        submitButton.disabled = false;
        notMachingPasswords.style.display = "none";
    }
}

/**
 * @brief Envoie les données d'inscription de l'utilisateur au serveur.
 * 
 * @details Récupère les valeurs des champs de formulaire et les envoie
 * au serveur en tant que données JSON via une requête POST.
 * Selon la réponse du serveur, affiche un message de succès
 * ou d'echec.
 * 
 * @return void
 * 
 * @TODO Traitement lors de la réception des données (data.success)
 */
function sendAuthData() {
    // Récupération des données
    const USERNAME = document.getElementById("username").value;
    const EMAIL = document.getElementById("email").value;
    const PASSWORD = document.getElementById("password").value;

    // Variables
    let resultMessage = document.getElementById("resultMessage");
    // Envoi des données au serveur avec une requête POST
    fetch('http://localhost:8000/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json'},
        body: JSON.stringify({
            username: USERNAME,
            email: EMAIL,
            password: PASSWORD
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Affichage du message de succès
            resultMessage.innerHTML = data.message;
            resultMessage.style.display = "block";
            resultMessage.style.color = "green";

        } else {
            // Affichage du message d'erreur
            resultMessage.innerHTML = data.message;
            resultMessage.style.display = "block";
            resultMessage.style.color = "red";
        }
    })
}
