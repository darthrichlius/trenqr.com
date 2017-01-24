 <?php

/**
 * Description de l'Entity Connection
 */
class CONNECTION extends PROD_ENTITY {
    
    private $regexNickname;
    private $regexPasswdMini;
    private $regexMail;
    private $regexDate;
    private $regexTstamps;
    
    private $attempt_id;
    private $attempt_date;
    private $attempt_date_tstamp;
    private $supplied_pseudo;
    private $supplied_email;
    private $supplied_passwd;
    private $supplied_date_nais;
    private $supplied_date_nais_tstamp;
    private $comment;
    private $result;
    
    private $session_id;
    private $accid;
    private $session_start;
    private $session_start_tstamp;
    private $session_end;
    private $session_end_tstamp;
    
    private $last_insert_id;
	
       
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->regexMail = '/^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/';
        $this->regexNickname = '/^[a-zA-Z0-9-_ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/';
        $this->regexPasswdMini = '/^[^<=>\\;\/]{4,20}$/';
        //La regex puissante étant copiée de Javascript, elle plante en PHP
        $this->regexMagicDates = "/^[0-9]{2}[-][0-9]{2}[-][0-9]{4}$/";
        //$this->regexMagicDates = "/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
        $this->regexTstamps = '/^-?\d{10,}$/';
        
        $this->is_instance_loaded = false;
        $this->last_insert_id = null;
    }
    
    
    /* P.L.: Problèmes rencontrés avec cette entity:
     * Pour avoir une connexion, il faut que le contenu de login_log soit OK.
     * Pour ça, il faut que le contenu de login_attempt soit OK car on récupère attempt_id pour login_log.
     * Or, ça implique d'utiliser un attempt_id 'en dur' pour les fonctions.
     * Et là je comprends plus comment je suis censé faire.
     */
    
    
    /*******************************************************************************************************/
    /********************************************* PROCESS ZONE ********************************************/
    public function build_volatile ($args) {
//        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
//        $attempt_date = ( !isset($attempt_date) ) ? new DateTime : $attempt_date;
//        if(intvlal($result) = 1){
//            $session_start = ( !isset($session_start) ) ? new DateTime : $session_start;
//        } else {
//            $session_start = '';
//        }
//        
//        $datas = $this->get_std_datas_format($attempt_id, $attempt_date->format('Y-m-d H:i:s'), $supplied_pseudo, $supplied_email, $supplied_passwd, $supplied_date_nais->format('Y-m-d H:i:s'), $accid, $session_start->format('Y-m-d H:i:s'));
//        
//        $foo = $this->valid_connection_attempt_instance($datas);
//        
//        if(!count($foo)){
//            return $foo;
//        } else {
//            $this->init_properties($datas);
//        }
    }
    

    /************************* MAJORITAIRE */
    public function load_entity ($args) {
//        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $session_id);
//        //$QO = new QUERY("????");
//        $QO = 'SELECT * FROM preregistration WHERE session_id = '. $session_id .';';
//        
//        $params = array(':session_id' => $session_id);
//        
//        $datas = $QO->execute($params);
//        if(!count($datas)){
//            if($std_err_enabled){$this->signalError("/*CODE ERREUR*/", __FUNCTION__, __LINE__);}
//            else{return 0;}
//        } else {
//            $this->init_properties($datas);
//            $this->is_instance_loaded = true;
//        }
        
        
        
    }
    
