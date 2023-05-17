<?php
/**
* Adminsitración de paginas de la aplicacion
* @author Andres Bravo
* @version 1.0
* El constructor de esta clase es {@link app_paginas()}
*/
class app_paginas extends ADOdb_Active_Record{

    var $_dbat = ADODBMYSQL;
    
	function data_page_alias($alias = ""){
		
		global $db;
		
		$strSQL = "SELECT id_pagina, alias, id_pagina_padre, nombre, titulo_html FROM app_paginas WHERE alias = '".$alias."'";
		$rsDatos = $db->Execute($strSQL);
		return $rsDatos;
	
	}
}

?>