<?php

/*
 * Permet de récupérer les tables des Articles dont les identifiants sont passés en paramètre.
 * Ce Worker est très interessant car il joue un rôle primordial dans les opérations pour améliorer la performance.
 * Ainsi, FE peut ne charger que les identifiants des Articles puis nous nous chargeons de les "remplir" par la suite.
 */
class WORKER_TPLA extends WORKER  {
    
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
            //*
            $SKIP = ["cz"];
            if ( !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            } else if ( !( isset($v) && $v !== "" ) && in_array($k, $SKIP) ) {
                continue;
            }
            //*/
            
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
            
            $istr = ["curl"];
            if ( !empty($v) && in_array($k, $istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "is" && !is_string($v) && !is_array($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
        }
    }
    
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
    
    
    private function GetArticles () {
        //* On créé le commentaire pour Article *//
        
        $this->DoesItComply_Datas();
        
        $ART = new ARTICLE_TR();
        $list = ( is_array($this->KDIn["datas"]["is"]) ) ? $this->KDIn["datas"]["is"] : [$this->KDIn["datas"]["is"]];
        $FE = [];
        foreach ( $list as $k => $v ) {
            $tab = $ART->on_read(["art_eid" => $v]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $tab) || !$tab ) {
                continue;
            } else if ( $tab === -1 ) {
                $tab = $ART->getArt_loads();
            }
            
            /*
             * [DEPUIS 02-07-16]
             *      On effectue la vérification en amant pour vérifier si USER a le droit d'accéder à ARTICLE.
             *      Dans le cas où il n'y a qu'un seul ART, on renvoie DENY
             * [DEPUIS 06-07-16]
             *      Refactorisation
             */
            $cu_can_read = FALSE;
            $uid__ = ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ? $this->KDIn["oid"] : NULL;
            $cu_can_read = $ART->onread_CanRead($uid__, $tab["artid"], ["FAST_WAY"]);
            if (! $cu_can_read ) {
                if ( count($list)  === 1 ) {
                    $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
                } else {
                    continue;
                }
            }
            
            
            $ustgs = [];
            if ( key_exists("art_list_usertags", $tab) && !empty($tab["art_list_usertags"]) ) {
                $ustgs = $tab["art_list_usertags"];
                array_walk($ustgs,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
            
            $RL = new RELATION();
            $EV = new EVALUATION();
            $me = NULL;
            
            /*
             * [DEPUIS 07-11-15] @author BOR
             */
            if ( $this->KDIn["oid"] ) {
                $E_E = $EV->exists(["actor" => $this->KDIn["oid"], "artid" => $tab["artid"]]);
                $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
            }
            
            $ivid = ( $tab["art_vid_url"] ) ? TRUE : FALSE;
            
            $case = $this->KDIn["datas"]["cz"];
            if (! $case ) {
                
                $is_frd = ( $this->KDIn["oid"] && $RL->friend_theyre_friends($this->KDIn["oid"], $tab["art_oid"]) ) ? TRUE : FALSE;
                $urel = ( $this->KDIn["oid"] ) ? $this->AcquireUREL($this->KDIn["oid"],$tab["art_oid"]) : NULL;
                
               /*
                * [NOTE 01-05-16]
                *      J'ai modifié les KEYS pour les standardisé et faciliter le traitement au niveau de FE
                */
                $ntab = [
                    "id"        => $tab["art_eid"],
                    "time"      => $tab["art_creadate"],
                    "img"       => $tab["art_pdpic_path"],
                    "msg"       => html_entity_decode($tab["art_desc"]),
    //                "adesc"     => html_entity_decode($tab["art_desc"]),
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($tab["art_eid"],$ivid),
                    "hashs"     => $tab["art_list_hash"],
                    "ustgs"     => $ustgs,
                    /* EXTRAS DATAS */
                    "rnb"       => $tab["art_rnb"],
                    "eval"      => $tab["art_eval"], /* [-1,+2,+1,total]*/
                    "me"        => $me, //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    /* TREND DATAS */
                    "trd_eid"   => ( key_exists("trd_eid", $tab) && isset($tab["trd_eid"]) ) ? $tab["trd_eid"] : NULL,
                    "trtitle"   => ( key_exists("trd_title", $tab) && isset($tab["trd_title"]) ) ? html_entity_decode($tab["trd_title"]) : NULL,
                    "trherf"    => ( key_exists("trd_href", $tab) && isset($tab["trd_href"]) ) ? $tab["trd_href"] : NULL,
                    "istrd"     => ( key_exists("trd_eid", $tab) && isset($tab["trd_eid"]) ) ? TRUE : FALSE,
                    /* OWNER DATAS */
                    "uid_"      => $tab["art_oid"], //Devra être supprimé
                    "uid"       => $tab["art_oeid"],
                    "ufn"       => $tab["art_ofn"],
                    "upsd"      => $tab["art_opsd"],
                    "uppic"     => $tab["art_oppic"],
                    "uhref"     => $tab["art_opsd"],
                    /************/
                    //hatr : HasAccessToReactions
                    "hatr"      => ( 
                        !empty($tab["trd_eid"]) 
                        | ( $this->KDIn["oid"] && $is_frd ) 
                        | ( intval($tab["art_is_sod"]) === 1 )
                    ) ? TRUE : FALSE, 
                   /*
                    * [DEPUIS 01-05-16]
                    */
                    "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $tab["art_eid"]) ) ? TRUE : FALSE,
                    "vidu"      => $tab["art_vid_url"],
                    "isod"      => ( intval($tab["art_is_sod"]) === 1 ) ? TRUE : FALSE,
                   /*
                    * [DEPUIS 07-07-16]
                    */
                    "te_ena"    => ( 
                        ( $this->KDIn["oid"] && floatval($this->KDIn["oid"]) === floatval($tab["art_oid"]) ) //Propriétaire
                        | $is_frd // Nous sommes AMIS
                        | ( intval($tab["art_is_sod"]) === 1 && $urel && in_array(strtolower($urel), ["xr02","xr12","xr22"]) ) ///iSOD et DFOLW 
                    ) ? TRUE : FALSE
                ];
            } else {
                switch ($case) {
                    case "TMLNR_TRD" :
                            $ntab = [
                                "id"       => $tab["art_eid"],
                                "prmlk"    => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($tab["art_eid"],$ivid),
                                "img"      => $tab["art_pdpic_path"]
                            ];
                        break;
                    default:
                        $this->Ajax_Return("err","__ERR_VOL_FAILED");
                }
            }
            
//            var_dump(__LINE__,$this->CheckAuth($ntab));
//            
            //On vérifie que l'utilisateur a bien le droit d'accéder à l'Article
            /*
             * [DEPUIS 07-07-15] @BOR
             */
            /* //[DEPUIS 06-07-16]
            $uid__ = ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ? $this->KDIn["oid"] : NULL;
//            if ( $this->CheckAuth($ntab) === TRUE ) { //[DEPUIS 07-07-15] @BOR
            if ( $ART->onread_CanRead($uid__, $tab["artid"], ["FAST_WAY"]) ) {
                unset($ntab["uid_"]); //On retire l'identifiant interne pour des raisons de sécurité
                $FE[] = $ntab;
            } else {
                $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
            }
            //*/
            
            unset($ntab["uid_"]); //On retire l'identifiant interne pour des raisons de sécurité
            $FE[] = $ntab;
            
        }
        
//        var_dump("APRES => ",$FE);    
//        exit();
        
        $this->KDOut["FE_DATAS"] = $FE;        
        
    }
    /*
     * [DEPUIS 07-07-15] @BOR
     * Le processus utilise la fonction général de l'entity ARTICLE
     */
    /*
    private function CheckAuth($tab) {
//        var_dump(__LINE__,TRUE|FALSE);
//        var_dump(__LINE__,TRUE||FALSE);
//        var_dump(__LINE__,( key_exists("oeid", $this->KDIn) && $this->KDIn["oeid"] === $tab["oid"] )|( key_exists("atrid", $tab) ) && !empty($tab["atrid"])( key_exists("oeid", $this->KDIn) && $this->KDIn["oeid"] === $tab["oid"] )|( key_exists("atrid", $tab) ) && !empty($tab["atrid"]));
        if ( 
            ( key_exists("oeid", $this->KDIn) && $this->KDIn["oeid"] === $tab["oid"] ) //Je suis le propriétaire
            | ( key_exists("atrid", $tab) && !empty($tab["atrid"]) ) //C'est un Article de type ITR
        ) {
//            var_dump(__LINE__,( key_exists("oeid", $this->KDIn) && $this->KDIn["oeid"] === $tab["oid"] ),( key_exists("atrid", $tab) ) && !empty($tab["atrid"]));
            return TRUE;
        } else if ( key_exists("oeid", $this->KDIn) && !empty($this->KDIn["oeid"]) ) {
            //A ce stade, il s'agit d'un Article de type IML. Il nous faut vérifier la relation qui lie les deux Acteurs dans le cas où CU est connecté 
            $REL = new RELATION();
            $x__ = $REL->friend_theyre_friends($this->KDIn["oid"], $tab["uid_"]);
//            var_dump(__LINE__,$this->KDIn["oid"],$tab["uid_"],$x__);
            return ( $x__ ) ? TRUE : FALSE;
//            return $REL->friend_theyre_friends($this->KDIn["oeid"], $tab["uid_"]);
        } else {
            return FALSE;
        }
        
    }
    //*/
    
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
         * is : Liste des identifiants d'artiles à traiter
         * cz : (Facultatif) Le cas dans lequel nous sommes. Détermine comment le WKR traitera la demande
         * curl : L'URL de la page depuis laquelle la demande a été lancée
         */
        $EXPTD = ["is","cz","curl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" && $v !== "''" ) && !in_array($k,["cz"]) )  {
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
        if ( $CXH->is_connected() ) {
//            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
            
            //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
            $oid = $_SESSION["rsto_infos"]->getAccid();
            $A = new PROD_ACC();
            $exists = $A->exists($_SESSION["rsto_infos"]->getAcc_eid(),TRUE); 
            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }
            
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }       
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->GetArticles();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>