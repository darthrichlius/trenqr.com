<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_CNX_TRYCNX extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function LogIn() {
        $args_cnx = [
            "cnx_login" => $this->KDIn["datas"]["lv"],
            "cnx_pwd"   => $this->KDIn["datas"]["pv"],
            "cnx_ssid"  => session_id(),
            "cnx_locip" => sprintf('%u', ip2long($_SERVER["REMOTE_ADDR"])),
            "cnx_too"   => round(microtime(TRUE)*1000)
        ];
        
        $TQCNX = new TQR_CONX();
        $result = $TQCNX->TryConx($args_cnx);
        
        
//        var_dump($result);
        if ( is_array($result) ) {
            $FE["r"] = $result[0];
            $FE["et"]["ec"] = $result[0];
            $FE["et"]["xd"] = $result[1];
        } else if ( $result === "_AUTH_TD_PMLY" ) {
            $FE["r"] = $result;
        } else if ( $result === "_AUTH_TD" || $result === "_AUTH_SUKX") {
            $FE["r"] = $result;
            $FE["utb"] = [
                "fn"    => $_SESSION["rsto_infos"]->getUfname(),
                "psd"   => $_SESSION["rsto_infos"]->getUpseudo()
            ];
        } else {
            $FE["r"] = $result;
        }
//        var_dump($FE);
        
        /*
         * (1) .r (Résultat) : _AUTH_SUKX | _AUTH_TD | _AUTH_FAILED
         * //POSSIBLES
         * (2) .utb 
         *      (21) .fn
         *      (21) .psd
         * (3) .et (ErrorTable)
         *      (31) .ec (ErrorCode) : Un code pour un message d'erreur ou autre
         *      (32) .xd (eXtraDatas) : cela peut être
         *          - Le nombre d'essais restant avant de passer en état "Shellmode"
         *          - Le temps restant avant la fin du SM.
         */
        
        
        /*
         * [DEPUIS 29-05-16]
         *      On crée le cookie qui nous permettra de péréniser la connexion à la plateforme.
         *      Cette opération se déroule en deux étapes :
         *          (1) Enregistrement de l'opération au niveau de BDD
         *          (2) Création du COOKIE avec USER_ID et TOKEN
         * [NOTE 29-05-16]
         *      A ce stade, le cookie est forcement non disponible.
         *      En effet, si l'utilisateur doit se connecter c'est qu'il sait déconnecté.
         *      Dans ce cas, on a du détruire le COOKIE et cloturer l'opération au niveau de la BDD
         */
        if ( in_array($result, ["_AUTH_SUKX"]) && $_SESSION["rsto_infos"] ) {
            $user_id = $_SESSION["rsto_infos"]->getAcc_eid();
            $ckdatas_db = $TQCNX->AutoCnx_StartCookie_inDB($user_id, NULL, [
                "compl" => [
                    "ssid"      => session_id(),
                    "locip"     => $this->KDIn["locip"],
                    "loc_cn"    => $this->KDIn["loc_cn"],
                    "uagent"    => $this->KDIn["uagent"],
                ]
            ]);
//            var_dump($ckdatas_db);
            if ( $ckdatas_db && is_array($ckdatas_db) && count($ckdatas_db) ) {
                $ckeid = $ckdatas_db["ckeid"];
                $token = $ckdatas_db["token"];

                /*
                 * ETAPE :
                 *      On crée le COOKIE en prenant bien soin de sauvegarder l'identifiant de SESSION.
                 *      Si USER quitte le browser, l'ID risque de disparaitre et on se retrouvera avec un gros probleme notamment pour la DECO
                 */
                $TQCNX->AutoCnx_SetCookie($ckeid, $user_id, $token, session_id(), "TQR_CALG");
            }
        }
        
        /*
         * [DEPUIS 30-05-16]
         *      On vérifie si nous sommes dans le cas de "_REDIR_AFTER_LGI".
         *      Si c'est le cas, on renvoie "REDIR_URL".
         *      Pour effectuer la vérification, nous utilisant l'algorithme de détection des pages internes. 
         */
        $curl = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($curl);
        if ( $upieces && $upieces["urqid"] === "cnx" && !empty($upieces["ups_raw"]) 
            && is_array($upieces["ups_raw"]) && !empty($upieces["ups_raw"]["redir_affair"]) && !empty($upieces["ups_raw"]["redir_url"])  
        ) {
            /*
             * ETAPE :
             *      on décode l'URL de la page ORIGIN
             */
            $final_redir_url = $upieces["ups_raw"]["redir_url"];
            $final_redir_url = urldecode($final_redir_url);

            $final_redir_url = str_replace("%2E", ".", $final_redir_url);
            $final_redir_url = str_replace("%3D", "=", $final_redir_url);
            
            /*
             * ETAPE :
             *      On ajoute les données à envoyer les données au niveau du CLIENT
             */
            $FE["rdr_afr"] = [
                "case"  => $upieces["ups_raw"]["redir_affair"],
                "url"   => $final_redir_url
            ];
            
        }
        
        $this->KDOut["FE_DATAS"] = $FE;
        
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
        //On masque les erreurs NOTICE, WARNING car ça fausse les résultats cote FE
        @session_start();
        
//        var_dump($_SESSION);
//        exit();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "lv" : Login value,
         * "pv" : Password value
         * "sc" : Stay connected
         * "cl" :  Current URL
         */
        $EXPTD = ["lv","pv","cl"];
//        $EXPTD = ["lv","pv","sc","cl"]; //[NOTE 08-11-15 PLUS TARD]

        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MISG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        foreach ($in_datas_keys as $k => $v) {
            if (! ( isset($v) && ( $v != "" || $k === "x") ) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MISG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }
        
        /*
         * [DEPUIS 07-09-15] @author
         *  On vérifie s'il n'existe pas déjà une connexion.
         *  Dans ce cas, on renvoie un message qui demandera à FE de reload la page. 
         */
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            $this->Ajax_Return("err", "__ERR_VOL_CNX_XSTS");
        }
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["datas"]["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
        
        /*
         * [DEPUIS 29-05-16]
         *      
         */
        $this->KDIn["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
    }

    public function on_process_in() {
        $this->LogIn();
    }

    public function on_process_out() {
//        $TQACC = new TQR_ACCOUNT();
//        $TQACC->ondelete_goToDelete(101);
        
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>