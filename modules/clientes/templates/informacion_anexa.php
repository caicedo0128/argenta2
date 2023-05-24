<script type="text/javascript">

$(document).ready(function(){
    $('.txt_fecha_expedicion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $("#id_sector").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $("#id_ciiu").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $(".pais_expedicion").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $(".pais_ubicacion").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $(".porcentaje_vtas").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    formReadonly("datosRegistroEditarVinculacion");
});

function EditarVinculacion(){

	var msjError="";
    validateForm("datosRegistroEditarVinculacion");

	var itemSocio = $("#item_socio").val();
	var tieneBeneficiarioFinal = false;
	for (var i=1;i<=itemSocio;i++){
		var esBeneficiario = $('input:radio[id=beneficiario_final' + i + ']:checked').val();
		if (esBeneficiario == 1)
			tieneBeneficiarioFinal = true;
	}

	if (!tieneBeneficiarioFinal)
		msjError="<br/>Debe marcar un registro como beneficiario final.<br/>";

    if ($("#datosRegistroEditarVinculacion").valid() && msjError==""){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
        var dataForm = new FormData(document.getElementById("datosRegistroEditarVinculacion"));

        $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data: dataForm,
            mimeType: "multipart/form-data",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                closeNotify();
                if (response.Success){
                    showSuccess("Transacci�n exitosa. Espere por favor...");
                    cargarInfoAnexo();
                }
                else{
                    showError(response.Message, 5000);
                }
            }
        });
    }
    else {
        showError("Hay errores en el formulario." + msjError + "Por favor revise los campos marcados.");
    }
}

function iniciarEdicion(){
	quitarFormReadonly("datosRegistroEditarVinculacion");
}

function validarTipoCliente(idTipoTercero,idTipoTerceroSec){

	//ES PAGADOR
	if ((idTipoTercero==6 && idTipoTerceroSec == 0)){

		//QUITAMOS CAMPOS REQUERIDOS DE ALGUNOS
		$("#datosRegistroEditarVinculacion input").removeClass("required");
		$("#datosRegistroEditarVinculacion select").removeClass("required");
		$("#datosRegistroEditarVinculacion textarea").removeClass("required");
	}


}

function validarDescontarFacturas(descuentaFacturas, item){

	$(".monto_descontar" + item).hide();
	if (descuentaFacturas == 1)
		$(".monto_descontar" + item).show();
}

function agregarReferencia(){

	var item = parseInt($("#item_referencia").val());
	item = (item + 1);
	$("#item_referencia").val(item);
	$(".content_cliente_" + item).show();
	if (item > 1)
		$("#btn_quitar_ref").show();
}

function quitarReferencia(){

	var item = parseInt($("#item_referencia").val());
	$(".content_cliente_" + item).hide();
	item = (item - 1);
	$("#item_referencia").val(item);
	if (item <= 1)
		$("#btn_quitar_ref").hide();

}

function agregarSocio(){

	var item = parseInt($("#item_socio").val());
	item = (item + 1);
	$("#item_socio").val(item);
	$(".content_socio_" + item).show();
	if (item > 1)
		$("#btn_quitar_socio").show();
}

function quitarSocio(){

	var item = parseInt($("#item_socio").val());
	$(".content_socio_" + item).hide();
	item = (item - 1);
	$("#item_socio").val(item);
	if (item <= 1)
		$("#btn_quitar_socio").hide();

}

function validarAutoretenedor(autoretenedor){

	$(".txt_tarifa_fte").hide();
	if (autoretenedor == 1)
		$(".txt_tarifa_fte").show();
}

function validarReteIVA(retenedorIVA){

	$(".txt_tarifa_iva").hide();
	if (retenedorIVA == 1)
		$(".txt_tarifa_iva").show();
}

function validarReteICA(retenedorICA){

	$(".txt_tarifa_ica").hide();
	if (retenedorICA == 1)
		$(".txt_tarifa_ica").show();
}

