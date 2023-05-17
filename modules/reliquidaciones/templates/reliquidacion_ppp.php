<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_pago_pactada').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_real_pago').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_movimiento').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function calcularReliquidacionPPP(){

	var tasaInversionista = $("#tasa_inversionista").val();
    var factor = $("#factor").val();
    var valorObligacionPP = $("#valor_obligacion_pp").val();
    var valorObligacionPactada = 0;
    var fechaPago = new Date($("#fecha_real_pago").val());
    var diasDiferencia = DateDiff.inDays(new Date($("#fecha_operacion").val()), fechaPago);
    var valorTotalIva = 0;
    var valorTotalDiferenciaDescuentoTotal = 0;
    var valorTotalDiferenciaIVA = 0;    
    
    var abono = parseFloat($("#abono").val());
    var tasa = $("#tasa").val();

    //TOTAMOS LAS FACTURAS SELECCIONADAS Y SACAMOS DATOS NECESARIOS
    $("#facturas_seleccionadas").val("");
    $("#id_factura_abonada > option").removeAttr("disabled");
    var facturasSeleccionadas = false;
    $("input[class='checks_facturas_reliquidar']:checked").each(function (id) {

        facturasSeleccionadas = true;
        var idFacturaCheck = this.id;

        //OBTENEMOS EL VALOR FUTURO
        var valorFuturo = parseFloat($("#valor_futuro_" + idFacturaCheck).val());
        var valorIVA = parseFloat($("#valor_iva_" + idFacturaCheck).val());
        var descuentoTotalInicial = parseFloat($("#descuento_total_" + idFacturaCheck).val());

        var descuentoTotal = Math.round((((diasDiferencia * factor) / 100) / 30) * valorFuturo);
		var potenciaMargen = Math.pow(1 + (tasaInversionista / 100),(diasDiferencia / 365));
		var margenInversionista =  Math.round(valorFuturo-(valorFuturo / potenciaMargen));
		var margeArgenta =  descuentoTotal - margenInversionista;
		var ivaFraAsesoria =  Math.round((margeArgenta * 19) / 100) ;        
        
        $("#valor_obligacion_pp").val(valorObligacionPactada + valorFuturo + valorIVA);       
        $("#valor_iva").val(valorTotalIva + valorIVA);
        valorTotalIva = parseFloat($("#valor_iva").val());  

        var totalDiferenciaDescuentoTotal = (descuentoTotalInicial) - (descuentoTotal);           
        $("#total_diferencia_descuento_total").val(valorTotalDiferenciaDescuentoTotal + totalDiferenciaDescuentoTotal);
        valorTotalDiferenciaDescuentoTotal = parseFloat($("#total_diferencia_descuento_total").val());

		var totalDiferenciaIVA = (valorIVA) - (ivaFraAsesoria);           
        $("#total_diferencia_iva").val(valorTotalDiferenciaIVA + totalDiferenciaIVA);
        valorTotalDiferenciaIVA = parseFloat($("#total_diferencia_iva").val());

        var fechaPagoPactada = $("#fecha_pago_" + idFacturaCheck).val();
        $("#fecha_pago_pactada").val(fechaPagoPactada);
        valorObligacionPactada = parseFloat($("#valor_obligacion_pp").val());

        var facturas = $("#nro_factura_" + idFacturaCheck).val() + "-" + $("#facturas_seleccionadas").val();
        $("#facturas_seleccionadas").val(facturas);
        $("#id_factura_abonada > option[value=" + idFacturaCheck + "]").attr("disabled","disabled");
    });

    //SI NO HAY FACTURAS SELECCIONADAS VOLVEMOS EN CEROS VARIOS CAMPOS
    if(facturasSeleccionadas == false){
        fechaPagoPactada = "";
        valorObligacionPactada = 0;
        $("#valor_obligacion_pp").val(0);
    }
    else{
        fechaPagoPactada = new Date($("#fecha_pago_pactada").val());
        valorObligacionPactada = parseFloat($("#valor_obligacion_pp").val());
        $('#fecha_real_pago').datetimepicker({ format: 'YYYY-MM-DD', showClear: true }).data('DateTimePicker').minDate($("#fecha_pago_pactada").val());        
    }

    if ($("#fecha_pago_pactada").val() != "" && $("#fecha_real_pago").val() != "")
    {
        //DIAS
        var diasObligacion = DateDiff.inDays(fechaPagoPactada, fechaPago);

        //VALOR OBLIGACION
        var tasaCalculo = tasa / 100;
        var potencia = Math.pow(1 + tasaCalculo,((diasObligacion * -1) / 365));
        var valorObligacion = Math.round(valorObligacionPactada / potencia);
		var diferenciaTotalDescuento = $("#total_diferencia_descuento_total").val();
		var diferenciaTotalIVA = $("#total_diferencia_iva").val();

        $("#valor_obligacion_pago_real").val(valorObligacion);
        $("#text_valor_obligacion_pago_real").text(valorObligacion);
        $('#text_valor_obligacion_pago_real').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //VALIDAMOS VALORES DE PAGO
        var valorObligacionPagoReal = parseFloat($("#valor_obligacion_pago_real").val());
        if (abono >= valorObligacionPagoReal){
            showError("El valor del abono no permite un valor igual o mayor al de la deuda");
            $("#abono").val("");
            return false;
        }

        //NUEVO VALOR OBLIGACION
        var nuevoValorObligacion = valorObligacion - abono;
        $("#nuevo_valor_obligacion").val(nuevoValorObligacion);
        $("#text_nuevo_valor_obligacion").text(nuevoValorObligacion);
        $('#text_nuevo_valor_obligacion').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //INTERESES MORAñ2
        var interesesMora = valorObligacion - valorObligacionPactada;
        $("#intereses_mora").val(interesesMora);
    }

    setDataInfoCliente();
}

