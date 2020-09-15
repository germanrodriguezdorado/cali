<?php

namespace AppBundle\Repository;
use AppBundle\Entity\OrdenTrabajo;


class OrdenTrabajoRepository extends \Doctrine\ORM\EntityRepository {



	public function productosPretty(OrdenTrabajo $orden_trabajo){
		
		$respuesta = "";
		$productos = $this->darProductosObjetos($orden_trabajo);
		foreach ($productos as $producto) {
			$respuesta .= $producto->getNombre().", ";
		}

		if($respuesta != ""){
			$respuesta = substr($respuesta, 0, -2);
		}

		return $respuesta;
	}



	public function productosSolicitadosPretty(OrdenTrabajo $orden_trabajo){	
		$em = $this->getEntityManager();
		$productos = $em->getRepository("AppBundle:OrdenTrabajoProductosSolicitados")->findBy(
			array("ordenTrabajo" => $orden_trabajo->getId())
		);
		$productos_string = "";
		foreach ($productos as $producto) {
			$productos_string .= $producto->getProducto()->getNombre().", ";
		}

		if($productos_string != ""){
			$productos_string = substr($productos_string, 0, -2);
		}

		return $productos_string;
	}




	public function balizasSegunEstado(OrdenTrabajo $orden_trabajo){

		$balizas = "";

		if($orden_trabajo->getEstado() == "Pendiente" || $orden_trabajo->getEstado() == "En curso"){
			$ultimo_log = $this->darUltimoLog($orden_trabajo);
			if($ultimo_log){
				$balizas = $ultimo_log->getBalizasPretty();
			}
		}



		if($orden_trabajo->getEstado() == "Cumplido"){
			$finalizacion = $this->darFinalizacion($orden_trabajo);
			if($finalizacion){
				$balizas = $finalizacion->getBalizasPretty();
			}

		}

		return $balizas;

	}



	public function darProductosIds(OrdenTrabajo $orden_trabajo){
		// Retorna un array de ids de Productos
		$em = $this->getEntityManager();
		$productos = array();
		if($orden_trabajo->getEstado() == "Pendiente" || $orden_trabajo->getEstado() == "En curso"){
			$otlp = $em->getRepository("AppBundle:OrdenTrabajoProductosSolicitados")->findBy(array("ordenTrabajo" => $orden_trabajo->getId()));			
		}


		if($orden_trabajo->getEstado() == "Cumplido"){
			$finzalizacion = $this->darFinalizacion($orden_trabajo);
			$otlp = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionProducto")->findBy(array("ordenTrabajoFinalizacion" => $finzalizacion->getId()));	
		}

		foreach ($otlp as $un_otlp) {
			$productos[] = $un_otlp->getProducto()->getId();
		}

		return $productos;
	}



	public function darProductosObjetos(OrdenTrabajo $orden_trabajo){

		// Los productos no se guardan a nivel de OrdenTrabajo, se guardan a nivel de log.
		// Por eso, debo tomar el último log y obtenerlos de ahi
		// Retorna un array de Productos
		$em = $this->getEntityManager();
		$productos = array();


		if($orden_trabajo->getEstado() == "Pendiente" || $orden_trabajo->getEstado() == "En curso"){
			
				$otlp = $em->getRepository("AppBundle:OrdenTrabajoProductosSolicitados")->findBy(
					array(
						"ordenTrabajo" => $orden_trabajo->getId()
					)
				);
			
		}

		if($orden_trabajo->getEstado() == "Cumplido"){
			$finzalizacion = $this->darFinalizacion($orden_trabajo);
			$otlp = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionProducto")->findBy(
				array(
					"ordenTrabajoFinalizacion" => $finzalizacion->getId()
				)
			);
		}

		foreach ($otlp as $un_otlp) {
			$productos[] = $un_otlp->getProducto();
		}


