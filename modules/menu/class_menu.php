<?php
/**
* Adminsitraci�n de paginas para armar un menu de navegacion
* @author Andres Bravo
* @version 1.0
* El constructor de esta clase es {@link menu()}
*/
class menu extends ADOdb_Active_Record{


    var $Database;
    var $ID;


    /**
      * Funci�n para seleccionar opciones de administrador
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){


        }

    }

    /**
      * Funci�n para seleccionar opciones de la parte publica
      */
    function parsePublic() {
        
        
        global $db,$id,$action,$option,$option2,$appObj;
        
        $id = $appObj->id;
        
        switch($appObj->action){        
            
            case "getMenu":
                            $this->getMenu();
                            break;
            case "getMenuStatic":
                            $this->getMenuStatic();
                            break;
        }

    }

    /**
     * Funci�n para armar el menu de navegacion estatico
     */
    function getMenuStatic() {

        global $db,$id,$action,$option,$option2,$appObj;


        include("./modules/menu/templates/menu.php");   
    }

    /**
     * Funci�n para armar el menu de navegacion
     */
    function getMenu() {
    
        global $db,$id,$action,$option,$option2,$appObj,$entraAlHijo;
        
        require_once("./modules/users/class_users.php");
    
        $_SESSION["itemsMenu"] = null;
        
        $arrItemsPadre = $this->getItemsPadre("aplica_menu = 1 AND  oculto = -1 AND  id_pagina_padre = '0'", true);

        $arrItemsHijos = $this->getItemsPadre("aplica_menu = 1 AND  oculto = -1 AND  id_pagina_padre != '0'", true);
        
        $totalItemsPadre = count($arrItemsPadre);
        
        //RECORREMOS LA PRIMERA VEZ LOS ITEMS PADRE PARA GENERAR EL ARBOL GENEALOGICO EN UN ARRREGLO
        for ($i=0;$i<$totalItemsPadre;$i++){
            
            $page = $arrItemsPadre[$i]["alias"];

            //TRAEMOS LOS ITEMS HIJOS 
            $this->crearArbol("app_paginas","alias","nombre","id_pagina_padre",$page," AND oculto=-1 AND aplica_menu=1 ","-"," ORDER BY orden ASC");        
        
        }
        
        //LINK AL HOME
        //$strMenu .= "<li class='".$strClass."'><a href=\"index.php\" >Inicio<!--[if gte IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]-->\n";             
        //$strMenu .= "<!--[if lte IE 6]></td></tr></table></a><![endif]--></li>\n";


        //RECORREMOS LA SEGUNDA VEZ LOS ITEMS PADRE PARA GENERAR EL MENU
        for ($i=0;$i<$totalItemsPadre;$i++){
            
            $page = $arrItemsPadre[$i]["alias"];
            $nombre = $arrItemsPadre[$i]["nombre"];
            $nombreHTML = $arrItemsPadre[$i]["titulo_html"];
            $target = $arrItemsPadre[$i]["target"];
            $ancho = $arrItemsPadre[$i]["ancho"];
            $alto = $arrItemsPadre[$i]["alto"];
            $linkExterno = $arrItemsPadre[$i]["link_externo"];
            $imagenMenu = $arrItemsPadre[$i]["imagen_menu"];
            $modulo = $arrItemsPadre[$i]["modulo"];
            $accion = $arrItemsPadre[$i]["accion"];
            $parametro = $arrItemsPadre[$i]["id"];
            $tipoMenu = $arrItemsPadre[$i]["id_tipo"];
            $id_tipo_padre = $arrItemsPadre[$i]["id_pagina_padre"];

                                    
            $urlLink = "admindex.php?".htmlentities("page=". $page);
            $urlLink = "javascript:;";
                                    
            //DETERMINAMOS SI HAY LINK EXTERNO      
            if ($tipoMenu == "externo" && $linkExterno != 'none')
                $urlLink = $linkExterno;    

            //DETERMINOS SI EL TARGET ES EN UN LIGHTBOX                
            if ($tipoMenu=="plugin"){
                $urlLink = "javascript:cargarContenido('mod=".$modulo."&action=".$accion."&".$parametro."','".$nombreHTML."','".$page."')"; 
                $target = "_self";
            }
            
            $textoLink = $nombre;
            //DETERMINAMOS SI HAY IMAGEN COMO LINK
            $imagenMenuHtml = "";
            if ($imagenMenu)
                $imagenMenuHtml = "<i class='fa ".$imagenMenu."' style='width:29px;'></i> ";
            
            $tieneHijos = false;
            $strClass = "";
            if ($this->tieneHijos($page,$_SESSION["itemsMenu"])){                              
                $tieneHijos = true;
                $strClass = "sub";
            }

            
             $strMenu .= "<li style='margin-right:-35px' id='menu_".$page."' class='list-header'><a href=\"".$urlLink."\" target='".$target."' title='".$nombreHTML."' data-title='".$nombreHTML."' style='padding-left:25px;' data-placement='right' class=''>".$imagenMenuHtml ."</a></li>\n";
           //$strMenu .= "<li id='menu_".$page."' class='list-header'><a href=\"".$urlLink."\" target='".$target."' title='".$nombreHTML."' data-title='".$nombreHTML."' style='padding-left:0px;' data-placement='right' class=''>".$imagenMenuHtml . $nombreHTML."</a></li>\n";            
                         
            //GENERAMOS MENU HIJOS
            if($tieneHijos == true){                
                $strMenu .= $this->generarMenu($_SESSION["itemsMenu"],$page, $nombreHTML); 
            }   else{
                $strMenu .= "<li id='menu_".$page."' class='list-header'><a href=\"".$urlLink."\" target='".$target."' title='".$nombreHTML."' data-title='".$nombreHTML."' style='padding-right:10px;' data-placement class=''>" .$nombreHTML."</a></li>\n";
                //$strMenu .= "<li id='menu_".$page."' class='list-header'><a href=\"".$urlLink."\" target='".$target."' title='".$nombreHTML."' data-title='".$nombreHTML."' style='padding-right:50px' data-placement' class=''>".$imagenMenuHtml . $nombreHTML."</a></li>\n";            
                //$strMenu .= "<li class='list-divider'></li>";
            }     
            $strMenu .= "<li class='list-divider'></li>";                                     
            
        }
            
        include("./modules/menu/templates/menu.php");       
    
    }
    
