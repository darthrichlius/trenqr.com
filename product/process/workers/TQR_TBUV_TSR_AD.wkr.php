<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_TBUV_TSR_AD extends WORKER  {
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
             
            $istr = ["ti","tx","cu"];
            if ( $v && in_array($k,$istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }

        }
        
    }
    
    
    private function AddReaction() {
        
        $this->DoesItComply_Datas();
        
        /*
         * [DEPUIS 09-11-15] @author BOR
         *      Dans le sens de la restriction de données pour les utilisateurs hors connexion.
         */
        /*
        if ( $this->KDIn["datas"]["tx"] === "BTM" && !( key_exists("oid",$this->KDIn) && $this->KDIn["oid"] ) ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        }
        //*/
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        switch ($page) {
            case "TMLNR_GTPG_RO" : 
            case "TMLNR_GTPG_RU" : 
                    $pgeid = "TMLNR";
                break;
            case "TRPG_GTPG_RO" : 
            case "TRPG_GTPG_RU" : 
                    $pgeid = "TRPG";
                break;
            case "TQR_GTPG_HVIEW" :
                    $pgeid = "HVIEW";
                break;
            case "FKSA_GTPG" :
                    $pgeid = "FKSA";
                break;
            case "TMLNR_GTPG_WLC" :
            case "TRPG_GTPG_WLC" :
            default:
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
        }
        
        
        $TST = new TESTY();
        $tstab = $TST->exists($this->KDIn["datas"]["ti"]);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        $PA = new PROD_ACC();
        $tgtab = $PA->exists_with_id($tstab["tst_tguid"],TRUE);
        if (! $tgtab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        
        /*
         * ETAPE :
         *      On vérifie les permissions
         */
        $cu_ico = ( $this->KDIn["oeid"] ) ? TRUE : FALSE;
        $oeid = ( $this->KDIn["oeid"] ) ? $this->KDIn["oeid"] : NULL;
        $perm = $TST->onread_hasPermission($tgtab["pdacc_eid"], $oeid, $cu_ico);
//        var_dump($perm);
//        exit();
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $perm) ) {
            $this->Ajax_Return("err",$perm);
        } else if ( $perm === TRUE ) {
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$this->KDIn);
//            exit();
            
            /*
             * ETAPE :
             *      On ajoute le commentaire
             */
            $tsr_tab = $TST->React_Add($this->KDIn["oid"], $this->KDIn["datas"]["ti"], $this->KDIn["datas"]["tx"], $pgeid, session_id(), $this->KDIn["locip"], $this->KDIn["uagent"], NULL);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tsr_tab) ) {
                $this->Ajax_Return("err", $tsr_tab);
            }
            
            /*
             * ETAPE :
             *      On récupère la table du commentaire le commentaire pour les autres procesus qui aurait besoin de données connexes
             */
            $tsr_tab = $TST->React_Read($tsr_tab["pdrtab"]["pdrct_eid"]);
            $this->KDOut["tsr_tab"] = $tsr_tab;
            
            /*
             * ETAPE :
             *      On récupère les données en fonction du cas en présence
             */
            $tsrds;
            $OPS = (! $this->KDIn["oid"] ) ? [] : [
                "cuid"  => $this->KDIn["oid"]
            ];
            if ( $this->KDIn["datas"]["pi"] && $this->KDIn["datas"]["pt"] ) {
                $tsrds = $TST->React_Pull($this->KDIn["datas"]["ti"],"TOP",$this->KDIn["datas"]["pi"],$this->KDIn["datas"]["pt"],TRUE,$OPS); 
            } else {
                $tsrds = $TST->React_Pull($this->KDIn["datas"]["ti"],"FST",NULL,NULL,TRUE,$OPS);
            }
            if ( $tsrds && $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tsrds) ) {
                $this->Ajax_Return("err", $tsrds);
            }
            
            
            $tstmetas = [];
            if ( $tsrds ) {
                $tsotab = $PA->exists_with_id($tstab["tst_ouid"]);
                
                $tstmetas = [
                   /*
                    * cdl : CanDelete
                    *      (1) L'utilisateur connecté est le propriétaire
                    *      (2) L'utilisateur connecté est la cible du message
                    */
                   "cdl"    => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tstab["tst_tguid"]) ) ? TRUE : FALSE,
                   /*
                    * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                    * NOTE :
                    *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                    */
                   "clk"    => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                   //QUESTION ? L'utilisateur a t-il LIKE ?
                   "hslk"   => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tstab["tstid"]) ) ? TRUE : FALSE,
                   //QUESTION ? Le nombre de LIKE ?
                   "cnlk"   => $TST->Like_Count($tstab["tst_eid"]),
                   //QUESSTION ? Le nombre de REACT
                   "cnrct"  => $TST->React_Count($tstab["tst_eid"])
                ];
                
            }
            
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tsrds,$tstmetas);
//        exit();
            
        if ( $this->KDIn["oeid"] ) {
            
            /*
             * [DEPUIS 25-11-15] @author BOR
             *      J'ai corrigé une erreur de conception GRAVE.
             *      Cette nouvelle version prend en compte tous les cas : DENY_FOR et GROUPE
             */
            $iwd = ( $TST->oncreate_hasPermission($this->KDIn["oeid"],$tgtab["pdacc_eid"]) ) ? FALSE : TRUE;
            $ird = ( $TST->onread_hasPermission($this->KDIn["oeid"],$tgtab["pdacc_eid"]) ) ? FALSE : TRUE;
            
            /*
            $iwd = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTADD");
            $ird = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTSEE");
            //*/
        } else {
            $iwd = TRUE;
            $ird = ( $perm === FALSE ) ? TRUE : FALSE;
        }
                
        $FE_DATAS = [
            "xt"        => $this->KDIn["datas"]["xt"],
            "tsrds"     => $tsrds,
            "tstmetas"  => $tstmetas,
            "rnb"       => $TST->React_Count($this->KDIn["datas"]["ti"]),
            "iwd"       => ( $iwd ) ? TRUE : FALSE,
            "ird"       => ( $ird ) ? TRUE : FALSE
        ];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$this->KDOut["tsr_tab"]]);
