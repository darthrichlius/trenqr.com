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
class PROD_ACC extends PROD_ENTITY {
    
    /* DEPUIS BASE ACCOUNT */
    private $pdaccid;
    private $pdacc_gid;
    private $pdacc_href;
    private $pdacc_eid;
    private $pdacc_upsd;
    private $pdacc_ufn;
    private $pdacc_gdr;
    private $pdacc_uppicid;
    private $pdacc_uppic;
    private $pdacc_uppisdf;
    private $pdacc_coverdatas;
//    private $pdacc_coverpic;
    private $pdacc_ucityid;
    private $pdacc_ucity_fn;
    private $pdacc_nocity;
    private $pdacc_ucnid;
    private $pdacc_ucn_fn;
    private $pdacc_udl;
    private $pdacc_datecrea;
    private $pdacc_datecrea_tstamp;
    private $pdacc_todelete;
    
    /* DEPUIS BASE PRODUCT */
    
    private $pdacc_ctw_dsma;
    private $pdacc_ctw_moddate;
    private $pdacc_ctw_moddate_tstamp;
    private $pdacc_profilbio;
    private $pdacc_website;
    private $pdacc_capital; 
    
    /********* EXTRAS - STATS **********/
    private $pdacc_stats_posts_nb;
    private $pdacc_stats_mytrends_nb;
    private $pdacc_stats_fol_trends_nb;
    private $pdacc_stats_folr_nb;
    private $pdacc_stats_folg_nb;
    
    
    /********* EXTRAS - DATAS ***********/
    private $my_trends_list; 
    private $my_following_trends_list; 
    
    private $my_followers_list; 
    private $my_following_list; 
    
    private $my_friends_list; 
    private $my_friend_request_list;
    
    /********* RULES ***********/
    /**
     * Le nombre d'Articles maximum affichables pour un Compte lors du chargement de la page.
     * @var int
     */
    private $_LIMIT_FIRST_ARTS;
    /**
     * Le nombre d'Articles affichés en mode sample au niveau d'une page FOKUS.
     * @var int 
     */
    private $_LIMIT_FKSASMPL_ARTS;
    /**
     * Le nombre d'Articles maximum affichables pour un Compte lorsque que l'on veut les Articles les plus récents.
     * @var int 
     */
    private $_LIMIT_NWR_ARTS;
    /**
     * Le nombre d'Articles maximum affichables pour un Compte lorsque que l'on veut les Articles les plus anciens.
     * @var int 
     */
    private $_LIMIT_PD_ARTS;
    /**
     * Le nombre de caractères maximum autorisés pour une bio de profil.
     * @var int 
     */
    private $_PFLBIO_MAX;
    /**
     * [DEPUIS 19-06-15 ] @BOR
     * Le nombre de caractères maximum autorisés pour une adresse de siteweb.
     * NOTES : 
     *  -> Ce chiffre est arbritraire. Il ne respecte aucune règle spécifique. Il suit la capacité prévue en base.
     * @var int 
     */
    private $_UWEBSITE_MAX;
    /**
     * @var type 
     */    
    private $_UWEBSITE_RGX;
    /**
     * Est ce qu'on autorise l'utilisateur à entrer des URL.
     * @var int
     */
    private $_PFLBIO_HREF_AUTHORIZED;
    /**
     * Est ce qu'on fait de telle sorte que les URL soit cliclable ?
     * 
     * @var int 
     */
    private $_PFLBIO_HREF_ENALBLE;
    /*
     * Tableau qui repertorie les adresses des images par défaut selon les sexes.
     */
    private $_DFLT_PICS;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["pdaccid","pdacc_gid","pdacc_href","pdacc_eid","pdacc_upsd","pdacc_ufn","pdacc_gdr","pdacc_uppicid","pdacc_uppic","pdacc_uppisdf","pdacc_coverdatas","pdacc_ucityid","pdacc_ucity_fn","pdacc_nocity","pdacc_ucnid","pdacc_ucn_fn","pdacc_udl","pdacc_datecrea","pdacc_datecrea_tstamp","pdacc_todelete","pdacc_ctw_dsma","pdacc_ctw_moddate","pdacc_ctw_moddate_tstamp","pdacc_profilbio", "pdacc_website", "pdacc_capital","pdacc_stats_posts_nb","pdacc_stats_mytrends_nb","pdacc_stats_fol_trends_nb","my_trends_list","my_following_trends_list","my_followers_list","my_following_list","my_friends_list","my_friend_request_list"];
        $this->needed_to_loading_prop_keys = ["pdaccid","pdacc_gid","pdacc_href","pdacc_eid","pdacc_upsd","pdacc_ufn","pdacc_gdr","pdacc_uppicid","pdacc_uppic","pdacc_uppisdf","pdacc_coverdatas","pdacc_ucityid","pdacc_ucity_fn","pdacc_nocity","pdacc_ucnid","pdacc_ucn_fn","pdacc_udl","pdacc_datecrea","pdacc_datecrea_tstamp","pdacc_todelete","pdacc_ctw_dsma","pdacc_ctw_moddate","pdacc_ctw_moddate_tstamp","pdacc_profilbio", "pdacc_website", "pdacc_capital","pdacc_stats_posts_nb","pdacc_stats_mytrends_nb","pdacc_stats_fol_trends_nb"];
        
        //Pour des raisons de simplification, on retire le préfixe 'pd' qui alourdit le code
        $this->needed_to_create_prop_keys = ["accid","acc_gid","acc_eid","acc_upsd","acc_ufn","acc_ugdr","acc_ucityid","acc_ucity_fn","acc_nocity","acc_ucnid","acc_ucn_fn","acc_udl","acc_datecrea","acc_datecrea_tstamp","acc_capital"];
        $this->needed_to_update_prop_keys = ["accid","acc_gid","acc_eid","acc_upsd","acc_ufn","acc_ugdr","acc_uppicid","acc_uppic","acc_coverpicid","acc_coverpic","acc_ucityid","acc_ucity_fn","acc_nocity","acc_ucnid","acc_ucn_fn","acc_udl","acc_datecrea","acc_datecrea_tstamp","acc_capital","acc_todelete"];
        
        /*************** RULES ******************/
        //ARTICLES (vb1)
        $this->_LIMIT_FIRST_ARTS = 3;
        $this->_LIMIT_FKSASMPL_ARTS = 10;
        $this->_LIMIT_NWR_ARTS = 10;
        $this->_LIMIT_PD_ARTS = 10;
//        $this->_LIMIT_NWR_ARTS = 5;
//        $this->_LIMIT_PD_ARTS = 5;
        
        //PROFILBIO (vb1)
        $this->_PFLBIO_MAX = 140;
        $this->_PFLBIO_HREF_AUTHORIZED = 1;
        $this->_PFLBIO_HREF_ENALBLE = 0;
        $this->_UWEBSITE_MAX = 255;
        $this->_UWEBSITE_MAX = 255;
        $this->_UWEBSITE_RGX = '_^(?:(?:https?)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';
        
        //PROFIL_PIC
        $this->_DFLT_PICS = [
            /*
            "m" => "http://timg.ycgkit.com/files/img/r-dp/tqr_std_ppic_m.png",
            "f" => "http://timg.ycgkit.com/files/img/r-dp/tqr_std_ppic_f.png"
            //*/
            /* //[DEPUIS 03-07-16]
            "m" => WOS_SYSDIR_PRODIMAGE_X_DPPIC."/r-dp/tqr_std_ppic_m.png",
            "f" => WOS_SYSDIR_PRODIMAGE_X_DPPIC."/r-dp/tqr_std_ppic_f.png"
            //*/
            "m" => WOS_SYSDIR_PRODIMAGE_X_DPPIC_2."/r-dp/tqr_std_ppic_m.png",
            "f" => WOS_SYSDIR_PRODIMAGE_X_DPPIC_2."/r-dp/tqr_std_ppic_f.png"
        ];
        
    }
    
    protected function build_volatile($args) {}

    public function exists($arg, $with_todelete = FALSE) {
        $acc_eid = NULL;
        
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["trd_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->pdacc_eid) ) {
                return;
            } else {
                $acc_eid = $this->pdacc_eid;
            }
        } else $acc_eid = $arg;
                
        //Contacter la base de données et vérifier si le Compte existe.
        $QO = new QUERY("qryl4pdaccn1");
        $params = array( ':acc_eid' => $acc_eid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            //On vérifie si CALLER souhaite que l'on considère que si l'utilisateur est en todelete, cela veut dire qu'il n'existe pas.
            if ( ( $with_todelete !== FALSE && $with_todelete !== 0 ) && ( $with_todelete === TRUE || $with_todelete === 1 ) 
                    && intval($datas[0]["pdacc_todelete"]) !== 0 ) {
                $r = FALSE;
            } else {
                $r = $datas[0];
            }
        }
        else {
            $r = FALSE;
        }
        
        return $r;
    }

    public function exists_with_id($arg, $with_todelete = FALSE) {
        $accid = NULL;
        
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["trd_eid"]
        if (! (!is_array($arg) && !empty($arg) ) ) {
            if ( empty($this->pdaccid) ) {
                return;
            } else {
                $accid = $this->pdaccid;
            }
        } else {
            $accid = $arg;
        }
                
        //Contacter la base de données et vérifier si le Compte existe.
        $QO = new QUERY("qryl4pdaccn2");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            //On vérifie si CALLER souhaite que l'on considère que si l'utilisateur est en todelete, cela veut dire qu'il n'existe pas.
            if ( ( $with_todelete !== FALSE && $with_todelete !== 0 ) && ( $with_todelete === TRUE || $with_todelete === 1 ) 
                    && intval($datas[0]["pdacc_todelete"]) !== 0 ) {
                $r = FALSE;
            } else {
                $r = $datas[0];
            }
            
        }
        else {
            $r = FALSE;
        }
        
        return $r;
    }
    
    /**
     * Permet de vérifier si un utilisateur existe pour le pseudo passé en paramètre au niveau de la base de données.
     * La méthode est paramétrable et admet une option pour vérifier que le Compte est actif et une deuxième option pour faire une recherche stricte (ou presque).
     * Dans ce dernier cas, on fait une recherche sans se soucier de la case mais où on CONSIDERE les accents. Aussi a === à est FALSE.
     * NB : wacrcso pour WithACcuRateOption
     * 
     * @param string $arg Le pseudonyme
     * @param boolean $_WITH_TODEL_OPT
     * @param boolean $_WITH_BIN_OPT
     * @return mixed Renvoie FALSE si aucun Compte n'est lié. Renvoie un tableau représentant la table du Compte dans le cas contraire.
     */
    public function exists_with_psd($arg, $_WITH_TODEL_OPT = FALSE, $_WITH_BIN_OPT = FALSE) {
        $upsd = NULL;
        
        //Déclencher une exception personnalisée si on ne recoit pas la valeur ["trd_eid"]
        if (! ( is_string($arg) && !empty($arg) && strlen($arg) && $arg !== "''" ) ) { //[NOTE 02-04-15] (3) et (4) sont importants pour déjouer les cas de chaine pseudo-vides
            if ( empty($this->pdacc_upsd) ) {
                return;
            } else {
                $upsd = $this->pdacc_upsd;
            }
        } else {
            $upsd = $arg;
        }
        
        //Contacter la base de données et vérifier si le COMPTE existe.
        if ( $_WITH_BIN_OPT ) {
            $QO = new QUERY("qryl4pdaccn10_wacrcso");
            $params = array( ':upsd' => strtolower($upsd) ); //OBLIGATOIRE
            $datas = $QO->execute($params);
        } else {
            $QO = new QUERY("qryl4pdaccn10");
            $params = array( ':upsd' => $upsd );
            $datas = $QO->execute($params);
        }
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",$QO,$params],'v_d');
//        $datas = $QO->execute($params);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>", $datas],'v_d');
        
        if ( $datas ) {
            //On vérifie si CALLER souhaite que l'on considère que si l'utilisateur est en todelete, cela veut dire qu'il n'existe pas.
            if ( ( $_WITH_TODEL_OPT !== FALSE && $_WITH_TODEL_OPT !== 0 ) 
                && ( $_WITH_TODEL_OPT === TRUE || $_WITH_TODEL_OPT === 1 ) 
                && intval($datas[0]["pdacc_todelete"]) !== 0 
            ) {
                $r = FALSE;
            } else {
                $r = $datas[0];
            }
            
        } else {
            $r = FALSE;
        }
        
        return $r;
    }

    protected function init_properties($datas) {
        /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
        
        if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
        }
        
        if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas),$this->needed_to_loading_prop_keys) ) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ",$this->needed_to_loading_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",array_keys($datas)],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>", array_diff($this->needed_to_loading_prop_keys,array_keys($datas))],'v_d');
            $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
        } 
        
        foreach($datas as $k => $v) {
            $$k = $v;
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
//        var_dump(264,intval($this->getPdacc_stats_posts_nb()));
//        exit();
    }

    protected function load_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, $args, TRUE);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $acc_eid;
        if (! ( !empty($args) && is_array($args) && key_exists("acc_eid", $args) && !empty($args["acc_eid"]) ) ) 
        {
            if ( empty($this->acc_eid) ) {
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            } else {
                $acc_eid = $this->acc_eid;
            }
        } else {
            $acc_eid = $args["acc_eid"];
        }
        
