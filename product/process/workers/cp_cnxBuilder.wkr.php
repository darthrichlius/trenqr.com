<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_cnxBuilder extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function connectionOverseer($t){
        
        $ACC = new ACCOUNT();
        $CNX = new CONNECTION();
        $err_ref = NULL;
        $log_res = 0;
        $rt_psd = NULL;
        $rt_eid = NULL;

        $regPasswdMini = $CNX->getRegexPasswdMini();

        $locktype = $t['locktype'];
        $login = $t['login'];
        $passwd = $t['passwd'];
//        //Cette variable va être nécessaire pour les vérifications de lock du compte
        $timeofday = $t['timeofday'];

        if(!preg_match_all($regPasswdMini, $passwd)){
            $err_ref = 'p_err_cnx_badinfo';
            return ['login' => FALSE, 'err_ref' => $err_ref];
        }
        
        $type = $CNX->login_detect($login);
        
        if ( ( ( key_exists("cnx_year", $_POST) && isset($_POST["cnx_year"]) && !empty($_POST["cnx_year"]) ) 
                && ( key_exists("cnx_month", $_POST) && isset($_POST["cnx_month"]) && !empty($_POST["cnx_month"]) )
                && ( key_exists("cnx_day", $_POST) && isset($_POST["cnx_day"]) && !empty($_POST["cnx_day"]) ) ) ) {
            $t['birthday'] = $_POST["cnx_year"] . '-'. $_POST['cnx_month'] . '-' . $_POST['cnx_day'];
        } else $t['birthday'] = NULL;
        
        if ( ( ( key_exists("superpw", $_POST) && isset($_POST["superpw"]) && !empty($_POST["superpw"]) ) ) ) {
            $t['superpw'] = $_POST["superpw"];
        } else $t['superpw'] = NULL;
        
        

        if($locktype == 'dob' && isset($t['birthday']) && !isset($t['superpw'])){
            $birthday = $t['birthday'];
            //On passe dans le check de fonctions comprenant la date de naissance
            switch($type){
                case 'email':
                    $rt = $CNX->conn_by_email_and_birthday($timeofday, $login, $passwd, $birthday, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
                        $rt_psd = $rt["pseudo"];
                        $rt_eid = $rt["eid"];
                    }
                    break;
                case 'pseudo':
                    $rt = $CNX->conn_by_pseudo_and_birthday($timeofday, $login, $passwd, $birthday, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
                        $rt_psd = $rt["pseudo"];
                        $rt_eid = $rt["eid"];
                    }
                    break;
                default:
                    $log_res = 0;
                    break;
            }
        } else if($locktype == 'full' && isset($t['birthday']) && isset($t['superpw']) && $t['superpw'] != ''){
            if($type != 'email'){
                //Pas possible théoriquement
                //erreur
                $err_ref = 'bad_login_type';
                $log_res = 0;
            } else {
                $birthday = $t['birthday'];
                $superpw = $t['superpw'];
                $rt = $CNX->super_connection($timeofday, $login, $passwd, $birthday, $superpw, $err_ref);
                if($rt != FALSE){
                    $log_res = 1;
                    $rt_psd = $rt["pseudo"];
                    $rt_eid = $rt["eid"];
                }
            }
        } else if($locktype == 'min'){
            //On reste dans le check de fonctions ne comprenant pas la date de naissance
            switch($type){
                case 'email':
                    $rt = $CNX->conn_by_email($timeofday, $login, $passwd, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
                        $rt_psd = $rt["pseudo"];
                        $rt_eid = $rt["eid"];
                        
                    }
                    break;
                case 'pseudo':
                    $rt = $CNX->conn_by_pseudo($timeofday, $login, $passwd, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
                        $rt_psd = $rt["pseudo"];
                        $rt_eid = $rt["eid"];
//                        $cnxInfo = [
//                            'login' => $login,
//                            'loginType' => $type,
//                            'passwd' => $passwd, //Absolument nécessaire?
//                            'account_type' => 'normal'
//                        ];
                    }
                    break;
                default:
                    //Erreur à renvoyer au FE
                    //TODO: Renvoyer erreur
                    $log_res = 0;
                    break;
            }
        } else {
            $err_ref = 'acctype_error';
            $log_res = 0;
        }
        if(!isset($rt)){
            $log_res = 0;
            $err_ref = 'p_err_cnx_unspec_err';
        }
        
        
        if(!isset($err_ref)){
            //On renvoie $r et on passe à la suite
            //On récupère le pseudo de l'utilisateur
            $rslt = [
                'login' => TRUE,
                'account' => $rt['accid'],
                'pseudo' => $rt_psd,
                'eid' => $rt_eid
//                'cnxInfo' => $cnxInfo
            ];
            return $rslt;
            //return true;
        } else {
            //Renvoi d'erreur
            $rslt = [
                'login' => FALSE,
                'err_ref' => $err_ref
            ];

            return $rslt;
        }
    }
    
   
    

    /****************** END SPECFIC METHODES ********************/
    

    public function on_process_in() {
        
        $rslt = $this->connectionOverseer($this->KDIn["datas"]);
        $this->KDOut["rslt"] = $rslt;
    }

    public function on_process_out() {
        
        if($this->KDOut["rslt"]["login"] === FALSE){
            //var_dump($this->KDOut["rslt"]["err_ref"]);
            //Echec de connecion / informations erronnées
            //TODO: Redirection vers accueil ou autre
            
            signalError('err_cnx_wkr',__FUNCTION__, __LINE__);
            
        } else if($this->KDOut["rslt"]["login"] === TRUE){
            $psd = $this->KDOut["rslt"]["pseudo"];
            $eid = $this->KDOut["rslt"]["eid"];
            if($psd == NULL | $eid == NULL){
                //Erreur - Il nous manque des informations. Vérifier dans le cnxBuilder les retours spécifiques rt_eid et rt_pseudo
                var_dump("Erreur - Il nous manque des informations. Vérifier dans le cnxBuilder les retours spécifiques rt_eid et rt_pseudo");
            } else {

                $CH = new CONX_HANDLER();

                $A = new PROD_ACC();
                $rt_ore = $A->on_read_entity(["acc_eid" => $eid]);
                
                $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
                if(isset($rt_ore) && is_array($rt_ore) && count($rt_ore)){
                    //OK donc on redirige
                    $SID = session_id();
                    $r = $CH->try_login($A, $SID);
                    
                    $url = $RDH->redir_build_std_url_string("profil", "TMLNR_GTPG_RO", $psd);
                    $RDH->start_redir_to_this_url_string($url);
                } else {
                    //Erreur de connexion - Redirection
                   
                    $RDH->redir_to_default_page(DFTPAGE_PROD_CONX);
                }
            }
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn["datas"]["login"] = ( key_exists("cnx_form_login_input", $_POST) ) ? $_POST["cnx_form_login_input"] : "";
        $this->KDIn["datas"]["passwd"] = ( key_exists("cnx_form_passwd_input", $_POST) ) ? $_POST["cnx_form_passwd_input"] : "";
        $this->KDIn["datas"]["locktype"] = ( key_exists("cnx_locktype", $_POST) ) ? $_POST["cnx_locktype"] : NULL;
        $this->KDIn["datas"]["timeofday"] = ( key_exists("cnx_tod", $_POST) ) ? $_POST["cnx_tod"] : NULL;
        
//        $this->KDIn["datas"] = [
//            'login' => 'aze2',
//            'passwd' => 'azerty.123',
//            'locktype' => 'min',
//            'timeofday' => '1980-01-14'
//        ];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>