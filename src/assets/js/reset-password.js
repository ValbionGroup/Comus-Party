const password = document.getElementById('password');
const passwordConfirm = document.getElementById('passwordConfirm');
const submitButton = document.getElementById('submitButton');
const passwordMatchError = document.getElementById('passwordMatchError');

function checkPasswordFormat() {
    const pictoPasswordLength = document.getElementById('pictoPasswordLength');
    const pictoPasswordCaps = document.getElementById('pictoPasswordCaps');
    const pictoPasswordLower = document.getElementById('pictoPasswordLower');
    const pictoPasswordNumber = document.getElementById('pictoPasswordNumber');
    const pictoPasswordSpecialCharacter = document.getElementById('pictoPasswordSpecialCharacter');

    const passwordLength = document.getElementById('passwordLength');
    const passwordCaps = document.getElementById('passwordCaps');
    const passwordLower = document.getElementById('passwordLower');
    const passwordNumber = document.getElementById('passwordNumber');
    const passwordSpecialCharacter = document.getElementById('passwordSpecialCharacter');

    if (password.value.length >= 8 && password.value.length <= 120) {
        pictoPasswordLength.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordLength.classList.add("text-green-500");
        passwordLength.classList.remove("text-red-500");
    } else {
        pictoPasswordLength.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordLength.classList.remove("text-green-500");
        passwordLength.classList.add("text-red-500");
    }

    if (/[A-Z]/.test(password.value)) {
        pictoPasswordCaps.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordCaps.classList.add("text-green-500");
        passwordCaps.classList.remove("text-red-500");
    } else {
        pictoPasswordCaps.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordCaps.classList.remove("text-green-500");
        passwordCaps.classList.add("text-red-500");
    }

    if (/[a-z]/.test(password.value)) {
        pictoPasswordLower.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordLower.classList.add("text-green-500");
        passwordLower.classList.remove("text-red-500");
    } else {
        pictoPasswordLower.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordLower.classList.remove("text-green-500");
        passwordLower.classList.add("text-red-500");
    }

    if (/\d/.test(password.value)) {
        pictoPasswordNumber.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordNumber.classList.add("text-green-500");
        passwordNumber.classList.remove("text-red-500");
    } else {
        pictoPasswordNumber.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordNumber.classList.remove("text-green-500");
        passwordNumber.classList.add("text-red-500");
    }

    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password.value)) {
        pictoPasswordSpecialCharacter.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordSpecialCharacter.classList.add("text-green-500");
        passwordSpecialCharacter.classList.remove("text-red-500");
    } else {
        pictoPasswordSpecialCharacter.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordSpecialCharacter.classList.remove("text-green-500");
        passwordSpecialCharacter.classList.add("text-red-500");
    }
}

function isPasswordValid(password) {
    return password.length >= 8 && password.length <= 120 && // Longueur valide
        /[A-Z]/.test(password) && // Majuscule
        /[a-z]/.test(password) && // Minuscule
        /\d/.test(password) && // Chiffre
        /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password); // Caractère spécial
}

function checkPassword() {
    const passwordsMatch = password.value === passwordConfirm.value;
    const isValid = isPasswordValid(password.value);

    checkPasswordFormat();

    if (!passwordsMatch && passwordConfirm.value.length > 0) {
        passwordMatchError.classList.remove("hidden");
    } else {
        passwordMatchError.classList.add("hidden");
    }

    if (passwordsMatch && isValid) {
        submitButton.classList.remove("btn-disabled");
        submitButton.classList.add("btn-primary");
        submitButton.removeAttribute("disabled");
        passwordMatchError.classList.add("hidden");
    } else {
        submitButton.classList.add("btn-disabled");
        submitButton.classList.remove("btn-primary");
        submitButton.setAttribute("disabled", "true");
    }
}

function resetPassword(e) {
    loading(e);

    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('passwordConfirm').value;

    makeRequest('POST', '/reset-password', (response) => {
        response = JSON.parse(response);
        if (response.success) {
            window.location.href = '/login';
        } else {
            showNotification("Oups...", response.message, "red");
        }
    }, `password=${password}&passwordConfirm=${passwordConfirm}`);
}