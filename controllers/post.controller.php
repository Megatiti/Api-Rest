<?php

require_once "models/get.model.php";
require_once "models/post.model.php";
require_once "models/put.model.php";
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class PostController{

    //peticion post para crear datos
    static public function postData($table, $data){
        $response = PostModel::postData($table, $data);

        $return = new PostController();
        $return->fncResponse($response);
    }

    //peticion post para registrar usuarios
    static public function postRegister($table, $data, $suffix){
        if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null){
            $crypt = crypt($data["password_".$suffix], '$2a$07$548gkrv4i67qtfv45x2b4h89s$');
            $data["password_".$suffix] = $crypt;

            $response = PostModel::postData($table, $data);

            $return = new PostController();
            $return->fncResponse($response);
        }else{ 
            //registro desde aplicaciones externas
            $response = PostModel::postData($table, $data);

            if(isset($response["comment"]) && $response["comment"] == "El registro se ejecuto sin problemas"){
                //validamos que el usuario exista en la base de datos
                $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix], null, null, null, null);
                
                $token = Connection::jwt($response[0]->{"id_".$suffix}, $response[0]->{"email_".$suffix});
                $jwt = JWT::encode($token, "agrguaoheiagnouineh45645", 'HS256');

                //Actualizar base de datos con el tokem del usuario
                $data = array(
                    "token_".$suffix => $jwt,
                    "token_exp_".$suffix => $token["exp"]
                );
                $update = PutModel::putData($table, $data, $response[0]->{"id_".$suffix}, "id_".$suffix);

                if(isset($update["comment"]) && $update["comment"] == "El registro se actualizo sin problemas"){
                    $response[0]->{"token_".$suffix} = $jwt;
                    $response[0]->{"token_exp_".$suffix} = $token["exp"];

                    $return = new PostController();
                    $return->fncResponse($response, null, $suffix);
                }
            }
        }
    }

    //peticion post para login de usuarios
    static public function postLogin($table, $data, $suffix){
        //validamos que el usuario exista en la base de datos
        $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix], null, null, null, null);

        if(!empty($response)){

            if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null){

                $crypt = crypt($data["password_".$suffix], '$2a$07$548gkrv4i67qtfv45x2b4h89s$');

                if($response[0]->{"password_".$suffix} == $crypt){
                    $token = Connection::jwt($response[0]->{"id_".$suffix}, $response[0]->{"email_".$suffix});
                    $jwt = JWT::encode($token, "agrguaoheiagnouineh45645", 'HS256');

                    //Actualizar base de datos con el tokem del usuario
                    $data = array(
                        "token_".$suffix => $jwt,
                        "token_exp_".$suffix => $token["exp"]
                    );
                    $update = PutModel::putData($table, $data, $response[0]->{"id_".$suffix}, "id_".$suffix);

                    if(isset($update["comment"]) && $update["comment"] == "El registro se actualizo sin problemas"){
                        $response[0]->{"token_".$suffix} = $jwt;
                        $response[0]->{"token_exp_".$suffix} = $token["exp"];

                        $return = new PostController();
                        $return->fncResponse($response, null, $suffix);
                    }
                }else{
                    $response = null;

                    $return = new PostController();
                    $return->fncResponse($response, "Contraseña incorrecta");
                }
            }else{
                //actualizar el token para usuarios logueados desde app externas
                $token = Connection::jwt($response[0]->{"id_".$suffix}, $response[0]->{"email_".$suffix});
                $jwt = JWT::encode($token, "agrguaoheiagnouineh45645", 'HS256');

                //Actualizar base de datos con el tokem del usuario
                $data = array(
                    "token_".$suffix => $jwt,
                    "token_exp_".$suffix => $token["exp"]
                );
                $update = PutModel::putData($table, $data, $response[0]->{"id_".$suffix}, "id_".$suffix);

                if(isset($update["comment"]) && $update["comment"] == "El registro se actualizo sin problemas"){
                    $response[0]->{"token_".$suffix} = $jwt;
                    $response[0]->{"token_exp_".$suffix} = $token["exp"];

                    $return = new PostController();
                    $return->fncResponse($response, null, $suffix);
                }
            }
        }else{
            $response = null;

            $return = new PostController();
            $return->fncResponse($response, "Email no existente");
        }
    }

    //respuestas del controlador
    public function fncResponse($response, $error = null, $suffix = null){
        //quitar contraseña de las respuestas

        if(!empty($response)){
            if(isset($response[0]->{"password_".$suffix})){
                unset($response[0]->{"password_".$suffix});
            }
            $json = array(
                'status' => 202,
                'results' => $response
            );
        }else{
            if($error != null){
                $json = array(
                    'status' => 400,
                    'results' => $error
                );
            }else{
                $json = array(
                    'status' => 404,
                    'results' => "NOT FOUND",
                    "method" => "POST"
                );
            }
        }
        echo json_encode($json, http_response_code($json["status"]));        
    }
}