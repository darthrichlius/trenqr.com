<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class TQR_CONX extends MOTHER {
    
    
    /***************** RULES *****************/
    
    private $rgx_bd;
    private $bd_limit;
    private $rgx_gdr;
    private $rgx_psd;
    private $rgx_email;
    private $email_max;
    private $rgx_pwd;
    private $alwd_atpt;
    private $shld_coef;
    private $consec_time;
    private $LogOutTypes;
    
    
    /*
     * [DEPUIS 2-07-16]
     *      On considère comme RECEMMENT ACTIF, si une activité existe depuis moins de X temps
     */
    private $RECLY_MEANS_SSN;
    private $RECLY_MEANS_HIST_ACTV;
    private $RECLY_MEANS_HIST_PSV;
    private $RECLY_MEANS_HIST_FKS_CD0;
    private $RECLY_MEANS_HIST_FKS_CD2;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["accid","acc_eid","acc_grp","pfl_fn","pfl_bdate","pfl_bdate_tstamp","pfl_bdate_mod_rem","pfl_lvcity","pfl_nocity","pfl_gender","pfl_gender_mod_rem","pfl_dmod","pfl_dmod_tstamp","acc_psd","acc_psd_dmod","acc_psd_dmod_tstamp","acc_eml","acc_lang","acc_lang_dmod","acc_lang_dmod_tstamp","acc_pwd","acc_pwd_dmod","acc_pwd_dmod_tstamp","acc_crea_locip","acc_pflbio","acc_pflbio_dmod","acc_pflbio_dmod_tstamp","acc_dcrea","acc_dcrea_tstamp","secu_staycon","secu_coWithPsdEna","secu_isThirdCritEna"];
        $this->needed_to_loading_prop_keys = ["accid","acc_eid","acc_grp","pfl_fn","pfl_bdate","pfl_bdate_tstamp","pfl_bdate_mod_rem","pfl_lvcity","pfl_nocity","pfl_gender","pfl_gender_mod_rem","pfl_dmod","pfl_dmod_tstamp","acc_psd","acc_psd_dmod","acc_psd_dmod_tstamp","acc_eml","acc_lang","acc_lang_dmod","acc_lang_dmod_tstamp","acc_pwd","acc_pwd_dmod","acc_pwd_dmod_tstamp","acc_crea_locip","acc_pflbio","acc_pflbio_dmod","acc_pflbio_dmod_tstamp","acc_dcrea","acc_dcrea_tstamp","secu_staycon","secu_coWithPsdEna","secu_isThirdCritEna"];
        
        //too : TimeOfOperation
        $this->needed_to_create_prop_keys = ["cnx_login","cnx_pwd","cnx_ssid","cnx_locip","cnx_too"];
        
        /**************** RULES ****************/
        $this->rgx_bd = "/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
        $this->bd_limit = 12;
        $this->rgx_gdr = "/^(f|m)$/i";
        $this->rgx_psd = "/^[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i";
        $this->rgx_email = "/^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i";
        $this->email_max = 256;
        $this->rgx_pwd = "/^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°()\[\]\-@#$%:;=''\/\\¤]).{6,32}$/i";
        
        /*
         * Le nombre de tentatives avant que le compte ne tombe sous le feu d'un mode "shelled_mode".
         * Les tentatives prises en compte sont celles où le login est "bien formé" et connu.
         * De plus, il faut que le mot de passe soit "bien formé".
         * Enfin, il faut que la précédente tentative ait été effectuée avec la même SESSION.
         */
        $this->alwd_atpt = 3;
        
//        $mn = 6000; //DEV,TEST
        $mn = 60000;
        $hr = 3600000;
        /*
         * Le coefficient permet de déterminer la durée pendant laquelle un compte sera mis en mode "shelled".
         * Pour l'heure, le coefficient 6 a la même durée que 5. PLus tard on verra s'il faudra l'associé à une actionsupplémentaire.
         * Dans tous les cas, le coef. 6 permet d'indiquer que le stade critique d'une journée a été dépassé plus d'une fois.
         * Sans cela, on aurait manquer de cette information capitale.
         * 
         * Dans notre contexte, si le coefficient doit être superieur à 5, le coefficient est réinitialisé. Il est donc remis à 1.
         * Pour l'heure, aucun autre mécanisme n'est prévu. 
         */
        $this->shld_coef = [1 => 10*$mn, 2 => 20*$mn, 3 => $hr, 4 => 5*$hr, 5 => 24*$hr, 6 => 24*$hr];
        /*
         * Intervalle de temps durant lequel si x tentatives échouent, elles seront désignées "consécutives". 
         */
        $this->consec_time = 10*$mn;
        /*
         * Les types de déconnexion.
         * Ces codes sont fortement liés à ceux de base de données.
         * Tout changement dans l'un ou l'autre des scopes devra être retranscrit.
         */
        $this->LogOutTypes = [ "NO_LO_SE" => 1, "PRODSYS_THATS_IT" => 2, "USER_DECONX" => 3, "USER_WENT_TODEL" => 4, "USER_KEEP_TODEL" => 5, "TAKE_A_SNAP" => 6, "SHOULDNT_BE" => 7, "OVERWRITE_PRVS" => 8];
        
        /*
         * [DEPUIS 29-07-16]
         */
        $this->RECLY_MEANS_SSN = 1 * 3600000; //x Heure
        $this->RECLY_MEANS_HIST_ACTV = 10 * 60000; //x Minutes
        $this->RECLY_MEANS_HIST_PSV = 5 * 60000; //x Minutes
        $this->RECLY_MEANS_HIST_FKS_CD0 = 3 * 60000; //x Minutes
        $this->RECLY_MEANS_HIST_FKS_CD2 = 15 * 60000; //x Minutes
    }
    
    
    public function TryConx ($args) {
        /*
         * Permet de lancer une tentative de connexion.
         * Si la tentative est un succès, la méthode renvoie : "_AUTH_SUKX".
         * Sinon, elle renvoie un code qui devra être interprété par CALLER.
         * La méthode peut dans certains cas renvoyer un tableau. Le tableau se compose de la manière suivante :
         *  0: code
         *  1: extra_datas
         * 
         * ATTENTION : C'EST LA RESPONSABILITE DE CALLER DE VERIFIER SI UNE CONNEXION EXISTE DEJA.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : ["cnx_login","cnx_pwd","cnx_ssid","cnx_locip","cnx_too"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        $l = $args["cnx_login"];
        $p = $args["cnx_pwd"];
        
        //Vérification générale des identifiants
        $gc = $this->GeneralChecks($l,$p);

        $u_tab = NULL;
        if ( is_string($gc) ) {
            return $gc;
        } else if ( is_array($gc) && key_exists(0, $gc) && $gc[0] === "_AUTH_SSM" ) {
            
            //On détermine le temps durant lequel le compte doit encore resté en lode "shelled_mode"
            $u_tab = $gc[1];
            $smtr = $this->ShellM_TimeRemaining($u_tab["accid"]);
            return ["_AUTH_SSM",$smtr];
        } else {
            $u_tab  = $gc;
            $uid    = $u_tab["accid"];
            $upsd   = $u_tab["acc_psd"];
            $upwd   = $u_tab["acc_pwd"];
            $ufn    = $u_tab["pfl_fn"];
            $ueml   = $u_tab["emhy_email"];
            $tdl    = $u_tab["acc_todelete"];
            $strd_datas = [
                "upsd" => $upsd, 
                "upwd" => $upwd, 
                "ueml" => $ueml
            ];
        }
        
        $now = round(microtime(TRUE)*1000);
        
//        if ( intval($tdl) === 1 ) {
//            $c = "_AUTH_TD";
//        } else if ( intval($tdl) === 2 ) {
//            $c = "_AUTH_TD_PMLY";
//        }
        
        //On tente une connexion
        if ( !$this->AuthCheck($strd_datas,$l,$p) ) {
            $log_args = [
                "uid"       => $uid, 
                "login"     => $l, 
                "pwd"       => $p, 
                "locip"     => $args["cnx_locip"], 
                "ssid"      => $args["cnx_ssid"], 
                "result"    => 0, 
                "res_rprt"  => "FAILED", 
                "tstamp"    => $now
            ];
            
            //On inscrit la tentative dans le log des tentatives
            $atpt_id = $this->LogAttempt($log_args);
            
            //Si la tentative a échoué, on détermine si le compte doit être "shelled"
            $ssm = $this->ShouldShellMode($uid, $args["cnx_ssid"]);
            
            //On prépare le code d'erreur à renvoyer
            if ( $ssm === TRUE ) {
                //On met le compte sous mode "shelled"
                $smtr = $this->GoShell($uid,$atpt_id);
                
               /*
                * [DEPUIS 10-09-15] @author BOR
                *  On envoie un email au compte concerné
                */
               $this->report_ShellMode($u_tab);
                
               return ["_AUTH_SSM",$smtr];
            } else {
                return ["_AUTH_FAILED",$ssm];
            }
        } else {
            $log_args = [
                "uid"       => $uid, 
                "login"     => $l, 
                "pwd"       => $p, 
                "locip"     => $args["cnx_locip"], 
                "ssid"      => $args["cnx_ssid"], 
                "result"    => 1, 
                "res_rprt"  => "SUCCESS", 
                "tstamp"    => $now
            ];
            
            //On inscrit la tentative dans le log des tentatives
            $atpt_id = $this->LogAttempt($log_args);
            
            //On met à jour les occurrences "Shelled". Cela signifie que l'on fait sortir l'utilisateur du mode "shelled"
            $this->UnShell($uid);
            /*
            if ( intval($tdl) === 1 ) {
                $c = "_AUTH_TD";
            } else if ( intval($tdl) === 2 ) {
                $c = "_AUTH_TD_PMLY";
            }
            //*/
            
            $r = "";
            if ( intval($tdl) !== 2 ) {
                
                //On vérifie si le compte n'est pas en mode TO_DELETE
                $itd = ( intval($tdl) === 1 ) ? TRUE : FALSE;
            
                //On sélectionne le bon code
                $r = ( $itd ) ? "_AUTH_TD" : "_AUTH_SUKX";
                
                //Log de la connexion
                $psid = $this->Log_Signin($uid, $atpt_id, $args["cnx_ssid"]);
//                var_dump("LINE =>",__LINE__,"; DATAS => ",$psid);
                //On crée la connexion en faisant appel au service
                $A = new PROD_ACC();
                $utab = $A->on_read_entity([
                    "acc_eid" => $u_tab["acc_eid"], 
                    "OPTIONS" => [
                        "_WITH_TODEL"  => FALSE
                    ]
                ]);
//                var_dump("LINE =>",__LINE__,"; DATAS => ",$utab);
                /*
                 * [DEPUIS 04-09-15] @author BOR
                 */
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $utab) ) {
                    /*
                     * 1- On arrete le processus et on lance un processus de deconnexion.
                     * 2- On signale l'erreur. 
                     */
                    $this->TryLogOut($u_tab["acc_eid"], session_id());
                    return "__ERR_VOL_FAILED";
                }
                
                $CXH = new CONX_HANDLER();
