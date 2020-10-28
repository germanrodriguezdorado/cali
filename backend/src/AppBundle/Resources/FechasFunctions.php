<?php

namespace AppBundle\Resources;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

// Uso:
// $this->get("fechas_functions")->functionNombre(variable);


class FechasFunctions extends Controller
{

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get("doctrine")->getManager();
    }


   



    // Recibe: $dia_de_la_semana_php
    // 0 
    // 1
    // 2
    // 3
    // 4
    // 5
    // 6
    // Retorna string:
    // domingo
    // lunes
    // martes
    // miercoles
    // jueves
    // viernes
    // sabado

    function deDiaDeLaSemanaPhpaDiaDelaSemanaHumano($dia_de_la_semana_php) {
      $respuesta = "";
      if($dia_de_la_semana_php == 0) $respuesta = "domingo";
      if($dia_de_la_semana_php == 1) $respuesta = "lunes";
      if($dia_de_la_semana_php == 2) $respuesta = "martes";
      if($dia_de_la_semana_php == 3) $respuesta = "miercoles";
      if($dia_de_la_semana_php == 4) $respuesta = "jueves";
      if($dia_de_la_semana_php == 5) $respuesta = "viernes";
      if($dia_de_la_semana_php == 6) $respuesta = "sabado";
      return $respuesta;
    }



    


   
}
