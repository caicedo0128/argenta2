<script type="text/javascript">

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Informacion de documentos</h4>
    </div>
</div>
<div id="content_documentos" class="" style="clear:both;padding-top:15px;">
	<div style="height: 40px;" class="row-fluid">
		<div class="agregar_registro text-right">
			<?
			if ($appObj->tienePermisosAccion(array("documentos_agregar_terceros")))
			{
				//Opcion a ejecutar si tiene el permiso
				echo "<a class='btn btn-primary btn-sm' href='javascript:editDocumento(0,'clientes','documento');'><i class='fa fa-plus-square fa-lg'></i> Agregar</a>";                                      
			}
			?>
		</div>
	</div>
	<table id="tableDataDocumentosClientes" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
	<thead>
		<tr>
			<th></th>
			<th>Fecha</th>
			<th>Documento</th>
			<th>Registrado por</th>
			<th>A�o-Semestre</th>
			<th>Usuario actualiza</th>
			<th>Vencimiento</th>
			<th>Estado</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>

		<?php
			while(!$rsDocumentos->EOF)
			{
				$idClienteDocumento = $rsDocumentos->fields["id_cliente_documento"];				
				$idEstadoDocumento = $rsDocumentos->fields["id_estado"];				
				
				$classEstado = "info";
				if ($idEstadoDocumento == 2)//APROBADO
					$classEstado = "success";
					
		?>
		
				<tr>

					<td align="center">
						<?
						if ($appObj->tienePermisosAccion(array("documentos_editar_terceros")))
						{
							//Opcion a ejecutar si tiene el permiso
							echo "<a href='javascript:editDocumento(<?=$idClienteDocumento?>,'clientes','documento');'><img border='0' alt='Editar operaci�n' title='Editar documento' src='./images/editar.png'>Editar</a>";
						}
						?>
						
						<?
							if ($_SESSION["profile_text"] != "Cliente"){	
								if ($appObj->tienePermisosAccion(array("documentos_eliminar_terceros")))
								{
									//Opcion a ejecutar si tiene el permiso
									echo "<a href='javascript:deleteDocumento(<?=$idClienteDocumento?>,'clientes','deleteDocumento',<?=$idEstadoDocumento ?)><img border='0' alt='Editar operacion' title='Eliminar documento' src='./images/eliminar.png'>Eliminar</a>";
								}	
							}
							?>
					</td>
					<td><?=$rsDocumentos->fields["fecha"]?></td>
					<td><?=$rsDocumentos->fields["tipo_documento"]?></td>
					<td><?=($rsDocumentos->fields["registro"]==1?"Web":"Interno")?></td>
					<td><?=$rsDocumentos->fields["anio"]?></td>
					<td><?=$rsDocumentos->fields["usuario"]?></td>
					<td align="center">
						<div style='margin-bottom:2px;'><?=($rsDocumentos->fields["fecha_vencimiento"]!="" && $rsDocumentos->fields["fecha_vencimiento"] != "0000-00-00"?$rsDocumentos->fields["fecha_vencimiento"]:"N/A")?></div>
						<?php
						if ($rsDocumentos->fields["fecha_vencimiento"] != "" && $rsDocumentos->fields["fecha_vencimiento"] != "0000-00-00"){
							$arrDiferenciaFechasNotificacion = date_diff_custom(date("Y-m-d"), $rsDocumentos->fields["fecha_vencimiento"]);        	
							if ($arrDiferenciaFechasNotificacion["d"] > 0)
								echo "<span class='label label-success' style='padding:4px;font-size:12px !important;'>Vigente</span>";
							else if ($arrDiferenciaFechasNotificacion["d"] <= 0)
								echo "<span class='label label-danger' style='padding:4px;font-size:12px !important;'>Vencido</span>";						
						}
						?>
					</td>									
					<td align="center">	
						<span class="label label-<?=$classEstado?>" style="padding:4px;"><?=$this->arrEstadosDocumento[$idEstadoDocumento]?></span>					
					</td>
					<td align="center">
						<a href="admindex.php?id_cliente_documento=<?=$idClienteDocumento?>&mod=clientes&action=verDocumentoCliente&Ajax=true" class="tdRegisterData" title="Ver documento" target="_blank"><i class="fa fa-search"></i></a>
						<?php
							if ($idEstadoDocumento == 1 && $_SESSION["profile_text"] != "Cliente"){
						?>
							<a href="javascript:aprobarDocumento(<?=$idClienteDocumento?>,'clientes','aprobarDocumento','true');" title="Aprobar documento"><i class="fa fa-check-circle text-success"></i></a>						
						<?php
							}
						?>
					</td>
				</tr>
		<?php 
				$rsDocumentos->MoveNext();
			}
		?>                
	</tbody>
	</table>
