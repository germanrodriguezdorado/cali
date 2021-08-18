<?php
namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Agenda;
use AppBundle\Entity\Negocio;




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
          $respuesta["telefono_negocio"] = $negocio->getTelefono();
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

        // Si el mail es incorrecto
        if(!$this->get("functions")->isValidEmail($request->get("email"))) return new JsonResponse("-1");

        // Si este email no esta bloqueado para el negocio
        $bloqueo = $em->getRepository("AppBundle:Bloqueo")->findOneBy(array("negocio" => $negocio->getId(), "email" => $request->get("email")));
        if($bloqueo) return new JsonResponse("-3");

        // Si el usuario tiene una agenda sin procesar
        //if($this->get("servicio_horarios")->tieneAgendaSinProcesar($request->get("email"), $negocio)) return new JsonResponse("-2");
        
        // Si el horario no esta dispoible
        if(!$this->get("servicio_horarios")->horariosEstaDisponible($negocio,$request->get("fecha"),$request->get("horario"))) return new JsonResponse("-1");
        
        // Creo agenda
        $agenda = new Agenda();
        $agenda->setNegocio($negocio);
        $agenda->setClienteMail($request->get("email"));
        $fecha_ok = \DateTime::createFromFormat("Y-m-d", $request->get("fecha"));
        $agenda->setFecha($fecha_ok);
        $agenda->setHorario($request->get("horario"));
        $token = $this->get("string_functions")->generateRandomString(60);
        $agenda->setConfirmationToken($token);
        $em->persist($agenda);

        $this->get("email_service")->preAgenda($request->get("email"), $negocio->getNombre(), $fecha_ok->format("d/m/Y"), $request->get("horario"), $token);


        $em->flush();
        
        return new JsonResponse("1");   
    }     



   /**
    * @Rest\Post("/api_hc/p/confirmacion")
    */
    public function confirmacion(Request $request)
    {
        
        $respuesta = array();
        $respuesta["cliente_email"] = "";
        $em = $this->getDoctrine()->getManager();
        $agenda = $em->getRepository("AppBundle:Agenda")->findOneBy(array("confirmationToken" => $request->get("token")));
          
        if($agenda){
          $respuesta["cliente_email"] = $agenda->getClienteMail();
          $respuesta["negocio"] = $agenda->getNegocio()->getNombre();
          $respuesta["direccion"] = $agenda->getNegocio()->getDireccion();
          $respuesta["fecha"] = $agenda->getFecha()->format("d/m/Y");
          $respuesta["horario"] = $agenda->getHorario();
          $agenda->setConfirmationToken(null);
          $em->flush();
          $this->get("email_service")->agendaConfirmadaDirigidaANegocio($agenda);
        }

        return new JsonResponse($respuesta);  
    }           



       




     /**
     * @Rest\Post("/api_hc/p/register_step1")
     */
    public function registerStep1(Request $request)
    {


      $em = $this->getDoctrine()->getManager();

      
      // Valido mail
      if(!$this->get("functions")->isValidEmail($request->get("email"))) return new JsonResponse("error");


      
      $posible_duplicado = $em->getRepository("AppBundle:Usuario")->findOneBy(array("email" => $request->get("email")));
      if($posible_duplicado) return new JsonResponse("email_existente");

      

      // Creo Usuario      
      $userManager = $this->get("fos_user.user_manager");
      $usuario = $userManager->createUser();
      $usuario->setUsername($request->get("email"));
      $usuario->setEmail($request->get("email"));
      $usuario->setEmailCanonical($request->get("email"));
      $usuario->setPlainPassword($request->get("password"));              
      $usuario->setEnabled(false);   
      $usuario->setTipo(1);          
      $usuario->setConfirmationToken($this->get("string_functions")->generateRandomString(60));          
      $em->persist($usuario);
      $userManager->updateUser($usuario);
      $em->flush();  

      // Creo negocio
      $negocio = new Negocio();
      $negocio->setUsuario($usuario);
      $negocio->setNombre($request->get("nombre"));
      $negocio->setSlug($this->get("string_functions")->slugify($request->get("nombre")));
      $negocio->setEmail($request->get("email"));
      $negocio->setDescanso("Sin descanso");
      $negocio->setDuracion("30");
      $em->persist($negocio);
      $em->flush(); 



      // Aviso al nuevo usuario
      $res = $this->get("email_service")->registroUsuario($usuario, $negocio->getNombre());
      if(!$res) return new JsonResponse("error");
     
      
      return new JsonResponse("1");


      
    }






    /**
    * @Rest\Get("/api_hc/p/register_check/{token}")
    */
    public function registerCheck($token)
    {      
        $respuesta = array();
        $respuesta["user_id"] = "";
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository("AppBundle:Usuario")->findOneBy(array("confirmationToken" => $token));
        if(!$usuario) return new JsonResponse($respuesta); 


        $respuesta["user_id"] = $usuario->getId();
        $respuesta["token"] = $this->get("jwt")->getToken($usuario);
        $respuesta["nombre"] = "";
        $respuesta["username"] = $usuario->getUsername();
        $respuesta["tipo"] = $usuario->getTipo();  

        $usuario->setConfirmationToken(null); 
        $usuario->setEnabled(true);
        $em->flush();

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