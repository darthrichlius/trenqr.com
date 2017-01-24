<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_rec2 extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    /* ---- CURRENT USER IDENTITY ---- */
    private function recovery_email_scavenger($email){
        if(isset($email) && $email != ''){
            $exp = explode('@', $email);
            $rt['ema'] = "http://www." . $exp[1];
            $rt['emd'] = $exp[1];
        } else {
            $rt = NULL;
        }
        
        return $rt;
    }
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rt = $this->recovery_email_scavenger($this->KDIn['email']);
        $this->KDOut['rt'] = $rt;
    }

    public function on_process_out() {
        //Si l'utilisateur arrive 'directement' sur cette page, on le renvoie sur la page d'accueil.
        if($this->KDOut['rt'] != NULL){
            $_SESSION["ud_carrier"]["ema"] = $this->KDOut['rt']['ema'];
            $_SESSION["ud_carrier"]["emd"] = $this->KDOut['rt']['emd'];
        } else {
            $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
            $RDH->redir_to_default_page(DFTPAGE_PROD_WEL);
        }
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn["email"] = $_POST["email"];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>