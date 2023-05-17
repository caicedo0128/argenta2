<?php
/**
* Adminsitración de tabla de modelo_riesgo
* @version 1.0
* El constructor de esta clase es {@link modelo_riesgo()}
*/
class modelo_riesgo extends ADOdb_Active_Record{
    
    function verificarIntegridadReferencial($idModelo = 0){
    
        global $db;                 
         
        return false;   
    }  
    
    function obtenerModelos(){
    
        global $db;

        $arrModelos = array();

        $strSQL = "SELECT * FROM modelo_riesgo WHERE activo=1 ORDER BY nombre";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrModelos[$rsDatos->fields["id_modelo"]] = $rsDatos->fields["nombre"];
            $rsDatos->MoveNext();
        }

        return $arrModelos;        
    
    }

}

class modelo_campos extends ADOdb_Active_Record{

    function getGruposPorModelo($idModelo = 0){
    
        global $db;
        
        $strSQL = "SELECT DISTINCT ID_GRUPO, gc.*
        			FROM modelo_campos as mc 
        			INNER JOIN grupo_campos as gc ON gc.id_grupo = mc.id_grupo  
        			WHERE mc.id_modelo = " . $idModelo. " ORDER BY gc.orden ASC";

        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }

    function getCamposPorModelo($idModelo = 0){
    
        global $db;
        
        $strSQL = "SELECT mc.*, c.tipo_campo, gc.grupo, c.campo_oculto FROM modelo_campos as mc INNER JOIN campos as c ON mc.id_campo = c.id_campo INNER JOIN grupo_campos as gc ON gc.id_grupo = mc.id_grupo  WHERE mc.id_modelo = " . $idModelo. " ORDER BY gc.orden,mc.orden ASC";
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }
    
    function getCamposCalculadosPorModelo($idModelo = 0){
    
        global $db;
        
        $strSQL = "SELECT mc.*, c.tipo_campo, gc.grupo, c.formula, c.campo FROM modelo_campos as mc INNER JOIN campos as c ON mc.id_campo = c.id_campo INNER JOIN grupo_campos as gc ON gc.id_grupo = mc.id_grupo  WHERE mc.id_modelo = " . $idModelo. " AND c.tipo_campo = 4 ORDER BY gc.orden,mc.orden ASC";
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }    
    
    function getCamposPorGrupo($idGrupo = 0){
    
        global $db;
        
        $strSQL = "SELECT mc.*, c.campo as nombre_campo, c.tipo_campo, c.texto_imprimir FROM modelo_campos as mc INNER JOIN campos as c ON mc.id_campo = c.id_campo WHERE mc.id_grupo = " . $idGrupo. " ORDER BY mc.orden ASC";
        $rsData = $db->Execute($strSQL);
                
        return $rsData;
        
    }    
    
    function obtenerOrden($idGrupo = 0){
    
        global $db;
        
        $strSQL = "SELECT max(orden) as orden FROM modelo_campos WHERE id_grupo = " . $idGrupo;
        $rsData = $db->Execute($strSQL);
        
        $orden = 0;
        if (!$rsData->EOf)
            $orden = $rsData->fields["orden"];
        
        return $orden; 
        
    }    

}

class grupo_campos extends ADOdb_Active_Record{

    function obtenerOrden($idModelo = 0){
    
        global $db;
        
        $strSQL = "SELECT max(orden) as orden FROM grupo_campos WHERE id_modelo = " . $idModelo;
        $rsData = $db->Execute($strSQL);
        
        $orden = 0;
        if (!$rsData->EOf)
            $orden = $rsData->fields["orden"];
        
        return $orden; 
        
    }

    function getGrupoPorModelo($idModelo = 0){
    
        global $db;
        
        $strSQL = "SELECT * FROM grupo_campos WHERE id_modelo = " . $idModelo. " ORDER BY orden ASC, activo";
        $rsData = $db->Execute($strSQL);
                
        return $rsData; 
        
    }

}

class campos_instancia extends ADOdb_Active_Record{

    function eliminarCamposInstanciaEstudio($idEstudio = 0){
        
        global $db;
        
        $strSQL = "DELETE FROM campos_instancia WHERE id_estudio=" . $idEstudio;
        $db->Execute($strSQL);
    }

    function getCamposPorEstudio($idEstudio = 0){
    
        global $db;
        
        $arrCamposEstudio = array();

        $strSQL = "SELECT ci.valor, c.id_campo  FROM campos_instancia as ci INNER JOIN campos as c ON ci.id_campo = c.id_campo WHERE ci.id_estudio = " . $idEstudio;
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrCamposEstudio[$rsDatos->fields["id_campo"]] = $rsDatos->fields["valor"];
            $rsDatos->MoveNext();
        }  
        
        return $arrCamposEstudio; 
        
    }
    
    function getCamposPorEstudioCalculos($idEstudio = 0){
    
        global $db;
        
        $arrCamposEstudio = array();

        $strSQL = "SELECT ci.valor, c.campo  FROM campos_instancia as ci INNER JOIN campos as c ON ci.id_campo = c.id_campo WHERE ci.id_estudio = " . $idEstudio;
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrCamposEstudio[$rsDatos->fields["campo"]] = $rsDatos->fields["valor"];
            $rsDatos->MoveNext();
        }  
        
        return $arrCamposEstudio; 
        
    }    

}

?>