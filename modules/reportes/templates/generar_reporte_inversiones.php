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
    echo "<th>Emisor</th>";    
    echo "<th>Pagador</th>";
    //echo "<th>No. Factura</th>";
    echo "<th>Fecha operación</th>";    
    echo "<th>Valor inversión</th>";  
    echo "<th>Fecha pago / abono</th>";
    echo "<th>Pago / Abono</th>";
    echo "<th>Pendiente por pagar</th>";
    echo "<th>Pendiente por pagar <br/ >para Argenta</th>";
    echo "<th>Estado</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $hayDatos = false;
    $numFactura = "";
    while (!$rsDatos->EOF){
               
        $idOperacion = $rsDatos->fields["id_operacion"];
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion ,$rsDatos->fields["fecha_operacion"]);
        $cantidadAbonos = $rsDatos->fields["total_pagos_abonos"];
        $totalPagoAbonos = $rsDatos->fields["total_valor_pago"];
        
        //DATOS DE LA OPERACION
        $emisor = $rsDatos->fields["emisor"];
        $pagador = $rsDatos->fields["pagador"];
        if (Count($arrFacturas) > 0){                                 
            $facturas =  implode(" - ",($arrFacturas["fac"]));
        }
        else
            $facturas = "Sin facturas cargadas";

        $fechaOperacion = $rsDatos->fields["fecha_operacion"];        
        
        //VALIDAMOS SE TIENE ABONOS
        $classTemp = "";
        if ($cantidadAbonos > 1){
            
            //SON RELIQUIDACIONES PARCIALES
            if ($numFactura != $rsDatos->fields["num_factura"]){ 

                $valorInversion = $rsDatos->fields["valor_giro_final"];  
                $emisor = "<div onclick='javascript:verAbonos(".$idOperacion.");' class='boton_mas'>+</div>". $emisor;
                $cuentaAbonos = 1;
            }
            else{
                $valorInversion = $valorInversion - $pagoAbono;
                $classTemp = "ver_pagos_" . $idOperacion . " pago_abono";
                $emisor = "";
                $pagador = "";
                $facturas = "";
                $fechaOperacion = "";
            }                   
            
            //GUARDAMOS EL VALOR DEL ABONO PARA RESTARLO EN EL PROXIMO ABONO Y ASI VER QUE EL VALOR DE LA INVERSION CAMBIA
            $pagoAbono = $rsDatos->fields["valor_pago"];
            echo "<tr class='".$classTemp."'>";
            echo "<td>".$emisor."</td>";    
            echo "<td>".$pagador ."</td>";
            //echo "<td>".$facturas."</td>";
            echo "<td>".$fechaOperacion."</td>";                   
            echo "<td align='right'>".formato_moneda($valorInversion)."</td>";   
            
            //SI EL ESTADO DE LA OPERACION ES CANCELADA CAMBIAMOS EL VALOR DEL PAGO
            if ($pagoAbono > $valorInversion)
                $pagoAbono = $valorInversion;  
                                                    
            $pendientePagar=$valorInversion - $pagoAbono; 
            $pendientePagarArgenta = $rsDatos->fields["nuevo_valor_obligacion"] + $pendientePagar;
        }
        else{
            $cuentaAbonos = 1;
            //SON PAGOS TOTALES
            if ($numFactura != $rsDatos->fields["num_factura"]){ 
                $valorInversion = $rsDatos->fields["valor_giro_final"];  
            }
            
            echo "<tr class=''>";            
            echo "<td>".$emisor."</td>";    
            echo "<td>".$pagador ."</td>";
            //echo "<td>".$facturas."</td>";
            echo "<td>".$fechaOperacion."</td>";              
            echo "<td align='right'>".formato_moneda($rsDatos->fields["valor_giro_final"])."</td>";   
            
            //SI EL ESTADO DE LA OPERACION ES CANCELADA CAMBIAMOS EL VALOR DEL PAGO
            $pagoAbono = $totalPagoAbonos;
            echo $totalPagoAbonos;
            if ($rsDatos->fields["estado"] == 2 || ($totalPagoAbonos > $valorInversion))
                $pagoAbono = $rsDatos->fields["valor_giro_final"];                   
            
            $pendientePagar=$rsDatos->fields["valor_giro_final"] - $pagoAbono;
            $pendientePagarArgenta = $rsDatos->fields["nuevo_valor_obligacion"] + $pendientePagar;
        }
        
        echo "<td>".$rsDatos->fields["fecha_real_pago"]."</td>";
        echo "<td align='right'>".formato_moneda($pagoAbono)."</td>";
        
        if ($pendientePagar <= 0)
            $pendientePagar = 0;
            
        if ($pendientePagar <= 0)
            $pendientePagarArgenta = 0;  
        
        echo "<td align='right'>".formato_moneda($pendientePagar)."</td>";
        echo "<td align='right'>".formato_moneda($pendientePagarArgenta)."</td>";
        echo "<td>".$operaciones->arrEstados[$rsDatos->fields["estado"]]."</td>";
        echo "</tr>";        
        
        //ACUMULAMOS TODOS LOS ABONOS
        $totalAbono += $pagoAbono;
        
        //ACUMULAMOS PARA EL PRIMER ABONO
        if ($cuentaAbonos == 1){
            $totalInversion += $valorInversion;        
        }
        
        //ACUMULAMOS PARA EL ULTIMO ABONO
        if ($cuentaAbonos >= ($cantidadAbonos - 1)){
            $totalPendientePagar += $pendientePagar;
            $totalPendientePagarArgenta += $pendientePagarArgenta;
        }
        
        $numFactura = $rsDatos->fields["num_factura"];
        $hayDatos = true;
        $cuentaAbonos++;
        $rsDatos->MoveNext();       
    }

    echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    //echo "<td></td>";
    echo "<td><b>TOTAL:</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalInversion)."</b></td>";
    //echo "<td></td>";
    echo "<td></td>";
    echo "<td align='right'><b>".formato_moneda($totalAbono)."</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalPendientePagar)."</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalPendientePagarArgenta)."</b></td>";
    echo "<td></td>";
    echo "</tr>";

    echo "</tbody>";
    echo "</table>";

?>
</div>