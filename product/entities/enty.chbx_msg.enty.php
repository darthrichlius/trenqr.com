<?php

class CHBX_MSG extends PROD_ENTITY {
    private $chmsgid;
    private $chmsg_eid;
    private $chmsg_convid;
    private $chmsg_msg;
    private $chmsg_locip;
    private $chmsg_useragt;
    
    /*
     * La liste des liens enregistrés, liés au message
     */
    private $chmsg_urlics;
    
    /**** ACTOR SCOPE */
    private $chmsg_actid;
    private $chmsg_acteid;
    private $chmsg_actgid;
    private $chmsg_actfn;
    private $chmsg_actpsd;
    private $chmsg_actgdr;
    private $chmsg_actppic;
    private $chmsg_acttodl;
    
    /**** TARGET SCOPE */
    private $chmsg_tgtid;
    private $chmsg_tgteid;
    private $chmsg_tgtgid;
    private $chmsg_tgtfn;
    private $chmsg_tgtpsd;
    private $chmsg_tgtgdr;
    private $chmsg_tgtppic;
    private $chmsg_tgttodl;
    
    /**** DATATION */
    private $chmsg_cdate;
    private $chmsg_cdate_tstamp;
    private $chmsg_fe_cdate;
    private $chmsg_fe_cdate_tstamp;
    private $chmsg_rdate;
    private $chmsg_rdate_tstamp;
    private $chmsg_ad_date;
    private $chmsg_ad_date_tstamp;
    private $chmsg_ad_rsncaz;
    private $chmsg_td_date;
    private $chmsg_td_date_tstamp;
    private $chmsg_td_rsncaz;
    private $chmsg_sd_date;
    private $chmsg_sd_date_tstamp;
    private $chmsg_sd_rsncaz;
    private $chmsg_nxtdldate;
    private $chmsg_nxtdldate_tstamp;        
    
    
    /*********** INNER ************/
    
    /*********** RULES ************/
    private $_NXT_TDL_MS;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["chmsgid","chmsg_eid","chmsg_msg","chmsg_msg_hashs","chmsg_msg_ustgs","chmsg_convid","chmsg_actid","chmsg_acteid","chmsg_actgid","chmsg_actfn","chmsg_actpsd","chmsg_actgdr","chmsg_actppic","chmsg_acttodl","chmsg_tgtid","chmsg_tgteid","chmsg_tgtgid","chmsg_tgtfn","chmsg_tgtpsd","chmsg_tgtgdr","chmsg_tgtppic","chmsg_tgttodl","chmsg_locip","chmsg_useragt","chmsg_urlics","chmsg_cdate","chmsg_cdate_tstamp","chmsg_fe_cdate","chmsg_fe_cdate_tstamp","chmsg_rdate","chmsg_rdate_tstamp","chmsg_ad_date","chmsg_ad_date_tstamp", "chmsg_ad_rsncaz","chmsg_td_date","chmsg_td_date_tstamp","chmsg_td_rsncaz","chmsg_sd_date","chmsg_sd_date_tstamp","chmsg_sd_rsncaz","chmsg_nxtdldate","chmsg_nxtdldate_tstamp"];
        $this->needed_to_loading_prop_keys = ["chmsgid","chmsg_eid","chmsg_convid","chmsg_msg","chmsg_msg_hashs","chmsg_msg_ustgs","chmsg_actid","chmsg_acteid","chmsg_actgid","chmsg_actfn","chmsg_actpsd","chmsg_actgdr","chmsg_actppic","chmsg_acttodl","chmsg_tgtid","chmsg_tgteid","chmsg_tgtgid","chmsg_tgtfn","chmsg_tgtpsd","chmsg_tgtgdr","chmsg_tgtppic","chmsg_tgttodl","chmsg_locip","chmsg_useragt","chmsg_urlics","chmsg_cdate","chmsg_cdate_tstamp","chmsg_fe_cdate","chmsg_fe_cdate_tstamp","chmsg_rdate","chmsg_rdate_tstamp","chmsg_ad_date","chmsg_ad_date_tstamp", "chmsg_ad_rsncaz","chmsg_td_date","chmsg_td_date_tstamp","chmsg_td_rsncaz","chmsg_sd_date","chmsg_sd_date_tstamp","chmsg_sd_rsncaz","chmsg_nxtdldate","chmsg_nxtdldate_tstamp"];
        
