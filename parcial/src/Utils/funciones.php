<?php
namespace App\Utils;
class Funciones{
    
    public static function DatosCompletos($objeto,$op){
     
        // $vars_clase = get_class_vars(get_class($objeto));
        // $aux = array_keys($vars_clase);
        $aux = array_keys($objeto);
      
        $sinDatos = "Falta completar: ";
        $flag = true;   
        foreach ($aux as $item) {
            
            if($objeto[$item] =="")
            {
                $sinDatos.= $item.",";
              
              $flag = false;
            }
        }
         if($op == 1)
         {   
             if($flag === true)
             {
                 $sinDatos ="1";
             }
             return $sinDatos;

         }
         else
         {
             return $flag;

         }

    }
    public static function TablaHtml($lista){
        $datos = "<!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <title>Vehiculos</title>
                    </head>
                    <style>
                        table{
                            width: 100%;
                            border-collapse: collapse; /*sin bordes entre los elementos internos*/
                        }
                    
                        thead{
                            font-size: 18px;
                            font-weight: bold;
                        }
                        th, td{
                            text-align: center;
                            padding: 10px;
                        }
                    
                        tr:nth-child(even){
                            background-color: #f2f2f2;
                        }
                    
                        th{
                            background:#252932;
                            color: #fff;
                            font: bold;
                        }
                    
                        img{
                            height: 80px;
                            width: 80px;
                            border-radius: 100%;
                        }
                    </style>
                    <body>
                        <table>
                            <thead>
                                <tr>
                                    <td>Id</td>
                                    <td>Mensaje</td>
                                    <td>Id_usuario</td>
                                    <td>Foto</td>
                                </tr>
                            </thead>
                            <tbody>";

       
        foreach($lista as $item){
            $datos .= "<tr>
                            <td>" .$item->id. "</td>
                            <td>" .$item->mensaje. "</td>
                            <td>" .$item->id_usuario. "</td>
                            <td><img src='." .$item->foto. "'/></td>
                    </tr>";
        }   
        $datos .= " </tbody>
                </table>
            </body>
            </html>";

        echo $datos;

    }
    public static function obtenerDatoGet($path)
    {
       $lista = explode("/",$path);
       $tam = count($lista);
       return $lista[$tam-1];
    }

    public static function obtenerDatoDelete($path)
    {
       $lista = explode("/",$path);
       $tam = count($lista);
       return $lista[$tam-2];
    }

    public static function validarHora($hora,$lh1,$lh2)
    { 
        $lista = explode(":",$hora);
       
        $retorno = false;
        if($lista!=[] && $lista != false && count($lista)>1)
        {
            if($lista[0]>=$lh1 && $lista[0]<=$lh2)
            {  
                if($lista[0]==$lh2)
                {
                    if($lista[1]== 0)
                    {
                       $retorno = true;
                    }
                    else
                    {
                       $retorno = "Los horarios para solicitar los turnos son desde: ".$lh1." hs"." hasta las ".$lh2." hs";
                    }
                }
                else
                {
                    if($lista[1]== 0 || $lista[1]== 30)
                    {
                       $retorno = true;
                    }
                    else
                    {
                        $retorno = "Los turnos son en horarios en punto o medias horas, ya que estos duran 30 min";
                    }

                }
            }
            else
            {
                $retorno = "Los horarios para solicitar los turnos son desde: ".$lh1." hs"." hasta las ".$lh2." hs";
            }
        }
        else
        {
            $retorno = "El formato de hora es HH:MM";
        }
       return $retorno;
    }
    public static function DatosCompletosOb($objeto){
     
        $vars_clase = get_class_vars(get_class($objeto));
        $aux = array_keys($vars_clase);
        $flag = true;   
      echo  json_encode($aux);
        foreach ($aux as $item) {
            //echo($objeto->$item.PHP_EOL);
            if($objeto->$item == "0")
            {
              $flag = false;
            }
        }
             return $flag;
    }

    public static function Filter($lista,$nombreDato,$dato)
    {
        $arrayResponse = array();

        foreach ($lista as $item) {
            if(strcasecmp($item->$nombreDato,$dato) == 0)
            {
                array_push($arrayResponse,$item);
            }
        } 

        return $arrayResponse;
    } 

