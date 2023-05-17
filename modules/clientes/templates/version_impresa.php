<style type="text/css">

#contenido-impresion{
 color:#000;
 font-size:8px;
}

#contenido-impresion .form-control{
 font-size:8px;
 height:21px;
 border-radius:0px;
}

.contenido-impresion{
 color:#000;
 font-size:8px;
}

.contenido-impresion .form-control{
 font-size:8px;
 height:21px;
 border-radius:0px;
 color:#000;
}

.table{
	font-size:8px !important;
}

#contenido-impresion .alert{
	margin-bottom:5px !important;
	margin-top:5px !important;
	padding:5px !important;
	border-radius:0px;
}

.contenido-impresion .alert{
	margin-bottom:5px !important;
	margin-top:5px !important;
	padding:5px !important;
	border-radius:0px;
}

</style>
<?php
    //IMPRIMIMOS EL LLAMADO
    if ($_REQUEST["es_reporte"] != "1"){
		echo "<input type='button' class='btn btn-primary' value='Imprimir' onclick='descargarPDF();'>";
	}
?>
<div id="contenido-impresion" class="contenido-impresion">
	<table border="1" width="100%">
		<tr>
			<td align="center" valign="middle" style="width:300px;height:95px;"><img id="logoArgenta" src="./images/logo.png" width="160" height="63" style="width:160px;margin-top:10px;"></td>
			<td align="center" valign="middle"><br/>FORMULARIO<br/>VINCULACION CLIENTE</td>
			<td align="center" valign="middle"><br/>FORM01<br/>Version No.2	</td>
		</tr>
	</table>
	<br/>
	<div class="row-fluid alert alert-info text-center">1. INFORMACIÓN BÁSICA</div>
	<div class="row">
		<div class="col-md-3 labelCustom">
			Tipo documento:
			<div class="form-control" style="padding-top:0px;">
			<?php
				$c_radio = new Radio;
				$arrTipos = array("1"=>"RUT","2"=>"CE", "3"=>"NIT","4"=>"CC");
				echo
				$c_radio->Radio("Tipo","Tipo",$arrTipos,"", 1, $rsDataCliente->fields["tipo_identificacion"], "", 0, "");
				while($tmp_html = $c_radio->next_entry()) {
					echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
				}
			?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Documento:
			<div class="form-control">
			<?=$rsDataCliente->fields["identificacion"]?>
			</div>
		</div>
		<div class="col-md-1 digito_verificacion labelCustom">
			DV:
			<div class="form-control">
			<?=$rsDataCliente->fields["digito_verificacion"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-6 labelCustom">
			<span class="titulo_razon_social">Nombre o Razón social:</span>
			<div class="form-control">
			<?=$rsDataCliente->fields["razon_social"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Fecha constitución:
			<div class="form-control">
			<?=$rsDataCliente->fields["fecha_consticucion"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-3 labelCustom">
			Pa&iacute;s:
			<div class="form-control">
				<?=$rsDataCliente->fields["pais"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Departamento:
			<div class="form-control">
				<?=$rsDataCliente->fields["departamento"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Ciudad:
			<div class="form-control">
				<?=$rsDataCliente->fields["ciudad"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:5px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-9 labelCustom">
			Direcci&oacute;n oficina principal:
			<div class="form-control">
				<?=$rsDataCliente->fields["direccion"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-3 labelCustom">
			Tel&eacute;fono (Conmutador):
			<div class="form-control">
				<?=$rsDataCliente->fields["telefono_fijo"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Tel&eacute;fono fijo 2:
			<div class="form-control">
				<?=$rsDataCliente->fields["telefono_fijo1"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Celular:
			<div class="form-control">
			<?=$rsDataCliente->fields["telefono_celular"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Celular 1:
			<div class="form-control">
				<?=$rsDataCliente->fields["telefono_celular1"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-3 labelCustom">
			Representante legal:
			<div class="form-control">
				<?=$rsDataCliente->fields["representante_legal"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Identificaci&oacute;n:
			<div class="form-control">
				<?=$rsDataCliente->fields["identificacion_representante"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Ciudad expedición:
			<div class="form-control">
				<?=$rsDataCliente->fields["ciudad_exp"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-3 labelCustom">
			Representante legal suplente:
			<div class="form-control">
				<?=$rsDataCliente->fields["representante_supl"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Identificaci&oacute;n:
			<div class="form-control">
				<?=$rsDataCliente->fields["identificacion_representante_supl"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Ciudad expedición:
			<div class="form-control">
				<?=$rsDataCliente->fields["ciudad_suplente"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-3 labelCustom">
			Funcionario autorizado:
			<div class="form-control">
				<?=$rsDataCliente->fields["encargado"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Cargo:
			<div class="form-control">
				<?=$rsDataCliente->fields["cargo_autorizador"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Celular:
			<div class="form-control">
				<?=$rsDataCliente->fields["telefonos_encargado"]?>
			</div>
		</div>
		<div class="col-md-3 labelCustom">
			Correo funcionaro autorizado:
			<div class="form-control">
				<?=$rsDataCliente->fields["correo_personal"]?>
			</div>
		</div>
	</div>
	<div class="row-fluid alert alert-info text-center">2. INFORMACIÓN GENERAL DEL NEGOCIO</div>
	<div class="row">
		<div class="col-md-4 labelCustom">
			Sector:
			<div class="form-control">
				<?=$rsDataCliente->fields["sector"]?>
			</div>
		</div>
		<div class="col-md-6 labelCustom">
			Ciiu:
			<div class="form-control">
				<?=$rsDataCliente->fields["desc_ciiu"]?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Tipo de empresa:
			<div class="form-control">
				<?=$this->arrTipoEmpresa[$rsDataCliente->fields["tipo_empresa1"]]?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2 labelCustom">
			Tipo de régimen:
			<div class="form-control">
				<?=$this->arrTipos[$rsDataCliente->fields["tipo_empresa"]]?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Gran Contribuyente:
			<div class="form-control" style="padding-top:0px;">
				<?php
					$c_radio = new Radio;
					$arrSiNo = array("1"=>"Si","2"=>"No");
					$c_radio->Radio("gran_contribuyente","gran_contribuyente",$arrSiNo,"", 1, $rsDataCliente->fields["gran_contribuyente"], "", 0, "");
					while($tmp_html = $c_radio->next_entry()) {
						echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
					}
				?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Auto Retenedor:
			<div class="form-control" style="padding-top:0px;">
				<?php
					$c_radio = new Radio;
					$arrSiNo = array("1"=>"Si","2"=>"No");
					$c_radio->Radio("autoretenedor","autoretenedor",$arrSiNo,"", 1, $rsDataCliente->fields["autoretenedor"], "", 0, "");
					while($tmp_html = $c_radio->next_entry()) {
						echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
					}
				?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Retenedor IVA:
			<div class="form-control" style="padding-top:0px;">
				<?php
					$c_radio = new Radio;
					$arrSiNo = array("1"=>"Si","2"=>"No");
					$c_radio->Radio("rete_iva","rete_iva",$arrSiNo,"", 1, $rsDataCliente->fields["rete_iva"], "", 0, "");
					while($tmp_html = $c_radio->next_entry()) {
						echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
					}
				?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-2 labelCustom">
			Retenedor ICA:
			<div class="form-control" style="padding-top:0px;">
				<?php
					$c_radio = new Radio;
					$arrSiNo = array("1"=>"Si","2"=>"No");
					$c_radio->Radio("rete_ica","rete_ica",$arrSiNo,"", 1, $rsDataCliente->fields["rete_ica"], "", 0, "");
					while($tmp_html = $c_radio->next_entry()) {
						echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
					}
				?>
			</div>
		</div>
		<div class="col-md-2 labelCustom txt_tarifa_ica">
			Tarifa ICA:
			<div class="form-control">
				<?=$rsDataCliente->fields["tarifa_ica"]?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Ventas netas <?=date("Y")-2?>:
			<div class="form-control">
				<?=formato_moneda($rsDataCliente->fields["evolucion_vta_anio_anterior"])?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Ventas netas <?=date("Y")-1?>:
			<div class="form-control">
				<?=formato_moneda($rsDataCliente->fields["evolucion_vta_anio_actual"])?>
			</div>
		</div>
		<div class="col-md-2 labelCustom">
			Número empleados:
			<div class="form-control">
				<?=$rsDataCliente->fields["numero_empleados"]?>
			</div>
		</div>
	</div>
	<div class="row" style="height:1px;">&nbsp;</div>
	<div class="row">
		<div class="col-md-8 labelCustom">
			Detalle su objeto social, servicio / productos / actividad:
			<div class="form-control">
				<?=$rsDataCliente->fields["detalle_producto"]?>
			</div>
		</div>
		<div class="col-md-4 labelCustom">
			Cómo se enteró de nuestra compañia:
			<div class="form-control">
				<?=$rsDataCliente->fields["referencia"]?>
			</div>
		</div>
	</div>
	<div class="row-fluid alert alert-info text-center">3. CLIENTES CON LOS QUE QUIERE HACER FACTORING</div>
	<div class="row-fluid">

		<table border="1" class="table table-border" style="width:100%;font-size:8px;" >
			<thead>
				<tr>
					<th style="text-align:center" valign="middle">Razón Social</th>
					<th style="text-align:center" valign="middle">Nit</th>
					<th style="text-align:center" valign="middle">% de Ventas totales<br/> de la empresa</th>
					<th style="text-align:center" valign="middle">Plazo real de Pago</th>
					<th style="text-align:center" valign="middle">¿Hace cuanto trabajas<br/> con éste cliente?</th>
				</tr>
			</thead>
			<tbody>
			<?php

				while(!$rsRefClientes->EOF)
				{

					echo "<tr>";
					echo "<td>".$rsRefClientes->fields["empresa"]."</td>";
					echo "<td>".$rsRefClientes->fields["nit"]."</td>";
					echo "<td style='text-align:center'>".$rsRefClientes->fields["porcentaje_vtas"]."%</td>";
					echo "<td style='text-align:center'>".$rsRefClientes->fields["plazo"]."</td>";
					echo "<td style='text-align:center'>".$rsRefClientes->fields["relacion_comercial"]."</td>";
					echo "</tr>";
					$rsRefClientes->MoveNext();
				}
			?>
			</tbody>
		</table>
	</div>
	<div class="saltoPagina"></div>
	<div class="row-fluid alert alert-info text-center">4. CONOCIMIENTO DE SOCIOS O ACCIONISTAS Y REPRESENTANTE LEGAL (PRINCIPAL Y SUPLENTES)</div>
	<div class="row-fluid">
		Los socios o accionistas y los representantes legales de la entidad que represento son los que se relacionan a continuación (para persona jurídica se debe discriminar información de socios o accionistas y representantes legales hasta llegar a persona natural):
		<table border="1" class="table table-border" style="width:100%;font-size:8px;" >
			<thead>
				<tr>
					<th style="text-align:center" valign="middle">Tipo de Persona</th>
					<th style="text-align:center" valign="middle">Tipo de Documento</th>
					<th style="text-align:center" valign="middle">No. Identificación</th>
					<th style="text-align:center" valign="middle">Nombre/Razón Social</th>
					<th style="text-align:center" valign="middle">País Residencia/ Ubicación</th>
					<th style="text-align:center" valign="middle">¿Es (PEP’s)?</th>
					<th style="text-align:center" valign="middle">¿Tiene algún vínculo con(PEP’s)?</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$tieneDatos = false;
				while(!$rsSocios->EOF)
				{

					echo "<tr>";
					echo "<td>".$rsSocios->fields["tipo_persona"]."</td>";
					echo "<td>".$arrTipoDocumento[$rsSocios->fields["id_tipo_documento"]]."</td>";
					echo "<td style='text-align:left'>".$rsSocios->fields["identificacion"]."</td>";
					echo "<td style='text-align:left'>".$rsSocios->fields["razon_social"]."</td>";
					echo "<td style='text-align:left'>".$rsSocios->fields["pais_ubicacion"]."</td>";
					echo "<td style='text-align:center'>".($rsSocios->fields["politicamente_expuesta"]==1?"SI":"NO")."</td>";
					echo "<td style='text-align:center'>".($rsSocios->fields["tipo_vinculacion_persona"]==1?"SI":"NO")."</td>";
					echo "</tr>";
					$tieneDatos = true;
					$rsSocios->MoveNext();
				}

				if (!$tieneDatos)
					echo "<tr><td colspan='9'>No registra información</td></tr>";
			?>
			</tbody>
		</table>
	</div>
	<div class="row-fluid alert alert-info text-center">5. CONOCIMIENTO DE BENEFICIARIO FINAL</div>
	<div class="row-fluid">
		Los beneficiarios finales son los que se relacionan a continuación (para persona jurídica se debe discriminar información de socios o accionistas y representantes legales hasta llegar a persona natural):
		<table border="1" class="table table-border" style="width:100%;font-size:8px;" >
			<thead>
				<tr>
					<th style="text-align:center" valign="middle">Tipo de Persona</th>
					<th style="text-align:center" valign="middle">Tipo de Documento</th>
					<th style="text-align:center" valign="middle">No. Identificación</th>
					<th style="text-align:center" valign="middle">Nombre/Razón Social</th>
					<th style="text-align:center" valign="middle">País Residencia/ Ubicación</th>
					<th style="text-align:center" valign="middle">¿Es (PEP’s)?</th>
					<th style="text-align:center" valign="middle">¿Tiene algún vínculo con(PEP’s)?</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$tieneDatos = false;
				while(!$rsBeneficiarios->EOF)
				{

					echo "<tr>";
					echo "<td>".$rsBeneficiarios->fields["tipo_persona"]."</td>";
					echo "<td>".$arrTipoDocumento[$rsBeneficiarios->fields["id_tipo_documento"]]."</td>";
					echo "<td style='text-align:left'>".$rsBeneficiarios->fields["identificacion"]."</td>";
					echo "<td style='text-align:left'>".$rsBeneficiarios->fields["razon_social"]."</td>";
					echo "<td style='text-align:left'>".$rsBeneficiarios->fields["pais_ubicacion"]."</td>";
					echo "<td style='text-align:center'>".($rsBeneficiarios->fields["politicamente_expuesta"]==1?"SI":"NO")."</td>";
					echo "<td style='text-align:center'>".($rsBeneficiarios->fields["tipo_vinculacion_persona"]==1?"SI":"NO")."</td>";
					echo "</tr>";
					$tieneDatos = true;
					$rsBeneficiarios->MoveNext();
				}

				if (!$tieneDatos)
					echo "<tr><td colspan='9'>No registra información</td></tr>";
			?>
			</tbody>
		</table>
	</div>
	<div class="row-fluid alert alert-info text-center">6. GESTION DE LA/FT Y DECLARACION DE ORIGEN DE FONDOS Y/O BIENES</div>
	<div class="row-fluid" style="text-align:justify;">
		En aplicación del Estatuto Orgánico del Sistema Financiero – Decreto 663 de 1993, Ley 190 de 1995 (Estatuto Anticorrupción), Circular Externa No.0170 de 2002 expedida por la DIAN, Resolución 285 de 2007, Decreto 2883 de 2008, y en observancia a lo establecido en la Circular 100-000005 del 17 de junio de 2014 expedida por la Superintendencia de Sociedades:
		<br/><br/>
		El suscrito representante legal de la sociedad <?=$rsDataCliente->fields["razon_social"]?>, tributariamente identificada con número <?=$rsDataCliente->fields["identificacion"]?>, mediante la firma del presente documento declara y hace constar que:
		<br/><br/>
		a. Ha leído, conoce y acepta la POLÍTICA DE LAVADO DE ACTIVOS Y FINANCIACIÓN DEL TERRORISMO, y el MANUAL DE SISTEMA DE AUTOGESTIÓN DE RIESGO DE LAVADO DE ACTIVOS Y FINANCIACIÓN DEL TERRORISMO, que tiene implementado ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1.
		<br/>
		b. Prestará colaboración en cualquier requerimiento dispuesto por la UIAF*** con el fin de detectar actividades de lavados de activos, financiación del terrorismo y proliferación de armas de destrucción masiva.
		<br/>
		c. Acepta y autoriza a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1, a realizar consultas en listas internacionales y locales LA/FT, así mismo el reporte de coincidencias de información ante los órganos encargados.
		<br/>
		d. Mantendrá libre de detrimento o prejuicio a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1, por cualquier multa que sea impuesta a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1, por o con ocasión de omisión o incumplimiento de las disposiciones de prevención de LA/FT y proliferación de armas de destrucción masiva, durante las relacionaciones comerciales establecidas entre las partes.
		<br/>
		e. Indemnizará, compensará, y/o reintegrará a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1, por cualquier multa que le sea impuesta a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1, por o con ocasión de omisión o incumplimiento de las disposiciones de prevención de LA/FT y proliferación de armas de destrucción masiva, durante las relacionaciones comerciales establecidas entre las partes.
		<br/>
		f. Declara que los recursos que entregamos y los bienes que figuran a nuestro nombre no provienen de ninguna actividad ilícita de las contempladas en el Código Penal Colombiano o en cualquier norma que lo modifique o adicione.
		<br/>
		g. No admite que terceros efectúen depósitos a nuestras cuentas con fondos provenientes de las actividades ilícitas contempladas en el Código Penal Colombiano o en cualquier norma que lo modifique o adicione, ni efectuaremos transacciones destinadas a tales actividades o a favor de personas relacionadas con las mismas. Declaramos que los bienes que poseemos provienen de (detalle el título de adquisiciones de los bienes):
		<?=$rsDataCliente->fields["declaracion_origen_fondos"]?>
	</div>
 	<div class="row">
		<div class="col-md-12" style="text-align:justify;">
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
				<div class="col-md-3">
					¿Realiza operaciones en moneda extranjera?:
					<div class="form-control" style="padding-top:0px;">
					<?php
						$c_radio = new Radio;
						$arrTipos = array("1"=>"SI","2"=>"NO");
						$c_radio->Radio("moneda_extranjera","moneda_extranjera",$arrTipos,"", 1, $rsDataCliente->fields["moneda_extranjera"], "", 0, "");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
				</div>
				<div class="col-md-9 operacion_me">
					¿Cuáles?:
					<div class="form-control">
					<?=$rsDataCliente->fields["transaccion_moneda"]?>
					</div>
				</div>
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
				<div class="col-md-3">
					¿Posee cuentas en moneda extranjera?
					<div class="form-control" style="padding-top:0px;">
					<?php
					$arrMoneda = array("1"=>"SI","2"=>"NO");
					$c_radio->Radio("cuentas_moneda_extranjera","cuentas_moneda_extranjera",$arrMoneda,"", 1, $rsDataCliente->fields["cuentas_moneda_extranjera"], "", 0, "");
					while($tmp_html = $c_radio->next_entry()) {
						echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
					}
					?>
					</div>
				</div>
				<div class="col-md-3 cuentas_me">
					Banco:
					<div class="form-control">
					<?=$rsDataCliente->fields["banco_me"]?>
					</div>
				</div>
				<div class="col-md-3 cuentas_me">
					Cuenta:
					<div class="form-control">
					<?=$rsDataCliente->fields["cuenta_nro_me"]?>
					</div>
				</div>
				<div class="col-md-3 cuentas_me">
					Moneda:
					<div class="form-control">
					<?=$rsDataCliente->fields["moneda_me"]?>
					</div>
				</div>
			</div>
			<div class="row cuentas_me" style="height:10px;">&nbsp;</div>
			<div class="row cuentas_me">
				<div class="col-md-3">
					Ciudad:
					<div class="form-control">
					<?=$rsDataCliente->fields["ciudad_me"]?>
					</div>
				</div>
				<div class="col-md-3">
					País:
					<div class="form-control">
					<?=$rsDataCliente->fields["pais_me"]?>
					</div>
				</div>
			</div>
			<div class="row" style="height:10px;">&nbsp;</div>
			<div class="row">
				<div class="col-md-3">
					¿Administra recursos públicos?
					<div class="form-control" style="padding-top:0px;">
					<?php
						$c_radio = new Radio;
						$c_radio->Radio("recursos_publicos","recursos_publicos",$arrSiNo,"", 1, $rsDataCliente->fields["recursos_publicos"], "", 0, "customValidateRadio('recursos_publicos');");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid alert alert-info text-center">7. DECLARACION DE INFORMACIÓN Y AUTORIZACIONES</div>
	<div class="row-fluid" style="text-align:justify;">
		Para los fines previstos en el Art. 83 de la Constitución Política de Colombia, declaramos bajo la gravedad de expresión de la verdad. Nos obligamos a entregar información veraz y verificable. Autorizamos a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit. 900.518.469-1 para inhabilitar y dar por terminado el producto o servicio, en el evento de que la información aquí suministrada sea errónea, falsa o inexacta o que no sea posible su confirmación por motivos ajenos a ARGENTA ESTRUCTURADORES S.A.S. identificada con Nit. 900.518.469-1. Autorizamos irrevocablemente a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit. 900.518.469-1 para que en caso de que esta solicitud sea negada, destruya todos los documentos que hemos aportado. Así mismo, autorizamos a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit.900.518.469-1 o a la entidad que éste designe, para realizar las verificaciones y consultas sobre la información comercial y financiera de la empresa, sus socios y administradores. En el mismo sentido, ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit. 900.518.469-1  podrá suministrar cualquier tipo de información requerida por las entidades de riesgo y registro de deudores morosos, Asociación Bancaria, entidades financieras o de cualquier entidad que se establezca con este propósito. Desde el momento de nuestra vinculación como cliente de ARGENTA ESTRUCTURADORES S.A.S.,identificada con Nit.900.518.469-1, nos obligamos y comprometemos a actualizar, minimo una vez al año, cualquier cambio de dirección y/o actividad económica, suministrando los soportes documentales respectivos, así como la información financiera, tributaria y comercial.
	</div>
	<div class="saltoPagina"></div>
	<div class="row-fluid alert alert-info text-center">8. AUTORIZACIÓN TRATAMIENTO DE DATOS</div>
	<div class="row-fluid" style="text-align:justify;">
		En cumplimiento de la Ley estatutaria 1581 de 2012 por la cual se establece el régimen general de protección de datos y reglamentada parcialmente por el Decreto Nacional 1377 de 2013, reglamentada parcialmente por el Decreto 1081 de 2015:
		<br/><br/>
		El suscrito representante legal de la sociedad <?=$rsDataCliente->fields["razon_social"]?>, tributariamente identificada con número <?=$rsDataCliente->fields["identificacion"]?>, mediante la firma del presente documento declara y hace constar que:
		<br/><br/>
		a. Autoriza a ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit. 900.518.469-1, para que realice la recolección, almacenamiento, uso, circulación, supresión, y en general, el tratamiento de los datos personales, incluyendo datos sensibles.
		<br/>
		b. Declara ser informado de manera clara y comprensible los derechos a conocer, actualizar y rectificar los datos personales proporcionados, a solicitar prueba de esta autorización, a solicitar información sobre el uso que se les ha dado a los datos personales, a presentar reclamo por el uso indebido de los datos personales, a revocar esta autorización o solicitar la supresión de los datos personales suministrados y a acceder de forma gratuita a los mismos a través del correo electrónico.
		<br/>
		c. Declara conocer y aceptar la política de Tratamiento de Datos Personales de ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit. 900.518.469-1 y que la información proporcionada es veraz, completa, exacta, actualizada y verificable.
		<br/>
		d. Reconoce y acepta que cualquier consulta o reclamación relacionada con el tratamiento de los datos personales podrá ser elevada por escrito ante ARGENTA ESTRUCTURADORES S.A.S., identificada con Nit. 900.518.469-1 al teléfono 7946502 o al correo eleyva@argentaestructuradores.com como responsable del tratamiento.
		<br/>
		e. Manifiesta bajo la gravedad de juramento que la sociedad a la cual representa, no se encuentra incursa en ninguna causal de liquidación voluntaria u obligatoria, ni ha iniciado trámite alguno tendiente a ser admitida en proceso de reestructuración de acuerdo con la Ley 1116 de 2006 o para ser admitida a concordato.
		<br/><br/>
	</div>
	<div class="row">
		<div class="col-md-2">
			<table border="1">
			  <tr>
				<td style="width:105px;height:80px;">&nbsp;</td>
			  </tr>
			</table>
			<div>Impresión Dactilar</div>
		</div>
		<div class="col-md-3">
			<br/><br/><br/><br/><br/>
			<div>______________________________</div>
			<div>FIRMA REPRESENTANTE LEGAL</div>
			<div><?=$rsDataCliente->fields["representante_legal"]?></div>
		</div>
	</div>
	<div class="row-fluid alert alert-info text-center">9.  ESPACIO DE USO EXCLUSIVO PARA ARGENTA ESTRUCTURADORES S.A.S.</div>
	<div class="row-fluid">
		<table border="1" class="table "width="100%">
			<tr>
				<td width="300px;"><b>Se verificó listas SAGRILAFT:</b></td>
				<td width="600px;">
				<?php
						$c_radio = new Radio;
						$c_radio->Radio("nada_1","nada_1",$arrSiNo,"", 1, "", "", 0, "");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
				?>
				</td>
				<td colspan="3">Fecha de consulta:</td>
			</tr>
			<tr>
				<td colspan="2">Observaciones:</td>
				<td rowspan="2">DD</td>
				<td rowspan="2">MM</td>
				<td rowspan="2">AAAA</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>Nombre del funcionario que realiza la verificación:</td>
				<td></td>
				<td colspan="3">Fecha verificación</td>
			</tr>
			<tr>
				<td>Oficial de Cumplimiento:</td>
				<td></td>
				<td rowspan="2">DD</td>
				<td rowspan="2">MM</td>
				<td rowspan="2">AAAA</td>
			</tr>
			<tr>
				<td>Firma del funcionario:</td>
				<td></td>
			</tr>
		</table>
		<table border="1" class="table "width="100%">
			<tr>
				<td width="300px;"><b>Se realizó el conocimiento del cliente:</b></td>
				<td width="600px;">
				<?php
						$c_radio = new Radio;
						$c_radio->Radio("nada_1","nada_1",$arrSiNo,"", 1, "", "", 0, "");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
				?>
				</td>
				<td colspan="3">Fecha de consulta:</td>
			</tr>
			<tr>
				<td colspan="2">Observaciones:</td>
				<td rowspan="2">DD</td>
				<td rowspan="2">MM</td>
				<td rowspan="2">AAAA</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>Nombre del funcionario que realiza la verificación:</td>
				<td></td>
				<td colspan="3">Fecha verificación</td>
			</tr>
			<tr>
				<td>Oficial de Cumplimiento:</td>
				<td></td>
				<td rowspan="2">DD</td>
				<td rowspan="2">MM</td>
				<td rowspan="2">AAAA</td>
			</tr>
			<tr>
				<td>Firma del funcionario:</td>
				<td></td>
			</tr>
		</table>
		<table border="1" class="table "width="100%">
			<tr>
				<td width="300px;"><b>Se realizó el conocimiento de la operación:</b></td>
				<td width="600px;">
				<?php
						$c_radio = new Radio;
						$c_radio->Radio("nada_1","nada_1",$arrSiNo,"", 1, "", "", 0, "");
						while($tmp_html = $c_radio->next_entry()) {
							echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
						}
				?>
				</td>
				<td colspan="3">Fecha de consulta:</td>
			</tr>
			<tr>
				<td colspan="2">Observaciones:</td>
				<td rowspan="2">DD</td>
				<td rowspan="2">MM</td>
				<td rowspan="2">AAAA</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>Nombre del funcionario que realiza la verificación:</td>
				<td></td>
				<td colspan="3">Fecha verificación</td>
			</tr>
			<tr>
				<td>Oficial de Cumplimiento:</td>
				<td></td>
				<td rowspan="2">DD</td>
				<td rowspan="2">MM</td>
				<td rowspan="2">AAAA</td>
			</tr>
			<tr>
				<td>Firma del funcionario:</td>
				<td></td>
			</tr>
		</table>
	</div>
	<div style="font-size:6px;">
	*PEP: significa personas expuestas políticamente, es decir, son los servidores públicos de cualquier sistema de nomenclatura y clasificación de empleos de la administración pública nacional y territorial, cuando en los cargos que ocupen, tengan en las funciones del área a la que pertenecen o en las de la ficha del empleo que ocupan, bajo su responsabilidad directa o por delegación, la dirección general, de formulación de políticas institucionales y de adopción de planes, programas y proyectos, el manejo directo de bienes, dineros o valores del Estado. Estos pueden ser a través de ordenación de gasto, contratación pública, gerencia de proyectos de inversión, pagos, liquidaciones, administración de bienes muebles e inmuebles. Incluye también a las PEP Extranjeras y las PEP de Organizaciones Internacionales.
	**Beneficiario Final: es la(s) persona(s) natural(es) que finalmente posee(n) o controla(n) a un cliente o a la persona natural en cuyo nombre se realiza una transacción. Incluye también a la(s) persona(s) que ejerzan el control efectivo y/o final, directa o indirectamente, sobre una persona jurídica u otra estructura sin personería jurídica.
	***UIAF: Unidad de Información Y Análisis Financiero.

	</div>

</div>