//                exit();
    //            $CXH->try_logout();
    //            var_dump($_SESSION);
                $CXH->try_login($A, $psid);
//                var_dump("LINE =>",__LINE__,"; DATAS => ",$_SESSION);
                if (! $CXH->is_connected() ) {
                    return "__ERR_VOL_UXPTD";
                }
            } else {
                //On vérifie si le compte n'est pas en mode todelete
//                $r = ( intval($tdl) === 1 ) ? ["_AUTH_TD",[$ufn,$upsd]] : "_AUTH_TD_PMLY";
                $r = "_AUTH_TD_PMLY";
            }
            
            return $r;
        }
    }
    
    
    public function HandleToDelCase ($uid, $ssid, $obj) {
        /*
         * Permet de gérer le cas où l'utilisateur décide de la suite de sa connexion quand son compte est en to_delete.
         * Deux cas sont possibles :   
         *  (1) GIT (GOT_IT) => Je veux annuler la procédure de suppression et me connecter.
         *  (2) KPIT (KEEP_IT) => Je veux arreter la procédure de connexion et laisser mon compte en TO_DELETE .
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $TQACC = new TQR_ACCOUNT();
        
        //On vérifie que le Compte est bel et bien en mode "TO_DELETE"
        $exists = $TQACC->exists_with_id($uid);
//        var_dump($exists["acc_todelete"]);
//        exit();
        if ( intval($exists["acc_todelete"]) === 0 ) {
            return "__ERR_VOL_NOT_TD";
        } else if ( intval($exists["acc_todelete"]) === 2 ) {
            //Lancer une deconnexion
            $r = $this->TryLogOut($uid, $ssid, "SHOULDNT_BE");
            $b__ = $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r);
            $f__ = ( $b__ ) ? $r : "__ERR_VOL_DFNTLY_TD";
            
            return $f__;
        } else if ( intval($exists["acc_todelete"]) === -1 ) {
            //Lancer une deconnexion
            $r = $this->TryLogOut($uid, $ssid, "SHOULDNT_BE");
            $b__ = $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r);
            $f__ = ( $b__ ) ? $r : "__ERR_VOL_FATAL_UXPTD";
            
            return $f__;
        }
       
        //On vérifie si on est dans le cas GIT ou KPIT
        $r = NULL;
        switch (strtoupper($obj)) {
            case "GIT":
                    //Annuler la procédure de suppression
                    $r = $TQACC->onalter_CclToDelete($uid);
                break;
            case "KPIT":
                    //Lancer une deconnexion
//                    $r = $this->TryLogOut($uid,"USER_KEEP_TODEL");
                    //[NOTE 06-11-14] @author L.C. On ne crée une connexion qu'avec KPIT. Aussi, plus besoin de se déconneter.
//                    $r = TRUE;
                    /*
                     * [NOTE 06-11-14] @author L.C. 
                     * Ne pas mettre en place une connexion puis la créer à ce niveau est technique trop lourd dans notre contexte.
                     * Cette contrainte est née du fait que si on présente "GIT" et "KPIT" à l'utilisateur et qu'il recharge la page, 
                     * on est face à un problème. En effet, la connexion établie reste sans décision formelle. 
                     * Cela doit avoir pour effet de Rediriger la page vers la page par défaut de l'utilisateur.
                     * 
                     * Franchement, ce n'est pas un problème. C'est de la faute de l'utilisateur. S'il n'est pas content, il pourra toujours lancer une
                     * procédure de de TO_DELETE.
                     */
                    //Lancer une deconnexion
                    $r = $this->TryLogOut($uid, $ssid, "USER_KEEP_TODEL");
                break;
            default:
                    "__ERR_VOL_WRG_DATAS";
                break;
        }
        return $r;
    }
    
    
    public function checkPwdForUser ($pwd,$uid) {
        /*
         * Permet de vérifier si le mot de passe spécifier correspond à celui du compte passé en paramètre.
         * Idéal pour les opérations de confirmation de mot de passe dans GESTION DE COMPTE.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! $this->IsValidPwd($pwd) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        //On récupère le mot de passe de l'utilisateur s'il existe
        $TQACC = new TQR_ACCOUNT();
        $acc_tab = $TQACC->exists_with_id($uid);
        if ( !$acc_tab | intval($acc_tab["acc_todelete"]) !== 0 ) {
            return "__ERR_VOL_U_G";
        } 
        
        //On compare les mot de passe
        if (! $this->compare_hashed_passwd($pwd, $acc_tab["acc_pwd"]) ) {
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
    
    /******************************************************************************************************************************/
    /************************************************************ INNER or ALMOST *************************************************/
    
    private function GeneralChecks ($l,$p) {
        /*
         * Permet d'effectuer un ensemble de vérifications qui permettront de signifier à CALLER ...
         * ... s'il peut lancer une tentative de vérifications des identifiants.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        //Vérifier s'il s'agit d'une connexion avec email ou pseudo
        $lie = $this->IsEmailLike($l);
        if ( !isset($lie) ) {
            return "_AUTH_WRG";
        } else if ( $lie ) {
            $ltp = "email";
            $ivl = $this->IsValidEmail($l); 
        } else {
            $ltp = "pseudo";
            $ivl = $this->IsValidPseudo($l);
        }
        
        //On vérifie si le login est bien formé
        if (! $ivl ) {
            return "_AUTH_WRG";
        }
        
        //On vérifie si le mot de passe est bien formé
        if (! $this->IsValidPwd($p) ) {
            return "_AUTH_WRG";
        }
        
        //On récupère l'identifiant du Compte
        $ise = ( $ltp === "email" ) ? TRUE : FALSE;
        $u_tab = $this->AcquireUTab($l,$ise);
        if (! $u_tab ) {
            //U_G : User_Gone
            /*
             * [NOTE 03-11-14] @author L.C.
             * A cette heure je ne sais pas si renvoyer ce code d'erreur peut être une faille de sécurité.
             */
            return "_AUTH_U_G";
        } else {
            $uid = $u_tab["accid"];
        }
        
        //On vérifie que le compte lié au login n'est pas "shelled"
        if ( $this->IsShelled($uid) ) {
            //On récupère les données sur la situation de "shelled". On récupère notamment : coef, la date de debut et la date de fin du mode "shelled"
            $si = $this->GetShellInfos($uid);
            
            if (! $si ) {
                return "__ERR_VOL_UXPTD";
            } else  {
                return ["_AUTH_SSM",$u_tab];
            }
        }
        
        //On vérifie que le type de login est autorisé par la configuration de sécurité du compte lié
        if ( $ltp === "pseudo" && !$this->IsLoginAuthEnabled($uid) ) {
            return "_AUTH_LGTYP";
        }
        
        return $u_tab;
    }
    
    
    /********************************************************************************************************************************************/
    /********************************************************* PRIVATE SCOPE ********************************************************************/
    
    private function IsEmailLike ($l) {
        /*
         * Permet de vérifier si la chaine passée en paramètre ressemble à un email.
         * Dans notre contexten, cela permet de savoir s'il faudra le considère comme un email ou un pseudo.
         * En effet, un login peut être un email ou un pseudo.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if (! is_string($l) ) {
            return;
        }
        
        return substr_count($l,'@');
    }
    
    private function IsValidEmail ($l) {
        /*
         * Détermine si la chaine passé en paramètre est une adresse email valide.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if (! is_string($l) ) {
            return;
        }
        
        return ( preg_match($this->rgx_email, $l) ) ? TRUE : FALSE;
    }
    
    private function IsValidPseudo ($l) {
        /*
         * Détermine si la chaine passé en paramètre est un pseudo valide.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if (! is_string($l) ) {
            return;
        }
        
        return ( preg_match($this->rgx_psd, $l) ) ? TRUE : FALSE;
    }
    
    private function IsValidPwd ($p) {
        /*
         * Détermine si la chaine passé en paramètre est un mot de passe valide.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if (! is_string($p) ) {
            return;
        }
        
        return ( preg_match($this->rgx_pwd, $p) ) ? TRUE : FALSE;
    }
    
    private function AcquireUTab ($l,$ise) {
        /*
         * Permet de récupérer les informations relatifs à l'utilisateur dont le login est passé en paramètre.
         * Selon le cas du login, on adapte notre requete.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if (! is_string($l) ) {
            return;
        }
        $now = round(microtime(TRUE)*1000);
        $qy = ( $ise ) ? $QO = "qryl4tqraccn7" : "qryl4tqraccn8";
        $QO = new QUERY($qy);
        
        $params = array(':login' => $l, ':now' => $now);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
        
    }
    
    private function IsShelled ($i) {
        /*
         * Détermine si le compte est en mode "shelled_mode".
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4tqrcnxn1");
        $params = array(":uid" => $i, ":now1" => $now, ":now2" => $now);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    private function GetShellInfos ($i) {
        /*
         * Récupère les informations autour du "shelled_mode" .
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4tqrcnxn1");
        $params = array(":uid" => $i, ":now1" => $now, ":now2" => $now);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }
    
    private function IsLoginAuthEnabled ($i) {
        /*
         * Vérifie si la configuration de sécurité du compte permet une autthentification avec ce type de compte.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        $QO = new QUERY("qryl4tqrcnxn2");
        $params = array(":uid" => $i);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return;
        } else {
            return ( intval($datas[0]["cwp"]) === 1 ) ? TRUE : FALSE;
        }
    }
    
    private function AuthCheck ($strd_datas, $gvn_l, $gvn_p) {
        /*
         * Permet de vérifier si le couple login/mot-de-pass fourni autorise une connexion.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        //On vérifie si le mot de passe
//        $HR = new srvc_PasswordHash_handler();
//        $HR->PasswordHash(8, FALSE);
//        $tp_pwd = $HR->HashPassword($gvn_p);
        if (! $this->compare_hashed_passwd($gvn_p, $strd_datas["upwd"]) ) {
            return FALSE;
        }
        
        $ise = ( $this->IsEmailLike($gvn_l) ) ? TRUE : FALSE;
        if ( $ise ) {
            return ( strtolower($gvn_l) === strtolower($strd_datas["ueml"]) ) ? TRUE : FALSE;
        } else {
            return ( strtolower($gvn_l) === strtolower($strd_datas["upsd"]) ) ? TRUE : FALSE;
        } 
            
    }
    
    public function compare_hashed_passwd($user_input, $stored_hash) {
        $HR = new srvc_PasswordHash_handler();
        $HR->PasswordHash(8, FALSE);
        $checked = $HR->CheckPassword($user_input, $stored_hash);
        
        return ( $checked ) ? TRUE : FALSE;
    }
    /*
    private function IsToDelete ($i) {
        
    }
    
    private function RemainingAttempts ($i) {
        
    }
    //*/
    private function LogAttempt ($args) {
        /*
         * Permet d'enregistrer les tentatives de connexion.
         * ATTENTION : Pour des rainsons de sécurité, les mots de passes sont cryptés même pour le log.
         * 
         * NOTE : Pour savoir ce qu'on entend pas "tentative de connexion" voir le constructeur.
         */
        
        //On crypte le mot de passe 
        $HR = new srvc_PasswordHash_handler();
        $HR->PasswordHash(8, FALSE);
        $hpwd = $HR->HashPassword($args["pwd"]);
        
        //On vérifie la présence d'un commentaire
        $comment = ( key_exists("comment", $args) && !empty($args["comment"]) ) ? $args["comment"] : "";
        
        //[":login",":pwd",":locip",":ssid",":result",":res_rprt","tstamp"];
        $QO = new QUERY("qryl4tqrcnxn3");
        $params = array(":uid" => $args["uid"], ":login" => $args["login"], ":pwd" => $hpwd, ":locip" => $args["locip"], ":ssid" => $args["ssid"], ":result" => $args["result"], ":res_rprt" => $args["res_rprt"], ":tstamp" => $args["tstamp"], ":comment" => $comment);
        $atpt_id = $QO->execute($params);
        
        return $atpt_id;
    }
    
    private function ShouldShellMode ($i,$ssid) {
        /*
         * La méthode est appelée pour déterminer si, selon le log des tentatives de connexion, le compte devrait être placé en mode "shelled_mode".
         * La méthode ne prend en paramètre que l'identifiant du compte concerné.
         * 
         * La méthode retourne :
         *  TRUE: Si le Compte devrait être mis sous "shelled_mode".
         *  NOMBRE_ESSAIS_RESTANTS : le nombre de tentatives restantes avant d'être placé sous "shelled_mode".
         */
//        var_dump($i,$ssid,$this->alwd_atpt);
        //On récupre les x dernières tentatives
        $QO = new QUERY("qryl4tqrcnxn4");
        $params = array(":uid" => $i, ":ssid" => $ssid, ":limit" => $this->alwd_atpt);
        $datas = $QO->execute($params);
//        var_dump($datas);
        //On vérifies si elles sont toutes fausses
        if ( $datas ) {
            //On vérifie si elles sont toutes sous format "ECHEC"
            $last = $datas[0];
            $first = $datas[count($datas)-1];
            
            //enb: ErrorNumber; sst: SeSsionTable
            $enb = $cn = 0;
            foreach ( $datas as $v ) {
                ++$cn;
                if ( intval($v["atpt_result"]) === 1 ) {
                    //Cela permet de ne pas comptabiliser toutes les tentatives effectuées avant un SUKX
                    break;
                } else if ( 
                        intval($v["atpt_result"]) === 0 
                        && 
                        (
                            ( intval($last["atptid"]) === intval($first["atptid"]) )
                            || ( count($datas) < 3 )
                            || ( count($datas) === 3 && ( ($last["atpt_date_tstamp"]-$v["atpt_date_tstamp"]) < $this->consec_time ) )
                        )
                    ) 
                {
                    ++$enb;
                }  
                
                /*
                if ( !isset($sst) && !array_key_exists($v["atpt_ssid"], $sst) ) {
                    $sst[$v["atpt_ssid"]] = 0;
                } else if ( array_key_exists($v["atpt_ssid"], $sst) ) {
                    $sst[$v["atpt_ssid"]] = ++$sst[$v["atpt_ssid"]];
                } 
                //*/
            }
//            var_dump($datas,$last,$first, $enb);
//            var_dump($datas, $enb);
            
            $t1 = $last["atpt_date_tstamp"] - $first["atpt_date_tstamp"];
            $now = round(microtime(TRUE)*1000);
            $t2 = $now - $last["atpt_date_tstamp"];
            
//            var_dump($enb, $t1, $t2, $this->consec_time);
//            exit();
            
            /*
             * On vérifie si le nombre d'échec autorisé a été atteint ou dépassé.
             * 
             * Pour ce faire, on vérifie si les echecs peuvent être considérés comme consécutifs.
             * Des tentatives échouées sont considérées comme "consécutives" si :
             *  (1) Elles ont le numéro de session_id.
             *  (2) Si elles ont été réalisées dans un espace de x temps.
             *  (3) La différence de temps entre maintenant et le dernier mauvais essai et inferieur à x temps
             *  (4) Il doit y avoir 3 mauvais essais à la suite
             */
            if ( $enb === $this->alwd_atpt && $t1 < $this->consec_time &&  $t2 < $this->consec_time ) {
                return TRUE;
            } else if ( $enb > 0 && $enb < $this->alwd_atpt && $t1 < $this->consec_time &&  $t2 < $this->consec_time) {
                $ra = $this->alwd_atpt - $enb;
                return $ra;
//                return $this->alwd_atpt;
            } else {
                $ra = $this->alwd_atpt - $enb;
                return $ra;
            }
        } else {
            return $this->alwd_atpt;
        }
        
    }
    
    private function GoShell ($i,$atpt) {
        /*
         * Met le compte sous "shelled_mode" et renvoie le temps pendant lequel le compte le restera.
         * Ce temps dépend du coefficient sm.
         */
        
        //On récupère le coefficient faisant référence à la derniere opération de shelled non terminée (si il existe).
        $QO = new QUERY("qryl4tqrcnxn6");
        $params = array(":uid" => $i);
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            $coef = intval($datas[0]["sat_coef"]);
            $coef = ( $coef === 6 ) ? 6 : ++$coef;
        } else {
            $coef = 1;
        }
        
        $t = $this->shld_coef[$coef];
//        echo "COEF. => ".$t;
        $start_tstamp = round(microtime(TRUE)*1000);
        $end_tstamp = $start_tstamp + $t;
        $end = date("Y-m-d H:i:s", $end_tstamp/1000);
        
        //On inscrit le compte dans la liste des "shelled_accounts"
        $QO = new QUERY("qryl4tqrcnxn7");
        $params = array(
            ":accid"        => $i, 
            ":coef"         => $coef, 
            ":sdate_tstamp" => $start_tstamp, 
            ":edate"        => $end, 
            ":edate_tstamp" => $end_tstamp, 
            ":atpt"         => $atpt
        );
        $QO->execute($params);
        
        return $t;
        
    }
    
    private function report_ShellMode($acc_tab) {
        /*
         * Permet d'envoyer un email au propriétaire du compte lui signifiant que son Compte a été mis en mode "En attente de suppression".
         * On se repère par rapport à l'identifiant de l'opération de suppression.
         */
         $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $acc_tab, TRUE);
