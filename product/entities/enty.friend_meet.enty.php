<?php


class FRIEND_MEET extends PROD_ENTITY { 
    
    /********* ASK SCOPE *********/
    
    private $fmt_id;
    private $fmt_eid;
    private $fmt_act_uid;
    private $fmt_tgt_uid;
    private $fmt_guests_list_str;
    
    private $fmt_place;
    private $fmt_date_start;
    private $fmt_date_start_tstamp;
    private $fmt_date_end;
    private $fmt_date_end_tstamp;
    
    private $fmt_msg;

    private $fmt_askdate;
    private $fmt_askdate_tstamp;

    private $fmt_ssid;
    private $fmt_locip;
    private $fmt_uagent;
    
    /******** ACTOR SCOPE ***********/
    
    private $fmt_acuidf;
    private $fmt_acueidf;
    private $fmt_acufnf;
    private $fmt_acupsdf;
    private $fmt_acuppicidf;
    private $fmt_acuppicf;
    private $fmt_acuhref;
    
    /******** TARGET SCOPE ***********/
            
    private $fmt_tguidf;
    private $fmt_tgueidf;
    private $fmt_tgufnf;
    private $fmt_tgupsdf;
    private $fmt_tguppicidf;
    private $fmt_tguppicf;
    private $fmt_tguhref;
    
    /********* TGT RES SCOPE *********/
            
    private $fmt_tgt_resp;
    
    /********* GSTS & GSTS RES SCOPE *********/
    
    private $fmt_gsts_list;
    private $fmt_gst_resps;
    
    /*********************************/
    /************* RULES *************/
    
    private $_FMT_ADDR_RGX;
    private $_FMT_MSG_RGX;
    private $_FRE_TYPES;
    
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);

        $this->prop_keys = ["fmt_id","fmt_eid","fmt_act_uid","fmt_tgt_uid","fmt_guests_list_str","fmt_place","fmt_date_start","fmt_date_start_tstamp","fmt_date_end","fmt_date_end_tstamp","fmt_msg","fmt_askdate","fmt_askdate_tstamp","fmt_ssid","fmt_locip","fmt_uagent","fmt_acuid","fmt_acueid","fmt_acufn","fmt_acupsd","fmt_acuppicid","fmt_acuppic","fmt_acuhref","fmt_tguid","fmt_tgueid","fmt_tgufn","fmt_tgupsd","fmt_tguppicid","fmt_tguppic","fmt_tguhref","fmt_tgresp","fmt_guests","fmt_guests_resp"];
        $this->needed_to_loading_prop_keys = ["fmt_id","fmt_eid","fmt_act_uid","fmt_tgt_uid","fmt_guests_list_str","fmt_place","fmt_date_start","fmt_date_start_tstamp","fmt_date_end","fmt_date_end_tstamp","fmt_msg","fmt_askdate","fmt_askdate_tstamp","fmt_ssid","fmt_locip","fmt_uagent","fmt_acuid","fmt_acueid","fmt_acufn","fmt_acupsd","fmt_acuppicid","fmt_acuppic","fmt_acuhref","fmt_tguid","fmt_tgueid","fmt_tgufn","fmt_tgupsd","fmt_tguppicid","fmt_tguppic","fmt_tguhref","fmt_tgresp","fmt_guests","fmt_guests_resp"];
        $this->needed_to_create_prop_keys = ["acuid","tguid","date","place","guests","message","ssid","locip","uagent"];
        
        /***************************** RULES *****************************/
        
        $this->_FMT_ADDR_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+$/i";
        $this->_FMT_MSG_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+$/i";
        
        $this->filters = [
            "UPCOMING","ARCHIVED"
        ];
        $this->_DFLT_FIL = "DEFAULT";
        
        $this->_DFLT_LMT = 10;
        
        $this->_FRE_TYPES = [
            1 => "SURE",
            2 => "MAYBE",
            3 => "NOPE",
        ];
        
    }
    
    public function build_volatile($args) { }

    public function exists($eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $eid);
        
        $QO = new QUERY("qryl4fmtn2");
        $params = array(":eid" => $eid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : FALSE;
    }
    
    public function exists_with_id($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $id);
        
        $QO = new QUERY("qryl4fmtn1");
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
        $fmt_eid;
        if (! ( !empty($args) && is_array($args) && key_exists("fmt_eid", $args) && !empty($args["fmt_eid"]) ) ) 
        {
            if ( empty($this->fmt_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else { 
                $fmt_eid = $this->fmt_eid;
            }
        } else { $fmt_eid = $args["fmt_eid"]; }
        
        // On controle si l'occurrence existe et on récupèrre les données (notamment accid)
        $fmt_tab = $this->exists($fmt_eid);
        if ( ( !$fmt_tab ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$fmt_tab ) && !$std_err_enbaled ) 
        {
            return FALSE;
        }
        
        $PA = new PROD_ACC();
        
        $actab = ( $this->entity_cache["oncreate_actab"] ) ? $this->entity_cache["oncreate_actab"] : $PA->exists_with_id($fmt_tab["fmtask_act_uid"]);
        $tgtab = ( $this->entity_cache["oncreate_tgtab"] ) ? $this->entity_cache["oncreate_tgtab"] : $PA->exists_with_id($fmt_tab["fmtask_tgt_uid"]);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$fmt_tab,$actab,$tgtab]);
