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

        public function detallesPedidos(){
            include_once 'view/detallePedidoAdmin.php';  
            
        }

        public function ofertas(){
            include_once 'view/ofertasAdmin.php';  
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

        public function verOfertas() {
            header("Content-Type: application/json; charset=UTF-8");
            $ofertas = OfertaDAO::obtenerTodasLasOfertas();
            echo json_encode($ofertas);
        }

        public function verDetalles() {
            header("Content-Type: application/json; charset=UTF-8");
        
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
                http_response_code(400);
                return;
            }
        
            $idPedido = intval($_GET['id']);
            $detalles = DetallePedidoDAO::obtenerDetallesPorPedido($idPedido);
        
            if (empty($detalles)) {
                echo json_encode(['success' => false, 'message' => 'No se encontraron detalles para el pedido']);
                http_response_code(404);
                return;
            }
        
            echo json_encode(['success' => true, 'detalles' => $detalles]);
        }
        
        

        public function eliminarPedido() {
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
        
            // Paso 1: Eliminar detalles del pedido
            $resultadoDetalles = DetallePedidoDAO::eliminarDetallesPorPedido($idPedido);
        
            if (!$resultadoDetalles) {
                echo json_encode(['success' => false, 'message' => 'No se pudieron eliminar los detalles del pedido']);
                http_response_code(500); // Error interno del servidor
                return;
            }
        
            // Paso 2: Eliminar el pedido
            $resultadoPedido = PedidoDAO::eliminarPedido($idPedido);
        
            if ($resultadoPedido) {
                echo json_encode(['success' => true, 'message' => 'Pedido eliminado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el pedido']);
                http_response_code(500); // Error interno del servidor
            }
        }

        public function actualizarPedido() {
            header("Content-Type: application/json; charset=UTF-8");
        
            if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405);
                return;
            }
        
            $datos = json_decode(file_get_contents("php://input"));
        
            if (!$datos || !isset($datos->ID_Pedido)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                http_response_code(400);
                return;
            }
        
            try {
                $resultado = PedidoDAO::editarPedido(
                    $datos->ID_Pedido,
                    $datos->Direccion,
                    $datos->Dedicatoria, 
                    $datos->ID_Oferta,
                    $datos->Precio_Total,
                    $datos->Estado
                );
        
                echo json_encode(['success' => true, 'message' => 'Pedido actualizado correctamente']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                http_response_code(500);
            }
        }

        public function crearPedido() {
            header("Content-Type: application/json; charset=UTF-8");
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405);
                return;
            }
        
            $datos = json_decode(file_get_contents("php://input"));
            
            try {
                $pedidoId = PedidoDAO::crearPedido(
                    $datos->ID_Usuario,
                    $datos->Direccion,
                    $datos->Dedicatoria,
                    $datos->ID_Oferta,
                    $datos->Precio_Total
                );
                
                echo json_encode(['success' => true, 'pedidoId' => $pedidoId]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                http_response_code(500);
            }
        }

        public function eliminarDetallePedido() {
            header("Content-Type: application/json; charset=UTF-8");
        
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405);
                return;
            }
        
            $detalleId = intval($_GET['id'] ?? 0);
            if ($detalleId <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID de detalle no válido']);
                http_response_code(400);
                return;
            }
        
            $resultado = DetallePedidoDAO::eliminarProducto($detalleId);
            echo json_encode(['success' => $resultado]);
        }

        public function crearOferta() {
            header("Content-Type: application/json; charset=UTF-8");
    
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405);
                return;
            }
    
            $datos = json_decode(file_get_contents("php://input"));
    
            if (!$datos || !isset($datos->Codigo) || !isset($datos->Descuento) || !isset($datos->Usos_Disponibles)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                http_response_code(400);
                return;
            }
    
            try {
                $resultado = OfertaDAO::crearOferta(
                    $datos->Codigo,
                    $datos->Descuento,
                    $datos->Usos_Disponibles
                );
    
                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Oferta creada correctamente']);
                } else {
                    throw new Exception('Error al crear la oferta');
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                http_response_code(500);
            }
        }
        public function actualizarOferta() {
            header("Content-Type: application/json; charset=UTF-8");
    
            if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405);
                return;
            }
    
            $datos = json_decode(file_get_contents("php://input"));
    
            if (!$datos || !isset($datos->ID_Oferta)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                http_response_code(400);
                return;
            }
    
            try {
                $resultado = OfertaDAO::editarOferta(
                    $datos->ID_Oferta,
                    $datos->Codigo,
                    $datos->Descuento,
                    $datos->Usos_Disponibles
                );
    
                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Oferta actualizada correctamente']);
                } else {
                    throw new Exception('Error al actualizar la oferta');
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                http_response_code(500);
            }
        }

        public function eliminarOferta() {
            header("Content-Type: application/json; charset=UTF-8");
    
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                http_response_code(405);
                return;
            }
    
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de oferta no proporcionado']);
                http_response_code(400);
                return;
            }
    
            $idOferta = intval($_GET['id']);
    
            try {
                $resultado = OfertaDAO::eliminarOferta($idOferta);
    
                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Oferta eliminada correctamente']);
                } else {
                    throw new Exception('Error al eliminar la oferta');
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                http_response_code(500);
            }
        }

       
    }        
    
    

?>