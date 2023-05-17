<script>

$(document).ready(function() {

});

function cerrarTarea(idTarea){

	loader()
	var strUrl = "admindex.php";
	$.ajax({
		type: 'POST',
		url: strUrl,
		dataType: "html",
		data: {
			Ajax:true,
			mod:'tareas',
			action:'tarea',
			id_tarea : idTarea
		},
		mimeType: "multipart/form-data",
		cache: false,
		success: function (response) {
			loader();
			var dialog = bootbox.dialog({
				title: "Actualizar/Cerrar tarea",
				message: response
			});
			$(".bootbox").show().addClass("show");
		}
	});

}

function verCliente(idCliente) {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, id_cliente: idCliente, mod: 'clientes', action:'client', referer:'tareas'}, function () {
        loader();
		goObjHtml("content_general", 70);
    });
}

</script>
<style>
	hr{
		margin:0px !important;
	}
</style>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Tareas pendientes por realizar</h4>
    </div>
    <br/><br/><br/>
    <div id="content_tareas" class="container-fluid " style="clear:both;padding-top:15px; background-color:#fff;">
		<table id="listTareas" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Quien asigna</th>
				<th>Cliente</th>
				<th>Tarea</th>
				<th>Tiempo</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>

			<?php
				while(!$rsTareas->EOF)
				{
					$arrDiferencia = date_diff_custom($rsTareasAsignadas->fields["fecha_proceso"], date("Y-m-d H:i:s"));
			?>
					<tr>
						<td><?=$rsTareas->fields["fecha_proceso"]?></td>
						<td><?=$rsTareas->fields["usuario_asigna"]?></td>
						<td><?=$rsTareas->fields["razon_social"]?></td>
						<td><?=$rsTareas->fields["observaciones"]?></td>
						<td><?=$arrDiferencia["d"]?></td>
						<td>
						<?php
							if ($_SESSION["profile_text"]!="Cliente")
							{
						?>
							<a href="javascript:verCliente(<?=$rsTareas->fields["id_cliente"]?>)" title="Ir a cliente"><i class="fa fa-search"></i></a>
						<?php
							}
						?>
						<a href="javascript:cerrarTarea(<?=$rsTareas->fields["id_cliente_seguimiento"]?>);" title="Cerrar tarea"><i class="fa fa-times-circle text-danger"></i></a>
						</td>
					</tr>
			<?php
					$rsTareas->MoveNext();
				}
			?>
		</tbody>
		</table>
    </div>
</div>
<?php
	if ($_SESSION["profile_text"] != "Cliente"){
?>
<br/>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Tareas pendientes asignadas</h4>
    </div>
    <br/><br/><br/>
    <div id="content_tareas" class="container-fluid " style="clear:both;padding-top:15px; background-color:#fff;">
		<table id="listTareas" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Asignado a</th>
				<th>Cliente</th>
				<th>Tarea</th>
				<th>Tiempo</th>
			</tr>
		</thead>
		<tbody>

			<?php
				while(!$rsTareasAsignadas->EOF)
				{
					$arrDiferencia = date_diff_custom($rsTareasAsignadas->fields["fecha_proceso"], date("Y-m-d H:i:s"));
			?>
					<tr>
						<td><?=$rsTareasAsignadas->fields["fecha_proceso"]?></td>
						<td><?=$rsTareasAsignadas->fields["usuario_responsable"]?></td>
						<td><?=$rsTareasAsignadas->fields["razon_social"]?></td>
						<td><?=$rsTareasAsignadas->fields["observaciones"]?></td>
						<td><?=$arrDiferencia["d"]?></td>
					</tr>
			<?php
					$rsTareasAsignadas->MoveNext();
				}
			?>
		</tbody>
		</table>
    </div>
</div>
<?php
	}
?>



