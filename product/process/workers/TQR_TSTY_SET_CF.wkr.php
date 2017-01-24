<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_TSTY_SET_CF extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if ( in_array($k,["iwd","ird"]) && !( isset($v) && $v !== "" ) ) {
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
             
             
            if ( in_array($k,["iwd","ird"]) && !( is_array($v) ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            if ( in_array($k,["iwa"]) && !in_array($v,["ONLY_FRD_N_FLWR","ONLY_FRD","EVRBDY"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            if ( in_array($k,["ira"]) && !in_array($v,["TQR_INSD","EVRBDY"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
        }
        
    }
    
    
    private function AddTesty() {
//        ["iwa","iwd","ira","ird","cu"]
        $this->DoesItComply_Datas();
        
//        var_dump(__LINE__,__FILE__,$this->KDIn["datas"]);
//        exit();
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        if (! in_array($page,["TMLNR_GTPG_RO"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
        }
        
        $PA = new PROD_ACC();
        $tgtab = $PA->exists($this->KDIn["oeid"],TRUE);
        if (! $tgtab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        
        $TST = new TESTY();
        
        /*
         * ETAPE :
         *      On insère les données de base
         */
        $sets = [
            "WCADD" => $this->KDIn["datas"]["iwa"],
            "WCSEE" => $this->KDIn["datas"]["ira"]
        ];
        $inis = $TST->config_set_inis($this->KDIn["oeid"], $sets, $this->KDIn["locip"], session_id(), $this->KDIn["uagent"]);
        $fnl_inis = [];
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $inis) ) {
            $this->Ajax_Return("err", $inis);
        } else {
            $prms = array_flip($TST->get_TST_CONF_INI());
            $fnl_inis = [
                "iwa" => $prms[$inis["tst_cnf_wcadd"]], 
                "ira" => $prms[$inis["tst_cnf_wcsee"]]  
            ];
        }
        
        $gt_dny = [];
        /*
         * ETAPE :
         *      On insère les données sur les interdictions pour certains utilisateurs : WCNTADD
         */
        $dnyfr_iwd = [
            "ouid"      => $this->KDIn["oeid"],
            "type"      => "WCNTADD",
            "ssid"      => session_id(),
            "locip"     => $this->KDIn["locip"],
            "uagent"    => $this->KDIn["uagent"],
            "sets"      => $this->KDIn["datas"]["iwd"]
        ];         
        $gt_dny_iwd = $TST->config_set_denyfor($dnyfr_iwd["ouid"], $dnyfr_iwd["type"], $dnyfr_iwd["locip"], $dnyfr_iwd["ssid"], $dnyfr_iwd["uagnt"], $dnyfr_iwd["sets"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $gt_dny_iwd) ) {
            $this->Ajax_Return("err", "__ERR_VOL_PARTIAL_IWD");
        } 
//        else if ( is_array($gt_dny_iwd) ) {
//            $gt_dny = $gt_dny_iwd;
//        }
            
        /*
         * ETAPE :
         *      On insère les données sur les interdictions pour certains utilisateurs : WCNTSEE
         */
        $dnyfr_ird = [
            "ouid"      => $this->KDIn["oeid"],
            "type"      => "WCNTSEE",
            "ssid"      => session_id(),
            "locip"     => $this->KDIn["locip"],
            "uagent"    => $this->KDIn["uagent"],
            "sets"      => $this->KDIn["datas"]["ird"]
        ];         
        $gt_dny_ird = $TST->config_set_denyfor($dnyfr_ird["ouid"], $dnyfr_ird["type"], $dnyfr_ird["locip"], $dnyfr_ird["ssid"], $dnyfr_ird["uagnt"], $dnyfr_ird["sets"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $gt_dny_ird) ) {
            $this->Ajax_Return("err", "__ERR_VOL_PARTIAL_IRD");
        } 
//        else if ( is_array($gt_dny_ird) ) {
//            if ( $gt_dny ) {
//                $gt_dny = array_merge($gt_dny,$gt_dny_ird);
//            } else {
//                $gt_dny = $gt_dny_ird;
//            }
//        }
            
        /*
        
        $fnl_gt_dny = [];
        if ( $gt_dny ) {
            
            $prms = array_flip($TST->get_TST_CONF_DNY_INI_TYPE());
            foreach ($gt_dny as $e) {
                $ut = $PA->exists_with_id($e["tcdf_tguid"],TRUE);
                if (! $ut ) {
                    continue;
                }
                
                if ( $prms[$e["tcdf_ini_type"]] === "WCNTADD" ) {
                    $fnl_gt_dny["iwd"][] = [
                        "uid"   => $ut["pdacc_eid"],
                        "ufn"   => $ut["pdacc_ufn"],
                        "upsd"  => $ut["pdacc_upsd"],
                    ];
                } else if ( $prms[$e["tcdf_ini_type"]] === "WCNTSEE" ) {
                    $fnl_gt_dny["ird"][] = [
                        "uid"   => $ut["pdacc_eid"],
                        "ufn"   => $ut["pdacc_ufn"],
                        "upsd"  => $ut["pdacc_upsd"],
                    ];
                }
            }
        }
        //*/
         /*
         * ETAPE :
         *      Récupère les informations sur les interdictions d'accès GET pour certains utilisateurs
         */
        $gt_dny = $TST->config_get_denyfor($this->KDIn["oeid"]);
        $fnl_gt_dny = [];
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $gt_dny) ) {
            $this->Ajax_Return("err", $gt_dny);
        } else if ( $gt_dny ) {
            
            $prms = array_flip($TST->get_TST_CONF_DNY_INI_TYPE());
            foreach ($gt_dny as $e) {
                $ut = $PA->exists_with_id($e["tcdf_tguid"],TRUE);
                if (! $ut ) {
                    continue;
                }
                
                if ( $prms[$e["tcdf_ini_type"]] === "WCNTADD" ) {
                    $fnl_gt_dny["iwd"][] = [
                        "uid"   => $ut["pdacc_eid"],
                        "ufn"   => $ut["pdacc_ufn"],
                        "upsd"  => $ut["pdacc_upsd"],
                    ];
                } else if ( $prms[$e["tcdf_ini_type"]] === "WCNTSEE" ) {
                    $fnl_gt_dny["ird"][] = [
                        "uid"   => $ut["pdacc_eid"],
                        "ufn"   => $ut["pdacc_ufn"],
                        "upsd"  => $ut["pdacc_upsd"],
                    ];
                }
            }
        }
        
        $FE[] = [
            "iwa"   => $fnl_inis["iwa"],
            "iwd"   => ( $fnl_gt_dny && $fnl_gt_dny["iwd"] ) ? $fnl_gt_dny["iwd"] : NULL,
            "ira"   => $fnl_inis["ira"],
            "ird"   => ( $fnl_gt_dny && $fnl_gt_dny["ird"] ) ? $fnl_gt_dny["ird"] : NULL,
        ];
        
        $FE_DATAS =  $FE;
        
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
         * "iwa"   : Ini sur WRITE authorisation
         * "iwd"   : Ini sur WRITE deny
         * "ira"   : Ini sur READ authorisation
         * "ird"   : Ini sur READ deny
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        
        $EXPTD = ["iwa","iwd","ira","ird","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,["iwd","ird"]) ) {
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
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        
        $this->AddTesty();
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
