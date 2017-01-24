<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_sup_emaAvaChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function sup_emailAvailabilityCheck($email){
        $EMA = new EMAIL();
        $available = $EMA->exists_and_is_used($email);
        return $available;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $ava = $this->sup_emailAvailabilityCheck($this->KDIn['email']);
        //Boolean
        $this->KDOut['available'] = $ava;
    }

    public function on_process_out() {
        echo json_encode(['available' => $this->KDOut['available']]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['email'] = $_POST['datas']['email'];
//        $this->KDIn['email'] = "ttrrtte@ondeuslynn.com";
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>