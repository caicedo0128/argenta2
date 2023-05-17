<?php

class Pager{

    var $CurrPage = 0;
    var $TotalPages = 0;
    var $TotalResults = 0;
    var $TotalRegister = 0;
    var $SQL = 0;
    var $Mod = "";
    var $Msj = "";
    var $Action = "";
    var $PId = "";
    var $POptions = "";
    var $POptions2 = "";
    var $functionJS = "";

    function Pager($rs,$msj,$mod,$action,$p_id="",$p_options="",$p_options2=""){

        $this->CurrPage = $rs->_currentPage;
        $this->TotalPages = $rs->_lastPageNo;
        $this->TotalResults = $rs->_maxRecordCount;
        $this->TotalRegister = $rs->_numOfRows;
        $this->SQL = $rs->sql;
        $this->ArchivoControl = $mod;
        $this->Mod = $mod;
        $this->Msj = $msj;
        $this->Action = $action;
        $this->PId = $p_id;
        $this->POptions = $p_options;
        $this->POptions2 = $p_options2;

    }

    function getCurrPage(){

        return $this->CurrPage;

    }

    function getTotalPages(){

        return $this->TotalPages;

    }

    function getTotalResults(){

        return $this->TotalResults;

    }

    function getSql(){

        return $this->SQL;

    }

    function setArchivoControl($param){

        $this->ArchivoControl = $param;

    }

    function getArchivoControl(){

        return $this->ArchivoControl;

    }

    function setMsj($param){

        $this->Msj = $param;

    }

    function getMsj(){

        return $this->Msj;

    }

    function setAction($param){

        $this->Action = $param;

    }

    function getAction(){

        return $this->Action;

    }

    function all_pages($tipo_paginador){


        if ($tipo_paginador=="numeros")
            $this->getNumeros();
        else if ($tipo_paginador=="select")
            $this->getSelect();
        else if ($tipo_paginador=="imagen")
            $this->getImagen();
        else if ($tipo_paginador=="flechas")
            $this->getFlechas();
        else if ($tipo_paginador=="palabras")
            $this->getPalabras();

    }

    function getNumeros($className,$classLink){

        $strPaginador = "";
        $strPaginador .= "<table width='100%' class='".$className."' border='0'>";
        $strPaginador .= "<tr>";
        $strPaginador .= "<td nowrap align='left'>";
        $strPaginador .= "Page <b>".$this->getCurrPage()."</b> of <b>".$this->getTotalPages()."</b>";
        $strPaginador .= "</td>";
        $strPaginador .= "<td nowrap align='center'>";

        $lista .= "<table width='100%' cellspacing='1' border='0' cellpadding='1' class='".$className."'>";
        $lista .= "<tr>";


        //ofTERMINAMOS SI PUEof IR HACIA ATRAS
        if ($this->getCurrPage()>1){
            $lista .= "<td align='center'>";
            $lista .= "<a href=\"javascript:paginadorAjax('1','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>|<</a>";
            $lista .= "</td>";
        }

        //ARMAMOS LA PAGINACION
        for ($pag=1;$pag<=$this->TotalPages;$pag++){

            $lista .= "<td align='center' class='".$classLink."'>";

            if ($pag!=$this->getCurrPage())
                $lista .= "<a href=\"javascript:paginadorAjax('".$pag."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>".$pag."</a>";
            else
                $lista .= "<strong>".$pag."</strong>";

            $lista .= "</td>";
        }

        //ofTERMINAMOS SI PUEof IR HACIA AofLANTE
        if ($this->getCurrPage()!=$this->getTotalPages()){
            $lista .= "<td align='center'>";
            $lista .= "<a href=\"javascript:paginadorAjax('".$this->getTotalPages()."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>>|</a>";
            $lista .= "</td>";
        }
        $lista .= "</tr>";
        $lista .= "</table>";

        $strPaginador .= $lista;
        $strPaginador .= "</td>";
        $strPaginador .= "<td nowrap align='right'>";
        $strPaginador .= "Total registries : <b>".$this->getTotalResults()."</b>";
        $strPaginador .= "</td>";
        $strPaginador .= "</tr>";
        $strPaginador .= "</table>";


        return $strPaginador;
    }

    function getSelect($className,$classLink){


        //ARMAMOS LA PAGINACION ofNTRO of UN SELECT
        $select .= "<select id='paginadorSelect' name='paginadorSelect' class='".$classLink."' onchange=\"javascript:paginadorAjax(this.value,'','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\">\n";
        for ($pag=1;$pag<=$this->TotalPages;$pag++){

            $selected = "";
            if ($pag==$this->CurrPage)
                $selected = "selected";

            $select .= "<option value='".$pag."' ".$selected.">".$pag."</option>\n";
        }

        $select .= "</select>";

        $strPaginador = "<table width='100%' class='".$className."' border='0'>";
        $strPaginador .= "<tr>";
        $strPaginador .= "<td nowrap align='left'>";
        $strPaginador .= "Page ".$select." of <b>".$this->getTotalPages()."</b>";
        $strPaginador .= "</td>";
        $strPaginador .= "<td nowrap align='right'>";
        $strPaginador .= "Total registries : <b>".$this->getTotalResults()."</b>";
        $strPaginador .= "</td>";
        $strPaginador .= "</tr>";
        $strPaginador .= "</table>";

        return $strPaginador;

    }

