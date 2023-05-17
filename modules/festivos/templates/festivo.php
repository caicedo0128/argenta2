<script type="text/javascript">

function saveFestivo(){

    validateForm("datosRegistro");

    if ($("#datosRegistro").valid()){

        showLoading("Enviando informacion...");
        var dataForm = "Ajax=true&" + $("#datosRegistro").serialize();
        var strUrl = "admindex.php";
        $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    showSuccess(response.Message);
                    if (response.Success) {
                        window.location.href="admindex.php?mod=festivos&action=listFestivos&";
                    }
                }
        });
    }
}

</script>
<div id="registro">
    <br/>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td><h1>Formulario de Registro y Actualización de Días Festivos.</h1></td>
      </tr>
      <tr>
        <td align="center">
        <br/>
        <form id="datosRegistro" method="post" name="datosRegistro" action="admindex.php" enctype="multipart/form-data">        
        <input type="hidden" name="mod" value="festivos" />
        <input type="hidden" name="action" value="saveFestivo" />
        <input type="hidden" name="id_festivo" id="id_festivo" value="<?=$idFestivo?>" />
        <table width="470" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td height="7" colspan="4"></td>
          </tr>                  
                 
          <tr>
            <td width="14">&nbsp;</td>
            <td width="130" height="22" class="textobuscador">Fecha Festivo:</td>
            <td width="14">* </td>
            <td width="290" align="left">
            <?php
                $c_textbox = new Textbox;
                echo $c_textbox->Textbox("fecha", "fecha", 1, $this->fecha, "form_registro", 30);
            ?>            
            </td>
          </tr> 
          
          <tr>
            <td></td>
            <td colspan="3" align="center" class="textobuscador"><br/>
            <input type="button" onclick="saveFestivo();" value="Guardar"/>      
            <input type="button" onclick="window.location.href='admindex.php?mod=festivos&action=listFestivos';" value="Regresar"/>       
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
<script>
    $(document).ready(function (){
        $("#fecha").datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>