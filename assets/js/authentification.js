

function checkPassword() {
    document.getElementById("registrationForm").addEventListener("submit", function(event) {
        const PASSWORD = document.getElementById("password").value;
        const CONFIRM_PASSWORD = document.getElementById("passwordConfirm").value;
        let submitButton = document.getElementById("submitButton");
        
        if (PASSWORD !== CONFIRM_PASSWORD) {
            submitButton.disabled = true;
            // create an error message under the form
            let errorMessage = document.createElement("p");
            errorMessage.textContent = "Les mots de passe ne correspondent pas.";
            errorMessage.style.color = "red";
            document.getElementById("registrationForm").appendChild(errorMessage);
        } else {
            submitButton.disabled = false;
            document.getElementById("registrationForm").submit();
        }
    });
}

// attach function to inputs
document.addEventListener("DOMContentLoaded", () => {
    const INPUT_PASSWORD = document.getElementById("password");
    INPUT_PASSWORD.addEventListener("input", checkPassword);

    const INPUT_CONFIRM_PASSWORD = document.getElementById("passwordConfirm");
    INPUT_CONFIRM_PASSWORD.addEventListener("input", checkPassword);
});

