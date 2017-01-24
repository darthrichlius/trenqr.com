<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TRPG_GTPG_WLC
 *
 * @author arsphinx
 */
class WORKER_TRPG_GTPG_WLC extends WORKER {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /****************************************************************************************************/
    /*************************************** ACQUIRE AREA START *****************************************/
    
    /* ---- OWNER IDENTITY ---- */
    
    private function AcquireOWnerDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        /*
         * La récupération des données se fait grace au pseudo passé en paramètre acquis depuis $_GET["user"]
         */
        
        $A = new PROD_ACC();
        $u_tab = $A->on_read_entity(["accid" => $this->KDout["trend_infos"]["trd_oid"]]);
//        var_dump($u_tab);
//        exit();
        if ( !$u_tab || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $u_tab)  ) {
            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
//            $this->signalErrorWithoutErrIdButGivenMsg("OWNER NOT FOUND", __FUNCTION__, __LINE__);
//            exit();
        }
        
        $OW = [];
        $OW["oueid"] = $A->getPdacc_eid();
        $OW["ouppic"] = $A->getPdacc_uppic();
        $OW["oufn"] = $A->getPdacc_ufn();
        $OW["oupsd"] = $A->getPdacc_upsd();
        $OW["ouhref"] = "/".$A->getPdacc_upsd();
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["OWner"] = $OW;
    }
    
    /* ---- TREND IDENTITY ---- */
    
    private function AcquireTrendDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        /*
         * La récupération des données se fait grace au pseudo passé en paramètre acquis depuis $_GET["user"]
         */
        
        $title = $this->KDIn["gvn_title"];
        $teid = $this->KDIn["gvn_eid"];
        
        //A utiliser au cas où
        $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
               
        $TRD = new TREND();
        $t_tab = $TRD->on_read_entity(["trd_eid" => $this->KDIn["gvn_eid"]]);
         
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->KDIn["gvn_title"],$t_tab["trd_title_href"]]);
        if ( !$t_tab || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_tab)  ) {
            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
        } else if (! ( strtolower($this->KDIn["gvn_title"]) === strtolower($t_tab["trd_title_href"]) ) ) {
            /*
             * [DEPUIS 11-06-15] @BOR
             */
            $args_redir = [
                "teid"  => strtolower($teid),
                "title" => strtolower($title),
                "lang"  => $_SESSION["sto_infos"]->getDefault_lang()
            ];

            $url = $RDH->redir_build_scoped_url("TRPG_GTPG",$args_redir);
            $RDH->start_redir_to_this_url_string($url);
            exit(); //PARANO
            /*
            //On vérifie que le titre fourni correspond au titre de la Tendance passée en paramètre
            $this->signalError ("err_user_l5e404_trend", __FUNCTION__, __LINE__, TRUE);
            //*/
        } 
        
        $this->KDOut["T_TAB"] = $t_tab;
        
        $TR = [];
        
        $TR["trid"] = $t_tab["trd_eid"];
        
        $TR["trcov_w"] = ( $t_tab["trd_cover"] ) ? $t_tab["trd_cover"]["trcov_width"]."px" : NULL;
        $TR["trcov_h"] = ( $t_tab["trd_cover"] ) ? $t_tab["trd_cover"]["trcov_height"]."px" : NULL;
        $TR["trcov_t"] = ( $t_tab["trd_cover"] ) ? $t_tab["trd_cover"]["trcov_top"]."px" : NULL;
        $TR["trcov_rp"] = ( $t_tab["trd_cover"] ) ? $t_tab["trd_cover"]["pdpic_realpath"] : NULL;
        
        $TR["trtitle"] = $t_tab["trd_title"];
        
        /*
         * [DEPUIS 26-09-15] @author BOR
         */
        $TR["trtlehrf"] = $t_tab["trd_title_href"];
        
        //lgt : LongTitle, Répond à la question : "Que faire si le titre est trop long?"
        $TR["trtitle_lgt"] = ( count($TR["trtitle"]) > 75 ) ? "tr-tle-lgt" : "";
        
        $TR["trdesc"] = html_entity_decode($t_tab["trd_desc"]);
        $TR["trhref"] = $t_tab["trd_href"];
        $TR["trcrea"] = $t_tab["trd_creadate_tstamp"];
        $TR["trpart"] = ( $t_tab["trd_is_public"] ) ? "pub" : "pri";
        
        $TR["trstate"] = $t_tab["tsh_state"];
        
        $TH = new TEXTHANDLER();
        $part_lib = ( $t_tab["trd_is_public"] ) ? $TH->get_deco_text('fr', "_Public") : $TH->get_deco_text('fr', "_Private");
        
        if ( !$part_lib || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $part_lib) ) {
            $TR["trpart_lib"] = "";
        } else {
            $TR["trpart_lib"] = trim($part_lib);
        }
