<?php

class WORKER_FKSA_GTPG_TSTVER extends WORKER {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /***************************************************************************************************************/
    /************************************************ SPECIFICS ****************************************************/
    
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
        
        $ueid = $this->KDIn["curr"]["pdacc_eid"];
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
    
    
    private function cu_complies ($target) {
        //QUESTION : Est ce que l'utilisateur, au regard des règles de fonctionnement du produit, a le droit d'accéder à cette page?
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //* On vérifie que le pseudo est conforme au format attendu *//
        
        //On vérifie qu'on a bien l'user et que son identifiant ueid est défini 
        $A = new PROD_ACC();
        $exists = $A->exists_with_psd($target,TRUE);
        
        if (! $exists ) {
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
        } else {
            return $exists;
        }
    }
    
    private function CheckAuthor ($tst_tab){
        /*
         * Permet de vérifier le cas des relations entre CU et AOW (ArticleOWner)
         * En fonction du résultat on optimise au niveau des données ou au niveau des fonctionnalités
         */
       
        $TST = new TESTY();
        $cueid = ( $this->KDIn["curr"] && $this->KDIn["curr"]["pdacc_eid"] ) ? $this->KDIn["curr"]["pdacc_eid"] : NULL;
            
        if (! $TST->onread_hasPermission($tst_tab["tst_tgueid"],$cueid,TRUE) ) {
            $this->signalError ("err_user_l5e403_art", __FUNCTION__, __LINE__);
        }
    }
    
    private function urq_complies (){
        /*
         * Permet de vérifier que les données fournies en ce qui concerne l'Article ou l'OptionDeVue sont correctes.
         */
        
        //Vérification de la validité de l'identifiant de PerMaLinK
        $ART = new ARTICLE();
    }
    
    private function AcquireCUDatas() {
        /*
         * Permet de récupérer les données fondamentales en ce qui concerne l'Utilisateur connecté.
         */
        $curr = $this->KDIn["curr"];
        
        $accid = $curr["pdaccid"];

        $A = new PROD_ACC();
        $A->on_read_entity(["accid" => $accid]);

        //TODO : A quelle moment on sécurise les données ? (Entity ou ICI)

        $CU = [];
        $CU["cueid"] = $A->getPdacc_eid();
        $CU["cuppic"] = $A->getPdacc_uppic();
        $CU["cufn"] = $A->getPdacc_ufn();
        $CU["cupsd"] = $A->getPdacc_upsd();
        $CU["cuhref"] = "/@".$A->getPdacc_upsd();
        $CU["cucityid"] = $A->getPdacc_ucityid();
        $CU["cucity"] = $A->getPdacc_ucity_fn();
        $CU["cucn_fn2"] = $A->getPdacc_ucnid();

        /* On crée la donnée dans KDatas*/
        $this->KDout["CUser_TAB"] = $CU;
    }
    
    private function AcquireArticleDatas() {
        /*
         * Permet de récupérer les données sur l'Article et son propriétaire.
         */
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$this->KDIn["DsIn"]["pmlkid"]);
//        exit();
        
        $TST = new TESTY();
        //On vérifie que l'identifiant PERMALINK est présent et valide
        $tst_etab = $TST->exists_with_prmlk($this->KDIn["DsIn"]["pmlkid"]);
        if (! $tst_etab ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        } 
        $tst_tab = $TST->on_read_entity([ "tst_eid" => $tst_etab["tst_eid"] ]);
        
        $this->KDOut["ORI_TST_TAB"] = $tst_tab;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tst_tab);
