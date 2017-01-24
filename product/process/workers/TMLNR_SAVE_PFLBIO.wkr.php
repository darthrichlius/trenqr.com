<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_SAVE_PFLBIO extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function SaveBio () {
        
        //Utile pour plus tard
        $args = [
            "accid"     => $this->KDIn["oid"],
            "acc_eid"   => $this->KDIn["oeid"],
            "art_locip" => $this->KDIn["locip"],
        ];
        
        /*
         * [NOTE 27-08-14 à 21:31] par L.C.
         * ATTENTION : 
         * Normalement accid dans args doit venir de FE. On s'en sert notamment pour détecter si l'utilisateur en FE essai de passer de fausses infos.
         * Cependant, à la vb1 on préfère ne prendre aucun risque et donner le OWNER en prenant l'identifiant dans la variable de SESSION. 
         */
        $A = new PROD_ACC();
        $bio = $A->set_new_profilbio($this->KDIn["oid"], $this->KDIn["datas"]["b"]);
        
        /*
         * [DEPUIS 19-06-15] @BOR
         * Intégration de la fonctionnalité de siteweb.
         * NOTE : 
         *  -> On ne doit absolument pas transformer la donnée au risque de la corrompre.
         */
        $wbst = $this->KDIn["datas"]["w"];
        $website = $A->set_new_website($this->KDIn["oid"], $wbst);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $bio)  ) {
//        if ( !$bio || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $bio)  ) {
//            $this->Ajax_Return("err","__ERR_VOL_FAILED");
            $this->Ajax_Return("err",$bio);
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $website)  ) {
//        } else if ( !$website || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $website)  ) {
//            $this->Ajax_Return("err","__ERR_VOL_FAILED");
            $this->Ajax_Return("err",$website);
        }
        
        $FE_DATAS = [
            "bio"   => $bio,
            "wbst"  => $website
        ];
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;        
        
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
        $this->perfEna = TRUE;
        $this->tm_start = round(microtime(true)*1000);
        
        /*
         * On vérifie que toutes les données sont présentes
         * 
         *  b : Bio
         *  w : Website
         *  u : Url
         */
        $EXPTD = ["b","w","u"];
        
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
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MISSING");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        
        $exists = $A->exists_with_id($oid,TRUE);
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        
        $this->KDIn["datas"] = $in_datas;
//        $this->KDIn["b"] = $in_datas["b"];
    }

    public function on_process_in() {
        /*
         * [DEPUIS 19-06-15] @BOR
         * On renforce la sécurité sur l'opération.
         * De plus, on le fait on prévision des possibles modifications à venir qui impliqueront encore plus de données.
         */
        
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["u"]);
        
//        var_dump($this->KDIn["datas"]["u"],$upieces);
//        exit();
//        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        if ( $upieces && is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) {
            $PDACC = new PROD_ACC();
            $this->KDIn["target"] = $PDACC->exists_with_psd($upieces["user"],TRUE);
//            var_dump(__LINE__,$this->KDIn["target"]);
//            exit();
            if (! $this->KDIn["target"] ) {
                $this->Ajax_Return("err","__ERR_VOL_U_G");
            } else if ( floatval($this->KDIn["target"]["pdaccid"]) !== floatval($this->KDIn["oid"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
            }
            
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        $this->SaveBio();
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
        
        /* A Utiliser dans le cas d'un WORKER de type AJAX
        echo json_encode(["err"=>"TMLNR_IMG_NOCOMPLIANCE"]);
        exit();
        //*/
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>