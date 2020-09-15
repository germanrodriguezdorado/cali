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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;


use AppBundle\Entity\OrdenTrabajoLog;





class MovilController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_bolsamax/admin/movil/listar")
    */
    public function listar()
    {     


        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);

        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $moviles = $em->getRepository("AppBundle:Usuario")->findBy(array("tipo" => "movil", "active" => true)); 
        foreach ($moviles as $usuario) {
            $un_usuario = array();
            $un_usuario["id"] = $usuario->getId();
            $un_usuario["nombre"] = $usuario->getNombre();
            $un_usuario["matricula"] = $usuario->getMovilMatricula();
            $un_usuario["modelo"] = $usuario->getMovilModelo();
            $un_usuario["operativo"] = $usuario->getOperativo();

            $ots = $this->getDoctrine()->getManager()->getRepository("AppBundle:OrdenTrabajo")->findBy(array("movilAsignado" => $usuario->getId(), "active" => true));    
            $un_usuario["ordenes_trabajo_asignadas"] = count($ots);
  
          
            $respuesta[] = $un_usuario;
        }
        return new JsonResponse($respuesta);
    }



    /**
    * @Rest\Get("/api_bolsamax/admin/movil/listar2")
    */
    public function listar2()
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $moviles = $em->getRepository("AppBundle:Usuario")->findBy(array("tipo" => "movil", "active" => true)); 
        foreach ($moviles as $usuario) {
            $un_usuario = array();
            $un_usuario["id"] = $usuario->getId();
            $un_usuario["nombre"] = $usuario->getNombre();
            $respuesta[] = $un_usuario;
        }
        return new JsonResponse($respuesta);
    }    


  
         
    /**
    * @Rest\Post("/api_bolsamax/admin/movil/crear/")
    */
    public function crear(Request $request)
    {     

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get("fos_user.user_manager");
        $usuario = $userManager->createUser();
        $usuario->setUsername($request->get("usuario"));
        $usuario->setNombre($request->get("nombre"));  
        $usuario->setMovilMatricula($request->get("matricula"));  
        $usuario->setMovilModelo($request->get("modelo"));  
        $usuario->setTipo("movil");     
        $usuario->setPlainPassword($request->get("password"));              
        $usuario->setEnabled(true);                 
        $em->persist($usuario);
        $userManager->updateUser($usuario);
        $em->flush();  
        return new JsonResponse(1);
    }


    /**
    * @Rest\Post("/api_bolsamax/admin/movil/editar/")
    */
    public function editar(Request $request)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($request->get("id"));
        $usuario->setUsername($request->get("usuario"));
        $usuario->setNombre($request->get("nombre"));  
        $usuario->setMovilMatricula($request->get("matricula"));  
        $usuario->setMovilModelo($request->get("modelo"));  
        $em->flush();  
        return new JsonResponse(1);
    }       



    /**
    * @Rest\Get("/api_bolsamax/admin/movil/traer/{id}")
    */
    public function traer($id)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($id);
        $respuesta = array();
        $respuesta["id"] = $usuario->getId();
        $respuesta["nombre"] = $usuario->getNombre();
        $respuesta["usuario"] = $usuario->getUsername();
        $respuesta["matricula"] = $usuario->getMovilMatricula();
        $respuesta["modelo"] = $usuario->getMovilModelo();
        return new JsonResponse($respuesta);
    }


    /**
    * @Rest\Get("/api_bolsamax/admin/movil/traer2/{id}")
    */
    public function traer2($id)
    {     
        if($this->getUser()->getTipo() != "admin" && $this->getUser()->getTipo() != "movil") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($id);
        $respuesta = array();
        $respuesta["id"] = $usuario->getId();
        $respuesta["nombre"] = $usuario->getNombre();
        $respuesta["usuario"] = $usuario->getUsername();
        $respuesta["matricula"] = $usuario->getMovilMatricula();
        $respuesta["modelo"] = $usuario->getMovilModelo();


        $ots = $this->getDoctrine()->getManager()->getRepository("AppBundle:OrdenTrabajo")->findBy(array("movilAsignado" => $id, "active" => true));    
        $ots_array = array();
        foreach ($ots as $ot) {
            $una_ot = array();
            $una_ot["numero"] = $ot->getNumero();
            $una_ot["cliente"] = $ot->getCliente()->getNombre();
            $una_ot["zona"] = $ot->getZona()->getNombre();
            $una_ot["productos_iniciales"] = $em->getRepository("AppBundle:OrdenTrabajo")->productosSolicitadosPretty($ot);
            $una_ot["prioridad"] = $ot->getPrioridad();
            $una_ot["direccion"] = $ot->getDireccionTrabajo();
            $una_ot["direccion_simple"] = $ot->getDireccionSimple();
            $una_ot["bolsones"] = $ot->getBolsones();
            $una_ot["latitud"] = $ot->getLatitud();
            $una_ot["longitud"] = $ot->getLongitud();
            $ots_array[] = $una_ot;
        }
        $respuesta["ordenes_trabajo"] = $ots_array;
  

        return new JsonResponse($respuesta);
    }    






       /**
    * @Rest\Get("/api_bolsamax/admin/movil/eliminar/{id}")
    */
    public function eliminar($id)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($id);
        $usuario->setActive(false);
        $em->flush();
        return new JsonResponse("ok");
    }            











    // Funciones que acceden los moviles:


    /**
    * @Rest\Get("/api_bolsamax/movil/operativo")
    */
    public function operativo()
    {   
        if($this->getUser()->getTipo() != "movil") return new JsonResponse("error", 500);
        return new JsonResponse($this->getUser()->getOperativo());
    }   




    /**
    * @Rest\Get("/api_bolsamax/movil/cambiar_estado_operativo/{estado}")
    */
    public function cambiarEstadoOperativo($estado)
    {   
        if($this->getUser()->getTipo() != "movil") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();


        if($estado == "1"){
            $this->getUser()->setOperativo(true);
        }


        if($estado == "0"){

            $ordenes = $em->getRepository("AppBundle:OrdenTrabajo")->findBy(array("movilAsignado" => $this->getUser()->getId(), "active" => true));

            foreach ($ordenes as $orden) {
               
                // Actualizar orden
                $orden->setMovilAsignado(null);
                $orden->setEstado("Pendiente");
                $orden->setAsignado("BOLSAMAX");

                $log = new OrdenTrabajoLog();
                $log->setOrdenTrabajo($orden);
                $log->setEstado("Pendiente");
                $log->setEmisor("BOLSAMAX");
                $log->setComentarios($this->getUser()->getNombre()." pasa a estado 'No operativo'");
                $log->setFecha(new \DateTime("now"));
                $em->persist($log);
            }

            $this->getUser()->setOperativo(false);
        }

        
        


        $em->flush();


        return new JsonResponse($this->getUser()->getOperativo());
    }      








}
