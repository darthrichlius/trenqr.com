<?php


class HVIEW extends MOTHER {
    
    private $HCTP;
    /*
     * La taille maximale autorisé pour traiter les hashtags dans certains cas.
     * 
     */
    private $hashMaxLn;
    private $SearchDftLmt;
    private $BlaBlaLmt;
    /*
     * [NOTE]
     *      Permet de renvoyer des données pertinente. 
     *      Si les gens clique et qu'il ne voit qu'un post ça fait très "cheap".
     *      Il sera toujours préférable d'avoir 5 HASH qui mène vers beaucoup de résultats que 10 vers 1 post.
     *          Cela porterait un coup grave à l'image de la plateforme.
     * 
     */
    private $BlaBlaRlvt;
    
    function __construct() {
        
        parent::__construct(__FILE__,__CLASS__);
        
        $this->HCTP = [
            "HCTP_ART_IML"      => 1,
            "HCTP_ART_ITR"      => 2,
            "HCTP_ART_REACT"    => 3,
            "HCTP_TESTY"        => 4,
            "HCTP_MI"           => 5,
            "HCTP_PDREACT"      => 6,
            "HCTP_MYSM"         => 7,
            "HCTP_ABME_INTRO"   => 8,
            "HCTP_ABME_WHYME"   => 9,
        ];
        
        /*******************************************************/
        $this->hashMaxLn = 50;
        $this->SearchDftLmt = 20;
        $this->BlaBlaLmt = 10;
        $this->BlaBlaRlvt = 10;
//        $this->BlaBlaRlvt = 1; //MODE : DEV, DEBUG, TEST
        
        /*
         * [DEPUIS 24-11-15] @author BOR
         */
        $this->default_dbname = ( defined("WOS_MAIN_HOST") && !in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) ? "tqr_product_vb1_prod" : "tqr_product_vb1";
    }

