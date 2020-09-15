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





class UsuarioController extends FOSRestController
{
    


    /**
    * @Rest\Get("/api_bolsamax/admin/usuario/listar")
    */
    public function listar()
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository("AppBundle:Usuario")->findBy(array("tipo" => "usuario_cliente", "active" => true)); 
        foreach ($usuarios as $usuario) {
            $un_usuario = array();
            $un_usuario["id"] = $usuario->getId();
            $un_usuario["email"] = $usuario->getEmail();
            $un_usuario["habilitado"] = $usuario->isEnabled();
            $un_usuario["ultimo_login"] = $usuario->getLastLoginPretty();
            $un_usuario["nombre"] = $usuario->getNombrePretty();
            $un_usuario["cliente"] = $usuario->getZona()->getCliente()->getNombre();
            $un_usuario["zona"] = $usuario->getZona()->getNombre();
            $un_usuario["telefono"] = $usuario->getTelefono();
            $un_usuario["direccion"] = $usuario->getDireccion();
            $un_usuario["perfil"] = $usuario->getPerfil();
            $respuesta[] = $un_usuario;
        }
        return new JsonResponse($respuesta);
    }



   /**
    * @Rest\Get("/api_bolsamax/usuario/dar_usuarios_de_zona/{zona_id}")
    */
    public function darUsuariosDeZona($zona_id)
    {     
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository("AppBundle:Usuario")->findBy(array("zona" => $zona_id, "active" => true)); 
        $respuesta = array();
        foreach ($usuarios as $usuario) {
            $una_respuesta = array();
            $una_respuesta["id"] = $usuario->getId();
            $una_respuesta["nombre"] = $usuario->getAlias();
            $respuesta[] = $una_respuesta;
        }

        return new JsonResponse($respuesta);
    }    






    





 /**
    * @Rest\Get("/api_bolsamax/admin/usuario/habilitar_deshabilitar/{usuario_id}")
    */
    public function habilitarDeshabilitar($usuario_id)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($usuario_id); 
        $usuario->isEnabled() ? $usuario->setEnabled(false) : $usuario->setEnabled(true);

        if($usuario->isEnabled() && $usuario->getLastLogin() == null){
            $this->get("email_service")->usuarioHabilitado($usuario);
        }
        
        $em->flush();
        return new JsonResponse($usuario->isEnabled());
    }          



         




    /**
    * @Rest\Post("/api_bolsamax/admin/usuario/crear/")
    */
    public function crear(Request $request)
    {     
        
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $zona = $em->getRepository("AppBundle:Zona")->find($request->get("zona"));

        // Valido mail
        $posible = $em->getRepository("AppBundle:Usuario")->findOneBy(array("email" => $request->get("email")));
        if($posible){
            return new JsonResponse("email_existente");
        }

        $userManager = $this->get("fos_user.user_manager");
        $usuario = $userManager->createUser();
        $usuario->setUsername($request->get("email"));
        $usuario->setEmail($request->get("email"));
        $usuario->setEmailCanonical($request->get("email"));
        $usuario->setNombre($request->get("nombre"));  
        $usuario->setApellido($request->get("apellido"));  
        $usuario->setDireccion($request->get("direccion"));  
        $usuario->setTelefono($request->get("telefono"));  
        $usuario->setPerfil($request->get("perfil"));  
        $usuario->setTipo("usuario_cliente");    
        $usuario->setZona($zona);      
        $usuario->setPlainPassword($request->get("password"));              
        $usuario->setEnabled(true);                 
      
        $em->persist($usuario);
        $userManager->updateUser($usuario);
        $em->flush();  

        return new JsonResponse("ok");



    }     







    /**
    * @Rest\Post("/api_bolsamax/admin/usuario/editar/")
    */
    public function editar(Request $request)
    {     
        
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($request->get("id"));
        $zona = $em->getRepository("AppBundle:Zona")->find($request->get("zona_id"));

        // Valido mail
        $posible = $em->getRepository("AppBundle:Usuario")->findOneBy(array("email" => $request->get("email")));
        if($posible && $posible->getId() != $request->get("id")){
            return new JsonResponse("email_existente");
        }

        
        $usuario->setUsername($request->get("email"));
        $usuario->setEmail($request->get("email"));
        $usuario->setEmailCanonical($request->get("email"));
        $usuario->setNombre($request->get("nombre"));  
        $usuario->setApellido($request->get("apellido"));  
        $usuario->setDireccion($request->get("direccion"));  
        $usuario->setTelefono($request->get("telefono"));  
        $usuario->setPerfil($request->get("perfil"));  
        $usuario->setZona($zona);      
        $em->flush();  

        return new JsonResponse("ok");



    }        





        /**
    * @Rest\Get("/api_bolsamax/admin/usuario/traer/{id}")
    */
    public function traer($id)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $respuesta = array();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->find($id); 
        $respuesta["id"] = $usuario->getId();
        $respuesta["email"] = $usuario->getEmail();
        $respuesta["habilitado"] = $usuario->isEnabled();
        $respuesta["nombre"] = $usuario->getNombre();
        $respuesta["apellido"] = $usuario->getApellido();
        $respuesta["cliente"] = $usuario->getZona()->getCliente()->getNombre();
        $respuesta["zona"] = $usuario->getZona()->getNombre();
        $respuesta["zona_id"] = $usuario->getZona()->getId();
        $respuesta["telefono"] = $usuario->getTelefono();
        $respuesta["direccion"] = $usuario->getDireccion();
        $respuesta["perfil"] = $usuario->getPerfil();
            
        return new JsonResponse($respuesta);
    }








   /**
    * @Rest\Get("/api_bolsamax/admin/usuario/eliminar/{id}")
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







}
