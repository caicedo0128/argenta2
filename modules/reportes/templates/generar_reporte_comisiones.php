<script>
$(document).ready(function() {
    oTableExport = $('#tableDataComisiones').dataTable({ "paging": false, "bStateSave": true, "bInfo": true, "bSort": false });
    $('#fecha_pago_comision').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function gestionarPago(idOperacion){

    loader();
    $("#content_detalle_pago").load('admindex.php', { Ajax:true, id_operacion_pago: idOperacion, mod: 'operaciones', action:'formActualizarComision'}, function () {
        $('#modalDetalle').modal('show');
        loader();
    });
}


</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="exportarExcel('tableDataComisiones', 'Reporte Comisiones');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>

<?php

    echo "<table id='tableDataComisiones' border='1' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:11px;'>";
    echo "<thead>";
    echo "<th>Ejecutivo</th>";
    echo "<th>Emisor</th>";
    echo "<th>Pagador</th>";
    echo "<th>No. Factura</th>";
    echo "<th>Fecha operación</th>";
    echo "<th>Facturas negociadas</th>";
    echo "<th>Estado operación</th>";
    echo "<th>Valor neto</th>";
    echo "<th>Valor giro final</th>";
    echo "<th>Margen Argenta</th>";
    echo "<th>Comision</th>";
    echo "<th>Vr. Comisión</th>";
    echo "<th>Fecha pago comisión</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while (!$rsDatos->EOF){

        $idOperacion = $rsDatos->fields["id_operacion"];
        $fechaOperacion = $rsDatos->fields["fecha_operacion"];

        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$fechaOperacion);
        echo "<tr>";
        echo "<td>".$rsDatos->fields["ejecutivo"]."</td>";
        echo "<td>".$rsDatos->fields["emisor"]."</td>";
        echo "<td>".$rsDatos->fields["pagador"]."</td>";
        echo "<td>".$rsDatos->fields["num_factura"]."</td>";
        echo "<td>".$rsDatos->fields["fecha_operacion"]."</td>";
        echo "<td>";
            if (Count($arrFacturas) > 0){
                echo implode(" - ",($arrFacturas["fac"]));
            }
            else
                echo "Sin facturas cargadas";

        echo "</td>";
        echo "<td>".$this->arrEstados[$rsDatos->fields["estado"]]."</td>";
        echo "<td align='right'>".formato_moneda($rsDatos->fields["valor_neto"])."</td>";
        echo "<td align='right'>".formato_moneda($rsDatos->fields["valor_giro_final"])."</td>";
        echo "<td align='right'>".formato_moneda($rsDatos->fields["total_margen_reli"])."</td>";
        echo "<td align='center'>".$rsDatos->fields["comision"]."%</td>";
        $comisionEjecutivo = ($rsDatos->fields["total_margen_reli"] * $rsDatos->fields["comision"]) / 100;
        echo "<td align='right'>".formato_moneda($comisionEjecutivo)."</td>";
        echo "<td align='center'>";

            if ($rsDatos->fields["fecha_pago_comision"]!="" && $rsDatos->fields["fecha_pago_comision"]!="0000-00-00")
                echo $rsDatos->fields["fecha_pago_comision"];
            else{
                echo "<div id='ope_".$rsDatos->fields["id_operacion"]."'>Sin pagar</div><a href='javascript:gestionarPago(".$rsDatos->fields["id_operacion"].");' title='Gestionar pago'><i class='fa fa-money fa-2x'></i></a>";
            }

        echo "</td>";
        echo "</tr>";

        $totalValorNeto += $rsDatos->fields["valor_neto"];
        $totalGiroFinal += $rsDatos->fields["valor_giro_final"];
        $totalMargenArgenta += $rsDatos->fields["total_margen_reli"];
        $totalComisionEjecutivo += $comisionEjecutivo;

        $rsDatos->MoveNext();
    }

    echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td><b>TOTAL:</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalValorNeto)."</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalGiroFinal)."</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalMargenArgenta)."</b></td>";
    echo "<td></td>";
    echo "<td align='right'><b>".formato_moneda($totalComisionEjecutivo)."</b></td>";
    echo "<td></td>";
    echo "</tr>";

    echo "</tbody>";
    echo "</table>";

?>

<div id="modalDetalle" class="modal fade" role="dialog" aria-labelledby="modalDetalle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Realizar pago comisión</h4>
            </div>
            <div class="modal-body " id="content_detalle_pago">

            </div>
        </div>
    </div>
</div>