class ofertaAdmin {
    constructor() {
        this.ofertasSection = document.querySelector("#ofertaAdmin");
        this.tablaOfertas = null;
        this.ofertasData = [];
        this.sortConfig = {
            column: null,
            direction: 'asc'
        };
    }

    async init() {
        if (!this.ofertasSection) {
            console.error("Contenedor #ofertaAdmin no encontrado.");
            return;
        }

        this.renderTabla();
        await this.cargarOfertas();
        this.initEventos();
        this.initCrearOfertaFormulario();
    }

    renderTabla() {
        this.ofertasSection.innerHTML = `
            <h2>OFERTAS</h2>
            <button id="btn-crear-oferta" class="mb-3">Crear Nueva Oferta</button>
            <div id="form-crear-oferta" style="display: none;" class="mb-3">
                <h3>Crear Nueva Oferta</h3>
                <form id="oferta-form" class="grid gap-3">
                    <div>
                        <label>Código:</label>
                        <input type="text" id="codigo" required class="form-control">
                    </div>
                    <div>
                        <label>Descuento (%):</label>
                        <input type="number" id="descuento" required class="form-control" min="1" max="100">
                    </div>
                    <div>
                        <label>Usos Disponibles:</label>
                        <input type="number" id="usos-disponibles" required class="form-control" min="1">
                    </div>
                    <div>
                        <button type="submit" class="btn-primary">Guardar</button>
                        <button type="button" id="btn-cancelar-crear">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="filtros-container mb-3">
                <input type="text" id="filtro-id" placeholder="Filtrar por ID" class="me-2">
                <input type="text" id="filtro-codigo" placeholder="Filtrar por Código" class="me-2">
                <input type="number" id="filtro-descuento" placeholder="Filtrar por Descuento (%)" class="me-2">
                <input type="number" id="filtro-usos" placeholder="Filtrar por Usos Disponibles" class="me-2">
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
                <tbody></tbody>
            </table>
        `;
        this.tablaOfertas = this.ofertasSection.querySelector("#tabla-ofertas tbody");
        this.ofertasSection.querySelector('.filtros-container');
    }

