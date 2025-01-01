class PedidoAdmin {
    constructor() {
        this.pedidosSection = document.querySelector("#pedidos");
        this.tablaPedidos = null;
        this.currencyConverter = new CurrencyConverter('fca_live_sAtIOrfXJE7hBnhZ3zXryTuVT9djWZ7lfpoVAznT');
        this.selectedCurrency = localStorage.getItem('selectedCurrency') || 'EUR';
        this.pedidosData = [];
        this.sortConfig = JSON.parse(localStorage.getItem('sortConfig')) || {
            column: null,
            direction: 'asc'
        };

        this.filtros = JSON.parse(localStorage.getItem('filtros')) || {
            usuario: '',
            fecha: '',
            precio: '',
            estado: '',
            oferta: '',
            direccion: '',
            id: '',
            dedicatoria: ''
        };
        this.editandoFilas = new Set();
    }

    async init() {
        if (!this.pedidosSection) {
            console.error("Contenedor #pedidos no encontrado.");
            return;
        }

        await this.currencyConverter.fetchRates();
        this.renderTabla();
        await this.cargarPedidos();
        this.initEventos();
        this.initCrearPedidoFormulario();
        this.restaurarFiltros();
    }

    restaurarFiltros() {
        Object.entries(this.filtros).forEach(([key, value]) => {
            const elemento = this.pedidosSection.querySelector(`#filtro-${key}`);
            if (elemento) elemento.value = value;
        });
    }

    renderTabla() {
     
        this.pedidosSection.innerHTML = `
            <h2>PEDIDOS</h2>
            <button id="btn-crear-pedido" class="mb-3">Crear Nuevo Pedido</button>
            <div id="form-crear-pedido" style="display: none;" class="mb-3">
                <h3>Crear Nuevo Pedido</h3>
                <form id="pedido-form" class="grid gap-3">
                    <div>
                        <label>Usuario ID:</label><br>
                        <input type="number" placeholder="ID DE USUARIO" id="usuario-id" required class="form-control">
                    </div>
                    <div>
                        <label>Dirección:</label><br>
                        <input type="text" id="direccion" placeholder="DIRECCIÓN" required class="form-control">
                    </div>
                    <div>
                        <label>Oferta ID:</label><br>
                        <input type="number" id="oferta-id" placeholder="OFERTA" class="form-control">
                    </div>
                    <div>
                        <label>Precio Total:</label><br>
                        <input type="number" step="0.01" id="precio-total" placeholder="PRECIO TOTAL" required class="form-control">
                    </div>
                    <div>
                        <label>Dedicatoria:</label><br>
                        <textarea id="dedicatoria" placeholder="INTRODUCE LA DEDICATORIA" class="form-control"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary">Guardar</button>
                        <button type="button" id="btn-cancelar-crear">Cancelar</button>
                    </div>
                </form>
            </div>
            
            <div class="filtros-container mb-3">
                <input type="text" id="filtro-id" placeholder="Filtrar por ID" class="me-2">
                <input type="text" id="filtro-usuario" placeholder="Filtrar por usuario" class="me-2">
                <input type="date" id="filtro-fecha" class="me-2">
                <input type="number" id="filtro-precio" placeholder="Precio mínimo" class="me-2">
                <select id="filtro-estado" class="me-2">
                    <option value="">Todos los estados</option>
                    <option value="En preparación">En preparación</option>
                    <option value="En camino">En camino</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
                <input type="text" id="filtro-oferta" placeholder="Filtrar por oferta" class="me-2">
                <input type="text" id="filtro-direccion" placeholder="Filtrar por dirección" class="me-2">
                <input type="text" id="filtro-dedicatoria" placeholder="Filtrar por dedicatoria" class="me-2">
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
            <table id="tabla-pedidos">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="id">ID ↕</th>
                        <th class="sortable" data-sort="fecha">Fecha ↕</th>
                        <th class="sortable" data-sort="precio">Total ↕</th>
                        <th class="sortable" data-sort="estado">Estado ↕</th>
                        <th class="sortable" data-sort="usuario">Usuario ↕</th>
                        <th class="sortable" data-sort="oferta">Oferta ↕</th>
                        <th class="sortable" data-sort="direccion">Dirección ↕</th>
                        <th class="sortable" data-sort="dedicatoria">Dedicatoria ↕</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        `;
        const currencySelector = `
       
    `;

        this.tablaPedidos = this.pedidosSection.querySelector("#tabla-pedidos tbody");
   

        const currencySelectorElement = this.pedidosSection.querySelector('#currency-selector');
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
        const headers = this.pedidosSection.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => this.ordenarPor(header.dataset.sort));
        });

        const filtroId = this.pedidosSection.querySelector('#filtro-id');
        const filtroUsuario = this.pedidosSection.querySelector('#filtro-usuario');
        const filtroFecha = this.pedidosSection.querySelector('#filtro-fecha');
        const filtroPrecio = this.pedidosSection.querySelector('#filtro-precio');
        const filtroEstado = this.pedidosSection.querySelector('#filtro-estado');
        const filtroOferta = this.pedidosSection.querySelector('#filtro-oferta');
        const filtroDireccion = this.pedidosSection.querySelector('#filtro-direccion');
        const filtroDedicatoria = this.pedidosSection.querySelector('#filtro-dedicatoria');
        const btnLimpiar = this.pedidosSection.querySelector('#btn-limpiar-filtros');

        const actualizarFiltros = (campo, valor) => {
            this.filtros[campo] = valor.toLowerCase();
            localStorage.setItem('filtros', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        };

        filtroId.addEventListener('input', () => actualizarFiltros('id', filtroId.value));
        filtroUsuario.addEventListener('input', () => actualizarFiltros('usuario', filtroUsuario.value));
        filtroFecha.addEventListener('change', () => actualizarFiltros('fecha', filtroFecha.value));
        filtroPrecio.addEventListener('input', () => actualizarFiltros('precio', filtroPrecio.value));
        filtroEstado.addEventListener('change', () => actualizarFiltros('estado', filtroEstado.value));
        filtroOferta.addEventListener('input', () => actualizarFiltros('oferta', filtroOferta.value));
        filtroDireccion.addEventListener('input', () => actualizarFiltros('direccion', filtroDireccion.value));
        filtroDedicatoria.addEventListener('input', () => actualizarFiltros('dedicatoria', filtroDedicatoria.value));

        btnLimpiar.addEventListener('click', () => {
            filtroId.value = '';
            filtroUsuario.value = '';
            filtroFecha.value = '';
            filtroPrecio.value = '';
            filtroEstado.value = '';
            filtroOferta.value = '';
            filtroDireccion.value = '';
            filtroDedicatoria.value = '';

            this.filtros = {
                usuario: '',
                fecha: '',
                precio: '',
                estado: '',
                oferta: '',
                direccion: '',
                id: '',
                dedicatoria: ''
            };
            localStorage.setItem('filtros', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        });
    }

    ordenarPor(columna) {
        if (this.sortConfig.column === columna) {
            this.sortConfig.direction = this.sortConfig.direction === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortConfig.column = columna;
            this.sortConfig.direction = 'asc';
        }

        localStorage.setItem('sortConfig', JSON.stringify(this.sortConfig));
        this.aplicarFiltros();
    }

    aplicarFiltros() {
        let pedidosFiltrados = this.pedidosData.filter(pedido => {
            const cumpleId = !this.filtros.id ||
                pedido.ID_Pedido.toString().includes(this.filtros.id);

            const cumpleUsuario = !this.filtros.usuario ||
                pedido.ID_Usuario.toString().includes(this.filtros.usuario);

            const cumpleFecha = !this.filtros.fecha ||
                pedido.Fecha_Pedido.includes(this.filtros.fecha);

            const cumplePrecio = !this.filtros.precio ||
                parseFloat(pedido.Precio_Total) >= parseFloat(this.filtros.precio);

            const cumpleEstado = !this.filtros.estado ||
                pedido.Estado.toLowerCase() === this.filtros.estado.toLowerCase();

            const cumpleOferta = !this.filtros.oferta ||
                (pedido.ID_Oferta && pedido.ID_Oferta.toString().includes(this.filtros.oferta));

            const cumpleDireccion = !this.filtros.direccion ||
                (pedido.Direccion && pedido.Direccion.toLowerCase().includes(this.filtros.direccion));

            const cumpleDedicatoria = !this.filtros.dedicatoria ||
                (pedido.Dedicatoria && pedido.Dedicatoria.toLowerCase().includes(this.filtros.dedicatoria));

            return cumpleId && cumpleUsuario && cumpleFecha && cumplePrecio &&
                cumpleEstado && cumpleOferta && cumpleDireccion && cumpleDedicatoria;
        });

        if (this.sortConfig.column) {
            pedidosFiltrados.sort((a, b) => {
                let valorA, valorB;

                switch (this.sortConfig.column) {
                    case 'id':
                        valorA = parseInt(a.ID_Pedido);
                        valorB = parseInt(b.ID_Pedido);
                        break;
                    case 'fecha':
                        valorA = new Date(a.Fecha_Pedido);
                        valorB = new Date(b.Fecha_Pedido);
                        break;
                    case 'precio':
                        valorA = parseFloat(a.Precio_Total);
                        valorB = parseFloat(b.Precio_Total);
                        break;
                    case 'estado':
                        valorA = a.Estado.toLowerCase();
                        valorB = b.Estado.toLowerCase();
                        break;
                    case 'usuario':
                        valorA = a.ID_Usuario.toString();
                        valorB = b.ID_Usuario.toString();
                        break;
                    case 'oferta':
                        valorA = a.ID_Oferta || 0;
                        valorB = b.ID_Oferta || 0;
                        break;
                    case 'direccion':
                        valorA = (a.Direccion || '').toLowerCase();
                        valorB = (b.Direccion || '').toLowerCase();
                        break;
                    case 'dedicatoria':
                        valorA = (a.Dedicatoria || '').toLowerCase();
                        valorB = (b.Dedicatoria || '').toLowerCase();
                        break;
                    default:
                        valorA = a[this.sortConfig.column];
                        valorB = b[this.sortConfig.column];
                }

                if (this.sortConfig.direction === 'asc') {
                    return valorA > valorB ? 1 : -1;
                } else {
                    return valorA < valorB ? 1 : -1;
                }
            });
        }

        this.renderPedidos(pedidosFiltrados);
    }

 

    editarPedido(id) {
        if (this.editandoFilas.has(id)) return;

        const fila = this.tablaPedidos.querySelector(`tr[data-id="${id}"]`);
        const pedido = this.pedidosData.find(p => p.ID_Pedido == id);

        if (!fila || !pedido) return;

        this.editandoFilas.add(id);
        fila.setAttribute('data-original', fila.innerHTML);

        const celdas = fila.getElementsByTagName('td');

        
        // Fecha - convertir a input type date
        celdas[1].innerHTML = `<input type="date" class="form-control" value="${pedido.Fecha_Pedido.split(' ')[0]}" />`;

        // Precio - convertir a input type number
        celdas[2].innerHTML = `<input type="number" class="form-control" value="${pedido.Precio_Total}" step="0.01" min="0" />`;

        // Estado - convertir a select
        celdas[3].innerHTML = this.renderEstadoSelect(pedido.Estado, true);

        // Usuario - mantener igual

        // Oferta - convertir a input number
        celdas[5].innerHTML = `<input type="number" class="form-control" value="${pedido.ID_Oferta || ''}" min="0" />`;

        // Dirección - convertir a input text
        celdas[6].innerHTML = `<input type="text" class="form-control" value="${pedido.Direccion || ''}" />`;

        // Dedicatoria - convertir a textarea
        celdas[7].innerHTML = `<textarea class="form-control">${pedido.Dedicatoria || ''}</textarea>`;

        // Botones - cambiar a Guardar/Cancelar
        celdas[8].innerHTML = `
            <button class="btn-guardar" data-id="${id}">Guardar</button>
            <button class="btn-cancelar" data-id="${id}">Cancelar</button>
        `;

       
        const btnGuardar = celdas[8].querySelector('.btn-guardar');
        const btnCancelar = celdas[8].querySelector('.btn-cancelar');

        btnGuardar.addEventListener('click', () => this.guardarCambios(id));
        btnCancelar.addEventListener('click', () => this.cancelarEdicion(id));
    }

    initCrearPedidoFormulario() {
        const btnCrearPedido = this.pedidosSection.querySelector('#btn-crear-pedido');
        const formCrearPedido = this.pedidosSection.querySelector('#form-crear-pedido');
        const formulario = this.pedidosSection.querySelector('#pedido-form');
        const btnCancelarCrear = this.pedidosSection.querySelector('#btn-cancelar-crear');

        btnCrearPedido.addEventListener('click', () => {
            formCrearPedido.style.display = 'block';
            btnCrearPedido.style.display = 'none';
        });

        btnCancelarCrear.addEventListener('click', () => {
            formCrearPedido.style.display = 'none';
            btnCrearPedido.style.display = 'block';
            formulario.reset();
        });

        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nuevoPedido = {
                ID_Usuario: document.querySelector('#usuario-id').value,
                Direccion: document.querySelector('#direccion').value,
                Dedicatoria: document.querySelector('#dedicatoria').value,
                ID_Oferta: document.querySelector('#oferta-id').value || null,
                Precio_Total: document.querySelector('#precio-total').value
            };

            try {
                const response = await fetch('?controller=api&action=crearPedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(nuevoPedido)
                });

                if (!response.ok) throw new Error('Error al crear el pedido');

                const resultado = await response.json();

                if (resultado.success) {
                    alert('Pedido creado correctamente');
                    formCrearPedido.style.display = 'none';
                    btnCrearPedido.style.display = 'block';
                    formulario.reset();
                    await this.cargarPedidos();
                } else {
                    throw new Error(resultado.message || 'Error al crear el pedido');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear el pedido: ' + error.message);
            }
        });
    }

    async cargarPedidos() {
        try {
            this.tablaPedidos.innerHTML = `<tr><td colspan="9">Cargando pedidos...</td></tr>`;

            const response = await fetch("?controller=api&action=verPedidos");
            if (!response.ok) throw new Error("Error al obtener los pedidos");
            this.pedidosData = await response.json();

            // Guardamos el último estado de los pedidos en localStorage
            localStorage.setItem('ultimosPedidos', JSON.stringify(this.pedidosData));
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            // Si hay error, intentamos cargar los últimos pedidos guardados
            const ultimosPedidos = localStorage.getItem('ultimosPedidos');
            if (ultimosPedidos) {
                this.pedidosData = JSON.parse(ultimosPedidos);
                this.aplicarFiltros();
                console.log('Cargados datos desde caché local');
            } else {
                this.pedidosSection.innerHTML = `<p>Error al cargar los pedidos.</p>`;
            }
        }
    }

    renderPedidos(pedidos) {
        this.tablaPedidos.innerHTML = "";

        if (pedidos.length === 0) {
            this.tablaPedidos.innerHTML = `<tr><td colspan="9">No hay pedidos disponibles</td></tr>`;
            return;
        }

        pedidos.forEach((pedido) => {
            const precioConvertido = this.currencyConverter.convertPrice(pedido.Precio_Total, this.selectedCurrency);
            const fila = document.createElement("tr");
            fila.setAttribute('data-id', pedido.ID_Pedido);
            fila.innerHTML = `
                <td>${pedido.ID_Pedido}</td>
                <td>${pedido.Fecha_Pedido}</td>
                <td>${precioConvertido} ${this.selectedCurrency}</td>
                <td>${pedido.Estado}</td>
                <td>${pedido.ID_Usuario}</td>
                <td>${pedido.ID_Oferta || 'Sin oferta'}</td>
                <td>${pedido.Direccion || 'Sin dirección'}</td>
                <td>${pedido.Dedicatoria || 'Sin dedicatoria'}</td>
                <td>
                    <button class="btn-ver" data-id="${pedido.ID_Pedido}">Ver</button>
                    <button class="btn-editar" data-id="${pedido.ID_Pedido}">Editar</button>
                    <button class="btn-eliminar" data-id="${pedido.ID_Pedido}">Eliminar</button>
                </td>
            `;
            this.tablaPedidos.appendChild(fila);
        });

        this.addEventListeners();
    }

    async guardarCambios(id) {
        const fila = this.tablaPedidos.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const fecha = fila.querySelector('input[type="date"]').value;
        const precio = fila.querySelector('input[type="number"]').value;
        const estado = fila.querySelector('select.estado-select').value;
        const oferta = fila.querySelectorAll('input[type="number"]')[1].value;
        const direccion = fila.querySelector('input[type="text"]').value;
        const dedicatoria = fila.querySelector('textarea').value;

        const datosActualizados = {
            ID_Pedido: id,
            Fecha_Pedido: fecha,
            Precio_Total: precio,
            Estado: estado,
            ID_Oferta: oferta || null,
            Direccion: direccion,
            Dedicatoria: dedicatoria
        };

        try {
            const response = await fetch('?controller=api&action=actualizarPedido', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datosActualizados)
            });

            if (!response.ok) throw new Error('Error al actualizar el pedido');

            const resultado = await response.json();

            if (resultado.success) {
                alert('Pedido actualizado correctamente');
                this.editandoFilas.delete(id);

                // Guardamos el último pedido editado en localStorage
                localStorage.setItem('ultimoPedidoEditado', JSON.stringify(datosActualizados));

                await this.cargarPedidos();
            } else {
                throw new Error(resultado.message || 'Error al actualizar el pedido');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al actualizar el pedido: ' + error.message);
        }
    }

    renderEstadoSelect(estadoActual, editable = false) {
        const estados = ['En preparación', 'En camino', 'Entregado', 'Cancelado'];
        if (editable) {
            return `
                <select class="estado-select form-control">
                    ${estados.map(estado => `
                        <option value="${estado}" ${estado === estadoActual ? 'selected' : ''}>
                            ${estado}
                        </option>
                    `).join('')}
                </select>
            `;
        }
        return estadoActual;
    }

    cancelarEdicion(id) {
        const fila = this.tablaPedidos.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const contenidoOriginal = fila.getAttribute('data-original');
        if (contenidoOriginal) {
            fila.innerHTML = contenidoOriginal;
            this.editandoFilas.delete(id);
            this.addEventListeners();
        }
    }

    addEventListeners() {
        this.pedidosSection.querySelectorAll(".btn-ver").forEach((btn) =>
            btn.addEventListener("click", (e) => this.verPedido(e.target.dataset.id))
        );

        this.pedidosSection.querySelectorAll(".btn-editar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.editarPedido(e.target.dataset.id))
        );

        this.pedidosSection.querySelectorAll(".btn-eliminar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.eliminarPedido(e.target.dataset.id))
        );
    }

    async verPedido(id) {
        try {
            // Guardamos el último pedido visto en localStorage
            localStorage.setItem('ultimoPedidoVisto', id);

            const response = await fetch(`?controller=api&action=detallesPedidos`);
            if (!response.ok) throw new Error("Error al cargar la vista");
            const html = await response.text();

            const dynamicContent = document.getElementById("dynamic-content");
            dynamicContent.innerHTML = html;

            if (!document.querySelector('script[src="js/DetallePedidoAdmin.js"]')) {
                const script = document.createElement("script");
                script.src = "js/DetallePedidoAdmin.js";
                script.onload = () => {
                    const detallePedidoAdmin = new DetallePedidoAdmin();
                    detallePedidoAdmin.init(id);
                };
                document.body.appendChild(script);
            } else {
                const detallePedidoAdmin = new DetallePedidoAdmin();
                detallePedidoAdmin.init(id);
            }
        } catch (error) {
            console.error("Error al cargar la vista de detalles:", error);
            document.getElementById("dynamic-content").innerHTML =
                `<p>Error al cargar los detalles del pedido</p>`;
        }
    }

    async eliminarPedido(id) {
        if (confirm(`¿Estás seguro de que quieres eliminar el pedido con ID: ${id}?`)) {
            try {
                const response = await fetch(`?controller=api&action=eliminarPedido&id=${id}`, {
                    method: "DELETE",
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const resultado = await response.json();

                if (resultado.success) {
                    alert(`Pedido con ID ${id} eliminado.`);
                    // Guardamos registro de la eliminación
                    const eliminados = JSON.parse(localStorage.getItem('pedidosEliminados') || '[]');
                    eliminados.push({ id, fecha: new Date().toISOString() });
                    localStorage.setItem('pedidosEliminados', JSON.stringify(eliminados));

                    await this.cargarPedidos();
                } else {
                    alert(`No se pudo eliminar el pedido. ${resultado.message}`);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar eliminar el pedido.");
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    const pedidoAdmin = new PedidoAdmin();
    await pedidoAdmin.init();
});