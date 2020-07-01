<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
//use Psr\Http\Message\ResponseInterface as Response;

use Slim\Psr7\Response;
use App\Utils\Funciones;


class BeforeMiddleware
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
      /* $dato = Funciones::obtenerDatoGet($request->getUri()->getPath());
       echo $dato;*/
      // echo date("Y-m-d");
        if (Funciones::validacionToken()) {
            $resp = new Response();
            $response = $handler->handle($request);
           
            $existingContent = (string) $response->getBody();
           // echo json_encode($existingContent);
            $resp->getBody()->write($existingContent);   
            return $resp;
        } else {
            $resp = new Response();
            $resp->getBody()->write(json_encode(array("Error" =>"No Autorizado")));
            return $resp;
        }
        
    }
}
