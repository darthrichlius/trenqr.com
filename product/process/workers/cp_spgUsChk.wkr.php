<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_spgUsChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function cp_supergroupUserCheck($login){
        $CNX = new CONNECTION();
        $su = $CNX->detect_user_special_group($login);
        $rt = array();
        $file = RACINE."/product/view/repos/dvt/cnx_append_superuser.d.php";

        if(is_array($su)){
            $rt['status'] = 'superuser_email';
            if( file_exists($file) ) {
                $rt['htmlblock'] = file_get_contents($file);
            }
            else {
                $rt['htmlblock'] = 'SPGUSCHK_ERR';
            }
        } else if($su == TRUE){
            $rt['status'] = 'superuser_pseudo';
            if( file_exists($file) ) {
                $rt['htmlblock'] = file_get_contents($file);
            }
            else {
                $rt['htmlblock'] = 'SPGUSCHK_ERR';
            }
        } else if($su == FALSE){
            $rt['status'] = 'classical_user';
        } else {
            $rt['status'] = 'error';
        }
        return $rt;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rt = $this->cp_supergroupUserCheck($this->KDIn['login']);
        //Attention, $rt est un tableau associatif
        $this->KDOut['rt'] = $rt;
    }

    public function on_process_out() {
        if(isset($this->KDOut['rt']['htmlblock'])){
            echo json_encode([
                'status' => $this->KDOut['rt']['status'],
                'htmlblock' => $this->KDOut['rt']['htmlblock']
            ]);
            exit();
        } else {
            echo json_encode(['status' => $this->KDOut['rt']['status']]);
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