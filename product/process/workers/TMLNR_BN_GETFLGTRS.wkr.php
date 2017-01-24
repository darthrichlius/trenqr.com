<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_BN_GETFLGTRS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function AcquiereFollowingTrendsList () {
        $uid = $this->KDIn["uid"];
        /*
        *  trd_eid
        *  trd_title
        *  trd_desc
        *  trd_posts_nb
        *  trd_abos_nb
        //*/
        
        $A = new PROD_ACC();
        $r = $A->onread_acquiere_following_trends_datas($uid);
//        var_dump(__FILE__,__LINE__,$r);
//        exit();
        
        /* On ne récupère que les données dont on a réellement besoin */
        $folg_trends = [];
        if ( $r && is_array($r) && count($r) ) {
            foreach ( $r as $k => $trd ) {
                $tdl = FALSE;
                if ( ( key_exists("tsh_state",$trd) && isset($trd["tsh_state"]) && intval($trd["tsh_state"]) === 4 )
                        && ( key_exists("tsh_state_time",$trd) && isset($trd["tsh_state_time"]) && is_string($trd["tsh_state_time"]) && strpos($trd["tsh_state_time"],",") !== FALSE ) ) 
                {
                    $tdl = TRUE;
                }
//                var_dump(key_exists("trd_is_public",$trd), isset($trd["trd_is_public"]), $trd["trd_is_public"], intval($trd["trd_is_public"])); 
                $folg_trends[] = [
                    "trd_eid"       => $trd["trd_eid"],
                    "trd_title"     => $trd["trd_title"],
                    /*
                     * [DEPUIS 02-05-15] @BOR
                     * la donnée a subit un echappement de type htmlentities() donc ...
                     */
                    "trd_desc"      => html_entity_decode($trd["trd_desc"]),
//                    "trd_desc"      => $trd["trd_desc"],
                    "trd_href"      => $trd["trd_href"],
                    "trd_iprv"      => ( key_exists("trd_is_public",$trd) && isset($trd["trd_is_public"]) && intval($trd["trd_is_public"]) === 1 ) ? FALSE : TRUE,
                    "trd_posts_nb"  => ( key_exists("trd_stats_posts",$trd) && isset($trd["trd_stats_posts"]) ) ? $trd["trd_stats_posts"] : 0,
                    "trd_abos_nb"   => ( key_exists("trd_stats_subs",$trd) && isset($trd["trd_stats_subs"]) ) ? $trd["trd_stats_subs"] : 0,
                    "trd_tdl"       => $tdl
                ];
            }
            
            $this->KDOut["ftrs"] = $folg_trends; 
        } else {
            $this->KDOut["ftrs"] = NULL; 
        }
        
//        var_dump($this->KDOut["ftrs"]);
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
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        $this->KDIn["uid"] = $oid;
    }

    public function on_process_in() {
        $this->AcquiereFollowingTrendsList();
        
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["ftrs"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>