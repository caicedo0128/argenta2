<div id="registro">
    <br/>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
      <tr>
        <td><h1>Registro y Actualización de Oficinas</h1></td>
      </tr>
      <tr>
        <td align="center">

        <form id="datosRegistro" method="post" name="datosRegistro" action="index.php">
        <input type="hidden" name="mod" id="mod" value="oficinas" />
        <input type="hidden" name="action" id="action" value="saveOficina" />
        <input type="hidden" name="id_oficina" id="id_oficina" value="<?=$this->id_oficina?>" />
        <table width="470" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Código Oficina:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("codigo_oficina", "Codigo Oficina", 1, "$this->codigo_oficina", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Nombre:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("nombre", "Nombre", 1, "$this->nombre", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Ciudad:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_autocomplete = new Autocomplete;
                echo $c_autocomplete->Autocomplete ("id_ciudad", "ciudad", "form_registro", 60, "", "$this->id_ciudad", 1,"","",$jsonCiudades);
            ?>
            </td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Contácto:</td>
            <td width="14"> </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("contacto", "Contacto", 0, "$this->contacto", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Dirección:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("direccion", "Dirección", 1, "$this->direccion", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Teléfono 1:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("telefono1", "Teléfono", 1, "$this->telefono1", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Teléfono 2:</td>
            <td width="14"> </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("telefono2", "Teléfono", 0, "$this->telefono2", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td></td>
            <td width="130" height="22" class="textobuscador">Email:</td>
            <td> </td>
            <td align="left">
            <?php
                $c_email = new Email;
                echo $c_email->Email("email", "Email", 0, $this->email, "form_registro", "30", "", "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td></td>
            <td width="130" height="22" class="textobuscador">Activo:</td>
            <td>* </td>
            <td align="left" class="textobuscador" >
            <div id="divRadioEstado" class="radioValidate">
            <?php
                $c_radio = new Radio;
                $arrSiNo = array("1"=>"Si","2"=>"No");
                $c_radio->Radio("Estado","Estado",$arrSiNo,"", 1, $this->estado, "", 0, "customValidateRadio('Estado');");
                while($tmp_html = $c_radio->next_entry()) {
                    echo $tmp_html->getCode()."&nbsp;".$tmp_html->getLabel()."&nbsp;&nbsp;";
                }
            ?>
            </div>
            </td>
          </tr>
          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
            <input type="button" onclick="saveOficina();" value="Guardar"/>
            <input type="button" onclick="cerrarOficina();" value="Regresar"/>
            </td>
          </tr>
          <tr >
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><center>Los campos marcados con (*) son obligatorios.</center></td>
          </tr>
        </table>
        </form>
        </td>
      </tr>
    </table>
</div>
