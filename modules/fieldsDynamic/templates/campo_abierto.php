<?php

echo "<div class='col-md-6 labelCustom'>".strtoupper(strtolower($campoClass->texto_imprimir)) . ":";
echo "</div>";


echo "<div class='col-md-6'>";
$c_textbox = new Textbox;

$tipoDato = "";
$evento = "";
if ($campoClass->tipo_abierto == "number"){
	$tipoDato = $campoClass->tipo_abierto;
	$evento = "return IsNumberNeg(event);";
}

echo $c_textbox->Textbox ("campo_dinamico[".$id."]", $name, $required, "$value", "form-control-custom text-right ".$tipoDato, "", "", "", "","",$evento);
if ($campoClass->texto_ayuda != "")
	echo "<small>".$campoClass->texto_ayuda."</small>";
echo "</div>";

?>

