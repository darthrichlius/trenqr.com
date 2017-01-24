<?php

class SESSION_TO extends MOTHER {
    // -->> visitor_infos
    private $visitor_type;

    // -->> snitch_infos
    /**
     * Pour l'instant avoir l'historique des refer ne sert à rien dans le process.
     */
    private $curr_ref;
    private $prev_ref;
    private $current_ipadd;
    private $prev_ipadd;
    private $hostname; 
    private $ip_list_of_host;
    private $city_name;
    private $ctr_code;
    private $ctr_name;
    private $ctr_flag;
    private $ctr_lg_code;
    private $urq_start_date;
    // -->> Carrier
    private $curr_wto;
    private $prev_wto;
    private $urq_scope;
    // -->> Datation
    private $session_creadate;
    private $session_creadate_tstamp;
    // -->> Others
    private $running_lang;
    private $default_lang;
    /**
     * [NOTE au 02-12-13]
     * Certains autres modules ont besoin des informations contenues dans le fichier de conf du produit pour leur fonctionnement
     * Il est donc primordial de l'insérer.
     * Cependant, cet ajout est fait pour ne pas altérer le code originel (post 02-12-13)
     */
    private $prod_xmlscope;
    
    function __construct($file="",$class="") {

        //We ensure that until the mother this class is innit.
        $file = ( isset($file) and $file != "" ) ? $file : __FILE__;
        $class = ( isset($class) and $class != "" ) ? $class : __CLASS__;
        parent::__construct($file,$class);
        //SETTING DEFAULT VALUES
        $this->initNewSession();
    }
    
    /******************************************************************************************************************/
    /****************************************** START PUBLIC FUNCTIONS SECTION ****************************************/
    
    public function initNewSession () {
		/*
		session_start() ;
		session_destroy();
		//*/
		/*
		var_dump(__LINE__,$_SESSION);
		exit()
        //*/
		 
		  
        if( !$this->checkIfSessionExits() ) { 
            session_start(); 
            $this->session_creadate = date("Y-m-d H:i:s");
            $this->session_creadate_tstamp = round(microtime(TRUE)*1000);
            
			 // var_dump($_SESSION);
           // exit();
		   
			//*
		    $CH = new CONX_HANDLER();
			// var_dump($CH->is_connected());
			// exit();
            /* $CH->try_logout (); */
			//*/
			
            /*
            require_once WOS_PATH_INC_ENTITIES;
            $CH = new CONX_HANDLER();
        
            $A = new PROD_ACC();
            $A->on_read_entity(["acc_eid" => "4n3g4n1n1l4n32"]);

            $SID = session_id();

//            var_dump($_SESSION);
//            exit();

            // $CH->try_login($A, $SID);
            $CH->try_logout ();
            
            
            
//            var_dump($_SESSION);
//            exit();
            
            //*/
        } else { 
            if ( @defined(RIGHT_IS_DEBUG) and @RIGHT_IS_DEBUG) echo "<br/>FOR DEBUG : A SESSION already exists.<br/>"; 
        }  
		 
		 
    }
    
    /**
     * <p>Use this function if you want to rengen a Session_id. You will have to choose if you want to delete the old_session_file.
     * By default the value is \'false\'. Caution : Regen a SSID won't erase the old values inner the files.
     * In fact, whatever the answer in param, the old values will be returned back into the new file. 
     * @see destroySessionAndCreateFreshNewSession() If you to create a new SSID and remove the old values. 
     * @param type $delete_old_file
     */
    public function RegenerateSession ($delete_old_file) {
        $ok = session_regenerate_id($delete_old_file);
        if (!$ok) {  $this->signalError("sys_err_l314", __FUNCTION__, __LINE__); }
    } 
    
    /**
     * <p>Use this funcyion in case you want to destroy a SESSION. For example when an user logout.
     * This is well-thought because we unset the params before destroying the SESSION to make sure the function is 
     * sucure and reliable.</p>
     */
    public function destroySession () {
        if( $this->checkIfSessionExits() ) {
            session_unset();
            $ok = session_destroy();
            if (!$ok) {  $this->signalError("sys_err_l315", __FUNCTION__, __LINE__); }
        }        
    }
    