        $this->needed_to_create_prop_keys = ["conv_eid","message","act_eid","tgt_eid", "fetime","locip","useragt","curl"];
        
        /********** RULES **********/
        $this->_NXT_TDL_MS = 3600000*24*14; //14 jours (2 semaines)
        
        
        /*
         * [DEPUIS 24-11-15] @author BOR
         */
        $this->default_dbname = ( defined("WOS_MAIN_HOST") && !in_array(WOS_MAIN_HOST,["localhost","127.0.0.1"]) ) ? "tqr_product_vb1_prod" : "tqr_product_vb1";
    }
    
    protected function build_volatile($args) { }

    public function exists($cbmeid) {
        //QUESTION : Est-il une conversation avec l'identifiant fourni ? (FALSE, DONNEES sur l'évènement)
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_string($cbmeid) || is_int($cbmeid) ) ) {
            return;
        } 
        
        $QO = new QUERY("qryl4chbxmsgn2");
        $params = array( ':cbmeid' => $cbmeid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }

    public function exists_with_id($cbmid) {
        //QUESTION : Est-il une conversation avec l'identifiant fourni ? (FALSE, DONNEES sur l'évènement)
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_string($cbmid) || is_int($cbmid) ) ) {
            return;
        } 
        
        $QO = new QUERY("qryl4chbxmsgn1");
        $params = array( ':cbmid' => $cbmid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }

    protected function init_properties($datas) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
        
        foreach($datas as $k => $v) {
            $$k = $v;
            
            if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
            }
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",array_keys($datas)],'v_d');
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

    protected function load_entity($eid, $std_err_enbaled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Intéroger la base de données pour récupérer les données sur la RELATION
        $datas = $this->exists($eid);
        
        if ( !$datas && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( !$datas && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $usertags = []; $hashs = [];
        $this->oncreate_treat_msg($datas["chmsg_msg"], $u, $h);
        
        if ( $h ) {
            $hashs = $h[1];
        }
        
        if ( $u ) {
            $ustgs = $u[1];
            $PA = new PROD_ACC();
            foreach ($ustgs as $psd) {
                $utab = $PA->exists_with_psd($psd);
                if (! $utab ) {
                    continue;
                }
                
                $usertags[] = [
                    "eid"   => "",
                    "ueid"  => $utab["pdacc_eid"],
                    "ufn"   => $utab["pdacc_ufn"],
                    "upsd"  => $utab["pdacc_upsd"]
                ];
            }
        }
        
        $loads = [
            "chmsgid"                   => $datas["chmsgid"],
            "chmsg_eid"                 => $datas["chmsg_eid"],
            "chmsg_msg"                 => $datas["chmsg_msg"],
            "chmsg_locip"               => $datas["chmsg_locip"],
            "chmsg_useragt"             => $datas["chmsg_useragt"],
            "chmsg_convid"              => $datas["chmsg_convid"],
            /*
             * [DEPUIS 25-06-16]
             */
            "chmsg_msg_hashs"           => $hashs,
            "chmsg_msg_ustgs"           => $usertags,
            //ACTOR
            "chmsg_actid"               => $datas["actid"],
            "chmsg_acteid"              => $datas["acteid"],
            "chmsg_actgid"              => $datas["actgid"],
            "chmsg_actfn"               => $datas["actfn"],
            "chmsg_actpsd"              => $datas["actpsd"],
            "chmsg_actgdr"              => $datas["actgdr"],
//            "chmsg_actppic" => $datas["actppic"],
            "chmsg_acttodl"             => $datas["acttodl"],
            //TARGET
            "chmsg_tgtid"               => $datas["tgtid"],
            "chmsg_tgteid"              => $datas["tgteid"],
            "chmsg_tgtgid"              => $datas["tgtgid"],
            "chmsg_tgtfn"               => $datas["tgtfn"],
            "chmsg_tgtpsd"              => $datas["tgtpsd"],
            "chmsg_tgtgdr"              => $datas["tgtgdr"],
//            "chmsg_tgtppic" => $datas["tgtppic"],
            "chmsg_tgttodl"             => $datas["tgttodl"],            
            //DATATION
            "chmsg_cdate"               => $datas["chmsg_cdate"],
            "chmsg_cdate_tstamp"        => $datas["chmsg_cdate_tstamp"],
            "chmsg_fe_cdate"            => $datas["chmsg_fe_cdate"],
            "chmsg_fe_cdate_tstamp"     => $datas["chmsg_fe_cdate_tstamp"],
            "chmsg_rdate"               => $datas["chmsg_rdate"],
            "chmsg_rdate_tstamp"        => $datas["chmsg_rdate_tstamp"],
            "chmsg_ad_date"             => $datas["chmsg_ad_date"],
            "chmsg_ad_date_tstamp"      => $datas["chmsg_ad_date_tstamp"],
            "chmsg_ad_rsncaz"           => $datas["chmsg_ad_rsncaz"],
            "chmsg_td_date"             => $datas["chmsg_td_date"],
            "chmsg_td_date_tstamp"      => $datas["chmsg_td_date_tstamp"],
            "chmsg_td_rsncaz"           => $datas["chmsg_td_rsncaz"],
            "chmsg_sd_date"             => $datas["chmsg_sd_date"],
            "chmsg_sd_date_tstamp"      => $datas["chmsg_sd_date_tstamp"],
            "chmsg_sd_rsncaz"           => $datas["chmsg_sd_rsncaz"],
            "chmsg_nxtdldate"           => $datas["chmsg_nxtdldate"],
            "chmsg_nxtdldate_tstamp"    => $datas["chmsg_nxtdldate_tstamp"],
            //USERTAGS
            //URLIC
        ];
        
        /************************* EXTRAS DATAS **************************/
        $PA = new PROD_ACC();
        $loads["chmsg_actppic"] = $PA->onread_acquiere_pp_datas($datas["actid"]);
        $loads["chmsg_tgtppic"] = $PA->onread_acquiere_pp_datas($datas["tgtid"]);
        
        /*
         * [TODO]
         *      Gérer le cas de USERTAG dans les messages
         */
        
        /*
         * [DEPUIS 15-11-15] @author BOR
         *      On récupère les liens liés au Message
         */
        $urlics = $this->onread_GetUrlics($datas["chmsg_eid"]);
        $loads["chmsg_urlics"] = $urlics;
        
        $this->init_properties($loads);
        $this->is_instance_load = TRUE;
        return $loads;
            
    }

    protected function on_alter_entity($args) {
        
    }

    public function on_create_entity($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : conv_eid, message, act_eid, tgt_eid, fetime, locip, useragt, curl
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( empty($v) && ($k !== "useragt") ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $PACC = new PROD_ACC();
        $CONV = new CHBX_CONVRS();
        
        /*
         * Controle sur la donnée time. ON s'assure qu'il s'agit bien d'un TIMESTAMP.
         * La probilité que la valeur soit inferieur à 13 est IMPOSSIBLE. 
         * La date ne peut être que superieure à celle de la date de conception du produit.
         */
        if (! (preg_match("/^[\d]+$/", $args["fetime"]) && strlen($args["fetime"]) >= 13 ) ) {
            return "__ERR_VOL_FLD_TM";
        } else {
            $args["fetime"] = ( is_string($args["fetime"]) ) ? floatval($args["fetime"]) : $args["fetime"];
        }
        
        $act_xst = $PACC->exists($args["act_eid"],TRUE);
        if (! $act_xst ) {
            return "__ERR_VOL_ACT_GONE";
        }
        $args["actid"] = $act_xst["pdaccid"];
        
        $tgt_xst = $PACC->exists($args["tgt_eid"],TRUE);
        if (! $tgt_xst ) {
            return "__ERR_VOL_TGT_GONE";
        }
        $args["tgtid"] = $tgt_xst["pdaccid"];
        
        if ( $args["actid"] === $args["tgtid"] ) {
            return "__ERR_VOL_SAME_PROTAS";
        }
        
        $conv_xst = $CONV->exists($args["conv_eid"]);
        if (! $conv_xst ) {
            return "__ERR_VOL_CNV_GONE";
        }
        $args["convid"] = $conv_xst["convid"];
        
        //On sécurise le texte du message
//        $TXH = new TEXTHANDLER();
//        $args["message"] = $TXH->secure_text($args["message"]);
        
        /*
         * [DEPUIS 04-06-16]
         */
        $kws = $usertags = NULL;
        $args["message"] = $this->oncreate_treat_msg($args["message"], $usertags, $kws);
        
        //On écrit dans la base de données
        $ids = $this->write_new_in_database($args);
        
        
        //On load la classe
        return $this->load_entity($ids[1]);
        
    }

    protected function on_delete_entity($cbmeid) {
        /*
         * Permet de supprimer un message en passant son identifiant externe.
         * L'utilisation de l'identifiant est due au fait qu'il s'agit de l'identifiant principalement utilisé sur FE.
         * De plus, cela nous oblige à récupérer l'identifiant base de données du message. 
         * L'avantage étant que l'on profite pour vérifier si la ligne existe. 
         * 
         * Cette méthode est "protected" car elle supprime effectivement le Message et est appelée par une autre méthode.
         * Cependant, un Message est partagé par es deux acteurs liés à la Covnersation. 
         * Aussi, un Message ne sera définitivement supprimé que si les deux protagonistes décide de supprimer le dit Message.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $cbm_tab = $this->exists($cbmeid);
        if (! $cbm_tab ) {
            return "__ERR_VOL_CBM_GONE";
        }
        
        $QO = new QUERY("qryl4chbxmsgn5");
        $params = array( ":id" => $cbm_tab["chmsgid"]);
        $QO->execute($params);
        
        return TRUE;
    }

    public function on_read_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $chmsgid = $chmsg_eid = NULL;
        if ( !( !empty($args) && is_array($args) && key_exists("chmsg_eid", $args) && !empty($args["chmsg_eid"]) ) )
        {
            if ( key_exists("chmsgid", $args) && !empty($args["chmsgid"]) && !is_array($args["chmsgid"]) ) {
                $chmsgid = $args["chmsgid"];
            } else if ( !empty($this->chmsgid) ) {
                $chmsgid = $this->chmsgid;
            } else {
                if ( empty($this->chmsg_eid) ) {
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
                } else {
                    $chmsg_eid = $this->chmsg_eid;
                }
            }
            
        } else { $chmsg_eid = $args["chmsg_eid"]; }
        
        if ( !isset($chmsg_eid) | empty($chmsg_eid) ) {
            $r = $this->onread_get_chmsgeid_from_chmsgid($chmsgid);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                return $r; 
            }
            $chmsg_eid = $r;
        }
        
        $loads = $this->load_entity($chmsg_eid);
        
        return $loads;
        
    }

    protected function write_new_in_database($args) {
        $now = round(microtime(TRUE)*1000);
        
        //convid, conv_eid, actid, acteid, tgtid, tgt_eid, locip, useragt, message
        
        //On ajoute la conversation
        $QO = new QUERY("qryl4chbxmsgn3");
        $params = array(
            ":convid"           => $args["convid"], 
            ":message"          => $args["message"], 
            ":actor"            => $args["actid"], 
            ":target"           => $args["tgtid"], 
            ":fetime"           => date("Y-m-d G:i:s",($args["fetime"]/1000)),
            ":fetime_tstamp"    => $args["fetime"],
            ":locip"            => $args["locip"], 
            ":useragt"          => $args["useragt"], 
            ":cdate"            => date("Y-m-d G:i:s",($now/1000)), 
            ":cdate_tstamp"     => $now
        );
        $id = $QO->execute($params);
        
        $eid = $this->entity_ieid_encode($now,$id);
        
        //Mise à jour avec l'identifiant externe
        $QO = new QUERY("qryl4chbxmsgn4");
        $params = array( ":id" => $id, ":eid" => $eid);
        $QO->execute($params);
        
        
        /*
         * [DEPUIS 14-11-15] @author
         *      On lance l'opération d'entregistrement des UrlInContent.
         *      S'il n'y a pas d'URL, la méthode renvera FALSE.
         */
        $TXH = new TEXTHANDLER();
        if ( $TXH->ExtractURLs($args["message"]) ) {
            $type = "UCTP_MI";
            $URLIC = new URLIC();
            $r = $URLIC->URLIC_oncreate($args["message"], $id, $eid, $type, session_id(), $args["locip"], $args["curl"], $args["useragt"]);
        }
        
        
        $ids = [$id, $eid];
        return $ids;
    }
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** SPECIFICS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/

    /******************************************************************************************************************************************************/
    /********************************************************************* ONCREATE *************************************************************************/

    
    private function oncreate_treat_msg ($s, &$usertags = NULL, &$kws = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        /*
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
//        if ( is_string($s) && !preg_match($this->_TST_MSG_RGX,$s) ) {
//            return "__ERR_VOL_MSG_MSM";
//        }
        
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
        
        
    /******************************************************************************************************************************************************/
    /********************************************************************* ONREAD *************************************************************************/
    
    public function onread_get_chmsgeid_from_chmsgid ($chmsgid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists_with_id($chmsgid,TRUE);
        if (! $r ) {
            return "__ERR_VOL_CBM_GONE";
        } else {
            return $r["chmsg_eid"];
        }
    }
    
    
    public function onread_GetUrlics($eid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $eid);
        
        $urlics = []; 
        /*
         * [NOTE 17-11-15]
         *      On utilisera TOUJOURS $eid car il est unique dans la table quand id peut porter à confusion et fausser le résultat.
         */
        $QO = new QUERY("qryl4chbxmsgn19");
        $params = array(':ceid' => $eid);
        $datas = $QO->execute($params);
        if ( $datas ) {
            if ( $datas && $_WITH_FE_OPT ) {
                $urlics = $datas;
                array_walk($urlics,function(&$i,$k){
                    $i = [
                        'uicid'     => $i['uic_eid'],
                        'uic_url'   => $i['uic_gvnurl'],
                        'uic_cid'   => $i['uic_uceid']
                    ];
                });
            } else {
                $urlics = $datas;
            }
        }
            
        return $urlics;        
    }

    
    public function onread_UnreadGet($uid, $cpi = NULL, $_OPTIONS = NULL) {
        //cpi : Conversion Pivot Identifiant externe (Utilisé PAR DEFAUT pour l'exclusion de certains messages), $uid : Identifiant interne
        /*
         * OPTIONS (Tableau) : 
         *      ONLYTHIS : Ne prendre en compte que les Messages de cette Conversations, 
         *      GROUPBY : Le nombre de correspond aux Conversations non lus. Dans le cas contraire, il s'agit strictement du nombre de mesages non lus
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
         
        
        /*
         * [DEPUIS 11-11-15]
         *      On controle les OPTIONS
         */
        if ( $_OPTIONS && !array_intersect($_OPTIONS,["ONLYTHIS","GROUPBY"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        } else if ( $_OPTIONS ) {
            /*
             * On s'assure que dans ces cas, le pivot est bien présent
             */
            if ( in_array("ONLYTHIS",$_OPTIONS) && !$cpi ) {
                return "__ERR_VOL_WRG_DATAS";
            } 
        }
        
        $PA = new PROD_ACC();
        $utab = $PA->exists_with_id($uid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *      Dans le cas où on a un pivot, on vérifie s'il existe.
         */
        if ( $cpi ) {
            $CBCONV = new CHBX_CONVRS();
            /*
             * [NOTE]
             *      On ne met pas de 'del_opt' car cela fausserait la compréhension de la réponse pour FE.
             *      Si on a FALSE à cause de l'utilisateur, dire que CONV_GONE est faux. Il faut renvoyer une erreur de type U_G.
             */
            $ctab = $CBCONV->exists($cpi);
            if (! $ctab ) {
                return "__ERR_VOL_CNV_GONE";
            }
        }
        
        
        /*
         * ETAPE :
         *      On récupère les données sur le nombre de messages non lus en fonction de la présence du pivot.
         * RAPPEL : 
         *      Le role du pivot est de permettre de ne pas prendre en compte les messages d'une Conversion donnée.
         *      En effet, si l'utilisateur est déjà focus sur une Conversation, il ne serait pas judicieux que de lui notifier des informations relatives à la CONV sur laquelle il est déjà.
         * [DEPUIS 11-11-15] @author BOR
         *      On récupère le nombre en fonction des OPTIONS s'ils existent.
         */
        if ( $_OPTIONS ) {
            
            //CAS : ONLYTHIS
            if ( in_array("ONLYTHIS",$_OPTIONS) && in_array("GROUPBY",$_OPTIONS) ) {
                $QO = new QUERY("qryl4chbxmsgn18_gpby");
                $params = array( 
                    ':uid' => $uid, 
                    ':cpi' => $ctab["convid"] 
                );
            } else if ( in_array("ONLYTHIS",$_OPTIONS) ) {
                $QO = new QUERY("qryl4chbxmsgn18");
                $params = array( 
                    ':uid' => $uid, 
                    ':cpi' => $ctab["convid"] 
                );
            } 
            //CAS : GROUPBY
            else if ( in_array("GROUPBY",$_OPTIONS) ) {
                if ( $cpi ) {
                    $QO = new QUERY("qryl4chbxmsgn17_gpby");
                    $params = array( 
                        ':uid' => $uid, 
                        ':cpi' => $ctab["convid"] 
                    );
                } else {
                    $QO = new QUERY("qryl4chbxmsgn16_gpby");
                    $params = array( ':uid' => $uid );
                }
            }
            $datas = $QO->execute($params);
            
        } else {
            /*
             * NOTE :
             *      Le comportement par défaut en cas de non précision est :
             *      (1) Si on a un pivot, récupérer le nombre stricte de messages non lus en excluant le pivot.
             *      (2) Sans pivot, récupérer le nombre stricte de messages non lus.
             */
            if ( $cpi ) {
                $QO = new QUERY("qryl4chbxmsgn17");
                $params = array( 
                    ':uid' => $uid, 
                    ':cpi' => $ctab["convid"] 
                );
                $datas = $QO->execute($params);
            } else {
                $QO = new QUERY("qryl4chbxmsgn16");
                $params = array( ':uid' => $uid );
                $datas = $QO->execute($params);
            }
        }
        
        return ( $datas ) ? $datas[0]["cn"] : 0;
        
    }
   


    /**********************************************************************************************************************************************************/
    /*********************************************************************** ONUPDATE *************************************************************************/
 
    public function onalter_UnreadUpd ($ids, $rd, $ssid, $locip, $uagnt = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ids,$rd,$ssid,$locip]);
        
        /*
         * [NOTE]
         *      On réduit au maximum les dépendances en ne vérifiant ni l'utilisateur, ni la conversation ni l'existence de chaque message.
         *      Par la même occasion on gagne en performance.
         */
        
        /*
         * ETAPE :
         *      On effectue des opérations sur 
         */
        $ids = (! is_array($ids) ) ? explode(",", $ids) : $ids;
        $ids = array_filter($ids, function($v){
            return($v||FALSE);
        });
        
        if (! ( is_array($ids) && !empty($ids) && count($ids) ) ) {
            return "__ERR_VOL_FAILED";
        }
        
//        var_dump($ids, $rd, $ssid, $locip, $uagnt);
//        exit();
            
        /*
         * ETAPE :
         *      On met à jour les messages
         */
        $ids = ( is_array($ids) ) ? implode("','", $ids) : $ids;
        
        $date = date("Y-m-d G:i:s",($rd/1000));
        $QO = new QUERY();
        $qbody = "UPDATE ChatBox_Messages ";
        $qbody .= " SET chmsg_rdate = :date,";
        $qbody .= " chmsg_rdate_tstamp = :time,";
        $qbody .= " chmsg_rd_locip = :locip,";
        $qbody .= " chmsg_rd_useragt = :uagnt,";
        $qbody .= " chmsg_rd_ssid = :ssid";
        $qbody .= " WHERE chmsg_eid IN ('".$ids."'); ";
        $qdbname = $this->default_dbname;
        $qtype = "update";
        $qparams_in = array(
            ":locip"    => $locip, 
            ":ssid"     => $ssid, 
            ":uagnt"    => $uagnt, 
            ":date"     => $date, 
            ":time"     => $rd
        );
//        var_dump($qbody);
//        exit();
        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in);
        $QO->execute($qparams_in); 
        
        return TRUE;
        
    }
    

    /**********************************************************************************************************************************************************/
    /*********************************************************************** ONDELETE *************************************************************************/
    
    public function ondelete_delForMe ($meid,$ueid) {
        /*
         * Permet d'engager une procédure de demande de suppression de Message pour un des Acteurs tant que les conditions sont réunies.
         * Pour que la demande soit acceptée, il faut que toutes les conditions suivantes soient réunies :
         *  (1) Le Message existe 
         *  (2) L'utilisateur existe et ne fait pas l'objet d'une demande de suppression de Compte
         *  (3) L'utilisateur est le propriétaire du Message ou il en est le destinataire
         *  (4) Le Message ne fait pas déjà l'objet d'une demande de suppression (Actor, Target, System) ou est en attente de suppression définitve
         *  
         * Dans le cas où, à la fin de l'opération, le Message respecte les conditions pour une suppression définitive.
         * Le Message est supprimé. Cette règle peut ne pas s'appliquer s'il y a un mécanisme de mise en attente sur les Messages avant suppression.
         * 
         * RAPPEL : 
         *  -> On passe les identifiants externes dans l'idée de forcer à les convertir en identifiant interne et ainsi récupérer la table ou simplement vérifier l'existence du Message
         *  -> Si on se retrouve en face d'un cas où le Message a atteint ou dépasser la date de suppression définitive, on ne fait rien pour ne pas perturber le processus de suppression automatique.
         * 
         * ATTENTION : La méthode ne prend pas (encore) en compte la demande faite par la systeme
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //(1) Le Message existe ?
        $mtab = $this->exists($meid);
        if (! $mtab ) {
            return "__ERR_VOL_CBM_GONE";
        }
        
        //(2) L'utilisateur existe et ne fait pas l'objet d'une demande de suppression de Compte ?
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        //(3) L'utilisateur est le propriétaire du Message ou il en est le destinataire
        if (! in_array(intval($utab["pdaccid"]), [intval($mtab["chmsg_target"]),intval($mtab["chmsg_actor"])]) ) {
            return "__ERR_VOL_DNY_AKX";
        }
        $itrg = ( intval($utab["pdaccid"]) === intval($mtab["chmsg_target"]) ) ? TRUE : FALSE;
        
        //(4) Le Message ne fait pas déjà l'objet d'une demande de suppression (Actor, Target, System) ou est en attente de suppression définitve ?
        if (
            (
                ( ( !$itrg && !empty($mtab["chmsg_ad_date_tstamp"]) ) || ( $itrg && !empty($mtab["chmsg_td_date_tstamp"]) ) ) || ( !empty($mtab["chmsg_nxtdldate_tstamp"]) )
            ) | ( !empty($mtab["chmsg_sd_date_tstamp"]) ) 
        ) { return "__ERR_VOL_CBM_UVLB"; }
        
        //On lance le processus de demande de suppression
        $now = round(microtime(TRUE)*1000);
        $QO = ( $itrg ) ? new QUERY("qryl4chbxmsgn11") : new QUERY("qryl4chbxmsgn10");
        $params = array ( 
            ':id'       => $mtab["chmsgid"], 
            ':date'     => date("Y-m-d G:i:s",($now/1000)), 
            ':tstamp'   => $now, 
            ':rsn'      => 1 
        );
        $QO->execute($params);
        
        //On vérifie si le cas cas de la suppression définitve est atteint
        //RAPPEL : Si la demande est fait par TARGET alors ne compte que la date de ACTOR et vice versa
        if ( ( $itrg && !empty($mtab["chmsg_ad_date_tstamp"]) ) | ( !$itrg && !empty($mtab["chmsg_td_date_tstamp"]) ) ) {
            $now = round(microtime(TRUE)*1000) + $this->_NXT_TDL_MS;
            $QO = new QUERY("qryl4chbxmsgn13");
            $params = array ( 
                ':id'       => $mtab["chmsgid"], 
                ':date'     => date("Y-m-d G:i:s",($now/1000)), 
                ':tstamp'   => $now
            );
            $QO->execute($params);
        }
        
        return TRUE;
        
    }
    
    
    /**********************************************************************************************************************************************************/
    /************************************************************************ QUERIES *************************************************************************/
    private function QueriesWareHouse() {
        /*
         * La méthode permet de conserver des requetes SQL utiles.
         * La plus part du temps, il s'agit de requêtes qui servent pour la phase de développement ou de test
         */
        $QUERIEs = [
            [
                "title" => "Réinitialiser les champs relatifs à la suppression de la Conversation",
                "query" => "
                    UPDATE `chatbox_conversations` 
                    SET `conv_ad_date_tstamp` = NULL,
                    `conv_ad_date` = NULL,
                    `conv_ad_date_tstamp` = NULL,
                    `conv_ad_rsncaz` = NULL,
                    `conv_td_date` = NULL,
                    `conv_td_date_tstamp` = NULL,
                    `conv_td_rsncaz` = NULL,
                    `conv_nxtdldate` = NULL,
                    `conv_nxtdldate_tstamp` = NULL
                    WHERE = convid = ?;"
            ],
        ];
    }
    
    /*************************************************************************************************************************************************************************/
    /*************************************************************************** GETTERS & SETTERS ***************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    
}

?>