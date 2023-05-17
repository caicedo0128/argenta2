<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_desembolso').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_pago_pactada').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_real_pago').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_movimiento').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function calcularReliquidacionPT(){


    var fechaPagoPactada = new Date($("#fecha_pago_pactada").val());
    var fechaRealPago = new Date($("#fecha_real_pago").val());
    var factorTotal = $("#factor_total").val();
    var deudaFechaPagoPactada = 0;
    var valorTotalNeto = 0;
    var valorTotalIva = 0;
    var valorPago = parseFloat($("#valor_pago").val());
    var interesesInversionista = $("#intereses_inversionista").val();
    var interesesArgenta = $("#intereses_argenta").val();
    var otrosDescuentos = $("#otros_descuentos").val();
    var facturaAbonada =  $("#id_factura_abonada").val();

    //TOTAMOS LAS FACTURAS SELECCIONADAS Y SACAMOS DATOS NECESARIOS
    $("#facturas_seleccionadas").val("");
    $("#id_factura_abonada > option").removeAttr("disabled");
    var facturasSeleccionadas = false;
    $("input[class='checks_facturas_reliquidar']:checked").each(function (id) {

        facturasSeleccionadas = true;
        var idFacturaCheck = this.id;

        //VALIDAMOS EL OBJETO
        if (idFacturaCheck != ""){

            //OBTENEMOS EL VALOR FUTURO
            var valorFuturo = parseFloat($("#valor_futuro_" + idFacturaCheck).val());
            var valorNeto = parseFloat($("#valor_neto_" + idFacturaCheck).val());
            var valorIVA = parseFloat($("#valor_iva_" + idFacturaCheck).val());
            var fechaPagoPactada = $("#fecha_pago_" + idFacturaCheck).val();
            $("#deuda_fecha_pago_pactada").val(deudaFechaPagoPactada + valorFuturo + valorIVA);
            $("#valor_neto").val(valorTotalNeto + valorNeto);
            $("#valor_iva").val(valorTotalIva + valorIVA);
            $("#fecha_pago_pactada").val(fechaPagoPactada);
            $("#fecha_real_pago").val(fechaPagoPactada);
            deudaFechaPagoPactada = parseFloat($("#deuda_fecha_pago_pactada").val());
            valorTotalNeto = parseFloat($("#valor_neto").val());
            valorTotalIva = parseFloat($("#valor_iva").val());
            var facturas = $("#nro_factura_" + idFacturaCheck).val() + "-" + $("#facturas_seleccionadas").val();
            $("#facturas_seleccionadas").val(facturas);
            $("#id_factura_abonada > option[value=" + idFacturaCheck + "]").attr("disabled","disabled");
        }

    });

    //SI NO HAY FACTURAS SELECCIONADAS VOLVEMOS EN CEROS VARIOS CAMPOS
    if(facturasSeleccionadas == false){
        deudaFechaPagoPactada = 0;
        $("#deuda_fecha_pago_pactada").val(0);
    }
    else
        deudaFechaPagoPactada = $("#deuda_fecha_pago_pactada").val();

    if (fechaRealPago != "" && factorTotal != ""){

        //DIAS A RELIQUIDAR (MORA)
        var diasReliquidar = DateDiff.inDays(fechaPagoPactada, fechaRealPago);
        $(".text_dias_reliquidar").text(diasReliquidar);

        //FACTOR DIARIO
        var factorDiarioInterno = factorTotal / 30;
        $(".text_factor_diario").text(factorDiarioInterno.toFixed(2) + "%");

        //TASA INVERSIONISTA
        var tasaInversionista = <?=$operacion->tasa_inversionista?> / 100;
        $(".text_tasa_inversionista").text("<?=$operacion->tasa_inversionista?>%");

        //INTERESES MORA
        var interesesMora =  Math.round(deudaFechaPagoPactada * ((factorTotal / 100) / 30) * diasReliquidar);
        $(".text_intereses_mora").text(interesesMora);
        $('.text_intereses_mora').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //INTERESES INVERSIONISTA
        var potenciaMargen = Math.pow(1 + tasaInversionista,(diasReliquidar / 365));
        var interesesInversionista =  Math.round(deudaFechaPagoPactada-(deudaFechaPagoPactada / potenciaMargen));
        $(".text_intereses_inversionista").text(interesesInversionista);
        $('.text_intereses_inversionista').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //INTERESES ARGENTA
        var interesesArgenta =  interesesMora - interesesInversionista;
        $(".text_intereses_argenta").text(interesesArgenta);
        $('.text_intereses_argenta').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //REMANENTES DISPONIBLES
        var remanentes = valorPago - interesesMora - deudaFechaPagoPactada
        $(".text_remanentes_disponibles").text(remanentes);
        $('.text_remanentes_disponibles').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //MONTO A DEVOLVER 1
        var montoDevolver1 =  remanentes - otrosDescuentos;
        $("#devolucion_remanentes").val(montoDevolver1);
        $(".text_monto_devolver1").text(montoDevolver1);
        $('.text_monto_devolver1').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //GMF DEVOLUCIÓN
		var aplicaImpuesto = $("input[id='aplica_impuesto_reli']:checked").val();
		var factorGMF = 0.3984;    
		if (aplicaImpuesto == 2)
			factorGMF = 0;        
      
        var gmfDevolucion = Math.round(montoDevolver1 * (factorGMF/100));
        if (facturaAbonada != "" && facturaAbonada != null){
            gmfDevolucion = 0;
        }

        $("#gmf_pt").val(gmfDevolucion);
        $(".text_gmf_devolucion").text(gmfDevolucion);
        $('.text_gmf_devolucion').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //MONTO A DEVOLVER 2
        var montoDevolver2 =  montoDevolver1 - gmfDevolucion;
        $(".text_monto_devolver2").text(montoDevolver2);
        $('.text_monto_devolver2').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });
    }

    //VALIDAMOS VALORES DE PAGO
    if (valorPago < deudaFechaPagoPactada && valorPago != ""){
        showError("El valor del pago no permite un valor menor al de la deuda");
        $("#valor_pago").val("");
        return false;
    }

    setDataInfoCliente();
}

