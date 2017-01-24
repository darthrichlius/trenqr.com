<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class TREND extends PROD_ENTITY {
    private $trid;
    private $trd_eid;
    private $trd_title;
    //Version url du titre. Elle rentre dans la composition de l'url de le TENDANCE
    private $trd_href;
    private $trd_title_href;
    private $trd_desc;
    private $trd_is_public;
    private $trd_grat;
    private $trd_catgid;
    private $catg_decocode;
    private $trd_loc_numip;
    private $trd_country;
    private $trd_cover;
    
    private $trd_oid;
    private $trd_oeid;
    private $trd_ofn;
    private $trd_opsd;
    private $trd_oppic;
    private $trd_ohref;
    private $acc_todelete;
    
    private $trd_creadate;
    private $trd_creadate_tstamp;
    private $trd_datemod;
    private $trd_datemod_tstamp;
    private $trd_next_del;
    private $trd_next_del_tstamp;
    
    /*----------- STATES ------------*/
    private $tsh_state;
    private $tsh_state_time;
    
    /*----------- STATS ------------*/
    private $trd_stats_posts;
    private $trd_stats_subs;
    private $trd_stats_vips;
    
    /*----------- ARTICLES ------------*/
    //Tableau contenant quelques Articles de la Tendance
    private $trd_first_articles;
            
    /*----------- RULES ------------*/
    private $_DFT_TRD_URQID;
    private $_RGX_TITLE;
    private $_MIN_TITLE;
    private $_MAX_TITLE;
    private $_MAX_DESC;
    private $_AKX_CHOICES;
    private $_AKX_CHOICES_EXT;
    private $_GRAT_CHOICES;
    private $_MAX_GRAT;
    /*
     * Le nombre max d'Articles qu'on récupère lorsqu'on lit une Tendance
     */
    private $_TR_FIRST_ART_NB;
    /**
     * Le nombre d'Articles affichés en mode sample au niveau d'une page FOKUS.
     * @var int 
     */
    private $_LIMIT_FKSASMPL_ARTS;
    /**
     * @var int Le nombre d'Articles que l'on peut charger à chaque fois que l'on fait une demande pour charger de nouveaux ANCIENS Articles.
     * Cela ne concerne pas les Articles dit "PREDATE". En effet, il s'agit de récupérer TOUS LES ARTICLES.
     * Dans ce dernier cas, il s'agit de mettre à jour l'interface du client.
     * 
     * On fait donc la différence entre FEEDMORE qui regroupe "PREDATE" et "OLD" et "LOAD_MORE" qui ne concerne que "OLD"
     */
    private $_TR_LOADMORE_ART_NB;
    /**
     * Il s'agit de codes qui permettent de spécifier à la méthode qui charge les Articles de quelle manière il faudrait les filtrer.
     * 
     * @var string 
     */
    private $_TR_FEEDMORE_FLAGS; 
    /*
     * Le nombre d'Articles maximum afficheables pour une Tendance lorsque l'utilisateur n'est pas connecté.
     * Cela a deux avantages :
     *  (1) Eviter trop de fuite de nos données car le réseau est ouvert
     *  (2) Inciter à s'inscrire
     * Cependant, aucun message ne signale qu'on a atteint la limite. C'est une censure silencieuse.
     * Cela pour éviter de donner de la matière aux détracteurs.
     */
    private $_TR_WLC_MAX_ART_NB;
    /*
     * Le nombre d'heures maximum avant que la modification de la catégorie ne soit plus autorisée.
     * LA valeur est indiquée en heures.
     */
    private $_MAX_TIME_CHANGE_CATG;
    /*
     * Le nombre par défaut de Tendances à charger au niveau de la page TMLNR_TRENDS
     */
    private $_TR_LOADMORE_PGMYTRS_NB;
    /*
     * Les Etats :
     *  1 -> ToInvestigate : La Tendance reste accessible mais elle fera l'objet d'un check. (Ex : Signalement)
     *  2 -> AccountRemoval : La Tendance n'est pas accessible car le Compte est en attente de suppression  
     *  3 -> ToRemove : La Tendance est accessible pendant une période. C'est généralement le temps que les contributeurs récupèrent leurs images
     *  4 -> Buffering : La Tendance n'est pas accessible. Elle est en "rétention" le temps que le sujet soit réglé. (Ex : Justice)
     *  5 -> Avalaible : La Tendance est accessible. Cet état découle d'un autre état, elle est donc de nouveau accessible. 
     */
    private $_TRD_STATE;
    private $_TRD_STATE_DLY;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["trid","trd_eid","trd_title","trd_title_href","trd_href","trd_desc","trd_is_public","trd_grat","tsh_state","tsh_state_time","trd_loc_numip","trd_catgid","catg_decocode","trd_country","trd_oid","trd_oeid","trd_ofn","trd_opsd","trd_oppic","trd_ohref","acc_todelete","trd_creadate","trd_creadate_tstamp","trd_datemod","trd_datemod_tstamp","trd_next_del","trd_next_del_tstamp","trd_first_articles","trd_cover","trd_stats_posts","trd_stats_subs","trd_stats_vips","_MAX_TITLE","_MAX_DESC","_AKX_CHOICES","_GRAT_CHOICES","_MAX_GRAT","_TR_FIRST_ART_NB","_TR_WLC_MAX_ART_NB","_MAX_TIME_CHANGE_CATG"];
        $this->needed_to_loading_prop_keys = ["trid","trd_eid","trd_title","trd_title_href","trd_desc","trd_is_public","trd_grat","tsh_state","tsh_state_time","trd_loc_numip","trd_catgid","catg_decocode","trd_country","trd_oid","trd_oeid","trd_ofn","trd_opsd","trd_oppic","trd_ohref","acc_todelete","trd_creadate","trd_creadate_tstamp","trd_datemod","trd_datemod_tstamp","trd_next_del","trd_next_del_tstamp","trd_first_articles","trd_cover","trd_stats_posts","trd_stats_subs","trd_stats_vips"];
        $this->needed_to_create_prop_keys = ["trd_title","trd_desc","catg_decocode","trd_is_public","trd_grat","trd_loc_numip","trd_oid"];
        
        //****** RULES ******
        $this->_RGX_TITLE = "#(?:(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*).(?![\s]{5,})){20,}#iu";
//        $this->_RGX_TITLE = "/(?=.*[a-z])(?:.*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]){20,}/i"; //[DEPUIS 11-07-15] @BOR
//        $this->_RGX_TITLE = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{20,100}$/i";
        $this->_MIN_TITLE = 20;
        $this->_MAX_TITLE = 100;
        $this->_MAX_DESC = 200;
        $this->_AKX_CHOICES = ["pri","pub"];
        $this->_AKX_CHOICES_EXT = ["_NTR_PART_PRI","_NTR_PART_PUB"];
        $this->_GRAT_CHOICES = [0,1,2,5,10];
        $this->_MAX_GRAT = 10;
        $this->_TR_FIRST_ART_NB = 6;
        $this->_LIMIT_FKSASMPL_ARTS = 10;
        $this->_TR_WLC_MAX_ART_NB = 50;
        $this->_MAX_TIME_CHANGE_CATG = 24;
        $this->_TR_FEEDMORE_FLAGS = ["TRART_FILTER_GET_PREDATE", "TRART_FILTER_GET_NEW"];
        $this->_TR_LOADMORE_ART_NB = 16;
        $this->_TR_LOADMORE_PGMYTRS_NB = 10;
        
        $this->_DFT_TRD_URQID = "explore";
        $this->_TRD_STATE = [1,2,3,4,5,6];
//        $this->_TRD_STATE_DLY = 10*60*1000;  // DEV, DEBUG, TEST : 10 minutes  
        $this->_TRD_STATE_DLY = 14*24*60*60*1000; //14 jours 
    }

    public function build_volatile($args) { }

    public function exists($arg, $_OPTIONS = NULL) {
        $trd_eid = NULL;
        
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["trd_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->trd_eid) ) {
                return;
            } else {
                $trd_eid = $this->trd_eid;
            }
        } else { 
            $trd_eid = $arg; 
        }
                
        //Contacter la base de données et vérifier si la Tendance existe.
        $QO = new QUERY("qryl4trdn1neo0615001");
//        $QO = new QUERY("qryl4trdn1");
        $params = array( ':trd_eid' => $trd_eid );
        $datas = $QO->execute($params);
        
        if ( $datas && !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("NO_STATE_CHECK", $_OPTIONS) ) {
            return $datas[0];
        } else if ( $datas && key_exists("tsh_id", $datas[0]) ) {
//            var_dump(__FUNCTION__,__LINE__,$datas[0]["tsh_state"],in_array(intval($datas[0]["tsh_state"]), [1,4,6]));
            $r = ( !empty($datas[0]["tsh_id"]) && empty($datas[0]["tsh_evedate_tstamp"]) && !empty($datas[0]["tsh_state"]) && !in_array(intval($datas[0]["tsh_state"]), [1,4,6]) ) ?  FALSE : $datas[0]; 
        } else {
            $r = FALSE;
        }
        
//        $r = ( $datas ) ?  $datas[0] : FALSE;
        
        /*
         * [DEPUIS 01-08-15] @BOR
         *  Si la date de suppression est atteinte, on considère la Tendance comme "introuvable"
         */
        if ( intval($r["tsh_state"]) === 4 && !empty($r["tsh_evsdate_tstamp"]) && floatval($r["tsh_evsdate_tstamp"]) > 0 ) {
            
            $ddln = floatval($r["tsh_evsdate_tstamp"])+$this->_TRD_STATE_DLY;
            $now = round(microtime(TRUE)*1000);
//            $t__ = 50*1000;   
            $t__ = $ddln - $now;
//            var_dump(__LINE__,$now,$ddln,$t__,empty($trend["tsh_evsdate_tstamp"]));
//            exit(); 
            
//            if ( TRUE ) { 
//            if ( $t__ >= 1 ) { 
            if ( $t__ <= 0 ) { 
                /*
                 * [DEPUIS 05-08-15] @BOR
                 * On change l'état de la Tendace pour la faire passer à un état de suppression effective
                 */
                $this->onalter_change_state($r["trd_eid"],3);
                
                //On signale que la Tendance n'existe pas
                return FALSE;
            } 
        }
        
        return $r;
    }

    /**
     * Vérifie l'existence d'une Tendance à partir de son identifiant interne.
     * 
     * @author BlackOwlRobot
     * @since vb1.1503.1.1
     * @date 03/31/2015
     * @param integer $trid L'identifiant de la Tendance
     * @return mixed Si la Tendance n'existe pas, on renvoie FALSE. Sinon, on renvoie la table.
     */
    public function exists_with_id($trid, $_OPTIONS = NULL) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $trid, TRUE);
                
        //Contacter la base de données et vérifier si la Tendance existe.
        $QO = new QUERY("qryl4trdn26neo0615001");
//        $QO = new QUERY("qryl4trdn26");
        $params = array( ':trid' => $trid );
        $datas = $QO->execute($params);
        
        if ( $datas && !empty($_OPTIONS) && is_array($_OPTIONS) && in_array("NO_STATE_CHECK", $_OPTIONS) ) {
            return $datas[0];
        } else if ( $datas && key_exists("tsh_id", $datas[0]) ) {
//            var_dump(__FUNCTION__,__LINE__,$datas[0]["tsh_state"],in_array(intval($datas[0]["tsh_state"]), [1,4,6]));
            $r = ( !empty($datas[0]["tsh_id"]) && empty($datas[0]["tsh_evedate_tstamp"]) && !empty($datas[0]["tsh_state"]) && !in_array(intval($datas[0]["tsh_state"]), [1,4,6]) ) ?  FALSE : $datas[0]; 
        } else {
            $r = FALSE;
        }
        
