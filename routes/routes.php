<?php

$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

//sin peticiones a la api
if(empty($routesArray)){
    $json = array(
        'status' => 404,
        'result' => "not found"
    );
    
    echo json_encode($json, http_response_code($json["status"]));
    
    return;
}

//con peticiones a la api
if(!empty($routesArray) && isset($_SERVER["REQUEST_METHOD"])){
    //PETICIONES GET

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        
        include "servicios/get.php";
    }

    //PETICIONES POST

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $json = array(
            'status' => 202,
            'result' => "PAGINA POST"
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;
    }

    //PETICIONES PUT

    if($_SERVER["REQUEST_METHOD"] == "PUT"){
        $json = array(
            'status' => 202,
            'result' => "PAGINA PUT"
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;
    }

    //PETICIONES DELETE

    if($_SERVER["REQUEST_METHOD"] == "DELETE"){
        $json = array(
            'status' => 202,
            'result' => "PAGINA DELETE"
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;
    }
}


?>