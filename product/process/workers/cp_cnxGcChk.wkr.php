<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_cnxGcChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function cp_cnxGCCheck($args){
        $CNX = new CONNECTION();
        $regPasswdMini = $CNX->getRegexPasswdMini();
        $login = $args['login'];
        $passwd = $args['passwd'];
        $type = $CNX->login_detect($login);

        if(!preg_match_all($regPasswdMini, $passwd)){
            return FALSE;
        }

        $r = $CNX->gccheck($login, $passwd, $type);
        return $r;
}

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->cp_cnxGCCheck($this->KDIn['datas']);
        $this->KDOut['gcc'] = $r;
    }

    public function on_process_out() {
        echo json_encode(["gcc" => $this->KDOut["gcc"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['datas'] = $_POST['datas'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>