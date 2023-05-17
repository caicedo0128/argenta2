<script type="text/javascript">

$(document).ready(function(){

});

function cerrarTarea(){

    validateForm("datosRegistroTarea");

    if ($("#datosRegistroTarea").valid()){

		$("#datosRegistroTarea :input[name=action]").val("cerrarTarea");
        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroTarea"));

		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data: dataForm,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				closeNotify();
				if (response.Success){
					showSuccess("Transacción exitosa. Espere por favor...");
					window.setTimeout(function(){
						window.location.href="admindex.php";
					},1000);
					closeBootbox();
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function actualizarTarea(){

    validateForm("datosRegistroTarea");

    if ($("#datosRegistroTarea").valid()){

		$("#datosRegistroTarea :input[name=action]").val("ActualizarTarea");
        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroTarea"));

		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data: dataForm,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				closeNotify();
				if (response.Success){
					showSuccess("Transacción exitosa. Espere por favor...");
					window.setTimeout(function(){
						window.location.href="admindex.php";
					},1000);
					closeBootbox();
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
<div class="panel panel-primary" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        <form id="datosRegistroTarea" method="post" name="datosRegistroTarea" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="tareas" />
        <input type="hidden" name="action" id="action" value="cerrarTarea" />
        <input type="hidden" name="id_tarea" id="id_tarea" value="<?=$idTarea?>" />
			<div class="row">
				<div class="col-md-12">
					Tarea:
					<div>
						<?=$tarea->observaciones?>
					</div>
				</div>
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
				<div class="col-md-12">
					Comentarios / tarea:
					<div class="">
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones", "observaciones", 1, "", "form-control", 60, 3);
					?>
					</div>
				</div>
			</div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Cerrar Tarea" class="btn btn-primary datos_tarea_btnSave" onclick="cerrarTarea();">
                <input type="button" value="Actualizar Comentarios" class="btn btn-warning datos_tarea_btnSave" onclick="actualizarTarea();">
            </center>
    </div>
</div>

