<?php
include_once("config/database.php");

class LogsDAO {
    public static function crearLog($Accion, $ID_Usuario) {
        try {
            $con = database::connect();
            if (!$con) {
                throw new Exception("Error de conexión");
            }
            
            $stmt = $con->prepare("INSERT INTO Logs_Admin (Accion, Fecha_Log, ID_Usuario) VALUES (?, NOW(), ?)");
            if (!$stmt) {
                throw new Exception("Error en preparación: " . $con->error);
            }
            
            $stmt->bind_param("si", $Accion, $ID_Usuario);
            $resultado = $stmt->execute();
            $stmt->close();
            
            return $resultado;
        } catch (Exception $e) {
            error_log("Error en LogsDAO::crearLog: " . $e->getMessage());
            return false; 
        }
    }

    
    public static function ObtenerTodosLosLogs()
    {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Logs_Admin");
        $stmt->execute();
        $resultado = $stmt->get_result();

        $logs = [];
        while ($log = $resultado->fetch_assoc()) {
            $logs[] = $log;
        }
        $stmt->close();

        return $logs;
    }

    
}
?>