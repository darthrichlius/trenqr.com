<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_recch extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function key_check($key){
        $ACC = new ACCOUNT();
        $r = $ACC->passwd_reinit_check_key($key);
        return $r;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->key_check($this->KDIn["k"]);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        if($this->KDOut["r"] == FALSE){
            //Si la clé ne correspond à rien on redirige
            $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
            $RDH->redir_to_default_page(DFTPAGE_PROD_WEL);
        } else {
            //Sinon on affiche la page normalement
        }
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn["k"] = $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>