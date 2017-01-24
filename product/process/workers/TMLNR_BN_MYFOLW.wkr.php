<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_BN_MYFOLW extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function CreateFollowersList () {
        
        $A = new PROD_ACC();
        $list = $A->onread_acquiere_my_followers($this->KDIn["oid"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $list)  ) {
//        if ( !isset($list) || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $list)  ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        $FE_DATAS = NULL;
        
        foreach ($list as $k => $data) {
            
            $FE_DATAS[$k] = [
            
                "ueid"      => $data["pdacc_eid"],
                "ufn"       => $data["pdacc_ufn"],
                "upsd"      => $data["pdacc_upsd"],
                "uppic"     => $data["pdacc_uppic"],
                "uhref"     => $data["pdacc_upsd"],
                "upflbio"   => $data["pdacc_profilbio"],
                
//                "from" => $data["tbrel_datecrea_tstamp"], //PLus tard
            ];
            
            //On ajuste la chaine de relation a renvoyé
            if ( $data["tbrel_relsts"] === 2 ) {
                $FE_DATAS[$k]["urel"] = ["flr","flg"];
            } else {
                $FE_DATAS[$k]["urel"] = ["flr"];
            }
            
        }
        
        
        $this->KDOut["FE_ART"] = $FE_DATAS;        
        
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
        session_start();
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
    }

    public function on_process_in() {
        $this->CreateFollowersList();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_ART"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>