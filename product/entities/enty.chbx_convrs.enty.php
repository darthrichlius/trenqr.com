<?php


class CHBX_CONVRS extends PROD_ENTITY {
    
    private $convid;
    private $conv_eid;
    
    /**** ACTOR SCOPE */
    private $conv_actid;
    private $conv_acteid;
    private $conv_actgid;
    private $conv_actfn;
    private $conv_actpsd;
    private $conv_actgdr;
    private $conv_actppic;
    private $conv_acttodl;
    
    /**** TARGET SCOPE */
    private $conv_tgtid;
    private $conv_tgteid;
    private $conv_tgtgid;
    private $conv_tgtfn;
    private $conv_tgtpsd;
    private $conv_tgtgdr;
    private $conv_tgtppic;
    private $conv_tgttodl;
    
    //Ces deux dernières données correspondent aussi à celles du premier message
    private $conv_locip;
    private $conv_useragt;
    private $conv_rmbr;
    private $conv_break;
    /*
     * Cette donnée est facultative car une conversation peut se retrouver sans message.
     * De plus, elle ne représente pas le dernier message réel mais ...
     * celui qui est considéré comme tel à l'instant de la lecture.
     * Mais encore, cela permet d'afficher un échantillon de la conversation lorsqu'on liste la conversation.
     * Dans le cas d'une nouvelle conversation, le dernier message correspond logiquement au premier message.
     */
    private $conv_lmsgtab;
    
    private $conv_cdate;
    private $conv_cdate_tstamp;
    private $conv_ad_date;
    private $conv_ad_date_tstamp;
    private $conv_ad_rsncaz;
    private $conv_td_date;
    private $conv_td_date_tstamp;
    private $conv_td_rsncaz;
    private $conv_sd_date;
    private $conv_sd_date_tstamp;
    private $conv_sd_rsncaz;
    private $conv_nxtdldate;
    private $conv_nxtdldate_tstamp;
    
    /********* RULES **********/
    private $_FMSGS_LIMIT;
    private $_CONV_FIL_DEFLT;
    private $_CONV_FILTERS;
    private $_FCONVRS_LIMIT;
    private $_NXT_TDL_MS;
    private $_DRT_DEFVAL;
    /*
     * Un utilisateur doit avoir effectué une action dans les x minutes mentionnées ci-dessous pour être considéré comme idéalement "Actif".
     */
    private $_MIN_ACTY_TIME;
    /*
     * Dans le cas où on doit attendre des données sur les Messages en ce qui concerne une Conversation,
     * cette donnée indique le nombre de secondes d'attente autorisé.
     * 
     * ATTENTION : Cette option doit être utilisé intelligement car cela peut ralentir les autres opérations.
     * Il est donc recommandé d'utiliser des canaux différents.
     */
    public static $_WAIT_MSGS_FOR = 50;
    /*
     * Période durant lequel on sleep avant de réessayer
     */
    public static $_WAIT_MSGS_FOR_SLEEP = 5;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["convid","conv_eid","conv_actid","conv_acteid","conv_actgid","conv_actfn","conv_actpsd","conv_actgdr","conv_actppic","conv_acttodl","conv_tgtid","conv_tgteid","conv_tgtgid","conv_tgtfn","conv_tgtpsd","conv_tgtgdr","conv_tgtppic","conv_tgttodl","conv_lmsgtab","conv_locip","conv_useragt","conv_rmbr","conv_break","conv_cdate","conv_cdate_tstamp","conv_ad_date","conv_ad_date_tstamp","conv_ad_rsncaz","conv_td_date","conv_td_date_tstamp","conv_td_rsncaz","conv_sd_date","conv_sd_date_tstamp","conv_sd_rsncaz","conv_nxtdldate","conv_nxtdldate_tstamp"];
        $this->needed_to_loading_prop_keys = ["convid","conv_eid","conv_actid","conv_acteid","conv_actgid","conv_actfn","conv_actpsd","conv_actgdr","conv_actppic","conv_acttodl","conv_tgtid","conv_tgteid","conv_tgtgid","conv_tgtfn","conv_tgtpsd","conv_tgtgdr","conv_tgtppic","conv_tgttodl","conv_lmsgtab","conv_locip","conv_useragt","conv_rmbr","conv_break","conv_cdate","conv_cdate_tstamp","conv_ad_date","conv_ad_date_tstamp","conv_ad_rsncaz","conv_td_date","conv_td_date_tstamp","conv_td_rsncaz","conv_sd_date","conv_sd_date_tstamp","conv_sd_rsncaz","conv_nxtdldate","conv_nxtdldate_tstamp"];
        
        $this->needed_to_create_prop_keys = ["conv_acteid", "conv_tgteid", "fetime", "conv_locip", "conv_useragt", "conv_fmsg", "load_anyway"];
        
