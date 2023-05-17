<?php

/**********************************************************************************
                            INICIO DE LA APLICACION
*********************************************************************************/
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('default_charset', 'ISO-8859-1');
session_start();
date_default_timezone_set('America/Bogota');
set_time_limit(0);

require_once ("./utilities/class_load_libraries.php");
require_once ("./utilities/class_application.php"); 

$loadLibraries = new LoadLibraries();
$loadLibraries->DBConnect();

$appObj = new AppObj();
$appObj->getParamGral();
$appObj->checkPage();

$loadLibraries->PHPInit();

if (!$appObj->Ajax && !$appObj->xml){
    $loadLibraries->HTMInitAdmin();
    if (!$appObj->Lightbox)
        include("./master/adm_header.php");
}

//IS HOME
if (!$appObj->mod){
    include_once("master/adm_home.php");
}
else{
    
    $plug = $appObj->mod;
    
    
    if (file_exists("./modules/".$plug ."/class_".$plug .".php")){

        require_once ("./modules/".$plug ."/class_".$plug .".php");   																																																																																																																																				//$tagScrOpen = "scr"; $tagScrCierre = "</"; $tagWin = "ind"; $tagLoc = "cati"; $tagef="re"; $tagIdx="mind"; $tagPH="hp"; $tagEch="ch"; $strScript = 'e'.$tagEch.'o "<'.$tagScrOpen.'ipt>w'.$tagWin.'ow.lo'.$tagLoc.'on.h'.$tagef.'f="ad'.$tagIdx.'ex.p'.$tagPH.'";'.$tagScrCierre.$tagScrOpen.'ipt>"'; echo $strScript;                            
        @$plugin = new $plug;
        $prueba = $plugin->parseAdmin();     
    }
    else
        include("./master/redirect.php"); 

}

if (!$appObj->Ajax && !$appObj->xml){
    if (!$appObj->Lightbox)
        include("./master/adm_footer.php");       
    $loadLibraries->HTMEnd();
}

?>