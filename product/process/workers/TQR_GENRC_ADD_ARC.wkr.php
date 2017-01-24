<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TQR_GENRC_ADD_ARC extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ( $this->KDIn["datas"] as $k => $v ) {
            
            $SKIP = ["lri","lrt"];
            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //On vérifie que les données pour le "body" du commentaire sont valides selon les règles en vigueur au niveau du WORKER
            $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
            $rbody = $this->KDIn["datas"][$k];

            preg_match_all("/(\n)/", $rbody, $m_c1);
            preg_match_all("/(\r)/", $rbody, $m_c2);
            preg_match_all("/(\r\n)/", $rbody, $m_c3);
            preg_match_all("/(\t)/", $rbody, $m_c4);
            preg_match_all("/(\s)/", $rbody, $m_c5);

            //Parano : Je sais que j'aurais pu ne mettre que \s
//            if ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) {
            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            $istr = ["ai","at","rm","lri","lrt","xt","cu"];
            if ( $v && in_array($k,$istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
        }
        
    }
    
    private function DoesItComply_Accounts($art_tab) {
        //QUESTION : Est ce que l'utilisateur a le droit d'ajouter le commentaire ? (TRUE/FALSE)

        if ( intval($art_tab["art_accid"]) === intval($this->KDIn["oid"]) ) {
            //S'il s'agit du propriétaire de l'Article
            return TRUE;
        } else {
            //Est ce qu'il s'agit d'un Article de type TREND ?
            $AT = new ARTICLE();
            
            if ( $AT->onread_is_trend_version($art_tab["artid"]) ) {
                //Il n'y a aucune restriction pour les Articles de type TENDANCE. 
                return TRUE;
            } else if ( $AT->onread_is_sod_version($art_tab["artid"]) ) {
                //Il n'y a aucune restriction pour les Articles de type TENDANCE. 
                return TRUE;
            } else {
                //On vérifie si la relation entre les Protas est de type 'FRD'
                $REL = new RELATION();
                
                if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_accid"]) ) {
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    
    private function CreateReaction () {
        //* On créé le commentaire pour Article *//
        
        $this->DoesItComply_Datas();
        
        $page = strtoupper($this->KDIn["upieces"]["urqid"]);
        
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
        
        $ART = new ARTICLE();
        $article = $ART->exists($this->KDIn["datas"]["ai"]);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$page,$article]);
//        exit();
        
        if (! $article ) {
            $this->Ajax_Return("err","__ERR_VOL_ART_GONE");
        }
        
        //On vérifie si l'ajout est autorisé
        $go = $this->DoesItComply_Accounts($article, $this->KDIn["oeid"]);
        if (! $go ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        
        $rbody = $this->KDIn["datas"]["rm"];
        $react_tab = $ART->reaction_add_art_reaction($rbody, $this->KDIn["locip"], $this->KDIn["oid"], $article["artid"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $react_tab) ) {
            $this->Ajax_Return("err",$react_tab);
        }
        
        //On ajoute dans la table des Actions
        $PM = new POSTMAN();
        $args = [
            "uid"       => $this->KDIn["oid"],
            "ssid"      => session_id(),
            "locip_str" => $_SESSION['sto_infos']->getCurrent_ipadd(),
            "locip_num" => $this->KDIn["locip"],
            "useragt"   => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"       => __CLASS__,
            "fe_url"    => $this->KDIn["datas"]["cu"],
            "srv_url"   => $this->KDIn["srv_curl"],
            "url"       => $this->KDIn["srv_curl"],
            "isAx"      => 1,
            "refobj"    => $react_tab["reactid"],
            "uatid"     => 600,
            "uanid"     => 2
        ];
        $uai = $PM->UserActyLog_Set($args);
        
        /*
         * ETAPE : 
         * On vérifie s'il existe des Usertags pour ce commentaire.
         * Dans ce dernier cas, on enregistre l'activité
         */
        if ( key_exists("ustgs", $react_tab) && !empty($react_tab["ustgs"]) ) {
            $this->LogUsertagActy($react_tab["ustgs"]);
        }
        
        /*
         * [NOTE 06-04-15] @BOR
         * ETAPE :
         * On récèpère tous les commentaires depuis le dernier affiché au niveau de FE si "lri" est défine
         */
        if ( $this->KDIn["datas"]["lri"] && $this->KDIn["datas"]["lrt"] ) {
            //On récupère les données
            $rs = $ART->article_get_reacts_from($this->KDIn["datas"]["ai"], $this->KDIn["datas"]["lri"], $this->KDIn["datas"]["lrt"], "top");
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rs) ) {
                if ( strtoupper($rs) !== "__ERR_VOL_REF_GONE" ) {
                    $this->Ajax_Return("err",$rs);
                }
                $go_any_way = TRUE;
                /*
                 * [NOTE 07-04-15] BOR
                 * EVOLUTION :
                 *  Le système serait plus stable si on renvoyait les 20 derniers Commentaires en laissant le soin à FE d'afficher que ceux valides.
                 *  Cependant, cela impliquerait de devoir mettre en place un système de rangement dynamique. 
                 *  Je ne peux me le permettre à ce stade pour des raisons de delais. La version est utilisable jusqu'à la prochaine révision.
                 */
            } else {
                 array_walk($rs,function(&$i,$k){
                    $i["cdel"] = ( $this->CurrentUserCanDelete($i) ) ? 1 : 0;
                    /*
                     * [DEPUIS 26-04-15] @BOR
                     */
                    $i["raid"] = $i["art_eid"]; 
                    unset($i["artid"]);
                    unset($i["aoid"]);
                    unset($i["utc"]);
                });

                $FE_REACT = $rs;
            }
           
        } else {
            
            /*
             * [NOTE 06-04-15] @BOR
             * ETAPE : 
             * On vérifie et renvoie les USERTAGs
            */
            $ustgs = NULL;
            if ( key_exists("ustgs",$react_tab) && is_array($react_tab["ustgs"]) && count($react_tab["ustgs"]) ) {
                $ustgs = $react_tab["ustgs"];
                array_walk($ustgs,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
    //        var_dump($react_tab["ustgs"],$ustgs);
    //        exit();
            //FE = FrontEnd
            $FE_REACT[] = [
                "itemid"    => $react_tab["react_eid"],
                "body"      => $react_tab["react_body"],
                "time"      => $react_tab["react_date_tstamp"],
                "raid"      => $react_tab["raeid"],
                "oeid"      => $react_tab["oeid"],
                "ofn"       => $react_tab["ofn"],
                "opsd"      => $react_tab["opsd"],
                "oppic"     => $this->KDIn["oppic"],
                "ohref"     => "/@".$react_tab["opsd"],
                "cdel"      => 1,
                "ua_m"      => rand(1,6),
                "ustgs"     => $ustgs,
                "hashs"     => $react_tab["hashs"] //[DEPUIS 18-04-16]
            ];
        }
        
        if ( $go_any_way ) {
            
            /*
             * [NOTE 06-04-15] @BOR
             * ETAPE : 
             * On vérifie et renvoie les USERTAGs
            */
            $ustgs = NULL;
            if ( key_exists("ustgs",$react_tab) && is_array($react_tab["ustgs"]) && count($react_tab["ustgs"]) ) {
                $ustgs = $react_tab["ustgs"];
                array_walk($ustgs,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
    //        var_dump($react_tab["ustgs"],$ustgs);
    //        exit();
            //FE = FrontEnd
            $FE_REACT[] = [
                "itemid"    => $react_tab["react_eid"],
                "body"      => $react_tab["react_body"],
                "time"      => $react_tab["react_date_tstamp"],
                "raid"      => $react_tab["raeid"],
                "oeid"      => $react_tab["oeid"],
                "ofn"       => $react_tab["ofn"],
                "opsd"      => $react_tab["opsd"],
                "oppic"     => $this->KDIn["oppic"],
                "ohref"     => "/@".$react_tab["opsd"],
                "cdel"      => 1,
                "ua_m"      => rand(1,6),
                "ustgs"     => $ustgs,
                "hashs"     => $react_tab["hashs"] //[DEPUIS 18-04-16]
            ];
        }
        
        /*
         * ETAPE
         *      On standardise pour ne pas créer de DEPENDENCY
         */
        foreach ($FE_REACT as $k => $rtab) {
            $datas__[] = [
                "rid"       => $rtab["itemid"],
                "rm"        => $rtab["body"],
                "rtm"       => $rtab["time"],
                "raid"      => $rtab["raid"],
                "utc"       => NULL,
                "ustgs"     => $rtab["ustgs"],
                "hashs"     => $rtab["hashs"],
                "cdel"      => $rtab["cdel"],
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
        $FE_REACT = $datas__;
        
        /*
         * RAPPEL sur les clés pour chaque Commentaire retourné
         * "itemid",
            "oeid",
            "ofn" 
            "opsd" 
            "oppic" 
            "ohref" 
            "body" 
            "time" 
            "utc" 
            "cdel" : CanDelete
         */
        $this->KDOut["FE_REACT"] = [
            "ards" => $FE_REACT,
            //ARN : ArticleReactionNumber
            "arnb" => $ART->onload_art_rnb($this->KDIn["datas"]["ai"])
        ];  
        
        /* //AVANT 07-04-15 23:00
        //FE = FrontEnd
        $FE_REACT = [
            "itemid" => $react_tab["react_eid"],
            "body" => $react_tab["react_body"],
            "oeid" => $react_tab["oeid"],
            "ofn" => $react_tab["ofn"],
            "opsd" => $react_tab["opsd"],
            "oppic" => $this->KDIn["oppic"],
            "ohref" => "/@".$react_tab["opsd"],
            "time" => $react_tab["react_date_tstamp"]
        ];
        
        $this->KDOut["FE_REACT"] = $FE_REACT;        
        //*/
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
    
    private function LogUsertagActy ($ustgs) {
        
        $PM = new POSTMAN();
        foreach ($ustgs as $ustg) {
            
            //On ajoute dans la table des Actions
            $args = [
                "uid"           => $this->KDIn["oid"],
                "ssid"          => session_id(),
                "locip_str"     => $_SESSION['sto_infos']->getCurrent_ipadd(),
                "locip_num"     => $this->KDIn["locip"],
                "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
                "wkr"           => __CLASS__,
                "fe_url"        => $this->KDIn["datas"]["cu"],
                "srv_url"       => $this->KDIn["srv_curl"],
                "url"           => $this->KDIn["srv_curl"],
                "isAx"          => 1,
                "refobj"        => $ustg["ustg_id"],
                "uatid"         => 1102,
                "uanid"         => 2
            ];
            $uai = $PM->UserActyLog_Set($args);
            
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
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION
        @session_start();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * ai : ArticleID
         * at : ArticleTIME
         * rm : ReactMessage
         * lri : LastReactID
         * lrt : LastReactTime
         * xt : Identifiant d'Opération
         * cu : CurrentURL (Permet de déterminer la page qui exécute l'opération)
         */
        $EXPTD = ["ai","at","rm","lri","lrt","xt","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( in_array($k,["lri","lrt"]) ) {
                continue;
            } else if (! ( isset($v) && $v !== "" && $v !== "''" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->on_read_entity(["acc_eid" => $_SESSION["rsto_infos"]->getAcc_eid()]);
        if ( !$exists | is_string($exists) ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["oppic"] = $exists["pdacc_uppic"];
        
        $this->KDIn["datas"] = $in_datas;
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
        
        $this->CreateReaction();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_REACT"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>