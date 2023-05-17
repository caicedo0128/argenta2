<script type="text/javascript">

$(document).ready(function(){

});

function saveSeguimiento(){

    validateForm("datosSeguimiento");

    if ($("#datosSeguimiento").valid()){

        showLoading("Enviando información. Espere por favor...");
        var dataForm = "Ajax=true&" + $("#datosSeguimiento").serialize();
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
                            cargarSeguimiento();
                        }
                    },800);
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

</script>
<div class="panel panel-primary">
    <div class="panel-body">
        Registro de seguimiento
        <div class="cerrar_form" onclick="cargarSeguimiento();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosSeguimiento" method="post" name="datosSeguimiento" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="mod" value="operaciones" />
            <input type="hidden" name="action" value="saveSeguimiento" />
            <input type="hidden" name="id_seguimiento_operacion" id="id_seguimiento_operacion" value="<?=$idSeguimientoOperacion?>" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">Contácto:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("contacto", "contacto", 1, $seguimiento->contacto, "form-control required", 50, "", "", "","","");
                ?>
                </div>
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">Observaciones:</div>
                <div class="col-md-7">
				<?php
					$c_textarea = new Textarea;
					echo $c_textarea->Textarea("observaciones", "Observaciones", 1, $seguimiento->observaciones, "form-control", 60, 3);
				?>
                </div>
            </div>
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar seguimiento" class="btn btn-primary datosSeguimiento_btnSave" onclick="saveSeguimiento();">
            </center>
        </form>
</div>
<?php
    if ($idSeguimiento != 0){
?>
    <script>
        $(document).ready(function () {

        });
    </script>
<?php
}
?>


