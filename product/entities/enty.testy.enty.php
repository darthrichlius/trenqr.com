<?php

class TESTY extends PROD_ENTITY {
    private $tstid;
    private $tst_eid;
    private $tst_msg;
    private $tst_prmlk;
    private $tst_ucap;

    private $tst_list_hash;
    private $tst_list_usertags;
    private $tst_list_urlics;
    
    private $tst_date; 
    private $tst_tstamp;

    private $tst_locip;
    private $tst_ssid;
    private $tst_uagnt;

    private $tst_ouid;
    private $tst_ougid;
    private $tst_oueid;
    private $tst_oufn;
    private $tst_oupsd;
    private $tst_ouppicid;
    private $tst_ouppic;
    private $tst_ouhref;

    private $tst_tguid;
    private $tst_tgugid;
    private $tst_tgueid;
    private $tst_tgufn;
    private $tst_tgupsd;
    private $tst_tguppicid;
    private $tst_tguppic;
    private $tst_tguhref;
    
    /************* RULES *************/
    private $_TST_MSG_RGX;
    private $_TST_CONF_INI;
    
    private $_TST_GET_FST_DLFTLMT;
    private $_TST_GET_BTM_DLFTLMT;
    
    private $_TST_CONF_DFLT_WCADD;
    private $_TST_CONF_DFLT_WCSEE;
    
    private $_TST_CONF_DNY_INI_TYPE;
    
    private $_TST_XTRA_ACTION;
        
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["tstid","tst_eid","tst_msg","tst_prmlk","tst_ucap","tst_list_hash","tst_list_usertags","tst_list_urlics","tst_ssid","tst_locip","tst_uagent","tst_adddate","tst_adddate_tstamp","tst_ouid","tst_ougid","tst_oueid","tst_oufn","tst_oupsd","tst_ouppicid","tst_ouppic","tst_ouhref","tst_tguid","tst_tgugid","tst_tgueid","tst_tgufn","tst_tgupsd","tst_tguppicid","tst_tguppic","tst_tguhref"];
        $this->needed_to_loading_prop_keys = ["tstid","tst_eid","tst_msg","tst_prmlk","tst_ucap","tst_list_hash","tst_list_usertags","tst_list_urlics","tst_ssid","tst_locip","tst_uagent","tst_adddate","tst_adddate_tstamp","tst_ouid","tst_ougid","tst_oueid","tst_oufn","tst_oupsd","tst_ouppicid","tst_ouppic","tst_ouhref","tst_tguid","tst_tgugid","tst_tgueid","tst_tgufn","tst_tgupsd","tst_tguppicid","tst_tguppic","tst_tguhref"];
        $this->needed_to_create_prop_keys = ["ouid","tguid","msg","ssid","locip","uagent"];
        
        /***************************** RULES *****************************/
        
//        $this->_TST_MSG_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,1000}$/i"; //[DEPUIS 08-07-16]
        $this->_TST_MSG_RGX = "/^[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,1000}$/i";
        $this->_TST_CONF_INI = [
            "EVRBDY"            => 1,
            //AMIS et LEURS AMIS
            "ONLY_FRD_N_THFRD"  => 2,
            "ONLY_FRD_N_FLWR"   => 21, //[DEPUIS 16-05-16]
            "ONLY_FRD"          => 3,
            //Les CONTACTS : Ceux que je suis et qui le suivent
            "ONLY_CNTCT"        => 4,
            //Les KNOWLEDGE : Ceux que je suis
            "ONLY_KNWLDG"       => 5,
            //Les FOLLOWERS : Ceux qui me suivent
            "ONLY_FLWR"         => 6,
            "TQR_INSD"          => 7,
            "TQR_OTSD"          => 8
        ];
//        $this->_TST_GET_FST_DLFTLMT = 3;
        $this->_TST_GET_FST_DLFTLMT = 2;
        $this->_TST_GET_BTM_DLFTLMT = 15;
        
        $this->_TST_CONF_DFLT_WCADD = [ "ONLY_FRD_N_THFRD" => 2];
        $this->_TST_CONF_DFLT_WCSEE = [ "EVRBDY" => 1];
        
        $this->_TST_CONF_DNY_INI_TYPE = [
            "WCNTADD" => 1,
            "WCNTSEE" => 2
        ];
        
        /*
         * [DEPUIS 24-11-15] @author BOR
         */
        $this->default_dbname = ( defined("WOS_MAIN_HOST") && !in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) ? "tqr_product_vb1_prod" : "tqr_product_vb1";
        
