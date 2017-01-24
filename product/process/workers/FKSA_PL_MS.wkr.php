<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_FKSA_PL_MS extends WORKER  {
    
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
//            $SKIP = ["lmi","wso"];
//            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
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
            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) ) {
//            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            $istr = ["ai","curl"];
            if ( !empty($v) && in_array($k, $istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "curl" && !parse_url($v) ) {
//            if ( $k === "curl" && !filter_input(FILTER_VALIDATE_URL, preg_replace("#^http://|https://$#i", "", $v) ) ) {
                var_dump($v,preg_replace("#^http://|https://$#i", "", $v));
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
        }
        
    }
    
    private function OnlyMyDatas ($datas) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $datas);
        
        /*
         * Permet de ne récupérer que les données dans le cas mentionné.
         * La plus part du temps, les données proviennent d'une opération on_read.
         * 
         * La réecriture est faite pour empecher de faire fuiter des données brutes provenant de la base de données.
         * Dans certains cas, les clés sont elles-mêmes déjà réecrites. Nul n'est donc besoin de le refaire.
         */
        
        $nds = [];
        $PA = new PROD_ACC();
        $ART = new ARTICLE();
        foreach ($datas as $rtab) {
            $nds[] = [
                "rid"       => $rtab["react_eid"],
                "rbdy"      => $rtab["react_body"],
                "rtm"       => $rtab["react_date_tstamp"],
                "ustgs"     => $ART->onread_AcquiereUsertags_Reaction($rtab["react_eid"],TRUE),
                /* OWNER datas */
                "roid"      => $rtab["oeid"],
                "rofn"      => $rtab["ofn"],
                "ropsd"     => $rtab["opsd"],
                "roppic"    => $PA->onread_acquiere_pp_datas($rtab["react_writer"])['pic_rpath']
            ];
        }
        
        return $nds;
    }
    
    public function PullMessages() {
        $this->DoesItComply_Datas();
        
        $ai = $this->KDIn["datas"]["ai"];
        $curl = $this->KDIn["datas"]["curl"];
        
        /*
         * ETAPE :
         * On récupère les données sur l'Article. 
         * Ces données nous permettront de récupérer les commentaires les plus récents ainsi que les données sur le propriétaire.
         * Ces dernières de prendre des décisions en ce qui concerne les autorisations.
         */
        
        $art_tab = NULL;
        
        //On utilise ART_TR car on peut récupérer les données sur les Articles IML comme ITR
        $ART = new ARTICLE_TR();
        /*
        $r = $ART->on_read(["art_eid"=>$ai]);
        if ( !$r | $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        } else if ( $r === -1 ) {
            //Cas de l'Article IML
            $art_tab = $ART->getArt_loads();
        } else {
            $art_tab = $r;
        }
        //*/
        
        $art_tab = ( $ART->onread_is_trend_version_eid($ai) ) 
            ? $ART->onread_archive_itr(["art_eid" => $ai])
            : $ART->onread_archive_iml(["art_eid" => $ai]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $art_tab) ) {
            $this->Ajax_Return("err", $art_tab);
        } 
        
        
        /*
        $art_owner = [
            "aoid"   => $r["art_oid"],
            "aoeid"  => $r["art_oeid"],
            "aofn"   => $r["art_ofn"],
            "aopsd"  => $r["art_opsd"],
            "aoppic" => $r["art_oppic"]
        ];
        //*/
        
        /*
         * On effectue une analyse sur les autorisations d'accès.
         * On vérifie les autorisations avant même de vérifier s'il y a des Commentaires pour s'assurer que l'utilisateur a bien le droit d'accéder à cette fonctionnalité. 
         */
        $akx = $this->CheckForPermission($art_tab);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $akx) ) {
            $this->Ajax_Return("err",$akx);
        } else if (! $akx ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY_AKX");
        } 
        
        //On vérifie et récupère les données des Commentaires le cas échaant