//        exit();
        
        /*
         * ETAPE :
         *      Vérifier les autorisations d'accès au TESTY
         */ 
        $this->CheckAuthor($tst_tab);
        
        //*** On récupère toutes les données relatives aux TESTIES. Les données seront triées par CALLER. ***//
        
        $PA = new PROD_ACC();
        $FE = [];
            
        /*
         * ETAPE :
         *      On récupère les données sur les ACTORS
         */
        $tsotab = $PA->exists_with_id($tst_tab["tst_ouid"]);
        if (! $tsotab ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        }
        $tstgtab = $PA->exists_with_id($tst_tab["tst_tguid"]);
        if (! $tstgtab ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        }
            
        /*
         * ETAPE :
         *      On récupère les données sur TESTY
         */
        $FE = [
            "i"         => $tst_tab["tst_eid"],
            "tm"        => $tst_tab["tst_adddate_tstamp"],
            "m"         => html_entity_decode($tst_tab["tst_msg"]),
            "plk"       => $tst_tab["tst_prmlk"],
            "au"        => [
                "oid"       => $tsotab["pdacc_eid"],
                "ofn"       => $tsotab["pdacc_ufn"],
                "opsd"      => $tsotab["pdacc_upsd"],
                "oppic"     => $PA->onread_acquiere_pp_datas($tsotab["pdaccid"])["pic_rpath"],
            ],
            "tg"        => [
                "oid"       => $tstgtab["pdacc_eid"],
                "ofn"       => $tstgtab["pdacc_ufn"],
                "opsd"      => $tstgtab["pdacc_upsd"],
                "oppic"     => $PA->onread_acquiere_pp_datas($tstgtab["pdaccid"])["pic_rpath"],
            ],
            /*
             * cdl : CanDelete
             *      (1) L'utilisateur connecté est le propriétaire
             *      (2) L'utilisateur connecté est la cible du message
             */
            "cdl"       => ( floatval($this->KDIn["curr"]["pdaccid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["curr"]["pdaccid"]) === floatval($tst_tab["tst_tguid"]) ) ? TRUE : FALSE,
            /*
             * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
             * cgap : CanGetAccesstoPin
             */
            "cgap"      => ( $this->KDIn["curr"]["pdaccid"] && ( intval($this->KDIn["curr"]["pdaccid"]) === intval($tsotab["pdaccid"]) || intval($this->KDIn["curr"]["pdaccid"]) === intval($tst_tab["tst_tguid"]) ) ) ? TRUE : FALSE,
            //QUESTION ? Le TESTIMONY est-il PIN ?
            "isp"       => $TST->Pin_IsPin($tst_tab["tstid"]),
            "rnb"       => $TST->React_Count($tst_tab["tst_eid"]),
            /*
             * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
             * NOTE :
             *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
             */
            "clk"       => ( $this->KDIn["curr"]["pdaccid"] ) ? TRUE : FALSE,
            //QUESTION ? L'utilisateur a t-il LIKE ?
            "hslk"      => ( $this->KDIn["curr"]["pdaccid"] && $TST->Like_HasLiked($this->KDIn["curr"]["pdaccid"], $tst_tab["tstid"]) ) ? TRUE : FALSE,
            //QUESTION ? Le nombre de LIKE ?
            "cnlk"      => $TST->Like_Count($tst_tab["tst_eid"]),
            //Données sur les USERTAGS
            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst_tab["tst_eid"],TRUE),
            //Données sur les HASHTAGS
            "hashs"     => $TST->onread_AcquiereHashs_Testy($tst_tab["tst_eid"]),
            /*
             * [NOTE 31-05-16]
             *      On omet sciemment prmlk car, non seulement ce n'est pas necessaire, mais aussi pour ne pas obliger TLKBVIEW à gérer le changement d'URL.
             *      En effet, nous nous trouvons déjà sur la page FKSA...
             */
        ];
        
        $this->KDOut["ART_O"] = [
            "oid"       => $tsotab["pdacc_eid"],
            "ofn"       => $tsotab["pdacc_ufn"],
            "opsd"      => $tsotab["pdacc_upsd"],
            "oppic"     => $PA->onread_acquiere_pp_datas($tsotab["pdaccid"])["pic_rpath"],
        ];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$this->KDOut["ART_O"]);
//        exit();

        $this->KDOut["TST_TAB"] = $FE;
    }
    
    
    private function PageVersion() {
        /*
         * Permet de déterminer la version de la page.
         * La méthode permet aussi d'obtenir la donnée qui détermine si l'utilisateur est connecté.
         */
        
        if (! ( key_exists("curr", $this->KDIn) && $this->KDIn["curr"] && count($this->KDIn["curr"]) ) ) {
            $this->KDout["pgver"] = "WLC";
            $this->KDout["iauth"] = FALSE;
        } else if ( $this->KDIn["curr"]["pdacc_eid"] === $this->KDOut["TST_TAB"]["au"]["oid"] ) {
            $this->KDout["pgver"] = "RO";
            $this->KDout["iauth"] = TRUE;
        } else {
            $this->KDout["pgver"] = "RU";
            $this->KDout["iauth"] = TRUE;
        }
        
//        var_dump($this->KDout["pgver"],$this->KDout["iauth"]);
//        exit();
    }
    
    private function CustumHeaderDatas () {
        $this->KDout["head"]["oupsd"] = $this->KDOut["TST_TAB"]["au"]["opsd"];
        $this->KDout["head"]["oufn"] = $this->KDOut["TST_TAB"]["au"]["ofn"];
        
        $this->KDout["head"]["art_desc"] = $this->KDOut["TST_TAB"]["m"];
        
        if ( key_exists("hashs", $this->KDOut["TST_TAB"]) && $this->KDOut["TST_TAB"]["hashs"] && is_array($this->KDOut["TST_TAB"]["hashs"]) && count($this->KDOut["TST_TAB"]["hashs"]) ) {
            $this->KDout["head"]["head_keywords_list"] = implode(",",$this->KDOut["TST_TAB"]["hashs"]);
        }
        /*
         * [DEPUIS 25-09-15] @author BOR 
         */
        $this->KDout["head"]["aprmlk"] = $this->KDOut["TST_TAB"]["plk"];
        $this->KDout["head"]["uppic"] = $this->KDOut["TST_TAB"]["au"]["oppic"];
    }
    
    private function TreatCaptivate () {
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        
        $referer = $_SERVER['HTTP_REFERER'];
//        $referer = "http://127.0.0.1/dev.trenqr.co/f/6I3C1EoXjMb5b0f_7";
        
        $shw_captvt = FALSE;
        if ( $referer ) {
            
            $upieces = $TQR->explode_tqr_url($referer);

    //        var_dump($this->KDIn["datas"]["cu"],$upieces);
    //        var_dump($upieces['ups_raw']['aplki']);
    //        exit();

            if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
               /*
                * Dans ce cas, l'utilisateur vient d'une page AUTRE que celles de Trenqr
                */ 
                $shw_captvt = TRUE;
                
                $r__ = $TQR->sugg_GetChoosenProfils($this->KDOut["TST_TAB"]["au"]["oid"],NULL,1);
                $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
            } else {
               /*
                * Dans ce cas, l'utilisateur vient d'une page appartenant à TRENQR
                */
//                if ( strtoupper($upieces["urqid"]) === "ONTRENQR" ) {
//                    $shw_captvt = TRUE;
//                    
//                    $r__ = $TQR->sugg_GetChoosenProfils($this->KDOut["TST_TAB"]["au"]["oid"],NULL,1);
//                    $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
//                }
            }
            
//            var_dump(__LINE__,__FILE__,$referer,$this->KDIn["upieces"]);
            
        } else if ( !$referer ) {
           /*
            * Dans ce cas, on a aucune information. L'utilisateur a du atterir diectement sur la page
            */ 
            $shw_captvt = TRUE;
            
            $r__ = $TQR->sugg_GetChoosenProfils($this->KDOut["TST_TAB"]["au"]["oid"],NULL,1);
            $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
        }
        
        $this->KDOut["captivate"]["show"] = $shw_captvt;
