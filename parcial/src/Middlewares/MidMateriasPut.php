<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
//use Psr\Http\Message\ResponseInterface as Response;

use Slim\Psr7\Response;
use App\Utils\Funciones;
use App\Models\Materia;
use App\Models\inscripto;
use stdClass;

class MateriasGetMiddleware
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
       $idMat = Funciones::obtenerDatoDelete($request->getUri()->getPath());
       $idProf = Funciones::obtenerDatoGet($request->getUri()->getPath());
       
       if(Funciones::validacionTipoToken("3","tipo_id"))
       {
          $materia = Materia::where("id",$idMat)->get(); 
          $request= $request->withAttribute("materias",$materia);
          $resp = new Response();
          $response = $handler->handle($request);
          $existingContent = (string) $response->getBody();
          $resp->getBody()->write($existingContent);   
          return $resp;

       }
       else
       {   
           
                    $resp = new Response();
                    $resp->getBody()->write(json_encode(array("Error" =>"Solo admin")));
                    return $resp;
                 
               
             
          
       }
    
        
    }
}