<?php


class MYSTERY extends PROD_ENTITY { 
    private $mysmid;
    private $mysm_eid;
    private $mysm_text;
    private $mysm_owner;
    
    private $mysm_list_hash;

    private $mysm_creadate;
    private $mysm_creadate_tstamp;
    
    private $mysm_ssid;
    private $mysm_locip;
    private $mysm_curl;
    private $mysm_uagent;
    
    private $mysm_reflang;
    private $mysm_refcnty;
    private $mysm_refcity;
    
    private $mysm_cnvotes; 
    private $mysm_sumvotes;
    
    private $mysm_ouid;
    private $mysm_ougid;
    private $mysm_oueid;
    private $mysm_oufn;
    private $mysm_oupsd;
    private $mysm_ouppicid;
    private $mysm_ouppic;
    private $mysm_ouhref;
    
    /*****************************/
    /*********** RULES ***********/
    
    private $_MYSM_TXT_RGX;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);

        $this->prop_keys = ["mysmid","mysm_eid","mysm_text","mysm_owner","mysm_list_hash","mysm_creadate","mysm_creadate_tstamp","mysm_ssid","mysm_locip","mysm_curl","mysm_uagent","mysm_reflang","mysm_refcnty","mysm_refcity","mysm_cnvotes","mysm_sumvotes","mysm_ouid","mysm_ougid","mysm_oueid","mysm_oufn","mysm_oupsd","mysm_ouppicid","mysm_ouppic","mysm_ouhref"];
        $this->needed_to_loading_prop_keys = ["mysmid","mysm_eid","mysm_text","mysm_owner","mysm_list_hash","mysm_creadate","mysm_creadate_tstamp","mysm_ssid","mysm_locip","mysm_curl","mysm_uagent","mysm_reflang","mysm_refcnty","mysm_refcity","mysm_cnvotes","mysm_sumvotes","mysm_ouid","mysm_ougid","mysm_oueid","mysm_oufn","mysm_oupsd","mysm_ouppicid","mysm_ouppic","mysm_ouhref"];
        $this->needed_to_create_prop_keys = ["ouid","text","ssid","locip","curl","uagent","reflang","refcnty","refcity"];
        
        /***************************** RULES *****************************/
        
        $this->_MYSM_TXT_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,484}$/i";
        
        $this->sections = [
            "SEC_TQR",
            "SEC_MYCNTY",
            "SEC_MYLANG"
        ];
        $this->_DFLT_SEC = "SEC_TQR";
        
        $this->filters = [
            "DEFAULT",
            "BY_DATE",
            "BY_LIKES",
            "TODAY_BY_DATE",
            "TODAY_BY_LIKES",
        ];
        $this->_DFLT_FIL = "DEFAULT";
        
        $this->_DFLT_LMT = 10;
        
    }
    
    public function build_volatile($args) { }

    public function exists($eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $eid);
        
        $QO = new QUERY("qryl4mysmn2");
        $params = array(":mysm_eid" => $eid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function exists_with_id($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $id);
        
        $QO = new QUERY("qryl4mysmn1");
        $params = array(":mysmid" => $id);
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

    protected function load_entity($args) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args, TRUE);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $mysm_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("mysm_eid", $args) && !empty($args["mysm_eid"]) ) ) 
        {
            if ( empty($this->mysm_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else { 
                $mysm_eid = $this->mysm_eid;
            }
        } else { $mysm_eid = $args["mysm_eid"]; }
        
        // On controle si l'occurrence existe et on récupèrre les données (notamment accid)
        $exists = $this->exists($mysm_eid);
        if ( ( !$exists ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$exists ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $ouid = $exists["mysm_owner"];
        $mysmdom = $exists;
        
        $PA = new PROD_ACC();
        $exists = $PA->exists_with_id($ouid,TRUE);
        if ( !$exists && $std_err_enbaled ) {
            $this->signalError ("err_sys_l4comaccn1", __FUNCTION__, __LINE__);
        } else if ( !$exists && !$std_err_enbaled ) {
            return "__ERR_VOL_USER_GONE";
        }
        $owner = $exists;
        
        $loads = [
            "mysmid"                => $mysmdom["mysmid"],
            "mysm_eid"              => $mysmdom["mysm_eid"],
            "mysm_text"             => $mysmdom["mysm_text"],
            "mysm_ssid"             => $mysmdom["mysm_ssid"],
            "mysm_locip"            => $mysmdom["mysm_locip"],
            "mysm_uagent"           => $mysmdom["mysm_uagent"],
            "mysm_curl"             => $mysmdom["mysm_curl"],
            
            "mysm_reflang"          => $mysmdom["mysm_reflang"],
            "mysm_refcnty"          => $mysmdom["mysm_refcnty"],
            "mysm_refcity"          => $mysmdom["mysm_refcity"],
            
            "mysm_creadate"         => $mysmdom["mysm_creadate"],
            "mysm_creadate_tstamp"  => $mysmdom["mysm_creadate_tstamp"],
            
            //Données sur l'OWNER
            "mysm_owner"            => $owner["pdaccid"],
            "mysm_ouid"             => $owner["pdaccid"],
            "mysm_ougid"            => $owner["pdacc_gid"],
            "mysm_oueid"            => $owner["pdacc_eid"],
            "mysm_oufn"             => $owner["pdacc_ufn"],
            "mysm_oupsd"            => $owner["pdacc_upsd"],
            "mysm_ouppicid"         => $PA->onread_acquiere_pp_datas($owner["pdacc_eid"])["picid"],
            "mysm_ouppic"           => $PA->onread_acquiere_pp_datas($owner["pdacc_eid"])["pic_rpath"],
            "mysm_ouhref"           => "/".$owner["pdacc_upsd"]
        ];
      
        
        $mysmid = $loads['mysmid'];
        
        
//        var_dump(__FUNCTION__,__LINE__,$loads);
//        exit();
        
        if ( !count($loads) ) { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } else {
            $extras = ["mysm_list_hash","mysm_cnvotes","mysm_sumvotes"];
            foreach ( $extras as $v ) {
                $r = $this->load_entity_extras_datas($mysmid, $loads["mysm_eid"], $v);
                if ( !isset($r) ) {
                    $loads[$v] = NULL;
                    
                    //On signale l'erreur si on est en mode DEBUG
                    $er = $this->get_or_signal_error (1, "err_sys_l4comn7", __FUNCTION__, __LINE__, TRUE);
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$er,$v,$r], 'v_d');
                } else {
                    $loads[$v] = $r;
                }
            }
            
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
    }

    protected function on_alter_entity($args) { }

    public function on_create_entity($args) {
//        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : "ouid","text","ssid","locip","curl","uagent","reflang","refcnty","refcity"
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !( in_array($k,["uagent"]) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE => ",$k,$v],'v_d');
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $otab = $PACC->exists_with_id($args["ouid"],TRUE);
        if (! $otab ) {
            return "__ERR_VOL_OWNR_GONE";
        }
        
        $args["ouid"] = $otab["pdaccid"];
        
        $kws = $usertags = NULL;
        $nmsg = $this->oncreate_treat_msg($args["text"], $usertags, $kws);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$nmsg,$usertags,$kws);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nmsg) ) {
            $errs[]["msg"] = $nmsg;
        }
        $omsg = $args["text"];
        $args["text"] = $nmsg;
    
        $mysm_infos = $this->write_new_in_database($args);
        
        $mysmid = $mysm_infos["mysmid"];
        $mysm_eid = $mysm_infos["mysm_eid"];
        
        if ( $kws ) {
            $HVIEW = new HVIEW();
            $args_urlic = [
                "t"     => $omsg,
                "hci"   => $mysmid,
                "hcei"  => $mysm_eid,
                "hcp"   => "HCTP_MYSM",
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
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$kws_r);
        }
        
        //On load l'instance
        return $this->load_entity(["mysm_eid" => $mysm_eid]);
    }

    public function on_delete_entity($mm_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mm_eid]);
        
        /*
         * ETAPE :
         *      On s'assure que le MM existe et on récupère sa TABLE
         */
        $mm_tab = $this->exists($mm_eid);
        if (! $mm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        /*
         * ETAPE :
         *      On supprime les lignes faisant référence à une opération de MYSM_VOTES
         */
        $this->onvote_delall_for_msg_with_mmid($mm_tab["mysmid"]);
        
        /*
         * ETAPE :
         *      On supprime les lignes faisant référence à une opération de MYSM_DISCLOSE
         */
        $this->ondisclose_delall($mm_tab["mysmid"]);
        
        
        /*
         * ETAPE :
         *      On supprime effectivement le MYSM
         */
        $QO = new QUERY("qryl4mysmn5");
        $params = array(
            ":id"   => $mm_tab["mysmid"],
        );  
        $QO->execute($params); 
        
        return TRUE;
    }

    public function on_read_entity($args) {
        $mysm_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("mysm_eid", $args) && !empty($args["mysm_eid"]) ) ) 
        {
            if ( empty($this->mysm_eid) ) {
                return;
            } else {
                $mysm_eid = $this->mysm_eid;
            }
        } else {
            $mysm_eid = $args["mysm_eid"];
        }
        
        //On vérifie que l'occurrence existe
        $exists = $this->exists($mysm_eid); //AME : Fait perdre du temps pour rien !
        
        return ( $exists ) ? $this->load_entity($args) : "__ERR_VOL_MYSM_GONE";
    }

    protected function write_new_in_database($args) {
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4mysmn3");
        $params = array(
            ":mysm_text"            => $args["text"],
            ":mysm_locip"           => $args["locip"],
            ":mysm_ssid"            => $args["ssid"],
            ":mysm_uagent"          => $args["uagent"],
            ":mysm_curl"            => $args["curl"],
            ":mysm_reflang"         => $args["reflang"],
            ":mysm_refcnty"         => $args["refcnty"],
            ":mysm_refcity"         => $args["refcity"],
            ":mysm_ouid"            => $args["ouid"],
            ":mysm_creadate"        => $date,
            ":mysm_creadate_tstamp" => $time
        );  
        $mysmid = $QO->execute($params);     
        
//        var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$mysmid);
//        exit();
        
        //Créer mysm_eid
        $mysm_eid = $this->entity_ieid_encode($time, $mysmid);
        
        //Insérer mysm_eid
        $QO = new QUERY("qryl4mysmn4");
        $params = array(":mysmid" => $mysmid, ":mysm_eid" => $mysm_eid);  
        $datas = $QO->execute($params);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        /*
         * [ RAPPEL ] 
         *  Il faudra que CRON puisse archiver les TESTIES.
         */
        
        //Création TST_INFOS
        $mysm_infos = NULL;
        $mysm_infos["mysmid"] = $mysmid;
        $mysm_infos["mysm_eid"] = $mysm_eid;
        
        return $mysm_infos;
    }
    
    /********************************************************************************************************************************************************************************/
    /******************************************************************************* SPECIFICS SCOPE ********************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    /**************************************************************** ONLOAD SCOPE (START) ****************************************************************/
    
    private function load_entity_extras_datas ($mymid, $mym_eid, $k) {
        /*
         * Permet de load les autres données necessaires pour load l'Entity. 
         * La méthode peut servir lorsqu'on a un tableau de extras_keys et qu'on veut les charger les (extras) acquerir les uns après les autres. 
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
            
        switch($k) {
            case "mysm_cnvotes" :
                return $this->onload_mysm_cnvotes($mym_eid);
            case "mysm_sumvotes" :
                return $this->onvote_sum($mym_eid);
            case "mysm_list_hash" :
                return $this->onload_mysm_list_hash($mymid, $mym_eid);
            default:
                return 0;
        }
    }
    
    public function onload_mysm_cnvotes ($mymid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4mysvn1");
        $qparams_in_values = array( ":id"  => $mymid );  
        $datas = $QO->execute($qparams_in_values);
        
        return ( $datas ) ? $datas[0]["cnvotes"] : 0;
    }
    
    public function onload_mysm_list_hash ($mymid, $mym_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $list = NULL;
        
        $QO = new QUERY("qryl4hviewn17_MYSM");
        $qparams_in_values = array(
            ":id"   => $mymid,
            ":eid"  => $mym_eid
        );  
        $datas = $QO->execute($qparams_in_values);
        
        if ( $datas ) {
            foreach ($datas as $v) {
                $list[] = $v["hic_gvnhsh"];
            }
        }
        
        return $list;
    }
    
    
    
    /**************************************************************** ONCREATE SCOPE (START) ****************************************************************/
    
    private function oncreate_treat_msg ($s, &$usertags = NULL, &$kws = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        /*
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
        if ( is_string($s) && !preg_match($this->_MYSM_TXT_RGX,$s) ) {
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
    
    
    
    /**************************************************************** ONREAD SCOPE (START) ****************************************************************/
    
    public function onread_select ($uid, $sec = NULL, $dir = "FST", $fil = NULL, $lmt = NULL, $_OPTIONS = NULL) { 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        if ( $sec && !in_array($sec, $this->sections) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( !$sec ) {
            $sec = $this->_DFLT_SEC;
        }
        
        if ( $dir && !in_array($dir, ["FST","BTM","TOP"]) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if (! $dir ) {
            $dir = "FST";
        } else if ( in_array($dir, ["TOP","BTM","TOP"]) && !( $_OPTIONS && !empty($_OPTIONS["rfid"]) && !empty($_OPTIONS["rftm"]) ) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        if ( $fil && !in_array($fil, $this->filters) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( !$fil ) {
            $fil = $this->_DFLT_FIL;
        } 
        
        $lmt = ( $lmt ) ? $lmt : $this->_DFLT_LMT;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$sec,$dir,$fil,$lmt);
//        exit();
        
        switch ($sec) {
            case "SEC_TQR" :
                    $datas = $this->select_tqr_all($dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "SEC_MYCNTY" : 
                    $datas = $this->select_mycnty_all($dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "SEC_MYLANG" : 
                    $datas = $this->select_mycnty_all($dir,$fil,$lmt,$_OPTIONS);
                break; 
            default: 
                return;
        }
        
        $final_datas = ( $datas && !empty($_OPTIONS["fe_mode"]) && $_OPTIONS["fe_mode"] ) 
            ? $final_datas = $this->femode($uid,$datas) : $datas;
        
        return $final_datas;
    }
    
    private function femode ($uid,$datas) {
        
        $formatted = [];
        foreach ($datas as $k => $mmtab) {
           /*
            * ETAPE :
            *      Gestion du cas FAVORITE
            */
        
            $formatted[] = [
                "id"        => $mmtab["mysm_eid"],
                "text"      => html_entity_decode($mmtab["mysm_text"]),
                "time"      => $mmtab["mysm_creadate_tstamp"],
                "list_hash" => $this->onload_mysm_list_hash($mmtab["mysmid"],$mmtab["mysm_eid"]),
                "cnvotes"   => $this->onload_mysm_cnvotes($mmtab["mysmid"]),
                "sumvotes"  => $this->onvote_sum($mmtab["mysm_eid"]),
                "ssid"      => $mmtab["mysm_ssid"],
                "locip"     => $mmtab["mysm_locip"],
                "uagent"    => $mmtab["mysm_uagent"],
                "reflang"   => $mmtab["mysm_reflang"],
                "refcnty"   => $mmtab["mysm_refcnty"],
                "refcity"   => $mmtab["mysm_refcity"], 
                "ouid"      => $mmtab["mysm_ouid"], 
                "oueid"     => $mmtab["mysm_oueid"], 
                "oufn"      => $mmtab["mysm_oufn"], 
                "oupsd"     => $mmtab["mysm_oupsd"], 
                "ouhref"    => "/".$mmtab["mysm_oupsd"] 
 
                /*
                "id"        => $atab["art_eid"],
                "img"       => $atab["art_pic_rpath"],
                "msg"       => html_entity_decode($atab["art_desc"]),
                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($atab["art_eid"]),
                "time"      => $atab["art_crea_tstamp"],
                "eval"      => explode(",", $atab["art_evals"]),
                "myel"      => $EV->getUserMyEval($atab["art_oid"],$atab["artid"]),
                "evalu"     => null,
                //TREND
                "isrtd"     => ( !empty($atab["art_trd_eid"]) ) ? TRUE : FALSE,
                "trid"      => ( !empty($atab["art_trd_eid"]) ) ? $atab["art_trd_eid"] : null,
                "trtitle"   => ( !empty($atab["art_trd_title"]) ) ? $atab["art_trd_title"] : null,
                "trhref"    => ( !empty($atab["art_trd_eid"]) ) ? $TRD->on_read_build_trdhref($atab["art_trd_eid"],$atab["art_trd_title_href"]) : null,
                //USER
                "ueid"      => $atab["art_oeid"],
                "ufn"       => $atab["art_ofn"],
                "upsd"      => $atab["art_opsd"],
                "uppic"     => $atab["art_oppic_rpath"],
                "uhref"     => "/".$atab["art_opsd"],
                //FAVORITE
                "hasfv"     => ( $fvtab ) ? TRUE : FALSE,
                "fvtp"      => ( $fvtab ) ? $fvtp : null,
                "fvtm"      => $fvtab["arfv_startdate_tstamp"],
                "is_sod"    => $atab["art_is_sod"]
                //*/
            ];
        }
        
        return $formatted;
    }
    
    
    
    private function select_tqr_all ($dir, $fil, $lmt, $_OPTIONS = NULL) {
        $now = round(microtime(TRUE)*1000);
                    
        $foo = intval(date("G",($now/1000)))*3600000;
        $foo += intval(date("i",($now/1000)))*60000;
        $foo += intval(date("s",($now/1000)))*1000;

        $from  = $now - $foo;
                    
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4mysmn7");
                        $params = array (
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "TOP" ) {
                        $QO = new QUERY("qryl4mysmn7_top");
                        $params = array (
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4mysmn7_btm");
                        $params = array (
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4mysmn8");
                        $params = array (
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "TOP" ) {
                        return null;
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4mysmn8_btm");
                        $params = array (
                            ":refid"    => $_OPTIONS["rfid"],
                            ":refvote"  => $_OPTIONS["refvote"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "TODAY_BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4mysmn9");
                        $params = array (
                            ":date_from"    => $from,
                            ":limit"        => $lmt
                        );
                    } else if ( $dir === "TOP" ) {
                        $QO = new QUERY("qryl4mysmn9_top");
                        $params = array (
                            ":date_from"    => $from,
                            ":refid"        => $_OPTIONS["rfid"],
                            ":reftm"        => $_OPTIONS["rftm"],
                            ":limit"        => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4mysmn9_btm");
                        $params = array (
                            ":date_from"    => $from,
                            ":refid"        => $_OPTIONS["rfid"],
                            ":reftm"        => $_OPTIONS["rftm"],
                            ":limit"        => $lmt
                        );
                    }
                break;
            case "TODAY_BY_LIKES" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4mysmn10");
                        $params = array (
                            ":date_from"    => $from,
                            ":limit"        => $lmt
                        );
                    } else if ( $dir === "TOP" ) {
                        return null;
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4mysmn10_btm");
                        $params = array (
                            ":refid"        => $_OPTIONS["rfid"],
                            ":refvote"      => $_OPTIONS["refvote"],
                            ":date_from"    => $from,
                            ":limit"        => $lmt
                        );
                    }
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_mycnty_all ($dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                break;
            case "BY_LIKES" :
                break;
            case "TODAY_BY_DATE" :
                break;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return NULL;
    }
    
    private function select_mylang_all ($dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                break;
            case "BY_LIKES" :
                break;
            case "TODAY_BY_DATE" :
                break;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return NULL;
    }
    
    
    /********************************************************************************************************************************************************************************/
    /**************************************************************************** MYSTERY DISCLOSE SCOPE ****************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    public function ondisclose_disclose ($uid,$mysm_eid,$locip,$ssid,$curl,$uagent=NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$mysm_eid,$locip,$ssid,$curl]);
        
        $mysm_tab = $this->exists($mysm_eid);
        if (! $mysm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4mysdn1");
        $params = array(
            ":mysd_mysmid"          => $mysm_tab["mysmid"],
            ":mysd_ouid"            => $uid,
            ":mysd_locip"           => $locip,
            ":mysd_ssid"            => $ssid,
            ":mysd_uagent"          => $uagent,
            ":mysd_curl"            => $curl,
            ":mysd_creadate"        => $date,
            ":mysd_creadate_tstamp" => $time
        );  
        $mysmid = $QO->execute($params);   
        
        return $mysmid;
    }
    
    public function ondisclose_exists_for_user ($uid,$mysm_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$mysm_eid]);
        
        $mysm_tab = $this->exists($mysm_eid);
        if (! $mysm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        $QO = new QUERY("qryl4mysdn4");
        $params = array(
            ":mmid" => $mysm_tab["mysmid"],
            ":uid"  => $uid,
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? TRUE : FALSE;
    }
    
    public function ondisclose_count ($mysm_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mysm_eid]);
        
        $mysm_tab = $this->exists($mysm_eid);
        if (! $mysm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        $QO = new QUERY("qryl4mysdn3");
        $params = array(
            ":id" => $mysm_tab["mysmid"]
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["cn"] : 0;
    }
    
    public function ondisclose_delall($mysmid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mysmid]);
        
        $QO = new QUERY("qryl4mysdn5");
        $params = array( ":id" => $mysmid );
        $QO->execute($params);
        
        return TRUE;
    }
    
    /********************************************************************************************************************************************************************************/
    /***************************************************************************** MYSTERY VOTES SCOPE ******************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    public function onvote_exists ($mysv_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mysv_eid]);
        
        $QO = new QUERY("qryl4mysvn4");
        $params = array(":eid" => $mysv_eid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    
    public function onvote_exists_with_id ($mysvid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mysvid]);
        
        $QO = new QUERY("qryl4mysvn5");
        $params = array(":id" => $mysvid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function onvote_exists_for_user ($uid,$mysm_eid,$limit=NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$mysm_eid]);
        
        $mysm_tab = $this->exists($mysm_eid);
        if (! $mysm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        $lmt = ( $limit ) ?: 1;
        
//        $QO = new QUERY("qryl4mysvn6"); //[DEPUIS 20-03-16]
        $QO = new QUERY("qryl4mysvn9");
        $params = array(
            ":uid"      => $uid,
            ":mmid"     => $mysm_tab["mysmid"],
            ":limit"    => $lmt
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function onvote_add ($uid,$mysm_eid,$myvote,$locip,$ssid,$curl,$uagent=NULL) {
        /*
         * NOTE :
         *      [13-03-16]
         *          On n'interdit pas l'ajout de plusieurs votation. 
         *          C'est à CALLER de bloquer l'ajout de nouveau VOTE le cas échéant.
         *          Cela nous permet de réduire la dépendance entre les couches.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$mysm_eid,$myvote,$locip,$ssid,$curl]);
        
        if (! in_array($myvote, ["VOTE_UP","VOTE_DOWN"]) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        $mysm_tab = $this->exists($mysm_eid);
        if (! $mysm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        /*
         * [ETAPE]
         *      On vérifie qu'il existe une occurrence de vote par USER pour le MYSM mentionné.
         *      Si c'est le cas, nous le rendant obselète avant de passer à la création de la future occurrence.
         */
        $efu_tab = $this->onvote_exists_for_user($uid,$mysm_tab["mysm_eid"],1);
        if ( $efu_tab ) {
            $this->onvote_alter_close($efu_tab["mysvid"]);
        }
                
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4mysvn2");
        $params = array(
            ":mysv_vote"            => ( $myvote === "VOTE_UP" ) ? 1 : -1,
            ":mysv_mysmid"          => $mysm_tab["mysmid"],
            ":mysv_ouid"            => $uid,
            ":mysv_locip"           => $locip,
            ":mysv_ssid"            => $ssid,
            ":mysv_uagent"          => $uagent,
            ":mysv_curl"            => $curl,
            ":mysv_start"           => $date,
            ":mysv_start_tstamp"    => $time
        );  
        $mysvid = $QO->execute($params);   
        
        //Créer mysv_eid
        $mysv_eid = $this->entity_ieid_encode($time, $mysvid);
        
        //Insérer mysv_eid
        $QO = new QUERY("qryl4mysvn3");
        $params = array(":id" => $mysvid, ":eid" => $mysv_eid);  
        $QO->execute($params);
        
        $mysv_tab = $this->onvote_exists($mysv_eid);
        
        return $mysv_tab;
    }
    
    public function onvote_alter_close ($mvid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mvid]);
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4mysvn10");
        $params = array(
            ":mvid"     => $mvid,
            ":date"     => $date,
            ":tstamp"   => $time
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    public function onvote_sum ($mysm_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mysm_eid]);
        
        $mysm_tab = $this->exists($mysm_eid);
        if (! $mysm_tab ) {
            return "__ERR_VOL_MYSM_GONE";
        }
        
        $QO = new QUERY("qryl4mysvn7");
        $params = array( 
            ":mmid" => $mysm_tab["mysmid"]
        );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0]["ratio"] : 0;
    }
    
    public function onvote_delthis ($mveid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mveid]);
        
        $mvtab = $this->onvote_exists($mveid);
        if (! $mvtab )  {
            return "__ERR_VOL_MYSVOTE_GONE";
        }
        
        $QO = new QUERY("qryl4mysvn11");
        $params = array ( 
            ":id" => $mvtab["mysvid"]
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    public function onvote_delall_for_msg ($mmeid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mmeid]);
        
        $mmtab = $this->exists($mmeid);
        if (! $mmtab )  {
            return "__ERR_VOL_MYSMSG_GONE";
        }
        
        $QO = new QUERY("qryl4mysvn8");
        $params = array ( 
            ":id" => $mmtab["mysmid"]
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    public function onvote_delall_for_msg_with_mmid ($mmid, $CHECK_IF_EXISTS = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$mmid]);
        
        /*
         * ETAPE :
         *      On vérifie au préalable, le cas échéant, si le MM est disponible.
         */
        if ( $CHECK_IF_EXISTS ) {
            $mmtab = $this->exists_with_id($mmid);
            if (! $mmtab )  {
                return "__ERR_VOL_MYSMSG_GONE";
            }
        }
        
        $QO = new QUERY("qryl4mysvn8");
        $params = array ( 
            ":id" => $mmid
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    public function onvote_delall_for_user ($uid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $QO = new QUERY("qryl4mysvn12");
        $params = array ( 
            ":id" => $uid
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    /********************************************************************************************************************************************************************************/
    /*************************************************************************** GETTERS & SETTERS SCOPE ****************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    public function getMysmid() {
        return $this->mysmid;
    }

    public function getMysm_eid() {
        return $this->mysm_eid;
    }

    public function getMysm_text() {
        return $this->mysm_text;
    }

    public function getMysm_owner() {
        return $this->mysm_owner;
    }

    public function getMysm_list_hash() {
        return $this->mysm_list_hash;
    }

    public function getMysm_creadate() {
        return $this->mysm_creadate;
    }

    public function getMysm_creadate_tstamp() {
        return $this->mysm_creadate_tstamp;
    }

    public function getMysm_ssid() {
        return $this->mysm_ssid;
    }

    public function getMysm_locip() {
        return $this->mysm_locip;
    }

    public function getMysm_curl() {
        return $this->mysm_curl;
    }

    public function getMysm_uagent() {
        return $this->mysm_uagent;
    }

    public function getMysm_reflang() {
        return $this->mysm_reflang;
    }

    public function getMysm_refcnty() {
        return $this->mysm_refcnty;
    }

    public function getMysm_refcity() {
        return $this->mysm_refcity;
    }

    public function getMysm_cnvotes() {
        return $this->mysm_cnvotes;
    }

    public function getMysm_sumvotes() {
        return $this->mysm_sumvotes;
    }

    public function getMysm_ouid() {
        return $this->mysm_ouid;
    }

    public function getMysm_ougid() {
        return $this->mysm_ougid;
    }

    public function getMysm_oueid() {
        return $this->mysm_oueid;
    }

    public function getMysm_oufn() {
        return $this->mysm_oufn;
    }

    public function getMysm_oupsd() {
        return $this->mysm_oupsd;
    }

    public function getMysm_ouppicid() {
        return $this->mysm_ouppicid;
    }

    public function getMysm_ouppic() {
        return $this->mysm_ouppic;
    }

    public function getMysm_ouhref() {
        return $this->mysm_ouhref;
    }    
    
}