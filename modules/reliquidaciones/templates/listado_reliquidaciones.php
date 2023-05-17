<script>

$(document).ready(function() {
    oTable = $('#listReliquidaciones').dataTable({ "paging": "full_numbers", "bStateSave": true, "bInfo": false});
});

function editReliquidacion(idReliquidacion) {
    loader();
    $("#content_reliquidaciones").load('admindex.php', { Ajax:true, id_reliquidacion: idReliquidacion, mod: 'reliquidaciones', action:'reliquidacion', id_operacion:'<?=$idOperacion?>'}, function () {
        loader();
    });
}

function deleteReliquidacion(idReliquidacion) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=reliquidaciones&action=eliminarReliquidacion&id_reliquidacion=" + idReliquidacion
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarReliquidaciones();
            }
    });
}

function descargarReporte(idReliquidacion){

    showLoading("Descargando reporte. Espere por favor...");

    nombreReporte = "ReporteReliquidacion.pdf";

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=reliquidaciones&action=generarReporteClienteTrazabilidadNew&es_excel=false&id_reliquidacion=" + idReliquidacion;
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("reliquidaciones");
            $("#formMail input[id=action]").val("guardarReporteReliquidacion");
            $("#formMail input[id=__dataMail]").val(response);

            var dataForm = "Ajax=true&" + $("#formMail").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    downloadURI("./gallery/reliquidaciones/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

function descargarReporteReliquidacion(idReliquidacion){

    showLoading("Descargando reporte. Espere por favor...");

    nombreReporte = "ReporteReliquidacion.pdf";

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=reliquidaciones&action=generarReporteFacturasLiquidadas&es_excel=false&id_reliquidacion=" + idReliquidacion;
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("reliquidaciones");
            $("#formMail input[id=action]").val("guardarReporteReliquidacion");
            $("#formMail input[id=__dataMail]").val(response);

            var dataForm = "Ajax=true&" + $("#formMail").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    downloadURI("./gallery/reliquidaciones/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

function descargarReporteReliquidacionFacturacion(idReliquidacion){

    showLoading("Descargando reporte. Espere por favor...");

    nombreReporte = "ReporteReliquidacionFacturacion.pdf";

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=reliquidaciones&action=generarReporteFacturasLiquidadas&facturacion=true&es_excel=false&id_reliquidacion=" + idReliquidacion;
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("reliquidaciones");
            $("#formMail input[id=action]").val("guardarReporteReliquidacion");
            $("#formMail input[id=__dataMail]").val(response);

            var dataForm = "Ajax=true&" + $("#formMail").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    downloadURI("./gallery/reliquidaciones/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

</script>
<?php
    //IMPRIMIMOS EL SUMARIO DE LA OPERACION
    $operaciones->sumarioOperacion($idOperacion);
?>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Información de reliquidaciones</h4>
    </div>
    <div id="content_reliquidaciones" class="" style="clear:both;padding-top:10px;">
            <div style="height: 40px;" class="row-fluid">
                <div class="agregar_registro text-right">
                    <?php
                        if ($operacion->estado == 1 && $_SESSION["profile_text"]!="Cliente"){
                    ?>
                        <a class="btn btn-primary btn-sm" href="javascript:editReliquidacion(0)"><i class="fa fa-plus-square fa-lg"></i> Agregar</a>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <table id="listReliquidaciones" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Opciones</th>
                    <th>Cod. reliquidación</th>
                    <th>Fecha</th>
                    <th>Nro Factura</th>
                    <th>Tipo reliquidación</th>
                    <!--th>Remanentes enviados</th-->
                    <th>Estado</th>
                    <th>Trazabilidad</th>
                    <th>Reporte</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    while(!$rsReliquidaciones->EOF)
                    {
                        $idReliquidacion = $rsReliquidaciones->fields["id_reliquidacion"];
                ?>
                        <tr>
                            <td align="center">


                                <?php
                                    if ($operacion->estado == 1 && $_SESSION["profile_text"]!="Cliente" || $_SESSION["profile_text"]=="Administrador")
                                        echo "<a href=\"javascript:editReliquidacion(".$idReliquidacion.");\"><img border=\"0\" alt=\"Editar re-liquidación\" title=\"Editar re-liquidación\" src=\"./images/editar.png\"></a>";
                                ?>

                                <?php
                                    if ($operacion->estado == 1 && $_SESSION["profile_text"]!="Cliente")
                                        echo "<a href=\"javascript:deleteReliquidacion(".$idReliquidacion.");\"><img border=\"0\" alt=\"Eliminar re-liquidación\" title=\"Eliminar re-liquidación\" src=\"./images/eliminar.png\"></a>";
                                    else
                                        echo "N/D";
                                ?>
                            </td>
                            <td><?=$rsReliquidaciones->fields["id_reliquidacion"]?></td>
                            <td><?=$rsReliquidaciones->fields["fecha_registro"]?></td>
                            <td><?=$rsReliquidaciones->fields["num_factura"]?></td>
                            <td><?=$this->arrTiposReliquidacion[$rsReliquidaciones->fields["id_tipo_reliquidacion"]]?></td>
                            <!--td><?=($rsReliquidaciones->fields["enviado_remanentes"]==1?"Si":"No")?></td-->
                            <td><?=$this->arrEstadosReliquidacion[$rsReliquidaciones->fields["estado"]]?></td>
                            <td align='center'>
                                <?php
                                    $arrFacturasAbonadas = $operacionReliquidacionAbonos->getArrFacturasAbonadasReliquidacion($idReliquidacion);
                                    $arrReliquidacionTrazabilidad = $this->obtenerReliquidacionPrincipal($idReliquidacion,array($idReliquidacion));
                                    $idReliquidacionTrazabilidad = $arrReliquidacionTrazabilidad[(Count($arrReliquidacionTrazabilidad)-1)];
                                    $strTrazabilidad = implode(",",$arrReliquidacionTrazabilidad);

                                    if (Count($arrReliquidacionTrazabilidad) >= 2 || Count($arrFacturasAbonadas) >= 1){

                                ?>
                                        <a href="admindex.php?mod=reliquidaciones&action=generarReporteContableTrazabilidad&id_reliquidacion=<?=$idReliquidacionTrazabilidad?>" title="Generar reporte contable trazabilidad" target="_blank"><i class="fa fa-usd fa-2x"></i></a>
                                        <a href="javascript:descargarReporte(<?=$idReliquidacionTrazabilidad?>);" title="Generar reporte trazabilidad"><i class="fa fa-file fa-2x"></i></a>
                                <?php
                                        echo "<br/>".$strTrazabilidad;
                                    }
                                ?>
                            </td>
                            <td align="center">
                            	<?php
                            		//SI SON PARCIALES
                            		if ($rsReliquidaciones->fields["id_tipo_reliquidacion"]==4 || $rsReliquidaciones->fields["id_tipo_reliquidacion"]==6 || $rsReliquidaciones->fields["id_tipo_reliquidacion"]==8){
                            			echo "<a href=\"javascript:descargarReporteReliquidacion(".$rsReliquidaciones->fields["id_reliquidacion"].");\" title=\"Generar reporte\"><i class=\"fa fa-file\"></i></a>";	
                            		}
                            		else{
                            			echo "<a href=\"javascript:descargarReporteReliquidacionFacturacion(".$rsReliquidaciones->fields["id_reliquidacion"].");\" title=\"Generar reporte\"><i class=\"fa fa-file\"></i></a>";	
                            		}
                            	?>                            	

                            </td>
                        </tr>
                <?php
                        $rsReliquidaciones->MoveNext();
                    }
                ?>
            </tbody>
            </table>
    </div>
</div>
<div id="prueba_reporte">

</div>