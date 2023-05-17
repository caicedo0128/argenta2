<script>

function formReporteReliquidacion(idObj){

    $("#objeto_html").val(idObj);
    $('#modalToMail').modal('show');
}

function enviarReporteReliquidacion(){

    validateForm("custom_data_to_email");

    if ($("#custom_data_to_email").valid()) {

        showLoading("Enviando reporte por correo electrónico..."); 

        var objHtml = $("#objeto_html").val();

        //TOMAMOS EL HTML QUE SE VA A ENVIAR EN EL REPORTE
        var dataHtml = "<style>.reliquidacion_cliente{display:block;}.right{ text-align:right;}.td_border{border: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd; border-bottom: 1px solid #ddd; padding: 8px; } </style><table border='1' bordercolor='#CECECE' width='100%'>" + $("#" + objHtml).html() + "</table>";

        var dataForm = "Ajax=true&mod=reliquidaciones&action=guardarReporteReliquidacion&__dataMail=" + dataHtml;
        var strUrl = "admindex.php";
        $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) { 

                $("#formMail input[id=mod]").val("reliquidaciones");
                $("#formMail input[id=action]").val("enviarReporteReliquidacion");
                $("#formMail input[id=__subjectMail]").val("Reporte re-liquidacion operacion");
                $("#formMail input[id=__toEmailMail]").val($("#correo_to_email").val());
                $("#formMail input[id=__toNameMail]").val($("#nombre_to_email").val());

                var dataForm = "Ajax=true&" + $("#formMail").serialize();
                var strUrl = "admindex.php";
                $.ajax({
                        type: 'POST',
                        url: strUrl,
                        dataType: "json",
                        data:dataForm,
                        success: function (response) {
                            closeNotify();                    
                            if (response.Success) {
                                showSuccess("Transacción exitosa. Espere por favor..."); 
                                $('#modalToMail').modal('hide');
                            }
                            else{
                                showError(response.Message);
                            }
                        }
                }); 
            }
        }); 
    }                
}

</script>
<a href="javascript:;" onclick="formReporteReliquidacion('reliquidacion_cliente');" title="Cliente" class="btn btn-warning"><i class="fa fa-envelope-o fa-lg"></i>Enviar reporte cliente</a>                   


