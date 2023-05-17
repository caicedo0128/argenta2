<?php
/**
 * Class Textbox
 * @author Andres Bravo
 */
class Textbox
{

  var $ID;
  var $Type = "text";
  var $Name;
  var $FieldName;
  var $IsObligatory;
  var $Default;
  var $Style;
  var $Size;
  var $MaxLength;
  var $OnBlurScript;
  var $ReadOnly;
  var $PrintOnly;
  var $OnKeyUpScript;
  var $OnFocusScript;

  var $AddID = 1;

  /**
   * Constructor de la Class
   */
  function Textbox($field_name = "", $name = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "", $on_keyup_script = "", $on_focus_script = "")
  {
    $this->Name         = $name;
    $this->FieldName    = $field_name;
    $this->IsObligatory = $is_obligatory;
    $this->Default      = $default;
    $this->Style        = $style;
    $this->Size         = $size;
    $this->MaxLength    = $max_length;
    $this->OnBlurScript = $on_blur_script;
    $this->ReadOnly     = $read_only;
    $this->PrintOnly    = $print_only;
    $this->OnKeyUpScript = $on_keyup_script;
    $this->OnFocusScript = $on_focus_script;
    $this->Style .= ($this->IsObligatory?" required":"");
    $this->ID= $FieldName;

    return $this->genCode();

  }

  function genCode() {
    if(!$this->PrintOnly) {

      if(!$this->ID)
        $this->ID = "$this->FieldName";

      $tmp .= "<input type=\"$this->Type\"";
      $tmp .= " id=\"$this->ID\"";
      $tmp .= " name=\"$this->FieldName\"";
      $tmp .= " value=\"$this->Default\"";
      if($this->Style)
        $tmp .= " class=\"$this->Style\"";
      if($this->Size)
        $tmp .= " size=\"$this->Size\"";
      if($this->MaxLength)
        $tmp .= " maxlength=\"$this->MaxLength\"";
      if($this->ReadOnly)
        $tmp .= " readonly";
      if($this->OnBlurScript)
        $tmp .= " onblur=\"$this->OnBlurScript\"";
      if($this->OnKeyUpScript)
        $tmp .= " onkeydown=\"$this->OnKeyUpScript\"";
      if($this->OnFocusScript)
        $tmp .= " onfocus=\"$this->OnFocusScript\"";
      $tmp .= " />";
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