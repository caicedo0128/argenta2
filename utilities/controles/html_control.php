<?php
/**
 * Class Checkbox
 * @author Andres Bravo
 */
 
class HTMLControl {
  var $Code;
  var $Label;

  function HTMLControl($code = "", $label = "") {
    $this->Code = $code;
    $this->Label = $label;
  }

  function getCode() {
    return $this->Code;
  }

  function setCode($code) {
    $this->Code = $code;
  }

  function getLabel() {
    return $this->Label;
  }

  function setLabel($label) {
    $this->Label = $label;
  }

}

?>