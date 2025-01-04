class DetallePedidoAdmin {
    constructor() {
        this.detalleSection = document.querySelector("#detallePedido");
        this.tablaDetalles = null;
        this.idPedido = null;
        this.currencyConverter = new CurrencyConverter('fca_live_sAtIOrfXJE7hBnhZ3zXryTuVT9djWZ7lfpoVAznT');
        this.selectedCurrency = localStorage.getItem('selectedCurrency') || 'EUR';
        this.detallesData = [];
        this.editandoFilas = new Set();
        this.filtros = JSON.parse(localStorage.getItem('filtrosDetalles')) || {
            idDetalle: '',
            idProducto: '',
            cantidad: '',
            precioUnitario: ''
        };
    }

    async init(idPedido) {
        if (!this.detalleSection) {
            console.error("Contenedor #detallePedido no encontrado.");
            return;
        }
        this.idPedido = idPedido;
        await this.currencyConverter.fetchRates();
        this.renderTabla();
        await this.cargarDetalles();
        this.initEventos();
        this.initCrearDetalleFormulario();
        this.restaurarFiltros();
    }

    restaurarFiltros() {
        Object.entries(this.filtros).forEach(([key, value]) => {
            const elemento = this.detalleSection.querySelector(`#filtro-${key}`);
            if (elemento) elemento.value = value;
        });
    }

    renderTabla() {
        this.detalleSection.innerHTML = `
            <h2>Detalles del Pedido #${this.idPedido}</h2>
            <button id="btn-crear-detalle" class="mb-3">Añadir Producto al Pedido</button>
            <div id="form-crear-detalle" style="display: none;" class="mb-3">
                <h3>Añadir Nuevo Producto</h3>
                <form id="detalle-form" class="grid gap-3">
                    <div>
                        <label>ID Producto:</label><br>
                        <input type="number" id="producto-id" required class="form-control">
                    </div>
                    <div>
                        <label>Cantidad:</label><br>
                        <input type="number" id="cantidad" required class="form-control" min="1">
                    </div>
                    <div>
                        <button type="submit" class="btn-primary">Guardar</button>
                        <button type="button" id="btn-cancelar-crear">Cancelar</button>
                    </div>
                </form>
            </div>

            <div class="filtros-container mb-3">
                <input type="text" id="filtro-idDetalle" placeholder="Filtrar por ID Detalle" class="me-2">
                <input type="text" id="filtro-idProducto" placeholder="Filtrar por ID Producto" class="me-2">
                <input type="number" id="filtro-cantidad" placeholder="Cantidad mínima" class="me-2">
                <input type="number" id="filtro-precioUnitario" placeholder="Precio mínimo" class="me-2">
                <button id="btn-limpiar-filtros">Limpiar filtros</button>
                <div class="ajusteLabelCambioMoneda">
                    <label for="currency-selector">Cambiar moneda:</label>
                    <select id="currency-selector" class="me-2">
                        <option value="EUR">EUR</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
            </div>

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

        const currencySelectorElement = this.detalleSection.querySelector('#currency-selector');
        if (currencySelectorElement) {
            currencySelectorElement.value = this.selectedCurrency;
            currencySelectorElement.addEventListener('change', (e) => {
                this.selectedCurrency = e.target.value;
                localStorage.setItem('selectedCurrency', this.selectedCurrency);
                this.aplicarFiltros();
            });
        }
    }

    initEventos() {
        const filtroIdDetalle = this.detalleSection.querySelector('#filtro-idDetalle');
        const filtroIdProducto = this.detalleSection.querySelector('#filtro-idProducto');
        const filtroCantidad = this.detalleSection.querySelector('#filtro-cantidad');
        const filtroPrecioUnitario = this.detalleSection.querySelector('#filtro-precioUnitario');
        const btnLimpiar = this.detalleSection.querySelector('#btn-limpiar-filtros');

        const actualizarFiltros = (campo, valor) => {
            this.filtros[campo] = valor;
            localStorage.setItem('filtrosDetalles', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        };

        filtroIdDetalle.addEventListener('input', () => actualizarFiltros('idDetalle', filtroIdDetalle.value));
        filtroIdProducto.addEventListener('input', () => actualizarFiltros('idProducto', filtroIdProducto.value));
        filtroCantidad.addEventListener('input', () => actualizarFiltros('cantidad', filtroCantidad.value));
        filtroPrecioUnitario.addEventListener('input', () => actualizarFiltros('precioUnitario', filtroPrecioUnitario.value));

        btnLimpiar.addEventListener('click', () => {
            Object.keys(this.filtros).forEach(key => {
                this.filtros[key] = '';
                const elemento = this.detalleSection.querySelector(`#filtro-${key}`);
                if (elemento) elemento.value = '';
            });
            localStorage.setItem('filtrosDetalles', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        });
    }

    aplicarFiltros() {
        let detallesFiltrados = this.detallesData.filter(detalle => {
            const cumpleIdDetalle = !this.filtros.idDetalle || 
                detalle.ID_DetallePedido.toString().includes(this.filtros.idDetalle);
            
            const cumpleIdProducto = !this.filtros.idProducto || 
                detalle.ID_Producto.toString().includes(this.filtros.idProducto);
            
            const cumpleCantidad = !this.filtros.cantidad || 
                parseInt(detalle.Cantidad) >= parseInt(this.filtros.cantidad);
            
            const cumplePrecioUnitario = !this.filtros.precioUnitario || 
                parseFloat(detalle.Precio_Unitario) >= parseFloat(this.filtros.precioUnitario);

            return cumpleIdDetalle && cumpleIdProducto && cumpleCantidad && cumplePrecioUnitario;
        });

        this.renderDetalles(detallesFiltrados);
    }

    editarDetalle(idDetalle) {
        if (this.editandoFilas.has(idDetalle)) return;

        const fila = this.tablaDetalles.querySelector(`tr[data-id="${idDetalle}"]`);
        const detalle = this.detallesData.find(d => d.ID_DetallePedido == idDetalle);

        if (!fila || !detalle) return;

        this.editandoFilas.add(idDetalle);
        fila.setAttribute('data-original', fila.innerHTML);

        const celdas = fila.getElementsByTagName('td');

        celdas[1].innerHTML = `<input type="number" class="form-control" value="${detalle.ID_Producto}" min="1" />`;
        celdas[2].innerHTML = `<input type="number" class="form-control" value="${detalle.Cantidad}" min="1" />`;
        celdas[3].innerHTML = `<input type="number" class="form-control" value="${detalle.Precio_Unitario}" step="0.01" min="0" />`;

        celdas[5].innerHTML = `
            <button class="btn-guardar" data-id="${idDetalle}">Guardar</button>
            <button class="btn-cancelar" data-id="${idDetalle}">Cancelar</button>
        `;

        const btnGuardar = celdas[5].querySelector('.btn-guardar');
        const btnCancelar = celdas[5].querySelector('.btn-cancelar');

        btnGuardar.addEventListener('click', () => this.guardarCambiosDetalle(idDetalle));
        btnCancelar.addEventListener('click', () => this.cancelarEdicionDetalle(idDetalle));
    }

    async guardarCambiosDetalle(idDetalle) {
        const fila = this.tablaDetalles.querySelector(`tr[data-id="${idDetalle}"]`);
        if (!fila) return;
    
        const idProducto = parseInt(fila.querySelector('td:nth-child(2) input').value);
        const cantidad = parseInt(fila.querySelector('td:nth-child(3) input').value);
        const precioUnitario = parseFloat(fila.querySelector('td:nth-child(4) input').value);
    
        const datosActualizados = {
            ID_DetallePedido: parseInt(idDetalle),
            ID_Producto: idProducto,
            Cantidad: cantidad,
            Precio_Unitario: precioUnitario
        };
    
        try {
            const response = await fetch('?controller=api&action=actualizarDetallePedido', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datosActualizados)
            });
    
            const responseText = await response.text();
            let resultado;
            
            try {
                resultado = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Response text:', responseText);
                throw new Error('Respuesta del servidor no válida');
            }
    
            if (resultado.success) {
                alert('Detalle actualizado correctamente');
                this.editandoFilas.delete(idDetalle);
                await this.cargarDetalles();
            } else {
                throw new Error(resultado.message || 'Error al actualizar el detalle');
            }
        } catch (error) {
            console.error('Error completo:', error);
            alert('Error al actualizar el detalle: ' + error.message);
        }
    }

    cancelarEdicionDetalle(idDetalle) {
        const fila = this.tablaDetalles.querySelector(`tr[data-id="${idDetalle}"]`);
        if (!fila) return;

        const contenidoOriginal = fila.getAttribute('data-original');
        if (contenidoOriginal) {
            fila.innerHTML = contenidoOriginal;
            this.editandoFilas.delete(idDetalle);
            this.addEventListeners();
        }
    }


    async obtenerPrecioProducto(idProducto) {
        try {
            const response = await fetch(`?controller=api&action=verProductos`);
            const productos = await response.json();
            const producto = productos.find(p => p.ID_Producto == idProducto);
            return producto ? producto.Precio : null;
        } catch (error) {
            console.error('Error al obtener precio del producto:', error);
            return null;
        }
    }

    initCrearDetalleFormulario() {
        const btnCrearDetalle = this.detalleSection.querySelector('#btn-crear-detalle');
        const formCrearDetalle = this.detalleSection.querySelector('#form-crear-detalle');
        const formulario = this.detalleSection.querySelector('#detalle-form');
        const btnCancelarCrear = this.detalleSection.querySelector('#btn-cancelar-crear');
    
        btnCrearDetalle.addEventListener('click', () => {
            formCrearDetalle.style.display = 'block';
            btnCrearDetalle.style.display = 'none';
        });
    
        btnCancelarCrear.addEventListener('click', () => {
            formCrearDetalle.style.display = 'none';
            btnCrearDetalle.style.display = 'block';
            formulario.reset();
        });
    
        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();
    
            const idProducto = document.querySelector('#producto-id').value;
            const cantidad = document.querySelector('#cantidad').value;
    
            try {
                const precioUnitario = await this.obtenerPrecioProducto(idProducto);
    
                if (!precioUnitario) {
                    alert('Error: No se pudo obtener el precio del producto');
                    return;
                }
    
                const nuevoDetalle = {
                    ID_Pedido: this.idPedido,
                    ID_Producto: idProducto,
                    Cantidad: cantidad,
                    Precio_Unitario: precioUnitario
                };
    
                const response = await fetch('?controller=api&action=crearDetallePedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(nuevoDetalle)
                });
    
                const responseText = await response.text();
                let resultado;
                
                try {
                    resultado = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Response text:', responseText);
                    throw new Error('Respuesta del servidor no válida');
                }
    
                if (resultado.success) {
                    alert('Producto añadido correctamente');
                    formCrearDetalle.style.display = 'none';
                    btnCrearDetalle.style.display = 'block';
                    formulario.reset();
                    await this.cargarDetalles();
                } else {
                    throw new Error(resultado.message || 'Error al añadir el producto');
                }
            } catch (error) {
                console.error('Error completo:', error);
                alert('Error al añadir el producto: ' + error.message);
            }
        });
    }
    

    async cargarDetalles() {
        try {
            const response = await fetch(`?controller=api&action=verDetalles&id=${this.idPedido}`);
            
            if (!response.ok) {
                this.detallesData = [];
                this.renderDetalles([]);
                return;
            }

            const data = await response.json();

            if (!data.success || !data.detalles) {
                this.detallesData = [];
                this.renderDetalles([]);
                return;
            }

            this.detallesData = data.detalles;
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            this.detallesData = [];
            this.renderDetalles([]);
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
            totalElement.textContent = `$0.00 ${this.selectedCurrency}`;
            return;
        }
    
        detalles.forEach((producto) => {
            const precioUnitario = parseFloat(producto.Precio_Unitario) || 0;
            const cantidad = parseInt(producto.Cantidad) || 0;
            const precioTotal = parseFloat(producto.PrecioTotal) || 0;
            
            const precioUnitarioConvertido = this.currencyConverter.convertPrice(precioUnitario, this.selectedCurrency);
            const precioTotalConvertido = this.currencyConverter.convertPrice(precioTotal, this.selectedCurrency);
            
            total += precioTotal;
    
            const fila = document.createElement("tr");
            fila.setAttribute('data-id', producto.ID_DetallePedido);
            fila.innerHTML = `
                <td>${producto.ID_DetallePedido}</td>
                <td>${producto.ID_Producto}</td>
                <td>${cantidad}</td>
                <td>${precioUnitarioConvertido} ${this.selectedCurrency}</td>
                <td>${precioTotalConvertido} ${this.selectedCurrency}</td>
                <td>
                    <button class="btn-editar" data-id="${producto.ID_DetallePedido}">Editar</button>
                    <button class="btn-eliminar" data-id="${producto.ID_DetallePedido}">Eliminar</button>
                </td>
            `;
            this.tablaDetalles.appendChild(fila);
        });
    
        const totalConvertido = this.currencyConverter.convertPrice(total, this.selectedCurrency);
        const totalElement = this.detalleSection.querySelector("#total-pedido");
        totalElement.textContent = `${totalConvertido} ${this.selectedCurrency}`;
    
        this.addEventListeners();
    }

    addEventListeners() {
        this.tablaDetalles.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', () => {
                const idDetalle = btn.dataset.id;
                this.editarDetalle(idDetalle);
            });
        });

        this.tablaDetalles.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', () => {
                const idDetalle = btn.dataset.id;
                this.eliminarDetalle(idDetalle);
            });
        });
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


    async eliminarDetalle(idDetalle) {
        if (!confirm('¿Está seguro de que desea eliminar este detalle?')) {
            return;
        }
    
        try {
            const response = await fetch(`?controller=api&action=eliminarDetallePedido&id=${idDetalle}`, {
                method: 'DELETE'
            });
    
            const responseText = await response.text();
            let resultado;
            
            try {
                resultado = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Response text:', responseText);
                throw new Error('Respuesta del servidor no válida');
            }
    
            if (resultado.success) {
                alert('Detalle eliminado correctamente');
                await this.cargarDetalles();
            } else {
                throw new Error(resultado.message || 'Error al eliminar el detalle');
            }
        } catch (error) {
            console.error('Error completo:', error);
            alert('Error al eliminar el detalle: ' + error.message);
        }
    }

    configurarEventos() {
        const btnVolver = this.detalleSection.querySelector("#btn-volver");
        if (btnVolver) {
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