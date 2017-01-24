 <?php


/**
 * Description de l'Entity Prereg
 */
class PREREG extends PROD_ENTITY {
    
    private $regexFullname;
    private $regexNickname;
    private $regexPasswdMini;
    private $regexCity;
    private $regexNoCity;
    private $regexMail;
    private $regexMagicDates;
    private $regexTstamps;

    private $prereg_id;
    private $fullname;
    private $pseudo;
    private $email;
    private $passwd;
    private $birthday;
    private $birthday_tstamp;
    private $city;
    private $gender;
    private $datecrea;
    private $datecrea_tstamp;
    private $delay_date;
    private $delay_date_tstamp;
    private $restart_date;
    private $restart_date_tstamp;
    private $prg_date_close;
    private $prg_date_close_tstamp;
    private $prg_date_exp;
    private $prg_date_exp_tstamp;
    private $prg_exp_warning;
    
	
       
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->regexFullname = '/^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/';
        $this->regexNickname = '/^[a-zA-Z0-9-_ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/';
        $this->regexPasswdMini = '/^[^<=>\\;\/]{4,20}$/';
        $this->regexCity = '/^[A-Z]{1}[a-zA-Z-, ]{0,50}$/';
        $this->regexNoCity = '/^[^:;<=>\\\/]+$/';
        $this->regexMail = '/^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})*@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})*$/';
        //La regex puissante étant copiée de Javascript, elle plante en PHP
        $this->regexMagicDates = "/^[0-9]{2}[-][0-9]{2}[-][0-9]{4}$/";        
        //$this->regexMagicDates = '/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
        $this->regexTstamps = '/^\d{10,}$/';
        
