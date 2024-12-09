<?php
class oferta {
    protected $ID_Oferta; 
    
    protected $Codigo; 
    
    protected $Descuento; 
    
    protected $Usos_Disponibles;
    

    public function __construct() {

    }  
    


    /**
     * Get the value of Usos_Disponibles
     */ 
    public function getUsos_Disponibles()
    {
        return $this->Usos_Disponibles;
    }

    /**
     * Set the value of Usos_Disponibles
     *
     * @return  self
     */ 
    public function setUsos_Disponibles($Usos_Disponibles)
    {
        $this->Usos_Disponibles = $Usos_Disponibles;

        return $this;
    }

    /**
     * Get the value of Descuento
     */ 
    public function getDescuento()
    {
        return $this->Descuento;
    }

    /**
     * Set the value of Descuento
     *
     * @return  self
     */ 
    public function setDescuento($Descuento)
    {
        $this->Descuento = $Descuento;

        return $this;
    }

    /**
     * Get the value of Codigo
     */ 
    public function getCodigo()
    {
        return $this->Codigo;
    }

    /**
     * Set the value of Codigo
     *
     * @return  self
     */ 
    public function setCodigo($Codigo)
    {
        $this->Codigo = $Codigo;

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
}