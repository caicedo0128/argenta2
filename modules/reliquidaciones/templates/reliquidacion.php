<script type="text/javascript">

$(document).ready(function() {
    oTable = $('#listFacturasReliquidacion').dataTable({ "bStateSave": true, "bInfo": false,"bPaginate": false, "searching":false, "bSort": false});
    $("#id_factura_abonada").select2({ placeholder: 'Seleccione uno...',allowClear: true});
});

function saveReliquidacion(){

    validateForm("datosRegistroReliquidacion");

    //DETERMINAMOS SI HAY SELECCIONADO AL MENOS UN REGISTRO
    msjError = "<br/>Debe seleccionar al menos una factura para generar la solicitud.";
    $("input:checked").each(function (id) {
        msjError = "";
    });

    /*if ($("#id_factura_abonada").val() != "" && $("#valor_abonado").val() == ""){
        msjError = "<br/>Si hay una factura abonada, debe incluir el valor del abono";
    }*/

    if ($("#datosRegistroReliquidacion").valid() && msjError == ""){

        enabledForm("datosRegistroReliquidacion");
        showLoading("Enviando informacion. Espere por favor...");
        var dataForm = "Ajax=true&" + $("#datosRegistroReliquidacion").serialize();
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
						loader();
						$("#content_reliquidaciones").load('admindex.php', { Ajax:true, id_reliquidacion: response.IdReliquidacion, mod: 'reliquidaciones', action:'reliquidacion', id_operacion:'<?=$idOperacion?>'}, function () {
							loader();
						});                        
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados." + msjError);
    }
}

function cargarReliquidacionPorTipo() {

    var idReliquidacion = $("#id_reliquidacion").val();
    var idTipoReliquidacion = $("#id_tipo_reliquidacion").val();

    if (idTipoReliquidacion != ""){

		//SE VALIDA CON EL ARREGLO DE ESTADOS O TIPOS DE LIQUIDACION
		if (idTipoReliquidacion == 3)
			cargarTipoReliquidacion(idReliquidacion,'PT');
		else if (idTipoReliquidacion == 4)
			cargarTipoReliquidacion(idReliquidacion,'PP');
		else if (idTipoReliquidacion == 5)
			cargarTipoReliquidacion(idReliquidacion,'PTA');
		else if (idTipoReliquidacion == 6)
			cargarTipoReliquidacion(idReliquidacion,'PPA');
		else if (idTipoReliquidacion == 7)
			cargarTipoReliquidacion(idReliquidacion,'PTP');
		else if (idTipoReliquidacion == 8)
			cargarTipoReliquidacion(idReliquidacion,'PPP');

		if (idTipoReliquidacion == 5 || idTipoReliquidacion == 6)
			$(".actualizar_fact").show();

    }
}

function cargarTipoReliquidacion(idReliquidacion, tipo) {

    var idOperacion = $("#id_operacion").val();
    var fechaOperacion = $("#fecha_operacion").val();

    //CAMBIAMOS LA ACCION
    var action = "";
    if(tipo == 'PT')
         action = "reliquidacionPT";
    else if (tipo == 'PP')
         action = "reliquidacionPP";
    else if (tipo == 'PTA')
         action = "reliquidacionPTA";
    else if (tipo == 'PPA')
         action = "reliquidacionPPA";
    else if (tipo == 'PTP')
         action = "reliquidacionPTP";
    else if (tipo == 'PPP')
         action = "reliquidacionPPP";

    loader();
    window.setTimeout(function(){
		$("#content_reliquidacion").load('admindex.php', { Ajax:true, mod: 'reliquidaciones', action:action, id_reliquidacion : idReliquidacion, id_operacion : idOperacion}, function () {
			loader();

			//ESTE ES UN AJUSTE TEMPORAL POR EL CAMBIO DE CALCULOS EN LAS RELIQUIDACIONES
			if (fechaOperacion < '2019-01-01'){

				//LLAMAMOS LA FUNCION DE CALCULOS
				if(tipo == 'PT')
					calcularReliquidacionPTAnterior();
				else if (tipo == 'PP')
					calcularReliquidacionPPAnterior();
				else if (tipo == 'PTA')
					calcularReliquidacionPTAAnterior();
				else if (tipo == 'PPA')
					calcularReliquidacionPPAAnterior();
				else if (tipo == 'PTP')
					calcularReliquidacionPTPAnterior();
				else if (tipo == 'PPP')
					calcularReliquidacionPPPAnterior();
			}
			else{

				//LLAMAMOS LA FUNCION DE CALCULOS
				if(tipo == 'PT')
					calcularReliquidacionPT();
				else if (tipo == 'PP')
					calcularReliquidacionPP();
				else if (tipo == 'PTA')
					calcularReliquidacionPTA();
				else if (tipo == 'PPA')
					calcularReliquidacionPPA();
				else if (tipo == 'PTP')
					calcularReliquidacionPTP();
				else if (tipo == 'PPP')
					calcularReliquidacionPPP();
			}

			$(".factura_abonada").show();
			actualizarFacturaAbonada('id_factura_abonada');

			//DETERMINAMOS SI EL ESTADO DE LA OPERACION ESTA CERRADO PARA BLOQUEAR EL FORMULARIO
			if ($("#estado_operacion").val() == 2)
				formReadonly('datosRegistroReliquidacion');
		});
    },1000);
}

