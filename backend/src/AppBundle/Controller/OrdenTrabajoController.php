<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use AppBundle\Entity\OrdenTrabajo;
use AppBundle\Entity\OrdenTrabajoLog;
use AppBundle\Entity\OrdenTrabajoLogFoto;
use AppBundle\Entity\OrdenTrabajoFinalizacion;
use AppBundle\Entity\OrdenTrabajoFinalizacionFoto;
use AppBundle\Entity\OrdenTrabajoLogProducto;
use AppBundle\Entity\OrdenTrabajoFinalizacionProducto;
use AppBundle\Entity\OrdenTrabajoProductosSolicitados;




class OrdenTrabajoController extends FOSRestController
{




    // Cliente

     /**
    * @Rest\Post("/api_bolsamax/cliente/orden_trabajo/crear/")
    */
    public function crear(Request $request)
    {     

        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);

        $em = $this->getDoctrine()->getManager();


        // Validacion de fotos correctas
        if(count($request->get("fotos")) > 0){
            if(!$this->get("images_functions")->validarLote($request->get("fotos"))){
                return new JsonResponse("error_imagen");
            }
        }


        if( $request->get("numero") == "" ){
            $numero = $em->getRepository("AppBundle:OrdenTrabajo")->darUltimoNumero();
        }else{
            $numero = $request->get("numero");
        }


        $fecha = new \DateTime("now");



        $ot = new OrdenTrabajo();
        $ot->setUsuario($em->getRepository("AppBundle:Usuario")->find($this->getUser()));
        $ot->setZona($this->getUser()->getZona());
        $ot->setCliente($this->getUser()->getZona()->getCliente());
        $ot->setNumero($numero);
        $ot->setBolsones($request->get("bolsones"));
        $ot->setPrioridad($request->get("prioridad"));
        $ot->setDireccionTrabajo($request->get("direccion"));
        $ot->setComentariosIniciales($request->get("comentarios_iniciales"));
        $ot->setEstado("Pendiente");
        $ot->setAsignado("BOLSAMAX");
        $ot->setLatitud($request->get("latitud"));
        $ot->setLongitud($request->get("longitud"));
        $ot->setFechaSolicitado($fecha);
        $em->persist($ot);
        $em->flush();


        $orden_trabajo_log = new OrdenTrabajoLog();
        $orden_trabajo_log->setOrdenTrabajo($ot);
        $orden_trabajo_log->setEstado("Pendiente");
        $orden_trabajo_log->setEmisor("cliente");
        $orden_trabajo_log->setComentarios("Se abre orden de trabajo");
        $orden_trabajo_log->setFecha($fecha);
        $em->persist($orden_trabajo_log);
        $em->flush();



        // Grabo productos
        foreach ($request->get("productos") as $producto) {
            $orden_trabajo_producto_solicitado = new OrdenTrabajoProductosSolicitados();
            $orden_trabajo_producto_solicitado->setOrdenTrabajo($ot);
            $orden_trabajo_producto_solicitado->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
            $em->persist($orden_trabajo_producto_solicitado);
        }

        $em->flush();
      

        if(count($request->get("fotos")) > 0){
            $nombres = $this->get("images_functions")->subirLote($request->get("fotos"), "imagenes_ots");
            $em->flush();
            foreach ($nombres as $nombre) {
                $otl_foto = new OrdenTrabajoLogFoto();
                $otl_foto->setOrdenTrabajoLog($orden_trabajo_log);
                $otl_foto->setFoto($nombre);   
                $em->persist($otl_foto);
            }
        }


        // Incremento numero de OT
        if( $request->get("numero") == "" ){
            $config = $em->getRepository("AppBundle:Config")->find(1);  
            $config->setNumero( $config->getNumero() + 1 ); 
        }

        $em->flush();