//         var_dump("LINE => ",__LINE__,"; DATAS => ",$acc_tab);
         //On recupère l'email d'envoi
         $TQAC = new TQR_ACCOUNT();
         $exp = $TQAC->onread_getSenderMail("GO_TO_SHELL");
         if (! $exp ) {
             return;
         }
         
         $EMH = new EMAILAC_HANDLER();
         $args_eml = [
            "exp"       => htmlspecialchars_decode($exp),
//            "rcpt" => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
            "rcpt"      => $acc_tab["emhy_email"],
            "rcpt_uid"  => $acc_tab["accid"],
            "catg"      => "USER_ACTION"
        ];
         
//        var_dump("LINE => ",__LINE__,"; DATAS => ",$args_eml);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
         
                
        $args_eml_marks = [
            "fullname"                  => $acc_tab["pfl_fn"],
            "trenqr_http_root"          => HTTP_RACINE,
            "trenqr_login_link"         => HTTP_RACINE."/login",
            "trenqr_start_rcvy_link"    => HTTP_RACINE."/recovery/password",
            "trenqr_prod_img_root"      => WOS_SYSDIR_PRODIMAGE
        ];
//        var_dump("LINE => ",__LINE__,"; DATAS => ",$args_eml_marks);
        $r_ = $EMH->emac_send_email_via_model("emdl_toshelledn1", "fr", $args_eml, $args_eml_marks);
