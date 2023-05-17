<script>
$(document).ready(function() {
    oTableExport = $('#tableDataFacturas').dataTable({ "paging": false, "bStateSave": false, "bInfo": false, "bSort": false, "searching": false });     
});

</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="exportarExcel('tableDataFacturas', 'Reporte Cartera');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>

<?php

    echo "<table id='tableDataFacturas' border='0' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Emisor</th>";    
    echo "<th>0-30</th>";    
    echo "<th>31-60</th>";    
    echo "<th>61-90</th>";    
    echo "<th>91-120</th>";    
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
        $arrDiasVencidos = date_diff_custom($rsDatos->fields["fecha_pago"], date("Y-m-d"));
        $diasVencidos = $arrDiasVencidos["d"];
        
        //GUARDAMOS EL VALOR DE COBRO
        if ($reporteInversionista)
        	$valorAcumular= $rsDatos->fields["valor_participacion"];
		else        	
        	$valorAcumular= $rsDatos->fields["valor_giro_final"];
        	
        if ($rsDatos->fields["valor_pago"] > 0){
        	$valorAcumular = $rsDatos->fields["valor_pago"];
        }

        
        if ($idReliquidacion != ""){
        	if ($diasVencidos >= -30 && $diasVencidos <=0){
        		$totalValorGiroFinal0_30 += $valorAcumular;    
				$totalValorGiroFinal31_60 = 0; 
				$totalValorGiroFinal_61_90 = 0; 
				$totalValorGiroFinal_91_120 = 0;         		
        	}
        	else if ($diasVencidos >= -60 && $diasVencidos <=-31){	
        		$totalValorGiroFinal31_60 += $valorAcumular;
				$totalValorGiroFinal0_30 = 0; 
				$totalValorGiroFinal_61_90 = 0; 
				$totalValorGiroFinal_91_120 = 0;         		
        	}
        	else if ($diasVencidos >= -90 && $diasVencidos <=-61){
        		$totalValorGiroFinal_61_90 += $valorAcumular;
				$totalValorGiroFinal0_30 = 0; 
				$totalValorGiroFinal31_60 = 0;  
				$totalValorGiroFinal_91_120 = 0;         		
        	}
        	else if ($diasVencidos >= -120 && $diasVencidos <=-91){	
        		$totalValorGiroFinal_91_120 += $valorAcumular; 
				$totalValorGiroFinal0_30 = 0; 
				$totalValorGiroFinal31_60 = 0; 
				$totalValorGiroFinal_61_90 = 0;         		
        	}
        }
        else{
        	if ($diasVencidos >= -30 && $diasVencidos <=0){
        		$totalValorGiroFinal0_30 = $valorAcumular;  
				$totalValorGiroFinal31_60 = 0; 
				$totalValorGiroFinal_61_90 = 0; 
				$totalValorGiroFinal_91_120 = 0;        		
        	}
        	else if ($diasVencidos >= -60 && $diasVencidos <=-31){	
        		$totalValorGiroFinal31_60 = $valorAcumular;
				$totalValorGiroFinal0_30 = 0; 
				$totalValorGiroFinal_61_90 = 0; 
				$totalValorGiroFinal_91_120 = 0;        		
        	}
        	else if ($diasVencidos >= -90 && $diasVencidos <=-61){	
        		$totalValorGiroFinal_61_90 = $valorAcumular;
				$totalValorGiroFinal0_30 = 0; 
				$totalValorGiroFinal31_60 = 0;  
				$totalValorGiroFinal_91_120 = 0;         		
        	}
        	else if ($diasVencidos >= -120 && $diasVencidos <=-91){	
        		$totalValorGiroFinal_91_120 = $valorAcumular;
				$totalValorGiroFinal0_30 = 0; 
				$totalValorGiroFinal31_60 = 0; 
				$totalValorGiroFinal_61_90 = 0;          		
        	}
        }
        
        $arrDatosReporte[$i]["id_operacion"] = $rsDatos->fields["id_operacion"];
        $arrDatosReporte[$i]["id_reliquidacion"] = $idReliquidacion;                 
        $arrDatosReporte[$i]["emisor"] = $rsDatos->fields["emisor"];       
        $arrDatosReporte[$i]["valor_giro_final_0_30"] = $totalValorGiroFinal0_30;
        $arrDatosReporte[$i]["valor_giro_final_31_60"] = $totalValorGiroFinal31_60;
        $arrDatosReporte[$i]["valor_giro_final_61_90"] = $totalValorGiroFinal_61_90;
        $arrDatosReporte[$i]["valor_giro_final_91_120"] = $totalValorGiroFinal_91_120;  
        $arrDatosReporte[$i]["estado"] = $rsDatos->fields["estado"];
        $i++;
        
        $idOperacionAux = $rsDatos->fields["id_operacion"];
        $rsDatos->MoveNext();
        
        //SI LA SIGUIENTE RELIQUIDACION ES DIFERENTE SETEAMOS LOS VALORES
        if ($idReliquidacion != $rsDatos->fields["id_reliquidacion"]){
            $totalValorGiroFinal0_30 = 0; 
            $totalValorGiroFinal31_60 = 0; 
            $totalValorGiroFinal_61_90 = 0; 
            $totalValorGiroFinal_91_120 = 0;          
        }
    }
        
    //IMPRIMIMOS EL REPORTE BASADO EN EL ARREGLO GENERADO ANTERIORMENTE
    $emisorAux = "";
    for ($i=0; $i<Count($arrDatosReporte) ; $i++){
        
        $emisor = $arrDatosReporte[$i]["emisor"];
        
        //DETERINAMOS SI SE DEBE IMPRIMIR EL REGISTRO DEPENDIENTE SI ES RELIQUIDACION
        //RECORDAMOS QUE PUEDEN HABER VARIOS REGISTROS DE UNA SOLA RELIQUIDACION
        //PERO SE DEBE IMPRIMIR SOLO ULTIMO REGISTRO POR QUE ESTE TENDRA LOS ACUMULADOS
        if ($emisor != $arrDatosReporte[($i  + 1)]["emisor"] && ($totalValorGiroFinal0_30 > 0 || $totalValorGiroFinal31_60 > 0 || $totalValorGiroFinal_61_90 > 0 || $totalValorGiroFinal_91_120 > 0)){

            echo "<tr>";            
            echo "<td>".$emisor."</td>";  
            echo "<td align='right'>".formato_moneda($totalValorGiroFinal0_30)."</td>";
            echo "<td align='right'>".formato_moneda($totalValorGiroFinal31_60)."</td>";  
            echo "<td align='right'>".formato_moneda($totalValorGiroFinal_61_90)."</td>";  
            echo "<td align='right'>".formato_moneda($totalValorGiroFinal_91_120)."</td>";  
            echo "</tr>";
			
			//ACUMULAMOS TOTALES
			$total0_30 += $totalValorGiroFinal0_30;
			$total31_60 += $totalValorGiroFinal31_60;
			$total61_90 += $totalValorGiroFinal_61_90;
			$total91_120 += $totalValorGiroFinal_91_120;            
            
			$totalValorGiroFinal0_30 = 0; 
            $totalValorGiroFinal31_60 = 0; 
            $totalValorGiroFinal_61_90 = 0; 
            $totalValorGiroFinal_91_120 = 0;             
                       
        }       

		$totalValorGiroFinal0_30 += $arrDatosReporte[$i]["valor_giro_final_0_30"]; 
		$totalValorGiroFinal31_60 += $arrDatosReporte[$i]["valor_giro_final_31_60"]; 
		$totalValorGiroFinal_61_90 += $arrDatosReporte[$i]["valor_giro_final_61_90"]; 
		$totalValorGiroFinal_91_120 += $arrDatosReporte[$i]["valor_giro_final_91_120"]; 	
        
    }
    echo "</tbody>";
    echo "<tfoot>";
	echo "<tr>";            
	echo "<td align='right'><b>TOTALES:</b></td>";  
	echo "<td align='right'><b>".formato_moneda($total0_30)."</b></td>";
	echo "<td align='right'><b>".formato_moneda($total31_60)."</b></td>";  
	echo "<td align='right'><b>".formato_moneda($total61_90)."</b></td>";  
	echo "<td align='right'><b>".formato_moneda($total91_120)."</b></td>";  
	echo "</tr>";  
	echo "</tfoot>";
    echo "</table>";

?>