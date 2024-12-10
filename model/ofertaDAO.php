<?php
include_once("config/database.php");
include_once("model/oferta.php");
class OfertaDAO {

    public static function obtenerCodigo($codigo) {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Oferta WHERE Codigo = ?");
        $stmt->bind_param('s', $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $oferta = $result->fetch_object('oferta');
        $stmt->close();
        return $oferta;
    }

    public static function reducirUsos($ofertaId) {
        $con = database::connect();
        $stmt = $con->prepare("UPDATE Oferta SET Usos_Disponibles = Usos_Disponibles - 1 WHERE ID_Oferta = ?");
        $stmt->bind_param('i', $ofertaId);
        $stmt->execute();
        $stmt->close();
    }
}

    

   
