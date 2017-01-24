<?php

/**
 * Description of srvc
 *
 * @author lou.carther.69
 */
class SRVC_SNITCHER EXTENDS MOTHER {
    private $prod_xmlscope;
    
    //Simple datas
    private $current_ref;
    private $prev_ref;
    private $req_start_time;
    private $current_ipadd;
    private $prev_ipadd;
    private $hostname; 
    private $ipv4_list_of_host;
    private $iplang;
    //private $dns_record_of_host;
    //private $browzer;
    //private $os;
    
    //Complex datas
    private $loc_city;
    private $loc_ctr_code;
    private $loc_ctr_name;
    private $loc_ctr_lg_code;
    private $loc_ctr_flag;
    //private $is_forbidden_ctr;
    /**
     * On peut utiliser directement SNITCH ou récupérer les valeurs sous forme de tableau.
     * Cela permet notamment de ne pas refaire toutes les opérations auprès de la bdd.
     */
    private $snitch_infos_array;
    
    /*
     * [DEPUIS 10-07-16]
     *      La Langue lié à l'ordinateur en présence. 
     */
    private $lang_local_cookie;
    
    function __construct($entry_prod_xmlscope) {

        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__,__CLASS__);
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        $this->prod_xmlscope = $entry_prod_xmlscope;
        
