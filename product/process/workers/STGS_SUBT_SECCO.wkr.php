<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_STGS_SUBT_SECCO extends WORKER  {
    
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
    
    function SaveForm() {
        $this->DoesItComply_Datas();
        
        $FE = NULL;
        //On commence par vérifier que le mot de passe correspond
        $TQCNX = new TQR_CONX();
        $IsConf = $TQCNX->checkPwdForUser($this->KDIn["datas"]["cfpwd"],$this->KDIn["oid"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $IsConf) ) {
            $this->Ajax_Return("err",$IsConf);
        } else if (! $IsConf ) {
            $FE = [
                "r" => "_FALD_CFP"
            ];
        } else {
            $args = [
                "accid" => $this->KDIn["oid"],
                "sec_ecwpsd" => $this->KDIn["datas"]["ecwpsd"],
                "locip" => $this->KDIn["locip"]
            ];

            $TQACC = new TQR_ACCOUNT();
            $FE = $TQACC->onalter_seculog($args);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $FE) ) {
                $this->Ajax_Return("err",$FE);
            }

            if ( is_array($FE) && $FE[0] === "DONE" ) {
                
                //On doit mettre à jour SESSION sinon l'utilisateur aura un problème pour accéder de nouveau à son COMPTE
                $PDACC = new PROD_ACC();
                $PDACC->on_read_entity(["acc_eid"=>$this->KDIn["oeid"]]);
                
                $RSTOI = new RSTO_INFOS();
                $sr = $RSTOI->on_alter($PDACC);
                
                if (! $sr ) {
                    $this->Ajax_Return("err","__ERR_VOL_UXPTD");
                }
                
                $FE = [
                    "r" => $FE[0],
                    "anx" => $FE[1]
                ];
            } else if ( is_array($FE) && $FE[0] === "FAILED" ) {
                $FE = [
                    "r" => $FE[0],
                    "anx" => $FE[1]
                ];
            } else {
                $this->Ajax_Return("err","__ERR_VOL_UXPTD");
            }
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
        $EXPTD = ["ecwpsd","cfpwd"];
        
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
        $exists = $A->exists_with_id($oid);
        
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
        $this->SaveForm();
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