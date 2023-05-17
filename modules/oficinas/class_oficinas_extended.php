<?php
/**
* Adminsitración de opciones modulo Oficinas
* @version 1.0
*/
class sub_oficinas extends ADOdb_Active_Record{

    function verificarIntegridadReferencial($idSubOficina = 0){

        global $db;

        //DETERMINAMOS REFERENCIA EN RECIBOS OFICINA
        $strSQL = "SELECT count(*) as total FROM recibos_oficina WHERE id_suboficina='".$idSubOficina."'";
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return true;
        }

        //DETERMINAMOS REFERENCIA EN CONSUMOS DETALLE
        $strSQL = "SELECT count(*) as total FROM consumos_detalle WHERE id_suboficina='".$idSubOficina."'";
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            $total = $rsDatos->fields["total"];
            if ($total>0)
                return true;
        }

        return false;
    }

    /**
     * Funciòn para traer los suboficinas
     */
    function getSuboficinas() {

        global $db;

        $arrSuboficinas = array();

        $strSQL = "SELECT so.id_suboficina, so.nombre, o.nombre as oficina FROM sub_oficinas as so INNER JOIN oficinas as o ON so.id_oficina = o.id_oficina ORDER BY o.nombre, so.nombre";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrSuboficinas[$rsDatos->fields["id_suboficina"]] = "(" . $rsDatos->fields["oficina"] . ")-". $rsDatos->fields["nombre"];
            $rsDatos->MoveNext();
        }


        return $arrSuboficinas;

    }

    /**
     * Funciòn para traer los suboficinas con su codigo
     */
    function getSuboficinasCodigo() {

        global $db;

        $arrSuboficinas = array();

        $strSQL = "SELECT so.codigo, so.id_suboficina, so.id_oficina FROM sub_oficinas as so";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrSuboficinas[$rsDatos->fields["codigo"]] = array("IdSubOficina"=>$rsDatos->fields["id_suboficina"], "IdOficina"=>$rsDatos->fields["id_oficina"]);
            $rsDatos->MoveNext();
        }


        return $arrSuboficinas;

    }

    /**
     * Funciòn para traer los suboficinas por Oficina
     */
    function getSuboficinasPorOficina($idOficina = 0) {

        global $db;

        $arrSuboficinas = array();

        $strSQL = "SELECT so.id_suboficina, so.nombre, o.nombre as oficina FROM sub_oficinas as so INNER JOIN oficinas as o ON so.id_oficina = o.id_oficina
        			WHERE so.id_oficina = ".$idOficina."
        			ORDER BY o.nombre, so.nombre";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrSuboficinas[$rsDatos->fields["id_suboficina"]] = "(" . $rsDatos->fields["oficina"] . ")-". $rsDatos->fields["nombre"];
            $rsDatos->MoveNext();
        }


        return $arrSuboficinas;

    }

}

?>