//        return  ( $datas ) ?  $datas[0] : FALSE;
        
        /*
         * [DEPUIS 01-08-15] @BOR
         *  Si la date de suppression est atteinte, on considère la Tendance comme "introuvable"
         */
        if ( intval($r["tsh_state"]) === 4 && !empty($r["tsh_evsdate_tstamp"]) && floatval($r["tsh_evsdate_tstamp"]) > 0 ) {
            
            $ddln = floatval($r["tsh_evsdate_tstamp"])+$this->_TRD_STATE_DLY;
            $now = round(microtime(TRUE)*1000);
//            $t__ = 50*1000;   
            $t__ = $ddln - $now;
//            var_dump(__LINE__,$now,$ddln,$t__,empty($trend["tsh_evsdate_tstamp"]));
//            exit(); 
            
//            if ( TRUE ) { 
//            if ( $t__ >= 1 ) { 
            if ( $t__ <= 0 ) { 
                /*
                 * [DEPUIS 05-08-15] @BOR
                 * On change l'état de la Tendace pour la faire passer à un état de suppression effective
                 */
                $this->onalter_change_state($r["trd_eid"],3);
                
                //On signale que la Tendance n'existe pas
                return FALSE;
            } 
        }
        
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

    protected function load_entity($args, $std_err_enabled = FALSE) {
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$trend_infos],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
         /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args, TRUE);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $trd_eid;
        if (! ( !empty($args) && is_array($args) && key_exists("trd_eid", $args) && !empty($args["trd_eid"]) ) ) 
        {
            if ( empty($this->trd_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else { 
                $trd_eid = $this->trd_eid;
            }
        } else {
            $trd_eid = $args["trd_eid"];
        }
        
        // On controle si l'occurence existe et on récupèrre les données (notamment trd_oid)
        $exists = $this->exists($trd_eid);
        if ( !$exists && $std_err_enabled ) 
        {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$trd_eid]);
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__,TRUE);
        }
        else if ( !$exists && !$std_err_enabled ) 
        {
            return "__ERR_VOL_TREND_GONE";
        }
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$exists],'v_d');
        $trd_oid = $exists["trd_owner"];
        $trend = $exists;
        
        //*/
        
        //Intéroger la base de données pour récupérer les données sur OWNER
        //*
        
        $PA = new PROD_ACC();
//        $owner_infos = $PA->on_read_entity(["accid"=>$trd_oid]);
        //*
        $QO = new QUERY("qryl4pdaccn2");
        $params = array(":accid" => $trd_oid);
        $datas = $QO->execute($params);
        //*/
        if ( !$datas | intval($datas[0]["pdacc_todelete"]) !== 0 ) {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4comn11", __FUNCTION__, __LINE__);
            } else {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        
        /*
        $QO = new QUERY("qryl4accn3");
        $params = array( ':accid' => $trd_oid );
        $datas = $QO->execute($params);
     
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$exists,$trend,$datas],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        if ( ( !$datas || count($datas) > 1 ) && $std_err_enabled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( ( !$datas || count($datas) > 1 ) && !$std_err_enabled ) 
        {
            return 0;
        }
        //*/
        $owner_infos = $datas[0];
        
        //[NOTE 09-10-14] @author L.C. Si le propriétaire n'est plus, ce n'est pas logique que la Tendance soit encore disponible.
//        if ( intval($owner_infos["todelete"]) !== 0 ) {
//            return "__ERR_VOL_OWNER_GONE";
//        }
        
        $loads = [
            //Données sur TREND
            'trid'                  => $trend["trid"],
            'trd_eid'               => $trend["trd_eid"],
//            'trd_title' => htmlentities($trend["trd_title"]),
            'trd_title'             => $trend["trd_title"],
            'trd_desc'              => htmlentities($trend["trd_desc"]),
            'trd_title_href'        => $trend["trd_title_href"],
            'tsh_state'             => $trend["tsh_state"],
            'tsh_state_time'        => "",
            //[NOTE 13-09-14] @author L.C.
            "trd_href"              => $this->on_read_build_trdhref($trend["trd_eid"], $trend["trd_title_href"]),
            'trd_catgid'            => $trend["trd_catgid"],
            'catg_decocode'         => $trend["catg_decocode"],
            'trd_is_public'         => $trend["trd_is_public"],
            'trd_grat'              => $trend["trd_grat"],
            'trd_loc_numip'         => $trend["trd_loc_numip"],
            'trd_country'           => $trend["trd_country"],
            'trd_creadate'          => $trend['trd_datecrea'],
            'trd_creadate_tstamp'   => $trend['trd_date_tstamp'],
            'trd_datemod'           => $trend['trd_datemod'],
            'trd_datemod_tstamp'    => $trend['trd_datemod_tstamp'],
            'trd_next_del'          => $trend['trd_next_del'],
            'trd_next_del_tstamp'   => $trend['trd_next_del_tstamp'],
            //Données sur l'OWNER
            'acc_todelete'          => $owner_infos["pdacc_todelete"],
            'trd_oid'               => $owner_infos["pdaccid"],
            'trd_oeid'              => $owner_infos["pdacc_eid"],
            'trd_ofn'               => $owner_infos["pdacc_ufn"],
            'trd_opsd'              => $owner_infos["pdacc_upsd"],
            'trd_ohref'             => '/'.$owner_infos["pdacc_upsd"]
        ];
        
        /*
         * [DEPUIS 10-06-15] @author @BOR
         * On calcule dans combien de temps la Tendance devra être supprimée.
         * Si le delais est atteint ou supprimé, on supprime la Tendance et on signale qu'elle n'existe plus.
         */
//        var_dump(__LINE__,$trend["trd_is_public"]);
//        var_dump(__LINE__,$trend["tsh_evsdate_tstamp"]);
//        exit();
        if ( intval($trend["tsh_state"]) === 4 && !empty($trend["tsh_evsdate_tstamp"]) && floatval($trend["tsh_evsdate_tstamp"]) > 0 ) {
            
            $ddln = floatval($trend["tsh_evsdate_tstamp"])+$this->_TRD_STATE_DLY;
            $now = round(microtime(TRUE)*1000);
//            $t__ = 50*1000;   
            $t__ = $ddln - $now;
//            var_dump(__LINE__,$now,$ddln,$t__,empty($trend["tsh_evsdate_tstamp"]));
//            exit(); 
            
//            if ( TRUE ) { 
//            if ( $t__ >= 1 ) { 
            if ( $t__ <= 0 ) { 
                //On supprime définitivement la Tendance
//                $this->on_delete_entity($trend["trd_eid"]);
                
                /*
                 * [DEPUIS 05-08-15] @BOR
                 * On change l'état de la Tendance pour la faire passer à un état de suppression effective
                 */
                $this->onalter_change_state($trend["trd_eid"],3);
                
                if ( $std_err_enabled ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$trd_eid]);
                    $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__,TRUE);
                } else  {
                    return "__ERR_VOL_TREND_GONE";
                }
                
            } else {
                $dy__ = floor($t__/(24*60*60*1000));
                $hy__ = floor($t__/(60*60*1000));
                $my__ = floor($t__/(60*1000));
        
                $tmy__ = "";
                if ( intval($dy__) !== 0 ) {
                    $tmy__ = "$dy__,d";
                } else if ( intval($dy__) === 0 && intval($hy__) !== 0 ) {
                    $tmy__ = "$hy__,h";
                } else if ( intval($dy__) === 0 && intval($my__) !== 0 ) {
                    $tmy__ = "$my__,m";
                } else if ( intval($dy__) === 0 && intval($my__) === 0 ) {
                    $tmy__ = "0,m";
                } 
                
                $loads["tsh_state_time"] = $tmy__; 
            }
        }
            
        /*
         * [NOTE 22-09-14] @author L.C.
         * Du fait de changements au niveau de la gestion de PROFILPIC, on est obligé de faire appel à PDACC pour récupérer l'image de profil.
        */
        $loads["trd_oppic"] = $PA->onread_acquiere_pp_datas($loads["trd_oid"])["pic_rpath"];
        
        $trid = $loads['trid'];
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $loads,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        if ( !count($loads) ) { 
            if ( $std_err_enabled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else {
            $extras = ["trd_first_articles","trd_cover","trd_stats_posts","trd_stats_subs", "trd_stats_vips"];
            $r;
            foreach ( $extras as $v ) {
                $r = $this->load_entity_extras_datas($trid, $v);

                /* 
                 * Si pour x raisons, le contenu n'est pas disponible, plutot que de d'éclencher une erreur on déclare les valeurs à NULL. 
                 * Cela permet à l'utilisateur d'avoir au moins une partie de ses données.
                 * 
                 * Si $r === 0, alors on l'identifiant est faux. Dans ce cas et dans le cas n'est pas disponible on affiche le code une erreur.
                 * On ne le fait que si et seulement si on est en mode non DEBUG.
                 */
                if ( !isset($r) || $r === 0 ) {
                    $loads[$v] = $r;
                    
//                    debug_print_backtrace();
                    
                    //On signale l'erreur si on est en mode DEBUG
                    $er = $this->get_or_signal_error (1, "err_sys_l4comn7", __FUNCTION__, __LINE__, TRUE);
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$v,$er], 'v_d');
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $er, 'v_d');
                } else {
                    $loads[$v] = $r;
                }
                    
            }
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $loads,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
        
    }

    protected function on_alter_entity($args) { }

    public function on_create_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : ["trd_title","trd_desc","catg_decocode","trd_is_public","trd_grat","trd_loc_numip","trd_oid"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    
                    if ( ( $k == "trd_is_public" || $k == "trd_grat" ) && ( $v == 0 || $v == "0") )
                        continue;
                    else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    }
                } 
            }
        }
//        ["trd_title","trd_desc","catg_decocode","trd_is_public","trd_grat","trd_loc_numip","trd_oeid"]
        
        //Vérifier que le compte du propriétaire existe toujours et on récupère les infos au passage
        
        $PA = new PROD_ACC();
