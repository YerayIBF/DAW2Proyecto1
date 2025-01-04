class LogsAdmin {
    constructor() {
        this.logsSection = document.querySelector("#logs");
        this.tablaLogs = null;
        this.logsData = [];
        this.sortConfig = JSON.parse(localStorage.getItem('logsSortConfig')) || {
            column: null,
            direction: 'asc'
        };

        this.filtros = JSON.parse(localStorage.getItem('logsFiltros')) || {
            Accion: '',
            Fecha_Log: '',
            ID_Usuario: ''
        };
    }

    async init() {
        if (!this.logsSection) {
            console.error("Contenedor #logs no encontrado.");
            return;
        }

        this.renderTabla();
        await this.cargarLogs();
        this.initEventos();
        this.restaurarFiltros();
    }

    restaurarFiltros() {
        Object.entries(this.filtros).forEach(([key, value]) => {
            const elemento = this.logsSection.querySelector(`#filtro-${key}`);
            if (elemento) elemento.value = value;
        });
    }

    renderTabla() {
        this.logsSection.innerHTML = `
            <h2>LOGS DEL SISTEMA</h2>
            <div class="filtros-container mb-3">
                <input type="text" id="filtro-Accion" placeholder="Filtrar por acción" class="me-2">
                <input type="date" id="filtro-Fecha_Log" class="me-2">
                <input type="text" id="filtro-ID_Usuario" placeholder="Filtrar por usuario" class="me-2">
                <button id="btn-limpiar-filtros">Limpiar filtros</button>
            </div>
            <table id="tabla-logs">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="ID_Log">ID ↕</th>
                        <th class="sortable" data-sort="Fecha_Log">Fecha ↕</th>
                        <th class="sortable" data-sort="Accion">Acción ↕</th>
                        <th class="sortable" data-sort="ID_Usuario">Usuario ↕</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        `;

        this.tablaLogs = this.logsSection.querySelector("#tabla-logs tbody");
    }

    initEventos() {
        const headers = this.logsSection.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => this.ordenarPor(header.dataset.sort));
        });

        const filtroAccion = this.logsSection.querySelector('#filtro-Accion');   
        const filtroFecha = this.logsSection.querySelector('#filtro-Fecha_Log');    
        const filtroUsuario = this.logsSection.querySelector('#filtro-ID_Usuario');
        const btnLimpiar = this.logsSection.querySelector('#btn-limpiar-filtros');

        const actualizarFiltros = (campo, valor) => {
            this.filtros[campo] = valor.toLowerCase();
            localStorage.setItem('logsFiltros', JSON.stringify(this.filtros));
            this.aplicarFiltros();
        };

        filtroAccion.addEventListener('input', () => actualizarFiltros('Accion', filtroAccion.value));
        filtroFecha.addEventListener('change', () => actualizarFiltros('Fecha_Log', filtroFecha.value));
        filtroUsuario.addEventListener('input', () => actualizarFiltros('ID_Usuario', filtroUsuario.value));

        btnLimpiar.addEventListener('click', () => {
            filtroAccion.value = '';
            filtroFecha.value = '';
            filtroUsuario.value = '';

            this.filtros = {
                Accion: '',
                Fecha_Log: '',
                ID_Usuario: ''
            };
            localStorage.setItem('logsFiltros', JSON.stringify(this.filtros));
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

        localStorage.setItem('logsSortConfig', JSON.stringify(this.sortConfig));
        this.aplicarFiltros();
    }

    aplicarFiltros() {
        let logsFiltrados = this.logsData.filter(log => {
            const cumpleAccion = !this.filtros.Accion ||
                log.Accion.toLowerCase().includes(this.filtros.Accion);
    
            const cumpleFecha = !this.filtros.Fecha_Log ||
                log.Fecha_Log.includes(this.filtros.Fecha_Log);
    
            const cumpleUsuario = !this.filtros.ID_Usuario ||
                String(log.ID_Usuario).includes(this.filtros.ID_Usuario); 
    
            return cumpleAccion && cumpleFecha && cumpleUsuario;
        });
    
        if (this.sortConfig.column) {
            logsFiltrados.sort((a, b) => {
                let valorA, valorB;
    
                switch (this.sortConfig.column) {
                    case 'id':
                        valorA = parseInt(a.ID_Log);
                        valorB = parseInt(b.ID_Log);
                        break;
                    case 'fecha':
                        valorA = new Date(a.Fecha_Log);
                        valorB = new Date(b.Fecha_Log);
                        break;
                    case 'accion':
                        valorA = a.Accion.toLowerCase();
                        valorB = b.Accion.toLowerCase();
                        break;
                    case 'usuario':
                        valorA = String(a.ID_Usuario); 
                        valorB = String(b.ID_Usuario);
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
    
        this.renderLogs(logsFiltrados);
    }

    async cargarLogs() {
        try {
            this.tablaLogs.innerHTML = `<tr><td colspan="4">Cargando logs...</td></tr>`;

            const response = await fetch("?controller=api&action=verLogs");
            if (!response.ok) throw new Error("Error al obtener los logs");
            this.logsData = await response.json();

            console.log('Logs data:', this.logsData);

            localStorage.setItem('ultimosLogs', JSON.stringify(this.logsData));
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            const ultimosLogs = localStorage.getItem('ultimosLogs');
            if (ultimosLogs) {
                this.logsData = JSON.parse(ultimosLogs);
                this.aplicarFiltros();
                console.log('Cargados logs desde caché local');
            } else {
                this.logsSection.innerHTML = `<p>Error al cargar los logs.</p>`;
            }
        }
    }

    renderLogs(logs) {
        this.tablaLogs.innerHTML = "";

        if (logs.length === 0) {
            this.tablaLogs.innerHTML = `<tr><td colspan="4">No hay logs disponibles</td></tr>`;
            return;
        }

        logs.forEach((log) => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${log.ID_Log}</td>
                <td>${log.Fecha_Log}</td>
                <td>${log.Accion}</td>
                <td>${log.ID_Usuario}</td>
            `;
            this.tablaLogs.appendChild(fila);
        });
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    const logsAdmin = new LogsAdmin();
    await logsAdmin.init();
});