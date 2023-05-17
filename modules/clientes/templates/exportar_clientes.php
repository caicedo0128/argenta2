<?php

    header("Content-Type:application/vnd.ms-excel; charset=utf-8");
    header("Content-type:application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=listadoClientes.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
?>    
<table border="1">
    <tr>
        <td colspan="15">
            <h1>Listado terceros</h1>
        </td>
    </tr>
    <tr>
        <td colspan="15">Usuario: <?=$_SESSION["user"]?></td>
    </tr>
    <tr>
        <td colspan="15">Fecha: <?=date("Y-m-d")?></td>
    </tr>
    <tr>
        <td colspan="15">&nbsp;</td>
    </tr>
</table>
<table border="1">
        <tr>                            
            <td>Fecha registro</td>             
            <td>Tipo tercero</td>             
            <td>Raz�n social o nombre</td> 
            <td>Identificaci�n</td>
            <td>Direcci�n</td>   
            <td>Tel�fono fijo 1</td>
            <td>Tel�fono fijo 2</td>
            <td>Celular 1</td>
            <td>Celular 2</td>            
            <td>Ciudad</td>
            <td>Persona autorizada</td>   
            <td>Tel�fonos persona autorizada</td>                       
            <td>Correo electr�nico</td>   
            <td>Cupo</td>   
            <td>Ejecutivo</td>   
            <td>Porcentaje comisi�n</td>   
            <td>Activo</td>
        </tr>
    <?php

        while(!$rsData->EOF){
        
            echo "<tr>";
            echo "<td>".$rsData->fields["fecha_registro"]."</td>";
            echo "<td>".$rsData->fields["nombre"]."</td>";
            echo "<td>".$rsData->fields["razon_social"]."</td>";
            echo "<td>".$rsData->fields["identificacion"]."</td>";
            echo "<td>".$rsData->fields["direccion"]."</td>";
            echo "<td>".$rsData->fields["telefono_fijo"]."</td>";
            echo "<td>".$rsData->fields["telefono_fijo1"]."</td>";
            echo "<td>".$rsData->fields["telefono_celular"]."</td>";
            echo "<td>".$rsData->fields["telefono_celular1"]."</td>";
            echo "<td>".$rsData->fields["ciudad"]."</td>";
            echo "<td>".$rsData->fields["encargado"]."</td>";
            echo "<td>".$rsData->fields["telefonos_encargado"]."</td>";
            echo "<td>".$rsData->fields["correo_personal"]."</td>";
            echo "<td>".($rsData->fields["cupo"] != ""?$rsData->fields["cupo"]:"N/D")."</td>";
            echo "<td>".$rsData->fields["ejecutivo"]."</td>";
            echo "<td>".$rsData->fields["comision"]."</td>";
            echo "<td>".($rsData->fields["activo"]==1?"Si":"No")."</td>";
            echo "</tr>";

            $rsData->MoveNext();
        }
    ?>
</table>
