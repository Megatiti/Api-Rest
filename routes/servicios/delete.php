<?php

require_once "models/connection.php";
require_once "controllers/delete.controller.php"; 

if(isset($_GET["id"]) && isset($_GET["nameid"])){

    //separar propiedades de un arreglo
    $columns = array($_GET["nameid"]);

    //validar tabla y columnas
    if(empty(Connection::getColumnsData($table, $columns))){
        $json = array(
            'status' => 404,
            'result' => "Error: Los campos en el formulario no coinciden con la base de datos"
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;
    }
    
    //peticiones delete para usuarios autorizados
    if(isset($_GET["token"])){
        
        $tableToken = $_GET["table"] ?? "users";
        $suffix = $_GET["suffix"] ?? "user";

        $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);
        
        if($validate == "ok"){
            //solicitamos respuesta del controlador para eliminar datos en cualquier tabla
            $response = new DeleteController();
            $response->deleteData($table, $_GET["id"], $_GET["nameid"]);
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