<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TQR_EVT_PGTKFKS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function Process () {
        
        /*
         * LEXIQUE pour STS
         *      _CODE_2 : Présent
         *      _CODE_1 : Pas Présent mais était recemment là
         *      _CODE_0 : Pas Présent
         */
                
        $PM = new POSTMAN();
        $log_r = $this->Wkr_LogUsertagActy(
            $PM, 
            $this->KDIn["oid"], 
            $_SESSION['sto_infos']->getCurrent_ipadd(), 
            $this->KDIn["srv_curl"], 
            $this->KDIn["srv_curl"], 
            TRUE, 
            $this->KDIn["oid"], 
            50,
            TRUE, 
            $this->KDIn["datas"]["sts"],    
            $upieces["urqid"]
        );
                
        $this->KDOut["FE_DATAS"] = "DONE";
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
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        /*
         * On vérifie que toutes les données sont présentes
         * 
         * sts  : Le statut de connexion
         * cu   : Url
         */
        $EXPTD = ["cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MISSING");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
//            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
//        $oid = 102; //DEV, TEST, DEBUG
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        
        $exists = $A->exists_with_id($oid,TRUE);
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        /* //DEV, TEST, DEBUG
        $this->KDIn["oid"] = 102;
        $this->KDIn["oeid"] = "211kaahla61";
        //*/
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        exit();
        
//        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        if ( $upieces && is_array($upieces) && key_exists("page", $upieces) && !empty($upieces["page"]) ) {
//            $PDACC = new PROD_ACC();
//            $this->KDIn["target"] = $PDACC->exists_with_psd($upieces["user"],TRUE);
//            var_dump(__LINE__,$this->KDIn["target"]);
//            exit();
            
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        $this->Process();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}
?>