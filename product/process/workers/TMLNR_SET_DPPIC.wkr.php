<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_SET_DPPIC extends WORKER {

    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }

    /*     * ************** START SPECFIC METHODES ******************* */

    private function DoesItComply_Datas () {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            //Validation préléminaire de l'URL
            if ( ( $k === "cl" ) && !filter_var($v, FILTER_VALIDATE_URL) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
        }
        
    }
    
    
    private function SetProfilPicture() {

        //On vérifie si l'utilisateur est sur son compte
        /*
         * On utilise l'url envoyée par FE
         */
        $url = $this->KDIn["datas"]["cl"];
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $url_tab = $TQR->explode_tqr_url($url);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $url_tab) ) {
            $this->Ajax_Return("err",$url_tab);
        } else {
            
            //On vérifie si on a une URL valide et identifiable selon TQR
            if ( $url_tab && is_array($url_tab) && count($url_tab) ) {
                
                //** On récupère les données supplémentaires s'il y a une correpondance de pseudo **//
            
                if ( isset($url_tab["user"]) && ( strtolower($url_tab["user"]) === strtolower($this->KDIn["opsd"]) ) ) {
                        
                        $this->DoesItComply_Datas();
        
//                        var_dump($args_new_ppic["pdpic_string"]);
//                        exit();

                        $PPI = new IMAGE_PFLPIC();
                        $pp_tab = $PPI->oncreate_defaultpic($this->KDIn["oid"],TRUE);
                        
                        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $pp_tab) ) {
                            $this->Ajax_Return("err",$pp_tab);
                        }
                        
                        $this->KDOut["FE_DATAS"] = "DONE";
                        
                    }
                
            } else {
                $this->Ajax_Return("err","__ERR_VOL_MISMATCH_RULES");
            }
            
        }
    }

    /*     * **************** END SPECFIC METHODES ******************* */


    /*     * ************************************************************************************************************************************************** */
    /*     * ************************************************************************************************************************************************** */
    /*     * ************************************************************************************************************************************************** */

    /*     * ********* TMP ************ */

    //Mettre les instructons faites ailleurs pour les intégrer au WORKER


    /*     * ************************** */

    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        session_start();

        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["cl"];
        
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
        if (!$CXH->is_connected()) {
            $this->Ajax_Return("err", "__ERR_VOL_DNY_AKX");
        }

        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        $exists = $A->exists_with_id($oid,TRUE);

        if (!$exists) {
            $this->Ajax_Return("err", "__ERR_VOL_CU_GONE");
        }

        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["opsd"] = $_SESSION["rsto_infos"]->getUpseudo();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));

        $this->KDIn["datas"] = $in_datas;
        
    }

    public function on_process_in() {
        $this->SetProfilPicture();
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