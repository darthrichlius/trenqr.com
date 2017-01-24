<?php

/*
 * Permet de récupérer un échantillon d'images à afficher au niveau de FKSA.
 * Ces images permettent de favoriser l'exploration de la plateforme dans le but d'améliorer la rétention.
 */
class WORKER_FKSA_PL_SMPL extends WORKER  {
    
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
            $SKIP = [""];
            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //*/
            
            //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
            $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
            $rbody = $this->KDIn["datas"][$k];

            preg_match_all("/(\n)/", $rbody, $m_c1);
            preg_match_all("/(\r)/", $rbody, $m_c2);
            preg_match_all("/(\r\n)/", $rbody, $m_c3);
            preg_match_all("/(\t)/", $rbody, $m_c4);
            preg_match_all("/(\s)/", $rbody, $m_c5);

            //Parano : Je sais que j'aurais pu ne mettre que \s
            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) ) {
//            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            $istr = ["ai","sec","curl"];
            if ( !empty($v) && in_array($k, $istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "curl" && !parse_url($v) ) {
//            if ( $k === "curl" && !filter_input(FILTER_VALIDATE_URL, preg_replace("#^http://|https://$#i", "", $v) ) ) {
//                var_dump($v,preg_replace("#^http://|https://$#i", "", $v));
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "sec" && !in_array($v,["_sec_testy", "_sec_article"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
        }
        
    }
    
    /************ GET SAMPLE NEIGHBORS (PIC, VID)  ************/
    
    private function GetSample_Neighbors () {
        //* On créé le commentaire pour Article *//
        
        /*
         * [DEPUIS 08-11-15] @author
         *      On affiche plus de publication quand l'utilisateur n'est pas connecté dans l'espoir qu'il se dirige vers la page TRPG ou TMLNR où la gestion est plus optimisée.
         * [DEPUIS 15-12-15] @author
         *      On affiche les 3 publications les plus récentes pour améliorer le taux de rebond. 
         */
        $nas = []; $case = NULL;
        
        /*
         * [DEPUIS 15-12-15]
         */
        $this->DoesItComply_Datas();
        
        $ai = $this->KDIn["datas"]["ai"];
        $curl = $this->KDIn["datas"]["curl"];


        /*
         * On utilise ART_TR car on peut récupérer les données sur les Articles IML comme ITR
         */
        $ART = new ARTICLE_TR();
        /*
        $r = $ART->on_read(["art_eid"=>$ai]);
        if ( !$r | $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        } else if ( $r === -1 ) {
            //Cas de l'Article IML
            $art_tab = $ART->getArt_loads();
        } else {
            $art_tab = $r;
        }
        //*/
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $a_is_itr = $ART->onread_is_trend_version_eid($ai);
        $art_tab = ( $a_is_itr ) 
            ? $ART->onread_archive_itr(["art_eid" => $ai])
            : $ART->onread_archive_iml(["art_eid" => $ai]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $art_tab) ) {
            $this->Ajax_Return("err", $art_tab);
        } 
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        if ( $this->KDIn["oid"] ) { //[DEPUIS 20-04-16]
//        if ( $this->KDIn["oid"] || ( !$this->KDIn["oid"] && intval($art_tab["art_is_sod"]) === 1 ) ) { //[DEPUIS 18-06-16]
        /*
         * [DEPUIS 18-06-16]
         *      Cette section gère désormais tous les cas
         */
        if ( TRUE ) {

            /*
             * On vérifie que l'utilisateur est bien autorisé à accéder à l'Article dans l'état.
             */
            $akx = $this->CheckAuth($art_tab);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $akx) ) {
                $this->Ajax_Return("err",$akx);
            } else if (! $akx ) {
                $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
            }

            /*
             * On vérifie qu'elles données peuvent être envoyées.
             * Cette opération dépend des différents facteurs :
             *      -> Type de l'Article
             *      -> Le propriétaire de l'Article
             */
            /* //[DEPUIS 30-07-15] @BOR
            $nas;
            if ( key_exists("trid", $art_tab) && $art_tab["trid"] ) {
                /*
                 * On récupère les x Articles les plus récents dans la Tendance, excepté celui passé.
                 * [30-05-15] Le nombre est de 9 Articles.
                 * 
                 * [NOTE 30-05-15] @BOR 
                 * Il faudra utiliser un algorithme qui sélectionne les meilleures images des Tendances.
                 * Cet algorithme améliora le caractère découverte de Trenqr permettant à l'utilisateur d'aller au delà.
                 *
                $TRD = new TREND();
                $as__ = $TRD->onload_trend_get_first_articles($art_tab["trid"],["FKSA_SAMPLE"]);
                if (! $as__ ) {
                    $this->Ajax_Return("err","__ERR_VOL_FAILED");
                }
                usort($as__, function($a,$b) {
                    return (floatval($a['art_cdate_tstamp']) < floatval($b['art_cdate_tstamp']));
                });
            } else {
                /*
                 * On récupère les x Articles les plus récents.
                 * On récupère les Articles IML et ITR sauf l'Article déjà affiché
                 *
                $PA = new PROD_ACC();
                $as = $PA->onread_load_my_first_articles($art_tab["art_oid"],["VM_ART","FKSA_SAMPLE"]);
                $as__ = array_merge($as["iml"],$as["itr"]);
                usort($as__, function($a,$b) {
                    return (floatval($a['art_creadate']) < floatval($b['art_creadate']));
                });
            }
            //*/
            
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            if ( !$this->KDIn["oid"] && ( intval($art_tab["art_is_sod"]) === 1 || $a_is_itr ) ) {
                $as = $ART->onload_neighbors_from($art_tab["artid"], "SOURCE_ACC_NOT_IML", $art_tab["art_oid"], 5, ["FKSA_SAMPLE","VM_ART"]);
                
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                
                /*
                 * [DEPUIS 18-06-16]
                 */
                $case = "wu";
            } else if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                $REL = new RELATION();
                
                /*
                 * [DEPUIS 18-06-16]
                 *      Corriger un BOGUE non détectable au niveau de FE qui faisait que :
                 *          (1) Les erreurs $REL->friend_theyre_friends() équivalaient à un TRUE
                 *          (1) Ne prennait pas le cas de CU is OWNER
                 */
                $rel = $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_oid"]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rel) ) {
                    if ( $rel !== "__ERR_VOL_SAME_PROTAS" ) {
                        $this->Ajax_Return("err",$rel);
                    }
                }
                
                /*
                 * [DEPUIS 18-06-16]
                 *      On prend le cas de CU is OWNER
                 */
                if ( floatval($this->KDIn["oid"]) === floatval($art_tab["art_oid"]) ) {
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    $as = $ART->onload_neighbors_from($art_tab["artid"], "SOURCE_ACC", $art_tab["art_oid"], NULL, ["FKSA_SAMPLE","VM_ART"]);
                }
                /*
                 * [DEPUIS 21-09-15] @author BOR
                 */
                /*
                if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_oid"]) 
//                    || $REL->onread_relation_exists_fecase($this->KDIn["oid"], $art_tab["art_oid"]) === "_REL_FEO" //[DEPUIS 20-04-16]
                ) {
                    //*/
                /*
                 * [DEPUIS 18-06-16]
                 */
                else if ( $rel !== FALSE ) {
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    $as = $ART->onload_neighbors_from($art_tab["artid"], "SOURCE_ACC", $art_tab["art_oid"], NULL, ["FKSA_SAMPLE","VM_ART"]);
                } else {
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    $as = $ART->onload_neighbors_from($art_tab["artid"], "SOURCE_ACC_NOT_IML", $art_tab["art_oid"], NULL, ["FKSA_SAMPLE","VM_ART"]);
                }
    //            $as = $ART->onload_neighbors_from($art_tab["artid"], "SOURCE_ACC", $art_tab["art_oid"], ["FKSA_SAMPLE","VM_ART"]); //[DEPUIS 21-09-15] @author BOR
            } else {
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                $as = $ART->onload_neighbors_from($art_tab["artid"], "SOURCE_ACC_NOT_IML", $art_tab["art_oid"], NULL, ["FKSA_SAMPLE","VM_ART"]);
            } 
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
    //        var_dump(__LINE__,__FUNCTION__,$as);
    //        exit();
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $as) ) {
                $this->Ajax_Return("err",$as);
            } else if (! $as ) {
               $this->Ajax_Return("return",NULL);
            }

            if ( $as["iml"] && $as["itr"] ) {
                $as__ = array_merge($as["iml"],$as["itr"]);
            } else if ( $as["iml"] ) {
                $as__ =  $as["iml"];
            } else if ( $as["itr"] ) {
                $as__ = $as["itr"];
            } else {
                $this->Ajax_Return("return",NULL);
            }
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
    //        var_dump(__LINE__,__FUNCTION__,$art_tab["artid"],$art_tab["art_oid"]);
    //        var_dump(__LINE__,__FUNCTION__,$as__);
    //        exit();

            /*
             * [DEPUIS 30-07-15] @BOR
             */
            $nas = $this->OnlyNeighbors($art_tab,$as__);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            /*
            $cn = 0;
            foreach ( $as__ as $k => $at ) {
                if ( $ai === $at["art_eid"] ) continue;
                if ( $cn === 9 ) break;
                $nas[] = [
                    "id"    => $at["art_eid"],
                    "im"    => ( $at["art_pdpic_path"] ) ? $at["art_pdpic_path"] : $ART->onread_archive_itr([ "art_eid" => $at["art_eid"] ])["art_pdpic_path"],
                    "h"     => $ART->onread_AcquierePrmlk($at["art_eid"])
                ];
                ++$cn;
            }
            //*/
    //        var_dump(__LINE__,__FUNCTION__,$nas);
    //        exit();
        } 
        /*
        else {
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            $TRD = new TREND();
            $as__ = $TRD->onload_trend_get_first_articles($art_tab["trid"],["FKSA_SAMPLE"]);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            if ( $as__ ) {
                usort($as__, function($a,$b) {
                    return (floatval($a['art_cdate_tstamp']) < floatval($b['art_cdate_tstamp']));
                });
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                if ( count($as__) > 3 ) {
                    $as__ = array_slice($as__,0,3);
                }
                
//                var_dump(__LINE__,__FUNCTION__,$art_tab);
//                exit();
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                foreach ($as__ as $k => $at) {
                    $ivid = ( $at["art_vid_url"] ) ? TRUE : FALSE;
                    $nas[] = [
                        "id"    => $at["art_eid"],
                        "im"    => $ART->onread_archive_itr([ "art_eid" => $at["art_eid"] ])["art_pdpic_path"],
                        "h"     => $ART->onread_AcquierePrmlk($at["art_eid"],$ivid),
//                        "h"     => $art_tab["trd_href"],
                        "vidu"  => $at["art_vid_url"]
                    ];
                }
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            }
            $case = "wu";
        }
        //*/
        
        
        $this->KDOut["FE_DATAS"] = [
            "ds" => $nas,
            "cz" => $case
        ];
        
    }
    private function CheckAuth($art_tab) {
        /*
         * Permet de vérifier si l'utilisateur actif est autorisé à accéder à l'Article en cours.
         * Cela dépend de :
         *  -> Le type d'Article
         *  -> Statut de connexion
         *  -> Son identité
         *  -> Sa relation avec le propriétaire de l'Article
         */
        
        //ETAPE 1 : On vérifie la nature de l'Article
        if ( key_exists("trid", $art_tab) && $art_tab["trid"] ) {
            /*
             * A la première version, on laisse les Commentaires pour permettre aux visiteurs de passer plus de temps sur la plateforme.
             * A partir de la version "non beta", on les retirera.
             */
            return TRUE;
        } else if ( intval($art_tab["art_is_sod"]) === 1 ) {
            return TRUE;
        } else {
            //ETAPE 2 : On vérifie la nature de l'Article
            if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
                
                //ETAPE 3 : On vérifie si l'utitlisateur est le propriétaire de l'Article
                if ( floatval($art_tab["art_oid"]) === floatval($this->KDIn["oid"]) ) {
                    return TRUE;
                }
            
                //ETAPE 4 : On vérifie la relation entre les utilisateurs
                $REL = new RELATION();
                /*
                 * [NOTE 31-05-15] @BOR
                 * On prend en compte le cas DFOLW
                 */
//                return $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_oid"]);
                if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_oid"]) 
                     || $REL->onread_relation_exists_fecase($this->KDIn["oid"], $art_tab["art_oid"]) === "_REL_FEO" ) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                /*
                 * Si l'utilisateur n'est pas connecté, il n'a pas accès à l'Article IML. 
                 * De plus, il n'aurait jamais du avoir accès 
                 */
                return FALSE;
            }
        }
    }
    
    /**
     * Permet de récupèrer les publiations précedent et suivant l'Article de 
     * @param array $rfatab La table de définition de l'Article
     * @param array $ads La liste des Articles
     * @param array $nads 
     * @return array
     */
    private function OnlyNeighbors ($rfatab,$ads) {
        $ai = $rfatab["artid"];
        
        /*
         * On crée un tableau pour les articles précédent ET pour les suivants
         */
        $arf; $aprv; $anxt;
        foreach ( $ads as $k => $atab ) {
            if ( $ai === $atab["artid"] ) {
                $arf = $atab;
                continue;
            }
            if ( $atab["art_creadate"] <= $rfatab["art_creadate"] ) {
                $aprv[] = $atab;
            } else {
                $anxt[] = $atab;
            }
        }
        
        /*
         * On s'assure que l'Article de référence est présent
         * [DEPUIS 18-07-16]
         */
        if ( !$arf ) {
            if ( intval($rfatab["art_is_hstd"]) === 0 ) {
                return "__ERR_VOL_FAILED";
            } else {
                $arf = $rfatab;
            }
        }
        
        /*
         * [DEPUIS 05-09-15] @author BOR
         */
        if ( !$aprv && !$anxt ) {
            return [];
        }
        
//        var_dump(__FUNCTION__,__LINE__,"NEXT ARTICLES ==> ",$aprv);
//        var_dump(__FUNCTION__,__LINE__,"REFERENCE ARTICLES ==> ",$rfatab);
//        var_dump(__FUNCTION__,__LINE__,"PREVIOUS ARTICLES ==> ",$aprv);
//        exit();
        
        /*
         * On trie les tableaux s'ils existent
         */
        if ( $anxt ) {
            usort($anxt, function($a,$b) {
                return (floatval($a['art_creadate']) < floatval($b['art_creadate']));
            });
        }
        if ( $aprv ) {
            usort($aprv, function($a,$b) {
                return (floatval($a['art_creadate']) < floatval($b['art_creadate']));
            });
        }
        
        /*
         * On précède à la réduction des éléments du tableau en fonction des cas
         */
        if (! $anxt ) {
            if ( $aprv && count($aprv) > 8 ) {
                $aprv = array_slice($aprv, 0, 8);
                array_push($aprv,$arf);
                $as__ = $aprv;
            } else if ( $aprv && count($aprv) <= 8 ) {
                array_push($aprv,$arf);
                $as__ = $aprv;
            } else {
                $as__ = $arf;
            }
        } else if (! $aprv ) {
             if ( $anxt && count($anxt) > 8 ) {
                $anxt = array_slice($anxt, 0, 8);
                array_push($anxt,$arf);
                $as__ = $anxt;
            } else if ( $anxt && count($anxt) <= 8 ) {
                array_push($anxt,$arf);
                $as__ = $anxt;
            } else {
                $as__ = $arf;
            }
        } else {
//            var_dump(__FUNCTION__,__LINE__,count($aprv),count($anxt),count($as__));
//            exit();
            if ( count($anxt) >= 4 && count($aprv) >= 4 ) {
                $anxt = array_slice($anxt, -4);
                $aprv = array_slice($aprv, 0, 4);
                $as__ = array_merge($aprv,$anxt);
                array_push($as__, $arf);
            } else if ( count($anxt) < 4 && count($aprv) >= 4 ) {
                $x__ = 9 - count($anxt) - 1;
                $aprv = array_slice($aprv, 0, $x__);
                $as__ = array_merge($aprv,$anxt);
                array_push($as__, $arf);
            } else if ( count($anxt) > 4 && count($aprv) < 4 ) {
                $x__ = 9 - count($aprv) - 1;
                $anxt = array_slice($anxt, 0, $x__);
                $as__ = array_merge($aprv,$anxt);
                array_push($as__, $arf);
            } else if ( count($anxt) < 4 && count($aprv) < 4 ) {
                $as__ = array_merge($aprv,$anxt);
                array_push($as__, $arf);
            }
        }
//        var_dump(__FUNCTION__,__LINE__,count($aprv),count($anxt),count($as__));
//        exit();
        
        /*
         * On effectue le dernier trie
         */
        usort($as__, function($a,$b) {
            return (floatval($a['art_creadate']) < floatval($b['art_creadate']));
        });
        
        $ART = new ARTICLE_TR();
        foreach ($as__ as $k => $at) {
            /*
             * [DEPUIS 21-09-15] @author BOR
             *      
             */
            if ( !( key_exists("trid", $at) && isset($at["trid"]) ) && !$this->CheckAuth($at) ) {
                continue;
            }
            
            $ivid = ( $at["art_vid_url"] ) ? TRUE : FALSE;
            $nads[] = [
                "id"    => $at["art_eid"],
                "im"    => ( $at["art_pdpic_path"] ) ? $at["art_pdpic_path"] : $ART->onread_archive_itr([ "art_eid" => $at["art_eid"] ])["art_pdpic_path"],
                "h"     => $ART->onread_AcquierePrmlk($at["art_eid"],$ivid),
                "vidu"  => $at["art_vid_url"]
            ];
        }
        
        return $nads;
    }
    
    /************ GET SAMPLE FIRST (TST)  ************/
    
     private function GetSample_First () {
        $nas = []; $case = NULL;
         
        $this->DoesItComply_Datas();
        
        $ai = $this->KDIn["datas"]["ai"];
        
        $TST = new TESTY();
        /*
         * ETAPE :
         *      On vérifie que le TESTY existe
         */
        $tst_tab = $TST->exists($ai);
        if (! $tst_tab ) {
            $this->Ajax_Return("err", "__ERR_VOL_TST_GONE");
        }
        
        $PA = new PROD_ACC();
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $as = $PA->onread_load_my_first_articles($tst_tab["tst_ouid"], 9, [
                "VM_ART",
                "FKSA_SAMPLE", 
                "CUID" => $this->KDIn["oid"]
            ]);
        } else {
            $as = $PA->onread_load_my_first_articles($tst_tab["tst_ouid"], 9, [
                "VM_ART",
                "FKSA_SAMPLE", 
                "ARTICLE_IML_FILTER" => "NOT_IML_FRD"
            ]);
        }
        
        $as__ = array_merge($as["iml"],$as["itr"]);
        usort($as__, function($a,$b) {
            return (floatval($a['art_creadate']) < floatval($b['art_creadate']));
        });
        
        if ( count($as__) > 9 ) {
            $as__ = array_slice($as__,0,9);
        }
        
