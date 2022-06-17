<?php
require_once "models/connection.php";
require_once "controllers/get.controller.php";

$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

//sin peticiones a la api
if(empty($routesArray)){
    $json = array(
        'status' => 404,
        'results' => "not found"
    );
    echo json_encode($json, http_response_code($json["status"]));
    
    return;
}

//con peticiones a la api
if(!empty($routesArray) && isset($_SERVER["REQUEST_METHOD"])){

    $table = explode("?", $routesArray[1])[0];

    //valida llave secreta
    if(!isset(getallheaders()["Authorization"]) || getallheaders()["Authorization"] != Connection::apiKey()){
        if(in_array($table, Connection::publicAccess()) == 0){
            $json = array(
                'status' => 400,
                'results' => "No estas autorizado"
            );
            echo json_encode($json, http_response_code($json["status"]));
            
            return;
        }else{
            //acesso publico
            $response = new GetController();
            $response->getData($table, "*", null, null, null, null);

            return;
        }
    }

    //PETICIONES GET
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        include "servicios/get.php";
    }

    //PETICIONES POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        include "servicios/post.php";
    }

    //PETICIONES PUT
    if($_SERVER["REQUEST_METHOD"] == "PUT"){
        include "servicios/put.php";
    }

    //PETICIONES DELETE
    if($_SERVER["REQUEST_METHOD"] == "DELETE"){
        include "servicios/delete.php";
    }
}


?>