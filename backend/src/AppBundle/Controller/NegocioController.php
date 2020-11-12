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
use AppBundle\Entity\Bloqueo;
use AppBundle\Entity\Agenda;




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
        $respuesta["email"] = $negocio->getEmail();
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


        // Chequeo nombre
        // Chequeo email

        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        $email_anterior = $negocio->getEmail();
        $negocio->setNombre($request->get("nombre"));
        $negocio->setEmail($request->get("email"));
        $negocio->setSlug($this->get("string_functions")->slugify($request->get("nombre")));
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

        $this->getUser()->setUsername($request->get("email"));
        $this->getUser()->setUsernameCanonical($request->get("email"));
        $this->getUser()->setEmail($request->get("email")); 

        $em->flush();


        // Si el mail cambia se debe renovar el JWT
        $respuesta = array();
        $respuesta["new_jwt"] = "0";
        if($email_anterior != $request->get("email")) {
            $respuesta["new_jwt"] = $this->get("jwt")->getToken($this->getUser());
        }
        

        
        return new JsonResponse($respuesta);
    }    




    /**
    * @Rest\Post("/api_hc/negocio/agenda")
    */
    public function agenda(Request $request)
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        
        $q = $em->createQuery("SELECT a FROM AppBundle:Agenda a WHERE a.negocio = ".$negocio->getId()." AND a.confirmationToken IS null AND a.fecha = :fecha AND a.procesado = 0 ORDER BY REPLACE(a.horario, ':', '') ASC");
        $res = $q->setParameter("fecha", $request->get("fecha"))->getResult();

        $horarios_string = array();
        foreach ($res as $agenda) {
            $una_agenda = array();
            $una_agenda["id"] = $agenda->getId();
            $una_agenda["cliente"] = $agenda->getClienteMail();
            $una_agenda["horario"] = $agenda->getHorario();
            $una_agenda["horario_string"] = str_replace(":","",$agenda->getHorario());
            $una_agenda["estado"] = "ocupado";
            $respuesta[] = $una_agenda;
            $horarios_string[] = str_replace(":","",$agenda->getHorario());
        }


        // Horarios disponibles:
        if($request->get("mostrar_horarios_libres") == "true"){
            $horarios = $this->get("servicio_horarios")->horariosDisponibles($negocio, $request->get("fecha"));
            foreach ($horarios as $horario) {
                if (!in_array(str_replace(":","",$horario), $horarios_string)) {
                    $una_agenda = array();
                    $una_agenda["id"] = "";
                    $una_agenda["horario"] = $horario;
                    $una_agenda["horario_string"] = str_replace(":","",$horario);
                    $una_agenda["cliente"] = "";
                    $una_agenda["estado"] = "libre";
                    $respuesta[] = $una_agenda;
                }
            }
        }


        usort($respuesta, function ($a, $b) { 
            return $a["horario_string"] - $b["horario_string"]; 
        });

        
        return new JsonResponse($respuesta);
    }  




    /**
    * @Rest\Post("/api_hc/negocio/marcar_atendido")
    */
    public function marcarAtendido(Request $request)
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        $agenda = $em->getRepository("AppBundle:Agenda")->findOneBy(array("id" => $request->get("agenda"), "negocio" => $negocio->getId())); 
        if($agenda){
            $agenda->setProcesado(true);
            $em->flush();
        }
        return new JsonResponse(1);
    }  



    /**
    * @Rest\Post("/api_hc/negocio/marcar_no_concurre")
    */
    public function marcarNoConcurre(Request $request)
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        $agenda = $em->getRepository("AppBundle:Agenda")->findOneBy(array("id" => $request->get("agenda"), "negocio" => $negocio->getId())); 
        if($agenda){
            $agenda->setProcesado(true);
            $agenda->setNoConcurre(true);
        }

        // Bloqueo?
        if($request->get("bloquear") == true){
            if($this->get("functions")->isValidEmail($agenda->getClienteMail())){
                $bloqueo = new Bloqueo();
                $bloqueo->setNegocio($negocio);
                $bloqueo->setEmail($agenda->getClienteMail());
                $em->persist($bloqueo);
            }   
        }




        $em->flush();


        return new JsonResponse(1);
    } 



    /**
    * @Rest\Post("/api_hc/negocio/marcar_cancelar")
    */
    public function marcarCancelar(Request $request)
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        $agenda = $em->getRepository("AppBundle:Agenda")->findOneBy(array("id" => $request->get("agenda"), "negocio" => $negocio->getId())); 
        $fecha = $agenda->getFecha()->format("d/m/Y");
        $horario = $agenda->getHorario();
        $email = $agenda->getClienteMail();
        if($agenda){
            $em->remove($agenda);
            $em->flush();
        }

        if($this->get("functions")->isValidEmail($email)){
            $this->get("email_service")->cancelarAgenda($email, $negocio->getNombre(), $fecha, $horario, $request->get("motivo"));
        }

        return new JsonResponse(1);
    } 



    /**
    * @Rest\Post("/api_hc/negocio/marcar_tomado")
    */
    public function marcarTomado(Request $request)
    {     
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->findOneBy(array("usuario" => $this->getUser()->getId())); 
        
        $agenda = new Agenda();
        $agenda->setNegocio($negocio);
        $agenda->setClienteMail($request->get("cliente"));
        $fecha_ok = \DateTime::createFromFormat("Y-m-d", $request->get("fecha"));
        $agenda->setFecha($fecha_ok);
        $agenda->setHorario($request->get("horario"));
        $em->persist($agenda);
        $em->flush();

        return new JsonResponse(1);
    }     






}
