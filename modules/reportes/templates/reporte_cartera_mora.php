<script type="text/javascript">

    $(document).ready(function () {
        $('#fecha_inicio').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
        $('#fecha_fin').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    });


    function generarReporteCarteraMora(){

        validateForm("datosRegistro");
        var msjError = "";
        
        if ($("#id_emisor").val() != "" && $("#id_pagador").val() != ""){
        	msjError = "<br/>Solo debe seleccionar un Emisor o un Pagador. Verifique";
        }
        
        if ($("#datosRegistro").valid() && msjError == ""){

            showLoading("Enviando información. Espere por favor...");
            var dataForm = $("#datosRegistro").serialize();
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
        else{
        	showError("Por favor revise los campos marcados." + msjError);
        }
    }

</script>

<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Reporte cartera mora</h4>
    </div>
</div>
<div id="content_reporte" class="container-fluid " style="clear:both;padding-top:15px;">

    <div class="panel panel-primary">
        <div class="panel-body">
            <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
                <input type="hidden" name="mod" value="reportes" />
                <input type="hidden" name="action" value="generarReporteCarteraMora" />
                <input type="hidden" name="Ajax" value="True" />
                <div class="row" style="height:10px;">&nbsp;</div>     
                <div class="row">
                    <div class="col-md-2 labelCustom">
						Fecha pago inicial:
						<div class="">
						<?php 
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("fecha_inicio", "fecha_inicial", 0, "", "form-control", 50, "", "", "");
						?>           
						</div>
					</div>
                    <div class="col-md-2 labelCustom">
                    	Fecha pago final:
						<div class="">
						<?php 
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("fecha_fin", "fecha_final", 0, "", "form-control", 50, "", "", "");
						?>  
						</div>
					</div>
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-4 labelCustom">
                    	Emisor:
						<div class="">
						<?php

							$sede_select = new Select("id_emisor","Tercero",$arrEmisores,"",0,"", "form-control", 0, "", "", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = $operacion->id_emisor;
							echo $sede_select->genCode();
						?>    
						</div> 
					</div>
                    <div class="col-md-4 labelCustom">
                    	Pagador:
						<div class="">
						<?php

							$sede_select = new Select("id_pagador","Tercero",$arrPagadores,"",0,"", "form-control", 0, "", "", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = $operacion->id_pagador;
							echo $sede_select->genCode();
						?>    
						</div>                  
					</div>
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>
                <div class="row"> 
                	<div class="col-md-4 labelCustom">
						Formato:
						<div id="divRadiotipo" class="radioValidate">
						<?php

							$c_radio = new Radio;
							$arrSiNo = array("1"=>"Emisor","2"=>"Pagador","3"=>"Consolidado (Mora)");
							$c_radio->Radio("tipo","tipo",$arrSiNo,"", 1, "1", "", 0, "customValidateRadio('tipo');");
							while($tmp_html = $c_radio->next_entry()) {
								echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
							}
						?>
						</div>
					</div>                
				</div>
            </form>  
            <center>
                <input type="button" value="Consultar" class="btn btn-primary datos_reporte_btnSave" onclick="generarReporteCarteraMora();">
            </center>   
            <div id="resultado_reporte"></div> 
        </div>
    </div>
</div>

