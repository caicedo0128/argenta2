<script>
	function activarSelect(objeto){

		var tipo = objeto.value;	

        $(".tr_dinamica").hide();
		$(".tr_" + tipo).show();
	}

	function mostrarTamanio(target){
	
		$("#datosTamanios").hide();
		if (target=="lightbox"){
			$("#datosTamanios").show();
		}	
	}

	function validarPagina(){

		validateForm("formaPagina");

		if ($("#formaPagina").valid()){
            
            showLoading("Enviando informaci�n. Espere por favor...");
			var msj = "";	

			/*if (forma.tipo_contenido.value=="contenido" && (forma.contenido.value=="" || forma.contenido.value=="0"))
				msj += "Si selecciono en el tipo de contenido 'Contenido', debe seleccionar el contenido a cargar.\n";

			if (forma.tipo_contenido.value=="plugin" && (forma.plugin.value=="" || forma.plugin.value=="0"))
				msj += "Si selecciono en el tipo de contenido 'Plugin', debe seleccionar el plugin a cargar.\n";

			if (forma.tipo_contenido.value=="externo" && forma.link_externo.value=="")
				msj += "Si selecciono en el tipo de contenido 'Link externo', debe diligenciar el campo Link Externo.\n";

			if (forma.target.value=="lightbox" && (forma.ancho.value=="" || forma.ancho.value==0 || forma.alto.value=="" || forma.alto.value==0))
				msj += "Si selecciono abrir la pagina en un Lightbox, debe diligenciar los campos ancho y alto.\n";
*/
            var strUrl = "admindex.php";
            var dataForm = new FormData(document.getElementById("formaPagina"));

            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data: dataForm,
                mimeType: "multipart/form-data",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    closeNotify();
                    if (response.Success){
                        showSuccess("Transacci�n exitosa. Espere por favor...");     
                        verPaginasHijas('<?=$idPadre?>', 'paginas', 'verListado', '');
                    }
                }
            });
		}
        else{
            showError("Por favor revise los campos marcados.");
        }

	}
	
	function actualizarRuta(ruta){
		$("#ruta").text(ruta);
	}
	
	function verificaAlias(alias){
			
		var strUrl = "admindex.php";
		$.ajax({
				type: 'POST',
				url: strUrl,
				dataType: "json",
				data:
					{
						Ajax : "true",
						mod : "paginas",
						action : "verficarAlias",
						alias : alias
					},
				success: function (response) {
					//ALIAS EXISTENTE
					var aliasActual = $("alias").val();
					if (response.Success && aliasActual != alias) {
						$("alias").val("");
						showError("El alias asignado a esta accion ya existe. Por favor cambielo.");
					}
				}
		});
	
	}
	
