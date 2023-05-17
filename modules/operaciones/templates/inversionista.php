<script type="text/javascript">

$(document).ready(function(){

});

function saveInversionista(){

    validateForm("datosInversionista");

    if ($("#datosInversionista").valid()){

        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&" + $("#datosInversionista").serialize();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    window.setTimeout(function(){
                        closeNotify();
                        showSuccess(response.Message);
                        if (response.Success) {
                            cargarInversionistas();
                        }
                    },800);
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function validarInversion(){
	
	var valorInversion = parseFloat($("#valor_inversion").val());
	var valorValidacion = parseFloat($("#valor_validacion").val());	
	var valorInversionReal = valorInversion;
	if (valorInversionReal > valorValidacion){
		showError("El valor de la inversión no puede ser mayor que el valor del giro final. Verifique.");
		$("#valor_inversion").val("");
	}
}

</script>
<div class="panel panel-primary">
    <div class="panel-body">
        Registro de inversionista
        <div class="cerrar_form" onclick="cargarInversionistas();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosInversionista" method="post" name="datosInversionista" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="operaciones" />
            <input type="hidden" name="action" value="saveInversionista" />
            <input type="hidden" name="id_inversionista_operacion" id="id_inversionista_operacion" value="<?=$idInversionistaOperacion?>" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />            
            <input type="hidden" name="valor_validacion" id="valor_validacion" value="<?=$valorValidacionInversion?>" />
            <div class="row" style="height:10px;">&nbsp;</div>  
            <div class="row">            
                <div class="col-md-2 labelCustom">Inversionista:</div>
                <div class="col-md-4">
                <?php

                    $sede_select = new Select("id_inversionista","Tercero",$arrInversionistas,"",1,"", "form-control required", 0, "", "", 0);
                    $sede_select->enableBlankOption();
                    $sede_select->Default = $inversionista->id_inversionista;
                    echo $sede_select->genCode();
                ?>    
                </div>    
                <div class="col-md-2 labelCustom">Valor inversión:</div>
                <div class="col-md-2">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("valor_inversion", "valor_inversion", 1, $inversionista->valor_inversion, "form-control required number", 50, "", "validarInversion();", "","","return IsNumber(event);");  
                    echo "<b><i>Restante para esta operación: ".formato_moneda($valorValidacionInversion)."</i></b>";
                ?>
                </div>                    
            </div> 
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
			<?php
				if ($operacion->estado == 3 || $operacion->estado == 1){
			?>              
            <center>
                <input type="button" value="Guardar inversionista" class="btn btn-success datosInversionista_btnSave" onclick="saveInversionista();">
            </center>
			<?php
				}
			?>              
        </form>
</div>
<?php
    if ($idInversionista != 0){
?>
    <script>
        $(document).ready(function () {
        	
        });
    </script>
<?php
}
?>


