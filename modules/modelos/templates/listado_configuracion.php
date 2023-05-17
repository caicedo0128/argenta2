<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <div class="col-md-6" style="padding:0px;">
            <h4>Items de configuración</h4>
        </div>
        <div class="col-md-6" style="margin-top: 5px;padding:0px;">
            <a href="javascript:editGrupo(0);" style="float:right;" class="btn btn-warning btn-sm"><i class="fa fa-plus-square"></i>Agregar grupo</a>
        </div>
    </div>
</div>
<br/><br/><br/>
<br/>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  
  <?php
    while(!$rsGrupos->EOF){
        $idGrupo = $rsGrupos->fields["id_grupo"];
        $indiceGrupo = "grupo_" + $rsGrupos->fields["id_grupo"];
  ?>
      <div class="panel panel-info">
        <div class="panel-heading" role="tab" id="headingOne" style="background-color:<?=$rsGrupos->fields["color"]?>;color:<?=($rsGrupos->fields["color"]!=""?"#fff":"inherit");?>">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?=$indiceGrupo?>" aria-expanded="true" aria-controls="<?=$indiceGrupo?>">
              <?=$rsGrupos->fields["orden"]?> - <?=$rsGrupos->fields["grupo"]?>
            </a>
            <span style="float:right;">
            	Fila agrupación:<?=$rsGrupos->fields["ubicacion_impresion"]?>
                <a href="javascript:editCampo(0,<?=$idGrupo?>);" class="link_custom" title="Agregar campo"><i class="fa fa-plus-square"></i></a>
                <a href="javascript:editGrupo(<?=$idGrupo?>);" class="link_custom" title="Editar grupo"><i class="fa fa-edit"></i></a>                
                <a href="javascript:deleteGrupo(<?=$idGrupo?>);" class="link_custom evento_configuracion" title="Eliminar grupo"><i class="fa fa-times-circle"></i></a>
                <a href="javascript:changeOrderGrupo(<?=$idGrupo?>);" class="link_custom" title="Cambiar orden"><i class="fa fa-arrow-circle-up"></i></a>
            </span>
          </h4>
        </div>
        <div id="<?=$indiceGrupo?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <?php
                $this->listCamposGrupo($idGrupo);
            ?>
          </div>
        </div>
      </div>
  <?php
    $rsGrupos->MoveNext();
  }
  ?>
</div>

<script>
$(document).ready(function() {
   <?php
   	if ($bloquearModelo)
   		echo "ocultarEventosConfiguracion();";
   ?>
   
   
});

function editGrupo(idGrupo) {
    loader();
    $("#content_grupos").load('admindex.php', { Ajax:true, id_grupo: idGrupo, mod: 'modelos', action:'grupo'}, function () {
        $('#modalGrupo').modal('show');
        loader();
    });
}

function changeOrderGrupo(idGrupo) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=modelos&action=cambiarOrdenGrupo&id_grupo=" + idGrupo
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarConfiguracion();
            }
    });
}

function deleteGrupo(idGrupo) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=modelos&action=eliminarGrupo&id_grupo=" + idGrupo
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarConfiguracion();
            }
    });
}

function editCampo(idModeloCampo, idGrupo) {
    loader();
    $("#content_campos").load('admindex.php', { Ajax:true, id_modelo_campo: idModeloCampo, id_grupo: idGrupo, mod: 'modelos', action:'campo'}, function () {
        $('#modalCampo').modal('show');
        loader();
    });
}

function changeOrderCampo(idModeloCampo) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=modelos&action=cambiarOrdenCampo&id_modelo_campo=" + idModeloCampo
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarConfiguracion();
            }
    });
}

function deleteCampo(idModeloCampo) {

    showLoading("Enviando informacion. Espere por favor...");

    var strUrl = "admindex.php";
    var dataForm = "Ajax=true&mod=modelos&action=eliminarCampo&id_modelo_campo=" + idModeloCampo
    $.ajax({
            type: 'POST',
            url: strUrl,
            dataType: "json",
            data:dataForm,
            success: function (response) {
                closeNotify();
                showSuccess("Transacción exitosa. Espere por favor...");
                cargarConfiguracion();
            }
    });
}


function ocultarEventosConfiguracion(){
	$(".evento_configuracion").hide();
}

</script>

<div id="modalGrupo" class="modal fade" role="dialog" aria-labelledby="modalGrupo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Agregar grupo</h4>
            </div>
            <div class="modal-body" id="content_grupos">

            </div>
        </div>
    </div>
</div>

<div id="modalCampo" class="modal fade" role="dialog" aria-labelledby="modalCampo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModal">Agregar campo</h4>
            </div>
            <div class="modal-body" id="content_campos">

            </div>
        </div>
    </div>
</div>
