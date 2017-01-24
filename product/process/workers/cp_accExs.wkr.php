<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_accExs extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function cp_accountExists($login){
        $CNX = new CONNECTION();
        $ACC = new ACCOUNT();

        $type = $CNX->login_detect($login);
        $accid = $ACC->get_accid_from_login($login, $type);
        return $accid;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $accid = $this->cp_accountExists($this->KDIn['login']);
        $this->KDOut['exists'] = $accid;
    }

    public function on_process_out() {
        echo json_encode(['exists' => $this->KDOut['exists']]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['login'] = $_POST['datas']['login'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>