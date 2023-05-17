<?php
/**
 * Clase Textarea
 * @author Andres Bravo
 */
class Textarea
{

  var $ID;
  var $Name;
  var $FieldName;
  var $IsObligatory;
  var $Default;
  var $Style;
  var $Rows;
  var $Cols;
  var $OnBlurScript;
  var $PrintOnly;
  var $CountCharacters;
  var $CountCharactersValue;

  /**
   * Constructor de la clase
   */

  function Textarea($field_name = "", $name = "", $is_obligatory = 0, $default = "", $style = "", $cols = "", $rows = "", $on_blur_script = "", $print_only = "", $read_only = "", $count_characters = "", $count_characters_value = "")
  {
    $this->Name         = $name;
    $this->FieldName    = $field_name;
    $this->IsObligatory = $is_obligatory;
    $this->Default      = $default;  
    $this->Style        = $style;
    $this->Rows         = $rows;
    $this->Cols         = $cols;
    $this->OnBlurScript = $on_blur_script;
    $this->PrintOnly    = $print_only;
    $this->ReadOnly    = $read_only;
    $this->CountCharacters = $count_characters;
    $this->CountCharactersValue = $count_characters_value;
    $this->ID= $field_name;
    $this->Style .= ($this->IsObligatory?" required":"");
   

    return $this->genCode();

  }

  function genCode() {
    if(!$this->PrintOnly) {
          
      $tmp .= "<textarea ";
      $tmp .= " id=\"$this->ID\"";
      $tmp .= " name=\"$this->FieldName\"";
      if($this->Style)
        $tmp .= " class=\"$this->Style\"";
      if($this->Rows)
        $tmp .= " rows=\"$this->Rows\"";
      if($this->Cols)
        $tmp .= " cols=\"$this->Cols\"";
      if($this->OnBlurScript)
        $tmp .= " onblur=\"$this->OnBlurScript\"";
      if($this->ReadOnly)
        $tmp .= " readonly";

    $this->CountCharacters = $count_characters;
    $this->CountCharactersValue = $count_characters_value;

      $tmp .= ">$this->Default</textarea>";
    } else {
      if($this->Style)
        $tmp .= "<span class=\"$this->Style\">";

      $tmp .= $this->Default;

      if($this->Style)
        $tmp .= "</span>";
    }
    return $tmp;
  }


}

?>