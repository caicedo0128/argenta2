<div class="container-fluid">  
	<div class="col-md-6">	
		<?php
			//ESTE ES UN AJUSTE TEMPORAL POR EL CAMBIO DE CALCULOS EN LAS RELIQUIDACIONES
			$functionCalculos = "calcularReliquidacionPPP";
			if ($operacion->fecha_operacion < "2019-01-01"){
				$functionCalculos = "calcularReliquidacionPPPAnterior";
			}		
		?>
		<div class="row" style="height:10px;">&nbsp;</div>    
		<div class="row">            
			<input type="hidden" name="intereses_mora" id="intereses_mora" value="<?=$reliquidacionPP->intereses_mora?>">
			<input type="hidden" name="fecha_desembolso" id="fecha_desembolso" value="<?=$operacion->fecha_operacion?>">  
			<input type="hidden" name="facturas_seleccionadas" id="facturas_seleccionadas" value="">  
			<input type="hidden" name="valor_iva" id="valor_iva" value="">
			<input type="hidden" name="total_diferencia_descuento_total" id="total_diferencia_descuento_total" value="">
			<input type="hidden" name="total_diferencia_iva" id="total_diferencia_iva" value="0">           
			<div class="col-md-3 labelCustom">
				Fecha operación:
				<div class="form-control" disabled="disabled">
				<?=$operacion->fecha_operacion?>
				</div>
			</div>
			<div class="col-md-3 labelCustom">
				Fecha pago pactada:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_pago_pactada", "fecha_desembolso", 1, $reliquidacionPP->fecha_pago_pactada, "form-control required", 50, "", $functionCalculos . "();", "1","","");
				?>
				</div>
			</div>	
			<div class="col-md-3 labelCustom">
				Fecha real de pago:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_real_pago", "fecha_real_pago", 1, $reliquidacionPP->fecha_real_pago, "form-control required", 50, "", $functionCalculos . "();", "","","");
			   ?>
			   </div>
			</div>         
		</div> 
		<div class="row" style="height:10px;">&nbsp;</div>   
		<div class="row">            
			<div class="col-md-3 labelCustom">
				Fecha base movimiento:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("fecha_movimiento", "fecha_movimiento", 1, $reliquidacionPP->fecha_movimiento, "form-control required", 50, "", $functionCalculos . "();", "0","","");
			   ?>
			   </div>
			</div>      
			<div class="col-md-3 labelCustom">
				Deuda a la fecha pactada:
				<div class="">
				<?php
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("valor_obligacion_pp", "valor_obligacion_pp", 1, $reliquidacionPP->valor_obligacion_pp, "form-control required number", 50, "", $functionCalculos . "();", "1","","return IsNumber(event);");
				?>
				</div>
			</div>             	
			<div class="col-md-3 labelCustom">
				Tasa:
				<div class="">   
					<?php 
						$c_textbox = new Textbox;
						echo $c_textbox->Textbox ("tasa", "tasa", 1, $reliquidacionPP->tasa, "form-control required number", 50, "7", $functionCalculos."();", "","","return IsNumber(event);");
				   ?>  
				</div>           	
			</div>         
		</div>      
		<div class="row" style="height:10px;">&nbsp;</div>   
		<div class="row">   
			<div class="col-md-3 labelCustom">
				Valor obligación fecha pago real:
				<div class="form-control" disabled="disabled">   
					<span id="text_valor_obligacion_pago_real"></span>
					<input type="hidden" name="valor_obligacion_pago_real" id="valor_obligacion_pago_real" value="<?=($reliquidacionPP->nuevo_valor_obligacion <= 0?0:$reliquidacionPP->nuevo_valor_obligacion)?>">           
				</div>        
			</div>
			<div class="col-md-3 labelCustom">
				Abono:
				<div class="">   
				<?php 
					$c_textbox = new Textbox;
					echo $c_textbox->Textbox ("abono", "abono", 1, $reliquidacionPP->abono, "form-control required number", 50, "", $functionCalculos."();", "","","return IsNumber(event);");             
				?>    
				</div>           	
			</div>   
			<div class="col-md-3 labelCustom">
				Nuevo valor obligación:
				<div class="form-control" disabled="disabled">   
					<span id="text_nuevo_valor_obligacion"></span>
					<input type="hidden" name="nuevo_valor_obligacion" id="nuevo_valor_obligacion" value="<?=$reliquidacionPP->nuevo_valor_obligacion?>">  
				</div>             
			</div>
		</div>    
	</div>
    <div class="col-md-3">
		<b>Detalle facturaci&oacute;n:</b>
		<table  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
			<tr>
				<td>IVA Gesti&oacute;n:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->iva_gestion)?></td>
			</tr>
			<tr>
				<td>IVA Giro a terceros:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->iva_giro_terceros)?></td>
			</tr>
			<tr>
				<td>Total factura:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->total_factura)?></td>
			</tr>    
			<tr>
				<td>RTF Gesti&oacute;n:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->rtf_gestion)?></td>
			</tr>
			<tr>
				<td>RTF Giro a terceros:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->rtf_giro_terceros)?></td>
			</tr>
			<tr>
				<td>RTF Intereses:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->rtf_intereses)?></td>
			</tr>
			<tr>
				<td>RTF ICA Gesti&oacute;n:</td>
				<td align="right"><?=formato_moneda($reliquidacionPP->rtf_ica)?></td>
			</tr>
			<tr>
				<td>RTF IVA:</td>
				<td style="text-align:right;" align="right"><?=formato_moneda($reliquidacionPP->rtf_iva)?></td>
			</tr>
			<tr>
				<td>Valor neto factura:</td>
				<td style="text-align:right;" align="right"><?=formato_moneda($reliquidacionPP->neto_factura)?></td>
			</tr>
			<tr>
				<td>Valor retenciones a aplicar</td>
				<td style="text-align:right;" align="right"><?=formato_moneda($reliquidacionPP->nuevo_remanente)?></td>
			</tr>    
		</table>   
    </div>              
</div>
<div class="container-fluid">    
    <div class="row-fluid" style="height:10px;"><hr/></div> 
    <div class="row-fluid" style="height:10px;"></div> 
    <?php
        if ($reliquidacionPP->id_reliquidacion_pp != 0 && $reliquidacionPP->id_reliquidacion_pp != null)
        {
    ?>
            <span class="alert alert-warning">Información de abonos</span>
            <div class="row-fluid" style="height:10px;"></div> 
            <div class="row-fluid" style="height:10px;"><hr/></div>          
            <a class="btn btn-success btn-sm" id="btnAgregarAbono" href="javascript:editReliquidacionAbono(0,<?=$reliquidacionPP->id_reliquidacion?>)"><i class="fa fa-plus-square fa-lg"></i> Agregar Abono</a>                                        
    <?php
        }
    ?>
</div>
<div id="modalAbono" class="modal fade" role="dialog" aria-labelledby="modalAbono" aria-hidden="true">
    <div class="modal-dialog" id="modal_funcion">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="btnClosemodalAbono"class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Registro de información de abonos</h4>
            </div>
            <div class="modal-body" id="content_abonos">

            </div>
        </div>
    </div>
</div>

