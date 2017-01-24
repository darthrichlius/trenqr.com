<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_GTPG_RO extends WORKER  {
    
function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function urq_complies ($target) {
        //QUESTION : Est ce que l'utilisateur, au regard des règles de fonctionnement du produit, a le droit d'accéder à cette page?
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
         $TH = new TEXTHANDLER();
         
        //* On vérifie que le pseudo est conforme au format attendu *//
        
        //On vérifie s'il y a un '@'. Si oui on le retire
         
        $target = $TH->genuine_pseudo_in_url($target);
        
        if (! $target ) {
            //On signale que l'on ne trouve pas l'utilisateur. En effet, le pseudo d'un utilisateur ne peut pas avoir de
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
//            $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
//            exit();
        }
        
        //On vérifie que le pseudos respecte la regex
        if (! $TH->valid_user_in_url($target) ) {
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
//            $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
//            exit();
        }
        
        /*
         * [NOTE]
         *      Ce n'est pas necessaire mais on le met quand même par mesure de précaution.
         *      De plus, cela ne DEVRAIT pas dénaturé le pseudo donné en paramètre.
         * [NOTE 12-07-16]      
         *      Je retire cette option car un pseudo tel que "noël" sera DÉNATURÉ !
         *      Les commentaires précédents indiquent que la ligne n'est pas très importante ni pour le fonctionnel, ni pour la sécurité.
         *      Cela ne devrait à priori ne poser aucun problème si nous la retirant.
         */
//        $target = htmlentities($target);
        
        /*
         * On vérifie qu'on a bien l'user et que son identifiant ueid est défini. 
         * On ajoute une option pour dire qu'on considère que si le compte de l'utilisateur est en processus de suppression => il n'existe pas.
         */
        $A = new PROD_ACC();
        $exists = $A->exists_with_psd($target,TRUE);
        
        if (! $exists ) {
            /*
             * TODO :
             *      (1) On se déconnecte
             *      (2) On détruit la SESSION
             *      (3) On redirige vers la page d'acceuil
             */
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
//            $this->signalErrorWithoutErrIdButGivenMsg("USER_NOT_FOUND", __FUNCTION__, __LINE__);
//            exit();
        }
        else {
            return $exists;
        }
    }
    
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
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $uid = $this->KDIn["curr"]["pdaccid"];
        $ueid = $this->KDIn["curr"]["pdacc_eid"];
        /*
         * ETAPE :
         *      On vérifie si le compte a été validé au moins une fois 
         */
        /*
         * [DEPUIS 12-08-16]
         *      Optimisation pour des soucis de PERF
         */
        $iconce = $TA->EC_AccIsCnfrmdOnce_wid($uid,["AQAP"]);
        if ( $iconce === TRUE && !( $this->KDIn["ups_optional"] && key_exists("ec_case",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["ec_case"] === "econfirm_final" ) ) {
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            return "_EC_STT_NO_EC";
        } else if ( $iconce !== TRUE ) {
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
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
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
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
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
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
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
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
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
    
    /* ---- CURRENT USER IDENTITY ---- */
    
    private function AcquireCUDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        $curr = $this->KDIn["curr"];
        
        $accid = $curr["pdaccid"];
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);

        $A = new PROD_ACC();
        $utab = $A->exists_with_id($accid);
//        $A->on_read_entity(["accid" => $accid]); //[DEPUIS 13-07-15] @BOR

        //TODO : A quelle moment on sécurise les données ? (Entity ou ICI)

        $CU = [];
        $CU["cueid"]    = $utab["pdacc_eid"];
        $CU["cuppic"]   = $A->onread_acquiere_pp_datas($accid)["pic_rpath"];
        $CU["cufn"]     = $utab["pdacc_ufn"];
        $CU["cupsd"]    = $utab["pdacc_upsd"];
       //[DEPUIS 26-06-15] @BOR L'utilisateur de '@' engendre trop d'opérations et nuit à la performance.
        $CU["cuhref"]   = "/".$utab["pdacc_upsd"];
        $CU["cucityid"] = $utab["pdacc_ucityid"];
        $CU["cucity"]   = $utab["pdacc_ucity_fn"];
        $CU["cucn_fn2"] = strtoupper($utab["pdacc_ucnid"]);
        
        /* //[DEPUIS 13-07-15] @BOR
        $CU = [];
        $CU["cueid"] = $A->getPdacc_eid();
        $CU["cuppic"] = $A->getPdacc_uppic();
        $CU["cufn"] = $A->getPdacc_ufn();
        $CU["cupsd"] = $A->getPdacc_upsd();
       //[DEPUIS 26-06-15] @BOR L'utilisateur de '@' engendre trop d'opérations et nuit à la performance.
        $CU["cuhref"] = "/".$A->getPdacc_upsd();
//        $CU["cuhref"] = "/@".$A->getPdacc_upsd();
        $CU["cucityid"] = $A->getPdacc_ucityid();
        $CU["cucity"] = $A->getPdacc_ucity_fn();
        $CU["cucn_fn2"] = strtoupper($A->getPdacc_ucnid());
        //*/
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);

        /***************** TRENQR USERPREF ****************/
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $ps__ = $TQR->getPreferences($accid);
        if ( $ps__ && count($ps__) ) {
            foreach ($ps__ as $p__) {
                $this->KDout["cuprefdcs"][$p__["prfop_excd"]] = $p__;
            }
        }
        
//        var_dump(__LINE__,$this->KDout["cuprefdcs"]);
//        exit();
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["CUser"] = $CU;
    }
    
    private function GetCUDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Vérifier si les données sont effectivement présentes. Sinon, on termine avec une erreur technique.
        return $this->KDout["CUser"];
    }
    
    /* ---- OWNER IDENTITY ---- */
    private function AcquireOwnerDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        /*
         * La récupération des données se fait grace au pseudo passé en paramètre acquis depuis $_GET["user"]
         */
        
        $target = $this->KDIn["target"];
        $accid = $target["pdaccid"];
        $acc_eid = $target["pdacc_eid"];
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $A = new PROD_ACC();
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $loads = $A->on_read_entity([
//            "accid" => $accid
            "acc_eid" => $acc_eid
        ]);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
