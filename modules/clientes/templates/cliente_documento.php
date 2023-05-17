<script type="text/javascript">

$(document).ready(function(){
	//formReadonly("datosRegistroEditarVinculacion");
	$('#fecha_vencimiento').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveDocumento(){

    validateForm("datosRegistroDocumento");

    if ($("#datosRegistroDocumento").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroDocumento"));

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
					cargarInfoDocumentos();
				}
				else{
					showError(response.Message, 5000);
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function validarArchivo(obj, idObj) {

	if ($(obj).val() != "") {
		var fileInput = $(obj).get(0).files[0];

		var fileSize = $(obj)[0].files[0].size;
		var siezekiloByte = parseInt(fileSize / 1024);
		var tamanoPermitido = $(obj).attr('size');
		var tamanoPermitidoMegas = Math.round(parseFloat(tamanoPermitido / 1024));
		if (siezekiloByte > tamanoPermitido) {
			showError("El archivo soprepasa el peso permitido. (" + tamanoPermitidoMegas + " MB). Seleccione otro archivo.", 4500);
			$(obj).val("");
		}
	}
}

</script>
<style>
	.pagador{
		display:none;
	}
</style>
<div class="panel panel-primary col-md-offset-2 col-md-9">
    <div class="panel-body panel-custom-interno">
        Registro de información de documento
        <div class="cerrar_form" onclick="cargarInfoDocumentos();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />

        <form id="datosRegistroDocumento" method="post" name="datosRegistroDocumento" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveDocumento" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
        <input type="hidden" name="id_documento" id="id_documento" value="<?=$clienteDocumento->id_cliente_documento?>" />
            <div class="row">
                <div class="col-md-6">
                	<div>Tipo documento:</div>
					<?php
						$sede_select = new Select("id_tipo_documento","id_tipo_documento",$arrTiposDocumento,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $clienteDocumento->id_tipo_documento;
						echo $sede_select->genCode();
					?>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3">
                	<div>Año</div>
					<?php
						$sede_select = new Select("ano","ano",$arrAños,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $clienteDocumento->año;
						echo $sede_select->genCode();
					?>
                </div>
                <div class="col-md-3">
                	<div>Periodo:</div>
					<div id="divRadioperiodo" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrPeriodo = array("1"=>"1","2"=>"2");
						$c_radio->Radio("periodo","periodo",$arrPeriodo,"", 1, $clienteDocumento->periodo, "", 0, "customValidateRadio('periodo');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
            	</div>
                <div class="col-md-3">
                	<div>Fecha vencimiento:</div>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_vencimiento", "fecha_vencimiento", 0, $clienteDocumento->fecha_vencimiento, "form-control", "", "", "", "");
					?>
            	</div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">

                <div class="col-md-9">
                	<div class="">Documento:</div>
                	<?php
                		$requerido = "required";
                		if ($clienteDocumento->id_cliente_documento != 0){
                			$requerido = "";
                		}

                	?>
                	<?php
                		if ($clienteDocumento->id_cliente_documento == 0 || $clienteDocumento->id_estado == 1)
                		{
                	?>
						<input type="file" id="file_documento" name="file_documento" size="7000" class="<?=$requerido?> form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_declaracion');">
						Tamaño maximo archivo 7MB.
                	<?php
                		}
                	?>
                	<?php
                		if ($clienteDocumento->id_cliente_documento != 0){
                			echo "<a href='admindex.php?id_cliente_documento=".$clienteDocumento->id_cliente_documento."&mod=clientes&action=verDocumentoCliente&Ajax=true' target='_blank'><li class='fa fa-search'></li> Ver documento</a>";
                		}
                	?>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-9">
                	<div class="">Observaciones:</div>
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones", "Observaciones", 1, "", "form-control", 60, 2);
						echo "<a href=\"javascript:;\" onclick=\"$('#txt_observaciones').toggle();\" title='Ver/Ocultar historial'><small>Ver/Ocultar historial</small></a>";
						echo "<div id='txt_observaciones' style='display:none;'>".$clienteDocumento->observaciones."</div>";
					?>
                </div>
            </div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
			<?php
				if ($clienteDocumento->id_cliente_documento == 0 || $clienteDocumento->id_estado == 1 || $_SESSION["profile_text"]=="Administrador")
				{
			?>
				<center>
					<input type="button" value="Guardar" class="btn btn-primary datos_cliente_btnSave" onclick="saveDocumento();">
				</center>
            <?php
            	}
            ?>
            <div class="row" style="height:10px;">&nbsp;</div>
    </div>
</div>
