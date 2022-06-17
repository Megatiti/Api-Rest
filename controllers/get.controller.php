<?php

require_once "models/get.model.php";

class GetController{

    //peticiones sin filtro
    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){
        $response = GetModel::getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones con filtro
    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        $response = GetModel::getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones relacionas sin filtro
    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){
        $response = GetModel::getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones relacionadas con filtro
    static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        $response = GetModel::getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones con buscador sin relaciones
    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
        $response = GetModel::getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones con buscador y relacionadas 
    static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
        $response = GetModel::getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones con rango y filtro
    static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
        $response = GetModel::getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //peticiones con rango relacionadas y filtro
    static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
        $response = GetModel::getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
        $return = new GetController();
        $return->fncResponse($response);

        return $response;
    }

    //respuestas del controlador
    public function fncResponse($response){
        if(!empty($response)){
            $json = array(
                'status' => 202,
                'total' => count($response),
                'results' => $response
            );
        }else{
            $json = array(
                'status' => 404,
                'results' => "NOT FOUND",
                "method" => "GET"
            );
        }

        
        
        echo json_encode($json, http_response_code($json["status"]));        
    }
}