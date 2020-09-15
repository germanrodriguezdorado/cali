<?php

namespace AppBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


// Comando: php bin/console eliminar_imagenes

class eliminarImagenes extends ContainerAwareCommand
{
    private $em;

    protected function configure(){
        $this->setName("eliminar_imagenes");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get("doctrine")->getManager();
        $un_mes_atras = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
        $tres_meses_atras = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-3 month" ) );
              
        

        // OTs de BOLSAMAX.
        // Las imagenes se eliminan despues del mes
        // OT Finalización
        $sql = ""; 
        $sql .= "SELECT DISTINCT(ot.id) FROM orden_trabajo ot, orden_trabajo_finalizacion otf, orden_trabajo_finalizacion_foto otff WHERE 1=1";
        $sql .= " AND ot.estado = 'Cumplido'"; 
        $sql .= " AND ot.cliente = 19"; // BOLSAMAX
        $sql .= " AND ot.id = otf.orden_trabajo";
        $sql .= " AND otf.id = otff.orden_trabajo_finalizacion";
        //$sql .= " AND DATE(otf.fecha) < '".$un_mes_atras."';";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();   
        $res = $stmt->fetchAll();

        print_r($res);    
        
     
    }

  
}