function obtenerValor(idObjBase, idObjDestino, objChecked, isSpan){

    var valor = 0;
    if (objChecked.checked){

        if (isSpan == 0)
            valor = $("#" + idObjBase).val();
        else
            valor = $("." + idObjBase).unmask().trim();
    }
    else
        valor = "";

    $("#" + idObjDestino).val(valor);

}

function actualizarFacturaAbonada(idobj){

    var textoFacturaAbonada = "Ninguna";
    var facturaAbonada = $("#" + idobj + " option:selected").text();
    if (facturaAbonada != 'Seleccione uno...' && facturaAbonada != ''){
        var arrDatosFactura = facturaAbonada.split("-");
        var arrDatosFactura1 = arrDatosFactura[0].split(":");
        textoFacturaAbonada = arrDatosFactura1[1];
    }

    $(".text_factura_abonada").text(textoFacturaAbonada);

    //LLAMAMOS LA FUNCION DE CALCULOS
    var idTipoReliquidacion = $("#id_tipo_reliquidacion").val();
    var fechaOperacion = $("#fecha_operacion").val();
	//ESTE ES UN AJUSTE TEMPORAL POR EL CAMBIO DE CALCULOS EN LAS RELIQUIDACIONES
	if (fechaOperacion < '2019-01-01'){
		if (idTipoReliquidacion == 3)
			calcularReliquidacionPTAnterior();
		else if (idTipoReliquidacion == 4)
			calcularReliquidacionPPAnterior();
		else if (idTipoReliquidacion == 5)
			calcularReliquidacionPTAAnterior();
		else if (idTipoReliquidacion == 6)
		   calcularReliquidacionPPAAnterior();
		else if (idTipoReliquidacion == 7)
		   calcularReliquidacionPTPAnterior();
		else if (idTipoReliquidacion == 8)
			calcularReliquidacionPPPAnterior();
	}
	else{
		if (idTipoReliquidacion == 3)
			calcularReliquidacionPT();
		else if (idTipoReliquidacion == 4)
			calcularReliquidacionPP();
		else if (idTipoReliquidacion == 5)
			calcularReliquidacionPTA();
		else if (idTipoReliquidacion == 6)
		   calcularReliquidacionPPA();
		else if (idTipoReliquidacion == 7)
		   calcularReliquidacionPTP();
		else if (idTipoReliquidacion == 8)
			calcularReliquidacionPPP();
	}
}

function actualizarFacturaReliquidada(idOperacionFactura, idOperacion){

    showLoading("Enviando informacion. Espere por favor...");
    var fechaRealPago = $("#fecha_real_pago").val();
    var dataForm = "Ajax=true&mod=reliquidaciones&action=actualizarFacturaDesdeReliquidacion";
    var strUrl = "admindex.php";
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:{
                Ajax:true,
                mod:'reliquidaciones',
                action:'actualizarFacturaDesdeReliquidacion',
                id_factura:idOperacionFactura,
                id_operacion:idOperacion,
                fecha_real_pago:fechaRealPago
            },
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
            }
    });

}

