<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_GTPG_WLC extends WORKER  {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    /* ---- CONFORMITE ---- */
    private function urq_complies ($target) {
        //QUESTION : Est ce que l'utilisateur, au regard des règles de fonctionnement du produit, a le droit d'accéder à cette page?
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //* On vérifie que le pseudo est conforme au format attendu *//
        
        //On vérifie s'il y a un '@'. Si oui on le retire
        $pos = strpos($target, '@');
        
        if ( $pos === 0 || $pos > 0 ) {
            if ( $pos === 0 ) {
                //On extrait de la chaine le pseudo sans le caractère '@'.
                //RAPPEL : Les pseudos ne peuvent pas avoir des caractères autres que des lettres, chiffres,_ et certains caractères alphabéthiques de langues étrangères
                $target = substr($target, 1);
            }
            else {
                //On signale que l'on ne trouve pas l'utilisateur. En effet, le pseudo d'un utilisateur ne peut pas avoir de
                $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
                exit();
            }
        }
        $TX = new TEXTHANDLER();
        
        //On vérifie que le pseudos respecte la regex
        if (! $TX->valid_user_in_url($target) ) {
            $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
            exit();
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
         * [NOTE 21-02-15]  @Loukz
         * On ajoute une option pour dire qu'on considère que si le compte de l'utilisateur est en processus de suppression => il n'existe pas.
         */
        $A = new PROD_ACC();
        $exists = $A->exists_with_psd($target,TRUE);
        
        if (! $exists ) {
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
//            $this->signalErrorWithoutErrIdButGivenMsg("USER_NOT_FOUND", __FUNCTION__, __LINE__);
//            exit();
        }
        else {
            return $exists;
        }
        
        
    }
    
    /* ---- OWNER IDENTITY ---- */
    
    private function AcquireOWnerDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        /*
         * La récupération des données se fait grace au pseudo passé en paramètre acquis depuis $_GET["user"]
         */
        
        $target = $this->KDIn["target"];
        
        $accid = $target["pdaccid"];
        
        $A = new PROD_ACC();
        $A->on_read_entity(["accid" => $accid]);
        
        //TODO : A quelle moment on sécurise les données ?
        
        $OW = [];
        $OW["oueid"] = $A->getPdacc_eid();
        $OW["ouppic"] = $A->getPdacc_uppic();
        $OW["oufn"] = $A->getPdacc_ufn();
        $OW["oupsd"] = $A->getPdacc_upsd();
       /*
        * [DEPUIS 26-06-15] @BOR
        * L'utilisateur de '@' engendre trop d'opérations et nuit à la performance.
        */
        $OW["ouhref"] = "/".$A->getPdacc_upsd();
//        $OW["ouhref"] = "/@".$A->getPdacc_upsd();
        $OW["outesty"] = html_entity_decode($A->getPdacc_profilbio());
        $OW["ouwbst"] = html_entity_decode($A->getPdacc_website());
//        $OW["outesty"] = $A->getPdacc_profilbio();
        $OW["oudl"] = $A->getPdacc_udl();
        $OW["oucity_id"] = $A->getPdacc_ucityid();
        $OW["oucity"] = $A->getPdacc_ucity_fn();
        $OW["oucn"] = strtolower($A->getPdacc_ucnid());
        $OW["oucn_fn"] = strtoupper($A->getPdacc_ucnid());
//        $OW["oucover"] = $A->getPdacc_coverpic() ;
        
        /************** COVER ****************/
        
        $c_datas = $A->getPdacc_coverdatas();
        
        if ( isset($c_datas) && is_array($c_datas) && count($c_datas) ) {
            $OW["oucover_width"] = $c_datas["acov_width"]."px";
            $OW["oucover_height"] = $c_datas["acov_height"]."px";
            $OW["oucover_top"] = $c_datas["acov_top"]."px";
            $OW["oucover_rpath"] = $c_datas["acov_rpath"];
        } else {
            $OW["oucover_rpath"] = NULL;
        }
        
        
        /************** STATS ****************/
        $folw_nb = $A->onread_get_myfolrs_count($accid,["AQAP"]); 
        $folg_nb = $A->onread_get_myfolgs_count($accid,["AQAP"]);
        
        $OW["oucapital"] = $A->getPdacc_capital();
        $OW["oufolsnb"] = $folw_nb;
        $OW["oufolgsnb"] = $folg_nb;
        $OW["oupostnb"] = intval($A->getPdacc_stats_posts_nb());
        $OW["outrnb"] = $A->getPdacc_stats_mytrends_nb();
        $OW["ouabtrnb"] = $A->getPdacc_stats_fol_trends_nb();
        
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$OW,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        var_dump(__LINE__,$OW);
//        exit();
        
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
        
        //On charge les données de l'utilisateur
        $A = new PROD_ACC();
        $exists = $A->exists_with_psd($this->KDIn["target"]["pdacc_upsd"]);
        
        if (! $exists ) {
            $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
            exit();
        }
        
        $accid = $exists["pdaccid"];
        
//        $A->on_read_entity(["accid" => $accid]); //[DEPUIS 13-07-15] @BOR
        
        //On récupère toutes les First Articles
//        $art_stack = $A->onread_load_my_first_articles($accid);
        /*
         * [DEPUIS 28-04-15] @BOR
         * Pour harmoniser le processus d'encodage avec TRPG et profiter du fait que c'est plus performant
         */
        $art_stack = $A->onread_load_my_first_articles($accid, NULL, ["VM_ART"]);
//        var_dump($art_stack);
//        exit();
        
        $ART = new ARTICLE();
        
        /****************************************************/
        /* On créer le modèle pour les Articles de type IML */
        $iml_articles = [];
        
        if ( $art_stack && key_exists("iml", $art_stack) && isset($art_stack["iml"]) && count($art_stack["iml"]) ) {
            foreach ( $art_stack["iml"] as $k => $article ) {
                
//                $rnb = ( isset($article["art_list_reacts"]) && is_array($article["art_list_reacts"]) && count($article["art_list_reacts"]) ) ? count($article["art_list_reacts"]) : 0;
                
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
                    "img"       => $article["art_pdpic_path"],
                    //Interessant pour SEO
                    "msg"       => html_entity_decode($article["art_desc"]),
//                    "prmlk"     => "", //[NOTE 29-04-15] @BOR Non utilisé
                    /*
                     * [DEPUIS 28-04-16] @BOR
                     */
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid), 
                    //L'appreciation affichée correspond à la différence entre toutes les appréciations
                    "eval"      => $article["art_eval"], /* [-1,+2,+1,total]*/
                    //L'évaluation que j'ai donné pour cet article
                    "myel"      => "", //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    //Le nombre de commentaires 
                    "rnb"       => $article["art_rnb"],
                    "hashs"     => $article["art_list_hash"],
                    "ustgs"     => NULL,
                    /*
                     * [DEPUIS 18-12-15]
                     */
                    "hasfv"     => FALSE,
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
                     * Permet d'indiquer aux modules en aval s'il s'agit d'un Article distribué sous licence WELC.
                     * Cette information est utile pour adapter certaines fonctionnalités.
                     */
                    "isrtd"     => FALSE,
                    /*
                     * L'utilisateur a t-il le droit d'accéder aux foncitonnalités ACTION de l'Article
                     */
                    "af_ena"    => FALSE
                ];
            }
            
        }
        
        /* On stocke le tableau dans KDatas */
        $this->KDout["iml_articles"] = $iml_articles;
        
        /*
         * [DEPUIS 19-09-15] @author BOR
         * [NOTE 19-09-15] @author BOR
         *      A été créé dans le but de permet à XYZ de gérer le cas NoOne dans une section spécifique.
         *      Mais d'autres applications pourront en découler.
         */
        $this->KDout["iml_articles_count"] = count($iml_articles);
        
