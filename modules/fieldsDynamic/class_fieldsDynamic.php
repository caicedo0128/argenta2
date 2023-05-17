<?php
/**
* Adminsitración del modulo fieldsDynamic
* @version 1.0
* El constructor de esta clase es {@link fieldsDynamic()}
*/
require_once("class_fieldsDynamic_extended.php");
class fieldsDynamic{


    var $Database;
    var $ID;
    var $arrTipos = array("1"=>"Selección Unica","2"=>"Abierto","3"=>"Selección Multiple","4"=>"Calculado");
    var $arrNivelEjecucion = array("2"=>"Segundo");

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;       

        switch($appObj->action){

            case "formFields":
                            $this->formFields();
                            break;
            case "saveField":
                            $this->saveField();
                            break;                          
            case "listFields":
                            $this->listFields();
                            break;
            case "deleteField":
                            $this->deleteField();
                            break; 
            case "testCalc":
                            $this->testCalc();
                            break;    
        }
    }
    
    /**
      * Funciòn para revisar los calculos
      */
    function testCalc() {

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./modules/modelos/class_modelos_extended.php");

        $campoClass = new campos(); 
        $campoInstancia = new campos_instancia(); 
        
        $idEstudio = 2;

        //OBTENEMOS TODOS LOS CAMPOS CALCULADOS    
        $rsCamposCalculados = $campoClass->getCamposCalculados();
        
        //OBTENEMOS TODOS LOS CAMPOS DEL ESTUDIO    
        $arrCamposEstudio = $campoInstancia->getCamposPorEstudioCalculos($idEstudio); 
        
        /*
        eval('$calculo = (($arrCamposEstudio["ACTIVO CORRIENTE"] <= 1000)?11111:2222);');
        echo $calculo;              
        echo "prueba";               
        */
        
        $calculo = 0;
        while (!$rsCamposCalculados->EOF)
        {     
            $calculo = 0;
            
            try{
                echo "<hr>";   
                echo "Campo: " . $rsCamposCalculados->fields["campo"]."<br>"; 
                echo "formula: " . $rsCamposCalculados->fields["formula"]."<br>"; 

                $formula = $rsCamposCalculados->fields["formula"];

                $formula = preg_replace('/\[/', '$arrCamposEstudio[', $formula);

                echo "formula 2: " . $formula."<br>";
                
                eval('$calculo = (' . $formula . ');');
            }
            catch(Exception $e){
                $calculo = "Error";
            }
            
            $arrCamposEstudio[$rsCamposCalculados->fields["campo"]] = $calculo;
            
            print_r($arrCamposEstudio);
            
            $rsCamposCalculados->MoveNext();
        }
        
    }        
    
    /**
      * Funciòn para eliminar una campo
      */
    function deleteField() {

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $campoClass = new campos();
        

        $idCampo = $_REQUEST["id_campo"];

        //DETERMINAMOS SI TIENE REFERENCIA
        $tieneReferencia = $campoClass->verificarIntegridadReferencial($idCampo);

        if (!$tieneReferencia){

            $loadReg = $campoClass->load("id_campo=".$idCampo);       
            $campoClass->Delete();

            $msjProcesoRealizado = "El registro se elimino con exito.";
        }
        else{
            $msjProcesoRealizado = "El registro no se puede elminar por que tiene referencia en otras tablas.";
        }

        $this->listFields();
    }     

    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;       

        switch($appObj->action){
            
        }
    }

    /**
     * Funciòn para obtener el listado de campos dinamicos
     */
    function listFields(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT c.id_campo, c.campo, c.tipo_campo, c.valores, c.formula, c.campo_oculto, c.activo FROM campos as c";
        $strWhere = " WHERE 1=1";
        $strOrder = " ORDER BY c.id_campo DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("Agregar","javascript:editField(0,'fieldsDynamic','formFields')");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Campo","Tipo Campo","Valores","Formula","Visible","Activo"));

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_campo"));

        //CAMPOS DE SOLO DOS VALORES
        $arrValues = array("1","2");
        $arrText = array("Si","No");
        $dataGrid->addFieldTwoValues("campo_oculto",$arrValues,$arrText);  
        $dataGrid->addFieldTwoValues("activo",$arrValues,$arrText);  
        
        $arrValues = array("1","2","3","4");
        $arrText = array($this->arrTipos[1],$this->arrTipos[2],$this->arrTipos[3],$this->arrTipos[4]);
        $dataGrid->addFieldTwoValues("tipo_campo",$arrValues,$arrText);          
               

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_campo"=>"ID_CAMPO","mod"=>"fieldsDynamic","action"=>"formFields");
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar campo' alt='Editar campo' border='0'/></a></center>","editField",$arrVarGet1,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/fieldsDynamic/templates/listado_campos.php");


    }

    /**
     * Funciòn para registrar un campo
     */
    function saveField() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;
        
        $campoClass = new campos();
        
        $loadReg = $campoClass->load("id_campo=".$_POST['id_campo']);        
                
        $campoClass->campo = utf8_decode($_POST['campo']);
        $campoClass->texto_imprimir = utf8_decode($_POST['texto_imprimir']);
        $campoClass->formato_imprimir = utf8_decode($_POST['formato_imprimir']);
        $campoClass->tipo_campo = $_POST['tipo_campo'];
        $campoClass->texto_ayuda = utf8_decode($_POST['texto_ayuda']);
        
        $campoClass->valores = "";
        $campoClass->formula = "";
        if ($campoClass->tipo_campo == 4)
            $campoClass->formula = strtoupper($_POST['formula']);
        else if ($campoClass->tipo_campo == 3 || $campoClass->tipo_campo == 1)
            $campoClass->valores = strtoupper($_POST['valores']);  
        else if ($campoClass->tipo_campo == 2)
            $campoClass->tipo_abierto = $_POST['tipo_abierto'];
        
        $campoClass->cantidad_decimales = $_POST['cantidad_decimales'];
        $campoClass->campo_oculto = $_POST['visible'];
        $campoClass->nivel_ejecucion = $_POST['nivel_ejecucion'];
        $campoClass->activo = $_POST['activo'];
        $campoClass->Save();
               
        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;
        
        echo json_encode($jsondata);
        exit;
    }


    /**
     * Funciòn para ver el formulario de registro de un campo adicional
     */
    function formFields() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/email.php");
        require_once("./utilities/controles/radio.php");

        $campoClass = new campos();

        $idCampo = $_REQUEST["id_campo"];
               
        $loadReg = $campoClass->load("id_campo=".$idCampo);  
        
        //TRAEMOS LOS CAMPOS
        $arrCampos = $campoClass->getCamposFormula();           
               
        include("./modules/fieldsDynamic/templates/form.php");

    }


    /**
     * Funciòn para validar que tipo de campo se debe generar
     */
    function genFieldByType($typeField = 0, $idField = 0, $value = "", $required = 0) {

        global $db,$id,$appObj,$LANG;
                             
        if ($typeField == 2)
             $this->genFieldText($idField,$value,$required);
        else if ($typeField == 1)
            $this->genFieldSelect($idField,$value,$required);     
        else if ($typeField == 3)
            $this->genFieldSelectMultiple($idField,$value,$required);
        else if ($typeField == 4)
            $this->genFieldCalc($idField,$value);          
            
    } 
    
    /**
     * Funciòn para ver el formulario
     */
    function genFieldText($idField = 0, $value = "", $required = 0) {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");

        $campoClass = new campos();

        $loadReg = $campoClass->load("id_campo=".$idField);   
               
        $name = $campoClass->id_campo;
        $id = $campoClass->id_campo;        
               
        include("./modules/fieldsDynamic/templates/campo_abierto.php");

    }  
    
    /**
     * Funciòn para ver el formulario
     */
    function genFieldSelect($idField = 0, $value = "", $required = 0) {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/select.php");

        $campoClass = new campos();

        $loadReg = $campoClass->load("id_campo=".$idField);  
                
        $name = $campoClass->id_campo;
        $id = $campoClass->id_campo;
        
        $arrValues = array();
        $arrValuesTemp = preg_split("/;/", $campoClass->valores);
        
        foreach ($arrValuesTemp as $key=>$valueArr){
            $arrValues[$valueArr] = $valueArr;
        }        
        
        include("./modules/fieldsDynamic/templates/campo_seleccion_unica.php");

    }      
    
    /**
     * Funciòn para ver el formulario
     */
    function genFieldSelectMultiple($idField = 0, $value = "", $required = 0) {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/select.php");

        $campoClass = new campos();

        $loadReg = $campoClass->load("id_campo=".$idField); 
            
        $name = $campoClass->id_campo;
        $id = $campoClass->id_campo;
        
        $arrValues = array();
        $arrValuesTemp = preg_split("/;/", $campoClass->valores);
        
        foreach ($arrValuesTemp as $key=>$valueArr){
            $valueArr = trim($valueArr);            
            if (strlen($valueArr) > 0)
                $arrValues[$valueArr] = $valueArr;
        }        
        
        $arrDefault = preg_split("/;/",$value);
               
        include("./modules/fieldsDynamic/templates/campo_seleccion_multiple.php");

    }    
    
    /**
     * Funciòn para ver el campo calculado
     */
    function genFieldCalc($idField = 0, $value = "") {

        global $db,$id,$appObj,$LANG;
        
        $campoClass = new campos();

        $loadReg = $campoClass->load("id_campo=".$idField);          
               
        include("./modules/fieldsDynamic/templates/campo_calculado.php");

    }    
}

?>