//        $user_infos = $PA->on_read_entity(["accid" => $args["trd_oid"]]);
        /*
        $QO = new QUERY("qryl4accn3");
        $params = array(":accid" => $args["trd_oid"]);
        $datas = $QO->execute($params);
        //*/
        $QO = new QUERY("qryl4pdaccn2");
        $params = array(":accid" => $args["trd_oid"]);
        $datas = $QO->execute($params);
        //*/
        if ( !$datas | intval($datas[0]["pdacc_todelete"]) !== 0 ) {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4comn11", __FUNCTION__, __LINE__);
            } else return "__ERR_VOL_USER_GONE";
        }
        $user_infos = $datas[0];
        /*
        if ( !$user_infos | $this->return_is_error_volatile(__FUNCTION__, __LINE__, $user_infos) ) {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4comn11", __FUNCTION__, __LINE__);
            } else return "__ERR_VOL_USER_GONE";
        }
        */
        
        //On vérifie que le titre n'est pas trop long et qu'il ne s'agit pas d'une tentative de "bourrer" la base de données
        $ori_title = $args["trd_title"];
        $trd_title = $this->valid_trd_title($args["trd_title"]);
        if ( !$trd_title && !$std_err_enabled ){
            return "__ERR_VOL_TRTITLE_NOT_COMPLY";
        } else if ( !$trd_title && $std_err_enabled ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args["trd_title"],'v_d');
            $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        }
        $args["trd_title"] = $trd_title;
        
        //On crée la version HREF du titre. Cette version est utilisée pour composer l'url de la Tendance
        /*
         * On utilise le titre brut car il n'y a aucune chance que l'utilisateur face des injections.
         * Les caractères sont remplacés pour satisfaire le pattern de l'url.
         */
        $TH = new TEXTHANDLER();
        $args["trd_title_href"] = $TH->text_urlize_from_pattern($ori_title);
        
        /*
        $tr_infos = $this->trend_get_trend_infos_by_title($trd_title);
        if ( $tr_infos && !$std_err_enabled )
            return "__ERR_VOL_TR_EXISTS_BY_TITLE";
        else if ( $tr_infos && $std_err_enabled )
            $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        //*/
        
        /* On valide $trd_desc */ 
        $trd_desc = $args["trd_desc"] = $this->valid_trd_desc($args["trd_desc"]);
        if ( !$trd_desc && !$std_err_enabled )
            return "__ERR_VOL_TRDESC_NOT_COMPLY";
        else if ( !$trd_desc && $std_err_enabled ) {
           $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args["trd_desc"],'v_d');
           $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        }
        
        //On met en forme is_pub
        if ( is_string($args["trd_is_public"]) && preg_match("/^_NTR_PART_(?:PUB|PRI)$/i", $args["trd_is_public"]) ) {
            $f__ = strtolower(str_replace("_NTR_PART_", "", $args["trd_is_public"]));
            $args["trd_is_public"] = $f__;
        }
        $args["trd_is_public"] = ( ( isset($args["trd_is_public"]) && filter_var($args["trd_is_public"], FILTER_VALIDATE_BOOLEAN) ) || ( isset($args["trd_is_public"]) && $args["trd_is_public"] === "pub" )  ) ? "pub" : "pri";
        
        //On vérifie PARTICIPATION
        $trd_part = $args["trd_is_public"] = $this->valid_trd_participation($args["trd_is_public"]);
        if ( !$trd_part && !$std_err_enabled ){
            return "__ERR_VOL_TRPART_NOT_COMPLY";
        }
        else if ( !$trd_part && $std_err_enabled ){
           $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args["trd_is_public"],'v_d');
           $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        }
        
        //On vérifie GRATIFICATION
        $trd_grat = $args["trd_grat"];
        
        if ( !$this->valid_trd_gratification($args["trd_grat"]) && !$std_err_enabled )
            return "__ERR_VOL_TRGRAT_NOT_COMPLY";
        else if ( !$this->valid_trd_gratification($args["trd_grat"]) && $std_err_enabled ) {
           $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args["trd_grat"],'v_d');
           $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        }
        
        //On vérifie que la catégorie donnée est attendue
        //On met en forme la catégorie avant de la traiter
        if ( is_string($args["catg_decocode"]) && preg_match("/^_NTR_CATG_[a-z]+$/i", $args["catg_decocode"]) ) {
            $f__ = strtolower(str_replace("_NTR_CATG_", "", $args["catg_decocode"]));
            $args["catg_decocode"] = $f__;
        } else {
//            var_dump(__LINE__,$args["catg_decocode"]);
            return "__ERR_VOL_TRCATG_NOT_COMPLY";
        }
        
        $trd_catgid = $args["trd_catgid"] = $this->valid_trd_category_by_code($args["catg_decocode"]);
        if ( !$trd_catgid && !$std_err_enabled ) {
            return "__ERR_VOL_TRCATG_NOT_COMPLY";
        } else if ( !$trd_catgid && $std_err_enabled ) {
           $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args["catg_decocode"],'v_d');
           $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        }
        
        $args = array_merge($args,$user_infos);
        
        $trend_infos = $this->write_new_in_database($args);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$args, $trend_infos],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        
        return $this->load_entity($trend_infos, $std_err_enabled);
    }

    public function on_delete_entity($trd_eid) {
        /*
         * Permet de supprimer une Tendance ainsi que toutes ses composantes : Articles, 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie si la Tendance existe
        $trd_tab = $this->exists($trd_eid);
        
        if (! $trd_tab ) {
            return "__ERR_VOL_TR_GONE";
        }
        
        //On récupère tous les Articles de la Tendance
        $QO = new QUERY("qryl4trdn17");
        $params = array(":trid" => $trd_tab["trid"]);
        $datas = $QO->execute($params);
        
        //Si un ou plusieurs Articles existent, on commence par les supprimer
        if ( $datas ) {
            /*
            * On procède à la suppression de tous les Articles en prennant soin de supprimer chaque Article individuellement.
            * Il ne faut surtout pas supprimer un Article depuis la base de données. 
            * En effet, la suppression doit aussi comprendre les données extras. Le seul moyen "propre" de le faire est de faire appel à la méthode de suppression d'Article
            */
           foreach ($datas as $k => $a_t) {
               $ART = new ARTICLE();
               $ART->on_delete_entity($a_t["aei"]);
               
               /*
                * TODO : 
                * (1) Vérifier le retour pour voir si l'opération s'est bien déroulée
                * (2) Sinon, on vérifie si l'Article existe toujours
                * (3) Si oui, déclencher un ticket pour que l'équipe inspecte le cas
                */
           }
        }
        
        //On supprime tous les abonnements à la Tendance
        $QO = new QUERY("qryl4tbon9");
        $params = array(":trid" => $trd_tab["trid"]);
        $QO->execute($params);
        
        
        /*
         * On supprime toutes les anciennes occurrences (si elles existent) car on ne sauvegarde pas les anciennes images à la version vb1.10.14
         * TODO : Changer la requete, en effet, il faudra soit supprimer UNE occurrence (et non toutes) ...
         * ... Soit mettre à jour l'occurrence.
         * [NOTE 01-05-15] @BOR
         * EUH ... Pour faire simple, je vais supprimer tout ce qui se trouve dans les TR_COVER et autres.
         * Cependant, la manière dont tout cela est fait devra être changé. Pour l'heure je veux résoudre le bogue qui faisait que je ne pouvais pas effacer certaines Tendances.
         * La raison était qu'on ne supprimait pas l'image de couverture. Aussi, toutes les Tendances ayant une image de couverture ne pouvaient être supprimées.
         */
        $QO = new QUERY("qryl4trcovn2");
        $params = array(":trid" => $trd_tab["trid"]);
        $QO->execute($params);
        
        //On supprime les historiques d'etat de la Tendance
        $this->ondelete_statehisty($trd_tab["trid"]);
        
        //On supprime effectivement la Tendance
        $QO = new QUERY("qryl4trdn18");
        $params = array(":trid" => $trd_tab["trid"]);
        $QO->execute($params);
        
        //On supprime l'occurence dans SRH_TRENDS
        $QO = new QUERY("qryl4trdn21");
        $params = array(":trid" => $trd_tab["trid"]);
        $QO->execute($params);
        
        /*
         * A la version vbeta1, la suppression ne peut se faire que depuis BRAIN.
         * Aussi, il faut bien que le CALLER récupèrre ses données.
         * Pour ce faire, il n'aura qu'à 'read' le compte du propriétaire de la Tendance.
         * Cela aura pour effet, de récupérer les données mis à jour.
         */
        
        return TRUE;
    }

    /**
     * Permet de récupérer les données d'une Tendance. Accepte trid ou trd_eid.
     * 
     * @param type $args
     * @return type
     */
    
    public function on_read_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /* //La valeur est facultative
        //On a besoin de la valeur 'urqid' pour pouvoir créer trd_href
        if (! ( key_exists("urqid", $args) && !empty($args["urqid"]) && !is_array($args["urqid"]) ) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
        //*/
        
        $trd_eid = $trid = NULL;
        if ( !( !empty($args) && is_array($args) && key_exists("trd_eid", $args) && !empty($args["trd_eid"]) ) )
        {
            if ( key_exists("trid", $args) && !empty($args["trid"]) && !is_array($args["trid"]) ) {
                $trid = $args["trid"];
            } else if ( !empty($this->trid) )  {
                $trid = $this->trid;
            } else {
                if ( empty($this->trd_eid) ){
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args,'v_d');
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
                } else {
                    $trd_eid = $this->trd_eid;
                } 
                    
            }
            
        } else {
            $trd_eid = $args["trd_eid"];
        }
        
        /*
         * [NOTE 07-10-14] @author L.C.
         * Refactorisé pour prendre en compte les cas où la Tendance n'est pas connue ...
         * ... et qu'on le signale via le système de retour d'erreur
         */
        
        if ( !isset($trd_eid) || empty($trd_eid) ) {
            //t_ = temp
            $t_treid = $this->get_trdeid_from_trid($trid);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t_treid) ) {
                return $t_treid;
            }
            
            $args["trd_eid"] = $t_treid;
        }
        
        $loads = $this->load_entity($args, $std_err_enabled);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $loads) ) {
                return $loads;
        }
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args["urqid"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        /*
         * On append trd_href.
         * [NOTE 13-09-14] L.C.
         * Refactorisé
         */
        if ( key_exists("urqid",$args) && isset($args["urqid"]) ) { 
            $this->trd_href = $this->all_properties["trd_href"] = $loads["trd_href"] = $this->on_read_build_trdhref($loads["trd_eid"], $loads["trd_title_href"], $args["urqid"]);
        } else {
            $this->trd_href = $this->all_properties["trd_href"] = $loads["trd_href"] = $this->on_read_build_trdhref($loads["trd_eid"], $loads["trd_title_href"]);
        }
        
        return $loads;
    }

    protected function write_new_in_database($args, $new_row = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $akx = ( $args["trd_is_public"] === "pub" || $args["trd_is_public"] !== "pri" ) ? 1 : 0;
        $time = round(microtime(true)*1000);
        
        //On enregistre la Tendance
        $QO = new QUERY("qryl4trdn3");
        $params = array(":trd_owner" => $args["trd_oid"],":trd_desc" => $args["trd_desc"],":trd_title" => $args["trd_title"],":trd_title_href" => $args["trd_title_href"],":trd_is_public" => $akx,":trd_grat" => $args["trd_grat"],":trd_loc_numip" => $args["trd_loc_numip"],":trd_catgid" => $args["trd_catgid"],":trd_date_tstamp" => $time);
        $trid = $QO->execute($params);
        
        if (! $trid )
            $this->signalError("err_sys_l4trdn1", __FUNCTION__, __LINE__,TRUE);
        
        //On crée eid 
        $trd_eid = $this->entity_ieid_encode($time, $trid);
                
        //On met à jour eid
        
        $QO = new QUERY("qryl4trdn4");
        $params = array(":trid" => $trid, ":trd_eid" => $trd_eid);
        $QO->execute($params);
        
        //On ajoute le titre dans la table des titres
        $QO = new QUERY("qryl4trdn5");
        $params = array(":trtle_lib" => $args["trd_title"], ":trtle_loc_numip" => $args["trd_loc_numip"], ":trtle_trid" => $trid, ":trtle_date_tstamp" => $time);
        $QO->execute($params);
        
        //On append id et eid
        $args["trid"] = $trid;       
        $args["trd_eid"] = $trd_eid; 
        
        return $args;
    }
    
    /**************************************************************************************************/
    /***************************************** SPECIALS ***********************************************/
    
    /*------ ON_CREATE SCOPE ------ */
    
    public function valid_trd_desc ($trd_desc) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $trd_desc = (string)$trd_desc;
        if (! ( preg_match($this->_RGX_TITLE, $trd_desc) && strlen(utf8_decode($trd_desc)) <= $this->_MAX_DESC ) ) {
//        if (! ( preg_match($this->_RGX_TITLE, $trd_desc) && strlen($trd_desc) <= $this->_MAX_DESC ) ) { //[DEPUIS 12-07-15] @BOR
        /*
         * [DEPUIS 15-06-15] @BOR
         *  -> L'ancienne regex ne permettait pas d'être sur qu'il y avait au un 20 caractères alphanumériques
         *  -> Les solutions (vérifier le nombre min de alphanum + long totale) que j'ai pu mettre en oeuvre, étaient un cauchemar en termes de performance
         *  -> J'ai mis en place un compromis
         */    
//        if ( !is_string($trd_desc) && strlen($trd_desc) > intval($this->_MAX_DESC) ) {
            return FALSE;
        }
            
        $TH = new TEXTHANDLER();
        $trd_desc = $TH->secure_text($trd_desc);
        
        return $trd_desc;
    }
    
    public function valid_trd_title ($trd_title) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $trd_title = (string)$trd_title;
        
