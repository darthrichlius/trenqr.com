<?php

/*
 * Permet de récupérer les tables des Tendances appartenant à OWN et celles qu'il suit.
 * Cette version du Worker TPTF, permet entre autre de renvoyer les identifiants des Articles FIRST pour chaque Tendance que l'on récupérera.
 * 
 * SPECIFICITES :
 *  -> CALLER envoie la donnée 'dir' qui nous indique s'il faut récupérer les données anterieures ou ulterieures à la Tendance pivot.
 *  -> CALLER envoie le nombre Articles qu'il souhaite pour FIRST. Si la valeur pour le nombre est égale à 0, on ne renvoie pas d'identifiant d'Article.
 * 
 * NOTE : A ce stade, nous ne traitons pas le cas FIRST. Mais de légères modifications pourraient permettre de traiter ce cas.
 * Pour cela, il faudrait qu'il n'y ait aucune donnée disponible en ce qui concerne le pivot.
 */
class WORKER_TPTF_WAIO extends WORKER  {
    
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
            /*
            $SKIP = ["mtrs","sbtrs"];
            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //*/
            
            //On vérifie la conformité pour "mtrs" (MyTrends) et sbtrs (SubscriptionTrends)
            if ( $k === "mtrs" | $k === "sbtrs" ) {
                if ( !isset($v) || $v === "" ) {
                    continue;
                } else if (! is_array($v) ) {
//                    var_dump(__LINE__,$v);
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                } else if (! ( key_exists("i",$v) && key_exists("t",$v) ) ) {
//                    var_dump(__LINE__,$v);
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                } else if ( !$v["i"] | !$v["t"] ) {
//                    var_dump(__LINE__,$v);
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                } else if ( !( $v["i"] && is_string($v["i"]) && !empty($v["i"]) ) | !( is_numeric($v["t"]) && (float)$v["t"] == $v["t"] && floatval($v["t"]) > 1388534400000 && floatval($v["t"]) < 4102444800000 ) ) { //01/01/14 à 01/01/2100
//                    var_dump(__LINE__,$v);
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                }
                
                //On ne vérifie que 'i', car c'est le seul qui est de type String, donc qui peut provoquer un problème si CALLER envoie qu'elle que chose que l'on peut interpréter comme "rien" !
                $v = $v["i"];
            }
            
            
            //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
            $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
            $rbody = $v;
//            $rbody = $this->KDIn["datas"][$k]; //[NOTE 08-03-15] @BlackOwlRobot Peut mieux faire. De plus, cela compliquerait les traitements ci-dessus. 

            preg_match_all("/(\n)/", $rbody, $m_c1);
            preg_match_all("/(\r)/", $rbody, $m_c2);
            preg_match_all("/(\r\n)/", $rbody, $m_c3);
            preg_match_all("/(\t)/", $rbody, $m_c4);
            preg_match_all("/(\s)/", $rbody, $m_c5);

