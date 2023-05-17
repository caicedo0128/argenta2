<?php

class sendMail{

    function enviarMail($fromName = "", $fromEmail = "", $toNameMail = "",$toEmail = "",$subjectMail = "",$template = "", $arrAttach = array(), $arrVarsReplace = array()){    

        global $db,$appObj;        

        require_once("./utilities/PHPMailerAutoload.php");
        
        $mail = new PHPMailer(); 
        $mail->setFrom($fromEmail, $fromName);
        $mail->Subject = $subjectMail;
        
        //VALIDACION DE ARCHIVOS ADJUNTOS
        if (Count($arrAttach) > 0){
            foreach ($arrAttach as $key=>$valueArr){
                $fileAttach = $key;
                $mail->AddAttachment(__DIR__ . '/../gallery/' . $fileAttach);
            }     
        }
        
        //ENVIO A VARIOS CORREOS
        $toEmailArray = explode(';', $toEmail);
        $toNameEmailArray = explode(';', $toNameMail);
        $i=0;
        foreach($toEmailArray as $address)
        {
            $nameEmail = $toNameEmailArray[$i];
            $mail->addAddress($address, $nameEmail);
            $i++;
        }          
        
        //VALIDACION DE PLANTILLA DE CORREO
        $msjHtml = "Sin plantilla de correo";
        if ($template != ""){
            
            $msjHtml = file_get_contents(__DIR__ . '/../mailings/' . $template . '.htm', FILE_USE_INCLUDE_PATH);

            if(Count($arrVarsReplace) > 0){
            
                $arrVarsReplaceTemp1 = array();
                $arrVarsReplaceTemp2 = array();
                $arrVarsReplaceTemp1[] = "{SERVER}";
                $arrVarsReplaceTemp2[] = $appObj->paramGral["URL_PAGE"];
                
                foreach ($arrVarsReplace as $key=>$valueVar){
                    $arrVarsReplaceTemp1[] = "{" . $key . "}";
                    $arrVarsReplaceTemp2[] = $valueVar;
                } 
                
                $msjHtml = str_replace($arrVarsReplaceTemp1, $arrVarsReplaceTemp2, $msjHtml);
            }              
        }
        
        $mail->IsHTML(true);        
        $mail->msgHTML($msjHtml);
        
        $success = true;
        if(!$mail->Send()) {
            echo $mail->ErrorInfo;
            $success = false;
        } 

        return $success;
    }   

}

?>