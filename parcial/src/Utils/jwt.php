<?php
namespace App\Utils;
//include '.../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Exception;

class JsonWebToken
{ 
   

  public static function obtenerJWT($objeto)
  { 
    $key = "pro3-parcial";
    $payload = array(
      "iss" => "http://example.org",
      "aud" => "http://example.com",
      "iat" => 1356999524,
      "nbf" => 1357000000,
      "data" => $objeto
  );
   $jwt = JWT::encode($payload, $key);

   return $jwt;

  }

  public static function ValidarToken()
  {  
     $key = "pro3-parcial";
     $headers = getallheaders();
     $token = $headers['token'] ?? "0";
     $retorno="";

     try
     {
        $retorno = JWT::decode($token,$key,array('HS256'));
     }
     catch(Exception $ex)
     { 
         $retorno = false;
     }

     return $retorno; 
  }


 
}

?>