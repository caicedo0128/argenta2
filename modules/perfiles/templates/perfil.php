<script>
	
function savePermisos(){

    var msjError = "";

    validateForm("formaPagina");

    if ($("#formaPagina").valid() && msjError == ""){

        showLoading("Enviando información. Espere por favor...");
            
        var strUrl = "admindex.php";
        var dataForm = new FormData(document.getElementById("formaPagina"));

        $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data: dataForm,
            mimeType: "multipart/form-data",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                closeNotify();
                if (response.Success){
                    showSuccess("Transacción exitosa. Espere por favor...");
                    editPerfil(<?=$idPerfil?>, 'perfiles', 'editarPerfil');                     
                }
                else{                   
                    showError(response.Msg, 10000);
                }
            }
        });
    }
    else{
        showError("Por favor revise los campos marcados." + msjError);
    }
}

function marcarTodos(obj){

    if (obj.checked)
        $("input:checkbox[id=id_acciones]").attr('checked', 'checked');
    else
        $("input:checkbox[id=id_acciones]").removeAttr('checked');
}
                            
</script>	

<div class="">
<div class="panel panel-bordered-primary">
    <div class="panel-body panel-custom-interno">
        Registro de información de perfiles
        <div class="cerrar_form" onclick="cargarPerfiles();" title="Volver"><i class="fa fa-reply fa-lg"></i></div>
        <hr class="separador_titulo"/>
        <form name="formaPagina" id="formaPagina" action="admindex.php" method="post" enctype="multipart/form-data">		
        <input type="hidden" name="Ajax" value="true">
        <input type="hidden" name="id_perfil" value="<?=$idPerfil?>">
        <input type="hidden" name="mod" value="perfiles">
        <input type="hidden" name="action" value="guardarPermisos">
        <div class="row col-md-12" style="height:10px;">&nbsp;</div>
        <div class="row row-form">
            <div class="col-md-2">
                Nombre perfil:
            </div>
            <div class="col-md-3">
                <?=$perfilData->perfil?>
            </div>      
        </div>
        <div class="row col-md-12" style="height:10px;">&nbsp;</div>    
        <center>            
            
        </center>    
        <div class="row col-md-12" style="height:10px;">&nbsp;</div>    
        <div id="tabsPerfil">    
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item tab_custom"><a href="#content-permisos" class="nav-link active" aria-controls="content-permisos" role="tab" data-toggle="tab">Asignación de permisos</a></li>               
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane show active" id="content-permisos">
                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" id="" width="100%">
                    <thead>
                        <tr>
                        <th>Accion - alias</th>
                        <th><input type="checkbox" id="marcar_acciones" onclick="marcarTodos(this);">Asignar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                        foreach($arrAcciones as $key=>$value){
                            
                            $strChecked = "";
                            if (is_array($arrPermisos) && Count($arrPermisos) > 0){
                                
                                if (in_array($key, $arrPermisos)){
                                    $strChecked = "checked='checked'";
                                }
                            }
                        
                            echo "<tr>";
                            echo "<td>".$value."</td>";
                            echo "<td>";
                            echo "<input type='checkbox' id='id_acciones' name='permiso_accion[]' value='".$key."' ".$strChecked.">";
                            echo "</td>";
                            echo "</tr>";
                        }

                    ?>
                    </tbody>
                </table> 
                <center>            
                    <input type="button" class="btn btn-primary" onclick="savePermisos();" value="Guardar"/> 
                </center>                 
                </div>                
            </div>      
        </div> 
        </form>
    </div>
</div>
</div>  
<?php


?>
