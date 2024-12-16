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

class productoController
{
    public function index()
    {

        session_start();
        $productos = platosDAO::getAll();
        include_once 'view/header.php';
        include_once 'view/home.php';
        include_once 'view/footer.php';
    }

    public function verCuenta()
    {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=producto&action=iniciarSession");
            exit();
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $pedidos = PedidoDAO::obtenerPedidosPorUsuario($usuarioId);

        include_once 'view/header.php';
        include_once 'view/cuentausuario.php';
        include_once 'view/footer.php';
    }

    public function carta()
    {
        session_start();
        $productos = platosDAO::getAll();
        include_once 'view/header.php';
        include_once 'view/carta.php';
        include_once 'view/footer.php';
    }
    public function carrito()
    {
        session_start();

        $totalCarrito = 0;


        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto) {

                $totalProducto = $producto->getPrecio() * $producto->getCantidad();


                $totalCarrito += $totalProducto;


                $producto->totalProducto = $totalProducto;
            }
        }

        include_once 'view/header.php';
        include_once 'view/carrito.php';
        include_once 'view/footer.php';
    }



    public function iniciarSession()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'];
            $contraseña = $_POST['contraseña'];

            $usuario = UsuarioDAO::autenticarUsuario($correo, $contraseña);
            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = [
                    'id' => $usuario->getIDUsuario(),
                    'nombre' => $usuario->getNombre(),
                    'correo' => $usuario->getCorreo(),
                    'rol' => $usuario->getRol()
                ];


                header("Location: ?controller=producto&action=index");
                exit();
            } else {
                echo "Correo o contraseña incorrectos.";
            }
        } else {
            include_once 'view/header.php';
            include_once 'view/login.php';
            include_once 'view/footer.php';
        }
    }


    public function registrarte()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $contraseña = $_POST['contraseña'];


            $usuarioExistente = UsuarioDAO::autenticarUsuario($correo, $contraseña);
            if ($usuarioExistente) {
                echo "El correo ya está registrado.";
                return;
            }


            if (UsuarioDAO::crearUsuario($nombre, $correo, $contraseña)) {
                header("Location: ?controller=producto&action=iniciarSession");
                exit();
            } else {
                echo "Error al registrar el usuario.";
            }
        } else {
            include_once 'view/header.php';
            include_once 'view/registro.php';
            include_once 'view/footer.php';
        }
    }
    public function cerrarSession()
    {
        session_start();


        session_unset();


        session_destroy();

        header("Location: ?controller=producto&action=iniciarSession");
        exit();
    }

    public function addProducto()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=producto&action=iniciarSession");
            exit();
        }

        if (isset($_POST['ID_Producto'])) {
            $idProducto = $_POST['ID_Producto'];


            $producto = platosDAO::getId($idProducto);

            if ($producto) {
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }


                $existe = false;
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item->getID_Producto() == $idProducto) {

                        $cantidadActual = $item->getCantidad();
                        $item->setCantidad($cantidadActual + 1);
                        $existe = true;
                        break;
                    }
                }


                if (!$existe) {
                    $producto->setCantidad(1);
                    $_SESSION['carrito'][] = $producto;
                }
            }
        }
        header("Location: ?controller=producto&action=index");
        exit();
    }

    public function eliminarProducto()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=producto&action=iniciarSession");
            exit();
        }

        if (isset($_GET['id'])) {
            $idProducto = $_GET['id'];

            if (isset($_SESSION['carrito'])) {
                foreach ($_SESSION['carrito'] as $key => $producto) {
                    if ($producto->getID_Producto() == $idProducto) {
                        unset($_SESSION['carrito'][$key]);
                        break;
                    }
                }

                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }
        }

        header("Location: ?controller=producto&action=index");
        exit();
    }

    public function actualizarCantidad()
    {
        session_start();


        if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
            $idProducto = $_POST['id_producto'];
            $nuevaCantidad = $_POST['cantidad'];

            if ($nuevaCantidad <= 0) {
                if (isset($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as $key => $producto) {
                        if ($producto->getID_Producto() == $idProducto) {
                            unset($_SESSION['carrito'][$key]);
                            break;
                        }
                    }

                    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                }
            } else {

                if (isset($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as &$producto) {
                        if ($producto->getID_Producto() == $idProducto) {
                            $producto->setCantidad($nuevaCantidad);
                            $producto->totalProducto = $producto->getPrecio() * $nuevaCantidad;
                            break;
                        }
                    }
                }
            }
        }

        header("Location: ?controller=producto&action=carrito");
        exit();
    }

    public function quitarProductoCarrito()
    {
        session_start();


        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=producto&action=iniciarSession");
            exit();
        }


        if (isset($_GET['id'])) {
            $idProducto = $_GET['id'];


            if (isset($_SESSION['carrito'])) {
                foreach ($_SESSION['carrito'] as $key => $producto) {
                    if ($producto->getID_Producto() == $idProducto) {
                        unset($_SESSION['carrito'][$key]);
                        break;
                    }
                }


                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }
        }


        header("Location: ?controller=producto&action=carrito");
        exit();
    }


    public function paginaFinalizarPedido()
    {
        session_start();

        
        $subtotal = 0;
        $cantidadArticulos = 0;
        $descuento = $_SESSION['descuento'] ?? 0;
        $codigoCupon = $_SESSION['codigo_oferta'] ?? null;
        $envio = 0;
        $impuestoPorcentaje = 21; 
        $ahorroTotal = 0;
    
      
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto) {
                $subtotal += $producto->getPrecio() * $producto->getCantidad();
                $cantidadArticulos += $producto->getCantidad();
            }
        }
    
       
        $descuentoAplicado = $subtotal * ($descuento / 100);
        $ahorroTotal = $descuentoAplicado;
    
        // Calcular envío
        if ($subtotal >= 49) {
            $envio = 0;
        } else {
            $envio = 3.99;
        }
    
       
        $totalSinImpuestos = $subtotal - $descuentoAplicado + $envio;
    
      
        $impuestos = $totalSinImpuestos * ($impuestoPorcentaje / 100);
    
       
        $totalConImpuestos = $totalSinImpuestos + $impuestos;
    
        if (isset($_POST['dedicatoria'])) {
            $_SESSION['dedicatoria'] = $_POST['dedicatoria'];
        }

        include "view/finalizar_pedido.php";
    }

    public function aplicarCupon(){
        session_start();
        if (isset($_POST['Oferta'])) {
            $codigoCupon = $_POST['Oferta'];
            $oferta = OfertaDAO::obtenerCodigo($codigoCupon);
            if ($oferta && $oferta->getUsos_Disponibles() > 0) {
                $_SESSION['descuento'] = $oferta->getDescuento(); 
                $_SESSION['codigo_oferta'] = $oferta->getID_Oferta(); 
                header("Location: ?controller=producto&action=paginaFinalizarPedido");
                exit();
            }
            } else {
                // echo "El cupón no es válido o ya no tiene usos disponibles.";
            }
        }
    
    public function finalizarPedido(){
        session_start();
        if (isset($_POST['direccion'])) {
            $ID_Usuario = $_SESSION['usuario']['id'];
            $Direccion = $_POST['direccion'];
            $Dedicatoria = isset($_SESSION['dedicatoria']) ? $_SESSION['dedicatoria'] : null;
            $codigoOferta = isset($_SESSION['codigo_oferta']) ? $_SESSION['codigo_oferta'] : null;
            $Precio_Total = $this->calcularTotalConDescuento($_SESSION['carrito'], $_SESSION['descuento'] ?? 0);
            

            if ($codigoOferta) {
                OfertaDAO::reducirUsos($codigoOferta);
            }


           $pedidoID = PedidoDAO::crearPedido($ID_Usuario, $Direccion, $Dedicatoria, $codigoOferta, $Precio_Total);

            if ($pedidoID) {

                foreach ($_SESSION['carrito'] as $producto) {
                    $productoId = $producto->getID_Producto();
                    $cantidad = $producto->getCantidad();
                    $precioUnitario = $producto->getPrecio();
                    DetallePedidoDAO::agregarDetalle($pedidoID, $productoId, $cantidad, $precioUnitario);
                }


                unset($_SESSION['carrito'], $_SESSION['dedicatoria'], $_SESSION['codigo_oferta'], $_SESSION['descuento']);

                echo "Pedido realizado con éxito. ID del pedido: $pedidoID";
            } else {
                echo "Hubo un error al realizar el pedido. Inténtelo de nuevo.";
            }
        }
    }


    private function calcularTotalConDescuento($carrito, $descuento)
    {
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto->totalProducto;
        }
        if ($descuento > 0) {
            $total -= $total * ($descuento / 100);
        }
        return $total;
    }


    public function panelControl()
    {
        session_start();
        include_once 'view/panel-control.php';
    }
}