        /*
         * [DEPUIS 08-12-15] @author BOR
         */
        $this->_TST_XTRA_ACTION = [
            "TST_XA_GOLK"   => 1,
            "TST_XA_GOULK"  => 2,
            "TST_XA_GOPN"   => 3,
            "TST_XA_GOUPN"  => 4
        ];
        
    }

    protected function build_volatile($args) {
        
    }

    public function exists($eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $eid);
        
        $QO = new QUERY("qryl4testyn2");
        $params = array(":tst_eid" => $eid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function exists_with_id($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $id);
        
        $QO = new QUERY("qryl4testyn1");
        $params = array(":tstid" => $id);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function exists_with_prmlk ($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $id);
        
        $QO = new QUERY("qryl4testyn12");
        $params = array(":id" => $id);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }

    protected function init_properties($datas) {
        /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
                
        foreach($datas as $k => $v) {
            
            $$k = $v;
            if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
            }
            /*
             * On vérifie que toutes les données obligatoires pour l'initialisation des propriétés de la classe sont déclarées.
             * NOTE : On ne vérifie que les clés.
             */
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT =>",array_keys($datas)],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",  array_diff($this->needed_to_loading_prop_keys, array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            /*
            else 
            {
                /*
                 * Ces données doivent être NON NULLES. En effet, elles ne peuvent être NULL
                 *
                if ( in_array($k, $this->needed_to_loading_prop_keys) && empty($datas[$k]) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$datas[$k],'v_d');
                    $this->signalError ("err_sys_l4comn", __FUNCTION__, __LINE__,TRUE);
                }
            }
            */
            /*
             * On vérifie que les données entrantes sont attendues.
             * NOTE : On ne vérifie que les clés.
             */
            if (! in_array($k, $this->prop_keys) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,"KEY => ".$k,'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->prop_keys,'v_d');
                $this->signalError ("err_sys_l4comn3", __FUNCTION__, __LINE__,TRUE);
            } 
            
            $this->all_properties[$k] = $this->$k = $datas[$k];
            
        }
    }
    
    protected function load_entity($args, $std_err_enbaled = FALSE) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args, TRUE);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $tst_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("tst_eid", $args) && !empty($args["tst_eid"]) ) ) 
        {
            if ( empty($this->tst_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else { 
                $tst_eid = $this->tst_eid;
            }
        } else { $tst_eid = $args["tst_eid"]; }
        
        // On controle si l'occurrence existe et on récupèrre les données (notamment accid)
        $exists = $this->exists($tst_eid);
        if ( ( !$exists ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$exists ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $ouid = $exists["tst_ouid"];
        $tguid = $exists["tst_tguid"];
        $tstdom = $exists;
        
        $PA = new PROD_ACC();
        $exists = $PA->exists_with_id($ouid,TRUE);
        if ( !$exists && $std_err_enbaled ) {
            $this->signalError ("err_sys_l4comaccn1", __FUNCTION__, __LINE__);
        } else if ( !$exists && !$std_err_enbaled ) {
            return "__ERR_VOL_USER_GONE";
        }
        $owner = $exists;
        $exists = $PA->exists_with_id($tguid,TRUE);
        if ( !$exists && $std_err_enbaled ) {
            $this->signalError ("err_sys_l4comaccn1", __FUNCTION__, __LINE__);
        } else if ( !$exists && !$std_err_enbaled ) {
            return "__ERR_VOL_USER_GONE";
        }
        $target = $exists;
        
        $loads = [
            "tstid"             => $tstdom["tstid"],
            "tst_eid"           => $tstdom["tst_eid"],
//            "tst_msg"           => $tstdom["tst_msg"],
            /*
             * [DEPUIS 05-02-16]
             */
            "tst_msg"           => $tstdom["tst_msg"],
            "tst_prmlk"         => $tstdom["tst_prmlk"],
            "tst_ucap"          => $tstdom["tst_ucap"],
            "tst_ssid"          => $tstdom["tst_ssid"],
            "tst_locip"         => $tstdom["tst_locip"],
            "tst_uagent"        => $tstdom["tst_uagnt"],
            "tst_adddate"       => $tstdom["tst_adddate"],
            "tst_adddate_tstamp" => $tstdom["tst_adddate_tstamp"],
            
            //Données sur l'OWNER
            "tst_ouid"          => $owner["pdaccid"],
            "tst_ougid"         => $owner["pdacc_gid"],
            "tst_oueid"         => $owner["pdacc_eid"],
            "tst_oufn"          => $owner["pdacc_ufn"],
            "tst_oupsd"         => $owner["pdacc_upsd"],
            "tst_ouppicid"      => $PA->onread_acquiere_pp_datas($owner["pdacc_eid"])["picid"],
            "tst_ouppic"        => $PA->onread_acquiere_pp_datas($owner["pdacc_eid"])["pic_rpath"],
            "tst_ouhref"        => "/".$owner["pdacc_upsd"],
            //Données sur la TARGET
            "tst_tguid"         => $target["pdaccid"],
            "tst_tgugid"        => $target["pdacc_gid"],
            "tst_tgueid"        => $target["pdacc_eid"],
            "tst_tgufn"         => $target["pdacc_ufn"],
            "tst_tgupsd"        => $target["pdacc_upsd"],
            "tst_tguppicid"     => $PA->onread_acquiere_pp_datas($target["pdacc_eid"])["picid"],
            "tst_tguppic"       => $PA->onread_acquiere_pp_datas($target["pdacc_eid"])["pic_rpath"],
            "tst_tguhref"       => "/".$target["pdacc_upsd"]
        ];
      
        
        $tstid = $loads['tstid'];
        
        if ( !count($loads) ) 
        { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else 
        {
            //*
            $r;
            $extras = ["tst_list_hash","tst_list_usertags","tst_list_urlics"];
            foreach ( $extras as $v ) {
                $r = $this->load_entity_extras_datas($tstid, $loads["tst_eid"], $v);
                if ( !isset($r) || $r === 0 ) {
                    $loads[$v] = NULL;
                    
                    //On signale l'erreur si on est en mode DEBUG
                    $er = $this->get_or_signal_error (1, "err_sys_l4comn7", __FUNCTION__, __LINE__, TRUE);
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$er,$v,$r], 'v_d');
                } else {
                    $loads[$v] = $r;
                }
            }
            //*/
            
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
        
    }

    public function on_create_entity($args) {
//        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : "ouid","tguid","msg","ssid","locip","uagent"
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !( in_array($k,["uagent"]) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        
        /*
         * [NOTE] :
         *      On laisse la charge à l'utilisateur de vérifier les permissions afin de limiter les dépendances 
         */
//        if ( $args["ouid"] === $args["tguid"] ) {
//            return "__ERR_VOL_SAME_PROTAS";
//        }
        
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $otab = $PACC->exists($args["ouid"],TRUE);
        $tgtab = $PACC->exists($args["tguid"],TRUE);
        
        /*
         * ETAPE : 
         * On s'assure de manière stricte que les utilisateurs existent.
         */
        if (! $otab ) {
            return "__ERR_VOL_OWNR_GONE";
        }
        if (! $tgtab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        $args["ouid"] = $otab["pdaccid"];
        $args["tguid"] = $tgtab["pdaccid"];
        
        $errs = [];
        /*
         * ETAPE :
         *      Vérification de la validité du texte
         * TODO :
         *      Extraire les URLS
         */
        $kws = $usertags = NULL;
        $nmsg = $this->oncreate_treat_msg($args["msg"], $usertags, $kws);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$nmsg);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nmsg) ) {
            $errs[]["msg"] = $nmsg;
        }
        $omsg = $args["msg"];
        $args["msg"] = $nmsg;
        
        
//        var_dump(__LINE__,__FUNCTION__,$nmsg,$usertags, $kws);
//        exit();
        
        /*
         * ETAPE :
         *  On vérifie que les champs sont valides
         */
        if ( count($errs) ) {
            return ["__ERR_VOL_MULTIPLE",$errs];
        }
        
        /* 
         * On crée le lien Fav
         * */
        $tst_infos = $this->write_new_in_database($args);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tst_infos);
//        exit();
        
        $tstid = $tst_infos["tstid"];
        $tst_eid = $tst_infos["tst_eid"];
        
        /*
         * [DEPUIS 17-11-15] @author
         *      On lance l'opération d'entregistrement des UrlInContent.
         *      S'il n'y a pas d'URL, la méthode renvera FALSE.
         */
        $TXH = new TEXTHANDLER();
        if ( $TXH->ExtractURLs($omsg) ) {
            $URLIC = new URLIC();
            $args_urlic = [
                "t"     => $omsg,
                "uci"   => $tstid,
                "ucei"  => $tst_eid,
                "ucp"   => "UCTP_TESTY",
                "ssid"  => $args["ssid"],
                "locip" => $args["locip"],
                "curl"  => NULL,
                "uagnt" => $args["uagent"]
            ];
            $r = $URLIC->URLIC_oncreate($args_urlic["t"], $args_urlic["uci"], $args_urlic["ucei"], $args_urlic["ucp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
//            var_dump(__LINE__,__FUNCTION__,__FILE__,$r);
        }
        
        /*
         * [DEPUIS 17-11-15]
         *      On traite le cas des mot-clés
         */
        if ( $kws ) {
            $HVIEW = new HVIEW();
            $args_urlic = [
                "t"     => $omsg,
                "hci"   => $tstid,
                "hcei"  => $tst_eid,
                "hcp"   => "HCTP_TESTY",
                "ssid"  => $args["ssid"],
                "locip" => $args["locip"],
                "curl"  => NULL,
                "uagnt" => $args["uagent"]
            ];
            $kws_r = $HVIEW->HSH_oncreate($args_urlic["t"], $args_urlic["hci"], $args_urlic["hcei"], $args_urlic["hcp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
            /*
             * NOTE 
             *      On ne controle pas la valeur retour.
             *      L'objectif étant de ne pas arreter le process de création à cause de l'enregistrement des mots-clés.
             */
//            var_dump(__LINE__,__FUNCTION__,$kws_r);
        }
        
        /*
         * [DEPUIS 17-11-15]
         *      On traite le cas des USERTAGs
         * 
         * [NOTE]
         *      Ce code provient a été adapté de "enty.article.enty.php"
         *      Pour plus de précision en ce concerne les accents dans les usertags, se reporter aux commentaires dans le fichier cité.
         */
        if ( $usertags ) {
            $a = $usertags[1];
            
            /*
             * ETAPE :
             *      On transforme en LOWERCASE();
             */
            array_walk($a,function(&$i,$k){
                $i = strtolower($i);
            });
            
            /*
             * ETAPE :
             *      On supprime les doublons
             */
            $list_utags = array_unique($a);
            
            //Pour chaque pseudo, nous allons vérifier qu'il s'agit belle et bien d'un pseudo valide
            $PA = new PROD_ACC();
            foreach ($list_utags as $psd) {
                $utag_tab = $PA->exists_with_psd($psd,TRUE,TRUE);
                if ( $utag_tab ) {
                    /*
                     * ETAPE :
                     * On lance la procédure de création du tag au niveau de la base de données.
                     * On procède dans un premier temps à l'enregistrement puis à la mise à jour.
                     */
                    $now = round(microtime(TRUE)*1000);
                    
                    $QO = new QUERY("qryl4ustgn1");
                    $params = array(
                        /*
                         * [DEPUIS 13-08-16]
                         */
                        ":us_eid"   => $now, 
                        ":tgtuid"   => $utag_tab["pdaccid"], 
                        ":datecrea" => date("Y-m-d G:i:s",($now/1000)), 
                        ":tstamp"   => $now
                    );  
                    $id = $QO->execute($params);
                    
                    /*
                     * On procède à la mise à jour en insérant l'identifiant externe
                     */
                    $QO = new QUERY("qryl4ustgn2");
                    $params = array(
                        ":id"   => $id, 
                        ":eid"  => $this->entity_ieid_encode($now, $id));  
                    $QO->execute($params);
                        
                    /*
                     * On insère l'occurrence dans la classe fille dédiée à TESTY
                     */
                    $QO = new QUERY("qryl4ustg_tstn1");
                    $params = array(":id" => $id, ":tstid" => $tstid);  
                    $QO->execute($params);
                }
                
            }
            
        }
        
        //On load l'instance
        return $this->load_entity(["tst_eid" => $tst_eid]);
    }
    
    protected function on_alter_entity($args) { }
    
    public function on_delete_entity($eid) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $eid, TRUE);
        
        //On vérifie que l'occurrence existe
        $exists = $this->exists($eid); //AME : Fait perdre du temps pour rien !
        if ( $exists ) {
            /*
             * ETAPE :
             *      On supprime les occurrences dans la table PINTAB
             * 
             * [DEPUIS 13-12-15]
             *      La suppression des PIN datas avec la requête "qryl4testyn5" est liée à la version tqr.vb2.0.
             *      Cependant, la fonctionnalité de PIN, n'a été introduite qu'à partir de la version tqr.vb2.1.
             *      Aussi, on utilise la nouvelle requête ... L'objectif étant aussi de ne pas modifier l'ancien code et se focaliser sur le nouveau.
             */
//            $QO = new QUERY("qryl4testyn5");
            $QO = new QUERY("qryl4testypinn6");
            $params = array( ":id" => $exists["tstid"]);  
            $tstid = $QO->execute($params);
            
            /*
             * ETAPE :
             *      On supprime les occurences au niveau de la table LIKETAB
             */
            $QO = new QUERY("qryl4testyliken6");
            $params = array( ":id" => $exists["tstid"]);  
            $tstid = $QO->execute($params);
            
            /*
             * [EDPUIS 21-06-16]
             * ETAPE :
             *      On supprime les occurences au niveau de la table TSR
             */
            $this->React_Del_All($eid,$exists["tstid"],FALSE);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $deleted) ) {
                $this->Ajax_Return("err","__ERR_VOL_PARTIAL_FAILED");
            }
            
            /*
             * ETAPE :
             *      On supprime les occurrences dans la table USERTAGS
             */
            $QO = new QUERY("qryl4ustg_tstn3");
            $qparams = array(":tstid" => $exists["tstid"]);  
            $QO->execute($qparams);
            
            /*
             * ETAPE :
             *      On supprime les occurrences dans la table TESTIES
             */
            $QO = new QUERY("qryl4testyn6");
            $params = array( ":id" => $exists["tstid"]);  
            $tstid = $QO->execute($params);
            
            return TRUE;
        } else {
            return "__ERR_VOL_TST_GONE";
        }
    }

    public function on_read_entity($args) {
        $tst_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("tst_eid", $args) && !empty($args["tst_eid"]) ) ) 
        {
            if ( empty($this->tst_eid) ) {
                return;
            } else {
                $tst_eid = $this->tst_eid;
            }
        } else {
            $tst_eid = $args["tst_eid"];
        }
        
        //On vérifie que l'occurrence existe
        $exists = $this->exists($tst_eid); //AME : Fait perdre du temps pour rien !
        if ( $exists ) {
            $loads = $this->load_entity($args);
            return $loads;
        } else {
            return "__ERR_VOL_TST_GONE";
        }
    }

    protected function write_new_in_database($args) {
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $PA = new PROD_ACC();
        
        $QO = new QUERY("qryl4testyn3");
        $params = array(
            ":tst_msg"              => $args["msg"],
            ":tst_ucap"             => $PA->onread_updatedCapitalFor($args["ouid"],["AQAP"]),
            ":tst_locip"            => $args["locip"],
            ":tst_ssid"             => $args["ssid"],
            ":tst_uagnt"            => $args["uagent"],
            ":tst_ouid"             => $args["ouid"],
            ":tst_tguid"            => $args["tguid"],
            ":tst_adddate"          => $date,
            ":tst_adddate_tstamp"   => $time
        );  
        $tstid = $QO->execute($params);     
        
//        var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$tstid);
//        exit();
        
        //Créer tst_eid
        $tst_eid = $this->entity_ieid_encode($time, $tstid);
        
        //Insérer tst_eid
        $QO = new QUERY("qryl4testyn4");
        $params = array(":tstid" => $tstid, ":tst_eid" => $tst_eid);  
        $datas = $QO->execute($params);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        
        $tst_prmlk = $this->oncreate_EncodePrmlk($tst_eid);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        //Insérer tst_eid
        $QO = new QUERY("qryl4testyn11");
        $params = array(
            ":tstid"        => $tstid, 
            ":tst_eid"      => $tst_eid, 
            ":tst_prmlk"    => $tst_prmlk
        );  
        $datas = $QO->execute($params);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        
        /*
         * [ RAPPEL ] 
         *      Il faudra que CRON puisse archiver les TESTIES.
         */
        
        //Création TST_INFOS
        $tst_infos = NULL;
        $tst_infos["tstid"] = $tstid;
        $tst_infos["tst_eid"] = $tst_eid;
        $tst_infos["tst_prmlk"] = $tst_prmlk;
        
        return $tst_infos;
    }
    
    /***************************************************************************************************************************************************************************************/
    /*********************************************************************************** SPECIFICS SCOPE ***********************************************************************************/
    /***************************************************************************************************************************************************************************************/
    
    /**************************************************************** ONLOAD SCOPE (START) ****************************************************************/
    
    private function load_entity_extras_datas ($tstid, $tst_eid, $k) {
        /*
         * Permet de load les autres données necessaires pour load l'Entity. 
         * La méthode peut servir lorsqu'on a un tableau de extras_keys et qu'on veut les charger les (extras) acquerir les uns après les autres. 
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
            
        switch($k) {
            case "tst_list_hash" :
                return $this->onload_tst_list_hash($tst_eid);
            case "tst_list_usertags" :
                return $this->onload_tst_list_usertags($tstid);
            case "tst_list_urlics" :
                return $this->onload_tst_list_urlics($tst_eid);
            default:
                return 0;
        }
    }
    
    private function onload_tst_list_hash ($tst_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $list = NULL;
        
        /*
         * Récupérer la liste des Hashtags
         * [NOTE 17-11-15]
         *      On utilisera TOUJOURS $tst_eid car il est unique dans la table quand tstid peut porter à confusion.
         */
        $QO = new QUERY("qryl4hviewn12");
        $qparams_in_values = array(":eid" => $tst_eid);  
        $datas = $QO->execute($qparams_in_values);
        
        if ( $datas ) {
            foreach ($datas as $v) {
                $list[] = $v["hic_gvnhsh"];
            }
        }
        
        return $list;
    }
    
    private function onload_tst_list_urlics ($tst_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $list = NULL;
        
        /*
         * Récupérer la liste des URLICS
         * [NOTE 17-11-15]
         *      On utilisera TOUJOURS $tst_eid car il est unique dans la table quand tstid peut porter à confusion.
         */
        $QO = new QUERY("qryl4urlicn5");
        $qparams_in_values = array(":eid" => $tst_eid);  
        $datas = $QO->execute($qparams_in_values);
        
        if ( $datas ) {
            foreach ($datas as $v) {
                $list[] = $v["uic_gvnurl"];
            }
        }
        
        return $list;
    }
    
    private function onload_tst_list_usertags ($tstid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        //Récupérer la liste des Usertags
        $QO = new QUERY("qryl4ustg_tstn2");
        $qparams_in_values = array(
            ":tstid" => $tstid
        );  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
    }
    
    
    /**************************************************************** ONCREATE SCOPE (START) ****************************************************************/
    
    private function oncreate_treat_msg ($s, &$usertags = NULL, &$kws = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        /*
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
        if ( is_string($s) && !preg_match($this->_TST_MSG_RGX,$s) ) {
            return "__ERR_VOL_MSG_MSM";
        }
        
        $TH = new TEXTHANDLER();
        
        //On extrait les hashstags si le texte en comporte
        $kws = $TH->extract_prod_keywords($s);
        //On extrait les usertags si le texte en comporte
        $usertags = $TH->extract_tqr_usertags($s);
        
        
        $ns = $TH->secure_text($s);
        
        /*
         * [DEPUIS 05-02-16]
         *      On convertit les éventuels EMOJIS en une correspondance HTML.
         */
        $ns = $TH->replace_emojis_in($ns);
        
        return $ns;
    }
    
    //Permet de vérifier si un utilisateur a la permission d'accéder à l'opération de création ou qu'il en respecte les contraintes.
    public function oncreate_hasPermission ($cu_ueid, $tg_ueid, $cu_ico = FALSE, $tg_ico = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cu_ueid,$tg_ueid]);
        
        /*
         * ETAPE :
         *      On vérifie que les utilisateur existent.
         */
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $cutab = $PACC->exists($cu_ueid,TRUE);
        $tgtab = $PACC->exists($tg_ueid,TRUE);
        
        /*
         * ETAPE : 
         *      On s'assure de manière stricte que les utilisateurs existent.
         */
        if (! $cutab ) {
            return "__ERR_VOL_CU_GONE";
        }
        if (! $tgtab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        
        /*
         * ETAPE :
         *      Sélection du cas dans lequel nous sommes :
         *      CAS 1 : L'utilisateur veut écrire un mot sur son tableau
         *      CAS 2 : Un utilisateur veut écrire un mot sur le tableau de la cible. Il faut identifier leur relation.
         */
        $case;
        if ( $cutab["pdacc_eid"] === $tgtab["pdacc_eid"] ) {
            /*
             * [DEPUIS 17-05-16]
             *      Nous devons éviter de complexifier la compréhension de la plateforme.
             */
            return TRUE;
            
            /*
             * ETAPE :
             *      On vérifie que l'utilisateur a un Capital qui lui permet d'ajouter la publication.
             *      En effet, à chaque publication l'utilisateur perd un nombre x de capital
             */
            $cap = $PACC->onread_updatedCapitalFor($cutab["pdaccid"],["AQAP"]);
            if ( intval($cap) > 0 ) {
                return TRUE;
            }
            
            /*
             * ETAPE :
             *      On vérifie le nombre de publications ajoutés sans Capital.
             * [NOTE]
             *      La règle fonctionnelle veut que nous autorisons l'utilisateur à ajouter un nombre défini de messages quand il n'a pas de capital.
             *      Cette règle permet à l'utilisateur de pouvoir utiliser pleinement la plateforme dès la création de son compte sans trop de contraintes.
             *      Par la suite, l'objectif est de l'encourager à être plus actif. En effet, pour avoir un capital, il faudrait qu'il ajoute au moins une publication.
             *      Enfin cette règle est plus douce que celle qui avait été choisie au départ, retirant des points à l'utilisateur pour chaque message sur son TalkBoard.
             *      Cela avait un caractère punitif et restrictif.
             */
            $QO = new QUERY("qryl4testyn10");
            $params = array(
                ":ouid1" => $cutab["pdaccid"], 
                ":ouid2" => $cutab["pdaccid"]
            );  
            $datas = $QO->execute($params);
            
//            var_dump($datas[0] && intval($datas[0]["cn"]) > 1);
            
            return ( $datas[0] && intval($datas[0]["cn"]) >= 10  ) ? FALSE : TRUE;
            
        } else {
            $RL = new RELATION();
            $case = $RL->onread_relation_exists_fecase($cutab["pdaccid"],$tgtab["pdaccid"]);
//            $n__ = $RL->encode_relcode($r__);
        } 
        /*
         * ETAPE :
         *      On récupère la CONF
         */
        $cftab = $this->config_exists($tgtab["pdaccid"]);
        
        /*
         * ETAPE :
         *      On récupère la règle
         */
        if ( $cftab ) {
            $wcadd = $cftab["tst_cnf_wcadd"];
            $flipped = array_flip($this->_TST_CONF_INI);
            $wcadd = $flipped[$wcadd];
        } else {
            $wcadd = array_keys($this->_TST_CONF_DFLT_WCADD)[0];
        }
        
//        var_dump(__LINE__,__FUNCTION__,$case,$wcadd);
//        exit();
        
        /*
         * ETAPE : 
         *      On vérifie si l'utilisateur n'est pas "persona non grata"
         */
        
        
        /*
         * ETAPE :
         *      On décide en fonction du cas en présence
         */
        $hsprm = FALSE;
        
        /*
         * ETAPE :
         *      Vérifier si l'utilisateur est bloqué pour l'action de manière individuelle
         */
        if ( $this->config_check_denyfor($cutab["pdacc_eid"],$tgtab["pdacc_eid"],"WCNTADD") ) {
            return FALSE;
        }
        
        /*
         * De manière groupée
         */
        switch ($wcadd) {
            case "EVRBDY" :
                    $hsprm = TRUE;
                break;
            case "ONLY_FRD_N_THFRD" :
//                    var_dump(__LINE__,__FUNCTION__,( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) ),( $RL->friend_commons_friends_list($cutab["pdaccid"],$tgtab["pdaccid"]) ));
                    if ( ( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) ) || ( $RL->friend_commons_friends_list($cutab["pdaccid"],$tgtab["pdaccid"]) ) ) 
                    {
                        $hsprm = TRUE;
                    } 
                break;
            /*
             * [DEPUIS 17-05-16]
             */
            case "ONLY_FRD_N_FLWR" :
                    $hsprm = ( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) | $case === "_REL_CFO" ) ? TRUE : FALSE;
                break;
            case "ONLY_FRD" :
                    $hsprm = ( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) ) ? TRUE : FALSE;
                break;
            case "ONLY_CNTCT" :
                    $hsprm = ( $case === "_REL_FEO" ) ? TRUE : FALSE;
                break;
            case "ONLY_KNWLDG" :