            //Parano : Je sais que j'aurais pu ne mettre que \s
//            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) ) {
            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
//                var_dump(__LINE__,$v);
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            $istr = ["dir","curl"];
            if ( !empty($v) && in_array($k, $istr) && !is_string($v) ) {
//                var_dump(__LINE__,$v);
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
            //On vérifie la conformité de la donnée qui indique la direction
            if ( $k === "dr" && !in_array($v, ["b","t"]) ) {
//                var_dump(__LINE__,$v);
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
        }
        
    }
    
    
    private function GetTrends () {
        //* On créé le commentaire pour Article *//
        
        $this->DoesItComply_Datas();
        
        $ouid = $this->KDIn["target"]["pdaccid"];
        //On vérifie que OWNER existe et son compte est actif
        $PA = new PROD_ACC();
        if (! $PA->exists_with_id($ouid,TRUE) ) {
            $this->Ajax_Return("err","__ERR_VOL_OWNER_GONE");
        }
        
        $dir = ( $this->KDIn["datas"]["dr"] === "b" ) ? "bottom" : "top";
        
        $TRD = new TREND();
        $TD__ = $mtrs = $sbtrs = [];
        
        /*
         * ETAPE :
         * On récupère les Tendances appartenant à l'utilisateur dont l'identifiant est passé en paramètre selon la direction et la Tendance pivot.
         */
        if ( key_exists("mtrs", $this->KDIn["datas"]) && isset($this->KDIn["datas"]["mtrs"]) && is_array($this->KDIn["datas"]["mtrs"]) ) {
            $TD__ = $mtrs = $TRD->onread_pull_mytrends_from($ouid, $this->KDIn["datas"]["mtrs"]["i"], $this->KDIn["datas"]["mtrs"]["t"], $dir, TRUE);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $mtrs) ) {
                $this->Ajax_Return("err",$mtrs);
            }
        } else if ( key_exists("mtrs", $this->KDIn["datas"]) && empty($this->KDIn["datas"]["mtrs"]) ) {
            $TD__ = $mtrs = $PA->onread_acquiere_my_trends_datas($ouid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $mtrs) ) {
                $this->Ajax_Return("err",$mtrs);
            }
        }
        
        /*
         * ETAPE :
         * On récupère les Tendances suivies par l'utilisateur dont l'identifiant est passé en paramètre selon la direction et la Tendance pivot.
         */
        if ( key_exists("sbtrs", $this->KDIn["datas"]) && isset($this->KDIn["datas"]["sbtrs"]) && is_array($this->KDIn["datas"]["sbtrs"]) ) {
            $sbtrs = $TRD->onread_pull_substrends_from($ouid, $this->KDIn["datas"]["sbtrs"]["i"], $this->KDIn["datas"]["sbtrs"]["t"], $dir, TRUE);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $sbtrs) ) {
                $this->Ajax_Return("err",$sbtrs);
            }
        } else if ( key_exists("sbtrs", $this->KDIn["datas"]) && empty($this->KDIn["datas"]["sbtrs"]) ) {
            $sbtrs = $PA->onread_acquiere_following_trends_datas($ouid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $sbtrs) ) {
                $this->Ajax_Return("err",$sbtrs);
            }
        }
        //On consolide les données
        if ( $sbtrs ) {
            $TD__ = ( $mtrs ) ? array_merge($mtrs,$sbtrs) : $sbtrs;
        }
        
//        var_dump(key_exists("mtrs", $this->KDIn["datas"]), isset($this->KDIn["datas"]["mtrs"]), is_array($this->KDIn["datas"]["mtrs"]),$dir);
//        var_dump($TD__);
//        exit();
        
        $FE = [];
        if ( $TD__ && count( $TD__) ) {
            
            //On effectue un trie des données
            usort($TD__, function($a,$b){
                return floatval($a['trd_creadate_tstamp']) < floatval($b['trd_creadate_tstamp']);
            });

            foreach ( $TD__ as $k => $trd ) {
                if ( isset($trd["trd_next_del_tstamp"]) ) {
                    CONTINUE;
//                    return TRUE;
                }
               
                //NOTE  : C'est bien plus lisible ainsi :) !
                //*
                if ( $trd["trd_first_articles"] && is_array($trd["trd_first_articles"]) ) {
                    /*
                     * [DEPUIS 13-07-15] @BOR
                     */
                    $fas__ = array_column($trd["trd_first_articles"],"art_eid");
                    if ( $fas__ ) {
                        if ( count($fas__) > 4 ) { array_splice($fas__,4); }
                        $fartis = implode(',', $fas__);
                    } else {
                        if ( count($trd["trd_first_articles"]) > 4 ) { array_splice($trd["trd_first_articles"],4); }
                        $fartis = implode(',', $trd["trd_first_articles"]);
                    }
                } else {
                    $fartis = [];
                }
                //*/
                //$fartis = implode(',', $trd["trd_first_articles"]); //DEV, TEST, DEBUG
                $FE[] = [
                    "trd_eid"       => $trd["trd_eid"],
                    "trd_tle"       => $trd["trd_title"],
                    "trd_desc"      => html_entity_decode($trd["trd_desc"]),
                    "trd_href"      => $trd["trd_href"],
                    "trd_posts_nb"  => ( key_exists("trd_stats_posts",$trd) && isset($trd["trd_stats_posts"]) ) ? $trd["trd_stats_posts"] : 0,
                    "trd_abos_nb"   => ( key_exists("trd_stats_subs",$trd) && isset($trd["trd_stats_subs"]) ) ? $trd["trd_stats_subs"] : 0,
                    "trd_time"      => $trd["trd_creadate_tstamp"],
                    "tba"           => ( strtolower($trd["trd_oeid"]) === strtolower($this->KDIn["target"]["pdacc_eid"]) ) ? "mtrs" : "sbtrs",
                    /* COVER DATAS */
                    //*
                    "trd_cov_w"     => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["trcov_width"]."px" : NULL,
                    "trd_cov_h"     => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["trcov_height"]."px" : NULL,
                    "trd_cov_t"     => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["trcov_top"]."px" : NULL,
                    "trd_cov_rp"    => ( $trd["trd_cover"] ) ? $trd["trd_cover"]["pdpic_realpath"] : NULL,
                    //*/
                    /*
                     //DEV, TEST, DEBUG
                    "trd_cov_w"     => ( $trd["trd_cover"] ) ? NULL : NULL,
                    "trd_cov_h"     => ( $trd["trd_cover"] ) ? NULL : NULL,
                    "trd_cov_t"     => ( $trd["trd_cover"] ) ? NULL : NULL,
                    "trd_cov_rp"    => ( $trd["trd_cover"] ) ? NULL : NULL,
                    //*/
                    /* OWNER DATAS */
                    "trd_oid"       => $trd["trd_oeid"],
                    "trd_ofn"       => $trd["trd_ofn"],
                    "trd_opsd"      => $trd["trd_opsd"],
                    "trd_ohref"     => $trd["trd_ohref"],
                    "trd_oppic"     => $trd["trd_oppic"],
                    /* FIRST ARTICLES IDS */
                    "trd_fartis"    => $fartis,
//                    "trd_fartis"    => NULL //DEV, TEST, DEBUG
                    /* TARGET DATAS*/
                    "trd_pofn"      => $this->KDIn["target"]["pdacc_ufn"],
                    "trd_popsd"     => $this->KDIn["target"]["pdacc_upsd"],
                    "trd_poctrib"   => $TRD->onread_usercontrib($this->KDIn["target"]["pdaccid"],$trd["trid"]),
                ];
            }

        }
        
