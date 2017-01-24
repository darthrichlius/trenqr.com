<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_CHBX_DL_MS extends WORKER  {
    
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
            $SKIP = ["void"];
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
            $flm = ["pf","tr"];
            if ( $k === "flm" && ( !in_array($v, $flm) | !is_string($v) ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            $istr = ["tgt","flm","cid","ml","curl"];
            if ( !empty($v) && in_array($v, $istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "curl" && !parse_url($v) ) {
//            if ( $k === "curl" && !filter_input(FILTER_VALIDATE_URL, preg_replace("#^http://|https://$#i", "", $v) ) ) {
                var_dump($v,preg_replace("#^http://|https://$#i", "", $v));
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
        }
        
    }
    
    private function OnlyMyDatas ($case,$datas) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * Permet de ne récupérer que les données dans le cas mentionné.
         * La plus part du temps, les données proviennent d'une opération on_read.
         * 
         * La réecriture est faite pour empecher de faire fuiter des données brutes provenant de la base de données.
         * Dans certains cas, les clés sont elles-mêmes déjà réecrites. Nul n'est donc besoin de le refaire.
         */
        
        $nds = [];
        switch ($case) {
            //La cible dans le cadre de la Conversation, sans prendre en compte l'utilisateur courant
            case "target_from_convrs":
                     $nds = [
                         "tgtid"    => $datas["conv_tgteid"],
                         "tgtfn"    => $datas["conv_tgtfn"],
                         "tgtpsd"   => $datas["conv_tgtpsd"],
                         "tgtppic"  => $datas["conv_tgtppic"]["pic_rpath"]
                     ];
                break;
            //[14-01-15] fo ForOwner : L'ancienne version ne prennait en compte que la Cible de la Conversation. Or, cette derniere peut être CU. Cela provoque des bogues
            case "fo_target_from_convrs":
                     $nds = [
                         "tgtid"    => ( intval($datas["conv_tgtid"]) === intval($this->KDIn["oid"]) ) ? $datas["conv_acteid"] : $datas["conv_tgteid"],
                         "tgtfn"    => ( intval($datas["conv_tgtid"]) === intval($this->KDIn["oid"]) ) ? $datas["conv_actfn"] : $datas["conv_tgtfn"],
                         "tgtpsd"   => ( intval($datas["conv_tgtid"]) === intval($this->KDIn["oid"]) ) ? $datas["conv_actpsd"] : $datas["conv_tgtpsd"],
                         "tgtppic"  => ( intval($datas["conv_tgtid"]) === intval($this->KDIn["oid"]) ) ? $datas["conv_actppic"]["pic_rpath"] : $datas["conv_tgtppic"]["pic_rpath"]
                     ];
                break;
            case "convrs_from_convrs":
                    $nds = [
                        "cid"   => $datas["conv_eid"],
                        "crmbr" => $datas["conv_rmbr"],
                        "actid" => $datas["conv_acteid"],
                        "tgtid" => $datas["conv_tgteid"]
                        /* PERFORMANCE !
                        //NOTE : Permet (entres autres) de mettre à jour les données sur Actor
                        "actid" => $datas["conv_acteid"],
                        "actfn" => $datas["conv_actfn"],
                        "actpsd" => $datas["conv_actpsd"],
                        "actppic" => $datas["conv_actppic"],
                        //NOTE : Permet (entres autres) de mettre à jour les données sur Target
                        "tgtid" => $datas["conv_tgteid"],
                        "tgtfn" => $datas["conv_tgtfn"],
                        "tgtpsd" => $datas["conv_tgtpsd"],
                        "tgtppic" => $datas["conv_tgtppic"]
                        //*/
                     ];
                break;
            case "cmsg_from_cmsg_lightway":
                    $nds = [
                        "cmsgid"    => $datas["chmsg_eid"],
                        "cmsgm"     => $datas["chmsg_msg"],
                        "cmsgcd"    => $datas["chmsg_cdate_tstamp"],
                        "cmsg_fecd" => $datas["chmsg_fe_cdate_tstamp"],
                        "cmsgrd"    => $datas["chmsg_rdate_tstamp"],
                        "actid"     => $datas["chmsg_acteid"],
                        "tgtid"     => $datas["chmsg_tgteid"]
                        /* PERFORMANCE !
                        //ACTOR SCOPE
                        "actid" => $datas["chmsg_acteid"],
                        "actfn" => $datas["chmsg_actfn"],
                        "actpsd" => $datas["chmsg_actpsd"],
                        //TARGET SCOPE
                        "tgtid" => $datas["chmsg_tgteid"],
                        "tgtfn" => $datas["chmsg_tgtfn"],
                        "tgtpsd" => $datas["chmsg_tgtpsd"]
                         //*/
                    ];
                break;
            case "cmsg_from_convrs_lightway":
                    $nds = [
                        "cmsgid"    => $datas["chmsg_eid"],
                        "cmsgm"     => $datas["chmsg_msg"],
//                        "cmsgm" => html_entity_decode($datas["chmsg_msg"]),
                        "cmsgcd"    => $datas["chmsg_cdate_tstamp"],
                        "cmsg_fecd" => $datas["chmsg_fe_cdate_tstamp"],
                        "cmsgrd"    => $datas["chmsg_rdate_tstamp"],
                        "actid"     => $datas["chmsg_acteid"],
                        "tgtid"     => $datas["chmsg_tgteid"]
                        /* PERFORMANCE !
                        //ACTOR SCOPE
                        "actid" => $datas["chmsg_acteid"],
                        "actfn" => $datas["chmsg_actfn"],
                        "actpsd" => $datas["chmsg_actpsd"],
                        //TARGET SCOPE
                        "tgtid" => $datas["chmsg_tgteid"],
                        "tgtfn" => $datas["chmsg_tgtfn"],
                        "tgtpsd" => $datas["chmsg_tgtpsd"]
                         //*/
                    ];
                break;
            case "cmsg_from_cmsg_onreadway" :
                    $nds = [
                        "cmsgid"    => $datas["chmsg_eid"],
                        "cmsgm"     => $datas["chmsg_msg"],
                        "cmsgcd"    => $datas["chmsg_cdate_tstamp"],
                        "cmsg_fecd" => $datas["chmsg_fe_cdate_tstamp"],
                        "cmsgrd"    => $datas["chmsg_rdate_tstamp"],
                        "actid"     => $datas["chmsg_acteid"],
                        "tgtid"     => $datas["chmsg_tgteid"]
                    ];
                break;
            case "cmsg_from_convrs":
                    $nds = [
                        "cmsgid"    => $datas["conv_lmsgtab"]["chmsg_eid"],
                        "cmsgm"     => $datas["conv_lmsgtab"]["chmsg_msg"],
                        "cmsgcd"    => $datas["conv_lmsgtab"]["chmsg_cdate_tstamp"],
                        "cmsg_fecd" => $datas["conv_lmsgtab"]["chmsg_fe_cdate_tstamp"],
                        "cmsgrd"    => $datas["conv_lmsgtab"]["chmsg_rdate_tstamp"],
                        "actid"     => $datas["conv_acteid"],
                        "tgtid"     => $datas["conv_tgteid"]
                        /* PERFORMANCE !
                        //ACTOR SCOPE
                        "actid" => $datas["chmsg_acteid"],
                        "actfn" => $datas["chmsg_actfn"],
                        "actpsd" => $datas["chmsg_actpsd"],
                        //TARGET SCOPE
                        "tgtid" => $datas["chmsg_tgteid"],
                        "tgtfn" => $datas["chmsg_tgtfn"],
                        "tgtpsd" => $datas["chmsg_tgtpsd"]
                         //*/
                    ];
                break;
            case "target_from_cmsg":
                    $nds = [
                        "tgtid"     => $datas["chmsg_tgteid"],
                        "tgtfn"     => $datas["chmsg_tgtfn"],
                        "tgtpsd"    => $datas["chmsg_tgtpsd"],
                        "tgtppic"   => $datas["chmsg_tgtppic"]["pic_rpath"]
                    ];
                break;
            default:
                    return;
        }
        
        return $nds;
    }
    
    public function DeleteMessages() {
        $this->DoesItComply_Datas();
        
        $tgt = $this->KDIn["datas"]["tgt"];
        $cid = $this->KDIn["datas"]["cid"];
        $ml = $this->KDIn["datas"]["ml"];
        $flm = $this->KDIn["datas"]["flm"];
        $pcf = $this->KDIn["datas"]["pcf"];
        $wso = $this->KDIn["datas"]["wso"];
        $curl = $this->KDIn["datas"]["curl"];
        
        
        //"tgt","cid","ml","flm","pcf","wso","curl"
        /*
         * ETAPE :
         * On récupère les données sur l'owner. 
         * Ces données permettent de gérer l'affichage des Messages dans le sens horizontal au niveau de FE.
         * De plus, elles pourront permettre la mise à jour des données au de Owner au niveau de FE.
         * 
         * ATTENTION : Il ne faut renvoyer cette donnée accompagnée d'une donnée qui confirme à FE si l'utilisateur est sur une page de type OWNER
         */
        $PA = new PROD_ACC();
        $o__ = $PA->exists($this->KDIn["oeid"],TRUE);
        if (! $o__ ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        $cutab = [
            "oid"   => $o__["pdacc_eid"],
            "ofn"   => $o__["pdacc_ufn"],
            "opsd"  => $o__["pdacc_upsd"],
            "oppic" => $PA->onread_acquiere_pp_datas($o__["pdaccid"])
        ];
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($curl);
        
        /*
         * [NOTE 07-05-15] @BOR
         *      Je ne comprends pas cette logique. La personne qui est connecté est forcement à droite au niveau de FE.
         *      So, pourquoi vérifier si l'utilisateur est sur son compte quand cette donnée n'est pas probante ? :S
         *      J'ai complété le code pour éviter tout bogue mais je reste septique 
         * 
         * [DEPUIS 14-09-15] @author BOR
         *      J'ai recopié le code depuis "CHBX_PL_MS"
         */
        if ( is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) {
            $cutab["ioo"] = ( strtolower($upieces["user"]) === strtolower($cutab["opsd"]) ) ? TRUE : FALSE; 
        } else if ( in_array($upieces["urqid"],["TRPG_GTPG_RO","TRPG_GTPG_RFOL","TRPG_GTPG_RU","TRPG_GTPG_WLC"]) ) {
            switch (strtoupper($upieces["urqid"])) {
                case "TRPG_GTPG_RO" :
                        $cutab["ioo"] = TRUE;
                    break;
                default:
                        $cutab["ioo"] = FALSE;
                    break;
            }
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        /*
        if (! ( is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else {
            //ioo : IsOnOwn
            $cutab["ioo"] = ( strtolower($upieces["user"]) === strtolower($cutab["opsd"]) ) ? TRUE : FALSE; 
        }
        */
        $datas = $flist = $plist = $ulist = $chbxcnf = $cvtab = $tgttab = NULL;
        $CBCONV = new CHBX_CONVRS();
        
        /*
         * ETAPE :
         * On récupère les données sur la Conversation. Cela permet aussi de récupérer les données sur la Cible.
         * Si le dernier message correspond au message pivot, il n'est nul besoin d'effectuer une mise à jour des données.
         */
        $cvt__ = $CBCONV->on_read_entity(["conv_eid" => $cid]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cvt__) ) {
            $this->Ajax_Return("err",$cvt__);
        } else if (! $cvt__ ) {
            $this->Ajax_Return("err","__ERR_VOL_CNV_GONE");
        }
               
        $cvtab = $this->OnlyMyDatas("convrs_from_convrs", $cvt__);
        $tgttab = $this->OnlyMyDatas("fo_target_from_convrs", $cvt__);
        if ( strtolower($cvt__["conv_lmsgtab"]["chmsgid"]) === strtolower($cid) ) {
            //On a pas interet à chercher de nouveaux messages
        } else {
            /*
             * ETAPE :
             * On lance le processus de demande de suppression pour les messages reçus.
             */
            $CBMSG = new CHBX_MSG();
            $nml = explode(",", $ml);
            foreach ($nml as $meid) {
                $r = $CBMSG->ondelete_delForMe($meid,$this->KDIn["oeid"]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
                    $this->Ajax_Return("err",$r);
                }
            }
            
            /*
            * ETAPE : 
            * On récupère les données sur les messages. 
            * Il s'agit selon les cas des messages "FirstMessage", "Ulteriors" et "Previous".
            *  FirstMessages : Les x Messages les plus récents. Ce tableau est NON NUL quand il n'y a aucun message pivot
            *  Previous : Messages ajoutés après le Message pivot y compris le Message qui a été ajouté en dernier. 
            *             Cela permet de ne rater aucun Message donc que les Messages sont restitués de manière fidèle.
            *  Ulteriors : Messages ajoutés après le message qui vient d'être ajouté
            * 
            * L'obtention de ces données dépend donc en grande partie de l'existence d'un pivot.
            * RAPPEL : L'algorithme tel qu'il est constuit, permet de s'affranchir de traiter les cas "FIRST" et "FROM" séparément
            */
            $start = round(microtime(TRUE)*1000);
            do {
                //CAS UNIQUE : Pomper les Messages d'une Conversation dont le pivot n'est pas disponible. Il s'agit 
                $flist = $this->GiveMeFist($cid);
                                
                if ( isset($wso) && !empty($wso) && (!$flist && !$plist) ) {
                    sleep($CBCONV::$_WAIT_MSGS_FOR_SLEEP);
                }
                $now = round(microtime(TRUE)*1000);
                $diff = $now - $start;
                $diff /= 1000;
            } while( ( isset($wso) && !empty($wso) && $wso === TRUE ) && ( $diff < $CBCONV::$_WAIT_MSGS_FOR || (!$flist && !$plist) ) );//Traiter le cas de WSO WITH_STAY_OPTION
        }
        
        /*
         * ETAPE :
         * On récupère la configuration liée à l'application CHATBOX de l'utilisateur actif si FE l'a demandé.
         * Cette procédure supplémentaire ne doit pas être réclamée de manière récurrente pour des raisons de performance.
         * D'autant plus que la configuration est mise à jour à chaque changement. Il ne s'agirait donc que de mises à jours ...
         * ... de SESSION.
         */
        if ( $pcf === TRUE ) {
           $CHBX = new CHATBOX();
           //TODO : On récupère la configuration de ChatBox. Si elle n'existe pas, on la crée.
           $chbxcnf = NULL;
        } else {
           $chbxcnf = NULL;
        }
           
        $FE = [
            //L'identifiant externe de la Conversation. Est surtout utile dans le cas d'une nouvelle Conversation
            "cid" => $cid,
            //CurrentUser : Les données sur l'utilisateur qui est connecté permettant le meilleur traitement au niveau FE
            "cutab" => $cutab,
            //Les données de la cible. Permettent de mettre à jour les données au niveau de FE
            "tgttab" => $tgttab,
            //ConversationMessageList. On renvoie l'ensemble des messages depuis le message de référence. Cela permet de maintenir la liste des messages affichés à jour.
            "cmlist" => [
                //Données concernant les messages dits "First". Ce tableau est NON NULL quand il n'y a pas de pivot
                "flist" => $flist,
                /*
                 * Données concernant les messages "Previous". Ce sont les messages ajoutés après le Message pivot ycompris le Message qui vient d'être ajouté.  
                 * Cela permet de n'oublier aucun des messages ajoutés avant le dernier ajouté mais qui ont été ajouté après le message pivot.
                 * FE fera attention dans le traitement...
                 */
                "plist" => $plist,
                /*
                 * Données concernant les messages "Ulterior". Ce sont les messages ajoutés après le message pivot.
                 * ATTENTION : Le message qui vient d'être ajouté peut (grande chance d'être) être dans cette liste de données. 
                 */
                "ulist" => NULL //NON TRAITE au 05-01-14
            ],
//            "cmlist" => $cmlist,
            //ConversationTable 
            "cvtab" => $cvtab,
            //ChatBoxConf
            "chbxcnf" => $chbxcnf
        ];
        
        $this->KDOut["FE_DATAS"] = $FE;
    }
    /*
    private function GiveMeFrom($cid,$lmi,$drt) {
        $CBCONV = new CHBX_CONVRS();
        $datas = NULL;
        $cmsgs = $CBCONV->onread_FirstMessagesFrom($this->KDIn["oid"],$cid,$lmi,$drt,TRUE,TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cmsgs) ) {
            $this->Ajax_Return("err",$cmsgs);
        } else if ( isset($cmsgs) && count($cmsgs) ) {
            foreach ($cmsgs as $k => $v) {
                $datas[] = $this->OnlyMyDatas("cmsg_from_convrs_lightway",$v);
//                    $cmlist[] = $this->OnlyMyDatas("cmsg_from_cmsg_lightway",$v);
            }
        }
        
        return $datas;
    }
    //*/
    private function GiveMeFist($cid) {
        $CBCONV = new CHBX_CONVRS();
        $datas = NULL;
        $cmsgs = $CBCONV->onread_FirstMessages($this->KDIn["oid"],$cid,TRUE,TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $cmsgs) ) {
            $this->Ajax_Return("err",$cmsgs);
        } else if ( isset($cmsgs) && count($cmsgs) ) {
            foreach ($cmsgs as $k => $v) {
                /*
                if ( isset($v["chmsg_nxtdldate_tstamp"]) ) {
                    continue;
                }
                //*/
                $datas[] = $this->OnlyMyDatas("cmsg_from_cmsg_lightway",$v);
//                    $cmlist[] = $this->OnlyMyDatas("cmsg_from_cmsg_lightway",$v);
            }
        } 
        
        return $datas;
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
         * "tgt"  : TarGeT, Sert surtout à donner plus de poids à la demande
         * "cid"  : ConversationID,
         * "ml"   : MessageList,
         * "flm"  : FiLterMenu,
         * "pcf"  : PullConF,
         * "wso"  : WITH_STAY_OPTION (On reste pendant x secondes en attendant que des messages soient disponibles),
         * "curl" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["tgt","cid","ml","flm","pcf","wso","curl"];

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
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));

        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        $this->DeleteMessages();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
        
        //TODO : Log l'activité de l'utilisateur
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>