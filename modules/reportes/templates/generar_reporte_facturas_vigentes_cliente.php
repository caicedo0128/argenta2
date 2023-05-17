<script>
$(document).ready(function() {
    oTableExport = $('#tableDataFacturas').dataTable({ "paging": false, "bStateSave": false, "bInfo": false, "bSort": false, "searching": false });
});

</script>
<div class="row-fluid">
	<div class="col-md-12 bg-primary-custom">
		<h4>Resultado de la consulta</h4>
	</div>
	<div id="content_operaciones" class="" style="clear:both;padding-top:10px;">
		<div style="height: 40px;" class="row-fluid">
			<div class="agregar_registro text-right">
				<a href="javascript:;" title="Exportar" onclick="generarReporteFacturas('EXP');" class="btn btn-success btn-sm"><i class="fa fa-download fa-lg"></i>Exportar</a>
			</div>
		</div>

		<?php

			echo "<table id='tableDataFacturas' border='0' class='table table-striped table-bordered' cellspacing='0' style='width:100%;font-size:10px !important;'>";
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
	</div>
</div>