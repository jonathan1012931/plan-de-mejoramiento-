import { cargarProductos } from './datos-prueba.js';

async function iniciar() {

    const productos = await cargarProductos();

    console.log(productos);

    mostrarProductos(productos);
}

iniciar();