<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of srvc
 *
 * @author lou.carther.69
 */
class LANG_HANDLER extends MOTHER {
    private $running_lang;
    
    /**
     * Quelques règles : 
     * R1 :  Modifier IpLang revient à modifier running_lang. Mais si l'utilisateur modifie ensuite uspklang on doit modifier iplang car,
     *       on suppose qu'il aimerait garder la meme lang même s'il se déco.
     *       Si le futur utilisateur sur la même ip décide de changer de nouveau Iplang ça ne chagera pas upsklang. Aussi, lors de la connexion,
     *       c'est toujours uspklang qui prédomine. Ce qui veut dire que lors de la connexion, running_lang restera uspklang
     *  
     *      Lang_Handler gère ces conflits de langue
     */
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
    }

//    public function init_new_demand ($ip_lang,$cn_lang,$default_lang) { //[DEPUIS 10-07-16]
//    public function init_new_demand ($lang_local_cookie, $cn_lang, $default_lang) { //[DEPUIS 04-08-16]
    public function init_new_demand ($lang_local_cookie, $cn_lang, $default_lang, $available_lang_aliases = NULL) {
        //Les deux premieres valeurs peuvent etre fausses. 
        //En effet, la base de données peut ou ne pas avoir ces données. Mais default ne PEUT PAS etre manquant
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, array($default_lang));
        
        //On commence par demander à SESSION si on a accès à uspklang
        $uspklang = PCC_SESSION::giveMeUspklangIfSessionRestExistsAndUserConnected();
        
        /*
        $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$uspklang,$lang_local_cookie,$cn_lang,$default_lang]);
        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        //*/
        
        //FALSE signifie: SESSION n'existe pas OU rsto_infos n'existe pas
        if( isset($uspklang) &&  $uspklang !== FALSE ) {
            //Cela ne vaut donc que ssi l'user est connecté
            $this->running_lang = $uspklang;
        } else if( isset($uspklang) &&  $uspklang == FALSE ) {
            //Ici, soit SESSION n'existe pas soit RSTO n'est pas défini
            //On selectionne donc les valeurs par ordre d'importance
            /* //[DEPUIS 10-07-16]
            if ( isset($ip_lang) ) {
                $this->running_lang = $ip_lang;
            }
            //*/
            if ( !empty($lang_local_cookie) ) {
                $this->running_lang = $lang_local_cookie;
            }
//            else if ( isset($cn_lang) ) {
            else if ( !empty($cn_lang) ) {
                $this->running_lang = $cn_lang;
            }
            else {
                $this->running_lang = $default_lang;
            }
            
            /*
             * [DEPUIS 03-08-16]
             *      On vérifie s'il y a un LANG_ALIAS pour la langue spécifiée
             */
            return ( $available_lang_aliases && key_exists($this->running_lang, $available_lang_aliases) ) ? 
                $available_lang_aliases[$this->running_lang] : $this->running_lang; 
                
        } else if ( !isset($uspklang) ) {
            $this->signalError("err_sys_l330",__FUNCTION__,__LINE__); 
        }
        
        return $this->running_lang;
    }       


    public function modifier_running_lang_by_uspklang_donc_aussi_iplang($entry_pflid, $entry_ipv4, $entry_lg_code) {
        $now = round(microtime(TRUE)*1000);
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //On commence par changer uspklang
        $Query1 = new QUERY("qryl3accdbn1");
        $bdd1 = new WOS_DATABASE($Query1->getQdbname());
        $bdd1->tryConnection();
        $params1 = array( ':accid' => $entry_pflid, ':lg_code' => $entry_lg_code, ":tstamp" => $now );
        $bdd1->executePrepareQueryWithoutResult($Query1->getQbody(), $params1);
        
        //On modifie ensuite IpsTable
        $Query2 = new QUERY("qryl3n5");
        $bdd2 = new WOS_DATABASE($Query2->getQdbname());
        $bdd2->tryConnection();
        $ip_numeral = $this->std_ip_long($entry_ipv4);
        $params2 = array( ':ip_numeral' => $ip_numeral, ':lg_code' => $entry_lg_code, ":time" => $now );
        $bdd2->executePrepareQueryWithoutResult($Query2->getQbody(), $params2);
        
        $this->changer_running_lang_dans_sto($entry_lg_code);
    }
    
    
    private function std_ip_long ($entry_ip, $unisgned_form = true) {
        if ( isset($entry_ip) ) {
            $long = ip2long($entry_ip);
            //NOte : j'ai ajouté explode parce que, je n'ai pas confiance en ip2long
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$long);
            if ( (count(explode(".", $entry_ip)) != 4) || $long == -1 || $long === FALSE)  $this->signalError ("err_sys_l327", __FUNCTION__, __LINE__);
            else return ($unisgned_form) ? sprintf("%u", $long) : $long ;
        } else $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
    }
    
    
    public function modifier_running_lang_by_iplang ($entry_ipv4, $entry_lg_code) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //On modifie ensuite IpsTable
        $Query2 = new QUERY("qryl3n5");
        $bdd2 = new WOS_DATABASE($Query2->getQdbname());
        $bdd2->tryConnection();
        $ip_numeral = $this->std_ip_long($entry_ipv4);
        $params2 = array( ':ip_numeral' => $ip_numeral, ':lg_code' => $entry_lg_code );
        $bdd2->executePrepareQueryWithoutResult($Query2->getQbody(), $params2);
        
        $this->changer_running_lang_dans_sto($entry_lg_code);
    }
    
    
    private function changer_running_lang_dans_sto ($entry_lang) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $STO = new SESSION_TO();
        $session_not_void = PCC_SESSION::doesSessionExistAndIsNotvoid();
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["sto"],'v_d');              
        if(!$session_not_void) { $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__); } //FALSE car SESSION n'existe pas
        else {
            if ( key_exists("sto_infos",$_SESSION) and count($_SESSION["sto_infos"]) > 0 ) {
                $STO->setRunning_lang($entry_lang);
                $_SESSION["sto_infos"] = $STO;
            } else $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__);
        }
    }
}

?>