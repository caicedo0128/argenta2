<?php
/**
* Adminsitraci�n del modulo tipoDocumentos
* @version 1.0
* El constructor de esta clase es {@link tipoDocumentos()}
*/
require_once("class_tipoDocumentos_extended.php");
class tipoDocumentos{
    var $Database;
    var $ID;

    /**
      * Funci�n para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "Documentos":
                            $this->Documentos();
                            break;    
            case "saveDocumentos":
                            $this->saveDocumentos();
                            break;
            case "listTipoDocumentos":
                            $this->listTipoDocumentos();
                            break;
            case "eliminarDocumentos":
                            $this->eliminarDocumentos();
                            break;               
        }
    }
   
    /**
     * Funci�n para obtener el listado de tipo documentos
     */
    function listTipoDocumentos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_tipo_documento, tipo_documento, emisor, pagador, comercial FROM tipo_documento";    
        $strOrder = " ORDER BY id_tipo_documento DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;        

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("Agregar","javascript:editTipoDocumento(0,'tipoDocumentos','Documentos')");
        
        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;
        
        //CAMPOS DE SOLO DOS VALORES
        $arrValues = array(1,2);
        $arrText = array("SI","NO");
        $dataGrid->addFieldTwoValues("emisor",$arrValues,$arrText);         
        $dataGrid->addFieldTwoValues("pagador",$arrValues,$arrText);         
        $dataGrid->addFieldTwoValues("comercial",$arrValues,$arrText);         

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("C�digo","Tipo documentos","Emisor","Pagador","Comercial"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_tipo_documento"=>"ID_TIPO_DOCUMENTO","mod"=>"tipoDocumentos","action"=>"Documentos");
        $arrVarGet2 = Array("id_tipo_documento"=>"ID_TIPO_DOCUMENTO","mod"=>"tipoDocumentos","action"=>"eliminarDocumentos");    

        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Tipo Documentos' alt='Editar Tipo Documentos' border='0'/></a></center>","editTipoDocumento",$arrVarGet1,"functionjs","left");
        $dataGrid->addColLink("Eliminar","<center><a href=\"javascript:{function};\"><img src='./images/eliminar.png' title='Eliminar Tipo Documentos' alt='Eliminar Tipo Documentos' border='0'/></a></center>","deleteDocumento",$arrVarGet2,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/tipoDocumentos/templates/listado_tipo_documentos.php");
    }

    /**
     * Funci�n para ver el formulario de registrar un tipo documentos
     */
    function Documentos() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/radio.php");

        $tipo_documento = new tipo_documento();

        //$idDocumento = 0;
        $idDocumento = $_REQUEST["id_tipo_documento"];

        $loadReg = $tipo_documento->load("id_tipo_documento=".$idDocumento);

        include("./modules/tipoDocumentos/templates/tipo_documentos.php");
    }

    /**
     * Funci�n para guardar informacion tipo_documento
     */
    function saveDocumentos() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $tipo_documento = new tipo_documento();
        
        $idDocumento = $_REQUEST["id_tipo_documento"];

        $loadReg1 = $tipo_documento->load("id_tipo_documento=".$idDocumento);

        $tipo_documento->tipo_documento = utf8_decode($_POST['tipo_documento']);
        $tipo_documento->emisor = $_POST['emisor'];
        $tipo_documento->pagador = $_POST['pagador'];
        $tipo_documento->comercial = $_POST['comercial'];
        $tipo_documento->Save();

        $jsondata['Message'] = "El proceso se realizo con exito.";
        $jsondata['IdDocumento'] = $tipo_documento->id_tipo_documento;     
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funci�n para eliminar tipo_documento
     */
    function eliminarDocumentos() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $tipo_documento = new tipo_documento();
                
        $idDocumento = $_REQUEST["id_tipo_documento"];

        $loadReg1 = $tipo_documento->load("id_tipo_documento=".$idDocumento);

        $tipo_documento->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
        
    }

}

?>
