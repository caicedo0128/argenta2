<?php

/*Clase para controlar las tipo terceros*/
class tipo_terceros extends ADOdb_Active_Record{

    /**
     * Funciòn para traer los terceros
     */
    function getTerceros() {

        global $db;

        $arrTerceros = array();

        $strSQL = "SELECT * FROM tipo_tercero WHERE activo=1 ORDER BY nombre";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrTerceros[$rsDatos->fields["id_tipo_tercero"]] = $rsDatos->fields["nombre"];
            $rsDatos->MoveNext();
        }

        return $arrTerceros;

    }

}

/*Clase para controlar las tipo años*/
class años extends ADOdb_Active_Record{

    /**
     * Funciòn para traer los años
     */
    function getAños() {

        global $db;

        $arrDocumentos = array();

        $strSQL = "SELECT * FROM años ORDER BY año";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrDocumentos[$rsDatos->fields["año"]] = $rsDatos->fields["año"];
            $rsDatos->MoveNext();
        }

        return $arrDocumentos;

    }
}

/*Clase para controlar las motivos de rechazo*/
class motivos_rechazo extends ADOdb_Active_Record{

    /**
     * Funciòn para traer los motivos rechazo
     */
    function getMotivosRechazo() {

        global $db;

        $arrMotivos = array();

        $strSQL = "SELECT * FROM motivos_rechazo WHERE activo=1 ORDER BY descripcion";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrMotivos[$rsDatos->fields["id_motivo_rechazo"]] = $rsDatos->fields["descripcion"];
            $rsDatos->MoveNext();
        }

        return $arrMotivos;

    }

}

/*Clase para controlar las sectores*/
class sectores extends ADOdb_Active_Record{

    /**
     * Funciòn para traer los sectores
     */
    function getSectores() {

        global $db;

        $arrData = array();

        $strSQL = "SELECT * FROM sectores ORDER BY nombre";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrData[$rsDatos->fields["id_sector"]] = $rsDatos->fields["nombre"];
            $rsDatos->MoveNext();
        }

        return $arrData;

    }

}

/*Clase para controlar las Ciius*/
class ciiu  extends ADOdb_Active_Record{

    /**
     * Funciòn para traer los Ciius
     */
    function getCiius() {

        global $db;

        $arrData = array();

        $strSQL = "SELECT * FROM ciiu ORDER BY codigo";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrData[$rsDatos->fields["id_ciiu"]] = "(".$rsDatos->fields["codigo"].") ".$rsDatos->fields["descripcion"];
            $rsDatos->MoveNext();
        }

        return $arrData;

    }

}

/*Clase para controlar las numero de empleados*/
class numero_empleados extends ADOdb_Active_Record{

    /**
     * Funciòn para traer el numero de empleados
     */
    function getNumeroEmpleados() {

        global $db;

        $arrData = array();

        $strSQL = "SELECT * FROM numero_empleados ORDER BY id_numero";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrData[$rsDatos->fields["id_numero"]] = $rsDatos->fields["descripcion"];
            $rsDatos->MoveNext();
        }

        return $arrData;

    }

}

/*Clase para controlar las referencias argenta*/
class referencia_argenta extends ADOdb_Active_Record{

    /**
     * Funciòn para traer referencias argenta
     */
    function getReferencias() {

        global $db;

        $arrData = array();

        $strSQL = "SELECT * FROM referencia_argenta ORDER BY referencia";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrData[$rsDatos->fields["id_referencia"]] = $rsDatos->fields["referencia"];
            $rsDatos->MoveNext();
        }

        return $arrData;

    }

}

/*Clase para controlar los plazos de pago*/
class plazo_pago extends ADOdb_Active_Record{

    /**
     * Funciòn para traer referencias argenta
     */
    function getPlazosPago() {

        global $db;

        $arrData = array();

        $strSQL = "SELECT * FROM plazo_pago ORDER BY id_plazo_pago";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrData[$rsDatos->fields["id_plazo_pago"]] = $rsDatos->fields["plazo"];
            $rsDatos->MoveNext();
        }

        return $arrData;

    }

}


/*Clase para controlar las relaciones comercial*/
class relacion_comercial extends ADOdb_Active_Record{

    /**
     * Funciòn para traer relaciones comercial
     */
    function getRelacionComercial() {

        global $db;

        $arrData = array();

        $strSQL = "SELECT * FROM relacion_comercial ORDER BY id_relacion_comercial";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrData[$rsDatos->fields["id_relacion_comercial"]] = $rsDatos->fields["descripcion"];
            $rsDatos->MoveNext();
        }

        return $arrData;

    }

}


?>