<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_prg extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    /* ---- CURRENT OWNER IDENTITY ---- */
//    private function AcquireData(){
//        //TODO: Récupérer les infos depuis l'URL (FN/P/EM)
//        
//        //SIMULATION
//        $UD['em'] = 'toto@gmail.com';
//        $UD['p'] = 't0t0';
//        $UD['fn'] = 'Toto DUVAL';
//        
//        //Sécurisation de l'email
//        $EMA = new EMAIL();
//        $ctrlEmail = $EMA->email_validation($UD['em']);
//        if($ctrlEmail){
//            $this->KDIn["cem"] = $ctrlEmail;
//        } else {
//            $this->KDIn["cem"] = "";
//        }
//        
//        //Sécurisation du pseudo
//        $ACC = new ACCOUNT();
//        $ctrlPsd = $ACC->pseuso_validation($UD['p']);
//        if($ctrlPsd){
//            $this->KDIn["cp"] = $ctrlPsd;
//        } else {
//            $this->KDIn["cp"] = "";
//        }
//        
//        //Sécurisation du fullname
//        $PFL = new PROFIL();
//        $ctrlFn = $PFL->pseuso_validation($UD['fn']);
//        if($ctrlFn){
//            $this->KDIn["fn"] = $ctrlFn;
//        } else {
//            $this->KDIn["fn"] = "";
//        }
//        
//    }
//    
//    /** TODO: Faire les fonctions de récupération **/
//    
//   private function GetUrlData () {
//       return $this->KDIn;
//   }
        
    
    

    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {        
        $k = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["pa"];
        
        $this->KDIn['k'] = $k;
        
    }
    
    public function on_process_in() {
                var_dump($_SESSION);
        $PRG = new PREREG();
        $ld = $PRG->load_data_from_key($this->KDIn["k"]);
        $this->KDOut['fn'] = $ld["prg_fullname"];
        $this->KDOut['p'] = $ld["prg_pseudo"];
        $this->KDOut['em'] = $ld["prg_email"];
    }

    public function on_process_out() {
        
        foreach ($this->KDOut as $k => $v) {
            $_SESSION['ud_carrier'][$k] = $v;
        }
        
    }
    
    /**
     * @obsolete
     */
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>