
// js/datos-prueba.js

export let productos = [];

export async function cargarProductos() {

    try {

        const respuesta = await fetch('api/productos.php');

        if (!respuesta.ok) {
            throw new Error('No se pudieron cargar los productos');
        }

        productos = await respuesta.json();

        console.log('Productos desde MySQL:', productos);

        return productos;

    } catch (error) {

        console.error('Error:', error);

        productos = [];

        return productos;
    }
}
