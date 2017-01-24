<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_Q extends WORKER  {
    
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
            
            if (! ( isset($v) && $v !== "" ) ) {
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
            if ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //CAS SPECIAUX
            $qsp = ["min","hvy"];
            if ( $k === "qsp" && !in_array($v, $qsp) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            $flm = ["pf","tr"];
            if ( $k === "flm" && !in_array($v, $flm) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            $flc = ["at","kw","pop","wn"];
            if ( $k === "flc" && !in_array($v, $flc) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
            if ( $k === "rng" && !preg_match("/(at|kw|wn|pop)_rng_([\d]+)/", $v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
        }
        
    }
    
    private function OnlyMyDatas ($datas, $qsp, $flm) {
        
        $nd;
        if ( $flm === "pf" ) {
            /*
            * nn: None
            * fl: Follow
            * df: DoubleFollow
            * fr: Friend
            */
           $rltab = [
               0 => 'nn',
               1 => 'fl',
               2 => 'df',
               3 => 'fr'
           ];

           foreach ( $datas as $k => &$v ) {
               //Détermination de la relation
                $rl = $rlc = NULL;
                $rl = ( key_exists("urel", $v) && !empty($v["urel"]) ) ? $v["urel"] : 0;
                $rlc = $rltab[$rl];
                   
                $PA = new PROD_ACC();
                if ( $qsp === "min" ) {
                    if ( intval($v["acctdl"]) === 2 ) {
                        continue;
                    }
                    $nd[] = [
                        "upsd"      => $v["upsd"],
                        "ufn"       => $v["ufn"],
                        /*
                         * [DEPUIS 03-05-15] @BOR
                         */
                        "uppic"     => $PA->onread_acquiere_pp_datas($v["srh_pfl_uid"])["pic_rpath"],
 //                       "uppic" => ( $v["uppic"] ) ? $v["uppic"] : "http://timg.ycgkit.com/files/img/r-dp/tqr_std_ppic_m.png",
                        "acctdl"    => $v["acctdl"],
                        "urel"      => $rlc
                    ];
                } else {
                    /*
                    $nd[] = [
                        "upsd"      => $v["upsd"],
                        "ufn"       => $v["ufn"],
                        /*
                         * [DEPUIS 03-05-15] @BOR
                         *
                        "uppic"     => $PA->onread_acquiere_pp_datas($v["srh_pfl_uid"])["pic_rpath"],
//                        "uppic" => ( $v["uppic"] ) ? $v["uppic"] : "http://timg.ycgkit.com/files/img/r-dp/tqr_std_ppic_m.png",
                        "urel"      => $rlc,
                        "ucap"      => $v["ucap"],
                        "ufols"     => $v["ufols"]
                    ];
                    //*/
                }
           }
        } else {
            foreach ( $datas as $v ) {
                $TRD = new TREND();
                $thref = $TRD->on_read_build_trdhref($v["srh_tr_eid"], $v["srh_tr_tlehrf"]);
               if ( $qsp === "min" ) {
                   $rl = $v["urel"];
                   if ( intval($v["acctdl"]) === 2 ) {
                        continue;
                    }
                   $nd[] = [
                       "i"      => $v["srh_tr_eid"],
                       "tle"    => $v["srh_tr_tle"],
                       "dsc"    => html_entity_decode($v["srh_tr_desc"]),
//                       "dsc"    => $v["srh_tr_desc"],
                       "hrf"    => $thref,
                       "fnb"    => $v["srh_tr_fol"],
                       "pnb"    => $v["srh_tr_post"],
                       "acctdl" => $v["acctdl"],
//                       "oi" => $v["srh_tr_owid"],
//                       "op" => $v["srh_tr_owpsd"],
//                       "of" => $v["srh_tr_owfn"]
                   ];
               } else {
                   /*
                   $rl = ( key_exists("urel", $v) && !empty($v["urel"]) ) ? $v["urel"] : "nn";
                   $nd[] = [
                       "i"      => $v["srh_tr_eid"],
                       "tle"    => $v["srh_tr_tle"],
                       "dsc"    => $v["srh_tr_desc"],
                       "hrf"    => $thref,
                       "fnb"    => $v["srh_tr_fol"],
                       "pnb"    => $v["srh_tr_post"],
//                       "oi" => $v["srh_tr_owid"],
                       "op"     => $v["srh_tr_owpsd"],
                       "of"     => $v["srh_tr_owfn"],
                       "rl"     => $v["trel"]
                   ];
                  //*/
               }
           }
        }
        
        return $nd;
    }
    
    private function Trigger () {
        $this->DoesItComply_Datas(); 
        
//        $qt = $this->KDIn["datas"]["qt"];
        $qt = $this->KDIn["datas"]["qt"]."*";
        $qsp = $this->KDIn["datas"]["qsp"];
        $flm = $this->KDIn["datas"]["flm"];
        $flc = $this->KDIn["datas"]["flc"];
        $rng = $this->KDIn["datas"]["rng"];
        
        $datas = NULL;
        
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
        
//        var_dump($this->KDIn["oid"],$qt,$qsp,$flm,$flc,$datas);
//        var_dump(__FUNCTION__,__LINE__,$datas);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        } else if (! isset($datas) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else {
            $this->KDOut["FE_DATAS"] = $this->OnlyMyDatas($datas,$qsp,$flm);
//            $this->KDOut["FE_DATAS"] = $datas;
        }
                    
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
         * "qt" : QueryText,
         * "qsp" : QueryScoPe,
         * "flm" : FiLterMenu,
         * "flc" : FiLterCategory,
         * "rng : RaNGe: Utiliser pour spécifier la "page". Ce nombre est multiplier par limit pour obtenir le nombre de lignes à renvoyer
         */
        $EXPTD = ["qt","qsp","flm","flc","rng"];

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
        if ( $CXH->is_connected() ) {
            //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
            $oid = $_SESSION["rsto_infos"]->getAccid();
//            $A = new PROD_ACC();
//            $exists = $A->exists_with_id($oid);

//            if (!$exists) {
//                $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
//            }

            /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
            $this->KDIn["oid"] = $oid;
//            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
//            $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
//            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));

            $this->KDIn["datas"] = $in_datas;
        } else {
            /*
             * [DEPUIS 09-05-15] @BOR
             */
            $this->Ajax_Return("err", "__ERR_VOL_DNY_AKX");
//            $this->KDIn["datas"] = $in_datas;
        }
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