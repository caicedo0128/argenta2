<div id="registro">
    <table width="480px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div id="tabla_pronosticar2">INGRESO  <span class="subtitle1">Ingresa con tus datos registrados.</span></div></td>
      </tr>
      <tr>
        <td align="center">
		<br/><br/>

		<div id = "contenido_interno">

        <form id="form_login" name="form_login" method="post" action="">
        <table width="100%" border="1" cellspacing="2" cellpadding="2" class="tabla_registro">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>
          <tr>
            <td width="14">&nbsp;</td>
            <td height="22" width="250px" class="textobuscador">E-mail</td>
            <td width="14">* </td>
            <td align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox ("username", "username", 1, "", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
		  <tr>
            <td>&nbsp;</td>
            <td height="22" class="textobuscador">Contrase&ntilde;a</td>
            <td width="14">* </td>
            <td>
			<?php
				$c_textbox = new Password;
				echo $c_textbox->Textbox ("pass", "clave", 1, "", "form_registro", 30, "", "", "");
            ?>
            </td>
          </tr>
          <tr >
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><div id="msjError"></div></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="3" align="left" class="textobuscador"><br/>
            Si aún no estás registrado ingresa <a href="index.php?mod=users&action=register" class="link_ingreso">AQUÍ.</a><br/><br/>
            Para recordar tu contraseña haz clic <a href="javascript:rememberPass();" class="link_ingreso">AQUÍ.</a>
			<div id="jugar">
			<a href="javascript:login(false);">
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