<?php
    //IMPRIMIMOS DATOS PARA ENVIAR REPORTE CLIENTE, SE RECORRE NUEVAMENTE POR QUE EL FORMATO DE ENVIO ES MUY DIFERENTE AL QUE ESTÁ EN LA PANTALLA
    echo "<table id=\"reliquidacion_cliente\"  width=\"100%\" style=\"color:#6d6d6d;\" class=\"reliquidacion_cliente table table-bordered table-striped\">";
    
    echo "<tr>";
    echo "<td colspan=\"3\"><img id=\"logoArgenta\" src=\"./images/logo.png\"></td>";
    echo "<td colspan=\"7\" align=\"center\">ARGENTA ESTRUCTURADORES SAS<br/><i>Reporte liquidación cliente.</i></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan=\"10\"> <hr style=\"border-color:#449d44;\"></td>";
    echo "</tr>";                   
    echo "<tr style=\"background-color:#F9F9F9;\" class=\"reliquidacion_cliente\">";
    echo "<td colspan=\"3\" class=\"td_border\">Emisor:</td>";
    echo "<td colspan=\"7\" class=\"td_border right\">".$emisor->razon_social."</td>";
    echo "</tr>";
    echo "<tr >";
    echo "<td colspan=\"3\" class=\"td_border\">Pagador:</td>";
    echo "<td colspan=\"7\" class=\"td_border right\">".$pagador->razon_social."</td>";
    echo "</tr>";
    echo "<tr style=\"background-color:#F9F9F9;\">";
    echo "<td colspan=\"3\" class=\"td_border\">Facturadas pagadas:</td>";
    echo "<td colspan=\"7\" class=\"td_border right\">".implode(" - ",$arrFacturas)."</td>";
    echo "</tr>";  
    echo "<tr >";
    echo "<td colspan=\"10\"> <hr style=\"border-color:#449d44;\"></td>";
    echo "</tr>";       
    echo "<tr style=\"background-color:#F9F9F9;font-size:9px;\">";
    echo "<td class=\"td_border\">Fecha último abono</td>";
    echo "<td class=\"td_border\">Fecha pago</td>";
    echo "<td class=\"td_border\">Tasa %</td>";
    echo "<td class=\"td_border\">Valor obligación</td>";
    echo "<td class=\"td_border\">Abono</td>";
    echo "<td class=\"td_border\">Nuevo valor obligación</td>";
    echo "<td class=\"td_border\">Otros</td>";
    echo "<td class=\"td_border\">Devolución remanentes <br/> antes GMF</td>";
    echo "<td class=\"td_border\">GMF</td>";
    echo "<td class=\"td_border\">Valor devolución</td>";
    echo "</tr>";
    echo "<tbody>";
    
    $reg = 1;
    while (!$rsDataReliquidacionPPReporteCliente->EOF){
        $idReliquidacionPP = $rsDataReliquidacionPPReporteCliente->fields["id_reliquidacion_pp"];
        echo "<tr style=\"font-size:9px;\">";
        $fechaUltimoAbono = $rsDataReliquidacionPPReporteCliente->fields["fecha_pago_pactada"];
        if ($reg==1)
            $fechaUltimoAbono = "";
        $reg++;    
        echo "<td class=\"td_border\">".$fechaUltimoAbono."</td>";
        echo "<td class=\"td_border\">".$rsDataReliquidacionPPReporteCliente->fields["fecha_real_pago"]."</td>";
        echo "<td class=\"td_border\" align=\"center\">".$rsDataReliquidacionPPReporteCliente->fields["tasa"]." %</td>";
        echo "<td class=\"td_border\" align=\"right\">".formato_moneda($rsDataReliquidacionPPReporteCliente->fields["valor_obligacion_pp"])."</td>";
        echo "<td class=\"td_border\" align=\"right\">".formato_moneda($rsDataReliquidacionPPReporteCliente->fields["abono"])."</td>";
        echo "<td class=\"td_border\" align=\"right\">";
                if ($rsDataReliquidacionPPReporteCliente->fields["abono"] > $rsDataReliquidacionPPReporteCliente->fields["valor_obligacion_pp"])
                    echo formato_moneda(0);
                else                                        
                    echo formato_moneda($rsDataReliquidacionPPReporteCliente->fields["nuevo_valor_obligacion"]);
        echo "</td>";
        echo "<td class=\"td_border\" align=\"right\">".formato_moneda($rsDataReliquidacionPPReporteCliente->fields["otros"])."</td>";
        echo "<td class=\"td_border\" align=\"right\">".formato_moneda($rsDataReliquidacionPPReporteCliente->fields["devolucion_remanentes"])."</td>";
        echo "<td class=\"td_border\" align=\"right\">".formato_moneda($rsDataReliquidacionPPReporteCliente->fields["gmf"])."</td>";
        echo "<td class=\"td_border\" align=\"right\">".formato_moneda($rsDataReliquidacionPPReporteCliente->fields["monto_devolver"])."</td>";                    
        echo "</tr>";
        $rsDataReliquidacionPPReporteCliente->MoveNext();
    }     
    
    echo "</tbody>";
    echo "</table>";
?>    
<div id="modalToMail" class="modal fade" role="dialog" aria-labelledby="modalToMail" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Enviar correo electrónico a:</h4>
            </div>
            <div class="modal-body" id="">
                <form id="custom_data_to_email" name="custom_data_to_email">
                <input type="hidden" id="objeto_html" name="objeto_html">
                <div class="row">
                    <div class="col-md-2 labelCustom">Nombres:</div>
                    <div class="col-md-6">
                        <input type="textbox" id="nombre_to_email" name="nombre_to_email" value="" class="form-control required">                 
                    </div>
                </div>
                <div class="row" style="height:10px;">&nbsp;</div> 
                <div class="row">
                    <div class="col-md-2 labelCustom">E-mail:</div>
                    <div class="col-md-6">
                        <input type="textbox" id="correo_to_email" name="correo_to_email" value="" class="form-control required no-mayus">   
                        (Separe con ; para varios envíos)
                    </div>
                </div>  
                <div class="row" style="height:10px;">&nbsp;</div> 
                <center>
                    <input type="button" class="btn btn-success" value="Enviar" onclick="enviarReporteReliquidacion();">
                </center>  
                </form>
            </div>
        </div>
    </div>
</div>

