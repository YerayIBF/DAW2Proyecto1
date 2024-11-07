<?php



class productoController{
    public function index(){
        
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