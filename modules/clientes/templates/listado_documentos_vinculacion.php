<script type="text/javascript">

function descargarReporte(tipoReporte){

    showLoading("Descargando reporte. Espere por favor...");

    //REPORTE oferta
    if (tipoReporte == 1){
        action = "VerReporte";
        nombreReporte = "Oferta.pdf";
    }
    //REPORTE Pagar�
    else if (tipoReporte == 2){
        action = "VerFormatoPagare";
        nombreReporte = "Pagare.pdf";
    }
    //REPORTE carta
    else if (tipoReporte == 3){
        action = "VerCartaInstrcciones";
        nombreReporte = "CartaInstrucciones.pdf";
    }
    //REPORTE general
    else if (tipoReporte == 4){
        action = "versionImpresa";
        nombreReporte = "FormularioVinculacion.pdf";
    }

    //GENERAMOS EL REPORTE PDF
    var dataForm = "Ajax=true&mod=clientes&action=" + action + "&es_reporte=true&id_cliente=<?=$idCliente?>";
    var strUrl = "admindex.php";
    $.ajax({
        type: 'POST',
        url: strUrl,
        dataType: "html",
        data:dataForm,
        success: function (response) {

            $("#formMail input[id=mod]").val("clientes");
            $("#formMail input[id=action]").val("guardarInformacionCliente");
            $("#formMail input[id=__dataMail]").val(response);

            var dataForm = "Ajax=true&" + $("#formMail").serialize();
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:dataForm,
                success: function (response) {
                    closeNotify();
                    downloadURI("./gallery/clientes/reporte.pdf", nombreReporte);
                }
            });
        }
    });
    //FIN GUARDADO PDF
}

function descargarDocumento(archivo){
	downloadURI("./gallery/formatos_estaticos/" + archivo, archivo);
}

</script>
<style>
.panel-primary.panel-colorful {
  background-color: #25476a;
  border-color: #25476a;
  color: #fff;
}

.pad-all {
  padding-top: 10px !important;
  padding-bottom: 5px !important;
}

.text-2x {
  font-size: 2em;
}

.text-semibold {
  font-weight: 600;
}

.media-body, .media-left, .media-right {
  display: table-cell;
  vertical-align: top;
}

.media-left, .media > .pull-left {
  padding-right: 10px;
  padding-left:15px;
}

</style>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Informaci�n de documentos para vinculaci�n</h4>
    </div>
</div>
<div id="content_documentos_vinculacion" class="" style="clear:both;padding-top:15px;">
        <div class="row" style="background:#fff;">
            <div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarReporte(1);">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">Oferta</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                    <div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Contiene las condiciones econ�micas de las operaciones a realizar.</li>
                    	<li>2. �sta debe ser firmada y huellada por el representante legal, en se�al de aceptaci�n y sello de la compa��a en caso de manejarlo.</li>
                    	</ul>
                    	</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarReporte(2);">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">Pagar�</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                    <div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. En blanco como garant�a de las operaciones a realizar.</li>
                    	<li>2. Debe ser firmado y huellado por el representante legal, y sello de la compa��a en caso de manejarlo.
                    	<div class="row" style="height:28px;">&nbsp;</div>
                    	</li>                    	                    	
                    	</ul>                          	
                    	</small>
                    </div>                    
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarReporte(3);">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">Carta instrucciones</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                    <div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Para el diligenciamiento del pagar�.</li>
                    	<li>2. Debe ser firmada y huellada por el representante legal sello de la compa��a en caso de manejarlo.</li>
                    	<li>3. Debe ser autenticado con biometr�a ante notaria.</li>
                    	<div class="row" style="height:13px;">&nbsp;</div>
                    	</ul>
                    	</small>
                    </div>                      
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarDocumento('01.Endoso_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">01. Endoso</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
					<div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Debe diligenciarse con los datos del emisor de la factura firma y huella del representante legal, se imprime al reverso de la factura original que cumpla requisitos de ley.</li>
                    	<div class="row" style="height:29px;">&nbsp;</div>
                    	</ul>
                    	</small>
                    </div>                       
                </div>
            </div>            
        </div>
        <div class="row" style="height:3px;">&nbsp;</div>
        <div class="row" style="background:#fff;">            
            <div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarDocumento('02.Contrato_de_Cesion_Facturas_Electronicas_con_Responsabilidad_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">02. Cesi�n facturas electr�nicas</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
					<div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Debe diligenciar el contrato con los datos correspondientes a la facturaci�n a negociar.</li>
                    	<div class="row" style="height:29px;">&nbsp;</div>
                    	</ul>
                    	</small>
                    </div>                     
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarDocumento('03.Notificacion_de_Endoso_Revisado.doc')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">03. Notificaci�n Endoso</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
					<div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Debe diligenciarse con los datos de las facturas a descontar, se imprime en papeler�a membreteada firmada por el representante legal y debe radicarse ante su cliente "Pagador de la Factura".</li>
                    	</ul>
                    	</small>
                    </div>                     
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarDocumento('04.Aceptacion_Endoso_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">04. Aceptaci�n Endoso</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
					<div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Documento emitido por su cliente, "Pagador de la factura",  puede ser por correo electr�nico pero debe ser enviado por el pagador directamente al correo eleyva@argentaestructuradores.com (no aceptamos reenvio).</li>
                    	</ul>
                    	</small>
                    </div>                     
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer;padding-bottom:1px !important;" onclick="descargarDocumento('05.Instruccion_de_Giro_General_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">05. Instrucci�n giro general</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                    <div class="" style="background-color:#28a2c6;border-top:1px solid">
                    	<small>
                    	<ul style="margin-bottom:0px !important;padding-left:5px !important;text-align:justify;">
                    	<li>1. Diligencie los datos de la cuenta que ser� beneficiaria de los desembolsos de las operaciones, debe imprimirse en papeler�a membretada de la compa��a firmada y huella por representante legal y sello en caso de manejarlo.</li>
                    	</ul>
                    	</small>
                    </div>                       
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer" onclick="descargarDocumento('07.Instruccion_de_Giro_ESPECIFICA_Varios_Beneficiarios_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">07. Instrucci�n giro espec�fica</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer" onclick="descargarDocumento('08.Instruccion_de_Giro_Confirming_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">08. Instrucci�n giro confirming</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-primary panel-colorful media middle pad-all" style="cursor:pointer" onclick="descargarDocumento('09.Correccion_de_Vencimiento_Revisado.docx')">
                    <div class="media-left">
                        <div class="pad-hor">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <p class="text-2x mar-no text-semibold" style="margin:0px !important;">09. Correci�n vencimiento</p>
                        <p class="mar-no" style="margin:0px !important;">Descargar</p>
                    </div>
                </div>
            </div>
		</div>

</div>
<script>
$(document).ready(function() {

});
</script>

