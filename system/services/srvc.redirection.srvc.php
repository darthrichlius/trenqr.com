<?php



/**
 * Description of srvc
 *
 * @author lou.carther.69
 */
class REDIR extends MOTHER {
    private $prod_xmlscope;
            
    function __construct($prod_xmlscope) {
        parent::__construct(__FILE__, __CLASS__);
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $prod_xmlscope);
        
        $this->prod_xmlscope = $prod_xmlscope;
    }
    
    private function check_if_default_pages_exist () {
        //Aim : S'assurer que les pages par défaut sont définies
        $reg = "#pdpage_.*#";        
        $ret = array();
        foreach ( $this->prod_xmlscope as $k => $v ) {
            if ( preg_match($reg, $k) ) {
                if (! empty($v) )
                    $ret[$k] = $v;
                else 
                    $this->signalError("err_sys_l025",__FUNCTION__, __LINE__);
            }
        }
        
        return $ret;
    }


    public function redir_to_default_page($default_page_key) {
        //TODO : Renvoie vers la page par defaut demandé
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list_of_pdp = $this->check_if_default_pages_exist();
        
        if (! ( key_exists($default_page_key, $list_of_pdp) && isset($list_of_pdp[$default_page_key]) && $list_of_pdp[$default_page_key] !== "" ) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $list_of_pdp);
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $default_page_key);
                $this->signalError("err_sys_l027",__FUNCTION__, __LINE__);
        } else {
            return $list_of_pdp[$default_page_key];
        }
    }
    
    //[NOTE 26-08-14] L.C. Je l'ai passé en public, il n'y a pas de raison qu'elle ne le soit pas 
    public function start_redir_to_this_url_string ($url) {
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());

         header("Location: $url"); 
         exit();
     }
    
    
    private function redir_toward_this_url_id ($url_id) {
        //TODO : On recoit l'URL_ID, on envoit à VPARSER pour récupérer la valeur
    }

    /**
     * ...
     * Attention, la methode ne traite que des cas de AG de type redirection (SR)
     * @param type $ag_access
     * @param type $is_v_rest
     * @return string
     */
    public function redir_handle_redir_case ($ag_access, $is_v_rest) {
        //TODO : On recoit le AG_ACCESS target. On crée l'url puis on redirige.
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        //Verifie que les url vers les pages par defaut sont bien definies
        $this->check_if_default_pages_exist();
        
        /**
         * Cette zone est importante pour le systeme !
         * Elle permet de définir clairement les règles en termes de redirection du produit.
         * INVOLVE : On pourrait externaliser cela pour une gestion plus personalisable pour le produit.
         */
        $url = "";
        switch ($ag_access) {
            case AG_DEN_SR_RPROD :
                    $url = ( $is_v_rest ) ? $this->redir_to_default_page(DFTPAGE_PROD_REST) : $this->redir_to_default_page(DFTPAGE_PROD_WEL);
                break;
            case AG_DEN_SR_RPROD2 :
                    $url = ( $is_v_rest ) ? $this->redir_to_default_page(DFTPAGE_PROD_REST_OWN) : $this->redir_to_default_page(DFTPAGE_PROD_WEL);
                break;
            case AG_RALL :
                    if ($is_v_rest)
                        return EXC_GO_ON;
                    else
                        $url = $this->redir_to_default_page(DFTPAGE_PROD_CONX);
                break;
            case AG_AGALL_SR_NEED :
                    //TODO 
                    exit;
                break;
            case AG_RACCONOWN_SR1 :
                    if ($is_v_rest) {
                        //TODO : Mettre en place un mecanisme pour gérer la confirmation de own.
                        //En attendant, il faut renvoyer vers la page par defaut de REST
                        $url = $this->redir_to_default_page(DFTPAGE_PROD_REST);
                    } else
                        $url =  $this->redir_to_default_page(DFTPAGE_PROD_CONX);
                break;
            case AG_WALL_REST_SR1:
                    if ($is_v_rest)
                        $url = $this->redir_to_default_page(DFTPAGE_PROD_REST_OWN);
                    else
                        return EXC_GO_ON;
                break;
            case AG_WALL_REST_SR2:
                    if ($is_v_rest)
                        $url = $this->redir_to_default_page(DFTPAGE_PROD_REST);
                    else
                        return EXC_GO_ON;
                break;
            default:
                $url = ($is_v_rest) ? $this->redir_to_default_page(DFTPAGE_PROD_REST) : $this->redir_to_default_page(DFTPAGE_PROD_WEL);
                break;
        }
        
        $this->start_redir_to_this_url_string($url);
    }
    
    //[NOTE 26-08-14] L.C. Refactorisation : La méthode avait été mal construite !!!
    public function redir_build_std_url_string ($page, $urqid, $user = NULL, $ups = NULL) {
        $args = [$page, $urqid];
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $url = HTTP_RACINE."/index.php?";
        
        if ( is_string($user) && !empty($user)  ) {
            $url .= "user=$user";
            $url .= "&page=$page&urqid=$urqid";
        }
        else {
            $url .= "page=$page&urqid=$urqid";
        }
            
        if ( isset($ups) && is_array($ups) && count($ups) ) {
            $url .= "&ups=";
            $ups_str = "";

            foreach ( $ups as $k => $v ) {
                $p_s = $this->prod_xmlscope[default_params_separator];
                $c_s = $this->prod_xmlscope[default_couple_separator];


                if ( $ups_str == "" ) 
                    $ups_str = $k.$p_s.$v;
                else 
                    $ups_str .= $c_s.$k.$p_s.$v;
            }

            $url .= $ups_str;
        }
                
        return $url;
    }
    
    /*
    public static function static_redir_build_std_url_string ($page, $urqid, $user = NULL, $ups = NULL) {
        
        if ( !empty($page) and !empty($urqid) ) {
            $url = RACINE."/?";
        
            if ( !empty($user) and is_string($user) )
                $url = RACINE."/?user=$user";
            else {
                $url .= "page=$page&urqid=$urqid";

                if (! empty($ups) ) {
                    $url .= "&ups=";
                    $ups_str = "";

                    foreach ( $ups as $k => $v ) {
                        $p_s = $this->prod_xmlscope[default_params_separator];
                        $c_s = $this->prod_xmlscope[default_couple_separator];


                        if ( $ups_str == "" ) 
                            $ups_str = $k.$p_s.$v;
                        else 
                            $ups_str .= $c_s.$k.$p_s.$v;
                    }

                    $url .= $ups_str;
                }
            }
            
            return $url;
        }
                
        return;        
    }
    //*/
    
    public function redir_build_scoped_url ($scope, $scope_anx = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $scope);
        if (! is_string($scope) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        $url = HTTP_RACINE;
        switch (strtoupper($scope)) {
            case "ONTRENQR":
                    $url .= "/ontrenqr";
                break;
            case "TMLNR_GTPG_RO":
                    $XPTD = ["user"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "chezmoi" : "athome";
                    $sector = "";
                    if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "tr" ) {
                        $sector = ( $urq === "chezmoi" ) ? "/tendances" : "/trends";
                    } 
                    else if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "fv" ) {
                        $sector = ( $urq === "chezmoi" ) ? "/favoris" : "/favorite";
                    }
                    else if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "abme" ) {
                        $sector = ( $urq === "chezmoi" ) ? "/apropos" : "/aboutme";
                    }
                    $url .= "/".$scope_anx["user"].$sector."&as=$urq";
