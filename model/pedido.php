<?php
class pedido {
    protected $ID_Pedido; 
    
    protected $Fecha_Pedido; 
    
    protected $Precio_Total; 
    
    protected $ID_Usuario;
    
    protected $Direccion;

    protected $ID_Oferta;
    
    protected $Dedicatoria;

    protected $Estado;


    public function __construct() {

    }  
    


    /**
     * Get the value of Dedicatoria
     */ 
    public function getDedicatoria()
    {
        return $this->Dedicatoria;
    }

    /**
     * Set the value of Dedicatoria
     *
     * @return  self
     */ 
    public function setDedicatoria($Dedicatoria)
    {
        $this->Dedicatoria = $Dedicatoria;

        return $this;
    }

    /**
     * Get the value of ID_Oferta
     */ 
    public function getID_Oferta()
    {
        return $this->ID_Oferta;
    }

    /**
     * Set the value of ID_Oferta
     *
     * @return  self
     */ 
    public function setID_Oferta($ID_Oferta)
    {
        $this->ID_Oferta = $ID_Oferta;

        return $this;
    }

    /**
     * Get the value of Direccion
     */ 
    public function getDireccion()
    {
        return $this->Direccion;
    }

    /**
     * Set the value of Direccion
     *
     * @return  self
     */ 
    public function setDireccion($Direccion)
    {
        $this->Direccion = $Direccion;

        return $this;
    }

    /**
     * Get the value of ID_Usuario
     */ 
    public function getID_Usuario()
    {
        return $this->ID_Usuario;
    }

    /**
     * Set the value of ID_Usuario
     *
     * @return  self
     */ 
    public function setID_Usuario($ID_Usuario)
    {
        $this->ID_Usuario = $ID_Usuario;

        return $this;
    }

    /**
     * Get the value of Precio_Total
     */ 
    public function getPrecio_Total()
    {
        return $this->Precio_Total;
    }

    /**
     * Set the value of Precio_Total
     *
     * @return  self
     */ 
    public function setPrecio_Total($Precio_Total)
    {
        $this->Precio_Total = $Precio_Total;

        return $this;
    }

    /**
     * Get the value of Fecha_Pedido
     */ 
    public function getFecha_Pedido()
    {
        return $this->Fecha_Pedido;
    }

    /**
     * Set the value of Fecha_Pedido
     *
     * @return  self
     */ 
    public function setFecha_Pedido($Fecha_Pedido)
    {
        $this->Fecha_Pedido = $Fecha_Pedido;

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

    /**
     * Get the value of Estado
     */ 
    public function getEstado()
    {
        return $this->Estado;
    }

    /**
     * Set the value of Estado
     *
     * @return  self
     */ 
    public function setEstado($Estado)
    {
        $this->Estado = $Estado;

        return $this;
    }
}