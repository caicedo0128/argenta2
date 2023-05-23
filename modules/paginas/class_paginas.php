<?php
/**
* Adminsitraci�n de paginas de la aplicacion
* @author Andres Bravo
* @version 1.0
* El constructor de esta clase es {@link paginas()}
*/
require_once("class_paginas_extended.php");
class paginas {


	var $Database;
  	var $ID;


  	/**
      * Funci�n para seleccionar opciones de administrador
      */
  	function parseAdmin() {

 		global $db,$id,$action,$option,$option2,$appObj; 

		switch($appObj->action){

 			case "verListado":
							$this->listadoAdmin();
 							break;
 			case "editarPagina":
							$this->editarPagina();
 							break;
 			case "guardarPagina":
							$this->guardarPagina();
 							break; 
 			case "eliminarPagina":
							$this->eliminarPagina();
 							break;  
 			case "verficarAlias":				
							$this->verficarAlias();
 							break; 
 			case "cambiarOrden":				
							$this->cambiarOrden();
 							break;  							
 		}

  	}


  	/**
  	  * Funci�n para eliminar paginas de posicion
  	  */
  	function eliminarPagina() {

  		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

		//INSTANCIAMOS LA CLASE DE LA TABLA
  		$pagina = new app_paginas();

		$loadReg = $pagina->load("id_pagina=".$_REQUEST['id_pagina']);

		//DETERMINAMOS SI LA PAGINA TIENE PAGINAS HIJAS
		$strSQL = "SELECT count(*) as total FROM app_paginas WHERE id_pagina_padre='".$pagina->alias."'";
		$rs = $db->Execute($strSQL);
		
		$totalHijos = 0;
		if (!$rs->EOF){
			$totalHijos = $rs->fields["total"];
		}
		
		if ($totalHijos<=0){
			$pagina->Delete();
			$msjProcesoRealizado = "Proceso realizado con exito";
            $success = true;
		}
		else{
  			$msjProcesoRealizado = "La pagina no se puede eliminar por que tiene sub paginas asociadas.";
            $success = false;
        }
  			
                            
        $jsondata['Success'] = $success;
        $jsondata['Message'] = $msjProcesoRealizado;
        
        echo json_encode($jsondata);
        exit;   
  	}

  	/**
  	  * Funci�n para mover las paginas de posicion
  	  */
  	function cambiarOrden() {
  		
  		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
		
		//INSTANCIAMOS LA CLASE DE LA TABLA
  		$pagina1 = new app_paginas();
  		$pagina2 = new app_paginas();
  		
  		$loadReg = $pagina1->load("id_pagina=".$_REQUEST['id_pagina']);  		
  		$ordenPag1 = $pagina1->orden;
  		
  		$strSQL = "SELECT id_pagina FROM app_paginas WHERE orden < " . $ordenPag1 . " AND id_pagina_padre='".$_REQUEST["id_padre"]."'";  		  		
	    $rsPaginaAnterior = $this->findSQL($strSQL, "orden","DESC",1,1);
	    if (!$rsPaginaAnterior->EOF){	    	
	    	$loadRegPagina = $pagina2->load("id_pagina=".$rsPaginaAnterior->fields["id_pagina"]);
	    	$ordenPag2 = $pagina2->orden;    	
	    }  		
	    else
	    	$ordenPag2 = $ordenPag1;    	
  		
  		//SI HAY UN ORDEN PARA EL SEGUNDO REGISTRO SE ACTUALIZAN LOS DATOS
  		if ($pagina2->orden>0){
  			$pagina1->orden = $ordenPag2;  		
  			$pagina2->orden = $ordenPag1;
  		 		
			$pagina1->Save();
			$pagina2->Save();				
		}
  		
        $success = true;
                
        $jsondata['Success'] = $success;
        
        echo json_encode($jsondata);
        exit;   
  		
  	}

  	/**
  	  * Funci�n para verificar el alias
  	  */
  	function verficarAlias() {
  	
  		global $db,$id,$action,$option,$option2,$appObj;
  		
  		$alias = $_POST["alias"];
  		$strSQL = "SELECT alias FROM app_paginas WHERE alias='".$alias."'";
  		$rsAlias = $db->Execute($strSQL);
  		
  		if ($rsAlias->EOF)
  			$success = false;
  		else
  			$success = true;
  		  		
        $jsondata['Success'] = $success;
        
        echo json_encode($jsondata);
        exit;  		
  	}


