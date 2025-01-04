<?php
include_once 'config/database.php';
include_once 'model/Usuario.php';

class UsuarioDAO {
    public static function crearUsuario($nombre, $correo, $contraseña, $rol = 'usuario') {
        $con = database::connect();
        
        $contraseñaHash = password_hash($contraseña, PASSWORD_BCRYPT);
        $stmt = $con->prepare("INSERT INTO Usuarios (Nombre, Correo, Contraseña, Rol) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssss", $nombre, $correo, $contraseñaHash, $rol);
            if ($stmt->execute()) {
                return true; 
            } else {
                return false; 
            }
        } else {
            die("Error preparando la consulta: " . $con->error);
        }
    }

    public static function obtenerTodosUsuarios() {
        $con = database::connect();
        $query = "SELECT ID_Usuario, Nombre, Correo, Rol FROM Usuarios";
        $resultado = $con->query($query);
        
        $usuarios = [];
        while ($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    public static function actualizarUsuario($id, $nombre, $correo, $rol) {
        $con = database::connect();
        $stmt = $con->prepare("UPDATE Usuarios SET Nombre=?, Correo=?, Rol=? WHERE ID_Usuario=?");
        
        if ($stmt) {
            $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
            return $stmt->execute();
        }
        return false;
    }

    public static function eliminarUsuario($id) {
        $con = database::connect();
        $stmt = $con->prepare("DELETE FROM Usuarios WHERE ID_Usuario = ?");
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }

    public static function autenticarUsuario($correo, $contraseña) {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Usuarios WHERE Correo = ?");
    
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();
    
            $datos = $resultado->fetch_object('Usuario');
            if ($datos && password_verify($contraseña, $datos->getContraseña())) {
                return $datos; 
            }
        }
    
        return null; 
        
    }

    
    

}
?>
