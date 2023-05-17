<?php
    if ($_REQUEST["es_excel"]!='true')
    {
        header("Content-Type:application/vnd.ms-excel; charset=utf-8");
        header("Content-type:application/x-msexcel; charset=utf-8");
        header("Content-Disposition: attachment; filename=reportePagoFacturas.xls");
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
    <tr>
        <th>Fecha pago</th>
        <th>Nro factura</th>
        <th>Valor neto</th>
        <th>Valor futuro</th>
        <th>D&iacute;as mora</th>
        <th><?=($reliquidacion->id_tipo_reliquidacion == 6?"Intereses a devolver":"Intereses de mora")?></th>
        <th>Inter&eacute;s corriente</th>
        <th>Gesti&oacute;n de referenciaci&oacute;n</th>
        <th>Iva factura</th>
    </tr>
    <?php
        while(!$arrDetalleFacturas->EOF){

            $arrDiferencia = date_diff_custom($arrDetalleFacturas->fields["fecha_pago"],$fechaRealPago);
            
            //DETERMINAMOS EL TIPO DE RELIQUIDACION
            if ($reliquidacion->id_tipo_reliquidacion==6){
                //ES PAGO TOTAL ANTICIPADO
                $interesCorriente = $arrDetalleFacturas->fields["margen_inversionista_reli"];
                $gestionReferenciacion = ($arrDetalleFacturas->fields["descuento_total_reli"] - $arrDetalleFacturas->fields["margen_inversionista_reli"]);
                $ivaFactura = $arrDetalleFacturas->fields["iva_fra_asesoria_reli"];
                //$interesesDevolver = $arrDetalleFacturas->fields["descuento_total"] - $arrDetalleFacturas->fields["descuento_total_reli"];
            }
            else{
                $interesCorriente = $arrDetalleFacturas->fields["margen_inversionista"];
                $gestionReferenciacion = ($arrDetalleFacturas->fields["descuento_total"] - $arrDetalleFacturas->fields["margen_inversionista"]);
                $ivaFactura = $arrDetalleFacturas->fields["iva_fra_asesoria"];            
            }
            
            //VALIDAR CONTRA EL NUEVO CALCULO
            $interesesDevolver = $arrDetalleFacturas->fields["intereses_devolver"];
            $interesesMora = $arrDetalleFacturas->fields["intereses_mora"];
            
            echo "<tr>";
            echo "<td align='center'>".$arrDetalleFacturas->fields["fecha_pago"]."</td>";
            echo "<td align='center'>".$arrDetalleFacturas->fields["num_factura"]."</td>";
            echo "<td align=\"right\">".formato_moneda($arrDetalleFacturas->fields["valor_neto"],"")."</td>";
            echo "<td align=\"right\">".formato_moneda($arrDetalleFacturas->fields["valor_futuro"],"")."</td>";
            echo "<td align=\"right\">".$arrDiferencia["d"]."</td>";
            echo "<td align=\"right\">".formato_moneda(($reliquidacion->id_tipo_reliquidacion == 6?$interesesDevolver:$interesesMora),"")."</td>";
            echo "<td align=\"right\">".formato_moneda($interesCorriente,"")."</td>";
            echo "<td align=\"right\">".formato_moneda($gestionReferenciacion,"")."</td>";
            echo "<td align=\"right\">".formato_moneda($ivaFactura,"")."</td>";
            echo "</tr>";
            $totalValorNeto += $arrDetalleFacturas->fields["valor_neto"];
            $totalValorFuturo += $arrDetalleFacturas->fields["valor_futuro"];
            $totalInteresDevolver += $interesesDevolver;
            $totalInteresMora += $interesesMora;
            $totalInteresCorriente += $interesCorriente;
            $totalGestion += $gestionReferenciacion;
            $totalIva += $ivaFactura;

            $arrDetalleFacturas->MoveNext();

        }

        echo "<tr>";
        echo "<td colspan=\"2\">TOTALES:</td>";
        echo "<td align=\"right\">".formato_moneda($totalValorNeto,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalValorFuturo,"")."</td>";
        echo "<td align=\"right\">&nbsp;</td>";
        echo "<td align=\"right\">".formato_moneda(($reliquidacion->id_tipo_reliquidacion == 6?$totalInteresDevolver:$totalInteresMora),"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalInteresCorriente,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalGestion,"")."</td>";
        echo "<td align=\"right\">".formato_moneda($totalIva,"")."</td>";    
        echo "</tr>";       

         //DETERMINAMOS EL TIPO DE RELIQUIDACION
        if ($reliquidacion->id_tipo_reliquidacion==6){
            //ES PAGO TOTAL ANTICIPADO
            $totalPagado = $totalValorFuturo - $totalInteresDevolver + $totalIva;
        }
        else{
            $totalPagado = $totalValorFuturo + $totalInteresMora + $totalIva;
        }
    ?>
</table>  
<br/><br/>
<b><i>Detalle reliquidaci&oacute;n</i></b>
<br/><br/>
<table width="100%" border="1" style="font-size:9px !important;">
    <tr>
        <td>Fecha real de abono:</td>
        <td align="right"><?=$fechaRealPago?></td>
    </tr>
    <tr>
        <td>Vr. consignado:</td>
        <td align="right"><?=formato_moneda($valorIngreso,"")?></td>
    </tr>  
    <tr>
        <td>Total adeudado:</td>
        <td align="right"><?=formato_moneda($totalPagado,"")?></td>
    </tr>       
    <tr>
        <td>Nuevo valor obligaci&oacute;n:</td>
        <td align="right"><?=formato_moneda($nuevoValorObligacion,"")?></td>
    </tr>    
</table>
<?php
    $i = 1;
    while (!$rsDataReliquidacionPP->EOF){
                
    if ($i > 1){
    
    
        $interesesMora = $rsDataReliquidacionPP->fields["abono"] - $rsDataReliquidacionPP->fields["devolucion_remanentes"] - $nuevoValorObligacion;
        $interesesMora = $rsDataReliquidacionPP->fields["intereses_mora"];
?>
    <br/><br/>
    <b><i>Detalle abono <?=$i?></i></b>
    <br/><br/>
    <table width="100%" border="1" style="font-size:9px !important;">
        <tr>
            <td>Fecha pago:</td>
            <td align="right"><?=$rsDataReliquidacionPP->fields["fecha_real_pago"]?></td>
        </tr>
        <tr>
            <td>Vr. consignado:</td>
            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["abono"],"")?></td>
        </tr>
        <tr>
            <td>Intereses de mora:</td>
            <td align="right"><?=formato_moneda($interesesMora,"")?></td>
        </tr>    
        <tr>
            <td>Total adeudado:</td>
            <td align="right"><?=formato_moneda($pagoAbonoAnterior + $interesesMora,"")?></td>
        </tr>
        <tr>
            <td>Otros descuentos:</td>
            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["otros"],"")?></td>
        </tr>
        <tr>
            <td>Monto a devolver antes de GMF:</td>
            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["devolucion_remanentes"],"")?></td>
        </tr>
        <tr>
            <td>GMF:</td>
            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["gmf"],"")?></td>
        </tr>    
        <tr>
            <td>Monto devolver:</td>
            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["monto_devolver"],"")?></td>
        </tr>  
        <tr>
            <td>Nuevo valor obligaci&oacute;n:</td>
            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["nuevo_valor_obligacion"],"")?></td>
        </tr>         
    </table>
