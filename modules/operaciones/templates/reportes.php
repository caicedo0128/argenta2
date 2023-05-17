<script>

    function formDatosReporte(tipoReporte){

        $("#tipo_reporte").val(tipoReporte);
        $(".desembolso").hide();
        if (tipoReporte == 4)
            $(".desembolso").show();
        $('#modalToMail').modal('show');
    }

    function enviarReporte(){

        validateForm("custom_data_to_email");

        if ($("#custom_data_to_email").valid()) {

            showLoading("Enviando reporte por correo electrónico...");
            var tipoReporte = $("#tipo_reporte").val();
            var option2 = "";

            //REPORTE CLIENTE
            if (tipoReporte == 1){
                action = "reporteCliente";
                subjectMail = "Reporte cliente";
            }
            //REPORTE INVERSIONISTA
            else if (tipoReporte == 2){
                action = "reporteInversionista";
                subjectMail = "Reporte inversionista";
            }
            //REPORTE EJECUTIVO
            else if (tipoReporte == 3){
                action = "reporteEjecutivo";
                subjectMail = "Reporte ejecutivo";
            }
            //REPORTE CLIENTE DETALLE FACTURAS
            else if (tipoReporte == 4){
                action = "reporteClienteDetallado";
                subjectMail = "Reporte cliente detallado";
                option2 = "";
                if ($('#adjuntar_desembolso').is(':checked'))
                    option2 = "S";
            }

            //GENERAMOS EL REPORTE PDF
            var dataForm = "Ajax=true&mod=operaciones&action=" + action + "&es_reporte=true&id_operacion=" + <?=$idOperacion?>;
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "html",
                data:dataForm,
                success: function (response) {

                    var dataForm = "Ajax=true&mod=operaciones&action=guardarReporteOperacion&__dataMail=" + response;
                    var strUrl = "admindex.php";
                    $.ajax({
                        type: 'POST',
                        url: strUrl,
                        dataType: "json",
                        data:dataForm,
                        success: function (response) {

                            $("#formMail input[id=mod]").val("operaciones");
                            $("#formMail input[id=action]").val("enviarReporteOperacion");
                            $("#formMail input[id=__option1]").val(<?=$idOperacion?>);
                            $("#formMail input[id=__option2]").val(option2);
                            $("#formMail input[id=__subjectMail]").val(subjectMail);
                            $("#formMail input[id=__toEmailMail]").val($("#correo_to_email").val());
                            $("#formMail input[id=__toNameMail]").val($("#nombre_to_email").val());
                            $("#formMail input[id=__dataMail]").val($("#observaciones_correo").val());
                            $("#formMail input[id=__template]").val(action);


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

    function descargarReporte(tipoReporte){

        showLoading("Descargando reporte. Espere por favor...");

        //REPORTE CLIENTE
        if (tipoReporte == 1){
            action = "reporteCliente";
            nombreReporte = "ReporteCliente.pdf";
        }
        //REPORTE INVERSIONISTA
        else if (tipoReporte == 2){
            action = "reporteInversionista";
            nombreReporte = "ReporteInversionista.pdf";
        }
        //REPORTE EJECUTIVO
        else if (tipoReporte == 3){
            action = "reporteEjecutivo";
            nombreReporte = "ReporteEjecutivo.pdf";
        }
        //REPORTE CLIENTE DETALLE FACTURAS
        else if (tipoReporte == 4){
            action = "reporteClienteDetallado";
            nombreReporte = "ReporteClienteDetallado.pdf";
        }
        //REPORTE CLIENTE DETALLE FACTURAS
        else if (tipoReporte == 5){
            action = "reporteClienteDetalladoFacturacion";
            nombreReporte = "ReporteClienteDetalladoFacturacion.pdf";
        }        

        //GENERAMOS EL REPORTE PDF
        var dataForm = "Ajax=true&mod=operaciones&action=" + action + "&es_reporte=true&id_operacion=" + <?=$idOperacion?>;
        var strUrl = "admindex.php";
        $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "html",
            data:dataForm,
            success: function (response) {

                $("#formMail input[id=mod]").val("operaciones");
                $("#formMail input[id=action]").val("guardarReporteOperacion");
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
                        downloadURI("./gallery/operaciones/reporte.pdf", nombreReporte);
                    }
                });
            }
        });
        //FIN GUARDADO PDF
    }

</script>
<style>
    .desembolso{
        display:none;
    }

    li{
    	margin-bottom:10px;
    }

</style>
<?php
    //IMPRIMIMOS EL SUMARIO DE LA OPERACION
    $this->sumarioOperacion($idOperacion);
?>
    <div class="col-md-12 bg-primary-custom">
        <h4>Reportes operación</h4>
    </div>
    <br/><br/><br/><br/>
    <ul>
    <?php
    	if ($_SESSION["profile_text"]!="Cliente")
    	{
    ?>
			<li>
				<a href="javascript:;" onclick="descargarReporte(1);" title="Cliente" class="" style="color:inherit;"><i class="fa fa-search fa-lg"></i>Reporte general</a> -
				<a href="javascript:;" onclick="formDatosReporte(1);" title="Cliente" class="" style="color:inherit;"><i class="fa fa-envelope fa-lg"></i>Enviar reporte</a>
			</li>

    <?php
    	}
    ?>

	<li>
		<a href="javascript:;" onclick="descargarReporte(4);" title="Cliente" class="" style="color:inherit;"><i class="fa fa-search fa-lg"></i>Reporte detallado operación</a> -
    	<a href="javascript:;" onclick="formDatosReporte(4);" title="Cliente detalle" class="" style="color:inherit;"><i class="fa fa-envelope fa-lg"></i>Enviar reporte</a>
    </li>
	<li>
		<a href="javascript:;" onclick="descargarReporte(5);" title="Cliente" class="" style="color:inherit;"><i class="fa fa-search fa-lg"></i>Reporte detallado facturación operación</a>
    </li>    

    <?php
    /*
    	if ($_SESSION["profile_text"]!="Cliente")
    	{
    ?>
    		<li>
    			<a href="javascript:;" onclick="descargarReporte(2);" title="Cliente" class="" style="color:inherit;"><i class="fa fa-search fa-lg"></i>Reporte inversionista</a> -
				<a href="javascript:;" onclick="formDatosReporte(2);" title="Inversionista" class="" style="color:inherit;"><i class="fa fa-envelope fa-lg"></i>Enviar reporte</a>
			</li>
			<li>
				<a href="javascript:;" onclick="descargarReporte(3);" title="Cliente" class="" style="color:inherit;"><i class="fa fa-search fa-lg"></i>Reporte ejecutivo</a> -
				<a href="javascript:;" onclick="formDatosReporte(3);" title="Ejecutivo" class="" style="color:inherit;"><i class="fa fa-envelope fa-lg"></i>Enviar reporte</a>
			</li>
   	<?php
		}
		*/
    ?>
    </ul>
<br/>
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
                <div class="row desembolso" style="height:10px;">&nbsp;</div>
                <div class="row desembolso">
                    <div class="col-md-2 labelCustom">Adjuntar soportes desembolso:</div>
                    <div class="col-md-9">
                        <input type="checkbox" name="adjuntar_desembolso" id="adjuntar_desembolso" value="1">
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
                    <input type="button" class="btn btn-success" value="Enviar" onclick="enviarReporte();">
                </center>
                </form>
            </div>
        </div>
    </div>
</div>