function validarRetCuentasME(cuentasMe){

	$(".cuentas_me").hide();
	if (cuentasMe == 1)
		$(".cuentas_me").show();
}

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Informaci�n adicional del tercero</h4>
    </div>
</div>
<br/>
<div class="panel panel-primary" style="margin-top:20px;">
    <div class="panel-body">
        <form id="datosRegistroEditarVinculacion" method="post" name="datosRegistro" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="EditarVinculacion" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$this->id_cliente?>" />
			<div class="row-fluid alert alert-info">Informaci�n general del negocio</div>
			<div class="row">
                <div class="col-md-3 labelCustom">
					Sector:
					<div class="">
					<?php
						$sede_select = new Select("id_sector","id_sector",$arrSectores,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $clienteAdicional->id_sector;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Ciiu:
					<div class="">
					<?php
						$sede_select = new Select("id_ciiu","id_ciiu",$arrCiius,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $clienteAdicional->id_ciiu;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Tipo de empresa:
					<div class="">
					<?php
						$sede_select = new Select("tipo_empresa1","tipo_empresa1",$this->arrTipoEmpresa,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->tipo_empresa1;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Tipo de r�gimen:
					<div class="">
					<?php
						$sede_select = new Select("tipo_empresa","tipo_empresa",$this->arrTipos,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->tipo_empresa;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
			<div class="row">
                <div class="col-md-2 labelCustom">
					Gran Contribuyente:
					<div id="divRadiogran_contribuyente" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrSiNo = array("1"=>"Si","2"=>"No");
						$c_radio->Radio("gran_contribuyente","gran_contribuyente",$arrSiNo,"", 1, $clienteAdicional->gran_contribuyente, "", 0, "customValidateRadio('gran_contribuyente');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Auto Retenedor:
					<div id="divRadioautoretenedor" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("autoretenedor","autoretenedor",$arrSiNo,"", 1, $clienteAdicional->autoretenedor, "", 0, "customValidateRadio('autoretenedor');validarAutoretenedor(this.value);");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Rtf gesti�n referenciaci�n:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox("tarifa_autoretenedor", "tarifa_autoretenedor", 1, $clienteAdicional->tarifa_autoretenedor, "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Retenedor IVA:
					<div id="divRadiorete_iva" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("rete_iva","rete_iva",$arrSiNo,"", 1, $clienteAdicional->rete_iva, "", 0, "customValidateRadio('rete_iva');validarReteIVA(this.value);");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-1 labelCustom txt_tarifa_iva">
					Tarifa IVA:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox("tarifa_iva", "tarifa_iva", 1, $clienteAdicional->tarifa_iva, "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Rtf intereses:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox("rtf_intereses", "rtf_intereses", 1, $clienteAdicional->rtf_intereses, "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
                <div class="col-md-2 labelCustom">
					Retenedor ICA:
					<div id="divRadiorete_ica" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("rete_ica","rete_ica",$arrSiNo,"", 1, $clienteAdicional->rete_ica, "", 0, "customValidateRadio('rete_ica');validarReteICA(this.value)");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom txt_tarifa_ica">
					Tarifa ICA:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox("tarifa_ica", "tarifa_ica", 1, $clienteAdicional->tarifa_ica, "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
				<div class="col-md-2 labelCustom">
					Evoluci�n de ventas(<?=date("Y")-2?>):
					<div class="">
					<?php
						echo $c_textbox->Textbox("evolucion_vta_anio_anterior", "evolucion_vta_anio_anterior", 1, $clienteAdicional->evolucion_vta_anio_anterior, "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
				<div class="col-md-2 labelCustom">
					Evoluci�n de ventas(<?=date("Y")-1?>):
					<div class="">
					<?php
						echo $c_textbox->Textbox("evolucion_vta_anio_actual", "evolucion_vta_anio_actual", 1, $clienteAdicional->evolucion_vta_anio_actual, "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
				<div class="col-md-2 labelCustom">
					N�mero empleados:
					<div class="">
					<?php
						$sede_select = new Select("id_numero_empleados","id_numero_empleados",$arrNumEmpleados,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $clienteAdicional->numero_empleados;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
                <div class="col-md-8 labelCustom">
					Detalle su objeto social, servicio / productos / actividad:
					<div class="">
					<?php
						echo $c_textbox->Textbox("detalle_producto", "detalle_producto", 1, $this->detalle_producto, "form-control", 50, "", "", "");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					C�mo se enter� de nuestra compa�ia:
					<div class="">
					<?php
						$sede_select = new Select("id_referencia","id_referencia",$arrReferencias,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $clienteAdicional->id_como_se_entera;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Relaci�n principales clientes
            		<a href="javascript:agregarReferencia();" class="btn btn-primary btn-sm" title="Agregar referencia"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>
            		<a href="javascript:quitarReferencia();" id="btn_quitar_ref" class="btn btn-danger btn-sm" title="Quitar referencia" style="display:none;"><i class="fa fa-minus fa-lg"></i> Quitar �ltimo</a>
            </div>
            <div class="row-fluid">

                	<?php
                		$requerido = 1;
                		$visible = 1;
                		$i = 1;
                		$item_referencia = 1;
                		while(!$rsRefClientes->EOF || $i<=5)
                		{

                			if ($rsRefClientes->fields["id_cliente_referencia"]=="" || $rsRefClientes->fields["id_cliente_referencia"]==null){
                				$visible=0;
                			}
                	?>
							<div class="content_cliente_<?=$i?>" style="<?=($visible==1?"display:block;":"display:none;")?>">
							<div class="row <?=$well?>">
								<input type="hidden" id="id_cliente_referencia<?=$i?>" name="id_cliente_referencia<?=$i?>" value="<?=$rsRefClientes->fields["id_cliente_referencia"]?>">
								<input type="hidden" id="descontar_facturas<?=$i?>" name="descontar_facturas<?=$i?>" value="1">
								<input type="hidden" id="monto_descuento<?=$i?>" name="monto_descuento<?=$i?>" value="0">
								<div class="col-md-6 labelCustom">
									<span class="label label-info"><?=$i?></span> Raz�n social:
									<div class="">
									<?php
										echo $c_textbox->Textbox("ref_empresa".$i, "", $requerido, $rsRefClientes->fields["empresa"], "form-control", 30, "", "", "");
									?>
									</div>
								</div>
								<div class="col-md-3 labelCustom">
									Identificaci�n:
									<div class="">
									<?php
										echo $c_textbox->Textbox("ref_nit".$i, "", $requerido, $rsRefClientes->fields["nit"], "form-control", 30, "", "", "");
									?>
									</div>
								</div>
								<div class="col-md-3 labelCustom">
									% ventas totales de la empresa:
									<div class="">
									<?php
										$sede_select = new Select("porcentaje_vtas".$i,"porcentaje_vtas",$arrNumeros,"",$requerido,"", "form-control porcentaje_vtas", 0, "", "", 0);
										$sede_select->enableBlankOption();
										$sede_select->Default = $rsRefClientes->fields["porcentaje_vtas"];
										echo $sede_select->genCode();
									?>
									</div>
								</div>
							</div>
							<div class="row <?=$well?>" style="height:10px;">&nbsp;</div>
							<div class="row <?=$well?>">
								<div class="col-md-3 labelCustom">
									Plazo real de pago:
									<div class="">
									<?php
										$sede_select = new Select("id_plazo_pago".$i,"id_plazo_pago",$arrPlazoPago,"",$requerido,"", "form-control", 0, "", "", 0);
										$sede_select->enableBlankOption();
										$sede_select->Default = $rsRefClientes->fields["id_plazo_pago"];
										echo $sede_select->genCode();
									?>
									</div>
								</div>
								<div class="col-md-3 labelCustom">
									�Hace cuanto trabajas con �ste cliente?:
									<div class="">
									<?php
										$sede_select = new Select("id_relacion_comercial".$i,"id_relacion_comercial",$arrRelacionesComercial,"",$requerido,"", "form-control", 0, "", "", 0);
										$sede_select->enableBlankOption();
										$sede_select->Default = $rsRefClientes->fields["id_relacion_comercial"];
										echo $sede_select->genCode();
									?>
									</div>
								</div>
							</div>
							<hr/>
							</div>
                	<?php
                			if ($visible==1){
                				$rsRefClientes->MoveNext();
                				$item_referencia=$i;
                			}
                			$i++;

                		}
                		if ($item_referencia > 1){
                			echo "<script>$('#btn_quitar_referencia').show();</script>";
                		}

                		if ($rsRefClientes->_numOfRows == 0){
                			$item_referencia--;
                			echo "<script>agregarReferencia();</script>";
                		}
                	?>
					<input type="hidden" id="item_referencia" name="item_referencia" value="<?=$item_referencia?>">
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Conocimiento de socios, accionistas, representante legal (principal, suplente) y beneficiario final
            		<a href="javascript:agregarSocio();" id="btn_agregar_socio" class="btn btn-primary btn-sm" title="Agregar socio"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>
            		<a href="javascript:quitarSocio();" id="btn_quitar_socio" class="btn btn-danger btn-sm" title="Quitar socio" style="display:none;"><i class="fa fa-minus fa-lg"></i> Quitar �ltimo</a>
            </div>
            <div class="row-fluid">

                	<?php
                		$requerido = 1;
                		$visible = 1;
                		$i = 1;
                		$item_socio= 1;
						while(!$rsSocios->EOF || $i<=8)
                		{
							if ($rsSocios->fields["id_socio_accionista"]=="" || $rsSocios->fields["id_socio_accionista"]==null){
                				$visible=0;
                			}
                	?>
                		<div class="content_socio_<?=$i?>" style="<?=($visible==1?"display:block;":"display:none;")?>">
						<div class="row <?=$well?>">
							<input type="hidden" id="id_socio_accionista<?=$i?>" name="id_socio_accionista<?=$i?>" value="<?=$rsSocios->fields["id_socio_accionista"]?>">
							<input type="hidden" id="id_pais<?=$i?>" name="id_pais<?=$i?>" value="">
							<input type="hidden" id="fecha_expedicion<?=$i?>" name="fecha_expedicion<?=$i?>" value="">
							<input type="hidden" id="comentario<?=$i?>" name="comentario<?=$i?>" value="">
							<div class="col-md-3 labelCustom">
								<span class="label label-info"><?=$i?></span> Tipo de persona:
								<div id="divRadiotipo_persona<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$arrTipoPersona = array("NATURAL"=>"NATURAL","JURIDICA"=>"JURIDICA");
									$c_radio->Radio("tipo_persona".$i,"tipo_persona",$arrTipoPersona,"", 1, $rsSocios->fields["tipo_persona"], "", 0, "customValidateRadio('tipo_persona".$i."');");
									while($tmp_html = $c_radio->next_entry()) {
										echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
									}
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Tipo documento:
								<div class="">
								<?php
									$sede_select = new Select("id_tipo_documento".$i,"id_tipo_documento",$arrTipoDocumento,"",$requerido,"", "form-control", 0, "", "", 0);
									$sede_select->enableBlankOption();
									$sede_select->Default = $rsSocios->fields["id_tipo_documento"];
									echo $sede_select->genCode();
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Identificaci�n:
								<div class="">
								<?php
									echo $c_textbox->Textbox("identificacion".$i, "", $requerido, $rsSocios->fields["identificacion"], "form-control", 30, "", "", "");
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Nombre/Raz�n social completa:
								<div class="">
								<?php
									echo $c_textbox->Textbox("razon_social".$i, "", $requerido, $rsSocios->fields["razon_social"], "form-control", 30, "", "", "");
								?>
								</div>
							</div>
						</div>
						<div class="row <?=$well?>" style="height:10px;">&nbsp;</div>
						<div class="row <?=$well?>">
							<div class="col-md-3 labelCustom">
								Pa�s Residencia/ Ubicaci�n:
								<div class="">
								<?php
									$sede_select = new Select("pais_ubicacion".$i,"pais_ubicacion",$arrPaisesDesc,"",$requerido,"", "form-control pais_ubicacion", 0, "", "", 0);
									$sede_select->enableBlankOption();
									$sede_select->Default = $rsSocios->fields["pais_ubicacion"];
									echo $sede_select->genCode();
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								�Es (PEP�s)?:
								<div id="divRadiopoliticamente_expuesta<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$c_radio->Radio("politicamente_expuesta".$i,"politicamente_expuesta",$arrSiNo,"", $requerido, $rsSocios->fields["politicamente_expuesta"], "", 0, "customValidateRadio('politicamente_expuesta".$i."')");
									while($tmp_html = $c_radio->next_entry()) {
										echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
									}
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								�Tiene alg�n v�nculo con(PEP�s)?:
								<div id="divRadiotipo_vinculacion_persona<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$c_radio->Radio("tipo_vinculacion_persona".$i,"tipo_vinculacion_persona",$arrSiNo,"", $requerido, $rsSocios->fields["tipo_vinculacion_persona"], "", 0, "customValidateRadio('tipo_vinculacion_persona".$i."')");
									while($tmp_html = $c_radio->next_entry()) {
										echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
									}
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Es beneficiario final?:
								<div id="divRadiobeneficiario_final<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$c_radio->Radio("beneficiario_final".$i,"beneficiario_final",$arrSiNo,"", $requerido,  $rsSocios->fields["socio_beneficiario"], "", 0, "customValidateRadio('beneficiario_final".$i."')");
									while($tmp_html = $c_radio->next_entry()) {
										echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
									}
								?>
								</div>
							</div>
						</div>
						<hr/>
						</div>
					<?php
                			if ($visible==1){
                				$rsSocios->MoveNext();
                				$item_socio=$i;
                			}
                			$i++;

                		}

                		if ($item_socio > 1){
                			echo "<script>$('#btn_quitar_socio').show();</script>";
                		}

                		if ($rsSocios->_numOfRows == 0){
                			$item_socio--;
                			echo "<script>agregarSocio();</script>";
                		}
                	?>
					<input type="hidden" id="item_socio" name="item_socio" value="<?=$item_socio?>">

            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Gesti�n de LA/FT y declaraci�n de origen de fondos y/o bienes</div>
            <div class="row">
                <div class="col-md-12" style="text-align:justify;">
                Declaraci�n de origen de fondos y/o bienes:
				<?php
					$c_textarea = new Textarea;
					echo $c_textarea->Textarea("declaracion_origen_fondos", "declaracion_origen_fondos", 1, $this->declaracion_origen_fondos, "form-control", 30, "", "", "");
				?>
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-12" style="text-align:justify;">
					<div class="row" style="height:10px;">&nbsp;</div>
					<div class="row">
						<div class="col-md-3">
							�Realiza operaciones en moneda extranjera?:
							<div id="divRadiomoneda_extranjera" class="radioValidate" style="width:auto;">
							<?php
								$c_radio = new Radio;
								$arrTipos = array("1"=>"SI","2"=>"NO");
								$c_radio->Radio("moneda_extranjera","moneda_extranjera",$arrTipos,"", 1, $this->moneda_extranjera, "", 0, "customValidateRadio('moneda_extranjera');");
								while($tmp_html = $c_radio->next_entry()) {
									echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
								}
							?>
							</div>
						</div>
						<div class="col-md-9 operacion_me">
							�Cu�les?:
							<div id="">
							<?php
								$c_textarea = new Textarea;
								echo $c_textarea->Textarea("transaccion_moneda", "", 0, $this->transaccion_moneda, "form-control", 30, "", "", "");
							?>
							</div>
						</div>
					</div>
					<div class="row" style="height:10px;">&nbsp;</div>
					<div class="row">
						<div class="col-md-3">
							�Posee cuentas en moneda extranjera?
							<div id="divRadiocuentas_moneda_extranjera" class="radioValidate" style="width:auto;">
							<?php
							$arrMoneda = array("1"=>"SI","2"=>"NO");
							$c_radio->Radio("cuentas_moneda_extranjera","cuentas_moneda_extranjera",$arrMoneda,"", 1, $this->cuentas_moneda_extranjera, "", 0, "customValidateRadio('cuentas_moneda_extranjera');validarRetCuentasME(this.value);");
							while($tmp_html = $c_radio->next_entry()) {
								echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
							}
							?>
							</div>
						</div>
						<div class="col-md-3 cuentas_me">
							Banco:
							<div id="">
							<?php
								echo $c_textbox->Textbox("banco_me", "", 1, $this->banco_me, "form-control", 30, "", "", "");
							?>
							</div>
						</div>
						<div class="col-md-3 cuentas_me">
							Cuenta:
							<div id="">
							<?php
								echo $c_textbox->Textbox("cuenta_me", "", 1, $this->cuenta_nro_me, "form-control", 30, "", "", "");
							?>
							</div>
						</div>
						<div class="col-md-3 cuentas_me">
							Moneda:
							<div id="">
							<?php
								echo $c_textbox->Textbox("moneda_me", "", 1, $this->moneda_me, "form-control", 30, "", "", "");
							?>
							</div>
						</div>
					</div>
					<div class="row cuentas_me" style="height:10px;">&nbsp;</div>
					<div class="row cuentas_me">
						<div class="col-md-3">
							Ciudad:
							<div id="">
							<?php
								echo $c_textbox->Textbox("ciudad_me", "", 1, $this->ciudad_me, "form-control", 30, "", "", "");
							?>
							</div>
						</div>
						<div class="col-md-3">
							Pa�s:
							<div id="">
							<?php
								echo $c_textbox->Textbox("pais_me", "", 1, $this->pais_me, "form-control", 30, "", "", "");
							?>
							</div>
						</div>
					</div>
					<div class="row" style="height:10px;">&nbsp;</div>
					<div class="row">
						<div class="col-md-3">
							�Administra recursos p�blicos?
							<div id="divRadiorecursos_publicos" class="radioValidate" style="width:auto;">
							<?php
								$c_radio = new Radio;
								$c_radio->Radio("recursos_publicos","recursos_publicos",$arrSiNo,"", 1, $clienteAdicional->recursos_publicos, "", 0, "customValidateRadio('recursos_publicos');");
								while($tmp_html = $c_radio->next_entry()) {
									echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
								}
							?>
							</div>
						</div>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
        </div>
        </form>
        <div class="row" style="height:10px;">&nbsp;</div>
        <center>
		<?
		if ($appObj->tienePermisosAccion(array("informacion_adicional_terceros")))
        {
        	//Opcion a ejecutar si tiene el permiso
			echo "<input type='button' value='Editar' class='btn btn-primary datosRegistroEditarVinculacion_btnIniciarEdicion' onclick='iniciarEdicion()'>";
        }
        ?>
            <input type="button" value="Guardar" class="btn btn-success datosRegistroEditarVinculacion_btnSave" onclick="EditarVinculacion();">
            <div id="msgEnvio" class="alert alert-info" style="display:none;">Enviando informaci�n. Espere por favor...</>
        </center>
        <div class="row" style="height:10px;">&nbsp;</div>
    </div>
</div>
<?php
    //DETERMINAMOS SI LA FACTURA YA ESTA RELIQUIDADA PARA NO CAMBIAR SUS DATOS
    if ($this->id_cliente != 0){
?>
    <script>
        $(document).ready(function () {
            validarTipoCliente(<?=$this->id_tipo_tercero?>,<?=$this->id_tipo_tercero_sec?>);
            validarRetCuentasME(<?=$this->cuentas_moneda_extranjera?>);
            validarAutoretenedor(<?=$clienteAdicional->autoretenedor?>);
            validarReteIVA(<?=$clienteAdicional->rete_iva?>);
            validarReteICA(<?=$clienteAdicional->rete_ica?>);
        });
    </script>
<?php
}
?>
