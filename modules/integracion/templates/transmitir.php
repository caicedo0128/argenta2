<script type="text/javascript">

$(document).ready(function(){

});

function procesarTransmision(tipo, idFactura){
		
		$("#resultado_transmision").hide();
		$("#listado_errores > tbody").empty();		
		$("#listado_errores").hide();

		$(".datosTransmision_btnSave").attr("disabled","disabled").css("color","#fffff !important").attr("value","Transmitiendo...");
		$("#resultado_transmision").removeClass("alert-danger").removeClass("alert-success").addClass("alert-info").html("Enviando información. Espere por favor...").show();

		var strUrl = "admindex.php";
		$.ajax({
				type: 'POST',
				url: strUrl,
				dataType: "json",
				data:{
					Ajax:true,
					mod:'integracion',
					action:'transmitirFactura',
					id_factura:idFactura,
					tipo_proceso:tipo
				},
				success: function (response) {
					if (response.Success){
						$("#resultado_transmision").removeClass("alert-info").addClass("alert-success").html("Transacción exitosa. Espere por favor...");
						cargarFacturas();
					}
					else{
						$("#resultado_transmision").removeClass("alert-info").addClass("alert-danger").html("Se han presentado errores en la transmisión.<br/>" + response.Msj);

						//RECORREMOS EL DETALLE DEL ERROR
						if (response.Response != null){
							for(let data of response.Response){

								$("#listado_errores > tbody").append('<tr>' +
																	 '<td>' + data.codigoValidacion + '</td>' +
																	 '<td>' + data.descripcionValidacion + '</td>' +
																	 '</tr>');
							}	
							$("#listado_errores").show(); 
						}
						$(".datosTransmision_btnSave").removeAttr("disabled").attr("value","Transmitir");
						$(".datosTransmision_btnConsultar").removeAttr("disabled").attr("value","Consultar eventos");
					}
				}
		});

}

function consultarEventosDian(idFactura){
		
		$("#resultado_transmision").hide();
		$("#listado_errores > tbody").empty();		
		$("#listado_errores").hide();

		$(".datosTransmision_btnSave").attr("disabled","disabled").css("color","#fffff !important").attr("value","Transmitiendo...");
		$("#resultado_transmision").removeClass("alert-danger").removeClass("alert-success").addClass("alert-info").html("Enviando información. Espere por favor...").show();

		var strUrl = "admindex.php";
		$.ajax({
				type: 'POST',
				url: strUrl,
				dataType: "json",
				data:{
					Ajax:true,
					mod:'integracion',
					action:'consultarEventos',
					id_factura:idFactura
				},
				success: function (response) {
					console.log(response.Response);
					if (response.Success){
						$("#resultado_transmision").removeClass("alert-info").addClass("alert-success").html("Transacción exitosa.");
						//RECORREMOS EL DETALLE DE LOS EVENTOS
						if (response.Response != null){
							for(let data of response.Response){

								$("#listado_eventos > tbody").append('<tr>' +
																	 '<td>' + data.id + '</td>' +
																	 '<td>' + data.fechaEvento + '</td>' +
																	 '<td>' + data.codigoEvento + ' - ' + data.descripcionEvento + '</td>' +
																	 '<td>' + data.emisor.nombreCompleto + '</td>' +
																	 '<td>' + data.receptor.nombreCompleto + '</td>' +
																	 '</tr>');
							}	
							$("#listado_eventos").show(); 
							$(".datosTransmision_btnSave").removeAttr("disabled").attr("value","Transmitir");
							$(".datosTransmision_btnConsultar").removeAttr("disabled").attr("value","Consultar eventos");							
						}						
					}
					else{
						$("#resultado_transmision").removeClass("alert-info").addClass("alert-danger").html("Se han presentado errores en la transmisión.<br/>" + response.Msj);

						//RECORREMOS EL DETALLE DEL ERROR
						if (response.Response != null){
							for(let data of response.Response){

								$("#listado_errores > tbody").append('<tr>' +
																	 '<td>' + data.codigoValidacion + '</td>' +
																	 '<td>' + data.descripcionValidacion + '</td>' +
																	 '</tr>');
							}	
							$("#listado_errores").show(); 
						}
						$(".datosTransmision_btnConsultar").removeAttr("disabled").attr("value","Consultar eventos");
					}
				}
		});

}

</script>
<style>
.modal-dialog {
    max-width:60% !important;
    width:60% !important;
}
</style>
<div class="panel panel-primary">
    <div class="panel-body">
        <form id="transmitirFactura" method="post" name="transmitirFactura" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="integracion" />
            <input type="hidden" name="action" value="transmitirFactura" />
            <input type="hidden" name="Ajax" id="Ajax" value="true" />
            <input type="hidden" name="id_factura" id="id_factura" value="<?=$idFactura?>" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$factura->id_operacion?>" />           
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3">
					<b>Nro. factura:</b>
					<div class="">
					<?=($factura->prefijo!=""?$factura->prefijo."-":"")?><?=$factura->num_factura?>
					</div>
                </div>  
                <div class="col-md-3">
					<b>Valor neto:</b>
					<div class="">
					<?=formato_moneda($factura->valor_neto)?>
					</div>
                </div>        
                <div class="col-md-3">
					<b>Ultimo evento enviado:</b>
					<div>
						<div class="" style="height:2px;">&nbsp;</div>
						<div  class="label label-success" style="">
						<?=$this->arrEstadosTransmisionFacturas[$factura->id_estado_transmision]?>
						</div>
					</div>
                </div> 
                <div class="col-md-3">
					<b>Crear evento:</b>
					<div>
						<div class="" style="height:2px;">&nbsp;</div>
						<div  class="label label-danger" style="">
						<?=$this->arrEstadosTransmisionFacturas[$tipo]?>
						</div>
					</div>
                </div>                
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-12 labelCustom">
					<b>CUFE:</b>
					<div class="">
					<?=$factura->cufe?>
					</div>
                </div>                
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
			<center>
				<input type="button" value="Transmitir" class="btn btn-primary datosTransmision_btnSave" onclick="procesarTransmision(<?=$tipo?>,<?=$idFactura?>);">
				<input type="button" value="Consultar eventos" class="btn btn-warning datosTransmision_btnSave datosTransmision_btnConsultar" onclick="consultarEventosDian(<?=$idFactura?>);">
			</center>
        </form>
</div>
<div id="resultado_transmision" class="alert">

</div>
<table id="listado_errores" class="table table-sm table-striped table-bordered nowrap" cellspacing="0" style="width:100%;display:none;">
	<thead>
		<tr>
		<th>Código validación</th>
		<th>Descripción</th>
		</tr>
	</thead>
	<tbody>
	
	</tbody>
</table>
<table id="listado_eventos" class="table table-sm table-striped table-bordered nowrap" cellspacing="0" style="width:100%;display:none;">
	<thead>
		<tr>
		<th>Id</th>
		<th>Fecha evento</th>
		<th>Evento</th>
		<th>Emisor</th>
		<th>Receptor</th>
		</tr>
	</thead>
	<tbody>
	
	</tbody>
</table>
