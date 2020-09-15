<?php

namespace AppBundle\Repository;
use AppBundle\Entity\OrdenTrabajoLog;


class OrdenTrabajoLogRepository extends \Doctrine\ORM\EntityRepository {



	



	
	public function darProductosJson(OrdenTrabajoLog $orden_trabajo_log){
		// Retorna un array de ids de Productos
		$em = $this->getEntityManager();
		$productos = array();
		
		$otlp = $em->getRepository("AppBundle:OrdenTrabajoLogProducto")->findBy(
			array(
				"ordenTrabajoLog" => $orden_trabajo_log->getId()
			)
		);
			
		foreach ($otlp as $un_otlp) {
			$un_producto = array();
			$un_producto["id"] = $un_otlp->getProducto()->getId();
			$un_producto["nombre"] = $un_otlp->getProducto()->getNombre();
			$productos[] = $un_producto;
		}

		return $productos;
	}


	public function productosPretty(OrdenTrabajoLog $orden_trabajo_log){
		
		$respuesta = "";
		$productos = $this->darProductosObjetos($orden_trabajo_log);
		foreach ($productos as $producto) {
			$respuesta .= $producto->getNombre().", ";
		}

		return substr($respuesta, 0, -2);
	}



	public function darProductosObjetos(OrdenTrabajoLog $orden_trabajo_log){

		
		$em = $this->getEntityManager();
		$productos = array();

				$otlp = $em->getRepository("AppBundle:OrdenTrabajoLogProducto")->findBy(
					array(
						"ordenTrabajoLog" => $orden_trabajo_log->getId()
					)
				);
			

		foreach ($otlp as $un_otlp) {
			$productos[] = $un_otlp->getProducto();
		}


		return $productos;

	}



	public function darFotos(OrdenTrabajoLog $orden_trabajo_log){
		$em = $this->getEntityManager();
		$respuesta = array();
        $otlog_fotos = $em->getRepository("AppBundle:OrdenTrabajoLogFoto")->findBy(array("ordenTrabajoLog" => $orden_trabajo_log->getId()));
        foreach ($otlog_fotos as $otlog_foto) {
        	$una_foto = array();
            $una_foto["id"] = $otlog_foto->getId();
            $una_foto["base64"] = $otlog_foto->generate_thumbnail();
            //$una_foto["base64"] = $otlog_foto->getFotoBase64();
            $respuesta[] = $una_foto;
        }
        return $respuesta;
	}




}
?>