//        var_dump($acc_eid);
        // On controle si l'occurence existe et on récupèrre les données (notamment trd_oid)
        if ( key_exists("OPTIONS", $args) && $args["OPTIONS"] && $args["OPTIONS"]["_WITH_TODEL"] === FALSE ) {
            $exists = $this->exists($acc_eid,FALSE);
        } else {
            $exists = $this->exists($acc_eid,TRUE);
        }
        /*
        var_dump("LINE =>",__LINE__,"; DATAS => ",$args);    
        var_dump("LINE =>",__LINE__,"; DATAS => ",key_exists("OPTIONS", $args), $args["OPTIONS"]["_WITH_TODEL"], $args["OPTIONS"]["_WITH_TODEL"] === FALSE);    
        var_dump("LINE =>",__LINE__,"; DATAS => ",$exists);    
        //*/
        if ( !$exists && $std_err_enabled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__,TRUE);
        }
        else if ( !$exists && !$std_err_enabled ) 
        {
            return "__ERR_VOL_USER_GONE";
        }
        
        $account = $exists;
        $EV = new EVALUATION();
        $loads = [
            "pdaccid"                   => $account["pdaccid"], 
            "pdacc_gid"                 => $account["pdacc_gid"], 
            "pdacc_eid"                 => $account["pdacc_eid"], 
            "pdacc_upsd"                => $account["pdacc_upsd"], 
            "pdacc_ufn"                 => $account["pdacc_ufn"], 
            "pdacc_gdr"                 => $account["pdacc_gdr"], 
//            "pdacc_uppic"               => $account["pdacc_uppic"],
//            "pdacc_uppicid"             => $account["pdacc_uppicid"], 
//            "pdacc_coverpicid"          => $account["pdacc_coverpicid"], 
//            "pdacc_coverpic"            => $account["pdacc_coverpic"],
            "pdacc_ucityid"             => $account["pdacc_ucityid"], 
            "pdacc_ucity_fn"            => $account["pdacc_ucity_fn"], 
            "pdacc_nocity"              => $account["pdacc_nocity"], 
            "pdacc_ucnid"               => $account["pdacc_ucnid"], 
            "pdacc_ucn_fn"              => $account["pdacc_ucn_fn"], 
            "pdacc_udl"                 => $account["pdacc_udl"], 
            "pdacc_datecrea"            => $account["pdacc_datecrea"], 
            "pdacc_datecrea_tstamp"     => $account["pdacc_datecrea_tstamp"], 
            "pdacc_capital"             => $this->onread_updatedCapitalFor($account["pdaccid"],["AQAP"]),
//            "pdacc_capital"             => ( isset($account["pdacc_capital"]) ) ? $account["pdacc_capital"] : 0,
            "pdacc_todelete"            => $account["pdacc_todelete"],
            "pdacc_ctw_dsma"            => $account["pdacc_ctw_dsma"],
            "pdacc_ctw_moddate"         => $account["pdacc_ctw_moddate"],
            "pdacc_ctw_moddate_tstamp"  => $account["pdacc_ctw_moddate_tstamp"],
            "pdacc_profilbio"           => htmlentities($account["pdacc_profilbio"]),
            "pdacc_website"             => htmlentities($account["pdacc_website"])
        ];
        
        //************************* On récupère les données extras ************************//
        $accid = $account["pdaccid"];
        /*
            $pdacc_stats_posts_nb;
            $pdacc_stats_mytrends_nb;
            $pdacc_stats_fol_trends_nb;
            $pdacc_stats_folr_nb;
            $pdacc_stats_folg_nb;
        //*/
        
        /*
         * [NOTE 24-08-14] @BOR
         *      Pour l'instant je ne récupère pas les données sur les Relations.
         *      A cette date, je n'ai pas encore DEBUG l'entiity Relation car ça pourrait me prendre trop de temps.
         *      J'enchaine sur les tâches qui me prennent le moins de temps et je finirai avec Relation
         */
        
        //On calcule le nombre de posts total de l'utilisateur Actif
        $pnb = $this->onread_get_my_articles_nb($accid);
        $loads["pdacc_stats_posts_nb"] = (! $pnb ) ? 0 : $pnb;
        
        //On calcule le nombre de Tendances qui appartiennent à l'Utilisateur Actif
        /* 
        $mytrends = $this->onread_acquiere_my_trends_datas($accid);
        $loads["pdacc_stats_mytrends_nb"] =  (! $mytrends ) ? 0 : count($mytrends);
        //*/
        /*
         * [DEPUIS 12-08-16]
         */
        $loads["pdacc_stats_mytrends_nb"] = $this->onread_get_mytrds_count($accid,["AQAP"]);
        
        //On calcule le nombre de Tendances qui sont suivies par l'Utilisateur Actif
        /*
        $fol_trends = $this->onread_acquiere_following_trends_datas($accid);
        $loads["pdacc_stats_fol_trends_nb"] =  (! $fol_trends ) ? 0 : count($fol_trends);
        //*/
        /*
         * [DEPUIS 12-08-16]
         */
        $loads["pdacc_stats_fol_trends_nb"] = $this->onread_get_myfoltrds_count($accid,["AQAP"]);
        
        $loads["pdacc_coverdatas"] = $this->onread_acquiere_cover_datas($accid); 
        
        //Données sur la photo de profil
        $pp_datas = $this->onread_acquiere_pp_datas($accid,$loads["pdacc_gdr"]); 
        $loads["pdacc_uppicid"] = $pp_datas["picid"];
        $loads["pdacc_uppic"] = $pp_datas["pic_rpath"];
        $loads["pdacc_uppisdf"] = $pp_datas["pic_isdf"];
            
        /***********************************************************************************/
        
        //On crée pdacc_href
        $loads["pdacc_href"] = "/".$account["pdacc_upsd"];
        
//        var_dump(__FUNCTION__,__LINE__,$loads);
//        exit();
        
        $this->init_properties($loads);
        $this->is_instance_load = TRUE;
        return $loads;
    }

    public function on_alter_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie la présence des données obligatoires : ["accid","acc_gid","acc_eid","acc_upsd","acc_ufn","acc_uppicid","acc_uppic","acc_coverpicid","acc_coverpic","acc_ucityid","acc_ucity_fn","acc_nocity","acc_ucnid","acc_ucn_fn","acc_udl","acc_datecrea","acc_datecrea_tstamp","acc_capital","acc_todelete"]
        $com  = array_intersect( array_keys($args), $this->needed_to_update_prop_keys);
        
        if ( count($com) != count($this->needed_to_update_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_update_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    
                    if ( ( $k == "acc_todelete" || $k == "acc_capital" ) && ( $v == 0 || $v == "0") )
                        continue;
                    else if ( $k == "acc_nocity" && ( !isset($v) || $v == 0 || $v == "0") ) {
                        continue;
                    }
                    else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    }
                } 
            }
            
        }
        
        //On crée les définitions des variables
        foreach ($args as $k => $v) {
            $$k = $v;
        }
        
        //On vérifie si un Compte ayant le même identifiant accid ou acc_eid existe bel et bien 
        if ( !$this->exists($acc_eid,TRUE) || !$this->exists_with_id($accid,TRUE) )
        {
            return "__ERR_VOL_USER_GONE";
        }
        
        //On met à jour l'occurence
        $QO = new QUERY("qryl4pdaccn11");
        //On voit mieux comme ça :)
        $params = array(
            ":accid"                => intval($accid), //OBLIGATOIRE : Il faut s'assurer que le type est bien un INT.
            ":acc_gid"              => $acc_gid, 
            ":acc_upsd"             => $acc_upsd, 
            ":acc_ufn"              => $acc_ufn, 
            ":acc_uppicid"          => $acc_uppicid,
            ":acc_uppic"            => $acc_uppic,
            ":acc_ucityid"          => $acc_ucityid, 
            ":acc_ucity_fn"         => $acc_ucity_fn, 
            ":acc_nocity"           => $acc_nocity, 
            ":acc_ucnid"            => $acc_ucnid, 
            ":acc_ucn_fn"           => $acc_ucn_fn, 
            ":acc_udl"              => $acc_udl, 
            ":acc_datecrea"         => $acc_datecrea, 
            ":acc_datecrea_tstamp"  => $acc_datecrea_tstamp, 
            ":acc_capital"          => $acc_capital);
        $QO->execute($params);
        
        return $this->load_entity($args);
    }

    public function on_create_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie la présence des données obligatoires : ["accid","acc_gid","acc_eid","acc_upsd","acc_ufn","acc_ugdr","acc_ucityid","acc_ucity_fn","acc_nocity","acc_ucnid","acc_ucn_fn","acc_udl","acc_datecrea","acc_datecrea_tstamp","acc_capital"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    
                    if ( ( $k == "acc_todelete" || $k == "acc_capital" ) && ( $v == 0 || $v == "0") ) {
                        continue;
                    } else if ( $k == "acc_nocity" ) {
                        continue;
                    } else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    }
                } 
            }
        }
        
        //On crée les définitions des variables
        foreach ($args as $k => $v) {
            $$k = $v;
        }
        
        //On vérifie si un Compte ayant le même identifiant accid ou acc_eid n'existe pas déjà 
        if ( $this->exists($acc_eid, TRUE) || $this->exists_with_id($accid, TRUE) )
        {
            return "__ERR_VOL_ACC_EXISTS";
        }
        
        //On crée l'occurrence
        $this->write_new_in_database($args);
        
        //On insère les données dans les tables SRH
        $this->FeedSrhTables($args);
        
        return $this->load_entity($args);
        
    }

    public function on_delete_entity($args) { }

    public function on_read_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $pdacc_eid = $pdaccid = NULL;
        if (! ( !empty($args) && is_array($args) && key_exists("acc_eid", $args) && !empty($args["acc_eid"]) ) )
        {
            if ( key_exists("accid", $args) && !empty($args["accid"]) && !is_array($args["accid"]) ) {
                $pdaccid = $args["accid"];
            } else if ( !empty($this->pdaccid) ) {
                $pdaccid = $this->pdaccid;
            } else {
                if ( empty($this->pdacc_eid) ) {
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__, TRUE);
                } else { 
                    $pdacc_eid = $this->pdacc_eid;
                }
            }
        } else { 
            $pdacc_eid = $args["acc_eid"];
        }
        
        if ( !isset($pdacc_eid) || empty($pdacc_eid) ) {
            $r = $this->onread_get_acceid_from_accid($pdaccid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                return $r;
            }
            
            $args["acc_eid"] = $r;
        }
        
        $loads = $this->load_entity($args, $std_err_enabled);
        
        return $loads;
    }

    protected function write_new_in_database($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On crée les définitions des variables
        foreach ($args as $k => $v) {
            $$k = $v;
        }
        
        //["accid","acc_gid","acc_eid","acc_upsd","acc_ufn","acc_ugdr","acc_ucityid","acc_ucity_fn","acc_nocity","acc_ucnid","acc_ucn_fn","acc_udl","acc_datecrea","acc_datecrea_tstamp","acc_capital"]
        $QO = new QUERY("qryl4pdaccn3");
        //On voit mieux comme ça :)
        $params = array(
            ":accid"                => intval($accid), //OBLIGATOIRE : Il faut s'assurer que le type est bien un INT.
            ":acc_gid"              => $acc_gid, 
            ":acc_eid"              => $acc_eid, 
            ":acc_upsd"             => $acc_upsd, 
            ":acc_ufn"              => $acc_ufn, 
            ":acc_ugdr"             => $acc_ugdr,
//            ":acc_uppicid" => $acc_uppicid,
//            ":acc_uppic" => $acc_uppic,
//            ":acc_coverpicid" => $acc_coverpicid,
//            ":acc_coverpic" => $acc_coverpic,
            ":acc_ucityid"          => $acc_ucityid, 
            ":acc_ucity_fn"         => $acc_ucity_fn, 
            ":acc_nocity"           => $acc_nocity, 
            ":acc_ucnid"            => $acc_ucnid, 
            ":acc_ucn_fn"           => $acc_ucn_fn, 
            ":acc_udl"              => $acc_udl, 
            ":acc_datecrea"         => $acc_datecrea, 
            ":acc_datecrea_tstamp"  => $acc_datecrea_tstamp, 
            ":acc_capital"          => $acc_capital);
        $QO->execute($params);
        
    }
    
    /**************************************************************************************************************************/
    /************************************************** ON CRETAE (start)  ****************************************************/
    private function FeedSrhTables($args) {
        /*
         * Permet d'insérer une ou plusieurs occurrence dans les tables d'archivage de type SRH.
         */
        
        $ppic = $this->_DFLT_PICS[$args["acc_ugdr"]];
        
        $QO = new QUERY("qryl4insn15");
        $params = array(":uid" => intval($args["accid"]), ":ueid" => $args["acc_eid"], ":ufn" => $args["acc_ufn"], ":upsd" => $args["acc_upsd"], ":uppic" => $ppic);
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    private function oncreate_treat_msg ($s, &$usertags = NULL, &$kws = NULL, $rgx = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        /*
         * ETAPE :
         *      On vérifie que le texte respecte le format attendu
         */
        if ( $rgx && is_string($s) && !preg_match($rgx,$s) ) {
            return "__ERR_VOL_MSM_FRMT";
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
    
    /*********************************************** ON CREATE (end) **********************************************************/
    /**************************************************************************************************************************/
    
        
    /**************************************************************************************************************************/
    /************************************************** ON READ (start)  ******************************************************/
    
    /*** ACCOUNTS EXTRAS ****/
    
     private function onread_get_acceid_from_accid($accid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists_with_id($accid,TRUE);
        return  (! $r ) ? "__ERR_VOL_USER_GONE" : $r["pdacc_eid"];
    }
    
    public function onread_get_accid_from_acceid($acc_eid) {
//    private function onread_get_accid_from_acceid($acc_eid) { //[DEPUIS 29-04-15]
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists($acc_eid);
        return  (! $r ) ? "__ERR_VOL_USER_GONE" : $r["pdaccid"];
    }
    
    
    /**** ARTICLES SCOPE ****/
    
    private function onread_get_my_articles_nb ($accid) {
        /*
         * Permet de récupérer le nombre d'ARTICLES totales appartenant à l'utilisateur.
         * Il existe d'autres fonctions qui permettent de charger les ARTCILES d'un utilisateur. Elles le font par groupe de x.
         * Celle ci ne récupère que le nombre. Cela évite de charger tous les ARTICLES d'un utilisateur
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4pdaccn6neo0815001");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
        return $datas[0]["artnb"];
    }
    
    public function onread_load_my_first_articles ($accid, $lmt = NULL, $_OPTIONS = NULL) {
        /*
         * Permet de charger les premiers Articles dans la limite ficée liées à l'Utilisateur spécifié en paramètre.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $accid);
        
        /*
         * [DEPUIS 12-08-16]
         *      Optimisation pour des soucis de PERF
         */
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $exists = $this->exists_with_id($accid,TRUE);
            if (! $exists ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        /*
         * ETAPE :
         * TODO : On vérifie si l'option passée est prévue dans le tableau des Options
         */
        
        /*
         * [DEPUIS 30-05-15] @BOR
         * On choisit la bonne limite
         * 
         * [NOTE 30-05-15] @BOR 
         *      On prend en compte le nombre que l'on veut au cas où un des type n'admet aucun Articles.
         *      Dans ce cas, on sera sur qu'on au aura toujours le bon nombre dans le cas d'un défaut.
         *      C'est à CALLER de ne retenir que le bon nombre d'Articles.
         *      De plus on ajoute +1 au cas où on voudrait retirer un Article en particulier.
         * 
         * [DEPUIS 18-06-16]
         *      On prend en compte le cas échéant la LIMIT passée par CALLER
         */
        if ( $lmt && is_int($lmt) && $lmt > 0 ) {
            $limit = $lmt;
        } else {
            $limit = (! in_array("FKSA_SAMPLE",$_OPTIONS) ) ? $this->_LIMIT_FIRST_ARTS : $this->_LIMIT_FKSASMPL_ARTS;
        }
        
        //On récupère les Articles IML
//        $QO1 = new QUERY("qryl4pdaccn8");
//        $QO1 = new QUERY("qryl4pdaccn8neo0815001");
        if ( $_OPTIONS && key_exists("ARTICLE_IML_FILTER", $_OPTIONS) && isset($_OPTIONS["ARTICLE_IML_FILTER"]) ) {
            $QO1 = ( $_OPTIONS["ARTICLE_IML_FILTER"] === "NOT_IML_FRD" ) 
                ? new QUERY("qryl4pdaccn8neo0616001") : new QUERY("qryl4pdaccn8neo0815001");
            $params1 = array( 
                ':accid'    => $accid,
                ':accid2'   => $accid, 
                ':limit'    => $limit 
            );
        } else if ( $_OPTIONS && key_exists("CUID", $_OPTIONS) && isset($_OPTIONS["CUID"]) ) {
            $cuid = $_OPTIONS["CUID"];
            
            $QO1 = new QUERY("qryl4pdaccn8neo0616002");
            $params1 = array( 
                ':accid'    => $accid,
                ':accid2'   => $accid, 
                ':cuid'     => $cuid, 
                ':cuid1'    => $cuid, 
                ':cuid2'    => $cuid, 
                ':cuid3'    => $cuid, 
                ':limit'    => $limit 
            );
        } else {
            $QO1 = new QUERY("qryl4pdaccn8neo0815001");
            $params1 = array( 
                ':accid'    => $accid,
                ':accid2'   => $accid, 
                ':limit'    => $limit 
            );
        }

        $datas_iml = $QO1->execute($params1);
//        var_dump(__LINE__,__FILE__,$datas_iml);
//        exit();
        
        //On récupère les Articles de type ITR
        $QO2 = new QUERY("qryl4pdaccn9neo0815001");
//        $QO2 = new QUERY("qryl4pdaccn9");
        $params2 = array( 
            ':accid' => $accid, 
            ':limit' => $limit 
        );
        $datas_itr = $QO2->execute($params2);
        
//        var_dump(__FUNCTION__, __LINE__,$datas_iml);
//        var_dump(__FUNCTION__, __LINE__,$datas_itr);  
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$datas_iml,$datas_itr],'v_d');
//        exit();
        
        if ( !$datas_iml && !$datas_itr ) {
            return NULL;
        } else {
            /*
             * On a besoin que ces Articles soit mis en forme selon un modèle. 
             * Pour cela on va faire appel aux Entity ARTICLE et ARTICLE_TR.
             */
           
            $articles_iml = $articles_itr = [];
            
            //On traite les Articles IML
            if ( $datas_iml ) {
                foreach ($datas_iml as $article) {
                    $artid = $article["artid"];
                    $art_eid = $article["art_eid"];
                    
                    //... Par mesure de précaution, on s'assure qu'il n'ya pas de doublons (deux même Articles )
                    if ( !key_exists($artid, $articles_iml) && !key_exists($artid, $articles_itr) ) {
                        $ART = new ARTICLE();
                        
                        if ( in_array("VM_ART",$_OPTIONS) ) {
                            $r__ = $ART->onread_archive_iml(["art_eid" => $art_eid]);
                            if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                                continue;
                            }
                            $load_iml = $r__;
                            
//                            $load_iml = $ART->onread_archive_iml(["art_eid" => $art_eid]);
                        } else {
                            $r__ = $ART->on_read_entity(["art_eid" => $art_eid]);
                            if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                                continue;
                            }
                            $load_iml = $r__;
                            
//                            $load_iml = $ART->on_read_entity(["art_eid" => $art_eid]);
                        }
                        
                        $articles_iml[$artid] = $load_iml;
                    }
                }
            }
            
//            var_dump(__LINE__,__FILE__,$articles_iml);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas_iml,'v_d');
//            exit();
            
            //On traite les Articles ITR
            if ( $datas_itr ) {
                foreach ($datas_itr as $article) {
                    $artid = $article["artid"];
                    $art_eid = $article["art_eid"];
                    
                    //... Par mesure de précautions, on s'assure qu'il n'ya pas de doublons (deux même Articles )
                    if ( !key_exists($artid, $articles_iml) && !key_exists($artid, $articles_itr) ) {
                        $ART_TR = new ARTICLE_TR();
                        if ( in_array("VM_ART",$_OPTIONS) ) {
                            $r__ = $ART_TR->onread_archive_itr(["art_eid" => $art_eid]); 
                            if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                            if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 04-08-15] @BOR
//                                var_dump(__LINE__,__FILE__,$artid,$art_eid,$r__);
                                continue;
                            }
                            $load_itr = $r__;
                            
//                            $load_itr = $ART_TR->onread_archive_itr(["art_eid" => $art_eid]);
                        } else {
                            $r__ = $ART_TR->on_read(["art_eid" => $art_eid]);
                            if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                            if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 04-08-15] @BOR
//                                var_dump(__LINE__,__FILE__,$artid,$art_eid,$r__); 
                                continue;
                            }
                            $load_itr = $r__;
                            
//                            $load_itr = $ART_TR->on_read(["art_eid" => $art_eid]);
                        }
                        
                        $articles_itr[$artid] = $load_itr;
                    }
                }
            }
            
