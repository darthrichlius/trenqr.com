<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_secu_load extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function secu_loadSecurityData($accid){
        $ACC = new ACCOUNT();
        $ACC->load_entity(['accid' => $accid]);
        $data = [
            'id'            => $accid,
            'stayconn'      => $ACC->getStaycon(),
            'cowithpseudo'  => $ACC->getSecu_coWithPseudoEna(),
            'thirdcriteria' => $ACC->getSecu_isThirdCritEna(),
            'hlock_start'   => $ACC->getSecu_lock_h_start(),
            'hlock_end'     => $ACC->getSecu_lock_h_end(),
            'dlock_start'   => $ACC->getSecu_lock_d_start(),
            'dlock_end'     => $ACC->getSecu_lock_d_end()
        ];
        return $data;
    }
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $data = $this->secu_loadSecurityData($this->KDIn["accid"]);
        $this->KDOut["data"] = $data;
    }

    public function on_process_out() {
        echo json_encode($this->KDOut["data"]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
//        //TODO: Récupérer l'ID de l'utilisateur connecté via $_SESSION['']
//        //En attendant: simulation
//        $this->KDIn['accid'] = 95;
        
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        } else {
            $this->KDIn['accid'] = $oid;
        }
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>