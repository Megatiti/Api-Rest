<?php

require_once "get.model.php";

class PutModel{

    static public function putData($table, $data, $id, $nameid){
        
        //validar que el id si exista
        $responseID = GetModel::getDataFilter($table, $nameid, $nameid, $id, null, null, null, null);
        if(empty($responseID)){
            $response = array(
                "comment" => "El Id no existe en la base de datos"
            );
            return $response;
        }

        //actualizamos el registro
        $set = "";
        foreach ($data as $key => $value){
            $set .= $key." = :".$key.", ";
        }
        $set = substr($set, 0, -2);

        $sql = "UPDATE $table SET $set WHERE $nameid = :$nameid";

        $link = Connection::connect();
        $stmt = $link->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);
        }
        $stmt->bindParam(":".$nameid, $id, PDO::PARAM_STR);

        if($stmt->execute()){
            $response = array(
                "comment" => "El registro se actualizo sin problemas"
            );
            return $response;
        } else{
            return $link->errorInfo();
        }
    }
}