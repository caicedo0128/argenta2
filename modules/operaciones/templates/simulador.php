<script type="text/javascript">

$(document).ready(function(){
	$('#fecha_pago').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_operacion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true }).on('dp.change', function (e) {
    	var fechaPago = new Date($("#fecha_operacion").val());
		var fechaLimite = dateAdd(fechaPago, "day", 30);
        $('#fecha_pago').data('DateTimePicker').minDate(moment(fechaLimite, 'YYYY-MM-DD'));
    });
    
    

});

function generarSimulacion(){

    validateForm("datosSimulador");

    if ($("#datosSimulador").valid()){

		var porcentajeDescuento = $("#porcentaje_descuento").val();
		var factor = $("#factor").val();
		var valorNeto = $("#valor_neto").val();
		var fechaInicial = new Date($("#fecha_operacion").val());
		var fechaFinal = new Date($("#fecha_pago").val());
		var diasDiferencia = DateDiff.inDays(fechaInicial, fechaFinal);
		$("#dias").text(diasDiferencia);

		//VALOR FUTURO
		var valorFuturo = Math.round((valorNeto * porcentajeDescuento) / 100);
		$("#vr_futuro").text(valorFuturo);
		$('#vr_futuro').priceFormat({
			prefix: '$ ',
			centsSeparator: ',',
			thousandsSeparator: '.',
			centsLimit: 0
		});

		//DESCUENTO TOTAL
		var descuentoTotal = Math.round((((diasDiferencia * factor) / 100) / 30) * valorFuturo);
		$("#descuento_total").text(descuentoTotal);
		$('#descuento_total').priceFormat({
			prefix: '$ ',
			centsSeparator: ',',
			thousandsSeparator: '.',
			centsLimit: 0
		});

		var giroAntesGMF =  Math.round(valorFuturo - descuentoTotal);
		$("#giro_antes_gmf").text(giroAntesGMF);
		$('#giro_antes_gmf').priceFormat({
			prefix: '$ ',
			centsSeparator: ',',
			thousandsSeparator: '.',
			centsLimit: 0
		});

    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

</script>
<div class="panel panel-primary col-md-offset-3 col-md-7" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        Simulador de operaciones
        <hr />
        <form id="datosSimulador" method="post" name="datosSimulador" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="operaciones" />
            <input type="hidden" name="action" value="verSimulacion" />
            <center>
            <table class="table table table-bordered table-striped" width="400px" style="width:400px !important;">
            	<tr>
            		<td nowrap="nowrap">Fecha operación:</td>
            		<td>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_operacion", "fecha_operacion", 1, "", "form-control", 50, "", "", "");
					?>
            		</td>
            		<td>Vr. Futuro:</td>
            		<td align="right">
						<span id="vr_futuro"></span>
            		</td>
            	</tr>
            	<tr>
            		<td>% Descuento:</td>
            		<td>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("porcentaje_descuento", "porcentaje_descuento", 1, "", "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
            		</td>
            		<td>Días:</td>
            		<td align="right">
						<span id="dias"></span>
            		</td>
            	</tr>
            	<tr>
            		<td>% Factor:</td>
            		<td>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("factor", "factor", 1, "", "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
            		</td>
            		<td>Descuento total:</td>
            		<td align="right">
						<span id="descuento_total"></span>
            		</td>
            	</tr>
            	<tr>
            		<td>Fecha de Pago:</td>
            		<td>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_pago", "fecha_pago", 1, "", "form-control", 50, "", "", "");
					?>
            		</td>
            		<td nowrap="nowrap">Giro Antes GMF:</td>
            		<td nowrap="nowrap" align="right">
						<span id="giro_antes_gmf">&nbsp;</span>
            		</td>
            	</tr>
            	<tr>
            		<td>Vr. Neto:</td>
            		<td>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("valor_neto", "valor_neto", 1, "", "form-control number", 50, "", "", "","","return IsNumber(event);");
					?>
            		</td>
            		<td colspan="2">
						<input type="button" value="Ver simulación" class="btn btn-primary datosSeguimiento_btnSave" onclick="generarSimulacion();">
            		</td>
            	</tr>
            </table>
            </center>
      	</form>
	</div>
</div>



