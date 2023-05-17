<script type="text/javascript">

function saveGrupo(){

    validateForm("datosGrupo");

    if ($("#datosGrupo").valid()){

        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&" + $("#datosGrupo").serialize() + "&id_modelo=" + $("#id_modelo").val();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    $('#modalGrupo').modal('hide');
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
    else {
        showError("Por favor revise los campos marcados.");
    }    
    
}

</script>
<div class="panel panel-success">
    <div class="panel-body">
        <form id="datosGrupo" method="post" name="datosGrupo" action="admindex.php" enctype="multipart/form-data">        
            <input type="hidden" name="mod" value="modelos" />
            <input type="hidden" name="action" value="saveGrupo" />
            <input type="hidden" name="id_grupo" id="id_grupo" value="<?=$idGrupo?>" />
            <div class="row" style="height:10px;">&nbsp;</div>     
            <div class="row">
                <div class="col-md-2 labelCustom">Grupo:</div>
                <div class="col-md-6">
                <?php 
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("nombre_grupo", "nombre", 1, $grupo->grupo, "form-control", 50, "", "", "");
                ?>           
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>     
            <div class="row">
                <div class="col-md-2 labelCustom">Color:</div>
                <div class="col-md-6">
                <?php 
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("color", "color", 1, $grupo->color, "form-control", 50, "", "", "");
                ?>   
                <small>Código HEX: Ejemplo: Gris - #CECECE</small>
                </div>
            </div>            
			<div class="row" style="height:10px;">&nbsp;</div> 
            <div class="row">
                <div class="col-md-2 labelCustom">Fila agrupación:</div>
                <div class="col-md-5">
                <div id="divRadioubicacion" class="radioValidate">
                <?php
					$c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("ubicacion", "ubicacion", 1, $grupo->ubicacion_impresion, "form-control number", 50, "", "", "");                
                ?>
                </div>         
                </div>
            </div>    
			<div class="row" style="height:10px;">&nbsp;</div> 
            <div class="row">
                <div class="col-md-2 labelCustom">Columna:</div>
                <div class="col-md-5">
                <div id="divRadioubicacion" class="radioValidate">
                <?php
					$c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("columna", "columna", 1, $grupo->columna, "form-control number", 50, "", "", "");                
                ?>
                </div>         
                </div>
            </div>       
            <div class="row" style="height:10px;">&nbsp;</div> 
            <div class="row">
                <div class="col-md-2 labelCustom">Activo:</div>
                <div class="col-md-3">
                <div id="divRadioactivo_grupo" class="radioValidate">
                <?php
                    $c_radio = new Radio;
                    $arrSiNo = array("1"=>"Si","2"=>"No");
                    $c_radio->Radio("activo_grupo","Activo",$arrSiNo,"", 1, $grupo->activo, "", 0, "customValidateRadio('activo_grupo');");
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
                <input type="button" value="Guardar" class="btn btn-primary datos_grupo_btnSave" onclick="saveGrupo();">
            </center> 
        </form>   
</div>

