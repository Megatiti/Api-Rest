<?php

require_once "controllers/get.controller.php"; 

$select = $_GET["select"] ?? "*";
$orderBy = $_GET["orderBy"] ?? null;
$orderMode = $_GET["orderMode"] ?? null;
$startAt = $_GET["startAt"] ?? null;
$endAt = $_GET["endAt"] ?? null;
$filterTo = $_GET["filterto"] ?? null;
$inTo = $_GET["into"] ?? null;

$response = new GetController;

//peticiones get con filtro
if(isset($_GET["linkto"]) && isset($_GET["equalto"]) && !isset($_GET["rel"]) && !isset($_GET["type"])){
    $response->getDataFilter($table, $select, $_GET["linkto"], $_GET["equalto"], $orderBy, $orderMode, $startAt, $endAt);

//peticiones get sin filtro entre tablas relacionadas
}else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && !isset($_GET["linkto"]) && !isset($_GET["equalto"])){
    $response = $response->getRelData($_GET["rel"], $_GET["type"], $select, $orderBy, $orderMode, $startAt, $endAt);

//peticiones get con filtro entre tablas relacionadas
}else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkto"]) && isset($_GET["equalto"])){
    $response = $response->getRelDataFilter($_GET["rel"], $_GET["type"], $select, $_GET["linkto"], $_GET["equalto"], $orderBy, $orderMode, $startAt, $endAt);

//peticiones get con buscador sin relaciones
}else if(isset($_GET["linkto"]) && isset($_GET["search"]) && !isset($_GET["rel"]) && !isset($_GET["type"])){
    $response = $response->getDataSearch($table, $select, $_GET["linkto"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);

//peticiones get con buscador y relacionadas 
}else if(isset($_GET["linkto"]) && isset($_GET["search"]) && isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations"){
    $response = $response->getRelDataSearch($_GET["rel"], $_GET["type"], $select, $_GET["linkto"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);

//peticiones get con rango
}else if(isset($_GET["linkto"]) && isset($_GET["between1"]) && isset($_GET["between2"]) && !isset($_GET["rel"]) && !isset($_GET["type"])){
    $response = $response->getDataRange($table, $select, $_GET["linkto"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

//peticiones get con rango y relaciones
}else if(isset($_GET["linkto"]) && isset($_GET["between1"]) && isset($_GET["between2"]) && isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations"){
    $response = $response->getRelDataRange($_GET["rel"], $_GET["type"], $select, $_GET["linkto"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

}else{ 
//peticion sin filtro
    $response->getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
}

