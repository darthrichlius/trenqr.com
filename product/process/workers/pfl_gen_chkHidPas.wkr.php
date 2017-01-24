<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_gen_chkHidPas extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    function hiddenpw_check($args){
        $CNX = new CONNECTION();
        $r = $CNX->profile_updates_passwd_checker($args);
        return $r;
    }
    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rtr = $this->hiddenpw_check($this->KDIn["datas"]);
        $this->KDOut["rtr"] = $rtr;
    }

    public function on_process_out() {
        echo json_encode(['authorized' => $this->KDOut["rtr"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        if(isset($_POST['datas'])){
            $this->KDIn['datas'] = $_POST['datas'];
//            //TODO: Récupérer l'ID de l'utilisateur connecté via $_SESSION['']
//            //En attendant: simulation
//            $this->KDIn['datas']['accid'] = 95;
            
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->exists_with_id($oid);

            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
            } else {
                $this->KDIn['datas']['accid'] = $oid;
            }
        } else {
            //On envoie une chaîne fausse qui déclenchera une erreur plus tard
            $this->KDIn["datas"] = -1;
        }
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>