//        var_dump("LINE => ",__LINE__,"; DATAS => ",$r_);
        if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
            return "__ERR_VOL_FAILED";
        }
        
        return TRUE;
         
    }
    
    private function ShellM_TimeRemaining ($i) {
        /*
         * Détermine le temps restant avant que le Compte ne sorte du mode "shelled_mode".
         * Cette méthode permet en outre d'aider à faire sortir un Compte du mode "shelled"
         */
        $QO = new QUERY("qryl4tqrcnxn9");
        $params = array(":uid" => $i, ":limit" => 1);
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return FALSE;
        } else {
            $end_tm = $datas[0]["sat_edate_tstamp"];
            $now = round(microtime(TRUE)*1000);
            $t = $now - $end_tm;
//            var_dump($now,$end_tm,$t);
            return ( $t >= 0 ) ? 0 : $t*-1; 
        }
                
    }
    
    private function UnShell ($i) {
        /*
         * Fait sortir le compte du mode "shelled_mode".
         * Pour cela on rend obselete toutes les occurrences qui ne l'étaient pas déjà
         */
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tqrcnxn8");
        $params = array(":accid" => $i, ":tstamp" => $now);
        $QO->execute($params);
        
        return TRUE;
    }
    
    private function Log_Signin ($uid, $atptid, $ssid) {
        /*
         * Enregistre une opération de connexion.
         * La méthode renvoie l'identifiant.
         */
        
        //Pour des raisons de fiabilité, on vérifie qu'il n'existe pas déjà une connexion active
        $QO = new QUERY("qryl4tqrcnxn11");
        $params = array(":uid" => $uid);
        $datas = $QO->execute($params);
        
        $now = round(microtime(TRUE)*1000);
        
        /*
         * [NOTE 07-11-14] @author L.C.
         * J'ai fait un choix suivant le cas suivant : 
         * "On veut se connecter mais une connexion est déjà ouverte, que faire ?".
         *  (1) Cloturer la précédente et ouvrir une nouvelle
         *  (2) Déclencher une erreur
         * Ce choix se propose d'être le plus consensuel et le moins agressif pour l'utilisateur.
         * Ce cas n'est pas à l'heure actuelle t, un véritable problème. On pourrait à la limite le log dans les avertissements (Au niveau du futur système de gestion des évènements silencieux).
         * On donc cloturer toutes les Sessions actives et en créer une nouvelle
         */
        if ( $datas > 1 ) {
            //On annule la ou les précédentes connexions
            $QO = new QUERY("qryl4tqrcnxn13");
            $params = array(
                ":uid"      => $uid, 
                ":ssid"     => $ssid, 
                ":lotid"    => $this->LogOutTypes["OVERWRITE_PRVS"], 
                ":tstamp"   => $now
            );
            $datas = $QO->execute($params);
        }
        
        $QO = new QUERY("qryl4tqrcnxn10");
        $params = array(":atptid" => $atptid, ":tstamp" => $now);
        $id = $QO->execute($params);
        
        return $id;
    }
    
    public function TryLogOut ($uid, $ssid, $obj = "USER_DECONX") {
        /*
         * Permet de gérer toutes les opérations relatives à une déconnexion.
         * Cette déconnexion peut être décidée par l'utilisateur ou par le système.
         * 
         * [NOTE 30-11-14] @author L.C.
         * J'ai modifié la méthode car elle ne répondait pas aux attentes.
         * En effet, il manquait la session en paramètre qui permettait de ne "tuer" que la SESSION désigné par un numéro de SESSION précis.
         * 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$ssid]);
        
        $CNXH = new CONX_HANDLER();
        //* //COMMENTER SI NECESSAIRE POUR : DEV, TEST, DEBUG
        if (! $CNXH->is_connected() ) {
            return "__ERR_VOL_NOT_CNTD";
        }
        //*/
        $obj = strtoupper($obj);
        $lotid = NULL;
        switch ($obj) {
            case "NO_LO_SE" :
            case "PRODSYS_THATS_IT" :
            case "USER_DECONX" :
            case "USER_WENT_TODEL" :
            case "USER_KEEP_TODEL" :
            case "TAKE_A_SNAP" :
            case "SHOULDNT_BE" :
                    $lotid = $this->LogOutTypes[$obj];
                break;
            default :
                return "__ERR_VOL_WRG_DATAS";
        }
        
        //On récupère la dernière ligne de connexion selon SID et UID
        $QO = new QUERY("qryl4tqrcnxn15");
        $params = array(
            ":uid"  => $uid, 
            ":ssid" => $ssid
        );
        $cnx_tab = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$uid,$ssid,$cnx_tab]);
