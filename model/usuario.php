<?php
class Usuario {
    protected $ID_Usuario;
    protected $nombre;
    protected $correo;
    protected $contraseña;
    protected $rol;

    public function __construct($ID_Usuario, $nombre, $correo, $contraseña, $rol = 'usuario') {
        $this->ID_Usuario = $ID_Usuario;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->contraseña = $contraseña;
        $this->rol = $rol;
    }
    
  



    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of correo
     */ 
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set the value of correo
     *
     * @return  self
     */ 
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get the value of contraseña
     */ 
    public function getContraseña()
    {
        return $this->contraseña;
    }

    /**
     * Set the value of contraseña
     *
     * @return  self
     */ 
    public function setContraseña($contraseña)
    {
        $this->contraseña = $contraseña;

        return $this;
    }

    /**
     * Get the value of rol
     */ 
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set the value of rol
     *
     * @return  self
     */ 
    public function setRol($rol)
    {
        $this->rol = $rol;

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
}
?>
