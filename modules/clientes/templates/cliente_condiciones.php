<script type="text/javascript">

$(document).ready(function(){
	$('.fechas').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveCondicion(){

    validateForm("datosRegistroCondicion");

    if ($("#datosRegistroCondicion").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroCondicion"));

		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data: dataForm,
			mimeType: "multipart/form-data",
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				closeNotify();
				if (response.Success){
					showSuccess("Transacción exitosa. Espere por favor...");
					cargarInfoAdicional();
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function saveResolucion(){

    validateForm("datosResolucion");

    if ($("#datosResolucion").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosResolucion"));

		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data: dataForm,
			mimeType: "multipart/form-data",
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				closeNotify();
				if (response.Success){
					showSuccess("Transacción exitosa. Espere por favor...");
					cargarInfoAdicional();
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function saveResolucion2(){

    validateForm("datosResolucion2");

    if ($("#datosResolucion2").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosResolucion2"));

		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data: dataForm,
			mimeType: "multipart/form-data",
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				closeNotify();
				if (response.Success){
					showSuccess("Transacción exitosa. Espere por favor...");
					cargarInfoAdicional();
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

</script>
<style>
	.pagador{
		display:none;
	}

    .select_multiple{
        height:250px !important;
    }

</style>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de parámetros del tercero</h4>
    </div>
</div>
<div class="row col-md-12" style="height:10px;">&nbsp;</div>
<div class="panel panel-primary col-md-offset-2 col-md-9" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        Registro de información de condiciones
        <hr/>
        <form id="datosRegistroCondicion" method="post" name="datosRegistroCondicion" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveCondiciones" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
			<div class="row">
				<div class="col-md-2">
					<div class="">% Descuento:</div>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("porcentaje_descuento", "porcentaje_descuento", 1, $this->porcentaje_descuento, "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
				</div>

				<div class="col-md-2">
					<div class="">% Factor:</div>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("factor", "factor", 1, $this->factor, "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
				</div>

            	<div class="col-md-4">
            		<div class="">Plazo(días):</div>
					<div id="divRadiodias" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrDias = array("30"=>"30","60"=>"60","90"=>"90","120"=>"120");
						$c_radio->Radio("dias","dias",$arrDias,"", 1, $this->plazo, "", 0, "customValidateRadio('dias');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
				</div>
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
				<div class="col-md-8">
					<div class="">Observaciones:</div>
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones_condiciones", "observaciones_condiciones", 1, "", "form-control", 60, 3);
						echo "<a href=\"javascript:;\" onclick=\"$('#txt_observaciones').toggle();\" title='Ver/Ocultar historial'><small>Ver/Ocultar historial</small></a>";
						echo "<div id='txt_observaciones' style='display:none;'>".$this->observaciones."</div>";
					?>
				</div>
			</div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_cliente_btnSave" onclick="saveCondicion();">
            </center>
    </div>
</div>
<div class="panel panel-primary col-md-offset-2 col-md-9" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        Registro de información de resolución de facturación 1
        <hr class="separador_titulo"/>
        <form id="datosResolucion" method="post" name="datosResolucion" action="index.php">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveResolucion" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
        <input type="hidden" name="id_resolucion" id="id_resolucion" value="<?=$idResolucion?>" />
        <input type="hidden" name="registro" id="registro" value="1" />
        <div class="row row-form">
			<div class="col-md-4">
				Resolución:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("resolucion", "resolucion", 1, $resolucion->resolucion, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Prefijo:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("prefijo", "prefijo", 1, $resolucion->prefijo, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
		</div>
		<div class="row col-md-12" style="height:10px;">&nbsp;</div>
		<div class="row row-form">
			<div class="col-md-2">
				Fecha inicial:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_inicial", "fecha_inicial", 1, $resolucion->fecha_inicial, "form-control fechas", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Fecha final:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_final", "fecha_final", 1, $resolucion->fecha_final, "form-control fechas", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Factura inicial:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fac_inicial", "fac_inicial", 1, $resolucion->fac_inicial, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Factura final:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fac_final", "fac_final", 1, $resolucion->fac_final, "form-control ", 0, 0, "","","","");
				?>
				</div>
			</div>
		</div>
		<div class="row col-md-12" style="height:10px;">&nbsp;</div>
		</form>
		<center>
				<input type="button" class="btn btn-primary" onclick="saveResolucion();" value="Guardar"/>
		</center>
	</div>
</div>
<div class="panel panel-primary col-md-offset-2 col-md-9" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        Registro de información de resolución de facturación 2
        <hr class="separador_titulo"/>
        <form id="datosResolucion2" method="post" name="datosResolucion2" action="index.php">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveResolucion" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
        <input type="hidden" name="id_resolucion" id="id_resolucion" value="<?=$idResolucion2?>" />
        <input type="hidden" name="registro" id="registro" value="2" />
        <div class="row row-form">
			<div class="col-md-4">
				Resolución:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("resolucion", "resolucion", 1, $resolucion2->resolucion, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Prefijo:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("prefijo", "prefijo", 1, $resolucion2->prefijo, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
		</div>
		<div class="row col-md-12" style="height:10px;">&nbsp;</div>
		<div class="row row-form">
			<div class="col-md-2">
				Fecha inicial:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_inicial", "fecha_inicial", 1, $resolucion2->fecha_inicial, "form-control fechas", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Fecha final:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_final", "fecha_final", 1, $resolucion2->fecha_final, "form-control fechas", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Factura inicial:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fac_inicial", "fac_inicial", 1, $resolucion2->fac_inicial, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
			<div class="col-md-2">
				Factura final:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fac_final", "fac_final", 1, $resolucion2->fac_final, "form-control", 0, 0, "","","","");
				?>
				</div>
			</div>
		</div>
		<div class="row col-md-12" style="height:10px;">&nbsp;</div>
		</form>
		<center>
				<input type="button" class="btn btn-primary" onclick="saveResolucion2();" value="Guardar"/>
		</center>
	</div>
</div>

