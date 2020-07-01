<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use App\Utils\JsonWebToken;
use App\Utils\Funciones;


class UsersController{
 
    public function add(Request $request, Response $response, $args)
    {   
       
        $rta ="";
        $user = $request->getAttribute("user");
     
        $rta = json_encode(array("ok" => $user->save()));

        $response->getBody()->write($rta);

        return $response;
    }
    public function login(Request $request, Response $response, $args)
    {   
        
      
        $arrDatos = $request->getParsedBody();
        $objeto = User::where('email',$arrDatos['email'])
        ->get();
        $retorno = json_encode(array("ok"=>JsonWebToken::obtenerJWT($objeto)));
        
        
         $response->getBody()->write($retorno);

        return $response;
    }

}