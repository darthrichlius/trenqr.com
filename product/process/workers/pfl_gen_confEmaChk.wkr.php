<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_gen_confEmaChk extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function acc_isEmailConfirmed($accid){
        //On commence par récupérer l'email de l'utilisateur via son ID
        $EMA = new EMAIL();

        $conf = $EMA->email_confirmation_verification($accid);

        $rVal = array();
        $conflink = RACINE."/product/view/repos/dvt/pflpage_append_emailconflink.d.php";
        $confdiv = RACINE."/product/view/repos/dvt/pflpage_append_unconfirmed_email.d.php";

        if($conf == TRUE){
            $rVal['conf'] = TRUE;
        } else if($conf == FALSE){
            $rVal['conf'] = FALSE;
            $rVal['link'] = (file_exists($conflink)) ? file_get_contents($conflink) : "APPEMACONLNK_ERR"; //Append Email Confirmation Link
            $rVal['div'] = (file_exists($confdiv)) ? file_get_contents($confdiv) : "APPUNCEMADIV_ERR"; //Append Unconfirmed Email Div
        }

        return $rVal;
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->acc_isEmailConfirmed($this->KDIn["accid"]);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        echo json_encode($this->KDOut["r"]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
//        //TODO: Récupérer l'ID de l'utilisateur connecté via $_SESSION['']
//        //En attendant: simulation
//        $this->KDIn['accid'] = 95;

        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        } else {
            $this->KDIn['accid'] = $oid;
        }
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>