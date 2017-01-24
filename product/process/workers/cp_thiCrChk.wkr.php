<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_thiCrChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    private function cp_thirdCritCheck($login){
        $rt = array();
        $ACC = new ACCOUNT();
        $ena = $ACC->isThirdCritEnabled($login);
        $file = RACINE."/product/view/repos/dvt/cnx_append_thirdcrit.d.php";
        
        if($ena == TRUE){
            $rt['enabled'] = TRUE;
            if( file_exists($file) ) {
                $rt['htmlblock'] = file_get_contents($file);
            }
            else {
                $rt['htmlblock'] = 'THICRCHK_ERR';
            }
        } else {
            $rt['enabled'] = FALSE;
        }
        return $rt;
    }
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rt = $this->cp_thirdCritCheck($this->KDIn['login']);
        //Attention, tableau associatif
        $this->KDOut['rt'] = $rt;
    }

    public function on_process_out() {
        if(isset($this->KDOut['rt']['htmlblock'])){
            echo json_encode([
                'enabled' => $this->KDOut['rt']['enabled'],
                'htmlblock' => $this->KDOut['rt']['htmlblock']
            ]);
            exit();
        } else {
            echo json_encode(['enabled' => $this->KDOut['rt']['enabled']]);
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