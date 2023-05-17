<script>
</script>

<div class="">

  
  <?php
  	$filaAux = -1;
    while(!$rsGrupos->EOF){
    	$colorLetra = "#000000";	
        $idGrupo = $rsGrupos->fields["id_grupo"];
        $indiceGrupo = "grupo_" . $rsGrupos->fields["id_grupo"];
        $color = "#CECECE";
        
        $saltoPagina = "";
        if ($idGrupo == "4" || $idGrupo == "9")
        	$saltoPagina = "saltoPagina";
        
        if ($rsGrupos->fields["color"]!="")
        	$color = $rsGrupos->fields["color"];
        $filaGrupoImpresion = $rsGrupos->fields["ubicacion_impresion"];
        $columna = $rsGrupos->fields["columna"];
		if ($filaAux != $filaGrupoImpresion){
			echo "<div class='row ".$saltoPagina."' style='clear:both;'>";
		}
		if ($color != "#CECECE")
			$colorLetra = "#ffffff";
		
  ?>
      <div class="col-md-<?=$columna?>" style="font-size:10px !important;">
        <div class="panel-heading" style="background-color:<?=$color?>;color:<?=$colorLetra?>;font-weight:bold;">
          <span class="panel-title" style="font-size:10px !important;">
              <?=$rsGrupos->fields["orden"]?> - <?=$rsGrupos->fields["grupo"]?>
          </span>
        </div>
        <div id="<?=$indiceGrupo?>" class="">
          <div class="panel-body">
			<?php
				$rsCampos=$this->listCamposDinamicosGrupo($idGrupo);
                while(!$rsCampos->EOF)
                {
                    $idCampo = $rsCampos->fields["id_campo"];
                    $esObligatorio = $rsCampos->fields["es_obligatorio"];
                    $tipoCampo = $rsCampos->fields["tipo_campo"];
            ?>

					<div class="row" style="display:<?=$display?>;">
						<?php
							$valorInstancia = $arrCamposInstancia[$idCampo];
							if ($imprimir == 1)
								$campos->genFieldByType(4, $idCampo,$valorInstancia, $esObligatorio);
							else
								$campos->genFieldByType($tipoCampo, $idCampo,$valorInstancia, $esObligatorio);
						?>
					</div>   
					<div class="row" style="height:10px;display:<?=$display?>;">&nbsp;</div>

            <?php 
                    $rsCampos->MoveNext();
                }
            ?>  
          </div>
        </div>
      </div>
  <?php
  	$filaAux = $filaGrupoImpresion;
    $rsGrupos->MoveNext();
    $filaGrupoImpresion = $rsGrupos->fields["ubicacion_impresion"];
	if ($filaAux != $filaGrupoImpresion){
		echo "</div>";
	}    
  }
  ?>
           
</div>