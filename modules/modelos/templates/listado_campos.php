<script>

$(document).ready(function() {
    oTable = $('#listCampos_<?=$idGrupo?>').dataTable({ "paging": false, "bStateSave": true, "bInfo": false, "searching": false,
                    "bSort": false
                });      
});

</script>


<div class="row">
    
        <table id="listCampos_<?=$idGrupo?>" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
        <thead>
            <tr>
                <th>Campo</th>
                <th>Es obligatorio</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                while(!$rsCampos->EOF)
                {
                    $idModeloCampo = $rsCampos->fields["id_modelo_campo"];
                    $idGrupo = $rsCampos->fields["id_grupo"];
            ?>
                    <tr>
                        <td><?=$rsCampos->fields["nombre_campo"]?></td>
                        <td><?=($rsCampos->fields["es_obligatorio"]==1?"Si":"No")?></td>
                        <td align="center">
                            <a href="javascript:editCampo(<?=$idModeloCampo?>,<?=$idGrupo?>);" title="Editar campo" class="link_custom"><i class="fa fa-edit"></i></a>
                            <a href="javascript:deleteCampo(<?=$idModeloCampo?>);" title="Eliminar campo" class="link_custom evento_configuracion"><i class="fa fa-times-circle"></i></a>
                            <a href="javascript:changeOrderCampo(<?=$idModeloCampo?>);" title="Cambiar orden" class="link_custom"><i class="fa fa-arrow-circle-up"></i></a>
                        </td>
                    </tr>
            <?php 
                    $rsCampos->MoveNext();
                }
            ?>                
        </tbody>
        </table>
</div>