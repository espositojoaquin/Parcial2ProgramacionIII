<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\User;
use App\Utils\Funciones;
use App\Utils\Archivos;
use stdClass;
use  Imagick;




class UserBefore
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
        
        
        $user = new User();
        $user->email = $arrDatos['email']??"0";
        $user->tipo_id = $arrDatos['tipo']??"0";
        $user->clave = $arrDatos['clave']??"0";
        $user->nombre = $arrDatos['nombre']??"0";
        $user->legajo = $arrDatos['legajo']??"0";

       
        if ($user->email != "0" && $user->tipo_id != "0" && $user->clave != "0" && $user->nombre != "0" && $user->legajo) {
           
            if(Funciones::datosEspecificos3(strtolower($user->tipo_id),"1","2","3"))
            {   
                    if($user->legajo >999 && $user->legajo<2001)
                    {
                        $objeto = User::where('email',$arrDatos['email'])->get();
                        $leg = User::where('legajo',$arrDatos['legajo'])->get();
                        if($objeto == "[]")
                        {    
                            if($leg == "[]")
                            {
                                $user->clave = password_hash($arrDatos['clave'],PASSWORD_DEFAULT);
                                $request = $request->withAttribute("user",$user);
                                $resp = new Response();
                                $response = $handler->handle($request);
                                $existingContent = (string) $response->getBody();
                                $resp->getBody()->write($existingContent);
                                return $resp;
        
                            }
                            else
                            {
                                $resp = new Response();
                                $resp->getBody()->write(json_encode(array("Error" => "Legajo Existente")));
                                return $resp;
                            }

                    }
                    else
                    {
                        $resp = new Response();
                        $resp->getBody()->write(json_encode(array("Error" => "Email Existente")));
                        
                        return $resp;
                    }
                }
                else
                { 
                    $resp = new Response();
                   
                    $resp->getBody()->write(json_encode(array("Error" => "Se puede ingresar un legajo entre 1000 y 2000")));

                    return $resp;
                }

            }
            else
            {
                $resp = new Response();
                $resp->getBody()->write(json_encode(array("Error" => "Solo es valido el tipo 1, 2 o 3")));
                return $resp;
            }
        } else {
            $resp = new Response();
            $resp->getBody()->write(json_encode(array("Error" => "Datos insuficientes")));
            return $resp;
        }
        
      
    }
}