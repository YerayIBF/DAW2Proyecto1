class DetallePedidoAdmin {
    constructor() {
        this.detalleSection = document.querySelector("#detallePedido");
        this.tablaDetalles = null;
        this.idPedido = null;
    }

    async init(idPedido) {
        if (!this.detalleSection) {
            console.error("Contenedor #detallePedido no encontrado.");
            return;
        }
        this.idPedido = idPedido;
        this.renderTabla();
        await this.cargarDetalles();
    }

    renderTabla() {
        this.detalleSection.innerHTML = `
            <h2>Detalles del Pedido #${this.idPedido}</h2>
            <table id="tabla-detalles">
                <thead>
                    <tr>
                        <th>ID Detalle</th>
                        <th>ID Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Precio Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                        <td id="total-pedido"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="botones-accion">
                <button id="btn-volver" class="btn-secundario">Volver a Pedidos</button>
            </div>
        `;
        
        this.tablaDetalles = this.detalleSection.querySelector("#tabla-detalles tbody");
        this.configurarEventos();
    }

    configurarEventos() {
        const btnVolver = this.detalleSection.querySelector("#btn-volver");
        btnVolver.addEventListener("click", async () => {
            try {
                const response = await fetch(`?controller=api&action=pedidos`);
                if (!response.ok) throw new Error("Error al cargar la vista de pedidos");
                const html = await response.text();
                
                document.getElementById("dynamic-content").innerHTML = html;
                
                if (!document.querySelector('script[src="js/pedidoAdmin.js"]')) {
                    const script = document.createElement("script");
                    script.src = "js/pedidoAdmin.js";
                    script.onload = async () => {
                        const pedidoAdmin = new PedidoAdmin();
                        await pedidoAdmin.init();
                    };
                    document.body.appendChild(script);
                } else {
                    const pedidoAdmin = new PedidoAdmin();
                    await pedidoAdmin.init();
                }
            } catch (error) {
                console.error("Error al volver a pedidos:", error);
            }
        });
    }

    async cargarDetalles() {
        try {
            this.tablaDetalles.innerHTML = `<tr><td colspan="6">Cargando detalles...</td></tr>`;

            const response = await fetch(`?controller=api&action=verDetalles&id=${this.idPedido}`);
            
            // Si la respuesta es 404 o cualquier error, mostramos "No hay productos"
            if (!response.ok) {
                this.renderDetalles([]); // Llamamos a renderDetalles con un array vacío
                return;
            }

            const data = await response.json();

            if (!data.success || !data.detalles) {
                this.renderDetalles([]); // Llamamos a renderDetalles con un array vacío
                return;
            }

            this.renderDetalles(data.detalles);
        } catch (error) {
            console.error("Error:", error);
            this.renderDetalles([]); // En caso de cualquier error, mostramos "No hay productos"
        }
    }

    renderDetalles(detalles) {
        this.tablaDetalles.innerHTML = "";
        let total = 0;
    
        if (!detalles || detalles.length === 0) {
            this.tablaDetalles.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        No hay productos asignados a este pedido
                    </td>
                </tr>`;
            const totalElement = this.detalleSection.querySelector("#total-pedido");
            totalElement.textContent = "$0.00";
            return;
        }
    
        detalles.forEach((producto) => {
            const precioUnitario = parseFloat(producto.Precio_Unitario) || 0;
            const cantidad = parseInt(producto.Cantidad) || 0;
            const precioTotal = parseFloat(producto.PrecioTotal) || 0;
            
            total += precioTotal;
    
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${producto.ID_DetallePedido}</td>
                <td>${producto.ID_Producto}</td>
                <td>${cantidad}</td>
                <td>$${precioUnitario.toFixed(2)}</td>
                <td>$${precioTotal.toFixed(2)}</td>
                <td>
                    <button class="btn-eliminar" data-id="${producto.ID_DetallePedido}">Eliminar</button>
                </td>
            `;
            this.tablaDetalles.appendChild(fila);
        });
    
        const totalElement = this.detalleSection.querySelector("#total-pedido");
        totalElement.textContent = `$${total.toFixed(2)}`;
    
        this.addEventListeners();
    }

    addEventListeners() {
        const botonesEliminar = this.tablaDetalles.querySelectorAll('.btn-eliminar');
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', () => {
                const idDetalle = boton.dataset.id;
                this.eliminarDetalle(idDetalle);
            });
        });
    }

    async eliminarDetalle(idDetalle) {
        if (confirm(`¿Estás seguro de que quieres eliminar este producto del pedido?`)) {
            try {
                const response = await fetch(`?controller=api&action=eliminarDetallePedido&id=${idDetalle}`, {
                    method: "DELETE"
                });

                const resultado = await response.json();
                
                if (resultado.success) {
                    await this.cargarDetalles();
                    alert("Producto eliminado correctamente");
                } else {
                    console.error("Error del servidor:", resultado.message);
                    const detalles = await this.verificarSiDetalleExiste(idDetalle);
                    if (!detalles) {
                        await this.cargarDetalles();
                        alert("Producto eliminado correctamente");
                    } else {
                        alert("No se pudo eliminar el producto del pedido");
                    }
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar eliminar el producto");
            }
        }
    }

    async verificarSiDetalleExiste(idDetalle) {
        try {
            const response = await fetch(`?controller=api&action=verDetalles&id=${this.idPedido}`);
            if (!response.ok) return false;
            
            const data = await response.json();
            if (!data.success || !data.detalles) return false;
            
            return data.detalles.some(detalle => detalle.ID_DetallePedido === idDetalle);
        } catch (error) {
            return false;
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idPedido = urlParams.get('id');
    
    if (idPedido) {
        const detallePedidoAdmin = new DetallePedidoAdmin();
        detallePedidoAdmin.init(idPedido);
    }
});