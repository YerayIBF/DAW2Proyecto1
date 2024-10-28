<?php

class database {

    public static function connect($host = "localhost", $username = "root", $password = "yeray", $dbname = "BBDD_DAW2Proyecto1", $port= 3308) {
        $con = new mysqli($host, $username, $password, $dbname,$port);
        
  
    if($con === false){
        die("Error" . $con->connect_error);
    }else{
        return $con;
        
      
    }

    }
}


?>