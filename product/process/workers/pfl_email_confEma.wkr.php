<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_email_confEma extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function emaconf($key){
        $EMA = new EMAIL();
        $rVal = $EMA->email_confirmation_action($key);
        return $rVal;
    }

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rVal = $this->emaconf($this->KDIn["k"]);
        $this->KDOut["rVal"] = $rVal;
    }

    public function on_process_out() {
        echo json_encode(["r" => $this->KDOut["rVal"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['k'] = $_POST['datas']['key'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>