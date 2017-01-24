<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_cnx extends WORKER  {
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function EC_Handle() {
        /*
         * Retourner un rapport comportant les réponses suivantes :
         *  -> Doit-on traiter le cas d'une validation de compte par Email ?
         *  NON : "_EC_STT_NO_EC"
         *  OUI :
         *      -> CAS 1 : Dans le cas où le compte n'a jamais été validé > Faut-il lancer une opération de validation de compte ?
         *      OUI :
         *          -> Faut-il restreindre le compte en attendant que la validation ait-été effectuée ?
         *          NON : "_EC_STT_INFO"
         *          OUI : "_EC_STT_LOCKNOW"
         *      -> CAS 2 : Dans le cas où le compte n'a jamais été validé > Le Compte est en attente de validation ?
         *      OUI :
         *          -> Faut-il restreindre le compte en attendant que la validation ait-été effectuée ?
         *          NON : "_EC_STT_INFO"
         *          OUI : "_EC_STT_LOCKNOW"
         *      -> CAS 3 : S'agit-il du cas d'une confirmation ?
         *      OUI :
         *          -> Les données liées à la demande sont-elles valides ?
         *          +
         *          -> La clé liée à la demande correspond elle à une demande cloturée il y a moins de 2 minutes ?
         *          =
         *          -> "_EC_STT_WELCOME"
         */     
        $TA = new TQR_ACCOUNT();
        
        /*
         * ETAPE :
         *      On vérifie si on est dans le cas d'une opération liée à la validation de Compte par l'email.
         *      Dans ce contexte il ne peut s'agir que de la validation définitive d'un Compte en utilisant l'email.
         */
        if ( $this->KDIn["ups_optional"] && key_exists("ec_case",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["ec_case"] === "econfirm_final" ) {
            /*
             * ETAPE : 
             *      On vérifie que les données necessaires sont disponibles
             */
            $errs = 0;
            foreach ( ["ec_k","ec_c"] as $kv ) {
                if ( in_array($kv, $this->KDIn["ups_optional"]) ) {
                    $errs++;
                }
            }
            if ( $errs ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["ERR_NUM => ",$errs],'v_d');
                $this->signalError("err_sys_l7emcnfn2",__FUNCTION__, __LINE__);
            } else {
               /*
                * ETAPE : 
                *      On vérifie que les données concordent
                */
                $ec_tab = $TA->EC_Exists($this->KDIn["ups_optional"]["ec_k"]);
                if ( $ec_tab ) {
                    if ( $this->KDIn["ups_optional"]["ec_k"] !== $ec_tab["cnfeml_key"] | $this->KDIn["ups_optional"]["ec_c"] !== $ec_tab["cnfeml_code"] ) {
                        $this->signalError("err_user_l5e404_any",__FUNCTION__, __LINE__);
                    } else if ( $ec_tab["cnfeml_infodt_tstamp"]  ) {
                        return "_EC_STT_NO_EC";
                    } 
                    
                    /*
                     * On va signaler que le Message de confirmation va être (a été) présenté à l'utilisateur.
                     * Pour cela, nous allons mettre à jour l'occurence liée
                     */    
                    $TA->EC_EndOper($ec_tab["cnfeml_key"]);
                    return "_EC_STT_WELCOME";
                } else {
                    $this->signalError("err_user_l5e404_any",__FUNCTION__, __LINE__);
                }
                
            }
            
        } 
        
    }
    
    private function Redir_Affair_Handler ($redir_url) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$redir_url]);
        /*
         * [NOTE]
         *      On vérifie si on est dans le cas d'une opération qui necessitera une redirection après l'inscription.
         *      Pour traiter ce cas nos avons 3 options :
         *          (1) Nous sommes dans le cas de COOKIE_AUTO_LOGIN 
         *              => On redirige tout de suite vers le lien ( après le processus indépendant de CONNEXION AUTO )
         *          (2) Nous sommes dans le cas de COOKIE_AUTO_LOGIN 
         *              (21) Si le lien est de type TMLNR ou TRPG
         *                  => On redirige vers la version WLC
         *              (22) Le lien n'est pas de type TMLNR ou TRPG MAIS est interne à TRENQR
         *                  => On continue vers la pge CONX
         *              (23) Le lien n'est pas interne à TRENQR
         *                  => C'est un HACK ! On continue vers la page CONX en retirant les QUERYSTING
         */
        
       
        /*
         * ETAPE :
         *      On décode l'URL
         */
        $redir_url = $this->KDIn["ups_optional"]["redir_url"];
        $redir_url = urldecode($redir_url);

        $redir_url = str_replace("%2E", ".", $redir_url);
        $redir_url = str_replace("%3D", "=", $redir_url);
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        /*
         * ETAPE :
         *      On lance le processus de vérification de COOKIE_AUTO_LOGIN
         */
        $TQCNX = new TQR_CONX();
        $r = $TQCNX->AutoCnx_StartAutoLogIn(session_id(),$this->KDIn["locip"],$this->KDIn["loc_cn"],$this->KDIn["uagent"],[
            "WITH_COOKIE_MANAGE"    => TRUE,
            "WITH_SESSION_MANAGE"   => TRUE,
            "WITH_RELOAD_MANAGE"    => FALSE,
            "WITH_REDIRURL_MANAGE"  => TRUE,
            "WITH_REDIRURL_URL"     => $redir_url
        ]);
        if ( !( ( $r && is_array($r) ) || $r === FALSE ) ) {
            return TRUE;
        } 
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);

        /*
         * RAPPEL :
         *      Si on arrive à ce point, c'est que nous ne sommes pas dans le cas COOKIE_AUTO_LOGIN
         */

        /*
         * ETAPE :
         *      On vérifie si le lien fait référence à une PAGE interne de type TMLNR ou TRPG
         */
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($redir_url);