//        var_dump("LINE ==> ",__LINE__,preg_match($this->_RGX_TITLE, $trd_title), strlen($trd_title) <= $this->_MAX_TITLE,"TITRE => ",$trd_title);
//        exit();
        if (! ( preg_match($this->_RGX_TITLE, $trd_title) && strlen(utf8_decode($trd_title)) <= $this->_MAX_TITLE ) ) {
//        if (! ( preg_match($this->_RGX_TITLE, $trd_title) && strlen($trd_title) <= $this->_MAX_TITLE ) ) { //[DEPUIS 12-07-15] @BOR
        /*
         * [DEPUIS 15-06-15] @BOR
         *  -> L'ancienne regex ne permettait pas d'être sur qu'il y avait au un 20 caractères alphanumériques
         *  -> Les solutions (vérifier le nombre min de alphanum + long totale) que j'ai pu mettre en oeuvre, étaient un cauchemar en termes de performance
         *  -> J'ai mis en place un compromis
         */
//        if (! preg_match($this->_RGX_TITLE, $trd_title) ) { 
             return FALSE;
        }
        /* (04-12-14) RETIRÉ
        if ( strlen($trd_title) > intval($this->_MAX_TITLE) )
        //*/
        $TH = new TEXTHANDLER();
        $trd_title = $TH->secure_text($trd_title);
        
        return $trd_title;
    }
    
    public function valid_trd_participation ($trd_part) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( !in_array($trd_part, $this->_AKX_CHOICES) ) {
            return FALSE;
        }
        
        return $trd_part;
    }
    
    public function valid_trd_gratification ($trd_grat) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        /*
         * ATTENTION : floatval('rr') donnera 0
         */
        
        //On utilise pas intval car ça laisse passer des valeurs et les arrondies au dixième. Cela est une faille !
        $trd_grat = floatval($trd_grat);
        
        if ( !in_array($trd_grat, $this->_GRAT_CHOICES) )
            return FALSE;
        
        return TRUE;
    }
    
    public function valid_trd_category_by_id ($trd_catgid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4ctgn1");
        $params = array(":catgid" => $trd_catgid);
        $datas = $QO->execute($params);
        
        $catg_decocode = NULL;
        if ( $datas )
            $catg_decocode = $datas[0]["catg_decocode"];
        else 
            return FALSE;
        
        return $catg_decocode;
    }
    
    public function valid_trd_category_by_code ($catg_decocode) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( !is_string($catg_decocode) ){
            return;
        }
        
        $QO = new QUERY("qryl4ctgn2");
        $params = array(":catg_decocode" => strtolower($catg_decocode));
        $datas = $QO->execute($params);
        
        $trd_catgid = ( $datas ) ? $datas[0]["catgid"] : FALSE;
        return $trd_catgid;
    }
    
    public function pull_categories () {
        /*
         * Permet de récupérer les catégories disponible pour les Tendances à l'instant t.
         * On ne récupère que les catégories dites "pleines"
         */
        
        /*
         * [DEPUIS 01-05-15] @BOR
         * -> Je ne me souviens pas vraiment on ne recéupère que les Catégories pleines. 
         *   Je retire cette obligation en attendant de voir si ça bogue entre temps
         * -> Les Catégories doivent se trouver dans la shortlist. D'où l'utilisation du nouveau "qryl4ctgn4"
         */
        $QO = new QUERY("qryl4ctgn4"); 
//        $QO = new QUERY("qryl4ctgn3");
        $datas = $QO->execute(null);
        
        $list = [];
        $TXH = new TEXTHANDLER();
        foreach ($datas as $cg_tab) {
            $t__ = "_NTR_CATG_".strtoupper($cg_tab["catg_decocode"]);
            $f__ = $TXH->get_deco_text("fr",$t__);
            if (! $this->return_is_error_volatile(__FUNCTION__, __LINE__, $f__) ) {
                $list[$t__] = $f__;
            }
                
        }
        ksort($list);
        
        return $list;
    }
    
    public function oncreate_archive_trend ($args) {
        /*
         * Permet de créer une occurrence d'une Tendance dans la table SRH.
         * La table SRH_Trends permet d'effectuer des recherches de Tendances depuis SRh_Box ou tout autre moteur de recherche.
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args(),TRUE);
        
        //Liste des données attendues, référencées par key
        $XPTD = [
            "srh_tr_id",
            "srh_tr_eid",
            "srh_tr_tle",
            "srh_tr_desc",
            "srh_tr_tlehrf",
            "srh_tr_fol",
            "srh_tr_post",
            "srh_tr_owid",
            "srh_tr_oweid",
            "srh_tr_owpsd",
            "srh_tr_owfn"
            ];
        
        /* On vérifie que les données sont attendues et qu'elles sont non-vides */
        $com = array_intersect($XPTD, array_keys($args));
        
        if ( count($com) !== count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD], 'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args], 'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            $X = ["art_hashs"];
            foreach ($args as $k => $v) {
                if ( !(isset($v) && $v !== "") && !in_array($k, $X) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $QO = new QUERY("qryl4trdn19");
        $params = array(
            ":id"       => $args["srh_tr_id"],
            ":eid"      => $args["srh_tr_eid"],
            ":title"    => $args["srh_tr_tle"],
            ":desc"     => $args["srh_tr_desc"],
            ":tlehrf"   => $args["srh_tr_tlehrf"],
            ":folr"     => $args["srh_tr_fol"],
            ":post"     => $args["srh_tr_post"],
            ":oid"      => $args["srh_tr_owid"],
            ":oeid"     => $args["srh_tr_oweid"],
            ":opsd"     => $args["srh_tr_owpsd"],
            ":ofn"      => $args["srh_tr_owfn"]
        );  
        $QO->execute($params);
        
        return TRUE;
        
    }
    
    /***********************************************************************************************************************************/
    /********************************************************** ON_READ SCOPE **********************************************************/
    
    private function load_entity_extras_datas ($trid, $code) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //["trd_first_articles","trd_cover","trd_stats_posts","trd_stats_subs", "trd_stats_vips"]
        switch($code) {
            case "trd_first_articles" :
                    return $this->onload_trend_get_first_articles($trid);
                break;
            case "trd_cover" :
                    return $this->onload_trend_get_trend_cover ($trid);
                break;
            case "trd_stats_posts" :
                    return $this->onload_trend_get_trend_stats_posts($trid);
                break;
            case "trd_stats_subs" :
                    return $this->onload_trend_get_trend_stats_subs($trid);
                break;
            case "trd_stats_vips" :
                    return $this->onload_trend_get_trend_vips($trid);
                break;
            default:
                    return 0;
                break;
        }
        
    }
    
    public function trend_get_trend_infos ($trd_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $trd_eid);
        
        $trend_infos = $this->exists($trd_eid);
                
        return $trend_infos;
    }
    
    public function trend_get_trend_infos_by_title ($trd_title, $skip_to_delete_acc = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $trend_infos = NULL;
        //Vérifier que le compte du propriétaire existe toujours
        $QO = new QUERY("qryl4trdn2");
        $params = array(":trd_title" => $trd_title);
        $datas = $QO->execute($params);
        
        if ( $datas )
            $trend_infos = $datas[0];
        else 
            return FALSE;
        
        $QO = new QUERY("qryl4tqraccn1");
//        $QO = new QUERY("qryl4accn1");
        $params = array(":uid" => $trend_infos["trd_owner"]);
        $owner_infos = $QO->execute($params)[0];
        //NOTE : Pas besoin de vérifier si le compte lié existe. On aurait pas pu le supprimer sans supprimer ses Tendances.
        
        if ( $skip_to_delete_acc && $owner_infos["todelete"] )
            return "__ERR_VOL_OWNER_TODELETE";
        
        $trend_infos = array_merge($trend_infos, $owner_infos);
                
        return $trend_infos;
    }
    //["tr_first_articles","tr_cover","tr_stats", "tr_vips"];
    public function onload_trend_get_first_articles ($trid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $trid);
        
        /*
         * [DEPUIS 30-05-15] @BOR
         * On choisit la bonne limite
         * 
         * [NOTE 30-05-15] @BOR 
         *  On prend en compte le nombre que le'on veut au cas où un des type n'admet aucun Articles.
         *  Dans ce cas, on sera sur qu'on au aura toujours le bon nombre dans le cas d'un défaut.
         *  C'est à CALLER de ne retenir que le bon nombre d'Articles.
         *  De plus on ajoute +1 au cas où on voudrait retirer un Article en particulier.
         */
        $limit = ( !empty($_OPTIONS) && in_array("FKSA_SAMPLE",$_OPTIONS) ) ? $this->_LIMIT_FKSASMPL_ARTS : $this->_TR_FIRST_ART_NB;
        
        //On récupère la liste des ids des articles dans la limite donnée et selon le filtre donné
        $QO = new QUERY("qryl4trdn8_dprvr"); 
