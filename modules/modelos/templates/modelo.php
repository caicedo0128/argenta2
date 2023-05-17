<script type="text/javascript">

function saveModelo(){

    validateForm("datosRegistro");

    if ($("#datosRegistro").valid()){

        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&" + $("#datosRegistro").serialize();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    showSuccess(response.Message);
                    if (response.Success) {
                        $("#id_modelo").val(response.IdModelo);
                        window.setTimeout(function(){
                            cargarConfiguracion();
                        },500);
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }    
    
}

function cargarConfiguracion() {

    var idModelo = $("#id_modelo").val();
    loader();
    $("#configuracion_modelo").load('admindex.php', { Ajax:true, mod: 'modelos', action:'listConfiguracion', id_modelo : idModelo}, function () {
        loader();
    });
}

</script>
<div class="panel panel-primary col-md-offset-2 col-md-8">
    <div class="panel-body">
        Registro de modelo
        <div class="cerrar_form" onclick="cargarModelos();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
            <input type="hidden" name="mod" value="modelos" />
            <input type="hidden" name="action" value="saveModelo" />
            <input type="hidden" name="id_modelo" id="id_modelo" value="<?=$idModelo?>" />
            <div class="row" style="height:10px;">&nbsp;</div>     
            <div class="row">
                <div class="col-md-1 labelCustom">Modelo:</div>
                <div class="col-md-4">
                <?php 
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("nombre", "nombre", 1, $modelo->nombre, "form-control", 50, "", "", "");
                ?>           
                </div>
                <div class="col-md-1 labelCustom">Activo:</div>
                <div class="col-md-3">
                <div id="divRadioactivo" class="radioValidate">
                <?php
                    $c_radio = new Radio;
                    $arrSiNo = array("1"=>"Si","2"=>"No");
                    $c_radio->Radio("activo","Activo",$arrSiNo,"", 1, $modelo->activo, "", 0, "customValidateRadio('activo');");
                    while($tmp_html = $c_radio->next_entry()) {
                        echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    }
                ?>
                </div>         
                </div>
            </div>    
          
            <div class="row" style="height:10px;">&nbsp;</div>               
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_modelo_btnSave" onclick="saveModelo();">
            </center> 
        </form>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="tab_custom active"><a href="#configuracion_modelo" aria-controls="configuracion_modelo" role="tab" data-toggle="tab">Configuración modelo</a></li>
        </ul>
        <div id="configuracion_modelo">

        </div>    
</div>
<?php
    if ($idModelo != 0){
?>
    <script>
        $(document).ready(function () {
            cargarConfiguracion();
        });
    </script>
<?php
}
?>