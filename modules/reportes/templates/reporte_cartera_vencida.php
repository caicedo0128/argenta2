<script type="text/javascript">

    $(document).ready(function () {
        $('#fecha_inicio').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
        $('#fecha_fin').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    });


    function generarReporteFacturas(){

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
        <h4>Reporte cartera vencida</h4>
    </div>
</div>
<div id="content_reporte" class="container-fluid " style="clear:both;padding-top:15px;">

    <div class="panel panel-primary">
        <div class="panel-body">
            <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
                <input type="hidden" name="mod" value="reportes" />
                <input type="hidden" name="action" value="generarReporteCarteraVencida" />
                <input type="hidden" name="Ajax" value="True" />
                <div class="row" style="height:10px;">&nbsp;</div>     
                <div class="row">
                    <div class="col-md-2 labelCustom">Fecha pago inicial:</div>
                    <div class="col-md-2">
                    <?php 
                        $c_textbox = new Textbox;
                        echo $c_textbox->Textbox ("fecha_inicio", "fecha_inicial", 0, "", "form-control", 50, "", "", "");
                    ?>           
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2 labelCustom">Fecha pago final:</div>
                    <div class="col-md-2">
                    <?php 
                        $c_textbox = new Textbox;
                        echo $c_textbox->Textbox ("fecha_fin", "fecha_final", 0, "", "form-control", 50, "", "", "");
                    ?>  
                    </div>
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-2 labelCustom">Emisor:</div>
                    <div class="col-md-4">
                    <?php

                        $sede_select = new Select("id_emisor","Tercero",$arrEmisores,"",0,"", "form-control", 0, "", "", 0);
                        $sede_select->enableBlankOption();
                        $sede_select->Default = $operacion->id_emisor;
                        echo $sede_select->genCode();
                    ?>    
                    </div>  
                    <div class="col-md-2 labelCustom">Pagador:</div>
                    <div class="col-md-4">
                    <?php

                        $sede_select = new Select("id_pagador","Tercero",$arrPagadores,"",0,"", "form-control", 0, "", "", 0);
                        $sede_select->enableBlankOption();
                        $sede_select->Default = $operacion->id_pagador;
                        echo $sede_select->genCode();
                    ?>    
                    </div>                  
                </div>   
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-2 labelCustom">Inversionista:</div>
                    <div class="col-md-4">
                    <?php

                        $sede_select = new Select("id_inversionista","Tercero",$arrInversionistas,"",1,"", "form-control", 0, "", "", 0);
                        $sede_select->enableBlankOption();
                        $sede_select->Default = "";
                        echo $sede_select->genCode();
                    ?>    
                    </div>                   
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>
            </form>  
            <center>
                <input type="button" value="Consultar" class="btn btn-primary datos_reporte_btnSave" onclick="generarReporteFacturas();">
            </center>   
            <div id="resultado_reporte"></div> 
        </div>
    </div>
</div>

