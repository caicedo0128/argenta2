<?php
/**
* Clase que permite generar un data grid de un objeto enviado por parametros
* @version 1.0
* El constructor de esta clase es {@link DataGrid()}
*/
class DataGrid{

  
    var $Database;
    var $obj;
    var $table;
    
    var $titleList;
    var $classTitleList = "titlecolumns_admin";
    var $titleProcess;  
    var $classTitleProcess = "titleProcess";    
    var $noData = "No hay datos para mostrar.";
    
    var $classTableGrid = "";
    var $idDataGrid = "resultDataGrid";
    var $tableDataId = "tableData";

    var $optionsHeader = true;
    var $arrOptionsHeader = array();
    var $posOptionsHeader = "right";
    var $classOptionsHeader = "titleOptionsHeader_admin";

    var $optionsFooter = true;
    var $arrOptionsFooter = array();
    var $posOptionsFooter = "right";
    var $classOptionsFooter = "titleOptionsHeader_admin";

    var $arrTitlesHeader = array();
    var $arrColumnHide = array();
    var $arrColumnImage = array();
    var $arrColumnImagePath = array();
    var $arrColumnFile = array();
    var $arrColumnFilePath = array();    
    var $totalColumnas = 0;
    var $classTitlesHeader = "titlesheader_admin";
    
    var $heightDG = "100%";
    var $classTdRegisterData = "tdRegisterData";
    var $classTrRegisterData = "trRegisterData";

    var $page = 1;
    var $totalRegPag = 20;
    var $posPaginador = "center";
    var $classPaginadorName = "tdRegisterData";
    var $classPaginadorLink = "tdRegisterData";
    var $paramMensajePaginador = "Cargando...";
    var $paramModuloPaginador = "";
    var $paramModulo1Paginador = "";
    var $paramAccionPaginador = "";
    var $paramIdPaginador = "";
    var $paramOptionPaginador = "";
    var $paramOption2Paginador = "";    
    var $paginadorHeader = true;
    var $paginadorFooter = false;   
    
    var $arrAltBgcolor = array();

    var $arrEventRegister = array();    

    var $arrColLink = array();  
    
    var $arrOperCol = array();
    var $arrColSum = array();
    
    var $arrAddFieldTwoValues = array();

    var $SQL;
    var $ORDER_BY="";
    var $ORDER_DIR = "ASC";
    var $WHERE="";

    var $exportList;
    
    var $paramModulo1FindByFilter = "";
    var $paramAccionFindByFilter = "";
    var $rowsFindByFilter;
    
    /**
      * Constructor de la Clase
      */
    function DataGrid($objParam) {          
        
        global $db,$id,$accion,$option,$option2;
        
        $this->obj = $objParam;         
            
    }   

    /**
      * Funcion que permite generar el data grid del objeto
      */
    function displayDataGrid() {

        //TRAEMOS EL TOTAL DE COLUMNAS PARA QUE EL PAGINADOR ESTE CENTRADO
        $this->totalColumnas = $this->getTotalColumnas();
        
        //DETERMINAMOS SI SE LA GRID TIENE PAGINADOR PARA INCLUIR LA CLASE NECESARIA
        if ($this->paginadorHeader || $this->paginadorFooter){
            
            require_once("class_pager.php"); 
            $rs = $this->getListData();             
            $paginador = new Paginador($rs,$this->paramMensajePaginador,$this->paramModuloPaginador,$this->paramAccionPaginador,$this->paramIdPaginador,$this->paramOptionPaginador,$this->paramOption2Paginador,$this->paramModulo1Paginador);             
            
        }          
                       
        //FILA PARA LAS OPCIONES GENERALES EN EL ENCABEZADO DEL GRID
        if ($this->optionsHeader){
            $strDatos .= "<div class='row-fluid' style='height: 40px;'><div class='agregar_registro text-right'>";
            $strDatos .= $this->getOptionsHeader();
            $strDatos .= "</div>";
            $strDatos .= "</div>";
        }
                      
        //FILA PARA EL TITULO DE LA TABLA CUANDO HAY UN PROCESO
        if ($this->titleProcess){
            $strDatos .= "<div class='".$this->classTitleProcess."' align='center'>";
            $strDatos .= $this->titleProcess;
            $strDatos .= "</div>";
        }

        $strDatos .= "<table id='$this->tableDataId' class='table table-striped table-bordered dt-responsive nowrap' cellspacing='0' style='width:100%;'>";
            
            //FILA PARA EL PAGINADOR DEL GRID
            if ($this->paginadorHeader){                
                
                $strDatos .= "<tr>";
                $strDatos .= "<td align='".$this->posPaginador."' colspan='".$this->totalColumnas."'>";
                $strDatos .= $paginador->getPalabras($this->classPaginadorName,$this->classPaginadorLink);
                $strDatos .= "</td>";
                $strDatos .= "</tr>";       
            }

            //LLAMAMOS EL METODO QUE GENERA EL GRID
            $strDatos .= $this->getDataGrid();

            //FILA PARA EL PAGINADOR DEL GRID
            if ($this->paginadorFooter){
                $strDatos .= "<tr>";
                $strDatos .= "<td align='".$this->posPaginador."' colspan='".$this->totalColumnas."'>";
                $strDatos .= $paginador->getSelect($this->classPaginadorName,$this->classPaginadorLink);
                $strDatos .= "</td>";
                $strDatos .= "</tr>";       
            }       
        
        $strDatos .= "</table>";
        
        echo $strDatos;
    }

