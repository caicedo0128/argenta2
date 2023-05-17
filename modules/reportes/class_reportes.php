<?php
/**
* Adminsitración del modulo reportes
* @version 1.0
* El constructor de esta clase es {@link reportes()}
*/
class reportes{


    var $Database;
    var $ID;
    var $arrRangosFecha = array("1"=>"Mensual", "2"=>"Trimestral", "3"=>"Semestral", "4"=>"Anual");
	var $arrEstados = array("1"=>"VIGENTE","2"=>"CANCELADA", "3"=>"CREADA");

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$idDeclaratoria,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "exportarExcel":
                            $this->exportarExcel();
                            break;
            case "guardarReportePDF":
                            $this->guardarReportePDF();
                            break;
            case "enviarReporte":
                            $this->enviarReporte();
                            break;
            case "reporteInversiones":
                            $this->reporteInversiones();
                            break;
            case "generarReporteInversiones":
                            $this->generarReporteInversionesParticipacion();
                            break;
            case "reporteFacturasVigentes":
                            $this->reporteFacturasVigentes();
                            break;
            case "generarReporteFacturasVigentes":
                            $this->generarReporteFacturasVigentes();
                            break;
            case "generarReporteFacturasVigentesCliente":
                            $this->generarReporteFacturasVigentesCliente();
                            break;
            case "reporteFacturasCanceladas":
                            $this->reporteFacturasCanceladas();
                            break;
            case "generarReporteFacturasCanceladas":
                            $this->generarReporteFacturasCanceladas();
                            break;
            case "reporteComisiones":
                            $this->reporteComisiones();
                            break;
            case "generarReporteComisiones":
                            $this->generarReporteComisiones();
                            break;
            case "reporteContable":
                            $this->reporteContable();
                            break;
            case "generarReporteContable":
                            $this->generarReporteContable();
                            break;
            case "generarReporteDetalleContable":
                            $this->generarReporteDetalleContable();
                            break;
            case "reporteMovimiento":
                            $this->reporteMovimiento();
                            break;
            case "generarReporteMovimiento":
                            $this->generarReporteMovimiento();
                            break;
            case "reporteFacturacion":
                            $this->reporteFacturacion();
                            break;
            case "generarReporteFacturacion":
                            $this->generarReporteFacturacion();
                            break;
            case "reporteCartera":
                            $this->reporteCartera();
                            break;
            case "generarReporteCartera":
                            $this->generarReporteCartera();
                            break;
            case "reporteCarteraVencida":
                            $this->reporteCarteraVencida();
                            break;
            case "generarReporteCarteraVencida":
                            $this->generarReporteCarteraVencida();
                            break;  
            case "reporteCarteraMora":
                            $this->reporteCarteraMora();
                            break;                             
            case "generarReporteCarteraMora":
                            $this->generarReporteCarteraMora();
                            break;                            
        }
    }
    
	/**
      * Funciòn para generar el reporte cartera  mora
      */
    function generarReporteCarteraMora() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;
		
		//INCLUIMOS ARCHIVOS NECESARIOS
		require_once("./modules/operaciones/class_operaciones.php");

		//INSTANCIAMOS CLASES NECESARIAS
		$operaciones = new operaciones();
		$operacion = new operacion();

		$tipoReporte = $_REQUEST["tipo"];

		$strSQL = "SELECT
					orepp.valor_pago,
					orepp.valor_obligacion_abono,
					orepp.interes_mora,
					orepp.id_reliquidacion,
					of.prefijo,
					of.num_factura,
					of.fecha_pago,
					of.fecha_emision,
					of.fecha_vencimiento as fecha_vencimiento_factura,
					of.valor_giro_final,
					of.valor_futuro,
					of.valor_neto,
					of.valor_bruto,
					of.descuento_total,
					of.margen_inversionista,
					of.margen_argenta,
					of.iva_fra_asesoria,
					of.fra_argenta,
					of.giro_antes_gmf,
					of.gmf,
					of.valor_giro_final,
					of.estado,
					of.aplica_otros,
					o.id_operacion,
					o.fecha_operacion,
					o.tasa_inversionista,
					o.porcentaje_descuento,
					o.factor,
					o.id_ejecutivo,
					o.valor_otros_operacion,
					o.comision,
					c1.identificacion as emisor_identificacion,
					c1.razon_social as emisor,
					c1.direccion as direccion,
					c1.representante_legal as representante_legal,
					c1.encargado as persona_contacto,
					c1.cargo_autorizador as cargo,
					c2.identificacion as pagador_identificacion,
					c2.razon_social as pagador,
					c3.razon_social as ejecutivo
					FROM operacion as o
					INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
					INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
					INNER JOIN clientes as c3 ON c3.id_cliente = o.id_ejecutivo
					INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
					LEFT JOIN
					(
						SELECT
							orpp.id_reliquidacion,
							ore.id_tipo_reliquidacion,
							sum(orpp.abono) AS valor_pago,
							sum(orpp.valor_obligacion_pp) AS valor_obligacion_abono,
							sum(orpp.intereses_mora) AS interes_mora
						FROM
							operacion_reliquidacion as ore
							INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
						WHERE ore.estado = 1
						GROUP BY
							orpp.id_reliquidacion
					)
					as orepp ON of.id_reliquidacion = orepp.id_reliquidacion
					";

		$strSQL .= " WHERE (o.estado=1 or o.estado=2) AND (of.estado = 1 OR (orepp.id_tipo_reliquidacion IN (4,6,8)))";

		if ($_REQUEST["fecha_inicio"] != "")
			$strSQL .= " AND of.fecha_pago >= '".$_REQUEST["fecha_inicio"]."'";

		if ($_REQUEST["fecha_fin"] != "")
			$strSQL .= " AND of.fecha_pago <= '".$_REQUEST["fecha_fin"]."'";

		if ($_REQUEST["id_emisor"] != "")
			$strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

		if ($_REQUEST["id_pagador"] != "")
			$strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

		$strSQL .= " ORDER BY o.id_operacion, of.fecha_pago ASC";

		$rsDatos = $db->Execute($strSQL);

        $template = "generar_reporte_cartera_mora.php";
        if ($tipoReporte == 3)
            $template = "generar_reporte_cartera_mora_consolidado.php";
		
        include("./modules/reportes/templates/" . $template);
    }    
    
    /**
      * Funciòn para ver el formulario reporte cartera  vencida
      */
    function reporteCarteraMora() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();

        include("./modules/reportes/templates/reporte_cartera_mora.php");
    }    

    /**
      * Funciòn para generar el reporte cartera  vencida
      */
    function generarReporteCarteraVencida() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];

        $strSQL = "SELECT orepp.fecha_real_pago_abono, orepp.nuevo_valor_obligacion, orepp.valor_pago, orepp.valor_obligacion_abono, orepp.interes_mora,  orepp.id_reliquidacion, of.prefijo, of.num_factura, of.fecha_pago,of.valor_giro_final, of.valor_futuro, of.estado, o.id_operacion, o.fecha_operacion, c1.razon_social as emisor, c2.razon_social as pagador
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    LEFT JOIN
                    (
                        SELECT
                            orpp.id_reliquidacion,
                            ore.id_tipo_reliquidacion,
                            orpp.fecha_real_pago AS fecha_real_pago_abono,
                            orpp.nuevo_valor_obligacion AS nuevo_valor_obligacion,
                            sum(orpp.abono) AS valor_pago,
                            sum(orpp.valor_obligacion_pp) AS valor_obligacion_abono,
                            sum(orpp.intereses_mora) AS interes_mora
                        FROM
                        	operacion_reliquidacion as ore
                            INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
						WHERE ore.estado=1
                        GROUP BY
                            orpp.id_reliquidacion,
                            orpp.nuevo_valor_obligacion,
                            orpp.fecha_real_pago
                    )
                    as orepp ON of.id_reliquidacion = orepp.id_reliquidacion
                    WHERE (of.estado = 1 OR (orepp.id_tipo_reliquidacion IN (4,6,8)))
                    ";


        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND of.fecha_pago >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND of.fecha_pago <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND o.id_inversionista = ".$_REQUEST["id_inversionista"];

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

        $strSQL .= " ORDER BY o.id_operacion, of.fecha_pago ASC";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_cartera_vencida.php");
    }

    /**
      * Funciòn para ver el formulario reporte cartera  vencida
      */
    function reporteCarteraVencida() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();

        include("./modules/reportes/templates/reporte_cartera_vencida.php");
    }

    /**
      * Funciòn para generar el reporte de cartera
      */
    function generarReporteCartera() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];
        $reporteInversionista = false;

        $strSQL = "SELECT orepp.fecha_real_pago_abono, orepp.nuevo_valor_obligacion,
        				  orepp.valor_pago, orepp.valor_obligacion_abono, orepp.interes_mora,
        				  orepp.id_reliquidacion, of.prefijo, of.num_factura, of.fecha_pago,of.valor_giro_final,
        				  of.valor_futuro, of.estado, o.id_operacion, o.fecha_operacion,
        				  c1.razon_social as emisor, c2.razon_social as pagador";


		//SI HAY INVERSIONISTA SACAMOS LOS DATOS DE PARTICIPACION
		if ($_REQUEST["id_inversionista"] != ""){
        	$strSQL .= ",ofp.valor_participacion";
        	$reporteInversionista = true;
       	}

		$strSQL .= "
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    LEFT JOIN
                    (
                        SELECT
                            orpp.id_reliquidacion,
                            ore.id_tipo_reliquidacion,
                            orpp.fecha_real_pago AS fecha_real_pago_abono,
                            orpp.nuevo_valor_obligacion AS nuevo_valor_obligacion,
                            sum(orpp.abono) AS valor_pago,
                            sum(orpp.valor_obligacion_pp) AS valor_obligacion_abono,
                            sum(orpp.intereses_mora) AS interes_mora
                        FROM
                        	operacion_reliquidacion as ore
                            INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
                        WHERE ore.estado = 1
                        GROUP BY
                            orpp.id_reliquidacion,
                            orpp.nuevo_valor_obligacion,
                            orpp.fecha_real_pago
                    )
                    as orepp ON of.id_reliquidacion = orepp.id_reliquidacion
					";

		//SI HAY INVERSIONISTA SACAMOS LOS DATOS DE PARTICIPACION
		if ($_REQUEST["id_inversionista"] != ""){
        	$strSQL .= " LEFT JOIN operacion_factura_participacion as ofp ON of.id_operacion_factura = ofp.id_operacion_factura AND ofp.id_inversionista=" . $_REQUEST["id_inversionista"];
       	}

        $strSQL .= " WHERE (of.estado = 1 OR (orepp.id_tipo_reliquidacion IN (4,6,8)))";


        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND of.fecha_pago >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND of.fecha_pago <= '".$_REQUEST["fecha_fin"]."'";

        //if ($_REQUEST["id_inversionista"] != "")
        //    $strSQL .= " AND o.id_inversionista = ".$_REQUEST["id_inversionista"];

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

        $strSQL .= " ORDER BY o.id_emisor, o.id_operacion, of.fecha_pago ASC";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_cartera.php");
    }

    /**
      * Funciòn para ver el formulario reporte cartera
      */
    function reporteCartera() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        include("./modules/reportes/templates/reporte_cartera.php");
    }

	/**
      * Funciòn para generar el reporte de inversiones participacion
      */
    function generarReporteInversionesParticipacion() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();
        $factura = new operacion_factura();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];


        $strSQL = "
					select
						SUM(pip.totalPagos) as totalReintegro,
						SUM(DISTINCT pio.totalInversion) as totalInversion,
						pio.id_inversionista,
						o.id_operacion,
						o.num_factura,
						o.estado,
						o.fecha_operacion,
						c1.razon_social as emisor,
						c2.razon_social as pagador,
						pip.fecha_real_pago
					from
					(
						select
							sum(ofp.valor_participacion) as totalInversion,
							ofp.id_inversionista,
							of.id_operacion
						from
							operacion_factura_participacion as ofp
							INNER JOIN operacion_factura as of ON ofp.id_operacion_factura = of.id_operacion_factura
						where ofp.id_inversionista = ".$_REQUEST["id_inversionista"]."
						GROUP BY ofp.id_inversionista, of.id_operacion
					) as pio
					INNER JOIN operacion as o ON pio.id_operacion = o.id_operacion
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
					LEFT JOIN
					(
						select
							sum(ofp.valor_participacion) as totalPagos,
							ofp.id_inversionista,
							of.id_reliquidacion,
							of.id_operacion,
							orel.fecha_real_pago
						from
							operacion_factura_participacion as ofp
							INNER JOIN operacion_factura as of ON ofp.id_operacion_factura = of.id_operacion_factura
							INNER JOIN operacion_reliquidacion as orel ON of.id_reliquidacion = orel.id_reliquidacion
						WHERE of.id_reliquidacion <> 0 and ofp.id_inversionista = ".$_REQUEST["id_inversionista"]."
						GROUP BY ofp.id_inversionista,of.id_reliquidacion, of.id_operacion, orel.fecha_real_pago
					) as pip ON pio.id_inversionista = pip.id_inversionista AND pio.id_operacion = pip.id_operacion
					WHERE 1=1
                    ";

        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND o.fecha_operacion >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND o.fecha_operacion <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND pio.id_inversionista = ".$_REQUEST["id_inversionista"];

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

        if ($_REQUEST["estado"] != "")
            $strSQL .= " AND o.estado = ".$_REQUEST["estado"];

        $strSQL .= " GROUP BY o.id_operacion, pio.id_inversionista, c1.razon_social, c2.razon_social,pip.fecha_real_pago
					 ORDER BY o.id_operacion DESC";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_inversiones_participacion.php");
    }

    /**
      * Funciòn para generar el reporte de facturacion
      */
    function generarReporteFacturacion() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();
        $factura = new operacion_factura();

        $strSQL = "SELECT o.*, c1.razon_social as emisor, c2.razon_social as pagador, c3.razon_social as inversionista
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN clientes as c3 ON c3.id_cliente = o.id_inversionista
                    WHERE 1=1 AND (o.estado=1 or o.estado=2) ";

        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND o.fecha_operacion >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND o.fecha_operacion <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = '".$_REQUEST["id_pagador"]."'";

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = '".$_REQUEST["id_emisor"]."'";

        if ($_REQUEST["id_ejecutivo"] != "")
            $strSQL .= " AND o.id_ejecutivo = '".$_REQUEST["id_ejecutivo"]."'";

        if ($_REQUEST["tipo"] == "2")
            $strSQL .= " AND (o.facturado is null OR o.facturado = 2)";

        if ($_REQUEST["tipo"] == "1")
            $strSQL .= " AND o.facturado = 1 ";

        $strSQL .= " ORDER BY o.id_operacion DESC";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_facturacion.php");
    }

    /**
      * Funciòn para ver el formulario reporte facturacion
      */
    function reporteFacturacion() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS EJECUTIVOS
        $arrEjecutivos = $clientes->obtenerClientesPorTipoTercero(5);

        include("./modules/reportes/templates/reporte_facturacion.php");
    }

    /**
      * Funciòn para generar el reporte de movimiento
      */
    function generarReporteMovimiento() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/reliquidaciones/class_reliquidaciones.php");

        $strSQL = "SELECT sum( valor_mov ) as valor_mov , fecha_mov
                    FROM v_movimientos
                    WHERE 1 =1";

        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND fecha_mov >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND fecha_mov <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND id_inversionista = ".$_REQUEST["id_inversionista"];

        $strSQL .= " GROUP BY fecha_mov ORDER BY fecha_mov ASC ";

        $rsDatos = $db->Execute($strSQL);

        //OBTENEMOS LOS VALORES INICIALES DEL REPORTE
        $strSQL = "SELECT SUM(valor_mov) as valor, MAX(fecha_mov) as max_fecha_mov
                    FROM v_movimientos
                    WHERE 1=1";

        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND fecha_mov < '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND id_inversionista = ".$_REQUEST["id_inversionista"];

        $rsDatoInicial = $db->Execute($strSQL);

        while (!$rsDatoInicial->EOF){
            $arrDatosIniciales["valor"]=$rsDatoInicial->fields["valor"];
            $arrDatosIniciales["fecha"]=$rsDatoInicial->fields["max_fecha_mov"];
            $rsDatoInicial->MoveNext();
        }

        include("./modules/reportes/templates/generar_reporte_movimientos.php");
    }

    /**
      * Funciòn para ver el formulario reporte movimiento
      */
    function reporteMovimiento() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        include("./modules/reportes/templates/reporte_movimientos.php");
    }

    /**
      * Funciòn para ver el formulario reporte detalle contable
      */
    function generarReporteDetalleContable() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/reliquidaciones/class_reliquidaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();
        $operacionFactura = new operacion_factura();
        $reliquidacion = new operacion_reliquidacion();
        $emisor = new clientes();
        $pagador = new clientes();

        $idOperacion = $_REQUEST["id_operacion"];
        $esReporte = $_REQUEST["es_reporte"];

        //OBTENEMOS DATOS DE LA RELIQUIDACION
        $strSQL = "SELECT
                    SUM(valor_pago) as valor_pago,
                    SUM(devolucion_remanentes) as devolucion_remanentes,
                    SUM(gmf) as gmf,
                    SUM(otros_descuentos) as otros_descuentos,
                    SUM(intereses_devolver) as intereses_devolver,
                    SUM(intereses_mora) as intereses_mora
                   FROM operacion_reliquidacion_pt WHERE id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);

        $valorPagoAbono = $rsData->fields["valor_pago"];
        $otros = $rsData->fields["otros_descuentos"];
        $gmf = $rsData->fields["gmf"];
        $devolucionRemanentes = $rsData->fields["devolucion_remanentes"];
        $interesMora = $rsData->fields["intereses_mora"];
        $interesDevolver = $rsData->fields["intereses_devolver"];

        $strSQL = "SELECT
                   SUM(abono) as abono,
                   SUM(devolucion_remanentes) as devolucion_remanentes,
                   SUM(gmf) as gmf,
                   SUM(otros) as otros,
                   SUM(intereses_devolver) as intereses_devolver,
                   SUM(intereses_mora) as intereses_mora
                   FROM operacion_reliquidacion_pp WHERE id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);

        $valorPagoAbono += $rsData->fields["abono"];
        $otros += $rsData->fields["otros"];
        $gmf += $rsData->fields["gmf"];
        $devolucionRemanentes += $rsData->fields["devolucion_remanentes"];
        $interesMora += $rsData->fields["intereses_mora"];
        $interesDevolver += $rsData->fields["intereses_devolver"];

        //OBTENEMOS LAS FACTURAS PAGADAS
        $arrFacturasReliquidadas = $operacionFactura->getArrFacturasReliquidadasPorOperacion($idOperacion);

        $arrFacturasIds = implode(",",(array_keys($arrFacturasReliquidadas)));

        //OBTENEMOS DATOS DE LA LIQUIDACION DE FACTURAS
        $strSQL = "SELECT
        			SUM(margen_inversionista) as intereses_corrientes,
                    SUM(descuento_total) as descuento_total,
                    SUM(fra_argenta) as fra_argenta,
                    SUM(iva_fra_asesoria) as iva_fra_asesoria
                   FROM operacion_factura
                   WHERE id_operacion_factura IN (".$arrFacturasIds.")";

        $rsDataFacturas = $db->Execute($strSQL);

        //OBTENEMOS DATOS DE LA OPERACION
        $loadReg2 = $operacion->load("id_operacion=" . $idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);
        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        include("./modules/reportes/templates/reporte_detalle_contable.php");
    }

    /**
      * Funciòn para generar el reporte de contable
      */
    function generarReporteContable() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/reliquidaciones/class_reliquidaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();
        $reliquidacion = new reliquidaciones();

        $strSQL = "SELECT o.id_operacion, o.fecha_pago_operacion, o.estado as estado_operacion, o.num_factura, o.fecha_operacion, o.valor_giro_final, o.valor_neto, c1.razon_social as emisor, c2.razon_social as pagador
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    WHERE 1=1 AND o.estado = 2 ";

        if ($_REQUEST["fecha_operacion"] != "")
            $strSQL .= " AND o.fecha_operacion = '".$_REQUEST["fecha_operacion"]."'";

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = '".$_REQUEST["id_pagador"]."'";

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = '".$_REQUEST["id_emisor"]."'";

        $strSQL .= " ORDER BY o.id_operacion DESC ";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_contable.php");
    }

    /**
      * Funciòn para ver el formulario reporte contable
      */
    function reporteContable() {

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

        include("./modules/reportes/templates/reporte_contable.php");
    }

    /**
      * Funciòn para generar el reporte de comisiones
      */
    function generarReporteComisiones() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();
        $factura = new operacion_factura();

        $strSQL = "SELECT o.*, fac.*, c1.razon_social as emisor, c2.razon_social as pagador, c3.razon_social as ejecutivo
                    FROM operacion as o
                   	INNER JOIN (
                   		SELECT SUM(margen_argenta_reli) as total_margen_reli, SUM(margen_argenta) as total_margen, id_operacion
                   		FROM
                   		operacion_factura
                   		GROUP BY id_operacion
                   	) as fac ON o.id_operacion = fac.id_operacion
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN clientes as c3 ON c3.id_cliente = o.id_ejecutivo
                    WHERE 1=1 ";

        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= "AND o.fecha_operacion >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= "AND o.fecha_operacion <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= "AND o.id_pagador = '".$_REQUEST["id_pagador"]."'";

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= "AND o.id_emisor = '".$_REQUEST["id_emisor"]."'";

        if ($_REQUEST["id_ejecutivo"] != "")
            $strSQL .= "AND o.id_ejecutivo = '".$_REQUEST["id_ejecutivo"]."'";


        if ($_REQUEST["tipo"] == "2")
            $strSQL .= "AND (o.fecha_pago_comision is null OR o.fecha_pago_comision = '' OR o.fecha_pago_comision = '0000-00-00') ";

        if ($_REQUEST["tipo"] == "1")
            $strSQL .= "AND (o.fecha_pago_comision != '' AND o.fecha_pago_comision != '0000-00-00') ";

        $strSQL .= "ORDER BY o.id_operacion DESC";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_comisiones.php");
    }

    /**
      * Funciòn para ver el formulario reporte comisiones
      */
    function reporteComisiones() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS EJECUTIVOS
        $arrEjecutivos = $clientes->obtenerClientesPorTipoTercero(5);

        include("./modules/reportes/templates/reporte_comisiones.php");
    }

    /**
      * Funciòn para generar el reporte de facturas canceladas
      */
    function generarReporteFacturasCanceladas() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];

		$fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];
        $tipoReporte = $_REQUEST["tipo_reporte"];
        $filtroInversionista = false;

        $strSQL = "SELECT
        			ore.fecha_real_pago,
        			ore.id_reliquidacion,
        			ore.estado,
        			COALESCE(orepp.fecha_real_pago_abono, orept.fecha_real_pago) as fecha_real_pago_abono,
        			COALESCE(orepp.valor_pago, orept.valor_pago) as valor_pago,
        			of.prefijo,
					of.num_factura,
					of.fecha_pago,
					of.fecha_emision,
        			of.fecha_vencimiento as fecha_vencimiento_factura,
        			of.valor_giro_final,
        			of.valor_futuro,
        			of.valor_neto,
        			of.descuento_total,
        			of.margen_inversionista,
        			of.margen_argenta,
        			of.iva_fra_asesoria,
        			of.fra_argenta,
        			of.giro_antes_gmf,
        			of.gmf,
        			of.valor_giro_final,
        			of.estado,
        			o.id_operacion,
        			o.fecha_operacion,
        			o.tasa_inversionista,
        			o.porcentaje_descuento,
        			o.factor,
        			o.id_ejecutivo,
        			o.valor_otros_operacion,
        			o.comision,
        			c1.identificacion as emisor_identificacion,
        			c1.razon_social as emisor,
        			c2.identificacion as pagador_identificacion,
        			c2.razon_social as pagador,
        			c3.razon_social as ejecutivo";

		//SI HAY INVERSIONISTA SACAMOS LOS DATOS DE PARTICIPACION
		if ($_REQUEST["id_inversionista"] != ""){
        	$strSQL .= ",ofp.valor_participacion";
        	$filtroInversionista = true;
       	}

		$strSQL .= "
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN clientes as c3 ON c3.id_cliente = o.id_ejecutivo
                    INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    LEFT JOIN operacion_reliquidacion as ore ON of.id_reliquidacion = ore.id_reliquidacion
                    LEFT JOIN
                    (
                        SELECT
                            orpp.id_reliquidacion,
                            min(orpp.fecha_real_pago) AS fecha_real_pago_abono,
                            sum(orpp.abono) AS valor_pago,
                            sum(orpp.valor_obligacion_pp) AS valor_obligacion_abono
                        FROM
                        	operacion_reliquidacion as ore
                            INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
						WHERE ore.estado = 2
                        GROUP BY
                            orpp.id_reliquidacion
                    )
                    as orepp ON ore.id_reliquidacion = orepp.id_reliquidacion
                    LEFT JOIN
                    (
                        SELECT
                            orpt.id_reliquidacion,
                            min(orpt.fecha_real_pago) AS fecha_real_pago,
                            sum(orpt.valor_pago) AS valor_pago
                        FROM
                        	operacion_reliquidacion as ore
                            INNER JOIN operacion_reliquidacion_pt as orpt ON ore.id_reliquidacion = orpt.id_reliquidacion
						WHERE ore.estado = 2
                        GROUP BY
                            orpt.id_reliquidacion
                    )
                    as orept ON ore.id_reliquidacion = orept.id_reliquidacion
                    ";

		//SI HAY INVERSIONISTA SACAMOS LOS DATOS DE PARTICIPACION
		if ($_REQUEST["id_inversionista"] != ""){
        	$strSQL .= " LEFT JOIN operacion_factura_participacion as ofp ON of.id_operacion_factura = ofp.id_operacion_factura AND ofp.id_inversionista=" . $_REQUEST["id_inversionista"];
       	}

		$strSQL .= " WHERE (o.estado=1 or o.estado=2) AND (of.estado <> 1 AND ore.estado=2)";


        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND of.fecha_pago >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND of.fecha_pago <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_emisor"] != "")
           	$strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

		//DETERMINAMOS SI ES PERFIL CLIENTE PARA BUSCAR SOLO LA INFORMACION PROPIA
       	if ($_SESSION["profile_text"]=="Cliente"){
			$strSQL .= " AND o.id_emisor = ".$_SESSION["id_tercero"];
       	}

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND ofp.valor_participacion > 0";

        $strSQL .= " ORDER BY o.id_operacion, of.fecha_pago ASC";

        $rsDatos = $db->Execute($strSQL);

		if ($_SESSION["profile_text"]!="Cliente"){
			$strTemplate = "generar_reporte_facturas_canceladas.php";
			if ($tipoReporte == "EXP")
				$strTemplate = "generar_reporte_facturas_canceladas_exportar.php";
		}
		else{
			$strTemplate = "generar_reporte_facturas_canceladas_cliente.php";
			if ($tipoReporte == "EXP")
				$strTemplate = "generar_reporte_facturas_canceladas_exportar_cliente.php";
		}

        include("./modules/reportes/templates/" . $strTemplate);
    }

    /**
      * Funciòn para ver el formulario reporte facturas canceladas
      */
    function reporteFacturasCanceladas() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();

        include("./modules/reportes/templates/reporte_facturas_canceladas.php");
    }

    /**
      * Funciòn para generar el reporte de facturas vigentes para cliente
      */
    function generarReporteFacturasVigentesCliente() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];
        $tipoReporte = $_REQUEST["tipo_reporte"];

        $strSQL = "SELECT
        			of.prefijo,
					of.num_factura,
					of.fecha_pago,
					of.fecha_emision,
        			of.fecha_vencimiento as fecha_vencimiento_factura,
        			of.valor_giro_final,
        			of.valor_futuro,
        			of.valor_neto,
                    of.valor_bruto,
        			of.descuento_total,
        			of.margen_inversionista,
        			of.margen_argenta,
        			of.iva_fra_asesoria,
        			of.fra_argenta,
        			of.giro_antes_gmf,
        			of.gmf,
        			of.valor_giro_final,
        			of.estado,
        			of.aplica_otros,
        			o.id_operacion,
        			o.fecha_operacion,
        			o.tasa_inversionista,
        			o.porcentaje_descuento,
        			o.factor,
        			o.id_ejecutivo,
        			o.valor_otros_operacion,
        			o.comision,
        			c1.identificacion as emisor_identificacion,
        			c1.razon_social as emisor,
        			c2.identificacion as pagador_identificacion,
        			c2.razon_social as pagador,
        			c3.razon_social as ejecutivo";

		$strSQL .= "
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN clientes as c3 ON c3.id_cliente = o.id_ejecutivo
                    INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    ";


		$strSQL .= " WHERE (o.estado=1) AND (of.estado = 1)";


        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND of.fecha_pago >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND of.fecha_pago <= '".$_REQUEST["fecha_fin"]."'";

		//DETERMINAMOS SI ES PERFIL CLIENTE PARA BUSCAR SOLO LA INFORMACION PROPIA
       	if ($_SESSION["profile_text"]=="Cliente"){
			$strSQL .= " AND o.id_emisor = ".$_SESSION["id_tercero"];
       	}

        $strSQL .= " ORDER BY o.id_operacion, of.fecha_pago ASC";

        $rsDatos = $db->Execute($strSQL);

		$strTemplate = "generar_reporte_facturas_vigentes_cliente.php";
		if ($tipoReporte == "EXP")
			$strTemplate = "generar_reporte_facturas_vigentes_exportar_cliente.php";

        include("./modules/reportes/templates/" . $strTemplate);
    }

    /**
      * Funciòn para generar el reporte de facturas vigentes
      */
    function generarReporteFacturasVigentes() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];
        $tipoReporte = $_REQUEST["tipo_reporte"];
        $filtroInversionista = false;

        $strSQL = "SELECT
        			orepp.fecha_real_pago_abono,
        			orepp.nuevo_valor_obligacion,
        			orepp.valor_pago,
        			orepp.valor_obligacion_abono,
        			orepp.interes_mora,
        			orepp.id_reliquidacion,
					of.prefijo,
					of.num_factura,
					of.fecha_pago,
					of.fecha_emision,
        			of.fecha_vencimiento as fecha_vencimiento_factura,
        			of.valor_giro_final,
        			of.valor_futuro,
        			of.valor_neto,
                    of.valor_bruto,
        			of.descuento_total,
        			of.margen_inversionista,
        			of.margen_argenta,
        			of.iva_fra_asesoria,
        			of.fra_argenta,
        			of.giro_antes_gmf,
        			of.gmf,
        			of.valor_giro_final,
        			of.estado,
        			of.aplica_otros,
        			o.id_operacion,
        			o.fecha_operacion,
        			o.tasa_inversionista,
        			o.porcentaje_descuento,
        			o.factor,
        			o.id_ejecutivo,
        			o.valor_otros_operacion,
        			o.comision,
        			c1.identificacion as emisor_identificacion,
        			c1.razon_social as emisor,
        			c1.direccion as direccion,
        			c1.representante_legal as representante_legal,
        			c1.encargado as persona_contacto,
        			c1.cargo_autorizador as cargo,
        			c2.identificacion as pagador_identificacion,
        			c2.razon_social as pagador,
        			c3.razon_social as ejecutivo";

		//SI HAY INVERSIONISTA SACAMOS LOS DATOS DE PARTICIPACION
		if ($_REQUEST["id_inversionista"] != ""){
        	$strSQL .= ",ofp.valor_participacion";
        	$filtroInversionista = true;
       	}

		$strSQL .= "
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    INNER JOIN clientes as c3 ON c3.id_cliente = o.id_ejecutivo
                    INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    LEFT JOIN
                    (
                        SELECT
                            orpp.id_reliquidacion,
                            ore.id_tipo_reliquidacion,
                            orpp.fecha_real_pago AS fecha_real_pago_abono,
                            orpp.nuevo_valor_obligacion AS nuevo_valor_obligacion,
                            sum(orpp.abono) AS valor_pago,
                            sum(orpp.valor_obligacion_pp) AS valor_obligacion_abono,
                            sum(orpp.intereses_mora) AS interes_mora
                        FROM
                        	operacion_reliquidacion as ore
                            INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
						WHERE ore.estado = 1
                        GROUP BY
                            orpp.id_reliquidacion,
                            orpp.nuevo_valor_obligacion,
                            orpp.fecha_real_pago
                    )
                    as orepp ON of.id_reliquidacion = orepp.id_reliquidacion
                    ";

		//SI HAY INVERSIONISTA SACAMOS LOS DATOS DE PARTICIPACION
		if ($_REQUEST["id_inversionista"] != ""){
        	$strSQL .= " LEFT JOIN operacion_factura_participacion as ofp ON of.id_operacion_factura = ofp.id_operacion_factura AND ofp.id_inversionista=" . $_REQUEST["id_inversionista"];
       	}

		$strSQL .= " WHERE (o.estado=1 or o.estado=2) AND (of.estado = 1 OR (orepp.id_tipo_reliquidacion IN (4,6,8)))";

        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND of.fecha_pago >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND of.fecha_pago <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND ofp.valor_participacion > 0";

        $strSQL .= " ORDER BY o.id_operacion, of.fecha_pago ASC";

        $rsDatos = $db->Execute($strSQL);

		$strTemplate = "generar_reporte_facturas_vigentes.php";
		if ($tipoReporte == "EXP")
			$strTemplate = "generar_reporte_facturas_vigentes_exportar.php";

        include("./modules/reportes/templates/" . $strTemplate);
    }

    /**
      * Funciòn para ver el formulario reporte facturas vigentes
      */
    function reporteFacturasVigentes() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        //OBTENEMOS LOS EMISORES
        $arrEmisores = $clientes->obtenerClientesPorTipoTercero(1);

        //OBTENEMOS LOS PAGADORES
        $arrPagadores = $clientes->obtenerClientesPorTipoTercero(6);

        //OBTENEMOS LOS INVERSIONSITAS
        $arrInversionistas = $clientes->obtenerClientesPorTipoTercero(3);

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();

        include("./modules/reportes/templates/reporte_facturas_vigentes.php");
    }

    /**
      * Funciòn para generar el reporte de inversiones
      */
    function generarReporteInversiones() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/operaciones/class_operaciones.php");

        //INSTANCIAMOS CLASES NECESARIAS
        $operaciones = new operaciones();
        $operacion = new operacion();
        $factura = new operacion_factura();

        $fechaIni = $_REQUEST["fecha_inicio"];
        $fechaFin = $_REQUEST["fecha_fin"];

        $strSQL = "SELECT
                    o.id_operacion, o.num_factura, o.fecha_operacion, o.tasa_inversionista, o.estado, o.valor_giro_final,
                    c1.razon_social as emisor, c2.razon_social as pagador,
                    orel.fecha_real_pago,
                    orel.valor_pago,
                    orel.devolucion_remanentes,
                    orel.gmf,
                    orel.valor_futuro,
                    COALESCE(orelAcum.total_valor_pago, 0) AS total_valor_pago,
                    COALESCE(orelAcum.total_pagos_abonos,0) AS total_pagos_abonos,
                    COALESCE(orelAcum.nuevo_valor_obligacion,0) AS nuevo_valor_obligacion
                    FROM operacion as o
                    INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
                    INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
                    LEFT JOIN
                    (
                        SELECT
                            id_operacion,
                            fecha_real_pago,
                            SUM(valor_pago) as valor_pago,
                            SUM(devolucion_remanentes) as devolucion_remanentes,
                            SUM(gmf) as gmf,
                            SUM(otros) as otros,
                            SUM(intereses_mora) as intereses_mora,
                            SUM(valor_obligacion) as valor_futuro
                        FROM
                            v_totales_reliquidaciones
                        GROUP BY id_operacion,fecha_real_pago
                    ) AS orel ON orel.id_operacion = o.id_operacion
                    LEFT JOIN
                    (
                        SELECT
                            id_operacion,
                            SUM(valor_pago) as total_valor_pago,
                            SUM(nuevo_valor_obligacion) as nuevo_valor_obligacion,
                            COUNT(*) as total_pagos_abonos
                        FROM
                            v_totales_reliquidaciones
                        GROUP BY id_operacion
                    ) AS orelAcum ON orelAcum.id_operacion = o.id_operacion
                    WHERE 1=1
                    ";


        if ($_REQUEST["fecha_inicio"] != "")
            $strSQL .= " AND o.fecha_operacion >= '".$_REQUEST["fecha_inicio"]."'";

        if ($_REQUEST["fecha_fin"] != "")
            $strSQL .= " AND o.fecha_operacion <= '".$_REQUEST["fecha_fin"]."'";

        if ($_REQUEST["id_inversionista"] != "")
            $strSQL .= " AND o.id_inversionista = ".$_REQUEST["id_inversionista"];

        if ($_REQUEST["id_emisor"] != "")
            $strSQL .= " AND o.id_emisor = ".$_REQUEST["id_emisor"];

        if ($_REQUEST["id_pagador"] != "")
            $strSQL .= " AND o.id_pagador = ".$_REQUEST["id_pagador"];

        if ($_REQUEST["estado"] != "")
            $strSQL .= " AND o.estado = ".$_REQUEST["estado"];

        $strSQL .= " ORDER BY o.id_operacion, orel.fecha_real_pago";

        $rsDatos = $db->Execute($strSQL);

        include("./modules/reportes/templates/generar_reporte_inversiones.php");
    }

    /**
      * Funciòn para ver el formulario reporte inversiones
      */
    function reporteInversiones() {

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

        include("./modules/reportes/templates/reporte_inversiones.php");
    }

    /**
      * Funciòn para generar el reporte de facturas vencidas
      */
    function exportarExcel() {

        global $db,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $dataReporte = $_REQUEST["__dataReporte"];
        $tituloReporte = $_REQUEST["__tituloReporte"];

        include("./modules/reportes/templates/exportar_excel.php");
    }

    function guardarReportePDF(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./utilities/pdf/tcpdf.php");

        $dataMail = $_REQUEST["__dataMail"];
        $nameReport = $_REQUEST["__nameReport"];

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
        $pdf->Output(__DIR__ . '../../../gallery/reportes/' . $nameReport . ".pdf" , 'F');

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    function enviarReporte(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/class_send_mail.php");
        require_once("./modules/operaciones/class_operaciones.php");
        require_once("./modules/clientes/class_clientes.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();
        $operaciones = new operaciones();
        $operacion = new operacion();
        $emisor = new clientes();
        $pagador = new clientes();

        $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
        $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
        $subjectMail = $_REQUEST["__subjectMail"];
        $toNameMail = $_REQUEST["__toNameMail"];
        $toEmail = $_REQUEST["__toEmailMail"];
        $files = $_REQUEST["__files"];
        $template = $_REQUEST["__template"];
        $idOperacion = $_REQUEST["__option1"];

        //OBTENEMOS OPERACION
        $loadReg1 = $operacion->load("id_operacion=".$idOperacion);

        //OBTENEMOS EL EMISOR
        $loadReg2 = $emisor->load("id_cliente=".$operacion->id_emisor);

        //OBTENEMOS EL PAGADOR
        $loadReg3 = $pagador->load("id_cliente=".$operacion->id_pagador);

        //ENVIAMOS EL CORREO
        $arrAttach = array($files=>"Reporte PDF");
        $arrVarsReplace = array("NAME"=>$toNameMail,"FECHA_OPERACION"=>$operacion->fecha_operacion,"EMISOR"=>$emisor->razon_social,"PAGADOR"=>$pagador->razon_social);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,$template,$arrAttach,$arrVarsReplace);

        $jsondata['Message'] = "test";
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }

    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$idDeclaratoria,$action,$option,$option2,$appObj;

        switch($appObj->action){

        }
    }



}
?>