<?php
    $requerido = 1;
    if ($campoClass->id_campo)
        $requerido = 0;
    
?> 

<script>
    function activarTipo(idTipo){
    
        $(".tipo_1").hide();
        $(".tipo_2").hide();        
        $(".tipo_4").hide();  
        
        if (idTipo == 2){
            $("#valores").val("");
            $(".tipo_2").show();        
        }
        
        if (idTipo == 1 || idTipo == 3){
            $(".tipo_1").show();        
        }
        
        if (idTipo == 4){
            $(".tipo_4").show();           
        }        
    }
    
function saveFields(){

    validateForm("datosRegistro");

    if ($("#datosRegistro").valid()){

        showLoading("Enviando informacion. Espere por favor...");
        
        var strUrl = "admindex.php";
        var dataForm = "Ajax=true&mod=fieldsDynamic&action=saveField&" + $("#datosRegistro").serialize();
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    showSuccess("Transacción exitosa. Espere por favor...");
                    if (response.Success) {
                        cargarCampos();
                    }
                }
        });
    }
    else{
        showError("Por favor revise los campos marcados.");
    }    
}    

function agregarCampo() {

    var TextSelected = "[\"" + $("#campo_formula option:selected").text() + "\"]";
    if (TextSelected != "[Seleccione uno...]") {
        $("#formula").val($("#formula").val() + TextSelected);
        return false;
    }
}
    
</script>