        /********* RULES **********/
        $this->_FMSGS_LIMIT = 30;
//        $this->_FMSGS_LIMIT = 10;
        $this->_CONV_FIL_DEFLT = "F_WTF";
        $this->_CONV_FILTERS = ["F_WTF","F_OLO","F_OLTO","F_MSO"];
//        $this->_FCONVRS_LIMIT = 1; //DEV, TEST, DEBUG
        $this->_FCONVRS_LIMIT = 6;
        $this->_DRT_DEFVAL = "bot";
        $this->_MIN_ACTY_TIME = 10;
        $this->_NXT_TDL_MS = 3600000*24*14; //14 jours (2 semaines)
    }

    protected function build_volatile($args) { }

    public function exists($cveid, $with_del_opt = FALSE) {
        //QUESTION : Est-il une conversation avec l'identifiant fourni ? (FALSE, DONNEES sur l'évènement)
        /*
         * L'option $with_del_opt spécifie s'il faut prendre en compte le fait que la Conversation soit en attente de suppression définitive.
         * On ne controle pas le cas spécifique de l'utilisateur courant. C'est à CALLER de faire cet effort pour éviter de perdre en souplesse.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_string($cveid) || is_int($cveid) ) ) {
            return;
        } 
        
        $QO = (! $with_del_opt) ? new QUERY("qryl4chbxcvn2") : new QUERY("qryl4chbxcvn15");
        $params = array( ':cveid' => $cveid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }

    public function exists_with_id($cvid, $with_del_opt = FALSE) {
        //QUESTION : Est-il une conversation avec l'identifiant fourni ? (FALSE, DONNEES sur l'évènement)
        /*
         * L'option $with_del_opt spécifie s'il faut prendre en compte le fait que la Conversation soit en attente de suppression définitive.
         * On ne controle pas le cas spécifique de l'utilisateur courant. C'est à CALLER de faire cet effort pour éviter de perdre en souplesse.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_string($cvid) || is_int($cvid) ) ) {
            return;
        } 
        
        $QO = (! $with_del_opt) ? new QUERY("qryl4chbxcvn1") : new QUERY("qryl4chbxcvn16");
        $params = array( ':cvid' => $cvid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }

    public function exists_with_protas($actor1,$actor2) {
        //QUESTION : Est-il un evenenemt avec les acteurs fournis ? (FALSE, DONNEES sur l'évènement)
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( ( is_string($actor1) | is_int($actor1) ) && ( is_string($actor2) | is_int($actor2) ) ) ) {
            return;
        } 
        
        //Contacter la base de données et vérifier si la Relation existe.
        $QO = new QUERY("qryl4chbxcvn3");
        $params = array( 
            ':actor1'   => $actor1, 
            ':actor2'   => $actor1, 
            ':target1'  => $actor2, 
            ':target2'  => $actor2, 
        );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas;
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

    protected function load_entity($id, $std_err_enbaled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Intéroger la base de données pour récupérer les données sur la RELATION
        /*
         * [22-01-15] @Lou
         * Pour des raisons de souplesse, on n'utilise pas l'option with_del_option par défaut.
         * CALLER peut le faire en faisant appel directement à exist();
         */
        $datas = $this->exists($id);
        
        if ( !$datas && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( !$datas && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $loads = [
            "convid"                => $datas["convid"],
            "conv_eid"              => $datas["conv_eid"],
            "conv_useragt"          => $datas["conv_useragt"],
            "conv_locip"            => $datas["conv_locip"],
            "conv_rmbr"             => $datas["conv_rmbr"],
            "conv_break"            => $datas["conv_break"],
            
            /**** ACTOR SCOPE */
            "conv_actid"            => $datas["actid"],
            "conv_acteid"           => $datas["acteid"],
            "conv_actgid"           => $datas["actgid"],
            "conv_actfn"            => $datas["actfn"],
            "conv_actpsd"           => $datas["actpsd"],
            "conv_actgdr"           => $datas["actgdr"],
//            "conv_actppic" => $datas[""],
            "conv_acttodl"          => $datas["acttodl"],
            
            /**** TARGET SCOPE */
            "conv_tgtid"            => $datas["tgtid"],
            "conv_tgteid"           => $datas["tgteid"],
            "conv_tgtgid"           => $datas["tgtgid"],
            "conv_tgtfn"            => $datas["tgtfn"],
            "conv_tgtpsd"           => $datas["tgtpsd"],
            "conv_tgtgdr"           => $datas["tgtgdr"],
//            "conv_tgtppic" => $datas[""],
            "conv_tgttodl"          => $datas["tgttodl"], 

            "conv_cdate"            => $datas["conv_cdate"],
            "conv_cdate_tstamp"     => $datas["conv_cdate_tstamp"],
            "conv_ad_date"          => $datas["conv_ad_date"],
            "conv_ad_date_tstamp"   => $datas["conv_ad_date_tstamp"],
            "conv_ad_rsncaz"        => $datas["conv_ad_rsncaz"],
            "conv_td_date"          => $datas["conv_td_date"],
            "conv_td_date_tstamp"   => $datas["conv_td_date_tstamp"],
            "conv_td_rsncaz"        => $datas["conv_td_rsncaz"],
            "conv_sd_date"          => $datas["conv_td_date"],
            "conv_sd_date_tstamp"   => $datas["conv_td_date_tstamp"],
            "conv_sd_rsncaz"        => $datas["conv_td_rsncaz"],
            "conv_nxtdldate"        => $datas["conv_nxtdldate"],
            "conv_nxtdldate_tstamp" => $datas["conv_nxtdldate_tstamp"]
        ];
        
        /************************* EXTRAS DATAS **************************/
        $PA = new PROD_ACC();
        $loads["conv_actppic"] = $PA->onread_acquiere_pp_datas($datas["actid"]);
//        $loads["conv_actppic"] = $PA->onread_acquiere_pp_datas($datas["convid"]); //[DEPUIS 10-08-15] @BOR
        $loads["conv_tgtppic"] = $PA->onread_acquiere_pp_datas($datas["tgtid"]);
//        $loads["conv_tgtppic"] = $PA->onread_acquiere_pp_datas($datas["convid"]); //[DEPUIS 10-08-15] @BOR
        
        /**** LATEST MESSAGE */
        /*
         * RAPPEL :
         * On prend le dernier Message non supprimé "physiquement". 
         * Etant donné que l'on ne peut pas prendre en compte l'utilisateur, on ne peut pas traité le cas où l'on masque le Message pour CU.
         * Cependant, la méthode peut naturellement écarter les Messages destinés à être définitivement supprimés. C'est en l'occurrence son paramètre par défaut.
         */
        $lmg_tab = $this->onread_lastestmessage($loads["convid"]);
        $loads["conv_lmsgtab"] = $lmg_tab;

        $this->init_properties($loads);
        $this->is_instance_load = TRUE;
        return $loads;
    }

    public function on_alter_entity($args) {
        
    }

    public function on_create_entity($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : conv_acteid, conv_tgteid, fetime, conv_locip, conv_useragt, conv_fmsg, load_anyway
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( empty($v) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $PACC = new PROD_ACC();
        /*
         * On vérifie si actor le cas échéant. Ne pas vérifier permet de gagner en performance quand on sait qu'un Chat doit être performant. 
         * De plus, par expérience, des vérifications sont effectuées en amont sur la validité du Compte encore plus s'il s'agit d'ACOTR.
         */
//        if ( key_exists("check_actor", $args) && $args["check_actor"] === TRUE ) {
            $act_xst = $PACC->exists($args["conv_acteid"],TRUE);
            if (! $act_xst ) {
                return "__ERR_VOL_ACT_GONE";
            }
//        }
//        if ( key_exists("check_target", $args) && $args["check_target"] === TRUE ) {
            $tgt_xst = $PACC->exists($args["conv_tgteid"],TRUE);
            if (! $tgt_xst ) {
                return "__ERR_VOL_TGT_GONE";
            }
//        }
        
        //On vérifie que la relation entre les deux protagonistes permet la création d'une conversation
        $tcc = $this->TheyCanChat($act_xst["pdaccid"], $tgt_xst["pdaccid"]);
        if (! $tcc ) {
            return "__ERR_VOL_RULES_REL";
        }
        
        //On vérifie qu'il n'y pas pas déjà une ou plusieurs conversations encours 
        $convtabs = $this->exists_with_protas($act_xst["pdaccid"],$tgt_xst["pdaccid"]);
//        var_dump($convtabs);
        if ( $convtabs ) {
            /*
             * La règle sur la création de nouvelles conversations varie selon la nature de la conversation qui existe déjà.
             * Certains cas ne permettent de créer une nouvelle conversation. Il faut respecter les règles suivantes :
             *  (1) Si des conversations existent ET que la dernière conversation est en attente de supprression effective :
             *      On crée une nouvelle conversation !
             *  (2) Si des conversations existent ET que la dernière conversation fait l'objet d'une demande de suppression (OU PAS) de la part ACTOR OU TARGET :
             *      On ne crée pas de nouvelle conversation. On renvoie l'identifiant de la conversation. 
             *      Il n'y a aucun risque en ce qui concerne la diffusion d'anciens messages car ils sont soit supprimés ...
             *      ... ou avec une mention de suppression qui indique qu'ils ne doivent plus être lu.
             *      La date de création de la conversation sera donc sa date de création virtuelle qui doit correspondre à celle du premier message ...
             *      ... après que l'utilisateur concerné (ACTOR) ait décidé de supprimer la conversation. Si Actor n'a jamais décidé de supprimer la conversation ...
             *      c'est la date de création originelle qui ait prise.
             *      
             */
            
            //On vérifie si la conversation a une date de suppression effective
            if ( empty($convtabs[0]["conv_sd_date_tstamp"]) && empty($convtabs[0]["conv_nxtdldate_tstamp"]) ) 
//            if ( 
//                ( !empty($convtabs[0]["conv_ad_date_tstamp"]) | !empty($convtabs[0]["conv_td_date_tstamp"] ) )
//                && empty($convtabs[0]["conv_sd_date_tstamp"]) && empty($convtabs[0]["conv_nxtdldate_tstamp"])
//            ) 
            {
                return ( $args["load_anyway"] === TRUE ) ? 
                ["ALRDY",$this->load_entity($convtabs[0]["conv_eid"])] 
                : ["ALRDY",[$convtabs[0]["convid"],$convtabs[0]["conv_eid"]]];
            } 
        }
       
        $args["conv_actid"] = $act_xst["pdaccid"]; 
        $args["conv_tgtid"] = $tgt_xst["pdaccid"];
                
        //On écrit dans la base de données
        $ids = $this->write_new_in_database($args);
        
        //On ajoute le message
        $CBMSG = new CHBX_MSG();
        $args_new_msg = [
            "conv_eid"  => $ids[1], 
            "message"   => $args["conv_fmsg"], 
            "act_eid"   => $args["conv_acteid"], 
            "tgt_eid"   => $args["conv_tgteid"], 
            "fetime"    => $args["fetime"], 
            "locip"     => $args["conv_locip"], 
            "useragt"   => $args["conv_useragt"]
        ];
        $msg_tab = $CBMSG->on_create_entity($args_new_msg);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $msg_tab)  ) {
            return $msg_tab;
        }
            
        //On load la classe
        return $this->load_entity($ids[1]);
    }

    public function on_delete_entity($args) {
        
    }

    public function on_read_entity($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $convid = $conv_eid = NULL;
        if ( !( !empty($args) && is_array($args) && key_exists("conv_eid", $args) && !empty($args["conv_eid"]) ) )
        {
            if ( key_exists("convid", $args) && !empty($args["convid"]) && !is_array($args["convid"]) ) {
                $convid = $args["convid"];
            } else if ( !empty($this->convid) ) {
                $convid = $this->convid;
            } else {
                if ( empty($this->conv_eid) ) {
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
                } else {
                    $conv_eid = $this->conv_eid;
                }
            }
            
        } else { 
            $conv_eid = $args["conv_eid"]; 
        }
        
        //[22-01-15] @Lou On effectue les conversations dans tous les cas sinon CALLER ne pourra pas être averti par une ERROR VOL, ce qui est très importante.
        if ( !isset($conv_eid) | empty($conv_eid) ) {
            $r = $this->onread_get_conveid_from_convid($convid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                return $r; 
            }
            $conv_eid = $r;
        } else {
            $r = $this->onread_get_convid_from_conveid($conv_eid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                return $r; 
            }
        }
        
        $loads = $this->load_entity($conv_eid);
        
        return $loads;
    }

    protected function write_new_in_database($args) {
        $now = round(microtime(TRUE)*1000);
        
        //conv_actid, conv_acteid, conv_tgtid, conv_tgteid, conv_locip, conv_useragt, conv_fmsg
        
        //On ajoute la conversation
        $QO = new QUERY("qryl4chbxcvn4");
        $params = array(
            ":actor"        => $args["conv_actid"], 
            ":target"       => $args["conv_tgtid"], 
            ":locip"        => $args["conv_locip"], 
            ":useragt"      => $args["conv_useragt"], 
            ":cdate"        => date("Y-m-d G:i:s",($now/1000)), 
            ":cdate_tstamp" => $now
        );
        $id = $QO->execute($params);
        
        $eid = $this->entity_ieid_encode($now,$id);
        
        //Mise à jour avec l'identifiant externe
        $QO = new QUERY("qryl4chbxcvn5");
        $params = array( ":id" => $id, ":eid" => $eid);
        $QO->execute($params);
        
        $ids = [$id, $eid];
        return $ids;
    }
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** SPECIFICS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    public function TheyCanChat ($actid, $tgtid) {
        /*
         * Permet de vérifier si deux protagonistes peuvent converser.
         * Pour cela, on vérifie le type de lien entre les protagonistes.
         * A la version vb1.1412 et vb1.1501, l'autorisation est accodée seulement si les deux protagonistes sont "amis"
         */
        
        $REL = new RELATION();
        $tcc = $REL->friend_theyre_friends($actid, $tgtid);
        return $tcc;
    }
    
    /**************************************************************************** ONREAD SCOPE ******************************************************************************/
    
    public function onread_get_conveid_from_convid ($convid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists_with_id($convid,TRUE);
        if (! $r ) {
            return "__ERR_VOL_CONV_GONE";
        } else {
            return $r["conv_eid"];
        }
    }
    
    public function onread_get_convid_from_conveid ($conveid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists($conveid,TRUE);
        if (! $r ) {
            return "__ERR_VOL_CONV_GONE";
        } else {
            return $r["convid"];
        }
    }
    
    public function onread_lastestmessage ($cvid, $check_conv = FALSE, $with_uvlb_opt = TRUE) {
        /*
         * Récupérer les données du dernier message posté dans la conversation.
         * Logiquement, le dernier message peut tout aussi bien être le premier message de la conversation.
         * On passe l'identifiant plutot que celui externe pour maximiser la sécurité
         * 
         * $check_conv : Permet de spécifier s'il faut effectuer des opérations de vérification sur la conversation.
         */
        $args = [$cvid];
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$args]);
        
        $convtab = NULL;
        if ( $check_conv === TRUE ) {
            $convtab = $this->exists_with_id($cvid);
            if (! $convtab ) {
                return "__ERR_VOL_CONV_GONE";
            }
        }
        
        $QO = (! $with_uvlb_opt ) ? new QUERY("qryl4chbxcvn6") : new QUERY("qryl4chbxcvn14");
        $params = array( ':cvid' => $cvid, ':limit' => 1 );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return 0;
        } else {
            $eid = $datas[0]["chmsg_eid"];
            $CBMSG = new CHBX_MSG();
            $tab = $CBMSG->on_read_entity(["chmsg_eid"=>$eid]);
            
            return $tab;
        }
    }
    
    public function onread_FirstMessages($cuid, $conveid, $lightway = FALSE, $with_del_opt = FALSE) {
        /*
         * Récupère les x messages les plus récents d'une conversation.
         * Ce sont les messages que l'utilisateur voit quand il ouvre pour la première fois une conversation. 
         * 
         * Si lightway == TRUE, on ne passe pas par read mais par une requete qui récupère les données de base (acteur, cible, message, date de création) 
         * Le but de cette dernière option est d'améliorer la performance de l'opération en évitant de passer par read qui est une opération lourde.
         * 
         * [20-01-15]
         * $cuid Permet surtout de travailler sur certains cas de tries
         * $with_del_opt == TRUE, on précise que l'on ne veut des Messages ayant une mention de suppression. La suppression concerne CU, le systeme ou une suppression défintive.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid,$conveid]);
        
        $convid = $this->onread_get_convid_from_conveid($conveid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $convid) ) {
            return $convid;
        } 
        
        $f_cbmsgs = [];
        $CBM = new CHBX_MSG();
        if ( $lightway ) {
            $QO = new QUERY("qryl4chbxmsgn7");
            $params = array( 
                ':cvid'     => $convid, 
                ':limit'    => $this->_FMSGS_LIMIT 
            );
            $datas = $QO->execute($params);
            if (! $datas ) {
                return;
            } else if ( $datas && !$with_del_opt ) {
                return $datas;
            }
            
            foreach ($datas as $cbm) {
//                var_dump($with_del_opt === TRUE 
//                    &&(    
//                        ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
//                        || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
//                        || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
//                    ));
                /*
                var_dump($with_del_opt,( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                        ,( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                        ,( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                        ,$with_del_opt === TRUE 
                    &&(    
                        ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                        || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                        || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                    ));
                 
                 //*/
                if 
                (
                    $with_del_opt === TRUE 
                    && (    
                        ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                        || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                        || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                    )
                ) {
                    continue;
                }
                
                /*
                 * [DEPUIS 24-06-16]
                 */
                $usertags = []; $hashs = [];
                $this->oncreate_treat_msg($cbm["chmsg_msg"], $u, $h);

                if ( $h ) {
                    $hashs = $h[1];
                    $cbm["chmsg_msg_hashs"] = $hashs;
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
                    $cbm["chmsg_msg_ustgs"] = $usertags;
                }
                
                $f_cbmsgs[] = $cbm;
            }
            
            return $f_cbmsgs;
        } else {
            //On récupère les identifiants des messages
            $QO = new QUERY("qryl4chbxmsgn6");
            $params = array( ':cvid' => $convid, ':limit' => $this->_FMSGS_LIMIT );
            $datas = $QO->execute($params);
            if (! $datas ) {
                return;
            }
            
            foreach ($datas as $cbm) {
//                var_dump(( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
//                        ,( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
//                        ,( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) ));
                $c__ = $CBM->on_read_entity(["chmsg_eid"=>$cbm["chmsg_eid"]]);
                if 
                (
                    ( $c__  && is_array($c__) )  
                    && $with_del_opt === TRUE 
                    && (    
                        ( !empty($c__["chmsg_nxtdldate_tstamp"]) | !empty($c__["chmsg_sd_date_tstamp"]) )
                        || ( intval($cuid) === intval($c__["chmsg_actid"]) && !empty($c__["chmsg_ad_date_tstamp"]) )
                        || ( intval($cuid) === intval($c__["chmsg_tgtid"]) && !empty($c__["chmsg_td_date_tstamp"]) )
                    )
                ) {
                    continue;
                }
                $f_cbmsgs[] = $c__;
                /*
                if 
                (
                    $with_del_opt === TRUE 
                    && (    
                        ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                        || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                        || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                    )
                ) {
                    continue;
                }
                $f_cbmsgs[] = $CBM->on_read_entity(["chmsg_eid"=>$cbm["chmsg_eid"]]);
                //*/
            }
            /*
            foreach ($datas as $cbm) {
                $f_cbmsgs[] = $CBM->on_read_entity(["chmsg_eid"=>$cbm["chmsg_eid"]]);
            }
            //*/
            return $f_cbmsgs;
        }
                
    }
    
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
    
    
    public function onread_FirstMessagesFrom($cuid, $conveid, $lmeid, $drt, $lightway = FALSE, $with_del_opt = FALSE) {
       
        /*
         * Récupère les x messages les plus récents d'une conversation en prenant un message comme pivot.
         * Ce sont les messages que l'utilisateur voit quand il ouvre pour la première fois une conversation. 
         * 
         * Si lightway == TRUE, on ne passe pas par read mais par une requete qui récupère les données de base (acteur, cible, message, date de création) 
         * Le but de cette dernière option est d'améliorer la performance de l'opération en évitant de passer par read qui est une opération lourde.
         * 
         * [20-01-15]
         * $cuid Permet surtout de travailler sur certains cas de tries
         * $with_del_opt == TRUE, on précise que l'on ne veut des Messages ayant une mention de suppression. La suppression concerne CU, le systeme ou une suppression défintive.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid,$conveid,$lmeid]);
        
        $convid = $this->onread_get_convid_from_conveid($conveid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $convid) ) {
            return $convid;
        }
        //On vérifie que le message pivot et on récupère sa table. Pour gagner en performance, on utilise la méthode exist()
        $CBM = new CHBX_MSG();
        $cmsg_tab = $CBM->exists($lmeid);
        
        if ( !$cmsg_tab | $cmsg_tab["conv_eid"] !== $conveid ) {
            /*
             * Si le message pivot n'existe pas, on ne peut pas continuer l'opération car il s'agirait soit 
             *  (1) Une tentative de Hack 
             *  (2) Un message persistent au niveau de FE quand il a été supprimé. Cela peut être possible dans le cas d'une vieille SESSION non à jour
             * Etant donné qu'on a pas la table, on ne peut pas avoir le temps de référence necessaire à la requete permettant de récupérer les messages.
             * Il faut dans tous les cas obliger l'utilisateur a rechargé la page pou permettre une mise à jour des données.
             */
            return "__ERR_VOL_BAD_REF";
        }
        
        //RAPPEL : drt peut avoir comme valeurs : top, fst, bot. Dans notre cas, il ne peut avoir que bot,top
        $drt = ( $drt && is_string($drt) && in_array(strtolower($drt), ["bot","top"]) ) ? strtolower($drt) : $this->_DRT_DEFVAL;
        
        $f_cbmsgs = [];
        if ( $lightway ) {
            if ( $drt === "bot" ) {
                /*
                 * [18-01-15] @Lou
                 * Ajout de la récupération des Messages ultérieurs
                 */
                $QO = new QUERY("qryl4chbxmsgn9");
                $params = array( 
                    ':cvid1'        => $convid, 
                    ':cvid2'        => $convid, 
                    ':pvt_cmid1'    => $cmsg_tab["chmsgid"], 
                    ':pvt_cmid2'    => $cmsg_tab["chmsgid"], 
                    ':pvt_tm1'      => $cmsg_tab["chmsg_fe_cdate_tstamp"], 
                    ':pvt_tm2'      => $cmsg_tab["chmsg_fe_cdate_tstamp"], 
                    ':limit_lt'     => $this->_FMSGS_LIMIT , 
                    ':limit_gt'     => $this->_FMSGS_LIMIT 
                );
    //            $params = array( ':cvid' => $convid, ':pvt_cmid' => $lmeid, ':pvt_tm' => $cmsg_tab["chmsg_fe_cdate_tstamp"], ':limit' => $this->_FMSGS_LIMIT );
                $datas = $QO->execute($params);
            } else {
                $QO = new QUERY("qryl4chbxmsgn14");
                $params = array( 
                    ':cvid'     => $convid, 
                    ':pvt_cmid' => $cmsg_tab["chmsgid"], 
                    ':pvt_tm'   => $cmsg_tab["chmsg_fe_cdate_tstamp"], 
                    ':limit'    => $this->_FMSGS_LIMIT , 
                );
    //            $params = array( ':cvid' => $convid, ':pvt_cmid' => $lmeid, ':pvt_tm' => $cmsg_tab["chmsg_fe_cdate_tstamp"], ':limit' => $this->_FMSGS_LIMIT );
                $datas = $QO->execute($params);
            }
            
            
            if (! $datas ) {
                return;
            } else if ( $datas && !$with_del_opt ) {
                return $datas;
            } else {
                foreach ($datas as $cbm) {
    //                var_dump($with_del_opt === TRUE 
    //                    &&(    
    //                        ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
    //                        || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
    //                        || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
    //                    ));
                    /*
                    var_dump($with_del_opt,( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                            ,( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                            ,( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                            ,$with_del_opt === TRUE 
                        &&(    
                            ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                            || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                            || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                        ));

                     //*/
                    if 
                    (
                        $with_del_opt === TRUE 
                        && (    
                            ( !empty($cbm["chmsg_nxtdldate_tstamp"]) | !empty($cbm["chmsg_sd_date_tstamp"]) )
                            || ( intval($cuid) === intval($cbm["chmsg_actor"]) && !empty($cbm["chmsg_ad_date_tstamp"]) )
                            || ( intval($cuid) === intval($cbm["chmsg_target"]) && !empty($cbm["chmsg_td_date_tstamp"]) )
                        )
                    ) {
                        continue;
                    }
                    $f_cbmsgs[] = $cbm;
                }

                return $f_cbmsgs;
            }
            
        } else {
            //On récupère les identifiants des messages
            if ( $drt === "bot" ) {
                $QO = new QUERY("qryl4chbxmsgn8");
                $params = array
                ( 
                    ':cvid1'        => $convid, 
                    ':cvid2'        => $convid, 
                    ':pvt_tm1'      => $cmsg_tab["chmsg_fe_cdate_tstamp"],
                    ':pvt_tm2'      => $cmsg_tab["chmsg_fe_cdate_tstamp"],
                    ':pvt_cmid1'    => $cmsg_tab["chmsgid"],
                    ':pvt_cmid2'    => $cmsg_tab["chmsgid"],
                    ':limit_lt'     => $this->_FMSGS_LIMIT ,
                    ':limit_gt'     => $this->_FMSGS_LIMIT 
                );
    //            $params = array( ':cvid' => $convid, ':pvt_cmid' => $lmeid, ':pvt_tm' => $cmsg_tab["chmsg_fe_cdate_tstamp"], ':limit' => $this->_FMSGS_LIMIT );
                $datas = $QO->execute($params);
            } else {
                $QO = new QUERY("qryl4chbxmsgn15");
                $params = array
                ( 
                    ':cvid'     => $convid, 
                    ':pvt_tm'   => $cmsg_tab["chmsg_fe_cdate_tstamp"],
                    ':pvt_cmid' => $cmsg_tab["chmsgid"],
                    ':limit'    => $this->_FMSGS_LIMIT ,
                );
    //            $params = array( ':cvid' => $convid, ':pvt_cmid' => $lmeid, ':pvt_tm' => $cmsg_tab["chmsg_fe_cdate_tstamp"], ':limit' => $this->_FMSGS_LIMIT );
                $datas = $QO->execute($params);
            }
            
            if (! $datas ) {
                return;
            }
            
            foreach ($datas as $cbm) {
                $c__ = $CBM->on_read_entity(["chmsg_eid"=>$cbm["chmsg_eid"]]);
                if 
                (
                    ( $c__  && is_array($c__) )  
                    && $with_del_opt === TRUE 
                    && (    
                        ( !empty($c__["chmsg_nxtdldate_tstamp"]) | !empty($c__["chmsg_sd_date_tstamp"]) )
                        || ( intval($cuid) === intval($c__["chmsg_actid"]) && !empty($c__["chmsg_ad_date_tstamp"]) )
                        || ( intval($cuid) === intval($c__["chmsg_tgtid"]) && !empty($c__["chmsg_td_date_tstamp"]) )
                    )
                ) {
                    continue;
                }
                $f_cbmsgs[] = $c__;
            }
            
            return $f_cbmsgs;
        }
                
    }
    
    public function onread_ListFirstConvrs ($ueid, $FILTER = "F_WTF", $options = NULL, $WITH_DEL_OPT = FALSE) {
        /*
         * Liste les conversations d'un conmpte.
         * Seule le premier lot de conversations est renvoyé. 
         * Pour cela, on se fit au filtre de base ou on prend compte le ou les filtres passés en paramètre.
         * Les différentes possibilités sont : 
         *  -> F_WTF : WiThoutFilter, sans filtre. 
         *      On récupère les Conversations par ordre chronologique décroissant avec une limit de 5 Conversations.
         *  -> F_OLO : OnlyOnLine, Seulement ceux en ligne
         *      On ne récupère que les Conversations où la cible est désignée comme "connecté".
         *      *****
         *      Un utilisateur est dit connecté s'il respecte les conditions suivantes :
         *          (1) L'utilisateur a une SESSION active (la plus récente), c'est à dire qu'il ne s'est pas déconnecté
         *          (2) L'utilisateur est en statut "En Ligne". La dernière ligne n'a pas de date de déconnexion
         *          (3) Que le log d'activité de l'utilisateur affiche une activité sur les 10 dernières minutes 
         *      Il faut que ces trois conditions soient réunies pour que l'utilisateur soit dit "Connectée". Sans elles, la donnée serait biaisée.
         *      En effet, si on était dans le meilleur des mondes, toutes les SESSIONs seraient cloturées "proprement" mais ce n'est pas le cas.
         *      La 3ème condition est presque la plus importante. Elle permet de gagner en précision et d'augmenter la probabilité que l'utilisateur soit réellement "En Ligne".
         *      Sinon, on pourrait afficher "En Ligne" et créer de réelles tensions sociales entre utilisateurs à cause de données biaisées.
         *  -> F_OLTO : OnLineTrendOnly, Seulement ceux qui sont connectés sur la Tendance
         *      On ne récupère que les Conversations où la cible est désignée comme "connecté" sur la Tendance sur laquelle je me trouve (celle que je visite)
         *      *****
         *      On respecte les mêmes conditions que pour le filtre F_OLO plus
         *          (4) L'utilisateur doit être sur une Tendance et sur LA Tendance 
         *          Pour vérifier cela, on se fier à des indicateurs :
         *                  (1) L'URL fournit avec la requête
         *                  (2) (Eventuellement) L'hitorique WTO présent dans la superglobale SESSION
         *                  (3) L'historique du log d'activité. On peut vérifier si l'utilisateur a effectuée une action qui fait référence à une Tendance et à LA Tendance.
         *      Ce filtre peut paraitre assez peu fiable.
         *  -> F_MSO : MisSedOnly, Seulement les conversations manquées
         *      On ne récupère que les conversations où l'utilisateur actif n'a pas lu au moins un des messages les plus récents.
         *      *****
         *      Pour ne prélever que les Conversations qui ne contiennent que des messages non lus, on recherche avant tout dans les messages.
         *      Techniquement, il s'agit d'un filtrage des données depusi la base de données ou depuis la partie traitement
         *          (1) On recherche les messages qui n'ont pas de date de lecture 
         *          (2) On effectue cette recherche de manière distincte. Aussi, on ne récupère qu'un message par conversation
         *          (3) On récupère les données sur la Conversation 
         *      L'opération peut être lourde en performance. Cepeandant, ça ne pose pas vraiment de problème car le demandeur s'attend à une dégradation de performance.
         *      Il est de coutume qu'une demande de filtrage prennent du temps. Il s'agit d'une demande qui sort du comportement normal 
         *  
         * Les filtres sont hiérarchisés et presque totalement utilisés de façon monopolistique.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $ueid);
        
        if ( $FILTER && !in_array($FILTER, $this->_CONV_FILTERS) ) {
            return "__ERR_VOL_MSM_FIL";
        } else {
            $FILTER = $this->_CONV_FIL_DEFLT;
        }
        
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid, TRUE);
        if (! $utab ) {
            return "__ERR_VOL_ACT_GONE";
        }
        
        $FNL_CONV = [];
        
        if ( $FILTER === "F_WTF" ) {
            
            //On récupère les identifiants des Conversations ainsi que les dates de création et de lecture du dernier message
            if (! $WITH_DEL_OPT ) {
                $QO = new QUERY("qryl4chbxcvn7");
                $params = array( 
                    ":actor1"   => $utab["pdaccid"], 
                    ":actor2"   => $utab["pdaccid"], 
                    ":limit"    => $this->_FCONVRS_LIMIT );
            } else {
                $QO = new QUERY("qryl4chbxcvn17");
                $params = array( 
                    ":actor1"   => $utab["pdaccid"], 
                    ":actor2"   => $utab["pdaccid"], 
                    ":pvt_uid1" => $utab["pdaccid"], 
                    ":pvt_uid2" => $utab["pdaccid"], 
                    ":limit"    => $this->_FCONVRS_LIMIT );
            }
            $convrs = $QO->execute($params);
            
            if ( $convrs ) {
                foreach ($convrs as $cvrow) {
                    $tgtuid = ( intval($cvrow["chmsg_actor"]) === intval($utab["pdaccid"]) ) ? $cvrow["chmsg_target"] : $cvrow["chmsg_actor"];
                    $tgttab = $PA->exists_with_id($tgtuid, TRUE);
                    if (! $tgttab ) {
                        continue; 
                    }
                    $tgttab = array_merge($tgttab,$PA->onread_acquiere_pp_datas($tgtuid, $tgttab["pdacc_gdr"]));
                    $cvrow["target_tab"] = $tgttab;
                    
                    $FNL_CONV[] = $cvrow;
                }
            } else { return; }
            
        } else if ( $FILTER === "F_OLO" ) {
            
            //On récupère les identifiants des Conversations ainsi que les dates de création et de lecture du dernier message
            $QO = new QUERY("qryl4chbxcvn7");
            $params = array( ':actor1' => $utab["pdaccid"], ':actor2' => $utab["pdaccid"], ":limit" => $this->_FCONVRS_LIMIT );
            $convrs = $QO->execute($params);
            
            if ( $convrs ) {
                foreach ($convrs as $cvrow) {
                    $tgtuid = ( intval($cvrow["chmsg_actor"]) === intval($utab["pdaccid"]) ) ? $cvrow["chmsg_target"] : $cvrow["chmsg_actor"];
                    $tgttab = $PA->exists_with_id($tgtuid, TRUE);
                    if (! $tgttab ) {
                        continue; 
                    }
                    if ( $this->onread_UserSts($tgttab["pdacc_eid"]) !== "S2" ) {
                        continue;
                    }
                    $tgttab = array_merge($tgttab,$PA->onread_acquiere_pp_datas($tgtuid, $tgttab["pdacc_gdr"]));
                    $cvrow["target_tab"] = $tgttab;
                    
                    $FNL_CONV[] = $cvrow;
                }
            } else { return; }
            
        }
        
        return $FNL_CONV;
    }
    
//    $cuid, $conveid, $lmeid, $drt, $lightway = FALSE, $with_del_opt = FALSE
    public function onread_ListFromConvrs ($ueid, $cveid, $drt, $FILTER = "F_WTF", $options = NULL, $WITH_DEL_OPT = FALSE) {
        /*
         * Récupère les Conversations en prenant comme pivot une Conversation.
         * La méthode est similaire à celle utilisant la méthode FIRST à la différence que l'on utilise un pivot et une "direction".
         * Aussi, on respecte les même règles que pour le méthode FIRST.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ueid,$cveid,$drt]);
        
        if ( $FILTER && !in_array($FILTER, $this->_CONV_FILTERS) ) {
            return "__ERR_VOL_MSM_FIL";
        } else {
            $FILTER = $this->_CONV_FIL_DEFLT;
        }
        
        $ctab = $this->exists($cveid);
        if (! $ctab ) {
            return "__ERR_VOL_CNV_GONE";
        }
        
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid, TRUE);
        if (! $utab ) {
            return "__ERR_VOL_ACT_GONE";
        }
        
        $FNL_CONV = [];
        //RAPPEL : drt peut avoir comme valeurs : top, fst, bot. Dans notre cas, il ne peut avoir que bot,top
        $drt = ( $drt && is_string($drt) && in_array(strtolower($drt), ["bot","top"]) ) ? strtolower($drt) : $this->_DRT_DEFVAL;
        
        if ( $FILTER === "F_WTF" ) {
            
            //On récupère les identifiants des Conversations ainsi que les dates de création et de lecture du dernier message
            if (! $WITH_DEL_OPT ) {
                $QO = ( $drt === "bot" ) ? new QUERY("qryl4chbxcvn22") : new QUERY("qryl4chbxcvn23");
                $params = array( 
                    'pvt_cvid1'     => $ctab["convid"], 
                    'pvt_cvid2'     => $ctab["convid"], 
                    ':actor1'       => $utab["pdaccid"], 
                    ':actor2'       => $utab["pdaccid"], 
                    ':limit'        => $this->_FCONVRS_LIMIT );
            } else {
                $QO = ( $drt === "bot" ) ? new QUERY("qryl4chbxcvn24") : new QUERY("qryl4chbxcvn25");
                $params = array( 
                    ':pvt_cvid1'    => $ctab["convid"], 
                    ':pvt_cvid2'    => $ctab["convid"], 
                    ':actor1'       => $utab["pdaccid"], 
                    ':actor2'       => $utab["pdaccid"], 
                    ':pvt_uid1'     => $utab["pdaccid"], 
                    ':pvt_uid2'     => $utab["pdaccid"], 
                    ':limit'        => $this->_FCONVRS_LIMIT );
            }
            $convrs = $QO->execute($params);
            
            if ( $convrs ) {
                foreach ($convrs as $cvrow) {
                    $tgtuid = ( intval($cvrow["chmsg_actor"]) === intval($utab["pdaccid"]) ) ? $cvrow["chmsg_target"] : $cvrow["chmsg_actor"];
                    $tgttab = $PA->exists_with_id($tgtuid, TRUE);
                    if (! $tgttab ) {
                        continue; 
                    }
                    $tgttab = array_merge($tgttab,$PA->onread_acquiere_pp_datas($tgtuid, $tgttab["pdacc_gdr"]));
                    $cvrow["target_tab"] = $tgttab;
                    
                    $FNL_CONV[] = $cvrow;
                }
            } else { return; }
            
        } 
        
        return $FNL_CONV;
        
    }
    
    public function onread_UserSts ($ueid) {
        /*
         * Permet de vérifier le statut de l'utilisateur.
         * Ainsi, on peut déterminer si 
         *      (S0) L'utilisateur est déconnecté
         *      (S1) L'utilisateur n'est pas déconnecté mais son statut est ambigu.
         *      (S2) L'utilisateur est connecté
         * 
         * Un utilisateur est dit connecté s'il respecte les conditions suivantes :
         *      (1) L'utilisateur a une SESSION active (la plus récente), c'est à dire qu'il ne s'est pas déconnecté
         *      (2) L'utilisateur est en statut "En Ligne". La dernière ligne n'a pas de date de déconnexion
         *      (3) Que le log d'activité de l'utilisateur affiche une activité sur les 10 dernières minutes 
         * 
         *      Il faut que ces trois conditions soient réunies pour que l'utilisateur soit dit "Connectée". Sans elles, la donnée serait biaisée.
         *      En effet, si on était dans le meilleur des mondes, toutes les SESSIONs seraient cloturées "proprement" mais ce n'est pas le cas.
         *      La 3ème condition est presque la plus importante. Elle permet de gagner en précision et d'augmenter la probabilité que l'utilisateur soit réellement "En Ligne".
         *      Sinon, on pourrait afficher "En Ligne" et créer de réelles tensions sociales entre utilisateurs à cause de données biaisées.
         * 
         * Si l'utilisateur a une session active, un statut "En Ligne" mais qu'e son'aucune activité n'a été enregistrée dans un intervalle de temps tollérable ... 
         * ... on considère qu'il n'est pas déconnecté mais que son statut est ambigu. Cela pourrait se traduire par "absent"
         * 
         * Si au moins une des condtions pour que l'utilisateur soit considéré comme connecté est fausse, l'utilsiateur est considéré comme déconnecté
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $ueid);
        
        $PA = new PROD_ACC();
        //On vérifie si l'utilisateur existe et par la même occcasion on récupère sa table
        $utab = $PA->exists($ueid, TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_GONE";
        } 
        
        //(1) On vérifie si la dernière SESSION enregistrée est active
        $QO = new QUERY("qryl4tqrcnxn16");
        $params = array( ':uid' => $utab["pdaccid"] );
        $ls_tab = $QO->execute($params);
        if ( !$ls_tab | !empty($ls_tab[0]["llog_enddate_tstamp"]) ) {
            return "S0";
        }
        
        //(2) L'utilisateur a t-il le statut en ligne au niveau de sa configuration ?
        $ulsts = $this->UserChatBoxStatus($utab["pdaccid"]);
        if ( !$ulsts | !empty($ulsts["cb_state_edate_tstamp"]) ) {
            return "S0";
        } 
                
        //(3) Son activité récente laisse t-elle envisagée qu'il est connecté ?
        //lah : LastActivityHistory
        $lah = $PA->UserActyLog_Within($utab["pdaccid"],$this->_MIN_ACTY_TIME);
        if (! $lah ) {
            return "S1";
        } else {
            return "S2";
        }
            
    }
    
    public function UserChatBoxStatus($uid) {
        /*
         * Vérifie le statut de l'utilisateur au niveau de ChatBox. 
         * Ce statut est mis à jour manuellement par l'utilisateur.
         * Il n'y a que deux état : "En Ligne" "Hors Ligne".
         * 
         * On requiert "uid" car "ueid" obligerait à vérifier que l'utilisateur existe en créer un utab.
         * Or, la probabilité que cette méthode soit appelée après qu'on ait déjà effectué une opération 
         * de vérification est très grande. Autant ne pas ralentir le processus.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $QO = new QUERY("qryl4chbxn2");
        $params = array( ":uid" => $uid, ":limit" => 1 );
        $datas = $QO->execute($params);
        
        if ( !$datas ) {
            return FALSE;
        } else {
            return $datas[0];
        }
    }
    
    public function onread_UnreadConvrs($ueid, $LIMIT = -1) {
        /*
         * Renvoie la liste des conversations contenant au moins un message non lu.
         * CALLER peut spécifier la limite de résultats qu'il désire. 
         * S'il désire tous les résultats, il passe -1
         */
    }
    
    /**********************************************************************************************************************************************************/
    /*********************************************************************** ONDELETE *************************************************************************/
    
    public function ondelete_delForMe ($ceid,$ueid) {
        /*
         * Permet d'engager une procédure de demande de suppression de Conversation pour un des Acteurs tant que les conditions sont réunies.
         * Pour que la demande soit acceptée, il faut que toutes les conditions suivantes soient réunies :
         *  (1) La Conversation existe 
         *  (2) L'utilisateur existe et ne fait pas l'objet d'une demande de suppression de Compte
         *  (3) L'utilisateur est le propriétaire de la Conversation ou il en est le destinataire
         *  (4) La Conversation n'est pas déjà en attente de suppression définitve
         *  
         * Dans le cas où, à la fin de l'opération, la Conversation respecte les conditions pour une suppression définitive.
         * La Conversation est supprimée. Cette règle peut ne pas s'appliquer s'il y a un mécanisme de mise en attente sur les Conversations avant suppression.
         * 
         * RAPPEL : 
         *  -> On passe les identifiants externes dans l'idée de forcer à les convertir en identifiant interne et ainsi récupérer la table ou simplement vérifier l'existence de la  Conversation
         *  -> Si on se retrouve en face d'un cas où la Conversation a atteint ou dépasser la date de suppression définitive, on ne fait rien pour ne pas perturber le processus de suppression automatique.
         * 
         * ATTENTION : La méthode ne prend pas (encore) en compte la demande faite par la systeme
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //(1) La Conversation existe ?
        $ctab = $this->exists($ceid);
        if (! $ctab ) {
            return "__ERR_VOL_CNV_GONE";
        }
        
        //(2) L'utilisateur existe et ne fait pas l'objet d'une demande de suppression de Compte ?
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        //(3) L'utilisateur est le propriétaire de la  Conversation ou il en est le destinataire
        if (! in_array(intval($utab["pdaccid"]), [intval($ctab["conv_target"]),intval($ctab["conv_actor"])]) ) {
            return "__ERR_VOL_DNY_AKX";
        }
        $itrg = ( intval($utab["pdaccid"]) === intval($ctab["conv_target"]) ) ? TRUE : FALSE;
       
        //(4) La Conversation ne fait pas déjà l'objet d'une demande de suppression (Actor, Target, System) ou est en attente de suppression définitve ?
        if ( !empty($ctab["conv_nxtdldate_tstamp"]) || !empty($ctab["conv_sd_date_tstamp"]) ) { 
            return "__ERR_VOL_CNV_UVLB"; 
        }
        
        //On lance le processus de demande de suppression
        $now = round(microtime(TRUE)*1000);
        
        $QO = ( $itrg ) ? new QUERY("qryl4chbxcvn10") : new QUERY("qryl4chbxcvn9");
        $params = array ( 
            ':id' => $ctab["convid"], 
            ':date' => date("Y-m-d G:i:s",($now/1000)), 
            ':tstamp' => $now, 
            ':rsn' => 1 
        );
        $QO->execute($params);
        
        //On lance le processus qui va charcher tous les Messages à "supprimer" de la Conversation qui ont une date anterieure à celle de la demande pour le compte de CU
        $QO = new QUERY("qryl4chbxcvn13");
        $params = array( 
            ":uid1" => $utab["pdaccid"], 
            ":uid2" => $utab["pdaccid"], 
            ":cvid" => $ctab["convid"], 
            ":time" => $now 
        );
        $datas = $QO->execute($params);        
        
//        var_dump($datas);
//        exit();
        //On procède à une demande de suppression pour les Messages désignés
        if ( $datas ) {
            $CBMSG = new CHBX_MSG();
            foreach ($datas as $m) {
                $r__ = $CBMSG->ondelete_delForMe($m["chmsg_eid"], $ueid);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r__) && in_array($r__, ["__ERR_VOL_U_G"]) ) 
                {
                    return $r__;
                }
            }
        }
        
        //On vérifie si le cas cas de la suppression définitve est atteint et respecte les conditions, Sinon ...
        /*
         * Quand A a déja fait une demande de suppression et que B en demande une aussi, on peut se retrouver dans deux cas qui entrainent des répercussions différentes.
         * L'action qui doit suivre dépend de la présence ou non d'au moins un Message de la Conversation qui n'a pas de double demande de suppression.
         *  (1) CAS DOUBLE POUR TOUS : On fait passer la Conversation à NXT
         *  (2) CAS OU IL EXISTE DES MESSAGES : On annule la demande de A pour éviter que la Conversation ne soit malencontreusement supprimée.
         */
        //RAPPEL : Si la demande est faite par TARGET alors ne compte que la date de ACTOR et vice versa
        if ( ( $itrg && !empty($ctab["conv_ad_date_tstamp"]) ) | ( !$itrg && !empty($ctab["conv_td_date_tstamp"]) ) ) {
            
            //on vérifie s'il existe un Message qui ne soit pas "doublement supprimé"
            $QO = new QUERY("qryl4chbxcvn18");
            $params = array (':convid' => $ctab["convid"]);
            $d__ = $QO->execute($params);
            
            if (! $d__ ) {
                $now = round(microtime(TRUE)*1000) + $this->_NXT_TDL_MS;
                $QO = new QUERY("qryl4chbxcvn12");
                $params = array ( 
                    ':id' => $ctab["convid"], 
                    ':date' => date("Y-m-d G:i:s",($now/1000)), 
                    ':tstamp' => $now
                );
                $QO->execute($params);
            } else {
                //On annule la demande effectuée par l'autre acteur pour éviter de se retrouver dans le cas d'une double demande
                //RAPPEL : On est protégé par le fait que les Messages ont une mention qui fera que les auteurs des demandes ne les verront plus
                //RAPPEL : La requete réinitialise aussi 'sd' car on ne serait jamais arrivé ici si 'sd' est NOT NULL
                $QO = ( $itrg ) ? new QUERY("qryl4chbxcvn20") : new QUERY("qryl4chbxcvn21");
                $params = array (':convid' => $ctab["convid"]);
                $QO->execute($params);
            }
            
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
                "title" => "Réinitialiser les champs relatifs à la suppression du Mesasge",
                "query" => "
                    UPDATE `chatbox_messages` 
                    SET `chmsg_ad_date_tstamp` = NULL,
                    `chmsg_ad_date` = NULL,
                    `chmsg_ad_date_tstamp` = NULL,
                    `chmsg_ad_rsncaz` = NULL,
                    `chmsg_td_date` = NULL,
                    `chmsg_td_date_tstamp` = NULL,
                    `chmsg_td_rsncaz` = NULL,
                    `chmsg_nxtdldate` = NULL,
                    `chmsg_nxtdldate_tstamp` = NULL
                    WHERE = convid = ?;"
            ],
        ];
    }
    
    
    /*************************************************************************************************************************************************************************/
    /*********************************************************************** GETTERS and SETTERS SCOPE ***********************************************************************/
    /*************************************************************************************************************************************************************************/
    
}

?>