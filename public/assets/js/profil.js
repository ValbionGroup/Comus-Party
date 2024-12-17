function afficher(section) {
    let profileBtn = document.getElementById('btnProfile');
    let settingsBtn = document.getElementById('btnSettings');
    let statisticsBtn = document.getElementById('btnStatistics');

    let profileBlock = document.getElementById('profile');
    let settingsBlock = document.getElementById('settings');
    let statisticsBlock = document.getElementById('statistics');

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

function closeModal() {
    let modal = document.getElementById('modalConfirmationSuppression');
    let background = document.getElementById('backgroundModal');
    modal.classList.add("hidden");
    background.classList.add("hidden");
}

function showModalSuppression() {
    let modal = document.getElementById('modalConfirmationSuppression');
    let background = document.getElementById('backgroundModal');
    modal.classList.remove("hidden");
    background.classList.remove("hidden");
}