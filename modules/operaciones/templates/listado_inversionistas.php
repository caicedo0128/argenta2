<script>

$(document).ready(function() {
    oTable = $('#listInversionistasOperacion').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});      
});

function editInversionista(idInversionista) {
    loader();
    $("#content_inversionistas").load('admindex.php', { Ajax:true, id_inversionista_operacion: idInversionista, id_operacion: <?=$idOperacion?>, mod: 'operaciones', action:'inversionista'}, function () {
        loader();
    });
}

function deleteInversionista(idInversionista) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=operaciones&action=eliminarInversionista&id_inversionista_operacion=" + idInversionista
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarInversionistas();
            }
    });
}

function reportInversionistasParticipacion() {
    loader();
    $("#content_inversionistas").load('admindex.php', { Ajax:true, id_operacion: <?=$idOperacion?>, mod: 'operaciones', action:'reporteInversionistaParticipacion'}, function () {
        loader();
    });
}

</script>

<?php
    //IMPRIMIMOS EL SUMARIO DE LA OPERACION
    $this->sumarioOperacion($idOperacion);
?>    

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de inversionistas</h4>
    </div>  
    <br/><br/><br/>
    <div id="content_inversionistas" class="container-fluid " style="clear:both;padding-top:15px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">
                    <?php
                        if ($operacion->estado == 3 || $operacion->estado == 1){
                    ?>
                        <a class="btn btn-success btn-sm" href="javascript:editInversionista(0)"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>                                                      
                    <?php
                        }
                    ?>                 
                    	<a class="btn btn-warning btn-sm" href="javascript:reportInversionistasParticipacion()"><i class="fa fa-plus-square fa-lg"></i> Detalle inversiones</a>                                                      
                    </div>
            </div>
            <table id="listInversionistasOperacion" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Editar</th>
                    <th>Eliminar</th>
                    <th>Razón social</th>
                    <th>Inversión</th>
                    <th>%Participación</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $totalInversionistas = 0;
                    while(!$rsInversionistas->EOF)
                    {
                        $totalInversionistas++;
                        $idOperacionInversionista = $rsInversionistas->fields["id_operacion_inversionista"];
                ?>
                        <tr>
                            <td align="center"><a href="javascript:editInversionista(<?=$idOperacionInversionista?>);"><img border="0" alt="Editar inversionista" title="Editar inversionista" src="./images/editar.png"></a></td>
                            <td align="center">
                                <?php 
                                    if ($operacion->estado == 3 || $operacion->estado == 1)
                                    {
                                ?>
                                        <a href="javascript:deleteInversionista(<?=$idOperacionInversionista?>);"><img border="0" alt="Eliminar inversionista" title="Eliminar inversionista" src="./images/eliminar.png"></a>
                                <?php 
                                    }
                                    else{
                                        echo "N/D";
                                    }
                                ?>
                            </td>
                            <td><?=$rsInversionistas->fields["razon_social"]?></td>
                            <td><?=formato_moneda($rsInversionistas->fields["valor_inversion"])?></td>
                            <td><?=$rsInversionistas->fields["porcentaje_participacion"]?>%</td>
                        </tr>
                <?php 
                        $rsInversionistas->MoveNext();
                    }
                ?>                
            </tbody>
            </table>
    </div>    
</div>




