<?php

class LoadLibraries{

    function LoadLibraries(){


    }

    function DBConnect($path="./"){

        require_once ($path."conf/config.php");
        require_once ($path."utilities/adodb/adodb.inc.php");
        require_once ($path."utilities/adodb/adodb-active-record.inc.php");

        if(!is_object($db)){
            Global $db;
            $db = NewADOConnection($CONF['CONF_DB_TYPE']);
            $db->Connect($CONF['CONF_DB_HOST'],$CONF['CONF_DB_USER'],$CONF['CONF_DB_PASS'],$CONF['CONF_DB_NAME']);
            ADOdb_Active_Record::SetDatabaseAdapter($db);
        }


    }

    function CloseDB(){

        global $db;

        $db->Close();

    }

    function PHPInit(){
        require_once ("./utilities/functions.php");
    }

    function PHPEnd(){

        return;
    }
    
    function HTMInit(){

        Global $appObj;

        $appObj->checkMetas();
        
        require_once ("./master/LayoutInit.php");
    }


    function HTMInitAdmin(){

        Global $appObj;
        
        require_once ("./master/LayoutInit.php");

    }

    function HTMEnd(){

        Global $appObj;

        echo $appObj->paramGral["STATISTICS"]. "\n";
        require_once ("./master/LayoutEnd.php");
    }

}

?>