//        var_dump(__LINE__,__FUNCTION__,__LINE__,$loads);
//        exit();
        
        //TODO : A quelle moment on sécurise les données ? (Entity ou ICI)
        
        $OW = [];
        $OW["oueid"]        = $A->getPdacc_eid();
        $OW["ouppic"]       = $A->getPdacc_uppic();
        $OW["ouppisdf"]     = ( $A->getPdacc_uppisdf() ) ? 1 : 0;
        $OW["oufn"]         = $A->getPdacc_ufn();
        $OW["oupsd"]        = $A->getPdacc_upsd();
       /*
        * [DEPUIS 26-06-15] @BOR
        *       L'utilisateur de '@' engendre trop d'opérations et nuit à la performance.
        */
        $OW["ouhref"]       = "/".$A->getPdacc_upsd();
//        $OW["ouhref"] = "/@".$A->getPdacc_upsd();
        $OW["outesty"]      = html_entity_decode($A->getPdacc_profilbio());
        $OW["ouwbst"]       = html_entity_decode($A->getPdacc_website());
//        $OW["outesty"] = $A->getPdacc_profilbio();
        $OW["oudl"]         = $A->getPdacc_udl();
        $OW["oucity_id"]    = $A->getPdacc_ucityid();
        $OW["oucity"]       = $A->getPdacc_ucity_fn();
        $OW["oucn"]         = strtolower($A->getPdacc_ucnid());
        $OW["oucn_fn"]      = strtoupper($A->getPdacc_ucnid());
        /*
         * [DEPUIS 15-09-15]
         */
        $ctw_dsma           = $A->getPdacc_ctw_dsma();
        $OW["ctw_dsma"]     = intval($ctw_dsma);
//        $OW["ctw_dsma"] = ( $A->getPdacc_ctw_dsma() === "1" ) ? 1 : 0; //[DEPUIS 15-09-15]
//        $OW["ctw_dsma"] = 0;
//        var_dump($OW["ctw_dsma"]);
//        var_dump(__LINE__,$OW);
//        exit();
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        /************** COVER ****************/
        
        $c_datas = $A->getPdacc_coverdatas();
        
        if ( isset($c_datas) && is_array($c_datas) && count($c_datas) ) {
            $OW["oucover_width"]    = $c_datas["acov_width"]."px";
            $OW["oucover_height"]   = $c_datas["acov_height"]."px";
            $OW["oucover_top"]      = $c_datas["acov_top"]."px";
            $OW["oucover_rpath"]    = $c_datas["acov_rpath"];
        } else {
            $OW["oucover_rpath"]    = NULL;
        }
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        
        /***************** STATS ****************/
        /*
        $folgs = $A->onread_acquiere_my_following($accid);
        $folws = $A->onread_acquiere_my_followers($accid);
        
        $folg_nb = ( isset($folgs) ) ? count($folgs) : 0;
        $folw_nb = ( isset($folws) ) ? count($folws) : 0;
        //*/
        
        $folw_nb = $A->onread_get_myfolrs_count($accid,["AQAP"]); 
        $folg_nb = $A->onread_get_myfolgs_count($accid,["AQAP"]);
                
        $OW["oucapital"]    = $A->getPdacc_capital();
        $OW["oufolsnb"]     = $folw_nb;
        $OW["oufolgsnb"]    = $folg_nb;
        $OW["oupostnb"]     = intval($A->getPdacc_stats_posts_nb());
        $OW["outrnb"]       = $A->getPdacc_stats_mytrends_nb();
        $OW["ouabtrnb"]     = $A->getPdacc_stats_fol_trends_nb();
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$OW,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["OWner"] = $OW;
    }
    
    private function GetOWnerDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Vérifier si les données sont effectivement présentes. Sinon, on termine avec une erreur technique.
        return $this->KDout["OWner"];
    }
    
    
    /* ---- ARTICLES SCOPE ---- */
    private function AcquireArticlesDatas () {
        //Permet de récupérer les données sur les Articles de type IML depuis les différents Entity et la base de données
        
//        var_dump($this->KDIn["target"]);
//        exit();
        
        //On charge les données de l'utilisateur
        $A = new PROD_ACC();
        $exists = $A->exists_with_psd($this->KDIn["target"]["pdacc_upsd"]);
        
        if (! $exists ) {
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
//            $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
//            exit();
        }
        
        $accid = $exists["pdaccid"];
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        $A->on_read_entity(["accid" => $accid]); 
        
        //On récupère toutes les First Articles
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        $art_stack = $A->onread_load_my_first_articles($accid);
        /*
         * [DEPUIS 28-04-15] @BOR
         *      Pour harmoniser le processus d'encodage avec TRPG et profiter du fait que c'est plus performant
         */
//        $art_stack = $A->onread_load_my_first_articles($accid, NULL, ["VM_ART"]);
        /*
         * [DEPUIS 12-08-16]
         *      Optimisation pour des soucis de PERF
         */
        $art_stack = $A->onread_load_my_first_articles($accid, 1, ["VM_ART","AQAP"]);
//        var_dump(__LINE__,__FUNCTION__,$art_stack);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        exit();
         
        $ART = new ARTICLE();
        
        /****************************************************/
        /* On crée le modèle pour les Articles de type IML */
        $iml_articles = [];
        
        if ( $art_stack && key_exists("iml", $art_stack) && isset($art_stack["iml"]) && count($art_stack["iml"]) ) {
            foreach ( $art_stack["iml"] as $k => $article ) {
                
//                $rnb = ( isset($article["art_list_reacts"]) && is_array($article["art_list_reacts"]) && count($article["art_list_reacts"]) ) ? count($article["art_list_reacts"]) : 0;
                
//                var_dump($article["art_desc"],$text);
//                exit();
                
                //On détemine l'évaluation du Compte courant
                $EV = new EVALUATION();
                $E_E = $EV->exists(["actor" => $this->KDIn["curr"]["pdaccid"],"artid" => $article["artid"]]);
                $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) 
                    ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) 
                    : "";
                
                //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile
                
                /*
                 * ETAPE : 
                 * On traite les UserTags
                 */
                $ustgs = $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE);
                
                /*
                 * ETAPE : 
                 * On traite les UserTags
                 */
                /*
                $ustgs = NULL;
                if ( key_exists("art_list_usertags", $article) && !empty($article["art_list_usertags"]) && is_array($article["art_list_usertags"]) ) {
                    $ustgs = $article["art_list_usertags"];
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
                
                $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
                
                $iml_articles[] = [
                    //Dans la page de developpement, ca n'arrive pas à 20. On peut donc utiliser >20
        //            "id" => 1022, //Tester la fonctionnalité de blocage de Articles en doublon
                    "id"        => $article["art_eid"],
                    "time"      => $article["art_creadate"],
//                    "img"       => NULL,
                    "img"       => $article["art_pdpic_path"],
                    //Interessant pour SEO
                    "msg"       => html_entity_decode($article["art_desc"]), 
//                    "msg"       => $article["art_desc"],
//                    "msg" => addcslashes($article["art_desc"], "\0..\37!@\177..\377 \\"),
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid),
//                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]), //[DEPUIS 28-04-15]
                    //L'appreciation affichée correspond à la différence entre toutes les appréciations
                    "eval"      => $article["art_eval"], /* [-1,+2,+1,total]*/
                    //L'évaluation que j'ai donné pour cet article
                    "myel"      => $me, //""(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    //Le nombre de commentaires 
                    "rnb"       => $article["art_rnb"],
                    "hashs"     => $article["art_list_hash"],
                    "ustgs"     => $ustgs,
                    /*
                     * [DEPUIS 18-12-15]
                     */
