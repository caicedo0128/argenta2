<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_constitucion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('.txt_fecha_expedicion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
	$("#id_pais").select2({ placeholder: 'Seleccione uno...',allowClear: true});
	$("#id_departamento").select2({ placeholder: 'Seleccione uno...',allowClear: true});
	$("#id_ciudad").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $("#id_ciudad_expedicion").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $("#id_sector").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $("#id_ciiu").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $(".pais_expedicion").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $(".pais_ubicacion").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $(".porcentaje_vtas").select2({ placeholder: 'Seleccione uno...',allowClear: true});

    var formularioCarga = '<?=$cargarFormulario?>';
    verFormularioVinculacion(formularioCarga);

    if (formularioCarga == 'act'){
    	obtenerDatos();
    	//$("#content_archivos").hide();
    }

});

function saveClient(){

	var msjError="";
    validateForm("datosRegistro");

	var itemSocio = $("#item_socio").val();
	var tieneBeneficiarioFinal = false;
	for (var i=1;i<=itemSocio;i++){
		var esBeneficiario = $('input:radio[id=beneficiario_final' + i + ']:checked').val();
		if (esBeneficiario == 1)
			tieneBeneficiarioFinal = true;
	}

	if (!tieneBeneficiarioFinal)
		msjError="<br/>Debe marcar un registro como beneficiario final.<br/>";

    if ($("#datosRegistro").valid() && msjError==""){

		showLoading("Enviando informaci�n. Espere por favor...");
		$(".btnGuardar").val("Enviando...").attr("disabled","disabled");
        $("#msgEnvio").show();

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistro"));

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
					$("#id_cliente").val(response.IdCliente);
					$("#msgEnvio").text("Transacci�n exitosa. Por favor descargue el formulario, f�rmelo y env�elo a eleyva@argentaestructuradores.com");
                    $(".btnGuardar").hide();
                    $(".btnActualizar").hide();
                    $(".btnDescargar").show();
				}
				else{
					$("#msgEnvio").hide();
					$("#msgEnvioError").text("Error: " + response.Message + ".").show().fadeOut(10000);
				}
				$(".btnGuardar").val("Guardar").removeAttr("disabled");
			}
		});
    }
    else {
    	$("#msgEnvioError").html("Hay errores en el formulario." + msjError + "Por favor revise los campos marcados.").show().fadeOut(9000);
    }
}

function cargarDepartamentos(idPais, idDepartamento){
	$("#id_ciudad").val(null).trigger('change');
	$("#id_departamento").val(null).trigger('change');
    cargarSelect('mod=zonificacion&action=getDptosJson', idPais, 'id_departamento', idDepartamento, '');

}


function validarArchivo(obj, idObj) {

	if ($(obj).val() != "") {
		var fileInput = $(obj).get(0).files[0];

		var fileSize = $(obj)[0].files[0].size;
		var siezekiloByte = parseInt(fileSize / 1024);
		var tamanoPermitido = $(obj).attr('size');
		var tamanoPermitidoMegas = Math.round(parseFloat(tamanoPermitido / 1024));
		if (siezekiloByte > tamanoPermitido) {
			showError("El peso del archivo que intenta cargar supera el permitido de " + tamanoPermitidoMegas + " MB. Seleccione otro archivo.<br/><br/><b>NOTA:</b> Si usted necesita enviar esta informaci�n por favor env�e la informaci�n al correo eleyva@argentaestructuradores.com", 5000);
			$(obj).val("");
		}
	}
}

