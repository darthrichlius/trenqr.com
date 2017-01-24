<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_SUGG_GA extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if ( in_array($k,["x"]) ) {
                continue;
            } else if ( in_array($k,["mode"]) && !$v ) {
                continue;
            } else {
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
                
                if ( $k === "mode" && $v && !in_array($v,["_MD_DECORA","_MD_TAKME_TOTHMN"]) ) {
                    return "__ERR_VOL_WRG_DATAS";
                }
            }    
        }
    }
    
    
    private function GetAll() {
        $this->DoesItComply_Datas();
        
        $PROFILS; $HASH; $TRENDS;
        $TQR  = new TRENQR();
        $HVIEW = new HVIEW();
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        
        /*
         * [DEPUIS 12-11-15] @author BOR
         *      On vérifie s'il s'agit du cas de TakeMeToTheMoon (TMTTM)
         * [DEPUIS 25-11-15] @author BOR
         *      Les valeurs provenant de FE sont sous forme de texte. 
         *      Aussi, si on veut TRUE (bool), on aura "true" (STRING).
         *      A cete heure je n'en sais pas plus
         */
//        var_dump("o_O",$this->KDIn["datas"]["mode"]);
        if ( key_exists("mode", $this->KDIn["datas"]) && $this->KDIn["datas"]["mode"] === "_MD_TAKME_TOTHMN" ) {
            
            /*
             * ETAPE :
             *      On s'assure que l'utilisateur est connecté
             */
            if (! $this->KDIn["oeid"] ) {
                $this->Ajax_Return("err", "__ERR_VOL_DNY_AKX");
            }
            
            /*
             * ETAPE :
             *      On vérifie que la page l'autorise. En d'autres termes si l'option est disponible pour la page.
             */
            switch ($page) {
                case "TQR_GTPG_HVIEW" :
                        $PAGE = "HVIEW";
                    break;
                case "TMLNR_GTPG" :
                case "TMLNR_GTPG_RO" :
                case "TMLNR_GTPG_RU" :
                case "TMLNR_GTPG_WLC" :
                        $PAGE = "TMLNR";
                    break;
                case "TRPG_GTPG" :
                case "TRPG_GTPG_RO" :
                case "TRPG_GTPG_RFOL" :
                case "TRPG_GTPG_RU" :
                case "TRPG_GTPG_WLC" :
                        $PAGE = "TRPG";
                    break;
                case "FKSA_GTPG" :
                        $PAGE = "FKSA";
                    break;
                default:
                        $this->Ajax_Return("err", "__ERR_VOL_WRG_HACK");
                    break;
            }
            
            
            /*
             * ETAPE :
             *      On récupère le l'URL de l'élément. Il peut s'agir d'un Profil, une Tendance ou un Article.
             * RAPPEl :
             *      L'algorithme est étudié pour retourner en prioriété des Articles. 
             *      Dans le cas où un article doit être retourné, l'algorithme est étudié pour renvoyer en priorité un Article publié par un compte du groupe Angels" ou dans une Tendance du groupe "Angels"
             */
            /*
            $lk = $TQR->sugg_GetChoosenAny($this->KDIn["oeid"],["W_FEO"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $lk) ) {
                $this->Ajax_Return("err", "__ERR_VOL_FAILED");
            }
            //*/
//            $lk = "/f/0X6Z14o7jVbIbTf67";
            $lk = $TQR->sugg_GetChoosenAny($this->KDIn["oeid"],["W_FEO"]);

            $FE_DATAS = [
                "page"      => $PAGE,
                "profils"   => NULL,
                "trends"    => NULL,
                "tmttm"     => $lk
            ];
            
        } else if ( key_exists("mode", $this->KDIn["datas"]) && $this->KDIn["datas"]["mode"] === "_MD_DECORA" ) {
            $EXPLR = new EXPLORER();
            $ad = $EXPLR->GetDecoraPic($this->KDIn["oeid"],NULL,[
                "strict_mode" => TRUE
            ]);
            
            $FE_DATAS = [
                "page"      => NULL,
                "profils"   => NULL,
                "trends"    => NULL,
                "tmttm"     => NULL,
                "decora"    => $ad[0]
            ];
        } else {
            
            switch ($page) {
                case "ONTRENQR" :
                        $TRENDS = $TQR->sugg_GetChoosenTrends(NULL,NULL,3,TRUE);
                        $PAGE = "HOME";
                    break;
                case "TMLNR_GTPG" :
                case "TMLNR_GTPG_RO" :
                case "TMLNR_GTPG_RU" :
                        $psd = $this->KDIn["upieces"]["user"];
                        $PA = new PROD_ACC();
                        $utab = $PA->exists_with_psd($psd,TRUE);

                        $PROFILS = $TQR->sugg_GetChoosenProfils($utab["pdacc_eid"]);
                        /*
                         * [DEPUIS 23-11-15] @author BOR
                         */
                        $BLABLA = $HVIEW->HSH_BLABLA($this->KDIn["oid"]);
                        $TRENDS = $TQR->sugg_GetChoosenTrends();
                        $PAGE = "TMLNR";
                    break;
                case "TMLNR_GTPG_WLC" :
                        $psd = $this->KDIn["upieces"]["user"];
                        $PA = new PROD_ACC();
                        $utab = $PA->exists_with_psd($psd,TRUE);

                        $PROFILS = $TQR->sugg_GetChoosenProfils($utab["pdacc_eid"]);
                        $TRENDS = $TQR->sugg_GetChoosenTrends();
                        $PAGE = "TMLNR";
                    break;
                case "TRPG_GTPG" :
                case "TRPG_GTPG_RO" :
                case "TRPG_GTPG_RFOL" :
                case "TRPG_GTPG_RU" :
                        $tpi = $this->KDIn["upieces"]["ups_raw"]["tei"];
                        $TR = new TREND();
                        $ttab = $TR->on_read_entity(["trd_eid"=>$tpi]);

                        $PROFILS = $TQR->sugg_GetChoosenProfils($ttab["trd_oeid"]);
                        /*
                         * [DEPUIS 23-11-15] @author BOR
                         */
                        $BLABLA = $HVIEW->HSH_BLABLA($this->KDIn["oid"]);
                        $TRENDS = $TQR->sugg_GetChoosenTrends($tpi);
                        $PAGE = "TRPG";
                    break;
                case "TRPG_GTPG_WLC" :
                        $tpi = $this->KDIn["upieces"]["ups_raw"]["tei"];
                        $TR = new TREND();
                        $ttab = $TR->on_read_entity(["trd_eid"=>$tpi]);

                        $PROFILS = $TQR->sugg_GetChoosenProfils($ttab["trd_oeid"]);
                        $TRENDS = $TQR->sugg_GetChoosenTrends($tpi);
                        $PAGE = "TRPG";
                    break;
                case "FKSA_GTPG" :
                        $api = $this->KDIn["upieces"]["ups_raw"]["aplki"];
                        $atypi = $this->KDIn["upieces"]["ups_raw"]["atypi"];
                    
                        $ati = NULL;
                        if ( $atypi !== "testy" ) {
                            $ART = new ARTICLE_TR();
                            $atab = $ART->exists_with_prmlk($api);
                            $ati = $ART->child_exists($atab["art_eid"])["trd_eid"];
//                            var_dump(__LINE__,__FILE__,$api,$atab,$ati);
                        }
                        
                        $TRENDS = $TQR->sugg_GetChoosenTrends($ati,NULL,2,TRUE);
                        $PAGE = "FKSA";
                    break;
                default:
                    $this->Ajax_Return("err", "__ERR_VOL_WRG_HACK");
            }

            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $PROFILS) || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $TRENDS) ) {
                $this->Ajax_Return("err", "__ERR_VOL_FAILED");
            }

            $FE_DATAS = [
                "page"      => $PAGE,
                "profils"   => ( $PROFILS ) ? $PROFILS : [],
                "blabla"    => ( $PROFILS ) ? $BLABLA : [],
                "trends"    => ( $TRENDS ) ? $TRENDS : []
            ];
        
        }
        
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
         * "mode"   :
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["mode","cu"];
        
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
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
//            var_dump($this->KDIn["datas"]["cu"],$upieces);
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        
        $this->GetAll();
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
