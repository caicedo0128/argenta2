<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div>
</div> 
<div style="height:100px;">
<div class="col-md-12" id="content_estudio" style="">
	<table width="100%">
		<tr>
			<td><img id="logoArgenta" src="./images/logo.png"></td>
		</tr>
		<tr>
			<td align="center">Estudio de Riesgo</td>
		</tr>
	</table>
	<hr/>	
    <div class="">          
		<div class="row">            
			<div class="col-md-2 labelCustom">Generó:</div>
			<div class="col-md-3"><?=$_SESSION["user"]?></div>  				               
		</div>      
        <div class="row" style="height:10px;">&nbsp;</div>    
		<div class="row">            
			<div class="col-md-2 labelCustom">Impresión:</div>
			<div class="col-md-3"><?=date("Y-m-d")?></div>  				               
		</div>     
		<hr/>
		<div class="row">            
			<div class="col-md-2 labelCustom">Fecha registro:</div>
			<div class="col-md-3"><?=$estudio->fecha?></div>  				               
		</div>                
		<div class="row" style="height:10px;">&nbsp;</div>    
		<div class="row">            
			<div class="col-md-2 labelCustom">Razón social:</div>
			<div class="col-md-8"><?=$cliente->razon_social?></div>  				               
		</div>                
		<div class="row" style="height:10px;">&nbsp;</div>                
		<div class="row">
			<div class="col-md-2 labelCustom">Corte EEFF:</div>
			<div class="col-md-2"><?=$estudio->corte_eeff?></div>             
			<div class="col-md-1 labelCustom">Año:</div>
			<div class="col-md-3"><?=$estudio->anio?></div>              
		</div>   
		<div class="row" style="height:10px;">&nbsp;</div>                             
		<div class="row" id="campos_modelo">
			Seleccione el modelo que va aplicar.
		</div> 
		<div class="alert alert-success" id="content_condiciones">Condiciones de aprobación.</div> 
		<div class="row">
			<div class="col-md-2 labelCustom">Tasa:</div>
			<div class="col-md-2"><?=$estudio->tasa?>%</div>
			<div class="col-md-1 labelCustom">Cupo:</div>
			<div class="col-md-2"><?=formato_moneda($estudio->cupo)?></div>
			<div class="col-md-1 labelCustom">Plazo:</div>
			<div class="col-md-3"><?=$estudio->plazo?></div>               
		</div>            
		<div class="row" style="height:10px;">&nbsp;</div>             
		<div class="row">
			<div class="col-md-2 labelCustom">Observaciones:</div>
			<div class="col-md-10"> 
				<?=$estudio->observaciones?> 
			</div>
		</div>            
		<div class="row" style="height:10px;">&nbsp;</div> 
    </div>        
</div>
</div>
<script>

	function descargarPDF() {

		var element = document.getElementById("content_estudio");     
		var opt = {
		  margin:       0.5,
		  filename:     'estudioRiesgo<?=$cliente->razon_social?>.pdf',
		  image:        { type: 'jpeg', quality: 1 },
		  html2canvas:  { scale: 3 },
		  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
		  pagebreak:    { before: '.saltoPagina', avoid: 'img' }
		};			

		html2pdf()
		  .set(opt)
		  .from(element)
		  .save();
	}    

	$(document).ready(function () {
		$("#campos_modelo").load('admindex.php', { Ajax:true, mod: 'modelos', action:'camposModelo', id_modelo : <?=$estudio->id_modelo?>, id_estudio:<?=$idEstudio?>, impresion:1}, function () {
		});            
	});
</script>

