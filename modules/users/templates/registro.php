<script>

$(document).ready(function(){   
    $("#id_tercero").select2({ placeholder: 'Seleccione uno...',allowClear: true}); 
});

function saveUser(isAdmin){

    validateForm("datosRegistro");

    if ($("#datosRegistro").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "index.php";
        if (isAdmin)
            strUrl = "admindex.php";

        var dataForm = "Ajax=true&mod=users&action=saveUser&" + $("#datosRegistro").serialize();
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    showSuccess("Transacción exitosa. Espere por favor...");
                    if (response.Success) {
                        if (response.IsAdmin)
                            cargarUsuarios();
                        else{
                            setTimeout(function (){
                                window.location.href="index.php?mod=users&action=login";
                            },3000);
                        }
                    }
                }
        });
    }
    else{
        showError("Debes ingresar todos los campos marcados.");
        closeNotify();
    }
}

</script>

<div class="panel panel-primary col-md-offset-2 col-md-9">
    <div class="panel-body">
        Registro de usuarios
        <div class="cerrar_form" onclick="cargarUsuarios();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />

            <form id="datosRegistro" method="post" name="datosRegistro" action="index.php">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$this->id_usuario?>" />
            <div class="row">
                <div class="col-md-2 labelCustom">Nombres:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("nombres", "Nombres", 1, "$this->nombres", "form-control", 30, "", "", "");   
                ?>                    
                </div>
                <div class="col-md-2 labelCustom">Apellidos:</div>
                <div class="col-md-4">
                <?php
                    echo $c_textbox->Textbox ("apellidos", "Apellidos", 1, "$this->apellidos", "form-control", 30, "", "", "");
                ?>                
                </div>
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">Documento:</div>
                <div class="col-md-4">
                  <?php
                    echo $c_textbox->Textbox ("documento", "Documento", 1, $this->identificacion, "form-control", 30, "", "", "");
                ?>                   
                </div>
                <div class="col-md-2 labelCustom">Usuario - E-mail:</div>
                <div class="col-md-4">
                <?php
                    echo $c_textbox->Textbox ("correo_personal", "Usuario", 1, $this->correo_personal, "form-control email", 30, "", "", "");
                ?>           
                </div>
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div>   
            <div class="row">
                <div class="col-md-2 labelCustom">Tel&eacute;fono:</div>
                <div class="col-md-4">
                <?php
                    echo $c_textbox->Textbox ("telefono", "Teléfono", 1, $this->telefono_fijo, "form-control", 30, "", "", "");
                ?>                 
                </div>
                <div class="col-md-2 labelCustom">Celular:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new TextBox;
                    echo $c_textbox->Textbox ("celular", "Celular", 0, $this->telefono_celular, "form-control", 30, "", "", "");
                ?>         
                </div>
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">
                <div class="col-md-2 labelCustom">Dirección:</div>
                <div class="col-md-4">
                <?php
                    echo $c_textbox->Textbox ("direccion", "Direccion", 0, $this->direccion, "form-control", 50, "", "", "");
                ?>                
                </div>
                <div class="col-md-2 labelCustom">Cargo:</div>
                <div class="col-md-4">
                <?php
                    echo $c_textbox->Textbox ("cargo", "cargo", 0, $this->cargo, "form-control", 10, "", "", "");
                ?>                
                </div>                
            </div>  
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Contrase&ntilde;a:</div>
                <div class="col-md-4">
                <?php
                    if(!$this->id_usuario)
                        $requerido = 1;
                    else
                        $requerido = 0;
                        
                    $c_textbox = new Password;
                    echo $c_textbox->Textbox ("contrasegna", "clave", $requerido, "", "form-control", 30, "", "", "");
                ?>       
                </div>
                <div class="col-md-2 labelCustom">Confirmar contrase&ntilde;a:</div>
                <div class="col-md-4">
                <?php
                    $c_textbox = new PasswordConfirm("contrasegna_confirm", "confirmar contraseña", "contrasegna", $requerido, "", "form-control", 30, "", "", "");
                    echo $c_textbox->genCode();
                ?>  
                </div>                
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Tercero:</div>
                <div class="col-md-4">
                <?php

                    $sede_select = new Select("id_tercero","Tercero",$arrTerceros,"",1,"", "form-control", 0, "", "", 0);
                    $sede_select->enableBlankOption();
                    $sede_select->Default = $this->id_tercero;
                    echo $sede_select->genCode();
                ?>    
                </div>             
            </div>             
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">            
                <div class="col-md-2 labelCustom">Perfíl:</div>
                <div class="col-md-4">
                <?php

                    $sede_select = new Select("id_perfil","Perfil",$arrPerfiles,"",1,"", "form-control", 0, "", "", 0);
                    $sede_select->enableBlankOption();
                    $sede_select->Default = $this->id_perfil;
                    echo $sede_select->genCode();
                ?>    
                </div>
                <div class="col-md-2 labelCustom">Activo:</div>
                <div class="col-md-4">
                    <div id="divRadioActivo" class="radioValidate">
                    <?php
                        $c_radio = new Radio;
                        $arrSiNo = array("1"=>"Si","2"=>"No");
                        $c_radio->Radio("activo","Activo",$arrSiNo,"", 1, $this->activo, "", 0, "customValidateRadio('Activo');");
                        while($tmp_html = $c_radio->next_entry()) {
                            echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                        }
                    ?>
                    </div>
                </div>                
            </div>                 
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_aplicacion_btnSave" onclick="saveUser(true);">
            </center>            
    </div>        
</div>