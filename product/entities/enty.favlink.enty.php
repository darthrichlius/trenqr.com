<?php

class FAVLINLK extends PROD_ENTITY {
    
    private $favid;
    private $fav_eid;
    private $fav_title;
    private $fav_url;
    private $fav_desc;
    private $fav_catg;
    private $fav_lastvst;
    private $fav_nbvst;
    private $fav_isPrv;
    private $fav_ssid;
    private $fav_curl;
    private $fav_locip;
    private $fav_uagent;
    private $fav_adddate;
    private $fav_adddate_tstamp;
    
    private $fav_oid;
    private $fav_oeid;
    private $fav_ogid;
    private $fav_ofn;
    private $fav_opsd;
    private $fav_oppicid;
    private $fav_oppic;
    private $fav_ohref;
    
    /* --------- RULES ---------- */
    
    protected $_FAV_FST_LIMIT;

    protected $_FAV_TITLE_RGX;
    protected $_FAV_URL_RGX;
    protected $_FAV_URL_MIN;
    protected $_FAV_URL_MAX;
    protected $_FAV_DESC_RGX;
    protected $_FAV_CATG_LST;
    protected $_FAV_CATG_BDD_LST;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["favid","fav_eid","fav_title","fav_url","fav_desc","fav_catg","fav_lastvst","fav_nbvst","fav_isPrv","fav_ssid","fav_curl","fav_locip","fav_uagent","fav_adddate","fav_adddate_tstamp","fav_oid","fav_ogid","fav_oeid","fav_ofn","fav_opsd","fav_oppicid","fav_oppic","fav_ohref"];
        $this->needed_to_loading_prop_keys = ["favid","fav_eid","fav_title","fav_url","fav_desc","fav_catg","fav_lastvst","fav_nbvst","fav_isPrv","fav_ssid","fav_curl","fav_locip","fav_uagent","fav_adddate","fav_adddate_tstamp","fav_oid","fav_ogid","fav_oeid","fav_ofn","fav_opsd","fav_oppicid","fav_oppic","fav_ohref"];
        $this->needed_to_create_prop_keys = ["accid","acc_eid","fav_title","fav_url","fav_desc","fav_catg","fav_ssid","fav_curl","fav_locip","fav_uagent"];
        
        /***************************** RULES *****************************/
        
        $this->_FAV_FST_LIMIT = 8;
                    
        $this->_FAV_TITLE_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{10,100}$/i";
        
        $this->_FAV_URL_RGX = '_^(?:(?:https?)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';
        $this->_FAV_URL_MIN = 5;
        $this->_FAV_URL_MAX = 255;
        
