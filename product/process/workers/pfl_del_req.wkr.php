<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_pfl_del_req extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function del_deletionRequest($args){
        $ACC = new ACCOUNT();

        $CNX = new CONNECTION();
        $pw = $CNX->delete_account_passwd_checker($args);
        if($pw == FALSE){
            //mauvais mot de passe
            return 'DELRQ_ERR_BADPW';
        }

        $accAlterData = [
            'accid' => $args['accid'],
            'todelete' => 1
        ];

        $rt1 = $ACC->on_alter_entity($accAlterData);
        if($rt1 != 1){
            //Erreur dans l'alter_entity
            return $rt1;
        }
        $rt2 = $ACC->delete_account_request($args['accid'], $args['reason'], $args['detail']);
        if($rt2 != 1){
            //Erreur dans l'insert dans la table delacc_history
            return $rt2;
        }
        
        //Si on arrive là c'est que tout s'est bien passé
        //Donc on lance la copie sur l'autre serveur
        $PDACC = new PROD_ACC();

        //Utilisation de la requête qui va récupérer toutes les informations nécessaires
        $QO = new QUERY("qryl4accountn22");
        $params = array( ':accid' => $args['accid'] );
        $datas = $QO->execute($params);

        $account = $datas[0];

        //Remplissage du tableau
        $args_new_pdacc = [
                "accid" => $account['accid'],
                "acc_gid" => $account['gid'],
                "acc_eid" => $account['acc_eid'],
                "acc_upsd" => $account['accpseudo'],
                "acc_ufn" => $account['ufullname'],
                "acc_uppic" => $account['acc_uppic'],
                "acc_uppicid" => $account['acc_uppicid'],
                "acc_coverpicid" => $account['acc_coverpicid'],
                "acc_coverpic" => $account['acc_coverpic'],
                "acc_ucityid" => $account['ulvcity'],
                "acc_ucity_fn" => $account['asciiname'],
                "acc_nocity" => NULL,
                "acc_ucnid" => $account['ctr_code'],
                "acc_ucn_fn" => $account['ctr_name'],
                "acc_udl" => $account['acclang'],
                "acc_datecrea" => $account['acc_datecrea'],
                "acc_datecrea_tstamp" => $account['acc_datecrea_tstamp'],
                "acc_capital" => $account['acc_capital'],
                "acc_todelete" => $account['todelete']
            ];

        //Appel de la fonction d'insert
        $r3 = $PDACC->on_alter_entity($args_new_pdacc);
        if(isset($r3) && is_array($r3) && count($r3)){
            return 1;
        } else {
            //Erreur lors de la copie sur l'autre base
            return FALSE;
        } 
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $rt = $this->del_deletionRequest($this->KDIn["datas"]);
        $this->KDOut["rt"] = $rt;
    }

    public function on_process_out() {
        echo json_encode(['rt' => $this->KDOut["rt"]]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['datas'] = $_POST['datas'];
//        //TODO: Récupérer l'ID de l'utilisateur connecté via $_SESSION['']
//        //En attendant: simulation
//        $this->KDIn['datas']['accid'] = 95;
        
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        } else {
            $this->KDIn['datas']['accid'] = $oid;
        }
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>