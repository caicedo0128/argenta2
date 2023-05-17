<div id="registro" style="background-color:#fff;">
    <br/>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><h1>Formulario de Registro y Actualización de Departamentos para <?=$pais->pais?> .</h1></td>
      </tr>
      <tr>
        <td align="center">
        <br/>
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
        <input type="hidden" name="mod" value="zonificacion" />
        <input type="hidden" name="action" value="saveDepartamento" />
        <input type="hidden" name="Ajax" value="true" />
        <input type="hidden" name="id_departamento" id="id_departamento" value="<?=$departamento->id_departamento?>" />
        <input type="hidden" name="id_pais" id="id_pais" value="<?=$pais->id_pais?>" />
        <table width="470" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>                  
                                  
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Departamento:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox("departamento", "Departamento", 1, $departamento->departamento, "form_registro", 30);
            ?>            
            </td>
          </tr> 
          
          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
            <input type="button" onclick="saveDepartamento();" value="Guardar"/>      
            <input type="button" onclick="cerrarDerpartamento();" value="Regresar"/>       
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