//            var_dump(__FUNCTION__, __LINE__,$articles_iml); 
//            var_dump(__FUNCTION__, __LINE__,$articles_itr);  
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$articles_iml,$articles_itr],'v_d');
//            exit();
            $stack = [
                "iml" => $articles_iml,
                "itr" => $articles_itr
            ];
             
            return $stack;
        }
    }
    
    public function onread_load_more_itr_articles ($accid, $is_predate, $laeid = NULL, $_OPTIONS = NULL) {
        //laeid : L'identifiant externe du dernier article qui sert de référence pour récupérer les autres Articles.
        $args = [$accid, $is_predate];
        /*
         * Permet de récupérer de nouveaux les Articles appartenant à Compte.
         * 
         * La récupération des Articles dépend de x données :
         *  (1) Le compte qui servira de référence pour la récupération des informations
         *  (2) S'agit-il d'Articles de type TRARTICLE ou des ARTICLES standards. 
         *  (3) L'identifiant du "dernier" Article. Il peut s'agir de l'Article le plus récent ou le plus anciens dans la liste des Articles au niveau du FE
         *  (4) S'agit-il de récupérer les données PREDATE (Anterieurs) OU ...
         *      ... S'agit-il de récupérer les données NEWER (Les nouveaux Articles qui existent au niveau du serveur mais pas au niveau du FE.
         *  
         * Dans le cas où l'identifiant du dernier Article n'est pas fourni, on considère qu'il s'agit de facto du cas de NEWER.
         * Dans ce dernier cas, on fait appel à first_articles. La plupart du temps, ce cas intervient lorsqu'au niveau du FrontEnd, il n'y a pas d'Aricles.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        if ( !isset($laeid) | empty($laeid) ) {
            return $this->onread_load_newer_articles($accid, TRUE, NULL, $_OPTIONS);
        } else if ( isset($laeid) && !empty($laeid) && !$is_predate ) {
            //On récupère les Articles les plus récents
            return $this->onread_load_newer_articles($accid, TRUE, $laeid, $_OPTIONS);
        } else if ( isset($laeid) && !empty($laeid) && $is_predate ) {
            //On récupère les Articles les plus anciens
            return $this->onread_load_predate_articles($accid, TRUE, $laeid, $_OPTIONS);
        }
        
    }
    
    public function onread_load_more_iml_articles ($accid, $is_predate, $laeid = NULL, $_OPTIONS = NULL) {
        //laeid : L'identifiant externe du dernier article qui sert de référence pour récupérer les autres Articles.
        $args = [$accid, $is_predate];
        /*
         * Permet de récupérer de nouveaux les Articles appartenant à Compte.
         * 
         * La récupération des Articles dépend de x données :
         *  (1) Le compte qui servira de référence pour la récupération des informations
         *  (2) S'agit-il d'Articles de type TRARTICLE ou des ARTICLES standards. 
         *  (3) L'identifiant du "dernier" Article. Il peut s'agir de l'Article le plus récent ou le plus anciens dans la liste des Articles au niveau du FE
         *  (4) S'agit-il de récupérer les données PREDATE (Anterieurs) OU ...
         *      ... S'agit-il de récupérer les données NEWER (Les nouveaux Articles qui existent au niveau du serveur mais pas au niveau du FE.
         *  
         * Dans le cas où l'identifiant du dernier Article n'est pas fourni, on considère qu'il s'agit de facto du cas de NEWER.
         * Dans ce dernier cas, on fait appel à first_articles. La plupart du temps, ce cas intervient lorsqu'au niveau du FrontEnd, il n'y a pas d'Aricles.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        if ( !isset($laeid) | empty($laeid) ) {
            return $this->onread_load_newer_articles($accid, FALSE);
        } else if ( isset($laeid) && !empty($laeid) && !$is_predate ) {
            //On récupère les Articles les plus récents
            return $this->onread_load_newer_articles($accid, FALSE, $laeid);
        } else if ( isset($laeid) && !empty($laeid) && $is_predate ) {
            //On récupère les Articles les plus anciens
            return $this->onread_load_predate_articles($accid, FALSE, $laeid, $_OPTIONS);
        }
        
    }
    
    private function onread_load_predate_articles($accid, $istr, $laeid, $_OPTIONS = NULL) {
        /*
         * Permet de récupérer les données sur les Articles en ne prenant que les plus récents.
         * De plus, on se base sur un Article qui nous servira de pivot.
         * 
         * Dans ce cas, laeid ne peut pas être NULL. En effet, s'il n'y a pas d'Articles PIVOT alors il faut se diriger vers NEWER.
         * 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $articles = NULL;
        if ( $istr === TRUE ) {
            //CQVD : Récupérer les Articles dont la date de création est anterieure à celle de l'Article pivot dans la limit définie. Seulement les Articles ITR.
            
            //RAPPEL : $this->_LIMIT_PD_ARTS = 10;
            
            //On read l'Article reférencé par laeid
            $LART = new ARTICLE();
            $LATB = $LART->exists($laeid); 
            if ( !$LATB ) {
                return "__ERR_VOL_REF_ART_GONE";
            }
            
            //On récupère la liste des Articles. On ne récupère que les identifiants externes.
            $QO = new QUERY("qryl4pdaccn17neo0815001");
//            $QO = new QUERY("qryl4pdaccn17"); //[NOTE 05-08-15] @BOR
            $params = array( 
                ':accid'        => $accid, 
                ':last_artid'   => $laeid, 
                ':last_time'    => $LATB["art_cdate_tstamp"], 
                ':limit'        => $this->_LIMIT_PD_ARTS 
            );
            $eids = $QO->execute($params);
            
            foreach ($eids as $k => $v) {
                $eid = $v["art_eid"];
                
                $A = new ARTICLE_TR();
//                $articles[$k] = $A->on_read(["art_eid"=>$eid]); //[28-04-15]
                if ( in_array("VM_ART",$_OPTIONS) ) {
                   /*
                    * [DEPUIS 07-06-15] @BOR
                    */
                    $r__ = $A->onread_archive_itr(["art_eid" => $eid]);
                    if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                    if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 04-08-15] @BOR
                        continue;
                    }
                    $articles[$k] = $r__;
                            
//                    $articles[$k] = $A->onread_archive_itr(["art_eid" => $eid]);
                } else {
                   /*
                    * [DEPUIS 07-06-15] @BOR
                    */
                    $r__ = $A->on_read(["art_eid"=>$eid]);
                    if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                    if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 04-08-15] @BOR
                        continue;
                    }
                    $articles[$k] = $r__;
                            
//                    $articles[$k] = $A->on_read(["art_eid"=>$eid]);
                }
                
                //[NOTE 04-09-14] Au départ je voulais supprimer les commentaires mais je me suis dit que j'allais laisser CALLER décidé. Aussi, la méthode est polyvalente.
            }
        } else if ( $istr === FALSE ) {
            //CQVD : Récupérer les Articles dont la date de création est anterieure à celle de l'Article pivot dans la limit définie. Seulement les Articles IML.
            
            //RAPPEL : $this->_LIMIT_PD_ARTS = 10;
            
            //On read l'Article reférencé par laeid
            $LART = new ARTICLE();
            $LATB = $LART->exists($laeid); 
            
            if ( !$LATB ) {
                return "__ERR_VOL_REF_ART_GONE";
            }
            
            //On récupère la liste des Articles. On ne récupère que les identifiants externes.
            $QO = new QUERY("qryl4pdaccn18neo0815001");
//            $QO = new QUERY("qryl4pdaccn18"); //[DEPUIS 04-08-15] @BOR
            $params = array( 
                ':accid'        => $accid, 
                ':last_artid'   => $laeid, 
                ':last_time'    => $LATB["art_cdate_tstamp"], 
                ':limit'        => $this->_LIMIT_PD_ARTS 
            );
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$params],'v_d');
            $eids = $QO->execute($params);
             
            if ( $eids ) {
                foreach ($eids as $k => $v) {
                    $eid = $v["art_eid"];

                    $A = new ARTICLE();
//                    $articles[$k] = $A->on_read_entity(["art_eid"=>$eid]); //[28-04-15]
                    if ( in_array("VM_ART",$_OPTIONS) ) {
                       /*
                        * [DEPUIS 07-06-15] @BOR
                        */
                        $r__ = $A->onread_archive_iml(["art_eid" => $eid]);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $articles[$k] = $r__;
                            
//                        $articles[$k] = $A->onread_archive_iml(["art_eid" => $eid]);
                    } else {
                       /*
                        * [DEPUIS 07-06-15] @BOR
                        */
                        $r__ = $A->on_read(["art_eid"=>$eid]);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $articles[$k] = $r__;
                        
//                        $articles[$k] = $A->on_read(["art_eid"=>$eid]);
                    }
                
                    //[NOTE 04-09-14] Au départ je voulais supprimer les commentaires mais je me suis dit que j'allais laisser CALLER décidé. Aussi, la méthode est polyvalente.
                }
            } else {
                return;
            }
            
        }
        
        return $articles;
    }
    
    
    private function onread_load_newer_articles($accid, $istr, $laeid = NULL, $_OPTIONS = NULL) {
        /*
         * Permet de récupérer les données sur les Articles en ne prenant que les plus récents.
         * De plus, on se base sur un Article qui nous servira de pivot.
         * 
         * Dans ce cas, laeid ne peut pas être NULL. En effet, s'il n'y a pas d'Articles PIVOT alors il faut se diriger vers NEWER.
         * 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$accid,$istr]);
        
        $articles = NULL;
        
        //Check si laeid
        if (! isset($laeid) ) {
            if ( $istr === TRUE ) {
                //CQVD : Récupérer les Articles les plus récents. Seulement les Articles ITR.
            
                //RAPPEL : $this->_LIMIT_NWR_ARTS = 10;

                $QO2 = new QUERY("qryl4pdaccn9neo0815001");
//                $QO2 = new QUERY("qryl4pdaccn9"); //[DEPUIS 04-08-15] @BOR
                $params2 = array( 
                    ':accid' => $accid, 
                    ':limit' => $this->_LIMIT_NWR_ARTS 
                );
                $itrs = $QO2->execute($params2);
                
                //Por chaque eid on "read" l'Article
                foreach ($itrs as $k => $v) {
                    $eid = $v["art_eid"];

                    $A = new ARTICLE_TR();
//                    $articles[$k] = $A->on_read(["art_eid"=>$eid]); //[DEPUIS 29-04-15]
                    if ( in_array("VM_ART",$_OPTIONS) ) {
                        /*
                         * [DEPUIS 07-06-15] @BOR
                         */
                        $r__ = $A->onread_archive_itr(["art_eid" => $eid]);
                        if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                        if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 04-08-15] @BOR
                            continue;
                        }
                        $articles[$k] = $r__;
                            