</div>
<script>
$(document).ready(function() {
    var oTable = $('#tableDataDocumentosClientes').dataTable({ "pagingType": "full_numbers", "bStateSave": true});  
});

function editDocumento(idDocumento, mod, action) {
    loader();
    $("#content_documentos").load('admindex.php', { Ajax:true, id_documento: idDocumento, id_cliente: <?=$idCliente?>, mod: mod, action:action}, function () {
        loader();
    });
}

function deleteDocumento(idDocumento, mod, action, idEstado) {
	
	//DETERMINAMOS SI SE PUEDE ELIMINAR EL REGISTRO
	if (idEstado == 1){
	
		bootbox.confirm({
			title: "Confirmaci�n",
			message: "Usted va a eliminar el documento. El proceso no se podra deshacer.<br/><br/>Realmente desea continuar?",
			closeButton: true,
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


				if (result === null) {
					closeBootbox();
					return;
				}
				else if (result){    

					showLoading("Enviando informacion. Espere por favor...");

					var strUrl = "admindex.php";
					var dataForm = "Ajax=true&mod=" + mod + "&action=" + action + "&id_documento=" + idDocumento;
					$.ajax({
							type: 'POST',
							url: strUrl,
							dataType: "json",
							data:dataForm,
							success: function (response) {
								closeNotify();
								showSuccess("Transacci�n exitosa. Espere por favor...");
								cargarInfoDocumentos();
							}
					});
				}
			}
		});
		$(".bootbox-prompt").addClass("show").show();	


    }
    else{
    	showError("El registro ya no se puede eliminar por que se encuentra aprobado.");
    }
}

function aprobarDocumento(idDocumento, mod, action) {

	bootbox.prompt({
		title: "Confirmaci�n",
		message: "Usted va aprobar el documento. El proceso no se podra deshacer.<br/><br/>Realmente desea continuar?<br/><br/>Comentarios:",
		closeButton: true,
		inputType: 'textarea',
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

			var observaciones = $(".bootbox-input-textarea").val();

			if (result === null) {
				closeBootbox();
				return;
			} else if (result === '') {
				showError('Debe ingresar un comentario para completar el proceso');
				return false;
			}
			else if (result){    

				showLoading("Enviando informacion. Espere por favor...");

				var strUrl = "admindex.php";
				var dataForm = "Ajax=true&mod=" + mod + "&action=" + action + "&id_documento=" + idDocumento + "&observaciones=" + observaciones;
				$.ajax({
						type: 'POST',
						url: strUrl,
						dataType: "json",
						data:dataForm,
						success: function (response) {
							closeNotify();
							if (response.Success){
								showSuccess("Transacci�n exitosa. Espere por favor...");
								cargarInfoDocumentos();
							}
							else 
								showError("Por favor ingrese el a�o del documento para realizar el proceso.");
						}
				});
			}
		}
	});
	$(".bootbox-prompt").addClass("show").show();					
}

</script>
<?php

	if ($_SESSION["profile_text"] == "Cliente"){	
?>
	<script>
		function cargarInfoDocumentos(){
			window.location.href='admindex.php?mod=clientes&action=listDocumentosCliente';
		}
	</script>
<?php
	}
?>
