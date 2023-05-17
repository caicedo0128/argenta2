<?php
/**
* Adminsitración del modulo operacion
* @version 1.0
* El constructor de esta clase es {@link operaciones()}
*/
require_once("class_operaciones_extended.php");
class operaciones{


    var $Database;
    var $ID;
    var $arrEstados = array("1"=>"VIGENTE","2"=>"CANCELADA", "3"=>"CREADA", "4"=>"CREADA POR CLIENTE", "5"=>"ANULADA", "6"=>"ENVIADA A VALIDACIÓN");
    var $arrEstadosFacturas = array("1"=>"ABIERTA","2"=>"FINALIZADA","3"=>"PAGO TOTAL FECHA PREVISTA","4"=>"PAGO PARCIAL FECHA PREVISTA","5"=>"PAGO TOTAL ANTICIPADO","6"=>"PAGO PARCIAL ANTICIPADO","7"=>"PAGO TOTAL POSTERIOR","8"=>"PAGO PARCIAL POSTERIOR");
    var $arrEstadosTransmisionFacturas = array("1"=>"INSCRIPCIÓN","2"=>"MANDATO","3"=>"ENDOSO","4"=>"INFORME PAGO","5"=>"PAGO");
    var $rutaArchivosFacturas = "./gallery/";
    var $rutaArchivosFacturasFisicas = "./gallery/facturas/";
    var $rutaArchivosDesembolsos = "./gallery/desembolsos/";
    var $rutaArchivosDesembolsosEnvio = "desembolsos/";

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "operacion":
                            $this->operacion();
                            break;
            case "saveOperacion":
                            $this->saveOperacion();
                            break;
            case "updateOperacion":
                            $this->updateOperacion();
                            break;
            case "operacionVigente":
                            $this->operacionVigente();
                            break;
            case "listOperaciones":
                            $this->listOperaciones();
                            break;
            case "eliminarOperacion":
                            $this->eliminarOperacion();
                            break;
            case "factura":
                            $this->factura();
                            break;                           
            case "saveFactura":
                            $this->saveFactura();
                            break;
            case "listFacturas":
                            $this->listFacturas();
                            break;
            case "eliminarFactura":
                            $this->eliminarFactura();
                            break;
            case "reporteCliente":
                            $this->reporteCliente();
                            break;
            case "reporteClienteDetallado":
                            $this->reporteClienteDetallado();
                            break;
            case "reporteLiquidacionFacturas":
                            $this->reporteLiquidacionFacturas();
                            break;
            case "reporteInversionista":
                            $this->reporteInversionista();
                            break;
            case "reporteEjecutivo":
                            $this->reporteEjecutivo();
                            break;
            case "desembolso":
                            $this->desembolso();
                            break;
            case "saveDesembolso":
                            $this->saveDesembolso();
                            break;
            case "listDesembolsos":
                            $this->listDesembolsos();
                            break;
            case "eliminarDesembolso":
                            $this->eliminarDesembolso();
                            break;
            case "cerrarOperacion":
                            $this->cerrarOperacion();
                            break;
            case "reportesOperacion":
                            $this->reportesOperacion();
                            break;
            case "guardarReporteOperacion":
                            $this->guardarReporteOperacion();
                            break;
            case "enviarReporteOperacion":
                            $this->enviarReporteOperacion();
                            break;
            case "procesarCargaFacturas":
                            $this->procesarCargaFacturas();
                            break;
            case "eliminarTodasFacturas":
                            $this->eliminarTodasFacturas();
                            break;
            case "formActualizarComision":
                            $this->formActualizarComision();
                            break;
            case "updateComision":
                            $this->updateComision();
                            break;
            case "updateFacturacion":
                            $this->updateFacturacion();
                            break;
            case "inversionista":
                            $this->inversionistaForm();
                            break;
            case "saveInversionista":
                            $this->saveInversionista();
                            break;
            case "listInversionistas":
                            $this->listInversionistas();
                            break;
            case "eliminarInversionista":
                            $this->eliminarInversionista();
                            break;
            case "reporteInversionistaParticipacion":
                            $this->reporteInversionistaParticipacion();
                            break;
            case "seguimiento":
                            $this->seguimientoForm();
                            break;
            case "saveSeguimiento":
                            $this->saveSeguimiento();
                            break;
            case "listSeguimientos":
                            $this->listSeguimientos();
                            break;
			case "BuscadorOperaciones":
                            $this->BuscadorOperaciones();
                            break;
			case "abrirOperacion":
                            $this->abrirOperacion();
                            break;
			case "operacionValidacion":
                            $this->operacionValidacion();
                            break;
			case "simulador":
                            $this->simulador();
                            break;
			case "verSimulacion":
                            $this->verSimulacion();
                            break;
			case "actualizarGMF":
                            $this->actualizarGMF();
                            break;
			case "reporteClienteDetalladoFacturacion":
                            $this->reporteClienteDetalladoFacturacion();
                            break;
            case "facturaLeerXML":
                            $this->facturaLeerXML();
                            break;                             
    	}
    }

	/**
     * Funciòn para leer factura XML
     */
    function facturaLeerXML() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $success=false;
		if ($_FILES){
			$ftmp = $_FILES['file_factura_xml']['tmp_name'];
			if ($ftmp != ""){
				$nombre_archivo = "file-".date("mdhis")."-".$_FILES['file_factura']['name'];
				@chmod($this->rutaArchivosFacturasFisicas, 0777);
				copy($ftmp, $this->rutaArchivosFacturasFisicas.$nombre_archivo);
				@chmod($this->rutaArchivosFacturasFisicas.$nombre_archivo, 0777);
				$factura->archivo = $nombre_archivo;
				$success=true;
			}
		}        
        
        
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }    

    
	/**
     * Funciòn para actualizar el GMF
     */
    function actualizarGMF() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $success = true;
        $msj = "";
        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);
        $operacion->gmf_manual=$_REQUEST["valor"];
        $operacion->Save();
        
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }    

	/**
     * Funciòn para ver la simulacion
     */
    function verSimulacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

 	/**
     * Funcionn para ver el simulador
     */
    function simulador(){

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");

        include("./modules/operaciones/templates/simulador.php");
    }

	/**
     * Funciòn para pasar la operación a validacion por argenta
     */
    function operacionValidacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $inversionista = new operacion_inversionista();
        $factura = new operacion_factura();

        $success = true;
        $msj = "";
        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

		//DETERMINAMOS SI HAY FACTURAS
        $tieneFacturas = $factura->tieneFacturasSoporte($idOperacion);
        if (!$tieneFacturas){
            $msj .= "La operacion no ha registrado facturas o hay facturas que no tienen soporte. Verifique.<br/>";
            $success = false;
        }

		if ($success){
			$operacion->tipo_operacion = 1;
			$operacion->estado = 6;
			$observaciones = "Fecha:". date("Y-m-d H:i:s") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones']."<hr/>";
			$operacion->observaciones = $observaciones . $operacion->observaciones;
			$operacion->Save();
        }

        $jsondata['Message'] = $msj;
        $jsondata['IdOperacion'] = $operacion->id_operacion;
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para abrir una operacion
     */
    function abrirOperacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["idOperacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        $success = true;
        $msj = "";

        //CERRAMOS LA OPERACION
		$observaciones = "Fecha:". date("Y-m-d H:i:s") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".($_POST['observaciones']==""?"RE-ABRE OPERACIÓN SIN OBSERVACIONES":$_POST['observaciones'])."<hr/>";
        $operacion->observaciones = $observaciones . $operacion->observaciones;
		$operacion->estado = 1;
		$operacion->Save();

        $jsondata['Message'] = $msj;
        $jsondata['Success'] = $success;
        $jsondata['IdOperacion'] = $idOperacion;

        echo json_encode($jsondata);
        exit;
    }

 	/**
     * FunciÃ²n para obtener el buscador operaciones
     */
    function BuscadorOperaciones(){

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        //OBTENEMOS LOS ESTADOS DE LA OPERACION
        $arrEstados = array("1"=>"VIGENTE","2"=>"CANCELADA");

        $fechaInicialBuscador = restar_dias_fecha(date("Y-m-d"),60);

        include("./modules/operaciones/templates/reporte_operaciones.php");
    }

	/**
     * Funciòn para obtener el listado de seguimientos
     */
    function listSeguimientos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["id_operacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $seguimiento = new operacion_seguimiento();

        $rsSeguimientos = $seguimiento->getSeguimientoPorOperacion($idOperacion);

        include("./modules/operaciones/templates/listado_seguimientos.php");
    }

    /**
     * Funciòn para guardar informacion seguimiento
     */
    function saveSeguimiento() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $seguimiento = new operacion_seguimiento();

        $idSeguimientoOperacion = $_REQUEST["id_seguimiento_operacion"];
        $idOperacion = $_POST['id_operacion'];

        $loadReg1 = $seguimiento->load("id_operacion_seguimiento=".$idSeguimientoOperacion);

        if ($idSeguimientoOperacion == 0){
        	$seguimiento->fecha = date("Y-m-d H:i:s");
        }

        $seguimiento->id_operacion = $idOperacion;
        $seguimiento->contacto = $_POST['contacto'];
        $seguimiento->observaciones = $_POST['observaciones'];
		$seguimiento->id_usuario = $_SESSION["id_user"];
        $seguimiento->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un seguimiento
     */
    function seguimientoForm() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/textarea.php");

        $seguimiento = new operacion_seguimiento();
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion"];
        $idSeguimientoOperacion = $_REQUEST["id_seguimiento_operacion"];

        $loadReg = $seguimiento->load("id_operacion_seguimiento=".$idSeguimientoOperacion);

        include("./modules/operaciones/templates/seguimiento.php");

    }

	/**
     * Funciòn para obtener el listado de inversionistas
     */
    function reporteInversionistaParticipacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["id_operacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $inversionista = new operacion_inversionista();

        $rsInversionistas = $inversionista->getReporteInversionistasPorOperacion($idOperacion);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        include("./modules/operaciones/templates/reporte_inversionistas_participacion.php");
    }

	/**
     * Funciòn para obtener el listado de inversionistas
     */
    function listInversionistas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["id_operacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $inversionista = new operacion_inversionista();

        $rsInversionistas = $inversionista->getInversionistasPorOperacion($idOperacion);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        include("./modules/operaciones/templates/listado_inversionistas.php");
    }

    /**
     * Funciòn para guardar informacion inversionista
     */
    function saveInversionista() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $inversionista = new operacion_inversionista();

        $idInversionistaOperacion = $_REQUEST["id_inversionista_operacion"];
        $idOperacion = $_POST['id_operacion'];

        $loadReg1 = $inversionista->load("id_operacion_inversionista=".$idInversionistaOperacion);

        $loadReg2 = $operacion->load("id_operacion=".$idOperacion);

        $inversionista->id_operacion = $idOperacion;
        $inversionista->id_inversionista = $_POST['id_inversionista'];
        $inversionista->valor_inversion =$_POST['valor_inversion'];

        //CALCULAMOS EL PORCENTAJE DE PARTICIPACION
        $porcentajeParticipacion = round((($inversionista->valor_inversion / $operacion->giro_antes_gmf)*100),6);
        $inversionista->porcentaje_participacion = $porcentajeParticipacion;

        $inversionista->Save();

        //ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
        $operacion->actualizarPorcentajesParticipacionInversionista($inversionista->id_operacion);

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un inversionista
     */
    function inversionistaForm() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();
        $inversionista = new operacion_inversionista();
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion"];
        $idInversionistaOperacion = $_REQUEST["id_inversionista_operacion"];

        $loadReg = $inversionista->load("id_operacion_inversionista=".$idInversionistaOperacion);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        //OBTENEMOS EL VALOR DE LOS INVERSIONISTAS
        $totalValoresInversionista = $inversionista->getTotalInversion($idOperacion);

        $valorValidacionInversion = $operacion->giro_antes_gmf;
        if ($totalValoresInversionista>0){
            $valorValidacionInversion = ($operacion->giro_antes_gmf - $totalValoresInversionista) + $inversionista->valor_inversion;
       	}

        include("./modules/operaciones/templates/inversionista.php");

    }

    /**
     * Funciòn para eliminar inversionista
     */
    function eliminarInversionista() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $inversionista = new operacion_inversionista();

        $idInversionista = $_REQUEST["id_inversionista_operacion"];

        $loadReg1 = $inversionista->load("id_operacion_inversionista=".$idInversionista);

        $idOperacion = $inversionista->id_operacion;

        $inversionista->Delete();

        //ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
        $operacion->actualizarPorcentajesParticipacionInversionista($inversionista->id_operacion);

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funciòn para actualizar informacion de facturacion
     */
    function updateFacturacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $arrOperaciones = $_REQUEST["id_operacion"];
        foreach($arrOperaciones as $key=>$value){

            $idOperacion = $value;

            $operacion = new operacion();
            $loadReg1 = $operacion->load("id_operacion=".$idOperacion);
            $operacion->facturado = 1;
            $operacion->fecha_facturacion = $_REQUEST["fecha_facturacion"];
            $operacion->Save();
        }

        $jsondata['Message'] = "El proceso se realizo con exito.";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para eliminar facturas
     */
    function eliminarTodasFacturas() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();

        $idOperacion = $_REQUEST["id_operacion"];

        $strSQL = "DELETE FROM operacion_factura WHERE estado = 1 AND id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        //ACTUALIZAMOS LOS TOTALES
        $operacion->actualizarTotalesOperacion($idOperacion);

        //ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
        $operacion->actualizarPorcentajesParticipacionInversionista($idOperacion);

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funciòn para procesar carga de facturas
     */
    function procesarCargaFacturas() {

        global $db,$id,$appObj,$LANG;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $errores = "";

        //CARGAMOS EL ARCHIVO
        $ftmp = $_FILES['file']['tmp_name'];
        $nombreArchivo = date("YmdHis")."-".$_FILES['file']['name'];
        $size= $_FILES['file']['size'];

        copy($ftmp, $this->rutaArchivosFacturas.$nombreArchivo);
        @chmod($this->rutaArchivosFacturas.$nombreArchivo, 0777);

        //CREAMOS UN ARREGLO DEL ARCHIVO
        $arrArchivo = file($this->rutaArchivosFacturas.$nombreArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        unlink($this->rutaArchivosFacturas.$nombreArchivo);

        //OBTENEMOS DATOS DE LA OPERACION
        $idOperacion = $_POST['id_operacion_cargue'];
        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        $porcentajeDescuento = $operacion->porcentaje_descuento;
        $tasaInversionista = $operacion->tasa_inversionista;
        $factor = $operacion->factor;
        $otrosOperacion = $operacion->valor_otros_operacion;
        $fechaInicial = $operacion->fecha_operacion;
        $aplicaImpuesto = $operacion->aplica_impuesto;

        $cargaPorLotes = count($arrArchivo);

        for ($contador=1;$contador<$cargaPorLotes;$contador++){

            $arrLinea = split(";",trim($arrArchivo[$contador]));

            //DETERMINAMOS SI EL ARCHIVO BIENE SEPARADO CON (,) O (;)
            if (count($arrLinea)<=1)
                $arrLinea = split(",",trim($arrArchivo[$contador]));

            if ($arrLinea[0] != "" && $arrLinea[1] != "" && $arrLinea[2] != "" && $arrLinea[3] != "" && $arrLinea[4] != "" && $arrLinea[5] != "" && $arrLinea[6] != "" && $arrLinea[7] != "")
            {

				try
				{

					$arrFechaEmision = split("/",trim($arrLinea[0]));
					if (count($arrFechaEmision)<=1)
						$arrFechaEmision = split("-",trim($arrLinea[0]));

					$arrFechaVencimiento = split("/",trim($arrLinea[1]));
					if (count($arrFechaVencimiento)<=1)
						$arrFechaVencimiento = split("-",trim($arrLinea[1]));

					$arrFechaPago = split("/",trim($arrLinea[2]));
					if (count($arrFechaPago)<=1)
						$arrFechaPago = split("-",trim($arrLinea[2]));

					$factura = new operacion_factura();
					$loadReg1 = $factura->load("id_operacion_factura=0");
					$factura->id_operacion = $idOperacion;
					$factura->fecha_emision = $arrFechaEmision[0] . "-" . $arrFechaEmision[1] . "-". $arrFechaEmision[2];
					$factura->fecha_vencimiento = $arrFechaVencimiento[0] . "-" . $arrFechaVencimiento[1] . "-". $arrFechaVencimiento[2];
					$factura->fecha_pago = $arrFechaPago[0] . "-" . $arrFechaPago[1] . "-". $arrFechaPago[2];
					$factura->prefijo = $arrLinea[3];
					$factura->num_factura = $arrLinea[4];
					$factura->aplica_otros = ($arrLinea[5]=="S"?1:2);
					$factura->valor_neto = $arrLinea[6];
					$factura->valor_bruto = $arrLinea[7];

					//REALIZAMOS CALCULOS
					$arrDiasDiferencia = date_diff_custom($fechaInicial, $factura->fecha_pago);
					$diasDiferencia = $arrDiasDiferencia["d"];

					$factura->valor_futuro = round(($factura->valor_neto * $porcentajeDescuento) / 100);
					$factura->descuento_total = round(((($diasDiferencia * $factor) / 100) / 30) * $factura->valor_futuro);
					$potenciaMargen = pow(1 + ($tasaInversionista / 100),($diasDiferencia / 365));
					$factura->margen_inversionista = round($factura->valor_futuro-($factura->valor_futuro / $potenciaMargen));
					$factura->margen_argenta = $factura->descuento_total - $factura->margen_inversionista;
					$factura->iva_fra_asesoria = round(($factura->margen_argenta * 19) / 100) ;
					$factura->fra_argenta = $factura->margen_argenta + $factura->iva_fra_asesoria;
					if ($factura->aplica_otros == 2){
						$otrosOperacion = 0;
					}
					$factura->giro_antes_gmf = round($factura->valor_futuro - $factura->descuento_total - $otrosOperacion);
					$factura->gmf = round(($factura->giro_antes_gmf * 0.3984) / 100);
					if ($aplicaImpuesto == 2)
						$factura->gmf = 0;
					$factura->valor_giro_final = $factura->giro_antes_gmf - $factura->gmf;
					$factura->id_reliquidacion = 0;
					$factura->descuento_total_reli = $factura->descuento_total;
					$factura->margen_argenta_reli = $factura->margen_argenta;
					$factura->iva_fra_asesoria_reli = $factura->iva_fra_asesoria;
					$factura->fra_argenta_reli = $factura->fra_argenta;
					$factura->estado = 1;
					$factura->fecha_registro = date("Y-m-d");
					$factura->Save();
                }
                catch(Exception $e){
                	$errores .= "<br>Error en linea ". $contador . " Datos:".$arrLinea;
                }
            }
            else{
            	$errores .= "<br>Error en linea ". $contador . " Datos:".$arrLinea;
            }
        }

        //ACTUALIZAMOS LOS TOTALES
        $operacion->actualizarTotalesOperacion($idOperacion);

        //ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
        $operacion->actualizarPorcentajesParticipacionInversionista($idOperacion);

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Errores'] = $errores;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    function enviarReporteOperacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./utilities/class_send_mail.php");
        require_once("./modules/clientes/class_clientes.php");

        //INSTANCIAMOS CLASES
        $desembolso = new operacion_desembolsos();
        $operacion = new operacion();
        $sendMail = new sendMail();
        $emisor = new clientes();
        $pagador = new clientes();

        $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
        $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
        $subjectMail = $_REQUEST["__subjectMail"];
        $toNameMail = $_REQUEST["__toNameMail"];
        $toEmail = $_REQUEST["__toEmailMail"];
        $template = $_REQUEST["__template"];
        $idOperacion = $_REQUEST["__option1"];
        $observaciones = ($_REQUEST["__dataMail"] != ""?$_REQUEST["__dataMail"]:"N/D");

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);


        //DETERMINAMOS QUE TEMPLATE VA A ENVIAR
        if ($template == "reporteCliente")
            $templateMail = "mailOperacion";
        else if ($template == "reporteClienteDetallado" || $template=="reporteClienteDetalladoFacturacion")
            $templateMail = "mailOperacionDetalle";
        else if ($template == "reporteInversionista")
            $templateMail = "mailOperacionInversionista";
        else if ($template == "reporteEjecutivo")
            $templateMail = "mailOperacionComercial";

        //ENVIAMOS EL CORREO
        $arrAttach = array("operaciones/reporte.pdf"=>"Reporte PDF");

        //DETERMINAMOS SI ADJUNTAMOS EL DESEMBOLSO
        if ($template == "reporteClienteDetallado" &&  $_REQUEST["__option2"] == "S"){
            //OBTENEMOS LOS DESEMBOLSOS
            $rsDesembolsos = $desembolso->getDesembolsosPorOperacion($idOperacion);
            $i=1;
            while (!$rsDesembolsos->EOF){
                $arrAttach += array($this->rutaArchivosDesembolsosEnvio."/".$rsDesembolsos->fields["archivo_desembolso"]=>"Desembolso " . $i);
                $i++;
                $rsDesembolsos->MoveNext();
            }
        }

        $arrVarsReplace = array("NAME"=>$toNameMail,"FECHA_OPERACION"=>$operacion->fecha_operacion,"EMISOR"=>$emisor->razon_social,"PAGADOR"=>$pagador->razon_social, "OBSERVACIONES"=>$observaciones);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,$templateMail,$arrAttach,$arrVarsReplace);

        $jsondata['Message'] = "test";
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }

    function guardarReporteOperacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./utilities/pdf/tcpdf.php");

        $dataMail = $_REQUEST["__dataMail"];

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set default header data
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $dataMail, 0, 1, 0, true, '', true);

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output(__DIR__ . '../../../gallery/operaciones/reporte.pdf', 'F');

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para obtener el listado de reportes operacion
     */
    function reportesOperacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["id_operacion"];

        include("./modules/operaciones/templates/reportes.php");
    }

    /**
     * Funciòn para cerrar una operacion
     */
    function cerrarOperacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["idOperacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        $success = true;
        $msj = "";

        //DETERMINAMOS SI HAY FACTURAS Y ESTÁN TODAS RELIQUIDADAS
        $tieneTodasFacturasReliquidadas = $operacion->tieneFacturasReliquidadas($idOperacion);
        if (!$tieneTodasFacturasReliquidadas){
            $msj .= "La operacion aun tiene facturas sin reliquidar. Verifique.<br/>";
            $success = false;
        }

        //DETERMINAMOS SI HAY DESEMBOLSOS
        $tieneDesembolsos = $operacion->tieneDesembolsos($idOperacion);
        if (!$tieneDesembolsos){
            $msj .= "La operacion no ha registrado desembolsos. Verifique.<br/>";
            $success = false;
        }

        //DETERMINAMOS SI TODAS LAS RELIQUIDACIONES ESTAN FINALIZADAS
        $tieneReliquidacionesFinalizadas = $operacion->tieneReliquidacionesFinalizadas($idOperacion);
        if (!$tieneReliquidacionesFinalizadas){
            $msj .= "Hay reliquidaciones que no han finalizado. Verifique.<br/>";
            $success = false;
        }

        //CERRAMOS LA OPERACION
        if ($success){
            $operacion->estado = 2;
            $operacion->Save();
        }

        $jsondata['Message'] = $msj;
        $jsondata['Success'] = $success;
        $jsondata['IdOperacion'] = $idOperacion;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para obtener el sumario de la operacion
     */
    function sumarioOperacion($idOperacion = 0){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");


        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $clientes = new clientes();


        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);
        $loadReg2 = $clientes->load("id_cliente=".$operacion->id_ejecutivo);

        include("./modules/operaciones/templates/sumario_operacion.php");


    }

    /**
     * Funciòn para obtener el listado de desembolsos
     */
    function listDesembolsos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $desembolso = new operacion_desembolsos();

        $idOperacion = $_REQUEST["id_operacion"];

        $rsDesembolsos = $desembolso->getDesembolsosPorOperacion($idOperacion);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        include("./modules/operaciones/templates/listado_desembolsos.php");


    }

    /**
     * Funciòn para guardar informacion desembolso
     */
    function saveDesembolso() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./utilities/class_send_mail.php");
		require_once("./modules/clientes/class_clientes.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();
        $desembolso = new operacion_desembolsos();
        $cliente = new clientes();

        $idDesembolso = $_REQUEST["id_desembolso"];
        $idTercero = $_REQUEST["id_tercero_mail"];
        $idOperacion = $_POST['id_operacion'];

        $loadReg1 = $desembolso->load("id_desembolso=".$idDesembolso);
        $loadReg2 = $cliente->load("id_cliente=".$idTercero);

        $desembolso->id_operacion = $idOperacion;
        $desembolso->tercero = null;
        $desembolso->id_tercero = null;
        if ($_POST['otro'] == 1){
        	$desembolso->tercero = $_POST['tercero'];
        }
        else if ($_POST['otro'] == 2){
        	$desembolso->id_tercero = $_POST['id_tercero'];
        }

        $desembolso->fecha_desembolso = $_POST['fecha_desembolso'];
        $desembolso->valor = $_POST['valor'];
        $desembolso->nro_cuenta = $_POST['nro_cuenta'];
        $desembolso->tipo_cuenta = $_POST['tipo_cuenta'];
        $desembolso->banco = $_POST['banco'];
        $desembolso->tipo_registro = $_POST['tipo_registro'];
        $desembolso->id_reliquidacion = $_POST['id_reliquidacion'];
        $desembolso->estado = 1; //CREADO

		//CARGAMOS EL ARCHIVO
		if ($_FILES){
			$ftmp = $_FILES['file_desembolso']['tmp_name'];
			if ($ftmp != ""){
				$nombre_archivo = "file-".$_FILES['file_desembolso']['name'];
				@chmod($this->rutaArchivosDesembolsos, 0777);
				copy($ftmp, $this->rutaArchivosDesembolsos.$nombre_archivo);
				@chmod($this->rutaArchivosDesembolsos.$nombre_archivo, 0777);
				$desembolso->archivo_desembolso = $nombre_archivo;
			}

			$ftmp = $_FILES['file_ofac']['tmp_name'];
			if ($ftmp != ""){
				$nombre_archivo = "file-".$_FILES['file_ofac']['name'];
				@chmod($this->rutaArchivosDesembolsos, 0777);
				copy($ftmp, $this->rutaArchivosDesembolsos.$nombre_archivo);
				@chmod($this->rutaArchivosDesembolsos.$nombre_archivo, 0777);
				$desembolso->archivo_ofac = $nombre_archivo;
			}
		}

        $desembolso->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['IdDesembolso'] = $desembolso->id_desembolso;
        $jsondata['Success'] = true;

        //SI ESTA CREANDO SE ENVIA CORREO
        if ($idDesembolso==0){
			$fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
			$fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
			$subjectMail = "Argenta - Confirmación registro de desembolso - Operación:".$idOperacion;
			$templateMail = "mailDesembolso";
			$arrVarsReplace = array("OPERACION"=>$idOperacion,"NAME"=>$cliente->representante_legal,"VALOR_DESEMBOLSO"=>formato_moneda($desembolso->valor));
			$toEmail = $cliente->correo_personal;
			//$toEmail = "andres.jap@gmail.com;eleyva@argentaestructuradores.com";
			$success = $sendMail->enviarMail($fromName,$fromEmail,$cliente->representante_legal,$toEmail,$subjectMail,$templateMail,array(),$arrVarsReplace);
		}

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un desembolso
     */
    function desembolso() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/filebox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/clientes/class_clientes.php");
        require_once("./modules/reliquidaciones/class_reliquidaciones.php");

        $operacion = new operacion();
        $clientes = new clientes();
        $desembolso = new operacion_desembolsos();
        $reliquidacion = new operacion_reliquidacion();

        $idOperacion = $_REQUEST["id_operacion"];
        $idDesembolso = $_REQUEST["id_desembolso"];

        $loadReg = $desembolso->load("id_desembolso=".$idDesembolso);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS LOS PAGADORES
        $arrTerceros = $clientes->obtenerClientesPorTipoTercero(1);

        $total = $desembolso->totalDesembolso($idOperacion);
        $valorGiroFinal = $operacion->valor_giro_final;
        $disponible = $valorGiroFinal - $total;

        //OBTENEMOS LAS RELIQUIDACION DE LA OPERACION
        $arrReliquidacion = $reliquidacion->obtenerArregloReliquidacionesPorOperacion($idOperacion);

        include("./modules/operaciones/templates/desembolso.php");

    }

    /**
     * Funciòn para eliminar desembolso
     */
    function eliminarDesembolso() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $desembolso = new operacion_desembolsos();

        $idDesembolso = $_REQUEST["id_desembolso"];

        $loadReg1 = $desembolso->load("id_desembolso=".$idDesembolso);

        $desembolso->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funciòn para obtener el reporte del ejecutivo
     */
    function reporteEjecutivo(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS Y DIAS DE PLAZO
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$operacion->fecha_operacion);

        include("./modules/operaciones/templates/reporte_ejecutivo.php");
    }

    /**
     * Funciòn para obtener el reporte de cliente
     */
    function reporteCliente(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");

        $idOperacion = $_REQUEST["id_operacion"];
        $esReporte = $_REQUEST["es_reporte"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS Y DIAS DE PLAZO
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$operacion->fecha_operacion);

        include("./modules/operaciones/templates/reporte_cliente.php");
    }

    /**
     * Funciòn para obtener el reporte de liquidacion facturas
     */
    function reporteLiquidacionFacturas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");

        $idOperacion = $_REQUEST["id_operacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS Y DIAS DE PLAZO
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$operacion->fecha_operacion);

        //FACTURAS
        $arrDetalleFacturas = $factura->getFacturasPorOperacion($idOperacion);

        include("./modules/operaciones/templates/reporte_liquidacion_facturas.php");
    }
    
	/**
     * Funciòn para obtener el reporte de cliente detallado
     */
    function reporteClienteDetalladoFacturacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");

        $idOperacion = $_REQUEST["id_operacion"];
        $esReporte = $_REQUEST["es_reporte"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS Y DIAS DE PLAZO
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$operacion->fecha_operacion);

        //FACTURAS
        $arrDetalleFacturas = $factura->getFacturasPorOperacion($idOperacion);

        include("./modules/operaciones/templates/reporte_cliente_detallado_facturacion.php");
    }
    
    /**
     * Funciòn para obtener el reporte de cliente detallado
     */
    function reporteClienteDetallado(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");

        $idOperacion = $_REQUEST["id_operacion"];
        $esReporte = $_REQUEST["es_reporte"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS Y DIAS DE PLAZO
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$operacion->fecha_operacion);

        //FACTURAS
        $arrDetalleFacturas = $factura->getFacturasPorOperacion($idOperacion);

        include("./modules/operaciones/templates/reporte_cliente_detallado.php");
    }



    /**
     * Funciòn para obtener el reporte de inversionista
     */
    function reporteInversionista(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes.php");

        $idOperacion = $_REQUEST["id_operacion"];
        $esReporte = $_REQUEST["es_reporte"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();
        $inversionista = new clientes();
        $desembolso = new operacion_desembolsos();

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS EL INVERSIONISTA
        $loadReg4 = $inversionista->load("id_cliente=".$operacion->id_inversionista);

        //OBTENEMOS TODOS LOS TITULOS Y DIAS DE PLAZO
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$operacion->fecha_operacion);

        //OBTENEMOS LOS DESEMBOLSOS
        $rsDesembolsos = $desembolso->getDesembolsosPorOperacion($idOperacion);

        include("./modules/operaciones/templates/reporte_inversionista.php");
    }


    /**
     * Funciòn para obtener el listado de facturas
     */
    function listFacturas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $idOperacion = $_REQUEST["id_operacion"];

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();

        $rsFacturas = $factura->getFacturasPorOperacion($idOperacion);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        include("./modules/operaciones/templates/listado_facturas.php");
    }

    /**
     * Funciòn para guardar informacion factura
     */
    function saveFactura() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./modules/clientes/class_clientes.php");

		//INSTANCIAMOS CLASES
		$operacion = new operacion();
		$factura = new operacion_factura();
		$resolucion1 = new clientes_res_facturas();
		$resolucion2 = new clientes_res_facturas();

		$idFactura = $_REQUEST["id_factura"];
		$idOperacion = $_POST['id_operacion'];
		$numFactura = $_POST['num_factura'];
		$prefijo = $_POST['prefijo'];

		$loadReg1 = $factura->load("id_operacion_factura=".$idFactura);
		$loadReg1 = $operacion->load("id_operacion=".$idOperacion);

		$permiteGuardarFactura = false;
		//OBTENEMOS DATOS DE LA RESOLUCION1
		$loadReg = $resolucion1->load("registro=1 AND id_cliente=".$operacion->id_emisor);
		if (($numFactura >= $resolucion1->fac_inicial && $numFactura <= $resolucion1->fac_final) && $prefijo == $resolucion1->prefijo){
			$permiteGuardarFactura = true;
		}

		//OBTENEMOS DATOS DE LA RESOLUCION2
		$loadReg = $resolucion2->load("registro=2 AND id_cliente=".$operacion->id_emisor);
		if (($numFactura >= $resolucion2->fac_inicial && $numFactura <= $resolucion2->fac_final) && $prefijo == $resolucion2->prefijo){
			$permiteGuardarFactura = true;
        }

        if ($permiteGuardarFactura){

			$factura->id_operacion = $idOperacion;
			$factura->num_factura = $numFactura;
			$factura->prefijo = $prefijo;
			$factura->prefijo_rnd =  $_POST['prefijo_rnd'];
			$factura->cufe = $_POST['cufe'];
			$factura->emisor_xml = utf8_decode($_POST['emisor_xml']);
			$factura->identificacion_emisor = $_POST['identificacion_emisor'];
			$factura->pagador_xml = utf8_decode($_POST['pagador_xml']);
			$factura->identificacion_pagador = $_POST['identificacion_pagador'];
			$factura->fecha_pago =$_POST['fecha_pago'];
			$factura->fecha_emision =$_POST['fecha_emision'];
			$factura->fecha_vencimiento =$_POST['fecha_vencimiento_factura'];
			$factura->valor_neto = $_POST['valor_neto'];
			$factura->valor_bruto = $_POST['valor_bruto'];
			$factura->valor_futuro = $_POST['valor_futuro'];
			$factura->descuento_total = $_POST['descuento_total'];
			$factura->margen_inversionista = $_POST['margen_inversionista'];
			$factura->margen_argenta = $_POST['margen_argenta'];
			$factura->iva_fra_asesoria = $_POST['iva_fra_asesoria'];
			$factura->fra_argenta = $_POST['fra_argenta'];
			$factura->giro_antes_gmf = $_POST['giro_antes_gmf'];
			$factura->gmf = $_POST['gmf']; //ESTE ES UN VALOR DE LA OPERACION NO POR FACTURA
			$factura->valor_giro_final = $_POST['valor_giro_final'];
			$factura->id_reliquidacion = 0;
			$factura->descuento_total_reli = $_POST['descuento_total'];
			$factura->margen_argenta_reli = $_POST['margen_argenta'];
			$factura->iva_fra_asesoria_reli = $_POST['iva_fra_asesoria'];
			$factura->fra_argenta_reli = $_POST['fra_argenta'];
			$factura->aplica_otros = $_POST['aplica_otros'];
			$factura->porcentaje_descuento = $_POST['porcentaje_descuento_radian'];

			//CARGAMOS EL ARCHIVO
			if ($_FILES){
				$ftmp = $_FILES['file_factura']['tmp_name'];
				if ($ftmp != ""){
					$nombre_archivo = "file-".date("mdhis")."-".$_FILES['file_factura']['name'];
					@chmod($this->rutaArchivosFacturasFisicas, 0777);
					copy($ftmp, $this->rutaArchivosFacturasFisicas.$nombre_archivo);
					@chmod($this->rutaArchivosFacturasFisicas.$nombre_archivo, 0777);
					$factura->archivo = $nombre_archivo;
				}
			}

			if ($idFactura == 0){
				$factura->estado = 1;
				$factura->fecha_registro = date("Y-m-d");
			}

			$factura->Save();

			//ACTUALIZAMOS LOS TOTALES
			$operacion->actualizarTotalesOperacion($factura->id_operacion);

			//ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
			$operacion->actualizarPorcentajesParticipacionInversionista($factura->id_operacion);

			$jsondata['Message'] = utf8_encode("Transacción exitosa. Espere por favor...");
			$jsondata['Success'] = true;
        }
        else{
        	$jsondata['Message'] = utf8_encode("Al parecer está registrando facturas que no coinciden con la información de resolución en el emisor. Verifique");
			$jsondata['Success'] = false;
        }

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un factura
     */
    function factura() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/filebox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./modules/clientes/class_clientes.php");

        $factura = new operacion_factura();
        $operacion = new operacion();
        $cliente = new clientes();

        $idOperacion = $_REQUEST["id_operacion"];
        $idFactura = $_REQUEST["id_factura"];

        $loadReg = $factura->load("id_operacion_factura=".$idFactura);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL PAGADOR
        $arrDatosPagador = $cliente->obtenerCuposPagador($operacion->id_pagador);

        //OBTENEMOS EL EMISOR
        $arrDatosEmisor = $cliente->obtenerCuposEmisor($operacion->id_emisor);

        $idCliente = $operacion->id_emisor;
		$loadReg = $cliente->load("id_cliente=".$idCliente);
		$plazoEmisor = $cliente->plazo;

		$referenciaPagador =  new clientes_ref_pagador();
		$idPagador = $operacion->id_pagador;
		$loadReg = $referenciaPagador->load("id_cliente=".$idCliente. " AND id_pagador=".$idPagador);
		$plazoPagador = $referenciaPagador->plazo;

		$plazoMaximo = ($plazoEmisor != ""?$plazoEmisor : 0);
		if ($plazoPagador != "")
			$plazoMaximo = $plazoPagador;

		//CALCULAMOS PLAZOS
		$fechaMinimaFactura = sumar_dias_fecha($operacion->fecha_operacion,30);
		$fechaMaximoPagoFactura = sumar_dias_fecha($operacion->fecha_operacion,120);
		if ($plazoMaximo > 0){
			$plazoMaximo = ($plazoMaximo==30?31:$plazoMaximo); //PARA EVITAR SI EL MINIMO Y EL MAXIMO SON 30
			$fechaMaximoPagoFactura = sumar_dias_fecha($operacion->fecha_operacion,$plazoMaximo);
		}

        include("./modules/operaciones/templates/factura.php");

    }

    /**
     * Funciòn para eliminar factura
     */
    function eliminarFactura() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();

        $idFactura = $_REQUEST["id_factura"];

        $loadReg1 = $factura->load("id_operacion_factura=".$idFactura);

        $idOperacion = $factura->id_operacion;

        $factura->Delete();

        //ACTUALIZAMOS LOS TOTALES
        $operacion->actualizarTotalesOperacion($idOperacion);

        //ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
        $operacion->actualizarPorcentajesParticipacionInversionista($idOperacion);

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funciòn para obtener el listado de operaciones
     */
    function listOperaciones(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $strSQL = "SELECT o.*, c1.razon_social as emisor, c2.razon_social as pagador
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    WHERE 1=1";

        if ($_REQUEST["fecha_inicio_buscador"] != "")
            $strSQL .= " AND o.fecha_operacion >= '".$_REQUEST["fecha_inicio_buscador"]."'";

        if ($_REQUEST["fecha_fin_buscador"] != "")
            $strSQL .= " AND o.fecha_operacion <= '".$_REQUEST["fecha_fin_buscador"]."'";

        if ($_REQUEST["id_inversionista_buscador"] != "")
            $strSQL .= " AND o.id_inversionista = ".$_REQUEST["id_inversionista_buscador"];

        if ($_REQUEST["id_emisor_buscador"] != "")
            $strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor_buscador"];

        if ($_REQUEST["id_pagador_buscador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador_buscador"];

        if ($_REQUEST["estado_buscador"] != "")
            $strSQL .= " AND o.estado = ".$_REQUEST["estado_buscador"];

         $strSQL .= " ORDER BY o.id_operacion DESC";

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $factura = new operacion_factura();

        $rsOperaciones = $db->Execute($strSQL);

        $template = "listado_operaciones.php";
        if ($_REQUEST["exportar"] == 1)
            $template = "listado_operaciones_exportar.php";

        if ($_SESSION["profile_text"] == "Cliente"){
        	$template = "listado_operaciones_cliente.php";
        	if ($_REQUEST["exportar"] == 1)
        		$template = "listado_operaciones_exportar.php";
        }

        include("./modules/operaciones/templates/" . $template);
    }

    /**
     * Funciòn para actualizar informacion comision
     */
    function updateComision() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion_pago"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        $operacion->fecha_pago_comision = $_POST['fecha_pago_comision'];
        $operacion->observaciones_comision = $_POST['observaciones_comision'];

        $operacion->Save();

        $jsondata['Message'] = "El proceso se realizo con exito.";
        $jsondata['IdOperacion'] = $operacion->id_operacion;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para mostrar el formulario de actualizar informacion comision
     */
    function formActualizarComision() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/textarea.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion_pago"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        include("./modules/operaciones/templates/actualizar_comision.php");
    }

    /**
     * Funciòn para pasar la operación a vigente
     */
    function operacionVigente() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $inversionista = new operacion_inversionista();
        $factura = new operacion_factura();

        $success = true;
        $msj = "";
        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

		//DETERMINAMOS SI HAY FACTURAS
        $tieneFacturas = $factura->tieneFacturasSoporte($idOperacion);
        if (!$tieneFacturas){
            $msj .= "La operacion no ha registrado facturas o hay facturas que no tienen soporte. Verifique.<br/>";
            $success = false;
        }

		//DETERMINAMOS SI HAY DESEMBOLSOS
        $tieneDesembolsos = $operacion->tieneDesembolsosSoporte($idOperacion);
        if (!$tieneDesembolsos){
            $msj .= "La operacion no ha registrado desembolsos o hay desembolsos que no tienen soporte. Verifique.<br/>";
            $success = false;
        }

        //DETERMINAMOS SI LOS DESEMBOLSOS SON IGUALES AL VALOR DEL GIRO FINAL
        $totalDesembolsos = $operacion->totalDesembolsosRegistrados($idOperacion);
        if ($operacion->valor_giro_final != $totalDesembolsos){
            $msj .= "El valor de los desembolsos (". $totalDesembolsos.") registrados es menor de el valor de giro final(". $operacion->valor_giro_final."). Verifique.<br/>";
            $success = false;
        }

		//DETERMINAMOS SI HAY INVERSIONISTAS
		//SE ELIMINA REVISION DE INVERSIONISTAS - CONTROL DE CAMBIOS
		/*$rsInversionistas = $inversionista->getInversionistasPorOperacion($idOperacion);
		if ($rsInversionistas->_numOfRows <= 0){
            $msj .= "La operacion no ha registrado inversionistas. Verifique.<br/>";
            $success = false;
        }*/

		if ($success){
			$operacion->tipo_operacion = 1;
			$operacion->estado = 1;
			$observaciones = "Fecha:". date("Y-m-d H:i:s") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones']."<hr/>";
			$operacion->observaciones = $observaciones . $operacion->observaciones;
			$operacion->Save();
        }

        $jsondata['Message'] = $msj;
        $jsondata['IdOperacion'] = $operacion->id_operacion;
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }


    /**
     * Funciòn para actualizar informacion operacion
     */
    function updateOperacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        $operacion->id_ejecutivo = $_POST['id_ejecutivo'];
        $operacion->comision = $_POST['comision'];
        $operacion->fecha_pago_comision = $_POST['fecha_pago_comision'];
        $observaciones = "Fecha:". date("Y-m-d H:i:s") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones']."<hr/>";
        $operacion->observaciones = $observaciones . $operacion->observaciones;
        $operacion->observaciones_comision = $_POST['observaciones_comision'];

        $operacion->Save();

        $jsondata['Message'] = "El proceso se realizo con exito.";
        $jsondata['IdOperacion'] = $operacion->id_operacion;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para guardar informacion operacion
     */
    function saveOperacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

		//EL NUMERO FACTURA YA NO VA POR OPERACION SI NO POR RELIQUIDACION
        $operacion->num_factura = 0;
        $operacion->fecha_operacion =  $_POST['fecha_operacion'];
        $operacion->fecha_pago_operacion = $_POST['fecha_pago_operacion'];
        $operacion->id_inversionista = $_POST['id_inversionista'];
        $operacion->fecha_vencimiento = $_POST['fecha_vencimiento'];
        $operacion->aplica_impuesto = $_POST['aplica_impuesto'];
        $operacion->id_emisor = $_POST['id_emisor'];
        $operacion->id_pagador = $_POST['id_pagador'];
        $operacion->porcentaje_descuento = $_POST['porcentaje_descuento'];
        $operacion->tasa_inversionista = $_POST['tasa_inversionista'];
        $operacion->factor = $_POST['factor'];
        $operacion->valor_otros_operacion = $_POST['valor_otros_operacion'];
        $operacion->descripcion_otros = $_POST['descripcion_otros'];
        $operacion->id_ejecutivo = $_POST['id_ejecutivo'];
        $operacion->comision = $_POST['comision'];
        $operacion->fecha_pago_comision = $_POST['fecha_pago_comision'];
        $operacion->monto_argenta = $_POST['monto_argenta'];
        $operacion->tipo_operacion = $_POST['tipo_operacion'];
        $observaciones = "Fecha:". date("Y-m-d H:i:s") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones']."<hr/>";
        $operacion->observaciones = $observaciones . $operacion->observaciones;
        $operacion->id_usuario = $_SESSION["id_user"];

		//SI LA OPERACION LA CREA DESDE EL PERFIL CLIENTE SE MODIFICAN ALGUNOS DATOS BASE
        if ($_REQUEST["desde"]=="cliente"){
        	$operacion->comision = null;
        	$operacion->fecha_pago_comision = null;
			$operacion->tipo_operacion = 1; //Real
        }

        $operacion->Save();

        //ACTUALIZAMOS EL NUMERO DE FACTURA
        if ($idOperacion == 0)
        {
            $loadReg1 = $operacion->load("id_operacion=".$operacion->id_operacion);
            $operacion->fecha = date("Y-m-d");

            $estado = 3;//CREADO
            if ($_REQUEST["desde"]=="cliente")
            	$estado = 4;//CREADO POR CLIENTE

            $operacion->estado = $estado;
            $operacion->facturado = 2; //SIN FACTURAR
            $operacion->Save();
        }

        //ACTUALIZAMOS LOS TOTALES
        $operacion->actualizarTotalesOperacion($operacion->id_operacion);

        //ACTUALIZAMOS LOS VALORES Y PORCENTAJES DE PARTICIPACION EN FACTURAS
        $operacion->actualizarPorcentajesParticipacionInversionista($operacion->id_operacion);

        $jsondata['Message'] = "El proceso se realizo con exito. Por favor configure su operacion.";
        $jsondata['IdOperacion'] = $operacion->id_operacion;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un operacion
     */
    function operacion() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();
        $operacion = new operacion();
        $factura = new operacion_factura();

        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);
        $arrEmisoresActivos = $clientes->obtenerClientesActivosPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);
        $arrPagadoresActivos = $clientes->obtenerClientesActivosPorTipoTercero(6);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        //OBTENEMOS LOS EJECUTIVOS
        $arrEjecutivos = $clientes->obtenerClientesPorTipoTercero(5);

        $rsFacturas = $factura->getFacturasPorOperacion($idOperacion);

        $template = "operacion.php";
        if ($_SESSION["profile_text"] == "Cliente"){
        	$template = "operacion_cliente.php";
        }

        include("./modules/operaciones/templates/".$template);

    }

    /**
     * Funciòn para eliminar operacion
     */
    function eliminarOperacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $operacion = new operacion();

        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        $operacion->Delete();

        //ELIMINAMOS DESEMBOLSOS
        $strSQL = "DELETE FROM operacion_desembolsos WHERE id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        //ELIMINAMOS FACTURAS
        $strSQL = "DELETE FROM operacion_factura_participacion WHERE id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        //ELIMINAMOS FACTURAS
        $strSQL = "DELETE FROM operacion_factura WHERE id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        //ELIMINAMOS RELIQUIDACIONES
        $strSQL = "DELETE FROM operacion_reliquidacion WHERE id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        //ELIMINAMOS RELIQUIDACIONES PARCIALES
        $strSQL = "DELETE FROM operacion_reliquidacion_pp WHERE id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        //ELIMINAMOS RELIQUIDACIONES TOTALES
        $strSQL = "DELETE FROM operacion_reliquidacion_pt WHERE id_operacion=" . $idOperacion;
        $db->Execute($strSQL);

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

        }
    }

}

?>