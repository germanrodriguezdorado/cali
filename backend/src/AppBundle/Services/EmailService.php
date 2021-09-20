<?php

namespace AppBundle\Services;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;


use AppBundle\Entity\Usuario;
use AppBundle\Entity\Agenda;


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
        $this->email->setSubject("Confirmá tu reserva en ".$nombre_negocio);            
        $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
        $this->email->setTo($email);   
        $this->email->setBcc("germanrodriguezdorado@gmail.com");               
        $this->email->setBody($this->templating->render("emails/agenda_dirigida_a_cliente.html.twig", array(
            "nombre_negocio" => $nombre_negocio,
            "fecha" => $fecha,
            "horario" => $horario,
            "token" => $token
        )), "text/html");
        $this->mailer->send($this->email);
        return 1;
    }




    public function cancelarAgenda($email, $nombre_negocio, $fecha, $horario, $motivo){
        if(!$this->envio_emails) return;
        $this->email->setSubject($nombre_negocio." canceló tu cita");            
        $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
        $this->email->setTo($email);     
        $this->email->setBcc("germanrodriguezdorado@gmail.com");             
        $this->email->setBody($this->templating->render("emails/cancelacion.html.twig", array(
            "nombre_negocio" => $nombre_negocio,
            "fecha" => $fecha,
            "horario" => $horario,
            "motivo" => $motivo
        )), "text/html");
        $this->mailer->send($this->email);
        return 1;
    }



    


    public function registroUsuario(Usuario $usuario, $nombre_negocio){   
        if(!$this->envio_emails) return true;  

        try{
            $this->email->setSubject("Gracias por registrarte a Cali");            
            $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
            $this->email->setTo($usuario->getEmail());
            $this->email->setBcc("germanrodriguezdorado@gmail.com");              
            $this->email->setBody($this->templating->render("emails/registro.html.twig", array("usuario" => $usuario, "nombre_negocio" => $nombre_negocio)), "text/html");
            if($this->mailer->send($this->email) > 0) $envio_exitoso = true;
        }catch(\Swift_TransportException $e){
            $envio_exitoso = false;
        }

        return $envio_exitoso;
    }   




    public function agendaConfirmadaDirigidaANegocio(Agenda $agenda){
        if(!$this->envio_emails) return;
        $this->email->setSubject("Tenés una nueva reserva de horario");            
        $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));             
        $this->email->setTo($agenda->getNegocio()->getEmail()); 
        $this->email->setBcc("germanrodriguezdorado@gmail.com");                 
        $this->email->setBody($this->templating->render("emails/agenda_confirmada_dirigida_a_negocio.html.twig", array(
            "agenda" => $agenda
        )), "text/html");
        $this->mailer->send($this->email);
        return 1;
    }
    
    
    public function passwordReset(Usuario $usuario){   
        if(!$this->envio_emails) return;  
        try{
            $this->email->setSubject("Cambio de contraseña");            
            $this->email->setFrom(array($this->container_interface->getParameter("mailer_user") => $this->container_interface->getParameter("mailer_name")));               
            $this->email->setTo($usuario->getEmail()); 
            $this->email->setBcc("germanrodriguezdorado@gmail.com");        
            $this->email->setBody($this->templating->render("emails/password_reset.html.twig", array("usuario" => $usuario)), "text/html");                
            $res = $this->mailer->send($this->email);
            if($res > 0) $envio_exitoso = true;
        }catch(\Swift_TransportException $e){
            $envio_exitoso = false;
        }
        return $envio_exitoso;
    }




     


  
}