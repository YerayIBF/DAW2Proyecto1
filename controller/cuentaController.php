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

class cuentaController
{

    public function verCuenta()
    {
        session_start();

        if (isset($_SESSION['pedido_exitoso']) && $_SESSION['pedido_exitoso']) {
            $pedidoID = $_SESSION['pedido_id'];
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => "¡Pedido realizado con éxito! Tu número de pedido es: $pedidoID"
            ];

            // Limpiamos las variables de control
            unset($_SESSION['pedido_exitoso']);
            unset($_SESSION['pedido_id']);
        }
        if (!isset($_SESSION['usuario'])) {
            header("Location: ?controller=cuenta&action=iniciarSession");
            exit();
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $pedidos = PedidoDAO::obtenerPedidosPorUsuario($usuarioId);

        include_once 'view/header.php';
        include_once 'view/cuentausuario.php';
        include_once 'view/footer.php';
    }

    public function iniciarSession() {
        $error = null;
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'];
            $contraseña = $_POST['contraseña'];
    
            $usuario = UsuarioDAO::autenticarUsuario($correo, $contraseña);
            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = [
                    'id' => $usuario->getIDUsuario(),
                    'nombre' => $usuario->getNombre(),
                    'apellido' => $usuario->getApellido(),
                    'correo' => $usuario->getCorreo(),
                    'rol' => $usuario->getRol()
                ];
    
                header("Location: ?controller=cuenta&action=verCuenta");
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        }
    
        include_once 'view/header.php';
        include_once 'view/login.php';
        include_once 'view/footer.php';
    }


    public function registrarte() {
        $error = null;
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $contraseña = $_POST['contraseña'];
    
            try {
                if (UsuarioDAO::verificarCorreoExistente($correo)) {
                    $error = "El correo ya está registrado.";
                } else {
                    if (UsuarioDAO::crearUsuario($nombre, $apellido, $correo, $contraseña)) {
                        header("Location: ?controller=cuenta&action=iniciarSession");
                        exit();
                    } else {
                        $error = "Error al registrar el usuario.";
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $error = "Error en el sistema. Por favor, intente más tarde.";
            }
        }
    
        include_once 'view/header.php';
        include_once 'view/registro.php';
        include_once 'view/footer.php';
    }
    
    public function cerrarSession()
    {
        session_start();


        session_unset();


        session_destroy();

        header("Location: ?controller=cuenta&action=iniciarSession");
        exit();
    }
}
