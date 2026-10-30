// Apstiprinājums pirms dzēšanas: formām ar data-confirm atribūtu.
document.addEventListener('submit', (event) => {
    const message = event.target.dataset?.confirm;

    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});
