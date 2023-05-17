<?php
/**
 * Class Radio
 * @author Andres Bravo 
 */
require_once("html_control.php");
class Radio
{

  var $ID;
  var $Name;
  var $FieldName;
  var $Query;
  var $IsObligatory;
  var $Default;
  var $Style;
  var $PrintOnly;

  var $Results = array();
  var $Datasource;
  var $IsPrinted = 0;
  var $OnClick;

  /**
   * Constructor de la Class
   */

  function Radio($field_name = "", $name = "", $datasource = "", $query = "", $is_obligatory = 1, $default = "", $style = "", $print_only = "", $on_click = "")
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
    $this->Style        = $style;
    $this->PrintOnly    = $print_only;
    $this->IsPrinted    = 0;
    $this->OnClick      = $on_click;
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

  function genCode($value) {
    if(!$this->PrintOnly) {
      if(!$this->IsPrinted) {
        $this->IsPrinted++;
      }
      $tmp .= "<input type=\"radio\"";
      $tmp .= " id=\"$this->ID\"";
      $tmp .= " name=\"$this->FieldName\"";
      $tmp .= " value=\"$value\"";
      if($this->Style)
        $tmp .= " class=\"$this->Style\"";
      if($value == $this->Default)
        $tmp .= " checked = 'checked'";
      if($this->OnClick)
        $tmp .= " onclick=\"$this->OnClick\"";

      $tmp .= " />";
    } else {
      if($this->Style)
        $tmp .= "<span class=\"$this->Style\">";

      if($value == $this->Default)
        $tmp .= "X";

      if($this->Style)
        $tmp .= "</span>";
    }
    return $tmp;
  }


}

?>