<?php
/**
* Adminsitración de tabla de modelo_riesgo
* @version 1.0
* El constructor de esta clase es {@link modelo_riesgo()}
*/
class operacion extends ADOdb_Active_Record{
    
    function verificarIntegridadReferencial($idOperacion = 0){
    
        global $db;                 
         
        return false;   
    }  
    
    function obtenerOperaciones(){
    
        global $db;

        $strSQL = "SELECT o.*, c1.razon_social as emisor, c2.razon_social as pagador, c3.razon_social as inversionista FROM operacion as o INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador INNER JOIN clientes as c3 ON c3.id_cliente = o.id_inversionista ORDER BY o.id_operacion DESC"; 
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }        
    
    function obtenerOperacionesPrEstado($idEstado = 0){
    
        global $db;

        $strSQL = "SELECT * FROM operacion WHERE estado=" . $idEstado;
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;        
    
    }
    
    function actualizarTotalesOperacion($idOperacion = 0){
    
        global $db;
        
        $loadReg = $this->load("id_operacion=".$idOperacion);
        
        //ACTUALIZAMOS TOTALES DE LA OPERACION
        $strSQL = "UPDATE operacion o
                    LEFT JOIN (SELECT id_operacion, 
                                      SUM(valor_neto) as valorNeto,
                                      SUM(valor_futuro) as valorFuturo,
                                      SUM(descuento_total) as descuentoTotal,
                                      SUM(margen_inversionista) as margenInversionista,
                                      SUM(margen_argenta) as margenArgenta,
                                      SUM(iva_fra_asesoria) as ivaFraAsesoria,
                                      SUM(fra_argenta) as fraArgenta,
                                      SUM(giro_antes_gmf) as giroAntesGMF, 
                                      SUM(gmf) as gmfTotal,
                                      SUM(valor_giro_final) as valorGiroFinal
                               FROM operacion_factura 
                               WHERE id_operacion=".$idOperacion." 
                               GROUP BY id_operacion) as of ON o.id_operacion = of.id_operacion        
                    SET 
                    valor_neto = of.valorNeto,
                    valor_futuro = of.valorFuturo,
                    descuento_total = of.descuentoTotal,
                    margen_inversionista = of.margenInversionista,
                    margen_argenta = of.margenArgenta,
                    iva_fra_asesoria = of.ivaFraAsesoria,
                    fra_argenta = of.fraArgenta,
                    giro_antes_gmf = of.giroAntesGMF,
                    descuento_total_reli = of.descuentoTotal,
                    margen_argenta_reli = of.margenArgenta,
					iva_fra_asesoria_reli = of.ivaFraAsesoria,
                    fra_argenta_reli = of.fraArgenta
                   WHERE o.id_operacion = " . $idOperacion;                                 
                   
        $db->Execute($strSQL);   
        
        $factorGMF = 0.3984;
        if ($this->fecha_operacion >= '2020-07-01' && $this->aplica_impuesto==2){
            $factorGMF = 0;
        }
        
        //ACTUALIZAMO VALOR GMF
        $strSQL = "UPDATE operacion                        
                    SET 
                    giro_antes_gmf = giro_antes_gmf,
                    gmf = giro_antes_gmf * (".$factorGMF." / 100),
                    valor_giro_final = giro_antes_gmf - gmf
                   WHERE id_operacion = " . $idOperacion;                
                   
        $db->Execute($strSQL);                    
    }
    
    function actualizarTotalesOperacionDesdeReliquidacion($idOperacion = 0){
    
        global $db;
        
        //ACTUALIZAMOS TOTALES DE LA OPERACION
        $strSQL = "UPDATE operacion o
                    LEFT JOIN (SELECT id_operacion, 
                                      SUM(descuento_total_reli) as descuentoTotal,
                                      SUM(margen_argenta_reli) as margenArgenta,
                                      SUM(iva_fra_asesoria_reli) as ivaFraAsesoria,
                                      SUM(fra_argenta_reli) as fraArgenta
                               FROM operacion_factura 
                               WHERE id_operacion=".$idOperacion." 
                               GROUP BY id_operacion) as of ON o.id_operacion = of.id_operacion        
                    SET 
                    descuento_total_reli = of.descuentoTotal,
                    margen_argenta_reli = of.margenArgenta,
					iva_fra_asesoria_reli = of.ivaFraAsesoria,
                    fra_argenta_reli = of.fraArgenta
                   WHERE o.id_operacion = " . $idOperacion;                                 
                   
        $db->Execute($strSQL);   
                         
    }    
    
