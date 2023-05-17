<?php
/**
 * Class Autocomplete
 * @author Andres Bravo
 */

class Autocomplete extends Textbox
{

  /**
   * Constructor de la Class
   */
  function Autocomplete($field_name = "", $id_name = "", $style = "", $size = "", $max_length = "", $default="", $is_obligatory = "", $on_blur_script = "", $on_change_script = "", $data = "")
  {

    if ($data){

		$defaultText = "";
		if ($default)
			$defaultText = $this->getValueText($data,$default);

        $this->Textbox($field_name."_autocomplete", $field_name."_autocomplete", $is_obligatory, $defaultText, $style, $size, $max_length, $on_blur_script, $read_only, $print_only);

        $tmp = $this->genCode();

        $tmp .= "<script>\n";
        $tmp .= "$(document).ready(function(){\n";
        $tmp .= "var data = ".$data.";\n";
        $tmp .= "$('#".$field_name."_autocomplete').autocomplete({\n";
        $tmp .= "         minLength: 0,\n";
        $tmp .= "         source: data,\n";
        $tmp .= "         select: function( event, ui ) {\n";
        $tmp .= "                   $('#".$field_name."').val(ui.item.id);\n";
		$tmp .= "							var label = ui.item.label;\n";
		$tmp .= "							var value = ui.item.value;\n";
		//$tmp .= "							$this = $(this);\n";
		$tmp .= "							setTimeout(function () {\n";
		$tmp .= "								$('#".$field_name."_autocomplete').val(value);\n";
		$tmp .= "							}, 1);\n";

        if ($on_change_script)
        	$tmp .= "                   ".$on_change_script.";\n";

        $tmp .= "                   return false;\n";
        $tmp .= "         }\n";
        $tmp .= "    })";
        $tmp .= ".data('autocomplete')._renderItem = function( ul, item ) {\n";
        $tmp .= "    return $( '<li>' )\n";
        $tmp .= "        .data( 'item.autocomplete', item )\n";
        $tmp .= "        .append( '<a style=\"cursor:pointer;\">' + item.value + '</a>' )\n";
        $tmp .= "        .appendTo(ul);\n";
        $tmp .= "};\n";
        $tmp .= "});\n";
        $tmp .= "</script>\n";
        $tmp .= "<input type='hidden' id='".$field_name."' name='".$field_name."' value='".$default."'>";
    }

    return $tmp;

  }


  function getValueText($data, $default){


  	$arrData = json_decode($data);

  	foreach($arrData as $key=>$value){

  		if ($value->id == $default)
  			return $value->value;
  	}

  	return "";

  }

}

?>