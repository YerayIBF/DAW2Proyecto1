class UsuarioAdmin {
    constructor() {
        this.usuariosSection = document.querySelector("#usuarios");
        this.tablaUsuarios = null;
        this.usuariosData = [];
        this.sortConfig = JSON.parse(localStorage.getItem('sortConfigUsuarios')) || {
            column: null,
            direction: 'asc'
        };
        this.filtros = JSON.parse(localStorage.getItem('filtrosUsuarios')) || {
            id: '',
            nombre: '',
            correo: '',
            rol: ''
        };
        this.editandoFilas = new Set();
    }

    async init() {
        if (!this.usuariosSection) return;
        this.renderTabla();
        await this.cargarUsuarios();
        this.initEventos();
        this.initCrearUsuarioFormulario();
        this.restaurarFiltros();
    }

    renderTabla() {
        this.usuariosSection.innerHTML = `
            <h2>USUARIOS</h2>
            <button id="btn-crear-usuario" class="mb-3">Crear Nuevo Usuario</button>
            <div id="form-crear-usuario" style="display: none;" class="mb-3">
                <h3>Crear Nuevo Usuario</h3>
                <form id="usuario-form" class="grid gap-3">
                    <div>
                        <label>Nombre:</label><br>
                        <input type="text" id="nombre" required class="form-control">
                    </div>
                    <div>
                        <label>Correo:</label><br>
                        <input type="email" id="correo" required class="form-control">
                    </div>
                    <div>
                        <label>Contraseña:</label><br>
                        <input type="password" id="contraseña" required class="form-control">
                    </div>
                    <div>
                        <label>Rol:</label><br>
                        <select id="rol" class="form-control">
                            <option value="usuario">Usuario</option>
                            <option value="admin">Admin</option>
                        </select>
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
                <input type="text" id="filtro-correo" placeholder="Filtrar por correo" class="me-2">
                <select id="filtro-rol" class="me-2">
                    <option value="">Todos los roles</option>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Admin</option>
                </select>
                <button id="btn-limpiar-filtros">Limpiar filtros</button>
            </div>
            <table id="tabla-usuarios">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="id">ID ↕</th>
                        <th class="sortable" data-sort="nombre">Nombre ↕</th>
                        <th class="sortable" data-sort="correo">Correo ↕</th>
                        <th class="sortable" data-sort="rol">Rol ↕</th>
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        `;
        this.tablaUsuarios = this.usuariosSection.querySelector("#tabla-usuarios tbody");
    }

    async cargarUsuarios() {
        try {
            const response = await fetch("?controller=api&action=verUsuarios");
            if (!response.ok) throw new Error("Error al obtener usuarios");
            this.usuariosData = await response.json();
            localStorage.setItem('ultimosUsuarios', JSON.stringify(this.usuariosData));
            this.aplicarFiltros();
        } catch (error) {
            console.error("Error:", error);
            const ultimosUsuarios = localStorage.getItem('ultimosUsuarios');
            if (ultimosUsuarios) {
                this.usuariosData = JSON.parse(ultimosUsuarios);
                this.aplicarFiltros();
            }
        }
    }

    renderUsuarios(usuarios) {
        this.tablaUsuarios.innerHTML = usuarios.length ? "" : 
            `<tr><td colspan="6">No hay usuarios disponibles</td></tr>`;

        usuarios.forEach(usuario => {
            const fila = document.createElement("tr");
            fila.setAttribute('data-id', usuario.ID_Usuario);
            fila.innerHTML = `
                <td>${usuario.ID_Usuario}</td>
                <td>${usuario.Nombre}</td>
                <td>${usuario.Correo}</td>
                <td>${usuario.Rol}</td>
                <td>********</td>
                <td>
                    <button class="btn-editar" data-id="${usuario.ID_Usuario}">Editar</button>
                    <button class="btn-eliminar" data-id="${usuario.ID_Usuario}">Eliminar</button>
                </td>
            `;
            this.tablaUsuarios.appendChild(fila);
        });
        this.addEventListeners();
    }

    addEventListeners() {
        this.usuariosSection.querySelectorAll(".btn-editar").forEach(btn =>
            btn.addEventListener("click", e => this.editarUsuario(e.target.dataset.id))
        );

        this.usuariosSection.querySelectorAll(".btn-eliminar").forEach(btn =>
            btn.addEventListener("click", e => this.eliminarUsuario(e.target.dataset.id))
        );
    }

    async eliminarUsuario(id) {
        if (!confirm(`¿Eliminar usuario ${id}?`)) return;
        
        try {
            const response = await fetch(`?controller=api&action=eliminarUsuario&id=${id}`, {
                method: "DELETE"
            });
            
            const resultado = await response.json();
            if (resultado.success) {
                alert("Usuario eliminado correctamente");
                await this.cargarUsuarios();
            } else {
                throw new Error(resultado.message);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Error al eliminar usuario");
        }
    }

    editarUsuario(id) {
        if (this.editandoFilas.has(id)) return;

        const fila = this.tablaUsuarios.querySelector(`tr[data-id="${id}"]`);
        const usuario = this.usuariosData.find(u => u.ID_Usuario == id);
        if (!fila || !usuario) return;

        this.editandoFilas.add(id);
        fila.setAttribute('data-original', fila.innerHTML);

        const celdas = fila.getElementsByTagName('td');
        celdas[1].innerHTML = `<input type="text" class="form-control" value="${usuario.Nombre}" />`;
        celdas[2].innerHTML = `<input type="email" class="form-control" value="${usuario.Correo}" />`;
        celdas[3].innerHTML = `
            <select class="form-control">
                <option value="usuario" ${usuario.Rol === 'usuario' ? 'selected' : ''}>Usuario</option>
                <option value="admin" ${usuario.Rol === 'admin' ? 'selected' : ''}>Admin</option>
            </select>
        `;
        celdas[4].innerHTML = `<input type="password" class="form-control" placeholder="Nueva contraseña" />`;
        celdas[5].innerHTML = `
            <button class="btn-guardar" data-id="${id}">Guardar</button>
            <button class="btn-cancelar" data-id="${id}">Cancelar</button>
        `;

        const btnGuardar = celdas[5].querySelector('.btn-guardar');
        const btnCancelar = celdas[5].querySelector('.btn-cancelar');

        btnGuardar.addEventListener('click', () => this.guardarCambios(id));
        btnCancelar.addEventListener('click', () => this.cancelarEdicion(id));
    }

    async guardarCambios(id) {
        const fila = this.tablaUsuarios.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const nombre = fila.querySelector('input[type="text"]').value;
        const correo = fila.querySelector('input[type="email"]').value;
        const rol = fila.querySelector('select').value;
        const nuevaContraseña = fila.querySelector('input[type="password"]').value;

        const datosActualizacion = {
            ID_Usuario: id,
            Nombre: nombre,
            Correo: correo,
            Rol: rol
        };

        // Solo incluir la contraseña si se ha introducido una nueva
        if (nuevaContraseña) {
            datosActualizacion.Contraseña = nuevaContraseña;
        }

        try {
            const response = await fetch('?controller=api&action=actualizarUsuario', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datosActualizacion)
            });

            const resultado = await response.json();
            if (resultado.success) {
                alert('Usuario actualizado correctamente');
                this.editandoFilas.delete(id);
                await this.cargarUsuarios();
            } else {
                throw new Error(resultado.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al actualizar usuario');
        }
    }

    cancelarEdicion(id) {
        const fila = this.tablaUsuarios.querySelector(`tr[data-id="${id}"]`);
        if (!fila) return;

        const contenidoOriginal = fila.getAttribute('data-original');
        if (contenidoOriginal) {
            fila.innerHTML = contenidoOriginal;
            this.editandoFilas.delete(id);
            this.addEventListeners();
        }
    }

    initEventos() {
        const headers = this.usuariosSection.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => this.ordenarPor(header.dataset.sort));
        });

        const filtros = {
            id: this.usuariosSection.querySelector('#filtro-id'),
            nombre: this.usuariosSection.querySelector('#filtro-nombre'),
            correo: this.usuariosSection.querySelector('#filtro-correo'),
            rol: this.usuariosSection.querySelector('#filtro-rol')
        };

        Object.entries(filtros).forEach(([key, elemento]) => {
            if (elemento) {
                elemento.addEventListener('input', () => {
                    this.filtros[key] = elemento.value.toLowerCase();
                    localStorage.setItem('filtrosUsuarios', JSON.stringify(this.filtros));
                    this.aplicarFiltros();
                });
            }
        });

        const btnLimpiar = this.usuariosSection.querySelector('#btn-limpiar-filtros');
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', () => {
                Object.values(filtros).forEach(elemento => {
                    if (elemento) elemento.value = '';
                });
                this.filtros = { id: '', nombre: '', correo: '', rol: '' };
                localStorage.setItem('filtrosUsuarios', JSON.stringify(this.filtros));
                this.aplicarFiltros();
            });
        }
    }

    ordenarPor(columna) {
        if (this.sortConfig.column === columna) {
            this.sortConfig.direction = this.sortConfig.direction === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortConfig.column = columna;
            this.sortConfig.direction = 'asc';
        }

        localStorage.setItem('sortConfigUsuarios', JSON.stringify(this.sortConfig));
        this.aplicarFiltros();
    }

    
    aplicarFiltros() {
        let usuariosFiltrados = this.usuariosData.filter(usuario => {
            const cumpleId = !this.filtros.id || 
                usuario.ID_Usuario.toString().includes(this.filtros.id);
            const cumpleNombre = !this.filtros.nombre || 
                usuario.Nombre.toLowerCase().includes(this.filtros.nombre);
            const cumpleCorreo = !this.filtros.correo || 
                usuario.Correo.toLowerCase().includes(this.filtros.correo);
            const cumpleRol = !this.filtros.rol || 
                usuario.Rol.toLowerCase() === this.filtros.rol;

            return cumpleId && cumpleNombre && cumpleCorreo && cumpleRol;
        });

        if (this.sortConfig.column) {
            usuariosFiltrados.sort((a, b) => {
                let valorA = a[this.sortConfig.column === 'id' ? 'ID_Usuario' : 
                    this.sortConfig.column.charAt(0).toUpperCase() + this.sortConfig.column.slice(1)];
                let valorB = b[this.sortConfig.column === 'id' ? 'ID_Usuario' : 
                    this.sortConfig.column.charAt(0).toUpperCase() + this.sortConfig.column.slice(1)];

                if (this.sortConfig.column === 'id') {
                    valorA = parseInt(valorA);
                    valorB = parseInt(valorB);
                }

                if (this.sortConfig.direction === 'asc') {
                    return valorA > valorB ? 1 : -1;
                } else {
                    return valorA < valorB ? 1 : -1;
                }
            });
        }

        this.renderUsuarios(usuariosFiltrados);
    }

    restaurarFiltros() {
        Object.entries(this.filtros).forEach(([key, value]) => {
            const elemento = this.usuariosSection.querySelector(`#filtro-${key}`);
            if (elemento) elemento.value = value;
        });
    }

    initCrearUsuarioFormulario() {
        const btnCrear = this.usuariosSection.querySelector('#btn-crear-usuario');
        const formCrear = this.usuariosSection.querySelector('#form-crear-usuario');
        const formulario = this.usuariosSection.querySelector('#usuario-form');
        const btnCancelar = this.usuariosSection.querySelector('#btn-cancelar-crear');
    
        // Restaurar datos guardados
        const savedFormData = localStorage.getItem('usuarioFormData');
        if (savedFormData) {
            const formData = JSON.parse(savedFormData);
            Object.keys(formData).forEach(key => {
                const input = formulario.querySelector(`#${key}`);
                if (input) input.value = formData[key];
            });
        }
    
        // Guardar cambios en tiempo real
        formulario.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', () => {
                const formData = {
                    'nombre': formulario.querySelector('#nombre').value,
                    'correo': formulario.querySelector('#correo').value,
                    'contraseña': formulario.querySelector('#contraseña').value,
                    'rol': formulario.querySelector('#rol').value
                };
                localStorage.setItem('usuarioFormData', JSON.stringify(formData));
            });
        });
    
        btnCrear.addEventListener('click', () => {
            formCrear.style.display = 'block';
            btnCrear.style.display = 'none';
        });
    
        btnCancelar.addEventListener('click', () => {
            if (confirm('¿Desea borrar los datos guardados del formulario?')) {
                localStorage.removeItem('usuarioFormData');
                formulario.reset();
            }
            formCrear.style.display = 'none';
            btnCrear.style.display = 'block';
        });
    
        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();
    
            const nuevoUsuario = {
                Nombre: document.querySelector('#nombre').value,
                Correo: document.querySelector('#correo').value,
                Contraseña: document.querySelector('#contraseña').value,
                Rol: document.querySelector('#rol').value
            };
    
            try {
                const response = await fetch('?controller=api&action=crearUsuario', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(nuevoUsuario)
                });
    
                const resultado = await response.json();
                if (resultado.success) {
                    alert('Usuario creado correctamente');
                    localStorage.removeItem('usuarioFormData');
                    formCrear.style.display = 'none';
                    btnCrear.style.display = 'block';
                    formulario.reset();
                    await this.cargarUsuarios();
                } else {
                    throw new Error(resultado.message || 'Error al crear usuario');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear usuario: ' + error.message);
            }
        });
    }
}
    

document.addEventListener("DOMContentLoaded", async () => {
    const usuarioAdmin = new UsuarioAdmin();
    await usuarioAdmin.init();
});