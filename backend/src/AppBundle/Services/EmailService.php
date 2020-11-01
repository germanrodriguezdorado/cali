<?php

namespace AppBundle\Services;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;


use AppBundle\Entity\Usuario;


// Use from a controller:
// $this->get("email_service")->functionName(variable);




class EmailService extends Controller
{

    private $em;    
    private $templating;
    private $mailer;
    private $functions;
    private $container_interface;

    private $email;

    private $from;
    private $to;
    private $envio_emails;
    
    

    public function __construct(
        EntityManagerInterface $em, 
        ContainerInterface $container_interface,
        \Swift_Mailer $mailer, 
        \Twig_Environment $templating, 
        \AppBundle\Resources\Functions $functions
        )
    {                
        $this->em = $em;      
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->functions = $functions;
        //$config = $this->em->getRepository("AppBundle:Config")->find(1);        
        //$this->envio_emails = $config->getEnvioEmails();
        $this->envio_emails = true;
        $this->container_interface = $container_interface;
        $this->email = \Swift_Message::newInstance();
    }




    public function preAgenda($email, $nombre_negocio, $fecha, $horario, $token){
        if(!$this->envio_emails) return;
        $this->email->setSubject("Confirma tu reserva en ".$nombre_negocio."!");            
        $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
        $this->email->setTo($email);              
        $this->email->setBody($this->templating->render("emails/agenda_dirigida_a_cliente.html.twig", array(
            "nombre_negocio" => $nombre_negocio,
            "fecha" => $fecha,
            "horario" => $horario,
            "token" => $token
        )), "text/html");
        $this->mailer->send($this->email);
        return 1;
    }



     






    public function registroUsuario(Usuario $usuario){   
        if(!$this->envio_emails) return true;  

        try{
            $this->email->setSubject("Solicitud de usuario recibida");            
            $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
            $this->email->setTo($usuario->getEmail());              
            $this->email->setBody($this->templating->render("registroUsuario.html.twig", array("usuario" => $usuario)), "text/html");
            if($this->mailer->send($this->email) > 0) $envio_exitoso = true;
        }catch(\Swift_TransportException $e){
            $envio_exitoso = false;
        }

        return $envio_exitoso;
    }        



    public function avisoRegistroUsuario(Usuario $usuario){   
        if(!$this->envio_emails) return true;  

        try{
            $this->email->setSubject("Solicitud de usuario recibida");            
            $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
            $this->email->setTo( $this->container_interface->getParameter("casilla_receptora_aviso_nuevo_usuario") );              
            $this->email->setBody($this->templating->render("avisoRegistroUsuario.html.twig", array("usuario" => $usuario, "system_url" => $this->container_interface->getParameter("system_url"))), "text/html");
            if($this->mailer->send($this->email) > 0) $envio_exitoso = true;
        }catch(\Swift_TransportException $e){
            $envio_exitoso = false;
        }

        return $envio_exitoso;
    }      




    public function usuarioHabilitado(Usuario $usuario){   
        if(!$this->envio_emails) return true;  

        try{
            $this->email->setSubject("Solicitud de usuario aceptada");            
            $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
            $this->email->setTo($usuario->getEmail());              
            $this->email->setBody($this->templating->render("usuarioHabilitado.html.twig", array("usuario" => $usuario, "system_url" => $this->container_interface->getParameter("system_url"))), "text/html");
            if($this->mailer->send($this->email) > 0) $envio_exitoso = true;
        }catch(\Swift_TransportException $e){
            //echo($e->getMessage()); exit;
            $envio_exitoso = false;
        }

        return $envio_exitoso;
    }        


  
}