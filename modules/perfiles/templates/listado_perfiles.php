<div class="row-fluid">
    <div class="bg-title">
        Información de perfiles
    </div>
    <div class="panel-body panel-custom"  id="content_perfiles">
    <?php
        $dataGrid->displayDataGrid();
    ?>
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({ "pagingType": "full_numbers", "bStateSave": true });        
});

function editPerfil(idPerfil, mod, action) {
    loader();
    $("#content_perfiles").load('admindex.php', { Ajax:true, id_perfil: idPerfil, mod: mod, action:action}, function () {
        loader();
    });    
}

function cargarPerfiles() {
    loader();
    $("#content_general").load('admindex.php', { Ajax:true, mod: 'perfiles', action:'verListado'}, function () {
        loader();
    });
}

</script>