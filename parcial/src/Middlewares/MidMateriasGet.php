<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
//use Psr\Http\Message\ResponseInterface as Response;

use Slim\Psr7\Response;
use App\Utils\Funciones;
use App\Models\Materia;
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
       $dato = Funciones::obtenerDatoGet($request->getUri()->getPath());
       
       if($dato=="materias")
       {
           $materias = Materia::join("users","users.id","=","materias.profesor_id")
           ->all()
           ->select("materias.nombre","materias.vacantes","materias.cuatrimestre","users.nombre","users.email")
           ->get();

           foreach($materias as $item)
           {
                 if($item->vacantes == 0)
                 {
                     $item->nombre = strtoupper($item->nombre);
                     $item->email = strtoupper($item->email);
                     $item->vacantes = strtoupper($item->vacante);
                     $item->cuatrimestre = strtoupper($item->cuatrimestre);

                 }
           }
           $request= $request->withAttribute("materias",$materias);
           $resp = new Response();
           $response = $handler->handle($request);
           $existingContent = (string) $response->getBody();
           $resp->getBody()->write($existingContent);   
           return $resp;
       }
       else
       {
           if(Funciones::validacionTipoToken("1","tipo_id"))
           {
              $materia = Materia::where("id",$dato)->get(); 
              $request= $request->withAttribute("materias",$materia);
              $resp = new Response();
              $response = $handler->handle($request);
              $existingContent = (string) $response->getBody();
              $resp->getBody()->write($existingContent);   
              return $resp;
    
           }
           else
           {   
                 if(Funciones::validacionTipoToken("2","tipo_id")||Funciones::validacionTipoToken("3","tipo_id"))
                 {
                     $retornar = [];
                     //$objeto= Mascota::join("turnos","turnos.id_mascota","=","mascotas.id")
                     $materia = Materia::where("id",$dato)->get();
                     $inscripciones = Materia::join("inscriptos","inscriptos.materia_id","=","materias.id")
                     ->join("users","inscriptos.alumno_id","=","users.id")
                     //->where("users.id","=","inscriptos.alumno_id")
                     ->where("materias.id","=",$dato)
                     ->select("users.email,users.email,users.legajo")->get();
                     if($materia!= "[]")
                     {   
                         if($inscripciones!="[]")
                         {
                             $final = new stdClass();
                             $final->materia = $materia;
                             $final->inscriptos= $inscripciones; 
                             $request=  $request->withAttribute("materias",$final);
                             $resp = new Response();
                             $response = $handler->handle($request);
                             $existingContent = (string) $response->getBody();
                             $resp->getBody()->write($existingContent);   
                             return $resp;
    
                         }
                         else
                         {
                            $resp = new Response();
                            $resp->getBody()->write(json_encode(array("Error" =>"No hay inscriptos en esta materia")));
                            return $resp;
                         }
    
    
                     }
                     else
                     {
                        $resp = new Response();
                        $resp->getBody()->write(json_encode(array("Error" =>"No Existe la materia")));
                        return $resp;
                     }
                   
                 }
              
           }

       }
    
        
    }
}
