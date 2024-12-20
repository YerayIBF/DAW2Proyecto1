<?php
class Usuario {
    protected $ID_Usuario; 
    
    protected $Nombre; 
    
    protected $Correo; 
    
    protected $Contraseña;
    
    protected $Rol;
    

    public function __construct() {

    }  
    

    /**
     * Get the value of ID_Usuario
     */ 
    public function getIDUsuario()
    {
        return $this->ID_Usuario;
    }

    /**
     * Set the value of ID_Usuario
     *
     * @return  self
     */ 
    public function setIDUsuario($ID_Usuario)
    {
        $this->ID_Usuario = $ID_Usuario;

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
     * Get the value of Correo
     */ 
    public function getCorreo()
    {
        return $this->Correo;
    }

    /**
     * Set the value of Correo
     *
     * @return  self
     */ 
    public function setCorreo($Correo)
    {
        $this->Correo = $Correo;

        return $this;
    }

    /**
     * Get the value of Contraseña
     */ 
    public function getContraseña()
    {
        return $this->Contraseña;
    }

    /**
     * Set the value of Contraseña
     *
     * @return  self
     */ 
    public function setContraseña($Contraseña)
    {
        $this->Contraseña = $Contraseña;

        return $this;
    }

    /**
     * Get the value of Rol
     */ 
    public function getRol()
    {
        return $this->Rol;
    }

    /**
     * Set the value of Rol
     *
     * @return  self
     */ 
    public function setRol($Rol)
    {
        $this->Rol = $Rol;

        return $this;
    }
}