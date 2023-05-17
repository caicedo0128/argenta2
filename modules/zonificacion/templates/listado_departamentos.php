<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<div id="edit_departamento"></div>
<div id="content_ciudades"></div>
<script>
$(document).ready(function() {
    oTable = $('#tableDataDepartamentos').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });        
});

function cargarCiudades(idDepartamento, mod){

    $("#content_ciudades").text("Cargando...");

    $("#content_ciudades").dialog({
    	autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 720,
        title: 'Ciudades',
        close: function (event, ui){
            $("#edit_ciudad").dialog("close");    
        },
		buttons: [{
			text: "Cerrar",
			click: function() {
				$(this).dialog("close");
			}
		}]           
    });

    $("#content_ciudades").load("admindex.php",{Ajax: true, mod:mod, action:'listCiudades',id_departamento:idDepartamento}, function(){

        window.setTimeout(function () {
            $("#content_ciudades").addClass("reload");
        }, 400);
        $('#content_ciudades').removeClass('reload');
        $("#content_ciudades").dialog("open");
    });
}

function editarDepartamento(idDepartamento, mod, action, idPais){

    $("#edit_departamento").text("Cargando...");
    
    $("#edit_departamento").dialog({
    	autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 720,
        title: 'Registrar y/o Actualizar Departamento',
		buttons: [{
			text: "Cerrar",
			click: function() {
				$(this).dialog("close");
			}
		}]   
    });

    $("#edit_departamento").load("admindex.php",{Ajax: true, mod:mod, action:action,id_departamento:idDepartamento, id_pais:idPais}, function(){

        window.setTimeout(function () {
            $("#edit_departamento").addClass("reload");
        }, 400);
        $('#edit_departamento').removeClass('reload');
       	$("#edit_departamento").dialog("open");
    });
}

function cerrarDerpartamento(){

    $("#edit_departamento").text("Cargando...");
    $("#edit_departamento").dialog("close");
}



</script>