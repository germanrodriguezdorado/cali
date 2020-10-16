<?php
namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Usuario;




class PublicController extends FOSRestController
{


   /**
    * @Rest\Post("/api_hc/login")
    */
    public function login(Request $request)
    {

       /* $respuesta = array();
        $respuesta["user_id"] = "";


      
        $usuario = $this->get("fos_user.user_manager")->findUserByUsername($request->get("username"));


       
        // Se chequea que el usuario exista
        if(!$usuario) return new JsonResponse($respuesta);  
        
        // Se chequea si está activo
        if(!$usuario->getActive() || !$usuario->isEnabled()) return new JsonResponse($respuesta);  

        // Se verifica la password
        $encoder = $this->get("security.encoder_factory")->getEncoder($usuario);
        if(!$encoder->isPasswordValid($usuario->getPassword(), $request->get("password"), $usuario->getSalt())) return new JsonResponse($respuesta);  


        $respuesta["user_id"] = $usuario->getId();
        $respuesta["token"] = $this->get("jwt")->getToken($usuario);
        $respuesta["nombre"] = $usuario->getNombrePretty();
        $respuesta["username"] = $usuario->getUsername();
        $respuesta["tipo"] = $usuario->getTipoCodificado(); 
        $respuesta["perfil"] = $usuario->getPerfilCodificado();   
        return new JsonResponse($respuesta);  */


        $respuesta = array();
        if( $request->get("username") == "juan" ){
          $respuesta["user_id"] = "1";
          $respuesta["token"] = "1234";
          $respuesta["nombre"] = "Juan Perez";
          $respuesta["username"] = "juan";
          $respuesta["tipo"] = "1"; 
        }else{
          $respuesta["user_id"] = "2";
          $respuesta["token"] = "1234";
          $respuesta["nombre"] = "German Rodriguez";
          $respuesta["username"] = "german";
          $respuesta["tipo"] = "2"; 
        }

        
         
        return new JsonResponse($respuesta);  
        
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