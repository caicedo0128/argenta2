<?php
/**
* Adminsitración de tabla de informacion de detalle del cliente
* @version 1.0
* El constructor de esta clase es {@link clientes_detalle()}
*/

class clientes_documentos extends ADOdb_Active_Record{


}

class clientes_referencias extends ADOdb_Active_Record{

	function obtenerReferenciaCliente($idCliente = 0, $tipoReferencia){

		global $db;

		$strSQL = "SELECT cr.*, pp.plazo, rc.descripcion as relacion_comercial
					FROM clientes_referencias as cr
					LEFT JOIN plazo_pago as pp ON cr.id_plazo_pago=pp.id_plazo_pago
					LEFT JOIN relacion_comercial as rc ON cr.id_relacion_comercial=rc.id_relacion_comercial
					WHERE cr.id_cliente=".$idCliente." AND cr.tipo_referencia=" . $tipoReferencia;
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

}

class clientes_socios_accionistas extends ADOdb_Active_Record{

	function obtenerSociosCliente($idCliente = 0){

		global $db;

		$strSQL = "SELECT csa.*, p.descripc as pais
					FROM clientes_socios_accionistas as csa
					LEFT JOIN paises as p ON csa.id_pais = p.id_pais
					WHERE csa.id_cliente=".$idCliente;
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerSociosBeneficiariosCliente($idCliente = 0, $beneficiario = 1){

		global $db;

		$strSQL = "SELECT csa.*, p.descripc as pais
					FROM clientes_socios_accionistas as csa
					LEFT JOIN paises as p ON csa.id_pais = p.id_pais
					WHERE csa.id_cliente=".$idCliente. " AND csa.socio_beneficiario=".$beneficiario;
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

}

class clientes_ref_pagador extends ADOdb_Active_Record{

    function validarIntegridadReferencial($idPagador=0){

        global $db;

        $strSQL = "SELECT count(*) as total FROM operacion WHERE id_pagador = " . $idPagador;
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            if ($rsDatos->fields["total"] > 0)
                return false;
        }

        return true;

    }

	function obtenerTodosPagadoresSinFiltro(){

        global $db;

        $arrclientes = array();

        $strSQL = "SELECT * FROM clientes WHERE (id_tipo_tercero = 6 || id_tipo_tercero_sec = 6) ORDER BY razon_social";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrclientes[$rsDatos->fields["id_cliente"]] = $rsDatos->fields["razon_social"];
            $rsDatos->MoveNext();
        }

        return $arrclientes;

    }

    function obtenerTodosPagadores($idCliente = 0){

        global $db;

        $arrclientes = array();

        $strSQL = "SELECT * FROM clientes WHERE (id_tipo_tercero = 6 || id_tipo_tercero_sec = 6) AND id_cliente NOT IN (SELECT id_pagador FROM clientes_ref_pagador WHERE id_cliente = ".$idCliente.") ORDER BY razon_social";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrclientes[$rsDatos->fields["id_cliente"]] = $rsDatos->fields["razon_social"];
            $rsDatos->MoveNext();
        }

        return $arrclientes;

    }

	function obtenerPagadoresSeleccionados($idCliente = 0){

		global $db;

		$arrclientes = array();

        $strSQL = "SELECT * FROM clientes WHERE (id_tipo_tercero = 6 || id_tipo_tercero_sec = 6) AND id_cliente IN (SELECT id_pagador FROM clientes_ref_pagador WHERE id_cliente = ".$idCliente.") ORDER BY razon_social";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrclientes[$rsDatos->fields["id_cliente"]] = $rsDatos->fields["razon_social"];
            $rsDatos->MoveNext();
        }

        return $arrclientes;

	}

	function obtenerPagadoresCliente($idCliente = 0){

		global $db;

		$strSQL = "SELECT crp.id_pagador, c.razon_social FROM clientes_ref_pagador crp
					INNER JOIN clientes as c ON crp.id_pagador =  c.id_cliente
				   WHERE crp.id_cliente = ".$idCliente. " AND activo=1 ORDER BY c.razon_social";

		$rsData = $db->Execute($strSQL);
        $arrData = array();
        while (!$rsData->EOF){

            $value = utf8_encode($rsData->fields["razon_social"]);
            $arrData[] = array("Value"=>$rsData->fields["id_pagador"],"Text"=>$value);
            $rsData->MoveNext();
        }

        return json_encode($arrData);

 	}

}

