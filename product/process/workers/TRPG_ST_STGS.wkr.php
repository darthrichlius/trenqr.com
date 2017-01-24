<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_ST_STGS extends WORKER  {
    
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
            
            //Dans le cas de la donnée Participation
            if ( $k === "p" ) {
                $XPTD = ["_NTR_PART_PUB","_NTR_PART_PRI"];
                if (! in_array( strtoupper($this->KDIn["datas"]["p"]), $XPTD ) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                }
            }
            
        }
        
    }
    
    private function DoesItComply($t_tab) {
        /*
         * On va vérifier que l'utilisateur a effectivement le droit d'effectuer cette Action.
         */
        
        //On vérifie si la Tendance existe
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab) ) {
            $this->Ajax_Return("err",$t_tab);
        }
        
        //On vérifie que l'utilisateur actif est le propriétaire de la Tendance
        if ( intval($t_tab["trd_oid"]) !== intval($this->KDIn["oid"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        
    }
    
    
    private function SetSettingsDatas () {
        
        //On commence par s'assurer que les données sont là et correspondent à certains masques
        $this->DoesItComply_Datas();
        
        $TRD = new TREND();
        $t_tab = $TRD->on_read_entity(["trd_eid" => $this->KDIn["datas"]["ti"]]);
        
        //On vérifie si l'utilisateur a le droit de modifier les données de la Tendance
        $this->DoesItComply($t_tab);
        
        /*
         * J'ai préféré diviser la modification des éléments avec pour chaque élément une méthode. 
         * Aussi, on va vérifier pour chaque élément reçu, s'il est différent de celle déjà enregistré.
         * Dans ce dernier cas, on modfie la donnée.
         * Dans ce contexte, l'opération peut donc être plus ou moins longue.
         */
        $nt_tab = $ar = NULL;
        foreach ( array_keys($this->KDIn["datas"]) as $v) {
            switch ($v) {
                case "ti":
                        //On passe. On inscrit 'ti' pour éviter de tomber dans le cas de default
                    break;
                case "t":
                        if ( $this->KDIn["datas"]["t"] !== $t_tab["trd_title"] ) {
                            $ar = $TRD->setTrd_title($this->KDIn["datas"]["t"], $this->KDIn["oid"], FALSE, TRUE);
                        }
                    break;
                case "d":
                        if ( $this->KDIn["datas"]["d"] !== $t_tab["trd_desc"] ) {
                            $ar = $TRD->setTrd_desc($this->KDIn["datas"]["d"], $this->KDIn["oid"], FALSE, TRUE);
                        }
                    break;
//                case "c":
//                        if ( $this->KDIn["datas"]["c"] !== $t_tab["catg_decocode"] ) {
//                            $ar = $TRD->setTrd_catgid($this->KDIn["datas"]["c"], $this->KDIn["oid"], FALSE, TRUE);
//                        }
//                    break;
                case "p":
                        $tmp = ( $this->KDIn["datas"]["p"] === "_NTR_PART_PUB" ) ? 1 : 0;
                        if ( $tmp !== intval($t_tab["trd_is_public"]) ) {
                            $ar = $TRD->setTrd_is_public($this->KDIn["datas"]["p"], $this->KDIn["oid"], FALSE, TRUE);
                        }
                    break;
                default:
                        /*
                         * [NOTE 09-10-14] La seule explication dans ce cas est que l'utilisateur CU a modifié quelque chose au niveau de FE. 
                         * En effet, les codes ne sont jamais changés.
                         * 
                         * L'autre explication serait qu'un developpeur a modifié les codes lors d'une phase de développement, test ou debug
                         */
                        $this->Ajax_Return("err","__ERR_VOL_FAILED");
                    break;
            }
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ar) ) {
                $this->Ajax_Return("err",$ar);
            } 
        }
        
        //On re-read la Tendance
        $nt_tab = $TRD->on_read_entity(["trd_eid" => $this->KDIn["datas"]["ti"]]);
        
//        var_dump($nt_tab);
//        exit();
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nt_tab) ) {
            $this->Ajax_Return("err",$nt_tab);
        } 
        
        /*
        $TH = new TEXTHANDLER();
        
        $cat_lib = ( $t_tab["trd_is_public"] ) ? $TH->get_deco_text('fr', "_Public") : $TH->get_deco_text('fr', "_Private");
         
        if ( !$cat_lib || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cat_lib) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } 
        */
        
        $cov_datas = NULL;
        if ( $nt_tab["trd_cover"] ) {
            $cov_datas = [
                "cov_w"     => $nt_tab["trd_cover"]["trcov_width"],
                "cov_h"     => $nt_tab["trd_cover"]["trcov_height"],
                "cov_t"     => $nt_tab["trd_cover"]["trcov_top"],
                "cov_rp"    => $nt_tab["trd_cover"]["pdpic_realpath"],
            ];
        }
        
        $t_infos = [
            "t"     => $nt_tab["trd_title"],
            "d"     => html_entity_decode($nt_tab["trd_desc"]),
            "c"     => $nt_tab["catg_decocode"],
            //pub, pri
//            "p" => ( $nt_tab["trd_is_public"] ) ? ["pub",$cat_lib] : ["pri",$cat_lib],
            "p"     => ( $nt_tab["trd_is_public"] ) ? ["pub","Public"] : ["pri","Prive"],
//            "g" => "0", //0, 1, 2, 3, 5, 10
            "thrf"  => $nt_tab["trd_href"],
            "trcov" => $cov_datas,
            "pnb"   => $nt_tab["trd_stats_posts"],
            "sbs"   => $nt_tab["trd_stats_subs"],
            "oid"   => $nt_tab["trd_oid"],
            "oeid"  => $nt_tab["trd_oeid"],
            "ofn"   => $nt_tab["trd_ofn"],
            "opsd"  => $nt_tab["trd_opsd"],
            "oppic" => $nt_tab["trd_oppic"],
            "ohref" => "/@".$nt_tab["trd_opsd"]
        ];
        
        $this->KDOut["FE_DATAS"] = $t_infos;    
        $this->KDOut["TRD_TAB"] = $nt_tab;    
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
        @session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["ti","t","d","p"];
//        $EXPTD = ["ti","t","d","c","p"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_POST["datas"],'v_d');
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" && $v != "''" ) )  {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_POST["datas"],'v_d');
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
        $exists = $A->exists_with_id($oid, TRUE);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }

        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["oppic"] = $exists["pdacc_uppic"];

        $this->KDIn["datas"] = $in_datas;
             
    }

    public function on_process_in() {
        
        $this->SetSettingsDatas();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        
        //Mise à jour des données de la Tendance au niveau de SRH
        $TRD = new TREND();
        $TRD->onalter_update_archv_trend(["trid"=>$this->KDOut["TRD_TAB"]["trid"]]);
        
        exit(); //Parano
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>