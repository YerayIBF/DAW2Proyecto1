<?php
include_once("model/PlatosDAO.php");
include_once("model/Platos.php");


class productoController{
    public function index(){
        $productos = platosDAO::getAll();
        include_once 'view/header.php';
        include_once 'view/home.php';
        include_once 'view/footer.php';
    }
    public function carta(){
        
        include_once 'view/header.php';
        include_once 'view/carta.php';
        include_once 'view/footer.php';
    }
    public function carrito(){
        
        include_once 'view/header.php';
        include_once 'view/carrito.php';
        include_once 'view/footer.php';

    }
   
}


?>