		return $productos;

	}



	
	public function darProductosJson(OrdenTrabajo $orden_trabajo){
		// Retorna un array de ids de Productos
		$em = $this->getEntityManager();
		$productos = array();
		
		if($orden_trabajo->getEstado() == "Pendiente" || $orden_trabajo->getEstado() == "En curso"){
			$ultimo_log = $this->darUltimoLog($orden_trabajo);
			if($ultimo_log){
				$otlp = $em->getRepository("AppBundle:OrdenTrabajoLogProducto")->findBy(
					array(
						"ordenTrabajoLog" => $ultimo_log->getId()
					)
				);
			}
		}



		if($orden_trabajo->getEstado() == "Cumplido"){
			$finzalizacion = $this->darFinalizacion($orden_trabajo);
			$otlp = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionProducto")->findBy(
				array(
					"ordenTrabajoFinalizacion" => $finzalizacion->getId()
				)
			);
		}


		foreach ($otlp as $un_otlp) {
			$un_producto = array();
			$un_producto["id"] = $un_otlp->getProducto()->getId();
			$un_producto["nombre"] = $un_otlp->getProducto()->getNombre();
			$productos[] = $un_producto;
		}

		return $productos;
	}


	public function darFinalizacion(OrdenTrabajo $orden_trabajo){
		$em = $this->getEntityManager();
		$finalizacion = $em->getRepository("AppBundle:OrdenTrabajoFinalizacion")->findOneBy(array("ordenTrabajo" => $orden_trabajo->getId()));
		return $finalizacion;
	}


	public function darUltimoLog(OrdenTrabajo $orden_trabajo){
		$em = $this->getEntityManager();
		$ultimo_log = $em->getRepository("AppBundle:OrdenTrabajoLog")->findOneBy(array("ordenTrabajo" => $orden_trabajo->getId()),array("fecha" => "DESC"));
		return $ultimo_log;
	}



	public function darLogs(OrdenTrabajo $orden_trabajo){

		$em = $this->getEntityManager();
		$ot_logs = $em->getRepository("AppBundle:OrdenTrabajoLog")->findBy(array("ordenTrabajo" => $orden_trabajo->getId()), array("fecha" => "DESC"));
		$logs = array();

		foreach ($ot_logs as $ot_log) {
            $un_log = array();
            $un_log["id"] = $ot_log->getId();
            $un_log["fecha"] = $ot_log->getFecha()->format("d/m/Y H:i");

            if($ot_log->getEmisor() == "cliente"){
                $emisor = $orden_trabajo->getUsuario()->getZona()->getCliente()->getNombre();
            }else{
                $emisor = "BOLSAMAX";
            }

            $un_log["emisor"] = $emisor;
            $un_log["comentarios"] = $ot_log->getComentarios();
            $un_log["balizas"] = $ot_log->getBalizas();
            $un_log["cantidad_bolsones"] = $ot_log->getCantidadBolsones();
            $un_log["productos"] = $em->getRepository("AppBundle:OrdenTrabajoLog")->darProductosJson($ot_log);
            $un_log["fotos"] = $em->getRepository("AppBundle:OrdenTrabajoLog")->darFotos($ot_log);
            if($ot_log->getMovilAsignado()){
            	$un_log["movil_asignado"] = $ot_log->getMovilAsignado()->getNombre();
            }else{
            	$un_log["movil_asignado"] = "";
            }
            $logs[] = $un_log;
        }

        return $logs;
	}






	public function darInfo(OrdenTrabajo $orden_trabajo, $tipo_usuario = "admin"){

		$respuesta = array();
        $respuesta["id"] = $orden_trabajo->getId();
        $respuesta["cliente"] = $orden_trabajo->getCliente()->getNombre();
        $respuesta["zona"] = $orden_trabajo->getZona()->getNombre();
        $respuesta["referencia_nombre"] = $orden_trabajo->getUsuario()->getNombrePretty();
        $respuesta["referencia_telefono"] = $orden_trabajo->getUsuario()->getTelefono();
        $respuesta["referencia_direccion"] = $orden_trabajo->getUsuario()->getDireccion();
        $respuesta["numero"] = $orden_trabajo->getNumero();
        $respuesta["estado"] = $orden_trabajo->getEstado();
        $respuesta["productos_iniciales"] = $this->productosSolicitadosPretty($orden_trabajo);
        $respuesta["prioridad"] = $orden_trabajo->getPrioridad();
        $respuesta["direccion_trabajo"] = $orden_trabajo->getDireccionTrabajo();
        $respuesta["direccion_trabajo_simple"] = $orden_trabajo->getDireccionSimple();
        $respuesta["bolsones"] = $orden_trabajo->getBolsones();
        $respuesta["comentarios_iniciales"] = $orden_trabajo->getComentariosIniciales();
        $respuesta["asignado"] = $orden_trabajo->getAsignado();
        $respuesta["fecha_solicitado"] = $orden_trabajo->getFechaSolicitado()->format("d/m/Y H:i");
        $respuesta["latitud"] = $orden_trabajo->getLatitud();
        $respuesta["longitud"] = $orden_trabajo->getLongitud();

        $respuesta["fecha_cumplido"] = "";
        $respuesta["cantidad_bolsones_final"] = "";
        $respuesta["finalizacion_balizas"] = "";
        $respuesta["finalizacion_productos"] = "";
        if( $orden_trabajo->getEstado() == "Cumplido" ){
        	$ot_finalizacion = $this->darFinalizacion($orden_trabajo);
        	if($ot_finalizacion){
        		if($ot_finalizacion->getFechaManual() != "" && $ot_finalizacion->getFechaManual() != null){
        			$respuesta["fecha_cumplido"] = $ot_finalizacion->getFechaManual();
        		}else{
        			if($tipo_usuario == "usuario_cliente"){
        				$respuesta["fecha_cumplido"] = $ot_finalizacion->getFecha()->format("d/m/Y");
        			}else{
        				$respuesta["fecha_cumplido"] = $ot_finalizacion->getFecha()->format("d/m/Y H:i");
        			}
        		}
        		$respuesta["cantidad_bolsones_final"] = $ot_finalizacion->getCantidadBolsones();
        		$respuesta["finalizacion_balizas"] = $ot_finalizacion->getBalizas() ? "1" : "0";
        		$respuesta["finalizacion_productos"] = $this->getEntityManager()->getRepository("AppBundle:OrdenTrabajoFinalizacion")->darProductosString($ot_finalizacion);
        	}
        }



        $respuesta["movil_asignado_id"] = "";
        $respuesta["movil_asignado_nombre"] = "";
        if($orden_trabajo->getMovilAsignado() != null){
        	$respuesta["movil_asignado_id"] = $orden_trabajo->getMovilAsignado()->getId();
            $respuesta["movil_asignado_nombre"] = $orden_trabajo->getMovilAsignado()->getNombrePretty();
        }

        //
        
        return $respuesta;
	}




	public function darInfo2(OrdenTrabajo $orden_trabajo, $tipo_usuario = "admin"){

		$respuesta = array();
	    $respuesta["id"] = $orden_trabajo->getId();
	    $respuesta["cliente"] = $orden_trabajo->getCliente()->getNombre();
	    $respuesta["zona"] = $orden_trabajo->getZona()->getNombre();
	    $respuesta["referencia_nombre"] = $orden_trabajo->getUsuario()->getNombrePretty();
	    $respuesta["referencia_telefono"] = $orden_trabajo->getUsuario()->getTelefono();
	    $respuesta["referencia_direccion"] = $orden_trabajo->getUsuario()->getDireccion();
	    $respuesta["numero"] = $orden_trabajo->getNumero();
	    $respuesta["estado"] = $orden_trabajo->getEstado();
	    $respuesta["productos_iniciales"] = $this->productosSolicitadosPretty($orden_trabajo);
	    $respuesta["productos_pretty"] = $this->productosPretty($orden_trabajo);
        $respuesta["productos_array"] = $this->darProductosIds($orden_trabajo);
	    $respuesta["prioridad"] = $orden_trabajo->getPrioridad();
	    $respuesta["direccion_trabajo"] = $orden_trabajo->getDireccionTrabajo();
	    $respuesta["direccion_trabajo_simple"] = $orden_trabajo->getDireccionSimple();
	    $respuesta["bolsones"] = $orden_trabajo->getBolsones();
	    $respuesta["comentarios_iniciales"] = $orden_trabajo->getComentariosIniciales();
	    $respuesta["asignado"] = $orden_trabajo->getAsignado();
	    $respuesta["fecha_solicitado"] = $orden_trabajo->getFechaSolicitado()->format("d/m/Y H:i");
	    $respuesta["latitud"] = $orden_trabajo->getLatitud();
	    $respuesta["longitud"] = $orden_trabajo->getLongitud();

	    $respuesta["movil_asignado_id"] = "";
        $respuesta["movil_asignado_nombre"] = "";
        if($orden_trabajo->getMovilAsignado() != null){
        	$respuesta["movil_asignado_id"] = $orden_trabajo->getMovilAsignado()->getId();
        	$respuesta["movil_asignado_nombre"] = $orden_trabajo->getMovilAsignado()->getNombrePretty();
        }


	    $respuesta["logs"] = $this->darLogs($orden_trabajo);
	        
	    // Si está finalizado
	    if( $orden_trabajo->getEstado() == "Cumplido" ){
	    	$ot_finalizacion = $this->darFinalizacion($orden_trabajo);
	        $respuesta["finalizacion_cantidad_bolsones"] = $ot_finalizacion->getCantidadBolsones();
	        $respuesta["finalizacion_material_utilizado"] = $ot_finalizacion->getMaterialUtilizado();
	        $respuesta["finalizacion_comentarios"] = $ot_finalizacion->getComentarios();
	        if($tipo_usuario == "usuario_cliente"){
	        	$respuesta["finalizacion_fecha"] = $ot_finalizacion->getFecha()->format("d/m/Y");
	        }else{
	        	$respuesta["finalizacion_fecha"] = $ot_finalizacion->getFecha()->format("d/m/Y H:i");
	        }
	        $respuesta["finalizacion_balizas"] = $ot_finalizacion->getBalizas() ? "1" : "0";
	        $respuesta["finalizacion_fecha_manual"] = $ot_finalizacion->getFechaManual();
	        $respuesta["finalizacion_fotos"] = $this->getEntityManager()->getRepository("AppBundle:ordenTrabajoFinalizacion")->darFotos($ot_finalizacion);
	    }

	    return $respuesta;
	}






	public function reporteAdmin($desde, $hasta, $productos, $estado, $zona){

		$sql = "";
	    $sql .= "SELECT ot.id FROM orden_trabajo ot ";
	    $sql .= " WHERE 1 = 1 ";
	    $sql .= " AND ot.active = 1 ";
	    $sql .= " AND DATE(ot.fecha_solicitado) >= :desde";
	    $sql .= " AND DATE(ot.fecha_solicitado) <= :hasta";
	    if( $zona != "0" ){
	   		$sql .= " AND ot.zona = :zona";
	    }
	    if( $estado != "Cualquier" ){
	   		$sql .= " AND ot.estado = :estado";
	    }

	    $em = $this->getEntityManager();
	    $stmt = $em->getConnection()->prepare($sql);
	    if( $zona != "0" ) $stmt->bindValue(":zona", $zona);
	    if( $estado != "Cualquier" ) $stmt->bindValue(":estado", $estado);
	    $stmt->bindValue(":desde", $desde);
	    $stmt->bindValue(":hasta", $hasta);
	    $stmt->execute();
	    $res = $stmt->fetchAll();

		$respuesta = array();
	    foreach ($res as $un){
	    	$ot_objeto = $em->getRepository("AppBundle:OrdenTrabajo")->find($un);
	        if( count(array_intersect($this->darProductosIds($ot_objeto), $productos)) > 0 ){    
		    	$respuesta[] = $this->darInfo($ot_objeto, "admin");
	        }    
	    }

	    return $respuesta;
	}	






	public function reporteCliente($zona, $desde, $hasta, $productos, $estado){

		$sql = "";
	        $sql .= "SELECT ot.id FROM orden_trabajo ot ";
	        $sql .= " WHERE 1 = 1 ";
	        $sql .= " AND ot.active = 1 ";
	        $sql .= " AND DATE(ot.fecha_solicitado) >= :desde";
	        $sql .= " AND DATE(ot.fecha_solicitado) <= :hasta";
	        $sql .= " AND ot.zona = :zona";
	        if( $estado != "Cualquier" ) $sql .= " AND ot.estado = :estado";

	        $em = $this->getEntityManager();
	        $stmt = $em->getConnection()->prepare($sql);
	        if( $estado != "Cualquier" ) $stmt->bindValue(":estado", $estado);
		    $stmt->bindValue(":desde", $desde);
		    $stmt->bindValue(":hasta", $hasta);
		    $stmt->bindValue(":zona", $zona);
	        $stmt->execute();
	        $res = $stmt->fetchAll();

	        $respuesta = array();
	        foreach ($res as $un){
	        	$ot_objeto = $em->getRepository("AppBundle:OrdenTrabajo")->find($un);

	           if( count(array_intersect($this->darProductosIds($ot_objeto), $productos)) > 0 ){
		            $respuesta[] = $this->darInfo($ot_objeto, "usuario_cliente");
	            } 


	        }


	        return $respuesta;


	}	



	public function darUltimoNumero(){
        $em = $this->getEntityManager();
        $config = $em->getRepository("AppBundle:Config")->find(1);   
        return $config->getNumero();
    }





}
?>
