<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_GT_STGS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function GetSettingsDatas () {
        $TRD = new TREND();
        $t_tab = $TRD->on_read_entity(["trd_eid" => $this->KDIn["datas"]["ti"]]);
                
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab) ) {
            $this->Ajax_Return("err",$t_tab);
        } 
        /*
        $TH = new TEXTHANDLER();
        
        $cat_lib = ( $t_tab["trd_is_public"] ) ? $TH->get_deco_text('fr', "_Public") : $TH->get_deco_text('fr', "_Private");
         
        if ( !$cat_lib || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cat_lib) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } 
        */
        $TXH = new TEXTHANDLER();
        $cov_datas = NULL;
        if ( $t_tab["trd_cover"] ) {
            $cov_datas = [
                "cov_w" => $t_tab["trd_cover"]["trcov_width"],
                "cov_h" => $t_tab["trd_cover"]["trcov_height"],
                "cov_t" => $t_tab["trd_cover"]["trcov_top"],
                "cov_rp" => $t_tab["trd_cover"]["pdpic_realpath"],
            ];
        }
        $c_dc = "_NTR_CATG_".strtoupper($t_tab["catg_decocode"]);
        $c_dc_t = $TXH->get_deco_text("fr", $c_dc);
        $t_infos = [
            "t" => $t_tab["trd_title"],
            "d" => html_entity_decode($t_tab["trd_desc"]),
            "c" => [$c_dc,$c_dc_t],
            //pub, pri
//            "p" => ( $t_tab["trd_is_public"] ) ? ["pub",$cat_lib] : ["pri",$cat_lib],
            "p" => ( $t_tab["trd_is_public"] ) ? ["_NTR_PART_PUB","Public"] : ["_NTR_PART_PRI","Prive"],
//            "g" => "0", //0, 1, 2, 3, 5, 10
            "trcov" => $cov_datas,
            "pnb" => $t_tab["trd_stats_posts"],
            "sbs" => $t_tab["trd_stats_subs"],
            "oid" => $t_tab["trd_oid"],
            "oeid" => $t_tab["trd_oeid"],
            "ofn" => $t_tab["trd_ofn"],
            "opsd" => $t_tab["trd_opsd"],
            "oppic" => $t_tab["trd_oppic"],
            "ohref" => "/@".$t_tab["trd_opsd"]
        ];
        
        $this->KDOut["FE_DATAS"] = $t_infos;    
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
        $EXPTD = ["ti"];
        
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
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        
            //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->on_read_entity(["acc_eid" => $_SESSION["rsto_infos"]->getAcc_eid()]);

            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }

            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["oppic"] = $exists["pdacc_uppic"];

            $this->KDIn["datas"] = $in_datas;
        } else {
            
             $this->KDIn["datas"] = $in_datas;
             
        }
    }

    public function on_process_in() {
        
        $this->GetSettingsDatas();
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