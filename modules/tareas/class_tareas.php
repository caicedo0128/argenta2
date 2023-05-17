<?php
/**
* Adminsitración del modulo tareas
* @version 1.0
* El constructor de esta clase es {@link tareas()}
*/
require_once("class_tareas_extended.php");
class tareas{


    var $Database;
    var $ID;

    /**
      * Funciòn para seleccionar opciones de la parte administrativa
      */
    function parseAdmin() {

        global $db,$id,$action,$option,$option2,$appObj;

        switch($appObj->action){

            case "ListTareas":
                            $this->ListTareas();
                            break;
			case "ActualizarTarea":
                            $this->ActualizarTarea();
                            break;
			case "tarea":
                            $this->Tarea();
                            break;
			case "cerrarTarea":
                            $this->CerrarTarea();
                            break;

    	}
    }

    /**
     * Funciòn para cerrar informacion seguimiento
     */
    function CerrarTarea() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./modules/clientes/class_clientes_extended.php");

        //INSTANCIAMOS CLASES
        $tarea = new clientes_seguimiento();

        $idTareaSeguimiento = $_REQUEST["id_tarea"];

        $loadReg1 = $tarea->load("id_cliente_seguimiento=".$idTareaSeguimiento);

        $tarea->observaciones = date("Y-m-d H:i:s") . ": " . $_POST['observaciones'] . "<hr>" . $tarea->observaciones;
		$tarea->fecha_respuesta = date("Y-m-d H:i:s");
		$tarea->id_estado = 5; //TAREA CERRADA
        $tarea->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
        $jsondata['Success'] = true;

        echo json_encode($jsondata);
        exit;
    }

	/**
     * Funciòn para ver la tarea
     */
    function Tarea(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes_extended.php");
        require_once("./utilities/controles/textarea.php");

        //INSTANCIAMOS CLASES
		$tarea = new clientes_seguimiento();

        $idTarea = $_REQUEST["id_tarea"];

		$loadReg = $tarea->load("id_cliente_seguimiento=".$idTarea);

        include("./modules/tareas/templates/tarea.php");
    }

	/**
     * Funciòn para obtener el listado de tareas
     */
    function ListTareas(){

        global $db,$id,$action,$option,$option2,$appObj,$msjProcesoRealizado;

        require_once("./modules/clientes/class_clientes_extended.php");

        //INSTANCIAMOS CLASES
		$tarea = new clientes_seguimiento();

        $idUsuario = $_SESSION["id_user"];

        $rsTareas = $tarea->obtenerTareasPendientesPorUsuario($idUsuario);
        $rsTareasAsignadas = $tarea->obtenerTareasPendientesAsignadasPorUsuario($idUsuario);

        include("./modules/tareas/templates/listado_tareas.php");
    }

    /**
     * Funciòn para guardar informacion seguimiento
     */
    function ActualizarTarea() {

        global $db,$id,$appObj,$LANG,$msjProcesoRealizado;

		require_once("./modules/clientes/class_clientes_extended.php");

        //INSTANCIAMOS CLASES
        $tarea = new clientes_seguimiento();

        $idTareaSeguimiento = $_REQUEST["id_tarea"];

        $loadReg1 = $tarea->load("id_cliente_seguimiento=".$idTareaSeguimiento);

        $tarea->observaciones = date("Y-m-d H:i:s") . ": " . $_POST['observaciones'] . "<hr/>" . $tarea->observaciones;
        $tarea->Save();

        $jsondata['Message'] = "El proceso se realizo con exito. Espere por favor...";
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