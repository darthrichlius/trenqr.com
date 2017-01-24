<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_acc_psdAvaChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function acc_pseudoAvailable($args){
        $ACC = new ACCOUNT();
        $ACC->load_entity($args);
        $taken = $ACC->is_pseudo_taken($args['pseudo']);
        if($taken == TRUE){
            $storedPseudo = $ACC->getAccpseudo();
            if(strtolower($storedPseudo) == strtolower($args['pseudo'])){
                return TRUE;
            } else {
                return FALSE;
            }
        } else if($taken == FALSE){
            return TRUE;
        } else {
            return 'PSDAVA_UNK_ERR';
        }
    }

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->acc_pseudoAvailable($this->KDIn["datas"]);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        echo json_encode(['isAvailable' => $this->KDOut["r"]]);
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