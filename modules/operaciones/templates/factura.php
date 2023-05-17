<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_pago').datetimepicker({ format: 'YYYY-MM-DD', showClear: true }).data('DateTimePicker').minDate(moment('<?=$fechaMinimaFactura?>')).maxDate(moment('<?=$fechaMaximoPagoFactura?>'));
	$('#fecha_vencimiento_factura').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
	$('#fecha_emision').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $("#fecha_pago").on("dp.change", function(e) {
        liquidarFactura(false);
    });
});

function saveFactura(){

    validateForm("datosFactura");

    if ($("#datosFactura").valid()){

        showLoading("Enviando información. Espere por favor...");
        var dataForm = new FormData(document.getElementById("datosFactura"));
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                mimeType: "multipart/form-data",
				cache: false,
				contentType: false,
				processData: false,
                success: function (response) {
                    window.setTimeout(function(){
                        closeNotify();
                        if (response.Success) {
                        	showSuccess(response.Message);
                            cargarFacturas();
                        }
                        else{
                        	showError(response.Message,5000);
                        }
                    },800);
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function liquidarFactura(validarCupo){

    var porcentajeDescuento = $("#porcentaje_descuento").val();
    var tasaInversionista = $("#tasa_inversionista").val();
    var factor = $("#factor").val();
    var valorNeto = $("#valor_neto").val();
    var otrosOperacion = $("#otros_operacion").val();
    var fechaInicial = new Date($("#fecha_operacion").val());
    var fechaFinal = new Date($("#fecha_pago").val());
    var diasDiferencia = DateDiff.inDays(fechaInicial, fechaFinal);

    //VALOR FUTURO
    var valorFuturo = Math.round((valorNeto * porcentajeDescuento) / 100);
    $("#valor_futuro").val(valorFuturo);
    $("#text_valor_futuro").text(valorFuturo);
    $('#text_valor_futuro').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //DESCUENTO TOTAL
    var descuentoTotal = Math.round((((diasDiferencia * factor) / 100) / 30) * valorFuturo);
    $("#descuento_total").val(descuentoTotal);
    $("#text_descuento_total").text(descuentoTotal);
    $('#text_descuento_total').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //MARGEN INVERSIONISTA
    var potenciaMargen = Math.pow(1 + (tasaInversionista / 100),(diasDiferencia / 365));
    var margenInversionista =  Math.round(valorFuturo-(valorFuturo / potenciaMargen));
    $("#margen_inversionista").val(margenInversionista);
    $("#text_margen_inversionista").text(margenInversionista);
    $('#text_margen_inversionista').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //MARGEN ARGENTA
    var margeArgenta =  descuentoTotal - margenInversionista;
    $("#margen_argenta").val(margeArgenta);
    $("#text_margen_argenta").text(margeArgenta);
    $('#text_margen_argenta').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //IVA FRA ASESORIA
    var ivaFraAsesoria =  Math.round((margeArgenta * 19) / 100) ;
    $("#iva_fra_asesoria").val(ivaFraAsesoria);
    $("#text_iva_fra_asesoria").text(ivaFraAsesoria);
    $('#text_iva_fra_asesoria').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //FRA ARGENTA
    var fraArgenta =  margeArgenta + ivaFraAsesoria;
    $("#fra_argenta").val(fraArgenta);
    $("#text_fra_argenta").text(fraArgenta);
    $('#text_fra_argenta').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //GIRO ANTES GMF
    var aplicaOtros = $("input[id='aplica_otros']:checked").val();

    //DETERMINAMOS SI NO SE DEBE APLICAR EL VALOR DE OTROS DE LA OPERACION
    if (aplicaOtros == 2)
    	otrosOperacion = 0;

    var giroAntesGMF =  Math.round(valorFuturo - descuentoTotal - otrosOperacion);
    $("#giro_antes_gmf").val(giroAntesGMF);
    $("#text_giro_antes_gmf").text(giroAntesGMF);
    $('#text_giro_antes_gmf').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //GMF
    var aplicaImpuesto = $("#aplica_impuesto_op").val();
    var factorGMF = 0.3984;
    if ($("#fecha_operacion").val() >= '2020-07-01' && aplicaImpuesto == 2)
        factorGMF = 0;

    var gmf =  Math.round((giroAntesGMF * factorGMF) / 100);
    $("#gmf").val(gmf);
    $("#text_gmf").text(gmf);
    $('#text_gmf').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

    //VALOR GIRO FINAL
    var valorGiroFinal =  giroAntesGMF - gmf;
    $("#valor_giro_final").val(valorGiroFinal);
    $("#text_valor_giro_final").text(valorGiroFinal);
    $('#text_valor_giro_final').priceFormat({
        prefix: '$ ',
        centsSeparator: ',',
        thousandsSeparator: '.',
        centsLimit: 0
    });

	var cupoPagador = parseFloat($("#cupo_pagador").val());
	var inversionesPagador = $("#inversiones_pagador").val();
	var totalCupoValidacionPagador = parseFloat(inversionesPagador) + parseFloat(valorGiroFinal);
	var cupoEmisor = parseFloat($("#cupo_emisor").val());
	var inversionesEmisor = $("#inversiones_emisor").val();
	var totalCupoValidacionEmisor = parseFloat(inversionesEmisor) + parseFloat(valorGiroFinal);

	//CALCULO PORCENTAJE DESCUENTO RADIAN
	if (valorNeto > 0){
		var porcentajeDescuento = parseFloat((descuentoTotal / valorNeto) * 100).toFixed(2);
		$("#porcentaje_descuento_radian").val(porcentajeDescuento);
	}

    //VALIDAMOS LOS CUPOS
	if (totalCupoValidacionPagador > cupoPagador && validarCupo){
		showError("El pagador no tiene cupo para esta factura");
		$("#valor_neto").val("");
		return false;
	}

	if (totalCupoValidacionEmisor > cupoEmisor && validarCupo){
		showError("El emisor no tiene cupo para esta factura");
		$("#valor_neto").val("");
		return false;
	}
}

function leerArchivoXML(){

	if ($("#file_factura_xml").val() == "")
		showError("Debe seleccionar un archivo XML para procesar");
	else{
	
		var selectedFile = document.getElementById('file_factura_xml').files[0];
		var descriptionExternalReference = "";
		var descriptionAttachment = "";
		var nroFactura = "";
		var cufe = "";		
		var fechaEmision = "";		
		var fechaVencimiento = "";		
		var totalNeto = "";	
		var totalBruto = "";	
		var emisorXML = "";	
		var identificacionEmisor = "";	
		var pagadorXML = "";	
		var identificacionPagador = "";	
		var prefijo = "";
		
		var reader = new FileReader();
		reader.onload = function(e) {
		   readXml=e.target.result;
		   var parser = new DOMParser();
		   var doc = parser.parseFromString(readXml, "application/xml");
		   
		   var attachDocument = doc.querySelectorAll('AttachedDocument');
		   attachDocument.forEach(elementData =>{
		   
		   		descriptionAttachment = elementData.querySelector('Attachment').querySelector('Description').innerHTML;		   	

				//QUITAMOS CARACTERES ESPECIALES
				descriptionAttachment = descriptionAttachment.replace('<![CDATA[','');
				descriptionAttachment = descriptionAttachment.replace(']]>','');
				
				var docDescriptionAttachment = parser.parseFromString(descriptionAttachment, "application/xml");
				
				var data = docDescriptionAttachment.querySelectorAll('Invoice');
				data.forEach(elementDataInternal =>{
					
					var docUBLExtensions = elementDataInternal.querySelector('UBLExtensions').innerHTML;

					let indiceInicial = docUBLExtensions.indexOf("<sts:Prefix>");
					let indiceFinal = docUBLExtensions.indexOf("</sts:Prefix>");
					if (indiceInicial != -1)
						prefijo = docUBLExtensions.substring((indiceInicial + 12), indiceFinal);					
				});
		   });
		   
		   var documentReference = doc.querySelectorAll('ExternalReference');					   
		   documentReference.forEach(element =>{
		   
		   		descriptionExternalReference = element.children[2].innerHTML;
		   		
		   		if (nroFactura == "" && cufe == ""){
		   			
		   			//QUITAMOS CARACTERES ESPECIALES
					descriptionExternalReference = descriptionExternalReference.replace('<![CDATA[','');
					descriptionExternalReference = descriptionExternalReference.replace(']]>','');		   		
					
					var docDescriptionExternalReference = parser.parseFromString(descriptionExternalReference, "application/xml");

					var data = docDescriptionExternalReference.querySelectorAll('Invoice');
					data.forEach(elementData =>{					

						nroFactura = elementData.querySelector('ID').innerHTML;
						nroFactura = nroFactura.replace(prefijo,'');
						cufe = elementData.querySelector('UUID').innerHTML;
						fechaEmision = elementData.querySelector('IssueDate').innerHTML;
						fechaVencimiento = elementData.querySelector('PaymentMeans').querySelector('PaymentDueDate').innerHTML;
						totalNeto = elementData.querySelector('LegalMonetaryTotal').querySelector('PayableAmount').innerHTML;
						totalBruto = elementData.querySelector('LegalMonetaryTotal').querySelector('PayableAmount').innerHTML;
						totalBruto = totalBruto.replace('.000000','');
						totalBruto = totalBruto.replace('.00','');						
						emisorXML = elementData.querySelector('AccountingSupplierParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('RegistrationName').innerHTML;
						identificacionEmisor = elementData.querySelector('AccountingSupplierParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('CompanyID').innerHTML;
						pagadorXML = elementData.querySelector('AccountingCustomerParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('RegistrationName').innerHTML;
						if (pagadorXML == ""){
							pagadorXML = elementData.querySelector('AccountingCustomerParty').querySelector('Party').querySelector('PartyName').querySelector('Name').innerHTML;
						}
						identificacionPagador = elementData.querySelector('AccountingCustomerParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('CompanyID').innerHTML;
		
						//SETEAMOS DATOS
						$("#fecha_emision").val(fechaEmision);
						$("#fecha_vencimiento_factura").val(fechaVencimiento);
						$("#num_factura").val(nroFactura);
						//$("#valor_neto").val(totalNeto.replace('.00',''));
						$("#valor_bruto").val(totalBruto);
						$("#cufe").val(cufe);
						$("#emisor_xml").val(emisorXML);
						$("#identificacion_emisor").val(identificacionEmisor);
						$("#pagador_xml").val(pagadorXML);
						$("#identificacion_pagador").val(identificacionPagador);
						$("#prefijo_rnd").val(prefijo);
						
					});
				}
	   		
		   });
		   
		   //LECTURA FORMATO XML 2
			if (nroFactura == "" && cufe == ""){
				
				var data = doc.querySelectorAll('Invoice');
				data.forEach(elementData =>{
				
					var docUBLExtensions = elementData.querySelector('UBLExtensions').innerHTML;
				
					let indiceInicial = docUBLExtensions.indexOf("<sts:Prefix>");
					let indiceFinal = docUBLExtensions.indexOf("</sts:Prefix>");
					if (indiceInicial != -1)
						prefijo = docUBLExtensions.substring((indiceInicial + 12), indiceFinal);

					nroFactura = elementData.querySelector('ID').innerHTML;
					nroFactura = nroFactura.replace(prefijo,'');
					cufe = elementData.querySelector('UUID').innerHTML;
					fechaEmision = elementData.querySelector('IssueDate').innerHTML;
					fechaVencimiento = elementData.querySelector('PaymentMeans').querySelector('PaymentDueDate').innerHTML;
					totalNeto = elementData.querySelector('LegalMonetaryTotal').querySelector('PayableAmount').innerHTML;
					totalBruto = elementData.querySelector('LegalMonetaryTotal').querySelector('PayableAmount').innerHTML;
					totalBruto = totalBruto.replace('.000000','');
					totalBruto = totalBruto.replace('.00','');
					emisorXML = elementData.querySelector('AccountingSupplierParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('RegistrationName').innerHTML;
					identificacionEmisor = elementData.querySelector('AccountingSupplierParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('CompanyID').innerHTML;
					pagadorXML = elementData.querySelector('AccountingCustomerParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('RegistrationName').innerHTML;
					if (pagadorXML == ""){
						pagadorXML = elementData.querySelector('AccountingCustomerParty').querySelector('Party').querySelector('PartyName').querySelector('Name').innerHTML;
					}
					identificacionPagador = elementData.querySelector('AccountingCustomerParty').querySelector('Party').querySelector('PartyLegalEntity').querySelector('CompanyID').innerHTML;

					//SETEAMOS DATOS
					$("#fecha_emision").val(fechaEmision);
					$("#fecha_vencimiento_factura").val(fechaVencimiento);
					$("#num_factura").val(nroFactura);
					//$("#valor_neto").val(totalNeto.replace('.00',''));
					$("#valor_bruto").val(totalBruto);
					$("#cufe").val(cufe);
					$("#emisor_xml").val(emisorXML);
					$("#identificacion_emisor").val(identificacionEmisor);
					$("#pagador_xml").val(pagadorXML);
					$("#identificacion_pagador").val(identificacionPagador);
					$("#prefijo_rnd").val(prefijo);

				});		   
			}
		   		  
		}
		reader.readAsText(selectedFile);		
	}
}

</script>
<style>
    #reliquidacion_factura span{
        font-weight:bold;
    }
    
    .pref_50{
    	width:40% !important;
    	float:left;
    	margin-right:10px;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-body">
        Registro de información de factura
        <div class="cerrar_form" onclick="cargarFacturas();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosFactura" method="post" name="datosFactura" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="operaciones" />
            <input type="hidden" name="action" value="saveFactura" />
            <input type="hidden" name="Ajax" id="Ajax" value="true" />
            <input type="hidden" name="id_factura" id="id_factura" value="<?=$idFactura?>" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />
            <input type="hidden" name="porcentaje_descuento" id="porcentaje_descuento" value="<?=$operacion->porcentaje_descuento?>" />
            <input type="hidden" name="tasa_inversionista" id="tasa_inversionista" value="<?=$operacion->tasa_inversionista?>" />
            <input type="hidden" name="fecha_operacion" id="fecha_operacion" value="<?=$operacion->fecha_operacion?>" />
            <input type="hidden" name="factor" id="factor" value="<?=$operacion->factor?>" />
            <input type="hidden" name="otros_operacion" id="otros_operacion" value="<?=$operacion->valor_otros_operacion?>" />
            <input type="hidden" name="aplica_impuesto_op" id="aplica_impuesto_op" value="<?=$operacion->aplica_impuesto?>" />
            <input type="hidden" name="cupo_pagador" id="cupo_pagador" value="<?=$arrDatosPagador["cupo"]?>" />
            <input type="hidden" name="inversiones_pagador" id="inversiones_pagador" value="<?=($arrDatosPagador["totalInversiones"] != ""?$arrDatosPagador["totalInversiones"]:"0")?>" />
            <input type="hidden" name="cupo_emisor" id="cupo_emisor" value="<?=$arrDatosEmisor["cupo"]?>" />
            <input type="hidden" name="inversiones_emisor" id="inversiones_emisor" value="<?=($arrDatosEmisor["totalInversiones"] != ""?$arrDatosEmisor["totalInversiones"]:"0")?>" />
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">Fecha registro:
                	<div class="form-control" disabled="disabled"><?=($factura->fecha_registro != ""?$factura->fecha_registro:date("Y-m-d"))?></div>
                </div>
				<div class="col-md-2 labelCustom">
					Plazo pagador:
                	<div class="label label-success label-custom" style="">30-<?=$plazoMaximo?> días</div>
                </div>        
				<div class="col-md-4 labelCustom">
					Archivo XML:
					<?php
						$c_filebox = new FileBox;
						echo $c_filebox->Filebox ("file_factura_xml", "file_factura_xml", 0, $factura->archivo_xml, "form-control", 30, "", "", "");
						echo "<a href=\"javascript:leerArchivoXML()\" title=\"Leer datos XML\" class=\"text-success\"><i class=\"fa fa-upload\"></i> Procesar</a>";
						if ($factura->archivo_xml)
							echo "<a href='".$this->rutaArchivosFacturasFisicas."/".$factura->archivo_xml."' target='_blank' title='Ver XML'>Ver XML</a>";
					?>
                </div>               
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-4 labelCustom">
					CUFE:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("cufe", "cufe", 1, $factura->cufe, "form-control no_mayus", 50, "", "", "");
					?>
					</div>
                </div>   
                <div class="col-md-2 labelCustom">
					Emisor:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("emisor_xml", "emisor_xml", 1, $factura->emisor_xml, "form-control no_mayus", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Identificación emisor:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("identificacion_emisor", "identificacion_emisor", 1, $factura->identificacion_emisor, "form-control no_mayus", 50, "", "", "");
					?>
					</div>
                </div>  
                <div class="col-md-2 labelCustom">
					Pagador:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("pagador_xml", "pagador_xml", 1, $factura->pagador_xml, "form-control no_mayus", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Identificación pagador:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("identificacion_pagador", "identificacion_pagador", 1, $factura->identificacion_pagador, "form-control no_mayus", 50, "", "", "");
					?>
					</div>
                </div>                   
            </div>            
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">
					Fecha emisión:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_emision", "fecha_emision", 1, $factura->fecha_emision, "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Fecha vencimiento parcial:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_vencimiento_factura", "fecha_vencimiento_factura", 1, $factura->fecha_vencimiento, "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Fecha de pago:
					<div class="">
					<?php
						$fechaPagoFactura = $factura->fecha_pago;
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_pago", "fecha_pago", 1, $fechaPagoFactura, "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Prefijos: (ARG - RDN)
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("prefijo", "prefijo", 1, $factura->prefijo, "form-control pref_50", 50, "", "", "");
					?>
					</div>
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("prefijo_rnd", "prefijo_rnd", 0, $factura->prefijo_rnd, "form-control pref_50", 50, "", "", "");
					?>
					</div>					
                </div>
                <div class="col-md-2 labelCustom">
					Nro. factura:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("num_factura", "num_factura", 1, $factura->num_factura, "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <?php
                	if ($_SESSION["profile_text"]!="Cliente")
                	{
                ?>
						<div class="col-md-2 labelCustom">
							Aplica otros operación:
							<div id="divRadioaplica_otros" class="radioValidate">
							<?php
								$c_radio = new Radio;
								$arrSiNo = array("1"=>"Si","2"=>"No");
								$c_radio->Radio("aplica_otros","aplica_otros",$arrSiNo,"", 1, $factura->aplica_otros, "", 0, "customValidateRadio('aplica_otros');liquidarFactura(true);");
								while($tmp_html = $c_radio->next_entry()) {
									echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
								}
							?>
							</div>
						</div>
				<?php
					}
					else
						echo "<input type='hidden' name='aplica_otros' value='2'>";
				?>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">
					Valor neto:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("valor_neto", "valor_neto", 1, $factura->valor_neto, "form-control number", 50, "", "liquidarFactura(true);", "","","return IsNumber(event);");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Valor bruto:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("valor_bruto", "valor_bruto", 1, $factura->valor_bruto, "form-control number", 50, "", "liquidarFactura(true);", "","","return IsNumber(event);");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Porcentaje descuento:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("porcentaje_descuento_radian", "porcentaje_descuento_radian", 1, $factura->porcentaje_descuento, "form-control number", 50, "", "", "","","return IsNumber(event);");
					?>
					</div>
                </div>                
				<div class="col-md-4 labelCustom">
					Soporte:
					<div class="">
					<?php
						$requerido = 1;
						//DETERMINAMOS SI NO HAY FACTURA
						if ($idFactura != 0 || $operacion->tipo_operacion == 2)
							$requerido = 0;

						$c_filebox = new FileBox;
						echo $c_filebox->Filebox ("file_factura", "file_factura", $requerido, $factura->archivo, "form-control", 30, "", "", "");
						if ($factura->archivo)
							echo "<a href='".$this->rutaArchivosFacturasFisicas."/".$factura->archivo."' target='_blank' title='Ver factura'>Ver factura</a>";
					?>
					</div>
				</div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <?php
                	if ($_SESSION["profile_text"]!="Cliente")
                	{
                ?>
						<div class="col-md-4">
							<div class="alert alert-info">
								<i><b>Datos pagador:</b></i></br>
								<b>Cupo: <?=formato_moneda($arrDatosPagador["cupo"])?></b></br>
								<b>En operaciones: <?=formato_moneda($arrDatosPagador["totalInversiones"])?></b>
							</div>
						</div>
				<?php
					}
				?>
                <div class="col-md-4">
					<div class="alert alert-success">
						<i><b>Datos emisor:</b></i></br>
						<b>Cupo: <?=formato_moneda($arrDatosEmisor["cupo"])?></b></br>
						<b>En operaciones: <?=formato_moneda($arrDatosEmisor["totalInversiones"])?></b>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <b>Liquidación factura:</b>
            <hr/>
            <input type="hidden" name="valor_futuro" id="valor_futuro" value="<?=$factura->valor_futuro?>">
            <input type="hidden" name="descuento_total" id="descuento_total" value="<?=$factura->descuento_total?>">
            <input type="hidden" name="margen_inversionista" id="margen_inversionista" value="<?=$factura->margen_inversionista?>">
            <input type="hidden" name="margen_argenta" id="margen_argenta" value="<?=$factura->maragen_argenta?>">
            <input type="hidden" name="iva_fra_asesoria" id="iva_fra_asesoria" value="<?=$factura->iva_fra_asesoria?>">
            <input type="hidden" name="fra_argenta" id="fra_argenta" value="<?=$factura->fra_argenta?>">
            <input type="hidden" name="giro_antes_gmf" id="giro_antes_gmf" value="<?=$factura->giro_antes_gmf?>">
            <input type="hidden" name="gmf" id="gmf" value="<?=$factura->gmf?>">
            <input type="hidden" name="valor_giro_final" id="valor_giro_final" value="<?=$factura->valor_giro_final?>">
            <?php
            	if ($_SESSION["profile_text"]!="Cliente"){
            ?>
				<table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
						<tr>
							<td><b>Valor futuro:</b></td>
							<td><b>Descuento total:</b></td>
							<td><b>Interés corriente:</b></td>
							<td><b>Gestión de referenciación:</b></td>
						</tr>
						<tr>
							<td>
								<span id="text_valor_futuro"><?=$factura->valor_futuro?></span>
							</td>
							<td>
								<span id="text_descuento_total"><?=$factura->descuento_total?></span>
							</td>
							<td>
								<span id="text_margen_inversionista"><?=$factura->margen_inversionista?></span>
							</td>
							<td>
								<span id="text_margen_argenta"></span>
							</td>
						</tr>
						<tr>
							<td><b>Iva Fra asesoria:</b></td>
							<td><b>Fra Argenta:</b></td>
							<td><b>Giro antes GMF:</b></td>
							<td><b>GMF:</b></td>
						</tr>
						<tr>
							<td>
								<span id="text_iva_fra_asesoria"><?=$factura->iva_fra_asesoria?>
							</td>
							<td>
								<span id="text_fra_argenta"><?=$factura->fra_argenta?></span>
							</td>
							<td>
								<span id="text_giro_antes_gmf"><?=$factura->giro_antes_gmf?></span>
							</td>
							<td>
								<span id="text_gmf"><?=$factura->gmf?></span>
							</td>
						</tr>
						<tr>
							<td><b>Valor giro final:</b></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>
								<span id="text_valor_giro_final"><?=$factura->valor_giro_final?></span>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
				</table>
            <?php
            	}
            ?>
			<?php
            	if ($_SESSION["profile_text"]=="Cliente"){
            ?>
				<table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
						<tr>
							<td><b>Valor futuro:</b></td>
							<td><b>Descuento total:</b></td>
							<td><b>Interés corriente:</b></td>
							<td><b>Gestión de referenciación:</b></td>
							<td><b>Giro antes GMF:</b></td>
							<td><b>GMF:</b></td>
							<td><b>Valor giro final:</b></td>
						</tr>
						<tr>
							<td>
								<span id="text_valor_futuro"><?=$factura->valor_futuro?></span>
							</td>
							<td>
								<span id="text_descuento_total"><?=$factura->descuento_total?></span>
							</td>
							<td>
								<span id="text_margen_inversionista"><?=$factura->margen_inversionista?></span>
							</td>
							<td>
								<span id="text_margen_argenta"></span>
							</td>
							<td>
								<span id="text_giro_antes_gmf"><?=$factura->giro_antes_gmf?></span>
							</td>
							<td>
								<span id="text_gmf"><?=$factura->gmf?></span>
							</td>
							<td>
								<span id="text_valor_giro_final"><?=$factura->valor_giro_final?></span>
							</td>
						</tr>
				</table>
            <?php
            	}
            ?>
            <div class="row" style="height:10px;">&nbsp;</div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
			<?php
				if ($operacion->estado == 3 || $operacion->estado == 4 || ($operacion->estado == 6 && $_SESSION["profile_text"] != "Cliente" )){
			?>
				<center>
					<input type="button" value="Guardar factura" class="btn btn-primary datosFactura_btnSave" onclick="saveFactura();">
				</center>
			<?php
				}
			?>
        </form>
</div>
<?php
    if ($idFactura != 0){
?>
    <script>
        $(document).ready(function () {
            liquidarFactura(false);
        });
    </script>
<?php
}
?>

<?php
    //DETERMINAMOS SI LA FACTURA YA ESTA RELIQUIDADA PARA NO CAMBIAR SUS DATOS
    if ($idFactura != 0 && $factura->estado != 1){
?>
    <script>
        $(document).ready(function () {
            formReadonly('datosFactura');
        });
    </script>
<?php
}
?>

