<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_rec4 extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
   private function redirect_overseer($rd){
       if($rd === 't'){
           //OK
           return TRUE;
       } else {
           //Erreur / Hack
           return FALSE;
       }
   }
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rt = $this->redirect_overseer($this->KDIn['rd']);
        $this->KDOut['rt'] = $rt;
    }

    public function on_process_out() {
        if($this->KDOut['rt'] == TRUE){
            //Si tout se passe bien on envoie l'utilisateur sur la page de connexion
            $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
            $RDH->redir_to_default_page(DFTPAGE_PROD_CONX);
        } else {
            //Sinon il va à la page d'accueil
            $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
            $RDH->redir_to_default_page(DFTPAGE_PROD_WEL);
        }
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn["rd"] = ( key_exists("recch_rd", $_POST) ) ? $_POST["recch_rd"] : "";
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>