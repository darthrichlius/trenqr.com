<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_EC_REDO extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if ( $k === "d" && $v === "" ) {
                continue;
            }
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
//            //On vérifie s'il s'agit d'un cas de body
//            if ( $k === "t" ) {
                
            //On vérifie que les données pour le "body" du commentaire sont valides selon les règles en vigueur au niveau du WORKER
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
//            }
            
            $istr = ["k","cu"];
            if ( $v && in_array($k,$istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }    
            
        }
    
    }
    
    
    private function Trigger () {
        
        $this->DoesItComply_Datas();
        $key = $this->KDIn["datas"]["k"];
        $ueid = $this->KDIn["oeid"];
        
        $TA = new TQR_ACCOUNT();
        /*
         * ETAPE :
         *      [SECURITE & FIABILITE] On vérifie que l'opération existe. 
         */
        $ectab_client = $TA->EC_Exists($key);
        if (! $ectab_client ) {
            $this->Ajax_Return("err","__ERR_VOL_NOT_FND");
        }
        
        /*
         * ETAPE :
         *      [SECURITE & FIABILITE] On vérifie que l'occurrence est en attente de validation.
         */
        if ( !empty($ectab_client["cnfeml_rsltdt_tstamp"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_NOT_XPTD");
        }
        
        /*
         * ETAPE :
         *      [SECURITE & FIABILITE] On s'assure que la dernière occurrence en attente correspond celle envoyée.
         */
        $ectab_last = $TA->EC_GetLastPending($ueid);
        if ( floatval($ectab_client["cnfeml_id"]) !== floatval($ectab_last["cnfeml_id"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_MATCHING");
        }
        
        /*
         * ETAPE : 
         *      On récupère la table de l'utilisateur avec les données mises à jour.
         *      Surtout les données sur l'email au cas où l'adresse aurait changé.
         */
        $utab = $TA->on_read_entity(["acc_eid" => $this->KDIn["oeid"]]);
        
        /*
         * ETAPE :
         *      On lance l'opération en écrasant la précedente
         */
        $r__ = $TA->EC_NewOper($this->KDIn["oeid"], $utab["acc_eml"], $this->KDIn["locip"], "ACCOUNT_CREATION", session_id(), $this->KDIn["uagent"], TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r__ ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        /*
         * IMPORTANT :
         *      Le client devra reload pour que la nouvelle clé soit mise à jour. 
         *      En effet, nous ne renvoyons pas la clé par mesure de sécurité.
         */
        $this->KDOut["FE_DATAS"] = TRUE;

    }
    
    /****************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["k","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
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
        if (! $CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];

        $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
                
    }

    public function on_process_in() {
        $this->Trigger();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

}

?>
