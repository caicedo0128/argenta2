<div id="registro">
    <br/>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><h1>Formulario de Registro y Actualización de Zonas para <?=$ciudad->ciudad?>-<?=$departamento->departamento?>-<?=$pais->pais?> .</h1></td>
      </tr>
      <tr>
        <td align="center">
        <br/>
        <form id="datosRegistroZona" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">
        <input type="hidden" name="mod" value="zonificacion" />
        <input type="hidden" name="action" value="saveZona" />
        <input type="hidden" name="Ajax" value="true" />
        <input type="hidden" name="id_zona_sector" id="id_zona_sector" value="<?=$zona->id_zona_sector?>" />
        <input type="hidden" name="id_ciudad" id="id_ciudad" value="<?=$ciudad->id_ciudad?>" />
        <table width="470" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>

          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Zona:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox("zona_sector", "Zona", 1, $zona->zona_sector, "form_registro", 30);
            ?>
            </td>
          </tr>

          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
            <input type="button" onclick="saveZona();" value="Guardar"/>
            <input type="button" onclick="cerrarZona();" value="Regresar"/>
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