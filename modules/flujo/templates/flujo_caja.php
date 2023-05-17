<script type="text/javascript">

$(document).ready(function(){
    
});

function saveFlujo(){

	showLoading("Enviando información. Espere por favor...");
	var dataForm = new FormData(document.getElementById("datosFlujo"));
	var strUrl = "admindex.php";
	$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data:dataForm,
			mimeType: "multipart/form-data",
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				window.setTimeout(function(){
					closeNotify();
					if (response.Success) {
						showSuccess(response.Message);
						window.location.href="admindex.php?mod=flujo&action=flujoCaja";
					}
					else{
						showError(response.Message,5000);
					}
				},800);
			}
	});

}

function saveFlujoDetalle(){

	showLoading("Enviando información. Espere por favor...");
	var dataForm = new FormData(document.getElementById("flujoDetalle"));
	var strUrl = "admindex.php";
	$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data:dataForm,
			mimeType: "multipart/form-data",
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				window.setTimeout(function(){
					closeNotify();
					if (response.Success) {
						showSuccess(response.Message);
						window.location.href="admindex.php?mod=flujo&action=flujoCaja";
					}
					else{
						showError(response.Message,5000);
					}
				},800);
			}
	});

}

function deleteFlujoDetalle(id){

	showLoading("Enviando información. Espere por favor...");
	var dataForm = new FormData(document.getElementById("flujoDetalle"));
	var strUrl = "admindex.php";
	$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data:{
				mod:'flujo',
				action:'eliminarFlujoCajaDetalle',
				Ajax:true,
				id_flujo_caja_detalle:id
			},
			success: function (response) {
				window.setTimeout(function(){
					closeNotify();
					if (response.Success) {
						showSuccess(response.Message);
						window.location.href="admindex.php?mod=flujo&action=flujoCaja";
					}
					else{
						showError(response.Message,5000);
					}
				},800);
			}
	});

}

function saveSoporte(){

	showLoading("Enviando información. Espere por favor...");
	var strUrl = "admindex.php";
	$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data:{
				Ajax:true,
				mod:'flujo',
				action:'guardarSoporte',
				id_flujo_caja:$("#id_flujo_caja").val(),
				soporte:$("#contenido-flujo").html()
			},
			success: function (response) {
				closeNotify();
				if (response.Success) {
					showSuccess(response.Message);
					window.location.href="admindex.php?mod=flujo&action=flujoCaja";
				}
				else{
					showError(response.Message,5000);
				}
			}
	});

}

</script>
<style>

</style>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Reporte flujo de caja</h4>
    </div>