//                        $articles[$k] = $A->onread_archive_itr(["art_eid" => $eid]);
                    } else {
                        /*
                         * [DEPUIS 07-06-15] @BOR
                         */
                        $r__ = $A->on_read(["art_eid" => $eid]);
                        if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                        if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 04-08-15] @BOR
                            continue;
                        }
                        $articles[$k] = $r__;
                        
//                        $articles[$k] = $A->on_read(["art_eid" => $eid]);
                    }

                    //[NOTE 04-09-14] Au départ je voulais supprimer les commentaires mais je me suis dit que j'allais laisser CALLER décidé. Aussi, la méthode est polyvalente.
                }
                
            } 
            else {
                //CQVD : Récupérer les Articles les plus récents. Seulement les Articles IML.
            
                //RAPPEL : $this->_LIMIT_NWR_ARTS = 10;
                
                $QO1 = new QUERY("qryl4pdaccn8neo0815001");
//                $QO1 = new QUERY("qryl4pdaccn8"); //[DEPUIS 04-08-15] @BOR
                $params1 = array( 
                    ':accid'    => $accid,
                    ':accid2'   => $accid, 
                    ':limit'    => $this->_LIMIT_NWR_ARTS 
                );
                $imls = $QO1->execute($params1);
                
                //Por chaque eid on "read" l'Article
                foreach ($imls as $k => $v) {
                    $eid = $v["art_eid"];

                    $A = new ARTICLE();
//                    $articles[$k] = $A->on_read_entity(["art_eid"=>$eid]); //[DEPUIS 29-04-15]
                    if ( in_array("VM_ART",$_OPTIONS) ) {
                        /*
                         * [DEPUIS 07-06-15] @BOR
                         */
                        $r__ = $A->onread_archive_iml(["art_eid" => $eid]);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $articles[$k] = $r__;
                        
//                        $articles[$k] = $A->onread_archive_iml(["art_eid" => $eid]);
                    } else {
                        /*
                         * [DEPUIS 07-06-15] @BOR
                         */
                        $r__ = $A->on_read_entity(["art_eid" => $eid]);
                        if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                            continue;
                        }
                        $articles[$k] = $r__;
                        
//                        $articles[$k] = $A->on_read_entity(["art_eid" => $eid]);
                    }

                    //[NOTE 04-09-14] Au départ je voulais supprimer les commentaires mais je me suis dit que j'allais laisser CALLER décidé. Aussi, la méthode est polyvalente.
                }
            }
            
            return $articles;
        }
            
        if ( $istr === TRUE ) {
            //CQVD : Récupérer les Articles dont la date de création est ULTERIEURE à celle de l'Article pivot dans la limit définie. Seulement les Articles ITR.
            
            //RAPPEL : $this->_LIMIT_PD_ARTS = 10;
            
            //On read l'Article reférencé par laeid
            $LART = new ARTICLE();
            $LATB = $LART->exists($laeid); 
            
            if ( !$LATB ) {
                return "__ERR_VOL_REF_ART_GONE";
            }
            
            //On récupère la liste des Articles. On ne récupère que les identifiants externes.
            $QO = new QUERY("qryl4pdaccn19neo0815001");
//            $QO = new QUERY("qryl4pdaccn19"); //[DEPUIS 05-05-15] @BOR
            $params = array( 
                ':accid'        => $accid, 
                ':last_artid'   => $laeid, 
                ':last_time'    => $LATB["art_cdate_tstamp"], 
                ':limit'        => $this->_LIMIT_NWR_ARTS 
            );
            $eids = $QO->execute($params);
            
            //Por chaque eid on "read" l'Article
            foreach ($eids as $k => $v) {
                $eid = $v["art_eid"];
                
                $A = new ARTICLE_TR();
//                $articles[$k] = $A->on_read(["art_eid"=>$eid]); //[DEPUIS 29-04-15]
                if ( in_array("VM_ART",$_OPTIONS) ) {
                   /*
                    * [DEPUIS 07-06-15] @BOR
                    */
                    $r__ = $A->onread_archive_itr(["art_eid" => $eid]);
                    if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                    if ( $r__ === "__ERR_VOL_ART_GONE" ) { //[DEPUIS 05-05-15] @BOR
                        continue;
                    }
                    $articles[$k] = $r__;
                    
//                    $articles[$k] = $A->onread_archive_itr(["art_eid" => $eid]);
                } else {
                   /*
                    * [DEPUIS 07-06-15] @BOR
                    */
                    $r__ = $A->on_read(["art_eid" => $eid]);
                    if ( in_array($r__,["__ERR_VOL_ART_GONE","__ERR_VOL_TRD_GONE"]) ) {
//                    if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                        continue;
                    }
                    $articles[$k] = $r__;
                    
//                    $articles[$k] = $A->on_read(["art_eid" => $eid]);
                }
                
                //[NOTE 04-09-14] Au départ je voulais supprimer les commentaires mais je me suis dit que j'allais laisser CALLER décidé. Aussi, la méthode est polyvalente.
            }
        } else if ( $istr === FALSE ) {
            //CQVD : Récupérer les Articles dont la date de création est ULTERIEURE à celle de l'Article pivot dans la limit définie. Seulement les Articles IML.
            
            //RAPPEL : $this->_LIMIT_PD_ARTS = 10;
            
            //On read l'Article reférencé par laeid
            $LART = new ARTICLE();
            $LATB = $LART->exists($laeid); 
            
            if ( !$LATB ) {
                return "__ERR_VOL_REF_ART_GONE";
            }
            
            //On récupère la liste des Articles. On ne récupère que les identifiants externes.
            $QO = new QUERY("qryl4pdaccn20neo0815001");
//            $QO = new QUERY("qryl4pdaccn20"); [DEPUIS 05-08-15] @BOR
            $params = array( 
                ':accid'        => $accid, 
                ':last_artid'   => $laeid, 
                ':last_time'    => $LATB["art_cdate_tstamp"], 
                ':limit'        => $this->_LIMIT_NWR_ARTS 
            );
            $eids = $QO->execute($params);
            
            //Pour chaque eid on "read" l'Article
            foreach ($eids as $k => $v) {
                $eid = $v["art_eid"];
                
                $A = new ARTICLE();
//                $articles[$k] = $A->on_read_entity(["art_eid"=>$eid]); //[DEPUIS 29-04-15]
                if ( in_array("VM_ART",$_OPTIONS) ) {
                   /*
                    * [DEPUIS 07-06-15] @BOR
                    */
                    
                    $r__ = $A->onread_archive_iml(["art_eid" => $eid]);
                    if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                        continue;
                    }
                    $articles[$k] = $r__;
                    
//                    $articles[$k] = $A->onread_archive_iml(["art_eid" => $eid]);
                } else {
                   /*
                    * [DEPUIS 07-06-15] @BOR
                    */
                    $r__ = $A->on_read_entity(["art_eid" => $eid]);
                    if ( $r__ === "__ERR_VOL_ART_GONE" ) {
                        continue;
                    }
                    $articles[$k] = $r__;
                    
//                    $articles[$k] = $A->on_read_entity(["art_eid" => $eid]);
//                    $articles[$k] = $A->on_read(["art_eid" => $eid]); //[DEPUIS 01-06-15]
                }
                 
                //[NOTE 04-09-14] Au départ je voulais supprimer les commentaires mais je me suis dit que j'allais laisser CALLER décidé. Aussi, la méthode est polyvalente.
            }
        }
        
        return $articles;
    }
    
    /****** TREND SCOPE ******/
    
    public function onread_get_mytrds_count ($uid,$_OPTIONS = NULL) { 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $utb = $this->exists_with_id($uid,TRUE);
            if (! $utb ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $QO = new QUERY("qryl4pdaccn4_cn");
        $params = array( ':accid' => intval($uid) );
        $datas = $QO->execute($params);
        
        return  ( $datas ) ? $datas[0]["cn"] : 0;
    }
    
    public function onread_acquiere_my_trends_datas ($accid, $_OPTIONS = NULL) {
        /*
         * Permet de récupérer les données relatives à une Tendance. 
         * La méthode est utile pour :
         *  (1) Connaitre le nombre de Tendances que détient l'utilisateur acitf
         *  (2) Lister ses Tendances dans BRAIN
         *  (3) Lister les Tendances FIRST dans PGMYTRS
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $accid);
        
        $QO = new QUERY("qryl4pdaccn4");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return NULL;
        } else if ( $datas && count($datas) ) {
            $my_trends = NULL;
            /* 
             * On récupère pour chaque Tendance, une Occurence de TREND.
             * L'occurrence contient des donnée plus intéressantes que celles uniquement présentes dans la table TRENDS
             */
            foreach ($datas as $t) {
                $args = [
                    "trd_eid" => $t["trd_eid"],
//                    "urqid" => "ANY" //TODO : A rermplacer par l'URQ de redirection pour la page TPRG
                ];
                
                $TR = new TREND();
                $t__ = $TR->on_read_entity($args);
                
                /*
                 * [DEPUIS 04-08-15] @BOR
                 */
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__) ) {
                    if ( in_array($t__,["__ERR_VOL_TRD_GONE","__ERR_VOL_USER_GONE"]) ) {
                        continue;
                    }
                } else {
                    $my_trends[] = $t__;
                }
            }
            
            return $my_trends;
        } return NULL;
        
    }
    
    public function onread_get_myfoltrds_count ($uid,$_OPTIONS = NULL) { 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $utb = $this->exists_with_id($uid,TRUE);
            if (! $utb ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $QO = new QUERY("qryl4pdaccn5_cn");
        $params = array( ':accid' => intval($uid) );
        $datas = $QO->execute($params);
        
        return  ( $datas ) ? $datas[0]["cn"] : 0;
    }
    
    public function onread_acquiere_following_trends_datas ($accid) {
        /*
         * Permet de récupérer les données relatives aux Tendances suivies par l'utilisateur actif. 
         * La méthode est utile pour :
         *  (1) Lister ses Tendances suivies dans BRAIN
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4pdaccn5");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return NULL;
        } else if ( $datas && count($datas) ) {
            $folg_trends = NULL;
            /* 
             * On récupère pour chaque Tendance, une Occurence de TREND.
             * L'occurrence contient des donnée plus intéressantes que celles uniquement présentes dans la table TRENDS
             */
            foreach ($datas as $t) {
                $TR = new TREND();
                $treid = $TR->get_trdeid_from_trid($t["trabo_trid"]);
                
                $args = [
                    "trd_eid" => $treid,
//                    "urqid" => "ANY" //TODO : A rermplacer par l'URQ de redirection pour la page TPRG
                ];
                
//                $folg_trends[] = $TR->on_read_entity($args);
                $t__ = $TR->on_read_entity($args);
//                var_dump(__FUNCTION__,__LINE__,$t__);
                /*
                 * [DEPUIS 04-08-15] @BOR
                 */
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $t__) ) {
                    if ( in_array($t__,["__ERR_VOL_TRD_GONE","__ERR_VOL_USER_GONE"]) ) {
                        continue;
                    }
                } else {
                    $folg_trends[] = $t__;
                }
            }
            
            return $folg_trends;
        } return NULL;
    }
    
    public function onread_acquiere_cover_datas ($accid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $c_datas = NULL;
        
        $QO = new QUERY("qryl4acovn1");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
        /*
         * RAPPEL : 
         * [20-09-14] @author L.C.
         * A la version vb1, une seule ligne ne devrait être disponible. Aussi, on ne vérifie pas le nombre de row renvoyées.
         */
        
        if ( $datas ) {
            $datas = $datas[0];
            $c_datas = [
                "acovid"        => $datas["acovid"],
                "acov_pdpicid"  => $datas["acov_pdpicid"],
                "acov_rpath"    => $datas["pdpic_realpath"],
                "acov_width"    => $datas["acov_width"],
                "acov_height"   => $datas["acov_height"],
                "acov_top"      => $datas["acov_top"]
            ];
        }
        
        return $c_datas;
            
    }
    
    public function onread_acquiere_pp_datas($accid, $gender = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $accid);
        
        $pp_datas = NULL;
        
        $QO = new QUERY("qryl4pdppn1");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
