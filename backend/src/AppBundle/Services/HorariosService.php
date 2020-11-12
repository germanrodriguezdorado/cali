<?php

namespace AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Negocio;
use AppBundle\Entity\Usuario;


// Uso: $this->get("servicio_horarios")->functionNombre(variable);


class HorariosService extends Controller
{


	private $funciones_fechas;
	private $em;


	public function __construct(\AppBundle\Resources\FechasFunctions $funciones_fechas, EntityManagerInterface $em)  {
    	$this->funciones_fechas = $funciones_fechas;
    	$this->em = $em;
	}



	public function horariosDisponibles(Negocio $negocio, $fecha){

		$horarios = array();
		$date = \DateTime::createFromFormat("Y-m-d", $fecha);

		
		

		// Controlo si el negocio trabaja en esta fecha
		$dia = $this->funciones_fechas->deDiaDeLaSemanaPhpaDiaDelaSemanaHumano($date->format("w"));
		if( !$negocio->{"get".ucfirst($dia)}() ) return $horarios;


		//Controlo descansos:
		
		if($negocio->getDescanso() == "Sin descanso"){
			$horarios = $this->franjasDeHorarios($fecha." ".$negocio->getDesde(), $fecha." ".$negocio->getHasta(), $negocio->getDuracion());
		}


		if($negocio->getDescanso() == "De 12 a 13"){
			$h1 = $this->franjasDeHorarios($fecha." ".$negocio->getDesde(), $fecha." 12:00", $negocio->getDuracion());
			$h2 = $this->franjasDeHorarios($fecha." 13:00", $fecha." ".$negocio->getHasta(), $negocio->getDuracion());
			$horarios = array_merge($h1,$h2);
		}


		if($negocio->getDescanso() == "De 13 a 14"){
			$h1 = $this->franjasDeHorarios($fecha." ".$negocio->getDesde(), $fecha." 13:00", $negocio->getDuracion());
			$h2 = $this->franjasDeHorarios($fecha." 14:00", $fecha." ".$negocio->getHasta(), $negocio->getDuracion());
			$horarios = array_merge($h1,$h2);
		}


		// Controlo que no hay agendas activas
		$horarios2 = array();
		foreach ($horarios as $clave => $horario) {
			$test = $this->em->getRepository("AppBundle:Agenda")->findOneBy(array(
				"negocio" => $negocio->getId(),
				"fecha" => $date,
				"horario" => $horario,
				"procesado" => false,
				"noConcurre" => false
			));

			if(!$test) $horarios2[] = $horario;
		}

		

	    return $horarios2;

	}


	public function horariosEstaDisponible(Negocio $negocio, $fecha, $horario){
		$test = $this->horariosDisponibles($negocio, $fecha);
		return in_array($horario, $test);
	}


	private function franjasDeHorarios($desde, $hasta, $duracion){

		$horarios = array();
		$StartTime = strtotime($desde);
    	$EndTime = strtotime($hasta);
    	$AddMins  = $duracion * 60;

	    while ($StartTime < $EndTime){
	        $horarios[] = date("G:i", $StartTime);
	        $StartTime += $AddMins;
	    }

	    return $horarios;

	}



	public function tieneAgendaSinProcesar($email, Negocio $negocio){
		$test = $this->em->getRepository("AppBundle:Agenda")->findOneBy(array(
			"negocio" => $negocio->getId(),
			"clienteMail" => $email,
			"procesado" => false
		));


		return $test != null;
	}









  
}
