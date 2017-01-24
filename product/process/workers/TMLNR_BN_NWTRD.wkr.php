<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_BN_NWTRD extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply ($art_tab) {
        
        //On vérifie si l'utilisateur actif est le propriétaire de l'Article
        if ( intval($art_tab["art_accid"]) === intval($this->KDIn["oid"]) ) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    private function DoesItComply_Datas () {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //On vérifie s'il s'agit d'un cas de body
            if ( $k === "t" ) {
                
                //On vérifie que les données pour le "body" du commentaire sont valides selon les règles en vigueur au niveau du WORKER
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
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation préléminaire de l'URL
            if ( ( $k === "cl" ) && !filter_var($v, FILTER_VALIDATE_URL) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            $TRD = new TREND();
            //Validationde la Participation
            if ( ( $k === "pt" ) && !in_array($v, $TRD->get_AKX_CHOICES_EXT() ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
        }
        
    }
    
    private function CreateTrend () {
        //* On crée la Tendance *//
        /*
         * RAPPEL : Ce WORKER doit être appelé dans le contexte de création d'une Tendance lorsque la page de référence est TMLNR !!!
         */
         
        //On s'assure que les données reçues sont non nulles et conformes
        $this->DoesItComply_Datas();
        
        $TRD = new TREND();
        //RAPPEL données entrantes : ["t","d","c","pt","cl"];
        
        //RAPPEL POUR ON_CERATE : ["trd_title","trd_desc","catg_decocode","trd_is_public","trd_grat","trd_loc_numip","trd_oid"]
        $ntr_args = [
            "trd_title" => $this->KDIn["datas"]["t"],
            "trd_desc" => $this->KDIn["datas"]["d"],
            "catg_decocode" => $this->KDIn["datas"]["c"],
            "trd_is_public" => ( $this->KDIn["datas"]["pt"] === "_NTR_PART_PUB" ) ? 1 : 0,
            "trd_grat" => 0,
            "trd_loc_numip" => $this->KDIn["locip"],
            "trd_oid" => $this->KDIn["oid"]
        ];
        
        $trd_tab = $TRD->on_create_entity($ntr_args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $trd_tab) ) {
            $this->Ajax_Return("err",$trd_tab);
        }
        
        
        //On vérifie si l'utilisateur est sur son compte
        /*
         * On utilise l'url envoyée par FE
         */
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
            $this->Ajax_Return("err",$url_tab);
        } else {
            
            //On vérifie si on a une URL valide et identifiable selon TQR
            if ( $url_tab && is_array($url_tab) && count($url_tab) ) {
                
                //** On récupère les données supplémentaires s'il y a une correpondance de pseudo **//
            
                if ( isset($url_tab["user"]) && ( strtolower($url_tab["user"]) === strtolower($this->KDIn["opsd"]) ) ) {
                    
                    $A = new PROD_ACC();
                    $A->on_read_entity(["accid" => $this->KDIn["oid"]]);
                        
                    //Le nombre de Tendances de l'utilisateur
                    $this->KDOut["FE_DATAS"]["tr_nb"] = $A->getPdacc_stats_mytrends_nb();

                    /*
                     * [NOTE 13-09-14] @author L.C.
                     * !!!! ATTENTION !!!!
                     * Si on doit changer les clés, il faut aussi les modifiers dans les fichiers *.js en FE qui les utilisent.
                     * Il s'agit à cette date de "newtr.brain.d.js" 
                     */
                    
                }
                
                //FINALLY
                 
                $url = $trd_tab["trd_href"];
                
                if ( isset($url_tab["hcompl"]) ) {
                    $url = "/".$url_tab["hcompl"].$url;
                }
                
                //L'URL de la Tendance. FE s'en sert pour rediriger l'utilisateur.
                $this->KDOut["FE_DATAS"]["tul"] = $url;   
                $this->KDOut["TRD_TAB"] = $trd_tab;   
                
            } else {
                $this->Ajax_Return("err","__ERR_VOL_MISMATCH_RULES");
            }
            
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
        $EXPTD = ["t","d","c","pt","cl"];
        
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
        $exists = $A->exists_with_id($oid, TRUE);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["outrnb"] = $A->getPdacc_stats_mytrends_nb();
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->CreateTrend();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        
        $t_tab = $this->KDOut["TRD_TAB"];
        $args_srh = [
            "srh_tr_id"     => $t_tab["trid"],
            "srh_tr_eid"    => $t_tab["trd_eid"],
            "srh_tr_tle"    => $t_tab["trd_title"],
            "srh_tr_desc"   => $t_tab["trd_desc"],
            "srh_tr_tlehrf"  => $t_tab["trd_title_href"],
            //DONNEES EXTRAS
            "srh_tr_fol"    => $t_tab["trd_stats_subs"],
            "srh_tr_post"   => $t_tab["trd_stats_posts"],
            //DONNEES ONWER
            "srh_tr_owid"   => $t_tab["trd_oid"],
            "srh_tr_oweid"  => $t_tab["trd_oeid"],
            "srh_tr_owpsd"  => $t_tab["trd_opsd"],
            "srh_tr_owfn"   => $t_tab["trd_ofn"]
        ];
        
        $TRD = new TREND();
        $TRD->oncreate_archive_trend($args_srh);
        
        exit(); //PARANO
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>