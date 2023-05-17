<script>
$(document).ready(function() {
    oTableExport = $('#tableDataFacturas').dataTable({ "paging": false, "bStateSave": false, "bInfo": false, "bSort": false, "searching": false });     
});

</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="exportarExcel('tableDataFacturas', 'Reporte Facturas Vigentes');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>

<?php

    echo "<table id='tableDataFacturas' border='0' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Emisor</th>";    
    echo "<th>Pagador</th>";
    echo "<th>Fecha operación</th>";
    echo "<th>No. Factura</th>";
    echo "<th>Fecha pago pactada</th>";
    echo "<th>Días vencidos</th>";    
    echo "<th>Valor girado</th>";
    echo "<th>Valor futuro</th>";
    echo "<th>Nuevo Saldo</th>";
    echo "<th>Estado</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    //RECORREMOS ANTES EL RESULTADO DE DATOS PARA PODER AGRUPAR LAS FACTURAS CON ABONOS
    $arrDatosReporte = array();
    $i = 0;
    $idReliquidacionAux = 0;
    $idOperacionAux = 0;
    while (!$rsDatos->EOF){ 
        
        $idReliquidacion = $rsDatos->fields["id_reliquidacion"];
        
        if ($idReliquidacion != ""){

            $numFacturas = $rsDatos->fields["num_factura"] . " - " . $numFacturas;
            $totalValorGiroFinal += $rsDatos->fields["valor_giro_final"]; 
            $totalValorFuturo += $rsDatos->fields["valor_futuro"]; 
        }
        else{
            $numFacturas = $rsDatos->fields["num_factura"];
            $totalValorGiroFinal = $rsDatos->fields["valor_giro_final"]; 
            $totalValorFuturo = $rsDatos->fields["valor_futuro"];  
        }
        
        $arrDatosReporte[$i]["id_operacion"] = $rsDatos->fields["id_operacion"];
        $arrDatosReporte[$i]["id_reliquidacion"] = $idReliquidacion;                 
        $arrDatosReporte[$i]["emisor"] = $rsDatos->fields["emisor"];
        $arrDatosReporte[$i]["pagador"] = $rsDatos->fields["pagador"];
        $arrDatosReporte[$i]["fecha_operacion"] = $rsDatos->fields["fecha_operacion"];
        $arrDatosReporte[$i]["fecha_pago"] = $rsDatos->fields["fecha_pago"];
        $arrDatosReporte[$i]["num_factura"] = $numFacturas;
        $arrDatosReporte[$i]["valor_giro_final"] = $totalValorGiroFinal;
        $arrDatosReporte[$i]["valor_futuro"] = $totalValorFuturo;
        $arrDatosReporte[$i]["fecha_real_pago_abono"] = $rsDatos->fields["fecha_real_pago_abono"];
        $arrDatosReporte[$i]["valor_obligacion"] = $rsDatos->fields["valor_obligacion_abono"];
        $arrDatosReporte[$i]["interes_mora"] = $rsDatos->fields["interes_mora"];
        $arrDatosReporte[$i]["nuevo_valor_obligacion"] = $rsDatos->fields["nuevo_valor_obligacion"];
        $arrDatosReporte[$i]["valor_pago"] = $rsDatos->fields["valor_pago"];
        $arrDatosReporte[$i]["estado"] = $rsDatos->fields["estado"];
        $i++;
        
        $idOperacionAux = $rsDatos->fields["id_operacion"];
        $rsDatos->MoveNext();
        
        //SI LA SIGUIENTE RELIQUIDACION ES DIFERENTE SETEAMOS LOS VALORES
        if ($idReliquidacion != $rsDatos->fields["id_reliquidacion"]){
            $numFacturas = "";
            $totalValorGiroFinal = 0; 
            $totalValorFuturo = 0;          
        }
    }
        
    //IMPRIMIMOS EL REPORTE BASADO EN EL ARREGLO GENERADO ANTERIORMENTE
    $idReliquidacionAux = 0;
    for ($i=0; $i<Count($arrDatosReporte) ; $i++){
        
        $idReliquidacion = $arrDatosReporte[$i]["id_reliquidacion"];
        
        //DETERINAMOS SI SE DEBE IMPRIMIR EL REGISTRO DEPENDIENTE SI ES RELIQUIDACION
        //RECORDAMOS QUE PUEDEN HABER VARIOS REGISTROS DE UNA SOLA RELIQUIDACION
        //PERO SE DEBE IMPRIMIR SOLO ULTIMO REGISTRO POR QUE ESTE TENDRA LOS ACUMULADOS
        $diasVencidos = date_diff_custom($arrDatosReporte[$i]["fecha_pago"], date("Y-m-d"));
        if ($diasVencidos["d"] > 0){
			if ($idReliquidacion != $arrDatosReporte[($i  + 1)]["id_reliquidacion"] || $idReliquidacion == ""){

				echo "<tr>";
				echo "<td>".$arrDatosReporte[$i]["emisor"]."</td>";    
				echo "<td>".$arrDatosReporte[$i]["pagador"]."</td>";
				echo "<td>".$arrDatosReporte[$i]["fecha_operacion"]."</td>";                
				echo "<td>".$arrDatosReporte[$i]["num_factura"]."</td>";
				echo "<td>".$arrDatosReporte[$i]["fecha_pago"]."</td>";  
				echo "<td>".$diasVencidos["d"]."</td>";  
				echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_giro_final"])."</td>";
				echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_futuro"])."</td>";  
				$nuevoValorObligacion = $arrDatosReporte[$i]["valor_obligacion"] + $arrDatosReporte[$i]["interes_mora"]; 
				$capital = $arrDatosReporte[$i]["valor_pago"] - $arrDatosReporte[$i]["interes_mora"]; 
				echo "<td>".formato_moneda($arrDatosReporte[$i]["nuevo_valor_obligacion"])."</td>"; // ES EL NUEVO SALDO
				echo "<td>".($arrDatosReporte[$i]["estado"] == 1?"ABIERTA":"ABIERTA CON ABONOS")."</td>";
				echo "</tr>";      
			}                  
        }
    }
    echo "</tbody>";
    echo "</table>";

?>