    /**
     * Funci�n para generar el menu a partir de un arreglo
     */
    function generarMenu($arrItemsMenu,$pagePadre, $tituloPadre) {
        
        if (count($arrItemsMenu)>0){

            $strDatos .= "<li class='dropdown administrador consulta'\n>";
                            $strDatos .= "<a id='themes' href='#' data-toggle='dropdown' class='dropdown-toggle' aria-expanded='false'>$tituloPadre<span class='caret'></span></a>\n";   
                            $strDatos .= "<ul aria-labelledby='themes' class='dropdown-menu'\n>";

            foreach ($arrItemsMenu as $key=>$value){

                if ($pagePadre==$key){
                    $arrSubItems = $value;
                    foreach ($arrSubItems as $key=>$value){
                        
                        $arrDataItem = explode("|",$value);
                        
                        $alias = $arrDataItem[0];
                        $nombre = $arrDataItem[1];
                        $imagenMenu = $arrDataItem[2];
                        $linkExterno = $arrDataItem[3];
                        $target = $arrDataItem[4];                  
                        $ancho = $arrDataItem[5];                   
                        $alto = $arrDataItem[6];                    
                        $tituloHtml = $arrDataItem[7];
                        $modulo = $arrDataItem[8];
                        $accion = $arrDataItem[9];
                        $parametro = $arrDataItem[10];
                        $tipoMenu = $arrDataItem[11];                        
                                            
                        $urlLink = "admindex.php?".htmlentities("page=". $alias);
                        $urlLink = "javascript:;";
                        
                        //DETERMINAMOS SI HAY LINK EXTERNO
                        if ($tipoMenu == "externo" && $linkExterno != 'none')
                            $urlLink = $linkExterno;    
                            
                        //DETERMINOS SI EL TARGET ES EN UN LIGHTBOX                        
                        if ($tipoMenu=="plugin"){
                            $urlLink = "javascript:cargarContenido('mod=".$modulo."&action=".$accion."&".$parametro."','".$tituloPadre . " / " .$tituloHtml."','".$alias."')"; 
                            $target = "_self";
                        }
                        
                        $textoLink = $nombre;
                        //DETERMINAMOS SI HAY IMAGEN COMO LINK
                        $$imagenMenu = "";
                        if ($imagenMenu)
                            $imagenMenu = "<i class='fa ".$imagenMenu."' style='width:29px;'></i> ";                                                          

                        if ($this->tieneHijos($arrDataItem[0],$arrItemsMenu)){                               
                            $strDatos .= "<li id='menu_".$alias."'><a href=\"".$urlLink."\" target='".$target."' title='".$nombreHtml."'>".$imagenMenu." <span class='menu-title'>".$tituloHtml."</span><i class='arrow'></i></a>\n";
                            $strDatos .= "<ul class='collapse' aria-expanded='false' style=''>\n";                      
                            $strDatos .= $this->generarMenu($arrItemsMenu,$arrDataItem[0], $tituloPadre);
                            $strDatos .= "</ul>\n";
                            $strDatos .= "</li>\n";
                        }
                        else{                                                                                                                                                  
                           $strDatos .= "<li id='menu_".$alias."'><a href=\"".$urlLink."\" target='".$target."' title='".$tituloHtml."' data-original-title='".$tituloHtml."'><span class='menu-title'>".$tituloHtml."</span></a>\n";
                            
                        }

                    }
                }
            }
             $strDatos .= "</ul>\n";
             $strDatos .= "</li>\n";
        }
        
        return $strDatos;
                
    }


