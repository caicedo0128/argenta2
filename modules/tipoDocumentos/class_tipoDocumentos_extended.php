<?php
/**
* Adminsitración de tabla de tipo_documento
* @version 1.0
* El constructor de esta clase es {@link tipo_documento()}
*/
class tipo_documento extends ADOdb_Active_Record{
    
    function verificarIntegridadReferencial($idDocumento = 0){
    
        global $db;                 
         
        return false;   
    }   

    function obtenerDocumentos(){
    
        global $db;

        $arrDocumentos = array();

        $strSQL = "SELECT * FROM tipo_documento ORDER BY tipo_documento";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrDocumentos[$rsDatos->fields["id_tipo_documento"]] = $rsDatos->fields["tipo_documento"];
            $rsDatos->MoveNext();
        }

        return $arrDocumentos;            
    } 
    
    /**
     * Funciòn para traer los documentos
     */
    function getTipoDocumento() {

        global $db;

        $arrDocumentos = array();

        $strSQL = "SELECT * FROM tipo_documento ORDER BY tipo_documento";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrDocumentos[$rsDatos->fields["id_tipo_documento"]] = $rsDatos->fields["tipo_documento"];
            $rsDatos->MoveNext();
        }

        return $arrDocumentos;

    }     
    
    function obtenerDocumentosPorTipoTercero($idTipo = 0){
    
        global $db;

        $arrDocumentos = array();

        $strSQL = "SELECT * FROM tipo_documento WHERE 1=1 ";
        
        //SI NO HAY TIPO O ES CERO TRAE TODOS LOS DOCUMENTOS PARA REGISTRAR
        //EMISOR
        if ($idTipo == 1)
        	$strSQL .= " AND emisor=1"; 

        //PAGADOR
        if ($idTipo == 6)
        	$strSQL .= " AND pagador=1"; 

        //COMERCIAL
        if ($idTipo == 5)
        	$strSQL .= " AND comercial=1"; 
        
        $strSQL .= " ORDER BY tipo_documento";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrDocumentos[$rsDatos->fields["id_tipo_documento"]] = $rsDatos->fields["tipo_documento"];
            $rsDatos->MoveNext();
        }

        return $arrDocumentos;            
    }    
}

?>