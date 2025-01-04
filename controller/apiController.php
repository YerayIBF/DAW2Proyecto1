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
include_once("model/logsDAO.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class apiController
{

    public function getUsuarioActual() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : null;
    }


    public function pedidos()
    {
        include_once 'view/pedidoAdmin.php';
    }

    public function detallesPedidos()
    {
        include_once 'view/detallePedidoAdmin.php';
    }

    public function ofertas()
    {
        include_once 'view/ofertasAdmin.php';
    }


    public function usuarios()
    {
        include_once 'view/usuarioAdmin.php';
    }

    public function productos()
    {
        include_once 'view/productoAdmin.php';
    }

    public function logs()
    {
        include_once 'view/logsAdmin.php';
    }


    public function verPedidos()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $pedidos = PedidoDAO::ObtenerTodosLosPedidos();
        echo json_encode($pedidos);
    }


    public function verDetalles()
    {
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
    
    $usuarioActual = $this->getUsuarioActual();
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        return;
    }
    
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
        return;
    }
    
    try {
        $idPedido = intval($_GET['id']);
        
        // Eliminar detalles del pedido
        $resultadoDetalles = DetallePedidoDAO::eliminarDetallesPorPedido($idPedido);
        
        // Eliminar el pedido
        $resultadoPedido = PedidoDAO::eliminarPedido($idPedido);
        
        if ($resultadoPedido) {
            LogsDAO::crearLog("Eliminado pedido con ID: " . $idPedido, $usuarioActual);
            echo json_encode(['success' => true, 'message' => 'Pedido eliminado correctamente']);
        } else {
            throw new Exception('No se pudo eliminar el pedido');
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
    

    public function actualizarPedido() {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
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
                $datos->ID_Usuario,
                $datos->Dedicatoria,
                $datos->ID_Oferta,
                $datos->Precio_Total,
                $datos->Estado
            );
    
            if ($resultado) {
                LogsDAO::crearLog("Actualizado pedido con ID: " . $datos->ID_Pedido, $usuarioActual);
            }
            
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

        $usuarioActual = $this->getUsuarioActual();
    
        $datos = json_decode(file_get_contents("php://input"));
    
        try {
            $pedidoId = PedidoDAO::crearPedido(
                $datos->ID_Usuario,
                $datos->Direccion,
                $datos->Dedicatoria,
                $datos->ID_Oferta,
                $datos->Precio_Total
            );
    
            $logCreado = LogsDAO::crearLog("Se ha creado un nuevo pedido con ID: " . $pedidoId, $usuarioActual);
        
            if (!$logCreado) {
                error_log("Error al crear el log para el pedido: " . $pedidoId);
            }

            
            echo json_encode(['success' => true, 'pedidoId' => $pedidoId]);
           
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            error_log("Error al crear log: " . $e->getMessage());
            http_response_code(500);
        }
    }

    public function verUsuarios()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarios = UsuarioDAO::obtenerTodosUsuarios();
        echo json_encode($usuarios);
    }

    public function actualizarUsuario()
    {
        header("Content-Type: application/json; charset=UTF-8");

        $usuarioActual = $this->getUsuarioActual();
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }


        $datos = json_decode(file_get_contents("php://input"));
        if (!$datos || !isset($datos->ID_Usuario)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
        
        $resultado = UsuarioDAO::actualizarUsuario(
            $datos->ID_Usuario,
            $datos->Nombre,
            $datos->Correo,
            $datos->Rol
        );
        if ($resultado) {
            LogsDAO::crearLog("Actualizado usuario con ID: " . $datos->ID_Usuario, $usuarioActual);
        }
        echo json_encode(['success' => $resultado]);
    }

    public function eliminarUsuario()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }
        $idProducto = $_GET['id'];
        $resultado = UsuarioDAO::eliminarUsuario($_GET['id']);
        if ($resultado) {
            LogsDAO::crearLog("Eliminado usuario con ID: " . $idProducto, $usuarioActual);
        }
        echo json_encode(['success' => $resultado]);
    }

    public function crearUsuario()
    {
        header("Content-Type: application/json; charset=UTF-8");

        $usuarioActual = $this->getUsuarioActual();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        

        $datos = json_decode(file_get_contents("php://input"));
        if (!$datos || !isset($datos->Nombre) || !isset($datos->Correo) || !isset($datos->Contraseña)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        $resultado = UsuarioDAO::crearUsuario(
            $datos->Nombre,
            $datos->Correo,
            $datos->Contraseña,
            $datos->Rol ?? 'usuario'
        );

        if ($resultado) {
            LogsDAO::crearLog("Creado nuevo usuario: " . $datos->Nombre, $usuarioActual);
        }

        echo json_encode(['success' => $resultado]);
    }

    public function verProductos()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $productos = PlatosDAO::obtenerTodosLosProductos();
        echo json_encode($productos);
    }

    public function actualizarProducto()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            http_response_code(405);
            return;
        }

        $datos = json_decode(file_get_contents("php://input"));

        if (!$datos || !isset($datos->ID_Producto)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            http_response_code(400);
            return;
        }

        try {
            $resultado = PlatosDAO::editarProducto(
                $datos->ID_Producto,
                $datos->Nombre,
                $datos->Descripcion,
                $datos->Precio,
                $datos->Imagen
            );
            
        if ($resultado) {
            LogsDAO::crearLog("Actualizado producto con ID: " . $datos->ID_Producto, $usuarioActual);
            echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente']);
        }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function crearProducto()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            http_response_code(405);
            return;
        }

        $datos = json_decode(file_get_contents("php://input"));

        try {
            $productoId = PlatosDAO::crearProducto(
                $datos->Nombre,
                $datos->Descripcion,
                $datos->Precio,
                $datos->Imagen
            );

            if ($productoId) {
                LogsDAO::crearLog("Creado nuevo producto con ID " . $productoId, $usuarioActual);
                echo json_encode(['success' => true, 'productoId' => $productoId]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function eliminarProducto()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            http_response_code(405);
            return;
        }

        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            http_response_code(400);
            return;
        }

        try {
            $idProducto = $_GET['id'];
            $resultado = PlatosDAO::eliminarProducto($_GET['id']);
            if ($resultado) {
                LogsDAO::crearLog("Eliminado producto con ID: " . $idProducto, $usuarioActual);
            }
            echo json_encode(['success' => $resultado]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }
    // Obtener todas las ofertas
    public function verOfertas()
    {
        header("Content-Type: application/json; charset=UTF-8");
        
        $ofertas = OfertaDAO::obtenerTodasLasOfertas();
        echo json_encode($ofertas);
    }

    // Crear nueva oferta
    public function crearOferta()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
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
                LogsDAO::crearLog("Creada nueva oferta: " . $datos->Codigo, $usuarioActual);
                echo json_encode(['success' => true, 'message' => 'Oferta creada correctamente']);
            } else {
                throw new Exception('Error al crear la oferta');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    // Actualizar oferta existente
    public function actualizarOferta()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
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
                LogsDAO::crearLog("Actualizada oferta con ID: " . $datos->ID_Oferta, $usuarioActual);
                echo json_encode(['success' => true, 'message' => 'Oferta actualizada correctamente']);
            } else {
                throw new Exception('Error al actualizar la oferta');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    // Eliminar oferta
    public function eliminarOferta()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            http_response_code(405);
            return;
        }

        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            http_response_code(400);
            return;
        }

        try {
            $idOferta = $_GET['id'];
            $resultado = OfertaDAO::eliminarOferta($_GET['id']);

            if ($resultado) {
                LogsDAO::crearLog("Eliminada oferta con ID: " . $idOferta, $usuarioActual);
                echo json_encode(['success' => true, 'message' => 'Oferta eliminada correctamente']);
            } else {
                throw new Exception('Error al eliminar la oferta');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function verLogs() {
        header("Content-Type: application/json; charset=UTF-8");
        $logs = LogsDAO::ObtenerTodosLosLogs();
        echo json_encode($logs);
    }   



    public function crearDetallePedido() {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        
        // Añadir estos logs
        error_log("Iniciando crearDetallePedido");
        $rawData = file_get_contents("php://input");
        error_log("Datos recibidos: " . $rawData);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
    
        $datos = json_decode($rawData);
        
        // Añadir validación más detallada
        if (!$datos) {
            error_log("Error decodificando JSON: " . json_last_error_msg());
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error decodificando datos JSON']);
            return;
        }
        
        if (!isset($datos->ID_Pedido) || !isset($datos->ID_Producto) || 
            !isset($datos->Cantidad) || !isset($datos->Precio_Unitario)) {
            error_log("Datos incompletos: " . print_r($datos, true));
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
    
        try {
            // Convertir a los tipos correctos
            $idPedido = intval($datos->ID_Pedido);
            $idProducto = intval($datos->ID_Producto);
            $cantidad = intval($datos->Cantidad);
            $precioUnitario = floatval($datos->Precio_Unitario);
    
            $resultado = DetallePedidoDAO::agregarProducto(
                $idPedido,
                $idProducto,
                $cantidad,
                $precioUnitario
            );
            
            if ($resultado) {
                LogsDAO::crearLog("Creado detalle de pedido para pedido con ID: " . $idPedido, $usuarioActual);
                echo json_encode(['success' => true, 'message' => 'Detalle de pedido creado correctamente']);
            } else {
                throw new Exception('No se pudo crear el detalle del pedido');
            }
        } catch (Exception $e) {
            error_log("Error en crearDetallePedido: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function actualizarDetallePedido() {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
    
        $datos = json_decode(file_get_contents("php://input"));
        
        if (!$datos || !isset($datos->ID_DetallePedido) || !isset($datos->ID_Producto) || 
            !isset($datos->Cantidad) || !isset($datos->Precio_Unitario)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
    
        try {
            $resultado = DetallePedidoDAO::editarDetalle(
                $datos->ID_DetallePedido,
                $datos->Cantidad,
                $datos->Precio_Unitario
            );
            
            if ($resultado) {
                LogsDAO::crearLog("Actualizado detalle de pedido con ID: " . $datos->ID_DetallePedido, $usuarioActual);
                echo json_encode(['success' => true, 'message' => 'Detalle de pedido actualizado correctamente']);
            } else {
                throw new Exception('No se pudo actualizar el detalle del pedido');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function eliminarDetallePedido() {
        header("Content-Type: application/json; charset=UTF-8");
        $usuarioActual = $this->getUsuarioActual();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }
    
        try {
            $idDetalle = $_GET['id'];
            $resultado = DetallePedidoDAO::eliminarProducto($idDetalle);
            
            if ($resultado) {
                LogsDAO::crearLog("Eliminado detalle de pedido con ID: " . $idDetalle, $usuarioActual);
                echo json_encode(['success' => true, 'message' => 'Detalle de pedido eliminado correctamente']);
            } else {
                throw new Exception('No se pudo eliminar el detalle del pedido');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}