<?php
class detallePedido {
    protected $ID_DetallePedido; 
    
    protected $ID_Pedido; 
    
    protected $ID_Producto; 
    
    protected $Cantidad;
    
    protected $Precio_Unitario;

    protected $PrecioTotal;
    

    public function __construct() {

    }  
    


    /**
     * Get the value of PrecioTotal
     */ 
    public function getPrecioTotal()
    {
        return $this->PrecioTotal;
    }

    /**
     * Set the value of PrecioTotal
     *
     * @return  self
     */ 
    public function setPrecioTotal($PrecioTotal)
    {
        $this->PrecioTotal = $PrecioTotal;

        return $this;
    }

    /**
     * Get the value of Precio_Unitario
     */ 
    public function getPrecio_Unitario()
    {
        return $this->Precio_Unitario;
    }

    /**
     * Set the value of Precio_Unitario
     *
     * @return  self
     */ 
    public function setPrecio_Unitario($Precio_Unitario)
    {
        $this->Precio_Unitario = $Precio_Unitario;

        return $this;
    }

    /**
     * Get the value of Cantidad
     */ 
    public function getCantidad()
    {
        return $this->Cantidad;
    }

    /**
     * Set the value of Cantidad
     *
     * @return  self
     */ 
    public function setCantidad($Cantidad)
    {
        $this->Cantidad = $Cantidad;

        return $this;
    }

    /**
     * Get the value of ID_Producto
     */ 
    public function getID_Producto()
    {
        return $this->ID_Producto;
    }

    /**
     * Set the value of ID_Producto
     *
     * @return  self
     */ 
    public function setID_Producto($ID_Producto)
    {
        $this->ID_Producto = $ID_Producto;

        return $this;
    }

    /**
     * Get the value of ID_DetallePedido
     */ 
    public function getID_DetallePedido()
    {
        return $this->ID_DetallePedido;
    }

    /**
     * Set the value of ID_DetallePedido
     *
     * @return  self
     */ 
    public function setID_DetallePedido($ID_DetallePedido)
    {
        $this->ID_DetallePedido = $ID_DetallePedido;

        return $this;
    }

    /**
     * Get the value of ID_Pedido
     */ 
    public function getID_Pedido()
    {
        return $this->ID_Pedido;
    }

    /**
     * Set the value of ID_Pedido
     *
     * @return  self
     */ 
    public function setID_Pedido($ID_Pedido)
    {
        $this->ID_Pedido = $ID_Pedido;

        return $this;
    }
}