function obtenerDatos(){

	showLoading("Consultando informaci�n. Espere por favor...");
	var strUrl = "admindex.php";
	var dataForm = new FormData(document.getElementById("datosRegistro"));

	$.ajax({
		type: 'POST',
		url: strUrl,
		dataType: "json",
		data: {
			Ajax:true,
			mod:'clientes',
			action:'obtenerVinculacion'
		},
		success: function (response) {
			closeNotify();
			if (response.Success){
				$("#id_cliente").attr("readonly","readonly");
				$("#id_cliente").val(response.IdCliente);
				$("input[name=Tipo][value=" + response.TipoIdentificacion + "]").attr('checked', 'checked');
				$("#documento").val(response.Identificacion);
				$("#documento").attr("readonly","readonly");
				if (response.DV != ""){
					$("#digito_verificacion").val(response.DV);
				}

				$("#razon_social").val(response.RazonSocial);
				$("#fecha_constitucion").val(response.FechaConstitucion);
				if (response.Pais != ""){
					$("#id_pais").val(response.Pais);
					$("#id_pais").trigger('change');
					cargarDepartamentos(response.Pais);
				}

				if (response.Departamento != ""){
					window.setTimeout(function(){
						$("#id_departamento").val(response.Departamento);
						$("#id_departamento").trigger('change');
					},500);
					cargarSelect('mod=zonificacion&action=getCiudadesJson', response.Departamento, 'id_ciudad', response.Ciudad, '');

				}

				if (response.Ciudad != ""){
					window.setTimeout(function(){
						$("#id_ciudad").val(response.Ciudad);
						$("#id_ciudad").trigger('change');
					},800);
				}

				$("#fijo").val(response.Telefono);
				$("#celular").val(response.Celular);
				$("#direccion").val(response.Direccion);
				$("#correo_personal").val(response.Correo);
				$("#representante_legal").val(response.RepresentanteLegal);
				$("#documento_representante").val(response.IdentificacionRepresentante);
				$("#id_ciudad_expedicion").val(response.CiudadExpedicion);
				$("#id_ciudad_expedicion").trigger('change');
				$("#encargado").val(response.PersonaAutoriza);
				$("#telefonos_encargado").val(response.CelularPersonaAutoriza);
				$("#cargo_autorizador").val(response.CargoAutorizador);
				$("#declaracion").val(response.OrigenesFondo);
				$("input[name=modeda_extranjera][value=" + response.MonedaExtranjera + "]").attr('checked', 'checked');
				$("#transaccion_moneda").val(response.TipoTransaccion);
				$("input[name=ctas_modeda_extranjera][value=" + response.CuentasMonedaExtranjera + "]").attr('checked', 'checked');
				$("#banco_me").val(response.BancoMe);
				$("#cuenta_me").val(response.CuentaMe);
				$("#moneda_me").val(response.MonedaMe);
				$("#ciudad_me").val(response.CiudadMe);
				$("#pais_me").val(response.PaisMe);
				validarRetCuentasME(response.CuentasMonedaExtranjera);

				$("#detalle_producto").val(response.DetalleProducto);
				if (response.IdSector != ""){
					$("#id_sector").val(response.IdSector);
					$("#id_sector").trigger('change');
				}
				if (response.IdCiiu != ""){
					$("#id_ciiu").val(response.IdCiiu);
					$("#id_ciiu").trigger('change');
				}
				$("#tipo_empresa").val(response.TipoEmpresa);
				$("#tipo_empresa1").val(response.TipoEmpresa1);
				$("input[name=gran_contribuyente][value=" + response.GranContribuyente + "]").attr('checked', 'checked');
				$("input[name=autoretenedor][value=" + response.Autoretenedor + "]").attr('checked', 'checked');
				$("input[name=rete_iva][value=" + response.ReteIVA + "]").attr('checked', 'checked');
				$("input[name=rete_ica][value=" + response.ReteICA + "]").attr('checked', 'checked');
				validarReteICA(response.ReteICA);
				$("#tarifa_ica").val(response.TarifaICA);
				$("#evolucion_vta_anio_anterior").val(response.EvolucionVTAAnterior);
				$("#evolucion_vta_anio_actual").val(response.EvolucionVTAActual);
				$("#id_numero_empleados").val(response.IdNumeroEmpleados);
				$("#id_referencia").val(response.IdReferencia);
				$("input[name=recursos_publicos][value=" + response.RecursosPublicos + "]").attr('checked', 'checked');

				//REFERENCIAS
				var itemReferencia = 1;
				var totalItems = 0;
				response.ClienteReferencias.forEach(
					function(valor, indice, array) {
						$("#ref_empresa" + itemReferencia).val(valor.RefEmpresa);
						$("#ref_nit" + itemReferencia).val(valor.RefNit);
						if (valor.RefPorcentajeVtas != ""){
							$("#porcentaje_vtas" + itemReferencia).val(valor.RefPorcentajeVtas);
							$("#porcentaje_vtas" + itemReferencia).trigger('change');
						}
						$("#id_plazo_pago" + itemReferencia).val(valor.RefIdPlazoPago);
						//$("input[name=descontar_facturas" + itemReferencia + "][value=" + valor.RefDescontarFacturas + "]").attr('checked', 'checked');
						//$("#monto_descuento" + itemReferencia).val(valor.RefMontoDescuento);
						//validarDescontarFacturas(valor.RefDescontarFacturas, itemReferencia);
						$("#id_relacion_comercial" + itemReferencia).val(valor.RefIdRelacionComercial);
						$(".content_cliente_" + itemReferencia).show();
						$("#item_referencia").val(itemReferencia);
						itemReferencia++;
						totalItems++;
						if (totalItems > 1){
							$("#btn_quitar_ref").show();
						}
					}
				);

				//SOCIOS
				var itemReferencia = 1;
				var totalItems = 0;
				response.ClienteSocios.forEach(
					function(valor, indice, array) {
						$("input[name=tipo_persona" + itemReferencia + "][value=" + valor.TipoPersona + "]").attr('checked', 'checked');
						$("#id_tipo_documento" + itemReferencia).val(valor.IdTipoDocumento);
						$("#identificacion" + itemReferencia).val(valor.Identificacion);
						if (valor.IdPais != ""){
							$("#id_pais" + itemReferencia).val(valor.IdPais);
							$("#id_pais" + itemReferencia).trigger('change');
						}
						$("#fecha_expedicion" + itemReferencia).val(valor.FechaExpedicion);
						$("#razon_social" + itemReferencia).val(valor.RazonSocial);
						if (valor.PaisUbicacion != ""){
							$("#pais_ubicacion" + itemReferencia).val(valor.PaisUbicacion);
							$("#pais_ubicacion" + itemReferencia).trigger('change');
						}

						$("input[name=politicamente_expuesta" + itemReferencia + "][value=" + valor.PoliticamenteExpuesta + "]").attr('checked', 'checked');
						$("input[name=tipo_vinculacion_persona" + itemReferencia + "][value=" + valor.TipoVinculacionPersona + "]").attr('checked', 'checked');
						$("#comentario" + itemReferencia).val(valor.Comentario);
						$("input[name=beneficiario_final" + itemReferencia + "][value=" + valor.BeneficiarioFinal + "]").attr('checked', 'checked');
						$(".content_socio_" + itemReferencia).show();
						$("#item_socio").val(itemReferencia);
						itemReferencia++;
						totalItems++;
						if (totalItems > 1){
							$("#btn_quitar_socio").show();
						}
					}
				);

			}
			else{
				$("#documento").val("");
				showError(response.Message, 8000);
			}
		}
	});
}

