<?php
/**
* Adminsitración de tabla de modelo_riesgo
* @version 1.0
* El constructor de esta clase es {@link modelo_riesgo()}
*/
class estudio_riesgo extends ADOdb_Active_Record{
    
    function verificarIntegridadReferencial($idModelo = 0){
    
        global $db;                 
         
        return false;   
    }   
    
    function getEstudiosPorTercero($idTercero = 0){
    
        global $db;
        
        $strSQL = "SELECT er.*, md.nombre as nombre_modelo FROM estudio_riesgo as er INNER JOIN modelo_riesgo as md ON er.id_modelo = md.id_modelo WHERE er.id_tercero = " . $idTercero. " ORDER BY er.anio DESC";
        $rsData = $db->Execute($strSQL);
                
        return $rsData;        
    
    }
    
    function getEstudiosPorModelo($idModelo = 0){
    
        global $db;
        
        $strSQL = "SELECT id_modelo FROM estudio_riesgo WHERE id_modelo = " . $idModelo;
        $rsData = $db->Execute($strSQL);
                
		$idModelo = 0;
        if (!$rsData->EOf){
            $idModelo = $rsData->fields["id_modelo"];                
       	}
                
        return $idModelo;            
    }    

}

?>