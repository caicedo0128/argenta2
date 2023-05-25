<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_pago_comision').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_operacion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
	$("#id_emisor").select2({ placeholder: 'Seleccione uno...',allowClear: true});
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

function actualizarOperacion(){

    validateForm("datosOperacion");

    if ($("#datosOperacion").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        $("#datosOperacion input[id=action]").val("updateOperacion");

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
                    editOperacion(response.IdOperacion);
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function cerrarOperacion(){

	bootbox.confirm('La operaci�n se va a cerrar.<br/><br/>Desea continuar?.', function (result) {

		if (result) {
			showLoading("Cerrando operaci�n. Espere por favor...");
			var idOperacion = $("#id_operacion").val();
			var dataForm = "Ajax=true&mod=operaciones&action=cerrarOperacion&idOperacion=" + idOperacion;
			var strUrl = "admindex.php";
			$.ajax({
					type: 'POST',
					url: strUrl,
					dataType: "json",
					data:dataForm,
					success: function (response) {
						closeNotify();
						if (response.Success) {
							showSuccess("Transacci�n exitosa. Espere por favor...");
							editOperacion(response.IdOperacion);
						}
						else{
							showError(response.Message);
						}
					}
			});
		}
	});
}

function abrirOperacion(){
	bootbox.confirm('La operaci�n se va a re-abrir.<br/><br/>Desea continuar?.', function (result) {

		if (result) {
			showLoading("Abriendo operaci�n. Espere por favor...");
			var idOperacion = $("#id_operacion").val();
			var dataForm = "Ajax=true&mod=operaciones&action=abrirOperacion&idOperacion=" + idOperacion;
			var strUrl = "admindex.php";
			$.ajax({
					type: 'POST',
					url: strUrl,
					dataType: "json",
					data:dataForm,
					success: function (response) {
						closeNotify();
						if (response.Success) {
							showSuccess("Transacci�n exitosa. Espere por favor...");
							editOperacion(response.IdOperacion);
						}
						else{
							showError(response.Message);
						}
					}
			});
		}
	});
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

function cargarInversionistas() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#inversionistas_operacion").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'listInversionistas', id_operacion : idOperacion}, function () {
        loader();
        $("#desembolsos_operacion").removeClass("active");
        $("#reporte_cliente").removeClass("active");
        $("#reporte_inversionista").removeClass("active");
        $("#reliquidaciones_operacion").removeClass("active");
        $("#reportes").removeClass("active");
        $("#facturas_operacion").removeClass("active");
        $("#seguimiento").removeClass("active");
        $("#inversionistas_operacion").addClass("active");
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

function reporteInversionista() {

    var idOperacion = $("#id_operacion").val();
    loader();
    $("#reporte_inversionista").load('admindex.php', { Ajax:true, mod: 'operaciones', action:'reporteInversionista', id_operacion : idOperacion}, function () {
        loader();
        $("#reporte_inversionista").addClass("active");
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
	var idCliente = $("#datosOperacion select[name=id_emisor]").val();
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

                        if (response.DiasVigenciaPagare != null){

                        	var msjPagare = "";
                        	var classPagare = "";
							if (response.DiasVigenciaPagare > 30){
								msjPagare = "Pagar� vigente";
								classPagare = "label-success";
							}
							else if (response.DiasVigenciaPagare >= 1 && response.DiasVigenciaPagare <= 30){
								msjPagare = "Pagar� pr�ximo a vencer - " + response.DiasVigenciaPagare;
								classPagare = "label-warning";
							}
							else if (response.DiasVigenciaPagare <= 0){
								msjPagare = "Pagar� vencido - " + response.DiasVigenciaPagare;
        						classPagare = "label-danger";
        						formReadonly("datosOperacion");
        						showError("El pagar� est� vencido. Por favor verifique.");
							}

                        	$("#text_pagare").text(msjPagare).addClass(classPagare).show();
                        	if (classPagare == "label-danger")
                        		$("#refrescar").show();
                        }

						if (response.DiasVigenciaResFac != null){

                        	var msjResFac = "";
                        	var classResFac = "";
							if (response.DiasVigenciaResFac > 15){
								msjResFac = "Res. fac. vigente";
								classResFac = "label-success";
							}
							else if (response.DiasVigenciaResFac >= 1 && response.DiasVigenciaResFac <= 15){
								msjResFac = "Res. fac. pr�xima a vencer - " + response.DiasVigenciaResFac;
								classResFac = "label-warning";
							}
							else if (response.DiasVigenciaResFac <= 0 && response.DiasVigenciaResFac != -9999){
								msjResFac = "Res. fac. vencida - " + response.DiasVigenciaResFac;
        						classResFac = "label-danger";
        						formReadonly("datosOperacion");
        						showError("Las resoluci�n de facturaci�na est� vencida. Por favor verifique.");
							}
							else if (response.DiasVigenciaResFac <= -9999){
								msjResFac = "Res. fac. sin cargar o aprobar";
								classResFac = "label-danger";
								formReadonly("datosOperacion");
								showError("Las resoluci�n de facturaci�na no ha sido cargada o no est� aprobada. Por favor verifique.");
							}

                        	$("#text_resfac").text(msjResFac).addClass(classResFac).show();
                        	if (classResFac == "label-danger")
                        		$("#refrescar").show();
                        }
                    }
				}
			}
	});

}

