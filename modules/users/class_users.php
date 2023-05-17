<?php
/**
* Adminsitración de tabla de users
* @version 1.0
* El constructor de esta clase es {@link users()}
*/
class users extends ADOdb_Active_Record{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "login":
                            $this->login();
                            break;
            case "loginDo":
                            $this->loginDo(true);
                            break;
            case "rememberPassword":
                            $this->rememberPassword();
                            break;
            case "logout":
                            $this->logout();
                            break;
            case "register":
                            $this->register();
                            break;
            case "saveUser":
                            $this->saveUser(true);
                            break;
            case "listUsers":
                            $this->listUsers();
                            break;
            case "actualizarSession":
                            $this->actualizarSession();
                            break;
            case "generarUsuariosAuto":
                            $this->generarUsuariosAuto();
                            break;                            
        }
    }

    /**
      * Funciòn para seleccionar opciones de la parte publica
      */
    function parsePublic() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "login":
                            $this->loginPublic();
                            break;
            case "loginDo":
                            $this->loginDo(false);
                            break;
            case "rememberPassword":
                            $this->rememberPassword();
                            break;
            case "logout":
                            $this->logout();
                            break;
            case "register":
                            $this->registerPublic();
                            break;
            case "saveUser":
                            $this->saveUser(false);
                            break;
            case "listUsers":
                            $this->listUsers();
                            break;
        }
    }

    /**
     * Funciòn para ver la caja de logueo
     */
    function loginPublic() {

        global $db,$id,$action,$option,$option2;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/email.php");
        require_once("./utilities/controles/password.php");

        include("./modules/users/templates/login_publico.php");

    }
    
    function generarUsuariosAuto(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./utilities/class_hcemd5.php");

		$strSQL = "SELECT razon_social,tipo_identificacion,identificacion_representante,  
					correo_personal,telefono_fijo,telefono_fijo1,direccion,identificacion,id_cliente
					FROM clientes WHERE activo=1 and id_tipo_tercero=1";
		$rsData = $db->Execute($strSQL);
		
        $EncryptionKey = $appObj->paramGral["ENCRYPTION_KEY"];

        $hcemd5 = new Crypt_HCEMD5($EncryptionKey, '');

		while (!$rsData->EOF){
        
        	$user = new users();
			$loadReg = $user->load("id_usuario=0");

			$user->nombres = $rsData->fields['razon_social'];
			$user->apellidos = '';
			$user->tipo_documento = $rsData->fields['tipo_identificacion'];
			$user->identificacion = str_replace("-","",$rsData->fields['identificacion']);
			$user->correo_personal = $rsData->fields['correo_personal'];
			$user->telefono_fijo = $rsData->fields['telefono_fijo'];
			$user->telefono_celular = $rsData->fields['telefono_fijo1'];
			$user->direccion = $rsData->fields['direccion'];
			$user->fecha_registro = date("Y-m-d");
			$user->cargo = '';
			$Pool = $rsData->fields['identificacion'];
			$clavetmp = substr($Pool, 1, 5);
			$passEncrypt = base64_encode($hcemd5->encrypt($clavetmp));
			$user->password = $passEncrypt;
			$user->id_perfil = 3;
			$user->id_tercero = $rsData->fields['id_cliente'];
			$user->activo = 1;
			$user->Save();   
			
			$rsData->MoveNext();
        }
        
		$jsondata['Message'] = "Terminado";

        echo json_encode($jsondata);
        exit;        

    }    

    /**
     * Funciòn para ver el formulario de registrar un usuario
     */
    function registerPublic() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/email.php");
        require_once("./utilities/controles/password.php");
        require_once("./utilities/controles/password_confirm.php");
        require_once("./utilities/controles/radio.php");

        $idUsuario = $_REQUEST["id_usuario"];
        $loadReg = $this->load("id_usuario=".$idUsuario);

        include("./modules/users/templates/registro_publico.php");

    }

    /**
     * Funciòn para obtener el listado de users
     */
    function listUsers(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        include("./utilities/class_dataGrid.php");

        //INSTANCIAMOS LA CLASE DATA GRID
        $dataGrid = new DataGrid($this);

        $dataGrid->idDataGrid = "resultDatos";
        $dataGrid->heightDG = "300";

        //TRAEMOS LA CONSULTA DE DATOS PARA EL DATA GRID
        $strSQL = "SELECT users.id_usuario, usuarios_perfil.perfil, users.identificacion, users.nombres, users.apellidos, users.correo_personal, users.cargo, users.fecha_registro, c.razon_social,users.activo
        		   FROM usuarios_perfil
        		   INNER JOIN users ON (usuarios_perfil.id_perfil = users.id_perfil)
        		   LEFT JOIN clientes as c ON users.id_tercero = c.id_cliente
        		   ";
        $strWhere = " WHERE 1=1";
        $strOrder = " ORDER BY users.nombres,users.apellidos DESC";

        //TRAEMOS LA CONSULTA A EJECUTAR
        $dataGrid->SQL = $strSQL;
        $dataGrid->WHERE = $strWhere;
        $dataGrid->ORDER_BY = $strOrder;

        //INSTANCIAMOS EL MENSAJE DE PROCESO REALIZADO
        $dataGrid->titleProcess = $msjProcesoRealizado;

        //INSTANCIAMOS EL TITULO DEL ADMINISTRADOR
        $dataGrid->titleList="<h1>Listado de Administradores</h1>";

        //CREAR OPCIONES DE ENCABEZADO EN EL DATA GRID
        $dataGrid->optionsHeader=true;
        $dataGrid->addOptionsHeader("Agregar","javascript:editUsuario(0,'users','register')");

        //CREAR OPCIONES DE PIE EN EL DATA GRID
        $dataGrid->optionsFooter=false;

        //IMPRIMIMOS LOS ENCABEZADOS DE COLUMNAS DEL DATA GRID
        $dataGrid->addTitlesHeader(array("Perfil","Identificacion","Nombres","Apellidos","Email", "Cargo", "Fecha Registro","Tercero","Activo"));

        //OCULTAMOS COLUMNAS O CAMPOS DEL DATA GRID
        $dataGrid->addColumnHide(array("id_usuario"));

        //CAMPOS DE SOLO DOS VALORES
        $arrValues = array("1","2");
        $arrText = array("Si","No");
        $dataGrid->addFieldTwoValues("activo",$arrValues,$arrText);

        //CREAR UNA COLUMNA CON LINK PASANDO VARIABLES POR METODO GET
        $arrVarGet1 = Array("id_usuario"=>"ID_USUARIO","mod"=>"users","action"=>"register");
        $dataGrid->addColLink("Editar","<center><a href=\"javascript:{function};\"><img src='./images/editar.png' title='Editar Usuario' alt='Editar Usuario' border='0'/></a></center>","editUsuario",$arrVarGet1,"functionjs","left");

        //CREAR LA PAGINACION
        $dataGrid->paginadorHeader = false;
        $dataGrid->paginadorFooter = false;
        $dataGrid->totalRegPag = 500;

        include("./modules/users/templates/listado_usuarios.php");


    }

    /**
     * Funciòn para registrar un usuario
     */
    function saveUser($isAdmin = true) {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        $sql = "SELECT * FROM users WHERE (correo_personal= '".$_REQUEST['correo_personal']."' OR identificacion = '".$_REQUEST['documento']."') AND id_usuario<>'".$_POST["id_usuario"]."'";
        $rs = $db->Execute($sql);
        if($rs->EOF){

            $this->saveData();
            $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
            $jsondata['Success'] = true;
            $jsondata['IsAdmin'] = $isAdmin;

        }else{
            $jsondata['Message'] = "La Identificacion o Correo ya se encuentran registrados.";
            $jsondata['Success'] = false;
            $jsondata['IsAdmin'] = $isAdmin;
        }

        echo json_encode($jsondata);
        exit;
    }

    function saveData(){

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/class_hcemd5.php");

        $loadReg = $this->load("id_usuario=".$_POST['id_usuario']);

        $EncryptionKey = $appObj->paramGral["ENCRYPTION_KEY"];

        $hcemd5 = new Crypt_HCEMD5($EncryptionKey, '');

        $this->nombres = utf8_decode($_POST['nombres']);
        $this->apellidos = utf8_decode($_POST['apellidos']);
        $this->tipo_documento = $_POST['tipo_doc'];
        $this->identificacion = $_POST['documento'];
        $this->correo_personal = $_POST['correo_personal'];
        $this->telefono_fijo = $_POST['telefono'];
        $this->telefono_celular = $_POST['celular'];
        $this->direccion = $_POST['direccion'];
        $this->fecha_registro = date("Y-m-d");
        $this->cargo = $_POST['cargo'];
        if ($_POST['contrasegna']){
            $passEncrypt = base64_encode($hcemd5->encrypt($_POST['contrasegna']));
            $this->password = $passEncrypt;
        }

        $this->id_perfil = $_POST["id_perfil"];
        $this->id_tercero = $_POST["id_tercero"];
        $this->activo = $_POST["activo"];
        $this->Save();

    }

    /**
     * Funciòn para ver el formulario de registrar un usuario
     */
    function register() {

        global $db,$id,$appObj,$LANG;


        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/controles/textbox.php");
        require_once("./utilities/controles/select.php");
        require_once("./utilities/controles/email.php");
        require_once("./utilities/controles/password.php");
        require_once("./utilities/controles/password_confirm.php");
        require_once("./utilities/controles/radio.php");
        require_once("./modules/generales/class_generales.php");
        require_once("./modules/clientes/class_clientes.php");

        $clientes = new clientes();

        $idUsuario = $_REQUEST["id_usuario"];
        $loadReg = $this->load("id_usuario=".$idUsuario);

        //TRAEMOS LOS PERFILES
        $arrPerfiles = $this->getPerfiles();

        //OBTENEMOS LOS TERCEROS
        $arrTerceros = $clientes->obtenerClientesActivosPorTipoTercero(1);
        $arrTercerosPagadores = $clientes->obtenerClientesActivosPorTipoTercero(6);

        include("./modules/users/templates/registro.php");

    }

    /**
     * Funciòn para recordar la clave
     */
    function rememberPassword() {

        global $db,$id,$appObj,$LANG;

        //INCLUIMOS ARCHIVOS NECESARIOS
        require_once("./utilities/class_send_mail.php");
        require_once("./utilities/class_hcemd5.php");

        $EncryptionKey = $appObj->paramGral["ENCRYPTION_KEY"];       

        //INSTANCIAMOS CLASES
        $hcemd5 = new Crypt_HCEMD5($EncryptionKey, '');
        $sendMail = new sendMail();
        $success = false;

        $username = utf8_decode($_POST["username"]);
        $strSQL = "SELECT password,correo_personal,nombres,apellidos, id_usuario FROM users WHERE correo_personal='".$username."' AND activo=1";
        $rs = $db->Execute($strSQL);

        if (!$rs->EOF){

            $envio=false;
            $toNameMail = $rs->fields["nombres"] . " " . $rs->fields["apellidos"];
            $toEmail = $rs->fields["correo_personal"];
            $passEncrypted = $rs->fields["password"];
            $idUsuario = $rs->fields["id_usuario"];

            $asunto = "ARGENTA ESTRUCTURADORES :: Recordar Clave";

            $fromEmail = $appObj->paramGral["FROM_EMAIL_CONTACT"];
            $fromName = $appObj->paramGral["FROM_NAME_EMAIL_CONTACT"];
            $subjectMail = $asunto;

			$Pool = "1234567890";
			for($index = 0; $index < 6; $index++)
			{
				$clavetmp.= substr($Pool,(rand()%(strlen($Pool))),1);
			}
			
			//ENCRIPTAMOS CONTRASEÑA
			$passEncrypt = base64_encode($hcemd5->encrypt($clavetmp));	
			$strSQL = "update users set password = '".$passEncrypt."' where id_usuario = ". $idUsuario;
			$db->Execute($strSQL);		

			//ENVIAMOS EL CORREO
			$arrVarsReplace = array("NAME"=>$toNameMail, "PASSDECRYPT"=>$clavetmp);
			$success = $sendMail->enviarMail($fromName,$fromEmail,$toNameMail,$toEmail,$subjectMail,"mailContrasena","",$arrVarsReplace);


            if (!$success)
                $jsondata['Message'] = "La operacion no se pudo completar, por favor intente mas tarde";
            else{
                $jsondata['Message'] = "Hemos enviado tu contrasena a tu email registrado(".$toEmail.").";
                $success = true;
            }
        }
        else{
            $jsondata['Message'] = "El usuario (".$username.") no se encontro registrado o el usuario se encuentra inactivo.";
        }

        $jsondata['Success'] = $success;
        echo json_encode($jsondata);
        exit;

    }

    /**
     * Funciòn para cerrar session de usuario
     */
    function logout() {

        $_SESSION["id_user"] = null;
        $_SESSION["user"] = null;
        $_SESSION["email"] = null;
        $_SESSION["login"] = null;
        $_SESSION["profile"] = null;
        $_SESSION["profile_text"] = null;
        $_SESSION["id_tercero"] = null;
        $_SESSION["nit_tercero"] = null;
        $_SESSION["tercero"] = null;
        session_destroy();
        $jsondata['Success'] = true;
        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver hacer logueo del usuario
     */
    function loginDo($isAdmin = false) {

        global $db,$id,$action,$option,$option2,$appObj;

        require_once("./utilities/class_hcemd5.php");
        require_once("./modules/perfiles/class_perfiles_extended.php");

		$perfilPermiso = new perfil_permiso();

        $username = addslashes(utf8_decode($_POST["username"]));
        $pass = utf8_decode($_POST["pass"]);
        $EncryptionKey = $appObj->paramGral["ENCRYPTION_KEY"];
        $hcemd5 = new Crypt_HCEMD5($EncryptionKey, '');
        $encrypted_password = base64_encode($hcemd5->encrypt($pass));
        $strSQL = "SELECT u.id_usuario, u.nombres, u.apellidos, u.identificacion,u.correo_personal,u.id_perfil, c.id_cliente, c.razon_social, c.tipo_identificacion, c.identificacion
        		   FROM users as u
        		   LEFT JOIN clientes as c ON u.id_tercero = c.id_cliente
        		   WHERE u.correo_personal='".$username."' AND u.password='".$encrypted_password."' AND u.activo=1";
        $rs = $db->Execute($strSQL);
        $saveSession = false;
        if (!$rs->EOF){

            //DETERMINAMOS SI ENTRA AL ADMINISTRADOR
            if ($isAdmin){
                //if ($rs->fields["id_perfil"] == 1 || $rs->fields["id_perfil"] == 2){
                    $jsondata['Success'] = true;
                    $jsondata['Message'] = "El ingreso esta en proceso...";
                    $saveSession = true;
                //}
                //else{
                //  $jsondata['Success'] = false;
                //  $jsondata['Message'] = "Usted no tiene un perfil valido para ingresar a esta seccion.";
                //}
            }
            else{
                $jsondata['Success'] = true;
                $jsondata['Message'] = "El ingreso esta en proceso...";
                $saveSession = true;
            }
        }
        else{
            $jsondata['Success'] = false;
            $jsondata['Message'] = "El usuario o clave no se encuentran registrados o El usuario esta inactivo.";
        }

        if ($saveSession){
            $_SESSION["id_user"] = $rs->fields["id_usuario"];
            $_SESSION["user"] = ucwords(strtolower($rs->fields["nombres"] . " " . $rs->fields["apellidos"]));
            $arrNameUser = preg_split("/[ ]+/", $rs->fields["nombres"]);
            $_SESSION["user_name"] = ucwords(strtolower($rs->fields["nombres"]));
            $_SESSION["email"] = $rs->fields["correo_personal"];
            $_SESSION["login"] = true;
            $_SESSION["profile"] = $rs->fields["id_perfil"];
            $arrPerfiles = $this->getPerfiles();
            $_SESSION["profile_text"] = $arrPerfiles[$rs->fields["id_perfil"]];
			$_SESSION["id_tercero"] = $rs->fields["id_cliente"];
	        $_SESSION["nit_tercero"] = $rs->fields["identificacion"];
	        $_SESSION["tercero"] = $rs->fields["razon_social"];
	        
			//GENERAMOS EN SESION LOS PERMISOS DEL PERFIL
            $perfilPermiso->generarPermisosSesion($rs->fields["id_perfil"]);  	        
        }

        echo json_encode($jsondata);
        exit;
    }

    /**
     * Funciòn para ver la caja de logueo
     */
    function login() {

        global $db,$id,$action,$option,$option2;

        include("./modules/users/templates/login.php");

    }


    /**
     * Funciòn para traer los perfiles
     */
    function getPerfiles() {

        global $db;

        $arrPerfiles = array();

        $strSQL = "SELECT * FROM usuarios_perfil";
        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrPerfiles[$rsDatos->fields["id_perfil"]] = $rsDatos->fields["perfil"];
            $rsDatos->MoveNext();
        }


        return $arrPerfiles;

    }

    /**
     * Funciòn para traer los usuario
     */
    function getUsuariosPerfil($idPerfil = 0, $activo = 1) {

        global $db;

        $arrUsuarios = array();

        $strSQL = "SELECT * FROM users WHERE 1=1 AND activo=" . $activo;
        if ($idPerfil != 0)
        	$strSQL .= " AND id_perfil = " . $idPerfil;

		$strSQL .= " ORDER BY nombres, apellidos";

        $rsDatos = $db->Execute($strSQL);

        while (!$rsDatos->EOF){

            $arrUsuarios[$rsDatos->fields["id_usuario"]] = $rsDatos->fields["nombres"] . " " . $rsDatos->fields["apellidos"];
            $rsDatos->MoveNext();
        }

        return $arrUsuarios;

    }


    /**
     * Funciòn para traer los datos de un usuario por id
     */
    function getUsuarioById($idUsuario) {

        global $db;

        $usuarioWeb = new users();
        $loadReg = $usuarioWeb->load("id_usuario='".$idUsuario."'");

        return $usuarioWeb;

    }

    function actualizarSession(){

        global $db;

        $expiresSession = sumar_minutos_fecha(date("Y-m-d H:i:s"),20);
        $newDate = strtotime($expiresSession);
        $jsondata['Success'] = true;
        $jsondata['anio'] = date("Y", $newDate);
        $jsondata['mes'] = date("m", $newDate);
        $jsondata['dia'] = date("d", $newDate);
        $jsondata['hora'] = date("H", $newDate);
        $jsondata['minutos'] = date("i", $newDate);

        echo json_encode($jsondata);
        exit;

    }

}

?>
