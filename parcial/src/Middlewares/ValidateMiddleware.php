<?php
namespace App\Middleware;

//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\Funciones;

//dump-autoload 
class ValidateMiddleware
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
        
        if($arrDatos != null)
        {
            if (Funciones::DatosCompletos($arrDatos,1)== "1") {
                $resp = new Response();
                $response = $handler->handle($request);
                $existingContent = (string) $response->getBody();
                $resp->getBody()->write($existingContent);
                return $resp;
               
            } else {
            
                $resp = new Response();
                $resp->getBody()->write(json_encode(array("Error" =>Funciones::DatosCompletos($arrDatos,1))));
                return $resp;
            }
        }
        else
        {    
            $resp = new Response();
            $resp->getBody()->write(json_encode(array("Error" =>"No se han ingresado datos")));
            return $resp;
        }


        // $response->getBody()->write('BEFORE ' . $existingContent);

         //->withHeader('Content-Type','aplication/json');
    }
}
