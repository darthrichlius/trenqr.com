<?php
/**
 * Description of prod
 *
 * @author lou.carther.69
 */
class EMAIL extends PROD_ENTITY {
    //[NOTE au 23/10/2013] Il faut modifier la classe pour enlever les parametres au constructeur et le laisser à load pour que user puisse definir le comportement en cas de ... 
    private $reg;
    private $max_size;
    private $use_default;
    
    private $email;
    private $is_authentic;
    private $emaildomain;
    private $emaillogin;
    private $creadate;
    
    /* Ajouts PL */
    private $emailorigin;

    /**
     * <p>une instance de la classe EMAIL ne peut fonctionner sans avoir au préalable déclaré un email.<br/>
     * Il est possible de manipuler un objet 'Email' sans faire appel à la base de données. Dans ce cas, libre au caller de lancer l'opération de load.
     * Si on ne souhaite pas que l'objet instancie soit 'loaded' on ne le precise pas.</p>
     * <p>Si l'utilisateur décide d'utiliser ses propres paramètres de gestion, il mettra $use_default à FALSE.<br/>
     * Il devra par la suite, appeler lui même la fonction de validation et celle de fracture.</p>
     * 
     * @see valid_email()
     * @see get_login_and_domain_from_email()
     * @param type $email
     * @param type $load
     * @param type $use_default
     */
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        
        /**
         * Rules :
         * [a-zA-Z0-9]
         * Only one '@'
         * Max of extensions : we can't limit the max of extensions. So ... But we can say that the string end with one word with a max of 4 letters.
         * No 'word character' allowed : .-_ (yahoo: [a-zA-Z0-9._]; gmail [a-zA-Z0-9.]; hotmail [a-zA-Z0-9.-_]
         */
        /**
         * On choisit de ne pas utiliser filter_vars() car elle autorise des caractères que nous ne souhaitons pas.
         * De plus, on ne choisit pas la nomenclature proposée par IETF via son RFC. 
         */
        $this->reg = "/^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/";
        /**
         * local part : 64 characters
         * '@' : 1
         * domain : 63 characters
         * extension : from 2 (3 with '.') to 6 (7 with '.') (example : .gouv.fr
         * total max = 135 increased to 150 characters
         */
        $this->max_size = 150;
        /*
        $this->use_default = $use_default;
        
        if ($this->use_default === TRUE) $this->on_create_entity();
        if ($load === TRUE) $this->load_entity();
        */
    }
    
    public function build_volatile($args) {
        /*$this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $this->email = trim($email);
        
        $this->valid_email (); 
        $datas = $this->get_login_and_domain_from_email(); 
        $datas["email"] = $this->email;
        $datas["creadate"] = new DateTime();
        $datas["creadate"]->format("Y-m-d H:i:s");
        $datas["is_authentic"] = FALSE;
        $this->init_properties($datas);*/
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $datecrea = ( !isset($datecrea) ) ? new DateTime() : $datecrea;
        $vStorage['datecrea'] = $datecrea;
        $vStorage['datecrea_tstamp'] = $this->get_millitimestamp();
        $bar = $this->get_login_and_domain_from_email($vStorage['emailraw']);
        $vStorage['email_login'] = $bar['login'];
        $vStorage['emaildom'] = $bar['domain'];
        
        $datas = $this->get_std_datas_format($vStorage);
        $foo = $this->valid_email($vStorage['emailraw'], false);
        if($foo != true){
            return $foo;
        } else {
            $this->init_properties($datas);
        }
    }

    
    public function on_create_entity($args) {
        /*$this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $this->email = trim($email);
        
        $this->valid_email (); 
        $datas = $this->get_login_and_domain_from_email(); 
        $datas["email"] = $this->email;
        $datas["creadate"] = new DateTime();
        $datas["creadate"]->format("Y-m-d H:i:s");
        $datas["is_authentic"] = FALSE;
        $this->init_properties($datas);
        $this->write_new_in_database($email);*/
        //----------------------------------------------------------------
        //Tableau de stockage des variables qui vont exister dans le scope
        $vStorage = [];
        foreach($args as $k => $v){
            $$k = $v;
            $vStorage[$k] = $v;
        }
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //Tous les attributs de la base ne sont pas nécessaires à la création, mais seront mis à jour dans un update
        $datecrea = ( !isset($datecrea) ) ? new DateTime() : $datecrea;
        //$vStorage['datecrea_tstamp'] = $datecrea->getTimestamp();
        $vStorage['datecrea'] = $datecrea->format('Y-m-d H:i:s');
        $vStorage['datecrea_tstamp'] = $this->get_millitimestamp();
        $bar = $this->get_login_and_domain_from_email($vStorage['emailraw']);
        $vStorage['email_login'] = $bar['login'];
        $vStorage['emaildom'] = $bar['domain'];
        
        $datas = $this->get_std_datas_format($vStorage);
        $foo = $this->valid_email($vStorage['emailraw'], false);
        if($foo != true){
            return $foo;
        } else {
            $this->init_properties($datas);
            $written = $this->write_new_in_database($datas, true);
            return $written;
        }
    }
    
    public function get_std_datas_format($args){
        $datas = array();
        foreach ($args as $k => $v){
            $$k = $v;
            if($v instanceof DateTime /*|| $v instanceof Time*/){
                $datas[$k] = $v;
            } else {
                $datas[$k] = trim($v);
            }
        }
        return $datas;
    }
    
    public function load_entity($args) {
        foreach ($args as $k => $v){
            $$k = $v;
        }
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args);
        
        /*$QO = new QUERY("qryl4emailn1");
        $qparams_val = array( ':emailraw' => $this->email );
        
        $d = $QO->execute($qparams_val);*/
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $emailraw = $con->real_escape_string($emailraw);
        $QO = "SELECT * FROM emailarchive WHERE emailraw = '$emailraw'";
        $rslt = $con->query($QO);
        $d = $rslt->fetch_array;
        
        if ( !count($d) ) { 
            if ( $std_err_enbaled )  $this->signalError ("err_user_l017", __FUNCTION__, __LINE__);
            else return 0;
        } else  $this->init_properties($d);
    }
    

    protected function on_alter_entity($args) {
        /**
         * Dans notre contexte :
         * Reste vide car email etant une clé primaire on ne peut la modifier directement.
         */
    }
    
    public function on_delete_entity($args) {
        /**
         * Dans notre contexte :
         * Il faut au prealable que ACCOUNT soit deleted.
         * Si ce n'est pas le cas on obtiendra une erreur de la part de la base de données
         */
        $QO = new QUERY("qryl4emailn2");
        $qparams_val = array( ':emailraw' => $this->email );
        
        $QO->execute($qparams_val);
    }

    protected function on_read_entity($args) {
        /**
         * Dans notre contexte :
         * Rien n'est prévu pour cet objet
         */
    }
    
    public function exists($args) {
        /*foreach($args as $k => $v){
            $$k = $v;
        }
        if ( !( isset($emailraw) AND $emailraw != "" )  OR !( isset($this->emailraw) AND $this->emailraw != "") ) $this->signalError ("custom_err_email_exists_1", __FUNCTION__, __LINE__);
        else if ( (!isset($emailraw) OR $emailraw =="") and (isset($this->emailraw) and $this->emailraw != "") ) $emailraw = $this->emailraw;
        
        // ** Partie MySQLi ** //
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $QO = "SELECT emailraw from emailarchive WHERE emailraw = '$emailraw'";
        $rslt = $con->query($QO);
        $fa = $rslt->fetch_array();
        
        if(!count($fa)){
            return false;
        } else {
            return true;
        }*/
        
        /*
         * Fonction du dessus utilisable mais "inutile".
         * Pour savoir si un email existe, on utilise une des deux fonctions suivantes:
         * exists_and_is_used($email);
         * exists_in_archive($email);
         */
    }
    /**
     * 
     * @param type $email
     * The email address we want to test
     * @return boolean $available
     * Return TRUE if mail is available, FALSE if it is already assigned to an account
     */
    public function exists_and_is_used ($email) {
        //[NOTE au 24/10/13] : Ajout de la methode
        //[NOTE au 29/10/13] : Modification de la condition
        if ( !( isset($email) AND $email != "" )  /*OR*/ AND !( isset($this->email) AND $this->email != "") ) /*$this->signalError ("err_user_l00", __FUNCTION__, __LINE__)*/ return 'EMPTY';
        else if ( (!isset($email) OR $email =="") AND (isset($this->email) AND $this->email != "") ) $email = $this->email;
        
        
        //Pour les valeurs de retour: 
        
        $available = TRUE;
        /*$QO = new QUERY("qryl4emailn1");
        $qparams_val = array( ':emailraw' => $email );
        
        $d = $QO->execute($qparams_val);
        if ( count($d) ) $available = TRUE; */
        
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $QO = "SELECT emailraw FROM email_history WHERE emailraw = '$email' AND date_EndEna IS NULL;";
        $result = $con->query($QO);
        if($result == FALSE){ return NULL;}
        $rlog = $result->fetch_array(MYSQLI_ASSOC);
        $con->close();
       
        if( count($rlog) ){
            $available = FALSE;
        }
        return $available;
    }
    
    public function exists_in_archive($email){
        if ( !( isset($email) AND $email != "" )  /*OR*/ AND !( isset($this->email) AND $this->email != "") ) /*$this->signalError ("err_user_l00", __FUNCTION__, __LINE__)*/ echo 'ERREUR | MAIL';
        else if ( (!isset($email) OR $email =="") AND (isset($this->email) AND $this->email != "") ) $email = $this->email;
        
            
        $res = FALSE;        
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $QO = "SELECT emailraw FROM emailarchive WHERE emailraw = '$email';";
        $result = $con->query($QO);
        $rlog = $result->fetch_array(MYSQLI_ASSOC);
        $con->close();
        if(count($rlog) == 1){
            $res = TRUE;
        }
        
        return $res;
    }


    public function write_new_in_database($args, $new_row = null) {
        /*$QO = new QUERY("qryl4emailn3");
        
        $foo = new DateTime();
        $qparams_in_values = array(":emailraw" => $this->email,":emaildomain" => $this->emaildomain,":email_login" => $this->emaillogin,":creadate" => $foo->format("Y-m-d H:i:s"));  
        
        $QO->execute($qparams_in_values);*/
        
        //---------------------------------------------------------
        
        //L'update sur cette table correspond à la validation de l'email. Lors de la création d'un compte classique, la validation se fait automatiquement en même temps
        //Sinon, ce sera un update.
        
        
        //Initialisation des variables
        $emailraw = $email_login = $emaildom = $datecrea = $datevalid = null;
        $datecrea_tstamp = $datevalid_tstamp = null;
        $origin = 1;
        
        //Load des variables
        foreach($args as $k => $v){
            $$k = $v;
        }
        
        //Gestion des dates
        //[MODIF 04/08/14] => Les DATEVALID seront gérées autrement, par une action de l'utilisateur. Elles ne sont pas à traiter à la création
        $datecrea = new DateTime;
        //$datevalid = new DateTime;
        
        $datecrea_tstamp = (!isset($datecrea)) ? null : $this->get_millitimestamp();
        //$datecrea_tstamp = (!isset($datecrea)) ? null : $datecrea->getTimestamp();
        //$datevalid_tstamp = (!isset($datevalid)) ? null : $datevalid->getTimestamp();
        
        $datecrea = $datecrea->format('Y-m-d H:i:s');
        //$datevalid = $datevalid->format('Y-m-d H:i:s');
        
        if($new_row){
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $emailraw = $con->real_escape_string($emailraw);
            $email_login = $con->real_escape_string($email_login);
            $emaildom = $con->real_escape_string($emaildom);
            var_dump($emailraw,$email_login,$emaildom);
            $QO = "INSERT INTO emailarchive (emailraw, email_login, emaildom, datecrea, datevalid, origin, datecrea_tstamp, datevalid_tstamp)
                  VALUES ('$emailraw', '$email_login', '$emaildom', '$datecrea', NULL, ".intval($origin).", '$datecrea_tstamp', NULL);";
            $ctrl = $con->query($QO);
            echo $con->error;
            $con->close();
            if($ctrl == false){
                $this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);
                return false;
            } else {
                return true;
            }
        } else {
            if(!isset($emailraw)){
                $this->get_or_signal_error(1, 'err_user_l4eman2', __FUNCTION__, __LINE__);
            } else {
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $emailraw = $con->real_escape_string($emailraw);
                $QU = "UPDATE emailarchive SET datevalid = '$datevalid', datevalid_tstamp = '$datevalid_tstamp' WHERE emailraw = '$emailraw';";
                $ctrl = $con->query($QU);
                if($ctrl == false){
                    $this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);
                    return false;
                } else {
                    return true;
                }
            }
        }
        
    }

    /*************************************************************************************************************/
    /**************************************** FONCTIONS PROPRES A LA CLASSE **************************************/
    
    public function get_millitimestamp(){
        return round(microtime(TRUE)*1000);
    }
    
    public function send_email ($email_sender, $email_recipient, $subject, $email_body, $reply_to = "") {
        $headers ='From: '. $email_sender ."\n";
        $headers .='Reply-To: '. $reply_to ."\n";
        $headers .='Content-Type: text/html; charset="iso-8859-1"'."\n";
        $headers .='Content-Transfer-Encoding: 8bit';
        
        //Envoi du mail
        $ok = mail($email_recipient, $subject, $email_body, $headers); 
        
        if($ok == false){
            /* Si on passe ici c'est que le mail n'a pas été accepté pour livraison, et donc non envoyé */
            return FALSE;
        } else {
            /* Mail accepté pour livraison. Attention: ça ne veut pas forcément dire qu'il a été envoyé. */
            return TRUE;
        }
    }
    
    public function get_login_and_domain_from_email ($email) {
        $foo = explode('@', $email);
        
        $datas["login"] = $foo[0];
        $datas["domain"] = $foo[1];
        
        return $datas;
    }
    
    protected function init_properties($datas) {
        //Normallement utilisé pour fill les donnes provenant de la bdd mais aussi provenant de l'exterieur
        /*$this->all_properties["email"] = $this->email = $datas["email"];
        $this->all_properties["is_authentic"] = $this->is_authentic = $datas["is_authentic"];
        $this->all_properties["domain"] = $this->emaildomain = $datas["domain"];
        $this->all_properties["login"] = $this->emaillogin = $datas["login"];
        $this->all_properties["creadate"] = $this->creadate = $datas["creadate"];
        $this->all_properties["emailorigin"] = $this->emailorigin = $datas["emailorigin"];*/
        //----------------------------------------------------
        foreach($datas as $k => $v){
            $$k = $v;
            if ($v instanceof DateTime){
                $this->all_properties[$k] = $this->$k = $datas[$k];
            } else {
                $this->all_properties[$k] = $this->$k = trim($datas[$k]);
            }
        }
    }
    
    public function reinit_default_config () {
        //Si l'utilisateur veut revenir auc valeurs par défaut après qu'il ait délibérement changer ces valeurs.
        $this->max_size = 150;
        $this->reg = "/^(?!((.*)@(.*)){2,})[\w.-]+@[a-z0-9-.]+\.[a-z]{2,4}$/";
    }
    
    public function valid_email($email, $std_err_enabled = NULL) {
        //RAPPEL : Si l'utilisateur souhaite changer les regex il doit le faire avec les accesseurs.
        
        //Si l'utilisateur n'a pas rentré d'email on prend la valeur prop.
        //Si la valeur prop n'est pas définie, on déclenche une erreur.
        if ( !isset($email) and !(isset($this->email) and $email != "") ) $this->signalError ("err_user_l00", __FUNCTION__, __LINE__);
        else if ( !isset($email) and (isset($this->email) and $email != "") ) $email = $this->email;
        
        $code = ($std_err_enabled) ? 2 : 1;
        
        if ( !(preg_match($this->reg, trim($email)) and count($this->max_size) <= $this->max_size) ) {
            return $this->get_or_signal_error ($code, "err_user_l018", __FUNCTION__, __LINE__);
        } else {
            return true;
        }
    }
    
    public function get_email_origin($email){
        /* fonction qui va chercher l'origine de l'email, pour savoir comment tel utilisateur est arrivé.
         * En entrée, l'email à étudier */
        
        //Connection à une DB -- Local only
        $con = mysqli_connect('localhost', 'root', '', 'kx_account_vbeta');
        $query = 'SELECT a.emailraw, a.origin, o.desg FROM kx_account_vbeta.emailarchive a INNER JOIN kx_account_vbeta.emailorigins o ON a.origin = o.id WHERE a.emailraw = "'. $email .'";';
        $result = mysqli_query($con, $query);
        $originArray = mysqli_fetch_array($result);
        
        
        /* À voir ce qu'on veut retourner */
        $originId = $originArray[1];
        $originDesg = $originArray[2];
    }
    
    
