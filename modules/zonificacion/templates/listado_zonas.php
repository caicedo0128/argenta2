<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<div id="edit_zona"></div>
<script>
$(document).ready(function() {
    oTable = $('#tableDataZonas').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });        
});

function editarZona(idZona, mod, action, idCiudad){

    $("#edit_zona").text("Cargando...");
    
    $("#edit_zona").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 720,
        title: 'Registrar y/o Actualizar Zonas'
    });

    $("#edit_zona").load("admindex.php",{Ajax: true, mod:mod, action:action,id_zona_sector:idZona,id_ciudad:idCiudad}, function(){

        window.setTimeout(function () {
            $("#edit_zona").addClass("reload");
        }, 400);
        $('#edit_zona').removeClass('reload');
        $("#edit_zona").dialog("open");
    });
}

function cerrarZona(){

    $("#edit_zona").text("Cargando...");
    $("#edit_zona").dialog("close");
}

</script>