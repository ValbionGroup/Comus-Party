function checkPassword() {
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

    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/;
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('passwordConfirm').value;
    const submitButton = document.getElementById('submitButton');

    if (regex.test(password) && regex.test(passwordConfirm) && password === passwordConfirm) {
        submitButton.classList.remove("btn-disabled");
        submitButton.classList.add("btn-primary");
        submitButton.removeAttribute("disabled");
    } else {
        submitButton.classList.add("btn-disabled");
        submitButton.classList.remove("btn-primary");
        submitButton.setAttribute("disabled", "true");
    }

    if (password.length >= 8 && password.length <= 120) {
        pictoPasswordLength.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordLength.classList.add("text-green-500");
        passwordLength.classList.remove("text-red-500");
    } else {
        pictoPasswordLength.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordLength.classList.remove("text-green-500");
        passwordLength.classList.add("text-red-500");
    }

    if (/[A-Z]/.test(password)) {
        pictoPasswordCaps.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordCaps.classList.add("text-green-500");
        passwordCaps.classList.remove("text-red-500");
    } else {
        pictoPasswordCaps.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordCaps.classList.remove("text-green-500");
        passwordCaps.classList.add("text-red-500");
    }

    if (/[a-z]/.test(password)) {
        pictoPasswordLower.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordLower.classList.add("text-green-500");
        passwordLower.classList.remove("text-red-500");
    } else {
        pictoPasswordLower.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordLower.classList.remove("text-green-500");
        passwordLower.classList.add("text-red-500");
    }

    if (/\d/.test(password)) {
        pictoPasswordNumber.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordNumber.classList.add("text-green-500");
        passwordNumber.classList.remove("text-red-500");
    } else {
        pictoPasswordNumber.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordNumber.classList.remove("text-green-500");
        passwordNumber.classList.add("text-red-500");
    }

    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password)) {
        pictoPasswordSpecialCharacter.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg>';
        passwordSpecialCharacter.classList.add("text-green-500");
        passwordSpecialCharacter.classList.remove("text-red-500");
    } else {
        pictoPasswordSpecialCharacter.innerHTML = '<svg class="p-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 20L4 4m16 0L4 20"/></svg>';
        passwordSpecialCharacter.classList.remove("text-green-500");
        passwordSpecialCharacter.classList.add("text-red-500");
    }
}