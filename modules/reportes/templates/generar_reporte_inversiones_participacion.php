<script>
$(document).ready(function() {
    oTableExport = $('#tableDataInversiones').dataTable({ "paging": false, "bStateSave": true, "bInfo": true, "bSort": false });     
});

function verAbonos(idOperacion){    
    $(".ver_pagos_" + idOperacion).toggle();
}

</script>
<style>
    .pago_abono{
        display:none;
    }
    
    .boton_mas{
        background-color: #337ab7;
        border: 1px solid white;
        border-radius: 18px;
        box-shadow: 0 0 3px #444;
        color: white;
        height: 16px;
        text-align: center;
        width: 16px; 
        cursor:pointer;
        float:left;
        margin-right:3px;
    }
</style>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">
    <div class="col-md-12">
        <a href="javascript:;" title="Exportar" onclick="exportarExcel('tableDataInversiones', 'Reporte Inversiones');" class="link_custom"><i class="fa fa-download fa-lg"></i>Exportar a Excel</a>
    </div>
</div>
<?php

    echo "<table id='tableDataInversiones' border='1' class='table table-striped table-bordered dt-responsive nowrap' cellspacing='0' style='width:100%;'>";
    echo "<thead>";
    echo "<tr>"; 
    echo "<th>Id Operación</th>";  
    echo "<th>Emisor</th>";    
    echo "<th>Pagador</th>";
    echo "<th>No. Factura venta</th>";
    echo "<th>Fecha operación</th>";    
    echo "<th>Valor inversión</th>";  
    echo "<th>Fecha pago / abono</th>";
    echo "<th>Pago / Abono</th>";
    echo "<th>Saldo</th>";
    echo "<th>Estado</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $hayDatos = false;
    $numFactura = "";
    $idOperacionAux = 0;
    $valorInversion = 0;
    $totalInversion = 0;
    while (!$rsDatos->EOF){
               
        $idOperacion = $rsDatos->fields["id_operacion"];
        $idReliquidacion = $rsDatos->fields["id_reliquidacion"];	

        //DATOS DE LA OPERACION
        $emisor = $rsDatos->fields["emisor"];
        $pagador = $rsDatos->fields["pagador"];
        $fechaOperacion = $rsDatos->fields["fecha_operacion"];  
        
		if ($idOperacion != $idOperacionAux){
			$valorInversion = $rsDatos->fields["totalInversion"];        
			$totalInversion += $rsDatos->fields["totalInversion"];
		}
		
        $valorPago = $rsDatos->fields["totalReintegro"];
        $saldo = $valorInversion - $valorPago;       
        
		$classTemp = "";
		echo "<tr class='".$classTemp."'>";
		echo "<td>".$idOperacion."</td>"; 
		echo "<td>".$emisor."</td>";    
		echo "<td>".$pagador ."</td>";
		echo "<td>".$rsDatos->fields["num_factura"]."</td>";
		echo "<td>".$fechaOperacion."</td>";                   
		echo "<td align='right'>".formato_moneda($valorInversion)."</td>"; 
		echo "<td>".$rsDatos->fields["fecha_real_pago"]."</td>";
		echo "<td align='right'>".formato_moneda($valorPago)."</td>";
		echo "<td align='right'>".formato_moneda($saldo)."</td>";
		echo "<td>".$operaciones->arrEstados[$rsDatos->fields["estado"]]."</td>";
		echo "</tr>";  
		
		//ACTUALIZAMOS EL VALOR DE LA INVERISION
		$valorInversion = $saldo;
		$idOperacionAux = $idOperacion;	
		
		$totalPagos+=$valorPago;
		
        $rsDatos->MoveNext();       
    }

    echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td><b>TOTAL:</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalInversion)."</b></td>"; 
    echo "<td></td>";
    echo "<td align='right'><b>".formato_moneda($totalPagos)."</b></td>";
    echo "<td align='right'><b>".formato_moneda(($totalInversion - $totalPagos))."</b></td>";
    echo "<td></td>";
    echo "</tr>";

    echo "</tbody>";
    echo "</table>";

?>
</div>