//        exit();
        
        $loads = [
            //Données sur la DEMANDE
            "fmt_id"                => $fmt_tab["fmtask_id"],
            "fmt_eid"               => $fmt_tab["fmtask_eid"],
            "fmt_act_uid"           => $fmt_tab["fmtask_act_uid"],
            "fmt_tgt_uid"           => $fmt_tab["fmtask_tgt_uid"],
            "fmt_guests_list_str"   => $fmt_tab["fmtask_guests_list_str"],
            "fmt_date_start"        => $fmt_tab["fmtask_date_start"],
            "fmt_date_start_tstamp" => $fmt_tab["fmtask_date_start_tstamp"],
            "fmt_date_end"          => $fmt_tab["fmtask_date_end"],
            "fmt_date_end_tstamp"   => $fmt_tab["fmtask_date_end_tstamp"],
            "fmt_place"             => $fmt_tab["fmtask_place"],
            "fmt_msg"               => $fmt_tab["fmtask_msg"],
            
            "fmt_askdate"           => $fmt_tab["fmtask_askdate"],
            "fmt_askdate_tstamp"    => $fmt_tab["fmtask_askdate_tstamp"],
            "fmt_ssid"              => $fmt_tab["fmtask_ssid"],
            "fmt_locip"             => $fmt_tab["fmtask_locip"],
            "fmt_uagent"            => $fmt_tab["fmtask_uagent"],
            
            //Données sur ACTOR
            "fmt_acuid"             => $actab["pdaccid"],
            "fmt_acueid"            => $actab["pdacc_eid"],
            "fmt_acufn"             => $actab["pdacc_ufn"],
            "fmt_acupsd"            => $actab["pdacc_upsd"],
            "fmt_acuppicid"         => $PA->onread_acquiere_pp_datas($actab["pdacc_eid"])["picid"],
            "fmt_acuppic"           => $PA->onread_acquiere_pp_datas($actab["pdacc_eid"])["pic_rpath"],
            "fmt_acuhref"           => "/".$actab["pdacc_upsd"],
            
            //Données sur TARGET
            "fmt_tguid"             => $tgtab["pdaccid"],
            "fmt_tgueid"            => $tgtab["pdacc_eid"],
            "fmt_tgufn"             => $tgtab["pdacc_ufn"],
            "fmt_tgupsd"            => $tgtab["pdacc_upsd"],
            "fmt_tguppicid"         => $PA->onread_acquiere_pp_datas($tgtab["pdacc_eid"])["picid"],
            "fmt_tguppic"           => $PA->onread_acquiere_pp_datas($tgtab["pdacc_eid"])["pic_rpath"],
            "fmt_tguhref"           => "/".$tgtab["pdacc_upsd"],
            
            //Target RESPONSE
            
            //Gusts RESPONSES
            
            
        ];
        
        $fmt_id = $loads['fmt_id'];
        
