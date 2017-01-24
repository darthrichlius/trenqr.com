<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_hp_prgpsdchk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function hp_preregPseudoCheck($pseudo){
    if($pseudo != ''){
        $err_ref = null;
        $PRG = new PREREG();
        $ACC = new ACCOUNT();
        
        $prgCheck = $PRG->CheckPseudoExists($pseudo, false, $err_ref);
        $accCheck = $ACC->CheckPseudoExists($pseudo, false, $err_ref);
        
        if(!isset($err_ref)){
            //Pseudo dispo
            return $pseudo;
        } else {
            //Pseudo non dispo
            return FALSE;
        }
        
    } else {
        //Renvoi d'erreur
        return false;
    }
}
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->hp_preregPseudoCheck($this->KDIn["pseudo"]);
        $this->KDOut['r'] = $r;
    }

    public function on_process_out() {
        echo json_encode(['available' => $this->KDOut['r']]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $psd = $_POST['datas']['pseudo'];
        $this->KDIn['pseudo'] = $psd;
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>