    /**
     * <p>Use this function to destroy an old SESSION and wipe the inner params. We can choose whether you want
     * to delete session_file. The default value is \'TRUE\'.</p>
     * @param bool $delete_old_file
     * @see RegenerateSession() If your goal is just to reinit the SSID without wipe the old_vales.
     */
    public function destroySessionAndCreateFreshNewSession ($delete_old_file = TRUE) {
        if( $this->checkIfSessionExits() ) {
            //We're making sure all the old params in SESSION won't be taken back in the new SESSION. 
            session_unset();
            $ok = session_regenerate_id($delete_old_file);
            if (!$ok) {  $this->signalError("sys_err_l314", __FUNCTION__, __LINE__); }
        }
    }
        
   /**
    * <p>Use this function to get the current Session Status. You can also use it in a DEBUG way. 
    * Actually, you can display the function the verbose of the Session Status.</p> 
    * @return string string The verbose of the Session Status.
    */
    public function getSessionStatus() {
        
        $status = session_status();

        if($status == PHP_SESSION_DISABLED)
        {
            if (RIGH_IS_DEBUG) echo "<br/>FOR DEBUG : Session is Disabled<br/>";
            return PHP_SESSION_DISABLED;
        }
        else if($status == PHP_SESSION_NONE )
        {
            if (RIGH_IS_DEBUG) echo "<br/>FOR DEBUG : Session Enabled but No Session values Created<br/>";
            return PHP_SESSION_NONE;
        }
        else
        {
            if (RIGH_IS_DEBUG) echo "<br/>FOR DEBUG : Session Enabled and Session values Created<br/>";
            return PHP_SESSION_ACTIVE;
        }
    }
    
    /**
     * <p>Use this function to check if a Session exists. It's useful when you want to process a Session but you can't remember if a Session exists. 
     * Nevertheless you don't need this one before trying to create a new Session because the told-function will have already checked it out.</p>
     * @see initNewSession ()
     * @return bool 
     */
    public function checkIfSessionExits() {
        $status = session_status();
        return ( $status === PHP_SESSION_NONE or $status === PHP_SESSION_DISABLED) ? FALSE : TRUE;
    }
    /*
     $snitch_infos_array[""] = $STO->getCurr_ref();
        $snitch_infos_array[""] = $STO->getPrev_ref();
        $snitch_infos_array[""] = $STO->getUrq_start_date();
        $snitch_infos_array[""] =$STO->getCurrent_ipadd();
        $snitch_infos_array[""] = $STO->getPrev_ipadd();
        $snitch_infos_array[""] = $STO->getHostname(); 
        $snitch_infos_array[""] = $STO->getIp_list_of_host();
        $snitch_infos_array["iplang"] = $this->iplang;
        //$snitch_infos_array[""] = $this->dns_record_of_host;
        //$snitch_infos_array[""] = $this->browzer;
        //$snitch_infos_array[""] = $this->os;
        $snitch_infos_array[""] = $STO->getCity_name();
        $snitch_infos_array[""] = $STO->getCtr_code();
        $snitch_infos_array[""] = $STO->getCtr_name();
        $snitch_infos_array[""] = $STO->getCtr_lg_code();
        $snitch_infos_array[""] = $STO->getCtr_flag();
    */
    public function insert_all_datas_into_session ($prod_xmlscope, $run_lang, $v_type, SRVC_SNITCHER $snitcher, $prev_wto, $curr_wto, $urq_scope ) {
        $this->crea_date = date("Y-m-d H:i:s");        
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //If we're here, it means each var is set.
        $this->visitor_type     = $v_type;
        $this->curr_ref         = $snitcher->getSnitch_infos_array()["current_ref"];
        $this->prev_ref         = $snitcher->getSnitch_infos_array()["prev_ref"];
        $this->current_ipadd    = $snitcher->getSnitch_infos_array()["current_ipadd"];
        $this->prev_ipadd       = $snitcher->getSnitch_infos_array()["prev_ipadd"];
        $this->hostname         = $snitcher->getSnitch_infos_array()["hostname"]; 
        $this->ip_list_of_host  = $snitcher->getSnitch_infos_array()["ipv4_list_of_host"];
        $this->city_name        = $snitcher->getSnitch_infos_array()["loc_city"];
        $this->ctr_code         = $snitcher->getSnitch_infos_array()["loc_ctr_code"];
        $this->ctr_name         = $snitcher->getSnitch_infos_array()["loc_ctr_name"];
        $this->ctr_flag         = $snitcher->getSnitch_infos_array()["loc_ctr_flag"];
        $this->ctr_lg_code      = $snitcher->getSnitch_infos_array()["loc_ctr_lg_code"];
        $this->urq_start_date   = $snitcher->getSnitch_infos_array()["req_start_time"];
        $this->curr_wto         = $curr_wto;
        $this->prev_wto         = $prev_wto;
        $this->urq_scope        = $urq_scope;
        $this->running_lang     = $run_lang;
        $this->default_lang     = $prod_xmlscope["default_lang"];
        $this->prod_xmlscope    = $prod_xmlscope;
    }
    
    
    /****************************************** END PUBLIC FUNCTIONS SECTION ******************************************/
    /******************************************************************************************************************/
    
