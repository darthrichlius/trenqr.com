<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_toDeAcCan extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    function cp_todeleteAccountCancel($login){
        $CNX = new CONNECTION();
        $logintype = $CNX->login_detect($login);

        $ACC = new ACCOUNT();
        $accid = $ACC->get_accid_from_login($login, $logintype);
        $accAlterData = [
            'accid' => $accid,
            'todelete' => 0
        ];
        
        $rt1 = $ACC->on_alter_entity($accAlterData);
        if($rt1 != NULL){
            //Erreur dans l'alter_entity
            return $rt1;
        }

        $rt2 = $ACC->cancel_delete_request($accid);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $rt2,'v_d');
        if($rt2 != NULL){
            //Erreur dans l'update dans la table delacc_history
            return $rt2;
        }
    }

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $chk = $this->cp_todeleteAccountCancel($this->KDIn['login']);
        if($chk){
            //Récupération d'infos pour le debug
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $chk,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            //Gestion du cas pour l'utilisateur
            $this->KDOut['er'] = TRUE;
        }
    }

    public function on_process_out() {
        if(isset($this->KDOut['er'])){
            echo json_encode(['er' => $this->KDOut['er']]);
            exit();
        } else {
            echo json_encode(['er' => 'ras']);
            exit();
        }
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