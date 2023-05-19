<style>
.dropdown-submenu{
    position: relative;
}
.dropdown-submenu a::after{
    transform: rotate(-90deg);
    position: absolute;
    right: 3px;
    top: 40%;
}
.dropdown-submenu:hover .dropdown-menu, .dropdown-submenu:focus .dropdown-menu{
    display: flex;
    flex-direction: column;
    position: absolute !important;
    margin-top: -30px;
    left: 100%;
}
@media (max-width: 992px) {
    .dropdown-menu{
        width: 50%;
    }
    .dropdown-menu .dropdown-submenu{
        width: auto;
    }
}

</style>
<div class="navbar navbar-default navbar-fixed-top1">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand titulo-logo" href="admindex.php">Argenta</a>
      <button data-target="#navbar-main" data-toggle="collapse" type="button" class="navbar-toggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="navbar-main" class="navbar-collapse collapse titulo-menu">
      <ul class="nav navbar-nav">
		<?php
			//DETERMINAMOS SI HAY PERFIL EN SESION
			if ($_SESSION["profile_text"] != "Cliente"){
		?>
        <li class='administrador consulta'><a href="admindex.php?mod=clientes&action=searchClients">Terceros</a></li>
        <li class='administrador consulta'><a href="admindex.php?mod=clientes&action=vinculacion">Vinculaci�n</a></li>
        <li class='administrador consulta'><a href="admindex.php?mod=operaciones&action=BuscadorOperaciones">Operaciones</a></li>
        <li class="dropdown">
          <a id="themes" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">Reportes <span class="caret"></span></a>
          <ul aria-labelledby="themes" class="dropdown-menu">
          	<li class='administrador consulta'><a href="admindex.php?mod=flujo&action=flujoCaja">Flujo de caja</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteInversiones">Inversiones</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteFacturasVigentes">Facturas vigentes</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteFacturasCanceladas">Facturas canceladas</a></li>
			<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#">Cartera por edades <span class="caret"></span></a>
				<ul class="dropdown-menu">
		            <!--li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteCartera">Cartera vigente</a></li-->
		            <!--li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteCarteraVencida">Cartera vencida</a></li-->
		            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteCarteraMora">Cartera</a></li>
				</ul>
	        </li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteComisiones">Comisiones</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteContable">Contable</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteMovimiento">Movimientos</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=reportes&action=reporteFacturacion">Facturaci�n</a></li>
          </ul>
        </li>
        <li class="dropdown administrador consulta">
          <a id="themes" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">Parametros <span class="caret"></span></a>
          <ul aria-labelledby="themes" class="dropdown-menu">
          	<li><a href="admindex.php?mod=tipoDocumentos&action=listTipoDocumentos">Tipos documento</a></li>
          	<li><a href="admindex.php?mod=paginas&action=verListado">M�dulos</a></li>
          	<li><a href="admindex.php?mod=perfiles&action=verListado">Perfiles</a></li>
            <li><a href="admindex.php?mod=admin&action=listParamGral">Parametros Generales</a></li> 
            <li class='administrador consulta'><a href="admindex.php?mod=fieldsDynamic&action=listFields">Campos</a></li>
            <li class='administrador consulta'><a href="admindex.php?mod=modelos&action=listModelos">Modelos</a></li>
          </ul>
        </li>
        <li class='administrador consulta'>
          <a href="admindex.php?mod=users&action=listUsers">Usuarios sistema</a>
        </li>
        <?php
        	}
        ?>
		<?php
			//DETERMINAMOS SI HAY PERFIL EN SESION
			if ($_SESSION["profile_text"] == "Cliente"){
		?>
			<li class='administrador cliente'>
			  <a href="admindex.php?mod=clientes&action=vinculacion">Mi Empresa</a>
			</li>
			<li class='administrador cliente'>
			  <a href="admindex.php?mod=clientes&action=listDocumentosClienteVinculacion">Documentaci�n vinculaci�n</a>
			</li>
			<li class='administrador cliente'>
			  <a href="admindex.php?mod=operaciones&action=simulador">Simulador</a>
			</li>

			<li class='administrador cliente'>
			  <a href="admindex.php?mod=operaciones&action=BuscadorOperaciones">Operaciones</a>
			</li>
			<li class="dropdown">
			  <a id="themes" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">Reportes <span class="caret"></span></a>
			  <ul aria-labelledby="themes" class="dropdown-menu">
				<li class='administrador cliente'><a href="admindex.php?mod=reportes&action=reporteFacturasVigentes">Estado de cuenta</a></li>
				<li class='administrador cliente'><a href="admindex.php?mod=reportes&action=reporteFacturasCanceladas">Facturas canceladas</a></li>
			  </ul>
			</li>
        <?php
        	}
        ?>
        <?php
            if ($_SESSION["login"]){
        ?>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle dropdown-main" aria-expanded="false">
						<i class="fa fa-user fa-fw"></i><i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li>
							<a href="#"><i class="fa fa-gear fa-fw"></i> Cambiar contraseña</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="javascript:logout(true);"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
						</li>
					</ul>
				</li>
        <?php
            }
        ?>
      </ul>
    </div>
  </div>
</div>

<div class=" navbar navbar-default navbar-fixed-top">

  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand titulo-logo" href="admindex.php">Argenta</a>
  </div>
    <div class="navbar-collapse collapse titulo-menu">
    <ul class="nav navbar-nav">
    <?php
    echo $strMenu;
    //print_R($_SESSION);

?>
    </ul>

  </div>

</div>
          </div>
<script>

        $(document).ready(function () {        
            /*$("#mainnav-menu-wrap").mCustomScrollbar({
                scrollButtons:{enable:true}
                //theme:"light-thick"
            });*/
        });

        function actualizarDatos(){

            loader();
            $("#page-content").load("admindex.php?Ajax=true&mod=users&action=actualizarDatos", {}, function () {
                loader();
            });        
        }

        function cargarContenido(url, seleccion, id) {
            
            loader();
            $("#content_general").load("admindex.php?Ajax=true&" + url, {}, function () {
                loader();
                toggleActiveMenu("menu_" + id);
                $("#breadcrumb-content").html(seleccion);                    
                goObjHtml("breadcrumb-content", 70);
            });

        }

        function toggleActiveMenu(idObj) {

            $(".active-sub").removeClass("active-sub");
            $("#" + idObj).addClass("active-sub");
        }
        
        function verMenu(){
            $("#mainnav-container").removeClass("mainnav-oculto").addClass("mainnav-visible");
        }
        
        function ocultarMenu(){
            $("#mainnav-container").removeClass("mainnav-visible").addClass("mainnav-oculto");
        }        

</script>




