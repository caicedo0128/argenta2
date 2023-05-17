<script type="text/javascript">

$(document).ready(function(){
    $('#fecha_pago_pactada_abono').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    $('#fecha_real_pago_abono').datetimepicker({ format: 'YYYY-MM-DD', showClear: true }).on('dp.change', function (e) {
        calcularTasaAbono();
    });
    $('#fecha_real_pago_abono').data('DateTimePicker').minDate(moment('<?=$ultimaReliquidacionPP->fecha_real_pago?>', 'YYYY-MM-DD'));
    $('#fecha_movimiento_abono').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
});

function saveReliquidacionPPAbono(){

    validateForm("datosRegistroReliquidacionAbono");

    if ($("#datosRegistroReliquidacionAbono").valid()){

        enabledForm("datosRegistroReliquidacionAbono");
        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&" + $("#datosRegistroReliquidacionAbono").serialize();
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
                        var idReliquidacion = $("#id_reliquidacion_abono").val();
                        $('#btnClosemodalAbono').click();
                        window.setTimeout(function(){
                            cargarTipoReliquidacion(idReliquidacion,'PPA');
                        },500);
                    }
                }
        });
    }
    else {
        showError("Por favor revise los campos marcados.");
    }

}

function calcularTasaAbono(){

}

</script>

<style>
    .devoluciones_abono{
        display:none;
    }
</style>

<div class="container-fluid">
<?php
	//ESTE ES UN AJUSTE TEMPORAL POR EL CAMBIO DE CALCULOS EN LAS RELIQUIDACIONES
	$functionCalculos = "calcularReliquidacionPPAbono";
	if ($operacion->fecha_operacion < "2019-01-01"){
		$functionCalculos = "calcularReliquidacionPPAbonoAnterior";
	}		