//        exit();
        
        $psid = NULL;
        //On s'assure qu'une ligne existe belle et bien
        if ( !$cnx_tab || count($cnx_tab) > 1 ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $cnx_tab,"v_d");
            return "__ERR_VOL_FATAL_UXPTD";
        } else {
            $psid = $cnx_tab[0]["llog_psid"];
        }
        
        //Annuler la connexion au niveau de la base de données 
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tqrcnxn12");
        $params = array(
            ":psid"     => $psid, 
            ":lotid"    => $lotid, 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        //Arreter la connexion au niveau du service
        if (! $CNXH->try_logout() ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,"v_d");
            return "__ERR_VOL_FATAL_UXPTD";
        }
        
        return TRUE;
    }
    
    /*
     * TODO : Cette méthode provient d'un Copier-Coller. Il faut la réanalyser pour qu'elle coiencide avec ses futures missions.
     * Se déconnecter de tous les endroits. Cela permettre de couper toutes les SESSION à partir d'une seule déconnexion.
     * Cette fonctionnalité est autant dangereuse qu'utile pour l'utilisateur. S'il se fait pirater le pirate pourra le déconnecter.
     * Mais le contraire aussi est vrai.
     * La fonctionnalité sera DISABLE de base
     */
    public function TryLogOutAll ($uid, $obj = "USER_DECONX") {
        /*
         * Permet de gérer toutes les opérations relatives à une déconnexion.
         * Cette déconnexion peut être décidée par l'utilisateur ou par le système.
         *
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid,$ssid]);
        
        $CNXH = new CONX_HANDLER();
        //* //COMMENTER SI NECESSAIRE POUR : DEV, TEST, DEBUG
        if (! $CNXH->is_connected() ) {
            return "__ERR_VOL_NOT_CNTD";
        }
        //*/
        $obj = strtoupper($obj);
        $lotid = NULL;
        switch ($obj) {
            case "NO_LO_SE" :
            case "PRODSYS_THATS_IT" :
            case "USER_DECONX" :
            case "USER_WENT_TODEL" :
            case "USER_KEEP_TODEL" :
            case "TAKE_A_SNAP" :
            case "SHOULDNT_BE" :
                    $lotid = $this->LogOutTypes[$obj];
                break;
            default :
                    return "__ERR_VOL_WRG_DATAS";
        }
        
        //On récupère la dernière ligne de connexion
        $QO = new QUERY("qryl4tqrcnxn11");
        $params = array(":uid" => $uid);
        $cnx_tab = $QO->execute($params);
        
        $psid = NULL;
        //On s'assure qu'une ligne existe belle et bien
        if ( !$cnx_tab || count($cnx_tab) > 1 ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $cnx_tab,"v_d");
            return "__ERR_VOL_FATAL_UXPTD";
        } else {
            $psid = $cnx_tab[0]["llog_psid"];
        }
        
        //Annuler la connexion au niveau de la base de données 
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4tqrcnxn12");
        $params = array(":psid" => $psid, ":lotid" => $lotid, ":tstamp" => $now);
        $QO->execute($params);
        
        //Arreter la connexion au niveau du service
        if (! $CNXH->try_logout() ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,"v_d");
            return "__ERR_VOL_FATAL_UXPTD";
        }
        
        return TRUE;
    }
    
    
    /********************************************************************************************************************************************/
    /************************************************************ AUTO_RELOGIN SCOPE ************************************************************/
    
    public function AutoCnx_StartAutoLogIn ($ssid, $locip, $loc_cn = NULL, $uagent = NULL, $OPTIONS = NULL) {
        //QUOI : Lancer la procédure AUTO_LOGIN
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ssid, $locip]);
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur est déconnecté
         */
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            return -1;
        }
        
        /*
         * ETAPE :
         *      On vérifie si on a accès au cookie COOKIE_AUTO_LOGIN.
         *      Dans le contraire on laisse tomber.
         */
        //NOTE : TQR_CALG = TreQR_CookieAutoLoGin
        $ckdatas = $this->AutoCnx_CookieExists("TQR_CALG",TRUE);
        if ( !$ckdatas ) {
            return FALSE;
        } else if ( !( $ckdatas && is_array($ckdatas) && count($ckdatas) ) ) {
            return "__ERR_VOL_BROKEN_COOKIE";
        }
        
        $ckeid = $ckdatas[0];
        $user_eid = $ckdatas[1];
        $token = $ckdatas[2];
        $persistant_origin_ssid = $ckdatas[3];
        
        /*
         * ETAPE :
         *      On vérifie s'il existe une correspondance pour le couple USER_EID + TOKEN
         * RAPPEL : 
         *      L'objectif étant de maximiser la sécurité de cette opération car elle peut se revéler sensible.
         *      Un utilisateur bien avisé peut décider de truquer les données du cookies.
         *      Grace à une triple vérification, on limite au maximum une tentative de connexion par usurpation d'identité via les COOKIES.
         */
        $ckdatas_db_1 = $this->AutoCnx_OperExists($user_eid,$token);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ckdatas_db_1) ) {
            return $ckdatas_db_1;
        }
        
        /*
         * ETAPE :
         *      On vérifie s'il existe une correspondance pour lidentifiant
         * RAPPEL : 
         *      L'objectif étant de maximiser la sécurité de cette opération car elle peut se revéler sensible.
         *      Un utilisateur bien avisé peut décider de truquer les données du cookies.
         *      Grace à une triple vérification, on limite au maximum une tentative de connexion par usurpation d'identité via les COOKIES.
         */
        $ckdatas_db_2 = $this->AutoCnx_OperExists_With_Id($ckeid);
        if (! $ckdatas_db_2 ) {
            return "__ERR_VOL_HACKED";
        }
        
        /*
         * ETAPE :
         *      On vérifie que les deux tableaux sont égaux
         * RAPPEL : 
         *      L'objectif étant de maximiser la sécurité de cette opération car elle peut se revéler sensible.
         *      Un utilisateur bien avisé peut décider de truquer les données du cookies.
         *      Grace à une triple vérification, on limite au maximum une tentative de connexion par usurpation d'identité via les COOKIES.
         */
        if ( $ckdatas_db_1 !== $ckdatas_db_2 ) {
            return "__ERR_VOL_HACKED_DEEP";
        }
        
        /*
         * ETAPE :
         *      On crée une nouvelle occurence au niveau de la base de données.
         */
        $new_ckdatas = $this->AutoCnx_StartCookie_inDB($user_eid,$token, [
            "compl" => [
                "ssid"      => $ssid,
                "locip"     => $locip,
                "loc_cn"    => $loc_cn,
                "uagent"    => $uagent
            ]
        ]);
        if (! $new_ckdatas ) {
            return "__ERR_VOL_FAILED";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $new_ckdatas) ) {
            return $new_ckdatas;
        } 
        
        $new_ckeid = $new_ckdatas["ckeid"];
        $new_token = $new_ckdatas["token"];
        
        /*
         * ETAPE :
         *      Si CALLER le souhaite, on peut se charger de la gestion du cookie "COOKIE_AUTO_LOGIN"
         */
        if ( $OPTIONS && isset($OPTIONS["WITH_COOKIE_MANAGE"]) && $OPTIONS["WITH_COOKIE_MANAGE"] === TRUE ) {
            /*
             * ETAPE :
             *      Par précaution, on supprime l'ancien COOKIE.
             *      Quoique ... Il faut attendre le chargement de la page pour que cela soit effectif
             */
            $this->AutoCnx_DelCookie("TQR_CALG");
            $this->AutoCnx_SetCookie($new_ckeid, $user_eid, $new_token, $persistant_origin_ssid, "TQR_CALG");
        }
        
        //hdld_ssn = HanDLeD_SeSsioN
        $hdld_ssn = FALSE;
        /*
         * ETAPE :
         *      Si CALLER le souhaite, on peut se charger de la gestion de le SESSION "COOKIE_AUTO_LOGIN"
         */ 
        if ( $OPTIONS && isset($OPTIONS["WITH_SESSION_MANAGE"]) && $OPTIONS["WITH_SESSION_MANAGE"] === TRUE ) {
            $PA = new PROD_ACC();
            
            /*
             * ETAPE :
             *      On récupère la dernière ligne de connexio UID
             */
            $uid = $PA->onread_get_accid_from_acceid($user_eid);
            $QO = new QUERY("qryl4tqrcnxn15");
            $params = array(
                ":uid"  => $uid,
                ":ssid" => $ssid
            );
            $cnx_tab = $QO->execute($params);
            
            $psid = ( $cnx_tab ) ? $cnx_tab[0]["llog_psid"] : -1;

            /*
             * ETAPE :
             *      On charge un objet de type PROD_ACC.
             *      C'est necessaire pour créer le champs "rsto_infos" au niveau de SESSION.
             */
            $utab = $PA->on_read_entity([
                "acc_eid" => $user_eid, 
                "OPTIONS" => [
                    "_WITH_TODEL"  => FALSE
                ]
            ]);

            /*
             * ETAPE :
             *      On crée la connexion en ajoutant "rsto_infos"
             */
            $CXH = new CONX_HANDLER();
            $r = $CXH->try_login($PA, $psid);
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$r);
            if (! $CXH->is_connected() ) {
                return "__ERR_VOL_UXPTD";
            }

            /*
             * ETAPE :
             *      On signale que l'on a bien traité le cas de SESSION à CALLER
             */
            $hdld_ssn = TRUE;
            
        }
        
        if ( $OPTIONS && isset($OPTIONS["WITH_RELOAD_MANAGE"]) && $OPTIONS["WITH_RELOAD_MANAGE"] === TRUE ) {
            echo "Loading...";
            header("Refresh:0");
            exit();
        }
        
        $final_datas = [
            "hdl_ssn"   => $hdld_ssn,
            "ckeid"     => $new_ckeid,
            "user_id"   => $user_eid,
            "token"     => $new_token,
        ];
        
        return $final_datas;
        
    }
    
    
    /********* COOKIE **********/
    
    public function AutoCnx_CookieExists ($ckname = NULL, $WITH_ORDERED_OPT = FALSE) {
        //QUOI : Le cookie COOKIE_AUTO_LOGIN existe t-il ? Si oui récupérer les données
        
        /*
         * NOTE :
         *      TQR_CALG = TreQR_CookieAutoLoGin
         */
        $ckname = ( $ckname ) ? : "TQR_CALG";
        
        $ckdatas;
        if ( !empty($_COOKIE[$ckname]) ){
            $ckdatas = $_COOKIE[$ckname];
        }
        
        $fdatas = [];
        if ( isset($ckdatas) && is_string($ckdatas) ) {
            $fdatas = ( $WITH_ORDERED_OPT ) ? explode(",", $ckdatas) : $ckdatas;
        }
        
        return $fdatas;
    }
    
    public function AutoCnx_SetCookie ($ckeid, $user_eid, $token, $ssid, $ckname = NULL, $lifetime = NULL) {
        //QUOI : Crée le COOKIE_AUTO_LOGIN en dur et l'enregistre au niveau du navigateur
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$ckeid,$user_eid,$token]);
        
        /*
         * [NOTE 29-05-16]
         *      "ssid" correspond à l'identifiant de SESSION qui a été attribué à la première connexion, lorque que le COOKIE a été créé.
         *      Cette donnée est surtout necessaire pour se déconnecter. En effet, si on a pas l'SSID on ne pourra pas se DECO !!
         */
        
        /*
         * NOTE :
         *      La valeur par DEFAULT est : 6 mois (180 jours)
         *      Cette valeur est totalement arbitraire. 
         */
        $dflt_lifetime = 180*24*3600;
        
        /*
         * ETAPE :  
         *      On détermine la valeur EXPIRE en fonction des données disponibles
         */
        $expires = ( $lifetime ) ? time()+$lifetime : time()+$dflt_lifetime;
        
        /*
         * ETAPE :
         *      On construit la valeur à ajouter
         */
        $value = $ckeid.",".$user_eid.",".$token.",".$ssid;
        
        /*
         * ETAPE :
         *      On détermine le nom à donner au COOKIE
         */
        //NOTE : TQR_CALG = TreQR_CookieAutoLoGin
        $ckname = ( $ckname ) ? : "TQR_CALG";
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$ckname, $value, $expires]);
//        exit();
        
        /*
         * ETAPE :
         *      On crée et envoie le COOKIE
         */
        setcookie($ckname, $value, $expires, "/");
        
        return TRUE;
        
    }
    
    public function AutoCnx_DelCookie ($ckname = NULL) {
        //QUOI : Détruit le COOKIE_AUTO_LOGIN
        
        /*
         * ETAPE :
         *      On détermine le nom à donner au COOKIE
         */
        //NOTE : TQR_CALG = TreQR_CookieAutoLoGin
        $ckname = ( $ckname ) ? : "TQR_CALG";
        
        setcookie ($ckname, "", time() - 3600, "/");
        
    }
    
    
    /********* InDATABASE **********/
    
    public function AutoCnx_OperExists ($user_eid, $token) {
        //QUOI : Vérifie si le COOKIE_AUTO_LOGIN existe. Si c'est le cas, on retourne le couple USER_ID + TOKEN et COOKID
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$user_eid, $token]);
        
        /*
         * ETAPE :
         *      On vérifie si l'identifiant correspond à un compte connu et actif.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($user_eid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        $QO = new QUERY("qryl4latoln3");
        $params = array(
            ":uid"      => $utab["pdaccid"], 
            ":token"    => $token
        );
        $opr_tab = $QO->execute($params);
        
        if ( count($opr_tab) > 1 ) {
            return "__ERR_VOL_CORRUPTED";
        }
        
        return ( $opr_tab ) ? $opr_tab[0] : [];
    }
    
    public function AutoCnx_OperExists_With_Id ($opeid) {
        //QUOI : Vérifie si le COOKIE_AUTO_LOGIN existe. Si c'est le cas, on retourne le couple USER_ID + TOKEN et COOKID
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$opeid]);
        
        
        $QO = new QUERY("qryl4latoln7");
        $params = array(
            ":eid" => $opeid, 
        );
        $opr_tab = $QO->execute($params);
        
        return ( $opr_tab ) ? $opr_tab[0] : [];
    }
    
    public function AutoCnx_ActvOperCount ($user_eid, $WITH_DATAS = FALSE) {
        //QUOI : Vérifie si le COOKIE_AUTO_LOGIN existe. Si c'est le cas, on retourne le couple USER_ID + TOKEN et COOKID
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$user_eid]);
        
        /*
         * ETAPE :
         *      On vérifie si l'identifiant correspond à un compte connu et actif.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($user_eid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        $QO = new QUERY("qryl4latoln4");
        $params = array(
            ":uid"      => $utab["pdaccid"] 
        );
        $opr_tab = $QO->execute($params);
        
        $datas;
        if ( $WITH_DATAS ) {
            $datas = ( $opr_tab ) ? $opr_tab : [];
        } else {
            $datas = ( $opr_tab ) ? count($opr_tab) : 0;
        }
        
        return $datas;
    }
    
    public function AutoCnx_ActvOperCount_With_Ssn ($user_eid, $ssid, $WITH_DATAS = FALSE) {
        //QUOI : Vérifie si le COOKIE_AUTO_LOGIN existe. Si c'est le cas, on retourne le couple USER_ID + TOKEN et COOKID
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$user_eid,$ssid]);
        
        /*
         * ETAPE :
         *      On vérifie si l'identifiant correspond à un compte connu et actif.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($user_eid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        $QO = new QUERY("qryl4latoln4_wssn");
        $params = array(
            ":uid"  => $utab["pdaccid"],
            ":ssid" => $ssid
        );
        $opr_tab = $QO->execute($params);
        
        $datas;
        if ( $WITH_DATAS ) {
            $datas = ( $opr_tab ) ? $opr_tab : [];
        } else {
            $datas = ( $opr_tab ) ? count($opr_tab) : 0;
        }
        
        return $datas;
    }
    
    
    public function AutoCnx_StartCookie_inDB ($user_eid, $old_token = NULL, $XTRAS = NULL) {
        //QUOI : Permet de créer une nouvelle opération au niveau de la BDD
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$user_eid]);
        
        if ( $old_token ) {
            
            /*
             * ETAPE :
             *      On vérifie s'il existe une opération active.
             *      Dans ce cas on annule la précedente opération.
             */
            $old_datas = $this->AutoCnx_OperExists($user_eid, $old_token);
            /*
             * ETAPE :
             *      On vérifie s'il existe d'autres OPER actives (par sécurité)
             */
            $actv_opers = NULL;
            if ( $XTRAS && $XTRAS["compl"] && $XTRAS["compl"]["ssid"] ) {
                $actv_opers = $this->AutoCnx_ActvOperCount_With_Ssn($user_eid,$XTRAS["compl"]["ssid"]);
            }

            if ( $old_datas && $this->return_is_error_volatile(__FUNCTION__, __LINE__, $old_datas)  ) {
                return $old_datas;
            } else if ( $actv_opers && $this->return_is_error_volatile(__FUNCTION__, __LINE__, $actv_opers) ) {
                return $actv_opers;
            } else if ( $actv_opers > 1 ) {
                return "__ERR_VOL_UXPTD_2";
            } else if ( $old_datas ) {
                /*
                 * ETAPE :
                 *      On annule toutes les anciennes opérations
                 */
                $clal_r = $this->AutoCnx_Cookie_CloseAll_inDB($user_eid);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $clal_r) ) {
                    return $clal_r;
                }
            }
        }
        
        /*
         * ETAPE :
         *      On récupère la table pour ensuite avoir accès à UID
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($user_eid,TRUE);
        
        /*
         * ETAPE :
         *      On crée un nouveau TOKKEN
         */
        $new_token = $this->guidv4();
        
        /*
         * ETAPE :
         *      On crée la nouvelle occurence
         */
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4latoln1");
        $params = array( 
            ":temp_eid"         => $now,
            ":refu"             => $utab["pdaccid"],
            ":token"            => $new_token,
            ":ssid"             => $XTRAS["compl"]["ssid"],
            ":locip"            => $XTRAS["compl"]["locip"],
            ":loc_cn"           => $XTRAS["compl"]["loc_cn"],
            ":uagent"           => $XTRAS["compl"]["uagent"],
            ":curl"             => NULL,
            ":redirurl"         => NULL,
            ":datestart"        => date("Y-m-d G:i:s",($now/1000)),
            ":datestart_tstamp" => $now,
            ":isregen"          => 0
        );
        $id = $QO->execute($params);
        
        /*
         * ETAPE :
         *      On ajoute l'identifiant externe
         * NOTE 
         *      On choisit n'importe lequel ENTITY qui hérite de PROD_ENTY pour accéder aux fonction d'encodage.
         */
        $MYS = new MYSTERY();
        $eid = $MYS->entity_ieid_encode($now,$id);
        
        $QO = new QUERY("qryl4latoln2");
        $params = array( 
            ':id'   => $id,
            ':eid'  => $eid 
        );
        $QO->execute($params);
        
        $final_datas = [
            "ckid"      => $id,
            "ckeid"     => $eid,
            "user_id"   => $utab["pdacc_eid"],
            "token"     => $new_token
        ];
                
        return $final_datas;
        
    }
    
    public function AutoCnx_Cookie_CloseAll_inDB ($user_eid ) {
        //QUOI : Permet de cloturer toutes les opérations au niveau de la BDD. 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$user_eid]);
        
        /*
         * ETAPE :
         *      On vérifie si l'identifiant correspond à un compte connu et actif.
         */
        $PA = new PROD_ACC();
        $utab = $PA->exists($user_eid,TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        $now = round(microtime(TRUE)*1000);
        
        $QO = new QUERY("qryl4latoln5");
        $params = array(
            ":uid"          => $utab["pdaccid"],
            ":end_date"     => date("Y-m-d G:i:s",($now/1000)),
            ":end_tstamp"   => $now
        );
        $QO->execute($params);
        
        return TRUE;
        
    }
    
    public function AutoCnx_Cookie_CloseThis_inDB ($opeid) {
        //QUOI : Permet de cloturer une opération au niveau de la BDD. 
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$opeid]);
        
        $optab = $this->AutoCnx_OperExists_With_Id($opeid);
        if (! $optab ) {
            return "__ERR_VOL_NOT_FOUND";
        }
        
        $now = round(microtime(TRUE)*1000);
        
