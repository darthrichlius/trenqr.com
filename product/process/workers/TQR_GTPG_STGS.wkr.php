<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TQR_GTPG_STGS extends WORKER  {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function EC_Handle() {
        /*
         * Retourner un rapport comportant les réponses suivantes :
         *  -> Doit-on traiter le cas d'une validation de compte par Email ?
         *  NON : "_EC_STT_NO_EC"
         *  OUI :
         *      -> CAS 1 : Dans le cas où le compte n'a jamais été validé > Faut-il lancer une opération de validation de compte ?
         *      OUI :
         *          -> Faut-il restreindre le compte en attendant que la validation ait-été effectuée ?
         *          NON : "_EC_STT_INFO"
         *          OUI : "_EC_STT_LOCKNOW"
         *      -> CAS 2 : Dans le cas où le compte n'a jamais été validé > Le Compte est en attente de validation ?
         *      OUI :
         *          -> Faut-il restreindre le compte en attendant que la validation ait-été effectuée ?
         *          NON : "_EC_STT_INFO"
         *          OUI : "_EC_STT_LOCKNOW"
         *      -> CAS 3 : S'agit-il du cas d'une confirmation ?
         *      OUI :
         *          -> Les données liées à la demande sont-elles valides ?
         *          +
         *          -> La clé liée à la demande correspond elle à une demande cloturée il y a moins de 2 minutes ?
         *          =
         *          -> "_EC_STT_WELCOME"
         */     
        $TA = new TQR_ACCOUNT();
        
        $ueid = $this->KDIn["owner"]["pdacc_eid"];
        /*
         * ETAPE :
         *      On vérifie si le compte a été validé au moins une fois 
         */
        if ( $TA->EC_AccIsCnfrmdOnce($ueid) === TRUE && !( $this->KDIn["ups_optional"] && key_exists("ec_case",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["ec_case"] === "econfirm_final" ) ) {
            return "_EC_STT_NO_EC";
        } else if ( $TA->EC_AccIsCnfrmdOnce($ueid) !== TRUE ) {
            
            /*
             * ETAPE :
             *      On vérifie s'il y a une demande en attente.
             */
            $r1__ = $TA->EC_GetLastPending($ueid);
            if ( $r1__ ) {
                /*
                 * ETAPE : 
                 *      On vérifie s'il s'agit de la première session du compte
                 */
                $this->KDOut["econfirm"]["ec_key"] = $r1__["cnfeml_key"];
                /*
                 * [DEPUIS 24-11-15] @author BOR
                 */
                $sent_date = date("d/m/Y à H:i",($r1__["cnfeml_sntdate_tstamp"]/1000));
                $this->KDOut["econfirm"]["ec_sntdate"] = $sent_date; 
                return "_EC_STT_LOCKNOW";
            }

            /*
             * ETAPE :
             *      On vérifie si une opération d'attente de validation est en cours.
             *      Sinon on la lance une opération 
             */
            $r2__ = $TA->EC_NewOperIfNotVald($ueid, $this->KDIn["locip"], "ACCOUNT_CREATION", session_id(), NULL, $this->KDIn["uagent"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r2__) ) {
                $msg = "[ERR_NUM = err_sys_l7emcnfn1] ***** [ERR_VOL_MESSAGE = $r2__]";
                $this->signalErrorWithoutErrIdButGivenMsg($msg, __FUNCTION__, __LINE__);
                exit(); //PARANO
            } else {
                $ec__ = $TA->EC_GetLastPending($ueid);
                $this->KDOut["econfirm"]["ec_key"] = $ec__["cnfeml_key"];
                /*
                 * [DEPUIS 24-11-15] @author BOR
                 */
                $sent_date = date("d/m/Y à H:i",($ec__["cnfeml_sntdate_tstamp"]/1000));
                $this->KDOut["econfirm"]["ec_sntdate"] = $sent_date; 
                return "_EC_STT_LOCKNOW";
            }
            
        }
        
        /*
         * ETAPE :
         *      On vérifie si on est dans le cas d'une opération liée à la validation de Compte par l'email.
         *      Dans ce contexte il ne peut s'agir que de la validation définitive d'un Compte en utilisant l'email.
         */
        if ( $this->KDIn["ups_optional"] && key_exists("ec_case",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["ec_case"] === "econfirm_final" ) {
            /*
             * ETAPE : 
             *      On vérifie que les données necessaires sont disponibles
             */
            $errs = 0;
            foreach ( ["ec_k","ec_c"] as $kv ) {
                if ( in_array($kv, $this->KDIn["ups_optional"]) ) {
                    $errs++;
                }
            }
            if ( $errs ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["ERR_NUM => ",$errs],'v_d');
                $this->signalError("err_sys_l7emcnfn2",__FUNCTION__, __LINE__);
            } else {
               /*
                * ETAPE : 
                *      On vérifie que les données concordent
                */
                $ec_tab = $TA->EC_Exists($this->KDIn["ups_optional"]["ec_k"]);
                if ( $ec_tab ) {
                    if ( $this->KDIn["ups_optional"]["ec_k"] !== $ec_tab["cnfeml_key"] | $this->KDIn["ups_optional"]["ec_c"] !== $ec_tab["cnfeml_code"] ) {
                        $this->signalError("err_user_l5e404_any",__FUNCTION__, __LINE__);
                    } else if ( $ec_tab["cnfeml_infodt_tstamp"]  ) {
                        return "_EC_STT_NO_EC";
                    } 
                    
                    /*
                     * On va signaler que le Message de confirmation va être (a été) présenté à l'utilisateur.
                     * Pour cela, nous allons mettre à jour l'occurence liée
                     */    
                    $TA->EC_EndOper($ec_tab["cnfeml_key"]);
                    return "_EC_STT_WELCOME";
                } else {
                    $this->signalError("err_user_l5e404_any",__FUNCTION__, __LINE__);
                }
                
            }
            
        } 
        
    }
    
    
    /* ---- CURRENT OWNER IDENTITY ---- */
    private function AcquiereActorsDatas() {
        //Données sur l'utilisateur en cours
        $CU["cueid"]    = $this->KDIn["owner"]["pdacc_eid"];
        $CU["cuppic"]   = $this->KDIn["owner"]["pdacc_uppic"];
        $CU["cufn"]     = $this->KDIn["owner"]["pdacc_ufn"];
        $CU["cupsd"]    = $this->KDIn["owner"]["pdacc_upsd"];
        $CU["cuhref"]   = "/@".$this->KDIn["owner"]["pdacc_upsd"];
        $CU["cucityid"] = $this->KDIn["owner"]["pdacc_ucityid"];
        $CU["cucity"]   = $this->KDIn["owner"]["pdacc_ucity_fn"];
        $CU["cucn_fn2"] = strtoupper($this->KDIn["owner"]["pdacc_ucnid"]);
        
        //Données sur l'utilisateur qualifié de OWNER
        $OW["oueid"]    = $this->KDIn["owner"]["pdacc_eid"];
        $OW["ouppic"]   = $this->KDIn["owner"]["pdacc_uppic"];
        $OW["oufn"]     = $this->KDIn["owner"]["pdacc_ufn"];
        $OW["oupsd"]    = $this->KDIn["owner"]["pdacc_upsd"];
        $OW["ouhref"]   = "/@".$this->KDIn["owner"]["pdacc_upsd"];
        
        /*
         * [NOTE 20-10-2014] @author L.C.
         * On inscrit le code relation.
         * Il s'agit de l'utilisateur sur une page qui l'appartient. Aussi, on inscrit le code à la main.
         * L'endroit (le tableau utilisé) où on stocke le code n'a aucune réelle important.
         * Le plus important est de déclarer la donnée avec le bon code.
         */
        
        $CU["urel"] = "xrh";
        
//        array(23) { ["pdaccid"]=> string(3) "112" ["pdacc_gid"]=> string(1) "2" ["pdacc_eid"]=> string(14) "2n3hfn1n403n4k" ["pdacc_upsd"]=> string(16) "InsRedirection12" ["pdacc_ufn"]=> string(20) "Ins Redirection Douz" ["pdacc_uppicid"]=> string(1) "1" ["pdacc_uppic"]=> string(27) "path/to/profile/img/min.jpg" ["pdacc_coverpicid"]=> string(1) "2" ["pdacc_coverpic"]=> string(13) "path/to/cover" ["pdacc_ucityid"]=> string(7) "4647963" ["pdacc_ucity_fn"]=> string(5) "Paris" ["pdacc_nocity"]=> NULL ["pdacc_ucnid"]=> string(2) "us" ["pdacc_ucn_fn"]=> string(13) "United States" ["pdacc_udl"]=> string(2) "fr" ["pdacc_datecrea"]=> string(19) "2014-08-29 14:06:33" ["pdacc_datecrea_tstamp"]=> string(13) "1409313993367" ["pdacc_todelete"]=> string(1) "0" ["pdacc_ctw_dsma"]=> string(1) "0" ["pdacc_ctw_moddate"]=> NULL ["pdacc_ctw_moddate_tstamp"]=> NULL ["pdacc_profilbio"]=> string(0) "" ["pdacc_capital"]=> string(1) "0" }
        

//<meta property="tq:ueid" content="dfv55sdfv" />
//<meta property="tq:uppic" content="http://lorempixel.com/70/70" />
//<meta property="tq:ufn" content="Pierre Lallement" />
//<meta property="tq:upsd" content="Syel" />
//<meta property="tq:uhref" content="/@Syel" />
        
        $this->KDOut["OD"] = $OW;
        $this->KDOut["CUD"] = $CU;
    }
        
    private function FeedBufferDatasZone() {
        
        $TQACC = new TQR_ACCOUNT();
        $acc_tab = $TQACC->on_read_entity(["acc_eid"=>$this->KDIn['owner']["pdacc_eid"]]);
        
        $TQR = new TRENQR();
        $prod_tab = $TQR->Trenqr_GetVersionInfos(1);
        $rm = $TQR->Trenqr_TranslateRm($prod_tab["tqrver_runmode"]);
        
        /*
         * Le TIMESTAMP n'est pas en millisecondes
         */
        $bdy = date("d-m-Y",$acc_tab["pfl_bdate_tstamp"]);
        
        $this->KDOut["BD"] = [
            "stgs_bdz_fn"           => $acc_tab["pfl_fn"],
            "stgs_bdz_bd"           => $bdy,
            "stgs_bdz_bd_tstamp"    => $acc_tab["pfl_bdate_tstamp"],
            "stgs_bdz_bd_d"         => explode("-",$bdy)[0],
            "stgs_bdz_bd_m"         => explode("-",$bdy)[1],
            "stgs_bdz_bd_y"         => explode("-",$bdy)[2],
            "stgs_bdz_bd_rmn"       => $acc_tab["pfl_bdate_mod_rem"],
            "stgs_bdz_gdr"          => $acc_tab["pfl_gender"],
            "stgs_bdz_gdr_rmn"      => $acc_tab["pfl_gender_mod_rem"],
            "stgs_bdz_cyi"          => $acc_tab["pfl_lvcity"],
            "stgs_bdz_cyn"          => $acc_tab["pfl_lvcity_name"],
            "stgs_bdz_cycncd"       => $acc_tab["pfl_lvcity_cncode"],
            "stgs_bdz_city"         => $acc_tab["pfl_lvcity_name"].", ".strtoupper($acc_tab["pfl_lvcity_cncode"]),
            "stgs_bdz_psd"          => $acc_tab["acc_psd"],
            "stgs_bdz_em"           => $acc_tab["acc_eml"],
            "stgs_bdz_lg"           => $acc_tab["acc_lang"],
            "stgs_bdz_ecwpsd"       => $acc_tab["secu_coWithPsdEna"],
            "stgs_bdz_nlg"          => $acc_tab["secu_notifyWhenLogin"],
            //st : Short
            "stgs_bdz_pddesc_st"    => ( $prod_tab["tqrver_short_desc"] ) ? $prod_tab["tqrver_short_desc"] : "",
            //lg : Long
            "stgs_bdz_pddesc_lg"    => ( $prod_tab["tqrver_long_desc"] ) ? $prod_tab["tqrver_long_desc"] : "",
            "stgs_bdz_pdlib"        => $prod_tab["tqrver_relzname"],
            "stgs_bdz_pdver"        => "tqr.beta.1.1509.0",
//            "stgs_bdz_pdver"        => $prod_tab["tqrver_code"],
            "stgs_bdz_pdrm"         => "Fonctionne normalement"
//            "stgs_bdz_pdrm"         => $rm
        ];
        
    }

    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        
        //TODO: Vérifier l'existance de User et Ups(xeu / k)
        //TODO: Vérifier concordance des pseudos
        //Vérifier la concordance de l'UEID avec le pseudo courant
        //Vérifier le SID'
        
        //RAPPEL : Si on est ici c'est parce que WOS a laissé passer la requete. On ne revérifie donc plus la présence de SESSION
        
        
//        var_dump($_SESSION);
//        exit();
        
//        if ( !empty($_POST) ) {
//            var_dump($_POST);
//            exit();
//        }
        
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
        
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        $oid = $RSTOI->getAccid();
        $A = new PROD_ACC();
        $u_tab = $A->on_read_entity(["accid"=>$oid]);
        
//        var_dump($exists);
//        exit();
        
        if ( !$u_tab || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab) ) {
            $RDH = new REDIR($STOI->getProd_xmlscope());
            $url = $RDH->redir_to_default_page("pdpage_welc_pgid");
            
            $RDH->start_redir_to_this_url_string($url);
        } else {
            
            $given_ups = $STOI->getCurr_wto()->getUps_required();
            $given_ups_opt = $STOI->getCurr_wto()->getUps_optional();
            
            //Verifie pseudo
            
//            ... si secu
            /*
            //RAPPEL : Normalement les UPS xeu et k existent, on gagne du temps en ne les controllant pas
            
            $given_sid = $given_ups["k"];
            $given_ueid = $given_ups["xeu"];
            
            //On vérifie la concordance entre les infos dans UPS et ceux dans SESSION
            if (! ( $given_sid === session_id() && $given_ueid === $u_tab["pdacc_eid"] ) )
            {
                $RDH->redir_to_default_page("pdpage_rest_own_pgid");
            }
            */
            
            if ( $given_ups_opt && key_exists("s", $given_ups_opt) && isset($given_ups_opt["s"]) && $given_ups_opt["s"] !== "" ) {
                $XPTD_SCT_EN = ["profile","account","security","delete","about"];
                $XPTD_SCT_FR = ["profil","compte","securite","suppression","apropos"];
                
                $XPTD_SCT = array_merge($XPTD_SCT_FR, $XPTD_SCT_EN);
                
//                var_dump($XPTD_SCT);
//                exit();
                
                $this->KDin["section"] = ( in_array($given_ups_opt["s"],$XPTD_SCT) ) ? $given_ups_opt["s"] : "profile";
            } else $this->KDin["section"] = NULL;
//            var_dump("o",$STOI->getCurr_wto(),$this->KDin["section"]);
//            exit();
            $this->KDIn['owner'] = $u_tab;
            
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
            
        }
        
    }
    
    public function on_process_in() {
        $ec_stt = $this->EC_Handle();
        if ( $ec_stt !== "_EC_STT_NO_EC" ) {
            $this->KDOut["econfirm"]["ec_is_ecofirm"] = TRUE;
            $this->KDOut["econfirm"]["ec_state"] = $ec_stt;
            $this->KDOut["econfirm"]["ec_scope"] = "TQR_TMLNR";
        }
        
        $this->AcquiereActorsDatas();
        $this->FeedBufferDatasZone();
    }

    public function on_process_out() {
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        //Données sur OWNER
        foreach ( $this->KDOut["OD"] as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        //Données pour la zone BUFFER
        foreach ( $this->KDOut["BD"] as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        //Données sur CU
        foreach ( $this->KDOut["CUD"] as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /*
         * [DEPUIS 25-10-15] @author BOR
         */
        foreach ($this->KDOut["econfirm"] as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /* PAGE HEAD DATAS */
        /*
         * Il s'agit des données destinées à être ajoutées au niveau du header de la page.
         */
        foreach ($this->KDout["head"] as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
     
        $_SESSION["ud_carrier"]["section"] = ( isset($this->KDin["section"]) ) ? $this->KDin["section"] : "profile";
        
        $_SESSION["ud_carrier"]["pagid"] = "settings";
        $_SESSION["ud_carrier"]["pgakxver"] = "ro";
        
    }
    
    /**
     * @obsolete
     */
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}

?>