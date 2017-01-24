<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_sup_citLis extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function sup_citySuggestion($input, $names_only = NULL){
        $ACC = new ACCOUNT();
        $dataStore = $ACC->city_suggestion($input, $names_only = NULL);
        return $dataStore;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $ci = $this->sup_citySuggestion($this->KDIn['inputCity'], TRUE);
        //Tableau associatif
        $this->KDOut['cityList'] = $ci;
    }

    public function on_process_out() {
        echo json_encode($this->KDOut['cityList']);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['inputCity'] = $_POST['datas']['inputCity'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>