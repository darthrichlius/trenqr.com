<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_gen_loadHidPas extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function load_hiddenpw(){
        //Note: ce dvt n'a pas été déclaré dans le XML puisqu'il n'est utilisé que de cette manière
        $file = RACINE."/product/view/repos/dvt/pflpage_append_hiddenpw.d.php";
        
        if( file_exists($file) ) {
            $htmlblock = file_get_contents($file);
        }
        else {
            $htmlblock = 'APPHIDPAS_ERR';
        }
        return $htmlblock;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $block = $this->load_hiddenpw();
        $this->KDOut["block"] = $block;
    }

    public function on_process_out() {
        echo json_encode(['html' => $this->KDOut["block"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>