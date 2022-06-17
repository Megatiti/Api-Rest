<?php

require_once "models/connection.php";
require_once "controllers/post.controller.php"; 

if(isset($_POST)){
    $columns = array();

    foreach(array_keys($_POST) as $key => $value){
        array_push($columns, $value);
    }

    //validar la tabla y las columnas
    if(empty(Connection::getColumnsData($table, $columns))){
        $json = array(
            'status' => 404,
            'result' => "Error: Los campos en el formulario no coinciden con la base de datos"
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;
    }

    $response = new PostController();
    
    if(isset($_GET["register"]) && $_GET["register"] == true){
        //solicitud post para el registro de usuarios
        $suffix = $_GET["suffix"] ?? "user";
        $response->postRegister($table, $_POST, $suffix);

    }else if(isset($_GET["login"]) && $_GET["login"] == true){
        //solicitud post para el login de usuarios
        $suffix = $_GET["suffix"] ?? "user";
        $response->postLogin($table, $_POST, $suffix);

    }else{
        //peticiones post para usuarios autorizados
        if(isset($_GET["token"])){
            if($_GET["token"] == "no" && isset($_GET["except"])){
                $columns = array($_GET["except"]);
                if(empty(Connection::getColumnsData($table, $columns))){
                    $json = array(
                        'status' => 404,
                        'result' => "Error: Los campos en el formulario no coinciden con la base de datos"
                    );
                    echo json_encode($json, http_response_code($json["status"]));
                    
                    return;
                }
                //solicitamos respuesta del controlador para crear datos en cualquier tabla
                $response->postData($table, $_POST);
            }else{
                $tableToken = $_GET["table"] ?? "users";
                $suffix = $_GET["suffix"] ?? "user";
    
                $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);
                
                if($validate == "ok"){
                    //solicitamos respuesta del controlador para crear datos en cualquier tabla
                    $response->postData($table, $_POST);
                }else if($validate == "expired"){
                    //token expriado
                    $json = array(
                        'status' => 303,
                        'result' => "Error: Usuario inactivo"
                    );
                    echo json_encode($json, http_response_code($json["status"]));
            
                    return;
                }else if($validate == "no-auth"){
                    //token erroneo
                    $json = array(
                        'status' => 400,
                        'result' => "Error: Usuario no Autorizado"
                    );
                    echo json_encode($json, http_response_code($json["status"]));
            
                    return;
                }
            }
        }else{
            //no existe token
            $json = array(
                'status' => 400,
                'result' => "Error: Se requiere autorizacion"
            );
            echo json_encode($json, http_response_code($json["status"]));
    
            return;
        }
        
    }
}