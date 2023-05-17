<script type="text/javascript">

$(document).ready(function(){

});

function saveSeguimiento(){

    validateForm("datosRegistroTarea");

    if ($("#datosRegistroTarea").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroTarea"));

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
					cargarInfoSeguimiento();
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

</style>

<div class="row col-md-12" style="height:10px;">&nbsp;</div>
<div class="panel panel-primary col-md-offset-2 col-md-9" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        Registro de información de seguimiento / tarea
        <div class="cerrar_form" onclick="cargarInfoSeguimiento();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr/>
        <form id="datosRegistroTarea" method="post" name="datosRegistroTarea" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveSeguimiento" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
		<div class="row" style="height:10px;">&nbsp;</div>
		<div class="row">
			<div class="col-md-4">
				Asignado a:
				<div>
					<?php

						$sede_select = new Select("id_usuario_responsable","id_usuario_responsable",$arrUsuariosEmpleados,"",0,"", "form-control", 0, "", "", 0);
						$sede_select->enableBlankOption();
						echo $sede_select->genCode();
					?>
				</div>
			</div>
		</div>
		<div class="row" style="height:10px;">&nbsp;</div>
		<div class="row">
			<div class="col-md-8">
				Comentarios / tarea / seguimiento:
				<div class="">
				<?php
					$c_textarea = new Textarea;
					echo $c_textarea->Textarea("observaciones_seguimiento", "observaciones_seguimiento", 1, "", "form-control", 60, 3);
				?>
				</div>
			</div>
		</div>
		</form>
		<div class="row" style="height:10px;">&nbsp;</div>
		<center>
			<input type="button" value="Guardar" class="btn btn-primary datos_tarea_btnSave" onclick="saveSeguimiento();">
		</center>
    </div>
</div>