//        $QO = new QUERY("qryl4trdn8");
        $params = array(":trid" => $trid, ":limit" => $limit);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return NULL;
        } else {
            /*
             * [NOTE 19-08-14 00:21] @author L.C.
             * On récupère les art_eid car pour read ART_TR il demande les art_eid.
             * S'il faut changer les règles et récupérer artid il faudra utiliser qryl4trdn16
             * 
             * [NOTE 07-10-14 16:12] @author L.C.
             * "qryl4trdn8" renvoie maintenant aussi : artid, art_eid, art_accid, art_date_tstamp.
             * A cette heure, je n'ai aucune idée des conséquences du fait de renvoyer d'autres données.
             * Si des erreurs apparaissent elles seront corrigées facilement.
             */
            /*
            $art_eids = NULL;
            foreach ( $datas as $k => $v) {
                $art_eids[$k] = $v["art_eid"];
            }
            //*/
            
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $art_eids,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//            exit();
            
            //return $art_eids; //OBSELETE [NOTE 07-10-14 16:12]
            /*
             * [DEPUIS 07-09-15] @author BOR
             */
            foreach ( $datas as $k => &$atab) {
                if ( isset($atab["artdl"]) && !in_array(intval($atab["artdl"]),[1,6]) ) {
                    unset($datas[$k]);
                } else {
                    unset($datas[$k]["artdl"]);
                }
            }
            
            return $datas;
        }
    }

    public function onload_trend_get_trend_cover($trid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On récupère pdpic_realpath et trcov_top
        $QO = new QUERY("qryl4trcovn1");
        $params = array(":trid" => $trid);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return NULL;
        } else if ( count($datas) > 1 ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas,'v_d');
            $this->signalError ("err_sys_l4trdn2", __FUNCTION__, __LINE__,TRUE);
        } else  {
            return $datas[0];
        }
    }

    public function onload_trend_get_trend_stats_subs ($trid) {
        //DEPEND DE : TRABO + ART_TREND qryl4trdn7
        
        // ** On récupère le nombre d'abonnements ** //
        return $this->trend_get_trabo_number($trid);
        
    }

    public function onload_trend_get_trend_stats_posts ($trid) {
        //DEPEND DE : TRABO + ART_TREND qryl4trdn7
        
        // ** On récupère le nombre d'ARTICLES ** //
        $QO = new QUERY("qryl4trdn7");
        $params = array(":trid" => $trid);
        $datas = $QO->execute($params);
        
        $trd_posts = ( $datas ) ? $datas[0]["tr_posts"] : 0;
        
        return $trd_posts;
        
    }
    
    public function onload_trend_get_trend_vips ( $trid, $cuid = NULL, $only_if_three = FALSE ) {
        //$only_if_three : Ne retournera les données que s'il y exactement 3. Sinon, il renvoie FALSE.
        //cuid permettra de voir s'il y a des UREL entre CU et ABO afin de privélégier ce choix.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list_vips = NULL;
        /*
         * On récupère la liste des 10 derniers abonnées.
         * On en récupère 10 :
         *  (1) au cas où entre temps certains ce seraieent désabonnés.
         *  (2) pour augmenter les chances de voir qu'une des relation de CU est abonné. (TODO)
         */
        
        $QO = new QUERY("qryl4tbon7_wtdlo");
//        $QO = new QUERY("qryl4tbon7"); //[DEPUIS 11-09-15] @author BOR
        $params = array(":trid" => $trid);
        $datas = $QO->execute($params);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas,'v_d');
        
        if (! $datas ) {
            return NULL;
        } else {
            if ( count($datas) < 3 && $only_if_three ) {
                return FALSE;
            } else {
                foreach ( $datas as $k => $x ) {
                    //TODO : Voir si CU a une relation avec certains
                    
                    //On récupère ces informations de compte
                    $QO = new QUERY("qryl4tqraccn1");
//                    $QO = new QUERY("qryl4accn1");
                    $params = array(":uid" => $x["trabo_uid"]);
                    $datas = $QO->execute($params);
                    
                    if (! $datas ) {
                        //Si on a déjà les 3 on sort
                        if ( isset($list_vips) && count($list_vips) == 3  ) return $list_vips;
                    } else {
                        
                        //Si on a déjà les 3 on sort
                        if ( isset($list_vips) && count($list_vips) == 3  ) { return $list_vips; }
                        
                        //On ne récupère que l'ueid et le pseudo
                        
                        $list_vips[] = [
                            "ueid"  => $datas[0]["acc_eid"],
                            "upsd"  => $datas[0]["acc_psd"],
                            "ufn"   => $datas[0]["pfl_fn"]
                        ];
                        
                        /*
                        $list_vips[] = [
                            "ueid" => $datas[0]["art_oeid"],
                            "upsd" => $datas[0]["art_opsd"],
                            "ufn" => $datas[0]["art_ofn"]
                        ];
                        //*/
                    }
                }
                //ENFIN : On renvoie $list_vips même s'il est NULL. S'il est NULL on renvoie FALSE s'il faut absolument 3. NULL veut dire qu'il n'existe aucun abonnement
                if ( isset($list_vips) && $list_vips < 3 && $only_if_three ) {
                    return FALSE;
                } else {
                    return $list_vips;
                }
            }
        }
    }
    
    public function on_read_build_trdhref ($trd_eid, $trd_title_href, $urqid = NULL) {
        $args = [$trd_title_href, $trd_eid];
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
//        $urqid = ( !$urqid ) ? $this->_DFT_TRD_URQID : $urqid;
        
        $trd_href =  ( !$urqid ) ? "/tendance/".$trd_eid."/".$trd_title_href : "/tendance/".$trd_eid."/".$trd_title_href."&as=".$urqid;
        return $trd_href;
    }
    
    public function on_read_build_trdhref_from_treid ($trd_eid, $urqid = NULL, $lg = "en") {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $trd_eid);
        
        /*
         * ETAPE :
         *      On récupère la table de la TENDANCE
         */
        $trtab = $this->exists($trd_eid);
        if (! $trtab ) {
            return FALSE;
        }
        
        /*
         * ETAPE :
         *      On récupère le TTTLE_HREF
         */
        $trd_title_href = $trtab["trd_title_href"];
        
        $compl = ( $lg === "en" ) ? "trend" : "tendance";
            
        $trd_href =  ( !$urqid ) ? "/$compl/".$trd_eid."/".$trd_title_href : "/$compl/".$trd_eid."/".$trd_title_href."&as=".$urqid;
        return $trd_href;
    }
    
    /*
     * Permet de récupérer des tables de Tendance à partir d'une Tendance dite de référence.
     * Cette méthode récupère celles APPARTENANT à l'utilisateur passé en paramètre.
     * L'utilisateur indique s'il veut les Tendances les plus récentes ou celles plus anciennes.
     * 
     * OPTIONS :
     *  -> WITH_FAI_OPT : Récupérer les identifiants des Articles FIRST 
     * 
     *  NOTE : 
     *  -> uid : Correspond à l'identifiant interne de l'utilisateur. C'est à CALLER de s'assurer de la véracité de la nature de l'identifiant.
     */
    public function onread_pull_mytrends_from ($uid, $i, $t, $_DIR, $_WITH_FAI_OPT = FALSE) {
        //FAI = FIRST_ARTICLE_OPTION (CALLER signale qu'il veut une liste des identifiants des Articles
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$i,$t,$_DIR]);
        
        if (! in_array(strtolower($_DIR),["bottom","top"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        //On récupère l'identifiant de la Tendance à partir de l'identifiant externe de la Tendance
        $trd__ = $this->exists($i);
        if (! $trd__ ) {
            return "__ERR_VOL_TRD_GONE";
        }
        $i = $trd__["trid"];
        
        //On récupère les Tendances appartenant à l'utilisateur passé en paramètre
        $QO = ( strtolower($_DIR) === "bottom" ) ? new QUERY("qryl4trdn22") : new QUERY("qryl4trdn23");
        $params = array(
            ":accid"    => $uid, 
            ":trid"     => $i, 
            ":time"     => $t, 
            ":limit"    => $this->_TR_LOADMORE_PGMYTRS_NB
        );
        $datas = $QO->execute($params);
        /*
        if ( $trd__["trd_eid"] === "203mboi" ) {
            var_dump(__FUNCTION__,__LINE__,count($datas),$datas);
            exit();
        }
        //*/
        $d__ = [];
        if ( $datas ) {
            $TRD = new TREND();
            foreach ($datas as &$trtab) {
                $t__ = $TRD->on_read_entity(["trd_eid"=>$trtab["trd_eid"]]);
                if ( $_WITH_FAI_OPT === TRUE && ($t__["trd_first_articles"] && count($t__["trd_first_articles"])) ) {
                    $ai__ = array_column($t__["trd_first_articles"],"art_eid");
                    $t__["trd_first_articles"] = $ai__;
                } else {
                    unset($t__["trd_first_articles"]);
                }

                $d__[] = $t__;
            }
        }
        
        return $d__;
    }
    
    /*
     * Permet de récupérer des tables de Tendance à partir d'une Tendance dite de référence.
     * Cette méthode récupère celles SUIVIES par l'utilisateur passé en paramètre.
     * L'utilisateur indique s'il veut les Tendances les plus récentes ou celles plus anciennes.
     * 
     * OPTIONS :
     *  -> WITH_FAI_OPT : Récupérer les identifiants des Articles FIRST 
     * 
     *  NOTE : 
     *  -> uid : Correspond à l'identifiant interne de l'utilisateur. C'est à CALLER de s'assurer de la véracité de la nature de l'identifiant.
     */
    public function onread_pull_substrends_from ($uid, $i, $t, $_DIR, $_WITH_FAI_OPT = FALSE) {
        //FAI = FIRST_ARTICLE_OPTION (CALLER signale qu'il veut une liste des identifiants des Articles
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$i,$t,$_DIR]);
        
        if (! in_array(strtolower($_DIR),["bottom","top"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        //On récupère l'identifiant de la Tendance à partir de l'identifiant externe de la Tendance
        $trd__ = $this->exists($i);
        if (! $trd__ ) {
            return "__ERR_VOL_TRD_GONE";
        }
        $i = $trd__["trid"];
        
        //On récupère les Tendances suivies appartenant à l'utilisateur passé en paramètre
        $QO = ( strtolower($_DIR) === "bottom" ) ? new QUERY("qryl4trdn24") : new QUERY("qryl4trdn25");
        $params = array(
            ":accid"    => $uid, 
            ":trid"     => $i, 
            ":time"     => $t,
            ":limit"    => $this->_TR_LOADMORE_PGMYTRS_NB
        );
        $datas = $QO->execute($params);
        
        $d__ = [];
        if ( $datas ) {
            $TRD = new TREND();
            foreach ($datas as &$trtab) {
                $t__ = $TRD->on_read_entity(["trd_eid"=>$trtab["trd_eid"]]);
                if ( $_WITH_FAI_OPT === TRUE && ($t__["trd_first_articles"] && count($t__["trd_first_articles"])) ) {
                    $ai__ = array_column($t__["trd_first_articles"],"art_eid");
                    $t__["trd_first_articles"] = $ai__;
                } else {
                    unset($t__["trd_first_articles"]);
                }

                $d__[] = $t__;
            }
        }
        
        return $d__;
    }
    
    /**
     * Récupère le nombre d'articles postés par l'utilisateur dont l'identifiant est passé en paramètre au sein de la Tendance dont l'identifiant est passé en paramètre.
     * 
     * @param integer $uid L'identifiant interne de l'utilisateur
     * @param integer $trid L'identifiant interne de la Tendance
     * @param boolean $_WITH_CHECK_POT Détermine si on doit vérifier les Entity ACCOUNT & TREND. Ce paramètre peut avoir des conséquences en ce qui concerne la performance. 
     * @return integer
     */
    public function onread_usercontrib ($uid, $trid, $_WITH_CHECK_POT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$trid]);
        
        if ( $_WITH_CHECK_POT ) {
            $PA = new PROD_ACC();
            if (! $PA->exists_with_id($uid, TRUE) ){
                return "__ERR_VOL_U_G";
            }
            if (! $this->exists_with_id($trid) ){
                return "__ERR_VOL_TRD_GONE";
            }
        }
        
        $QO = new QUERY("qryl4trartn3");
        $params = array(':art_accid' => $uid, ':trart_trid' => $trid);
        $datas = $QO->execute($params);
        
        return ( $datas ) ?  $datas[0]["ucontb"] : 0;
    }
    
    /*****************************************************************************************************************/
    /************************************************** ON_ALTER *****************************************************/
    
    public function onalter_update_archv_trend ($args, $std_err_enabled = FALSE) {
        /*
         * Permet de mettre à jour les données d'une Tendance dans la table SRH.
         * La mise à jour se fait a partir de l'identifiant de la Tendance passé en paramètre.
         * L'utilisateur peut passer trid ou trd_eid
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $trd_eid = $trid = NULL;
        if (! ( !empty($args) && is_array($args) && key_exists("trd_eid", $args) && !empty($args["trd_eid"]) ) )
        {
            if ( key_exists("trid", $args) && !empty($args["trid"]) && !is_array($args["trid"]) ) {
                $trid = $args["trid"];
            } else if ( !empty($this->trid) )  {
                $trid = $this->trid;
            } else {
                if ( empty($this->trd_eid) && $std_err_enabled ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $args,'v_d');
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
                } else if ( empty($this->trd_eid) && !$std_err_enabled ) {
                    return "__ERR_VOL_ENTY_LOAD_FAILED";
                } else {
                    $trd_eid = $this->trd_eid;
                } 
            }
            
        } else {
            $trd_eid = $args["trd_eid"];
        }
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$args,$trid,$trd_eid],'v_d');
        
        //On vérifie que la Tendance existe et on la charge
        if ( isset($trid) && !isset($trd_eid) ) {
            $t_tab = $this->on_read_entity(["trid"=>$trid]);
        } else if ( !isset($trid) && isset($trd_eid) ) {
            $t_tab = $this->on_read_entity(["trd_eid"=>$trd_eid]);
        }
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$t_tab],'v_d');
        
        $QO = new QUERY("qryl4trdn20");
        $params = array(
            ":id"       => $t_tab["trid"],
            ":title"    => $t_tab["trd_title"],
            ":desc"     => $t_tab["trd_desc"],
            ":tlehrf"   => $t_tab["trd_title_href"],
            ":folr"     => $t_tab["trd_stats_subs"],
            ":post"     => $t_tab["trd_stats_posts"],
            ":opsd"     => $t_tab["trd_opsd"],
            ":ofn"      => $t_tab["trd_ofn"]
        );  
        $QO->execute($params);
        
        return TRUE;
        
    }
    
    
    /**
     * Permet de changer proprement l'etat d'un Article.
     *
     * @param string $teid L'identifiant externe de la Tendance
     * @param int $state Le futur état de la Tendance
     * @return string|boolean
     */
    public function onalter_change_state($teid, $state) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que l'etat est attendu
        if (! is_numeric($state) ) {
            return "__ERR_VOL_FAILED";
        }
        if (! in_array(intval($state), $this->_TRD_STATE) ) {
            return "__ERR_VOL_MSM";
        }
        $state = intval($state);
        
        $trtab = $this->exists($teid,["NO_STATE_CHECK"]);
        if (! $trtab ) {
            return "__ERR_VOL_TRD_GONE";
        }
        
        /*
         * [NOTE 08-06-15] @BOR
         * On suppose qu'on aura toujours qu'un seul état valide par Tendance.
         * Il est donc important de toujours utiliser cette méthode pour tout changement d'état au risque de tomber sur des erreurs non gérées.
         */
        
        $now = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($now/1000));
                            
        //On lance la procédure en faisant attention de ne pas lancer la procédure pour rien.
        if ( !isset($trtab["tsh_state"]) ) {
            
            //On ajoute le nouvel état 
            $QO = new QUERY("qryl4trdn28");
            $params = array( ':tsh_trid' => $trtab["trid"], ':tsh_state' => $state, ':time' => $date, ':tstamp' => $now );
            $QO->execute($params);
            
        } else if ( !empty($trtab["tsh_id"]) && intval($trtab["tsh_state"]) !== $state ) {
            
            //On termine l'état précédent
            $QO = new QUERY("qryl4trdn27");
            $params = array( ':tshid' => $trtab["tsh_id"], ':time' => $date, ':tstamp' => $now );
            $QO->execute($params);
            
            //On crée une ligne pour le nouvel état
            $QO = new QUERY("qryl4trdn28");
            $params = array( ':tsh_trid' => $trtab["trid"], ':tsh_state' => $state, ':time' => $date, ':tstamp' => $now  );
            $QO->execute($params);
            
        } else if ( !empty($trtab["tsh_id"]) && intval($trtab["tsh_state"]) === $state ) { 
            return TRUE;
        } else {
            return "__ERR_VOL_TRD_GONE";
        }
        
        /*
         * [DEPUIS 02-08-15] @BOR
         *  On change l'état de tous les Articles de la Tendance.
         *  On ne le fait que dans les cas des état 'ToRemove' et 'Available' sinon on ne pourra pas avoir accès aux Articles de la Tendance durant la période BUFFERING.
         */
        if ( in_array($state,[2,3,5]) ) {
            $y__ = $this->onalter_change_all_art_state($trtab["trid"],$state,["AQAP","UPD_CAP_NO"]);
        } else if ( in_array($state,[1,4,6]) ) {
            $astate = ( in_array($state,[4,6]) ) ? 6 : $state;
            $y__ = $this->onalter_change_all_art_state($trtab["trid"],$astate,["AQAP","UPD_CAP_NO"]);
        }
            
        /*
         * [DEPUIS 06-08-15] @BOR
         *  On met à jour le capital du Compte de l'utilisateur.
         */
        $PA = new PROD_ACC();
        $y__ = $PA->update_capital_for($trtab["trd_owner"],["AQAP"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $y__) ) {
            return "__ERR_VOL_ALMST_DONE";
        }
        
        return ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $y__) ) ? "__ERR_VOL_ALMST_DONE" : TRUE;
    }
    
    
    public function onalter_change_all_art_state($trid, $state, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$trid,$state]);
        
        //On vérifie que l'etat est attendu
        if (! is_numeric($state) ) {
            return "__ERR_VOL_FAILED";
        }
        if (! in_array(intval($state), ARTICLE::$_ART_STATE_STATIC) ) {
            return "__ERR_VOL_MSM";
        }
        $state = intval($state);
        
        /*
         * On vérifie si l'objet lié à l'identifiant de référence existe le cas échéant.
         * On utilise pas la méthode exists() car elle prend en compte l'état ce qui biaiserait l'opération dans notre cas.
         */ 
        if (! ( $_OPTIONS && is_array($_OPTIONS) && in_array("AQAP", $_OPTIONS) ) ) {
            $QO = new QUERY("qryl4trdn26neo0615001");
            $params = array( ':trid' => $trid );
            $datas = $QO->execute($params);
            if (! $datas ) {
                return "__ERR_VOL_TRD_GONE";
            }
            $trtb = $datas[0];
        }
        
        $now = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($now/1000));
        
        /*
         * On met à jour les occurrences dans la table des historiques pour les Articles ayant déjà un "state".
         * Il se peut qu'il y'en ait pas.
         */
        $QO = new QUERY("qryl4artn25");
        $params = array( ':trid' => $trid, ':time' => $date, ':tstamp' => $now);
        $QO->execute($params);
        
        /*
         * On ajoute les nouvelles occurrences dans la table des historiques pour tous les Articles.
         */
        $QO = new QUERY("qryl4artn26");
        $params = array( ':trid' => $trid, ':ash_state' => $state, ':time' => $date, ':tstamp' => $now);
        $QO->execute($params);
        
        /*
         * Dans tous les cas, on change les états des Articles pour les versions VM.
         */
        $QO = new QUERY("qryl4artn27");
        $params = array( ':trid' => $trid, ':state' => $state );
        $QO->execute($params);
        
        /*
         * [DEPUIS 06-08-15] @BOR
         *  On met à jour le capital du Compte de l'utilisateur sauf si on a une indication contraire.
         */
        if (! ( $_OPTIONS && is_array($_OPTIONS) && in_array("UPD_CAP_NO", $_OPTIONS) ) ) {
            $QO = new QUERY("qryl4trdn26neo0615001");
            $params = array( ':trid' => $trid );
            $d__ = $QO->execute($params);
            if (! $d__ ) {
                return "__ERR_VOL_TRD_GONE";
            }
            $trtb = $d__[0];
            
            $PA = new PROD_ACC();
            $y__ = $PA->update_capital_for($trtb["trd_owner"],["AQAP"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $y__) ) {
                return "__ERR_VOL_ALMST_DONE";
            }
        }
        
        return TRUE;
    }
    
    /**************************************************************************************************************/
    /************************************************** ON_DELTE **************************************************/
    
    private function ondelete_statehisty ($trid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $trid);
        
        $QO = new QUERY("qryl4trdn29");
        $qparams = array(":trid" => $trid);  
        $QO->execute($qparams);
        
        return TRUE;
    }
    
    /************************************************************************************
    /************************* GETS SPECIFICS ARTICLES (START) *************************/
     
    /**
     * Permet de récupérer les ARTICLES triés selon un flag. Liste des flags disponibles :
     *  TRART_FILTER_GET_NEW : Lister les ARTICLES dont la date de création est SUPERIEURE à la date donnée en paramètre
     *  TRART_FILTER_GET_PREDATE : Lister les ARTICLES dont la date de création est INFERIEURE à la date donnée en paramètre
     * 
     * Le paramètre timestamp est généralement celui d'un ARTICLE référence.
     * Il est donc important de récupérer le TIMESTAMP en millisecondes plutot qu'une simple DATE moins précise et plus difficile à manier.
     * 
     * ATTENTION : Le signe utilisé est <= ou >= On le fait pour récupérer les articles insérés au même moment. 
     *              En FrontEnd on vérifie si l'ARTICLE est déjà listé pour éviter des doublons possibles.
     * 
     * @param type $timestamp
     * @param type $FLAG
     * @param type $std_err_enabled
     * @return array Tableau contenant les Articles s'ils existent. Sinon, renvoie NULL
     */
    public function on_read_get_filtered_articles_from ($art_eid, $timestamp, $FLAG, $trd_eid, $urqid = NULL, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
        if ( !$this->is_instance_load && $std_err_enabled ) {
            $this->signalError ("err_sys_l4comn14", __FUNCTION__, __LINE__,TRUE);
        } else if ( !$this->is_instance_load && !$std_err_enabled ){
            return "_ERR_VOL_ENTITY_MUST_BE_LOADED";
        }
        //*/    
        
        /*
         * [DEPUIS 16-08-15] @BOR
         *  On vérifie si la référence EXISTS
         */
        $ART = new ARTICLE();
        if (! $ART->exists($art_eid) ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        //["TRART_FILTER_GET_PREDATE", "TRART_FILTER_GET_OLD"]
        if ( in_array($FLAG, $this->_TR_FEEDMORE_FLAGS) ) {
            
//            $this->_TR_LOADMORE_ART_NB = 10; //DEBUG, FAST CHANGING
            $articles = $eid = $datas = NULL;
//            var_dump($FLAG);
//            exit();
            switch ( $FLAG ) {
                case "TRART_FILTER_GET_NEW":
                        $QO = new QUERY("qryl4trartn4");
                        $params = array(":art_eid" => $art_eid, ":trart_treid" => $trd_eid, ":art_cdate_tstamp" => $timestamp);
                        $datas = $QO->execute($params);
                    break;
                case "TRART_FILTER_GET_PREDATE":
                        $QO = new QUERY("qryl4trartn5");
                        $params = array(":art_eid" => $art_eid, ":trart_treid" => $trd_eid, ":art_cdate_tstamp" => $timestamp, ":limit" => $this->_TR_LOADMORE_ART_NB);
                        $datas = $QO->execute($params);
                    break;
                default :
                    return;
            }
            
//            var_dump(__FILE__,__LINE__,$art_eid, $timestamp, $FLAG, $trd_eid,$FLAG,$datas);
//            exit();
            
            if ( $datas ) {
                foreach ($datas as $v) {
                   $art_eid = $v["art_eid"];
                    
//                   $ART_TR = new ARTICLE_TR();
//                   $articles[] = $ART_TR->on_read(["art_eid"=>$art_eid], $urqid);
                   $ART_TR = new ARTICLE();
                   $articles[] = $ART_TR->onread_archive_itr(["art_eid"=>$art_eid], $urqid);
                    
                }
            }
            
            return $articles;
            
        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ", $this->_TR_FEEDMORE_FLAGS],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ", $FLAG],'v_d');
            $this->signalError ("err_sys_l4trdn6", __FUNCTION__, __LINE__,TRUE);
        }
        
    }
    
    /************ GETS SPECIFICS ARTICLES (START) *************/
    
    public function get_trid_from_trdeid ( $trd_eid ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4trdn6");
        $params = array(":trd_eid" => $trd_eid);
        $datas = $QO->execute($params);
        
        if (! $datas )
            return "__ERR_VOL_TREND_GONE";
        else 
            return $datas[0]["trid"];
        
    }
    
    public function get_trdeid_from_trid ( $trid, $std_err_enabled = FALSE ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4trdn9");
        $params = array(":trid" => $trid);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            if ( $std_err_enabled ) {
                $this->signalError ("err_user_l4trdn3", __FUNCTION__, __LINE__);
            } else {
                return "__ERR_VOL_TREND_GONE";
            }
        }
        else {
            return $datas[0]["trd_eid"];
        }
        
    }
    
    /*------ TREND SUBSCRIBE ------ */
    
            
    public function trend_abo_exists ($uid, $trd_eid, &$trid = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $trd_eid]);
        
        $trid = $this->get_trid_from_trdeid ($trd_eid);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $trid) ) {
            $this->Ajax_Return("err",$trid);
        }
        
        //On vérifie si une relation existe entre l'utilisateur et la Tendance
        $QO = new QUERY("qryl4tbon5");
        $params = array(":uid" => $uid, ":trid" => $trid);
        $datas = $QO->execute($params);
        
        if (! $datas )
            return FALSE;
        else {
            return $datas[0];
        }
    }
    
    public function trend_subscribe ($uid, $trd_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $aboid = $trid = NULL;
        //On vérifie si une relation n'existe pas déjà entre TR et CU
        $abo_datas = $this->trend_abo_exists ($uid, $trd_eid, $trid);
        
        
        if ( $abo_datas ) {
            return "__ERR_VOL_ABO_EXISTS";
        } 
        
        /*
         * ETAPE :
         *      On vérifie que CU n'est pas le propriétaire de la Tendance. Par la meme occasion on vérifie si la Tendance existe toujours
         */
        $QO = new QUERY("qryl4trdn1");
        $params = array(":trd_eid" => $trd_eid);
        $datas = $QO->execute($params);
        if (! $datas ){
            return "__ERR_VOL_TR_GONE";
        }
        
        $trd_oid = $datas[0]["trd_owner"];
        if ( intval($uid) === intval($trd_oid) ) {
            return "__ERR_VOL_IS_OWNER";
        }
        
        /*
         * ETAPE :
         *      
         */
        $time = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tbon1");
        $params = array(
            ":trabo_trid"       => $trid, 
            ":trabo_follower"   => $uid, 
            ":trabo_datecrea_tstamp" => $time
        );
        $datas = $QO->execute($params);
        
        if (! $datas  ) {
            //TODO : Vérifier si l'abonnement a quand même été pris en compte et renvoyer 'aboid'
            
            return "__ERR_VOL_BDD_INTERNAL_ERR";
        }
        else 
        {
            /*
             * NOTES
             *      -> Ici $datad représente 'last_insert_id' qui est en fait 'traboid'.
             *      -> ASTUCE : Pour vérifier si le string est une erreur on peut faire un in_array() avec une liste d'erreurs connues.
             */
            return $datas;
        }
    }
    
    public function trend_disconnect ($cuid, $trd_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas = $this->trend_abo_exists($cuid, $trd_eid);
        
//        $now = round(microtime(TRUE)*1000);
//        var_dump($cuid,$trd_eid,$datas["traboid"], $now);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        }
        
        if (! $datas ) {
           return "__ERR_VOL_NO_TRABO";
        }
        
        //On récupère l'identifiant de l'abonnement
        $aboid = $datas["traboid"];
        
        //On supprime l'abonnement
        /*
         * [NOTE 01-09-14] @author L.C.
         * J'ai changé le mécanisme. Avant on supprimait l'occurrence au lieu de la mettre à jour.
         * Supprimer nous fait perdre des données et l'historique des données.
         */
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tbon8");
        $params = array(":traboid" => $aboid, ":tstamp" => $now);
        $QO->execute($params);

       return TRUE;
       
    }

    public function trend_get_trabo_number ( $trid ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4tbon3_wtdlo");
//        $QO = new QUERY("qryl4tbon3"); //[NOTE 11-09-15] @author BOR
        $params = array(":trid" => $trid);
        $datas = $QO->execute($params);

        return (! $datas ) ? 0 : $datas[0]["trabo"];
    }
    
    private function GRANT_ALL_PRIVILEGES ($cuid, $trd_eid, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        /**
         * Permet d'autoriser certaines opérations qui necessite que ce soit le propriétaire de la Tendance qui le fasse.
         * LISTE :
         *  -> Autoriser DEL_TR
         *  -> Autoriser ALTER_TR
         *  -> ...
         * 
         */
        //On vérifie que CU n'est pas le propriétaire de la Tendance. Par la meme occasion on vérifie si la Tendance existe toujours
        $QO = new QUERY("qryl4trdn1");
        $params = array(":trd_eid" => $trd_eid);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            if ( $std_err_enabled )
                $this->signalError ("err_user_l4trdn3", __FUNCTION__, __LINE__);
            else
                return "__ERR_VOL_TR_GONE";
        }
        
        $trd_oid = $datas[0]["trd_owner"];
        
        if ( is_int($trd_oid) ) 
            $cuid = intval ($cuid);
        else 
            $cuid = (string)$cuid;
        
        if ( $cuid == $trd_oid )
            return TRUE;
        else 
            FALSE;
    }
    
    /**************************************************************************************************/
    /*********************************** GETTERS and SETTERS ******************************************/
    
    public function setTrd_title($trd_title, $cuid, $std_err_enabled = FALSE, $no_load = FALSE) {
        ///no_load : Permet de dire qu'il ne faut pas load pour de multiples raisons que seul CALLER connait
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! $this->is_instance_load ) {
            return;
        }
        
        $granted = $this->GRANT_ALL_PRIVILEGES($cuid, $this->trd_eid, $std_err_enabled);
        if ( !$granted && $std_err_enabled ) {
                $this->signalError ("err_user_l4comn12", __FUNCTION__, __LINE__,TRUE);
        } else if ( !$granted && !$std_err_enabled ) {
            return "__ERR_VOL_DENY"; 
        }
        
        /*
         * On ne peut pas juste renvoyer TRUE ou FALSE. Car JS a laisser passer quand il ne devait pas.
         * Il serait même peut être préférable que de signaler l'erreur OBLIGATOIREMENT.
         */
        $time = round(microtime(TRUE)*1000);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->trid,$trd_title,$time],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        set_error_handler('exceptions_error_handler');
        try {
            
            /*
             * [NOTE 09-10-14] @author L.C.
             * On vérifie que le titre n'est pas trop long et qu'il ne s'agit pas d'une tentative de "bourrer" la base de données
             */
            $ori_title = $trd_title;
            $new_title = $this->valid_trd_title($trd_title);
            if ( !$new_title && !$std_err_enabled ) {
                return "__ERR_VOL_TRTITLE_NOT_COMPLY";
            } else if ( !$new_title && $std_err_enabled ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $ori_title,'v_d');
                $this->signalError ("err_user_l4comn15", __FUNCTION__, __LINE__,TRUE);
            }
            $trd_title = $new_title;
            