    /**
     * Funciòn para obtener las opciones del header para la tabla administrada   
     */
    function getOptionsHeader() {
        
        global $db,$LANG;       
                
        $optionsHeader = "";        
        
        //TRAEMOS LAS OPCIONES QUE LLEGARON EN EL ARREGLO
        if (is_array($this->arrOptionsHeader)){
            
            foreach($this->arrOptionsHeader as $arrOptions){
                foreach($arrOptions as $label => $link){
                    if ($link != "")
                        $optionsHeader .= '<a href="'.$link.'" class="btn btn-primary btn-sm '.$this->classOptionsHeader.'"> <i class="fa fa-plus-square fa-lg"></i> '.$label.'</a>&nbsp;';                                    
                    else
                        $optionsHeader .= $label;                                    
                } 
            }
        }
               

        $strOptionsHeader .= $optionsHeader;
 
   
        return $strOptionsHeader;
    }

    /**
     * Funciòn para crear las opciones adicionales a la tabla
     */
    function addOptionsHeader($label,$link) {
    
        $this->arrOptionsHeader[] = array($label=>$link);
        
            
    }

    /**
     * Funciòn para crear las opciones adicionales a la tabla
     */
    function addOptionsFooter($label,$link) {
    
        $this->arrOptionsFooter[] = array($label=>$link);
        
            
    }

    /**
     * Funciòn para obtener las opciones del footer para la tabla administrada   
     */
    function getOptionsFooter() {
        
        global $db,$LANG;
        
        $strOptionsFooter = "<table id='optionsFooter'>";
                
        $optionsFooter = "";        
        
        //TRAEMOS LAS OPCIONES QUE LLEGARON EN EL ARREGLO
        if (is_array($this->arrOptionsFooter)){             
            foreach($this->arrOptionsFooter as $arrOptions){                
                foreach($arrOptions as $label => $link)
                    $optionsFooter .= "<a href='".$link."' class='".$this->classOptionsFooter."'> ".$label."</a>";
            }
        }
        
        $strOptionsFooter .= "<tr>";    
        $strOptionsFooter .= "<td class='".$this->classOptionsFooter."'>";  
        $strOptionsFooter .= $optionsFooter;
        $strOptionsFooter .= "</td>";   
        $strOptionsFooter .= "</tr>";   
        $strOptionsFooter .= "</table>";    
   
        return $strOptionsFooter;
    }

    /**
     * Funciòn para crear las opciones adicionales a la tabla en la seccion footer
     */
    function arrOptionsFooter($label,$link) {
    
        $this->arrOptionsFooter[] = array($label=>$link);       
            
    }

