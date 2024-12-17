<?php
include_once("model/PlatosDAO.php");
include_once("model/Platos.php");
include_once("model/usuario.php");
include_once("model/usuarioDAO.php");
include_once("model/pedido.php");
include_once("model/PedidoDAO.php");
include_once("model/oferta.php");
include_once("model/ofertaDAO.php");
include_once("model/detallePedido.php");
include_once("model/detallePedidoDAO.php");



    class apiController {
        public function Pedidos(){
            include_once 'view/pedidoAdmin.php';
        }

        
        public function verPedidos() {
           
            $pedidos = PedidoDAO::ObtenerTodosLosPedidos();
            echo json_encode($pedidos);
        }
        
    }
    

?>