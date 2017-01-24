<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_FRDCTR_UFRD extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function Unfriend() {
        //* BUT : Briser la relation entre CU et l'utilisateu cible *//
        
        //On récupère les données sur l'utilisateur cible en demandant s'il existe
        $PA = new PROD_ACC();
        $exists = $PA->exists($this->KDIn["datas"]["i"]);
        
        if ( !isset($exists) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else if (! $exists) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        } 
        
        $RL = new RELATION();
        //On va vérifier s'il y réellement une demande entre les deux protagonistes ou Cu est target
        $rel_tab = $RL->friend_theyre_friends($this->KDIn["oid"], $exists["pdaccid"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rel_tab) ) {
            $this->Ajax_Return("err",$rel_tab);
        } else if ( $rel_tab ) {
            
            //On brise la relation d'amis
            $r = $RL->friend_break_friend_relation($this->KDIn["oid"], $exists["pdaccid"]);
//            $r = TRUE; //TEST, DEBUG
               
            if ( !$r || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                $this->Ajax_Return("err",$r);
            } else {
                //On vérifie qu'elle est la nouvelle relation entre les Protas
//                $args = [
//                    "actor" => $this->KDIn["oid"],
//                    "target" => $exists["pdaccid"]
//                ];

                $r = $RL->onread_relation_exists_fecase($this->KDIn["oid"],$exists["pdaccid"]);
                
                if ( !isset($r) ) {
                    //[NOTE 04-09-14] A vrai dire je ne sais pas quoi renvoyer dans ce cas excepté : "__ERR_VOL_FAILED"
                    $this->Ajax_Return("err","__ERR_VOL_FAILED");
                } else if (! $r ) {
                    //Même si c'est peu logique. Si c'était VOID, la relation serait de type VOID ...
                    $this->KDOut["FE_DATAS"] = "_REL_VOID";
                } else {
                    //Ajout au 19-10-14
                    $cr = $RL->encode_relcode($r);
                    
                    if ( !$r || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cr) ) {
                        $this->Ajax_Return("err","__ERR_VOL_UXPTD");
                    }
                
                    $this->KDOut["FE_DATAS"] = $cr;
                }
                
            }
            
        } else {
            $this->Ajax_Return("err","__ERR_VOL_NO_FRD");
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
        $EXPTD = ["i","rl","cl"];
        
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
        $this->Unfriend();
        
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