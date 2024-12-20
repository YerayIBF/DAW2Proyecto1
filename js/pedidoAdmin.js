class PedidoAdmin {
    constructor() {
        this.pedidosSection = document.querySelector("#pedidos");
        this.tablaPedidos = null;
    }

    async init() {
        if (!this.pedidosSection) {
            console.error("Contenedor #pedidos no encontrado.");
            return;
        }

        this.renderTabla();
        await this.cargarPedidos();
    }

    renderTabla() {
        this.pedidosSection.innerHTML = `
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
        this.tablaPedidos = this.pedidosSection.querySelector("#tabla-pedidos tbody");
    }

    async cargarPedidos() {
        try {
            this.tablaPedidos.innerHTML = `<tr><td colspan="6">Cargando pedidos...</td></tr>`;

            const response = await fetch("?controller=api&action=verPedidos");
            if (!response.ok) throw new Error("Error al obtener los pedidos");
            const pedidos = await response.json();

            this.renderPedidos(pedidos);
        } catch (error) {
            console.error("Error:", error);
            this.pedidosSection.innerHTML = `<p>Error al cargar los pedidos.</p>`;
        }
    }

    renderPedidos(pedidos) {
        this.tablaPedidos.innerHTML = "";

        if (pedidos.length === 0) {
            this.tablaPedidos.innerHTML = `<tr><td colspan="6">No hay pedidos disponibles</td></tr>`;
            return;
        }

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
            this.tablaPedidos.appendChild(fila);
        });

        this.addEventListeners();
    }

    addEventListeners() {
        this.pedidosSection.querySelectorAll(".btn-editar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.editarPedido(e.target.dataset.id))
        );

        this.pedidosSection.querySelectorAll(".btn-eliminar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.eliminarPedido(e.target.dataset.id))
        );
    }

    editarPedido(id) {
        alert(`Editar pedido con ID: ${id}`);
        
    }

    eliminarPedido(id) {
        if (confirm(`¿Estás seguro de que quieres eliminar el pedido con ID: ${id}?`)) {
            fetch(`?controller=api&action=eliminarPedido&id=${id}`, {
                method: "DELETE",
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text(); 
                })
                .then((text) => {
                    console.log("Respuesta del servidor:", text); 
                    const resultado = JSON.parse(text); 
                    if (resultado.success) {
                        alert(`Pedido con ID ${id} eliminado.`);
                        this.cargarPedidos(); 
                    } else {
                        alert(`No se pudo eliminar el pedido. ${resultado.message}`);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Ocurrió un error al intentar eliminar el pedido.");
                });
            
        }
    }
    
}


document.addEventListener("DOMContentLoaded", async () => {
    const pedidoAdmin = new PedidoAdmin();
    await pedidoAdmin.init();
});
