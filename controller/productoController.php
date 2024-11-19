<?php
include_once("model/PlatosDAO.php");
include_once("model/Platos.php");
include_once("model/usuario.php");
include_once("model/usuarioDAO.php");

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
        include_once 'view/header.php';
        include_once 'view/carrito.php';
        include_once 'view/footer.php';
    }

    public function iniciarSession(){
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
}
