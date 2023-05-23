<?php

class AppObj{

    var $paramGral = array();
    var $textGral = array();
	var $aliasPage = 0;
	var $idPagePadre = 0;
	var $nombre = "";
	var $tituloHtml = "";
    var $mod = "";
    var $action = "";
    var $id = "";
    var $Lightbox = false;
    var $Ajax = false;
    var $xml = false;
    var $metasGoogle = "";

    function AppObj(){


    }

    function checkPage(){

        global $db;

		$this->aliasPage = $_REQUEST["page"];

		if ($this->aliasPage){

			//GET PAGE
			$strSQL = "SELECT app_paginas.id_pagina, app_paginas.id_pagina_padre, app_paginas.nombre, app_paginas.titulo_html, app_paginas.id_tipo, app_paginas.target, app_paginas.modulo, app_paginas.accion, app_paginas.id, app_paginas.descripcion, app_paginas.palabras_clave, app_paginas.scripts_includes_ini, app_paginas.scripts_includes_fin, app_paginas.scripts_code_ini, app_paginas.scripts_code_fin FROM app_paginas WHERE alias='".$this->aliasPage."' AND oculto=-1";
			$rsPage = $db->Execute($strSQL);
			if(!$rsPage->EOF){

				$nombre = $rsPage->fields["nombre"];
				$tituloHtml = $rsPage->fields["titulo_html"];
				$this->idPagePadre = $rsPage->fields["id_pagina_padre"];
				$this->nombre = $rsPage->fields["nombre"];
				$this->tituloHtml = $rsPage->fields["titulo_html"];
				$this->mod = $rsPage->fields["modulo"];
				$this->action = $rsPage->fields["accion"];
				$this->id = $rsPage->fields["id"];

				$tituloPagina = $nombre;
				if ($tituloHtml)
					$tituloPagina = $tituloHtml;
				$description = $rsPage->fields["descripcion"];
				$keywords = $rsPage->fields["palabras_clave"];


				$this->paramGral["TITLE_PAGE"] = $tituloPagina . "-" . $this->paramGral["TITLE_PAGE"];

				if ($description)
					$this->paramGral["DESCRIPTION_PAGE"] = $description;

				if ($keywords)
					$this->paramGral["KEYWORDS_PAGE"] = $keywords;

				//INCLUDES DINAMICOS
				$this->scriptsIncludesHeader = $rsPage->fields["scripts_includes_ini"];
				$this->scriptsIncludesFooter = $rsPage->fields["scripts_includes_fin"];
				$this->scriptsCodeHeader = $rsPage->fields["scripts_code_ini"];
				$this->scriptsCodeFooter = $rsPage->fields["scripts_code_fin"];

				$this->Ajax = $_REQUEST["Ajax"];
				$this->xml = $_REQUEST["xml"];

				if ($rsPage->fields["target"]=="lightbox"){
					$this->Lightbox = true;
					$this->Ajax = false;
					$this->xml = false;
				}
			}
		}
		else if ($_REQUEST["mod"] && $_REQUEST["action"]){

            $this->mod = $_REQUEST["mod"];
            $this->action = $_REQUEST["action"];
            $this->id = $_REQUEST["id"];

            if ($_REQUEST["Ajax"])
                $this->Ajax = $_REQUEST["Ajax"];

            if ($_REQUEST["xml"])
                $this->xml = $_REQUEST["xml"];

            if ($_REQUEST["lightbox"])
                $this->Lightbox = $_REQUEST["lightbox"];
        }

    }

    function getParamGral(){

        global $db;

        $strSQL = "SELECT app_param_global.id_parametro, app_param_global.parametro, app_param_global.valor FROM app_param_global WHERE tipo=1";
        $rsParamGral = $db->Execute($strSQL);

        while (!$rsParamGral->EOF){
            $arrParamGral[$rsParamGral->fields["parametro"]] = $rsParamGral->fields["valor"];
            $rsParamGral->MoveNext();
        }

        $strSQL = "SELECT app_texts.param, app_texts.text FROM app_texts";
        $rsTextGral = $db->Execute($strSQL);

        while (!$rsTextGral->EOF){
            $textGral[$rsTextGral->fields["param"]] = $rsTextGral->fields["text"];
            $rsTextGral->MoveNext();
        }

        $arrParamGral["arrMeses"] = $this->getMeses();

        $arrParamGral["arrAnios"] = $this->getAnios(5,2);

        $arrParamGral["arrHoras"] = $this->getHoras();

        $this->paramGral = $arrParamGral;
        $this->textGral = $textGral;
        return;
    }

    function getHoras(){

        $horaInicial = 1;
        $horaFin = 24;

        for ($i=$horaInicial;$i<=$horaFin;$i++){

            $arrHoras[$i] = $i . " Hrs.";
        }

        return $arrHoras;

    }

    function getMeses(){

        $arrMeses[1] = "Enero";
        $arrMeses[2] = "Febrero";
        $arrMeses[3] = "Marzo";
        $arrMeses[4] = "Abril";
        $arrMeses[5] = "Mayo";
        $arrMeses[6] = "Junio";
        $arrMeses[7] = "Julio";
        $arrMeses[8] = "Agosto";
        $arrMeses[9] = "Septiembre";
        $arrMeses[10] = "Octubre";
        $arrMeses[11] = "Noviembre";
        $arrMeses[12] = "Diciembre";

        return $arrMeses;

    }

