<?php
/**
* Adminsitración de tabla de paises
* @version 1.0
* El constructor de esta clase es {@link paises()}
*/
class paises extends ADOdb_Active_Record{

    function paisesAllJson(){

        global $db;

        $strSQL = "SELECT * FROM paises order by descripc";
		$rsData = $db->Execute($strSQL);
        $arrData = array();
        while (!$rsData->EOF){

            $value = $rsData->fields["descripc"];
            $arrData[] = array("id"=>$rsData->fields["id_pais"],"value"=>$value);
            $rsData->MoveNext();
        }

        return json_encode($arrData);
    }
    
    function getPaises(){

        global $db;

        $arrPaises = array();

        $strSQL = "SELECT * FROM paises order by descripc";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrPaises[$rsDatos->fields["id_pais"]] = $rsDatos->fields["descripc"];
            $rsDatos->MoveNext();
        }

        return $arrPaises;

    } 
    
    function getPaisesDesc(){

        global $db;

        $arrPaises = array();

        $strSQL = "SELECT * FROM paises order by descripc";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrPaises[$rsDatos->fields["descripc"]] = $rsDatos->fields["descripc"];
            $rsDatos->MoveNext();
        }

        return $arrPaises;

    }    

}

class departamentos extends ADOdb_Active_Record{


    function departamentosPorPais($idPais = 0){

         global $db;

         $strSQL = "SELECT * FROM departamentos WHERE id_pais = ".$idPais. " ORDER BY departamento";
         $rsData = $db->Execute($strSQL);

         return $rsData;

    }
    
    function departamentosPorPaisJson($idPais){

        global $db;

		$strSQL = "SELECT * FROM departamentos WHERE id_pais = ".$idPais. " ORDER BY departamento";
		$rsData = $db->Execute($strSQL);
        $arrData = array();
        while (!$rsData->EOF){

            $value = $rsData->fields["departamento"];
            $arrData[] = array("Value"=>$rsData->fields["id_departamento"],"Text"=>$value);
            $rsData->MoveNext();
        }

        return json_encode($arrData);

    }    
    
    function getDepartamentosPorPais($idPais=0){

        global $db;

        $arrDeptos = array();

        $strSQL = "SELECT * FROM departamentos order by departamento";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrDeptos[$rsDatos->fields["id_departamento"]] = $rsDatos->fields["departamento"];
            $rsDatos->MoveNext();
        }

        return $arrDeptos;

    }        

}

class ciudades extends ADOdb_Active_Record{


    function ciudadesPorDepartamento($idDepartamento = 0){

         global $db;

         $strSQL = "SELECT * FROM ciudades WHERE id_departamento = ".$idDepartamento . " ORDER BY ciudad";
         $rsData = $db->Execute($strSQL);

         return $rsData;

    }

    function ciudadesAll(){

         global $db;

         $strSQL = "SELECT
                      paises.cod_pais,
                      departamentos.departamento,
                      ciudades.id_ciudad,
                      ciudades.ciudad
                    FROM
                      paises
                      INNER JOIN departamentos ON (paises.id_pais = departamentos.id_pais)
                      INNER JOIN ciudades ON (departamentos.id_departamento = ciudades.id_departamento)
                    ORDER BY ciudades.ciudad
                    ";
                    
         $rsData = $db->Execute($strSQL);

         return $rsData;

    }
    
    function getCiudades(){

        global $db;

        $arrCiudades = array();

        $rsData = $this->ciudadesAll();

        while (!$rsData->EOF){

            $arrCiudades[$rsData->fields["id_ciudad"]] = utf8_encode($rsData->fields["ciudad"]);
            $rsData->MoveNext();
        }

        return $arrCiudades;

    }            

    function ciudadesAllJson(){

        global $db;

        $rsData = $this->ciudadesAll();
        $arrData = array();
        while (!$rsData->EOF){

            $value = utf8_encode($rsData->fields["ciudad"]) . "(".substr($rsData->fields["departamento"],0,3)."-".$rsData->fields["cod_pais"].")";
            $arrData[] = array("id"=>$rsData->fields["id_ciudad"],"value"=>$value);
            $rsData->MoveNext();
        }

        return json_encode($arrData);
    }
    
    function ciudadesPorDepartamentoJson($idDepartamento = 0){

        global $db;

        $strSQL = "SELECT * FROM ciudades WHERE id_departamento = ".$idDepartamento. " ORDER BY ciudad";
        $rsData = $db->Execute($strSQL);
        $arrData = array();
        while (!$rsData->EOF){

            $value = utf8_encode($rsData->fields["ciudad"]);
            $arrData[] = array("Value"=>$value,"Text"=>$value);
            $rsData->MoveNext();
        }

        return json_encode($arrData);

    }    
}

class zona_sectores extends ADOdb_Active_Record{


    function getZonas(){

         global $db;

        $arrZonas = array();

        $strSQL = "SELECT id_zona_sector, zona_sector FROM zona_sectores ORDER BY zona_sector";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrZonas[$rsDatos->fields["id_zona_sector"]] = $rsDatos->fields["zona_sector"];
            $rsDatos->MoveNext();
        }

        return $arrZonas;

    }

    function zonasPorCiudad($idCiudad){

         global $db;

        $arrZonas = array();

        $strSQL = "SELECT id_zona_sector, zona_sector FROM zona_sectores WHERE id_ciudad = ".$idCiudad." ORDER BY zona_sector";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrZonas[] = array("id_zona_sector"=>$rsDatos->fields["id_zona_sector"], "zona_sector"=>$rsDatos->fields["zona_sector"]);
            $rsDatos->MoveNext();
        }

        return $arrZonas;

    }

}


class zonas extends ADOdb_Active_Record{


    function getZonas(){

         global $db;

        $arrZonas = array();

        $strSQL = "SELECT id_zona, zona FROM zonas ORDER BY zona";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrZonas[$rsDatos->fields["id_zona"]] = $rsDatos->fields["zona"];
            $rsDatos->MoveNext();
        }
        return $arrZonas;

    }

}

?>

