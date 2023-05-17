    <div class="panel panel-primary col-md-offset-3 col-md-6  ">
        <div class="panel-body reporte-cliente">
            <img id="logoArgenta" src="./images/logo.png" class="marca-de-agua">
            <br/><br/>
            <table width="100%" style="color:#6d6d6d;">
                <tr>
                    <th>Número factura</th>
                    <th>Valor neto</th>
                    <th>Valor giro final</th>
                    <th>Inversionista</th>
                    <th>Valor participación</th>
                </tr>
                <tr>
                    <td colspan="5"><hr style="border-color:#449d44;"></td>
                </tr> 
				<?php
					$auxFactura = "";
					while (!$rsInversionistas->EOF){
					
						$numFactura = "";
						$valorNeto = "";
						$valorGiroFinal = "";
						if ($auxFactura != $rsInversionistas->fields["num_factura"]){
							$numFactura = $rsInversionistas->fields["num_factura"];
							$valorNeto = formato_moneda($rsInversionistas->fields["valor_neto"]);
							$valorGiroFinal = formato_moneda($rsInversionistas->fields["valor_giro_final"]);	
							
							if ($auxFactura != ""){
								echo "<tr>";
								echo "<td colspan=\"5\"><hr style=\"border-color:#449d44;\"></td>";
								echo "</tr>";
							}
							$auxFactura = $rsInversionistas->fields["num_factura"];
						}
				?>	
					<tr>
						<td><?=$numFactura?></td>
						<td align="right"><?=$valorNeto?></td>
						<td align="right"><?=$valorGiroFinal?></td>
						<td><b><?=$rsInversionistas->fields["razon_social"]?></b></td>
						<td align="right"><?=formato_moneda($rsInversionistas->fields["valor_participacion"])?></td>
					</tr>				
				<?php
						$rsInversionistas->MoveNext();
					}
				?>                
                <tr>
                    <td colspan="5"><hr style="border-color:#449d44;"></td>
                </tr>                 
            </table>
            <input type="button" class="btn btn-success" value="Volver" onclick="cargarInversionistas()">
    </div>
    <br/>
    </div> 


