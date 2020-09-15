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

use AppBundle\Entity\Cliente;
use AppBundle\Entity\Zona;




class ClienteController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_bolsamax/admin/cliente/listar")
    */
    public function listar()
    {     
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $clientes = $em->getRepository("AppBundle:Cliente")->findBy(array("active" => true)); 
        foreach ($clientes as $cliente) {
            $un_cliente = array();
            $un_cliente["id"] = $cliente->getId();
            $un_cliente["nombre"] = $cliente->getNombre();
            $un_cliente["rut"] = $cliente->getRut();
            $zonas = $em->getRepository("AppBundle:Zona")->findBy(array("cliente" => $cliente->getId(), "active" => true));
            $zonas_string = "";
            foreach ($zonas as $zona) {
                 $zonas_string.= $zona->getNombre().", ";
            }

            $zonas_string = substr($zonas_string, 0, -2);

            $un_cliente["zonas_string"] = $zonas_string;
            $respuesta[] = $un_cliente;
        }
        return new JsonResponse($respuesta);
    }



    /**
    * @Rest\Post("/api_bolsamax/admin/cliente/crear/")
    */
    public function crear(Request $request)
    {     
        $em = $this->getDoctrine()->getManager();

        // Compruebo por RUT existente:
        if( $em->getRepository("AppBundle:Cliente")->rutExistente($request->get("rut")) != "0" ){
            return new JsonResponse(2);
        }

        $cliente = new Cliente();
        $cliente->setNombre($request->get("nombre"));
        $cliente->setRut($request->get("rut"));
        $em->persist($cliente);
        $em->flush();


        // Agrego zonas
        $zonas = $request->get("zonas");
        foreach ($zonas as $zona) {
            $zona_obj = new Zona();
            $zona_obj->setNombre($zona["nombre"]);
            $zona_obj->setDireccion($zona["direccion"]);
            $zona_obj->setTelefono($zona["telefono"]);
            $zona_obj->setCliente($cliente);
            $em->persist($zona_obj);
        }

        $em->flush();
        return new JsonResponse(1);
    } 


 



    /**
    * @Rest\Get("/api_bolsamax/admin/cliente/traer/{id}")
    */
    public function traer($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository("AppBundle:Cliente")->find($id);
        $respuesta = array();
        $respuesta["id"] = $cliente->getId();
        $respuesta["nombre"] = $cliente->getNombre();
        $respuesta["rut"] = $cliente->getRut();
        $zonas = array();
        $zonas_obj = $em->getRepository("AppBundle:Zona")->findBy(array("cliente" => $cliente->getId(), "active" => true));
        foreach ($zonas_obj as $zo) {
            $una = array();
            $una["id"] = $zo->getId();
            $una["nombre"] = $zo->getNombre();
            $una["direccion"] = $zo->getDireccion();
            $una["telefono"] = $zo->getTelefono();
            $zonas[] = $una;
        }
        $respuesta["zonas"] = $zonas;
        return new JsonResponse($respuesta);
    }     





   /**
    * @Rest\Post("/api_bolsamax/admin/cliente/editar/")
    */
    public function editar(Request $request)
    {     
        $em = $this->getDoctrine()->getManager();

        // Compruebo por RUT existente:
        $existente = $em->getRepository("AppBundle:Cliente")->rutExistente($request->get("rut"));
        if( $existente != "0" ){
            if( $existente != $request->get("id") ){
                return new JsonResponse(2);
            }
        }

        $cliente = $em->getRepository("AppBundle:Cliente")->find($request->get("id")); 
        $cliente->setNombre($request->get("nombre"));
        $cliente->setRut($request->get("rut"));
        
        $zonas = $request->get("zonas");

        // Actualizo zonas (Son las que tienen id != 0)
        foreach ($zonas as $zona) {
            if($zona["id"] != 0){
                $zona_obj = $em->getRepository("AppBundle:Zona")->find($zona["id"]); 
                $zona_obj->setNombre($zona["nombre"]);
                $zona_obj->setDireccion($zona["direccion"]);
                $zona_obj->setTelefono($zona["telefono"]);
            }
        }


        // Creo zonas nuevas (Son las que tienen id == 0)
        foreach ($zonas as $zona) {
            if($zona["id"] == 0){
                $zona_obj = new Zona();
                $zona_obj->setNombre($zona["nombre"]);
                $zona_obj->setDireccion($zona["direccion"]);
                $zona_obj->setTelefono($zona["telefono"]);
                $zona_obj->setCliente($cliente);
                $em->persist($zona_obj);
            }
        }


        $zonas_a_eliminar = $request->get("zonas_a_eliminar");

        // Elimino las zonas
        foreach ($zonas_a_eliminar as $zona_a_eliminar) {
            $zona_obj = $em->getRepository("AppBundle:Zona")->find($zona_a_eliminar); 
            $zona_obj->setActive(false);
        }


        $em->flush();
        return new JsonResponse(1);
    }   






     /**
    * @Rest\Get("/api_bolsamax/admin/cliente/eliminar/{id}")
    */
    public function eliminar($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository("AppBundle:Cliente")->find($id);
        $cliente->setActive(false);

        // Elimino sus zonas:
        $zonas = $em->getRepository("AppBundle:Zona")->findBy(array("cliente" => $id));
        foreach ($zonas as $zona) {
            $zona->setActive(false);
        }


        $em->flush();
        return new JsonResponse("ok");
    }       




}