//    public function keyGenerator(){
//        $genKey = bin2hex(openssl_random_pseudo_bytes(128, $crypto_strong));
//        if(!$crypto_strong){
//            exit('Crypto too weak');
//        } else {
//            return $genKey;
//        }
//    }
//    
//    public function create_key($email){
//        $key = $this->keyGenerator();
//        $sentdate = $sentdate_tstamp = null;
//        $datecrea = $expdate = new DateTime();
////        $datecrea_tstamp = $datecrea->getTimestamp();
////        $expdate = $expdate->modify('+1 month');
////        $expdate_tstamp = $expdate->getTimestamp();
//        $datecrea_tstamp = $this->get_millitimestamp();
//        $expdate = $expdate->modify('+1 month');
//        //Méthode complètement cheesy pour coller au format des microsecondes, mais pour le moment j'ai pas mieux
//        $expdate_tstamp = strtotime($expdate->getTimestamp())*1000;
////        $expdate_tstamp = $expdate->getTimestamp();
//        
//        $datecrea = $datecrea->format('Y-m-d H:i:s');
//        $expdate = $expdate->format('Y-m-d H:i:s');
//        
//        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//        $rq = "INSERT INTO accountkey (acc_key, datecrea, datecrea_tstamp, sentdate, sentdate_tstamp, expdate, expdate_tstamp, email)
//               VALUES ('$key', '$datecrea', '$datecrea_tstamp', '$sentdate', '$sentdate_tstamp', '$expdate', '$expdate_tstamp', '$email');";
//        $ctrl = $con->query($rq);
//        if($ctrl == FALSE){
//            $this->get_or_signal_error('custom_err_createkey', '', __FUNCTION__, __LINE__);
//            return false;
//        } else {
//            return $key;
//        }
//        
//    }
    
    public function send_passwd_recovery_email($sender, $addressee, $subject, $body){
        $headers = 'From: '.$sender;
        $isSent = mail($addressee, $subject, $body, $headers);
        if(!$isSent){
            $this->get_or_signal_error(1, 'err_sys_l4eman3', __FUNCTION__, __LINE__);
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    
    public function is_own_email($accid, $email){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $email = $con->real_escape_string($email);
        $qr = "SELECT emailraw FROM email_history WHERE accid = '$accid' AND emailraw = '$email';";
        $obj = $con->query($qr);
        $con->close();
        if($obj == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);
            return;
        }
        $corresp = $obj->fetch_array(MYSQLI_ASSOC);
        if(count($corresp) == 1){
            //On a une correspondance, c'est donc notre email
            return TRUE;
        } else {
            //Pas notre email
            return FALSE;
        }
    }
    
    /**
     * Fonction présente dans la classe EMAIL() parce qu'elle concerne les adresses mail, mais
     * elle est liée au processus de sauvegarde classique des paramètres du compte
     */
    public function account_classic_save_email_check($accid, $email){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $email = $con->real_escape_string($email);
        //On commence par récupérer l'email d'authentification courant de l'utilisateur
        $QE = "SELECT * FROM email_history WHERE accid = '$accid' AND date_EndEna IS NULL;";
        $obj = $con->query($QE);
        if($obj == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);}
        $fetchEmailData = $obj->fetch_array(MYSQLI_ASSOC);
        $fEmail = $fetchEmailData['emailraw'];
        
        if($fEmail != $email){
            //Email modifié
            $now = new DateTime();
            $now_tstamp = $this->get_millitimestamp();
            //$now_tstamp = $now->getTimestamp();
            $now = $now->format('Y-m-d H:i:s');
            //On commence par 'clore' l'ancien email
            $QU1 = "UPDATE email_history SET date_EndEna = '$now', date_EndEna_tstamp = '$now_tstamp' WHERE accid = '$accid' AND date_EndEna IS NULL;";
            $oQu1 = $con->query($QU1);
            if($oQu1 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return FALSE;}
            //On annule aussi toutes les demandes de confirmation sur cet email
            $QU1b = "UPDATE accountkey SET canceldate = '$now', canceldate_tstamp = '$now_tstamp' WHERE email = '$fEmail' AND canceldate_tstamp IS NULL";
            $oQu1b = $con->query($QU1b);
            if($oQu1b == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return FALSE;}
            //On regarde ensuite si le nouvel email existe dans les archives
            $eia = $this->exists_in_archive($email);
            if($eia == FALSE){
                //On insert ce nouvel email dans les archives | origin = 3 car account
                $addInArchive = [
                    'emailraw' => $email,
                    'origin' => 3
                ];
                $this->on_create_entity($addInArchive);
            } //Sinon on continue directement, tout en mettant manuellement les dates de fin à NULL (si on remet un ancien email à nous par exemple)
            $QU2 = "INSERT INTO email_history (emailraw, accid, date_Enafrom, date_Enafrom_tstamp, date_EndEna, date_EndEna_tstamp) VALUES ('$email', '$accid', '$now', '$now_tstamp', NULL, NULL);";
            
            $oQu2 = $con->query($QU2);
            if($oQu2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);}
            
            //Si on arrive ici c'est que tout est OK et que les modifs se sont bien passées
            return 1;
        }
        //Si on arrive ici c'est que le mail n'a pas eu à être modifié
        return 2;
    }
    
    public function get_email_from_account($accid){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $query = "SELECT emailraw FROM email_history WHERE accid='$accid' AND date_EndEna IS NULL;";
        $ctrl = $con->query($query);
        $con->close();
        if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return 'QUERY_ERROR';}
        $arr = $ctrl->fetch_array(MYSQLI_ASSOC);
        return $arr['emailraw'];
    }
    
    
    /**
     * Taken from StackOverflow
     * http://stackoverflow.com/a/15875555
     * 
     * Will generate - theorytically - unique keys.
     * Used for Preregistration
     * 
     * @return string (36 chars)
     */
    public function guidv4()
    {
        $data = openssl_random_pseudo_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    public function assign_to_account($email, $accid){
        $now = new DateTime();
        $now_tstamp = $this->get_millitimestamp();
        $now = $now->format('Y-m-d H:i:s');
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $accid = $con->real_escape_string($accid);
        $qr = "INSERT INTO email_history (emailraw, accid, date_Enafrom, date_Enafrom_tstamp) VALUES ('$email', '$accid', '$now', '$now_tstamp');";
        $result = $con->query($qr);
        $con->close();
        if($result == NULL){
            $this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);
            return -1;
        } else {
            return 1;
        }
    }
    
    
    
    /**
     * Fonction de génération des clés liées aux comptes pour la validation de ces derniers.
     * Les clés sont générées sur une base de GUIDv4, couplées à un encodage simple de l'adresse
     * email pour limiter le risque qu'un bot "devine" la clé d'un utilisateur avant même que
     * celui-ci ait pu avoir le temps de l'utiliser.
     * 
     * On a besion de l'email en entrée afin d'avoir le string à encoder et également pour
     * récupérer l'ID du compte
     * 
     * @return string Clé complète (80char)
     */
    public function create_accountkey($email){
        
        
        //Préparation de l'insertion en base
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $now_tstamp = $this->get_millitimestamp();
        $expdate = new DateTime();
        $expdate = $expdate->modify('+1 month');
        $expdate_tstamp = strtotime($expdate->format('Y-m-d H:i:s')) * 1000;
        $expdate = $expdate->format('Y-m-d H:i:s');
        
        //Note: On part du principe que la clé est envoyée via une autre fonction, donc au moment de la création, $sentdate est null.
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        
        //Génération de la clé
        $key = $this->guidv4();
        $md5mail = md5($email);
        //Fixe, et toujours présent pour 'adoucir' la concaténation entre un GUIDv4 et le MD5,
        //de façon à ce que ce soit moins lisible par un éventuel intrus dans la base.
        $delimiter = '-756ea474fe-';
        $fullKey = $key . $delimiter . $md5mail;
        //($fullKey);
        // ---------- //
        
        //On vérifie que la clé n'existe pas déjà en base
        $vq = "SELECT * FROM accountkey WHERE acc_key='$fullKey';";
        $rslt = $con->query($vq);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
        $fetch = $rslt->fetch_array(MYSQLI_ASSOC);
        if($fetch == NULL){
            //All good
            $rq = "INSERT INTO accountkey (acc_key, datecrea, datecrea_tstamp, expdate, expdate_tstamp, email)
                   VALUES ('$fullKey', '$now', '$now_tstamp', '$expdate', '$expdate_tstamp', '$email');";
            $ctrl = $con->query($rq);
            $con->close();
            if($ctrl == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__);
                return FALSE;
            } else {
                return $fullKey;
            }
        } else {
            $this->get_or_signal_error(1, 'err_sys_l4accn2', __FUNCTION__, __LINE__);
            return FALSE;
        }
    }
    
    /**
     * Fonction qui va préparer le call à send_email(); avec toutes les informations nécessaires
     * à la confirmation de l'adresse mail.
     * 
     * Si le $request_type est 'creation', le mail automatique contenant la clé sera envoyé lors de la création du compte
     * Si le $request_type est 'request' (aka demandé par l'utilisateur), le mail sera envoyé avec un corps différent, mais surtout
     * la fonction passera les précédentes demandes en 'expiré' ou les supprimera, à voir.
     * 
     * @param string $recipient Adresse mail du destinataire
     * @param string $request_type Type d'email de confirmation ('creation' | 'request')
     */
    public function email_confirmation_request($recipient, $request_type){
        //$this->send_email($email_sender, $email_recipient, $subject, $email_body)
        $email_sender = 'noreply@trenqr.com';
        $subject = 'Email - Confirmation';
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $recipient = $con->real_escape_string($recipient);
        
        $acc_key = $this->get_current_acckey($recipient);
        //var_dump($acc_key);
        
        //Construction du lien complet à envoyer
        
        switch($request_type){
            case 'creation':
                $emailLink = "http://www.trenqr.com/forrest/index.php?page=confirm&urqid=email_confirm&ups=k=" . $acc_key;
                //À retravailler
                $email_body = "Pour confirmer votre email, veuillez <a href='" . $emailLink . "'>cliquer ici</a>.";
                $email_sender = "noreply@trenqr.com";
                $subject = "Validation de compte";
                $reply_to ="noreply@trenqr.com";
                //TODO: ENVOYER LE MAIL
                //Appel à email_send() ici, à tester sur le live
                $sent = $this->send_email($email_sender, $recipient, $subject, $email_body, $reply_to);
                
                if($sent == FALSE){
                    //Erreur au niveau de l'email
                    return FALSE;
                }
                //L'email est considéré comme parti, on peut renseigner la 'sentdate' en base
                $now = new DateTime();
                $now = $now->format('Y-m-d H:i:s');
                $now_tstamp = $this->get_millitimestamp();
                $sentQuery = "UPDATE accountkey SET sentdate='$now', sentdate_tstamp='$now_tstamp' WHERE email='$recipient' AND acc_key='$acc_key' AND canceldate IS NULL;";
                $ctrl = $con->query($sentQuery);
                $con->close();
                if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return FALSE;}
                //Bonne exécution
                return TRUE;
                break;
            
            case 'request':
                //On commence par 'cancel' l'ancienne clé
                $now = new DateTime();
                $now = $now->format('Y-m-d H:i:s');
                $now_tstamp = $this->get_millitimestamp();
                $cancelQuery = "UPDATE accountkey SET canceldate='$now', canceldate_tstamp='$now_tstamp' WHERE email='$recipient' AND acc_key='$acc_key' AND dateofuse IS NULL;";
                $ctrl = $con->query($cancelQuery);
                if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
                
                //On en regénère une nouvelle
                $acc_key = $this->create_accountkey($recipient);
                $emailLink = "http://www.trenqr.com/forrest/index.php?page=confirm&urqid=email_confirm&ups=k=" . $acc_key;
                
                //Et on envoie le tout:
                //À retravailler
                $email_body = "Pour confirmer votre email, veuillez <a href='" . $emailLink . "'>cliquer ici</a>.";
                $email_sender = "noreply@trenqr.com";
                $subject = "Validation de compte - Renvoi de demande";
                $reply_to ="noreply@trenqr.com";
                //TODO: ENVOYER LE MAIL
                //Appel à email_send() ici, à tester sur le live
                $this->send_email($email_sender, $recipient, $subject, $email_body, $reply_to);
                
                //L'email est considéré comme parti, on peut renseigner la 'sentdate' en base
                $now = new DateTime();
                $now = $now->format('Y-m-d H:i:s');
                $now_tstamp = $this->get_millitimestamp();
                $sentQuery = "UPDATE accountkey SET sentdate='$now', sentdate_tstamp='$now_tstamp' WHERE email='$recipient' AND acc_key='$acc_key' AND canceldate IS NULL;";
                $ctrl = $con->query($sentQuery);
                $con->close();
                if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return FALSE;}
                //Bonne exécution
                return TRUE;
                break;
                
            default:
                //Erreur - Ne devrait pas arriver
                $con->close();
                return FALSE;
        }
    }
    
    public function get_current_acckey($email){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $keyQuery = "SELECT acc_key FROM accountkey WHERE email = '$email' AND canceldate_tstamp IS NULL;";
        $rslt = $con->query($keyQuery);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return FALSE;}
        $fullkey = $rslt->fetch_array(MYSQLI_ASSOC);
        $acc_key = $fullkey['acc_key'];
        $con->close();
        if($fullkey == NULL){$this->get_or_signal_error(1, 'err_user_l4eman1', __FUNCTION__, __LINE__); return FALSE;}
        return $acc_key;
    }
    
    public function email_confirmation_cancel_key($email){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $email = $con->real_escape_string($email);
        $acc_key = $this->get_current_acckey($email);
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $now_tstamp = $this->get_millitimestamp();
        $cancelQuery = "UPDATE accountkey SET canceldate='$now', canceldate_tstamp='$now_tstamp' WHERE email='$email' AND acc_key='$acc_key';";
        $ctrl = $con->query($cancelQuery);
        if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
        //Déroulement OK
        return TRUE;
    }
    
    /**
     * On regarde si l'email indiqué a été confirmé (la clé de vérification utilisée).
     * Si oui, return <b>TRUE</b>.
     * Si non, return <b>FALSE</b>.
     * En cas d'erreur, return <b>-1</b>.
     * 
     * @param type $email
     * @return boolean
     */
    //public function email_confirmation_verification($email){
    public function email_confirmation_verification($accid){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        //$qry = "SELECT * FROM accountkey WHERE email = '$email' AND dateofuse_tstamp IS NOT NULL AND canceldate_tstamp IS NULL;";
        $qry = "SELECT acc_confirmed FROM accounts WHERE accid = '$accid' AND acc_confirmed = '1';";
        $rslt = $con->query($qry);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); return -1;}
        $data = $rslt->fetch_array(MYSQLI_ASSOC);
        
        if($data){
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public function email_confirmation_action($key){
        $now = new DateTime();
        $fnow = $now->format('Y-m-d H:i:s');
        $now_tstamp = $this->get_millitimestamp();
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        //On commence par récupérer les infos correspondant à la clé (non expirée)
        $checkQuery = "SELECT * FROM accountkey WHERE acc_key = '$key' AND expdate_tstamp > $now_tstamp AND canceldate_tstamp IS NULL;";
        $rslt = $con->query($checkQuery);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
        $data = $rslt->fetch_array(MYSQLI_ASSOC);
        if(!$data){
            //Aucune corrélation, ou la clé a expiré ou n'existe pas
            $con->close();
            return 'EMACONF_DNE_OR_EXPIRED';
        } else {
            if($data['dateofuse'] != NULL){
                //Ce compte a déjà été validé
                $con->close();
                return 'EMACONF_ALREADYCONF';
            } else {
                $validQuery = "UPDATE accountkey SET dateofuse = '$fnow', dateofuse_tstamp = $now_tstamp WHERE acc_key = '$key' AND expdate_tstamp > $now_tstamp AND canceldate_tstamp IS NULL;";
                $ctrl = $con->query($validQuery);
                if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
                
                //On va récupérer l'accid correspondant à ce mail à ce moment là
                $emailraw = $data['email'];
                $accQuery = "SELECT accid FROM email_history WHERE emailraw = '$emailraw' AND date_EndEna_tstamp IS NULL;";
                $chk = $con->query($accQuery);
                if($chk == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
                $accArray = $chk->fetch_array();
                $accid = $accArray["accid"];
                //On va modifier la table account
                $accTableQuery = "UPDATE accounts SET acc_confirmed = '1' WHERE accid = '$accid';";
                $chk2 = $con->query($accTableQuery);
                if($chk2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4eman1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
                
                
                //Tout s'est bien passé
                return TRUE;
            }
        }
    }
    
    public function email_validation($email){
        if(isset($email) && $email != ""){
            if(preg_match($this->reg, $email)){
                $email = htmlentities($email);
                return $email;
            } else {
                return NULL;
            }
        }
        return NULL;
    }


    /*************************************************************************************************************/
    /******************************************** GETTERS AND SETTERS ********************************************/
    // <editor-fold defaultstate="collapsed" desc="Getters and Setters">
    public function getReg() {
        return $this->reg;
    }

    public function setReg($reg) {
        $this->reg = $reg;
    }

    public function getMax_size() {
        return $this->max_size;
    }

    public function setMax_size($max_size) {
        $this->max_size = $max_size;
    }

    public function getUse_default() {
        return $this->use_default;
    }

    public function setUse_default($use_default) {
        $this->use_default = $use_default;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        $this->valid_email();
    }

    public function getIs_authentic() {
        return $this->is_authentic;
    }

    public function setIs_authentic($is_authentic) {
        $this->is_authentic = $is_authentic;
    }

    public function getEmaildomain() {
        return $this->emaildomain;
    }

    public function setEmaildomain($emaildomain) {
        $this->emaildomain = $emaildomain;
    }

    public function getEmaillogin() {
        return $this->emaillogin;
    }

    public function setEmaillogin($emaillogin) {
        $this->emaillogin = $emaillogin;
    }

    public function getCreadate() {
        return $this->creadate;
    }

    public function setCreadate($creadate) {
        $this->creadate = $creadate;
    }
    
    public function getEmailorigin() {
        return $this->emailorigin;
    }

    public function setEmailorigin($emailorigin) {
        $this->emailorigin = $emailorigin;
    }



// </editor-fold>

    
}

?>