//        $TR["trgrat_choices"] = htmlentities("0,1,2,5,10");
//        $TR["trgrat"] = htmlentities("2");
        $TR["trcat"] = $t_tab["catg_decocode"];
        /*
         * [DEPUIS 22-11-15] @author BOR
         *      On affiche la catégorie au niveau de la VIEW
         */
        $TR["trcat_text"] = $TH->get_deco_text("fr", "_NTR_CATG_".strtoupper($t_tab["catg_decocode"]));
        
        $TR["trd_oid"] = $t_tab["trd_oid"];
        
        //QUESTION : Est-ce que l'utilisateur actif est abonné à la Tendance ?
        $TR["isown"] = FALSE;
        $TR["cuisabo"] = FALSE;
//        $TR["cuisabo"] = ( $TRD->trend_abo_exists($this->KDIn["oid"], $t_tab["trd_eid"]) ) ? true : false;
        
//        var_dump($TR);
//        exit();
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["trend_infos"] = $TR;
        
        /*
         * [DEPUIS 21-05-16]
         */
        return $t_tab;
        
    }
    
    /* ---- TREND STATS ---- */
    
    private function AcquireTrendStatsDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        /*
         * La récupération des données se fait grace au pseudo passé en paramètre acquis depuis $_GET["user"]
         */
        
        if (! ( isset($this->KDOut) && key_exists("T_TAB", $this->KDOut) && isset($this->KDOut["T_TAB"]) && is_array($this->KDOut["T_TAB"]) && count($this->KDOut["T_TAB"]) ) ) {
            //RAPPEL : Cette méthode doit être appelée après celle qui récupère les données sur la Tendance. Cela permet de réutiliser les données déjà récoltées.
            $this->signalError("err_sys_l7comn2",__FUNCTION__, __LINE__,TRUE);
        }
        
        $TR = [];
        $TR["trnbposts"] = ( key_exists("trd_stats_posts", $this->KDOut["T_TAB"]) && !empty($this->KDOut["T_TAB"]["trd_stats_posts"]) ) ? $this->KDOut["T_TAB"]["trd_stats_posts"] : 0;
        $TR["trfolws"] = ( key_exists("trd_stats_subs", $this->KDOut["T_TAB"]) && !empty($this->KDOut["T_TAB"]["trd_stats_subs"]) ) ? $this->KDOut["T_TAB"]["trd_stats_subs"] : 0;
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["trend_stats"] = $TR;
    }
    
    
    /* ---- TREND VIP ---- */
    
    private function AcquireTrendVIPDatas () {
        //Permet de récupérer les données sur l'OWner de la page.
        /*
         * La récupération des données se fait grace au pseudo passé en paramètre acquis depuis $_GET["user"]
         */
        
        if (! ( isset($this->KDOut) && key_exists("T_TAB", $this->KDOut) && isset($this->KDOut["T_TAB"]) && is_array($this->KDOut["T_TAB"]) && count($this->KDOut["T_TAB"]) ) ) {
            //RAPPEL : Cette méthode doit être appelée après celle qui récupère les données sur la Tendance. Cela permet de réutiliser les données déjà récoltées.
            $this->signalError("err_sys_l7comn2",__FUNCTION__, __LINE__,TRUE);
        } else if ( key_exists("trd_stats_vips", $this->KDOut["T_TAB"]) && !empty($this->KDOut["T_TAB"]["trd_stats_vips"]) && is_array($this->KDOut["T_TAB"]["trd_stats_vips"]) && count($this->KDOut["T_TAB"]["trd_stats_vips"]) > 3 ) {
            $this->signalError("err_sys_l7comn2",__FUNCTION__, __LINE__,TRUE);
        }
        
        $VIPs = [];
        if ( key_exists("trd_stats_vips", $this->KDOut["T_TAB"]) && !empty($this->KDOut["T_TAB"]["trd_stats_vips"]) && is_array($this->KDOut["T_TAB"]["trd_stats_vips"]) ) {
            $VIPS = $this->KDOut["T_TAB"]["trd_stats_vips"];
            foreach ($VIPS as $k => $v) {
                $VIPs[] = [
                    "ueid"  => $v["ueid"],
                    "ufn"   => $v["ufn"],
                    "upsd"  => $v["upsd"],
                    "uhref" => "/".$v["upsd"]
                ];
            } 
        }
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["trend_vip"] = $VIPs;
    }
    
    /* ---- ARTICLES ---- */
    
    private function AcquireArticlesDatas () {
        
        if (! ( isset($this->KDOut) && key_exists("T_TAB", $this->KDOut) && isset($this->KDOut["T_TAB"]) && is_array($this->KDOut["T_TAB"]) && count($this->KDOut["T_TAB"]) ) ) {
            //RAPPEL : Cette méthode doit être appelée après celle qui récupère les données sur la Tendance. Cela permet de réutiliser les données déjà récoltées.
            $this->signalError("err_sys_l7comn2",__FUNCTION__, __LINE__,TRUE);
        }
        
        if ( key_exists("trd_first_articles", $this->KDOut["T_TAB"]) && isset($this->KDOut["T_TAB"]["trd_first_articles"]) && is_array($this->KDOut["T_TAB"]["trd_first_articles"]) && count($this->KDOut["T_TAB"]["trd_first_articles"]) ) {
            
//            var_dump($this->KDOut["T_TAB"]["trd_first_articles"]);
//            exit();
            $all = $ad_west = $ad_east = $cn = NULL;
            /*
             * Pour chaque eid on read l'Article et on le trie immédiatement dans un des deux tableaux.
             */
            $start = round(microtime(TRUE)*1000);
            $cn = 0;
//            $ART = new ARTICLE_TR();
            $ART = new ARTICLE();
            foreach ($this->KDOut["T_TAB"]["trd_first_articles"] as $k => $v) {
                $aeid = $v["art_eid"];
//                $a_tab = $ART->on_read_entity(["art_eid"=>$aeid]);
//                $a_tab = $ART->on_read(["art_eid"=>$aeid]);
                $a_tab = $ART->onread_archive_itr(["art_eid"=>$aeid]);
                
                if  ( isset($a_tab) && is_array($a_tab) && count($a_tab) ) {
                   
                    if ( ($cn%2) == 0 ) {
                        $ad_west[$v["art_eid"]] = $a_tab;
                    } else {
                        $ad_east[$v["art_eid"]] = $a_tab;
                    }
                    $all[] = $a_tab;
                    ++$cn;
                }
                
                /*
                 * Sinon, on affiche pas l'Article. Il a peut être été supprimé entre temps ou autre. 
                 * Cependant, on ne va pas tout arreter pour un seul Article surtout lorsqu'il ne s'agit pas vraiment d'un bug
                 */
                
            }
            /*
            $end = round(microtime(TRUE)*1000);
            $foo = $end - $start;
            var_dump($foo);
            exit();
            //*/
            /*
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EAST",$ad_east],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WEST",$ad_west],'v_d');
            exit();
            //*/
//            $this->AcquireArticlesEastDatas($ad_east);
//            $this->AcquireArticlesWestDatas($ad_west);
            $as__ = $this->AcquireAllArticlesDatas($all);
            
            /*
//            var_dump(__LINE__,$this->KDout["articles_east"]);
//            var_dump(__LINE__,$this->KDout["articles_west"]);
            var_dump(__LINE__,$as__,$this->KDout["articles"]);
//            var_dump(__LINE__,$as__);
            exit();
            //*/
            
            $this->KDout["articles"] = base64_encode(serialize($as__));
        }
        
    }
    
    private function AcquireArticlesWestDatas ($datas) {
        //Permet de récupérer les données sur les Articles de type IML depuis les différents Entity et la base de données
        
        $articles = [];
        foreach ($datas as $k => $v) {
            $temp = $this->PrepareArticle($v);
            
            if ( isset($temp) ) {
                $articles[$v["art_eid"]] = $temp;
            }
        }
        
        /* On stocke le tableau dans KDatas */
        $this->KDout["articles_west"] = $articles;
    }
    
    
    private function AcquireArticlesEastDatas ($datas) {
        //Permet de récupérer les données sur les Articles de type IML depuis les différents Entity et la base de données
        //Permet de récupérer les données sur les Articles de type IML depuis les différents Entity et la base de données
        
        $articles = [];
        foreach ($datas as $k => $v) {
            $temp = $this->PrepareArticle($v);
            
            if ( isset($temp) ) {
                $articles[$v["art_eid"]] = $temp;
            }
        }
        
        /* On stocke le tableau dans KDatas */
        $this->KDout["articles_east"] = $articles;
    }
    
    private function AcquireAllArticlesDatas ($datas) {
        /*
         * Permet de mettre en forme les Articles.
         */
        
        $r_articles = [];
        foreach ($datas as $k => $v) {
            $temp = $this->PrepareArticle($v);
            if ( isset($temp) ) {
                $r_articles[] = $temp;
            }
        }
        
        return $r_articles;
    }
    
    private function PrepareArticle ( $art_tab ) {
        
        if (! (isset($art_tab) && is_array($art_tab) && count($art_tab) ) ) {
            return;
        }
        
        /*
         * ETAPE : 
         * On traite les UserTags
         */
        $ART = new ARTICLE();
        $ustgs = $ART->onread_AcquiereUsertags_Article($art_tab["art_eid"],TRUE);
        
        /*
         * ETAPE :
         * On récupère la contribution du propriétaire de l'Article à l'ensemble de la Tendance.
         */
        $TRD = new TREND();
        $ocontrb = $TRD->onread_usercontrib($art_tab["art_oid"],$art_tab["trid"]);
       
       /*
        * ETAPE 
        *      Est ce que l'utilisateur connecté (le cas échéant) a AUSSI FAV ?
        *      On récupère le type de FAV, le cas échéant
        */
        $cuftab = NULL;
        $cuftp = NULL;
        
        $ivid = ( $art_tab["art_vid_url"] ) ? TRUE : FALSE;
        
        $PA = new PROD_ACC();
        $article = [
                "id"            => $art_tab["art_eid"],
                "img"           => $art_tab["art_pdpic_path"],
        //                "art_img" => "http://www.placehold.it/370x370",
                "time"          => $art_tab["art_creadate"],
                //Interessant pour SEO
//                "art_desc"      => $art_tab["art_desc"], //[DEPUIS 29-04-15] @BOR
//                "art_desc"      => html_entity_decode($art_tab["art_desc"]), //[DEPUIS 27-04-15] @BOR
//                "art_desc"      => html_entity_decode(html_entity_decode($art_tab["art_desc"])),//[NOTE 26-04-15] @BOR A cause du fait qu'on utilise VM qui subbit une double HTMLENTITIES
                "msg"           => $art_tab["art_desc"], //[DEPUIS 22-04-16] @BOR
                "prmlk"         => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($art_tab["art_eid"],$ivid),
                "rnb"           => $art_tab["art_rnb"],
                "ustgs"         => $ustgs,
               /*
                * [DEPUIS 18-12-15]
                */
                "hasfv"         => FALSE,
               /*
                * [DEPUIS 21-04-15]
                */
                "hashs"         => $art_tab["art_list_hash"],
                "fvtp"          => $cuftp,
                "fvtm"          => NULL,
                "vidu"          => $art_tab["art_vid_url"],
                "isod"          => ( $art_tab["art_is_sod"] === 1 ) ? TRUE : FALSE,
               /*
                * [DEPUIS 29-03-16]
                *      Indique si l'ARTICLE doit être distribué en mode RESTRICTED
                */
                "isrtd"         => FALSE,
                /* EVAL DATAS */
                "eval"          => $art_tab["art_eval"], /* [-1,+2,+1,total]*/
                //L'évaluation que j'ai donné pour cet article
                "myel"          => "", //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                //Données sur la Tendance
                "trd_eid"       => $art_tab["trd_eid"],
                "trtitle"       => $art_tab["trd_title"],
                "trhref"        => $TRD->on_read_build_trdhref($art_tab["trd_eid"],$art_tab["trd_title_href"]),
                "istrd"         => TRUE,
                /* ARTICLE OWNER DATAS */
                "ueid"          => $art_tab["art_oeid"],
                "ufn"           => $art_tab["art_ofn"],
                "upsd"          => $art_tab["art_opsd"],
                "uppic"         => $PA->onread_acquiere_pp_datas($art_tab["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                "uppic"         => $art_tab["art_oppic"],
                "uhref"         => "/".$art_tab["art_opsd"],
//                "ucontb" => NULL, //Non traité à vb1.10.14
//                "ucontb"        => NULL, //Non traité à vb1.10.14
                "ucontb"        => $ocontrb, //[NOTE 06-04-15]
                "art_eval_lt"   => NULL
            ];
            
        return $article;
    }
    
    /* ---- ART VIP ---- */
    
    private function AcquireArtVIPDatas () {
        //Permet de récupérer les données sur les VIP de l'article.
        /*
         * La récupération des données se fait grace à l'identifiant de l'Article
         */
        
        //*
        //SIMULATION
        $VIP = [];
        for ( $i=0; $i<3; $i++ ) {
            $VIP[] = [
                "upsd"  => htmlentities("@VIP$i"),
                "uhref" => htmlentities("/@VIP$i")
            ];
        }
        $VIP[] = "110";
        //*/
        
//        return htmlentities(serialize($VIP));
        return $VIP;
    }
    
    
    /* ---- CU EVAL ---- */
    
    /***************************************** ACQUIRE AREA END *****************************************/
    /****************************************************************************************************/
    
    
    
    /****************************************************************************************************/
    /***************************************** ACQUIRE AREA *********************************************/
    
    private function GetOWnerDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Vérifier si les données sont effectivement présentes. Sinon, on termine avec une erreur technique.
        return $this->KDout["OWner"];
    }
    
    private function GetTrendDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Vérifier si les données sont effectivement présentes. Sinon, on termine avec une erreur technique.
        return $this->KDout["trend_infos"];
    }
    
    private function GetTrendStatsDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Vérifier si les données sont effectivement présentes. Sinon, on termine avec une erreur technique.
        return $this->KDout["trend_stats"];
    }
    
    private function GetTrendVIPDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Vérifier si les données sont effectivement présentes. Sinon, on termine avec une erreur technique.
        return htmlentities(serialize($this->KDout["trend_vip"]));
    }
    
    private function GetArticlesEastDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
        //return htmlentities(serialize($this->KDout["articles_east"])); //OBSELETE
        
        //[NOTE 07-10-14] @author L.C.
        $ts = base64_encode(serialize($this->KDout["articles_east"]));
        return $ts;
    }
    
    private function GetArticlesWestDatas () {
        //Renvoie un tableau contenant les données sur les Articles 
        
        //TODO : Ajouter un try ... catch. Si une exception est levée, on termine avec une erreur technique. Si aucune donnée n'est disponible on renvoie un tableau vide.
        //[NOTE 06-08-14] Si on echappe pas les " (quote) eval() ne pourra pas traiter correctement la chaine. UN erreur T_STRING apparaitra à cause des guillemets.
//        return htmlentities(serialize($this->KDout["articles_west"])); //OBSELETE
        
        //[NOTE 07-10-14] @author L.C.
        $ts = base64_encode(serialize($this->KDout["articles_west"]));
        return $ts;
    }
    
   /*
    * [DEPUIS 26-09-15] @author BOR 
    */
    private function CustumHeaderDatas() {
        $this->KDout["head"]["head_trtlehrf"] = strtolower($this->KDout["trend_infos"]["trtlehrf"]);
        $this->KDout["head"]["head_trcov"] = ( $this->KDout["trend_infos"]["trcov_rp"] ) ? strtolower($this->KDout["trend_infos"]["trcov_rp"]) : $this->KDout["OWner"]["ouppic"];
        
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
    
    
    /***************************************** ACQUIRE AREA END *****************************************/
    /****************************************************************************************************/
          
   
    /*************************************************************************************************/
    /***************************************** PROCESS AREA ******************************************/
    
    public function prepare_datas_in() {
        //RAPPEL : Si on est arrivé ici c'est forcement que WOS c'est assuré que CU est connecté.
//        var_dump($_SESSION["sto_infos"]->getCurr_wto()->getUps_required());
//        exit();
        
        @session_start();
        /*
         * TODO : Verifier que les donnéees obligatoires sont disponibles et valides
         * TODO : Rediriger en cas de problème
         * TODO : Formatter dans KDin les données dont on a besoin 
         */
        
        $din = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required();
        $XPTD = ["tei","tle"];
        
        $com = array_intersect($XPTD, array_keys($din));
        
//        var_dump($com);
//        exit();
        
        if ( ! ( isset($din) && is_array($din) && count($din) && ( count($com) === count($XPTD) ) ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$din],'v_d');
            $this->signalError("err_sys_l7comn1",__FUNCTION__, __LINE__);
        }
        
        foreach ($din as $k => $v) {
            if ( empty($v) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$din],'v_d');
                $this->signalError("err_sys_l7comn1",__FUNCTION__, __LINE__);
            }
        }
        
        //gvn : GiVeN
        $this->KDIn["gvn_title"] = $din["tle"];
        $this->KDIn["gvn_eid"] = $din["tei"];
        //CU datas
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
    }
        
    public function on_process_in() {
        
        /* TREND */
        $trtab = $this->AcquireTrendDatas();
        
        /*
         * [DEPUIS 21-05-16]
         */
        @session_start();
        unset($_SESSION["apps"]["ltc"]);
        $_SESSION["apps"]["ltc"] = [
            "trid"      => $trtab["trid"],
            "treid"     => $trtab["trd_eid"]
        ];
        
        /* OWNER */
        $this->AcquireOWnerDatas();
        
        /* TREND STATS */
        $this->AcquireTrendStatsDatas();
        
        /* TREND VIP */
        $this->AcquireTrendVIPDatas();
        
        /* ARTICLES ITR */
        $this->AcquireArticlesDatas();
        
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
        
        /* OWNER TREND DATAS */
        foreach ($this->GetOWnerDatas() as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /* TREND IDENTITY DATAS */
        foreach ($this->GetTrendDatas() as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /* TREND STATS DATAS */
        foreach ($this->GetTrendStatsDatas() as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /* TREND VIP DATAS */
        $_SESSION["ud_carrier"]["trend_vip"] = $this->GetTrendVIPDatas();
        
        /* TREND ARTICLES DATAS for EAST */
        $_SESSION["ud_carrier"]["articles_east"] = $this->GetArticlesEastDatas();
        
        /* TREND ARTICLES DATAS for WEST */
        $_SESSION["ud_carrier"]["articles_west"] = $this->GetArticlesWestDatas();
        
        $_SESSION["ud_carrier"]["trpg_articles"] = $this->KDout["articles"];
        
        /* PAGE HEAD DATAS */
        /*
         * Il s'agit des données destinées à être ajoutées au niveau du header de la page.
         */
        foreach ($this->KDout["head"] as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        
        /* 
         * On inscrit certaines informations relatives à la page.
         * Ces informations sont données par WORKER car 'ver' dépend du fait qu'on soit sure que la procédure c'est passé corectement.
         * Seuls les WORKER définissent les droits d'accès.
         * 
         * En ce qui concerne 'pgid', WORKER le fait car certaines URQ sont de type AJAX qui n'admet pas de page.
         * C'est donc au WORKER de définir ces informations.
         */
        $_SESSION["ud_carrier"]["pagid"] = "trpg";
        $_SESSION["ud_carrier"]["pgakxver"] = "wu";
    }

    protected function prepare_params_in_if_exist() {
        
    }

}