<?php
if (!$esReporte){
?>
	<script type="text/javascript">

	$(document).ready(function(){
		$('#fecha_generacion_pagare').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });

	});

	</script>
<?php
	}
?>
<?php

    $pagare = $this->pagare;
    if ($this->pagare == "")
        $pagare = date('dmY').$this->id_cliente;

    //IMPRIMIMOS EL LLAMADO
    if (!$esReporte){
    	echo "<br/><center>";
        if ($this->pagare == ""){
            echo "<div>Nro. pagaré: <input type='text' class='form-control' value='".$pagare."' id='nro_pagare' class='btn btn-success' style='width:20%'>";
            echo "<input type='button' value='Guardar pagaré' onclick='savePagare(".$this->id_cliente.");' class='btn btn-warning' style='margin-top:10px;'>&nbsp;</div><br/>";
        }
        else{
        	$dias = $appObj->paramGral["ANIOS_VENCIMIENTO_PAGARE"];
        	$fechaVencimiento = sumar_dias_fecha($this->fecha_generacion_pagare,$dias);
        	echo "<input type='hidden' value='".$this->pagare."' id='nro_pagare'>";
        	echo "<div class='label label-info' style='padding:10px;font-size:12px !important;'>Fecha generación pagaré: </div>&nbsp;<input type='text' name='fecha_generacion_pagare' id='fecha_generacion_pagare' class='' size='10' value='".$this->fecha_generacion_pagare."' style='padding:5px;'> <a href='javascript:savePagare(".$this->id_cliente.");' title='Actualizar fecha' class='text-white'><i class='fa fa-refresh text-white'></i></a>&nbsp;";
        	echo "<div class='label label-info' style='padding:10px;font-size:12px !important;'>Fecha vencimiento: ".$fechaVencimiento."</div>&nbsp;";
        	$arrDiferenciaFechasNotificacion = date_diff_custom(date("Y-m-d"), $fechaVencimiento);
        	if ($arrDiferenciaFechasNotificacion["d"] > 30)
        		echo "<span class='label label-success' style='padding:10px;font-size:12px !important;'>Vigente</span>";
        	else if ($arrDiferenciaFechasNotificacion["d"] >= 1 && $arrDiferenciaFechasNotificacion["d"] <= 30)
        		echo "<span class='label label-warning' style='padding:10px;font-size:12px !important;'>Próximo a vencer</span>";
        	else if ($arrDiferenciaFechasNotificacion["d"] <= 0)
        		echo "<span class='label label-danger' style='padding:10px;font-size:12px !important;'>Vencido</span>";

        	echo "<hr/>";
        }
		echo "<input type='button' value='Enviar información' onclick='formDatosReporte(2);' class='btn btn-success'>&nbsp;&nbsp;";
        echo "<input type='button' value='Descargar' onclick='descargarReporte(2);' class='btn btn-primary'>";
		echo "</center></br>";
    }

    //DETERMINAMOS SI HAY REPRESENTANTE SUPLENTE
    $display = "none";
    if ($this->representante_supl != "" && $this->identificacion_representante_supl != "" && $this->id_ciudad_exp_representante_supl != "")
    	$display = "inline";
?>
<style type="text/css">
    #dina4 {
    width: 210mm;
    height: 297mm;
    padding: 20px 60px;
    border: 1px solid #D2D2D2;
    background: #fff;
    margin: 10px auto;
}
</style>

