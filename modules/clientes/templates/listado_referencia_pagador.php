<script type="text/javascript">

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Referencia pagador</h4>
    </div>
</div>
<div id="content_referencia_pagador" class="container-fluid " style="clear:both;padding-top:15px;">
<?php
    $dataGrid->displayDataGrid();
?>
</div>
<script>
$(document).ready(function() {
    var oTable = $('#tableDataReferenciaPagador').dataTable({ "pagingType": "full_numbers", "bStateSave": true});  
});

function editReferenciaPagador(idFefPagador, mod, action) {
    loader();
    $("#content_referencia_pagador").load('admindex.php', { Ajax:true, id_ref_pagador: idFefPagador, id_cliente: <?=$idCliente?>, mod: mod, action:action}, function () {
        loader();
    });
}

function deleteReferenciaPagador(idFefPagador, mod, action) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=" + mod + "&action=" + action + "&id_ref_pagador=" + idFefPagador;
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                if (response.Success){
                    showSuccess("Transacción exitosa. Espere por favor...");
                    cargarReferenciaPagador();
                }
                else{
                    showError("El pagador no se puede eliminar por que tiene operaciones asignadas.", 5000);
                }
            }
    });
}

</script>
