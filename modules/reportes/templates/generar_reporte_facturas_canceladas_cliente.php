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

			echo "<table id='tableDataFacturas' border='1' class='table table-striped table-bordered nowrap' cellspacing='0' style='width:100%;'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Emisor</th>";
			echo "<th>Pagador</th>";
			echo "<th>Fecha operación</th>";
			echo "<th>No. Factura</th>";
			echo "<th>Fecha emisión</th>";
			echo "<th>Fecha vencimiento</th>";
			echo "<th>Fecha pago</th>";
			echo "<th>Valor neto</th>";
			echo "<th>Estado</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

			$idOperacionAux = 0;
			while (!$rsDatos->EOF){

				$idOperacion = $rsDatos->fields["id_operacion"];

				echo "<tr>";
				echo "<td>".$rsDatos->fields["emisor"]."</td>";
				echo "<td>".$rsDatos->fields["pagador"]."</td>";
				echo "<td>".$rsDatos->fields["fecha_operacion"]."</td>";
				echo "<td>".$rsDatos->fields["prefijo"].$rsDatos->fields["num_factura"]."</td>";
				echo "<td>".$rsDatos->fields["fecha_emision"]."</td>";
				echo "<td>".$rsDatos->fields["fecha_vencimiento_factura"]."</td>";
				echo "<td>".$rsDatos->fields["fecha_pago"]."</td>";
				echo "<td>".formato_moneda($rsDatos->fields["valor_neto"])."</td>";
				echo "<td>CANCELADA</td>";
				echo "</tr>";
				$idOperacionAux = $idOperacion;
				$rsDatos->MoveNext();
			}
			echo "</tbody>";
			echo "</table>";

		?>
	</div>
</div>