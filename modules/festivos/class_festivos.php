<?php
/**
* Adminsitración del modulo festivos
* @version 1.0
* El constructor de esta clase es {@link festivos()}
*/
class festivos extends ADOdb_Active_Record{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "festivo":
                            $this->festivo();
                            break;
            case "saveFestivo":
                            $this->saveFestivo();
                            break;
            case "listFestivos":
                            $this->listFestivos();
                            break;    
            case "eliminarFestivo":
                            $this->eliminarFestivo();
                            break;                             
        }
    }
    

    /**
     * Funciòn para obtener el listado de festivos
     */
    function listFestivos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_festivo, fecha FROM festivos";
        $strWhere = " WHERE 1=1";
        $strOrder = " ORDER BY id_festivo DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Festivos</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear Festivo' alt='Crear Festivo' border='0'/>","admindex.php?id_festivo=0&mod=festivos&action=festivo");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Código","Fecha Festivo"));


        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_festivo"=>"ID_FESTIVO","mod"=>"festivos","action"=>"festivo");
        $arrVarGet2 = Array("id_festivo"=>"ID_FESTIVO","mod"=>"festivos","action"=>"eliminarFestivo");
        $dataGrid->addColLink("Editar","<center><img src='./images/editar.png' title='Editar Festivo' alt='Editar Festivo' border='0'/></center>","admindex.php",$arrVarGet1,"","left");
        $dataGrid->addColLink("Eliminar","<center><img src='./images/eliminar.png' title='Eliminar Festivo' alt='Eliminar Festivo' border='0'/></center>","admindex.php",$arrVarGet2,"","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/festivos/templates/listado_festivos.php");


    }

    /**
     * Funciòn para guardar informacion festivo
     */
    function saveFestivo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $idFestivo = $_REQUEST["id_festivo"];

        $loadReg1 = $this->load("id_festivo=".$idFestivo);

        $this->fecha = $_POST['fecha'];
        $this->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un festivo
     */
    function festivo() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");

        $idFestivo = $_REQUEST["id_festivo"];

        $loadReg = $this->load("id_festivo=".$idFestivo);

        include("./modules/festivos/templates/festivo.php");

    }
    
    /**
     * Funciòn para eliminar festivo
     */
    function eliminarFestivo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $idFestivo = $_REQUEST["id_festivo"];

        $loadReg1 = $this->load("id_festivo=".$idFestivo);

        $this->Delete();

		$msjProcesoRealizado = "El registro fue eliminado con exito";
		$this->listFestivos();
        
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
