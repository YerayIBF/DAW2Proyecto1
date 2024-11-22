// Variables de elementos
const carritoIcono = document.getElementById('icono-carrito');
const carritoPanel = document.getElementById('carrito-panel');
const fondoOscuro = document.getElementById('fondo-oscuro');
const seguirComprando = document.getElementById('seguir-comprando');

//abrir el panel
carritoIcono.addEventListener('click', () => {
    carritoPanel.classList.add('active');
    fondoOscuro.classList.add('active'); // Mostrar fondo oscuro
    document.body.classList.add('no-scroll'); // Desactivar scroll
});

//cerrar el panel
const cerrarPanel = () => {
    carritoPanel.classList.remove('active');
    fondoOscuro.classList.remove('active'); // Ocultar fondo oscuro
    document.body.classList.remove('no-scroll'); // Activar scroll
};

//Cerrar al hacer clic en el fondo oscuro
fondoOscuro.addEventListener('click', cerrarPanel);

// Cerrar al hacer clic en "Seguir comprando"
seguirComprando.addEventListener('click', (e) => {
    e.preventDefault(); // Evita la acción predeterminada del enlace
    cerrarPanel();
});
