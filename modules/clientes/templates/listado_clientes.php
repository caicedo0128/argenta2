<script>

    function exportarClientes(){

    	var idTipoTercero = $("#id_tipo_tercero_buscador").val();
    	$("#id_tipo_tercero_buscador").removeClass("invalid");
    	if (idTipoTercero == ""){
    		showError("Seleccione un tipo de tercero");
    		$("#id_tipo_tercero_buscador").addClass("invalid");
    	}
    	else
    		window.location.href = "admindex.php?Ajax=true&mod=clientes&action=exportarClientes&idTipoTercero=" + idTipoTercero;

    }
</script>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de terceros</h4>
    </div>
</div>
<div id="content_clientes" class="" style="clear:both;padding-top:15px;">
<?php
    $dataGrid->displayDataGrid();
?>
</div>
<script>
$(document).ready(function() {
    var oTable = $('#tableDataClientes').dataTable({ "pagingType": "full_numbers", "bStateSave": true});
});

function editCliente(idCliente, mod, action) {
    loader();
    $("#content_clientes").load('admindex.php', { Ajax:true, id_cliente: idCliente, mod: mod, action:action}, function () {
        loader();
		$("#content_reporte_clientes").hide();
		$("#titulo_reporte_clientes").hide();
		goObjHtml("content_general", 70);
    });
}

function deleteCliente(idCliente, mod, action) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=clientes&action=deleteClient&id_cliente=" + idCliente
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                if (response.Success){
                    showSuccess("Transacción exitosa. Espere por favor...");
                    cargarClientes();
                }
                else{
                    showError("El registro no se puede eliminar por que tiene información relacionada.", 5000);
                }
            }
    });
}

function cargarClientes() {
    buscarClientes();
}

function cargarEstudios(idTercero) {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'estudioRiesgo', action:'listEstudios', id_tercero: idTercero}, function () {
        loader();
    });
}

function formDatosReporte(idTercero){
    $("#id_cliente").val(idTercero);
    $('#modalToMail').modal('show');
}

function enviarReporte(){

    validateForm("custom_data_to_email");

    if ($("#custom_data_to_email").valid()) {

        showLoading("Enviando reporte por correo electrónico...");

        var dataForm = "Ajax=true&" + $("#custom_data_to_email").serialize();
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
}

</script>
<div id="modalToMail" class="modal fade" role="dialog" aria-labelledby="modalToMail" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Enviar correo electrónico a:</h4>
            </div>
            <div class="modal-body" id="">
                <form id="custom_data_to_email" name="custom_data_to_email">
                <input type="hidden" name="mod" id="mod" value="clientes">
                <input type="hidden" name="action" id="action" value="sendDataClient">
                <input type="hidden" name="id_cliente" id="id_cliente" value="0">
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
                    <input type="button" class="btn btn-success" value="Enviar" onclick="enviarReporte();">
                </center>
                </form>
            </div>
        </div>
    </div>
</div>