  	/**
  	  * Funci�n para guardar la informacion de la pagina
  	  */
  	function guardarPagina() {
  	
  		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
			
		//INSTANCIAMOS LA CLASE DE LA TABLA
  		$pagina = new app_paginas();  		
        
        $idPagina = $_POST['id_pagina'];
		$loadReg = $pagina->load("id_pagina=". $idPagina);
				
		//DETERMINAMOS SI EL ALIAS CAMBIA PARA ACTUALIZAR TODAS SUS PAGINAS HIJAS
		if ($_POST["alias"]!=$pagina->alias){			
			$strSQL = "UPDATE app_paginas SET id_pagina_padre='".$_POST["alias"]."' WHERE id_pagina_padre='".$pagina->alias."'";
			$db->Execute($strSQL);
		}		
		
		$pagina->alias=$_POST['alias'];
		$pagina->id_pagina_padre=($_POST['id_pagina_padre'])?$_POST['id_pagina_padre']:0;
		$pagina->nombre=utf8_decode($_POST['nombre']);
        $pagina->titulo_html=utf8_decode($_POST['titulo_html']);
		$pagina->id_tipo=$_POST['tipo_contenido'];
		$pagina->oculto=$_POST['oculto'];
		$pagina->aplica_menu=$_POST['aplica_menu'];
		$pagina->link_externo=$_POST['link_externo'];
        $pagina->modulo=$_POST['modulo'];
        $pagina->accion=$_POST['accion'];
        $pagina->id=$_POST['parametro'];         
		$pagina->target=$_POST['target'];
		$pagina->ancho=0;
		$pagina->alto=0;		
		$pagina->requiere_logueo=$_POST['requiere_logueo'];
        $pagina->imagen_menu=$_POST['imagen_menu'];
        $pagina->descripcion="N/A";
        $pagina->palabras_clave="N/A";        
				
		//DETERMINAMOS SI EL REGISTRO SE INSERTA PARA ACTUALIZAR SU ORDEN AL MAXIMO DE LA PAGINA
        if ($idPagina=="0"){
			$strSQL = "SELECT (orden + 1) as nuevoOrden FROM app_paginas WHERE id_pagina_padre='".$pagina->id_pagina_padre."' ORDER BY orden DESC LIMIT 0,1";
			$rsOrden = $db->Execute($strSQL);
			
			$nuevoOrden = 1;
			if(!$rsOrden->EOF)
				$nuevoOrden = $rsOrden->fields["nuevoOrden"];
			
			$pagina->orden = $nuevoOrden;	
		}
  				  
  		$pagina->Save();		
  		
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
  	}

  	/**
  	  * Funci�n para mostrar el formulario de editar una pagina en el administrador
  	  */
  	function editarPagina() {
  	
  		global $db,$id,$action,$option,$option2,$appObj;
						
        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/select.php");  
        require_once("./utilities/controles/textbox.php");  
        require_once("./utilities/controles/textarea.php");  
        require_once("./utilities/controles/radio.php");  
        require_once("./utilities/controles/filebox.php");
						
		//INSTANCIAMOS LA CLASE DE LA TABLA
  		$pagina = new app_paginas();  	
 		
 		$idPagina = $_REQUEST["id_pagina"];
 		$idPadre = $_REQUEST["id_padre"];
 		$idPaginaPadre = $_REQUEST["id_pagina_padre"];
		
		$loadReg = $pagina->load("id_pagina=".$idPagina);		
		 		
 		//TRAEMOS LAS PAGINAS PADRE
  		$arrPaginas = $this->getPaginasPadre();  		   		
   		
  		$arrTiposContenido = array("plugin"=>"Modulo",
  								   "externo"=>"Link Externo"
  								  );
  								  
  		$arrSiNo = array("1"=>"Si","-1"=>"No");

  		$arrTarget = array("_self"=>"En la misma ventana","_blank"=>"En una ventana nueva","lightbox"=>"En un Lightbox");  		
  		
  		include("./modules/paginas/templates/admin_editar_paginas.php"); 
  		
  	}

