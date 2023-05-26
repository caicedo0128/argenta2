<script>

$(document).ready(function() {
    oTable = $('#listEstudios').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});
});

function editEstudio(idEstudio) {
    loader();
    $("#content_estudios").load('admindex.php', { Ajax:true, id_estudio: idEstudio, mod: 'estudioRiesgo', action:'estudio', id_tercero:<?=$idTercero?>}, function () {
        loader();
    });
}

function imprimirEstudio(idEstudio) {

    loader();
    $("#content_impresion_estudio").load('admindex.php', { Ajax:true, id_estudio: idEstudio, mod: 'estudioRiesgo', action:'imprimirEstudio', id_tercero:<?=$idTercero?>}, function () {
        loader();
        $('#modalToImpresionEstudio').modal('show');
        window.setTimeout(function(){
        	descargarPDF();
        	window.setTimeout(function(){
        		$('#modalToImpresionEstudio').modal('hide');
        	},1000);
        },1000);
    });
}

function deleteEstudio(idEstudio) {

	bootbox.confirm({
		title: "Confirmaci�n",
		message: "Usted va a eliminar el estudio de riesgo. El proceso no se podra deshacer.<br/><br/>Realmente desea continuar?",
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

				showLoading("Enviando informaci�n. Espere por favor...");

				var strUrl = "admindex.php";
				var dataForm = "Ajax=true&mod=estudioRiesgo&action=eliminarEstudio&id_estudio=" + idEstudio
				$.ajax({
						type: 'POST',
						url: strUrl,
						dataType: "json",
						data:dataForm,
						success: function (response) {
							closeNotify();
							showSuccess("Transacci�n exitosa. Espere por favor...");
							cargarEstudios();
						}
				});
			}
		}
	});
	$(".bootbox-prompt").addClass("show").show();
}

function cargarEstudios() {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'estudioRiesgo', action:'listEstudios', id_tercero:<?=$idTercero?>}, function () {
        loader();
    });
}

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-success-custom">
        <h4>Informaci�n de estudios de riesgo</h4>
    </div>
    <br/><br/><br/>
    <div class="container-fluid row-fluid">
        <div class="col-md-1 labelCustom"><strong>Raz�n social</strong></div>
        <div class="col-md-3 btn btn-warning"><?=$cliente->razon_social?></div>
        <div class="col-md-1 labelCustom"><strong>Representante legal</strong></div>
        <div class="col-md-3 btn btn-warning"><?=$cliente->representante_legal?></div>
        <div class="col-md-1 labelCustom"><strong>Identificaci�n</strong></div>
        <div class="col-md-3 btn btn-warning"><?=$cliente->identificacion?></div>
    </div>
    <hr/>
    <div id="content_estudios" class="container-fluid " style="clear:both;padding-top:15px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">
                    <?
                    if ($appObj->tienePermisosAccion(array("estudioRiesgo_agregar_terceros")))
                    {
                        //Opcion a ejecutar si tiene el permiso
                        echo "<a class='btn btn-primary btn-sm' href='javascript:editEstudio(0)'><i class='fa fa-plus-square fa-lg'></i> Agregar</a>";
                    }    
                    ?>
                    <a class="btn btn-warning btn-sm" href="admindex.php?mod=clientes&action=searchClients"><i class="fa fa-reply fa-lg"></i> Regresar</a>
                </div>
            </div>
            <table id="listEstudios" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Editar</th>
                    <th>Eliminar</th>
                    <th>Fecha</th>
                    <th>A�o</th>
                    <th>Modelo</th>
                    <th>Cupo aprobado</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    while(!$rsEstudios->EOF)
                    {
                        $idEstudio = $rsEstudios->fields["id_estudio"];
                ?>
                        <tr>
                            <?
                                if ($appObj->tienePermisosAccion(array("estudioRiesgo_eliminar_terceros")))
                                {
                                    //Opcion a ejecutar si tiene el permiso
                                    echo "<td align='center'><a href='javascript:deleteEstudio(<?=$idEstudio?>);'><img border='0' alt='Eliminar estudio' title='Eliminar estudio' src='./images/eliminar.png'></a></td>";
                                } 
                                 
                                if ($appObj->tienePermisosAccion(array("estudioRiesgo_edita_terceros")))
                                {
                                    //Opcion a ejecutar si tiene el permiso
                                    echo "<td align='center'><a href='javascript:editEstudio(<?=$idEstudio?>);'><img border='0' alt='Editar estudio' title='Editar estudio' src='./images/editar.png'></a></td>";
                                } 
                            ?>
                            
                            <td><?=$rsEstudios->fields["fecha"]?></td>
                            <td><?=$rsEstudios->fields["anio"]?></td>
                            <td><?=$rsEstudios->fields["nombre_modelo"]?></td>
                            <td align="right"><?=formato_moneda($rsEstudios->fields["cupo"])?></td>
                            <td align="center">
                                <a href="javascript:imprimirEstudio(<?=$idEstudio?>);" title="Imprimir estudio" class="link_custom"><i class="fa fa-print text-success"></i></a>
                            </td>
                        </tr>
                <?php
                        $rsEstudios->MoveNext();
                    }
                ?>
            </tbody>
            </table>
    </div>
</div>
<div id="modalToImpresionEstudio" class="modal fade" role="dialog" aria-labelledby="modalToImpresionEstudio" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Generando PDF...</h4>
            </div>
            <div class="modal-body" id="content_impresion_estudio">
            </div>
        </div>
    </div>
</div>


