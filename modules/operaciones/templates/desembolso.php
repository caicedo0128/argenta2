<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_desembolso').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $("#id_tercero").attr("disabled","disabled");
});

function saveDesembolso(){

    validateForm("datosRegistroDesembolso");

    if ($("#datosRegistroDesembolso").valid()){

        enabledForm("datosRegistroDesembolso");
        showLoading("Enviando información. Espere por favor...");
        var dataForm = new FormData(document.getElementById("datosRegistroDesembolso"));

        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
				mimeType: "multipart/form-data",
				cache: false,
				contentType: false,
				processData: false,
                success: function (response) {
                    closeNotify();
                    showSuccess(response.Message);
                    if (response.Success) {
                        cargarDesembolsos();
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }
}

function validarOtroTercero(tipo, forzarCambio){

	$(".otros_tercero").hide();
	$(".tercero_registrado").hide();
	var idTercero = $("#id_tercero").val();
	var tercero = $("#tercero").val();
	var idDesembolso = $("#id_desembolso").val();
	var idTerceroBase = $("#id_tercero_base").val();

	if (forzarCambio){
		if (idTercero != "")
			tipo = 2;
		else if (tercero != "")
			tipo = 1;
	}

	//ES OTRO TERCERO
	if (tipo == "1"){
		$(".otros_tercero").show();
		$(".tercero_registrado").hide();
	}
	else if (tipo == "2"){
		$(".otros_tercero").hide();
		$(".tercero_registrado").show();
		if (!forzarCambio)
			$("#id_tercero").val(idTerceroBase);
	}
}

function validarDesembolso(){

	var vlrDisponible = parseInt($("#disponible").val());
	var vlrDesembolso = parseInt($("#valor").val());

	if (vlrDesembolso > vlrDisponible){
		showError("El valor de los desembolsos supera el valor del giro final. Verifique.");
		$("#valor").val("");
	}

}

</script>
<style>
	.otros_tercero{
		display:none;
	}
</style>
<div class="panel panel-primary">
    <div class="panel-body">
    	Registro de información de
			<?php
				if ((($operacion->estado == 3 || $operacion->estado == 6) && $idDesembolso == 0) || $desembolso->tipo_registro==1){
			?>
				<span class="label label-success" style="padding:5px">Desembolso</span>
			<?php
				}
			?>
			<?php
				$esRemanente = false;
				if ($operacion->estado == 1 && $idDesembolso == 0 || $desembolso->tipo_registro==2)
				{
					$esRemanente = true;
			?>
					<span class="label label-danger" style="padding:5px">Remanente</span>
			<?php
				}
			?>
        <div class="cerrar_form" onclick="cargarDesembolsos();" title="Regresar"><i class="fa fa-reply fa-lg"></i></div>
        <hr />
        <form id="datosRegistroDesembolso" method="post" name="datosRegistroDesembolso" action="admindex.php" enctype="multipart/form-data">
            <input type="hidden" name="Ajax" value="true" />
            <input type="hidden" name="mod" value="operaciones" />
            <input type="hidden" name="action" value="saveDesembolso" />
            <input type="hidden" name="id_desembolso" id="id_desembolso" value="<?=$idDesembolso?>" />
            <input type="hidden" name="id_operacion" id="id_operacion" value="<?=$idOperacion?>" />
            <input type="hidden" name="id_tercero_base" id="id_tercero_base" value="<?=$operacion->id_emisor?>" />
            <input type="hidden" name="id_tercero_mail" id="id_tercero_mail" value="<?=$operacion->id_emisor?>" />
            <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?=($operacion->estado == 1?2:1)?>" />
            <input type="hidden" name="disponible" id="disponible" value="<?=($disponible + $desembolso->valor)?>" />
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
            	<div class="col-md-2">
					Fecha desembolso:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("fecha_desembolso", "fecha_desembolso", 1, $desembolso->fecha_desembolso, "form-control required", 50, "", "", "","","");
				   ?>
				   </div>
				</div>
				<div class="col-md-2 labelCustom">
					Otro tercero?:
					<div class="">
						<div id="divRadiootro" class="radioValidate">
						<?php

							//DETERMINAMOS SI EL TERCERO ESTA REGISTRADO O NO
							$terceroRegistrado = 2;
							if (($desembolso->id_tercero == "" || $desembolso->id_tercero == null) && $idDesembolso != 0)
								$terceroRegistrado = 1;

							$c_radio = new Radio;
							$arrSiNo = array("1"=>"Si","2"=>"No");
							$c_radio->Radio("otro","otro",$arrSiNo,"", 0, $terceroRegistrado, "", 0, "customValidateRadio('otro');validarOtroTercero(this.value, false);");
							while($tmp_html = $c_radio->next_entry()) {
								echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
							}
						?>
						</div>
					</div>
                </div>
				<div class="col-md-4 tercero_registrado">
					Tercero:
					<div class="">
					<?php
						$idTercero = $operacion->id_emisor;
						if($desembolso->id_tercero != "")
							$idTercero = $desembolso->id_tercero;

						if ($desembolso->tercero != "")
							$idTercero = "";

						$sede_select = new Select("id_tercero","id_tercero",$arrTerceros,"",1,"", "form-control", 0, "", "", 0);
						$sede_select->enableBlankOption();
						$sede_select->Default = $idTercero;
						echo $sede_select->genCode();
					?>
					</div>
				</div>
				<div class="col-md-4 otros_tercero">
					Tercero:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("tercero", "tercero", 1, $desembolso->tercero, "form-control", 50, "", "", "","","");
					?>
					</div>
				</div>
				<div class="col-md-4 otros_tercero">
					OFAC:
					<div class="">
					<?php
						$requerido = 1;
						if ($desembolso->id_desembolso != 0 && $desembolso->archivo_ofac != "")
							$requerido = 0;

						$c_filebox = new FileBox;
						echo $c_filebox->Filebox ("file_ofac", "file_ofac", $requerido, $desembolso->archivo_ofac, "form-control", 30, "", "", "");
						if ($desembolso->archivo_ofac)
							echo "<a href='".$this->rutaArchivosDesembolsos."/".$desembolso->archivo_ofac."' target='_blank' title='Ver OFAC'>Ver OFAC</a>";
					?>
					</div>
				</div>
            </div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <div class="row">
                <div class="col-md-2 labelCustom">
					Banco:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("banco", "banco", 1, $desembolso->banco, "form-control", 50, "", "", "","","");
					?>
					</div>
                </div>
				<div class="col-md-2 labelCustom">
					Nro de cuenta:
					<div class="">
					<?php
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("nro_cuenta", "nro_cuenta", 1, $desembolso->nro_cuenta, "form-control", 50, "", "", "","","");
					?>
					</div>
                </div>
                <div class="col-md-2 labelCustom">
					Tipo cuenta?:
					<div class="">
						<div id="divRadiotipo_cuenta" class="radioValidate">
						<?php
							$c_radio = new Radio;
							$arrTipo = array("1"=>"Ahorros","2"=>"Corriente");
							$c_radio->Radio("tipo_cuenta","tipo_cuenta",$arrTipo,"", 1, $desembolso->tipo_cuenta, "", 0, "customValidateRadio('tipo_cuenta');");
							while($tmp_html = $c_radio->next_entry()) {
								echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
							}
						?>
						</div>
					</div>
                </div>
            </div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
				<div class="col-md-4 labelCustom">
					Soporte:
					<div class="">
					<?php
						$requerido = 1;
						if ($desembolso->id_desembolso != 0)
							$requerido = 0;

						$c_filebox = new FileBox;
						echo $c_filebox->Filebox ("file_desembolso", "file_desembolso", $requerido, $desembolso->archivo_desembolso, "form-control", 30, "", "", "");
						if ($desembolso->archivo_desembolso)
							echo "<a href='".$this->rutaArchivosDesembolsos."/".$desembolso->archivo_desembolso."' target='_blank' title='Ver soporte'>Ver soporte</a>";
					?>
					</div>
				</div>
                <div class="col-md-2 labelCustom">
               		Valor:
					<div class="">
					<?php
						$funcionValidar = "";
						if ((($operacion->estado == 3 || $operacion->estado == 6)  && $idDesembolso == 0) || $desembolso->tipo_registro==1)
							$funcionValidar = "validarDesembolso();";

						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("valor", "valor", 1, $desembolso->valor, "form-control number", 50, "", $funcionValidar, "","","return IsNumber(event);","");
					?>
					<?php
						if ((($operacion->estado == 3 || $operacion->estado == 6) && $idDesembolso == 0) || $desembolso->tipo_registro==1)
						{
					?>
						<small>Restante para desembolso: <?=formato_moneda($disponible + $desembolso->valor)?></small>
					<?php
						}
					?>
					</div>
                </div>
                <?php
                	if ($esRemanente){
                ?>
					<div class="col-md-2 labelCustom">
						Id Reliquidación:
						<div class="">
						<?php
							$sede_select = new Select("id_reliquidacion","id_reliquidacion",$arrReliquidacion,"",1,"", "form-control", 0, "", "", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = $desembolso->id_reliquidacion;
							echo $sede_select->genCode();
						?>
						</div>
					</div>
				<?php
					}
				?>
			</div>
            <div class="row" style="height:10px;">&nbsp;</div>
            <center>
                <input type="button" value="Guardar" class="btn btn-primary datos_desembolso_btnSave" onclick="saveDesembolso();">
            </center>

        </form>
</div>
<?php
    if ($idDesembolso != 0){
?>
    <script>
        $(document).ready(function () {
            validarOtroTercero(0, true);
        });
    </script>
<?php
}
?>
