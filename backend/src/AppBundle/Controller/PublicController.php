<?php
namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Agenda;




class PublicController extends FOSRestController
{


   /**
    * @Rest\Post("/api_hc/login")
    */
    public function login(Request $request)
    {
        $respuesta = array();
        $respuesta["user_id"] = "";
        $usuario = $this->get("fos_user.user_manager")->findUserByUsername($request->get("username"));
        // Se chequea que el usuario exista y que esté activo
        if(!$usuario || !$usuario->isEnabled()) return new JsonResponse($respuesta);  
        // Se verifica password
        $encoder = $this->get("security.encoder_factory")->getEncoder($usuario);
        if(!$encoder->isPasswordValid($usuario->getPassword(), $request->get("password"), $usuario->getSalt())) return new JsonResponse($respuesta);  
        $respuesta["user_id"] = $usuario->getId();
        $respuesta["token"] = $this->get("jwt")->getToken($usuario);
        $respuesta["nombre"] = "";
        $respuesta["username"] = $usuario->getUsername();
        $respuesta["tipo"] = $usuario->getTipo();  
        return new JsonResponse($respuesta);   
    }



   /**
    * @Rest\Post("/api_hc/p/buscar_negocio")
    */
    public function buscarNegocio(Request $request)
    {
        $respuesta = array();
        $q2 = "";        
        $q2 .= " SELECT n FROM AppBundle:Negocio n";
        $q2 .= " WHERE 1 = 1";
        if($request->get("nombre") != "") $q2 .= " AND n.nombre LIKE :nombre";
        if($request->get("barrio") != "") $q2 .= " AND n.barrio = :barrio";

        $q = $this->getDoctrine()->getManager()->createQuery($q2);
        if($request->get("nombre") != "") $q->setParameter("nombre", "%".$request->get("nombre")."%");
        if($request->get("barrio") != "") $q->setParameter("barrio", $request->get("barrio"));      
        $negocios = $q->getResult(); 

        foreach ($negocios as $negocio) {
          $un_negocio = array();
          $un_negocio["nombre"] = $negocio->getNombre();
          $un_negocio["direccion"] = $negocio->getDireccion();
          $un_negocio["slug"] = $negocio->getSlug();
          $respuesta[] = $un_negocio;
        }

        return new JsonResponse($respuesta);   

    }  



   /**
    * @Rest\Post("/api_hc/p/buscar_horarios")
    */
    public function buscarHorarios(Request $request)
    {
        $respuesta = array();
        $respuesta["nombre_negocio"] = "";
        $q = $this->getDoctrine()->getManager()->createQuery("SELECT n FROM AppBundle:Negocio n WHERE n.slug = :slug");
        $negocio = $q->setParameter("slug", $request->get("slug"))->getOneOrNullResult();      
       
        if($negocio){
          $respuesta["nombre_negocio"] = $negocio->getNombre();
          $respuesta["direccion_negocio"] = $negocio->getDireccion();
          $respuesta["horarios"] = $this->get("servicio_horarios")->horariosDisponibles($negocio, $request->get("fecha"));
        }
        
        return new JsonResponse($respuesta);   
    } 




   /**
    * @Rest\Post("/api_hc/p/agendar")
    */
    public function agendar(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $q = $em->createQuery("SELECT n FROM AppBundle:Negocio n WHERE n.slug = :slug");
        $negocio = $q->setParameter("slug", $request->get("slug"))->getOneOrNullResult();      
        // Si no existe el negocio
        if(!$negocio) return new JsonResponse("-1");
        // Si el usuario tiene una agenda sin procesar
        //if($this->get("servicio_horarios")->tieneAgendaSinProcesar()) return new JsonResponse("-2");
        // Si el horario no esta dispoible
        if(!$this->get("servicio_horarios")->horariosEstaDisponible($negocio,$request->get("fecha"),$request->get("horario"))) return new JsonResponse("-1");
        // Si el mail es incorrecto
        if(!$this->get("functions")->isValidEmail($request->get("email"))) return new JsonResponse("-1");

        // Creo agenda
        $agenda = new Agenda();
        $agenda->setNegocio($negocio);
        $agenda->setClienteMail($request->get("email"));
        $fecha_ok = \DateTime::createFromFormat("Y-m-d", $request->get("fecha"));
        $agenda->setFecha($fecha_ok);
        $agenda->setHorario($request->get("horario"));
        $agenda->setConfirmado(false);
        $em->persist($agenda);
        $em->flush();
        
        return new JsonResponse("1");   
    }            



