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

    public static function obtenerDetallesPorPedido($pedidoId) {
        $con = database::connect();
    
        $stmt = $con->prepare("SELECT * FROM DetallePedido WHERE ID_Pedido = ?");
        $stmt->bind_param('i', $pedidoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $detalles = [];
        while ($detalle = $resultado->fetch_assoc()) {
            $detalles[] = $detalle;
        }
        $stmt->close();
    
        return $detalles;
    }
    public static function eliminarDetallesPorPedido($pedidoId) {
        $con = database::connect();
    
        $stmt = $con->prepare("DELETE FROM DetallePedido WHERE ID_Pedido = ?");
        $stmt->bind_param('i', $pedidoId);
        $resultado = $stmt->execute();
        $stmt->close();
        $con->close();
    
        return $resultado;
    }
    
    
    public static function editarDetalle($detalleId, $cantidad, $precioUnitario) {
        $con = database::connect();
        $precioTotal = $cantidad * $precioUnitario;
    
        $stmt = $con->prepare("
            UPDATE DetallePedido 
            SET Cantidad = ?, Precio_Unitario = ?, PrecioTotal = ? 
            WHERE ID_DetallePedido = ?
        ");
        $stmt->bind_param('iddi', $cantidad, $precioUnitario, $precioTotal, $detalleId);
        $stmt->execute();
        $stmt->close();
    }

    public static function agregarProducto($pedidoId, $productoId, $cantidad, $precioUnitario) {
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

    public static function eliminarProducto($detalleId) {
        $con = database::connect();
    
        $stmt = $con->prepare("
            DELETE FROM DetallePedido 
            WHERE ID_DetallePedido = ?
        ");
        $stmt->bind_param('i', $detalleId);
        $stmt->execute();
        $stmt->close();
    }


    
    
}
