<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_UNQ_GETALL_RCTS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply($art_tab) {
        //QUESTION : Est ce que l'utilisateur a le droit d'ajouter le commentaire ? (TRUE/FALSE)

        $AT = new ARTICLE();
        if ( $AT->onread_is_trend_version($art_tab["artid"]) ) {
            //Il n'y a aucune restriction pour les Articles de type TENDANCE. 
            
            return TRUE;
        } else if ( intval($art_tab["art_is_sod"]) === 1 ) { //[DEPUIS 20-04-16]
            return TRUE;
        } else {
            //On vérifie si l'utilisateur est connecté
            $CXH = new CONX_HANDLER();
            
            if (! $CXH->is_connected() ) {
                /*
                 * Si l'utilisateur n'est pas connecté on autorise pas la lecture des commentaires.
                 * En effet, pour les Articles de type IML, seul le propriétaire et les mais peuvent y accéder.
                 */
                
                return FALSE;
            } else {
                //on vérifie s'il s'agit du propriétaire
                if ( intval($art_tab["art_accid"]) === intval($this->KDIn["oid"]) ) {
                    //S'il s'agit du propriétaire de l'Article
                    return TRUE;
                } else {
                     //On vérifie si la relation entre les Protas est de type 'FRD'
                    $REL = new RELATION();
                    
                    if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["art_accid"]) ) {
                        return TRUE;
                    }
                }
            }
        }
        
        return FALSE;
    }
    
    private function GetAllReactsInLimit () {
        //* On créé l'Article IML *//
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $ART = new ARTICLE();
        $article = $ART->exists($this->KDIn["datas"]["i"]);
        if (! $article ) {
            $this->Ajax_Return("err","__ERR_VOL_ART_GONE");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //On vérifie si l'ajout est autorisé
        $go = $this->DoesItComply($article);
        if (! $go ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        //PENSE-BETE : 
        $reactions = $ART->article_get_reacts($this->KDIn["datas"]["i"]);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $reactions) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
//            $this->Ajax_Return("err",$this->KDIn["datas"]["art_eid"]);
        } else if ( isset($reactions) && is_array($reactions) && count($reactions) ) {
            
            //On s'attèle à désigner les commentaires que CU peut supprimer
            $CXH = new CONX_HANDLER();
            $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//            if ( isset($reactions) && is_array($reactions) && count($reactions) ) {
            foreach ($reactions as $k => &$react) {
                if (! $CXH->is_connected() ) {
                    $react["cdel"] = 0;
                } else {
                    $react["cdel"] = ( $this->CurrentUserCanDelete($react) ) ? 1 : 0;
                }
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                /*
                 * [DEPUIS 14-04-15] @BOR
                 * On envoie l'identifiant externe de l'Article pour permettre une stabilisation dans l'affichage des Commentaires.
                 * Le but étant de s'assurer que le Commentaire ne sera au bon Article référant.
                 */
                $react["raid"] = $react["raeid"];
                
                /*
                 * [DEPUIS 26-04-15] @BOR
                 * On traite le texte de description du Commentaire
                 */
                $react["body"] = html_entity_decode($react["body"]);
                
                /* 
                 * ETAPE :
                 * On invalide les données qui ne doivent pas être sorties
                 */
                unset($react["raeid"]);
                unset($react["artid"]);
                unset($react["aoid"]);
                unset($react["utc"]);
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            }

            //FE = FrontEnd
            $this->KDOut["FE_DATAS"] = [
                "rtab"  => $reactions,
                "extm"  => $this->KDIn["datas"]["et"],
                "arn"   => $ART->onload_art_rnb($this->KDIn["datas"]["i"])
            ];
//            }
        } else {
            //FE = FrontEnd
            $this->KDOut["FE_DATAS"] = [];
        }
        
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
         */
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
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION
        @session_start(); 
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["i","et","cu"];
        
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
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MSG");
        }
        
        //* On s'assure que SI l'utilisateur est COONECTÉ, il existe et on le charge *//
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            
            $oid = $_SESSION["rsto_infos"]->getAccid();
            
            $A = new PROD_ACC();
            $exists = $A->exists_with_id($oid, TRUE);
            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }
            
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["datas"] = $in_datas;
            
        } else {
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["datas"] = $in_datas;
        }
        
    }

    public function on_process_in() {
        $this->GetAllReactsInLimit();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"], FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["i"], 612, TRUE);
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}
?>