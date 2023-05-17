<script type="text/javascript">

$(document).ready(function(){

});

function saveMotivoRechazo(){

    validateForm("datosRegistroMotivoRechazo");

    if ($("#datosRegistroMotivoRechazo").valid()){

        showLoading("Enviando informacion. Espere por favor...");

        var strUrl = "admindex.php";
		var dataForm = new FormData(document.getElementById("datosRegistroMotivoRechazo"));

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
					loader();
					$("#content_clientes").load('admindex.php', { Ajax:true, id_cliente: response.IdCliente, mod: 'clientes', action:'client'}, function () {
						loader();
					});
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
        <form id="datosRegistroMotivoRechazo" method="post" name="datosRegistroMotivoRechazo" action="admindex.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="Ajax" id="Ajax" value="true" />
        <input type="hidden" name="mod" id="mod" value="clientes" />
        <input type="hidden" name="action" id="action" value="saveRechazo" />
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?=$idCliente?>" />
			<div class="row">
				<div class="col-md-6">
					Motivo/Rechazo:
					<div>
						<?php
							$sede_select = new Select("id_motivo_rechazo","id_motivo_rechazo",$arrMotivos,"",1,"", "form-control", 0, "", "", 0);
							$sede_select->enableBlankOption();
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
						echo $c_textarea->Textarea("observaciones_rechazo", "observaciones_rechazo", 1, "", "form-control", 60, 3);
					?>         
					</div>
				</div>                     
			</div>     
            </form>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_tarea_btnSave" onclick="saveMotivoRechazo();">
            </center>            
    </div>        
</div>

