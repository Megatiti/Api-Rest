<?php

require_once "get.model.php";

class Connection{

    //info de la base de datos
    static public function infoDatabase(){

        $infoDB = array(
            "database" => "database-1",
            "user" => "root",
            "pass" => ""
        );

        return $infoDB;
    }

    //conexion a la base de datos
    static public function connect(){

        try{
            $link = new PDO(
                "mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],
                Connection::infoDatabase()["user"],
                Connection::infoDatabase()["pass"]
            );
            $link->exec("set names utf8");


        }catch(PDOException $e){
            die("Error: ".$e->getMessage());
        }

        return $link;
    }

    //validar nombre de la tabla
    static public function getColumnsData($table, $columns){
        //traer el nombre de la base de datos
        $database = Connection::infoDatabase()["database"];
        //traer todas las colunmas de la tablas
        $validate = Connection::connect()
        ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
        ->fetchAll(PDO::FETCH_OBJ);
        //validamos existencia de la tabla
        if(!empty($validate)){
            //si hacemos solucitud de columnas globales *
            if($columns[0] == "*"){
                array_shift($columns);
            }

            //validamos la existencia de las columnas
            $sum = 0;
            foreach ($validate as $key => $value){
                $sum += in_array($value->item, $columns);
            }
            return $sum == count($columns) ? $validate : null;
        }else{
            return null;
        }
    }


    //generar token de autenticacion
    static public function jwt($id, $email){
        $time = time();

        $token = array(
            "iat" => $time, //tiempo en que inicia el token
            "exp" => $time + (60*60*24), //tiempo de expiracion del token
            "data" => [
                "id" => $id,
                "email" => $email
            ]
        );

        

        return $token;
    }

    //validar el token de seguridad
    static public function tokenValidate($token, $table, $suffix){
        //traemos el usuario del token
        $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token, null, null, null, null);
        if(!empty($user)){
            //validammos que el tokem no haya expirado
            
            $time = time();
            if($user[0]->{"token_exp_".$suffix} > $time){
                return "ok";
            }else{
                return "expired";
            }
        }else{
            return "no-auth";
        }
    }

    static public function apiKey(){
        return "A2gEtQYQcvttFi2hRE4kcqC6kWzHUd";
    }

    //acesso public
    static public function publicAccess(){
        $tables = ["courses"];
        return $tables;
    }
}