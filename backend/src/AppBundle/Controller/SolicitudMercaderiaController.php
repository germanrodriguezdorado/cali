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




class SolicitudMercaderiaController extends FOSRestController
{
    


    /**
    * @Rest\Post("/api_bolsamax/solicitud_mercaderia/filtrar/")
    */
    public function filtrar(Request $request)
    {     

        $solicitudes = $this->getDoctrine()->getManager()->getRepository("AppBundle:SolicitudMercaderia")->findAll(); 
        $respuesta = array();
        foreach ($solicitudes as $solicitud){
            $una_respuesta = array();
            $una_respuesta["id"] = $solicitud->getId();
            $una_respuesta["fecha"] = $solicitud->getFechaSolicitado()->format("d/m/Y H:i");
            $una_respuesta["producto"] = $solicitud->getProducto();
            $una_respuesta["cantidad"] = $solicitud->getCantidad();
            $una_respuesta["rut"] = $solicitud->getRut();
            $respuesta[] = $una_respuesta;
        }


        return new JsonResponse($respuesta);
    }




}
