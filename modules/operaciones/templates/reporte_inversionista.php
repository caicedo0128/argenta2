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
    <div class="panel-body reporte-inversionista">
        <img id="logoArgenta" src="./images/logo.png" class="marca-de-agua">
        <br/><br/>
        <table width="100%" style="color:#6d6d6d;"> 
            <tr>
                <td><b>Emisor:</b></td>
                <td colspan="3"><?=$emisor->razon_social?></td>
            </tr>
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr> 
            <tr>
                <td><b>Pagador:</b></td>
                <td colspan="3"><?=$pagador->razon_social?></td>
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
                <td colspan="4"><b>Desembolsos</b></td>
            </tr>                 
            <tr>
                <td colspan="4">
                    <table width="100%" class="" border="1" cellspacing="2" cellpadding="2">
                        <tr>
                            <td>Cliente</td>
                            <td>Valor</td>
                            <td>Fecha</td>
                        </tr>   
                        <?php
                            $numeroDesembolsos = 0;
                            $totalDesembolsos = 0;
                            while(!$rsDesembolsos->EOF)
                            {
                        ?>
                                <tr>
                                    <td><?=($rsDesembolsos->fields["tercero"] != ""?$rsDesembolsos->fields["tercero"]:$rsDesembolsos->fields["razon_social"])?></td>
                                    <td align="right"><?=formato_moneda($rsDesembolsos->fields["valor"])?></td>
                                    <td align="right"><?=$rsDesembolsos->fields["fecha_desembolso"]?></td>
                                </tr>
                        <?php 
                                $numeroDesembolsos++;
                                $totalDesembolsos += $rsDesembolsos->fields["valor"];
                                $rsDesembolsos->MoveNext();
                            }
                        ?>                            
                    </table>
                </td>
            </tr>   
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr>                 
            <tr>
                <td><b>Número desembolsos:</b></td>
                <td colspan="3"><?=$numeroDesembolsos?></td>
            </tr>   
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr>  
            <tr>
                <td><b>Valor inversión:</b></td>
                <td colspan="3"><?=formato_moneda($totalDesembolsos)?></td>
            </tr>       
            <?php
                if ($operacion->monto_argenta != 0){
            ?>        
                    <tr>
                        <td colspan="4"><hr style="border-color:#449d44;"></td>
                    </tr>  
                    <tr>
                        <td><b>Monto invertido Argenta:</b></td>
                        <td colspan="3"><?=formato_moneda($operacion->monto_argenta)?></td>
                    </tr>  
            <?php
                }
            ?>                  
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr>  
            <tr>
                <td><b>Monto invertido Savile:</b></td>
                <?php
                    $montoSavile = $totalDesembolsos + $operacion->monto_argenta;
                ?>                      
                <td colspan="3"><?=formato_moneda($montoSavile)?></td>
            </tr>   
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr>                 
            <tr>
                <td><b>% Desembolso:</b></td>
                <?php
                    $desembolso = 0;
                    if ($operacion->valor_neto > 0)
                        $desembolso = ($montoSavile / $operacion->valor_neto);
                ?>                     
                <td colspan="3"><?=Round($desembolso * 100,2)?>%</td>
            </tr>  
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr>      
            <tr>
                <td><b>Fecha vencimiento:</b></td>                  
                <td colspan="3"><?=$operacion->fecha_vencimiento?></td>
            </tr>  
            <tr>
                <td colspan="4"><hr style="border-color:#449d44;"></td>
            </tr>                 
        </table>        
</div>  
<br/>
</div> 


