function alternarMenu() {
    const sidebar = document.getElementById('sidebar');
    const boton = document.getElementById('btn-menu');
    if (!sidebar || !boton) return;

    const abierto = sidebar.classList.toggle('abierto');
    boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
}

function inicializarMenu() {
    const boton = document.getElementById('btn-menu');
    if (!boton) return;

    boton.addEventListener('click', alternarMenu);
    boton.addEventListener('keydown', (evento) => {
        if (evento.key === 'Enter' || evento.key === ' ') {
            evento.preventDefault();
            alternarMenu();
        }
    });
}

document.addEventListener('DOMContentLoaded', inicializarMenu);