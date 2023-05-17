<script>
$(document).ready(function() {
    oTable = $('#list_parametros').dataTable({ "paging":false, "bStateSave": true, "bInfo": false });
});

function saveParam(){

    validateForm("datosRegistro");

    if ($("#datosRegistro").valid()){

        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&mod=admin&action=saveParam&" + $("#datosRegistro").serialize();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data: dataForm,
                success: function (response) {
                    showSuccess("Transacción exitosa. Espere por favor...");
                    if (response.Success) {
                        window.location.href="admindex.php?mod=admin&action=listParamGral";
                    }
                }
        });
    }
}

</script>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Listado parametros</h4>
    </div>
</div>
<div id="parametros" class="container-fluid " style="clear:both;padding-top:15px;">
<form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php">
<input type="button" onclick="saveParam();" class="btn btn-primary" value="Guardar Parametros"/>              
<br/><br/>
<table id="list_parametros" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
<thead>
    <tr>                            
        <th align='center'>Parametro</th>
        <th align='center'>Valor</th>                            
        <th align='center'>Descripcion</th>
    </tr>
</thead>
<?php

    $i = 1;
    while (!$rsParam->EOF){

        $idParametro= $rsParam->fields["id_parametro"];

        echo "<tr>";
        echo "<td>".$rsParam->fields["parametro"]."</td>";                            
        echo "<td>";
        echo "<input type='text' name='param_val_".$i."' id='param_val_".$i."' value='".$rsParam->fields["valor"]."' size='80' class='form-control no_mayus required'>";
        echo "<input type='hidden' name='param_id_".$i."' id='param_id_".$i."' value='".$rsParam->fields["id_parametro"]."' size='80' class='form-control required'>";
        echo "</td>";
        echo "<td>".$rsParam->fields["descripcion"]."</td>";                            
        echo "</tr>";
        $i++;
        $rsParam->MoveNext();
    }

?>
</table>
<input type="hidden" name="total_param" value="<?=$i?>" id="total_param">
</form>
  
</div>
