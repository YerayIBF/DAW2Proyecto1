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

 }

?>