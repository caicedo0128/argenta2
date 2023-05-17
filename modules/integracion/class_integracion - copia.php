<?php
/**
* Adminsitración del modulo integracion
* @version 1.0
* El constructor de esta clase es {@link integracion()}
*/
require_once("class_integracion_extended.php");
class integracion{


    var $Database;
    var $ID;
    var $arrEstadosTransmisionFacturas = array("1"=>"INSCRIPCIÓN","2"=>"MANDATO","3"=>"ENDOSO","4"=>"INFORME PAGO","5"=>"PAGO");
    var $token;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj; 

        switch($appObj->action){
        
            case "confirmacionTransmitirFactura":
                            $this->confirmacionTransmitirFactura();
                            break;
            case "transmitirFactura":
                            $this->transmitirFactura();
                            break;
            case "verLogTransmision":
                            $this->verLogTransmision();
                            break;                            
    	}
    }
    
 	/**
     * Funcionn para ver el log de transmision
     */
    function verLogTransmision(){

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
		$factura = new operacion_factura();  
		
		$idFactura = $_REQUEST["id_factura"];
		$tipoProceso = $_REQUEST["tipo_proceso"];
		
        $loadReg1 = $factura->load("id_operacion_factura=".$idFactura); 

        include("./modules/integracion/templates/ver_detalle_log.php");
    }      
    
	/**
     * Funciòn para transmitir una factura a RADIAN
     */
    function transmitirFactura() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $success = true;
        $msj = "";
        $idFactura = $_REQUEST["id_factura"];
        $tipoProceso = $_REQUEST["tipo_proceso"];
        $idEstadoTransmision = 0;
        
        //OBTENEMOS EL TOKEN PARA HACER CONSUMO
        $respuesta = $this->consultarToken();

        //SI HAY UN TOKEN VALIDO SE CONSUME EL SERVICIO
        if ($respuesta["Success"]){
       
        	$this->token = $respuesta["Token"];        
        	$idEstadoTransmision = $tipoProceso;
        	
        	//INSCRIPCION
        	if ($tipoProceso == 1){
        		$respuestaTransmision = $this->inscribirFactura($idFactura);
        	}
        	//MANDATO
			else if ($tipoProceso == 2){
        		$respuestaTransmision = $this->mandatoFactura($idFactura);
        	}    
        	//ENDOSO
			else if ($tipoProceso == 3){
        		$respuestaTransmision = $this->endosoFactura($idFactura);
        	}     
        	//INFORME PARA PAGO
			else if ($tipoProceso == 4){
        		$respuestaTransmision = $this->informePagoFactura($idFactura);
        	}  
        	//PAGO
			else if ($tipoProceso == 5){
        		$respuestaTransmision = $this->pagoFactura($idFactura);
        	}         	

        }
        else{
        	$respuestaTransmision["Msg"] = "No se pudo consultar el token en Dispapeles. Error:" . $respuesta["Msj"];
        }

        echo json_encode($respuestaTransmision);
        exit;
    } 

 	/**
     * Funcionn para ver el formulario de transmision de facturas
     */
    function confirmacionTransmitirFactura(){

        global $d,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
		$factura = new operacion_factura();  
		
		$idFactura = $_REQUEST["id_factura"];
		$tipo = $_REQUEST["tipo_proceso"];
		
        $loadReg1 = $factura->load("id_operacion_factura=".$idFactura); 

        include("./modules/integracion/templates/transmitir.php");
    }        
    
	/**
     * Funciòn para crear evento inscribir factura
     */
    function inscribirFactura($idFactura = 0) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./utilities/class_web_service.php");

        //INSTANCIAMOS CLASES
        $ws = new WebService();                
        $operacion = new operacion();
        $cliente = new clientes();
        $operacionFactura = new operacion_factura();

        $success = true;
        $msj = "";
		$respuesta = "";
        $urlServicio = $appObj->paramGral["WSCrearEvento"];
        $idEmpresa = $appObj->paramGral["IdEmpresaDispapeles"];
		$respuestaService = array();        
		
        $loadReg1 = $operacionFactura->load("id_operacion_factura=".$idFactura); 
        $loadReg2 = $operacion->load("id_operacion=".$operacionFactura->id_operacion); 
        $loadReg3 = $cliente->load("id_cliente=".$operacion->id_emisor); 
        
        $nombreEmisor = "Prodispel"; //ucfirst(strtolower($cliente->razon_social));
        $identificacion = "817000707"; //$cliente->identificacion;
        $digitoVerificacion = "2"; //$cliente->digito_verificacion;
        
        $notas[] = $nombreEmisor . " OBRANDO EN NOMBRE Y REPRESENTACION DE Dispapeles";
        
        //ARMAMOS MENSAJE        
		$parametersService = array(
			"idEmpresa"=>$idEmpresa,
			"token"=>$this->token,
			"id"=>$this->genIDService($idFactura),
			"codigoEvento"=>"036",
			"tipoOperacion"=>"361",
			"referenciaTipoOperacion"=>"",
			"archivosAdjuntos"=>array(),
			"notas"=> $notas,
			"documentosReferenciado"=>
				array(
					"consecutivo"=>$operacionFactura->prefijo.$operacionFactura->num_factura,
					"cufeCude"=>$operacionFactura->cufe,
					"tipoDocumento"=>"01",
					"fechaVencimiento"=>$operacionFactura->fecha_vencimiento,
					"deudores"=>array()
				),
			"generadorEvento"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> $identificacion,
					"nombreCompleto"=> $nombreEmisor,
					"tipoPersona"=> "1",
					"digitoVerificacion"=> $digitoVerificacion,
					"monto"=> 0.0,
					"representanteLegal"=>array(),
					"tipoPortal"=>0
				),
			"mandante"=>array(),
			"mandatario"=>array(),
			"informacionCampoValor"=> 
				array
				(
					array
					(
						"nombre"=>"ValorFEV-TV",
						"valor"=>$operacionFactura->valor_neto,
						"numeric"=>true,
						"disabled"=>true
					),
					array
					(
						"nombre"=>"ValorPagado",
						"valor"=>"0",
						"numeric"=>true,
						"disabled"=>false
					),
					array
					(
						"nombre"=>"NuevoValorTV",
						"valor"=>$operacionFactura->valor_neto,
						"numeric"=>true,
						"disabled"=>true
					)	
				)			 
		);     	
		        
        $respuesta = null;
        try
        {        
			$respuesta = $ws->callPOST($parametersService, $urlServicio);

			if (is_object($respuesta)){
				$codigoRespuesta = $respuesta->codigoRespuesta;				
				if ($codigoRespuesta == 200){
					$respuestaService["Success"] = true;
					$respuestaService["Response"] = $respuesta->objeto;				
					$respuestaService["Msj"] = "Transaccion exitosa";						
				}
				else{
					$descripcion = $respuesta->descripcion . $respuesta->message;					
					$respuestaService["Success"] = false;
					$respuestaService["Response"] = $respuesta->validaciones;			
					$respuestaService["Msj"] = $descripcion;				
				}
			}
		}
        catch(exception $e){
			$respuestaService["Success"] = false;
			$respuestaService["Response"] = "";			
			$respuestaService["Msj"] = "Error al consumir WS - Class Integracion InscribirFactura";        
        }
        
		//ACTUALIZAMOS LA FACTURA CON LA RESPUESTA DEL SERVICIO        
		$operacionFactura->id_estado_transmision = 1; //ESTADO INSCRIPCION
		$msjTransmision = "<hr/>Fecha:". date("Y-m-d H:i:s") . " - Usuario:".$_SESSION["user"]."<br/>Respuesta:". json_encode($respuestaService);
		$operacionFactura->msj_inscripcion = $msjTransmision . $operacionFactura->msj_inscripcion;
		$operacionFactura->id_estado_inscripcion = ($respuestaService["Success"]==true?1:2);
		$operacionFactura->Save();        
        
        return $respuestaService;
    }  
    
	/**
     * Funciòn para crear evento mandato
     */
    function mandatoFactura($idFactura = 0) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./utilities/class_web_service.php");

        //INSTANCIAMOS CLASES
        $ws = new WebService();                
        $operacion = new operacion();
        $cliente = new clientes();
        $operacionFactura = new operacion_factura();

        $success = true;
        $msj = "";
		$respuesta = "";
        $urlServicio = $appObj->paramGral["WSCrearEvento"];
        $idEmpresa = $appObj->paramGral["IdEmpresaDispapeles"];
		$respuestaService = array();        
		
        $loadReg1 = $operacionFactura->load("id_operacion_factura=".$idFactura); 
        $loadReg2 = $operacion->load("id_operacion=".$operacionFactura->id_operacion); 
        $loadReg3 = $cliente->load("id_cliente=".$operacion->id_emisor); 
        
        $nombreEmisor = "Prodispel"; //ucfirst(strtolower($cliente->razon_social));
        $identificacion = "817000707"; //$cliente->identificacion;
        $digitoVerificacion = "2"; //$cliente->digito_verificacion;
        
        $nota1 = "xxxxxxxxxxxxx OBRANDO EN NOMBRE Y REPRESENTACION DE xxxxxxxxxxxxx";
        $nota2 = "xxxxxxxxxxx, identificado con la cedula de ciudadania No. xxxxxxxxxxxxxxxxxx, expresamente manifiesto que obro en nombre y representacion de xxxxxxxxx, de conformidad con el contrato de mandato escrito existente entre las partes y con las facultades senaladas en el presente documento y por el tiempo consignado en este."; 
        $nota3 = "xxxxxxxxxxx, identificado con la cedula de ciudadania No. xxxxxxxxxxx, en mi calidad de representante legal de la sociedad xxxxxx, segun consta en el certificado de existencia y representacion legal expedido por la Camara de Comercio de Bogota, expresamente manifiesto que obro en nombre y representacion de xxxxxxxx, de conformidad con el contrato de mandato escrito existente entre las partes y con las facultades senaladas en el presente documento y por el tiempo consignado en este."; 
        
        //ARMAMOS MENSAJE        
		$parametersService = array(
			"idEmpresa"=>$idEmpresa,
			"token"=>$this->token,
			"id"=>$this->genIDService($idFactura),
			"codigoEvento"=>"043",
			"tipoOperacion"=>"432",
			"referenciaTipoOperacion"=>"1",
			"codigoCantidadDocumentos"=>"1",			
			"archivosAdjuntos"=>array(),
			"facultades"=>"ALL17-PT",
			"descripcionFacultades"=>"Mandato por documento General",			
			"notas"=> [$nota1,$nota2,$nota3],
			"fechaActuarMandatario"=>date("Y-m-d"),
			"documentosReferenciado"=>
				array(
					"consecutivo"=>$operacionFactura->prefijo.$operacionFactura->num_factura,
					"cufeCude"=>$operacionFactura->cufe,
					"tipoDocumento"=>"01",
					"codigoTiempoMandato"=>"2",
					"descripcionEvento"=>"Lapso de vigencia del Mandato-Ilimitado",
					"deudores"=>array()
				),
			"generadorEvento"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> $identificacion,
					"nombreCompleto"=> $nombreEmisor,
					"tipoPersona"=> "1",
					"tipoFactor"=>"Mandante-FE",
					"descripcionFactor"=>"Mandante Facturador Electronico",					
					"digitoVerificacion"=> $digitoVerificacion,
					"monto"=> 0.0,
					"representanteLegal"=>
						array
						(
							"tipoDocumento"=>"13",
							"numeroDocumento"=>"123456789",
							"nombres"=>"Pepito",
							"apellidos"=>"Perez",
							"cargo"=>"Analista de Pruebas",
							"nacionalidad"=>"Colombia",
							"area"=>"Colaboracion Electronica"
						)					
				),
			"mandante"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> $identificacion,
					"nombreCompleto"=> $nombreEmisor,
					"tipoPersona"=> "1",
					"tipoFactor"=>"Mandante-FE",
					"descripcionFactor"=>"Mandante Facturador Electronico",					
					"digitoVerificacion"=> $digitoVerificacion,
					"monto"=> 0.0,
					"representanteLegal"=>array()
				),			
			"mandatario"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> "860028580",
					"nombreCompleto"=> "Dispapeles sas",
					"tipoPersona"=> "1",
					"tipoFactor"=>"M-PT",
					"descripcionFactor"=>"Mandatario Proveedor Tecnologico",				
					"digitoVerificacion"=>"2",
					"monto"=> 0.0,
					"representanteLegal"=>
						array
						(
							"tipoDocumento"=>"13",
							"numeroDocumento"=>"9876533214",
							"nombres"=>"Pepito",
							"apellidos"=>"Pineda",
							"cargo"=>"Analista de Pruebas",
							"nacionalidad"=>"Colombia",
							"area"=>"Colaboracion Electronica"
						)						
				),			
			"informacionCampoValor"=>array() 
		);     			
		
        $respuesta = null;
        try
        {        
			$respuesta = $ws->callPOST($parametersService, $urlServicio);

			if (is_object($respuesta)){
				$codigoRespuesta = $respuesta->codigoRespuesta;				
				if ($codigoRespuesta == 200){
					$respuestaService["Success"] = true;
					$respuestaService["Response"] = $respuesta->objeto;				
					$respuestaService["Msj"] = "Transaccion exitosa";						
				}
				else{
					$descripcion = $respuesta->descripcion . $respuesta->message;					
					$respuestaService["Success"] = false;
					$respuestaService["Response"] = $respuesta->validaciones;			
					$respuestaService["Message"] = json_encode($parametersService);
					$respuestaService["Msj"] = $descripcion;				
				}
			}
		}
        catch(exception $e){
			$respuestaService["Success"] = false;
			$respuestaService["Response"] = "";			
			$respuestaService["Msj"] = "Error al consumir WS - Class Integracion MandatoFactura";        
        }
        
		//ACTUALIZAMOS LA FACTURA CON LA RESPUESTA DEL SERVICIO        
		$operacionFactura->id_estado_transmision = 2; //ESTADO MANDATO
		$msjTransmision = "<hr/>Fecha:". date("Y-m-d H:i:s") . " - Usuario:".$_SESSION["user"]."<br/>Respuesta:". json_encode($respuestaService);
		$operacionFactura->msj_mandato = $msjTransmision . $operacionFactura->msj_mandato;
		$operacionFactura->id_estado_mandato = ($respuestaService["Success"]==true?1:2);
		$operacionFactura->Save();        
        
        return $respuestaService;
    }      
        
	/**
     * Funciòn para crear evento endoso
     */
    function endosoFactura($idFactura = 0) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./utilities/class_web_service.php");

        //INSTANCIAMOS CLASES
        $ws = new WebService();                
        $operacion = new operacion();
        $cliente = new clientes();
        $pagador = new clientes();
        $operacionFactura = new operacion_factura();

        $success = true;
        $msj = "";
		$respuesta = "";
        $urlServicio = $appObj->paramGral["WSCrearEvento"];
        $idEmpresa = $appObj->paramGral["IdEmpresaDispapeles"];
		$respuestaService = array();        
		
        $loadReg1 = $operacionFactura->load("id_operacion_factura=".$idFactura); 
        $loadReg2 = $operacion->load("id_operacion=".$operacionFactura->id_operacion); 
        $loadReg3 = $cliente->load("id_cliente=".$operacion->id_emisor); 
        $loadReg4 = $pagador->load("id_cliente=".$operacion->id_pagador); 
        
        $nombreEmisor = "Prodispel"; //ucfirst(strtolower($cliente->razon_social));
        $identificacion = "817000707"; //$cliente->identificacion;
        $digitoVerificacion = "2"; //$cliente->digito_verificacion;
        
        $nombrePagador = "Dispapeles sas"; //ucfirst(strtolower($pagador->razon_social));
        $identificacionPagador = "860028580"; //$pagador->identificacion;
        $digitoVerificacionPagador = "2"; //$pagador->digito_verificacion; 
        
		$nota1 = "Andres OBRANDO EN NOMBRE Y REPRESENTACION DE Prodispel";        
        $nota2 = "sin mi responsabilidad u otra equivalente"; //validar con o sin depende de condiciones
        
        //ARMAMOS MENSAJE        
		$parametersService = array(
			"idEmpresa"=>$idEmpresa,
			"token"=>$this->token,
			"id"=>$this->genIDService($idFactura),
			"codigoEvento"=>"037",
			"tipoOperacion"=>"372",
			"referenciaTipoOperacion"=>"1",
			"moneda"=>"COP",			
			"archivosAdjuntos"=>array(),		
			"notas"=> [$nota1,$nota2],
			"documentosReferenciado"=>
				array(
					"consecutivo"=>$operacionFactura->prefijo.$operacionFactura->num_factura,
					"cufeCude"=>$operacionFactura->cufe,
					"tipoDocumento"=>"01",
					"fechaVencimiento"=>$operacionFactura->fecha_vencimiento,
					"deudores"=>
						array
						(
							"tipoDocumento"=> "31",
							"numeroDocumento"=> $identificacionPagador,
							"nombreCompleto"=> $nombrePagador,
							"tipoPersona"=> "1",
							"digitoVerificacion"=> $digitoVerificacionPagador,
							"monto"=> 0.0,
							"representanteLegal"=>array(),
							"tipoPortal"=>0
						)
				),
			"generadorEvento"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> $identificacion,
					"nombreCompleto"=> $nombreEmisor,
					"tipoPersona"=> "1",
					"digitoVerificacion"=> $digitoVerificacion,
					"monto"=> 1452457.63, //PrecioPagarseFEV 1130622 Valor giro final:
					"representanteLegal"=>array()
				),
			"mandante"=>array(),		
			"mandatario"=> //Argenta
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> "900518469",
					"nombreCompleto"=> "ARGENTA ESTRUCTURADORES SAS",
					"tipoPersona"=> "1",				
					"digitoVerificacion"=>"1",
					"monto"=> 11713.37,//333549 //Diferencia entre Valor neto y Valor giro final:
					"representanteLegal"=>array(),
					"tipoPortal"=>0						
				),			
			"informacionCampoValor"=> 
				array
				(
					array
					(
						"nombre"=>"ValorTotalEndoso",
						"valor"=>1464171.00,
						"numeric"=>true,
						"disabled"=>false
					),
					array
					(
						"nombre"=>"PrecioPagarseFEV",
						"valor"=>1452457.63,
						"numeric"=>true,
						"disabled"=>false
					),
					array
					(
						"nombre"=>"TasaDescuento",
						"valor"=>0.80,//% descuento operacion
						"numeric"=>true,
						"disabled"=>false
					),	
					array
					(
						"nombre"=>"MedioPago",
						"valor"=>"47",
						"numeric"=>false,
						"disabled"=>false
					)					
				)
		);     			
		
        $respuesta = null;
        try
        {        
			$respuesta = $ws->callPOST($parametersService, $urlServicio);

			if (is_object($respuesta)){
				$codigoRespuesta = $respuesta->codigoRespuesta;				
				if ($codigoRespuesta == 200){
					$respuestaService["Success"] = true;
					$respuestaService["Response"] = $respuesta->objeto;				
					$respuestaService["Msj"] = "Transaccion exitosa";						
				}
				else{
					$descripcion = $respuesta->descripcion . $respuesta->message;					
					$respuestaService["Success"] = false;
					$respuestaService["Response"] = $respuesta->validaciones;
					$respuestaService["Message"] = json_encode($parametersService);
					$respuestaService["Msj"] = $descripcion;				
				}
			}
		}
        catch(exception $e){
			$respuestaService["Success"] = false;
			$respuestaService["Response"] = "";			
			$respuestaService["Msj"] = "Error al consumir WS - Class Integracion EndosoFactura";        
        }
        
		//ACTUALIZAMOS LA FACTURA CON LA RESPUESTA DEL SERVICIO        
		$operacionFactura->id_estado_transmision = 3; //ESTADO ENDOSO
		$msjTransmision = "<hr/>Fecha:". date("Y-m-d H:i:s") . " - Usuario:".$_SESSION["user"]."<br/>Respuesta:". json_encode($respuestaService);
		$operacionFactura->msj_endoso = $msjTransmision . $operacionFactura->msj_endoso;
		$operacionFactura->id_estado_endoso = ($respuestaService["Success"]==true?1:2);
		$operacionFactura->Save();        
        
        return $respuestaService;
    }        
    
	/**
     * Funciòn para crear evento inscribir factura
     */
    function informePagoFactura($idFactura = 0) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./utilities/class_web_service.php");

        //INSTANCIAMOS CLASES
        $ws = new WebService();                
        $operacion = new operacion();
        $cliente = new clientes();
        $operacionFactura = new operacion_factura();

        $success = true;
        $msj = "";
		$respuesta = "";
        $urlServicio = $appObj->paramGral["WSCrearEvento"];
        $idEmpresa = $appObj->paramGral["IdEmpresaDispapeles"];
		$respuestaService = array();        
		
        $loadReg1 = $operacionFactura->load("id_operacion_factura=".$idFactura); 
        $loadReg2 = $operacion->load("id_operacion=".$operacionFactura->id_operacion); 
        $loadReg3 = $cliente->load("id_cliente=".$operacion->id_emisor); 
        
        $nombreEmisor = "Prodispel"; //ucfirst(strtolower($cliente->razon_social));
        $identificacion = "817000707"; //$cliente->identificacion;
        $digitoVerificacion = "2"; //$cliente->digito_verificacion;   
        
		$nota1 = "Andres OBRANDO EN NOMBRE Y REPRESENTACION DE Argenta estucturadores sas";        
        $nota2 = "sin mi responsabilidad u otra equivalente"; //validar con o sin depende de condiciones        
        
        //ARMAMOS MENSAJE        
		$parametersService = array(
			"idEmpresa"=>$idEmpresa,
			"token"=>$this->token,
			"id"=>$this->genIDService($idFactura),
			"codigoEvento"=>"046",
			"tipoOperacion"=>"046",
			"archivosAdjuntos"=>array(),
			"notas"=> [$nota1,$nota2],
			"documentosReferenciado"=>
				array(
					"consecutivo"=>$operacionFactura->prefijo.$operacionFactura->num_factura,
					"cufeCude"=>$operacionFactura->cufe,
					"tipoDocumento"=>"01",
					"fechaVencimiento"=>$operacionFactura->fecha_vencimiento,
					"deudores"=>array()
				),
			"generadorEvento"=> //ARGENTA
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> "900518469",
					"nombreCompleto"=> "ARGENTA ESTRUCTURADORES SAS",
					"tipoPersona"=> "1",				
					"digitoVerificacion"=>"1",
					"monto"=> 0.0,
					"representanteLegal"=>array(),
					"tipoPortal"=>0
				),
			"mandante"=>array(),
			"mandatario"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> "860028580",
					"nombreCompleto"=> "Dispapeles sas",
					"tipoPersona"=> "1",				
					"digitoVerificacion"=>"2",
					"monto"=> 0.0,
					"representanteLegal"=>array(),
					"tipoPortal"=>0						
				),	
			"informacionCampoValor"=> 
				array
				(
					array
					(
						"nombre"=>"ValorFEV-TV",
						"valor"=>$operacionFactura->valor_neto,
						"numeric"=>true,
						"disabled"=>false
					)	
				)			 
		);     	
		        
        $respuesta = null;
        try
        {        
			$respuesta = $ws->callPOST($parametersService, $urlServicio);

			if (is_object($respuesta)){
				$codigoRespuesta = $respuesta->codigoRespuesta;				
				if ($codigoRespuesta == 200){
					$respuestaService["Success"] = true;
					$respuestaService["Response"] = $respuesta->objeto;				
					$respuestaService["Msj"] = "Transaccion exitosa";						
				}
				else{
					$descripcion = $respuesta->descripcion . $respuesta->message;					
					$respuestaService["Success"] = false;
					$respuestaService["Response"] = $respuesta->validaciones;			
					$respuestaService["Msj"] = $descripcion;				
				}
			}
		}
        catch(exception $e){
			$respuestaService["Success"] = false;
			$respuestaService["Response"] = "";			
			$respuestaService["Msj"] = "Error al consumir WS - Class Integracion InformePagoFactura";        
        }
        
		//ACTUALIZAMOS LA FACTURA CON LA RESPUESTA DEL SERVICIO        
		$operacionFactura->id_estado_transmision = 4; //ESTADO INFORME PAGO
		$msjTransmision = "<hr/>Fecha:". date("Y-m-d H:i:s") . " - Usuario:".$_SESSION["user"]."<br/>Respuesta:". json_encode($respuestaService);
		$operacionFactura->msj_informe = $msjTransmision . $operacionFactura->msj_informe;
		$operacionFactura->id_estado_informe = ($respuestaService["Success"]==true?1:2);
		$operacionFactura->Save();        
        
        return $respuestaService;
    }      
    
	/**
     * Funciòn para crear evento inscribir factura
     */
    function pagoFactura($idFactura = 0) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./utilities/class_web_service.php");

        //INSTANCIAMOS CLASES
        $ws = new WebService();                
        $operacion = new operacion();
        $cliente = new clientes();
        $operacionFactura = new operacion_factura();

        $success = true;
        $msj = "";
		$respuesta = "";
        $urlServicio = $appObj->paramGral["WSCrearEvento"];
        $idEmpresa = $appObj->paramGral["IdEmpresaDispapeles"];
		$respuestaService = array();        
		
        $loadReg1 = $operacionFactura->load("id_operacion_factura=".$idFactura); 
        $loadReg2 = $operacion->load("id_operacion=".$operacionFactura->id_operacion); 
        $loadReg3 = $cliente->load("id_cliente=".$operacion->id_emisor); 
        
        $nombreEmisor = "Prodispel"; //ucfirst(strtolower($cliente->razon_social));
        $identificacion = "817000707"; //$cliente->identificacion;
        $digitoVerificacion = "2"; //$cliente->digito_verificacion;        
        
        //ARMAMOS MENSAJE        
		$parametersService = array(
			"idEmpresa"=>$idEmpresa,
			"token"=>$this->token,
			"id"=>$this->genIDService($idFactura),
			"codigoEvento"=>"045",
			"tipoOperacion"=>"452",
			"referenciaTipoOperacion"=>"1",
		    "moneda"=>"COP",			
			"archivosAdjuntos"=>array(),
			"notas"=> array(),
			"documentosReferenciado"=>
				array(
					"consecutivo"=>$operacionFactura->prefijo.$operacionFactura->num_factura,
					"cufeCude"=>$operacionFactura->cufe,
					"tipoDocumento"=>"01",
					"fechaVencimiento"=>$operacionFactura->fecha_vencimiento,
					"deudores"=>array()
				),
			"generadorEvento"=>
				array
				(
					"tipoDocumento"=> "31",
					"numeroDocumento"=> $identificacion,
					"nombreCompleto"=> $nombreEmisor,
					"tipoPersona"=> "1",
					"digitoVerificacion"=> $digitoVerificacion,
					"monto"=> 0.0,
					"representanteLegal"=>array(),
					"tipoPortal"=>0
				),
			"mandante"=>array(),
			"mandatario"=>array(),
			"informacionCampoValor"=> 
				array
				(
					array
					(
						"nombre"=>"ValorActualTituloValor",
						"valor"=>$operacionFactura->valor_neto,
						"numeric"=>true,
						"disabled"=>false
					),
					array
					(
						"nombre"=>"ValorPendienteTituloValor",
						"valor"=>0,
						"numeric"=>true,
						"disabled"=>false
					)						
				)			 
		);     	
		        
        $respuesta = null;
        try
        {        
			$respuesta = $ws->callPOST($parametersService, $urlServicio);

			if (is_object($respuesta)){
				$codigoRespuesta = $respuesta->codigoRespuesta;				
				if ($codigoRespuesta == 200){
					$respuestaService["Success"] = true;
					$respuestaService["Response"] = $respuesta->objeto;				
					$respuestaService["Msj"] = "Transaccion exitosa";						
				}
				else{
					$descripcion = $respuesta->descripcion . $respuesta->message;					
					$respuestaService["Success"] = false;
					$respuestaService["Response"] = $respuesta->validaciones;			
					$respuestaService["Msj"] = $descripcion;				
				}
			}
		}
        catch(exception $e){
			$respuestaService["Success"] = false;
			$respuestaService["Response"] = "";			
			$respuestaService["Msj"] = "Error al consumir WS - Class Integracion PagoFactura";        
        }
        
		//ACTUALIZAMOS LA FACTURA CON LA RESPUESTA DEL SERVICIO        
		$operacionFactura->id_estado_transmision = 5; //ESTADO PAGO
		$msjTransmision = "<hr/>Fecha:". date("Y-m-d H:i:s") . " - Usuario:".$_SESSION["user"]."<br/>Respuesta:". json_encode($respuestaService);
		$operacionFactura->msj_pago = $msjTransmision . $operacionFactura->msj_pago;
		$operacionFactura->id_estado_pago = ($respuestaService["Success"]==true?1:2);
		$operacionFactura->Save();        
        
        return $respuestaService;
    }      
    
	/**
     * Funciòn para consultar el token
     */
    function consultarToken() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INSTANCIAMOS CLASES
        $appTokens = new app_tokens();
        
		$app = "DISPAPELES";
		$fecha = date("Y-m-d H:i:s");
		$token = $appTokens->consultarToken($app, $fecha);
		
		//DETERMINAMOS SI HAY TOKEN GENERADO Y ACTIVO DE LO CONTRARIO PEDIMOS UN NUEVO TOKEN
		if ($token == ""){	
			$respuestaToken = $this->generarToken();		
			$respuesta["Success"] = $respuestaToken["Success"];
			$respuesta["Token"] = $respuestaToken["Token"];
			$respuesta["Msj"] = $respuestaToken["Msj"];
		}
		else{
			$respuesta["Success"] = true;
			$respuesta["Token"] = $token;
			$respuesta["Msj"] = "Token database";
		}
		
		return $respuesta;
    }    
    
	/**
     * Funciòn para generar un nuevo token
     */
    function generarToken() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;
        
        require_once("./utilities/class_web_service.php");
        
        //INSTANCIAMOS CLASES
        $ws = new WebService();        
        $appToken = new app_tokens();
        
        $urlServicio = $appObj->paramGral["WSLogin"];
        $idEmpresa = $appObj->paramGral["IdEmpresaDispapeles"];
        $usuario = $appObj->paramGral["UsuarioDispapeles"];
        $password = $appObj->paramGral["PassDispapeles"];
        $respuestaToken = array();        
		$parametersLogin = array(
			 "idEmpresa"=>$idEmpresa,
			 "usuario"=>$usuario,
			 "password"=>$password
		);
        
        $respuesta = null;
        try
        {        
			$respuesta = $ws->callPOST($parametersLogin, $urlServicio);

			if (is_object($respuesta)){
				$codigoRespuesta = $respuesta->codigoRespuesta;
				$descripcion = $respuesta->descripcion;
				if ($codigoRespuesta == 200){
					$respuestaToken["Success"] = true;
					$respuestaToken["Token"] = $respuesta->token;				
					$respuestaToken["Msj"] = $descripcion;	
					
					//GUARDAMOS EL TOKEN EN BASE DE DATOS PARA NO CONSUMIR NUEVAMENTE
					$loadReg = $appToken->Load("app='DISPAPELES'");
					$appToken->token = $respuesta->token;					
					$dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $respuesta->dateExpireToken);				
					$appToken->fecha_validez = $dateTime->format('Y-m-d H:i:s');
					$appToken->Save();
				}
				else{
					$respuestaToken["Success"] = false;
					$respuestaToken["Token"] = "";			
					$respuestaToken["Msj"] = $descripcion;				
				}
			}
		}
        catch(exception $e){
			$respuestaToken["Success"] = false;
			$respuestaToken["Token"] = "";			
			$respuestaToken["Msj"] = "Error al consumir WS - Class Integracion Login";        
        }
        
        return $respuestaToken;
   	}
   	
   	function genIDService($key = 0){
   		
   		global $db,$id,$appObj,$LANG,$msjProcesoRealizado;
   		
   		$idGen = 0;
   		
   		if ($key != 0){
   			$idGen = $key . date("mdhis");
   		}
   		
   		return $idGen;
   	
   	}    
   	
	function escapeJsonString($value) {
		$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
	}   	
    
}

?>