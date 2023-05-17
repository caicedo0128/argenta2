<style>
.table-bordered {
    border: 1px solid #ddd;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
}

.td_border{
    border: 1px solid #ddd;
    border-top: 1px solid #ddd;
    border-right: 1px solid #ddd;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    padding: 8px;
}
</style>
<?php
if (!$esReporte){
?>
    <div class="panel-body well well-sm bg-primary-custom text-right " style="height: 40px;">
        <div class="col-md-12">
            <a href="javascript:;" title="Exportar" onclick="$('#modalToMail').modal('show');" class="link_custom"><i class="fa fa-envelope fa-lg"></i>Enviar informe</a>
        </div>
    </div>
    <input type="hidden" id="id_operacion_reporte" value="<?=$idOperacion?>">
<?php
}
?>
<table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
		<tr style="background-color:#F9F9F9;">
			<td class="td_border"><b>Emisor:</b></td>
			<td class="td_border right" align="right"><?=$emisor->razon_social?></td>
		</tr>
		<tr>
			<td class="td_border"><b>Pagador:</b></td>
			<td class="td_border right" align="right"><?=$pagador->razon_social?></td>
		</tr>
		<tr style="background-color:#F9F9F9;">
			<td class="td_border"><b>Fecha desembolso:</b></td>
			<td class="td_border right" align="right"><?=$fechaDesembolso?></td>
		</tr>
        <tr>
            <td class="td_border"><b>Fecha ingreso:</b></td>
            <td class="td_border" align="right"><?=$operacion->fecha_operacion?></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Inversión cancelada:</b></td>
            <td class="td_border" align="right">
            <?php
                if (Count($arrFacturasReliquidadas) > 0){
                    echo implode(" - ",($arrFacturasReliquidadas));
                }
                else
                    echo "Sin facturas";
            ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Factura Argenta pagada:</b></td>
            <td class="td_border" align="right"><?=$operacion->num_factura?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Intereses corrientes:</b></td>
            <?php
            	$totalInteresesCorrientes = $rsDataFacturas->fields["intereses_corrientes"];
            ?>
            <td class="td_border" align="right"><?=formato_moneda($totalInteresesCorrientes)?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Intereses mora:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($interesMora)?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Intereses devolver:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($interesDevolver)?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Valor factura Argenta:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($rsDataFacturas->fields["fra_argenta"])?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Remanentes devueltos:</b></td>
            <?php
            	$totalRemanentes = $devolucionRemanentes - $gmf;
            ?>
            <td class="td_border" align="right"><?=formato_moneda($totalRemanentes)?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Reintegro 4 x mil:</b></td>
            <?php
            	$totalGMF = $gmf + $operacion->gmf;
            ?>
            <td class="td_border" align="right"><?=formato_moneda($totalGMF)?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Otros:</b></td>
            <?php
            	$totalOtros = $otros + $operacion->valor_otros_operacion;
            ?>
            <td class="td_border" align="right"><?=formato_moneda($totalOtros)?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Valor inversión:</b></td>
            <td class="td_border" align="right"><?=formato_moneda(($operacion->valor_giro_final))?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Valor ingreso:</b></td>
            <td class="td_border" align="right"><?=formato_moneda(($valorPagoAbono))?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Balance:</b></td>
            <?php
            	$totalBalance = ($valorPagoAbono - $operacion->valor_giro_final - $totalOtros - $totalGMF - $totalRemanentes - $rsDataFacturas->fields["fra_argenta"] - $totalInteresesCorrientes - $interesMora + $interesDevolver);
            ?>
            <td class="td_border" align="right"><?=formato_moneda(($totalBalance))?></td>
        </tr>
</table>
