<script>

$(document).ready(function() {
    oTable = $('#listFacturasOperacion').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});
});

function editFactura(idFactura) {
    loader();
    $("#content_facturas").load('admindex.php', { Ajax:true, id_factura: idFactura, id_operacion: <?=$idOperacion?>, mod: 'operaciones', action:'factura'}, function () {
        loader();
    });
}

function deleteFactura(idFactura) {

		bootbox.confirm({
			title: "Confirmaci�n",
			message: "Usted va a eliminar la factura. El proceso no se podra deshacer.<br/><br/>Realmente desea continuar?",
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
					var dataForm = "Ajax=true&mod=operaciones&action=eliminarFactura&id_factura=" + idFactura
					$.ajax({
							type: 'POST',
							url: strUrl,
							dataType: "json",
							data:dataForm,
							success: function (response) {
								closeNotify();
								showSuccess("Transacci�n exitosa. Espere por favor...");
								cargarFacturas();
							}
					});
				}
			}
		});
		$(".bootbox-prompt").addClass("show").show();

}

function cargueFacturas(){
    $("#modalToCargarFacturas").modal('show');
    $("#id_operacion_cargue").val($("#id_operacion").val());
}

function procesarCargueFacturas(){

    validateForm("cargar_facturas");

    if ($("#cargar_facturas").valid()) {

        showLoading("Cargando datos. Espere por favor...");

        var strUrl = "admindex.php";
        var dataForm = new FormData(document.getElementById("cargar_facturas"));

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
                showSuccess("Transacci�n exitosa. Espere por favor...");
                $("#modalToCargarFacturas").modal('hide');
                window.setTimeout(function(){
                    cargarFacturas();
                },1000);
            }
        });

    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function eliminarTodasFacturas(){

        bootbox.confirm('Usted va a eliminar todas las facturas.<br/><br/>Tenga en cuenta que solo se borrar�n las facturas sin reliquidaci�n, Desea continuar?.', function (result) {

        if (result) {

            showLoading("Eliminando datos. Espere por favor...");

            var strUrl = "admindex.php?Ajax=true&mod=operaciones&action=eliminarTodasFacturas";

            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data: {
                    id_operacion : $("#id_operacion").val()
                },
                cache: false,
                success: function (response) {
                    closeNotify();
                    showSuccess("Transacci�n exitosa. Espere por favor...");
                    cargarFacturas();
                }
            });
        }
        });
}

function descargarReporte(){

}

function confirmarTransmitirFactura(tipo, idFactura){

	showLoading("Enviando informaci�n. Espere por favor...");

	var strUrl = "admindex.php";
	$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "html",
			data:{
				Ajax:true,
				mod:'integracion',
				action:'confirmacionTransmitirFactura',
				id_factura:idFactura,
				tipo_proceso:tipo
			},
			success: function (response) {
				closeNotify();
				var dialog = bootbox.dialog({
					title: "Transmitir factura a DIAN",
					message: response
				});
				$(".bootbox").show().addClass("show");
			}
	});

}

function verLogTransmision(tipoProceso, idFactura){

	showLoading("Enviando informaci�n. Espere por favor...");
	var strUrl = "admindex.php";
	$.ajax({
		type: 'POST',
		url: strUrl,
		dataType: "html",
		data: {
			Ajax:true,
			mod:'integracion',
			action:'verLogTransmision',
			tipo_proceso:tipoProceso,
			id_factura:idFactura
		},
		mimeType: "multipart/form-data",
		cache: false,
		success: function (response) {
			closeNotify();
			var dialog = bootbox.dialog({
				title: "Ver log transmisi�n",
				message: response
			});
			$(".bootbox").show().addClass("show");
		}
	});

}

</script>
<style>
	#listFacturasOperacion{
		font-size:11px !important;
	}
</style>
<?php
    //IMPRIMIMOS EL SUMARIO DE LA OPERACION
    $this->sumarioOperacion($idOperacion);
