<style>
.modal-dialog {
    max-width:70% !important;
    width:70% !important;
}
</style>
<div id="content-log">
<?php
	if ($tipoProceso==1){
?>
	<div class="alert alert-info">Respuesta evento inscripción</div>
	<?php	
		$res=($factura->msj_inscripcion!=""?$factura->msj_inscripcion:"No disponible aún");	
		echo "<pre>".$res."</pre>";
	?>
<?php
	}
?>

<?php
	if ($tipoProceso==2){
?>
	<div class="alert alert-info">Respuesta evento mandato</div>
	<?php

		$res=($factura->msj_mandato!=""?$factura->msj_mandato:"No disponible aún");	
		echo "<pre>".$res."</pre>";
	?>
<?php
	}
?>

<?php
	if ($tipoProceso==3){
?>
	<div class="alert alert-info">Respuesta evento endoso</div>
	<?php

		$res=($factura->msj_endoso!=""?$factura->msj_endoso:"No disponible aún");	
		echo "<pre>".$res."</pre>";
	?>
<?php
	}
?>

<?php
	if ($tipoProceso==4){
?>
	<div class="alert alert-info">Respuesta evento informe para el pago</div>
	<?php

		$res=($factura->msj_informe!=""?$factura->msj_informe:"No disponible aún");	
		echo "<pre>".$res."</pre>";
	?>
<?php
	}
?>

<?php
	if ($tipoProceso==5){
?>
	<div class="alert alert-info">Respuesta evento pago</div>
	<?php

		$res=($factura->msj_pago!=""?$factura->msj_pago:"No disponible aún");	
		echo "<pre>".$res."</pre>";
	?>
<?php
	}
?>	
</div>
