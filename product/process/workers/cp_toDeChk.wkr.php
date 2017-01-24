<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_toDeChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function cp_todeleteAccountCheck($login){
        $CNX = new CONNECTION();
        $logintype = $CNX->login_detect($login);
        $file = RACINE."/product/view/repos/dvt/cancel_delete_overlay.d.php";

        $ACC = new ACCOUNT();
        $accid = $ACC->get_accid_from_login($login, $logintype);

        $todelete = $ACC->detect_delete_request($accid);
        $rVal['todelete'] = $todelete;

        if($todelete == TRUE){
            if( file_exists($file) ) {
                $rVal['overlay'] = file_get_contents($file);
            }
            else {
                $rVal['overlay'] = 'DELACCOVERLAY_ERR';
            }
        }

        return $rVal;
    }
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rVal = $this->cp_todeleteAccountCheck($this->KDIn['login']);
        //Tableau associatif
        $this->KDOut['rVal'] = $rVal;
    }

    public function on_process_out() {
        echo json_encode($this->KDOut['rVal']);
        exit();
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