class clientes_res_facturas extends ADOdb_Active_Record{

}

class clientes_adicionales extends ADOdb_Active_Record{

}

class clientes_seguimiento extends ADOdb_Active_Record{

	function obtenerSeguimientoCliente($idCliente = 0){

		global $db;

		$strSQL = "SELECT cs.*, CONCAT(uc.nombres, ' ', uc.apellidos) as usuario_procesa,  CONCAT(ua.nombres, ' ', ua.apellidos) as usuario_asignado
				   FROM clientes_seguimiento as cs
				   LEFT JOIN users as uc ON cs.id_usuario = uc.id_usuario
				   LEFT JOIN users as ua ON cs.id_usuario_responsable = ua.id_usuario
				   WHERE cs.id_cliente=".$idCliente." ORDER BY cs.fecha_proceso DESC";

		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerTareasCliente($idCliente = 0){

		global $db;

		$strSQL = "SELECT * FROM clientes_seguimiento WHERE id_cliente=".$idCliente." AND es_tarea=1 ORDER BY fecha_proceso DESC";
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerTareasPendientesCliente($idCliente = 0){

		global $db;

		$strSQL = "SELECT * FROM clientes_seguimiento WHERE id_cliente=".$idCliente." AND es_tarea=1 AND fecha_respuesta is null ORDER BY fecha_proceso DESC";
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerTareasPendientesClientePorUsuario($idCliente = 0, $idUsuario = 0){

		global $db;

		$strSQL = "SELECT * FROM clientes_seguimiento WHERE id_usuario_responsable=".$idUsuario." AND id_cliente=".$idCliente." AND es_tarea=1 AND fecha_respuesta is null ORDER BY fecha_proceso DESC";
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerTareasPendientesPorUsuario($idUsuario = 0){

		global $db;

		$strSQL = "SELECT cs.*, c.razon_social, CONCAT(uc.nombres, ' ', uc.apellidos) as usuario_asigna
		    	   FROM clientes_seguimiento as cs
		    	   INNER JOIN users as uc ON cs.id_usuario = uc.id_usuario
		    	   INNER JOIN clientes as c ON cs.id_cliente = c.id_cliente
				   WHERE cs.id_usuario_responsable=".$idUsuario." AND cs.es_tarea=1 AND cs.fecha_respuesta is null
				   ORDER BY cs.fecha_proceso";

		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerTareasPendientesAsignadasPorUsuario($idUsuario = 0){

		global $db;

		$strSQL = "SELECT cs.*, c.razon_social, CONCAT(uc.nombres, ' ', uc.apellidos) as usuario_responsable
		    	   FROM clientes_seguimiento as cs
		    	   INNER JOIN users as uc ON cs.id_usuario_responsable = uc.id_usuario
		    	   INNER JOIN clientes as c ON cs.id_cliente = c.id_cliente
				   WHERE cs.id_usuario=".$idUsuario." AND cs.es_tarea=1 AND cs.fecha_respuesta is null
				   ORDER BY cs.fecha_proceso";

		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}
}

class clientes_verificaciones extends ADOdb_Active_Record{

	function obtenerVerificacionesCliente($idCliente){

		global $db;

		$strSQL = "SELECT * FROM clientes_verificaciones WHERE id_cliente=".$idCliente." ORDER BY id_tipo_proceso";
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

	function obtenerVerificacionClientePorTipo($idCliente,$idTipo){

		global $db;

		$strSQL = "SELECT * FROM clientes_verificaciones WHERE id_cliente=".$idCliente." AND id_tipo_verificacion=".$idTipo;
		$rsDatos = $db->Execute($strSQL);

		return $rsDatos;

	}

}

?>