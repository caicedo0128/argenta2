<script>
$(document).ready(function() {

});

function agregarSeguimiento() {
    loader();
    $("#content_seguimiento").load('admindex.php', { Ajax:true, id_cliente: <?=$idCliente?>, mod: 'clientes', action:'seguimiento'}, function () {
        loader();
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
        <h4>Informaci�n de seguimiento</h4>
    </div>
</div>
<div id="content_seguimiento" class="" style="clear:both;padding-top:15px;">
	<div style="height: 40px;" class="row-fluid">
		<div class="agregar_registro text-right">
			<?
				if ($appObj->tienePermisosAccion(array("seguimiento_terceros")))
				{
					//Opcion a ejecutar si tiene el permiso
					echo "<a class='btn btn-primary btn-sm' href='javascript:agregarSeguimiento();'><i class='fa fa-plus-square fa-lg'></i> Agregar tarea / seguimiento</a>";
				}
			?>
		</div>
	</div>
	<table id="tableDataSeguimiento" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Usuario</th>
			<th>Observaciones</th>
			<th>Asignado a</th>
			<th>Fecha respuesta</th>
			<th>Tarea</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>

		<?php
			while(!$rsSeguimiento->EOF)
			{
		?>
				<tr>
					<td><?=$rsSeguimiento->fields["fecha_proceso"]?></td>
					<td>
					<?php
							if ($rsSeguimiento->fields["id_usuario"] == -1)
								echo "USUARIO EXTERNO";
							else
								echo $rsSeguimiento->fields["usuario_procesa"];
					?>
					</td>
					<td><?=$rsSeguimiento->fields["observaciones"]?></td>
					<td><?=($rsSeguimiento->fields["usuario_asignado"]!=""?$rsSeguimiento->fields["usuario_asignado"]:"N/A")?></td>
					<td>
						<?php
							if ($rsSeguimiento->fields["es_tarea"] == 1){

								echo ($rsSeguimiento->fields["fecha_respuesta"]==""?"Sin respuesta":$rsSeguimiento->fields["fecha_respuesta"]);
							}
							else{
								echo "N/A";
							}
						?>
					</td>
					<td><?=($rsSeguimiento->fields["es_tarea"]==1?"Si":"No")?></td>
					<td><?=$this->arrEstadosCliente[$rsSeguimiento->fields["id_estado"]]?></td>
				</tr>
		<?php
				$rsSeguimiento->MoveNext();
			}
		?>
	</tbody>
	</table>
</div>

