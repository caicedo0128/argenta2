<script type="text/javascript">

    $(document).ready(function () {
		$("#estado_buscador").val("");
    });

    function buscarClientes(){

        validateForm("datosRegistroBuscador");
		$("#content_reporte_clientes").show();
		$("#titulo_reporte_clientes").show();        

		if ($("#datosRegistroBuscador").valid()){
			$("#exportar").val('');			
			showLoading("Enviando información. Espere por favor...");
			var dataForm = "Ajax=true&" + $("#datosRegistroBuscador").serialize();
			var strUrl = "admindex.php";
			$.ajax({
					type: 'POST',
					url: strUrl,
					dataType: "html",
					data:dataForm,
					success: function (response) {
						closeNotify();
						$("#resultado_consulta").html(response);
					}
			});     
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

<div class="row-fluid" id="titulo_reporte_clientes">
    <div class="col-md-12 bg-primary-custom">
        <h4>Consulta de clientes</h4>
    </div>
</div>
<div id="content_reporte_clientes" class="" style="clear:both;padding-top:15px;">

    <div class="panel panel-primary">
        <div class="panel-body">
            <form id="datosRegistroBuscador" method="post" name="datosRegistroBuscador" action="admindex.php" enctype="multipart/form-data">        
                <input type="hidden" name="mod" value="clientes" />
                <input type="hidden" name="action" value="listClients" />
                <input type="hidden" name="Ajax" value="True" />
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-2 labelCustom">
						Tipo tercero:
						<div class="">
						<?php

							$sede_select = new Select("id_tipo_tercero_buscador","Tercero",$arrTerceros,"",0,"", "form-control", 0, "", "", 0);
							$sede_select->enableBlankOption();
							$sede_select->Default = "";
							echo $sede_select->genCode();
						?>    
						</div>    
                    </div>
                    <div class="col-md-4 labelCustom">
						Razón social / Nombres:
						<div class="">
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("razon_social_buscador", "Direccion", 0, "", "form-control", 50, "", "", "");
						?>    
						</div>                     
					</div>
                    <div class="col-md-2 labelCustom">
						Estado:
						<div class="">
						<?php
							$estado_select = new Select("estado_buscador","Estado",$this->arrEstadosCliente,"",0,"", "form-control", 0, "", "", 0);
							$estado_select->enableBlankOption();
							$estado_select->Default = "";
							echo $estado_select->genCode(); 
						?>
						</div>                    
                    </div>
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
               
                </div>                 
                <div class="row" style="height:10px;">&nbsp;</div>
            </form>  
            <center>
                <input type="button" value="Consultar" class="btn btn-primary datos_reporte_btnSave" onclick="buscarClientes();">
            </center>  
            <div class="row" style="height:10px;">&nbsp;</div>             
        </div>
    </div>
</div>

 <div id="resultado_consulta"></div>

