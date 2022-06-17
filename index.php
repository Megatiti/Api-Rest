<?php

//mostrar errores
ini_set("display_errors",1);
ini_set("log_errors",1);
ini_set("error_log", "C:/xampp/htdocs/api-restfull/php_error_log");
//configurar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("content-type: application/json; charset-utf-8");

require_once "controllers/routes.controller.php";

$index = new RoutesController();
$index->index();