//        var_dump(__LINE__,__FUNCTION__,$as__);
//        exit();
        
        $ART = new ARTICLE();
        foreach ($as__ as $k => $at) {
            $ivid = ( $at["art_vid_url"] ) ? TRUE : FALSE;
            $nas[] = [
                "id"    => $at["art_eid"],
                "im"    => ( $at["art_pdpic_path"] ) ? $at["art_pdpic_path"] : $ART->onread_archive_itr([ "art_eid" => $at["art_eid"] ])["art_pdpic_path"],
                "h"     => $ART->onread_AcquierePrmlk($at["art_eid"],$ivid),
                "vidu"  => $at["art_vid_url"]
            ];
        }
        
//        var_dump(__LINE__,__FUNCTION__,$nas);
//        exit();
        
        $case = (! ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ) ? "wu" : "";
         
        $this->KDOut["FE_DATAS"] = [
            "ds" => $nas,
            "cz" => $case
        ];
         
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
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * ai   : L'identifiant externen de l'Article (Photo/Video, Testy). 
         * sec  : La section (le type) de la page FKSA_GTPG : ARTICLE, TESTY
         * curl : L'URL de la page qui a déclencher l'opération
         */
        $EXPTD = ["ai","curl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" && $v !== "''" ) && !in_array($k,[""]) )  {
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
            $exists = $A->exists_with_id($oid,TRUE);

            if (! $exists ) {
                $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
            }

            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
            
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["datas"] = $in_datas;
        } else {
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["datas"] = $in_datas;
        }
    }

    public function on_process_in() {
        
        /*
         * [DEPUIS 18-06-16]
         *      On récupère les données au sujet de la PAGE pour effectuer des vérifications ultérieures
         */
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["curl"]);
        
//        var_dump($this->KDIn["datas"]["curl"],$upieces);
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        
        $atype = $this->KDIn["upieces"]["ups_raw"]["atypi"];
        
        /*
         * [DEPUIS 18-06-16]
         * ETAPE :
         *      Le dispatching se fait en fonction de l'URL et de la donnée "sec" envoyée.
         *      Cette double vérification est faite pour améliorer la fiabilité de l'opération
         */
        switch ($atype) {
            case "photo" :
            case "video" :
                    if ( strtolower($this->KDIn["datas"]["sec"]) === "_sec_article" ) {
                        $this->GetSample_Neighbors();
                    }
                break;
            case "testy" :
                    if ( strtolower($this->KDIn["datas"]["sec"]) === "_sec_testy" ) {
                        $this->GetSample_First();
                    }
                break;
            default :
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                break;
        }
        
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