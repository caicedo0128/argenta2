<script>

$(document).ready(function() {
    oTableExport = $('#listOperaciones').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});      
});

function editOperacion(idOperacion) {
    loader();
    $("#content_operaciones").load('admindex.php', { Ajax:true, id_operacion: idOperacion, mod: 'operaciones', action:'operacion'}, function () {
        loader();
        $("#titulo_reporte_operaciones").hide();
        $("#content_reporte_operaciones").hide();
        goObjHtml("content_general", 70);
    });
}


function cargarOperaciones() {
    loader();
    $("#titulo_reporte_operaciones").show();
    $("#content_reporte_operaciones").show();

    generarReporteInversiones();

     loader();
}

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Resultado de la busqueda</h4>
    </div>  
    <div id="content_operaciones" class="" style="clear:both;padding-top:15px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">
                    <a class="btn btn-primary btn-sm" href="javascript:editOperacion(0)"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>                                      
                    <a href="javascript:;" title="Exportar" onclick="exportarReporteInversiones();" class="btn btn-success btn-sm"><i class="fa fa-download fa-lg"></i>Exportar</a>
                </div>
            </div>
            <table id="listOperaciones" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Editar</th>
                    <th>Id. Operación</th>
                    <th>Fecha operación</th>
                    <th>Emisor</th>
                    <th>Pagador</th>
                    <th>Titulos negociados</th>
                    <th>Valor giro final</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    while(!$rsOperaciones->EOF)
                    {
                        $idOperacion = $rsOperaciones->fields["id_operacion"];
                        $fechaOperacion = $rsOperaciones->fields["fecha_operacion"];
                        $idEstado = $rsOperaciones->fields["estado"];
                        $numero_factura = $rsOperaciones->fields["num_factura"];
                        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$fechaOperacion);
                        $arrFacturasReliquidacion = $factura->getArrFacturasPorOperacionReliquidacion($idOperacion,$fechaOperacion);						
                ?>
                        <tr>
                            <td align="center"><a href="javascript:editOperacion(<?=$idOperacion?>);"><img border="0" alt="Editar operación" title="Editar operación" src="./images/editar.png"></a></td>
                            <td><?=$idOperacion?></td>
                            <td><?=$fechaOperacion?></td>
                            <td><?=$rsOperaciones->fields["emisor"]?></td>
                            <td><?=$rsOperaciones->fields["pagador"]?></td>
                            <td>
                            <?php
                                if (Count($arrFacturas) > 0){                                 
                                    echo implode(" - ",($arrFacturas["fac"]));
                                }
                                else
                                    echo "Sin facturas cargadas";
                            ?>
                            </td>
                            <td><?=formato_moneda($rsOperaciones->fields["valor_giro_final"])?></td>
                            <td><?=$this->arrEstados[$rsOperaciones->fields["estado"]]?></td>
                        </tr>
                <?php 
                        $rsOperaciones->MoveNext();
                    }
                ?>                
            </tbody>
            </table>
    </div>    
</div>



