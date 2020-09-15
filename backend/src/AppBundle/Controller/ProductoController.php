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





class ProductoController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_bolsamax/admin/producto/listar/")
    */
    public function listar()
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $productos = $em->getRepository("AppBundle:Producto")->findBy(array("active" => true)); 
        foreach ($productos as $producto) {
            $un_producto = array();
            $un_producto["id"] = $producto->getId();
            $un_producto["nombre"] = $producto->getNombre();
            $respuesta[] = $un_producto;
        }
        return new JsonResponse($respuesta);
    }






}
