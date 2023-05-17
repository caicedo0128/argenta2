<script type="text/javascript">

$(document).ready(function(){
	$('#fecha_inicial').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
	$('#fecha_final').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveAceptacion(){

    validateForm("datosRegistroAceptacion");

    if ($("#datosRegistroAceptacion").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroAceptacion"));

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
					cargarInfoAceptacion();
				}
			}
		});
    }
    else {
        showError("Por favor revise los campos marcados.");
    }    
}

function validarAceptacionEspecifica(validacionEspecifica){

	$(".confirma_valores").hide();
	if (validacionEspecifica == 1){
		$(".confirma_valores").show();
	}
		

}

</script>
<style>
	.pagador{
		display:none;
	}

    .select_multiple{
        height:250px !important;
    }	
	
</style>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de parámetros del tercero</h4>
    </div>
</div>
<div class="row col-md-12" style="height:10px;">&nbsp;</div>
<div class="panel panel-primary col-md-offset-2 col-md-9" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        Registro de información de aceptación
        <hr/>
        <form id="datosRegistroAceptacion" method="post" name="datosRegistroAceptacion" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveAceptaciones" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
			<div class="row">     				
				<div class="col-md-4">
            		<div class="">Emite carta/correo de aceptación general:</div>
					<div id="divRadioaceptacion_general" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrSINO = array("1"=>"Si","2"=>"No");
						$c_radio->Radio("aceptacion_general","aceptacion_general",$arrSINO,"", 1, $adicional->aceptacion_general, "", 0, "customValidateRadio('aceptacion_general');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}               
					?>  
					</div>               
				</div>
				
				<div class="col-md-4">
            		<div class="">Emite carta/correo de aceptación específica:</div>
					<div id="divRadioaceptacion_especifica" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("aceptacion_especifica","aceptacion_especifica",$arrSINO,"", 1, $adicional->aceptacion_especifica, "", 0, "customValidateRadio('aceptacion_especifica');validarAceptacionEspecifica(this.value);");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}               
					?>  
					</div>               
				</div>	
				<div class="col-md-3 confirma_valores">
					<div class="">Confirma valores/fecha:</div>
					<div id="divRadioconfirma_valores" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("confirma_valores","confirma_valores",$arrSINO,"", 1, $adicional->confirma_valores, "", 0, "customValidateRadio('confirma_valores');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}               
					?>          
					</div>
				</div> 	            			
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>    
			<div class="row">              				
				<div class="col-md-8">
					<div class="">Correo electrónico validación:</div>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox("correo_validacion", "correo_validacion", 1, $adicional->correo_validacion, "form-control email", "", "");
					?>         
				</div>                    
			</div>  			
			<div class="row" style="height:10px;">&nbsp;</div>    
			<div class="row">              				
				<div class="col-md-8">
					<div class="">Observaciones:</div>
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones_aceptacion", "observaciones_aceptacion", 1, "", "form-control", 60, 3);
						echo "<a href=\"javascript:;\" onclick=\"$('#txt_observaciones').toggle();\" title='Ver/Ocultar historial'><small>Ver/Ocultar historial</small></a>";
						echo "<div id='txt_observaciones' style='display:none;'>".$adicional->observaciones_aceptacion."</div>";        
					?>         
				</div>                    
			</div>     
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_cliente_btnSave" onclick="saveAceptacion();">
            </center>            
    </div>        
</div>
<?php
    if ($adicional->aceptacion_especifica != 0){
?>
    <script>
        $(document).ready(function (){
             validarAceptacionEspecifica(<?=$adicional->aceptacion_especifica?>);
        });    
    </script>
<?php
    }
?>