//        var_dump(__LINE__,__FUNCTION__,$datas);
//        exit();
        /*
         * RAPPEL : 
         * [20-09-14] @author L.C.
         *      A la version vb1, une seule ligne ne devrait être disponible. Aussi, on ne vérifie pas le nombre de row renvoyées.
         */
        
        if ( $datas ) {
            $datas = $datas[0];
            $pp_datas = [
                "picid"     => $datas["pdpp_pdpicid"],
                "pic_rpath" => $datas["pdpic_realpath"],
                "pic_isdf"  => FALSE
            ];
        } else {
            $dpp_datas = $this->onread_acquiere_default_pp($gender);
            $pp_datas = [
                "picid"     => $dpp_datas["picid"],
                "pic_rpath" => $dpp_datas["pic_rpath"],
                "pic_isdf"  => TRUE
            ];
        }
        
        return $pp_datas;
    } 
    
    private function onread_acquiere_default_pp ($gender = NULL) {
        /*
         * Permet de récupérer l'image de profil par défaut pour un compte.
         * Cette image dépend entre autre du sexe de l'utilisateur.
         * 
         * [NOTE 22-09-14] @author L.C.
         * A la version vb1, l'image de profil sera toujours et encore celle de sexe masculin.
         * Cela pour des questions fonctionnelles. En effet, à la conseption on a pas prévu de récupérrer les données sur le sexe de l'utilisateur.
         * De plus, essayer de récupérer le sexe depuis la base Account ralentirait encore plus le processus d'acquisition des données.
         * Cette fonctionnalité peut être mise en place plus tard. Aussi, je préfère faire l'impasse dessus et me concentrer sur ce qui est essentiel.
         */
        
        $path = ( $gender ) ? $this->_DFLT_PICS[$gender] : $this->_DFLT_PICS["m"];
        $pp_datas = [
            "picid"     => "1",
            "pic_rpath" => $path
        ];
        
        return $pp_datas;
    }
    
    /******* EVALUATION ********/
    
    /**
     * Permet de récupérer les données à jour sur le Capital du compte dont l'identifiant est passé en paramètre.
     * 
     * @param type $uid L'identifiant du Compte concerné
     * @param type $_OPTIONS
     * @return int La capital du Compte
     */
    public function onread_updatedCapitalFor($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if (! in_array("AQAP", $_OPTIONS) ) {
            $utab = $this->exists_with_id($uid,TRUE);
            if (! $utab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $capital = NULL;
        
        //On récupère le capital
        $QO = new QUERY("qryl4pdaccn21neo0915001");
//        $QO = new QUERY("qryl4pdaccn21");
        $params = array( ':accid' => $uid );
        $datas = $QO->execute($params);
        
        if (!$datas ) {
            $capital = 0;
        } else {
            $capital = ( intval($datas[0]["capital"]) < 0 ) ? 0 : intval($datas[0]["capital"]);
        }
        
        return $capital;
    }
    
    
    /****** RELATION SCOPE ******/
    
    /**
     * Renvoie un tableau dont chaque ligne contient en même temps les données sur la Relation ainsi que ceux sur l'utilisateur suivi par celui passé en paramètre.
     * 
     * @param type $accid
     * @param type $std_err_enabled
     * @return array|NULL|String
     */
    public function onread_acquiere_my_following ($accid, $std_err_enabled = FALSE) {
        /*
         * Permet de récupérer les données sur les Utilisateur qui suivent l'Utilisateur passé en paramètre.
         * 
         * Pour cela on récupère les Relations de type s_folw et/ou d_folw actives au moment de la requete.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $exists = $this->exists_with_id($accid,TRUE);
        if ( !$exists && $std_err_enabled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__,TRUE);
        }
        else if ( !$exists && !$std_err_enabled ) 
        {
            return "__ERR_VOL_USER_GONE";
        }
        
        $QO = new QUERY("qryl4pdaccn13");
        $params = array( ':curr_user1' => intval($accid),':curr_user2' => intval($accid),':curr_user3' => intval($accid) );
        $folg_datas = $QO->execute($params);
        
        //[29-12-2014] Fait expres pour ne pas modifier la structure de l'Objet. Le but est de faure que les on_read soit indépendant de l'Objet.
        $PA = new PROD_ACC();
        if ( $folg_datas ) {
            /*
             * [29-08-14]
             * Gestion du cas 'D_FOLW'. 
             * Plusieurs choix était possibles, j'ai choisi celui de récupérer l'identifiant de l'utilisateur qui est suivi (Target) par celui donné en paramètre
             * ou si c'est le même (Target == User) je prends celui de l'Actor. Ensuite, je récupère les données du Compte sélectionné.
             * C'est la manière la moins casse-tête que j'ai trouvé. L'autre aurait impliqué beaucoup trop d'opérations qui auraient pu aboutir à des bugs ou un debug difficile.
             * 
             * On aurait pu le faire autrement, mais à cette date je choisi cette méthode !
             */
            $following_list = [];
            foreach ($folg_datas as $row) {
                
                //S'il est l'Acteur on récupère les données de Target. Ceci est le cas "normal".
                if ( intval($accid) === intval($row["tbrel_acc_actor"]) ) {
                    
                    //Il est toujours possible que l'utilisateur ciblé n'est plus de compte ou qu'il soit à todelete (1). On s'assure que ce n'est pas le cas.
                    /*
                     * [02-12-14] L.C.
                     * On utilise on_read car c'est le seul qui est capable de fournir toutes les informations necessaires.
                     * De plus, d'après les derniers tests, le temps d'acquisition des données n'est pas si élévé.
                     */
                    $r = $PA->on_read_entity(["accid" => $row["tbrel_acc_targ"]]);
//                    $r = $PA->exists_with_id($row["tbrel_acc_targ"]);
                    if ( !$r || intval($r["pdacc_todelete"]) !== 0 || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                        continue; //Ce qui veut dire qu'on skip
                    } else {
                        //On fusionne les deux tableaux
                        $following_list[] = array_merge($row, $r);
                    }
                    
                } else {
                    /*
                     * On récupère les données d'ACTOR. Ce cas est possible uniquement à cause du système 'D_FOLW'.
                     * On en effet, meme si dans la ligne actuelle acccid ne correspond pas à Actor, il l'a été avant que la Relation se transforme en 'D_FOLW'.
                     * Autrement dit, dans ce cas, ils sont à 'D_FOL' parce que accid a commencé à FOLW puis il a été FOLW
                     */
                    
                    //Il est toujours possible que l'utilisateur ciblé n'est plus de compte ou qu'il soit à todelete (1). On s'assure que ce n'est pas le cas.
                    /*
                     * [02-12-14] L.C.
                     * On utilise on_read car c'est le seul qui est capable de fournir toutes les informations necessaires.
                     * De plus, d'après les derniers tests, le temps d'acquisition des données n'est pas si élévé.
                     */
                    $r = $PA->on_read_entity(["accid" => $row["tbrel_acc_actor"]]);
//                    $r = $PA->exists_with_id($row["tbrel_acc_actor"]);
                    if ( !$r || intval($r["pdacc_todelete"]) !== 0 || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                        continue; //Ce qui veut dire qu'on skip
                    } else {
                        //On fusionne les deux tableaux
                        $following_list[] = array_merge($row, $r);
                    }
                    
                }   
            }
            
            return $following_list;
        }
        else {
            return;
        }
    }
    
    /**
     * Permet de récupérer le nombre de Comptes abonnées au Compte pivot dont l'identifiant est passé en paramètre.
     * 
     * @param string $uid L'identifiant interne de Compte pivot
     * @param array $_OPTIONS Les options disponibles pour le traitement
     * @return mixed[int|string] Le nombre de Comptes abonnés au compte passé en paramètre
     */
    public function onread_get_myfolrs_count ($uid,$_OPTIONS = NULL) { 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $utb = $this->exists_with_id($uid,TRUE);
            if (! $utb ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $QO = new QUERY("qryl4pdaccn12_cnver_wtdlo");
//        $QO = new QUERY("qryl4pdaccn12_cnver"); //[DEPUIS 11-09-15] @author BOR
        $params = array( ':curr_user1' => intval($uid),':curr_user2' => intval($uid),':curr_user3' => intval($uid) );
        $datas = $QO->execute($params);
        
        $cn = ( $datas ) ? $datas[0]["followers"] : 0;
//        var_dump(__FUNCTION__,__LINE__,$cn,$datas);
        
        return $cn;
    }
    
    /**
     * Permet de récupérer le nombre de Comptes suivis par le Compte pivot dont l'identifiant est passé en paramètre.
     * 
     * @param string $eid L'identifiant interne de Compte pivot
     * @param array $_OPTIONS Les options disponibles pour le traitement
     * @return mixed[int|string] Le nombre de Comptes abonnés au compte passé en paramètre
     */
    public function onread_get_myfolgs_count ($uid,$_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $utb = $this->exists_with_id($uid,TRUE);
            if (! $utb ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $QO = new QUERY("qryl4pdaccn13_cnver_wtdlo");
//        $QO = new QUERY("qryl4pdaccn13_cnver"); //[DEPUIS 11-09-15] @author BOR
        $params = array( ':curr_user1' => intval($uid),':curr_user2' => intval($uid),':curr_user3' => intval($uid) );
        $datas = $QO->execute($params);
        
        $cn = ( $datas ) ? $datas[0]["following"] : 0;
//        var_dump(__FUNCTION__,__LINE__,$cn,$datas); 
        
        return $cn;
    }
    
    /**
     * Renvoie un tableau dont chaque ligne contient en même temps les données sur la Relation ainsi que ceux sur l'utilisateur que suit celui passé en paramètre.
     * 
     * @param type $accid
     * @param type $std_err_enabled
     * @return array|NULL|String
     */
    public function onread_acquiere_my_followers ($accid, $std_err_enabled = FALSE) {
        /*
         * Permet de récupérer les données sur les Utilisateur que suit l'Utilisateur passé en paramètre.
         * 
         * Pour cela on récupère les Relations de type s_folw et/ou d_folw actives au moment de la requete.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $exists = $this->exists_with_id($accid,TRUE);
        if ( !$exists && $std_err_enabled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__,TRUE);
        }
        else if ( !$exists && !$std_err_enabled ) 
        {
            return "__ERR_VOL_USER_GONE";
        }
        
        $QO = new QUERY("qryl4pdaccn12");
        $params = array( 
            ':curr_user1' => intval($accid),
            ':curr_user2' => intval($accid),
            ':curr_user3' => intval($accid) 
        );
        $folg_datas = $QO->execute($params);
        
        //[29-12-2014] Fait expres pour ne pas modifier la structure de l'Objet. Le but est de faure que les on_read soit indépendant de l'Objet.
        $PA = new PROD_ACC();
        if ( $folg_datas ) {
            /*
             * [29-08-14]
             * Gestion du cas 'D_FOLW'. 
             * Plusieurs choix était possibles, j'ai choisi celui de récupérer l'identifiant de l'utilisateur qui suit (ACTOR) celui donné en paramètre
             * ou si c'est le même (ACTOR == User) je prends celui de TARGET. Ensuite, je récupère les données du Compte sélectionné.
             * C'est la manière la moins casse-tête que j'ai trouvé. L'autre aurait impliqué beaucoup trop d'opérations qui auraient pu aboutir à des bugs ou un debug difficile.
             * 
             * NOTE : Cette méthode est relativement similaire à onread_acquiere_my_following(). C'est normal ! 
             *          Cette manière de faire nous permet de garder comme pivot un compte. C-a-d on peut envoyer un numéro dans les deux et recevoir Followers ou Following.
             * 
             * On aurait pu le faire autrement, mais à cette date je choisi cette méthode !
             * 
             * [30-08-14]
             * Gestion du cas 'D_FOLW'. 
             * 
             * Il n'y a pas vraiment de problème avec D_FOLW.
             * En effet, les données sont renvoyées SI ET SEULEMENT SI, on a une relation 'S_FOLW' et que la cible est ACTOR. ENSUITE, s'il y a une relation de type 'D_FOLW'.
             * 
             */
            $following_list = [];
            foreach ($folg_datas as $row) {
                
                //S'il est l'Acteur on récupère les données de Target. Ceci est le cas "normal".
                if ( intval($accid) === intval($row["tbrel_acc_targ"]) ) {
                    
                    //Il est toujours possible que l'utilisateur ciblé n'est plus de compte ou qu'il soit à todelete (1). On s'assure que ce n'est pas le cas.
                    /*
                     * [02-12-14] L.C.
                     * On utilise on_read car c'est le seul qui est capable de fournir toutes les informations necessaires.
                     * De plus, d'après les derniers tests, le temps d'acquisition des données n'est pas si élévé.
                     */
                    $r = $PA->on_read_entity(["accid"=>$row["tbrel_acc_actor"]]);
//                    $r = $PA->exists_with_id($row["tbrel_acc_actor"]);
                    if ( !$r || intval($r["pdacc_todelete"]) !== 0 || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                        continue; //Ce qui veut dire qu'on skip
                    } else {
                        //On fusionne les deux tableaux
                        $following_list[] = array_merge($row, $r);
                    }
                    
                } else {
                    /*
                     * On récupère les données d'ACTOR. Ce cas est possible uniquement à cause du système 'D_FOLW'.
                     * On en effet, meme si dans la ligne actuelle acccid ne correspond pas à Actor, il l'a été avant que la Relation se transforme en 'D_FOLW'.
                     * Autrement dit, dans ce cas, ils sont à 'D_FOL' parce que accid a commencé à FOLW puis il a été FOLW
                     */
                    
                    //Il est toujours possible que l'utilisateur ciblé n'est plus de compte ou qu'il soit à todelete (1). On s'assure que ce n'est pas le cas.
                    /*
                     * [02-12-14] L.C.
                     * On utilise on_read car c'est le seul qui est capable de fournir toutes les informations necessaires.
                     * De plus, d'après les derniers tests, le temps d'acquisition des données n'est pas si élévé.
                     */
                    $r = $PA->on_read_entity(["accid"=>$row["tbrel_acc_targ"]]);
//                    $r = $PA->exists_with_id($row["tbrel_acc_targ"]);
                    
                    if ( !$r || intval($r["pdacc_todelete"]) !== 0 || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                        continue; //Ce qui veut dire qu'on skip
                    } else {
                        //On fusionne les deux tableaux
                        $following_list[] = array_merge($row, $r);
                    }
                }   
            }
            
            return $following_list;
            
        } else {
            return;
        }
    }
    
    public function onread_acquiere_my_community ($accid) {
        /*
         * Récupère les données sur la "communauté" de l'utilisateur passé en paramètre.
         * Cette communauté compte :
         *  (1) Les Articles postés dans mes Tendances qui ne lui appartiennent pas
         *  (2) Les Articles postés les Tendances suivies par l'utilisateur qui ne lui appartiennent pas
         *  (3) Les Articles postés par les Amis
         *  (4) Les Articles postés par les comptes suivis (S_FOLW ou D_FOLW)
         * 
         * Pour récupérer les données sur les Relations on passe par la table VM car elle est plus rapide.
         * Raison de plus pour qu'elle soit régulièrement mise à jour.
         */
        
        /*
         * Ajouté depuis [06-12-14]
         */
        //On récupère la liste des Tendances qui appartiennent à l'utilisateur passé en paramètre
        $QO = new QUERY("qryl4pdaccn4");
        $params = array( ':accid' => $accid );
        $mine__ = $datas = $QO->execute($params);
        
        $mine_list = NULL;
        if ( $datas ) {
            foreach ($datas as $v) {
                $mine_list[] = $v["trid"];
            }
        }
        
        //On récupère la liste des abonnements aux Tendances
        $QO = new QUERY("qryl4pdaccn5_wopt_tdl");
//        $QO = new QUERY("qryl4pdaccn5"); [DEPUIS 04-09-15] @BOR
        $params = array( ':accid' => $accid );
        $abo__ = $datas = $QO->execute($params);
        
        $abo_list = NULL;
        if ( $datas ) {
            foreach ($datas as $v) {
                $abo_list[] = $v["trabo_trid"];
            }
        }
        
        //On récupère la liste des Relations
        $QO = new QUERY("qryl4pdaccn22_wopt_tdl");
//        $QO = new QUERY("qryl4pdaccn22");
        $params = array( 
            ':actor1' => floatval($accid), 
            ':actor2' => floatval($accid),
            ':actor3' => floatval($accid), 
            ':actor4' => floatval($accid), 
            ':actor5' => floatval($accid) 
        );
        $rel__ = $datas = $QO->execute($params);
        
//        var_dump("LINE => ",__LINE__,$mine__,$abo__,$rel__);
//        exit();
        
        $rel_list = NULL;
        if ( $datas ) {
            foreach ($datas as &$v) {
                if ( intval($v["tbrel_acc_actor"]) === intval($accid) ) {
                    $rel_list[$v["tbrel_acc_targ"]] = [ 
                        "id"        => $v["tbrel_acc_targ"], 
                        "rel_sts"   => $v["tbrel_relsts"] 
                    ];
                } else {
                    $rel_list[$v["tbrel_acc_actor"]] = [ 
                        "id"        => $v["tbrel_acc_actor"], 
                        "rel_sts"   => $v["tbrel_relsts"] 
                    ];
                }
            }
        }
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$rel_list,'v_d');

        
        $r = [
            "own" => $mine_list,
            "abo" => $abo_list,
            "rel" => $rel_list
        ];
                
//        var_dump($r);
//        exit();
        
        return $r;
        
    }
    
    /*** FRIEND SCOPE ***/
    
    public function onread_acquiere_my_friends ( $accid, $with_ids = FALSE  ) {
        
        //with ids : Permet de dire qu'on aimerait que le retour soit un tableau [tab_ids, tab_User]
        /*
         * Crée le 30-08-14
         * Permet de récupérer la liste des Amis du compte mentionné en paramètre.
         */
        //On vérifie que l'utilisateur passé en paramètre existe toujours
        $exists = $this->exists_with_id($accid,TRUE);
        
        if (! $exists ) {
            return "__ERR_VOL_ACC_GONE";
        }
        
        //On récupère les données sur les Amis à lister.
        /*
         * A la version vb1 : On récupère tous les amis. Cela peut ralentir la performance mais on par du principe qu'il y'en aura pas tant que ça à cette version.
         */
        
        $QO = new QUERY("qryl4pdaccn15");
        $params = array( ':actor1' => intval($accid), ':actor2' => intval($accid) );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $my_friends = $ids = NULL;
            
            //On vérifie que l'utilisateur n'est pas en mode to_delete
            foreach ($datas as $frd) {
                if ( $frd["pdacc_todelete"] === 0 || $frd["pdacc_todelete"] === '0' ) {
                    $my_friends[] = $frd;
                    $ids[] = $frd["pdaccid"];
                }
            }
            
            if ( ( is_bool($with_ids) && $with_ids !== FALSE ) || ( is_integer($with_ids) && $with_ids !== 0 ) ) {
                return [$ids,$my_friends];
            } else {
                return $my_friends;
            }
            
        } else return;
    }
    
    public function onread_acquiere_my_friend_requests_list ($uid) {
        /*
         * Permet de récupérer la liste des mes demandes d'amis auxquelles l'utilisateur passé en paramètre n'a pas encore répondues.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que l'utilisateur passé en paramètre existe toujours
        $exists = $this->exists_with_id($uid,TRUE);
        
        if (! $exists ) {
            return "__ERR_VOL_ACC_GONE";
        }
        
        //On vérifier les demandes en attente au niveau de la base de données
        $QO = new QUERY("qryl4pdaccn14");
        $params = array( ':actor' => intval($uid) );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas;
            
            //EVOLUTION : Faut-il récupérer la relation qu'ont les protagonistes à l'instant t?
        } else { return; }
        
    }
    
    public function onread_acquiere_sent_friend_requests_list ($uid) {
        
    }
    
    
    /****************************** USER_ACTIVITY_LOGS ********************************/
    
    public function UserActyLog_Set($args, $CHECK_USER = TRUE) {
        /*
         * Permet d'enregistrer l'activité de l'utilisateur.
         * Ce "log" est une fonctionnalité essentielle car elle permet de suivre l'activité de l'utilsateur de manière extremement précise.
         * En termes d'application, on pourra noter l'opération qui permet de déterminer si l'utilisateur est connecté. 
         */
        $this->check_isset_entry_vars (__FUNCTION__, __LINE__, $args, TRUE);
        
        $XPTD = ["uid","ssid","locip_str","locip_num","useragt","wkr","url","isAx"];
        $com  = array_intersect( array_keys($args), $XPTD);
        
        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE => ", array_diff(array_keys($args), $XPTD)],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    if ( $k === "useragt" ) {
                        continue;
                    } else if ( $k === "isAx" && $v === 0 ) {
                        continue;
                    } else {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    }
                } 
            }
        }
        
        //On vérifie que l'utilisateur existe
        if ( $CHECK_USER ) {
            $u_tab = $this->exists_with_id($args["uid"],TRUE);
            if (! $utab ) {
                return "__ERR_VOL_U_GONE";
            }
        }
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4ualgn1");
        $params = array ( 
            ":uid"          => $args["uid"], 
            ":ssid"         => $args["ssid"], 
            ":locip_str"    => $args["locip_str"], 
            ":locip_num"    => $args["locip_num"], 
            ":useragt"      => $args["useragt"], 
            ":wkr"          => $args["wkr"], 
            ":fe_url"       => $args["fe_url"], 
            ":srv_url"      => $args["srv_url"], 
            ":isax"         => $args["isAx"], 
            ":adate"        => date("Y-m-d G:i:s",($now/1000)), 
            ":adate_tstamp" => $now );
        $id = $QO->execute($params);
        
        return $id;
    }
    
    public function UserActyLog_FeedTestDatas ($uid, $setnb = 10) {
        /*
         * Permet de créer un jet de données test pour un Compte donné.
         * Cette méthode est particulièrement utile pour des opérations de DEV, DEBUG et TEST.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_int($setnb) && $setnb > 0 ) ) {
            return;
        }
        
        for($i=0;$i<$setnb;$i++) {
            $DATAS = [
                "uid"       => $uid,
                "ssid"      => $this->guidv4(),
                "locip_str" => "127.0.0.1",
                "locip_num" => sprintf('%u', ip2long("127.0.0.1")),
                "useragt"   => "USER_AGENT",
                "wkr"       => "FAKE_WORKER_ID_".rand(1, 100),
                "url"       => "http://127.0.0.1",
                "isAx"      => rand(0,1)
            ];
            
            $this->UserActyLog_Set($DATAS,FALSE);
        }
        
        return TRUE;
    }
    
    public function UserActyLog_Within($uid, $mins, $limit = 10) {
        /*
         * Permet de récupérer l'ensemble des activités de l'utilisateur passé en paramètre dans l'interval de temps mentionné.
         * La valeur est renseignée en minutes. 
         * On prend considère l'ensemble des logs entre "now" et les x minutes qui ont suivi.
         * La méthode admet aussi une limite qui sert de garde-fou.
         * 
         * On ne vérifie pas si l'utilisateur existe car s'il n'existe pas il n'aura aucune activité et puis c'est tout.
         * On le fait aussi pour des soucis de performances quand on sait que dans ce cas savoir si l'utilisateur existe est trivial.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( (is_int($mins) && $mins > 0) && (is_int($limit) && ($limit === -1 || $limit > 0)) ) ) {
            return "__ERR_VOL_RULES";
        }
        
        $now = round(microtime(true)*1000);
        $gap = $now - $mins*60000;
        
        $QO = new QUERY("qryl4ualgn2");
        $params = array( ':uid' => $uid, ':now' => $now, ':minus' => $gap, ':limit' => $limit );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return;
        } else {
            return $datas;
        }
        
    }
    
    /*********************************************** ON READ (end) ************************************************************/
    /**************************************************************************************************************************/
    
    
    /**************************************************************************************************************************/
    /************************************************** ON UPDATE (start)  ****************************************************/
    
    /*** CAPITAL ***/
    public function update_capital() {
        /*
         * Permet de mettre à jour le capital du compte chargé en prennant en compte toutes opérations disponibles.
         */
        if ( !$this->is_instance_load || empty($this->pdaccid) ) {
            return "__ERR_VOL_ACC_UPD_FAILED_NOT_LOADED";
        }
        
        $capital = NULL;
        
        //On récupère le capital
        $QO = new QUERY("qryl4pdaccn21neo0815001");
//        $QO = new QUERY("qryl4pdaccn21");
        $params = array( ':accid' => $this->pdaccid );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            $capital = 0;
        } else {
            $capital = $datas[0]["capital"];
        }
        
        //On met à jour le capital
        $QO = new QUERY("qryl4capern3");
        $params = array( ':accid' => $this->pdaccid, ':capital' => $capital );
        $QO->execute($params);
        
        return $capital;
    }
    
    /**
     * Permet de mettre à jour le capital du Compte dont l'identifiant est passé en paramètre
     * 
     * @param type $uid
     * @param array $_OPTIONS (Facultatif)
     * @return int Le capital après mise à jour 
     */
    public function update_capital_for($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        if (! in_array("AQAP", $_OPTIONS) ) {
            $utab = $this->exists_with_id($uid,TRUE);
            if (! $utab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $capital = NULL;
        
        //On récupère le capital
        $QO = new QUERY("qryl4pdaccn21neo0815001");
//        $QO = new QUERY("qryl4pdaccn21");
        $params = array( ':accid' => $uid );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            $capital = 0;
        } else {
            $capital = intval($datas[0]["capital"]);
        }
        
        //On met à jour le capital
        $QO = new QUERY("qryl4capern3");
        $params = array( ':accid' => $uid, ':capital' => $capital );
        $QO->execute($params);
        
        return $capital;
    }
    
    /*** PROFILBIO ***/
    
    public function set_new_profilbio ($oid, $bio) {
        //RAPPEL : On peut recevoir une chaine vide. En effet, l'utilisateur peut décider de n'avoir aucune bio.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $oid);
        //** On vérifie si le texte respecte les conditions d'entrée (existe, ne dépase pas MAX) **//
        
        $TXH = new TEXTHANDLER();
        
        /*
         * [NOTE 31-08-14] @author L.C. <lou.carther@deuslynn-entreprise.com>
         * On ne considère pas la gestion des href. on laisse l'utilisateur mettre ce qu'il veut.
         * De toutes les façons, on va sécuriser le texte.
         */
//        var_dump(!isset($bio),( $bio !== "" && strlen($bio) > $this->_PFLBIO_MAX ),strlen($bio),$bio);
//        exit();
        if ( !isset($bio) || ( $bio !== "" && $TXH->strlen_utf8($bio) > $this->_PFLBIO_MAX ) ) {
//        if ( !isset($bio) || ( $bio !== "" && strlen($bio) > $this->_PFLBIO_MAX ) ) {
            echo strlen($bio);
            return "__ERR_VOL_BIO_MSM_RULES";
        }
        
        //On vérifie que l'utilisateur existe
        $exists = $this->exists_with_id($oid,TRUE);
        
        if (! $exists ) {
            return "__ERR_VOL_CU_GONE";
        }
        
        //On vérifie si on a du texte.... 
        if ( $bio !== "" ) {
            //... Dans ce cas, on le sécurise
            
            //On sécurise le texte
            
            $bio = $TXH->secure_text($bio);
        }
                
        //On UPDATE la base de données
        $QO = new QUERY("qryl4pdaccn16");
        $params = array( ':accid' => $oid, ':bio' => $bio );
        $QO->execute($params);
        
        //On retourne le texte sécurisé
        return $bio;
        
    }
    
    /*** WEBSITE **/
    
    public function set_new_website ($oid, $wbst) {
        //RAPPEL : On peut recevoir une chaine vide. En effet, l'utilisateur peut ne pas avoir de siteweb personnel.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $oid);
        
        /*
         * [NOTE 31-08-14] @author L.C. <lou.carther@deuslynn-entreprise.com>
         * On ne considère pas la gestion des href. on laisse l'utilisateur mettre ce qu'il veut.
         * De toutes les façons, on va sécuriser le texte.
         */
        
        if ( !isset($wbst) ) {
            return "__ERR_VOL_UWSBT_MSM_RULES";
        } else if ( !empty($wbst) && is_string($wbst) && strlen($wbst) > $this->_UWEBSITE_MAX ) {
            return "__ERR_VOL_UWSBT_MSM_LEN";
        } else if ( !empty($wbst) && is_string($wbst) ) {
           /*
            * On effectue des opérations de transformation de l'URL le cas échéant pour la valider.
            */
            $w__ = (! preg_match("#^https?://#", $wbst) ) ? "http://".$wbst : $wbst;
            if ( !preg_match($this->_UWEBSITE_RGX,$w__) ) {
                return "__ERR_VOL_UWSBT_MSM_FRMT";
            }
//            var_dump(__LINE__,isset($w__),empty($w__),is_string($w__),strlen($w__) > $this->_UWEBSITE_MAX,filter_var($w__, FILTER_VALIDATE_URL), parse_url($w__),preg_match($this->_UWEBSITE_RGX,$w__));
//            exit();
        }
        
        //On vérifie que l'utilisateur existe
        $exists = $this->exists_with_id($oid,TRUE);
        if (! $exists ) {
            return "__ERR_VOL_CU_GONE";
        }
        
        //On vérifie si on a du texte.... 
        if ( $wbst !== "" ) {
            //... Dans ce cas, on le sécurise
            
            //On sécurise le texte
            $TXH = new TEXTHANDLER();
            $wbst = $TXH->secure_text($wbst);
        }
                
        //On UPDATE la base de données
        $QO = new QUERY("qryl4pdaccn29");
        $params = array( ':accid' => $oid, ':wbst' => $wbst );
        $QO->execute($params);
        
        //On retourne le texte sécurisé
        return $wbst;
        
    }
    
    /*** APPARENCE ***/
    
        
    /*** ACCOUNT COVER ***/
    public function set_new_account_cover () {
        
    }
    
    /*** PROFIL PIC ***/
    public function set_new_profil_picture () {
        
    }
    
    public function reset_profil_picture () {
        
    }
    /*********************************************** ON UPDATE (end) **********************************************************/
    /**************************************************************************************************************************/
        
    
    
    /**************************************************************************************************************************/
    /************************************************** ON DELETE (start)  ****************************************************/
    
    public function ondelete_change_all_art_state($accid, $state, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$accid,$state]);
        
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
         * On vérifie également que le compt n'est pas en mode TODEL mode 2. C'est le seul cas où on ne plus effectuer de modifications.
         */ 
        if (! ( $_OPTIONS && is_array($_OPTIONS) && in_array("AQAP", $_OPTIONS) ) ) {
            $atab = $this->exists_with_id($accid,FALSE);
            if (! $atab ) {
                return "__ERR_VOL_USER_GONE";
            } else if ( intval($atab["pdacc_todelete"]) === 2 ) {
                /*
                 * [NOTE 05-09-15] @BOR
                 *  Il serait possible de changer le type d'erreur s'il fallait effectuer une differenciation.
                 */
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        $now = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($now/1000));
        
        /*
         * On met à jour les occurrences dans la table des historiques pour les Articles ayant déjà un "state".
         * Il se peut qu'il y'en ait pas.
         */
        $QO = new QUERY("qryl4artn25_all");
        $params = array( ':accid' => $accid, ':time' => $date, ':tstamp' => $now);
        $QO->execute($params);
        
        /*
         * On ajoute les nouvelles occurrences dans la table des historiques pour tous les Articles.
         */
        $QO = new QUERY("qryl4artn26_all");
        $params = array( ':accid' => $accid, ':ash_state' => $state, ':time' => $date, ':tstamp' => $now);
        $QO->execute($params);
        
        /*
         * Dans tous les cas, on change les états des Articles pour les versions VM.
         *  -> Pour les Articles IML
         *  -> Pour les Articles ITR
         */
        $QO = new QUERY("qryl4artn28_iml");
        $params = array( ':accid' => $accid, ':state' => $state );
        $QO->execute($params);
        
        $QO = new QUERY("qryl4artn28_itr");
        $params = array( ':accid' => $accid, ':state' => $state );
        $QO->execute($params);
        
        /*
         * [NOTE 05-09-15] @author BOR
         *  Faut-il changer la donnée "art_todel" en la mettant à 1 ?
         */
        
        /*
         *  On met à jour le capital du Compte de l'utilisateur sauf si on a une indication contraire.
         */
        if (! ( $_OPTIONS && is_array($_OPTIONS) && in_array("UPD_CAP_NO", $_OPTIONS) ) ) {
            $y__ = $this->update_capital_for($accid,["AQAP"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $y__) ) {
                return "__ERR_VOL_ALMST_DONE";
            }
        }
        
        return TRUE;
    }
    
    private function ondelete_delete_all_reactions () {
        
    }
    
    private function ondelete_delete_all_articles () {
        //Supprimera : EVALUATION, IMAGE et ARTICLE
    }
    
    private function ondelete_delete_all_trends () {
        
    }
    
    private function ondelete_delete_requests () {
        
    }
    
    private function ondelete_delete_all_relations () {
        
    }
    
    private function ondelete_delete_all_capoper () {
        
    }
    
    private function ondelete_delete_all_evants () {
        
    }
    
    
    /*********************************************** ON DELETE (end) **********************************************************/
    /**************************************************************************************************************************/
    
    /***************************************************************************************************************************************************/
    /************************************************************ OTHERS MODULE SCOPE ******************************************************************/
    /***************************************************************************************************************************************************/
    
    /****************************** ABME_INTRO SCOPE ********************************/
    
    public function abme_intro_get ($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $final = NULL;
        $QO = new QUERY("qryl4abme_intron3");
        $params = array( ':uid' => $uid);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $datas = $datas[0];
            $ustgs = $this->abme_intro_get_anx_list_usertags($datas["abme_intro_id"]);
            $hashs = $this->abme_intro_get_anx_list_hash($datas["abme_intro_id"],$datas["abme_intro_eid"]);
                    
            $final = [
                "datas" => $datas,
                "ustgs" => $ustgs,
                "hashs" => $hashs,
            ];
            
            if ( $_OPTIONS && in_array("WFEO", $_OPTIONS) ) {
                if ( $ustgs ) {
                    array_walk($ustgs,function(&$i,$k){
                        $i = [
                            'eid'   => $i['ustg_eid'],
                            'ueid'  => $i['tgtueid'],
                            'ufn'   => $i['tgtufn'],
                            'upsd'  => $i['tgtupsd']
                        ];
                    });
                }
                
                $final = [
                    "datas" => $datas["abme_intro_lib"],
                    "ustgs" => $ustgs,
                    "hashs" => $hashs,
                ];
            }
        }
        
        return $final;
    }
    public function abme_intro_get_anx_list_hash ($id, $eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $list = NULL;
        
        $QO = new QUERY("qryl4hviewn17_ABME_INTRO");
        $qparams_in_values = array(
            ":id"   => $id,
            ":eid"  => $eid
        );  
        $datas = $QO->execute($qparams_in_values);
        
        if ( $datas ) {
            foreach ($datas as $v) {
                $list[] = $v["hic_gvnhsh"];
            }
        }
        
        return $list;
    }
    public function abme_intro_get_anx_list_usertags ($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        //Récupérer la liste des Usertags
        $QO = new QUERY("qryl4ustg_abme_intron2");
        $qparams_in_values = array(
            ":abmid" => $id
        );  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
    }
    
    public function abme_intro_set ($uid, $set, $ssid, $locip, $uagent = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $set, $ssid, $locip]);
        
        $txt_format = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,300}$/i";
        
        $exists = $this->abme_intro_get ($uid);
        if ( $exists ) {
            $this->abme_intro_last_upd($uid);
        }
        
        $omsg = $set["lib"];
        $kws = $usertags = NULL;
        $nmsg = $this->oncreate_treat_msg($set["lib"], $usertags, $kws, $txt_format);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nmsg) ) {
            return $nmsg;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$omsg,$usertags,$kws,$nmsg]);
