<script type="text/javascript">

$(document).ready(function(){
	$('#corte_eeff').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveEstudio(){

    validateForm("datosEstudio");

    if ($("#datosEstudio").valid()){

        showLoading("Enviando información. Espere por favor...");
        var dataForm = "Ajax=true&" + $("#datosEstudio").serialize();
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
                        editEstudio(response.IdEstudio);
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }       
}

function cargarCamposModelo(idModelo, idEstudio){
    
    if (idModelo != ""){
        $("#campos_modelo").html("Cargando campos modelo...");
        $("#campos_modelo").load('admindex.php', { Ajax:true, mod: 'modelos', action:'camposModelo', id_modelo : idModelo, id_estudio:idEstudio}, function () {
        });
    }
}

function verObservaciones(){
    $("#ver_observaciones").toggle();        
}

function saveCupo(){
	
	var cupo = $("#cupo").val();
	var observaciones = $("#observaciones").val();
	
	if (cupo != "" && observaciones != ""){
		
		cupo = parseFloat(cupo);
		
		if (cupo > 0){
		
			bootbox.confirm({
				title: "Confirmación",
				message: "Usted va aprobar el cupo para este tercero.<br/><br/>Realmente desea continuar?",
				closeButton: true,
				buttons: {
					confirm: {
						label: 'Si',
						className: 'btn-primary'
					},
					cancel: {
						label: 'No',
						className: 'btn-danger'
					}
				},
				callback: function (result) {


					if (result === null) {
						closeBootbox();
						return;
					}
					else if (result){  

						showLoading("Enviando informaciÓn. Espere por favor...");
						var idEstudio = $("#id_estudio").val();
						var strUrl = "admindex.php";
						var dataForm = "Ajax=true&mod=estudioRiesgo&action=saveCupo&id_estudio=" + idEstudio
						$.ajax({
								type: 'POST',
								url: strUrl,
								dataType: "json",
								data:{
									Ajax:true,
									mod:'estudioRiesgo',
									action:'saveCupo',
									id_estudio:idEstudio,
									observaciones:observaciones,
									cupo:cupo
								},
								success: function (response) {
									closeNotify();
									showSuccess("Transacción exitosa. Espere por favor...");
									cargarEstudios();
									goObjHtml("content_general", 70,1000);
								}
						});					
					}
				}
			});
			$(".bootbox-prompt").addClass("show").show();
		}
		else
			showError("El cupo aprobado debe ser mayor a cero. Verifique");
	}
	else
		showError("Los campo cupo y observaciones son obligatorios. Verifique");
}

</script>
<div class="panel panel-primary col-md-offset-2 col-md-8">
    <div class="panel-body">
        Registro de estudio de riesgo
        <div class="cerrar_form" onclick="cargarEstudios();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosEstudio" method="post" name="datosEstudio" action="admindex.php" enctype="multipart/form-data">        
            <input type="hidden" name="mod" value="estudioRiesgo" />
            <input type="hidden" name="action" value="saveEstudio" />
            <input type="hidden" name="id_estudio" id="id_estudio" value="<?=$idEstudio?>" />
            <input type="hidden" name="id_tercero" id="id_tercero" value="<?=$idTercero?>" />            
            <div class="row">            
                <div class="col-md-2 labelCustom">Fecha registro:</div>
                <div class="col-md-3"><?=$estudio->fecha?></div>  
				<div class="col-md-2 labelCustom">Corte EEFF:</div>
				<div class="col-md-2">
				<?php 
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("corte_eeff", "corte_eeff", 1, $estudio->corte_eeff, "form-control", 50, "", "", "","","");
				?>           
				</div>                
            </div>                
            <div class="row" style="height:10px;">&nbsp;</div>    
            <div class="row">
                <div class="col-md-2 labelCustom">Año:</div>
                <div class="col-md-3"> 
                <?php
                    $selectData = new Select("anio","nombre",$appObj->paramGral["arrAnios"],"",1,"", "form-control", 0, "", "", 0);
                    $selectData->enableBlankOption();
                    $selectData->Default = $estudio->anio;
                    echo $selectData->genCode();
                ?>                  
                </div>
                <div class="col-md-2 labelCustom">Modelo:</div>
                <div class="col-md-5"> 
                <?php

                    $selectData = new Select("id_modelo","nombre",$arrModelos,"",1,"", "form-control", 0, "", "cargarCamposModelo(this.value, 0);", 0);
                    $selectData->enableBlankOption();
                    $selectData->Default = $estudio->id_modelo;
                    echo $selectData->genCode();
                ?>                 
                </div>                
            </div>   
            <div class="row" style="height:10px;">&nbsp;</div>                             
            <div class="row" id="campos_modelo">
				Seleccione el modelo que va aplicar.
            </div> 
			<div class="alert alert-success">Condiciones de aprobación.</div> 
            <div class="row">
				<div class="col-md-3 labelCustom">
					Cupo:
					<div class="">
					<?php 
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("cupo", "cupo", 0, "", "form-control number", 50, "11", "", "","","return IsNumber(event);");
						echo "<small>Último cupo aprobado:<br/>".formato_moneda($estudio->cupo)."</small>";
					?>           
					</div>            
				</div>
                <div class="col-md-9 labelCustom">
					Observaciones:
					<div class=""> 
					<?php
						$c_textarea = new Textarea;
						echo $c_textarea->Textarea("observaciones", "", 1, "", "form-control", "", "", "", "");   
					?>    
					<a href="javascript:verObservaciones();"><i>Ver observaciones</i></a>
					<div id="ver_observaciones" style="display:none;">
						<?=$estudio->observaciones?> 
					</div>
                	</div>
                </div>				
            </div>            
            <div class="row" style="height:10px;">&nbsp;</div>             
            <div class="row">

            </div>            
            <div class="row" style="height:10px;">&nbsp;</div> 
        </form> 
        <center>
            <input type="button" value="Guardar" class="btn btn-primary datos_estudio_btnSave" onclick="saveEstudio();">
            <input type="button" value="Aprobar cupo" class="btn btn-success datos_estudio_btnSave" onclick="saveCupo();">
        </center> 
    </div>        
</div>
<?php
    if ($idEstudio != 0){
?>
    <script>
        $(document).ready(function () {
            cargarCamposModelo(<?=$estudio->id_modelo?>, <?=$idEstudio?>);
        });
    </script>
<?php
}
?>

