<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_TSTY_ADD extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if ( in_array($k,["pi","pt"]) && !( isset($v) && $v !== "" ) ) {
                continue;
            }
                
            
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
    
    
    private function AddTesty() {
        
        $this->DoesItComply_Datas();
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        if (! in_array($page,["TMLNR_GTPG_RO","TMLNR_GTPG_RU"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
        }
        $psd = $this->KDIn["upieces"]["user"];
        
        $PA = new PROD_ACC();
        $tgtab = $PA->exists_with_psd($psd,TRUE);
        if (! $tgtab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        
        $args_new = [
            "ouid"      => $this->KDIn["oeid"],
            "tguid"     => $tgtab["pdacc_eid"],
            "msg"       => $this->KDIn["datas"]["tv"],
            "ssid"      => session_id(),
            "locip"     => $this->KDIn["locip"],
            "uagent"    => $this->KDIn["uagent"]
        ];
        
        $TST = new TESTY();
        
        /*
         * ETAPE :
         *      On vérifie les permissions
         */
//        var_dump(__LINE__,__FILE__,$this->KDIn["oeid"], $tgtab["pdacc_eid"], $TST->oncreate_hasPermission($this->KDIn["oeid"], $tgtab["pdacc_eid"], TRUE));
        $perm = $TST->oncreate_hasPermission($this->KDIn["oeid"], $tgtab["pdacc_eid"], TRUE);
        $FE = [];
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $perm) ) {
            $this->Ajax_Return("err",$perm);
        } else if ( $perm === TRUE ) {
        
            /*
             * ETAPE :
             *      On ajoute le témoignage
             */
            $ttab = $TST->on_create_entity($args_new);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ttab) ) {
                $this->Ajax_Return("err", $ttab);
            }
            
            /*
             * ETAPE :
             *      On place les données de telle sorte qu'on puisse les utiliser au niveau d'autres processus.
             */
            $this->KDOut["ttab"] = $ttab;

            /*
             * ETAPE :
             *      On récupère les données en fonction du cas en présence
             */
            $tds = [];
            $tds = ( $this->KDIn["datas"]["pi"] && $this->KDIn["datas"]["pt"] ) ? 
                $TST->onread_getTesties($tgtab["pdacc_eid"], "TOP", $this->KDIn["datas"]["pi"], $this->KDIn["datas"]["pt"]) : $TST->onread_getTesties($tgtab["pdacc_eid"], "FST");
            
            if ( $tds && $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tds) ) {
                if ( $tds !== "__ERR_VOL_REF_GONE" ) {
                    $this->Ajax_Return("err", $tds);
                }
                $tds = [$ttab];
            }
            
            $ids = []; $cn = 0;
            foreach ($tds as $tst) {

                $tsotab = $PA->exists_with_id($tst["tst_ouid"]);
                if (! $tsotab ) {
                    continue;
                }
                $tstgtab = $PA->exists_with_id($tst["tst_tguid"]);
                
                /*
                 * Sert surtout dans le cas de la gestion de PINNED
                 */
                if ( $ids && in_array($tst["tst_eid"], $ids) ) {
                    continue;
                }
                
                $FE[] = [
                    "i"         => $tst["tst_eid"],
                    "tm"        => $tst["tst_adddate_tstamp"],
                    "m"         => html_entity_decode($tst["tst_msg"]),
                    "plk"       => $tst["tst_prmlk"],
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
                    "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst["tst_tguid"]) ) ? TRUE : FALSE,
                    /*
                     * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                     * cgap : CanGetAccesstoPin
                     */
                    "cgap"      => ( intval($this->KDIn["oid"]) === intval($tst["tst_tguid"]) ) ? TRUE : FALSE,
                    //QUESTION ? Le TESTIMONY est-il PIN ?
                    "isp"       => $TST->Pin_IsPin($tst["tstid"]),
                    "rnb"       => $TST->React_Count($tst["tst_eid"]),
                    /*
                     * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                     * NOTE :
                     *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                     */
                    "clk"       => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                    //QUESTION ? L'utilisateur a t-il LIKE ?
                    "hslk"      => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tst["tstid"]) ) ? TRUE : FALSE,
                    //QUESTION ? Le nombre de LIKE ?
                    "cnlk"      => $TST->Like_Count($tst["tst_eid"]),
                    //Données sur les USERTAGS
                    "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst["tst_eid"],TRUE),
                    //Données sur les HASHTAGS
                    "hashs"     => $TST->onread_AcquiereHashs_Testy($tst["tst_eid"]),
                    /*
                     * [DEPUIS 31-05-16]
                     *      On récupère le lien permanent de TSM
                     */
                    "prmlk"     => $_SERVER["HTTP_HOST"].$TST->onread_AcquierePrmlk($tst["tst_eid"]),
                
                ];
                $ids[] = $tst["tst_eid"];
                ++$cn;
                
               /*
                * [DEPUIS 11-12-15] @author BOR
                */
                if ( strtoupper($this->KDIn["datas"]["dr"]) === "FST" && $cn === 3 ) {
                    break;
                }
                
            }
            
            if ( $this->KDIn["oeid"] ) {
            
                /*
                 * [DEPUIS 25-11-15] @author BOR
                 *      J'ai corrigé une erreur de conception GRAVE.
                 *      Cette nouvelle version prend en compte tous les cas : DENY_FOR et GROUPE
                 */
                $iwd = ( $TST->oncreate_hasPermission($this->KDIn["oeid"],$tgtab["pdacc_eid"]) ) ? FALSE : TRUE;
                $ird = ( $TST->onread_hasPermission($this->KDIn["oeid"],$tgtab["pdacc_eid"]) ) ? FALSE : TRUE;

            } else {
                $iwd = TRUE;
                $ird = ( $perm === FALSE ) ? TRUE : FALSE;
            }
            
            $FE_DATAS = [
                "tds"   => $FE,
                "iwd"   => ( $iwd ) ? TRUE : FALSE,
                "ird"   => ( $ird ) ? TRUE : FALSE
            ];
        }
            /*
            foreach ($tds as $tst) {
                $tsotab = $PA->exists_with_id($tst["tst_ouid"],TRUE);
                if (! $tsotab ) {
                    continue;
                }

                $FE[] = [
                    "i"         => $tst["tst_eid"],
                    "tm"        => $tst["tst_adddate_tstamp"],
                    "m"         => html_entity_decode($tst["tst_msg"]),
                    "oid"       => $tsotab["pdacc_eid"],
                    "ofn"       => $tsotab["pdacc_ufn"],
                    "opsd"      => $tsotab["pdacc_upsd"],
                    "oppic"     => $PA->onread_acquiere_pp_datas($tsotab["pdaccid"])["pic_rpath"],
                    /*
                     * cdl : CanDelete
                     *      (1) L'utilisateur connecté est le propriétaire
                     *      (2) L'utilisateur connecté est la cible du message
                     *
                    "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst["tst_tguid"]) ) ? TRUE : FALSE,
                    //Données sur les USERTAGS
                    "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst["tst_eid"],TRUE),
                    //Données sur les HASHTAGS
                    "hashs"     => NULL
                ];
            }
        }
        
        if ( $this->KDIn["oeid"] ) {
            $iwd = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTADD");
            $ird = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTSEE");
        }
        
        $FE_DATAS = [
            "tds"   => $FE,
            "iwd"   => ( $iwd ) ? TRUE : FALSE,
            "ird"   => ( $ird ) ? TRUE : FALSE,
            /*
             * Permet d'indiquer :
             *      (1) Le capital mis à jour si l'utilisateur est sur son compte
             *      (2) Permet de justifier l'impossibilité d'ajouter un témoignage 
             *
            "ucp"   => $PA->onread_updatedCapitalFor($this->KDIn["oid"],["AQAP"]),
        ];
        //*/
        
        
        /******************************************************************************************************************************************************/
        
        
        /*
         * ETAPE :
         *      On procède à l'ajout dans la table des ACTIONS en fonction du cas en présence.
         *  CAS 1 : La personne connectée est la cible du TSM 
         *      Cas 11 : Il existe aucun Usertag > Je continue
         *      Cas 12 : Il existe des Usertags > J'ajoute en tant que USTG_TSM (CUID n'est pas pris en compte)
         * 
         *  /!\ NOTE : Les cas ci-dessous font tous références au cas la CIBLE et l'AUTEUR du TSM sont différents /!\
         *  
         *  CAS 2 : Il n'existe aucun USERTAG > J'ajoute en tant que TSM
         *  Cas 3 : Il existe des USERTAGS
         *      Cas 31 :  Il n'y a qu'un USERTAG
         *          Cas 311 : Il n'y a qu'un USERTAG et Je suis la personne marquée      > J'ajoute en tant que TSM
         *          Cas 312 : Il n'y a qu'un USERTAG et Je ne suis la personne marquée   > J'ajoute en tant que USTG_TSM
         *      Cas 32 : Il y a plusieurs USERTAGS
         *          Cas 321 : Il y a plusieurs USERTAGS et Je NE fais pas partie des personnes marquées > J'ajoute en tant que USTG_TSM
         *          Cas 322 : Il y a plusieurs USERTAGS et Je fais partie des personnes marquées 
         *              > J'ajoute en tant que TSM dans le cas de CUID
         *              > J'ajoute en tant que USTG_TSM pour les autres
         */
        if ( floatval($this->KDIn["oid"]) === floatval($this->KDOut["ttab"]["tst_tguid"]) ) {
            //QUOI ? CAS 1 : Si l'utilisateur connecté est en même temps à cible du TSM on passe !
            if ( key_exists("tst_list_usertags", $this->KDOut["ttab"]) && $this->KDOut["ttab"]["tst_list_usertags"] && is_array($this->KDOut["ttab"]["tst_list_usertags"]) ) {
                //QUOI ? CAS 12 : Il y a des USERTAGS > J'ajoute en tant que USTG_TSM (CUID n'est pas pris en compte)
                
                $this->LogUsertagActy_USTG_TSM($this->KDIn["oid"], 1106, $this->KDOut["ttab"]["tst_list_usertags"]);
            }
        } else if ( !key_exists("tst_list_usertags", $this->KDOut["ttab"]) || empty($this->KDOut["ttab"]["tst_list_usertags"]) ) {
            //QUOI ? CAS 2 : Il n'existe aucun USERTAG > J'ajoute en tant que TSM
            
            $this->LogUsertagActy_TSM($this->KDIn["oid"], 1202, $this->KDOut["ttab"]["tstid"]);
        } else if ( key_exists("tst_list_usertags", $this->KDOut["ttab"]) && $this->KDOut["ttab"]["tst_list_usertags"] && is_array($this->KDOut["ttab"]["tst_list_usertags"]) ) { 
            //QUOI ? CAS 3 : Il existe des USERTAGS
            
            /*
             * ETAPE :
             *      On récupère les identifiants de OWNER et TARGET TSM
             */
            $tsm_ouid = $this->KDIn["oid"];
            $tsm_tguid = $this->KDOut["ttab"]["tst_tguid"];
            
            if ( count($this->KDOut["ttab"]["tst_list_usertags"]) === 1 ) {
                $ustg_tab = $this->KDOut["ttab"]["tst_list_usertags"][0];
                if ( floatval($ustg_tab["ustg_tgtuid"]) === floatval($this->KDIn["oid"]) ) {
                    //QUOI ? Cas 311 : Il n'y a qu'un USERTAG et Je suis la personne marquée
                    
                    $this->LogUsertagActy_TSM($this->KDIn["oid"], 1202, $this->KDOut["ttab"]["tstid"]);
                } else {
                    //QUOI ? Cas 312 : Il n'y a qu'un USERTAG et Je ne suis la personne marquée
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en TSM pour permettre à OWNER TSM de recevoir la Notification.
                     *      POSTMAN se chargera de gérer les cas spéciaux pour éviter les doublons.
                     */
                    $this->LogUsertagActy_TSM($this->KDIn["oid"], 1202, $this->KDOut["ttab"]["tstid"]);
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en USTG_TSM pour la personne visée.
                     *      La méthode se charge de ne pas envoyer à TARGET qui a déjà été averti par l'activité TSM
                     */
                    $this->LogUsertagActy_USTG_TSM($this->KDIn["oid"], 1106, $this->KDOut["ttab"]["tst_list_usertags"], [$tsm_tguid]);
                }
            } else {
                $uztagged = array_column($this->KDOut["ttab"]["tst_list_usertags"], "tgtueid");
                if (! in_array($this->KDIn["oeid"], $uztagged) ) {
                    //QUOI ? Cas 321 : Il y a plusieurs USERTAGS et Je NE fais pas partie des  personnes marquées
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en TSM pour permettre à OWNER TSM de recevoir la Notification.
                     *      POSTMAN se chargera de gérer les cas spéciaux pour éviter les doublons.
                     */
                    $this->LogUsertagActy_TSM($this->KDIn["oid"], 1202, $this->KDOut["ttab"]["tstid"]);
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en USTG_TSM pour la personne visée.
                     *      La méthode se charge de ne pas envoyer à TARGET qui a déjà été averti par l'activité TSM
                     */
                    $this->LogUsertagActy_USTG_TSM($this->KDIn["oid"], 1106, $this->KDOut["ttab"]["tst_list_usertags"], [$tsm_tguid]);
                } else {
                    //QUOI ? Cas 322 : Il y a plusieurs USERTAGS et JE FAIS partie des personnes marquées 
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en TSM pour permettre à OWNER TSM de recevoir la Notification.
                     *      POSTMAN se chargera de gérer les cas spéciaux pour éviter les doublons.
                     */
                    $this->LogUsertagActy_TSM($this->KDIn["oid"], 1202, $this->KDOut["ttab"]["tstid"]);
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en USTG_TSM pour la personne visée.
                     *      La méthode se charge de ne pas envoyer à TARGET qui a déjà été averti par l'activité TSM 
                     * NOTE :
                     *      On n'a pas besoin de retirer l'occurrence au sujet de CUID, la fonction qui gère l'enregistrement va l'éliminer par défaut.
                     */
                    $this->LogUsertagActy_USTG_TSM($this->KDIn["oid"], 1106, $this->KDOut["ttab"]["tst_list_usertags"] [$tsm_tguid]);
                }
            }
        }
        
        /******************************************************************************************************************************************************/
            
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
        
    }

    /***************************************************** LOG ACTIVITIES *****************************************************/
    
    private function LogUsertagActy_TSM ($uid, $uatid, $refid) {
        
        $PM = new POSTMAN();
        //On ajoute dans la table des Actions
        $args = [
            "uid"           => $uid,
            "ssid"          => session_id(),
            "locip_str"     => $_SESSION['sto_infos']->getCurrent_ipadd(),
            "locip_num"     => $this->KDIn["locip"],
            "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"           => __CLASS__,
            "fe_url"        => $this->KDIn["datas"]["cu"],
            "srv_url"       => $this->KDIn["srv_curl"],
            "url"           => $this->KDIn["srv_curl"],
            "isAx"          => 1,
            "refobj"        => $refid,
            "uatid"         => $uatid,
            "uanid"         => 2
        ];
        $uai = $PM->UserActyLog_Set($args);
    }
    
    
    private function LogUsertagActy_USTG_TSM ($uid, $uatid, $ustgs_tab, $exclude_tab = NULL) {
        
        $PM = new POSTMAN();
        foreach ($ustgs_tab as $ustgs) {
            if ( floatval($ustgs["tgtuid"]) !== floatval($uid) && ( !isset($exclude_tab) || ( $exclude_tab && !in_array($ustgs["tgtuid"], $exclude_tab) ) ) ) {
                //On ajoute dans la table des Actions
                $args = [
                    "uid"           => $uid,
                    "ssid"          => session_id(),
                    "locip_str"     => $_SESSION['sto_infos']->getCurrent_ipadd(),
                    "locip_num"     => $this->KDIn["locip"],
                    "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
                    "wkr"           => __CLASS__,
                    "fe_url"        => $this->KDIn["datas"]["cu"],
                    "srv_url"       => $this->KDIn["srv_curl"],
                    "url"           => $this->KDIn["srv_curl"],
                    "isAx"          => 1,
                    "refobj"        => $ustgs["ustg_id"],
                    "uatid"         => $uatid,
                    "uanid"         => 2
                ];
                $uai = $PM->UserActyLog_Set($args);
            }
        }
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
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["tv","pi","pt","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,["pi","pt"]) ) {
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
        if (! $CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        
        $this->KDIn["datas"] = $in_datas;
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
        
        $this->AddTesty();
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
