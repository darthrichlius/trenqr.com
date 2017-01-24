<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_CHBX_NTY_SN extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /************************************* START SPECIFICS SCOPE *************************************/
    
    private function SetNotif() {
        $ids = $this->KDIn["datas"]["is"];
        $rd = $this->KDIn["datas"]["rd"];
        /*
         * ETAPE :
         *      On update les lignes au niveau de la base de données.
         */
        $args_upd = [
            "ids"       => array_column($ids,"i"),
            "rd"        => $rd,
            "ssid"      => session_id(),
            "locip"     => $this->KDIn["locip"],
            "uagnt"     => $this->KDIn["uagent"]
        ];
//        var_dump($args_upd);
//        exit();
        $CBMSG = new CHBX_MSG();
        $r = $CBMSG->onalter_UnreadUpd($args_upd["ids"],$args_upd["rd"],$args_upd["ssid"],$args_upd["locip"],$args_upd["uagnt"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        }
        
        $jrmis = [];
        foreach ($ids as $d) {
            $jrmis[] = [
                "i" => $d["i"],
                "t" => $rd,
            ];
        }
        
        //jrmis : JustReadMessagesIds
        $FE_DATAS = [
            "jrmis"   => $jrmis,
        ];
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
    }
    
    /*************************************** END SPECIFICS SCOPE **************************************/


    public function prepare_datas_in() {
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "is  : Conversion Pivot Identifiant
         * "rd" : ReadDate
         * "cu" : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["is","rd","cu"];
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" ) && !in_array($k,[""] ) ) {
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
        $this->SetNotif();
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
