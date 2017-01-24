<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_sup_creAcc extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function sup_createAccount($datas){
        $ACC = new ACCOUNT();
        $PFL = new PROFIL();
        $EMA = new EMAIL();
        $PRG = new PREREG();

        //On commence par trier les données d'entrée en fonction de leur destination
        //(Profile | Account)
        $profilData = array(
            'ufullname' => $datas['fullname'],
            'uborndate' => $datas['birthday'],
            'ugender' => $datas['gender'],
            'ulvcity' => $datas['cityId']
        );
        //La fonction retourne l'ID du dernier insert
        $pflid = $PFL->on_create_entity($profilData);
        //On vérifie que le return a bien renvoyé un ID correct
        //(Cast en string de sécurité)
        if(!ctype_digit((string)$pflid)){
            return 'ERR_CREAPFL';
        }
        //Linking à l'image de profil par défaut
        $pflpic_link = $PFL->default_pflpic_linking($pflid);
        //On vérifie le bon déroulement
        if($pflpic_link != 1){
            return 'ERR_PFLPICLINK';
        }

        $accountData = array(
            'accpseudo' => $datas['pseudo'],
            'acc_authpwd' => $datas['passwd'],
            'acclang' => $datas['acclang'],
            'acc_socialarea' => $datas['acc_socialarea'],
            'pflid' => $pflid
        );
        //La fonction retourne l'ID du dernier insert
        $accid = $ACC->on_create_entity($accountData);
        //On vérifie que le return a bien renvoyé un ID correct
        //(Cast en string de sécurité)
        if(!ctype_digit((string)$pflid)){
            return 'ERR_CREAACC';
        }
        //Ajout au groupe lambda (on sait que ID == 2);
        $link_status = $ACC->group_linking($accid, 2);
        //On vérifie le bon déroulement
        if($link_status != 1){
            return 'ERR_ACCGRPLINK';
        }
        //Linking à l'image de cover par défaut
        $covpic_link = $ACC->default_coverpic_linking($accid);
        //On vérifie le bon déroulement
        if($covpic_link != 1){
            return 'ERR_COVPICLINK';
        }

        $emailData = array(
            'emailraw' => $datas['email'],
            'accid' => $accid,
            //Paramètre pour signaler qu'on est en train de créer un vrai compte directement
            //Pour la validation istantanée de l'email
            //'from' => 'account'
        );

        $alreadyInArchive = $EMA->exists_in_archive($datas['email']);
        if($alreadyInArchive == FALSE){
            $crea_ema = $EMA->on_create_entity($emailData);
            if($crea_ema != TRUE){
                return 'ERR_CREAEMAARC';
            }
        }
        $assign_status = $EMA->assign_to_account($datas['email'], $accid);
        //On vérifie le bon déroulement
        if($assign_status != 1){
            return 'ERR_EMAACCLINK';
        }

        //Maintenant on regarde si cet email était lié à une prereg (email étant unique dans cette table)
        //Si c'est le cas, on ferme la prereg en mettant une date_close.
        $prereg_id = $PRG->get_prereg_id_from_email($datas['email']);
        if($prereg_id != 'error' && $prereg_id != null){
            $clpg_status = $PRG->close_prereg($prereg_id);
            //On vérifie le bon déroulement
            if($clpg_status != 1){
                return 'ERR_CLOPREG';
            }
        }

        //On pense à générer et lier ce nouveau compte et son email avec une clé pour la confirmation du compte
        $cracckey = $EMA->create_accountkey($datas['email']);
        //On vérifie le bon déroulement
        if($cracckey == FALSE){
            return 'ERR_ACCKEY';
        }
        
        ////////////////////// ENVOI DES TROIS EMAILS /////////////////////

        //Mail de bienvenue
        $totomail = "Bienvenue sur Trenqr! <br />Toute l'équipe vous souhaite la bienvenue, etc.";
        $rsl = $EMA->send_email('noreply@trenqr.com', $datas['email'], 'Welcome', $totomail, 'noreply@trenqr.com');
        if($rsl == FALSE){
            return 'ERR_SENDMAIL';
        }
        
        //Mail d'ajout de capital
        
        //Mail de confirmation de compte
        $emailLink = "http://www.trenqr.com/forrest/index.php?page=confirm&urqid=email_confirm&ups=k=" . $cracckey;
        $totomail2 = "Bienvenue sur Trenqr! <br />Pour confirmer dès à présent votre email, <a href='". $emailLink ."'>cliquez ici</a>.";
        $rsl2 = $EMA->send_email('noreply@trenqr.com', $datas['email'], 'Welcome', $totomail2, 'noreply@trenqr.com');
        if($rsl2 == FALSE){
            return 'ERR_SENDMAIL';
        }

        //On termine tout ce bazar en regardant si le compte en train d'être créé est un compte d'essai
        //Si oui, on fait aussi un insert dans la table tryaccounts
        if($datas['acctype'] == 'ta'){
            $TAC = new TRYACCOUNT();
            //Pour le moment pas besoin de vérification ici, vu que c'est désactivé
            $TAC->on_create_entity(['accid' => $accid]);
        }
        
        //Si on arrive là c'est que tout s'est bien passé
        //Donc on lance la copie sur l'autre serveur
        
        //Utilisation de la requête qui va récupérer toutes les informations nécessaires
        $QO = new QUERY("qryl4accountn21");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);

        $account = $datas[0];
        
        //Remplissage du tableau
        $args_new_pdacc = [
                "accid" => $account['accid'],
                "acc_gid" => "2",   //Sera toujours '2' car on vient d'une inscription
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
                "acc_capital" => "0"
            ];
        
        //Appel de la fonction d'insert
        $this->prod_save($args_new_pdacc);
        //Et on peut termier
        return 1;
    }
    
    
    private function prod_save($args_new_pdacc){
        $PDACC = new PROD_ACC();
        $r = $PDACC->on_create_entity($args_new_pdacc);
    }
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        $accountCreation = $this->sup_createAccount($this->KDIn['insData']);
        //1 si tout va bien, code d'erreur sinon
        $this->KDOut['creation_status'] = $accountCreation;
    }

    public function on_process_out() {
        echo json_encode(['status' => $this->KDOut['creation_status']]);
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['insData'] = $_POST['datas'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>