    /**
     * Funciòn para obtener el resultado de datos para el datagrid
     */
    function getDataGrid() {
        
        global $db,$LANG;
        
        //SE IMPRIME LOS TITULOS DE LA TABLA
        $strDataGrid .= "<thead><tr>";
        
        
        if (is_array($this->arrTitlesHeader) && count($this->arrTitlesHeader)>0){               
            $strDataGrid .= $this->getTitlesHeader();               
        }

        
        $strDataGrid .= "</tr></thead>";
        
        //SE RECORRE LA CONSULTA PARA LA GRID
        if ($this->SQL){
            
            $lista = $this->getListData();          
            

            //DETERMINAMOS SI HAY REGISTROS
            if ($lista->_maxRecordCount>0){
                        
                $strDataGrid .= "<tbody>";      
                        
                //RECORREMOS LA CONSULTA
                while (!$lista->EOF){

                    $arrRegister = $lista->GetRowAssoc();

                    /* PASAR ESTO A METODOS*/
                    //$strBgcolor = $this->getBgColor();
                    //$strEvents = $this->getEvents();
                    if (count($this->arrAltBgcolor)>0){
                        ($indiceArrBgcolor==0)?$indiceArrBgcolor=1:$indiceArrBgcolor=0;
                        $strBgcolor = " bgcolor='".$this->arrAltBgcolor[$indiceArrBgcolor]."'"; 
                    }

                    if (count($this->arrEventRegister)>0){
                        $strEvents = "";
                        foreach($this->arrEventRegister as $arrEvents) {                                                    
                            foreach($arrEvents as $event => $func)                          
                                $strEvents .= $event ."='". $func . "'";                        
                        }

                    }
                    /*HASTA AQUI SE DEBE PASAR A METODOS*/


                    $strDataGrid .= "<tr class='".$this->classTrRegisterData."' ".$strBgcolor." ".$strEvents.">";                                                       

                    //DETERMINAMOS SI HAY COLUMNAS ADICIONALES A LA IZQUIERDA DEL DATA GRID *** PASAR A METODO
                    //$strDataGrid = $this->getColLeft();
                    if (count($this->arrColLink)>0){
                        foreach($this->arrColLink as $arr){             

                            if ($arr[5]=="left"){

                                $strLink = $arr[2];
                                //ARMAMOS LAS VARIABLES PARA PASARLAS POR GET EN EL LINK
                                $strVarGet = "";
                                $strVarFunction = "";
                                foreach($arr[3] as $var=>$value){

                                    $valor = $value;                                
                                    if ($arrRegister[$value])
                                        $valor = $arrRegister[$value];

                                    $strVarGet .= $var . "=" . $valor."&";
                                    $strVarFunction .= "'".$valor."',";
                                }

                                $strLink .= "?".$strVarGet;

                                $strDataGrid .= "<td class='".$this->classTdRegisterData."' align='center' style='width:100px;'>";
                                
                                if ($arr[4]=="popup")
                                    $strDataGrid .= "<a href='javascript:;' class='".$this->classTdRegisterData."' onclick=\"openWindow('".$strLink."',900,600);\">".$arr[1]."</a>";                                          
                                else if ($arr[4]=="functionjs"){                                    
                                    //Ejemplo de uso:
                                    //$arrVarGet1 = Array("id_cliente"=>"ID_CLIENTE");
                                    //$dataGrid->addColLink("Seleccionar","<center><input type='radio' name='id_cliente' id='id_cliente' onclick=\"{function}\" /></center>","selectClient",$arrVarGet1,"functionjs","left");
                                    //ó $dataGrid->addColLink("Ver Departamentos","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Departamento' alt='Editar Departamento' border='0'/></a></center>","cargarDepartamentos",$arrVarGet1,"functionjs","right");        
                                    $strVarFunction = substr($strVarFunction, 0, -1);                                                                       
                                    $objeto = $arr[1];                                  
                                    $strLink = $arr[2] . "(" . $strVarFunction . ")";                                   
                                    $objeto = str_replace("{function}", $strLink, $objeto);
                                    $strDataGrid .= $objeto;
                                }                                   
                                else
                                    $strDataGrid .= "<a href='".$strLink."' class='".$this->classTdRegisterData."' target='".$arr[4]."'>".$arr[1]."</a>";                                           
                                
                                $strDataGrid .= "</td>";    
                            }               
                        }
                    }


                    foreach($lista->GetRowAssoc() as $name=>$fld) {                         

                        $nombreCampo = strtolower($name);

                        //DETERMINAMOS SI EL CAMPOR ESTA EN arrColumnHide PARA NO MOSTRAR SU CONTENIDO
                        if (!in_Array($nombreCampo,$this->arrColumnHide)){

                            $strDataGrid .= "<td  class='".$this->classTdRegisterData."' align='left'>";            
                            $strDataGrid .= $this->validateValueField($fld,$nombreCampo)."&nbsp;";          
                            $strDataGrid .= "</td>";

                            //DETERMINAMOS SI SE HAY COLUMNAS PARA OPERAR **** PASAR A METODO                       
                            if (count($this->arrOperCol)>0){                            
                                foreach($this->arrOperCol as $arr){                             
                                    if (in_Array($nombreCampo,$arr)){
                                        $nombreCampoTotal = $nombreCampo."_total";
                                        $$nombreCampoTotal += $fld;
                                        $arrColSum[$nombreCampo."_total"] += $fld;
                                        echo $arrColSum[$nombreCampo."_total"];
                                        $nombreCampoSubtotal = $nombreCampo."_subtotal";                                    
                                        $$nombreCampoSubtotal += $fld;

                                    }
                                }

                            }

                        }
                    }   

                    //DETERMINAMOS SI HAY COLUMNAS ADICIONALES A LA DERECHA DEL DATA GRID *** PASAR A METODO
                    //$strDataGrid = $this->getColRight();
                    if (count($this->arrColLink)>0){
                        foreach($this->arrColLink as $arr){             

                            if ($arr[5]=="right"){

                                $strLink = $arr[2];
                                //ARMAMOS LAS VARIABLES PARA PASARLAS POR GET EN EL LINK
                                $strVarGet = "";
                                $strVarFunction = "";
                                foreach($arr[3] as $var=>$value){

                                    $valor = $value;
                                    if ($arrRegister[$value])
                                        $valor = $arrRegister[$value];

                                    $strVarGet .= $var . "=" . $valor."&";
                                    $strVarFunction .= "'".$valor."',";
                                }

                                $strLink .= "?".$strVarGet;

                                $strDataGrid .= "<td class='".$this->classTdRegisterData."' align='center' style='width:100px;'>";                              
                                if ($arr[4]=="popup")
                                    $strDataGrid .= "<a href='javascript:;' class='".$this->classTdRegisterData."' onclick=\"openWindow('".$strLink."',900,600);\">".$arr[1]."</a>";                                          
                                else if ($arr[4]=="functionjs"){                                    
                                    $strVarFunction = substr($strVarFunction, 0, -1);                                   
                                    $objeto = $arr[1];                                  
                                    $strLink = $arr[2] . "(" . $strVarFunction . ")";                                   
                                    $objeto = str_replace("{function}", $strLink, $objeto);
                                    $strDataGrid .= $objeto;
                                }                                   
                                else
                                    $strDataGrid .= "<a href='".$strLink."' class='".$this->classTdRegisterData."' target='".$arr[4]."'>".$arr[1]."</a>";                                           
                                
                                $strDataGrid .= "</td>";    
                            }               
                        }
                    }

                    $strDataGrid .= "</tr>";                    

                    $lista->MoveNext();
                }
                
                $strDataGrid .= "</tbody>";                 

                if (count($this->arrOperCol)>0){                            
                    echo $genero_subtotal."<hr>";
                    echo $genero_total;
                }
            }
            else{
                //LO HACE DATA TABLE
                //$strDataGrid .= "<tr>";
                //$strDataGrid .= "<td align='center' colspan='".$this->totalColumnas."' class='".$this->classPaginadorName."'>".$this->noData."</td>";
                //$strDataGrid .= "</tr>";
            }
            
        }
        
        return $strDataGrid;
        
    }


