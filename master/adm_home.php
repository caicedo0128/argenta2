<div id="home_admin">
    <?php

        if (!$_SESSION["login"]){
            require_once("./modules/users/class_users.php");
            @$plugin = new users();
            $plugin->login();
        }
        else{
        	if ($_SESSION["profile_text"] != "Cliente"){
        	
				require_once("./modules/flujo/class_flujo.php");
				@$plugin = new flujo();
				$plugin->flujoCajaHome(); 
            }
            
			require_once("./modules/tareas/class_tareas.php");
            @$plugin = new tareas();
            $plugin->ListTareas();  
             
        }
    ?>
</div>


