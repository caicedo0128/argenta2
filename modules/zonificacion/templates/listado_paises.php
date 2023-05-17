<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<div id="content_departamentos"></div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });           
});

    
function cargarDepartamentos(idPais, mod){

    $("#content_departamentos").text("Cargando...");

    $("#content_departamentos").dialog({
    	autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 720,
        title: 'Departamentos',
        close: function (event, ui){
            $("#edit_departamento").dialog("close");    
        },
		buttons: [{
			text: "Cerrar",
			click: function() {
				$(this).dialog("close");
			}
		}]        
    });

    $("#content_departamentos").load("admindex.php",{Ajax: true, mod:mod, action:'listDepartamentos',id_pais:idPais}, function(){

        window.setTimeout(function () {
            $("#content_departamentos").addClass("reload");
        }, 400);
        $('#content_departamentos').removeClass('reload');
       	$("#content_departamentos").dialog("open");
    });
}

</script>