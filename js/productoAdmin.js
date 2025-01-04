class ProductoAdmin {
    constructor() {
        this.productosSection = document.querySelector("#productos");
        this.tablaProductos = null;
        this.currencyConverter = new CurrencyConverter('fca_live_sAtIOrfXJE7hBnhZ3zXryTuVT9djWZ7lfpoVAznT');
        this.selectedCurrency = localStorage.getItem('selectedCurrency') || 'EUR';
        this.productosData = [];
        this.sortConfig = JSON.parse(localStorage.getItem('sortConfig')) || {
            column: null,
            direction: 'asc'
        };

        this.filtros = JSON.parse(localStorage.getItem('filtros')) || {
            nombre: '',
            descripcion: '',
            precio: '',
            id: ''
        };
        this.editandoFilas = new Set();
    }

    async init() {
        if (!this.productosSection) {
            console.error("Contenedor #productos no encontrado.");
            return;
        }

        await this.currencyConverter.fetchRates();
        this.renderTabla();
        await this.cargarProductos();
        this.initEventos();
        this.initCrearProductoFormulario();
        this.restaurarFiltros();
    }

    restaurarFiltros() {
        Object.entries(this.filtros).forEach(([key, value]) => {
            const elemento = this.productosSection.querySelector(`#filtro-${key}`);
            if (elemento) elemento.value = value;
        });
    }

    renderTabla() {
        this.productosSection.innerHTML = `
            <h2>PRODUCTOS</h2>
            <button id="btn-crear-producto" class="mb-3">Crear Nuevo Producto</button>
            <div id="form-crear-producto" style="display: none;" class="mb-3">
                <h3>Crear Nuevo Producto</h3>
                <form id="producto-form" class="grid gap-3">
                    <div>
                        <label>Nombre:</label><br>
                        <input type="text" id="nombre" placeholder="NOMBRE" required class="form-control">
                    </div>
                    <div>
                        <label>Descripción:</label><br>
                        <textarea id="descripcion" placeholder="DESCRIPCIÓN" class="form-control"></textarea>
                    </div>
                    <div>
                        <label>Precio:</label><br>
                        <input type="number" step="0.01" id="precio" placeholder="PRECIO" required class="form-control">
                    </div>
                    <div>
                        <label>Imagen URL:</label><br>
                        <input type="text" id="imagen" placeholder="URL DE LA IMAGEN" class="form-control">
                    </div>
                    <div>
                        <button type="submit" class="btn-primary">Guardar</button>
                        <button type="button" id="btn-cancelar-crear">Cancelar</button>
                    </div>
                </form>
            </div>
            
            <div class="filtros-container mb-3">
                <input type="text" id="filtro-id" placeholder="Filtrar por ID" class="me-2">
                <input type="text" id="filtro-nombre" placeholder="Filtrar por nombre" class="me-2">
                <input type="text" id="filtro-descripcion" placeholder="Filtrar por descripción" class="me-2">
                <input type="number" id="filtro-precio" placeholder="Precio mínimo" class="me-2">
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
            <table id="tabla-productos">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="id">ID ↕</th>
                        <th class="sortable" data-sort="nombre">Nombre ↕</th>
                        <th class="sortable" data-sort="descripcion">Descripción ↕</th>
                        <th class="sortable" data-sort="precio">Precio ↕</th>
                        <th class="sortable" data-sort="imagen">Imagen ↕</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        `;

        this.tablaProductos = this.productosSection.querySelector("#tabla-productos tbody");

        const currencySelectorElement = this.productosSection.querySelector('#currency-selector');
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
        const headers = this.productosSection.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => this.ordenarPor(header.dataset.sort));
        });

        const filtroId = this.productosSection.querySelector('#filtro-id');
        const filtroNombre = this.productosSection.querySelector('#filtro-nombre');
        const filtroDescripcion = this.productosSection.querySelector('#filtro-descripcion');
        const filtroPrecio = this.productosSection.querySelector('#filtro-precio');
        const btnLimpiar = this.productosSection.querySelector('#btn-limpiar-filtros');

        const actualizarFiltros = (campo, valor) => {
            this.filtros[campo] = valor.toLowerCase();
            localStorage.setItem('filtros', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        };

        filtroId.addEventListener('input', () => actualizarFiltros('id', filtroId.value));
        filtroNombre.addEventListener('input', () => actualizarFiltros('nombre', filtroNombre.value));
        filtroDescripcion.addEventListener('input', () => actualizarFiltros('descripcion', filtroDescripcion.value));
        filtroPrecio.addEventListener('input', () => actualizarFiltros('precio', filtroPrecio.value));

        btnLimpiar.addEventListener('click', () => {
            filtroId.value = '';
            filtroNombre.value = '';
            filtroDescripcion.value = '';
            filtroPrecio.value = '';

            this.filtros = {
                nombre: '',
                descripcion: '',
                precio: '',
                id: ''
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
        let productosFiltrados = this.productosData.filter(producto => {
            const cumpleId = !this.filtros.id ||
                producto.ID_Producto.toString().includes(this.filtros.id);

            const cumpleNombre = !this.filtros.nombre ||
                producto.Nombre.toLowerCase().includes(this.filtros.nombre);

            const cumpleDescripcion = !this.filtros.descripcion ||
                (producto.Descripcion && producto.Descripcion.toLowerCase().includes(this.filtros.descripcion));

            const cumplePrecio = !this.filtros.precio ||
                parseFloat(producto.Precio) >= parseFloat(this.filtros.precio);

            return cumpleId && cumpleNombre && cumpleDescripcion && cumplePrecio;
        });

        if (this.sortConfig.column) {
            productosFiltrados.sort((a, b) => {
                let valorA, valorB;

                switch (this.sortConfig.column) {
                    case 'id':
                        valorA = parseInt(a.ID_Producto);
                        valorB = parseInt(b.ID_Producto);
                        break;
                    case 'nombre':
                        valorA = a.Nombre.toLowerCase();
                        valorB = b.Nombre.toLowerCase();
                        break;
                    case 'descripcion':
                        valorA = (a.Descripcion || '').toLowerCase();
                        valorB = (b.Descripcion || '').toLowerCase();
                        break;
                    case 'precio':
                        valorA = parseFloat(a.Precio);
                        valorB = parseFloat(b.Precio);
                        break;
                    case 'imagen':
                        valorA = (a.Imagen || '').toLowerCase();
                        valorB = (b.Imagen || '').toLowerCase();
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

        this.renderProductos(productosFiltrados);
    }

    editarProducto(id) {
        if (this.editandoFilas.has(id)) return;

        const fila = this.tablaProductos.querySelector(`tr[data-id="${id}"]`);
        const producto = this.productosData.find(p => p.ID_Producto == id);

        if (!fila || !producto) return;

        this.editandoFilas.add(id);
        fila.setAttribute('data-original', fila.innerHTML);

        const celdas = fila.getElementsByTagName('td');

        celdas[1].innerHTML = `<input type="text" class="form-control" value="${producto.Nombre}" />`;
        celdas[2].innerHTML = `<textarea class="form-control">${producto.Descripcion || ''}</textarea>`;
        celdas[3].innerHTML = `<input type="number" class="form-control" value="${producto.Precio}" step="0.01" min="0" />`;
        celdas[4].innerHTML = `<input type="text" class="form-control" value="${producto.Imagen || ''}" />`;

        celdas[5].innerHTML = `
            <button class="btn-guardar" data-id="${id}">Guardar</button>
            <button class="btn-cancelar" data-id="${id}">Cancelar</button>
        `;

        const btnGuardar = celdas[5].querySelector('.btn-guardar');
        const btnCancelar = celdas[5].querySelector('.btn-cancelar');

        btnGuardar.addEventListener('click', () => this.guardarCambios(id));
        btnCancelar.addEventListener('click', () => this.cancelarEdicion(id));
    }

    initCrearProductoFormulario() {
        const btnCrearProducto = this.productosSection.querySelector('#btn-crear-producto');
        const formCrearProducto = this.productosSection.querySelector('#form-crear-producto');
        const formulario = this.productosSection.querySelector('#producto-form');
        const btnCancelarCrear = this.productosSection.querySelector('#btn-cancelar-crear');

        const savedFormData = localStorage.getItem('productoFormData');
        if (savedFormData) {
            const formData = JSON.parse(savedFormData);
            Object.keys(formData).forEach(key => {
                const input = formulario.querySelector(`#${key}`);
                if (input) input.value = formData[key];
            });
        }

        formulario.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', () => {
                const formData = {
                    'nombre': formulario.querySelector('#nombre').value,
                    'descripcion': formulario.querySelector('#descripcion').value,
                    'precio': formulario.querySelector('#precio').value,
                    'imagen': formulario.querySelector('#imagen').value
                };
                localStorage.setItem('productoFormData', JSON.stringify(formData));
            });
        });

        btnCrearProducto.addEventListener('click', () => {
            formCrearProducto.style.display = 'block';
            btnCrearProducto.style.display = 'none';
        });

        btnCancelarCrear.addEventListener('click', () => {
            if (confirm('¿Desea borrar los datos guardados del formulario?')) {
                localStorage.removeItem('productoFormData');
                formulario.reset();
            }
            formCrearProducto.style.display = 'none';
            btnCrearProducto.style.display = 'block';
        });

        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nuevoProducto = {
                Nombre: document.querySelector('#nombre').value,
                Descripcion: document.querySelector('#descripcion').value,
                Precio: document.querySelector('#precio').value,
                Imagen: document.querySelector('#imagen').value
            };

            try {
                const response = await fetch('?controller=api&action=crearProducto', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'
                },
                body: JSON.stringify(nuevoProducto)
            });

            if (!response.ok) throw new Error('Error al crear el producto');

            const resultado = await response.json();

            if (resultado.success) {
                alert('Producto creado correctamente');
                localStorage.removeItem('productoFormData');
                formCrearProducto.style.display = 'none';
                btnCrearProducto.style.display = 'block';
                formulario.reset();
                await this.cargarProductos();
            } else {
                throw new Error(resultado.message || 'Error al crear el producto');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al crear el producto: ' + error.message);
        }
    });
}

    async cargarProductos() {
        try {
            this.tablaProductos.innerHTML = `<tr><td colspan="6">Cargando productos...</td></tr>`;

            const response = await fetch("?controller=api&action=verProductos");
            if (!response.ok) throw new Error("Error al obtener los productos");
            this.productosData = await response.json();

            localStorage.setItem('ultimosProductos', JSON.stringify(this.productosData));
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            const ultimosProductos = localStorage.getItem('ultimosProductos');
            if (ultimosProductos) {
                this.productosData = JSON.parse(ultimosProductos);
                this.aplicarFiltros();
                console.log('Cargados datos desde caché local');
            } else {
                this.productosSection.innerHTML = `<p>Error al cargar los productos.</p>`;
            }
        }
    }

    renderProductos(productos) {
        this.tablaProductos.innerHTML = "";

        if (productos.length === 0) {
            this.tablaProductos.innerHTML = `<tr><td colspan="6">No hay productos disponibles</td></tr>`;
            return;
        }

        productos.forEach((producto) => {
            const precioConvertido = this.currencyConverter.convertPrice(producto.Precio, this.selectedCurrency);
            const fila = document.createElement("tr");
            fila.setAttribute('data-id', producto.ID_Producto);
            fila.innerHTML = `
                <td>${producto.ID_Producto}</td>
                <td>${producto.Nombre}</td>
                <td>${producto.Descripcion || 'Sin descripción'}</td>
                <td>${precioConvertido} ${this.selectedCurrency}</td>
                <td>${producto.Imagen || 'Sin imagen'}</td>
                <td>
                    <button class="btn-editar" data-id="${producto.ID_Producto}">Editar</button>
                    <button class="btn-eliminar" data-id="${producto.ID_Producto}">Eliminar</button>
                </td>
            `;
            this.tablaProductos.appendChild(fila);
        });

        this.addEventListeners();
    }

    async guardarCambios(id) {
        const fila = this.tablaProductos.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const nombre = fila.querySelector('input[type="text"]').value;
        const descripcion = fila.querySelector('textarea').value;
        const precio = parseFloat(fila.querySelector('input[type="number"]').value);
        const imagen = fila.querySelector('td:nth-child(5) input[type="text"]').value;

        const datosActualizados = {
            ID_Producto: parseInt(id),
            Nombre: nombre,
            Descripcion: descripcion,
            Precio: precio,
            Imagen: imagen
        };

        try {
            const response = await fetch('?controller=api&action=actualizarProducto', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datosActualizados)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error al actualizar el producto');
            }

            const resultado = await response.json();

            if (resultado.success) {
                alert('Producto actualizado correctamente');
                this.editandoFilas.delete(id);
                localStorage.setItem('ultimoProductoEditado', JSON.stringify(datosActualizados));
                await this.cargarProductos();
            } else {
                throw new Error(resultado.message || 'Error al actualizar el producto');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al actualizar el producto: ' + error.message);
        }
    }

    cancelarEdicion(id) {
        const fila = this.tablaProductos.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const contenidoOriginal = fila.getAttribute('data-original');
        if (contenidoOriginal) {
            fila.innerHTML = contenidoOriginal;
            this.editandoFilas.delete(id);
            this.addEventListeners();
        }
    }

    addEventListeners() {
        this.productosSection.querySelectorAll(".btn-editar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.editarProducto(e.target.dataset.id))
        );

        this.productosSection.querySelectorAll(".btn-eliminar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.eliminarProducto(e.target.dataset.id))
        );
    }

    async eliminarProducto(id) {
        if (confirm(`¿Estás seguro de que quieres eliminar el producto con ID: ${id}?`)) {
            try {
                const response = await fetch(`?controller=api&action=eliminarProducto&id=${id}`, {
                    method: "DELETE",
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const resultado = await response.json();

                if (resultado.success) {
                    alert(`Producto con ID ${id} eliminado.`);
                    const eliminados = JSON.parse(localStorage.getItem('productosEliminados') || '[]');
                    eliminados.push({ id, fecha: new Date().toISOString() });
                    localStorage.setItem('productosEliminados', JSON.stringify(eliminados));
                    await this.cargarProductos();
                } else {
                    alert(`No se pudo eliminar el producto. ${resultado.message}`);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar eliminar el producto.");
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    const productoAdmin = new ProductoAdmin();
    await productoAdmin.init();
});
                        