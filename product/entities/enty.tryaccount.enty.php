 <?php

/**
 * Description de l'Entity Prereg
 */
class TRYACCOUNT extends PROD_ENTITY {
    
    private $regexNumbers;
    private $regexKeys;
    private $regexMagicDates;
    private $regexTstamps;
    
    private $taccid;
    private $accid;
    private $tak_key;
    private $datecrea;
    private $datecrea_tstamp;
    private $dateexp;
    private $dateexp_tstamp;
    private $date_of_mutation;
    private $date_of_mutation_tstamp;
    
	
       
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->regexNumbers = '/^\d*$/';
        //Temporaire car format des clés non fixé encore
        $this->regexKeys = '^[0-9a-f]{64}$';
        //La regex puissante étant copiée de Javascript, elle plante en PHP
        $this->regexMagicDates = "/^[0-9]{2}[-][0-9]{2}[-][0-9]{4}$/";        
        //$this->regexMagicDates = '/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
        $this->regexTstamps = '/^\d{10,}$/';
        
        $this->is_instance_loaded = false;
    }
    
    /*******************************************************************************************************/
    /********************************************* PROCESS ZONE ********************************************/
    public function build_volatile ($args) {
//        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
//        $datecrea = ( !isset($datecrea) ) ? new DateTime : $datecrea;
//        if(!isset($dateexp)){
//            $temp = new DateTime();
//            //Date de 3 semaine totalement arbitraire
//            $dateexp = $temp->modify('+3 week');
//        }
//        
//        $datas = $this->get_std_datas_format($accid, $tak_key, $datecrea->format('Y-m-d H:i:s'), $dateexp->format('Y-m-d H:i:s'));
//        
//        $foo = $this->valid_tryaccount_instance($datas);
//        
//        if(!count($foo)){
//            return $foo;
//        } else {
//            $this->init_properties($datas);
//        }
        /*$this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $vStore = array();
        foreach ($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        
        $datecrea = new DateTime();
        $vStore['datecrea'] = $datecrea;
        $vStore['datecrea_tstamp'] = $this->get_millitimestamp();
        if(!isset($dateexp)){
            $tmp = new DateTime();
            $dateexp = $tmp->modify('+1 month');
        }
        $vStore['dateexp'] = $dateexp;
        $vStore['dateexp_tstamp'] = strtotime($dateexp->getTimestamp()) * 1000;
        $date_of_mutation = ( !isset($date_of_mutation) ) ? NULL : $date_of_mutation;
        $vStore['date_of_mutation'] = $date_of_mutation;
        $vStore['date_of_mutation_tstamp'] = strtotime($date_of_mutation->getTimestamp()) * 1000;
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_tryaccount_instance($datas);
        if(count($foo)){
            $this->get_or_signal_error(1, 'custom_err_buildvolatile_tryaccount', __FUNCTION__, __LINE__);
            return $foo;
        } else {
            $this->init_properties($datas);
        }
        */
    }

    /************************* MAJORITAIRE */
    public function load_entity ($args) {
        
        foreach($args as $k => $v){
            $$k = $v;
        }
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $taccid);
        
        //$QO = new QUERY("????");
        //$params = array(':prereg_id' => $prereg_id);
        //$datas = $QO->execute(/*$params*/);
        
