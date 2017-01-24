<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_UNQ_DELRCT extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DeleteReaction () {
        /*
         * ETAPE :
         * On supprime le commentaire 
         */
        $ART = new ARTICLE();
        
        $r = $ART->reaction_del_art_reaction($this->KDIn["datas"]["i"], $this->KDIn["oid"],$this->KDIn["datas"]["ai"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            $this->Ajax_Return("err",$r);
        }
        
        $this->KDOut["FE_DATA"] = [
            "arn" => $ART->onload_art_rnb($this->KDIn["datas"]["ai"])
        ];        
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
        $EXPTD = ["ai","i"];
        
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
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
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
    }

    public function on_process_in() {
        $this->DeleteReaction();
    }

    public function on_process_out() {
        
        // A utiliser pour les DATX fournis sous forme de tableau. On boucle pour créer les row
        /*
        foreach (... as $k => $v) {
            $_SESSION["ud_carrier"][$k] = $v;
        }
        //*/
        
        /* A utiliser pour les DATX à insérer directement
        $_SESSION["ud_carrier"]["iml_articles"] = ...;
        //*/
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATA"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>