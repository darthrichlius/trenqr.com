<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_FRD_GOFRD extends WORKER  {
    
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
          
        }
        
    }
    
    
    private function Friend () {
        
        /*
         * [NOTE 19-10-14] @author L.C.
         * A la version vb1, cet URQ ne sert que pour les demandes depuis le header de TMLNR.
         * Aussi, on effectue un controle au niveau de l'URL pour assurer la fiabilité et la sécurité du processus.
         */
        
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
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
            $this->Ajax_Return("err",$url_tab);
        } else {
            
            //On vérifie si on a une URL valide et identifiable selon TQR
            if ( $url_tab && is_array($url_tab) && count($url_tab) ) {
                
                //** On récupère les données supplémentaires s'il y a une correpondance de pseudo **//
                
                if ( isset($url_tab["user"]) && ( strtolower($url_tab["user"]) === strtolower($trg_tab["pdacc_upsd"]) ) ) {
                    $RL = new RELATION();
                    
                    $r = $RL->friend_ask_as_a_friend($this->KDIn["oid"], $trg_tab["pdaccid"]);
                    
                    if ( !$r ) {
                        $this->Ajax_Return("err","__ERR_VOL_FAILED");
                    } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
                        $this->Ajax_Return("err",$r);
                    }
                    
                    //-- On convertit le code au format FE. --//
                    //On récupère le code
                    $foo = $RL->onread_relation_exists_fecase($this->KDIn["oid"], $trg_tab["pdaccid"]);
                    $cdrl = $RL->encode_relcode($foo);
                    
                    if (! $cdrl ) {
                        $this->Ajax_Return("err","__ERR_VOL_FAILED");
                    } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cdrl)  ) {
                        $this->Ajax_Return("err",$cdrl);
                    }
                    //*/
                    
                    //On récupère des données pour mettre à jour OW
                    $PA->on_read_entity(["acc_eid"=>$this->KDIn["datas"]["i"]]);
                    $folgs = $PA->onread_acquiere_my_following($trg_tab["pdaccid"]);
                    $folws = $PA->onread_acquiere_my_followers($trg_tab["pdaccid"]);
//                    var_dump($folws,$folgs);
        
                    $folg_nb = ( isset($folgs) ) ? count($folgs) : 0;
                    $folw_nb = ( isset($folws) ) ? count($folws) : 0;
        
                    $FED = [
                        "ufn"   => $trg_tab["pdacc_ufn"],
                        "upsd"  => $trg_tab["pdacc_upsd"],
                        "uflr"  => $folw_nb,
                        "uflw"  => $folg_nb,
                        "ucap"  => $PA->getPdacc_capital(),
                        /*
                         * [NOTE 19-10-14] @author L.C.
                         * On ne renvoie pas le nombre de Posts, il faut laisser faire les mécanismes de mise à jour des Articles qui s'en chargeront.
                         * En effet, si on touche au nombre d'Articles, il risque d'y avoir des incohérences visuelles au niveau de FE pour User.
                         */
                        "utnb"  => $PA->getPdacc_stats_mytrends_nb(),
                        "urel"  => $cdrl
//                        "urel" => "xr03"
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
        session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
            "i": (IMPORTANT) L'identifiant du compte cible
            "rl": La relation actuellement affichée au niveau de FE (xr01,xr02,...)
            "cl": (IMPORTANT) curl
            //*/
        $EXPTD = ["i","rl","cl"];
        
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
    }

    public function on_process_in() {
        $this->Friend();
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