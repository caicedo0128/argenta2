<?php
/**
* Adminsitración de paginas de la aplicacion
* @author Andres Bravo
* @version 1.0
* El constructor de esta clase es {@link app_paginas()}
*/
class usuarios_perfil extends ADOdb_Active_Record{

    var $_dbat = ADODBMYSQL;
    
}

class perfil_permiso extends ADOdb_Active_Record{

    var $_dbat = ADODBMYSQL;
    
    function borrarPermisosPerfil($idPerfil){
    
        global $db;
        
        $strSQL = "DELETE FROM perfil_permiso WHERE id_perfil=".$idPerfil;
        $db->Execute($strSQL);
        
        return true;
    
    }
    
    function obtenerPermisosPorPerfil($idPerfil){
    
        global $db;
        
        $arrPermisos = array();
        $strSQL = "SELECT * FROM perfil_permiso WHERE id_perfil=".$idPerfil;
        $rsData = $db->Execute($strSQL);
        
        while (!$rsData->EOF){
            
            $arrPermisos[] = $rsData->fields["alias_accion"];
            $rsData->MoveNext();
        }
        
        return $arrPermisos;
    
    }  
    
    function generarPermisosSesion($idPerfil){
    
        global $db;        
        
        $arrPermisos = array();
        $strSQL = "SELECT * FROM perfil_permiso WHERE id_perfil=".$idPerfil;
        $rsData = $db->Execute($strSQL);
        
        while (!$rsData->EOF){
            
            $arrPermisos[] = $rsData->fields["alias_accion"];
            $rsData->MoveNext();
        }
        
        $_SESSION["permisos_perfil"] = $arrPermisos;
        return $arrPermisos;
    
    }
    
    function tienePermisosAccion($arrAcciones = array()){
    
        global $db;        
        
        $tienePermiso = false;
        if (Count($arrAcciones) > 0){
            foreach($arrAcciones as $key=>$accion){
                if (in_array($accion, $_SESSION["permisos_perfil"]))
                    $tienePermiso = true;
            }
        }
        
        if ($_SESSION["profile_text"] == "Superadministrador"){
            $tienePermiso = true;
        }
        
        return $tienePermiso;    
    }    
}

?>