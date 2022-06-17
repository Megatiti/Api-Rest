<?php

require_once "models/delete.model.php";

class DeleteController{

    static public function deleteData($table, $id, $nameid){
        $response = DeleteModel::deleteData($table, $id, $nameid);

        $return = new DeleteController();
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