<script type="text/javascript">

function verFlujoCaja(idFlujoCaja, fecha){
	
	var dataSoporte = $("#content_soporte_" + idFlujoCaja).html();
	if (dataSoporte == "" || dataSoporte == null)
		dataSoporte = "No hay información registrada para esta fecha";
		
	var dialog = bootbox.dialog({
		title: "Flujo de caja " + fecha,
		message: dataSoporte
	});
	$(".bootbox").show().addClass("show").find("div.modal-dialog").css({ "width": "90%" });

}

</script>
<style>

</style>
<div class="row-fluid">
    <div class="col-md-12 bg-primary-custom">
        <h4>Soporte flujos de caja - 10 últimos soportes guardados</h4>
    </div>
</div>			
<br/><br/><br/>
<div class="container-fluid " style="clear:both;padding-top:10px; background-color:#fff;padding-bottom:10px;">				
	<div class="" style=''>
	<?php
		while(!$rsFlujosCaja->EOF)
		{
			$classText="text-danger";
			if ($rsFlujosCaja->fields["soporte"]!="" && $rsFlujosCaja->fields["soporte"]!=null){
				$classText="text-success";
				echo "<div id='content_soporte_".$rsFlujosCaja->fields["id_flujo_caja"]."' style='display:none;'>";
    			echo "<div class='panel panel-primary'>";
        		echo "<div class='panel-body'>";  				
				echo "<div class=''>".$rsFlujosCaja->fields["soporte"]."</div>";
				echo "</div>";	
				echo "</div>";	
				echo "</div>";				
			}
			echo "<div class='text-center left ".$classText."' style='float:left;padding:5px;margin-left:5px;border:1px solid;'>";
			echo $rsFlujosCaja->fields["fecha"];
			echo "<br/>";
			echo "<a href=\"javascript:verFlujoCaja(".$rsFlujosCaja->fields["id_flujo_caja"].",'".$rsFlujosCaja->fields["fecha"]."');\" title='Ver soporte flujo de caja' class='".$classText."'><i class='fa fa-eye'></i></a>";
			echo "</div>";
			$rsFlujosCaja->MoveNext();
		}
	?>	
	</div>	
</div>
<br/>
