<?php

//[NOTE 10-08-14]
class WTO extends MOTHER
{
    //Certaines variables peuvent ou ne pas être instanciées.
    //C'est au Controller de gérer ce cas
    private $user;
    private $userSuppliedPage;
    private $userSuppliedPageLang;
    private $sysSuggestPageBasedOnRunningLang;
    private $watchman_decision_on_lang;
    private $urqid;
    private $ups_required;
    private $ups_optional;
    
    
    function __construct() {
        
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /********************************************************************************************************************/
    /******************************************* START GETTERS AND SETTERS **********************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUserSuppliedPage() {
        return $this->userSuppliedPage;
    }

    public function setUserSuppliedPage($userSuppliedPage) {
        $this->userSuppliedPage = $userSuppliedPage;
    }

    public function getUserSuppliedPageLang() {
        return $this->userSuppliedPageLang;
    }

    public function setUserSuppliedPageLang($userSuppliedPageLang) {
        $this->userSuppliedPageLang = $userSuppliedPageLang;
    }

    public function getSysSuggestPageBasedOnRunningLang() {
        return $this->sysSuggestPageBasedOnRunningLang;
    }

    public function setSysSuggestPageBasedOnRunningLang($sysSuggestPageBasedOnRunningLang) {
        $this->sysSuggestPageBasedOnRunningLang = $sysSuggestPageBasedOnRunningLang;
    }

    public function getWatchman_decision_on_lang() {
        return $this->watchman_decision_on_lang;
    }

    public function setWatchman_decision_on_lang($watchman_decision_on_lang) {
        $this->watchman_decision_on_lang = $watchman_decision_on_lang;
    }

    public function getUrqid() {
        return $this->urqid;
    }

    public function setUrqid($urqid) {
        $this->urqid = $urqid;
    }

    public function getUps_required() {
        return $this->ups_required;
    }

    public function setUps_required($ups_required) {
        $this->ups_required = $ups_required;
    }

    public function getUps_optional() {
        return $this->ups_optional;
    }

    public function setUps_optional($ups_optional) {
        $this->ups_optional = $ups_optional;
    }



// </editor-fold>

    /********************************************* END GETTERS AND SETTERS **********************************************/
    /********************************************************************************************************************/

}
?>