//        var_dump($now,date("Y-m-d G:i:s",($now/1000)));
//        exit();
        
        $QO = new QUERY("qryl4latoln6");
        $params = array(
            ":opid"         => $optab["latol_op_id"],
            ":end_date"     => date("Y-m-d G:i:s",($now/1000)),
            ":end_tstamp"   => $now
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    
    
    /********************************************************************************************************************************************/
    /************************************************************ AUTO_RELOGIN SCOPE ************************************************************/
    
    
    public function IsConnectedLate ($uid) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
            
       /*
        * ETAPE :
        *      On détermine le STATUT DE CONNEXION de CU. Autrement dit est ce que TGUSER est considéré comme étant ACTIF ou INACTIF
        *          1- On vérifie s'il y a au moins une SESSION d'ouverte. 
        *          NON : "EST DECONNECTÉ"
        *          OUI, faire :
        *               2- On vérifie le code CODE ACTIF de USER. Est-il Actif ?
        *               NON : 
        *                   30 - N'est pas ou plus actif ! Depuis PEU ?
        *                       OUI : "EST CONNECTÉ"
        *                       NON : "EST DÉCONNECTÉ"
        *               OUI, faire :
        *                   31- Est-ce que ça fait moins de x temps
        *                   OUI : "EST CONNECTÉ"
        *                   NON, faire :
        *                       4- On vérifie si USER a effectué une ACTIVITE ACTIVE "récemment" (<= 10 minutes)
        *                       OUI : "EST CONNECTÉ"
        *                       NON, faire : 
        *                           5- On vérifie si USER a effectué une ACTIVITE PASSIVE "récemment" (<= 10 minutes)
        *                           OUI : "EST CONNECTÉ"
        *                           NON : "EST DECONNECTÉ"
        */   
        $IS_CONNECTED = TRUE;
        //1- On vérifie s'il y a au moins une SESSION d'ouverte. 
        if ( !$this->IsConnectedLate_SsnExsts($uid) ) { 
            $IS_CONNECTED = FALSE;
        } 
        else {
            //2- On vérifie le code CODE ACTIF de USER. Est-il Actif ?
            $ACT_STS = $this->IsConnectedLate_LateFocusHisto($uid);
            if ( !$ACT_STS ) {
                $IS_CONNECTED = FALSE;
            } 
            else if ( in_array($ACT_STS[0],["_CODE_0","_CODE_1"]) ) {
                //30 - N'est pas ou plus actif ! Depuis PEU ?
                $IS_CONNECTED = ( $ACT_STS[1] <= $this->RECLY_MEANS_HIST_FKS_CD0 ) ? TRUE : FALSE;
            } 
            //3- Est-ce que ça fait moins de x temps
            else if ( $ACT_STS[0] === "_CODE_2" && $ACT_STS[1] > $this->RECLY_MEANS_HIST_FKS_CD2 ) {
                //4- On vérifie si USER a effectué une ACTIVITE ACTIVE "récemment" (<= 10 minutes)
                //5- On vérifie si USER a effectué une ACTIVITE PASSIVE "récemment"
                $IS_CONNECTED = ( $this->IsConnectedLate_LateActiveHisto($uid,TRUE) | $this->IsConnectedLate_LatePassiveHisto($uid,TRUE) ) ? TRUE : FALSE;
            }
        }
        
        return $IS_CONNECTED;
    }
    
    
    public function IsConnectedLate_SsnExsts ($uid, $WRCOP = FALSE) {
        //QUESTION : Est-ce qu'il existe une SESSION récente d'ouverte ?
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $QO = new QUERY("qryl4lacotdn1");
        $params = array(
            ":uid"      => $uid,
            ":limit"    => 1
        );
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();       
        
        if (! $datas ) {
            return;
        } else {
            $late = $datas[0];
            $late_time = floatval($late["llog_stdate_tstamp"]);
            $now = round(microtime(TRUE)*1000);
            
            $diff = $now - $late_time;
            if ( $WRCOP ) {
                return ( $diff < $this->RECLY_MEANS_SSN ) ? TRUE : FALSE;
            } else {
                return $diff;
            }
        }
    }
    
    
    public function IsConnectedLate_LateActiveHisto ($uid, $WRCOP = FALSE) {
        //QUESTION : Est-ce que USER a effectué une activité ACTIVE recemment ? 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $QO = new QUERY("qryl4lacotdn2");
        $params = array(
            ":uid"      => $uid,
            ":limit"    => 1
        );
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        if (! $datas ) {
            return;
        } else {
            $late = $datas[0];
            $late_time = floatval($late["ualg_adate_tstamp"]);
            $now = round(microtime(TRUE)*1000);
            
            $diff = $now - $late_time;
            if ( $WRCOP ) {
                return ( $diff < $this->RECLY_MEANS_HIST_ACTV ) ? TRUE : FALSE;
            } else {
                return $diff;
            }
        }
    }
    
    
    public function IsConnectedLate_LatePassiveHisto ($uid, $WRCOP = FALSE) {
        //QUESTION : Est-ce que USER a effectué une activité PASSIVE recemment ? 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $QO = new QUERY("qryl4lacotdn3");
        $params = array(
            ":uid"      => $uid,
            ":limit"    => 1
        );
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        if (! $datas ) {
            return;
        } else {
            $late = $datas[0];
            $late_time = floatval($late["ualg_adate_tstamp"]);
            $now = round(microtime(TRUE)*1000);
            
            $diff = $now - $late_time;
            if ( $WRCOP ) {
                return ( $diff < $this->RECLY_MEANS_HIST_PSV ) ? TRUE : FALSE;
            } else {
                return $diff;
            }
        }
    }
    
    
    public function IsConnectedLate_LateFocusHisto ($uid, $WRCOP = FALSE) {
        //QUESTION : Qu'elle est le dernier STATUT "FOCUS" de USER ? 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $uid);
        
        $QO = new QUERY("qryl4lacotdn4");
        $params = array(
            ":uid"      => $uid,
            ":limit"    => 1
        );
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        if (! $datas ) {
            return;
        } else {
            $late = $datas[0];
            $late_time = floatval($late["ualg_adate_tstamp"]);
            $now = round(microtime(TRUE)*1000);
            
            $diff = $now - $late_time;
            if ( $WRCOP ) {
                if ( $late["ualg_refobj_lib"] === "_CODE_2" ) {
                    return ( $diff < $this->RECLY_MEANS_HIST_FKS ) ? TRUE : FALSE;
                } else {
                    return FALSE;
                }
            } else {
                return [$late["ualg_refobj_lib"],$diff];
            }
        }
    }
    
    /***************************************************************************************************************************************************************************/
    /***************************************************************************************************************************************************************************/
    /***************************************************************************************************************************************************************************/
    
    
}