<?php
        
    }    
    $i++;
    $nuevoValorObligacion = $rsDataReliquidacionPP->fields["nuevo_valor_obligacion"];
    $pagoAbonoAnterior = $nuevoValorObligacion;
    
    //TOMAMOS EL ULTIMO MONTO A DEVOLVER DEL ULTIMO ABONO Y SUMAMOS RTE DE INTERES = NUEVO REMANENTE
    $vrNuevoRemanente = $rsDataReliquidacionPP->fields["monto_devolver"] + $rsDataReliquidacionPP->fields["rtf_intereses"];
    
    $rsDataReliquidacionPP->MoveNext();
}
?>

<br/><br/>
<b><i>Detalle facturaci&oacute;n - Factura: ARG-<?=$numFacturaArgenta?></i></b>
<br/><br/>
<table width="100%" border="1" style="font-size:9px !important;">
    <tr>
        <td>IVA Gesti&oacute;n:</td>
        <td align="right"><?=formato_moneda($ivaGestion,"")?></td>
    </tr>
    <tr>
        <td>IVA Giro a terceros:</td>
        <td align="right"><?=formato_moneda($ivaGiroTerceros,"")?></td>
    </tr>
    <tr>
        <td>Total factura:</td>
        <td align="right"><?=formato_moneda($vrTotalFactura,"")?></td>
    </tr>    
    <tr>
        <td>RTF Gesti&oacute;n:</td>
        <td align="right"><?=formato_moneda($rtfGestion,"")?></td>
    </tr>
    <tr>
        <td>RTF Giro a terceros:</td>
        <td align="right"><?=formato_moneda($rtfGiroTerceros,"")?></td>
    </tr>
    <tr>
        <td>RTF Intereses:</td>
        <td align="right"><?=formato_moneda($rtfIntereses,"")?></td>
    </tr>
    <tr>
        <td>RTF ICA Gesti&oacute;n:</td>
        <td align="right"><?=formato_moneda($rtfICA,"")?></td>
    </tr>
    <tr>
        <td>RTF IVA:</td>
        <td style="text-align:right;" align="right"><?=formato_moneda($rtfIVA,"")?></td>
    </tr>
    <tr>
        <td>Valor neto factura:</td>
        <td style="text-align:right;" align="right"><?=formato_moneda($vrNetoFactura,"")?></td>
    </tr>
    <tr>
        <td>Remanente final: (&Uacute;ltimo monto devolver + RTE Inter&eacute;s)</td>
        <td style="text-align:right;" align="right"><?=formato_moneda($vrNuevoRemanente,"")?></td>
    </tr>    
</table>

