<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_CHBX_PL_CNV extends WORKER  {
    
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
            $SKIP = ["pvt"];
            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
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
            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //CAS SPECIAUX
            $qsp = ["asd"];
            if ( $k === "qsp" && !in_array($v, $qsp) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            $flm = ["pf","tr"];
            if ( $k === "flm" && !in_array($v, $flm) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            $drt = ["top","fst","bot"];
            if ( $k === "drt" && !in_array($v, $drt) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
//            if ( $k === "rng" && !preg_match("/rng_([\d]+)/i", $v) ) {
//                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
//            }
            
        }
        
    }
    
    private function OnlyMyDatas ($case,$datas,$qsp,$flm) {
        
        $nd = $prl = $cnv = [];
        
        $CBMSG = new CHBX_MSG();
        switch ($case) {
            case "PULL_FIRST":
            case "PULL_FROM":
                foreach ( $datas as $v ) {
                    if ( $v["pdacc_todelete"] === 1 | $v["pdacc_todelete"] === "1" ) {
                        continue;
                    }
                    $nd["plcnv"][] = [
                        "cvid"      => $v["conv_eid"],
                        /*
                         * [DEPUIS 11-11-15] @author BOR
                         *      On renvoie le nombre de messages non lus
                         */
                        "cvird"     => ( $CBMSG->onread_UnreadGet($this->KDIn["oid"],$v["conv_eid"],["ONLYTHIS"]) ) ? $CBMSG->onread_UnreadGet($this->KDIn["oid"],$v["conv_eid"],["ONLYTHIS"]) : 0, 
                        //Message de présentation
                        "chbm_id"   => $v["chmsg_eid"],
                        "chbm_msg"  => $v["chmsg_msg"],
                        "chbm_cd"   => $v["chmsg_fe_cdate_tstamp"],
                        "chbm_rd"   => $v["chmsg_rdate_tstamp"],
                        //TARGET
                        "uid"       => $v["target_tab"]["pdacc_eid"],
                        "upsd"      => $v["target_tab"]["pdacc_upsd"],
                        "ufn"       => $v["target_tab"]["pdacc_ufn"],
    //                    "ugdr" => $v["pdacc_ugdr"],
                        "uppic"     => $v["target_tab"]["pic_rpath"],
                        "ufols"     => null, //[20-01-15]@Lou Non disponible 
                        "ucap"      => $v["target_tab"]["pdacc_capital"]
                         //?
     //                    "ucbsts" => $CBCONV->onread_ListFirstConvrs ($v["tgt_ueid"]) //[07-01-15] devrait faire partie des données liées à la configuration de ChatBox
                    ];
                }
                break;
            default:
                /*
                    $CBCONV = new CHBX_CONVRS();
                    if ( $flm === "pf" ) {
                        if ( key_exists("convoid", $datas) && !empty($datas["convoid"]) ) {
                            foreach ( $datas["convoid"] as $v ) {
                                if ( $v["tgt_todel"] === 1 | $v["tgt_todel"] === "1" ) {
                                    continue;
                                }
                                $nd["convoid"][] = [
                                     "cvid"   => $v["conv_eid"],
                                     //TARGET
                                     "uid"   => $v["tgt_ueid"],
                                     "upsd"  => $v["tgt_upsd"],
                                     "ufn"   => $v["tgt_ufn"],
                 //                    "ugdr" => $v["tgt_ugdr"],
                                     "uppic" => $v["tgt_uppic"],
                                     "ufols" => $v["tgt_ufols"],
                                     "ucap"  => $v["tgt_ucap"]
                                     //?
                 //                    "ucbsts" => $CBCONV->onread_ListFirstConvrs ($v["tgt_ueid"]) //[07-01-15] devrait faire partie des données liées à la configuration de ChatBox
                                ];
                            }
                        }
                        if ( key_exists("convers", $datas) && !empty($datas["convers"]) ) {
                            foreach ( $datas["convers"] as $v ) {
                                if ( $v["tgt_todel"] === 1 | $v["tgt_todel"] === "1" ) {
                                    continue;
                                }
                                $nd["convrs"][] = [
                                     "cvid" => $v["conv_eid"],
                                     //Message de présentation
                                     "chbm_id"  => $v["chmsg_eid"],
                                     "chbm_msg" => $v["chmsg_msg"],
                                     "chbm_cd"  => $v["chmsg_fe_cdate_tstamp"],
                                     "chbm_rd"  => $v["chmsg_rdate_tstamp"],
                                     //TARGET
                                     "uid"      => $v["tgt_ueid"],
                                     "upsd"     => $v["tgt_upsd"],
                                     "ufn"      => $v["tgt_ufn"],
                 //                    "ugdr" => $v["tgt_ugdr"],
                                     "uppic"    => $v["tgt_uppic"],
                                     "ufols"    => $v["tgt_ufols"],
                                     "ucap"     => $v["tgt_ucap"]
                                     //?
                 //                    "ucbsts" => $CBCONV->onread_ListFirstConvrs ($v["tgt_ueid"]) //[07-01-15] devrait faire partie des données liées à la configuration de ChatBox
                                ];
                            }
                        }
                       if ( key_exists("parleys", $datas) && !empty($datas["parleys"]) ) {
                           foreach ( $datas["parleys"] as $v ) {
                                if ( $v["tgt_todel"] === 1 | $v["tgt_todel"] === "1" ) {
                                    continue;
                                }
                                $nd["parleys"][] = [
                                     "uid"      => $v["tgt_ueid"],
                                     "upsd"     => $v["tgt_upsd"],
                                     "ufn"      => $v["tgt_ufn"],
                 //                    "ugdr" => $v["tgt_ugdr"],
                                     "uppic"    => $v["tgt_uppic"],
                                     "ufols"    => $v["tgt_ufols"],
                                     "ucap"     => $v["tgt_ucap"]
                                     //?
                 //                    "ucbsts" => $CBCONV->onread_ListFirstConvrs ($v["tgt_ueid"]) //[07-01-15] devrait faire partie des données liées à la configuration de ChatBox
                                ];
                            }
                       }
                    } else {
                        foreach ( $datas as $v ) {
                        }
                    }
                    //*/
                break;
        }
        
        return $nd;
    }
    
    private function Pull () {
        $this->DoesItComply_Datas(); 
        
//        $qt = $this->KDIn["datas"]["qt"];
        $pvt = $this->KDIn["datas"]["pvt"];
        $qsp = $this->KDIn["datas"]["qsp"];
        $flm = $this->KDIn["datas"]["flm"];
//        $rng = $this->KDIn["datas"]["rng"];
        //VALEURS : "top","fst","bot"
        $drt = $this->KDIn["datas"]["drt"];
        $pcf = $this->KDIn["datas"]["pcf"];
        
        $datas;
        $CBCONV = new CHBX_CONVRS();
        if (! $pvt ) {
            $case = "PULL_FIRST";
            $datas = $CBCONV->onread_ListFirstConvrs($this->KDIn["oeid"],NULL,NULL,TRUE);
        } else if ( isset($pvt) && !empty($pvt) ) {
            $case = "PULL_FROM";
            $datas = $CBCONV->onread_ListFromConvrs($this->KDIn["oeid"],$pvt,$drt,NULL,NULL,TRUE);
        }
//        var_dump($datas);
//        exit();
        /*
        $SRH = new SEARCH();
        if ( $qsp === "min" ) {
            if ( $flm === "pf" ) {
                $datas = $SRH->Profile_FirstResults($qt, $this->KDIn["oid"]);
            } else {
                $datas = $SRH->Trend_FirstResults($qt, $this->KDIn["oid"]);
            }
        } else {
            $ms = NULL;
            preg_match("/(at|kw|wn|pop)_rng_([\d]+)/i", $rng, $ms);
            $rows = intval($ms[2]); 
            
            $uid = ( key_exists("oid", $this->KDIn) && !empty($this->KDIn["oid"]) ) ? $this->KDIn["oid"] : NULL;
            
            if ( $flm === "pf" ) {
//                var_dump($qt,$flc,$rows,$uid);
                $datas = $SRH->Profile_MrReslt($qt, $flc, $rows, $uid);
            } else {
//                var_dump($qt,$flc,$rows,$uid);
                $datas = $SRH->Trend_MrReslt($qt, $flc, $rows, $uid);
            }
        }
        //*/
        
//        var_dump($this->KDIn["oid"],$qt,$qsp,$flm,$datas);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        } else if (! isset($datas) ) {
//            $this->Ajax_Return("err","__ERR_VOL_FAILED"); //[24-01-15] ? Pourquoi s'il n'y a aucun résultat, il faudrait déclencher une erreur ?
        } else {
            $this->KDOut["FE_DATAS"] = $this->OnlyMyDatas($case,$datas,$qsp,$flm);
//            $this->KDOut["FE_DATAS"] = $datas;
        }
                    
    }
    
    private function PullFrom ($pvt,$drt, $rng = NULL) {
        /*
         * Permet de récupérer les Conversations en fonction d'un pivot et selon la direction passé en paramètre
         */
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


        //* On vérifie que toutes les données sont présentes *//
        /*
         * "pvt"  : PiVoT,
         * "qsp"  : QueryScoPe,
         * "flm"  : FiLterMenu,
         * //OBSELETE
         * "rng"  : RaNGe: Utiliser pour spécifier la "page". Ce nombre est multiplier par limit pour obtenir le nombre de lignes à renvoyer
         * "drt"  : DiRecTion
         * "pcf"  : PullConFiguration
         * "curl" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["pvt","drt","qsp","flm","pcf","curl"];
        
        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
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
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
//            $A = new PROD_ACC();
//            $exists = $A->exists_with_id($oid);

//            if (!$exists) {
//                $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
//            }

        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
//            $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
//            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));

        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->Pull();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
        
        $PA = new PROD_ACC();
        //TODO : Log l'action
        $args = [
//            "uid" => ,
//            "ssid" => ,
//            "locip_str" => ,
//            "locip_num" => ,
//            "useragt" => ,
//            "wkr" => ,
//            "url" => ,
//            "isAx" => 
        ];
        $PA->UserActyLog_Set($args);
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}
?>