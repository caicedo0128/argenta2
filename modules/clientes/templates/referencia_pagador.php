<script type="text/javascript">

$(document).ready(function(){

});

function saveReferenciaPagador(){

    validateForm("datosRegistroReferenciaPagador");

    if ($("#datosRegistroReferenciaPagador").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroReferenciaPagador"));

		$.ajax({
			type: 'POST',
			url: strUrl,
			dataType: "json",
			data: dataForm,
			mimeType: "multipart/form-data",
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				closeNotify();
				if (response.Success){
					showSuccess("Transacción exitosa. Espere por favor...");
					cargarReferenciaPagador();
				}
				else{
					showError(response.Message, 5000);
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }    
}



</script>
<style>
	.pagador{
		display:none;
	}
</style>
<div class="panel panel-primary">
    <div class="panel-body">
        Referencia pagador
        <div class="cerrar_form" onclick="cargarReferenciaPagador();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />

        <form id="datosRegistroReferenciaPagador" method="post" name="datosRegistroReferenciaPagador" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveReferenciaPagador" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
        <input type="hidden" name="id_ref_pagador" id="id_ref_pagador" value="<?=$idRefPagador?>" />
            <div class="row" style="height:10px;">&nbsp;</div> 
            <div class="row">
                <div class="col-md-2 labelCustom">Pagador:</div>
                <div class="col-md-10">
                <?php
                    $sede_select = new Select("id_pagador","id_pagador",$arrTodosPagadores,"",1,"", "form-control required", 0, "", "", 0);
                    $sede_select->enableBlankOption();
                    $sede_select->Default = $clientesRefPagador->id_pagador;
                    echo $sede_select->genCode();                    
                ?>             
                </div>
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div> 
            <div class="row">     
                <div class="col-md-2 labelCustom">% Descuento:</div>
                <div class="col-md-2">
                <?php 
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("porcentaje_descuento", "porcentaje_descuento", 1, $clientesRefPagador->porcentaje_descuento, "form-control number", 50, "7", "", "","","return IsNumber(event);");
                ?>           
                </div>
                <div class="col-md-1 labelCustom">% Factor:</div>
                <div class="col-md-2">
                <?php 
                    $c_textbox = new Textbox;
                    echo $c_textbox->Textbox ("factor", "factor", 1, $clientesRefPagador->factor, "form-control number", 50, "7", "", "","","return IsNumber(event);");
                ?>          
                </div>              
            </div> 
            <div class="row" style="height:10px;">&nbsp;</div>     
            <div class="row">
                <div class="col-md-2 labelCustom">Plazo(días):</div>
                <div class="col-md-6">
                    <div id="divRadiodias" class="radioValidate" style="width:auto;">
                    <?php
                        $c_radio = new Radio;
                        $arrDias = array("30"=>"30","60"=>"60","90"=>"90","120"=>"120");
                        $c_radio->Radio("dias","dias",$arrDias,"", 1, $clientesRefPagador->plazo, "", 0, "customValidateRadio('dias');");
                        while($tmp_html = $c_radio->next_entry()) {
                            echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                        }               
                    ?>  
                    </div>            
                </div>                  
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">              
                <div class="col-md-2 labelCustom">Observaciones:</div>
                <div class="col-md-10">
                <?php
                    $c_textarea = new Textarea;
                    echo $c_textarea->Textarea("observaciones_condiciones", "observaciones_condiciones", 1, "", "form-control", 60, 3);
                    echo $clientesRefPagador->observaciones;        
                ?>         
                </div>                    
            </div>     
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_cliente_btnSave" onclick="saveReferenciaPagador();">
            </center>            
    </div>        
</div>
