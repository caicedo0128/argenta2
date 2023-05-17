<div id="header" class="container-fluid" style="margin-bottom:60px;">
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <!--sidebar-->
        <?php
            if ($_SESSION["login"]){
        ?>
            <div id="" class="container-fluid col-md-12">
                <?=$appObj->getMenu()?>
            </div>
			<div id="content_breadcrumb" class="col-sm-6 col-md-6" style="margin-top:0px !important;">
				<ul class="">
					<li>
						<a href="admindex.php" title="Inicio"><span class='fa fa-home' style="color:#979FA1; "></span> / </a>
					</li>
					<li class="text-breadcrumb" style=""></li>
				</ul>
			</div>
            <div class="col-sm-6 col-md-6" id="text-loader" style="display:inline-block;padding-right:1px !important;">
                <small>Usuario:<?=$_SESSION["user"]?> - Empresa:<?=$_SESSION["tercero"]?> - <?=$_SESSION["nit_tercero"]?></small>
            </div>
        <?php
            }
        ?>
        <div id="content_general" class="container-fluid body-content col-sm-11 col-md-12">
            <div id="msj_session" class="alert alert-success">
                Se ha detectado inactividad en el sistema, por su seguridad su sesión se cerrará automáticamente en <b><span id="segundos">120</span></b> segundos. Si desea continuar por favor haga clic <a href="javascript:activarSession(0);">aquí</a>
            </div>
            <div id="content_page">
                <div class="overlayCustom" style="display: none">
                </div>