function calcularReliquidacionPPAbono(){

    var valorPresente = parseFloat($("#valor_presente_abono").val());
    var tasa = parseFloat($("#tasa_abono").val());
    var fechaUltimoPago = new Date($("#fecha_ultimo_pago_abono").val());
    var fechaPago = new Date($("#fecha_real_pago_abono").val());
    var abono = parseFloat($("#abono_abono").val());
    var otros = parseFloat($("#otros_abono").val());

    //DIAS
    var diasObligacion = DateDiff.inDays(fechaPago, fechaUltimoPago);

    //VALOR OBLIGACION
    var tasaCalculo = tasa / 100;
    var potencia = Math.pow(1 + tasaCalculo,(diasObligacion / 365));
    var valorObligacion = Math.round(valorPresente / potencia);
    $("#valor_obligacion_pp_abono").val(valorObligacion);
    $("#text_valor_obligacion_pp_abono").text(valorObligacion);
    $('#text_valor_obligacion_pp_abono').priceFormat({
        allowNegative: true,
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //NUEVO VALOR OOBLIGACION - VALOR PRESENTE
    var valorObligacionPresente = valorObligacion - abono;
    $("#nuevo_valor_obligacion_abono").val(valorObligacionPresente);
    $("#text_nuevo_valor_obligacion_abono").text(valorObligacionPresente);
    $('#text_nuevo_valor_obligacion_abono').priceFormat({
        allowNegative: true,
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //PARA CALCULAR LOS REMANENTES SOLO SE TIENE EN CUENTA SI EL ABONO ES MAYOR AL VALOR DE LA OBLIGACION
    if (abono > valorObligacion){

        //SI EL VALOR DE LA OBLIGACION PRESENTE ES MENOR DE 0 SE VUELVE TODO 0
        if (valorObligacionPresente <= 0){
            $("#nuevo_valor_obligacion_abono").val(0);
            $("#text_nuevo_valor_obligacion_abono").text(0);
            $('#text_nuevo_valor_obligacion_abono').priceFormat({
                allowNegative: true,
                prefix: '$ ',
                centsSeparator: ',',
                thousandsSeparator: '.',
                centsLimit: 0
            });
        }

        valorObligacionPresente = (valorObligacionPresente * -1);

        //REMANENTES DISPONIBLES
        var remanentes = abono - parseFloat(valorObligacion);
        $("#text_remanentes_disponibles").text(remanentes);
        $('#text_remanentes_disponibles').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //DEVOLUCION REMANENTES
        var devolucion = remanentes - parseFloat(otros);
        $("#devolucion_remanentes_gmf").val(devolucion);
        $("#text_devolucion_remanentes_gmf").text(devolucion);
        $('#text_devolucion_remanentes_gmf').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //GMF
		var aplicaImpuesto = $("input[id='aplica_impuesto_reli']:checked").val();
		var factorGMF = 0.3984;    
		if (aplicaImpuesto == 2)
			factorGMF = 0; 
			
        var gmf = Math.round((devolucion * factorGMF) / 100);
        $("#gmf_ppp").val(gmf);
        $("#text_gmf_ppp").text(gmf);
        $('#text_gmf_ppp').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //MONTO A DEVOLVER
        var montoDevolver = devolucion - gmf;
        $("#monto_devolver").val(montoDevolver);
        $("#text_monto_devolver").text(montoDevolver);
        $('#text_monto_devolver').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        $(".devoluciones_abono").show();

    }

    //INTERESES MORA
    var interesesMora = valorObligacion - valorPresente;
    $("#intereses_mora_abono").val(interesesMora);

}

function calcularReliquidacionPPPAnterior(){

    var valorObligacionPP = $("#valor_obligacion_pp").val();
    var valorObligacionPactada = 0;
    var fechaPago = new Date($("#fecha_real_pago").val());
    var abono = parseFloat($("#abono").val());
    var tasa = $("#tasa").val();

    //TOTAMOS LAS FACTURAS SELECCIONADAS Y SACAMOS DATOS NECESARIOS
    $("#facturas_seleccionadas").val("");
    $("#id_factura_abonada > option").removeAttr("disabled");
    var facturasSeleccionadas = false;
    $("input:checked").each(function (id) {

        facturasSeleccionadas = true;
        var idFacturaCheck = this.id;

        //OBTENEMOS EL VALOR FUTURO
        var valorFuturo = parseFloat($("#valor_futuro_" + idFacturaCheck).val());
        $("#valor_obligacion_pp").val(valorObligacionPactada + valorFuturo);

        var fechaPagoPactada = $("#fecha_pago_" + idFacturaCheck).val();
        $("#fecha_pago_pactada").val(fechaPagoPactada);
        valorObligacionPactada = parseFloat($("#valor_obligacion_pp").val());

        var facturas = $("#nro_factura_" + idFacturaCheck).val() + "-" + $("#facturas_seleccionadas").val();
        $("#facturas_seleccionadas").val(facturas);
        $("#id_factura_abonada > option[value=" + idFacturaCheck + "]").attr("disabled","disabled");
    });

    //SI NO HAY FACTURAS SELECCIONADAS VOLVEMOS EN CEROS VARIOS CAMPOS
    if(facturasSeleccionadas == false){
        fechaPagoPactada = "";
        valorObligacionPactada = 0;
        $("#valor_obligacion_pp").val(0);
    }
    else{
        fechaPagoPactada = new Date($("#fecha_pago_pactada").val());
        valorObligacionPactada = parseFloat($("#valor_obligacion_pp").val());
    }

    if ($("#fecha_pago_pactada").val() != "" && $("#fecha_real_pago").val() != "")
    {
        //DIAS
        var diasObligacion = DateDiff.inDays(fechaPagoPactada, fechaPago);

        //VALOR OBLIGACION
        var tasaCalculo = tasa / 100;
        var potencia = Math.pow(1 + tasaCalculo,((diasObligacion * -1) / 365));
        var valorObligacion = Math.round(valorObligacionPactada / potencia);

        $("#valor_obligacion_pago_real").val(valorObligacion);
        $("#text_valor_obligacion_pago_real").text(valorObligacion);
        $('#text_valor_obligacion_pago_real').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //VALIDAMOS VALORES DE PAGO
        var valorObligacionPagoReal = parseFloat($("#valor_obligacion_pago_real").val());
        if (abono >= valorObligacionPagoReal){
            showError("El valor del abono no permite un valor igual o mayor al de la deuda");
            $("#abono").val("");
            return false;
        }

        //NUEVO VALOR OBLIGACION
        var nuevoValorObligacion = valorObligacion - abono;
        $("#nuevo_valor_obligacion").val(nuevoValorObligacion);
        $("#text_nuevo_valor_obligacion").text(nuevoValorObligacion);
        $('#text_nuevo_valor_obligacion').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //INTERESES MORA
        var interesesMora = valorObligacion - valorObligacionPactada;
        $("#intereses_mora").val(interesesMora);
    }

    setDataInfoCliente();
}

function calcularReliquidacionPPAbonoAnterior(){

    var valorPresente = parseFloat($("#valor_presente_abono").val());
    var tasa = parseFloat($("#tasa_abono").val());
    var fechaUltimoPago = new Date($("#fecha_ultimo_pago_abono").val());
    var fechaPago = new Date($("#fecha_real_pago_abono").val());
    var abono = parseFloat($("#abono_abono").val());
    var otros = parseFloat($("#otros_abono").val());

    //DIAS
    var diasObligacion = DateDiff.inDays(fechaPago, fechaUltimoPago);

    //VALOR OBLIGACION
    var tasaCalculo = tasa / 100;
    var potencia = Math.pow(1 + tasaCalculo,(diasObligacion / 365));
    var valorObligacion = Math.round(valorPresente / potencia);
    $("#valor_obligacion_pp_abono").val(valorObligacion);
    $("#text_valor_obligacion_pp_abono").text(valorObligacion);
    $('#text_valor_obligacion_pp_abono').priceFormat({
        allowNegative: true,
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //NUEVO VALOR OOBLIGACION - VALOR PRESENTE
    var valorObligacionPresente = valorObligacion - abono;
    $("#nuevo_valor_obligacion_abono").val(valorObligacionPresente);
    $("#text_nuevo_valor_obligacion_abono").text(valorObligacionPresente);
    $('#text_nuevo_valor_obligacion_abono').priceFormat({
        allowNegative: true,
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //PARA CALCULAR LOS REMANENTES SOLO SE TIENE EN CUENTA SI EL ABONO ES MAYOR AL VALOR DE LA OBLIGACION
    if (abono > valorObligacion){

        //SI EL VALOR DE LA OBLIGACION PRESENTE ES MENOR DE 0 SE VUELVE TODO 0
        if (valorObligacionPresente <= 0){
            $("#nuevo_valor_obligacion_abono").val(0);
            $("#text_nuevo_valor_obligacion_abono").text(0);
            $('#text_nuevo_valor_obligacion_abono').priceFormat({
                allowNegative: true,
                prefix: '$ ',
                centsSeparator: ',',
                thousandsSeparator: '.',
                centsLimit: 0
            });
        }

        valorObligacionPresente = (valorObligacionPresente * -1);

        //REMANENTES DISPONIBLES
        var remanentes = abono - parseFloat(valorObligacion);
        $("#text_remanentes_disponibles").text(remanentes);
        $('#text_remanentes_disponibles').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //DEVOLUCION REMANENTES
        var devolucion = remanentes - parseFloat(otros);
        $("#devolucion_remanentes_gmf").val(devolucion);
        $("#text_devolucion_remanentes_gmf").text(devolucion);
        $('#text_devolucion_remanentes_gmf').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //GMF
        var gmf = Math.round((devolucion * 0.4) / 100);
        $("#gmf_ppp").val(gmf);
        $("#text_gmf_ppp").text(gmf);
        $('#text_gmf_ppp').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        //MONTO A DEVOLVER
        var montoDevolver = devolucion - gmf;
        $("#monto_devolver").val(montoDevolver);
        $("#text_monto_devolver").text(montoDevolver);
        $('#text_monto_devolver').priceFormat({
            allowNegative: true,
            prefix: '$ ',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0
        });

        $(".devoluciones_abono").show();

    }

    //INTERESES MORA
    var interesesMora = valorObligacion - valorPresente;
    $("#intereses_mora_abono").val(interesesMora);

}

function editReliquidacionAbono(idReliquidacionPP, idReliquidacion){

    var idOperacion = $("#id_operacion").val();
    showLoading("Cargando información. Espere por favor...");
    $("#content_abonos").load('admindex.php', { Ajax:true, mod: 'reliquidaciones', action:'reliquidacionPPAbonos', idReliquidacionPP : idReliquidacionPP, idReliquidacion: idReliquidacion, id_operacion : idOperacion, tipo :'PPP'}, function () {
        closeNotify();
        $('#modalAbono').modal('show');
    });

}

function deleteReliquidacionAbono(idReliquidacionPP, idReliquidacion){

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=reliquidaciones&action=eliminarReliquidacionAbono&id_reliquidacion_pp=" + idReliquidacionPP
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                 cargarTipoReliquidacion(idReliquidacion,"PPP");
            }
    });


}

function bloquearRegistroBase(){

    formReadonly('datosRegistroReliquidacion');
}

function validarAbono(objAbono, objObligacion){

    $(".devoluciones_abono").hide();
    var abono = parseInt($("#" + objAbono).val());
    var obligacion = parseInt($("#" + objObligacion).val());
    if (abono > obligacion){
        $(".devoluciones_abono").show();
        //showError("El monto del abono (" + $("#" + objAbono).val() + ") no puedes ser superior al de la obligación.");
        //$("#" + objAbono).val("");
    }

}

function setDataInfoCliente(){

    $(".text_emisor").text($("#id_emisor option:selected").text());
    $(".text_pagador").text($("#id_pagador option:selected").text());
    $(".text_facturas").text($("#facturas_seleccionadas").val());
    $(".text_fecha_desembolso").text($("#fecha_desembolso").val());
    $(".text_fecha_pago_pactada").text($("#fecha_pago_pactada").val());
    $(".text_fecha_real_pago").text($("#fecha_movimiento").val());
}

</script>
<?php

    $hayDatos = false;
    $esRegistroBase = true;
    $valorPresente = 0;
    $numeroRegistro = 1;
    $numeroRegistroAbono = 0;
    $agregaAbonos = true;
    $rsDataReliquidacionPPImpresion = $rsDataReliquidacionPP;
    while (!$rsDataReliquidacionPP->EOF){

        $hayDatos = true;
        $idReliquidacionPP = $rsDataReliquidacionPP->fields["id_reliquidacion_pp"];

        if ($esRegistroBase){
            $this->reliquidacionPPBase($idReliquidacionPP, $idOperacion, "PPP");
        }
        else{

            $numeroRegistroAbono++;

            //IMPRIMIMOS LOS ABONOS DE LA RELIQUIDACION
            ?>
                <div class="container-fluid">
                <table id="listReliquidaciones_<?=$idReliquidacionPP?>" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
                    <thead>
                        <tr class="alert alert-success">
                            <th>Opciones</th>
                            <th>Valor presente</th>
                            <th>Fecha último abono</th>
                            <th>Fecha pago</th>
                            <th>Tasa %</th>
                            <th>Valor obligación</th>
                            <th>Abono</th>
                            <th>Nuevo valor obligación</th>
                            <th>Otros</th>
                            <th>Devolución remanentes <br/> antes GMF</th>
                            <th>GMF</th>
                            <th>Monto a devolver</th>
                            <th>Rtf Interés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center">
                                <?php
                                    if ($rsDataReliquidacionPP->_numOfRows == $numeroRegistro && $operacion->estado == 1)
                                    {
                                ?>
                                    <a href="javascript:editReliquidacionAbono(<?=$idReliquidacionPP?>, <?=$idReliquidacion?>);"><img border="0" alt="Editar re-liquidación" title="Editar re-liquidación" src="./images/editar.png"></a>
                                    <a href="javascript:deleteReliquidacionAbono(<?=$idReliquidacionPP?>, <?=$idReliquidacion?>);"><img border="0" alt="Eliminar re-liquidación" title="Eliminar re-liquidación" src="./images/eliminar.png"></a>
                                <?php
                                    }
                                    else{
                                        echo "N/D";
                                    }
                                ?>
                            </td>
                            <td><?=formato_moneda($valorPresente)?></td>
                            <td><?=$rsDataReliquidacionPP->fields["fecha_pago_pactada"]?></td>
                            <td><?=$rsDataReliquidacionPP->fields["fecha_real_pago"]?></td>
                            <td align="center"><?=$rsDataReliquidacionPP->fields["tasa"]?> %</td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["valor_obligacion_pp"])?></td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["abono"])?></td>
                            <td align="right">
                                <?php
                                    if ($rsDataReliquidacionPP->fields["abono"] > $rsDataReliquidacionPP->fields["valor_obligacion_pp"])
                                        echo formato_moneda(0);
                                    else
                                        echo formato_moneda($rsDataReliquidacionPP->fields["valor_obligacion_pp"] - $rsDataReliquidacionPP->fields["abono"]);
                                ?>
                            </td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["otros"])?></td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["devolucion_remanentes"])?></td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["gmf"])?></td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["monto_devolver"])?></td>
                            <td align="right"><?=formato_moneda($rsDataReliquidacionPP->fields["rtf_intereses"])?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <script type="text/javascript">
                $(document).ready(function() {
                    $('#listReliquidaciones_<?=$idReliquidacionPP?>').dataTable({"bInfo": false,"bPaginate": false, "searching":false, "bSort": false});
                });
                </script>

            <?php
                $montoDevolver = $rsDataReliquidacionPP->fields["monto_devolver"];
        }

        $valorPresente = $rsDataReliquidacionPP->fields["nuevo_valor_obligacion"];
        $fechaUltimoAbono = $rsDataReliquidacionPP->fields["fecha_pago_pactada"];
        $esRegistroBase = false;
        $numeroRegistro++;

        //DETERMINAMOS SI SE PUEDEN SEGUIR AGREGANDO ABONOS
        if ($valorPresente <= 0)
            $agregaAbonos = false;

        $rsDataReliquidacionPP->MoveNext();
    }

    if (!$hayDatos)
        $this->reliquidacionPPBase(0, $idOperacion,"PPP");
?>

<?php
    //VALIDAMOS SI HAY ABONOS PARA BLOQUEAR EL REGISTRO BASE
    if ($numeroRegistroAbono > 0){
?>
    <script>
        $(document).ready(function () {
            bloquearRegistroBase();
        });
    </script>
<?php
    }
?>

<?php
    //DETERMINAMOS SI SE PUEDEN SEGUIR AGREGANDO ABONOS
    if (!$agregaAbonos){
?>
    <script>
        $(document).ready(function () {
            $("#btnAgregarAbono").hide();
        });
    </script>
<?php
    }
?>

<?php
    //DETERMINAMOS SI HAY MONTO A DEVOLVER
    if ($montoDevolver > 0){
?>
    <script>
        $(document).ready(function () {
            $("#id_factura_abonada").removeAttr("disabled");
            $(".select2-input").removeAttr("readonly");
            $(".datosRegistroReliquidacion_btnSave").show();
        });
    </script>
<?php
    }
?>

