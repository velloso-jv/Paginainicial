// script.js

const hamburguer = document.querySelector('.menu-hamburguer');
const menu = document.querySelector('.menu');

hamburguer.addEventListener('click', (e) => {
    hamburguer.classList.toggle('active');
    menu.classList.toggle('active');
});

document.addEventListener('click', (e) => {
    const clicouFora =
        !menu.contains(e.target) &&
        !hamburguer.contains(e.target);

    if (clicouFora) {
        menu.classList.remove('active');
        hamburguer.classList.remove('active');
    }
});