    initEventos() {
        const headers = this.ofertasSection.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => this.ordenarPor(header.dataset.sort));
        });

        // Filtros
        const filtroId = this.ofertasSection.querySelector('#filtro-id');
        const filtroCodigo = this.ofertasSection.querySelector('#filtro-codigo');
        const filtroDescuento = this.ofertasSection.querySelector('#filtro-descuento');
        const filtroUsos = this.ofertasSection.querySelector('#filtro-usos');


        const actualizarFiltros = (campo, valor) => {
            this.filtros[campo] = valor.toLowerCase();
            localStorage.setItem('filtros', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        };

        filtroId.addEventListener('input', () => this.actualizarFiltros());
        filtroCodigo.addEventListener('input', () => this.actualizarFiltros());
        filtroDescuento.addEventListener('input', () => this.actualizarFiltros());
        filtroUsos.addEventListener('input', () => this.actualizarFiltros());

        this.ofertasSection.querySelector('#btn-limpiar-filtros').addEventListener('click', () => {
            filtroId.value = '';
            filtroCodigo.value = '';
            filtroDescuento.value = '';
            filtroUsos.value = '';
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

        this.aplicarFiltros();
    }

    async cargarOfertas() {
        try {
            this.tablaOfertas.innerHTML = `<tr><td colspan="5">Cargando ofertas...</td></tr>`;

            const response = await fetch("?controller=api&action=verOfertas");
            if (!response.ok) throw new Error("Error al obtener las ofertas");
            this.ofertasData = await response.json();

            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            this.tablaOfertas.innerHTML = `<tr><td colspan="5">Error al cargar las ofertas.</td></tr>`;
        }
    }

    aplicarFiltros() {
       
        const filtroId = this.ofertasSection.querySelector('#filtro-id').value.trim();
        const filtroCodigo = this.ofertasSection.querySelector('#filtro-codigo').value.trim().toLowerCase();
        const filtroDescuento = this.ofertasSection.querySelector('#filtro-descuento').value.trim();
        const filtroUsos = this.ofertasSection.querySelector('#filtro-usos').value.trim();
    
        const ofertasFiltradas = this.ofertasData.filter(oferta => {
            const cumpleId = !filtroId || oferta.ID_Oferta.toString().includes(filtroId);
            const cumpleCodigo = !filtroCodigo || oferta.Codigo.toLowerCase().includes(filtroCodigo);
            const cumpleDescuento = !filtroDescuento || oferta.Descuento == parseInt(filtroDescuento);
            const cumpleUsos = !filtroUsos || oferta.Usos_Disponibles == parseInt(filtroUsos);
    
            return cumpleId && cumpleCodigo && cumpleDescuento && cumpleUsos;
        });
    
        this.renderOfertas(ofertasFiltradas);
    }

    

    renderOfertas(ofertas) {
        this.tablaOfertas.innerHTML = "";

        if (ofertas.length === 0) {
            this.tablaOfertas.innerHTML = `<tr><td colspan="5">No hay ofertas disponibles</td></tr>`;
            return;
        }

        ofertas.forEach(oferta => {
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

    addEventListeners() {
        this.ofertasSection.querySelectorAll(".btn-editar").forEach(btn =>
            btn.addEventListener("click", (e) => this.editarOferta(e.target.dataset.id))
        );

        this.ofertasSection.querySelectorAll(".btn-eliminar").forEach(btn =>
            btn.addEventListener("click", (e) => this.eliminarOferta(e.target.dataset.id))
        );
    }

    initCrearOfertaFormulario() {
        const btnCrearOferta = this.ofertasSection.querySelector('#btn-crear-oferta');
        const formCrearOferta = this.ofertasSection.querySelector('#form-crear-oferta');
        const formulario = this.ofertasSection.querySelector('#oferta-form');
        const btnCancelarCrear = this.ofertasSection.querySelector('#btn-cancelar-crear');

        btnCrearOferta.addEventListener('click', () => {
            formCrearOferta.style.display = 'block';
            btnCrearOferta.style.display = 'none';
        });

        btnCancelarCrear.addEventListener('click', () => {
            formCrearOferta.style.display = 'none';
            btnCrearOferta.style.display = 'block';
            formulario.reset();
        });

        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nuevaOferta = {
                Codigo: document.querySelector('#codigo').value,
                Descuento: document.querySelector('#descuento').value,
                Usos_Disponibles: document.querySelector('#usos-disponibles').value
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

                alert('Oferta creada correctamente');
                formCrearOferta.style.display = 'none';
                btnCrearOferta.style.display = 'block';
                formulario.reset();
                await this.cargarOfertas();
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear la oferta: ' + error.message);
            }
        });
    }

    async editarOferta(id) {
        const fila = this.tablaOfertas.querySelector(`tr[data-id="${id}"]`);
        const oferta = this.ofertasData.find(o => o.ID_Oferta == id);

        if (!fila || !oferta) {
            alert("No se encontró la oferta seleccionada.");
            return;
        }

        // Cambiar la fila a modo edición
        fila.innerHTML = `
            <td>${oferta.ID_Oferta}</td>
            <td><input type="text" value="${oferta.Codigo}" class="form-control" id="editar-codigo-${id}"></td>
            <td><input type="number" value="${oferta.Descuento}" min="1" max="100" class="form-control" id="editar-descuento-${id}"></td>
            <td><input type="number" value="${oferta.Usos_Disponibles}" min="1" class="form-control" id="editar-usos-${id}"></td>
            <td>
                <button class="btn-guardar" data-id="${id}">Guardar</button>
                <button class="btn-cancelar" data-id="${id}">Cancelar</button>
            </td>
        `;

        // Eventos para guardar o cancelar
        fila.querySelector(".btn-guardar").addEventListener("click", async () => {
            const codigo = document.querySelector(`#editar-codigo-${id}`).value;
            const descuento = document.querySelector(`#editar-descuento-${id}`).value;
            const usosDisponibles = document.querySelector(`#editar-usos-${id}`).value;

            try {
                const response = await fetch("?controller=api&action=actualizarOferta", {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        ID_Oferta: id,
                        Codigo: codigo,
                        Descuento: parseInt(descuento),
                        Usos_Disponibles: parseInt(usosDisponibles),
                    }),
                });

                if (!response.ok) throw new Error("Error al actualizar la oferta.");

                alert("Oferta actualizada correctamente.");
                await this.cargarOfertas();
            } catch (error) {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar actualizar la oferta.");
            }
        });

        fila.querySelector(".btn-cancelar").addEventListener("click", () => {
            this.renderOfertas(this.ofertasData); // Restaurar la tabla sin cambios
        });
    }

    async eliminarOferta(id) {
        if (confirm(`¿Estás seguro de que quieres eliminar la oferta con ID: ${id}?`)) {
            try {
                const response = await fetch(`?controller=api&action=eliminarOferta&id=${id}`, {
                    method: "DELETE",
                });

                if (!response.ok) throw new Error('Error al eliminar la oferta');

                alert(`Oferta con ID ${id} eliminada.`);
                await this.cargarOfertas();
            } catch (error) {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar eliminar la oferta.");
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    const ofertaAdmin = new ofertaAdmin();
    await ofertaAdmin.init();
});
