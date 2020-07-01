<?php
namespace App\Utils;
class Archivos{
    public static function existePeticionPOST(){
        return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
    }

    public static function existePeticionGET(){
        return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
    }

    //json 
    public static function guardarUno( $path, $objeto ){
        $archivo = fopen( $path, 'a+' );
        fwrite( $archivo, json_encode($objeto).PHP_EOL);
        fclose( $archivo );
    }

  
    public static function guardarUnoSerializado( $path, $objeto ){
        $array = array();
        if(file_exists($path))
        {
            if(filesize($path) > 0)
            {
                $array = Archivos::retornarDatosSerializados($path);
                
            }

        }
        array_push($array,$objeto);
        Archivos::guardarTodosSerializado($path,$array);
    }
     
    //json
    public static function guardarTodos( $path, $lista ){
        $archivo = fopen( $path, 'w' );
        foreach( $lista as $objeto ){
            fwrite( $archivo, json_encode($objeto).PHP_EOL );
        }
        fclose( $archivo );
    } 

    //Serializado 
    public static function guardarTodosSerializado( $path, $lista ){
        $archivo = fopen( $path, 'w' );
       
         fwrite( $archivo, serialize($lista) );
        
        fclose( $archivo );
    } 
    //json
    public static function retornarDatos($path){
        $lista = array();
        $archivo = fopen($path, 'r' );
        while (!feof($archivo)){
            $linea = fgets($archivo);
            if($linea!='')
            array_push($lista,json_decode($linea));
        }
        fclose( $archivo );
        return $lista;
    } 
 
    public static function retornarDatosSerializados($path){
        
         $archivo = fopen( $path, 'r' );
         $rta = fread($archivo, filesize($path));
         $ret = unserialize($rta);
        fclose($archivo);
        return $ret;

    }


    //Manejo de imagenes 
    //$img: nombreDesdePostman
    //$path: ubicacion donde guardar la imagen
    //$id: identificacion con usuario.
    //$objeto: el objeto.
    //$nomCamp: nombre del campo de la imagen.
    public static function GuardarImagen($img,$path,$id,$objeto,$nomCamp){
        $origen=$_FILES[$img]["tmp_name"];
        $exten= pathinfo($_FILES[$img]["name"],PATHINFO_EXTENSION);
        $destino=$path.$id.'.'.date("dmy_His").".".$nomCamp.".".$exten;
        move_uploaded_file($origen,$destino);
        $objeto->$nomCamp = $destino;
        } 
        
        // Devuelve la extencion de la imagen
        public static function devovlerExten($img)
        {
          $aux = explode(".",$img);
          return $aux[4];
        }
        //Eliminar y mandar al Backup
    //Manejo de imagenes 
   
    //$path: ubicacion donde guardar la imagen
    //$id: identificacion con usuario.
    //$objeto: el objeto.
    //$nomCamp: nombre del campo de la imagen.
    public static function EliminarImage($path,$objeto,$nomCamp,$id){

        $oriAntigua = $objeto->$nomCamp;
        $exten= Archivos::devovlerExten($oriAntigua);
        $ret = false;
        if(file_exists($oriAntigua)){
           $ret = copy($oriAntigua,$path.$id.'.'.date("dmy_His").".".$nomCamp.".".$exten);
        }
        if($ret == true)
        {
            unlink($oriAntigua);
            
        } 
       return $ret;
    } 
        //modificacion de foto
    //Manejo de imagenes 
    //$img: nombreDesdePostman
    //$path: ubicacion donde guardar la imagen
    // $pathBac el path del backup
    //$id: identificacion con usuario.
    //$objeto: el objeto.
    //$nomCamp: nombre del campo de la imagen.
        public static function ModificarImage($img,$path,$pathBac,$id,$objeto,$nomCamp){
          
            if(Archivos::EliminarImage($pathBac,$objeto,$nomCamp,$id)){
                Archivos::GuardarImagen($img,$path,$id,$objeto,$nomCamp);
            }

        }
        
}
?>