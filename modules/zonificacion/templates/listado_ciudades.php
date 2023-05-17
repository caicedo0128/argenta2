<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<div id="edit_ciudad"></div>
<div id="content_zonas"></div>
<script>
$(document).ready(function() {
    oTable = $('#tableDataCiudades').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });        
});

function cargarZonas(idCiudad, mod){

    $("#content_zonas").text("Cargando...");

    $("#content_zonas").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 720,
        title: 'Zonas',
        close: function (event, ui){
            $("#edit_zona").dialog("close");    
        }
    });

    $("#content_zonas").load("admindex.php",{Ajax: true, mod:mod, action:'listZonas',id_ciudad:idCiudad}, function(){

        window.setTimeout(function () {
            $("#content_zonas").addClass("reload");
        }, 400);
        $('#content_zonas').removeClass('reload');
        $("#content_zonas").dialog("open");
    });
}

function editarCiudad(idCiudad, mod, action, idDepartamento){

    $("#edit_ciudad").text("Cargando...");
    
    $("#edit_ciudad").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 720,
        title: 'Registrar y/o Actualizar Ciudad',
        buttons: [{
            text: "Cerrar",
            click: function() {
                $(this).dialog("close");
            }
        }]   
    });

    $("#edit_ciudad").load("admindex.php",{Ajax: true, mod:mod, action:action,id_ciudad:idCiudad,id_departamento:idDepartamento}, function(){

        window.setTimeout(function () {
            $("#edit_ciudad").addClass("reload");
        }, 400);
        $('#edit_ciudad').removeClass('reload');
        $("#edit_ciudad").dialog("open");
    });
}

function cerrarCiudad(){

    $("#edit_ciudad").text("Cargando...");
    $("#edit_ciudad").dialog("close");
}

</script>