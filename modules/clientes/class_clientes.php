<?php
/**
* Adminsitraci�n de tabla de clientes
* @version 1.0
* El constructor de esta clase es {@link clientes()}
*/
require_once("class_clientes_extended.php");
class clientes extends ADOdb_Active_Record{


    var $Database;
    var $ID;
    var $arrTipos = array("1"=>"REGIMEN COM�N","2"=>"R�GIMEN SIMPLIFICADO");
    var $arrTipoEmpresa = array("1"=>"PRIVADA","2"=>"PUBLICA","3"=>"MIXTA");
    var $arrEstadosDocumento = array("1"=>"CREADO","2"=>"APROBADO");
    var $arrEstadosCliente = array("0"=>"CREADO","1"=>"ACTIVO","2"=>"INACTIVO","3"=>"RECHAZADO","4"=>"PENDIENTE REVISI�N","5"=>"TAREA CERRADA");

    /**
      * Funci�n para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "client":
                            $this->client();
                            break;
            case "saveClient":
                            $this->saveClient();
                            break;
            case "listClients":
                            $this->listClients();
                            break;
            case "searchClients":
                            $this->searchClients();
                            break;
            case "deleteClient":
                            $this->deleteClient();
                            break;
            case "sendDataClient":
                            $this->sendDataClient();
                            break;
            case "vinculacion":
                            $this->vinculacion();
                            break;
            case "guardarVinculacion":
                            $this->guardarVinculacion();
                            break;
            case "actualizarVinculacion":
                            $this->actualizarVinculacion();
                            break;
            case "obtenerVinculacion":
                            $this->obtenerVinculacion();
                            break;
            case "verDocumentoCliente":
                            $this->verDocumentoCliente();
                            break;
            case "listDocumentosCliente":
                            $this->listDocumentosCliente();
                            break;
            case "verInformacionAnexa":
                            $this->verInformacionAnexa();
                            break;
            case "documento":
                            $this->documento();
                            break;
            case "saveDocumento":
                            $this->saveDocumento();
                            break;
            case "deleteDocumento":
                            $this->deleteDocumento();
                            break;
            case "aprobarDocumento":
                            $this->aprobarDocumento();
                            break;
            case "exportarClientes":
                            $this->exportarClientes();
                            break;
            case "verInfoCondiciones":
                            $this->condiciones();
                            break;
            case "saveCondiciones":
                            $this->saveCondiciones();
                            break;
			case "EditarVinculacion":
                            $this->EditarVinculacion();
                            break;
			case "VerReporte":
                            $this->VerReporte();
                            break;
			case "listReferenciaPagador":
                            $this->listReferenciaPagador();
                            break;
            case "ReferenciaPagador":
                            $this->ReferenciaPagador();
                            break;
            case "saveReferenciaPagador":
                            $this->saveReferenciaPagador();
                            break;
            case "deleteReferenciaPagador":
                            $this->deleteReferenciaPagador();
                            break;
            case "VerFormatoPagare":
                            $this->VerFormatoPagare();
                            break;
            case "VerCartaInstrcciones":
                            $this->VerCartaInstrcciones();
                            break;
            case "guardarInformacionCliente":
                            $this->guardarInformacionCliente();
                            break;
            case "enviarInformacionCliente":
                            $this->enviarInformacionCliente();
                            break;
            case "cargarPagadoresDependientes":
                            $this->cargarPagadoresDependientes();
                            break;
            case "cargarInfoTerceroJson":
                            $this->cargarInfoTerceroJson();
                            break;
            case "savePagare":
                            $this->savePagare();
                            break;
            case "saveResolucion":
                            $this->saveResolucion();
                            break;
            case "aceptaciones":
                            $this->aceptaciones();
                            break;
            case "saveAceptaciones":
                            $this->saveAceptaciones();
                            break;
            case "listSeguimiento":
                            $this->listSeguimiento();
                            break;
			case "seguimiento":
                            $this->seguimiento();
                            break;
            case "saveSeguimiento":
                            $this->saveSeguimiento();
                            break;
            case "cambiarEstado":
                            $this->cambiarEstado();
                            break;
            case "rechazo":
                            $this->rechazo();
                            break;
            case "saveRechazo":
                            $this->saveRechazo();
                            break;
            case "listVerificaciones":
                            $this->listVerificaciones();
                            break;
			case "verificacion":
                            $this->verificacion();
                            break;
            case "saveVerificacion":
                            $this->saveVerificacion();
                            break;
            case "versionImpresa":
                            $this->versionImpresa();
                            break;
            case "listDocumentosClienteVinculacion":
                            $this->listDocumentosClienteVinculacion();
                            break;

        }
    }

    /**
      * Funci�n para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){
            case "vinculacion":
                            $this->vinculacion();
                            break;
            case "guardarVinculacion":
                            $this->guardarVinculacion();
                            break;

        }
    }

	/**
     * Funci�n para ver el listado de documentos de vinculacion de un cliente
     */
    function listDocumentosClienteVinculacion() {

        global $db,$id,$appObj,$LANG;

		require_once("./modules/generales/class_generales.php");

        $idCliente = $_SESSION["id_tercero"];

        include("./modules/clientes/templates/listado_documentos_vinculacion.php");
    }

 	/**
     * Funci�n para ver formato impreso de la vinculacion
     */
    function versionImpresa() {

        global $db,$id,$appObj,$LANG;

		require_once("./modules/generales/class_generales.php");
		require_once("./utilities/controles/radio.php");

        $idCliente = $_REQUEST["id_cliente"];

		$strSQL = "SELECT c.*, p.descripc as pais, d.departamento, ce.ciudad ciudad_exp, ces.ciudad as ciudad_suplente, ca.*,
						  s.nombre as sector, ciiu.descripcion as desc_ciiu, emp.descripcion as numero_empleados, ref.referencia
				   FROM clientes as c
				   LEFT JOIN departamentos as d ON c.id_departamento = d.id_departamento
				   LEFT JOIN paises as p ON d.id_pais = p.id_pais
				   LEFT JOIN ciudades as ce ON c.id_ciudad_expedicion = ce.id_ciudad
				   LEFT JOIN ciudades as ces ON c.id_ciudad_exp_representante_supl = ces.id_ciudad
				   LEFT JOIN clientes_adicionales as ca ON c.id_cliente = ca.id_cliente
				   LEFT JOIN sectores as s ON ca.id_sector = s.id_sector
				   LEFT JOIN ciiu as ciiu ON ca.id_ciiu = ciiu.id_ciiu
				   LEFT JOIN numero_empleados AS emp ON ca.numero_empleados = emp.id_numero
				   LEFT JOIN referencia_argenta AS ref ON ca.id_como_se_entera = ref.id_referencia
				   WHERE c.id_cliente=".$idCliente;

        $rsDataCliente = $db->Execute($strSQL);

		$relacionComercial = new relacion_comercial();
		$clienteReferencia = new clientes_referencias();
		$clienteSocio = new clientes_socios_accionistas();
		$clienteAdicional = new clientes_adicionales();

        //TRAEMOS DATOS
        $arrRelacionesComercial = $relacionComercial->getRelacionComercial();
        $arrTipoDocumento = array("1"=>"RUT","2"=>"C�dula extranjer�a", "3"=>"NIT","4"=>"C�dula ciudadan�a");

		//OBTENEMOS LAS REFERENCIAS - CLIENTES
		$rsRefClientes = $clienteReferencia->obtenerReferenciaCliente($idCliente, 1);

		//OBTENEMOS LOS SOCIOS - ACCIONISTAS - BENEFICIARIOS
		$rsSocios = $clienteSocio->obtenerSociosCliente($idCliente);

		//OBTENEMOS LOS SOCIOS - ACCIONISTAS - BENEFICIARIOS
		$rsBeneficiarios = $clienteSocio->obtenerSociosBeneficiariosCliente($idCliente,1);


        include("./modules/clientes/templates/version_impresa.php");
    }

	/**
     * Funci�n para guardar una verificacion
     */
    function saveVerificacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$seguimiento = new clientes_seguimiento();
		$verificacion = new clientes_verificaciones();

		$idCliente = $_POST["id_cliente"];
		$idTipoVerificacion = $_POST["id_tipo_verificacion"];

        $loadReg1 = $this->load("id_cliente=".$idCliente);

		$loadReg2 = $verificacion->load("id_cliente=".$idCliente. " AND id_tipo_verificacion=". $idTipoVerificacion);

		$verificacion->id_cliente = $idCliente;
		$verificacion->id_tipo_verificacion = $idTipoVerificacion;
		$verificacion->valor_verificacion = $_REQUEST["verifica"];
		$verificacion->fecha_consulta = $_REQUEST["fecha_consulta"];
		$verificacion->fecha_verificacion = date("Y-m-d");
		$observaciones = "Fecha:". date("Y-m-d") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones']."<hr/>";
		$verificacion->observaciones = $observaciones . $verificacion->observaciones;
		$verificacion->id_usuario_verifica = $_REQUEST["id_usuario_verifica"];
		$verificacion->id_usuario_cumplimiento = $_SESSION["id_user"];
		$verificacion->Save();

		$tipoVerificacion = "";
		if ($idTipoVerificacion == 1)
			$tipoVerificacion = "SAGRILAFT";
		else if ($idTipoVerificacion == 2)
			$tipoVerificacion = "CONOCIMIENTO DE CLIENTE";
		else if ($idTipoVerificacion == 3)
			$tipoVerificacion = "CONOCIMIENTO DE LA OPERACION";

		$loadReg = $seguimiento->load("id_cliente_seguimiento=0");
		$seguimiento->id_cliente = $idCliente;
		$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
        $seguimiento->id_usuario = $_SESSION["id_user"];
        $seguimiento->id_estado = $this->activo;
        $seguimiento->observaciones = "REGISTRO/ACTUALIZACION DE VERIFICACION: " . $tipoVerificacion. " : ". $_POST['observaciones'];
        $seguimiento->es_tarea = 2;
        $seguimiento->Save();

		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['IdCliente'] = $idCliente;
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

	/**
     * Funci�n para ver el formulario de verificacion
     */
    function verificacion() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/users/class_users.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/textarea.php");
        require_once("./utilities/controles/radio.php");

		$verificacion = new clientes_verificaciones();
		$usuario = new users();

        $idCliente = $_REQUEST["id_cliente"];
        $tipoVerificacion = $_REQUEST["tipo_verificacion"];

        $loadReg2 = $verificacion->load("id_cliente=".$idCliente. " AND id_tipo_verificacion=". $tipoVerificacion);

        //OBTENEMOS LOS USUARIOS EMPLEADOS
        $arrUsuariosEmpleados = $usuario->getUsuariosPerfil(0);

