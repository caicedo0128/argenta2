<?php
/**
* Adminsitración del modulo modelos
* @version 1.0
* El constructor de esta clase es {@link modelos()}
*/
require_once("class_modelos_extended.php");
class modelos{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "modelo":
                            $this->modelo();
                            break;
            case "saveModelo":
                            $this->saveModelo();
                            break;
            case "listModelos":
                            $this->listModelos();
                            break;    
            case "eliminarModelo":
                            $this->eliminarModelo();
                            break;   
            case "grupo":
                            $this->grupo();
                            break;
            case "saveGrupo":
                            $this->saveGrupo();
                            break;
            case "listConfiguracion":
                            $this->listConfiguracion();
                            break;    
            case "eliminarGrupo":
                            $this->eliminarGrupo();
                            break;   
            case "cambiarOrdenGrupo":
                            $this->cambiarOrdenGrupo();
                            break;  
            case "campo":
                            $this->campo();
                            break;
            case "saveCampo":
                            $this->saveCampo();
                            break;
            case "listCamposGrupo":
                            $this->listCamposGrupo();
                            break;    
            case "eliminarCampo":
                            $this->eliminarCampo();
                            break;   
            case "cambiarOrdenCampo":
                            $this->cambiarOrdenCampo();
                            break;  
            case "camposModelo":
                            $this->camposModelo();
                            break;                            
        }
    }
    
    /**
     * Funciòn para obtener campos modelo
     */
    function camposModelo(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/fieldsDynamic/class_fieldsDynamic.php");

        $campos = new fieldsDynamic();
        $modeloCampos = new modelo_campos();
        $camposInstancia = new campos_instancia();
        $grupo = new grupo_campos();
        
        
        $idModelo = $_REQUEST["id_modelo"];
        $idEstudio = $_REQUEST["id_estudio"];
        $imprimir = $_REQUEST["impresion"];
        
        //TRAEMOS LOS CAMPOS DEL MODELO
        $rsCamposDinamicos = $modeloCampos->getCamposPorModelo($idModelo);
        
        $rsGrupos = $grupo->getGrupoPorModelo($idModelo);
        
        //TRAEMOS LOS CAMPOS DE LA INSTANCIA
        $arrCamposInstancia = $camposInstancia->getCamposPorEstudio($idEstudio);   
        
        include("./modules/modelos/templates/generacion_campos_dinamicos.php");
    }    
   
    
    /**
     * Funciòn para cambiar el orden de los campos
     */
    function cambiarOrdenCampo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $campo = new modelo_campos();
        $campo1 = new modelo_campos();
        
        $idModeloCampo = $_REQUEST["id_modelo_campo"];

        $loadReg1 = $campo->load("id_modelo_campo=".$idModeloCampo);
        
        //OBTENEMOS EL REGISTRO 2 POR MODELO PARA CAMBIAR EL ORDEN
        $idGrupo = $campo->id_grupo;
        $ordenActual = $campo->orden;
        
        $strSQL = "SELECT * FROM modelo_campos WHERE id_grupo=".$idGrupo. " AND orden < " . $ordenActual ." ORDER BY orden DESC LIMIT 1";
        $rsData = $db->Execute($strSQL);            
        
        $campo->orden = 1;
        //SOLO ACTUALIZAMOS EL GRUPO AL QUE LO MOVIO SIEMPRE Y CUANDO HAYA REGISTRO
        if (!$rsData->EOF){           
            $loadReg1 = $campo1->load("id_modelo_campo=".$rsData->fields["id_modelo_campo"]);   
            $campo->orden = $campo1->orden;
            $campo1->orden = $ordenActual;        
            $campo1->Save();  
        }
        
        //CAMBIAMOS EL REGISTRO QUE SE ESTA MOVIENDO
        $campo->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }    
    
    /**
     * Funciòn para obtener el listado de campos
     */
    function listCamposGrupo($idGrupo = 0){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
               
        //INSTANCIAMOS CLASES
        $campo = new modelo_campos();
        
        $rsCampos = $campo->getCamposPorGrupo($idGrupo);
        
        include("./modules/modelos/templates/listado_campos.php");
    }
    
    /**
     * Funciòn para obtener el listado de campos
     */
    function listCamposDinamicosGrupo($idGrupo = 0){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
               
        //INSTANCIAMOS CLASES
        $campo = new modelo_campos();
        
        $rsCampos = $campo->getCamposPorGrupo($idGrupo);
        
        return $rsCampos;
    }    

    /**
     * Funciòn para guardar informacion campo
     */
    function saveCampo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $campo = new modelo_campos();
        
        $idModeloCampo = $_REQUEST["id_modelo_campo"];

        $loadReg1 = $campo->load("id_modelo_campo=".$idModeloCampo);

        $campo->id_campo = $_POST['id_campo'];
        $campo->id_modelo = $_POST['id_modelo'];
        $campo->id_grupo = $_POST['id_grupo'];
        $campo->es_obligatorio = $_POST['es_obligatorio'];
        
        if ($idModeloCampo == 0)      
            $campo->orden = $campo->obtenerOrden($campo->id_grupo) + 1;

        $campo->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un campo
     */
    function campo() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/fieldsDynamic/class_fieldsDynamic.php");

        $campos = new campos();
        $campo = new modelo_campos();

        $idModeloCampo = $_REQUEST["id_modelo_campo"];
        $idGrupo = $_REQUEST["id_grupo"];
        
        $loadReg = $campo->load("id_modelo_campo=".$idModeloCampo);
        
        //OBTENER CAMPOS PARA EL MODELO
        $arrCampos = $campos->getCamposFormula();

        include("./modules/modelos/templates/campo.php");

    }
    
    /**
     * Funciòn para eliminar campo
     */
    function eliminarCampo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $campo = new modelo_campos();
                
        $idCampo = $_REQUEST["id_modelo_campo"];

        $loadReg1 = $campo->load("id_modelo_campo=".$idCampo);

        $campo->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
        
    }    
    
    /**
     * Funciòn para cambiar el orden de los grupos
     */
    function cambiarOrdenGrupo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $grupo = new grupo_campos();
        $grupo1 = new grupo_campos();
        
        $idGrupo = $_REQUEST["id_grupo"];

        $loadReg1 = $grupo->load("id_grupo=".$idGrupo);
        
        //OBTENEMOS EL REGISTRO 2 POR MODELO PARA CAMBIAR EL ORDEN
        $idModelo = $grupo->id_modelo;
        $ordenActual = $grupo->orden;
        
        $strSQL = "SELECT * FROM grupo_campos WHERE id_modelo=".$idModelo. " AND orden < " . $ordenActual ." ORDER BY orden DESC LIMIT 1";
        $rsData = $db->Execute($strSQL);            
        
        $grupo->orden = 1;
        //SOLO ACTUALIZAMOS EL GRUPO AL QUE LO MOVIO SIEMPRE Y CUANDO HAYA REGISTRO
        if (!$rsData->EOF){           
            $loadReg1 = $grupo1->load("id_grupo=".$rsData->fields["id_grupo"]);   
            $grupo->orden = $grupo1->orden;
            $grupo1->orden = $ordenActual;        
            $grupo1->Save();  
        }
        
        //CAMBIAMOS EL REGISTRO QUE SE ESTA MOVIENDO
        $grupo->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }    
    
    /**
     * Funciòn para obtener el listado de grupos
     */
    function listConfiguracion(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
        
        require_once("./modules/estudioRiesgo/class_estudioRiesgo_extended.php");
        
        $idModelo = $_REQUEST["id_modelo"];
        
        //INSTANCIAMOS CLASES
        $grupo = new grupo_campos();
        $estudioRiesgo = new estudio_riesgo();
        
        $rsGrupos = $grupo->getGrupoPorModelo($idModelo);
        
		//DETERMINAMOS SI EL MODELO FUE UTILIZADO EN ALGUN TERCERO
        $idUtilizaModelo = $estudioRiesgo->getEstudiosPorModelo($idModelo);
        
        $bloquearModelo=false;
		if ($idUtilizaModelo)
			$bloquearModelo=true;
        
        include("./modules/modelos/templates/listado_configuracion.php");
    }

    /**
     * Funciòn para guardar informacion grupo
     */
    function saveGrupo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $grupo = new grupo_campos();
        
        $idGrupo = $_REQUEST["id_grupo"];

        $loadReg1 = $grupo->load("id_grupo=".$idGrupo);

        $grupo->grupo = $_POST['nombre_grupo'];
        $grupo->id_modelo = $_POST['id_modelo'];
        $grupo->color = $_POST['color'];
        $grupo->ubicacion_impresion = $_POST['ubicacion'];
        $grupo->columna = $_POST['columna'];
        $grupo->activo = $_POST['activo_grupo'];
        
        if ($idGrupo == 0)      
            $grupo->orden = $grupo->obtenerOrden($grupo->id_modelo) + 1;

        $grupo->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un grupo
     */
    function grupo() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");

        $grupo = new grupo_campos();

        $idGrupo = $_REQUEST["id_grupo"];
        
        $loadReg = $grupo->load("id_grupo=".$idGrupo);

        include("./modules/modelos/templates/grupo.php");

    }
    
    /**
     * Funciòn para eliminar grupo
     */
    function eliminarGrupo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $grupo = new grupo_campos();
                
        $idGrupo = $_REQUEST["id_grupo"];

        $loadReg1 = $grupo->load("id_grupo=".$idGrupo);

        $grupo->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
        
    }    

    /**
     * Funciòn para obtener el listado de modelos
     */
    function listModelos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_modelo, nombre, activo FROM modelo_riesgo";
        $strWhere = " WHERE 1=1";
        $strOrder = " ORDER BY id_modelo DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //CAMPOS DE SOLO DOS VALORES
        $arrValues = array("1","2");
        $arrText = array("Si","No");
        $dataGrid->addFieldTwoValues("activo",$arrValues,$arrText);

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("Agregar","javascript:editModelo(0,'modelos','modelo')");
        
        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Código","Modelo","Activo"));


        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_modelo"=>"ID_MODELO","mod"=>"modelos","action"=>"modelo");
        $arrVarGet2 = Array("id_modelo"=>"ID_MODELO","mod"=>"modelos","action"=>"eliminarModelo");
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Modelo' alt='Editar Modelo' border='0'/></a></center>","editModelo",$arrVarGet1,"functionjs","left");
        $dataGrid->addColLink("Eliminar","<center><a href=\"javascript:{function};\"><img src='./images/eliminar.png' title='Eliminar Modelo' alt='Eliminar Modelo' border='0'/></a></center>","deleteModelo",$arrVarGet2,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/modelos/templates/listado_modelos.php");


    }

    /**
     * Funciòn para guardar informacion modelo
     */
    function saveModelo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $modelo = new modelo_riesgo();
        
        $idModelo = $_REQUEST["id_modelo"];

        $loadReg1 = $modelo->load("id_modelo=".$idModelo);

        $modelo->nombre = $_POST['nombre'];
        $modelo->activo = $_POST['activo'];
        $modelo->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Por favor configure su modelo.";
        $jsondata['IdModelo'] = $modelo->id_modelo;        
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un modelo
     */
    function modelo() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");

        $modelo = new modelo_riesgo();

        $idModelo = $_REQUEST["id_modelo"];

        $loadReg = $modelo->load("id_modelo=".$idModelo);

        include("./modules/modelos/templates/modelo.php");

    }
    
    /**
     * Funciòn para eliminar modelo
     */
    function eliminarModelo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $modelo = new modelo_riesgo();
                
        $idModelo = $_REQUEST["id_modelo"];

        $loadReg1 = $modelo->load("id_modelo=".$idModelo);

        $modelo->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
        
    }

    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

        }
    }

}

?>