//                    $url .= "/".$scope_anx["user"]."/$urq";
                break;
            case "TMLNR_GTPG_RU":
                    $XPTD = ["user"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "visiting" : "visiting";
                    $sector = "";
                    if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "tr" ) {
                        $sector = ( $urq === "visiting" ) ? "/tendances" : "/trends";
                    } 
                    else if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "fv" ) {
                        $sector = ( $urq === "visiting" ) ? "/favoris" : "/favorite";
                    }
                    else if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "abme" ) {
                        $sector = ( $urq === "visiting" ) ? "/apropos" : "/aboutme";
                    }
                    $url .= "/".$scope_anx["user"].$sector."&as=$urq";
//                    $url .= "/".$scope_anx["user"]."/$urq";
                break;
            case "TMLNR_GTPG_WLC":
                    $XPTD = ["user"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "public" : "public";
                    $sector = "";
                    if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "tr" ) {
                        $sector = ( $urq === "public" ) ? "/tendances" : "/trends";
                    } 
                    else if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "fv" ) {
                        $sector = ( $urq === "public" ) ? "/favoris" : "/favorite";
                    }
                    else if ( array_key_exists("pg", $scope_anx) && isset($scope_anx["pg"]) && strtolower($scope_anx["pg"]) === "abme" ) {
                        $sector = ( $urq === "public" ) ? "/apropos" : "/aboutme";
                    }
                    $url .= "/".$scope_anx["user"].$sector."&as=$urq";
//                    $url .= "/".$scope_anx["user"]."/$urq";
                break;
            case "TRPG_GTPG_RO":
                    $XPTD = ["teid","title"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "tendance" : "trend";
                    $url .= "/".$urq."/".$scope_anx["teid"]."/".$scope_anx["title"]."&as=manager";
                break;
            case "TRPG_GTPG_RFOL":
                    $XPTD = ["teid","title"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "tendance" : "trend";
                    $url .= "/".$urq."/".$scope_anx["teid"]."/".$scope_anx["title"]."&as=contributor";
                break;
            case "TRPG_GTPG_RU":
                    $XPTD = ["teid","title"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "tendance" : "trend";
                    $url .= "/".$urq."/".$scope_anx["teid"]."/".$scope_anx["title"]."&as=me";
                break;
            case "TRPG_GTPG_WLC":
                    $XPTD = ["teid","title"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "tendance" : "trend";
                    $url .= "/".$urq."/".$scope_anx["teid"]."/".$scope_anx["title"]."&as=visitor";
                break;
            case "TRPG_GTPG":
                    $XPTD = ["teid","title"];
                    foreach ($XPTD as $v) {
                        if (! ( array_key_exists($v, $scope_anx) && isset($scope_anx[$v]) && $scope_anx[$v] !== "" ) ) {
                            return "__ERR_VOL_MSG_DATAS";
                        }
                    }
                    
                    $urq = ( array_key_exists("lang", $scope_anx) && isset($scope_anx["lang"]) && $scope_anx["lang"] === "fr" ) ? "tendance" : "trend";
                    $url .= "/".$urq."/".$scope_anx["teid"]."/".$scope_anx["title"];
                break;
            default:
                    return "__ERR_VOL_WRG_DATAS";
        }
        
        return $url;
    }
}

?>
