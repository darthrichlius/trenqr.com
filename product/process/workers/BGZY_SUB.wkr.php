<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_BGZY_SUB extends WORKER  {
    
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
            
            if ( $k === "x" )
                continue;
            else {
               //Les données ont déjà été vérifiées
//            if (! ( isset($v) && $v !== "" ) ) {
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
//            }
            
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

            }    
            
        }
        
    }
    
    private function Submit() {
        $this->DoesItComply_Datas();
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        
        $TQR = new TRENQR($STOI->getProd_xmlscope());
        $args = [
            "accid" => $this->KDIn["oid"],
            "bgzy_type" => $this->KDIn["datas"]["type"],
            "bgzy_where" => $this->KDIn["datas"]["where"],
            "bgzy_when" => $this->KDIn["datas"]["when"],
            "bgzy_message" => $this->KDIn["datas"]["message"],
            "bgzy_lang" => $this->KDIn["datas"]["lang"],
            "bgzy_url" => $this->KDIn["datas"]["url"],
            "bgzy_scrn_w" => $this->KDIn["datas"]["scrn_w"],
            "bgzy_scrn_h" => $this->KDIn["datas"]["scrn_h"],
            "ssid" => session_id(),
            "srvip" => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "srvname" => $_SERVER["SERVER_NAME"],
            "user_agent" => $_SERVER["HTTP_USER_AGENT"],
            "locip" => $this->KDIn["locip"]
        ];
        
        $FE = $TQR->ReportBug($args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $FE) ) {
            $this->Ajax_Return("err",$FE);
        }

        if ( !is_array($FE) && $FE === "DONE" ) {

            $FE = [
                "r" => $FE
            ];
        } else if ( is_array($FE) && $FE[0] === "FAILED" ) {
            $FE = [
                "r" => $FE[0],
                "anx" => $FE[1]
            ];
        } else {
            $this->Ajax_Return("err","__ERR_VOL_UXPTD");
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
        //On masque les erreurs NOTICE, WARNING car ça fausse les résultats cote FE
        @session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["type","where","when","message","lang","url","scrn_w","scrn_h"];
        
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
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid,TRUE);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_U_G");
        }
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $RSTOI->getAcc_eid();
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->Submit();
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