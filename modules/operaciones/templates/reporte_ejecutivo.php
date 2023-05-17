<style>
.table-bordered {
    border: 1px solid #ddd;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
    font-size:10px;
}

.td_border{
    border: 1px solid #ddd;
    border-top: 1px solid #ddd;
    border-right: 1px solid #ddd;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    padding: 8px;
}
</style>
<div class="panel-body reporte-ejecutivo" style="color:#6d6d6d;">
    <table width="100%" style="color:#6d6d6d;" class="">
        <tr>
            <td width="30%"><img id="logoArgenta" src="./images/logo.png" class="marca-de-agua"></td>
            <td>Reporte liquidación para ejecutivo de cuenta</td>
        </tr>                            
    </table>
    <hr style="border-color:#449d44;">
     <div></div>
    <table width="100%" style="color:#6d6d6d;" class="table">
        <tr>
            <td>Leyenda para este reporte</td>
        </tr>                            
    </table>    
    <div></div>
    <table width="100%" style="color:#6d6d6d;" class="table table-bordered table-striped">
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Operación nro:</b></td>
            <td class="td_border" align="right"><?=$idOperacion?></td>
            <td class="td_border"><b>Fecha operación:</b></td>
            <td class="td_border" align="right"><?=$operacion->fecha_operacion?></td>           
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr>
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Emisor:</b></td>
            <td class="td_border" align="right"><?=$emisor->razon_social?></td>
            <td class="td_border"><b>Pagador:</b></td>
            <td class="td_border" align="right"><?=$pagador->razon_social?></td>           
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr> 
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Títulos negociados:</b></td>
            <td class="td_border" colspan="3"><?=implode(" - ",$arrFacturas["fac"])?></td>
        </tr>
        <tr>
            <td colspan="4" class="td_border"></td>
        </tr> 
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Valor neto:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->valor_neto)?></td>
            <td class="td_border"><b>Costo financiero:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->descuento_total)?></td>
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr> 
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>% Anticipo:</b></td>
            <td class="td_border" align="right"><?=$operacion->porcentaje_descuento?>%</td>
            <td class="td_border"><b>Factor:</b></td>
            <td class="td_border" align="right"><?=$operacion->factor?>%</td>
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr>         
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Margen Argenta:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->margen_argenta)?></td>
            <td class="td_border"><b>Comisión ejecutivo:</b></td>
            <?php
                $comisionEjecutivo = ($operacion->margen_argenta * $operacion->comision) / 100;
            ?>          
            <td class="td_border" align="right"><?=$operacion->comision?>% - <b>Valor:</b> <?=formato_moneda($comisionEjecutivo)?></td>
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr> 
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Iva factura asesoría:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->iva_fra_asesoria)?></td>
            <td class="td_border"><b>Factura Argenta:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->fra_argenta)?></td>
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr>   
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>Otros:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->valor_otros_operacion)?></td>
            <td class="td_border"><b>Giro antes GMF:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->giro_antes_gmf)?></td>
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr>     
        <tr style="background-color:#F9F9F9;">
            <td class="td_border"><b>GMF:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->gmf)?></td>
            <td class="td_border"><b>Valor giro final:</b></td>
            <td class="td_border" align="right"><?=formato_moneda($operacion->valor_giro_final)?></td>
        </tr>
        <tr>
            <td class="td_border" colspan="4"></td>
        </tr>         
    </table>
</div>


