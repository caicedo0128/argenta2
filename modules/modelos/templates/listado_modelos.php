<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de modelos</h4>
    </div>
</div>
<div id="content_modelos" class="container-fluid " style="clear:both;padding-top:15px;">
<?php
    $dataGrid->displayDataGrid();
?>
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({ "pagingType": "full_numbers", "bStateSave": true, "bInfo": false });     
});

function editModelo(idModelo, mod, action) {
    loader();
    $("#content_modelos").load('admindex.php', { Ajax:true, id_modelo: idModelo, mod: mod, action:action}, function () {
        loader();
    });
}

function deleteModelo(idModelo, mod, action) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=modelos&action=eliminarModelo&id_modelo=" + idModelo
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarModelos();
            }
    });
}

function cargarModelos() {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'modelos', action:'listModelos'}, function () {
        loader();
    });
}

</script>