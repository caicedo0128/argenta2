            </div>
            <div class="overlayCustom" style="display: none">
            </div>
            <br/><br/><br/>
        </div>        
    </div>
</div>
<div>
    <div class="row-fluid navbar-fixed-bottom">
        <div class="content_footer col-md-12">
            <div class="col-md-5">Soportado por <a href="http://www.irsoftware.com.co" title="IR Software" target="_blank">IRSoftware</a> - <?=date("Y")?> - Aplicaciones web a la medida - Cel. 318 3751591</div>
            <div class="col-md-7 fechahora">Fecha: <?=date("d")?> de <?=date("M")?> de <?=date("Y")?></div>
        </div>
    </div>
</div>
<form id="formExport" name="formExport" method="post" action="admindex.php" target="_blank">
    <input type="hidden" name="mod" value="reportes">
    <input type="hidden" name="action" value="exportarExcel">
    <input type="hidden" name="Ajax" value="true">
    <input type="hidden" name="__dataReporte" id="__dataReporte">
    <input type="hidden" name="__tituloReporte" id="__tituloReporte">
</form>
<form id="formMail" name="formMail" method="post" action="" target="_blank">
    <input type="hidden" name="mod" id="mod">
    <input type="hidden" name="action" id="action">
    <input type="hidden" name="__option1" id="__option1">
    <input type="hidden" name="__option2" id="__option2">
    <input type="hidden" name="__dataMail" id="__dataMail">
    <input type="hidden" name="__subjectMail" id="__subjectMail">
    <input type="hidden" name="__toEmailMail" id="__toEmailMail">
    <input type="hidden" name="__toNameMail" id="__toNameMail">
    <input type="hidden" name="__files" id="__files">
    <input type="hidden" name="__template" id="__template">
</form>