        /*
         * DEV, TEST, DEBUG
         */
//        $this->current_ipadd = "192.95.22.206"; //NOWHERE
//        $this->current_ipadd = "109.212.71.173"; //FRANCE
        //$this->current_ipadd = "90.53.245.7"; //FRANCE
//        $this->current_ipadd = "198.199.97.30"; //ETATS-UNIS
        //$this->current_ipadd = "180.76.5.61"; //CHINE
        //$this->current_ipadd = "81.31.252.182"; //IRAN

        
        $this->current_ipadd = $_SERVER['REMOTE_ADDR']; //PROD
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->current_ipadd,'v_d');  
//        exit();
        $this->run();
    }
    
    
    private function run () {
        //0) Est ce qu'on a vraiment besoin de procéder à un snitchage ? 
        //Il est necessaire si l'ip a changé ou si SESSION n'esxiste pas ou plus
        $we_need_it = $this->does_snitch_is_required_here ();
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$we_need_it,'v_d');  
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        if ( $we_need_it ) {
            
            //echo ("<p style=\"color: red;\">We need it !</p>");
            //1) On commence par vérifier que l'ipv4 n'est pas bannis
            $this->is_visitor_ip_banned();
            //2) On réccupère les données liées à la provenance et l'heure d'arrivée
            $this->start_snitching_for_origin_infos();
            //3) On réccupère les données liées à la localisation
            $this->acquiere_localisation_datas($this->current_ipadd);
            //4)On récupère iplang
            $this->acquiere_iplang($this->current_ipadd);
            /*
             * [DEPUIS 10-07-16]
             */
            //5) Récupère la langue attachée à l'ordinateur utilisé par CUSER
            $this->acquiere_local_lang();
            //6) Preparer snicth_infos
            $this->build_snitch_infos();
            
        } else {    
            //LANG_HANDLER 
            $this->acquiere_iplang($this->current_ipadd);
            $this->build_snitch_infos_from_session();   
        }
    }
    
    
    private function does_snitch_is_required_here () {
        $STO = new SESSION_TO();
        $session_not_void = PCC_SESSION::doesSessionExistAndIsNotvoid();
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["sto"],'v_d');              
        if(! $session_not_void ) { 
            //Comme on a pas de fichier de SESSION, on laisse prev_ipadd à NULL et current reste current
//            echo "<p style=\"color: red;\">BESOIN DE SNITCH CAR SESSION EST VIDE</p>";  //FOR TEST OR DEBUG ONLY
            return TRUE; //TRUE car SESSION n'existe pas
            
        } 
        else 
        {
            if ( key_exists("sto_infos",$_SESSION) and count($_SESSION["sto_infos"]) > 0 ) {
                $STO = $_SESSION["sto_infos"];
                if ( $STO->getCurrent_ipadd() == $this->current_ipadd ) {
                    //echo "<p style=\"color: red;\">PAS BESOIN DE SNITCH CAR MEME IPADD</p>"; //FOR TEST OR DEBUG ONLY
                    //current reste current
                    //Prev reste Prev et ceux quelque soit son etat
                    return FALSE;
                } else {
                    /*
                    echo "<p style=\"color: red;\">ON A BESOIN DE SNITCH CAR IPADD DIFFERENTE</p>"; //FOR TEST OR DEBUG ONLY
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, $STO->getCurrent_ipadd());
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->current_ipadd);
                    //*/
                    //L'ipadd qui était défini comme courante dans SESSION est déclarée PREV par SNITCHER. Elle sera write dans STO_INOFS par SESSION
                    $this->prev_ipadd = $STO->getCurrent_ipadd();
                    //Current pourra ensuite ecrasé le CURRENT de STO_INFOS sans perte d'information
                    return TRUE;
                }
            } else $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__);
        }
    }

    
    private function is_visitor_ip_banned () {
        
        $ip_num_ver = $this->std_ip_long($this->current_ipadd);
        
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$ip_num_ver);
        $Query = new QUERY("qryl3n1");
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Query);
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        $bdd = new WOS_DATABASE($Query->getQdbname());
        $bdd->tryConnection();
        $return = $bdd->executePrepareQueryWithResult($Query->getQbody(), array(":ipv4" => $ip_num_ver));
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$return);
        $datas = $return->fetch(PDO::FETCH_ASSOC);
        if ($datas) {
            //Pour l'instant on ne sert pas des données recoltées dans la base de données
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION);
            $this->signalError ("err_user_l329", __FUNCTION__, __LINE__);
        } 
    }

    
    public function start_snitching_for_origin_infos () { 
        $this->req_start_time = $_SERVER['REQUEST_TIME'];
        $this->current_ref = $this->handle_referer_case();
        //$this->refer = ( isset($_SERVER['HTTP_REFERER']) ) ? $_SERVER['HTTP_REFERER'] : NULL;
        $this->hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $this->ipv4_list_of_host = gethostbynamel($this->hostname);
        //$this->dns_record_of_host = dns_get_record($this->hostname);
    }
    
    
    private function handle_referer_case () {
        $STO = new SESSION_TO();
        $session_not_void = PCC_SESSION::doesSessionExistAndIsNotvoid();
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["sto"],'v_d');              
        if(!$session_not_void) { 
            //Comme on a pas de fichier de SESSION, on laisse prev_refer à NULL et current reste current
            return NULL; //TRUE car SESSION n'existe pas
        } 
        else {
            if ( key_exists("sto_infos",$_SESSION) and count($_SESSION["sto_infos"]) > 0 ) {
                if ( !isset($_SERVER['HTTP_REFERER']) ) { return NULL; } 
                else if ( $STO->getCurr_ref() == $_SERVER['HTTP_REFERER'] ) {
                    return $STO->getCurr_ref(); //En gros on ne change rien
                } else {
                    $this->prev_ref = $STO->getCurr_ref();
                    return $_SERVER['HTTP_REFERER'];
                }
            } else $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__);
        }
    }
    
    //NOt tested
    static function my_transform_ipv4_to_numeral ($entry_ip) {
        if ( isset($entry_ip) ) {
            $ABCD = explode(".", $entry_ip);
            if( count($ABCD) != 4 ) $this->signalError ("err_sys_l327", __FUNCTION__, __LINE__);
         
            $numeral_value = intval($ABCD[0]*256*256*256);
            $numeral_value += intval($ABCD[1]*256*256);
            $numeral_value += intval($ABCD[2]*256);
            $numeral_value += intval($ABCD[3]*1);
            return $numeral_value;
        } return NULL;
    }
    
    
    /**
     * Allows to convert from literal ipv4 to mumeral version.
     * We use sprint() to get the unisgned version because the result could be negative for 32bits version. see ip2long() doc for more explainations.
     * @see sprintf() 
     * @see ip2long()
     * @param string $entry_ip
     * @param bool $unisgned_form
     * @return long
     */
    private function std_ip_long ($entry_ip, $unisgned_form = true) {
            /**
            {//test de ma fonction de transformation
                $long0 = SNITCHER::my_transform_ipv4_to_numeral($entry_ip);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$long0);
            }
            //**/
        if ( isset($entry_ip) ) {
            $long = ip2long($entry_ip);
            //NOte : j'ai ajouté explode parce que, je n'ai pas confiance en ip2long
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$long);
            if ( (count(explode(".", $entry_ip)) != 4) || $long == -1 || $long === FALSE ) {  
                    $this->signalError ("err_sys_l327", __FUNCTION__, __LINE__);
            } else {
                return ($unisgned_form) ? sprintf("%u", $long) : $long ;
            }
        } else $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
    }

    
    private function acquiere_localisation_datas ($entry_ip_addr) {
        if( isset($entry_ip_addr) ) {
            $ip_num_ver = $this->std_ip_long($entry_ip_addr);
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$ip_num_ver);
            
            $Query = new QUERY("qryl3n2");
            $bdd = new WOS_DATABASE($Query->getQdbname());
            $bdd->tryConnection();
            
            $params = array( ':ip_numeral1' => $ip_num_ver, ':ip_numeral2' => $ip_num_ver );
            
            $return = $bdd->executePrepareQueryWithResult($Query->getQbody(), $params);
            $datas = $return->fetch(PDO::FETCH_ASSOC);
            $Localisation = array(array());
            $count = 0;
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$datas, 'v_d');
            
            if ($datas) {
                do {
                    $Localisation[$count] = [
                      "loc_city" => $datas["loc_city"],
                      "loc_ctr_code" => $datas["loc_ctr_code"],
                      "loc_ctr_name" => $datas["loc_ctr_name"]
                    ];
                    $count++;
                } while ( $datas = $return->fetch(PDO::FETCH_ASSOC) );
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Localisation, 'v_d');
                if ( count($Localisation) == 1 ) { // A quoi ca sert ? A refactoriser, on veut savoir s'il y a plusieurs occurence pas autre chose
                    $this->loc_city = $Localisation[0]["loc_city"];
                    $this->loc_ctr_code = $Localisation[0]["loc_ctr_code"];
                    $this->loc_ctr_name = $Localisation[0]["loc_ctr_name"];
                    
                    $this->acquire_genuine_ctr_lg_code($this->loc_ctr_code);
                } else {
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__,"<p>Error : Not located because of many choicies, Skiped</p>");
                    
                    //On ne déclenche pas d'erreur grave car on ne va pas penaliser l'utilisateur pour notre incapacité à le repérer. 
                    //On prendra donc les valeurs par défaut, c'est à ça qu'elles servent
                    //SESSION va faire son travaile choisir la bonne solution pour running_lang
                } 
            } else  {
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,"<p>Error : Not located, Skiped</p>");
                
                //On ne déclenche pas d'erreur grave car on ne va pas penaliser l'utilisateur par notre incapacité à le repérer. On prendra donc les valeurs par défaut, c'est à ça qu'elles servent
                //SESSION va faire son travaile choisir la bonne solution pour running_lang
            }
        }
        else $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
    }
    
    
    private function acquiere_iplang($entry_ip_addr) {
        if ( isset($entry_ip_addr) ) {
            $ip_num_ver = $this->std_ip_long($this->current_ipadd);
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$ip_num_ver);
            $Query = new QUERY("qryl3n6");

            $bdd = new WOS_DATABASE($Query->getQdbname());
            $bdd->tryConnection();
            $return = $bdd->executePrepareQueryWithResult($Query->getQbody(), array(":ipv4" => $ip_num_ver));
            
            $datas = $return->fetch(PDO::FETCH_ASSOC);
           
            $this->iplang = ($datas) ? $datas["iplang"] : NULL; 
        }
    }
    
    
    private function acquiere_local_lang () {
        
        $local_lang = "";
        if ( $this->prod_xmlscope["cookie_names"] ) {
           /*
            * ETAPE :
            *      On récupère le nom du COOKIE pour les langues locales au niveau de la TABLE PROD CONF
            */
           $loc_lg_cook_nm = $this->prod_xmlscope["cookie_names"]["lang_welc"];
           
//           $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$loc_lg_cook_nm]);
//           $this->endExecutionIfDebug(__FUNCTION__,__LINE__);

           /*
            * ETAPE :
            *      On vérifie si le cookie existe, si OUI, on récupère la langue et on renvoie
            */
           $CKIE_HDLR = new SRVC_CKIE_HANDLER();
           $local_lang = $CKIE_HDLR->Cookie_Exists($loc_lg_cook_nm,TRUE);
           
           $local_lang = ( $local_lang && is_string($local_lang) ) ? $local_lang : "";
        }
        
        $this->lang_local_cookie = $local_lang;
    }
    
    
    /********************************** COOKIE **********************************/
    
    public function AutoCnx_CookieExists ($ckname = NULL, $WITH_ORDERED_OPT = FALSE) {
        //QUOI : Le cookie COOKIE_AUTO_LOGIN existe t-il ? Si oui récupérer les données
        
        /*
         * NOTE :
         *      TQR_CALG = TreQR_CookieAutoLoGin
         */
        $ckname = ( $ckname ) ? : "TQR_CALG";
        
        $ckdatas;
        if ( !empty($_COOKIE[$ckname]) ){
            $ckdatas = $_COOKIE[$ckname];
        }
        
        $fdatas = [];
        if ( isset($ckdatas) && is_string($ckdatas) ) {
            $fdatas = ( $WITH_ORDERED_OPT ) ? explode(",", $ckdatas) : $ckdatas;
        }
        
        return $fdatas;
    }
    
    /***************************************************************************/
            
    private function acquire_genuine_ctr_lg_code ($entry_ctr_code) {
        if ( isset($entry_ctr_code) ) {
           // (1) We get the ctr_code and try to get the lang corresponding.
            $Query = new QUERY("qryl3n3");
            
            $bdd = new WOS_DATABASE($Query->getQdbname());
            $bdd->tryConnection();
            
            $params = array( ':ctr_code' => $entry_ctr_code );
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$params);
            $return = $bdd->executePrepareQueryWithResult($Query->getQbody(), $params);
            $datas = $return->fetch(PDO::FETCH_ASSOC);
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$entry_ctr_code,$datas]);
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            
            if ( $datas ) {//Si on ne trouve aucune langue, on met NULL. C'est à SEESION de gérer la suite
                do {  
                    $Langs_Infos[] = [
                      "loc_ctr_lg_code" => $datas["loc_ctr_lg_code"],
                      "loc_ctr_flag" => $datas["loc_ctr_flag"]
                    ];
                    
                } while ( $datas = $return->fetch(PDO::FETCH_ASSOC) );
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Langs_Infos);
                
