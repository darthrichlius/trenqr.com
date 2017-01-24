<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_GTARP extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function AcquireUREL ($cuid,$tguid) {
        //Détermine s'il y a une relation entre CU et OW
        
        //Détermine s'il y a une relation entre CU et OW
        $REL = new RELATION();
        $r = $REL->onread_relation_exists_fecase($cuid,$tguid);
        
        $n = $REL->encode_relcode($r);
        
//        var_dump($n);
//        exit();
        
        return $n; 
    }
    
    private function CanAkxRcts($art_tab) {
        //QUESTION : Est ce que l'utilisateur a le droit de voir les commentaires ? (TRUE/FALSE)

        //On vérifie si l'utilisateur est connecté
        
        /*
        $CXH = new CONX_HANDLER();
        if (! $CXH->is_connected() ) {
            /*
             * Si l'utilisateur n'est pas connecté on autorise pas la lecture des commentaires.
             * En effet, pour les Articles de type IML, seul le propriétaire et les mais peuvent y accéder.
             *
            return FALSE;
        } else {
            //On vérifie s'il s'agit du propriétaire
            if ( intval($art_tab["art_oid"]) === intval($this->KDIn["oid"]) ) {
                //S'il s'agit du propriétaire de l'Article
                return TRUE;
            } else {
                 //On vérifie si la Relation entre les Protas est de type 'FRD'
                $REL = new RELATION();
                if ( is_array($REL->friend_theyre_friends($this->KDIn["oid"],$art_tab["art_oid"])) ) {
                    return TRUE;
                }
            }
        }
        //*/
        
        //On vérifie s'il s'agit du propriétaire
        if ( isset($this->KDIn["oid"]) && intval($art_tab["art_oid"]) === intval($this->KDIn["oid"]) ) {
            //S'il s'agit du propriétaire de l'Article
            return TRUE;
        } 
        else if ( $art_tab["art_is_sod"] && isset($this->KDIn["oid"]) ) {
            return TRUE;
        } 
        else if ( isset($this->KDIn["oid"]) ) {
             //On vérifie si la Relation entre les Protas est de type 'FRD'
            $REL = new RELATION();
            if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_oid"]) ) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    private function CurrentUserCanDelete ($react_tab) {
        //QUESTION : Est ce que l'utilisateur actif a le droit d'avoir accès au 'bouton" de supression (TRUE/FALSE)
        
        $CXH = new CONX_HANDLER();
        if (! $CXH->is_connected() ) {
            return FALSE;
        } else {
            //On vérifie s'il s'agit du propriétaire du commentaire
            if ( isset($this->KDIn["oid"]) && intval($react_tab["oid"]) === intval($this->KDIn["oid"]) ) {
                return TRUE;
            } else if ( isset($this->KDIn["oid"]) && intval($react_tab["aoid"]) === intval($this->KDIn["oid"]) ) { //Est ce qu'il s'agit du propriétaire de l'Article ?
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    private function TripleEvalEnabled ($atab) {
        //QUESTION : Est ce que l'utilisateur actif a le droit d'avoir accès aux trois types d'EVAL (TRUE/FALSE)
        
//        $CXH = new CONX_HANDLER();
//        $REL = new RELATION();
        /*
        if (! $CXH->is_connected() ) {
            return FALSE;
        } else {
            //On vérifie s'il s'agit du propriétaire de l'Article
            if ( intval($aoid) === intval($this->KDIn["oid"]) ) {
                return TRUE;
            } else if ( $REL->friend_theyre_friends($this->KDIn["oid"], $aoid) ) { //Est ce qu'il s'agit d'un ami du propriétaire ?
                return TRUE;
            }
        }
        //*/
        
        /*
         * [DEPUIS 19-04-16]
         */
        $aoid = $atab["art_oid"];
        $isod = ( $atab["art_is_sod"] ) ? TRUE : FALSE;
        
        $urel = ( $this->KDIn["oid"] ) ? $this->AcquireUREL($this->KDIn["oid"],$aoid) : NULL;
        
        $REL = new RELATION();
        //On vérifie s'il s'agit du propriétaire de l'Article
        if ( isset($this->KDIn["oid"]) && intval($aoid) === intval($this->KDIn["oid"]) ) { //Je suis propriétaire
            return TRUE;
        } else if ( isset($this->KDIn["oid"]) && $REL->friend_theyre_friends($this->KDIn["oid"], $aoid) ) { //Est ce qu'il s'agit d'un ami du propriétaire ?
            return TRUE;
        } 
        /* //[DEPUIS 07-07-16]
        else if ( $isod === TRUE ) {
            return FALSE;
        }
        //*/
        else if ( $isod === TRUE && !empty($urel) && in_array(strtolower($urel), ["xr03","xr13","xr23","xr02","xr12","xr22"]) ) {
            return TRUE;
        } 
        
        return FALSE;
    }
    
    private function IsRestLicenced () {
        //QUESTION : Est ce que l'utilisateur actif peut avoir avoir accès à l'Article sous LICENCE Welcome ? (TRUE/FALSE)
        
        $CXH = new CONX_HANDLER();
        $r = ( $CXH->is_connected() ) ? TRUE : FALSE;
        return $r;
    }
    
    private function ActFeatsEna ($atab) {
//    private function ActFeatsEna ($aoid) {
        //QUESTION : Est ce que l'utilisateur actif peut accéder aux fonctionnalités ACTION ? (TRUE/FALSE)
        /*
         * [MODIFIE 01-06-15] @BOR
         *  -> On ne dit plus seulement si l'utilisateur peut accéder à "Action", on définit les fonctionnalités qu'on lui autorises
         *  -> Prise en compte du cas où au moment de la demande les utilisateurs sont amis
         *  -> Prise en compte du cas où au moment de la demande les utilisateurs ont une Relation de type D_FOLW
         */
        $REL = new RELATION();
        if ( isset($this->KDIn["oid"]) && floatval($atab["art_oid"]) === floatval($this->KDIn["oid"])) {
            $r = ["del","pml"];
        } else {
            if ( 
//                ( isset($this->KDIn["oid"]) && $REL->onread_relation_exists_fecase($this->KDIn["oid"],$atab["art_oid"]) === "_REL_FEO" ) 
                $atab["art_is_sod"]
                || ( isset($this->KDIn["oid"]) && is_array($REL->friend_theyre_friends($this->KDIn["oid"],$atab["art_oid"])) ) 
            ) {   
                $r = ["pml"];
            } else {
                $r = FALSE;
            }
        }
        
//        $r = ( intval($aoid) === intval($this->KDIn["oid"]) ) ? TRUE : FALSE;
        return $r;
    }
    
    
    private function GetArpDatas () {
        //* Récupère les données *//
        
        //On récupère les données sur l'ARTICLE et son propriétaire
        $ART = new ARTICLE();
        
//        $article = $ART->on_read_entity([ "art_eid" => $this->KDIn["datas"]["i"] ]); //[DEPUIS 19-04-16]
        $article = $ART->onread_archive_iml(["art_eid" => $this->KDIn["datas"]["i"]]);
        if (! $article ) {
            $this->Ajax_Return("err","__ERR_VOL_ART_GONE");
        }
        
        /*
         * [NOTE 20-04-15] @BOR
         * ETAPE :
         * On vérifie que l'utilisateur d'accéder à ARP.
         * ARP n'est accessible que pour les cas suivants :
         *  -> Propriétaire
         *  -> DFLOW
         *  -> FRIEND
         */
        /*
         * [DEPUIS 19-04-16]
         *      On vérifie qu'il ne s'agit pas d'un SOD.
         *      Dans lequel cas, il faudra afficher les commentaires.
         *      C'est à ARP de gérer le cas WLC
         */
        if ( !$article["art_is_sod"] && isset($this->KDIn["oid"]) ) {
            $REL = new RELATION();
            $r__ = $REL->onread_relation_exists_fecase($this->KDIn["oid"],$article["art_oid"]);
            $n__ = $REL->encode_relcode($r__);
    //        if (! in_array($n__, ["xrh","xr02","xr12","xr22","xr03","xr13","xr23"]) ) { //[DEPUIS 19-04-16]
            if (! in_array($n__, ["xrh","xr03","xr13","xr23"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
            }
        }
        
                
        /*
         * [Depuis 15-12-14]
         * ETAPE :
         * On détemine l'évaluation du Compte courant 
         */
        if ( isset($this->KDIn["oid"]) ) {
            $EV = new EVALUATION();
            $E_E = $EV->exists(["actor" => $this->KDIn["oid"],"artid" => $article["artid"]]);
            $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) 
                ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) 
                : "";
        } else {
            $me = "";
        }      
        
        $PA = new PROD_ACC();
        $FE_DATAS = [
            "art_eid"   => $article["art_eid"],
            "art_desc"  => $article["art_desc"],
            "art_time"  => $article["art_creadate"],
            "art_ustgs" => $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE),
            "art_hashs" => $article["art_list_hash"],
            "art_evals" => $article["art_eval"],
            "art_myel"  => $me,
            /*** PROPRIETAIRE ***/
            "aopsd"     => $article["art_opsd"],
            "aofn"      => $article["art_ofn"],
            "aohref"    => $article["art_ohref"],
//            "aoppic"    => $article["art_oppic"],
            "aoppic"    => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 02-07-16]
        ];
        
        /*
         * [DEPUIS 29-03-16]
         */
//        $FE_DATAS["vidu"] = $ART->onload_art_vid_url($article["art_eid"],null,TRUE);
        $FE_DATAS["vidu"] = $article["art_vid_url"];
        
        //On vérifie s'il s'agit d'un ARTICLE de type SOD
        $FE_DATAS["isod"] = ( $article["art_is_sod"] ) ? TRUE : FALSE;
        
        //On vérifie si l'utilisateur actif a le droit de voir tous les types d'EVAL
        $FE_DATAS["te_ena"] = $this->TripleEvalEnabled($article);
         
        //On vérifie si l'Article est doit être distribué sous licence WLC (ou pas)
        $FE_DATAS["isrtd"] = $this->IsRestLicenced();
        
        //On vérifie si l'utilisateur accéder à la section ACTION de l'Article (ou pas)
        $FE_DATAS["af_ena"] = $this->ActFeatsEna($article);
        
        //On vérifie si l'utilisateur actif a le droit de voir les commentaires
//        $go = $this->CanAkxRcts($article); //DEV, DEBUG, TEST
//        var_dump($go);
        
        if ( $this->CanAkxRcts($article) ) {
            //On récupère les commentaires de l'Article dans la LIMIT
            /*
             * [29-11-14] @author L.C.
             * J'ai modifié le code pour récupérer les commentaires depuis la méthodes ci-dessous.
             * Je l'avais spéciellement créée pour les besoins de FE dans le cas du WOrker qui ne fait que récupérer les commentaires.
             * J'ai aussi modifier les requetes SQL pour récupérer les Reacts par ordre croissant et avec une limite de 20 pour rester dans la logique des produits analogues.
             * 
             * Cette manière de faire peut sembler gourmande mais elle est la plus simple que j'ai trouvé en attendant une refonte.
             * En effet, je n'ai pas modifier de code au risque de faire boguer d'autres secteurs. De plus, on pourra compter sur un gain de puissance à venir qui saura compenser. 
             */
            $FE_DATAS["art_reacts"] = $ART->article_get_reacts($article["art_eid"]);
            
            /*
             * [DEPUIS 13-07-15] @BOR
             * Permet une gestion plus optimisée.
             * Cette donnée est surtout réservée à ARP car (à cette date) je n'ai pas ressenti le besoin de le faire sur les autres modules.
             * Ils utilisent sans-doutes d'autres méthodes.
             */
            $FE_DATAS["rakx_ena"] = TRUE;
            
            //On s'attèle à désigner les commentaires que CU peut supprimer
            $CXH = new CONX_HANDLER();
            if ( isset($FE_DATAS["art_reacts"]) && is_array($FE_DATAS["art_reacts"]) && count($FE_DATAS["art_reacts"]) ) {
                foreach ($FE_DATAS["art_reacts"] as $k => $react) {
                    /*
                     * RAPPEL sur les clés pour chaque Commentaire retourné
                     *      "itemid",
                     *      "body" 
                     *      "time" 
                     *      "utc"
                     *      "aoid" (Ajouté le 29-11-14)
                     *      "cdel" : CanDelete
                     *      "oeid",
                     *      "ofn" 
                     *      "opsd" 
                     *      "oppic" 
                     *      "ohref" 
                     */
                    
                    if (! $CXH->is_connected() ) {
                        $FE_DATAS["art_reacts"][$k]["cdel"] = 0;
                    } else {
                        $FE_DATAS["art_reacts"][$k]["cdel"] = ( $this->CurrentUserCanDelete($react) ) ? 1 : 0;
                    }
                }
            }
            
        } else {
            /*
             * [DEPUIS 13-07-15] @BOR
             * Permet une gestion plus optimisée.
             * Cette donnée est surtout réservée à ARP car (à cette date) je n'ai pas ressenti le besoin de le faire sur les autres modules.
             * Ils utilisent sans-doutes d'autres méthodes.
             */
            $FE_DATAS["rakx_ena"] = FALSE;
            $FE_DATAS["art_reacts"] = NULL;
        }
        
        /*
         * ETAPE : 
         * On récupère les données de Tag
         */
        $ustg = $ART->onread_AcquiereUsertags_Article($this->KDIn["datas"]["i"],TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ustg) ) {
            $this->Ajax_Return("err", $ustg);
        }
        $FE_DATAS["ustgs"] = $ustg;
        
        $this->KDout["ARP"] = $FE_DATAS;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$this->KDout["ARP"]);
//        exit();
    }
    

    /****************** END SPECFIC METHODES ********************/
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION
        session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["i"];
        
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
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //* On s'assure que SI l'utilisateur est CONNECTÉ, il existe et on le charge *//
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->exists_with_id($oid, TRUE);

            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }
            
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["datas"] = $in_datas;
            
        } else {
             $this->KDIn["datas"] = $in_datas;
        }
        
    }

    public function on_process_in() {
        
        $this->GetArpDatas();
        
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDout["ARP"]);
        
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
}
?>