<input type="hidden" id="required" value="<?=$requerido?>" />
<div class="panel panel-primary col-md-offset-2 col-md-8">
    <div class="panel-body">
        Registro de campos
        <div class="cerrar_form" onclick="cargarCampos();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />

        <form id="datosRegistro" method="post" name="datosRegistro" action="index.php">
        <input type="hidden" name="id_campo" id="id_campo" value="<?=$campoClass->id_campo?>" />
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Campo:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("campo", "Campo", 1, "$campoClass->campo", "form-control", 50, "", "", "");
                ?>   
                </div>  
                <div class="col-md-2 labelCustom">Título HTML/Impresión:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("texto_imprimir", "texto_imprimir", 1, "$campoClass->texto_imprimir", "form-control no_mayus", 50, "", "", "");
                ?>   
                </div>                  
            </div>  
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Tipo de campo:</div>
                <div class="col-md-4">
                <?php                       
                    $c_select = new Select("tipo_campo","Tipo campo", $this->arrTipos,"",1,$campoClass->tipo_campo,"form-control","","","activarTipo(this.value);");                       
                    $c_select->enableBlankOption("Seleccione uno...");
                    echo $c_select->genCode();

                ?>  
                </div>                    
            </div>  
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Formato impresión:</div>
                <div class="col-md-4">
                <?php
                	$arrFormatoImpresion = array("$"=>"Moneda","Por100"=>"% * 100", "Entre100"=>"% / 100", "%"=>"Solo %");
 					$c_select = new Select("formato_imprimir","formato_imprimir", $arrFormatoImpresion,"",0,$campoClass->formato_imprimir,"form-control","","","");                       
                    $c_select->enableBlankOption("Sin formato");
                    echo $c_select->genCode();                	
                ?>   
                </div>   
                <div class="col-md-2 labelCustom">Decimales impresión:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("cantidad_decimales", "cantidad_decimales", 0, "$campoClass->cantidad_decimales", "form-control no_mayus", 50, "", "", "");
                ?>   
                <small>Ejm. 1 o 2 o 3 o 4</small>
                </div>   
			</div>                
            <div class="row tipo_1" style="height:10px;">&nbsp;</div>    
            <div class="row tipo_1" style="display:none;">            
                <div class="col-md-2 labelCustom">Valores:</div>
                <div class="col-md-10">
                <?php
                    $c_textarea = new Textarea;
                    echo $c_textarea->Textarea ("valores", "Valores", 0, "$campoClass->valores", "form-control", 40, 5, "", "");
                ?>
                <br/>
                <b>Separe con punto y coma(;) si desea dar varias opciones de selección.
                <br/>
                Ejm: CEMENTO;PORCELANATO;BALDOSA;ALFOMBRA
                </b>
                </div>                 
            </div>
            <div class="row tipo_1" style="height:10px;">&nbsp;</div>    
            <div class="row tipo_1" style="display:none;">            
                <div class="col-md-2 labelCustom">Valores:</div>
                <div class="col-md-10">
                <?php
                    $c_textarea = new Textarea;
                    echo $c_textarea->Textarea ("valores", "Valores", 0, "$campoClass->valores", "form-control", 40, 5, "", "");
                ?>
                <br/>
                <b>Separe con punto y coma(;) si desea dar varias opciones de selección.
                <br/>
                Ejm: CEMENTO;PORCELANATO;BALDOSA;ALFOMBRA
                </b>
                </div>                 
            </div>            
            <div class="row tipo_2" style="height:10px;">&nbsp;</div>    
            <div class="row tipo_2" style="display:none;">            
                <div class="col-md-2 labelCustom">Valor permitido:</div>
                <div class="col-md-4">
                <div id="divRadiotipo_abierto" class="radioValidate">
                <?php
					$c_radioVisible = new Radio;
                    $arrTipoCampoAbierto = array("texto"=>"Alfanumerico","number"=>"Número");
                    $c_radioVisible->Radio("tipo_abierto","tipo_abierto",$arrTipoCampoAbierto,"", 1, $campoClass->tipo_abierto, "", 0, "customValidateRadio('tipo_abierto');");
                    while($tmp_html = $c_radioVisible->next_entry()) {
                        echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    }                
               	?>
               	</div>
                </div>                 
            </div>
			<div class="row tipo_4" style="height:10px;">&nbsp;</div>    
            <div class="row tipo_4" style="display:none;">            
                <div class="col-md-2 labelCustom">Formula:</div>
                <div class="col-md-10">
                <?php
                    $campo_select = new Select("campo_formula","campo formula",$arrCampos,"",0,"", "form-control", 0, "", "", 0);
                    $campo_select->enableBlankOption();
                    echo $campo_select->genCode();
                    echo "<br/><input type='button' value='Agregar a la formula' onclick='agregarCampo();' class='btn btn-primary btn-sm'><br/><br/>";
                    $c_textarea = new Textarea;
                    echo $c_textarea->Textarea ("formula", "formula", 1, "$campoClass->formula", "form-control no_mayus", 40, 5, "", "");
                ?>
                </div>                 
            </div>            
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Texto ayuda:</div>
                <div class="col-md-10">
                <?php
                    $c_textarea = new Textarea;
                    echo $c_textarea->Textarea ("texto_ayuda", "texto_ayuda", 0, "$campoClass->texto_ayuda", "form-control no_mayus", 40, 5, "", "");
                ?>
                </div>                 
            </div>              
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Visible:</div>
                <div class="col-md-4">
                <div id="divRadiovisible" class="radioValidate">
                <?php
                    $c_radioVisible = new Radio;
                    $arrSiNo = array("1"=>"Si","2"=>"No");
                    $c_radioVisible->Radio("visible","Visible",$arrSiNo,"", 1, $campoClass->campo_oculto, "", 0, "customValidateRadio('visible');");
                    while($tmp_html = $c_radioVisible->next_entry()) {
                        echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    }
                ?>
                </div>
                </div>                 
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Nivel ejecución:</div>
                <div class="col-md-4">
                <?php                       
                    $c_select = new Select("nivel_ejecucion","Nivel ejecucion", $this->arrNivelEjecucion,"",0,$campoClass->nivel_ejecucion,"form-control","","","");                       
                    $c_select->enableBlankOption("No aplica o primero");
                    echo $c_select->genCode();

                ?> 
                </div>                 
            </div>              
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Activo:</div>
                <div class="col-md-4">
                <div id="divRadioactivo" class="radioValidate">
                <?php
                    $c_radioVisible = new Radio;
                    $c_radioVisible->Radio("activo","Visible",$arrSiNo,"", 1, $campoClass->activo, "", 0, "customValidateRadio('activo');");
                    while($tmp_html = $c_radioVisible->next_entry()) {
                        echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    }
                ?>
                </div>
                </div>                 
            </div>              
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_campos_btnSave" onclick="saveFields();">
            </center>   
    </div>            
</div>
<script>
    $(document).ready(function (){
        activarTipo(<?=$campoClass->tipo_campo?>);
    });
</script>