<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Cliente;


class ClienteRepository extends \Doctrine\ORM\EntityRepository {



	public function rutExistente($rut){
		$em = $this->getEntityManager();
		$respuesta = "0";
		$existente = $em->getRepository("AppBundle:Cliente")->findOneBy(array("rut" => $rut));
		if($existente){
			$respuesta = $existente->getId();
		}
		return $respuesta;
	}


	


}
?>