    /**
     * Funciòn para obtener el listado de titulos para el grid
     */
    function getTitlesHeader() {

        $strTitlesHeader = "";
        
        //DETERMINAMOS SI HAY COLUMNAS ADICIONALES A LA IZQUIERDA DEL DATA GRID
        if (count($this->arrColLink)>0){
            foreach($this->arrColLink as $arr){             
                if ($arr[5]=="left"){
                    $strTitlesHeader .= "<th class='".$this->classTitlesHeader."' align='center'>";
                    $strTitlesHeader .= $arr[0];                                        
                    $strTitlesHeader .= "</th>";    
                }               
            }
        }
        
        foreach($this->arrTitlesHeader as $name=>$value){
            
            //DETERMINAMOS SI EL LABEL ESTA EN arrColumnHide PARA NO MOSTRARLO
            if (!in_Array($value,$this->arrColumnHide)){
                $strTitlesHeader .= "<th class='".$this->classTitlesHeader."' align='center'>";
                $strTitlesHeader .= $value;                                         
                $strTitlesHeader .= "</th>";
            }
        }
            
        //DETERMINAMOS SI HAY COLUMNAS ADICIONALES A LA DERECHA DEL DATA GRID   
        if (count($this->arrColLink)>0){
            foreach($this->arrColLink as $arr){             
                if ($arr[5]=="right"){
                    $strTitlesHeader .= "<th class='".$this->classTitlesHeader."' align='center'>";
                    $strTitlesHeader .= $arr[0];                                        
                    $strTitlesHeader .= "</th>";    
                }               
            }
        }       
        
        return $strTitlesHeader;
    }

    /**
     * Funciòn para crear columnas de titulo del data grid
     */
    function addTitlesHeader($arrTitles = array()) {
    
        if (is_array($arrTitles))
            $this->arrTitlesHeader = $arrTitles;
    }

    /**
     * Funciòn para tomar las columas del data grid que no se van a mostrar
     */
    function addColumnHide($arrHide = array()) {
    
        if (is_array($arrHide))
            $this->arrColumnHide = $arrHide;
    }
    