    /**
     * Funci�n para generar el menu a partir de un arreglo
     */
    function generarCadenaHijos($arrItemsMenu,$pagePadre,$nombrePadre) {
        
        if (count($arrItemsMenu)>0){

            foreach ($arrItemsMenu as $key=>$value){

                if ($pagePadre==$key){
                    $arrSubItems = $value;
                    foreach ($arrSubItems as $key=>$value){                                     
                        $arrDataItem = explode("|",$value);                     
                        $alias = $arrDataItem[0];                   
                        $nombre = $arrDataItem[1];                      
                        if ($this->tieneHijos($arrDataItem[0],$arrItemsMenu)){                                  
                            $strDatos .= "$alias:$nombrePadre / $nombre#";
                            $strDatos .= $this->generarCadenaHijos($arrItemsMenu,$arrDataItem[0],$nombrePadre." / ".$nombre);
                        }
                        else
                            $strDatos .= "$alias:$nombrePadre / $nombre#";                      
                    }
                }
            }
        }
        
        return $strDatos;
                
    }
    
    /**
     * Funci�n para determinar si un papa tiene hijos
     */
    function tieneHijos($idPapa,$arrItemsMenu){
        //echo $idPapa."-";      
        if (count($arrItemsMenu)>0){
            foreach ($arrItemsMenu as $key=>$value){                
                if ($key==$idPapa)                                                     
                    return true;            
            }        
        }
        
        return false;               
    }


    
    /**
     * Funci�n para traer los items padre
     */
    function getItemsPadre($where="1=1", $joinPerfil = false) {
    
        global $db,$id,$action,$option,$option2,$appObj;
    
        //TRAEMOS LOS ITEMS PADRE
        $strSQL = "SELECT appa.alias, appa.nombre, appa.titulo_html, appa.imagen_menu, appa.link_externo, appa.target, appa.ancho, appa.alto, appa.modulo, appa.accion, appa.id, appa.id_tipo 
                   FROM app_paginas as appa ";
                   
        if ($joinPerfil){
            $strSQL .= " INNER JOIN perfil_permiso as pp ON appa.alias=pp.alias_accion";
        }
                   
        $strSQL .= " WHERE ".$where;
        
        if ($joinPerfil){
            $strSQL .= " AND pp.id_perfil=".$_SESSION["profile"];
        }
        
        $strSQL .= " ORDER BY appa.orden";

        $rsItems = $db->Execute($strSQL);
        $i=0;
        while (!$rsItems->EOF){
        
            $arrItemsPadre[$i]["alias"]=$rsItems->fields["alias"];
            $arrItemsPadre[$i]["nombre"]=$rsItems->fields["nombre"];
            $arrItemsPadre[$i]["titulo_html"]=$rsItems->fields["titulo_html"];
            $arrItemsPadre[$i]["imagen_menu"]=$rsItems->fields["imagen_menu"];
            $arrItemsPadre[$i]["link_externo"]=$rsItems->fields["link_externo"];
            $arrItemsPadre[$i]["target"]=$rsItems->fields["target"];
            $arrItemsPadre[$i]["ancho"]=$rsItems->fields["ancho"];
            $arrItemsPadre[$i]["alto"]=$rsItems->fields["alto"];
            $arrItemsPadre[$i]["modulo"]=$rsItems->fields["modulo"];
            $arrItemsPadre[$i]["accion"]=$rsItems->fields["accion"];
            $arrItemsPadre[$i]["id"]=$rsItems->fields["id"];
            $arrItemsPadre[$i]["id_tipo"]=$rsItems->fields["id_tipo"];
            $rsItems->MoveNext();
            $i++;
        }
        
        return $arrItemsPadre;
    }

    function crearArbol($tabla,$id_field,$show_data,$link_field,$parent,$where,$prefix,$order_by=""){
    
        global $db;     
    
        //EL PROCESO NO SE PUDO HACER CON ADODB POR QUE PRESENTABA PROBLEMAS.
    
        //ARMAMOS LA CONSULTA
        $sql="SELECT * FROM ".$tabla." WHERE ".$link_field."='".$parent . "'" . $where . " " . $order_by;
        $rs=$db->Execute($sql);
        if($rs){               
                
               while(!$rs->EOF){                    
                
                    //GUARDAMOS LOS DATOS EN UN ARREGLO DE SESSION  - Debe ir el perfil Administrador para la generacion de permisos por perfil                     
                    if (in_array($rs->fields["alias"], $_SESSION["permisos_perfil"]) || $_SESSION["profile_text"] == "Administrador"){
                        $_SESSION["itemsMenu"][$rs->fields["id_pagina_padre"]][]=$rs->fields["alias"]."|".$rs->fields["nombre"]."|".$rs->fields["imagen_menu"]."|".$rs->fields["link_externo"]."|".$rs->fields["target"]."|".$rs->fields["ancho"]."|".$rs->fields["alto"]."|".$rs->fields["titulo_html"]."|".$rs->fields["modulo"]."|".$rs->fields["accion"]."|".$rs->fields["id"]."|".$rs->fields["id_tipo"];
                    }

                    //LLAMAMOS LA FUNCION RECURSIVAMENTE
                    $this->crearArbol($tabla,$id_field,$show_data,$link_field,$rs->fields[$id_field],$where,$prefix.$prefix,$order_by);
                    $rs->MoveNext();
               }              
        }    
    }  
}

?>