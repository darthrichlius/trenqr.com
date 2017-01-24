<?php

/* 
 * RAPPEL : 
 *      UAR : UserActivtyReport. Il ne s'agit que d'une autre manière de désigner une Notification.
 */

class WORKER_PM_SBPL_RPS extends WORKER  {
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            $SKIP = ["rgrs","plds"];
            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
            $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
            $rbody = $this->KDIn["datas"][$k];

            preg_match_all("/(\n)/", $rbody, $m_c1);
            preg_match_all("/(\r)/", $rbody, $m_c2);
            preg_match_all("/(\r\n)/", $rbody, $m_c3);
            preg_match_all("/(\t)/", $rbody, $m_c4);
            preg_match_all("/(\s)/", $rbody, $m_c5);

            //Parano : Je sais que j'aurais pu ne mettre que \s
            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            $istr = ["dir","curl"];
            if ( !empty($v) && in_array($k, $istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "dir" && !in_array(strtolower($v), ["fst","top","btm"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
        }
        
    }
    
    private function OnlyMyDatas ($datas) {
//    private function OnlyMyDatas ($datas,$ids) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, $datas, TRUE);
//        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$datas,$ids], TRUE);
        
        $nds = [];
        /*
        foreach ($datas as $k => $v) {
            switch ($k) {
                case "XRCT" :
                        if ( key_exists("UAT_XRCT_AD_oMA", $v) && count($v["UAT_XRCT_AD_oMA"]) ) {
                            $at__ = $this->OnlyMyDatas_Transform($v["UAT_XRCT_AD_oMA"],$ids,"UAT_XRCT_AD_oMA");
                            $nds = array_merge($nds,$at__);
//                            $nds["XRCT"]["UAT_XRCT_AD_oMA"] = $this->OnlyMyDatas_Transform($v["UAT_XRCT_AD_oMA"],$ids,"UAT_XRCT_AD_oMA");
                        }
                    break;
                default:
                    return;
            }
        }
        */
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $nds = $this->OnlyMyDatas_Transform2($datas);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        var_dump(__LINE__,"DATAS => ",$datas,"<br/>");
//        var_dump(__LINE__,"NEW_DATAS => ",$nds,"<br/>");
//        exit();
//        var_dump(__LINE__,"AVANT => ",array_column($nds,"tm"),"<br/>");
        
        //On trie les données par ordre décroissant
        usort($nds, function($a,$b){
            /*
             * [DEPUIS 08-07-15] @BOR
             */
//            echo floatval($b['tm']) - floatval($a['tm']);
            
            if ( floatval($a['tm']) === floatval($b['tm']) ) {
                return 0;
            }
            return ( floatval($a['tm']) < floatval($b['tm']) ) ? 1 : -1;
            
//            return floatval($b['tm']) - floatval($a['tm']);
            
//            return $b['art_crea_tstamp'] - $a['art_crea_tstamp'];
        });
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
//        var_dump(__LINE__,"APRES => ",array_column($nds,"tm"),"<br/>");
//        exit();
        
        return $nds;
    }
    
    private function OnlyMyDatas_Transform (&$datas,$ids,$KD) {
        /*
         * Permet d'ajouter, modifier ou supprimer des données en fonction de la clé passée en paramètre.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, [$datas,$ids,$KD], TRUE);
        
        foreach ($datas as $k => &$v) {
            switch ($KD) {
                case "UAT_XRCT_AD_oMA" : 
                     /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
                        if (! $k ) {
                            //On détruit la Notification
                            unset($v);
                            //On saute !
                            continue;
                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent de l'Article
//                        var_dump(key_exists("mst_prmlkid", $v), !empty($v["mst_prmlkid"]), $v["mst_prmlkid"]);
                        if ( key_exists("slv_prmlkid", $v) && !empty($v["slv_prmlkid"]) ) {
                            $v["slv_prmlk"] = "/f/".$v["slv_prmlkid"];
                        } else {
                            //Ce n'est pas normal !
                            $this->Ajax_Return("err","__ERR_VOL_FAILED");
                        }
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        $v["wha"] = "UAT_XRCT_AD_oMA";
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                    break;
                default :
                    return false;
            }
            
        }
        return $datas;
    }
    
    private function OnlyMyDatas_Transform2 (&$datas) {
        /*
         * Permet d'ajouter, modifier ou supprimer des données en fonction de la clé passée en paramètre.
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, $datas, TRUE);
        
        foreach ($datas as $k => &$v) {
            
            /*
             * [DEPUIS 07-07-15 19:00] @BOR
             *      Le tableau peut contenir des valeurs NULL.
             *      La conséquence est que les valeurs suivantes ne sont pas traitées et le processus s'arrete à cause de l'instruction "return FALSE".
             *      A cette date, je ne sais pas pourquoi exactement. Il peut s'agir du fait que les éléments liés à la NOTIFICATION n'existent pas.
             *      SOLUCE :
             *          (1) Quoiqu'il en soit, pour régler un bogue au niveau de certaines NOTIFICATIONS, je vérifie que le jet d'éléments à traiter est NON NULL.
             *          (2) Je le retire completement de la liste, sinon ça va faire boguer FE.
             */
            if (! ( !empty($v) && is_array($v) ) ) {
                unset($datas[$k]);
                continue;
            }
            
            /*
             * [DEPUIS 07-07-15] @BOR
             * On traite le cas des autorisations d'accès à l'Article lié (pour certains cas).
             * On le fait plus loin et à part pour des raions pratiques.
             */
            $ent_type; //[DEPUIS 12-04-16]
            switch ($v["pmr_wha"]) {
                case "UAT_XUSTG_RCT" : 
                        $ent_type = "ARTICLE";
                        $arid = $v["slvl1"];
                    break;
                case "UAT_XUSTG_MEoTSR" : 
                        $ent_type = "TESTY";
                        $arid = $v["slvl1"];
                    break;
                case "UAT_XRCT_AD_oMA" :
                case "UAT_XEVL_GOEVL_oMA" :
                case "UAT_XUSTG_ART" : 
                case "UAT_XFAV_ART_FVoMI" : 
                        $ent_type = "ARTICLE";
                        $arid = $v["slv"];
                    break;
                case "UAT_XUSTG_MEoTSM" :
                case "UAT_XTSTY_AD_SBoMTBD" :
                case "UAT_XTSTY_TSR_SBoMTSM" :
                case "UAT_XTSTY_TSL_SBoMTSM" :
                case "UAT_XREL_NWAB" :
                        $ent_type = "ACCOUNT";
                        $arid = $v["slv"];
                    break;
                case "UAT_XMTRD_NWABO" : 
                        $ent_type = "TREND";
                        $arid = $v["slv"];
                    break;
                default:
                        $v["cnrd"] = FALSE;
                    break;
            }
             
                
            if ( !empty($arid) ) {
                if ( $ent_type === "ARTICLE" ) {
                    $ART = new ARTICLE();
                    $r__ = $ART->onread_CanRead($this->KDIn["oid"], $arid, ["FAST_WAY"]);
        //            var_dump(__LINE__,$this->KDIn["oid"], $arid, $r__);
                    $v["cnrd"] = ( is_bool($r__) ) ? $v["cnrd"] = $r__ : FALSE;
                } else if ( $ent_type === "TESTY" ) {
                } else if ( $ent_type === "ACCOUNT" ) {
                } else if ( $ent_type === "TREND" ) {
                }
            }
            
            /**********************/
            
            
            switch ($v["pmr_wha"]) {
                case "UAT_XRCT_AD_oMA" : 
                     /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent de l'Article
//                        var_dump(key_exists("mst_prmlkid", $v), !empty($v["mst_prmlkid"]), $v["mst_prmlkid"]);
                        if ( key_exists("slv_prmlkid", $v) && !empty($v["slv_prmlkid"]) ) {
                            $v["slv_prmlk"] = "/f/".$v["slv_prmlkid"];
                        } else {
                            //Ce n'est pas normal !
                            $this->Ajax_Return("err","__ERR_VOL_FAILED");
                        }
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $ART = new ARTICLE_TR();
                        /*
                         * [NOTE 08-04-15] @BOR
                         * ETAPE :
                         * On récupère les données de l'Article
                         * 
                         * Cette procédure est trop lourde. Il faut passer par une autre solution.
                         */
                        /*
                        $art_tab = $ART->on_read(["art_eid"=>$v["slv_eid"]]);
                        $art_tab = [
                            "aid"       => $art_tab["art_eid"],
                            "aba"       => $art_tab["aba"],
                            "aprmlk"    => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($art_tab["art_eid"]),
                            "apic"      => $art_tab["art_pic_rpath"],
                            "adesc"     => html_entity_decode($art_tab["art_desc"]),
                            "atime"     => $art_tab["art_time"],
                            "arnb"      => $art_tab["art_rnb"],
                            "aevals"    => $art_tab["art_evals"],
                            "tot"       => $art_tab["art_tot"],
                            "me"        => $art_tab["art_me"],
                            "hashs"    => $art_tab["art_hashs"],
                            "ustgs"    => $ART->onread_AcquiereUsertags_Article($art_tab["art_eid"],TRUE),
                            "atrid"     => ( key_exists("art_trd_eid", $art_tab) && isset($art_tab["art_trd_eid"]) ) ? $art_tab["art_trd_eid"] : NULL,
                            "atrtle"    => ( key_exists("art_trd_title", $art_tab) && isset($art_tab["art_trd_title"]) ) ? $art_tab["art_trd_title"] : NULL,
                            "atrhrf"    => ( key_exists("art_trd_href", $art_tab) && isset($art_tab["art_trd_href"]) ) ? $art_tab["art_trd_href"] : NULL,
                            "oid"       => $art_tab["art_oeid"],
                            "ofn"       => $art_tab["art_ofn"],
                            "ohref"     => $art_tab["art_ohref"],
                            "oppic"     => $art_tab["art_oppic_rpath"],
                            "opsd"      => $art_tab["art_opsd"],
                        ];
//                        var_dump(__LINE__,$art_tab);
//                        exit();
                        $v["a_tab"] = $art_tab;
                         //*/       
                        /*
                         * ETAPE :
                         * On récupère les données sur le commentaire
                         */
                        $r_tab = $ART->reaction_exists($v["mst_eid"]);
                        $atab = $ART->exists($v["slv_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $r_tab["react_body"],
                            "ustgs"     => $ART->onread_AcquiereUsertags_Reaction($v["mst_eid"],TRUE),
                            "hashs"     => $ART->onread_AcquiereHashs_Reaction($v["mst_eid"]),
                            /*
                             * [DEPUIS 02-05-16]
                             *      Dans ce cas 'tm' ne fait pas référence à ARTICLE. 
                             */
                            "time"      => $atab["art_cdate_tstamp"],
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XRCT_AD_oMA";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XUSTG_RCT" : 
                     /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent de l'Article
//                        var_dump(key_exists("mst_prmlkid", $v), !empty($v["mst_prmlkid"]), $v["mst_prmlkid"]);
                        if ( key_exists("slvl1_prmlkid", $v) && !empty($v["slvl1_prmlkid"]) ) {
                            $v["slv_prmlk"] = "/f/".$v["slvl1_prmlkid"];
                        } else {
                            //Ce n'est pas normal !
                            $this->Ajax_Return("err","__ERR_VOL_FAILED");
                        }
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $ART = new ARTICLE_TR();
                        /*
                         * [NOTE 08-04-15] @BOR
                         * ETAPE :
                         * On récupère les données de l'Article
                         * 
                         * Cette procédure est trop lourde. Il faut passer par une autre solution.
                         */
                        /*
                        $art_tab = $ART->on_read(["art_eid"=>$v["slv_eid"]]);
                        $art_tab = [
                            "aid"       => $art_tab["art_eid"],
                            "aba"       => $art_tab["aba"],
                            "aprmlk"    => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($art_tab["art_eid"]),
                            "apic"      => $art_tab["art_pic_rpath"],
                            "adesc"     => html_entity_decode($art_tab["art_desc"]),
                            "atime"     => $art_tab["art_time"],
                            "arnb"      => $art_tab["art_rnb"],
                            "aevals"    => $art_tab["art_evals"],
                            "tot"       => $art_tab["art_tot"],
                            "me"        => $art_tab["art_me"],
                            "hashs"    => $art_tab["art_hashs"],
                            "ustgs"    => $ART->onread_AcquiereUsertags_Article($art_tab["art_eid"],TRUE),
                            "atrid"     => ( key_exists("art_trd_eid", $art_tab) && isset($art_tab["art_trd_eid"]) ) ? $art_tab["art_trd_eid"] : NULL,
                            "atrtle"    => ( key_exists("art_trd_title", $art_tab) && isset($art_tab["art_trd_title"]) ) ? $art_tab["art_trd_title"] : NULL,
                            "atrhrf"    => ( key_exists("art_trd_href", $art_tab) && isset($art_tab["art_trd_href"]) ) ? $art_tab["art_trd_href"] : NULL,
                            "oid"       => $art_tab["art_oeid"],
                            "ofn"       => $art_tab["art_ofn"],
                            "ohref"     => $art_tab["art_ohref"],
                            "oppic"     => $art_tab["art_oppic_rpath"],
                            "opsd"      => $art_tab["art_opsd"],
                        ];
//                        var_dump(__LINE__,$art_tab);
//                        exit();
                        $v["a_tab"] = $art_tab;
                         //*/       
                        /*
                         * ETAPE :
                         * On récupère les données sur le commentaire
                         */
                        $r_tab = $ART->reaction_exists($v["slv_eid"]);
                        $atab = $ART->exists($v["slvl1_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $r_tab["react_body"],
                            "ustgs"     => $ART->onread_AcquiereUsertags_Reaction($v["slv_eid"],TRUE),
                            "hashs"     => $ART->onread_AcquiereHashs_Reaction($v["slv_eid"]),
                            /*
                             * [DEPUIS 02-05-16]
                             *      Dans ce cas 'tm' ne fait pas référence à ARTICLE. 
                             */
                            "time"      => $atab["art_cdate_tstamp"],
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour l'identifiant de l'objet tertiaire
                        $v["slvl1id"] = $v["slvl1_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XUSTG_RCT";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                        //On supprime les données indésirables pour l'Article lié
                        unset($v["slvl1"]);
                        unset($v["slvl1_eid"]);
                        unset($v["slvl1_oid"]);
                        unset($v["slvl1_tm"]);
                    break;
                case "UAT_XUSTG_ART" : 
                     /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent de l'Article
//                        var_dump(key_exists("mst_prmlkid", $v), !empty($v["mst_prmlkid"]), $v["mst_prmlkid"]);
                        if ( key_exists("slv_prmlkid", $v) && !empty($v["slv_prmlkid"]) ) {
                            $v["slv_prmlk"] = "/f/".$v["slv_prmlkid"];
                        } else {
                            //Ce n'est pas normal !
                            $this->Ajax_Return("err","__ERR_VOL_FAILED");
                        }
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $ART = new ARTICLE_TR();
                        /*
                         * [NOTE 08-04-15] @BOR
                         * ETAPE :
                         * On récupère les données de l'Article
                         * 
                         * Cette procédure est trop lourde. Il faut passer par une autre solution.
                         */
                        /*
                        $art_tab = $ART->on_read(["art_eid"=>$v["slv_eid"]]);
                        $art_tab = [
                            "aid"       => $art_tab["art_eid"],
                            "aba"       => $art_tab["aba"],
                            "aprmlk"    => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($art_tab["art_eid"]),
                            "apic"      => $art_tab["art_pic_rpath"],
                            "adesc"     => html_entity_decode($art_tab["art_desc"]),
                            "atime"     => $art_tab["art_time"],
                            "arnb"      => $art_tab["art_rnb"],
                            "aevals"    => $art_tab["art_evals"],
                            "tot"       => $art_tab["art_tot"],
                            "me"        => $art_tab["art_me"],
                            "hashs"    => $art_tab["art_hashs"],
                            "ustgs"    => $ART->onread_AcquiereUsertags_Article($art_tab["art_eid"],TRUE),
                            "atrid"     => ( key_exists("art_trd_eid", $art_tab) && isset($art_tab["art_trd_eid"]) ) ? $art_tab["art_trd_eid"] : NULL,
                            "atrtle"    => ( key_exists("art_trd_title", $art_tab) && isset($art_tab["art_trd_title"]) ) ? $art_tab["art_trd_title"] : NULL,
                            "atrhrf"    => ( key_exists("art_trd_href", $art_tab) && isset($art_tab["art_trd_href"]) ) ? $art_tab["art_trd_href"] : NULL,
                            "oid"       => $art_tab["art_oeid"],
                            "ofn"       => $art_tab["art_ofn"],
                            "ohref"     => $art_tab["art_ohref"],
                            "oppic"     => $art_tab["art_oppic_rpath"],
                            "opsd"      => $art_tab["art_opsd"],
                        ];
//                        var_dump(__LINE__,$art_tab);
//                        exit();
                        $v["a_tab"] = $art_tab;
                         //*/       
                        /*
                         * ETAPE :
                         * On récupère les données sur le commentaire
                         */
                        $a_tab = $ART->exists($v["slv_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $a_tab["art_desc"],
                            "ustgs"     => $ART->onread_AcquiereUsertags_Article($v["slv_eid"],TRUE),
                            "hashs"     => $ART->onread_AcquiereHashs_Article($v["slv_eid"]),
                            /*
                             * [DEPUIS 02-05-16]
                             *      Dans ce cas 'tm' ne fait pas référence à ARTICLE. 
                             */
                            "time"      => $a_tab["art_cdate_tstamp"],
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XUSTG_ART";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XEVL_GOEVL_oMA" : 
                     /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent de l'Article
//                        var_dump(key_exists("mst_prmlkid", $v), !empty($v["mst_prmlkid"]), $v["mst_prmlkid"]);
                        if ( key_exists("slv_prmlkid", $v) && !empty($v["slv_prmlkid"]) ) {
                            $v["slv_prmlk"] = "/f/".$v["slv_prmlkid"];
                        } else {
                            //Ce n'est pas normal !
                            $this->Ajax_Return("err","__ERR_VOL_FAILED");
                        }
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $evlps = [
                            1 => "_SP",
                            2 => "_CL",
                            3 => "_DL"
                        ];
                        $atab = $ART->exists($v["slv_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $evlps[intval($v["evtp"])],
                            "ustgs"     => $ART->onread_AcquiereUsertags_Article($v["slv_eid"],TRUE),
                            "hashs"     => $ART->onread_AcquiereHashs_Article($v["slv_eid"]),
                            /*
                             * [DEPUIS 02-05-16]
                             *      Dans ce cas 'tm' fait référence à EVAL. 
                             *      Il faut donc récupérer manuellement la donnée.
                             */
                            "time"      => $atab["art_cdate_tstamp"],
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
//                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XEVL_GOEVL_oMA";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
//                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                        unset($v["evtp"]);
                    break;
                case "UAT_XUSTG_MEoTSM" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent 
                        
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $TST = new TESTY();
                        /*
                         * ETAPE :
                         * On récupère les données de l'Article
                         */
                        $tst_tab = $TST->exists($v["slv_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $tst_tab["tst_msg"],
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($v["slv_eid"],TRUE),
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($v["slv_eid"])
                        ];
                        
                        $tsotab = $PA->exists_with_id($tst_tab["tst_ouid"]);
                        if (! $tsotab ) {
                            continue;
                        }
                        $tstgtab = $PA->exists_with_id($tst_tab["tst_tguid"]);
                        $v["xdatas"] = [
                            "i"         => $tst_tab["tst_eid"],
                            "tm"        => $tst_tab["tst_adddate_tstamp"],
                            "m"         => html_entity_decode($tst_tab["tst_msg"]),
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
                            "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst_tab["tst_tguid"]) ) ? TRUE : FALSE,
                            /*
                             * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                             * cgap : CanGetAccesstoPin
                             */
                            "cgap"      => ( $this->KDIn["oid"] && ( intval($this->KDIn["oid"]) === intval($tsotab["pdaccid"]) || intval($this->KDIn["oid"]) === intval($tst_tab["tst_tguid"]) ) ) ? TRUE : FALSE,
                            //QUESTION ? Le TESTIMONY est-il PIN ?
                            "isp"       => $TST->Pin_IsPin($tst_tab["tstid"]),
                            "rnb"       => $TST->React_Count($tst_tab["tst_eid"]),
                            /*
                             * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                             * NOTE :
                             *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                             */
//                                "clk"       => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                            "clk"       => TRUE,
                            //QUESTION ? L'utilisateur a t-il LIKE ?
                            "hslk"      => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tst_tab["tstid"]) ) ? TRUE : FALSE,
                            //QUESTION ? Le nombre de LIKE ?
                            "cnlk"      => $TST->Like_Count($tst_tab["tst_eid"]),
                            //Données sur les USERTAGS
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst_tab["tst_eid"],TRUE),
                            //Données sur les HASHTAGS
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($tst_tab["tst_eid"])
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XUSTG_MEoTSM";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XUSTG_MEoTSR" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent 
                        
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $TQR = new TRENQR();
                        $TST = new TESTY();
                        /*
                         * ETAPE :
                         *      On récupère les données sur le commentaire
                         */
                        $tst_tab = $TST->exists($v["slvl1_eid"]);
                        $tsr_tab = $TST->React_Tsrc_Exists_With_Id($v["slv"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $tsr_tab["pdrct_text"],
                            "ustgs"     => $TQR->pdreact_getUsertags($tsr_tab['pdrct_eid'],TRUE),
                            "hashs"     => $TQR->pdreact_getHashs($tsr_tab['pdrct_eid']),
                        ];
                        
                        $tsotab = $PA->exists_with_id($tst_tab["tst_ouid"]);
                        if (! $tsotab ) {
                            continue;
                        }
                        $tstgtab = $PA->exists_with_id($tst_tab["tst_tguid"]);
                        $v["xdatas"] = [
                            "i"         => $tst_tab["tst_eid"],
                            "tm"        => $tst_tab["tst_adddate_tstamp"],
                            "m"         => html_entity_decode($tst_tab["tst_msg"]),
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
                            "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst_tab["tst_tguid"]) ) ? TRUE : FALSE,
                            /*
                             * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                             * cgap : CanGetAccesstoPin
                             */
                            "cgap"      => ( $this->KDIn["oid"] && ( intval($this->KDIn["oid"]) === intval($tsotab["pdaccid"]) || intval($this->KDIn["oid"]) === intval($tst_tab["tst_tguid"]) ) ) ? TRUE : FALSE,
                            //QUESTION ? Le TESTIMONY est-il PIN ?
                            "isp"       => $TST->Pin_IsPin($tst_tab["tstid"]),
                            "rnb"       => $TST->React_Count($tst_tab["tst_eid"]),
                            /*
                             * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                             * NOTE :
                             *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                             */
//                                "clk"       => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                            "clk"       => TRUE,
                            //QUESTION ? L'utilisateur a t-il LIKE ?
                            "hslk"      => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tst_tab["tstid"]) ) ? TRUE : FALSE,
                            //QUESTION ? Le nombre de LIKE ?
                            "cnlk"      => $TST->Like_Count($tst_tab["tst_eid"]),
                            //Données sur les USERTAGS
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst_tab["tst_eid"],TRUE),
                            //Données sur les HASHTAGS
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($tst_tab["tst_eid"])
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour l'identifiant de l'objet tertiaire
                        $v["slvl1id"] = $v["slvl1_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XUSTG_MEoTSR";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                        //On supprime les données indésirables pour l'Article lié
                        unset($v["slvl1"]);
                        unset($v["slvl1_eid"]);
                        unset($v["slvl1_oid"]);
                        unset($v["slvl1_tm"]);
                    break;
                case "UAT_XTSTY_AD_SBoMTBD" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent 
                        
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $TST = new TESTY();
                        /*
                         * ETAPE :
                         * On récupère les données de l'Article
                         * 
                         */
                        $tst_tab = $TST->exists($v["mst_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $tst_tab["tst_msg"],
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($v["mst_eid"],TRUE),
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($v["mst_eid"]),
                        ];
                        
                        $tsotab = $PA->exists_with_id($tst_tab["tst_ouid"]);
                        if (! $tsotab ) {
                            continue;
                        }
                        $tstgtab = $PA->exists_with_id($tst_tab["tst_tguid"]);
                        $v["xdatas"] = [
                            "i"         => $tst_tab["tst_eid"],
                            "tm"        => $tst_tab["tst_adddate_tstamp"],
                            "m"         => html_entity_decode($tst_tab["tst_msg"]),
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
                            "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst_tab["tst_tguid"]) ) ? TRUE : FALSE,
                            /*
                             * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                             * cgap : CanGetAccesstoPin
                             */
                            "cgap"      => ( $this->KDIn["oid"] && ( intval($this->KDIn["oid"]) === intval($tsotab["pdaccid"]) || intval($this->KDIn["oid"]) === intval($tst_tab["tst_tguid"]) ) ) ? TRUE : FALSE,
                            //QUESTION ? Le TESTIMONY est-il PIN ?
                            "isp"       => $TST->Pin_IsPin($tst_tab["tstid"]),
                            "rnb"       => $TST->React_Count($tst_tab["tst_eid"]),
                            /*
                             * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                             * NOTE :
                             *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                             */
//                                "clk"       => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                            "clk"       => TRUE,
                            //QUESTION ? L'utilisateur a t-il LIKE ?
                            "hslk"      => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tst_tab["tstid"]) ) ? TRUE : FALSE,
                            //QUESTION ? Le nombre de LIKE ?
                            "cnlk"      => $TST->Like_Count($tst_tab["tst_eid"]),
                            //Données sur les USERTAGS
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst_tab["tst_eid"],TRUE),
                            //Données sur les HASHTAGS
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($tst_tab["tst_eid"])
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XTSTY_AD_SBoMTBD";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XTSTY_TSR_SBoMTSM" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent 
                        
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $TQR = new TRENQR();
                        $TST = new TESTY();
                        /*
                         * ETAPE :
                         *      On récupère les données sur le commentaire
                         */
                        $tst_tab = $TST->exists($v["slv_eid"]);
                        $tsr_tab = $TST->React_Exists_With_Id($v["mst"]);
                        
                        $v["pvw_tab"] = [
                            "pvwbody"   => $tsr_tab["pdrct_text"],
                            "ustgs"     => $TQR->pdreact_getUsertags($tsr_tab['pdrct_eid'],TRUE),
                            "hashs"     => $TQR->pdreact_getHashs($tsr_tab['pdrct_eid']),
                        ];
                        
                        $tsotab = $PA->exists_with_id($tst_tab["tst_ouid"]);
                        if (! $tsotab ) {
                            continue;
                        }
                        $tstgtab = $PA->exists_with_id($tst_tab["tst_tguid"]);
                        $v["xdatas"] = [
                            "i"         => $tst_tab["tst_eid"],
                            "tm"        => $tst_tab["tst_adddate_tstamp"],
                            "m"         => html_entity_decode($tst_tab["tst_msg"]),
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
                            "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst_tab["tst_tguid"]) ) ? TRUE : FALSE,
                            /*
                             * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                             * cgap : CanGetAccesstoPin
                             */
                            "cgap"      => ( $this->KDIn["oid"] && ( intval($this->KDIn["oid"]) === intval($tsotab["pdaccid"]) || intval($this->KDIn["oid"]) === intval($tst_tab["tst_tguid"]) ) ) ? TRUE : FALSE,
                            //QUESTION ? Le TESTIMONY est-il PIN ?
                            "isp"       => $TST->Pin_IsPin($tst_tab["tstid"]),
                            "rnb"       => $TST->React_Count($tst_tab["tst_eid"]),
                            /*
                             * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                             * NOTE :
                             *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                             */
//                                "clk"       => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                            "clk"       => TRUE,
                            //QUESTION ? L'utilisateur a t-il LIKE ?
                            "hslk"      => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tst_tab["tstid"]) ) ? TRUE : FALSE,
                            //QUESTION ? Le nombre de LIKE ?
                            "cnlk"      => $TST->Like_Count($tst_tab["tst_eid"]),
                            //Données sur les USERTAGS
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst_tab["tst_eid"],TRUE),
                            //Données sur les HASHTAGS
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($tst_tab["tst_eid"])
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour l'identifiant de l'objet tertiaire
                        $v["slvl1id"] = $v["slvl1_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XTSTY_TSR_SBoMTSM";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XTSTY_TSL_SBoMTSM" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent 
                        
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $TST = new TESTY();
                        /*
                         * ETAPE :
                         * On récupère les données de l'Article
                         */
                        $tst_tab = $TST->exists($v["slv_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"   => $tst_tab["tst_msg"],
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($v["slv_eid"],TRUE),
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($v["slv_eid"]),
                        ];
                        
                        $tsotab = $PA->exists_with_id($tst_tab["tst_ouid"]);
                        if (! $tsotab ) {
                            continue;
                        }
                        $tstgtab = $PA->exists_with_id($tst_tab["tst_tguid"]);
                        $v["xdatas"] = [
                            "i"         => $tst_tab["tst_eid"],
                            "tm"        => $tst_tab["tst_adddate_tstamp"],
                            "m"         => html_entity_decode($tst_tab["tst_msg"]),
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
                            "cdl"       => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tst_tab["tst_tguid"]) ) ? TRUE : FALSE,
                            /*
                             * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                             * cgap : CanGetAccesstoPin
                             */
                            "cgap"      => ( $this->KDIn["oid"] && ( intval($this->KDIn["oid"]) === intval($tsotab["pdaccid"]) || intval($this->KDIn["oid"]) === intval($tst_tab["tst_tguid"]) ) ) ? TRUE : FALSE,
                            //QUESTION ? Le TESTIMONY est-il PIN ?
                            "isp"       => $TST->Pin_IsPin($tst_tab["tstid"]),
                            "rnb"       => $TST->React_Count($tst_tab["tst_eid"]),
                            /*
                             * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                             * NOTE :
                             *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                             */
//                                "clk"       => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                            "clk"       => TRUE,
                            //QUESTION ? L'utilisateur a t-il LIKE ?
                            "hslk"      => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tst_tab["tstid"]) ) ? TRUE : FALSE,
                            //QUESTION ? Le nombre de LIKE ?
                            "cnlk"      => $TST->Like_Count($tst_tab["tst_eid"]),
                            //Données sur les USERTAGS
                            "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst_tab["tst_eid"],TRUE),
                            //Données sur les HASHTAGS
                            "hashs"     => $TST->onread_AcquiereHashs_Testy($tst_tab["tst_eid"])
                        ];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XTSTY_TSL_SBoMTSM";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XREL_NWAB" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        $PA = new PROD_ACC();
                        $utab = $PA->exists_with_id($v["act_uid"]);
                        //Ajout du lien permanent (Le lien vers le compte de ACTOR
                        $v["slv_prmlk"] = "/".$utab["pdacc_upsd"];
                        
                        //Ajout de l'image de profil de ACTOR
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XREL_NWAB";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XMTRD_NWABO" :
                        /** AJOUT DE DONNÉES **/
                        /*
                         * On s'assure que la Notification fait partie du lot des x Notifications les plus récentes.
                         * RAPPEL : 
                         * Chaque notification fait partie d'un groupe dont le nombre maximum est limité.
                         * Cependant, si on additionne toutes ces Notifications, on dépasse la limite générale.
                         * Aussi, le tableau ids est là pour nous indiquer qu'elles données font partie des x les plus récents 
                         */
//                        $k = array_search($v["pmrid"], array_column($ids,"pmrid"));
//                        if (! $k ) {
//                            //On détruit la Notification
//                            unset($v);
//                            //On saute !
//                            continue;
//                        }
                        
                    /** AJOUT DE DONNÉES **/
                        $PA = new PROD_ACC();
                        $TR = new TREND();
                        $trtab = $TR->exists($v["slv_eid"]);
                        //Ajout du lien permanent (Le lien vers le compte de ACTOR
                        $v["slv_prmlk"] = $TR->on_read_build_trdhref($v["slv_eid"],$trtab["trd_title_href"]);
                        
                        //Ajout de l'image de profil de ACTOR
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XMTRD_NWABO";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                    break;
                case "UAT_XFAV_ART_FVoMI" : 
                     /** AJOUT DE DONNÉES **/
                        
                    /** AJOUT DE DONNÉES **/
                        //Ajout du lien permanent de l'Article
//                        var_dump(key_exists("mst_prmlkid", $v), !empty($v["mst_prmlkid"]), $v["mst_prmlkid"]);
                        if ( key_exists("slv_prmlkid", $v) && !empty($v["slv_prmlkid"]) ) {
                            $v["slv_prmlk"] = "/f/".$v["slv_prmlkid"];
                        } else {
                            //Ce n'est pas normal !
                            $this->Ajax_Return("err","__ERR_VOL_FAILED");
                        }
                        //Ajout de l'image de profil de l'acteur
                        $PA = new PROD_ACC();
                        $v["act_uppic"] = $PA->onread_acquiere_pp_datas($v["act_uid"])["pic_rpath"];
                        
                        $evlps = [
                            "PUBLIC"    => "_PU",
                            "PRIVATE"   => "_PRI",
                        ];
                        $atab = $ART->exists($v["slv_eid"]);
                        $v["pvw_tab"] = [
                            "pvwbody"       => $atab["art_desc"],
                            "pvwbody_xtra"  => $evlps[$v["fvtp"]],
                            "ustgs"         => $ART->onread_AcquiereUsertags_Article($v["slv_eid"],TRUE),
                            "hashs"         => $ART->onread_AcquiereHashs_Article($v["slv_eid"]),
                            "time"          => $atab["art_cdate_tstamp"],
                        ];
                        
                        
                    /** MODIFICATION DE DONNÉES **/
                        //L'identifiant de la Notification
                        $v["id"] = $v["pmrid"];
                        //La date de pull : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_pull"] = ( $v["tm_pull"] ) ? TRUE : FALSE;
                        }
                        //La date de lecture : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_rgr"] = ( $v["tm_rgr"] ) ? TRUE : FALSE;
                        }
                        //La date de visite : La date est remplacée par un simple booléen pour éviter de la sortir.
                        if ( key_exists("tm_vstd", $v) ) {
                            $v["tm_vstd"] = ( $v["tm_vstd"] ) ? TRUE : FALSE;
                        }
                        //Changement de clé pour l'identifiant de l'acteur
                        $v["actid"] = $v["act_ueid"];
                        //Changement de clé pour l'identifiant de l'objet principal
//                        $v["mstid"] = $v["mst_eid"];
                        //Changement de clé pour l'identifiant de l'objet secondaire
                        $v["slvid"] = $v["slv_eid"];
                        //Changement de clé pour wha
                        $v["wha"] = "UAT_XFAV_ART_FVoMI";
                    
                    /** SUPRESSION DES DONNÉES **/
                        //Identifiants de l'acteur
                        unset($v["act_uid"]);
                        unset($v["act_ueid"]);
                        //Identifiants de l'objet principal
                        unset($v["mst"]);
//                        unset($v["mst_eid"]);
                        //Identifiants de l'objet secondaire
                        unset($v["slv"]);
                        unset($v["slv_eid"]);
                        //Identifiant pour lien permanent de l'objet principal
                        if ( key_exists("mst_prmlkid", $v) ) {
                            unset($v["mst_prmlkid"]);
                        }
                        //Identifiant pour lien permanent de l'objet secondaire
                        if ( key_exists("slv_prmlkid", $v) ) {
                            unset($v["slv_prmlkid"]);
                        }
                        unset($v["pmr_wha"]);
                        unset($v["fvtp"]);
                    break;
                default :
                    return FALSE;
            }
            
        }
        return $datas;
    }
    
    private function PullReports () {
        $this->DoesItComply_Datas();
        
        $dir    = $this->KDIn["datas"]["dir"];
        $fm     = $this->KDIn["datas"]["fm"];
        $rgrs   = $this->KDIn["datas"]["rgrs"];
        $plds   = $this->KDIn["datas"]["plds"];
        $curl   = $this->KDIn["datas"]["curl"];
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $PM = new POSTMAN();
        
        /*
         * ETAPE : 
         * Si FE a envoyé des données en ce qui concerne des Notifications qui ont été lus, on commence par les traités.
         * En effet, cette notion est très importante dans le cas présent où on est sur un produit multisession.
         */
        if ( !empty($rgrs) ) {
//            $t__ = json_decode($rgrs);
//            var_dump(">>> LINE : ",__LINE__,$rgrs);
//            exit();
            if (! ( is_array($rgrs) && count($rgrs) ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            } else {
                $t__ = $PM->onupdate_ntfyRogers($this->KDIn["oid"],$rgrs);
//                var_dump(__LINE__,$t__);
                if ( !$t__ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__)  ) {
                    $this->Ajax_Return("err","__ERR_VOL_FAILED");
                }
            }
        }
               
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        /*
         * ETAPE : 
         *      Si FE a envoyé des données en ce qui concerne (date pull) des accusés reception pour les Notifications.
         * RAPPEL : On laisse le soin a FE de fournir la date pour des soucis de precision.
         *      En ce qui concrne la fiabilité ou la sécurité, je ne peux pas me prononcer.
         */
        if ( !empty($plds) ) {
//            $t__ = json_decode($plds);
//            var_dump(__LINE__,$plds);
            if (! ( is_array($plds) && count($plds) ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            } else {
                $t__ = $PM->onupdate_ntfyPulleds($this->KDIn["oid"],$plds);
//                var_dump(__LINE__,$t__);
                if ( !$t__ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__)  ) {
                    $this->Ajax_Return("err","__ERR_VOL_PRLY_FAILED");
                }
            }
        }
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        /*
         * ETAPE :
         *      On vérifie et récupère les données relatives à d'eventuelles nouvelles Notifications. 
         *      Les données se présentent sous la forme d'un tableau qui contient les Notifications. 
         *      Les Notifications sont groupées par "type" au sein du tableau. 
         * 
         * NOTES et RAPPELS : 
         *      (1) L'option WFEO permet de se passer d'un filtre de données. Les données dans le tableau sont certifiés "declassified".
         *      (2) Les données ont toutes les informations necessaires pour que FE adopte le comportement de signalement adéquat. 
         *          Pour cela, nous fournissons l'information en ce qui concerne le "type".
         */
        $reports = $grpcn = NULL;
        $tp__ = $d___ = [];
        
        //Vérificaition pour les Commentaires
        $tp__["XRCT"] = $PM->onexistscreate_reactions($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $tp__["XUSTG"] = $PM->onexistscreate_usertags($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $tp__["XEVAL"] = $PM->onexistscreate_evals($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //TODO : Vérification pour les Evals
        /*
         * [DEPUIS 10-04-16]
         */
        //TESTIES & CO SCOPE (Les cas des Usertags est géré par la fonction USERTAG
        $tp__["XTSTY"] = $PM->onexistscreate_testy($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $tp__["XTSR"] = $PM->onexistscreate_testy_reactions($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $tp__["XTSL"] = $PM->onexistscreate_testy_like($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //RELATION
        $tp__["XUREL"] = $PM->onexistscreate_relation_followers($this->KDIn["oid"],1,TRUE);
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //TREND
        $tp__["XTRAB"] = $PM->onexistscreate_trend_follower($this->KDIn["oid"],1,TRUE);
        //*/
        /*
         * [DEPUIS 31-07-16]
         */
        //TREND
        $tp__["XFVAR"] = $PM->onexistscreate_fav_art($this->KDIn["oid"],1,TRUE);
        
//        sleep(5);
        
//        var_dump(__LINE__,$tp__);
//         exit();
//        var_dump(__LINE__,strtolower($dir));
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $postdatas;
        if ( strtolower($dir) === "fst" ) {
           /*
            * ETAPE :
            * On récupère les x Notifications les plus récentes. Ces données vont permettre de trier le tableau par ordre chronomogique.
            * Ce travail est effectué par OnlyMyDatas() afin de faciliter la tâche à FE et permettre une totale maitrise des données auniveau du serveur
            */
   //         $ids = $PM->onread_NtfyNewest($this->KDIn["oid"]);
            $postdatas = $PM->onread_NtfyNewest($this->KDIn["oid"], 2, TRUE);
        } else if ( strtolower($dir) === "top" ) {
            $postdatas = $PM->onread_NtfyFrom($this->KDIn["oid"], $fm["i"], $fm["t"], "top", 2, TRUE);
        } else if ( strtolower($dir) === "btm" ) {
            $postdatas = $PM->onread_NtfyFrom($this->KDIn["oid"], $fm["i"], $fm["t"], "btm", 2, TRUE);
        }
        
//         var_dump(__FUNCTION__,__LINE__,$postdatas);
//         exit(); 
         
         $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
         
         if ( $postdatas ) {

            $reports = ( $postdatas && count($postdatas) ) ? $this->OnlyMyDatas($postdatas) : NULL;
   //         $reports = $this->OnlyMyDatas($t__,$ids);

            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);

   //        var_dump($reports);
   //        exit();
        
         }
         
       /*
        * ETAPE :
        *       On récupère les données de nombre de Notifications par type.
        *       Si on n'a aucune information à ce sujet, on renvoie un tableau par défaut.
        */
        $grpcn = $PM->onread_AllUnRgrGrpCount($this->KDIn["oid"],["test_force_null_datas"=>FALSE]);

        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $this->KDOut["FE_DATAS"] = [
            "ds"    => $reports,
            "gcn"   => $grpcn
        ];
        
//        var_dump(__LINE__,"FINAL DATAS => ",$this->KDOut["FE_DATAS"]);
//        exit();
        
    }

    /****************** END SPECFIC METHODES ********************/
    
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        //exit(); //TEMP 06-09-15
        @session_start();
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
//        var_dump(__LINE__,session_get_cookie_params(),session_id());
//        exit();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "plds"  : (PuLleDS) Liste des Notifications qui ont été déclaré comme transmis. Il s'agit d'une donnée de connfirmation.
         *            RAPPEL : 
         *              (1) La donnée "plds" peut être vide.  
         * "rgrs"  : (RoGeRS) Il s'agit d'une liste des identifiants des Notifications qui ont été vues par l'utilisateur.
         *           Les données sont envoyées sous forme d'un tableau au format JSON. En effet, les identifiants sont accompagnés de la date de lecture.
         *           RAPPEL : 
         *              (1) La donnée "rgrs" peut être vide.  
         *              (2) Si la Notification n'a pas de donnée "PULLED" mais a une donnée "RoGeRS", cette dernière pourra prendre la valeur "plds". 
         * "curl"  : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["plds","rgrs","dir","fm","curl"];

        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        } else if ( count(array_diff(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ( $in_datas_keys as $k => $v ) {
            if (!( isset($v) && $v != "" )) {
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
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }

        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid,TRUE);

        if (! $exists ) {
            $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
        }

        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();

        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->PullReports();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],TRUE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        $PM = new POSTMAN();
//        $r__ = $PM->UserActyLog_FeedTestDatas(106,10);
//        var_dump($r__);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>