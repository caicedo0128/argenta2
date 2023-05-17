<div id="registro">
    <br/>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><h1>Formulario de Registro y Actualización de Ciudades para <?=$departamento->departamento?>-<?=$pais->pais?> .</h1></td>
      </tr>
      <tr>
        <td align="center">
        <br/>
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">
        <input type="hidden" name="mod" value="zonificacion" />
        <input type="hidden" name="action" value="saveCiudad" />
        <input type="hidden" name="Ajax" value="true" />
        <input type="hidden" name="id_ciudad" id="id_ciudad" value="<?=$ciudad->id_ciudad?>" />
        <input type="hidden" name="id_departamento" id="id_departamento" value="<?=$departamento->id_departamento?>" />
        <table width="470" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>

          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Ciudad:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox("ciudad", "Ciudad", 1, $ciudad->ciudad, "form_registro", 30);
            ?>
            </td>
          </tr>

		  <tr>
            <td></td>
            <td width="130" height="22" class="textobuscador">Zona:</td>
            <td>* </td>
            <td align="left" id="sede" class="textobuscador">
            <?php

                $sede_select = new Select("id_zona","Zona",$arrZonas,"",1,"", "form_registro", 0, "", "", 0);
                $sede_select->enableBlankOption();
                $sede_select->Default = $ciudad->id_zona;
                echo $sede_select->genCode();
            ?>
            </td>
          </tr>

          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
            <input type="button" onclick="saveCiudad();" value="Guardar"/>
            <input type="button" onclick="cerrarCiudad();" value="Regresar"/>
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