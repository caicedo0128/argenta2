<script>
$(document).ready(function() {
    oTableExport = $('#tableDataContable').dataTable({ "pagingType": "full_numbers", "bStateSave": true, "bInfo": false });   
    $('#fecha_pago_comision').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function detalleContable(idOperacion){
    loader();
    $("#content_detalle_contable").load('admindex.php', { Ajax:true, id_operacion: idOperacion, mod: 'reportes', action:'generarReporteDetalleContable'}, function () {
        $('#modalDetalle').modal('show');
        loader();
    });
}    
</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
    </div>
</div>

<?php

    echo "<table id='tableDataContable' border='1' class='table table-striped table-bordered dt-responsive nowrap' cellspacing='0' style='width:100%;'>";
    echo "<thead>";
    echo "<th>Nro operación</th>"; 
    echo "<th>Emisor</th>";    
    echo "<th>Pagador</th>";
    echo "<th>No. Factura</th>";
    echo "<th>Fecha operación</th>";   
    echo "<th>Valor neto</th>";
    echo "<th>Valor giro final</th>";
    echo "<th>Estado operación</th>";
    echo "<th>Fecha de pago</th>";
    echo "<th>Opciones</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while (!$rsDatos->EOF){
        
        echo "<tr>";
        echo "<td>".$rsDatos->fields["id_operacion"]."</td>"; 
        echo "<td>".$rsDatos->fields["emisor"]."</td>";    
        echo "<td>".$rsDatos->fields["pagador"]."</td>";
        echo "<td>".$rsDatos->fields["num_factura"]."</td>";
        echo "<td>".$rsDatos->fields["fecha_operacion"]."</td>";     
        echo "<td>".formato_moneda($rsDatos->fields["valor_neto"])."</td>";
        echo "<td>".formato_moneda($rsDatos->fields["valor_giro_final"])."</td>";    
        echo "<td>".$operaciones->arrEstados[$rsDatos->fields["estado_operacion"]]."</td>";       
        echo "<td>".$rsDatos->fields["fecha_pago_operacion"]."</td>"; 
        echo "<td align='center'><a href=\"javascript:detalleContable(".$rsDatos->fields["id_operacion"].");\"><img border=\"0\" alt=\"Detalle contable\" title=\"Detalle contable\" src=\"./images/ver.png\"></a></td>"; 
        echo "</tr>";         
        $rsDatos->MoveNext();
    }
    echo "</tbody>";
    echo "</table>";

?>
<div id="modalDetalle" class="modal fade" role="dialog" aria-labelledby="modalDetalle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Detalle contable</h4>
            </div>
            <div class="modal-body" id="content_detalle_contable">

            </div>
        </div>
    </div>
</div>