?>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Informacin de facturas</h4>
    </div>
    <div id="content_facturas" class="" style="clear:both;padding-top:10px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">

				<?
					if ($appObj->tienePermisosAccion(array("facturas_agregar_operaciones")))
					{
					//Opcion a ejecutar si tiene el permiso
					echo "<a class='btn btn-primary btn-sm' href='javascript:editFactura(0)'><i class='fa fa-plus-square fa-lg'></i> Agregar</a>";
					}

				?>
                    <?php
                        if ($operacion->estado == 3 || $operacion->estado == 4){
                    ?>
                        <a class="btn btn-warning btn-sm" href="javascript:cargueFacturas()"><i class="fa fa-plus-square fa-lg"></i> Cargar facturas</a>
                        <a class="btn btn-danger btn-sm" href="javascript:eliminarTodasFacturas()"><i class="fa fa-minus fa-lg"></i> Eliminar facturas</a>
                    <?php

                        }
                    ?>
                    <a class="btn btn-success btn-sm" href="admindex.php?Ajax=true&mod=operaciones&action=reporteLiquidacionFacturas&id_operacion=<?=$idOperacion?>" target="_blank"><i class="fa fa-download fa-lg"></i> Exportar</a>
                </div>
            </div>
            <table id="listFacturasOperacion" class="table table-sm table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th class="no-sort">Opciones</th>
                    <th>Fecha<br/>pago</th>
                    <th>Fecha<br/>emisi�n</th>
                    <th>Fecha<br/>vencimiento</th>
                    <th>Nro.<br/>Factura</th>
                    <th>Valor<br/>neto</th>
                    <th>Valor<br/>bruto</th>
                    <th>Valor<br/>futuro</th>
                    <th>Descuento<br/>total</th>
                    <th>Inter�s<br/>corriente</th>
                    <th>Gesti�n<br/>referenciaci�n</th>
                    <th>Giro<br/>final</th>
					<?php
						if ($_SESSION["profile_text"]!="Cliente"){
					?>
                    	<th class="no-sort">Transmitir</th>
                   	<?php
                   		}
                   	?>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $totalFacturas = 0;
                    while(!$rsFacturas->EOF)
                    {
                        $totalFacturas++;
                        $idOperacionFactura = $rsFacturas->fields["id_operacion_factura"];
                        $idEstado = $rsFacturas->fields["estado"];
                ?>
                        <tr>
                            <td align="center">
								<?php
                                    //if (($operacion->estado == 4 && $_SESSION["profile_text"]=="Cliente") || $_SESSION["profile_text"]!="Cliente")
                                    //{
                                ?>
									<?
									if ($appObj->tienePermisosAccion(array("facturas_editar_operaciones")))
									{
										//Opcion a ejecutar si tiene el permiso
										echo "<a href='javascript:editFactura(<?=$idOperacionFactura?>);'><img border='0' alt='Editar factura' title='Editar factura' src='./images/editar.png'></a>";
									}
									?>
                                <?php
                                   // }
                                ?>

								<?php
                                   // if (($idEstado == 1 && ($operacion->estado == 3 || $operacion->estado == 6) && $_SESSION["profile_text"]!="Cliente") || ($operacion->estado == 4 && $_SESSION["profile_text"]=="Cliente"))
                                    //{
                                ?>
									<?
									if ($appObj->tienePermisosAccion(array("facturas_eliminar_operaciones")))
									{
										//Opcion a ejecutar si tiene el permiso
										echo "<a href='javascript:deleteFactura(<?=$idOperacionFactura?>);'><img border='0' alt='Eliminar factura' title='Eliminar factura' src='./images/eliminar.png'></a>";
									
									}
									?>
                                <?php
                                    //}
                                ?>
                                
                            	<?php
									if ($rsFacturas->fields["archivo"] != ""){
										echo "<a href='".$this->rutaArchivosFacturasFisicas."/".$rsFacturas->fields["archivo"]."' target='_blank' title='Ver archivo soporte'><i class='fa fa-file text-danger'></i></a>";
									}
				        		?>
                                
                            </td>
                            <td><?=$rsFacturas->fields["fecha_pago"]?></td>
                            <td><?=$rsFacturas->fields["fecha_emision"]?></td>
                            <td><?=$rsFacturas->fields["fecha_vencimiento"]?></td>
                            <td><?=$rsFacturas->fields["prefijo"]?><?=$rsFacturas->fields["num_factura"]?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["valor_neto"])?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["valor_bruto"])?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["valor_futuro"])?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["descuento_total"])?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["margen_inversionista"])?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["margen_argenta"])?> <br/>Reli: <?=formato_moneda($rsFacturas->fields["margen_argenta_reli"])?></td>
                            <td align="right"><?=formato_moneda($rsFacturas->fields["valor_giro_final"])?></td>
                            <?php
                            	if ($_SESSION["profile_text"]!="Cliente"){
                            ?>
								<td align="center" nowrap="nowrap">
									<div>
										<div style="float:left;">
											<a href="javascript:confirmarTransmitirFactura(2,<?=$idOperacionFactura?>);" title="Mandato factura">
												<span class="rcorners rcorners_small label-primary" title="Mandato">M</span>                            	
											</a>
											<?php
												if ($rsFacturas->fields["id_estado_mandato"] != null ){
													$classIconM = ($rsFacturas->fields["id_estado_mandato"]==1?"text-success fa-check":"text-danger fa-times-circle-o")
											?>										
												<br/>
												<a href="javascript:verLogTransmision(2,<?=$idOperacionFactura?>);" title="Ver log transmisi�n">
													<i class="fa <?=$classIconM?>" style="float:left;"></i> 
												</a>
											<?php
												}
											?>											
										</div>									
										<div style="float:left;">
											<a href="javascript:confirmarTransmitirFactura(1,<?=$idOperacionFactura?>);" title="Inscribir factura">
												<span class="rcorners rcorners_small label-info" title="Inscripci�n">I</span>                        
											</a>
											<?php
												if ($rsFacturas->fields["id_estado_inscripcion"] != null ){
													$classIconI = ($rsFacturas->fields["id_estado_inscripcion"]==1?"text-success fa-check":"text-danger fa-times-circle-o")
											?>
												<br/>
												<a href="javascript:verLogTransmision(1,<?=$idOperacionFactura?>);" title="Ver log transmisi�n">
													<i class="fa <?=$classIconI?>" style="float:left;"></i> 
												</a>									
											<?php
												}
											?>
										</div>
										<div style="float:left;">
											<a href="javascript:confirmarTransmitirFactura(3,<?=$idOperacionFactura?>);" title="Endoso factura">
												<span class="rcorners rcorners_small label-warning" title="Endoso">E</span>                            	
											</a>
											<?php
												if ($rsFacturas->fields["id_estado_endoso"] != null ){
													$classIconE = ($rsFacturas->fields["id_estado_endoso"]==1?"text-success fa-check":"text-danger fa-times-circle-o")
											?>											
												<br/>
												<a href="javascript:verLogTransmision(3,<?=$idOperacionFactura?>);" title="Ver log transmisi�n">
													<i class="fa <?=$classIconE?>" style="float:left;"></i> 
												</a>																				
											<?php
												}
											?>																					
										</div>
										<div style="float:left;">
											<a href="javascript:confirmarTransmitirFactura(4,<?=$idOperacionFactura?>);" title="Informe para pago factura">
												<span class="rcorners rcorners_small label-success" title="Informe para pago">IP</span>                            	
											</a>										
											<?php
												if ($rsFacturas->fields["id_estado_informe"] != null ){
													$classIconIP = ($rsFacturas->fields["id_estado_informe"]==1?"text-success fa-check":"text-danger fa-times-circle-o")
											?>	
												<br/>
												<a href="javascript:verLogTransmision(4,<?=$idOperacionFactura?>);" title="Ver log transmision">
													<i class="fa <?=$classIconIP?>" style="float:left;"></i> 
												</a>																			
											<?php
												}
											?>																					
										</div>
										<div style="float:left;">
											<a href="javascript:confirmarTransmitirFactura(5,<?=$idOperacionFactura?>);" title="Pago factura">
												<span class="rcorners rcorners_small label-default" title="Pago">P</span>                            	
											</a>
											<?php
												if ($rsFacturas->fields["id_estado_pago"] != null ){
													$classIconP = ($rsFacturas->fields["id_estado_pago"]==1?"text-success fa-check":"text-danger fa-times-circle-o")
											?>											
												<br/>
												<a href="javascript:verLogTransmision(5,<?=$idOperacionFactura?>);" title="Ver log transmisi�n">
													<i class="fa <?=$classIconP?>" style="float:left;"></i> 
												</a>																				
											<?php
												}
											?>																					
										</div>
									</div>							
								</td>
                            <?php
                            	}
                            ?>                            
                            <td align="center"><?=$this->arrEstadosFacturas[$rsFacturas->fields["estado"]]?></td>
                        </tr>
                <?php
                        $rsFacturas->MoveNext();
                    }
                ?>
            </tbody>
            </table>
    </div>