    /**
     * Funciòn para tomar las columas del data grid que son imagenes
     */
    function addColumnImage($arrImage = array()) {
    
        if (is_array($arrImage))
            $this->arrColumnImage = $arrImage;
    }   
    
    /**
     * Funciòn para tomar las columas del data grid que son imagenes y guardar su path
     */
    function addColumnImagePath($arrImagePath = array()) {
    
        if (is_array($arrImagePath))
            $this->arrColumnImagePath = $arrImagePath;
    }   
    
    /**
     * Funciòn para tomar las columas del data grid que son archivos
     */
    function addColumnFile($arrFile = array()) {
    
        if (is_array($arrFile))
            $this->arrColumnFile = $arrFile;
    }   
    
    /**
     * Funciòn para tomar las columas del data grid que son archivos y guardar su path
     */
    function addColumnFilePath($arrFilePath = array()) {
    
        if (is_array($arrFilePath))
            $this->arrColumnFilePath = $arrFilePath;
    }     

    /**
     * Funciòn para obtener el listado de titulos para el grid
     */
    function getListData() {

        global $db;
        
        if (!$this->page)
            $this->page = 1;
        
        $lista = $db->PageExecute($this->SQL.$this->WHERE.$this->ORDER_BY,$this->totalRegPag,$this->page, false, 0);
        
        return $lista;
    
    }

    /**
     * Funciòn para crear eventos al data grid
     */
    function addEventRegister($event="",$function=""){
    
        $this->arrEventRegister[] = array($event=>$function);           
                
    }


    /**
     * Funciòn para crear columnas adicionales a la tabla de resultados del resultSet
     */
    function addColLink($title="",$label="",$link="",$arrVarsGet = array(),$target="_self",$pos="left"){
        
        if (!$target)
            $target = "_self";
            
        $this->arrColLink[] = array($title,$label,$link,$arrVarsGet,$target,$pos);          
                
    }

    /**
     * Funciòn para crear operaciones a una columna ya se por paginacion, al final de la lista o las 2
     */
    function addOperCol($campo="",$operacion="SUM",$vista="ST",$estilo=""){
            
        $this->arrOperCol[] = array($campo,$operacion,$vista,$estilo);          
                
    }

    /**
     * Funciòn para validar si el tipo de campo es de dos valores especificos
     */
    function addFieldTwoValues($field="",$arrValues = array(),$arrText = array()){
        
        
        $this->arrAddFieldTwoValues[] = array($field,$arrValues,$arrText);          
                
    }

    /**
     * Funciòn para validar el contenido de un campo
     */
    function validateValueField($fld,$nombreCampo){
    
        $valueField = $fld;
        if (count($this->arrAddFieldTwoValues)>0){                          
            foreach($this->arrAddFieldTwoValues as $arr){                                               
                if (in_Array($nombreCampo,$arr)){                       
                
                    $arrValues = $arr[1];
                    $arrText = $arr[2];
                    
                    for ($j=0;$j<count($arrValues);$j++){                       
                        if ($fld==$arrValues[$j]){                          
                            $valueField = $arrText[$j];
                        }
                    }                   
                }
            }
        }
        
        //DETERMINAMOS SI EL CAMPOR ESTA EN arrColumnImge PARA CAMBIAR EL CONTENIDO POR UNA IMAGEN
        if (in_Array($nombreCampo,$this->arrColumnImage)){
            $arrKey = array_keys($this->arrColumnImage, $nombreCampo);      
            $path =  $this->arrColumnImagePath[$arrKey[0]];         
            $valueField = "<center><img src='" . $path . "" . $valueField . "' width='100' height='100' title='" . $nombreCampo ."'/></center>";
        }

        //DETERMINAMOS SI EL CAMPOR ESTA EN arrColumnFile PARA CAMBIAR EL CONTENIDO POR UN LINK
        if (in_Array($nombreCampo,$this->arrColumnFile)){
            $arrKey = array_keys($this->arrColumnFile, $nombreCampo);      
            $path =  $this->arrColumnFilePath[$arrKey[0]];         
            $valueField = "<center><a href='" . $path . "" . $valueField . "' title=' Descargar " . $nombreCampo ."' target='_blank'> <img src='./images/download.png' border='0' />Descargar </a></center>";
        }

        return $valueField;
    }

    /**
     * Funciòn para traer el total de columnas del datagrid
     */
    function getTotalColumnas(){
        
        $rs = $this->getListData();
        $totalColumnas = $rs->_numOfFields + count($this->arrColLink);                          
        return $totalColumnas;
    }

}

?>
