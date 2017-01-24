<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe pour Account. 
 * Il s'agit de la version pour la base ACCOUNT.
 * Elle a été créée suite aux nombreux disfonctionnement constatés au niveau de la version de la version prototype qui n'a donc pas été retenue.
 * 
 * @author Richard Lou Carther 
 */
class TQR_ACCOUNT extends PROD_ENTITY {
    
    private $accid;
    private $acc_eid;
    private $acc_grp;

    /* Données de Profil */
    private $pfl_fn;
    private $pfl_bdate;
    private $pfl_bdate_tstamp;
    private $pfl_bdate_mod_rem;
    private $pfl_lvcity;
    private $pfl_lvcity_name;
    private $pfl_lvcity_cncode;
    private $pfl_nocity;
    private $pfl_gender;
    private $pfl_gender_mod_rem;
    private $pfl_dmod;
    private $pfl_dmod_tstamp;

    /* Données de Compte */
    private $acc_psd;
    private $acc_psd_dmod;
    private $acc_psd_dmod_tstamp;
    private $acc_eml;
    private $acc_pwd;
    private $acc_pwd_dmod;
    private $acc_pwd_dmod_tstamp;
    private $acc_lang;
    private $acc_lang_dmod;
    private $acc_lang_dmod_tstamp;
    private $acc_crea_locip;
//    private $acc_pflbio;
//    private $acc_pflbio_dmod;
//    private $acc_pflbio_dmod_tstamp;
    private $acc_dcrea;
    private $acc_dcrea_tstamp;
    private $acc_todelete;

    /* Données de Sécurité de Compte */
    private $secu_staycon;
    private $secu_coWithPsdEna;
    private $secu_isThirdCritEna;
    private $secu_notifyWhenLogin;
    
    /*********** RULES ***********/
    private $rgx_fn;
    private $rgx_bd;
    private $bd_limit;
    private $rgx_gdr;
    private $rgx_psd;
    private $psd_min;
    private $psd_max;
    private $rgx_email;
    private $email_max;
    private $rgx_pwd;
    private $pwd_min;
    private $pwd_max;
    private $INS_DEFAULT_LNG;
    private $rgx_code;
    private $rgx_lng;
    private $TQR_AVAL_LANG;
    private $DEL_HIKW;
    private $DEL_YILV;
    private $rgx_yilv_ot;
    private $rgx_ilbbif;
    
    private $REC_INTVAL;
    private $TDL_INTVAL;
    private $EML_DEFAULT;
    
    /*
     * Le Capital de clcoins donné à la création du Compte.
     * Cette valeur est susceptible de changer en fonction de la politique de fonctionnement de Trenqr
     */
    private $_CPTL_START;
    
    /*
     * Le mode choisi par USER pour s'inscrire.
     */
    private $_ENTERCZ;
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["accid","acc_eid","acc_grp","pfl_fn","pfl_bdate","pfl_bdate_tstamp","pfl_bdate_mod_rem","pfl_lvcity","pfl_lvcity_name","pfl_lvcity_cncode","pfl_nocity","pfl_gender","pfl_gender_mod_rem","pfl_dmod","pfl_dmod_tstamp","acc_psd","acc_psd_dmod","acc_psd_dmod_tstamp","acc_eml","acc_lang","acc_lang_dmod","acc_lang_dmod_tstamp","acc_pwd","acc_pwd_dmod","acc_pwd_dmod_tstamp","acc_crea_locip","acc_dcrea","acc_dcrea_tstamp","acc_todelete","secu_staycon","secu_coWithPsdEna","secu_isThirdCritEna","secu_notifyWhenLogin"];
        $this->needed_to_loading_prop_keys = ["accid","acc_eid","acc_grp","pfl_fn","pfl_bdate","pfl_bdate_tstamp","pfl_bdate_mod_rem","pfl_lvcity","pfl_lvcity_name","pfl_lvcity_cncode","pfl_nocity","pfl_gender","pfl_gender_mod_rem","pfl_dmod","pfl_dmod_tstamp","acc_psd","acc_psd_dmod","acc_psd_dmod_tstamp","acc_eml","acc_lang","acc_lang_dmod","acc_lang_dmod_tstamp","acc_pwd","acc_pwd_dmod","acc_pwd_dmod_tstamp","acc_crea_locip","acc_dcrea","acc_dcrea_tstamp","acc_todelete","secu_staycon","secu_coWithPsdEna","secu_isThirdCritEna","secu_notifyWhenLogin"];
        
        $this->needed_to_create_prop_keys = ["ins_fn","ins_nais","ins_gdr","ins_cty","ins_psd","ins_eml","ins_pwd","locip","entercz","xtras"];
        
        //Récupération de prodconf_table si une SESSION existe. Sinon on prend l'adresse email par défaut
        $this->prodconf_table = ( isset($_SESSION) && key_exists("sto_infos", $_SESSION) && isset($_SESSION["sto_infos"]) && ($_SESSION["sto_infos"] instanceof SESSION_TO) ) ? $_SESSION["sto_infos"]->getProd_xmlscope() : NULL;
        
        /**************** RULES ****************/
        $this->rgx_fn = "/^[a-z\-\+\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,25}$/i";
        $this->rgx_bd = "/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
        $this->bd_limit = 12;
        $this->rgx_gdr = "/^(f|m)$/i";
        $this->rgx_psd = "/^[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i";
        $this->psd_min = 2;
        $this->psd_max = 20;
        $this->rgx_email = "/^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i";
        $this->email_max = 256;
        $this->rgx_pwd = "/^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°()\[\]\-@#$%:;=''\/\\¤]).{6,32}$/i";
        $this->pwd_min = 6;
        $this->pwd_max = 32;
        $this->INS_DEFAULT_LNG = "fr";
        $this->INS_DEFAULT_GRP = 2;
        $this->rgx_lng = "/^[a-z]{2}$/i";
        /*
         * [DEPUIS 11-07-16]
         *      Ajout des valeurs correspondantes aux LANG à venir : en, us, es, de
         */
        $this->TQR_AVAL_LANG = ["fr","en","us","es","de"];
        $this->DEL_HIKW = ["SCHOOL","WORKPL","RELATIVE","SOCNET","WEBSIT","MEDIA"];
        $this->DEL_YILV = ["MSFUNC","MSPHONE","MSENTOURAGE","ERRNBG","MSFAV","DESIGN","CONCEPT","HTRUN","OTHER"];
        $this->rgx_yilv_ot = "/^(?=.*[a-z]).{4,242}$/i";
        $this->rgx_ilbbif = "/^(?=.*[a-z]).{4,242}$/i";
        
        $this->_CPTL_START = 0;
        
        /****** REC ******/
        $this->rgx_code =  "/^[a-z\d]{6}$/i";
        
        $this->TDL_INTVAL = 30*86400000;
//        $this->TDL_INTVAL = 5*60000; //DEV, TEST, DEBUG
        $this->REC_INTVAL = 1*86400000;
        $this->EML_DEFAULT = "Trenqr <noreply@trenqr.com>";
        
        $this->_ENTERCZ = [
            "ENTERCZ_DIRECT"        => 1,
            "ENTERCZ_PREFORM"       => 2,
            "ENTERCZ_ACTIVE_FB_SSN" => 3,
            "ENTERCZ_INSAPI_FB"     => 4
        ];
        
    }

    protected function build_volatile($args) { }

    public function exists($ueid, $check_todelete = FALSE) {
        /*
         * Vérifie si un compte existe en utilisant l'identifiant externe passé en paramètre.
         * Dans le cas où le compte existe, on renvoie les données de la table. 
         * Dans le cas contraire, on renvoie FALSE.
         * 
         * Cette version de la méthode utilise "eid"
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $ueid);
        
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tqraccn10");
        $params = array(":ueid" => $ueid, ":now" => $now);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
        
    }

    public function exists_with_id($uid, $check_todelete = FALSE) {
        /*
         * Vérifie si un compte existe en utilisant l'identifiant externe passé en paramètre.
         * Dans le cas où le compte existe, on renvoie les données de la table. 
         * Dans le cas contraire, on renvoie FALSE.
         * 
         * Cette version de la méthode utilise "id"
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tqraccn9");
        $params = array(":uid" => $uid, ":now" => $now);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }

    protected function init_properties($datas) {
        /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
        
//        $diff = array_diff($this->prop_keys,  array_keys($datas));
//        var_dump($this->prop_keys,array_keys($datas),$diff);
//        exit();
        
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
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        /* On vérifie si on a l'identifiant. Sinon on tente de prendre celui déjà chargé s'il existe */
        $acc_eid;
        if (! ( !empty($args) && is_array($args) && key_exists("acc_eid", $args) && !empty($args["acc_eid"]) ) ) 
        {
            if ( empty($this->acc_eid) )
                $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
            else 
                $acc_eid = $this->acc_eid;
        } else $acc_eid = $args["acc_eid"];
        
        $acc_tab = $this->exists($acc_eid);
        
        if ( !$acc_tab && $std_err_enabled ) {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__,TRUE);
        } else if ( !$acc_tab && !$std_err_enabled ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        $loads = [
            //Données de COMPTE
            "accid"                 => $acc_tab["accid"],
            "acc_eid"               => $acc_tab["acc_eid"],
            "acc_psd"               => $acc_tab["acc_psd"],
            "acc_psd_dmod"          => $acc_tab["acc_psd_dmod"],
            "acc_psd_dmod_tstamp"   => $acc_tab["acc_psd_dmod_tstamp"],
            "acc_lang"              => $acc_tab["acc_lang"],
            "acc_lang_dmod"         => $acc_tab["acc_lang_dmod"],
            "acc_lang_dmod_tstamp"  => $acc_tab["acc_lang_dmod_tstamp"],
            "acc_pwd"               => $acc_tab["acc_pwd"],
            "acc_pwd_dmod"          => $acc_tab["acc_pwd_dmod"],
            "acc_pwd_dmod_tstamp"   => $acc_tab["acc_pwd_dmod_tstamp"],
            "acc_crea_locip"        => $acc_tab["acc_crea_locip"],
//            "acc_pflbio" => $acc_tab["acc_pflbio"],
//            "acc_pflbio_dmod" => $acc_tab["acc_pflbio_dmod"],
//            "acc_pflbio_dmod_tstamp" => $acc_tab["acc_pflbio_dmod_tstamp"],
            "acc_dcrea"             => $acc_tab["acc_dcrea"],
            "acc_dcrea_tstamp"      => $acc_tab["acc_dcrea_tstamp"],
            "acc_dcrea_tstamp"      => $acc_tab["acc_dcrea_tstamp"],
            "acc_todelete"          => $acc_tab["acc_todelete"],
            //Données EMAIL
            "acc_eml"               => $acc_tab["emhy_email"],
            //Données de PROFIL
            "pfl_fn"                => $acc_tab["pfl_fn"],
            "pfl_bdate"             => $acc_tab["pfl_bdate"],
            "pfl_bdate_tstamp"      => $acc_tab["pfl_bdate_tstamp"],
            "pfl_bdate_mod_rem"     => $acc_tab["pfl_bdate_mod_rem"],
            "pfl_lvcity"            => $acc_tab["pfl_lvcity"],
            "pfl_lvcity_name"       => $acc_tab["pfl_lvcity_name"],
            "pfl_lvcity_cncode"     => $acc_tab["pfl_lvcity_cncode"],
            "pfl_nocity"            => $acc_tab["pfl_nocity"],
            "pfl_gender"            => $acc_tab["pfl_gender"],
            "pfl_gender_mod_rem"    => $acc_tab["pfl_gender_mod_rem"],
            "pfl_dmod"              => $acc_tab["pfl_dmod"],
            "pfl_dmod_tstamp"       => $acc_tab["pfl_dmod_tstamp"],
            //Données de SECURITE
            "secu_staycon"          => $acc_tab["secu_staycon"],
            "secu_coWithPsdEna"     => $acc_tab["secu_coWithPsdEna"],
            "secu_isThirdCritEna"   => $acc_tab["secu_isThirdCritEna"],
            "secu_notifyWhenLogin"  => $acc_tab["secu_notifyWhenLogin"]
        ];
        
        /****************************** EXTRA DATAS ******************************/
        //*
        $xts_datas = ["acc_grp"];
        
        foreach ($xts_datas as $k) {
            $loads[$k] = $this->PullExtraDatas($k,$acc_tab["accid"]);
        }
        //*/
     
        $this->init_properties($loads);
        $this->is_instance_load = TRUE;
        return $loads;
    }

    
    protected function on_alter_entity($args) { }

    
    public function on_create_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        //On vérifie la présence des données obligatoires : ["ins_fn","ins_nais","ins_gdr","ins_cty","ins_psd","ins_eml","ins_pwd","locip","entercz","xtras"]
        $com = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
