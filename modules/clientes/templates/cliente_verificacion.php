<script type="text/javascript">

$(document).ready(function(){
	$('#fecha_consulta').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveVerificacion(){

    validateForm("datosRegistroVerificacion");

    if ($("#datosRegistroVerificacion").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroVerificacion"));

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
					closeBootbox();
					window.setTimeout(function(){
						loader();
						$("#content_clientes").load('admindex.php', { Ajax:true, id_cliente: response.IdCliente, mod: 'clientes', action:'client'}, function () {
							loader();
						});
					},1000);
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

</style>

<div class="panel panel-primary" style="padding-right:0px !important;padding-left:0px !important;">
    <div class="panel-body">
        <form id="datosRegistroVerificacion" method="post" name="datosRegistroVerificacion" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveVerificacion" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
        <input type="hidden" name="id_tipo_verificacion" id="id_tipo_verificacion" value="<?=$tipoVerificacion?>" />
			<div class="row">
				<div class="col-md-4">
				<?php
					$descripcion = "";
					if ($tipoVerificacion == 1)
						$descripcion = "listas SAGRILAFT";
					else if ($tipoVerificacion == 2)
						$descripcion = "conocimiento de cliente";
					else if ($tipoVerificacion == 3)
						$descripcion = "conocimiento de operación";	
				?>
				<span class="label label-info">Verificación de <?=$descripcion?></span>
				</div>				 	
			</div>        
			<div class="row" style="height:10px;">&nbsp;</div>    			
			<div class="row">
				<div class="col-md-4">
					Fecha verificación:
					<div class="form-control"><?=($verificacion->fecha_verificacion != "" ?$verificacion->fecha_verificacion:date("Y-m-d"))?></div>
				</div>		
				<div class="col-md-4">
					Fecha consulta:
					<div>
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_consulta", "fecha_consulta", 1, $verificacion->fecha_consulta, "form-control", 30, "", "", "");
					?>  		
					</div>
				</div>					
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>    			
			<div class="row">
				<div class="col-md-4">
					Verifica:
					<div id="divRadioverifica" class="radioValidate" style="width:auto;">
					<?php
						$c_radio = new Radio;
						$arrSN = array("1"=>"Si","2"=>"No");
						$c_radio->Radio("verifica","verifica",$arrSN,"", 1, $verificacion->valor_verificacion, "", 0, "customValidateRadio('verifica');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div> 
				</div>		
				<div class="col-md-8">
					Usuario verifica:
					<div>
						<?php
							$sede_select = new Select("id_usuario_verifica","id_usuario_verifica",$arrUsuariosEmpleados,"",1,"", "form-control", 0, "", "", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = ($verificacion->id_usuario_verifica != "" ?$verificacion->id_usuario_verifica:$_SESSION["id_user"]);
							echo $sede_select->genCode();
						?> 			
					</div>
				</div>					
			</div>			
			<div class="row" style="height:10px;">&nbsp;</div>    
			<div class="row">              				
				<div class="col-md-12">
					Observaciones:
					<div class="">
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones", "observaciones", 1, "", "form-control", 60, 3);
						echo "<a href=\"javascript:;\" onclick=\"$('#txt_observaciones').toggle();\" title='Ver/Ocultar historial'><small>Ver/Ocultar historial</small></a>";
						echo "<div id='txt_observaciones' style='display:none;'>".$verificacion->observaciones."</div>";        						
					?>         
					</div>
				</div>                     
			</div>     
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_tarea_btnSave" onclick="saveVerificacion();">
            </center>            
    </div>        
</div>