function descargarReporte(idReliquidacion){

    showLoading("Descargando reporte. Espere por favor...");

    nombreReporte = "ReporteReliquidacion.pdf";

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=reliquidaciones&action=generarReporteFacturasLiquidadas&es_excel=false&id_reliquidacion=" + idReliquidacion;
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("reliquidaciones");
            $("#formMail input[id=action]").val("guardarReporteReliquidacion");
            $("#formMail input[id=__dataMail]").val(response);

            var dataForm = "Ajax=true&" + $("#formMail").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    downloadURI("./gallery/reliquidaciones/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

function descargarReporteFacturacion(idReliquidacion){

    showLoading("Descargando reporte. Espere por favor...");

    nombreReporte = "ReporteReliquidacionFacturacion.pdf";

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=reliquidaciones&action=generarReporteFacturasLiquidadas&facturacion=true&es_excel=false&id_reliquidacion=" + idReliquidacion;
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("reliquidaciones");
            $("#formMail input[id=action]").val("guardarReporteReliquidacion");
            $("#formMail input[id=__dataMail]").val(response);

            var dataForm = "Ajax=true&" + $("#formMail").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    downloadURI("./gallery/reliquidaciones/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

function actualizacionDatosFacturacion(idReliquidacion,idOperacion){

    showLoading("Actualizando informacion. Espere por favor...");
    var strUrl = "admindex.php";
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:{
                Ajax:true,
                mod:'reliquidaciones',
                action:'actualizacionDatosFacturacion',
                id_reliquidacion:idReliquidacion,
                id_operacion:idOperacion
            },
            success: function (response) {
            	closeNotify();
				loader();
				$("#content_reliquidaciones").load('admindex.php', { Ajax:true, id_reliquidacion: idReliquidacion, mod: 'reliquidaciones', action:'reliquidacion', id_operacion:idOperacion}, function () {
					loader();
				});   
            }
    });

}

</script>
<style>
.actualizar_fact{
	display:none;
}
</style>
<div class="panel panel-primary">
    <div class="panel-body">
        Registro de re-liquidacion
        <div class="cerrar_form" onclick="cargarReliquidaciones();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosRegistroReliquidacion" method="post" name="datosRegistroReliquidacion" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="reliquidaciones" />
            <input type="hidden" name="action" value="saveReliquidacion" />
            <input type="hidden" name="id_reliquidacion" id="id_reliquidacion" value="<?=$idReliquidacion?>" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />
            <input type="hidden" name="estado_operacion" id="estado_operacion" value="<?=$operacion->estado?>" />
            <div class="row">
                <div class="col-md-12">
                    <span class="alert alert-warning">Facturas sin re-liquidación</span>
                    <div style="float:right;">
                    <!--a href="javascript:;" onclick="formReporteReliquidacion('reliquidacion_cliente');" title="Cliente" class="btn btn-warning"><i class="fa fa-user fa-lg"></i>Reporte cliente</a-->
                    <a href="admindex.php?mod=reliquidaciones&action=generarReporteCliente&id_reliquidacion=<?=$idReliquidacion?>" target="_blank" title="Cliente" class="btn btn-warning"><i class="fa fa-user"></i>Reporte cliente</a>
                    <a href="admindex.php?Ajax=true&mod=reliquidaciones&action=generarReporteFacturasLiquidadas&id_reliquidacion=<?=$idReliquidacion?>" target="_blank" title="Reliquidación" class="btn btn-primary"><i class="fa fa-table"></i>Reporte liquidación facturas</a>
					<?php
						//SI SON PARCIALES
						if ($reliquidacion->id_tipo_reliquidacion==4 || $reliquidacion->id_tipo_reliquidacion==6 || $reliquidacion->id_tipo_reliquidacion==8){
							echo "<a href=\"javascript:descargarReporte(".$idReliquidacion.");\" title=\"Descargar reporte\" class=\"btn btn-success\"><i class=\"fa fa-download\"></i>Descargar PDF</a>";	
						}
						else{
							echo "<a href=\"javascript:descargarReporteFacturacion(".$idReliquidacion.");\" title=\"Descargar reporte\" class=\"btn btn-success\"><i class=\"fa fa-download\"></i>Descargar PDF</a>";	
						}
					?>                       
                    </div>
                    <div class="row" style="height:10px;">&nbsp;</div>
                    <table id="listFacturasReliquidacion" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Fecha de pago</th>
                            <th>Nro. Factura</th>
                            <th>Aplica OD</th>
                            <th>Valor neto</th>
                            <th>Valor futuro</th>
                            <th>Descuento total</th>
                            <th>Margen inversionista</th>
                            <th>Margen Argenta</th>
                            <th>Valor giro final</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            while(!$rsFacturasOperacion->EOF)
                            {
                                $idOperacionFactura = $rsFacturasOperacion->fields["id_operacion_factura"];
                                $idReliquidacionFactura = $rsFacturasOperacion->fields["id_reliquidacion"];

                                $strChecked = "";
                                if ($idReliquidacion == $idReliquidacionFactura)
                                    $strChecked = "checked='checked'";
                        ?>
                                <tr>
                                    <td align="center">
                                        <input type="hidden" id="valor_futuro_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["valor_futuro"]?>">
                                        <input type="hidden" id="valor_neto_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["valor_neto"]?>">
                                        <input type="hidden" id="valor_iva_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["iva_fra_asesoria"]?>">
                                        <input type="hidden" id="fra_argenta_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["fra_argenta"]?>">
                                        <input type="hidden" id="margen_inversionista_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["margen_inversionista"]?>">
                                        <input type="hidden" id="fecha_pago_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["fecha_pago"]?>">
                                        <input type="hidden" id="nro_factura_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["num_factura"]?>">
                                        <input type="hidden" id="descuento_total_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["descuento_total"]?>">
                                        <input type="hidden" id="giro_antes_gmf_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["giro_antes_gmf"]?>">
                                        <input type="hidden" name="diferencia_descuento_total_xfra_<?=$idOperacionFactura?>" id="diferencia_descuento_total_xfra_<?=$idOperacionFactura?>"value="0">
                                        <input type="hidden" name="diferencia_fra_argenta_xfra_<?=$idOperacionFactura?>" id="diferencia_fra_argenta_xfra_<?=$idOperacionFactura?>" value="0">
                                        <input type="hidden" name="diferencia_iva_xfra_<?=$idOperacionFactura?>" id="diferencia_iva_xfra_<?=$idOperacionFactura?>" value="0">
                                        <input type="hidden" name="diferencia_margen_xfra_<?=$idOperacionFactura?>" id="diferencia_margen_xfra_<?=$idOperacionFactura?>" value="0">
                                        <input type="hidden" name="aplica_otro_descuento_<?=$idOperacionFactura?>" id="aplica_otro_descuento_<?=$idOperacionFactura?>" value="<?=$rsFacturasOperacion->fields["aplica_otros"]?>">
                                        <input type="checkbox" id="<?=$idOperacionFactura?>" class="checks_facturas_reliquidar" name="facturas[]" value="<?=$idOperacionFactura?>" onclick="cargarReliquidacionPorTipo();" <?=$strChecked?>>
                                    </td>
                                    <td><?=$rsFacturasOperacion->fields["fecha_pago"]?></td>
                                    <td><?=$rsFacturasOperacion->fields["num_factura"]?> <a href="javascript:actualizarFacturaReliquidada(<?=$idOperacionFactura?>,<?=$idOperacion?>);" class="actualizar_fact" title="Actualizar valores de acuerdo a reliquidación(Solo PTA y PPA)">ACT</a></td>
                                    <td align="center"><?=($rsFacturasOperacion->fields["aplica_otros"]==1?"SI":"NO")?></td>
                                    <td align="right"><?=formato_moneda($rsFacturasOperacion->fields["valor_neto"])?></td>
                                    <td align="right"><?=formato_moneda($rsFacturasOperacion->fields["valor_futuro"])?></td>
                                    <td align="right"><?=formato_moneda($rsFacturasOperacion->fields["descuento_total"])?></td>
                                    <td align="right"><?=formato_moneda($rsFacturasOperacion->fields["margen_inversionista"])?></td>
                                    <td align="right"><?=formato_moneda($rsFacturasOperacion->fields["margen_argenta"])?></td>
                                    <td align="right"><?=formato_moneda($rsFacturasOperacion->fields["valor_giro_final"])?></td>

                                </tr>
                        <?php
                                $rsFacturasOperacion->MoveNext();
                            }
                        ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-1 labelCustom">
                	Fecha registro:
                	<div class="form-control" disabled="disabled">
                		<?=($reliquidacion->fecha_registro != ""?$reliquidacion->fecha_registro:date("Y-m-d"))?>
                	</div>
                </div>
                <div class="col-md-3 labelCustom">
                	Tipo reliquidacion:
					<div class="">
					<?php

						$sede_select = new Select("id_tipo_reliquidacion","tipo",$this->arrTiposReliquidacion,"",1,"", "form-control", 0, "", "cargarReliquidacionPorTipo();", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $reliquidacion->id_tipo_reliquidacion;
						echo $sede_select->genCode();
					?>
					</div>    
				</div>
                <div class="col-md-1 labelCustom">
                	Número factura:
                	<div>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("numero_factura", "numero_factura", 1, $reliquidacion->num_factura, "form-control number", 50, "", "", "","","return IsNumber(event);");
					?>
					</div>
				</div>
				<div class="col-md-1 labelCustom">
					Aplica 4xmil:
					<div id="divRadioaplica_impuesto_reli" class="radioValidate">
					<?php
						$c_radio = new Radio;
						$arrSiNo = array("1"=>"Si","2"=>"No");
						$c_radio->Radio("aplica_impuesto_reli","aplica_impuesto_reli",$arrSiNo,"", 1, $reliquidacion->aplica_impuesto, "", 0, "customValidateRadio('aplica_impuesto_reli');actualizarFacturaAbonada('id_factura_abonada');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
				</div>				
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-6 labelCustom">
                	Observaciones:
                	<div class="">
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones", "Observaciones", 0, $reliquidacion->observaciones, "form-control", 60, 2);
					?>
					</div>
				</div>
            </div>
            <hr/>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row" id="content_reliquidacion">
            </div>
            <div class="row factura_abonada" id="factura_abonada" style="display:none;">
                <div class="col-md-12 labelCustom">
                	Factura(s) abonada(s):
					<div class="">
						<?php
							$select_fact = new Select("id_factura_abonada","id_factura_abonada",$arrFacturasEmisor,"",0,"", "", 1, "", "actualizarFacturaAbonada('id_factura_abonada');", 0);
							$select_fact->enableBlankOption();
							$select_fact->Default = $arrFacturasAbonadas;
							echo $select_fact->genCode();
						?>
					</div>
				</div>					
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <!--div class="row factura_abonada" style="display:none;">
                <div class="col-md-2 labelCustom">Valor abonado:</div>
                <div class="col-md-2">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("valor_abonado", "valor_abonado", 0, $reliquidacion->valor_abonado, "form-control number", 50, "", "", "","","return IsNumber(event);");
                ?>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div-->
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datosRegistroReliquidacion_btnSave" onclick="saveReliquidacion();">
				<?php
					if ($idReliquidacion != 0){
				?>
                	<input type="button" value="Actualizar facturación" class="btn btn-primary" onclick="actualizacionDatosFacturacion(<?=$idReliquidacion?>,<?=$idOperacion?>);">
                <?php
                	}
                ?>
            </center>
        </form>
</div>
<?php
    if ($idReliquidacion != 0){
?>
    <script>
        $(document).ready(function () {
            cargarReliquidacionPorTipo();

            //DESACTIVAMOS CAMPOS NECESARIOS
            $("#id_factura").attr("disabled","disabled");
            $("#id_tipo_reliquidacion").attr("disabled","disabled");
        });
    </script>
<?php
}
?>

<?php
    //BLOQUEAMOS LOS FORMULARIOS
    if ($operacion->estado == 2){
?>
    <script>
        $(document).ready(function () {
            formReadonly('datosRegistroReliquidacion');
        });
    </script>
<?php
}
?>


