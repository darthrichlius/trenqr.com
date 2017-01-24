<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_hp_crprg2 extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function hp_createPrereg($args){
    $PRG = new PREREG();
    $EMA = new EMAIL();
    
    $regNickname = $PRG->getRegexNickname();
    $regPasswdMini = $PRG->getRegexPasswdMini();
    $regMail = $PRG->getRegexMail();
    $regFullname = $PRG->getRegexFullname();
    
    $fullname = $args['fullname'];
    $pseudo = $args['pseudo'];
    $email = $args['email'];
    $passwd = $args['passwd'];
    
    /* Variable de gestion des erreurs.
     * Si le tableau est vide on est bons.
     * Sinon, il contient des couples $k => $v
     * avec les erreurs et on peut le retourner
     */
    $ok = [];
    if(!$EMA->exists_and_is_used($email)){
        $ok[] = ['email' => 'Erreur email deja utilise'];
    }
    if(!preg_match_all($regFullname, $fullname)){
        $ok[] = ['fullname' => 'Erreur nom complet'];
    }
    
    if(!preg_match_all($regNickname, $pseudo)){
        $ok[] = ['pseudo' => 'Erreur pseudo'];
    }
    
    if(!preg_match_all($regMail, $email)){
        $ok[] = ['email' => 'Erreur email'];
    }
    
    if(!preg_match($regPasswdMini, $passwd)){
        $ok[] = ['passwd' => 'Erreur mot de passe'];
    }
    
    /* Après tous les checks, on vérifie l'état de notre tableau de contrôle */
    if(count($ok) != 0){
        /* On renvoie le tableau d'erreurs */
        //TODO: Renvoyer erreurs
        //echo 'prereg error';
        return false;
    } else {
        /* On crée la pré-inscription et on poursuis vers Inscription */
        //echo 'prereg ok';
        $mailArgs = ['emailraw' => $email, 'origin' => 2]; //Origins = 2 car preregistration
        if($EMA->exists_in_archive($email) == FALSE){
            $EMA->on_create_entity($mailArgs);
        }
        $PRG->on_create_entity($args);
        $key = $PRG->get_prereg_key_from_email($args['email']);
        return $key;
    }
}
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $args = $_POST['datas'];
        $k = $this->hp_createPrereg($args);
        $this->KDOut['k'] = $k;
    }

    public function on_process_out() {
        echo json_encode(['preins_status' => $this->KDOut["k"]]);
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