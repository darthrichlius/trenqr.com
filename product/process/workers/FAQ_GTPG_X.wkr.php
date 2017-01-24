<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_FAQ_GTPG_X extends WORKER  {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function Index () {
        /*
         * Peut etre qu'on va traiter le cas de usercard dans le cas où l'utilisateur est connecté
         */
        $this->KDOut["vmode"] = NULL;
        
        //On vérifie que les données correspondent à une opération de demande de mot de passe
        $TQACC = new TQR_ACCOUNT();
        
    }
    
    /****************** END SPECFIC METHODES ********************/
    
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            $this->signalError ("err_sys_l7comn3", __FUNCTION__, __LINE__);
        }
        
        $STOI = new SESSION_TO(); //ASTUCE : Permet d'accéder aux methodes grace à auto-imp
        $STOI = $_SESSION["sto_infos"];
        
        /*
         * [DEPUIS 17-09-15] @author BOR
         */
        $CXH = new CONX_HANDLER();
        $this->KDout["iauth"] = ( $CXH->is_connected() ) ? TRUE : FALSE ;
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
    }

    public function on_process_in() {
        $this->Index();
    }

    public function on_process_out() {
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        // A utiliser pour les DATX fournis sous forme de tableau. On boucle pour créer les row
        //*
        if ( $this->KDOut && count($this->KDOut) ) {
            foreach ( $this->KDOut as $k => $v ) {
                $_SESSION["ud_carrier"][$k] = $v;
            }
        }
        //*/
        
        /* A utiliser pour les DATX à insérer directement
        $_SESSION["ud_carrier"]["iml_articles"] = ...;
        //*/
        
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}

?>