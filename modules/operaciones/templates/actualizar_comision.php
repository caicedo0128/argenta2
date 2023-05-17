<script>
$(document).ready(function() { 
    $('#fecha_pago_comision').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveComision(){

    validateForm("detalle_pago_comision");

    if ($("#detalle_pago_comision").valid()){

        showLoading("Enviando informacion...");
        
        var dataForm = "Ajax=true&" + $("#detalle_pago_comision").serialize();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    showSuccess(response.Message);
                    var idOperacion = $("#id_operacion_pago").val();
                    var fechaPago  = $("#fecha_pago_comision").val();
                    $("#ope_" + idOperacion).text(fechaPago);
                     $('#modalDetalle').modal('hide');
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    } 
}

</script>
<form id="detalle_pago_comision" name="detalle_pago_comision" methos="post">
    <input type='hidden' name="id_operacion_pago" id="id_operacion_pago" value="<?=$operacion->id_operacion?>"> 
    <input type='hidden' name='mod' value='operaciones'>
    <input type='hidden' name='action' value='updateComision'>
    <div class="row">             
        <div class="col-md-2 labelCustom">Fecha pago comisión:</div>
        <div class="col-md-3">
        <?php
            $c_textbox = new Textbox;
            echo $c_textbox->Textbox ("fecha_pago_comision", "fecha_pago_comision", 1, $operacion->fecha_pago_comision, "form-control", 50, "", "", "");  
        ?>
        </div>                    
    </div> 
    <div class="row" style="height:10px;">&nbsp;</div>     
    <div class="row">
        <div class="col-md-2 labelCustom">Observaciones:</div>
        <div class="col-md-12">
        <?php
            $c_textarea = new Textarea;
            echo $c_textarea->Textarea("observaciones_comision", "Observaciones", 1, $operacion->observaciones_comision, "form-control", 60, 3);
        ?>         
        </div>
    </div>                    
</form>
<div class="row" style="height:10px;">&nbsp;</div>   
<center>
<input type="button" value="Guardar" class="btn btn-primary datosOperacion_btnSave" onclick="saveComision();">    
</center>