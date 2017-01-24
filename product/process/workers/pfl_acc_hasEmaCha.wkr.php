<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_acc_hasEmaCha extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function acc_hasEmailChanged($args){
        $EMA = new EMAIL();
        $storedEmail = $EMA->get_email_from_account($args['accid']);
        
        if($storedEmail == 'QUERY_ERROR'){
            return 'HASEMACHA_QRY_ERR';
        } else if(strcasecmp($storedEmail, $args['email']) != 0){
            //Email différent
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->acc_hasEmailChanged($this->KDIn["datas"]);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        echo json_encode(["changed" => $this->KDOut["r"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['datas'] = $_POST['datas'];
//        //TODO: Récupérer l'ID de l'utilisateur connecté via $_SESSION['']
//        //En attendant: simulation
//        $this->KDIn['datas']['accid'] = 95;
        
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        } else {
            $this->KDIn['datas']['accid'] = $oid;
        }
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>