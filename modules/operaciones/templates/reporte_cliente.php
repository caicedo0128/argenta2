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
    <div class="panel panel-primary col-md-offset-3 col-md-6  ">
        <div class="panel-body reporte-cliente">
            <img id="logoArgenta" src="./images/logo.png" class="marca-de-agua">
            <br/><br/>
            <table width="100%" style="color:#6d6d6d;">
                <tr>
                    <td><b>Nro operación:</b></td>
                    <td><?=$operacion->id_operacion?></td>
                    <td><b>Fecha operación:</b></td>
                    <td><?=$operacion->fecha_operacion?></td>
                </tr>
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr> 
                <tr>
                    <td><b>Emisor:</b></td>
                    <td colspan="3"><?=str_replace("&","AND",$emisor->razon_social)?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr> 
                <tr>
                    <td><b>Pagador:</b></td>
                    <td colspan="3"><?=str_replace("&","AND",$pagador->razon_social)?></td>
                </tr>
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>  
                <tr>
                    <td><b>Títulos negociados:</b></td>
                    <td colspan="3"><?=($arrFacturas["fac"] != null?implode(" - ",$arrFacturas["fac"]):"Sin facturas")?></td>
                </tr>
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>    
                <tr>
                    <td><b>Valor neto títulos:</b></td>
                    <td colspan="3"><?=formato_moneda($operacion->valor_neto)?></td>
                </tr>
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>  
                <tr>
                    <td><b>% Anticipo:</b></td>
                    <td><?=$operacion->porcentaje_descuento?>%</td>
                    <td><b>Factor:</b></td>
                    <td><?=$operacion->factor?>%</td>
                </tr>   
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>  
                <tr>
                    <td><b>Plazo operación días:</b></td>
                    <td colspan="3"><?=($arrFacturas["dias"] != null?implode(" - ",$arrFacturas["dias"]):"Sin facturas")?></td>
                </tr> 
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>  
                <tr>
                    <td><b>Costo financiero:</b></td>
                    <td colspan="3"><?=formato_moneda($operacion->descuento_total)?></td>
                </tr>   
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>  
                <tr>
                    <td><b>Valor giro antes GMF:</b></td>                   
                    <td><?=formato_moneda($operacion->giro_antes_gmf)?></td>
                    <td> </td>
                    <td> </td>
                </tr>   
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>                 
                <tr>
                    <td><b>GMF:</b></td>
                    <td><?=formato_moneda($operacion->gmf)?></td>
                    <td><b>Otros:</b></td>                   
                    <td><?=formato_moneda($operacion->valor_otros_operacion)?></td>
                </tr>  
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>      
                <tr>
                    <td><b>Valor final girado:</b></td>                 
                    <td colspan="3"><?=formato_moneda($operacion->valor_giro_final)?></td>
                </tr>  
                <tr>
                    <td colspan="4"><hr style="border-color:#449d44;"></td>
                </tr>                 
            </table>
    </div>
    <br/>
    </div> 


