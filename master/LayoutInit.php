<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$appObj->paramGral["TITLE_PAGE"]?></title>
    <link href="./css/bootstrap.min.css" rel="stylesheet"/>
    <link href="./css/dataTables.bootstrap.css" rel="stylesheet"/>
    <link href="./css/jquery.jNotify.css" rel="stylesheet"/>
    <link href="./css/font-awesome.min.css" rel="stylesheet"/>
    <link href="./css/bootstrap-datetimepicker.css" rel="stylesheet"/>
    <link href="./css/responsive.bootstrap.min.css" rel="stylesheet"/>
    <link href="./css/metis_menu.css" rel="stylesheet"/>
    <link href="./css/jquery.select2.css" rel="stylesheet"/>
    <link href="./css/pivot.css" rel="stylesheet"/>
    <link href="./css/Site.css" rel="stylesheet"/>
    <script src="./js/modernizr-2.6.2.js"></script>
    <script src="./js/jquery-1.10.2.min.js"></script>   
    
    <?php
        if ($_SESSION["login"]){        
            $expiresSession = sumar_minutos_fecha(date("Y-m-d H:i:s"),30);
            $newDate = strtotime($expiresSession);      
    ?>      
            <script type="text/javascript">
                var utcTimeExpireSession = null;
                $(document).ready(function(){
                    utcTimeExpireSession = new Date(<?=date("Y", $newDate)?>,<?=date("m", $newDate)-1?>,<?=date("d", $newDate)?>,<?=date("H", $newDate)?>,<?=date("i", $newDate)?>);                    
                });
                
            </script>
            <script src="./js/validate_activity.js"></script>  
    <?php
        }
    ?>     
    
</head>
<body id="bod-sis" class="<?=($_SESSION["login"] && $appObj->mod?"":($appObj->action=="vinculacion"?"":"login"))?>">