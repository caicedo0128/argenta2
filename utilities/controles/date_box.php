<?php
/**
 * Class DateBox
 * @author Andres Bravo
 */
class DateBox extends Textbox
{

  var $FieldID;
  var $MinDate;
  var $MaxDate;
  var $DependMinDateField;

  /**
   * Constructor de la Class
   */
  function DateBox($field_name = "", $name = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "", $min_date = "", $max_date = "", $dependend_field = "")
  {
    $this->Textbox($field_name, $name, $is_obligatory, $default, $style, $size, $max_length, "$on_blur_script;", $read_only, $print_only);
    $this->MinDate = $min_date;
    $this->MaxDate = $max_date;
    $this->DependMinDateField = $dependend_field;

    return $this->genCode();

  }

  function genCode() {
    $tmp  = parent::genCode();
    $tmp .= $this->genCalendar();

    return $tmp;
  }

  function genTextbox() {
    $tmp  = parent::genCode();
    return $tmp;
  }

  function genCalendar() {
  
  
    $scriptLoad = "<script type='text/javascript'>";
    $scriptLoad .= "$(document).ready(function () { ";

    //INSTANCIA EL CALENDARIO
    $scriptLoad .= "$('#" . $this->FieldID . "').datetimepicker({ format: 'DD/MM/YYYY', showClear: true });";

    //VALIDAMOS SI HAY FECHA MINIMA PARA EL CALENDARIO
    if ($this->MinDate != null && $this->MinDate != "")
        //$scriptLoad += "$('#" . $this->FieldID  . "').data('DateTimePicker').minDate(moment('" . $this->MinDate.Value.Day . "/" . $this->MinDate.Value.Month . "/" . $this->MinDate.Value.Year . "', 'DD/MM/YYYY'));";

    //VALIDAMOS SI HAY FECHA MAXIMA PARA EL CALENDARIO
    if ($this->MaxDate != null && $this->MaxDate != "")
        //$scriptLoad .= "$('#" . $this->FieldID  . "').data('DateTimePicker').maxDate(moment('" . $this->MaxDate.Value.Day . "/" . $this->MaxDate.Value.Month . "/" . $this->MaxDate.Value.Year . "', 'DD/MM/YYYY'));";

    //VALIDAMOS SI HAY UNA DEPENDENCIA DE CAMPO PARA LA FECHA MINIMA
    if ($this->DependMinDateField != null && $this->DependMinDateField != "")
    {
        /*$scriptLoad .= "$('#". $this->DependMinDateField ."').on('dp.change', function (e) {$('#". $this->FieldID ."').val('');$('#". $this->FieldID ."').data('DateTimePicker').minDate(e.date);});";

        $scriptLoad .= "window.setTimeout(function (){if ($('#". $this->DependMinDateField ."').val() != ''){$('#". $this->FieldID ."').data('DateTimePicker').minDate(moment($('#" . $this->DependMinDateField . "').val(), 'DD/MM/YYYY'));}},300);";
        */
    }

    $scriptLoad .= "});";
    $scriptLoad .= "</script>";

    return $scriptLoad;
  }

}

?>