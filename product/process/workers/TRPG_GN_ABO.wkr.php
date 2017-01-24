<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_GN_ABO extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply_Datas () {
//        exit();
        $f = function($str) {
            return htmlentities(stripcslashes(html_entity_decode(preg_replace("#u([0-9a-f]{3,4})#i","&#x\\1;",urldecode($str)),null,'UTF-8')));
        };
        foreach ($this->KDIn["datas"] as $k => $v) {
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS_25");
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation préléminaire de l'URL
            if ( ( $k === "cl" ) && !filter_var($f($v), FILTER_VALIDATE_URL) ) {
                $this->Ajax_Return("err",$v);
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation de la page
            if ( $k === "pl" && in_array(strtoupper($v), ["TRPG,TMLNR"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS_35");
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation de la page
            if ( $k === "w" && in_array(strtolower($v), ["sta,sra"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS_40");
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
        }
        
    }
    
    private function TryAboCo($pl) {
        /*
         * Permet de lancer un processus de Connexion à une Tendance.
         * AMELIORATIONS : 
         *  (1) Vérifier que dans le cas de TMLNR, l'utilisateur est bien sur son compte
         *  (2) Vérifier via l'URL que l'utilisateur est bien sur la bonne Tendance dans le cas d'une procédure depuis une Tendance.
         */
        
        $TRD = new TREND();
        $traboid = $TRD->trend_subscribe($this->KDIn["oid"],$this->KDIn["datas"]["ti"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $traboid) ) {
            $this->Ajax_Return("err",$traboid);
        }
        
        /******************************************************************************************************************************************************/
                    
        /*
         * [DEPUIS 17-06-16]
         *      Traitement du cas de l'enregistrement de l'activité
         */
        $PM = new POSTMAN();
        //On ajoute dans la table des Actions
        $args = [
            "uid"           => $this->KDIn["oid"],
            "ssid"          => session_id(),
            "locip_str"     => $_SESSION['sto_infos']->getCurrent_ipadd(),
            "locip_num"     => $this->KDIn["locip"],
            "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"           => __CLASS__,
            "fe_url"        => $this->KDIn["datas"]["cl"],
            "srv_url"       => $this->KDIn["srv_curl"],
            "url"           => $this->KDIn["srv_curl"],
            "isAx"          => 1,
            "refobj"        => $traboid,
            "uatid"         => 901,
            "uanid"         => 2
        ];
        $uai = $PM->UserActyLog_Set($args);


        /******************************************************************************************************************************************************/
                    
        
        
        $this->KDOut["FE_DATAS"] = true;    //[NOTE 13-10-14] @author L.C. La méthode renvoie l'identifiant de trabo de la base. Aussi, on ne renvoie que TRUE.
    }
    
    private function TryAboDisco($pl) {
        /*
         * Permet de lancer un processus de Déconnexion à une Tendance.
         * AMELIORATIONS : 
         *  (1) Vérifier que dans le cas de TMLNR, l'utilisateur est bien sur son compte
         *  (2) Vérifier via l'URL que l'utilisateur est bien sur la bonne Tendance dans le cas d'une procédure depuis une Tendance.
         */
        
        $TRD = new TREND();
        $r = $TRD->trend_disconnect($this->KDIn["oid"],$this->KDIn["datas"]["ti"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        }
        
        $this->KDOut["FE_DATAS"] = $r;    
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


        //* On vérifie que toutes les données sont présentes *//
        /*
         * LEGENDE vb1.10.14.1
         * u    : Identifiant de l'utilisateur Actif depuis FE. Fourni pour des raisons de controle de sécurité renforcé
         * t    : Identifiant de la Tendance
         * cl   : L'URL qui nous permettra d'encore mieux authentifier la requete
         * pl   : (Place) La page depuis laquelle la requete a été lancé. On utilise un seul WORKER pour des raisons pratiques
         * w    : (What) sta (STopAbo) ou ou sra (StaRtAbo) ?
         */
        $EXPTD = ["ui","ti","pl","w","cl"];

        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if (!( isset($v) && $v != "" )) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }

        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if (!$CXH->is_connected()) {
            $this->Ajax_Return("err", "__ERR_VOL_DNY_AKX");
        }

        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);

        if (!$exists) {
            $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
        }

        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        /*
         * [DEPUIS 22-06-16]
         */
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        //Controler les variables en entrée
        $this->DoesItComply_Datas();
        
        //Déterminer la méthode appelée en fonction de la demande de FE (Disco ? Co ?)
        if ( strtolower($this->KDIn["datas"]["w"]) === "sta" ) {
            $this->TryAboDisco($this->KDIn["datas"]["pl"]);
        } else {
            $this->TryAboCo($this->KDIn["datas"]["pl"]);
        }
        
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        
        //Mise à jour des données de la Tendance au niveau de SRH
        $TRD = new TREND();
        $TRD->onalter_update_archv_trend(["trd_eid"=>$this->KDIn["datas"]["ti"]]);
        
        exit(); //Parano
        /*
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
        //*/
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>