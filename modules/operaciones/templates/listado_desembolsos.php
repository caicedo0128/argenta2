<script>

$(document).ready(function() {
    oTable = $('#listDesembolsos').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});
});

function editDesembolso(idDesembolso, idOperacion) {
    loader();
    $("#content_desembolsos").load('admindex.php', { Ajax:true, id_desembolso: idDesembolso, mod: 'operaciones', action:'desembolso', id_operacion:idOperacion}, function () {
        loader();
    });
}

function deleteDesembolso(idDesembolso, idOperacion) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=operaciones&action=eliminarDesembolso&id_desembolso=" + idDesembolso
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacci�n exitosa. Espere por favor...");
                cargarDesembolsos();
            }
    });
}
</script>
<?php
    //IMPRIMIMOS EL SUMARIO DE LA OPERACION
    $this->sumarioOperacion($idOperacion);
?>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Informacion de desembolsos</h4>
    </div>
    <div id="content_desembolsos" class="" style="clear:both;padding-top:10px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">
                    <?php
                        if (($operacion->estado == 3 || $operacion->estado == 1 || $operacion->estado == 6) && $_SESSION["profile_text"]!="Cliente"){
                    ?>
                        <?
                            if ($appObj->tienePermisosAccion(array("desembolso_agregar_terceros")))
        					{
                                //Opcion a ejecutar si tiene el permiso
                               echo "<a class='btn btn-primary btn-sm' href='javascript:editDesembolso(0,<?=$idOperacion?>)'><i class='fa fa-plus-square fa-lg'></i> Agregar</a>";
        					}
                        ?>
                    
                    <?php
                        }
                    ?>
                </div>
            </div>
            <table id="listDesembolsos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Opciones</th>
                    <th>Tipo</th>
                    <th>Tercero</th>
                    <th>Fecha desembolso</th>
                    <th>Valor</th>
                    <th>Banco</th>
                    <th>Nro cuenta</th>
                    <th>Tipo cuenta</th>
                    <th>Soporte</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    while(!$rsDesembolsos->EOF)
                    {
                        $idDesembolso = $rsDesembolsos->fields["id_desembolso"];
                ?>
                        <tr>
                            <td align="center">
                                <?php
                                	 if ($_SESSION["profile_text"] != "Cliente")
                                        if ($appObj->tienePermisosAccion(array("desembolso_editar_terceros")))
                                            {
                                                //Opcion a ejecutar si tiene el permiso
                                            echo "<a href=\"javascript:editDesembolso(".$idDesembolso.",".$idOperacion.");\"><img border=\"0\" alt=\"Editar desembolso\" title=\"Editar desembolso\" src=\"./images/editar.png\"></a>";
                                            }

                                ?>
                                <?php
                                    if (($operacion->estado == 3 || $operacion->estado == 6) && $_SESSION["profile_text"] != "Cliente")
                                    
                                    if ($appObj->tienePermisosAccion(array("desembolso_eliminar_terceros")))
                                    {
                                        //Opcion a ejecutar si tiene el permiso
                                        echo "<a href=\"javascript:deleteDesembolso(".$idDesembolso.",".$idOperacion.");\"><img border=\"0\" alt=\"Eliminar desembolso\" title=\"Eliminar desembolso\" src=\"./images/eliminar.png\"></a>";
                                    }
                                    else
                                        echo "N/D";
                                ?>
                            </td>
                            <td align="center">
                            	<?php

                            		echo ($rsDesembolsos->fields["tipo_registro"] == "1"?"<span class='label label-success'>Desembolso</span>":"<span class='label label-danger'>Remanente</span>");
                            		if ($rsDesembolsos->fields["tipo_registro"] == "2")
                            			echo "<br/><br/><span class='label label-info' style=''>Id rel.".$rsDesembolsos->fields["id_reliquidacion"]."</span>";
                            	?>
                            </td>
                            <td><?=($rsDesembolsos->fields["tercero"] != ""?$rsDesembolsos->fields["tercero"]:$rsDesembolsos->fields["razon_social"])?></td>
                            <td><?=$rsDesembolsos->fields["fecha_desembolso"]?></td>
                            <td align="right"><?=formato_moneda($rsDesembolsos->fields["valor"])?></td>
                            <td><?=$rsDesembolsos->fields["banco"]?></td>
                            <td><?=$rsDesembolsos->fields["nro_cuenta"]?></td>
                            <td><?=($rsDesembolsos->fields["tipo_cuenta"]==1?"Ahorros":"Corriente")?></td>
                            <td>
                            	<?php
									if ($rsDesembolsos->fields["archivo_desembolso"] != "")
										echo "<a href='".$this->rutaArchivosDesembolsos."/".$rsDesembolsos->fields["archivo_desembolso"]."' target='_blank' title='Ver soporte'>Ver soporte</a>";
									else
										echo "No disponible a�n";
				        		?>

                            </td>
                        </tr>
                <?php
                        $rsDesembolsos->MoveNext();
                    }
                ?>
            </tbody>
            </table>
    </div>
</div>