function calcularReliquidacionPTAnterior(){


    var fechaPagoPactada = new Date($("#fecha_pago_pactada").val());
    var fechaRealPago = new Date($("#fecha_real_pago").val());
    var factorTotal = $("#factor_total").val();
    var deudaFechaPagoPactada = 0;
    var valorTotalNeto = 0;
    var valorPago = parseFloat($("#valor_pago").val());
    var interesesInversionista = $("#intereses_inversionista").val();
    var interesesArgenta = $("#intereses_argenta").val();
    var otrosDescuentos = $("#otros_descuentos").val();
    var facturaAbonada =  $("#id_factura_abonada").val();

    //TOTAMOS LAS FACTURAS SELECCIONADAS Y SACAMOS DATOS NECESARIOS
    $("#facturas_seleccionadas").val("");
    $("#id_factura_abonada > option").removeAttr("disabled");
    var facturasSeleccionadas = false;
    $("input:checked").each(function (id) {

        facturasSeleccionadas = true;
        var idFacturaCheck = this.id;

        //VALIDAMOS EL OBJETO
        if (idFacturaCheck != ""){

            //OBTENEMOS EL VALOR FUTURO
            var valorFuturo = parseFloat($("#valor_futuro_" + idFacturaCheck).val());
            var valorNeto = parseFloat($("#valor_neto_" + idFacturaCheck).val());
            var fechaPagoPactada = $("#fecha_pago_" + idFacturaCheck).val();
            $("#deuda_fecha_pago_pactada").val(deudaFechaPagoPactada + valorFuturo);
            $("#valor_neto").val(valorTotalNeto + valorNeto);
            $("#fecha_pago_pactada").val(fechaPagoPactada);
            $("#fecha_real_pago").val(fechaPagoPactada);
            deudaFechaPagoPactada = parseFloat($("#deuda_fecha_pago_pactada").val());
            valorTotalNeto = parseFloat($("#valor_neto").val());
            var facturas = $("#nro_factura_" + idFacturaCheck).val() + "-" + $("#facturas_seleccionadas").val();
            $("#facturas_seleccionadas").val(facturas);
            $("#id_factura_abonada > option[value=" + idFacturaCheck + "]").attr("disabled","disabled");
        }

    });

    //SI NO HAY FACTURAS SELECCIONADAS VOLVEMOS EN CEROS VARIOS CAMPOS
    if(facturasSeleccionadas == false){
        deudaFechaPagoPactada = 0;
        $("#deuda_fecha_pago_pactada").val(0);
    }
    else
        deudaFechaPagoPactada = $("#deuda_fecha_pago_pactada").val();

    if (fechaRealPago != "" && factorTotal != ""){

        //DIAS A RELIQUIDAR (MORA)
        var diasReliquidar = DateDiff.inDays(fechaPagoPactada, fechaRealPago);
        $(".text_dias_reliquidar").text(diasReliquidar);

        //FACTOR DIARIO
        var factorDiarioInterno = factorTotal / 30;
        $(".text_factor_diario").text(factorDiarioInterno.toFixed(2) + "%");

        //TASA INVERSIONISTA
        var tasaInversionista = <?=$operacion->tasa_inversionista?> / 100;
        $(".text_tasa_inversionista").text("<?=$operacion->tasa_inversionista?>%");

        //INTERESES MORA
        var interesesMora =  Math.round(deudaFechaPagoPactada * ((factorTotal / 100) / 30) * diasReliquidar);
        $(".text_intereses_mora").text(interesesMora);
        $('.text_intereses_mora').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //INTERESES INVERSIONISTA
        var potenciaMargen = Math.pow(1 + tasaInversionista,(diasReliquidar / 365));
        var interesesInversionista =  Math.round(deudaFechaPagoPactada-(deudaFechaPagoPactada / potenciaMargen));
        $(".text_intereses_inversionista").text(interesesInversionista);
        $('.text_intereses_inversionista').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //INTERESES ARGENTA
        var interesesArgenta =  interesesMora - interesesInversionista;
        $(".text_intereses_argenta").text(interesesArgenta);
        $('.text_intereses_argenta').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //REMANENTES DISPONIBLES
        var remanentes = valorPago - interesesMora - deudaFechaPagoPactada
        $(".text_remanentes_disponibles").text(remanentes);
        $('.text_remanentes_disponibles').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //MONTO A DEVOLVER 1
        var montoDevolver1 =  remanentes - otrosDescuentos;
        $("#devolucion_remanentes").val(montoDevolver1);
        $(".text_monto_devolver1").text(montoDevolver1);
        $('.text_monto_devolver1').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //GMF DEVOLUCIÓN
		var aplicaImpuesto = $("#aplica_impuesto_reli").val();
		var factorGMF = 0.3984;    
		if (aplicaImpuesto == 2)
			factorGMF = 0;        
		alert(factorGMF);			
        var gmfDevolucion = Math.round(montoDevolver1 * (factorGMF/100));
        if (facturaAbonada != "" && facturaAbonada != null){
            gmfDevolucion = 0;
        }

        $("#gmf_pt").val(gmfDevolucion);
        $(".text_gmf_devolucion").text(gmfDevolucion);
        $('.text_gmf_devolucion').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //MONTO A DEVOLVER 2
        var montoDevolver2 =  montoDevolver1 - gmfDevolucion;
        $(".text_monto_devolver2").text(montoDevolver2);
        $('.text_monto_devolver2').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });
    }

    //VALIDAMOS VALORES DE PAGO
    if (valorPago < deudaFechaPagoPactada && valorPago != ""){
        showError("El valor del pago no permite un valor menor al de la deuda");
        $("#valor_pago").val("");
        return false;
    }

    setDataInfoCliente();
}