//        exit();
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4abme_intron1");
        $params = array ( 
            ":uid"          => $uid,
            ":lib"          => $nmsg,
            ":ssid"         => $ssid, 
            ":locip"        => $locip, 
            ":uagent"       => $uagent, 
            ":date"         => date("Y-m-d G:i:s",($now/1000)), 
            ":tstamp"       => $now 
        );
        $id = $QO->execute($params);
        
        //Créer eid
        $eid = $this->entity_ieid_encode($now, $id);
        $QO = new QUERY("qryl4abme_intron1_eid");
        $params = array(
            ":id"   => $id, 
            ":eid"  => $eid
        );  
        $QO->execute($params);
        
        /*
         * [DEPUIS 17-11-15]
         *      On traite le cas des mot-clés
         */
        if ( $kws ) {
            $HVIEW = new HVIEW();
            $args_urlic = [
                "t"     => $omsg,
                "hci"   => $id,
                "hcei"  => $eid,
                "hcp"   => "HCTP_ABME_INTRO",
                "ssid"  => $ssid,
                "locip" => $locip,
                "curl"  => NULL,
                "uagnt" => $uagent
            ];
            $kws_r = $HVIEW->HSH_oncreate($args_urlic["t"], $args_urlic["hci"], $args_urlic["hcei"], $args_urlic["hcp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
        }
        
        /*
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
            foreach ($list_utags as $psd) {
                $utag_tab = $this->exists_with_psd($psd,TRUE,TRUE);
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
                    $us_id = $QO->execute($params);
                    
                    /*
                     * On procède à la mise à jour en insérant l'identifiant externe
                     */
                    $QO = new QUERY("qryl4ustgn2");
                    $params = array(
                        ":id"   => $id, 
                        ":eid"  => $this->entity_ieid_encode($now, $us_id));  
                    $QO->execute($params);
                        
                    /*
                     * On insère l'occurrence dans la classe fille dédiée à TESTY
                     */
                    $QO = new QUERY("qryl4ustg_abme_intron1");
                    $params = array(
                        ":id"       => $us_id, 
                        ":abmid"    => $id
                    );  
                    $QO->execute($params);
                }
            }
            
        }
        
        if (! ( $_OPTIONS && in_array("WGDOP", $_OPTIONS) ) ) {
            $WGDOP_datas = $this->abme_intro_get($uid);
            return ( $WGDOP_datas ) ? $WGDOP_datas : NULL;
        }
        
        return $id;
    }
    
    public function abme_intro_last_upd ($uid, $set) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $QO = new QUERY("qryl4abme_intron2");
        $params = array( 
            ':uid'      => $uid,
            ':date'     => date("Y-m-d G:i:s",($now/1000)), 
            ':tstamp'   => $now 
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    /****************************** ABME_LOVE_SNIPPETS SCOPE ********************************/
    
    
    public function abme_lvsp_get ($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $final = NULL;
        if ( $_OPTIONS && in_array("WFEO", $_OPTIONS) ) {
            $QO = new QUERY("qryl4abme_lvspn3_wfeo");
            $params = array( 
                ':uid1'     => $uid,
                ':uid2'     => $uid,
                ':uid3'     => $uid,
                ':uid4'     => $uid,
                ':uid5'     => $uid,
                ':uid6'     => $uid,
                ':uid7'     => $uid,
                ':uid8'     => $uid,
                ':uid9'     => $uid,
                ':uid10'    => $uid
            );
            $datas = $QO->execute($params);
        } else {
            $QO = new QUERY("qryl4abme_lvspn3");
            $params = array( ':uid' => $uid);
            $datas = $QO->execute($params);
        }
        
        if ( $datas ) {
            
            if ( $_OPTIONS && in_array("WFEO", $_OPTIONS) ) {
                $datas = array_column($datas, "catg_decocode");
                array_walk($datas,function(&$i,$k){
                    $i = "_NTR_CATG_".strtoupper($i);
                });
            } else {
                $datas = $datas[0];
                $datas = array_values(array_slice($datas,3,10));
            }
                    
            $final = [
                "datas" => $datas,
            ];
        }
        
        return $final;
    }
    public function abme_lvsp_get_pl_catgs () {
        
        $QO = new QUERY("qryl4ctgn4"); 
        $datas = $QO->execute(null);
        
        $list = [];
        $TXH = new TEXTHANDLER();
        foreach ($datas as $cg_tab) {
            $t__ = "_NTR_CATG_".strtoupper($cg_tab["catg_decocode"]);
            $list[$cg_tab["catgid"]] = $t__;
        }
        ksort($list);
        
        return $list;
    }
    
    public function abme_lvsp_set ($uid, $set, $ssid, $locip, $uagent = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $set, $ssid, $locip]);
        
        /*
        $tr_catg = [
            "_NTR_CATG_ANIMALS"         => 0,
            "_NTR_CATG_ART"             => 0,
            "_NTR_CATG_BIZARRE"         => 0,
            "_NTR_CATG_BUSINESS"        => 0,
            "_NTR_CATG_CINEMA"          => 0,
            "_NTR_CATG_CONFLICT"        => 0,
            "_NTR_CATG_COOKING"         => 0,
            "_NTR_CATG_EDUCATION"       => 0,
            "_NTR_CATG_ENTERTAINMENT"   => 0,
            "_NTR_CATG_FASHION"         => 0,
            "_NTR_CATG_FITNESS"         => 0,
            "_NTR_CATG_GEEK"            => 0,
            "_NTR_CATG_HEALTH"          => 0,
            "_NTR_CATG_HUMANITARIAN"    => 0,
            "_NTR_CATG_HUMOR"           => 0,
            "_NTR_CATG_LEISURE"         => 0,
            "_NTR_CATG_LITERATURE"      => 0,
            "_NTR_CATG_MEME"            => 0,
            "_NTR_CATG_MUSIC"           => 0,
            "_NTR_CATG_NONPROFIT"       => 0,
            "_NTR_CATG_PEOPLE"          => 0,
            "_NTR_CATG_PHOTOBOMBS"      => 0,
            "_NTR_CATG_PLACES"          => 0,
            "_NTR_CATG_POLITICS"        => 0,
            "_NTR_CATG_SCIENCE"         => 0,
            "_NTR_CATG_SELFIES"         => 0,
            "_NTR_CATG_SOCIETY"         => 0,
            "_NTR_CATG_SPORT"           => 0,
            "_NTR_CATG_TECHNOLOGY"      => 0,
            "_NTR_CATG_TOURISM"         => 0,
            "_NTR_CATG_VIDEOGAME"       => 0,
            "_NTR_CATG_WEB"             => 0,
        ];
        //*/

       
        
        $tr_catg = $this->abme_lvsp_get_pl_catgs();
        $tr_catg_flip = array_flip($tr_catg);
        $choices = [];
        foreach ( $set as $k_ss => $ss ) {
            if ( is_string($k_ss) && substr($k_ss,0,4) === "chc_" && substr($ss,0,10) === "_NTR_CATG_" ) {
                $choices[] = strtoupper($ss);
            }
        }
        if ( empty($choices) ) {
            return "__ERR_VOL_DATAS_MSG";
        }
        
        $choices = array_unique($choices);
        if ( empty($choices) ) {
            return "__ERR_VOL_DATAS_MSG";
        }
        