    function tieneFacturasReliquidadas($idOperacion = 0){
    
        global $db;
        
        
        //DETERMINAMOS SI HAY FACTURAS FACTURAS SIN RELIQUIDACION
        $strSQL = "SELECT count(*) as total FROM operacion_factura WHERE id_operacion=" . $idOperacion . " AND estado = 1";
        $rsDatos = $db->Execute($strSQL);
        
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return false;
        }        
       
        return true;
    }   
    
    function tieneDesembolsos($idOperacion = 0){
    
        global $db;
        
        
        //DETERMINAMOS SI HAY DESEMBOLSOS
        $strSQL = "SELECT count(*) as total FROM operacion_desembolsos WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);
        
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total<=0)
                return false;
        }        
       
        return true;
    }  
    
    function totalDesembolsosRegistrados($idOperacion = 0){
    
        global $db;
        
        
        //DETERMINAMOS SI HAY DESEMBOLSOS
        $strSQL = "SELECT COALESCE(sum(valor),0) as total FROM operacion_desembolsos WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);
        $total = 0;
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
        }        
       
        return $total;
    }       
    
    function tieneDesembolsosSoporte($idOperacion = 0){
    
        global $db;
        
        
        //DETERMINAMOS SI HAY DESEMBOLSOS
        $strSQL = "SELECT count(*) as total FROM operacion_desembolsos WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);
        
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total<=0)
                return false;
            else{
				$strSQL = "SELECT count(*) as totalConSoporte FROM operacion_desembolsos WHERE (archivo_desembolso != '' OR archivo_desembolso != null) AND id_operacion=" . $idOperacion;
				$rsDatos1 = $db->Execute($strSQL); 
				if (!$rsDatos1->EOF){
					$totalConSoporte = $rsDatos1->fields["totalConSoporte"];
					if ($total > $totalConSoporte)
						return false;
				}
            }
        }        
       
        return true;
    }     
    
    function tieneReliquidacionesFinalizadas($idOperacion = 0){
    
        global $db;        
        
        //DETERMINAMOS SI HAY DESEMBOLSOS
        $strSQL = "SELECT count(*) as total FROM operacion_reliquidacion WHERE id_operacion=" . $idOperacion. " AND estado = 1";
        $rsDatos = $db->Execute($strSQL);
        
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return false;
        }        
       
        return true;
    }     
    
    function actualizarPorcentajesParticipacionInversionista($idOperacion = 0){
    
    	global $db; 
    	
    	//BORRAMOS LA PARTICIPACION DE LAS FACTURAS
    	$strSQL = "DELETE FROM operacion_factura_participacion WHERE id_operacion=".$idOperacion;
    	$db->Execute($strSQL);
    	
		$strSQL = "SELECT * FROM operacion_inversionista WHERE id_operacion=".$idOperacion;
        $rsDatos = $db->Execute($strSQL);
		
        while (!$rsDatos->EOF){
			
			$porcentaje = $rsDatos->fields["porcentaje_participacion"];
			$idOperacionInversionista = $rsDatos->fields["id_operacion_inversionista"];
			$idInversionista = $rsDatos->fields["id_inversionista"];

			$strSQL = "INSERT INTO operacion_factura_participacion(id_operacion, id_operacion_factura, id_operacion_inversionista, id_inversionista,valor_participacion)
					   SELECT 
							of.id_operacion,
							of.id_operacion_factura,
							".$idOperacionInversionista.",
							".$idInversionista.",
                            (of.giro_antes_gmf * ".$porcentaje.") / 100
					   FROM operacion_factura as of
					   WHERE id_operacion=" . $idOperacion;

			$db->Execute($strSQL);	

            $rsDatos->MoveNext();
        }
        
    	return true;
    }
        
}

