<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_rp_recSenEma extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function rp_recoverySendMail($email){
        $EMA = new EMAIL();
        $ACC = new ACCOUNT();
        $regMail = $EMA->getReg();
        if(isset($email) && preg_match_all($regMail, $email)){
            //Obligé de refaire le check pour des raisons de sécurité.
            //Vu que le code est envoyé en JS, il peut être analysé et
            //corrompu
            if(!$EMA->exists_and_is_used($email)){
                //Génération de la clé unique pour le reset
                $recovery_key = $EMA->guidv4();
                //Insert des détails de l'opération en base
                $dbInsert = $ACC->passwd_reinit_request($email, $recovery_key);
                if($dbInsert == FALSE){
                    //Erreur quelconque
                    return FALSE;
                } else {
                    //Construction de l'URL
                    $url = "http://www.trenqr.com/forrest/index.php?page=recuperation_change&urqid=recch&ups=k=".$recovery_key;
                    
                    
                    //Envoi du mail à l'utilisateur
                    $sender = 'noreply@trenqr.com';
                    $subject = 'Réinitialisation du mot de passe';
                    $reply_to = 'noreply@trenqr.com';
                    $body = 'Pour réinitialiser votre mot de passe, cliquez sur <a href="'.$url.'">ce lien</a>.';
                    
                    $sent = $EMA->send_email($sender, $email, $subject, $body, $reply_to);
                    if($sent == TRUE){
                        //Si tout s'est bien passé, on retourne la clé car on va en avoir besoin pour le passage vers rec2
                        return $recovery_key;
                    } else {
                        return FALSE;
                    }
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $r = $this->rp_recoverySendMail($this->KDIn['email']);
        $this->KDOut["r"] = $r;
    }

    public function on_process_out() {
        echo json_encode(['okForRecovery' => $this->KDOut["r"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['email'] = $_POST['datas']['em'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>