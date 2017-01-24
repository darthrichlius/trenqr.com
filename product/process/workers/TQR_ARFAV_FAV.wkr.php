<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_ARFAV_FAV extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            /*
            if ( in_array($k,[""]) && !( isset($v) && $v !== "" ) ) {
                continue;
            }
            //*/
            
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
        }
    }
    
    
    private function Perform() {
        
        $this->DoesItComply_Datas();
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        if (! in_array($page,["TMLNR_GTPG_RO","TMLNR_GTPG_RU","TRPG_GTPG_RO","TRPG_GTPG_RFOL","TRPG_GTPG_RU","FKSA_GTPG"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
        }
        
        if ( in_array($page, ["TMLNR_GTPG_RO","TMLNR_GTPG_RU"]) ) {
            $psd = $this->KDIn["upieces"]["user"];
            $PA = new PROD_ACC();
            $tgtab = $PA->exists_with_psd($psd,TRUE);
            if (! $tgtab ) {
                $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
            }

//            var_dump(__LINE__,__FUNCTION__,__FILE__,$tgtab);
//            exit();
        }
        
        $entry = [
            "i"         => $this->KDIn["datas"]["i"],
            "a"         => $this->KDIn["datas"]["a"],
            "ssid"      => session_id(),
            "locip"     => $this->KDIn["locip"],
            "uagent"    => $this->KDIn["uagent"]
        ];
        
//        var_dump(__LINE__,__FUNCTION__,__FILE__,$entry);
//        exit();
        
        $ART = new ARTICLE();
        
        $FE = [];
        switch ($entry["a"]) {
            case "ART_XA_FAV_PUB" :
            case "ART_XA_FAV_PRI" :
            case "ART_XA_UNFAV" :
                    $r = $ART->Favorite_Action($this->KDIn["oeid"],$entry["i"],$entry["a"],$entry["ssid"],$entry["locip"],$entry["uagent"],[
                        "RETURN_DATAS_ANYW" => TRUE
                    ]);
                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                        $this->Ajax_Return("err",$r);
                    } 

                    $FE_DATAS = [
                        "i"     => $this->KDIn["datas"]["i"],
                        "lc"    => $ART->Favorite_Count($entry["i"]),
                        "xt"    => $this->KDIn["datas"]["xt"]
                    ];
                break;
            default:
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
        }
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
        
        /*
         * [DEPUIS 31-07-16]
         *      On récupère les données pour pouvoir enregistrer l'ACTION.
         *      On vérifie que les données correspondent à une nouvelle occurrence, sinon il n'est pas necessaire d'enregistrer les données.
         */
        if ( $r && !$r["datas_is_previous"] ) {
            $this->KDOut["LOG_DATAS"] = [
                "axcode"    => $entry["a"],
                "rdatas"    => $r,
            ];
        }
        
    }
    
    private function LogUsertagActy ($log_datas) {
         
        $axcode = $log_datas["axcode"];
        $rdatas = $log_datas["rdatas"];
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        
        /*
         * ETAPE :
         *      On récupère les données sur l'ARTICLE
         */
        $artid = $rdatas["arfv_artid"];
        $ART = new ARTICLE();
        $atab = $ART->exists_with_id($artid);
        
        $axcode_db = NULL;
        switch ($axcode) {
            case "ART_XA_FAV_PUB" :
                    $axcode_db = "PUBLIC";
                    $uatid = ( floatval($atab["art_accid"]) === floatval($this->KDIn["oid"]) ) ? 801 : 803;
                break;
            case "ART_XA_FAV_PRI" :
                    $axcode_db = "PRIVATE";
                    $uatid = ( floatval($atab["art_accid"]) === floatval($this->KDIn["oid"]) ) ? 801 : 803;
                break;
            case "ART_XA_UNFAV" :
                    $uatid = ( floatval($atab["art_accid"]) === floatval($this->KDIn["oid"]) ) ? 802 : 804;
                break;
            default:
                return;
        }
        
        /*
         * ETAPE :
         *      On ajoute dans la table des Actions
         */
        $PM = new POSTMAN();
        $args = [
            "uid"       => $this->KDIn["oid"],
            "ssid"      => session_id(),
            "locip_str" => $_SESSION['sto_infos']->getCurrent_ipadd(),
            "locip_num" => $this->KDIn["locip"],
            "useragt"   => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"       => __CLASS__,
            "fe_url"    => $this->KDIn["datas"]["cu"],
            "srv_url"   => $this->KDIn["srv_curl"],
            "url"       => $this->KDIn["srv_curl"],
            "isAx"      => 1,
            "refobj"    => $rdatas["arfv_id"],
            "uatid"     => $uatid,
            "uanid"     => 2
        ];
        $uai = $PM->UserActyLog_Set($args,TRUE,$axcode_db,$page);
        
    }

    /**************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "i"  : AID
         * "a"  : ACTION
         * "xt" : ID de l'opération
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["i","a","xt","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,[""]) ) {
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
        if (! $CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    
    public function on_process_in() {
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        
        $this->Perform();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"], FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        if ( $this->KDOut["LOG_DATAS"] ) {
            $this->LogUsertagActy($this->KDOut["LOG_DATAS"]);
        }
        
        exit();
        
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

}

?>
