<script type="text/javascript">

function saveCampo(){

    validateForm("datosCampo");

    if ($("#datosCampo").valid()){

        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&" + $("#datosCampo").serialize() + "&id_modelo=" + $("#id_modelo").val();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    $('#modalCampo').modal('hide');
                    window.setTimeout(function(){
                        closeNotify();
                        showSuccess(response.Message);
                        if (response.Success) {
                            cargarConfiguracion();
                        }
                    },800);
                }
        });
    }
    else{
        showError("Por favor revise los campos marcados.");
    }
}

</script>
<div class="panel panel-primary">
    <div class="panel-body">
        <form id="datosCampo" method="post" name="datosCampo" action="admindex.php" enctype="multipart/form-data">        
            <input type="hidden" name="mod" value="modelos" />
            <input type="hidden" name="action" value="saveCampo" />
            <input type="hidden" name="id_modelo_campo" id="id_modelo_campo" value="<?=$idModeloCampo?>" />
            <input type="hidden" name="id_grupo" id="id_grupo" value="<?=$idGrupo?>" />
            <div class="row" style="height:10px;">&nbsp;</div>     
            <div class="row">
                <div class="col-md-3 labelCustom">Campo:</div>
                <div class="col-md-6"> 
                <?php

                    $selectData = new Select("id_campo","nombre",$arrCampos,"",1,"", "form-control", 0, "", "", 0);
                    $selectData->enableBlankOption();
                    $selectData->Default = $campo->id_campo;
                    echo $selectData->genCode();
                ?>                 
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div> 
            <div class="row">
                <div class="col-md-3 labelCustom">Es obligatorio:</div>
                <div class="col-md-3">
                <div id="divRadioes_obligatorio" class="radioValidate">
                <?php
                    $c_radio = new Radio;
                    $arrSiNo = array("1"=>"Si","2"=>"No");
                    $c_radio->Radio("es_obligatorio","Activo",$arrSiNo,"", 1, $campo->es_obligatorio, "", 0, "customValidateRadio('es_obligatorio');");
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
                <input type="button" value="Guardar" class="btn btn-primary datos_campo_btnSave" onclick="saveCampo();">
            </center> 
        </form>   
</div>