//        var_dump($iml_articles,$this->KDout["iml_articles"]);
//        var_dump($art_stack["itr"]);
//        exit();
        
        /****************************************************/
        /* On créer le modèle pour les Articles de type itr */
        $itr_articles = [];
        
        if ( $art_stack && key_exists("itr", $art_stack) && isset($art_stack["itr"]) && count($art_stack["itr"]) ) {
            foreach ( $art_stack["itr"] as $k => $article ) {
                
//                $rnb = ( isset($article["art_list_reacts"]) && is_array($article["art_list_reacts"]) && count($article["art_list_reacts"]) ) ? count($article["art_list_reacts"]) : 0;
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
                
                $TRD = new TREND();
                $itr_articles[] = [
                    //Dans la page de developpement, ca n'arrive pas à 20. On peut donc utiliser >20
        //            "id" => 1022, //Tester la fonctionnalité de blocage de Articles en doublon
                    "id"        => $article["art_eid"],
                    "time"      => $article["art_creadate"],
                    "img"       => $article["art_pdpic_path"],
                    //Interessant pour SEO
//                    "msg"       => html_entity_decode($article["art_desc"]),
                    "msg"       => $article["art_desc"], //[DEPUIS 29-04-15] @BOR
                    /*
                     * [NOTE 29-04-15] @BOR Non utilisé
                     * [DEPUIS 01-05-15] @BOR
                     */
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid), 
                    //L'appreciation affichée correspond à la différence entre toutes les appréciations
                    "eval"      => $article["art_eval"], /* [-1,+2,+1,total]*/
                    //L'évaluation que j'ai donné pour cet article
                    "myel"      => "", //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    //Le nombre de commentaires 
                    "rnb"       => $article["art_rnb"],
                    "hashs"     => $article["art_list_hash"],
                    "ustgs"     => $ustgs,
                    /*
                     * [DEPUIS 18-12-15]
                     */
                    "hasfv"     => FALSE,
                    "vidu"      => $article["art_vid_url"],
                    /******** OWNER DATAS ********/
                    "ueid"      => $article["art_oeid"],
                    "uppic"     => $A->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                    "uppic"     => $article["art_oppic"],
                    "ufn"       => $article["art_ofn"],
                    "upsd"      => $article["art_opsd"],
                    "uhref"     => "/@".$article["art_opsd"], //[AJOUTE 29-04-15]
                    
                    /******** TREND DATAS ********/
                    //L'id externe de la TENDANCE.
                    "trd_eid"   => $article["trd_eid"],
                    "trtitle"   => $article["trd_title"],
                    "trhref"    => $TRD->on_read_build_trdhref($article["trd_eid"],$article["trd_title_href"]),
//                    "trhref"    => $article["trd_href"], [28-04-15]
                    "istrd"     => TRUE,
                    /*
                     * NOTE :
                     *      Permet d'indiquer aux modules en aval s'il s'agit d'un Article distribué sous licence WELC.
                     *      Cette information est utile pour adapter certaines fonctionnalités.
                     */
                    "isrtd"     => FALSE
                ];
            }
            
        }
        
        
        /* On stocke le tableau dans KDatas */
        $this->KDout["itr_articles"] = $itr_articles;
        
        
        /*
         * [DEPUIS 19-09-15] @author BOR
         * [NOTE 19-09-15] @author BOR
         *      A été créé dans le but de permet à XYZ de gérer le cas NoOne dans une section spécifique.
         *      Mais d'autres applications pourront en découler.
         */
        $this->KDout["itr_articles_count"] = count($itr_articles);
    }
      
    private function GetArticlesITRDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
        $ts = base64_encode(serialize($this->KDout["itr_articles"]));
        return $ts;
    }
    
    private function GetArticlesIMLDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
        //TODO : Vérifier que le tableau est au moins défini. Sinon, un bug se produira au niveau de VPRSER
        $ts = base64_encode(serialize($this->KDout["iml_articles"]));
        return $ts;
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
        
        $arts = $ART->Favorite_GetFavArts($this->KDIn["target"]["pdaccid"], NULL, "FST", NULL, NULL, NULL, ["ONLY_PUB"]);
