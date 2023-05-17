<?php

function redireccion($pagina='index.php'){
    
    echo "<script type='text/javascript'>";
    echo "location.href='".$pagina."'";
    echo "</script>";
}

function date_diff_custom($date1, $date2)
{
    $s = strtotime($date2)-strtotime($date1);
    $d = intval($s/86400);
    $s -= $d*86400;
    $h = intval($s/3600);
    $s -= $h*3600;
    $m = intval($s/60);
    $s -= $m*60;
    return array("d"=>$d,"h"=>$h,"m"=>$m,"s"=>$s);
}

function restar_dias_fecha($fecha,$dias){
    $nuevaFecha = date("Y-m-d", mktime (0,0,0,date("m",strtotime($fecha)),date("d",strtotime($fecha))-$dias,date("Y",strtotime($fecha))));
    return $nuevaFecha;
}

function sumar_dias_fecha($fecha,$dias){
    $nuevaFecha = date("Y-m-d", mktime (0,0,0,date("m",strtotime($fecha)),date("d",strtotime($fecha))+$dias,date("Y",strtotime($fecha))));
    return $nuevaFecha;
}

function sumar_minutos_fecha($fecha,$minutos){
    $nuevaFecha = date("Y-m-d H:i:s", strtotime($fecha)+(60*$minutos));
    return $nuevaFecha;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!deleteDirectory($dir . "/" . $item)) return false;
            };
        }
        return rmdir($dir);
    } 

function readDirectory($dir, $arrFiles = array()) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) return true;
        foreach (scandir($dir) as $item) {
            $arrFiles[] =  $item;
            if ($item == '.' || $item == '..') continue;
            if (!readDirectory($dir . "/" . $item, $arrFiles)) {
                $arrFiles[] =  $item;
                if (!readDirectory($dir . "/" . $item, $arrFiles)) return false;
            };
        }
        return $arrFiles;
    } 
    
function formato_moneda($valor, $format = "$"){

    return $format . number_format((double)$valor,0,",",".");

}

function generarAleatorio(){

    $strNumero = rand (0, 9);
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
    $cad = str_shuffle($str);
    $cad= substr($cad,0,7).$strNumero;    
    $clave= str_shuffle($cad);
    return $clave;
}

function fecha_fin_de_semana($fecha){

    $dia = date("w",strtotime($fecha));
    $finDeSemana = restar_dias_fecha($fecha,$dia);

    return $finDeSemana;
}

?>