document.addEventListener("DOMContentLoaded", () => {
    const tablaPedidos = document.querySelector("#tabla-pedidos tbody");

    
    async function cargarPedidos() {
        try {
            const response = await fetch("");
            if (!response.ok) throw new Error("Error al obtener los pedidos");
            const pedidos = await response.json();

            // Limpiar la tabla
            tablaPedidos.innerHTML = "";

            // Mostrar los pedidos en la tabla
            pedidos.forEach(pedido => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${pedido.ID_Pedido}</td>
                    <td>${pedido.Fecha_Pedido}</td>
                    <td>${pedido.Precio_Total}</td>
                    <td>${pedido.Estado}</td>
                    <td>${pedido.ID_Usuario}</td>
                    <td>
                        <button class="btn-editar">Editar</button>
                        <button class="btn-eliminar">Eliminar</button>
                        
                    </td>
                `;
                tablaPedidos.appendChild(fila);
            });
        } catch (error) {
            console.error("Error:", error);
        }
    }

    // Cargar pedidos al cargar la página
    cargarPedidos();
});