//        $arts = $ART->Favorite_GetFavArts($this->KDIn["target"]["pdaccid"], NULL, "FST", NULL, NULL, 2, ["ONLY_PUB"]); //DEV, TEST, DEBUG
        
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
                    "hasfv"     => FALSE,
                    /*
                     * [DEPUIS 21-04-16]
                     */
                    "vidu"      => $article["art_vid_url"],
                    "isod"      => ( $article["art_is_sod"] === 1 ) ? TRUE : FALSE,
                    /*
                     * [DEPUIS 29-03-16]
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
                
                $r__ = $TQR->sugg_GetChoosenProfils($this->KDout["OWner"]["oueid"],NULL,1);
                $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
            } else {
               /*
                * Dans ce cas, l'utilisateur vient d'une page appartenant à TRENQR
                */
                if ( in_array(strtoupper($upieces["urqid"]),["ONTRENQR","TRPG_GTPG_WLC","FKSA_GTPG","FAQ_GTPG_ABOUT","FAQ_GTPG_TERMS","FAQ_GTPG_PRIVACY","FAQ_GTPG_COOKIES"]) ) {
                    $shw_captvt = TRUE;
                    
                    $r__ = $TQR->sugg_GetChoosenProfils($this->KDout["OWner"]["oueid"],NULL,1);
                    $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
                }
            }
            
