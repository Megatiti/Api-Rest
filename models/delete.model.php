<?php

require_once "get.model.php";

class DeleteModel{

    static public function deleteData($table, $id, $nameid){
        
        //validar que el id si exista
        $responseID = GetModel::getDataFilter($table, $nameid, $nameid, $id, null, null, null, null);
        if(empty($responseID)){
            $response = array(
                "comment" => "El Id no existe en la base de datos"
            );
            return $response;
        }

        //eliminamos el registro

        $sql = "DELETE FROM $table WHERE $nameid = :$nameid";

        $link = Connection::connect();
        $stmt = $link->prepare($sql);

        $stmt->bindParam(":".$nameid, $id, PDO::PARAM_STR);

        if($stmt->execute()){
            $response = array(
                "comment" => "El registro se elimino sin problemas"
            );
            return $response;
        } else{
            return $link->errorInfo();
        }
    }
}