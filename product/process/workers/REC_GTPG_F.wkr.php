<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_REC_GTPG_F extends WORKER  {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function RecoveryChangeFinalHandler () {
        
        $this->KDOut["vmode"] = NULL;
        /*
         * On s'assure que les données sont remis à leur état originel
         */
        $this->KDIn["datas"]["ue"] = str_replace(',','.',$this->KDIn["datas"]["ue"]);
        
        //On vérifie que les données correspondent à une opération de demande de mot de passe
        $TQACC = new TQR_ACCOUNT();
        $r = $TQACC->onalter_CheckPassRecLinkDatas($this->KDIn["datas"]);
        
        $this->KDOut["vmode"] = ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) ? "BAD_NEWS" : "GOOD_NEWS";
//        var_dump($r,$this->KDOut["vmode"]);
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
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["up","ui","ue","oei","k","ssid"];
        
        $STOI = new SESSION_TO(); //ASTUCE : Permet d'accéder aux methodes grace à auto-imp
        $STOI = $_SESSION["sto_infos"];
        
        $in_datas = $STOI->getCurr_wto()->getUps_required();
        
        /*
         * On vérifie que les données sont bien présentes sinon on déclenche une erreur
         * Normallement, c'est impossible. Mais on ne préfère prendre aucun risque.
         */
        if (! isset($in_datas) ) {
            $this->signalError ("err_sys_l7comn1", __FUNCTION__, __LINE__);
        }
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($in_datas), $EXPTD)) ) {
            $this->signalError ("err_sys_l7comn1", __FUNCTION__, __LINE__);
        }
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        $in_datas_keys = array_keys($in_datas);
        foreach ($in_datas_keys as $k => $v) {
            if (! isset($v) && $v !== "" ) {
                $this->signalError ("err_sys_l7comn1", __FUNCTION__, __LINE__);
            }
        }

        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
    }

    public function on_process_in() {
        $this->RecoveryChangeFinalHandler();
    }

    public function on_process_out() {
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        
        // A utiliser pour les DATX fournis sous forme de tableau. On boucle pour créer les row
        //*
        foreach ( $this->KDOut as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
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