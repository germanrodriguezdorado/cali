<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use AppBundle\Entity\Negocio;




class NegocioController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_hc/negocio/informacion")
    */
    public function darInformacion()
    {     
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        $respuesta = array();
        $respuesta["nombre"] = $negocio->getNombre();
        $respuesta["direccion"] = $negocio->getDireccion();
        $respuesta["barrio"] = $negocio->getBarrio();
        $respuesta["telefono"] = $negocio->getTelefono();
        $respuesta["duracion"] = $negocio->getDuracion();
        $respuesta["lunes"] = $negocio->getLunes();
        $respuesta["martes"] = $negocio->getMartes();
        $respuesta["miercoles"] = $negocio->getMiercoles();
        $respuesta["jueves"] = $negocio->getJueves();
        $respuesta["viernes"] = $negocio->getViernes();
        $respuesta["sabado"] = $negocio->getSabado();
        $respuesta["domingo"] = $negocio->getDomingo();
        $respuesta["desde"] = $negocio->getDesde();
        $respuesta["hasta"] = $negocio->getHasta();
        $respuesta["descanso"] = $negocio->getDescanso();
        return new JsonResponse($respuesta);
    }




    /**
    * @Rest\Post("/api_hc/negocio/guardar_info/")
    */
    public function guardarInformacion(Request $request)
    {     
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        $negocio->setNombre($request->get("nombre"));
        $negocio->setSlug($this->get("functions")->slugify($request->get("nombre")));
        $negocio->setDireccion($request->get("direccion"));
        $negocio->setBarrio($request->get("barrio"));
        $negocio->setTelefono($request->get("telefono"));
        $negocio->setDuracion($request->get("duracion"));
        $negocio->setLunes($request->get("lunes"));
        $negocio->setMartes($request->get("martes"));
        $negocio->setMiercoles($request->get("miercoles"));
        $negocio->setJueves($request->get("jueves"));
        $negocio->setViernes($request->get("viernes"));
        $negocio->setSabado($request->get("sabado"));
        $negocio->setDomingo($request->get("domingo"));
        $negocio->setDesde($request->get("desde"));
        $negocio->setHasta($request->get("hasta"));
        $negocio->setDescanso($request->get("descanso"));
        $em->flush();
        return new JsonResponse(1);
    }    




    /**
    * @Rest\Post("/api_hc/negocio/agenda")
    */
    public function agenda(Request $request)
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        
        $q = $em->createQuery("SELECT a FROM AppBundle:Agenda a WHERE a.negocio = ".$negocio->getId()." AND a.fecha = :fecha AND a.procesado = 0 ORDER BY STR_TO_DATE(a.horario, '%h:%i') ASC");
        $res = $q->setParameter("fecha", $request->get("fecha"))->getResult();

        foreach ($res as $agenda) {
            $una_agenda = array();
            $una_agenda["id"] = $agenda->getId();
            $una_agenda["fecha"] = $agenda->getFecha()->format("d/m/Y");
            $una_agenda["horario"] = $agenda->getHorario();
            $una_agenda["cliente_mail"] = $agenda->getClienteMail();
            $respuesta[] = $una_agenda;
        }
        
        
        return new JsonResponse($respuesta);
    }    





}