//                $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$Langs_Infos,$this->prod_xmlscope["available_langs"]]);
//                $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
                
                /*
                 * [DEPUIS 10-07-16]
                 */
                $available_langs = $this->prod_xmlscope["available_langs"]["lang"];
                foreach ($Langs_Infos as $Lang_Info) {
//                    var_dump($Lang_Info["loc_ctr_lg_code"],$available_langs);
//                    exit();
                    //if ($Lang_Info["is_installed"] == '1') { //NON : Il est plus simple de récupérer ces données dans le fichier de conf. 
                    //En effet, modifier la base de données doit être exceptionnel au vu des ressources que cela necessite.
                    if ( in_array($Lang_Info["loc_ctr_lg_code"], $available_langs)) { //Cette façon de faire fonction car la requete demande les resultats de la langue parlée la plus importante vers la moins importante    
                        $this->loc_ctr_lg_code = $Lang_Info["loc_ctr_lg_code"];
                        $this->loc_ctr_flag = $Lang_Info["loc_ctr_flag"];
                        break;
                    } 
                }
                //If not isset : It's means we didn't found any installed language among provided langs.
                $this->loc_ctr_lg_code = ( !isset($this->loc_ctr_lg_code) ) ? NULL : $this->loc_ctr_lg_code;
//                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->loc_ctr_lg_code);
//                $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            }  else {
                $this->loc_ctr_lg_code = NULL; 
            }    
        } else $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
    }
    
    
    private function build_snitch_infos() {
        $snitch_infos_array = array();
        
        $snitch_infos_array["current_ref"] = $this->current_ref;
        $snitch_infos_array["prev_ref"] = $this->prev_ref;
        $snitch_infos_array["req_start_time"] = $this->req_start_time;
        $snitch_infos_array["current_ipadd"] = $this->current_ipadd;
        $snitch_infos_array["prev_ipadd"] = $this->prev_ipadd;
        $snitch_infos_array["hostname"] = $this->hostname; 
        $snitch_infos_array["ipv4_list_of_host"] = $this->ipv4_list_of_host;
        $snitch_infos_array["iplang"] = $this->iplang;
        //$snitch_infos_array[""] = $this->dns_record_of_host;
        //$snitch_infos_array[""] = $this->browzer;
        //$snitch_infos_array[""] = $this->os;
        $snitch_infos_array["loc_city"] = $this->loc_city;
        $snitch_infos_array["loc_ctr_code"] = $this->loc_ctr_code;
        $snitch_infos_array["loc_ctr_name"] = $this->loc_ctr_name;
        $snitch_infos_array["loc_ctr_lg_code"] = $this->loc_ctr_lg_code;
        $snitch_infos_array["loc_ctr_flag"] = $this->loc_ctr_flag;
        
        /*
         * [DEPUIS 10-07-16]
         */
        $snitch_infos_array["lang_local_cookie"] = $this->lang_local_cookie;
        
        $this->snitch_infos_array = $snitch_infos_array;
       //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->snitch_infos_array);
    }
    
    
    private function build_snitch_infos_from_session () {
        $STO = new SESSION_TO();
        $STO = $_SESSION["sto_infos"];
        
        $snitch_infos_array = array();
        
        $snitch_infos_array["current_ref"] = $STO->getCurr_ref();
        $snitch_infos_array["prev_ref"] = $STO->getPrev_ref();
        $snitch_infos_array["req_start_time"] = $STO->getUrq_start_date();
        $snitch_infos_array["current_ipadd"] =$STO->getCurrent_ipadd();
        $snitch_infos_array["prev_ipadd"] = $STO->getPrev_ipadd();
        $snitch_infos_array["hostname"] = $STO->getHostname(); 
        $snitch_infos_array["ipv4_list_of_host"] = $STO->getIp_list_of_host();
        $snitch_infos_array["iplang"] = $this->iplang;
        //$snitch_infos_array[""] = $this->dns_record_of_host;
        //$snitch_infos_array[""] = $this->browzer;
        //$snitch_infos_array[""] = $this->os;
        $snitch_infos_array["loc_city"] = $STO->getCity_name();
        $snitch_infos_array["loc_ctr_code"] = $STO->getCtr_code();
        $snitch_infos_array["loc_ctr_name"] = $STO->getCtr_name();
        $snitch_infos_array["loc_ctr_lg_code"] = $STO->getCtr_lg_code();
        $snitch_infos_array["loc_ctr_flag"] = $STO->getCtr_flag();
        
        /*
         * [DEPUIS 10-07-16]
         */
        $this->acquiere_local_lang();
        $snitch_infos_array["lang_local_cookie"] = $this->lang_local_cookie;
        
        $this->snitch_infos_array = $snitch_infos_array;
       //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->snitch_infos_array);
    }
    /******************************************************************************************************************/
    /********************************************* START GETTERS AND SETTERS ******************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getProd_xmlscope() {
        return $this->prod_xmlscope;
    }

    public function setProd_xmlscope($prod_xmlscope) {
        $this->prod_xmlscope = $prod_xmlscope;
    }

    public function getCurrent_ref() {
        return $this->current_ref;
    }

    public function setCurrent_ref($current_ref) {
        $this->current_ref = $current_ref;
    }

    public function getPrev_ref() {
        return $this->prev_ref;
    }

    public function setPrev_ref($prev_ref) {
        $this->prev_ref = $prev_ref;
    }

    public function getReq_start_time() {
        return $this->req_start_time;
    }

    public function setReq_start_time($req_start_time) {
        $this->req_start_time = $req_start_time;
    }

    public function getCurrent_ipadd() {
        return $this->current_ipadd;
    }

    public function setCurrent_ipadd($current_ipadd) {
        $this->current_ipadd = $current_ipadd;
    }

    public function getPrev_ipadd() {
        return $this->prev_ipadd;
    }

    public function setPrev_ipadd($prev_ipadd) {
        $this->prev_ipadd = $prev_ipadd;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function getIpv4_list_of_host() {
        return $this->ipv4_list_of_host;
    }

    public function setIpv4_list_of_host($ipv4_list_of_host) {
        $this->ipv4_list_of_host = $ipv4_list_of_host;
    }

    public function getIplang() {
        return $this->iplang;
    }

    public function setIplang($iplang) {
        $this->iplang = $iplang;
    }

    public function getLoc_city() {
        return $this->loc_city;
    }

    public function setLoc_city($loc_city) {
        $this->loc_city = $loc_city;
    }

    public function getLoc_ctr_code() {
        return $this->loc_ctr_code;
    }

    public function setLoc_ctr_code($loc_ctr_code) {
        $this->loc_ctr_code = $loc_ctr_code;
    }

    public function getLoc_ctr_name() {
        return $this->loc_ctr_name;
    }

    public function setLoc_ctr_name($loc_ctr_name) {
        $this->loc_ctr_name = $loc_ctr_name;
    }

    public function getLoc_ctr_lg_code() {
        return $this->loc_ctr_lg_code;
    }

    public function setLoc_ctr_lg_code($loc_ctr_lg_code) {
        $this->loc_ctr_lg_code = $loc_ctr_lg_code;
    }

    public function getLoc_ctr_flag() {
        return $this->loc_ctr_flag;
    }

    public function setLoc_ctr_flag($loc_ctr_flag) {
        $this->loc_ctr_flag = $loc_ctr_flag;
    }

    public function getSnitch_infos_array() {
        return $this->snitch_infos_array;
    }

    public function setSnitch_infos_array($snitch_infos_array) {
        $this->snitch_infos_array = $snitch_infos_array;
    }

    // </editor-fold>


    /********************************************* END GETTERS AND SETTERS ********************************************/
    /******************************************************************************************************************/

}

?>
