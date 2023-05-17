<div class="row-fluid">
    <div class="bg-title">
        Información de acciones en la plataforma
    </div>
    <div class="panel-body panel-custom"  id="content_acciones">
    <?php
    
        if ($idPadre!="0")
            echo "<div class='alert alert-success'>Ir a: <a href='javascript:cargarPaginas();' style='color:inherit;'>Primer nivel</a> => <a href=\"javascript:verPaginasHijas('".$idPaginaPadre."', 'paginas', 'verListado', '');\" class='titletableLink' style='color:inherit;'>Nivel anterior</a></div>";

        $dataGrid->displayDataGrid();
    ?>
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({ "pagingType": "full_numbers", "bStateSave": true });        
});

function editPagina(idPagina, mod, action, idPadre, idPaginaPadre) {
    loader();
    $("#content_acciones").load('admindex.php', { Ajax:true, id_pagina: idPagina, mod: mod, action:action, id_padre:idPadre, id_pagina_padre:idPaginaPadre}, function () {
        loader();
    });    
}

function cargarPaginas() {
    loader();
    $("#content_general").load('admindex.php', { Ajax:true, mod: 'paginas', action:'verListado'}, function () {
        loader();
    });
}

function verPaginasHijas(idPadre, mod, action, idPaginaPadre) {
    loader();
    $("#content_general").load('admindex.php', { Ajax:true, mod: mod, action:action, id_padre:idPadre, id_pagina_padre:idPaginaPadre}, function () {
        loader();
    });    
}

function subirNivel(idPagina, idPadre, mod, action, idPaginaPadre) {

    showLoading("Enviando información. Espere por favor...");
    var strUrl = "admindex.php";
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:{
                Ajax:true,
                mod:'paginas',
                action:'cambiarOrden',
                id_pagina:idPagina,
                id_padre:idPadre,
                id_pagina_padre:idPaginaPadre
            },
            success: function (response) {
                closeNotify();
                if (response.Success) {
                    showSuccess("Transacción exitosa. Espere por favor...");
                    verPaginasHijas(idPadre, mod, 'verListado', idPaginaPadre);
                }
            }
    });
   
}

</script>