//                    "hasfv"     => ( $ART->Favorite_hasFavorite($this->KDIn["curr"]["pdaccid"], $article["art_eid"]) ) ?  TRUE : FALSE,
                    /* 
                     * [DEPUIS 12-08-16]
                     *      Optiomisation pour des soucis de PERF
                     */
                    "hasfv"     => ( $ART->Favorite_hasFavorite_waid($this->KDIn["curr"]["pdaccid"], $article["artid"], FALSE, ["AQAP"]) ) ?  TRUE : FALSE,
                    /*
                     * [DEPUIS 29-03-16]
                     */
                    "vidu"      => $article["art_vid_url"],
                    "isod"      => ( $article["art_is_sod"] === 1 ) ? TRUE : FALSE,
                    /* DONNEES PROPRIO */
                    "ufn"       => $article["art_ofn"],
                    "upsd"      => $article["art_opsd"],
                    "uppic"     => $A->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                    "uppic"     => $article["art_oppic"],
                    /*
                     * [DEPUIS 26-06-15] @BOR
                     * L'utilisateur de '@' engendre trop d'opérations et nuit à la performance.
                     */
                    "uhref"     => "/".$article["art_opsd"],
//                    "uhref"     => "/@".$article["art_opsd"],
                    /*
                     * Permet d'indiquer aux modules en EVAL s'il s'agit d'un Article distribué sous licence WELC.
                     * Cette information est utile pour adapter certaines fonctionnalités.
                     */
                    "isrtd"     => TRUE,
                    /*
                     * L'utilisateur a t-il le droit d'accéder aux foncitonnalités ACTION de l'Article
                     */
                    "af_ena"    => TRUE
                ];
                
            }
            
        }
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        //TOREMOVE
//                $wk = htmlentities(serialize($iml_articles));
//                $sk = unserialize(html_entity_decode("$wk"));
//                var_dump($iml_articles[0]["msg"],$wk,$sk);
//                exit();
        
        /* On stocke le tableau dans KDatas */
        $this->KDout["iml_articles"] = $iml_articles;
        
        /*
         * [DEPUIS 19-09-15] @author BOR
         * [NOTE 19-09-15] @author BOR
         *      A été créé dans le but de permet à XYZ de gérer le cas NoOne dans une section spécifique.
         *      Mais d'autres applications pourront en découler.
         */
        $this->KDout["iml_articles_count"] = count($iml_articles);
        
