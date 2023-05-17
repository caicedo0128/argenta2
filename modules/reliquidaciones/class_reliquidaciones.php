<?php
/**
* Adminsitración del modulo reliquidacion
* @version 1.0
* El constructor de esta clase es {@link reliquidaciones()}
*/
require_once("class_reliquidaciones_extended.php");
class reliquidaciones{


    var $Database;
    var $ID;
    var $arrEstadosReliquidacion = array("1"=>"ABIERTO","2"=>"FINALIZADO");
    var $arrTiposReliquidacion = array("3"=>"PAGO TOTAL FECHA PREVISTA","4"=>"PAGO PARCIAL FECHA PREVISTA","5"=>"PAGO TOTAL ANTICIPADO","6"=>"PAGO PARCIAL ANTICIPADO","7"=>"PAGO TOTAL POSTERIOR","8"=>"PAGO PARCIAL POSTERIOR");

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "reliquidacion":
                            $this->reliquidacion();
                            break;
            case "saveReliquidacion":
                            $this->saveReliquidacion();
                            break;
            case "listReliquidaciones":
                            $this->listReliquidaciones();
                            break;
            case "eliminarReliquidacion":
                            $this->eliminarReliquidacion();
                            break;
            case "reliquidacionPT":
                            $this->reliquidacionPagos("PT");
                            break;
            case "reliquidacionPTA":
                            $this->reliquidacionPagos("PTA");
                            break;
            case "reliquidacionPPA":
                            $this->reliquidacionPagos("PPA");
                            break;
            case "reliquidacionPTP":
                            $this->reliquidacionPagos("PTP");
                            break;
            case "reliquidacionPP":
                            $this->reliquidacionPagos("PP");
                            break;
            case "reliquidacionPPP":
                            $this->reliquidacionPagos("PPP");
                            break;
            case "reliquidacionPPAbonos":
                            $this->reliquidacionPPAbonos();
                            break;
            case "saveReliquidacionPPAbono":
                            $this->saveReliquidacionPPAbono();
                            break;
            case "eliminarReliquidacionAbono":
                            $this->eliminarReliquidacionAbono();
                            break;
            case "guardarReporteReliquidacion":
                            $this->guardarReporteReliquidacion();
                            break;
            case "enviarReporteReliquidacion":
                            $this->enviarReporteReliquidacion();
                            break;
            case "generarReporteCliente":
                            $this->generarReporteCliente();
                            break;
            case "generarReporteClienteTrazabilidad":
                            $this->generarReporteClienteTrazabilidad();
                            break;
            case "generarReporteContableTrazabilidad":
                            $this->generarReporteContableTrazabilidad();
                            break;
            case "generarReporteFacturasLiquidadas":
                            $this->generarReporteFacturasLiquidadas();
                            break;
            case "actualizarFacturaDesdeReliquidacion":
                            $this->actualizarFacturaDesdeReliquidacion();
                            break;
            case "generarReporteClienteTrazabilidadNew":
                            $this->generarReporteClienteTrazabilidadNew();
                            break;
            case "actualizacionDatosFacturacion":
                            $this->actualizacionDatosFacturacion();
                            break;

        }
    }

    function actualizacionDatosFacturacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

		require_once("./modules/clientes/class_clientes.php");
		require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $emisor = new clientes_adicionales();
        $operacionReliquidacion = new operacion_reliquidacion();
        
        $idReliquidacion = $_REQUEST["id_reliquidacion"];
        $idOperacion = $_REQUEST["id_operacion"];
        
        //OBTENEMOS RELIQUIDACION
		$loadReg0 = $operacionReliquidacion->load("id_reliquidacion=".$idReliquidacion);
       	
       	//OBTENEMOS OPERACION
        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

		if ($operacionReliquidacion->id_tipo_reliquidacion == 3 || $operacionReliquidacion->id_tipo_reliquidacion == 5 || $operacionReliquidacion->id_tipo_reliquidacion == 7)
			$operacionReliquidacion->actualizacionDatosFacturacion($idReliquidacion, $emisor);
		else
			$operacionReliquidacion->actualizacionDatosFacturacionPP($idReliquidacion, $emisor);

        $jsondata['Message'] = "Actualizada";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para generar reporte cliente
     */
    function generarReporteClienteTrazabilidadNew() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $idReliquidacion = $_REQUEST["id_reliquidacion"];

        $operacionReliquidacion = new operacion_reliquidacion();
        $reliquidacionAbonos = new operacion_reliquidacion_abonos();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $reliquidacionPT = new operacion_reliquidacion_pt();
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacionReliquidacion->load("id_reliquidacion=".$idReliquidacion);
        $loadReg1 = $reliquidacionPP->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);

        $totalConsignado = $reliquidacionPP->abono + $reliquidacionPT->valor_pago;

        $idOperacion = $operacionReliquidacion->id_operacion;

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS
        $arrFacturas = $factura->getArrFacturasPorReliquidacion($idReliquidacion);

        $fechaDesembolso = $reliquidacionPT->fecha_desembolso;
        if ($fechaDesembolso == "" || $fechaDesembolso == null || $fechaDesembolso == "0000-00-00")
            $fechaDesembolso = $reliquidacionPP->fecha_desembolso;


        //OBTENEMOS LAS FACTURAS DE LA RELIQUIDACION
        $arrFacturasTrazabilidad = array();
        $strSQL = "SELECT * FROM operacion_factura WHERE id_reliquidacion=".$idReliquidacion;
        $rsFacturasReliquidacion = $db->Execute($strSQL);
        while (!$rsFacturasReliquidacion->EOF){
            $arrFacturasTrazabilidad[]=$rsFacturasReliquidacion->fields[id_operacion_factura];
            $rsFacturasReliquidacion->MoveNext();
        }

        $arrFacturaAbonada = $reliquidacionAbonos->getArrFacturasAbonadasReliquidacion($idReliquidacion);

        $arrFacturasTrazabilidadTemp = array_merge($arrFacturasTrazabilidad, $arrFacturaAbonada);

        //RECORREMOS HASTA CUANDO LA RELIQUIDACION NO TENGA UNA FACTURA ABONADA
        while(Count($arrFacturaAbonada) > 0)
        {
            //DETERMINAMOS SI LA NUEVA FACTURA TIENE UNA RELIQUIDACION Y ESTA ABONA A OTRA FACTURA
            $strSQL = "SELECT orr.id_factura
                       FROM operacion_factura as of
                       INNER JOIN operacion_reliquidacion_abonos as orr ON of.id_reliquidacion = orr.id_reliquidacion
                       WHERE of.id_operacion_factura in (".implode(",",$arrFacturaAbonada).")";
            $rsListadoFacturas = $db->Execute($strSQL);

            //GENERAMOS EL ARREGLO DE LAS FACTURAS ABONADAS
            $arrFacturaAbonada = array();
            while (!$rsListadoFacturas->EOF){

                $arrFacturaAbonada[] = $rsListadoFacturas->fields["id_factura"];
                $rsListadoFacturas->MoveNext();
            }

            //INCLUIMOS LA FACTURA ABONADA
            $arrFacturasTrazabilidadTemp = array_merge($arrFacturasTrazabilidadTemp, $arrFacturaAbonada);

        }

        $strSQL = "SELECT of.*,
                  COALESCE(rpp.id_reliquidacion, rpt.id_reliquidacion) as id_reliquidacion,
                  COALESCE(rpp.abono, rpt.valor_pago) as valorPago,
                  COALESCE(rpp.fecha_pago_pactada, rpt.fecha_pago_pactada) as fechaPagoPactada,
                  COALESCE(rpp.fecha_real_pago, rpt.fecha_real_pago) as fechaRealPago,
                  (COALESCE(rpp.intereses_mora,0) + COALESCE(rpt.intereses_mora,0)) as interesesMora,
                  (COALESCE(rpp.intereses_devolver,0) + COALESCE(rpt.intereses_devolver,0)) as interesesDevolver,
                  (COALESCE(rpp.otros,0) + COALESCE(rpt.otros_descuentos,0)) as otros,
                  (COALESCE(rpp.gmf,0) + COALESCE(rpt.gmf,0)) as gmf,
                  (COALESCE(rpp.monto_devolver,0) + COALESCE(rpt.monto_devolver,0))  as montoDevolucion,
                  (COALESCE(rpp.devolucion_remanentes,0) + COALESCE(rpt.devolucion_remanentes,0)) as remanentesDisponibles,
                  (COALESCE(NULL,rpt.nuevo_remanente)) as nuevoRemanente
                  FROM operacion_factura as of
                  LEFT JOIN operacion_reliquidacion_pp as rpp on of.id_reliquidacion = rpp.id_reliquidacion
                  LEFT JOIN operacion_reliquidacion_pt as rpt on of.id_reliquidacion = rpt.id_reliquidacion
                  WHERE of.id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp).")";

        $rsListadoFacturas = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE RELIQUIDACIONES TOTALES
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(otros_descuentos) as otros,
                     SUM(devolucion_remanentes) as devolucion,
                     SUM(gmf) as gmf,
                     SUM(intereses_mora) as interesesMora,
                     SUM(intereses_devolver) as interesesDevolver,
                     SUM(monto_devolver) as montoDevolver,
                     SUM(valor_pago) as valor_ingreso,
					 SUM(COALESCE(iva_gestion ,0)) as ivaGestion,
					 SUM(COALESCE(iva_giro_terceros  ,0)) as ivaGiroTerceros,
					 SUM(COALESCE(rtf_gestion ,0)) as rtfGestion,
					 SUM(COALESCE(rtf_giro_terceros ,0)) as rtfGiroTerceros,
					 SUM(COALESCE(rtf_intereses ,0)) as rtfIntereses,
					 SUM(COALESCE(rtf_ica ,0)) as rtfICA,
					 SUM(COALESCE(rtf_iva ,0)) as rtfIVA,
					 SUM(COALESCE(total_factura ,0)) as totalFactura,
					 SUM(COALESCE(neto_factura ,0)) as netoFactura,
					 SUM(COALESCE(nuevo_remanente ,0)) as nuevoRemanente                     
                     FROM operacion_reliquidacion_pt
                     WHERE id_reliquidacion in (SELECT DISTINCT id_reliquidacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datos
                  ";                  

        $rsTotalesReliquidacionTotales = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE RELIQUIDACIONES PARCIALES
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(otros) as otros,
                     SUM(devolucion_remanentes) as devolucion,
                     SUM(gmf) as gmf,
                     SUM(intereses_mora) as interesesMora,
                     SUM(intereses_devolver) as interesesDevolver,
                     SUM(monto_devolver) as montoDevolver,
                     SUM(abono) as valor_ingreso
                     FROM operacion_reliquidacion_pp
                     WHERE id_reliquidacion in (SELECT DISTINCT id_reliquidacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datos
                  ";
        $rsTotalesReliquidacionParciales = $db->Execute($strSQL);

        $totalConsignadoVariasReliquidaciones = $rsTotalesReliquidacionTotales->fields["valor_ingreso"] + $rsTotalesReliquidacionParciales->fields["valor_ingreso"];
        $totalOtros = $rsTotalesReliquidacionTotales->fields["otros"] + $rsTotalesReliquidacionParciales->fields["otros"];
        $totalRemanentes = $rsTotalesReliquidacionTotales->fields["devolucion"] + $rsTotalesReliquidacionParciales->fields["devolucion"];
        $totalGmf= $rsTotalesReliquidacionTotales->fields["gmf"] + $rsTotalesReliquidacionParciales->fields["gmf"];
        $totalInteresesMora = $rsTotalesReliquidacionTotales->fields["interesesMora"] + $rsTotalesReliquidacionParciales->fields["interesesMora"];
        $totalInteresesDevolver = $rsTotalesReliquidacionTotales->fields["interesesDevolver"] + $rsTotalesReliquidacionParciales->fields["interesesDevolver"];
        $totalIVAGestion = $rsTotalesReliquidacionTotales->fields["ivaGestion"];
        $totalIVAGiroTerceros = $rsTotalesReliquidacionTotales->fields["ivaGiroTerceros"];
        $totalRTFGestion = $rsTotalesReliquidacionTotales->fields["rtfGestion"];
        $totalRTFGiroTerceros = $rsTotalesReliquidacionTotales->fields["rtfGiroTerceros"];
        $totalRTFIntereses = $rsTotalesReliquidacionTotales->fields["rtfIntereses"];
        $totalRTFICA = $rsTotalesReliquidacionTotales->fields["rtfICA"];
        $totalRTFIVA = $rsTotalesReliquidacionTotales->fields["rtfIVA"];
        $totalTotalFactura = $rsTotalesReliquidacionTotales->fields["totalFactura"];
        $totalNetoFactura = $rsTotalesReliquidacionTotales->fields["netoFactura"];
        $totalNuevoRemanente = $rsTotalesReliquidacionTotales->fields["nuevoRemanente"];

        include("./modules/reliquidaciones/templates/reporte_cliente_trazabilidad_new.php");
	}

    function actualizarFacturaDesdeReliquidacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./modules/operaciones/class_operaciones.php");

        $idOperacion = $_REQUEST["id_operacion"];
        $idFactura = $_REQUEST["id_factura"];
        $fechaRealPago = $_REQUEST["fecha_real_pago"];

        $operacionFactura = new operacion_factura();
        $operacion = new operacion();

        $loadReg = $operacion->load("id_operacion=".$idOperacion);

        $operacionFactura->actualizarFacturaDesdeReliquidacion($idFactura, $operacion, $fechaRealPago);

        $jsondata['Message'] = "Actualizada";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para obtener el reporte de facturas liquidadas
     */
    function generarReporteFacturasLiquidadas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");
        require_once("./utilities/pdf/tcpdf.php");


        $idReliquidacion = $_REQUEST["id_reliquidacion"];
        $conFacturacion = $_REQUEST["facturacion"];

        //INSTANCIAMOS CLASES
        $reliquidacion = new operacion_reliquidacion();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $reliquidacionPT = new operacion_reliquidacion_pt();
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacionPP->load("id_reliquidacion=".$idReliquidacion);
        $loadReg3 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);

        $loadReg4 = $operacion->load("id_operacion=".$reliquidacion->id_operacion);

        //OBTENEMOS EL EMISOR
        $loadReg5 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg6 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //FACTURAS PAGADAS
        $arrDetalleFacturas = $factura->getFacturasPorReliquidacion($idReliquidacion);

        //DATOS PARA REPORTE DE RELIQUIDACIONES PARCIALES
        if ($reliquidacionPP->id_reliquidacion_pp != ""){

            $arrDetalleFacturas = $factura->getFacturasPorReliquidacionPP($idReliquidacion);
            $rsDataReliquidacionPP = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);

            $arrDiasMora = date_diff_custom($operacion->fecha_operacion,$reliquidacionPP->fecha_real_pago);
            //DETERMINAMOS EL TIPO DE RELIQUIDACION
            $factorDias = 1;
            if ($operacionReliquidacion->id_tipo_reliquidacion == 6)
                $factorDias = -1;

            $diasMora = $arrDiasMora["d"];
            $interesesDevolver = $reliquidacionPP->intereses_devolver;
            $valorIngreso = $reliquidacionPP->abono;
            $nuevoValorObligacion = $reliquidacionPP->nuevo_valor_obligacion;
            $fechaRealPago = $reliquidacionPP->fecha_real_pago;
            $numFacturaArgenta = $reliquidacion->num_factura;

			$ivaGestion = $reliquidacionPP->iva_gestion;
			$ivaGiroTerceros = $reliquidacionPP->iva_giro_terceros;
			$rtfGestion = $reliquidacionPP->rtf_gestion;
			$rtfGiroTerceros = $reliquidacionPP->rtf_giro_terceros;
			$rtfIntereses = $reliquidacionPP->rtf_intereses;
			$rtfICA = $reliquidacionPP->rtf_ica;
			$rtfIVA = $reliquidacionPP->rtf_iva;
			$vrTotalFactura = $reliquidacionPP->total_factura;
			$vrNetoFactura = $reliquidacionPP->neto_factura;
			$vrNuevoRemanente = $reliquidacionPP->nuevo_remanente;

            $template = "reporte_liquidacion_facturas_pp.php";
        }

        //DATOS PARA REPORTE DE RELIQUIDACIONES TOTALES
        if ($reliquidacionPT->id_reliquidacion_pt != ""){

            $arrDiasMora = date_diff_custom($operacion->fecha_operacion,$reliquidacionPT->fecha_real_pago);
            //DETERMINAMOS EL TIPO DE RELIQUIDACION
            $factorDias = 1;
            if ($operacionReliquidacion->id_tipo_reliquidacion == 5)
                $factorDias = -1;

            $diasMora = $arrDiasMora["d"];
            $valorIngreso = $reliquidacionPT->valor_pago;
            $interesesMora = $reliquidacionPT->intereses_mora;
            $interesesDevolver = $reliquidacionPT->intereses_devolver;
            $remanentesDisponibles = $reliquidacionPT->devolucion_remanentes;
            $gmfDevolucion = $reliquidacionPT->gmf;
            $valorDevolucion = $reliquidacionPT->monto_devolver;
            $otros = $reliquidacionPT->otros_descuentos;
            $fechaRealPago = $reliquidacionPT->fecha_real_pago;

			$ivaGestion = $reliquidacionPT->iva_gestion;
			$ivaGiroTerceros = $reliquidacionPT->iva_giro_terceros;
			$rtfGestion = $reliquidacionPT->rtf_gestion;
			$rtfGiroTerceros = $reliquidacionPT->rtf_giro_terceros;
			$rtfIntereses = $reliquidacionPT->rtf_intereses;
			$rtfICA = $reliquidacionPT->rtf_ica;
			$rtfIVA = $reliquidacionPT->rtf_iva;
			$vrTotalFactura = $reliquidacionPT->total_factura;
			$vrNetoFactura = $reliquidacionPT->neto_factura;
			$vrNuevoRemanente = $reliquidacionPT->nuevo_remanente;

            $template = "reporte_liquidacion_facturas.php";
        }

        if ($conFacturacion==true)
        	$template = "reporte_liquidacion_facturas_facturacion.php";

        include("./modules/reliquidaciones/templates/" . $template);

    }

    /**
     * Funciòn para generar reporte contable trazabilidad
     */
    function generarReporteContableTrazabilidad() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $idReliquidacion = $_REQUEST["id_reliquidacion"];

        $operacionReliquidacion = new operacion_reliquidacion();
        $reliquidacionAbonos = new operacion_reliquidacion_abonos();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $reliquidacionPT = new operacion_reliquidacion_pt();
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacionReliquidacion->load("id_reliquidacion=".$idReliquidacion);
        $loadReg1 = $reliquidacionPP->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);

        $idOperacion = $operacionReliquidacion->id_operacion;

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS
        $arrFacturas = $factura->getArrFacturasPorReliquidacion($idReliquidacion);

        $fechaDesembolso = $reliquidacionPT->fecha_desembolso;
        if ($fechaDesembolso == "" || $fechaDesembolso == null || $fechaDesembolso == "0000-00-00")
            $fechaDesembolso = $reliquidacionPP->fecha_desembolso;


        //OBTENEMOS LAS FACTURAS DE LA RELIQUIDACION
        $arrFacturasTrazabilidad = array();
        $strSQL = "SELECT * FROM operacion_factura WHERE id_reliquidacion=".$idReliquidacion;
        $rsFacturasReliquidacion = $db->Execute($strSQL);
        while (!$rsFacturasReliquidacion->EOF){
            $arrFacturasTrazabilidad[]=$rsFacturasReliquidacion->fields[id_operacion_factura];
            $rsFacturasReliquidacion->MoveNext();
        }

        $arrFacturaAbonada = $reliquidacionAbonos->getArrFacturasAbonadasReliquidacion($idReliquidacion);

        $arrFacturasTrazabilidadTemp = array_merge($arrFacturasTrazabilidad, $arrFacturaAbonada);

        //RECORREMOS HASTA CUANDO LA RELIQUIDACION NO TENGA UNA FACTURA ABONADA
        while(Count($arrFacturaAbonada) > 0)
        {
            //DETERMINAMOS SI LA NUEVA FACTURA TIENE UNA RELIQUIDACION Y ESTA ABONA A OTRA FACTURA
            $strSQL = "SELECT orr.id_factura
                       FROM operacion_factura as of
                       INNER JOIN operacion_reliquidacion_abonos as orr ON of.id_reliquidacion = orr.id_reliquidacion
                       WHERE of.id_operacion_factura in (".implode(",",$arrFacturaAbonada).")";
            $rsListadoFacturas = $db->Execute($strSQL);

            //GENERAMOS EL ARREGLO DE LAS FACTURAS ABONADAS
            $arrFacturaAbonada = array();
            while (!$rsListadoFacturas->EOF){

                $arrFacturaAbonada[] = $rsListadoFacturas->fields["id_factura"];
                $rsListadoFacturas->MoveNext();
            }

            //INCLUIMOS LA FACTURA ABONADA
            $arrFacturasTrazabilidadTemp = array_merge($arrFacturasTrazabilidadTemp, $arrFacturaAbonada);

        }

        $strSQL = "SELECT of.id_reliquidacion,
                  of.valor_futuro,
                  of.num_factura,
                  COALESCE(rpp.abono, rpt.valor_pago) as valorPago,
                  COALESCE(rpp.fecha_pago_pactada, rpt.fecha_pago_pactada) as fechaPagoPactada,
                  COALESCE(rpp.fecha_real_pago, rpt.fecha_real_pago) as fechaRealPago,
                  (COALESCE(rpp.intereses_mora,0) + COALESCE(rpt.intereses_mora,0)) as interesesMora,
                  (COALESCE(rpp.intereses_devolver,0) + COALESCE(rpt.intereses_devolver,0)) as interesesDevolver,
                  (COALESCE(rpp.otros,0) + COALESCE(rpt.otros_descuentos,0)) as otros,
                  (COALESCE(rpp.gmf,0) + COALESCE(rpt.gmf,0)) as gmf,
                  (COALESCE(rpp.monto_devolver,0) + COALESCE(rpt.monto_devolver,0))  as montoDevolucion,
                  (COALESCE(rpp.devolucion_remanentes,0) + COALESCE(rpt.devolucion_remanentes,0)) as remanentesDisponibles
                  FROM operacion_factura as of
                  LEFT JOIN operacion_reliquidacion_pp as rpp on of.id_reliquidacion = rpp.id_reliquidacion
                  LEFT JOIN operacion_reliquidacion_pt as rpt on of.id_reliquidacion = rpt.id_reliquidacion
                  WHERE of.id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp).")";
        $rsListadoFacturas = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE LAS OPERACIONES AFECTADAS
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(valor_otros_operacion) as otros_operacion
                     FROM operacion
                     WHERE id_operacion in (SELECT DISTINCT id_operacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datosOperacion
                  ";

        $rsTotalesOperacion = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE RELIQUIDACIONES TOTALES
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(otros_descuentos) as otros,
                     SUM(devolucion_remanentes) as devolucion,
                     SUM(gmf) as gmf,
                     SUM(intereses_mora) as interesesMora,
                     SUM(intereses_devolver) as interesesDevolver,
                     SUM(monto_devolver) as montoDevolver
                     FROM operacion_reliquidacion_pt
                     WHERE id_reliquidacion in (SELECT DISTINCT id_reliquidacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datos
                  ";
        $rsTotalesReliquidacionTotales = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE RELIQUIDACIONES PARCIALES
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(otros) as otros,
                     SUM(devolucion_remanentes) as devolucion,
                     SUM(gmf) as gmf,
                     SUM(intereses_mora) as interesesMora,
                     SUM(intereses_devolver) as interesesDevolver,
                     SUM(monto_devolver) as montoDevolver
                     FROM operacion_reliquidacion_pp
                     WHERE id_reliquidacion in (SELECT DISTINCT id_reliquidacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datos
                  ";
        $rsTotalesReliquidacionParciales = $db->Execute($strSQL);

        //OBTENEMOS DATOS DE LAS FACTURAS RELIQUIDADAS
        $strSQL = "SELECT
                  SUM(of.descuento_total) as interesesCorrientesNose,
                  SUM(of.margen_inversionista) as interesesCorrientes,
                  SUM(of.margen_argenta) as valorFacturaArgenta,
                  SUM(of.gmf) as gmfFacturas,
                  SUM(of.valor_giro_final) as valorGiroFinal
                  FROM operacion_factura as of
                  WHERE of.id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp).")";

        $rsInfoFacturas = $db->Execute($strSQL);

        //OBTENEMOS LAS FACTURAS DE ARGENTA QUE INTERVIENEN EN LAS OPERACIONES
        $strSQL = "SELECT distinct group_concat(distinct o.num_factura separator ',') as num_facturas FROM operacion_factura AS of INNER JOIN operacion as o ON of.id_operacion = o.id_operacion WHERE of.id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp).")";
        $rsFacturasArgenta = $db->Execute($strSQL);

        $facturasArgenta = $rsFacturasArgenta->fields["num_facturas"];
        $arrFacturasArgenta = explode(",",$rsFacturasArgenta->fields["num_facturas"]);
        $totalFacturasArgenta = Count($arrFacturasArgenta) - 1;
        $totalInteresesCorrientes = $rsInfoFacturas->fields["interesesCorrientes"];
        $totalValorFacturaArgenta = $rsInfoFacturas->fields["valorFacturaArgenta"] + ($rsInfoFacturas->fields["valorFacturaArgenta"] * 0.16);
        $totalOtros = $rsTotalesOperacion->fields["otros_operacion"] + $rsTotalesReliquidacionTotales->fields["otros"] + $rsTotalesReliquidacionParciales->fields["otros"];
        $totalRemanentes = $rsTotalesReliquidacionTotales->fields["devolucion"] + $rsTotalesReliquidacionParciales->fields["devolucion"];
        $totalGmfFacturas = $rsInfoFacturas->fields["gmfFacturas"];
        $totalGmf= $rsTotalesReliquidacionTotales->fields["gmf"] + $rsTotalesReliquidacionParciales->fields["gmf"];
        $totalInteresesMora = $rsTotalesReliquidacionTotales->fields["interesesMora"] + $rsTotalesReliquidacionParciales->fields["interesesMora"];
        $totalInteresesDevolver = $rsTotalesReliquidacionTotales->fields["interesesDevolver"] + $rsTotalesReliquidacionParciales->fields["interesesDevolver"];
        $totalMontoDevolver = $rsTotalesReliquidacionTotales->fields["montoDevolver"] + $rsTotalesReliquidacionParciales->fields["montoDevolver"];
		$totalInversion = $rsInfoFacturas->fields["valorGiroFinal"];
		$totalAjusteOtros = $rsTotalesOperacion->fields["otros_operacion"] * ($totalFacturasArgenta==0?1:$totalFacturasArgenta);

        include("./modules/reliquidaciones/templates/reporte_contable_trazabilidad.php");
    }

    /**
     * Funciòn para generar reporte cliente
     */
    function generarReporteClienteTrazabilidad() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $idReliquidacion = $_REQUEST["id_reliquidacion"];

        $operacionReliquidacion = new operacion_reliquidacion();
        $reliquidacionAbonos = new operacion_reliquidacion_abonos();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $reliquidacionPT = new operacion_reliquidacion_pt();
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacionReliquidacion->load("id_reliquidacion=".$idReliquidacion);
        $loadReg1 = $reliquidacionPP->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);

        $idOperacion = $operacionReliquidacion->id_operacion;

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS
        $arrFacturas = $factura->getArrFacturasPorReliquidacion($idReliquidacion);

        $fechaDesembolso = $reliquidacionPT->fecha_desembolso;
        if ($fechaDesembolso == "" || $fechaDesembolso == null || $fechaDesembolso == "0000-00-00")
            $fechaDesembolso = $reliquidacionPP->fecha_desembolso;


        //OBTENEMOS LAS FACTURAS DE LA RELIQUIDACION
        $arrFacturasTrazabilidad = array();
        $strSQL = "SELECT * FROM operacion_factura WHERE id_reliquidacion=".$idReliquidacion;
        $rsFacturasReliquidacion = $db->Execute($strSQL);
        while (!$rsFacturasReliquidacion->EOF){
            $arrFacturasTrazabilidad[]=$rsFacturasReliquidacion->fields[id_operacion_factura];
            $rsFacturasReliquidacion->MoveNext();
        }

        $arrFacturaAbonada = $reliquidacionAbonos->getArrFacturasAbonadasReliquidacion($idReliquidacion);

        $arrFacturasTrazabilidadTemp = array_merge($arrFacturasTrazabilidad, $arrFacturaAbonada);

        //RECORREMOS HASTA CUANDO LA RELIQUIDACION NO TENGA UNA FACTURA ABONADA
        while(Count($arrFacturaAbonada) > 0)
        {
            //DETERMINAMOS SI LA NUEVA FACTURA TIENE UNA RELIQUIDACION Y ESTA ABONA A OTRA FACTURA
            $strSQL = "SELECT orr.id_factura
                       FROM operacion_factura as of
                       INNER JOIN operacion_reliquidacion_abonos as orr ON of.id_reliquidacion = orr.id_reliquidacion
                       WHERE of.id_operacion_factura in (".implode(",",$arrFacturaAbonada).")";
            $rsListadoFacturas = $db->Execute($strSQL);

            //GENERAMOS EL ARREGLO DE LAS FACTURAS ABONADAS
            $arrFacturaAbonada = array();
            while (!$rsListadoFacturas->EOF){

                $arrFacturaAbonada[] = $rsListadoFacturas->fields["id_factura"];
                $rsListadoFacturas->MoveNext();
            }

            //INCLUIMOS LA FACTURA ABONADA
            $arrFacturasTrazabilidadTemp = array_merge($arrFacturasTrazabilidadTemp, $arrFacturaAbonada);

        }

        $strSQL = "SELECT of.id_reliquidacion,
                  of.valor_futuro,
                  of.num_factura,
                  COALESCE(rpp.abono, rpt.valor_pago) as valorPago,
                  COALESCE(rpp.fecha_pago_pactada, rpt.fecha_pago_pactada) as fechaPagoPactada,
                  COALESCE(rpp.fecha_real_pago, rpt.fecha_real_pago) as fechaRealPago,
                  (COALESCE(rpp.intereses_mora,0) + COALESCE(rpt.intereses_mora,0)) as interesesMora,
                  (COALESCE(rpp.intereses_devolver,0) + COALESCE(rpt.intereses_devolver,0)) as interesesDevolver,
                  (COALESCE(rpp.otros,0) + COALESCE(rpt.otros_descuentos,0)) as otros,
                  (COALESCE(rpp.gmf,0) + COALESCE(rpt.gmf,0)) as gmf,
                  (COALESCE(rpp.monto_devolver,0) + COALESCE(rpt.monto_devolver,0))  as montoDevolucion,
                  (COALESCE(rpp.devolucion_remanentes,0) + COALESCE(rpt.devolucion_remanentes,0)) as remanentesDisponibles
                  FROM operacion_factura as of
                  LEFT JOIN operacion_reliquidacion_pp as rpp on of.id_reliquidacion = rpp.id_reliquidacion
                  LEFT JOIN operacion_reliquidacion_pt as rpt on of.id_reliquidacion = rpt.id_reliquidacion
                  WHERE of.id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp).")";

        $rsListadoFacturas = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE RELIQUIDACIONES TOTALES
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(otros_descuentos) as otros,
                     SUM(devolucion_remanentes) as devolucion,
                     SUM(gmf) as gmf,
                     SUM(intereses_mora) as interesesMora,
                     SUM(intereses_devolver) as interesesDevolver,
                     SUM(monto_devolver) as montoDevolver
                     FROM operacion_reliquidacion_pt
                     WHERE id_reliquidacion in (SELECT DISTINCT id_reliquidacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datos
                  ";
        $rsTotalesReliquidacionTotales = $db->Execute($strSQL);

        //OBTENEMOS LOS DATOS DE RELIQUIDACIONES PARCIALES
        $strSQL = "SELECT *
                  FROM
                  (SELECT
                     SUM(otros) as otros,
                     SUM(devolucion_remanentes) as devolucion,
                     SUM(gmf) as gmf,
                     SUM(intereses_mora) as interesesMora,
                     SUM(intereses_devolver) as interesesDevolver,
                     SUM(monto_devolver) as montoDevolver
                     FROM operacion_reliquidacion_pp
                     WHERE id_reliquidacion in (SELECT DISTINCT id_reliquidacion FROM operacion_factura WHERE id_operacion_factura in (".implode(",",$arrFacturasTrazabilidadTemp)."))
                  ) as datos
                  ";
        $rsTotalesReliquidacionParciales = $db->Execute($strSQL);

        $totalOtros = $rsTotalesReliquidacionTotales->fields["otros"] + $rsTotalesReliquidacionParciales->fields["otros"];
        $totalRemanentes = $rsTotalesReliquidacionTotales->fields["devolucion"] + $rsTotalesReliquidacionParciales->fields["devolucion"];
        $totalGmf= $rsTotalesReliquidacionTotales->fields["gmf"] + $rsTotalesReliquidacionParciales->fields["gmf"];
        $totalInteresesMora = $rsTotalesReliquidacionTotales->fields["interesesMora"] + $rsTotalesReliquidacionParciales->fields["interesesMora"];
        $totalInteresesDevolver = $rsTotalesReliquidacionTotales->fields["interesesDevolver"] + $rsTotalesReliquidacionParciales->fields["interesesDevolver"];
        $totalMontoDevolver = $rsTotalesReliquidacionTotales->fields["montoDevolver"] + $rsTotalesReliquidacionParciales->fields["montoDevolver"];

        include("./modules/reliquidaciones/templates/reporte_cliente_trazabilidad.php");
    }

    /**
     * Funciòn para generar reporte cliente
     */
    function generarReporteCliente() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $idReliquidacion = $_REQUEST["id_reliquidacion"];

        $operacionReliquidacion = new operacion_reliquidacion();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $reliquidacionPT = new operacion_reliquidacion_pt();
        $operacion = new operacion();
        $factura = new operacion_factura();
        $emisor = new clientes();
        $pagador = new clientes();

        $loadReg1 = $operacionReliquidacion->load("id_reliquidacion=".$idReliquidacion);
        $loadReg1 = $reliquidacionPP->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);

        $idOperacion = $operacionReliquidacion->id_operacion;

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //OBTENEMOS TODOS LOS TITULOS
        $arrFacturas = $factura->getArrFacturasPorReliquidacion($idReliquidacion);

        $fechaDesembolso = $reliquidacionPT->fecha_desembolso;
        if ($fechaDesembolso == "" || $fechaDesembolso == null || $fechaDesembolso == "0000-00-00")
            $fechaDesembolso = $reliquidacionPP->fecha_desembolso;

        //DATOS PARA REPORTE DE RELIQUIDACIONES PARCIALES
        if ($reliquidacionPP->id_reliquidacion_pp != ""){
            $reporte = "reporte_cliente_parciales";
            $rsDataReliquidacionPPReporteCliente = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
        }

        //DATOS PARA REPORTE DE RELIQUIDACIONES TOTALES
        if ($reliquidacionPT->id_reliquidacion_pt != ""){

            $arrDiasMora = date_diff_custom($reliquidacionPT->fecha_pago_pactada,$reliquidacionPT->fecha_real_pago);
            //DETERMINAMOS EL TIPO DE RELIQUIDACION
            $factorDias = 1;
            if ($operacionReliquidacion->id_tipo_reliquidacion == 5)
                $factorDias = -1;

            $diasMora = $arrDiasMora["d"];
            $valorIngreso = $reliquidacionPT->valor_pago;
            $interesesMora = $reliquidacionPT->intereses_mora;
            $remanentesDisponibles = $reliquidacionPT->devolucion_remanentes;
            $gmfDevolucion = $reliquidacionPT->gmf;
            $valorDevolucion = $reliquidacionPT->monto_devolver;
            $reporte = "reporte_cliente_totales";
        }

        include("./modules/reliquidaciones/templates/".$reporte.".php");
    }

    function enviarReporteReliquidacion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./utilities/class_send_mail.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();

        $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
        $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
        $subjectMail = $_REQUEST["__subjectMail"];
        $toNameMail = $_REQUEST["__toNameMail"];
        $toEmail = $_REQUEST["__toEmailMail"];

        //ENVIAMOS EL CORREO
        $arrAttach = array("reliquidaciones/reporte.pdf"=>"Reporte PDF");
        $arrVarsReplace = array("NAME"=>$toNameMail);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,"mailReliquidacion",$arrAttach,$arrVarsReplace);

        $jsondata['Message'] = "test";
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }

    function guardarReporteReliquidacion(){

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
        $pdf->Output(__DIR__ . '../../../gallery/reliquidaciones/reporte.pdf', 'F');

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para eliminar reliquidacion por abono
     */
    function eliminarReliquidacionAbono() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $reliquidacionPP = new operacion_reliquidacion_pp();

        $idReliquidacionPP = $_REQUEST["id_reliquidacion_pp"];

        $loadReg1 = $reliquidacionPP->load("id_reliquidacion_pp=".$idReliquidacionPP);

        //SE ABRE NUEVAMENTE LA RELIQUIDACION
        $reliquidacionTemp = new operacion_reliquidacion();
        $loadReg1 = $reliquidacionTemp->load("id_reliquidacion=".$reliquidacionPP->id_reliquidacion);
        $reliquidacionTemp->estado = 1; //ABIERTO
        $reliquidacionTemp->Save();

        //ELIMINAMOS EL ABONO
        $reliquidacionPP->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funciòn para guardar informacion reliquidacion PAGO PARCIAL FECHA PREVISTA - ABONO
     */
    function saveReliquidacionPPAbono() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./modules/clientes/class_clientes.php");
		require_once("./modules/operaciones/class_operaciones_extended.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $emisor = new clientes_adicionales();    
        $reliquidacion = new operacion_reliquidacion();
        $reliquidacionPP = new operacion_reliquidacion_pp();

        $idReliquidacionPP = $_POST["id_reliquidacion_pp"];
        $idReliquidacion = $_POST["id_reliquidacion_abono"];

        $loadReg1 = $reliquidacionPP->load("id_reliquidacion_pp=".$idReliquidacionPP);
        $loadReg2 = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);
		$loadReg3 = $operacion->load("id_operacion=".$reliquidacion->id_operacion); 
        $loadReg4 = $emisor->load("id_cliente=".$operacion->id_emisor);            

        $reliquidacionPP->id_operacion = $reliquidacion->id_operacion;
        $reliquidacionPP->id_reliquidacion = $idReliquidacion;
        $reliquidacionPP->fecha_desembolso = $_POST['fecha_desembolso_abono'];
        $reliquidacionPP->fecha_pago_pactada = $_POST['fecha_ultimo_pago_abono'];
        $reliquidacionPP->fecha_real_pago = $_POST['fecha_real_pago_abono'];
        $reliquidacionPP->fecha_movimiento = $_POST['fecha_movimiento_abono'];
        $reliquidacionPP->valor_obligacion_pp = $_POST['valor_obligacion_pp_abono'];
        $reliquidacionPP->abono = $_POST['abono_abono'];
        $reliquidacionPP->tasa = $_POST['tasa_abono'];
        $reliquidacionPP->otros = $_POST['otros_abono'];
        $reliquidacionPP->intereses_devolver = $_POST['intereses_devolver_abono'];
        $reliquidacionPP->nuevo_valor_obligacion = $_POST['nuevo_valor_obligacion_abono'];

        //SI EL NUEVO VALOR DE LA OBLIGACION ES MENOR O IGUAL A 0
        //SE SUPONE QUE ES EL ULTIMO ABONO
        $reliquidacionPP->abono_inversionista = $reliquidacionPP->abono;
        if ($reliquidacionPP->nuevo_valor_obligacion <= 0){

            //OBTENEMOS EL TOTAL DEL ABONOS DE LA RELIQUIDACION
            $strSQL = "SELECT SUM(abono) AS totalAbonos FROM  operacion_reliquidacion_pp WHERE nuevo_valor_obligacion > 0 AND id_reliquidacion = " . $idReliquidacion;
            $rsData1 = $db->Execute($strSQL);
            $totalAbonos = $rsData1->fields["totalAbonos"];

            //OBTENEMOS EL TOTAL DEL GIRO FINAL DE LAS FACTURAS RELIQUIDADAS
            $strSQL = "SELECT SUM(valor_giro_final) AS totalFacturasGiroFinal FROM  operacion_factura WHERE id_reliquidacion = " . $idReliquidacion;
            $rsData2 = $db->Execute($strSQL);
            $totalFacturasGiroFinal = $rsData2->fields["totalFacturasGiroFinal"];

            if ($totalAbonos > $totalFacturasGiroFinal)
                $ultimoValorAbonoInversionista = 0;
            else
                $ultimoValorAbonoInversionista = ($totalFacturasGiroFinal - $totalAbonos);

            //SI ES ULTIMO ABONO NO SE TOMA EL 100%
            $reliquidacionPP->abono_inversionista = $ultimoValorAbonoInversionista;
        }

        $reliquidacionPP->intereses_mora = $_POST['intereses_mora_abono'];
        $reliquidacionPP->devolucion_remanentes = 0;
        $reliquidacionPP->gmf = 0;
        $reliquidacionPP->monto_devolver = 0;

        //VALIDAMOS SI HAY REAMANENTES A DEVOLVER
        if ($reliquidacionPP->abono > $reliquidacionPP->valor_obligacion_pp){
            $reliquidacionPP->devolucion_remanentes = $_POST['devolucion_remanentes_gmf'];
            $reliquidacionPP->gmf = $_POST['gmf'];
            $reliquidacionPP->monto_devolver = $_POST['monto_devolver'];
        }

        $reliquidacionPP->Save();

        //CAMBIAMOS EL ESTADO DE CREADO POR FINALIZADO SOLO EN LAS RELIQUIDACIONES DE PAGO TOTAL
        if ($reliquidacionPP->nuevo_valor_obligacion <= 0){
            $reliquidacionTemp = new operacion_reliquidacion();
            $loadReg1 = $reliquidacionTemp->load("id_reliquidacion=".$reliquidacionPP->id_reliquidacion);
            $reliquidacionTemp->estado = 2; //FINALIZADO
            $reliquidacionTemp->Save();
        }
        
		//ACTUALIZAMOS DATOS DE LA FACTURACION PAGOS PARCIALES ABONOS
		$reliquidacion->actualizacionDatosFacturacionPPAbono($reliquidacionPP->id_reliquidacion, $emisor,$reliquidacionPP->id_reliquidacion_pp);     
			
        $jsondata['Message'] = "El proceso se realizo con exito.";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para obtener el formulario de abonos de reliquidacion PP
     */
    function reliquidacionPPAbonos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones_extended.php");
        require_once("./utilities/controles/textbox.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $ultimaReliquidacionPP = new operacion_reliquidacion_pp();

        $idReliquidacion = $_REQUEST["idReliquidacion"];
        $idReliquidacionPP = $_REQUEST["idReliquidacionPP"];
        $tipoReliquidacion = $_REQUEST["tipo"];

        //DETERMINAMOS SI ESTA CREANDO O ACTUALIZANDO UN RELIQUIDACION PARA OBTENER
        if ($idReliquidacionPP == 0)
            $ultimaRelquidacionPP = $reliquidacionPP->obtenerUltimaLiquidacion($idReliquidacion);
        else if ($idReliquidacionPP != 0)
            $ultimaRelquidacionPP = $reliquidacionPP->obtenerLiquidacionAnterior($idReliquidacionPP,$idReliquidacion);

        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $reliquidacionPP->load("id_reliquidacion_pp=".$idReliquidacionPP);
        $loadReg2 = $ultimaReliquidacionPP->load("id_reliquidacion_pp=".$ultimaRelquidacionPP);

        $loadReg2 = $operacion->load("id_operacion=".$idOperacion);

        //VALIDAMOS QUE PLANTILLA SE ABRE
        $strPlantilla = "";
        if ($tipoReliquidacion == "PPP")
            $strPlantilla = "reliquidacion_ppp_abono";
        else if ($tipoReliquidacion == "PP")
            $strPlantilla = "reliquidacion_pp_abono";
        else if ($tipoReliquidacion == "PPA")
            $strPlantilla = "reliquidacion_ppa_abono";

        include("./modules/reliquidaciones/templates/".$strPlantilla.".php");

    }

    /**
     * Funciòn para obtener el formulario de inicio de reliquidacion PP
     */
    function reliquidacionPPBase($idReliquidacionPP = 0, $idOperacion = 0, $tipoReliquidacion = ""){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $reliquidacionPP = new operacion_reliquidacion_pp();

        $loadReg1 = $reliquidacionPP->load("id_reliquidacion_pp=".$idReliquidacionPP);

        $loadReg2 = $operacion->load("id_operacion=".$idOperacion);

        //DETERMINAMOS QUE TIPO DE RELIQUIDACION ES
        $plantilla = "";
        if ($tipoReliquidacion == "PP")
            $plantilla = "reliquidacion_pp_base";
        else if ($tipoReliquidacion == "PPA")
            $plantilla = "reliquidacion_ppa_base";
        else if ($tipoReliquidacion == "PPP")
            $plantilla = "reliquidacion_ppp_base";

        include("./modules/reliquidaciones/templates/".$plantilla.".php");

    }

    /**
     * Funciòn para guardar informacion reliquidacion PAGO PARCIAL FECHA PREVISTA
     */
    function saveReliquidacionPP($idReliquidacion) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");

        //INSTANCIAMOS CLASES
        $reliquidacion = new operacion_reliquidacion();
        $reliquidacionPP = new operacion_reliquidacion_pp();

        $loadReg1 = $reliquidacionPP->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);

        //CARGAMOS UN REGISTRO PARA INSERTAR
        if ($reliquidacionPP->id_reliquidacion == null || $reliquidacionPP->id_reliquidacion == 0 || $reliquidacionPP->id_reliquidacion == ""){

            $loadReg1 = $reliquidacionPP->load("id_reliquidacion=0");
        }

        //OBTENEMOS EL TOTAL DEL GIRO FINAL DE LAS FACTURAS RELIQUIDADAS
        $strSQL = "SELECT SUM(valor_giro_final) AS totalFacturasGiroFinal FROM  operacion_factura WHERE id_reliquidacion = " . $idReliquidacion;
        $rsData2 = $db->Execute($strSQL);
        $totalFacturasGiroFinal = $rsData2->fields["totalFacturasGiroFinal"];

        $abonoInversionista = $_POST['abono'];
        //SI EL VALOR DEL ABONO SOBREPASA EL VALOR DEL GIRO FINAL DE LA FACTURA SOLO SE DEBE TOMAR EL VALOR DE LA FACTURA
        if ($abonoInversionista > $totalFacturasGiroFinal)
            $abonoInversionista = $totalFacturasGiroFinal;

        $reliquidacionPP->id_operacion = $reliquidacion->id_operacion;
        $reliquidacionPP->id_reliquidacion = $idReliquidacion;
        $reliquidacionPP->fecha_desembolso = $_POST['fecha_desembolso'];
        $reliquidacionPP->fecha_pago_pactada = $_POST['fecha_pago_pactada'];
        $reliquidacionPP->fecha_movimiento = $_POST['fecha_movimiento'];
        $reliquidacionPP->fecha_real_pago = $_POST['fecha_real_pago'];
        $reliquidacionPP->valor_obligacion_pp = $_POST['valor_obligacion_pp'];
        $reliquidacionPP->abono = $_POST['abono'];
        $reliquidacionPP->abono_inversionista = $abonoInversionista;
        $reliquidacionPP->tasa = $_POST['tasa'];
        $reliquidacionPP->nuevo_valor_obligacion = $_POST['nuevo_valor_obligacion'];
        $reliquidacionPP->intereses_mora = $_POST['intereses_mora'];
        $reliquidacionPP->intereses_devolver = $_POST['intereses_devolver'];

        $reliquidacionPP->Save();
    }

    /**
     * Funciòn para guardar informacion reliquidacion PAGO TOTAL FECHA PREVISTA - PAGO TOTAL ANTICIPADO -
     */
    function saveReliquidacionPagosTotales($idReliquidacion) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");

        //INSTANCIAMOS CLASES
        $reliquidacion = new operacion_reliquidacion();
        $reliquidacionPT = new operacion_reliquidacion_pt();

        $loadReg1 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);

        //CARGAMOS UN REGISTRO PARA INSERTAR
        if ($reliquidacionPT->id_reliquidacion == null || $reliquidacionPT->id_reliquidacion == 0 || $reliquidacionPT->id_reliquidacion == ""){

            $loadReg1 = $reliquidacionPT->load("id_reliquidacion=0");
        }

        $reliquidacionPT->id_operacion = $reliquidacion->id_operacion;
        $reliquidacionPT->id_reliquidacion = $idReliquidacion;
        $reliquidacionPT->fecha_desembolso = $_POST['fecha_desembolso'];
        $reliquidacionPT->fecha_pago_pactada = $_POST['fecha_pago_pactada'];
        $reliquidacionPT->fecha_real_pago = $_POST['fecha_real_pago'];
        $reliquidacionPT->fecha_movimiento = $_POST['fecha_movimiento'];
        $reliquidacionPT->factor_total_tasa = $_POST['factor_total'];
        $reliquidacionPT->deuda_fecha_pago_pactada = $_POST['deuda_fecha_pago_pactada'];
        $reliquidacionPT->valor_pago = $_POST['valor_pago'];
        $reliquidacionPT->otros_descuentos = $_POST['otros_descuentos'];
        $reliquidacionPT->intereses_mora = $_POST['intereses_mora'];
        $reliquidacionPT->intereses_devolver = $_POST['intereses_devolver'];
        $reliquidacionPT->devolucion_remanentes = 0;
        $reliquidacionPT->gmf = 0;
        $reliquidacionPT->monto_devolver = 0;

        //VALIDAMOS SI HAY REAMANENTES A DEVOLVER
        if ($_POST['devolucion_remanentes'] > 0){
            $reliquidacionPT->devolucion_remanentes = $_POST['devolucion_remanentes'];
            $reliquidacionPT->gmf = $_POST['gmf'];
            $reliquidacionPT->monto_devolver = $_POST['devolucion_remanentes'] - $_POST['gmf'];
        }

        $reliquidacionPT->Save();
    }

    /**
     * Funciòn para obtener el formulario de reliquidacion de pagos totales
     */
    function reliquidacionPagos($tipoReliquidacion = ""){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/date_box.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones_extended.php");

        //INSTANCIAMOS CLASES
        $operacion = new operacion();
        $operacionFactura = new operacion_factura();
        $reliquidacionPT = new operacion_reliquidacion_pt();
        $reliquidacionPP = new operacion_reliquidacion_pp();
        $reliquidacion = new operacion_reliquidacion();

        $idReliquidacion = $_REQUEST["id_reliquidacion"];
        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);
        $loadReg1 = $reliquidacionPT->load("id_reliquidacion=".$idReliquidacion);
        $loadReg2 = $operacion->load("id_operacion=".$idOperacion);

        //DETERMINAMOS QUE TIPO DE RELIQUIDACION ES
        $plantilla = "";
        if ($tipoReliquidacion == "PT")
            $plantilla = "reliquidacion_pt";
        else if ($tipoReliquidacion == "PP"){

            //OBTENEMOS LA RELIQUIDACIONES PARCIALES
            $rsDataReliquidacionPPReporteCliente = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
            $rsDataReliquidacionPP = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
            $plantilla = "reliquidacion_pp";

        }
        else if ($tipoReliquidacion == "PTA")
            $plantilla = "reliquidacion_pta";
        else if ($tipoReliquidacion == "PPA"){

            //OBTENEMOS LA RELIQUIDACIONES PARCIALES
            $rsDataReliquidacionPPReporteCliente = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
            $rsDataReliquidacionPP = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
            $plantilla = "reliquidacion_ppa";
        }
        else if ($tipoReliquidacion == "PTP")
            $plantilla = "reliquidacion_ptp";
        else if ($tipoReliquidacion == "PPP"){

            //OBTENEMOS LA RELIQUIDACIONES PARCIALES
            $rsDataReliquidacionPP = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
            $rsDataReliquidacionPPReporteCliente = $reliquidacionPP->getReliquidacionPorReliquidacion($idReliquidacion);
            $plantilla = "reliquidacion_ppp";
        }

        //DETERMINAMOS SI LA RELIQUIDACION ABONA A UNA FACTURA PARA TOMAR EL VALOR DE LA RELIQUIDACION
        if ($reliquidacion->id_factura_abonada != ""){

            $abonoFactura = 0;

            //DETERMINAMOS SI ES ABONO PARCIAL
            $strSQL = "SELECT opp.abono as valorAbonoFactura
                       FROM operacion_factura as of
                       INNER JOIN operacion_reliquidacion_pp as opp ON of.id_reliquidacion = opp.id_reliquidacion
                       WHERE of.id_operacion_factura=" . $reliquidacion->id_factura_abonada . " LIMIT 1";
            $rsData = $db->Execute($strSQL);
            if (!$rsData->EOF)
                $abonoFactura = $rsData->fields["valorAbonoFactura"];

           //DETERMINAMOS SI ES ABONO TOTAL
           $strSQL = "SELECT of.valor_giro_final as valorAbonoFactura
                      FROM operacion_factura as of
                      INNER JOIN operacion_reliquidacion_pt as opt ON of.id_reliquidacion = opt.id_reliquidacion
                      WHERE of.id_operacion_factura=" . $reliquidacion->id_factura_abonada;

            $rsData = $db->Execute($strSQL);
            if (!$rsData->EOF)
                $abonoFactura = $rsData->fields["valorAbonoFactura"];
        }

        include("./modules/reliquidaciones/templates/".$plantilla.".php");

    }


    /**
     * Funciòn para obtener el listado de operaciones
     */
    function listReliquidaciones(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES
        $operaciones = new operaciones();
        $operacion = new operacion();
        $operacionReliquidacion = new operacion_reliquidacion();
        $operacionReliquidacionAbonos = new operacion_reliquidacion_abonos();

        $idOperacion = $_REQUEST["id_operacion"];

        $rsReliquidaciones = $operacionReliquidacion->obtenerReliquidacionesPorOperacion($idOperacion);

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        include("./modules/reliquidaciones/templates/listado_reliquidaciones.php");

    }

    /**
     * Funciòn para obtener la reliquidacion principal para generar el reporte trazabilidad
     */
    function obtenerReliquidacionPrincipal($idReliquidacion = 0, $arrReliquidacionTrazabilidad = array()){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $strSQL = "SELECT id_operacion_factura FROM operacion_factura WHERE id_reliquidacion=" . $idReliquidacion;
        $rsFacturas = $db->Execute($strSQL);
        $arrFacturas = array();
        while (!$rsFacturas->EOF){
            $arrFacturas[] = $rsFacturas->fields["id_operacion_factura"];
            $rsFacturas->MoveNext();
        }

        $strSQL = "SELECT distinct id_reliquidacion FROM operacion_reliquidacion_abonos WHERE id_factura in(".implode(",",$arrFacturas).")";
        $rsReliquidacionFac = $db->Execute($strSQL);
        if (!$rsReliquidacionFac->EOF){
             $idReliquidacionPrincipal = $rsReliquidacionFac->fields["id_reliquidacion"];
             $arrReliquidacionTrazabilidad[] =  $idReliquidacionPrincipal;
             if ($idReliquidacionPrincipal == "")
                return $arrReliquidacionTrazabilidad;
             else
                return $arrReliquidacionTrazabilidad = $this->obtenerReliquidacionPrincipal($idReliquidacionPrincipal,$arrReliquidacionTrazabilidad);
        }
        else{
            return $arrReliquidacionTrazabilidad;
        }

    }

    /**
     * Funciòn para guardar informacion reliquidacion
     */
    function saveReliquidacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./modules/clientes/class_clientes.php");
		require_once("./modules/operaciones/class_operaciones_extended.php");
		require_once("./utilities/class_send_mail.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();
        $reliquidacion = new operacion_reliquidacion();
        $operacion = new operacion();
        $operacionFactura = new operacion_factura();
        $cliente = new clientes();
		$emisor = new clientes_adicionales();        

        $idReliquidacion = $_REQUEST["id_reliquidacion"];
        $idOperacion = $_POST['id_operacion'];

        $loadReg1 = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);
		$loadReg2 = $operacion->load("id_operacion=".$idOperacion); 
        $loadReg3 = $emisor->load("id_cliente=".$operacion->id_emisor);     		
        
        $reliquidacion->id_operacion = $idOperacion;
        //GUARDAMOS LA FECHA REAL DE PAGO, VALIDAMOS SI EXISTE EL DATO O ES UN ABONO
        $reliquidacion->fecha_real_pago = ($_POST['fecha_real_pago']!=""?$_POST['fecha_real_pago']:$_POST['fecha_real_pago_abono']);
        $reliquidacion->id_tipo_reliquidacion = $_POST['id_tipo_reliquidacion'];
        $reliquidacion->aplica_impuesto = $_POST['aplica_impuesto_reli'];
        $reliquidacion->observaciones = $_POST['observaciones'];
        $reliquidacion->num_factura = $_POST['numero_factura'];
        $reliquidacion->id_factura_abonada = 0;
        $reliquidacion->valor_abonado = 0;

        if ($idReliquidacion == 0){
            $reliquidacion->fecha_registro = date("Y-m-d");
            $reliquidacion->estado = 1; //CREADO
        }

        $reliquidacion->id_usuario = $_SESSION["id_user"];
        $reliquidacion->Save();
        
        //BORRAMOS LAS FACTURAS ABONADAS POR RELIQUIDACION
        $strSQL = "DELETE FROM operacion_reliquidacion_abonos WHERE id_reliquidacion=" . $idReliquidacion;
        $db->Execute($strSQL);

        //DETERMINAMOS SI HAY FACTURAS ABONADAS
        if ($_REQUEST["id_factura_abonada"] != ""){

            $arrFacturasAbonadas = $_REQUEST["id_factura_abonada"];
            foreach($arrFacturasAbonadas as $key=>$value){
                $idFactura = $value;
                $reliquidacionAbonos = new operacion_reliquidacion_abonos();
                $loadRegDef = $reliquidacionAbonos->load("id_reliquidacion_abono=0");
                $reliquidacionAbonos->id_reliquidacion = $reliquidacion->id_reliquidacion;
                $reliquidacionAbonos->id_reliquidacion_rel = 0;
                $reliquidacionAbonos->id_factura = $idFactura;
                $reliquidacionAbonos->Save();
            }
        }

        //ACTUALIZAMOS LAS FACTURAS CON LA RELIQUIDACION
        $strSQL = "UPDATE operacion_factura SET id_reliquidacion=0, estado=1 WHERE id_reliquidacion=".$reliquidacion->id_reliquidacion;
        $db->Execute($strSQL);

        $arrFacturas = $_REQUEST["facturas"];
        foreach ($arrFacturas as $key=>$value){

            $idFactura = $value;

            //ACTUALIZAMOS LA FACTURA CON LA RELIQUIDACION GENERADA
            $strSQL = "UPDATE operacion_factura SET id_reliquidacion=" . $reliquidacion->id_reliquidacion . ", estado=".$reliquidacion->id_tipo_reliquidacion." WHERE id_operacion_factura=".$idFactura;
            $db->Execute($strSQL);

            //**********************************************************************
            //ESTE PROCESO SOLO SE EJECUTARÁ PARA OPERACIONES DESPUES DEL 1 DE ENERO
            //**********************************************************************
            if ($operacion->fecha_operacion < '2019-01-01'){

				//ACTUALIZAMOS POR CADA FACTURA LOS NUEVOS VALORES DE FACTURAS EN LA RELIQUIDACION
				//DETERMINAMOS QUE TIPO DE RELIQUIDACION SE ESTA HACIENDO PARA HACER LAS ACTUALIZACIONES CORRESPONDIENTES
				//PAGO TOTAL ANTICIPADO Y PAGO PARCIAL ANTICIPADO
				if ($reliquidacion->id_tipo_reliquidacion == 5 || $reliquidacion->id_tipo_reliquidacion == 6){
					$strSQL = "UPDATE operacion_factura
							   SET
							   descuento_total_reli = descuento_total - ".$_REQUEST["diferencia_descuento_total_xfra_".$idFactura].",
							   margen_argenta_reli = margen_argenta - ".$_REQUEST["diferencia_margen_xfra_".$idFactura].",
							   iva_fra_asesoria_reli = iva_fra_asesoria - ".$_REQUEST["diferencia_iva_xfra_".$idFactura].",
							   fra_argenta_reli = fra_argenta - ".$_REQUEST["diferencia_fra_argenta_xfra_".$idFactura]."
							   WHERE id_operacion_factura=".$idFactura;

					$db->Execute($strSQL);

					//ACTUALIZAMOS EL TOTAL DE LA OPERACION PARA LOS DATOS RELIQUIDADOS
					$operacion->actualizarTotalesOperacionDesdeReliquidacion($reliquidacion->id_operacion);
				}
			}

            //ACTUALIZAMOS POR CADA FACTURA LOS NUEVOS VALORES DE FACTURAS EN LA RELIQUIDACION
            //DETERMINAMOS QUE TIPO DE RELIQUIDACION SE ESTA HACIENDO PARA HACER LAS ACTUALIZACIONES CORRESPONDIENTES
            //PAGO TOTAL ANTICIPADO Y PAGO PARCIAL ANTICIPADO
            if ($reliquidacion->id_tipo_reliquidacion == 5 || $reliquidacion->id_tipo_reliquidacion == 6){
                //ACTUALIZAMOS LAS FACTURAS
                $operacionFactura->actualizarFacturaDesdeReliquidacion($idFactura, $operacion, $reliquidacion->fecha_real_pago);
            }
        }

        //DETERMINAMOS QUE TIPO DE RELIQUIDACION SE ESTA HACIENDO
        //PAGO TOTALES
        if ($reliquidacion->id_tipo_reliquidacion == 3 || $reliquidacion->id_tipo_reliquidacion == 5 || $reliquidacion->id_tipo_reliquidacion == 7){
            
            $this->saveReliquidacionPagosTotales($reliquidacion->id_reliquidacion);

            //CAMBIAMOS EL ESTADO DE CREADO POR FINALIZADO SOLO EN LAS RELIQUIDACIONES DE PAGO TOTAL
            $reliquidacionTemp = new operacion_reliquidacion();
            $loadReg1 = $reliquidacionTemp->load("id_reliquidacion=".$reliquidacion->id_reliquidacion);
            $reliquidacionTemp->estado = 2; //FINALIZADO
            $reliquidacionTemp->Save();
            
            //ACTUALIZAMOS DATOS DE LA FACTURACION PAGOS TOTALES
			$reliquidacion->actualizacionDatosFacturacion($reliquidacion->id_reliquidacion, $emisor);
        }
        //PAGO PARCIAL
        else if ($reliquidacion->id_tipo_reliquidacion == 4 || $reliquidacion->id_tipo_reliquidacion == 6 || $reliquidacion->id_tipo_reliquidacion == 8){
            
            $this->saveReliquidacionPP($reliquidacion->id_reliquidacion);
            
            //ACTUALIZAMOS DATOS DE LA FACTURACION PAGOS PARCIALES SOLO PRIMER ABONO
			$reliquidacion->actualizacionDatosFacturacionPP($reliquidacion->id_reliquidacion, $emisor);            
        }

        $jsondata['Message'] = utf8_encode("Transacción exitosa. Espere por favor...");
        $jsondata['IdReliquidacion'] = $reliquidacion->id_reliquidacion;
        $jsondata['Success'] = true;	


        //SI ESTA CREANDO SE ENVIA CORREO
        if ($idReliquidacion==0){
        	$loadReg4 = $cliente->load("id_cliente=".$operacion->id_emisor);
			$fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
			$fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
			$subjectMail = "Argenta - Confirmación registro de reliquidacion - Operación: ".$operacion->id_operacion." - Emisor" . $operacion->id_emisor;
			$templateMail = "mailReliquidacionCliente";
			$arrVarsReplace = array("OPERACION"=>$reliquidacion->id_operacion,"NAME"=>$cliente->representante_legal,"RELIQUIDACION"=>$reliquidacion->id_reliquidacion);
			$toEmail = $cliente->correo_personal;
			//$toEmail = "andres.jap@gmail.com;eleyva@argentaestructuradores.com";
			$success = $sendMail->enviarMail($fromName,$fromEmail,$cliente->representante_legal,$toEmail,$subjectMail,$templateMail,array(),$arrVarsReplace);
		}

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un reliquidacion
     */
    function reliquidacion() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones_extended.php");

        $operacion = new operacion();
        $operacionFacturas = new operacion_factura();
        $facturasAbonadas = new operacion_reliquidacion_abonos();
        $reliquidacion = new operacion_reliquidacion();

        $idReliquidacion = $_REQUEST["id_reliquidacion"];
        $idOperacion = $_REQUEST["id_operacion"];

        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);
        $loadReg = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);

        //OBTENEMOS LAS FACTURAS DE LA OPERACION
        $rsFacturasOperacion = $operacionFacturas->getFacturasPorOperacionSinReliquidar($idOperacion,$idReliquidacion);

        //OBTENEMOS LAS FACTURAS ABONADAS
        $arrFacturasAbonadas = $facturasAbonadas->getArrFacturasAbonadasReliquidacion($idReliquidacion);

        //OBTENEMOS LAS FACTURAS QUE NO SE HAN PAGADO DEL EMISOR DE LA OPERACION
        $arrFacturasEmisor = $operacionFacturas->getFacturasSinReliquidarPorEmisor($operacion->id_emisor, $arrFacturasAbonadas);

        include("./modules/reliquidaciones/templates/reliquidacion.php");

    }

    /**
     * Funciòn para eliminar reliquidacion
     */
    function eliminarReliquidacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/operaciones/class_operaciones_extended.php");

        //INSTANCIAMOS CLASES
        $reliquidacion = new operacion_reliquidacion();
        $operacion = new operacion();

        $idReliquidacion = $_REQUEST["id_reliquidacion"];

        $loadReg1 = $reliquidacion->load("id_reliquidacion=".$idReliquidacion);

        $reliquidacion->Delete();

        //ACTUALIZAMOS LAS FACTURAS DE LA RELIQUIDACION
        $strSQL = "UPDATE operacion_factura SET
        			id_reliquidacion=0,
        			estado=1,
					descuento_total_reli = descuento_total,
					margen_argenta_reli = margen_argenta,
					iva_fra_asesoria_reli = iva_fra_asesoria,
					fra_argenta_reli = fra_argenta
        			WHERE id_reliquidacion=".$idReliquidacion;
        $db->Execute($strSQL);

        //ACTUALIZAMOS EL TOTAL DE LA OPERACION PARA LOS DATOS RELIQUIDADOS
        $operacion->actualizarTotalesOperacionDesdeReliquidacion($reliquidacion->id_operacion);

        //ELIMINAMOS LAS RELIQUIDACIONES
        $strSQL = "DELETE FROM operacion_reliquidacion_pp WHERE id_reliquidacion=".$idReliquidacion;
        $db->Execute($strSQL);

        $strSQL = "DELETE FROM operacion_reliquidacion_pt WHERE id_reliquidacion=".$idReliquidacion;
        $db->Execute($strSQL);

        //ELIMINAMOS LOS ABONOS
        $strSQL = "DELETE FROM operacion_reliquidacion_abonos WHERE id_reliquidacion=" . $idReliquidacion;
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
