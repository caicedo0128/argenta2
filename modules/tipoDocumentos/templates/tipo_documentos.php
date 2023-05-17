<script type="text/javascript">

function saveDocumentos(){

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
                        window.location.href='admindex.php?mod=tipoDocumentos&action=listTipoDocumentos';
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }        
}

function cargarDocumentos() {
    loader();
    $("#content_page").load('admindex.php', { Ajax:true, mod: 'tipoDocumentos', action:'listTipoDocumentos'}, function () {
        loader();
    });
}

</script>

<div class="panel panel-primary col-md-offset-2 col-md-8">
    <div class="panel-body">
        Registro de tipo comunicados
        <div class="cerrar_form" onclick="cargarDocumentos();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
            <input type="hidden" name="mod" value="tipoDocumentos" />
            <input type="hidden" name="action" value="saveDocumentos" />
            <input type="hidden" name="id_tipo_documento" id="id_tipo_documento" value="<?=$idDocumento?>" />
            <div class="row" style="height:10px;">&nbsp;</div>     
            <div class="row">
                <div class="col-md-6">
					Tipo de comunicado:
					<div>
					<?php 
						$c_textbox = new Textbox;   
						echo $c_textbox->Textbox ("tipo_documento", "tipo_documento", 1, $tipo_documento->tipo_documento, "form-control", 50, "", "", "");
					?>           
					</div>
                </div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>  
			<div class="row">
                <div class="col-md-2">
                Emisor:
                <div id="divRadioemisor" class="radioValidate" style="width:auto;">
                <?php
                    $c_radio = new Radio;
                    $arrSN = array("1"=>"Si","2"=>"No");
                    $c_radio->Radio("emisor","Tipo",$arrSN,"", 1, $tipo_documento->emisor, "", 0, "customValidateRadio('emisor');");
                    while($tmp_html = $c_radio->next_entry()) {
                        echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    }
                ?>
                </div>                         
                </div>
                <div class="col-md-2">
                Pagador:
                <div id="divRadiopagador" class="radioValidate" style="width:auto;">
                <?php
                    $c_radio = new Radio;
                    $c_radio->Radio("pagador","Tipo",$arrSN,"", 1, $tipo_documento->pagador, "", 0, "customValidateRadio('pagador');");
                    while($tmp_html = $c_radio->next_entry()) {
                        echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    }
                ?>
                </div>                         
                </div>
                <div class="col-md-2">
                Comercial:
                <div id="divRadiocomercial" class="radioValidate" style="width:auto;">
                <?php
                    $c_radio = new Radio;
                    $c_radio->Radio("comercial","Tipo",$arrSN,"", 1, $tipo_documento->comercial, "", 0, "customValidateRadio('comercial');");
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
                <input type="button" value="Guardar" class="btn btn-primary datos_modelo_btnSave" onclick="saveDocumentos();">
            </center> 
        </form>       
</div>