//        var_dump($art_stack["itr"]);
//        var_dump($this->KDout["iml_articles"]);
//        exit();
        
        /****************************************************/
        /* On créer le modèle pour les Articles de type itr */
        $itr_articles = [];
        
        if ( $art_stack && key_exists("itr", $art_stack) && isset($art_stack["itr"]) && count($art_stack["itr"]) ) {
            foreach ( $art_stack["itr"] as $k => $article ) {
                
//                $rnb = ( isset($article["art_list_reacts"]) && is_array($article["art_list_reacts"]) && count($article["art_list_reacts"]) ) ? count($article["art_list_reacts"]) : 0;
                
                //On détemine l'évaluation du Compte courant
                $EV = new EVALUATION();
                $me = $EV->getUserMyEval($this->KDIn["curr"]["pdaccid"],$article["artid"]);
                
                //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile (??)
                
                /*
                 * ETAPE : 
                 * On traite les UserTags
                 */
                /*
                $ustgs = NULL;
                if ( key_exists("art_list_usertags", $article) && !empty($article["art_list_usertags"]) && is_array($article["art_list_usertags"]) ) {
                    $ustgs = $article["art_list_usertags"];
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
                /*
                 * ETAPE : 
                 * On traite les UserTags
                 */
                $ustgs = $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE);
                
                $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
                
                $TRD = new TREND();
                
                $itr_articles[] = [
                    //Dans la page de developpement, ca n'arrive pas à 20. On peut donc utiliser >20
        //            "id" => 1022, //Tester la fonctionnalité de blocage de Articles en doublon
                    "id"        => $article["art_eid"],
                    "time"      => $article["art_creadate"],
//                    "img"       => NULL,
                    "img"       => $article["art_pdpic_path"],
                    //Interessant pour SEO
//                    "msg"       => htmlentities($article["art_desc"]),
//                    "msg"       => html_entity_decode($article["art_desc"]),
                    "msg"       => $article["art_desc"], //[DEPUIS 29-04-15] @BOR
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid),
//                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]),
                    //L'appreciation affichée correspond à la différence entre toutes les appréciations
                    "eval"      => $article["art_eval"], /* [+2,+1,-1,total]*/
                    //L'évaluation que j'ai donné pour cet article
                    "myel"      => $me, //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    //Le nombre de commentaires 
                    "rnb"       => $article["art_rnb"],
                    "hashs"     => $article["art_list_hash"],
                    "ustgs"     => $ustgs,
                    /*
                     * [DEPUIS 18-12-15]
                     */
//                    "hasfv"     => ( $ART->Favorite_hasFavorite($this->KDIn["curr"]["pdaccid"], $article["art_eid"]) ) ?  TRUE : FALSE,
                    /* 
                     * [DEPUIS 12-08-16]
                     *      Optiomisation pour des soucis de PERF
                     */
                    "hasfv"     => ( $ART->Favorite_hasFavorite_waid($this->KDIn["curr"]["pdaccid"], $article["artid"], FALSE, ["AQAP"]) ) ?  TRUE : FALSE,
                    /*
                     * [DEPUIS 29-03-16]
                     */
                    "vidu"      => $article["art_vid_url"],
                    /******** OWNER DATAS ********/
                    "ueid"      => $article["art_oeid"],
                    "uppic"     => $A->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                    "uppic"     => $article["art_oppic"],
                    "upsd"      => $article["art_opsd"],
                    /*
                     * [DEPUIS 26-06-15] @BOR
                     * L'utilisateur de '@' engendre trop d'opérations et nuit à la performance.
                     */
                    "uhref"     => "/".$article["art_opsd"],
//                    "uhref"     => "/@".$article["art_opsd"],
                    
                    /******** TREND DATAS ********/
                    //L'id externe de la TENDANCE.
                    "trd_eid"   => $article["trd_eid"],
                    "trtitle"   => $article["trd_title"],
                    "trhref"    => $TRD->on_read_build_trdhref($article["trd_eid"],$article["trd_title_href"]),
//                    "trhref"    => $article["trd_href"], [28-04-15]
                    "istrd"     => TRUE,
                    /*
                     * Permet d'indiquer aux modules en EVAL s'il s'agit d'un Article distribué sous licence WELC.
                     * Cette information est utile pour adapter certaines fonctionnalités.
                     */
                    "isrtd"     => TRUE
                ];
            }
            
        }
        
        
        /* On stocke le tableau dans KDatas */
        $this->KDout["itr_articles"] = $itr_articles;
//        var_dump(__LINE__,__FUNCTION__,$art_stack["itr"]);
//        var_dump(__LINE__,__FUNCTION__,$itr_articles);
//        exit();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        
        /*
         * [DEPUIS 19-09-15] @author BOR
         * [NOTE 19-09-15] @author BOR
         *      A été créé dans le but de permet à XYZ de gérer le cas NoOne dans une section spécifique.
         *      Mais d'autres applications pourront en découler.
         */
        $this->KDout["itr_articles_count"] = count($itr_articles);
    }
    
      
    private function GetArticlesIMLDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
        //TODO : Vérifier que le tableau est au moins défini. Sinon, un bug se produira au niveau de VPRSER
//        return htmlentities(serialize($this->KDout["iml_articles"]));
        // Damn pesky carriage returns...
    
//        return htmlentities(serialize($this->KDout["iml_articles"]));
        
        $ts = base64_encode(serialize($this->KDout["iml_articles"]));
        return $ts;
        var_dump($ts);
//        var_dump(preg_match("#\\\\n#", $ts));
//        
        $nts = preg_replace("#\\\\n#", "a", $ts);
        $nts = preg_replace("#\\\\r#", "a", $nts);
