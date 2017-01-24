<?php
require_once dirname(__DIR__) . '/../../vendor/autoload.php';

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_LTC_SSN_NEW extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
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
    
    private function warmSubscribers ($wcsi) {
        $WPC = new Ltc\LtcHttpClient('ltc.realm.wolverine');

        // add a transport to connect to the WAMP router
        $WPC->addTransportProvider(new Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:5555/'));
        
        $WPC->on("open",function($session) use ($WPC,$wcsi){
            $session->subscribe("ltc.chat.app.mtune", [$WPC,"_onMessage"]);
            $WPC->warmNewSessionAsync($wcsi);
        });

        // start the WampPost client
        $WPC->start();
        
    }
    
    private function Start() {
        
        $this->KDIn["datas"]["dr"] = strtoupper($this->KDIn["datas"]["dr"]);
        
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
        
        /*
         * TODO : Vérifier à partir de la donnée de SESSION que l'utilisateur est bien sur une page TENDANCE
         */
        
        /*
         * ETAPE 
         *      On crée un WAMP Client qui sera chaegé de lancer une opération PUBLISH auprès de tous les CLIENTS
         */
        $this->warmSubscribers($this->KDIn["datas"]["wcsi"]);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$tsrds,$tstmetas);
//        exit();
                
        $FE_DATAS = [
            "hopi"  => $this->KDIn["datas"]["hopi"]
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
         * "hopi"   : HttpOPerationIdentifiant
         * "tri"    : Trend ID
         * "wcsi"   : WAMP Client Session Idendifiant
         * "cu"     : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["hopi","tri","wcsi","cu"]; 
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v != "" ) && in_array($k,[]) ) {
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