//        $QO = 'SELECT * FROM kx_account_vbeta.tryaccounts WHERE taccid = '. $taccid .';';
//        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//        $rslt = $con->query($QO);
//        $datas = $rslt->fetch_array(MYSQLI_ASSOC);
        
        $QO = new QUERY("qryl4tryaccn1");
        $params = array( ":accid" => $taccid );
        $datas = $QO->execute($params);
        
        if(!count($datas)){
//            if($std_err_enabled){$this->signalError("/*CODE ERREUR*/", __FUNCTION__, __LINE__);}
//            else{return 0;}
            return 0;
        } else {
            $this->init_properties($datas[0]);
            $this->is_instance_loaded = true;
        }
        
    }
    
    protected function init_properties($datas) {
        /*
        $this->all_properties["taccid"] = $this->taccid = trim($datas["taccid"]);
        $this->all_properties["accid"] = $this->accid = trim($datas["accid"]);
        $this->all_properties["tak_key"] = $this->tak_key = trim($datas["tak_key"]);
        $this->all_properties["datecrea"] = $this->datecrea = $datas["datecrea"];
        $this->all_properties["datecrea_tstamp"] = $this->datecrea_tstamp = trim($datas["datecrea"]);
        $this->all_properties["dateexp"] = $this->dateexp = trim($datas["dateexp"]);
        $this->all_properties["dateexp_tstamp"] = $this->dateexp_tstamp = trim($datas["dateexp_tstamp"]);
        $this->all_properties["date_of_mutation"] = $this->date_of_mutation = trim($datas["date_of_mutation"]);
        $this->all_properties["date_of_mutation_tstamp"] = $this->date_of_mutation_tstamp = ["date_of_mutation"];
        */
        
        foreach($datas as $k => $v){
            $$k = $v;
            if($v instanceof DateTime){
                $this->all_properties[$k] = $this->$k = $datas[$k];
            } else {
                $this->all_properties[$k] = $this->$k = trim($datas[$k]);
            }
        }
    }
    

    /*public function on_create_entity($accid, $tak_key, DateTime $datecrea, DateTime $dateexp) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datecrea = (!isset($datecrea)) ? new DateTime() : $datecrea;
        $datecrea_tstamp = $datecrea->getTimestamp();
        $dateexp = new DateTime();
        $dateexp->modify('+1 month');
        $dateexp_tstamp = $dateexp->getTimestamp();
        
        $datas = $this->get_std_datas_format($accid, $tak_key, $datecrea->format('Y-m-d H:i:s'), $datecrea_tstamp, $dateexp->format('Y-m-d H:i:s'), $dateexp_tstamp, '', '');

        $foo = $this->valid_tryaccount_instance($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->init_properties($datas);
            $this->write_new_in_database();
        }
    }*/
    public function on_create_entity($args){
        //Tableau de stockage des variables du scope
        $vStorage = [];
        
        foreach($args as $k => $v){
            $$k = $v;
            $vStorage[$k] = $v;
        }
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        /*
         * 16/07/14 | Note personnelle :
         * Arguments possibles:
         * taccid                   (PK, autoincrement)
         * accid                    (FK, int)
         * datecrea                 (DateTime)
         * datecrea_tstamp          (Bigint)
         * dateexp                  (DateTime)
         * dateexp_tstamp           (Bigint)
         * date_of_mutation         (DateTime)
         * date_of_mutation_tstamp  (Bigint)
         * 
         * EDIT: 11/08/14 | Viré tak_key. N'est plus du tout dans cette table.
         */
        
        $datecrea = new DateTime();
        $vStorage['datecrea'] = $datecrea;
        $vStorage['datecrea_tstamp'] = $this->get_millitimestamp();
        //$vStorage['datecrea_tstamp'] = $datecrea->getTimestamp();
        
        $dateexp = new DateTime();
        //Expiration au bout d'un mois arbitraire
        $dateexp = $dateexp->modify('+1 month');
        $vStorage['dateexp'] = $dateexp;
        $vStorage['dateexp_tstamp'] = strtotime($dateexp->format('Y-m-d H:i:s')) * 1000;
        
        $datas = $this->get_std_datas_format($vStorage);
        $foo = $this->valid_tryaccount_instance($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->init_properties($datas);
            $this->write_new_in_database($datas, true);
        }
        
    }

    public function on_alter_entity($args) {
        /* Si ALTER il y a, ce ne peut être que pour la mutation du compte */
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On set manuellement la date de mutation à la date actuelle
        $date_of_mutation = new DateTime();
        $date_of_mutation_tstamp = $this->get_millitimestamp();
        //$date_of_mutation_tstamp = $date_of_mutation->getTimestamp();
        $vStore['date_of_mutation'] = $date_of_mutation;
        $vStore['date_of_mutation_tstamp'] = $date_of_mutation_tstamp;
        
        if(!isset($taccid)){
            $this->get_or_signal_error(1, 'custom_err_missing_id_for_alter_tryaccount', __FUNCTION__, __LINE__);
            return;
        }
        
        $this->load_entity(['taccid' => $taccid]);
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_prereg_instance($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->write_new_in_database($datas, null);
            $this->load_entity(['taccid' => $taccid]);
        }
    }
    
    public function on_delete_entity($args) {
        /* Un tryaccount ne se supprime pas, il mute en compte normal.
         * Voir on_alter_entity */
    }
    
    protected function on_read_entity($args) {
        /* ??? */
    }
    
    /*public function exists($taccid, bool $std_err_enabled = null) {
        if(!(isset($taccid) AND $taccid !='') OR !(isset($this->taccid) AND $this->taccid !='')){$this->signalEror('err_user_100', __FUNCTION__, __LINE__);}
        else if((!isset($taccid) OR $taccid =='') AND (isset($this->taccid) AND $this->taccid !='')){$taccid = $this->taccid;}
        
        $code = ($std_err_enabled) ? 2 : 1;
        
        $QO = new QUERY('???');
        $qparams_val = array(':taccid' => $taccid);
        
        $d = $QO->execute($qparams_val);
        if(!count($d)){$this->get_or_signal_error($code, 'err_sys_14preregn4', __FUNCTION__, __LINE__);}
    }*/
    public function exists($args) {
        foreach ($args as $k => $v){
            $$k = $v;
        }
        
        if(!(isset($taccid) AND $taccid !='') OR !(isset($this->taccid) AND $this->taccid !='')){$this->signalEror('err_user_100', __FUNCTION__, __LINE__);}
        else if((!isset($taccid) OR $taccid =='') AND (isset($this->taccid) AND $this->taccid !='')){$taccid = $this->taccid;}
        
//        $QO = "SELECT taccid FROM tryaccounts WHERE taccid = '$taccid'";
//        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//        $rslt = $con->query($QO);
//        if($rslt == false){$this->get_or_signal_error(1, 'custom_err_exist_query', __FUNCTION__, __LINE__);}
//        $count = $rslt->fetch_array(MYSQLI_ASSOC);
        
        $QO = new QUERY("qryl4tryaccn1");
        $params = array( ":accid" => $taccid );
        $datas = $QO->execute($params);
        
//        if(count($count) != 0){
//            return true; //existe
//        } else {
//            return false; //n'existe pas
//        }
        
        if( count($datas) ){
            return true; //existe
        } else {
            return false; //n'existe pas
        }
    }
    
    protected function write_new_in_database($args, $new_row = NULL) {
        //$q = '';

        //[16/07/14] - Pour ne pas changer le reste de la fonction on réalloue la variable
        //$new_row = $args;
        
        //Initialisation des variables
        $accid = $tak_key = $datecrea = $datecrea_tstamp = null;
        $dateexp = $dateexp_tstamp = $date_of_mutation = $date_of_mutation_tstamp = null;

        //Load des variables
        foreach($args as $k => $v){
            $$k = $v;
        }
        
        //Gestion des datetime
        $datecrea_tstamp = $this->get_millitimestamp();
        $dateexp_tstamp = (!isset($dateexp)) ? null : strtotime($dateexp->format('Y-m-d H:i:s')) * 1000;
        $date_of_mutation_tstamp = (!isset($date_of_mutation)) ? null : strtotime($date_of_mutation->format('Y-m-d H:i:s')) * 1000;
        
        $datecrea = new DateTime();
        $datecrea = $datecrea->format('Y-m-d H:i:s');
        $dateexp = (!isset($dateexp)) ? null : $dateexp->format('Y-m-d H:i:s');
        $date_of_mutation = (!isset($date_of_mutation)) ? null : $date_of_mutation->format('Y-m-d H:i:s');
        
        var_dump($accid);
        
        if($new_row){
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $qr = "INSERT INTO kx_account_vbeta.tryaccounts (accid, datecrea, datecrea_tstamp, dateexp, dateexp_tstamp, date_of_mutation, date_of_mutation_tstamp)
                   VALUES ('$accid', '$datecrea', '$datecrea_tstamp', '$dateexp', '$dateexp_tstamp', '$date_of_mutation', '$date_of_mutation_tstamp');";
            $ctrl = $con->query($qr);
            echo $con->error;
            if($ctrl == false){$this->get_or_signal_error(1, 'custom_err_writetryaccount', __FUNCTION__, __LINE__);}
            //INSERT INTO
            /*$q = 'qry14tryaccountn3';
            $qparams_in_values = array(
                ':accid' => $this->accid,
                ':tak_key' => $this->tak_key,
                ':datecrea' => $this->datecrea,
                ':datecrea_tstamp' => $this->datecrea_tstamp,
                ':dateexp' => $this->dateexp,
                ':dateexp_tstamp' => $this->dateexp_tstamp
                );*/
        } else {
            /*$q = 'qry14tryaccountn4';
            $qparams_in_values = array(
                ':taccid' => $this->taccid,
                ':accid' => $this->accid,
                ':tak_key' => $this->tak_key,
                ':datecrea' => $this->datecrea,
                ':datecrea_tstamp' => $this->datecrea_tstamp,
                ':dateexp' => $this->dateexp,
                ':dateexp_tstamp' => $this->dateexp_tstamp,
                ':date_of_mutation' => $this->date_of_mutation,
                ':date_of_mutation_tstamp' => $this->date_of_mutation_tstamp
                );*/
            // ************ TEST DE RECUP AUTOMATIQUE *************** //
            //On regarde ce qu'il y a déjà en base à l'ID donné
            //ID qui doit être passé en paramètre sinon erreur
            //Au passage, la date de modification est supposée être passée dans $args, elle n'est pas regénérée ici
            if(!isset($taccid)){
                $this->get_or_signal_error(1, 'custom_err_no_id_for_tryaccount_update', __FUNCTION__, __LINE__);
                return;
            }
//            $QR = "SELECT * FROM tryaccounts WHERE taccid = '$taccid';";
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $fetch = $con->query($QR);
//            $fArray = $fetch->fetch_array(MYSQLI_ASSOC);
            
            $QO = new QUERY("qryl4tryaccn1");
            $params = array( ":accid" => $taccid );
            $fArray = $QO->execute($params);
            
            //On boucle dans ce tableau pour chaque donnée entrée
            foreach($fArray as $ka => $va){
                //On compare les données entrées aux données récupérées
                //Si différence, on remplace par la donnée entrée
                foreach($datas as $kd => $vd){
                    if($ka == $kd){
                        $va = $vd;
                    }
                }
            }
            //On reset les fonctions
            foreach($fArray as $k => $v){
                $$k = $v;
            }
            
            //On fait l'update
            $QP = "UPDATE tryaccounts SET accid = '$accid', datecrea = '$datecrea', datecrea_tstamp = '$datecrea_tstamp', dateexp = '$dateexp', dateexp_tstamp = '$dateexp_tstamp', date_of_mutation = '$date_of_mutation', date_of_mutation_tstamp = '$date_of_mutation_tstamp'
                   WHERE taccid = '$taccid'";
            $ctrl = $con->query($QP);
            $con->close();
            if($ctrl == false){$this->get_or_signal_error(1, 'custom_err_update_tryaccount', __FUNCTION__, __LINE__);}
            
            
        }
    }
    
    
    /********************************************************************/
    
    public function get_millitimestamp(){
        return round(microtime(TRUE)*1000);
    }
    
    /*private function get_std_datas_format($taccid, $accid, $tak_key, DateTime $datecrea, $datecrea_tstamp, DateTime $dateexp, $dateexp_tstamp, DateTime $date_of_mutation, $date_of_mutation_tstamp){
        $datas = array();
        
        $datas['taccid'] = trim($taccid);
        $datas['accid'] = trim($accid);
        $datas['tak_key'] = trim($tak_key);
        $datas['datecrea'] = trim($datecrea);
        $datas['datecrea_tstamp'] = trim($datecrea_tstamp);
        $datas['dateexp'] = trim($dateexp);
        $datas['dateexp_tstamp'] = trim($dateexp_tstamp);
        $datas['date_of_mutation'] = trim($date_of_mutation);
        $datas['date_of_mutation_tstamp'] = trim($date_of_mutation_tstamp);
        
        return $datas;
    }*/
    protected function get_std_datas_format($args){
        $datas = array();
        foreach($args as $k => $v){
            $$k = $v;
            if($v instanceof DateTime){
                $datas[$k] = $v;
            } else {
                $datas[$k] = trim($v);
            }
        }
        return $datas;
    }
    
