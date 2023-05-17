<?php
/**
* Adminsitración para la clase integracion
* @version 1.0
*/
class operacion extends ADOdb_Active_Record{
        
}

class operacion_factura extends ADOdb_Active_Record{


}

class clientes extends ADOdb_Active_Record{


}

class app_tokens extends ADOdb_Active_Record{

	function consultarToken($app, $fecha){
	
		global $db;
		
		$token = "";
		
		$strSQL = "SELECT * FROM app_tokens WHERE app='".$app."' AND fecha_validez>'".$fecha."'";
		$rsDatos = $db->Execute($strSQL);
		
        if (!$rsDatos->EOF){
            $token = $rsDatos->fields["token"];
        }  
        
		return $token;
	
	}

}

?>