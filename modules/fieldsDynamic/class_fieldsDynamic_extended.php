<?php
/**
* Adminsitración de tabla de campos
* @version 1.0
* El constructor de esta clase es {@link campos()}
*/
class campos extends ADOdb_Active_Record{
       
    function getCamposFormula(){
    
        global $db;

        $arrCampos = array();

        $strSQL = "SELECT * FROM campos ORDER BY campo";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrCampos[$rsDatos->fields["id_campo"]] = $rsDatos->fields["campo"];
            $rsDatos->MoveNext();
        }

        return $arrCampos;    
    
    }
    
    function getCamposCalculados(){
    
        global $db;

        $strSQL = "SELECT * FROM campos WHERE tipo_campo = 4 ORDER BY nivel_ejecucion";
        $rsDatos = $db->Execute($strSQL);

        return $rsDatos;    
    
    }    
    
    function verificarIntegridadReferencial($idCampo = 0){
    
        global $db;                 
        
        
        
        return false;   
    }    

}

?>