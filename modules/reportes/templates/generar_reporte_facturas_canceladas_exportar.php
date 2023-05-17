<?php
    header("Content-Type:application/vnd.ms-excel; charset=utf-8");
    header("Content-type:application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteFacturasCanceladas.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<table>
    <tr>
        <td colspan="6">
            <h1>Reporte facturas canceladas</h1>
        </td>
    </tr>
    <tr>
        <td colspan="2">Usuario: <?=$_SESSION["user"]?></td>
    </tr>
    <tr>
        <td colspan="2">Fecha: <?=date("Y-m-d")?></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
<?php

    echo "<table id='tableDataFacturas' border='1' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Nit Emisor</th>";    
    echo "<th>Emisor</th>";    
    echo "<th>Nit Pagador</th>";    
    echo "<th>Pagador</th>";
    echo "<th>No. operación</th>";
    echo "<th>Fecha operación</th>";
    echo "<th>No. Factura</th>";
    echo "<th>Fecha emisión</th>";
    echo "<th>Fecha vencimiento</th>";
    echo "<th>Fecha pago pactada</th>";
    echo "<th>Cod. reliquidación</th>";
    echo "<th>Primera fecha reliquidación</th>";
    echo "<th>Valor pagado</th>";
    echo "<th>Vr Neto</th>";  
    echo "<th>% Descuento</th>";  
    if ($filtroInversionista)
    	echo "<th>Valor participación</th>";    	
    echo "<th>Valor futuro</th>";
    echo "<th>Valor girado</th>";
    echo "<th>Tasa</th>";
    echo "<th>Factor</th>";
    echo "<th>Ejecutivo</th>";
    echo "<th>Comisión</th>";
    echo "<th>Descuento total</th>";
    echo "<th>Margen inversionista</th>";
    echo "<th>Margen Argenta</th>";
    echo "<th>Comisión ejecutivo</th>";
    echo "<th>Iva factura asesoria</th>";
    echo "<th>Factura Argenta</th>";
    echo "<th>Otros</th>";
    echo "<th>Giro antes GMF</th>";
    echo "<th>GMF</th>";
    echo "<th>Valor giro final</th>";
    echo "<th>Estado</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    //RECORREMOS ANTES EL RESULTADO DE DATOS PARA PODER AGRUPAR LAS FACTURAS CON ABONOS
    $arrDatosReporte = array();
    $i = 0;
    $idReliquidacionAux = 0;
    $idOperacionAux = 0;
    while (!$rsDatos->EOF){ 
        
        $idReliquidacion = $rsDatos->fields["id_reliquidacion"];
        

		$numFacturas = $rsDatos->fields["num_factura"];
		$totalValorGiroFinal = $rsDatos->fields["valor_giro_final"]; 
		$totalValorFuturo = $rsDatos->fields["valor_futuro"];  

        
        $arrDatosReporte[$i]["id_operacion"] = $rsDatos->fields["id_operacion"];
        $arrDatosReporte[$i]["id_reliquidacion"] = $idReliquidacion;                 
        $arrDatosReporte[$i]["emisor"] = $rsDatos->fields["emisor"];
        $arrDatosReporte[$i]["pagador"] = $rsDatos->fields["pagador"];
        $arrDatosReporte[$i]["fecha_operacion"] = $rsDatos->fields["fecha_operacion"];
        $arrDatosReporte[$i]["id_operacion"] = $rsDatos->fields["id_operacion"];
        $arrDatosReporte[$i]["tasa_inversionista"] = $rsDatos->fields["tasa_inversionista"];
        $arrDatosReporte[$i]["factor"] = $rsDatos->fields["factor"];
        $arrDatosReporte[$i]["fecha_pago"] = $rsDatos->fields["fecha_pago"];
        $arrDatosReporte[$i]["num_factura"] = $numFacturas;
        $arrDatosReporte[$i]["fecha_emision"] = $rsDatos->fields["fecha_emision"];
        $arrDatosReporte[$i]["fecha_vencimiento_factura"] = $rsDatos->fields["fecha_vencimiento_factura"];
        $arrDatosReporte[$i]["valor_participacion"] = $rsDatos->fields["valor_participacion"];
        $arrDatosReporte[$i]["valor_giro_final"] = $totalValorGiroFinal;
        $arrDatosReporte[$i]["valor_futuro"] = $totalValorFuturo;
        $arrDatosReporte[$i]["id_reliquidacion"] = $rsDatos->fields["id_reliquidacion"];
        $arrDatosReporte[$i]["fecha_real_pago"] = $rsDatos->fields["fecha_real_pago"];
        $arrDatosReporte[$i]["fecha_real_pago_abono"] = $rsDatos->fields["fecha_real_pago_abono"];
        $arrDatosReporte[$i]["valor_obligacion"] = $rsDatos->fields["valor_obligacion_abono"];
        $arrDatosReporte[$i]["interes_mora"] = $rsDatos->fields["interes_mora"];
        $arrDatosReporte[$i]["nuevo_valor_obligacion"] = ($rsDatos->fields["nuevo_valor_obligacion"] > 0?$rsDatos->fields["nuevo_valor_obligacion"]:$totalValorFuturo);
        $arrDatosReporte[$i]["valor_pago"] = $rsDatos->fields["valor_pago"];
        $arrDatosReporte[$i]["estado"] = $rsDatos->fields["estado"];
        $arrDatosReporte[$i]["emisor_identificacion"] = $rsDatos->fields["emisor_identificacion"];
        $arrDatosReporte[$i]["pagador_identificacion"] = $rsDatos->fields["pagador_identificacion"];
        $arrDatosReporte[$i]["porcentaje_descuento"] = $rsDatos->fields["porcentaje_descuento"];
        $arrDatosReporte[$i]["valor_neto"] = $rsDatos->fields["valor_neto"];
        $arrDatosReporte[$i]["ejecutivo"] = $rsDatos->fields["ejecutivo"];
        $arrDatosReporte[$i]["comision"] = $rsDatos->fields["comision"];
        $arrDatosReporte[$i]["descuento_total"] = $rsDatos->fields["descuento_total"];
        $arrDatosReporte[$i]["margen_inversionista"] = $rsDatos->fields["margen_inversionista"];
        $arrDatosReporte[$i]["margen_argenta"] = $rsDatos->fields["margen_argenta"];
        $arrDatosReporte[$i]["iva_fra_asesoria"] = $rsDatosñ8->fields["iva_fra_asesoria"];
        $arrDatosReporte[$i]["fra_argenta"] = $rsDatos->fields["fra_argenta"];
        $arrDatosReporte[$i]["valor_otros_operacion"] = $rsDatos->fields["valor_otros_operacion"];
        $arrDatosReporte[$i]["giro_antes_gmf"] = $rsDatos->fields["giro_antes_gmf"];
        $arrDatosReporte[$i]["gmf"] = $rsDatos->fields["gmf"];
        $arrDatosReporte[$i]["valor_giro_final"] = $rsDatos->fields["valor_giro_final"];
        
        $i++;
        
        $idOperacionAux = $rsDatos->fields["id_operacion"];
        $rsDatos->MoveNext();
        
        //SI LA SIGUIENTE RELIQUIDACION ES DIFERENTE SETEAMOS LOS VALORES
        if ($idReliquidacion != $rsDatos->fields["id_reliquidacion"]){
            $numFacturas = "";
            $totalValorGiroFinal = 0; 
            $totalValorFuturo = 0;          
        }
    }
        
    //IMPRIMIMOS EL REPORTE BASADO EN EL ARREGLO GENERADO ANTERIORMENTE
    $idReliquidacionAux = 0;
    for ($i=0; $i<Count($arrDatosReporte) ; $i++){
        
        $idReliquidacion = $arrDatosReporte[$i]["id_reliquidacion"];
        
		echo "<tr>";
		echo "<td>".$arrDatosReporte[$i]["emisor_identificacion"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["emisor"]."</td>";    
		echo "<td>".$arrDatosReporte[$i]["pagador_identificacion"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["pagador"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["id_operacion"]."</td>";    
		echo "<td>".$arrDatosReporte[$i]["fecha_operacion"]."</td>";    
		$diasVencidos = date_diff_custom($arrDatosReporte[$i]["fecha_pago"], date("Y-m-d"));
		echo "<td>".$arrDatosReporte[$i]["num_factura"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["fecha_emision"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["fecha_vencimiento_factura"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["fecha_pago"]."</td>";  
		echo "<td>".$idReliquidacion."</td>";  
		echo "<td>".$arrDatosReporte[$i]["fecha_real_pago"]."</td>";  
		echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_pago"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_neto"])."</td>";
		echo "<td>".str_replace(".",",",$arrDatosReporte[$i]["porcentaje_descuento"])."%</td>";
		if ($filtroInversionista)
			echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_participacion"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_futuro"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_giro_final"])."</td>";
		echo "<td>".str_replace(".",",",$arrDatosReporte[$i]["tasa_inversionista"])."%</td>";  
		echo "<td>".str_replace(".",",",$arrDatosReporte[$i]["factor"])."%</td>";  
		echo "<td>".$arrDatosReporte[$i]["ejecutivo"]."</td>";
		echo "<td>".$arrDatosReporte[$i]["comision"]."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["descuento_total"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["margen_inversionista"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["margen_argenta"])."</td>";
		$comisionEjecutivo = ($arrDatosReporte[$i]["margen_argenta"] * $arrDatosReporte[$i]["comision"]) / 100;    
		echo "<td>".formato_moneda($comisionEjecutivo)."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["iva_fra_asesoria"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["fra_argenta"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_otros_operacion"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["giro_antes_gmf"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["gmf"])."</td>";
		echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_giro_final"])."</td>";            
		echo "<td>CANCELADA</td>";
		echo "</tr>";                   
    }
    echo "</tbody>";
    echo "</table>";

?>