//            var_dump(__LINE__,__FILE__,$trd_title);
            
            /* [NOTE 09-10-14] @author L.C. OBSELTE : Deux Tendances peuvent avoir le même titre
            //On vérfie que le titre n'est pas déjà pris
            $tr_infos = $this->trend_get_trend_infos_by_title($trd_title);
            if ( $tr_infos && !$std_err_enabled ){
                return "__ERR_VOL_TR_EXISTS_BY_TITLE";
            } else if ( $tr_infos && $std_err_enabled ) {
                $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
            }
            //*/
            
            $TH = new TEXTHANDLER();
            $trd_title_href = $TH->text_urlize_from_pattern($ori_title);
            
            //On modifie la valeur dans la base de données
            $QO = new QUERY("qryl4trdn10");
            $params = array(":trid" => $this->trid, ":trd_title" => $trd_title, ":trd_title_href" => $trd_title_href, ":trd_datemod_tstamp" => $time);
            $QO->execute($params);
            
            //On modifie dans TrTile_Archive
            $QO = new QUERY("qryl4trdn11");
            $params = array(":trtle_trid" => $this->trid, ":trtle_lib" => $trd_title);
            $QO->execute($params);
            
            if ( !$no_load ) {
                //On load l'Entity
                return $this->load_entity(["trd_eid" => $this->trd_eid]);
            } else {
                $this->all_properties["trd_title"] = $this->trd_title = $trd_title;
                return $trd_title;
            }
            
        } catch (Exception $exc) {
            if (! $std_err_enabled ) {
                return "__ERR_VOL_UXPTD_ERR";
            } else {
                $this->signalErrorWithoutErrIdButGivenMsg ($exc->getMessage(), __FUNCTION__, __LINE__,true);
            }
            //PARANO
            exit();
        }
    }

    public function setTrd_desc($trd_desc, $cuid, $std_err_enabled = FALSE, $no_load = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! $this->is_instance_load )
            return;
        
        $granted = $this->GRANT_ALL_PRIVILEGES($cuid, $this->trd_eid, $std_err_enabled);
        
        if (! $granted && $std_err_enabled ) 
                $this->signalError ("err_user_l4comn12", __FUNCTION__, __LINE__,TRUE);
        else if (! $granted && !$std_err_enabled )
            return "__ERR_VOL_DENY"; 
        /*
         * On ne peut pas juste renvoyer TRUE ou FALSE. Car JS a laisser passer quand il ne devait pas.
         * Il serait même peut être préférable que de signaler l'erreur OBLIGATOIREMENT.
         */
        $time = round(microtime(TRUE)*1000);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->trid,$trd_title,$time],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        set_error_handler('exceptions_error_handler');
        try {
            //[NOTE 09-10-14] @author L.C.
            /* On valide $trd_desc */ 
            $ori_desc = $trd_desc;
            $new_desc = $this->valid_trd_desc($trd_desc);
            if ( !$new_desc && !$std_err_enabled )
                return "__ERR_VOL_TRDESC_NOT_COMPLY";
            else if ( !$new_desc && $std_err_enabled ) {
               $this->presentVarIfDebug(__FUNCTION__, __LINE__, $ori_desc,'v_d');
               $this->signalError ("err_user_l4comn15", __FUNCTION__, __LINE__);
            }
            $trd_desc = $new_desc;
            
            //On modifie la valeur dans la base de données
            $QO = new QUERY("qryl4trdn12");
            $params = array(":trid" => $this->trid, ":trd_desc" => $trd_desc, ":trd_datemod_tstamp" => $time);
            $QO->execute($params);

            if ( !$no_load ) {
                //On load l'Entity
                return $this->load_entity(["trd_eid" => $this->trd_eid]);
            } else {
                $this->all_properties["trd_desc"] = $this->trd_desc = $trd_desc;
                return $trd_desc;
            }

        } catch (Exception $exc) {
            if (! $std_err_enabled ) {
                return "__ERR_VOL_UXPTD_ERR";
            } else {
                $this->signalErrorWithoutErrIdButGivenMsg ($exc->getMessage(), __FUNCTION__, __LINE__,true);
            }
            //PARANO
            exit();
        }
    }

    public function setTrd_is_public($trd_is_public, $cuid, $std_err_enabled = FALSE, $no_load = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $cuid);
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $trd_is_public);
        
        if (! $this->is_instance_load )
            return;
        
        $granted = $this->GRANT_ALL_PRIVILEGES($cuid, $this->trd_eid, $std_err_enabled);
        
        if (! $granted && $std_err_enabled ) {
            $this->signalError ("err_user_l4comn12", __FUNCTION__, __LINE__,TRUE);
        }
        else if (! $granted && !$std_err_enabled ){
            return "__ERR_VOL_DENY"; 
        }
        /*
         * On ne peut pas juste renvoyer TRUE ou FALSE. Car JS a laisser passer quand il ne devait pas.
         * Il serait même peut être préférable que de signaler l'erreur OBLIGATOIREMENT.
         */
        //On met en forme is_pub
        if ( is_string($trd_is_public) && preg_match("/^_NTR_PART_(?:PUB|PRI)$/i", $trd_is_public) ) {
            $f__ = strtolower(str_replace("_NTR_PART_", "", $trd_is_public));
            $trd_is_public = $f__;
        }
        $trd_is_public = ( ( isset($trd_is_public) && filter_var($trd_is_public, FILTER_VALIDATE_BOOLEAN) && $trd_is_public ) || ( isset($trd_is_public) && $trd_is_public === "pub" )  ) ? 1 : 0;
        $time = round(microtime(TRUE)*1000);
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->trid,$trd_title,$time],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        set_error_handler('exceptions_error_handler');
        try {
            //On modifie la valeur dans la base de données
            $QO = new QUERY("qryl4trdn13");
            $params = array(":trid" => $this->trid, ":trd_is_public" => $trd_is_public, ":trd_datemod_tstamp" => $time);
            $QO->execute($params);

            if ( !$no_load ) {
                //On load l'Entity
                return $this->load_entity(["trd_eid" => $this->trd_eid]);
            } else {
                $this->all_properties["trd_is_public"] = $this->trd_is_public = $trd_is_public;
                return $trd_is_public;
            }

        } catch (Exception $exc) {
            if (! $std_err_enabled ) {
                return "__ERR_VOL_UXPTD_ERR";
            } else {
                $this->signalErrorWithoutErrIdButGivenMsg ($exc->getMessage(), __FUNCTION__, __LINE__,true);
            }
            //PARANO
            exit();
        }
    }

    public function setTrd_grat($trd_grat, $cuid, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $cuid);
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $trd_grat);
        
        if (! $this->is_instance_load )
            return;
        
        $granted = $this->GRANT_ALL_PRIVILEGES($cuid, $this->trd_eid, $std_err_enabled);
        
        if (! $granted && $std_err_enabled ) 
                $this->signalError ("err_user_l4comn12", __FUNCTION__, __LINE__,TRUE);
        else if (! $granted && !$std_err_enabled )
            return "__ERR_VOL_DENY"; 
        /*
         * On ne peut pas juste renvoyer TRUE ou FALSE. Car JS a laisser passer quand il ne devait pas.
         * Il serait même peut être préférable que de signaler l'erreur OBLIGATOIREMENT.
         */
        if ( !$this->valid_trd_gratification($trd_grat) && !$std_err_enabled )
            return "__ERR_VOL_TRGRAT_NOT_COMPLY";
        else if ( !$this->valid_trd_gratification($trd_grat) && $std_err_enabled )
           $this->signalError ("err_user_l4comn10", __FUNCTION__, __LINE__);
        
        $time = round(microtime(TRUE)*1000);
