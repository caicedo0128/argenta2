<?php
/**
 * Class Checkbox
 * @author Andres Bravo
 */
class Checkbox
{

  var $ID;
  var $Name;
  var $Query;
  var $IsObligatory;
  var $Default;
  var $IsMultiple;
  var $Style;
  var $PrintOnly;

  var $Results = array();
  var $Datasource;
  var $IsPrinted = 0;

  /**
   * Constructor de la Class
   */
  function Checkbox($field_name = "", $name = "", $datasource = "", $query = "", $is_obligatory = 1, $default = "", $is_multiple = 0, $style = "", $print_only = "")
  {

    $this->Name         = $name;
    $this->FieldName    = $field_name;
    if(is_array($datasource)) {
      $this->Results = $datasource;
    } else {
      $this->Datasource = $datasource;
      $this->Query = $query;
    }
    $this->IsObligatory = $is_obligatory;
    $this->Default      = $default;
    $this->IsMultiple   = $is_multiple;
    $this->Style        = $style;
    $this->PrintOnly    = $print_only;
    $this->Style .= ($this->IsObligatory?" required":"");
    $this->ID= $field_name;

    if($this->Query && is_object($this->Datasource)) {
      $db = $this->Datasource;
      $db->query($this->Query);
      while($db->next_row())
        $this->Results[$db->R[0]] = $db->R[1];
    }
  }

  function next_entry() {
    if(key($this->Results)) {
      $tmp = new HTMLControl($this->genCode(key($this->Results)),current($this->Results));
      next($this->Results);
      return $tmp;
    } else {
      return 0;
    }
  }

  function genCode($value, $onClickAction = "", $disabled = false) {
   $this->OnClickAction = $onClickAction;
    if(!$this->PrintOnly) {
      if(!$this->IsPrinted) {
        $this->IsPrinted++;
      }
      
      $tmp .= "<input type=\"checkbox\"";
      $tmp .= " id=\"$this->ID\"";
      $tmp .= " name=\"$this->FieldName".($this->IsMultiple?"[]":"")."\"";
      $tmp .= " value=\"$value\"";
      if($this->Style)
        $tmp .= " class=\"$this->Style\"";
      if((is_array($this->Default) && in_array($value,$this->Default)) || $value == $this->Default)
        $tmp .= " checked";
     if($this->OnClickAction)
        $tmp .= " OnClick=\"$this->OnClickAction\"";
     if($disabled)
        $tmp .= " disabled";
      $tmp .= ">";
    } else {
      if($this->Style)
        $tmp .= "<span class=\"$this->Style\">";

      if((is_array($this->Default) && in_array($value,$this->Default)) || $value == $this->Default)
        $tmp .= "X";

      if($this->Style)
        $tmp .= "</span>";
    }

    return $tmp;
  }

}

?>