        return new JsonResponse("ok");



    }    





    /**
    * @Rest\Post("/api_bolsamax/cliente/orden_trabajo/actualizar/")
    */
    public function actualizar(Request $request)
    {     


        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);

        // Validacion de fotos correctas
        if(count($request->get("fotos")) > 0){
            if(!$this->get("images_functions")->validarLote($request->get("fotos"))){
                return new JsonResponse("error_imagen");
            }
        }
        
        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($request->get("id"));

        if($ot->getUsuario()->getZona()->getCliente()->getId() != $this->getUser()->getZona()->getCliente()->getId()){
            return new JsonResponse("error", 500);
        }

        $ot->setAsignado("BOLSAMAX");
        

        $otl = new OrdenTrabajoLog();
        $otl->setOrdenTrabajo($ot);
        $otl->setEstado($ot->getEstado());
        $otl->setEmisor("cliente");
        $otl->setComentarios($request->get("comentario"));
        $otl->setFecha(new \DateTime("now"));

        $em->persist($otl);
        

        if(count($request->get("fotos")) > 0){
            $nombres = $this->get("images_functions")->subirLote($request->get("fotos"), "imagenes_ots");
            $em->flush();
            foreach ($nombres as $nombre) {
                $otl_foto = new OrdenTrabajoLogFoto();
                $otl_foto->setOrdenTrabajoLog($otl);
                $otl_foto->setFoto($nombre);   
                $em->persist($otl_foto);
            }
        }

        $em->flush();
        return new JsonResponse("ok");
    }        



   /**
    * @Rest\Post("/api_bolsamax/cliente/orden_trabajo/filtrar/")
    */
    public function filtrarCliente(Request $request)
    {     

        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);

        $em =  $this->getDoctrine()->getManager();
         
        $q = "SELECT o FROM AppBundle:OrdenTrabajo o WHERE 1 = 1";
        $q .= " AND o.active = 1";
        $q .= " AND o.zona = ".$this->getUser()->getZona()->getId();
        $q .= " AND DATE(o.fechaSolicitado) BETWEEN :desde AND :hasta"; // german

        if($request->get("estado") != ""){
            $q .= " AND o.estado LIKE :estado";
        }

        if($request->get("direccion") != ""){
            $q .= " AND o.direccionTrabajo LIKE :direccion";
        }

        $query = $em->createQuery($q);   
        $query->setParameter("desde", $request->get("desde"));
        $query->setParameter("hasta", $request->get("hasta"));
        
        if($request->get("estado") != ""){
            $query->setParameter("estado", $request->get("estado"));
        }

        if($request->get("direccion") != ""){
            $query->setParameter("direccion", "%".$request->get("direccion")."%");
        }

        $ots = $query->getResult();
        $respuesta = array();
        foreach ($ots as $ot){
            $respuesta[] = $em->getRepository("AppBundle:OrdenTrabajo")->darInfo($ot, "usuario_cliente");
        }

        return new JsonResponse($respuesta);
    }
    
   

    
  

    /**
    * @Rest\Get("/api_bolsamax/cliente/orden_trabajo/traer/{id}")
    */
    public function traerCliente($id)
    {     


        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);

        $respuesta = array();
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($id);
        
        if($ot->getCliente()->getId() != $this->getUser()->getZona()->getCliente()->getId()){
            $respuesta["status"] = "error";
            $respuesta["data"] = $data;
            return $respuesta;
        }

        $respuesta["status"] = "ok";
        $respuesta["data"] = $em->getRepository("AppBundle:OrdenTrabajo")->darInfo2($ot, "usuario_cliente");

        return new JsonResponse($respuesta);
    }    


    /**
    * @Rest\Post("/api_bolsamax/cliente/orden_trabajo/traer_foto/")
    */
    public function traerFoto(Request $request)
    {     

        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);

        // TO-DO: control que sea del user
        $em = $this->getDoctrine()->getManager();
        $otl = $em->getRepository("AppBundle:OrdenTrabajoLog")->find($request->get("id"));
        $file = $_SERVER["DOCUMENT_ROOT"]."/imagenes_ots/".$otl->getFoto();
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }        







   /**
    * @Rest\Post("/api_bolsamax/cliente/orden_trabajo/reporte/")
    */
     public function reporteCliente(Request $request){

        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);
         
        $em = $this->getDoctrine()->getManager();
        $respuesta = $em->getRepository("AppBundle:OrdenTrabajo")->reporteCliente(
            $this->getUser()->getZona()->getId(),
            $request->get("desde"),
            $request->get("hasta"),
            $request->get("productos"),
            $request->get("estado")
        );

        return new JsonResponse($respuesta);
     }



    /**
    * @Rest\Post("/api_bolsamax/cliente/orden_trabajo/reporte_excel/")
    */
    public function reporteExcelCliente(Request $request){

        if($this->getUser()->getTipo() != "usuario_cliente") return new JsonResponse("error", 500);

       $em = $this->getDoctrine()->getManager();
        $respuesta = $em->getRepository("AppBundle:OrdenTrabajo")->reporteCliente(
            $this->getUser()->getZona()->getId(),
            $request->get("desde"),
            $request->get("hasta"),
            $request->get("productos"),
            $request->get("estado")
        );

      
       
        $eo = new Spreadsheet();         
        $eo->setActiveSheetIndex(0);       
        $eo->getActiveSheet()->setCellValue("A1", "Número");   
        $eo->getActiveSheet()->setCellValue("B1", "Fecha");   
        $eo->getActiveSheet()->setCellValue("C1", "Dirección");   
        $eo->getActiveSheet()->setCellValue("D1", "Tareas");     
        $eo->getActiveSheet()->setCellValue("E1", "Bolsones requeridos");    
        $eo->getActiveSheet()->setCellValue("F1", "Estado"); 
        $eo->getActiveSheet()->setCellValue("G1", "Fecha realización"); 
        $eo->getActiveSheet()->setCellValue("H1", "Bolsones utilizados");  
        $eo->getActiveSheet()->setCellValue("I1", "Balizas");
        $eo->getActiveSheet()->setCellValue("J1", "Trabajo realizado");                
        $row=2;     

        foreach ($respuesta as $ot) {
          $eo->getActiveSheet()->setCellValue("A".$row, $ot["numero"]);   
          $eo->getActiveSheet()->setCellValue("B".$row, $ot["fecha_solicitado"]);  
          $eo->getActiveSheet()->setCellValue("C".$row, $ot["direccion_trabajo"]);  
          $eo->getActiveSheet()->setCellValue("D".$row, $ot["productos_iniciales"]);   
          $eo->getActiveSheet()->setCellValue("E".$row, $ot["bolsones"]);   
          $eo->getActiveSheet()->setCellValue("F".$row, $ot["estado"]);   
          $eo->getActiveSheet()->setCellValue("G".$row, $ot["fecha_cumplido"]);       
          $eo->getActiveSheet()->setCellValue("H".$row, $ot["cantidad_bolsones_final"]);
          $eo->getActiveSheet()->setCellValue("I".$row, $ot["finalizacion_balizas"] == '1' ? "Si" : "No"); 
          $eo->getActiveSheet()->setCellValue("J".$row, $ot["finalizacion_productos"]);    
          $row++;          
        }


        $writer = new Xlsx($eo);

       
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="export.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;

    }      


































    // ADMIN




       /**
    * @Rest\Post("/api_bolsamax/admin/orden_trabajo/reporte/")
    */
     public function reporteAdmin(Request $request){

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $respuesta = $em->getRepository("AppBundle:OrdenTrabajo")->reporteAdmin(
            $request->get("desde"),
            $request->get("hasta"),
            $request->get("productos"),
            $request->get("estado"),
            $request->get("zona")
        );

        return new JsonResponse($respuesta);
     }

