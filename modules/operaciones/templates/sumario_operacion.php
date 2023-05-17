<div class="">
<?php
	if ($_SESSION["profile_text"] != "Cliente"){
?>
    <table width="100%" style="color:#6d6d6d;margin-bottom:5px;" class="table table-bordered table-striped">
    <tr>
        <td><b>Valor neto:</b></td>
        <td><b>Valor futuro:</b></td>
        <td><b>Descuento total:</b></td>
        <td><b>Interés corriente:</b></td>
        <td colspan="2"><b>Gestión de referenciación:</b></td>
        <td><b>Comisión ejecutivo:</b></td>
    </tr>
    <tr>
        <td><?=formato_moneda($operacion->valor_neto)?></td>
        <td><?=formato_moneda($operacion->valor_futuro)?></td>
        <td><?=formato_moneda($operacion->descuento_total)?></td>
        <td><?=formato_moneda($operacion->margen_inversionista)?></td>
        <td colspan="2"><?=formato_moneda($operacion->margen_argenta)?></td>
        <td><?php
            $comisionEjecutivo = ($operacion->margen_argenta * $operacion->comision) / 100;
            ?>
            <?=formato_moneda($comisionEjecutivo)?>
        </td>
    </tr>
    <tr>
        <td><b>Iva factura asesoría:</b></td>
        <td><b>Factura Argenta:</b></td>
        <td><b>Costo transferencia:</b></td>
        <td><b>Giro antes GMF:</b></td>
        <td><b>GMF:</b></td>
        <td><b>GMF Manual:</b></td>
        <td><b>Valor giro final:</b></td>
    </tr>
    <tr>
        <td><?=formato_moneda($operacion->iva_fra_asesoria)?></td>
        <td><?=formato_moneda($operacion->fra_argenta)?></td>
        <td><?=formato_moneda($operacion->valor_otros_operacion)?></td>
        <td><?=formato_moneda($operacion->giro_antes_gmf)?></td>
        <td><?=formato_moneda($operacion->gmf)?></td>
        <td><?=formato_moneda($operacion->gmf_manual)?>&nbsp;&nbsp;<a href="javascript:cambiarGMFManual()" title="Actualizar GMF Manual"><i class="fa fa-refresh"></i></a></td>
        <td><?=formato_moneda($operacion->valor_giro_final)?></td>
    </tr>
    </table>
<?php
	}
?>
<?php
	if ($_SESSION["profile_text"] == "Cliente"){
?>
    <table width="100%" style="color:#6d6d6d;margin-bottom:5px;" class="table table-bordered table-striped">
    <tr>
        <td><b>Valor neto:</b></td>
        <td><b>Valor futuro:</b></td>
        <td><b>Descuento total:</b></td>
        <td><b>Costo transferencia:</b></td>
        <td><b>Giro antes GMF:</b></td>
        <td><b>GMF:</b></td>
        <td><b>Valor giro final:</b></td>
    </tr>
    <tr>
        <td><?=formato_moneda($operacion->valor_neto)?></td>
        <td><?=formato_moneda($operacion->valor_futuro)?></td>
        <td><?=formato_moneda($operacion->descuento_total)?></td>
        <td><?=formato_moneda($operacion->valor_otros_operacion)?></td>
        <td><?=formato_moneda($operacion->giro_antes_gmf)?></td>
        <td><?=formato_moneda($operacion->gmf)?></td>
        <td><?=formato_moneda($operacion->valor_giro_final)?></td>
    </tr>
    </table>
<?php
	}
?>
</div>