        include("./modules/clientes/templates/cliente_verificacion.php");

    }

    /**
     * Funci�n para ver el listado de verificaciones
     */
    function listVerificacion() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/textarea.php");

		$verificacion = new clientes_verificaciones();

        $idCliente = $_REQUEST["id_cliente"];

        $rsVerificacion = $verificacion->obtenerVerificacionesCliente($idCliente);

        include("./modules/clientes/templates/listado_verificacion.php");

    }

 	/**
     * Funci�n para guardar un seguimiento - tarea
     */
    function saveRechazo() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$seguimiento = new clientes_seguimiento();

        $idCliente = $_REQUEST["id_cliente"];

        $loadReg = $this->load("id_cliente=".$idCliente);

		$this->id_cliente = $idCliente;
		$this->id_motivo_rechazo = $_POST['id_motivo_rechazo'];
		$this->activo = 3; //RECHAZADO
		$this->observaciones_rechazo = $_POST['observaciones_rechazo'];
		$this->Save();

		$loadReg1 = $seguimiento->load("id_cliente_seguimiento=0");
		$seguimiento->id_cliente = $idCliente;
		$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
        $seguimiento->id_usuario = $_SESSION["id_user"];
        $seguimiento->id_estado = 3; //RECHAZADO
        $seguimiento->observaciones = $_POST["observaciones_rechazo"];
        $seguimiento->es_tarea = 2;
        $seguimiento->Save();

		$jsondata['IdCliente'] = $idCliente;
		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

	/**
     * Funci�n para ver el formulario de seguimiento
     */
    function rechazo() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/textarea.php");

		$motivo = new motivos_rechazo();

        $idCliente = $_REQUEST["id_cliente"];

        //OBTENEMOS LOS MOTIVOS DE RECHAZO
        $arrMotivos = $motivo->getMotivosRechazo();

        include("./modules/clientes/templates/cliente_rechazo.php");

    }

 	/**
     * Funci�n para cambiar el estado del cliente
     */
    function cambiarEstado() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$cliente = new clientes();
		$seguimiento = new clientes_seguimiento();

        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $cliente->load("id_cliente=".$idCliente);

		//DETERMINAMOS EL ESTADO AL QUE SE EST� CAMBIANDO
		$nuevoEstado = $_REQUEST["estado"];
		//var $arrEstadosCliente = array("1"=>"ACTIVO","2"=>"INACTIVO","3"=>"RECHAZADO","4"=>"PENDIENTE REVISI�N");
		if ($nuevoEstado == 1){

		}
		else if ($nuevoEstado == 2){

		}

		$cliente->activo = $nuevoEstado;
		$cliente->Save();

        //REGISTRAMOS SEGUIMIENTO
        $loadReg = $seguimiento->load("id_cliente_seguimiento=0");

		$seguimiento->id_cliente = $idCliente;
		$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
        $seguimiento->id_usuario = $_SESSION["id_user"];
        $seguimiento->id_estado = $nuevoEstado;
        $seguimiento->es_tarea = 2;
        $seguimiento->observaciones = strtoupper(strtolower($_POST["observaciones"]));
        $seguimiento->Save();

		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['IdCliente'] = $idCliente;
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

 	/**
     * Funci�n para guardar un seguimiento - tarea
     */
    function saveSeguimiento() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./utilities/class_send_mail.php");
        require_once("./modules/users/class_users.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();

		$usuario = new users();
		$seguimiento = new clientes_seguimiento();

		$idCliente = $_POST["id_cliente"];

        $loadReg = $seguimiento->load("id_cliente_seguimiento=0");
        $loadReg1 = $this->load("id_cliente=".$idCliente);

		$seguimiento->id_cliente = $idCliente;
		$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
		$seguimiento->id_usuario = $_SESSION["id_user"];
		$seguimiento->id_estado = $this->activo; //TOMAMOS ESTADO ACTUAL DEL CLIENTE
		$seguimiento->id_usuario_responsable = null;
		$seguimiento->es_tarea = 2;
		if ($_POST['id_usuario_responsable'] != ""){
			$seguimiento->id_usuario_responsable = $_POST['id_usuario_responsable'];
			$seguimiento->es_tarea = 1;
			$seguimiento->id_estado = 4; //PENDIENTE REVISION CUANDO ES UNA TAREA
		}

		$seguimiento->observaciones = $_POST["observaciones_seguimiento"];
		$seguimiento->Save();

		if ($_POST['id_usuario_responsable'] != ""){

			$loadReg2 = $usuario->load("id_usuario=".$seguimiento->id_usuario_responsable);

			$enviadoPor=$_SESSION["user"];
			$cliente=$this->razon_social;
			$para=$usuario->nombres . " " . $usuario->apellidos;
			$paraEmail=$usuario->correo_personal;
			$fechaEnvio=date("Y-m-d h:i:s");
			$nota=$_POST["observaciones_seguimiento"];

			$fromName = $_SESSION["user"];
			$fromEmail = $_SESSION["email"];
			$subjectMail = "Asignacion nueva tarea portal Argenta";
			$toNameMail = $_SESSION["user"].";".$para;
			$toEmail = $_SESSION["email"].";".$paraEmail;
			$templateMail = "mailTarea";

			//ENVIAMOS EL CORREO
			$arrVarsReplace = array("NAME"=>$para,"FECHA_ENVIO"=>$fechaEnvio,"CLIENTE"=>$cliente,"ENVIADO_POR"=>$enviadoPor,"NOTA"=>$nota);
			$success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,$templateMail,array(),$arrVarsReplace);
		}

		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
   	}

	/**
     * Funci�n para ver el formulario de seguimiento
     */
    function seguimiento() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/users/class_users.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/textarea.php");

		$usuario = new users();

        $idCliente = $_REQUEST["id_cliente"];

        //OBTENEMOS LOS USUARIOS EMPLEADOS
        $arrUsuariosEmpleados = $usuario->getUsuariosPerfil(0);

        include("./modules/clientes/templates/cliente_seguimiento.php");

    }

    /**
     * Funci�n para ver el listado de seguimiento
     */
    function listSeguimiento() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/textarea.php");

		$seguimiento = new clientes_seguimiento();

        $idCliente = $_REQUEST["id_cliente"];

        $rsSeguimiento = $seguimiento->obtenerSeguimientoCliente($idCliente);

        include("./modules/clientes/templates/listado_seguimiento.php");

    }

 	/**
     * Funci�n para guardar las aceptacion
     */
    function saveAceptaciones() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$adicional = new clientes_adicionales();

        $idCliente = $_REQUEST["id_cliente"];

        $loadReg = $adicional->load("id_cliente=".$idCliente);

		$adicional->id_cliente = $idCliente;
		$adicional->aceptacion_general = $_POST['aceptacion_general'];
        $adicional->aceptacion_especifica = $_POST['aceptacion_especifica'];
        $adicional->confirma_valores = 2;
        if ($_POST['confirma_valores'] != "" && $adicional->aceptacion_especifica == 1)
        	$adicional->confirma_valores = $_POST['confirma_valores'];

        $adicional->correo_validacion = $_POST['correo_validacion'];
        $observaciones = "Fecha:". date("Y-m-d") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones_aceptacion']."<hr/>";
        $adicional->observaciones_aceptacion = $observaciones . $adicional->observaciones_aceptacion;
        $adicional->Save();

		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funci�n para ver el formulario de aceptacion
     */
    function aceptaciones() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

		$adicional = new clientes_adicionales();

        $idCliente = $_REQUEST["id_cliente"];

        $loadReg = $adicional->load("id_cliente=".$idCliente);

        include("./modules/clientes/templates/cliente_aceptaciones.php");

    }

    /**
     * Funci�n para guardar las resolucion de facturacion
     */
    function saveResolucion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$resolucion = new clientes_res_facturas();

		$idCliente = $_POST['id_cliente'];
		$registro = $_POST['registro'];

        $loadReg = $resolucion->load("id_cliente=".$idCliente." AND registro=" . $registro);

        $resolucion->id_cliente = $idCliente;
        $resolucion->prefijo = $_POST['prefijo'];
        $resolucion->resolucion = $_POST['resolucion'];
        $resolucion->fecha_inicial = $_POST['fecha_inicial'];
        $resolucion->fecha_final = $_POST['fecha_final'];
        $resolucion->fac_inicial = $_POST['fac_inicial'];
        $resolucion->fac_final = $_POST['fac_final'];
        $resolucion->registro = $_POST['registro'];
        $resolucion->Save();

		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
	 * Funci�n para obtener la vinculacion
	 */
	function obtenerVinculacion() {

		global $db;

		$clienteReferencia = new clientes_referencias();
		$clienteSocio = new clientes_socios_accionistas();
		$clienteAdicional = new clientes_adicionales();

		$idCliente = $_SESSION["id_tercero"];

		//$loadReg = $this->load("tipo_identificacion=".$_POST['tipo_identificacion']." AND identificacion='".$_POST['identificacion']."' AND permite_actualizar=1");
		$loadReg = $this->load("id_cliente=".$idCliente);

		if ($this->id_cliente != "")
		{

			$loadReg1 = $clienteAdicional->load("id_cliente=".$this->id_cliente);

			$jsondata['IdCliente'] = $this->id_cliente;
			$jsondata['Identificacion'] = $this->identificacion;
			$jsondata['DV'] = $this->digito_verificacion;
			$jsondata['TipoIdentificacion'] = $this->tipo_identificacion;
			$jsondata['RazonSocial'] = utf8_encode($this->razon_social);
			$jsondata['FechaConstitucion'] = $this->fecha_consticucion;

			$idPais = "";
			if ($this->id_departamento != ""){
				$strSQL = "SELECT id_pais FROM departamentos WHERE id_departamento = ".$this->id_departamento;
				$rsDatos = $db->Execute($strSQL);
				$idPais = $rsDatos->fields["id_pais"];
			}

			$jsondata['Pais'] = $idPais;
			$jsondata['Departamento'] = $this->id_departamento;
			$jsondata['Ciudad'] = $this->ciudad;
			$jsondata['Telefono'] = $this->telefono_fijo;
			$jsondata['Celular'] = $this->telefono_celular;
			$jsondata['Direccion'] = utf8_encode($this->direccion);
			$jsondata['Correo'] = $this->correo_personal;
			$jsondata['RepresentanteLegal'] = utf8_encode($this->representante_legal);
			$jsondata['IdentificacionRepresentante'] = $this->identificacion_representante;
			$jsondata['CiudadExpedicion'] = $this->id_ciudad_expedicion;
			$jsondata['PersonaAutoriza'] = utf8_encode($this->encargado);
			$jsondata['CelularPersonaAutoriza'] = $this->telefonos_encargado;
			$jsondata['CargoAutorizador'] = utf8_encode($this->cargo_autorizador);
			$jsondata['TipoEmpresa'] = $this->tipo_empresa;
			$jsondata['TipoEmpresa1'] = $this->tipo_empresa1;
			$jsondata['OrigenesFondo'] = utf8_encode($this->declaracion_origen_fondos);
			$jsondata['MonedaExtranjera'] = $this->moneda_extranjera;
			$jsondata['BancoMe'] = $this->banco_me;
			$jsondata['CuentaMe'] = $this->cuenta_nro_me;
			$jsondata['MonedaMe'] = $this->moneda_me;
			$jsondata['CiudadMe'] = $this->ciudad_me;
			$jsondata['PaisMe'] = $this->pais_me;
			$jsondata['TipoTransaccion'] = utf8_encode($this->transaccion_moneda);
			$jsondata['CuentasMonedaExtranjera'] = $this->cuentas_moneda_extranjera;
			$jsondata['DetalleProducto'] = utf8_encode($this->detalle_producto);
			$jsondata['IdSector'] = $clienteAdicional->id_sector;
			$jsondata['IdCiiu'] = $clienteAdicional->id_ciiu;
			$jsondata['GranContribuyente'] = $clienteAdicional->gran_contribuyente;
			$jsondata['Autoretenedor'] = $clienteAdicional->autoretenedor;
			$jsondata['ReteIVA'] = $clienteAdicional->rete_iva;
			$jsondata['ReteICA'] = $clienteAdicional->rete_ica;
			$jsondata['TarifaICA'] = $clienteAdicional->tarifa_ica;
			$jsondata['EvolucionVTAAnterior'] = $clienteAdicional->evolucion_vta_anio_anterior;
			$jsondata['EvolucionVTAActual'] = $clienteAdicional->evolucion_vta_anio_actual;
			$jsondata['IdNumeroEmpleados'] = $clienteAdicional->numero_empleados;
			$jsondata['IdReferencia'] = $clienteAdicional->id_como_se_entera;
			$jsondata['RecursosPublicos'] = $clienteAdicional->recursos_publicos;

			//OBTENEMOS LAS REFERENCIAS - CLIENTES
			$rsRefClientes = $clienteReferencia->obtenerReferenciaCliente($this->id_cliente, 1);
			$jsondataReferencia = array();
			$i=0;
			while (!$rsRefClientes->EOF){
				$jsondataReferencia[$i]['RefEmpresa'] = utf8_encode($rsRefClientes->fields["empresa"]);
				$jsondataReferencia[$i]['RefNit'] = $rsRefClientes->fields["nit"];
				$jsondataReferencia[$i]['RefTipoReferencia'] = $rsRefClientes->fields["tipo_referencia"];
				$jsondataReferencia[$i]['RefPorcentajeVtas'] = $rsRefClientes->fields["porcentaje_vtas"];
				$jsondataReferencia[$i]['RefIdPlazoPago'] = $rsRefClientes->fields["id_plazo_pago"];
				$jsondataReferencia[$i]['RefDescontarFacturas'] = $rsRefClientes->fields["descontar_facturas"];
				$jsondataReferencia[$i]['RefMontoDescuento'] = $rsRefClientes->fields["monto_descuento"];
				$jsondataReferencia[$i]['RefIdRelacionComercial'] = $rsRefClientes->fields["id_relacion_comercial"];
				$jsondata['ClienteReferencias'] = $jsondataReferencia;
				$i++;
				$rsRefClientes->MoveNext();
			}

			//OBTENEMOS LOS SOCIOS - ACCIONISTAS - BENEFICIARIOS
			$rsSocios = $clienteSocio->obtenerSociosCliente($this->id_cliente);
			$jsondataSocio = array();
			$i=0;
			while (!$rsSocios->EOF){
				$jsondataSocio[$i]['TipoPersona'] = $rsSocios->fields["tipo_persona"];
				$jsondataSocio[$i]['IdTipoDocumento'] = $rsSocios->fields["id_tipo_documento"];
				$jsondataSocio[$i]['Identificacion'] = $rsSocios->fields["identificacion"];
				$jsondataSocio[$i]['IdPais'] = $rsSocios->fields["id_pais"];
				$jsondataSocio[$i]['FechaExpedicion'] = $rsSocios->fields["fecha_expedicion"];
				$jsondataSocio[$i]['RazonSocial'] = utf8_encode($rsSocios->fields["razon_social"]);
				$jsondataSocio[$i]['PaisUbicacion'] = utf8_encode($rsSocios->fields["pais_ubicacion"]);
				$jsondataSocio[$i]['PoliticamenteExpuesta'] = $rsSocios->fields["politicamente_expuesta"];
				$jsondataSocio[$i]['TipoVinculacionPersona'] = $rsSocios->fields["tipo_vinculacion_persona"];
				$jsondataSocio[$i]['Comentario'] = $rsSocios->fields["comentario"];
				$jsondataSocio[$i]['BeneficiarioFinal'] = $rsSocios->fields["socio_beneficiario"];
				$jsondata['ClienteSocios'] = $jsondataSocio;
				$i++;
				$rsSocios->MoveNext();
			}

			$jsondata['Success'] = true;
		}
		else{
			$jsondata['Success'] = false;
			$jsondata['Message'] = utf8_encode("El NIT ingresado no ha sido activado para actualizaci�n.<br/><br/>Por favor solicite autorizaci�n de actualizaci�n al correo eleyva@argentaestructuradores.com");
		}


		//print_R($jsondata);
		echo json_encode($jsondata);
		exit;
    }

    /**
     * Funci�n para guardar el pagare
     */
    function savePagare() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idCliente = $_POST['id_cliente'];

        $loadReg = $this->load("id_cliente=".$idCliente);

		//ACTUALIZAMOS EL PAGARE Y LA FECHA DE SISTEMA
		if ($_POST['nro_pagare'] != "" && $this->pagare==""){
        	$this->pagare = $_POST['nro_pagare'];
        	$this->fecha_generacion_pagare = date("Y-m-d");
        }
        else{//SOLO ACTUALIZAMOS LA FECHA
       		$this->fecha_generacion_pagare = $_POST['fecha_generacion_pagare'];
        }

        $this->Save();

        $jsondata['Message'] = "Transaccion exitosa.";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    function cargarInfoTerceroJson(){

		global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

    	$tipo = $_REQUEST["tipo"];
    	$idCliente = $_REQUEST['id_cliente'];

    	$factor = null;
    	$descuento = null;
        $idEjecutivo = null;
        $comision = null;
        $diasVigenciaPagare = null;
        $diasVigenciaResFac = null;
    	if ($tipo == "emisor"){
    		if ($idCliente != ""){
				$loadReg = $this->load("id_cliente=".$idCliente);
				$factor = $this->factor;
				$descuento = $this->porcentaje_descuento;
                $idEjecutivo = $this->id_ejecutivo;
                $comision = $this->comision;

				//OBTENEMOS DIAS DE VIGENCIA DEL PAGARE
				$diasVigenciaPagare = $this->diasVigenciaPagare($idCliente, $this->fecha_generacion_pagare);

				//OBTENEMOS DIAS DE VIGENCIA DE LA RESOLUCION DE FACTURACION
				$diasVigenciaResFac = $this->diasVigenciaResolucion($idCliente);
    		}
    	}
    	else if ($tipo == "pagador"){
    		$referenciaPagador =  new clientes_ref_pagador();
    		$idPagador = $_REQUEST["id_pagador"];
    		if ($idPagador != ""){
				$loadReg = $referenciaPagador->load("id_cliente=".$idCliente. " AND id_pagador=".$idPagador);
				$factor = $referenciaPagador->factor;
				$descuento = $referenciaPagador->porcentaje_descuento;
    		}
    	}

    	$jsondata['Factor'] = $factor;
    	$jsondata['Descuento'] = $descuento;
        $jsondata['IdEjecutivo'] = $idEjecutivo;
        $jsondata['Comision'] = $comision;
        $jsondata['DiasVigenciaPagare'] = $diasVigenciaPagare;
        $jsondata['DiasVigenciaResFac'] = $diasVigenciaResFac;
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

    function cargarPagadoresDependientes(){

    	global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

		$idTercero = $_REQUEST["id"];

		$referenciaPagador =  new clientes_ref_pagador();

        $jsondata = $referenciaPagador->obtenerPagadoresCliente($idTercero);

        echo $jsondata;
        exit;
    }

    function enviarInformacionCliente(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./utilities/class_send_mail.php");
        require_once("./modules/clientes/class_clientes.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();

        $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
        $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
        $subjectMail = $_REQUEST["__subjectMail"];
        $toNameMail = $_REQUEST["__toNameMail"];
        $toEmail = $_REQUEST["__toEmailMail"];
        $template = $_REQUEST["__template"];
        $idCliente = $_REQUEST["__option1"];
        $observaciones = ($_REQUEST["__dataMail"] != ""?$_REQUEST["__dataMail"]:"N/D");

        //OBTENEMOS EL CLIENTE
        $loadReg2 = $this->load("id_cliente=".$idCliente);

        //ENVIAMOS EL CORREO
        $arrAttach = array("clientes/reporte.pdf"=>"Reporte PDF");
        $arrVarsReplace = array("NAME"=>$toNameMail,"OBSERVACIONES"=>$observaciones);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,$template,$arrAttach,$arrVarsReplace);

        $jsondata['Message'] = "test";
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }

    function guardarInformacionCliente(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado,$appObj;

        require_once("./utilities/pdf/tcpdf.php");

        $dataMail = $_REQUEST["__dataMail"];

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set default header data
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 7, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        // Print text using writeHTMLCell()
        //$pdf->writeHTMLCell(0, 0, '', '', $dataMail, 0, 1, 0, true, '', true);
        $pdf->writeHTML($dataMail, true, false, true, false, '');

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output(__DIR__ . '../../../gallery/clientes/reporte.pdf', 'F');

        $jsondata['Success'] = true;
        echo json_encode($jsondata);
        exit;
    }

	/**
     * Funci�n para obtener el listado de referencias pagador
     */
    function listReferenciaPagador(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/select.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->tableDataId = "tableDataReferenciaPagador";
        $dataGrid->heightDG = "300";

        $idCliente = $_POST['id_cliente'];

        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT ref.id_ref_pagador, pg.razon_social, ref.porcentaje_descuento, ref.factor, ref.plazo
                   FROM clientes_ref_pagador as ref
                   INNER JOIN clientes as pg ON ref.id_pagador = pg.id_cliente";
        $strWhere = " WHERE ref.id_cliente=".$idCliente;

        $strOrder = " ORDER BY  pg.razon_social ASC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Referncia Pagador</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("Agregar","javascript:editReferenciaPagador(0,'clientes','ReferenciaPagador')");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Razon social o<br/> Nombre completo","Porcentaje", "Factor", "Plazo"));
        $dataGrid->searchColumn=false;

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_ref_pagador"));

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_ref_pagador"=>"ID_REF_PAGADOR","mod"=>"clientes","action"=>"ReferenciaPagador");
        $arrVarGet2 = Array("id_ref_pagador"=>"ID_REF_PAGADOR","mod"=>"clientes","action"=>"deleteReferenciaPagador");

        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Cliente' alt='Editar Referncia Pagador' border='0'/></a></center>","editReferenciaPagador",$arrVarGet1,"functionjs","left");
        $dataGrid->addColLink("Eliminar","<center><a href=\"javascript:{function};\"><img src='./images/eliminar.png' title='Eliminar Cliente' alt='Eliminar Referncia Pagador' border='0'/></a></center>","deleteReferenciaPagador",$arrVarGet2,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/clientes/templates/listado_referencia_pagador.php");
    }

    /**
     * Funci�n para ver el formulario de resgistro referencia pagador
     */
    function ReferenciaPagador() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

        $idCliente = $_REQUEST["id_cliente"];
        $idRefPagador = $_REQUEST["id_ref_pagador"];

        $clientesRefPagador = new clientes_ref_pagador();
        $loadReg = $clientesRefPagador->load("id_ref_pagador=".$idRefPagador);

        //OBTENEMOS LOS PAGADORES
        $listClientesRefPagador = new clientes_ref_pagador();
        $arrTodosPagadores = $listClientesRefPagador->obtenerTodosPagadoresSinFiltro();

        include("./modules/clientes/templates/referencia_pagador.php");
    }

    /**
     * Funci�n para guardar las referencias pagador
     */
    function saveReferenciaPagador() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idCliente = $_POST['id_cliente'];
        $idPagador = $_POST['id_pagador'];
        $idRefPagador = $_REQUEST["id_ref_pagador"];
        $porcentaje = $_REQUEST["porcentaje_descuento"];
        $factor = $_REQUEST["factor"];
        $plazo = $_REQUEST["dias"];
        $observaciones = "Fecha:". date("Y-m-d") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones_condiciones']."<hr/>";

        try
        {
            if ($idRefPagador == 0){

                $strSQL = "INSERT INTO clientes_ref_pagador(id_cliente, id_pagador, porcentaje_descuento, factor, plazo, observaciones) VALUES (".$idCliente.", ".$idPagador.", '".$porcentaje."', '".$factor."','".$plazo."','".$observaciones."')";
            }
            else{
                $strSQL = "UPDATE clientes_ref_pagador SET id_pagador=".$idPagador.", porcentaje_descuento='".$porcentaje."', factor='".$factor."', plazo='".$plazo."',observaciones=CONCAT('".$observaciones."',COALESCE(clientes_ref_pagador.observaciones,''))
                            WHERE id_ref_pagador=" . $idRefPagador;
            }
            $db->Execute($strSQL);
            $jsondata['Success'] = true;
        }
        catch(Exception $e){
            echo 'Excepci�n capturada: ',  $e->getMessage(), "\n";
            $jsondata['Success'] = false;
        }



        echo json_encode($jsondata);
        exit;

    }

    function deleteReferenciaPagador(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idRefPagador = $_REQUEST["id_ref_pagador"];

        $clientesRefPagador = new clientes_ref_pagador();
        $loadReg = $clientesRefPagador->load("id_ref_pagador=".$idRefPagador);

        if ($clientesRefPagador->validarIntegridadReferencial($clientesRefPagador->id_pagador)){
            $clientesRefPagador->Delete();
            $jsondata['Success'] = true;
        }
        else{
            $jsondata['Success'] = false;
        }

        echo json_encode($jsondata);
        exit;
    }

 	/**
     * Funciòn para ver reporte formato de oferta
     */
    function VerReporte() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/zonificacion/class_zonificacion_extended.php");
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);
        
        //TRAEMOS LAS CIUDADES
        $ciudad = new ciudades();
        $arrCiudades = $ciudad->getCiudades();     
        
        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT ref.id_ref_pagador, pg.razon_social, pg.identificacion, pg.digito_verificacion, ref.porcentaje_descuento, ref.factor, ref.plazo
                   FROM clientes_ref_pagador as ref
                   INNER JOIN clientes as pg ON ref.id_pagador = pg.id_cliente
        		   WHERE ref.id_cliente=".$idCliente." 
        		   ORDER BY  pg.razon_social ASC";        

		$rsData = $db->Execute($strSQL);
		
        include("./modules/clientes/templates/reporte_formato_oferta.php");
    }



    /**
     * Funciòn para ver reporte formato de pagare
     */
    function VerFormatoPagare() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/zonificacion/class_zonificacion_extended.php");
        require_once("./modules/generales/NumeroALetras.php");

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $esReporte = $_REQUEST["es_reporte"];
        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);

        $nom_depatamento = '';

        $strSQL = "SELECT departamento FROM departamentos WHERE id_departamento = ".$this->id_departamento;
        $rsDatos = $db->Execute($strSQL);

        $nomDepartamento = $rsDatos->fields["departamento"];

        //TRAEMOS LAS CIUDADES
        $ciudad = new ciudades();
        $arrCiudades = $ciudad->getCiudades();

        include("./modules/clientes/templates/reporte_formato_pagare.php");
    }


    /**
     * Funciòn para ver reporte formato carta de instrucciones
     */
    function VerCartaInstrcciones() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/zonificacion/class_zonificacion_extended.php");
        require_once("./modules/generales/NumeroALetras.php");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);

        $nom_depatamento = '';

        $strSQL = "SELECT departamento FROM departamentos WHERE id_departamento = ".$this->id_departamento;
        $rsDatos = $db->Execute($strSQL);

        $nomDepartamento = $rsDatos->fields["departamento"];

        //TRAEMOS LAS CIUDADES
        $ciudad = new ciudades();
        $arrCiudades = $ciudad->getCiudades();

        include("./modules/clientes/templates/reporte_carta_instrucciones.php");
    }

	/**
     * Funci�n para editar la vinculacion
     */
    function EditarVinculacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $sql = "SELECT * FROM clientes WHERE (identificacion = '".$_REQUEST['documento']."')";
        $rs = $db->Execute($sql);
        if($rs->EOF){

            $respuesta = $this->editDataVinculacion();
            if ($respuesta){
                $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
                $jsondata['Success'] = true;
            }
            else{
                $jsondata['Message'] = "Se ha presentado un error en la vinculacion. Si el error persiste comuniquese con nosotros";
                $jsondata['Success'] = false;
            }

        }else{
            $jsondata['Message'] = "El documento de identificacion de su empresa ya se encuentra registrado. Verifique.";
            $jsondata['Success'] = false;
        }

        echo json_encode($jsondata);
        exit;
    }

	/**
     * Funci�n para editar informacion de la vinculacion
     */
    function editDataVinculacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $respuesta = false;
		$seguimiento = new clientes_seguimiento();
		$clienteAdicional = new clientes_adicionales();

        $loadReg = $this->load("id_cliente=".$_POST['id_cliente']);

        $this->tipo_empresa = $_POST['tipo_empresa'];
        $this->tipo_empresa1 = $_POST['tipo_empresa1'];
        $this->declaracion_origen_fondos = utf8_decode($_POST['declaracion_origen_fondos']);
        $this->moneda_extranjera = $_POST['moneda_extranjera'];
        $this->transaccion_moneda = utf8_decode($_POST['transaccion_moneda']);
        $this->cuentas_moneda_extranjera = $_POST['cuentas_moneda_extranjera'];
		$this->banco_me = "";
		$this->cuenta_nro_me = "";
		$this->moneda_me = "";
		$this->ciudad_me = "";
		$this->pais_me = "";
        if ($this->cuentas_moneda_extranjera == 1){
			$this->banco_me = $_POST['banco_me'];
			$this->cuenta_nro_me = $_POST['cuenta_me'];
			$this->moneda_me = $_POST['moneda_me'];
			$this->ciudad_me = $_POST['ciudad_me'];
			$this->pais_me = $_POST['pais_me'];
        }
        $this->detalle_producto = utf8_decode($_POST['detalle_producto']);

        try
        {
            $this->Save();
            $respuesta = true;
			$idCliente = $this->id_cliente;

			//DATOS ADICIONALES DEL CLIENTE
        	$loadReg1 = $clienteAdicional->load("id_cliente=".$idCliente);
        	$clienteAdicional->id_cliente = $idCliente;
        	$clienteAdicional->id_sector = $_POST["id_sector"];
        	$clienteAdicional->id_ciiu = $_POST["id_ciiu"];
        	$clienteAdicional->numero_empleados = $_POST["id_numero_empleados"];
        	$clienteAdicional->id_como_se_entera = $_POST["id_referencia"];
        	$clienteAdicional->evolucion_vta_anio_anterior = $_POST["evolucion_vta_anio_anterior"];
        	$clienteAdicional->evolucion_vta_anio_actual = $_POST["evolucion_vta_anio_actual"];
        	$clienteAdicional->gran_contribuyente = $_POST["gran_contribuyente"];
        	$clienteAdicional->autoretenedor = $_POST["autoretenedor"];
       		$clienteAdicional->tarifa_autoretenedor = $_POST["tarifa_autoretenedor"]; //ES TARIFA GESTION REFERENCIACION
        	$clienteAdicional->rete_iva = $_POST["rete_iva"];
        	$clienteAdicional->tarifa_iva = null;
        	if ($clienteAdicional->rete_iva == 1)
        		$clienteAdicional->tarifa_iva = $_POST["tarifa_iva"];

        	$clienteAdicional->rete_ica = $_POST["rete_ica"];
        	$clienteAdicional->tarifa_ica = null;
        	if ($clienteAdicional->rete_ica == 1)
        		$clienteAdicional->tarifa_ica = $_POST["tarifa_ica"];

			$clienteAdicional->rtf_intereses = $_POST["rtf_intereses"];
        	$clienteAdicional->recursos_publicos = $_POST["recursos_publicos"];
        	$clienteAdicional->Save();

        	//GUARDAMOS LAS REFERENCIAS
        	$totalReferencias = $_POST["item_referencia"];
        	for ($i=1;$i<=$totalReferencias;$i++){

				$clienteReferencia = new clientes_referencias();
				$idClienteReferencia = 0;
				if ($_REQUEST["id_cliente_referencia" . $i] != "")
					$idClienteReferencia = $_REQUEST["id_cliente_referencia" . $i];

				$loadReg = $clienteReferencia->load("id_cliente_referencia=".$idClienteReferencia);
				$clienteReferencia->id_cliente = $idCliente;
				$clienteReferencia->empresa = utf8_decode($_REQUEST["ref_empresa" . $i]);
				$clienteReferencia->nit = $_REQUEST["ref_nit" . $i];
				$clienteReferencia->porcentaje_vtas = $_REQUEST["porcentaje_vtas" . $i];
				$clienteReferencia->id_plazo_pago = $_REQUEST["id_plazo_pago" . $i];
				$clienteReferencia->descontar_facturas = $_REQUEST["descontar_facturas" . $i];
				$clienteReferencia->monto_descuento = null;
				if ($clienteReferencia->descontar_facturas==1)
					$clienteReferencia->monto_descuento = $_REQUEST["monto_descuento" . $i];
				$clienteReferencia->id_relacion_comercial = $_REQUEST["id_relacion_comercial" . $i];
				$clienteReferencia->telefono = "";
				$clienteReferencia->contacto = "";
				$clienteReferencia->tipo_referencia = 1;
				$clienteReferencia->Save();
        	}

        	//GUARDAMOS SOCIOS, ACCIONISTAS, BENEFICIARIOS
        	$totalSocios = $_POST["item_socio"];
        	for ($i=1;$i<=$totalSocios;$i++){

				$socioBeneficiario = new clientes_socios_accionistas();
				$idClienteSocio = 0;
				if ($_REQUEST["id_socio_accionista" . $i] != "")
					$idClienteSocio = $_REQUEST["id_socio_accionista" . $i];

				$loadReg = $socioBeneficiario->load("id_socio_accionista=".$idClienteSocio);
				$socioBeneficiario->id_cliente = $idCliente;
				$socioBeneficiario->tipo_persona = $_REQUEST["tipo_persona" . $i];
				$socioBeneficiario->id_tipo_documento = $_REQUEST["id_tipo_documento" . $i];
				$socioBeneficiario->identificacion = $_REQUEST["identificacion" . $i];
				$socioBeneficiario->id_pais = $_REQUEST["id_pais" . $i];
				$socioBeneficiario->fecha_expedicion = $_REQUEST["fecha_expedicion" . $i];
				$socioBeneficiario->razon_social = utf8_decode($_REQUEST["razon_social" . $i]);
				$socioBeneficiario->pais_ubicacion = utf8_decode($_REQUEST["pais_ubicacion" . $i]);
				$socioBeneficiario->politicamente_expuesta = $_REQUEST["politicamente_expuesta" . $i];
				$socioBeneficiario->tipo_vinculacion_persona = $_REQUEST["tipo_vinculacion_persona" . $i];
				$socioBeneficiario->comentario = $_REQUEST["comentario" . $i];
				$socioBeneficiario->socio_beneficiario = $_REQUEST["beneficiario_final" . $i];
				$socioBeneficiario->activo = 1;
				$socioBeneficiario->Save();
        	}

            $idCliente = $this->id_cliente;

			$loadReg1 = $seguimiento->load("id_cliente_seguimiento=0");
			$seguimiento->id_cliente = $idCliente;
			$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
			$seguimiento->id_usuario = $_SESSION["id_user"];
			$seguimiento->id_estado = $this->activo;
			$seguimiento->observaciones = "ACTUALIZACION DE INFORMACION ADICIONAL DEL CLIENTE";
			$seguimiento->es_tarea = 2;
			$seguimiento->Save();

        }
        catch(Exception $e){
            $respuesta = false;
        }

        return $respuesta;
    }

    /**
     * Funci�n para guardar las condiciones
     */
    function saveCondiciones() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$idCliente = $_POST['id_cliente'];

        $loadReg = $this->load("id_cliente=".$idCliente);

        $this->factor = $_POST['factor'];
        $this->porcentaje_descuento = $_POST['porcentaje_descuento'];
        $this->plazo = $_POST['dias'];
        $observaciones = "Fecha:". date("Y-m-d") . "<br/>Usuario:".$_SESSION["user"]."<br/>Observaciones:".$_POST['observaciones_condiciones']."<hr/>";
        $this->observaciones = $observaciones . ($this->observaciones!=null?$this->observaciones:"");
        $this->Save();

		$jsondata['Message'] = "Transaccion exitosa.";
		$jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funci�n para ver el formulario de condiciones
     */
    function condiciones() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

		$resolucion = new clientes_res_facturas();
		$resolucion2 = new clientes_res_facturas();

        $idCliente = $_REQUEST["id_cliente"];

        $loadReg = $this->load("id_cliente=".$idCliente);
        $loadReg2 = $resolucion->load("id_cliente=".$idCliente . " AND registro=1");
        $loadReg3 = $resolucion2->load("id_cliente=".$idCliente . " AND registro=2");

        include("./modules/clientes/templates/cliente_condiciones.php");

    }

    /**
     * Funci�n para ver el formulario de registrar un cliente
     */
    function exportarClientes() {

        global $db,$id,$appObj,$LANG;

 		//TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT
        		   c.id_cliente,
        		   tt.nombre,
        		   c.identificacion,
        		   c.razon_social,
        		   c.correo_personal,
        		   c.telefono_fijo,
        		   c.telefono_fijo1,
        		   c.telefono_celular,
        		   c.telefono_celular1,
        		   c.fecha_registro,
        		   c.ciudad,
        		   c.direccion,
        		   c.cupo,
                   e.razon_social as ejecutivo,
                   c.comision,
        		   c.activo
        		   FROM clientes as c
        		   INNER JOIN tipo_tercero as tt ON c.id_tipo_tercero = tt.id_tipo_tercero
                   LEFT JOIN clientes as e ON c.id_ejecutivo = e.id_cliente
                   ";

        $strSQL .= " WHERE 1=1";

        //FILTRO TIPO TERCERO
        if ($_REQUEST["idTipoTercero"] != "")
             $strSQL .= " AND tt.id_tipo_tercero=" . $_REQUEST["idTipoTercero"];

        $strSQL .= " ORDER BY  c.id_cliente DESC";

        $rsData = $db->Execute($strSQL);

        include("./modules/clientes/templates/exportar_clientes.php");

    }

    function aprobarDocumento(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idDocumento = $_REQUEST["id_documento"];

        $clienteDocumento = new clientes_documentos();

        $loadReg = $clienteDocumento->load("id_cliente_documento=".$idDocumento);

		$success = false;
		if ($clienteDocumento->a�o != '' && $clienteDocumento->a�o != '0'){

			$clienteDocumento->id_estado = 2;
			$clienteDocumento->id_usuario = $_SESSION["id_user"];
			$observaciones = "Fecha: ". date("Y-m-d H:i:s") . " - Usuario: ".$_SESSION["user"]."<br/>Observaciones:".$_REQUEST['observaciones']."<hr/>";
			$clienteDocumento->observaciones = strtoupper($observaciones) . $clienteDocumento->observaciones;
			$clienteDocumento->Save();
			$success = true;
        }

		$jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;
    }

    function deleteDocumento(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idDocumento = $_REQUEST["id_documento"];

        $clienteDocumento = new clientes_documentos();

        $loadReg = $clienteDocumento->load("id_cliente_documento=".$idDocumento);

        $clienteDocumento->Delete();

        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

	/**
     * Funci�n para ver el formulario de registrar un cliente
     */
    function saveDocumento() {

        global $db,$id,$appObj,$LANG;

        $idDocumento = $_REQUEST["id_documento"];
        $idCliente = $_REQUEST["id_cliente"];
        $ano = $_REQUEST["ano"];
        $periodo = $_REQUEST["periodo"];
        $usuario = $_SESSION["id_user"];
        $fechaVencimiento = null;
        if ($_REQUEST["fecha_vencimiento"] != "")
        	$fechaVencimiento = "'".$_REQUEST["fecha_vencimiento"]."'";
        $observaciones = "Fecha: ". date("Y-m-d H:i:s") . " - Usuario: ".$_SESSION["user"]."<br/>Observaciones:".$_REQUEST['observaciones']."<hr/>";

        try
        {
            //GUARDAMOS ARCHIVOS
            if ($_FILES['file_documento']['tmp_name'] != "")
            {
				$fh = fopen($_FILES['file_documento']['tmp_name'], 'r');
				$fileCargaContenido = fread($fh, filesize($_FILES['file_documento']['tmp_name']));
				$fileCargaContenido = addslashes($fileCargaContenido);
				$tipo = $_FILES['file_documento']['type'];
				$tamano = filesize($_FILES['file_documento']['tmp_name']);

				if ($idDocumento == 0){
					$strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, a�o, periodo, id_usuario, fecha, observaciones, fecha_vencimiento) VALUES (".$idCliente.", ".$_REQUEST["id_tipo_documento"].",'".$fileCargaContenido."', '".$tipo."', 2,'".$ano."','".$periodo."','".$usuario."','".date("Y-m-d")."','".$observaciones."',".($fechaVencimiento!=null?$fechaVencimiento:'null').")";
				}
				else{
					$strSQL = "UPDATE clientes_documentos SET id_tipo_documento=".$_REQUEST["id_tipo_documento"].", archivo='".$fileCargaContenido."', tipo_archivo='".$tipo."', a�o='".$ano."',periodo='".$periodo."',id_usuario=".$usuario.", observaciones = concat('".$observaciones."',COALESCE(observaciones,'')), fecha_vencimiento=".($fechaVencimiento!=null?$fechaVencimiento:'null')." WHERE id_cliente_documento=" . $idDocumento;
				}
            }
            else{
            	$strSQL = "UPDATE clientes_documentos SET id_tipo_documento=".$_REQUEST["id_tipo_documento"].", a�o='".$ano."',periodo='".$periodo."',id_usuario=".$usuario.", observaciones = concat('".$observaciones."',COALESCE(observaciones,'')), fecha_vencimiento=".($fechaVencimiento!=null?$fechaVencimiento:'null')." WHERE id_cliente_documento=" . $idDocumento;
            }

            $db->Execute($strSQL);
        }
        catch(Exception $e){
            $mensaje = "Excepci�n capturada: ".  $e->getMessage();
        }

        $jsondata['Success'] = true;
        $jsondata['Msg'] = $mensaje;

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funci�n para ver el formulario de registrar un cliente
     */
    function documento() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./modules/tipoDocumentos/class_tipoDocumentos_extended.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

		$idDocumento = $_REQUEST["id_documento"];
        $idCliente = $_REQUEST["id_cliente"];

        $clienteDocumento = new clientes_documentos();
        $documentos = new tipo_documento();

        $loadReg = $this->load("id_cliente=".$idCliente);
        $loadReg1 = $clienteDocumento->load("id_cliente_documento=".$idDocumento);

        //TRAEMOS LOS TIPOS DOCUMENTO POR TERCERO
        $idTipoTerceroP = $this->id_tipo_tercero;
        $idTipoTerceroS = $this->id_tipo_tercero_sec;

        $arrTiposDocumento = $documentos->obtenerDocumentosPorTipoTercero($idTipoTerceroP);
        //$arrTiposDocumento = $documentos->obtenerDocumentosPorTipoTercero($idTipoTerceroS);

        $a�os = new a�os();
        $arrA�os = $a�os->getA�os();

        include("./modules/clientes/templates/cliente_documento.php");

    }

    /**
     * Funci�n para ver el formulario de registrar un cliente
     */
    function verInformacionAnexa() {

        global $db,$id,$appObj,$LANG;

		//INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/zonificacion/class_zonificacion_extended.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

		//INSTANCIAMOS CLASES
		$pais = new paises();
		$ciudad = new ciudades();
		$sector = new sectores();
		$ciuu = new ciiu();
		$referencia = new referencia_argenta();
		$empleado = new numero_empleados();
		$plazoPago = new plazo_pago();
		$relacionComercial = new relacion_comercial();
		$clienteReferencia = new clientes_referencias();
		$clienteSocio = new clientes_socios_accionistas();
		$clienteAdicional = new clientes_adicionales();

        //TRAEMOS DATOS
        $arrNumeros = $appObj->getNumeros(0,100);
        $arrPaises = $pais->getPaises();
        $arrPaisesDesc = $pais->getPaisesDesc();
        $arrCiudades = $ciudad->getCiudades();
        $arrSectores = $sector->getSectores();
        $arrCiius = $ciuu->getCiius();
        $arrReferencias = $referencia->getReferencias();
        $arrNumEmpleados = $empleado->getNumeroEmpleados();
        $arrPlazoPago = $plazoPago->getPlazosPago();
        $arrRelacionesComercial = $relacionComercial->getRelacionComercial();
        $arrTipoDocumento = array("1"=>"RUT","2"=>"C�dula extranjer�a", "3"=>"NIT","4"=>"C�dula ciudadan�a");


        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);
        $loadReg1 = $clienteAdicional->load("id_cliente=".$idCliente);

		//OBTENEMOS LAS REFERENCIAS - CLIENTES
		$rsRefClientes = $clienteReferencia->obtenerReferenciaCliente($idCliente, 1);

		//OBTENEMOS LOS SOCIOS - ACCIONISTAS - BENEFICIARIOS
		$rsSocios = $clienteSocio->obtenerSociosCliente($idCliente);

        include("./modules/clientes/templates/informacion_anexa.php");

    }

	/**
     * Funci�n para ver guardar la vinculacion
     */
    function listDocumentosCliente() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/select.php");

        $idCliente = $_REQUEST["id_cliente"];
        //DETERMINAMOS SI ES EL PERFIL CLIENTE PARA TOMAR EL DATO DE LA SESION
        if ($_SESSION["profile_text"] == "Cliente")
        	$idCliente = $_SESSION["id_tercero"];

        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT cd.fecha,cd.id_cliente_documento, td.tipo_documento, cd.registro, concat(cd.a�o,'-',cd.periodo) as anio,concat_ws(' ', users.nombres, users.apellidos) as usuario, cd.id_estado, cd.fecha_vencimiento
        FROM clientes_documentos as cd INNER JOIN tipo_documento AS td ON cd.id_tipo_documento = td.id_tipo_documento
        LEFT JOIN users ON cd.id_usuario = users.id_usuario";
        $strSQL .= " WHERE cd.id_cliente = " . $idCliente;
        $strSQL .= " ORDER BY td.tipo_documento";

        $rsDocumentos = $db->Execute($strSQL);

        include("./modules/clientes/templates/listado_documentos.php");

    }

    /**
     * Funci�n para ver guardar la vinculacion
     */
    function verDocumentoCliente() {

		global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$clientesDocumentos = new clientes_documentos();

		$idClienteDocumento = $_REQUEST["id_cliente_documento"];

		$loadReg = $clientesDocumentos->load("id_cliente_documento=".$idClienteDocumento);

		$archivo = $clientesDocumentos->archivo;

		if ($clientesDocumentos->tipo_archivo == "application/pdf"){
			header('Content-type: application/pdf');
			echo $archivo;
		}
		else{
			echo '<img src="data:'.$clientesDocumentos->tipo_archivo.';base64,'.base64_encode($archivo).'"/>';
		}
		exit;
   	}

    /**
     * Funci�n para ver guardar la vinculacion
     */
    function guardarVinculacion() {

		global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $sql = "SELECT * FROM clientes WHERE (identificacion = '".$_REQUEST['documento']."')";
        $rs = $db->Execute($sql);
        if($rs->EOF){

            $respuesta = $this->saveDataVinculacion();
            if ($respuesta["Success"]){
                $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
                $jsondata['IdCliente'] = $respuesta["IdCliente"];
                $jsondata['Success'] = true;
            }
            else{
            	$jsondata['Message'] = "Se ha presentado un error en la vinculacion. Si el error persiste comuniquese con nosotros";
            	$jsondata['Success'] = false;
            }

        }else{
            $jsondata['Message'] = "El documento de identificacion de su empresa ya se encuentra registrado. Verifique.";
            $jsondata['Success'] = false;
        }

        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funci�n para ver actualizar la vinculacion
     */
    function actualizarVinculacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idCliente = $_POST['id_cliente'];
        $sql = "SELECT * FROM clientes WHERE (identificacion = '".$_REQUEST['documento']."')";
        $rs = $db->Execute($sql);
        if($rs->EOF || $idCliente>0){

            $respuesta = $this->saveDataVinculacion();
            if ($respuesta["Success"]){
                $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
                $jsondata['IdCliente'] = $respuesta["IdCliente"];
                $jsondata['Success'] = true;
            }
            else{
                $jsondata['Message'] = "Se ha presentado un error en la vinculacion. Si el error persiste comuniquese con nosotros";
                $jsondata['Success'] = false;
            }

        }else{
            $jsondata['Message'] = "El documento de identificacion de su empresa ya se encuentra registrado. Verifique.";
            $jsondata['Success'] = false;
        }

        echo json_encode($jsondata);
        exit;

    }

	/**
     * Funci�n para guardar informacion de la vinculacion
     */
    function saveDataVinculacion() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/class_send_mail.php");

        //INSTANCIAMOS CLASES
        $sendMail = new sendMail();
        $terceros = new tipo_terceros();
        $clienteAdicional = new clientes_adicionales();
        $seguimiento = new clientes_seguimiento();

        //TRAEMOS LOS TIPOS TERCEROS
        $arrTerceros = $terceros->getTerceros();

		$respuesta = 0;
		$idCliente = $_POST['id_cliente'];

        $loadReg = $this->load("id_cliente=".$idCliente);

        if ($this->id_cliente == 0){
            $this->id_tipo_tercero = 7; //REGISTRO WEB
            $this->fecha_registro = date("Y-m-d");
            $this->cupo = 0;
            $this->activo = 0;
            $observacionesSeguimiento = "CREACION DE CLIENTE POR PAGINA WEB";
        }
        else{
            //BORRAMOS REFERENCIAS
            $strSQL = "DELETE FROM clientes_referencias WHERE id_cliente=".$this->id_cliente;
            $db->Execute($strSQL);

			//BORRAMOS SOCIOS
            $strSQL = "DELETE FROM clientes_socios_accionistas WHERE id_cliente=".$this->id_cliente;
            $db->Execute($strSQL);

            $observacionesSeguimiento = "ACTUALIZACION DE CLIENTE POR PAGINA WEB";
        }

        $this->razon_social = utf8_decode($_POST['razon_social']);
        $this->tipo_identificacion = $_POST['Tipo'];
        $this->identificacion = $_POST['documento'];
        $this->digito_verificacion = $_POST['digito_verificacion'];
        $this->fecha_consticucion = $_POST['fecha_constitucion'];
        $this->id_pais = $_POST['id_pais'];
        $this->id_departamento = $_POST['id_departamento'];
        $this->ciudad = $_POST['id_ciudad'];
        $this->telefono_fijo = $_POST['fijo'];
        $this->telefono_celular = $_POST['celular'];
        $this->direccion = utf8_decode($_POST['direccion']);
        $this->correo_personal = $_POST['correo_personal'];
        $this->representante_legal = utf8_decode($_POST['representante_legal']);
        $this->identificacion_representante = $_POST['documento_representante'];
        $this->id_ciudad_expedicion = $_POST['id_ciudad_expedicion'];
        $this->tipo_empresa = $_POST['tipo_empresa'];
        $this->tipo_empresa1 = $_POST['tipo_empresa1'];
        $this->declaracion_origen_fondos = utf8_decode($_POST['declaracion']);
        $this->moneda_extranjera = $_POST['modeda_extranjera'];
        $this->transaccion_moneda = utf8_decode($_POST['transaccion_moneda']);
        $this->cuentas_moneda_extranjera = $_POST['ctas_modeda_extranjera'];
		$this->banco_me = "";
		$this->cuenta_nro_me = "";
		$this->moneda_me = "";
		$this->ciudad_me = "";
		$this->pais_me = "";
        if ($this->cuentas_moneda_extranjera == 1){
			$this->banco_me = $_POST['banco_me'];
			$this->cuenta_nro_me = $_POST['cuenta_me'];
			$this->moneda_me = $_POST['moneda_me'];
			$this->ciudad_me = $_POST['ciudad_me'];
			$this->pais_me = $_POST['pais_me'];
        }
        $this->detalle_producto = utf8_decode($_POST['detalle_producto']);
        $this->autorizado_por = utf8_decode($_POST['autorizacion']);
        $this->encargado = utf8_decode($_POST['encargado']);
        $this->telefonos_encargado = $_POST['telefonos_encargado'];
        $this->cargo_autorizador = $_POST['cargo_autorizador'];
        $this->permite_actualizar = 2;

		try
		{
        	$this->Save();
        	$respuesta = true;
        	$idCliente = $this->id_cliente;

			//DATOS ADICIONALES DEL CLIENTE
        	$loadReg1 = $clienteAdicional->load("id_cliente=".$idCliente);
        	$clienteAdicional->id_cliente = $idCliente;
        	$clienteAdicional->id_sector = $_POST["id_sector"];
        	$clienteAdicional->id_ciiu = $_POST["id_ciiu"];
        	$clienteAdicional->numero_empleados = $_POST["id_numero_empleados"];
        	$clienteAdicional->id_como_se_entera = $_POST["id_referencia"];
        	$clienteAdicional->evolucion_vta_anio_anterior = $_POST["evolucion_vta_anio_anterior"];
        	$clienteAdicional->evolucion_vta_anio_actual = $_POST["evolucion_vta_anio_actual"];
        	$clienteAdicional->gran_contribuyente = $_POST["gran_contribuyente"];
        	$clienteAdicional->autoretenedor = $_POST["autoretenedor"];
        	$clienteAdicional->rete_iva = $_POST["rete_iva"];
        	$clienteAdicional->rete_ica = $_POST["rete_ica"];
        	$clienteAdicional->tarifa_ica = null;
        	if ($clienteAdicional->rete_ica == 1)
        		$clienteAdicional->tarifa_ica = $_POST["tarifa_ica"];
        	$clienteAdicional->recursos_publicos = $_POST["recursos_publicos"];
        	$clienteAdicional->Save();

        	//GUARDAMOS LAS REFERENCIAS
        	$totalReferencias = $_POST["item_referencia"];
        	for ($i=1;$i<=$totalReferencias;$i++){

				$clienteReferencia = new clientes_referencias();
				$loadReg = $clienteReferencia->load("id_cliente_referencia=0");
				$clienteReferencia->id_cliente = $idCliente;
				$clienteReferencia->empresa = utf8_decode($_REQUEST["ref_empresa" . $i]);
				$clienteReferencia->nit = $_REQUEST["ref_nit" . $i];
				$clienteReferencia->porcentaje_vtas = $_REQUEST["porcentaje_vtas" . $i];
				$clienteReferencia->id_plazo_pago = $_REQUEST["id_plazo_pago" . $i];
				$clienteReferencia->descontar_facturas = $_REQUEST["descontar_facturas" . $i];
				$clienteReferencia->monto_descuento = null;
				if ($clienteReferencia->descontar_facturas==1)
					$clienteReferencia->monto_descuento = $_REQUEST["monto_descuento" . $i];
				$clienteReferencia->id_relacion_comercial = $_REQUEST["id_relacion_comercial" . $i];
				$clienteReferencia->telefono = "";
				$clienteReferencia->contacto = "";
				$clienteReferencia->tipo_referencia = 1;
				$clienteReferencia->Save();
        	}

        	//GUARDAMOS SOCIOS, ACCIONISTAS, BENEFICIARIOS
        	$totalSocios = $_POST["item_socio"];
        	for ($i=1;$i<=$totalSocios;$i++){

				$socioBeneficiario = new clientes_socios_accionistas();
				$loadReg = $socioBeneficiario->load("id_socio_accionista=0");
				$socioBeneficiario->id_cliente = $idCliente;
				$socioBeneficiario->tipo_persona = $_REQUEST["tipo_persona" . $i];
				$socioBeneficiario->id_tipo_documento = $_REQUEST["id_tipo_documento" . $i];
				$socioBeneficiario->identificacion = $_REQUEST["identificacion" . $i];
				$socioBeneficiario->id_pais = null;
				$socioBeneficiario->fecha_expedicion = null;
				$socioBeneficiario->razon_social = utf8_decode($_REQUEST["razon_social" . $i]);
				$socioBeneficiario->pais_ubicacion = utf8_decode($_REQUEST["pais_ubicacion" . $i]);
				$socioBeneficiario->politicamente_expuesta = $_REQUEST["politicamente_expuesta" . $i];
				$socioBeneficiario->tipo_vinculacion_persona = $_REQUEST["tipo_vinculacion_persona" . $i];
				$socioBeneficiario->comentario = "";
				$socioBeneficiario->socio_beneficiario = $_REQUEST["beneficiario_final" . $i];
				$socioBeneficiario->activo = 1;
				$socioBeneficiario->Save();
        	}

        	//GUARDAMOS ARCHIVOS
        	if ($_FILES['file_rut']['tmp_name'] != ""){
				$fh = fopen($_FILES['file_rut']['tmp_name'], 'r');
				$fileCargaContenido = fread($fh, filesize($_FILES['file_rut']['tmp_name']));
				$fileCargaContenido = addslashes($fileCargaContenido);
				$tipo = $_FILES['file_rut']['type'];
				$tamano = filesize($_FILES['file_rut']['tmp_name']);
				$strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
							(".$idCliente.",1,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
				$db->Execute($strSQL);
			}

			if ($_FILES['file_financieros']['tmp_name'] != ""){
				$fh = fopen($_FILES['file_financieros']['tmp_name'], 'r');
				$fileCargaContenido = fread($fh, filesize($_FILES['file_financieros']['tmp_name']));
				$fileCargaContenido = addslashes($fileCargaContenido);
				$tipo = $_FILES['file_financieros']['type'];
				$tamano = filesize($_FILES['file_financieros']['tmp_name']);
				$strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
							(".$idCliente.",2,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
				$db->Execute($strSQL);
			}

            if ($_FILES['file_financieros_2']['tmp_name'] != ""){
                $fh = fopen($_FILES['file_financieros_2']['tmp_name'], 'r');
                $fileCargaContenido = fread($fh, filesize($_FILES['file_financieros_2']['tmp_name']));
                $fileCargaContenido = addslashes($fileCargaContenido);
                $tipo = $_FILES['file_financieros_2']['type'];
                $tamano = filesize($_FILES['file_financieros_2']['tmp_name']);
                $strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
                            (".$idCliente.",24,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
                $db->Execute($strSQL);
            }

			if ($_FILES['file_camara']['tmp_name'] != ""){
				$fh = fopen($_FILES['file_camara']['tmp_name'], 'r');
				$fileCargaContenido = fread($fh, filesize($_FILES['file_camara']['tmp_name']));
				$fileCargaContenido = addslashes($fileCargaContenido);
				$tipo = $_FILES['file_camara']['type'];
				$tamano = filesize($_FILES['file_camara']['tmp_name']);
				$strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
							(".$idCliente.",3,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
				$db->Execute($strSQL);
			}

            if ($_FILES['file_declaracion']['tmp_name'] != ""){
                $fh = fopen($_FILES['file_declaracion']['tmp_name'], 'r');
                $fileCargaContenido = fread($fh, filesize($_FILES['file_declaracion']['tmp_name']));
                $fileCargaContenido = addslashes($fileCargaContenido);
                $tipo = $_FILES['file_declaracion']['type'];
                $tamano = filesize($_FILES['file_declaracion']['tmp_name']);
                $strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
                            (".$idCliente.",4,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
                $db->Execute($strSQL);
			}

            if ($_FILES['file_accionaria']['tmp_name'] != ""){
				$fh = fopen($_FILES['file_accionaria']['tmp_name'], 'r');
				$fileCargaContenido = fread($fh, filesize($_FILES['file_accionaria']['tmp_name']));
				$fileCargaContenido = addslashes($fileCargaContenido);
				$tipo = $_FILES['file_accionaria']['type'];
				$tamano = filesize($_FILES['file_accionaria']['tmp_name']);
				$strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
							(".$idCliente.",5,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
				$db->Execute($strSQL);
        	}

            if ($_FILES['file_legal']['tmp_name'] != ""){
                $fh = fopen($_FILES['file_legal']['tmp_name'], 'r');
                $fileCargaContenido = fread($fh, filesize($_FILES['file_legal']['tmp_name']));
                $fileCargaContenido = addslashes($fileCargaContenido);
                $tipo = $_FILES['file_legal']['type'];
                $tamano = filesize($_FILES['file_legal']['tmp_name']);
                $strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
                            (".$idCliente.",6,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
                $db->Execute($strSQL);
        	}

            if ($_FILES['file_centrales']['tmp_name'] != ""){
                $fh = fopen($_FILES['file_centrales']['tmp_name'], 'r');
                $fileCargaContenido = fread($fh, filesize($_FILES['file_centrales']['tmp_name']));
                $fileCargaContenido = addslashes($fileCargaContenido);
                $tipo = $_FILES['file_centrales']['type'];
                $tamano = filesize($_FILES['file_centrales']['tmp_name']);
                $strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
                            (".$idCliente.",7,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
                $db->Execute($strSQL);
            }

            if ($_FILES['file_res_fac']['tmp_name'] != ""){
                $fh = fopen($_FILES['file_res_fac']['tmp_name'], 'r');
                $fileCargaContenido = fread($fh, filesize($_FILES['file_res_fac']['tmp_name']));
                $fileCargaContenido = addslashes($fileCargaContenido);
                $tipo = $_FILES['file_res_fac']['type'];
                $tamano = filesize($_FILES['file_res_fac']['tmp_name']);
                $strSQL = "INSERT INTO clientes_documentos(id_cliente, id_tipo_documento, archivo, tipo_archivo, registro, fecha) VALUES
                            (".$idCliente.",23,'".$fileCargaContenido."', '".$tipo."', 1, '".date("Y-m-d")."')";
                $db->Execute($strSQL);
            }
        }
        catch(Exception $e){
        	$respuesta = false;
        }

        $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
        $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
        $subjectMail = "Vinculaci�n portal web Argenta";
        $toNameMail = "Elvira Leyva;Jacobo Sanint";
        $toEmail = "eleyva@argentaestructuradores.com;jsanint@argentaestructuradores.com;coordinadora@argentaestructuradores.com";
        $templateMail = "mailTerceros";
        $observaciones = "Se ha realizado un registro desde la p�gina web por favor revisar la informaci�n adjunta.";

        //ARMAMOS LOS DATOS DEL TERCERO
        $arrTipos = array("1"=>"RUT","2"=>"CE", "3"=>"NIT","4"=>"CC");
        $datosTercero = "Tipo tercero: " . $arrTerceros[$this->id_tipo_tercero];
        $datosTercero .= "<br/>Tipo identificaci�n: " . $arrTipos[$this->tipo_identificacion];
        $datosTercero .= "<br/>Identificaci�n: " . $this->identificacion;
        $datosTercero .= "<br/>Raz�n social: " . $this->razon_social;
        $datosTercero .= "<br/>Representante legal: " . $this->representante_legal;
        $datosTercero .= "<br/>Ciudad: " . $this->ciudad;
        $datosTercero .= "<br/>Tel�fono fijo: " . $this->telefono_fijo;
        $datosTercero .= "<br/>Tel�fono celular: " . $this->telefono_celular;
        $datosTercero .= "<br/>Direcci�n: " . $this->direccion;
        $datosTercero .= "<br/>Correo electr�nico: " . $this->correo_personal;

        //ENVIAMOS EL CORREO
        $arrVarsReplace = array("DATOS_TERCERO"=>$datosTercero,"NOTA"=>$observaciones,"NAME"=>$toNameMail);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,$templateMail,array(),$arrVarsReplace);

		//ENVIAMOS EL CORREO AL TERCERO
		$subjectMail = "Confirmaci�n vinculaci�n portal web Argenta Estructuradores.";
		$templateMail = "mailTercerosExterno";
        $arrVarsReplace = array("DATOS_TERCERO"=>$datosTercero,"NAME"=>$this->representante_legal);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$this->representante_legal,$this->correo_personal,$subjectMail,$templateMail,array(),$arrVarsReplace);

		$loadReg1 = $seguimiento->load("id_cliente_seguimiento=0");
		$seguimiento->id_cliente = $idCliente;
		$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
        $seguimiento->id_usuario = -1;
        $seguimiento->id_estado = $this->activo;
        $seguimiento->observaciones = $observacionesSeguimiento;
        $seguimiento->es_tarea = 2;
        $seguimiento->Save();

		$arrRespuesta["Success"] = $respuesta;
		$arrRespuesta["IdCliente"] = $idCliente;
		return $arrRespuesta;

    }

    /**
     * Funci�n para ver el formulario de registrar un cliente
     */
    function vinculacion() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/zonificacion/class_zonificacion_extended.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

		//INSTANCIAMOS CLASES
		$arrNumeros = $appObj->getNumeros(0,100);
		$pais = new paises();
		$ciudad = new ciudades();
		$sector = new sectores();
		$ciuu = new ciiu();
		$referencia = new referencia_argenta();
		$empleado = new numero_empleados();
		$plazoPago = new plazo_pago();
		$relacionComercial = new relacion_comercial();

        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);

        //TRAEMOS DATOS
        $arrPaises = $pais->getPaises();
        $arrPaisesDesc = $pais->getPaisesDesc();
        $arrCiudades = $ciudad->getCiudades();
        $arrSectores = $sector->getSectores();
        $arrCiius = $ciuu->getCiius();
        $arrReferencias = $referencia->getReferencias();
        $arrNumEmpleados = $empleado->getNumeroEmpleados();
        $arrPlazoPago = $plazoPago->getPlazosPago();
        $arrRelacionesComercial = $relacionComercial->getRelacionComercial();
        $arrTipoDocumento = array("1"=>"RUT","2"=>"C�dula extranjer�a", "3"=>"NIT","4"=>"C�dula ciudadan�a");

		//DETERMINAMOS SI EST� INGRESANDO DESDE EL USUARIO LOGUEADO DE ARGENTA
		$cargarFormulario = "reg";
		if ($_SESSION["id_user"] != ""){
			$cargarFormulario = "act";
		}

        include("./modules/clientes/templates/cliente_vinculacion.php");

    }


    /**
     * Funci�n para mostrar formulario de buscador clientes
     */
    function searchClients(){

 		require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");

        //TRAEMOS LOS TIPOS TERCEROS
        $terceros = new tipo_terceros();
        $arrTerceros = $terceros->getTerceros();

		include("./modules/clientes/templates/consulta_clientes.php");
	}


    /**
     * Funci�n para obtener el listado de clientes
     */
    function listClients(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/select.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->tableDataId = "tableDataClientes";
        $dataGrid->heightDG = "300";

        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT c.id_cliente, CONCAT(tt.nombre,' ', COALESCE(tt2.nombre,'')) as tipo_tercero, c.identificacion, c.razon_social, c.telefono_fijo,c.telefono_celular,
        				  c.encargado, c.telefonos_encargado, c.fecha_registro, c.activo
                   FROM clientes as c
                   INNER JOIN tipo_tercero as tt ON c.id_tipo_tercero = tt.id_tipo_tercero
                   LEFT JOIN tipo_tercero as tt2 ON c.id_tipo_tercero_sec = tt2.id_tipo_tercero
                   WHERE 1=1
                   ";

        if ($_REQUEST["id_tipo_tercero_buscador"] != "")
            $strSQL .= " AND c.id_tipo_tercero = ".$_REQUEST["id_tipo_tercero_buscador"];

        if ($_REQUEST["estado_buscador"] != "")
            $strSQL .= " AND c.activo = ".$_REQUEST["estado_buscador"];

        if ($_REQUEST["razon_social_buscador"] != "")
            $strSQL .= " AND c.razon_social like '%".$_REQUEST["razon_social_buscador"]."%'";

        $strOrder = " ORDER BY  c.id_cliente DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Clientes</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
    
        
        //$dataGrid->addOptionsHeader("Agregar","javascript:editCliente(0,'clientes','client')","btn-primary","fa-plus-square");
        $dataGrid->addOptionsHeader("Exportar","javascript:exportarClientes()","btn-success","fa-download");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Tipo tercero","Identificaciin","Razin social /<br/>Nombres", "Telefonos", "Celular","Encargado", "Telefono", "Fecha Registro","Estado"));
        $dataGrid->searchColumn=false;

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_cliente"));

        //CAMPOS DE SOLO DOS VALORES
        $arrValues = array_keys($this->arrEstadosCliente);
        $arrText = array_values($this->arrEstadosCliente);
        $dataGrid->addFieldTwoValues("activo",$arrValues,$arrText);

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_cliente"=>"ID_CLIENTE","mod"=>"clientes","action"=>"client");
        $arrVarGet2 = Array("id_cliente"=>"ID_CLIENTE","mod"=>"clientes","action"=>"deleteClient");
        $arrVarGet3 = Array("id_cliente"=>"ID_CLIENTE");


        if ($appObj->tienePermisosAccion(array("eliminar_terceros")))
        {
            //Opcion a ejecutar si tiene el permiso
            $dataGrid->addColLink("Eliminar","<center><a href=\"javascript:{function};\"><img src='./images/eliminar.png' title='Eliminar Cliente' alt='Eliminar Cliente' border='0'/></a></center>","deleteCliente",$arrVarGet2,"functionjs","left");
        }
        
        if ($appObj->tienePermisosAccion(array("editar_terceros")))
        {
            //Opcion a ejecutar si tiene el permiso
            $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Cliente' alt='Editar Cliente' border='0'/></a></center>","editCliente",$arrVarGet1,"functionjs","left");
        }
       // $dataGrid->addColLink("Eliminar","<center><a href=\"javascript:{function};\"><img src='./images/eliminar.png' title='Eliminar Cliente' alt='Eliminar Cliente' border='0'/></a></center>","deleteCliente",$arrVarGet2,"functionjs","left");

       if ($appObj->tienePermisosAccion(array("agregar_terceros")))
       {
           //Opcion a ejecutar si tiene el permiso
           $dataGrid->addOptionsHeader("Agregar","javascript:editCliente(0,'clientes','client')","btn-primary","fa-plus-square");
        }


        $dataGrid->addColLink("Estudios de riesgo","<center><a href=\"javascript:{function};\" title='Estudio de riesgo'><li class='fa fa-bar-chart'></li></a></center>","cargarEstudios",$arrVarGet3,"functionjs","right");
        $dataGrid->addColLink("Reporte","<center><a href=\"javascript:{function};\" title='Enviar datos'><li class='fa fa-envelope'></li></a></center>","formDatosReporte",$arrVarGet3,"functionjs","right");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        //TRAEMOS LOS TIPOS TERCEROS
        $terceros = new tipo_terceros();
        $arrTerceros = $terceros->getTerceros();

        include("./modules/clientes/templates/listado_clientes.php");


    }

    /**
     * Funci�n para registrar un cliente
     */
    function saveClient() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $sql = "SELECT * FROM clientes WHERE activo in (0,1,4) AND (correo_personal= '".$_REQUEST['correo_personal']."' OR identificacion = '".$_REQUEST['documento']."') AND id_cliente<>'".$_POST["id_cliente"]."'";
        $rs = $db->Execute($sql);
        if($rs->EOF){

            $idCliente = $this->saveDataClient();
            $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
            $jsondata['IdCliente'] = $idCliente;
            $jsondata['Success'] = true;

        }else{
            $jsondata['Message'] = "La Identificacion o Correo ya se encuentran registrados.";
            $jsondata['Success'] = false;
            $jsondata['IdCliente'] = 0;
        }

        echo json_encode($jsondata);
        exit;
    }

    function saveDataClient(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		$seguimiento = new clientes_seguimiento();

        $loadReg = $this->load("id_cliente=".$_POST['id_cliente']);

        $this->id_tipo_tercero = $_POST['id_tipo_tercero'];
        $this->id_tipo_tercero_sec = $_POST['id_tipo_tercero_sec'];
        $this->razon_social = utf8_decode($_POST['razon_social']);
        $this->representante_legal = utf8_decode($_POST['representante_legal']);
        $this->tipo_identificacion = $_POST['Tipo'];
        $this->identificacion = trim($_POST['documento']);
        $this->identificacion_representante = $_POST['documento_representante'];
        $this->id_ciudad_expedicion = $_POST['id_ciudad_expedicion'];
        $this->digito_verificacion = $_POST['digito_verificacion'];
        $this->fecha_consticucion = $_POST['fecha_constitucion'];
        $this->correo_personal = $_POST['correo_personal'];
        $this->telefono_fijo = $_POST['telefono'];
        $this->telefono_fijo1 = $_POST['telefono1'];
        $this->telefono_celular = $_POST['celular'];
        $this->telefono_celular1 = $_POST['celular1'];
        $this->encargado = utf8_decode($_POST['encargado']);
        $this->telefonos_encargado = $_POST['telefonos_encargado'];
        $this->cargo_autorizador = $_POST['cargo_autorizador'];
        $this->id_departamento = $_POST['id_departamento'];
        $this->ciudad = $_POST['id_ciudad'];
        $this->direccion = $_POST['direccion'];
        $this->cupo = $_POST['cupo'];
        $this->id_ejecutivo = $_POST['id_ejecutivo'];
        $this->comision = $_POST['comision'];
        $this->permite_actualizar = $_POST['permite_actualizar'];
		$this->representante_supl = $_POST['representante_supl'];
		$this->identificacion_representante_supl = $_POST['identificacion_representante_supl'];
        $this->id_ciudad_exp_representante_supl = $_POST['id_ciudad_exp_representante_supl'];
        $observaciones = "ACTUALIZACION INFORMACION CLIENTE";
        if ($_POST['id_cliente'] == 0){
            $this->fecha_registro = date("Y-m-d");
            $this->activo = 0;
            $observaciones = "CREACI�N DE CLIENTE";
		}

        $this->Save();

		$loadReg1 = $seguimiento->load("id_cliente_seguimiento=0");
		$seguimiento->id_cliente =  $this->id_cliente;
		$seguimiento->fecha_proceso = date("Y-m-d h:i:s");
        $seguimiento->id_usuario = $_SESSION["id_user"];
        $seguimiento->id_estado = $this->activo;
        $seguimiento->observaciones = $observaciones;
        $seguimiento->es_tarea = 2;
        $seguimiento->Save();

        return  $this->id_cliente;

    }

    /**
     * Funci�n para ver el formulario de registrar un cliente
     */
    function client() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/zonificacion/class_zonificacion_extended.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/radio.php");
        require_once("./utilities/controles/textarea.php");

		//INSTANCIAMOS CLASES
		$verificacion = new clientes_verificaciones();
		$pais = new paises();
		$departamento = new departamentos();
        $ciudad = new ciudades();
        $terceros = new tipo_terceros();

        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);

 		$arrPaises = $pais->getPaises();

        //TRAEMOS LAS CIUDADES
        $arrCiudades = $ciudad->getCiudades();
        $jsonCiudades = $ciudad->ciudadesAllJson();

        //TRAEMOS LOS TIPOS TERCEROS
        $arrTerceros = $terceros->getTerceros();

        //OBTENEMOS LOS EJECUTIVOS
        $arrEjecutivos = $this->obtenerClientesPorTipoTercero(5);

		$ultimaFecha = $this->obtenerUltimaFechaOperacion($this->id_tipo_tercero, $idCliente);

        //TRAEMOS LOS DEPARTAMENTOS
        $arrDepartamentos = $departamento->getDepartamentosPorPais(44);

		$idPais = "";
		if ($this->id_departamento != ""){
			$strSQL = "SELECT id_pais FROM departamentos WHERE id_departamento = ".$this->id_departamento;
			$rsDatos = $db->Execute($strSQL);
			$idPais = $rsDatos->fields["id_pais"];
		}

		$verificacionSagrilaft = $verificacion->obtenerVerificacionClientePorTipo($idCliente,1);
		$verificacionCliente = $verificacion->obtenerVerificacionClientePorTipo($idCliente,2);
		$verificacionOperacion = $verificacion->obtenerVerificacionClientePorTipo($idCliente,3);

        include("./modules/clientes/templates/cliente.php");

    }

    function deleteClient(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $idCliente = $_REQUEST["id_cliente"];

        if ($this->validarIntegridadReferencial($idCliente)){
            $loadReg = $this->load("id_cliente=".$idCliente);
            $this->Delete();
            $jsondata['Success'] = true;
        }
        else{
            $jsondata['Success'] = false;
        }

        echo json_encode($jsondata);
        exit;
    }

    function obtenerClientes(){

        global $db;

        $arrclientes = array();

        $strSQL = "SELECT * FROM clientes ORDER BY razon_social";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrclientes[$rsDatos->fields["id_cliente"]] = $rsDatos->fields["razon_social"];
            $rsDatos->MoveNext();
        }

        return $arrclientes;

    }

    function obtenerClientesPorTipoTercero($idTipoTercero = 0){

        global $db;

        $arrclientes = array();

        $strSQL = "SELECT * FROM clientes WHERE (id_tipo_tercero = " . $idTipoTercero . " || id_tipo_tercero_sec = " . $idTipoTercero . ") ORDER BY razon_social";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrclientes[$rsDatos->fields["id_cliente"]] = $rsDatos->fields["razon_social"];
            $rsDatos->MoveNext();
        }

        return $arrclientes;
    }

    function obtenerClientesActivosPorTipoTercero($idTipoTercero = 0){

        global $db;

        $arrclientes = array();

        $strSQL = "SELECT * FROM clientes WHERE activo=1 AND (id_tipo_tercero = " . $idTipoTercero . " || id_tipo_tercero_sec = " . $idTipoTercero . ") ORDER BY razon_social";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrclientes[$rsDatos->fields["id_cliente"]] = $rsDatos->fields["razon_social"];
            $rsDatos->MoveNext();
        }

        return $arrclientes;

    }

    /**
     * Funci�n para enviar informacion de un cliente por correo
     */
    function sendDataClient() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./modules/generales/class_generales.php");
        require_once("./utilities/class_send_mail.php");

        $sendMail = new sendMail();

        //TRAEMOS LOS TIPOS TERCEROS
        $terceros = new tipo_terceros();
        $arrTerceros = $terceros->getTerceros();

        $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
        $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
        $subjectMail = "Informaci�n nuevo tercero Argenta";
        $toNameMail = $_REQUEST["nombre_to_email"];
        $toEmail = $_REQUEST["correo_to_email"];
        $templateMail = "mailTerceros";
        $observaciones = ($_REQUEST["observaciones_correo"] != ""?$_REQUEST["observaciones_correo"]:"N/D");

        $idCliente = $_REQUEST["id_cliente"];
        $loadReg = $this->load("id_cliente=".$idCliente);

        //ARMAMOS LOS DATOS DEL TERCERO
        $arrTipos = array("1"=>"RUT","2"=>"CE", "3"=>"NIT","4"=>"CC");
        $datosTercero = "Tipo tercero: " . $arrTerceros[$this->id_tipo_tercero];
        $datosTercero .= "<br/>Tipo identificaci�n: " . $arrTipos[$this->tipo_identificacion];
        $datosTercero .= "<br/>Identificaci�n: " . $this->identificacion;
        $datosTercero .= "<br/>Raz�n social: " . $this->razon_social;
        $datosTercero .= "<br/>Representante legal: " . $this->representante_legal;
        $datosTercero .= "<br/>Ciudad: " . $this->ciudad;
        $datosTercero .= "<br/>Tel�fono fijo: " . $this->telefono_fijo;
        $datosTercero .= "<br/>Tel�fono celular: " . $this->telefono_celular;
        $datosTercero .= "<br/>Direcci�n: " . $this->direccion;
        $datosTercero .= "<br/>Correo personal: " . $this->correo_personal;

        //ENVIAMOS EL CORREO
        $arrVarsReplace = array("DATOS_TERCERO"=>$datosTercero,"NOTA"=>$observaciones,"NAME"=>$toNameMail);
        $success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,$templateMail,array(),$arrVarsReplace);

        $jsondata['Message'] = "test";
        $jsondata['Success'] = $success;

        echo json_encode($jsondata);
        exit;

    }

    function obtenerCuposPagador($idPagador = 0){

        global $db;

        $arrDatos = array();

        $strSQL = "SELECT SUM(of.giro_antes_gmf) AS valorTotalInversiones, c.cupo
        			FROM operacion as o
        			INNER JOIN clientes as c ON o.id_pagador = c.id_cliente
        			INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    left JOIN operacion_reliquidacion as r ON of.id_reliquidacion = r.id_reliquidacion
                   WHERE of.estado in (1,4,6,8) AND (r.estado = 1 or r.estado is null) AND o.id_pagador=" . $idPagador;
        $rsDatos = $db->Execute($strSQL);

		$arrDatos["totalInversiones"] = 0;
		$arrDatos["cupo"] = 0;
        if (!$rsDatos->EOF){
            $arrDatos["totalInversiones"] = $rsDatos->fields["valorTotalInversiones"];
            $arrDatos["cupo"] = $rsDatos->fields["cupo"];
        }

        return $arrDatos;
    }

    function obtenerCuposEmisor($idEmisor = 0){

        global $db;

        $arrDatos = array();

        $strSQL = "SELECT SUM(of.giro_antes_gmf) AS valorTotalInversiones, c.cupo
        			FROM operacion as o
        			INNER JOIN clientes as c ON o.id_emisor = c.id_cliente
        			INNER JOIN operacion_factura as of ON o.id_operacion = of.id_operacion
                    left JOIN operacion_reliquidacion as r ON of.id_reliquidacion = r.id_reliquidacion
                   WHERE of.estado in (1,4,6,8) AND (r.estado = 1 or r.estado is null) AND o.id_emisor=" . $idEmisor;
        $rsDatos = $db->Execute($strSQL);

		$arrDatos["totalInversiones"] = 0;
		$arrDatos["cupo"] = 0;
        if (!$rsDatos->EOF){
            $arrDatos["totalInversiones"] = $rsDatos->fields["valorTotalInversiones"];
            $arrDatos["cupo"] = $rsDatos->fields["cupo"];
        }

        return $arrDatos;
    }

    function obtenerUltimaFechaOperacion($idTipoTercero = 0, $idCliente = 0){

    	global $db;

    	$ultimaFecha = "";

        //DETERMINAMOS QUE TIPO DE TERCERO ES
        //EMISOR
        if ($idTipoTercero == 1){
        	$strSQL = "SELECT MAX(fecha_operacion) as ultimaFecha
                   		FROM operacion
                   		WHERE id_emisor=".$idCliente;
        }
        //PAGADOR
        else if ($idTipoTercero == 6){
        	$strSQL = "SELECT MAX(fecha_operacion) as ultimaFecha
                   		FROM operacion
                   		WHERE id_pagador=".$idCliente;
        }

        $rsDatos = $db->Execute($strSQL);
        if (!$rsDatos->EOF){
        	$ultimaFecha = $rsDatos->fields["ultimaFecha"];
        }

        return $ultimaFecha;

    }

    function validarIntegridadReferencial($idCliente=0){

        global $db;

        $strSQL = "SELECT count(*) as total FROM operacion WHERE id_pagador = " . $idCliente. " OR id_emisor=" . $idCliente . " OR id_ejecutivo=" . $idCliente;
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            if ($rsDatos->fields["total"] > 0)
                return false;
        }

        $strSQL = "SELECT count(*) as total FROM clientes_ref_pagador WHERE id_cliente  = " . $idCliente. " OR id_pagador =" . $idCliente;
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            if ($rsDatos->fields["total"] > 0)
                return false;
        }

        $strSQL = "SELECT count(*) as total FROM clientes_referencias WHERE id_cliente  = " . $idCliente;
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            if ($rsDatos->fields["total"] > 0)
                return false;
        }

        $strSQL = "SELECT count(*) as total FROM clientes_documentos WHERE id_cliente  = " . $idCliente;
        $rsDatos = $db->Execute($strSQL);

        if (!$rsDatos->EOF){
            if ($rsDatos->fields["total"] > 0)
                return false;
        }

        return true;

    }

	function diasVigenciaPagare($idEmisor = 0, $fechaGeneracionPagare){

		global $db, $appObj;

		$dias = $appObj->paramGral["ANIOS_VENCIMIENTO_PAGARE"];
		$fechaVencimiento = sumar_dias_fecha($fechaGeneracionPagare,$dias);
		$arrDiferenciaFechasNotificacion = date_diff_custom(date("Y-m-d"), $fechaVencimiento);

		return $arrDiferenciaFechasNotificacion["d"];

	}

	function diasVigenciaResolucion($idEmisor = 0){

		global $db, $appObj;

		$dias = "";
		$resolucion = new clientes_res_facturas();
		$resolucion1 = new clientes_res_facturas();

        $loadReg2 = $resolucion->load("id_cliente=".$idEmisor. " AND registro=1");

		if ($resolucion->fecha_final != ""){
			$arrDiferenciaFechasNotificacion1 = date_diff_custom(date("Y-m-d"), $resolucion->fecha_final);
			$dias = $arrDiferenciaFechasNotificacion1["d"];
		}

        $loadReg3 = $resolucion1->load("id_cliente=".$idEmisor. " AND registro=2");

		if ($resolucion1->fecha_final != ""){

			$arrDiferenciaFechasNotificacion2 = date_diff_custom(date("Y-m-d"), $resolucion1->fecha_final);

			if ($arrDiferenciaFechasNotificacion2["d"] > 0){
				if ($arrDiferenciaFechasNotificacion2["d"] > $arrDiferenciaFechasNotificacion1["d"]){
					$dias = $arrDiferenciaFechasNotificacion2["d"];
				}
			}
		}

		return $dias;

	}

	function diasVigenciaDocumento($idCliente = 0, $idTipoDocumento = 0){

		global $db, $appObj;

		$clientesDocumentos = new clientes_documentos();

		$idClienteDocumento = $_REQUEST["id_cliente_documento"];

		//VALIDAMOS DOCUMENTOS SOLO APROBADOS
		$loadReg = $clientesDocumentos->load("id_cliente=".$idCliente." AND id_tipo_documento=".$idTipoDocumento." AND id_estado=2");

		$dias = -9999;
		if ($clientesDocumentos->id_cliente_documento != ""){

			$fechaVencimiento =  $clientesDocumentos->fecha_vencimiento;
			$arrDiferenciaFechasNotificacion = date_diff_custom(date("Y-m-d"), $fechaVencimiento);
			$dias = $arrDiferenciaFechasNotificacion["d"];
		}

		return $dias;

	}

}

?>