    /******************************************************************************************************************/
    /****************************************** START PRIVATE FUNCTIONS SECTION ***************************************/
    
    
    /****************************************** END PRIVATE FUNCTIONS SECTION *****************************************/
    /******************************************************************************************************************/
    
    /******************************************************************************************************************/
    /******************************************* START GETTERS SETTERS SECTION ****************************************/
    /* BAD THINKING
    public function getSsid() {
        return $this->ssid;
    }

    public function setSsid($ssid) {
        $this->ssid = $ssid;
    }
    */
    // <editor-fold defaultstate="collapsed" desc="Getters and Setters">
    public function getVisitor_type() {
        return $this->visitor_type;
    }

    public function setVisitor_type($visitor_type) {
        $this->visitor_type = $visitor_type;
    }

    public function getCurr_ref() {
        return $this->curr_ref;
    }

    public function setCurr_ref($curr_ref) {
        $this->curr_ref = $curr_ref;
    }

    public function getPrev_ref() {
        return $this->prev_ref;
    }

    public function setPrev_ref($prev_ref) {
        $this->prev_ref = $prev_ref;
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
    
    public function getIp_list_of_host() {
        return $this->ip_list_of_host;
    }

    public function setIp_list_of_host($ip_list_of_host) {
        $this->ip_list_of_host = $ip_list_of_host;
    }

    public function getCity_name() {
        return $this->city_name;
    }

    public function setCity_name($city_name) {
        $this->city_name = $city_name;
    }

    public function getCtr_code() {
        return $this->ctr_code;
    }

    public function setCtr_code($ctr_code) {
        $this->ctr_code = $ctr_code;
    }

    public function getCtr_name() {
        return $this->ctr_name;
    }

    public function setCtr_name($ctr_name) {
        $this->ctr_name = $ctr_name;
    }

    public function getCtr_flag() {
        return $this->ctr_flag;
    }

    public function setCtr_flag($ctr_flag) {
        $this->ctr_flag = $ctr_flag;
    }

    public function getCtr_lg_code() {
        return $this->ctr_lg_code;
    }

    public function setCtr_lg_code($ctr_lg_code) {
        $this->ctr_lg_code = $ctr_lg_code;
    }

    public function getUrq_start_date() {
        return $this->urq_start_date;
    }

    public function setUrq_start_date($urq_start_date) {
        $this->urq_start_date = $urq_start_date;
    }

    public function getCurr_wto() {
        return $this->curr_wto;
    }

    public function setCurr_wto($curr_wto) {
        $this->curr_wto = $curr_wto;
    }

    public function getPrev_wto() {
        return $this->prev_wto;
    }

    public function setPrev_wto($prev_wto) {
        $this->prev_wto = $prev_wto;
    }

    public function getUrq_scope() {
        return $this->urq_scope;
    }

    public function setUrq_scope($urq_scope) {
        $this->urq_scope = $urq_scope;
    }

    public function getSession_creadate() {
        return $this->session_creadate;
    }
    //[NOTE 25-08-14] @author L.C. : Pas logique !
//    public function setSession_creadate($session_creadate) {
//        $this->session_creadate = $session_creadate;
//    }
    
    public function getSession_creadate_tstamp() {
        return $this->session_creadate_tstamp;
    }

    public function getRunning_lang() {
        return $this->running_lang;
    }

    public function setRunning_lang($running_lang) {
        $this->running_lang = $running_lang;
    }

    public function getDefault_lang() {
        return $this->default_lang;
    }
    
    public function getProd_xmlscope() {
        return $this->prod_xmlscope;
    }

    // </editor-fold>
    
        
    /******************************************** END GETTERS SETTERS SECTION *****************************************/
    /******************************************************************************************************************/
}
        
?>