function verFormularioVinculacion(tipo){

    $(".btnGuardar").hide();
    $(".btnActualizar").hide();
    $(".cerrar_form").hide();
    $("#file_centrales").addClass("required");
    $("#file_legal").addClass("required");
    $("#file_declaracion").addClass("required");
    $("#file_res_fac").addClass("required");

    if (tipo == ''){
        $("#action").val("");
        $("#datosRegistro").hide();
        $(".featured").fadeIn();
    }
    else if (tipo == 'reg'){
        $("#action").val("guardarVinculacion");
        $("#datosRegistro").show();
        $(".featured").hide();
        $(".btnGuardar").show();
        $(".cerrar_form").show();
    }
    else if (tipo == 'act'){
        $("#action").val("actualizarVinculacion");
        $("#datosRegistro").show();
        $(".featured").hide();
        $(".btnActualizar").show();
        $(".cerrar_form").show();
        $("#file_centrales").removeClass("required");
        $("#file_legal").removeClass("required");
        $("#file_declaracion").removeClass("required");
        $("#file_res_fac").removeClass("required");
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

function descargarFormularioVinculacion(){

	$("#msgEnvio").text("Preparando informaci�n para descargar. Espere por favor...").show();
	var idCliente = $("#id_cliente").val();
	$("#contenido-impresion").load('admindex.php', { Ajax:true, mod: 'clientes', action:'versionImpresa', id_cliente:idCliente, es_reporte:1}, function () {
		descargarPDFVinculacion();
		window.setTimeout(function(){
			$("#msgEnvio").text("Transacci�n exitosa. Espere por favor").show().fadeOut(2000);
			$("#contenido-impresion").html("");
		},2000);
	});
}

function descargarPDFVinculacion() {

		var element = document.getElementById("contenido-impresion");
		var opt = {
		  margin:       0.2,
		  filename:     'FormularioVinculacionArgentaEstructuradores.pdf',
		  image:        { type: 'jpeg', quality: 1 },
		  html2canvas:  {scale: 3, logging: true},
		  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
		  pagebreak:    { before: '.saltoPagina', avoid: 'img' }
		};

		html2pdf()
		  .set(opt)
		  .from(element)
			.toPdf().get('pdf').then(function (pdf) {

				var totalPages = pdf.internal.getNumberOfPages();
				var myFooter = "Footer info";
				for (i = 1; i <= totalPages; i++) {
					pdf.setPage(i);

					pdf.setFontSize(7);
					pdf.setTextColor(150);
					pdf.text('_____________________________________________________________________________________________________________________________________________________', 0.2, 10.6);

					pdf.setFontSize(7);
					pdf.setTextColor(150);
					pdf.text('P�gina ' + i + ' de ' + totalPages, (pdf.internal.pageSize.getWidth()-1), 10.8);

					pdf.setFontSize(7);
					pdf.setTextColor(150);
                    pdf.text('ARGENTA ESTRUCTURADORES S.A.S - BOGOT� D.C - COLOMBIA', 0.2, 10.8);

				  }
			  }).save();
}

</script>
<style>
	.pagador{
		display:none;
	}

.featured .icon-box:hover {
    background:#286090;
    color:#fff;
}
.featured .icon-box {
    padding: 20px 15px;
    box-shadow: 0px 2px 15px
    rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    background: #fff;
    transition: all ease-in-out 0.3s;
    margin-right:20px;
    cursor:pointer;
}
.icon-box .titulo{
    color:#286090;
}

#datosRegistro{
    display:none;
}

.cerrar_form, .btnDescargar{
    display:none;
}

</style>
<?php
	if ($_SESSION["profile_text"]=="")
		echo "<a href='https://www.argentaestructuradores.com' title='Argenta estructuradores SAS'><img src=\"./images/encabezado.png\" class=\"img-responsive\" border=\"0\"/></a>";
?>
<div class="panel panel-primary col-md-offset-1 col-md-10">
    <div class="panel-body">
        Registre o actualice la informaci�n de su vinculaci�n.        
        <hr />
        <section class="featured row" style="clear:both;">
            <div class="icon-box col-lg-4" onclick="verFormularioVinculacion('reg');">
              <span class="titulo" style="font-size:18px;;color:inherit;">Nueva vinculaci�n</span>
              <p>Si es la primera vez que se registra en Argenta, ingrese aqu�.</p>
            </div>
            <div class="icon-box col-lg-4" onclick="verFormularioVinculacion('act');">
              <span class="titulo" style="font-size:18px;color:inherit;">Actualizaci�n</span>
              <p>Si va a realizar actualizaci�n de informaci�n a su registro en Argenta, ingrese aqu�.</p>
            </div>
        </section>
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="guardarVinculacion" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$this->id_cliente?>" />
			<div class="row-fluid alert alert-info">Informaci�n b�sica</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
					Tipo documento:
					<div id="divRadioTipo" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrTipos = array("2"=>"CE", "3"=>"NIT","4"=>"CC");
						$c_radio->Radio("Tipo","Tipo",$arrTipos,"", 1, $this->tipo_identificacion, "", 0, "customValidateRadio('Tipo');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
                	Documento:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("documento", "Documento", 1, $this->identificacion, "form-control", 30, "", "calcularDigitoVerificacion(this.value,'digito_verificacion');", "","","return IsNit(event);");
					?>
					</div>
                </div>
				<div class="col-md-1 digito_verificacion labelCustom">
					DV:
					<div style="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("digito_verificacion", "digito_verificacion", 1, "$this->digito_verificacion", "form-control", 30, "", "", 1,"","");
					?>
					</div>
				</div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-6 labelCustom titulo_razon_social">
					Nombre o Raz�n social:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("razon_social", "", 1, "$this->razon_social", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Fecha constituci�n:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("fecha_constitucion", "fecha_constitucion", 1, $this->fecha_consticucion, "form-control", 30, "", "", "");
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
					Pa&iacute;s:
					<div class="">
					<?php

						$sede_select = new Select("id_pais","id_pais",$arrPaises,"",1,"", "form-control required", 0, "", "cargarDepartamentos(this.value);", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->id_pais;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Departamento:
					<div class="">
						<select id="id_departamento" name="id_departamento" onchange="cargarSelect('mod=zonificacion&action=getCiudadesJson', this.value, 'id_ciudad', '', '');" class="form-control required"><option value="">Seleccione uno...</option></select>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Ciudad:
					<div class="">
						<select id="id_ciudad" name="id_ciudad" class="form-control required"><option value="">Seleccione uno...</option></select>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-6 labelCustom">
					Direcci&oacute;n oficina principal:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("direccion", "Direccion", 1, "", "form-control text_input", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Tel&eacute;fono (Conmutador):
					<div class="">
					<?php
						echo $c_textbox->Textbox ("fijo", "fijo", 1, "", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Celular:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("celular", "celular", 0, "", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
					Representante legal:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("representante_legal", "", 1, "$this->representante_legal", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Identificaci&oacute;n:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("documento_representante", "documento_representante", 1, "", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					Ciudad expedici�n:
					<div class="">
					<?php

						$sede_select = new Select("id_ciudad_expedicion","id_ciudad_expedicion",$arrCiudades,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->id_ciudad_expedicion;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
					Funcionario autorizado:
					<div class="">
					<?php
						echo $c_textbox->Textbox("encargado", "encargado", 1, "", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Cargo:
					<div class="">
					<?php
						echo $c_textbox->Textbox("cargo_autorizador", "cargo_autorizador", 1, "", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Celular:
					<div class="">
					<?php
						echo $c_textbox->Textbox("telefonos_encargado", "encargado", 1, "", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Correo funcionaro autorizado:
					<div class="">
					<?php
						echo $c_textbox->Textbox("correo_personal", "Correo Personal", 1, "", "form-control email no_mayus", "30", "", "", "", "");
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Informaci�n general del negocio</div>
			<div class="row">
                <div class="col-md-3 labelCustom">
					Sector:
					<div class="">
					<?php
						$sede_select = new Select("id_sector","id_sector",$arrSectores,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
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
						echo $sede_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Tipo de empresa:
					<div class="">
					<?php
						$sede_select = new Select("tipo_empresa1","tipo_empresa1",$this->arrTipoEmpresa,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
			<div class="row">
                <div class="col-md-3 labelCustom">
					Gran Contribuyente:
					<div id="divRadiogran_contribuyente" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrSiNo = array("1"=>"Si","2"=>"No");
						$c_radio->Radio("gran_contribuyente","gran_contribuyente",$arrSiNo,"", 1, "", "", 0, "customValidateRadio('gran_contribuyente');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Auto Retenedor:
					<div id="divRadioautoretenedor" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("autoretenedor","autoretenedor",$arrSiNo,"", 1, "", "", 0, "customValidateRadio('autoretenedor');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Retenedor IVA:
					<div id="divRadiorete_iva" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("rete_iva","rete_iva",$arrSiNo,"", 1, "", "", 0, "customValidateRadio('rete_iva');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Retenedor ICA:
					<div id="divRadiorete_ica" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("rete_ica","rete_ica",$arrSiNo,"", 1, "", "", 0, "customValidateRadio('rete_ica');validarReteICA(this.value)");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
                <div class="col-md-3 labelCustom txt_tarifa_ica">
					Tarifa ICA:
					<div class="">
					<?php
						echo $c_textbox->Textbox("tarifa_ica", "tarifa_ica", 1, "", "form-control number", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					Ventas netas(<?=date("Y")-2?>):
					<div class="">
					<?php
						echo $c_textbox->Textbox("evolucion_vta_anio_anterior", "evolucion_vta_anio_anterior", 1, "", "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					Ventas netas(<?=date("Y")-1?>):
					<div class="">
					<?php
						echo $c_textbox->Textbox("evolucion_vta_anio_actual", "evolucion_vta_anio_actual", 1, "", "form-control", 50, "", "", "", "","return IsNumber(event);");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					N�mero empleados:
					<div class="">
					<?php
						$sede_select = new Select("id_numero_empleados","id_numero_empleados",$arrNumEmpleados,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
                <div class="col-md-9 labelCustom">
					Detalle su objeto social, servicio / productos / actividad:
					<div class="">
					<?php
						echo $c_textbox->Textbox("detalle_producto", "detalle_producto", 1, "", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					C�mo se enter� de nuestra compa�ia:
					<div class="">
					<?php
						$sede_select = new Select("id_referencia","id_referencia",$arrReferencias,"",1,"", "form-control required", 0, "", "", 0);
						$sede_select->enableBlankOption();
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Clientes con los que quiere hacer Factoring
            		<a href="javascript:agregarReferencia();" class="btn btn-primary btn-sm" title="Agregar referencia"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>
            		<a href="javascript:quitarReferencia();" id="btn_quitar_ref" class="btn btn-danger btn-sm" title="Quitar referencia" style="display:none;"><i class="fa fa-minus fa-lg"></i> Quitar �ltimo</a>
            		<input type="hidden" id="item_referencia" name="item_referencia" value="1">
            </div>
            <div class="row-fluid">

                	<?php
                		for($i=1;$i<=5;$i++)
                		{

                			$requerido = 1;
                			$visible = 0;
                			//SOLO SE MUESTRA EL PRIMERO
                			if ($i==1){
                				$visible = 1;
                			}

                	?>
                		<div class="content_cliente_<?=$i?>" style="<?=($visible==1?"display:block;":"display:none;")?>">
						<div class="row <?=$well?>">
							<div class="col-md-6 labelCustom">
								<span class="label label-info"><?=$i?></span> Raz�n social:
								<div class="">
								<?php
									echo $c_textbox->Textbox("ref_empresa".$i, "", $requerido, "", "form-control", 30, "", "", "");
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Identificaci�n:
								<div class="">
								<?php
									echo $c_textbox->Textbox("ref_nit".$i, "", $requerido, "", "form-control", 30, "", "", "");
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								% ventas totales de la empresa:
								<div class="">
								<?php
									$sede_select = new Select("porcentaje_vtas".$i,"porcentaje_vtas",$arrNumeros,"",$requerido,"", "form-control porcentaje_vtas", 0, "", "", 0);
									$sede_select->enableBlankOption();
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
									echo $sede_select->genCode();
								?>
								</div>
							</div>
						</div>
						<hr/>
						</div>
                	<?php
                		}
                	?>

            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Conocimiento de socios, accionistas, representante legal (principal, suplente) y beneficiario final
            		<a href="javascript:agregarSocio();" class="btn btn-primary btn-sm" title="Agregar socio"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>
            		<a href="javascript:quitarSocio();" id="btn_quitar_socio" class="btn btn-danger btn-sm" title="Quitar socio" style="display:none;"><i class="fa fa-minus fa-lg"></i> Quitar �ltimo</a>
            		<input type="hidden" id="item_socio" name="item_socio" value="1">
            </div>
            <div class="row-fluid">

                	<?php
                		for($i=1;$i<=8;$i++)
                		{

                			$requerido = 1;
                			$visible = 0;
                			//SOLO SE MUESTRA EL PRIMERO
                			if ($i==1){
                				$visible = 1;
                			}

                	?>
                		<div class="content_socio_<?=$i?>" style="<?=($visible==1?"display:block;":"display:none;")?>">
						<div class="row <?=$well?>">
							<div class="col-md-3 labelCustom">
								<span class="label label-info"><?=$i?></span> Tipo de persona:
								<div id="divRadiotipo_persona<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$arrTipoPersona = array("NATURAL"=>"NATURAL","JURIDICA"=>"JURIDICA");
									$c_radio->Radio("tipo_persona".$i,"tipo_persona",$arrTipoPersona,"", 1, "", "", 0, "customValidateRadio('tipo_persona".$i."');");
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
									echo $sede_select->genCode();
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Identificaci�n:
								<div class="">
								<?php
									echo $c_textbox->Textbox("identificacion".$i, "", $requerido, "", "form-control", 30, "", "", "");
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								Nombre/Raz�n social completa:
								<div class="">
								<?php
									echo $c_textbox->Textbox("razon_social".$i, "", $requerido, "", "form-control", 30, "", "", "");
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
									echo $sede_select->genCode();
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								�Es (PEP�s)?:
								<div id="divRadiopoliticamente_expuesta<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$c_radio->Radio("politicamente_expuesta".$i,"politicamente_expuesta",$arrSiNo,"", $requerido, "", "", 0, "customValidateRadio('politicamente_expuesta".$i."')");
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
									$c_radio->Radio("tipo_vinculacion_persona".$i,"tipo_vinculacion_persona",$arrSiNo,"", $requerido, "", "", 0, "customValidateRadio('tipo_vinculacion_persona".$i."')");
									while($tmp_html = $c_radio->next_entry()) {
										echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
									}
								?>
								</div>
							</div>
							<div class="col-md-3 labelCustom">
								�Es beneficiario final?:
								<div id="divRadiobeneficiario_final<?=$i?>" class="radioValidate" style="width:auto;">
								<?php
									$c_radio = new Radio;
									$c_radio->Radio("beneficiario_final".$i,"beneficiario_final",$arrSiNo,"", $requerido, "", "radio_beneficiario", 0, "customValidateRadio('beneficiario_final".$i."')");
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
                		}
                	?>

            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row-fluid alert alert-info">Gesti�n de LA/FT y declaraci�n de origen de fondos y/o bienes</div>
            <div class="row">
                <div class="col-md-12" style="text-align:justify;">
                Obrando en nombre propio y de la sociedad que represento, de manera voluntaria y dando certeza de que todo lo aqu�
				consignado es cierto, realizamos la siguiente declaraci�n de origen de bienes y fondos, con el prop�sito de que se pueda
				dar cumplimiento a lo se�alado al respecto en la Circular Externa No. 007 de 1996, expedida por la Superintendencia
				Bancaria, Estatuto Org�nico del Sistema Financiero (Decreto 663 de 1993), Ley 190 de 1995 (Estatuto Anticorrupci�n) y
				dem�s normas legales concordantes: a) Declaramos que los recursos que entregamos y los bienes que figuran a nuestro
				nombre no provienen de ninguna actividad il�cita de las contempladas en el C�digo Penal Colombiano o en cualquier
				norma que lo modifique o adicione. b) No admitiremos que terceros efect�en dep�sitos a nuestras cuentas con fondos
				provenientes de las actividades il�citas contempladas en el C�digo Penal Colombiano o en cualquier norma que lo
				modifique o adicione, ni efectuaremos transacciones destinadas a tales actividades o a favor de personas relacionadas
				con las mismas. Declaramos que los bienes que poseemos provienen de (detalle el t�tulo de adquisiciones de los bienes):
				<?php
					$c_textarea = new Textarea;
					echo $c_textarea->Textarea("declaracion", "declaracion", 1, "", "form-control", 60, 3);
				?>
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-12" style="text-align:justify;">
                Para los fines previstos en el Art. 83 de la Constituci�n Pol�tica de Colombia, declaramos bajo la gravedad de
                expresi�n de la verdad. Nos obligamos a entregar informaci�n veraz y verificable. Autorizamos a Argenta Estructuradores
				S.A.S. para inhabilitar y dar por terminado el producto o servicio, en el evento de que la informaci�n aqu� suministrada sea
				err�nea, falsa o inexacta o que no sea posible su confirmaci�n por motivos ajenos a Argenta Estructuradores S.A.S.
				Autorizamos irrevocablemente a Argenta Estructuradores S.A.S. para que en caso de que esta solicitud sea negada,
				destruya todos los documentos que hemos aportado. As� mismo, autorizamos a Argenta Estructuradores S.A.S. o a la
				entidad que �ste designe, para realizar las verificaciones y consultas sobre la informaci�n comercial y financiera de la
				empresa, sus socios y administradores. En el mismo sentido, Argenta Estructuradores S.A.S. podr� suministrar cualquier
				tipo de informaci�n requerida por las entidades de riesgo y registro de deudores morosos, Asociaci�n Bancaria, entidades
				financieras o de cualquier entidad que se establezca con este prop�sito.
				Desde el momento de nuestra vinculaci�n como cliente de Argenta Estructuradores S.A.S., nos obligamos y
				comprometemos a actualizar, por lo menos una vez al a�o, cualquier cambio de direcci�n y/o actividad econ�mica,
				suministrando los soportes documentales respectivos, as� como la informaci�n financiera, tributaria y comercial.
				OPERACIONES EN MONEDA EXTRANJERA:
					<div class="row" style="height:10px;">&nbsp;</div>
					<div class="row">
						<div class="col-md-3">
							�Realiza operaciones en moneda extranjera?:
							<div id="divRadiomodeda_extranjera" class="radioValidate" style="width:auto;">
							<?php
								$c_radio = new Radio;
								$arrTipos = array("1"=>"SI","2"=>"NO");
								$c_radio->Radio("modeda_extranjera","modeda_extranjera",$arrTipos,"", 1, "", "", 0, "customValidateRadio('modeda_extranjera');");
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
								echo $c_textarea->Textarea("transaccion_moneda", "", 0, "", "form-control", 30, "", "", "");
							?>
							</div>
						</div>
					</div>
					<div class="row" style="height:10px;">&nbsp;</div>
					<div class="row">
						<div class="col-md-3">
							�Posee cuentas en moneda extranjera?
							<div id="divRadioctas_modeda_extranjera" class="radioValidate" style="width:auto;">
							<?php
								$c_radio = new Radio;
								$arrTipos = array("1"=>"SI","2"=>"NO");
								$c_radio->Radio("ctas_modeda_extranjera","ctas_modeda_extranjera",$arrTipos,"", 1, "", "", 0, "customValidateRadio('ctas_modeda_extranjera');validarRetCuentasME(this.value)");
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
								echo $c_textbox->Textbox("banco_me", "", 1, "", "form-control", 30, "", "", "");
							?>
							</div>
						</div>
						<div class="col-md-3 cuentas_me">
							Cuenta:
							<div id="">
							<?php
								echo $c_textbox->Textbox("cuenta_me", "", 1, "", "form-control", 30, "", "", "");
							?>
							</div>
						</div>
						<div class="col-md-3 cuentas_me">
							Moneda:
							<div id="">
							<?php
								echo $c_textbox->Textbox("moneda_me", "", 1, "", "form-control", 30, "", "", "");
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
								echo $c_textbox->Textbox("ciudad_me", "", 1, "", "form-control", 30, "", "", "");
							?>
							</div>
						</div>
						<div class="col-md-3">
							Pa�s:
							<div id="">
							<?php
								echo $c_textbox->Textbox("pais_me", "", 1, "", "form-control", 30, "", "", "");
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
								$c_radio->Radio("recursos_publicos","recursos_publicos",$arrSiNo,"", 1, "", "", 0, "customValidateRadio('recursos_publicos');");
								while($tmp_html = $c_radio->next_entry()) {
									echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
								}
							?>
							</div>
						</div>
					</div>
				<br/>Igualmente, manifiesto bajo la gravedad de juramento que la sociedad a la cual represento, no se encuentra incursa en
				ninguna causal de liquidaci�n voluntaria u obligatoria, ni ha iniciado tr�mite alguno tendiente a ser admitida en proceso de
				reestructuraci�n de acuerdo con la Ley 1116 de 2006 o para ser admitida a concordato.
				<br/><br/>
                </div>
            </div>
            <div id="content_archivos">
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row-fluid alert alert-info">Documentos requeridos (Tama�o maximo por archivo 7MB.)</div>
				<div class="row">
					<div class="col-md-4 labelCustom">RUT:</div>
					<div class="col-md-5">
						<input type="file" id="file_rut" name="file_rut" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_rut');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">C�mara de comercio. Certificado de representaci�n legal con m�ximo 30 d�as de emisi�n:</div>
					<div class="col-md-5">
						<input type="file" id="file_camara" name="file_camara" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_camara');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">Copia de la c�dula del representante legal (Ampliado al 150% con firma y huella):</div>
					<div class="col-md-5">
						<input type="file" id="file_legal" name="file_legal" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_legal');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">Composici�n accionaria. Certificado de composici�n accionaria con m�ximo 30 d�as de emisi�n:</div>
					<div class="col-md-5">
						<input type="file" id="file_accionaria" name="file_accionaria" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_accionaria');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">Resoluci�n de facturaci�n:</div>
					<div class="col-md-5">
						<input type="file" id="file_res_fac" name="file_res_fac" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_res_fac');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">Estados financieros(1) �ltimos 2 a�os con sus respectivas notas auditadas:</div>
					<div class="col-md-5">
						<input type="file" id="file_financieros" name="file_financieros" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_financieros');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">Estados financieros(2) �ltimos 2 a�os con sus respectivas notas auditadas (opcional):</div>
					<div class="col-md-5">
						<input type="file" id="file_financieros_2" name="file_financieros_2" size="7000" class="form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_financieros_2');">
					</div>
				</div>
				<div class="row" style="height:10px;">&nbsp;</div>
				<div class="row">
					<div class="col-md-4 labelCustom">Declaraciones de renta �ltimos 2 periodos:</div>
					<div class="col-md-5">
						<input type="file" id="file_declaracion" name="file_declaracion" size="7000" class="required form-control" onchange="limitAttach(this, 7);validarArchivo(this, 'file_declaracion');">
					</div>
				</div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <div id="msgEnvio" class="alert" style="display:none;margin-bottom:5px;background-color:#449D44;color:#fff;font-size:17px;">Enviando informaci�n. El proceso puede tardar unos segundos. Espere por favor...</div>
                <div id="msgEnvioError" class="alert" style="display:none;margin-bottom:5px;background-color:#C9302C;color:#fff;font-size:17px;"></div>
                <input type="button" value="Guardar" class="btn btn-primary datos_cliente_btnSave btnGuardar" onclick="saveClient();">
                <input type="button" value="Actualizar" class="btn btn-primary datos_cliente_btnSave btnActualizar" onclick="saveClient();">
                <input type="button" value="Descargue aqu� el formulario de vinculaci�n" class="btn btn-warning btnDescargar" onclick="descargarFormularioVinculacion();">
                <br/>
                <a href="https://www.argentaestructuradores.com" title="Argenta Estructuradores SAS" class="btn btn-success btnDescargar">Volver a www.argentaestructuradores.com</a>
            </center>
            </form>
    </div>
</div>
<div class="row" style="height:10px;clear:both;"><br/><br/><br/><br/><br/><br/><br/><br/></div>
<div id="contenido-impresion">

</div>