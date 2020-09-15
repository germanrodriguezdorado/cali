<?php

namespace AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\OrdenTrabajo;
use AppBundle\Entity\OrdenTrabajoLog;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Cliente;

// Uso: $this->get("ordentrabajo_service")->functionNombre(variable);


class OrdenTrabajoService extends Controller
{

    private $em;

    public function __construct(ContainerInterface $container){
        $this->em = $container->get("doctrine")->getManager();
        //$this->notificaciones_por_whatsapp = $container->getParameter("notificaciones_por_whatsapp");
    }




    public function existeNumero($numero, Cliente $cliente){
        $respuesta = false;
        $orden = $this->em->getRepository("AppBundle:OrdenTrabajo")->findOneBy(array("cliente" => $cliente->getId(), "numero" => $numero));   
        if($orden) $respuesta = true;
        return $respuesta;
    }



    public function filtrar(Usuario $usuario){

       
            $ordenes_trabajo = $this->em->getRepository("AppBundle:OrdenTrabajo")->findAll();    
        

     
        
        $respuesta = array();
        foreach ($ordenes_trabajo as $orden_trabajo){
            $una_respuesta = array();
            $una_respuesta["id"] = $orden_trabajo->getId();
            $una_respuesta["cliente"] = $orden_trabajo->getUsuario()->getZona()->getCliente()->getNombre();
            $una_respuesta["zona"] = $orden_trabajo->getUsuario()->getZona()->getNombre();
            $una_respuesta["referencia_nombre"] = $orden_trabajo->getUsuario()->getNombrePretty();
            $una_respuesta["referencia_telefono"] = $orden_trabajo->getUsuario()->getTelefono();
            $una_respuesta["referencia_direccion"] = $orden_trabajo->getUsuario()->getDireccion();
            $una_respuesta["numero"] = $orden_trabajo->getNumero();
            $una_respuesta["estado"] = $orden_trabajo->getEstado();
            $una_respuesta["tipo_trabajo"] = $orden_trabajo->getTipoTrabajo();
            $una_respuesta["prioridad"] = $orden_trabajo->getPrioridad();
            $una_respuesta["direccion_trabajo"] = $orden_trabajo->getDireccionTrabajo();
            $una_respuesta["bolsones"] = $orden_trabajo->getBolsones();
            $una_respuesta["comentarios"] = $orden_trabajo->getComentarios();
            $una_respuesta["asignado"] = $orden_trabajo->getAsignado();
            $una_respuesta["fecha_solicitado"] = $orden_trabajo->getFechaSolicitado()->format("d/m/Y H:i");
            $una_respuesta["latitud"] = $orden_trabajo->getLatitud();
            $una_respuesta["longitud"] = $orden_trabajo->getLongitud();
            
            if($usuario->getTipo() == "usuario_cliente"){
                if($orden_trabajo->getUsuario()->getZona()->getCliente()->getId() == $usuario->getZona()->getCliente()->getId()){
                    $respuesta[] = $una_respuesta;
                }
            }else{
                $respuesta[] = $una_respuesta;
            }
            

        }
        return $respuesta;
    }




    public function eliminar($id){


        // Elimino los logs
        $logs = $this->em->getRepository("AppBundle:OrdenTrabajoLog")->findBy(array("ordenTrabajo" => $id));
        foreach ($logs as $log) $this->em->remove($log);


        $ot = $this->em->getRepository("AppBundle:OrdenTrabajo")->find($id);
        $this->em->remove($ot);
        $this->em->flush();
        return true;
    }    

 



    public function traer(Usuario $usuario, $ot_id){
        $respuesta = array();
        $data = array();
        $ot = $this->em->getRepository("AppBundle:OrdenTrabajo")->find($ot_id);
        
        if($usuario->getTipo() == "usuario_cliente"){
            if( $ot->getUsuario()->getZona()->getCliente()->getId() != $usuario->getZona()->getCliente()->getId() ){
                $respuesta["status"] = "error";
                $respuesta["data"] = $data;
                return $respuesta;
            }
        }


        
        $data["id"] = $ot->getId();
        $data["empresa"] = $ot->getUsuario()->getZona()->getCliente()->getNombre();
        $data["referencia_nombre"] = $ot->getUsuario()->getNombrePretty();
        $data["referencia_telefono"] = $ot->getUsuario()->getTelefono();
        $data["referencia_direccion"] = $ot->getUsuario()->getDireccion();
        $data["numero"] = $ot->getNumero();
        $data["estado"] = $ot->getEstado();
        $data["tipo_trabajo"] = $ot->getTipoTrabajo();
        $data["prioridad"] = $ot->getPrioridad();
        $data["direccion_trabajo"] = $ot->getDireccionTrabajo();
        $data["bolsones"] = $ot->getBolsones();
        $data["comentarios_iniciales"] = $ot->getComentariosIniciales();
        $data["asignado"] = $ot->getAsignado();
        $data["fecha_solicitado"] = $ot->getFechaSolicitado()->format("d/m/Y H:i");
        $data["latitud"] = $ot->getLatitud();
        $data["longitud"] = $ot->getLongitud();
       

        $logs = array();
        $ot_logs = $this->em->getRepository("AppBundle:OrdenTrabajoLog")->findBy(array("ordenTrabajo" => $ot_id), array("id" => "DESC"));
        foreach ($ot_logs as $ot_log) {
            $un_log = array();
            $un_log["id"] = $ot_log->getId();
            $un_log["fecha"] = $ot_log->getFecha()->format("d/m/Y H:i");

            $emisor = "";
            if($ot_log->getEmisor() == "cliente"){
                $emisor = $ot->getUsuario()->getZona()->getCliente()->getNombre();
            }else{
                $emisor = "BOLSAMAX";
            }

            $un_log["emisor"] = $emisor;
            $un_log["comentarios"] = $ot_log->getComentarios();

            $un_log["foto"] = $ot_log->getFotoBase64();

            
            $logs[] = $un_log;
        }

        $data["logs"] = $logs; 


        $respuesta["status"] = "ok";
        $respuesta["data"] = $data;
        return $respuesta;
    }









    













  
}
