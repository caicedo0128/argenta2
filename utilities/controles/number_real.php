<?php
/**
 * Class NumberReal
 * @author Andres Bravo
 */
class NumberReal extends Textbox
{

  /**
   * Constructor de la Class
   */
  function NumberReal($field_name = "", $name = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "", $range_from = "", $range_to = "")
  {
   $this->Textbox($field_name, $name, $is_obligatory, $default, $style, $size, $max_length, "Indexcol_Reformatear_Campo__(this);verificarValorReal(this);$on_blur_script;Indexcol_Reformatear_Campo__(this);", $read_only, $print_only, "javascript: Indexcol_Mascara_Pesos__Real(this);", "javascript: Indexcol_Mascara_Pesos__Real(this);");

   $this->ID .= "__CNumeroReal".($range_from != $range_to?"[$range_from>$range_to]":"")."__".$this->FieldName;
   $this->AddID = 0;

   return $this->genCode();

  }

}

?>