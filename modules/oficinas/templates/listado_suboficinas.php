<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<script type="text/javascript">
$(document).ready(function() {
    oTable = $('#tableSubOficinas').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
});


function editarSubOficina(idOficina, idSubOficina, mod, action){

    $("#content_suboficina").text("Cargando...");

    $("#content_suboficina").dialog({
    	autoOpen: false,
        resizable: false,
        modal: true,
        position: ['center', 'center'],
        minHeight: 450,
        width: 700,
        title: 'Areas'
    });

    $("#content_suboficina").load("admindex.php",{Ajax: true, mod:mod, action:action,id_oficina:idOficina, id_suboficina:idSubOficina}, function(){

        window.setTimeout(function () {
            $("#content_suboficina").addClass("reload");
        }, 400);
        $('#content_suboficina').removeClass('reload');
       	$("#content_suboficina").dialog("open");
    });

}

function cerrarSubOficina(){

    $("#content_suboficina").text("Cargando...");
    $("#content_suboficina").dialog("close");
}

</script>
<div id="content_suboficina"></div>