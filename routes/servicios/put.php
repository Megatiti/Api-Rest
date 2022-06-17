<?php

require_once "models/connection.php";
require_once "controllers/put.controller.php"; 

if(isset($_GET["id"]) && isset($_GET["nameid"])){

    //capturamos datos del formulario
    $data = array();
    parse_str(file_get_contents('php://input'), $data);
    
    //separar propiedades de un arreglo
    $columns = array();
    foreach(array_keys($data) as $key => $value){
        array_push($columns, $value);
    }
    array_push($columns, $value);
    $columns = array_unique($columns);

    //validar tabla y columnas
    if(empty(Connection::getColumnsData($table, $columns))){
        $json = array(
            'status' => 404,
            'result' => "Error: Los campos en el formulario no coinciden con la base de datos"
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;
    }
    

    //peticiones put para usuarios autorizados
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
            $response = new PutController();
            $response->putData($table, $data, $_GET["id"], $_GET["nameid"]);
        }else{
            $tableToken = $_GET["table"] ?? "users";
            $suffix = $_GET["suffix"] ?? "user";

            $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);
            
            if($validate == "ok"){
                //solicitamos respuesta del controlador para crear datos en cualquier tabla
                $response = new PutController();
                $response->putData($table, $data, $_GET["id"], $_GET["nameid"]);
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