<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_TSTY_DL extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if ( !isset($v) && in_array($k,["atp"]) ) {
                continue;
            } else {
                
//                Les données ont déjà été vérifiées
//                if (! ( isset($v) && $v !== "" ) ) {
//                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
//                }
            
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
        
    }
    
    
    private function DelTesty() {
        /*
         * [NOTE 01-06-16]
         *      Pour RAPPEL, Il s'agit d'un code refactoriser.
         */
        $this->perfAtPoint($this->tm_start, __LINE__,TRUE);
        
        $this->DoesItComply_Datas();
        
        $this->perfAtPoint($this->tm_start, __LINE__,TRUE);
        
        $TST = new TESTY();
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        switch ($page) {
            case "TMLNR_GTPG_RO" :
            case "TMLNR_GTPG_RU" :
                    $psd = $this->KDIn["upieces"]["user"];
                
                    $PA = new PROD_ACC();
                    $tgtab = $PA->exists_with_psd($psd,TRUE);
                    if (! $tgtab ) {
                        $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
                    }

                    $iwd = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTADD");
                    $ird = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTSEE");
                    
                break;
            case "TQR_GTPG_HVIEW" :
                break;
            case "FKSA_GTPG" :
                break;
            default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                return;
        }
        
        $this->perfAtPoint($this->tm_start, __LINE__,TRUE);
        
        
        /*
         * ETAPE :
         *      On vérifie les permissions
         */
        $perm = $TST->ondelete_hasPermission($this->KDIn["oeid"],$this->KDIn["datas"]["i"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $perm) ) {
            $this->Ajax_Return("err",$perm);
        } 
        
        $this->perfAtPoint($this->tm_start, __LINE__,TRUE);
        
//        var_dump($this->KDIn["datas"]["i"]);
//        exit();
        
        /*
         * ETAPE :
         *      On supprime effectivement l'occurrence
         */
        $r = $TST->on_delete_entity($this->KDIn["datas"]["i"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        } 
        
        $this->perfAtPoint($this->tm_start, __LINE__,TRUE);

        $FE_DATAS = [
            "iwd"   => ( isset($iwd) ) ? TRUE : FALSE,
            "ird"   => ( isset($ird) ) ? TRUE : FALSE,
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
         * "i"      : L'Identifiant de TSM
         * "atp"    : [FACULTATIF] (ArticleTyPe) Permet de déterminer la PAGE sur laquelle TSM est affiché.
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["i","atp","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,["atp"]) ) {
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
    }
    
    public function on_process_in() {
        
        $this->perfAtPoint($this->tm_start, __LINE__,TRUE);
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        $this->DelTesty();
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