//                if (! ( !is_array($v) && !empty($v) ) ) { //[DEPUIS 03-07-16]
                if ( !( !is_array($v) && !empty($v) ) && !in_array($k,["xtras"]) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        foreach ( $args as $k => $v ) {
//            if ( $k === "locip" ) { //[DEPUIS 03-07-16]
            if ( in_array($k,["locip","entercz","xtras"]) ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
                    $wrg_datas[] = [$k,$v];
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return "__ERR_VOL_WRG_DATAS";
        }
        
        //On hash le mot de passe
        $args["ins_pwd"] = $this->hash_input_passwd($args["ins_pwd"]);
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        $f_ = explode('-', $args["ins_nais"]);
        $f__ = mktime(0, 0, 0, $f_[0], $f_[1], $f_[2]);
        //On met la date sous format TIMESTAMP
        $args["ins_nais_tstamp"] = $f__;
        //On crée une date avec le bon format
        $b_ = getdate($f__);
        $args["ins_nais"] = $b_["year"]."-".$b_["mon"]."-".$b_["mday"];
        
//        var_dump($args["ins_eml"]);
//        var_dump($args);
//        exit();
        
        $email = $args["ins_eml"];
        //RAPPEL : La probabilité que le retour soit NULL est presque impossible.
        if (! $this->Email_Exists($email) ) {
            //Insertion de l'email
            $r_ = $this->InsertEmail($email);
            if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) {
                return "__ERR_VOL_FAILED_ON_EML";
            }
        }
        
        //On récupère les données sur la localisation
        $loc_tab = $this->GetLocationInfos($args["ins_cty"]);
        if (! $loc_tab ) {
            return "__ERR_VOL_UKW_CITY";
        }
        $args["ins_cn"] = $loc_tab["ctr_code"];
        
        /*
         * [DEPUIS 03-07-16] @RLC
         */
        //CAS ENTERCZ
        $EN_VLS = array_keys($this->_ENTERCZ);
        if ( !in_array($args["entercz"], $EN_VLS) ) {
            $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
        } 
        $entercz = $args["entercz"];
        $args['entercz_id'] = $this->_ENTERCZ[$entercz];
            
//        var_dump(__LINE__,__FUNCTION__,__LINE__,$args);
//        exit();
        
        //On crée l'occurrence ACCOUNT
        $ids = $this->write_new_in_database($args);
        
        $this->oncreate_copytoproduct($ids[0]);
        
        //On envoie les emaux
        $this->oncreate_emailit($ids[0]);
                
//        exit();
        $acc_eid = $ids[1];
        return $this->load_entity(["acc_eid" => $acc_eid], $std_err_enabled);
    }
    
    protected function on_delete_entity($args) {
        
    }

    public function on_read_entity($args, $std_err_enabled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $pdacc_eid = $pdaccid = NULL;
        if (! ( !empty($args) && is_array($args) && key_exists("acc_eid", $args) && !empty($args["acc_eid"]) ) )
        {
            if ( key_exists("accid", $args) && !empty($args["accid"]) && !is_array($args["accid"]) ) {
                $pdaccid = $args["accid"];
            } else if ( !empty($this->pdaccid) ) 
                $pdaccid = $this->pdaccid;
            else {
                if ( empty($this->pdacc_eid) )
                    $this->signalError ("err_sys_l4comn2", __FUNCTION__, __LINE__);
                else 
                    $pdacc_eid = $this->pdacc_eid;
            }
            
        } else $pdacc_eid = $args["acc_eid"];
        
        if ( !isset($pdacc_eid) || empty($pdacc_eid) ) {
            $r = $this->onread_get_acceid_from_accid($pdaccid);
            
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) )
                return $r;
            
            $args["acc_eid"] = $r;
        }
        
        $loads = $this->load_entity($args, $std_err_enabled);
        
        return $loads;
    }

    protected function write_new_in_database($args) {
        /*
         * On crée l'occurrence du Compte dans la base ACCOUNT
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $now = round(microtime(TRUE)*1000);
        //Création du compte au niveau de la base de données ACCOUNT
        $QO = new QUERY("qryl4insn11");
        $params = array(
            ":ufn"          => $args["ins_fn"], 
            ":udb"          => $args["ins_nais"], 
            ":ubd_stp"      => $args["ins_nais_tstamp"], 
            ":ulvcty"       => $args["ins_cty"], 
            ":ugdr"         => $args["ins_gdr"], 
            ":upsd"         => $args["ins_psd"], 
            ":ulng"         => $this->INS_DEFAULT_LNG, 
            ":upwd"         => $args["ins_pwd"], 
            ":clocip"       => $args["locip"], 
            ":cdate_stp"    => $now,
            ":ins_entercz"  => $args["entercz_id"], 
            
        );
        $uid = $QO->execute($params);
        
        //Acquisition de l'eid
        $ueid = $this->tqr_create_ueid($args["ins_nais_tstamp"], $args["ins_gdr"], $args["ins_cn"], $uid);
        
        //Mise à jour de l'occurrence de COMPTE
        $QO = new QUERY("qryl4insn12");
        $params = array( ":uid" => $uid, ":ueid" => $ueid );
        $QO->execute($params);
        
        //On met en place les liens entre le compte et l'email
        $this->Email_LinkNewlyEmail($args["ins_eml"],$uid);
        
        //On inscrit l'utilisateur à un groupe
        /*
         * Le groupe sera toujours celui lambda.
         * Pour l'instant les autres groupes ne sont pas vraiment actifs. Aussi, on verra pour le reste plus tard.
         */
        $this->JoinGroup($uid);
        
        $ids = [$uid,$ueid];
        //On renvoie les ids
        return $ids;
    }

    /*****************************************************************************************************************************************************/
    /**************************************************************** SPECIFIC SCOPE *********************************************************************/
    /*****************************************************************************************************************************************************/
    
    private function oncreate_copytoproduct ($uid) {
        /*
         * On crée l'occurrence dans la base PRODUCT.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $now = round(microtime(TRUE)*1000);
        
        //Acquisition des données sur le Compte depuis la base ACCOUNT
        $acc_tab = $this->exists_with_id($uid);
        if (! $acc_tab ) {
            return "__ERR_VOL_UXPTD";
        }
        
        //Acquisition des données sur la localisation
        $loc_tab = $this->GetLocationInfos($acc_tab["pfl_lvcity"]);
        if (! $loc_tab ) {
            return "__ERR_VOL_UKW_CITY";
        }
        
        //Ajout de l'occurrence dans la base PRODUCT
        $pdacc_args = [
            "accid"                 => $acc_tab["accid"],
            "acc_eid"               => $acc_tab["acc_eid"],
            "acc_gid"               => $this->INS_DEFAULT_GRP,
            "acc_upsd"              => $acc_tab["acc_psd"],
            "acc_ufn"               => $acc_tab["pfl_fn"],
            "acc_ugdr"              => $acc_tab["pfl_gender"],
            "acc_ucityid"           => $loc_tab["city_id"],
            "acc_ucity_fn"          => $loc_tab["asciiname"],
            "acc_nocity"            => "",
            "acc_ucnid"             => $loc_tab["ctr_code"],
            "acc_ucn_fn"            => $loc_tab["ctr_name"],
            "acc_udl"               => $this->INS_DEFAULT_LNG,
            "acc_datecrea"          => date("Y-m-d",($now/1000)),
            "acc_datecrea_tstamp"   => $now,
            "acc_capital"           => $this->_CPTL_START
        ];
        
        //["accid","acc_gid","acc_eid","acc_upsd","acc_ufn","acc_ucityid","acc_ucity_fn","acc_nocity","acc_ucnid","acc_ucn_fn","acc_udl","acc_datecrea","acc_datecrea_tstamp","acc_capital"]
        $PDACC = new PROD_ACC();
        $r_ = $PDACC->on_create_entity($pdacc_args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) {
            return $r_;
        }
    }
    
     
    private function oncreate_emailit($uid) {
        /*
         * Permet d'envoyer au tout nouveau propriétaire du compte, tous les emaux inhérents à la création de son compte.
         * Liste des emails :
         *      -> Confirmation de la création du compte
         *      -> Don de capital
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
         
         $exp = ( isset($this->prodconf_table) && $this->prodconf_table["prod_email_table"]["email_welcome"] ) ? $this->prodconf_table["prod_email_table"]["email_welcome"] : $this->EML_DEFAULT;
         $acc_tab = $this->on_read_entity(["accid" => $uid]);
         
         /*****************************************************************************************/
         //**************************** On envoie l"email : WELCOME *******************************/
         
         //Envoi de l'email de bienvenue
         $EMH = new EMAILAC_HANDLER();
         $args_eml = [
            "exp"       => htmlspecialchars_decode($exp),
//            "rcpt" => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
            "rcpt"      => $acc_tab["acc_eml"],
            "rcpt_uid"  => $acc_tab["accid"],
            "catg"      => "USER_ACTION"
        ];
         
//        var_dump($args_eml,$rec_link);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
        
        $args_eml_marks = [
            "fullname"  => $acc_tab["pfl_fn"],
            "pseudo"    => $acc_tab["acc_psd"],
            /*
             * [DEPUIS 29-08-15] @author BOR
             */
            "trenqr_http_root"          => HTTP_RACINE,
            "trenqr_login_link"         => HTTP_RACINE."/login",
            "trenqr_start_rcvy_link"    => HTTP_RACINE."/recovery/password",
            "trenqr_prod_img_root"      => WOS_SYSDIR_PRODIMAGE
        ];
        $r_ = $EMH->emac_send_email_via_model("emdl_newaccn1", "fr", $args_eml, $args_eml_marks);
        if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
            return "__ERR_VOL_FAILED";
        }
         
         /*********************************************************************************************/
         //****************************** On envoie l"email : DONATION ********************************/
         /* // [DEPUIS 29-08-15] @author BOR
         //Envoi de l'email de bienvenue
         $EMH = new EMAILAC_HANDLER();
         $args_eml = [
            "exp"       => htmlspecialchars_decode($exp),
//            "rcpt"    => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
            "rcpt"      => $acc_tab["acc_eml"],
            "rcpt_uid"  => $acc_tab["accid"],
            "catg"      => "USER_ACTION"
        ];
         
//        var_dump($args_eml,$rec_link);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
        
        $args_eml_marks = [
            "fullname"  => $acc_tab["pfl_fn"],
            "pseudo"    => $acc_tab["acc_psd"],
            "clcoins"   => $this->_CPTL_START
        ];
        $r_ = $EMH->emac_send_email_via_model("emdl_doncapn1", "fr", $args_eml, $args_eml_marks);
        if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
            return "__ERR_VOL_FAILED";
        }
        */
        return TRUE;
         
    }
    
    
    /************************************* GENERAL SECURITY *************************************/
    
    public function CheckField ($fld_n, $fld_v) {
        /*
         * Permet de vérifier la validité de certaines données necessaires à la création d'un Compte.
         * La méthode n'effectue pas d'opérations poussées comme pourrait le faire FE.
         * Elle se contente de dire si les champs sont valides ou non.
         */
        if (! in_array($fld_n, ["yilv_ot","ilbbif"]) ) {
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        }
        
        //IsValid
        $iv = FALSE;
        switch ($fld_n) {
            case "ins_fn" :
                    //On utilise une des fonctions de TQRACC. Plutot que la recréer
                    if ( preg_match($this->rgx_fn, $fld_v) ) {
                        //On vérifie si le NomComplet ne contient pas des mots interdits ou reservé est disponible
                        if ( !$this->Fullname_IsDenied($fld_v) ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "ins_nais" :
                    /*
                     * La date doit être sous format : m-d-Y
                     */
//                        var_dump(preg_match($this->rgx_bd, "02-02-91"));
                    
//                        var_dump($fld_v);
//                        $fld_v = "02-28-2002";
                        //On vérifie si l'age limite est atteinte 
                        $bd_d = intval(explode("-", $fld_v)[1]);
                        $bd_m = intval(explode("-", $fld_v)[0]);
                        $bd_y = intval(explode("-", $fld_v)[2]);
                        
//                        var_dump($bd_d,$bd_m,$bd_y);
//                    if ( preg_match($this->rgx_bd, $fld_v) ) {    
                    if ( checkdate($bd_m,$bd_d,$bd_y) ) {    
                        $f__ = intval($bd_y)+$this->bd_limit;
                        $gt = mktime(0, 0, 0, $bd_m, $bd_d, $f__);
                        $now = (new DateTime())->getTimestamp();
                        
                        $df = $now - $gt ;
                        
                        if ( $df > 0 ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "ins_nais_tstamp" :
                    set_error_handler('exceptions_error_handler');
                    try {
                        $d = getdate($fld_v);
                        if ( $d ) {
                            $t__d = intval(date("d",$fld_v)); 
                            $t__m = intval(date("m",$fld_v)); 
                            $t__y = intval(date("Y",$fld_v))+$this->bd_limit;
                            
                            $gt = mktime(0, 0, 0, $t__m, $t__d, $t__y);
                            $now = (new DateTime())->getTimestamp();
                            
                            $df = $now - $gt ;
//                            var_dump(541,$now,$gt,$df);
                            if ( $df > 0 ) {
                                $iv = TRUE;
                            }
                        }
                    } catch (Exception $ex) {
                    }
                break;
            case "ins_gdr" :
                    if ( preg_match($this->rgx_gdr, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "ins_cty" :
                    //On vérifie si l'identifiant est connu de la base de donnnées
                    if ( $this->GetLocationInfos($fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "ins_psd" :
                    if ( preg_match($this->rgx_psd, $fld_v) ) {
                        //On vérifie si le pseudo est disponible
                        if ( !$this->Pseudo_IsUsed($fld_v) && !$this->Pseudo_IsReserved($fld_v) && !$this->Pseudo_IsDenied($fld_v) ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "ins_eml" :
                    //L'email respect-il le format d'un email
                    $tplchk = $this->Email_TripleCheck($fld_v);
                    $used = $this->Email_Used($fld_v);
                    if ( !$this->return_is_error_volatile(__FUNCTION__, __LINE__, $tplchk) && !$used ) {
                        $iv = TRUE;
                    }
                break;
            case "ins_lng" :
                    //La langue fournie respect-elle le format et les langues gérées
                    if ( preg_match($this->rgx_lng, $fld_v) && in_array($fld_v, $this->TQR_AVAL_LANG) ) {
                        $iv = TRUE;
                    }
                break;
            case "ins_pwd" :
                    /*
                     * Le password ne doit pas avoir été hashé avant d'essayer de le valider.
                     * En effet, cela pourrait fausser la vérification.
                     */
                    if ( preg_match($this->rgx_pwd, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "sec_ecwpsd" :
                    /*
                     * Connexeion avec Pseudo autorrisé ?
                     */
                     if ( in_array($fld_v, ["chk","uchk"])) {
                         $iv = TRUE;
                     }
                break;
//                ["hikw","yilv","yilv_ot","ilbbif"]
            case "hikw" :
                    if ( in_array($fld_v, $this->DEL_HIKW) ) {
                        $iv = TRUE;
                    }
                break;
            case "yilv" :
                    if ( in_array($fld_v, $this->DEL_YILV) ) {
                        $iv = TRUE;
                    }
                break;
            case "yilv_ot" :
                    if ( $fld_v === "" ) {
                        $iv = TRUE;
                    } else if ( $fld_v !== "" && preg_match($this->rgx_yilv_ot, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "ilbbif" :
                    if ( $fld_v === "" ) {
                        $iv = TRUE;
                    } else if ( $fld_v !== "" && preg_match($this->rgx_ilbbif, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            default:
                    return;
                break;
        }
        
        return $iv;
    }
    
    /****************************************** PASSWORD ****************************************/
    
    public function hash_input_passwd($passwd){
        /*
         * Permet de hasher le mot de passe via l'utilisation du service PASSHASH_HDLR.
         * Ce dernier n'est qu'un dérivé de PHPASS
         *
         * http://stackoverflow.com/questions/401656/secure-hash-and-salt-for-php-passwords
         * http://www.openwall.com/phpass/
         * http://sunnyis.me/blog/secure-passwords/
         * 
         */

        //On initialise notre objet
        $hasher = new srvc_PasswordHash_handler();
        $hasher->PasswordHash(8, FALSE);
        //On hashe le password
        $hashedPw = $hasher->HashPassword($passwd);
        //On vérifie que le hash fait plus de 20char de long (si non, il y a eu un problème)
        if ( strlen($hashedPw) < 20 ) {
            $this->signalError ("err_sys_l4pdaccn1", __FUNCTION__, __LINE__,TRUE);
//            $this->get_or_signal_error(1, 'custom_err_account_pwd_hash_error', __FUNCTION__, __LINE__);
            return FALSE;
        } else {
            return $hashedPw;
        }
    }

    public function compare_hashed_passwd($user_input, $stored_hash){
        $hasher = new srvc_PasswordHash_handler();
        $hasher->PasswordHash(8, FALSE);
        $checked = $hasher->CheckPassword($user_input, $stored_hash);
        if($checked){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /******************************************* EMAIL ******************************************/
    
    public function Email_TripleCheck ($email) {
        /*
         * Permet de vérifier la validitité d'une adresse email en se basant sur trois vérifications :
         *  (1) Format de l'email
         *  (2) Est ce que le dom de l'email passé en paramètre répond aux requetes 
         *  (3) Est ce que le dom de l'email passé en paramètre est interdit ? 
         * 
         * Attention : Pour rappel, la méthode ne vérifie pas si l'email est déjà repertorié dans la base de données
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $email);
        
        //Format de l'email
        if (! $this->Email_ChkFormat($email) ) {
            return "__ERR_VOL_EML_FORMAT";
        }
        
        //Vérification des requêtes DNS
        if (! $this->Email_CheckDns($email) ) {
            return "__ERR_VOL_EML_DNS";
        }
        
        //Vérification si le domaine est banni
        if ( $this->Email_IsBan($email) ) {
            return "__ERR_VOL_EML_BAN";
        }
        
        return TRUE;
        
    }
    
    public function Email_ChkFormat ($email) {
        /*
         * Permet de vérifier si l'email passé en paramètre est valide.
         * Pour cela, on vérifie si 
         *  (1) Il s'agit d'une chaine de caractères
         *  (2) Il s'agit d'un email (format)
         * 
         * La méthode renvoie une erreur de type volatile en cas de non conformité ou TRUE.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! is_string($email) ) {
            return "__ERR_VOL_STR";
        } else if ( count($email) > $this->email_max ) {
            return "__ERR_VOL_MAX";
        } else if (! preg_match($this->rgx_email, $email) ) {
            return "__ERR_VOL_MISM";
        }
            
        return TRUE;    
    }
    
    public function Email_Exists ($email) {
        /*
         * Permet de vérifier si une adresse email existe dans la table des emaux archivés.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $email);
        
        //On vérifie s'il s'agit d'un email
        $f__ = $this->Email_ChkFormat($email);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $f__) ) {
            return $f__;
        }
        
//        var_dump($email,$this->Email_ChkFormat($email));
        $datas = NULL;
        $QO = new QUERY("qryl4tqraccn4");
        $params = array(':email' => $email);
        $datas = $QO->execute($params);
        
        if (! $datas ) { 
            return FALSE;
        } else  {
            return $datas[0];
        }
    }
    
    public function Email_Used ($email) {
        /*
         * Permet de vérifier si une adresse email est disponible.
         * Pour cela on regarde dans la table des emaux en cours d'utilisation qui offre de bonnes performances.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $email);
        
        //On vérifie s'il s'agit d'un email
        $f__ = $this->Email_ChkFormat($email);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $f__) ) {
            return $f__;
        }
        
//        var_dump($email,$this->Email_ChkFormat($email));
        $datas = NULL;
        $QO = new QUERY("qryl4tqraccn3");
        $params = array(':email' => $email);
        $datas = $QO->execute($params);
        
        if (! $datas ) { 
            return FALSE;
        } else  {
            return $datas[0];
        }
    }
    
    public function Email_CheckDns ($email) {
        /* 
         * Vérifie aussi si le domaine lié à l'email passé en paramètre est disponible.
         * Pour cela, on tente une résolution DSN.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $email);
        
        //On vérifie s'il s'agit d'un email
        if (! $this->Email_ChkFormat($email) ) 
            return;
        
        //On récupère le domaine à partir de l'adresse email
        $dom = explode("@", $email)[1];
        
        $r = checkdnsrr($dom,"MX");
        
        return $r;
    }
    
    public function Email_IsBan ($email) {
        /*
         * Permet de vérifier si le nom de domaine lié à l'émail est banni ou interdit pour ajout.
         * La plupart du temps, un nom de domaine est banni pour les raisons suivantes :
         *  (1) Risques en termes de sécurité
         *  (2) Problème de fiabilité
         *  (3) Qualité de service 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $email);
        
        //On vérifie s'il s'agit d'un email
        if (! $this->Email_ChkFormat($email) ) 
            return;
        
        //On récupère le domaine à partir de l'adresse email
        $dom = explode("@", $email)[1];
        
        $datas = NULL;
        $QO = new QUERY("qryl4insn8");
        $params = array(':dom' => $dom);
        $datas = $QO->execute($params);
        
        return ( $datas ) ? TRUE : FALSE;
    }
    
    public function InsertEmail ($email) {
        /*
         * Insère l'email dans la table archivant les emaux.
         * Le but final de ce genre d'action est de lier l'email à un compte.
         * 
         * En outre, la méthode effectue la triple vérification :
         *  (1) Vérification du format
         *  (2) DNS du domaine
         *  (3) Dom est banni ?
         * Enfin, on vérifie si l'émail n'est pas déjà enregistré.
         */
        
        //On vérifie la validité de l'email via la triple vérification
        $b__ = $this->Email_TripleCheck($email);
        if ( $b__ !== TRUE ) {
            return $b__;
        }
        
        //On vérifie si l'email n'est pas déjà pris
        $f__ = $this->Email_Exists($email);
        if ( is_array($f__) && count($f__) ) {
            return "__ERR_VOL_ALDY_EXSTS";
        }
//        var_dump($f__);
//        exit();
        //On ajoute l'emailtstamp
        $now = round(microtime(TRUE)*1000);
        $eml_login = explode('@', $email)[0];
        $eml_dom = explode('@', $email)[1];
        
        $QO = new QUERY("qryl4insn10");
        $params = array(":eml_raw" => $email, ":eml_login" => $eml_login, ":eml_dom" => $eml_dom, ":tstamp" => $now);
        $QO->execute($params);
        
        //RAPPEL : La probabilité que le retour soit NULL est presque impossible.
        $eml_tab = $this->Email_Exists($email);
        
        return $eml_tab;
        
    }
    
    private function Email_LinkNewlyEmail ($email, $uid) {
        /*
         * Permet d'ajouter des occurences dans certaines tables qui servent pour :
         *  (1) Associer une adresse emaux à un compte.
         *  (2) Rechercher les email actifs pour les protéger contre toute nouvelle inscription.
         * 
         * ATTENTION : La méthode n'effectue pas d'opérations sécurisées dans le sens où elle ne vérifie pas avant d'opérer.
         * A ce titre, elle ne se réfère qu'aux erreurs déclenchées.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $now = round(microtime(TRUE)*1000);
        
        /*
         * On récupère les données via l'id ce qui nous permet aussi de nous assurer que l'identifiant est valide.
         * On récupère les données que l'on va insérer dans la table de recherche.
         * Cependant, la probabilité reste NULLE (ou presque) car l'identifiant provient d'un ajout de Compte récent.
         */
        $QO = new QUERY("qryl4tqraccn1");
        $params = array(":uid" => $uid);
        $t__ = $QO->execute($params);
        
        if (! $t__ ) {
            return "__ERR_VOL_ACC_GONE";
        } else {
            $acc_tab = $t__[0];
        }
        
        //On associe l'Email et le Compte
        $QO = new QUERY("qryl4insn13");
        $params = array(":eml_raw" => $email, ":accid" => $uid, ":tstamp" => $now);
        $id1 = $QO->execute($params);
        
        //Insertion dans la table de recherche
        $eml_login = explode("@", $email)[0];
        $eml_dom = explode("@", $email)[1];
        
        $QO = new QUERY("qryl4insn14");
        $params = array(":eml_raw" => $email, ":eml_login" => $eml_login, ":eml_dom" => $eml_dom, ":tstamp" => $now, ":uid" => $acc_tab["accid"], ":ueid" => $acc_tab["acc_eid"], ":ufn" => $acc_tab["pfl_fn"], ":upsd" => $acc_tab["acc_psd"]);
        $id2 = $QO->execute($params);
        
        $ids = [$id1,$id2];
        return $ids;
    }
    
    private function Email_UpdateUserEmlLink ($old_email, $new_email, $acc_tab, $addimsg = FALSE) {
        /*
         * AddIMsg (AddIfMissing) : Spécifie qu'il faut ajouter l'email dans la table d'archive si elle n'y est pas.
         * Dans le cas contraire, on renvoie une erreur volatile.
         */
        /*
         * Permet de créer une nouvelle connexion entre un email existant et un utilisateur.
         * Cette méthode est interessante dans le cas d'un changement d'un email de référence.
         * 
         * La méthode ne permet pas d'effecutuer (pour des raisons de performance et bcz la probabilité que le controle ait déjà été réalisé est grande) ...
         * ...des controles de sécurité.
         * Pour les mêmes raisons, la méthode ne vérifie pas si l'utilisateur existe ou est que son compte est actif.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie que l'email est bien présent dans la table Archive
        if ( !$this->Email_Exists($new_email) && $addimsg ) {
            //Insertion de l'email
            $r_ = $this->InsertEmail($new_email);
            if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) {
                return "__ERR_VOL_FAILED_ON_EML";
            }
        } else if ( !$this->Email_Exists($new_email) && !$addimsg ) {
            return "__ERR_VOL_EML_NOARCHD";
        }
        
        $now = round(microtime(TRUE)*1000);
        
        //On modifie le lien dans la table histy entre l'user et l'ancien email
        $QO = new QUERY("qryl4tqraccn16");
        $params = array(":email" => $old_email, ":accid" => $acc_tab["accid"], ":now" => date("Y-m-d",($now/1000)), ":tstamp" => $now);
        $QO->execute($params);
        
        //On retire l'email dans la table SRH entre l'user et l'ancien email
        $QO = new QUERY("qryl4tqraccn17");
        $params = array(":email" => $old_email, ":accid" => $acc_tab["accid"]);
        $QO->execute($params);
        
        //On associe l'Email et le Compte
        $QO = new QUERY("qryl4insn13");
        $params = array(":eml_raw" => $new_email, ":accid" => $acc_tab["accid"], ":tstamp" => $now);
        $id1 = $QO->execute($params);
        
        //Insertion dans la table de recherche
        $eml_login = explode("@", $new_email)[0];
        $eml_dom = explode("@", $new_email)[1];
        
        $QO = new QUERY("qryl4insn14");
        $params = array(":eml_raw" => $new_email, ":eml_login" => $eml_login, ":eml_dom" => $eml_dom, ":tstamp" => $now, ":uid" => $acc_tab["accid"], ":ueid" => $acc_tab["acc_eid"], ":ufn" => $acc_tab["pfl_fn"], ":upsd" => $acc_tab["acc_psd"]);
        $id2 = $QO->execute($params);
        
        $ids = [$id1,$id2];
        return $ids;
        
    }
    
    /*************************/
    
    private function JoinGroup ($accid, $gid = 2) {
        /*
         * Permet d'inscrire un utilisateur à un groupe donné.
         */
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4insn17");
        $params = array(":uid" => $accid, ":gid" => $gid, ":tstamp" => $now);
        $id = $QO->execute($params);
        
        return $id;
    }
    
    
    public function Pseudo_IsUsed ( $psd ) {
        /*
         * Permet de vérifier si un pseudo est disponible.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! is_string($psd) )
            return;
        
        $datas;
        $QO = new QUERY("qryl4insn6");
        $params = array(':sqy' => $psd);
        $datas = $QO->execute($params);

        if (! $datas ) { 
            return FALSE;
        } else  {
            return $datas[0];
        }
    }
    
    public function Pseudo_IsReserved ($psd) {
        /*
         * Permet de vérifier si un pseudo est réservé.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas;
        $QO = new QUERY("qryl4insn16");
        $params = array(':psd' => $psd);
        $datas = $QO->execute($params);

        if (! $datas ) { 
            return FALSE;
        } else  {
            return $datas[0];
        }
    }
    
    public function Fullname_IsDenied ($fn) {
        /*
         * Permet de vérifier si un NOmComplet contient des mots qui sont refusés.
         * Cela permet par exemple d'interdir des pseudos contenant le mot "trenqr".
         * Cette dernière interdiction est faire pour des raisons de sécurtié, fiabilité et de protection de marque.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas;
        $QO = new QUERY("qryl4insn20");
        $datas = $QO->execute(NULL);
//        var_dump($datas);
        if ( $datas ) {
            foreach ($datas as $v) {
                $rgx = "#".$v["dnyfn_fn"]."#i";
//                var_dump($v["dnypsd_psd"],$psd,preg_match($rgx, $psd));
//                if ( strpos($v["dnypsd_psd"],$psd) !== FALSE ) {
                if ( preg_match($rgx, $fn) ) {
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    public function Pseudo_IsDenied ($psd) {
        /*
         * Permet de vérifier si un pseudo contient des mots qui sont refusés.
         * Cela permet par exemple d'interdir des pseudos contenant le mot "trenqr".
         * Cette dernière interdiction est faire pour des raisons de sécurtié, fiabilité et de protection de marque.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas;
        $QO = new QUERY("qryl4insn18");
        $datas = $QO->execute(NULL);
//        var_dump($datas);
        if ( $datas ) {
            foreach ($datas as $v) {
                $rgx = "#".$v["dnypsd_psd"]."#i";
//                var_dump($v["dnypsd_psd"],$psd,preg_match($rgx, $psd));
//                if ( strpos($v["dnypsd_psd"],$psd) !== FALSE ) {
                if ( preg_match($rgx, $psd) ) {
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    /********************* OTHERS **********************/
    
    /**
    * Function used to create user external ID's based on their birthday (m-y), gender, and country AT THE TIME OF ACCOUNT CREATION.
    * 
    * In the output string, the character 'n' will be used as a delimiter between the "zones" of the ueid.
    * This 'n' cannot appear in a base 23 converted int and will be used for explode();
    * 
    * @param int $user_birthday
    * @param string $user_gender
    * @param string $user_country
    * @param int $user_id
    * @return string
    */
    //OBSELETE 31.10.14
   public function create_ueid($user_birthday, $user_gender, $user_country, $user_id) {
       $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
       //Récupération des données utiles de la date de naissance
       //N.B.: La date de naissance fournie est attendue en format timestamp
       //IMPORTANT: Utilisation du floor et de la division par 1000 pour retomber sur un format de timestamp classique (à la seconde),
       //et non à la miliseconde comme ils sont en base.
       $dt = new DateTime('@'.floor($user_birthday/1000));
       //Pour le mois, on le prend tel quel ('MM').
       $month = $dt->format('m');
       //Pour la date, on va prendre le premier chiffre du millénaire puis les deux derniers
       $fullyear = $dt->format('Y');
       //$yeararray = str_split($fullyear);
       //$formatedyear = $yeararray[0] . $yeararray[2] . $yeararray[3];


       //Genre de l'utilisateur. On pose M = 1 | F = 2.
       //En cas de problème, on aura -1.
       switch ($user_gender){
           case 'm':
               $gender = 1;
               break;
           case 'f':
               $gender = 2;
               break;
           default:
               $gender = -1;
               break;
       }

       //Traitement du pays (nécessaire car dans la base 'commons', la clé primaire de la table
       //correspond au code de ce pays (deux lettres) et pas à un int
       //On va chercher la correspondance entre la lettre et son numéro dans l'alphabet
       $alpha = ['a' => '01','b' => '02','c' => '03','d' => '04','e' => '05','f' => '06','g' => '07','h' => '08','i' => '09','j' => '10','k' => '11','l' => '12','m' => '13','n' => '14','o' => '15','p' => '16','q' => '17','r' => '18','s' => '19','t' => '20','u' => '21','v' => '22','w' => '23','x' => '24','y' => '25','z' => '26'];
       $country = '';
       $ctr_array = str_split($user_country);
       foreach($ctr_array as $letter){
           $country .= $alpha[$letter];
       }
       //Passage de user_id en base 23 pour normaliser le nombre de caractères
       $b23user_id = base_convert($user_id, 10, 23);
//            //On sait que si ID <= 9999999, la taille max de $b23user_id sera de 6 chars.
//            //Donc on normalise:
//            while(strlen($b23user_id) < 6){
//                $b23user_id = '0'.$b23user_id;
//            }

       //Génération de l'UEID en convertissant chacune des parties en b23.
       $ueid = base_convert($month, 10, 23) . 'n' . base_convert($fullyear, 10, 23) . 'n' . base_convert($gender, 10, 23) . 'n' . base_convert($country, 10, 23) . 'n' . $b23user_id;

       return $ueid;
   }
   
   /**
     * Will 'decode' the given UEID.
     * Returns an associative array ('date', 'country', 'accid').
     * @param string $ueid
     * @return array
     */
   //OBSELETE 31.10.14
    public function read_ueid($ueid){
        //On commence par explode l'ueid car on sait que les 'n' sont délimiteurs
        $explodedUeid = explode('n', $ueid);

        //On sait que la première partie correspond au mois
        $month = base_convert($explodedUeid[0], 23, 10);

        //Seconde partie: année (sur 3 chiffres)
        $year = base_convert($explodedUeid[1], 23, 10);

        //Troisième: genre
        $gender = base_convert($explodedUeid[2], 23, 10);

        //Quatrième: pays
        $country = base_convert($explodedUeid[3], 23, 10);

        //Cinquième: accid
        $accid = base_convert($explodedUeid[4], 23, 10);

        //Préparation du tableau de retour
        $rVal = array();

        //Traitement des mois
        if(strlen($month) < 2){
            $month = '0'.$month;
        }
        $rVal['month'] = $month;

        //Pas de traitement nécessaire pour les années
        $rVal['year'] = $year;

        //Traitement du genre
        if(intval($gender) == 1){
            $rVal['gender'] = 'm';
        } else {
            $rVal['gender'] = 'f';
        }

        //Traitement du pays
        if(strlen($country) < 4){
            $country = '0'.$country;
        }
        $first_code = substr($country, 0, 2);
        $second_code = substr($country, 2, 2);

        $num = ['01' => 'a','02' => 'b','03' => 'c','04' => 'd','05' => 'e','06' => 'f','07' => 'g','08' => 'h','09' => 'i','10' => 'j','11' => 'k','12' => 'l','13' => 'm','14' => 'n','15' => 'o','16' => 'p','17' => 'q','18' => 'r','19' => 's','20' => 't','21' => 'u','22' => 'v','23' => 'w','24' => 'x','25' => 'y','26' => 'z',];
        $first_letter = $num[$first_code];
        $second_letter = $num[$second_code];
        $ctr_code = $first_letter . $second_letter;
        $rVal['country'] = $ctr_code;

        //Pas de traitement sur l'accid
        $rVal['accid'] = $accid;

        //Retour du tableau
        return $rVal;

    }
   
   public function tqr_create_ueid ($user_birthday, $user_gender, $user_country, $uid) {
       /*
        * Permet de créer un code représentant l'identitifiant extern.
        * Ce code est un composé de chiifres et de lettres qui permet d'identifier un utilisateur de façon unique.
        * Il a été créé pour empêcher les acteurs externes d'avoir des informations en ce qui concerne le nombre de comptes créés.
        * Cependant, on a pris soin d'y incorporer des éléments relatifs au profil de l'utilisateur lié.
        * 
        * Il se compose des éléments suivants :
        *   - 1|2 : Les codes commencent tous par 1 ou 2 pour le rendre plus "nombre".
        *   - Sexe-Pays : Il s'agit d'un couple formé de : sexe (1 ou 2) et de code pays de l'utilisateur. Le tout forme donc 3 caractères.
        *                 Les deux caractères du code pays sont ensuite traduit en nombre selon une table puis convertis en b23.
        *   - Leurre : Un élément pour complexfier la lecture. Il sert aussie de separateur. 
        *              Il s'agit d'un nombre compris entre 23 (Nombre à partir duquel on a 2 caractères en b23) et 528 (limite avant de passer à 3 caractères). 
        *              Cette valeur est multipliée par la partie appelée "premier" de la clé puis convertie.
        *   - UID : L'identifiant incrémental du compte. 
        *           Ce nombre est ensuite multiplié par la partie "second" de la clé et le "n-ieme" jour de la date anniversaire de l'utilisateur (exemple 15-10 => 15) puis converti en b23.
        *   - Clé : Un nombre pris au hasard entre 01 et 99. Il est d'une longueur de 2.
        *           PREMIER - SECOND : Si le nombre est un nombre premier, la partie à gauche est dite "premier" et celle de droite "second". Et vice versa.
        * 
        * REMARQUE : 
        *   (1) J'ai retiré les données sur la "date de naissance" car cela constituait une faille de sécurité. 
        *       En effet, il est possible qu'in utilise la date de naissance comme "3ème critère de connexion".
        *   (2) L'utilisation d'éléments aléatoire permet de complexifier la lecture de l'identifiant ainsi que son décodage.
        */
       $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
       
       /*
        * On génère la clé.
        * On le fait en deux temps pour des raisons de praticité.
        */
       $k_l = rand(1,9);
       $k_r = rand(1,9);
       $k = strval($k_l.$k_r);
       
       /*
        * gmp_prob_prime() renvoie 2 si le nombre est SUREMENT premier.
        * Cette fonction devient peu sure lorsqu'on utilise de grands nombres.
        * Dans notre cas on utilise des nombres srictement inferieurs à 100. Aussi, la fonction est sure.
        */
       $k_1st = $k_2nd = NULL;
//       if ( gmp_prob_prime(intval($k)) === 2 ) {
       if ( true ) {
           $k_1st = $k_l;
           $k_2nd = $k_r;
       } else {
           $k_1st = $k_r;
           $k_2nd = $k_l;
       }
       $k_1st = intval($k_1st);
       $k_2nd = intval($k_2nd);
       
       $first = rand(1,2);
       $leurre = rand(23,528);
       
       $gender;
       switch ($user_gender){
           case 'm':
                    $gender = 1;
               break;
           case 'f':
                    $gender = 2;
               break;
           default:
                    return "__ERR_VOL_WRG_GDR";
               break;
       }
       
       $nth = getdate($user_birthday)["yday"]+1;
       
       //On crypte le code pays
       /*
        * On utilise cette transformation (+23) pour être sur qu'on reste dans les 2 caractères.
        * Sinon au aurait des fois 2, 3 ou 4.
        */
       $alpha = ['a' => '01','b' => '02','c' => '03','d' => '04','e' => '05','f' => '06','g' => '07','h' => '08','i' => '09','j' => '10','k' => '11','l' => '12','m' => '13','n' => '14','o' => '15','p' => '16','q' => '17','r' => '18','s' => '19','t' => '20','u' => '21','v' => '22','w' => '23','x' => '24','y' => '25','z' => '26'];
       $cn_l = intval($alpha[$user_country[0]]);
       $cn_l = ( ($cn_l + $k_1st) > 26 ) ? ($cn_l+$k_1st)-26 : ($cn_l+$k_1st);
       $cn_l = base_convert($cn_l, 10, 27);
       
//       exit();
       $cn_r = intval($alpha[$user_country[1]]);
       $cn_r = ( ($cn_r+$k_2nd) > 26 ) ? ($cn_r+$k_2nd)-26 : ($cn_r+$k_2nd);
       $cn_r = base_convert($cn_r, 10, 27);
//       var_dump($cn_l,$cn_r);
       $cn = $cn_l.$cn_r;
       
       $a = $first;
       $b = $gender.$cn;
       $f_ = $leurre;
       $c = base_convert($f_, 10, 23);
       $b_ = $uid * $k_2nd * $nth;
//       var_dump($uid,$k_2nd,$nth);
//       echo  $b_;
       $d = base_convert($b_, 10, 23);
//       echo $d;
       $e = $k;
       
//       var_dump($uid,$a,$b,$c,$d,$e);
       
       $ueid = $a.$b.$c.$d.$e;
           
       return $ueid;
   }
   
   
   public function tqr_read_ueid ($user_birthday, $ueid) {
       /*
        * Permet de décoder un identifiant externe.
        */
       $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
       
       $gdr = substr($ueid, 1, 1);
       $cn = substr($ueid, 2, 2);
       $b_id = substr($ueid, 6,-2);
       $key = substr($ueid, -2);
       
       
       $nth = getdate($user_birthday)["yday"]+1;
       
       //Détermination de "premier" et "second"
       $k_1st = $k_2nd = NULL;
//       if ( gmp_prob_prime(intval($k)) === 2 ) {
       if ( true ) {
           $k_1st = $key[0];
           $k_2nd = $key[1];
       } else {
           $k_1st = $key[1];
           $k_2nd = $key[0];
       }
       $k_1st = intval($k_1st);
       $k_2nd = intval($k_2nd);
       
       //************ Décodage du pays ***************/
       //Acquisition de "Right" et "Left"
       $cn_l = $cn[0];
       $cn_l = intval(base_convert($cn_l, 27, 10));
       $cn_r = $cn[1];
       $cn_r = intval(base_convert($cn_r, 27, 10));
//       var_dump($k_1st,$k_2nd,$cn_l,$cn_r);
       //Application de la formule
       $cn_l = ( $cn_l > $k_1st && $cn_l < 27 ) ? ($cn_l-$k_1st) : 26+$cn_l-$k_1st;
       $cn_r = ( $cn_r > $k_2nd && $cn_r < 27 ) ? ($cn_r-$k_2nd) : 26+$cn_r-$k_2nd;
       
       //****** Conversion en alpha
       $alpha = ['a' => 1,'b' => 2,'c' => 3,'d' => 4,'e' => 5,'f' => 6,'g' => 7,'h' => 8,'i' => 9,'j' => 10,'k' => 11,'l' => 12,'m' => 13,'n' => 14,'o' => 15,'p' => 16,'q' => 17,'r' => 18,'s' => 19,'t' => 20,'u' => 21,'v' => 22,'w' => 23,'x' => 24,'y' => 25,'z' => 26];
       //af = AlphaFlip
       $af = array_flip($alpha);
//       var_dump($k_1st,$k_2nd,$cn_l,$cn_r);
       $cn_l = $af[$cn_l];
       $cn_r = $af[$cn_r];

        //*********** Décodage de l'identifiant ************/
       $a_id = base_convert($b_id, 23, 10)/$nth/$k_2nd;
       
//       var_dump($gdr,$cn,"BEFORE => ",$b_id,$nth,$k_2nd,"AFTER => ",$a_id,$key);
       
       $decoded = [
           "gender"     => $gdr,
           "country"    => $cn_l.$cn_r,
           "uid"        => $a_id,
           "key"        => $key
       ];
       
       return $decoded;
   }


    public function GetLocationInfos ($locid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $QO = new QUERY("qryl4comn1");
        $params = array( ':cyid' => $locid );
        $loc_datas = $QO->execute($params);
        
        if ( $loc_datas ) {
            return $loc_datas[0];
        } 
    }
    
    /**************************************************************************************************************************/
    /************************************************** ON READ (start)  ******************************************************/
    
    public function onread_getSenderMail ($case) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        switch ($case) {
            case "GO_TO_SHELL" :
                    return ( isset($this->prodconf_table) && $this->prodconf_table["prod_email_table"]["email_noreply"] ) ? 
                        $this->prodconf_table["prod_email_table"]["email_noreply"] : 
                        $this->EML_DEFAULT; 
                break;
            case "ACTY_EML_NOTIFY" :
                    return ( isset($this->prodconf_table) && $this->prodconf_table["prod_email_table"]["email_acty_notif"] ) ? 
                        $this->prodconf_table["prod_email_table"]["email_acty_notif"] : 
                        $this->EML_DEFAULT; 
                break;
            default:
                return;
        }
        
    }
    
    private function onread_get_acceid_from_accid($accid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists_with_id($accid,TRUE);
        
        if (! $r )
            return "__ERR_VOL_USER_GONE";
        else
            return $r["acc_eid"];
    }
    
    private function onread_get_accid_from_acceid($acc_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $r = $this->exists($acc_eid);
        
        if (! $r )
            return "__ERR_VOL_USER_GONE";
        else
            return $r["accid"];
    }
    
    /******************************************* EXTRA DATAS ******************************************/
            
    private function PullExtraDatas ($k,$i) {
        
        $datas = NULL;
        switch ($k) {
            case "acc_eml" :
                    $datas = $this->PullEmail($i);
                break;
            case "acc_grp":
                    $datas = $this->PullAccGrp($i);
                break;
            default:
                break;
        }
        
        return $datas;
    }
    
//    private function PullEmail ($i) { //[DEPUIS 30-07-16]
    public function PullEmail ($i) {
        /*
         * Permet de récupérer l'email actif lié à un Compte dont l'identifiant est passé en paramètre.
         * Cette méthode est difféente de @see Pseudo_IsUsed() en ce qu'elle utilise une autre table.
         * La méthode @see Pseudo_IsUsed() utilise la table de recherche d'email quand cette dernière utilise la table Historique D'utilisation.
         *
         * La méthode renvoie FALSE si l'email n'est pas retrouvé.
         * Dans le cas contraire, elle renvoie les données de l'occurence.
         * 
         * ATTENTION : La méthode ne vérifie pas si l'identifiant est valide. Si il ne l'est pas la méthode renverra logiquement FALSE.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $i);
        
        $datas;
        $QO = new QUERY("qryl4tqraccn5");
        $params = array(':uid' => $i);
        $datas = $QO->execute($params);

        return (! $datas ) ? FALSE : $datas[0]["emhy_email"];
    }
    
    private function PullAccGrp ($i) {
        /*
         * Permet de récupérer le groupe lié au Compte dont l'identifiant est passé en paramètre.
         * L'information est récupérée depuis la table "Historique de Groupes".
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $i);
        
        $datas;
        $QO = new QUERY("qryl4tqraccn6");
        $params = array(":uid" => $i);
        $datas = $QO->execute($params);

        if (! $datas ) { 
            return FALSE;
        } else  {
            return $datas[0]["abogrp_gid"];
        }
    }
    
    /****************************************************************************************************************************/
    /************************************************** ON DELETE (start)  ******************************************************/
    
    public function ondelete_apply ($args) {
         /*
          * Permet de gérer toutes les opérations de demande de suppression de Compte.
          */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args, TRUE);
        
        $XPTD = ["accid","hikw","yilv","yilv_ot","ilbbif","locip"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && ( !empty($v) | in_array($k,["yilv_ot","ilbbif"]) ) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        
        //On vérifie que le compte existe et est actif
        $acc_tab = $this->on_read_entity(["accid"=>$args["accid"]]);
        if ( !$acc_tab || intval($acc_tab["acc_todelete"]) !== 0 ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        
        //On vérifie si la logique est bonne pour YILV
        if ( ( strtoupper($args["yilv"]) === "OTHER" && $args["yilv_ot"] === "" ) | ( strtoupper($args["yilv"]) !== "OTHER" && $args["yilv_ot"] !== "" ) ) {
            $wrg_datas[] = "yilv";
        } 
        
        foreach ( $args as $k => $v ) {
            if ( $k === "locip" || $k === "accid" ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
//                    $wrg_datas[] = [$k,$v];
                    $wrg_datas[] = $k;
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return ["FAILED",$wrg_datas];
        }
        
        //On lance l'enregistrement de la demande ET envoi d'email
        $id = $this->ondelete_goToDelete ($acc_tab["accid"], FALSE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $id) ) {
            return $id;
        }
        
        $now = round(microtime(TRUE)*1000);
        //On associe la demande précédemment créée à la LastLetter
        $QO = new QUERY("qryl4tqraccn21");
        $params = array(
            ":id"       => $id, 
            ":hikw"     => $args["hikw"], 
            ":yilv"     => $args["yilv"], 
            ":yilv_ot"  => $args["yilv_ot"], 
            ":ilbbif"   => $args["ilbbif"], 
            ":now"      => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now);
        $llid = $QO->execute($params);
        
        /*
         * [DEPUIS 05-09-15] @author BOR
         *      On change l'état de tous les Articles du Compte. 
         */
        $PA = new PROD_ACC();
        $PA->ondelete_change_all_art_state($acc_tab["accid"],2);
        
        /*
         * [DEPUIS 09-09-15] @author BOR
         * On envoie un email au propriétaire du compte
         */
        $this->report_todelete($acc_tab);
        
        return $llid;
     }
     
     
    public function ondelete_goToDelete ($accid, $email_it = FALSE) {
        /*
         * Permet de mettre un compte en mode TO_DELETE.
         * La méthode gère aussi les opérations connexes, comme :
         *  (1) Envoyer un email à l'utilisateur
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $accid);
        
        //On vérifie que le compte est bel et bien en mode TO_DELETE
        $acc_tab = $this->on_read_entity(["accid" => $accid]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $acc_tab) ) {
            return $acc_tab;
        }
        
//        return intval($acc_tab["acc_todelete"]);
        //On vérifie si le compte est déjà en mode TDL
        if ( intval($acc_tab["acc_todelete"]) === 1 ) {
            return "__ERR_VOL_ALDY_TD";
        } else if ( intval($acc_tab["acc_todelete"]) === 2 ) {
            return "__ERR_VOL_DFNTLY_TD";
        } else if ( $acc_tab["acc_todelete"] === -1 ) {
            return "__ERR_VOL_FATAL_UXPTD";
        }
        
        //On inscrit le compte comme ToDelete
        $id;
        $now = round(microtime(TRUE)*1000);
        $edate_tstamp = $now + $this->TDL_INTVAL;
        $edate = date("Y-m-d", ($edate_tstamp/1000));
        $QO = new QUERY("qryl4tqraccn11");
        $params = array(":accid" => $accid, ":tstamp" => $now, ":edate" => $edate, ":edate_tstamp" => $edate_tstamp);
        $id = $QO->execute($params);
        
        //On met à jour la version PRODUIT du compte
        $QO = new QUERY("qryl4pdaccn31");
//        $QO = new QUERY("qryl4pdaccn23"); //[NOTE 04-09-15] @author BOR
        $params = array(
            ":accid"    => $accid, 
            ":istodel"  => 1,
            ":date"     => $edate,
            ":tstamp"   => $edate_tstamp
        );
        $QO->execute($params);
        //PLUS TARD
        
        if ( $email_it === TRUE ) {
            //On envoie un email au propriétaire du compte
            $this->report_todelete($acc_tab);
        }
        
        return $id;
    }
    
    /**
     * Permet de changer l'état de tous les comptes dont le delai de carence est dépassé.
     * L'état passe à 2. Cela signifie que le compte est désormais définitivement inaccessible.
     */
    public function ondetele_update_todel_state_all  () {
        
        $now = round(microtime(TRUE)*1000);
        
        /*
         * ETAPE :
         *  Mettre à jour la table en changeant l'état.
         */
        $QO = new QUERY("qryl4pdaccn32");
        $params = array(":now_tstamp" => $now);
        $t__ = $QO->execute($params);
//        var_dump($t__); //[NOTE ] //TEST, DEBUG
        
    }
    
    private function report_todelete($acc_tab) {
                
        /*
         * Permet d'envoyer un email au propriétaire du compte lui signifiant que son Compte a été mis en mode "En attente de suppression".
         * On se repère par rapport à l'identifiant de l'opération de suppression.
         */
         $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $acc_tab, TRUE);
         
         //On envoie l"email
         $exp = ( isset($this->prodconf_table) && $this->prodconf_table["prod_email_table"]["email_desactivation"] ) ? $this->prodconf_table["prod_email_table"]["email_desactivation"] : $this->EML_DEFAULT; 
         
         $EMH = new EMAILAC_HANDLER();
         $args_eml = [
            "exp"       => htmlspecialchars_decode($exp),
//            "rcpt" => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
            "rcpt"      => $acc_tab["acc_eml"],
            "rcpt_uid"  => $acc_tab["accid"],
            "catg"      => "USER_ACTION"
        ];
         
//        var_dump($args_eml,$rec_link);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
         
        /*
         * [DEPUIS 09-09-2015] @author BOR
         *     On récupère les données concernant la demande.
         */
        $QO = new QUERY("qryl4tqraccn22");
        $params = array(
            ":uid"    => $acc_tab["accid"], 
        );
        $datas = $QO->execute($params);
        if (! $datas ) {
            /*
             * [NOTE 09-09-2015] @author BOR
             *  Plutot que de déclencher une erreur, on envoie pas l'email.
             *  L'utilisateur ne recevra pas de mail et nous espérons que :
             *      1- Il le signale
             *      2- Qu'à force que personne n'en recoive, on finisse par le détecter
             */
            return TRUE;
        } else {
            $datas = $datas[0];
        }
        
        $WY = $datas["lltr_YILV"];
        switch ($WY) {
            case "CONCEPT" :
            case "DESIGN" :
            case "ERRNBG" :
            case "HTRUN" :
            case "MSENTOURAGE" :
            case "MSFAV" :
            case "MSFUNC" :
            case "MSPHONE" :
                    $code = "_YILV_".$WY;
                    $TXH = new TEXTHANDLER();
                    $rzn = $TXH->get_deco_text('fr',$code);
                break;
            case "OTHER" :
                    $rzn = $datas["lltr_YILV_Other"];
                break;
            default:
                return TRUE;
        }
        if (! $rzn ) {
            return TRUE;
        }
                
        $args_eml_marks = [
            "fullname"                  => $acc_tab["pfl_fn"],
            "pseudo"                    => $acc_tab["acc_psd"],
            "date"                      => date("d-m-Y G:i:s",($datas["actdl_sdate_tstamp"]/1000)),
            "reasons"                   => $rzn,
            "trenqr_http_root"          => HTTP_RACINE,
            "trenqr_login_link"         => HTTP_RACINE."/login",
            "trenqr_start_rcvy_link"    => HTTP_RACINE."/recovery/password",
            "trenqr_prod_img_root"      => WOS_SYSDIR_PRODIMAGE
        ];
        $r_ = $EMH->emac_send_email_via_model("emdl_delacccnfn1", "fr", $args_eml, $args_eml_marks);
//        $r_ = $EMH->emac_send_email_via_model("emdl_delacc_apply_light", "fr", $args_eml, $args_eml_marks);
        if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
            return "__ERR_VOL_FAILED";
        }
        
        return TRUE;
         
    }
    
    private function report_todelete_byaccid($atdlid) {
        /*
         * Permet d'envoyer un email au propriétaire du compte lui signifiant que son Compte a été mis en mode "En attente de suppression".
         */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $atdlid);
        
    }
    
    /****************************************************************************************************************************/
    /************************************************** ON UPDATE (start)  ******************************************************/
    
    public function onalter_CclToDelete ($accid) {
        /*
         * Permet d'annuler le processus de suppression de Compte si cela est (encore) possible.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $accid);
        
        //On vérifie si le compte existe et est en mode TO_DELETE
        $acc_tab = $this->on_read_entity(["accid" => $accid]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $acc_tab) ) {
            return $acc_tab;
        }
        
//        return intval($acc_tab["acc_todelete"]);
        if ( intval($acc_tab["acc_todelete"]) === 0 ) {
            return "__ERR_VOL_NOT_TD";
        } else if ( intval($acc_tab["acc_todelete"]) === 2 ) {
            return "__ERR_VOL_DFNTLY_TD";
        } else if ( $acc_tab["acc_todelete"] === -1 ) {
            return "__ERR_VOL_FATAL_UXPTD";
        }
        
        $now = round(microtime(TRUE)*1000);
        //On procède à l'annulation effective
        $QO = new QUERY("qryl4tqraccn12");
        $params = array(":accid" => $accid, ":tstamp" => $now);
        $QO->execute($params);
        
        //On met à jour au niveau de PDACC
        //On met à jour la version PRODUIT du compte
        $QO = new QUERY("qryl4pdaccn31");
//        $QO = new QUERY("qryl4pdaccn23"); //[NOTE 04-09-15] @author BOR
//        $params = array(":accid" => $accid, ":istodel" => 0);
        $params = array(
            ":accid"    => $accid, 
            ":istodel"  => 0,
            ":date"     => NULL,
            ":tstamp"   => NULL
        );
        $QO->execute($params);
        
        /*
         * [DEPUIS 05-09-15] @author BOR
         *      On change l'état de tous les Articles du Compte. 
         */
        $PA = new PROD_ACC();
        $PA->ondelete_change_all_art_state($accid,6);
        
        //TODO : Envoyer un email
        
        return TRUE;
    }
    
    
    public function onalter_RecoverPassword ($email, $ssid, $locip = NULL) {
        /*
         * Permet de reinitiliser le mot de passe d'un compte.
         * L'opération necessite l'email actif de l'utilisateur.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie qu'il s'agit d'un email valide
        $iv = $this->Email_ChkFormat($email);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $iv) ) {
            return "_REC_L_WRG_DATAS";
        }
        
        //On vérifie que l'email est actif
        $t_tab = $this->Email_Used($email);
        if (! $t_tab ) {
            return "_REC_L_UKNW_EML";
        }
        $uid = $t_tab["srh_eml_uid"];
                
        //On vérifie que le compte existe et qu'il n'est pas en attente de suppression
        $acc_tab = $this->on_read_entity(["accid" => $uid]);
        if (! $acc_tab ) {
            return "_REC_L_U_GONE";
        }
        
//        return intval($acc_tab["acc_todelete"]);
        //On vérifie si le compte est déjà en mode TDL
        if ( intval($acc_tab["acc_todelete"]) === 1 ) {
            return "_REC_L_ALDY_TD";
        } else if ( intval($acc_tab["acc_todelete"]) === 2 ) {
            /*
             * [NOTE 14-11-14] @author L.C.
             * On ne renvoie pas un signal qui explique que le compte est dans la phase suppression finale.
             * En effet, cela pourrait être interprété comme le fait que l'on ne supprime pas effectivement les comptes.
             */
            return "_REC_L_U_GONE";
        } else if ( $acc_tab["acc_todelete"] === -1 ) {
            return "__ERR_VOL_FATAL_UXPTD";
        }
        
        //On lance le processus de changement de mot de passe au niveau de la base de données
        $prec_ids =  $this->onalter_writerecovery($acc_tab, $email, $ssid, $locip);
        
        //On envoie le mail 
        $r = $this->onalter_sendrec_link($acc_tab,$prec_ids, $email, $ssid);
        
        return $r;
    }
    
    
    private function onalter_writerecovery ($acc_tab, $em, $ssid, $locip = NULL) {
        /*
         * Permet d'effectuer la sauvegarde de la demande au niveau de la base de données.
         * Si une demande active existe on l'annule avant de créer la nouvelle demande.
         */
         $now = round(microtime(TRUE)*1000);
         
        //On vérifie si une demande active existe
        $QO = new QUERY("qryl4tqrrecn3");
        $params = array(":uid" => $acc_tab["accid"], ":now" => $now);
        $prec_exists = $QO->execute($params);
        
        if ( $prec_exists ) {
            $prec_exists = $prec_exists[0];
            //On annule la demande on ajoutant une occurrence dans la table Cancellation
            $QO = new QUERY("qryl4tqrrecn4");
            $params = array(":precid" => $prec_exists["precid"], ":cdate" => date("Y-m-d G:i:s",($now/1000)), ":cdate_tstamp" => $now);
            $QO->execute($params);
        }
        
        $key = $this->guidv4();
        $exp = $now + $this->REC_INTVAL;
        //Il s'agit du code de validation
        $code_val = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $args = [
            "prec_accid"            => $acc_tab["accid"],
            "prec_ssid"             => $ssid,
            "prec_key"              => $key,
            "prec_code_vald"        => $code_val,
            "prec_locip"            => $locip,
            "prec_eml_used"         => $em,
            "pred_evdate"           => date("Y-m-d G:i:s",($now/1000)),
            "pred_evdate_tstamp"    => $now,
            "pred_xdate"            => date("Y-m-d G:i:s",($exp/1000)),
            "pred_xdate_tstamp"     => $exp
        ];
        $QO = new QUERY("qryl4tqrrecn1");
        $params = array(":prec_accid" => $args["prec_accid"], ":prec_ssid" => $args["prec_ssid"], ":prec_key" => $args["prec_key"], ":prec_code_vald" => $args["prec_code_vald"], ":prec_locip" => $args["prec_locip"], ":prec_eml_used" => $args["prec_eml_used"], ":pred_evdate" => $args["pred_evdate"], ":pred_evdate_tstamp" => $args["pred_evdate_tstamp"], ":pred_xdate" => $args["pred_xdate"], ":pred_xdate_tstamp" => $args["pred_xdate_tstamp"]);
        $id = $QO->execute($params);
        
        //Construction de l'eid
        $eid = $this->entity_ieid_encode($now, $id);
        
        //On met à jour la table avec eid
        $QO = new QUERY("qryl4tqrrecn2");
        $params = array(":id" => $id, ":eid" => $eid);
        $QO->execute($params);
        
        return [$id, $eid, $key, $code_val];
    }
    
     private function onalter_sendrec_link ($acc_tab, $prec_ids, $email, $ssid) {
         /*
          * Permet d'envoyer par mail le lien qui permettra d'autoriser la modification de mot de passe
          */
         
         //********** Construction des liens ***********
         
         //Lien pour modifier son mot de passe
         //s = Special
         $rec_link = HTTP_RACINE."/s/rpassword?case=";
         $rec_link .= $acc_tab["acc_psd"];
         $rec_link .= "&uid=".$acc_tab["acc_eid"];
         /*
          * On transforme les '.' en ',' sinon cela va entrainer un bug au niveau de url_handler.
          * On utilise ',' car ce caractère n'est pas autorisé pour un email.
          * Pour des besoins visuels, je préfère %2C que ','.
          */
         $email = str_replace('.',"%2C",$email);
         $rec_link .= "&em=".$email;
         //is = IdS
         $rec_link .= "&is=".$prec_ids[1];
         $rec_link .= "=".$prec_ids[2];
         $rec_link .= "=".$ssid;
         $rec_link .= "&lg=".$acc_tab["acc_lang"];
         $rec_link_encoded = urlencode($rec_link);
         
         //Lien pour annuler la demande de réinitialisation
         $rec_link_ccl = HTTP_RACINE."/s/cancel_rpassword?case=";
         $rec_link_ccl .= $acc_tab["acc_psd"];
         $rec_link_ccl .= "&uid=".$acc_tab["acc_eid"];
         $rec_link_ccl .= "&em=".$email;
         //is = IdS
         $rec_link_ccl .= "&is=".$prec_ids[1];
         $rec_link_ccl .= "=".$prec_ids[2];
         $rec_link_ccl .= "=".$ssid;
         $rec_link_ccl .= "&lg=".$acc_tab["acc_lang"];
         $rec_link_ccl_encoded = urlencode($rec_link_ccl);
          
         //On envoie l"email
         $exp = ( isset($this->prodconf_table) && $this->prodconf_table["prod_email_table"]["email_pass_recovery"] ) ? $this->prodconf_table["prod_email_table"]["email_pass_recovery"] : $this->EML_DEFAULT;
          
         $EMH = new EMAILAC_HANDLER();
          
         $args_eml = [
            "exp"       => htmlspecialchars_decode($exp),
//            "rcpt" => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
            "rcpt"      => $acc_tab["acc_eml"],
            "rcpt_uid"  => $acc_tab["accid"],
            "catg"      => "USER_ACTION"
        ];
          
//        var_dump($args_eml,$rec_link);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
        
        $args_eml_marks = [
            "fullname"                  => $acc_tab["acc_psd"],
            "secret_code"               => $prec_ids[3],
            /*
             * [DEPUIS 28-08-15] @author BOR
             */
            "trenqr_login_link"         => HTTP_RACINE."/login",
            "trenqr_start_rcvy_link"    => HTTP_RACINE."/recovery/password",
            "trenqr_prod_img_root"      => WOS_SYSDIR_PRODIMAGE,
            
            "recovery_link"             => $rec_link,
            "recovery_link_public"      => $rec_link,
            "recovery_cancel_link"      => $rec_link_ccl,
            "recovery_cancel_link_public" => $rec_link_ccl
        ];
         
//         var_dump(method_exists(EMAILAC_HANDLER, "emac_send_email_via_model"));
        $r_ = $EMH->emac_send_email_via_model("emdl_recpwdn1", "fr", $args_eml, $args_eml_marks);
         
        if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
            return "__ERR_VOL_FAILED";
        }
        
        return TRUE;
        
     }
     
     public function onalter_CheckPassRecLinkDatas ($args) {
        /*
         * Permet de vérifier si les données passées en paramètre qui proviennent très certainement d'un lien de modification de mot de passe ...
         * ... sont valides. Ce qui veut dire, que les données correspondent à une opération en attente.
         */
        $NEEDED = ["up","ui","ue","oei","k","ssid"];
        $com = array_intersect(array_keys($args), $NEEDED);
         
        if ( count($com) != count($NEEDED) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$NEEDED],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        $acc_tab = $this->exists($args["ui"]);
        if ( !$acc_tab || ( strtoupper($acc_tab["acc_psd"]) !==  strtoupper($args["up"]) ) ) {
            return "__ERR_VOL_UG";
        } 
        
        //On récupère les données liées au code externe de l'opération.
        $QO = new QUERY("qryl4tqrrecn5");
        $params = array(":prec_eid" => $args["oei"]);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return "__ERR_VOL_UKNW";
        } else {
            $prec_tab = $datas[0];
            $now = round(microtime(TRUE)*1000);
            
            //On vérifie si la date d'expiration est atteteinte ou dépassée
            if ( intval($now) >= intval($prec_tab["pred_xdate_tstamp"]) ) {
                return "__ERR_VOL_EXP";
            } 
            
            //On vérifie si le ticket a déjà été utilisé
            if ( isset($prec_tab["pred_udate_tstamp"]) ) {
                return "__ERR_VOL_OBSLT";
            } 
            
            //On vérifie si l'opération a été annulée
            $QO = new QUERY("qryl4tqrrecn7");
            $params = array(":precid" => $prec_tab["precid"]);
            $prec_ccl_tab = $datas = $QO->execute($params);
            
            if ( $prec_ccl_tab ) {
                return "__ERR_VOL_CCL";
            }
            
            //On vérifie que les données correspondantes
            if ( $args["ue"] === $acc_tab["emhy_email"] && $prec_tab["prec_ssid"] === $args["ssid"] && $prec_tab["prec_key"] === $args["k"] ) {
                return TRUE;
            } else {
                return "__ERR_VOL_INVLD";
            }
        }
     }
     
     public function onalter_ValidPwdRecovery ($p,$c,$oper_datas) {
         /*
          * Permet de valider la changement d'un mot de passe.
          * Pour ce faire, on tenter de valider l'opération en prenant compte des données fournies.
          * Ensuite, on effectue la modfication.
          */
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
         
         $vld = $this->onalter_CheckPassRecLinkDatas($oper_datas);
         if ( $vld === TRUE ) {
             //On récupère les données de la table au niveau de la base de données
             $QO = new QUERY("qryl4tqrrecn5");
             $params = array(":prec_eid" => $oper_datas["oei"]);
             $datas = $QO->execute($params);
             
             if (! $datas ) {
                 return "__ERR_VOL_UKNW";
             } else {
                 $prec_tab = $datas[0];
                 //On vérifie que l'élément Code correspond
                 if (! preg_match($this->rgx_code, $c) ) {
                     //WC = WRONG_CODE
                     return "_REC_WC"; 
                 } else if ( $c !== $prec_tab["prec_code_vald"] ) {
                     //BC = BAD_CODE
                     return "_REC_BC";
                 } else if (! preg_match($this->rgx_pwd, $p) ) {
                     //BP = WRONG_PASSWORD
                     return "_REC_WP";
                 } else {
                     //**> On lance le changement de mot de passe
                     //On hash le mot de passe
                     $hp = $this->hash_input_passwd($p);
                     $now = round(microtime(TRUE)*1000);
                     
                     //On effectue la modification au niveau de la base de données sur la table Accounts
                     $QO = new QUERY("qryl4tqraccn13");
                     $params = array(":accid" => $prec_tab["prec_accid"], ":hpwd" => $hp, ":precid" => $prec_tab["precid"], ":date" => date("Y-m-d G:i:s",($now/1000)), ":tstamp" => $now);
                     $QO->execute($params);
                     
                     //On modifie l'occurrence dans la table PwdRecoveries pour indiquer que le ticket a été utilisé
                     $QO = new QUERY("qryl4tqrrecn8");
                     $params = array(":precid" => $prec_tab["precid"], ":date" => date("Y-m-d G:i:s",($now/1000)), ":tstamp" => $now);
                     $QO->execute($params);
                     
                     return TRUE;
                 }
             }
        
         } else {
             return $vld;
         }
         
     }
     
     public function onalter_profile ($args) {
         /*
          * Permet de valider la mise à jout des données de profil envoyées par FE - Gestion de compte
          */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["accid","ins_fn","ins_nais_tstamp","ins_gdr","ins_cty","locip"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        
        //On vérifie que le compte existe et est actif
        $acc_tab = $this->exists_with_id($args["accid"]);
        if ( !$acc_tab || intval($acc_tab["acc_todelete"]) !== 0 ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On transforme "gender" en LowerCase au cas où
        $args["ins_gdr"] = strtolower($args["ins_gdr"]);

        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        foreach ( $args as $k => $v ) {
            if ( $k === "locip" || $k === "accid" ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
//                    $wrg_datas[] = [$k,$v];
                    $wrg_datas[] = $k;
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return ["FAILED",$wrg_datas];
        }
        
        //On vérifie si on peut encore changer "Gender" et on en déduit le nombre restant
        if ( intval($acc_tab["pfl_gender_mod_rem"]) === 0 ) {
            /*
             * Plutot que de délencher une erreur on laisse la valeur gender à celle par qu'on a déjà en base.
             * Normalement, FE aurait du bloquer le changement de cette valeur. 
             * Mais la restriction est relativement simple à controuner.
             */
            $args["ins_gdr"] = $acc_tab["pfl_gender"];
            $args["pfl_gdr_rmn"] = 0;
        } else if ( strtolower($acc_tab["pfl_gender"]) !== strtolower($args["ins_gdr"]) ) {
            //On détermine le nombre de changements restants autorisés
            $t__ = intval($acc_tab["pfl_gender_mod_rem"]);
            $args["pfl_gdr_rmn"] = --$t__;
        } else {
            $args["pfl_gdr_rmn"] = intval($acc_tab["pfl_gender_mod_rem"]);
        }
        
        //On vérifie si on peut encore changer "Date De Naissance" et on en déduit le nombre restant
        if ( intval($acc_tab["pfl_bdate_mod_rem"]) === 0 ) {
            /*
             * Plutot que de délencher une erreur on laisse la valeur Date de Naissance à celle par qu'on a déjà en base.
             * Normalement, FE aurait du bloquer le changement de cette valeur. 
             * Mais la restriction est relativement simple à controuner.
             */
            $args["ins_nais"] = $acc_tab["pfl_bdate"];
            $args["ins_nais_tstamp"] = $acc_tab["pfl_bdate_tstamp"];
            $args["pfl_bdy_rmn"] = 0;
        } else {
            //On crée une date avec le bon format
            $b_ = getdate($args["ins_nais_tstamp"]);
            $args["ins_nais"] = $b_["year"]."-".$b_["mon"]."-".$b_["mday"];
            
            if ( intval($args["ins_nais_tstamp"]) !== intval($acc_tab["pfl_bdate_tstamp"]) ) {
                //On détermine le nombre de changements restants autorisés
                $t__ = intval($acc_tab["pfl_bdate_mod_rem"]);
                $args["pfl_bdy_rmn"] = --$t__;
            } else {
                $args["pfl_bdy_rmn"] = intval($acc_tab["pfl_bdate_mod_rem"]);
            }
            
        } 
        
        //On récupère les données sur la localisation
        $loc_tab = $this->GetLocationInfos($args["ins_cty"]);
        if (! $loc_tab ) {
            return ["FAILED","ins_cty"];
        }
        $args["ins_cn"] = $loc_tab["ctr_code"];
        
        //On met à jour l'occurrence ACCOUNT
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tqraccn14");
        $params = array(
            ":pfl_fn"           => $args["ins_fn"], 
            ":pfl_bdy"          => $args["ins_nais"], 
            ":pfl_bdy_tstamp"   => $args["ins_nais_tstamp"], 
            ":pfl_bdy_rmn"      => $args["pfl_bdy_rmn"], 
            ":pfl_city"         => $args["ins_cty"], 
            ":pfl_gdr"          => $args["ins_gdr"], 
            ":pfl_gdr_rmn"      => $args["pfl_gdr_rmn"], 
            ":accid"            => $args["accid"], 
            ":now"              => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"           => $now);
        $QO->execute($params);
        
        $so = $this->onalter_stgs_updproddb($args["accid"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $so) ) {
            return "__ERR_VOL_UXPTD";
        }
        
        $loc_tab = $this->GetLocationInfos($acc_tab["pfl_lvcity"]);
        $bdy = date("d-m-Y",$args["ins_nais_tstamp"]);
        //On renvoie les bonnees données
        $DONE = [
            "fullname"      => $args["ins_fn"], 
            "birthdate"     => $bdy, 
            "birthdate_tsp" => $args["ins_nais_tstamp"],
            "bdy_d"         => explode("-",$bdy)[0], 
            "bdy_m"         => explode("-",$bdy)[1], 
            "bdy_y"         => explode("-",$bdy)[2], 
            "bdy_mod_rmn"   => $args["pfl_bdy_rmn"], 
            "pfl_city"      => [
                "i"     => $args["ins_cty"],
                "n"     => $loc_tab["asciiname"],
                "cn"    => $loc_tab["ctr_code"],
                "city"  => $loc_tab["asciiname"].", ".strtoupper($loc_tab["ctr_code"])
            ], 
            "pfl_gdr"       => $args["ins_gdr"], 
            "gdr_mod_rmn"   => $args["pfl_gdr_rmn"],
        ];
        
        return ["DONE",$DONE];
        
     }
     
     public function onalter_account ($args) {
         /*
          * Permet de valider la mise à jout des données de profil envoyées par FE - Gestion de compte
          */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["accid","ins_eml","ins_psd","ins_lng","locip"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        
        //On vérifie que le compte existe et est actif
        $acc_tab = $this->on_read_entity(["accid"=>$args["accid"]]);
        if ( !$acc_tab || intval($acc_tab["acc_todelete"]) !== 0 ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On ne change que les valeurs qui différente sinon on risque d'avoir une "ERREUR_DOUBLON" ou "INDISPONIBLE"
        $SKIP = [];
        if ( strtolower($args["ins_psd"]) === strtolower($acc_tab["acc_psd"]) ) {
            $SKIP[] = "ins_psd";
        }
        if ( strtolower($args["ins_eml"]) === strtolower($acc_tab["acc_eml"]) ) {
            $SKIP[] = "ins_eml";
        }
        
        //On transforme "lang" en LowerCase au cas où
        $args["ins_lng"] = strtolower($args["ins_lng"]);
        /*
         * [DEPUIS 11-07-16]
         */ 
        if ( strtolower($args["ins_lng"]) === strtolower($acc_tab["acc_lang"]) ) {
            $SKIP[] = "ins_lng";
        }

        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        foreach ( $args as $k => $v ) {
            if ( $k === "locip" || $k === "accid" || in_array($k, $SKIP) ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
//                    $wrg_datas[] = [$k,$v];
                    $wrg_datas[] = $k;
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return ["FAILED",$wrg_datas];
        }
        
        $now = round(microtime(TRUE)*1000);
        
        // ********* On met à jour l'occurence ACCOUNT pour EMAIL **********/
        if (! in_array("ins_eml", $SKIP) ) {
            //Cela permet de garder la future valeur de SRH à jour
            $acc_tab["acc_psd"] = $args["ins_psd"];

            $email = $args["ins_eml"];
            if (! $this->Email_Exists($email) ) {
                //Insertion de l'email
                $r_ = $this->InsertEmail($email);
                if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) {
                    return "__ERR_VOL_FALD_ON_EML";
                }
            }
            
            //On retire l'ancien email (Histo + SRH) + Update
            $nar = $this->Email_UpdateUserEmlLink($acc_tab["acc_eml"], $email, $acc_tab);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nar) ) {
                return "__ERR_VOL_FALD_ON_EML";
            }
        }
        
        /**********/
        $must = array_intersect(["ins_lng","ins_psd"], $SKIP);
//        if (! in_array("ins_psd", $SKIP) ) {
        if ( $must && count($must) ) {
            //On met à jour l'occurrence ACCOUNT pour PSEUDO et LANG
            $QO = new QUERY("qryl4tqraccn18");
            $params = array (
                ":accid"    => $args["accid"], 
                ":acc_psd"  => $args["ins_psd"], 
                ":now1"     => date("Y-m-d H:m:s",($now/1000)), 
                ":tstamp1"  => $now,
                ":acc_lng"  => $args["ins_lng"], 
                ":now2"     => date("Y-m-d H:m:s",($now/1000)), 
                ":tstamp2"  => $now
            );
            $QO->execute($params);
            
            /*
             * [NOTE 28-11-14] @author L.C.
             * On doit mettre à jour les tables dans tous les cas. 
             * La mise à jour globale permet de mettre à jour tout le profil sans devoir à utiliser CRON dont l'utilisation a été restreint
             */
            /*
            $so = $this->onalter_stgs_updproddb($args["accid"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $so) ) {
                return "__ERR_VOL_UXPTD";
            }

            //Mise à jour de SRH_Profil (Pseudo)
            $QO = new QUERY("qryl4tqraccn19");
            $params = array(":accid" => $args["accid"], ":upsd" => $args["ins_psd"]); 
            $QO->execute($params);
            */
        }
        
        $so = $this->onalter_stgs_updproddb($args["accid"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $so) ) {
            return "__ERR_VOL_UXPTD";
        }
        
        //On renvoie les bonnees données
        $DONE = [
            "pseudo"    => $args["ins_psd"], 
            "email"     => $args["ins_eml"], 
            "lang"      => $args["ins_lng"]
        ];
        
        return ["DONE",$DONE];
        
     }
     
     public function onalter_password ($args) {
         /*
          * Permet de valider la mise à jout des données de profil envoyées par FE - Gestion de compte
          */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["accid","ins_pwd","locip"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        
        //On vérifie que le compte existe et est actif
        $acc_tab = $this->on_read_entity(["accid"=>$args["accid"]]);
        if ( !$acc_tab || intval($acc_tab["acc_todelete"]) !== 0 ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        foreach ( $args as $k => $v ) {
            if ( $k === "locip" || $k === "accid" ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
//                    $wrg_datas[] = [$k,$v];
                    $wrg_datas[] = $k;
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return ["FAILED",$wrg_datas];
        }
        
        //**> On lance le changement de mot de passe
        //On hash le mot de passe
        $hp = $this->hash_input_passwd($args["ins_pwd"]);
        $now = round(microtime(TRUE)*1000);

        //On effectue la modification au niveau de la base de données sur la table Accounts
        $QO = new QUERY("qryl4tqraccn13");
        $params = array(":accid" => $args["accid"], ":hpwd" => $hp, ":precid" => NULL, ":date" => date("Y-m-d H:i:s",($now/1000)), ":tstamp" => $now);
        $QO->execute($params);
        
        return "DONE";
     }
     
     public function onalter_seculog ($args) {
         /*
          * Permet de valider la mise à jout des données de profil envoyées par FE - Gestion de compte
          */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["accid","sec_ecwpsd","locip"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        
        //On vérifie que le compte existe et est actif
        $acc_tab = $this->on_read_entity(["accid"=>$args["accid"]]);
        if ( !$acc_tab || intval($acc_tab["acc_todelete"]) !== 0 ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        foreach ( $args as $k => $v ) {
            if ( $k === "locip" || $k === "accid" ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
//                    $wrg_datas[] = [$k,$v];
                    $wrg_datas[] = $k;
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return ["FAILED",$wrg_datas];
        }
        
        /*
         * Passer par chk/uchk permet d'éviter les problèmes avec le controle de valeurs considérées comme FALSE
         */
        foreach ($args as $k => $v) {
            if ( strtolower($v) === "chk") {
                $args[$k] = 1;
            } else if ( strtolower($v) === "uchk") {
                $args[$k] = 0;
            }
        }
        
        //**> On lance le changement des paramètres

        //On effectue la modification au niveau de la base de données sur la table Accounts
        $QO = new QUERY("qryl4tqraccn20");
        $params = array(":accid" => $args["accid"], ":ecwpsd" => intval($args["sec_ecwpsd"]));
        $QO->execute($params);
        
        
        $DONE = [
            "sec_ecwpsd" => $args["sec_ecwpsd"]
        ];
        
        return ["DONE",$DONE];
     }
     
     private function onalter_stgs_updproddb ($uid) {
        /*
         * Met à jour l'occurrence dans la base PRODUCT à l'aide des données récupérées dans ACCDB.
         * Facilte grandement la vie :)
         * 
         * [NOTE 28-11-14] @author L.C.
         *      J'ai ajouté la mise à jour des tables de types VM et SRH qui contiennent des données sur les profils utilisateurs.
         *      Que ces tables soient vides ou pas. De toutes les façons, si elles sont vides, le traitement se fera d'autant plus rapidement.
         *      L'avantage de faire ces mises à jour ici c'est que cette méthode est utilisée lors des opérations au niveau de SETTINGS et que l'utilisateur est apte a attendre.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        //Acquisition des données sur le Compte depuis la base ACCOUNT
        $acc_tab = $this->exists_with_id($uid);
        if (! $acc_tab ) {
            return "__ERR_VOL_UXPTD";
        }
        
        //Acquisition des données sur la localisation
        $loc_tab = $this->GetLocationInfos($acc_tab["pfl_lvcity"]);
        if (! $loc_tab ) {
            return "__ERR_VOL_UKW_CITY";
        }
        
        //Mise à jour ACCOUNT de l'occurrence dans la base PRODUCT
        $QO = new QUERY("qryl4pdaccn24");
        $params = array(
            ":accid"        => $acc_tab["accid"],
            ":acc_gid"      => $this->INS_DEFAULT_GRP,
            ":acc_upsd"     => $acc_tab["acc_psd"],
            ":acc_ufn"      => $acc_tab["pfl_fn"],
            ":acc_gdr"      => $acc_tab["pfl_gender"],
            ":acc_ucityid"  => $loc_tab["city_id"],
            ":acc_ucity_fn" => $loc_tab["asciiname"],
            ":acc_ucnid"    => $loc_tab["ctr_code"],
            ":acc_ucn_fn"   => $loc_tab["ctr_name"],
            /*
             * [DEPUIS 12-07-16]
             */
//            ":acc_udl"      => $this->INS_DEFAULT_LNG
            ":acc_udl"      => $acc_tab["acc_lang"]
        );
        $QO->execute($params);
        
        //On va maintenant récupérer les données au niveau de PDACC
        
        $PDACC = new PROD_ACC();
        $pdacc_tab = $PDACC->on_read_entity(["acc_eid" => $acc_tab["acc_eid"]]);
        $flwr = $PDACC->onread_acquiere_my_followers($acc_tab["accid"]);
        $flwr_nb = ( isset($flwr) ) ? count($flwr) : 0;
        $flwg = $PDACC->onread_acquiere_my_following($acc_tab["accid"]);
        $flwg_nb = ( isset($flwr) ) ? count($flwg) : 0;
        
        //Mise à jour de SRH_Profil 
        $QO = new QUERY("qryl4pdaccn25");
        $params = array(
            ":uid"      => $pdacc_tab["pdaccid"],
            ":upsd"     => $pdacc_tab["pdacc_upsd"],
            ":ufn"      => $pdacc_tab["pdacc_ufn"],
            ":ppic"     => $pdacc_tab["pdacc_uppic"],
            ":ufols"    => $flwr_nb,
            ":ucap"     => $pdacc_tab["pdacc_capital"]
        );
        $QO->execute($params);
        
        //Mise à jour de SRH_Trends 
        $QO = new QUERY("qryl4pdaccn26");
        $params = array(
            ":uid"  => $pdacc_tab["pdaccid"],
            ":upsd" => $pdacc_tab["pdacc_upsd"],
            ":ufn"  => $pdacc_tab["pdacc_ufn"]
        );
        $QO->execute($params);
              
        //Mise à jour de VM_Articles_IML
       $QO = new QUERY("qryl4pdaccn27");
        $params = array(
            ":uid"      => $pdacc_tab["pdaccid"],
            ":ugid"     => $pdacc_tab["pdacc_gid"],
            ":ufn"      => $pdacc_tab["pdacc_ufn"],
            ":upsd"     => $pdacc_tab["pdacc_upsd"],
            ":ppicid"   => $pdacc_tab["pdacc_uppicid"],
            ":ppic"     => $pdacc_tab["pdacc_uppic"],
            ":todel"    => $pdacc_tab["pdacc_todelete"]
        );
        $QO->execute($params);
              
        //Mise à jour de VM_Articles_ITR
        $QO = new QUERY("qryl4pdaccn28");
        $params = array(
            ":uid"      => $pdacc_tab["pdaccid"],
            ":ugid"     => $pdacc_tab["pdacc_gid"],
            ":ufn"      => $pdacc_tab["pdacc_ufn"],
            ":upsd"     => $pdacc_tab["pdacc_upsd"],
            ":ppicid"   => $pdacc_tab["pdacc_uppicid"],
            ":ppic"     => $pdacc_tab["pdacc_uppic"],
            ":todel"    => $pdacc_tab["pdacc_todelete"]
        );
        $QO->execute($params);
        
        return TRUE;
        
    }
     
    
    /**************************************************************************************************************************************************************************************************************/
    /***************************************************************************************** CONFIRMATION EMAIL SCOPE  *****************************************************************************************/
    /**************************************************************************************************************************************************************************************************************/
    
    public function EC_Exists($key) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $key);
        
        $QO = new QUERY("qryl4tqraccn29");
        $params = array( ":key" => $key );
        $d__ = $QO->execute($params);
        
        $datas = ( $d__ ) ? $d__[0] : FAlSE;
        return $datas;
    }
    
    /**
     * Permet de vérifier si le compte a déjà fait l'objet d'une validation de Compte.
     * Cette méthode est utile pour déterminer s'il faut demander à l'utilisateur de valider son adresse email.
     * 
     * @param string $ueid L'identifiant externe du compte
     * @return mixed {string|boolean} Un code erreur ou une réponse à la question
     */
    public function EC_AccIsCnfrmdOnce ($ueid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $ueid);
        
        /*
         * ETAPE :
         *      On récupère la table des données en vérifiant par la même occasion que le compte existe.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * [ETAPE 22-10-15] @author BOR
         *  On interroge la base de données pour savoir si au moins une occurence existe en ce qui concerne le compte.
         */
        $QO = new QUERY("qryl4tqraccn23_lmt");
        $params = array( ":uid" => $utab["pdaccid"], ":limit" => 1 );
        $datas = $QO->execute($params);
        
        $r = FALSE;
        if ( $datas ) {
            $datas = $datas[0];
            if ( $datas["cnfeml_result"] === "VALIDATED" ) {
                $r = TRUE;
            }
        } 
        
        return $r;
            
    }
    
    /* 
     * [DEPUIS 12-08-16]
     *       Optimisation pour des soucis de PERF
     */
    public function EC_AccIsCnfrmdOnce_wid ($uid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        /*
         * ETAPE :
         *      On récupère la table des données en vérifiant par la même occasion que le compte existe.
         * [DEPUIS 12-08-16]
         *      Prise en compte de AQAP
         */
        if (! ( $_OPTIONS && in_array("AQAP", $_OPTIONS) ) ) {
            $PA = new PROD_ACC();
            $utab = $PA->exists_with_id($uid,TRUE);
            if (! $utab ) {
                return "__ERR_VOL_USER_GONE";
            }
        }
        
        /*
         * [ETAPE 22-10-15] @author BOR
         *      On interroge la base de données pour savoir si au moins une occurence existe en ce qui concerne le compte.
         */
        $QO = new QUERY("qryl4tqraccn23_lmt");
        $params = array( ":uid" => $uid, ":limit" => 1 );
        $datas = $QO->execute($params);
        
        $r = FALSE;
        if ( $datas ) {
            $datas = $datas[0];
            if ( $datas["cnfeml_result"] === "VALIDATED" ) {
                $r = TRUE;
            }
        } 
        
        return $r;
            
    }
    
    public function EC_GetLastPending ($ueid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $ueid);
        
        /*
         * ETAPE :
         *  On récupère la table des données en vérifiant par la même occasion que le compte existe.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * ETAPE 22-10-15] @author BOR
         *  On interroge la base de données pour savoir si au moins une occurence existe en ce qui concerne le compte.
         */
        $QO = new QUERY("qryl4tqraccn28");
        $params = array( ":uid" => $utab["pdaccid"] );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : NULL;
            
    }
    
    
    /**
     * Vérifie s'il existe une occurrence terminée en ce qui concerne l'adresse email passée en paramètre.
     * Cette méthode peut par exemple servir dans le cas où l'utilisateur veut modifier son adresse email.
     * Elle peut aussi servir pour d'autres opérations qui aimerait savoir si l'email a déjà été validée.
     * 
     * @param string $ueid L'identifiant externe du Compte
     * @param string $eml L'adresse email à vérifier
     * @return mixed {string|boolean} Un code erreur ou une réponse à la question
     */
    public function EC_EmailIsConfirm ($ueid, $eml, $_GET_DATAS = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * ETAPE
         *  On vérifie que l'élément passé en paramètre est de type EMAIL
         */
        $iv = $this->Email_ChkFormat($eml);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $iv) ) {
            return $iv;
        }
        
        /*
         * ETAPE :
         *  On récupère la table des données en vérifiant par la même occasion que le compte existe.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($ueid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_USER_GONE";
        }
        
        /*
         * ETAPE :
         *  On vérifie s'il existe une occurrence liée à l'email pour le compte passé en paramètre
         */
        $QO = new QUERY("qryl4tqraccn24");
        $params = array( ":uid" => $utab["pdaccid"], ":eml" => $eml );
        $datas = $QO->execute($params);
        if ( $datas ) {
            $r__ = ( $_GET_DATAS ) ? $datas : TRUE;
        } else {
            $r__ = FALSE;
        }
        
        return $r__;
    }
    
    /**
     * Lancer une opération de demande de validation de l'adresse email.
     * 
     * @param type $ueid L'identifiant externe du Compte
     * @param type $eml L'email qu'il faudra utiliser pour lancer l'opération
     * @param type $locip L'adresse IP de l'ordinateur qui a servi pour lancer l'opération
     * @param type $purpz L'objet de l'opération
     * @param type $ssid L'identifiant de SESSION
     * @param type $uagent La chaine USER_AGET
     * @param type $OVRWRT Permet de relancer l'opération en écrasant l'opération en cours
     * @return string
     */
    public function EC_NewOper ($ueid, $eml, $locip, $purpz, $ssid, $uagent = NULL, $OVRWRT = FALSE) {
        //
        //OVRWRT 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ueid,$eml,$locip,$purpz,$ssid]);
        
        /*
         * ETAPE :
         *      On vérifie que la donnée email passée en paramètre est valide.
         */
        if ( $eml ) {
            $iv = $this->Email_ChkFormat($eml);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $iv) ) {
                return $iv;
            }
        } 
            
        /*
         * ETAPE :
         *      On s'assure que les données concordent
         */
        if (! in_array($iv, ["ACCOUNT_CREATION", "EMAIL_MODIFY", "SECURITY_VERIFICATION"]) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        
        /*
         * ETAPE :
         *      On vérifie au préalable que l'adresse email n'a pas déjà été validée.
         */
        $xs__ = $this->EC_EmailIsConfirm($ueid, $eml, TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $xs__) ) {
            return $xs__;
        } else if ( $xs__ && !$OVRWRT ) {
            return "__ERR_VOL_NON_BIS_IN_IDEM";
        }
        
        /*
         * ETAPE :
         */
        if ( $xs__ ) {
            $now = round(microtime(TRUE)*1000);
            $date = date("Y-m-d H:i:s",($now/1000));
            /*
             * ETAPE : 
             *      On annule l'opération en cours
             */
            $QO = new QUERY("qryl4tqraccn25");
            $params = array(
                ":ce_id"        => $xs__["cnfeml_id"],
                ":result"       => "ABORTED_PLYAG",
                ":ce_date"      => $date,
                ":ce_tstamp"    => $now
            );
            $QO->execute($params);
        }
        
        /*
         * On s'assure que la dernière occurrence est annulée.
         */
        if ( $OVRWRT === TRUE ) {
            /*
             * ETAPE :
             *      On récupère la dernière occurrence non validée. Si elle existe, on l'annule.
             */
            $zs__ = $this->EC_GetLastPending($ueid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $zs__) ) {
                return $zs__;
            } else if ( $zs__ ) {
               /*
                * ETAPE :
                *      On annule l'occurrence
                */
               $now = round(microtime(TRUE)*1000);
               $date = date("Y-m-d H:i:s",($now/1000));
               /*
                * ETAPE : 
                *      On annule l'opération en cours
                */
               $QO = new QUERY("qryl4tqraccn25");
               $params = array(
                   ":ce_id"        => $zs__["cnfeml_id"],
                   ":result"       => "ABORTED_PLYAG",
                   ":ce_date"      => $date,
                   ":ce_tstamp"    => $now
               );
               $QO->execute($params);
            }
            
        }
        
        
        /*
         * ETAPE :
         *  On récupère la table des données en vérifiant par la même occasion que le compte existe.
         */
        $utab = $this->exists($ueid);
        $eml = ( $eml ) ? $eml : $utab["acc_eml"];

        
        /*
         * ETAPE :
         *      On prépare les données necessaires à la création de l'entité au niveau de la base de données.
         */
        $now = round(microtime(TRUE)*1000);
        $key = $this->guidv4();
        $code = substr(str_shuffle("0123456789_-abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,6);
                
        $args = [
            ":ce_key"        => $key,
            ":ce_code"       => $code,
            ":ce_email"      => $eml,
            ":ce_accid"      => $utab["accid"],
            ":ce_purpz"      => $purpz,
            ":ce_ssid"       => $ssid,
            ":ce_locip"      => $locip,
            ":ce_uagent"     => ( $uagent ) ? $uagent : NULL,
            ":ce_date"       => date("Y-m-d G:i:s",($now/1000)),
            ":ce_tstamp"     => $now
        ];
        $QO = new QUERY("qryl4tqraccn26");
        $params = $args;
        $QO->execute($params);
        
        //On envoie le mail 
        $r = $this->EC_NewOper_SendMail($utab, $key, $ssid, $code, $now, $eml);
        
        return $r;
        
    }
    
    
    public function EC_NewOperIfNotVald ($ueid, $locip, $purpz, $ssid, $email = NULL, $uagent = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ueid, $locip, $purpz, $ssid]);
        
        /*
         * ETAPE :
         *      On vérifie s'il y a une demande en attente.
         */
        $r__ = $this->EC_GetLastPending($ueid);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r__) ) {
            return $r__;
        } else if ( $r__ ) {
            /*
             * ETAPE :
             *      On renvoie les données sur la dernière demande en attente
             */
            return $r__;
        }
        
        /*
         * ETAPE : 
         *  On récupère la table des données
         */
        $TA = new TQR_ACCOUNT();
        $utab = $TA->on_read_entity(["acc_eid"=>$ueid]);
        if (! $utab ) {
            return "__ERR_VOL_USER_GONE";
        } 
        
        $eml = ( $email ) ? $email : $utab["acc_eml"];
        
        $ro__ = $this->EC_NewOper($ueid, $eml, $locip, $purpz, $ssid, $uagent);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ro__) ) {
            return $ro__;
        } else if (! $ro__ ) {
            return "__ERR_VOL_FAILED";
        }
        
        return TRUE;
    }
    
    
    private function EC_NewOper_SendMail ($acc_tab, $key, $ssid, $code, $time, $eml = NULL) {
//        .http_build_query(["secret"=>$this->code_secret]);
         /*
          * Permet d'envoyer par mail le lien qui permettra d'autoriser la modification de mot de passe
          */
         
         /********** CONSTRUCTION DU LIEN ***********/
         
         //s = Special
         $link = HTTP_RACINE."/s/confirm_email?";
         $link .= http_build_query(["case" => $acc_tab["acc_eid"]]);
         
         /*
          * On transforme les '.' en ',' sinon cela va entrainer un bug au niveau de url_handler.
          * On utilise ',' car ce caractère n'est pas autorisé pour un email.
          * Pour des besoins visuels, je préfère %2C que ','.
          */
         if ( $eml ) {
             $email = $eml;
         } else if ( key_exists($key, $acc_tab) && $acc_tab["acc_eml"] ) {
             $email = $acc_tab["acc_eml"];
         } else {
             return "__ERR_VOL_WRG_DATAS";
         }
         
         $e__ = http_build_query(["em" => $email]);
         $link .= "&".str_replace('.',"%2C",$e__);
         //is = IdS
         $link .= "&".http_build_query(["is" => $key]);
         $link .= "=".$ssid;
         $link .= "=".$time;
         $link .= "&".http_build_query(["code" => $code]);
         $link_encoded = urlencode($link);
         
         //On envoie l"email
         $exp = ( isset($this->prodconf_table) && $this->prodconf_table["prod_email_table"]["email_noreply"] ) ? $this->prodconf_table["prod_email_table"]["email_noreply"] : $this->EML_DEFAULT;
          
         $EMH = new EMAILAC_HANDLER();
         $args_eml = [
            "exp"       => htmlspecialchars_decode($exp),
//            "rcpt" => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
            "rcpt"      => $email,
            "rcpt_uid"  => $acc_tab["accid"],
            "catg"      => "USER_ACTION"
        ];
          
//        var_dump($args_eml);
//        var_dump($args_eml,$rec_link);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
         
        $args_eml_marks = [
            "trenqr_http_root"          => HTTP_RACINE,
            "trenqr_prod_img_root"      => WOS_SYSDIR_PRODIMAGE,
            
            "fullname"                  => $acc_tab["pfl_fn"],
            
            "econfirm_link"             => $link,
            "econfirm_link_public"      => $link,
            
            "trenqr_login_link"         => HTTP_RACINE."/connexion",
            "trenqr_start_rcvy_link"    => HTTP_RACINE."/recovery/password",
        ];
        
//        var_dump($acc_tab,$args_eml_marks);
//        exit();
         
//         var_dump(method_exists(EMAILAC_HANDLER, "emac_send_email_via_model"));
        $r_ = $EMH->emac_send_email_via_model("emdl_econfirm", "fr", $args_eml, $args_eml_marks);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) {
            return $r_;
        } else if ( !$r_ ) { 
            return "__ERR_VOL_FAILED";
        }
        
        return TRUE;
        
    }
    
    
    /**
     * Permet de valider une opération de confirmation d'email.
     * 
     * @param type $ui
     * @param type $ueml
     * @param type $key
     * @param type $ssid
     * @param type $tm
     * @param type $cd
     * @return boolean
     */
    public function EC_ValidOper ($ui, $ueml, $key, $ssid, $tm, $cd) {
//         "ui","ueml","key","ssid","tm","cd"
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ui,$ueml,$key,$ssid,$tm,$cd]);
        
        $eds = $this->EC_ValidOper_Check($ui, $ueml, $key, $ssid, $tm, $cd, TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $eds) ) {
            return $eds;
        }
        
        $ecid = $eds["cnfeml_id"];
        $now = round(microtime(TRUE)*1000);
        $date = date("Y-m-d H:i:s",($now/1000));
        /*
         * ETAPE : 
         *      On met à jour l'occurrence liée à la clé 
         */
        $QO = new QUERY("qryl4tqraccn25");
        $params = array(
            ":ce_id"        => $ecid,
            ":result"       => "VALIDATED",
            ":ce_date"      => $date,
            ":ce_tstamp"    => $now
        );
        $QO->execute($params);
        
        return TRUE;
        
    }
    
     
    /**
     * 
     * 
     * @param type $ui L'identifiant externe
     * @param type $ueml L'adresse email
     * @param type $key La clé publique
     * @param type $ssid L'identifiant de Session
     * @param type $tm Le timestamp de l'opération
     * @param type $cd Le code lié à l'opération qui sert de mot de passe
     * @param type $_GET_DATAS
     * @return string
     */ 
    public function EC_ValidOper_Check ($ui, $ueml ,$key, $ssid, $tm, $cd, $_GET_DATAS = FALSE) {
//         "ui","ueml","key","ssid","tm","cd"
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ui,$ueml,$key,$ssid,$tm,$cd]);
         
        $acc_tab = $this->exists($ui);
        if (! $acc_tab ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On récupère les données liées au code externe de l'opération.
        $QO = new QUERY("qryl4tqraccn27");
        $params = array(
            ":key"      => $key,
            ":code"     => $cd,
            ":eml"      => $ueml,
            ":uid"      => $acc_tab["accid"],
            ":ssid"     => $ssid,
            ":tstamp"   => $tm
        );
//        var_dump(__LINE__,$params);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return "__ERR_VOL_UKNW";
        } else {
            $ec_tab = $datas[0];
            
            //On vérifie si le ticket a déjà été utilisé
            if ( isset($ec_tab["cnfeml_rsltdt_tstamp"]) ) {
                return "__ERR_VOL_OBSLT";
            } 
            
            return ( $_GET_DATAS === TRUE ) ? $ec_tab : TRUE;
        }
         
     }
     
    /**
     * Permet d'indiquer que l'occurrence liée a fait lobjet d'un message auprès de l'utilisateur final afin de lui signifier que son compte est validé.
     * 
     * @param string $key 
     * @return mixed {string|boolean}
     */
    public function EC_EndOper ($key) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $key);
         
        $cetab = $this->EC_Exists($key);
        if (! $cetab ) {
            return "_ERR_VOL_EC_GONE";
        }
         
        $ecid = $cetab["cnfeml_id"];
        $now = round(microtime(TRUE)*1000);
        $date = date("Y-m-d H:i:s",($now/1000));
        /*
         * ETAPE : 
         *      On met à jour l'occurrence liée à la clé 
         */
        $QO = new QUERY("qryl4tqraccn30");
        $params = array(
            ":ce_id"        => $ecid,
            ":ce_date"      => $date,
            ":ce_tstamp"    => $now
        );
        $QO->execute($params);
         
        return TRUE;
    }
    
    
    /**************************************************************************************************************************************************************************************************************/
    /***************************************************************************************** GETTERS and SETTERS SCOPE  *****************************************************************************************/
    /**************************************************************************************************************************************************************************************************************/
    
    public function getAccid() {
        return $this->accid;
    }

    public function getAcc_eid() {
        return $this->acc_eid;
    }

    public function getAcc_grp() {
        return $this->acc_grp;
    }

    public function getPfl_fn() {
        return $this->pfl_fn;
    }

    public function getPfl_bdate() {
        return $this->pfl_bdate;
    }

    public function getPfl_bdate_tstamp() {
        return $this->pfl_bdate_tstamp;
    }

    public function getPfl_bdate_mod_rem() {
        return $this->pfl_bdate_mod_rem;
    }

    public function getPfl_lvcity() {
        return $this->pfl_lvcity;
    }
    
    public function getPfl_lvcity_name() {
        return $this->pfl_lvcity_name;
    }

    public function getPfl_lvcity_cncode() {
        return $this->pfl_lvcity_cncode;
    }

    public function getPfl_nocity() {
        return $this->pfl_nocity;
    }

    public function getPfl_gender() {
        return $this->pfl_gender;
    }

    public function getPfl_gender_mod_rem() {
        return $this->pfl_gender_mod_rem;
    }

    public function getPfl_dmod() {
        return $this->pfl_dmod;
    }

    public function getPfl_dmod_tstamp() {
        return $this->pfl_dmod_tstamp;
    }

    public function getAcc_psd() {
        return $this->acc_psd;
    }

    public function getAcc_psd_dmod() {
        return $this->acc_psd_dmod;
    }

    public function getAcc_psd_dmod_tstamp() {
        return $this->acc_psd_dmod_tstamp;
    }

    public function getAcc_eml() {
        return $this->acc_eml;
    }

    public function getAcc_pwd() {
        return $this->acc_pwd;
    }

    public function getAcc_pwd_dmod() {
        return $this->acc_pwd_dmod;
    }

    public function getAcc_pwd_dmod_tstamp() {
        return $this->acc_pwd_dmod_tstamp;
    }

    public function getAcc_lang() {
        return $this->acc_lang;
    }

    public function getAcc_lang_dmod() {
        return $this->acc_lang_dmod;
    }

    public function getAcc_lang_dmod_tstamp() {
        return $this->acc_lang_dmod_tstamp;
    }

    public function getAcc_crea_locip() {
        return $this->acc_crea_locip;
    }
/*
    public function getAcc_pflbio() {
        return $this->acc_pflbio;
    }

    public function getAcc_pflbio_dmod() {
        return $this->acc_pflbio_dmod;
    }

    public function getAcc_pflbio_dmod_tstamp() {
        return $this->acc_pflbio_dmod_tstamp;
    }
 */ 

    public function getAcc_dcrea() {
        return $this->acc_dcrea;
    }

    public function getAcc_dcrea_tstamp() {
        return $this->acc_dcrea_tstamp;
    }

    public function getAcc_todelete() {
        return $this->acc_todelete;
    }

    public function getSecu_staycon() {
        return $this->secu_staycon;
    }

    public function getSecu_coWithPsdEna() {
        return $this->secu_coWithPsdEna;
    }

    public function getSecu_isThirdCritEna() {
        return $this->secu_isThirdCritEna;
    }

    public function getSecu_NotifyWhenLogin() {
        return $this->secu_notifyWhenLogin;
    }

    public function getRgx_fn() {
        return $this->rgx_fn;
    }

    public function getRgx_bd() {
        return $this->rgx_bd;
    }

    public function getBd_limit() {
        return $this->bd_limit;
    }

    public function getRgx_gdr() {
        return $this->rgx_gdr;
    }

    public function getRgx_psd() {
        return $this->rgx_psd;
    }

    public function getPsd_min() {
        return $this->psd_min;
    }

    public function getPsd_max() {
        return $this->psd_max;
    }

    public function getRgx_email() {
        return $this->rgx_email;
    }

    public function getEmail_max() {
        return $this->email_max;
    }

    public function getRgx_pwd() {
        return $this->rgx_pwd;
    }

    public function getPwd_min() {
        return $this->pwd_min;
    }

    public function getPwd_max() {
        return $this->pwd_max;
    }

    public function getINS_DEFAULT_LNG() {
        return $this->INS_DEFAULT_LNG;
    }

}
