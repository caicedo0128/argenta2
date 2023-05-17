<?php
/**
 * Class Password
 * @author Andres Bravo
 */
class Password extends Textbox
{

  /**
   * Constructor de la Class
   */
  function Password($field_name = "", $name = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "")
  {
    $this->Textbox($field_name, $name, $is_obligatory, $default, $style, $size, $max_length, "$on_blur_script;", $read_only, $print_only);

    $this->ID = $this->FieldName;
    $this->Type = "password";
    $this->AddID = 0;

    return $this->genCode();

  }

}

?>