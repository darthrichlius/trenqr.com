<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TMLNR_ABME_GDS extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ( $this->KDIn["datas"] as $k => $v ) {
            
            if ( $k === "sec" && $v && explode("|",$v) ) {
                $sec_wanted = explode("|",$v); 
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
            
            $sec_list = ["all","sec_intro","sec_lvsp","sec_whyme","sec_imas","sec_suks"];
            $sec_intersec = array_intersect($sec_wanted,$sec_list);
            if ( $k === "sec" && !$sec_intersec ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            $this->KDIn["sec_wanted"] = $sec_intersec;
        }
    }
    
    
    private function GetDatas() {
        $this->DoesItComply_Datas();
            
        $SEC_INTRO; $SEC_LVSP; $SEC_WHYME; $SEC_IMAS; $SEC_SUKS;
        $TQR  = new TRENQR();
        $HVIEW = new HVIEW();
        $PA = new PROD_ACC();
        
        $user = strtoupper($this->KDIn["upieces"]["user"]);
        
        /*
         * ETAPE :
         *      On vérifie que l'utilisateur cible existe
         */
        $utab = $PA->exists_with_psd($user,TRUE);
        if (! $utab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$utab);
//        exit();
        
        foreach ($this->KDIn["sec_wanted"] as $sec) {
            $sec = strtoupper($sec);
            switch ($sec) {
                case "ALL" :
                        $SEC_INTRO = $PA->abme_intro_get($utab["pdaccid"],["WFEO"]);
                        $SEC_LVSP = $PA->abme_lvsp_get($utab["pdaccid"],["WFEO"]);
                        $SEC_WHYME = $PA->abme_whyme_get($utab["pdaccid"],["WFEO"]);
                        $SEC_IMAS = $PA->abme_imas_get($utab["pdaccid"],["WFEO"]);
    //                    $SEC_SUKS = $PA->abme_suks_get($utab["pdaccid"],["WFEO"]);
                        
                        /*
                         * ETAPE :
                         *      On arrête tout !
                         */
                        break;
                    break;
                case "SEC_INTRO" :
                        $SEC_INTRO = $PA->abme_intro_get($utab["pdaccid"],["WFEO"]);
                    break;
                case "SEC_LVSP" :
                        $SEC_LVSP = $PA->abme_lvsp_get($utab["pdaccid"],["WFEO"]);
                    break;
                case "SEC_WHYME" :
                        $SEC_WHYME = $PA->abme_whyme_get($utab["pdaccid"],["WFEO"]);
                    break;
                case "SEC_IMAS" :   
                        $SEC_IMAS = $PA->abme_imas_get($utab["pdaccid"],["WFEO"]);
                    break;
                case "SEC_SUKS" :   
                        $SEC_SUKS = $PA->abme_suks_get($utab["pdaccid"],["WFEO"]);
                    break;
                default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$SEC_INTRO,$SEC_LVSP,$SEC_WHYME,$SEC_IMAS,$SEC_SUKS]);
//        exit();
        
        $FE_DATAS = [
            "SEC_INTRO"     => ( $SEC_INTRO )   ? $SEC_INTRO    : [],
            "SEC_LVSP"      => ( $SEC_LVSP )    ? $SEC_LVSP     : [],
            "SEC_WHYME"     => ( $SEC_WHYME )   ? $SEC_WHYME    : [],
            "SEC_IMAS"      => ( $SEC_IMAS )    ? $SEC_IMAS     : [],
            "SEC_SUKS"      => ( $SEC_SUKS )    ? $SEC_SUKS     : []
        ];
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
        
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
         * "sec"    : "all" | "sec_intro" | "sec_lvsp" | "sec_whyme" | "sec_imas" | "sec_suks" 
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["sec","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" ) && !in_array($k,["mode"]) ) {
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
        if ( $CXH->is_connected() ) {
            $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
    }
    
    public function on_process_in() {
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
//            var_dump($this->KDIn["datas"]["cu"],$upieces);
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        
        $this->GetDatas();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() { }
}

?>