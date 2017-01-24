<?php

/**
 * Description of ImageHandler
 *
 * @author arsphinx
 */
class SRVC_CKIE_HANDLER extends MOTHER {
    
    private $dflt_lifetime;
    private $dflt_glue;
    
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        
        /*
         * NOTE :
         *      La valeur par DEFAULT est : 1 mois (30 jours)
         *      Cette valeur est totalement arbitraire. 
         */
        $this->dflt_lifetime = 30*24*3600;
        
        $this->dflt_glue = ",";
    }
    
    
    public function Cookie_Exists ($ckname, $W_DATAS = FALSE) {
        //QUOI : Le cookie COOKIE existe t-il ? Si oui renvoyer les données
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ckname]);
        
        $ckdatas;
        if ( !empty($_COOKIE[$ckname]) ){
            $ckdatas = $_COOKIE[$ckname];
        }
        
        if ( $W_DATAS ) {
            return ( !empty($ckdatas) ) ? $ckdatas : FALSE;
        }
        else {
            return ( !empty($ckdatas) ) ? TRUE : FALSE;
        }
    }
    
    public function Cookie_Set ($ckname, $ckdatas, $lifetime = NULL, $glue = NULL) {
        //QUOI : Crée un COOKIE avec le nom passé en paramètre et l'enregistre au niveau du navigateur
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ckname, $ckdatas]);
        
        /*
         * ETAPE :  
         *      On détermine la valeur EXPIRE en fonction des données disponibles
         */
        $expires = ( $lifetime ) ? time()+$lifetime : time()+$this->dflt_lifetime;
        
        if ( is_string($ckdatas) ) {
            $value = $ckdatas;
        } else if ( is_array($ckdatas) ) {
            /*
             * ETAPE : 
             *      On construit la chaine de données
             */
            foreach ( $ckdatas as $val ) {
                $value = ( !isset($value) ) ? $val : $value.$this->dflt_glue.$val;
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$ckname, $value, $expires]);
//        exit();
        
        /*
         * ETAPE :
         *      On crée et envoie le COOKIE
         */
        setcookie($ckname, $value, $expires, "/");
        
        return TRUE;
    }
    
    public function Cookie_Del ($ckname) {
        //QUOI : Détruit le COOKIE_AUTO_LOGIN
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ckname]);
        
        /*
         * ETAPE :
         *      On détruit le COOKIE en changeant EXPIRES
         */
        setcookie ($ckname, "", time() - 3600, "/");
    }
    
    
    /***********************************************************************************************************/
    /**************************************** GETTERS and SETTERS **********************************************/

    function getDflt_lifetime() {
        return $this->dflt_lifetime;
    }

    function getDflt_glue() {
        return $this->dflt_glue;
    }

}