//        if ( key_exists("art_list_reacts", $art_tab) && count($art_tab["art_list_reacts"]) ) { //[DEPUIS 20-04-16]
        if ( intval($art_tab["art_rnb"]) > 0 ) {
//            $FE = $this->OnlyMyDatas($art_tab["art_list_reacts"]);
            /*
             * [DEPUIS 18-04-16]
             *      On récupère les données en utilisant @see article_get_reacts()
             */
            $FE = $ART->article_get_reacts($art_tab["art_eid"]);
            /*
             * ETAPE
             *      On standardise pour ne pas créer de DEPENDENCY
             */
            foreach ($FE as $k => $rtab) {
                $datas__[] = [
                    "rid"       => $rtab["itemid"],
                    "rm"        => $rtab["body"],
                    "rtm"       => $rtab["time"],
                    "raid"      => $rtab["raeid"],
                    "utc"       => NULL,
                    "ustgs"     => $rtab["ustgs"],
                    "hashs"     => $rtab["hashs"],
                    "cdel"      => ( $this->CurrentUserCanDelete($rtab) ) ? 1 : 0,
                    //Le propriétaire de l'Article lié au Commentaire
    //                "raoid"     => $rtab["aoid"],
                    //Le propriétaire du commentaire
                    "roid"      => $rtab["oeid"],
                    "rofn"      => $rtab["ofn"],
                    "ropsd"     => $rtab["opsd"],
                    "roppic"    => $rtab["oppic"],
                    "rohref"    => "/".$rtab["opsd"],
                ];
            }
            $FE = $datas__;
            
            $this->KDOut["FE_DATAS"] = [
                "ards"  => $FE,
                "arnb"  => $ART->onload_art_rnb_wid($art_tab["artid"])
            ];
        } else {
            $this->KDOut["FE_DATAS"] = [];
        }
    }
    
    private function CheckForPermission($art_tab) {
        /*
         * Permet de vérifier si l'utilisateur actif est autorisé à accéder aux commentaires.
         * Cela dépend de :
         *  -> Le type d'Article
         *  -> Statut de connexion
         *  -> Son identité
         *  -> Sa relation avec le propriétaire de l'Article
         */
        
        //ETAPE 1 : On vérifie la nature de l'Article
        if ( key_exists("trid", $art_tab) && $art_tab["trid"] ) {
            /*
             * A la première version, on laisse les Commentaires pour permettre aux visiteurs de passer pplus de temps sur la plateforme.
             * A partir de la version "non beta", on les retirera.
             */
            return TRUE;
        } else if ( intval($art_tab["art_is_sod"]) === 1 ) {
            return TRUE;
        } else {
            //ETAPE 2 : On vérifie la nature de l'Article
            if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
                
                //ETAPE 3 : On vérifie si l'utitlisateur est le propriétaire de l'Article
                if ( $art_tab["art_oid"] === $this->KDIn["oid"] ) {
                    return TRUE;
                }
            
                //ETAPE 4 : On vérifie la relation entre les utilisateurs
                $REL = new RELATION();
                return $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_oid"]);
            } else {
                /*
                 * Si l'utilisateur n'est pas connecté, il n'a pas accès aux Commentaires d'un Article IML. 
                 * De plus, il n'aurait jamais du avoir accès 
                 */
                return FALSE;
            }
        }
    }
    
    private function CurrentUserCanDelete ($react_tab) {
        //QUESTION : Est ce que l'utilisateur actif a le droit d'avoir accès au 'bouton" de supression (TRUE/FALSE)
        
        $CXH = new CONX_HANDLER();
        if (! $CXH->is_connected() ) {
            return FALSE;
        } else {
            //On vérifie s'il s'agit du propriétaire du commentaire
            if ( intval($react_tab["oid"]) === intval($this->KDIn["oid"]) ) {
                return TRUE;
            } else if ( intval($react_tab["aoid"]) === intval($this->KDIn["oid"]) ) { //Est ce qu'il s'agit du propriétaire de l'Article ?
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
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();


        //* On vérifie que toutes les données sont présentes *//
        /*
         * "ai"   : L'identifiant de l'Article
         * "curl" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["ai","curl"];

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
//            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
            
            //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->exists_with_id($oid,TRUE);

            if (! $exists ) {
                $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
            }

            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
            
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["datas"] = $in_datas;
        } else {
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["datas"] = $in_datas;
        }
    }

    public function on_process_in() {
        $this->PullMessages();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"], FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        //TODO : Log l'activité de l'utilisateur
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["curl"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["ai"], 613, TRUE);
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}
?>