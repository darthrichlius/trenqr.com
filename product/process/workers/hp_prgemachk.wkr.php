<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_hp_prgemachk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    private function hp_preregEmailCheck($email){
    if($email != ''){
        $EMA = new EMAIL();
        
        $emaCheck = $EMA->exists_and_is_used($email);
        
        if( $emaCheck ){
            //Mail inconnu
            //OK et on continue
            return $email;
        } else {
            //Mail déjà en base, donc pas possible de l'insérer
            return FALSE;
        }
        
    } else {
        //Renvoi d'erreur
        return FALSE;
    }
}
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->hp_preregEmailCheck($this->KDIn["email"]);
        $this->KDOut['r'] = $r;
    }

    public function on_process_out() {
        echo json_encode(['available' => $this->KDOut["r"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $email = $_POST['datas']['email'];
        $this->KDIn['email'] = $email;
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>