<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<script type="text/javascript">
$(document).ready(function() {
    oTable = $('#tableData').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
});

function editarOficina(idOficina, mod, action){

    $("#content_oficina").text("Cargando...");

    $("#content_oficina").dialog({
    	autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 700,
        title: 'Oficinas'
    });

    $("#content_oficina").load("admindex.php",{Ajax: true, mod:mod, action:action,id_oficina:idOficina}, function(){

        window.setTimeout(function () {
            $("#content_oficina").addClass("reload");
        }, 400);
        $('#content_oficina').removeClass('reload');
       	$("#content_oficina").dialog("open");
    });

}

function cerrarOficina(){

    $("#content_oficina").text("Cargando...");
    $("#content_oficina").dialog("close");
}

function cargarSubOficinas(idOficina, mod, action){

    $("#listado_suboficinas").text("Cargando...");

    $("#listado_suboficinas").dialog({
    	autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 600,
        width: 800,
        title: 'Areas'
    });

    $("#listado_suboficinas").load("admindex.php",{Ajax: true, mod:mod, action:action,id_oficina:idOficina}, function(){

        window.setTimeout(function () {
            $("#listado_suboficinas").addClass("reload");
        }, 400);
        $('#listado_suboficinas').removeClass('reload');
       	$("#listado_suboficinas").dialog("open");
    });

}

</script>
<div id="content_oficina"></div>
<div id="listado_suboficinas"></div>