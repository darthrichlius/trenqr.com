 <?php

/**
 * Description de l'Entity Profil
 *
 * @author lou.carther.69
 */
class PROFIL extends PROD_ENTITY {
    private $reg_input_text;
    private $reg_input_gender;
    private $reg_input_date;
    private $reg_input_tstamp;
    private $regexFullname;
    
    //get; set;
    private $pflid;
    //get; set;
    private $uname;
    //get; set;
    private $ufullname;
    //get; set
    private $uborndate;
    //get; set;
    private $ulvcity;
    //get; set;
    private $ugender;
    //get;
    private $creadate;
    //get;
    private $modifdate;
    
    private $uborndate_mod_rem;
    private $ugender_mod_rem;
	
    
    /** [NOTE  au 29/10/13] : Ajout de la propriété.
     * Elle est importante pour eviter qu'une erreur soit déclenchée lorsque Caller tente d'effectuer une opération
     * alors que l'instance n'est pas load(). 
     * Certaines methodes ont besoin que l'instance soit load pour permettre une quelconque opération.
     */
    
    /**
     * <p>Construteur</p>
     * <p>Les regex par defaut sont utilisées par défaut tant qu'elles n'ont pas été modifiées.
     * Il faut absolument modifier les regex avec les accesseurs correspondant pour qu'elles soient pris en compte.
     * </p>
     */     
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->reg_input_text = "/^[\wÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\.\s-]{2,50}$/";
        $this->reg_input_gender = "/^[m|f]{1}$/";
        $this->regexFullname = '/^[a-zA-Z- ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,40}$/';
        //La regex puissante étant copiée de Javascript, elle plante en PHP
        $this->reg_input_date = "/^[0-9]{2}[-][0-9]{2}[-][0-9]{4}$/";
        //$this->reg_input_date = "/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
        $this->reg_input_tstamp = "/^-?\d{10,}$/";
        
