
document.addEventListener("DOMContentLoaded", async function() {
    
    await cargarPedidos();
});
async function cargarPedidos() {
    const pedidosSection = document.querySelector("#pedidos");

    if (!pedidosSection) {
        console.error("Contenedor #pedidos no encontrado.");
        return;
    }
    
    try {
        pedidosSection.innerHTML = `
        <h2>PEDIDOS</h2>
        <table id="tabla-pedidos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        `;

        const tablaPedidos = document.querySelector("#tabla-pedidos tbody");

        tablaPedidos.innerHTML = `<tr><td colspan="6">Cargando pedidos...</td></tr>`;

        // Obtener los pedidos del servidor
        const response = await fetch("?controller=api&action=verPedidos");
        if (!response.ok) throw new Error("Error al obtener los pedidos");
        const pedidos = await response.json();

        // Limpiar la tabla
        tablaPedidos.innerHTML = "";

        if (pedidos.length === 0) {
            tablaPedidos.innerHTML = `<tr><td colspan="6">No hay pedidos disponibles</td></tr>`;
            return;
        }

        // Crear las filas dinámicamente
        pedidos.forEach((pedido) => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${pedido.ID_Pedido}</td>
                <td>${pedido.Fecha_Pedido}</td>
                <td>${pedido.Precio_Total}</td>
                <td>${pedido.Estado}</td>
                <td>${pedido.ID_Usuario}</td>
                <td>
                    <button class="btn-editar" data-id="${pedido.ID_Pedido}">Editar</button>
                    <button class="btn-eliminar" data-id="${pedido.ID_Pedido}">Eliminar</button>
                </td>
            `;
            tablaPedidos.appendChild(fila);
        });

        // Agregar eventos a los botones después de renderizar
        document.querySelectorAll(".btn-editar").forEach((btn) =>
            btn.addEventListener("click", (e) => editarPedido(e.target.dataset.id))
        );

        document.querySelectorAll(".btn-eliminar").forEach((btn) =>
            btn.addEventListener("click", (e) => eliminarPedido(e.target.dataset.id))
        );
    } catch (error) {
        console.error("Error:", error);
        pedidosSection.innerHTML = `<p>Error al cargar los pedidos.</p>`;
    }
}

function editarPedido(id) {
    alert(`Editar pedido con ID: ${id}`);
   
}

function eliminarPedido(id) {
    if (confirm(`¿Estás seguro de que quieres eliminar el pedido con ID: ${id}?`)) {
        alert(`Pedido con ID ${id} eliminado`);
      
    }
}


cargarPedidos()



