<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de campos</h4>
    </div>
</div>
<div id="content_campos" class="container-fluid " style="clear:both;padding-top:15px;">
<?php
    $dataGrid->displayDataGrid();
?>
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({ "pagingType": "full_numbers", "bStateSave": true, "bInfo": false });       
});

function editField(idCampo, mod, action) {
    loader();
    $("#content_campos").load('admindex.php', { Ajax:true, id_campo: idCampo, mod: mod, action:action}, function () {
        loader();
    });
}

function deleteField(idCampo, mod, action) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=fieldsDynamic&action=deleteField&id_campo=" + idCampo
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarCampos();
            }
    });
}

function cargarCampos() {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'fieldsDynamic', action:'listFields'}, function () {
        loader();
    });
}

</script>