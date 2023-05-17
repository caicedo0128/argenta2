<?php
    if ($_REQUEST["es_excel"]!='true')
    {
        header("Content-Type:application/vnd.ms-excel; charset=utf-8");
        header("Content-type:application/x-msexcel; charset=utf-8");
        header("Content-Disposition: attachment; filename=reporteTrazabilidadFacturas.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
    }
?>
<style>
    table{
        font-size:10px;
    }
</style>
<table>
    <tr>
        <td><img id="logoArgenta" src="./images/logo.png"></td>
    </tr>
    <tr>
        <td colspan="2">Reporte reliquidaci&oacute;n operaci&oacute;n.</td>
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
<table width="100%" border="1">
    <tr>
        <td><b>Nro operaci&oacute;n:</b></td>
        <td><?=$operacion->id_operacion?></td>
        <td><b>Fecha operaci&oacute;n:</b></td>
        <td><?=$operacion->fecha_operacion?></td>
    </tr>
    <tr>
        <td><b>Emisor:</b></td>
        <td colspan="3"><?=$emisor->razon_social?></td>
    </tr>
    <tr>
        <td><b>Pagador:</b></td>
        <td colspan="3"><?=$pagador->razon_social?></td>
    </tr>
    <tr>
        <td><b>% Anticipo:</b></td>
        <td><?=$operacion->porcentaje_descuento?>%</td>
        <td><b>Factor:</b></td>
        <td><?=$operacion->factor?>%</td>
    </tr>     
</table>
<br/><br/>
<b><i>Detalle facturas pagadas</i></b>
<br/><br/>
<table width="100%" border="1">
    <tr style="font-size:9px;">
        <th>Fecha pago</th>
        <th>Nro factura</th>
        <th>Valor neto</th>
        <th>Valor futuro</th>
        <th>D&iacute;as mora</th>
        <th>Intereses a devolver</th>
        <th>Inter&eacute;s corriente</th>
        <th>Gesti&oacute;n de referenciaci&oacute;n</th>
        <th>Iva factura</th>
    </tr>
    <?php
        $totalRegistros = $rsListadoFacturas->_numOfRows;
        $i=1;
        while(!$rsListadoFacturas->EOF){

			$fechaRealPago = $rsListadoFacturas->fields["fechaRealPago"];
			$arrDiferencia = date_diff_custom($rsListadoFacturas->fields["fecha_pago"],$fechaRealPago);
            $interesesDevolver = $rsListadoFacturas->fields["interesesDevolver"];
            
            //DETERMINAMOS EL TIPO DE RELIQUIDACION
            //OBTENEMOS EL TIPO DE RELIQUIDACION
            $idTipoReliquidacion = $operacionReliquidacion->obtenerTipoReliquidacion($rsListadoFacturas->fields["id_reliquidacion"]);
            if ($idTipoReliquidacion==6){
                //ES PAGO TOTAL ANTICIPADO
                $interesCorriente = $rsListadoFacturas->fields["margen_inversionista_reli"];
                $gestionReferenciacion = ($rsListadoFacturas->fields["descuento_total_reli"] - $rsListadoFacturas->fields["margen_inversionista_reli"]);
                $ivaFactura = $rsListadoFacturas->fields["iva_fra_asesoria_reli"];
                $interesesDevolver = $rsListadoFacturas->fields["descuento_total"] - $rsListadoFacturas->fields["descuento_total_reli"];
            }
            else{
                $interesCorriente = $rsListadoFacturas->fields["margen_inversionista"];
                $gestionReferenciacion = ($rsListadoFacturas->fields["descuento_total"] - $rsListadoFacturas->fields["margen_inversionista"]);
                $ivaFactura = $rsListadoFacturas->fields["iva_fra_asesoria"];           
            }            

            echo "<tr style=\"font-size:9px;\">";
            echo "<td align='center'>".$rsListadoFacturas->fields["fecha_pago"]."</td>";
            echo "<td align='center'>".$rsListadoFacturas->fields["num_factura"]."</td>";            
            echo "<td align=\"right\">".formato_moneda($rsListadoFacturas->fields["valor_neto"],"")."</td>";
            echo "<td align=\"right\">".formato_moneda($rsListadoFacturas->fields["valor_futuro"],"")."</td>";
            echo "<td align=\"right\">".$arrDiferencia["d"]."</td>";
            echo "<td align=\"right\">".formato_moneda($interesesDevolver,"")."</td>";
            echo "<td align=\"right\">".formato_moneda($interesCorriente,"")."</td>";
            echo "<td align=\"right\">".formato_moneda($gestionReferenciacion,"")."</td>";
            echo "<td align=\"right\">".formato_moneda($ivaFactura,"")."</td>";
            echo "</tr>";
            
            $totalValorNeto += $rsListadoFacturas->fields["valor_neto"];
            $totalValorFuturo += $rsListadoFacturas->fields["valor_futuro"];
            $totalInteresDevolver += $interesesDevolver;
            $totalInteresCorriente += $interesCorriente;
            $totalGestion += $gestionReferenciacion;
            $totalIva += $ivaFactura; 
            
            //SE TOMA EL ULTIMO REMANENTE
            $totalNuevoRemanente = $rsListadoFacturas->fields["nuevoRemanente"];
            
            $i++;
            $rsListadoFacturas->MoveNext();
        }
        
        echo "<tr style=\"font-size:9px;\">";
        echo "<td colspan=\"2\">TOTALES:</td>";
        echo "<td align=\"right\">".formato_moneda($totalValorNeto,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalValorFuturo,"")."</td>";
        echo "<td align=\"right\">&nbsp;</td>";
        echo "<td align=\"right\">".formato_moneda($totalInteresDevolver,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalInteresCorriente,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalGestion,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalIva,"")."</td>";    
        echo "</tr>";   
        
        $totalPagado = $totalValorFuturo + $totalIva - $totalInteresDevolver + $totalInteresesMora;
        $remanentesDisponibles = $totalConsignado - $totalPagado - $totalOtros;
			
    ?>
</table>
<br/><br/>
<b><i>Detalle reliquidaci&oacute;n</i></b>
<br/><br/>
<table width="100%" border="1">
    <tr>
        <td><b>Fecha real de pago:</b></td>
        <td align="right"><?=$fechaRealPago?></td>
    </tr>
    <tr>
        <td><b>Vr. consignado:</b></td>
        <td align="right"><?=formato_moneda($totalConsignado,"")?></td>
    </tr>
    <tr>
        <td><b>Intereses de mora:</b></td>
        <td align="right"><?=formato_moneda($totalInteresesMora,"")?></td>
    </tr>    
    <tr>
        <td><b>Total pagado:</b></td>
        <td align="right"><?=formato_moneda($totalPagado,"")?></td>
    </tr>
    <tr>
        <td><b>Otros descuentos:</b></td>
        <td align="right"><?=formato_moneda($totalOtros,"")?></td>
    </tr>
    <tr>
        <td><b>Monto a devolver antes de GMF:</b></td>
        <td align="right"><?=formato_moneda($remanentesDisponibles,"")?></td>
    </tr>
    <tr>
        <td><b>GMF:</b></td>
        <td style="text-align:right;" align="right"><?=formato_moneda($totalGmf,"")?></td>
    </tr>    
    <tr>
        <td><b>Monto devolver:</b></td>
        <?php
        	$montoDevolver = $remanentesDisponibles - $totalGmf;
        	$nuevoMontoDevolverRetenciones = $montoDevolver - $totalIVAGiroTerceros + $totalRTFGestion + $totalRTFGiroTerceros + $totalRTFIntereses + $totalRTFICA + $totalRTFIVA;
        	
			//DETERMINAMOS SI LA GESTION REFRENCIACION SUPERA EL TOPE
			if ($totalGestion < 152000){

				$nuevoMontoDevolverRetenciones-=$totalRTFGestion;
				$totalRTFGestion = 0;
			}	        	
        ?>
        <td style="text-align:right;" align="right"><?=formato_moneda(($montoDevolver),"")?></td>
    </tr>    
</table>
<br/><br/>
<b>Detalle facturaci&oacute;n:</b>
<br/><br/>
<table width="100%" border="1">
	<tr>
		<td>IVA Gesti&oacute;n:</td>
		<td align="right"><?=formato_moneda($totalIVAGestion)?></td>
	</tr>
	<tr>
		<td>IVA Giro a terceros:</td>
		<td align="right"><?=formato_moneda($totalIVAGiroTerceros)?></td>
	</tr>
	<tr>
		<td>Total factura:</td>
		<td align="right"><?=formato_moneda($totalTotalFactura)?></td>
	</tr>    
	<tr>
		<td>RTF Gesti&oacute;n:</td>
		<td align="right"><?=formato_moneda($totalRTFGestion)?></td>
	</tr>
	<tr>
		<td>RTF Giro a terceros:</td>
		<td align="right"><?=formato_moneda($totalRTFGiroTerceros)?></td>
	</tr>
	<tr>
		<td>RTF Intereses:</td>
		<td align="right"><?=formato_moneda($totalRTFIntereses)?></td>
	</tr>
	<tr>
		<td>RTF ICA Gesti&oacute;n:</td>
		<td align="right"><?=formato_moneda($totalRTFICA)?></td>
	</tr>
	<tr>
		<td>RTF IVA:</td>
		<td style="text-align:right;" align="right"><?=formato_moneda($totalRTFIVA)?></td>
	</tr>
	<tr>
		<td>Valor neto factura:</td>
		<td style="text-align:right;" align="right"><?=formato_moneda($totalNetoFactura)?></td>
	</tr>
	<tr>
		<td>Nuevo remanente:</td>
		<td style="text-align:right;" align="right"><?=formato_moneda($nuevoMontoDevolverRetenciones)?></td>
	</tr>    
</table>    