class operacion_factura extends ADOdb_Active_Record{

    function getFacturasPorOperacion($idOperacion = 0){
   
        global $db;
        
        $strSQL = "SELECT of.*, DATEDIFF(of.fecha_pago,o.fecha) as dias, o.num_factura as factura_operacion, o.fecha as fecha_operacion  
         		   FROM operacion_factura as of 
         		   INNER JOIN operacion as o ON of.id_operacion = o.id_operacion  
         		   WHERE of.id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }
    
	function tieneFacturasSoporte($idOperacion = 0){
    
        global $db;
        
        
        //DETERMINAMOS SI HAY FACTURAS
        $strSQL = "SELECT count(*) as total FROM operacion_factura WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);
        
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total<=0)
                return false;
            else{
				$strSQL = "SELECT count(*) as totalConSoporte FROM operacion_factura WHERE (archivo != '' OR archivo != null) AND id_operacion=" . $idOperacion;
				$rsDatos1 = $db->Execute($strSQL); 
				if (!$rsDatos1->EOF){
					$totalConSoporte = $rsDatos1->fields["totalConSoporte"];
					if ($total > $totalConSoporte)
						return false;
				}
            }
        }        
       
        return true;
    }    
    
    function getFacturasPorOperacionSinReliquidar($idOperacion = 0, $idReliquidacion = 0){
    
        global $db;
        
        $strSQL = "SELECT of.*, o.num_factura as factura_operacion, o.fecha as fecha_operacion  FROM operacion_factura as of INNER JOIN operacion as o ON of.id_operacion = o.id_operacion  WHERE (of.id_reliquidacion = 0 OR of.id_reliquidacion IS NULL) AND of.id_operacion=" . $idOperacion;
        
        if ($idReliquidacion != 0)
            $strSQL .= " OR of.id_reliquidacion=".$idReliquidacion;
        
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }    
    
    function getDatosAcumuladosFacturasPorOperacion($idOperacion = 0){
    
        global $db;
        
        $strSQL = "SELECT * FROM operacion_factura WHERE id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    } 
    
    function getArrFacturasPorOperacion($idOperacion = 0, $fechaOperacion = ""){
    
        global $db;

        $arrFacturas = array();

        $strSQL = "SELECT *, DATEDIFF(fecha_pago,'" .$fechaOperacion. "') as dias 
                    FROM operacion_factura WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrFacturas["fac"][$rsDatos->fields["id_operacion_factura"]] = $rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"];
            $arrFacturas["dias"][$rsDatos->fields["id_operacion_factura"]] = $rsDatos->fields["dias"];
            $rsDatos->MoveNext();
        }
        return $arrFacturas;   
        
    } 
    
    function getArrFacturasPorReliquidacion($idReliquidacion = 0){
    
        global $db;

        $arrFacturas = array();

        $strSQL = "SELECT * FROM operacion_factura WHERE id_reliquidacion=" . $idReliquidacion;      
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrFacturas[$rsDatos->fields["id_operacion_factura"]] = $rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"];
            $rsDatos->MoveNext();
        }
        return $arrFacturas; 
    }  
    
    function getArrFacturasReliquidadasPorOperacion($idOperacion = 0){
    
        global $db;

        $arrFacturas = array();

        $strSQL = "SELECT * FROM operacion_factura WHERE id_reliquidacion IN (SELECT id_reliquidacion FROM operacion_reliquidacion WHERE id_operacion=".$idOperacion.")";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrFacturas[$rsDatos->fields["id_operacion_factura"]] = $rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"];
            $rsDatos->MoveNext();
        }
        return $arrFacturas; 
    }   
    
    function getFacturasSinReliquidarPorEmisor($idEmisor = 0, $arrFacturaAbonada = array()){
    
        global $db;
        
        $arrFacturas = array();
        
        $idsFacturaAbonada = 0;
        if (Count($arrFacturaAbonada) > 0)
            $idsFacturaAbonada = implode(",", $arrFacturaAbonada);
        
        $strSQL = "SELECT of.*  FROM operacion_factura as of INNER JOIN operacion as o ON of.id_operacion = o.id_operacion  WHERE (of.id_reliquidacion = 0 OR of.id_reliquidacion IS NULL) AND o.id_emisor=" . $idEmisor . " or of.id_operacion_factura IN (" . $idsFacturaAbonada . ")";       
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrFacturas[$rsDatos->fields["id_operacion_factura"]] = "Factura:" . $rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"] . " - Vr. Neto: ". formato_moneda($rsDatos->fields["valor_neto"]) . " - Vr. Futuro: ". formato_moneda($rsDatos->fields["valor_futuro"]) . " - Vr. Giro final: ". formato_moneda($rsDatos->fields["valor_giro_final"]);
            $rsDatos->MoveNext();
        }
                
        return $arrFacturas;
        
    }    
    
	function getArrFacturasPorOperacionReliquidacion($idOperacion = 0, $fechaOperacion = ""){
    
        global $db;

        $arrFacturasReliquidacion = array();

        $strSQL = "SELECT * FROM operacion_reliquidacion WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrFacturasReliquidacion["fac"][$rsDatos->fields["id_factura_abonada"]] = $rsDatos->fields["num_factura"];
            $rsDatos->MoveNext();
        }
        return $arrFacturasReliquidacion;   
        
    }      
    
    function getFacturasPorReliquidacion($idReliquidacion = 0){
    
        global $db;
        
        $strSQL = "SELECT of.*, DATEDIFF(of.fecha_pago,o.fecha) as dias, o.num_factura as factura_operacion, o.fecha as fecha_operacion  
                    FROM operacion_factura as of 
                    INNER JOIN operacion as o ON of.id_operacion = o.id_operacion  WHERE of.id_reliquidacion=" . $idReliquidacion;
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }    
    
    function getFacturasPorReliquidacionPP($idReliquidacion = 0){
    
        global $db;
        
        //SOLO SE TOMA EL PRIMER REGISSTRO PARTIENDO DE LA PREMISA QUE UNA RELIQUIDACION PARCIAL SOLO VA A TENER SIEMPRE
        //UNA SOLA FACTURA.
        $strSQL = "SELECT of.*,
                  rpp.abono as abono,
                  rpp.fecha_pago_pactada as fecha_pago_pactada,
                  rpp.fecha_real_pago as fecha_real_pago,
                  rpp.intereses_mora as intereses_mora,
                  rpp.intereses_devolver as intereses_devolver,
                  rpp.otros as otros,
                  rpp.gmf as gmf,
                  rpp.monto_devolver  as monto_devolver,
                  rpp.devolucion_remanentes as devolucion_remanentes
                  FROM operacion_factura as of
                  LEFT JOIN operacion_reliquidacion_pp as rpp on of.id_reliquidacion = rpp.id_reliquidacion
                  WHERE of.id_reliquidacion in (".$idReliquidacion.")
                  LIMIT 1 
                  ";                  
                      
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }          
    
    function actualizarFacturaDesdeReliquidacion($idFactura = 0, $operacion = null, $fechaRealPago = ""){
    
        global $db;
        
        $valorNeto=0;
		$strSQL = "SELECT of.valor_neto
        		   FROM operacion_factura as of
                   WHERE of.id_operacion_factura=" . $idFactura;
        $rsData = $db->Execute($strSQL);
                
        if (!$rsData->EOF){
            $valorNeto = $rsData->fields["valor_neto"];
        }        
        
        $porcentajeDescuento = $operacion->porcentaje_descuento;
        $tasaInversionista = $operacion->tasa_inversionista;
        $factor = $operacion->factor;
        $otrosOperacion = $operacion->valor_otros_operacion;
        $fechaInicial = $operacion->fecha_operacion;

        //REALIZAMOS CALCULOS
        $arrDiasDiferencia = date_diff_custom($fechaInicial, $fechaRealPago);
        $diasDiferencia = $arrDiasDiferencia["d"];

        $valorFuturo = round(($valorNeto * $porcentajeDescuento) / 100);
        $descuentoTotal = round(((($diasDiferencia * $factor) / 100) / 30) * $valorFuturo);
        $potenciaMargen = pow(1 + ($tasaInversionista / 100),($diasDiferencia / 365));
        $margenInversionista = round($valorFuturo-($valorFuturo / $potenciaMargen));
        $margenArgenta = $descuentoTotal - $margenInversionista;
        $ivaFraAsesoria = round(($margenArgenta * 19) / 100) ;
        $fraArgenta = $margenArgenta + $ivaFraAsesoria;
        
        $strSQL = "UPDATE operacion_factura
        			SET 
        			fecha_pago_reli='".$fechaRealPago."',
        			descuento_total_reli=".$descuentoTotal.",
        			margen_argenta_reli=".$margenArgenta.",
        			margen_inversionista_reli=".$margenInversionista.",
        			iva_fra_asesoria_reli=".$ivaFraAsesoria.",
        			fra_argenta_reli=".$fraArgenta."
        			WHERE id_operacion_factura=".$idFactura;
        
		$db->Execute($strSQL);
		
        /*$this->fecha_pago_reli = $fechaRealPago;
        $this->descuento_total_reli = $descuentoTotal;
        $this->margen_argenta_reli = $margenArgenta;
        $this->margen_inversionista_reli = $margenInversionista;
        $this->iva_fra_asesoria_reli = $ivaFraAsesoria;
        $this->fra_argenta_reli = $fraArgenta; 
        $this->Save();         */
        
        return true;
    }

}