//            var_dump($redir_url,$upieces);
//            exit();
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);

        /*
         * ETAPE :
         *      On vérifie si on est dans le cas d'une PAGE spécifique pour lancer un traitement personnalisé.
         */
        if ( $upieces && is_array($upieces) ) {

           $final_redir_url;
           $urqid = $upieces["urqid"];
           switch ($urqid) {
               case "TMLNR_GTPG_RO":
               case "TMLNR_GTPG_RU":
                       $user = $upieces["user"];
                       $final_redir_url = "http://$_SERVER[HTTP_HOST]/$user";
                   break;
               case "TRPG_GTPG_RO":
               case "TRPG_GTPG_RFOL":
               case "TRPG_GTPG_RU":
                       $tei = $upieces["ups_raw"]["tei"];
                       $tle = $upieces["ups_raw"]["tle"];

                       $TRD = new TREND();
                       $trd_href = $TRD->on_read_build_trdhref_from_treid($tei);
                       if ( $trd_href ) {
                           $final_redir_url = $trd_href;
                       }
                   break;
               default:
                   return TRUE;
           }
           
           ob_end_clean();

           /*
            * ETAPE : 
            *      On redirige vers le lien obtenu
            */
           $REDIR_SRVC = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
           $REDIR_SRVC->start_redir_to_this_url_string($final_redir_url);

           exit();
       }
       
        /*
         * NOTE :
         *       Indique que le processus peut suivre son chemin normalement.
         */
        return TRUE;
       
    }
   
    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        /*
         * [DEPUIS 30-05-16]
         *      Permet d'utiliser les methodes telles que "setcookies()" ou "header" qui doivent etre appeléés avant tout OUTPUT.
         *      Aussi, on bloque toute temporisation de sortie.
         *      Je ne sais pas à cette date quelles pourraient être les conséquences pour les autres processus.
         */
        ob_start();
       
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
//        var_dump($_SESSION);
//        var_dump($_GET,$_SESSION["sto_infos"]->getCurr_wto()->getUps_optional());
//        exit();
        
        /*
         * [DEPUIS 25-10-15] @author BOR
         */
        if ( $_SESSION["sto_infos"] && $_SESSION["sto_infos"]->getCurr_wto()->getUps_optional() ) {
            $this->KDIn["ups_optional"] = $_SESSION["sto_infos"]->getCurr_wto()->getUps_optional();
                    
            $this->KDIn["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
        }
        
    }
    
    public function on_process_in() {
        /*
         * [DEPUIS 25-10-15] @author BOR
         */
//        var_dump(__LINE__,__FILE__,$this->KDIn["ups_optional"], $this->KDIn["ups_optional"] && key_exists("ec_case",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["ec_case"] === "econfirm_final");
        if ( $this->KDIn["ups_optional"] && key_exists("ec_case",$this->KDIn["ups_optional"]) && $this->KDIn["ups_optional"]["ec_case"] === "econfirm_final" ) {
            $ec_stt = $this->EC_Handle();
            if ( $ec_stt !== "_EC_STT_NO_EC" ) {
                $this->KDOut["econfirm"]["ec_is_ecofirm"] = TRUE;
                $this->KDOut["econfirm"]["ec_state"] = $ec_stt;
                $this->KDOut["econfirm"]["ec_scope"] = "TQR_INS";
            }
        }
        
        /*
         * [DEPUIS 30-05-16]
         *      On vérifie si on est dans le cas d'une opération qui necessitera une redirection après l'inscription.
         *      Pour traiter ce cas nos avons 3 options :
         *          (1) Nous sommes dans le cas de COOKIE_AUTO_LOGIN 
         *              => On redirige tout de suite vers le lien ( après le processus indépendant de CONNEXION AUTO )
         *          (2) Nous sommes dans le cas de COOKIE_AUTO_LOGIN 
         *              (21) Si le lien est de type TMLNR ou TRPG
         *                  => On redirige vers la version WLC
         *              (22) Le lien n'est pas de type TMLNR ou TRPG MAIS est interne à TRENQR
         *                  => On continue vers la pge CONX
         *              (23) Le lien n'est pas interne à TRENQR
         *                  => C'est un HACK ! On continue vers la page CONX en retirant les QUERYSTING
         */
        if ( $_SESSION["sto_infos"] && $this->KDIn["ups_optional"] && key_exists("redir_affair",$this->KDIn["ups_optional"]) 
                && strtoupper($this->KDIn["ups_optional"]["redir_affair"]) === "_REDIR_AFTER_LGI" && $this->KDIn["ups_optional"]["redir_url"] 
        ) {
            
            $this->Redir_Affair_Handler ($this->KDIn["ups_optional"]["redir_url"]);
            
            /*
             * NOTE :
             *      Si nous arrivons ici cela signifie :
             *          (1) Il n'y a pas de COOKIE_AUTO_LOGIN d'actif
             *          (2) La PAGE de redirection n'est ni de type TMLNR ou TRPG
             *          (3) Autres possibilité ne faisant pas partie d'un CAS FATAL ou pris en compte
             */
//            var_dump(__FILE__,__FUNCTION__,__LINE__,$redir_url);
//            exit();
        }
        
        ob_end_flush();
    }

    public function on_process_out() {
        /*
         * [DEPUIS 25-10-15] @author BOR
         */
        if ( isset($this->KDOut["econfirm"]) ) {
            foreach ($this->KDOut["econfirm"] as $k => $v) {
                $_SESSION["ud_carrier"][$k] = $v;
            }
        }
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}

?>