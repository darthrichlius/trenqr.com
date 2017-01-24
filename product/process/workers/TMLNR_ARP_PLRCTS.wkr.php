<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_ARP_PLRCTS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
     private function DoesItComply($art_tab) {
        //QUESTION : Est ce que l'utilisateur a le droit de voir les commentaires ? (TRUE/FALSE)

        //On vérifie si l'utilisateur est connecté
        $CXH = new CONX_HANDLER();
        /*
        if (! $CXH->is_connected() ) {
            /*
             * Si l'utilisateur n'est pas connecté on autorise pas la lecture des commentaires.
             * En effet, pour les Articles de type IML, seul le propriétaire et les mais peuvent y accéder.
             *
            return FALSE;
        } else {
            //on vérifie s'il s'agit du propriétaire
            if ( intval($art_tab["uid"]) === intval($this->KDIn["oid"]) ) {
                //S'il s'agit du propriétaire de l'Article

                return TRUE;
            } else {
                 //On vérifie si la Relation entre les Protas est de type 'FRD'
                $REL = new RELATION();

                if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["uid"]) ) {
                    return TRUE;
                }
            }

        }
        //*/
        
        //On vérifie s'il s'agit du propriétaire
        if ( intval($art_tab["uid"]) === intval($this->KDIn["oid"]) ) {
            //S'il s'agit du propriétaire de l'Article
            return TRUE;
        } else if ( $art_tab["art_is_sod"] ) {
            return TRUE;
        } else if ( isset($this->KDIn["oid"]) ) {
             //On vérifie si la Relation entre les Protas est de type 'FRD'
            $REL = new RELATION();
            if ( $REL->friend_theyre_friends($this->KDIn["oid"], $art_tab["uid"]) ) {
                return TRUE;
            }
        }
        
        return FALSE;
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
    
    private function GetAllReactsInLimit () {
        
        //On vérifie si l'Article existe
        $ART = new ARTICLE();
        $article = $ART->exists($this->KDIn["datas"]["i"]);
        if (! $article ) {
            $this->Ajax_Return("err","__ERR_VOL_ART_GONE");
        }
        
        //On vérifie si la Lecture des Commentaires de l'Article est autorisée
        $go = $this->DoesItComply($article);
        if (! $go ) {
            $this->Ajax_Return("err","__ERR_VOL_DENY");
        }
        
        $reactions = $ART->article_get_reacts($this->KDIn["datas"]["i"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $reactions) ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else {
            
            /*
             * ETAPE : 
             * On s'attèle à revéler les commentaires que peut supprimer CU 
             */
            $CXH = new CONX_HANDLER();
            foreach ($reactions as $k =>  $react) {
                /*
                 * [DEPUIS 26-04-15] @BOR 
                 *      On decode pour rectifier les conséquences de la prise des Usertags
                 */
//                $reactions[$k]["body"] = html_entity_decode($reactions[$k]["body"]);
                $reactions[$k]["body"] = $reactions[$k]["body"];
                
                if (! $CXH->is_connected() ) {
                    $reactions[$k]["cdel"] = 0;
                } else {
                   
                    if ( $this->CurrentUserCanDelete($react) ) {
//                        echo "";
                        $reactions[$k]["cdel"] = 1;
                    } else {
//                        echo "";
                        $reactions[$k]["cdel"] = 0;
                    }
                }
                
            }
//            var_dump($reactions);
//            exit();
            
            /*
             * [DEPUIS 30-04-15] @BOR
             * On récupère les données sur EVAL
             */
            $EVL = new EVALUATION();
            $me = NULL;
            $EVALs_DATAS = $ART->onload_art_eval($article["art_eid"]);
            if ( isset($this->KDIn["oid"]) ) 
            {
                //Donnée sur l'évaluation de l'utilisateur en cours
                $E_E = $EVL->exists(["actor" => $this->KDIn["oid"],"artid" => $article["artid"]]);
                $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) ? $EVL->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
            }
            $evals = [
                "tab"   => $EVALs_DATAS,
                "me"    => $me
            ];
            
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
        
            //FE = FrontEnd
            $this->KDOut["FE_DATAS"] = [
                "rs"    => $reactions,
                /*
                 * [DEPUIS 30-04-15] @BOR
                 */
                "es"    => $evals,
                "arn"   => $ART->onload_art_rnb($this->KDIn["datas"]["i"])
            ];
//            $this->KDOut["FE_DATAS"] = $reactions;
            
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
        $EXPTD = ["i","cu"];
        
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
            $this->KDIn["locip"]    = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"]      = $oid;
            $this->KDIn["oeid"]     = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["datas"]    = $in_datas;
            
        } else {
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["datas"] = $in_datas;
        }
        
    }

    public function on_process_in() {
        
        $this->GetAllReactsInLimit();
        
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["datas"]["i"], 611, TRUE);
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}
?>