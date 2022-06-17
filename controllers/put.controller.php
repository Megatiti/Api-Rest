<?php

require_once "models/put.model.php";

class PutController{

    static public function putData($table, $data, $id, $nameid){
        $response = PutModel::putData($table, $data, $id, $nameid);

        $return = new PutController();
        $return->fncResponse($response);
    }

    //respuestas del controlador
    public function fncResponse($response){
        if(!empty($response)){
            $json = array(
                'status' => 202,
                'results' => $response
            );
        }else{
            $json = array(
                'status' => 404,
                'results' => "NOT FOUND",
                "method" => "PUT"
            );
        }
        echo json_encode($json, http_response_code($json["status"]));        
    }
}