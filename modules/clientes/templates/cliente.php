<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_constitucion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
	$("#id_pais").select2({ placeholder: 'Seleccione uno...',allowClear: true});
	$("#id_departamento").select2({ placeholder: 'Seleccione uno...',allowClear: true});
	$("#id_ciudad").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $("#id_ciudad_expedicion").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    $("#id_ciudad_exp_representante_supl").select2({ placeholder: 'Seleccione uno...',allowClear: true});

});

function saveClient(){

    validateForm("datosRegistro");

    if ($("#datosRegistro").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
        var dataForm = "Ajax=true&mod=clientes&action=saveClient&" + $("#datosRegistro").serialize();
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    if (response.Success){
                        showSuccess("Transacción exitosa. Espere por favor...");
                        window.setTimeout(function(){
                            loader();
                            $("#content_clientes").load('admindex.php', { Ajax:true, id_cliente: response.IdCliente, mod: 'clientes', action:'client'}, function () {
                                loader();
                            });
                        },2000);
                    }
                    else{
                        showError(response.Message);
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function cambiarEstado(nuevoEstado,proceso){

	bootbox.prompt({
		title: "Confirmación",
		message: "Usted va a " + proceso + " el tercero. El proceso no se podrá deshacer. Desea continuar?<br/><br/>Ingrese comentarios:",
		closeButton: true,
		inputType: 'textarea',
		buttons: {
			confirm: {
				label: 'Si',
				className: 'btn-primary'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
		},
		callback: function (result) {

			var observaciones = $(".bootbox-input-textarea").val();
			$(".bootbox-input-textarea").removeClass("invalid");

			if (result === null) {
				closeBootbox();
				return;
			} else if (result === '') {
				$(".bootbox-input-textarea").addClass("invalid");
				showError('Debe ingresar un comentario para completar el proceso');
				return false;
			}
			else if (result) {

				showLoading("Enviando informacion. Espere por favor...");

				var strUrl = "admindex.php";
				var dataForm = "Ajax=true&mod=clientes&action=cambiarEstado&" + $("#datosRegistro").serialize();
				$.ajax({
						type: 'POST',
						url: strUrl,
						dataType: "json",
						data:{
							Ajax:true,
							mod:'clientes',
							action:'cambiarEstado',
							id_cliente:$("#id_cliente").val(),
							estado:nuevoEstado,
							observaciones:observaciones
						},
						success: function (response) {
							closeNotify();
							if (response.Success){
								showSuccess("Transacción exitosa. Espere por favor...");
								window.setTimeout(function(){
									loader();
									$("#content_clientes").load('admindex.php', { Ajax:true, id_cliente: response.IdCliente, mod: 'clientes', action:'client'}, function () {
										loader();
									});
								},1000);
							}
							else{
								showError(response.Message);
							}
						}
				});
			}
		}
	});
}

function validarTipoTercero(){

    var idTipo = $("#id_tipo_tercero").val();
    var idTipoS = $("#id_tipo_tercero_sec").val();
    $(".titulo_razon_social").text("Razón social:");
    $(".titulo_representante").show();
    $(".pagador").hide();
    $(".emisor").hide();
    $(".tabs_cliente").hide();
    $(".ejecutivo").hide();
    if (idTipo == 4 || idTipo == 5)
    {
        $(".titulo_razon_social").text("Nombre completo:");
        $(".titulo_representante").hide();
    }

    if (idTipo == 6 || idTipoS == 6)
    {
    	$(".pagador").show();
        $(".tabs_cliente").show();
        $("#cupo").attr("readonly","readonly");
        $("#cupo").removeClass("required");
    }

    if (idTipo == 1 || idTipoS == 1)
    {
        $(".pagador").show();
        $(".emisor").show();
        $(".tabs_cliente").show();
    }

    if (idTipo == 7 || idTipo == 8 || idTipoS == 7 || idTipoS == 8 || idTipo == 5){
        $(".tabs_cliente").show();
    }

    if (idTipo == 1 || idTipo == 7 || idTipo == 8 || idTipoS == 1 || idTipoS == 7 || idTipoS == 8){
        $(".ejecutivo").show();
    }

    //ES COMERCIAL
    if (idTipo == 5){
		$("#info_anexo").hide();
		$(".info_anexo").hide();
		window.setTimeout(function(){
				cargarInfoDocumentos();
		},200);
    }
}

function cargarInfoDocumentos(){

	var idCliente = $("#id_cliente").val();
	if (idCliente > 0){
	    loader();
		$("#documentos_cliente").load('admindex.php', { Ajax:true, mod: 'clientes', action:'listDocumentosCliente', id_cliente : idCliente}, function () {
			loader();
			$("#info_anexo").removeClass("active");
			$("#info_condicion").removeClass("active");
			$("#info_pdf").removeClass("active");
			$("#documentos_cliente").addClass("active");
		});
    }

}

function cargarInfoAnexo(){

	var idCliente = $("#id_cliente").val();

    loader();
    $("#info_anexo").load('admindex.php', { Ajax:true, mod: 'clientes', action:'verInformacionAnexa', id_cliente : idCliente}, function () {
        loader();
		$("#documentos_cliente").removeClass("active");
		$("#info_condicion").removeClass("active");
		$("#info_pdf").removeClass("active");
        $("#info_anexo").addClass("active");
    });

}

function cargarInfoAdicional(){

	var idCliente = $("#id_cliente").val();

    loader();
    $("#info_condicion").load('admindex.php', { Ajax:true, mod: 'clientes', action:'verInfoCondiciones', id_cliente : idCliente}, function () {
        loader();
		$("#documentos_cliente").removeClass("active");
		$("#info_anexo").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#info_condicion").addClass("active");
    });

}

function cargarInfoPdf(){

	var idCliente = $("#id_cliente").val();

    loader();
    $("#info_pdf").load('admindex.php', { Ajax:true, mod: 'clientes', action:'VerReporte', id_cliente : idCliente}, function () {
        loader();
		$("#documentos_cliente").removeClass("active");
		$("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").addClass("active");
    });

}

function cargarReferenciaPagador(){

    var idCliente = $("#id_cliente").val();

    loader();
    $("#ref_pagador").load('admindex.php', { Ajax:true, mod: 'clientes', action:'listReferenciaPagador', id_cliente : idCliente}, function () {
        loader();
        $("#documentos_cliente").removeClass("active");
        $("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#ref_pagador").addClass("active");
    });
}

function cargarFormatoPagare(){

    var idCliente = $("#id_cliente").val();

    loader();
    $("#formato_pagare").load('admindex.php', { Ajax:true, mod: 'clientes', action:'VerFormatoPagare', id_cliente : idCliente}, function () {
        loader();
        $("#documentos_cliente").removeClass("active");
        $("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#ref_pagador").removeClass("active");
        $("#formato_pagare").addClass("active");
    });
}

function cargarCartaInstrcciones(){

    var idCliente = $("#id_cliente").val();

    loader();
    $("#carta_instrucciones").load('admindex.php', { Ajax:true, mod: 'clientes', action:'VerCartaInstrcciones', id_cliente : idCliente}, function () {
        loader();
        $("#documentos_cliente").removeClass("active");
        $("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#ref_pagador").removeClass("active");
        $("#formato_pagare").removeClass("active");
        $("#carta_instrucciones").addClass("active");
    });
}

function cargarInfoAceptacion(){

	var idCliente = $("#id_cliente").val();

    loader();
    $("#info_aceptacion").load('admindex.php', { Ajax:true, mod: 'clientes', action:'aceptaciones', id_cliente : idCliente}, function () {
        loader();
		$("#documentos_cliente").removeClass("active");
        $("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#ref_pagador").removeClass("active");
        $("#formato_pagare").removeClass("active");
        $("#carta_instrucciones").removeClass("active");
        $("#info_aceptacion").addClass("active");
    });

}

function cargarInfoSeguimiento(){

	var idCliente = $("#id_cliente").val();

    loader();
    $("#info_seguimiento").load('admindex.php', { Ajax:true, mod: 'clientes', action:'listSeguimiento', id_cliente : idCliente}, function () {
        loader();
		$("#documentos_cliente").removeClass("active");
        $("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#ref_pagador").removeClass("active");
        $("#formato_pagare").removeClass("active");
        $("#carta_instrucciones").removeClass("active");
        $("#info_aceptacion").removeClass("active");
        $("#info_seguimiento").addClass("active");
    });
}

function cargarVersionImpresa(){

	var idCliente = $("#id_cliente").val();

    loader();
    $("#impresa").load('admindex.php', { Ajax:true, mod: 'clientes', action:'versionImpresa', id_cliente : idCliente}, function () {
        loader();
		$("#documentos_cliente").removeClass("active");
        $("#info_anexo").removeClass("active");
        $("#info_condicion").removeClass("active");
        $("#info_pdf").removeClass("active");
        $("#ref_pagador").removeClass("active");
        $("#formato_pagare").removeClass("active");
        $("#carta_instrucciones").removeClass("active");
        $("#info_aceptacion").removeClass("active");
        $("#info_seguimiento").removeClass("active");
        $("#impresa").addClass("active");

    });

}

function formDatosReporte(tipoReporte){

        $("#tipo_reporte").val(tipoReporte);
        $('#modalToMail').modal('show');
}

function enviarReporte(){

	validateForm("custom_data_to_email");

	if ($("#custom_data_to_email").valid()) {

		showLoading("Enviando información por correo electrónico...");
		var tipoReporte = $("#tipo_reporte").val();


		//REPORTE oferta
		if (tipoReporte == 1){
			action = "VerReporte";
			subjectMail = "Formato oferta";
		}
		//REPORTE Pagaré
		else if (tipoReporte == 2){
			action = "VerFormatoPagare";
			subjectMail = "Pagaré";
		}
		//REPORTE i
		else if (tipoReporte == 3){
			action = "VerCartaInstrcciones";
			subjectMail = "Carta instrucciones";
		}

		//GENERAMOS EL REPORTE PDF
		var dataForm = "Ajax=true&mod=clientes&action=" + action + "&es_reporte=true&id_cliente=" + <?=($this->id_cliente==""?"0":$this->id_cliente)?>;
		var strUrl = "admindex.php";
		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "html",
			data:dataForm,
			success: function (response) {

				var dataForm = "Ajax=true&mod=clientes&action=guardarInformacionCliente&__dataMail=" + response;
				var strUrl = "admindex.php";
				$.ajax({
					type: 'POST',
					url: strUrl,
					dataType: "json",
					data:dataForm,
					success: function (response) {

						$("#formMail input[id=mod]").val("clientes");
						$("#formMail input[id=action]").val("enviarInformacionCliente");
						$("#formMail input[id=__option1]").val(<?=$this->id_cliente?>);
						$("#formMail input[id=__subjectMail]").val(subjectMail);
						$("#formMail input[id=__toEmailMail]").val($("#correo_to_email").val());
						$("#formMail input[id=__toNameMail]").val($("#nombre_to_email").val());
						$("#formMail input[id=__dataMail]").val($("#observaciones_correo").val());
						$("#formMail input[id=__template]").val("mailEnvioInformacion");


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
		});

		//FIN GUARDADO PDF
	}

}

function savePagare(idCliente){

    var pagare = $("#nro_pagare").val();
    var fechaGeneracion = $("#fecha_generacion_pagare").val();

    if (pagare != ""){

        showLoading("Enviando información. Espere por favor...");

        var strUrl = "admindex.php";
        var dataForm = "Ajax=true&mod=clientes&action=savePagare&id_cliente=" + idCliente + "&nro_pagare=" + pagare + "&fecha_generacion_pagare=" + fechaGeneracion;
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    if (response.Success){
                        showSuccess("Transacción exitosa. Espere por favor...");
                        cargarFormatoPagare();
                    }
                    else{
                        showError(response.Message);
                    }
                }
        });
    }
    else {
        showError("El número de pagaré es obligatorio.");
    }
}

function descargarReporte(tipoReporte){

    showLoading("Descargando reporte. Espere por favor...");

    //REPORTE oferta
    if (tipoReporte == 1){
        action = "VerReporte";
        nombreReporte = "Oferta.pdf";
    }
    //REPORTE Pagaré
    else if (tipoReporte == 2){
        action = "VerFormatoPagare";
        nombreReporte = "Pagare.pdf";
    }
    //REPORTE carta
    else if (tipoReporte == 3){
        action = "VerCartaInstrcciones";
        nombreReporte = "CartaInstrucciones.pdf";
    }
    //REPORTE general
    else if (tipoReporte == 4){
        action = "versionImpresa";
        nombreReporte = "FormularioVinculacion.pdf";
    }

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=clientes&action=" + action + "&es_reporte=true&id_cliente=" + <?=($this->id_cliente==""?"0":$this->id_cliente)?>;
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("clientes");
            $("#formMail input[id=action]").val("guardarInformacionCliente");
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
                    downloadURI("./gallery/clientes/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

function rechazarCliente(){

	loader()
	var strUrl = "admindex.php";
	$.ajax({
		type: 'POST',
		url: strUrl,
		dataType: "html",
		data: {
			Ajax:true,
			mod:'clientes',
			action:'rechazo',
			id_cliente: $("#id_cliente").val()
		},
		mimeType: "multipart/form-data",
		cache: false,
		success: function (response) {
			loader();
			var dialog = bootbox.dialog({
				title: "Confirmación motivo rechazo",
				message: response
			});
			$(".bootbox").show().addClass("show");
		}
	});
}

function cargarDepartamentos(idPais, idDepartamento){
	$("#id_ciudad").val(null).trigger('change');
	$("#id_departamento").val(null).trigger('change');
    cargarSelect('mod=zonificacion&action=getDptosJson', idPais, 'id_departamento', idDepartamento, '');

}

function verificar(tipoVerificacion){

	loader()
	var strUrl = "admindex.php";
	$.ajax({
		type: 'POST',
		url: strUrl,
		dataType: "html",
		data: {
			Ajax:true,
			mod:'clientes',
			action:'verificacion',
			id_cliente: $("#id_cliente").val(),
			tipo_verificacion : tipoVerificacion
		},
		mimeType: "multipart/form-data",
		cache: false,
		success: function (response) {
			loader();
			var dialog = bootbox.dialog({
				title: "Registrar verificación",
				message: response
			});
			$(".bootbox").show().addClass("show");
		}
	});

}

function descargarPDF() {

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
					pdf.text('Página ' + i + ' de ' + totalPages, (pdf.internal.pageSize.getWidth()-1), 10.8);

					pdf.setFontSize(7);
					pdf.setTextColor(150);
                    pdf.text('ARGENTA ESTRUCTURADORES S.A.S - BOGOTÁ D.C - COLOMBIA', 0.2, 10.8);

				  }
			  }).save();
}

</script>
<style>
	.pagador{
		display:none;
	}

    .emisor{
        display:none;
    }

    .tabs_cliente{
        display:none;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-body ">
        Registro de información del tercero
        <?php
        	if ($this->id_cliente != 0){
        		echo "<span class='label label-primary text-white' style='padding:5px;font-size:12px;'>ID: ".$this->id_cliente."</span>&nbsp;";
        		echo "<span class='label label-info text-white' style='padding:5px;font-size:12px;'>Estado: ".$this->arrEstadosCliente[$this->activo]."</span>&nbsp;";

        		$classSagrilaft = "success";
        		if ($verificacionSagrilaft->_numOfRows == 0)
        			$classSagrilaft = "danger";
        		else{
        			if ($verificacionSagrilaft->fields["valor_verificacion"] == 2)
        				$classSagrilaft = "warning";
        		}
        		echo "<span class='label label-".$classSagrilaft." text-white' style='padding:5px;font-size:12px;cursor:pointer;' onclick='verificar(1)' title='Verificación listas SAGRILAFT'>SAGRILAFT</span>&nbsp;";

        		$classCliente = "success";
        		if ($verificacionCliente->_numOfRows == 0)
        			$classCliente = "danger";
        		else{
        			if ($verificacionCliente->fields["valor_verificacion"] == 2)
        				$classCliente = "warning";
        		}
        		echo "<span class='label label-".$classCliente." text-white' style='padding:5px;font-size:12px;cursor:pointer;' onclick='verificar(2)' title='Verificación conocimiento cliente'>C. CLIENTE</span>&nbsp;";

        		$classOperacion = "success";
        		if ($verificacionOperacion->_numOfRows == 0)
        			$classOperacion = "danger";
        		else{
        			if ($verificacionOperacion->fields["valor_verificacion"] == 2)
        				$classOperacion = "warning";
        		}
        		echo "<span class='label label-".$classOperacion." text-white' style='padding:5px;font-size:12px;cursor:pointer;' onclick='verificar(3)' title='Verificación conocimiento operación'>C. OPERACION</span>&nbsp;";
        		echo "<span class='label label-info text-white' style='padding:5px;font-size:12px;'>Ultima fecha operación: ".($ultimaFecha != ""?$ultimaFecha:"No registra")."</span>&nbsp;";
        	}
        ?>
        <?php
        	if ($_REQUEST["referer"]=="tareas")
        		echo "<div class=\"cerrar_form\" onclick=\"window.location.href='admindex.php';\" title=\"Regresar\"><i class=\"fa fa-reply fa-lg\"></i></div>";
        	else
        		echo "<div class=\"cerrar_form\" onclick=\"cargarClientes();\" title=\"Regresar\"><i class=\"fa fa-reply fa-lg\"></i></div>";
        ?>

        <hr />

        <form id="datosRegistro" method="post" name="datosRegistro" action="index.php">
	        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$this->id_cliente?>" />
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
                	Fecha registro:
                	<div class="form-control disabled readonly" style="disabled:disabled;"><?=$this->fecha_registro?></div>
                </div>
                <div class="col-md-3 labelCustom">
                	Tipo tercero:
					<div class="">
					<?php

						$sede_select = new Select("id_tipo_tercero","Tercero",$arrTerceros,"",1,"", "form-control", 0, "", "validarTipoTercero()", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->id_tipo_tercero;
						echo $sede_select->genCode();
					?>
					</div>
				</div>
                <div class="col-md-3 labelCustom">
					Tipo tercero secundario:
					<div class="">
					<?php

						$sede_select = new Select("id_tipo_tercero_sec","Tercero",$arrTerceros,"",0,"", "form-control", 0, "", "validarTipoTercero()", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->id_tipo_tercero_sec;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
                	Permite actualizar:
					<div id="divRadiopermite_actualizar" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrSN = array("1"=>"Si","2"=>"No");
						$c_radio->Radio("permite_actualizar","Tipo",$arrSN,"", 0, $this->permite_actualizar, "", 0, "customValidateRadio('permite_actualizar');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
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
						echo $c_textbox->Textbox ("documento", "Documento", 1, $this->identificacion, "form-control", 30, "", "calcularDigitoVerificacion(this.value,'digito_verificacion');obtenerDatos()", "","","return IsNit(event);");
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
                <div class="col-md-6 labelCustom">
					<span class="titulo_razon_social">Nombre o Razón social:</span>
					<div class="">
					<?php
						echo $c_textbox->Textbox ("razon_social", "", 1, "$this->razon_social", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Fecha constitución:
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
						$sede_select->Default = $idPais;
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
						echo $c_textbox->Textbox ("direccion", "Direccion", 1, $this->direccion, "form-control text_input", 50, "", "", "");
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
					Tel&eacute;fono (Conmutador):
					<div class="">
					<?php
						echo $c_textbox->Textbox ("telefono", "fijo", 1, $this->telefono_fijo, "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Tel&eacute;fono fijo 2:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("telefono1", "fijo", 0, $this->telefono_fijo1, "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Celular:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("celular", "celular", 1, $this->telefono_celular, "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Celular 1:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("celular1", "celular", 0, $this->telefono_celular1, "form-control", 30, "", "", "");
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
						echo $c_textbox->Textbox ("documento_representante", "documento_representante", 1, "$this->identificacion_representante", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					Ciudad expedición:
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
					Representante legal suplente:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("representante_supl", "", 0, "$this->representante_supl", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Identificaci&oacute;n:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("identificacion_representante_supl", "identificacion_representante_supl", 0, "$this->identificacion_representante_supl", "form-control", 30, "", "", "");
					?>
					</div>
                </div>
				<div class="col-md-3 labelCustom">
					Ciudad expedición:
					<div class="">
					<?php

						$sede_select = new Select("id_ciudad_exp_representante_supl","id_ciudad_exp_representante_supl",$arrCiudades,"",0,"", "form-control", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $this->id_ciudad_exp_representante_supl;
						echo $sede_select->genCode();
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-3 labelCustom">
					Persona autorizada:
					<div class="">
					<?php
						echo $c_textbox->Textbox("encargado", "encargado", 1, "$this->encargado", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Cargo:
					<div class="">
					<?php
						echo $c_textbox->Textbox("cargo_autorizador", "cargo_autorizador", 1, "$this->cargo_autorizador", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Celular:
					<div class="">
					<?php
						echo $c_textbox->Textbox("telefonos_encargado", "encargado", 1, "$this->telefonos_encargado", "form-control", 50, "", "", "");
					?>
					</div>
                </div>
                <div class="col-md-3 labelCustom">
					Correo funcionaro autorizado:
					<div class="">
					<?php
						echo $c_textbox->Textbox("correo_personal", "Correo Personal", 1, "$this->correo_personal", "form-control email no_mayus", "30", "", "", "", "");
					?>
					</div>
                </div>
            </div>
            <div class="row ejecutivo pagador" style="height:10px;">&nbsp;</div>
            <div class="row ejecutivo pagador">
 				<div class="col-md-3 labelCustom pagador">
					Cupo aprobado:
					<div class="">
					<?php
                    	echo $c_textbox->Textbox("cupo", "cupo", 1, $this->cupo, "form-control number", 30, "", "", "","","return IsNumber(event);");
                	?>
					</div>
                </div>
                <div class="col-md-3 labelCustom ejecutivo">
					Ejecutivo:
					<div class="">
					<?php
						$ejecutivo_select = new Select("id_ejecutivo","id_ejecutivo",$arrEjecutivos,"",1,"", "form-control", 0, "", "", 0);
						$ejecutivo_select->enableBlankOption();
						$ejecutivo_select->Default = $this->id_ejecutivo;
						echo $ejecutivo_select->genCode();
					?>
					</div>
                </div>
                <div class="col-md-1 labelCustom ejecutivo">
					Comisión:
					<div class="">
					<?php
						echo $c_textbox->Textbox ("comision", "comision", 1, $this->comision, "form-control number", 50, "7", "", "","","return IsNumber(event);");
					?>
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_cliente_btnSave" onclick="saveClient();">
				<?php
					if ($this->id_cliente != 0)
					{
						//CONTROLAMOS ESTADO CREADO
						if ($this->activo == 0){
							echo "<input type=\"button\" value=\"Activar\" class=\"btn btn-success datos_cliente_btnSave\" onclick=\"cambiarEstado(1,'activar');\">";
							echo "<input type=\"button\" value=\"Inactivar\" class=\"btn btn-danger datos_cliente_btnSave\" onclick=\"cambiarEstado(2,'inactivar');\">";
							echo "<input type=\"button\" value=\"Rechazar\" class=\"btn btn-warning datos_cliente_btnSave\" onclick=\"rechazarCliente();\">";
						}

						if ($this->activo == 2){
							echo "<input type=\"button\" value=\"Activar\" class=\"btn btn-success datos_cliente_btnSave\" onclick=\"cambiarEstado(1,'activar');\">";
						}

						if ($this->activo == 1){
							echo "<input type=\"button\" value=\"Inactivar\" class=\"btn btn-danger datos_cliente_btnSave\" onclick=\"cambiarEstado(2,'inactivar');\">";
						}
                	}
                ?>

            </center>
            <div class="row" style="height:10px;">&nbsp;</div>
        	<div role="tabpanel" class="tabbable">
            <?php
                if ($this->id_cliente != 0){
            ?>
                <ul class="nav nav-tabs tabs_cliente" role="tablist">
                    <li role="presentation" class="tab_custom active info_anexo"><a href="#info_anexo" onclick="cargarInfoAnexo();" aria-controls="info_anexo" role="tab" data-toggle="tab">Información adicional</a></li>
                    <li role="presentation" class="tab_custom documentos_cliente"><a href="#documentos_cliente" onclick="cargarInfoDocumentos();" aria-controls="documentos_cliente" role="tab" data-toggle="tab" >Documentos</a></li>
                    <?php
                    	if ($this->id_tipo_tercero == 1 || $this->id_tipo_tercero_sec == 1)
                    	{
                    ?>
						<li role="presentation" class="tab_custom"><a href="#info_condicion" onclick="cargarInfoAdicional();" aria-controls="info_condicion" role="tab" data-toggle="tab" >Parámetros</a></li>
						<li role="presentation" class="tab_custom"><a href="#ref_pagador" onclick="cargarReferenciaPagador();" aria-controls="ref_pagador" role="tab" data-toggle="tab" >Referencia pagador</a></li>
						<li role="presentation" class="tab_custom"><a href="#info_pdf" onclick="cargarInfoPdf();" aria-controls="info_pdf" role="tab" data-toggle="tab" >Oferta</a></li>
						<li role="presentation" class="tab_custom"><a href="#formato_pagare" onclick="cargarFormatoPagare();" aria-controls="formato_pagare" role="tab" data-toggle="tab" >Pagaré</a></li>
						<li role="presentation" class="tab_custom"><a href="#carta_instrucciones" onclick="cargarCartaInstrcciones();" aria-controls="carta_instrucciones" role="tab" data-toggle="tab">Carta de instrucciones</a></li>
					<?php
 						}
                    ?>
                    <?php
                    	if ($this->id_tipo_tercero == 6 || $this->id_tipo_tercero_sec == 6)
                    	{
                    ?>
						<li role="presentation" class="tab_custom"><a href="#info_aceptacion" onclick="cargarInfoAceptacion();" aria-controls="info_aceptacion" role="tab" data-toggle="tab" >Aceptaciones</a></li>
					<?php
 						}
                    ?>
                    <li role="presentation" class="tab_custom"><a href="#info_seguimiento" onclick="cargarInfoSeguimiento();" aria-controls="info_seguimiento" role="tab" data-toggle="tab" >Seguimiento</a></li>
                    <li role="presentation" class="tab_custom"><a href="#impresa" onclick="cargarVersionImpresa();" aria-controls="impresa" role="tab" data-toggle="tab" >Impresa</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="info_anexo"></div>
                    <div role="tabpanel" class="tab-pane" id="documentos_cliente"></div>
                    <div role="tabpanel" class="tab-pane" id="info_condicion"></div>
                    <div role="tabpanel" class="tab-pane" id="info_pdf"></div>
                    <div role="tabpanel" class="tab-pane" id="ref_pagador"></div>
                    <div role="tabpanel" class="tab-pane" id="formato_pagare"></div>
                    <div role="tabpanel" class="tab-pane" id="carta_instrucciones"></div>
                    <div role="tabpanel" class="tab-pane" id="info_aceptacion"></div>
                    <div role="tabpanel" class="tab-pane" id="info_seguimiento"></div>
                    <div role="tabpanel" class="tab-pane" id="impresa"></div>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>
<?php
    if ($this->id_cliente != 0){
?>
    <script>
        $(document).ready(function (){
			validarTipoTercero();
			cargarDepartamentos('<?=$idPais?>','<?=$this->id_departamento?>');
			cargarSelect('mod=zonificacion&action=getCiudadesJson', '<?=$this->id_departamento?>', 'id_ciudad', '<?=$this->ciudad?>', '');
        });
    </script>
<?php
    }
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
                <input type="hidden" id="tipo_reporte" name="tipo_reporte" value="0">
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
                <div class="row">
                    <div class="col-md-2 labelCustom">Observaciones:</div>
                    <div class="col-md-9">
                        <textarea id="observaciones_correo" name="observaciones_correo" value="" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row" style="height:10px;">&nbsp;</div>
                <center>
                    <input type="button" class="btn btn-primary" value="Enviar" onclick="enviarReporte();">
                </center>
                </form>
            </div>
        </div>
    </div>
</div>


