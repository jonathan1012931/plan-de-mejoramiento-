import { productos } from './datos-prueba.js';

const pedidos = [
    { idPedido: 1, cliente: "Carlos Pérez", idProducto: 1, cantidad: 2, fecha: "2026-09-01" },
    { idPedido: 2, cliente: "Ana Gómez", idProducto: 3, cantidad: 1, fecha: "2026-09-02" },
    { idPedido: 3, cliente: "Luis Torres", idProducto: 4, cantidad: 3, fecha: "2026-09-03" },
    { idPedido: 4, cliente: "María Rodríguez", idProducto: 6, cantidad: 1, fecha: "2026-09-03" },
    { idPedido: 5, cliente: "Jorge Díaz", idProducto: 14, cantidad: 1, fecha: "2026-09-04" },
    { idPedido: 6, cliente: "Sofia Castro", idProducto: 2, cantidad: 2, fecha: "2026-09-05" },
    { idPedido: 7, cliente: "Andrés Ruiz", idProducto: 9, cantidad: 1, fecha: "2026-09-05" },
    { idPedido: 8, cliente: "Laura Vargas", idProducto: 10, cantidad: 4, fecha: "2026-09-06" }
];

const formateadorCOP = new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0
});

/* ---------- 1. Pintar la tabla de productos desde datos-prueba.js ---------- */
function pintarProductos(lista) {
    document.getElementById('tbody-productos').innerHTML = lista.map(p => `
        <tr>
            <td>#${p.id}</td>
            <td>${p.nombre}</td>
            <td><span class="badge">${p.categoria}</span></td>
            <td>${formateadorCOP.format(p.precio)}</td>
            <td>${p.stock < 5 ? `<span class="stock-low">${p.stock} und</span>` : `${p.stock} und`}</td>
        </tr>
    `).join('');

    const contador = document.getElementById('contador-resultados');
    if (contador) contador.innerText = `${lista.length} producto${lista.length === 1 ? '' : 's'} encontrado${lista.length === 1 ? '' : 's'}`;
}

/* ---------- 2. Buscador en vivo ---------- */
function inicializarBuscador() {
    const input = document.getElementById('buscador-productos');
    if (!input) return;

    input.addEventListener('input', () => {
        const texto = input.value.trim().toLowerCase();
        const resultado = productos.filter(p =>
            p.nombre.toLowerCase().includes(texto) ||
            p.categoria.toLowerCase().includes(texto)
        );
        pintarProductos(resultado);
    });
}

/* ---------- Análisis (usa el mismo arreglo importado) ---------- */
function inicializarAnalisis() {
    const productoMasCaro = productos.reduce((prev, current) => (current.precio > prev.precio) ? current : prev);
    document.getElementById('res-mas-caro').innerText = productoMasCaro.nombre;
    document.getElementById('res-precio-caro').innerText = formateadorCOP.format(productoMasCaro.precio);

    const sumaPrecios = productos.reduce((total, p) => total + p.precio, 0);
    document.getElementById('res-promedio').innerText = formateadorCOP.format(sumaPrecios / productos.length);

    const stockCritico = productos.filter(p => p.stock < 5);
    document.getElementById('res-stock-critico').innerText = stockCritico.length + " ítems";
    document.getElementById('res-total-items').innerText = productos.length + " productos";

    document.getElementById('tabla-stock-critico').innerHTML = stockCritico.map(p => `
        <div class="stock-row">
            <span>${p.nombre}</span>
            <span class="stock-low">Stock: ${p.stock}</span>
        </div>
    `).join('');

    const unidadesPorCat = productos.reduce((acumulador, p) => {
        acumulador[p.categoria] = (acumulador[p.categoria] || 0) + p.stock;
        return acumulador;
    }, {});

    document.getElementById('tabla-categorias').innerHTML = Object.entries(unidadesPorCat).map(([cat, total]) => `
        <div class="category-row">
            <span>${cat}</span>
            <span class="badge">${total} unidades</span>
        </div>
    `).join('');
}

function pintarPedidos() {
    document.getElementById('tbody-pedidos').innerHTML = pedidos.map(ped => `
        <tr>
            <td>#${ped.idPedido}</td>
            <td>${ped.cliente}</td>
            <td>Prod #${ped.idProducto}</td>
            <td>${ped.cantidad}</td>
            <td>${ped.fecha}</td>
        </tr>
    `).join('');
}

/* ---------- 3. Menú lateral: responde a click y a tecla Enter ---------- */
function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('abierto');
}
window.toggleMenu = toggleMenu;

function inicializarMenu() {
    const boton = document.getElementById('btn-menu');
    if (!boton) return;

    boton.addEventListener('click', toggleMenu);
    boton.addEventListener('keydown', (evento) => {
        if (evento.key === 'Enter' || evento.key === ' ') {
            evento.preventDefault();
            toggleMenu();
        }
    });
}

/* ---------- 4. Validación del formulario de producto ---------- */
function mostrarError(campo, mensaje) {
    const error = document.getElementById(`error-${campo}`);
    error.innerText = mensaje;
    error.style.display = 'block';

    const campoInput = document.getElementById(`input-${campo}`);
    if (campoInput) campoInput.classList.add('error');
}

function limpiarError(campo) {
    const error = document.getElementById(`error-${campo}`);
    error.innerText = '';
    error.style.display = 'none';

    const campoInput = document.getElementById(`input-${campo}`);
    if (campoInput) campoInput.classList.remove('error');
}

function inicializarFormulario() {
    const formulario = document.getElementById('form-producto');
    if (!formulario) return;

    formulario.addEventListener('submit', (evento) => {
        evento.preventDefault();

        const nombre = document.getElementById('input-nombre').value.trim();
        const categoria = document.getElementById('input-categoria').value;
        const precioTexto = document.getElementById('input-precio').value.trim();
        const stockTexto = document.getElementById('input-stock').value.trim();

        const precio = Number(precioTexto);
        const stock = Number(stockTexto);

        let esValido = true;

        if (nombre.length < 3) {
            mostrarError('nombre', 'El nombre debe tener mínimo 3 caracteres.');
            esValido = false;
        } else limpiarError('nombre');

        if (!categoria) {
            mostrarError('categoria', 'Selecciona una categoría.');
            esValido = false;
        } else limpiarError('categoria');

        if (precioTexto === '' || isNaN(precio) || precio <= 0) {
            mostrarError('precio', 'El precio debe ser un número mayor que cero.');
            esValido = false;
        } else limpiarError('precio');

        if (stockTexto === '' || isNaN(stock) || !Number.isInteger(stock) || stock < 0) {
            mostrarError('stock', 'El stock debe ser un número entero igual o mayor a cero.');
            esValido = false;
        } else limpiarError('stock');

        if (!esValido) return;

        const nuevoId = Math.max(...productos.map(p => p.id)) + 1;
        productos.push({ id: nuevoId, nombre, categoria, precio, stock });

        pintarProductos(productos);
        inicializarAnalisis();
        formulario.reset();
    });
}

/* ---------- Arranque ---------- */
function iniciar() {
    pintarProductos(productos);
    pintarPedidos();
    inicializarAnalisis();
    inicializarBuscador();
    inicializarMenu();
    inicializarFormulario();
}

document.addEventListener('DOMContentLoaded', iniciar);