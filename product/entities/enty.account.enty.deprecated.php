<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prod
 *
 * @author lou.carther.69
 */


class ACCOUNT extends PROD_ENTITY {    
    
    private $regexMail;
    private $regexNickname;
    private $regexPasswdMini;
    private $reg_input_caplol_tot;
    private $reg_input_caplol_modif;
    private $regDate;
    
    private $query_for_update;
    //--
    private $accid;
    private $acc_eid;
    private $is_acc_validated_by_email;
    private $accpseudo; 
    private $accpseudo_datemod; 
    private $accpseudo_datemod_tstamp; 
    private $acclang; 
    private $acclang_datemod; 
    private $acclang_datemod_tstamp; 
    private $acc_authemail; 
    //private $acc_email_modif_date; 
    private $acc_authpwd; 
    private $authpwd_datemod; 
    private $authpwd_datemod_tstamp; 
    private $acc_capital;
    private $staycon;
    //private $accIsBan; 
    //private $bandate; 
    private $datecrea;
    private $datecrea_tstamp;
    private $pflid;
    private $gid;
	
    private $todelete;
    //[NOTE au 29/10/13] Non géré pour l'heure
    /*private $todel_reason;
    private $todel_date;
    private $todel_date_tstamp;
    private $cancel_todel_date;
    private $cancel_todel_date_tstamp;*/
    
    private $secu_coWithPseudoEna;
    private $secu_isThirdCritEna;
	
	//Ajouts PL
    private $secu_lock_h_start;
    private $secu_lock_h_end;
    private $secu_lock_d_start;
    private $secu_lock_d_end;
    private $secu_lock_h_start_tstamp;
    private $secu_lock_h_end_tstamp;
    private $secu_lock_d_start_tstamp;
    private $secu_lock_d_end_tstamp;
    private $acc_socialarea;
    private $acc_socialarea_datemod;
    private $acc_socialarea_datemod_tstamp;
	// /Ajouts
    //--

    
    //private $caplolp_modif;
    
    
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        
        $this->reg_input_pseudo = "/^[\wÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\.-]{2,50}$/";
        $this->reg_input_pwd = "^(?=.*[a-z])(?=.*\d)[-\.~+!\*\$@%\w]{8,30}$";
        $this->reg_input_caplol_tot = "/^[\d]{1,6}$/"; //max = 999 999 
        $this->reg_input_caplol_modif = "/^[-]?(?:[1-9]|[1-9][0-9]|100)$/"; //Soit 1-9 soit un num à 2 ch soit 100 donc : -100 <= a <= 100 
        //
        $this->regexMail = '/^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})*@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})*$/';
        $this->regexNickname = '/^[a-zA-Z0-9-_ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{2,20}$/';
        $this->regexPasswdMini = '/^[^<=>\\;\/]{4,20}$/';
        //La regex puissante étant copiée de Javascript, elle plante en PHP
        $this->regDate = "/^[0-9]{2}[-][0-9]{2}[-][0-9]{4}$/";
        //$this->regDate = '/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
        //
        $this->is_instance_load = FALSE;
        
        /**
         * Il faut changer ":prop" par la propriété correspondante.
         */
        $this->query_for_update = "UPDATE ACCOUNTS SET :prop = :val WHERE accid = :accid;";
        
    }
    
    /*
     * En théorie, on attends au minimum:
     * accpseudo / acclang / acc_socialarea / acc_authpwd / (datecrea)
     */
    protected function build_volatile($args) {
       /* $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        
        $datecrea = ( !isset($datecrea) ) ? new DateTime() : $datecrea;
        $vStorage['datecrea'] = $datecrea;
        $vStorage['datecrea_tstamp'] = $datecrea->getTimestamp();
        
        $datas = $this->get_std_datas_format($vStore);
        $foo = $this->valid_property($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->init_properties($datas);
        }
        
        */
    }
    /************************* MAJORITAIRE */
    public function load_entity($args) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $args);
        foreach ($args as $k => $v){
            $$k = $v;
        }
        $QO = new QUERY("qryl4accountn1");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
//        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//        $QO = 'SELECT * FROM accounts WHERE accid = '.$accid.';';
//        $obj = $con->query($QO);
//        if($obj == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//        $datas = $obj->fetch_array();
        
        $QO = new QUERY("qryl4accountn2");
        $params = array( ':accid' => $accid );
        $datas2 = $QO->execute($params);
//        $QO2 = "SELECT * FROM email_history WHERE accid = '$accid' AND date_EndEna IS NULL;";
//        $obj2 = $con->query($QO2);
//        if($obj2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//        $datas2 = $obj2->fetch_array();
        
        if ( !count($datas) | !count($datas2)) { 
            $this->signalError ("err_sys_l4accn4", __FUNCTION__, __LINE__);
        } else {
            $this->init_properties($datas[0]);
            $this->init_properties(['acc_authemail' => $datas2[0]['emailraw']]);
            $this->is_instance_load = TRUE;
        }
    }

    
    /*private function init_properties($datas) {
         //Find : ([\[]"([a-z_]*)"[\]][\s]= [$]this->)
         //Replace : $1$2
        
         //Find : ([\[]"([a-z_]*)"[\]][\s]= [$]this->[a-z_]*)
         //Replace : $1 = trim\(\$datas\["$2"\]\)
        
         $this->all_properties["accid"] = $this->accid = trim($datas["accid"]); 
         $this->all_properties["acc_eid"] = $this->acc_eid = trim($datas["acc_eid"]); 
         $this->all_properties["is_acc_validated_by_email"] = $this->is_acc_validated_by_email = trim($datas["is_acc_validated_by_email"]);
         $this->all_properties["accpseudo"] = $this->accpseudo = trim($datas["accpseudo"]); 
         $this->all_properties["accpseudo_datemod"] = $this->accpseudo_datemod = trim($datas["accpseudo_datemod"]); 
         $this->all_properties["accpseudo_datemod_tstamp"] = $this->accpseudo_datemod_tstamp = trim($datas["accpseudo_datemod_tstamp"]); 
         $this->all_properties["acclang"] = $this->acclang = trim($datas["acclang"]); 
         $this->all_properties["acclang_datemod"] = $this->acclang_datemod = trim($datas["acclang_datemod"]); 
         $this->all_properties["acclang_datemod_tstamp"] = $this->acclang_datemod_tstamp = trim($datas["acclang_datemod_tstamp"]); 
         //$this->all_properties["acc_authemail"] = $this->acc_authemail = trim($datas["acc_authemail"]); 
         //$this->all_properties["acc_email_modif_date"] = $this->acc_email_modif_date = trim($datas["acc_email_modif_date"]); 
         $this->all_properties["acc_authpwd"] = $this->acc_authpwd = trim($datas["acc_authpwd"]); 
         $this->all_properties["authpwd_datemod"] = $this->authpwd_datemod = trim($datas["authpw_datemod"]); 
         $this->all_properties["authpwd_datemod_tstamp"] = $this->authpwd_datemod_tstamp = trim($datas["authpw_datemod_tstamp"]); 
         $this->all_properties["acc_capital"] = $this->acc_capital = trim($datas["acc_capital"]);
         $this->all_properties["staycon"] = $this->staycon = trim($datas["staycon"]);
         //$this->all_properties["acc_is_banned"] = $this->acc_is_banned = trim($datas["acc_is_banned"]); 
         //$this->all_properties["bandate"] = $this->bandate = trim($datas["bandate"]); 
         $this->all_properties["datecrea"] = $this->datecrea = trim($datas["datecrea"]);
         $this->all_properties["datecrea_tstamp"] = $this->datecrea_tstamp = trim($datas["datecrea_tstamp"]);
         $this->all_properties["pflid"] = $this->pflid = trim($datas["pflid"]);
         $this->all_properties["gid"] = $this->gid = trim($datas["gid"]);
         $this->all_properties["todelete"] = $this->todelete = trim($datas["todelete"]); 
         //$this->all_properties["todel_event_date"] = $this->todel_event_date = trim($datas["todel_event_date"]); 
         //$this->all_properties["cancel_todel_event_date"] = $this->cancel_todel_event_date = trim($datas["cancel_todel_event_date"]); 
         $this->all_properties["secu_isThirdCritEna"] = $this->secu_isThirdCritEna = trim($datas["secu_isThirdCritEna"]);
         $this->all_properties["secu_coWithPseudoEna"] = $this->secu_coWithPseudoEna = trim($datas["secu_coWithPseudoEna"]);
         
         $this->all_properties["secu_lock_h_start"] = $this->secu_lock_h_start = trim($datas["secu_lock_h_start"]);
         $this->all_properties["secu_lock_h_start_tstamp"] = $this->secu_lock_h_start_tstamp = trim($datas["secu_lock_h_start_tstamp"]);
         $this->all_properties["secu_lock_h_end"] = $this->secu_lock_h_end = trim($datas["secu_lock_h_end"]);
         $this->all_properties["secu_lock_h_end_tstamp"] = $this->secu_lock_h_end_tstamp = trim($datas["secu_lock_h_end_tstamp"]);
         $this->all_properties["secu_lock_d_start"] = $this->secu_lock_d_start = trim($datas["secu_lock_d_start"]);
         $this->all_properties["secu_lock_d_start_tstamp"] = $this->secu_lock_d_start_tstamp = trim($datas["secu_lock_d_start_tstamp"]);
         $this->all_properties["secu_lock_d_end"] = $this->secu_lock_d_end = trim($datas["secu_lock_d_end"]);
         $this->all_properties["secu_lock_d_end_tstamp"] = $this->secu_lock_d_end_tstamp = trim($datas["secu_lock_d_end_tstamp"]);
         $this->all_properties["acc_socialarea"] = $this->acc_socialarea = trim($datas["acc_socialarea"]);
         $this->all_properties["acc_socialarea_datemod"] = $this->acc_socialarea_datemod = trim($datas["acc_socialarea_datemod"]);
         $this->all_properties["acc_socialarea_datemod_tstamp"] = $this->acc_socialarea_datemod_tstamp = trim($datas["acc_socialarea_datemod_tstamp"]);
    }*/
    
    /* P.L.: Version alternative dynamique(?) */
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

     //Il ne faut pas avoir peur du fait que cette methode demandera sans doutes beaucoup de ressources. Un 
    // script d'attente est disponible en FrontEnd
    public function on_create_entity($args) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //Attention des erreurs sont toujours declenchées pour pfild et Email. C'est à Caller de s'assurer que les valeurs sont valables. 
        
        //Tableau de stockage des variables qui vont exister dans le scope
        $vStorage = [];
        
        foreach ($args as $k => $v){
            $$k = $v;
            $vStorage[$k] = $v;
        }
        $db_datas = "";
        $datas = "";
        $errs_tab = array();
        
        //Parmi les arguments passés en entrée on peut se permettre d'en zapper une partie.
        //Ils seront traités lors d'une modif du compte

        //Date de création
        $datecrea = new DateTime();
        $vStorage['datecrea'] = $datecrea;
        $vStorage['datecrea_tstamp'] = $this->get_millitimestamp();
        //$vStorage['datecrea_tstamp'] = $datecrea->getTimestamp();
        
        //Options par défaut
        //Si la langue n'est pas passée en paramètre, on utilise 'en' par défaut.
        //Note pour l'intégration: cette langue est normalement récupérable via $_SESSION['lang']
        //et/ou via un ID quelconque dans le sélecteur de langue dans chacun des headers.
        $acclang = (!isset ($acclang)) ? 'en' : $acclang;
        
        
        /* PROFIL ZONE */
        //On declenche une erreur car si pflid n'est pas conforme c'est une faute de conception.
        if($this->valid_property(['pflid' => $pflid])){
            $errs_tab[] = $this->valid_property(['pflid' => $pflid]);
        }
        //[Note au 29/10/13] Pourquoi reverifier l'existance de l'erreur lorsque l'on sait qu'une erreur sera déclenchée en cas de problème ???
        if ( !key_exists("pflid", $errs_tab) ) {
            //Verifier que pflid existe
            $foo = new PROFIL();
            /* On declenche une erreur car si pflid n'existe pas à ce stade c'est une faute de conception.
             * Caller doit au prealable write un PROFIL avant d'appeler ACCOUNT.
             */
            if ( !$foo->exists(['pflid' =>$pflid]) ) $this->signalError("err_sys_l4accn10", __FUNCTION__, __LINE__);
        }
        
        if($this->valid_property(['accpseudo' => $accpseudo])){
            $errs_tab[] = $this->valid_property(['accpseudo' => $accpseudo]);
        }
        if($this->valid_property(['acclang' => $acclang])){
            $errs_tab[] = $this->valid_property(['acclang' => $acclang]);
        }
        