//    protected function tryaccountKeyGenerator(){
//        $genKey = bin2hex(openssl_random_pseudo_bytes(32, $crypto_strong));
//        if(!$crypto_strong){
//            exit('Crypto too weak');
//        } else {
//            return $genKey;
//        }
//    }
//    
//    public function create_tryaccountkey($email){
//        $key = $this->tryaccountKeyGenerator();
//        $sentdate = $sentdate_tstamp = null;
//        $datecrea = $expdate = new DateTime();
//        $datecrea_tstamp = $datecrea->getTimestamp();
//        $expdate = $expdate->modify('+1 month');
//        $expdate_tstamp = $expdate->getTimestamp();
//        
//        $datecrea = $datecrea->format('Y-m-d H:i:s');
//        $expdate = $expdate->format('Y-m-d H:i:s');
//        
//        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//        $rq = "INSERT INTO tryaccountkey (tak_key, datecrea, datecrea_tstamp, sentdate, sentdate_tstamp, expdate, expdate_tstamp, email)
//               VALUES ('$key', '$datecrea', '$datecrea_tstamp', '$sentdate', '$sentdate_tstamp', '$expdate', '$expdate_tstamp', '$email');";
//        $ctrl = $con->query($rq);
//        if($ctrl == FALSE){
//            echo 'Erreur création tryaccountkey';
//            return false;
//        } else {
//            return $key;
//        }
//        
//    }
    
    
    /* Attention, on ne peut pas balancer d'éléments '' ou null dans le tableau à cause des regex.
     * Et aussi que si on traite les cas '' ici on risque d'insert / overwrite des champs avec des '' */
    public function valid_tryaccount_instance($array){
        $err_tab = array();
        foreach ($array as $k => $v){
            switch($k){
                case 'accid':
                    if(!(preg_match($this->regexNumbers, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
//                case 'tak_key':
//                    if(!(preg_match($this->regexKeys, $v))){
//                        $err_tab[$k] = [
//                            'v' => $v,
//                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
//                        ];
//                    }
//                    break;
                case 'datecrea':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'datecrea_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'dateexp':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'dateexp_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'date_of_mutation':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'date_of_mutation_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('message_erreur_quelconque', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                default:
                    break;
            }
        }
        return $err_tab;
    }
    
    public function tryaccountDetection($accid){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $qr = "SELECT taccid FROM tryaccounts WHERE accid = '$accid' AND date_of_mutation_tstamp = '0';";
        $ctrl = $con->query($qr);
        if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4tacn1', __FUNCTION__, __LINE__); return NULL;}
        $tac = $ctrl->fetch_array(MYSQLI_ASSOC);
        $con->close();
        return $tac['taccid'];
    }
    
    public function tryaccountConversion($accid){
        $now = new DateTime();
        $fnow = $now->format('Y-m-d H:i:s');
        $now_tstamp = $this->get_millitimestamp();
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $qr = "UPDATE tryaccounts SET date_of_mutation='$fnow', date_of_mutation_tstamp='$now_tstamp' WHERE accid='$accid';";
        $ctrl = $con->query($qr);
        $con->close();
        if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4tacn1', __FUNCTION__, __LINE__); return FALSE;}
    }
    
    
    // <editor-fold defaultstate="collapsed" desc="Getters / Setters">
    public function getTaccid() {
        return $this->taccid;
    }

    public function getAccid() {
        return $this->accid;
    }

    public function getTak_key() {
        return $this->tak_key;
    }

    public function getDatecrea() {
        return $this->datecrea;
    }

    public function getDatecrea_tstamp() {
        return $this->datecrea_tstamp;
    }

    public function getDateexp() {
        return $this->dateexp;
    }

    public function getDateexp_tstamp() {
        return $this->dateexp_tstamp;
    }

    public function getDate_of_mutation() {
        return $this->date_of_mutation;
    }

    public function getDate_of_mutation_tstamp() {
        return $this->date_of_mutation_tstamp;
    }

    public function setTaccid($taccid) {
        $this->taccid = $taccid;
    }

    public function setAccid($accid) {
        $this->accid = $accid;
    }

    public function setTak_key($tak_key) {
        $this->tak_key = $tak_key;
    }

    public function setDatecrea($datecrea) {
        $this->datecrea = $datecrea;
    }

    public function setDatecrea_tstamp($datecrea_tstamp) {
        $this->datecrea_tstamp = $datecrea_tstamp;
    }

    public function setDateexp($dateexp) {
        $this->dateexp = $dateexp;
    }

    public function setDateexp_tstamp($dateexp_tstamp) {
        $this->dateexp_tstamp = $dateexp_tstamp;
    }

    public function setDate_of_mutation($date_of_mutation) {
        $this->date_of_mutation = $date_of_mutation;
    }

    public function setDate_of_mutation_tstamp($date_of_mutation_tstamp) {
        $this->date_of_mutation_tstamp = $date_of_mutation_tstamp;
    }


// </editor-fold>

    
}