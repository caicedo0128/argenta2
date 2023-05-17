<?php
/**
 * Class FileBox
 * @author Andres Bravo
 */
class FileBox extends Textbox
{

  /**
   * Constructor de la Class
   */
  function FileBox($field_name = "", $name = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "")
  {
    $this->Textbox($field_name, $name, $is_obligatory, $default, $style, $size, $max_length, "$on_blur_script;", $read_only, $print_only);

    $this->ID = $this->FieldName;
    $this->AddID = 0;
    $this->Type = "file";

    return $this->genCode();

  }

}

?>