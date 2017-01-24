<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_FRDCTR_DCLN extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function Decline() {
        //* BUT : refuser demande d'amis *//
        
        //On récupère les données sur l'utilisateur cible en demandant s'il existe
        $PA = new PROD_ACC();
        $exists = $PA->exists($this->KDIn["datas"]["i"]);
        
        if (! isset($exists) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else if (! $exists) {
            $this->Ajax_Return("err","__ERR_VOL_TARGET_GONE");
        } 
        
        $RL = new RELATION();
        //On va vérifier s'il y réellement une demande entre les deux protagonistes ou Cu est target
        $rqt = $RL->friend_askfriend_request_exists($this->KDIn["oid"], $exists["pdaccid"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rqt) ) {
            $this->Ajax_Return("err",$rqt);
        } else if ( $rqt ) {
            //On vérifie que Cu était bien la personne ciblée dans la demande
            if (! ( ( intval($rqt["rlev_acc_targ"]) === intval($this->KDIn["oid"]) ) && ( intval($rqt["rlev_acc_actor"]) === intval($exists["pdaccid"]) ) ) ) {
                $this->Ajax_Return("err","__ERR_VOL_FRDRSQT_BAD_REQUEST");
            } else {
                //On procède à l'annulation de la demande
                $r = $RL->friend_reject_request($this->KDIn["oid"], $exists["pdaccid"], $rqt["frdrqtid"]);
//                $r = true; //TEST, DEBUG
            }
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FRDRSQT_NOT_FOUND");
        }
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        } else {
            $this->KDOut["FE_DATAS"] = "DONE";
        }
        
    }

    /****************** END SPECFIC METHODES ********************/
    
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION
        session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["i"];
        
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
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        
        if ( $this->KDIn["oeid"] === $in_datas["i"] ) {
            $this->Ajax_Return("err","__ERR_VOL_SAME_PROTAS");
        }
        
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->Decline();
        
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