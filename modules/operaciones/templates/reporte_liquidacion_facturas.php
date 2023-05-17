<?php
    header("Content-Type:application/vnd.ms-excel; charset=utf-8");
    header("Content-type:application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteLiquidacionFacturas.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<table>
    <tr>
        <td colspan="6">
            <h1>Reporte liquidación facturas</h1>
        </td>
    </tr>
    <tr>
        <td colspan="2">Usuario: <?=$_SESSION["user"]?></td>
    </tr>
    <tr>
        <td colspan="2">Fecha: <?=date("Y-m-d")?></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
<table width="100%" border="1">
    <tr>
        <td><b>Nro operación:</b></td>
        <td><?=$operacion->id_operacion?></td>
        <td><b>Fecha operación:</b></td>
        <td><?=$operacion->fecha_operacion?></td>
    </tr>
    <tr>
        <td><b>Emisor:</b></td>
        <td colspan="3"><?=$emisor->razon_social?></td>
    </tr>
    <tr>
        <td><b>Pagador:</b></td>
        <td colspan="3"><?=$pagador->razon_social?></td>
    </tr>
    <tr>
        <td><b>Valor neto títulos:</b></td>
        <td><?=$operacion->valor_neto?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><b>% Anticipo:</b></td>
        <td><?=$operacion->porcentaje_descuento?>%</td>
        <td><b>Factor:</b></td>
        <td><?=$operacion->factor?>%</td>
    </tr>
    <tr>
        <td><b>Costo financiero:</b></td>
        <td><?=$operacion->descuento_total?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><b>Valor giro antes GMF:</b></td>
        <td><?=$operacion->giro_antes_gmf?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><b>GMF:</b></td>
        <td><?=$operacion->gmf?></td>
        <td><b>Otros:</b></td>
        <td><?=$operacion->valor_otros_operacion?></td>
    </tr>
    <tr>
        <td><b>Valor final girado:</b></td>
        <td><?=$operacion->valor_giro_final?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<br/><br/>
<b><i>Detalle facturas</i></b>
<br/><br/>
<table width="100%" border="1">
    <tr>
        <th>Fecha pago</th>
        <th>Nro factura</th>
        <th>Valor neto</th>
        <th>Valor futuro</th>
        <th>Plazo días</th>
        <th>Descuento total</th>
        <th>Interés corriente</th>
        <th>Gestión de referenciación</th>
        <?php
        	if ($_SESSION["profile_text"] != "Cliente"){
        ?>
        <th>Iva factura</th>
        <th>Factura Argenta</th>
        <?php
        	}
        ?>
        <th>Giro antes GMF</th>
        <th>GMF</th>
        <th>Valor giro final</th>
    </tr>
    <?php
        while(!$arrDetalleFacturas->EOF){

            $arrDiasDiferencia = date_diff_custom($operacion->fecha_operacion, $arrDetalleFacturas->fields["fecha_pago"]);
            $diasDiferencia = $arrDiasDiferencia["d"];
            echo "<tr>";
            echo "<td align='center'>".$arrDetalleFacturas->fields["fecha_pago"]."</td>";
            echo "<td align='center'>".$arrDetalleFacturas->fields["num_factura"]."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["valor_neto"]."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["valor_futuro"]."</td>";
            echo "<td align='right'>".$diasDiferencia."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["descuento_total"]."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["margen_inversionista"]."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["margen_argenta"]."</td>";

        	if ($_SESSION["profile_text"] != "Cliente"){
	            echo "<td align='right'>".$arrDetalleFacturas->fields["iva_fra_asesoria"]."</td>";
	            echo "<td align='right'>".$arrDetalleFacturas->fields["fra_argenta"]."</td>";
	       	}

            echo "<td align='right'>".$arrDetalleFacturas->fields["giro_antes_gmf"]."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["gmf"]."</td>";
            echo "<td align='right'>".$arrDetalleFacturas->fields["valor_giro_final"]."</td>";
            echo "</tr>";
            $totalValorNeto += $arrDetalleFacturas->fields["valor_neto"];
            $totalValorFuturo += $arrDetalleFacturas->fields["valor_futuro"];
            $totalDescuentoTotal += $arrDetalleFacturas->fields["descuento_total"];
            $totalMargenInversionista += $arrDetalleFacturas->fields["margen_inversionista"];
            $totalGestion += $arrDetalleFacturas->fields["margen_argenta"];
            $totalIva += $arrDetalleFacturas->fields["iva_fra_asesoria"];
            $totalFactura += $arrDetalleFacturas->fields["fra_argenta"];
            $totalGiroGMF += $arrDetalleFacturas->fields["giro_antes_gmf"];
            $totalGMF += $arrDetalleFacturas->fields["gmf"];
            $totalGiroFinal += $arrDetalleFacturas->fields["valor_giro_final"];

            $arrDetalleFacturas->MoveNext();

        }
		echo "<tr>";
		echo "<td colspan='2'>TOTALES:</td>";
		echo "<td align='right'>".$totalValorNeto."</td>";
		echo "<td align='right'>".$totalValorFuturo."</td>";
		echo "<td align='right'>&nbsp;</td>";
		echo "<td align='right'>".$totalDescuentoTotal."</td>";
		echo "<td align='right'>".$totalMargenInversionista."</td>";
		echo "<td align='right'>".$totalGestion."</td>";

		if ($_SESSION["profile_text"] != "Cliente"){
			echo "<td align='right'>".$totalIva."</td>";
			echo "<td align='right'>".$totalFactura."</td>";
		}

		echo "<td align='right'>".$totalGiroGMF."</td>";
		echo "<td align='right'>".$totalGMF."</td>";
		echo "<td align='right'>".$totalGiroFinal."</td>";
		echo "</tr>";
    ?>
</table>



