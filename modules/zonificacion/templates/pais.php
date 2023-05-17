<div id="registro">
    <br/>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><h1>Formulario de Registro y Actualización de Paises.</h1></td>
      </tr>
      <tr>
        <td align="center">
        <br/>
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
        <input type="hidden" name="mod" value="zonificacion" />
        <input type="hidden" name="action" value="savePais" />
        <input type="hidden" name="id_pais" id="id_pais" value="<?=$idPais?>" />
        <table width="470" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>                  
                  
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Cod. País:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox("cod_pais", "Cod pais", 1, $pais->cod_pais, "form_registro",12);
            ?>            
            </td>
          </tr>   
                
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">País:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox("pais", "Pais", 1, $pais->pais, "form_registro", 30);
            ?>            
            </td>
          </tr> 
          
          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
            <input type="button" onclick="savePaises();" value="Guardar"/>      
            <input type="button" onclick="window.location.href='admindex.php?mod=zonificacion&action=listPaises';" value="Regresar"/>       
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