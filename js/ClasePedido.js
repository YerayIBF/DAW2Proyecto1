class Pedido {
    constructor(id, fecha, total, estado) {
        this.id = id;
        this.fecha = fecha;
        this.total = total;
        this.estado = estado;
    }

    mostrarDetalles() {
        console.log(`Pedido ID: ${this.id}, Fecha: ${this.fecha}, Total: ${this.total}, Estado: ${this.estado}`);
    }
    
    cambiarEstado(nuevoEstado) {
        this.estado = nuevoEstado;
    }

}

