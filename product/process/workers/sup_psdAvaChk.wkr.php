<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_sup_psdAvaChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function sup_pseudoAvailabilityCheck($pseudo){
        $ACC = new ACCOUNT();
        $available = $ACC->is_pseudo_taken($pseudo);
        return $available;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $ava = $this->sup_pseudoAvailabilityCheck($this->KDIn['pseudo']);
        //Boolean
        $this->KDOut['available'] = $ava;
    }

    public function on_process_out() {
        echo json_encode(['taken' => $this->KDOut['available']]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['pseudo'] = $_POST['datas']['pseudo'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>