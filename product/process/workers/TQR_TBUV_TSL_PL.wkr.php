<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_TBUV_TSL_PL extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if ( in_array($k,["pi","pt"]) && !( isset($v) && $v !== "" ) && $this->KDIn["datas"]["dr"] === "FST" ) {
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
             
             if ( $k === "dr" && !in_array(strtoupper($v),["FST","TOP","BTM"]) ) {
                 $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
             }

        }
        
    }
    
    
    private function GetLikes() {
        
        $this->KDIn["datas"]["dr"] = strtoupper($this->KDIn["datas"]["dr"]);
        
        $this->DoesItComply_Datas();
        
        $TST = new TESTY();
        $tstab = $TST->exists($this->KDIn["datas"]["ti"]);
        if (! $tstab ) {
            return "__ERR_VOL_TST_GONE";
        }
        $this->KDIn["datas"]["tii"] = $tstab["tstid"];
        
        
        $PA = new PROD_ACC();
        $tgtab = $PA->exists_with_id($tstab["tst_tguid"],TRUE);
        if (! $tgtab ) {
            $this->Ajax_Return("err","__ERR_VOL_TGT_GONE");
        }
        
        
        /*
         * ETAPE :
         *      On vérifie les permissions
         */
        $cu_ico = ( $this->KDIn["oeid"] ) ? TRUE : FALSE;
        $oeid = ( $this->KDIn["oeid"] ) ? $this->KDIn["oeid"] : NULL;
        $perm = $TST->onread_hasPermission($tgtab["pdacc_eid"], $oeid, $cu_ico);
//        var_dump($perm);
//        exit();
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $perm) ) {
            $this->Ajax_Return("err",$perm);
        } else if ( $perm === TRUE ) {
            $FE = [];
            $OPS = (! $this->KDIn["oid"] ) ? [] : [
                "cuid"  => $this->KDIn["oid"]
            ];
            
            /*
             * ETAPE :
             *      On récupère les données en fonction du cas en présence
             */
            $tslds;
            if ( $this->KDIn["datas"]["pi"] && $this->KDIn["datas"]["pt"] ) {
                $tslds = $TST->Like_Pull($this->KDIn["datas"]["ti"],$this->KDIn["datas"]["dr"],$this->KDIn["datas"]["pi"],$this->KDIn["datas"]["pt"],TRUE,$OPS); 
//                $tsrds = $TST->onread_getTesties($tgtab["pdacc_eid"], $this->KDIn["datas"]["dr"], $this->KDIn["datas"]["pi"], $this->KDIn["datas"]["pt"]);
            } else {
                $tslds = $TST->Like_Pull($this->KDIn["datas"]["ti"],"FST",NULL,NULL,TRUE,$OPS);
            }
            if ( $tslds && $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tslds) ) {
                $this->Ajax_Return("err", $tslds);
            }
            
            
            /*
             * [DEPUIS 11-12-15] @author BOR
             *      On réccupère la table de l'élément épinglé.
             *      On ne le fait que dans le cas de FST. 
             * [NOTE]
             *      Dans les cas autres que FST, si FE reçoit un TESTY qui est PINNED, il ne doit pas le considérer comme PINNED.
             *      Les données se mettront à jour au RELOAD.
             */
            /*
            if ( strtoupper($this->KDIn["datas"]["dr"]) === "FST" ) {
                $pinned = $TST->Pin_WhoIsPin($tgtab["pdaccid"],TRUE);
                array_unshift($tsrds,$pinned);
            }
            //*/
            
            $tstmetas = [];
            if ( $tslds ) {
                $tsotab = $PA->exists_with_id($tstab["tst_ouid"]);
                
                $tstmetas = [
                   /*
                    * cdl : CanDelete
                    *      (1) L'utilisateur connecté est le propriétaire
                    *      (2) L'utilisateur connecté est la cible du message
                    */
                   "cdl"    => ( floatval($this->KDIn["oid"]) === floatval($tsotab["pdaccid"]) | floatval($this->KDIn["oid"]) === floatval($tstab["tst_tguid"]) ) ? TRUE : FALSE,
                   /*
                    * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                    * NOTE :
                    *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                    */
                   "clk"    => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                   //QUESTION ? L'utilisateur a t-il LIKE ?
                   "hslk"   => ( $this->KDIn["oid"] && $TST->Like_HasLiked($this->KDIn["oid"], $tstab["tstid"]) ) ? TRUE : FALSE,
                   //QUESTION ? Le nombre de LIKE ?
                   "cnlk"   => $TST->Like_Count($tstab["tst_eid"]),
                   //QUESSTION ? Le nombre de REACT
                   "cnrct"  => $TST->React_Count($tstab["tst_eid"])
                ];
                
            }
            
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tsrds,$tstmetas);
//        exit();
            
        if ( $this->KDIn["oeid"] ) {
            
            /*
             * [DEPUIS 25-11-15] @author BOR
             *      J'ai corrigé une erreur de conception GRAVE.
             *      Cette nouvelle version prend en compte tous les cas : DENY_FOR et GROUPE
             */
            $iwd = ( $TST->oncreate_hasPermission($this->KDIn["oeid"],$tgtab["pdacc_eid"]) ) ? FALSE : TRUE;
            $ird = ( $TST->onread_hasPermission($this->KDIn["oeid"],$tgtab["pdacc_eid"]) ) ? FALSE : TRUE;
            
            /*
            $iwd = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTADD");
            $ird = $TST->config_check_denyfor($this->KDIn["oeid"],$tgtab["pdacc_eid"],"WCNTSEE");
            //*/
        } else {
            $iwd = TRUE;
            $ird = ( $perm === FALSE ) ? TRUE : FALSE;
        }
                
        $FE_DATAS = [
            "xt"        => $this->KDIn["datas"]["xt"],
            "tsrds"     => $tslds,
            "tstmetas"  => $tstmetas,
            "iwd"       => ( $iwd ) ? TRUE : FALSE,
            "ird"       => ( $ird ) ? TRUE : FALSE
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
         * "xt" : L'identifiant de l'opération qui permet de fiabiliser l'affichage des commentaires en prenant en compte le temps de réponse
         * "ti" : TesyID  
         * "dr" : Direction
         * "pi" : Pivot ID
         * "pt" : Pivot timestamp
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["xt","ti","dr","pi","pt","cu"]; 
        
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
        if ( $CXH->is_connected() ) {
            $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
        
        $this->GetLikes();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $PM = new POSTMAN();
            $an = strtolower($this->KDIn["datas"]["an"]);
            $dr = strtolower($this->KDIn["datas"]["dr"]);
            switch ($an) {
                case "tlkbrd":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["tii"], 1252, TRUE, $dr);
                    break;
                case "fksa":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["tii"], 1253, TRUE, $dr);
                    break;
                case "hview":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["tii"], 1254, TRUE, $dr);
                    break;
                case "psmn":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["tii"], 1255, TRUE, $dr);
                    break;
                case "tia-explr":
                        $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["tii"], 1256, TRUE, $dr);
                    break;
                default:
                    break;
            }
            
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
}

?>
