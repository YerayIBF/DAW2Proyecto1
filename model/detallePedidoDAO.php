<?php
include_once("config/database.php");
include_once("model/detallePedido.php");
class DetallePedidoDAO {
    public static function agregarDetalle($pedidoId, $productoId, $cantidad, $precioUnitario) {
        $con = database::connect();
        $precioTotal = $cantidad * $precioUnitario;
        $stmt = $con->prepare("
            INSERT INTO DetallePedido (ID_Pedido, ID_Producto, Cantidad, Precio_Unitario, PrecioTotal) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('iiidd', $pedidoId, $productoId, $cantidad, $precioUnitario, $precioTotal);
        $stmt->execute();
        $stmt->close();
    }
}
