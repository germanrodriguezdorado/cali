<?php

namespace AppBundle\Repository;
use AppBundle\Entity\OrdenTrabajoFinalizacion;


class OrdenTrabajoFinalizacionRepository extends \Doctrine\ORM\EntityRepository {





	public function darIdsProductos(OrdenTrabajoFinalizacion $orden_trabajo_finalizacion){
		$em = $this->getEntityManager();
		$otf = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionProducto")->findBy(
					array(
						"ordenTrabajoFinalizacion" => $orden_trabajo_finalizacion->getId()
					)
				);
		$productos = array();
		foreach ($otf as $un) {
			$productos[] = $un->getProducto()->getId();
		}
		return $productos;
	}

	
	public function darProductosJson(OrdenTrabajoFinalizacion $orden_trabajo_finalizacion){
		$em = $this->getEntityManager();
		$otf = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionProducto")->findBy(
					array(
						"ordenTrabajoFinalizacion" => $orden_trabajo_finalizacion->getId()
					)
				);



		$productos = array();
		
		foreach ($otf as $un) {
			$un_producto = array();
			$un_producto["id"] = $un->getProducto()->getId();
			$un_producto["nombre"] = $un->getProducto()->getNombre();
			$productos[] = $un_producto;
		}

		return $productos;
	}



	public function darProductosString(OrdenTrabajoFinalizacion $orden_trabajo_finalizacion){
		$array = $this->darProductosJson($orden_trabajo_finalizacion);
		$respuesta = "";
		foreach ($array as $a) {
			$respuesta .= $a["nombre"].",";
		}
		$respuesta = rtrim($respuesta, ',');
		return $respuesta;
	}




	public function darFotos(OrdenTrabajoFinalizacion $orden_trabajo_finalizacion){
		$em = $this->getEntityManager();
		$respuesta = array();
        $fotos = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionFoto")->findBy(array("ordenTrabajoFinalizacion" => $orden_trabajo_finalizacion->getId()));
        foreach ($fotos as $foto) {
        	$una_foto = array();
            $una_foto["id"] = $foto->getId();
            $una_foto["base64"] = $foto->generate_thumbnail();
            //$una_foto["base64"] = $foto->getFotoBase64();
            $respuesta[] = $una_foto;
        }
        return $respuesta;
	}



}
?>
