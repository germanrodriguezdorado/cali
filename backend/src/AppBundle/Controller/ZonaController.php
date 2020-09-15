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





class ZonaController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_bolsamax/admin/zona/listar/")
    */
    public function listar()
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $zonas = $em->getRepository("AppBundle:Zona")->findBy(array("active" => true)); 
        foreach ($zonas as $zona) {
            $una_zona = array();
            $una_zona["id"] = $zona->getId();
            $una_zona["nombre"] = $zona->getNombre();
            $una_zona["cliente"] = $zona->getCliente()->getNombre();
            $respuesta[] = $una_zona;
        }
        return new JsonResponse($respuesta);
    }



   /**
    * @Rest\Get("/api_bolsamax/zona/zonas_de_cliente_por_rut/{rut}")
    */
    public function zonaDeClientePorRut($rut)
    {      
        $em = $this->getDoctrine()->getManager();
        $respuesta = array();
        $cliente = $em->getRepository("AppBundle:Cliente")->findOneBy(array("rut" => $rut, "active" => true));
        if($cliente){
          $zonas = $em->getRepository("AppBundle:Zona")->findBy(array("cliente" => $cliente->getId(), "active" => true));
          foreach ($zonas as $zona) {
            $una_zona = array();
            $una_zona["id"] = $zona->getId();
            $una_zona["nombre"] = $zona->getNombre();
            $respuesta[] = $una_zona;
          }
        }
        return new JsonResponse($respuesta);
    }


   /**
    * @Rest\Get("/api_bolsamax/zona/zonas_de_cliente_por_id/{id}")
    */
    public function zonasDeClientePorId($id)
    {      
        $em = $this->getDoctrine()->getManager();
        $respuesta = array();
        $cliente = $em->getRepository("AppBundle:Cliente")->find($id);
        if($cliente){
          $zonas = $em->getRepository("AppBundle:Zona")->findBy(array("cliente" => $cliente->getId()));
          foreach ($zonas as $zona) {
            $una_zona = array();
            $una_zona["id"] = $zona->getId();
            $una_zona["nombre"] = $zona->getNombre();
            $respuesta[] = $una_zona;
          }
        }
        return new JsonResponse($respuesta);
    }    






}
