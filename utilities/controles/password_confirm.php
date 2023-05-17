<?php
/**
 * Class PasswordConfirm
 * @author Andres Bravo
 */
class PasswordConfirm extends Textbox
{

  /**
   * Constructor de la Class
   */
  function PasswordConfirm($field_name = "", $name = "", $field_password = "", $is_obligatory = 1, $default = "", $style = "", $size = "", $max_length = "", $on_blur_script = "", $read_only = "", $print_only = "")
  {
    $this->Textbox($field_name, $name, $is_obligatory, $default, $style, $size, $max_length, "$on_blur_script;", $read_only, $print_only);

    $this->ID .= $field_password;
    $this->Type = "password";
    $this->AddID = 0;

    return $this->genCode();

  }

}

?>