//                    $hsprm = ( $case === "_REL_CFO" ) ? TRUE : FALSE; //[DEPUIS 17-05-16]
                    $hsprm = ( $case === "_REL_OFC" ) ? TRUE : FALSE;
                break;
            case "ONLY_FLWR" :
//                    $hsprm = ( $case === "_REL_OFC" ) ? TRUE : FALSE; //[DEPUIS 17-05-16]
                    $hsprm = ( $case === "_REL_CFO" ) ? TRUE : FALSE;
                break;
            case "TQR_INSD" :
                    $hsprm = ( $cu_ico ) ? TRUE : FALSE;
                break;
            case "TQR_OTSD" :
                    $hsprm = (! $cu_ico ) ? TRUE : FALSE;
                break;
            default:
                break;
        }
        
       return $hsprm;
        
    }

    
    private function oncreate_EncodePrmlk ($aeid) {
        /*
         * Permet de génrer un identifiant alétoire unique à partir de l'identifiant externe de l'Article.
         * Grace à l'unicité de l'identifiant externe, on peut s'assurer que PerMalink restera unique.
         * Pour s'assurer de l'unicité de cet identifiant, on teste s'il n'est pas déjà présent dans la base de données.
         * Dans ce dernier cas, on réessait de créer un autre identifiant.
         * 
         * [21-02-2015] @Loukz
         * La vérification est assurée par CALLER
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $aeid);
        
        if (! is_string($aeid) ) {
            return;
        }
        /*
         * [NOTE 21-02-2015] @Louks
         *      On ne prend que les majuscule pour s'assurer qu'il y en ait dans la chaine.
         *      L'idenfiant externe ne contient que des minuscules en ce qui concerne les lettres.
         */
        $salt = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_";
        $aeid = str_split($aeid);
        $aeid = array_reverse($aeid);
        
        $p = "";
        foreach ($aeid as $k => $ch) {
            $p .= ( $k == 0) ? $ch : str_split($salt)[mt_rand(0,37)].$ch;
        }
        
        return $p;
    }
    
    
    /**************************************************************** ONCREATE SCOPE (END) ****************************************************************/
    
    
    /**************************************************************** ONDELETE SCOPE (START) **************************************************************/
    
    
    
    /**************************************************************** ONDELETE SCOPE (END) ****************************************************************/
    
    public function ondelete_hasPermission ($cu_ueid, $tst_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cu_ueid, $tst_eid]);
        
        $PA = new PROD_ACC();
        $cutab = $PA->exists($cu_ueid);
        if (! $cutab ) {
            return "__ERR_VOL_U_G";
        }
        $tstab = $this->exists($tst_eid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        return ( floatval($cutab["pdaccid"]) === floatval($tstab["tst_ouid"]) | floatval($cutab["pdaccid"]) === floatval($tstab["tst_tguid"]) ) ? TRUE : FALSE;
        
    }
    
    /**************************************************************** ONALTER SCOPE (START) **************************************************************/
    
    
    
    /**************************************************************** ONALTER SCOPE (END) ****************************************************************/
    
    
    /*************************************************************** ONREAD SCOPE (START) ***************************************************************/
    
    
    public function onread_AcquierePrmlk($tst_eid, $_ONLYID_OPT = FALSE) {
        /*
         * Permet de récupérer le permalien d'un TSM dont l'identifiant est passé en paramètre.
         * Caller peut décider de ne récupérer que l'identifiant. Pour cela il passe l'option correspondante.
         * 
         * NOTE :
         *      -> [21-03-2015] A l'origine, cette méthode a été créée pour permettre aux modèles VM d'accéder au "permalien" sans devoir modifier en profondeur la base de données.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $tst_eid);
        
        /*
         * On vérifie que l'Article existe.
         * TODO : Vérifier que l'Article n'est pas en mode de suppression
         */
        $tstab = $this->exists($tst_eid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        return ( $_ONLYID_OPT ) ? $tstab["tst_prmlk"] : $this->onread_PrmlkToHref($tstab["tst_prmlk"]);
    }
    
    public function onread_PrmlkToHref ($prmlkid, $ivid = FALSE) {
        /*
         * [NOTE 02-03-15]
         *      Permet de générer le lien permanent pour un TSM à partir de l'identifiant prmlkid fourni.
         *      Cette méthode a aussi et surtout l'avantage de centraliser la gestion de l'url pour les permaliens
         * 
         * RAPPEL : 
         *      (1) "tst_prmlk" représente un identifiant et non un lien. Il serait judicieux de le changer mais l'opportunité ne sait pas encore présentée.
         *      (2) Nous ne vérifions pas la validité de l'identifiant fourni pour des raisons de performances et fonctionnelles.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $prmlkid);
        
        $url = "/f/sts/$prmlkid";
        
        return $url;
    }
    
    /************************************** USERTAG SCOPE ****************************************/
    
    public function onread_AcquiereUsertags_Testy ($tsteid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $tsteid);
        
        /*
         * On vérifie que le TESTY existe
         */
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        } else {
            $ustgs = $this->onload_tst_list_usertags($tstab["tstid"]);
            if ( $ustgs && $_WITH_FE_OPT ) {
                array_walk($ustgs,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
        }
        return $ustgs;
    }
    
    public function onread_AcquiereHashs_Testy ($tsteid) {
        return $this->onload_tst_list_hash($tsteid);
    }
    
    /************************************** GET TESTIES FROM ****************************************/
    
    public function onread_getTesties ($tgueid, $dir, $tspi = NULL, $tspt = NULL, $lmt = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tgueid,$dir]);

        /*
         * ETAPE :
         *      On vérifie que la direction est "attendue"
         */
        if (! in_array(strtoupper($dir), ["FST","TOP","BTM"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        $dir = strtoupper($dir);
        
        if ( in_array($dir, ["TOP","BTM"]) && !( $tspi && $tspt )  ) {
            return "__ERR_VOL_DATAS_MSG";
        }
        
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $tgtab = $PACC->exists($tgueid,TRUE);
        
        /*
         * ETAPE : 
         *      On s'assure de manière stricte que l'es 'utilisateur cible existe.
         */
        if (! $tgtab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        
        /*
         * ETAPE :
         *      On vérifie que la référence est authentique
         */
        if ( $tspi && $tspt ) {
            $tsttab = $this->exists($tspi);
            if (! $tsttab ) {
                return "__ERR_VOL_REF_GONE";
            } else if ( floatval($tsttab["tst_adddate_tstamp"]) !== floatval($tspt) ) {
                return "__ERR_VOL_REF_HACK";
            }
        }
        
        
        /*
         * NOTE :
         *      On laisse la charge à CALLER de gérer les permissions pour limiter les dépendances.
         */
        if ( $dir === "FST" ) {
            $limit = ( $lmt ) ? $lmt : $this->_TST_GET_FST_DLFTLMT;
            
            $QO = new QUERY("qryl4testyn7");
            $params = array(
                ":tguid" => $tgtab["pdaccid"],
                ":limit" => $limit,
            );
        } else if ( $dir === "TOP" ) {
            $QO = new QUERY("qryl4testyn8");
            $params = array(
                ":tguid" => $tgtab["pdaccid"],
                ":refid" => $tsttab["tstid"],
                ":reftm" => $tspt
            );
        } else {
            $limit = ( $lmt ) ? $lmt : $this->_TST_GET_BTM_DLFTLMT;
            
            $QO = new QUERY("qryl4testyn9");
            $params = array(
                ":tguid" => $tgtab["pdaccid"],
                ":refid" => $tsttab["tstid"],
                ":reftm" => $tspt,
                ":limit" => $limit,
            );
        }
        $testies = $QO->execute($params);
        
//        var_dump(__LINE__,__FILE__,$testies);
//        exit();
        
        return $testies;
        
    }
   
    public function onread_hasPermission ($tg_ueid, $cu_ueid = NULL, $cu_ico = FALSE, $tg_ico = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tg_ueid]);
        
        /*
         * ETAPE :
         *      On vérifie que les utilisateurs existent.
         */
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $tgtab = $PACC->exists($tg_ueid,TRUE);
        if ( $cu_ueid ) {
            $cutab = $PACC->exists($cu_ueid,TRUE);
            if (! $cutab ) {
                return "__ERR_VOL_CU_GONE";
            }
            
            /*
             * ETAPE :
             *      Vérifier si l'utilisateur est bloqué pour l'action de manière individuelle
             */
            if ( $this->config_check_denyfor($cutab["pdacc_eid"],$tgtab["pdacc_eid"],"WCNTSEE") ) {
                return FALSE;
            }
        }
        
        
        /*
         * ETAPE : 
         *      On s'assure de manière stricte que les utilisateurs existent.
         */
        if (! $tgtab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        
        /*
         * ETAPE :
         *      Sélection du cas dans lequel nous sommes :
         *          CAS 1 : L'utilisateur est sur sa page
         *          CAS 2 : Un utilisateur est sur la page de la cible.
         */
        $case;
        if ( $cu_ueid === $tg_ueid ) {
            return TRUE;
        } else if ( $cutab ) {
            $RL = new RELATION();
            $case = $RL->onread_relation_exists_fecase($cutab["pdaccid"],$tgtab["pdaccid"]);
//            $n__ = $RL->encode_relcode($r__);
        } 
        /*
         * ETAPE :
         *      On récupère la CONF
         */
        $cftab = $this->config_exists($tgtab["pdaccid"]);
        
        /*
         * ETAPE :
         *      On récupère la règle
         */
        if ( $cftab ) {
            $wcsee = $cftab["tst_cnf_wcsee"];
            $flipped = array_flip($this->_TST_CONF_INI);
            $wcsee = $flipped[$wcsee];
        } else {
            $wcsee = array_keys($this->_TST_CONF_DFLT_WCSEE)[0];
        }
        
//        var_dump(__LINE__,__FUNCTION__,$case,$wcsee);
//        exit();
        
        /*
         * ETAPE :
         *      On décide en fonction du cas en présence
         */
        $hsprm = FALSE;
        
        /*
         * De manière groupée
         */
        switch ($wcsee) {
            case "EVRBDY" :
                    $hsprm = TRUE;
                break;
            case "ONLY_FRD_N_THFRD" :
//                    var_dump(__LINE__,__FUNCTION__,( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) ),( $RL->friend_commons_friends_list($cutab["pdaccid"],$tgtab["pdaccid"]) ));
                    if ( ( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) ) || ( $RL->friend_commons_friends_list($cutab["pdaccid"],$tgtab["pdaccid"]) ) ) 
                    {
                        $hsprm = TRUE;
                    } 
                break;
            case "ONLY_FRD" :
                    $hsprm = ( $RL->friend_theyre_friends($cutab["pdaccid"],$tgtab["pdaccid"]) ) ? TRUE : FALSE;
                break;
            case "ONLY_CNTCT" :
                    $hsprm = ( $case === "_REL_FEO" ) ? TRUE : FALSE;
                break;
            case "ONLY_KNWLDG" :
                    $hsprm = ( $case === "_REL_CFO" ) ? TRUE : FALSE;
                break;
            case "ONLY_FLWR" :
                    $hsprm = ( $case === "_REL_OFC" ) ? TRUE : FALSE;
                break;
            case "TQR_INSD" :
                    $hsprm = ( $cu_ico ) ? TRUE : FALSE;
                break;
            case "TQR_OTSD" :
                    $hsprm = (! $cu_ico ) ? TRUE : FALSE;
                break;
            default:
                break;
        }
        
       return $hsprm;
    }

    /**************************************************************** ONREAD SCOPE (END) ****************************************************************/
    
    /*************************************************************** PIN/UNPIN SCOPE ***************************************************************/
    
    public function pin_pin ($tst_eid) {
        
    }
    
    public function pin_unpin ($tst_eid) {
        
    }
    
    /*************************************************************** CONFIG SCOPE ******************************************************************/
    
    private function config_exists ($uid) {
        //Permet de créer la table de configuration si l'utilisateur n'en a pas déjà
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $QO = new QUERY("qryl4testycnfn2");
        $params = array(":uid" => $uid);  
        $cftab = $QO->execute($params);
        
        return ( $cftab ) ? $cftab[0] : FALSE;
    }
    
    private function config_create ($uid) {
        //Permet de créer la table de configuration si l'utilisateur n'en a pas déjà
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $QO = new QUERY("qryl4testycnfn1");
        $params = array(":uid" => $uid);  
        $cfi = $QO->execute($params);
        
        return $cfi;
    }
    
    public function config_get_inis ($ueid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ueid]);
        
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *      On vérifie si la table de configuration existe.
         *      Sinon, on crée la table de configuaration. Ce cas est normal dans le cas où le compte a été créé après la mise en marche du module.
         *      De plus, cela est tout aussi normal dans le cas où le ou les developpeur n'ont pas mis en place la création de la table lors de l'inscription pour diverses raisons.
         */
        $cftab = $this-> config_exists($utab["pdaccid"]);
        if (! $cftab ) {
            $cftab = $this->config_create($utab["pdaccid"]);
        }
        $cftab = $this->config_exists($utab["pdaccid"]);
        
        /*
         * ETAPE :
         *      On renvoie la table
         */
        return $cftab;
    }
    
    public function config_set_inis ($ueid, $sets, $locip, $ssid, $uagnt = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ueid,$sets,$locip,$ssid,]);
        
        /*
         * ETAPE :
         *      On vérifie que les données attendues y sont
         */
        $needed = ["WCADD","WCSEE"];
        $com  = array_intersect( array_keys($sets), $needed);
        if ( count($com) != count($needed) ) {
            return "__ERR_VOL_WRG_HCK";
        } else {
            $errs = 0;
            foreach ($sets as $k => $v) {
                if ( !( isset($v) && $v !== "" && in_array($v, array_keys($this->_TST_CONF_INI)) ) ) {
                    $errs++;
                } 
            }
            if ( $errs ) {
                return "__ERR_VOL_WRG_DATAS";
            }
        }
        
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *      On vérifie si la table de configuration existe.
         *      Sinon, on crée la table de configuaration. Ce cas est normal dans le cas où le compte a été créé après la mise en marche du module.
         *      De plus, cela est tout aussi normal dans le cas où le ou les developpeur n'ont pas mis en place la création de la table lors de l'inscription pour diverses raisons.
         */
        $cftab = $this-> config_exists($utab["pdaccid"]);
        if (! $cftab ) {
            $cftab = $this->config_create($utab["pdaccid"]);
        }
        
        $n_sets = [];
        foreach ($sets as $k => $v) {
            switch ($k) {
                case "WCADD" :
                case "WCSEE" :
                        $n_sets[$k] = $this->_TST_CONF_INI[$v];
                    break;
                default:
                    return;
            }
        }
        
        /*
         * ETAPE :
         *      On met à jour les données données
         */
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        $QO = new QUERY("qryl4testycnfn3");
        $params = array(
            ":cfid"     => $cftab["tst_cnf_id"],
            ":wcadd"    => $n_sets["WCADD"], 
            ":wcsee"    => $n_sets["WCSEE"], 
            ":locip"    => $locip, 
            ":ssid"     => $ssid, 
            ":uagnt"    => $uagnt,
            ":date"     => $date,
            ":tstamp"   => $time,
        );  
        $QO->execute($params); 
        
        /*
         * ETAPE :
         *      On récupère les données mise à jour
         */
        $cftab = $this->config_exists($utab["pdaccid"]);
        
        return $cftab;
        
    }
    
    /**
     * Les données sur les utilisateurs qui sont victimes d'une interdiction de faire une certaine action.
     * 
     * @param string $ueid L'identifiant externe de l'utilisateur
     * @return mixed {string|array} Les données sur les utilisateurs bloqués
     */
    public function config_get_denyfor ($ueid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ueid]);
        
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *      On récupère la liste des utilisateurs bloqués
         */
        $QO = new QUERY("qryl4testycnfn2_spednyfr");
        $params = array(":uid" => $utab["pdaccid"]);
        $datas = $QO->execute($params);
        
        
        /*
         * ETAPE :
         *      On renvoie la table
         */
        return $datas;
    }
    
    /**
     * Permet de vérifier si l'utilisateur passé en paramètre est dans la liste des personnes interdites pour une action
     * 
     */
    public function config_check_denyfor ($cueid,$tgueid,$action) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cueid,$tgueid,$action]);
        
        $PA = new PROD_ACC();
        $cutab = $PA->exists($cueid);
        $tgtab = $PA->exists($tgueid);
        if (! $cutab ) {
            return "__ERR_VOL_CU_GONE";
        }
        if (! $tgtab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'action est attendue
         */
        $action = strtoupper($action);
        if (! in_array($action,["WCNTADD","WCNTSEE"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        $rules = $this->_TST_CONF_DNY_INI_TYPE;
        
        /*
         * ETAPE :
         *      On récupère la liste des utilisateurs bloqués
         */
        $QO = new QUERY("qryl4testycnfn7_spednyfr");
        $params = array(
            ":cuid"     => $cutab["pdaccid"],
            ":tguid"    => $tgtab["pdaccid"],
            ":type"     => $rules[$action]
        );
        $datas = $QO->execute($params);
        
        /*
         * ETAPE :
         *      On renvoie la table
         */
        return ( $datas ) ? $datas : FALSE;
    }
    
    public function config_set_denyfor ($cu_ueid, $ini_type, $locip, $ssid, $uagnt = NULL, $sets = NULL) {
        //sets : Liste sous forme de tableau des utilisateurs à locker. Chaquer utilisateur est représenté par son pseudo
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cu_ueid,$ini_type,$locip,$ssid]);
        
        $PA = new PROD_ACC();
        $cutab = $PA->exists($cu_ueid,TRUE);
        
        /*
         * ETAPE : 
         *      On s'assure de manière stricte que les utilisateurs existent.
         */
        if (! $cutab ) {
            return "__ERR_VOL_CU_GONE";
        }
        
        /*
         * 
         */
        if (! in_array($ini_type, array_keys($this->_TST_CONF_DNY_INI_TYPE)) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        /*
         * ETAPE :
         *      Si on ne recoit rien, on annule toutes les interdictions liées à l'action.
         */
        if (! $sets ) {
            $time = round(microtime(TRUE)*1000);
            $date = date("Y-m-d G:i:s",($time/1000));
                
            $QO = new QUERY("qryl4testycnfn8_spednyfr");
            $params = array(
                ":type"     => $this->_TST_CONF_DNY_INI_TYPE[$ini_type], 
                ":locip"    => $locip,
                ":ssid"     => $ssid,
                ":uagnt"    => $uagnt,
                ":date"     => $date,
                ":tstamp"   => $time
            );
            $locked = $QO->execute($params);
            
            return TRUE;
        }
        
        
        /*
         * ETAPE :
         *      On récupère la liste des utilisateurs déjà locked.
         *      Cette liste prend en compte les occurrences terminées
         */
        $QO = new QUERY("qryl4testycnfn6_spednyfr");
        $params = array(
            ":uid1" => $cutab["pdaccid"],
            ":uid2" => $cutab["pdaccid"]
        );
        $locked = $QO->execute($params);
        
        $lk_ids = [];
        if ( $locked ) {
            $lk_ids = array_column($locked,"tcdf_id");
            $lk_uids = array_column($locked,"tcdf_tguid");
            foreach ($locked as $tb) {
                if ( $tb["tcdf_ini_type"] === 1 ) {
                    $lk_gp["WCNTADD"][] = $tb["tcdf_tguid"];
                } else if ( $tb["tcdf_ini_type"] === 2 ) {
                    $lk_gp["WCNTSEE"][] = $tb["tcdf_tguid"];
                }

            }
        }
        
        
//        var_dump(__LINE__,__FUNCTION__,$locked,$lk_ids,$lk_uids,$lk_gp);
//        exit();
        
        /*
         * ETAPE :
         *      Pour chaque utilisateur trouvé on ajoute à la liste.
         *      Puis on récupère les données de chaque utilisateurs. Ces données seront renvoyées.
         */
        foreach ($sets as $psd) {
            $tgtab = $PA->exists_with_psd($psd,TRUE);
            if ( is_array($tgtab) && $tgtab["pdaccid"] ) {
                
                if ( floatval($tgtab["pdaccid"]) === floatval($cutab["pdaccid"]) ) {
                    continue;
                }
                
                $fnl_ds[] = $tgtab;
                $fnl_ids[] = $tgtab["pdaccid"];
                
                $time = round(microtime(TRUE)*1000);
                $date = date("Y-m-d G:i:s",($time/1000));
                
                if ( !$lk_gp || !in_array($tgtab["pdaccid"], $lk_gp[$ini_type])) {
                    $QO = new QUERY("qryl4testycnfn1_spednyfr");
                    $params = array(
                        ":acuid"    => $cutab["pdaccid"],
                        ":tguid"    => $tgtab["pdaccid"], 
                        ":type"     => $this->_TST_CONF_DNY_INI_TYPE[$ini_type], 
                        ":date"     => $date,
                        ":tstamp"   => $time,
                        ":locip"    => $locip, 
                        ":ssid"     => $ssid, 
                        ":uagnt"    => $uagnt
                    );  
                    $ids[] = $QO->execute($params); 
                } 
                
            }
        }
        
//        var_dump(__LINE__,__FUNCTION__,$fnl_ids,$fnl_ds);
//        exit();
        
        $tupd_uids = array_diff($lk_uids, $fnl_ids);
        foreach ($locked as $tb) {
            if ( in_array($tb["tcdf_tguid"],$tupd_uids) ) {
                $tupd_ids[] = $tb["tcdf_id"];
            }
            
        }
//        var_dump(__LINE__,__FUNCTION__,$tupd_uids, $tupd_ids);
//        exit();
        if ( $tupd_uids ) {
            $QO = new QUERY();
            $qbody = "UPDATE TESTY_CONF_DENY_FOR";
            $qbody .= " SET tcdf_end_locip = :locip,";
            $qbody .= " tcdf_end_ssid = :ssid,";
            $qbody .= " tcdf_end_uagnt = :uagnt,";
            $qbody .= " tcdf_end_date = :date,";
            $qbody .= " tcdf_end_tstamp = :time";
            $qbody .= " WHERE tcdf_tguid IN (".implode(",",$tupd_uids).") ";
            $qbody .= " AND tcdf_id IN (".implode(",",$tupd_ids).") ";
            $qbody .= " AND tcdf_ini_type = :type ; ";
            $qdbname = $this->default_dbname;
            $qtype = "update";
            $qparams_in = array(
                ":type"     => $this->_TST_CONF_DNY_INI_TYPE[$ini_type], 
                ":locip"    => $locip, 
                ":ssid"     => $ssid, 
                ":uagnt"    => $uagnt, 
                ":date"     => $date, 
                ":time"     => $time
            );
            $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
            $QO->execute($qparams_in); 
        }
        
        
        /*
         * ETAPE :
         *      On renvoie les données
         */
        return $fnl_ds;
    }
    
    
    /***************************************************** LIKE/UNLIKE SCOPE *****************************************************/
    
    public function Like_Exists_WithID ($tslid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tslid]);
        
        $QO = new QUERY("qryl4testyliken4");
        $params = array( ":id" => $tslid );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : [];
    }
    
    public function Like_Action ($cueid, $tsteid, $action, $ssid, $locip, $uagent = NULL) {
        /*
         * [NOTE 09-12-15]
         *      Il est important que CALLER transmette ACTION car l'utilisateur peut effectuer une action sur un élément non à jour.
         *      Si on devait deviner, effectuer l'ACTION cela pourrait entrainer des problèmes de compréhension.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cueid, $tsteid, $action, $ssid, $locip]);
        
        /*
         * ETAPE :
         *      On vérifie que l'action est attendu.
         * TABLE :
         *      > TST_XA_GOLK  : TeSTy_eXtrasAction_GOLiKe
         *      > TST_XA_GOULK : TeSTy_eXtrasAction_GOUnLiKe
         */
        if ( !in_array(strtoupper($action), array_keys($this->_TST_XTRA_ACTION) ) ) {
            return "__ERR_VOL_WRG_DATAS";
        } else if ( !in_array(strtoupper($action), ["TST_XA_GOLK","TST_XA_GOULK"])  ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'élément existe et on récupère la table
         */
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$tstab);
//        exit();
        
        /*
         * ETAPE :
         *      On vérifie l'existence des différents ACTORS et on récupère la table.
         */
        $PA = new PROD_ACC();
        $cutab = $PA->exists($cueid);
        if (! $cutab ) {
            return "__ERR_VOL_CU_GONE";
        }
        $tgutab = $PA->exists_with_id($tstab["tst_tguid"]);
        if (! $tgutab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'utilisateur a le droit d'accéder au TESTY
         * 
         * [NOTE]
         *      Ne pas vérifier nous fait gagner 200ms. 
         *      Cependant, on pourrait avoir de gros problèmes de sécurité et de fiabilité sans cette vérification.
         */
        if ( intval($tstab["tst_ouid"]) !== intval($cutab["pdaccid"]) && !$this->onread_hasPermission($tgutab["pdacc_eid"],$cueid) ) {
            return "__ERR_VOL_DNY_AKX";
        }
        
        /*
         * ETAPE :
         *      On vérifie si le TESTY a une appréciation en cours
         */
        $me = $this->Like_HasLiked($cutab["pdaccid"],$tstab["tstid"],TRUE);
        
        /*
         * ETAPE :
         *      On vérifie que si ACTION fait référence a UNLIKE, il faut qu'e LIKE existe qu'il existe au moins une ACTION précedente.
         */
//        ...c'est faux : ce cas ne devrait déclencher aucune erreur. Le seul qui pourrait serait que l'utilisateur n'a JAMAIS auparavent LIKE'
        if ( strtoupper($action) === "TST_XA_GOU" && !$me ) {
            return "__ERR_VOL_AMBIGUOUS";
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$action,$this->_TST_XTRA_ACTION[$action],$me);
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$me, is_array($me), $me["tlkh_taxtid"], $this->_TST_XTRA_ACTION[$action], $me["tlkh_taxtid"] === $this->_TST_XTRA_ACTION[$action]);
//        exit();
        /*
         * ETAPE :
         *      On vérifie l'Action est différente de la précédente
         */
        if ( $me && is_array($me) && intval($me["tlkh_taxtid"]) === intval($this->_TST_XTRA_ACTION[$action]) ) {
            return $me;
        } else if ( !$me && strtoupper($action) === "TST_XA_GOULK" ) {
            return [];
        }
        
        /*
         * ETAPE :
         *      On annule l'ACTION précédente
         */
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4testyliken2");
        $params = array(
            ":id"       => $tstab["tstid"],
            ":uid"      => $cutab["pdaccid"],
            ":date"     => date("Y-m-d G:i:s",($now/1000)),
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *      On ajoute l'occurence de l'action et renvoie les données le cas échéant
         */
        $now = round(microtime(TRUE)*1000);

        $QO = new QUERY("qryl4testyliken3");
        $params = array(
            ":tstid"    => $tstab["tstid"], 
            ":uid"      => $cutab["pdaccid"],
            ":tltid"    => $this->_TST_XTRA_ACTION[$action],
            ":ssid"     => $ssid,
            ":locip"    => $locip,
            ":uagnt"    => $uagent,
            ":date"     => date("Y-m-d G:i:s",($now/1000)),
            ":tstamp"   => $now
        );
        $id = $QO->execute($params);

        /*
         * On récupère les données
         */
        $QO = new QUERY("qryl4testyliken4");
        $params = array( ":id"  => $id);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return  ( strtoupper($action) === "TST_XA_GOLK" ) ? $datas[0] : [];
        } else {
            return "__ERR_VOL_FAILED";
        }
        
    }
    
    public function Like_Count ($tsteid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tsteid]);
        
        /*
         * ETAPE :
         *      On vérifie que l'élément existe et on récupère la table
         */
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        $QO = new QUERY("qryl4testyliken5");
        $params = array( ":tstid"  => $tstab["tstid"]);
        $datas = $QO->execute($params);
        return ( $datas ) ? $datas[0]["cn"] : "__ERR_VOL_FAILED";
    }
    
    public function Like_HasLiked ($cuid, $tstid, $_GET_TAB = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid,$tstid]);
        
        $QO = new QUERY("qryl4testyliken1");
        $params = array(
            ":cuid"  => $cuid,
            ":tstid" => $tstid
        );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return ( $_GET_TAB ) ? $datas[0] : TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public function Like_Pull ($tsteid, $dir = "FST", $tsleid = NULL, $tsltm = NULL, $_WFEO = FALSE, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tsteid]);
        
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        if (! in_array($dir, ["FST","TOP","BTM"]) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( in_array($dir, ["TOP","BTM"]) && ! ( $tsleid && $tsltm ) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        
        if ( $tsleid ) {
            $tslid = $this->entity_ieid_decode($tsleid)["id"];
            $ref_tsltab = $this->Like_Exists_WithID($tslid);
            if (! $ref_tsltab ) {
                return "__ERR_VOL_TSL_REF_GONE";
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$tsltab]);
//        exit();
        
        if ( $dir === "FST" ) {
            $QO = new QUERY("qryl4testyliken7");
            $params = array(
                ":tstid" => $tstab["tstid"],
                ":limit" => 10
            );
        } else if ( $dir === "TOP" ) { //NEWER
            $QO = new QUERY("qryl4testyliken7_nwr");
            $params = array(
                ":tstid" => $tstab["tstid"],
                ":refid" => $ref_tsltab["tlkh_id"],
                ":reftm" => $tsltm,
                ":limit" => 10
            );
        } else { //OLDER
            $QO = new QUERY("qryl4testyliken7_oldr");
            $params = array(
                ":tstid" => $tstab["tstid"],
                ":refid" => $ref_tsltab["tlkh_id"],
                ":reftm" => $tsltm,
                ":limit" => 10
            );
        }
        $datas = $QO->execute($params);
        if (! $datas ) {
            return [];
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        foreach ($datas as $tsltab) {
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$tab);
//            exit();
            
            $PA = new PROD_ACC();
            $pdr_utb = $PA->exists_with_id($tsltab["tlkh_accid"]);
            
            if (! $_WFEO ) {
                
                $tsrtab[] = [
                    "tstab"     => [
                        'id'        => $tstab["tstid"],
                        'eid'       => $tstab["tst_eid"],
                        'msg'       => $tstab["tst_msg"],
                        'date'      => $tstab["tst_adddate"],
                        'tstamp'    => $tstab["tst_adddate_tstamp"],
                        'locip'     => $tstab["tst_locip"],
                        'ssid'      => $tstab["tst_ssid"],
                        'uagnt'     => $tstab["tst_uagnt"],
                        'ouid'      => $tstab["tst_ouid"],
                        'tguid'     => $tstab["tst_tguid"],
                        'ucap'      => $tstab["tst_ucap"],
                    ],
                    //tsl = TeStyLikes
                    "tsltab"    => [ 
                        'id'        => $tsltab["tlkh_id"],
                        'eid'       => $this->entity_ieid_encode($tsltab["tlkh_startdate_tstamp"], $tsltab["tlkh_id"]),
                        'is_lk'     => ( intval($tsltab["tlkh_taxtid"]) === 1 ) ? TRUE : FALSE,
                        'autab'     => [
                            '_id'   => $pdr_utb['pdaccid'],
                            'id'    => $pdr_utb['pdacc_eid'],
                            'fn'    => $pdr_utb['pdacc_ufn'],
                            'ps'    => $pdr_utb['pdacc_upsd'],
                            'pp'    => $PA->onread_acquiere_pp_datas($pdr_utb['pdaccid'])["pic_rpath"]
                        ],
                        'locip'     => $tsltab["tlkh_locip"],
                        'ssid'      => $tsltab["tlkh_ssid"],
                        'uagnt'     => $tsltab["tlkh_uagnt"],
                        'date'      => $tsltab["tlkh_startdate"],
                        'tstamp'    => $tsltab["tlkh_startdate_tstamp"],
                    ],
                ];
                
            } else {
                
                $tsrtab[] = [
                    "tstab"     => [
                        'id'        => $tstab["tst_eid"]
                    ],
                    //tsl = TeStyLikes
                    "tsltab"    => [
                        'id'        => $this->entity_ieid_encode($tsltab["tlkh_startdate_tstamp"], $tsltab["tlkh_id"]),
                        'is_lk'     => ( intval($tsltab["tlkh_taxtid"]) === 1 ) ? TRUE : FALSE,
                        'autab'     => [
                            'id'    => $pdr_utb['pdacc_eid'],
                            'fn'    => $pdr_utb['pdacc_ufn'],
                            'ps'    => $pdr_utb['pdacc_upsd'],
                            'pp'    => $PA->onread_acquiere_pp_datas($pdr_utb['pdaccid'])["pic_rpath"]
                        ],
                        'date'      => $tsltab["tlkh_startdate_tstamp"],
                    ]
                ];
                
            }
        }
        
        return $tsrtab;
    }
    
    /***************************************************** PIN/UNPIN SCOPE *****************************************************/
    
    public function Pin_Action ($cuid, $poueid, $tsteid, $action, $ssid, $locip, $uagent = NULL) {
        //po... : PageOwner...
        /*
         * [NOTE 09-12-15]
         *      Il est important que CALLER transmette ACTION car l'utilisateur peut effectuer une action sur un élément non à jour.
         *      Si on devait deviner, effectuer l'ACTION cela pourrait entrainer des problèmes de compréhension.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid, $poueid, $tsteid, $action, $ssid, $locip]);
        
        /*
         * ETAPE :
         *      On vérifie que l'action est attendu.
         * TABLE :
         *      > TST_XA_GOPN  : TeSTy_eXtrasAction_GOPiN
         *      > TST_XA_GOUPN : TeSTy_eXtrasAction_GOUnPiN
         */
        if ( !in_array(strtoupper($action), array_keys($this->_TST_XTRA_ACTION) ) ) {
            return "__ERR_VOL_WRG_DATAS";
        } else if ( !in_array(strtoupper($action), ["TST_XA_GOPN","TST_XA_GOUPN"])  ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'élément existe et on récupère la table
         */
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$tstab);
//        exit();
        
        /*
         * ETAPE :
         *      On vérifie l'existence des différents ACTORS et on récupère la table.
         */
        $PA = new PROD_ACC();
        $potab = $PA->exists($poueid);
        if (! $potab ) {
            return "__ERR_VOL_CU_GONE";
        }
        if ( intval($tstab["tst_tguid"]) === intval($potab["pdaccid"]) ) {
            $tgutab = $potab;
        } else {
            $tgutab = $PA->exists_with_id($tstab["tst_tguid"]);
            if (! $tgutab ) {
                return "__ERR_VOL_TGT_GONE";
            }
        }
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$cutab,$tgutab);
//        exit();
        /*
         * ETAPE :
         *      On vérifie que l'utilisateur courant a le droit d'effectuer l'action.
         *      A cette version (tqr.vb2.1), seule le propriétaire du compte sur lequel le TESTIMONY a été ajouté peut épingler l'élément.
         * 
         * [NOTE 10-12-15]
         *      On ne vérifie pas les autres autorisations d'accès (READ) car cela complexifierait le traitement.
         *      De plus, on considère que l'utilsateur qui écrit sur le TALKBOARD d'un autre lui "laisse la propriété" du mot tant qu'il ne le supprime pas.
         */
        if ( intval($cuid) !== intval($tgutab["pdaccid"]) ) {
            return "__ERR_VOL_DNY_AKX";
        }
        
        /*
         * ETAPE :
         *      On vérifie si le TESTY est déjà PIN
         */
        $IsP = $this->Pin_IsPin($tstab["tstid"],TRUE);
        
        /*
         * ETAPE :
         *      On vérifie que si ACTION fait référence a UNLIKE, il faut qu'e LIKE existe qu'il existe au moins une ACTION précedente.
         */
        if ( strtoupper($action) === "TST_XA_GOPN" && $IsP) {
            return $IsP;
        } else if ( strtoupper($action) === "TST_XA_GOUPN" && !$IsP ) {
            return [];  
        } 
         
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$action,$this->_TST_XTRA_ACTION[$action],$me);
//        exit();
        
        /*
         * ETAPE :
         *      On annule le ou les anciens TESTY (ce qui ne devrait pas arriver).
         */
        $now = round(microtime(TRUE)*1000);

        $QO = new QUERY("qryl4testypinn2");
        $params = array(
            ":date"     => date("Y-m-d G:i:s",($now/1000)),
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *      On ajoute l'occurence de l'action le cas échéant
         */
        if ( strtoupper($action) === "TST_XA_GOPN" ) {
            $now = round(microtime(TRUE)*1000);
        
            $QO = new QUERY("qryl4testypinn3");
            $params = array(
                ":tstid"    => $tstab["tstid"], 
                ":ssid"     => $ssid,
                ":locip"    => $locip,
                ":uagnt"    => $uagent,
                ":date"     => date("Y-m-d G:i:s",($now/1000)),
                ":tstamp"   => $now
            );
            $id = $QO->execute($params);

            /*
             * On récupère les données
             */
            $wip = $this->Pin_WhoIsPin($potab["pdaccid"]);
            return ( $wip ) ? $wip : "__ERR_VOL_FAILED";
        } else {
            return [];
        }
        
    }
    
    public function Pin_IsPin ($tstid, $_GET_TAB = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tstid]);
        
        $QO = new QUERY("qryl4testypinn1");
        $params = array( ":tstid" => $tstid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return ( $_GET_TAB ) ? $datas[0] : TRUE;
        } else {
            return FALSE;
        }
    }
    
    
    public function Pin_WhoIsPin ($cuid, $_WOD = FALSE) {
        //WOD : WithOwnerDatas
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid]);
        
        $QO = (! $_WOD ) ? new QUERY("qryl4testypinn4") : new QUERY("qryl4testypinn5");
        $params = array( ":cuid" => $cuid );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : [];
    }
    
    
    /*****************************************************************************************************************************************/
    /************************************************************ REACTIONS SCOPE ************************************************************/
    
    public function React_Exists ( $pdr_eid ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pdr_eid]);
        
        $QO = new QUERY("qryl4tstreactn6");
        $params = array(
            ":eid"    => $pdr_eid
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
    
    
    public function React_Exists_With_Id ( $pdr_id ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pdr_id]);
        
