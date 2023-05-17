<script>
$(document).ready(function() {
    oTableExport = $('#tableDataMovimiento').dataTable({ "pagingType": "full_numbers", "bStateSave": true, "bInfo": false, "paging": false, "bSort": false, "searching": false });     
});

</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="exportarExcel('tableDataMovimiento', 'Reporte Movimientos');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>

<?php

    echo "<table id='tableDataMovimiento' border='1' class='table table-striped table-bordered dt-responsive nowrap' cellspacing='0' style='width:100%;'>";
    echo "<thead>";
    echo "<th>Días</th>"; 
    echo "<th>Fecha</th>";    
    echo "<th>Inversión / Pago</th>";
    echo "<th>Interes</th>";
    echo "<th>Interes acumulado</th>";   
    echo "<th>Balance</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $fechaAnterior = "";

    $balanceInicial = $arrDatosIniciales["valor"];
    $fechaAnterior = $arrDatosIniciales["fecha"];
    $balanceAnterior = $balanceInicial;
    $balance = $balanceInicial;
    
    //IMPRIMIMOS EL REGISTRO INICIAL
    echo "<tr>";
    echo "<td align='center'>-</td>"; 
    echo "<td align='center'>".$fechaAnterior."</td>";    
    echo "<td>-</td>";
    echo "<td>-</td>";
    echo "<td>-</td>";     
    echo "<td>".formato_moneda($balanceInicial)."</td>";
    echo "</tr>";         
    
    while (!$rsDatos->EOF){
        
        $valorMovimiento = $rsDatos->fields["valor_mov"];
           
        $fechaActual = $rsDatos->fields["fecha_mov"];
        $dias = 0;

        if ($fechaAnterior != ""){
            $arrDiff = date_diff_custom($fechaAnterior, $fechaActual);
            $dias = $arrDiff["d"];
        }
        
        $balance += $valorMovimiento;            
        
        $tasa = (13.148 / 100) / 365;
        $interes = $balanceAnterior * $dias * $tasa;
        $interesAcumulado += $interes;
        
        echo "<tr>";
        echo "<td align='center'>".$dias."</td>"; 
        echo "<td align='center'>".$rsDatos->fields["fecha_mov"]."</td>";    
        echo "<td>".formato_moneda($valorMovimiento)."</td>";
        echo "<td>".formato_moneda($interes)."</td>";
        echo "<td>".formato_moneda($interesAcumulado)."</td>";     
        echo "<td>".formato_moneda($balance)."</td>";
        echo "</tr>";         
        $fechaAnterior = $rsDatos->fields["fecha_mov"];
        $balanceAnterior = $balance;
        $rsDatos->MoveNext();
    }
    echo "</tbody>";
    echo "</table>";

?>