  	/**
  	  * Funci�n para mostrar el listado de paginas en el administrador
  	  */
  	function listadoAdmin() {
  		
  		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;;
  	
		include("./utilities/class_dataGrid.php");
	
		
		//INSTANCIAMOS LA CLASE DATA GRID	
		$dataGrid = new DataGrid($this);	

		$dataGrid->idDataGrid = "resultDatos";
		$dataGrid->heightDG = "300";
		
		$idPadre = 0;		
		if ($_REQUEST["id_padre"]){
			$idPadre = $_REQUEST["id_padre"];		
			$idPaginaPadre = $_REQUEST["id_pagina_padre"];		
		}
			
		//TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
		$strSQL = "SELECT id_pagina,id_pagina_padre,alias,nombre,titulo_html,id_tipo,oculto,aplica_menu FROM app_paginas WHERE id_pagina_padre='".$idPadre."' ORDER BY orden";			
	
		//TRAEMOS LA CONSULTA A EJECUTAR
		$dataGrid->SQL = $strSQL;

		//INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
		if ($idPadre!="0")
			$dataGrid->titleList="<h1>Administrar paginas de la aplicaci�n: <a href='admindex.php?mod=paginas&action=verListado' class='titletableLink'>Primer nivel</a>=><a href='admindex.php?mod=paginas&action=verListado&id_padre=".$idPaginaPadre."' class='titletableLink'>Nivel anterior</a></h1>";
		else
			$dataGrid->titleList="<h1>Administrar paginas de la aplicaci�n</h1>";
			
		$dataGrid->classTitleList="titletable";

		//INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
		$dataGrid->titleProcess = $msjProcesoRealizado;
		
		//CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
		
		$dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("Agregar","javascript:editPagina(0,'paginas','editarPagina','".$idPadre."','".$idPaginaPadre."')","btn-primary","fa-plus-square");


		//CREAR OPCIONES DE PIE EN EL DATA GRID
		$dataGrid->optionsFooter=false;
		
		//IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
		$dataGrid->addTitlesHeader(array("Alias","Nombre","Titulo HTML","Tipo","Oculto","Aplica menu"));
		
		//OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
		$dataGrid->addColumnHide(array("id_pagina","id_pagina_padre"));

		//CAMPOS DE SOLO DOS VALORES		
		$arrValues = array("1","-1");
		$arrText = array("SI","NO");
		$dataGrid->addFieldTwoValues("oculto",$arrValues,$arrText); 
		$dataGrid->addFieldTwoValues("aplica_menu",$arrValues,$arrText); 		
		
		//CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET		
        $arrVarGet1 = Array("id_pagina"=>"ID_PAGINA","mod"=>"paginas","action"=>"editarPagina","id_padre"=>$idPadre,"id_pagina_padre"=>$idPaginaPadre);
		$arrVarGet2 = Array("id_padre"=>"ALIAS","mod"=>"paginas","action"=>"verListado","id_pagina_padre"=>$idPadre);
		$arrVarGet3 = Array("id_pagina"=>"ID_PAGINA","id_padre"=>$idPadre,"mod"=>"paginas","action"=>"cambiarOrden","id_pagina_padre"=>$idPaginaPadre);
		$arrVarGet4 = Array("id_pagina"=>"ID_PAGINA","id_padre"=>$idPadre,"mod"=>"paginas","action"=>"eliminarPagina","id_pagina_padre"=>$idPaginaPadre);
        $dataGrid->addColLink("Editar","<a href=\"javascript:{function};\" title=\"Editar\" alt=\"Editar\"><i class=\"fa fa-edit\"></i>","editPagina",$arrVarGet1,"functionjs","left");
		//$dataGrid->addColLink("Eliminar","<img src='./images/eliminar.png' title='Eliminar' alt='Eliminar' border='0'/>","admindex.php",$arrVarGet4,"","left"); 		
        $dataGrid->addColLink("Sub acciones","<a href=\"javascript:{function};\" title=\"Ver paginas hijas\" alt=\"Ver paginas hijas\"><i class=\"fa fa-sitemap text-info\"></i>","verPaginasHijas",$arrVarGet2,"functionjs","left");
        $dataGrid->addColLink("Subir nivel","<a href=\"javascript:{function};\" title=\"Subir nivel\" alt=\"Subir nivel\"><i class=\"fa fa-arrow-circle-up text-success\"></i>","subirNivel",$arrVarGet3,"functionjs","left");
        
		//CREAR LA PAGINACION
        $dataGrid->totalRegPag = 500;
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;						  		
  		
  		include("./modules/paginas/templates/admin_listado_paginas.php"); 
  		
  	}