    function getAnios($inicial,$final){

        $anioInicial = date("Y") - $inicial;
        $anioFin = date("Y") + $final;

        for ($i=$anioInicial;$i<=$anioFin;$i++){

            $arrAnios[$i] = $i;
        }

        return $arrAnios;
    }
    
    function getNumeros($inicial,$final){

        for ($i=$inicial;$i<=$final;$i++){

            $arrNumeros[$i] = $i;
        }

        return $arrNumeros;
    }    

    function getMenu(){

        global $db;
        
        require_once("./modules/menu/class_menu.php");

        $menu = new menu();

        return $menu->getMenu();
    }


  	/**
  	 * Funciòn generar el path de la pagina visitada
  	 */
  	function getPathContent($separador="/",$estilo=""){

  		global $db;

  		$_SESSION["arrPath"] = null;

  		$idContenidoPadre = $this->idPagePadre;

  		if ($idContenidoPadre=="0"){

  			$tituloPath = $this->nombre;
			if ($this->tituloHtml)
				$tituloPath = $this->tituloHtml;

  			$urlLink = "index.php?".htmlentities("page=". $this->aliasPage);
			$link = $separador . " <a href='".$urlLink."' class='".$estilo."'>".$tituloPath."</a> ";
			$strPath = $link;
  		}
  		else{
  			$this->getGenealogia($this->aliasPage,$separador,$estilo);

  			$arrPath = array_reverse($_SESSION["arrPath"]);
			for ($i=0;$i<count($arrPath);$i++){
				$strPath .= $arrPath[$i];
			}
  		}

  		return $strPath;
  	}

  	/**
  	 * Funciòn generar la genealogia de la contenido visitada
  	 */
  	function getGenealogia($aliasPage="0",$separador,$estilo){

		global $db;
		$db_class = $db;

		if ($aliasPage=="0"){
			return $_SESSION["arrPath"];
		}

		$strSQL = "SELECT id_pagina_padre, nombre, titulo_html , alias FROM app_paginas WHERE alias='" . $aliasPage . "'";
		$rsConsulta = $db_class->Execute($strSQL);

		if(!$rsConsulta->EOF){

			$aliasPagina = $rsConsulta->fields["alias"];
			$idPaginaPadre = $rsConsulta->fields["id_pagina_padre"];

			$tituloLink = $rsConsulta->fields["nombre"];
			//if ($rsConsulta->fields["titulo_html"])
			//	$tituloLink = $rsConsulta->fields["titulo_html"];

			$urlLink = "index.php?".htmlentities("page=". $aliasPagina);
			$link = $separador . " <a href='".$urlLink."' class='".$estilo."'>".$tituloLink."</a> ";
			$_SESSION["arrPath"][] = $link;
			$this->getGenealogia($idPaginaPadre,$separador,$estilo);

		}

  	}
  	
    function tienePermisosAccion($acciones = array()){

        require_once("./modules/perfiles/class_perfiles_extended.php");

        $perfilPermiso = new perfil_permiso();
        $tienePermiso = $perfilPermiso->tienePermisosAccion($acciones);

        return $tienePermiso;
    
    }  	

    /**
     * Funciòn generar las metas dependiendo del modlo
     */
    function checkMetas(){

        $mod = $this->mod;

        if (file_exists("./modules/".$mod ."/class_".$mod .".php")){

            require_once ("./modules/".$mod ."/class_".$mod .".php");

            //INSTANCIAMOS LA CLASE SEGUN EL REQUEST
            @$module = new $mod;
            if (method_exists($module,"checkMetas")){

                $arrMetas = $module->checkMetas();

                //DETERMINAMOS SI LOS METAS SON DIFERENTES DE VACIO
                if ($arrMetas["keywords"]!="")
                    $this->paramGral["KEYWORDS_PAGE"] = $arrMetas["keywords"];

                if ($arrMetas["description"]!="")
                    $this->paramGral["DESCRIPTION_PAGE"] = $arrMetas["description"];

                if ($arrMetas["title"]!="")
                    $titlePage = $arrMetas["title"] . "-" . $this->paramGral["TITLE_PAGE"];

                if ($arrMetas["metasGoogle"]){

                    $siteTitle = "<meta property='og:title' content='".$arrMetas["title"]."' />\n<meta name='DC.title' content='".$arrMetas["title"]."'/>\n";
                    $siteType = "<meta property='og:type' content='article' />\n";
                    $siteImage = "<meta property='og:image' content='".$arrMetas["img"]."' />\n";


                    $this->metasGoogle = $siteName . $siteTitle . $siteType . $siteUrl . $siteImage . $siteDescription;

                }

                $this->paramGral["TITLE_PAGE"] = $titlePage;
            }

        }

    }

  	/**
  	 * Funciòn para cambiar los caracteres que hacen XSS
  	 */
	function removeXSS($texto=""){

		$strTexto = str_replace("<","",$texto);
		$strTexto = str_replace(">","",$strTexto);
		$strTexto = str_replace("&","",$strTexto);
		$strTexto = str_replace("script","",$strTexto);
		$strTexto = str_replace("javascript","",$strTexto);

		return $strTexto;

	}

}

?>
