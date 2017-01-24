<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_GOFLWREL extends WORKER  {
    
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
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
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
          
            //CAS SPECIAUX : FollowMode
            $fm = ["f","u"];
            if ( $k === "fm" && !in_array($v, $fm) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
        }
        
    }
    
    
    private function SwitchRel () {
        
        $this->DoesItComply_Datas();
        
        //On crée le scope de la cible
        $PA = new PROD_ACC();
        $trg_tab = $PA->exists($this->KDIn["datas"]["i"],TRUE);
        
        if ( !$trg_tab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
            $this->Ajax_Return("err",$url_tab);
        } else {
            
            //On vérifie si on a une URL valide et identifiable selon TQR
            if ( $url_tab && is_array($url_tab) && count($url_tab) ) {
                
                //** On récupère les données supplémentaires s'il y a une correpondance de pseudo **//
                
                if ( isset($url_tab["user"]) && ( strtolower($url_tab["user"]) === strtolower($trg_tab["pdacc_upsd"]) ) ) {
                    $RL = new RELATION();
                    
                    if ( $this->KDIn["datas"]["fm"] === "f" ) {
                        //On lance le processus de création de la Relation
                        $r = $RL->on_create_entity(["acc_actor" => $this->KDIn["oid"],"acc_target" => $trg_tab["pdaccid"]]);
                    } else if ( $this->KDIn["datas"]["fm"] === "u" ) {
                        //On lance le processus de downgrade de la Relation
                        $r = $RL->onalter_downgrade_relation($this->KDIn["oid"],$trg_tab["pdaccid"]);
                    }
                    
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    
                    if (! $r ) {
                        $this->Ajax_Return("err","__ERR_VOL_FAILED");
                    } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
                        $this->Ajax_Return("err",$r);
                    }
                    
                    //-- On convertit le code au format FE. --//
                    //On récupère le code
                    $foo = $RL->onread_relation_exists_fecase($this->KDIn["oid"], $trg_tab["pdaccid"]);
                    $cdrl = $RL->encode_relcode($foo);
                    
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    
                    if (! $cdrl ) {
                        $this->Ajax_Return("err","__ERR_VOL_FAILED");
                    } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cdrl)  ) {
                        /*
                         * [DEPUIS 22-06-16] 
                         *      J'ai modifié cette ligne car elle ne me parait pas logique. 
                         *      Je n'ai pas effectuer de test de regressioin.
                         */
//                        $this->Ajax_Return("err",$r); 
                        $this->Ajax_Return("err",$cdrl);
                    }
                    
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    
                    $PA->on_read_entity(["acc_eid"=>$this->KDIn["datas"]["i"]]);
                    $folgs = $PA->onread_acquiere_my_following($trg_tab["pdaccid"]);
                    $folws = $PA->onread_acquiere_my_followers($trg_tab["pdaccid"]);
//                    var_dump($folws,$folgs);
        
                    $folg_nb = ( isset($folgs) ) ? count($folgs) : 0;
                    $folw_nb = ( isset($folws) ) ? count($folws) : 0;
                    
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    
                    /******************************************************************************************************************************************************/
                    
                    /*
                     * [DEPUIS 17-06-16]
                     *      Traitement du cas de l'enregistrement de l'activité
                     */
                    if ( $this->KDIn["datas"]["fm"] === "f" ) {

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
                            "refobj"        => $r["tbrel_id"],
                            "uatid"         => 300,
                            "uanid"         => 2
                        ];
                        $uai = $PM->UserActyLog_Set($args);

                    }
        
                    
                    /******************************************************************************************************************************************************/
                    
                    $FED = [
                        "upsd" => $trg_tab["pdacc_upsd"],
                        "uflr" => $folw_nb,
                        "uflw" => $folg_nb,
                        "ucap" => $PA->getPdacc_capital(),
                        /*
                         * [NOTE 19-10-14] @author L.C.
                         * On ne renvoie pas le nombre de Posts, il faut laisser faire les mécanismes de mise à jour des Articles qui s'en chargeront.
                         * En effet, si on touche au nombre d'Articles, il risque d'y avoir des incohérences visuelles au niveau de FE pour User.
                         */
                        "upnb" => $PA->getPdacc_stats_posts_nb(),
                        "utnb" => $PA->getPdacc_stats_mytrends_nb(),
                        "urel" => $cdrl
                    ];
                    
                    $this->KDOut["FE_DATAS"] = $FED;
                } else {
                    $this->Ajax_Return("err","__ERR_VOL_DENY");
                }
                
            } else {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
        
        }
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
        /*
            "i"     : (IMPORTANT) L'identifiant du compte cible
            "p"     : Le code la page depuis laquelle la requete a été lancée
            "v"     : La version de la page (ro,ru,...)
            "fm"    : (IMPORTANT) FollowMode : De quel cas s'agit-il ? (Follow (f) ? UnFollow (u))
            "cl"    : (IMPORTANT) cul
            //*/
        $EXPTD = ["i","p","v","fm","cl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MISG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MISG");
            }
        }
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MISG");
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
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
        /*
         * [DEPUIS 22-06-16]
         */
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function on_process_in() {
        $this->SwitchRel();
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