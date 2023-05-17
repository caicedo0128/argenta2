<?php
/**
* Adminsitración del modulo estudioRiesgo
* @version 1.0
* El constructor de esta clase es {@link estudioRiesgo()}
*/
require_once("class_estudioRiesgo_extended.php");
class estudioRiesgo{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){
 
            case "estudio":
                            $this->estudio();
                            break;
            case "saveEstudio":
                            $this->saveEstudio();
                            break;
            case "listEstudios":
                            $this->listEstudios();
                            break;    
            case "eliminarEstudio":
                            $this->eliminarEstudio();
                            break; 
            case "imprimirEstudio":
                            $this->imprimirEstudio();
                            break;    
            case "saveCupo":
                            $this->saveCupo();
                            break;                               
        }
    } 
    
	/**
     * Funciòn para guardar informacion sobre el cupo
     */
    function saveCupo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./modules/clientes/class_clientes.php");
		
        //INSTANCIAMOS CLASES
        $estudio = new estudio_riesgo();
        $cliente = new clientes();
        
        $idEstudio = $_REQUEST["id_estudio"];

        $loadReg1 = $estudio->load("id_estudio=".$idEstudio);
        
        //OBTENEMOS DATOS DEL TERCERO
        $loadReg = $cliente->load("id_cliente=" . $estudio->id_tercero);
        
        $estudio->cupo = $_REQUEST['cupo']; 
        $nuevaObservacion = "<b>Fecha actualización:</b> ".date("Y-m-d H:i:s")."<br/>";
        $nuevaObservacion .= "<b>Usuario actualización: </b>".$_SESSION["user"]."<br/>";
        if ($_REQUEST['observaciones'] != ""){
            $nuevaObservacion .= "<b>Observación:</b><br/> ".$_REQUEST['observaciones'];
        }
        
        $estudio->observaciones = $estudio->observaciones . $nuevaObservacion . "<hr/>";
        $estudio->activo = 1;
        
        $estudio->Save();
        
        //ACTUALIZAMOS CUPO EN EL TERCERO
        $cliente->cupo = $estudio->cupo;
        $cliente->Save();
    
        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['IdEstudio'] = $estudio->id_estudio;
        $jsondata['Success'] = true;
        
        echo json_encode($jsondata);
        exit;
    }    
    
    /**
     * Funciòn para imprimir un estudio
     */
    function imprimirEstudio() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/modelos/class_modelos_extended.php");
        require_once("./modules/clientes/class_clientes.php");
        require_once("./utilities/pdf/tcpdf.php");

        $estudio = new estudio_riesgo();
        $modelo = new modelo_riesgo();
        $cliente = new clientes();

        $idEstudio = $_REQUEST["id_estudio"];
        $idTercero = $_REQUEST["id_tercero"];
        
        $loadReg = $estudio->load("id_estudio=".$idEstudio);
        
        //OBTENEMOS DATOS DEL TERCERO
        $loadReg = $cliente->load("id_cliente=" . $idTercero);
        
        include("./modules/estudioRiesgo/templates/estudio_imprimir.php");

    }    
    
    /**
     * Funciòn para obtener el listado de estudios
     */
    function listEstudios(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;
        
        require_once("./modules/clientes/class_clientes.php");
        
        //INSTANCIAMOS CLASES
        $estudio = new estudio_riesgo();
        $cliente = new clientes();
        
        $idTercero = $_REQUEST["id_tercero"];
        
        //OBTENEMOS DATOS DEL TERCERO
        $loadReg = $cliente->load("id_cliente=" . $idTercero);
                
        $rsEstudios = $estudio->getEstudiosPorTercero($idTercero);
               
        include("./modules/estudioRiesgo/templates/listado_estudios.php");
    }

    /**
     * Funciòn para guardar informacion estudio
     */
    function saveEstudio() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/modelos/class_modelos_extended.php");
        require_once("./modules/fieldsDynamic/class_fieldsDynamic_extended.php");

        //INSTANCIAMOS CLASES
        $campoClass = new campos();
        $modeloCampos = new modelo_campos();        
        $estudio = new estudio_riesgo();
        $camposInstancia = new campos_instancia();
        
        $idEstudio = $_REQUEST["id_estudio"];

        $loadReg1 = $estudio->load("id_estudio=".$idEstudio);

        $estudio->id_tercero = $_POST['id_tercero'];
        $estudio->anio = $_POST['anio'];   
        $estudio->id_modelo = $_POST['id_modelo']; 
        $estudio->tasa = 0; //NO SON NECESARIOS 
        $estudio->cupo = $_POST['cupo']; 
        $estudio->plazo = 0; //NO SON NECESARIOS 
        $estudio->corte_eeff = $_POST['corte_eeff']; 

        $nuevaObservacion = "<b>Fecha actualización:</b> ".date("Y-m-d H:i:s")."<br/>";
        $nuevaObservacion .= "<b>Usuario actualización: </b>".$_SESSION["user"]."<br/>";
        if ($_POST['observaciones'] != ""){
            $nuevaObservacion .= "<b>Observación:</b><br/> ".$_POST['observaciones'];
        }
        
        $estudio->observaciones = $estudio->observaciones . $nuevaObservacion . "<hr/>";
        $estudio->activo = 1;
        
        if ($idEstudio == 0)
            $estudio->fecha = date("Y-m-d");       

        $estudio->Save();
             
        //ELIMINAMOS LOS CAMPOS INSTANCIA DEL ESTUDIO
        $camposInstancia->eliminarCamposInstanciaEstudio($estudio->id_estudio);
       
        //OBTENEMOS LOS DATOS DEL MODELO PARA GUARDAR  LA INSTANCIA DE LOS CAMPOS
        if ($_REQUEST["campo_dinamico"] != ""){
        
            $arrCamposDinamicos = $_REQUEST["campo_dinamico"];

            foreach($arrCamposDinamicos as $key => $value){

                $idCampo = $key;
                $valor = $value;

                //INSERTAMOS LA INSTANCIA DE LOS CAMPOS
                $camposInstancia = new campos_instancia();
                $camposInstancia->load("id_instancia=0");
                $camposInstancia->id_estudio = $estudio->id_estudio;
                $camposInstancia->id_campo = $idCampo;
                $camposInstancia->valor = $valor;
                $camposInstancia->id_grupo = 0;
                $camposInstancia->Save();
            }
        }          
                
        //INSERTAMOS CAMPOS CALCULADOS
        
        //OBTENEMOS TODOS LOS CAMPOS CALCULADOS    
        $rsCamposCalculados = $modeloCampos->getCamposCalculadosPorModelo($estudio->id_modelo);

        //OBTENEMOS TODOS LOS CAMPOS DEL ESTUDIO    
        $arrCamposEstudio = $camposInstancia->getCamposPorEstudioCalculos($estudio->id_estudio); 
        
        $calculo = 0;
        //SE INCLUYE ESTE VALOR POR LOS WARNING DE DivisionByZero
        error_reporting(0);
        while (!$rsCamposCalculados->EOF)
        {     
            $calculo = 0;
            
            try{
                $formula = $rsCamposCalculados->fields["formula"];
                
                $formula = preg_replace('/\[/', '$arrCamposEstudio[', $formula);
                               
                eval('$calculo = (' . $formula . ');');
            }
			catch(DivisionByZeroError $e){
    			$calculo = "Error";
    		}
            catch(Exception $e){
                $calculo = "Error";
            }
            
            $arrCamposEstudio[$rsCamposCalculados->fields["campo"]] = $calculo;
            
            //INSERTAMOS LA INSTANCIA DE LOS CAMPOS
            $camposInstancia = new campos_instancia();
            $camposInstancia->load("id_instancia=0");
            $camposInstancia->id_estudio = $estudio->id_estudio;
            $camposInstancia->id_campo = $rsCamposCalculados->fields["id_campo"];
            $camposInstancia->valor = $calculo;
            $camposInstancia->id_grupo = 0;
            $camposInstancia->Save();            
            
            $rsCamposCalculados->MoveNext();
        }        
    
        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['IdEstudio'] = $estudio->id_estudio;
        $jsondata['Success'] = true;
        
        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver el formulario de registrar un estudio
     */
    function estudio() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./modules/modelos/class_modelos_extended.php");

        $estudio = new estudio_riesgo();
        $modelo = new modelo_riesgo();

        $idEstudio = $_REQUEST["id_estudio"];
        $idTercero = $_REQUEST["id_tercero"];
        
        $loadReg = $estudio->load("id_estudio=".$idEstudio);
        
        //OBTENER MODELOS
        $arrModelos = $modelo->obtenerModelos();

        include("./modules/estudioRiesgo/templates/estudio.php");

    }
    
    /**
     * Funciòn para eliminar estudio
     */
    function eliminarEstudio() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $estudio = new estudio_riesgo();
                
        $idEstudio = $_REQUEST["id_estudio"];

        //BORRAMOS LA INFORMACION DE CAMPOS DINAMICOS
        $strSQL = "DELETE FROM campos_instancia WHERE id_estudio=".$idEstudio;
        $db->Execute($strSQL);

        $loadReg1 = $estudio->load("id_estudio=".$idEstudio);

        $estudio->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
        
    }   
    
    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

        }
    }

}

?>
