<?php


class WORKER_TQR_RLC_PLAC extends WORKER  {
    
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
            if ( !( isset($v) && $v !== "" ) 
                && ( 
                    ( in_array($k,["fl"]) ) 
                    || ( in_array($k,["pi","pt"]) && $this->KDIn["datas"]["dr"] === "FST" ) 
            ) ) {
                continue;
            }
            
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
             
             if ( $k === "sc" && !in_array(strtoupper($v),["_SEC_FOLW","_SEC_FOLG","_SEC_RCPFOLW"]) ) {
                 $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
             }
             if ( $k === "dr" && !in_array(strtoupper($v),["FST","TOP","BTM"]) ) {
                 $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
             }

        }
        
    }
    
    
    private function PullAcc () {
        
        $this->DoesItComply_Datas();
        
        $PA = new PROD_ACC();
        
        /*
         * ETAPE :
         *      On récupère les données sur l'utilisateur CIBLE via l'URL
         */
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        switch ($page) {
            case "TMLNR_GTPG_RO":
            case "TMLNR_GTPG_RU":
                    $psd = $this->KDIn["upieces"]["user"];

                    $tgtab = $PA->exists_with_psd($psd,TRUE);
                    if (! $tgtab ) {
                        $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
                    }
                    
                break;
            default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACk");
                break;
        }
        
        $this->KDOut["tgtab"] = $tgtab;
        
        $sc = $this->KDIn["datas"]["sc"];
        $fl = $this->KDIn["datas"]["fl"];
        $dr = $this->KDIn["datas"]["dr"];
        $pi = $this->KDIn["datas"]["fl"];
        $pt = $this->KDIn["datas"]["fl"];
        $xt = $this->KDIn["datas"]["xt"];
        
        /*
         * ETAPE :
         *      On récupère les données en fonction de la demande de CU
         */
        switch ($sc) {
            case "_SEC_FOLW" : 
                    /*
                     * TODO :
                     *      Utiliser une méthode qui prend en compte la gestion de la direction   
                     */
                    $list = $PA->onread_acquiere_my_followers($tgtab["pdaccid"]);
                break;
            case "_SEC_FOLG" :
                    /*
                     * TODO :
                     *      Utiliser une méthode qui prend en compte la gestion de la direction   
                     */
                    $list = $PA->onread_acquiere_my_following($tgtab["pdaccid"]);
                break;
            case "_SEC_RCPFOLW" :
                    /*
                     * TODO :
                     *      Récupérer les données
                     */
                    $list = [];
                break;
            default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACk");
                break;
        }
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $list)  ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        $FE = [];
        foreach ($list as $k => $data) {
            $FE[$k] = [
                "i"         => $data["pdacc_eid"],
                "fn"        => $data["pdacc_ufn"],
                "ps"        => $data["pdacc_upsd"],
                "pp"        => $data["pdacc_uppic"],
                "pb"        => html_entity_decode($data["pdacc_profilbio"]),
                "tm"        => $data["tbrel_datecrea_tstamp"],
                "isbj"      => FALSE // TODO : A affiner !    
            ];
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$FE);
//        exit();
        
        $FE_DATAS = [
            "xt"    => $xt,
            "list"  => $FE,
//            "list"  => [], //DEV, TEST, DEBUG
            "flrnb" => $PA->onread_get_myfolrs_count($tgtab["pdaccid"]),
            "flgnb" => $PA->onread_get_myfolgs_count($tgtab["pdaccid"]),
            "cuio"  => ( $this->KDIn["oid"] && floatval($this->KDIn["oid"]) === floatval($tgtab["pdaccid"]) ) ? TRUE : FALSE
        ];
        
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
         * "sc"     : La section ("_SEC_FOLW","_SEC_FOLG","_SEC_RCPFOLW")
         * "fl"     : [FACULTATIF]Le filtre utilisé 
         * "dr"     : La direction (FST, BTM, TOP)
         * "pi"     : [FACULTATIF]L'ID externe de l'élement qui sert de PIVOT 
         * "pt"     : [FACULTATIF] Le TIME de l'élement qui sert de PIVOT
         * "xt"     : L'identifiant de l'opération qui permet de fiabiliser l'affichage des commentaires en prenant en compte le temps de réponse
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["sc","fl","dr","pi","pt","xt","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,["fl","pi","pt"]) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
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
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
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
        
        $this->PullAcc();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $PM = new POSTMAN();
            $sc = strtolower($this->KDIn["datas"]["sc"]);
            $dr = strtolower($this->KDIn["datas"]["dr"]);
            switch ($sc) {
                case "_sec_folw":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDOut["tgtab"]["pdaccid"], 307, TRUE, $dr);
                    break;
                case "_sec_folg":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDOut["tgtab"]["pdaccid"], 308, TRUE, $dr);
                    break;
                default:
                    break;
            }
            
        }
        exit();
        
    }
    
    protected function prepare_params_in_if_exist() { }
    
}
?>