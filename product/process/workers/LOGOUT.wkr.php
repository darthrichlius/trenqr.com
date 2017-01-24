<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class WORKER_LOGOUT extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    public function Logout() {
        
        /*
         * [DEPUIS 30-05-16]
         *      On réccupère l'SSID en fonction du cas en présence.
         *      (1) Si aucun COOKIE_AUTO_LOGIN est present on prend SSID courant
         *      (2) Sinon on prend celui du COOKIE : COOKIE_AUTO_LOGIN. Il s'agit de celui qui est censé être inscrit dans la BDD.
         */
        $TQCNX = new TQR_CONX();
         
        $ssid = session_id();
        $ckdatas = $TQCNX->AutoCnx_CookieExists("TQR_CALG",TRUE);
        if ( $ckdatas && $ckdatas[3] ) {
            $ssid = $ckdatas[3];
        }
        
        $r = $TQCNX->TryLogOut($this->KDIn["oid"], $ssid);
        
        /*
         * [DEPUIS 30-05-16]
         *      On détruit le COOKIE qui permet l'AUTO_LOGIN et on cloture l'opération au niveau de la BDD.
         */
        $user_id = $this->KDIn["oeid"];
        
//        $CNXH = new CONX_HANDLER();
//        $CNXH->try_logout();
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$_SESSION,$ckdatas,$r]);
//        exit();
        
        if ( $ckdatas && is_array($ckdatas) && count($ckdatas) ) {
            $ckeid = $ckdatas[0];
            // (2) On cloture l'opération
            $TQCNX->AutoCnx_Cookie_CloseThis_inDB($ckeid);
            // (3) On supprime le COOKIE
            $TQCNX->AutoCnx_DelCookie("TQR_CALG");
        }
        
    }

    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        //On masque les erreurs NOTICE, WARNING car ça fausse les résultats cote FE
//        @session_start(); [DEPUIS 07-09-15] @author BOR PAssage en mode PAGE au niveau de URQTAB
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        /*
        //* On vérifie que toutes les données sont présentes 
        $EXPTD = ["type","where","when","message","lang","url","scrn_w","scrn_h"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        //*/
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        /*
         * [DEPUIS 07-09-15] @author BOR
         *  Ce n'est pas necessaire car WATHMAN s'occuper de tout ça.
         */
        /*
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        //*/
        
        //Est ce que l'utilisateur est connecté ? 
        //Normalement c'est impossible mais bon ...
        /*
         * [DEPUIS 07-09-15] @author BOR
         *  Ce n'est pas necessaire car WATHMAN s'occuper de tout ça.
         */
        /*
        $CXH = new CONX_HANDLER();
        if (! $CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        } 
        //*/
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $RSTOI->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid,TRUE);
        
        if ( !$exists ) {
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
//            $this->Ajax_Return("err","__ERR_VOL_U_G"); //[NOTE 07-09-15] @BOR
        }
        
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $RSTOI->getAcc_eid();
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        
//        var_dump(__LINE__,$_COOKIE);
//        var_dump(__LINE__,$_SESSION);
//        exit();
    }

    public function on_process_in() {
        $this->Logout();
    }

    public function on_process_out() {
        /*
        //On lance la déconnexion
        $CH = new CONX_HANDLER();
        //RAPPEL : Renvoie TRUE si tout s'est bien passé
        $CH->try_logout();
        //*/
        
        //On lance la redirection
        $RDH = new REDIR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url = $RDH->redir_to_default_page(DFTPAGE_PROD_WEL);
        $RDH->start_redir_to_this_url_string($url);
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>