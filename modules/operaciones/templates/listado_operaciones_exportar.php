<?php
    header("Content-Type:application/vnd.ms-excel; charset=utf-8");
    header("Content-type:application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteOperaciones.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<table>
    <tr>
        <td colspan="6">
            <h1>Reporte operaciones</h1>
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
<table id="listOperaciones" border="1">
<thead>
    <tr>
        <th>Id. Operación</th>
        <th>Fecha registro</th>
        <th>Fecha operación</th>
        <th>Factura de venta</th>
        <th>Emisor</th>
        <th>Pagador</th>
        <th>Inversionista</th>
        <th>Titulos negociados</th>
        <th>Valor giro final</th>
        <th>Estado</th>
    </tr>
</thead>
<tbody>

    <?php
        while(!$rsOperaciones->EOF)
        {
            $idOperacion = $rsOperaciones->fields["id_operacion"];
            $fechaOperacion = $rsOperaciones->fields["fecha_operacion"];
            $idEstado = $rsOperaciones->fields["estado"];
            $numero_factura = $rsOperaciones->fields["num_factura"];
            $arrFacturas = $factura->getArrFacturasPorOperacion($idOperacion,$fechaOperacion);
            $arrFacturasReliquidacion = $factura->getArrFacturasPorOperacionReliquidacion($idOperacion,$fechaOperacion);						
    ?>
            <tr>
                <td><?=$idOperacion?></td>
                <td><?=$rsOperaciones->fields["fecha"]?></td>
                <td><?=$fechaOperacion?></td>
                <td>
                    <?php
                    if ($numero_factura == 0){                                 
                        if (Count($arrFacturasReliquidacion) > 0){                                 
                        echo implode(" - ",($arrFacturasReliquidacion["fac"]));
                        }
                        else{
                            echo "Sin reliquidaciones";
                        }
                    }else{
                        echo $numero_factura;
                    }                           
                    ?>
                </td>
                <td><?=$rsOperaciones->fields["emisor"]?></td>
                <td><?=$rsOperaciones->fields["pagador"]?></td>
                <td><?=$rsOperaciones->fields["inversionista"]?></td>
                <td>
                <?php
                    if (Count($arrFacturas) > 0){                                 
                        echo implode(" - ",($arrFacturas["fac"]));
                    }
                    else
                        echo "Sin facturas cargadas";
                ?>
                </td>
                <td><?=$rsOperaciones->fields["valor_giro_final"]?></td>
                <td><?=$this->arrEstados[$rsOperaciones->fields["estado"]]?></td>
            </tr>
    <?php 
            $rsOperaciones->MoveNext();
        }
    ?>                
</tbody>
</table>

