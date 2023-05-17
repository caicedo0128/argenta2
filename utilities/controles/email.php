<?php
/**
 * Class Email
 * @author Andres Bravo 
 */
class Email extends Textbox
{

  /**
   * Constructor de la Class
   */
  function Email($field_name = "", $name = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "")
  {
    $this->Textbox($field_name, $name, $is_obligatory, $default, $style, $size, $max_length, "$on_blur_script;", $read_only, $print_only);
    
    $this->Style .= " email ";
    $this->AddID = 0;

    return $this->genCode();

  }

}

?>