</div>

<?php
    //DETERMINAMOS SI HAY FACTURAS PARA BLOQUEAR EL FORMULARIO DE LA OPERACION
    if ($totalFacturas > 0){
?>
    <script>
        $(document).ready(function () {

        });
    </script>
<?php
}
?>

<div id="modalToCargarFacturas" class="modal fade" role="dialog" aria-labelledby="modalToCargarFacturas" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Cargar facturas masivas.</h4>
            </div>
            <div class="modal-body" id="">
                <form id="cargar_facturas" name="cargar_facturas" method="post" action="admindex.php" enctype="multipart/form-data">
                <input type="hidden" name="Ajax" id="Ajax" value="true">
                <input type="hidden" name="mod" id="mod" value="operaciones">
                <input type="hidden" name="action" id="action" value="procesarCargaFacturas">
                <input type="hidden" name="id_operacion_cargue" id="id_operacion_cargue" value="">
                <div class="row">
                    <div class="col-md-2 labelCustom">Archivo:</div>
                    <div class="col-md-8">
                        <input type="file" id="file" name="file" value="" class="form-control required" style="height:none !important;" accept=".csv">
						<center><a href="./gallery/FormatoCargueFacturas.xls" title="Descargar formato excel"><i class="fa fa-download fa-2x"></i></a></center>
						<small>
							<li>Descargue el formato de carga.
							<li>Diligencie el archivo sin cambiar su estructura, todos los campos son obligatorios.
							<li>Formato para fechas (AAAA-MM-DD).
							<li>Valores sin punto o coma.
							<li>Guarde el archivo en formato csv(DOS).
							<li>Cargue el archivo generado.
						</small>
                    </div>
                </div>
                <div class="row" style="height:10px;">&nbsp;</div>
                <center>
                    <input type="button" class="btn btn-primary" value="Cargar archivo" onclick="procesarCargueFacturas();">
                </center>
                </form>
            </div>
        </div>
    </div>
</div>



