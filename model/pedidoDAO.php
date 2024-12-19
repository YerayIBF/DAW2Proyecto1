<?php
include_once("config/database.php");
include_once("model/pedido.php");
class PedidoDAO {
    public static function crearPedido($usuarioId, $direccion, $dedicatoria, $ofertaId, $total)
    {
        $con = database::connect();
        $fecha = date('Y-m-d H:i:s');
        $estado = 'En preparación';
        
        $stmt = $con->prepare("
            INSERT INTO Pedidos (ID_Usuario, Direccion, Dedicatoria, ID_Oferta, Precio_Total, Fecha_Pedido, Estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param('issidss', $usuarioId, $direccion, $dedicatoria, $ofertaId, $total, $fecha, $estado);
        $stmt->execute();
        $pedidoId = $stmt->insert_id;
        $stmt->close();
    
        return $pedidoId;
    }
    


    public static function obtenerPedidosPorUsuario($usuarioId) {
        $db = database::connect(); 
        $stmt = $db->prepare("SELECT * FROM Pedidos WHERE ID_Usuario = ?");
        $stmt->bind_param('i', $usuarioId); 
        $stmt->execute();
        $resultado = $stmt->get_result();

        $pedidos=[];
        while ($pedido = $resultado->fetch_object('pedido')) {
            $pedidos[] = $pedido;
        }

        $stmt->close();
        return $pedidos;
    }

    public static function obtenerPedidoPorId($pedidoId) {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Pedidos WHERE ID_Pedido = ?");
        $stmt->bind_param('i', $pedidoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $pedido = $resultado->fetch_object('pedido');
        $stmt->close();
    
        return $pedido;
    }


    public static function ObtenerTodosLosPedidos() {
        $con = database::connect();
    
        $stmt = $con->prepare("SELECT * FROM Pedidos");
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $pedidos = [];
        while ($pedido = $resultado->fetch_assoc(   )) {
            $pedidos[] = $pedido;
        }
        $stmt->close();
    
        return $pedidos;
    }
    

    public static function eliminarPedido($idPedido) {
        $con = database::connect();

        $stmt = $con->prepare("DELETE FROM pedidos WHERE ID_Pedido = ?");
        $stmt->bind_param("i", $idPedido);
    
        $resultado = $stmt->execute();
        $stmt->close();
        $con->close();
    
        return $resultado;
    }


    public static function editarPedido($pedidoId, $direccion, $dedicatoria, $ofertaId, $total, $estado) {
        $con = database::connect();
    
        $stmt = $con->prepare("
            UPDATE Pedidos 
            SET Direccion = ?, Dedicatoria = ?, ID_Oferta = ?, Precio_Total = ?, Estado = ?
            WHERE ID_Pedido = ?
        ");
        $stmt->bind_param('ssidis', $direccion, $dedicatoria, $ofertaId, $total, $pedidoId, $estado);
        $stmt->execute();
        $stmt->close();
    }
    
}