//        var_dump(__FUNCTION__,__LINE__,$loads);
//        exit();
        
        if ( !count($loads) ) { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else if ( isset($args["is_create"]) && $args["is_create"] === TRUE ) {
            $loads["fmt_tgresp"] = NULL;
            $loads["fmt_guests"] = NULL;
            $loads["fmt_guests_resp"] = NULL;
            
            return $loads;
        }
        else {
            $extras = ["fmt_tgresp","fmt_guests","fmt_guests_resp"];
            foreach ( $extras as $v ) {
                $r = $this->load_entity_extras_datas($loads["fmt_id"], $loads["fmt_eid"], $v);
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
        
        //On vérifie la présence des données obligatoires : "acuid","tguid","date","place","guests","message","ssid","locip","uagent"
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !( in_array($k,["message","uagent"]) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE => ",$k,$v],'v_d');
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        /*
         * ETAPE :
         *      On vérifie si ACTOR existe toujours
         */
        $PACC = new PROD_ACC();
        $actab = $PACC->exists_with_id($args["acuid"],TRUE);
        if (! $actab ) {
            return "__ERR_VOL_ACT_GONE";
        }
        /*
         * ETAPE :
         *      On vérifie si TARGET existe toujours
         */
        $PACC = new PROD_ACC();
        $tgtab = $PACC->exists_with_id($args["tguid"],TRUE);
        if (! $tgtab ) {
            return "__ERR_VOL_TGT_GONE";
        }
        
        /*
         * ETAPE :
         *      On stocke les données pour ne pas avoir à les recharger de nouveaux
         */
        $this->entity_cache = [
            "oncreate_actab" => $actab,
            "oncreate_tgtab" => $tgtab
        ];
        
        
        /*
         * ETAPE :
         *      On vérifie que les champs sont conformes
         */
        $check_list = [
            "date"      => $args["date"],
            "place"     => $args["place"],
            "guests"    => $args["guests"],
            "message"   => $args["message"],
        ];
        $errs = $this->oncreate_control($check_list);
        
        if ( $errs ) {
            return "__ERR_VOL_MSM_DATAS";
        }
        
        $crea_args = [
            "acuid"     => $args["acuid"],
            "tguid"     => $args["tguid"],
            "date"      => $check_list["date"],
            "place"     => $check_list["place"],
            "guests"    => $check_list["guests"],
            "message"   => $check_list["message"],
            "ssid"      => $args["ssid"],
            "locip"     => $args["locip"],
            "uagent"    => $args["uagent"],
        ];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$errs);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$check_list);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$crea_args);
//        exit();
        
        $fmt_infos = $this->write_new_in_database($crea_args);
        
        $fmt_id = $fmt_infos["fmt_id"];
        $fmt_eid = $fmt_infos["fmt_eid"];
        
        /*
         * ETAPE :
         *      Ajouter les GUESTS
         */
        $guests_list;
        if ( $crea_args["guests"] ) {
            $guests_list = $this->oncreate_addguests($fmt_id,$crea_args["guests"]);
            
            $this->entity_cache["guests_list"] = $guests_list;
            $this->entity_cache["guests_list_str"] = implode(",", array_column($args["guests"],"pdacc_eid"));
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$this->entity_cache["guests_list"]]);
//            exit();
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$fmt_id,$fmt_eid,$fmt_infos]);
//        exit();
        
        //On load l'instance
        return $this->load_entity([
            "fmt_eid"   => $fmt_eid,
            "is_create" => TRUE
        ]);
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
        $QO = new QUERY("qryl4fmtn5");
        $params = array(
            ":id"   => $mm_tab["mysmid"],
        );  
        $QO->execute($params); 
        
        return TRUE;
    }

    public function on_read_entity($args) {
        $fmt_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("fmt_eid", $args) && !empty($args["fmt_eid"]) ) ) 
        {
            if ( empty($this->fmt_eid) ) {
                return;
            } else {
                $fmt_eid = $this->fmt_eid;
            }
        } else {
            $fmt_eid = $args["fmt_eid"];
        }
        
        
        return $this->load_entity($args);
    }

    protected function write_new_in_database($args) {
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4fmtn3");
        $params = array(
            ":fmt_eid"              => $time,
            ":fmt_acuid"            => $args["acuid"],
            ":fmt_tguid"            => $args["tguid"],
            ":fmt_guests_list_str"  => ( $args["guests"] ) ? implode(",", array_column($args["guests"],"pdacc_eid") ) : NULL,
            ":fmt_stdate"           => date("Y-m-d G:i:s",($args["date"]/1000)),
            ":fmt_stdate_tstamp"    => $args["date"],
            ":fmt_endate"           => NULL,
            ":fmt_endate_tstamp"    => NULL,
            ":fmt_place"            => $args["place"],
            ":fmt_msg"              => $args["message"]["m"],
            
            ":fmt_ssid"             => $args["ssid"],
            ":fmt_locip"            => $args["locip"],
            ":fmt_uagent"           => $args["uagent"],
            
            ":fmt_creadate"         => $date,
            ":fmt_creadate_tstamp"  => $time
        );  
//        var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$params);
//        exit();
        $fmt_id = $QO->execute($params);     
        
//        var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$fmt_id);
//        exit();
        
        //Créer fmt_eid
        $fmt_eid = $this->entity_ieid_encode($time, $fmt_id);
        
        //Insérer fmt_eid
        $QO = new QUERY("qryl4fmtn4");
        $params = array(":id" => $fmt_id, ":eid" => $fmt_eid);  
        $datas = $QO->execute($params);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        /*
         * [ RAPPEL ] 
         *  Il faudra que CRON puisse archiver les TESTIES.
         */
        
        //Création TST_INFOS
        $fmt_infos = NULL;
        $fmt_infos["fmt_id"] = $fmt_id;
        $fmt_infos["fmt_eid"] = $fmt_eid;
        
        return $fmt_infos;
    }
    
    /********************************************************************************************************************************************************************************/
    /******************************************************************************* SPECIFICS SCOPE ********************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    /**************************************************************** ONLOAD SCOPE (START) ****************************************************************/
    
    private function load_entity_extras_datas ($fmt_id, $fmt_eid, $k) {
        /*
         * Permet de load les autres données necessaires pour load l'Entity. 
         * La méthode peut servir lorsqu'on a un tableau de extras_keys et qu'on veut les charger les (extras) acquerir les uns après les autres. 
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
            
        switch($k) {
            case "fmt_tgresp" :
                return $this->onload_get_tgresp($fmt_eid);
            case "fmt_guests" :
                return $this->onload_guest_get_all($fmt_eid);
            case "fmt_guests_resp" :
                return $this->onload_guest_get_resps($fmt_eid);
            default:
                return 0;
        }
    }
    
    public function onload_get_tgresp ($fmt_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4fmtn8_eid");
        $qparams_in_values = array( ":eid"  => $fmt_eid );  
        $datas = $QO->execute($qparams_in_values);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
    
    public function onload_guest_get_all ($fmt_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4fmt_gstn2");
        $qparams_in_values = array( ":eid"  => $fmt_eid );  
        $datas = $QO->execute($qparams_in_values);
        
        return ( $datas ) ? $datas : NULL;
    }
    
    public function onload_guest_get_resps ($fmt_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4fmt_grespn1");
        $qparams_in_values = array( ":eid"  => $fmt_eid );  
        $datas = $QO->execute($qparams_in_values);
        
        return ( $datas ) ? $datas : NULL;
    }
    
    
    /**************************************************************** ONCREATE SCOPE (START) ****************************************************************/
    
    private function oncreate_treat_msg ($s, $RGX, &$usertags = NULL, &$kws = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        $TH = new TEXTHANDLER();
        
        /*
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
        if (! ( is_string($s) && $TH->strlen_utf8($s) <= 150 && preg_match($RGX,$s) ) ) {
            return "__ERR_VOL_TXT_MSM";
        }
        
        
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
    
    private function oncreate_control (&$list) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $list);
        
        $errs;
        foreach ($list as $k => &$v) {
            switch ($k) {
                case "date" :
                        //On vérifie que la date est postérieure à la date en cours. L'écart est au moins de 10 minutes
                        if (! $this->isValidTimeStamp($v,TRUE) ) {
                            $errs[] = "date2";
                        } else {
                            $now = round(microtime(TRUE)*1000);
                            $v = floatval($v);
                            $diff = $v - $now;
                            
                            $min = 10 * 60000;
                            if ( $diff < $min ) {
                                $errs[] = "date";
                            }
                        }
                    break;
                case "place" :
                        //On vérifie que le texte respecte le format attendu
                        $TH = new TEXTHANDLER();
                        
//                        var_dump($v,is_string($v),$TH->strlen_utf8($v),preg_match($this->_FMT_ADDR_RGX,$v));
                        if (! ( is_string($v) && $TH->strlen_utf8($v) <= 150 && preg_match($this->_FMT_ADDR_RGX,$v) ) ) {
                            $errs[] = "place";
                        } else {
                            $v__ = $TH->secure_text($v);
                            $v = $TH->replace_emojis_in($v__);
                        }
                    break;
                case "guests" :
                        $PA = new PROD_ACC();
                        $guests = [];
                        //On vérifie que chaque Guest exists
                        foreach ($v as $g) {
                            $g = ( $g[0] === "@" ) ? substr($g,1) : $g;
                            if ( $gtab = $PA->exists_with_psd($g) ) {
                                $guests[] = $gtab;
                            }
                        }
                        $v = $guests;
                    break;
                case "message" :
                        //On vérifie que le texte respecte le format attendu ET on traite le MESSAGE
                    
                        $kws = $usertags = NULL;
                        $nmsg = $this->oncreate_treat_msg($v, $this->_FMT_MSG_RGX, $kws, $usertags);
                        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nmsg) ) {
                            $errs[] = "message";
                        } 
                        else {
                            $v = [
                                "m" => $nmsg,
                                "k" => $kws,
                                "u" => $usertags
                            ];
                        }
                    break;
                default :
                    break;
            }
        }
        
        return $errs;
    }
    
    private function oncreate_addguests ($fmt_id, $guests_tabs) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fmt_id,$guests_tabs]);
        
        $guests_list = [];
        foreach ($guests_tabs as $utab) {
            $time = round(microtime(TRUE)*1000);
            $date = date("Y-m-d G:i:s",($time/1000));

            $QO = new QUERY("qryl4fmt_gstn1");
            $params = array(
                ":fmt_id"       => $fmt_id,
                ":gst_uid"      => $utab["pdaccid"],
            );  
            $gst_id = $QO->execute($params);
            
            $guests_list[] = [
                "gsti"  => $gst_id,
                "fmti"  => $fmt_id,
                //Données USER
                "uid"   => $utab["pdaccid"],
                "ueid"  => $utab["pdacc_eid"],
                "ufn"   => $utab["pdacc_ufn"],
                "upsd"  => $utab["pdacc_upsd"],
            ];
        }
        
        return $guests_list;
        
    }
    
    
    /**************************************************************** ONREAD SCOPE (START) ****************************************************************/
    
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
            ];
        }
        
        return $formatted;
    }
    
    
    /********************************************************************************************************************************************************************************/
    /************************************************************************** MEET TARGET RESPONSE SCOPE **************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    public function tgresp_set ($uid, $fmt_eid, $rstp, $ssid, $locip, $uagent = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $fmt_eid, $rstp, $ssid, $locip]);
        
        /*
         * ETAPE :
         *      On vérifie que le TYPE est conforme
         */
        $keys = array_keys($this->_FRE_TYPES);
        if (! in_array($rstp, $keys) ) {
            return "__ERR_VOL_MSM_TYP";
        }
        
        /*
         * ETAPE :
         *      On récupère les données sur : la demande de rencontre et une éventuelle réponse existante.
         */
        $QO = new QUERY("qryl4fmtn8_eid_byleft");
        $qparams_in_values = array( ":eid"  => $fmt_eid );  
        $full = $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         *      On s'éassure qu'il n'existe aucune réponse
         */
        if (! $full ) {
            return "__ERR_VOL_VOID";
        }
        else if ( isset($full) && isset($full["fmtre_id"]) ) {
            return "__ERR_VOL_ALDY";
        }
        
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));

        $QO = new QUERY("qryl4fmtn9");
        $params = array(
            ":fre_eid"      => $time,
            ":fmt_id"       => "",
            ":fre_typ"      => "",
            ":fre_date"     => $date,
            ":fre_tstamp"   => $time,
            ":ssid"         => $ssid,
            ":locip"        => $locip,
            ":uagent"       => $uagent,
        );  
        $fre_id = $QO->execute($params);
        
        //Créer fmt_eid
        $fmt_eid = $this->entity_ieid_encode($time, $fre_id);
        
        //Insérer fmt_eid
        $QO = new QUERY("qryl4fmtn10");
        $params = array(":id" => $fre_id, ":eid" => $fmt_eid);  
        $datas = $QO->execute($params);
        
        ...
        
    }
    
    public function tgresp_get ($fmt_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fmt_eid]);
        
        return $this->onload_get_tgresp($fmt_eid);
    }
    
    
    /********************************************************************************************************************************************************************************/
    /***************************************************************************** MEET GUESTS SCOPE ******************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    public function guests_is_guest ($fmt_eid, $uid, $wdatas = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fmt_eid, $uid]);
        
        $QO = new QUERY("qryl4fmt_gstn3");
        $qparams_in_values = array( 
            ":uid"      => $uid,
            ":feid"     => $fmt_eid,
        );  
        $datas = $QO->execute($qparams_in_values);
        
        if ( $datas ) {
            return ( $wdatas ) ? $datas[0] : TRUE;
        }
        return FALSE;
    }
    
    public function guests_get_all ($fmt_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fmt_eid]);
        
        return $this->onload_guest_get_all($fmt_eid);
    }
    
    public function guests_get_allresps ($fmt_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fmt_eid]);
        
        return $this->onload_guest_get_resps($fmt_eid);
    }
    
    
    /********************************************************************************************************************************************************************************/
    /************************************************************************* MEET GUESTS RESPONSES SCOPE **************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    public function gstresp_set () {
        
    }
    
    public function gstresp_get () {
        
    }
    
    
    /********************************************************************************************************************************************************************************/
    /*************************************************************************** GETTERS & SETTERS SCOPE ****************************************************************************/
    /********************************************************************************************************************************************************************************/
    
    
}