//    private function init_properties($datas) {
//        $this->all_properties["attempt_id"] = $this->attempt_id = trim($datas["attempt_id"]);
//        $this->all_properties["attempt_date"] = $this->attempt_date = trim($datas["attempt_date"]);
//        $this->all_properties["attempt_date_tstamp"] = $this->attempt_date_tstamp = trim($datas["attempt_date_tstamp"]);
//        $this->all_properties["supplied_pseudo"] = $this->supplied_pseudo = $datas["supplied_pseudo"];
//        $this->all_properties["supplied_email"] = $this->supplied_email = trim($datas["supplied_email"]);
//        $this->all_properties["supplied_passwd"] = $this->supplied_passwd = trim($datas["supplied_passwd"]);
//        $this->all_properties["supplied_date_nais"] = $this->supplied_date_nais = trim($datas["supplied_date_nais"]);
//        $this->all_properties["supplied_date_nais_tstamp"] = $this->supplied_date_nais_tstamp = trim($datas["supplied_date_nais_tstamp"]);
//        $this->all_properties["comment"] = $this->comment = ["comment"]; 
//        $this->all_properties["result"] = $this->result = ["result"];
//        
//        $this->all_properties["session_id"] = $this->session_id = ["session_id"]; 
//        $this->all_properties["accid"] = $this->accid = ["accid"]; 
//        $this->all_properties["session_start"] = $this->session_start = ["session_start"];
//        $this->all_properties["session_start_tstamp"] = $this->session_start_tstamp = ["session_start_tstamp"];
//        $this->all_properties["session_end"] = $this->session_end = ["session_end"];
//        $this->all_properties["session_end_tstamp"] = $this->session_end_tstamp = ["session_end_tstamp"];
//    }
    
    protected function init_properties($datas) {
        foreach($datas as $k => $v){
            $$k = $v;
            if($v instanceof DateTime){
                $this->all_properties[$k] = $this->$k = $datas[$k];
            } else {
                $this->all_properties[$k] = $this->$k = trim($datas[$k]);
            }
        }
    }



    /*
     * La méthode on_create_entity($args) va servir à enregistrer les tentatives de connexion
     * sur la table login_attempt.
     * Si la connection est OK, on enregistre également dans login_log.
     */
    public function on_create_entity($args) {
//        //$supplied_date_nais n'est pas précisé en tant que DateTime pour pouvoir le traiter en tant que string si jamais elle n'est pas remplie
//        // => Utilisation de strtotime() dans la valid_connection_instance();
//        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
//        
//        /* On va devoir faire l'insert en deux fois.
//         * Première étape: login attempt.
//         * Si login_attempt est ok (result=1), on fait l'insert de login_log
//         */
//        $attempt_date = new DateTime();
//        $attempt_date_tstamp = $attempt_date->getTimestamp();
//        
//        $supplied_pseudo = (!isset($supplied_pseudo)) ? '' : $supplied_pseudo;
//        $supplied_email = (!isset($supplied_email)) ? '' : $supplied_email;
//        $supplied_date_nais = (!isset($supplied_date_nais)) ? '' : $supplied_date_nais;
//        $comment = (!isset($comment)) ? '' : $comment;
//        $result = (!isset($result)) ? 0 : $result;
//        
//        $datas = $this->get_std_datas_format($supplied_pseudo, $supplied_email, $supplied_passwd, $supplied_date_nais, $comment, $result);
//        
//        $foo = $this->valid_connection_instance($datas);
//        if(count($foo)){
//            return $foo;
//        } else {
//            $this->init_properties($datas);
//            $this->write_new_in_database();
//            
//            //Seconde partie, ajout dans login_log si result=1
//            //$QO = new QUERY('code');
//            //$params = array('
//            //
//            // PROBLEME: Retrouver l'ID de ce qu'on vient d'insérer?
//        }
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        
        //Init compte
        $account = NULL;
        //Type d'authentification
        $ACC = new ACCOUNT();
        $loginType = $this->login_detect($login);
        switch($loginType){
            case 'email':
                $vStore['supplied_email'] = $login;
                $account = $ACC->get_accid_from_login($login, 'email');
                break;
            case 'pseudo':
                $vStore['supplied_pseudo'] = $login;
                $account = $ACC->get_accid_from_login($login, 'pseudo');
                break;
            default:
                $this->get_or_signal_error(1, 'custom_err_cnx_login_type', __FUNCTION__, __LINE__);
                break;
        }
        
        
        //Gestion de la date de naissance
        if(isset($supplied_date_nais) && $supplied_date_nais instanceof DateTime){
            $vStore['supplied_date_nais'] = $supplied_date_nais->format('Y-m-d');
            $vStore['supplied_date_nais_tstamp'] = strtotime($supplied_date_nais->format('Y-m-d'));
        } else if(isset ($supplied_date_nais) && !preg_match('/[init]/', $supplied_date_nais) && !$supplied_date_nais instanceof DateTime){
            $formated_sdn = date_create_from_format('d-m-Y', $supplied_date_nais);
            $vStore['supplied_date_nais'] = $formated_sdn;
            $vStore['supplied_date_nais_tstamp'] = strtotime($formated_sdn->format('Y-m-d')) * 1000;
        } else {
            $vStore['supplied_date_nais'] = NULL;
            $vStore['supplied_date_nais_tstamp'] = NULL;
        }
        
        
        
        /*//Stockage de l'ID du compte connecté si la connexion se fait
        $account = null;        
        //Tentative de connexion au site
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        if($loginType == 'pseudo'){
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $QO = "SELECT accid, accpseudo, acc_authpwd FROM accounts WHERE accpseudo = '$login';";
            $rslto = $con->query($QO);
            if($rslto == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return 0;}
            $fao = $rslto->fetch_array(MYSQLI_ASSOC);
            if(!count($fao)){
                //Pas de match dans la base, le pseudo n'existe pas
                $vStore['result'] = 0;
            } else {
                $ACC = new ACCOUNT();
                $check = $ACC->compare_hashed_passwd($supplied_authpwd, $fao['acc_authpwd']);
                if($check){
                    //Il y a correspondance, donc connexion OK
                    $vStore['result'] = 1;
                } else {
                    //Mauvais login ou pw
                    $vStore['result'] = 0;
                }
            }
        } else if($loginType == 'email'){
            $QP = "SELECT a.accid, ea.emailraw, a.acc_authpwd FROM accounts a INNER JOIN email_history ea ON a.accid = ea.accid WHERE ea.emailraw = '$login' AND a.acc_authpwd = '$passwd';";
            $rsltp = $con->query($QP);
            if($rsltp == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return 0;}
            $fap = $rsltp->fetch_array(MYSQLI_ASSOC);
            if(!count($fap)){
                //Pas de match dans la base, le pseudo n'existe pas
                $vStore['result'] = 0;
            } else {
                $ACC = new ACCOUNT();
                $check = $ACC->compare_hashed_passwd($supplied_authpwd, $fap['acc_authpwd']);
                if($check){
                    //Il y a correspondance, donc connexion OK
                    $vStore['result'] = 1;
                } else {
                    //Mauvais login ou pw
                    $vStore['result'] = 0;
                }
            }
        } else {
            //Ça non plus c'est pas supposé arriver
            $this->get_or_signal_error(1, 'err_user_l4cnxn1', __FUNCTION__, __LINE__);
            $vStore['result'] = 0;
        }*/
        
        //Gestion de la date de tentative
        $attempt_date = new DateTime();
        $vStore['attempt_date'] = $attempt_date;
        $vStore['attempt_date_tstamp'] = $this->get_millitimestamp();
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_connection_instance($datas);
        
        if(count($foo)){
            return $foo;
        } else {
            //Insert dans login_attempt
            $this->init_properties($datas);
            $this->write_new_in_database($datas, true);
        }
        
        /* ---------------------------------------------------------------------- */
        /* -- Si result = 1 (aka connexion OK), on insert aussi dans login_log -- */
        if(intval($datas['result']) == 1 && $account != null && $this->last_insert_id != null){
            /**************/
            /* TEMPORAIRE */
            /**************/
            //$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12); //Retiré par L.C. 07-09-14
            /**************/
            
            
            /*
             * [NOTE 07-09-2014] @author L.C.
             * Pierre ... a oublié de mettre la vrai constante de SESSION.
             * On aurait du le faire au niveau du CALLER mais il m'est impossible de savoir les CALLERs qui appellent cette méthode.
             * Aussi, je vais bricoler en créer (recréant) une session et récupérer une session_id().
             * Si une SESSION a déjà start on aura une erreur E_NOTICE. Grace à la configuration de WOS, elle n'apparaitra pas.
             */
            session_start();
            $SID = session_id();
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $session_start = new DateTime();
            $session_start_tstamp = $this->get_millitimestamp();
            //$session_start_tstamp = $session_start->getTimestamp();
            $session_start = $session_start->format('Y-m-d H:i:s');
            $QR = "INSERT INTO login_log (session_id, accid, session_start, session_start_tstamp, attempt_id)
                   VALUES ('$SID', '$account', '$session_start', '$session_start_tstamp', '$this->last_insert_id');";
            $ctrl = $con->query($QR);
            $con->close();
            if($ctrl == false){
                $this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);
            }
        }
    }

    public function on_alter_entity($args) {
        /* On ne peut pas modifier un login_attempt.
         * Par contre, on peut modifier un login_log.
         * 
         * Donc si on modifie login_log, ça veut dire qu'on 'coupe' la session,
         * et que pour ça, il faut avoir l'id de celle-ci (donc erreur si on l'a pas)
         */
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        if(!isset($session_id)){
            $this->get_or_signal_error(1, 'err_sys_l4cnxn2', __FUNCTION__, __LINE__);
        } else {
            $session_end = new DateTime();
            $vStore['session_end'] = $session_end;
            $vStore['session_end_tstamp'] = strtotime($session_end->getTimestamp()) * 1000;
            
            $datas = $this->get_std_datas_format($vStore);
            $foo = $this->valid_connection_instance($datas);
            if(count($foo)){
                return $foo;
            } else {
                $this->init_properties($datas);
                $this->write_new_in_database($datas);
            }
        }
        
    }
    
    
    
    protected function on_delete_entity($args) {
        /*
         * Delete dans un log ça n'a pas de sens voyons
         */
    }
    
    protected function on_read_entity($args) {
        /* ??? */
    }
    
     
    public function exists($args) {
        /*
         * Je ne vois pas d'utilité à cette fonction pour le moment.
         */
    }
    
    protected function write_new_in_database($args, $new_row = null) {
        
        //Initialisation des variables
        $attempt_date = $attempt_date_tstamp = $supplied_pseudo = $supplied_email = $supplied_authpwd = $supplied_date_nais = null;
        $supplied_date_nais_tstamp = $comment = $result = null;
        
        $accid = $session_start = $session_start_tstamp = $session_end = null;
        $session_end_tstamp = $attempt_id = null;
        
        //Load des variables
        foreach($args as $k => $v){
            $$k = $v;
        }
        
        //Gestion du passwd
        $ACC = new ACCOUNT();
        $supplied_authpwd_cypher = $ACC->hash_input_passwd($supplied_authpwd);
        
        //Gestion des DateTime
        $attempt_date_tstamp = (!isset($attempt_date)) ? null : strtotime($attempt_date->format('Y-m-d H:i:s')) * 1000;
        $attempt_date = (!isset($attempt_date)) ? null : $attempt_date->format('Y-m-d H:i:s');
        $supplied_date_nais_tstamp = (!isset($supplied_date_nais) || $supplied_date_nais == NULL) ? NULL : strtotime($supplied_date_nais->format('Y-m-d H:i:s')) * 1000;
        $supplied_date_nais = (!isset($supplied_date_nais) || $supplied_date_nais == NULL) ? NULL : $supplied_date_nais->format('Y-m-d H:i:s');
        

        $session_start_tstamp = (!isset($session_start_tstamp)) ? null : strtotime($session_start->format('Y-m-d H:i:s')) * 1000;
        $session_end_tstamp = (!isset($session_end_tstamp)) ? null : strtotime($session_end-format('Y-m-d H:i:s')) * 1000;
        $session_start = (!isset($session_start)) ? null : $session_start->format('Y-m-d H:i:s');
        $session_end = (!isset($session_end)) ? null : $session_end->format('Y-m-d H:i:s');
      

        if($new_row){
            //RAPPEL: Cette requête insert dans LOGIN_ATTEMPT
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $supplied_pseudo = $con->real_escape_string($supplied_pseudo);
            $supplied_email = $con->real_escape_string($supplied_email);
            $comment = $con->real_escape_string($comment);
            $qr = "INSERT INTO login_attempt (attempt_date, attempt_date_tstamp, supplied_pseudo, supplied_email, supplied_passwd, supplied_date_nais, supplied_date_nais_tstamp, comment, result)
                   VALUES ('$attempt_date', '$attempt_date_tstamp', '$supplied_pseudo', '$supplied_email', '$supplied_authpwd_cypher', '$supplied_date_nais', '$supplied_date_nais_tstamp', '$comment', '$result');";
            $ctrl = $con->query($qr);
            if($ctrl == false){
                $this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);
            } else {
                $this->setLast_insert_id($con->insert_id);
            }
        } else {
            //RAPPEL: Cette requête modifie LOGIN_LOG
            if(!isset($session_id)){
                $this->get_or_signal_error(1, 'custom_err_connection_missing_sessionid_update_loginlog', __FUNCTION__, __LINE__);
                return;
            } else {
                $qs = "SELECT * FROM login_log WHERE session_id = '$session_id';";
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                
                $ftc = $con->query($qs);
                if($ftc == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
                $fas = $ftc->fetch_array();
                
                foreach($fas as $ks => $vs){
                    foreach($args as $kt => $vt){
                        if($ks == $kt){
                            $vs = $vt;
                        }
                    }
                }
                //Reset des attributs
                foreach($fas as $k => $v){
                    $$k = $v;
                }
                //Update
                $qt = "UPDATE login_log SET WHERE session_id = '$session_id';";
                $ctrl = $con->query($qt);
                $con->close();
                if($ctrl == false){
                    $this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);
                }
            }
        }
    }
    
    
  
    /****************************************************************************************************************/
    /********************************************* SPECIFIQUE A LA CLASSE *******************************************/
    public function get_millitimestamp(){
        return round(microtime(TRUE)*1000);
    }
    
    public function login_detect($login){
        if(!isset($login) || $login == ''){
            $this->get_or_signal_error(1, 'err_user_l4cnxn1', __FUNCTION__, __LINE__);
            return false;
        } else {
            if(preg_match_all($this->regexMail, $login)){
                $type = 'email';
            } else if(preg_match_all($this->regexNickname, $login)){
                $type = 'pseudo';
            } else {
                $type = 'unknown';
            }
            return $type;
        }
    }
    
    protected function get_std_datas_format($args){
        $datas = array();
        foreach ($args as $k => $v){
            $$k = $v;
            if($v instanceof DateTime){
                $datas[$k] = $v;
            } else {
                $datas[$k] = trim($v);
            }
        }
        
        return $datas;
    }
    
    
    public function conn_by_email($timeofday, $email, $passwd, &$err_ref = null){
        /* Mis en standby -- Réimplémentation plus tard
        $spg = $this->detect_user_special_group($email);
        if($spg == TRUE){
            //L'utilisateur fait partie d'un groupe spécial et ne doit pas être connecté ici
            $err_ref = 'p_cnx_superconnect_via_simple';
            return FALSE;
        }*/
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $rq = "SELECT a.accid, a.accpseudo as pseudo, a.acc_eid as eid, a.acc_authpwd, a.accIsBan, eh.emailraw, a.secu_isThirdCritEna, a.secu_lock_d_start_tstamp, a.secu_lock_d_end_tstamp, a.secu_lock_h_start, a.secu_lock_h_end FROM accounts a INNER JOIN email_history eh ON a.accid = eh.accid WHERE eh.emailraw = '$email' AND eh.date_EndEna IS NULL";
        $result = $con->query($rq);
        if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        $con->close();
        if(!count($logged)){
            //Erreur au niveau de la requête
            //$this->get_or_signal_error(1, 'err_user_l4cnx2', __FUNCTION__, __LINE__);
            $err_ref = 'p_unknown_account';
            return;
        } else if(intval($logged['secu_isThirdCritEna']) == 0){
            //Si on a pas besoin de la date de naissance
            //On commence par regarder si le compte est bloqué
            $lw = $this->lock_wizard($timeofday, $logged['secu_lock_h_start'], $logged['secu_lock_h_end'], $logged['secu_lock_d_start_tstamp'], $logged['secu_lock_d_end_tstamp']);
            if($lw == TRUE){
                $err_ref = 'p_err_cnx_account_locked';
                return FALSE;
            }
            
            //Puis on regarde si le compte est banni
            if(intval($logged['accIsBan']) == 1){
                $err_ref = 'p_err_cnx_account_banned';
                return FALSE;
            }
            
            //Puis on compare le password
            $stored_hash = $logged['acc_authpwd'];
            $ACC = new ACCOUNT();
            $compare = $ACC->compare_hashed_passwd($passwd, $stored_hash);
            if($compare == TRUE && $lw == FALSE){
                //Connexion OK
                return $logged;
            } else {
                $err_ref = 'p_cnx_email_failed';
                return FALSE;
            }
        } else {
            //Si authentification via date de naissance activée
            //$err_ref = 'redirect_birthday';
            //NOTE - Pierre - 18/08/14 : Il ne faut pas dire à l'utilisateur qu'il faut qu'il donne sa date de naissance. Faille de sécurité.
            $err_ref = 'p_cnx_email_failed';
            return FALSE;
        }
    }


    public function conn_by_pseudo($timeofday, $pseudo, $passwd, &$err_ref = null){
        /* Mis en standby -- réimplémentation plus tard
        $spg = $this->detect_user_special_group($pseudo);
        if($spg == TRUE){
            //L'utilisateur fait partie d'un groupe spécial et ne doit pas être connecté ici
            $err_ref = 'p_cnx_superconnect_via_simple';
            return FALSE;
        }*/
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        
        $con->set_charset('utf8');
        $pseudo = $con->real_escape_string($pseudo);
        
        $rq = "SELECT accid, accpseudo as pseudo, acc_eid as eid, acc_authpwd, accIsBan, accpseudo, secu_coWithPseudoEna, secu_isThirdCritEna, secu_lock_d_start_tstamp, secu_lock_d_end_tstamp, secu_lock_h_start, secu_lock_h_end FROM accounts WHERE accpseudo = '$pseudo';";
        $result = $con->query($rq);
        if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        $con->close();
        if(!count($logged)){
            //Erreur au niveau de la requête
            //$this->get_or_signal_error(1, 'err_user_l4cnxn2', __FUNCTION__, __LINE__);
            $err_ref = 'p_unknown_account';
            return FALSE;
        } else if(intval($logged['secu_coWithPseudoEna']) == 0){
            //On signale qu'il faut que l'user se connecte avec son mail
            $err_ref = 'p_cnx_cowithpsd_disabled';
            $this->get_or_signal_error(1, 'err_user_l4cnxn3', __FUNCTION__, __LINE__);
            return;
        } else if(intval($logged['secu_isThirdCritEna']) == 0){
            //Si on a pas besoin de la date de naissance
            //On check le verrouillage du compte
            $lw = $this->lock_wizard($timeofday, $logged['secu_lock_h_start'], $logged['secu_lock_h_end'], $logged['secu_lock_d_start_tstamp'], $logged['secu_lock_d_end_tstamp']);
            if($lw == TRUE){
                $err_ref = 'p_err_cnx_account_locked';
                return FALSE;
            }
            
            //Puis on regarde si le compte est banni
            if(intval($logged['accIsBan']) == 1){
                $err_ref = 'p_err_cnx_account_banned';
                return FALSE;
            }
            
            //Enfin le password
            $stored_hash = $logged['acc_authpwd'];
            $ACC = new ACCOUNT();
            $compare = $ACC->compare_hashed_passwd($passwd, $stored_hash);
            if($compare == TRUE && $lw == FALSE){
                //Connexion OK
                return $logged;
            } else {
                $err_ref = 'p_cnx_pseudo_failed';
                return FALSE;
            }
        } else {
            //Si authentification via date de naissance activée
            //$err_ref = 'cnx_third_crit_error';
            //NOTE - Pierre - 18/08/14 : Il ne faut pas dire à l'utilisateur qu'il faut qu'il donne sa date de naissance. Faille de sécurité.
            $err_ref = 'p_cnx_pseudo_failed';
            return FALSE;
        }
    }
    
    public function conn_by_pseudo_and_birthday($timeofday, $pseudo, $passwd, $birthday, &$err_ref = NULL){
        
        $formated_birthday = date_create_from_format('d-m-Y', $birthday);
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $pseudo = $con->real_escape_string($pseudo);
        $rq = "SELECT a.accid, a.accpseudo as pseudo, a.acc_eid as eid, a.acc_authpwd, a.accIsBan, a.accpseudo, a.secu_coWithPseudoEna, a.secu_lock_d_start_tstamp, a.secu_lock_d_end_tstamp, a.secu_lock_h_start, a.secu_lock_h_end, p.uborndate FROM accounts a INNER JOIN profils p ON a.pflid = p.pflid WHERE accpseudo = '$pseudo';";
        $result = $con->query($rq);
        if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        $con->close();
        if(!count($logged)){
            //Erreur au niveau de la requête
            //$this->get_or_signal_error(1, 'err_user_l4cnxn2', __FUNCTION__, __LINE__);
            $err_ref = 'p_unknown_account';
            return FALSE;
        } else {
            //On regarde si le compte est banni
            if(intval($logged['accIsBan']) == 1){
                $err_ref = 'p_err_cnx_account_banned';
                return FALSE;
            }
            
            //On regarde si le compte est verrouillé
            $lw = $this->lock_wizard($timeofday, $logged['secu_lock_h_start'], $logged['secu_lock_h_end'], $logged['secu_lock_d_start_tstamp'], $logged['secu_lock_d_end_tstamp']);
            if($lw == TRUE){
                $err_ref = 'p_err_cnx_account_locked';
                return FALSE;
            }
            //On regarde si le password correspond
            $stored_hash = $logged['acc_authpwd'];
            $ACC = new ACCOUNT();
            $compare = $ACC->compare_hashed_passwd($passwd, $stored_hash);
            
            //On check aussi la date de naissance
            $realbirthday = date_create_from_format('Y-m-d H:i:s', $logged['uborndate']);
            $formated_realbirthday = $realbirthday->format('Y-m-d');
            if($formated_birthday->format('Y-m-d') == $formated_realbirthday){
                //Vérification de date de naissance OK
                $dob_check = TRUE;
            } else {
                $dob_check = FALSE;
            }
            if($compare == TRUE && $dob_check == TRUE && $lw == FALSE){
                //Connexion OK
                return $logged;
            } else {
                $err_ref = 'p_cnx_pseudobd_failed';
                return FALSE;
            }
        }
    }
    
    public function conn_by_email_and_birthday($timeofday, $email, $passwd, $birthday, &$err_ref = NULL){
        $formated_birthday = date_create_from_format('d-m-Y', $birthday);
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $rq = "SELECT a.accid, a.accpseudo as pseudo, a.acc_eid as eid, a.acc_authpwd, a.accIsBan, eh.emailraw, p.uborndate, a.secu_lock_d_start_tstamp, a.secu_lock_d_end_tstamp, a.secu_lock_h_start, a.secu_lock_h_end FROM accounts a INNER JOIN email_history eh ON a.accid = eh.accid INNER JOIN profils p ON a.pflid = p.pflid WHERE eh.emailraw = '$email' AND eh.date_EndEna IS NULL;";
        $result = $con->query($rq);
        if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        $con->close();
        if(!count($logged)){
            //Erreur au niveau de la requête
            //$this->get_or_signal_error(1, 'err_user_l4_cnxn2', __FUNCTION__, __LINE__);
            $err_ref = 'p_unknown_account';
            return FALSE;
        } else if(count($logged)) {
            //On regarde si le compte est banni
            if(intval($logged['accIsBan']) == 1){
                $err_ref = 'p_err_cnx_account_banned';
                return FALSE;
            }
            
            //On regarde si le compte est verrouillé
            $lw = $this->lock_wizard($timeofday, $logged['secu_lock_h_start'], $logged['secu_lock_h_end'], $logged['secu_lock_d_start_tstamp'], $logged['secu_lock_d_end_tstamp']);
            if($lw == TRUE){
                $err_ref = 'p_err_cnx_account_locked';
                return FALSE;
            }
            //On regarde si le password correspond
            $stored_hash = $logged['acc_authpwd'];
            $ACC = new ACCOUNT();
            $compare = $ACC->compare_hashed_passwd($passwd, $stored_hash);
            
            //On check aussi la date de naissance
            $realbirthday = date_create_from_format('Y-m-d H:i:s', $logged['uborndate']);
            $formated_realbirthday = $realbirthday->format('Y-m-d');
            if($formated_birthday->format('Y-m-d') == $formated_realbirthday){
                //Vérification de date de naissance OK
                $dob_check = TRUE;
            } else {
                $dob_check = FALSE;
            }
            if($compare == TRUE && $dob_check == TRUE && $lw == FALSE){
                //Connexion OK
                return $logged;
            } else {
                $err_ref = 'p_cnx_emailbd_failed';
                return FALSE;
            }
        }
    }
    
    public function super_connection($timeofday, $email, $passwd, $birthday, $superpw, &$err_ref){
        $formated_birthday = date_create_from_format('d-m-Y', $birthday);
        //$con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $rq = "SELECT a.accid, a.accpseudo as pseudo, a.acc_eid as eid, a.acc_authpwd, eh.emailraw, p.uborndate, sg.grp_authpwd, a.secu_lock_d_start_tstamp, a.secu_lock_d_end_tstamp, a.secu_lock_h_start, a.secu_lock_h_end, a.accIsBan FROM accounts a INNER JOIN email_history eh ON a.accid = eh.accid INNER JOIN profils p ON a.pflid = p.pflid INNER JOIN abo_grp_histo agh ON a.accid = agh.accid INNER JOIN specialgroupes sg ON agh.gid = sg.gid WHERE eh.emailraw = '$email';";
        $rslt = $con->query($rq);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
        $fetch = $rslt->fetch_array(MYSQLI_ASSOC);
        if(!count($fetch)){
            //erreur de la requête
            //$this->get_or_signal_error(1, 'err_user_l4cnxn2', __FUNCTION__, __LINE__);
            $err_ref = 'p_unknown_account';
            return FALSE;
        } else if(count($fetch)){
            //On regarde si le compte est banni
            if(intval($fetch['accIsBan']) == 1){
                $err_ref = 'p_err_cnx_account_banned';
                return FALSE;
            }
            
            //On regarde si le compte est bloqué
            $lw = $this->lock_wizard($timeofday, $fetch['secu_lock_h_start'], $fetch['secu_lock_h_end'], $fetch['secu_lock_d_start_tstamp'], $fetch['secu_lock_d_end_tstamp']);
            if($lw == TRUE){
                $err_ref = 'p_err_cnx_account_locked';
                return FALSE;
            }
            //On check le password personnel
            $ACC = new ACCOUNT();
            $stored_acc_hash = $fetch['acc_authpwd'];
            $compare_acc = $ACC->compare_hashed_passwd($passwd, $stored_acc_hash);
            
            //On check la date de naissance
            $realbirthday = date_create_from_format('Y-m-d H:i:s', $fetch['uborndate']);
            if($formated_birthday->format('Y-m-d') == $realbirthday->format('Y-m-d')){
                //Vérification de date de naissance OK
                $dob_check = TRUE;
            } else {
                $dob_check = FALSE;
            }
            
            //Enfin, on check le mot de passe du groupe
            $stored_grp_hash = $fetch['grp_authpwd'];
            $compare_grp = $ACC->compare_hashed_passwd($superpw, $stored_grp_hash);
            
            //On vérifie que tous nos checks sont OK pour continuer
            if($compare_acc == TRUE && $dob_check == TRUE && $compare_grp == TRUE && $lw == FALSE){
                //Connexion OK
                return $fetch;
            } else {
                $err_ref = 'p_superconnect_failed';
                return FALSE;
            }
        }
    }
    
    /**
     * Fonction qui va déterminer si l'état d'un compte est bloqué ou pas en fonction des données qui lui sont passées
     * en entrée.<br>Note: les hlock_* sont des heures (format H:i) et les dlock_* sont des timestamps (millitimestamps).
     * <br>Retourne TRUE si le compte est actuellement locké, FALSE dans le cas contraire.
     * @param string $local_time Heure locale du client, récupéré via le Javascript (H:i)
     * @param string $hlock_start Heure de début de lock (H:i)
     * @param string $hlock_end Heure de fin de lock (H:i)
     * @param string $dlock_start_ts Timestamp de début de lock
     * @param string $dlock_end_ts Timestamp de fin de lock
     * @return boolean
     */
    public function lock_wizard($local_time, $hlock_start, $hlock_end, $dlock_start_ts, $dlock_end_ts){
        $now = new DateTime();
        $now_ts = $this->get_millitimestamp();
        $lock = NULL;
        $d_lock = NULL;
        $h_lock = NULL;
        
        
        //D-LOCK
        if($now_ts >= $dlock_start_ts && $now_ts <= $dlock_end_ts){
            $d_lock = TRUE;
        } else {
            $d_lock = FALSE;
        }

        //H-LOCK
        $localArray = explode(':', $local_time);
        $startArray = explode(':', $hlock_start);
        $endArray = explode(':', $hlock_end);
        
        if(intval($localArray[0]) > intval($startArray[0]) && intval($localArray[0]) < intval($endArray[0])){
            $h_lock = TRUE;
        } else if(intval($localArray[0]) >= intval($startArray[0]) && intval($localArray[0]) < intval($endArray[0])){
            if(intval($localArray[1] >= $startArray[1])){
                $h_lock = TRUE;
            } else {
                $h_lock = FALSE;
            }
        } else if(intval($localArray[0]) > intval($startArray[0]) && intval($localArray[0]) <= intval($endArray[0])){
            if(intval($localArray[0] <= $endArray[1])){
                $h_lock = TRUE;
            } else {
                $h_lock = FALSE;
            }
        }
        
        //Vérif
        if($d_lock == FALSE && $h_lock == FALSE){
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /**
     * Fonction qui va vérifier si les identifiants fournis sont corrects
     * @param type $login
     * @param type $passwd
     * @param type $type
     * @return boolean
     */
    public function gccheck($login, $passwd, $type){
        $ACC = new ACCOUNT();
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        switch($type){
            case 'pseudo':
                $login = $con->real_escape_string($login);
                $rq = "SELECT accid, acc_authpwd, accpseudo, secu_coWithPseudoEna FROM accounts WHERE accpseudo = '$login';";
                $result = $con->query($rq);
                if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
                $logged = $result->fetch_array(MYSQLI_ASSOC);
                $con->close();
                if(count($logged)){
                    $compare = $ACC->compare_hashed_passwd($passwd, $logged['acc_authpwd']);
                    if($compare == TRUE){
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;
            case 'email':
                $login = $con->real_escape_string($login);
                $rq = "SELECT a.accid, a.acc_authpwd, eh.emailraw FROM accounts a INNER JOIN email_history eh ON a.accid = eh.accid WHERE eh.emailraw = '$login';";
                $result = $con->query($rq);
                if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
                $logged = $result->fetch_array(MYSQLI_ASSOC);
                $con->close();
                if(count($logged)){
                    $compare = $ACC->compare_hashed_passwd($passwd, $logged['acc_authpwd']);
                    if($compare == TRUE){
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;
        }
    }
    
    
    public function valid_connection_instance($array){
        $err_tab = array();
        foreach ($array as $k => $v){
            switch($k){
                case 'attempt_date':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn4', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'supplied_pseudo':
                    if(!(preg_match($this->regexNickname, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'supplied_email':
                    if(!(preg_match($this->regexMail, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'supplied_passwd':
                    if(!(preg_match($this->regexPasswdMini, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'supplied_date_nais':
                    if($v != NULL && !(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn4', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'supplied_date_nais_tstamp':
                    if($v != NULL && !(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'comment':
                    if(!(preg_match('/^[^<>"=]$/', $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'result':
                    if(intval($v) != 1 && intval($v) != 0){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'attempt_date_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'session_start':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn4', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'session_end':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn4', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'session_start_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'session_end_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'accid':
                    if(!(preg_match('/^\d{1,}$/', $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'pflid':
                    if(!(preg_match('/^\d{1,}$/', $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4cnxn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                default:
                    break;
            }
        }
        return $err_tab;
    }
    
    public function get_all_connected_atm(){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $q = "SELECT * FROM login_log WHERE session_end IS NULL;";
        $rslt = $con->query($q);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__);}
        $fa = $rslt->fetch_array();
        return $fa;
    }
    
    /**
     * This function will detect if a given user belong to a special group with their login (email / pseudo)
     * If a superuser try to use their pseudo to log in, we will ask them to use their email.
     * 
     * There are 3 possible return values:<br />
     * - <b>FALSE</b> if no match is found
     * - <b>TRUE</b> if one or more matches are found, but the user tried to log with their pseudo
     * - <b>array()</b> containing the matches if matches are found and the used tried to log with their email
     * 
     * @param string $login
     * @return array|boolean
     * 
     */
    public function detect_user_special_group($login){
        $type = $this->login_detect($login);
        $toReturn = null;
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        switch($type){
            case 'pseudo':
                $login = $con->real_escape_string($login);
                //Requête 1: On récupère l'ID du compte de la personne en fonction du pseudo
                $rq_p1 = "SELECT accid FROM accounts WHERE accpseudo = '$login';";
                $rs_p1 = $con->query($rq_p1);
                if($rs_p1 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return NULL;}
                $ar_p1 = $rs_p1->fetch_assoc();
                $accid = $ar_p1['accid'];
                
                //Requête 2: On récupère tous les groupes auxquels l'utilisateur appartient
                $rq_p2 = "SELECT gid FROM abo_grp_histo WHERE accid = '$accid';";
                $rs_p2 = $con->query($rq_p2);
                if($rs_p2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return NULL;}
                $gid_list = array();
                while($ar_p2 = $rs_p2->fetch_array(MYSQLI_ASSOC)){
                    $gid_list[] = $ar_p2['gid'];
                }
                
                //Requête 3: Pour chaque groupe trouvé de cette manière, on regarde s'il correspond à un groupe spécial
                $spg_list = array();
                foreach($gid_list as $ugp){
                    $rq_p3 = "SELECT spgid FROM specialgroupes WHERE gid = '$ugp';";
                    $rs_p3 = $con->query($rq_p3);
                    if($rs_p2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return NULL;}
                    $ar_p3 = $rs_p3->fetch_array(MYSQLI_ASSOC);
                    $spg_list[] = $ar_p3['spgid'];
                }
                
                //On termine en 'nettoyant' le tableau
                $spgid_list = array();
                foreach($spg_list as $v){
                    if($v != NULL){
                        $spgid_list[] = $v;
                    }
                }
                
                //Et en le retournant s'il y a quelque chose à retourner
                if(count($spgid_list)){
                    return TRUE;
                } else {
                    return FALSE;
                }
                break;
                
            case 'email':
                $login = $con->real_escape_string($login);
                //Requête 1: On récupère l'ID du compte de la personne en fonction du mail
                $rq_e1 = "SELECT accid FROM email_history WHERE emailraw = '$login' AND date_EndEna IS NULL;";
                $rs_e1 = $con->query($rq_e1);
                if($rs_e1 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return NULL;}
                $ar_e1 = $rs_e1->fetch_assoc();
                $accid = $ar_e1['accid'];
                
                //Requête 2: On récupère tous les groupes auxquels l'utilisateur appartient
                $rq_e2 = "SELECT gid FROM abo_grp_histo WHERE accid = '$accid';";
                $rs_e2 = $con->query($rq_e2);
                if($rs_e2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return NULL;}
                $gid_list = array();
                while($ar_e2 = $rs_e2->fetch_array(MYSQLI_ASSOC)){
                    $gid_list[] = $ar_e2['gid'];
                }
                
                //Requête 3: Pour chaque groupe trouvé de cette manière, on regarde s'il correspond à un groupe spécial
                $spg_list = array();
                foreach($gid_list as $ugp){
                    $rq_e3 = "SELECT spgid FROM specialgroupes WHERE gid = '$ugp';";
                    $rs_e3 = $con->query($rq_e3);
                    if($rs_e2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4cnxn1', __FUNCTION__, __LINE__); return NULL;}
                    $ar_e3 = $rs_e3->fetch_array(MYSQLI_ASSOC);
                    $spg_list[] = $ar_e3['spgid'];
                }
                
                //On termine en 'nettoyant' le tableau
                $spgid_list = array();
                foreach($spg_list as $v){
                    if($v != NULL){
                        $spgid_list[] = $v;
                    }
                }
                
                //Et en le retournant s'il y a quelque chose à retourner
                if(count($spgid_list)){
                    return $spgid_list;
                } else {
                    return FALSE;
                }
                break;
            default:
                $this->get_or_signal_error(1, 'err_user_l4cnxn1', __FUNCTION__, __LINE__);
                break;
        }
        $con->close();
        return $toReturn;
    }
    
    public function profile_updates_passwd_checker($args) {
        $accid = $args['accid'];
        $passwd = $args['hiddenpw'];
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $passwd = $con->real_escape_string($passwd);
        $qr = "SELECT acc_authpwd FROM accounts WHERE accid = '$accid';";
        $rslt = $con->query($qr);
        $fpw = $rslt->fetch_array(MYSQLI_ASSOC);
        $con->close();
        $ACC = new ACCOUNT();
        $compare = $ACC->compare_hashed_passwd($passwd, $fpw['acc_authpwd']);
        return $compare;
    }
    
    public function delete_account_passwd_checker($args){
        $accid = $args['accid'];
        $passwd = $args['passwd'];
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $passwd = $con->real_escape_string($passwd);
        $qr = "SELECT acc_authpwd FROM accounts WHERE accid = '$accid';";
        $rslt = $con->query($qr);
        $fpw = $rslt->fetch_array(MYSQLI_ASSOC);
        $con->close();
        $ACC = new ACCOUNT();
        $compare = $ACC->compare_hashed_passwd($passwd, $fpw['acc_authpwd']);
        return $compare;
    }
    
// <editor-fold defaultstate="collapsed" desc="Getters / Setters">
    public function getAttempt_id() {
        return $this->attempt_id;
    }

    public function getAttempt_date() {
        return $this->attempt_date;
    }

    public function getAttempt_date_tstamp() {
        return $this->attempt_date_tstamp;
    }

    public function getSupplied_pseudo() {
        return $this->supplied_pseudo;
    }

    public function getSupplied_email() {
        return $this->supplied_email;
    }

    public function getSupplied_passwd() {
        return $this->supplied_passwd;
    }

    public function getSupplied_date_nais() {
        return $this->supplied_date_nais;
    }

    public function getSupplied_date_nais_tstamp() {
        return $this->supplied_date_nais_tstamp;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getResult() {
        return $this->result;
    }

    public function getSession_id() {
        return $this->session_id;
    }

    public function getAccid() {
        return $this->accid;
    }

    public function getSession_start() {
        return $this->session_start;
    }

    public function getSession_start_tstamp() {
        return $this->session_start_tstamp;
    }

    public function getSession_end() {
        return $this->session_end;
    }

    public function getSession_end_tstamp() {
        return $this->session_end_tstamp;
    }

    public function setAttempt_id($attempt_id) {
        $this->attempt_id = $attempt_id;
    }

    public function setAttempt_date($attempt_date) {
        $this->attempt_date = $attempt_date;
    }

    public function setAttempt_date_tstamp($attempt_date_tstamp) {
        $this->attempt_date_tstamp = $attempt_date_tstamp;
    }

    public function setSupplied_pseudo($supplied_pseudo) {
        $this->supplied_pseudo = $supplied_pseudo;
    }

    public function setSupplied_email($supplied_email) {
        $this->supplied_email = $supplied_email;
    }

    public function setSupplied_passwd($supplied_passwd) {
        $this->supplied_passwd = $supplied_passwd;
    }

    public function setSupplied_date_nais($supplied_date_nais) {
        $this->supplied_date_nais = $supplied_date_nais;
    }

    public function setSupplied_date_nais_tstamp($supplied_date_nais_tstamp) {
        $this->supplied_date_nais_tstamp = $supplied_date_nais_tstamp;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function setSession_id($session_id) {
        $this->session_id = $session_id;
    }

    public function setAccid($accid) {
        $this->accid = $accid;
    }

    public function setSession_start($session_start) {
        $this->session_start = $session_start;
    }

    public function setSession_start_tstamp($session_start_tstamp) {
        $this->session_start_tstamp = $session_start_tstamp;
    }

    public function setSession_end($session_end) {
        $this->session_end = $session_end;
    }

    public function setSession_end_tstamp($session_end_tstamp) {
        $this->session_end_tstamp = $session_end_tstamp;
    }

    public function getRegexNickname() {
        return $this->regexNickname;
    }

    public function getRegexPasswdMini() {
        return $this->regexPasswdMini;
    }

    public function getRegexMail() {
        return $this->regexMail;
    }

    public function getRegexDate() {
        return $this->regexDate;
    }
    
    public function getRegexTstamps() {
        return $this->regexTstamps;
    }
    
    public function getLast_insert_id() {
        return $this->last_insert_id;
    }

    public function setLast_insert_id($last_insert_id) {
        $this->last_insert_id = $last_insert_id;
    }


// </editor-fold>

    
}