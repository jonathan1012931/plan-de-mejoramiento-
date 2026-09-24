# SportZone - Prototipo responsive

Proyecto preparado para los 4 puntos del ejercicio:

1. **Mobile First** en todo el prototipo.
   - 0–767 px: móvil
   - 768–1199 px: tablet
   - 1200 px o más: escritorio
2. **Menú lateral colapsable solo con CSS**.
   - `.sidebar` = cerrado en móvil.
   - `.sidebar abierto` = abierto.
   - No se usa JavaScript para abrir/cerrar el menú; el botón es visual hasta el día indicado.
3. **componentes.html** contiene la guía de estilo con cinco estados: normal, hover, focus, active y disabled.
4. Pruebas recomendadas: 360 px, 768 px, 1440 px y teléfono real.

## Abrir en PC

Puedes abrir `login.html` directamente en Chrome, pero para probar el teléfono usa VS Code + Live Server.

1. Abre esta carpeta en VS Code.
2. Instala la extensión **Live Server** (Ritwick Dey).
3. Clic derecho en `dashboard.html` → **Open with Live Server**.
4. En la terminal ejecuta `ipconfig` y busca la **Dirección IPv4** del Wi-Fi.
5. Conecta el teléfono y el PC a la misma Wi-Fi.
6. En el teléfono abre `http://TU-IP:5500/dashboard.html`.

Ejemplo: `http://192.168.1.20:5500/dashboard.html`

## Estructura

- `login.html` — inicio de sesión de demostración.
- `dashboard.html` — panel responsive.
- `productos.html` — tabla y formulario de demostración.
- `componentes.html` — guía de estilo.
- `css/tokens.css` — variables de diseño.
- `css/stilos.css` — estilos globales Mobile First.
- `assets/img/logo.png` — logo original del proyecto.

## Nota

El menú se deja con `.abierto` aplicado manualmente, según el requisito del ejercicio. El JavaScript del botón se implementará posteriormente.
