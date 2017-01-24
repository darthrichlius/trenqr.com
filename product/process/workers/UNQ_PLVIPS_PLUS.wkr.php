<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_UNQ_PLVIPS_PLUS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    //Depuis 14-12-14
    
    
    private function GetDatas () {
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $VIPs_DATAS = $EVALs_DATAS = NULL;
        /*
         *      On vérifie si les données de la localisation sont données.
         *      Dans ce cas, l'utilisateur n'est pas connecté. Aussi, on va se baser sur les données de localisation de l'utilisateur actif
         */
        $EVL = new EVALUATION();
        if ( ( key_exists("cu_loc_cid", $this->KDIn) && $this->KDIn["cu_loc_cid"] ) && ( key_exists("cu_loc_cn", $this->KDIn) && $this->KDIn["cu_loc_cn"] ) ) 
        {
            $vips_list = $EVL->onread_acquiere_vips($this->KDIn["datas"]["i"], $this->KDIn["cu_loc_cid"], $this->KDIn["cu_loc_cn"], null);
        } 
        else if ( isset ($this->KDIn["oid"]) )
        {
            //On ne se base sur les données propres à l'utilisateur connecté
            $vips_list = $EVL->onread_acquiere_vips($this->KDIn["datas"]["i"], null, null, $this->KDIn["oid"]);
        } 
        else {
            //On ne se base que sur l'Article
            $vips_list = $EVL->onread_acquiere_vips($this->KDIn["datas"]["i"]);
        }
        
        $vn;
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $vips_list) ) 
        {
            $this->Ajax_Return("err", $vips_list);
        } 
        else if ( $vips_list === -1 ) 
        {
            /*
             * [NOTE 13-12-14] @author
             * Cette solution commentée etait fausse car cet URQ sert aussi à envoyer d'autres données exceptées ceux de VIPs.
             * Aussi, on renvoyant une erreur volatile, on se retrouve sans les données sur l'EVAL, o_cap et récemment, cdel.
             * Ces autres données sont necessaires pour la construction et la compréhension de l'Article.
             */
//            $this->Ajax_Return("err", "__ERR_VOL_POSTPONED");
            $VIPs_DATAS = NULL;
        } 
        else if ( $vips_list ) 
        {
            foreach ($vips_list as $u) {
                $VIPs_DATAS[] = [
                    "ueid"  => $u["ueid"],
                    "upsd"  => $u["upsd"],
                    "ufn"   => $u["ufn"]
                ];
            }
            //On récupère le nombre de gens qui ont évalué l'Article
            $vn = $EVL->onread_count_evaltot($this->KDIn["datas"]["i"]) - 3;
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        /********************************************************************/
        /************************ DONNEES EVALUATIONS ***********************/
        //On récupère les données d'Eval de l'Article (MAJ)
        
        $AR = new ARTICLE_TR();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        /*
         * [DEPUIS 20-04-16]
         *      Modification effectuée pour amémliorer la PERF de ce WORKER
         */
//        $art_tab = $AR->on_read(["art_eid" => $this->KDIn["datas"]["i"]]); 
        $art_tab = ( $AR->onread_is_trend_version_eid($this->KDIn["datas"]["i"]) ) 
            ? $AR->onread_archive_itr(["art_eid" => $this->KDIn["datas"]["i"]])
            : $AR->onread_archive_iml(["art_eid" => $this->KDIn["datas"]["i"]]);
         
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //Permet de savoir si un Article de type ITR ou pas.

        //[Depuis 14-12-14 00:30]
        /*
        $AR = new ARTICLE();
        $art_tab = $AR->on_read_entity(["art_eid" => $this->KDIn["datas"]["i"]]);
        //*/
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $art_tab) ) {
            $this->Ajax_Return("err", $art_tab);
        } else if ( $art_tab === -1 ) { //Depuis 14-12-14 00:30
            $art_tab = $AR->getArt_loads();
            $EVALs_DATAS = $art_tab["art_eval"];
        } else {
            $EVALs_DATAS = $art_tab["art_eval"];
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $me = NULL;
        if ( isset($this->KDIn["oid"]) ) 
        {
            //Donnée sur l'évaluation de l'utilisateur en cours
            $E_E = $EVL->exists(["actor" => $this->KDIn["oid"],"artid" => $art_tab["artid"]]);
            $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) ? $EVL->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        /********************************************************************/
        /************************ DONNEES EXTRAS USER ***********************/
        /*
         * [DEPUIS 07-11-15] @author
         *      Refactorisé pour corriger un bogue qui faisait que les données concernait l'utilisateur connecté dans tous les cas...
         *      ... quand le but est de mettre à jour les données du propriétaire du compte.
         *      On emprofite pour zapper l'opération dans le cas où l'utilisateur est sur une page TRD
         */
        $o_cap = NULL;
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        if ( in_array($page,["TMLNR_GTPG_WLC","TMLNR_GTPG_RU","TMLNR_GTPG_RO"]) ) {
            $ueid;
            $PA = new PROD_ACC();
            if ( $page === "TMLNR_GTPG_RO" ) {
                $ueid = $this->KDIn["oeid"];
                $u_tab = $PA->exists($ueid,TRUE);
            } else {
                $u_tab = $PA->exists_with_psd($this->KDIn["upieces"]["user"],TRUE);
            }
            if (! $u_tab ) {
                $this->Ajax_Return("err", "__ERR_VOL_U_G");
            }
            
            $o_cap = $u_tab["pdacc_capital"];
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        /********************************************************************/
        /************************** CANDELART SCOPE *************************/
        $cdel = NULL;
        if ( isset($art_tab) ) {
            $cdel = $this->CurrentUserCanDelete($art_tab);
        } 
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        /********************************************************************/
        /**************************** FINAL SCOPE ***************************/
        /*
        $ustg = $AR->onread_AcquiereUsertags_Article($this->KDIn["datas"]["i"],TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ustg) ) {
            $this->Ajax_Return("err", $ustg);
        }
        //*/
        $this->KDOut["FE_DATAS"] = [
//            "ustgs" => $ustg,
            "vips"  => [
                "tab"   => $VIPs_DATAS,
                "nb"    => ( !$vn ||  $vn < 0 ) ? 0 : $vn,
            ],
            "evals" => [
                "tab"   => $EVALs_DATAS,
                "me"    => $me
            ],
            "o_cap" => (! $o_cap ) ? 0 : intval($o_cap),
            "cdel"  => $cdel
        ];
        
    }
    
    private function CurrentUserCanDelete ($art_tab) {
        //QUESTION : Est ce que l'utilisateur actif a le droit d'avoir accès au "bouton" de supression (TRUE/FALSE)
        
        if (! $art_tab ) {
            return;
        }
        
        $CXH = new CONX_HANDLER();
        if (! $CXH->is_connected() ) {
            return FALSE;
        } else {
            //On vérifie s'il s'agit du propriétaire de l'a Tendance'Article
            if ( intval($art_tab["art_oid"]) === intval($this->KDIn["oid"]) ) {
                return TRUE;
            } else if ( key_exists("art_trd_oid", $art_tab) && intval($art_tab["art_trd_oid"]) === intval($this->KDIn["oid"]) ) { //Est ce qu'il s'agit du propriétaire de la Tendance
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    
    /****************** END SPECFIC METHODES ********************/
    
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    private function Test_datas () {
        //Créer de fausses evaluations pour tester le Worker
        
        $EVL = new EVALUATION();
        
        $args_new_evls[] = [
            "actor"     => "70",
            "eval_code" => "_eval_cl", //spcl, cl, dlk
            "art_eid"   => "7fbbjo1"
        ];
        $args_new_evls[] = [
            "actor"     => "46",
            "eval_code" => "_eval_cl", //spcl, cl, dlk
            "art_eid"   => "7fbbjo1"
        ];
        $args_new_evls[] = [
            "actor"     => "53",
            "eval_code" => "_eval_cl", //spcl, cl, dlk
            "art_eid"   => "7fbbjo1"
        ];

        foreach ( $args_new_evls as $v ) {
            $EVL->on_create_entity($v);
        }
        
        
    }
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["i","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //* On s'assure que SI l'utilisateur est CONNECTÉ, il existe et on le charge *//
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            $this->KDIn["irest"] = TRUE;
            
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->exists_with_id($oid, TRUE);

            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }
            
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["datas"] = $in_datas;
            
        } else {
            $this->KDIn["irest"] = FALSE;
            
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["datas"] = $in_datas;
            
            /*
             * On récupère les données de localisation de l'utilisateur actif (SI POSSIBLE)
             * Ces données sont intéressantes car elles permettront d'affiner le travail du module qui se charge de récupérer les VIPs de l'Article.
             */
            $QO = new QUERY("qryl3n2");
            $params = array(":ip_numeral1" => $this->KDIn["locip"], ":ip_numeral2" => $this->KDIn["locip"]);
            $datas = $QO->execute($params);
            
            if ( $datas ) {
                $loc_datas = $datas[0];
                $this->KDIn["cu_loc_cid"] = ( key_exists("cityid", $loc_datas) && !empty($loc_datas["cityid"]) ) ? $loc_datas["cityid"] : NULL;
                $this->KDIn["cu_loc_cn"] = ( key_exists("loc_ctr_code", $loc_datas) && !empty($loc_datas["loc_ctr_code"]) ) ? $loc_datas["loc_ctr_code"] : NULL;
            }
        }
    }

    public function on_process_in() {
//        $this->Test_datas();
//        exit();
        
        /*
         * [DEPUIS 07-11-15] @author BOR
         */
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["cu"]);
        
//        var_dump($this->KDIn["datas"]["cu"],$upieces);
//        var_dump($upieces['ups_raw']['aplki']);
//        exit();
        
        if (! ( $upieces && is_array($upieces) && key_exists("urqid", $upieces) && !empty($upieces["urqid"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        $this->KDIn["upieces"] = $upieces;
        
        $this->GetDatas();
    }

    public function on_process_out() {
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}


?>