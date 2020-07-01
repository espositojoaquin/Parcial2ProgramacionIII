<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Materia;
use App\Utils\JsonWebToken;
use App\Utils\Funciones;


class MateriasController{
 
    public function add(Request $request, Response $response, $args)
    {   
       
        $rta ="";
       
        $materia = $request->getAttribute("materia");
        $rta = json_encode(array("ok" => $materia->save()));

        $response->getBody()->write($rta);

        return $response;
    }
    public function get(Request $request, Response $response, $args)
    {   
       
        $objeto = $request->getAttribute("materias");
        $rta = json_encode(array("ok" => $objeto));
        $response->getBody()->write($rta);

        return $response;
    }
    public function put(Request $request, Response $response, $args)
    {   
         
        $objeto = $request->getAttribute("materias");
        $rta = json_encode(array("ok" => $objeto));
        $response->getBody()->write($rta);

        return $response;
    }

    /*public function login(Request $request, Response $response, $args)
    {   
        
      
        $arrDatos = $request->getParsedBody();
        $objeto = materia::where('email',$arrDatos['email'])
        ->get();
        $retorno = json_encode(array("ok"=>JsonWebToken::obtenerJWT($objeto)));
        
        
         $response->getBody()->write($retorno);

        return $response;
    }*/

}