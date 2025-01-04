class OfertaAdmin {
    constructor() {
        this.ofertasSection = document.querySelector("#ofertas");
        this.tablaOfertas = null;
        this.ofertasData = [];
        this.sortConfig = JSON.parse(localStorage.getItem('sortConfigOfertas')) || {
            column: null,
            direction: 'asc'
        };

        this.filtros = JSON.parse(localStorage.getItem('filtrosOfertas')) || {
            codigo: '',
            descuento: '',
            usos: '',
            id: ''
        };
        this.editandoFilas = new Set();
    }

    async init() {
        if (!this.ofertasSection) {
            console.error("Contenedor #ofertas no encontrado.");
            return;
        }

        this.renderTabla();
        await this.cargarOfertas();
        this.initEventos();
        this.initCrearOfertaFormulario();
        this.restaurarFiltros();
    }

    restaurarFiltros() {
        Object.entries(this.filtros).forEach(([key, value]) => {
            const elemento = this.ofertasSection.querySelector(`#filtro-${key}`);
            if (elemento) elemento.value = value;
        });
    }

    renderTabla() {
        this.ofertasSection.innerHTML = `
            <h2>OFERTAS</h2>
            <button id="btn-crear-oferta" class="mb-3">Crear Nueva Oferta</button>
            <div id="form-crear-oferta" style="display: none;" class="mb-3">
                <h3>Crear Nueva Oferta</h3>
                <form id="oferta-form" class="grid gap-3">
                    <div>
                        <label>Código:</label><br>
                        <input type="text" placeholder="CÓDIGO DE OFERTA" id="codigo" required class="form-control">
                    </div>
                    <div>
                        <label>Descuento (%):</label><br>
                        <input type="number" step="0.01" id="descuento" placeholder="DESCUENTO" required class="form-control">
                    </div>
                    <div>
                        <label>Usos Disponibles:</label><br>
                        <input type="number" id="usos" placeholder="USOS DISPONIBLES" required class="form-control">
                    </div>
                    <div>
                        <button type="submit" class="btn-primary">Guardar</button>
                        <button type="button" id="btn-cancelar-crear">Cancelar</button>
                    </div>
                </form>
            </div>
            
            <div class="filtros-container mb-3">
                <input type="text" id="filtro-id" placeholder="Filtrar por ID" class="me-2">
                <input type="text" id="filtro-codigo" placeholder="Filtrar por código" class="me-2">
                <input type="number" id="filtro-descuento" placeholder="Descuento mínimo" class="me-2">
                <input type="number" id="filtro-usos" placeholder="Usos mínimos" class="me-2">
                <button id="btn-limpiar-filtros">Limpiar filtros</button>
            </div>
            <table id="tabla-ofertas">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="id">ID ↕</th>
                        <th class="sortable" data-sort="codigo">Código ↕</th>
                        <th class="sortable" data-sort="descuento">Descuento ↕</th>
                        <th class="sortable" data-sort="usos">Usos Disponibles ↕</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        `;

        this.tablaOfertas = this.ofertasSection.querySelector("#tabla-ofertas tbody");
    }

    initEventos() {
        const headers = this.ofertasSection.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => this.ordenarPor(header.dataset.sort));
        });

        const filtroId = this.ofertasSection.querySelector('#filtro-id');
        const filtroCodigo = this.ofertasSection.querySelector('#filtro-codigo');
        const filtroDescuento = this.ofertasSection.querySelector('#filtro-descuento');
        const filtroUsos = this.ofertasSection.querySelector('#filtro-usos');
        const btnLimpiar = this.ofertasSection.querySelector('#btn-limpiar-filtros');

        const actualizarFiltros = (campo, valor) => {
            this.filtros[campo] = valor.toLowerCase();
            localStorage.setItem('filtrosOfertas', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        };

        filtroId.addEventListener('input', () => actualizarFiltros('id', filtroId.value));
        filtroCodigo.addEventListener('input', () => actualizarFiltros('codigo', filtroCodigo.value));
        filtroDescuento.addEventListener('input', () => actualizarFiltros('descuento', filtroDescuento.value));
        filtroUsos.addEventListener('input', () => actualizarFiltros('usos', filtroUsos.value));

        btnLimpiar.addEventListener('click', () => {
            filtroId.value = '';
            filtroCodigo.value = '';
            filtroDescuento.value = '';
            filtroUsos.value = '';

            this.filtros = {
                codigo: '',
                descuento: '',
                usos: '',
                id: ''
            };
            localStorage.setItem('filtrosOfertas', JSON.stringify(this.filtros));
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

        localStorage.setItem('sortConfigOfertas', JSON.stringify(this.sortConfig));
        this.aplicarFiltros();
    }

    aplicarFiltros() {
        let ofertasFiltradas = this.ofertasData.filter(oferta => {
            const cumpleId = !this.filtros.id ||
                oferta.ID_Oferta.toString().includes(this.filtros.id);

            const cumpleCodigo = !this.filtros.codigo ||
                oferta.Codigo.toLowerCase().includes(this.filtros.codigo);

            const cumpleDescuento = !this.filtros.descuento ||
                parseFloat(oferta.Descuento) >= parseFloat(this.filtros.descuento);

            const cumpleUsos = !this.filtros.usos ||
                parseInt(oferta.Usos_Disponibles) >= parseInt(this.filtros.usos);

            return cumpleId && cumpleCodigo && cumpleDescuento && cumpleUsos;
        });

        if (this.sortConfig.column) {
            ofertasFiltradas.sort((a, b) => {
                let valorA, valorB;

                switch (this.sortConfig.column) {
                    case 'id':
                        valorA = parseInt(a.ID_Oferta);
                        valorB = parseInt(b.ID_Oferta);
                        break;
                    case 'codigo':
                        valorA = a.Codigo.toLowerCase();
                        valorB = b.Codigo.toLowerCase();
                        break;
                    case 'descuento':
                        valorA = parseFloat(a.Descuento);
                        valorB = parseFloat(b.Descuento);
                        break;
                    case 'usos':
                        valorA = parseInt(a.Usos_Disponibles);
                        valorB = parseInt(b.Usos_Disponibles);
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

        this.renderOfertas(ofertasFiltradas);
    }

    async cargarOfertas() {
        try {
            this.tablaOfertas.innerHTML = `<tr><td colspan="5">Cargando ofertas...</td></tr>`;

            const response = await fetch("?controller=api&action=verOfertas");
            if (!response.ok) throw new Error("Error al obtener las ofertas");
            this.ofertasData = await response.json();

            localStorage.setItem('ultimasOfertas', JSON.stringify(this.ofertasData));
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            const ultimasOfertas = localStorage.getItem('ultimasOfertas');
            if (ultimasOfertas) {
                this.ofertasData = JSON.parse(ultimasOfertas);
                this.aplicarFiltros();
                console.log('Cargados datos desde caché local');
            } else {
                this.ofertasSection.innerHTML = `<p>Error al cargar las ofertas.</p>`;
            }
        }
    }

    renderOfertas(ofertas) {
        this.tablaOfertas.innerHTML = "";

        if (ofertas.length === 0) {
            this.tablaOfertas.innerHTML = `<tr><td colspan="5">No hay ofertas disponibles</td></tr>`;
            return;
        }

        ofertas.forEach((oferta) => {
            const fila = document.createElement("tr");
            fila.setAttribute('data-id', oferta.ID_Oferta);
            fila.innerHTML = `
                <td>${oferta.ID_Oferta}</td>
                <td>${oferta.Codigo}</td>
                <td>${oferta.Descuento}%</td>
                <td>${oferta.Usos_Disponibles}</td>
                <td>
                    <button class="btn-editar" data-id="${oferta.ID_Oferta}">Editar</button>
                    <button class="btn-eliminar" data-id="${oferta.ID_Oferta}">Eliminar</button>
                </td>
            `;
            this.tablaOfertas.appendChild(fila);
        });

        this.addEventListeners();
    }

    editarOferta(id) {
        if (this.editandoFilas.has(id)) return;

        const fila = this.tablaOfertas.querySelector(`tr[data-id="${id}"]`);
        const oferta = this.ofertasData.find(o => o.ID_Oferta == id);

        if (!fila || !oferta) return;

        this.editandoFilas.add(id);
        fila.setAttribute('data-original', fila.innerHTML);

        const celdas = fila.getElementsByTagName('td');

        celdas[1].innerHTML = `<input type="text" class="form-control" value="${oferta.Codigo}" />`;
        celdas[2].innerHTML = `<input type="number" step="0.01" class="form-control" value="${oferta.Descuento}" />`;
        celdas[3].innerHTML = `<input type="number" class="form-control" value="${oferta.Usos_Disponibles}" />`;
        celdas[4].innerHTML = `
            <button class="btn-guardar" data-id="${id}">Guardar</button>
            <button class="btn-cancelar" data-id="${id}">Cancelar</button>
        `;

        const btnGuardar = celdas[4].querySelector('.btn-guardar');
        const btnCancelar = celdas[4].querySelector('.btn-cancelar');

        btnGuardar.addEventListener('click', () => this.guardarCambios(id));
        btnCancelar.addEventListener('click', () => this.cancelarEdicion(id));
    }

    initCrearOfertaFormulario() {
        const btnCrearOferta = this.ofertasSection.querySelector('#btn-crear-oferta');
        const formCrearOferta = this.ofertasSection.querySelector('#form-crear-oferta');
        const formulario = this.ofertasSection.querySelector('#oferta-form');
        const btnCancelarCrear = this.ofertasSection.querySelector('#btn-cancelar-crear');

        const savedFormData = localStorage.getItem('ofertaFormData');
        if (savedFormData) {
            const formData = JSON.parse(savedFormData);
            Object.keys(formData).forEach(key => {
                const input = formulario.querySelector(`#${key}`);
                if (input) input.value = formData[key];
            });
        }

        formulario.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                const formData = {
                    'codigo': formulario.querySelector('#codigo').value,
                    'descuento': formulario.querySelector('#descuento').value,
                    'usos': formulario.querySelector('#usos').value
                };
                localStorage.setItem('ofertaFormData', JSON.stringify(formData));
            });
        });

        btnCrearOferta.addEventListener('click', () => {
            formCrearOferta.style.display = 'block';
            btnCrearOferta.style.display = 'none';
        });

        btnCancelarCrear.addEventListener('click', () => {
            if (confirm('¿Desea borrar los datos guardados del formulario?')) {
                localStorage.removeItem('ofertaFormData');
                formulario.reset();
            }
            formCrearOferta.style.display = 'none';
            btnCrearOferta.style.display = 'block';
        });

        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nuevaOferta = {
                Codigo: document.querySelector('#codigo').value,
                Descuento: document.querySelector('#descuento').value,
                Usos_Disponibles: document.querySelector('#usos').value
            };

            try {
                const response = await fetch('?controller=api&action=crearOferta', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(nuevaOferta)
                });

                if (!response.ok) throw new Error('Error al crear la oferta');

                const resultado = await response.json();

                if (resultado.success) {
                    alert('Oferta creada correctamente');
                    localStorage.removeItem('ofertaFormData');
                    formCrearOferta.style.display = 'none';
                    btnCrearOferta.style.display = 'block';
                    formulario.reset();
                    await this.cargarOfertas();
                } else {
                    throw new Error(resultado.message || 'Error al crear la oferta');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear la oferta: ' + error.message);
            }
        });
    }

    async guardarCambios(id) {
        const fila = this.tablaOfertas.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const codigo = fila.querySelector('td:nth-child(2) input').value;
        const descuento = parseFloat(fila.querySelector('td:nth-child(3) input').value);
        const usos = parseInt(fila.querySelector('td:nth-child(4) input').value);

        const datosActualizados = {
            ID_Oferta: parseInt(id),
            Codigo: codigo,
            Descuento: descuento,
            Usos_Disponibles: usos
        };

        try {
            const response = await fetch('?controller=api&action=actualizarOferta', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datosActualizados)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error al actualizar la oferta');
            }

            const resultado = await response.json();

            if (resultado.success) {
                alert('Oferta actualizada correctamente');
                this.editandoFilas.delete(id);
                localStorage.setItem('ultimaOfertaEditada', JSON.stringify(datosActualizados));
                await this.cargarOfertas();
            } else {
                throw new Error(resultado.message || 'Error al actualizar la oferta');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al actualizar la oferta: ' + error.message);
        }
    }

    cancelarEdicion(id) {
        const fila = this.tablaOfertas.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const contenidoOriginal = fila.getAttribute('data-original');
        if (contenidoOriginal) {
            fila.innerHTML = contenidoOriginal;
            this.editandoFilas.delete(id);
            this.addEventListeners();
        }
    }

    addEventListeners() {

        this.ofertasSection.querySelectorAll(".btn-editar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.editarOferta(e.target.dataset.id))
        );

        this.ofertasSection.querySelectorAll(".btn-eliminar").forEach((btn) =>
            btn.addEventListener("click", (e) => this.eliminarOferta(e.target.dataset.id))
        );
    }

   

    async eliminarOferta(id) {
        if (confirm(`¿Estás seguro de que quieres eliminar la oferta con ID: ${id}?`)) {
            try {
                const response = await fetch(`?controller=api&action=eliminarOferta&id=${id}`, {
                    method: "DELETE",
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const resultado = await response.json();

                if (resultado.success) {
                    alert(`Oferta con ID ${id} eliminada.`);
                    const eliminadas = JSON.parse(localStorage.getItem('ofertasEliminadas') || '[]');
                    eliminadas.push({ id, fecha: new Date().toISOString() });
                    localStorage.setItem('ofertasEliminadas', JSON.stringify(eliminadas));

                    await this.cargarOfertas();
                } else {
                    alert(`No se pudo eliminar la oferta. ${resultado.message}`);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar eliminar la oferta.");
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    const ofertaAdmin = new OfertaAdmin();
    await ofertaAdmin.init();
});