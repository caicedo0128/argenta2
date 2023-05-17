<script type="text/javascript">

    $(document).ready(function () {
        $('#fecha_inicio').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
        $('#fecha_fin').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    });


    function generarReporteFacturacion(){

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

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Reporte facturación</h4>
    </div>
</div>
<div id="content_reporte" class="container-fluid " style="clear:both;padding-top:15px;">

    <div class="panel panel-primary col-md-12">
        <div class="panel-body">
            <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
                <input type="hidden" name="mod" value="reportes" />
                <input type="hidden" name="action" value="generarReporteFacturacion" />
                <input type="hidden" name="Ajax" value="True" />
                <div class="row" style="height:10px;">&nbsp;</div>     
                <div class="row">
                    <div class="col-md-3 labelCustom">Fecha operacion inicial:</div>
                    <div class="col-md-2">
                    <?php 
                        $c_textbox = new Textbox;
                        echo $c_textbox->Textbox ("fecha_inicio", "fecha_inicial", 0, "", "form-control", 50, "", "", "");
                    ?>           
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3 labelCustom">Fecha operacion final:</div>
                    <div class="col-md-2">
                    <?php 
                        $c_textbox = new Textbox;
                        echo $c_textbox->Textbox ("fecha_fin", "fecha_final", 0, "", "form-control", 50, "", "", "");
                    ?>  
                    </div>
                </div>   
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-3 labelCustom">Pagador:</div>
                    <div class="col-md-3">
                    <?php

                        $sede_select = new Select("id_pagador","Tercero",$arrPagadores,"",0,"", "form-control", 0, "", "", 0);
                        $sede_select->enableBlankOption();
                        echo $sede_select->genCode();
                    ?>    
                    </div>  
                    <div class="col-md-1 labelCustom">Tipo:</div>
                    <div class="col-md-4">
                        <div id="divRadioTipo" class="radioValidate">
                        <?php
                            $c_radio = new Radio;
                            $arrSiNo = array("1"=>"Facturado","2"=>"Sin Facturar");
                            $c_radio->Radio("tipo","tipo",$arrSiNo,"", 1, 2, "", 0, "customValidateRadio('Tipo');");
                            while($tmp_html = $c_radio->next_entry()) {
                                echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                            }
                        ?>
                        </div>
                    </div>                      
                </div>                  
                <div class="row" style="height:10px;">&nbsp;</div>
            </form>  
            <center>
                <input type="button" value="Consultar" class="btn btn-primary datos_reporte_btnSave" onclick="generarReporteFacturacion();">
            </center>             
        </div>
    </div>
    <div id="resultado_reporte"></div>       
</div>


