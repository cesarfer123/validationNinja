<?php

class User{
    private $errors=array();
    public function signup($POST){
        foreach ($POST as $key => $value)  {
            // username
            if($key=="username"){
                if(trim($value)==""){
                    $this->errors[]="Introduce un nombre valido";  
                }
                if(is_numeric($value)){
                    $this->errors[]="El nombre no puede ser numero";  
                }
                if(preg_match("/[0-9]+/",$value)){
                    $this->errors[]="el nombre no puede contener numeros";  
                }
            }
            // email   
            if($key=="email"){

                if(trim($value)==""){
                    $this->errors[]="Introduce un email valido";  
                }

                if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->errors[]="El email no es valido";
                }
            }
            // password   
            if($key=="password"){
                // verifica si esta vacio
                if(trim($value)==""){
                    $this->errors[]="Introduce un password valido";  
                }

                if(strlen($value)<4){
                    $this->errors[]="La contraseña debe tener al meos 4 caracteres";
                }
            }
        }

        
        $DB=new Database();
        // comprobar si el email ya existe
        $data=array();
        $data["email"]=$POST["email"];
        $query="select * from users where email= :email limit 1";
        $result=$DB->read($query,$data);
        
        // echo "<pre>";
        // echo print_r($result);
        // echo "</pre>";
        // die();
        if($result){
            $this->errors[]="ese email ya esta en uso";
        }

        //guardar a la bbdd
        if(count($this->errors)==0){
            // guardar

            $query="insert into users (username,email,password,date) values (:username,:email,:password,:date)";
           
            $data=array();
            $data["username"]=$POST["username"];
            $data["email"]=$POST["email"];
            $data["password"]=$POST["password"];
            $data["date"]=date("Y-m-d H:i:s");
            $result=$DB->write($query,$data);

            if(!$result){
                $this->errors[]="tu informacion no puede ser guardada";
            }else{

            }

        } 
        return $this->errors;
    }
}