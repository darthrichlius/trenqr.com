<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_TIA_MYSRY_ADD extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if ( in_array($k,["pi","pt"]) && !( isset($v) && $v !== "" ) ) {
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
            
        }
        
    }
    
    
    private function Process() {
        
        $this->DoesItComply_Datas();
        
        $pi = $this->KDIn["datas"]["pi"];
        $pt = $this->KDIn["datas"]["pt"];
        $sc = $this->KDIn["datas"]["sc"];
        $fl = $this->KDIn["datas"]["fl"];
        
        
        $PA = new PROD_ACC();
        $cutab = $PA->on_read_entity(["accid" => $this->KDIn["oid"]]);
        
        $mysm_crea_datas = [
            "ouid"      => $this->KDIn["oid"],
            "text"      => $this->KDIn["datas"]["m"],
            "reflang"   => $this->KDIn["ulang"],
            "refcnty"   => $cutab["pdacc_ucnid"],
            "refcity"   => $cutab["pdacc_ucityid"],
            "ssid"      => session_id(),
            "locip"     => $this->KDIn["locip"],
            "curl"      => $this->KDIn["datas"]["cu"],
            "uagent"    => $this->KDIn["uagent"]
        ];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$mysm_crea_datas);
//        exit();
        
        $MYSM = new MYSTERY();
        
        $cdatas = $MYSM->on_create_entity($mysm_crea_datas);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cdatas) ) {
            $this->Ajax_Return("err",$cdatas);
        }
        
        /*
         * ETAPE :
         *      Dans le cas où nous avons accès aux données de référence, on récpère les données depuis cette référence.
         */
        if ( $pi && $pt && in_array($fl,["BY_DATE","TODAY_BY_DATE"]) ) {
            $refvote = $MYSM->onvote_sum($pi);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $refvote) ) {
                $this->Ajax_Return("err","__ERR_VOL_REF_GONE");
            }
            $ldatas = $MYSM->onread_select($this->KDIn["oid"],$sc,"TOP",$fl,null,[
                "rfid"      => $pi,
                "rftm"      => $pt,
                "refvote"   => $refvote,
                "fe_mode"   => true
            ]);
        } else {
            $ldatas = $MYSM->onread_select($this->KDIn["oid"],$sc,"FST",$fl,null,[
                "fe_mode"   => true
            ]);
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$ldatas);
//        exit();
        
        foreach ( $ldatas as $i => $mmtab ) {
            $FE[] = [
                "id"        => $mmtab["id"],
                "text"      => $mmtab["text"],
                "time"      => $mmtab["time"],
                "hashs"     => $mmtab["list_hash"],
                "cnvotes"   => $mmtab["cnvotes"],
                "sumvotes"  => $mmtab["sumvotes"],
                "reflang"   => $mmtab["reflang"],
                "refcnty"   => $mmtab["refcnty"],
                "refcity"   => $mmtab["refcity"],
                "candel"    => ( intval($this->KDIn["oid"]) === intval($mmtab["ouid"]) ) ? TRUE : FALSE,
                "lastvt"    => null
            ];
        }
        
        $FE_DATAS = [
            "alist"   => $FE
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
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["sc","fl","m","pi","pt","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,["pi","pt"]) ) {
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
        $this->KDIn["ulang"] = $STOI->getRunning_lang();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
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
        
        $this->Process();
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
