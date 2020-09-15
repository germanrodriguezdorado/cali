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




class DashboardController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_bolsamax/admin/info_dashboard")
    */
    public function infoDashboard()
    {     

        $em = $this->getDoctrine()->getManager();
        $ordenes_trabajo_pendientes = $em->getRepository("AppBundle:OrdenTrabajo")->findBy(array("estado" => "Pendiente", "active" => true)); 
        $ordenes_trabajo_en_curso = $em->getRepository("AppBundle:OrdenTrabajo")->findBy(array("estado" => "En curso", "active" => true)); 
        $usuarios_pendientes = $em->getRepository("AppBundle:Usuario")->findBy(array("enabled" => false, "lastLogin" => null, "active" => true)); 
        
        $pendiente_respuesta = false;
        $ot_pendiente_respuesta = $em->getRepository("AppBundle:OrdenTrabajo")->findOneBy(array("estado" => "Pendiente", "asignado" => "BOLSAMAX", "active" => true));
        if($ot_pendiente_respuesta){
            $pendiente_respuesta = true;
        }


        $respuesta["ordenes_trabajo_activas"] = count($ordenes_trabajo_pendientes) + count($ordenes_trabajo_en_curso);
        $respuesta["usuarios_pendientes"] = count($usuarios_pendientes);
        $respuesta["pendiente_respuesta"] = $pendiente_respuesta;
        return new JsonResponse($respuesta);
    }




}
