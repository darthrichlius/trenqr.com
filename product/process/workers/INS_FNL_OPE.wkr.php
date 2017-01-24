<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_INS_FNL_OPE extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
//            if ( $k === "x" ) {
            if ( in_array($k,["x","xtras"]) && !$v ) {
                continue;
            } elseif ( $k === "loc_cn" && $_SERVER["REMOTE_ADDR"] === "127.0.0.1" && $_SERVER["SERVER_NAME"] === "127.0.0.1" ) { 
                /*
                 * [DEPUIS 19-10-15] @author BOR
                 *      Permet de travailler en mode local
                 */
                $this->KDIn["locip"] = sprintf('%u', ip2long("127.0.0.1"));;
                $this->KDIn["loc_cn"] = "fr";
                continue;
            } else {
               //Les données ont déjà été vérifiées
//            if (! ( isset($v) && $v !== "" ) ) {
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
//            }
            
                //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
                $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
                $rbody = $this->KDIn["datas"][$k];

                preg_match_all("/(\n)/", $rbody, $m_c1);
                preg_match_all("/(\r)/", $rbody, $m_c2);
                preg_match_all("/(\r\n)/", $rbody, $m_c3);
                preg_match_all("/(\t)/", $rbody, $m_c4);
                preg_match_all("/(\s)/", $rbody, $m_c5);

                //Parano : Je sais que j'aurais pu ne mettre que \s
                if ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                }

                /*
                 * [DEPUIS 28-05-16]
                 */
                //CAS ENTERCZ
                $entercz = ["ENTERCZ_DIRECT","ENTERCZ_PREFORM","ENTERCZ_ACTIVE_FB_SSN","ENTERCZ_INSAPI_FB"];
                if ( $k === "entercz" && !in_array($v, $entercz) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                } 

                //CAS SPECIAUX
                $qsp = ["INS_CHKFORM","INS_GOINS"];
                if ( $k === "urq" && !in_array($v, $qsp) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                } 
            }    
            
        }
        
    }
    
    private function CheckForm () {
        //["ufn","ubd","ugdr","ucy","upsd","ueml","upwd","urq"]
        $INS = new INSCRIPTION();
        
        $c = [
            "ufn"       => "fullname",
            "ubd"       => "borndate",
            "ugdr"      => "gender",
            "ucy"       => "city",
            "upsd"      => "pseudo",
            "ueml"      => "email",
            "upwd"      => "password"
        ];
//        $this->KDIn["datas"]["ueml"] = "fake.email@ondeuslynn.com"; //TEST
        $errs = [];
        foreach ( $this->KDIn["datas"] as $k => $v ) {
            if ( in_array($k, ["ufn","ubd","ugdr","ucy","upsd","ueml","upwd"]) ) {
                $k = $c[$k];
                if (! $INS->CheckField($k, $v) ) {
                    $errs[] = $k;
                } 
            }
        }
        
        if (! count($errs) ) {
            $errs = "DONE";
        }
        
        return $errs;
                
    }
    
    private function CreateNewAccount () {
//        $INS = new INSCRIPTION();
        
        //Controle des données du formulaire
        $stp1 = $this->CheckForm();
        
        //Création du compte au niveau de la base de données ACCOUNT
        if ( $stp1 === "DONE" ) {
            
            //On transforme la date de naissance
//            $f__ = $this->KDIn["datas"]["ubd"];
            
//            ["ins_fn","ins_nais","ins_gdr","ins_cty","ins_psd","ins_eml","ins_pwd","locip"]
            $args = [
                "ins_fn"    => $this->KDIn["datas"]["ufn"],
                "ins_nais"  => $this->KDIn["datas"]["ubd"],
                "ins_gdr"   => $this->KDIn["datas"]["ugdr"],
                "ins_cty"   => $this->KDIn["datas"]["ucy"],
                "ins_psd"   => $this->KDIn["datas"]["upsd"],
                "ins_eml"   => $this->KDIn["datas"]["ueml"],
                "ins_pwd"   => $this->KDIn["datas"]["upwd"],
                /*
                 * [DEPUIS 03-07-16]
                 */
//                "locip"     => $this->KDIn["loc_cn"],
                "locip"     => $this->KDIn["locip"],
                /*
                 * [DEPUIS 28-05-16]
                 */
                "entercz"   => $this->KDIn["datas"]["entercz"],
                "xtras"     => ( $this->KDIn["datas"]["xtras"] ) ? : [] 
            ];
                    
//            $r = $INS->CreateNewAccount($args);
            $TQACC = new TQR_ACCOUNT();
            $r = $TQACC->on_create_entity($args);
            
            return $r;
        } else {
            return FALSE;
        }
        
    }
    
    private function Gofinal() {
        
        $this->DoesItComply_Datas(); 
        
        $urq = $this->KDIn["datas"]["urq"];
        $result = NULL;
        switch ($urq) {
            case "INS_CHKFORM" :
                    $result = $this->CheckForm();
                break;
            case "INS_GOINS" :
                    $errs = [];
                
                    /*
                     * [DEPUIS 20-10-15] @author BOR
                     *      On vérifie que le code du site est le même que celui présent au niveau local
                     */
                    $sitekey = $this->KDIn["datas"]["g-stkey"];
                    $grr = $this->KDIn["datas"]["g-r-r"];
                    if ( $sitekey !== TRENQR::$RECAPTCHA_SITEKEY ) {
//                        var_dump(__LINE__,$sitekey,TRENQR::$RECAPTCHA_SITEKEY);
                        $errs[] = "recaptcha";
                    } 
                    /*
                     * [DEPUIS 20-10-15] @author BOR
                     *      On fait appel à google pour vérifier si la réponse est valide
                     */
                    $S_RCPT = new SRVC_ReCaptcha(TRENQR::$RECAPTCHA_SITEKEY, TRENQR::$RECAPTCHA_SECRET);
                    $r = $S_RCPT->checkResponse($grr,$_SESSION['sto_infos']->getCurrent_ipadd(),["ssl_verifypeer" => FALSE]);
                    if (! $r ) {
//                        var_dump(__LINE__,$r);
                        $errs[] = "recaptcha";
                    }
                    
                    
                    if (! count($errs) ) { 
//                    if ( FALSE ) { //DEV, TEST, DEBUG
                        
                        $result = $this->CreateNewAccount();
                
                        if ( is_array($result) && count($result) ) {

                            //On lance un processus de connexion
                            $args_cnx = [
                                "cnx_login" => $this->KDIn["datas"]["ueml"],
                                "cnx_pwd"   => $this->KDIn["datas"]["upwd"],
                                "cnx_ssid"  => session_id(),
                                "cnx_locip" => sprintf('%u', ip2long($_SERVER["REMOTE_ADDR"])),
                                "cnx_too"   => round(microtime(TRUE)*1000)
                            ];

                            $TQCNX = new TQR_CONX();
                            $cnx_r = $TQCNX->TryConx($args_cnx);

                            //On ne vérifie pas le résultat car la probabilité qu'il y ait un problème est faible voire IMPOSSIBLE
    //                        $result = $cnx_r; //DEV, DEBUG, TEST
                            
//                            ... Envoyer l'email de confirmation de Compte
                                
//                            ... FEnvoyer l'email lui demandant d'inviter des amis
                            
                            $result = "DONE";
                        } else {
                            $result = FALSE;
                        }
                    } else {
//                         $result = "DONE"; //DEV, TEST, DEBUG
                         $result = $errs;
                    }
                break;
            default:
                break;
        }
        
        $this->KDOut["FE_DATAS"] = $result;
        
    }

    /****************** END SPECFIC METHODES ********************/
    
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["ufn","ubd","ugdr","ucy","upsd","ueml","upwd","urq","g-r-r","g-stkey","entercz","xtras"];

        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        foreach ($in_datas_keys as $k => $v) {
//            if (! ( isset($v) && ( $v != "" || $k === "x") ) ) {
            if (! ( isset($v) && ( $v != "" || in_array($k,["x","xtras"]) ) ) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }

        $this->KDIn["datas"] = $in_datas;
//        $this->KDIn["datas"]["ueml"] = "fake.email@ondeuslynn.com";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["uagt"] = $_SERVER["HTTP_USER_AGENT"];
        $this->KDIn["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
    }

    public function on_process_in() {
        $this->Gofinal();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}