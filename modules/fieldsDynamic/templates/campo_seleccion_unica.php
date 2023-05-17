<?php

    echo "<div class='col-md-6 labelCustom'>".strtoupper(strtolower($campoClass->texto_imprimir)) . ":";
    echo "</div>";

    echo "<div class='col-md-6'>";
    $select = new Select("campo_dinamico[".$id."]",$name,$arrValues,"",$required,"", "form-control-custom", 0, "", "", 0);
    $select->enableBlankOption();
    $select->Default = $value;
    echo $select->genCode();
	if ($campoClass->texto_ayuda != "")
		echo "<small>".$campoClass->texto_ayuda."</small>";        
    echo "</div>";


?>