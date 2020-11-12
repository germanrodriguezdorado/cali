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



class AdminController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_hc/admin/dar_negocios")
    */
    public function darNegocios()
    {     

        $respuesta = array();
        if($this->getUser()->getTipo() != "2") return new JsonResponse($respuesta);
        $em = $this->getDoctrine()->getManager();
        $negocios = $em->getRepository("AppBundle:Negocio")->findAll(); 

        foreach ($negocios as $negocio) {
            $un_negocio = array();
            $un_negocio["id"] = $negocio->getId();
            $un_negocio["nombre"] = $negocio->getNombre();
            $un_negocio["email"] = $negocio->getEmail();
            $un_negocio["direccion"] = $negocio->getDireccion();
            $un_negocio["barrio"] = $negocio->getBarrio();
            $un_negocio["telefono"] = $negocio->getTelefono();
            $un_negocio["duracion"] = $negocio->getDuracion();
            $un_negocio["lunes"] = $negocio->getLunes();
            $un_negocio["martes"] = $negocio->getMartes();
            $un_negocio["miercoles"] = $negocio->getMiercoles();
            $un_negocio["jueves"] = $negocio->getJueves();
            $un_negocio["viernes"] = $negocio->getViernes();
            $un_negocio["sabado"] = $negocio->getSabado();
            $un_negocio["domingo"] = $negocio->getDomingo();
            $un_negocio["desde"] = $negocio->getDesde();
            $un_negocio["hasta"] = $negocio->getHasta();
            $un_negocio["descanso"] = $negocio->getDescanso();
            $respuesta[] = $un_negocio;
        }
        
        
        return new JsonResponse($respuesta);
    }





    /**
    * @Rest\Post("/api_hc/admin/eliminar_negocio/")
    */
    public function eliminarNegocio(Request $request)
    {     

        if($this->getUser()->getTipo() != "2") return new JsonResponse($respuesta);
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository("AppBundle:Negocio")->find($request->get("negocio_id")); 
        
        // Elimino sus citas:
        $citas = $em->getRepository("AppBundle:Agenda")->findBy(array("negocio" => $negocio->getId()));
        foreach ($citas as $cita) {
            $em->remove($cita);
        }

        // Elimino el negocio:
        $usuario_id = $negocio->getUsuario()->getId(); // Antes me quedo con el id de usuario
        $em->remove($negocio);

        // Elimino el usuario
        $usuario = $em->getRepository("AppBundle:Usuario")->find($usuario_id);
        $em->remove($usuario);

        $em->flush();

        return new JsonResponse(1);

    }    







}