//        $QO = new QUERY("qryl4tstreactn8");
        $QO = new QUERY("qryl4tstreactn7");
        $params = array(
            ":id"    => $pdr_id
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
    
    public function React_Tsrc_Exists_With_Id ( $tsrcid ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tsrcid]);
        
        $QO = new QUERY("qryl4tstreactn8");
        $params = array(
            ":id"    => $tsrcid
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
    
    
    public function React_Read ($tstr_eid, $_WFEO = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tstr_eid]);
        
        $tstr_tab = $this->React_Exists($tstr_eid);
        if (! $tstr_tab ) {
            return FALSE;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tstr_tab);
//        exit();
        
        $tstab = $this->exists_with_id($tstr_tab["tsrc_tstid"]);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        $TQR = new TRENQR();
        $prdtab = $TQR->pdreact_read([ "id" => $tstr_tab["pdrct_id"] ]);
            
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$tstab,$prdtab]);
//        exit();
            
        $PA = new PROD_ACC();
        $pdr_outb = $PA->exists_with_id($prdtab["author"]);
        
        $tst_otab = $PA->exists_with_id($tstab["tst_ouid"]);
        $tst_tgtab = $PA->exists_with_id($tstab["tst_tguid"]);
        
        if (! $_WFEO ) {
                
            $tsrtab = [
                "tstab"     => [
                    'id'        => $tstab["tstid"],
                    'eid'       => $tstab["tst_eid"],
                    'msg'       => $tstab["tst_msg"],
                    'date'      => $tstab["tst_adddate"],
                    'tstamp'    => $tstab["tst_adddate_tstamp"],
                    'locip'     => $tstab["tst_locip"],
                    'ssid'      => $tstab["tst_ssid"],
                    'uagnt'     => $tstab["tst_uagnt"],
                    'ouid'      => $tstab["tst_ouid"],
                    'oueid'     => $tst_otab["pdacc_eid"],
                    'tguid'     => $tstab["tst_tguid"],
                    'tgueid'    => $tst_tgtab["pdacc_eid"],
                    'ucap'      => $tstab["tst_ucap"],
                ],
                "pdrtab"    => [
                    'id'        => $prdtab["id"],
                    'eid'       => $prdtab["eid"],
                    'text'      => $prdtab["text"],
                    'autab'     => [
                        '_id'   => $pdr_outb['pdaccid'],
                        'id'    => $pdr_outb['pdacc_eid'],
                        'fn'    => $pdr_outb['pdacc_ufn'],
                        'ps'    => $pdr_outb['pdacc_upsd'],
                        'pp'    => $PA->onread_acquiere_pp_datas($pdr_outb['pdaccid'])["pic_rpath"]
                    ],
                    'locip'     => $prdtab["locip"],
                    'ssid'      => $prdtab["ssid"],
                    'uagnt'     => $prdtab["uagnt"],
                    'date'      => $prdtab["date"],
                    'tstamp'    => $prdtab["tstamp"],
                    'usertags'  => $TQR->pdreact_getUsertags($prdtab['eid'],FALSE),
                    'hashtags'  => $TQR->pdreact_getHashs($prdtab['eid'],TRUE),
                    'urls_set'  => NULL
                ],
                //*
                //tsr = TeStyReaction
                "tsrtab"    => [
                    "id"    => $tstr_tab["tsrcid"],
//                    "page"  => ( $tab["tsrc_page"] ) ? array_flip(TRENQR::$TQR_PAGE)[$tab["tsrc_page"]] : null,
//                    "app"   => ( $tab["tsrc_app"] ) ? array_flip(TRENQR::$TQR_APP)[$tab["tsrc_app"]] : null,
//                    "vwr"   => ( $tab["tsrc_vwr"] ) ? array_flip(TRENQR::$TQR_VIEWER)[$tab["tsrc_vwr"]] : null
                ]
                //*/
            ];

        } else {
                
            $tsrtab = [
                "tstab"     => [
                    'id'        => $tstab["tst_eid"],
                    'oueid'     => $tst_otab["pdacc_eid"],
                    'tgueid'    => $tst_tgtab["pdacc_eid"],
                ],
                "pdrtab"    => [
                    'id'        => $prdtab["eid"],
                    'text'      => html_entity_decode($prdtab["text"]),
                    'autab'     => [
                        'id'    => $pdr_outb['pdacc_eid'],
                        'fn'    => $pdr_outb['pdacc_ufn'],
                        'ps'    => $pdr_outb['pdacc_upsd'],
                        'pp'    => $PA->onread_acquiere_pp_datas($pdr_outb['pdaccid'])["pic_rpath"]
                    ],
                    'date'      => $prdtab["tstamp"],
                    'usertags'  => $TQR->pdreact_getUsertags($prdtab['eid'],TRUE),
                    'hashtags'  => $TQR->pdreact_getHashs($prdtab['eid'],TRUE),
                    'urls_set'  => NULL,
                    'cdl'       => ( $_OPTIONS && $_OPTIONS["cuid"] 
                        && ( floatval($_OPTIONS["cuid"]) === floatval($pdr_outb['pdaccid']) | floatval($_OPTIONS["cuid"]) === floatval($tstab["tst_ouid"]) ) ) ? TRUE : FALSE
                ]
            ];

        }
        
        return $tsrtab;
    }
    
    
    public function React_Add ($cuid, $tsteid, $rtext, $pgeid, $ssid, $locip, $uagent = NULL, $appeid = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid, $tsteid, $rtext, $pgeid, $ssid, $locip]);
        
        /*
         * ETAPE :
         *      On vérifie que les données sur la LOCALISATION de l'ACTION sont exactes
         */
        /* ************************ PAGE SCOPE ************************ */
        if (! key_exists($pgeid, TRENQR::$TQR_PAGE) ) {
            return "__ERR_VOL_BAD_ACX_LOC";
        }
        $pgid = TRENQR::$TQR_PAGE[$pgeid];
        
        /* ************************ APPL SCOPE ************************ */
        if ( $appeid ) {
            if (! key_exists($appeid, TRENQR::$TQR_APP) ) {
                return "__ERR_VOL_BAD_ACX_LOC";
            }
            $appid = TRENQR::$TQR_APP[$appeid];
        } else {
            $appid = NULL;
        }
        /* ************************ VIEWER SCOPE ************************ */
        $vwrid = TRENQR::$TQR_VIEWER["UNQ_TST"];
        
        if ( is_string($rtext) && !preg_match($this->_TST_MSG_RGX,$rtext) ) {
            return "__ERR_VOL_MSG_MSM";
        }
        
        /*
         * ETAPE :
         *      On vérifie que le texte respecte les conditions d'ajout :
         *          -> MAX_LN : 1000
         */
        if ( is_string($rtext) && !preg_match($this->_TST_MSG_RGX,$rtext) ) {
            return "__ERR_VOL_MSG_MSM";
        }
        
        /*
         * ETAPE :
         *      On vérifie que le TESTY est disponible
         */
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        /*
         * ETAPE :
         *      On rassemble les données necessaires à la création du commentaire dans la table MERE
         */
        $args = [
            "prod" => [
                "author"    => $cuid,
                "text"      => $rtext,
                "locip"     => $locip, 
                "ssid"      => $ssid, 
                "uagnt"     => $uagent
            ],
            "testy" => [
                "tstid" => $tstab["tstid"],
                "pgid"  => $pgid,
                "appid" => $appid,
                "vwr"   => $vwrid,
            ]
        ];
        
        /*
         * ETAPE :
         *      Créer une occurrence au niveau de la table PDR
         */
        $TQ = new TRENQR();
        $pdrtab = $TQ->pdreact_add($args["prod"]["author"],$args["prod"]["text"],$args["prod"]["locip"],$args["prod"]["ssid"],$args["prod"]["uagnt"]);
        if (! $pdrtab ) {
            return "__ERR_VOL_FAILED";
        }
        
        
        /* 
         * ETAPE :
         *      On crée l'occurrence au niveau de la classe fille
         * 
         */
        $QO = new QUERY("qryl4tstreactn1");
        $params = array(
            ":tstid"    => $args["testy"]["tstid"],
            ":pdrid"    => $pdrtab["pdrct_id"],
            ":page"     => $args["testy"]["pgid"],
            ":app"      => $args["testy"]["appid"],
            ":vwr"      => $args["testy"]["vwr"]
        );
        $tsrid = $QO->execute($params);
        
        
        /*
         * ETAPE :
         *      On construit la table de données
         */
        $infos = [
            "tstab"     => $tstab,
            "pdrtab"    => $pdrtab,
            //tsr = TeStyReaction
            "tsrtab"    => [
                "id"    => $tsrid,
                "page"  => ( $args["testy"]["pgid"] )   ? array_flip(TRENQR::$TQR_PAGE)[$args["testy"]["pgid"]] : null,
                "app"   => ( $args["testy"]["appid"] )  ? array_flip(TRENQR::$TQR_APP)[$args["testy"]["appid"]] : null,
                "vwr"   => ( $args["testy"]["vwr"] )    ? array_flip(TRENQR::$TQR_VIEWER)[$args["testy"]["vwr"]] : null
            ]
        ];
        
        return $infos;
    }
    
    
    public function React_Del ($tstr_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tstr_eid]);
        
        $tsr_tab = $this->React_Exists($tstr_eid);
        if (! $tsr_tab ) {
            return "__ERR_VOL_TSR_GONE";
        }
        
        $TQR = new TRENQR();
        $deleted = $TQR->pdreact_delete($tsr_tab["pdrct_id"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $deleted) ) {
            $this->Ajax_Return("err",$deleted);
        }
        
        return $deleted;
    }
    
    
    public function React_Del_All ($tst_eid, $tstid = NULL, $check = TRUE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tst_eid]);
        
        if ( !$check && !$tstid ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( $check ) {
            $tst_tab = $this->Exists($tst_eid);
            if (! $tst_tab ) {
                return "__ERR_VOL_TSM_GONE";
            }
            $in_tstid = $tst_tab["tstid"];
        } else {
            $in_tstid = $tstid;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$in_tstid);
//        exit();
        
        /*
         * ETAPE :
         *      On supprime l'URLIC lié aux COMMENTAIRES
         */
        $QO = new QUERY("qryl4testydel_urlicn1");
        $qparams_in_values = array(
            ":tsmid"    => $in_tstid, 
            ":type"     => 6
        );  
        $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         *      On supprime les HASHTAGS liés aux COMMENTAIRES
         */
        $QO = new QUERY("qryl4testydel_hviewn1");
        $qparams_in_values = array(
            ":tsmid"    => $in_tstid,
            ":type"     => 6
        );  
        $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         *      On supprime les USERTAGS liés aus COMMENTAIRES
         */
        $QO = new QUERY("qryl4testydel_ustg_pdrn1");
        $qparams_in_values = array(
            ":tsmid"    => $in_tstid 
        );  
        $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         *      On supprime les occurrences de PDR (Et TESTY en cascade)
         */
        $QO = new QUERY("qryl4testydel_pdrn1");
        $qparams_in_values = array(
            ":tsmid"    => $in_tstid 
        );  
        $QO->execute($qparams_in_values);
        
        return TRUE;
    }
    
    
    public function React_Pull ($tsteid, $dir = "FST", $tsreid = NULL, $tsrtm = NULL, $_WFEO = FALSE, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tsteid]);
        
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        if (! in_array($dir, ["FST","TOP","BTM"]) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( in_array($dir, ["TOP","BTM"]) && ! ( $tsreid && $tsrtm ) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        $TQR = new TRENQR();
        if ( $tsreid ) {
            $prdtab_ref = $TQR->pdreact_exists($tsreid);
            if (! $prdtab_ref ) {
                return "__ERR_VOL_TSR_REF_GONE";
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tstab,$prdtab_ref);
//        exit();
        
        if ( $dir === "FST" ) {
            $QO = new QUERY("qryl4tstreactn2");
            $params = array(
                ":tstid" => $tstab["tstid"],
                ":limit" => 10
            );
        } else if ( $dir === "TOP" ) { //NEWER
            $QO = new QUERY("qryl4tstreactn3");
            $params = array(
                ":tstid" => $tstab["tstid"],
                ":tsrid" => $prdtab_ref["pdrct_id"],
                ":tsrtm" => $tsrtm,
                ":limit" => 10
            );
        } else { //OLDER
            $QO = new QUERY("qryl4tstreactn4");
            $params = array(
                ":tstid" => $tstab["tstid"],
                ":tsrid" => $prdtab_ref["pdrct_id"],
                ":tsrtm" => $tsrtm,
                ":limit" => 10
            );
        }
        $datas = $QO->execute($params);
        if (! $datas ) {
            return [];
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        foreach ($datas as $tab) {
            
            $prdtab = $TQR->pdreact_read([ "id" => $tab["tsrc_pdrid"] ]);
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$tab);
//            exit();
            
            $PA = new PROD_ACC();
            $pdr_utb = $PA->exists_with_id($prdtab["author"]);
            if (! $_WFEO ) {
                
                $tsrtab[] = [
                    "tstab"     => [
                        'id'        => $tstab["tstid"],
                        'eid'       => $tstab["tst_eid"],
                        'msg'       => $tstab["tst_msg"],
                        'date'      => $tstab["tst_adddate"],
                        'tstamp'    => $tstab["tst_adddate_tstamp"],
                        'locip'     => $tstab["tst_locip"],
                        'ssid'      => $tstab["tst_ssid"],
                        'uagnt'     => $tstab["tst_uagnt"],
                        'ouid'      => $tstab["tst_ouid"],
                        'tguid'     => $tstab["tst_tguid"],
                        'ucap'      => $tstab["tst_ucap"],
                    ],
                    "pdrtab"    => [
                        'id'        => $prdtab["id"],
                        'eid'       => $prdtab["eid"],
                        'text'      => $prdtab["text"],
                        'autab'     => [
                            '_id'   => $pdr_utb['pdaccid'],
                            'id'    => $pdr_utb['pdacc_eid'],
                            'fn'    => $pdr_utb['pdacc_ufn'],
                            'ps'    => $pdr_utb['pdacc_upsd'],
                            'pp'    => $PA->onread_acquiere_pp_datas($pdr_utb['pdaccid'])["pic_rpath"]
                        ],
                        'locip'     => $prdtab["locip"],
                        'ssid'      => $prdtab["ssid"],
                        'uagnt'     => $prdtab["uagnt"],
                        'date'      => $prdtab["date"],
                        'tstamp'    => $prdtab["tstamp"],
                        'usertags'  => $TQR->pdreact_getUsertags($prdtab['eid'],TRUE),
                        'hashtags'  => $TQR->pdreact_getHashs($prdtab['eid'],TRUE),
                        'urls_set'  => NULL
                    ],
                    //tsr = TeStyReaction
                    "tsrtab"    => [
                        "id"    => $tab["tsrcid"],
                        "page"  => ( $tab["tsrc_page"] ) ? array_flip(TRENQR::$TQR_PAGE)[$tab["tsrc_page"]] : null,
                        "app"   => ( $tab["tsrc_app"] ) ? array_flip(TRENQR::$TQR_APP)[$tab["tsrc_app"]] : null,
                        "vwr"   => ( $tab["tsrc_vwr"] ) ? array_flip(TRENQR::$TQR_VIEWER)[$tab["tsrc_vwr"]] : null
                    ]
                ];
                
            } else {
                
                $tsrtab[] = [
                    "tstab"     => [
                        'id'        => $tstab["tst_eid"]
                    ],
                    "pdrtab"    => [
                        'id'        => $prdtab["eid"],
                        'text'      => html_entity_decode($prdtab["text"]),
                        'autab'     => [
                            'id'    => $pdr_utb['pdacc_eid'],
                            'fn'    => $pdr_utb['pdacc_ufn'],
                            'ps'    => $pdr_utb['pdacc_upsd'],
                            'pp'    => $PA->onread_acquiere_pp_datas($pdr_utb['pdaccid'])["pic_rpath"]
                        ],
                        'date'      => $prdtab["tstamp"],
                        'usertags'  => $TQR->pdreact_getUsertags($prdtab['eid'],TRUE),
                        'hashtags'  => $TQR->pdreact_getHashs($prdtab['eid'],TRUE),
                        'urls_set'  => NULL,
                        'cdl'       => ( $_OPTIONS && $_OPTIONS["cuid"] 
                            && ( floatval($_OPTIONS["cuid"]) === floatval($pdr_utb['pdaccid']) | floatval($_OPTIONS["cuid"]) === floatval($tstab["tst_ouid"]) ) ) ? TRUE : FALSE
                    ]
                ];
                
            }
        }
        
        return $tsrtab;
    }
    
    
    public function React_Count ($tsteid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$tsteid]);
        
        /*
         * ETAPE :
         *      On vérifie que l'élément existe et on récupère la table
         */
        $tstab = $this->exists($tsteid);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        
        $QO = new QUERY("qryl4tstreactn5");
        $params = array( ":tstid"  => $tstab["tstid"]);
        $datas = $QO->execute($params);
//        return ( $datas ) ? $datas[0]["cn"] : "__ERR_VOL_FAILED"; //[DEPUIS 02-04-16]
        return ( $datas ) ? $datas[0]["cn"] : 0;
    }
    
    
    /***************************************************************************************************************************************************************************************/
    /********************************************************************************** GETTERS & SETTERS **********************************************************************************/
    /***************************************************************************************************************************************************************************************/
    
    public function getTstid() {
        return $this->tstid;
    }

    public function getTst_eid() {
        return $this->tst_eid;
    }

    public function getTst_msg() {
        return $this->tst_msg;
    }
    
    public function getTst_prmlk() {
        return $this->tst_prmlk;
    }

    public function getTst_date() {
        return $this->tst_date;
    }

    public function getTst_tstamp() {
        return $this->tst_tstamp;
    }

    public function getTst_locip() {
        return $this->tst_locip;
    }

    public function getTst_ssid() {
        return $this->tst_ssid;
    }

    public function getTst_uagnt() {
        return $this->tst_uagnt;
    }

    public function getTst_ouid() {
        return $this->tst_ouid;
    }

    public function getTst_ougid() {
        return $this->tst_ougid;
    }

    public function getTst_oueid() {
        return $this->tst_oueid;
    }

    public function getTst_oufn() {
        return $this->tst_oufn;
    }

    public function getTst_oupsd() {
        return $this->tst_oupsd;
    }

    public function getTst_ouppicid() {
        return $this->tst_ouppicid;
    }

    public function getTst_ouppic() {
        return $this->tst_ouppic;
    }

    public function getTst_ouhref() {
        return $this->tst_ouhref;
    }

    public function getTst_tguid() {
        return $this->tst_tguid;
    }

    public function getTst_tgugid() {
        return $this->tst_tgugid;
    }

    public function getTst_tgueid() {
        return $this->tst_tgueid;
    }

    public function getTst_tgufn() {
        return $this->tst_tgufn;
    }

    public function getTst_tgupsd() {
        return $this->tst_tgupsd;
    }

    public function getTst_tguppicid() {
        return $this->tst_tguppicid;
    }

    public function getTst_tguppic() {
        return $this->tst_tguppic;
    }

    public function getTst_tguhref() {
        return $this->tst_tguhref;
    }

    public function get_TST_MSG_RGX() {
        return $this->_TST_MSG_RGX;
    }

    public function get_TST_CONF_INI() {
        return $this->_TST_CONF_INI;
    }

    public function get_TST_GET_FST_DLFTLMT() {
        return $this->_TST_GET_FST_DLFTLMT;
    }

    public function get_TST_GET_BTM_DLFTLMT() {
        return $this->_TST_GET_BTM_DLFTLMT;
    }

    public function get_TST_CONF_DFLT_WCADD() {
        return $this->_TST_CONF_DFLT_WCADD;
    }

    public function get_TST_CONF_DFLT_WCSEE() {
        return $this->_TST_CONF_DFLT_WCSEE;
    }

    public function get_TST_CONF_DNY_INI_TYPE() {
        return $this->_TST_CONF_DNY_INI_TYPE;
    }


}
