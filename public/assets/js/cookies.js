void 0 === window._axcb && (window._axcb = []);
window._axcb.push(function (axeptio) {
    axeptio.on("cookies:complete", function (choices) {
        if (choices.google_analytics) {
            loadGoogleAnalyticsTag();
        }
    });
});

function loadGoogleAnalyticsTag() {
    const t = document.getElementsByTagName("script")[0];
    const e = document.createElement("script");
    e.async = true;
    e.src = "https://www.googletagmanager.com/gtag/js?id=G-CLHH1TD1KW";
    t.parentNode.insertBefore(e, t);
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag("js", new Date());
    gtag("config", "G-CLHH1TD1KW");
}