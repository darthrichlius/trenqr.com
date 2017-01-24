<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_CNX_TDLACT extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
            $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
            $rbody = $this->KDIn["datas"][$k];

            preg_match_all("/(\n)/", $rbody, $m_c1);
            preg_match_all("/(\r)/", $rbody, $m_c2);
            preg_match_all("/(\r\n)/", $rbody, $m_c3);
            preg_match_all("/(\t)/", $rbody, $m_c4);
            preg_match_all("/(\s)/", $rbody, $m_c5);

            //Parano : Je sais que j'aurais pu ne mettre que \s
            if ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
          
            //CAS SPECIAUX : Scope
            $fm = ["git","kpit"];
            if ( $k === "scp" && !in_array($v, $fm) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
        }
        
    }
    
    private function HandleTodDelCase() {
        
        $this->DoesItComply_Datas();
        
        $TQCNX = new TQR_CONX();
        $result = $TQCNX->HandleToDelCase($this->KDIn["oid"],  session_id(), $this->KDIn["datas"]["scp"]);
        //$result = "__ERR_VOL_NOT_TD"; //[TRUE, FALSE, "__ERR_VOL_DFNTLY_TD", "__ERR_VOL_NOT_TD"] //DEV, TEST, DEBUG
        
        if (! $result ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $result) && $result !== "__ERR_VOL_DFNTLY_TD" && $result !== "__ERR_VOL_NOT_TD") {
            $this->Ajax_Return("err",$result);
        } else if ( $result === "__ERR_VOL_DFNTLY_TD" ) {
            $result = "DFNTLY_TD";
        } else if ( $result === "__ERR_VOL_NOT_TD" ) {
            $result = "NOT_TD";
        }
        
        $FE = [];
        if ( strtoupper($this->KDIn["datas"]["scp"]) === "GIT" ) {
            if ( $result !== "DFNTLY_TD" ) {
                $FE["h"] = $_SESSION["rsto_infos"]->getUpseudo();
            }
            $FE["r"] = $result;
        } else {
            $FE = $result;
        }
        
        $this->KDOut["FE_DATAS"] = $FE;
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
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "qt" : QueryText,
         * "iqsp" : InscriptionQueryScoPe
         * "t": Le temps en millisecondes au moment de la requete. Permet à FE de n'afficher que le résultat le plus récent
         * "x": eXtra datas. L'utilisateur du WORKER peut passer des données supplémentaires. Cette valeur peut être vide.
         *      Elle est obligatoire pour certaines requêtes
         */
        $EXPTD = ["scp","cl"];

        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MISG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        foreach ($in_datas_keys as $k => $v) {
            if (! ( isset($v) && ( $v != "" || $k === "x") ) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MISG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }
        
        $CXH = new CONX_HANDLER();
        if (! $CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        } 
        
//        var_dump($_SESSION["rsto_infos"]);
//        var_dump($_SESSION["rsto_infos"]->getAcc_id());
//        exit();

        $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
//        var_dump($this->KDIn["oid"]);
//        exit();
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["datas"]["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
    }

    public function on_process_in() {
        $this->HandleTodDelCase();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>