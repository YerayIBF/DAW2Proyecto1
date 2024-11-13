<?php
abstract class Productos{

    protected $ID_Producto;
    protected $Nombre;
    protected $Descripcion;
    protected $Precio;
    protected $Imagen;


    public function __construct($ID_Producto,$Nombre,$Descripcion,$Precio,$Imagen)
    {
        $this->ID_Producto=$ID_Producto;
        $this->Nombre=$Nombre;
        $this->Descripcion=$Descripcion;
        $this->Precio=$Precio;
        $this->Imagen=$Imagen;
    }



    /**
     * Get the value of Imagen
     */ 
    public function getImagen()
    {
        return $this->Imagen;
    }

    /**
     * Set the value of Imagen
     *
     * @return  self
     */ 
    public function setImagen($Imagen)
    {
        $this->Imagen = $Imagen;

        return $this;
    }

    /**
     * Get the value of Precio
     */ 
    public function getPrecio()
    {
        return $this->Precio;
    }

    /**
     * Set the value of Precio
     *
     * @return  self
     */ 
    public function setPrecio($Precio)
    {
        $this->Precio = $Precio;

        return $this;
    }

    /**
     * Get the value of Descripcion
     */ 
    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    /**
     * Set the value of Descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($Descripcion)
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    /**
     * Get the value of Nombre
     */ 
    public function getNombre()
    {
        return $this->Nombre;
    }

    /**
     * Set the value of Nombre
     *
     * @return  self
     */ 
    public function setNombre($Nombre)
    {
        $this->Nombre = $Nombre;

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
}




?>