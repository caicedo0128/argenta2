<script>
$(document).ready(function() {
    oTableExport = $('#tableDataFacturas').dataTable({ "paging": false, "bStateSave": false, "bInfo": false, "bSort": false, "searching": false });     
});

</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="exportarExcelFromDiv('content-reporte-cartera', 'Reporte cartera');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>
<div id="content-reporte-cartera">
<?php

    echo "<table id='tableDataFacturas' border='0' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>".($tipoReporte==1?"Emisor":"Pagador")."</th>";    
    echo "<th align='center'>Al día</th>";    
    echo "<th align='center'>1 - 30</th>";    
    echo "<th align='center'>31 - 60</th>";    
    echo "<th align='center'>61 - 90</th>";  
    echo "<th align='center'>91 - 120</th>";
    echo "<th align='center'>> 120</th>";
    echo "<th align='center'>Total general</th>";    
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    //RECORREMOS ANTES EL RESULTADO DE DATOS PARA PODER AGRUPAR LAS FACTURAS CON ABONOS
    $arrDatosReporte = array();
    $idOperacion = 0;
    $idReliquidacion = 0;
    while (!$rsDatos->EOF){ 
        
        $diasVencidos = date_diff_custom($rsDatos->fields["fecha_pago"], date("Y-m-d"));        
        
        if ($tipoReporte == 1){
			$terceroIdentificacion = $rsDatos->fields["emisor_identificacion"];        
			$arrDatosReporte[$terceroIdentificacion]["tercero"] = $rsDatos->fields["emisor"]; 
        }
        else{
			$terceroIdentificacion = $rsDatos->fields["pagador_identificacion"];        
			$arrDatosReporte[$terceroIdentificacion]["tercero"] = $rsDatos->fields["pagador"];         
        }
        
        $valorNeto = $rsDatos->fields["valor_neto"];
        
		if ($diasVencidos["d"]<=0)
			$arrDatosReporte[$terceroIdentificacion]["valor_neto_al_dia"] += $valorNeto; 
		else if ($diasVencidos["d"]>=1 && $diasVencidos["d"]<=30)
			$arrDatosReporte[$terceroIdentificacion]["valor_neto_1_30"] += $valorNeto; 
		else if ($diasVencidos["d"]>30 && $diasVencidos["d"]<=60)     
			$arrDatosReporte[$terceroIdentificacion]["valor_neto_31_60"] += $valorNeto; 
		else if ($diasVencidos["d"]>60 && $diasVencidos["d"]<=90)
			$arrDatosReporte[$terceroIdentificacion]["valor_neto_61_90"] += $valorNeto; 
		else if ($diasVencidos["d"]>90 && $diasVencidos["d"]<=120)
			$arrDatosReporte[$terceroIdentificacion]["valor_neto_91_120"] += $valorNeto; 
		else if ($diasVencidos["d"]>120)
			$arrDatosReporte[$terceroIdentificacion]["valor_neto_120"] += $valorNeto; 
        
        
        $rsDatos->MoveNext();

    }
    
    
    foreach($arrDatosReporte as $key=>$arrData){
    	
       
		echo "<tr>";
		echo "<td>".$arrData["tercero"]."</td>";    
		echo "<td align='right'>".formato_moneda($arrData["valor_neto_al_dia"])."</td>";    
		echo "<td align='right'>".formato_moneda($arrData["valor_neto_1_30"])."</td>";    
		echo "<td align='right'>".formato_moneda($arrData["valor_neto_31_60"])."</td>";    
		echo "<td align='right'>".formato_moneda($arrData["valor_neto_61_90"])."</td>";    
		echo "<td align='right'>".formato_moneda($arrData["valor_neto_91_120"])."</td>";
		echo "<td align='right'>".formato_moneda($arrData["valor_neto_120"])."</td>";
		$totalGeneral = $arrData["valor_neto_al_dia"] + $arrData["valor_neto_1_30"] + $arrData["valor_neto_31_60"] + $arrData["valor_neto_61_90"] + $arrData["valor_neto_91_120"] + $arrData["valor_neto_120"];
		echo "<td align='right'>".formato_moneda($totalGeneral)."</td>";    
		echo "</tr>";    
		$totalAlDia+=$arrData["valor_neto_al_dia"];
		$total1_30+=$arrData["valor_neto_1_30"];
		$total31_60+=$arrData["valor_neto_31_60"];
		$total61_90+=$arrData["valor_neto_61_90"];
		$total91_120+=$arrData["valor_neto_91_120"];
		$total120+=$arrData["valor_neto_120"];
		$totalTotalGeneral+=$totalGeneral;
			
		
    }
    
	echo "<tr>";
	echo "<td><b>TOTAL GENERAL:</b></td>";    
	echo "<td align='right'><b>".formato_moneda($totalAlDia)."</b></td>";    
	echo "<td align='right'><b>".formato_moneda($total1_30)."</b></td>";    
	echo "<td align='right'><b>".formato_moneda($total31_60)."</b></td>";    
	echo "<td align='right'><b>".formato_moneda($total61_90)."</b></td>";    
	echo "<td align='right'><b>".formato_moneda($total91_120)."</b></td>";
	echo "<td align='right'><b>".formato_moneda($total120)."</b></td>";
	echo "<td align='right'><b>".formato_moneda($totalTotalGeneral)."</b></td>";    
	echo "</tr>";      
    echo "</tbody>";
    echo "</table>";  
?>
</div>