?>
<form id="datosRegistroReliquidacionAbono" method="post" name="datosRegistroReliquidacionAbono" action="admindex.php" enctype="multipart/form-data">
    <input type="hidden" name="mod" value="reliquidaciones" />
    <input type="hidden" name="action" value="saveReliquidacionPPAbono" />
    <input type="hidden" name="id_reliquidacion_pp" id="id_reliquidacion_pp" value="<?=$idReliquidacionPP?>" />
    <input type="hidden" name="id_reliquidacion_abono" id="id_reliquidacion_abono" value="<?=$idReliquidacion?>" />
    <input type="hidden" name="id_operacion_abono" id="id_operacion_abono" value="<?=$idOperacion?>" />
    <input type="hidden" name="intereses_mora_abono" id="intereses_mora_abono" value="<?=$reliquidacionPP->intereses_mora?>">
    <input type="hidden" name="fecha_real_pago_operacion" id="fecha_real_pago_operacion" value="<?=$operacion->fecha_pago_operacion?>">
    <input type="hidden" name="tasa_operacion" id="tasa_operacion" value="<?=$operacion->tasa_inversionista?>">
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Fecha operación:</div>
        <div class="col-md-4">
        <?=$operacion->fecha_operacion?>
        <input type="hidden" name="fecha_desembolso_abono" id="fecha_desembolso_abono" value="<?=$operacion->fecha_operacion?>">
        </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Fecha pago pactada:</div>
        <div class="col-md-4">
        <input type="hidden" name="fecha_ultimo_pago_abono" id="fecha_ultimo_pago_abono" value="<?=$ultimaReliquidacionPP->fecha_real_pago?>">
        <input type="hidden" name="fecha_pago_pactada_abono" id="fecha_pago_pactada_abono" value="<?=$ultimaReliquidacionPP->fecha_pago_pactada?>">
        <?=$ultimaReliquidacionPP->fecha_pago_pactada?>
        </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Fecha utlimo abono:</div>
        <div class="col-md-4">
        <input type="hidden" name="fecha_ultimo_pago_abono" id="fecha_ultimo_pago_abono" value="<?=$ultimaReliquidacionPP->fecha_real_pago?>">
        <?=$ultimaReliquidacionPP->fecha_real_pago?>
        </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Valor presente:</div>
        <div class="col-md-4">
        <input type="hidden" name="valor_presente_abono" id="valor_presente_abono" value="<?=$ultimaReliquidacionPP->nuevo_valor_obligacion?>">
        <input type="hidden" name="valor_obligacion_pp_abono" id="valor_obligacion_pp_abono" value="<?=$ultimaReliquidacionPP->nuevo_valor_obligacion?>">
        <?=formato_moneda($ultimaReliquidacionPP->nuevo_valor_obligacion)?>
        </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Fecha pago:</div>
        <div class="col-md-4">
        <?php
            $c_textbox = new Textbox;
            echo $c_textbox->Textbox ("fecha_real_pago_abono", "fecha_real_pago_abono", 1, $reliquidacionPP->fecha_real_pago, "form-control required", 50, "", $functionCalculos."();", "","","");
       ?>
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Fecha base movimiento:</div>
        <div class="col-md-4">
        <?php
            $c_textbox = new Textbox;
            echo $c_textbox->Textbox ("fecha_movimiento_abono", "fecha_movimiento", 1, $reliquidacionPP->fecha_movimiento, "form-control required", 50, "", $functionCalculos."();", "0","","");
       ?>
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Tasa:</div>
        <div class="col-md-4">
        <?php
            $c_textbox = new Textbox;
            echo $c_textbox->Textbox ("tasa_abono", "tasa", 1, $reliquidacionPP->tasa, "form-control required number", 50, "7", $functionCalculos."();", "","","return IsNumber(event);");
       ?>
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Valor obligación:</div>
        <div class="col-md-4">
        <span id="text_valor_obligacion_abono"></span>
       </div>
    </div>       
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Abono:</div>
        <div class="col-md-4">
        <?php
            $c_textbox = new Textbox;
            echo $c_textbox->Textbox ("abono_abono", "abono_abono", 1, $reliquidacionPP->abono, "form-control required number", 50, "", "validarAbono('abono_abono','valor_obligacion_pp_abono');".$functionCalculos."();", "","","return IsNumber(event);");
       ?>
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Otros:</div>
        <div class="col-md-4">
        <?php
            $c_textbox = new Textbox;
            echo $c_textbox->Textbox ("otros_abono", "otros_abono", 1, $reliquidacionPP->otros, "form-control required number", 50, "", $functionCalculos."();", "","","return IsNumber(event);");
       ?>
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Intereses a devolver:</div>
        <div class="col-md-4">
        <span id="text_intereses_devolver_abono"></span>
        <input type="hidden" name="intereses_devolver_abono" id="intereses_devolver_abono" value="<?=($reliquidacionPP->intereses_devolver <= 0?0:$reliquidacionPP->intereses_devolver)?>">
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 labelCustom">Nuevo valor obligación:</div>
        <div class="col-md-4">
        <span id="text_nuevo_valor_obligacion_abono"></span>
        <input type="hidden" name="nuevo_valor_obligacion_abono" id="nuevo_valor_obligacion_abono" value="<?=($reliquidacionPP->nuevo_valor_obligacion <= 0?0:$reliquidacionPP->nuevo_valor_obligacion)?>">
       </div>
    </div>
    <div class="row devoluciones_abono" style="height:10px;">&nbsp;</div>
    <div class="row devoluciones_abono">
        <div class="col-md-4 labelCustom">Remanentes disponibles:</div>
        <div class="col-md-4">
        <span id="text_remanentes_disponibles"></span>
        </div>
    </div>
    <div class="row devoluciones_abono" style="height:10px;">&nbsp;</div>
    <div class="row devoluciones_abono">
        <div class="col-md-4 labelCustom">Devolución remanentes antes GMF:</div>
        <div class="col-md-4">
        <span id="text_devolucion_remanentes_gmf"></span>
        <input type="hidden" name="devolucion_remanentes_gmf" id="devolucion_remanentes_gmf" value="<?=$reliquidacionPP->devolucion_remanentes?>">
       </div>
    </div>
    <div class="row devoluciones_abono" style="height:10px;">&nbsp;</div>
    <div class="row devoluciones_abono">
        <div class="col-md-4 labelCustom">GMF:</div>
        <div class="col-md-4">
        <span id="text_gmf_ppa"></span>
        <input type="hidden" name="gmf" id="gmf_ppa" value="<?=$reliquidacionPP->gmf?>">
       </div>
    </div>
    <div class="row devoluciones_abono" style="height:10px;">&nbsp;</div>
    <div class="row devoluciones_abono">
        <div class="col-md-4 labelCustom">Monto a devolver:</div>
        <div class="col-md-4">
        <span id="text_monto_devolver"></span>
        <input type="hidden" name="monto_devolver" id="monto_devolver" value="<?=$reliquidacionPP->monto_devolver?>">
       </div>
    </div>
    <div class="row" style="height:10px;">&nbsp;</div>
    <center>
        <input type="button" value="Guardar" class="btn btn-primary datos_reliquidacion_btnSave" onclick="saveReliquidacionPPAbono();">
    </center>
</form>
</div>
<?php
    if ($idReliquidacionPP != 0 && $idReliquidacionPP != ""){
?>
    <script>
        $(document).ready(function () {
			var fechaOperacion = $("#fecha_operacion").val();
			//ESTE ES UN AJUSTE TEMPORAL POR EL CAMBIO DE CALCULOS EN LAS RELIQUIDACIONES
			if (fechaOperacion < '2019-01-01')		
	            calcularReliquidacionPPAbonoAnterior();
	        else
	        	calcularReliquidacionPPAbono();
	        
            calcularTasaAbono();
        });
    </script>
<?php
    }
?>
