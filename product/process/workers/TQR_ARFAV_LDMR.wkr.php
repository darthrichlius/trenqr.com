<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_ARFAV_LDMR extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            /*
            if ( in_array($k,[""]) && !( isset($v) && $v !== "" ) ) {
                continue;
            }
            //*/
            
            //Les données ont déjà été vérifiées
//            if (! ( isset($v) && $v !== "" ) ) {
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
//            }

             //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
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
        
    }
    
    
    private function LoadMore() {
        
        $this->DoesItComply_Datas();
        
        /*
         * ETAPE :
         *      Si WLC, on ne charge pas les ARTICLES plus anciens
         * [NOTE 29-04-15]
         *      C'est une solution de facilité et d'urgence. 
         *      L'idéal aurait été d'afficher un peu plus d'ARTICLES
         */
        if (! $this->KDIn["oid"] ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        }
        
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        if (! in_array($page,["TMLNR_GTPG_RO","TMLNR_GTPG_RU","TMLNR_GTPG_WLC"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
        }
        $psd = $this->KDIn["upieces"]["user"];
        
        $PA = new PROD_ACC();
        $tgtab = $PA->exists_with_psd($psd,TRUE);
        if (! $tgtab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        $ai = strtoupper($this->KDIn["datas"]["i"]);
        $at = strtoupper($this->KDIn["datas"]["t"]);
        $dir = strtoupper($this->KDIn["datas"]["dr"]);
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$ai,$at,$dir);
//        exit();
        
        $ART = new ARTICLE_TR();
        
        $FE = [];
        switch ($dir) {
            case "FST" :
            case "TOP" :
            case "BTM" :
                    /*
                    if ( key_exists("oid",$this->KDIn) && $this->KDIn["oid"] && intval($this->KDIn["oid"]) === intval($tgtab["pdaccid"]) ) {
                        $r = $ART->Favorite_GetFavArts($tgtab["pdaccid"], $dir, $ai, $at, NULL);
                    } else {
                        $r = $ART->Favorite_GetFavArts($tgtab["pdaccid"], $dir, $ai, $at, NULL, ["ONLY_PUB"]);
                    }
                    //*/
                    if ( key_exists("oid",$this->KDIn) && $this->KDIn["oid"] ) {
                        $r = ( intval($this->KDIn["oid"]) === intval($tgtab["pdaccid"]) ) ? 
                            $ART->Favorite_GetFavArts($tgtab["pdaccid"], $this->KDIn["oid"], $dir, $ai, $at, NULL)
                            : $ART->Favorite_GetFavArts($tgtab["pdaccid"], $this->KDIn["oid"], $dir, $ai, $at, NULL, ["ONLY_PUB"]);
                    } else {
                        $r = $ART->Favorite_GetFavArts($tgtab["pdaccid"], NULL, $dir, $ai, $at, NULL, ["ONLY_PUB"]);
                    }
            
                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                        $this->Ajax_Return("err",$r);
                    } 
                    $arts = $r;
                break;
            default:
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
        }
        
        if ( $arts && is_array($arts) ) {
            $TRD = new TREND();
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $arts) ) {
                $this->KDOut["pg_favs"] = NULL; 
            } else if ( $arts && is_array($arts) ) {
            
                $atab = $FADs = [];
                foreach ($arts as $article) {
                    //On détemine l'évaluation du Compte courant
                    $me = "";
                    if ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) {
                        $EV = new EVALUATION();
                        $E_E = $EV->exists(["actor" => $this->KDIn["oid"],"artid" => $article["artid"]]);
                        if ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) {
                            $me = $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]);
                        } else {
                            $me = "";
                        }
                        //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile
                    }


                    /*
                     * ETAPE : 
                     * On traite les UserTags
                     */
                    $ustgs = $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE);
                    
                    /*
                     * ETAPE :
                     *      On récupère les données sur FAVTAB en ce qui concerne OWNER
                     */
                    $ouftab = $ART->Favorite_hasFavorite($tgtab["pdaccid"],$article["art_eid"],TRUE);
                    $ouftp = ( $ouftab ) ? $ART->Favorite_ConvertTypeID($ouftab["arfv_fvtid"]) : NULL;
                    
                    /*
                     * ETAPE 
                     *      Est ce que l'utilisateur connecté (le cas échéant) a AUSSI FAV ?
                     *      On récupère le type de FAV, le cas échéant
                     */
                    /*
                    if ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) {
                        $cuftab = $ART->Favorite_hasFavorite($this->KDIn["oid"], $article["art_eid"],TRUE);
                        $cuftp = ( $cuftab ) ? $ART->Favorite_ConvertTypeID($cuftab["arfv_fvtid"]) : NULL;
                    } else {
                        $cuftab = NULL;
                        $cuftp = NULL;
                    }
                    //*/
                    
                    $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;

                    $atab = [
                        "id"        => $article["art_eid"],
                        "time"      => $article["art_creadate"],
                        "img"       => $article["art_pdpic_path"],
                        "msg"       => $article["art_desc"], 
                        "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid),
                        "eval"      => $article["art_eval"], /* [+2,+1,-1,total]*/
                        "myel"      => $me, //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                        "rnb"       => $article["art_rnb"],
                        "hashs"     => $article["art_list_hash"],
                        "ustgs"     => $ustgs, 
                        "fvtp"      => $ouftp,
                        "fvtm"      => $ouftab["arfv_startdate_tstamp"],
                        //QUESTION : Est-ce que l'utilisateur connecté à FAV cet ARTICLE
                        "hasfv"     => ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) ? $ART->Favorite_hasFavorite($this->KDIn["oid"], $article["art_eid"]) : FALSE,
                        
                        /*
                         * [DEPUIS 21-04-16]
                         */
                        "vidu"      => $article["art_vid_url"],
                        "isod"      => ( $article["art_is_sod"] === 1 ) ? TRUE : FALSE,
                        /******** OWNER DATAS ********/
                        "ueid"      => $article["art_oeid"],
                        "uppic"     => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], 
                        "ufn"       => $article["art_ofn"],
                        "upsd"      => $article["art_opsd"],
                        "uhref"     => "/".$article["art_opsd"],
                        "isrtd"     => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                        /*
                         * [NOTE 01-05-16]
                         *      Cette donnée sera modifiée dans le cas de TREND
                         */
                        "istrd"     => FALSE 
                    ];

                    if ( $article["trd_eid"] ) {
                        $atab["trd_eid"] = $article["trd_eid"];
                        $atab["trtitle"] = $article["trd_title"];
                        $atab["trhref"] = $TRD->on_read_build_trdhref($article["trd_eid"],$article["trd_title_href"]);
                        $trds = [
                            "trid"  => $atab["trd_eid"],
                            "trtle" => $atab["trtitle"],
                            "trhrf" => $atab["trhref"],
                        ];
                        $trds = (string)json_encode($trds);
                        $atab["trds"] = htmlspecialchars($trds, ENT_QUOTES, 'UTF-8');
                        $atab["istrd"] = TRUE;
                    }

                    $FADs[] = $atab;
                }

            } else {
                $FADs = NULL; 
            }
        
//            var_dump(__LINE__,__FUNCTION__,__FILE__,$FADs);
//            exit();
            
        }
        
        $FE_DATAS = [
            "ds"    => $FADs
        ];
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
        
    }

    /**************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "i"  : Identifiant
         * "t"  : Time
         * "dr" : Direction
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["i","t","dr","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,[""]) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }
        
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER(); 
        if ( $CXH->is_connected() ) {
            $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
    }
    
    public function on_process_in() {
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        $this->LoadMore();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

}

?>
