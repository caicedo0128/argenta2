<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_pago_comision').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_operacion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
	$("#id_pagador").select2({ placeholder: 'Seleccione uno...',allowClear: true});
});

function saveOperacion(){

    validateForm("datosOperacion");

    if ($("#datosOperacion").valid()){

        showLoading("Enviando informacion. Espere por favor...");
        var dataForm = "Ajax=true&" + $("#datosOperacion").serialize();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    showSuccess(response.Message);
                    if (response.Success) {
                        $("#id_operacion").val(response.IdOperacion);
                        editOperacion(response.IdOperacion);
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function cargarFacturas() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#facturas_operacion").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'listFacturas', id_operacion : idOperacion}, function () {
        loader();
        $("#desembolsos_operacion").removeClass("active");
        $("#reporte_cliente").removeClass("active");
        $("#reporte_inversionista").removeClass("active");
        $("#reliquidaciones_operacion").removeClass("active");
        $("#reportes").removeClass("active");
        $("#inversionistas_operacion").removeClass("active");
        $("#seguimiento").removeClass("active");
        $("#facturas_operacion").addClass("active");
        goObjHtml("tabs_operacion", 70);
    });
}

function cargarDesembolsos() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#desembolsos_operacion").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'listDesembolsos', id_operacion : idOperacion}, function () {
        loader();
        $("#desembolsos_operacion").addClass("active");
        goObjHtml("tabs_operacion", 70);
    });
}

function reporteCliente() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#reporte_cliente").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'reporteCliente', id_operacion : idOperacion}, function () {
        loader();
        $("#reporte_cliente").addClass("active");
        goObjHtml("tabs_operacion", 70);
    });
}

function cargarReliquidaciones() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#reliquidaciones_operacion").load('admindex.php', { Ajax:true, mod: 'reliquidaciones', action:'listReliquidaciones', id_operacion : idOperacion}, function () {
        loader();
        $("#reliquidaciones_operacion").addClass("active");
        goObjHtml("tabs_operacion", 70);
    });
}

function cargarReportes() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#reportes").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'reportesOperacion', id_operacion : idOperacion}, function () {
        loader();
        $("#reportes").addClass("active");
        goObjHtml("tabs_operacion", 70);
    });
}

function cargarSeguimiento() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#seguimiento").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'listSeguimientos', id_operacion : idOperacion}, function () {
        loader();
        $("#seguimiento").addClass("active");
        goObjHtml("tabs_operacion", 70);
    });
}

function cargarInfoEmisor(idEmisor){

	cargarSelect('mod=clientes&action=cargarPagadoresDependientes', idEmisor, 'id_pagador', '', '');
	traerCondiciones('emisor');
}

function traerCondiciones(tipo){

	$("#factor").val("");
	$("#porcentaje_descuento").val("");
	$("#factor").removeAttr("readonly");
	$("#porcentaje_descuento").removeAttr("readonly");
	var idPagador = $("#datosOperacion select[name=id_pagador]").val();
	var idCliente = $("#id_emisor").val();
	var strUrl = "admindex.php?Ajax=true&mod=clientes&action=cargarInfoTerceroJson&tipo=" + tipo + "&id_cliente=" + idCliente + "&id_pagador=" + idPagador;
	$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			success: function (response) {
				if (response.Success) {
					if (response.Factor != null){
						$("#factor").val(response.Factor);
						$("#factor").attr("readonly","readonly");
					}

					if (response.Descuento != null){
						$("#porcentaje_descuento").val(response.Descuento);
						$("#porcentaje_descuento").attr("readonly","readonly");
					}

                    if (tipo == "emisor"){
                        $("#id_ejecutivo").val("");
                        if (response.IdEjecutivo != null){
                            $("#id_ejecutivo").val(response.IdEjecutivo);
                        }

                        $("#comision").val("");
                        if (response.Comision != null){
                            $("#comision").val(response.Comision);
                        }
                    }
				}
			}
	});

}