//        /* EMAIL */ 
//        $errs_tab[] = $this->valid_property(['acc']);
//        //On verifie qu'une erreur n'existe pas deja concernant email. Sinon, pas la peine de faire appel à bdd pour verif si la valeur n'est pas valide. 
//        if ( !key_exists("acc_authemail", $errs_tab) ) {
//            //Verifier que Email n'est pas déjà attribué.
//            $QO = new QUERY("qryl4accn2");
//            $params = array( ':email' => $acc_authemail );
//            
//            $db_datas = $QO->execute($params);
//            /*On déclenche une erreur car c'est à caller de s'assurer que l'email n'est pas déjà attribué. 
//             * ACCOUNT joue lui le role de dernier rempart.
//             */
//            if ( count($db_datas) ) $this->signalError("err_sys_l4accn11", __FUNCTION__, __LINE__); 
//        }       
        
        if($this->valid_property(['acc_authpwd' => $acc_authpwd])){
            $errs_tab[] = $this->valid_property(['acc_authpwd' => $acc_authpwd]);
        }
        if($this->valid_property(['datecrea' => $datecrea])){
            $errs_tab[] = $this->valid_property(['datecrea' => $datecrea]);
        }
        
        if ( count($errs_tab) ) {
            return $errs_tab;
        } else {
            //On crypte le password
            $hashedPwd = $this->hash_input_passwd($acc_authpwd);
            $vStorage['acc_authpw'] = $hashedPwd;
            
            $datas = $this->get_std_datas_format($vStorage);
            
            /** 
             * [NOTE au 29/10/13] : Il est évident qu'à ce stade seules les propriétés obligatoires sont instanciées.
             * Les autres sont NULL.
             * C'est pourquoi qu'apres la création de l'objet au sein de la base de données, nous loadons pour récupérer les valeurs par défaut.
             * Ainsi toutes les propriétés (ou presque) sont instaciées 
             */
            $this->init_properties($datas);
            /**
             * Write est ici auto. Sinon, caller n'a  qu'à utiliser build_volatile()
             * @see build_volatile()
             */
            $lastId = $this->write_new_in_database($datas, true);
            return $lastId;
            /**
             * On Load pour instancier toutes les propriétés. Pour cela, on recupèrre l'accid grace à l'email.
             * Puis on lance Load()
             * @see load_entity()
             */
//            $QO = new QUERY("qryl4accn2");
//            $params = array( ':email' => $acc_authemail );
//            $db_datas2 = $QO->execute($params);
//            
//            if ( count($db_datas2) ) {
//                $var = $db_datas2["acc_id"];
//                /**
//                 * Avant de load, on va attribuer les 100 premier lol+ à l'utilisateur
//                 */
//                $this->setCaplolp_modif(100);
//                //On load        
//                $this->load_entity($var,TRUE);
//            }
            //Pas de ELSE car c'est impossible qu'il y est une erreur ici après toutes les barrières de sécurité en amont.
            //Arriver au ELSE signifie que l'ajout s'est mal passé. Or, si c'était le cas, on aurait eu une erreur.
        }   
    }
        
    
    //protected function on_alter_entity($k, $v, bool $std_err_enbaled) {
    public function on_alter_entity($args) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $vStore = array();
        foreach($args as $k => $v){
            $$k = $v;
            $vStore[$k] = $v;
        }
        
        if(!isset($accid)){
            $this->get_or_signal_error(1, 'err_sys_l4accn2', __FUNCTION__, __LINE__);
        }
        
        $this->load_entity(['accid' => $accid]);
        $datas = $this->get_std_datas_format($vStore);
        
        $foo = $this->valid_property($datas);
        if(count($foo)){
            return $foo;
        } else {
            $this->init_properties($datas);
            $rval = $this->write_new_in_database($datas);
            $this->load_entity(['accid' => $accid]);
            
            return $rval;
        }
    }
    
    
    protected function valid_property ($array) {
        $err_tab = array();
        foreach($array as $k => $v){        
            switch ($k) {
               case "secu_isThirdCritEna" :
                   if(!(intval($v) == 0 || intval($v) == 1)){
                       $err_tab[$k] = [
                           "v" => $v,
                           "m" => $this->get_or_signal_error(1, 'err_user_l4accn1', __FUNCTION__, __LINE__)
                       ];
                   }
                   break;
               case "secu_coWithPseudoEna" :
                   if(!(intval($v) == 0 || intval($v) == 1)){
                       $err_tab[$k] = [
                           "v" => $v,
                           "m" => $this->get_or_signal_error(1, 'err_user_l4accn1', __FUNCTION__, __LINE__)
                       ];
                   }
                   break;
               case "accIsBan" :
                   if(!(intval($v) == 0 || intval($v) == 1)){
                       $err_tab[$k] = [
                           "v" => $v,
                           "m" => $this->get_or_signal_error(1, 'err_user_l4accn1', __FUNCTION__, __LINE__)
                       ];
                   }
                   break;
               case "staycon" :
                   if(!(intval($v) == 0 || intval($v) == 1)){
                       $err_tab[$k] = [
                           "v" => $v,
                           "m" => $this->get_or_signal_error(1, 'err_user_l4accn1', __FUNCTION__, __LINE__)
                       ];
                   }
                   break;
               case "todelete" :
                   if(!(intval($v) == 0 || intval($v) == 1)){
                       $err_tab[$k] = [
                           "v" => $v,
                           "m" => $this->get_or_signal_error(1, 'err_user_l4accn1', __FUNCTION__, __LINE__)
                       ];
                   }
                   break;
//               case "todel_event_date" :
//               case "cancel_todel_event_date" :
//                       if ( $v !== TRUE or $v !== FALSE ) {
//                           //Dans la base de données on a INT(1) qui est interprete par : FALSE ou TRUE
//                           $err_tab[$k] = [
//                                "v" => $v,
//                                "m" => $this->get_or_signal_error($code, err_user_l45, __FUNCTION__, __LINE__)
//                            ];
//                       }
//                       break;
              case "accpseudo" :
                           if ( !( preg_match($this->reg_input_pseudo, $v) ) ) {
                            $err_tab[$k] = [
                                "v" => $v,
                                "m" => $this->get_or_signal_error(1, 'err_user_l4profiln3', __FUNCTION__, __LINE__)
                            ];
                       }
                       break;
              case "acclang" :
                           //Cette valeur a été passée a to_lower()
                           if ( !( preg_match("/^[a-z]{2,3}$/", $v) ) ) {
                               $err_tab[$k] = [
                                   "v" => $v,
                                   "m" => $this->get_or_signal_error(1, 'err_user_l4accn3', __FUNCTION__, __LINE__)
                               ];
                           } else {
                               //P.L.: ??? | Je suppose que c'est pour vérifier que l'acclang existe bien
                               /*$QO = new QUERY("qryl4anyn1");
                               $params = array( ':lg_code' => $v );
                               $datas = $QO->execute($params);
                               if ( !count($datas) ) {
                                   $err_tab[$k] = [
                                       "v" => $v,
                                       "m" => $this->get_or_signal_error(1, 'err_user_l4accn3', __FUNCTION__, __LINE__)
                                   ];
                               }*/
                           }
                       break;
              case "accpseudo_datemod" :
                  if(!(preg_match_all($this->regDate, $v->format('m-d-Y')))){
                      $err_tab[$k] = [
                          'v' => $v,
                          'm' => $this->get_or_signal_error(1, 'err_user_l4accn2', __FUNCTION__, __LINE__)
                      ];
                  }
                  break;
              case "acclang_datemod" :
                  if(!(preg_match_all($this->regDate, $v->format('m-d-Y')))){
                      $err_tab[$k] = [
                          'v' => $v,
                          'm' => $this->get_or_signal_error(1, 'err_user_l4accn2', __FUNCTION__, __LINE__)
                      ];
                  }
                  break;
              //case "bandate" :
              case "datecrea" :
                  if(!(preg_match_all($this->regDate, $v->format('m-d-Y')))){
                      $err_tab[$k] = [
                          'v' => $v,
                          'm' => $this->get_or_signal_error(1, 'err_user_l4accn2', __FUNCTION__, __LINE__)
                      ];
                  }
                  break;
              case "acc_authemail" :
                           $e_t = new EMAIL();
                           $m = $e_t->valid_email($e_t);
                           if ( $m  ) {
                               $err_tab[$k] = [
                                       "v" => $v,
                                       "m" => $m 
                                   ];
                           } 
                       break;
              case "acc_authpwd" :
                           if ( !( preg_match($this->regexPasswdMini, $v) ) ) {
                               $err_tab[$k] = [
                                   "v" => $v,
                                   "m" => $this->get_or_signal_error(1, 'err_sys_l4accn5', __FUNCTION__, __LINE__)
                               ];
                           } 
                       break;
              case "acc_capital" :
                       if ( !( preg_match($this->reg_input_caplol_tot, $v) ) ) {
                               $err_tab[$k] = [
                                   "v" => $v,
                                   "m" => $this->get_or_signal_error(1, 'err_sys_l4accn8', __FUNCTION__, __LINE__)
                               ];
                           } 
                       break;
               case "caplolp_modif" :
                       if ( !( preg_match($this->reg_input_caplol_modif, $v) ) ) {
                               $err_tab[$k] = [
                                   "v" => $v,
                                   "m" => $this->get_or_signal_error(1, 'err_sys_l4accn9', __FUNCTION__, __LINE__)
                               ];
                           } 
                       break;
              case "pflid" :
                           if ( !( preg_match("/^[\d]{1,10}$/", $v) ) ) {
                               $err_tab[$k] = [
                                   "v" => $v,
                                   "m" => $this->get_or_signal_error(1, 'err_sys_l4profiln5', __FUNCTION__, __LINE__)
                               ];
                           }
                       break;
              default:
                   break;  
            }
        }
        return $err_tab;
    }


    public function on_delete_entity($args) {
        /**
         * La suppession de compte est l'une des opérations les plus critiques à réaliser.
         * Elle implique la supression de toutes les entités liées au compte : groupe aricle, profil etc ...
         * 
         * Pour l'heure, il est impossible de supprimer un compte directement à la demande de l'utilisateur pour de multiples raisons.
         * Premierement, pour des raisons fonctionnelles :
         *      - Accéder instantannement à cette demande c'est ne pas lui laisser l'opportunité de changer d'avis et revenir sur sa decision.
         *      - De plus, c'est perdre un utilisateur sans essayer une relance.
         *      - C'est permettre qu'un utilisateur dont le compte a été piraté de perdre toutes ses données sans possiblité de retour en arriere.
         *      Note : Dans ce dernier cas, un email est toujours envoyé à l'utilisateur pour l'informer en ce qui concerne le processus de suppression.
         *      Un lien est inséré pour faire marche arriere selon le temps imparti.
         * Deuxiemement, il s'agit techniquement d'une opération sensible. Aussi, nous laisserons ce soin à un DEAMON qui supprimera le compte
         * et les groupes associés.
         * 
         * Pour l'instant tout ce que nous pouvons faire c'est change todelete
         */
        
        /**
         * PROCEDURES : 
         * - On verifie que le compte n'est pas deja en mode to delete. 
         * - On verifie qu'une decision d'annulation n'est pas déjà en cours
         * En temps normal, les cas évoqués precedemment ne peuvent pas se produire. Aussi, pour des soucis de performance on ne les verifiera meme pas.
         * - On modifie l'occurrence pour changer les colonnes "todelete" et "todel_event_date"   
         */
        
        $this->write_and_load_new_prop_on_alter ("todelete", "1");
        $n_date = new DateTime();
        $n_date->format("Y-m-d H:i:s");
        $this->write_and_load_new_prop_on_alter ("todel_event_date", $n_date);
        
    }

    
    protected function on_read_entity($args) {
        /*
         ?
         */
    }
    
    
    public function exists( $args ) {
        foreach($args as $k => $v){
            $$k = $v;
        }
        if ( !( isset($accid) AND $accid != "" )  OR !( isset($this->accid) AND $this->accid != "") ) $this->signalError ("err_user_l00", __FUNCTION__, __LINE__);
        else if ( (!isset($accid) OR $accid =="") and (isset($this->accid) and $this->accid != "") ) $accid = $this->accid;
        
        
//        $code = ($std_err_enabled) ? 2 : 1;
//        
//        $QO = new QUERY("qryl4accn1");
//        $qparams_val = array( ':accid' => $accid );
//        
//        $d = $QO->execute($qparams_val);
//        if ( !count($d) )  $this->get_or_signal_error ($code, "err_sys_l4accn4", __FUNCTION__, __LINE__);
        
        // ** Partie MySQLi ** //
//        $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//        $QO = "SELECT accid from accounts WHERE accid = '$accid'";
//        $rslt = $con->query($QO);
//        if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//        $fa = $rslt->fetch_array();
        $QO = new QUERY("qryl4accountn3");
        $params = array( ':accid' => $accid );
        $datas = $QO->execute($params);
        
        if(!count($datas)){
            return false;
        } else {
            return true;
        }
    }

    
    public function write_new_in_database($datas, $new_row = NULL) {
        /*$qparams_in_values = array(":pflid" => $datas["pflid"],":accpseudo" => $datas["accpseudo"],":acclang" => $datas["acclang"],":acc_authemail" => $datas["acc_authemail"],":acc_authpwd" => $datas["acc_authpwd"],":datecrea" => $datas["datecrea"]);
        
        $QO = new QUERY("qryl4accn3");
        $QO->execute($qparams_in_values);
        
        */
        
        //Initialisation des variables
        $acc_eid = $pflid = $accpseudo = $accpseudo_datemod = $accpseudo_datemod_tstamp = null;
        $acclang_datemod = $acclang_datemod_tstamp = $acc_socialarea = $acc_authpwd = null;
        $authpwd_datemod = $authpwd_datemod_tstamp = $acclang = null;
        $datecrea = $datecrea_tstamp = $menu_pfl_notf = null;
        $menu_pfl_notf_tstamp = $menu_acc_notf = $menu_acc_notf_tstamp = $menu_secu_notf = null;
        $menu_secu_notf_tstamp = $secu_lock_h_start = $secu_lock_h_end = $secu_lock_d_start = null;
        $secu_lock_d_start_tstamp = $secu_lock_d_end = $secu_lock_d_end_tstamp = null;
        $acc_capital = $staycon = $secu_isThirdCritEna = $accIsBan = $todelete = 0;
        $secu_coWithPseudoEna = 1;
        // -- Nécessaire pour la suite --
        $emailraw = null;
        
        
        //Load des variables
        foreach($datas as $k => $v){
            $$k = $v;
        }
        
        //Gestion des dates
        $datecrea = (!isset($datecrea)) ? null : $datecrea = new DateTime();
        if($datecrea != null){
            //$datecrea_tstamp = $datecrea->getTimestamp();
            $datecrea_tstamp = $this->get_millitimestamp();
        } else {
            $datecrea_tstamp = null;
        }
        //$datecrea_tstamp = ($datecrea != null) ? null : $datecrea->getTimestamp();
        $datecrea = (!isset($datecrea)) ? null : $datecrea = $datecrea->format('Y-m-d H:i:s');
        
        //Params 
        //Options par défaut
        //Si la langue n'est pas passée en paramètre, on utilise 'en' par défaut.
        //Note pour l'intégration: cette langue est normalement récupérable via $_SESSION['socialarea'] (ou truc du genre).
        $acc_socialarea = (!isset($acc_socialarea)) ? 'us' : $acc_socialarea;
        
        if($new_row){
            //Obligé de faire plusieurs requêtes, puisque pour fabriquer acc_eid il nous faut le accid généré.
            //De plus, les données nécessaires à la création de acc_eid se trouvent dans d'autres tables.
            $hashedPw = $this->hash_input_passwd($acc_authpwd);
            //Première phase: insertion
            $QO = new QUERY("qryl4accountn4");
            $params = array(':pflid' => $pflid, ':accpseudo' => $accpseudo, ':accpseudo_datemod' => $accpseudo_datemod, ':accpseudo_datemod_tstamp' => $accpseudo_datemod_tstamp, ':acclang' => $acclang, ':acclang_datemod' => $acclang_datemod, ':acclang_datemod_tstamp' => $acclang_datemod_tstamp, ':acc_socialarea' => $acc_socialarea, ':hashedPw' => $hashedPw, ':authpwd_datemod' => $authpwd_datemod, ':authpwd_datemod_tstamp' => $authpwd_datemod_tstamp, ':acc_capital' => $acc_capital, ':staycon' => $staycon, ':secu_coWithPseudoEna' => $secu_coWithPseudoEna, ':secu_isThirdCritEna' => $secu_isThirdCritEna, ':accIsBan' => $accIsBan, ':todelete' => $todelete, ':datecrea' => $datecrea, ':datecrea_tstamp' => $datecrea_tstamp, ':menu_pfl_notf' => $menu_pfl_notf, ':menu_pfl_notf_tstamp' => $menu_pfl_notf_tstamp, ':menu_acc_notf' => $menu_acc_notf, ':menu_acc_notf_tstamp' => $menu_acc_notf_tstamp, ':menu_secu_notf' => $menu_secu_notf, ':menu_secu_notf_tstamp' => $menu_secu_notf_tstamp, ':secu_lock_h_start' => $secu_lock_h_start, ':secu_lock_h_end' => $secu_lock_h_end, ':secu_lock_d_start' => $secu_lock_d_start, ':secu_lock_d_start_tstamp' => $secu_lock_d_start_tstamp, ':secu_lock_d_end' => $secu_lock_d_end, ':secu_lock_d_end_tstamp' => $secu_lock_d_end_tstamp);
            $lastId = $QO->execute($params);
                   
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $con->set_charset('utf8');
//            $ctrl = $con->query($QO);
//            if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//            $lastId = $con->insert_id;
            
            //Deuxième phase: récupération des données nécessaires à la fabrication de acc_eid
//            $QP = "SELECT p.uborndate_tstamp, p.ugender, ct.ctr_code FROM profils p INNER JOIN partner_gn_cities_5000 gn ON p.ulvcity = gn.city_id INNER JOIN countries ct ON gn.country_code = ct.ctr_code WHERE p.pflid = '$pflid';";
//            $midresult = $con->query($QP);
//            if($midresult == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//            $midarray = $midresult->fetch_array(MYSQLI_ASSOC);
            $QO2 = new QUERY("qryl4accountn5");
            $params2 = array(':pflid' => $pflid);
            $midarray = $QO2->execute($params2)[0];
            
            //Troisième phase: construction et insertion de acc_eid
            $acc_eid = $this->create_ueid($midarray['uborndate_tstamp'], $midarray['ugender'], $midarray['ctr_code'], $lastId);
//            $QR = "UPDATE accounts SET acc_eid = '$acc_eid' WHERE accid = '$lastId';";
//            $ctrl2 = $con->query($QR);
//            if($ctrl2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//            $con->close();
            $QO3 = new QUERY("qryl4accountn6");
            $params3 = array(':acc_eid' => $acc_eid, ':lastId' => $lastId);
            $QO3->execute($params3)[0];
            return $lastId;
        } else {
            // ************ TEST DE RECUP AUTOMATIQUE *************** //
            //On regarde ce qu'il y a déjà en base à l'ID donné
            //ID qui doit être passé dans $args
            if(!isset($accid)){
                $this->get_or_signal_error(1, 'err_sys_l4accn2', __FUNCTION__, __LINE__);
                return;
            }
//            $QR = "SELECT * FROM accounts WHERE accid = '$accid';";
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $fetch = $con->query($QR);
//            if($fetch == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//            $fArray = $fetch->fetch_array(MYSQLI_ASSOC);
            $QO = new QUERY("qryl4accountn1");
            $params = array(':accid' => $accid);
            $fArray = $QO->execute($params);
            
            if (! ( $fArray && count($fArray) ) )
            {
                return 0;
            }
            
            $fArray = $fArray[0];
            
            //On regarde s'il y a eu modification du pseudo
            if( $fArray && count($fArray) && isset($datas['accpseudo']) && $fArray['accpseudo'] != $datas['accpseudo']){
                
                $fArray['accpseudo'] = $datas['accpseudo'];
                $fArray['accpseudo_datemod'] = new DateTime();
                //$fArray['accpseudo_datemod_tstamp'] = $fArray['accpseudo_datemod']->getTimestamp();
                $fArray['accpseudo_datemod_tstamp'] = $this->get_millitimestamp();
                $fArray['accpseudo_datemod'] = $fArray['accpseudo_datemod']->format('Y-m-d H:i:s');
            }
            
            //On regarde s'il y a eu modification du password
            if(isset($datas['newpw']) && isset($datas['newpwconf']) && isset($datas['oldpw'])){
                $oldpw = $datas['oldpw'];
                $newpw = $datas['newpw'];
                $newpwconf = $datas['newpwconf'];
                
                //On commence par check si le oldpw est correct
                $match = $this->compare_hashed_passwd($oldpw, $fArray['acc_authpwd']);
                if($match == FALSE){
                    //Mauvais password
                    return 'badpw';
                } else {
                    //Si tout est bon, on vérifie que le newpw et newpwconf sont similaires
                    if($newpw != $newpwconf){
                        //Mauvaise confirm
                        return 'badconfirm';
                    } else {
                        //On vérifie que le nouveau password ne contient pas de caractères interdits
                        if(!preg_match_all($this->regexPasswdMini, $newpw)){
                        //Nouveau pas conforme
                            return 'notconform';
                        } else {
                            //Si tout ça est bon, on prépare la mise à jour
                            $fArray['acc_authpwd'] = $this->hash_input_passwd($newpw);
                            $now = new DateTime();
                            $fArray['authpwd_datemod_tstamp'] = $this->get_millitimestamp();
                            //$fArray['authpwd_datemod_tstamp'] = $now->getTimestamp();
                            $fArray['authpwd_datemod'] = $now->format('Y-m-d H:i:s');
                        }
                    }
                }
                
                
//                $fArray['acc_authpwd'] = $datas['acc_authpwd'];
//                $fArray['acc_authpwd_datemod'] = new DateTime;
//                $fArray['acc_authpwd_datemod_tstamp'] = $fArray['acc_authpwd_datemod']->getTimestamp();
            }
            
           
            //On regarde s'il y a eu modification de la langue
            if(isset($datas['acclang']) && $fArray['acclang'] != $datas['acclang']){
                $fArray['acclang'] = $datas['acclang'];
                $fArray['acclang_datemod'] = new DateTime;
                $fArray['acclang_datemod_tstamp'] = $this->get_millitimestamp();
                //$fArray['acclang_datemod_tstamp'] = $fArray['acclang_datemod']->getTimestamp();
                $fArray['acclang_datemod'] = $fArray['acclang_datemod']->format('Y-m-d H:i:s');
            }
            
            //On regarde s'il y a eu modification de la SocialArea
            if(isset($datas['acc_socialarea']) && $fArray['acc_socialarea'] != $datas['acc_socialarea']){
                $fArray['acc_socialarea'] = $datas['acc_socialarea'];
            }
            
            //On boucle dans ce tableau pour chaque donnée entrée restante.
            //On a besoin de check pw/pseudo/lang avant pour récupérer la date
            //de modification si besoin.
            foreach($datas as $kd => $vd){
                if(isset($fArray[$kd]) && $fArray[$kd] != $vd){
                    //Cas d'une donnée présente dans l'input mais pas mise à jour avant
                    $fArray[$kd] = $vd;
                }/* else if(!isset($fArray[$kd])){
                    var_dump($kd);
                    //Ça, c'est pas supposé arriver
                    return 'UACC_GENERAL_UPERROR';
                }*/
            }
            /*foreach($fArray as $ka => $va){
                //On compare les données entrées aux données récupérées
                //Si différence, on remplace par la donnée entrée
                foreach($datas as $kd => $vd){
                    if($ka == $kd){
                        $va = $vd;
                    }
                }
            }*/
            //On reset les fonctions
            foreach($fArray as $k => $v){
                $$k = $v;
            }
          
            
            
            //var_dump($fArray);
            //var_dump($acc_eid);
            
            //On fait l'update
//            $QP = "UPDATE accounts SET acc_eid = '$acc_eid', pflid = '$pflid', accpseudo = '$accpseudo', accpseudo_datemod = '$accpseudo_datemod', accpseudo_datemod_tstamp = '$accpseudo_datemod_tstamp', acclang = '$acclang', acclang_datemod = '$acclang_datemod', acclang_datemod_tstamp = '$acclang_datemod_tstamp',
//                   acc_socialarea = '$acc_socialarea', acc_authpwd = '$acc_authpwd', authpwd_datemod = '$authpwd_datemod', authpwd_datemod_tstamp = '$authpwd_datemod_tstamp', acc_capital = '$acc_capital', staycon = '$staycon', secu_coWithPseudoEna = '$secu_coWithPseudoEna', secu_isThirdCritEna = '$secu_isThirdCritEna',
//                   accIsBan = '$accIsBan', todelete = '$todelete', datecrea = '$datecrea', datecrea_tstamp = '$datecrea_tstamp', menu_pfl_notf = '$menu_pfl_notf', menu_pfl_notf = '$menu_pfl_notf_tstamp', menu_acc_notf = '$menu_acc_notf', menu_acc_notf_tstamp = '$menu_acc_notf_tstamp', menu_secu_notf = '$menu_secu_notf',
//                   menu_secu_notf_tstamp = '$menu_secu_notf_tstamp', secu_lock_h_start = '$secu_lock_h_start', secu_lock_h_end = '$secu_lock_h_end', secu_lock_d_start = '$secu_lock_d_start', secu_lock_d_start_tstamp = '$secu_lock_d_start_tstamp', secu_lock_d_end = '$secu_lock_d_end', secu_lock_d_end_tstamp = '$secu_lock_d_end_tstamp'
//                   WHERE accid = '$accid'";
//            $ctrl = $con->query($QP);
//            $con->close();
//            if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
            $QO = new QUERY("qryl4accountn7");
            $params = array(':accid' => $accid, ':acc_eid' => $acc_eid, ':pflid' => $pflid, ':accpseudo' => $accpseudo, ':accpseudo_datemod' => $accpseudo_datemod, ':accpseudo_datemod_tstamp' => $accpseudo_datemod_tstamp, ':acclang' => $acclang, ':acclang_datemod' => $acclang_datemod, ':acclang_datemod_tstamp' => $acclang_datemod_tstamp,
                   ':acc_socialarea' => $acc_socialarea, ':acc_authpwd' => $acc_authpwd, ':authpwd_datemod' => $authpwd_datemod, ':authpwd_datemod_tstamp' => $authpwd_datemod_tstamp, ':acc_capital' => $acc_capital, ':staycon' => $staycon, ':secu_coWithPseudoEna' => $secu_coWithPseudoEna, ':secu_isThirdCritEna' => $secu_isThirdCritEna,
                   ':accIsBan' => $accIsBan, ':todelete' => $todelete, ':datecrea' => $datecrea, ':datecrea_tstamp' => $datecrea_tstamp, ':menu_pfl_notf' => $menu_pfl_notf, ':menu_pfl_notf_tstamp' => $menu_pfl_notf_tstamp, ':menu_acc_notf' => $menu_acc_notf, ':menu_acc_notf_tstamp' => $menu_acc_notf_tstamp, ':menu_secu_notf' => $menu_secu_notf,
                   ':menu_secu_notf_tstamp' => $menu_secu_notf_tstamp, ':secu_lock_h_start' => $secu_lock_h_start, ':secu_lock_h_end' => $secu_lock_h_end, ':secu_lock_d_start' => $secu_lock_d_start, ':secu_lock_d_start_tstamp' => $secu_lock_d_start_tstamp, ':secu_lock_d_end' => $secu_lock_d_end, ':secu_lock_d_end_tstamp' => $secu_lock_d_end_tstamp);
            $QO->execute($params);
            
            //Si on arrive là, c'est que tout est OK
            return 1;
        }
    }
    
    /****************************************************************************************************************/
    /********************************************* SPECIFIQUE A LA CLASSE *******************************************/
    public function get_millitimestamp(){
        return round(microtime(TRUE)*1000);
    }
    
    protected function instance_is_load_or_nothing () {
        /**
         * Permet de verifier si l'instance est load. Si ce n'est pas le cas on STOP
         * 
         * Sert de protection pour les methodes qui ont besoin que l'instance soit load pour fonctionner.
         */
        if ( !$this->is_instance_load ) $this->signalError ("err_sys_l46", __FUNCTION__, __LINE__);
    }
    
    
    public function cancel_a_deleting_process () {
        /**
         * Permet d'annuler une procedure de suppression tant que le delai contractuel d'attente n'est pas atteint.
         * 
         * On verifie si effectivement une annulation est en cours.
         * Si oui, on annule en modifiant to_delete.
         * 
         * PROCEDURES :
         * - On verifie que le compte est effectivement en mode todelete = 1 
         * - On mets la valeur todelete à 0
         * - On mets NULL la date todel_evnt_date
         * - On mets DATETIME (Now) à cancel_todel_event_date
         */
        $this->instance_is_load_or_nothing();
        //On ne va pas interroger la base de données car la classe etant déjà load la probabilité que les données soient à jour est consequente.
        if ( $this->todelete ) { 
            //On verifie qu'on a pas dépassé les 14 jours.
            $now = new DateTime();
            $now->format("Y-m-d H:i:s");
            
        } 
        
    }
    
    public function process_valid_account_by_account () {
        /**
         * Validation du compte par email :
         * On verifie que ce n'est pas déjà fait 
         * On change l'occurence au niv de la bdd
         * On load
         */
    }
    
    
    public function did_user_validated_his_account_by_email () {
        //Verifie si user a valider account
        //S'il ne l'a pas fait il renvoie un time representant le temps un rapport entre la date d'inscription et auj
    }
     
    
    protected function get_std_datas_format ($args) {
        //string pour datecrea car on veut s'assurer que l'on a pas fait une erreur en ne formattant pas DateTime en entrée
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
    
    
    public function reinit_default_config () {
        $this->reg_input_pseudo = "/^[\wÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\.-]{2,50}$/";
        $this->reg_input_pwd = "^(?=.*[a-z])(?=.*\d)[-\.~+!\*\$@%\w]{8,30}$";
        $this->reg_input_caplol_tot = "/^[\d]{1,6}$/"; //max = 999 999 
        $this->reg_input_caplol_modif = "/^[-]?(?:[1-9]|[1-9][0-9]|100)$/"; //Soit 1-9 soit un num à 2 ch soit 100 donc : -100 <= a <= 100
        
    }
    
    
    protected function create_and_get_query_for_update ($k) {
        //RAPPEL : k = key, c-a-d quelle de quelle propriété il s'agit
        
        //Permet de formater la chaine $query_for_update 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args()); 
        if ( $k != "" ) {
            $p = "/:prop/";
            $r = $k;
            return preg_replace($p, $r, $this->query_for_update);
        } else $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
    }

    
    protected function write_and_load_new_prop_on_alter ($k, $v) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());

        if ( $k != "" && $v != "" ) {
            /**
             * On verifie si l'instance a été load au moins une fois.
             * Dans le cas d'un objet créer grace à build_volatile(), cette fonction déclenchera une erreur car build_volatile
             * ne permet pas une modification avec action de write. 
             */
            if ( $this->is_instance_load ) {
                    $st = $this->create_and_get_query_for_update($k);

                    //On crée un objet Query avec comme Qid "qryl4accn4" seulement pour récuppérer les composantes 
                    $QO1 = new QUERY("qryl4accn4");

                    $QO2 = new QUERY();
                    $qparams_in = array( ':accid' => $this->accid, ':val' => $v );
                    $QO2->build_volatile($st, $QO1->getQdbname(), $QO1->getQtype(), $qparams_in);

                    $QO2->execute($qparams_in);
                
                } else $this->signalError ("err_sys_l019", __FUNCTION__, __LINE__);
            
        } else $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
        
    }
	
	/* Ajouts PL */
	public function is_try_account($accid){
		/* fonction qui doit vérifier si l'utilisateur est actuellement sur un comtpe d'essai.
		 * En entrée, l'id du compte à vérifier */
		
		//Connexion à la DB -- Local only
//		$con = mysqli_connect('localhost', 'root', '', 'kx_account_vbeta');		
//		$query = "SELECT taccid, accid, dateexp, dateexp_tstamp FROM tryaccounts WHERE accid=".$accid.";";
//		$result = mysqli_query($con, $query);
//                if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//		$status = mysqli_fetch_array($result);
                
                $QO = new QUERY("qryl4accountn17");
                $params = array( ':accid' => $accid );
                $status = $QO->execute($params);
		
//		if( $status === null ){
//			return false;	//Pas de match dans la base => pas un tryaccount
//		} else {
//			return true;	//Match dans la base => tryaccount repéré
//		}
                
                
		if (! $status ){
			return false;	//Pas de match dans la base => pas un tryaccount
		} else {
			return true;	//Match dans la base => tryaccount repéré
		}
		
	}
	
	public function handle_staycon_cookie(){
		/* fonction qui gère le cookie 'Rester connecté'.
		 * N'est lancée que si stayconn = 1 ofc. */
		
		
		if($_COOKIE['stayConCookie'] != ''){
			//Si le cookie existe, on le récupère et on connecte directement l'utilisateur et on lui autorise l'accès
			$stayConCookie = $_COOKIE['stayConCookie'];
			//fonction de connexion?
		}
	}
	
        public function is_account_locked($accid){
            /* fonction qui va vérifier si le compte vérifié est lock ou pas.
             * Entrée: ID du compte à vérifier 
             * Retour: false: unlocked | true: locked */
            
            //Connexion à la DB -- Local only
            //N.B.: Manipulation en mode objet plutôt que procédural pour des questions d'entraînement
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');

            /* Plutôt que d'aller chercher les time/datetime, on peut travailler directement avec les tstamps en les prenant en base */
//            $query = "SELECT accid, secu_lock_h_start, secu_lock_h_end, secu_lock_d_start, secu_lock_d_end FROM accounts WHERE accid = '". $accid ."';";
//            $result = $con->query($query);
//            if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//            $lockInfo = $result->fetch_array(MYSQLI_BOTH);
                 
            $QO = new QUERY("qryl4accountn14");
            $params = array( ':accid' => $accid );
            $lockInfo = $QO->execute($params);
            
            if( $lockInfo && count($lockInfo) && $lockInfo[0]['secu_lock_h_start'] != null ) {
                $lockInfo = $lockInfo[0];
                $now = time();
                if($lockInfo[0]['secu_lock_h_end'] != null && strtotime($lockInfo['secu_lock_h_start']) - 86400 < $now && strtotime($lockInfo['secu_lock_h_end']) > $now){
                    $status = true;
                } else {
                    $status = false;
                }
            } else if( $lockInfo && count($lockInfo) && $lockInfo[0]['secu_lock_d_start'] != null){
                $lockInfo = $lockInfo[0];
                $now = time();
                if($lockInfo['secu_lock_d_end'] != null && strtotime($lockInfo['secu_lock_d_start']) < $now && strtotime($lockInfo['secu_lock_d_end']) > $now){
                    $status = true;
                } else {
                    $status = false;
                }
            } else {
                $status = false;
            }

//            $con->close();
            return $status;
        }
        
        
        public function CheckPseudoExists($pseudo, $std_err_enabled = NULL, &$err_ref = NULL){
            if($pseudo == ''){
                $err_ref = 'Empty pseudo';
                return;
            } else {
                //On ne prend pas en compte la casse
                $stdpseudo = strtolower($pseudo);
                
//                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//                $rq = "SELECT accid FROM accounts WHERE accpseudo = '$stdpseudo';";
//                $result = $con->query($rq);
//                if($result == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//                $rlog = $result->fetch_array(MYSQLI_ASSOC);
                
                $QO = new QUERY("qryl4accountn15");
                $params = array( ':accpseudo' => $stdpseudo );
                $rlog = $QO->execute($params);
                
//                $rq2 = "SELECT resid FROM reserved_pseudos WHERE pseudo = '$stdpseudo';";
//                $result2 = $con->query($rq2);
//                if($result2 == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//                $rlog2 = $result->fetch_array(MYSQLI_ASSOC);
                
                $QO = new QUERY("qryl4accountn16");
                $params = array( ':pseudo' => $stdpseudo );
                $rlog2 = $QO->execute($params);

//                if( count($rlog) == 0 && count($rlog2) == 0 ){
//                    //Le pseudo est dispo
//                    return $pseudo;
//                } else {
//                    $err_ref = 'Pseudo deja utillise (acc)';
//                    return FALSE;
//                }

                if ( !count($rlog) && !count($rlog2) ){
                    //Le pseudo est dispo
                    return $pseudo;
                } else {
                    $err_ref = 'Pseudo deja utillise (acc)';
                    return FALSE;
                }
            }
        }
        
        public function hash_input_passwd($passwd){
            /*
             * TODO: Créer la fonction de hash du password.
             * Utilisation de PHPASS?
             * http://stackoverflow.com/questions/401656/secure-hash-and-salt-for-php-passwords
             * http://www.openwall.com/phpass/
             * http://sunnyis.me/blog/secure-passwords/
             * 
             */
            
            //On initialise notre objet
            $hasher = new srvc_PasswordHash_handler();
            $hasher->PasswordHash(8, FALSE);
            //On hashe le password
            $hashedPw = $hasher->HashPassword($passwd);
            //On vérifie que le hash fait plus de 20char de long (si non, il y a eu un problème)
            if(strlen($hashedPw) < 20){
                $this->get_or_signal_error(1, 'custom_err_account_pwd_hash_error', __FUNCTION__, __LINE__);
                return FALSE;
            } else {
                return $hashedPw;
            }
        }
        
        public function compare_hashed_passwd($user_input, $stored_hash){
            $hasher = new srvc_PasswordHash_handler();
            $hasher->PasswordHash(8, FALSE);
            $checked = $hasher->CheckPassword($user_input, $stored_hash);
            if($checked){
                return TRUE;
            } else {
                return FALSE;
            }
        }
	
        public function has_user_seen_standby_screen($accid){
          
            //Comment déterminer l'ID de la personne connectée?
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $accid = $con->real_escape_string($accid);
            $q = "SELECT acc_has_seen_creation_screen FROM accounts WHERE accid = '$accid';";
            $rslt = $con->query($q);
            if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
            $hsss = $rslt->fetch_array(MYSQLI_ASSOC);
            $con->close();
            if(!count($hsss)){
                $this->get_or_signal_error(1, 'custom_err_standbyscreencheck_query_error', __FUNCTION__, __LINE__);
                return TRUE;
            } else {
                if(intval($hsss['acc_has_seen_creation_screen']) == 1){
                    //L'utilisateur a vu l'écran
                    return TRUE;
                } else {
                    //L'utilisateur ne l'a pas vu
                    return FALSE;
                }
            }
        }
        
        public function isThirdCritEnabled($login){
            $CNX = new CONNECTION();
            $type = $CNX->login_detect($login);
            switch($type){
                case 'email':
                    $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                    $login = $con->real_escape_string($login);
                    $q = "SELECT a.secu_isThirdCritEna FROM accounts a INNER JOIN email_history eh ON a.accid = eh.accid WHERE eh.emailraw = '$login' AND date_EndEna IS NULL;";
                    $rslt = $con->query($q);
                    if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
                    $tce = $rslt->fetch_array(MYSQLI_ASSOC);
                    $con->close();
                    if(!count($tce)){
                        //Penser à décommenter quand les erreurs fonctionneront
                        //$this->get_or_signal_error(1, 'err_user_l4accn4', __FUNCTION__, __LINE__);
                        return FALSE;
                    } else {
                        if(intval($tce['secu_isThirdCritEna'])){
                            //Sécu activée
                            return TRUE;
                        } else {
                            return FALSE;
                        }
                    }
                    break;
                case 'pseudo':
                    $con2 = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                    $con2->set_charset('utf8');
                    //23-07-14 - PL : Je n'ai aucune idée pourquoi je dois décoder les caractères HTML ici et jamais sur les autres requêtes...
                    $login = $con2->real_escape_string($login);
                    $qr = html_entity_decode("SELECT secu_isThirdCritEna FROM accounts WHERE accpseudo = '$login';");
                    $rslt = $con2->query($qr);
                    if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
                    $tce = $rslt->fetch_array(MYSQLI_ASSOC);
                    $con2->close();
                    if(!count($tce)){
                        $this->get_or_signal_error(1, 'err_user_l4accn4', __FUNCTION__, __LINE__);
                        return FALSE;
                    } else {
                        if(intval($tce['secu_isThirdCritEna'])){
                            //Sécu activée
                            return TRUE;
                        } else {
                            return FALSE;
                        }
                    }
                    break;
                default:
                    $this->get_or_signal_error(1, 'err_user_l4accn4', __FUNCTION__, __LINE__);
                    break;
            }
        }
        
        /**
         * 
         * First step of password recovery
         * Returns TRUE if the INSERT INTO was done correctly
         * Returns FALSE if an error happened somewhere
         * @param string $email User's email
         * @param string $key (Hopefully) Unique generated key
         * @return boolean
         */
        public function passwd_reinit_request($email, $key){
            if(!isset($email) || $email == ''){
                $this->get_or_signal_error(1, 'custom_err_pwreinitrq_bad_email', __FUNCTION__, __LINE__);
                return FALSE;
            } else if(!isset($key) || $key == ''){
                $this->get_or_signal_error(1, 'custom_err_pwreinitrq_bad_key', __FUNCTION__, __LINE__);
                return FALSE;
            } else {
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                //On récupère l'ID du compte lié à cette @mail
                $con->set_charset('utf8');
                $email = $con->real_escape_string($email);
                $rq = "SELECT accid FROM email_history WHERE emailraw = '$email' AND date_EndEna IS NULL";
                $rslt_rq = $con->query($rq);
                if($rslt_rq == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
                $fid = $rslt_rq->fetch_array(MYSQLI_ASSOC);
                if(count($fid) != 1){
                    $this->get_or_signal_error(1, 'err_user_l4accn4', __FUNCTION__, __LINE__);
                    $con->close();
                    return FALSE;
                } else {
                    $accid = $fid['accid'];
                    //On détermine la date courante et la date d'expiration
                    $request_date = new DateTime();
                    $request_date_tstamp = $this->get_millitimestamp();
                    $request_date = $request_date->format('Y-m-d H:i:s');
                    //Délai d'expiration fixé à 1 mois
                    $expire_date = new DateTime();
                    $expire_date->modify('+1 month');
                    $expire_date_tstamp = strtotime($expire_date->format('Y-m-d H:i:s')) * 1000;
                    $expire_date = $expire_date->format('Y-m-d H:i:s');
                    
                    //On check si la clé n'existe pas déjà dans la base
                    $ckq = "SELECT * FROM reinit_operation WHERE reinit_key = '$key';";
                    $rslt_ckq = $con->query($ckq);
                    if($rslt_ckq == FALSE){
                        $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                        $con->close();
                        return FALSE;
                    } else {
                        $ckr = $rslt_ckq->fetch_array(MYSQLI_ASSOC);
                        if(count($ckr)){
                            //On a des résultats, donc erreur
                            return FALSE;
                        }
                    }
                    
                    //On insère dans reinit_operation
                    $rs = "INSERT INTO reinit_operation (reinit_key, accid, request_date, request_date_tstamp, expire_date, expire_date_tstamp)
                           VALUES ('$key', '$accid', '$request_date', '$request_date_tstamp', '$expire_date', '$expire_date_tstamp')";
                    $rslt_rs = $con->query($rs);
                    if($rslt_rs == FALSE){
                        $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                        $con->close();
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            }
        }
        
        /**
         * Second step of password recovery
         * 
         * @param type $key Unique key (id)
         * @param type $new_passwd New user password
         * @return boolean
         */
        public function passwd_reinit_change($key, $new_passwd){
            if((isset($key) && $key != '') | (isset($new_passwd) && preg_match_all($this->regexPasswdMini, $new_passwd))){
                //Vérification de la validité de la clé
                $now = new DateTime();
                $now_tstamp = $this->get_millitimestamp();
                $now = $now->format('Y-m-d H:i:s');
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $key = $con->real_escape_string($key);
                $rq = "SELECT * FROM reinit_operation WHERE reinit_key = '$key' AND expire_date_tstamp > '$now_tstamp' AND use_date_tstamp IS NULL AND cancel_date_tstamp IS NULL;";
                $rslt_rq = $con->query($rq);
                if($rslt_rq == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
                $bar = $rslt_rq->fetch_array();
                if(!count($bar)){
                    //Clé vraisemblablement expirée
                    $this->get_or_signal_error(1, 'err_user_l4accn5', __FUNCTION__, __LINE__);
                    $con->close();
                    return FALSE;
                } else {
                    //Clé valide, on peut faire les opérations de changement de passwd
                    $accid = $bar['accid'];
                    //Modifications sur reinit_operation
                    $rqo = "UPDATE reinit_operation SET use_date = '$now', use_date_tstamp = '$now_tstamp' WHERE reinit_key = '$key';";
                    $rslt_rqo = $con->query($rqo);
                    if($rslt_rqo == FALSE){
                        $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                        $con->close();
                        return FALSE;
                    }
                    //Modifications sur accounts
                    $hashedPw = $this->hash_input_passwd($new_passwd);
                    //Je ne peux pas utiliser on_alter_entity() à cause de la manière dont PHPASS hashe les pwd,
                    //car il faut l'input de base pour la comparaison.
                    //Ce problème ne devrait pas se poser pour les modifications 'normales' du password
                    //(depuis la gestion de compte) puisque l'utilisateur reprécise son input.
                    $accid = $con->real_escape_string($accid);
                    $QD = "UPDATE accounts SET acc_authpwd = '$hashedPw', authpwd_datemod = '$now', authpwd_datemod_tstamp = '$now_tstamp' WHERE accid = '$accid';";
                    $rslt_qd = $con->query($QD);
                    $con->close();
                    
                    
                    if($rslt_qd != FALSE){
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                }
            } else {
                return FALSE;
            }
        }
        
        public function passwd_reinit_check_key($key){
            if(isset($key) && $key != ''){
                $now = $this->get_millitimestamp();
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $key = $con->real_escape_string($key);
                $qr = "SELECT * FROM reinit_operation WHERE reinit_key = '$key' AND expire_date_tstamp > '$now' AND use_date_tstamp IS NULL AND cancel_date_tstamp IS NULL;";
                $chk = $con->query($qr);
                if($chk == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); $con->close(); return FALSE;}
                $rslt = $chk->fetch_array(MYSQLI_ASSOC);
                if(count($rslt)){
                    //On a une correspondance avec la requête, clé valide
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        
        public function passwd_reinit_cancel($key){
            if(isset($key) && $key != ''){
                $now = new DateTime();
                $now_tstamp = $now->getTimestamp();
                $now = $now->format('Y-m-d H:i:s');
                
//                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//                $qr = "UPDATE reinit_operation SET cancel_date = '$now', cancel_date_tstamp = '$now_tstamp' WHERE reinit_key = '$key';";
//                $rslt = $con->query($qr);
//                if($rslt == FALSE){
//                    $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
//                }
                
                
                $QO = new QUERY("qryl4accountn13");
                $params = array( ':key' => $key, ':now' => $now, ':now_tstamp' => $now_tstamp );
                $QO->execute($params);
                
            } else {
                $this->get_or_signal_error(1, 'err_user_l4accn4', __FUNCTION__, __LINE__);
            }
        }
        
        /**
         * Returns FALSE if pseudo available, TRUE if taken
         * @param type $pseudo
         * @return boolean
         * 
         * We start by looking if the pseudo is already taken by another user
         * Then we look if this pseudo is reserved
         */
        public function is_pseudo_taken($pseudo){
            if(!isset($pseudo) || $pseudo == ''){
                return 'EMPTY';
            }
            else if(isset($pseudo) && preg_match_all($this->regexNickname, $pseudo)){
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $pseudo = $con->real_escape_string($pseudo);
                $qy = "SELECT accid FROM accounts WHERE accpseudo = '$pseudo';";
                $result = $con->query($qy);
                if($result == FALSE){
                    $con->close();
                    $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                } else {
                    $bar = $result->fetch_array(MYSQLI_ASSOC);
                    if(count($bar) == 1){
                        $con->close();
                        return TRUE;
                    } else {
                        //Check dans la table de réservation
                        $qz = "SELECT resid FROM reserved_pseudos WHERE pseudo = '$pseudo';";
                        $result = $con->query($qz);
                        if($result == FALSE){
                            $con->close();
                            $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                        } else {
                            $foo = $result->fetch_array(MYSQLI_ASSOC);
                            if(count($foo) == 1){
                                $con->close();
                                return TRUE;
                            } else {
                                return FALSE;
                            }
                        }
                    }
                }
            } else {
                $this->get_or_signal_error(1, 'err_user_l4accn6', __FUNCTION__, __LINE__);
                return 'regex_error';
            }
        }
        
        public function city_suggestion($input, $names_only = NULL){
            if(isset($input)){
                $input = trim($input);
                $dataStore = array();
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $input = $con->real_escape_string($input);
                $q_full = "SELECT city_id, asciiname, country_code as ctr_name, city_pop FROM partner_gn_cities_5000_search WHERE asciiname LIKE '$input%';";
                $q_name = "SELECT asciiname FROM partner_gn_cities_5000_search WHERE asciiname LIKE '$input%';";
                if($names_only == TRUE){
                    $result = $con->query($q_name);
                } else {
                    $result = $con->query($q_full);
                }
                while($row = $result->fetch_assoc()){
                    $dataStore[] = $row;
                }
                return $dataStore;
            } else {
                $this->get_or_signal_error(1, 'err_user_l4accn6', __FUNCTION__, __LINE__);
                return FALSE;
            }
        }
        

        
        public function default_coverpic_linking($accid){
            $now = new DateTime();
            $now_tstamp = $now->getTimestamp();
            $now = $now->format('Y-m-d H:i:s');
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            //On sait quelle image sera celle par défaut, on peut donc mettre la valeur acp_id en dur
            $con->set_charset('utf8');
            $accid = $con->real_escape_string($accid);
            $qr = "INSERT INTO acccoverpics_history (acp_id, accid, date_Enafrom, date_Enafrom_tstamp) VALUES ('1', '$accid', '$now', '$now_tstamp')";
            $rslt = $con->query($qr);
            $con->close();
            if($rslt == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return -1;
            } else {
                return 1;
            }
        }
        
        
        /**
         * 
         * @param int $accid
         * ID of the account
         * @param int $gid
         * ID of the group
         */
        public function group_linking($accid, $gid){
            $datestart = new DateTime();
            $datestart_tstamp = $datestart->getTimestamp();
            $datestart = $datestart->format('Y-m-d H:i:s');
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $accid = $con->real_escape_string($accid);
            $gid = $con->real_escape_string(gid);
            $qr = "INSERT INTO abo_grp_histo (accid, gid, dateStart, dateStart_tstamp) VALUES ('$accid', '$gid', '$datestart', '$datestart_tstamp');";
            $rslt = $con->query($qr);
            $con->close();
            if($rslt == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return -1;
            } else {
                return 1;
            }
        }
        
        public function group_unlinking($accid, $gid){
            $dateend = new DateTime();
            $dateend_tstamp = $dateend->getTimestamp();
            $dateend = $dateend->format('Y-m-d H:i:s');
//            
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $qr = "UPDATE abo_grp_histo SET dateEnd = '$dateend', dateEnd_tstamp = '$dateend_tstamp' WHERE accid = '$accid' AND gid = '$gid' AND dateEnd IS NULL;";
//            $rslt = $con->query($qr);
//            $con->close();
//            if($rslt == FALSE){
//                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
//            }
            
            $QO = new QUERY("qryl4accountn12");
            $params = array( ':accid' => $accid, ':gid' => $gid, ':dateend' => $dateend , ':dateend_tstamp' => $dateend_tstamp );
            $QO->execute($params);
        }
        
        public function get_members_in_group($gid){
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $gid = $con->real_escape_string($gid);
            $qr = "SELECT * FROM abo_grp_histo WHERE gid = '$gid' AND dateEnd IS NULL;";
            $rslt = $con->query($qr);
            if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
            $rows = array();
            while($row = $rslt->fetch_array()){
                $rows[] = $row;
            }
            $con->close();
            return $rows;
        }
        
        /* MÉTHODES NÉCESSAIRES POUR LE CÔTÉ <LANDING> */
        
        /**
         * Will fetch all the CU data with aliases.
         * 
         * Returns an associative array containing the data (ueid, ufn, upsd, uppic, udl, uhref)
         * or FALSE if something went wrong.
         * 
         * @param int $accid
         * @return array|boolean
         */
        public function fetch_cu_data($accid){
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $accid = $con->real_escape_string($accid);
            $qr = "SELECT a.acc_eid AS ueid, p.ufullname AS ufn, a.accpseudo AS upsd, pic.picpath_to_ori AS uppic, a.acclang AS udl FROM accounts a INNER JOIN profils p ON a.pflid = p.pflid INNER JOIN pflpics_history pph ON p.pflid = pph.pflid INNER JOIN profilpictures pp ON pph.pflpicid = pp.pflpicid INNER JOIN pictures pic ON pp.picid = pic.picid WHERE a.accid = '$accid';";
            $rslt = $con->query($qr);
            $con->close();
            if($rslt == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return FALSE;
            } else if($rslt == NULL){
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return FALSE;
            }
            $fa = $rslt->fetch_array(MYSQLI_ASSOC);
            //On a les 5 premières données, on 'fabrique' la dernière: uhref
            //Template: "/@<pseudo>"
            $uhref = "/@".$fa['upsd'];
            $fa['uhref'] = $uhref;
            
            return $fa;
        }
        
        
        /**
         * Will fetch all the OW data with aliases.
         * 
         * Returns an associative array containing the data (ueid, ufn, upsd, uppic, udl, ucity, ucity_id, ucn, ucn_fn, uhref)
         * or FALSE if something went wrong.
         * 
         * @param int $accid
         * @return array|boolean
         */
        public function fetch_ow_data($accid){
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $accid = $con->real_escape_string($accid);
            $qr = "SELECT a.accid AS ueid, p.ufullname AS ufn, a.accpseudo AS upsd, pic.picpath_to_ori AS uppic, a.acclang AS udl, pct.asciiname AS ucity, pct.city_id AS ucity_id, co.ctr_code AS ucn, co.ctr_name AS ucn_fn FROM accounts a INNER JOIN profils p ON a.pflid = p.pflid INNER JOIN pflpics_history pph ON p.pflid = pph.pflid INNER JOIN profilpictures pp ON pph.pflpicid = pp.pflpicid INNER JOIN pictures pic ON pp.picid = pic.picid INNER JOIN partner_gn_cities_5000 pct ON p.ulvcity = pct.city_id INNER JOIN countries co ON pct.country_code = co.ctr_code WHERE a.accid = '$accid'";
            $rslt = $con->query($qr);
            $con->close();
            if($rslt == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return FALSE;
            } else if($rslt == NULL){
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return FALSE;
            }
            $fa = $rslt->fetch_array(MYSQLI_ASSOC);
            //On a toutes les données sauf une (uhref) donc on la fabrique:
            //Template: "/@<pseudo>"
            $uhref = "/@".$fa['upsd'];
            $fa['uhref'] = $uhref;
            
            return $fa;
        }
        
        public function social_area_fetch(){
//            $con = new mysqli('localhost', 'root', '', 'kx_commons_vbeta');
//            $con->set_charset('utf8');
//            $QO = "SELECT ctr_code, ctr_name FROM countries;";
//            $obj = $con->query($QO);
            
            //[NOTE @L.C. 20-08-14] J'ai mis :limit car je ne savais pas comment il allait réagir sans params. J'ai pas le temps de le tester. Le param ne dénature pas la requete
            $QO = new QUERY("qryl4accountn20");
            $params = array( ':limit' => 300 );
            $obj = $QO->execute($params);
            
            
//            if($obj == FALSE ){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//            $list = array();
//            while($toto = $obj->fetch_array(MYSQLI_ASSOC)){
//                $list[] = $toto;
//            }
//            
            if (! $obj ) {
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); 
                return 0;
            }
            
            $list = array();
            
            foreach ( $obj as $v ) {
                $list[] = $v;
            }
            
            return $list;
        }
        
        /* Méthodes utilisées pour la création de l'UEID */
        
        /**
         * Function used to create user external ID's based on their birthday (m-y), gender, and country AT THE TIME OF ACCOUNT CREATION.
         * 
         * In the output string, the character 'n' will be used as a delimiter between the "zones" of the ueid.
         * This 'n' cannot appear in a base 23 converted int and will be used for explode();
         * 
         * @param int $user_birthday
         * @param string $user_gender
         * @param string $user_country
         * @param int $user_id
         * @return string
         */
        public function create_ueid($user_birthday, $user_gender, $user_country, $user_id){
            //Récupération des données utiles de la date de naissance
            //N.B.: La date de naissance fournie est attendue en format timestamp
            //IMPORTANT: Utilisation du floor et de la division par 1000 pour retomber sur un format de timestamp classique (à la seconde),
            //et non à la miliseconde comme ils sont en base.
            $dt = new DateTime('@'.floor($user_birthday/1000));
            //Pour le mois, on le prend tel quel ('MM').
            $month = $dt->format('m');
            //Pour la date, on va prendre le premier chiffre du millénaire puis les deux derniers
            $fullyear = $dt->format('Y');
            //$yeararray = str_split($fullyear);
            //$formatedyear = $yeararray[0] . $yeararray[2] . $yeararray[3];
            
            
            //Genre de l'utilisateur. On pose M = 1 | F = 2.
            //En cas de problème, on aura -1.
            switch ($user_gender){
                case 'm':
                    $gender = 1;
                    break;
                case 'f':
                    $gender = 2;
                    break;
                default:
                    $gender = -1;
                    break;
            }
            
            //Traitement du pays (nécessaire car dans la base 'commons', la clé primaire de la table
            //correspond au code de ce pays (deux lettres) et pas à un int
            //On va chercher la correspondance entre la lettre et son numéro dans l'alphabet
            $alpha = ['a' => '01','b' => '02','c' => '03','d' => '04','e' => '05','f' => '06','g' => '07','h' => '08','i' => '09','j' => '10','k' => '11','l' => '12','m' => '13','n' => '14','o' => '15','p' => '16','q' => '17','r' => '18','s' => '19','t' => '20','u' => '21','v' => '22','w' => '23','x' => '24','y' => '25','z' => '26'];
            $country = '';
            $ctr_array = str_split($user_country);
            foreach($ctr_array as $letter){
                $country .= $alpha[$letter];
            }
            //Passage de user_id en base 23 pour normaliser le nombre de caractères
            $b23user_id = base_convert($user_id, 10, 23);
//            //On sait que si ID <= 9999999, la taille max de $b23user_id sera de 6 chars.
//            //Donc on normalise:
//            while(strlen($b23user_id) < 6){
//                $b23user_id = '0'.$b23user_id;
//            }
            
            //Génération de l'UEID en convertissant chacune des parties en b23.
            $ueid = base_convert($month, 10, 23) . 'n' . base_convert($fullyear, 10, 23) . 'n' . base_convert($gender, 10, 23) . 'n' . base_convert($country, 10, 23) . 'n' . $b23user_id;

            return $ueid;
        }
        
        
        /**
         * Will 'decode' the given UEID.
         * Returns an associative array ('date', 'country', 'accid').
         * @param string $ueid
         * @return array
         */
        public function read_ueid($ueid){
            //On commence par explode l'ueid car on sait que les 'n' sont délimiteurs
            $explodedUeid = explode('n', $ueid);

            //On sait que la première partie correspond au mois
            $month = base_convert($explodedUeid[0], 23, 10);
            
            //Seconde partie: année (sur 3 chiffres)
            $year = base_convert($explodedUeid[1], 23, 10);
            
            //Troisième: genre
            $gender = base_convert($explodedUeid[2], 23, 10);
            
            //Quatrième: pays
            $country = base_convert($explodedUeid[3], 23, 10);
            
            //Cinquième: accid
            $accid = base_convert($explodedUeid[4], 23, 10);
            
            //Préparation du tableau de retour
            $rVal = array();
            
            //Traitement des mois
            if(strlen($month) < 2){
                $month = '0'.$month;
            }
            $rVal['month'] = $month;
            
            //Pas de traitement nécessaire pour les années
            $rVal['year'] = $year;
            
            //Traitement du genre
            if(intval($gender) == 1){
                $rVal['gender'] = 'm';
            } else {
                $rVal['gender'] = 'f';
            }
            
            //Traitement du pays
            if(strlen($country) < 4){
                $country = '0'.$country;
            }
            $first_code = substr($country, 0, 2);
            $second_code = substr($country, 2, 2);
            
            $num = ['01' => 'a','02' => 'b','03' => 'c','04' => 'd','05' => 'e','06' => 'f','07' => 'g','08' => 'h','09' => 'i','10' => 'j','11' => 'k','12' => 'l','13' => 'm','14' => 'n','15' => 'o','16' => 'p','17' => 'q','18' => 'r','19' => 's','20' => 't','21' => 'u','22' => 'v','23' => 'w','24' => 'x','25' => 'y','26' => 'z',];
            $first_letter = $num[$first_code];
            $second_letter = $num[$second_code];
            $ctr_code = $first_letter . $second_letter;
            $rVal['country'] = $ctr_code;
            
            //Pas de traitement sur l'accid
            $rVal['accid'] = $accid;
            
            //Retour du tableau
            return $rVal;
            
            

//            //On sait que les 5 premiers chiffres correspondent à la date
//            $rawdate = substr($ueid, 0, 5);
//            $datearray = str_split($rawdate);
//            $month = $datearray[0] . $datearray[1];
//            if($datearray[2] == '1'){
//                $filler = '9';
//            } else if($datearray[2] == '2'){
//                $filler = '0';
//            } else {
//                $filler = '?';
//            }
//            $year = $datearray[2] . $filler . $datearray[3] . $datearray[4];
//            $dt = date_create_from_format('mY', $month . $year);
//            $extDate = $dt->format('m-Y');
//            
//            //On traite le reste de la chaîne
//            $rem = substr($ueid, 5);
//            
//            //On sait également que le chiffre suivant correspond au genre et est soit 1 soit 2
//            $genderNumber = substr($rem, 0, 1);
//            if($genderNumber == '1'){
//                $gender = 'm';
//            } else if($genderNumber == '2'){
//                $gender = 'f';
//            } else {
//                $gender = 'error';
//            }
//            
//            $rem = substr($rem, 1);
//            
//            //Ensuite vient le pays            
//            //On sait que le pays est codé sur 4 caractères (01->26 *2), donc
//            $country = substr($rem, 0, 4);
//            $first_code = substr($country, 0, 2);
//            $second_code = substr($country, 2, 2);
//            
//            $num = ['01' => 'a','02' => 'b','03' => 'c','04' => 'd','05' => 'e','06' => 'f','07' => 'g','08' => 'h','09' => 'i','10' => 'j','11' => 'k','12' => 'l','13' => 'm','14' => 'n','15' => 'o','16' => 'p','17' => 'q','18' => 'r','19' => 's','20' => 't','21' => 'u','22' => 'v','23' => 'w','24' => 'x','25' => 'y','26' => 'z',];
//            $first_letter = $num[$first_code];
//            $second_letter = $num[$second_code];
//            $ctr_code = $first_letter . $second_letter;
//            
//            $rem = substr($rem, 4);
//            
//            //Il ne reste que l'ID
//            $accid = $rem;
//            
//            $ra = [
//                'date' => $extDate,
//                'gender' => $gender,
//                'country' => $ctr_code,
//                'accid' => $accid
//            ];
//            
//            return $ra;
        }
        
        
        
        /* Méthodes utilisées pour la création du path de l'image [À transférer] */
        
        /**
         * Creates a name for the image, containing all the following information.
         * Output pattern: <ueid> . <machine_name> . <ieid> [. <width> . <height> .<quality>]
         * @param string $ueid User External ID
         * @param string $machine_name Name of the server
         * @param int $upload_timestamp Timestamp of the upload date of the image
         * @param int $picid Picture ID
         * @return string Image fullname
         */
        public function imgname_encode($ueid, $machine_name, $upload_timestamp, $picid, $width = NULL, $height = NULL, $quality = NULL){
            //Génération d'un 'IEID' (Image External ID) basé sur la date d'upload (timestamp), qu'il faut repasser en secondes (et pas millisecondes comme en base)
            //et l'ID de l'image (auto increment)
            //Pour la conversion du timestamp, on va simplement utiliser la base 23.
            //Ensuite, on concatène l'ID réel avec la même stratégie de séparation avec le caractère 'n' dans le cryptage de UEID
            //Mais cette fois-ci avec un autre caractère (pour ne pas confondre): 'o'.
            //On transforme aussi le picid en base 23.
            //[Pierre | 14/08/14] La création de l'ieid suis toujours cette logique, mais a été extrait d'ici pour en faire une fonction à part.
            $ieid = $this->create_ieid($upload_timestamp, $picid);
            
            //On va aussi coder le nom du serveur
            $secret_machine = $this->serverName_encode($machine_name);
            
            //Et on crée notre nom d'image
            //Si $message est set, on l'ajoute à la fin
            if($width && $height && $quality){
                $imgname = $ueid . '_' . $secret_machine . '_' . $ieid . '_' . $width . 'x' . $height . '_' . $quality;
                return $imgname;
            } else if($width && $height){
                $imgname = $ueid . '_' . $secret_machine . '_' . $ieid . '_' . $width . 'x' . $height;
                return $imgname;
            } else {
                $imgname = $ueid . '_' . $secret_machine . '_' . $ieid;
                return $imgname;
            }
        }
        
        /**
         * Reverse function of imgname_encode. Will output an associative array of the 4 parts
         * of the image name (ueid, machine, uploadtstamp and picid)
         * @param string $imgname Fullname of the image (minus extension)
         * @return array
         */
        public function imgname_decode($imgname){
            //On va décoder les informations contenues dans le nom de l'image à partir de celui-ci.
            //On commence par explode le string pour en récupérer les 3 parties
            $explodedName = explode('_', $imgname);
            
            $ueid = $explodedName[0];
            $raw_machine_name = $explodedName[1];
            $ieid = $explodedName[2];
            
            //On va traiter $ieid pour récupérer le timestamp d'uplaod et l'ID de l'image
            //On traite ensuite le tstamp pour le remettre en base 10
            //[Pierre | 14/08/14] La lecture de l'ieid se fait toujours selon cette logique, mais a été extrait pour en faire une fonction à part.
            $decIeid = $this->read_ieid($ieid);
            $b10tstamp = $decIeid['upload_tstamp_seconds'];
            $b10picid = $decIeid['picid'];
            
            //On va décoder le nom de la machine
            $machine_name = $this->serverName_decode($raw_machine_name);
            
            //Si on avait un $message set lors de l'encodage, il faut aussi le retrouver ici
            if(isset($explodedName[3]) && isset($explodedName[4])){
                //Ça veut dire qu'on a un message de set, avec 2 paramètres dedans.
                //On sait que dans le cas de 2 paramètres dans le message, ce sera toujours dans le même ordre
                $resize = $explodedName[3];
                $quality = $explodedName[4];
                
                $ra = [
                    'ueid' => $ueid,
                    'machine' => $machine_name,
                    'uploadtstamp' => $b10tstamp,
                    'picid' => $b10picid,
                    'resize' => $resize,
                    'quality' => $quality
                ];
            } else if(isset($explodedName[3]) && !isset($explodedName[4])){
                //Ici on a un message avec un seul paramètre, qui sera forcément resize
                $resize = $explodedName[3];
                $ra = [
                    'ueid' => $ueid,
                    'machine' => $machine_name,
                    'uploadtstamp' => $b10tstamp,
                    'picid' => $b10picid,
                    'resize' => $resize
                ];
            } else {
                //Pas de message
                $ra = [
                    'ueid' => $ueid,
                    'machine' => $machine_name,
                    'uploadtstamp' => $b10tstamp,
                    'picid' => $b10picid
                ];
            }
            return $ra;
        }
        
        
        /**
         * HS: Fonction de création de l'IEID, extraite de la fonction de création du nom de l'image
         * @param int $upload_tstamp Timestamp d'upload de l'image (millisecondes)
         * @param int $picid ID de l'image
         */
        public function create_ieid($upload_tstamp_ms, $picid){
            //On doit convertir les ms en s pour avoir des dates 'normales' au décode
            $sec_tstamp = floor(intval($upload_tstamp_ms)/1000);
            $b23tstamp = base_convert(intval($sec_tstamp), 10, 23);
            $b23picid = base_convert(intval($picid), 10, 23);
            $ieid = $b23tstamp . 'o' . $b23picid;
            return $ieid;
        }
        
        /**
         * Inverse de create_ieid(). Permet de retrouver les infos utilisées pour la création de l'ieid.
         * @param string $ieid IEID de l'image
         * @return array Tableau associatif contenant 'uplaod_tstamp_seconds' et 'picid'
         */
        public function read_ieid($ieid){
            $exp = explode('o', $ieid);
            $b23tstamp = $exp[0];
            $b23picid = $exp[1];
            
            $rArray = [
                'id'    => base_convert($b23picid, 23, 10),
                'time'  => base_convert($b23tstamp, 23, 10)
            ];
            
            return $rArray;
        }
        
        /**
         * Retrieve accid with known ueid.
         * Returns the ID if found, NULL if not.
         * Returns FALSE on error.
         * @param string $ueid User External ID
         * @return string|boolean|null
         */
        public function get_accid_from_ueid($ueid){
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $qr = "SELECT accid FROM accounts WHERE acc_eid = '$ueid';";
//            $rslt = $con->query($qr);
//            $con->close();
            
            $QO = new QUERY("qryl4accountn19");
            $params = array( ':acc_eid' => $ueid );
            $rslt = $QO->execute($params);
            
            
//            if($rslt == FALSE){
//                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
//                return FALSE;
//            } else {
//                $array = $rslt->fetch_array(MYSQLI_ASSOC);
//                $accid = $array['accid'];
//                return $accid;
//            }
            
            if( $rslt && count($rslt) && key_exists("accid", $rslt) && $rslt[0]["accid"] != "" ) {
                return $rslt[0]["accid"];
            } else {
                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                return FALSE;
            }
        }
        
        public function get_accid_from_login($login, $logintype){
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $login = $con->real_escape_string($login);
            
            switch($logintype){
                case 'email':
                    $query = "SELECT accid FROM email_history WHERE emailraw = '$login';";
                    break;
                case 'pseudo':
                    $query = "SELECT accid FROM accounts WHERE accpseudo = '$login';";
                    break;
            }
            
            $rslt = $con->query($query);
            $con->close();
            if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
            $idarray = $rslt->fetch_array(MYSQLI_ASSOC);
            return $idarray['accid'];
        }
        
        public function secu_hLockReset($accid){
            //On utilise un 'soft-reset' à 00:00:00 car on a besoin de ça pour comprendre les données renvoyées par le loader
            $null = '00:00:00';
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $query = "UPDATE accounts SET secu_lock_h_start = '$null', secu_lock_h_end = '$null' WHERE accid = '$accid';";
//            $rslt = $con->query($query);
//            $con->close();
//            if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
            
            $QO = new QUERY("qryl4accountn11");
            $params = array( ':accid' => $accid, ':null1' => $null, ':null2' => $null );
            $QO->execute($params);
        }
        
        public function secu_dLockReset($accid){
            //On utilise un 'soft-reset' à 0000-00-00 00:00:00 car on a besoin de ça pour comprendre les données renvoyées par le loader
            $null = '0000-00-00 00:00:00';
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $query = "UPDATE accounts SET secu_lock_d_start = '$null', secu_lock_d_end = '$null', secu_lock_d_start_tstamp = '$null', secu_lock_d_end_tstamp = '$null' WHERE accid = '$accid';";
//            $rslt = $con->query($query);
//            $con->close();
//            if($rslt == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
            
            $QO = new QUERY("qryl4accountn10");
            $params = array( ':accid' => $accid, ':null1' => $null, ':null2' => $null, ':null3' => $null, ':null4' => $null );
            $QO->execute($params);
        }
        
        /**
         * Will operate on the 'delacc_history' table to insert an account
         * deletion request.
         * @param int $accid
         * @param int $reason
         * @param string $comment
         */
        public function delete_account_request($accid, $reason, $comment){
            //Note: si il n'y a pas de commentaire, $comment est un string vide
            $now = new DateTime();
            $rq = new DateTime();
            $rq = $rq->format('Y-m-d H:i:s');
            $rq_tstamp = $this->get_millitimestamp();
            //Date de suppression effective = $now + 1 mois (arbitraire)
            $delete_date = $now->modify('+1 month');
            $delete_date = $delete_date->format('Y-m-d H:i:s');
            $delete_date_tstamp = strtotime($delete_date) * 1000;
            
            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            $con->set_charset('utf8');
            $accid = $con->real_escape_string($accid);
            $reason = $con->real_escape_string($reason);
            $comment = $con->real_escape_string($comment);
            $qr = "INSERT INTO delacc_history (accid, request_date, request_date_tstamp, deletion_date, deletion_date_tstamp, reason, comment) VALUES ('$accid', '$rq', '$rq_tstamp', '$delete_date', '$delete_date_tstamp', '$reason', '$comment');";
            $ctrl = $con->query($qr);
            if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
            //Si on arrive ici tout est OK
            return 1;
        }
        
        public function detect_delete_request($accid){
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $query = "SELECT todelete FROM accounts WHERE accid = '$accid';";
//            $ctrl = $con->query($query);
//            if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
//            $status = $ctrl->fetch_array(MYSQLI_ASSOC);
            
            $QO = new QUERY("qryl4accountn18");
            $params = array( ':accid' => $accid );
            $status = $QO->execute($params);
            
//            if( intval($status['todelete'] ) == 1){
//                return TRUE;
//            } else {
//                return FALSE;
//            }
            
            if ( $status && count($status) && intval($status[0]['todelete'] ) == 1 ){
                return TRUE;
            } else {
                return FALSE;
            }
            
            
        }
        
        public function cancel_delete_request($accid){
            $now = new DateTime();
            $now = $now->format('Y-m-d H:i:s');
            $now_tstamp = $this->get_millitimestamp();
            
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            $qr = "UPDATE delacc_history SET cancel_date = '$now', cancel_date_tstamp = '$now_tstamp' WHERE accid = '$accid' AND cancel_date IS NULL;";
//            $ctrl = $con->query($qr);
//            if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return FALSE;}
            
            $QO = new QUERY("qryl4accountn9");
            $params = array( ':accid' => $accid, ':now' => $now , ':now_tstamp' => $now_tstamp );
            $QO->execute($params);
            
            //Si on arrive là c'est qu'aucune erreur n'a été déclenchée par PDO
            return 1;
        }
        
        public function account_effective_deletion($accid){
            /* [P.L. | 07/08/14] Pour les données se trouvant dans ma partie de la base,
             * l'ordre de suppression dans les tables à respecter est plus ou moins le suivant:
             * #01 - abo_grp_histo           (accid)
             * #02 - acccoverpics_history    (accid)
             * #03 - email_history           (accid)
             * #04 - delacc_history          (accid)
             * #05 - reinit_operation        (accid)
             * #06 - login_log               (accid)
             * #07 - tryaccounts             (accid)     (potentiellement nul, mais à check quand même)
             * #08 - accountkey              (email)     (pas certain pour ça, mais je ne vois pas à quoi servirait de garder des couples clés-emails)
             * #09 - accounts                (accid)
             * #10 - pflpics_history         (pflid)
             * #11 - profile_history         (pflid)
             * #12 - profils                 (pflid)
             * 
             * -----------------------
             * 
             * [P.L. | 08/08/14] Note pour plus tard: C'est probablement dans cette fonction
             * que seront faits les calls à toutes les autres fonctions de suppression pour
             * le reste des informations du compte (photos, articles, tendances, ...)
             */
            
            //On récupère le pflid lié à accid
            $PFL = new PROFIL();
            $pflid = $PFL->get_profile_from_account($accid);
            
            //On récupère le mail
            $EMA = new EMAIL();
            $emailraw = $EMA->get_email_from_account($accid);
            
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
//            
//            //On vérifie que le compte existe avant de lancer la procédure de suppression
//            $queryExists = "SELECT * FROM accounts where accid = '$accid';";
//            $check = $con->query($queryExists);
            
            $QO = new QUERY("qryl4accountn1");
            $params = array( ':accid' => $accid );
            $check = $QO->execute($params);
            
//            
//            if($check == FALSE){
//                $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
//                return 'DELERROR_BAD_SEARCH_QUERY';
//            } else {
//                $ar = $check->fetch_array(MYSQLI_ASSOC);
//                if($ar == NULL){
//                    return 'DELERROR_ACC_NOT_FOUND';
//                }
//            }
            
            if (! $check )
                return 'DELERROR_ACC_NOT_FOUND';
            
            $queries = [
                'abo_grp_histo'         => "DELETE FROM abo_grp_histo WHERE accid = '$accid';",
                'acccoverpics_history'  => "DELETE FROM acccoverpics_history WHERE accid = '$accid';",
                'email_history'         => "DELETE FROM email_history WHERE accid = '$accid';",
                'delacc_history'        => "DELETE FROM delacc_history WHERE accid = '$accid';",
                'reinit_operation'      => "DELETE FROM reinit_operation WHERE accid = '$accid';",
                'login_log'             => "DELETE FROM login_log WHERE accid = '$accid';",
                'tryaccounts'           => "DELETE FROM tryaccounts WHERE accid = '$accid';",
                'accountkey'            => "DELETE FROM accountkey WHERE email = '$emailraw';",
                'accounts'              => "DELETE FROM accounts WHERE accid = '$accid';",
                'pflpics_history'       => "DELETE FROM pflpics_history WHERE pflid = '$pflid';",
                'profile_history'       => "DELETE FROM profile_history WHERE pflid = '$pflid';",
                'profils'               => "DELETE FROM profils WHERE pflid = '$pflid';"
            ];
            
            foreach($queries as $n => $qr){
                $rslt = $con->query($qr);
                if($rslt == FALSE){
                    //On s'arrête dès qu'une requête plante
                    $con->close();
                    $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                    return 'DELERROR_'.$n;
                }
            }
            
            //Si tout s'est bien passé, on fait la même chose sur l'autre base
            $PDACC = new PROD_ACC();
            $r = $PDACC->on_delete_entity($accid);
            if(isset($r) && is_array($r) && count($r)){
                //Opération OK
                $con->close();
                return TRUE;
            } else {
                $con->close();
                return FALSE;
            }
        }
        
        /**
         * Cette fonction est TEMPORAIRE, le temps que je répare la classe avec l'utilisation
         * des SetX(); comme il faut.
         * @param int $mod Modificateur de capital
         */
        public function setCapital_temp($mod){
            //On vérifie que |$mod| n'est pas trop grand
            if(intval($mod) < -15 || intval($mod) > 15){
                $this->get_or_signal_error(1, 'err_sys_l4accn4', __FUNCTION__, __LINE__);
                return 'ERR_SETCAPITAL_INVALID_MOD';
            }
            
            //On check si l'instance est chargée. Si non, on renvoie une erreur parce qu'on a besoin de ACCID
            if($this->is_instance_load == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4accn3', __FUNCTION__, __LINE__);
                return 'ERR_SETCAPITAL_INSTANCE_NOT_LOADED';
            } else {
                //On récupère les points actuels pour faire l'opération avec $mod
                $cap = $this->acc_capital;
                //Rappel: en base, la colonne acc_capital est de type UNSIGNED INT, donc on a pas de valeur négative.
                $newcap = (intval($cap) + intval($mod) < 0) ? 0 : intval($cap) + intval($mod);
                //On fait l'insertion du nouveau montant dans la DB
                $accid = $this->accid;
                
                $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
                $con->set_charset('utf8');
                $accid = $con->real_escape_string($accid);
                $qr = "UPDATE accounts SET acc_capital = '$newcap' WHERE accid = '$accid';";
                $check = $con->query($qr);
                if($check == FALSE){
                    //echo $con->error
                    $con->close();
                    $this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__);
                    return 'ERR_SETCAPITAL_BADQUERY';
                } else {
                    //Reload de l'entity
                    $this->load_entity(['accid' => $this->accid]);
                    return 'SUCCESS';
                }
            }
        }
        
        /**
         * Comme au dessus, cette fonction est aussi temporaire, et pour les mêmes raisons.
         * @return string
         */
        public function getCapital_temp(){
            //On check si l'instance est chargée. Si non, on renvoie une erreur parce qu'on a besoin de ACCID
            if($this->is_instance_load == FALSE){
                $this->get_or_signal_error(1, 'err_sys_l4accn3', __FUNCTION__, __LINE__);
                return 'ERR_SETCAPITAL_INSTANCE_NOT_LOADED';
            } else {
                return $this->acc_capital;
            }
        }
        
        public function staycon_management($staycon, $login){
            $CNX = new CONNECTION();
            $type = $CNX->login_detect($login);
            $accid = $this->get_accid_from_login($login, $type);
            if($accid != NULL){
                $this->load_entity(['accid' => $accid]);
            }
            
            
            if ( $staycon == 'true' && $this->staycon == 0 ) {
                $staycon = '1';
            } else if ( $staycon == 'false' && $this->staycon == 1 ) {
                $staycon = '0';
            } else return 0;
            
//            $con = new mysqli('10.0.205.70', 'dsly.dbmgr.2', 'TqrVille.69', 'tqr_account_vb1');
            
            $QO = new QUERY("qryl4accountn8");
            $params = array( ':accid' => $accid, ':staycon' => $staycon );
            $QO->execute($params);
            
            
//            
//            switch($staycon){
//                case 'true':
//                    if($this->staycon == 0){
//                        $qr = "UPDATE accounts SET staycon = '1' WHERE accid='$accid';";
//                        $ctrl = $con->query($qr);
//                        $con->close();
//                        if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//                    }
//                    return TRUE;
//                case 'false':
//                    if($this->staycon == 1){
//                        $qr = "UPDATE accounts SET staycon = '0' WHERE accid='$accid';";
//                        $ctrl = $con->query($qr);
//                        $con->close();
//                        if($ctrl == FALSE){$this->get_or_signal_error(1, 'err_sys_l4accn1', __FUNCTION__, __LINE__); return 0;}
//                    }
//                    return TRUE;
//                default:
//                    return 0;
//            }
        }
        
        /**
         * Conversion simple d'une chaîne quelconque en chaîne hexadécimale correspondante
         * @param string $string
         * @return string
         */
        public function str2hex($string){
            $hex = '';
            for ($i=0; $i<strlen($string); $i++){
                $ord = ord($string[$i]);
                $hexCode = dechex($ord);
                $hex .= substr('0'.$hexCode, -2);
            }
            return $hex;
        }
//        -- OLD --
//        public function str2hex($str){
//            $strArray = str_split($str);
//            $asciiArray = array();
//            $hexArray = array();
//            
//            foreach ($strArray as $char){
//                $asciiArray[] = ord($char);
//            }
//            
//            foreach ($asciiArray as $asciiChar){
//                $hexArray[] = base_convert(intval($asciiChar), 10, 16);
//            }
//            
//            $rVal = implode($hexArray);
//            return $rVal;
//        }
        
        /**
         * Conversion d'une chaîne héxadécimale en chaîne correspondante classique
         * @param type $hex_string
         * @return type
         */
        public function hex2str($hex){
            $string='';
            for ($i=0; $i < strlen($hex)-1; $i+=2){
                $string .= chr(hexdec($hex[$i].$hex[$i+1]));
            }
            return $string;
        }
//        -- OLD --
//        public function hex2str($hex_string){
//            $hexArray = str_split($hex_string, 2);
//            $asciiArray = array();
//            $strArray = array();
//            
//            foreach($hexArray as $hexChar){
//                $asciiArray[] = base_convert(intval($hexChar), 16, 10);
//            }
//            
//            foreach($asciiArray as $asciiChar){
//                $strArray[] = chr($asciiChar);
//            }
//            
//            $rVal = implode($strArray);
//            return $rVal;
//        }
        
        public function serverName_encode($servername){
            //On va faire un truc très simple, juste pour éviter que les gens ne fassent le rapprochement
            //Base 32. Tous les caractères usuels (a-zA-Z0-9) seront codés sur 2 caractères.
            $output = '';
            for($i = 0; $i < strlen($servername); $i++){
                $ord = ord($servername[$i]);
                $b32 = base_convert(intval($ord), 10, 32);
                $output .= $b32;
            }
            return $output;
        }
        
        public function serverName_decode($codename){
            //On sait que les éléments de la chaîne de base sont codés sur 2 caractères
            //Donc:
            $output = '';
            $strArray = str_split($codename, 2);
            foreach($strArray as $char){
                $b10 = base_convert($char, 32, 10);
                $chr = chr($b10);
                $output .= $chr;
            }
            return $output;
        }
        
        public function pseuso_validation($pseudo){
            if(isset($pseudo) && $pseudo != ""){
                if(preg_match($this->regexNickname, $pseudo)){
                    $pseudo = htmlentities($pseudo);
                    return $pseudo;
                } else {
                    return NULL;
                }
            } else {
                return NULL;
            }
        }

    /****************************************************************************************************************/
    /********************************************** GETTERS and SETTERS *********************************************/
    //[COM PERSO au 29/10/13]Beaucoup de remplacements de textes, en se basant sur des REGEX, ont été necessaires pour écrire les lignes qui suivent. LONGUE VIE AUX REGEX! 
    
    // <editor-fold defaultstate="collapsed" desc="Getters and Setters">
    // <editor-fold defaultstate="collapsed" desc="REGEX">
    public function getReg_input_pseudo() {
        return $this->reg_input_pseudo;
    }

    public function setReg_input_pseudo($reg_input_pseudo, bool $std_err_enbaled = NULL) {
        $k = "reg_input_pseudo";
        return $this->on_alter_entity($k, $reg_input_pseudo, $std_err_enbaled);
        $this->reg_input_pseudo = $reg_input_pseudo;
    }

    public function getReg_input_pwd() {
        return $this->reg_input_pwd;
    }

    public function setReg_input_pwd($reg_input_pwd, bool $std_err_enbaled = NULL) {
        $k = "reg_input_pwd";
        return $this->on_alter_entity($k, $reg_input_pwd, $std_err_enbaled);
        $this->reg_input_pwd = $reg_input_pwd;
    }

    public function getReg_input_caplol_tot() {
        return $this->reg_input_caplol_tot;
    }

    public function setReg_input_caplol_tot($reg_input_caplol_tot, bool $std_err_enbaled = NULL) {
        $k = "reg_input_caplol_tot";
        return $this->on_alter_entity($k, $reg_input_caplol_tot, $std_err_enbaled);
        $this->reg_input_caplol_tot = $reg_input_caplol_tot;
    }

    public function getReg_input_caplol_modif() {
        return $this->reg_input_caplol_modif;
    }

    public function setReg_input_caplol_modif($reg_input_caplol_modif, bool $std_err_enbaled = NULL) {
        $k = "reg_input_caplol_modif";
        return $this->on_alter_entity($k, $reg_input_caplol_modif, $std_err_enbaled);
        $this->reg_input_caplol_modif = $reg_input_caplol_modif;
    }
// </editor-fold>
    
    public function getAccid() {
        return $this->accid;
    }

    public function getIs_instance_load() {
        return $this->is_instance_load;
    }

    public function getIs_acc_validated_by_email() {
        return $this->is_acc_validated_by_email;
    }

    public function setIs_acc_validated_by_email(bool $is_acc_validated_by_email, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "is_acc_validated_by_email";
	$v = $is_acc_validated_by_email;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->is_acc_validated_by_email = $is_acc_validated_by_email;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAccpseudo() {
        return $this->accpseudo;
    }

    public function setAccpseudo($accpseudo, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "accpseudo";
	$v = $accpseudo;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->accpseudo = $accpseudo;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_pseudo_modif_date() {
        return $this->accpseudo_datemod;
    }

    public function setAcc_pseudo_modif_date($accpseudo_datemod, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "accpseudo_datemod";
	$v = $accpseudo_datemod;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->accpseudo_datemod = $accpseudo_datemod;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcclang() {
        return $this->acclang;
    }

    public function setAcclang($acclang, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acclang";
	$v = $acclang;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acclang = $acclang;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_lang_modif_date() {
        return $this->acclang_datemod;
    }

    public function setAcc_lang_modif_date($acclang_datemod, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acclang_datemod";
	$v = $acclang_datemod;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acclang_datemod = $acclang_datemod;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_authemail() {
        return $this->acc_authemail;
    }

    public function setAcc_authemail($acc_authemail, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acc_authemail";
	$v = $acc_authemail;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        
        //Verifier que Email n'est pas déjà attribué.
        $code = ($std_err_enbaled) ? 2 : 1; 
        $QO = new QUERY("qryl4accn2");
        $params = array( ':email' => $acc_authemail );

        $datas = $QO->execute($params);

        if ( count($datas) ) $this->get_or_signal_error ($code, "err_sys_l4accn11", __FUNCTION__, __LINE__);
        
        $this->acc_authemail = $acc_authemail;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_email_modif_date() {
        return $this->acc_email_modif_date;
    }

    public function setAcc_email_modif_date($acc_email_modif_date, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acc_email_modif_date";
	$v = $acc_email_modif_date;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acc_email_modif_date = $acc_email_modif_date;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_authpwd() {
        return $this->acc_authpwd;
    }

    public function setAcc_authpwd($acc_authpwd, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acc_authpwd";
	$v = $acc_authpwd;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acc_authpwd = $acc_authpwd;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_pwd_modif_date() {
        return $this->acc_pwd_modif_date;
    }

    public function setAcc_pwd_modif_date($acc_pwd_modif_date, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acc_pwd_modif_date";
	$v = $acc_pwd_modif_date;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acc_pwd_modif_date = $acc_pwd_modif_date;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_caplolp() {
        return $this->acc_capital;
    }

    public function setAcc_caplolp($acc_capital, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acc_capital";
	$v = $acc_capital;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acc_capital = $acc_capital;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getStayconnected() {
        return $this->staycon;
    }

    public function setStayconnected(bool $staycon, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "staycon";
	$v = $staycon;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->staycon = $staycon;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getAcc_is_banned() {
        return $this->acc_is_banned;
    }

    public function setAcc_is_banned(bool $acc_is_banned, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "acc_is_banned";
	$v = $acc_is_banned;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->acc_is_banned = $acc_is_banned;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getBandate() {
        return $this->bandate;
    }

    public function setBandate($bandate, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "bandate";
	$v = $bandate;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->bandate = $bandate;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getCreadate() {
        return $this->datecrea;
    }

    public function setCreadate($datecrea, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "datecrea";
	$v = $datecrea;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->datecrea = $datecrea;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getPflid() {
        return $this->pflid;
    }

    public function setPflid($pflid, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "pflid";
	$v = $pflid;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->pflid = $pflid;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getTodelete() {
        return $this->todelete;
    }
    
    //Le processus de supression est gérer par on_delete_entity
    protected function setTodelete(bool $todelete, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "todelete";
	$v = $todelete;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->todelete = $todelete;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }
    
    public function getTodel_event_date() {
        return $this->todel_event_date;
    }

    public function getCancel_todel_event_date() {
        return $this->cancel_todel_event_date;
    }

    public function getIs_third_crit_enabled() {
        return $this->is_third_crit_enabled;
    }

    public function setIs_third_crit_enabled(bool $is_third_crit_enabled, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "is_third_crit_enabled";
	$v = $is_third_crit_enabled;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->is_third_crit_enabled = $is_third_crit_enabled;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getIs_conx_with_pseudo_enabled() {
        return $this->is_conx_with_pseudo_enabled;
    }

    public function setIs_conx_with_pseudo_enabled(bool $is_conx_with_pseudo_enabled, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "is_conx_with_pseudo_enabled";
	$v = $is_conx_with_pseudo_enabled;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->is_conx_with_pseudo_enabled = $is_conx_with_pseudo_enabled;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
    }

    public function getCaplolp_modif() {
        return $this->caplolp_modif;
    }

    public function setCaplolp_modif($caplolp_modif, bool $std_err_enbaled = NULL, bool $write_and_load = NULL) {
        $k = "caplolp_modif";
	$v = $caplolp_modif;
        $err = $this->on_alter_entity($k, $v, $std_err_enbaled);

	if($err) return err;
        $this->caplolp_modif = $caplolp_modif;

	if ( $write_and_load ) $this->write_and_load_new_prop_on_alter ($k, $v);
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

    public function getAccpseudo_datemod() {
        return $this->accpseudo_datemod;
    }

    public function getAccpseudo_datemod_tstamp() {
        return $this->accpseudo_datemod_tstamp;
    }

    public function getAcclang_datemod() {
        return $this->acclang_datemod;
    }

    public function getAcclang_datemod_tstamp() {
        return $this->acclang_datemod_tstamp;
    }

    public function getAuthpwd_datemod() {
        return $this->authpwd_datemod;
    }

    public function getAuthpwd_datemod_tstamp() {
        return $this->authpwd_datemod_tstamp;
    }

    public function getAcc_capital() {
        return $this->acc_capital;
    }

    public function getStaycon() {
        return $this->staycon;
    }

    public function getDatecrea() {
        return $this->datecrea;
    }

    public function getDatecrea_tstamp() {
        return $this->datecrea_tstamp;
    }

    public function getGid() {
        return $this->gid;
    }

    public function getSecu_coWithPseudoEna() {
        return $this->secu_coWithPseudoEna;
    }

    public function getSecu_isThirdCritEna() {
        return $this->secu_isThirdCritEna;
    }

    public function getSecu_lock_h_start() {
        return $this->secu_lock_h_start;
    }

    public function getSecu_lock_h_end() {
        return $this->secu_lock_h_end;
    }

    public function getSecu_lock_d_start() {
        return $this->secu_lock_d_start;
    }

    public function getSecu_lock_d_end() {
        return $this->secu_lock_d_end;
    }

    public function getSecu_lock_h_start_tstamp() {
        return $this->secu_lock_h_start_tstamp;
    }

    public function getSecu_lock_h_end_tstamp() {
        return $this->secu_lock_h_end_tstamp;
    }

    public function getSecu_lock_d_start_tstamp() {
        return $this->secu_lock_d_start_tstamp;
    }

    public function getSecu_lock_d_end_tstamp() {
        return $this->secu_lock_d_end_tstamp;
    }

    public function getAcc_socialarea() {
        return $this->acc_socialarea;
    }

    public function getAcc_socialarea_datemod() {
        return $this->acc_socialarea_datemod;
    }

    public function getAcc_socialarea_datemod_tstamp() {
        return $this->acc_socialarea_datemod_tstamp;
    }

    public function setAccpseudo_datemod($accpseudo_datemod) {
        $this->accpseudo_datemod = $accpseudo_datemod;
    }

    public function setAccpseudo_datemod_tstamp($accpseudo_datemod_tstamp) {
        $this->accpseudo_datemod_tstamp = $accpseudo_datemod_tstamp;
    }

    public function setAcclang_datemod($acclang_datemod) {
        $this->acclang_datemod = $acclang_datemod;
    }

    public function setAcclang_datemod_tstamp($acclang_datemod_tstamp) {
        $this->acclang_datemod_tstamp = $acclang_datemod_tstamp;
    }

    public function setAuthpwd_datemod($authpwd_datemod) {
        $this->authpwd_datemod = $authpwd_datemod;
    }

    public function setAuthpwd_datemod_tstamp($authpwd_datemod_tstamp) {
        $this->authpwd_datemod_tstamp = $authpwd_datemod_tstamp;
    }

    public function setAcc_capital($acc_capital) {
        $this->acc_capital = $acc_capital;
    }

    public function setStaycon($staycon) {
        $this->staycon = $staycon;
    }

    public function setDatecrea($datecrea) {
        $this->datecrea = $datecrea;
    }

    public function setDatecrea_tstamp($datecrea_tstamp) {
        $this->datecrea_tstamp = $datecrea_tstamp;
    }

    public function setGid($gid) {
        $this->gid = $gid;
    }

    public function setSecu_coWithPseudoEna($secu_coWithPseudoEna) {
        $this->secu_coWithPseudoEna = $secu_coWithPseudoEna;
    }

    public function setSecu_isThirdCritEna($secu_isThirdCritEna) {
        $this->secu_isThirdCritEna = $secu_isThirdCritEna;
    }

    public function setSecu_lock_h_start($secu_lock_h_start) {
        $this->secu_lock_h_start = $secu_lock_h_start;
    }

    public function setSecu_lock_h_end($secu_lock_h_end) {
        $this->secu_lock_h_end = $secu_lock_h_end;
    }

    public function setSecu_lock_d_start($secu_lock_d_start) {
        $this->secu_lock_d_start = $secu_lock_d_start;
    }

    public function setSecu_lock_d_end($secu_lock_d_end) {
        $this->secu_lock_d_end = $secu_lock_d_end;
    }

    public function setSecu_lock_h_start_tstamp($secu_lock_h_start_tstamp) {
        $this->secu_lock_h_start_tstamp = $secu_lock_h_start_tstamp;
    }

    public function setSecu_lock_h_end_tstamp($secu_lock_h_end_tstamp) {
        $this->secu_lock_h_end_tstamp = $secu_lock_h_end_tstamp;
    }

    public function setSecu_lock_d_start_tstamp($secu_lock_d_start_tstamp) {
        $this->secu_lock_d_start_tstamp = $secu_lock_d_start_tstamp;
    }

    public function setSecu_lock_d_end_tstamp($secu_lock_d_end_tstamp) {
        $this->secu_lock_d_end_tstamp = $secu_lock_d_end_tstamp;
    }

    public function setAcc_socialarea($acc_socialarea) {
        $this->acc_socialarea = $acc_socialarea;
    }

    public function setAcc_socialarea_datemod($acc_socialarea_datemod) {
        $this->acc_socialarea_datemod = $acc_socialarea_datemod;
    }

    public function setAcc_socialarea_datemod_tstamp($acc_socialarea_datemod_tstamp) {
        $this->acc_socialarea_datemod_tstamp = $acc_socialarea_datemod_tstamp;
    }

    public function getacc_eid() {
        return $this->acc_eid;
    }
    //"Doublement" de la fonction pour pouvoir l'appeller via getUeid();
    public function getUeid() {
        return $this->acc_eid;
    }




// </editor-fold>
}

?>