    public static function FilterUno($lista,$nombreDato,$dato)
    {
        $response = false;

        foreach ($lista as $item) {

            if(strcasecmp($item->$nombreDato,$dato) == 0)
            {
                $response = $item;
            }
        } 

       
        return $response;
    } 
    public static function FilterDos($lista,$nombreDato,$dato,$nombreDato2,$dato2)
    {
        $response = false;

        foreach ($lista as $item) {
         
            if(strcasecmp($item->$nombreDato,$dato) == 0 && strcasecmp($item->$nombreDato2,$dato2) == 0)
            {
                $response = $item;
            }
        } 

       
        return $response;
    } 




    public static function Existe($lista,$nombreDato,$dato)
    {
        $retorno = false;
        foreach ($lista as $item) {
            if(strcasecmp($item->$nombreDato,$dato) == 0)
            {
               $retorno = true;
            }
        } 

        return $retorno;
    } 

    public static function generateID($path)
    {   
        $retorno = 1;
        if(file_exists($path) && filesize($path) > 0) {

            $objeto = Archivos::retornarDatosSerializados($path);

            $retorno = $objeto[count($objeto)-1]->id +1;

        }

        return $retorno;
    }

    public static function generateIDJson($path)
    {   
        $retorno = 1;
        if(file_exists($path) && filesize($path) > 0) {

            $objeto = Archivos::retornarDatos($path);

            $retorno = $objeto[count($objeto)-1]->id +1;

        }

        return $retorno;
    }

    // Se fija si hay una cantidad solicitada de X y descuenta la cantidad;
    // Lista de objetos
    // $id indenficador del obejto
    // $nombreDato, es el nombre del campo a descontar
    // $cantidad es el numero a descontar.
    // return true en caso de haber podido descontar, false en caso de no

    public static function Descontar($lista,$id,$nombreId,$nombreDato,$cantidad,$path)
    {  
        $retorno = false;
           foreach($lista as $item ){
               if($item->$nombreId == $id)
               {
                   if($item->$nombreDato >= $cantidad)
                   {
                       $item->$nombreDato -= $cantidad;
                       $retorno = true;
                       break;
                   }
               }

           }

           Archivos::guardarTodos($path,$lista);

           return $retorno;

    }



    public static function datoEspecifico($dato,$nombreDato){

        if($nombreDato == $dato)
        {
            return true;
        }
        else
        {
            return false;
        }

    } 
    public static function datosEspecificos2($dato,$opcion1,$opcion2)
    {
        if(strcasecmp($dato,$opcion1)==0 || strcasecmp($dato,$opcion2)==0) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function datosEspecificos3($dato,$opcion1,$opcion2,$opcion3)
    {
        if(strcasecmp($dato,$opcion1)==0 || strcasecmp($dato,$opcion2)==0 || strcasecmp($dato,$opcion3)==0) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function montoTotal($nombreDato,$lista)
    {
       $retorno = 0; 

       foreach($lista as $item)
       {
            $retorno += $item->$nombreDato;
       }
       return $retorno;
    }

    public static function Modificar($id,$nombreId,$dato,$nombreDato,$path)
    { 
        $lista = Archivos::retornarDatos($path);
        foreach($lista as $item)
        {
            if($item->$nombreId == $id)
            {
                $item->$nombreDato = $dato;
            }
        } 

        Archivos::guardarTodos($path,$lista);

    }
    public static function validacionToken()
    { 
          
        $retorno = false;
        $objeto = JsonWebToken::ValidarToken();        
        if($objeto!=false)
        {   
            
                $retorno = true;

        }
       

        return $retorno;

    }

    public static function validacionTipoToken($nombre,$nomCamp)
    {
               $objeto = JsonWebToken::ValidarToken();
               $retorno = false;
               if($objeto !=false)
               {
                   $aux = $objeto->data;
                   $data = $aux[0]->$nomCamp;
    
                   if($data == $nombre)
                   {
                       $retorno = true;
                   }

               }
               return $retorno;
            
    }

    





}
?>