<script>
$(document).ready(function() {
    oTableExport = $('#tableDataFacturas').dataTable({ "paging": false, "bStateSave": false, "bInfo": false, "bSort": false, "searching": false });     
});

</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="generarReporteFacturas('EXP');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>

<?php

    echo "<table id='tableDataFacturas' border='0' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Emisor</th>";    
    echo "<th>Dirección</th>";    
    echo "<th>Representante legal</th>";    
    echo "<th>Persona contacto</th>";    
    echo "<th>Cargo</th>";    
    echo "<th>Pagador</th>";
    echo "<th>No. operación</th>";
    echo "<th>Fecha operación</th>";
    echo "<th>No. Factura</th>";
    echo "<th>Fecha de emisión</th>";
    echo "<th>Fecha de vencimiento</th>";
    echo "<th>Fecha pago pactada</th>";
    echo "<th>Valor bruto</th>";
    echo "<th>Días vencidos</th>";    
    echo "<th>Calificación</th>";    
    if ($filtroInversionista)
    	echo "<th>Valor participación</th>";    	
    echo "<th>Valor girado</th>";
    echo "<th>Tasa</th>";
    echo "<th>Factor</th>";
    echo "<th>Valor futuro</th>";
    echo "<th>Fecha abono</th>";
    echo "<th>Nuevo vr. obligación</th>";
    echo "<th>Abono</th>";
    echo "<th>Capital</th>";
    echo "<th>Intereses</th>";
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

            $numFacturas = $rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"] . " - " . $numFacturas;
            $totalValorGiroFinal += $rsDatos->fields["valor_giro_final"]; 
            $totalValorFuturo += $rsDatos->fields["valor_futuro"]; 
        }
        else{
            $numFacturas = $rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"];
            $totalValorGiroFinal = $rsDatos->fields["valor_giro_final"]; 
            $totalValorFuturo = $rsDatos->fields["valor_futuro"];  
        }
        
        $arrDatosReporte[$i]["id_operacion"] = $rsDatos->fields["id_operacion"];
        $arrDatosReporte[$i]["id_reliquidacion"] = $idReliquidacion;                 
        $arrDatosReporte[$i]["emisor"] = $rsDatos->fields["emisor"];
        $arrDatosReporte[$i]["direccion"] = $rsDatos->fields["direccion"];
        $arrDatosReporte[$i]["representante_legal"] = $rsDatos->fields["representante_legal"];
        $arrDatosReporte[$i]["persona_contacto"] = $rsDatos->fields["persona_contacto"];
        $arrDatosReporte[$i]["cargo"] = $rsDatos->fields["cargo"];
        $arrDatosReporte[$i]["pagador"] = $rsDatos->fields["pagador"];
        $arrDatosReporte[$i]["fecha_operacion"] = $rsDatos->fields["fecha_operacion"];
        $arrDatosReporte[$i]["id_operacion"] = $rsDatos->fields["id_operacion"];
        $arrDatosReporte[$i]["tasa_inversionista"] = $rsDatos->fields["tasa_inversionista"];
        $arrDatosReporte[$i]["factor"] = $rsDatos->fields["factor"];
        $arrDatosReporte[$i]["fecha_pago"] = $rsDatos->fields["fecha_pago"];
        $arrDatosReporte[$i]["valor_bruto"] = $rsDatos->fields["valor_bruto"];
        $arrDatosReporte[$i]["num_factura"] = $numFacturas;
        $arrDatosReporte[$i]["fecha_emision"] =  $rsDatos->fields["fecha_emision"];
        $arrDatosReporte[$i]["fecha_vencimiento"] =  $rsDatos->fields["fecha_vencimiento_factura"];
        $arrDatosReporte[$i]["valor_participacion"] = $rsDatos->fields["valor_participacion"];
        $arrDatosReporte[$i]["valor_giro_final"] = $totalValorGiroFinal;
        $arrDatosReporte[$i]["valor_futuro"] = $totalValorFuturo;
        $arrDatosReporte[$i]["fecha_real_pago_abono"] = $rsDatos->fields["fecha_real_pago_abono"];
        $arrDatosReporte[$i]["valor_obligacion"] = $rsDatos->fields["valor_obligacion_abono"];
        $arrDatosReporte[$i]["interes_mora"] = $rsDatos->fields["interes_mora"];
        $arrDatosReporte[$i]["nuevo_valor_obligacion"] = ($rsDatos->fields["nuevo_valor_obligacion"] > 0?$rsDatos->fields["nuevo_valor_obligacion"]:$totalValorFuturo);
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
        if ($idReliquidacion != $arrDatosReporte[($i  + 1)]["id_reliquidacion"] || $idReliquidacion == ""){
        
            echo "<tr>";
            echo "<td>".$arrDatosReporte[$i]["emisor"]."</td>";    
            echo "<td>".$arrDatosReporte[$i]["direccion"]."</td>";    
            echo "<td>".$arrDatosReporte[$i]["representante_legal"]."</td>";    
            echo "<td>".$arrDatosReporte[$i]["persona_contacto"]."</td>";    
            echo "<td>".$arrDatosReporte[$i]["cargo"]."</td>";     
            echo "<td>".$arrDatosReporte[$i]["pagador"]."</td>";
            echo "<td>".$arrDatosReporte[$i]["id_operacion"]."</td>";    
            echo "<td>".$arrDatosReporte[$i]["fecha_operacion"]."</td>";    
            $diasVencidos = date_diff_custom($arrDatosReporte[$i]["fecha_pago"], date("Y-m-d"));
            echo "<td>".$arrDatosReporte[$i]["num_factura"]."</td>";
            echo "<td>".$arrDatosReporte[$i]["fecha_emision"]."</td>";
            echo "<td>".$arrDatosReporte[$i]["fecha_vencimiento"]."</td>";
            echo "<td>".$arrDatosReporte[$i]["fecha_pago"]."</td>";              
            echo "<td>".$arrDatosReporte[$i]["valor_bruto"]."</td>";              
            echo "<td>".$diasVencidos["d"]."</td>"; 
            $calificacion = "N/D";
            if ($diasVencidos["d"]>=0 && $diasVencidos["d"]<=30)
            	$calificacion = "1-30";
			else if ($diasVencidos["d"]>30 && $diasVencidos["d"]<=60)     
				$calificacion = "31-60";
			else if ($diasVencidos["d"]>60 && $diasVencidos["d"]<=90)
				$calificacion = "61-90";
			else if ($diasVencidos["d"]>90 && $diasVencidos["d"]<=120)
				$calificacion = "91-120";
			else if ($diasVencidos["d"]>120 && $diasVencidos["d"]<=150)
				$calificacion = "121-150";
			else
				$calificacion = "151-180";
            echo "<td>".$calificacion."</td>";  
			if ($filtroInversionista)
				echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_participacion"])."</td>";
            echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_giro_final"])."</td>";
            echo "<td>".$arrDatosReporte[$i]["tasa_inversionista"]."</td>";  
            echo "<td>".$arrDatosReporte[$i]["factor"]."</td>";  
            echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_futuro"])."</td>";
            echo "<td>".$arrDatosReporte[$i]["fecha_real_pago_abono"]."</td>";    
            $nuevoValorObligacion = $arrDatosReporte[$i]["valor_obligacion"] + $arrDatosReporte[$i]["interes_mora"]; 
            $capital = $arrDatosReporte[$i]["valor_pago"] - $arrDatosReporte[$i]["interes_mora"];
            echo "<td>".formato_moneda($nuevoValorObligacion)."</td>";
            echo "<td>".formato_moneda($arrDatosReporte[$i]["valor_pago"])."</td>";  
            echo "<td>".formato_moneda($capital)."</td>";    
            echo "<td>".formato_moneda($arrDatosReporte[$i]["interes_mora"])."</td>";
            echo "<td>".formato_moneda($arrDatosReporte[$i]["nuevo_valor_obligacion"])."</td>"; // ES EL NUEVO SALDO
            echo "<td>".($arrDatosReporte[$i]["estado"] == 1?"VIGENTE":"VIGENTE CON ABONOS")."</td>";
            echo "</tr>";      
        }                  
    }
    echo "</tbody>";
    echo "</table>";

?>