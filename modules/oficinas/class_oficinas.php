<?php
/**
* Adminsitración del modulo Oficinas
* @version 1.0
* El constructor de esta clase es {@link oficinas()}
*/
require_once("class_oficinas_extended.php");
class oficinas extends ADOdb_Active_Record{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "oficina":
                            $this->oficina();
                            break;
            case "saveOficina":
                            $this->saveOficina();
                            break;
            case "listOficinas":
                            $this->listOficinas();
                            break;
            case "deleteOficina":
                            $this->deleteOficina();
                            break;
            case "suboficina":
                            $this->suboficina();
                            break;
            case "saveSuboficina":
                            $this->saveSuboficina();
                            break;
            case "listSuboficinas":
                            $this->listSuboficinas();
                            break;
            case "deleteSuboficina":
                            $this->deleteSuboficina();
                            break;
        }
    }

    /**
      * Funciòn para eliminar una oficina
      */
    function deleteOficina() {

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;


        $idOficina = $_REQUEST["id_oficina"];

        //DETERMINAMOS SI TIENE REFERENCIA
        $tieneReferencia = $this->verificarIntegridadReferencial($idOficina);

        if (!$tieneReferencia){

            $loadReg = $this->load("id_oficina=".$idOficina);
            $this->Delete();

            $msjProcesoRealizado = "El registro se elimino con exito.";
        }
        else{
            $msjProcesoRealizado = "El registro no se puede elminar por que tiene referencia en otras tablas.";
        }

        $this->listOficinas();
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
     * Funciòn para obtener el listado de oficinas
     */
    function listOficinas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";


        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_oficina, codigo_oficina, nombre, ciudad, direccion, telefono1 FROM oficinas INNER JOIN ciudades ON oficinas.id_ciudad = ciudades.id_ciudad";
        $strWhere = " WHERE 1=1";
        $strOrder = " ORDER BY id_oficina DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Oficinas</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear oficina' alt='Crear oficina' border='0'/>","javascript:editarOficina(0,'oficinas','oficina')");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Cod. Oficina","Nombre","Ciudad","Dirección","Teléfono 1"));

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_oficina"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_oficina"=>"ID_OFICINA","mod"=>"oficinas","action"=>"oficina");
        $arrVarGet2 = Array("id_oficina"=>"ID_OFICINA","mod"=>"oficinas","action"=>"deleteOficina");
        $arrVarGet3 = Array("id_oficina"=>"ID_OFICINA","mod"=>"oficinas","action"=>"listSuboficinas");
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Oficina' alt='Editar Oficina' border='0'/></a></center>","editarOficina",$arrVarGet1,"functionjs","left");
        $dataGrid->addColLink("Eliminar","<center><img src='./images/eliminar.png' title='Eliminar oficina' alt='Eliminar oficina' border='0'/></center>","admindex.php",$arrVarGet2,"","left");
        //$dataGrid->addColLink("Areas","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Cargar Areas' alt='Cargar Areas' border='0'/></a></center>","cargarSubOficinas",$arrVarGet3,"functionjs","right");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/oficinas/templates/listado_oficinas.php");


    }

    /**
     * Funciòn para registrar un oficina
     */
    function saveOficina() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $loadReg = $this->load("id_oficina=".$_POST['id_oficina']);

        $this->codigo_oficina = $_POST['codigo_oficina'];
        $this->nombre = utf8_decode($_POST['nombre']);
        $this->id_ciudad = $_POST['id_ciudad'];
        $this->contacto = utf8_decode($_POST['contacto']);
        $this->direccion = $_POST['direccion'];
        $this->telefono1 = $_POST['telefono1'];
        $this->telefono2 = $_POST['telefono2'];
        $this->celular = $_POST["celular"];
        $this->email = $_POST["email"];
        $this->estado = $_POST["Estado"];
        $this->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;
        $jsondata['IdOficina'] = $this->id_oficina;

        echo json_encode($jsondata);
        exit;
    }


    /**
     * Funciòn para ver el formulario de registrar un oficina
     */
    function oficina() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/email.php");
        require_once("./utilities/controles/autocomplete.php");
        require_once("./utilities/controles/radio.php");
        require_once("./modules/zonificacion/class_zonificacion_extended.php");

        $ciudad = new ciudades();

        $idOficina = $_REQUEST["id_oficina"];

        $loadReg = $this->load("id_oficina=".$idOficina);

        //TRAEMOS LAS CIUDADES
        $jsonCiudades = $ciudad->ciudadesAllJson();

        include("./modules/oficinas/templates/oficina.php");

    }

    /**
     * Funciòn para traer los oficinas
     */
    function getOficinas() {

        global $db;

        $arrOficinas = array();

        $strSQL = "SELECT * FROM oficinas ORDER BY nombre";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrOficinas[$rsDatos->fields["id_oficina"]] = $rsDatos->fields["nombre"];
            $rsDatos->MoveNext();
        }


        return $arrOficinas;

    }

    function verificarIntegridadReferencial($idOficina = 0){

        global $db;

        //DETERMINAMOS REFERENCIA EN RECIBOS OFICINA
        $strSQL = "SELECT count(*) as total FROM recibos_oficina WHERE id_oficina='".$idOficina."'";
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return true;
        }

        //DETERMINAMOS REFERENCIA EN CONSUMOS DETALLE
        $strSQL = "SELECT count(*) as total FROM consumos_detalle WHERE id_oficina='".$idOficina."'";
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return true;
        }

        //DETERMINAMOS REFERENCIA EN SOLICITUDES
        $strSQL = "SELECT count(*) as total FROM solicitudes WHERE id_oficina='".$idOficina."'";
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return true;
        }

        return false;
    }

    //METODOS SUBOFICINAS
    /**
      * Funciòn para eliminar una suboficina
      */
    function deleteSuboficina() {

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $subOficina = new sub_oficinas();

        $idSuboficina = $_REQUEST["id_suboficina"];
        $loadReg = $subOficina->load("id_suboficina=".$idSuboficina);

        //DETERMINAMOS SI TIENE REFERENCIA
        $tieneReferencia = $subOficina->verificarIntegridadReferencial($idSuboficina);

        if (!$tieneReferencia){

            $subOficina->Delete();

            $msjProcesoRealizado = "El registro se elimino con exito.";
        }
        else{
            $msjProcesoRealizado = "El registro no se puede elminar por que tiene referencia en otras tablas.";
        }

        $jsondata['Message'] = $msjProcesoRealizado;
        $jsondata['Success'] = true;
        $jsondata['IdOficina'] = $subOficina->id_oficina;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para obtener el listado de suboficinas
     */
    function listSuboficinas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";
        $dataGrid->tableDataId = "tableSubOficinas";

        //TRAEMOS LA OFICINA
        $idOficina = $_REQUEST["id_oficina"];
        $loadReg = $this->load("id_oficina=".$idOficina);

        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT id_suboficina, codigo, nombre, direccion, telefono1 FROM sub_oficinas";
        $strWhere = " WHERE 1=1 AND id_oficina = " . $idOficina;
        $strOrder = " ORDER BY id_suboficina DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Areas para oficina: " . $this->nombre. "</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("<img src='./images/crear.png' title='Crear area' alt='Crear area' border='0'/>","javascript:editarSubOficina(".$idOficina.",0,'oficinas','suboficina')");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Codigo","Nombre","Dirección","Teléfono 1"));

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_suboficina"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_oficia"=>$idOficina,"id_suboficina"=>"ID_SUBOFICINA","mod"=>"oficinas","action"=>"suboficina");
        $arrVarGet2 = Array("id_oficia"=>$idOficina,"id_suboficina"=>"ID_SUBOFICINA","mod"=>"oficinas","action"=>"deleteSuboficina");
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar area' alt='Editar area' border='0'/></a></center>","editarSubOficina",$arrVarGet1,"functionjs","left");
        $dataGrid->addColLink("Eliminar","<center><a href=\"javascript:{function};\"><img src='./images/eliminar.png' title='Eliminar area' alt='Eliminar area' border='0'/></a></center>","eliminarSubOficina",$arrVarGet2,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/oficinas/templates/listado_suboficinas.php");


    }

    /**
     * Funciòn para registrar un suboficina
     */
    function saveSuboficina() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $subOficina = new sub_oficinas();

        $loadReg = $subOficina->load("id_suboficina=".$_POST['id_suboficina']);

        $subOficina->codigo = utf8_decode($_POST['codigo']);
        $subOficina->nombre = utf8_decode($_POST['nombre']);
        $subOficina->id_oficina = $_POST['id_oficina'];
        $subOficina->contacto = $_POST['contacto'];
        $subOficina->usuario_entrega = utf8_decode($_POST['usuario_entrega']);
        $subOficina->direccion = $_POST['direccion'];
        $subOficina->telefono1 = $_POST['telefono1'];
        $subOficina->telefono2 = $_POST['telefono2'];
        $subOficina->estado = $_POST["Estado"];
        $subOficina->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;
        $jsondata['IdOficina'] = $subOficina->id_oficina;

        echo json_encode($jsondata);
        exit;
    }


    /**
     * Funciòn para ver el formulario de registrar un suboficina
     */
    function suboficina() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/email.php");
        require_once("./utilities/controles/radio.php");

        $subOficina = new sub_oficinas();

        $idOficina = $_REQUEST['id_oficina'];

        $loadReg = $subOficina->load("id_suboficina=".$_REQUEST['id_suboficina']);

        include("./modules/oficinas/templates/suboficina.php");

    }

}

?>