/**
    * @Rest\Post("/api_bolsamax/admin/orden_trabajo/reporte_excel/")
    */
     public function reporteExcelAdmin(Request $request){

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $respuesta = $em->getRepository("AppBundle:OrdenTrabajo")->reporteAdmin(
            $request->get("desde"),
            $request->get("hasta"),
            $request->get("productos"),
            $request->get("estado"),
            $request->get("zona")
        );

      
       
        $eo = new Spreadsheet();
        

        $eo->setActiveSheetIndex(0);         
        $eo->getActiveSheet()->setCellValue("A1", "Cliente");       
        $eo->getActiveSheet()->setCellValue("B1", "Número");   
        $eo->getActiveSheet()->setCellValue("C1", "Fecha");   
        $eo->getActiveSheet()->setCellValue("D1", "Dirección");   
        $eo->getActiveSheet()->setCellValue("E1", "Tareas");     
        $eo->getActiveSheet()->setCellValue("F1", "Bolsones requeridos");    
        $eo->getActiveSheet()->setCellValue("G1", "Estado"); 
        $eo->getActiveSheet()->setCellValue("H1", "Fecha realización"); 
        $eo->getActiveSheet()->setCellValue("I1", "Bolsones utilizados");
        $eo->getActiveSheet()->setCellValue("J1", "Balizas");    
        $eo->getActiveSheet()->setCellValue("K1", "Trabajo realizado");               
        $row=2;     

        foreach ($respuesta as $ot) {
          $eo->getActiveSheet()->setCellValue("A".$row, $ot["cliente"]);   
          $eo->getActiveSheet()->setCellValue("B".$row, $ot["numero"]);   
          $eo->getActiveSheet()->setCellValue("C".$row, $ot["fecha_solicitado"]);  
          $eo->getActiveSheet()->setCellValue("D".$row, $ot["direccion_trabajo_simple"]);  
          $eo->getActiveSheet()->setCellValue("E".$row, $ot["productos_iniciales"]);   
          $eo->getActiveSheet()->setCellValue("F".$row, $ot["bolsones"]);   
          $eo->getActiveSheet()->setCellValue("G".$row, $ot["estado"]);   
          $eo->getActiveSheet()->setCellValue("H".$row, $ot["fecha_cumplido"]);       
          $eo->getActiveSheet()->setCellValue("I".$row, $ot["cantidad_bolsones_final"]);
          $eo->getActiveSheet()->setCellValue("J".$row, $ot["finalizacion_balizas"] == '1' ? "Si" : "No");  
          $eo->getActiveSheet()->setCellValue("K".$row, $ot["finalizacion_productos"]);  
          $row++;          
        }


        $writer = new Xlsx($eo);

       
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="export.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;

    }   


       /**
    * @Rest\Post("/api_bolsamax/admin/orden_trabajo/filtrar/")
    */
    public function filtrarAdmin(Request $request)
    {     

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em =  $this->getDoctrine()->getManager(); 
        $q = "SELECT o FROM AppBundle:OrdenTrabajo o WHERE 1 = 1";
        $q .= " AND o.active = 1";
        $q .= " AND DATE(o.fechaSolicitado) BETWEEN :desde AND :hasta";

        if($request->get("estado") != ""){
            $q .= " AND o.estado = :estado";
        }

        if($request->get("zona") != ""){
            $q .= " AND o.zona = :zona";
        }

        if($request->get("direccion") != ""){
            $q .= " AND o.direccionTrabajo LIKE :direccion";
        }

        if($request->get("movil") != ""){
            $q .= " AND o.movilAsignado = :movil";
        }

        $query = $em->createQuery($q);   
        $query->setParameter("desde", $request->get("desde"));
        $query->setParameter("hasta", $request->get("hasta"));
        if($request->get("estado") != ""){
            $query->setParameter("estado", $request->get("estado"));
        }
        if($request->get("zona") != ""){
            $query->setParameter("zona", $request->get("zona"));
        }
        if($request->get("direccion") != ""){
            $query->setParameter("direccion", "%".$request->get("direccion")."%");
        }
        if($request->get("movil") != ""){
            $query->setParameter("movil", $request->get("movil"));
        }

        $ots = $query->getResult();
        $respuesta = array();
        foreach ($ots as $ot) $respuesta[] = $em->getRepository("AppBundle:OrdenTrabajo")->darInfo($ot, "admin");
        return new JsonResponse($respuesta);
    }



    /**
    * @Rest\Get("/api_bolsamax/admin/orden_trabajo/traer/{id}")
    */
    public function traerAdmin($id)
    {     

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);

        $respuesta = array();
        
        if($this->getUser()->getTipo() != "admin"){
                $respuesta["status"] = "error";
                $respuesta["data"] = [];
                return new JsonResponse($respuesta);
        }

        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($id);
        $respuesta["status"] = "ok";
        $respuesta["data"] = $em->getRepository("AppBundle:OrdenTrabajo")->darInfo2($ot, "admin");

        return new JsonResponse($respuesta);
    }        




      /**
    * @Rest\Post("/api_bolsamax/admin/orden_trabajo/actualizar/")
    */
    public function actualizarAdmin(Request $request)
    {     

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        // Validacion de fotos correctas
        if(count($request->get("fotos")) > 0){
            if(!$this->get("images_functions")->validarLote($request->get("fotos"))){
                return new JsonResponse("error_imagen");
            }
        }


        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($request->get("id"));
        $ot->setMovilAsignado(null);
        $ot->setEstado($request->get("estado"));
        $ot->setAsignado("BOLSAMAX");


        if($request->get("fecha") == ""){
            $fecha = new \DateTime("now");
        }else{
            $fecha = \DateTime::createFromFormat("d/m/Y H:i", $request->get("fecha"));
        }

        

        if($request->get("estado") == "Pendiente"){
            if($request->get("asignar_a_cliente") == "Si") $ot->setAsignado("cliente");
            $otl = new OrdenTrabajoLog();
            $otl->setOrdenTrabajo($ot);
            $otl->setEstado("Pendiente");
            $otl->setEmisor("BOLSAMAX");
            $otl->setComentarios($request->get("comentario"));
            $otl->setCantidadBolsones($request->get("cantidad_bolsones"));
            $otl->setBalizas($request->get("balizas"));
            $otl->setFecha($fecha);
            $em->persist($otl);
        }




        if($request->get("estado") == "En curso"){
            $movil = $em->getRepository("AppBundle:Usuario")->find($request->get("movil"));     
            $ot->setMovilAsignado($movil);
            $otl = new OrdenTrabajoLog();
            $otl->setOrdenTrabajo($ot);
            $otl->setEstado("En curso");
            $otl->setEmisor("BOLSAMAX");
            $otl->setComentarios($request->get("comentario"));
            $otl->setCantidadBolsones($request->get("cantidad_bolsones"));
            $otl->setBalizas($request->get("balizas"));
            $otl->setFecha($fecha);
            $otl->setMovilAsignado($movil);
            $em->persist($otl);
        }




        if($request->get("estado") == "Cumplido"){
            $otl = new OrdenTrabajoFinalizacion();
            $otl->setOrdenTrabajo($ot);
            $otl->setCantidadBolsones($request->get("cantidad_bolsones"));
            $otl->setMaterialUtilizado($request->get("material_utilizado"));
            $otl->setComentarios($request->get("comentario"));
            $otl->setBalizas($request->get("balizas"));
            $otl->setFecha($fecha);
            $em->persist($otl);
        }





        // Productos
        foreach ($request->get("productos") as $producto) {

            
            if($request->get("estado") == "Pendiente" || $request->get("estado") == "En curso"){
                $orden_trabajo_log_producto = new OrdenTrabajoLogProducto();
                $orden_trabajo_log_producto->setOrdenTrabajoLog($otl);
                $orden_trabajo_log_producto->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
                $em->persist($orden_trabajo_log_producto);
            }

            if($request->get("estado") == "Cumplido"){
                $orden_trabajo_finalizacion_producto = new OrdenTrabajoFinalizacionProducto();
                $orden_trabajo_finalizacion_producto->setOrdenTrabajoFinalizacion($otl);
                $orden_trabajo_finalizacion_producto->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
                $em->persist($orden_trabajo_finalizacion_producto);
            }


        }
        






        if(count($request->get("fotos")) > 0){
            $nombres = $this->get("images_functions")->subirLote($request->get("fotos"), "imagenes_ots");
            $em->flush();
            foreach ($nombres as $nombre) {
                if($request->get("estado") == "Cumplido"){
                    $otl_foto = new OrdenTrabajoFinalizacionFoto();
                    $otl_foto->setOrdenTrabajoFinalizacion($otl);
                }else{
                    $otl_foto = new OrdenTrabajoLogFoto();
                    $otl_foto->setOrdenTrabajoLog($otl);
                }
                $otl_foto->setFoto($nombre);   
                $em->persist($otl_foto);
            }
        }


        $em->flush();
        return new JsonResponse("ok");
    }    




    





     /**
    * @Rest\Post("/api_bolsamax/admin/orden_trabajo/crear/")
    */
    public function crearAdmin(Request $request)
    {     

        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository("AppBundle:Cliente")->find($request->get("cliente"));

        
        if( $request->get("numero") == "" ){
            $numero = $em->getRepository("AppBundle:OrdenTrabajo")->darUltimoNumero();
        }else{
            $numero = $request->get("numero");
        }


        $ot = new OrdenTrabajo();
        $ot->setCliente($cliente);
        $ot->setZona($em->getRepository("AppBundle:Zona")->find($request->get("zona")));
        $ot->setUsuario($em->getRepository("AppBundle:Usuario")->find($request->get("usuario")));
        $ot->setNumero($numero);
        $ot->setBolsones($request->get("bolsones"));
        $ot->setPrioridad($request->get("prioridad"));
        $ot->setDireccionTrabajo($request->get("direccion"));
        $ot->setComentariosIniciales($request->get("comentarios_iniciales"));
        $ot->setEstado("Pendiente");
        $ot->setAsignado("BOLSAMAX");
        $ot->setLatitud($request->get("latitud"));
        $ot->setLongitud($request->get("longitud"));

        // Fecha de trabajo: Si campo "fecha_creacion" viene vacio => fecha del sistema
        // Sino, pongo el que viene

        if($request->get("fecha") == ""){
            $fecha = new \DateTime("now");
        }else{
            $fecha = \DateTime::createFromFormat("d/m/Y H:i", $request->get("fecha"));
        }

        $ot->setFechaSolicitado($fecha);  

        $em->persist($ot);
        $em->flush();


        // Grabo Log
        $orden_trabajo_log = new OrdenTrabajoLog();
        $orden_trabajo_log->setOrdenTrabajo($ot);
        $orden_trabajo_log->setEstado("Pendiente");
        $orden_trabajo_log->setEmisor("cliente");
        $orden_trabajo_log->setComentarios("Se abre orden de trabajo");
        $orden_trabajo_log->setFecha($fecha);
        $em->persist($orden_trabajo_log);
        
        
        // Grabo productos
        foreach ($request->get("productos") as $producto) {
            $orden_trabajo_producto_solicitado = new OrdenTrabajoProductosSolicitados();
            $orden_trabajo_producto_solicitado->setOrdenTrabajo($ot);
            $orden_trabajo_producto_solicitado->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
            $em->persist($orden_trabajo_producto_solicitado);
        }


         // Incremento numero de OT
        if( $request->get("numero") == "" ){
            $config = $em->getRepository("AppBundle:Config")->find(1);  
            $config->setNumero( $numero + 1 ); 
        }

        $em->flush();



        return new JsonResponse("ok");



    }





       /**
    * @Rest\Get("/api_bolsamax/admin/orden_trabajo/eliminar/{id}")
    */
    public function eliminar($id)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($id);
        $ot->setActive(false);
        $em->flush();
        return new JsonResponse("ok");
    }  



   /**
    * @Rest\Get("/api_bolsamax/admin/orden_trabajo/dar_finalizacion/{orden_trabajo_id}")
    */
    public function darFinalizacion($orden_trabajo_id)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);

        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($orden_trabajo_id);
        $otf = $em->getRepository("AppBundle:OrdenTrabajo")->darFinalizacion($ot);
        $respuesta = array();
        $respuesta["numero"] = $ot->getNumero();
        $respuesta["fecha"] = $otf->getFecha()->format("Y-m-d")."T".$otf->getFecha()->format("H:i").":00"; //1968-11-16T00:00:00
        $respuesta["bolsones"] = $otf->getCantidadBolsones();
        $respuesta["comentarios"] = $otf->getComentarios();
        $respuesta["balizas"] = $otf->getBalizas();
        $respuesta["material_utilizado"] = $otf->getMaterialUtilizado();
        $respuesta["productos"] = $em->getRepository("AppBundle:OrdenTrabajoFinalizacion")->darIdsProductos($otf);
        return new JsonResponse($respuesta);
    }   



   /**
    * @Rest\Post("/api_bolsamax/admin/orden_trabajo/actualizar_finalizacion/")
    */
    public function actualizarFinalizacion(Request $request)
    {     
        if($this->getUser()->getTipo() != "admin") return new JsonResponse("error", 500);

        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($request->get("orden_id"));
        $otf = $em->getRepository("AppBundle:OrdenTrabajo")->darFinalizacion($ot);
        $fecha = \DateTime::createFromFormat("d/m/Y H:i", $request->get("fecha"));
        $otf->setFecha($fecha);
        $otf->setCantidadBolsones($request->get("bolsones"));
        $otf->setMaterialUtilizado($request->get("material_utilizado")); 
        $otf->setBalizas($request->get("balizas")); 
        $otf->setComentarios($request->get("comentarios"));   


        // Tareas:
        // 1- Las elimino:
        $productos_a_eliminar = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionProducto")->findBy(array("ordenTrabajoFinalizacion" => $otf->getId()));
        foreach ($productos_a_eliminar as $producto_a_eliminar) {
            $em->remove($producto_a_eliminar);
        }

        // 2- Las creo:
        foreach ($request->get("tareas") as $producto) {
            $orden_trabajo_finalizacion_producto = new OrdenTrabajoFinalizacionProducto();
            $orden_trabajo_finalizacion_producto->setOrdenTrabajoFinalizacion($otf);
            $orden_trabajo_finalizacion_producto->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
            $em->persist($orden_trabajo_finalizacion_producto);
        }

        $em->flush();
        return new JsonResponse("ok");
    }                  












































    // Movil


   /**
    * @Rest\Post("/api_bolsamax/movil/orden_trabajo/filtrar/")
    */
    public function filtrarMovil(Request $request)
    {     

        if($this->getUser()->getTipo() != "movil") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $ots = $em->getRepository("AppBundle:OrdenTrabajo")->findBy(array("movilAsignado" => $this->getUser()->getId(), "active" => true));    
        $respuesta = array();
        foreach ($ots as $ot){
            $una_respuesta = $em->getRepository("AppBundle:OrdenTrabajo")->darInfo($ot, "movil");
            $respuesta[] = $una_respuesta;
        }

        return new JsonResponse($respuesta);
    }




    /**
    * @Rest\Get("/api_bolsamax/movil/orden_trabajo/traer/{id}")
    */
    public function traerMovil($id)
    {     

        if($this->getUser()->getTipo() != "movil") return new JsonResponse("error", 500);
        $em = $this->getDoctrine()->getManager();
        $respuesta = array();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($id);
        
        if($ot->getMovilAsignado()->getId() != $this->getUser()->getId()){
            $respuesta["status"] = "error";
            $respuesta["data"] = $data;
            return $respuesta;
        }
        
        
        $data = $em->getRepository("AppBundle:OrdenTrabajo")->darInfo2($ot, "movil");

        $respuesta["status"] = "ok";
        $respuesta["data"] = $data;

        return new JsonResponse($respuesta);
    }









      /**
    * @Rest\Post("/api_bolsamax/movil/orden_trabajo/actualizar/")
    */
    public function actualizarMovil(Request $request)
    {     

        if($this->getUser()->getTipo() != "movil") return new JsonResponse("error", 500);
        // Validacion de fotos correctas
        if(count($request->get("fotos")) > 0){
            if(!$this->get("images_functions")->validarLote($request->get("fotos"))){
                return new JsonResponse("error_imagen");
            }
        }
        
        $em = $this->getDoctrine()->getManager();
        $ot = $em->getRepository("AppBundle:OrdenTrabajo")->find($request->get("id"));
       
       // Si la OT no es del movil, error
        if($ot->getMovilAsignado()->getId() != $this->getUser()->getId()){
            return new JsonResponse("error", 500);
        }


     


        

        if($request->get("estado") == "Pendiente"){
            $ot->setEstado("Pendiente");
            if($request->get("asignar_a_cliente") == "Si"){
                $ot->setAsignado("cliente");
                $ot->setMovilAsignado(null);
            }
            $otl = new OrdenTrabajoLog();
            $otl->setOrdenTrabajo($ot);
            $otl->setEstado("Pendiente");
            $otl->setEmisor("BOLSAMAX");
            $otl->setComentarios($request->get("comentario"));
            $otl->setBalizas($request->get("balizas"));
            $otl->setCantidadBolsones($request->get("cantidad_bolsones"));
            $otl->setFecha(new \DateTime("now"));
            if($request->get("asignar_a_cliente") == "Si"){
                $otl->setMovilAsignado(null);
            }else{
                $otl->setMovilAsignado($this->getUser());
            }
            $em->persist($otl);
        }




        if($request->get("estado") == "En curso"){
            $ot->setEstado("En curso");
            $otl = new OrdenTrabajoLog();
            $otl->setOrdenTrabajo($ot);
            $otl->setEstado("En curso");
            $otl->setEmisor("BOLSAMAX");
            $otl->setComentarios($request->get("comentario"));
            $otl->setBalizas($request->get("balizas"));
            $otl->setCantidadBolsones($request->get("cantidad_bolsones"));
            $otl->setFecha(new \DateTime("now"));
            if($request->get("asignar_a_cliente") == "Si"){
                $otl->setMovilAsignado(null);
            }else{
                $otl->setMovilAsignado($this->getUser());
            }
            $em->persist($otl);
        }




        if($request->get("estado") == "Cumplido"){
            $ot->setEstado("Cumplido");
            $ot->setMovilAsignado(null);
            $otl = new OrdenTrabajoFinalizacion();
            $otl->setOrdenTrabajo($ot);
            $otl->setCantidadBolsones($request->get("cantidad_bolsones"));
            $otl->setMaterialUtilizado($request->get("material_utilizado"));
            $otl->setComentarios($request->get("comentario"));
            $otl->setBalizas($request->get("balizas"));

            if($request->get("fecha_finalizacion") == ""){
                $fecha = new \DateTime("now");
            }else{
                $fecha = \DateTime::createFromFormat("d/m/Y H:i", $request->get("fecha_finalizacion"));
            }


            $otl->setFecha($fecha);
            $em->persist($otl);
        }




        // Productos
        foreach ($request->get("productos") as $producto) {
            
            if($request->get("estado") == "Pendiente" || $request->get("estado") == "En curso"){
                $orden_trabajo_log_producto = new OrdenTrabajoLogProducto();
                $orden_trabajo_log_producto->setOrdenTrabajoLog($otl);
                $orden_trabajo_log_producto->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
                $em->persist($orden_trabajo_log_producto);
            }

            if($request->get("estado") == "Cumplido"){
                $orden_trabajo_finalizacion_producto = new OrdenTrabajoFinalizacionProducto();
                $orden_trabajo_finalizacion_producto->setOrdenTrabajoFinalizacion($otl);
                $orden_trabajo_finalizacion_producto->setProducto($em->getRepository("AppBundle:Producto")->find($producto));
                $em->persist($orden_trabajo_finalizacion_producto);
            }

        }



        



         if(count($request->get("fotos")) > 0){
            $nombres = $this->get("images_functions")->subirLote($request->get("fotos"), "imagenes_ots");
            $em->flush();
            foreach ($nombres as $nombre) {        
                if($request->get("estado") == "Cumplido"){
                    $otl_foto = new OrdenTrabajoFinalizacionFoto();
                    $otl_foto->setOrdenTrabajoFinalizacion($otl);
                }else{
                    $otl_foto = new OrdenTrabajoLogFoto();
                    $otl_foto->setOrdenTrabajoLog($otl);
                }
                    
                $otl_foto->setFoto($nombre);   
                $em->persist($otl_foto);
            }
        }


        $em->flush();
        return new JsonResponse(1);

    }    






        /**
    * @Rest\Post("/api_bolsamax/common/orden_trabajo/dar_foto/")
    */
    public function darFoto(Request $request)
    { 


        //TO-DO: Validaciones de acceso
        $respuesta = "";
        $em = $this->getDoctrine()->getManager();

        if($request->get("modo") == "finalizacion"){
            $objeto = $em->getRepository("AppBundle:OrdenTrabajoFinalizacionFoto")->find($request->get("id"));
            $respuesta = $objeto->getFotoBase64(); 
        }


        if($request->get("modo") == "log"){
            $objeto = $em->getRepository("AppBundle:OrdenTrabajoLogFoto")->find($request->get("id"));
            $respuesta = $objeto->getFotoBase64(); 
        }

        
       
        return new JsonResponse($respuesta);

    }   
    





}