//            var_dump(__LINE__,__FILE__,$referer,$this->KDIn["upieces"]);
            
        } else if ( !$referer ) {
           /*
            * Dans ce cas, on a aucune information. L'utilisateur a du atterir diectement sur la page
            */ 
            $shw_captvt = TRUE;
            
            $r__ = $TQR->sugg_GetChoosenProfils($this->KDout["OWner"]["oueid"],NULL,1);
            $this->KDOut["captivate"]["sgg_upsd"] = $r__[0]["upsd"];
        }
        
        $this->KDOut["captivate"]["show"] = $shw_captvt;
//        var_dump(__LINE__,__FUNCTION__,$referer,$upieces,$this->KDOut["captivate"]);
//        exit();
    }
    
    /****************** END SPECFIC METHODES ********************/
    
    
    public function prepare_datas_in() {
        
        //Récupération des données sur le compte cible
        $target = $_SESSION["sto_infos"]->getCurr_wto()->getUser();
        
        /*
         * ETAPE :
         * Vérification des données sur la "page".
         */
        $din = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required();
        $XPTD = ["pg"];
        
        $com = array_intersect($XPTD, array_keys($din));
        
//        var_dump($com);
//        exit();
        
        if ( ! ( isset($din) && is_array($din) && count($din) && ( count($com) === count($XPTD) ) ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$din],'v_d');
            $this->signalError("err_sys_l7comn1",__FUNCTION__, __LINE__);
        }
        
        foreach ($din as $k => $v) {
            if ( empty($v) | !in_array(strtolower($din["pg"]), ["ml","tr","fv"]) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$din],'v_d');
                $this->signalError("err_sys_l7comn1",__FUNCTION__, __LINE__);
            }
        }
        
        //gvn : GiVeN
        $this->KDIn["gvn_pg"] = $din["pg"];
        
        $this->KDIn["target"] = $this->urq_complies($target);
        
        //TODO ? Changer la langue de telle sorte qu'on puisse à chaque fois tomber sur du FR en attendant que la version EN soit totallement opérationnelle.
    }

    public function on_process_in() {
        
        /* OWNER */
        $this->AcquireOWnerDatas($this->KDIn["target"]);
        
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
        
        /*
         * [DEPUIS 04-11-15] @author BOR
         * [DEPUIS 20-07-16]
         *      A partir de la version TQR.VB3.1, on affiche plus la fenêtre pour espérer augmenter le taux de rebond.
         *      Mais cette décision a aussi pour objectif de ne pas décourager les USER qui utilise leur mobile car elle peu pratique. 
         */
//        $this->TreatCaptivate();
        $this->KDOut["captivate"]["show"] = FALSE;
        
        $this->KDOut["sector"] = strtoupper($this->KDIn["gvn_pg"]);
        
        /* CUSTOMS HEAD TAG */
        $this->CustumHeaderDatas();
        
    }

    public function on_process_out() {
        //QUESTION : Esr ce que l'utilisateur actif est connecté ? (Sert aux modules en aval)
        $_SESSION["ud_carrier"]["iauth"] = FALSE;
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        /* OWNER PAGE DATAS */
        /*
         * Les données concernent aussi bien celles sur l'identité de l'OWNER que sur les statistiques liées à son Compte, ses Relations ou son activité.
         */
        foreach ($this->GetOWnerDatas() as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
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
        
        /* PAGE HEAD DATAS */
        /*
         * Il s'agit des données destinées à être ajoutées au niveau du header de la page.
         */
        foreach ($this->KDout["head"] as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /*
         * [DEPUIS 03-11-15] @author BOR
         *      Gestion de CAPTIVATE
         */
        $_SESSION["ud_carrier"]["captivate_sgg_upsd"] = $this->KDOut["captivate"]["sgg_upsd"];
        $_SESSION["ud_carrier"]["captivate_show"] = $this->KDOut["captivate"]["show"];
        
        /* 
         * On inscrit certaines informations relatives à la page.
         * Ces informations sont données par WORKER car 'ver' dépend du fait qu'on soit sure que la procédure c'est passé corectement.
         * Seuls les WORKER définissent les droits d'accès.
         * 
         * En ce qui concerne 'pgid', WORKER le fait car certaines URQ sont de type AJAX qui n'admet pas de page.
         * C'est donc au WORKER de définir ces informations.
         */
        $_SESSION["ud_carrier"]["pagid"] = "tmlnr";
        $_SESSION["ud_carrier"]["pgakxver"] = "wu";
        $_SESSION["ud_carrier"]["sector"] = $this->KDOut["sector"];
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}
?>