<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_acc_sendConfToNewEma extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function acc_sendConfToNewMail($args){
        //Dans ce cas là, on veut cancel la demande en cours sur l'ancien email, et en refaire une sur le nouveau
        //Pour ça, on va enregistrer le nouveau comme si on enregistrait une modification habituelle, et faire une demande
        $EMA = new EMAIL();
        $oldmail = $EMA->get_email_from_account($args['accid']);
        if($oldmail == 'QUERY_ERROR'){
            return FALSE;
        }
        $newmail = $args['newmail'];

        //On cancel
        $ctrl1 = $EMA->email_confirmation_cancel_key($oldmail);     //Si tout est OK, retourne TRUE
        //On sauvegarde
        $ctrl2 = $EMA->account_classic_save_email_check($args['accid'], $newmail);  //Si tout est OK, retourne 1
        //On recrée
        $ctrl3 = $EMA->create_accountkey($newmail);     //Si tout est OK, retourne la clé. FALSE sinon.
        //On envoie
        $ctrl4 = $EMA->email_confirmation_request($newmail, 'creation');     //Si tout est OK, retourne TRUE. False sinon. 
        //Vérifications
        if($ctrl1 == TRUE && $ctrl2 == 1 && $ctrl3 != FALSE && $ctrl4 == TRUE){
            //Exécution OK
            return TRUE;
        } else {
            //Problème quelque part
            return FALSE;
        }
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $ctrl = $this->acc_sendConfToNewMail($this->KDIn['datas']);
        $this->KDOut["ctrl"] = $ctrl;
    }

    public function on_process_out() {
        echo json_encode(["ctrl" => $this->KDOut["ctrl"]]);
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