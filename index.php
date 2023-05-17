<?php

/**********************************************************************************
                            INICIO DE LA APLICACION
*********************************************************************************/
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
    $loadLibraries->HTMInit();
    if (!$appObj->Lightbox)
        include("./master/header.php");
}

//IS HOME
if (!$appObj->mod){
    include_once("master/home.php");
}
else{

    $plug = $appObj->mod;

    if (file_exists("./modules/".$plug ."/class_".$plug .".php")){

        require_once ("./modules/".$plug ."/class_".$plug .".php");

        @$plugin = new $plug;
        $prueba = $plugin->parsePublic();
    }
    else
        include("./master/redirect.php");

}

if (!$appObj->Ajax && !$appObj->xml){
    if (!$appObj->Lightbox)
        include("./master/footer.php");
    $loadLibraries->HTMEnd();
}

$loadLibraries->CloseDB();

?>