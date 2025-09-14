// Helper seguro para eventos
function onElement(selector, event, callback) {
    const element = document.querySelector(selector);
    if (element) {
        element.addEventListener(event, callback);
    }
}
