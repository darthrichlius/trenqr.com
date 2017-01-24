<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_del_canDel extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function del_cancelRequest($accid){
        $ACC = new ACCOUNT();

        $accAlterData = [
            'accid' => $accid,
            'todelete' => 0
        ];

        $rt1 = $ACC->on_alter_entity($accAlterData);
        if($rt1 != 1){
            //Erreur dans l'alter_entity
            return $rt1;
        }

        $rt2 = $ACC->cancel_delete_request($accid);
        if($rt2 != 1){
            //Erreur dans l'update dans la table delacc_history
            return $rt2;
        }
        
        //Si on arrive là c'est que tout est OK
        return 1;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rt = $this->del_cancelRequest($this->KDIn["accid"]);
        $this->KDOut["rt"] = $rt;
    }

    public function on_process_out() {
        //DECONNEXION
        $CH = new CONX_HANDLER();
        //RAPPEL : Renvoie TRUE si tout s'est bien passÃ©
        $CH->try_logout();
        
        echo json_encode(["rt" => $this->KDOut["rt"]]);
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