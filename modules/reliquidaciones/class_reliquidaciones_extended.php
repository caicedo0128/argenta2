<?php
/**
* Adminsitración de tabla de modelo_riesgo
* @version 1.0
* El constructor de esta clase es {@link modelo_riesgo()}
*/
class operacion_reliquidacion extends ADOdb_Active_Record{

    function verificarIntegridadReferencial($idOperacion = 0){

        global $db;

        return false;
    }

    function obtenerReliquidacionesPorOperacion($idOperacion = 0){

        global $db;

        $strSQL = "SELECT ore.*  FROM operacion_reliquidacion as ore WHERE ore.id_operacion=".$idOperacion." ORDER BY ore.id_reliquidacion DESC";
        $rsDatos = $db->Execute($strSQL);

        return $rsDatos;
    }

    function obtenerArregloReliquidacionesPorOperacion($idOperacion = 0){

        global $db;

		$arrData = array();
        $strSQL = "SELECT ore.*  FROM operacion_reliquidacion as ore WHERE ore.id_operacion=".$idOperacion." ORDER BY ore.id_reliquidacion DESC";
        $rsData = $db->Execute($strSQL);

		while (!$rsData->EOF){

            $arrData[$rsData->fields["id_reliquidacion"]] = "Id: " . $rsData->fields["id_reliquidacion"] . " - Num. fac: " . $rsData->fields["num_factura"];
            $rsData->MoveNext();
        }

        return $arrData;
    }

    function obtenerReliquidacionesPorFactura($idFactura = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion WHERE id_factura=".$idFactura." ORDER BY id_factura";
        $rsDatos = $db->Execute($strSQL);

        return $rsDatos;
    }

    function obtenerTipoReliquidacion($idReliquidacion = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion WHERE id_reliquidacion=".$idReliquidacion;
        $rsData = $db->Execute($strSQL);

		$idTipoReliquidacion = 0;

        if (!$rsData->EOF)
            $idTipoReliquidacion = $rsData->fields["id_tipo_reliquidacion"];

        return $idTipoReliquidacion;
    }

    function actualizacionDatosFacturacion($idReliquidacion, $emisorAdicionales){

    	global $db;

		$factorIvaGestion = 19 / 100;
		$factorIvaGiroTerceros = 19 / 100;
		$factorRTFGestion = 0;
		$factorRTFGiroTerceros = 0;
		$factorRTFIntereses = 0;
		$factorRTFICA = 0;
		$factorRTFIVA = 0;

		if ($emisorAdicionales->tarifa_autoretenedor != null){
			$factorRTFGestion = $emisorAdicionales->tarifa_autoretenedor / 100;
			$factorRTFGiroTerceros = $emisorAdicionales->tarifa_autoretenedor / 100;
		}

		if ($emisorAdicionales->tarifa_autoretenedor != null)
			$factorRTFIntereses = $emisorAdicionales->tarifa_autoretenedor / 100;

		if ($emisorAdicionales->rtf_intereses != null)
			$factorRTFIntereses = $emisorAdicionales->rtf_intereses / 100;

		if ($emisorAdicionales->tarifa_ica != null)
			$factorRTFICA = $emisorAdicionales->tarifa_ica / 100;

		if ($emisorAdicionales->tarifa_iva != null)
			$factorRTFIVA = $emisorAdicionales->tarifa_iva / 100;
		
		//TODAS LAS RELIQUIDACIONES QUE NO SON ANTICIPADAS
		$strSQL = "UPDATE
					operacion_reliquidacion_pt as orpt
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total) as totalDescuentoTotalReli,
							sum(margen_inversionista) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpt.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpt.id_reliquidacion = orr.id_reliquidacion
					SET
					   orpt.iva_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.",
					   orpt.iva_giro_terceros = COALESCE(orpt.gmf,0) * ".$factorIvaGiroTerceros.",
					   orpt.rtf_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorRTFGestion.",
					   orpt.rtf_giro_terceros = COALESCE(orpt.gmf,0) * ".$factorRTFGiroTerceros.",
					   orpt.rtf_intereses = (sub1.totalMargenInversionistaReli + COALESCE(orpt.intereses_mora,0)) * ".$factorRTFIntereses.",
					   orpt.rtf_ica = (sub1.totalDescuentoTotalReli + COALESCE(orpt.intereses_mora,0) + COALESCE(orpt.gmf,0)) * ".$factorRTFICA."					   
					WHERE orr.id_tipo_reliquidacion <> 5 AND orpt.id_reliquidacion=".$idReliquidacion;