//        
//        var_dump(preg_match("#\\\\n#", $nts));
//        exit();
//        
//        $nts = preg_replace("#\\\\n\\\\r#", "a", $nts);
//        return $nts;
        
        var_dump(preg_match("#\\\\\\n|\\\\\\r#", $nts));
        var_dump(preg_match("#\\\\\n|\\\\\r#", $nts));
        var_dump(preg_match("#\\\\n|\\\\r|\\\\r\\\\n#", $nts));
        var_dump(preg_match("#\\\n|\\\r#", $nts));
        var_dump(preg_match("#\\n|\\r#", $nts));
        var_dump(preg_match("#\n|\r#", $nts));
        
        var_dump($nts);
        /*
        $z = html_entity_decode($nts);
        
        var_dump(preg_match("#\\\\\\n|\\\\\\r#", $z));
        var_dump(preg_match("#\\\\\n|\\\\\r#", $z));
        var_dump(preg_match("#\\\\n|\\\\r#", $z));
        var_dump(preg_match("#\\\n|\\\r#", $z));
        var_dump(preg_match("#\\n|\\r#", $z));
        var_dump(preg_match("#\n|\r#", $z));
        //*/
        exit();
        return; 
    }
    
    private function GetArticlesITRDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
//        return htmlentities(serialize($this->KDout["itr_articles"]));
         $ts = base64_encode(serialize($this->KDout["itr_articles"]));
         return $ts;
    }
    
    private function GetPreferencesDatas () {
        //Renvoie un tableau contenant les données sur les preferences 

         $ts = base64_encode(serialize($this->KDout["cuprefdcs"]));
         return $ts;
    }
    
    /* ---- RELATION ---- */
    private function AcquireUREL () {
        $arctor_id = $this->KDIn["target"]["pdaccid"];
        $target_id = $this->KDIn["curr"]["pdaccid"];
        
        //Détermine s'il y a une relation entre CU et OW
        $REL = new RELATION();
        $r = $REL->onread_get_urel_if_exists($arctor_id, $target_id);
        
        /**
         * 
         * [31-08-14] @author L.C <lou.carther@deuslynn-entreprise.com>
         * A ce stade, la possibilité qu'on tombe sur un NULL, ou ERROR_VOLATILE est extremet faible. 
         * Ceci grace aux vérifications effectuées en amont.
         * Aussi, je choisis à cette date de ne pas faire de plus amples vérifications et de faire confiance à mon code.
         */
        
        //On retire '_REL_' pour la conversion
        $tc = str_replace("_REL_", "", $r);
        
        $id = $this->convert_str_to_ascii($tc);
        $n = base_convert(intval($id), 10, 23);
        
        $this->KDout["urel"] = $n; 
    }
    
    private function GetUREL () {
        return $this->KDout["urel"];
    }
    
    /* ---- TRENDS LIST ---- */
    
    /*
     * Récupère la liste des Tendances de l'utilisateur actif.
     */
    private function AcquireTrendsDatas() {
        $A = new PROD_ACC();
        
        $r = $mt = $A->onread_acquiere_my_trends_datas($this->KDIn["target"]["pdaccid"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            /*
             * TODO : Signaler l'erreur
             * [DEPUIS 31-07-15] @BOR
             *  On signale le résultat comme vide.
             */
            $mt = NULL;
        } else if ( $mt ) {
            usort($mt, function($a,$b){
                return floatval($a["trd_creadate_tstamp"]) < floatval($b["trd_creadate_tstamp"]);
            });
            $mt = array_slice($mt, 0, 1);
        }
        
        $ft = $A->onread_acquiere_following_trends_datas($this->KDIn["target"]["pdaccid"]);
        if ( $ft ) {
            usort($ft, function($a,$b){
                return floatval($a["trd_creadate_tstamp"]) < floatval($b["trd_creadate_tstamp"]);
            });
            $ft = array_slice($ft, 0, 1);
            $r = ( empty($mt) ) ? $ft : array_merge($mt,$ft);
        } 
        
//        var_dump(__FUNCTION__,__LINE__,array_column($mt,"trd_creadate_tstamp"),array_column($ft,"trd_creadate_tstamp"),array_column($r,"trd_creadate_tstamp"));
//        var_dump(__FUNCTION__,__LINE__,count($mt),count($ft),count($r));
//        var_dump(__FUNCTION__,__LINE__,$mt,$ft,$r);
//        exit();
        /* 
         * ETAPE : 
         * On ne récupère que les données dont on a réellement besoin
         * */
        $my_trends = [];
        $cn = 0;
        if ( $r && is_array($r) && count($r) ) {
            usort($r, function($a,$b){
//                var_dump(floatval($b["trd_creadate_tstamp"]), floatval($a["trd_creadate_tstamp"]),floatval($b["trd_creadate_tstamp"]) - floatval($a["trd_creadate_tstamp"]));
                return floatval($a["trd_creadate_tstamp"]) < floatval($b["trd_creadate_tstamp"]);
            });
//            var_dump(__FUNCTION__,__LINE__,$r);
//            exit();
            
            foreach ( $r as $k => $trd ) {
                /*
                //A utiliser pour tester la fonctionnalité de chargement des Tendances plus anciennes
                ++$cn;
                if ( $cn === 2) {
                    break;
                }
                //*/
                if ( isset($trd["trd_next_del_tstamp"]) ) {
                    CONTINUE;
//                    return TRUE;
                }
                
                
                $TRD = new TREND();
                //On récupère l'identifiant interne de OWNER pour
                $tgt_uid = $this->KDIn["target"]["pdaccid"];
                
                $my_trends[] = [
                    "trd_eid"       => $trd["trd_eid"],
                    "trd_tle"       => $trd["trd_title"],
                    "trd_desc"      => html_entity_decode($trd["trd_desc"]),
                    "trd_href"      => $trd["trd_href"],
                    "trd_posts_nb"  => ( key_exists("trd_stats_posts",$trd) && isset($trd["trd_stats_posts"]) ) ? $trd["trd_stats_posts"] : 0,
                    "trd_abos_nb"   => ( key_exists("trd_stats_subs",$trd) && isset($trd["trd_stats_subs"]) ) ? $trd["trd_stats_subs"] : 0,
                    "trd_time"      => $trd["trd_creadate_tstamp"],
                    "tba"           => ( strtolower($trd["trd_oeid"]) === strtolower($this->KDIn["target"]["pdacc_eid"]) ) ? "mtrs" : "sbtrs",
                    /* COVER DATAS */
                    "trd_cov_w"     => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["trcov_width"]."px" : NULL,
                    "trd_cov_h"     => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["trcov_height"]."px" : NULL,
                    "trd_cov_t"     => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["trcov_top"]."px" : NULL,
                    "trd_cov_rp"    => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["pdpic_realpath"] : NULL,
                    /*
                     //DEV, TEST, DEBUG
                     "trd_cov_w"     => ( $trd["trd_cover"] ) ? NULL : NULL,
                    "trd_cov_h"     => ( $trd["trd_cover"] ) ? NULL : NULL,
                    "trd_cov_t"     => ( $trd["trd_cover"] ) ? NULL : NULL,
                    "trd_cov_rp"    => ( $trd["trd_cover"] ) ? NULL : NULL,
                     */
                    /* OWNER DATAS */
                    "trd_oid"       => $trd["trd_oeid"],
                    "trd_ofn"       => $trd["trd_ofn"],
                    "trd_opsd"      => $trd["trd_opsd"],
                    "trd_ohref"     => $trd["trd_ohref"],
                    "trd_oppic"     => $trd["trd_oppic"],
                    "trd_octrib"    => $TRD->onread_usercontrib($tgt_uid,$trd["trid"]),
                    /* FIRST ARTICLES IDS */
//                    "trd_fartis"    => NULL
                    "trd_fartis"    => implode(',', array_column($trd["trd_first_articles"],"art_eid"))
                ];
//                var_dump($trd["trd_first_articles"]);
            }
//            exit();
            $this->KDOut["pg_trends"] = $my_trends; 
        } else {
            $this->KDOut["pg_trends"] = NULL; 
        }
        
//        $this->KDOut["pg_trends"] = NULL; //DEV, TEST : Tester NONE
        
//        var_dump(__LINE__,$this->KDOut["pg_trends"]);
//        exit();
    }
    
    private function GetTrendsDatas () {
        //Renvoie un tableau contenant les données sur les Tendances sous un format les permettant d'être traités au niveau de SKLT
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
//        return htmlentities(serialize($this->KDout["itr_articles"]));
//        var_dump(__LINE__,$this->KDOut["pg_trends"]);
//        exit();
         $ts = base64_encode(serialize($this->KDOut["pg_trends"]));
         return $ts;
    }
    
    private function AcquireFavArtsDatas () {
        $ART = new ARTICLE_TR();
        $PA = new PROD_ACC();
        $TRD = new TREND();
        
        $arts = $ART->Favorite_GetFavArts($this->KDIn["target"]["pdaccid"], $this->KDIn["curr"]["pdaccid"], "FST", NULL, NULL, NULL);
//        $arts = $ART->Favorite_GetFavArts($this->KDIn["target"]["pdaccid"], $this->KDIn["curr"]["pdaccid"], "FST", NULL, NULL, 2); //DEV, TEST, DEBUG
        
//        var_dump($arts);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $arts) ) {
            $this->KDOut["pg_favs"] = NULL; 
        } else if ( $arts && is_array($arts) ) {
            
            $atab = $FADs = [];
            foreach ($arts as $article) {
                //On détemine l'évaluation du Compte courant
                $EV = new EVALUATION();
                $E_E = $EV->exists(["actor" => $this->KDIn["target"]["pdaccid"],"artid" => $article["artid"]]);
                $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") )? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
            
                //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile (??)

                /*
                 * ETAPE : 
                 * On traite les UserTags
                 */
                $ustgs = $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE);
                /*
                 * ETAPE :
                 *      On récupère le type de FAV
                 */
                $fvtab = $ART->Favorite_hasFavorite($this->KDIn["target"]["pdaccid"], $article["art_eid"],TRUE);
                $fvtp = $ART->Favorite_ConvertTypeID($fvtab["arfv_fvtid"]);
                
                $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
                
                $atab = [
                    "id"        => $article["art_eid"],
                    "time"      => $article["art_creadate"],
                    "img"       => $article["art_pdpic_path"],
                    "msg"       => $article["art_desc"], //[DEPUIS 29-04-15] @BOR
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid),
                    "eval"      => $article["art_eval"], /* [+2,+1,-1,total]*/
                    "myel"      => $me, //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    "rnb"       => $article["art_rnb"],
                    "hashs"     => $article["art_list_hash"],
                    "ustgs"     => $ustgs,
                    "fvtp"      => $fvtp,
                    "fvtm"      => $fvtab["arfv_startdate_tstamp"],
                    //QUESTION : Est-ce que l'utilisateur connecté à FAV cet ARTICLE
                    "hasfv"     => TRUE,
                    /*
                     * [DEPUIS 21-04-16]
                     */
                    "vidu"      => $article["art_vid_url"],
                    "isod"      => ( $article["art_is_sod"] === 1 ) ? TRUE : FALSE,
                    /*
                     * [DEPUIS 29-04-16]
                     *      Indique si l'ARTICLE doit être distribué en mode RESTRICTED
                     */
                    "isrtd"     => TRUE,
                    /******** OWNER DATAS ********/
                    "ueid"      => $article["art_oeid"],
                    "uppic"     => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
    //                    "uppic"     => $article["art_oppic"],
                    "upsd"      => $article["art_opsd"],
                    "uhref"     => "/".$article["art_opsd"],
                ];

                if ( $article["istrd"] ) {
                    $atab["trd_eid"] = $article["trd_eid"];
                    $atab["trtitle"] = $article["trd_title"];
                    $atab["trhref"] = $TRD->on_read_build_trdhref($article["trd_eid"],$article["trd_title_href"]);
                    $atab["istrd"] = TRUE;
                }
            
                $FADs[] = $atab;
            }
            
            $this->KDOut["pg_favs"] = $FADs; 
        } else {
            $this->KDOut["pg_favs"] = NULL; 
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$this->KDOut["pg_favs"]);
//        exit();
    }
    
    private function GetFavsDatas () {
        //Renvoie un tableau contenant les données sur les Tendances sous un format les permettant d'être traités au niveau de SKLT
         $ts = base64_encode(serialize($this->KDOut["pg_favs"]));
         return $ts;
    }
    
    
   /*
    * [DEPUIS 29-09-15] @author BOR 
    */
    private function CustumHeaderDatas() {
        $TXT = new TEXTHANDLER();
        $desc_alt = $TXT->get_deco_text("fr","_head_description_alt");
        $this->KDout["head"]["head_outesty"] = ( $this->KDout["OWner"]["outesty"] ) ? $this->KDout["OWner"]["outesty"] : $TXT->ReplaceDmd("pseudo",$this->KDout["OWner"]["oupsd"],$desc_alt,FALSE);
        
        /*
         * [TODO 26-09-15] @author BOR 
         *      Récupérer les hashtags dans la description et le titre de la Tendance pour la mettre dans le Header
         */
        /* //EXEMPLE
        if ( key_exists("art_list_hash", $this->KDOut["ART_TAB"]) && $this->KDOut["ART_TAB"]["art_list_hash"] && is_array($this->KDOut["ART_TAB"]["art_list_hash"]) && count($this->KDOut["ART_TAB"]["art_list_hash"]) ) {
            $this->KDout["head"]["head_keywords_list"] = implode(",",$this->KDOut["ART_TAB"]["art_list_hash"]);
            if ( key_exists("trid", $this->KDOut["ART_TAB"]) && $this->KDOut["ART_TAB"]["trid"] ) {
                //TODO : Ajouter la catégorie de la Tendance
            }
        }
        //*/
        
    }

    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //RAPPEL : Si on est arrivé ici c'est forcement que WOS c'est assuré que CU est connecté.
        //On masque les erreurs NOTICE, WARNING car ça fausse les résultats cote FE