  /**
     * @Rest\Post("/api_hc/login_using_token")
     */
    public function loginUsingToken(Request $request)
    {


        $respuesta = array();
        $respuesta["user_id"] = "";
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->findOneBy(array("confirmationToken" => $request->get("token")));
       
        if(!$usuario) return new JsonResponse($respuesta); 


        $respuesta["user_id"] = $usuario->getId();
        $respuesta["token"] = $this->get("jwt")->getToken($usuario);
        $respuesta["nombre"] = $usuario->getNombrePretty();
        $respuesta["username"] = $usuario->getUsername();
        $respuesta["tipo"] = $usuario->getTipoCodificado(); 
        $respuesta["perfil"] = $usuario->getPerfilCodificado();   



        $usuario->setConfirmationToken(null); 
        $usuario->setEnabled(true); 


        // Cambio password
        $userManager = $this->container->get("fos_user.user_manager");
        $usuario->setPlainPassword($request->get("password"));                  
        $userManager->updateUser($usuario, true);   
        
        $em->flush();
        

        return new JsonResponse($respuesta);  
        
    }        




     /**
     * @Rest\Post("/api_hc/public/register_step1")
     */
    public function registerStep1(Request $request)
    {

      // Valido mail
      $em = $this->getDoctrine()->getManager();
      $posible_duplicado = $em->getRepository("AppBundle:Usuario")->findOneBy(array("email" => $request->get("email")));
      if($posible_duplicado) return new JsonResponse("email_existente", 200);
      if(!$this->get("functions")->isValidEmail($request->get("email"))) return new JsonResponse("Error", 500);
      if($request->get("perfil") != "Administración" && $request->get("perfil") != "Operaciones" && $request->get("perfil") != "Programación") return new JsonResponse("Error",500);

      // Valido Rut
      $cliente = $em->getRepository("AppBundle:Cliente")->findOneBy(array("rut" => $request->get("rut"), "active" => true));
      if(!$cliente) return new JsonResponse("cliente_no_encontrado", 200);

      // Valido la zona
      $zona = $em->getRepository("AppBundle:Zona")->findOneBy(array("cliente" => $cliente->getId(), "id" => $request->get("zona"), "active" => true));
      if(!$zona) return new JsonResponse("zona_no_encontrada", 200);

      // Usuario      
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
      $usuario->setPlainPassword($this->get("functions")->generateRandomString(20));              
      $usuario->setEnabled(false);          
      $usuario->setConfirmationToken($this->get("functions")->generateRandomString(60));          
      
      $em->persist($usuario);
      $userManager->updateUser($usuario);
      $em->flush();  



      // Aviso al nuevo usuario
      $this->get("email_service")->registroUsuario($usuario);

      // Aviso a Operaciones BOLSAMAX
      $this->get("email_service")->avisoRegistroUsuario($usuario);
      

      
      return new JsonResponse("ok", 200);


      
    }






    /**
    * @Rest\Get("/api_hc/public/register_check/{token}")
    */
    public function registerCheck($token)
    {      
        $em = $this->getDoctrine()->getManager();
        $respuesta = array();
        $usuario = $em->getRepository("AppBundle:Usuario")->findOneBy(array("confirmationToken" => $token));
        if($usuario){
          $respuesta["nombre"] = $usuario->getNombre();
        }else{
          $respuesta["nombre"] = "";
        }
        return new JsonResponse($respuesta);
    }




       /**
    * @Rest\Get("/api_hc/common/password_change/{password}")
    */
    public function passwordChange($password)
    {              
        $user = $this->getUser();        
        $userManager = $this->container->get("fos_user.user_manager");
        $user->setPlainPassword($password);                  
        $userManager->updateUser($user, true);   
        return new JsonResponse(1);
    }





 



}

?>