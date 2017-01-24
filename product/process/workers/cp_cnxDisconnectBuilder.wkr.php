<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_cnxDisconnectBuilder extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
    }

    public function on_process_out() {
        
        $CH = new CONX_HANDLER();
        //RAPPEL : Renvoie TRUE si tout s'est bien passÃ©
        $CH->try_logout();
        $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
        $RDH->redir_to_default_page(DFTPAGE_PROD_WEL);
        
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {

    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>