<?php

namespace AppBundle\Resources;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

// Uso:
// $this->get("images_functions")->functionNombre(variable);


class ImagesFunctions extends Controller
{

    private $em;

    public function __construct()
    {
        
    }


   
  function validarLote($lote){
        
        $respuesta = true;
        foreach ($lote as $una_imagen) {
            if($una_imagen["base64"] != ""){
                $respuesta = $this->validar($una_imagen["base64"]);
            }
            if(!$respuesta) return false;
        }
        return $respuesta;
    }



     function validar($data){

        $respuesta = true;
        $size = $this->getBase64ImageSize($data);
        if($size > 10){
            $respuesta = false;
        }


        
        //$data = 'data:image/png;base64,AAAFBfj42Pj4';    
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        if (!in_array($type, [ 'data:image/jpg', 'data:image/jpeg', 'data:image/png' ])) {
            $respuesta = false;
        }



        $data = base64_decode($data);

        if ($data === false) {
            $respuesta = false;
        }

        return $respuesta;
    }




     function getBase64ImageSize($base64Image){ //return memory size in B, KB, MB
    
        $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
        $size_in_kb    = $size_in_bytes / 1024;
        $size_in_mb    = $size_in_kb / 1024;

        return $size_in_mb;
    
    }


    function subirLote($lote, $carpeta){
        
        $respuesta = array();
        foreach ($lote as $una_imagen) {
            if($una_imagen["base64"] != ""){
                $respuesta[] = $this->subir($una_imagen["base64"], $carpeta);
            }
        }
        return $respuesta;
    }



     function subir($base64, $carpeta){

        $array = explode(",", $base64);
        $tipo_archivo = $this->darTipoArchivo($base64);
        $nombre_archivo = date("His").substr((string)microtime(), 2, 8).".".$tipo_archivo;  
        $filepath_temp = $_SERVER["DOCUMENT_ROOT"]."/".$carpeta."/".$nombre_archivo; 
        file_put_contents($filepath_temp, base64_decode($array[1]));
        return $nombre_archivo;

    }


    function darTipoArchivo($base64){
        //$data = 'data:image/png;base64,AAAFBfj42Pj4'; 
        $data = explode(";", $base64);
        $test = $data[0];
        $respuesta = "";
        if($test == "data:image/png") $respuesta = "png";
        if($test == "data:image/jpeg") $respuesta = "jpeg";
        if($test == "data:image/jpg") $respuesta = "jpg";
        return $respuesta;   
    }




    


   
}
