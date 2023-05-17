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
        var dataHtml = "<style>.reliquidacion_cliente{display:block;}.right{ text-align:right;}.td_border{border: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd; border-bottom: 1px solid #ddd; padding: 8px; } </style>" + $("#" + objHtml).html() + "";

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
<div id="reliquidacion_cliente">
<table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">

    <tr>
        <td><img id="logoArgenta" src="./images/logo.png"></td>
        <td>ARGENTA ESTRUCTURADORES SAS<br/><i>Reporte liquidación cliente.</i></td>
    </tr>
    <tr>
        <td colspan="2"> <hr style="border-color:#449d44;"></td>
    </tr>
    <tr style="background-color:#F9F9F9;">
        <td class="td_border">Emisor:</td>
        <td class="td_border right"><?=$emisor->razon_social?></td>
    </tr>
    <tr>
        <td class="td_border">Pagador:</td>
        <td class="td_border right"><?=$pagador->razon_social?></td>
    </tr>
    <tr style="background-color:#F9F9F9;">
        <td class="td_border">Fecha desembolso:</td>
        <td class="td_border right"><?=$fechaDesembolso?></td>
    </tr>
    <tr>
        <td colspan="2"> <hr style="border-color:#449d44;"></td>
    </tr>
</table>
<br/>
<b><u>Facturas abonadas</u></b>
<br/><br/>
<table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
    <tr style="font-size:9px;">
        <td class='td_border'><b>Número factura</b></td>
        <td class='td_border'><b>Fecha pago</b></td>
        <td class='td_border'><b>Valor deuda fecha pago pactada</b></td>
        <!--td class='td_border'>Días de mora / A favor</td>
        <td class='td_border'>Intereses mora</td>
        <td class='td_border'>Intereses a devolver</td>
        <td class='td_border'>Otros</td>
        <td class='td_border'>Remantentes disponibles</td>
        <td class='td_border'>Gmf</td>
        <td class='td_border'>Valor devolución</td-->
    </tr>
    <?php
        $totalRegistros = $rsListadoFacturas->_numOfRows;
        $i=1;
        while(!$rsListadoFacturas->EOF){

            $diasMora = date_diff_custom($rsListadoFacturas->fields["fechaPagoPactada"],$rsListadoFacturas->fields["fechaRealPago"]);

            $valorDevolucion = 0;
            $gmf = 0;
            if ($i == $totalRegistros){
                $valorDevolucion = $rsListadoFacturas->fields["montoDevolucion"];
                $gmf = $rsListadoFacturas->fields["gmf"];
                $remanentesDisponibles = $rsListadoFacturas->fields["remanentesDisponibles"];
            }

            if ($i == 1){
                $totalIngreso = $rsListadoFacturas->fields["valorPago"];
            }

            echo "<tr style=\"font-size:9px;\">";
            echo "<td class='td_border'>".$rsListadoFacturas->fields["num_factura"]."</td>";
            echo "<td class='td_border'>".$rsListadoFacturas->fields["fechaRealPago"]."</td>";
            echo "<td class='td_border'>".formato_moneda($rsListadoFacturas->fields["valor_futuro"])."</td>";
            /*echo "<td class='td_border'>".$diasMora["d"]."</td>";
            echo "<td class='td_border'>".formato_moneda($rsListadoFacturas->fields["interesesMora"])."</td>";
            echo "<td class='td_border'>".formato_moneda($rsListadoFacturas->fields["interesesDevolver"])."</td>";
            echo "<td class='td_border'>".formato_moneda($rsListadoFacturas->fields["otros"])."</td>";
            echo "<td class='td_border'>".formato_moneda($valorDevolucion)."</td>";
            echo "<td class='td_border'>".formato_moneda($gmf)."</td>";
            echo "<td class='td_border'>".formato_moneda(($valorDevolucion - $gmf))."</td>";*/
            echo "</tr>";
            $totalValorFuturo += $rsListadoFacturas->fields["valor_futuro"];
            $i++;
            $rsListadoFacturas->MoveNext();
        }
        echo "<tr style=\"font-size:9px;\">";
        echo "<td class='td_border'><span></span></td>";
        echo "<td class='td_border'><b>TOTAL FACTURAS:</b></td>";
        echo "<td class='td_border'><b>".formato_moneda($totalValorFuturo)."</b></td>";
        //echo "<td class='td_border' colspan='7'><span></span></td>";
        echo "</tr>";
    ?>
</table>
<br/><br/>
<b><u>Totales</u></b>
<br/><br/>
<table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
    <tr style='font-size:9px;'>
        <td class='td_border'><b>Intereses mora</b></td>
        <td class='td_border'><b>Intereses a devolver</b></td>
        <td class='td_border'><b>Otros</b></td>
        <td class='td_border'><b>Remantentes disponibles</b></td>
        <td class='td_border'><b>Gmf</b></td>
        <td class='td_border'><b>Valor devolución</b></td>
        <td class='td_border'><b>Total ingreso</b></td>
    </tr>
    <tr style='font-size:9px;'>
        <td class='td_border'><?=formato_moneda($totalInteresesMora)?></td>
        <td class='td_border'><?=formato_moneda($totalInteresesDevolver)?></td>
        <td class='td_border'><?=formato_moneda($totalOtros)?></td>
        <td class='td_border'><?=formato_moneda($remanentesDisponibles)?></td>
        <td class='td_border'><?=formato_moneda($gmf)?></td>
        <td class='td_border'><?=formato_moneda($valorDevolucion)?></td>
        <td class='td_border'><?=formato_moneda($totalIngreso)?></td>
    </tr>
</table>
<br/>
</div>

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

