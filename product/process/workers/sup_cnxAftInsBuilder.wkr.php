<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_sup_cnxAftInsBuilder extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function check_data($login, $pw){
        if(isset($login) && isset($pw) && $login != '' && $pw != ''){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->check_data($this->KDIn["login"], $this->KDIn["passwd"]);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        if($this->KDOut["r"] == TRUE){
            //Je récupère l'EID
            $ACC = new ACCOUNT();
            $accid = $ACC->get_accid_from_login($this->KDIn["login"], "pseudo");
            $ACC->load_entity(["accid" => $accid]);
            $eid = $ACC->getacc_eid();
            
            $CH = new CONX_HANDLER();

            $A = new PROD_ACC();
            $rt_ore = $A->on_read_entity(["acc_eid" => $eid]);

            $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
            if(isset($rt_ore) && is_array($rt_ore) && count($rt_ore)){
                //OK donc on redirige
                $SID = session_id();
                $r = $CH->try_login($A, $SID);

                $url = $RDH->redir_build_std_url_string("profil", "TMLNR_GTPG_RO", $this->KDIn["login"]);
                $RDH->start_redir_to_this_url_string($url);
            } else {
                //Erreur de connexion - Redirection

                $RDH->redir_to_default_page(DFTPAGE_PROD_CONX);
            }
        } else {
            //TODO: Redirection quelque part
        }
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn["login"] = ( key_exists("ins_input_nickname", $_POST) ) ? $_POST["ins_input_nickname"] : "";
        $this->KDIn["passwd"] = ( key_exists("ins_input_passwd", $_POST) ) ? $_POST["ins_input_passwd"] : "";
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>