<div class="panel panel-success">
    <div class="panel-body reporte-cliente" style="font-size:12px;">
        <div style="line-height:1.5;">
        <br/>
        <b style="font-size:14px;">PAGARE No. <?=($this->pagare==""?"No ha guardado nro de pagaré":$this->pagare)?></b>
        <br/>
        <br/>
        <b style="font-size:14px;">VALOR:____________________________________________________ ($___________________)</b>
        <br/>
        <b style="font-size:14px;">CIUDAD DONDE SE EFECTUARÁ EL PAGO: __________________________________________.</b>
        <br/>
        <b style="font-size:14px;">VENCIMIENTO(S): ________________________________________________________________.</b>
        <br/>
        <b style="font-size:14px;">INTERESES DURANTE LA MORA: __________________________________________________.</b>
        </div>

        <br/><br/><div align="justify" style="line-height:1.5;">Yo, <b><?=$this->representante_legal?></b>  mayor(es) de edad, identificado(s) como aparece al pie de mi(nuestras) firma (s), actuando en nombre propio y en calidad de representante legal de <b><?=$this->razon_social?></b><span style="display:<?=$display?>;"> y, yo, <b><?=$this->representante_supl?></b>  mayor(es) de edad, identificado(s) como aparece al pie de mi(nuestras) firma (s), actuando en nombre propio y en calidad de representante legal suplente de <b><?=$this->razon_social?>;</b></span> domiciliado(s) en el lugar que aparece al pie de mi(nuestra) firma, por medio del presente escrito manifiesto(manifestamos), lo siguiente: <b>PRIMERO:</b> Que debo(debemos) y pagaré(pagaremos), incondicional y solidariamente (si son varias personas) a la orden de <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, o a la persona natural o jurídica a quien el(los) mencionado(s) acreedor(es) ceda(n) o endose(n) sus derechos, la suma cierta de <b style="font-size:14px;">________________________________________________________________________________</b>, pesos moneda legal colombiana. <b>SEGUNDO:</b> Que el pago total de la mencionada obligación se efectuará en un sólo contado, el día <b style="font-size:14px;">___________________</b>, en las oficinas de <b>ARGENTA ESTRUCTURADORES S.A.S</b>, localizadas en la <b style="font-size:14px;">_________________</b> de la ciudad de <b style="font-size:14px;">_____________</b>. <b>TERCERO:</b> Que en caso de mora pagaré(mos) a <b>ARGENTA ESTRUCTURADORES S.A.S</b>, o a la persona natural o jurídica a quien el mencionado acreedor ceda o endose sus derechos, intereses de mora a la más alta tasa permitida por la Ley desde cuando la obligación se haga exigible, o sea a la fecha de exigibilidad del presente pagaré, y hasta cuando su pago total se efectúe. <b>CUARTO:</b> Expresamente declaro (declaramos) excusado el protesto del presente pagaré y los requerimientos judiciales o extrajudiciales para la constitución en mora. <b>QUINTO:</b> En caso de que haya lugar al recaudo judicial o extrajudicial de la obligación contenida en el presente título valor será de mi(nuestro) cargo las costas judiciales y/o los honorarios que se causen por tal razón. <b>SEXTO: ARGENTA ESTRUCTURADORES S.A.S</b>, queda(n) autorizada(dos) para debitar cualquier suma que llegare a tener como crédito a su cargo y en mi (nuestro) favor, del importe total o parcial de este título valor en caso de incumplimiento de mi(nuestra) parte. <b>SEPTIMO:</b> También serán a nuestro (mi) cargo el valor del (de los) impuesto(s) que deba(n) cancelarse para la legalización del título valor. En constancia de lo anterior, se suscribe en la ciudad de Bogotá, a los <?php echo strtolower(NumeroALetras::convertir(date('d'))); ?>
        (<?php echo date('d'); ?>) días del mes de <?php echo $meses[date('n')-1]; ?> del <?php echo date('Y');?>.
        </div>
		<br/>
		<br/>
        EL(LOS) DEUDOR(ES),

        <br/>
        <br/>
        <br/>
        <br/>
		<br/>
		<table>
		  <tr>
		    <td style="width:40%;">__________________________________</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;">__________________________________</td>
		  </tr>
		  <tr>
            <td style="width:40%;"><?=$this->representante_legal?></td>
		    <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;"><?=$this->representante_supl?></td>
		  </tr>
		  <tr>
            <td style="width:40%;">C.C. <?=$this->identificacion_representante?> DE <?=$arrCiudades[$this->id_ciudad_expedicion]?></td>
		    <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;">C.C. <?=$this->identificacion_representante_supl?> DE <?=$arrCiudades[$this->id_ciudad_exp_representante_supl]?></td>
		  </tr>
		  <tr>
            <td style="width:40%;">REPRESENTANTE LEGAL</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;">REPRESENTANTE LEGAL SUPLENTE</td>
		  </tr>
		  <tr>
            <td style="width:40%;"><?=$this->razon_social?></td>
		    <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;"><?=$this->razon_social?></td>
		  </tr>
          <tr>
            <td style="width:40%;">NIT. <?=$this->identificacion?>-<?=$this->digito_verificacion?></td>
            <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;">NIT. <?=$this->identificacion?>-<?=$this->digito_verificacion?></td>
          </tr>
          <tr>
            <td style="width:40%;">DIRECCION. <?=$this->direccion?></td>
            <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;">DIRECCION. <?=$this->direccion?></td>
          </tr>
          <tr>
            <td style="width:40%;"><?=$this->ciudad?> - <?=$nomDepartamento ?></td>
            <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;"><?=$this->ciudad?> - <?=$nomDepartamento ?></td>
          </tr>
          <tr>
            <td style="width:40%;">TEL:	<?=$this->telefono_fijo?></td>
            <td style="width:9%;"></td>
            <td style="width:50%;display:<?=$display?>;">TEL:	<?=$this->telefono_fijo?></td>
          </tr>
		</table>
</div>
</div>
