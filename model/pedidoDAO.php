<?php
include_once("config/database.php");
include_once("model/pedido.php");
class PedidoDAO {
    public static function crearPedido($usuarioId, $direccion, $dedicatoria, $ofertaId, $total)
    {
        $con = database::connect();
        $fecha = date('Y-m-d H:i:s');
    
        $stmt = $con->prepare("
            INSERT INTO Pedidos (ID_Usuario, Direccion, Dedicatoria, ID_Oferta, Precio_Total, Fecha_Pedido) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param('issids', $usuarioId, $direccion, $dedicatoria, $ofertaId, $total, $fecha);
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
    
}
