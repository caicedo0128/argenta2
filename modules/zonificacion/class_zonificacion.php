<?php
/**
* Adminsitración del modulo zonificacion
* @version 1.0
* El constructor de esta clase es {@link zonificacion()}
*/
require_once("class_zonificacion_extended.php");
class zonificacion extends ADOdb_Active_Record{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "pais":
                            $this->pais();
                            break;
            case "savePais":
                            $this->savePais();
                            break;
            case "listPaises":
                            $this->listPaises();
                            break;
            case "departamento":
                            $this->departamento();
                            break;
            case "saveDepartamento":
                            $this->saveDepartamento();
                            break;
            case "listDepartamentos":
                            $this->listDepartamentos();
                            break;
            case "ciudad":
                            $this->ciudad();
                            break;
            case "saveCiudad":
                            $this->saveCiudad();
                            break;
            case "listCiudades":
                            $this->listCiudades();
                            break;
            case "zona":
                            $this->zona();
                            break;
            case "saveZona":
                            $this->saveZona();
                            break;
            case "listZonas":
                            $this->listZonas();
                            break;
            case "getZonas":
                            $this->getZonas();
                            break;
            case "getDptosJson":
                            $this->getDptosJson();
                            break;    
            case "getCiudadesJson":
                            $this->getCiudadesJson();
                            break;                              
        }
    }

    /**
     * Funciòn para traer las ciudades por departamento
     */
    function getCiudadesJson() {

        global $db,$id,$appObj,$LANG;

        $idDepartamento = $_REQUEST["id"];

        $ciudad = new ciudades();

        $jsondata = $ciudad->ciudadesPorDepartamentoJson($idDepartamento);

        echo $jsondata;
        exit;

    }

    /**
     * Funciòn para traer las departamentos por pais
     */
    function getDptosJson() {

        global $db,$id,$appObj,$LANG;

        $idPais = $_REQUEST["id"];

        $departamento = new departamentos();

        $jsondata = $departamento->departamentosPorPaisJson($idPais);

        echo $jsondata;
        exit;

    }

    /**
     * Funciòn para traer las zonas por ciudad
     */
    function getZonas() {

        global $db,$id,$appObj,$LANG;

        $idCiudad = $_REQUEST["id_ciudad"];

        $zona = new zona_sectores();

        $arrZonas = $zona->zonasPorCiudad($idCiudad);

        $jsondata = json_encode($arrZonas);

        echo $jsondata;
        exit;

    }

    /**
     * Funciòn para obtener el listado de zonas
     */
    function listZonas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";
        $dataGrid->tableDataId = "tableDataZonas";

        $idCiudad = $_REQUEST["id_ciudad"];
        $pais = new paises();
        $departamento = new departamentos();
        $ciudad = new ciudades();
        $loadReg = $ciudad->load("id_ciudad=".$idCiudad);
        $loadReg = $departamento->load("id_departamento=".$ciudad->id_departamento);
        $loadReg = $pais->load("id_pais=".$departamento->id_pais);

        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_zona_sector, zona_sector FROM zona_sectores";
        $strWhere = " WHERE id_ciudad = " . $idCiudad;
        $strOrder = " ORDER BY zona_sector DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Zonas para ".$ciudad->ciudad."-".$departamento->departamento."-".$pais->pais."</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear Zona' alt='Crear Zona' border='0'/>","javascript:editarZona(0,'zonificacion','zona', ".$idCiudad.")");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Id. Zona","Zona Sector"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_zona_sector"=>"ID_ZONA_SECTOR","mod"=>"zonificacion","action"=>"zona","id_ciudad"=>$idCiudad);
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Zona' alt='Editar Zona' border='0'/></a></center>","editarZona",$arrVarGet1,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/zonificacion/templates/listado_zonas.php");


    }

    /**
     * Funciòn para guardar informacion de zona
     */
    function saveZona() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $zona = new zona_sectores();

        $idZona = $_REQUEST["id_zona_sector"];

        $loadReg1 = $zona->load("id_zona_sector=".$idZona);

        $zona->id_ciudad = $_POST['id_ciudad'];
        $zona->zona_sector = strtoupper(strtolower($_POST['zona_sector']));
        $zona->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['IdCiudad'] = $zona->id_ciudad;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar una zona
     */
    function zona() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");

        $zona = new zona_sectores();
        $ciudad = new ciudades();
        $departamento = new departamentos();
        $pais = new paises();

        $idZona = $_REQUEST["id_zona_sector"];
        $idCiudad = $_REQUEST["id_ciudad"];

        $loadReg = $zona->load("id_zona_sector=".$idZona);
        $loadReg = $ciudad->load("id_ciudad=".$idCiudad);
        $loadReg = $departamento->load("id_departamento=".$ciudad->id_departamento);
        $loadReg = $pais->load("id_pais=".$departamento->id_pais);


        include("./modules/zonificacion/templates/zona.php");

    }

    /**
     * Funciòn para obtener el listado de ciudades
     */
    function listCiudades(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";
        $dataGrid->tableDataId = "tableDataCiudades";

        $idDepartamento = $_REQUEST["id_departamento"];
        $pais = new paises();
        $departamento = new departamentos();
        $loadReg = $departamento->load("id_departamento=".$idDepartamento);
        $loadReg = $pais->load("id_pais=".$departamento->id_pais);


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_ciudad, ciudad, zona FROM ciudades INNER JOIN zonas ON ciudades.id_zona = zonas.id_zona";
        $strWhere = " WHERE id_departamento = " . $idDepartamento;
        $strOrder = " ORDER BY id_ciudad DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Ciudades para ".$departamento->departamento."-".$pais->pais."</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear Ciudad' alt='Crear Ciudad' border='0'/>","javascript:editarCiudad(0,'zonificacion','ciudad', ".$idDepartamento.")");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Id. Ciudad","Ciudad","Zona"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_ciudad"=>"ID_CIUDAD","mod"=>"zonificacion","action"=>"ciudad","id_departamento"=>$idDepartamento);
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Ciudad' alt='Editar Ciudad' border='0'/></a></center>","editarCiudad",$arrVarGet1,"functionjs","left");
        //$dataGrid->addColLink("Ver Zonas","<center><a href=\"javascript:{function};\"><img src='./images/genealogy.png' title='Ver Zonas' alt='Ver Zonas' border='0'/></a></center>","cargarZonas",$arrVarGet1,"functionjs","right");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/zonificacion/templates/listado_ciudades.php");


    }

    /**
     * Funciòn para guardar informacion de ciudad
     */
    function saveCiudad() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $ciudad = new ciudades();

        $idCiudad = $_REQUEST["id_ciudad"];

        $loadReg1 = $ciudad->load("id_ciudad=".$idCiudad);

        $ciudad->id_departamento = $_POST['id_departamento'];
        $ciudad->ciudad = strtoupper(strtolower(utf8_decode($_POST['ciudad'])));
        $ciudad->id_zona = $_POST['id_zona'];
        $ciudad->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['IdDepartamento'] = $ciudad->id_departamento;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar una ciudad
     */
    function ciudad() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");

        $ciudad = new ciudades();
        $departamento = new departamentos();
        $pais = new paises();
        $zona = new zonas();

        $idCiudad = $_REQUEST["id_ciudad"];
        $idDepartamento = $_REQUEST["id_departamento"];

        $loadReg = $departamento->load("id_departamento=".$idDepartamento);
        $loadReg1 = $pais->load("id_pais=".$departamento->id_pais);
        $loadReg2 = $ciudad->load("id_ciudad=".$idCiudad);

        //TRAEMOS LAS ZONAS
        $arrZonas = $zona->getZonas();

        include("./modules/zonificacion/templates/ciudad.php");

    }

    /**
     * Funciòn para obtener el listado de departamentos
     */
    function listDepartamentos(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";
        $dataGrid->tableDataId = "tableDataDepartamentos";

        $idPais = $_REQUEST["id_pais"];
        $pais = new paises();
        $loadReg = $pais->load("id_pais=".$idPais);


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_departamento, departamento FROM departamentos";
        $strWhere = " WHERE id_pais = " . $idPais;
        $strOrder = " ORDER BY id_departamento DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Departamentos para ".$pais->pais."</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear Departamento' alt='Crear Departamento' border='0'/>","javascript:editarDepartamento(0,'zonificacion','departamento', ".$idPais.")");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Id. Departamento","Departamento"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_departamento"=>"ID_DEPARTAMENTO","mod"=>"zonificacion","action"=>"departamento","id_pais"=>$idPais);
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Departamento' alt='Editar Departamento' border='0'/></a></center>","editarDepartamento",$arrVarGet1,"functionjs","left");
        $dataGrid->addColLink("Ver Ciudades","<center><a href=\"javascript:{function};\"><img src='./images/genealogy.png' title='Ver Ciudades' alt='Ver Ciudades' border='0'/></a></center>","cargarCiudades",$arrVarGet1,"functionjs","right");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/zonificacion/templates/listado_departamentos.php");


    }

    /**
     * Funciòn para guardar informacion de departamento
     */
    function saveDepartamento() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $departamento = new departamentos();

        $idDepartamento = $_REQUEST["id_departamento"];

        $loadReg1 = $departamento->load("id_departamento=".$idDepartamento);

        $departamento->id_pais = $_POST['id_pais'];
        $departamento->departamento = strtoupper(strtolower(utf8_decode($_POST['departamento'])));
        $departamento->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['IdPais'] = $departamento->id_pais;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un departamento
     */
    function departamento() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");

        $departamento = new departamentos();
        $pais = new paises();

        $idDepartamento = $_REQUEST["id_departamento"];
        $idPais = $_REQUEST["id_pais"];

        $loadReg = $departamento->load("id_departamento=".$idDepartamento);
        $loadReg1 = $pais->load("id_pais=".$idPais);

        include("./modules/zonificacion/templates/departamento.php");

    }

    /**
     * Funciòn para obtener el listado de paises
     */
    function listPaises(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_pais, cod_pais, pais FROM paises";
        $strWhere = " WHERE 1=1";
        $strOrder = " ORDER BY id_pais DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Paises</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear Pais' alt='Crear Pais' border='0'/>","admindex.php?id_pais=0&mod=zonificacion&action=pais");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Código","Pais"));

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_pais"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_pais"=>"ID_PAIS","mod"=>"zonificacion","action"=>"pais");
        $dataGrid->addColLink("Editar","<center><img src='./images/editar.png' title='Editar Pais' alt='Editar Pais' border='0'/></center>","admindex.php",$arrVarGet1,"","left");
        $dataGrid->addColLink("Ver Departamentos","<center><a href=\"javascript:{function};\"><img src='./images/genealogy.png' title='Ver Departamentos' alt='Ver Departamentos' border='0'/></a></center>","cargarDepartamentos",$arrVarGet1,"functionjs","right");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/zonificacion/templates/listado_paises.php");


    }

    /**
     * Funciòn para guardar informacion pais
     */
    function savePais() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $pais = new paises();

        $idPais = $_REQUEST["id_pais"];

        $loadReg1 = $pais->load("id_pais=".$idPais);

        $pais->cod_pais = strtoupper(strtolower($_POST['cod_pais']));
        $pais->pais = strtoupper(strtolower($_POST['pais']));
        $pais->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un pais
     */
    function pais() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");

        $idPais = $_REQUEST["id_pais"];

        $pais = new paises();

        $loadReg = $pais->load("id_pais=".$idPais);

        include("./modules/zonificacion/templates/pais.php");

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
