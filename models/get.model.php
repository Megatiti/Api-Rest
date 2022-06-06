<?php

require_once "connection.php";

class GetModel{

    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){
        $selectArray = explode(",", $select);
        //validar existencia de la tabla
        if(empty(Connection::getColumnsData($table, $selectArray))){
            return null;
        }
        /*=============================
        Sin ordernar  ni filtrar
        ================================*/
        $sql = "SELECT $select FROM $table";
        /*=============================
        Ordernar 
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
        }
        /*=============================
        Ordernar y Limitar
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }
        /*=============================
        Limitar 
        ================================*/
        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt";
        }
        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getDataFilter($table, $select, $linkto, $equalto, $orderBy, $orderMode, $startAt, $endAt){

        //validar existencia de la tabla
        if(empty(Connection::getColumnsData($table))){
            return null;
        }

        $linktoArray = explode(",", $linkto);
        $equaltoArray = explode("_", $equalto);
        $linktoText = "";

        if(count($linktoArray)>1){
            foreach ($linktoArray as $key => $value) {
                if($key>0){
                    $linktoText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }
        
        //enlazamos parametros linkto con equalto
        /*=============================
        Sin ordernar ni filtrar
        ================================*/
        $sql = "SELECT $select FROM $table WHERE $linktoArray[0] = :$linktoArray[0] $linktoText";
        /*=============================
        Ordernar 
        ================================*/
        if($orderBy != null && $orderMode != null){
            $sql = "SELECT $select FROM $table WHERE $linktoArray[0] = :$linktoArray[0] $linktoText ORDER BY $orderBy $orderMode";
        }
        /*=============================
        Ordernar y Limitar
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linktoArray[0] = :$linktoArray[0] $linktoText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }
        /*=============================
        Limitar 
        ================================*/
        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linktoArray[0] = :$linktoArray[0] $linktoText LIMIT $startAt, $endAt";
        }
        $stmt = Connection::connect()->prepare($sql);
        //enlazamos parametros linkto con equalto fin
        foreach ($linktoArray as $key => $value) {
            $stmt->bindParam(":".$value, $equaltoArray[$key], PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){
        
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinText = "";

        if(count($relArray)>1){
            foreach ($relArray as $key => $value) {
                //validar existencia de la tabla
                if(empty(Connection::getColumnsData($value))){
                    return null;
                }
                if($key>0){
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
        
            /*=============================
            Sin ordernar  ni filtrar
            ================================*/
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText";
            /*=============================
            Ordernar 
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode";
            }
            /*=============================
            Ordernar y Limitar
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            /*=============================
            Limitar 
            ================================*/
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText LIMIT $startAt, $endAt";
            }
            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);

        }else{
            return null;
        }
    }

    static public function getRelDataFilter($rel, $type, $select, $linkto, $equalto, $orderBy, $orderMode, $startAt, $endAt){
        
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinText = "";

        $linktoArray = explode(",", $linkto);
        $equaltoArray = explode("_", $equalto);
        $linktoText = "";

        if(count($linktoArray)>1){
            foreach ($linktoArray as $key => $value) {
                
                if($key>0){
                    $linktoText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }

        if(count($relArray)>1){
            foreach ($relArray as $key => $value) {
                //validar existencia de la tabla
                if(empty(Connection::getColumnsData($value))){
                    return null;
                }
                if($key>0){
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
        
            /*=============================
            Sin ordernar  ni filtrar
            ================================*/
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] = :$linktoArray[0] $linktoText";
            /*=============================
            Ordernar 
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] = :$linktoArray[0] $linktoText ORDER BY $orderBy $orderMode";
            }
            /*=============================
            Ordernar y Limitar
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] = :$linktoArray[0] $linktoText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            /*=============================
            Limitar 
            ================================*/
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] = :$linktoArray[0] $linktoText LIMIT $startAt, $endAt";
            }
            $stmt = Connection::connect()->prepare($sql);

            foreach ($linktoArray as $key => $value) {
                $stmt->bindParam(":".$value, $equaltoArray[$key], PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);

        }else{
            return null;
        }
    }

    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){

        //validar existencia de la tabla
        if(empty(Connection::getColumnsData($table))){
            return null;
        }

        $linktoArray = explode(",", $linkTo);
        $searchArray = explode("_", $search);
        $linktoText = "";

        if(count($linktoArray)>1){
            foreach ($linktoArray as $key => $value) {
                if($key>0){
                    $linktoText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }
        
        /*=============================
        Sin ordernar  ni filtrar
        ================================*/
        $sql = "SELECT $select FROM $table WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText";
        /*=============================
        Ordernar 
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText ORDER BY $orderBy $orderMode";
        }
        /*=============================
        Ordernar y Limitar
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }
        /*=============================
        Limitar 
        ================================*/
        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText LIMIT $startAt, $endAt";
        }
        $stmt = Connection::connect()->prepare($sql);

        //enlazamos parametros linkto con equalto fin
        foreach ($linktoArray as $key => $value) {
            if($key > 0){
                $stmt->bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getRelDataSearch($rel, $type, $select, $linkto, $search, $orderBy, $orderMode, $startAt, $endAt){
        
        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinText = "";

        $linktoArray = explode(",", $linkto);
        $searchArray = explode("_", $search);
        $linktoText = "";

        if(count($linktoArray)>1){
            foreach ($linktoArray as $key => $value) {
                
                if($key>0){
                    $linktoText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }

        if(count($relArray)>1){
            foreach ($relArray as $key => $value) {
                //validar existencia de la tabla
                if(empty(Connection::getColumnsData($value))){
                    return null;
                }
                if($key>0){
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
        
            /*=============================
            Sin ordernar  ni filtrar
            ================================*/
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText";
            /*=============================
            Ordernar 
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText ORDER BY $orderBy $orderMode";
            }
            /*=============================
            Ordernar y Limitar
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            /*=============================
            Limitar 
            ================================*/
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linktoArray[0] LIKE '%$searchArray[0]%' $linktoText LIMIT $startAt, $endAt";
            }
            $stmt = Connection::connect()->prepare($sql);

            //enlazamos parametros linkto con equalto fin
            foreach ($linktoArray as $key => $value) {
                if($key > 0){
                    $stmt->bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);

        }else{
            return null;
        }
    }

    static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
        //validar existencia de la tabla
        if(empty(Connection::getColumnsData($table))){
            return null;
        }
        $filter = "";
        if($filterTo != null && $inTo != null){
            $filter = "AND " . $filterTo . " IN (" . $inTo . ")";
        }
        
        /*=============================
        Sin ordernar  ni filtrar
        ================================*/
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";
        /*=============================
        Ordernar 
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
        }
        /*=============================
        Ordernar y Limitar
        ================================*/
        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }
        /*=============================
        Limitar 
        ================================*/
        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt, $endAt";
        }
        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
        
        $filter = "";
        if($filterTo != null && $inTo != null){
            $filter = "AND " . $filterTo . " IN (" . $inTo . ")";
        }

        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);
        $innerJoinText = "";

        if(count($relArray)>1){
            foreach ($relArray as $key => $value) {
                //validar existencia de la tabla
                if(empty(Connection::getColumnsData($value))){
                    return null;
                }
                if($key>0){
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
            /*=============================
            Sin ordernar  ni filtrar
            ================================*/
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";
            /*=============================
            Ordernar 
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
            }
            /*=============================
            Ordernar y Limitar
            ================================*/
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
            /*=============================
            Limitar 
            ================================*/
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt, $endAt";
            }
            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        }else{
            return null;
        }
    }
}