function setDataInfoCliente(){

    $(".text_emisor").text($("#id_emisor option:selected").text());
    $(".text_pagador").text($("#id_pagador option:selected").text());
    $(".text_facturas").text($("#facturas_seleccionadas").val());
    $(".text_fecha_desembolso").text($("#fecha_desembolso").val());
    $(".text_fecha_pago_pactada").text($("#fecha_pago_pactada").val());
    $(".text_fecha_real_pago").text($("#fecha_movimiento").val());
}


</script>
<div class="">
<div class="col-md-6">
	<?php
		//ESTE ES UN AJUSTE TEMPORAL POR EL CAMBIO DE CALCULOS EN LAS RELIQUIDACIONES
		$functionCalculos = "calcularReliquidacionPT";
		if ($operacion->fecha_operacion < "2019-01-01"){
			$functionCalculos = "calcularReliquidacionPTAnterior";
		}		
	?>
    <div class="row">
		<input type="hidden" name="fecha_desembolso" id="fecha_desembolso" value="<?=$operacion->fecha_operacion?>">
		<input type="hidden" name="facturas_seleccionadas" id="facturas_seleccionadas" value="">
		<input type="hidden" name="valor_neto" id="valor_neto" value="">
		<input type="hidden" name="valor_iva" id="valor_iva" value="">
		<div class="col-md-3 labelCustom">
			Fecha operación:
			<div class="form-control" disabled="disabled">
			<?=$operacion->fecha_operacion?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Fecha pago pactada:
			<div class="">
			<?php
				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("fecha_pago_pactada", "fecha_desembolso", 1, $reliquidacionPT->fecha_pago_pactada, "form-control required", 50, "", $functionCalculos . "();", "1","","");
			?>
			</div>
		</div>	
		<div class="col-md-3 labelCustom">
			Fecha real de pago:
			<div class="">
			<?php
				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("fecha_real_pago", "fecha_real_pago", 1, $reliquidacionPT->fecha_real_pago, "form-control required", 50, "", $functionCalculos . "();", "","","");
		   ?>
		   </div>
		</div> 
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-3 labelCustom">
        	Fecha base movimiento:
			<div class="">
			<?php
				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("fecha_movimiento", "fecha_movimiento", 1, $reliquidacionPT->fecha_movimiento, "form-control required", 50, "", $functionCalculos . "();", "0","","");
		   ?>
		   </div>
       	</div>     
		<div class="col-md-3 labelCustom">
        	Tasa:
			<div class="">
			<?php
				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("factor_total", "factor_total", 1, $reliquidacionPT->factor_total_tasa, "form-control required number", 50, "7", $functionCalculos . "();", "","","return IsNumber(event);");
		   	?>
		   	</div>
       	</div>
		<div class="col-md-3 labelCustom">
			Deuda a la fecha pactada:
			<div class="">
			<?php
				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("deuda_fecha_pago_pactada", "deuda_fecha_pago_pactada", 1, $reliquidacionPT->deuda_fecha_pago_pactada, "form-control required number", 50, "", $functionCalculos . "();", "1","","return IsNumber(event);");
			?>
			</div>
       	</div>          	
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
       	<div class="col-md-3 labelCustom">
        	Valor pago:<input type="checkbox" onclick="obtenerValor('valor_neto','valor_pago', this, 0);<?=$functionCalculos . "();"?>" class="">Obtener dato.
			<div class="">			
			<?php
				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("valor_pago", "valor_pago", 1, $reliquidacionPT->valor_pago, "form-control required number", 50, "", $functionCalculos."();", "","","return IsNumber(event);");
		   	?>
		   	</div>
       	</div>
     	<div class="col-md-3 labelCustom">
        	Otros descuentos:
			<div class="">
			<?php

				//LOS OTROS DESCUENTOS NO SALEN DE LA OPERACION
				//$otrosDescuentos = $operacion->valor_otros_operacion;
				//if ($reliquidacionPT->otros_descuentos != "")
				$otrosDescuentos = $reliquidacionPT->otros_descuentos;

				$c_textbox = new Textbox;
				echo $c_textbox->Textbox ("otros_descuentos", " otros_descuentos", 1, $otrosDescuentos, "form-control required number", 50, "", $functionCalculos."();", "","","return IsNumber(event);");
		   ?>
		   </div>
       </div>       
    </div>