    function getPalabras($className,$classLink){

        $strPaginador = "<table width='100%' class='".$className."' border='0'>";
        $strPaginador .= "<tr>";
        $strPaginador .= "<td nowrap align='left' width='20%'>";
        $strPaginador .= "Pag. <b>".$this->getCurrPage()."</b> de <b>".$this->getTotalPages()."</b>";
        $strPaginador .= "</td>";
        $strPaginador .= "<td nowrap align='center'>";

        $lista .= "<table width='100%' cellspacing='1' border='0' cellpadding='1' class='".$className."'>";
        $lista .= "<tr>";

        $lista .= "<td align='center'>";

        if ($this->functionJS)
            $lista .= "<a href=\"javascript:".$this->functionJS."(1);\" class='".$classLink."'>Primero</a>";
        else
            $lista .= "<a href=\"javascript:paginadorAjax('1','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>First</a>";
            
        $lista .= "</td>";
        $lista .= "<td align='center'>";
        
        if ($this->functionJS)
            $lista .= "<a href=\"javascript:".$this->functionJS."(".($this->getCurrPage()-1).");\" class='".$classLink."'>Anterior</a>";
        else        
            $lista .= "<a href=\"javascript:paginadorAjax('".($this->getCurrPage()-1)."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>Previous</a>";
            
        $lista .= "</td>";
        
        $lista .= "<td align='center'>";
        
        if ($this->functionJS)
            $lista .= "<a href=\"javascript:".$this->functionJS."(".($this->getCurrPage()+1).");\" class='".$classLink."'>Siguiente</a>";
        else
            $lista .= "<a href=\"javascript:paginadorAjax('".($this->getCurrPage()+1)."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>Next</a>";
            
        $lista .= "</td>";
        $lista .= "<td align='center'>";
        
        if ($this->functionJS)
            $lista .= "<a href=\"javascript:".$this->functionJS."(".$this->getTotalPages().");\" class='".$classLink."'>Ultimo</a>";
        else
            $lista .= "<a href=\"javascript:paginadorAjax('".$this->getTotalPages()."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."')\" class='".$classLink."'>Last</a>";
        $lista .= "</td>";

        $lista .= "</tr>";
        $lista .= "</table>";

        $strPaginador .= $lista;
        $strPaginador .= "</td>";
        $strPaginador .= "<td nowrap align='right' width='20%'>";
        $strPaginador .= "Total registros : <b>".$this->getTotalResults()."</b>";
        $strPaginador .= "</td>";
        $strPaginador .= "</tr>";
        $strPaginador .= "</table>";

        return $strPaginador;
    }

    function getNumerosCustom($className,$classLink){

        $strPaginador = "";
        $strPaginador .= "<table class='".$className."' border='0' align='right'>";
        $strPaginador .= "<tr>";
        $strPaginador .= "<td nowrap='nowrap' align='center' class='textobuscador'>";
        $strPaginador .= "P&aacute;gina&nbsp;de&nbsp;resultados:&nbsp;";
        $strPaginador .= "</td>";
        $strPaginador .= "<td nowrap='nowrap' align='center'>";

        $lista .= "<table width='100%' cellspacing='2' border='0' cellpadding='3' class='".$className."'>";
        $lista .= "<tr>";

        //ofTERMINAMOS SI PUEof IR HACIA ATRAS
        $lista .= "<td align='center'>";
        $lista .= "<a href=\"javascript:paginadorAjax('".($this->getCurrPage()-1)."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'><img src=\"./images/sabores/img_anterior.gif\" alt=\"Anterior\" width=\"16\" height=\"13\" border=\"0\" /></a>";
        $lista .= "</td>";


        //ARMAMOS LA PAGINACION
        for ($pag=1;$pag<=$this->TotalPages;$pag++){

            $lista .= "<td align='center' class='".$classLink."' >";

            if ($pag!=$this->getCurrPage())
                $lista .= "<a href=\"javascript:paginadorAjax('".$pag."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'>".$pag."</a>";
            else
                $lista .= "<strong>".$pag."</strong>";

            $lista .= "</td>";
        }

        //ofTERMINAMOS SI PUEof IR HACIA AofLANTE
        $lista .= "<td align='center'>";
        $lista .= "<a href=\"javascript:paginadorAjax('".($this->getCurrPage()+1)."','','','".$this->Msj."','".$this->ArchivoControl."','".$this->Action."','".$this->PId."','".$this->POptions."','".$this->POptions2."');\" class='".$classLink."'><img src=\"./images/sabores/img_siguiente.gif\" alt=\"Siguiente\" width=\"16\" height=\"13\" border=\"0\" /></a>";
        $lista .= "</td>";

        $lista .= "</tr>";
        $lista .= "</table>";

        $strPaginador .= $lista;
        $strPaginador .= "</td>";
        $strPaginador .= "</tr>";
        $strPaginador .= "</table>";


        return $strPaginador;
    }
    

}

?>