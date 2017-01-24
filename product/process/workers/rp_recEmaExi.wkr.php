<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_rp_recEmaExi extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function rp_recoveryMailExists($email){
        $EMA = new EMAIL();
        $regMail = $EMA->getReg();
        if(isset($email) && preg_match_all($regMail, $email)){
            $emOk = $EMA->exists_and_is_used($email);
            if($emOk == FALSE){
                //Si on a un retour FALSE c'est que l'email n'est pas disponible,
                //donc qu'il est actuellement assigné à un compte
                return TRUE;
            } else {
                //unknown_email
                return FALSE;
            }
        } else {
            //email_error
            return FALSE;
        }
    }

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->rp_recoveryMailExists($this->KDIn['email']);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        echo json_encode(['okForRecovery' => $this->KDOut["r"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['email'] = $_POST['datas']['email'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>