        $this->is_instance_loaded = false;
    }
    
    /*******************************************************************************************************/
    /********************************************* PROCESS ZONE ********************************************/
    public function build_volatile ($args) {
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $vStore = array();
        foreach ($args as $k => $v) {
           $$k = $v;
           $vStore[$k] = $v;
        }
        /*
        $fullname = $args["fullname"];
        $pseudo = $args["pseudo"];
        $email = $args["email"];
        $passwd = $args["passwd"];
        $birthday = is_a($args["birthday"],"DateTime");
        $city = $args["city"];
        $gender = $args["gender"];
        $datecrea = is_a($args["datecrea"], "DateTime");
        $delay_date = is_a($args["delay_date"], "DateTime");
        $restart_date = is_a($args["restart_date"], "DateTime");
        //*/
        
        $datecrea = ( isset($datecrea) && is_a($args["datecrea"], "DateTime") ) ? : new DateTime ;
        $delay_date= ( isset($delay_date) && is_a($args["delay_date"], "DateTime") ) ? $delay_date->format('Y-m-d H:i:s') : null;
        $restart_date = ( isset($restart_date) && is_a($args["restart_date"], "DateTime") ) ? $restart_date->format('Y-m-d H:i:s') : null;
        
        $datas = $this->get_std_datas_format($vStore);
        
        $foo = $this->valid_prereg_instance($datas);
        
        if(!count($foo)){
            return $foo;
        } else {
            $this->init_properties($datas);
        }
    }

    /************************* MAJORITAIRE */
    public function load_entity ($args) {
        foreach ($args as $k => $v) {
           $$k = $v;
        }
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args);
        //$QO = new QUERY("????");
        //$params = array(':prereg_id' => $prereg_id);
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $QO = 'SELECT * FROM preregistration WHERE prereg_id = '. $args['prereg_id'] .';';
        $rslt = $con->query($QO);
        $datas = $rslt->fetch_array();
        //$datas = $QO->execute(/*$params*/);
        
        if(!count($datas)){
            if($std_err_enabled){$this->signalError("err_sys_l4prgn1", __FUNCTION__, __LINE__);}
            else{return 0;}
        } else {
            $this->init_properties($datas);
            $this->is_instance_loaded = true;
        }
        
    }
    
    /*protected function init_properties($datas) {
        $this->all_properties["fullname"] = $this->fullname = trim($datas["fullname"]);
        $this->all_properties["pseudo"] = $this->pseudo = trim($datas["pseudo"]);
        $this->all_properties["email"] = $this->email = trim($datas["email"]);
        $this->all_properties["passwd"] = $this->passwd = $datas["passwd"];
        $this->all_properties["birthday"] = $this->birthday = trim($datas["birthday"]);
        $this->all_properties["birthday_tstamp"] = $this->birthday_tstamp = trim($datas["birthday_tstamp"]);
        $this->all_properties["city"] = $this->city = trim($datas["city"]);
        $this->all_properties["gender"] = $this->gender = trim($datas["gender"]);
        $this->all_properties["datecrea"] = $this->datecrea = ["datecrea"]; 
        $this->all_properties["datecrea_tstamp"] = $this->datecrea_tstamp = ["datecrea_tstamp"]; 
        $this->all_properties["delay_date"] = $this->delay_date = ["delay_date"]; 
        $this->all_properties["delay_date_tstamp"] = $this->delay_date_tstamp = ["delay_date_tstamp"]; 
        $this->all_properties["restart_date"] = $this->restart_date = ["restart_date"];
        $this->all_properties["restart_date_tstamp"] = $this->restart_date_tstamp = ["restart_date_tstamp"];
        $this->all_properties["prg_date_close"] = $this->prg_date_close = ["prg_date_close"];
        $this->all_properties["prg_date_close_tstamp"] = $this->prg_date_close_tstamp = ["prg_date_close_tstamp"];
        $this->all_properties["prg_date_exp"] = $this->prg_date_exp = ["prg_date_exp"];
        $this->all_properties["prg_date_exp_tstamp"] = $this->prg_date_exp_tstamp = ["prg_date_exp_tstamp"];
        $this->all_properties["prg_exp_warning"] = $this->prg_exp_warning = ["prg_exp_warning"];
        
    }*/
    
    /* P.L.: Version alternative dynamique(?) */
    protected function init_properties($datas){
        foreach ($datas as $k => $v){
            $$k = $v;
            if($v instanceof DateTime){
                $this->all_properties[$k] = $this->$k = $datas[$k];
            } else {
                $this->all_properties[$k] = $this->$k = trim($datas[$k]);
            }
        }
    }
    

    public function on_create_entity($args) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //Tableau qui va permettre de stocker les variables 'existantes' dans le scope
        $vStorage = [];
        
        foreach ($args as $k => $v) {
           $$k = $v;
           $vStorage[$k] = $v;
        }
        
        //Parmi les arguments passables en entrée, si certains ne sont pas définis, on peut les zapper.
        //Ils pourront être ajoutés lors d'un edit.
        
        $birthday = (!isset($birthday)) ? NULL : date_create_from_format('Y-m-d', $birthday);
        $vStorage['birthday'] = $birthday;
        $birthday_tstamp = (!isset($birthday)) ? NULL : strtotime($birthday->format('Y-m-d')) * 1000;
        //$birthday_tstamp = (!isset($birthday)) ? NULL : $birthday->getTimestamp();
        $datecrea = ( !isset($datecrea) ) ? new DateTime() : $datecrea;
        $vStorage['datecrea'] = $datecrea;
        $vStorage['datecrea_tstamp'] = $this->get_millitimestamp();
        //Durée de 1 mois arbitraire
        $prg_date_exp = ( !isset($prg_date_exp) ) ? new DateTime() : $prg_date_exp;
        $prg_date_exp->modify('+1 month');
        $vStorage['prg_date_exp'] = $prg_date_exp;
        $vStorage['prg_date_exp_tstamp'] = strtotime($prg_date_exp->format('Y-m-d')) * 1000;
        //Nombre d'avertissements?
        $vStorage['prg_exp_warning'] = 5;
        
        
        $datas = $this->get_std_datas_format($vStorage);
        //$datas = $this->get_std_datas_format($fullname, $pseudo, $email, $passwd, $birthday->format('Y-m-d H:i:s'), $birthday_tstamp, $city, $gender, $datecrea->format('Y-m-d H:i:s'), $datecrea_tstamp, '', '', '', '', '', '', $prg_date_exp->format('Y-m-s H:i:s'), $prg_date_exp_tstamp, $prg_exp_warning);
        
        $foo = $this->valid_prereg_instance($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->init_properties($datas);
            $this->write_new_in_database($datas, true);
        }
    }

    public function on_alter_entity($args) {
        $vStore = array();
        foreach ($args as $k => $v) {
           $$k = $v;
           $vStore[$k] = $v;
        }
        
        /* ??? */
        /* On traite restart_date et delay_date directement ici car ce sont ces variables qui jouent le rôle de datemod.
         * Si une modification est faite sur ces variables, c'est au moins que l'utilisateur est venu remodifier quelque chose pour ne pas finir son inscription une fois de plus,
         * d'où l'utilisation de delay et restart. */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $birthday = (!isset($birthday)) ? NULL : date_create_from_format('Y-m-d', $birthday);
        $vStore['birthday'] = $birthday;
        $birthday_tstamp = (!isset($birthday)) ? NULL : strtotime($birthday->format('Y-m-d')) * 1000;        
        
        if(!isset($delay_date)){
            $delay_date = new DateTime();
            $delay_date_tstamp = $this->get_millitimestamp();
            //$delay_date_tstamp = $delay_date->getTimestamp();
            $restart_date = null;
        } else if(isset($delay_date) && !isset($restart_date)){
            $restart_date = new DateTime();
            $restart_date_tstamp = $this->get_millitimestamp();
            //$restart_date_tstamp = $restart_date->getTimestamp();
        } else if(isset($delay_date) && isset($restart_date)){
            //Cas où on arrive à un 2e report
            $delay_date = new DateTime();
            $delay_date_tstamp = $this->get_millitimestamp();
            //$delay_date_tstamp = $delay_date->getTimestamp();
            $restart_date = null;
        }
        $args = ['prereg_id' => $prereg_id, 'std_err_enabled' => TRUE];
        $this->load_entity($args);
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_prereg_instance($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->write_new_in_database($datas);
            $args = ['prereg_id' => $prereg_id];
            $this->load_entity($args);
        }
    }
    
    public function on_delete_entity($args) {
        /* Avant de supprimer un compte prereg, on doit savoir s'il a été converti (date_close) ou s'il a expiré (date_exp) */
        //$QO = new QUERY("???");
        //$qparams_in_values = array(':prereg_id' => $this->prereg_id);
        if(!isset($args['prereg_id'])){
            $this->get_or_signal_error(1, 'err_sys_l4prgn2', __FUNCTION__, __LINE__);
        }
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $QO = 'SELECT prereg_id, prg_date_close_tstamp, prg_date_exp_tstamp FROM preregistration WHERE prereg_id = '. $args['prereg_id'] .';';
        $rslt = $con->query($QO);
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4prgn3', __FUNCTION__, __LINE__);
        }
        $datas = $rslt->fetch_array(MYSQLI_ASSOC);
        //$datas = $QO->execute(/*$qparams_in_values*/);
        
        $now = $this->get_millitimestamp();
        if(isset($datas['prg_date_close_tstamp']) && $datas['prg_date_close_tstamp'] < $now){
            //On peut supprimer
            //$QP = new QUERY('requete_de_suppr');
            //$qparams_in_values = array(':prereg_id' => $this->prereg_id);
            //TODO: DELETE
        } else if(isset($datas['prg_date_exp_tstamp']) && $datas['prg_date_exp_tstamp'] < $now){
            //On peut supprimer
            //$QP = new QUERY('requete_de_suppr');
            //$qparams_in_values = array(':prereg_id' => $this->prereg_id);
            //TODO: DELETE
        } else {
            //On ne supprime pas
            return $datas['prereg_id'];
        }
    }
    
    protected function on_read_entity($args) {
        /* ??? */
    }
    
    public function exists($args) {
        
        foreach ($args as $k => $v) {
           $$k = $v;
        }
        
        if(!(isset($prereg_id) AND $prereg_id !='') OR !(isset($this->prereg_id) AND $this->prereg_id !='')){$this->signalEror('err_user_100', __FUNCTION__, __LINE__);}
        else if((!isset($prereg_id) OR $prereg_id =='') AND (isset($this->prereg_id) AND $this->prereg_id !='')){$prereg_id = $this->prereg_id;}
        
//        $code = ($std_err_enabled) ? 2 : 1;
//        
//        $QO = new QUERY('???');
//        $qparams_val = array(':prereg_id' => $prereg_id);
//        
//        $d = $QO->execute($qparams_val);
//        if(!count($d)){$this->get_or_signal_error($code, 'err_sys_14preregn4', __FUNCTION__, __LINE__);}
        
        // ** Partie MySQLi ** //
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $QO = "SELECT prereg_id from preregistration WHERE prereg_id = '$prereg_id'";
        $rslt = $con->query($QO);
        $fa = $rslt->fetch_array();
        
        if(!count($fa)){
            return false;
        } else {
            return true;
        }
    }
    
    
    protected function write_new_in_database($args, $new_row = null) {
        //$q = '';
        
        //[11/07/14] - Pour ne pas changer le reste de la fonction on réalloue la variable
        //$new_row = $args;
        
        //Initialisation des variables
        $fullname = $pseudo = $email = $passwd = $birthday = $birthday_tstamp = null;
        $cityId = $gender = $datecrea = $datecrea_tstamp = $delay_date = null;
        $delay_date_tstamp = $restart_date = $restart_date_tstamp = $prg_date_close = null;
        $prg_date_close_tstamp = $prg_date_exp = $prg_date_exp_tstamp = null;
        $prg_exp_warning = 5;
        //Load des variables
        foreach ($args as $k => $v){
            $$k = $v;
        }

        //Gestion des DateTime
        $datecrea = new DateTime();
        //$datecrea_tstamp = $datecrea->getTimestamp();
        $datecrea_tstamp = $this->get_millitimestamp();
        $datecrea = $datecrea->format('Y-m-d H:i:s');
        $delay_date_tstamp = (!isset($delay_date)) ? null : strtotime($delay_date->format('Y-m-d H:i:s')) * 1000;
        $delay_date = (!isset($delay_date)) ? null : $delay_date->format('Y-m-d H:i:s');
        $restart_date_tstamp = (!isset($restart_date)) ? null : strtotime($restart_date->format('Y-m-d H:i:s')) * 1000;
        $restart_date = (!isset($restart_date)) ? null : $restart_date->format('Y-m-d H:i:s');
        $prg_date_close_tstamp = (!isset($prg_date_close)) ? null : strtotime($prg_date_close->format('Y-m-d H:i:s')) * 1000;
        $prg_date_close = (!isset($prg_date_close)) ? null : $prg_date_close->format('Y-m-d H:i:s');
        $prg_date_exp_tstamp = (!isset($prg_date_exp)) ? null : strtotime($prg_date_exp->format('Y-m-d H:i:s')) * 1000;
        $prg_date_exp = (!isset($prg_date_exp)) ? null : $prg_date_exp->format('Y-m-d H:i:s');
        $birthday_tstamp = (!isset($birthday)) ? null : strtotime($birthday->format('Y-m-d H:i:s')) * 1000;
        $birthday = (!isset($birthday)) ? null : $birthday->format('Y-m-d H:i:s');
        
        $key = $this->prereg_key_generator();
        
        
        if($new_row){
            $ACC = new ACCOUNT();
            $hashedPw = $ACC->hash_input_passwd($passwd);
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $qr = "INSERT INTO preregistration (prg_fullname, prg_pseudo, prg_email, prg_pwd, prg_born, prg_gender, prg_city, datecrea, datecrea_tstamp, delay_date, restart_date, prg_date_close, prg_date_exp, prg_exp_warning, prg_born_tstamp, delay_date_tstamp, restart_date_tstamp, prg_date_close_tstamp, prg_date_exp_tstamp, prg_key)
                  VALUES ('$fullname', '$pseudo', '$email', '$hashedPw', '$birthday', '$gender', '$cityId', '$datecrea', '$datecrea_tstamp', '$delay_date', '$restart_date', '$prg_date_close', '$prg_date_exp', '$prg_exp_warning', '$birthday_tstamp', '$delay_date_tstamp', '$restart_date_tstamp', '$prg_date_close_tstamp', '$prg_date_exp_tstamp', '$key');";
            $ctrl = $con->query($qr);
            $con->close();
            if($ctrl == false){$this->get_or_signal_error(1, 'err_user_l4prgn1', __FUNCTION__, __LINE__);}
        } else {
            if(!isset($prereg_id)){
                $this->get_or_signal_error(1, 'err_sys_l4prgn4', __FUNCTION__, __LINE__);
                return;
            } else {
                $ACC = new ACCOUNT();
                $qs = "SELECT * FROM preregistration WHERE prereg_id = '$prereg_id';";
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $ftc = $con->query($qs);
                $fas = $ftc->fetch_array(MYSQLI_ASSOC);
                
                //On regarde s'il s'agit d'un délai
                if(isset($args['delay_date']) && $fas['delay_date'] != $args['delay_date']){
                    $fas['delay_date'] = $args['delay_date'];
                    //$fas['delay_date_tstamp'] = $fas['delay_date']->getTimestamp();
                    $fas['delay_date_tstamp'] = strtotime($fas['delay_date']->format('Y-m-d H:i:s')) * 1000;
                    $fas['delay_date'] = $fas['delay_date']->format('Y-m-d H:i:s');
                }
                //On regarde s'il s'agit d'une reprise
                if(isset($args['restart_date']) && $fas['restart_date'] != $args['restart_date']){
                    $fas['restart_date'] = $args['restart_date'];
                    //$fas['restart_date_tstamp'] = $fas['restart_date']->getTimestamp();
                    $fas['restart_date_tstamp'] = strtotime($fas['restart_date']->format('Y-m-d H:i:s'));
                    $fas['restart_date'] = $fas['restart_date']->format('Y-m-d H:i:s');
                }
                //On regarde si le pw a changé                
                $pwCtrl = $ACC->compare_hashed_passwd($args['prg_pwd'], $fas['prg_pwd']);
                if(isset($args['prg_pwd']) && $pwCtrl == FALSE){
                    $fas['prg_pwd'] = $ACC->hash_input_passwd($args['prg_pwd']);
                }
                //On boucle pour les données restantes
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
                $qt = "UPDATE preregistration SET prg_fullname = '$fullname', prg_pseudo = '$pseudo', prg_email = '$email', prg_pwd = '$prg_pwd', prg_born = '$birthday', prg_gender = '$gender',
                       prg_city = '$cityId', datecrea = '$datecrea', datecrea_tstamp = '$datecrea_tstamp', delay_date = '$delay_date', restart_date = '$restart_date', prg_date_close = '$prg_date_close',
                       prg_date_exp = '$prg_date_exp', prg_exp_warning = '$prg_exp_warning', prg_born_tstamp = '$birthday_tstamp', delay_date_tstamp = '$delay_date_tstamp', restart_date_tstamp = '$restart_date_tstamp',
                       prg_date_close_tstamp = '$prg_date_close_tstamp', prg_date_exp_tstamp = '$prg_date_exp_tstamp'
                       WHERE prereg_id = '$prereg_id';";
                $ctrl = $con->query($qt);
                $con->close();
                if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_user_l4prgn2', __FUNCTION__, __LINE__);}
            }
        }
    }
    
    
    /****************************************************************************************************************/
    /********************************************* SPECIFIQUE A LA CLASSE *******************************************/
    
    public function get_millitimestamp(){
        return round(microtime(TRUE)*1000);
    }
    
    /*private function get_std_datas_format($fullname, $pseudo, $email, $passwd, DateTime $birthday, $birthday_tstamp, LOCATION $city, $gender, DateTime $datecrea, $datecrea_tstamp, DateTime $delay_date, $delay_date_tstamp, DateTime $restart_date, $restart_date_tstamp, DateTime $prg_date_close, $prg_date_close_tstamp, DateTime $prg_date_exp, $prg_date_exp_tstamp, $prg_exp_warning){
        $datas = array();
        
        $datas['fullname'] = trim($fullname);
        $datas['pseudo'] = trim($pseudo);
        $datas['email'] = trim($email);
        $datas['passwd'] = trim($passwd);
        $datas['birthday'] = trim($birthday);
        $datas['birthday_tstamp'] = trim($birthday_tstamp);
        $datas['city'] = trim($city);
        $datas['gender'] = trim($gender);
        $datas['datecrea'] = trim($datecrea);
        $datas['datecrea_tstamp'] = trim($datecrea_tstamp);
        $datas['delay_date'] = trim($delay_date);
        $datas['delay_date_tstamp'] = trim($delay_date_tstamp);
        $datas['restart_date'] = trim($restart_date);
        $datas['restart_date_tstamp'] = trim($restart_date_tstamp);
        $datas['prg_date_close'] = trim($prg_date_close);
        $datas['prg_date_close_tstamp'] = trim($prg_date_close_tstamp);
        $datas['prg_date_exp'] = trim($prg_date_exp);
        $datas['prg_date_exp_tstamp'] = trim($prg_date_exp_tstamp);
        $datas['prg_exp_warning'] = trim($prg_exp_warning);
        
        return $datas;
    }*/
    
    /* P.L.: Nouvelle version de la fonction permettant un nombre d'arguments variable */
    protected function get_std_datas_format($args){
        $datas = array();
        foreach ($args as $k => $v){
            $$k = $v;
            if($v instanceof DateTime || $v == NULL){
                $datas[$k] = $v;
            } else {
                $datas[$k] = trim($v);
            }
        }
        
        return $datas;
    }
    
    /**
     * Vérifie si une instance existe dans la table PREREG. 
     * Pour ce faire, la méthode recoit : Fullname, Pseudo, email et password
     */
    public function CheckPreregExists_ByEmail ($email, $pwd, $std_err_enabled = NULL, &$err_ref = null) {
        //$ref_err permet de signaler au Caller qu'une erreur a été déclenchée. Il s'agit d'une référence.
        
        //TODO : Interroger le serveur
        $rq = "SELECT prereg_id FROM preregistration WHERE prg_email = '$email' AND prg_pwd = '$pwd';";
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $result = $con->query($rq);
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        
        if (count($logged) == 0) {
            
            //$code = ($std_err_enabled) ? 2 : 1;
//            $ref_err =  $this->get_or_signal_error($code, "random_err_code", __FUNCTION__, __LINE__);
            $err_ref = "Inscription non terminée !";
            return;
        } else {
            //TODO : Formater puis renvoyer les données
            return $logged;
        }
        
    }
    
    /**
     * Vérifie si une instance existe dans la table PREREG. 
     * Pour ce faire, la méthode recoit : Fullname, Pseudo, email et password
     */
    public function CheckPreregExists_ByPsd ($psd, $pwd, $std_err_enabled = NULL, &$err_ref = null) {
        //$ref_err permet de signaler au Caller qu'une erreur a été déclenchée. Il s'agit d'une référence.
        
        //TODO : Vérifie si les données sont conformes. WOS ne fait que vérifier si elles existent
        $rq = "SELECT prereg_id FROM preregistration WHERE prg_pseudo = '$psd' AND prg_pwd = '$pwd';";
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $result = $con->query($rq);
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        
        
        if (count($logged) == 0) {
            
            //$code = ($std_err_enabled) ? 2 : 1;
//            $ref_err =  $this->get_or_signal_error($code, "random_err_code", __FUNCTION__, __LINE__);
            $err_ref = "Inscription non terminée !";
            return;
        } else {
            //TODO : Formatter puis renvoyer les données
            return $logged;
        }
        
    }
    
    public function CheckPseudoExists($pseudo, $std_err_enabled = NULL, &$err_ref = NULL){
        if($pseudo == ''){
            $err_ref = 'Empty pseudo';
            return;
        } else {
            //On ne prend pas en compte la casse
            $stdpseudo = strtolower($pseudo);
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $rq = "SELECT prereg_id FROM preregistration WHERE prg_pseudo = '$stdpseudo';";
            $result = $con->query($rq);
            $rlog = $result->fetch_array(MYSQLI_ASSOC);
            
            if(count($rlog) == 0){
                //Le pseudo est dispo
                return $pseudo;
            } else {
                $err_ref = 'Pseudo deja utillise (prereg)';
                return;
            }
        }
    }
    
    
    public function CheckPreregExists_ById($id, &$err_ref = null){
        $rq = "SELECT prereg_id from preregistration WHERE prereg_id = ".$id.";";
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $result = $con->query($rq);
        $logged = $result->fetch_array(MYSQLI_ASSOC);
        
        if(count($logged) == 0){
            return $logged;
        } else {
            $err_ref = "Ce compte est deja en cours d'inscription.";
            return;
        }
    }
    
    
    public function valid_prereg_instance($array){
        $err_tab = array();
        foreach ($array as $k => $v){
            switch($k){
                case 'fullname':
                    if(!(preg_match($this->regexFullname, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn3', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'pseudo':
                    if(!(preg_match($this->regexNickname, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn3', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'email':
                    if(!(preg_match($this->regexMail, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn4', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'passwd':
                    if(!(preg_match($this->regexPasswdMini, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn5', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'birthday':
                    if($v != NULL && !(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'birthday_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'city':
                    if(!(preg_match($this->regexCity, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn8', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'gender':
                    if($v != NULL && $v != 'm' && $v != 'f'){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn9', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'datecrea':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'datecrea_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'delay_date':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'delay_date_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'restart_date':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'restart_date_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'prg_date_close':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'prg_date_close_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'prg_date_exp':
                    if(!(preg_match($this->regexMagicDates, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn6', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'prg_date_exp_tstamp':
                    if(!(preg_match($this->regexTstamps, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn7', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case 'prg_exp_warning':
                    if(!(preg_match('/^\d$/', $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('err_user_l4prgn10', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                default:
                    break;
            }
        }
        return $err_tab;
    }
    
    public function on_warning($prereg_id, bool $std_err_enabled = NULL){
        
        //Syntaxe PDO
//        $QO = new QUERY('random_query_code');
//        $qparams_val = array(':prereg_id' => $prereg_id);
//        $d = $QO->execute($qparams_val);
        
        $QO = 'SELECT prereg_id, prg_exp_warning FROM preregistration WHERE prereg_id = '.$prereg_id.';';
        $QO2 = 'UPDATE preregistration SET prg_exp_warning = '.$w.' WHERE prereg_id = '.$prereg_id.';';
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $res = $con->query($QO);
        $d = $res->fetch_array();
        
        $w = $d['prg_exp_warning'];
        
        if(intval($w) !=0){
            $w--;
            //$QO2 = new QUERY('random_query_code_2');
            //$qparams_val2 = array(':prg_exp_warning' => $w);
            //$QO2->execute($qparams_val2);
            $con->query($QO2);
            $con->close();
        } else {
            $con->close();
            $code = ($std_err_enabled) ? 2 : 1;
            return $this->get_or_signal_error(1, "err_sys_l4prgn4", __FUNCTION__, __LINE__);
        }
    }
    
    /**
     * Returns TRUE if mail is used, FALSE if not, and 'error' if something went wrong.
     * @param string $email
     * @return string|boolean
     */
    public function is_email_used_for_prereg($email){
        if(!preg_match($this->regexMail, $email)){
            $this->get_or_signal_error(1, 'err_sys_l4prgn5', __FUNCTION__, __LINE__);
            return 'error';
        } else {
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $qr = "SELECT * FROM preregistration WHERE prg_email = '$email';";
            $rslt = $con->query($qr);
            $con->close();
            if($rslt == FALSE){
                $this->get_or_signal_error(1, 'err_user_l4prgn11', __FUNCTION__, __LINE__);
                return 'error';
            } else {
                $foo = $rslt->fetch_array();
                if(count($foo)){
                    return $foo;
                } else {
                    return FALSE;
                }
            }
        }
    }
    
    public function prereg_key_generator(){
        $EMA = new EMAIL();
        $key = $EMA->guidv4();
        return $key;
    }
    
    public function get_prereg_id_from_email($email){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $qr = "SELECT prereg_id FROM preregistration WHERE prg_email = '$email' AND prg_date_close = '0000-00-00 00:00:00' AND prg_date_exp > NOW();";
        $rslt = $con->query($qr);
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4prgn6', __FUNCTION__, __LINE__);
            return 'error';
        } else {
            $foo = $rslt->fetch_array(MYSQLI_ASSOC);
            return $foo['prereg_id'];
        }
    }
    
    public function get_prereg_key_from_email($email){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $qr = "SELECT prg_key FROM preregistration WHERE prg_email = '$email' AND prg_date_close = '0000-00-00 00:00:00' AND prg_date_exp > NOW();";
        $rslt = $con->query($qr);
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4prgn7', __FUNCTION__, __LINE__);
            return 'error';
        } else {
            $foo = $rslt->fetch_array(MYSQLI_ASSOC);
            return $foo['prg_key'];
        }
    }
    
    public function load_data_from_key($key){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $qr = "SELECT * FROM preregistration WHERE prg_key = '$key';";
        $con->set_charset('utf8');
        $rslt = $con->query($qr);
        $con->close();
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4prgn8', __FUNCTION__, __LINE__);
            return;
        } else {
            $load = $rslt->fetch_array(MYSQLI_ASSOC);
            return $load;
        }
    }
    
    public function get_city_with_id($id){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $qr = "SELECT asciiname FROM partner_gn_cities_5000 WHERE city_id = '$id';";
        $rslt = $con->query($qr);
        $con->close();
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4prgn9', __FUNCTION__, __LINE__);
            return;
        } else {
            $cityname = $rslt->fetch_array(MYSQLI_ASSOC);
            return $cityname['asciiname'];
        }
    }
    
    public function close_prereg($id){
        $now = new DateTime();
        //$now_tstamp = $now->getTimestamp();
        $now_tstamp = $this->get_millitimestamp();
        $now_format = $now->format('Y-m-d H:i:s');
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $qr = "UPDATE preregistration SET prg_date_close = '$now_format', prg_date_close_tstamp = '$now_tstamp' WHERE prereg_id = '$id';";
        $rslt = $con->query($qr);
        $con->close();
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4prgn10', __FUNCTION__, __LINE__);
            return -1;
        } else {
            return 1;
        }
    }

    // <editor-fold defaultstate="collapsed" desc="Getters / Setters">
    /* GETTERS / SETTERS */
    public function getFullname() {
        return $this->fullname;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPasswd() {
        return $this->passwd;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function getCity() {
        return $this->city;
    }

    public function getDatecrea() {
        return $this->datecrea;
    }

    public function getDelay_date() {
        return $this->delay_date;
    }

    public function getRestart_date() {
        return $this->restart_date;
    }
    
    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function setPseudo($pseudo) {
        $this->pseudo = $pseudo;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPasswd($passwd) {
        $this->passwd = $passwd;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setDatecrea($datecrea) {
        $this->datecrea = $datecrea;
    }

    public function setDelay_date($delay_date) {
        $this->delay_date = $delay_date;
    }

    public function setRestart_date($restart_date) {
        $this->restart_date = $restart_date;
    }
    
    public function getPrereg_id() {
        return $this->prereg_id;
    }

    public function setPrereg_id($prereg_id) {
        $this->prereg_id = $prereg_id;
    }
    
    public function getBirthday_tstamp() {
        return $this->birthday_tstamp;
    }

    public function getDatecrea_tstamp() {
        return $this->datecrea_tstamp;
    }

    public function getDelay_date_tstamp() {
        return $this->delay_date_tstamp;
    }

    public function getRestart_date_tstamp() {
        return $this->restart_date_tstamp;
    }

    public function getPrg_date_close() {
        return $this->prg_date_close;
    }

    public function getPrg_date_close_tstamp() {
        return $this->prg_date_close_tstamp;
    }

    public function getPrg_date_exp() {
        return $this->prg_date_exp;
    }

    public function getPrg_date_exp_tstamp() {
        return $this->prg_date_exp_tstamp;
    }

    public function getPrg_exp_warning() {
        return $this->prg_exp_warning;
    }

    public function setBirthday_tstamp($birthday_tstamp) {
        $this->birthday_tstamp = $birthday_tstamp;
    }

    public function setDatecrea_tstamp($datecrea_tstamp) {
        $this->datecrea_tstamp = $datecrea_tstamp;
    }

    public function setDelay_date_tstamp($delay_date_tstamp) {
        $this->delay_date_tstamp = $delay_date_tstamp;
    }

    public function setRestart_date_tstamp($restart_date_tstamp) {
        $this->restart_date_tstamp = $restart_date_tstamp;
    }

    public function setPrg_date_close($prg_date_close) {
        $this->prg_date_close = $prg_date_close;
    }

    public function setPrg_date_close_tstamp($prg_date_close_tstamp) {
        $this->prg_date_close_tstamp = $prg_date_close_tstamp;
    }

    public function setPrg_date_exp($prg_date_exp) {
        $this->prg_date_exp = $prg_date_exp;
    }

    public function setPrg_date_exp_tstamp($prg_date_exp_tstamp) {
        $this->prg_date_exp_tstamp = $prg_date_exp_tstamp;
    }

    public function setPrg_exp_warning($prg_exp_warning) {
        $this->prg_exp_warning = $prg_exp_warning;
    }
    
    /* ------- */
    
    public function getRegexNickname() {
        return $this->regexNickname;
    }

    public function getRegexPasswdMini() {
        return $this->regexPasswdMini;
    }

    public function getRegexMail() {
        return $this->regexMail;
    }
    
    public function getRegexFullname() {
        return $this->regexFullname;
    }

// </editor-fold>


}