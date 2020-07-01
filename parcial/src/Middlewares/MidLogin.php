<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\User;
use App\Utils\Funciones;
use stdClass;

class LoginBefore
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
        
        $user = new stdClass();
        $user->email = $arrDatos['email']??"0";
        $user->clave = $arrDatos['clave']??"0";
        if ($user->email != "0" && $user->clave != "0" ) {
           
                $objeto = User::where('email',$arrDatos['email'])
                 ->get();
                if($objeto != "[]")
                {    
                    if(password_verify($user->clave,$objeto[0]->clave))
                    {    
                        $resp = new Response();
                        $response = $handler->handle($request);
                        $existingContent = (string) $response->getBody();
                        $resp->getBody()->write($existingContent);
                        return $resp;
                    }
                    else
                    {
                        $resp = new Response();
                        $resp->getBody()->write(json_encode(array("Error" => "Contraseña invalida")));
                        return $resp;
                    }
                }
                else
                { 
                    $resp = new Response();
                    $resp->getBody()->write(json_encode(array("Error" => "Usuario inexistente")));
                    return $resp;
                }

           
        } else {
            $resp = new Response();
            $resp->getBody()->write(json_encode(array("Error" => "Datos insuficientes")));
            return $resp;
        }
        
      
    }
}