<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_ontrenqr extends WORKER  {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function Init() {
        
        if ( $this->KDIn && $this->KDIn["ups_optional"] ) {
            if ( key_exists("view",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["view"] === "1m30" ) {
                $this->KDOut["view"] = "1m30";
            }
        }
        
        /*
         * [DEPUIS 09-11-15] @author
         *      On en prend qu'un que pour "Surprenez moi !". De plus c'est moins gourmand.
         *      Les autres seront récupérées depuis AJAX.
         */
        $TQR = new TRENQR();
        $r = $TQR->sugg_GetChoosenTrends(NULL,NULL,1,TRUE);
        if (! ( !$r || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) ) {
            $this->KDOut["spme"] = $r[0]["tlk"];
        }
        
    }
   
    

    /****************** END SPECFIC METHODES ********************/
    
    protected function prepare_params_in_if_exist() {
        
        
    }
    
    public function prepare_datas_in() {
        @session_start();
        
        /*
         * ETAPE :
         *  On récupère les données UPS Optionelles
         */
        $this->KDIn["ups_optional"] = $_SESSION["sto_infos"]->getCurr_wto()->getUps_optional();
        
        
        /*
         * [DEPUIS 29-05-16]
         *      
         */
        $this->KDIn["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
        
    }
    
    public function on_process_in() {
        
        /*
         * [DEPUIS 29-05-16]
         *      On gère le cas de l'AUTO_LOGIN
         */
        $TQCNX = new TQR_CONX();
        
//        $TQCNX->AutoCnx_DelCookie("TQR_CALG");
//        exit();
        
        $r = $TQCNX->AutoCnx_StartAutoLogIn(session_id(),$this->KDIn["locip"],$this->KDIn["loc_cn"],$this->KDIn["uagent"],[
            "WITH_COOKIE_MANAGE"    => TRUE,
            "WITH_SESSION_MANAGE"   => TRUE,
            "WITH_RELOAD_MANAGE"    => TRUE
        ]);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$r);
        
        $this->Init();
    }

    public function on_process_out() {
        
        /*
         * [DEPUIS 05-11-15]
         */
        $_SESSION["ud_carrier"]["spme"] = $this->KDOut["spme"];
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
//        var_dump($_SESSION["ud_carrier"]["runlang"]);
//        exit();
                
        /* PAGE HEAD DATAS */
        /*
         * [DEPUIS 05-11-15]
         *      Il s'agit des données destinées à être ajoutées au niveau du header de la page.
         */
        $_SESSION["ud_carrier"]["view"] = ( $this->KDOut["view"] ) ? $this->KDOut["view"] : "";
        
        /* 
         * [DEPUIS 05-11-15]
         *      On inscrit certaines informations relatives à la page.
         *      Ces informations sont données par WORKER car 'ver' dépend du fait qu'on soit sure que la procédure c'est passé corectement.
         *      Seuls les WORKER définissent les droits d'accès.
         * 
         *      En ce qui concerne 'pgid', WORKER le fait car certaines URQ sont de type AJAX qui n'admet pas de page.
         *      C'est donc au WORKER de définir ces informations.
         */
        $_SESSION["ud_carrier"]["pagid"] = "home";
        $_SESSION["ud_carrier"]["pgakxver"] = "wlc";
        
    }
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>