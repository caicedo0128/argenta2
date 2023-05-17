<script>

$(document).ready(function() {
    oTable = $('#listSeguimientosOperacion').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});
});

function editSeguimiento(idSeguimiento) {
    loader();
    $("#content_seguimientos").load('admindex.php', { Ajax:true, id_seguimiento_operacion: idSeguimiento, id_operacion: <?=$idOperacion?>, mod: 'operaciones', action:'seguimiento'}, function () {
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
        <h4>Información de seguimientos</h4>
    </div>
    <div id="content_seguimientos" class="" style="clear:both;padding-top:10px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">
				<a class="btn btn-primary btn-sm" href="javascript:editSeguimiento(0)"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>
				</div>
            </div>
            <table id="listSeguimientosOperacion" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Contácto</th>
                    <th>Observaciones</th>
                    <th>Usuario registro</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $totalSeguimientos = 0;
                    while(!$rsSeguimientos->EOF)
                    {
                        $totalSeguimientos++;
                        $idOperacionSeguimiento = $rsSeguimientos->fields["id_operacion_seguimiento"];
                ?>
                        <tr>
                            <td><?=$rsSeguimientos->fields["fecha"]?></td>
                            <td><?=$rsSeguimientos->fields["contacto"]?></td>
                            <td><?=$rsSeguimientos->fields["observaciones"]?></td>
                            <td><?=$rsSeguimientos->fields["nombres"]?> <?=$rsSeguimientos->fields["apellidos"]?></td>
                        </tr>
                <?php
                        $rsSeguimientos->MoveNext();
                    }
                ?>
            </tbody>
            </table>
    </div>
</div>




