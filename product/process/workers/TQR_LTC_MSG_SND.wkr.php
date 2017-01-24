<?php
require_once dirname(__DIR__) . '/../../vendor/autoload.php';

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_LTC_MSG_SND extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if ( in_array($k,["lmpi","lmpt"]) && !( isset($v) && $v !== "" ) ) {
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
             
        }
        
    }
    
    
    private function Start() {
        
        $this->DoesItComply_Datas();
        
        $page = $this->KDIn["upieces"]["urqid"];
        if (! $this->isValidTrendUrl($page) ) {
            $this->Ajax_Return("err", "__ERR_VOL_NO_SUCH_URL_FRMT");
        }
        
        $tei = $this->KDIn["upieces"]["ups_raw"]["tei"];
        $TR = new TREND();
        $trtab = $TR->exists($tei,TRUE);
        if (! $trtab ) {
            $this->Ajax_Return("err","__ERR_VOL_TRD_GONE");
        }
        
        //TODO : La chaine est crée en fonction de la Tendance pour limiter les risques de sécurité en ce qui concerne les fuites de données.
        $channel = "ltc.chat.app.mtune.";
        $channel .= $tei;
        
        $user = [
            "id" => $this->KDIn["oeid"],
            "fn" => $_SESSION["rsto_infos"]->getUfname(),
            "ps" => $_SESSION["rsto_infos"]->getUpseudo(),
            "pp" => $_SESSION["rsto_infos"]->getUppic_path()
        ];
        
        $now = round(microtime(TRUE)*1000);
        
        /*
         * ETAPE :
         *      On crée l'identifiant externe fictif du Message
         */
        $eid = $this->entity_ieid_encode($now,$this->KDIn["oid"]);
        
        /*
         * ETAPE :
         *      Traiter le texte pour vérifier la présence de RICH-DATAS : Usertags, Hashtags, Url.
         */
        $this->treat_desc($this->KDIn["datas"]["ms"],$hashs,$ustgs);
        if (  isset($hashs) && is_array($hashs) && count($hashs) ) {
            $hashs = $hashs[1];
        }
        
        $FNL_USTGS;
        if (  isset($ustgs) && is_array($ustgs) && count($ustgs) ) {
            $ustgs = $ustgs[1];
            foreach ($ustgs as $psd) {
                $PA = new PROD_ACC();
                $utab = $PA->exists_with_psd($psd);
                if (! $utab ) {
                    continue;
                }
                
                $FNL_USTGS[] = [
                    "eid"   => "",
                    "ueid"  => $utab["pdacc_eid"],
                    "ufn"   => $utab["pdacc_ufn"],
                    "upsd"  => $utab["pdacc_upsd"]
                ];
            }
        }
        
        
        /*
         * ETAPE :
         *      On rassemble les données à envoyer
         */
        $message = [
            "id"    => $eid,
            "ms"    => $this->KDIn["datas"]["ms"],
            "tm"    => round(microtime(TRUE)*1000),
            "ustgs" => $FNL_USTGS,
            "hashs" => $hashs,
            "urls"  => []
        ];
        
        /*
         * ETAPE :
         *      Ajout des données EXTRAS
         */
        $XTRAS = [
            "is_admin"  => ( floatval($trtab["trd_owner"]) === floatval($this->KDIn["oid"]) ) ? TRUE : FALSE
        ];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$XTRAS);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$user,$message);
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$trtab["trid"]);
//        exit();
        
        //$chan, $uid, $wcsi, $trid, $user, $message, $XTRAS
        $this->warmSubscribers($channel,$this->KDIn["oid"],$this->KDIn["datas"]["wcsi"],$trtab["trid"],$user,$message,$XTRAS);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tsrds,$tstmetas);
//        exit();
                
        $FE_DATAS = [
            "hopi"      => $this->KDIn["datas"]["hopi"],
            "user"      => "",
            "message"   => ""
        ];
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
        
    }
    
    private function treat_desc ( $art_desc, &$kws = NULL, &$usertags = NULL ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$art_desc]);
        
        $TH = new TEXTHANDLER();
        
        //On extrait les hashstags si le texte en comporte
        $kws = $TH->extract_prod_keywords($art_desc);
        //On extrait les usertags si le texte en comporte
        $usertags = $TH->extract_tqr_usertags($art_desc);
//        
//        var_dump(preg_match("#\\\\n#", $art_desc));
//        exit();
        //On échappe le texte
        $new_art_desc = $TH->secure_text($art_desc);
        
        /*
         * [DEPUIS 05-02-16]
         *      On convertit les éventuels EMOJIS en une correspondance HTML.
         */
        $new_art_desc = $TH->replace_emojis_in($new_art_desc);
        
        return $new_art_desc;
    }
    
    private function isValidTrendUrl ($page) {
        switch ($page) {
            case "TRPG_GTPG_RO" :
            case "TRPG_GTPG_RU" :
            case "TRPG_GTPG_RFOL" :
            case "TRPG_GTPG_WLC" :
                return true;
            default: 
                return false;
        }
    }
    
    private function warmSubscribers ($chan, $uid, $wcsi, $trid, $user, $message, $XTRAS=NULL) {
        
        $WPC = new Ltc\LtcHttpClient('ltc.realm.wolverine');

        // add a transport to connect to the WAMP router
        $WPC->addTransportProvider(new Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:5555/'));
        
        $WPC->on("open",function($session) use ($WPC, $chan, $uid, $wcsi, $trid, $user, $message, $XTRAS){
            
            /*
             * TODO :
             *      -> Vérifier que la CHAINE existe. Elle déclarée par au moins un des CLIENTS à la connexion.
             *      -> Vérifier que le COMPTE est bien connecté à la liste des SUBS de cette CHAINE.
             */
            $session->subscribe($chan, [$WPC,"_onMessage"]);
            $WPC->relayMessage($chan, $uid, $wcsi, $trid, $user, $message, $XTRAS);
        });

        // start the WampPost client
        $WPC->start();
        
    }
    
    /**************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        $this->perfEna = FALSE;
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $this->tm_start = round(microtime(true)*1000);
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //* On vérifie que toutes les données sont présentes *//
        
        /*
         * "hopi"   : (HttpOPerationId) L'identifiant de l'opération qui permet de fiabiliser l'affichage des messages en prenant en compte le temps de latence 
         * "tri"    : L'identifiant de la Tendance 
         * "wcsi"   : L'identifiant de SESSION du CLIENT qui envoie le message
         * "ms"     : Le MESSAGE à transmettre
         * "lmpi"   : Pivot ID, l'identifiant du dernier message
         * "lmpt"   : Pivot timestamp
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["hopi","tri","wcsi","ms","lmpi","lmpt","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,["lmpi","lmpt"]) ) {
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
        
        $this->Start();
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
