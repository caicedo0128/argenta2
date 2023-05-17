<?php
/**
* Adminsitración del modulo reportes
* @version 1.0
* El constructor de esta clase es {@link reportes()}
*/
require_once("class_flujo_extended.php");
class flujo{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$idDeclaratoria,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "flujoCaja":
                            $this->flujoCaja();
                            break;
            case "guardarFlujoCaja":
                            $this->guardarFlujoCaja();
                            break;
			case "guardarFlujoCajaDetalle":
                            $this->guardarFlujoCajaDetalle();
                            break;    
			case "eliminarFlujoCajaDetalle":
                            $this->eliminarFlujoCajaDetalle();
                            break;                                    
            case "guardarSoporte":
                            $this->guardarSoporte();
                            break;  
                            
        }
    }  
    
    /**
      * Funciòn para mostrar el flujo de caja en el home 
      */
    function flujoCajaHome() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        $flujoCaja = new flujo_caja();

        //OBTENEMOS INFORMACION FLUJOS DE CAJA      
        $rsFlujosCaja = $flujoCaja->obtenerUltimosFlujosCaja(10);

        include("./modules/flujo/templates/flujo_caja_home.php");
    }     
    
    /**
      * Funciòn para guardar soporte de flujo de caja 
      */
    function guardarSoporte() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS       
        $flujoCaja = new flujo_caja();
        
        $idFlujoCaja = $_POST["id_flujo_caja"];
        
        $loadReg1 = $flujoCaja->load("id_flujo_caja=".$idFlujoCaja);       
        $flujoCaja->soporte=utf8_decode($_POST["soporte"]);
        $flujoCaja->fecha_actualizacion_soporte=date("Y-m-d H:i:s");
        $flujoCaja->Save();
        
        $jsondata['Message'] = utf8_encode("Transacción exitosa. Espere por favor...");
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }      
    
    /**
      * Funciòn para guardar el de flujo de caja detalle
      */
    function eliminarFlujoCajaDetalle() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS       
        $flujoCajaDetalle = new flujo_caja_detalle();
        
        $idFlujoCajaDetalle =  $_POST["id_flujo_caja_detalle"];
        
        $loadReg1 = $flujoCajaDetalle->load("id_flujo_caja_detalle=".$idFlujoCajaDetalle);       
        $flujoCajaDetalle->Delete();
        
        $jsondata['Message'] = utf8_encode("Transacción exitosa. Espere por favor...");
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }     
    
    /**
      * Funciòn para guardar el de flujo de caja detalle
      */
    function guardarFlujoCajaDetalle() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS       
        $flujoCajaDetalle = new flujo_caja_detalle();
               
        $loadReg1 = $flujoCajaDetalle->load("id_flujo_caja_detalle=0");
        
        $flujoCajaDetalle->id_flujo_caja = $_POST["id_flujo_caja"];
        $flujoCajaDetalle->tercero = $_POST["tercero"];
        $flujoCajaDetalle->valor = $_POST["valor"];       
        $flujoCajaDetalle->Save();
        
        $jsondata['Message'] = utf8_encode("Transacción exitosa. Espere por favor...");
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }     
    
    /**
      * Funciòn para guardar el de flujo de caja
      */
    function guardarFlujoCaja() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS       
        $flujoCaja = new flujo_caja();
        
        $idFlujoCaja = $_POST["id_flujo_caja"];
        if ($idFlujoCaja == "")
        	$idFlujoCaja = 0;
               
        $loadReg1 = $flujoCaja->load("id_flujo_caja=".$idFlujoCaja);
        
        $flujoCaja->fecha = $_POST["fecha"];
        $flujoCaja->cuenta1 = $_POST["cuenta1"];
        $flujoCaja->cuenta2 = $_POST["cuenta2"];
        $flujoCaja->cuenta3 = $_POST["cuenta3"];
        $flujoCaja->cuenta4 = 0;
        $flujoCaja->usuario_actualiza = $_SESSION["user"];
        $flujoCaja->fecha_actualizacion = date("Y-m-d H:i:s");
        $flujoCaja->Save();
        
        $jsondata['Message'] = utf8_encode("Transacción exitosa. Espere por favor...");
        $jsondata['IdFlujo'] = $flujoCaja->id_flujo_caja;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }    

    /**
      * Funciòn para ver el reporte de flujo de caja
      */
    function flujoCaja() {

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./modules/operaciones/class_operaciones.php");
        
        $flujoCaja = new flujo_caja();
        $flujoCajaDetalle = new flujo_caja_detalle();

        $fechaActual = date("Y-m-d");
        $fechaFacturasVencidas = fecha_fin_de_semana($fechaActual);
        $fechaIniProximosVencimientos = sumar_dias_fecha($fechaFacturasVencidas,1); //ES EL LUNES
        $fechaFinProximosVencimientos = sumar_dias_fecha($fechaFacturasVencidas,7); //ES EL DOMINGO
        
        
        $loadReg1 = $flujoCaja->load("fecha='".$fechaActual."'");
        
        //OBTENEMOS INFORMACION OPERACIONES-FACATURAS VENCIDAS         
        $rsFacturasVencidas = $flujoCaja->obtenerInformacionFacturasVencidas($fechaFacturasVencidas);
        
		//OBTENEMOS INFORMACION PROXIMOS VENCIMIENTOS
        $rsProximosVencimientos = $flujoCaja->obtenerInformacionProximoVencimientos($fechaIniProximosVencimientos, $fechaFinProximosVencimientos);   
        
        //OBTENEMOS INFORMACION FLUJO DETALLE
        $rsPagosAdministrativos = $flujoCajaDetalle->obtenerInformacionFlujoDetalle($flujoCaja->id_flujo_caja);  
        
		//OBTENEMOS INFORMACION OPERACIONES PROGRAMADAS
        $rsOperacionesProgramadas = $flujoCaja->obtenerInformacionOperacionesProgramadas();    
                
		//OBTENEMOS INFORMACION REMANENTES
		$fechaInicialRemanentes = restar_dias_fecha($fechaActual,0);
        $rsRemanentes = $flujoCaja->obtenerInformacionRemanentes($fechaInicialRemanentes, $fechaActual);           
               

        include("./modules/flujo/templates/flujo_caja.php");
    }
	   

    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$idDeclaratoria,$action,$option,$option2,$appObj;

        switch($appObj->action){

        }
    }

}
?>