        $this->_FAV_DESC_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,200}$/i";
        
        $this->_FAV_CATG_LST = ["CATG_NEWS","CATG_MMEDIA","CATG_PRO","CATG_GAMES","CATG_SHOP","CATG_SOCNET","CATG_OTHERS"];
        $this->_FAV_CATG_BDD_LST = [
            "_FVLK_CATG_NEWS"   =>  1,
            "_FVLK_CATG_MMEDIA" =>  2,
            "_FVLK_CATG_PRO"    =>  3,
            "_FVLK_CATG_GAMES"  =>  4,
            "_FVLK_CATG_SHOP"   =>  5,
            "_FVLK_CATG_SOCNET" =>  6,
            "_FVLK_CATG_OTHERS" =>  100
        ];
    }

    protected function build_volatile($args) { }

    public function exists($eid) { 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $eid, TRUE);
        
        $QO = new QUERY("qryl4tapp_fvlkn4");
        $params = array( ':eid' => $eid );
        $datas = $QO->execute($params);
                
        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
    }
    
    public function exists_with_id($id) { 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $id, TRUE);
        
        $QO = new QUERY("qryl4tapp_fvlkn3");
        $params = array( ':id' => $id );
        $datas = $QO->execute($params);
                
        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
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
        $fav_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("fav_eid", $args) && !empty($args["fav_eid"]) ) ) 
        {
            if ( empty($this->fav_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else { 
                $fav_eid = $this->fav_eid;
            }
        } else { $fav_eid = $args["fav_eid"]; }
        
        // On controle si l'occurrence existe et on récupèrre les données (notamment accid)
        $exists = $this->exists($fav_eid);
        if ( ( !$exists ) && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$exists ) && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $accid = $exists["fvlk_fav_ownr"];
        $favdom = $exists;
        
        $PA = new PROD_ACC();
        $exists = $PA->exists_with_id($accid,TRUE);
        
        if ( !$exists && $std_err_enbaled ) {
            $this->signalError ("err_sys_l4comaccn1", __FUNCTION__, __LINE__);
        } else if ( !$exists && !$std_err_enbaled ) {
            return "__ERR_VOL_USER_GONE";
        }
        $owner = $exists;
        
        $loads = [
            "favid"                 => $favdom["fvlk_fav_id"],
            "fav_eid"               => $favdom["fvlk_fav_eid"],
            "fav_catg"              => $favdom["fvlk_fav_catg"],
            "fav_title"             => $favdom["fvlk_fav_title"],
            "fav_url"               => $favdom["fvlk_fav_url"],
            "fav_desc"              => $favdom["fvlk_fav_desc"],
            "fav_isPrv"             => $favdom["fvlk_fav_isPrv"],
            "fav_ssid"              => $favdom["fvlk_fav_ssid"],
            "fav_curl"              => $favdom["fvlk_fav_curl"],
            "fav_locip"             => $favdom["fvlk_fav_locip"],
            "fav_uagent"            => $favdom["fvlk_fav_uagent"],
            "fav_adddate"           => $favdom["fvlk_fav_adddate"],
            "fav_adddate_tstamp"    => $favdom["fvlk_fav_adddate_tstamp"],
            //Données sur l'OWNER
            "fav_oid"               => $owner["pdaccid"],
            "fav_oeid"              => $owner["pdacc_eid"],
            "fav_ogid"              => $owner["pdacc_gid"],
            "fav_ofn"               => $owner["pdacc_ufn"],
            "fav_opsd"              => $owner["pdacc_upsd"],
//            "fav_oppic" => $owner["pdacc_uppic"],
            "fav_ohref"             => "/".$owner["pdacc_upsd"]
        ];
      
       /*
        * [NOTE 22-09-14] @author L.C.
        * Du fait de changements au niveau de la gestion de PROFILPIC, on est obligé de faire appel à PDACC pour récupérer l'image de profil.
        */
       //RAPPEL : Attention, à vb1, ppicid peut être 1 mais l'image peut ne pas êxister dans la base de données. (Meme très surement)
        $loads["fav_oppicid"] = $PA->onread_acquiere_pp_datas($loads["fav_oid"])["picid"];
        $loads["fav_oppic"] = $PA->onread_acquiere_pp_datas($loads["fav_oid"])["pic_rpath"];
        
        $favid = $loads['favid'];
        
        if ( !count($loads) ) 
        { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else 
        {
            //*
            $r;
            $extras = ["fav_lastvst","fav_nbvst"];
            foreach ( $extras as $v ) {
                $r = $this->load_entity_extras_datas($favid, $loads["fav_eid"], $v);
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
        
        //On vérifie la présence des données obligatoires : "accid","acc_eid","fav_title","fav_url","fav_desc","fav_catg","fav_ssid","fav_curl","fav_locip","fav_uagent"
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !( in_array($k,["fav_desc","fav_uagent"]) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
       
        //On vérifie si le compte existe toujours
        $PACC = new PROD_ACC();
        $exists_id = $PACC->exists_with_id($args["accid"],TRUE);
        
        /*
         * ETAPE : 
         * On s'assure de manière stricte que l'utilisateur existe
         * Pour cela on vérifie si son eid et son id existent et coeincident.
         */
        if (! ( $exists_id && $exists_id["pdacc_eid"] === $args["acc_eid"] ) ){
            return "__ERR_VOL_USER_GONE";
        }
        
        $errs = [];
        /*
         * ETAPE :
         *  Vérification de la validité du titre
         */
        $ntle = $this->on_create_treat_title($args["fav_title"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ntle) ) {
            $errs[]["title"] = $ntle;
        } else {
            $args["fav_title"] = $ntle;
        }
        
        /*
         * ETAPE :
         *  Vérification de la validité de l'url 
         */
        $nurl = $this->on_create_treat_url($args["fav_url"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nurl) ) {
            $errs[]["url"] = $nurl;
        } else {
            $args["fav_url"] = $nurl;
        }
        
        /*
         * ETAPE :
         *  Vérification de la validité de la description s'elle est fournie.
         */
        if ( $args["fav_desc"] ) {
            $ndsc = $this->on_create_treat_desc($args["fav_desc"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__,$ndsc) ) {
                $errs[]["desc"] = $ndsc;
            } else {
                $args["fav_desc"] = $ndsc;
            }
        } 
        
        /*
         * ETAPE :
         *  On vérifie la validité de la catégorie
         */
        if ( !is_string($args["fav_catg"]) | !in_array(strtoupper($args["fav_catg"]), $this->_FAV_CATG_LST) ) {
            $errs[]["catg"] = "__ERR_VOL_FAV_CATG_MSM";
        } else {
            $args["fav_catg"] = "_FVLK_".strtoupper($args["fav_catg"]);
        }
        
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
        $fav_infos = $this->write_new_in_database($args);
        $favid = $fav_infos["favid"];
        $fav_eid = $fav_infos["fav_eid"];
        
        
        //On load l'instance
        return $this->load_entity(["fav_eid" => $fav_eid]);
    }
    
    protected function on_alter_entity($args) { }
    
    public function on_delete_entity($fav_eid) { 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $fav_eid);
        
        $favid = $this->onread_get_id_from_eid($fav_eid);
        if (! $favid ) {
            return "__ERR_VOL_FAV_GONE";
        }
        
        /*
         * ETAPE :
         *  On supprime toutes les visites liées
         */
        $this->visit_ondelete_all($favid);
        
        /*
         * ETAPE :
         *  On supprime le lien favori
         * [NOTE]
         *  On ne s'occupe pas de la version archivée. Et si elle n'existe pas, une amélioration permettra d'archiver avant de supprimer.
         */
        $QO = new QUERY("qryl4tapp_fvlkn8");
        $params = array( ':favid' => $favid );
        $QO->execute($params);
        
        return TRUE;
        
    }

    public function on_read_entity($args) {
        $fav_eid;
        if (! ( !empty($args) && is_array($args) && count($args) === 1 && key_exists("fav_eid", $args) && !empty($args["fav_eid"]) ) ) 
        {
            if ( empty($this->fav_eid) ) {
                return;
            } else {
                $fav_eid = $this->fav_eid;
            }
        } else {
            $fav_eid = $args["fav_eid"];
        }
        
        //On vérifie que l'occurrence existe
        $exists = $this->exists($fav_eid); //AME : Fait perdre du temps pour rien !
        if ( $exists ) {
            $loads = $this->load_entity($args);
            return $loads;
        } else {
            return "__ERR_VOL_FAV_GONE";
        }
    }

    protected function write_new_in_database($args) { 
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4tapp_fvlkn1");
        $params = array(
            ":accid"            => $args["accid"], 
            ":fav_title"        => $args["fav_title"], 
            ":fav_url"          => $args["fav_url"], 
            ":fav_desc"         => $args["fav_desc"], 
            ":fav_catg"         => $this->_FAV_CATG_BDD_LST[$args["fav_catg"]], 
            ":fav_ssid"         => $args["fav_ssid"], 
            ":fav_curl"         => $args["fav_curl"], 
            ":fav_locip"        => $args["fav_locip"], 
            ":fav_uagent"       => $args["fav_uagent"], 
            ":fav_cdate"        => $date, 
            ":fav_cdate_tstamp" => $time
        );  
        $favid = $QO->execute($params);     
        
//        var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$artid);
//        exit();
        
        //Créer fav_eid
        $fav_eid = $this->entity_ieid_encode($time, $favid);
        
        //Insérer fav_eid
        $QO = new QUERY("qryl4tapp_fvlkn2");
        $params = array(":favid" => $favid, ":fav_eid" => $fav_eid);  
        $datas = $QO->execute($params);
//        var_dump("CHECKPOINT => ",__LINE__);
        
        /*
         * [ RAPPEL ] 
         *  Il faudra que CRON puisse archiver les FAVLINKS.
         */
        
        //Création FAV_INFOS
        $fav_infos = NULL;
        $fav_infos["favid"] = $favid;
        $fav_infos["fav_eid"] = $fav_eid;
        
        return $fav_infos;
    }
    
    /***************************************************************************************************************************************************************************************/
    /************************************************************************************* SPECS SCOPE *************************************************************************************/
    /***************************************************************************************************************************************************************************************/
    
    /*********** ON_LOAD SCOPE (START) *************/
    
    private function load_entity_extras_datas ($favid, $fav_eid, $k) {
        /*
         * Permet de load les autres données necessaires pour load l'Entity. 
         * La méthode peut servir lorsqu'on a un tableau de extras_keys et qu'on veut les charger les (extras) acquerir les uns après les autres. 
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
            
        switch($k) {
            case "fav_lastvst" :
                return $this->onload_fav_lastvst($favid);
            case "fav_nbvst" :
                return $this->onload_fav_nbvst($favid);
            default:
                return 0;
        }
    }
    
    public function onload_fav_lastvst ($favid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4tapp_fvlkvstn4");
        $qparams_in_values = array(":favid" => $favid);  
        $datas = $QO->execute($qparams_in_values);
        
        $lv = ( $datas ) ? $datas[0]["fvlk_fav_vst_date_tstamp"] : NULL;
        
        return $lv;
    }
    
    
    public function onload_fav_nbvst ($favid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4tapp_fvlkvstn6");
        $qparams_in_values = array(":favid" => $favid);  
        $datas = $QO->execute($qparams_in_values);
        
        $nbv = ( $datas ) ? $datas[0]["nbvisit"] : 0;
        
        return $nbv;
    }
    
    /*********** ON_LOAD SCOPE (END) *************/
    
    
    /*********** ON_READ SCOPE (START) *************/
    
    public function onread_get_eid_from_id ($favid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists_with_id($favid);
        return  (! $r ) ? FALSE : $r["fvlk_fav_eid"];
    }
    
    public function onread_get_id_from_eid ($fav_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists($fav_eid);
        return  (! $r ) ? FALSE : $r["fvlk_fav_id"];
    }
    
    public function onread_pull_favs_first ($accid, $filter = NULL, $_WITH_FWO = TRUE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$accid]);
        
        if (! $_WITH_FWO ) {
            /*
             * On vérifie si le compte est disponible
             */
            $PA = new PROD_ACC();
            $utab = $PA->exists_with_id($accid,TRUE);
            if (! $utab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        /*
         * ETAPE : 
         *  On gère et valide la catégorie si elle est spécififiée
         */
        if ( $filter && !in_array(strtoupper($filter), $this->_FAV_CATG_LST) ) {
            return "__ERR_VOL_FIL_MSM";
        } else if ( $filter ) {
            $catg = strtoupper($filter);
            $catg__ = "_FVLK_".$catg;
            $catg__  = $this->_FAV_CATG_BDD_LST[$catg__];
        }
        
        /*
         * On récupère les liens favoris
         */
        if (! $filter ) {
            $QO = new QUERY("qryl4tapp_fvlkn5");
            $params = array( ':accid' => $accid, ":limit" => $this->_FAV_FST_LIMIT );
            $datas = $QO->execute($params);
        } else {
            $QO = new QUERY("qryl4tapp_fvlkn5_wfil");
            $params = array( ":accid" => $accid, ":fav_catg" => $catg__, ":limit" => $this->_FAV_FST_LIMIT );
            $datas = $QO->execute($params);
        }
        
        $favs = [];
        if ( $datas ) {
            
            $FAV = new FAVLINLK();
            foreach ($datas as $fdm) {
                $t__ = $FAV->on_read_entity(["fav_eid"=>$fdm["fvlk_fav_eid"]]);
                if (! $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__) ) {
                    $favs[] = $t__;
                } else {
                    $favs = [];
                    break;
                }
            }
            
        }
        
        return $favs;
    }
    
    
    public function onread_pull_favs_from ($fav_eid, $fav_tm, $dir, $filter = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fav_eid, $fav_tm, $dir]);
        
        /*
         * Controle sur dir avant d'aller plus loin
         */
        if (! in_array($dir, ["btm","top"]) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        /*
         * ETAPE :
         *  On s'assure que FAV existe
         */
        $favid;
        $t__ = $this->exists($fav_eid);
        if (! $t__ ) {
            return "__ERR_VOL_FAV_GONE";
        } else if ( floatval($t__["fvlk_fav_adddate_tstamp"]) !== floatval($fav_tm) ) {
            return "__ERR_VOL_FAV_TRNCTD";
        }
        $favid = $t__["fvlk_fav_id"];
        $uid = $t__["fvlk_fav_ownr"];
        
        /*
         * ETAPE : 
         *  On gère et valide la catégorie si elle est spécififiée
         */
        if ( $filter && !in_array(strtoupper($filter), $this->_FAV_CATG_LST) ) {
            return "__ERR_VOL_FIL_MSM";
        } else if ( $filter ) {
            $catg = strtoupper($filter);
            $catg__ = "_FVLK_".$catg;
            $catg__  = $this->_FAV_CATG_BDD_LST[$catg__];
        }
        
        /*
         * ETAPE :
         *  On récupère les liens favoris
         */
        if (! $filter ) {
            $QO = ( $dir === "btm" ) ? new QUERY("qryl4tapp_fvlkn6") : new QUERY("qryl4tapp_fvlkn7");
            $params = array( 
                ":favid"    => $favid, 
                ":uid"      => $uid, 
                ":fav_tm"   => $fav_tm, 
                ":limit"    => $this->_FAV_FST_LIMIT 
            );
            $datas = $QO->execute($params);
        } else {
            $QO = ( $dir === "btm" ) ? new QUERY("qryl4tapp_fvlkn6_wfil") : new QUERY("qryl4tapp_fvlkn7_wfil");
            $params = array( 
                ":favid"    => $favid, 
                ":uid"      => $uid, 
                ":fav_catg" => $catg__, 
                ":fav_tm"   => $fav_tm, 
                ":limit"    => $this->_FAV_FST_LIMIT 
            );
            $datas = $QO->execute($params);
        }
            
        
        $favs = [];
        if ( $datas ) {
            
            $FAV = new FAVLINLK();
            foreach ($datas as $fdm) {
                $t__ = $FAV->on_read_entity(["fav_eid"=>$fdm["fvlk_fav_eid"]]);
                if (! $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__) ) {
                    $favs[] = $t__;
                } else {
                    $favs = [];
                    break;
                }
            }
            
        }
        
        return $favs;
    }
    
    public function onread_totLinksnb ($accid, $_WITH_FWO = TRUE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! $_WITH_FWO ) {
            /*
             * On vérifie si le compte est disponible
             */
            $PA = new PROD_ACC();
            $utab = $PA->exists_with_id($accid,TRUE);
            if (! $utab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $QO = new QUERY("qryl4tapp_fvlkn9");
        $qparams_in_values = array(":accid" => $accid);  
        $datas = $QO->execute($qparams_in_values);
        
        $tot = ( $datas ) ? $datas[0]["total"] : 0;
        
        return $tot;
    }
    
    /*********** ON_READ SCOPE (END) *************/
    
    /*********** ON_CREATE SCOPE (START) *************/
    
    private function on_create_treat_title ( $s ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        $TH = new TEXTHANDLER();
        
        /*
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
        if ( is_string($s) && !preg_match($this->_FAV_DESC_RGX,$s) ) {
            return "__ERR_VOL_FAV_TLE_MSM";
        }
        
        $ns = $TH->secure_text($s);
        
        return $ns;
    }
    
    private function on_create_treat_url ( $s ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        $TH = new TEXTHANDLER();
        $len = $TH->strlen_utf8($s);
        if ( $len < $this->_FAV_URL_MIN || $len > $this->_FAV_URL_MAX ) {
            return "__ERR_VOL_FAV_TLE_MSM_LEN";
        } 
        
       /*
        * On effectue des opérations de transformation de l'URL le cas échéant pour la valider.
        */
        $w__ = (! preg_match("#^https?://#", $s) ) ? "http://".$s : $s;
        if ( !preg_match($this->_FAV_URL_RGX,$w__) ) {
            return "__ERR_VOL_UWSBT_MSM_FRMT";
        }
        
        $new_desc = $TH->secure_text($w__);
        
        return $new_desc;
    }
    
    private function on_create_treat_desc ( $fav_desc ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$fav_desc]);
        
        $TH = new TEXTHANDLER();
        if ( !empty($fav_desc) && is_string($fav_desc) && !preg_match($this->_FAV_DESC_RGX,$fav_desc) ) {
            return "__ERR_VOL_FAV_DESC_MSM_LEN";
        } else if ( !empty($fav_desc) && is_string($fav_desc) ) {
            $new_desc = $TH->secure_text($fav_desc);
        } else {
            return "__ERR_VOL_FAV_DESC_FAILED";
        }
        
        return $new_desc;
    }
    
    /*********** ON_CREATE SCOPE (END) *************/
    
    /***************************************************************************************************************************************************************************************/
    /************************************************************************************* VISITES SCOPE ***********************************************************************************/
    /***************************************************************************************************************************************************************************************/
    
    
    public function visit_exists($fvs_eid) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $fvs_eid, TRUE);
        
        $QO = new QUERY("qryl4tapp_fvlkvstn2");
        $params = array( ':fvst_eid' => $fvs_eid );
        $datas = $QO->execute($params);
                
        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
    }
    
    public function visit_exists_with_id($fvsid) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $fvsid, TRUE);
        
        $QO = new QUERY("qryl4tapp_fvlkvstn1");
        $params = array( ':fvstid' => $fvsid );
        $datas = $QO->execute($params);
                
        $r = ( $datas ) ? $datas[0] : FALSE;
        
        return $r;
    }
    
    public function visit_onread($fvs_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $fvs_eid);
         
        $exists = $this->visit_exists($fvs_eid);
        if (! $exists ) {
            return "__ERR_VOL_FVS_GONE";
        }
        
        $loads = [
            "fvs_id"        => $exists["fvlk_fav_vst_id"],
            "fvs_eid"       => $exists["fvlk_fav_vst_eid"],
            "fvs_favid"     => $exists["fvlk_fav_vst_favid"],
            "fvs_ssid"      => $exists["fvlk_fav_vst_ssid"],
            "fvs_locip"     => $exists["fvlk_fav_vst_locip"],
            "fvs_uagent"    => $exists["fvlk_fav_vst_uagent"],
            "fvs_curl"      => $exists["fvlk_fav_vst_curl"],
            "fvs_date"      => $exists["fvlk_fav_vst_date"],
            "fvs_tstamp"    => $exists["fvlk_fav_vst_date_tstamp"],
            "fvs_who"       => $exists["fvlk_fav_vst_who"],
        ];
        
        return $loads;
        
    }
    
    public function visit_new($args, $_WITH_RD_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
             
        //On vérifie la présence des données obligatoires
        $_needed_to_create_prop_keys = ["fvs_fav_aeid","fvs_fav_eid","fvs_ssid","fvs_curl","fvs_locip","fvs_uagent"];
        $com  = array_intersect( array_keys($args), $_needed_to_create_prop_keys);
        if ( count($com) != count($_needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$_needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !( in_array($k,["fav_uagent"]) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $acc_eid = $args["fvs_fav_aeid"];
       
       /*
        * ETAPE :
        *   On vérifie si le compte est disponible
        */
        $PA = new PROD_ACC();
        $utab = $PA->exists($acc_eid,TRUE); 
        if (! $utab ) {
            return "__ERR_VOL_USER_GONE";
        }
        $accid = $utab["pdaccid"];
        $args["accid"] = $accid;
        
        /*
         * ETAPE :
         *   On vérifie que le lien favori existe
         */
        $fav_eid = $args["fvs_fav_eid"];
        $ftab = $this->exists($fav_eid);
        if (! $ftab ) {
            return "__ERR_VOL_FAV_GONE";
        }
        $favid = $ftab["fvlk_fav_id"];
        $args["favid"] = $favid;
        
        /*
         * ETAPE :
         *   On ajoute l'occurrence de la visite du lien
         */
        $infos = $this->visit_write_new($args);
        
        if ( $_WITH_RD_OPT === TRUE ) {
            $fvs_tab = $this->visit_onread($infos["fvs_eid"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $fvs_tab) ) {
                return "__ERR_VOL_ALMOST";
            }
            return $fvs_tab;
        }
        
    }
    
    
    private function visit_write_new ($args) {
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4tapp_fvlkvstn7");
        $params = array(
            ":favid"    => $args["favid"], 
            ":accid"    => $args["accid"], 
            ":ssid"     => $args["fvs_ssid"], 
            ":locip"    => $args["fvs_locip"], 
            ":uagent"   => $args["fvs_uagent"], 
            ":curl"     => $args["fvs_curl"], 
            ":date"     => $date, 
            ":tstamp"   => $time
        );  
        $fvsid = $QO->execute($params);     
        
        //Créer fvs_eid
        $fvs_eid = $this->entity_ieid_encode($time, $fvsid);
        
        //Insérer fav_eid
        $QO = new QUERY("qryl4tapp_fvlkvstn8");
        $params = array(":fvsid" => $fvsid, ":fvs_eid" => $fvs_eid);  
        $datas = $QO->execute($params);
        
        //Création FAV_INFOS
        $fvs_infos = NULL;
        $fvs_infos["fvsid"] = $fvsid;
        $fvs_infos["fvs_eid"] = $fvs_eid;
        
        return $fvs_infos;
        
    }
    
    private function visit_ondelete($fvsid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $fvsid);
         
        $exists = $this->visit_exists_with_id($fvsid);
        if (! $exists ) {
            return "__ERR_VOL_FVS_GONE";
        }
        
        $QO = new QUERY("qryl4tapp_fvlkvstn9");
        $params = array( ':fvsid' => $fvsid );
        $QO->execute($params);
                
        return TRUE;
        
    }
    
    private function visit_ondelete_all($favid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $favid);
         
        $exists = $this->exists_with_id($favid);
        if (! $exists ) {
            return "__ERR_VOL_FAV_GONE";
        }
        
        $QO = new QUERY("qryl4tapp_fvlkvstn10");
        $params = array( ':favid' => $favid );
        $QO->execute($params);
                
        return TRUE;
    }
    
    
    /***************************************************************************************************************************************************************************************/
    /********************************************************************************** GETTERS & SETTERS **********************************************************************************/
    /***************************************************************************************************************************************************************************************/
    
    public function getfavid() {
        return $this->favid;
    }

    public function getFav_eid() {
        return $this->fav_eid;
    }

    public function getFav_ownr() {
        return $this->fav_ownr;
    }

    public function getFav_catg() {
        return $this->fav_catg;
    }

    public function getFav_title() {
        return $this->fav_title;
    }

    public function getFav_url() {
        return $this->fav_url;
    }

    public function getFav_desc() {
        return $this->fav_desc;
    }

    public function getFav_isPrv() {
        return $this->fav_isPrv;
    }

    public function getFav_ssid() {
        return $this->fav_ssid;
    }

    public function getFav_curl() {
        return $this->fav_curl;
    }

    public function getFav_locip() {
        return $this->fav_locip;
    }

    public function getFav_adddate() {
        return $this->fav_adddate;
    }

    public function getFav_adddate_tstamp() {
        return $this->fav_adddate_tstamp;
    }

    public function setfavid($favid) {
        $this->favid = $favid;
    }

    public function setFav_eid($fav_eid) {
        $this->fav_eid = $fav_eid;
    }

    public function setFav_ownr($fav_ownr) {
        $this->fav_ownr = $fav_ownr;
    }

    public function setFav_catg($fav_catg) {
        $this->fav_catg = $fav_catg;
    }

    public function setFav_title($fav_title) {
        $this->fav_title = $fav_title;
    }

    public function setFav_url($fav_url) {
        $this->fav_url = $fav_url;
    }

    public function setFav_desc($fav_desc) {
        $this->fav_desc = $fav_desc;
    }
    
    public function getFav_lastvst() {
        return $this->fav_lastvst;
    }

    public function getFav_nbvst() {
        return $this->fav_nbvst;
    }

    public function setFav_lastvst($fav_lastvst) {
        $this->fav_lastvst = $fav_lastvst;
    }

    public function setFav_nbvst($fav_nbvst) {
        $this->fav_nbvst = $fav_nbvst;
    }

    public function setFav_isPrv($fav_isPrv) {
        $this->fav_isPrv = $fav_isPrv;
    }

    public function setFav_ssid($fav_ssid) {
        $this->fav_ssid = $fav_ssid;
    }

    public function setFav_curl($fav_curl) {
        $this->fav_curl = $fav_curl;
    }

    public function setFav_locip($fav_locip) {
        $this->fav_locip = $fav_locip;
    }
    
    public function getFav_uagent() {
        return $this->fav_uagent;
    }

    public function setFav_uagent($fav_uagent) {
        $this->fav_uagent = $fav_uagent;
    }

    public function setFav_adddate($fav_adddate) {
        $this->fav_adddate = $fav_adddate;
    }

    public function setFav_adddate_tstamp($fav_adddate_tstamp) {
        $this->fav_adddate_tstamp = $fav_adddate_tstamp;
    }

    public function getFav_oid() {
        return $this->fav_oid;
    }

    public function getFav_oeid() {
        return $this->fav_oeid;
    }

    public function getFav_ogid() {
        return $this->fav_ogid;
    }

    public function getFav_ofn() {
        return $this->fav_ofn;
    }

    public function getFav_opsd() {
        return $this->fav_opsd;
    }

    public function getFav_oppicid() {
        return $this->fav_oppicid;
    }

    public function getFav_oppic() {
        return $this->fav_oppic;
    }

    public function getFav_ohref() {
        return $this->fav_ohref;
    }

    public function setFav_oid($fav_oid) {
        $this->fav_oid = $fav_oid;
    }

    public function setFav_oeid($fav_oeid) {
        $this->fav_oeid = $fav_oeid;
    }

    public function setFav_ogid($fav_ogid) {
        $this->fav_ogid = $fav_ogid;
    }

    public function setFav_ofn($fav_ofn) {
        $this->fav_ofn = $fav_ofn;
    }

    public function setFav_opsd($fav_opsd) {
        $this->fav_opsd = $fav_opsd;
    }

    public function setFav_oppicid($fav_oppicid) {
        $this->fav_oppicid = $fav_oppicid;
    }

    public function setFav_oppic($fav_oppic) {
        $this->fav_oppic = $fav_oppic;
    }

    public function setFav_ohref($fav_ohref) {
        $this->fav_ohref = $fav_ohref;
    }

}