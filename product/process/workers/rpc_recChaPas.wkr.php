<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_rpc_recChaPas extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function rpc_recoveryChangePasswd($key, $new_passwd){
        $ACC = new ACCOUNT();
        $pwChanged = $ACC->passwd_reinit_change($key, $new_passwd);
        return $pwChanged;
}
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->rpc_recoveryChangePasswd($this->KDIn["k"], $this->KDIn["npw"]);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        echo json_encode(["confirmation" => $this->KDOut["r"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn["k"] = $_POST["datas"]["key"];
        $this->KDIn["npw"] = $_POST["datas"]["newPasswd"];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>