<div id="listado_info">
<br/>
<table width="100%" border="0" id="tableDataGrid" align="left">
    <tbody>
        <tr>
            <td class="titlecolumns_admin"><h1><?=$appObj->textGral["LIST_TEXT"]?></h1></td>
        </tr>
        <tr>
            <td align="left">
                <table id="optionsHeader">
                    <tbody>
                        <tr>
                        <td class="titleOptionsHeader_admin">                            
                            
                        </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" height="300">
                <div id="resultDatos">                   
                    <form id="datosRegistro" method="post" name="datosRegistro" action="index.php">
                    <input type="button" onclick="saveTexts();" value="Guardar Textos"/><br/><br/>
                    <table cellspacing="1" cellpadding="1" border="0" align="center" id="tableData" style="width: 100%">
                    <thead>
                        <tr>                            
                            <td align='center'>Parametro</td>
                            <td align='center'>Valor</td>                            
                            <!--td align='center'>Descripcion</td-->
                        </tr>
                    </thead>
                    <?php
                        
                        $i = 1;
                        while (!$rsParam->EOF){
                            
                            $idParametro= $rsParam->fields["id"];
                           
                            echo "<tr>";
                            echo "<td>".$rsParam->fields["param"]."</td>";                            
                            echo "<td>";
                            echo "<textarea name='param_val_".$i."' id='param_val_".$i."' class='required' cols='110' rows='3'>".$rsParam->fields["text"]."</textarea>";
                            echo "<input type='hidden' name='param_id_".$i."' id='param_id_".$i."' value='".$rsParam->fields["id"]."' size='80' class='required'>";
                            echo "</td>";
                            //echo "<td>".$rsParam->fields["descripcion"]."</td>";                            
                            echo "</tr>";
                            $i++;
                            $rsParam->MoveNext();
                        }
                    
                    ?>
                    </table>
                    <input type="hidden" name="total_param" value="<?=$i?>" id="total_param">
                    </form>
            </td>
        </tr>
    </tbody>
</table>    
</div>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "iDisplayLength": 50,
        "bSort": false
    });        
});
</script>