<?php
    header("Content-Type:application/vnd.ms-excel; charset=utf-8");
    header("Content-type:application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteEstadoCuenta.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<table>
    <tr>
        <td colspan="6">
            <h1>Reporte estado de cuenta - facturas vigentes</h1>
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
<?php

	echo "<table border='1' class='' cellspacing='0'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Emisor</th>";
	echo "<th>Pagador</th>";
	echo "<th>No. operación</th>";
	echo "<th>Fecha operación</th>";
	echo "<th>No. Factura</th>";
	echo "<th>Fecha de emisión</th>";
	echo "<th>Fecha de vencimiento</th>";
	echo "<th>Fecha pago pactada</th>";
	echo "<th>Días vencidos</th>";
	echo "<th>Valor neto</th>";
	echo "<th>% Descuento</th>";
	echo "<th>% Factor</th>";
	echo "<th>Valor futuro</th>";
	echo "<th>Otros</th>";
	echo "<th>Giro antes GMF</th>";
	echo "<th>GMF</th>";
	echo "<th>Valor giro final</th>";
	echo "<th>Estado</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

    //RECORREMOS ANTES EL RESULTADO DE DATOS PARA PODER AGRUPAR LAS FACTURAS CON ABONOS
    while (!$rsDatos->EOF){

       $diasVencidos = date_diff_custom($rsDatos->fields["fecha_pago"], date("Y-m-d"));

		echo "<tr>";
		echo "<td>".$rsDatos->fields["emisor"]."</td>";
		echo "<td>".$rsDatos->fields["pagador"]."</td>";
		echo "<td>".$rsDatos->fields["id_operacion"]."</td>";
		echo "<td>".$rsDatos->fields["fecha_operacion"]."</td>";
		echo "<td>".$rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"]."</td>";
		echo "<td>".$rsDatos->fields["fecha_emision"]."</td>";
		echo "<td>".$rsDatos->fields["fecha_vencimiento_factura"]."</td>";
		echo "<td>".$rsDatos->fields["fecha_pago"]."</td>";
		echo "<td>".$diasVencidos["d"]."</td>";
		echo "<td>".formato_moneda($rsDatos->fields["valor_neto"])."</td>";
		echo "<td>".$rsDatos->fields["porcentaje_descuento"]."</td>";
		echo "<td>".$rsDatos->fields["factor"]."</td>";
		echo "<td>".formato_moneda($rsDatos->fields["valor_futuro"])."</td>";
		echo "<td>".($rsDatos->fields["aplica_otros"]==1?formato_moneda($rsDatos->fields["valor_otros_operacion"]):0)."</td>";
		echo "<td>".formato_moneda($rsDatos->fields["giro_antes_gmf"])."</td>";
		echo "<td>".formato_moneda($rsDatos->fields["gmf"])."</td>";
		echo "<td>".formato_moneda($rsDatos->fields["valor_giro_final"])."</td>";
		echo "<td>".($rsDatos->fields["estado"] == 1?"VIGENTE":"VIGENTE CON ABONOS")."</td>";
		echo "</tr>";
        $rsDatos->MoveNext();

    }

    echo "</tbody>";
    echo "</table>";

?>