<?php
/**
* Adminsitración de perfiles de la perfiles
* @author Andres Bravo
* @version 1.0
* El constructor de esta clase es {@link perfiles()}
*/
require_once("class_perfiles_extended.php");
class perfiles {


	var $Database;
  	var $ID;


  	/**
      * Funciòn para seleccionar opciones de administrador
      */
  	function parseAdmin() {

 		global $db,$id,$action,$option,$option2,$appObj; 

		switch($appObj->action){

 			case "verListado":
							$this->listadoAdmin();
 							break;
 			case "editarPerfil":
							$this->editarPerfil();
 							break;
 			case "guardarPerfil":
							$this->guardarPerfil();
 							break; 
 			case "permisosPerfil":				
                            $this->permisosPerfil();
 							break; 
 			case "guardarPermisos":				
                            $this->guardarPermisos();
 							break;  							
 		}

  	}	

    /**
      * Funciòn para guardar los permisos
      */
    function guardarPermisos() {
    
        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
        
        //INSTANCIAMOS LA CLASE DE LA TABLA
        $perfilPermiso = new perfil_permiso();        
        
        $idPerfil = $_POST['id_perfil'];
        
        $perfilPermiso->borrarPermisosPerfil($idPerfil);
        
        $arrAcciones = $_REQUEST["permiso_accion"];
        foreach($arrAcciones as $key=>$value){
            $perfilPermiso = new perfil_permiso();        
            $perfilPermiso->load("id_perfil_permiso=0");
            $perfilPermiso->id_perfil = $idPerfil;
            $perfilPermiso->alias_accion = $value;
            $perfilPermiso->Save();
        }             
        
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }


  	/**
  	  * Funciòn para guardar la informacion de la perfil
  	  */
  	function guardarPerfil() {
  	
  		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
		
		//INSTANCIAMOS LA CLASE DE LA TABLA
        $perfil = new usuarios_perfil();        
        
        $idPerfil = $_POST['id_perfil'];
		$loadReg = $perfil->load("id_perfil=". $idPerfil);
  				  
  		$perfil->Save();		
  		
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
  	}

  	/**
  	  * Funciòn para mostrar el formulario de editar una perfil en el administrador
  	  */
  	function editarPerfil() {
  	
  		global $db,$id,$action,$option,$option2,$appObj;
						
        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/paginas/class_paginas.php");  
        
						
		//INSTANCIAMOS LA CLASE DE LA TABLA
        $perfilData = new usuarios_perfil();   
        $acciones = new paginas();   
        $perfilPermiso = new perfil_permiso();
		
 		$idPerfil = $_REQUEST["id_perfil"];
 		
        $loadReg = $perfilData->load("id_perfil=".$idPerfil);
        
        //OBTENEMOS TODAS LAS ACCIONES
        $arrAcciones = $acciones->getPaginasPadre();
        
        //OBTENEMOS LOS PERMISOS DEL PERFIL
        $arrPermisos = $perfilPermiso->obtenerPermisosPorPerfil($idPerfil);       
        
  		include("./modules/perfiles/templates/perfil.php"); 
  		
  	}

  	/**
  	  * Funciòn para mostrar el listado de perfiles en el administrador
  	  */
  	function listadoAdmin() {
  		
  		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;;
  	
		include("./utilities/class_dataGrid.php");
	
		
		//INSTANCIAMOS LA CLASE DATA GRID	
		$dataGrid = new DataGrid($this);	

		$dataGrid->idDataGrid = "resultDatos";
		$dataGrid->heightDG = "300";		
			
		//TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
		$strSQL = "SELECT id_perfil,perfil FROM usuarios_perfil";			
	
		//TRAEMOS LA CONSULTA A EJECUTAR
		$dataGrid->SQL = $strSQL;

    	$dataGrid->titleList="<h1>Administrar perfiles de la aplicación</h1>";
			
		$dataGrid->classTitleList="titletable";

		//INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
		$dataGrid->titleProcess = $msjProcesoRealizado;
		
		//CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
		$dataGrid->optionsHeader=true;
        //$dataGrid->addOptionsHeader("Agregar","javascript:editPerfil(0,'perfiles','editarPerfil','".$idPadre."','".$idPerfilPadre."')","btn-primary","fa-plus-square");

		//CREAR OPCIONES DE PIE EN EL DATA GRID
		$dataGrid->optionsFooter=false;
		
		//IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
		$dataGrid->addTitlesHeader(array("Id perfil","Nombre"));
				
		//CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET		
        $arrVarGet1 = Array("id_perfil"=>"ID_PERFIL","mod"=>"perfiles","action"=>"editarPerfil");		
        $dataGrid->addColLink("Editar","<a href=\"javascript:{function};\" title=\"Editar\" alt=\"Editar\"><i class=\"fa fa-edit\"></i>","editPerfil",$arrVarGet1,"functionjs","left");
        
		//CREAR LA PAGINACION
        $dataGrid->totalRegPag = 500;
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;                             
  		
  		include("./modules/perfiles/templates/listado_perfiles.php"); 
  		
  	}
  
}

?>