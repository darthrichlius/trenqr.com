<?php

class WORKER_FKSA_GTPG extends WORKER {
    
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
    
    private function Vrfy_UrelCases ($aex_tab){
        /*
         * Permet de vérifier le cas des relations entre CU et AOW (ArticleOWner)
         * En fonction du résultat on optimise au niveau des données ou au niveau des fonctionnalités
         */
       
        $ART = new ARTICLE();
        //On vérifie s'il s'agit d'un Article ITR ou IML. On vérifie la présence des données sur TREND qui trahissent sa nature.
//        if (! $ART->onread_is_trend_version($aex_tab["artid"]) ) { //IML
        if ( !$ART->onread_is_trend_version($aex_tab["artid"]) && !$aex_tab["art_is_sod"] ) { //IML
            if (! ( key_exists("curr", $this->KDIn) && $this->KDIn["curr"] && count($this->KDIn["curr"]) ) ) {
                $this->signalError ("err_user_l5e403_art", __FUNCTION__, __LINE__);
            }
            
            /*
             * On vérifie si CU est amis avec le propriétaire de l'Article
             * [NOTE 31-05-15] @BOR
             * On prend aussi en compte le cas D_FOLW
             */
            $REL = new RELATION();
            if (! ( $REL->friend_theyre_friends($this->KDIn["curr"]["pdaccid"], $aex_tab["uid"]) 
                 || $REL->onread_relation_exists_fecase($this->KDIn["curr"]["pdaccid"], $aex_tab["uid"]) === "_REL_FEO" ) ) 
            {
                
                /*
                 * [NOTE 21-02-15] @Loukz
                 * On déclare la requete comme NOT_FOUND car dans un sens elle l'est étant donné que l'utilisateur ni a pas accès. 
                 * Cependant, 
                 */
                $this->signalError ("err_user_l5e403_art", __FUNCTION__, __LINE__);
            } 
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
        
        $ART = new ARTICLE_TR();
        //On vérifie que l'identifiant PERMALINK est présent et valide
        $art_etab = $ART->exists_with_prmlk($this->KDIn["DsIn"]["pmlkid"]);
        if (! $art_etab ) {
            $this->signalError ("err_user_l5e404_any", __FUNCTION__, __LINE__);
        } 
        
        /* CHECK UREL */
        $this->Vrfy_UrelCases($art_etab);
        
        //*** On récupère toutes les données relatives aux Articles. Les données seront triées par CALLER. ***//
        
        //Données de base sur l'Article
        /*
         * [NOTE 21-02-2015] @Loukz
         * On utilise ART_TR car on s'éviter une vérification de la nature de l'Article.
         * Avec ARTICLE_TR, on est sur d'atteindre ARTICLE et ARTICLE_TR
         */
        $args = [
            "art_eid" => $art_etab["art_eid"]
        ];
        
//        $r = $ART->on_read($args);
        if ( $ART->onread_is_trend_version($art_etab["artid"]) ) {
            $r = $ART->onread_archive_itr($args);
        } else {
            $r = $ART->onread_archive_iml($args);
        }
        
//        var_dump(__LINE__,$r);
//        exit();
        
        if ( !$r || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
            return $r;
        } else if ( $r === -1 ) {
            $r = $ART->getArt_loads();
        }
        
        
        /*
        * ETAPE : 
        * On traite les UserTags
        */
        
        /*
         * [DEPUIS 18-06-15] @BOR
         */
        $ustgs = NULL;
        $ustgs = $ART->onread_AcquiereUsertags_Article($r["art_eid"],TRUE);
        
        /*
        if ( key_exists("art_list_usertags", $r) && !empty($r["art_list_usertags"]) && is_array($r["art_list_usertags"]) ) {
            $ustgs = $r["art_list_usertags"];
            array_walk($ustgs, function(&$i,$k){
                $i = [
                    'eid'   => $i['ustg_eid'],
                    'ueid'  => $i['tgtueid'],
                    'ufn'   => $i['tgtufn'],
                    'upsd'  => $i['tgtupsd']
                ];
            });
        }
        //*/
        
        $PA = new PROD_ACC();
        
       /*
        * [NOTE 11-09-15] @auhtor BOR
        *  J'ai voulu mettre en place un code pour que les données soient à jour mais la performance en prend un gros coup.
        *  J'ai laissé tombé.
        *  Quand l'utilisateur va lire l'Article via ARP ou UNQ les données seront mises à jour.
        *  De plus, Reaper va mettre à jour les Articles ce qui va réduire les risques de voir les données être fausses pendant plus d'une journée.
        */
        $r["art_eval"] = $ART->onload_art_eval($r["art_eid"]);
        
        /*
         * [DEPUIS 28-07-15] @BOR
         * Le nombre total d'EVAL
         */
        $eval_nb = 0;
        if ( key_exists("art_eval",$r) && !empty($r["art_eval"]) && count($r["art_eval"]) === 4 ) {
            $eval_nb += ( is_int(intval($r["art_eval"][0])) && intval($r["art_eval"][0]) >= 0 ) ? intval($r["art_eval"][0]) : 0;
            $eval_nb += ( is_int(intval($r["art_eval"][1])) && intval($r["art_eval"][1]) >= 0 ) ? intval($r["art_eval"][1]) : 0;
            $eval_nb += ( is_int(intval($r["art_eval"][2])) && intval($r["art_eval"][2]) >= 0 ) ? intval($r["art_eval"][2]) : 0;
        }
        
        
        /*
         * [DEPUIS 20-06-16]
         */
        $EV = new EVALUATION();
        $E_E = $EV->exists(["actor" => $this->KDIn["curr"]["pdaccid"],"artid" => $r["artid"]]);
        $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
        
        
        $ivid = ( $r["art_vid_url"] ) ? TRUE : FALSE;
        
        /*
         * [NOTE 22-02-15] @Loukz
         * Sert sourtout à permettre au développeur de se souvenir des clés plutot que de les chercher dans les Entity.
         */
        $art_tab = [
            /* ARTICLES */
            "artid"             => $r["artid"],
            "art_eid"           => $r["art_eid"],
            "art_prmlk"         => $ART->onread_AcquierePrmlk($r["art_eid"],$ivid), //[DEPUIS 25-09-15] @author BOR
            "art_picid"         => $r["art_pdpic"],
            "art_pdpic_path"    => $r["art_pdpic_path"], 
            "art_locip"         => $r["art_locip"],
            "art_desc"          => html_entity_decode($r["art_desc"]),
            "art_creadate"      => $r["art_creadate"],
            "art_pdpic_string"  => $r["art_pdpic_string"],
            "art_list_hash"     => $r["art_list_hash"],
            "art_ustgs"         => $ustgs,
            "art_rnb"           => $r["art_rnb"],
            "art_vidu"          => $r["art_vid_url"],
//            "art_list_reacts" => $r["art_list_reacts"], //[22-02-15] @Loukz On en a pas besoin, les commentaires seront récupérés via AJAX
            "art_eval"          => $r["art_eval"],
            "art_eval_vl"       => $r["art_eval"][3],
            "art_eval_nb"       => $eval_nb,
            "myel"              => $me, //""(aucune), p2 (supaCool), p1 (cool), m1 (-1)
           /*
            * [DEPUIS 19-05-16]
            */
            "hasfv"             => ( $this->KDIn["curr"]["pdaccid"] && $ART->Favorite_hasFavorite($this->KDIn["curr"]["pdaccid"], $r["art_eid"]) ) ?  TRUE : FALSE,
           /*
            * [DEPUIS 02-07-16]
            */
            "art_isod"          => ( $r["art_is_sod"] === 1 ) ? TRUE : FALSE,
           /*
            * [DEPUIS 18-07-16]
            */
            "art_ihstd"         => ( $r["art_is_hstd"] === 1 ) ? TRUE : FALSE,
            /* OWNER */
            "art_oid"           => $r["art_oid"],
            "art_ogid"          => $r["art_ogid"],
            "art_oeid"          => $r["art_oeid"],
            "art_ofn"           => $r["art_ofn"],
            "art_opsd"          => $r["art_opsd"],
            "art_oppicid"       => $r["art_oppicid"],
            "art_oppic"         => $PA->onread_acquiere_pp_datas($r["art_oid"])["pic_rpath"],
            "art_ohref"         => $r["art_opsd"]
        ];
        
//        var_dump(__LINE__,$art_tab);
//        var_dump(__LINE__,$r);
//        exit();
//        $art_tab["istr"] = FALSE;
         
        if ( key_exists("trd_eid", $r) ) {
            
            $TRD = new TREND();
            
            /* QTREND DATAS */
            $art_tab["trartid"]             = $r["trartid"];
            $art_tab["trid"]                = $r["trid"];
            $art_tab["trd_eid"]             = $r["trd_eid"];
            $art_tab["trd_title"]           = $r["trd_title"];
            $art_tab["trd_desc"]            = $r["trd_desc"];
            $art_tab["trd_title_href"]      = $r["trd_title_href"];
            $art_tab["art_trd_catgid"]      = $r["art_trd_catgid"];
            $art_tab["art_trd_is_public"]   = $r["art_trd_is_public"];
            $art_tab["art_trd_grat"]        = $r["art_trd_grat"];
            $art_tab["art_trd_date_tstamp"] = $r["art_trd_date_tstamp"];
            /*
             * [DEPUIS 02-05-15] @BOR
             */
            $art_tab["trd_href"]            = $TRD->on_read_build_trdhref($r["trd_eid"],$r["trd_title_href"]);
//            $art_tab["trd_href"]            = $r["trd_href"];
            /* QTREND OWNER DATAS */
            $art_tab["art_trd_oid"]         = $r["art_trd_oid"];
            $art_tab["art_trd_oeid"]        = $r["art_trd_oeid"];
            $art_tab["art_trd_ogid"]        = $r["art_trd_ogid"];
            $art_tab["art_trd_ofn"]         = $r["art_trd_ofn"];
            $art_tab["art_trd_opsd"]        = $r["art_trd_opsd"];
            $art_tab["art_trd_otdel"]       = $r["art_trd_otdel"];
            
            //Dans tous les cas, on spécifie s'il s'agit d'un Article de type ITR pour des raisons de commodité
            $art_tab["istr"] = TRUE;
        }
        
        $this->KDOut["ART_TAB"] = $art_tab;
    } 
    
    private function PageVersion() {
        /*
         * Permet de déterminer la version de la page.
         * La méthode permet aussi d'obtenir la donnée qui détermine si l'utilisateur est connecté.
         */
        
        if (! ( key_exists("curr", $this->KDIn) && $this->KDIn["curr"] && count($this->KDIn["curr"]) ) ) {
            $this->KDout["pgver"] = "WLC";
            $this->KDout["iauth"] = FALSE;
        } else if ( $this->KDIn["curr"]["pdaccid"] === $this->KDOut["ART_TAB"]["art_oid"] ) {
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
        $this->KDout["head"]["oupsd"] = $this->KDOut["ART_TAB"]["art_opsd"];
        $this->KDout["head"]["oufn"] = $this->KDOut["ART_TAB"]["art_ofn"];
        
        if ( key_exists("art_list_hash", $this->KDOut["ART_TAB"]) && $this->KDOut["ART_TAB"]["art_list_hash"] && is_array($this->KDOut["ART_TAB"]["art_list_hash"]) && count($this->KDOut["ART_TAB"]["art_list_hash"]) ) {
            $this->KDout["head"]["head_keywords_list"] = implode(",",$this->KDOut["ART_TAB"]["art_list_hash"]);
            if ( key_exists("trid", $this->KDOut["ART_TAB"]) && $this->KDOut["ART_TAB"]["trid"] ) {
                //TODO : Ajouter la catégorie de la Tendance
            }
        }
        /*
         * [DEPUIS 25-09-15] @author BOR 
         */
        $this->KDout["head"]["aprmlk"] = $this->KDOut["ART_TAB"]["art_prmlk"];
        $this->KDout["head"]["apichrf"] = $this->KDOut["ART_TAB"]["art_pdpic_path"];
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
                
                $r__ = $TQR->sugg_GetChoosenProfils($this->KDOut["ART_TAB"]["art_oeid"],NULL,1);
                $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
            } else {
               /*
                * Dans ce cas, l'utilisateur vient d'une page appartenant à TRENQR
                */
//                if ( strtoupper($upieces["urqid"]) === "ONTRENQR" ) {
//                    $shw_captvt = TRUE;
//                    
//                    $r__ = $TQR->sugg_GetChoosenProfils($this->KDOut["ART_TAB"]["art_oeid"],NULL,1);
//                    $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
//                }
            }
            
//            var_dump(__LINE__,__FILE__,$referer,$this->KDIn["upieces"]);
            
        } else if ( !$referer ) {
           /*
            * Dans ce cas, on a aucune information. L'utilisateur a du atterir diectement sur la page
            */ 
            $shw_captvt = TRUE;
            
            $r__ = $TQR->sugg_GetChoosenProfils($this->KDOut["ART_TAB"]["art_oeid"],NULL,1);
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
            && is_string($this->KDIn["DsIn"]["atypi"]) && in_array(strtolower($this->KDIn["DsIn"]["atypi"]), ["photo"] ) 
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
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        /* CURRENTUSER PAGE DATAS */
        foreach ( $this->KDout["CUser_TAB"] as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
        }

        /* ARTICLE (PAGE) DATAS */
        /*
         * Les données concernent aussi bien celles de l'Article que son Propriétaire..
         */
        foreach ($this->KDOut["ART_TAB"] as $k => $v) {
            if ( $k === "art_ustgs" || $k === "art_list_hash" || $k === "art_eval" ) {
                $_SESSION["ud_carrier"][$k] = base64_encode(serialize($v));
//                var_dump(__LINE__,base64_encode(serialize($v)));
            } else {
                $_SESSION["ud_carrier"][$k] = $v;
            }
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
        
        /*
         * [DEPUIS 13-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( $this->KDIn["curr"]["pdaccid"] ) {
            $PM = new POSTMAN();
            $log_r = $this->Wkr_LogUsertagActy($PM, $this->KDIn["curr"]["pdaccid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["srv_curl"], $this->KDIn["srv_curl"], FALSE, $this->KDOut["ART_TAB"]["artid"], 1501, TRUE);
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
