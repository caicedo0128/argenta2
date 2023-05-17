<?php
/**
 * Class Select
 * @author Andres Bravo 
 */
class Select
{

  var $ID;
  var $Name;
  var $FieldName;
  var $Query;
  var $IsObligatory;
  var $Default;
  var $IsMultiple;
  var $Size;
  var $Style;
  var $OnChangeAction;
  var $PrintOnly;

  var $ShowBlankOption = 0;
  var $TextBlankOption;
  var $Results = array();
  var $Datasource;
  var $Disabled = false;

  var $JsCode;


  function enableBlankOption($texto = "") {
    $this->ShowBlankOption = 1;
    $this->TextBlankOption = $texto;
  }

  function disableBlankOption() {
    $this->ShowBlankOption = 0;
  }

 function Clean(){
    $this->Results=NULL;
 }
  /**
   * Constructor de la Class
   */
  function Select($field_name = "", $name = "", $datasource = "", $query = "", $is_obligatory = 1, $default = "", $style = "", $is_multiple = 0, $size = 0, $onChangeAction = "", $print_only = 0)
  {

    $this->Name       = $name;
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
    $this->Size         = $size;
    $this->Style        = $style;
    $this->OnChangeAction = $onChangeAction;
    $this->PrintOnly    = $print_only;
    $this->Style .= ($this->IsObligatory?" required":"");
    $this->ID = $field_name;

    if($this->Query && is_object($this->Datasource)) {
      global $db;         
      $rs = $datasource->Execute($this->Query);
      while(!$rs->EOF){
        $this->Results[$rs->fields[0]] = $rs->fields[1];
        $rs->MoveNext();
      }
    }

    return $this->genCode();
  }

  function genCode() {
    if(!$this->PrintOnly) {
      if($this->JsCode)
        $tmp .= $this->JsCode;
      $tmp .= "<select";
      $tmp .= " id=\"$this->ID\"";
      $tmp .= " name=\"$this->FieldName".($this->IsMultiple?"[]":"")."\"";
      if($this->IsMultiple)
        $tmp .= " multiple";
      if($this->Size)
        $tmp .= " size=\"$this->Size\"";
      if($this->Style)
        $tmp .= " class=\"$this->Style\"";
      if($this->OnChangeAction)
        $tmp .= " onchange=\"$this->OnChangeAction\"";
      if($this->Disabled)
        $tmp .= " disabled=\"disabled\"";        
      $tmp .= ">\n";

      if($this->ShowBlankOption){
        $strTextOption = "Seleccione uno...";
        if ($this->TextBlankOption)
            $strTextOption = $this->TextBlankOption;

        $tmp .= "<option value=\"\">".$strTextOption."</option>\n";
      }


      foreach($this->Results as $key=>$val)
        $tmp .= "<option value=\"$key\"".((is_array($this->Default) && in_array($key,$this->Default)) || $key == $this->Default?" selected='selected'":"").">$val</option>\n";

      $tmp .= "</select>\n";
    } else {
      if($this->Style)
        $tmp .= "<span class=\"$this->Style\">";

      if(is_array($this->Default)) {
        foreach($this->Default as $def)
          $tmp .= ($tmp?", ":"").$this->Results[$def];
      } else if($this->Default) {
        $tmp .= $this->Results[$this->Default];
      }

      if($this->Style)
        $tmp .= "</span>";
    }
    return $tmp;
  }
  
}

?>