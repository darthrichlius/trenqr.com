<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cp_cnxPrcs extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/

    private function cp_connectPageSubmit($t){
        $ACC = new ACCOUNT();
        $CNX = new CONNECTION();
        $err_ref = NULL;
        $log_res = 0;

        $regPasswdMini = $CNX->getRegexPasswdMini();
        
        //Tableau qui va nous servir à stocker toutes les informations nécessaires au CNX_BUILDER
        //$cnxInfo = array();

        $locktype = $t['locktype'];
        $login = $t['login'];
        $passwd = $t['passwd'];
        //Temporairement enlevé
        //$staycon = $t['staycon'];
        //Cette variable va être nécessaire pour les vérifications de lock du compte
        $timeofday = $t['timeofday'];

        if(!preg_match_all($regPasswdMini, $passwd)){
            $err_ref = 'p_err_cnx_badinfo';
            return ['login' => FALSE, 'err_ref' => $err_ref];
        }

        $type = $CNX->login_detect($login);

        if($locktype == 'dob' && isset($t['birthday']) && !isset($t['superpw'])){
            $birthday = $t['birthday'];
            //On passe dans le check de fonctions comprenant la date de naissance
            switch($type){
                case 'email':
                    $rt = $CNX->conn_by_email_and_birthday($timeofday, $login, $passwd, $birthday, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
//                        $cnxInfo = [
//                            'login' => $login,
//                            'loginType' => $type,
//                            'passwd' => $passwd, //Absolument nécessaire?
//                            'account_type' => 'thirdcrit',
//                            'birthday' => $birthday
//                        ];
                    }
                    break;
                case 'pseudo':
                    $rt = $CNX->conn_by_pseudo_and_birthday($timeofday, $login, $passwd, $birthday, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
//                        $cnxInfo = [
//                            'login' => $login,
//                            'loginType' => $type,
//                            'passwd' => $passwd, //Absolument nécessaire?
//                            'account_type' => 'thirdcrit',
//                            'birthday' => $birthday
//                        ];
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
//                    $cnxInfo = [
//                        'login' => $login,
//                        'loginType' => $type,
//                        'passwd' => $passwd, //Absolument nécessaire?
//                        'account_type' => 'superuser',
//                        'birthday' => $birthday,
//                        'superpw' => $superpw //Pareil que l'autre password. Nécessaire?
//                    ];
                }
            }
        } else if($locktype == 'min'){
            //On reste dans le check de fonctions ne comprenant pas la date de naissance
            switch($type){
                case 'email':
                    $rt = $CNX->conn_by_email($timeofday, $login, $passwd, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
//                        $cnxInfo = [
//                            'login' => $login,
//                            'loginType' => $type,
//                            'passwd' => $passwd, //Absolument nécessaire?
//                            'account_type' => 'normal'
//                        ];
                        
                    }
                    break;
                case 'pseudo':
                    $rt = $CNX->conn_by_pseudo($timeofday, $login, $passwd, $err_ref);
                    if($rt != FALSE){
                        $log_res = 1;
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

        //GESTION DE LA FONCTION POUR RESTER CONNECTE
        /*$rs = $ACC->staycon_management($staycon, $login);
        if($rs == 0){
            $err_ref = 'staycon_mngmt_error';
        }*/

        //Gestion du login_attempt / login_log
        if(isset($t['birthday'])){
        $argsCreateCnx = [
                'login' => $login,
                'supplied_authpwd' => $passwd,
                'supplied_date_nais' => $t['birthday'],
                'result' => $log_res
            ];
            $CNX->on_create_entity($argsCreateCnx);
        } else {
        $argsCreateCnx = [
                'login' => $login,
                'supplied_authpwd' => $passwd,
                'result' => $log_res
            ];
            $CNX->on_create_entity($argsCreateCnx);
        }

        if(!isset($err_ref)){
            //On renvoie $r et on passe à la suite
            $rslt = [
                'login' => TRUE,
                'account' => $rt['accid']
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
        $rslt = $this->cp_connectPageSubmit($this->KDIn['args']);
        //Attention: tableau associatif
        $this->KDOut['rslt'] = $rslt;
    }

    public function on_process_out() {
        if($this->KDOut['rslt']['login'] == FALSE && $this->KDOut['rslt']['err_ref'] != ''){
            echo json_encode([
                'connected' => FALSE,
                'err' => $this->KDOut['rslt']['err_ref']
                ]);
            exit();
        } else if($this->KDOut['rslt']['login'] == TRUE && isset($this->KDOut['rslt']['account'])){
            echo json_encode([
                'connected' => TRUE
                //'info' => $this->KDOut['rslt']['cnxInfo']
            ]);
            exit();
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    public function prepare_datas_in() {
        $this->KDIn['args'] = $_POST['datas'];
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>