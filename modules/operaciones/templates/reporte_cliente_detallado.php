<?php
    //IMPRIMIMOS EL SUMARIO DE LA OPERACION
    if (!$esReporte){
        $this->sumarioOperacion($idOperacion);
?>   
        <div class="col-md-12 bg-primary-custom">
            <h4>Reporte cliente</h4>
        </div>  
        <br/><br/><br/><br/>
<?php
    }
?>
    <div class="panel panel-primary col-md-offset-2 col-md-8  ">
        <div class="panel-body reporte-cliente" style="font-size:10px;">
            <img id="logoArgenta" src="./images/logo.png" class="marca-de-agua">
            <br/><br/>            
            <b>Estimados:
            <br/>          	
            <?=$emisor->razon_social?>
            </b>
            <br/><br/>  
            La siguiente relación hace referencia a la liquidación de las facturas negociadas el pasado <?=$operacion->fecha_operacion?>.
            <br/><br/>            
            <table width="100%" border="1" cellpadding="2">
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
                    <td colspan="3"><?=formato_moneda($operacion->valor_neto)?></td>
                </tr>
                <tr>
                    <td><b>% Anticipo:</b></td>
                    <td><?=$operacion->porcentaje_descuento?>%</td>
                    <td><b>Factor:</b></td>
                    <td><?=$operacion->factor?>%</td>
                </tr>   
                <tr>
                    <td><b>Costo financiero:</b></td>
                    <td colspan="3"><?=formato_moneda($operacion->descuento_total)?></td>
                </tr>   
                <tr>
                    <td><b>Valor giro antes GMF:</b></td>                   
                    <td><?=formato_moneda($operacion->giro_antes_gmf)?></td>
                    <td></td>
                    <td></td>
                </tr>   
                <tr>
                    <td><b>GMF:</b></td>
                    <td><?=formato_moneda($operacion->gmf)?></td>
                    <td><b>Otros:</b></td>                   
                    <td><?=formato_moneda($operacion->valor_otros_operacion)?></td>
                </tr>  
                <tr>
                    <td><b>Valor final girado:</b></td>                 
                    <td colspan="3"><?=formato_moneda($operacion->valor_giro_final)?></td>
                </tr>  
            </table>
            <br/><br/>
            <b><i>Detalle facturas</i></b>
            <br/><br/>
			<table width="100%" border="1" cellpadding="2" style="font-size:7px;">
				<tr>
                    <th>Fecha pago</th>
                    <th>Nro factura</th>
                    <th>Valor neto</th>
                    <th>Valor futuro</th>
                    <th>Plazo días</th>
                    <th>Costo financiero total</th>
                    <th>Interés corriente</th>
                    <th>Gestión de referenciación</th>
                    <th>Iva factura</th>
                    <th>Factura Argenta</th>                
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
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["valor_neto"])."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["valor_futuro"])."</td>";
                            echo "<td align='right'>".$diasDiferencia."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["descuento_total"])."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["margen_inversionista"])."</td>";
                            echo "<td align='right'>".formato_moneda(($arrDetalleFacturas->fields["descuento_total"] - $arrDetalleFacturas->fields["margen_inversionista"]))."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["iva_fra_asesoria"])."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["fra_argenta"])."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["giro_antes_gmf"])."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["gmf"])."</td>";
                            echo "<td align='right'>".formato_moneda($arrDetalleFacturas->fields["valor_giro_final"])."</td>";
                            echo "</tr>";
                            $totalValorNeto += $arrDetalleFacturas->fields["valor_neto"];
                            $totalValorFuturo += $arrDetalleFacturas->fields["valor_futuro"];
                            $totalDescuentoTotal += $arrDetalleFacturas->fields["descuento_total"];
                            $totalMargenInversionista += $arrDetalleFacturas->fields["margen_inversionista"];
                            $totalGestion += ($arrDetalleFacturas->fields["descuento_total"] - $arrDetalleFacturas->fields["margen_inversionista"]);
                            $totalIva += $arrDetalleFacturas->fields["iva_fra_asesoria"];
                            $totalFactura += $arrDetalleFacturas->fields["fra_argenta"];
                            $totalGiroGMF += $arrDetalleFacturas->fields["giro_antes_gmf"];
                            $totalGMF += $arrDetalleFacturas->fields["gmf"];
                            $totalGiroFinal += $arrDetalleFacturas->fields["valor_giro_final"];

                            $arrDetalleFacturas->MoveNext();

                        }
                        
                        echo "<tr>";
                        echo "<td></td>";
                        echo "<td>TOTALES:</td>";
                        echo "<td align='right'>".formato_moneda($totalValorNeto)."</td>";
                        echo "<td align='right'>".formato_moneda($totalValorFuturo)."</td>";
                        echo "<td align='right'></td>";
                        echo "<td align='right'>".formato_moneda($totalDescuentoTotal)."</td>";
                        echo "<td align='right'>".formato_moneda($totalMargenInversionista)."</td>";
                        echo "<td align='right'>".formato_moneda($totalGestion)."</td>";
                        echo "<td align='right'>".formato_moneda($totalIva)."</td>";
                        echo "<td align='right'>".formato_moneda($totalFactura)."</td>";
                        echo "<td align='right'>".formato_moneda($totalGiroGMF)."</td>";
                        echo "<td align='right'>".formato_moneda($totalGMF)."</td>";
                        echo "<td align='right'>".formato_moneda($totalGiroFinal)."</td>";
                        echo "</tr>";        
                    ?>                
			</table>  
            <br/><br/><br/><br/>  
            Cordialmente,
            <br/><br/><br/><br/><br/> 
            Departamento de operaciones.
            <br/>
            ARGENTA ESTRUCTURADORES S.A.S.
            <br/>
            Nit. 900.518.469-1
            <br/>
            Calle 94 No. 11ª 76 Oficina 102 - Bogotá D.C.
            <br/>
            Tel. (1) 7429779 - info@argentaestructuradores.com
            <br/><br/> 

    </div>
    <br/>
    </div> 


