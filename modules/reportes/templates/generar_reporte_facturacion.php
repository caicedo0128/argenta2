<script>
$(document).ready(function() {
    oTableExport = $('#tableDataFacturacion').dataTable({ "paging": false, "bStateSave": true, "bInfo": true, "bSort": false });  

    $('#fecha_facturacion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });  
});

function gestionarFacturacion(){

        if ($("#fecha_facturacion").val() != "")
        {        
            bootbox.confirm('Se va a realizar la actualización de las operaciones.<br/><br/>Desea continuar?.', function (result) {

                if (result) {   

                    showLoading("Enviando informacion. Espere por favor...");

                    var dataForm = "Ajax=true&fecha_facturacion=" + $("#fecha_facturacion").val() + "&" + $("#datos_facturacion").serialize();
                    var strUrl = "admindex.php";
                    $.ajax({
                            type: 'POST',
                            url: strUrl,
                            dataType: "json",
                            data:dataForm,
                            success: function (response) {
                                closeNotify();
                                showSuccess(response.Message);
                                if (response.Success) {
                                    window.location.href='admindex.php?mod=reportes&action=reporteFacturacion';
                                }
                            }
                    });
                }            
            });   
        }
        else{
            showError("Ingrese la fecha facturación y las observaciones facturación.");
        }
}  

function facturacion(){    
    $('#modalToFacturacion').modal('show');
}


</script>
<hr />
<h4>Resultados de la consulta</h4>

<div class="panel-body well well-sm bg-success-custom text-right " style="height: 40px;">

</div>
<div style="height: 40px;" class="row-fluid">
    <div class="agregar_registro text-right">
        <a class="btn btn-success btn-sm" href="javascript:facturacion();"><i class="fa fa-plus-square fa-lg"></i> Facturados</a>
        <a class="btn btn-warning btn-sm" href="javascript:exportarExcel('tableDataFacturacion', 'Reporte Facturación');"><i class="fa fa-download fa-lg"></i> Exportar a excel</a>
    </div>
</div>
<form id='datos_facturacion' name='datos_facturacion'>
<?php

    echo "<input type='hidden' name='mod' value='operaciones'>";
    echo "<input type='hidden' name='action' value='updateFacturacion'>";
    echo "<input type='hidden' name='Ajax' value='true'>";
    echo "<table id='tableDataFacturacion' border='1' class='table table-striped table-bordered dt-responsive nowrap' cellspacing='0' style='width:100%;'>";
    echo "<thead>";
    echo "<th>Inversionista</th>"; 
    echo "<th>Emisor</th>";    
    echo "<th>Pagador</th>";
    echo "<th>Títulos negociados</th>";   
    echo "<th>No. Factura Argenta</th>";
    echo "<th>Fecha operación</th>"; 
    echo "<th>Fecha facturación</th>"; 
    echo "<th>Margen Argenta</th>";
    echo "<th>IVA factura</th>";
    echo "<th>Valor factura Argenta</th>";
    echo "<th>Estado</th>";   
    echo "<th>Facturado</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $arrEstados = array("1"=>"VIGENTE","2"=>"CANCELADA","3"=>"CREADA");
    while (!$rsDatos->EOF){
    
        $idOperacion = $rsDatos->fields["id_operacion"];
        $fechaOperacion = $rsDatos->fields["fecha_operacion"];
        $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$fechaOperacion);  
        $arrFacturasReliquidacion = $factura->getArrFacturasPorOperacionReliquidacion($idOperacion,$fechaOperacion);	
        
        echo "<tr>";
        echo "<td>".$rsDatos->fields["inversionista"]."</td>";    
        echo "<td>".$rsDatos->fields["emisor"]."</td>";    
        echo "<td>".$rsDatos->fields["pagador"]."</td>";
        echo "<td>";
            if (Count($arrFacturas) > 0){                                 
                echo implode(" - ",($arrFacturas["fac"]));
            }
            else
                echo "Sin facturas cargadas";
        echo "</td>"; 
        echo "<td>";
			if (Count($arrFacturasReliquidacion) > 0){                                 
                echo implode(" - ",($arrFacturasReliquidacion["fac"]));
            }
            else
                echo "Sin reliquidaciones";        
        echo "</td>";
        echo "<td>".$rsDatos->fields["fecha_operacion"]."</td>";   
        echo "<td>".($rsDatos->fields["fecha_facturacion"]!=""?$rsDatos->fields["fecha_facturacion"]:"Sin facturar")."</td>";  
        echo "<td align='right'>".formato_moneda($rsDatos->fields["margen_argenta_reli"])."</td>";
        echo "<td align='right'>".formato_moneda($rsDatos->fields["iva_fra_asesoria_reli"])."</td>";
        echo "<td align='right'>".formato_moneda($rsDatos->fields["fra_argenta_reli"])."</td>";
        echo "<td>".$arrEstados[$rsDatos->fields["estado"]]."</td>"; 
        //echo "<td align='right'>".formato_moneda($rsDatos->fields["valor_giro_final"])."</td>";
        //echo "<td align='right'>".formato_moneda($rsDatos->fields["margen_argenta"])."</td>";     
        echo "<td align='center'>";
        
            if ($rsDatos->fields["facturado"]==1)
                echo "Facturado";
            else{
                echo "<input type='checkbox' name='id_operacion[]' value='".$rsDatos->fields["id_operacion"]."'>";
            }
        
        echo "</td>";     
        echo "</tr>";    
        
        $totalValorNeto += $rsDatos->fields["valor_neto"];
        $totalGiroFinal += $rsDatos->fields["valor_giro_final"];
        $totalMargenArgenta += $rsDatos->fields["margen_argenta_reli"];
        
        $rsDatos->MoveNext();
    }
    
    /*echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td><b>TOTAL:</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalValorNeto)."</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalGiroFinal)."</b></td>";
    echo "<td align='right'><b>".formato_moneda($totalMargenArgenta)."</b></td>";
    echo "<td></td>";
    echo "</tr>";    
    */
    echo "</tbody>";
    echo "</table>";
    echo "</form>";

?>

<div id="modalToFacturacion" class="modal fade" role="dialog" aria-labelledby="modalToFacturacion" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Datos para procesar facturación:</h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                    <div class="col-md-3 labelCustom">Fecha facturación:</div>
                    <div class="col-md-3">
                        <input type="textbox" id="fecha_facturacion" name="fecha_facturacion" value="" class="form-control required">                 
                    </div>
                </div>
                <div class="row" style="height:10px;">&nbsp;</div> 
                <center>
                    <input type="button" class="btn btn-success" value="Enviar" onclick="gestionarFacturacion();">
                </center>  
            </div>
        </div>
    </div>
</div>
</form>
