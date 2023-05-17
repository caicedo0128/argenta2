<?php
/**
* Adminsitración de la pagina admin
* @version 1.0
* El constructor de esta clase es {@link admin()}
*/
class admin{

    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "listParamGral":
                $this->listParamGral();
                break;

            case "saveParam":
                $this->saveParam();
                break;
                
            case "listTextGral":
                $this->listTextGral();
                break;

            case "saveText":
                $this->saveText();
                break;                
        }
    }


    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "admin":
                $this->adminHome();
                break;                  
        }
    }

    function saveParam(){

        global $db,$id,$accion,$option,$option2,$appObj;                

        require_once("class_admin_extended.php");
        
        $param = new app_param_global();
        
        $totalParam = $_POST["total_param"];
        for($i=1;$i<=$totalParam;$i++){
            
            $id = $_POST["param_id_".$i];
            $val = $_POST["param_val_".$i];
            $reg = $param->load("id_parametro=".$id);
            $param->valor = $val;
            $param->Save();            
        }
        
        $jsondata['Success'] = true;    
        $jsondata['Message'] = $appObj->textGral["PROCESS_SUCCESSFULLY"];    
        echo json_encode($jsondata);        
        exit; 
    }

    function listParamGral(){

        global $db,$id,$accion,$option,$option2,$appObj;
        
        require_once("class_admin_extended.php");
        
        $param = new app_param_global();
        
        $rsParam = $param->getListParam();
        
        include("./modules/admin/templates/list_param.php");

    }
    
	function saveText(){

        global $db,$id,$accion,$option,$option2,$appObj;                

        require_once("class_admin_extended.php");
        
        $param = new app_texts();
        
        $totalParam = $_POST["total_param"];
        for($i=1;$i<=$totalParam;$i++){
            
            $id = $_POST["param_id_".$i];
            $val = $_POST["param_val_".$i];
            $reg = $param->load("id=".$id);
            $param->text = utf8_decode($val);
            $param->Save();            
        }
        
        $jsondata['Success'] = true;    
        $jsondata['Message'] = $appObj->textGral["PROCESS_SUCCESSFULLY"];    
        echo json_encode($jsondata);        
        exit; 
    }

    function listTextGral(){

        global $db,$id,$accion,$option,$option2,$appObj;
        
        require_once("class_admin_extended.php");
        
        $param = new app_texts();
        
        $rsParam = $param->getListParam();
        
        include("./modules/admin/templates/list_texts.php");

    }    

    function adminHome(){

        global $db,$id,$accion,$option,$option2,$appObj;
        
        include("./modules/admin/templates/admin.php");

    }
    
    function getMenu(){
    
        global $db,$id,$accion,$option,$option2,$appObj;

        include("./modules/admin/templates/menu.php");
    
    }
       
}

?>
