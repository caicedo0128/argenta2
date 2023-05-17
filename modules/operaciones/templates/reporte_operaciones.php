<script type="text/javascript">

    $(document).ready(function () {
        $('#fecha_inicio_buscador').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
        $('#fecha_fin_buscador').datetimepicker({ format: 'YYYY-MM-DD', showClear: true });
    });

    function generarReporteInversiones(){

        validateForm("datosRegistro");
        
        if ($("#fecha_inicio_buscador").val() == "" && $("#fecha_fin_buscador").val() == "" && $("#id_inversionista_buscador").val() == "" &&
            $("#estado_buscador").val() == "" && $("#id_emisor_buscador").val() == "" && $("#id_pagador_buscador").val() == ""){
            showError("Debe seleccionar al menos un filtro para buscar información");
        }
        else{

            if ($("#datosRegistro").valid()){
                $("#exportar").val('');
                showLoading("Enviando información. Espere por favor...");
                var dataForm = "Ajax=true&" + $("#datosRegistro").serialize();
                var strUrl = "admindex.php";
                $.ajax({
                        type: 'POST',
                        url: strUrl,
                        dataType: "html",
                        data:dataForm,
                        success: function (response) {
                            closeNotify();
                            $("#resultado_reporte_operaciones").html(response);
                        }
                });     
            }
        }
    }
    
    function exportarReporteInversiones(){

        validateForm("datosRegistro");

        if ($("#datosRegistro").valid()){
            $("#exportar").val('1');
            $("#datosRegistro").submit();
        }
    } 

</script>

<div class="row-fluid" id="titulo_reporte_operaciones">
    <div class="col-md-12 bg-primary-custom">
        <h4>Consulta de operaciones</h4>
    </div>
</div>
<div id="content_reporte_operaciones" class="" style="clear:both;padding-top:15px;">

    <div class="panel panel-primary">
        <div class="panel-body">
            <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
                <input type="hidden" name="mod" value="operaciones" />
                <input type="hidden" name="action" value="listOperaciones" />
                <input type="hidden" name="Ajax" value="True" />
                <input type="hidden" name="exportar" id="exportar" value="" />
                <div class="row" style="height:10px;">&nbsp;</div>     
                <div class="row">
                    <div class="col-md-2">
						Fecha operación inicial:
						<div>
						<?php 
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("fecha_inicio_buscador", "fecha_inicial", 0, $fechaInicialBuscador, "form-control", 50, "", "", "");
						?>           
						</div>
                    </div>
                    <div class="col-md-2">
						Fecha operación final:
						<div>
						<?php 
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("fecha_fin_buscador", "fecha_final", 0, "", "form-control", 50, "", "", "");
						?> 
						</div>
                    </div>
                    <div class="col-md-2">
						Estado:
						<div class="">
						<?php
							$estado_select = new Select("estado_buscador","Estado",$this->arrEstados,"",0,"", "form-control", 0, "", "", 0);
							$estado_select->enableBlankOption();
							$estado_select->Default = "";
							echo $estado_select->genCode(); 
						?>
						</div>                    
					</div>                    
                </div>  
				<?php
					if ($_SESSION["profile_text"] != "Cliente"){
				?>                
					<div class="row" style="height:10px;">&nbsp;</div>    
					<div class="row">            
						<div class="col-md-4">
							Emisor:
							<div class="">
							<?php

								$sede_select = new Select("id_emisor_buscador","Tercero",$arrEmisores,"",0,"", "form-control", 0, "", "", 0);
								$sede_select->enableBlankOption();
								$sede_select->Default = $operacion->id_emisor;
								echo $sede_select->genCode();
							?>    
							</div>  
						</div>						
						<div class="col-md-4">
							Pagador:
							<div>
							<?php

								$sede_select = new Select("id_pagador_buscador","Tercero",$arrPagadores,"",0,"", "form-control", 0, "", "", 0);
								$sede_select->enableBlankOption();
								$sede_select->Default = $operacion->id_pagador;
								echo $sede_select->genCode();
							?>    
							</div>                  
						</div>
					</div> 
					<script type="text/javascript">
					
					    $(document).ready(function () {					 
					       	$("#id_emisor_buscador").select2({ placeholder: 'Seleccione uno...',allowClear: true});
							$("#id_pagador_buscador").select2({ placeholder: 'Seleccione uno...',allowClear: true});
    					});
    				</script>
				<?php
					}
					else{
				?>
					<input type="hidden" name="id_emisor_buscador" id="id_emisor_buscador" value="<?=$_SESSION["id_tercero"]?>">
				<?php			
					}
				?>                                
                <div class="row" style="height:10px;">&nbsp;</div>
            </form>  
            <center>
                <input type="button" value="Consultar" class="btn btn-primary datos_reporte_btnSave" onclick="generarReporteInversiones();">
            </center>  
            <div class="row" style="height:10px;">&nbsp;</div>             
        </div>
    </div>
</div>

 <div id="resultado_reporte_operaciones"></div>