//        var_dump($tr_catg,$tr_catg_flip);
//        var_dump($choices);
//        exit();
        
        $exists = $this->abme_lvsp_get($uid);
        if ( $exists ) {
            $this->abme_lvsp_last_upd($uid);
        }
        
        $fchoices ;
        foreach ( $choices as $k => $chc ) {
            if ( in_array($chc, $tr_catg)  ) {
                $fchoices[] = $tr_catg_flip[$chc];
            }
        }
        if ( empty($fchoices) ) {
            return "__ERR_VOL_MSM_NO_MATCH";
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$tr_catg,$fchoices]);
//        exit();
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4abme_lvspn1");
        $params = array ( 
            ":uid"          => $uid,
            ":catg_1"       => $fchoices[0],
            ":catg_2"       => ( $fchoices[1] ) ? $fchoices[1] : NULL,
            ":catg_3"       => ( $fchoices[2] ) ? $fchoices[2] : NULL,
            ":catg_4"       => ( $fchoices[3] ) ? $fchoices[3] : NULL,
            ":catg_5"       => ( $fchoices[4] ) ? $fchoices[4] : NULL,
            ":catg_6"       => ( $fchoices[5] ) ? $fchoices[5] : NULL,
            ":catg_7"       => ( $fchoices[6] ) ? $fchoices[6] : NULL,
            ":catg_8"       => ( $fchoices[7] ) ? $fchoices[7] : NULL,
            ":catg_9"       => ( $fchoices[8] ) ? $fchoices[8] : NULL,
            ":catg_10"      => ( $fchoices[9] ) ? $fchoices[9] : NULL,
            ":ssid"         => $ssid, 
            ":locip"        => $locip, 
            ":uagent"       => $uagent, 
            ":date"         => date("Y-m-d G:i:s",($now/1000)), 
            ":tstamp"       => $now 
        );
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$params);
//        exit();
        $id = $QO->execute($params);
        
        //Créer eid
        $eid = $this->entity_ieid_encode($now, $id);
        $QO = new QUERY("qryl4abme_lvspn1_eid");
        $params = array(
            ":id"   => $id, 
            ":eid"  => $eid
        );
        $QO->execute($params);
        
        if (! ( $_OPTIONS && in_array("WGDOP", $_OPTIONS) ) ) {
            $WGDOP_datas = $this->abme_lvsp_get($uid);
            return ( $WGDOP_datas ) ? $WGDOP_datas : NULL;
        }
        
        return $id;
    }
    
    public function abme_lvsp_last_upd ($uid, $set) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $QO = new QUERY("qryl4abme_lvspn2");
        $params = array( 
            ':uid'      => $uid,
            ':date'     => date("Y-m-d G:i:s",($now/1000)), 
            ':tstamp'   => $now 
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    /****************************** ABME_WHYME SCOPE ********************************/
    
    
    public function abme_whyme_get ($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $final = NULL;
        $QO = new QUERY("qryl4abme_whymen3");
        $params = array( ':uid' => $uid);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $datas = $datas[0];
            
            if ( $_OPTIONS && in_array("WFEO", $_OPTIONS) ) {
                $datas = [
                    html_entity_decode($datas["abme_whyme_chc1"]),
                    html_entity_decode($datas["abme_whyme_chc2"]),
                    html_entity_decode($datas["abme_whyme_chc3"])
                ];
            }
        }
        
        $final = [
            "datas" => $datas,
        ];
        
        return $final;
    }
    
    public function abme_whyme_set ($uid, $set, $ssid, $locip, $uagent = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $set, $ssid, $locip]);
        
        $txt_format = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,100}$/i";
        
        $exists = $this->abme_whyme_get($uid);
        if ( $exists ) {
            $this->abme_whyme_last_upd($uid);
        }
        
        if ( empty($set["chc_1"]) ) {
            return "__ERR_VOL_DATAS_MSG";
        }
        
        $nw_chc1 = $this->oncreate_treat_msg($set["chc_1"], $usertags, $kws, $txt_format);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nw_chc1) ) {
            return "__ERR_VOL_MSM_AT1";
        }
        if ( !empty($set["chc_2"]) ) {
            $nw_chc2 = $this->oncreate_treat_msg($set["chc_2"], $usertags, $kws, $txt_format);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nw_chc2) ) {
                return "__ERR_VOL_MSM_AT2";
            }
        }
        if ( !empty($set["chc_3"]) ) {
            $nw_chc3 = $this->oncreate_treat_msg($set["chc_3"], $usertags, $kws, $txt_format);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nw_chc3) ) {
                return "__ERR_VOL_MSM_AT3";
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$nw_chc1,$nw_chc2,$nw_chc3]);
//        exit();
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4abme_whymen1");
        $params = array ( 
            ":uid"          => $uid,
            ":chc_1"        => $nw_chc1,
            ":chc_2"        => $nw_chc2,
            ":chc_3"        => $nw_chc3,
            ":ssid"         => $ssid, 
            ":locip"        => $locip, 
            ":uagent"       => $uagent, 
            ":date"         => date("Y-m-d G:i:s",($now/1000)), 
            ":tstamp"       => $now 
        );
        $id = $QO->execute($params);
        
        //Créer eid
        $eid = $this->entity_ieid_encode($now, $id);
        $QO = new QUERY("qryl4abme_whymen1_eid");
        $params = array(
            ":id"   => $id, 
            ":eid"  => $eid
        );  
        $QO->execute($params);
        
        if (! ( $_OPTIONS && in_array("WGDOP", $_OPTIONS) ) ) {
            $WGDOP_datas = $this->abme_whyme_get($uid);
            return ( $WGDOP_datas ) ? $WGDOP_datas : NULL;
        }
        
        return $id;
    }
    
    public function abme_whyme_last_upd ($uid, $set) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $QO = new QUERY("qryl4abme_whymen2");
        $params = array( 
            ':uid'      => $uid,
            ':date'     => date("Y-m-d G:i:s",($now/1000)), 
            ':tstamp'   => $now 
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    /****************************** ABME_IMASTER SCOPE ********************************/
    
    
    public function abme_imas_get ($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $final = NULL;
        $QO = new QUERY("qryl4abme_imasn3");
        $params = array( ':uid' => $uid);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $datas = $datas[0];
            
            if ( $_OPTIONS && in_array("WFEO", $_OPTIONS) ) {
                $datas = [
                    html_entity_decode($datas["abme_imas_chc1"]),
                    html_entity_decode($datas["abme_imas_chc2"]),
                    html_entity_decode($datas["abme_imas_chc3"])
                ];
            }
        }
        
        $final = [
            "datas" => $datas,
        ];
        
        return $final;
    }
    
    public function abme_imas_set ($uid, $set, $ssid, $locip, $uagent = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid, $set, $ssid, $locip]);
        
        $txt_format = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,30}$/i";
        
        $exists = $this->abme_imas_get($uid);
        if ( $exists ) {
            $this->abme_imas_last_upd($uid);
        }
        
        if ( empty($set["chc_1"]) ) {
            return "__ERR_VOL_DATAS_MSG";
        }
        
        $nw_chc1 = $this->oncreate_treat_msg($set["chc_1"], $usertags, $kws, $txt_format);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nw_chc1) ) {
            return "__ERR_VOL_MSM_AT1";
        }
        if ( !empty($set["chc_2"]) ) {
            $nw_chc2 = $this->oncreate_treat_msg($set["chc_2"], $usertags, $kws, $txt_format);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nw_chc2) ) {
                return "__ERR_VOL_MSM_AT2";
            }
        }
        if ( !empty($set["chc_3"]) ) {
            $nw_chc3 = $this->oncreate_treat_msg($set["chc_3"], $usertags, $kws, $txt_format);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nw_chc3) ) {
                return "__ERR_VOL_MSM_AT3";
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$nw_chc1,$nw_chc2,$nw_chc3]);
//        exit();
        
        //On enregistre le log
        $now = round(microtime(true)*1000);
        $QO = new QUERY("qryl4abme_imasn1");
        $params = array ( 
            ":uid"          => $uid,
            ":chc_1"        => $nw_chc1,
            ":chc_2"        => $nw_chc2,
            ":chc_3"        => $nw_chc3,
            ":ssid"         => $ssid, 
            ":locip"        => $locip, 
            ":uagent"       => $uagent, 
            ":date"         => date("Y-m-d G:i:s",($now/1000)), 
            ":tstamp"       => $now 
        );
        $id = $QO->execute($params);
        
        //Créer eid
        $eid = $this->entity_ieid_encode($now, $id);
        $QO = new QUERY("qryl4abme_imasn1_eid");
        $params = array(
            ":id"   => $id, 
            ":eid"  => $eid
        );  
        $QO->execute($params);
        
        if (! ( $_OPTIONS && in_array("WGDOP", $_OPTIONS) ) ) {
            $WGDOP_datas = $this->abme_imas_get($uid);
            return ( $WGDOP_datas ) ? $WGDOP_datas : NULL;
        }
        
        return $id;
    }
    
    public function abme_imas_last_upd ($uid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        $now = round(microtime(true)*1000);
        
        $QO = new QUERY("qryl4abme_imasn2");
        $params = array( 
            ':uid'      => $uid,
            ':date'     => date("Y-m-d G:i:s",($now/1000)), 
            ':tstamp'   => $now 
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    
    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ GETTERS and SETTERS ******************************************************************/
    // <editor-fold defaultstate="collapsed" desc="Getters">
    
    public function getPdaccid() {
        return $this->pdaccid;
    }

    public function getPdacc_gid() {
        return $this->pdacc_gid;
    }

    public function getPdacc_href() {
        return $this->pdacc_href;
    }

    public function getPdacc_eid() {
        return $this->pdacc_eid;
    }

    public function getPdacc_upsd() {
        return $this->pdacc_upsd;
    }

    public function getPdacc_ufn() {
        return $this->pdacc_ufn;
    }
    
    public function getPdacc_gdr() {
        return $this->pdacc_gdr;
    }

    public function getPdacc_uppicid() {
        return $this->pdacc_uppicid;
    }

    public function getPdacc_uppic() {
        return $this->pdacc_uppic;
    }

    public function getPdacc_uppisdf() {
        return $this->pdacc_uppisdf;
    }

    public function getPdacc_coverdatas() {
        return $this->pdacc_coverdatas;
    }

    public function getPdacc_ucityid() {
        return $this->pdacc_ucityid;
    }

    public function getPdacc_ucity_fn() {
        return $this->pdacc_ucity_fn;
    }

    public function getPdacc_nocity() {
        return $this->pdacc_nocity;
    }

    public function getPdacc_ucnid() {
        return $this->pdacc_ucnid;
    }

    public function getPdacc_ucn_fn() {
        return $this->pdacc_ucn_fn;
    }

    public function getPdacc_udl() {
        return $this->pdacc_udl;
    }

    public function getPdacc_datecrea() {
        return $this->pdacc_datecrea;
    }

    public function getPdacc_datecrea_tstamp() {
        return $this->pdacc_datecrea_tstamp;
    }

    public function getPdacc_todelete() {
        return $this->pdacc_todelete;
    }

    public function getPdacc_ctw_dsma() {
        return $this->pdacc_ctw_dsma;
    }

    public function getPdacc_ctw_moddate() {
        return $this->pdacc_ctw_moddate;
    }

    public function getPdacc_ctw_moddate_tstamp() {
        return $this->pdacc_ctw_moddate_tstamp;
    }

    public function getPdacc_profilbio() {
        return $this->pdacc_profilbio;
    }
    
    public function getPdacc_website() {
        return $this->pdacc_website;
    }

    public function getPdacc_capital() {
        return $this->pdacc_capital;
    }

    public function getPdacc_stats_posts_nb() {
        return $this->pdacc_stats_posts_nb;
    }

    public function getPdacc_stats_mytrends_nb() {
        return $this->pdacc_stats_mytrends_nb;
    }

    public function getPdacc_stats_fol_trends_nb() {
        return $this->pdacc_stats_fol_trends_nb;
    }

    public function getPdacc_stats_folr_nb() {
        return $this->pdacc_stats_folr_nb;
    }

    public function getPdacc_stats_folg_nb() {
        return $this->pdacc_stats_folg_nb;
    }

    public function getMy_trends_list() {
        return $this->my_trends_list;
    }

    public function getMy_following_trends_list() {
        return $this->my_following_trends_list;
    }

    public function getMy_followers_list() {
        return $this->my_followers_list;
    }

    public function getMy_following_list() {
        return $this->my_following_list;
    }

    public function getMy_friends_list() {
        return $this->my_friends_list;
    }

    public function getMy_friend_request_list() {
        return $this->my_friend_request_list;
    }

    public function get_LIMIT_FIRST_ARTS() {
        return $this->_LIMIT_FIRST_ARTS;
    }

    public function get_LIMIT_NWR_ARTS() {
        return $this->_LIMIT_NWR_ARTS;
    }

    public function get_LIMIT_PD_ARTS() {
        return $this->_LIMIT_PD_ARTS;
    }

    public function get_PFLBIO_MAX() {
        return $this->_PFLBIO_MAX;
    }

    public function get_PFLBIO_HREF_AUTHORIZED() {
        return $this->_PFLBIO_HREF_AUTHORIZED;
    }

    public function get_PFLBIO_HREF_ENALBLE() {
        return $this->_PFLBIO_HREF_ENALBLE;
    }

    
    // </editor-fold>

    
    public function setPdacc_ctw_dsma($accid, $pdacc_ctw_dsma, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $accid);
        
//        var_dump(( isset($pdacc_ctw_dsma) && ( is_bool($pdacc_ctw_dsma) || ( $pdacc_ctw_dsma === 1 || $pdacc_ctw_dsma === 0 ) ) ));
        
        if (! ( isset($pdacc_ctw_dsma) && ( is_bool($pdacc_ctw_dsma) || ( $pdacc_ctw_dsma === 1 || $pdacc_ctw_dsma === 0 ) ) ) )
            return;
        
        //On vérifie si l'utilisateur existe
        $exists = $this->exists_with_id($accid,TRUE);
        
        if (! $exists ) {
            if ( $std_err_enabled ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$accid,'v_d');
                $this->signalError ("err_user_l4comn11", __FUNCTION__, __LINE__,TRUE);
            }
            else
                return "__ERR_VOL_CU_GONE";
        }
        
        $action = ( $pdacc_ctw_dsma ) ? 1 : 0;
        $now = round(microtime(true)*1000);
        
        $QO = new QUERY("qryl4pdaccn7");
        $params = array( ':accid' => intval($accid), ':action' => $action, ':tstamp' => $now );
        $QO->execute($params);
        
        $this->pdacc_ctw_dsma = $action;
        
        $acc_eid = $this->onread_get_acceid_from_accid($accid);
        
        //On load la classe
        $loads = $this->load_entity(["acc_eid" => $acc_eid]);
        
//        var_dump($loads);
//        exit();
        
        return $loads;
    }


}


?>