</div>
<style>
    .reliquidacion_cliente{
        display:none;
    }
</style>
<div class="col-md-3">
    <b>Re-Liquidación Interna:</b>
    <table id="reliquidacion_cliente"  width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
        <tr style="background-color:#F9F9F9;">
            <td class="td_border">Días reliquidar (mora):</td>
            <td class="td_border right"><span class="text_dias_reliquidar"></span></td>
        </tr>
        <tr>
            <td class="td_border">Factor diario:</td>
            <td class="td_border right"><span class="text_factor_diario"></span></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border">Intereses mora:</td>
            <td class="td_border right"><span class="text_intereses_mora"></span></td>
        </tr>
        <tr>
            <td class="td_border">Intereses inversionista:</td>
            <td class="td_border right"><span class="text_intereses_inversionista"></span></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border">Intereses Argenta:</td>
            <td class="td_border right"><span class="text_intereses_argenta"></span></td>
        </tr>
        <tr >
            <td class="td_border">Remanentes disponibles:</td>
            <td class="td_border right"><span class="text_remanentes_disponibles"></span></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border">Monto a devolver antes de GMF:</td>
            <td class="td_border right">
                <span class="text_monto_devolver1"></span>
                <input type="hidden" name="devolucion_remanentes" id="devolucion_remanentes" value="<?=$reliquidacionPT->devolucion_remanentes?>">
            </td>
        </tr>
        <tr>
            <td class="td_border">GMF devolución:</td>
            <td class="td_border right">
                <span class="text_gmf_devolucion"></span>
                <input type="hidden" name="gmf" id="gmf_pt" value="<?=$reliquidacionPT->gmf?>">
            </td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border">Monto devolver:</td>
            <td class="td_border right"><span class="text_monto_devolver2"></span></td>
        </tr>
    </table>