        $this->is_instance_loaded = FALSE;
    }
    
    /*******************************************************************************************************/
    /********************************************* PROCESS ZONE ********************************************/
    public function build_volatile ($args) {
        /*$this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $creadate = ( !isset($creadate) ) ? new DateTime() : $creadate;
        $modifdate = ( !isset($modifdate) ) ? new DateTime() : $modifdate;
        
        $datas = $this->get_std_datas_format($name, $fname, $gender, $borndate->format("Y-m-d H:i:s"), $LvLoc, $creadate->format("Y-m-d H:i:s"), $modifdate->format("Y-m-d H:i:s"));
        
        $foo = $this->valid_profil_instance($datas);
        
        if ( !count($foo) ) {
            return $foo;
        } else {
            $this->init_properties($datas);
        }*/
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $vStore = array();
        foreach ($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        
        $uborndate_tstamp = $uborndate->getTimestamp();
        $vStore['uborndate_tstamp'] = $uborndate_tstamp;
        $datecrea = new DateTime();
        $vStore['datecrea'] = $datecrea;
        $vStore['datecrea_tstamp'] = $this->get_millitimestamp();
        $uborndate_mod_rem = (!isset($uborndate_mod_rem)) ? 5 : $uborndate_mod_rem;
        $vStore['uborndate_mod_rem'] = $uborndate_mod_rem;
        $ugender_mod_rem = (!isset($ugender_mod_rem)) ? 5 : $ugender_mod_rem;
        $vStore['ugender_mod_rem'] = $ugender_mod_rem;
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_profil_instance($datas);
        if(count($foo)){
            $this->get_or_signal_error(1, 'err_sys_l4pfln2', __FUNCTION__, __LINE__);
            return $foo;
        } else {
            $this->init_properties($datas);
        }
    }

    /************************* MAJORITAIRE */
    /*public function load_entity ($pflid, bool $std_err_enbaled = null) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $pflid);
        $QO = new QUERY("qryl4profiln2");
        $params = array( ':pflid' => $pflid );
        
        $datas = $QO->execute($params);
        if ( !count($datas) ) { 
            if ( $std_err_enbaled )  $this->signalError ("err_sys_l4profiln4", __FUNCTION__, __LINE__);
            else return 0;
        } else {
            $d["ulvloc"] = new LOCATION($datas["ulvcity_code"]);
            $this->init_properties($d);
            $this->is_instance_loaded = TRUE;
        }
    }*/
    public function load_entity($args){
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        foreach($args as $k => $v){
            $$k = $v;
        }
        if(!isset($pflid)){$this->get_or_signal_error(1, 'err_sys_l4pfln3', __FUNCTION__, __LINE__);}
        
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $QO = "SELECT * FROM profils WHERE pflid = '$pflid';";
        $rslt = $con->query($QO);
        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__); return 0;}
        $loaded = $rslt->fetch_array(MYSQLI_ASSOC);
        if(!count($loaded)){
            //ERREUR DE CHARGEMENT
            $this->get_or_signal_error(1, 'err_sys_l4pfln4', __FUNCTION__, __LINE__);
            $con->close();
            return 0;
        } else {
            $this->init_properties($loaded);
            $this->is_instance_loaded = TRUE;
            $con->close();
        }
    }
    
    protected function init_properties($datas) {
        /*$this->all_properties["pflid"] = $this->pflid = trim($datas["pflid"]);
        $this->all_properties["uname"] = $this->uname = trim($datas["uname"]);
        $this->all_properties["ufname"] = $this->ufname = trim($datas["ufname"]);
        $this->all_properties["uborndate"] = $this->uborndate = trim($datas["uborndate"]);
        $this->all_properties["ulvloc"] = $this->uLvLoc = $datas["ulvloc"];
        $this->all_properties["ugender"] = $this->ugender = trim($datas["ugender"]);
        $this->all_properties["creadate"] = $this->creadate = trim($datas["creadate"]);
        $this->all_properties["modifdate"] = $this->modifdate = (isset($datas["modifdate"]) and $datas["modifdate"] != "") ? trim($datas["modifdate"]) : "";
         */
        
        foreach($datas as $k => $v){
            $$k = $v;
            if($v instanceof DateTime || is_array($v)){
                $this->all_properties[$k] = $this->$k = $datas[$k];
            } else {
                $this->all_properties[$k] = $this->$k = trim($datas[$k]);
            }
        }
        
    }
    

    /*public function on_create_entity($name, $fname,$gender, DateTime $borndate, LOCATION $LvLoc, DateTime $creadate) {
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //Mettre la date de creation est obligatoire et on veut que caller s'en souvienne
        //Cependant, s'il veut que l'on attribue à $creadate la date d'auj on peut le faire.
        //Cela permet de decharger caller de cette tache qui pourrait etre repetitive
        $creadate = ( !isset($creadate) ) ? new DateTime() : $creadate;
        
        $datas = $this->get_std_datas_format($name, $fname, $gender, $borndate->format("Y-m-d H:i:s"), $LvLoc, $creadate->format("Y-m-d H:i:s"));

        //Rappel : A ce stade, modifdate = ""
        $foo = $this->valid_profil_instance($datas);
        
        if ( count($foo) ) {
            return $foo;
        } else {
            $this->init_properties($datas);
            /**
             * Write est ici auto. Sinon, caller n'a  qu'à utiliser build_volatile()
             * @see build_volatile()
             *//*
            $this->write_new_in_database();
        }
    }*/
    public function on_create_entity($args) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        
        //Override pour passer uborndate en DateTime
        $uborndate = date_create_from_format(('Y-m-d'), $uborndate);
        $vStore['uborndate'] = $uborndate;
        
        $datecrea = (!isset($datecrea)) ? new DateTime() : $datecrea;
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_profil_instance($datas);
        if(count($foo)){
            $this->get_or_signal_error(1, 'err_user_l4pfln1', __FUNCTION__, __LINE__);
            return $foo;
        } else {
            $this->init_properties($datas);
            $lastId = $this->write_new_in_database($datas, true);
            return $lastId;
        }
    }

    
    public function on_alter_entity($args) {
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        /**
         * Pour les commentaires
         * @see on_create_entity()
         */
        //On set manuellement la date de modif à la date actuelle
        $datemod = new DateTime();
        $datemod_tstamp = $this->get_millitimestamp();
        $vStore['datemod'] = $datemod;
        $vStore['datemod_tstamp'] = $datemod_tstamp;
        
        if(!isset($pflid)){
            $this->get_or_signal_error(1, 'err_sys_l4pfln3', __FUNCTION__, __LINE__);
        }
        
        //Si on a uborndate dans l'envoi, on la transforme en DateTime pour les traitements qui suivront
        if(isset($uborndate)){
            $fud = date_create_from_format('Y-m-d', $uborndate);
            $vStore['uborndate'] = $fud;
            $vStore['uborndate_tstamp'] = strtotime($fud->format('Y-m-d')) * 1000;
        }
        
        $this->load_entity (['pflid' => $pflid]);
        
        //Grace à load on, on a la possibilité d'avoir accès à la date de création. Cela permet aussi de garantir que la date ne sera jamais changée.
        $datas = $this->get_std_datas_format($vStore);
        
        $foo = $this->valid_profil_instance($datas);
        
        if ( count($foo) ) {
            return $foo;
        } else {
            $this->init_properties($datas);
            $rt = $this->write_new_in_database($datas);
            $this->load_entity(['pflid' => $pflid]);
            return $rt;
        }
    }
    
    /**
     * Supprimer l'occurenre PROFIL representée par pflid (propriété de classe).
     * @return type
     */
    public function on_delete_entity($args) {
        foreach ($args as $k => $v){
            $$k = $v;
        }
        /**
         * On doit verifier au preablement que le compte lié a déjà été supprimé
         * Si c'est bon on supprime
         */
        
        /*$QO = new QUERY("qryl4accn5");
        
        $qparams_in_values = array(":pflid" => $this->pflid);  

        $datas = $QO->execute($qparams_in_values);
        
        if ( !$datas ) {
            $QO = new QUERY("qryl4profiln5");
        
            $qparams_in_values = array(":pflid" => $this->pflid);  

            $QO->execute($qparams_in_values);
        } else return $datas["accid"];     */
        if(!isset($pflid)){
            $this->get_or_signal_error(1, 'err_sys_l4pfln3', __FUNCTION__, __LINE__);
            return;
        }
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $QO = "SELECT accid FROM accounts WHERE pflid = '$pflid';";
        $rsltAccount = $con->query($QO);
        $rsltListAcc = $rsltAccount->fetch_array(MYSQLI_ASSOC);
        if(!count($rsltListAcc)){
            $QP = "DELETE FROM profile WHERE pflid = '$pflid';";
            $rslt = $con->query($QP);
            if($rslt == false){
                $this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__);
            }
            $con->close();
        } else {
            //Erreur - Compte existant
            $this->get_or_signal_error(1, 'err_user_l4pfln2', __FUNCTION__, __LINE__);
            $con->close();
            return;
        }
    }
    
    protected function on_read_entity($args) {
        /* ? */
    }
    
    public function exists($args) {
        foreach($args as $k => $v){
            $$k = $v;
        }
        
        if ( !( isset($pflid) AND $pflid != "" )  /*OR*/ AND !( isset($this->pflid) AND $this->pflid != "") ) {echo 'here'; $this->signalError ("err_user_l00", __FUNCTION__, __LINE__);}
        else if ( (!isset($pflid) OR $pflid =="") and (isset($this->pflid) and $this->pflid != "") ) $pflid = $this->pflid;
        
        
        /*$code = ($std_err_enabled) ? 2 : 1;
            
        $QO = new QUERY("qryl4profiln2");
        $qparams_val = array( ':pflid' => $pflid );
        
        $d = $QO->execute($qparams_val);*/
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $QO = "SELECT pflid FROM profils WHERE pflid = '$pflid'";
        $rslt = $con->query($QO);
        $exists = $rslt->fetch_array(MYSQLI_ASSOC);
        if ( !count($exists) ){  $this->get_or_signal_error ($code, "err_sys_l4profiln4", __FUNCTION__, __LINE__);}
        //Pas de retour?...
        else {return TRUE;}
    }
    
    protected function write_new_in_database($args, $new_row = null) {
        /*$q = "";
        
        if ($new_row) {
            $q = "qryl4profiln3";
            $qparams_in_values = array(":uname" => $this->uname,":fname" => $this->ufname,":ubdate" => $this->uborndate,":ulvcity_code" => $this->uLvLoc->getLoc_city_code(),":ugender" => $this->ugender,":creadate" => $this->creadate);  
        } else {
            $q = "qryl4profiln4";
            $qparams_in_values = array(":pflid" => $this->pflid, $this->uname,":fname" => $this->ufname,":ubdate" => $this->uborndate,":ulvcity_code" => $this->uLvLoc->getLoc_city_code(),":ugender" => $this->ugender,":creadate" => $this->creadate, ":modifdate" => $this->modifdate);  
        }
        
        $QO = new QUERY($q);
        $QO->execute($qparams_in_values);*/
        
        //Initialisation des variables
        $pflid = $ufullname = $uborndate = $uborndate_tstamp = $ulvcity = null;
        $nocity = $ugender = $datecrea = $datecrea_tstamp = $datemod = $datemod_tstamp = null;
        $uborndate_mod_rem = 5;
        $ugender_mod_rem = 5;
        
        //Load des variables
        foreach($args as $k => $v){
            $$k = $v;
        }
        
        //Gestion des DateTime
        $datecrea = new DateTime();

        $uborndateInput = (!isset($uborndate)) ? null : $uborndate->format('Y-m-d H:i:s');
        $datecreaInput = $datecrea->format('Y-m-d H:i:s');
        //$datemodInput = (!isset($datemod)) ? null : $datemod->format('Y-m-d H:i:s');
        
        $uborndate_tstamp = (!isset($uborndate)) ? null : strtotime($uborndate->format('Y-m-d H:i:s')) * 1000;
        //$datecrea_tstamp = (!isset($datecrea)) ? null : $datecrea->getTimestamp();
        $datecrea_tstamp = (!isset($datecrea)) ? null : $this->get_millitimestamp();
        //$datemod_tstamp = (!isset($datemod)) ? null : strtotime($datemod->getTimestamp()) * 1000;
        
        if($new_row){
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $ufullname = $con->real_escape_string($ufullname);
            $ulvcity = $con->real_escape_string($ulvcity);
            $nocity = $con->real_escape_string($nocity);
            $ugender = $con->real_escape_string($ugender);
            $rq = "INSERT INTO profils (ufullname, uborndate, uborndate_tstamp, uborndate_mod_rem, ulvcity, nocity, ugender, ugender_mod_rem, datecrea, datecrea_tstamp)
                   VALUES ('$ufullname', '$uborndateInput', '$uborndate_tstamp', '$uborndate_mod_rem', '$ulvcity', '$nocity', '$ugender', '$ugender_mod_rem', '$datecreaInput', '$datecrea_tstamp');";
            $ctrl = $con->query($rq);
            if($ctrl == false){$this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__);}
            $lastId = $con->insert_id;
            return $lastId;
        } else {
            //Variable qui va servir à stocker les infos qui ont changé pour faire les inserts dans profils_history
            $modArray = array();
            
            if(!isset($pflid)){
                $this->get_or_signal_error(1, 'err_sys_l4pfln3', __FUNCTION__, __LINE__);
                return;
            }
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $QR = "SELECT * FROM profils WHERE pflid = '$pflid';";
            $fetch = $con->query($QR);
            $fArray = $fetch->fetch_array();
            
            //On regarde s'il y a eu modification du nom
            if(isset($args['ufullname']) && $fArray['ufullname'] != $args['ufullname']){
                $fArray['ufullname'] = $args['ufullname'];
                $now = new DateTime();
                $fArray['datemod'] = $now->format('Y-m-d H:i:s');
                $fArray['datemod_tstamp'] = $this->get_millitimestamp();
                $modArray['datemod_ufullname'] = $now->format('Y-m-d H:i:s');
                $modArray['datemod_ufullname_tstamp'] = $this->get_millitimestamp();
            }
            
            //On regarde s'il y a eu modification de la date de naissance
            //Pour ça il faut 'convertir' les date de naissance dans un
            //même format qui ignore l'heure (Y-m-d)
            if(isset($args['uborndate'])){
                $db_uborndate = date_create_from_format('Y-m-d H:i:s', $fArray['uborndate']);
                $db_uborndate = $db_uborndate->format('Y-m-d');
                $args_uborndate = $args['uborndate']->format('Y-m-d');
                if($db_uborndate != $args_uborndate){
                    //On check qu'il reste des autorisations de changement de date de naissance
                    if(intval($fArray['uborndate_mod_rem']) > 0){
                        $fArray['uborndate'] = $args['uborndate'];
                        $fArray['uborndate_tstamp'] = strtotime($args['uborndate']->format('Y-m-d')) * 1000;
                        $fArray['uborndate_mod_rem'] = intval($fArray['uborndate_mod_rem']) - 1;
                        $now = new DateTime();
                        $modArray['datemod_uborndate'] = $now->format('Y-m-d H:i:s');
                        $modArray['datemod_uborndate_tstamp'] = $this->get_millitimestamp();
                    } else {
                        return 'PFLU_NO_BDMOD_REM';
                    }
                }
            }
            
            //On regarde s'il y a eu modification du genre
            if(isset($args['ugender']) && $fArray['ugender'] != $args['ugender']){
                //On check qu'il reste des autorisations de changement de date de naissance
                if(intval($fArray['ugender_mod_rem']) > 0){
                    $fArray['ugender'] = $args['ugender'];
                    $fArray['ugender_mod_rem'] = intval($fArray['ugender_mod_rem']) - 1;
                    $now = new DateTime();
                    $modArray['datemod_ugender'] = $now->format('Y-m-d H:i:s');
                    $modArray['datemod_ugender_tstamp'] = $this->get_millitimestamp();
                } else {
                    return 'PFLU_NO_GMOD_REM';
                }
            }
            
            //On regarde s'il y a eu modification de la ville
            if(isset($args['ulvcity']) && $fArray['ulvcity'] != $args['ulvcity']){
                $fArray['ulvcity'] = $args['ulvcity'];
                $now = new DateTime();
                $modArray['datemod_ulvcity'] = $now->format('Y-m-d H:i:s');
                $modArray['datemod_ulvcity_tstamp'] = $this->get_millitimestamp();
            }
            
            //On boucle dans le tableau pour chaque donnée entrée
            /*foreach($fArray as $ka => $va){
                //On compare les données entrées aux données récupérées
                //Si différence, on remplace par la donnée entrée
                foreach($args as $kd => $vd){
                    if($ka == $kd){
                        $va = $vd;
                    }
                }
            }*/
            
            //On regarde ce qu'il y a à changer dans 'profils_history'
            if(sizeof($modArray) > 0){
                //On initialise toutes les variables possible à NULL
                $datemod_ufullname = $datemod_ufullname_tstamp = NULL;
                $datemod_uborndate = $datemod_uborndate_tstamp = NULL;
                $datemod_ugender = $datemod_ugender_tstamp = NULL;
                $datemod_ulvcity = $datemod_ulvcity_tstamp = NULL;
                //On load ce qu'on a dans le tableau
                foreach($modArray as $kma => $vma){
                    $$kma = $vma;
                }
                //On insert en base
                $QMOD = "INSERT INTO profile_history (pflid, datemod_ufullname, datemod_ufullname_tstamp, datemod_uborndate, datemod_uborndate_tstamp, datemod_ugender, datemod_ugender_tstamp, datemod_ulvcity, datemod_ulvcity_tstamp)
                         VALUES ('$pflid', '$datemod_ufullname', '$datemod_ufullname_tstamp', '$datemod_uborndate', '$datemod_uborndate_tstamp', '$datemod_ugender', '$datemod_ugender_tstamp', '$datemod_ulvcity', '$datemod_ulvcity_tstamp');";
                $chk = $con->query($QMOD);
                if($chk == false){$this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__); return 0;}
                
            }
            
            //On reset les fonctions
            foreach($fArray as $k => $v){
                $$k = $v;
            }
            //On récupère la date actuelle pour datemod
            $now = new DateTime();
            $datemod = $now->format('Y-m-d H:i:s');
            $datemod_tstamp = $this->get_millitimestamp();
            
            //On fait l'update de 'profils'
            $QP = "UPDATE profils SET ufullname = '$ufullname', uborndate = '$uborndateInput', uborndate_mod_rem = '$uborndate_mod_rem', uborndate_tstamp = '$uborndate_tstamp', ulvcity = '$ulvcity', nocity = '$nocity', ugender = '$ugender', ugender_mod_rem = '$ugender_mod_rem', datecrea = '$datecrea', datecrea_tstamp = '$datecrea_tstamp', datemod = '$datemod', datemod_tstamp = '$datemod_tstamp' WHERE pflid = '$pflid';";
            $ctrl = $con->query($QP);
            $con->close();
            if($ctrl == false){$this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__); return 0;}
            //Si on arrive là c'est que tout est OK
            return 1;
        }
    }

    /*************************************************************************************************************/
    /**************************************** FONCTIONS PROPRES A LA CLASSE **************************************/
    
    public function get_millitimestamp(){
        return round(microtime(TRUE)*1000);
    }
    
    public function did_user_completed_his_profile () {
        
    }
    
    public function does_user_must_complete_his_profile_now () {
        
    }

    /*private function get_std_datas_format ($name, $fname,$gender, $borndate, LOCATION $LvLoc, $creadate, $modifdate="") {
        $datas = array();
        
        $datas["uname"] = trim($name);
        $datas["ufname"] = trim($fname);
        $datas["uborndate"] = trim($borndate);
        $datas["ulvloc"] = $LvLoc;
        $datas["ugender"] = trim($gender);
        $datas["creadate"] = trim($creadate);
        $datas["modifdate"] = trim($modifdate);
        
        return $datas;
    }*/
    
    public function get_profile_from_account($accid){
        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        $con->set_charset('utf8');
        $accid = $con->real_escape_string($accid);
        $qr = "SELECT pflid FROM accounts WHERE accid = '$accid';";
        $rslt = $con->query($qr);
        $ra = $rslt->fetch_array(MYSQLI_ASSOC);
        return $ra['pflid'];
    }
    
    protected function get_std_datas_format($args){
        $datas = array();
        foreach ($args as $k => $v){
            if($v instanceof DateTime || is_array($v)){
                $datas[$k] = $v;
            } else {
                $datas[$k] = trim($v);
            }
        }
        return $datas;
    }

    public function valid_profil_instance($array) {
        /**
         * Cette methode est utilisée pour valider une instance profil.
         * Elle est utilisée :
         * - pour verifier que les données elementaires a l'instance sont valides
         * - pour valider un formulaire coté serveur 
         * Attention : La ville n'est pas controlée car on a en entré un objet Location qui lui controle déjà la nomenclature pour la ville
         * Renvoit un tableau de tableau avec [v][m] (m sert surtout de message de client distant) en cas d'errreur
         */
        $err_tab = array();
        foreach ($array as $k => $v) {
            switch ($k) {
                case "ufullname":
                    if ( !( preg_match($this->reg_input_text, $v) ) ) {
                         $err_tab[$k] = [
                             "v" => $v,
                             "m" => $this->get_error_msg("err_user_l4profiln2", __FUNCTION__, __LINE__)
                         ];
                    } 
                    break;
                case "ugender":
                    if ( !( preg_match($this->reg_input_gender, $v) ) ) {
                         $err_tab[$k] = [
                             "v" => $v,
                             "m" => $this->get_error_msg("err_user_l4profiln3", __FUNCTION__, __LINE__)
                         ];
                    }
                    break;
                case "uborndate":
                    if(!(preg_match($this->reg_input_date, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('random_error_msg', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case "uborndate_tstamp":
                    if(!(preg_match($this->reg_input_tstamp, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('random_error_msg', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case "datecrea":
                    if(!(preg_match($this->reg_input_date, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('random_error_msg', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case "datecrea_tstamp":
                    if(!(preg_match($this->reg_input_tstamp, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('random_error_msg', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case "datemod":
                    if(!(preg_match($this->reg_input_date, $v->format('m-d-Y')))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('random_error_msg', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                case "datemod_tstamp":
                    if(!(preg_match($this->reg_input_tstamp, $v))){
                        $err_tab[$k] = [
                            'v' => $v,
                            'm' => $this->get_error_msg('random_error_msg', __FUNCTION__, __LINE__)
                        ];
                    }
                    break;
                default:
                    break;
            }   
        }
        return $err_tab;
    }
	
    public function is_try_account($pflid){
            /* fonction qui doit vérifier si l'utilisateur est actuellement sur un comtpe d'essai.
             * En entrée, l'id du profil dont le compte correspondant est à vérifier */
            $con = mysqli_connect('localhost', 'root', '', 'kx_account_vbeta');		
            $queryAcc = "SELECT accid FROM kx_account_vbeta.accounts WHERE pflid = ". $pflid .";";
            $resultAcc = mysqli_query($con, $queryAcc);
            $accArray = mysqli_fetch_array($resultAcc);
            if(!count($accArray)){
                $this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__);
                return;
            }
            $fetchedAccid = intval($accArray['accid']);	//On peut faire ça parce que la requête n'est supposée retourner qu'un enregistrement.

            $queryStatus = "SELECT taccid, accid, dateexp, dateexp_tstamp FROM kx_account_vbeta.tryaccounts WHERE accid='$fetchedAccid';";
            $result = mysqli_query($con, $queryStatus);
            $status = mysqli_fetch_array($result);
            $con->close();
            
            if(!count($status)){
                return false;   	//Pas de match dans la base => pas un tryaccount
            } else {
                return true;    	//Match dans la base => tryaccount repéré
            }

    }
    
    public function default_pflpic_linking($pflid){
        $now = new DateTime();
        $now_tstamp = $now->getTimestamp();
        $now = $now->format('Y-m-d H:i:s');

        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
        //On sait quelle image sera celle par défaut, on peut donc mettre la valeur acp_id en dur
        $qr = "INSERT INTO pflpics_history (pflpicid, pflid, date_Enafrom, date_Enafrom_tstamp) VALUES ('1', '$pflid', '$now', '$now_tstamp')";
        $rslt = $con->query($qr);
        $con->close();
        if($rslt == FALSE){
            $this->get_or_signal_error(1, 'err_sys_l4pfln1', __FUNCTION__, __LINE__);
            return -1;
        } else {
            //Déroulement OK
            return 1;
        }
    }
    
    public function fullname_validation($fn){
        if(isset($fn) && $fn != ""){
            if(preg_match($this->regexFullname, $fn)){
                $fn = htmlentities($fn);
                return $fn;
            } else {
                return NULL;
            }
        }
        return NULL;
    }
    
    /*******************************************************************************************************/
    /*************************************** GETTERS AND SETTERS *******************************************/
    // <editor-fold defaultstate="collapsed" desc="Getters an Setters">
    public function getReg_input_text() {
        return $this->reg_input_text;
    }

    public function setReg_input_text($reg_input_text) {
        $this->reg_input_text = $reg_input_text;
    }

    public function getReg_input_gender() {
        return $this->reg_input_gender;
    }

    public function setReg_input_gender($reg_input_gender) {
        $this->reg_input_gender = $reg_input_gender;
    }

    //Dans le cas de cette classe nous ne voulons pas que les propriétés soient modifiables de l'exterieur.
    //Elles doivent être modifiées via on_alter_entity()
    public function getPflid() {
        return $this->pflid;
    }

    /********************** */

    public function getUname() {
        return $this->uname;
    }

    public function getUfullname() {
        return $this->ufullname;
    }

    public function getUborndate() {
        return $this->uborndate;
    }

    public function getUlvcity() {
        return $this->ulvcity;
    }

    public function getUgender() {
        return $this->ugender;
    }

    public function getCreadate() {
        return $this->creadate;
    }

    public function getModifdate() {
        return $this->modifdate;
    }
    
    public function getReg_input_date() {
        return $this->reg_input_date;
    }

    public function getReg_input_tstamp() {
        return $this->reg_input_tstamp;
    }
    
    public function getUborndate_mod_rem() {
        return $this->uborndate_mod_rem;
    }

    public function getUgender_mod_rem() {
        return $this->ugender_mod_rem;
    }

    public function setUborndate_mod_rem($uborndate_mod_rem) {
        $this->uborndate_mod_rem = $uborndate_mod_rem;
    }

    public function setUgender_mod_rem($ugender_mod_rem) {
        $this->ugender_mod_rem = $ugender_mod_rem;
    }



// </editor-fold>


}

?>
