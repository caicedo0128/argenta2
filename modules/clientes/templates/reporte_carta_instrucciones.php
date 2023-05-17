<?php
    //IMPRIMIMOS EL LLAMADO
    if (!$esReporte){
    	echo "<br/><center>";
        echo "<input type='button' value='Enviar información' onclick='formDatosReporte(3);' class='btn btn-warning'>&nbsp;&nbsp;";
        echo "<input type='button' value='Descargar' onclick='descargarReporte(3);' class='btn btn-primary'>";
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
        <br/>
        <b>CARTA DE INSTRUCCIONES PARA DILIGENCIAR LOS ESPACIOS EN BLANCO DEL PAGARE  <?=($this->pagare==""?"No ha guardado nro de pagaré":$this->pagare)?></b>
        <br/><br/>
        <div align="justify">Yo, <b><?=$this->representante_legal?></b> mayor(es) de edad, identificado(s) como aparece al pie de mi(nuestras) firma (s), actuando en nombre propio y calidad de representante legal de <b><?=$this->razon_social?></b><span style="display:<?=$display?>;"> y, yo, <b><?=$this->representante_supl?></b>  mayor(es) de edad, identificado(s) como aparece al pie de mi(nuestras) firma (s), actuando en nombre propio y en calidad de representante legal suplente de <b><?=$this->razon_social?></b></span>, por medio del presente escrito faculto(facultamos) a <b>ARGENTA ESTRUCTURADORES S.A.S</b>, a nombre propio para que, de manera permanente e irrevocable, en aplicación del derecho conferido por el artículo 622 del Código de Comercio, sin previo aviso complete los espacios que se han dejado en blanco en el <b>PAGARÉ No. <?=($this->pagare==""?"No ha guardado nro de pagaré":$this->pagare)?></b> adjunto siguiendo las siguientes instrucciones:
        </div>
        <br/>
        <div align="justify">1. <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, podrá hacer uso de esta autorización y llenar los espacios, sin aviso a <b><?=$this->razon_social?></b>, cuando incumpla sobre cualquiera de las obligaciones de pago contraídas con <b>ARGENTA ESTRUCTURADORES S.A.S</b>
        </div>
        <br/>
        <div align="justify">2. <b>ARGENTA ESTRUCTURADORES S.A.S</b>, queda facultada para declarar vencido el plazo y exigir el pago total de la obligación, más los intereses de mora y demás accesorios en caso de incumplimiento en el pago de cualquiera de las obligaciones a su cargo, así como los demás gastos relacionados con dicho cobro, tales como costos de avalúos, pólizas, gastos para la defensa de los bienes, comisiones fiduciarias, impuestos, gastos de administración, gastos bancarios, etcétera, o de cualquier otra obligación a cargo de <b><?=$this->razon_social?></b>, en favor de <b>ARGENTA ESTRUCTURADORES S.A.S.</b>
        </div>
        <br/>
        <div align="justify">3.  El espacio en blanco correspondiente al lugar donde debe cumplirse la obligación, deberá completarse con el nombre de la ciudad donde se firmó el pagaré.
        </div>
        <br/>
        <div align="justify">4.  El espacio en blanco correspondiente a la fecha de vencimiento deberá ser llenado, con la fecha correspondiente al día en el cual se completan los espacios en blanco en el pagaré de conformidad con lo establecido en el numeral siguiente.
        </div>
        <br/>
        <div align="justify">5.  Los espacios dejados en blanco en el pagaré se podrán diligenciar de conformidad con lo aquí señalado, a partir del día o momento en que ocurra o se configure mora o incumplimiento en el pago de cualquier obligación existente a mi (nuestro) cargo o de cualquier otra obligación en mora a favor de <b>ARGENTA ESTRUCTURADORES S.A.S.</b>
        </div>
        <br/>
        <div align="justify">Autorizo (amos) para que verifiquen, procesen, administren y reporten toda información comercial y financiera que sobre mi hayan recibido o se llegare a recibir en las centrales de información. En constancia de pleno conocimiento y aceptación de lo anterior se firma la presente carta de instrucciones en Bogotá a los <?php echo strtolower(NumeroALetras::convertir(date('d'))); ?> (<?php echo strtolower(date('d')); ?>) días del mes de <?php echo $meses[date('n')-1]; ?> del <?php echo date('Y');?>.
        </div>
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
