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

class homeController{
    public function index()
    {
        session_start();
        $productos = platosDAO::getAll();
        include_once 'view/header.php';
        include_once 'view/home.php';
        include_once 'view/footer.php';
    }

    public function addProducto()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=cuenta&action=iniciarSession");
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
        header("Location: ?controller=home&action=index");
        exit();
    }

}