		$db->Execute($strSQL);

		$strSQL = "UPDATE
					operacion_reliquidacion_pt as orpt
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total) as totalDescuentoTotalReli,
							sum(margen_inversionista) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpt.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpt.id_reliquidacion = orr.id_reliquidacion
					SET
						orpt.rtf_iva = (((sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.") + orpt.iva_giro_terceros) * ".$factorRTFIVA.",
					   orpt.total_factura = ROUND((COALESCE(orpt.gmf,0) + COALESCE(orpt.intereses_mora,0) + (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) + sub1.totalMargenInversionistaReli) + orpt.iva_gestion + orpt.iva_giro_terceros,2)
					WHERE orr.id_tipo_reliquidacion <> 5 AND orpt.id_reliquidacion=".$idReliquidacion;
		$db->Execute($strSQL);

		//SOLO LA RELIQUIDACION PAGO TOTAL ANTICIPADO
		$strSQL = "UPDATE
					operacion_reliquidacion_pt as orpt
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total_reli) as totalDescuentoTotalReli,
							sum(margen_inversionista_reli) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpt.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpt.id_reliquidacion = orr.id_reliquidacion
					SET
					   orpt.iva_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.",
					   orpt.iva_giro_terceros = COALESCE(orpt.gmf,0) * ".$factorIvaGiroTerceros.",
					   orpt.rtf_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorRTFGestion.",
					   orpt.rtf_giro_terceros = COALESCE(orpt.gmf,0) * ".$factorRTFGiroTerceros.",
					   orpt.rtf_intereses = (sub1.totalMargenInversionistaReli + COALESCE(orpt.intereses_mora,0)) * ".$factorRTFIntereses.",
					   orpt.rtf_ica = (sub1.totalDescuentoTotalReli + COALESCE(orpt.intereses_mora,0) + COALESCE(orpt.gmf,0)) * ".$factorRTFICA."					   
					WHERE orr.id_tipo_reliquidacion = 5 AND orpt.id_reliquidacion=".$idReliquidacion;				   	

		$db->Execute($strSQL);

		$strSQL = "UPDATE
					operacion_reliquidacion_pt as orpt
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total_reli) as totalDescuentoTotalReli,
							sum(margen_inversionista_reli) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpt.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpt.id_reliquidacion = orr.id_reliquidacion
					SET					
						orpt.rtf_iva = (((sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.") + orpt.iva_giro_terceros) * ".$factorRTFIVA.",
					   orpt.total_factura = ROUND((COALESCE(orpt.gmf,0) + COALESCE(orpt.intereses_mora,0) + (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) + sub1.totalMargenInversionistaReli) + orpt.iva_gestion + orpt.iva_giro_terceros,2)
					WHERE orr.id_tipo_reliquidacion = 5 AND orpt.id_reliquidacion=".$idReliquidacion;				   	

		$db->Execute($strSQL);		

		//ACTUALIZAMOS OTROS TOTALES
		$strSQL = "UPDATE 
					operacion_reliquidacion_pt as orpt			
					SET
						orpt.neto_factura = ROUND((orpt.total_factura - orpt.rtf_gestion - orpt.rtf_giro_terceros - orpt.rtf_intereses - orpt.rtf_ica - orpt.rtf_iva),2),
						orpt.nuevo_remanente = COALESCE(orpt.devolucion_remanentes,0) - COALESCE(orpt.gmf,0) + orpt.rtf_gestion + orpt.rtf_giro_terceros + orpt.rtf_intereses + orpt.rtf_ica + orpt.rtf_iva - orpt.iva_giro_terceros 					    					    
					WHERE orpt.id_reliquidacion=".$idReliquidacion;				   						   
		$db->Execute($strSQL);

		
    	return true;
    }
    
	function actualizacionDatosFacturacionPP($idReliquidacion, $emisorAdicionales){

    	global $db;

		$factorIvaGestion = 19 / 100;
		$factorIvaGiroTerceros = 19 / 100;
		$factorRTFGestion = 0;
		$factorRTFGiroTerceros = 0;
		$factorRTFIntereses = 0;
		$factorRTFICA = 0;
		$factorRTFIVA = 0;

		if ($emisorAdicionales->tarifa_autoretenedor != null){
			$factorRTFGestion = $emisorAdicionales->tarifa_autoretenedor / 100;
			$factorRTFGiroTerceros = $emisorAdicionales->tarifa_autoretenedor / 100;
		}

		if ($emisorAdicionales->tarifa_autoretenedor != null)
			$factorRTFIntereses = $emisorAdicionales->tarifa_autoretenedor / 100;

		if ($emisorAdicionales->rtf_intereses != null)
			$factorRTFIntereses = $emisorAdicionales->rtf_intereses / 100;

		if ($emisorAdicionales->tarifa_ica != null)
			$factorRTFICA = $emisorAdicionales->tarifa_ica / 100;

		if ($emisorAdicionales->tarifa_iva != null)
			$factorRTFIVA = $emisorAdicionales->tarifa_iva / 100;
		
		//TODAS LAS RELIQUIDACIONES QUE NO SON ANTICIPADAS
		$strSQL = "UPDATE
					operacion_reliquidacion_pp as orpp
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total) as totalDescuentoTotalReli,
							sum(margen_inversionista) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpp.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpp.id_reliquidacion = orr.id_reliquidacion
					SET
					   orpp.iva_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.",
					   orpp.iva_giro_terceros = COALESCE(orpp.gmf,0) * ".$factorIvaGiroTerceros.",
					   orpp.rtf_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorRTFGestion.",
					   orpp.rtf_giro_terceros = COALESCE(orpp.gmf,0) * ".$factorRTFGiroTerceros.",
					   orpp.rtf_intereses = (sub1.totalMargenInversionistaReli + COALESCE(orpp.intereses_mora,0)) * ".$factorRTFIntereses.",
					   orpp.rtf_ica = (sub1.totalDescuentoTotalReli + COALESCE(orpp.intereses_mora,0) + COALESCE(orpp.gmf,0)) * ".$factorRTFICA."					   
					WHERE orr.id_tipo_reliquidacion <> 6 AND orpp.id_reliquidacion=".$idReliquidacion;
		$db->Execute($strSQL);

		$strSQL = "UPDATE
					operacion_reliquidacion_pp as orpp
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total) as totalDescuentoTotalReli,
							sum(margen_inversionista) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpp.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpp.id_reliquidacion = orr.id_reliquidacion
					SET
						orpp.rtf_iva = (((sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.") + orpp.iva_giro_terceros) * ".$factorRTFIVA.",
						orpp.total_factura = ROUND((COALESCE(orpp.gmf,0) + COALESCE(orpp.intereses_mora,0) + (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) + sub1.totalMargenInversionistaReli) + orpp.iva_gestion + orpp.iva_giro_terceros,2)
					WHERE orr.id_tipo_reliquidacion <> 6 AND orpp.id_reliquidacion=".$idReliquidacion;
		$db->Execute($strSQL);

		//SOLO LA RELIQUIDACION PAGO TOTAL ANTICIPADO
		$strSQL = "UPDATE
					operacion_reliquidacion_pp as orpp
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total_reli) as totalDescuentoTotalReli,
							sum(margen_inversionista_reli) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpp.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpp.id_reliquidacion = orr.id_reliquidacion
					SET
					   orpp.iva_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.",
					   orpp.iva_giro_terceros = COALESCE(orpp.gmf,0) * ".$factorIvaGiroTerceros.",
					   orpp.rtf_gestion = (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorRTFGestion.",
					   orpp.rtf_giro_terceros = COALESCE(orpp.gmf,0) * ".$factorRTFGiroTerceros.",
					   orpp.rtf_intereses = (sub1.totalMargenInversionistaReli + COALESCE(orpp.intereses_mora,0)) * ".$factorRTFIntereses.",
					   orpp.rtf_ica = (sub1.totalDescuentoTotalReli + COALESCE(orpp.intereses_mora,0) + COALESCE(orpp.gmf,0)) * ".$factorRTFICA."					   
					WHERE orr.id_tipo_reliquidacion = 6 AND orpp.id_reliquidacion=".$idReliquidacion;				   	

		$db->Execute($strSQL);

		$strSQL = "UPDATE
					operacion_reliquidacion_pp as orpp
					INNER JOIN (
						SELECT
							id_reliquidacion,
							sum(descuento_total_reli) as totalDescuentoTotalReli,
							sum(margen_inversionista_reli) as totalMargenInversionistaReli
						FROM operacion_factura
						WHERE id_reliquidacion=".$idReliquidacion."
						GROUP BY id_reliquidacion
					) as sub1 ON orpp.id_reliquidacion=sub1.id_reliquidacion
					INNER JOIN operacion_reliquidacion as orr ON orpp.id_reliquidacion = orr.id_reliquidacion
					SET		
						orpp.rtf_iva = (((sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) * ".$factorIvaGestion.") + orpp.iva_giro_terceros) * ".$factorRTFIVA.",
					   orpp.total_factura = ROUND((COALESCE(orpp.gmf,0) + COALESCE(orpp.intereses_mora,0) + (sub1.totalDescuentoTotalReli - sub1.totalMargenInversionistaReli) + sub1.totalMargenInversionistaReli) + orpp.iva_gestion + orpp.iva_giro_terceros,2)
					WHERE orr.id_tipo_reliquidacion = 6 AND orpp.id_reliquidacion=".$idReliquidacion;				   	

		$db->Execute($strSQL);		

		//ACTUALIZAMOS OTROS TOTALES
		$strSQL = "UPDATE 
					operacion_reliquidacion_pp as orpp			
					SET
						orpp.neto_factura = ROUND((orpp.total_factura - orpp.rtf_gestion - orpp.rtf_giro_terceros - orpp.rtf_intereses - orpp.rtf_ica - orpp.rtf_iva),2),
						orpp.nuevo_remanente = COALESCE(orpp.devolucion_remanentes,0) - COALESCE(orpp.gmf,0) + orpp.rtf_gestion + orpp.rtf_giro_terceros + orpp.rtf_intereses + orpp.rtf_ica + orpp.rtf_iva - orpp.iva_giro_terceros 					    					    
					WHERE orpp.id_reliquidacion=".$idReliquidacion;				   						   
		$db->Execute($strSQL);

		
    	return true;
    }    
    
	function actualizacionDatosFacturacionPPAbono($idReliquidacion, $emisorAdicionales, $idReliquidacionPP){

    	global $db;

		$factorIvaGestion = 19 / 100;
		$factorIvaGiroTerceros = 19 / 100;
		$factorRTFGestion = 0;
		$factorRTFGiroTerceros = 0;
		$factorRTFIntereses = 0;
		$factorRTFICA = 0;
		$factorRTFIVA = 0;

		if ($emisorAdicionales->tarifa_autoretenedor != null){
			$factorRTFGestion = $emisorAdicionales->tarifa_autoretenedor / 100;
			$factorRTFGiroTerceros = $emisorAdicionales->tarifa_autoretenedor / 100;
		}

		if ($emisorAdicionales->tarifa_autoretenedor != null)
			$factorRTFIntereses = $emisorAdicionales->tarifa_autoretenedor / 100;

		if ($emisorAdicionales->rtf_intereses != null)
			$factorRTFIntereses = $emisorAdicionales->rtf_intereses / 100;

		if ($emisorAdicionales->tarifa_ica != null)
			$factorRTFICA = $emisorAdicionales->tarifa_ica / 100;

		if ($emisorAdicionales->tarifa_iva != null)
			$factorRTFIVA = $emisorAdicionales->tarifa_iva / 100;
		
		//OBTENEMOS EL VALOR PRESENTE = nuevo_valor_obligacion DEL ABONO ANTERIOR
		$strSQL = "SELECT nuevo_valor_obligacion
					FROM operacion_reliquidacion_pp
					WHERE id_reliquidacion=".$idReliquidacion. " AND id_reliquidacion_pp<".$idReliquidacionPP."
					ORDER BY id_reliquidacion_pp DESC
					LIMIT 1";
		
		$nuevoValorObligacion = 0;		
		$rsData = $db->Execute($strSQL);	
		if (!$rsData->EOF)
            $nuevoValorObligacion = $rsData->fields["nuevo_valor_obligacion"];
		
		
		//SOLO CALCULAMOS RTF DE INTERESES
		$strSQL = "UPDATE 
					operacion_reliquidacion_pp as orpp			
					SET
						orpp.rtf_intereses = (orpp.valor_obligacion_pp - ".$nuevoValorObligacion.") * ".$factorRTFIntereses.",
						orpp.iva_gestion = 0,
					   	orpp.iva_giro_terceros = 0,
					   	orpp.rtf_gestion = 0,
					   	orpp.rtf_giro_terceros = 0,
					   	orpp.rtf_iva = 0,
					   	orpp.total_factura = 0,
						orpp.neto_factura = 0,
						orpp.nuevo_remanente = 0
					WHERE orpp.id_reliquidacion=".$idReliquidacion. " AND orpp.id_reliquidacion_pp=".$idReliquidacionPP;				   						   
		$db->Execute($strSQL);
		
    	return true;
    }        

}

class operacion_reliquidacion_pt extends ADOdb_Active_Record{

    function getReliquidacionPorReliquidacion($idReliquidacion = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion_pt WHERE id_reliquidacion=" . $idReliquidacion;
        $rsData = $db->Execute($strSQL);

        return $rsData;
    }
}

class operacion_reliquidacion_pp extends ADOdb_Active_Record{

    function getReliquidacionPorReliquidacion($idReliquidacion = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion_pp WHERE id_reliquidacion=" . $idReliquidacion . " ORDER BY id_reliquidacion_pp";
        $rsData = $db->Execute($strSQL);

        return $rsData;
    }

    function obtenerUltimaLiquidacion($idReliquidacion = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion_pp WHERE id_reliquidacion=" . $idReliquidacion . " ORDER BY id_reliquidacion_pp DESC LIMIT 1";

        $rsData = $db->Execute($strSQL);

        $idReliquidacion = 0;

        if (!$rsData->EOF)
            $idReliquidacion = $rsData->fields["id_reliquidacion_pp"];

        return $idReliquidacion;
    }

    function obtenerLiquidacionAnterior($idReliquidacionPP = 0, $idReliquidacion = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion_pp WHERE id_reliquidacion_pp < " . $idReliquidacionPP . " AND id_reliquidacion = ".$idReliquidacion." ORDER BY id_reliquidacion_pp DESC LIMIT 1";
        $rsData = $db->Execute($strSQL);

        $idReliquidacion = 0;

        if (!$rsData->EOF)
            $idReliquidacion = $rsData->fields["id_reliquidacion_pp"];

        return $idReliquidacion;
    }
}

class operacion_reliquidacion_abonos extends ADOdb_Active_Record{

    function getFacturasAbonadasReliquidacion($idReliquidacion = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion_abonos WHERE id_reliquidacion=" . $idReliquidacion;
        $rsData = $db->Execute($strSQL);

        return $rsData;
    }

    function getArrFacturasAbonadasReliquidacion($idReliquidacion = 0){

        global $db;

        $arrData = array();
        $strSQL = "SELECT * FROM operacion_reliquidacion_abonos WHERE id_reliquidacion=" . $idReliquidacion;
        $rsData = $db->Execute($strSQL);

        while (!$rsData->EOF){

            $arrData[] = $rsData->fields["id_factura"];
            $rsData->MoveNext();
        }

        return $arrData;
    }

    function getFacturasAbonadasReliquidacionRelacion($idReliquidacionRel = 0){

        global $db;

        $strSQL = "SELECT * FROM operacion_reliquidacion_abonos WHERE id_reliquidacion_rel=" . $idReliquidacionRel;
        $rsData = $db->Execute($strSQL);

        return $rsData;
    }
}


?>