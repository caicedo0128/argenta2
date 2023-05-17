<?php

class flujo_caja extends ADOdb_Active_Record{
    
    function obtenerUltimosFlujosCaja($cantidad){
    
        global $db;

        $strSQL = "
					SELECT
					*
					FROM
					flujo_caja
					ORDER BY fecha DESC  
					LIMIT ".$cantidad."
       	        "; 
       	        
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }
    
    function obtenerInformacionFacturasVencidas($fecha){
    
        global $db;

        $strSQL = "    
					SELECT
						sub1.pagador,
						sum(sub1.valor) as valor,
						sub1.menorFechaPago
					FROM
					(
						SELECT
						sum(of.valor_futuro) as valor,
						c2.razon_social as pagador,
						min(of.fecha_pago) as menorFechaPago
						FROM operacion as o
						INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
						INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
						INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
						WHERE (o.estado=1) AND of.estado in (1) AND of.fecha_pago<='".$fecha."'
						GROUP BY c2.razon_social
						UNION
						SELECT
						sum(facaturasPP.nuevo_valor_obligacion) as valor,
						c2.razon_social as pagador,
						min(of.fecha_pago) as menorFechaPago
						FROM operacion as o
						INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
						INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
						INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
						INNER JOIN (
							SELECT 
							ore.id_reliquidacion,
							min(orpp.nuevo_valor_obligacion) as nuevo_valor_obligacion
							FROM
							operacion_reliquidacion as ore
							INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
							WHERE ore.estado=1
							GROUP BY ore.id_reliquidacion
						) as facaturasPP on of.id_reliquidacion = facaturasPP.id_reliquidacion
						WHERE (o.estado=1) AND of.estado in (4,6,8) AND of.fecha_pago<='".$fecha."'
						GROUP BY c2.razon_social
					) AS sub1
					GROUP BY sub1.pagador
					order by sub1.menorFechaPago, sub1.pagador
					
       	        "; 
       	        
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }   
    
    function obtenerInformacionProximoVencimientos($fechaIni, $fechaFin){
    
        global $db;

        $strSQL = "				
					SELECT
						sub1.pagador,
						sum(sub1.valor) as valor,
						sub1.menorFechaPago
					FROM
					(
						SELECT
						sum(of.valor_futuro) as valor,
						c2.razon_social as pagador,
						min(of.fecha_pago) as menorFechaPago
						FROM operacion as o
						INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
						INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
						INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
						WHERE (o.estado=1) AND of.estado in (1) AND (of.fecha_pago >='".$fechaIni."' AND of.fecha_pago <='".$fechaFin."')
						GROUP BY c2.razon_social
						UNION
						SELECT
						sum(facaturasPP.nuevo_valor_obligacion) as valor,
						c2.razon_social as pagador,
						min(of.fecha_pago) as menorFechaPago
						FROM operacion as o
						INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
						INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
						INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
						INNER JOIN (
							SELECT 
							ore.id_reliquidacion,
							min(orpp.nuevo_valor_obligacion) as nuevo_valor_obligacion
							FROM
							operacion_reliquidacion as ore
							INNER JOIN operacion_reliquidacion_pp as orpp ON ore.id_reliquidacion = orpp.id_reliquidacion
							WHERE ore.estado=1
							GROUP BY ore.id_reliquidacion
						) as facaturasPP on of.id_reliquidacion = facaturasPP.id_reliquidacion
						WHERE (o.estado=1) AND of.estado in (4,6,8) AND (of.fecha_pago >='".$fechaIni."' AND of.fecha_pago <='".$fechaFin."')
						GROUP BY c2.razon_social
					) AS sub1
					GROUP BY sub1.pagador
					order by sub1.menorFechaPago, sub1.pagador					
       	        "; 
       	        
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }   
    
    function obtenerInformacionOperacionesProgramadas(){
    
        global $db;

        $strSQL = "
					SELECT
					o.id_operacion,
					o.fecha_operacion,
					o.valor_giro_final as valor,
					c1.razon_social as emisor, 
					c2.razon_social as pagador 
					FROM operacion as o
					INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
					INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
					WHERE (o.estado=3 OR o.estado=4)
       	        "; 
       	        
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }     
    
    function obtenerInformacionRemanentes($fechaInicial, $fechaFinal){
    
        global $db;

        $strSQL = "
					SELECT
					o.id_operacion,
					c1.razon_social as emisor,    
					c2.razon_social as pagador, 
					opt.monto_devolver as monto_devolver_pt,
					opt.nuevo_remanente as nuevo_remanente_pt,
					opp.monto_devolver as monto_devolver_pp,
					od.valor as desembolso_remanente,
					od.fecha_desembolso,
					ore.fecha_registro
					FROM operacion as o
					INNER JOIN clientes as c1 ON c1.id_cliente = o.id_emisor
					INNER JOIN clientes as c2 ON c2.id_cliente = o.id_pagador
					INNER JOIN operacion_reliquidacion as ore ON o.id_operacion = ore.id_operacion AND ore.id_reliquidacion NOT IN (SELECT DISTINCT id_reliquidacion FROM operacion_reliquidacion_abonos )
					LEFT JOIN
					(
						SELECT 
						id_reliquidacion,
						monto_devolver,
						nuevo_remanente
						FROM
						operacion_reliquidacion_pt
					) AS opt ON ore.id_reliquidacion = opt.id_reliquidacion
					LEFT JOIN
					(
						SELECT 
						id_reliquidacion,
						monto_devolver
						FROM
						operacion_reliquidacion_pp
					) AS opp ON ore.id_reliquidacion = opp.id_reliquidacion					
					LEFT JOIN
					(
						SELECT
						id_reliquidacion,
						fecha_desembolso,
						valor
						FROM 
						operacion_desembolsos
						WHERE tipo_registro=2
					) AS od ON ore.id_reliquidacion = od.id_reliquidacion
					WHERE (o.estado=1) AND opt.monto_devolver>0 AND ore.fecha_registro >='".$fechaInicial."' AND ore.fecha_registro <='".$fechaFinal."'
					ORDER BY o.id_operacion, ore.fecha_registro, c1.razon_social
       	        "; 
       	        
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }   
}


class flujo_caja_detalle extends ADOdb_Active_Record{
    
    function obtenerInformacionFlujoDetalle($idFlujoCaja=0){
    
        global $db;

        $strSQL = "
					SELECT
					fd.*                                    
					FROM flujo_caja_detalle as fd
					WHERE fd.id_flujo_caja = '".$idFlujoCaja."'";
       	    
        $rsDatos = $db->Execute($strSQL);
       
        return $rsDatos;            
    }       
}
?>



