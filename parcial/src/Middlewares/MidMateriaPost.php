<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
//use Psr\Http\Message\ResponseInterface as Response;

use Slim\Psr7\Response;
use App\Utils\Funciones;
use App\Models\Materia;


class MateriasPostMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {      
          $arrDatos = $request->getParsedBody();
          $materia = new Materia();
          $materia->materia = $arrDatos['materia']??"0";
          $materia->cuatrimestre = $arrDatos['cuatrimestre']??"0";
          $materia->vacantes = $arrDatos['vacantes']??"0";
          $materia->profesor_id = $arrDatos['profesor']??"0";
          
          if($materia->materia != "0" && $materia->cuatrimestre != "0" && $materia->vacantes != "0" && $materia->profesor_id ) 
          {
                if(Funciones::validacionTipoToken("3","tipo_id"))
                {   
                   // echo json_encode($materia);
                    $request = $request->withAttribute("materia",$materia);
                    $resp = new Response();
                    $response = $handler->handle($request);
                    $existingContent = (string) $response->getBody();
                    $resp->getBody()->write($existingContent);   
                    return $resp;
                }
                else
                {
                    $resp = new Response();
                    $resp->getBody()->write(json_encode(array("Error" =>"Solo los administradores pueden ingresar una meteria")));
                    return $resp;
                }
          }
          else
          {
            $resp = new Response();
            $resp->getBody()->write(json_encode(array("Error" =>"Datos insuficientes")));
            return $resp;
          }

        
    }
}