<?php
include_once("config/database.php");
include_once("model/platos.php");
class platosDAO {
    public static function getAll($id = "L"){
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Productos");
      
        $stmt->execute();
        $result = $stmt->get_result();
      
        
        $productos=[];
        while($producto = $result->fetch_object("Platos")){
            $productos[] = $producto;
        }
        $con->close();
        return $productos;
    }
    
    public static function getId($id)
    {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Productos WHERE ID_Producto = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $producto = $result->fetch_object("Platos");
        $con->close();

        return $producto;
    }

    public static function ObtenerTodosLosPedidos()
    {
        $con = database::connect();

        $stmt = $con->prepare("
            SELECT * FROM Pedidos 
        ");
        $stmt->execute();
        $resultado = $stmt->get_result();

        $pedidos = [];
        while ($pedido = $resultado->fetch_assoc()) {
            $pedidos[] = $pedido;
        }
        $stmt->close();

        return $pedidos;
    }

    public static function obtenerTodosLosProductos()
    {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Productos");
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_assoc()) {
            $productos[] = $producto;
        }
        $con->close();

        return $productos;
    }

    public static function eliminarProducto($id) {
        $con = database::connect();
        $stmt = $con->prepare("DELETE FROM Productos WHERE ID_Producto = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $con->close();
        return $result;
    }
    
    public static function editarProducto($id, $nombre, $descripcion, $precio, $imagen) {
        $con = database::connect();
        $stmt = $con->prepare("UPDATE Productos SET Nombre = ?, Descripcion = ?, Precio = ?, Imagen = ? WHERE ID_Producto = ?");
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $id);
        $result = $stmt->execute();
        $con->close();
        return $result;
    }
    
    public static function crearProducto($nombre, $descripcion, $precio, $imagen) {
        $con = database::connect();
        $stmt = $con->prepare("INSERT INTO Productos (Nombre, Descripcion, Precio, Imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen);
        $stmt->execute();
        $id = $con->insert_id;
        $con->close();
        return $id;
    }


 }

?>