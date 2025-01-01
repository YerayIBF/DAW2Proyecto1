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

    public static function obtenerTodasLasOfertas() {
        $con = database::connect();
        $stmt = $con->prepare("SELECT * FROM Oferta");
        $stmt->execute();
        $result = $stmt->get_result();

        $ofertas = [];
        while ($row = $result->fetch_assoc()) {
            $ofertas[] = $row;
        }
        $stmt->close();
        return $ofertas;
    }

    public static function crearOferta($codigo, $descuento, $usosDisponibles) {
        $con = database::connect();
        $stmt = $con->prepare("INSERT INTO Oferta (Codigo, Descuento, Usos_Disponibles) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $codigo, $descuento, $usosDisponibles);

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    public static function editarOferta($idOferta, $codigo, $descuento, $usosDisponibles) {
        $con = database::connect();
        $stmt = $con->prepare("UPDATE Oferta SET Codigo = ?, Descuento = ?, Usos_Disponibles = ? WHERE ID_Oferta = ?");
        $stmt->bind_param('siii', $codigo, $descuento, $usosDisponibles, $idOferta);

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    public static function eliminarOferta($idOferta) {
        $con = database::connect();
        $stmt = $con->prepare("DELETE FROM Oferta WHERE ID_Oferta = ?");
        $stmt->bind_param('i', $idOferta);

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

}

    

   
