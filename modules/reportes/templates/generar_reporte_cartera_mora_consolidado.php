<script>
$(document).ready(function() {
        
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
<table width="100%" border="0" cellpadding="2" cellspancing="2">
<tbody>
<tr>
<?php

   
    //RECORREMOS ANTES EL RESULTADO DE DATOS PARA PODER AGRUPAR LAS FACTURAS CON ABONOS
    $arrDatosReporteEmisor = array();
    $arrDatosReportePagador = array();
    $idOperacion = 0;
    $idReliquidacion = 0;
    while (!$rsDatos->EOF){ 
        
        $diasVencidos = date_diff_custom($rsDatos->fields["fecha_pago"], date("Y-m-d"));        
        
		$emisorIdentificacion = $rsDatos->fields["emisor_identificacion"];        
		$pagadorIdentificacion = $rsDatos->fields["pagador_identificacion"];        
		
		$arrDatosReporteEmisor[$emisorIdentificacion]["tercero"] = $rsDatos->fields["emisor"]; 
		$arrDatosReportePagador[$pagadorIdentificacion]["tercero_pagador"] = $rsDatos->fields["pagador"]; 
        $valorNeto = $rsDatos->fields["valor_neto"];
        
		if ($diasVencidos["d"]<=0){
			$arrDatosReporteEmisor[$emisorIdentificacion]["valor_neto_al_dia"] += $valorNeto; 
			$arrDatosReportePagador[$pagadorIdentificacion]["valor_neto_al_dia"] += $valorNeto; 
		}
		else if ($diasVencidos["d"]>=1 && $diasVencidos["d"]<=30){
			$arrDatosReporteEmisor[$emisorIdentificacion]["valor_neto_1_30"] += $valorNeto; 
			$arrDatosReportePagador[$pagadorIdentificacion]["valor_neto_1_30"] += $valorNeto; 
		}
		else if ($diasVencidos["d"]>30 && $diasVencidos["d"]<=60){
			$arrDatosReporteEmisor[$emisorIdentificacion]["valor_neto_31_60"] += $valorNeto; 
			$arrDatosReportePagador[$pagadorIdentificacion]["valor_neto_31_60"] += $valorNeto; 
		}
		else if ($diasVencidos["d"]>60 && $diasVencidos["d"]<=90){
			$arrDatosReporteEmisor[$emisorIdentificacion]["valor_neto_61_90"] += $valorNeto; 
			$arrDatosReportePagador[$pagadorIdentificacion]["valor_neto_61_90"] += $valorNeto; 
		}
		else if ($diasVencidos["d"]>90 && $diasVencidos["d"]<=120){
			$arrDatosReporteEmisor[$emisorIdentificacion]["valor_neto_91_120"] += $valorNeto; 
			$arrDatosReportePagador[$pagadorIdentificacion]["valor_neto_91_120"] += $valorNeto; 
		}
		else if ($diasVencidos["d"]>120){
			$arrDatosReporteEmisor[$emisorIdentificacion]["valor_neto_120"] += $valorNeto; 
			$arrDatosReportePagador[$pagadorIdentificacion]["valor_neto_120"] += $valorNeto; 
		}
        
        
        $rsDatos->MoveNext();

    }   
    
	
	//DATOS EMISOR
    foreach($arrDatosReporteEmisor as $key=>$arrData){
    	
       
		if ($arrData["valor_neto_1_30"] > 0)
		{       
			$cadena1_30 .="<tr>";
			$cadena1_30 .= "<td>".$arrData["tercero"]."</td>";      
			$cadena1_30 .= "<td align='right'>".formato_moneda($arrData["valor_neto_1_30"])."</td>";    
			$cadena1_30 .= "</tr>";
		}
		
		if ($arrData["valor_neto_31_60"] > 0)
		{       
			$cadena31_60 .= "<tr>";
			$cadena31_60 .= "<td>".$arrData["tercero"]."</td>";   		
			$cadena31_60 .= "<td align='right'>".formato_moneda($arrData["valor_neto_31_60"])."</td>";   
			$cadena31_60 .= "</tr>";
		}
		
		if ($arrData["valor_neto_61_90"] > 0)
		{       
			$cadena61_90 .= "<tr>";
			$cadena61_90 .= "<td>".$arrData["tercero"]."</td>";   			
			$cadena61_90 .= "<td align='right'>".formato_moneda($arrData["valor_neto_61_90"])."</td>";   
			$cadena61_90 .= "</tr>";
		}
		
		if ($arrData["valor_neto_91_120"] > 0)
		{       
			$cadena91_120 .= "<tr>";
			$cadena91_120 .= "<td>".$arrData["tercero"]."</td>";   					
			$cadena91_120 .= "<td align='right'>".formato_moneda($arrData["valor_neto_91_120"])."</td>";
			$cadena91_120 .= "</tr>";
		}		
		
		if ($arrData["valor_neto_120"] > 0)
		{       
			$cadena120 .= "<tr>";
			$cadena120 .= "<td>".$arrData["tercero"]."</td>";   							
			$cadena120 .= "<td align='right'>".formato_moneda($arrData["valor_neto_120"])."</td>";
			$cadena120 .= "</tr>";
		}
		
		$totalGeneral = $arrData["valor_neto_al_dia"] + $arrData["valor_neto_1_30"] + $arrData["valor_neto_31_60"] + $arrData["valor_neto_61_90"] + $arrData["valor_neto_91_120"] + $arrData["valor_neto_120"];		
		$totalAlDia+=$arrData["valor_neto_al_dia"];
		$total1_30+=$arrData["valor_neto_1_30"];
		$total31_60+=$arrData["valor_neto_31_60"];
		$total61_90+=$arrData["valor_neto_61_90"];
		$total91_120+=$arrData["valor_neto_91_120"];
		$total120+=$arrData["valor_neto_120"];
		$totalTotalGeneral+=$totalGeneral;			
		
    }
    
    echo "<td width='50%' valign='top' style='padding:5px;'>";
    echo "<table id='tableDataFacturas' border='1' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<tbody>";
    echo "<tr>";
    echo "<td>Emisor</td>";    
    echo "<td>Total</td>";    
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='2' class='bg-info'><b>&nbsp;1 - 30</b></td>";    
    echo "</tr>";    
    echo $cadena1_30;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-info' align='right'><b>".formato_moneda($total1_30)."</b></td>";    
    echo "</tr>";    
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";     
    echo "<tr>";
    echo "<td colspan='2' class='bg-warning'><b>31 - 60</b></td>";    
    echo "</tr>";    
    echo $cadena31_60;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-warning' align='right'><b>".formato_moneda($total31_60)."</b></td>";    
    echo "</tr>";    
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-warning'><b>61 - 90</b></td>";    
    echo "</tr>";    
    echo $cadena61_90;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-warning' align='right'><b>".formato_moneda($total61_90)."</b></td>";    
    echo "</tr>";     
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-danger'><b>91 - 120</b></td>";    
    echo "</tr>";    
    echo $cadena91_120;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-danger' align='right'><b>".formato_moneda($total91_120)."</b></td>";    
    echo "</tr>";  
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-danger'><b>> 120</b></td>";    
    echo "</tr>";    
    echo $cadena120;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-danger' align='right'><b>".formato_moneda($total120)."</b></td>";    
    echo "</tr>";       
    echo "</tbody>";
    echo "</table>";
    echo "</td>";
    
	//DATOS PAGADOR
	unset($arrData);
	$totalAlDia=0;
	$total1_30=0;
	$total31_60=0;
	$total61_90=0;
	$total91_120=0;
	$total120=0;
	$cadena1_30="";
	$cadena31_60="";
	$cadena61_90="";
	$cadena91_120="";
	$cadena120="";
    foreach($arrDatosReportePagador as $key=>$arrData){
    	
       
		if ($arrData["valor_neto_1_30"] > 0)
		{       
			$cadena1_30 .="<tr>";
			$cadena1_30 .= "<td>".$arrData["tercero_pagador"]."</td>";      
			$cadena1_30 .= "<td align='right'>".formato_moneda($arrData["valor_neto_1_30"])."</td>";    
			$cadena1_30 .= "</tr>";
		}
		
		if ($arrData["valor_neto_31_60"] > 0)
		{       
			$cadena31_60 .= "<tr>";
			$cadena31_60 .= "<td>".$arrData["tercero_pagador"]."</td>";   		
			$cadena31_60 .= "<td align='right'>".formato_moneda($arrData["valor_neto_31_60"])."</td>";   
			$cadena31_60 .= "</tr>";
		}
		
		if ($arrData["valor_neto_61_90"] > 0)
		{       
			$cadena61_90 .= "<tr>";
			$cadena61_90 .= "<td>".$arrData["tercero_pagador"]."</td>";   			
			$cadena61_90 .= "<td align='right'>".formato_moneda($arrData["valor_neto_61_90"])."</td>";   
			$cadena61_90 .= "</tr>";
		}
		
		if ($arrData["valor_neto_91_120"] > 0)
		{       
			$cadena91_120 .= "<tr>";
			$cadena91_120 .= "<td>".$arrData["tercero_pagador"]."</td>";   					
			$cadena91_120 .= "<td align='right'>".formato_moneda($arrData["valor_neto_91_120"])."</td>";
			$cadena91_120 .= "</tr>";
		}		
		
		if ($arrData["valor_neto_120"] > 0)
		{       
			$cadena120 .= "<tr>";
			$cadena120 .= "<td>".$arrData["tercero_pagador"]."</td>";   							
			$cadena120 .= "<td align='right'>".formato_moneda($arrData["valor_neto_120"])."</td>";
			$cadena120 .= "</tr>";
		}
		
		$totalGeneral = $arrData["valor_neto_al_dia"] + $arrData["valor_neto_1_30"] + $arrData["valor_neto_31_60"] + $arrData["valor_neto_61_90"] + $arrData["valor_neto_91_120"] + $arrData["valor_neto_120"];		
		$totalAlDia+=$arrData["valor_neto_al_dia"];
		$total1_30+=$arrData["valor_neto_1_30"];
		$total31_60+=$arrData["valor_neto_31_60"];
		$total61_90+=$arrData["valor_neto_61_90"];
		$total91_120+=$arrData["valor_neto_91_120"];
		$total120+=$arrData["valor_neto_120"];
		$totalTotalGeneral+=$totalGeneral;			
		
    }    
    
	echo "<td width='50%' valign='top' style='padding:5px;'>";
    echo "<table id='tableDataFacturas' border='1' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<tbody>";
    echo "<tr>";
    echo "<td>Pagador</td>";    
    echo "<td>Total</td>";    
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='2' class='bg-info'><b>&nbsp;1 - 30</b></td>";    
    echo "</tr>";    
    echo $cadena1_30;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-info' align='right'><b>".formato_moneda($total1_30)."</b></td>";    
    echo "</tr>";  
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-warning'><b>31 - 60</b></td>";    
    echo "</tr>";    
    echo $cadena31_60;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-warning' align='right'><b>".formato_moneda($total31_60)."</b></td>";    
    echo "</tr>";   
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-warning'><b>61 - 90</b></td>";    
    echo "</tr>";    
    echo $cadena61_90;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-warning' align='right'><b>".formato_moneda($total61_90)."</b></td>";    
    echo "</tr>";      
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-danger'><b>91 - 120</b></td>";    
    echo "</tr>";    
    echo $cadena91_120;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-danger' align='right'><b>".formato_moneda($total91_120)."</b></td>";    
    echo "</tr>";  
    echo "<tr>";
    echo "<td colspan='2' class=''>&nbsp;</td>";    
    echo "</tr>";         
    echo "<tr>";
    echo "<td colspan='2' class='bg-danger'><b>> 120</b></td>";    
    echo "</tr>";    
    echo $cadena120;
    echo "<tr>";
    echo "<td class=''><b>TOTAL:</b></td>";    
    echo "<td class='bg-danger' align='right'><b>".formato_moneda($total120)."</b></td>";    
    echo "</tr>";       
    echo "</tbody>";
    echo "</table>";
    echo "</td>";    
    

?>
</tr>
</tbody>
</table>
</div>