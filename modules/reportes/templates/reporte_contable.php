<script type="text/javascript">

    $(document).ready(function () {
        $('#fecha_operacion').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    });


    function generarReporteContable(){

        validateForm("datosRegistro");

        if ($("#datosRegistro").valid()){

            showLoading("Enviando informacion...");
            var dataForm = "Ajax=true&" + $("#datosRegistro").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                    type: 'POST',
                    url: strUrl,
                    dataType: "html",
                    data:dataForm,
                    success: function (response) {
                        closeNotify();
                        $("#resultado_reporte").html(response);
                    }
            });      
        }
    }
    
    function enviarReporte(){
    
        validateForm("custom_data_to_email");

        if ($("#custom_data_to_email").valid()) {
        
            showLoading("Enviando reporte por correo electrónico..."); 
            
            var idOperacion = $("#id_operacion_reporte").val(); 
            var action = "reporteCliente";
            var subjectMail = "Reporte contable";

            //GENERAMOS EL REPORTE PDF            
            var dataForm = "Ajax=true&mod=reportes&action=generarReporteDetalleContable&es_reporte=true&id_operacion=" + idOperacion;
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "html",
                data:dataForm,
                success: function (response) { 
                    
                    var dataForm = "Ajax=true&mod=reportes&action=guardarReportePDF&__nameReport=reporteContable&__dataMail=" + response;
                    var strUrl = "admindex.php";
                    $.ajax({
                        type: 'POST',
                        url: strUrl,
                        dataType: "json",
                        data:dataForm,
                        success: function (response) { 
                        
                            $("#formMail input[id=mod]").val("reportes");
                            $("#formMail input[id=action]").val("enviarReporte");
                            $("#formMail input[id=__option1]").val(idOperacion);
                            $("#formMail input[id=__subjectMail]").val(subjectMail);
                            $("#formMail input[id=__toEmailMail]").val($("#correo_to_email").val());
                            $("#formMail input[id=__toNameMail]").val($("#nombre_to_email").val());
                            $("#formMail input[id=__files]").val('reportes/reporteContable.pdf');
                            $("#formMail input[id=__template]").val('mailContable');


                            var dataForm = "Ajax=true&" + $("#formMail").serialize();
                            var strUrl = "admindex.php";
                            $.ajax({
                                    type: 'POST',
                                    url: strUrl,
                                    dataType: "json",
                                    data:dataForm,
                                    success: function (response) {
                                        closeNotify();                    
                                        if (response.Success) {
                                            showSuccess("Transacción exitosa. Espere por favor..."); 
                                            $('#modalToMail').modal('hide');
                                        }
                                        else{
                                            showError(response.Message);
                                        }
                                    }
                            }); 
                        }
                    });  
                }
            });              
            
            //FIN GUARDADO PDF
        }
    
    }    
    
</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Reporte contable</h4>
    </div>
</div>
<div id="content_reporte" class="container-fluid " style="clear:both;padding-top:15px;">

    <div class="panel panel-primary col-md-12">
        <div class="panel-body">
            <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
                <input type="hidden" name="mod" value="reportes" />
                <input type="hidden" name="action" value="generarReporteContable" />
                <input type="hidden" name="Ajax" value="True" />
                <div class="row" style="height:10px;">&nbsp;</div>     
                <div class="row">
                    <div class="col-md-2 labelCustom">Fecha operación:</div>
                    <div class="col-md-2">
                    <?php 
                        $c_textbox = new Textbox;
                        echo $c_textbox->Textbox ("fecha_operacion", "fecha_operacion", 1, "", "form-control", 50, "", "", "");
                    ?>           
                    </div>
                </div>   
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-2 labelCustom">Emisor:</div>
                    <div class="col-md-3">
                    <?php

                        $sede_select = new Select("id_emisor","Tercero",$arrEmisores,"",1,"", "form-control", 0, "", "", 0);
                        $sede_select->enableBlankOption();
                        echo $sede_select->genCode();
                    ?>    
                    </div>  
                    <div class="col-md-2 labelCustom">Pagador:</div>
                    <div class="col-md-3">
                    <?php

                        $sede_select = new Select("id_pagador","Tercero",$arrPagadores,"",1,"", "form-control", 0, "", "", 0);
                        $sede_select->enableBlankOption();
                        echo $sede_select->genCode();
                    ?>    
                    </div>                  
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>
            </form>  
            <center>
                <input type="button" value="Consultar" class="btn btn-primary datos_reporte_btnSave" onclick="generarReporteContable();">
            </center>               
            <div id="resultado_reporte">

            </div> 
    </div>
    </div>
</div>

<div id="modalToMail" class="modal fade" role="dialog" aria-labelledby="modalToMail" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Enviar correo electrónico a:</h4>
            </div>
            <div class="modal-body" id="">
                <form id="custom_data_to_email" name="custom_data_to_email">
                <input type="hidden" id="id_operacion" name="id_operacion" value="0">
                <input type="hidden" id="fecha_pago" name="fecha_pago" value="0">
                <div class="row">
                    <div class="col-md-2 labelCustom">Nombres:</div>
                    <div class="col-md-6">
                        <input type="textbox" id="nombre_to_email" name="nombre_to_email" value="" class="form-control required">                 
                    </div>
                </div>
                <div class="row" style="height:10px;">&nbsp;</div> 
                <div class="row">
                    <div class="col-md-2 labelCustom">E-mail:</div>
                    <div class="col-md-6">
                        <input type="textbox" id="correo_to_email" name="correo_to_email" value="" class="form-control required no-mayus"> 
                        (Separe con ; para varios envíos)
                    </div>
                </div>  
                <div class="row" style="height:10px;">&nbsp;</div> 
                <center>
                    <input type="button" class="btn btn-success" value="Enviar" onclick="enviarReporte();">
                </center>  
                </form>
            </div>
        </div>
    </div>
</div>