//        var_dump("APRES => ",$FE);    
//        exit();
                
        $this->KDOut["FE_DATAS"] = $FE; 
    }
    /* RESIDU de COPIER-COLLER. Je conserve le code au cas où !
    private function CheckAuth($tab) {
        
        if ( 
            ( key_exists("oeid", $this->KDIn) && $this->KDIn["oeid"] === $tab["oid"] ) //Je suis le propriétaire
            | ( key_exists("atrid", $tab) ) && !empty($tab["atrid"]) //C'est un Article de type ITR
        ) {
            return TRUE;
        } else if ( key_exists("oeid", $this->KDIn) && !empty($this->KDIn["oeid"]) ) {
            //A ce stade, il s'agit d'un Article de type IML. Il nous faut vérifier la relation qui lie les deux Acteurs dans le cas où CU est connecté 
            $REL = new RELATION();
            return $REL->friend_theyre_friends($this->KDIn["oeid"], $tab["oid_"]);
        } else {
            return FALSE;
        }
        
    }
    //*/
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
        $EXPTD = ["mtrs","sbtrs","c","dr","curl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( in_array($k,["mtrs","sbtrs"]) ) {
                continue;
            } else if ( !( isset($v) && $v !== "" && $v !== "''" ) )  {
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
            $exists = $A->exists($_SESSION["rsto_infos"]->getAcc_eid(),TRUE); 
            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }
            
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }       
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        //On détermine la cible
        /*
         * [NOTE 28/03/15 tirée du 29-11-14] @BlackOwlRobot
         *  (1) Pour déterminer la cible on se base sur l'URL de la page envoyée par FE. Cette manière de faire n'est pas très fiable.
         *  Une méthode fiable serait de récupérer les valeurs dans la variable de SESSION. Cependant, cette dernière n'a pas été concu pour faire la distinction entre
         *  les requetes de page et AJax. Aussi, la derniere requete est forcement celle d'AJAX.
         *  Il faut faudrait modifier : WTO, URQTABLE pour permettre de séparer les deux et ainsi obtenir la page de référence.
         *  Par la meme occasion, on pourrait aussi ajouter une foncitonnalité pour l'historisation de la navigation de l'utilisateur.
         */
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["curl"]);
        
//        var_dump($this->KDIn["datas"],$upieces);
//        exit();
        
        if ( $upieces && is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) {
            $PDACC = new PROD_ACC();
            $this->KDIn["target"] = $PDACC->exists_with_psd($upieces["user"],TRUE);
            if (! $this->KDIn["target"] ) {
                $this->Ajax_Return("err","__ERR_VOL_U_G");
            }
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        /*
         * [DEPUIS 09-11-15] @author BOR
         */
        if (! ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        }
        
        $this->GetTrends();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>