class operacion_desembolsos extends ADOdb_Active_Record{

    function getDesembolsosPorOperacion($idOperacion = 0){
    
        global $db;
        
        $strSQL = "SELECT od.*,  c.razon_social
        		   FROM operacion_desembolsos as od
        		   LEFT JOIN clientes as c ON od.id_tercero = c.id_cliente 
                   WHERE od.id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }
    
    function totalDesembolso($idOperacion = 0){
    
        global $db;
        
        $totalDesembolso = 0;
        
	    $strSQL = "SELECT sum(od.valor) as total
        		   FROM operacion_desembolsos as od
                   WHERE od.id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);
                
        if (!$rsData->EOF){
            $totalDesembolso = $rsData->fields["total"];
        }                	
                
        return $totalDesembolso;        
        
        
    }
    
}

class operacion_inversionista extends ADOdb_Active_Record{

    function getInversionistasPorOperacion($idOperacion = 0){
    
        global $db;
        
        $strSQL = "SELECT oi.*, c.razon_social FROM operacion_inversionista as oi INNER JOIN clientes as c ON oi.id_inversionista = c.id_cliente  WHERE oi.id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }
    
    function getTotalInversion($idOperacion = 0){
    
        global $db;
        
        $strSQL = "SELECT sum(valor_inversion) as totalInversion FROM operacion_inversionista WHERE id_operacion=" . $idOperacion;
        $rsDatos = $db->Execute($strSQL);
		
		$total = 0;
        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["totalInversion"];
        }    
        
        return $total;
        
    }    
    
    function getReporteInversionistasPorOperacion($idOperacion = 0){
    
        global $db;
        
        $strSQL = "SELECT of.num_factura, of.valor_neto, of.valor_giro_final, ofo.valor_participacion, c.razon_social
					FROM 
					operacion_factura_participacion as ofo
					INNER JOIN operacion_factura as of ON ofo.id_operacion_factura = of.id_operacion_factura
					INNER JOIN clientes as c ON ofo.id_inversionista = c.id_cliente
					WHERE ofo.id_operacion=" . $idOperacion."
					ORDER BY num_factura, valor_participacion DESC";
					
        $rsDatos = $db->Execute($strSQL);
        
        return $rsDatos;
    }
}

class operacion_seguimiento extends ADOdb_Active_Record{

    function getSeguimientoPorOperacion($idOperacion = 0){
    
        global $db;
        
        $strSQL = "SELECT os.*, u.nombres, u.apellidos FROM operacion_seguimiento as os INNER JOIN users as u ON os.id_usuario = u.id_usuario  WHERE os.id_operacion=" . $idOperacion;
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }
}

?>