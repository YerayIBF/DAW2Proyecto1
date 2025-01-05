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

class carritoController{
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

        header("Location: ?controller=carrito&action=carrito");
        exit();
    }

    public function quitarProductoCarrito()
    {
        session_start();


        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=cuenta&action=iniciarSession");
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


        header("Location: ?controller=carrito&action=carrito");
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

    public function aplicarCupon()
{
    session_start();
    if (isset($_POST['Oferta'])) {
        $codigoCupon = $_POST['Oferta'];
        $oferta = OfertaDAO::obtenerCodigo($codigoCupon);
        
        if ($oferta) {
            if ($oferta->getUsos_Disponibles() > 0) {
                $_SESSION['descuento'] = $oferta->getDescuento();
                $_SESSION['codigo_oferta'] = $oferta->getID_Oferta();
                $_SESSION['nombre_oferta'] = $oferta->getCodigo();
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Cupón aplicado correctamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => 'Lo sentimos, este cupón ya no tiene usos disponibles'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'El código de cupón introducido no es válido'
            ];
        }
        
        header("Location: ?controller=carrito&action=paginaFinalizarPedido");
        exit();
    }
}

public function finalizarPedido()
{
    session_start();
    if (isset($_POST['direccion'])) {
        $ID_Usuario = $_SESSION['usuario']['id'];
        $Direccion = $_POST['direccion'];
        $Dedicatoria = isset($_SESSION['dedicatoria']) ? $_SESSION['dedicatoria'] : null;
        $codigoOferta = isset($_SESSION['codigo_oferta']) ? $_SESSION['codigo_oferta'] : null;
        
        // Calculamos el total con impuestos
        $Precio_Total = $this->calcularTotalConImpuestos($_SESSION['carrito'], $_SESSION['descuento'] ?? 0);

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

            $_SESSION['pedido_exitoso'] = true;
            $_SESSION['pedido_id'] = $pedidoID;

            unset($_SESSION['carrito'], $_SESSION['dedicatoria'], $_SESSION['codigo_oferta'], $_SESSION['descuento']);
            
            header("Location: ?controller=cuenta&action=verCuenta");
            exit();
        } else {
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'Hubo un error al realizar el pedido. Inténtelo de nuevo.'
            ];
            header("Location: ?controller=carrito&action=paginaFinalizarPedido");
            exit();
        }
    }
}



private function calcularTotalConImpuestos($carrito, $descuento)
{
    $subtotal = 0;
    $impuestoPorcentaje = 21; // 21% de IVA
    
    // Calcular subtotal
    foreach ($carrito as $producto) {
        $subtotal += $producto->getPrecio() * $producto->getCantidad();
    }
    
    // Aplicar descuento si existe
    if ($descuento > 0) {
        $descuentoAplicado = $subtotal * ($descuento / 100);
        $subtotal -= $descuentoAplicado;
    }
    
    // Calcular envío
    $envio = ($subtotal >= 49) ? 0 : 3.99;
    
    // Añadir envío al subtotal
    $subtotal += $envio;
    
    // Calcular y añadir impuestos
    $impuestos = $subtotal * ($impuestoPorcentaje / 100);
    $totalConImpuestos = $subtotal + $impuestos;
    
    return round($totalConImpuestos, 2); // Redondear a 2 decimales
}


}

