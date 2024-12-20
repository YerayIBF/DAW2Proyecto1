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

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    class apiController {


        public function pedidos(){
            include_once 'view/pedidoAdmin.php';  
            
        }

      

        public function usuarios(){
            
        }

        public function productos(){
        
            
        }

        public function logs(){
             
            
        }
        
        
        public function verPedidos() {
            header("Content-Type: application/json; charset=UTF-8");
            $pedidos = PedidoDAO::ObtenerTodosLosPedidos();
            echo json_encode($pedidos);
            
        }

        public function eliminarPedido($idPedido) {
            header("Content-Type: application/json; charset=UTF-8");
        
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405); // Método no permitido
                return;
            }
        
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
                http_response_code(400); // Solicitud incorrecta
                return;
            }
        
            $idPedido = intval($_GET['id']);
            $resultado = PedidoDAO::eliminarPedido($idPedido);
        
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Pedido eliminado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el pedido']);
                http_response_code(500); // Error interno del servidor
            }
        }
          
    }
    
    

?>