function actualizarOperacionVigente(){

	validateForm("datosOperacion");

	if ($("#datosOperacion").valid()){

		bootbox.confirm('La operaci�n se va a procesar como VIGENTE.<br/><br/>El proceso no se podr� deshacer.<br/><br/>Desea continuar?.', function (result) {

			if (result) {

				showLoading("Enviando informacion. Espere por favor...");

				$("#datosOperacion input[id=action]").val("operacionVigente");

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
								showSuccess("Transacci�n exitosa. Espere por favor...");
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

function cambiarGMFManual(){

	bootbox.prompt({
		title: "Confirmaci�n",
		message: "Actualizar GMF manual.<br/><br/>Realmente desea continuar?<br/><br/>GMF manual:",
		closeButton: false,
		inputType: 'number',
		buttons: {
			confirm: {
				label: 'Si',
				className: 'btn-primary'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
		},
		callback: function (result) {

			var valorGMF = $(".bootbox-input-number").val();
			var idOperacion = $("#id_operacion").val();

			if (result === null) {
				closeBootbox();
				return;
			} else if (result === '') {
				showError('Debe ingresar un n�mero inicial para completar el proceso');
				return false;
			}
			else if (result){
				showLoading("Enviando informaci�n. Espere por favor...");
				var strUrl = "admindex.php";
				$.ajax({
					type: 'POST',
					url: strUrl,
					dataType: "json",
					data: {
						Ajax:true,
						mod:'operaciones',
						action:'actualizarGMF',
						id_operacion:idOperacion,
						valor:valorGMF
					},
					mimeType: "multipart/form-data",
					cache: false,
					success: function (response) {
						closeNotify();
						closeBootbox();
						showSuccess("Transacci�n exitosa. Espere por favor...");
						editOperacion(idOperacion);
					}
				});
			}
		}
	});
	$(".bootbox-prompt").addClass("show").show();

}

</script>
<div class="panel panel-primary">
    <div class="panel-body">
        Registro de informacion de operacion
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
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />
            <div class="row">
                <div class="col-md-2 labelCustom">
                	Fecha registro:
                	<div class="form-control" style="" disabled="disabled"><?=($operacion->fecha != ""?$operacion->fecha:date("Y-m-d"))?></div>
                </div>
                <div class="col-md-2 labelCustom">
                	Fecha operaci�n:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_operacion", "fecha_operacion", 1, $operacion->fecha_operacion, "form-control", 50, "", "", "");
					?>
					</div>
				</div>
				<div class="col-md-4 labelCustom">
					Emisor:
					<div class="">
					<?php
						if ($operacion->estado == 1){
							echo $arrEmisores[$operacion->id_emisor];
							echo "<input type='hidden' value='".$operacion->id_emisor."' name='id_emisor'>";
						}
						else{
							$sede_select = new Select("id_emisor","Tercero",$arrEmisoresActivos,"",1,"", "form-control", 0, "", "cargarInfoEmisor(this.value);", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = $operacion->id_emisor;
							echo $sede_select->genCode();
						}
					?>
					Obtener condiciones: <a href="javascript:;" onclick="traerCondiciones('emisor');"><i class="fa fa-reply fa-lg"></i></a>
					<div>
						<span id="text_pagare" class="label " style="display:none;padding:2px;width:37%;float:left;margin-top:1px;">Pagar�</span>
						<span id="text_resfac" class="label "style="display:none;padding:2px;width:43%;float:left;margin-left:1px;margin-top:1px;">Res. Fac.</span>
						<span id="refrescar" onclick="editOperacion(0);" class="label label-info" style="display:none;padding:2px;width:15%;float:left;margin-left:1px;margin-top:1px;cursor:pointer;">Recargar</span>
					</div>
					</div>
                </div>
                <div class="col-md-4 labelCustom">
					Pagador:
					<div class="">
					<?php
						if ($operacion->estado == 1){
							echo $arrPagadores[$operacion->id_pagador];
							echo "<input type='hidden' value='".$operacion->id_pagador."' name='id_pagador'>";
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
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <fieldset class="well">
            	<legend>Opciones para gestion del proceso:</legend>
            	<input type="hidden" name="fecha_pago_operacion" id="fecha_pago_operacion" value="">
            	<input type="hidden" name="fecha_vencimiento" id="fecha_vencimiento" value="">
            	<input type="hidden" name="descripcion_otros" id="descripcion_otros" value="">
                <div class="row">
					<div class="col-md-2 labelCustom">
						Tipo operacion:
						<div id="divRadiotipo_operacion" class="radioValidate">
						<?php
							$c_radio = new Radio;
							$arrTipo = array("1"=>"Real","2"=>"Simulaci�n");
							$c_radio->Radio("tipo_operacion","tipo_operacion",$arrTipo,"", 1, $operacion->tipo_operacion, "", 0, "customValidateRadio('tipo_operacion');");
							while($tmp_html = $c_radio->next_entry()) {
								echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
							}
						?>
						</div>
					</div>
					<div class="col-md-2 labelCustom">
						Aplica 4xmil:
						<div class="">
							<div id="divRadioaplica_impuesto" class="radioValidate">
							<?php

								$eventoFacturas = "";
								if ($rsFacturas->_numOfRows>0)
									$eventoFacturas = "showError('<b>IMPORTANTE:</b> Este cambio requiere que ingrese manualmente a cada una de las facturas y actualice la informaci�n.<br/>Verifique que haya guardado el cambio en la operaci�n',5000);";

								$c_radio = new Radio;
								$arrSiNo = array("1"=>"Si","2"=>"No");
								$c_radio->Radio("aplica_impuesto","aplica_impuesto",$arrSiNo,"", 1, $operacion->aplica_impuesto, "", 0, $eventoFacturas."customValidateRadio('aplica_impuesto');");
								while($tmp_html = $c_radio->next_entry()) {
									echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
								}
							?>
							</div>
						</div>
					</div>
                    <div class="col-md-2 labelCustom">
						% Descuento:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("porcentaje_descuento", "porcentaje_descuento", 1, $operacion->porcentaje_descuento, "form-control number", 50, "7", "", "","","return IsNumber(event);");
						?>
						</div>
                    </div>
                    <div class="col-md-2 labelCustom">
						% Tasa inversionista:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("tasa_inversionista", "tasa_inversionista", 1, $operacion->tasa_inversionista, "form-control number", 50, "7", "", "","","return IsNumber(event);");
						?>
						</div>
                    </div>
                    <div class="col-md-2 labelCustom">
						% Factor:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("factor", "factor", 1, $operacion->factor, "form-control number", 50, "7", "", "","","return IsNumber(event);");
						?>
						</div>
                    </div>
                    <div class="col-md-2 labelCustom">
						Costo transferencia:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("valor_otros_operacion", "otros", 1, $operacion->valor_otros_operacion, "form-control number", 50, "", "", "","","return IsNumber(event);");
						?>
						</div>
                    </div>
                </div>
                <div class="row" style="height:10px;">&nbsp;</div>
                <div class="row">
                    <div class="col-md-4 labelCustom">
						Ejecutivo:
						<div class="">
						<?php
							$ejecutivo_select = new Select("id_ejecutivo","id_ejecutivo",$arrEjecutivos,"",1,"", "form-control", 0, "", "", 0);
							$ejecutivo_select->enableBlankOption();
							$ejecutivo_select->Default = $operacion->id_ejecutivo;
							echo $ejecutivo_select->genCode();
						?>
						</div>
                    </div>
                    <div class="col-md-2 labelCustom">
						Comisi�n:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("comision", "comision", 1, $operacion->comision, "form-control number", 50, "7", "", "","","return IsNumber(event);");
						?>
						</div>
                    </div>
                    <div class="col-md-2 labelCustom">
						Fecha pago comisi�n:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("fecha_pago_comision", "fecha_pago_comision", 0, $operacion->fecha_pago_comision, "form-control", 50, "", "", "");
						?>
						</div>
                    </div>
                    <div class="col-md-2 labelCustom">
						Monto Argenta:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("monto_argenta", "monto_argenta", 0, $operacion->monto_argenta, "form-control number", 50, "", "", "","","return IsNumber(event);");
						?>
						</div>
                    </div>
                </div>
				<div class="row" style="height:10px;">&nbsp;</div>
                <div class="row">
                    <div class="col-md-6 labelCustom">
						Observaciones comisi�n:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox("observaciones_comision", "Observaciones", 0, $operacion->observaciones_comision, "form-control", 60, 3);
						?>
						</div>
                    </div>
                </div>
            </fieldset>
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
                    //if ($operacion->estado == 3 || $operacion->estado == 6 || $idOperacion == 0){
                ?>
                    <input type="button" value="Guardar" class="btn btn-primary datosOperacion_btnSave" onclick="saveOperacion();">
                <?php
                    //}
                ?>
                <?php
                   // if ($operacion->estado == 3 || $operacion->estado == 6){
					if ($appObj->tienePermisosAccion(array("pasarVigente_operacion")))
        					{
							//Opcion a ejecutar si tiene el permiso
							echo "<input id='btn_operacion_vigente' type='button' value='Pasar a vigente' class='btn btn-warning datosActualizarOperacionVigente_btnSave' onclick='actualizarOperacionVigente();'>";
        					}
                ?>
                <?php
                   // }
                ?>
                <?php
                   // if ($operacion->estado == 1){
					if ($appObj->tienePermisosAccion(array("actualizar_operaciones")))
        					{
							//Opcion a ejecutar si tiene el permiso
							echo "<input id='btn_actualizar_operacion' type='button' value='Actualizar operacion' class='btn btn-warning datosActualizarOperacion_btnSave' onclick='actualizarOperacion();'>";
							echo "<input id='btn_cerrar_operacion' type='button' value='Cerrar operacion' class='btn btn-danger datosCerrarOperacion_btnSave' onclick='cerrarOperacion();'>";
        					}
                ?>
                <?php
                    //}
                ?>

                <?php
                    if ($operacion->estado == 2){
                ?>
                    <input id="btn_abrir_operacion" type="button" value="Re-abrir operaci�n" class="btn btn-success datosAbrirOperacion_btnSave" onclick="abrirOperacion();">
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
					<!--li role="presentation" class="tab_custom"><a href="#inversionistas_operacion" onclick="cargarInversionistas();" aria-controls="inversionistas_operacion" role="tab" data-toggle="tab">Inversionistas</a></li-->
					<li role="presentation" class="tab_custom"><a href="#facturas_operacion" onclick="cargarFacturas();" aria-controls="facturas_operacion" role="tab" data-toggle="tab" >Facturas</a></li>
					<li role="presentation" class="tab_custom"><a href="#desembolsos_operacion" onclick="cargarDesembolsos();" aria-controls="desembolsos_operacion" role="tab" data-toggle="tab">Desembolsos</a></li>
					<!--li role="presentation" class="tab_custom"><a href="#reporte_cliente" onclick="reporteCliente();" aria-controls="reporte_cliente" role="tab" data-toggle="tab">Cliente</a></li-->
					<!--li role="presentation" class="tab_custom"><a href="#reporte_inversionista" onclick="reporteInversionista();" aria-controls="reporte_inversionista" role="tab" data-toggle="tab">Inversionista</a></li-->
					<li role="presentation" class="tab_custom"><a href="#reliquidaciones_operacion" onclick="cargarReliquidaciones();" aria-controls="reliquidaciones_operacion" role="tab" data-toggle="tab">Re-Liquidaciones</a></li>
					<li role="presentation" class="tab_custom"><a href="#reportes" onclick="cargarReportes();" aria-controls="reportes" role="tab" data-toggle="tab">Reportes</a></li>
					<?
					if ($appObj->tienePermisosAccion(array("seguimiento_operaciones")))
        					{
							//Opcion a ejecutar si tiene el permiso
							echo "<li role='presentation' class='tab_custom'><a href='#seguimiento' onclick='cargarSeguimiento();' aria-controls='seguimiento' role='tab' data-toggle='tab'>Seguimiento</a></li>";
        					}
					?>		
				</ul>
				<div class="tab-content">
					<!--div role="tabpanel" class="tab-pane" id="inversionistas_operacion"></div-->
					<div role="tabpanel" class="tab-pane" id="facturas_operacion"></div>
					<div role="tabpanel" class="tab-pane" id="desembolsos_operacion"></div>
					<!--div role="tabpanel" class="tab-pane" id="reporte_cliente"></div-->
					<!--div role="tabpanel" class="tab-pane" id="reporte_inversionista"></div-->
					<div role="tabpanel" class="tab-pane" id="reliquidaciones_operacion"></div>
					<div role="tabpanel" class="tab-pane" id="reportes"></div>
					<div role="tabpanel" class="tab-pane" id="seguimiento"></div>
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
            //QUITARMOS EL BLOQUEO PARA OBSERVACIONES Y DATOS DE COMISION
            $("#fecha_pago_comision").removeAttr("readonly");
            $("#observaciones").removeAttr("readonly");
            $("#id_ejecutivo").removeAttr("disabled");
            $("#comision").removeAttr("readonly");
            $("#observaciones_comision").removeAttr("readonly");
        });
    </script>
<?php
}
?>


