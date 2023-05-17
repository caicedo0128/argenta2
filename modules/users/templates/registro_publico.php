<div id="registro">
    <table width="480px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div id="tabla_pronosticar2">REGISTRO  <span class="subtitle1">Para participar debes darnos algunos datos.</span></div></td>
      </tr>
      <tr>
        <td align="center">
		<br/><br/>
		<div id = "contenido_interno">
        <form id="datosRegistro" method="post" name="datosRegistro" action="index.php">
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$this->id_usuario?>" />
        <input type="hidden" name="id_perfil" id="id_perfil" value="3" />
        <input type="hidden" name="activo" id="activo" value="1" />
        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_registro">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td height="22" width="250px" class="textobuscador">Nombres</td>
            <td width="14">* </td>
            <td align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("nombres", "Nombres", 1, "$this->nombres", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td></td>
            <td  height="22" class="textobuscador">Apellidos</td>
            <td>* </td>
            <td align="left">
            <?php
                echo $c_textbox->Textbox ("apellidos", "Apellidos", 1, "$this->apellidos", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td></td>
            <td  height="22" class="textobuscador">Tipo Documento</td>
            <td>* </td>
            <td align="left" class="textobuscador" >
            <div id="divRadiotipo_doc" class="radioValidate" style="width:232px;height:25px;">
            <?php
                $c_radio = new Radio;
                $arrTiposDoc = array("1"=>"CC","2"=>"NUIP","3"=>"CE","4"=>"#PASAPORTE");
                $c_radio->Radio("tipo_doc","tipo_doc",$arrTiposDoc,"", 1, $this->tipo_documento, "", 0, "customValidateRadio('tipo_doc');");
                while($tmp_html = $c_radio->next_entry()) {
                    echo $tmp_html->getCode()."".$tmp_html->getLabel()."";
                }
            ?>
            </div>
            </td>
          </tr>
          <tr>
            <td></td>
            <td  height="22" class="textobuscador">Número Documento Identidad</td>
            <td>* </td>
            <td align="left" class="textobuscador">
            <?php
                echo $c_textbox->Textbox ("documento", "Documento", 1, $this->identificacion, "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td></td>
            <td  height="22" class="textobuscador">E-mail</td>
            <td>* </td>
            <td align="left">
            <?php
                $c_email = new Email;
                echo $c_email->Email("correo_personal", "Correo Personal", 1, $this->correo_personal, "form_registro", "30", "", "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="22" class="textobuscador">Celular</td>
            <td>*</td>
            <td align="left">
            <?php
                $c_textbox = new TextBox;
                echo $c_textbox->Textbox ("celular", "Celular", 1, $this->telefono_celular, "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="22" class="textobuscador">Contrase&ntilde;a</td>
            <td>
                <?php
                if(!$this->id_usuario){
                ?>
                *
                <? } ?>
            </td>
            <td align="left">
            <?php
            if(!$this->id_usuario)
                $requerido = 1;
            else
                $requerido = 0;
            $c_textbox = new Password;
            echo $c_textbox->Textbox ("contrasegna", "clave", $requerido, "", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="22" class="textobuscador">Confirmar contrase&ntilde;a</td>
            <td>&nbsp;</td>
            <td align="left">
            <?php

            $c_textbox = new PasswordConfirm("contrasegna_confirm", "confirmar contraseña", "contrasegna", $requerido, "", "form_registro", 30, "", "", "");
            echo $c_textbox->genCode();
            ?>
            </td>
          </tr>
          <tr >
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><div id="msjError"></div></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
			<div id="jugar">
			<a href="javascript:saveUser(false);">
				<img width="161" height="48" alt="Jugar" src="./images/page/btn_jugar.png">
			</a>
			</div>
			<br/><br/>
            </td>
          </tr>
          <tr >
            <td colspan="4" align="center" class="textobuscador"><br/><br/></td>
          </tr>
        </table>
        </form>
        </div>
        </td>
      </tr>
    </table>
</div>