<?php
    //IMPRIMIMOS EL LLAMADO
    if (!$esReporte){
    	echo "<br/><center>";
        echo "<input type='button' value='Enviar información' onclick='formDatosReporte(1);' class='btn btn-warning'>&nbsp;&nbsp;";
        echo "<input type='button' value='Descargar' onclick='descargarReporte(1);' class='btn btn-primary'>";
		echo "</center></br>";
    }
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
        <div style="text-align:right;padding:0px:margin:0px;">
        <img src="./images/logo3.png" style="padding:0px:margin:0px;">
        </div>
        Bogotá D.C., <?php echo $meses[date('n')-1]; ?> <?php
        $hoy = getdate();
        $d = $hoy[mday];
        $m = $hoy[mon];
        $y = $hoy[year];
        echo($d ." de ". $y).".";
        ?>
        <br/> <br/>
        Señores:
        <br/>
        <b><?=$this->razon_social?></b>
        <br/>
        <b>ATN. <?=$this->representante_legal?></b>
        <br/>
        <b>Representante Legal</b>
        <br/>
        <?=$this->ciudad?>
        <br/>
        <br/>
        <br/>
        Apreciados Señores:
        <br/>
        <br/>
        Es un placer para <b>ARGENTA ESTRUCTURADORES S.A.S.</b> adelantar la presente propuesta comercial encaminada a realizar operaciones de factoring tradicional, enmarcadas por las siguientes condiciones:
		<br/><br/>
		<span align="center" style="text-align:center;"><b>I. CONTENIDO DE LA PROPUESTA</b></span>
		<br/>
        <ul>
        <li align="justify">El plazo máximo de nuestras operaciones de factoring es de <?=$this->plazo?> días.
        <li>Factor del <?=$this->factor?>% por los primeros 30 días y prorrateado por cada día adicional a partir del día 31 de la operación, además de los impuestos que la operación genere.
        <li align="justify">El porcentaje de desembolso se relaciona en el numeral II y se calcula sobre el valor neto de pago de la factura.
        <li align="justify">El tiempo mínimo para descuento de una factura será de 30 días.
        <ul>
        <li align="justify">Las facturas pagadas con anterioridad a la fecha pactada para el pago, generará devolución de los intereses proporcionalmente.
        <li align="justify">Las facturas pagadas con posterioridad a la fecha pactada de pago, generan intereses moratorios a la tasa máxima legal vigente permitida.
        </ul>
        <li align="justify">Los remanentes de las operaciones serán devueltos a <?=$this->razon_social?> o a quién éste designe en el momento en que el pagador de las facturas cancele la totalidad del dinero de la obligación, y siempre y cuando <?=$this->razon_social?> no tenga deudas pendientes en mora por saldar con <b>ARGENTA ESTRUCTURADORES S.A.S.</b>
        <li align="justify">Si la transferencia de recursos (al momento del giro inicial y/o en la devolución de remanentes) se requiere a un tercero diferente del titular de la operación (emisor de las facturas) generará un cargo adicional equivalente al 0.003984 del valor de giro, costo que será facturado.</b>
        <li align="justify">Tanto el pagaré, como el contrato de cesión de derechos económicos, objeto del factoring, deberán haber sido debidamente otorgados y estar vigentes. Lo anterior, como requisito para la validez de la operación de factoring, el cual se materializa con la aceptación de la propuesta.</b>
        <li align="justify"><b>ARGENTA ESTRUCTURADORES S.A.S.</b> tendrá la facultad de requerir al cliente para que realice el reemplazo del pagaré, en caso de pérdida o destrucción de este, solicitud que deberá ser atendida por el cliente en los siguientes ocho (8) días hábiles.</b>
        <li align="justify">El cliente deberá mantener informado y actualizado a <b>ARGENTAESTRUCTURADORES S.A.S.</b>, respecto a la ejecución de sus operaciones e información financiera, así como toda la información relevante y relativa a la solvencia del deudor cedido, la cual deberá ser suministrada de manera completa por el cliente. No obstante, una vez Argenta lo solicite, deberá remitir dicha información en un plazo no mayor a diez (10) días hábiles.</b>
        <li align="justify">El cliente garantiza y es responsable frente a <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, acerca de la validez y existencia de los créditos que cede a favor del factor como garantía para su financiamiento.</b>
        <li align="justify">El cliente no podrá ceder o transferir los créditos otorgados a <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, a ningún título, ni a favor de algún tercero. 
        <li align="justify">El incumplimiento de cualquiera de las obligaciones contractuales del cliente con <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, facultará a éste para acelerar y hacer exigibles todos los créditos otorgados.
        </ul>
        <br/>
		<span align="center" style="text-align:center;"><b>II. PAGADORES AUTORIZADOS</b></span>
		<br/><br/>
		A continuación, se relacionan los pagadores autorizados para las operaciones de factoring, y cupos asignados:		
		<br/>
		<div align="center" style="text-align:center;margin:0 auto;">
		<small>
		<table border="1" width="100%" align="center">
			<tr>
				<th align="center" style="text-align:center;">NIT</th>
				<th align="center" style="text-align:center;">RAZÓN SOCIAL</th>
				<th align="center" style="text-align:center;">PORCENTAJE DE DESEMBOLSO</th>
			</tr>
			<?php
				while(!$rsData->EOF){
					echo "<tr>";
					echo "<td>".$rsData->fields["identificacion"].$rsData->fields["digito_verificacion"]."</td>";
					echo "<td>".$rsData->fields["razon_social"]."</td>";
					echo "<td tyle=\"text-align:center;\">".$rsData->fields["porcentaje_descuento"]."%</td>";
					echo "</tr>";
					$rsData->MoveNext();
				}
			?>
		</table>
		</small>
		</div>
		En caso de requerir la autorización de un nuevo pagador, podrá formalizarse vía correo electrónico el cual será parte integral de la presente propuesta. 
		<br/>
		<small>
		*En caso de que como resultado de la evaluación periódica que <b>ARGENTA ESTRUCTURADORES S.A.S</b> realiza; alguno de los pagadores no cumpla las políticas de riesgo y crédito de ARGENTA ESTRUCTURADORES SAS será notificado para actualización inmediata.
		</small>
		<br/><br/>
		<span align="center" style="text-align:center;"><b>III. CONFIDENCIALIDAD</b></span>
		<br/><br/>		
		Todo documento, programa, base de datos, tecnología y demás operaciones inherentes a la actividad del cliente, constituye información confidencial de propiedad exclusiva del mismo, y se encuentra protegida por la Ley 23 de 1982, Ley 44 de 1993 y la Decisión 344 y 351 del Acuerdo de Cartagena sobre derechos de autor y propiedad industrial, y todas aquellas normas que las adicionen, modifiquen o sustituyan.
		<br/><br/>		
		De igual forma, las condiciones y términos establecidos en este documento como aquellos contenidos en los contratos que con posterioridad las partes decidan suscribir, se considera información confidencial y estarán amparados bajo las leyes mencionadas en este acápite. Por lo tanto, el cliente se abstendrá de remitir a terceros las condiciones, precios establecidos en este documento y en los demás que hagan parte de este. De la misma manera, cada parte se compromete a evitar la realización de actos, conductas, hechos constitutivos de competencia desleal, actos de descrédito o cualquier acto desleal, actos de sustracción y explotación de secretos o información confidencial, incluyendo la utilización de trabajadores para el desarrollo de actividades propias de cada parte sin mediar la debida autorización. 
		<br/><br/>		
		La presente oferta tiene una vigencia de treinta (30) días desde la fecha de su expedición. Una vez aceptada se mantendrá vigente de manera indefinida hasta tanto se notifique por escrito cualquier modificación. 
		<br/><br/>		
		En señal de aceptación, las partes suscriben el presente documento. 		
        <br/><br/><br/><br/>
        Aceptación por las partes,
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <b>
		<table>
		  <tr>
		    <td style="width:40%;">__________________________________</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;">__________________________________</td>
		  </tr>
		  <tr>
            <td style="width:40%;">JACOBO SANINT ARISTIZABAL</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;"><?=$this->representante_legal?></td>
		  </tr>
		  <tr>
            <td style="width:40%;">C.C. 75.105.339 de MANIZALES</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;">C.C. <?=formato_moneda($this->identificacion_representante,"")?> de <?=$arrCiudades[$this->id_ciudad_expedicion] ?></td>
		  </tr>		  
		  <tr>
            <td style="width:40%;">REPRESENTANTE LEGAL</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;">REPRESENTANTE LEGAL</td>
		  </tr>
		  <tr>
            <td style="width:40%;">ARGENTA ESTRUCTURADORES S.A.S</td>
		    <td style="width:9%;"></td>
            <td style="width:50%;"><?=$this->razon_social?></td>
		  </tr>
          <tr>
            <td style="width:40%;">NIT. 900.518.469-1</td>
            <td style="width:9%;"></td>
            <td style="width:50%;">NIT. <?=formato_moneda($this->identificacion,"")?>-<?=$this->digito_verificacion?></td>
          </tr>
		</table>
		</b>
        <br/><br/><br/>
        <br/>
        <div style="text-align:center;">
        	Calle 94 No. 11ª 76 Oficina 102 - Bogotá D.C.
        	<br/>
			Tel. (1) 7429779  - info@argentaestructuradores.com
        </div>
</div>
</div>