//        @session_start();
        
//        var_dump(__LINE__,$_COOKIE);
//        var_dump(__LINE__,$_SESSION); 
//        exit();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //Récupération des données sur le compte cible
        $target = $STOI->getCurr_wto()->getUser();
        //Récupération des données sur le compte CU
        $curr_user = $RSTOI->getUpseudo();
        
        
        $target = $this->urq_complies($target);
        $curr_user = $this->urq_complies($curr_user);
        
        $target_id = $target["pdaccid"];
        $curr_user_id = $curr_user["pdaccid"];
        
        //On vérifie bien que la demande concerne bien CU. Auquel cas il faut faire une redirection
        if ( strtolower($target_id) !== strtolower($curr_user_id) ) {
            //Ce cas est NORMALEMENT IMPOSSIBLE. Il serivra cependant de garde fou 'PARANOIAQUE'
            $this->signalErrorWithoutErrIdButGivenMsg("FORBIDDEN", __FUNCTION__, __LINE__);
            exit();
        } 
        
        /*
         * ETAPE :
         *      Vérification des données sur la "page".
         */
        $din = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required();
        $XPTD = ["pg"];
        
        /*
         * ETAPE :
         *  On récupère les données UPS Optionelles
         */
        $this->KDIn["ups_optional"] = $_SESSION["sto_infos"]->getCurr_wto()->getUps_optional();
        
        $com = array_intersect($XPTD, array_keys($din));
        