</script>
<div class="">
<div class="panel panel-bordered-primary">
    <div class="panel-body panel-custom-interno">
        Registro de informacion de acciones
        <div class="cerrar_form" onclick="verPaginasHijas('<?=$idPadre?>', 'paginas', 'verListado', '');" title="Volver"><i class="fa fa-reply fa-lg"></i></div>
        <hr class="separador_titulo"/>
			<form name="formaPagina" id="formaPagina" action="admindex.php" method="post" enctype="multipart/form-data">		
            <input type="hidden" name="Ajax" value="true">
            <input type="hidden" name="id_pagina" value="<?=$idPagina?>">
			<input type="hidden" name="id_padre" value="<?=$idPadre?>">
			<input type="hidden" name="id_pagina_padre" value="<?=$idPaginaPadre?>">			
			<input type="hidden" name="mod" value="paginas">
			<input type="hidden" name="action" value="guardarPagina">

			<div class="row" style="height:10px;">&nbsp;</div>    
                <div class="row">            
                    <div class="col-md-2 labelCustom">
							<tr class="">
						<td>Pagina padre:</td>
						<td>
						<?php
						
							$idPaginaPadreAux = $pagina->id_pagina_padre;	
							if (!$idPaginaPadreAux)
								$idPaginaPadreAux = $idPadre;
						
							$c_select = new Select;
							$c_select->Select("id_pagina_padre", "Pagina padre", $arrPaginas, "", 0,$idPaginaPadreAux , "form-control", 0, "", "", 0);
							$c_select->enableBlankOption();				
							echo $c_select->genCode();				
						?>
							</td>
						</tr>
					</div>

                    <div class="col-md-3 labelCustom">
						<tr class="">
						<td>Alias:</td>
						<td>
						<?php
							$c_textbox = new Textbox;
							echo $c_textbox->Textbox ("alias", "Alias", 1, $pagina->alias, "form-control no_mayus", 30, "", "verificaAlias(this.value);", "","","actualizarRuta(this.value);");
						?>
                		<span id="aliasActual" style="display:none"><?=$pagina->alias?></span>
                		<span id="verificar" style="display:none"></span>         
                		</td>
						<small>
                		<td>Link de llamda:</td>
                		http:/<?=$_SERVER["HTTP_HOST"]?>/index.php?page=<span id="ruta"><?=$pagina->alias?></span></td>
						</small>
					</tr>	  
						</div>                     
					

                    <div class="col-md-3 labelCustom">
								<tr class="">
									<td>Nombre:</td>
									<td>
									<?php
										$c_textbox = new Textbox;
										echo $c_textbox->Textbox ("nombre", "Nombre", 1, $pagina->nombre, "form-control", 30, "", "", "");
									?>
									</td>
								</tr>
            
						</div>                    
                    </div>
                
				
							<div class="col-md-3 labelCustom">
									<tr class="">
								
									<td>Titulo HTML:</td>
									<td>
									<?php
										echo $c_textbox->Textbox ("titulo_html", "Titulo HTML", 1, $pagina->titulo_html, "form-control no_mayus", 30, "", "", "");                   
									?>
									</td>                
								</tr>
							</div>  
							
							<div class="col-md-3 labelCustom">
									<tr class="">
									<td>Imagen menu:</td>
									<td>
									<?php               
										echo $c_textbox->Textbox ("imagen_menu", "Imagen menu", 0, $pagina->imagen_menu, "form-control no_mayus", 30, "", "", "");                    
									?>              				
									</td>
								</tr>
							</div>   


							<div class="col-md-2 labelCustom">
							<tr class="">
							<td>Forma abrir pagina:</td>
							<td>				
							<?php
								$c_select->Select("target", "Abrir link en?", $arrTarget, "", 0,$pagina->target, "form-control", 0, "", "mostrarTamanio(this.value);", 0);					
								echo $c_select->genCode()
							?>		
							<span id="datosTamanios" style="display:none">
							<?php				
								echo "Ancho:" . $c_textbox->Textbox ("ancho", "Ancho", 0, $pagina->ancho, "form-control", 3, "", "", "");					
								echo "Alto:" . $c_textbox->Textbox ("alto", "Alto", 0, $pagina->alto, "form-control", 3, "", "", "");					
								
							?>	
							<br>Codigo para cerrar:<br> <xmp><a href="javascript:window.parent.cerrarContenido();">Cerrar</a></xmp>
							</span>
							</td>
						</tr>
					</div>

                    	

				<div class="col-md-4 labelCustom">
					<td>Tipo contenido:</td>
					<table border="0" cellspacing="3" cellpadding="3" >
					
						<tr class="">
					<td>
					<?php
						$c_select->Select("tipo_contenido", "Tipo contenido para la pagina", $arrTiposContenido, "", 1,$pagina->id_tipo, "form-control", 0, "", "activarSelect(this);", 0);
						$c_select->enableBlankOption();				
						echo $c_select->genCode()
					?>				
					</td>
				</tr>	



            <tr style="display:none" class="tr_externo tr_dinamica">
				<td>Link externo:</td>
				<td>
				<?php				
					echo $c_textbox->Textbox ("link_externo", "Link Externo", 1, $pagina->link_externo, "form-control no_mayus", 30, "", "", "");					
				?>		
                Si no tiene link escriba none
				</td>
			</tr>		
            <tr style="display:none" class="tr_plugin tr_dinamica">
                <td>Modulo:</td>
                <td>
                <?php               
                    echo $c_textbox->Textbox ("modulo", "Modulo", 1, $pagina->modulo, "form-control no_mayus", 30, "", "", "");                   
                ?>              
                </td>
                <td>Action:</td>
                <td>
                <?php               
                    echo $c_textbox->Textbox ("accion", "Accion", 1, $pagina->accion, "form-control no_mayus", 30, "", "", "");                   
                ?>              
                </td>                
            </tr>  
            <tr style="display:none" class="tr_plugin tr_dinamica">
                <td>Parametro:</td>
                <td>
                <?php               
                    echo $c_textbox->Textbox ("parametro", "Modulo", 0, $pagina->id, "form-control no_mayus", 30, "", "", "");                   
                ?>              
                </td>              
            </tr>   
			</table>  
		</div>

		<div class="col-md-2 labelCustom">
				<tr class="">
						<td>Pagina oculta:</td>
						<td>
						<div id="divRadiooculto" class="radioValidate">
						<?php
							$c_radio = new Radio;
							$c_radio->Radio("oculto","Pagina oculta",$arrSiNo,"", 1, $pagina->oculto, "", 0, "customValidateRadio('oculto');");
							while($tmp_html = $c_radio->next_entry()) {
								echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
							}
						?>
						</div>
						</td>
					</div>
                

				<div class="col-md-2 labelCustom">
				<tr class="">
						<td>Aplica en el menu:</td>
						<td>
							<div id="divRadioaplica_menu" class="radioValidate">
                			<?php                   
                	    	$c_radio->Radio("aplica_menu","Aplica en el menu",$arrSiNo,"", 1, $pagina->aplica_menu, "", 0, "customValidateRadio('aplica_menu');");
                    		while($tmp_html = $c_radio->next_entry()) {
                        	echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                    		}
                		?>     
						</div>
						</td>
					</div>
				
				<div class="col-md-2 labelCustom">
				<tr class="">
						<td>Requiere Logueo?:</td>
						<td>
						<div id="divRadiorequiere_logueo" class="radioValidate">
							<?php					
								$c_radio->Radio("requiere_logueo","Requiere Logueo",$arrSiNo,"", 1, $pagina->requiere_logueo, "", 0, "customValidateRadio('requiere_logueo');");
								while($tmp_html = $c_radio->next_entry()) {
									echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
								}
							?>	
					</div>
                </div>


						</div>                    
                    </div>
                </div>
				


        <div class="row col-md-12" style="height:10px;">&nbsp;</div>    
        </form>
        <center>
            <input type="button" class="btn btn-primary" onclick="validarPagina();" value="Guardar"/>             
        </center>
    </div>
</div>
</div>  

<?php

//DETERMINAMOS SI HAY CONTENIDO
if ($pagina->id_tipo){
	echo "<script type=\"text/javascript\">";	
	echo "setTimeout(\"activarSelect(document.formaPagina.tipo_contenido);\",1000)";
	echo "</script>";
}

if ($pagina->target){
	echo "<script type=\"text/javascript\">";	
	echo "setTimeout(\"mostrarTamanio(document.formaPagina.target.value);\",1000)";
	echo "</script>";
}


?>