//        exit();
        
        /******************************************************************************************************************************************************/
        
        /*
         * ETAPE :
         *      On procède à l'ajout dans la table des ACTIONS en fonction du cas en présence.
         *  CAS 1 : La cible et l'auteur du TSM sont les mêmes
         *      CAS 11 : Il n'y a pas de USERTAG > Je continue
         *      CAS 12 : Il y a des USERTAGS > J'ajoute en tant que USTG_TSR (CUID n'est pas pris en compte)
         * 
         *  /!\ NOTE : Les cas ci-dessous font tous références au cas la CIBLE et l'AUTEUR du TSM sont différents /!\
         * 
         *  CAS 2 : Il n'existe aucun USERTAG > J'ajoute en tant que TSR
         *  Cas 3 : Il existe un ou plusieurs USERTAGS
         *      Cas 31 :  Il n'y a qu'un USERTAG
         *          Cas 311 : ... et Je suis la personne marquée        > J'ajoute en tant que TSR
         *          Cas 312 : ... et Je ne suis pas la personne marquée > J'ajoute en tant que USTG_TSR
         *      Cas 32 : Il y a plusieurs USERTAGS
         *          Cas 321 : ... et Je NE fais pas partie des personnes marquées > J'ajoute en tant que USTG_TSR
         *              > J'ajoute en tant que TSR dans le cas de OWNER et TARGET TSM
         *              > J'ajoute en tant que USTG_TSR pour les autres (OWNER et TARGET TSM seront omis)
         *          Cas 322 : ... et Je fais partie des personnes marquées 
         *              > J'ajoute en tant que TSR dans le cas de OWNER et TARGET TSM
         *              > J'ajoute en tant que USTG_TSR pour les autres (OWNER et TARGET TSM seront omis)
         */
        if ( $this->KDIn["oeid"] === $this->KDOut["tsr_tab"]["tstab"]["oueid"] && $this->KDIn["oeid"] === $this->KDOut["tsr_tab"]["tstab"]["tgueid"] ) {
            //QUOI ? CAS 1 : La cible et l'auteur du TSR sont les mêmes
            
            /*
             * ETAPE :
             *      On ajoute en tant que USTG_TSR
             */
            if ( key_exists("usertags", $this->KDOut["tsr_tab"]["pdrtab"]) && $this->KDOut["tsr_tab"]["pdrtab"]["usertags"] && is_array($this->KDOut["tsr_tab"]["pdrtab"]["usertags"]) ) {
                //QUOI ? CAS 12 : Il y a des USERTAGS > J'ajoute en tant que USTG_TSR (CUID n'est pas pris en compte)
                
                $this->LogUsertagActy_USTG_TSR($this->KDIn["oid"], 1107, $this->KDOut["tsr_tab"]["pdrtab"]["usertags"], [$this->KDOut["tsr_tab"]["tstab"]["ouid"]]);
            }
        } else if ( !key_exists("usertags", $this->KDOut["tsr_tab"]["pdrtab"]) || empty($this->KDOut["tsr_tab"]["pdrtab"]["usertags"]) ) {
            //QUOI ? CAS 2 : Il n'existe aucun USERTAG > J'ajoute en tant que TSR
            
            $this->LogUsertagActy_TSR($this->KDIn["oid"], 1212, $this->KDOut["tsr_tab"]["tsrtab"]["id"]);
        } else if ( key_exists("usertags", $this->KDOut["tsr_tab"]["pdrtab"]) && $this->KDOut["tsr_tab"]["pdrtab"]["usertags"] && is_array($this->KDOut["tsr_tab"]["pdrtab"]["usertags"]) ) { 
            //QUOI ? CAS 3 : Il existe un ou plusieurs USERTAGS
            
            /*
             * ETAPE :
             *      On récupère les identifiants de OWNER et TARGET TSM
             */
            $tsm_ouid = $this->KDOut["tsr_tab"]["tstab"]["ouid"];
            $tsm_tguid = $this->KDOut["tsr_tab"]["tstab"]["tguid"];
            
            if ( count($this->KDOut["tsr_tab"]["pdrtab"]["usertags"]) === 1 ) {
                $ustg_tab = $this->KDOut["tsr_tab"]["pdrtab"]["usertags"][0];
                if ( floatval($ustg_tab["ustg_tgtuid"]) === floatval($this->KDIn["oid"]) ) {
                    //QUOI ? Cas 311 : Il n'y a qu'un USERTAG et Je suis la personne marquée
                    
                    $this->LogUsertagActy_TSR($this->KDIn["oid"], 1212, $this->KDOut["tsr_tab"]["tsrtab"]["id"]);
                } else {
                    //QUOI ? Cas 312 : Il n'y a qu'un USERTAG et Je ne suis la personne marquée
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en TSR pour permettre à OWNER et TARGET de TSM de recevoir la Notification.
                     *      POSTMAN se chargera de gérer les cas spéciaux pour éviter les doublons.
                     */
                    $this->LogUsertagActy_TSR($this->KDIn["oid"], 1212, $this->KDOut["tsr_tab"]["tsrtab"]["id"]);
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en USTG_TSR pour la personne visée.
                     *      La méthode se charge de ne pas envoyer à OWNER et TARGET qui ont déjà été avertis par l'activité TSR
                     */
                    $this->LogUsertagActy_USTG_TSR($this->KDIn["oid"], 1107, $this->KDOut["tsr_tab"]["pdrtab"]["usertags"], [$tsm_ouid,$tsm_tguid]);
                }
            } else {
                /*
                 * ETAPE :
                 *      On récupère les identifiants externes des personnes mentionnées
                 */
                $uztagged = array_column($this->KDOut["tsr_tab"]["pdrtab"]["usertags"], "tgtueid");
                if (! in_array($this->KDIn["oeid"], $uztagged) ) {
                    //QUOI ? Cas 321 : Il y a plusieurs USERTAGS et Je NE fais pas partie des  personnes marquées
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en TSR pour permettre à OWNER et TARGET de TSM de recevoir la Notification.
                     *      POSTMAN se chargera de gérer les cas spéciaux pour éviter les doublons.
                     */
                    $this->LogUsertagActy_TSR($this->KDIn["oid"], 1212, $this->KDOut["tsr_tab"]["tsrtab"]["id"]);
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en USTG_TSR pour les personnes visées.
                     *      La méthode se charge de ne pas envoyer à OWNER et TARGET qui ont déjà été avertis par l'activité TSR
                     */
                    $this->LogUsertagActy_USTG_TSR($this->KDIn["oid"], 1107, $this->KDOut["tsr_tab"]["pdrtab"]["usertags"], [$tsm_ouid,$tsm_tguid]);
                } else {
                    //QUOI ? Cas 322 : Il y a plusieurs USERTAGS et JE FAIS partie des personnes marquées 
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en TSR pour permettre à OWNER et TARGET de TSM de recevoir la Notification.
                     *      POSTMAN se chargera de gérer les cas spéciaux pour éviter les doublons.
                     */
                    $this->LogUsertagActy_TSR($this->KDIn["oid"], 1212, $this->KDOut["tsr_tab"]["tsrtab"]["id"]);
                    
                    /*
                     * ETAPE :
                     *      J'ajoute en USTG_TSR pour les personnes visées.
                     *      La méthode se charge de ne pas envoyer à OWNER et TARGET qui ont déjà été avertis par l'activité TSR
                     */
                    $this->LogUsertagActy_USTG_TSR($this->KDIn["oid"], 1107, $this->KDOut["tsr_tab"]["pdrtab"]["usertags"], [$tsm_ouid,$tsm_tguid]);
                }
            }
        }
        
        /******************************************************************************************************************************************************/
            
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
        
    }

    
    private function LogUsertagActy_TSR ($uid, $uatid, $refid) {
        
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
    
    
    private function LogUsertagActy_USTG_TSR ($uid, $uatid, $ustgs_tab, $exclude_tab = NULL) {
        
        $PM = new POSTMAN();
        foreach ($ustgs_tab as $ustgs) {
            if ( floatval($ustgs["uid"]) !== floatval($uid) && ( !isset($exclude_tab) || ( $exclude_tab && !in_array($ustgs["uid"], $exclude_tab) ) ) ) {
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
                    "refobj"        => $ustgs["id"],
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
         * "ti" : TesyID  
         * "tx" : Le texte lié au Commentaire
         * "pi" : Pivot ID
         * "pt" : Pivot timestamp
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         * "xt" : L'identifiant de l'opération qui permet de fiabiliser l'affichage des commentaires en prenant en compte le temps de réponse
         */
        $EXPTD = ["xt","ti","tx","pi","pt","cu"]; 
        
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
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        }
        
        $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
        
        $this->AddReaction();
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
