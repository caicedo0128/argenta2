<?php
/**
* Adminsitración de tabla de app_param_global
* @version 1.0
* El constructor de esta clase es {@link app_param_global()}
*/
class app_param_global extends ADOdb_Active_Record{


    function getListParam(){
    
        global $db;
        
        $strSQL = "SELECT id_parametro, parametro, valor, descripcion FROM app_param_global";
        $rsData = $db->Execute($strSQL);
        
        return $rsData;
    
    }

}

class app_texts extends ADOdb_Active_Record{


    function getListParam(){
    
        global $db;
        
        $strSQL = "SELECT id, param, text, actualizable FROM app_texts WHERE actualizable=1";
        $rsData = $db->Execute($strSQL);
        
        return $rsData;
    
    }

}

?>