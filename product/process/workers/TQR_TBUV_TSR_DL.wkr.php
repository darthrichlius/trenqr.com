<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */   
class WORKER_TQR_TBUV_TSR_DL extends WORKER  {
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
    
    
    private function DelReaction() {
        
        $this->DoesItComply_Datas();
        
        $ti = $this->KDIn["datas"]["ti"];
        $tri = $this->KDIn["datas"]["tri"];
        $TST = new TESTY();
        
        /*
         * ETAPE :
         *      On récupère la table du TESTY s'il existe.
         */
        $tst_tab = $TST->Exists($ti);
        if (! $tst_tab ) {
            $this->Ajax_Return("err","__ERR_VOL_TST_GONE");
        }
        
        /*
         * ETAPE :
         *      On récupère la table du COMMENTAIRE s'il existe.
         */
        $tsr_tab = $TST->React_Exists($tri);
        if (! $tsr_tab ) {
            $this->Ajax_Return("err","__ERR_VOL_TSR_GONE");
        }
        
        /*
         * ETAPE :
         *      On vérifie que l'utilisateur a bien le droit de supprimer le COMMENTAIRE.
         * [NOTE 23-06-16]
         *      -> Rajout de l'autorisation pour TST_OWNER
         *      -> On ne la donne pas à TST_TARGET 
         */
//        if ( intval($tsr_tab["pdrct_author"]) !== intval($this->KDIn["oid"]) ) { //[DEPUIS 23-06-16]
        if (! ( floatval($tsr_tab["pdrct_author"]) === floatval($this->KDIn["oid"]) || floatval($tst_tab["tst_ouid"]) === floatval($this->KDIn["oid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        /*
         * ETAPE :
         *      On lance la procédure de supression du COMMENTAIRE.
         */
        $r = $TST->React_Del($tri);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        }
        
                
        $FE_DATAS = [
            "xt"    => $this->KDIn["datas"]["xt"],
            "rnb"   => $TST->React_Count($tst_tab["tst_eid"]),
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
         * "ti"     : TestyID  
         * "tri"    : TestyReactionID  
         * "trt"    : TestyReactionTime
         * "xt"     : L'identifiant de l'opération qui permet de fiabiliser l'affichage des commentaires en prenant en compte le temps de réponse
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["ti","tri","trt","xt","cu"]; 
        
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
        
        $this->DelReaction();
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