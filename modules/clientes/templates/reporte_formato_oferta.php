<?php
    //IMPRIMIMOS EL LLAMADO
    if (!$esReporte){
    	echo "<br/><center>";
        echo "<input type='button' value='Enviar informaci�n' onclick='formDatosReporte(1);' class='btn btn-warning'>&nbsp;&nbsp;";
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
        Bogot� D.C., <?php echo $meses[date('n')-1]; ?> <?php
        $hoy = getdate();
        $d = $hoy[mday];
        $m = $hoy[mon];
        $y = $hoy[year];
        echo($d ." de ". $y).".";
        ?>
        <br/> <br/>
        Se�ores:
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
        Apreciados Se�ores:
        <br/>
        <br/>
        Es un placer para <b>ARGENTA ESTRUCTURADORES S.A.S.</b> adelantar la presente propuesta comercial encaminada a realizar operaciones de factoring tradicional, enmarcadas por las siguientes condiciones:
		<br/><br/>
		<span align="center" style="text-align:center;"><b>I. CONTENIDO DE LA PROPUESTA</b></span>
		<br/>
        <ul>
        <li align="justify">El plazo m�ximo de nuestras operaciones de factoring es de <?=$this->plazo?> d�as.
        <li>Factor del <?=$this->factor?>% por los primeros 30 d�as y prorrateado por cada d�a adicional a partir del d�a 31 de la operaci�n, adem�s de los impuestos que la operaci�n genere.
        <li align="justify">El porcentaje de desembolso se relaciona en el numeral II y se calcula sobre el valor neto de pago de la factura.
        <li align="justify">El tiempo m�nimo para descuento de una factura ser� de 30 d�as.
        <ul>
        <li align="justify">Las facturas pagadas con anterioridad a la fecha pactada para el pago, generar� devoluci�n de los intereses proporcionalmente.
        <li align="justify">Las facturas pagadas con posterioridad a la fecha pactada de pago, generan intereses moratorios a la tasa m�xima legal vigente permitida.
        </ul>
        <li align="justify">Los remanentes de las operaciones ser�n devueltos a <?=$this->razon_social?> o a qui�n �ste designe en el momento en que el pagador de las facturas cancele la totalidad del dinero de la obligaci�n, y siempre y cuando <?=$this->razon_social?> no tenga deudas pendientes en mora por saldar con <b>ARGENTA ESTRUCTURADORES S.A.S.</b>
        <li align="justify">Si la transferencia de recursos (al momento del giro inicial y/o en la devoluci�n de remanentes) se requiere a un tercero diferente del titular de la operaci�n (emisor de las facturas) generar� un cargo adicional equivalente al 0.003984 del valor de giro, costo que ser� facturado.</b>
        <li align="justify">Tanto el pagar�, como el contrato de cesi�n de derechos econ�micos, objeto del factoring, deber�n haber sido debidamente otorgados y estar vigentes. Lo anterior, como requisito para la validez de la operaci�n de factoring, el cual se materializa con la aceptaci�n de la propuesta.</b>
        <li align="justify"><b>ARGENTA ESTRUCTURADORES S.A.S.</b> tendr� la facultad de requerir al cliente para que realice el reemplazo del pagar�, en caso de p�rdida o destrucci�n de este, solicitud que deber� ser atendida por el cliente en los siguientes ocho (8) d�as h�biles.</b>
        <li align="justify">El cliente deber� mantener informado y actualizado a <b>ARGENTAESTRUCTURADORES S.A.S.</b>, respecto a la ejecuci�n de sus operaciones e informaci�n financiera, as� como toda la informaci�n relevante y relativa a la solvencia del deudor cedido, la cual deber� ser suministrada de manera completa por el cliente. No obstante, una vez Argenta lo solicite, deber� remitir dicha informaci�n en un plazo no mayor a diez (10) d�as h�biles.</b>
        <li align="justify">El cliente garantiza y es responsable frente a <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, acerca de la validez y existencia de los cr�ditos que cede a favor del factor como garant�a para su financiamiento.</b>
        <li align="justify">El cliente no podr� ceder o transferir los cr�ditos otorgados a <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, a ning�n t�tulo, ni a favor de alg�n tercero. 
        <li align="justify">El incumplimiento de cualquiera de las obligaciones contractuales del cliente con <b>ARGENTA ESTRUCTURADORES S.A.S.</b>, facultar� a �ste para acelerar y hacer exigibles todos los cr�ditos otorgados.
        </ul>
        <br/>
		<span align="center" style="text-align:center;"><b>II. PAGADORES AUTORIZADOS</b></span>
		<br/><br/>
		A continuaci�n, se relacionan los pagadores autorizados para las operaciones de factoring, y cupos asignados:		
		<br/>
		<div align="center" style="text-align:center;margin:0 auto;">
		<small>
		<table border="1" width="100%" align="center">
			<tr>
				<th align="center" style="text-align:center;">NIT</th>
				<th align="center" style="text-align:center;">RAZ�N SOCIAL</th>
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
		En caso de requerir la autorizaci�n de un nuevo pagador, podr� formalizarse v�a correo electr�nico el cual ser� parte integral de la presente propuesta. 
		<br/>
		<small>
		*En caso de que como resultado de la evaluaci�n peri�dica que <b>ARGENTA ESTRUCTURADORES S.A.S</b> realiza; alguno de los pagadores no cumpla las pol�ticas de riesgo y cr�dito de ARGENTA ESTRUCTURADORES SAS ser� notificado para actualizaci�n inmediata.
		</small>
		<br/><br/>
		<span align="center" style="text-align:center;"><b>III. CONFIDENCIALIDAD</b></span>
		<br/><br/>		
		Todo documento, programa, base de datos, tecnolog�a y dem�s operaciones inherentes a la actividad del cliente, constituye informaci�n confidencial de propiedad exclusiva del mismo, y se encuentra protegida por la Ley 23 de 1982, Ley 44 de 1993 y la Decisi�n 344 y 351 del Acuerdo de Cartagena sobre derechos de autor y propiedad industrial, y todas aquellas normas que las adicionen, modifiquen o sustituyan.
		<br/><br/>		
		De igual forma, las condiciones y t�rminos establecidos en este documento como aquellos contenidos en los contratos que con posterioridad las partes decidan suscribir, se considera informaci�n confidencial y estar�n amparados bajo las leyes mencionadas en este ac�pite. Por lo tanto, el cliente se abstendr� de remitir a terceros las condiciones, precios establecidos en este documento y en los dem�s que hagan parte de este. De la misma manera, cada parte se compromete a evitar la realizaci�n de actos, conductas, hechos constitutivos de competencia desleal, actos de descr�dito o cualquier acto desleal, actos de sustracci�n y explotaci�n de secretos o informaci�n confidencial, incluyendo la utilizaci�n de trabajadores para el desarrollo de actividades propias de cada parte sin mediar la debida autorizaci�n. 
		<br/><br/>		
		La presente oferta tiene una vigencia de treinta (30) d�as desde la fecha de su expedici�n. Una vez aceptada se mantendr� vigente de manera indefinida hasta tanto se notifique por escrito cualquier modificaci�n. 
		<br/><br/>		
		En se�al de aceptaci�n, las partes suscriben el presente documento. 		
        <br/><br/><br/><br/>
        Aceptaci�n por las partes,
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
        	Calle 94 No. 11� 76 Oficina 102 - Bogot� D.C.
        	<br/>
			Tel. (1) 7429779  - info@argentaestructuradores.com
        </div>
</div>
</div>