//        var_dump(__LINE__,__FUNCTION__,$referer,$upieces,$this->KDOut["captivate"]);
//        exit();
    }
    
    
    /***************************************************************************************************************/
    
    public function prepare_datas_in() {
        //RAPPEL : Si on est arrivé ici c'est forcement que WOS c'est assuré que CU est connecté.
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
        
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            $RSTOI = new RSTO_INFOS();
            $RSTOI = $_SESSION["rsto_infos"];
            
            //Récupération des données sur le compte CU
            $curr_user = $RSTOI->getUpseudo();
            //On vérifie que l'utilisateur actif est Autorisé à continuer
            $curr_user = $this->cu_complies($curr_user);

            $this->KDIn["curr"] = $curr_user;
        } 
        

        //On récupère les données de Entrants
        $this->KDIn["DsIn"] = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required();
        /*
         * TODO : Traiter le cas des paramètres optionnelles.
         *      Il s'agira de travailler sur le mode d'affichage au niveau de la page. 
         *      Le lien peut être distribué de tel sorte que son destinataire l'apercoive d'une certaines manière.
         */

        //On vérifie que l'identifiant PERMALINK est présent et valide (structure)
        if (! ( key_exists("pmlkid", $this->KDIn["DsIn"]) && !empty($this->KDIn["DsIn"]["pmlkid"]) && is_string($this->KDIn["DsIn"]["pmlkid"]) ) ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        } 
        
        if (! ( key_exists("atypi", $this->KDIn["DsIn"]) && !empty($this->KDIn["DsIn"]["atypi"]) 
            && is_string($this->KDIn["DsIn"]["atypi"]) && in_array(strtolower($this->KDIn["DsIn"]["atypi"]), ["testy"] ) 
        ) ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        }
        
        
        //gvn : GiVeN
        $this->KDIn["gvn_pg"] = $this->KDIn["DsIn"]["atypi"];
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
        
    }
    
    
    public function on_process_in() {
        
        /* ARTICLES DATAS (+ARTICLE OWNER) */
        $this->AcquireArticleDatas();
        
        /* CU DATAS */
        if ( key_exists("curr", $this->KDIn) && $this->KDIn["curr"] && count($this->KDIn["curr"]) ) {
            $this->AcquireCUDatas();
            
            $ec_stt = $this->EC_Handle();
            if ( $ec_stt !== "_EC_STT_NO_EC" ) {
                $this->KDOut["econfirm"]["ec_is_ecofirm"] = TRUE;
                $this->KDOut["econfirm"]["ec_state"] = $ec_stt;
                $this->KDOut["econfirm"]["ec_scope"] = "TQR_TMLNR";
            }
        }
        
        /* PAGE VERSION */
        $this->PageVersion();
        
        /* PAGE HEAD*/
        $this->CustumHeaderDatas();
        
        /*
         * [DEPUIS 04-11-15]
         */
        $this->TreatCaptivate();
    }

    public function on_process_out() {
        //QUESTION : Esr ce que l'utilisateur actif est connecté ? (Sert aux modules en aval)
        $_SESSION["ud_carrier"]["iauth"] = $this->KDout["iauth"];
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        /* CURRENTUSER PAGE DATAS */
        foreach ( $this->KDout["CUser_TAB"] as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /*
         * ETAPE :
         *      On envoie toutes les données surtout pour TALKVIEW
         */
        $_SESSION["ud_carrier"]["tst_tab"] = base64_encode(serialize($this->KDOut["TST_TAB"]));
        
        /* ARTICLE (PAGE) DATAS */
        /*
         *      Les données concernent aussi bien celles de l'Article que son Propriétaire..
         */
        foreach ($this->KDOut["TST_TAB"] as $k => $v) {
            if ( $k === "ustgs" || $k === "hashs" ) {
                $_SESSION["ud_carrier"][$k] = base64_encode(serialize($v));
//                var_dump(__LINE__,base64_encode(serialize($v)));
            } else {
                $_SESSION["ud_carrier"][$k] = $v;
            }
        }
        
        /*
         * ETAPE :
         *      On envoie la donnée sur PPIC comme avec le même ID que pour les versions ARTICLE.
         */
        $_SESSION["ud_carrier"]["art_oppic"] = $this->KDOut["ART_O"]["oppic"];
        
        
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
        
        /*
         * [DEPUIS 13-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( $this->KDIn["curr"]["pdaccid"] ) {
            $PM = new POSTMAN();
            $log_r = $this->Wkr_LogUsertagActy($PM, $this->KDIn["curr"]["pdaccid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["srv_curl"], $this->KDIn["srv_curl"], FALSE, $this->KDOut["ORI_TST_TAB"]["tstid"], 1503, TRUE);
        }
        
        
        /*
         * [DEPUIS 03-11-15] @author BOR
         *      Gestion de CAPTIVATE
         */
        $_SESSION["ud_carrier"]["captivate_sgg_upsd"] = $this->KDOut["captivate"]["sgg_upsd"];
        $_SESSION["ud_carrier"]["captivate_show"] = $this->KDOut["captivate"]["show"];
        
        /* 
         * On inscrit certaines informations relatives à la page.
         * Ces informations sont données par WORKER car 'ver' dépend du fait qu'on soit sûr que la procédure c'est passé corectement.
         * Seuls les WORKER définissent les droits d'accès.
         * 
         * En ce qui concerne 'pgid', WORKER le fait car certains URQ sont de type AJAX qui n'admettent pas de page.
         * C'est donc au WORKER de définir ces informations.
         */
        $_SESSION["ud_carrier"]["pagid"] = "fksa";
        $_SESSION["ud_carrier"]["pgakxver"] = $this->KDout["pgver"];
        
    }

    
    protected function prepare_params_in_if_exist() {
        
    }

}
