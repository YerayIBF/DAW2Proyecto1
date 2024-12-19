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


        public function pedidos(){
            include_once 'view/pedidoAdmin.php';  
            
        }

      

        public function usuarios(){
            include_once 'view/pedidoAdmin.php';  
            
        }

        public function productos(){
            include_once 'view/pedidoAdmin.php';  
            
        }

        public function logs(){
            include_once 'view/pedidoAdmin.php';  
            
        }
        
        
        public function verPedidos() {
            header('Content-Type: application/json');
            $pedidos = PedidoDAO::ObtenerTodosLosPedidos();
            echo json_encode($pedidos);
            
        }
        
    }
    

?>