<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_EVAL_ACT extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /*********************************************************************/
    /********************** START SPECFIC METHODES ***********************/
    private function DoesItComply_Datas () {
        //On controle si les données fournies correspondent
        
        //RAPPEL : ["ec","t","i","mdl","pg_prop","curl]
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            switch ($k) {
                case "ec": //Le code EVAL lié à l'opération d'évaluation
                        //On vérifie si la donnée existe, est non NULL et est NON VIDE
                        if (! ( isset($v) && $v !== "" ) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
                        }
                        
                        //On vérifie que le code d'EVAL est connue et repertortiée
                        $EV = new EVALUATION();
                        $mdl = array_keys($EV->get_EVAL_TYPES());
                        if (! in_array(strtoupper($v), $mdl) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DIN_MSM");
                        }
                        
                    break;
                case "t": //Le type d'Article 'iml' ou 'itr'
                        /*
                         * [NOTA 07-09-14] @author Lou Carther <lou.carther@deuslynn-entreprise.com>
                         *      Cette donnée n'a aucune réelle importance.
                         *      Elle existe car elle date du developpement au niveau de FE et qu'on ne préfère pas tout cassé.
                         */
                    break;
                case "i": //L'entifiant externe de l'Article
                        //On vérifie si la donnée existe, est non NULL et est NON VIDE
                        if (! ( isset($v) && $v !== "" ) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
                        }
                        //On ne vérifie pas ici si l'Article existe car l'opération sera réalisée plus tard. Autant ne pas êrdre en performance.
                    break;
                case "mdl": //Le module qui a servi de support pour l'action
                        //On vérifie si la donnée existe, est non NULL et est NON VIDE
                        if (! ( isset($v) && $v !== "" ) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
                        }
                        
                        /*
                         * On vérifie que le module est connue et repertortiée
                         * [DEPUIS 20-06-16]
                         *      Ajout de "FKSA"
                         */
                        $mdl = ["arp","nwfd","unq","psmn","fksa"];
                        if (! in_array(strtolower($v), $mdl) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DIN_MSM");
                        }
                        
                    break;
                case "pg_prop": //Les données sur la page d'où a été effectuée l'action
                
                        /*
                         * SECURITY !!
                         * [NOTE 10-04-15] @BOR
                         * TODO : Il faut utiliser une méthode plus sécurisée.
                         * On préférera la méthode d'identification par url envoyé par FE qui est plus complexe 
                         * OU celle où l'URL est enregistré en SESSIOn et récupérée ulterieurement
                         */
                
                        //On vérifie si la donnée existe, est non NULL et est NON VIDE
                        if (! ( isset($v) && is_array($v) && count($v) === 2 && 
                                ( key_exists("pg", $v) && !empty($v["pg"]) ) && ( key_exists("ver", $v) && !empty($v["ver"]) ) ) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
                        }
                        
                        //On vérifie que la page est connue et repertortiée
                        $pg = ["tmlnr","trpg","fksa"];
                        if (! in_array(strtolower($v["pg"]), $pg) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DIN_MSM");
                        }
                        //On vérifie que la version de page est connue et repertortiée
                        $ver = ["wlc","ru","ro"];
                        if (! in_array(strtolower($v["ver"]), $ver) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DIN_MSM");
                        }
                        
                    break;
                case "curl" :
                        if (! ( is_string($v) && parse_url($v) ) ) {
                            $this->Ajax_Return("err","__ERR_VOL_DIN_MSM");
                        }
                    break;
                default:
                        $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
                    break;
            }
        }
        
    }
    
    private function AuthThisEval ($atab) {
        //QUESTION : L'utilisateur est-il autorisé à EVAL l'Article selon le choix fourni (SupaCool OU DL) ? 
        
        $AT = new ARTICLE_TR();
        $RL = new RELATION();
        if ( $AT->child_exists_with_id($atab["artid"],["BA_ART_TO"]) !== -1 ) {
            //QUESTION : L'Article est-il de type ITR ?
//            var_dump(__LINE__,__FUNCTION__,__FILE__);
            return TRUE;
        } 
       /*
        * [DEPUIS 07-07-16]
        */
        else if ( $atab["art_is_sod"] ) {
           return TRUE;
        }
        else if ( floatval($this->KDIn["oid"]) === floatval($atab["uid"]) ) {
            //QUESTION : L'Utilisateur est-il le propriétaire ?
//            var_dump(__LINE__,__FUNCTION__,__FILE__);
            return TRUE;
        } 
        else if ( is_array($RL->friend_theyre_friends($this->KDIn["oid"],$atab["uid"])) ) {
            //QUESTION : La Relation (FRIEND) entre CU et AOWN le permet-il ?
//            var_dump(__LINE__,__FUNCTION__,__FILE__);
            return TRUE;
        } 
        else {
            $r__ = $RL->onread_relation_exists_fecase($this->KDIn["oid"],$atab["uid"]);
            $n__ = $RL->encode_relcode($r__);
            if ( strtoupper($this->KDIn["datas"]["ec"]) === "_EVAL_CL" && in_array($n__, ["xr02","xr12","xr22"]) ) {
                //QUESTION : S'agit-il de CL ET une relation DFOLW ?
//                var_dump(__LINE__,__FUNCTION__,__FILE__);
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    
    private function Evaluate () {
        //* On lance le processus d'évaluation sur l'Article *//
        
        //On valide les données en entrée
        $this->DoesItComply_Datas();
        
        /*
         * ETAPE :
         * On vérifie que l'Article existe et on récupère sa définition
         */
        $ART = new ARTICLE();
        $art_tab = $ART->exists($this->KDIn["datas"]["i"]);
        
        if (! isset($art_tab) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $art_tab)  ) {
            $this->Ajax_Return("err",$art_tab);
        }
        
        /*
         * [NOTE 20-04-15] @BOR
         * ETAPE :
         * On vérifie que l'utilisateur a le droit d'EVAL l'Article selon son choix.
         */
        if (! $this->AuthThisEval($art_tab) ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        /*
         * ETAPE :
         * On procède à l'opération à proprement parlé
         */
        $args = [
            "actor"     => $this->KDIn["oid"],
            "eval_code" => $this->KDIn["datas"]["ec"],
            "art_eid"   => $this->KDIn["datas"]["i"]
        ];
        
        $EV = new EVALUATION();
        $r = $EV->on_create_entity($args);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
            $this->Ajax_Return("err",$r);
        } 
        
        //On met à jour les tables qui comportent des données liées à EVAL ([10-04-15] ?
        
        /*
         * [NOTE 10-04-15] @BOR
         * ETAPE :
         * On enregistre l'action de l'utilisateur.
         */
        //On détermine le code de l'action
        $uat = 700;
        $E_E = $EV->exists(["actor" => $this->KDIn["oid"], "artid" => $art_tab["artid"]]);
        if ( !$E_E || $E_E["evtype_fe"] === strtoupper("_EVAL_VOID") ) {
            //L'Acteur a "UNEVAL" l'Article
            $uat = 710;
        } else if ( floatval($art_tab["art_accid"]) === floatval($this->KDIn["oid"]) ) {
            //L'Acteur a EVAL un Article qui lui appartient
            $uat = 701;
        }
        
        $PM = new POSTMAN();
        $pm_args = [
            "uid"           => $this->KDIn["oid"],
            "ssid"          => session_id(),
            "locip_str"     => $_SESSION['sto_infos']->getCurrent_ipadd(),
            "locip_num"     => $this->KDIn["locip"],
            "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"           => __CLASS__,
            "fe_url"        => $this->KDIn["datas"]["curl"],
            "srv_url"       => $this->KDIn["srv_curl"],
            "url"           => $this->KDIn["srv_curl"],
            "isAx"          => 1,
            "refobj"        => $r["tbevlid"],
            "uatid"         => $uat,
            "uanid"         => 2
        ];
        $uai = $PM->UserActyLog_Set($pm_args);
        
        /*
         * On récupère les données sur l'EVAL (mis à jour)
         */
        $ar = $ART->on_read_entity(["art_eid" => $this->KDIn["datas"]["i"]]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ar) ) {
            $this->Ajax_Return("err",$ar);
        } 
        
        /*
         * [DEPUSI 30-04-15] @BOR
         * On détermine l'EVAL de l'utilisateur CU.
         * Cette donnée est à jour et permet de rendre la gestion d'EVAL plus stable au niveau de FE.
         */
        $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) 
            ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
        
        //FE = FrontEnd
        $FE_DATAS = [
            "eval"  => $ar["art_eval"],
            /*
             * [DEPUIS 30-04-15] @BOR
             *      Permet de mettre à jour l'EVAL visuellement de manière plus sûr
             */
            "me"    => $me
        ];
        
        /*
         * TODO : 
         *  -> Il faut utiliser curl pour vérifier sur quelle page nous nous trouvons
         *  -> Si on est sur TMLNR_RU, il faut envoyer le capital
         */
        //Si on est dans le cas de RO, on récupère le capital mis à jour
        
        if ( in_array(strtoupper($this->KDIn["curl_pcs"]["urqid"]), ["TMLNR_GTPG_RO","TMLNR_GTPG_RU"]) ) {
//        if ( strtolower($this->KDIn["datas"]["pg_prop"]["ver"]) === "ro" ) { //PAS ASSEZ SAFE et ne prend pas en compte TMLNR_RU
            $PA = new PROD_ACC();
            $ad = $PA->exists($this->KDIn["target"]["pdacc_eid"],TRUE);  
            $cap = $ad["pdacc_capital"];
            $FE_DATAS["ocap"] = $cap;
        }
        
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;        
        
    }
    
    /****************** END SPECFIC METHODES ********************/
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION
        session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * ec       : EvalCode
         * t        : Le type d'Article 'iml' ou 'itr'
         * i        : L'identifiant de l'ARTICLE
         * mdl      : Le module qui a servi de support pour l'action
         * pg_prop  : Les données sur la page d'où a été effectuée l'ACTION
         * curl     : L'URL de la page où a été déclenché l'ACTION
         */
        $EXPTD = ["ec","t","i","mdl","pg_prop","curl"];
        
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
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        /*
         * [NOTE 10-04-15] @BOR
         * ETAPE : 
         * On récupère la cible à partir de l'url fourni.
         * [NOTE 28-04-15] @BOR
         * Il faut prendre en compte que les URL des pages TRPG n'ont pas de "user".
         * On ne vérifie l'existence de la donnée "user" que pour les pages TMLNR
         */
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["curl"]);
        
//        var_dump($this->KDIn["datas"]["curl"],$upieces);
//        exit();
        
        $this->KDIn["curl_pcs"] = $upieces;
        if ( in_array(strtoupper($this->KDIn["curl_pcs"]["urqid"]), ["TMLNR_GTPG_RO","TMLNR_GTPG_RU","TMLNR_GTPG_WLC"]) ) {
            
            if ( $upieces && is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) {
                $PDACC = new PROD_ACC();
                $this->KDIn["target"] = $PDACC->exists_with_psd($upieces["user"],TRUE);
                if (! $this->KDIn["target"] ) {
                    $this->Ajax_Return("err","__ERR_VOL_U_G");
                }
            } 
            else {
                $this->Ajax_Return("err","__ERR_VOL_FAILED");
            }
        }
        
        $this->Evaluate();
        
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>