<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de usuarios</h4>
    </div>
</div>
<div id="content_usuarios" class="container-fluid " style="clear:both;padding-top:15px;">
<?php
    $dataGrid->displayDataGrid();
?>
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({ "pagingType": "full_numbers", "bStateSave": true, "bInfo": false });     
});

function editUsuario(idUsuario, mod, action) {
    loader();
    $("#content_usuarios").load('admindex.php', { Ajax:true, id_usuario: idUsuario, mod: mod, action:action}, function () {
        loader();
    });
}

function cargarUsuarios() {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'users', action:'listUsers'}, function () {
        loader();
    });
}

</script>