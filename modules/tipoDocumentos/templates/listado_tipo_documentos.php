<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de tipos de documentos</h4>
    </div>
</div>
<div id="content_documentos" class="container-fluid " style="clear:both;padding-top:15px;">
<?php
    $dataGrid->displayDataGrid();
?>
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({ "pagingType": "full_numbers", "bStateSave": true, "bInfo": false });     
});

function editTipoDocumento(idDocumento, mod, action) {
    loader();
    $("#content_documentos").load('admindex.php', { Ajax:true, id_tipo_documento: idDocumento, mod: mod, action:action}, function () {
        loader();
    });
}

function deleteDocumento(idDocumento, mod, action) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=tipoDocumentos&action=eliminarDocumentos&id_tipo_documento=" + idDocumento
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarTipoDocumento();
            }
    });
}

function cargarTipoDocumento() {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'tipoDocumentos', action:'listTipoDocumentos'}, function () {
        loader();
    });
}

</script>