</div>
<div class="col-md-3">
	<b>Detalle facturaci&oacute;n:</b>
	<table  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
		<tr>
			<td>IVA Gesti&oacute;n:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->iva_gestion)?></td>
		</tr>
		<tr>
			<td>IVA Giro a terceros:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->iva_giro_terceros)?></td>
		</tr>
		<tr>
			<td>Total factura:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->total_factura)?></td>
		</tr>    
		<tr>
			<td>RTF Gesti&oacute;n:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->rtf_gestion)?></td>
		</tr>
		<tr>
			<td>RTF Giro a terceros:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->rtf_giro_terceros)?></td>
		</tr>
		<tr>
			<td>RTF Intereses:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->rtf_intereses)?></td>
		</tr>
		<tr>
			<td>RTF ICA Gesti&oacute;n:</td>
			<td align="right"><?=formato_moneda($reliquidacionPT->rtf_ica)?></td>
		</tr>
		<tr>
			<td>RTF IVA:</td>
			<td style="text-align:right;" align="right"><?=formato_moneda($reliquidacionPT->rtf_iva)?></td>
		</tr>
		<tr>
			<td>Valor neto factura:</td>
			<td style="text-align:right;" align="right"><?=formato_moneda($reliquidacionPT->neto_factura)?></td>
		</tr>
		<tr>
			<td>Nuevo remanente:</td>
			<td style="text-align:right;" align="right"><?=formato_moneda($reliquidacionPT->nuevo_remanente)?></td>
		</tr>    
	</table>    
</div>