function enviarAValidacion(){

	validateForm("datosOperacion");

	if ($("#datosOperacion").valid()){

		bootbox.confirm('Va a enviar la operación al area de estudio de Argenta.<br/><br/>El proceso no se podrá deshacer.<br/><br/>Desea continuar?.', function (result) {

			if (result) {

				showLoading("Enviando informacion. Espere por favor...");

				$("#datosOperacion input[id=action]").val("operacionValidacion");

				var dataForm = "Ajax=true&" + $("#datosOperacion").serialize();
				var strUrl = "admindex.php";
				$.ajax({
						type: 'POST',
						url: strUrl,
						dataType: "json",
						data:dataForm,
						success: function (response) {
							closeNotify();
							if (response.Success){
								showSuccess("Transacción exitosa. Espere por favor...");
								editOperacion(response.IdOperacion);
							}
							else
								showError("Por favor revise los siguientes items:<br/><br/>" + response.Message,6000);
						}
				});
			}
		});
	}
	else {
		showError("Por favor revise los campos marcados.");
	}
}


</script>
<div class="panel panel-primary">
    <div class="panel-body">
        Registro de información de operación
		<?php
        	if ($operacion->id_operacion != 0){
        		echo "<span class='label label-primary text-white' style='padding:5px;font-size:12px;'>ID: ".$operacion->id_operacion."</span>&nbsp;";
        		echo "<span class='label label-info text-white' style='padding:5px;font-size:12px;'>Estado: ".$this->arrEstados[$operacion->estado]."</span>&nbsp;";
        	}
		?>
        <div class="cerrar_form" onclick="cargarOperaciones();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosOperacion" method="post" name="datosOperacion" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="operaciones" />
            <input type="hidden" id="action" name="action" value="saveOperacion" />
            <input type="hidden" id="desde" name="desde" value="cliente" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />
			<input type="hidden" name="fecha_pago_operacion" id="fecha_pago_operacion" value="">
			<input type="hidden" name="fecha_vencimiento" id="fecha_vencimiento" value="">
			<input type="hidden" name="descripcion_otros" id="descripcion_otros" value="">
			<input type="hidden" name="tipo_operacion" id="tipo_operacion" value="<?=($operacion->tipo_operacion==""?1:$operacion->tipo_operacion)?>">
			<input type="hidden" name="aplica_impuesto" id="aplica_impuesto" value="<?=($operacion->aplica_impuesto==""?2:$operacion->aplica_impuesto)?>">
			<input type="hidden" name="tasa_inversionista" id="tasa_inversionista" value="<?=$operacion->tasa_inversionista?>">
			<input type="hidden" name="valor_otros_operacion" id="valor_otros_operacion" value="<?=$operacion->valor_otros_operacion?>">
			<input type="hidden" name="id_ejecutivo" id="id_ejecutivo" value="<?=$operacion->id_ejecutivo?>">
			<input type="hidden" name="comision" id="comision" value="">
			<input type="hidden" name="fecha_pago_comision" id="fecha_pago_comision" value="">
			<input type="hidden" name="monto_argenta" id="monto_argenta" value="">
			<input type="hidden" name="observaciones_comision" id="observaciones_comision" value="">
            <div class="row">
                <div class="col-md-2 labelCustom">
                	Fecha registro:
                	<div class="form-control" style="" disabled="disabled"><?=($operacion->fecha != ""?$operacion->fecha:date("Y-m-d"))?></div>
                </div>
                <div class="col-md-2 labelCustom">
                	Fecha operación:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_operacion", "fecha_operacion", 1, $operacion->fecha_operacion, "form-control", 50, "", "", "");
					?>
					</div>
				</div>
				<div class="col-md-4 labelCustom">
					Emisor:
					<div class="form-control">
					<?php
						echo "<b>".$_SESSION["tercero"]."</b>";
						echo "<input type='hidden' value='".$_SESSION["id_tercero"]."' name='id_emisor' id='id_emisor'>";
					?>
					<?php
						if ($idOperacion == 0)
						{
					?>
						<script>
							cargarInfoEmisor(<?=$_SESSION["id_tercero"]?>);
						</script>
					<?php
						}
					?>
					<br/>
					<span id="text_pagare" class="label" style="display:none;padding:3px;">Pagaré</span>
					<span id="text_resfac" class="label" style="display:none;padding:3px;">Res. Fac.</span>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
                <div class="col-md-4 labelCustom">
					Pagador:
					<div class="">
					<?php
						if ($operacion->estado == 1 || $operacion->estado == 2 || $operacion->estado == 5 || $operacion->estado == 6){
							echo "<div class='form-control'>";
							echo $arrPagadores[$operacion->id_pagador];
							echo "<input type='hidden' value='".$operacion->id_pagador."' name='id_pagador'>";
							echo "</div>";
						}
						else{
							$sede_select = new Select("id_pagador","Tercero",$arrPagadoresActivos,"",1,"", "form-control", 0, "", "traerCondiciones('pagador');", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = $operacion->id_pagador;
							echo $sede_select->genCode();
						}
					?>
					Obtener condiciones: <a href="javascript:;" onclick="traerCondiciones('pagador');"><i class="fa fa-reply fa-lg"></i></a>
					</div>
                </div>
				<div class="col-md-1 labelCustom">
					% Descuento:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("porcentaje_descuento", "porcentaje_descuento", 1, $operacion->porcentaje_descuento, "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
					</div>
				</div>
				<div class="col-md-1 labelCustom">
					% Factor:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("factor", "factor", 1, $operacion->factor, "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="col-md-6 labelCustom">
					Observaciones generales:
					<div class="">
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones", "Observaciones", 1, "", "form-control", 60, 2);
						echo "<a href=\"javascript:;\" onclick=\"$('#txt_observaciones').toggle();\" title='Ver/Ocultar historia'><small>Ver/Ocultar historia</small></a>";
						echo "<div id='txt_observaciones' style='display:none;'>".$operacion->observaciones."</div>";
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>

                <?php
                    if ($operacion->estado == 4 || $idOperacion == 0){
                ?>
                    <input type="button" value="Guardar" class="btn btn-primary datosOperacion_btnSave" onclick="saveOperacion();">
                <?php
                    }
                ?>
                <?php
                    if ($operacion->estado == 4){
                ?>
                    <input type="button" value="Enviar a validación" class="btn btn-success datosOperacion_btnSave" onclick="enviarAValidacion();">
                <?php
                    }
                ?>
            </center>
            <div class="row" style="height:10px;">&nbsp;</div>
        </form>
        <?php
		    if ($idOperacion != 0){
		?>
			<div role="tabpanel" class="tabbable" id="tabs_operacion">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="tab_custom"><a href="#facturas_operacion" onclick="cargarFacturas();" aria-controls="facturas_operacion" role="tab" data-toggle="tab" >Facturas</a></li>
					<li role="presentation" class="tab_custom"><a href="#desembolsos_operacion" onclick="cargarDesembolsos();" aria-controls="desembolsos_operacion" role="tab" data-toggle="tab">Desembolsos</a></li>
					<li role="presentation" class="tab_custom"><a href="#reliquidaciones_operacion" onclick="cargarReliquidaciones();" aria-controls="reliquidaciones_operacion" role="tab" data-toggle="tab">Re-Liquidaciones</a></li>
					<li role="presentation" class="tab_custom"><a href="#reportes" onclick="cargarReportes();" aria-controls="reportes" role="tab" data-toggle="tab">Reporte</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane" id="facturas_operacion"></div>
					<div role="tabpanel" class="tab-pane" id="desembolsos_operacion"></div>
					<div role="tabpanel" class="tab-pane" id="reliquidaciones_operacion"></div>
					<div role="tabpanel" class="tab-pane" id="reportes"></div>
				</div>
			</div>
        <?php
        	}
        ?>
</div>
<?php
    if ($idOperacion != 0){
?>
    <script>
        $(document).ready(function () {
            cargarSelect('mod=clientes&action=cargarPagadoresDependientes', <?=$operacion->id_emisor?>, 'id_pagador', '<?=$operacion->id_pagador?>', '');
            $("#factor").attr("readonly","readonly");
            $("#porcentaje_descuento").attr("readonly","readonly");
        });
    </script>
<?php
}
?>

<?php
    if ($idOperacion != 0 && ($operacion->estado == 1 || $operacion->estado == 2)){
?>
    <script>
        $(document).ready(function () {
            formReadonly('datosOperacion');
        });
    </script>
<?php
}
?>