</div>
<div id="content_flujo class="container-fluid " style="clear:both;padding-top:15px;">

    <div class="panel panel-primary col-md-12">
        <div class="panel-body">        
        <center>
        <h2 style="font-size:24px !important;">FLUJO DE CAJA <?=$fechaActual?></h2>
        <b>Semana: <?=$fechaIniProximosVencimientos?> a <?=$fechaFinProximosVencimientos?></b>
        <br/> <br/>
		<?php
			if ($flujoCaja->id_flujo_caja == null || $flujoCaja->id_flujo_caja == ""){
				echo "<div class='label label-danger p-5'>Flujo de caja sin guardar</div><br/><br/>";
				echo "<input type='button' class='btn btn-sm btn-success' value='Guardar' onclick='saveFlujo()'><br/><br/>";
			}
			else{
				echo "<div class='label label-success p-5'>Flujo de caja guardado</div><br/><br/>";
				echo "<input type='button' class='btn btn-sm btn-primary' style='' value='Actualizar información' onclick='saveFlujo()'>";
				echo "<div class='label label-warning' style='margin-left:5px;padding:8px;margin-top:8px;'>Ultima actualización:" . $flujoCaja->fecha_actualizacion . " - Actualizado por: " . $flujoCaja->usuario_actualiza . "</div>";
				
				if ($flujoCaja->soporte == ""){					
					echo "<input type='button' class='btn btn-sm btn-primary' style='margin-left:5px;' value='Guardar soporte' onclick='saveSoporte()'>";
					echo "<div class='label label-danger' style='margin-left:5px;padding:8px;margin-top:5px;'>Soporte sin guardar</div>";
				}
				else{
					echo "<input type='button' class='btn btn-sm btn-primary' style='margin-left:5px;' value='Actualizar soporte' onclick='saveSoporte()'>";
					echo "<div class='label label-info' style='margin-left:5px;padding:8px;margin-top:5px;'>Ultima actualizacón soporte: ".$flujoCaja->fecha_actualizacion_soporte."</div>";				
				}
				
				echo "<br/><br/>";
			}
		?>        
        </center>
		<div class="row" id="contenido-flujo">
			<div class="col-md-6 labelCustom"> 
				<div class="row">
					<div class="alert alert-success">ENTRADAS</div>			
					<form name="datosFlujo" id="datosFlujo" action="" method="POST">
					<input type="hidden" name="mod"  value="flujo">
					<input type="hidden" name="action" value="guardarFlujoCaja">
					<input type="hidden" name="Ajax" value="true">
					<input type="hidden" name="id_flujo_caja" id="id_flujo_caja" value="<?=$flujoCaja->id_flujo_caja?>">
					<input type="hidden" name="fecha" id="fecha" value="<?=$fechaActual?>">

				
					<div class="alert alert-success alert-custom row-form-alert">Saldo en bancos:</div>
					<table class="table table-striped table-bordered responsive nowrap" id="" width="60%" style="width:60% !important;">
						<thead>
							<tr>
							<th>Cuenta</th>
							<th>Saldo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<td>53-31</td>
							<td>
								<?php 
									$c_textbox = new Textbox;
									echo $c_textbox->Textbox ("cuenta1", "cuenta1", 1, $flujoCaja->cuenta1, "form-control", 50, "", "", "","","");
								?> 							
							</td>
							</tr>
							<tr>
							<td>62-68</td>
							<td>
								<?php 
									$c_textbox = new Textbox;
									echo $c_textbox->Textbox ("cuenta2", "cuenta2", 1, $flujoCaja->cuenta2, "form-control", 50, "", "", "","","");
								?> 							
							</td>
							</tr>
							<tr>
							<td>60-72</td>
							<td>
								<?php 
									$c_textbox = new Textbox;
									echo $c_textbox->Textbox ("cuenta3", "cuenta3", 1, $flujoCaja->cuenta3, "form-control", 50, "", "", "","","");
								?> 							
							</td>
							</tr>		
							<tr>
							<td nowrap="nowrap"><b>TOTAL EN BANCOS:</b></td>
							<td>
								<?=formato_moneda($flujoCaja->cuenta1+$flujoCaja->cuenta2+$flujoCaja->cuenta3)?> 							
							</td>
							</tr>								
					</table>
					</form>
					<div class="alert alert-success alert-custom row-form-alert">Operaciones vencidas (Hasta el <?=$fechaFacturasVencidas?>):</div>
					<table class="table table-striped table-bordered responsive nowrap" id="" width="80%" style="width:80% !important;">
						<thead>
							<tr>
							<th>Pagador</th>
							<th>Fecha</th>
							<th>Valor</th>
							</tr>
						</thead>
						<tbody>
							<?php
								while(!$rsFacturasVencidas->EOF)
								{
							?>
									<tr>
									<td><?=$rsFacturasVencidas->fields["pagador"]?></td>
									<td><?=$rsFacturasVencidas->fields["menorFechaPago"]?></td>
									<td align="right">
									<?php
										echo formato_moneda($rsFacturasVencidas->fields["valor"]);
									?>
									</td>
									</tr>
							<?php
									$totalOperacionesVencidas+=$rsFacturasVencidas->fields["valor"];
									$rsFacturasVencidas->MoveNext();
								}
							?>
							<tr>
							<td nowrap="nowrap" colspan="2"><b>TOTAL OPERACIONES VENCIDAS:</b></td>
							<td align="right">
								<b>
								<?=formato_moneda($totalOperacionesVencidas)?> 							
								</b>
							</td>
							</tr>								
					</table>	
					<div class="alert alert-success alert-custom row-form-alert">Próximos vencimientos (Desde <?=$fechaIniProximosVencimientos?> a <?=$fechaFinProximosVencimientos?>):</div>
					<table class="table table-striped table-bordered responsive nowrap" id="" width="80%" style="width:80% !important;">
						<thead>
							<tr>
							<th>Pagador</th>
							<th>Fecha</th>
							<th>Valor</th>
							</tr>
						</thead>
						<tbody>
							<?php
								while(!$rsProximosVencimientos->EOF)
								{
							?>
									<tr>
									<td><?=$rsProximosVencimientos->fields["pagador"]?></td>
									<td><?=$rsProximosVencimientos->fields["menorFechaPago"]?></td>
									<td align="right"><?=formato_moneda($rsProximosVencimientos->fields["valor"])?></td>
									</tr>
							<?php
									$totalProximosVencimientos+=$rsProximosVencimientos->fields["valor"];
									$rsProximosVencimientos->MoveNext();
								}
							?>
							<tr>
							<td nowrap="nowrap" colspan="2"><b>TOTAL PROXIMOS VENCIMIENTOS:</b></td>
							<td align="right">
								<b>
								<?=formato_moneda($totalProximosVencimientos)?> 							
								</b>
							</td>
							</tr>								
					</table>						
				</div>				
			</div>
			<div class="col-md-6 labelCustom">        
				<div class="alert alert-warning">SALIDAS</div>	
				<div class="alert alert-warning alert-custom row-form-alert">Remanentes (Desde <?=$fechaInicialRemanentes?> a <?=$fechaActual?>):</div>
				<table class="table table-striped table-bordered responsive nowrap" id="" width="100%" style="width:100% !important;">
					<thead>
						<tr>
						<th>Operación</th>
						<th>Emisor</th>
						<th>Fecha reliquidación</th>
						<th>Remanentes</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$tieneRemanentes = false;
							while(!$rsRemanentes->EOF)
							{
							
								//DETERMINAMOS EL MONTO A DEVOLVER
								$montoDevolver = $rsRemanentes->fields["monto_devolver_pp"];
								$nuevoRemanente = -99;
								if ($montoDevolver == null){
									$montoDevolver = $rsRemanentes->fields["monto_devolver_pt"];
									$nuevoRemanente = $rsRemanentes->fields["nuevo_remanente_pt"];
								}
							
								//SI NO TIENE DESEMBOLSO SE MUESTRA
								if ($rsRemanentes->fields["fecha_desembolso"]=="")
								{
						?>
									<tr>
									<td><?=$rsRemanentes->fields["id_operacion"]?></td>
									<td><?=$rsRemanentes->fields["emisor"]?></td>
									<td><?=$rsRemanentes->fields["fecha_registro"]?></td>								
									<td align="right"><?=formato_moneda($montoDevolver)?><?=($nuevoRemanente!=-99?"<br/>NR:".formato_moneda($nuevoRemanente):"")?></td>
									</tr>
						<?php
									$tieneRemanentes = true;
								}
								$rsRemanentes->MoveNext();
							}
							
							if (!$tieneRemanentes){
								echo "<tr><td colspan='6'>No hay remanentes para devolución</td></tr>";
							}
						?>						
				</table>				
				<div class="alert alert-warning alert-custom row-form-alert">Operaciones programadas (En estado creada o creada por cliente):</div>
				<table class="table table-striped table-bordered responsive nowrap" id="" width="100%" style="width:100% !important;">
					<thead>
						<tr>
						<th>Operación</th>
						<th>Fecha operación</th>
						<th>Emisor</th>
						<th>Pagador</th>
						<th>Valor</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while(!$rsOperacionesProgramadas->EOF)
							{
						?>
								<tr>
								<td><?=$rsOperacionesProgramadas->fields["id_operacion"]?></td>
								<td><?=$rsOperacionesProgramadas->fields["fecha_operacion"]?></td>
								<td><?=$rsOperacionesProgramadas->fields["emisor"]?></td>
								<td><?=$rsOperacionesProgramadas->fields["pagador"]?></td>
								<td align="right"><?=formato_moneda($rsOperacionesProgramadas->fields["valor"])?></td>
								</tr>
						<?php
								$totalOperacionesProgramadas+=$rsOperacionesProgramadas->fields["valor"];
								$rsOperacionesProgramadas->MoveNext();
							}
						?>
						<tr>
						<td nowrap="nowrap" colspan="4"><b>TOTAL OPERACIONES PROGRAMADAS:</b></td>
						<td align="right">
							<b>
							<?=formato_moneda($totalOperacionesProgramadas)?> 							
							</b>
						</td>
						</tr>								
				</table>	
				<div class="alert alert-warning alert-custom row-form-alert">Pagos administrativos:</div>
				<?php
					if ($flujoCaja->id_flujo_caja == null || $flujoCaja->id_flujo_caja == ""){
						echo "No se puede registrar pagos administrativos por que no se ha guardado el flujo de caja";
					}
					else
					{
				?>
					<form name="flujoDetalle" id="flujoDetalle" action="" method="POST">
					<input type="hidden" name="mod"  value="flujo">
					<input type="hidden" name="action" value="guardarFlujoCajaDetalle">
					<input type="hidden" name="Ajax" value="true">
					<input type="hidden" name="id_flujo_caja" id="id_flujo_caja" value="<?=$flujoCaja->id_flujo_caja?>">		
					<table class="table table-striped table-bordered responsive nowrap" id="" width="80%" style="width:80% !important;">
						<thead>
							<tr>
							<th>Tercero</th>
							<th>Valor</th>
							<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
								while(!$rsPagosAdministrativos->EOF)
								{
							?>
									<tr>
									<td><?=$rsPagosAdministrativos->fields["tercero"]?></td>
									<td align="right"><?=formato_moneda($rsPagosAdministrativos->fields["valor"])?></td>
									<td><a href="javascript:deleteFlujoDetalle(<?=$rsPagosAdministrativos->fields["id_flujo_caja_detalle"]?>)"><i class="fa fa-minus text-danger fa-2x"></i></a></td>
									</tr>
							<?php
									$totalPagosAdministrativos+=$rsPagosAdministrativos->fields["valor"];
									$rsPagosAdministrativos->MoveNext();
								}
							?>
							<tr>
							<td>
								<?php 
									$c_textbox = new Textbox;
									echo $c_textbox->Textbox ("tercero", "tercero", 1, "", "form-control", 50, "", "", "","","");
								?> 								
							</td>
							<td>
								<?php 
									$c_textbox = new Textbox;
									echo $c_textbox->Textbox ("valor", "valor", 1, "", "form-control", 50, "", "", "","","");
								?> 								
							</td>
							<td><a href="javascript:saveFlujoDetalle()"><i class="fa fa-save fa-2x text-primary"></i></a></td>
							</tr>						
							<tr>
							<td nowrap="nowrap"><b>TOTAL PAGOS ADMINISTRATIVOS:</b></td>
							<td align="right">
								<b>
								<?=formato_moneda($totalPagosAdministrativos)?> 							
								</b>
							</td>
							<td>&nbsp;</td>
							</tr>								
					</table>		
					</form>
				<?php
					}
				?>					
			</div>			
		</div>
	</div>
</div>