//        set_error_handler('exceptions_error_handler');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->trid,$trd_title,$time],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        try {
            //On modifie la valeur dans la base de données
            $QO = new QUERY("qryl4trdn14");
            $params = array(":trid" => $this->trid, ":trd_grat" => $trd_grat, ":trd_datemod_tstamp" => $time);
            $QO->execute($params);

            $this->trd_grat = $trd_grat;

            //On load l'Entity
            $this->load_entity(["trd_eid" => $this->trd_eid]);

        } catch (Exception $exc) {
            exit();
        }
    }
    
    //ON NE PEUT PAS MODIFIER LA CATEGORIE APRES 24HEURES
    public function setTrd_catgid($catg_decocode, $cuid, $std_err_enabled = FALSE, $no_load = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! $this->is_instance_load )
            return;
        
        $granted = $this->GRANT_ALL_PRIVILEGES($cuid, $this->trd_eid, $std_err_enabled);
        
        if (! $granted && $std_err_enabled ) 
            $this->signalError ("err_user_l4comn12", __FUNCTION__, __LINE__,TRUE);
        else if (! $granted && !$std_err_enabled )
            return "__ERR_VOL_DENY"; 
        /*
         * On ne peut pas juste renvoyer TRUE ou FALSE. Car JS a laisser passer quand il ne devait pas.
         * Il serait même peut être préférable que de signaler l'erreur OBLIGATOIREMENT.
         */
        $trd_catgid = $this->valid_trd_category_by_code($catg_decocode);
        if ( !$trd_catgid && !$std_err_enabled )
            return "__ERR_VOL_TRCATG_NOT_COMPLY";
        else if ( !$trd_catgid && $std_err_enabled )
           $this->signalError ("err_user_l4comn15", __FUNCTION__, __LINE__);
        
        $time = round(microtime(TRUE)*1000);
        
        //* On vérifie que le delais de carence de Xh n'est pas dépassé *//
        $intv = $time - $this->trd_creadate_tstamp;
        
        $this->_MAX_TIME_CHANGE_CATG = 1000; //DEGUG, TEST
        
        if ( ( $intv > $this->_MAX_TIME_CHANGE_CATG*3600000 ) && !$std_err_enabled )
            return "__ERR_VOL_TRD_CATGLOCK";
        else if ( ( $intv > $this->_MAX_TIME_CHANGE_CATG*3600000 ) && $std_err_enabled )
           $this->signalError ("err_user_l4comn13", __FUNCTION__, __LINE__);
        
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$this->trid,$trd_title,$time],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        set_error_handler('exceptions_error_handler');
        try {
        
            //On modifie la valeur dans la base de données
            $QO = new QUERY("qryl4trdn15");
            $params = array(":trid" => $this->trid, ":trd_catgid" => $trd_catgid, ":trd_datemod_tstamp" => $time);
            $QO->execute($params);

            $this->trd_catgid = $trd_catgid;

            if ( !$no_load ) {
                //On load l'Entity
                return $this->load_entity(["trd_eid" => $this->trd_eid]);
            } else {
                $this->all_properties["trd_catgid"] = $this->trd_catgid = $trd_catgid;
                return $trd_catgid;
            }

        } catch (Exception $exc) {
            if (! $std_err_enabled ) {
                return "__ERR_VOL_UXPTD_ERR";
            } else {
                $this->signalErrorWithoutErrIdButGivenMsg ($exc->getMessage(), __FUNCTION__, __LINE__,true);
            }
            //PARANO
            exit();
        }
        
    }
    
    /************************************** GETTERS ***************************************/
    
    public function getTrid() {
        return $this->trid;
    }

    public function getTrd_eid() {
        return $this->trd_eid;
    }

    public function getTrd_title() {
        return $this->trd_title;
    }

    public function getTrd_href() {
        return $this->trd_href;
    }

    public function getTrd_title_href() {
        return $this->trd_title_href;
    }

    public function getTrd_desc() {
        return $this->trd_desc;
    }

    public function getTrd_is_public() {
        return $this->trd_is_public;
    }

    public function getTrd_grat() {
        return $this->trd_grat;
    }

    public function getTrd_catgid() {
        return $this->trd_catgid;
    }

    public function getCatg_decocode() {
        return $this->catg_decocode;
    }

    public function getTrd_loc_numip() {
        return $this->trd_loc_numip;
    }

    public function getTrd_country() {
        return $this->trd_country;
    }

    public function getTrd_cover() {
        return $this->trd_cover;
    }

    public function getTrd_oid() {
        return $this->trd_oid;
    }

    public function getTrd_oeid() {
        return $this->trd_oeid;
    }

    public function getTrd_ofn() {
        return $this->trd_ofn;
    }

    public function getTrd_opsd() {
        return $this->trd_opsd;
    }

    public function getTrd_oppic() {
        return $this->trd_oppic;
    }

    public function getTrd_ohref() {
        return $this->trd_ohref;
    }

    public function getAcc_todelete() {
        return $this->acc_todelete;
    }

    public function getTrd_creadate() {
        return $this->trd_creadate;
    }

    public function getTrd_creadate_tstamp() {
        return $this->trd_creadate_tstamp;
    }

    public function getTrd_datemod() {
        return $this->trd_datemod;
    }

    public function getTrd_datemod_tstamp() {
        return $this->trd_datemod_tstamp;
    }

    public function getTrd_next_del() {
        return $this->trd_next_del;
    }

    public function getTrd_next_del_tstamp() {
        return $this->trd_next_del_tstamp;
    }

    public function getTrd_stats_posts() {
        return $this->trd_stats_posts;
    }

    public function getTrd_stats_subs() {
        return $this->trd_stats_subs;
    }

    public function getTrd_stats_vips() {
        return $this->trd_stats_vips;
    }

    public function getTrd_first_articles() {
        return $this->trd_first_articles;
    }
    
    public function getTsh_state() {
        return $this->tsh_state;
    }

    public function getTsh_state_time() {
        return $this->tsh_state_time;
    }

    public function get_MAX_TITLE() {
        return $this->_MAX_TITLE;
    }

    public function get_MAX_DESC() {
        return $this->_MAX_DESC;
    }

    public function get_AKX_CHOICES() {
        return $this->_AKX_CHOICES;
    }
    
    public function get_AKX_CHOICES_EXT() {
        return $this->_AKX_CHOICES_EXT;
    }

    public function get_GRAT_CHOICES() {
        return $this->_GRAT_CHOICES;
    }

    public function get_MAX_GRAT() {
        return $this->_MAX_GRAT;
    }

    public function get_TR_FIRST_ART_NB() {
        return $this->_TR_FIRST_ART_NB;
    }

    public function get_TR_WLC_MAX_ART_NB() {
        return $this->_TR_WLC_MAX_ART_NB;
    }

    public function get_MAX_TIME_CHANGE_CATG() {
        return $this->_MAX_TIME_CHANGE_CATG;
    }
    
    public function get_TR_LOADMORE_ART_NB() {
        return $this->_TR_LOADMORE_ART_NB;
    }

    public function get_TR_FEEDMORE_FLAGS() {
        return $this->_TR_FEEDMORE_FLAGS;
    }




}