  	/**
  	  * Funci�n para seleccionar opciones de la parte publica
  	  */
  	function parsePublic() {
		
		
 		global $db,$id,$action,$option,$option2,$appObj;
		
		$id = $appObj->id;
		
		switch($appObj->accion){		
			
 			
 		}

  	}
  	

  	/**
  	 * Funci�n para mostrar el Contenidos por p�ginas
  	 */
  	function findSQL($strSQL, $order_by,$order_direction,$page = 1,$num_results = 20) {

		global $db,$dbAux,$id,$action,$option,$option2;

		$db_class = $db;

		if(!$order_by) $order_by = "id_pagina";

		$strSQL = $strSQL ." ORDER BY $order_by $order_direction";

		$rsConsulta = $db_class->PageExecute($strSQL, $num_results, $page, false, 0);

		return $rsConsulta;
  	}

  	/**
  	 * Funci�n para traer el arreglo de paginas padre
  	 */
  	function getPaginasPadre(){


		include("./modules/menu/class_menu.php");
		
		$menu = new menu();
		
		$arrPaginas = array();
		$arrItemsPadre = $menu->getItemsPadre("id_pagina_padre = '0'", false);		
 		$totalItemsPadre = 0;
        if (is_array($arrItemsPadre))
            $totalItemsPadre = count($arrItemsPadre);
		$_SESSION["itemsMenu"] = null;
  		
  		//RECORREMOS LA PRIMERA VEZ LOS ITEMS PADRE PARA GENERAR EL ARBOL GENEALOGICO EN UN ARRREGLO
        if ($totalItemsPadre > 0){

            for ($i=0;$i<$totalItemsPadre;$i++){

                $page = $arrItemsPadre[$i]["alias"];

                //TRAEMOS LOS ITEMS HIJOS 
                $menu->crearArbol("app_paginas","alias","nombre","id_pagina_padre",$page,"","-","");	    

            }

            //RECORREMOS LA SEGUNDA VEZ LOS ITEMS PADRE PARA GUARDARLOS EN UN ARREGLO MAS SIMPLE
            for ($i=0;$i<$totalItemsPadre;$i++){

                $page = $arrItemsPadre[$i]["alias"];
                $nombre = $arrItemsPadre[$i]["nombre"];

                $arrPaginas[$page] = $nombre;

                //GENERAMOS ARREGLO HIJOS
                $strPaginasHijas = "";
                if ($_SESSION["itemsMenu"] != null && is_array($_SESSION["itemsMenu"]))
                    $strPaginasHijas = $menu->generarCadenaHijos($_SESSION["itemsMenu"],$page,$nombre);  			

                //CONERTIVOS EN UN ARREGLO LA CADENA PARA PODERLA RECORRER
                if ($strPaginasHijas != ""){
                    $arrTempPaginasHijas = preg_split("[#+]", $strPaginasHijas);
                    if (is_array($arrTempPaginasHijas)){
                        $totalPaginas = count($arrTempPaginasHijas);
                        for ($j=0;$j<$totalPaginas;$j++){

                            //TOMAMOS LA INFORMACION DE LA PAGINA Y LA GUARDAMOS EN EL ARREGLO DE PAGINAS
                            $arrPaginaHija = preg_split("[:+]",$arrTempPaginasHijas[$j]);
                            $idAlias = $arrPaginaHija[0];
                            if ($idAlias)
                                $arrPaginas[$idAlias] = $arrPaginaHija[1];
                        }
                    }
                }
            }
        }
  		
  		if (count($arrPaginas)>0)
  			asort($arrPaginas);

  		return $arrPaginas;
  	}

	  function metodoValidarModuloLink(){
		$validaModulo = "";
		$validaLink = "";

		if($arrTiposContenido == "Modulo"){
			$validaModulo = "true";
			$validaLink = "none";
		}

		if($arrTiposContenido == "Link Externo"){
			$validaModulo = "none";
			$validaLink = "";
		}
		
	  }
	

}

?>