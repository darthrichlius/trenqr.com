<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_GARTS_FM extends WORKER  {
    
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
            
            //*
            $SKIP = ["hmt"];
            if ( !( isset($v) && $v !== "" ) && in_array($k, $SKIP) ) {
                continue;
            }
            //*/
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            if ( $k === "w" && !in_array($v, ["std","new","old"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            } 
            
        }
    }
    
    
    private function LockBtmCase() {
        /*
         * Permet de gérer l'opération de LOCK de la page dans le cas de WLC.
         * 
         * LES CAS DE BLOCAGE
         *  CAS 1 : L'utilisateur a déjà cliqué deux fois sur le bouton
         *  CAS 2 : Il a cliqué 1 fois sur le bouton et il n'y a plus d'articles disponibles
         */
        
        /*
         * ETAPE :
         *      On traite le cas des références de chargement s'ils existent
         */
        $hmt = $this->KDIn["datas"]["hmt"];
        if ( !$hmt || !explode(',',$hmt) ) {
            return TRUE; //On indique au script qu'il peut continuer son chemin normalement.
        }
        
        $ids = explode(',',$hmt);
        if (! $ids ) {
            /*
             * C'est considéré comme un HACK !
             */
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur a déjà cliqué au moins 2 fois sur le bouton.
         *      Je mets '>=2' par intuition car je ne pense pas que '===2' aurait été judicieux.
         */
        $last = NULL;
        if ( is_array($ids) && count($ids) >= 2 ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        } 
        /*
         * ETAPE :
         *      On vérifie que l'idenfiant pivot correspond bien à au dernier dans dans hmt
         */
        else if ( is_array($ids) && count($ids) ) {
            $last = end(array_values($ids));
            if ( $last !== $this->KDIn["datas"]["ai"] ) {
//                var_dump(__LINE__,__FILE__,$last,$this->KDIn["datas"]["ai"]);
                $this->Ajax_Return("err","__ERR_VOL_FAILED");
            }
        } 
        
        /*
         * ICI : On suppose que l'utilisateur a déjà cliqué une fois sur le bouton.
         * Aussi $ids est un tableau contenant l'identifiant a traité. 
         */
        
        /*
         * ETAPE :
         *      On vérifie que l'article existe pour en récupérer la table
         */
        $ART = new ARTICLE_TR();
        $atab = $ART->child_exists($last, ["BA_ART_TO"]);
        if (! $atab ) {
            return TRUE; //On indique au script qu'il peut continuer son chemin normalement.
        } else if ( $atab === -1 ) {
//            var_dump(__LINE__,__FILE__,"CAS -1");
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur existe et on récupère la table.
         */
        $TR = new TREND();
        if (! $TR->exists($atab["trd_eid"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_TRD_GONE");
        }
        
        /*
         * ETAPE :
         *      On vérifie s'il y a des Articles plus bas
         */
//        qryl4pdaccn17neo0815001
        $QO = new QUERY("qryl4artn29_olytra");
        $params = array(
            ":ai"   => $atab["artid"],
            ":at"   => $atab["art_cdate_tstamp"],
            ":ti"   => $atab["trid"]
        );
        $datas = $QO->execute($params);
//        var_dump(__LINE__,__FUNCTION__,$datas);
//        exit();
       /*
        * [DEPUIS 11-11-2015] @author BOR 
        *      Pour des soucis de créer un sentiment d'exclusivité, on ne charge pas s'il n'y a un nombre d'Articles restant suffisant.
        *      Aussi, on lock s'il n' y a pas plus de 10 articles.
        */
        if ( !$datas || ( $datas && $datas[0]["cn"] <= 10 ) ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        } else {
            return TRUE; //On indique au script qu'il peut continuer son chemin normalement.
        }
        
    }
    
    
    private function GetArticles () {
        //* On créé le commentaire pour Article *//
        
        $this->DoesItComply_Datas();
        
        $TRD = new TREND();
        
        $datas = NULL;
        switch ($this->KDIn["datas"]["w"]) {
            case "new":
//                    $datas = $NWFD->GetNewerArticles($this->KDIn["oid"], $this->KDIn["datas"]["vw"], $this->KDIn["datas"]["ai"], $this->KDIn["datas"]["at"], $this->KDIn["datas"]["mn"]);
                    $datas = $TRD->on_read_get_filtered_articles_from($this->KDIn["datas"]["ai"],$this->KDIn["datas"]["at"],"TRART_FILTER_GET_NEW",$this->KDIn["datas"]["ti"]);
                    //Pour respecter l'ordre d'insertion au niveau de FE on va trier dans le sens inverse
                break;
            case "old":
//                    $datas = $NWFD->GetOlderArticles($this->KDIn["oid"], $this->KDIn["datas"]["vw"], $this->KDIn["datas"]["ai"], $this->KDIn["datas"]["at"], $this->KDIn["datas"]["mn"]);
                    $datas = $TRD->on_read_get_filtered_articles_from($this->KDIn["datas"]["ai"],$this->KDIn["datas"]["at"],"TRART_FILTER_GET_PREDATE",$this->KDIn["datas"]["ti"]);
                break;
            default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                break;
        }
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        }
        
        $hmti = $articles = NULL;
        if ( $datas && is_array($datas) && count($datas) ) {
//            var_dump(__LINE__,__FILE__,$datas);
//            exit();
            
            foreach ($datas as $k => $v) {
                $articles[$v["art_eid"]] = $this->PrepareArticle($v);
            }
    //        var_dump($articles);
            
            
            //Pour respecter l'ordre d'insertion au niveau de FE on va trier dans le sens inverse APRES que les Articles aient été préparés !!
            if ( $this->KDIn["datas"]["w"] === "new" ) {
                usort($articles, function($a, $b) {
                    return $a["art"]["time"] - $b["art"]["time"];
                });
            } else {
                
               /*
                * [DEPUIS 09-11-15] @author BOR
                *      On récupère l'article le plus ancien pour les besoins du traitement du cas de LOCK_BTM.
                */
                $last = end(array_values($articles));
//                var_dump(__LINE__,__FILE__,$last);
//                exit();
                
                $hmti = $last["art"]["id"];
            }
            
        }
        
        $this->KDOut["FE_DATAS"] = [
            "tds"   => $articles,
            "hmti"  => $hmti,
        ];        
        
    }
    
    
    private function PrepareArticle ( $art_tab ) {
        
        if (! (isset($art_tab) && is_array($art_tab) && count($art_tab) ) ) {
            return;
        }
        
        $ART = new ARTICLE();
        $me = NULL;
        //On détemine l'évaluation du Compte courant
        $EV = new EVALUATION();
        $E_E = $EV->exists(["actor" => $this->KDIn["oid"], "artid" => $art_tab["artid"]]);
        if ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) {
            $me = $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]);
        } else {
            $me = "";
        }
        
        $ustgs = $ART->onread_AcquiereUsertags_Article($art_tab["art_eid"],TRUE);
        
        $ivid = ( $art_tab["art_vid_url"] ) ? TRUE : FALSE;
        $prmlk = preg_replace('#^https?://#', '', WOS_MAIN_HTTP_HOST.$ART->onread_AcquierePrmlk($art_tab["art_eid"],$ivid));
        
       /*
        * ETAPE 
        *      Est ce que l'utilisateur connecté (le cas échéant) a AUSSI FAV ?
        *      On récupère le type de FAV, le cas échéant
        */
        if ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) {
            $cuftab = $ART->Favorite_hasFavorite($this->KDIn["oid"], $art_tab["art_eid"],TRUE);
            ( $cuftab ) ? $ART->Favorite_ConvertTypeID($cuftab["arfv_fvtid"]) : NULL;
        } else {
            $cuftab = NULL;
            $cuftp = NULL;
        }
        
        
        $PA = new PROD_ACC();
        $article = [
            /*
            "art" => [
                "id"        => $art_tab["art_eid"],
                "img"       => $art_tab["art_pdpic_path"],
                "time"      => $art_tab["art_creadate"],    
                "utc"       => "",    
                "msg"       => $art_tab["art_desc"],
//                "msg"       => html_entity_decode($art_tab["art_desc"]),
//                "msg"       => html_entity_decode(html_entity_decode($art_tab["art_desc"])),//[NOTE 26-04-15] @BOR A cause du fait qu'on utilise VM qui subbit une double HTMLENTITIES
                "ustgs"    => $ustgs,
                "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $art_tab["art_eid"]) ) ? TRUE : FALSE,
                "prmlk"     => $prmlk,
//                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($art_tab["art_eid"]), //[DEPUIS 21-09-15] @author BOR
                "trid"      => $art_tab["trd_eid"],
                "trtitle"   => $art_tab["trd_title"],
                "trhref"    => $art_tab["trd_href"],
                "rnb"       => $art_tab["art_rnb"],
                "eval"      => $art_tab["art_eval"],
                "eval_lt"   => NULL,
    //                    "eval_lt" => ['@SrvUser1','@SrvUser2','@SrvUser3',100],
                "myel"      => $me, //Choix : p2,p1,m1,0
               /*
                * [DEPUIS 21-04-15]
                *
                "hashs"     => $art_tab["art_list_hash"],
                "fvtp"      => $cuftp,
                "fvtm"      => NULL,
                "vidu"      => $art_tab["art_vid_url"],
                "isod"      => ( $art_tab["art_is_sod"] === 1 ) ? TRUE : FALSE,
            ],
            "user" => [
                "ueid"      => $art_tab["art_oeid"],
                "ufn"       => $art_tab["art_ofn"],
                "upsd"      => $art_tab["art_opsd"],
                "uppic"     => $PA->onread_acquiere_pp_datas($art_tab["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                "uppic"     => $art_tab["art_oppic"],
                "uhref"     => "/@".$art_tab["art_opsd"],
                "ucontb"    => NULL
            ]
            //*/
            "id"        => $art_tab["art_eid"],
            "img"       => $art_tab["art_pdpic_path"],
            "time"      => $art_tab["art_creadate"],    
            "utc"       => "",    
//            "msg"       => $art_tab["art_desc"],
//            "msg"       => html_entity_decode($art_tab["art_desc"]),
//            "msg"       => html_entity_decode(html_entity_decode($art_tab["art_desc"])),//[NOTE 26-04-15] @BOR A cause du fait qu'on utilise VM qui subbit une double HTMLENTITIES
            "msg"       => html_entity_decode($art_tab["art_desc"]), //[DEPUIS 22-04-16]
            "ustgs"     => $ustgs,
            "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $art_tab["art_eid"]) ) ? TRUE : FALSE,
            "prmlk"     => $prmlk,
//                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($art_tab["art_eid"]), //[DEPUIS 21-09-15] @author BOR
            
            "rnb"       => $art_tab["art_rnb"],
            "eval"      => $art_tab["art_eval"],
            "eval_lt"   => NULL,
//                    "eval_lt" => ['@SrvUser1','@SrvUser2','@SrvUser3',100],
            "myel"      => $me, //Choix : p2,p1,m1,0
           /*
            * [DEPUIS 21-04-15]
            */
            "hashs"     => $art_tab["art_list_hash"],
            "fvtp"      => $cuftp,
            "fvtm"      => NULL,
            "vidu"      => $art_tab["art_vid_url"],
            "isod"      => ( $art_tab["art_is_sod"] === 1 ) ? TRUE : FALSE,
           /*
            * [DEPUIS 29-03-16]
            */
            //Indique si l'ARTICLE doit être distribué en mode RESTRICTED
            "isrtd"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ? TRUE : FALSE,
            /********* DONNEES DE TENDANCE *********/
            "trd_eid"   => $art_tab["trd_eid"],
            "trtitle"   => $art_tab["trd_title"],
            "trhref"    => $art_tab["trd_href"],
            "istrd"     => TRUE,
            /***** PROPRIETAIRE ARTICLE *****/
            "ueid"      => $art_tab["art_oeid"],
            "ufn"       => $art_tab["art_ofn"],
            "upsd"      => $art_tab["art_opsd"],
            "uppic"     => $PA->onread_acquiere_pp_datas($art_tab["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                "uppic"     => $art_tab["art_oppic"],
            "uhref"     => "/".$art_tab["art_opsd"],
            "ucontb"    => NULL
        ];
            
        return $article;
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
        /*
         * ti   : L'Identifiant de la Tendance
         * ai   : L'identifiant de l'Article Pivot
         * at   : Le time de l'Article Pivot
         * hmt  : 
         * w    : La DIRECTION
         * cu   : L'URL founie par FE
         */
        $EXPTD = ["ti","ai","at","hmt","w","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_POST["datas"],'v_d');
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" && $v !== "''" ) && in_array($k,["hmt"]) )  {
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
        if ( $CXH->is_connected() ) {
//            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        
            //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->on_read_entity(["acc_eid" => $_SESSION["rsto_infos"]->getAcc_eid()]);

            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }

            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["oppic"] = $exists["pdacc_uppic"];

            $this->KDIn["datas"] = $in_datas;
        } else {
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["datas"] = $in_datas;
        }
    }

    public function on_process_in() {
        /*
         * [DEPUIS 09-11-15] @author BOR
         *      Utilisée en premier lieu dans le cas de WLC
         */
        if (! ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ) {
            $this->LockBtmCase();
        }
        
        $this->GetArticles();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        /*
         * [DEPUIS 13-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $this->KDIn["datas"]["w"] === "old" ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["ti"], 532, TRUE);
        } 
        exit();
    }
    
    protected function prepare_params_in_if_exist() { }
    
}

?>