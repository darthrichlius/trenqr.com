<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_trial extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    /* ---- CURRENT OWNER IDENTITY ---- */
    private function CleanEmail(){
        //TODO: Récupérer l'email depuis l'URL via GET
        
        //SIMULATION
        $em = 'toto@gmail.com';
        
         $EMA = new EMAIL();
         $ctrl = $EMA->email_validation($em);
         if(!$ctrl){
             $this->KDIn['cem'] = '';
         } else {
             $this->KDIn["cem"] = $ctrl;
         }
         
    }
   
   private function GetFinalEmail(){
       return $this->KDOut['cem'];
   }

    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        
        //TODO: Vérifier l'existance de ups['em']
        //TODO: Vérifier que ups['em'] est sécurisé
        // ==> Ajout de addslashes, htmlentities, et vérification via regex
        $this->CleanEmail();
        
    }
    
    public function on_process_in() {
        
    }

    public function on_process_out() {
        $_SESSION["ud_carrier"]["em"] = $this->GetFinalEmail();        
    }
    
    /**
     * @obsolete
     */
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>