    public function HSH_exists ($eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$eid]);
        
        $QO = new QUERY("qryl4hviewn2");
        $params = array( ":eid" => $eid);  
        $datas = $QO->execute($params);

        return ( $datas ) ? $datas[0] : FALSE;
    }

    public function HSH_exists_with_id ($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$id]);
        
        $QO = new QUERY("qryl4hviewn1");
        $params = array( ":id" => $id);  
        $datas = $QO->execute($params);

        return ( $datas ) ? $datas[0] : FALSE;
    }

    public function HSH_exists_with_hsh ($hash, $_OPTS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$hash]);
        
        /*
         * ETAPE :
         *  On s'assure que le texte respecte les contraintes :
         *      TAILLE : Pas plus de MAX caractères
         *      FORMAT : minuscule et sans accent
         */ 
        if (! ( is_string($hash) && strlen($hash) <= $this->hashMaxLn ) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        $THX = new TEXTHANDLER();
        $hash = strtolower($THX->remove_accents($hash));
        
        $r = FALSE;
        if ( $_OPTS && in_array("GET_COUNT", $_OPTS) ) {
            $QO = new QUERY("qryl4hviewn3_cn");
            $params = array( ":hash" => $hash);  
            $datas = $QO->execute($params);
            $r = ( $datas ) ? $datas[0]["cn"] : 0;
        } else {
            $QO = new QUERY("qryl4hviewn3");
            $params = array( ":hash" => $hash);  
            $datas = $QO->execute($params);
            if ( $datas ) {
                $r = ( $_OPTS && in_array("GET_ALL", $_OPTS) ) ? $datas : $datas[0];
            } 
        }
        
        return $r;
    }
    
    public function HSH_oncreate ($t, $cid, $ceid, $ctp, $ssid, $locip, $curl = NULL, $uagnt = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$t,$cid,$ceid,$ctp,$ssid,$locip]);
        
        if (! in_array($ctp,array_keys($this->HCTP) ) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        $TXH = new TEXTHANDLER();
        $hs = $TXH->extract_prod_keywords($t);
        if (! $hs ) {
            return FALSE;
        }
        $hs = $hs[1];
        
//        var_dump(__LINE__,__FUNCTION__,$hs);
//        exit();
        
        $hshs = [];
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $OnlyOne = [];
        foreach ($hs as $hsh) {
            /*
             * REGLES
             *      (1) On n'ajoute pas deux fois de suite un mot-clé que l'on considère comme identique.
             *      (2) Deux mot-clés sont identiques si en minuscules il sont identiques. 
             *      (3) Les accents permettent de différencier deux mot-clés
             * 
             * EXEMPE :
             *      Hashtag (identique) hashtag
             *      Hâshtag (different) hashtag
             */
            $mini = strtolower($hsh);
            
            /*
             * ETAPE :
             *      On vérifie que l'élément n'est pas déjà ajouté
             */
            if ( $OnlyOne && in_array($mini,$OnlyOne) ) {
                continue;
            }
            $ori = $hsh;
            $THX = new TEXTHANDLER();
            $ntrhsh = strtolower($THX->remove_accents($hsh));
            
//            var_dump(__LINE__,$ori,$mini,$ntrhsh);
//            exit();
            
            /*
             * ETAPE :
             *      On crée l'occurrence.
             */
            $QO = new QUERY("qryl4hviewn4");
            $params = array(
                ":hcid"         => $cid,
                ":hceid"        => $ceid,
                ":hctp"         => $this->HCTP[$ctp],
                ":ntrhsh"       => $ntrhsh,
                ":mnihsh"       => $mini,
                ":gvnhsh"       => $ori,
                ":ssid"         => $ssid,
                ":curl"         => $curl,
                ":locip"        => $locip,
                ":uagnt"        => $uagnt,
                ":date"         => $date,
                ":tstamp"       => $time,
            );  
            $id = $QO->execute($params);  
            
//            var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$id);
//            exit();
        
            /*
             * ETAPE :
             *      On crée l'identifiant externe.
             */
            $eid = $this->entity_ieid_encode($time, $id);
            
            $QO = new QUERY("qryl4hviewn5");
            $params = array(":id" => $id, ":eid" => $eid);  
            $QO->execute($params);
            
            //On ajoute l'élément dans un tableau pour éviter les doublons
            $OnlyOne[] = $mini;
            
            $hshs[] = [$id,$eid,$ntrhsh,$mini,$ori];
        }
        
//        var_dump("CHECKPOINT => ",__LINE__,$hshs);
        
        return $hshs;
    }
    
    /*********************************************** ONVISIT SCOPE ***********************************************/ 
    
    //TODO
    public function HSH_onvisit_declare ($uicid, $auid = NULL, $referer = NULL, $ssid = NULL, $locip = NULL, $curl = NULL, $uagnt = NULL) {
        //auid : L'identifiant interne de l'utilsateur qui a effectué l'action s'il existe.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uicid]);
        
        /*
         * [NOTE]
         *      Pour des raisons pratiques et de performance, on n'effectue que très peu de vérification. 
         *      On effet, il faut répondre le plus rapidement à CALLER.
         *      Si l'ACTOR et/ou l'URL n'existe pas, l'opération renverra NULL et c'est tout.
         */
        
        /*
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4hviewn6");
        $params = array(
            ":uicid"    => $uicid,
            ":auid"     => $auid,
            ":refr"     => $referer,
            ":ssid"     => $ssid,
            ":curl"     => $curl,
            ":locip"    => $locip,
            ":uagnt"    => $uagnt,
            ":date"     => $date,
            ":tstamp"   => $time,
        );  
        $id = $QO->execute($params);  
        
        return $id;
        //*/
    }
    
    /*********************************************** HVIEW MODO DESC SCOPE ***********************************************/ 
    
    public function HSH_MODO_DESC ($hash,$lang) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$hash,$lang]);
        
        /*
         * ETAPE :
         *  On s'assure que le texte respecte les contraintes :
         *      TAILLE : Pas plus de MAX caractères
         *      FORMAT : minuscule et sans accent
         */ 
        if (! ( is_string($hash) && strlen($hash) <= $this->hashMaxLn ) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        $THX = new TEXTHANDLER();
        $hash = strtolower($THX->remove_accents($hash));
        
        $QO = new QUERY("qryl4hviewn15");
        $params = array( ":hash" => $hash);  
        $datas = $QO->execute($params);
        
        $d = FALSE;
        if ( $datas ) {
            $datas = $datas[0];
            if ( $datas["hmdsc_isEna"] === 1 ) {
                $dc = $datas["hmdsc_dctxid"];
                $t__ = $THX->get_deco_text($lang,$dc);
                if (! ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__) || !$t__ ) ) {
                    $d = $t__;
                }
            }
        }
            
        return $d;
    }
    
    /*********************************************** HVIEW BLABLABLABLA SCOPE ***********************************************/ 
    
    
    public function HSH_BLABLA ($cuid, $lmt = NULL) {
        // cuid : L'identifiant interne de l'utilisateur. (Servira pour affiner les recherches plus tard)
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid]);
        
        
        /*
         * ETAPE :
         *      On s'assure d'avoir une donnée LIMIT
         */
        $limit = (! $lmt ) ? $this->BlaBlaLmt : $lmt;
        
        /*
         * ETAPE :
         *      On récupère les données LIVE (ce qui se passe en ce moment)
         */
        $live_datas = [];
        $QO = new QUERY("qryl4hviewn16_live");
        $params = array( 
            ":rlvt"     => $this->BlaBlaRlvt, 
            ":limit"    => $limit 
        );  
        $l_datas = $QO->execute($params);
        if ( $l_datas ) {
            foreach ($l_datas as $r) {
                $live_datas[] = $r["hash"];
            }
        }
        
        /*
         * ETAPE :
         *      On récupère les données BESTOF (ce qui a et qui continue de faire rage)
         */
        $bsof_datas = [];
        $QO = new QUERY("qryl4hviewn16_bestof");
        $params = array( 
            ":rlvt"     => $this->BlaBlaRlvt, 
            ":limit"    => $limit 
        );  
        $b_datas = $QO->execute($params);
        if ( $b_datas ) {
            foreach ($b_datas as $r) {
                $bsof_datas[] = $r["hash"];
            }
        }
        
        $final = [
            "live" => $live_datas,
            "bsofh" => $bsof_datas,
            "bsofp" => $bsof_datas
        ];
        
        return $final;
        
    }
    
    /*********************************************** HVIEW SEARCH SCOPE ***********************************************/ 
    
    public function Search($hash, $dr = NULL, $cuid = NULL, $pvi = NULL, $pvt = NULL, $lmt = NULL) {
        //dr: DiRection; cuid : L'identifiant interne de l'utilisateur connecté (le cas échéant); pvi : PiVotId; pvt : PiVotTime
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$hash]);
        
        /*
         * ETAPE :
         *      On s'assure d'avoir une donnée LIMIT
         */
        $limit = (! $lmt ) ? $this->SearchDftLmt : $lmt;
    
        /*
         * ETAPE :
         *      On traite le cas de la direction
         */
        if ( $dr && !in_array($dr,["FST","TOP","BTM"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        $dir = (! $dr ) ? "FST" : $dr;
        
        /*
         * ETAPE :  
         *      On fait de la chaine de recherche une chaine neutre pour considérer tout autant les mots apparentés sans accent.
         */
        if (! ( is_string($hash) && strlen($hash) <= $this->hashMaxLn ) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        $THX = new TEXTHANDLER();
        $hash = strtolower($THX->remove_accents($hash));
        
        /*
         * ETAPE :
         *      On récupère les données (Les occurences HASH enregistrées, contenues dans un CONTENT) en fonction du hashtag passé.
         * REGLE :
         *      (1) La recherche s'effectue avec un hashtag sans accent et en minuscule
         *      (2) On récupère les données par ordre décroissant
         *      (3) On récupère les données indifféremment de leur type.
         */
        
        if ( $pvt && $pvi && $dir === "TOP" ) {
//            $QO = new QUERY("qryl4hviewn7"); //[DEPUIS 13-07-16]
//            $QO = new QUERY("qryl4hviewn7_v2"); //[DEPUIS 17-07-16]
            $QO = new QUERY("qryl4hviewn7_v3");
            $params = array( 
                ":hash"     => $hash,
                ":cuid"     => $cuid,
                ":cuid1"    => $cuid,
                ":cuid2"    => $cuid,
                ":cuid3"    => $cuid,
                ":cuid4"    => $cuid,
                ":pvid"     => $pvi,
                ":pvtm"     => $pvt,
                ":limit"    => $limit,
            );  
        } else if ( $pvt && $pvi && $dir === "BTM" ) {
//            $QO = new QUERY("qryl4hviewn8"); //[DEPUIS 13-07-16]
//            $QO = new QUERY("qryl4hviewn8_v2"); //[DEPUIS 17-07-16]
            $QO = new QUERY("qryl4hviewn8_v3");
            $params = array( 
                ":hash"     => $hash,
                ":cuid"     => $cuid,
                ":cuid1"    => $cuid,
                ":cuid2"    => $cuid,
                ":cuid3"    => $cuid,
                ":cuid4"    => $cuid,
                ":pvid"     => $pvi,
                ":pvtm"     => $pvt,
                ":limit"    => $limit,
            );  
        } else {
//            $QO = new QUERY("qryl4hviewn6"); //[DEPUIS 13-07-16]
//            $QO = new QUERY("qryl4hviewn6_v2"); //[DEPUIS 17-07-16]
            $QO = new QUERY("qryl4hviewn6_v3");
            $params = array( 
                ":hash"     => $hash,
                ":cuid"     => $cuid,
                ":cuid1"    => $cuid,
                ":cuid2"    => $cuid,
                ":cuid3"    => $cuid,
                ":cuid4"    => $cuid,
                ":limit"    => $limit,
            ); 
             
        }
        $datas = $QO->execute($params);
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$datas);
//        exit();
        
        $final = [];
        if (! $datas ) {
            return $final;
        }
        
        /*
         * ETAPE :
         *      On crée un tableau regroupant les identifiants par type
         */
        $a = $final_guide =[];
        foreach ($datas as $set) {
            $case = $set["type"]; $type;
            if ( $case === 1 ) {
                $a["HCTP_ART_IML"][] = $set["cnid"];
                $type = "AIML";
            } else if ( $case === 2 ) {
                $a["HCTP_ART_ITR"][] = $set["cnid"];
                $type = "AITR";
            } else if ( $case === 4 ) {
                $a["HCTP_TESTY"][] = $set["cnid"];
                $type = "TST";
            } else {
                continue;
            }
            
            /*
             * ETAPE :
             *      On créé notre tableau qui servira de guide pour l'insertion des données au niveau de FE.
             */
            $final_guide_tmp[] = [
                'hid'   => $set["hid"],
                'cnid'  => $set["cneid"],
                'time'  => $set["time"],
                'type'  => $type
            ];
        }
        $b = array_unique(array_column($datas,"type"));
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$a,$b,$final_guide_tmp);
//        exit();
        
//        return $a;
//        exit();
        
        $PA = new PROD_ACC();
        $ART = new ARTICLE();
        $TRD = new TREND();
        $TST = new TESTY();
        
        $final_contents = $got_ids = [];
        foreach ($a as $k => $v) {
            $case = $k;
            switch ($case) {
                case "HCTP_ART_IML" :
                    if ( $cuid ) {
                        $ids = implode("','",$v);
//                        var_dump(__LINE__,__FUNCTION__,__FILE__,$ids);
//                        exit();
                        
                        /*
                         * [NOTE ]
                         *      Version Build Volatile de : "qryl4hviewn9"
                         */
                        $QO = new QUERY();
                        $qbody = " SELECT * ";
                        $qbody .= " FROM VM_ARTICLES_IML ";
                        $qbody .= " WHERE artid IN ('".$ids."') ";
                        $qbody .= " AND  ";
                        $qbody .= " ( ";
                        $qbody .= " art_oid = :cuid ";
                        $qbody .= " OR art_is_sod = 1 ";
                        $qbody .= " OR ";
                        $qbody .= " ( ";
                        $qbody .= " :cuid1 IS NOT NULL AND art_oid IN ";
                        $qbody .= " ( ";
                        $qbody .= " SELECT ( ";
                        $qbody .= " CASE ";
                        $qbody .= " WHEN tbrel_acc_actor = :cuid2 THEN tbrel_acc_targ ";
                        $qbody .= " ELSE tbrel_acc_actor ";
                        $qbody .= " END ";
                        $qbody .= " ) as uid ";
                        $qbody .= " FROM VM_Tabrels ";
                        $qbody .= " INNER JOIN Proddb_Accounts PA_A ON PA_A.pdaccid = tbrel_acc_actor ";
                        $qbody .= " INNER JOIN Proddb_Accounts PA_T ON PA_T.pdaccid = tbrel_acc_targ ";
                        $qbody .= " WHERE ( tbrel_relsts = 3 AND ( tbrel_acc_actor = :cuid3 OR tbrel_acc_targ = :cuid4 ) ) ";
                        $qbody .= " AND tbrel_edate_tstamp IS NULL ";
                        $qbody .= " AND PA_A.pdacc_todelete = 0 ";
                        $qbody .= " AND PA_T.pdacc_todelete = 0 ";
                        $qbody .= " ) ";
                        $qbody .= " ) ";
                        $qbody .= " ) ";
                        $qbody .= " AND art_is_hstd = 0 ";
                        $qbody .= " AND ( art_state IS NULL OR art_state IN (1,6) ); ";
                        
                        $qdbname = $this->default_dbname;
                        $qtype = "get";
                        $qparams_in = array(
                            ":cuid"     => $cuid,
                            ":cuid1"    => $cuid,
                            ":cuid2"    => $cuid,
                            ":cuid3"    => $cuid,
                            ":cuid4"    => $cuid,
                        );
                        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                        $datas = $QO->execute($qparams_in); 
                        
//                        var_dump(__LINE__,__FUNCTION__,__FILE__,$ids,$datas);
//                        exit();
                        if ( $datas ) {
                            foreach ( $datas as $a_tab ) {
                                 
                               /*
                                * ETAPE 
                                *      Est ce que l'utilisateur connecté (le cas échéant) a AUSSI FAV ?
                                *      On récupère le type de FAV, le cas échéant
                                */
                                if ( $cuid ) {
                                    $cuftab = $ART->Favorite_hasFavorite($cuid, $a_tab["art_eid"],TRUE);
                                    $cuftp = ( $cuftab ) ? $ART->Favorite_ConvertTypeID($cuftab["arfv_fvtid"]) : NULL;
                                } else {
                                    $cuftp = NULL;
                                }
                                
                                $ivid = ( $atab["art_vid_url"] ) ? TRUE : FALSE;
                                
                                /*
                                 * ETAPE :
                                 *      On créé la table des données
                                 */
                                $final_contents["AIML"][$a_tab["art_eid"]] = [
                                    "cmnid"     => $a_tab["art_eid"],
                                    "aid"       => $a_tab["art_eid"],
                                    "apic"      => $a_tab["art_pic_rpath"], 
                                    "adesc"     => $a_tab["art_desc"], 
//                                    "adesc"     => htmlentities($a_tab["art_desc"]), 
                                    "atime"     => $a_tab["art_crea_tstamp"],
                                    "aprmlk"    => "//".$_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($a_tab["art_eid"],$ivid),
                                    "ustgs"     => $ART->onread_AcquiereUsertags_Article($a_tab["art_eid"],TRUE),
                                    "hashs"     => explode(",", $a_tab["art_hashs"]),
                                    "areacts"   => NULL,
                                    "aevals"    => explode(",", $a_tab["art_evals"]),
                                    "arnb"      => $a_tab["art_rnb"],
                                        
                                    /*
                                     * [DEPUIS 22-04-16]
                                     */
                                    "hasfv"     => ( $cuftab ) ? TRUE : FALSE ,
                                    "fvtp"      => $cuftp,
                                    "fvtm"      => NULL,
                                    "vidu"      => $a_tab["art_vid_url"],
                                    "isod"      => ( $a_tab["art_is_sod"] && intval($a_tab["art_is_sod"]) === 1 ) ? TRUE : FALSE,
                                    
                                    /* DONNEES SUR LE PROPRIETAIRE */
                                    "aoid"      => $a_tab["art_oeid"],
                                    "aofn"      => $a_tab["art_ofn"],
                                    "aopsd"     => $a_tab["art_opsd"],
                                    "aohref"    => "/".$a_tab["art_opsd"],
                                    "aoppic"    => $a_tab["art_oppic_rpath"],
                                    
                                ];
                                
                            }
                        }
                    }
                    break;
                case "HCTP_ART_ITR" :
                        $ids = implode("','",$v);
                       /*
                        * [NOTE ]
                        *      Version Build Volatile de : "qryl4hviewn10"
                        */
                        $QO = new QUERY();
                        $qbody = " SELECT * ";
                        $qbody .= " FROM VM_ARTICLES_ITR ";
                        $qbody .= " WHERE artid IN ('".$ids."') ";
                        $qbody .= " AND ( art_state IS NULL OR art_state IN (1,6) ); ";
                        $qdbname = $this->default_dbname;
                        $qtype = "get";
                        $qparams_in = array();
                        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                        $datas = $QO->execute(NULL); 
                        
//                        var_dump(__LINE__,__FUNCTION__,__FILE__,$ids,$datas);
//                        exit();
                        if ( $datas ) {
                            foreach ( $datas as $a_tab ) {
                                /*
                                 * ETAPE :
                                 *      On récupère les données à jour sur la Tendance.
                                 */
                                $TRD = new TREND();
                                $tr_infos = $TRD->trend_get_trend_infos($datas[0]["art_trd_eid"]);
                                if (! $tr_infos) {
                                    continue;
                                }
                                $title = $tr_infos["trd_title"];
                                $tle_hrf = $tr_infos["trd_title_href"];
                                
                               /*
                                * ETAPE 
                                *      Est ce que l'utilisateur connecté (le cas échéant) a AUSSI FAV ?
                                *      On récupère le type de FAV, le cas échéant
                                */
                                if ( $cuid ) {
                                    $cuftab = $ART->Favorite_hasFavorite($cuid, $a_tab["art_eid"],TRUE);
                                    $cuftp = ( $cuftab ) ? $ART->Favorite_ConvertTypeID($cuftab["arfv_fvtid"]) : NULL;
                                } else {
                                    $cuftp = NULL;
                                }
                                
                                $ivid = ( $atab["art_vid_url"] ) ? TRUE : FALSE;
                                
                                /*
                                 * ETAPE :
                                 *      On créé la table des données
                                 */
                                $final_contents["AITR"][$a_tab["art_eid"]] = [
                                    "cmnid"     => $a_tab["art_eid"],
                                    "aid"       => $a_tab["art_eid"],
                                    "apic"      => $a_tab["art_pic_rpath"], 
                                    "adesc"     => $a_tab["art_desc"], 
//                                    "adesc"     => htmlentities($a_tab["art_desc"]), 
                                    "atime"     => $a_tab["art_crea_tstamp"],
                                    "aprmlk"    => "//".$_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($a_tab["art_eid"],$ivid),
                                    "ustgs"     => $ART->onread_AcquiereUsertags_Article($a_tab["art_eid"],TRUE),
                                    "hashs"     => explode(",", $a_tab["art_hashs"]),
                                    "areacts"   => NULL,
                                    "aevals"    => explode(",", $a_tab["art_evals"]),
                                    "arnb"      => $a_tab["art_rnb"],
                                    
                                    /*
                                     * [DEPUIS 22-04-16]
                                     */
                                    "hasfv"         => ( $cuftab ) ? TRUE : FALSE ,
                                    "fvtp"          => $cuftp,
                                    "fvtm"          => NULL,
                                    "vidu"          => $a_tab["art_vid_url"],
                                    "isod"          => ( $a_tab["art_is_sod"] && intval($a_tab["art_is_sod"]) === 1 ) ? TRUE : FALSE,
                                    
                                    /* DONNEES SUR LE PROPRIETAIRE */
                                    "aoid"      => $a_tab["art_oeid"],
                                    "aofn"      => $a_tab["art_ofn"],
                                    "aopsd"     => $a_tab["art_opsd"],
                                    "aohref"    => "/".$a_tab["art_opsd"],
                                    "aoppic"    => $a_tab["art_oppic_rpath"],
                                    /* DONNEES SUR LA TENDANCE */
                                    "teid"      => $a_tab["art_trd_eid"],
                                    "ttle"      => $title,
                                    "ttle_href" => $tle_hrf,
                                    "thref"     => $TRD->on_read_build_trdhref($a_tab["art_trd_eid"], $tle_hrf)
                                ];
                                
                            }
                        }
                    break;
                case "HCTP_TESTY" :
                        $ids = implode("','",$v);
                        
                       /*
                        * [NOTE ]
                        *      Version Build Volatile de : "qryl4hviewn11"
                        */
                        $QO = new QUERY();
                        $qbody = " SELECT TST.*, PA1.pdacc_eid oueid, PA1.pdaccid ouid, PA1.pdacc_upsd oupsd, PA1.pdacc_ufn oufn, PA2.pdaccid tguid, PA2.pdacc_eid tgueid, PA2.pdacc_upsd tgupsd, PA2.pdacc_ufn tgufn ";
                        $qbody .= " FROM TESTIES TST, PRODDB_ACCOUNTS PA1, PRODDB_ACCOUNTS PA2 ";
                        $qbody .= " WHERE tst_ouid = PA1.pdaccid ";
                        $qbody .= " AND tst_tguid = PA2.pdaccid ";
                        $qbody .= " AND tstid IN ('".$ids."') ";
                        $qbody .= " AND ( ";
                        $qbody .= " tst_ouid = :cuid ";
                        $qbody .= " OR tst_ouid IN ( "; 
                        $qbody .= " SELECT tst_cnf_uid ";
                        $qbody .= " FROM TESTY_CONF ";
                        $qbody .= " WHERE tst_cnf_wcsee = 7 ";
                        $qbody .= " AND :cuid1 IS NOT NULL ";
                        $qbody .= " )  ";
                        $qbody .= " OR tst_ouid IN (  ";
                        $qbody .= " SELECT tst_cnf_uid ";
                        $qbody .= " FROM TESTY_CONF ";
                        $qbody .= " WHERE tst_cnf_wcsee = 1 ";
                        $qbody .= " ) ";
                        $qbody .= " ); ";
                        
                        $qdbname = $this->default_dbname;
                        $qtype = "get";
                        $qparams_in = array(
                            ":cuid"     => $cuid,
                            ":cuid1"    => $cuid,
                        );
                        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
                        $datas = $QO->execute($qparams_in);
//                        var_dump(__LINE__,__FUNCTION__,__FILE__,$ids,$datas);
//                        exit();
                        if ( $datas ) {
                            foreach ( $datas as $tstdom ) {
                                /*
                                 * ETAPE :
                                 *      On créé la table des données
                                 */
                                $final_contents["TST"][$tstdom["tst_eid"]] = [
                                    "cmnid"     => $tstdom["tst_eid"],
                                    "i"         => $tstdom["tst_eid"],
                                    "tm"        => $tstdom["tst_adddate_tstamp"],
                                    "m"         => $tstdom["tst_msg"],
                                    
                                    //Données sur l'OWNER
                                    "au" => [
                                        "oid"       => $tstdom["oueid"],
                                        "ofn"       => $tstdom["oufn"],
                                        "opsd"      => $tstdom["oupsd"],
                                        "oppic"     => $PA->onread_acquiere_pp_datas($tstdom["ouid"])["pic_rpath"],
                                        "ohref"     => "/".$tstdom["oupsd"],
                                    ],
                                    //Données sur la TARGET
                                    "tg" => [
                                        "oid"       => $tstdom["tgueid"],
                                        "ofn"       => $tstdom["tgufn"],
                                        "opsd"      => $tstdom["tgupsd"],
                                        "oppic"     => $PA->onread_acquiere_pp_datas($tstdom["tguid"])["pic_rpath"],
                                        "ohref"     => "/".$tstdom["tgupsd"]
                                    ],
                                    
                                    //USERTAGS
                                    "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tstdom["tst_eid"],TRUE),
                                    
                                    /*
                                     * [DEPUIS 22-04-16]
                                     *      Je ne n'ajoute pas de prefixe aux INDEX car j'aimerais ne plus reproduire ce procédé car cela ne permet pas de lutter contre DEPENDENCY de manière efficace
                                     */
                                    //*
                                    "hashs"     => $TST->onread_AcquiereHashs_Testy($tstdom["tst_eid"]),
                                    "cdl"       => ( floatval($cuid) === floatval($tstdom["ouid"]) | floatval($cuid) === floatval($tstdom["tguid"]) ) ? TRUE : FALSE,
                                    // QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                                    //cgap : CanGetAccesstoPin
                                    "cgap"      => ( $cuid && ( intval($cuid) === intval($tstdom["tguid"]) || intval($cuid) === intval($tstdom["tguid"]) ) ) ? TRUE : FALSE,
                                    //QUESTION ? Le TESTIMONY est-il PIN ?
                                    "isp"       => $TST->Pin_IsPin($tstdom["tstid"]),
                                    "rnb"       => $TST->React_Count($tstdom["tst_eid"]),
                                    //QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                                    //NOTE : On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                                    "clk"       => ( $cuid ) ? TRUE : FALSE,
                                    //QUESTION ? L'utilisateur a t-il LIKE ?
                                    "hslk"      => ( $cuid && $TST->Like_HasLiked($cuid, $tstdom["tstid"]) ) ? TRUE : FALSE,
                                    //QUESTION ? Le nombre de LIKE ?
                                    "cnlk"      => $TST->Like_Count($tstdom["tst_eid"]),
                                    //*/
                                    /*
                                     * [DEPUIS 31-05-16]
                                     *      On récupère le lien permanent de TSM
                                     */
                                    "prmlk"     => $_SERVER["HTTP_HOST"].$TST->onread_AcquierePrmlk($tstdom["tst_eid"]),
                                ];
                            }
                        }
                    break;
                default:
                    continue;
            }
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__, $final_guide_tmp,$final_contents);
//        var_dump(__LINE__,__FUNCTION__,__FILE__, $final_contents);
//        var_dump(__LINE__,__FUNCTION__,__FILE__, array_column($final_guide_tmp,"cnid"));
//        var_dump(__LINE__,__FUNCTION__,__FILE__, array_column($final_contents["AIML"],"cmnid"),array_column($final_contents["AITR"],"cmnid"),array_column($final_contents["TST"],"cmnid"));
//        var_dump(__LINE__,__FUNCTION__,__FILE__, $final_guide_tmp, $got_ids);
//        exit();
        
        /*
         * [DEPUIS 23-11-15]
         *      On s'assure qu'on a bien les CONTENT correspond aux ids dans GUIDE
         */
        $final_guide = [];
        if ( $final_contents ) {
            foreach ($final_guide_tmp as &$gds) {
//                var_dump(__LINE__,__FUNCTION__,__FILE__, [$gds["cnid"],array_column($final_contents[$gds["type"]],"cmnid")]);
                if ( key_exists($gds["type"],$final_contents) && in_array($gds["cnid"], array_column($final_contents[$gds["type"]],"cmnid")) ) {
                    $final_guide[] = [
                        'hid'   => $gds["hid"],
                        'cnid'  => $gds["cnid"],
                        'time'  => $gds["time"],
                        'type'  => $gds["type"]
                    ];
                }
            }
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__, $final_guide);
//        var_dump(__LINE__,__FUNCTION__,__FILE__, $a, $b, $final_guide, $final_contents);
//        exit();
        
        return [
            //g : Guide
            "g" => ( $final_guide ) ? $final_guide : NULL,
            //c : Contents
            "c" => ( $final_contents ) ? $final_contents : NULL
        ];
    }
    
    
    /*********************************************************************************************************************************************************/
    /************************************************************************ SPECIAL TRANSFERT ************************************************************************/
    
    public function SPE_TRANSFERT_HSH () {
        /*
         * [NOTE 17-11-15]
         *      Cette méthode est utilisée pour le transfert des données HASHTAG de l'ancienne table vers la nouvelle.
         *      L'ancienne ne permettait que peut de liberté et était peu générique. 
         *      De plus, le nouveau mécanisme permet de faire fonctionner le système HVIEW !
         */
        
        $QO = new QUERY("qryl4hviewn13_spe_art_transf");
        $datas = $QO->execute(NULL);
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$datas);
//        exit();
        
        if ( $datas ) {
            
            foreach ($datas as $htab) {
                $QO = new QUERY("qryl4hviewn4");
                $params = array(
                    ":hcid"         => $htab["hcid"],
                    ":hceid"        => $htab["hceid"],
                    ":ntrhsh"       => $htab["ntrhsh"],
                    ":mnihsh"       => $htab["mnihsh"],
                    ":gvnhsh"       => $htab["gvnhsh"],
                    ":ssid"         => $htab["ssid"],
                    ":curl"         => $htab["curl"],
                    ":locip"        => $htab["locip"],
                    ":uagnt"        => $htab["uagnt"],
                    ":date"         => $htab["date"],
                    ":tstamp"       => $htab["tstamp"],
                    ":hctp"         => $htab["hctp"]
                );  
                
                $id = $QO->execute($params);  
                
                /*
                 * ETAPE :
                 *      On crée l'identifiant externe.
                 *      On le crée avec l'élément TIMESTAMP actuel pour réduire la possibilité qu'il y ait un doublon.
                 *      En effet, en temps normal, on aurait utiliser le TIMESTAM du CONTENT. Mais comme il s'agit d'un TRANSFERT il faut bidouiller.
                 */
                $time = round(microtime(TRUE)*1000);
                $eid = $this->entity_ieid_encode($time, $id);
                
//                var_dump(__LINE__,__FUNCTION__,__FILE__,$eid);

                $QO = new QUERY("qryl4hviewn5");
                $params = array(":id" => $id, ":eid" => $eid);  
                $QO->execute($params);
            }
            
        }
        
        return $datas;
    }
}

?>