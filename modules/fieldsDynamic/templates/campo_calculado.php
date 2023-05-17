<?php

    echo "<div class='col-md-6 labelCustom'>".strtoupper(strtolower($campoClass->texto_imprimir)) . ":";
    echo "</div>";

    echo "<div class='col-md-6 text-right'>";
    if ($value != ""){
    	$valorImpresion = $value;
    	
    	if ($campoClass->cantidad_decimales > 0)
    		$valorImpresion = round($valorImpresion, $campoClass->cantidad_decimales);
		
		if ($campoClass->formato_imprimir != ""){
			if ($campoClass->formato_imprimir == "$")
				$valorImpresion=formato_moneda($valorImpresion);
			else if ($campoClass->formato_imprimir == "Por100")
				$valorImpresion=($valorImpresion*100)."%";
			else if ($campoClass->formato_imprimir == "Entre100")
				$valorImpresion=($valorImpresion/100)."%";	
			else if ($campoClass->formato_imprimir == "%")
				$valorImpresion=($valorImpresion)."%";				
		}
		
        echo $valorImpresion;
    }
    else
        echo "Sin cálculo";
    echo "</div>";

?>