//        var_dump($com);
//        exit();
        
        if ( ! ( isset($din) && is_array($din) && count($din) && ( count($com) === count($XPTD) ) ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$din],'v_d');
            $this->signalError("err_sys_l7comn1",__FUNCTION__, __LINE__);
        }
        
        foreach ($din as $k => $v) {
            if ( empty($v) | !in_array(strtolower($din["pg"]), ["ml","tr","fv","abme"]) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$din],'v_d');
                $this->signalError("err_sys_l7comn1",__FUNCTION__, __LINE__);
            }
        }
        
        //gvn : GiVeN
        $this->KDIn["gvn_pg"] = $din["pg"];
        
        $this->KDIn["target"] = $target;
        $this->KDIn["curr"] = $curr_user;
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$_SESSION, $this->KDIn],'v_d');
//        var_dump(__FILE__,__FUNCTION__, __LINE__, [$_SESSION, $this->KDIn]);
//        exit();
        
        //TODO ? Changer la langue de telle sorte qu'on puisse à chaque fois tomber sur du FR en attendant que la version EN soit totallement opérationnelle.
    }

    
    public function on_process_in() {
        $ec_stt = $this->EC_Handle();
        if ( $ec_stt !== "_EC_STT_NO_EC" ) {
            $this->KDOut["econfirm"]["ec_is_ecofirm"] = TRUE;
            $this->KDOut["econfirm"]["ec_state"] = $ec_stt;
            $this->KDOut["econfirm"]["ec_scope"] = "TQR_TMLNR";
        }
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
         /* CU */
        $this->AcquireCUDatas();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        /* OWNER */
        $this->AcquireOWnerDatas();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        /* RELATION */
        $this->AcquireUREL (); 
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        if ( strtolower($this->KDIn["gvn_pg"] === "ml") ) {
            /* ACQUISITION DES ARTICLES */
            $this->AcquireArticlesDatas();
        } else if ( strtolower($this->KDIn["gvn_pg"] === "tr") ) {
            /* ACQUISITION DES TENDANCES */
            $this->AcquireTrendsDatas();
        } else {
            /* ACQUISITION DES FIRST ARTICLES */
            $this->AcquireFavArtsDatas();
        }
        $this->KDOut["sector"] = strtoupper($this->KDIn["gvn_pg"]);
        
        /* CUSTOMS HEAD TAG */
        $this->CustumHeaderDatas();
        
        //TODO : Traitement des données avant envoi au niveau de VIEW */
        
    }

    public function on_process_out() {
        //QUESTION : Esr ce que l'utilisateur actif est connecté ? (Sert aux modules en aval)
        $_SESSION["ud_carrier"]["iauth"] = TRUE;
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        /* CURRENTUSER PAGE DATAS */
        foreach ($this->GetCUDatas() as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }

        /* OWNER PAGE DATAS */
        /*
         * Les données concernent aussi bien celles sur l'identité de l'OWNER que sur les statistiques liées à son Compte, ses Relations ou son activité.
         */
        foreach ($this->GetOWnerDatas() as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /*
         * USER PREFERENCES DECISIONS
         */
        if ( key_exists("cuprefdcs", $this->KDout) && !empty($this->KDout["cuprefdcs"]) && is_array($this->KDout["cuprefdcs"]) && count($this->KDout["cuprefdcs"]) ) {
            $_SESSION["ud_carrier"]["cuprefdcs"] = $this->GetPreferencesDatas();
        }
                
        //Relation entre les deux utilisateurs
        $_SESSION["ud_carrier"]["urel"] = $this->GetUREL();
        
        if ( strtolower($this->KDIn["gvn_pg"]) === "ml" ) {
            /* On sérialise le tableau pour qu'il soit utilisable au niveau de Tenmplates */
//        $_SESSION["ud_carrier"]["iml_articles"] = []; //CAS A TRAITER
            $_SESSION["ud_carrier"]["iml_articles"] = $this->GetArticlesIMLDatas();
            $_SESSION["ud_carrier"]["itr_articles"] = $this->GetArticlesITRDatas();
        } else if ( strtolower($this->KDIn["gvn_pg"]) === "tr" ) {
            $_SESSION["ud_carrier"]["pg_trends_datas"] = $this->GetTrendsDatas();
        } else {
            $_SESSION["ud_carrier"]["pg_favs_datas"] = $this->GetFavsDatas();
        }
//        var_dump($_SESSION["ud_carrier"]["pg_trends_datas"]);
//        var_dump($_SESSION["ud_carrier"]["pg_favs_datas"]);
//        exit();
        
        /*
         * [DEPUIS 19-09-15] @author BOR
         */
        $_SESSION["ud_carrier"]["iml_articles_count"] = $this->KDout["iml_articles_count"];
        $_SESSION["ud_carrier"]["itr_articles_count"] = $this->KDout["itr_articles_count"];
        
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
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$this->KDOut["sector"]);
//        exit();
        
        
        /*
         * [DEPUIS 13-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( $this->KDIn["curr"]["pdaccid"] ) {
            $PM = new POSTMAN();
            $sector = strtolower($this->KDOut["sector"]);
            switch ($sector) {
                case "ml" :
                        $log_r = $this->Wkr_LogUsertagActy($PM, $this->KDIn["curr"]["pdaccid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["srv_curl"], $this->KDIn["srv_curl"], FALSE, $this->KDIn["target"]["pdaccid"], 112, TRUE);
                    break;
                case "tr" :
                        $log_r = $this->Wkr_LogUsertagActy($PM, $this->KDIn["curr"]["pdaccid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["srv_curl"], $this->KDIn["srv_curl"], FALSE, $this->KDIn["target"]["pdaccid"], 113, TRUE);
                    break;
                case "fv" :
                        $log_r = $this->Wkr_LogUsertagActy($PM, $this->KDIn["curr"]["pdaccid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["srv_curl"], $this->KDIn["srv_curl"], FALSE, $this->KDIn["target"]["pdaccid"], 114, TRUE);
                    break;
                case "abme" :
//                        $log_r = $this->Wkr_LogUsertagActy($PM, $this->KDIn["curr"]["pdaccid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["srv_curl"], $this->KDIn["srv_curl"], FALSE, $this->KDIn["target"]["pdaccid"], 114, TRUE);
                    break;
            }
        }
        
        
        /* 
         * On inscrit certaines informations relatives à la page.
         * Ces informations sont données par WORKER car 'ver' dépend du fait qu'on soit sure que la procédure c'est passé corectement.
         * Seuls les WORKER définissent les droits d'accès.
         * 
         * En ce qui concerne 'pgid', WORKER le fait car certaines URQ sont de type AJAX qui n'admet pas de page.
         * C'est donc au WORKER de définir ces informations.
         */
        $_SESSION["ud_carrier"]["pagid"] = "tmlnr";
        $_SESSION["ud_carrier"]["pgakxver"] = "ro";
        $_SESSION["ud_carrier"]["sector"] = $this->KDOut["sector"];
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}

?>