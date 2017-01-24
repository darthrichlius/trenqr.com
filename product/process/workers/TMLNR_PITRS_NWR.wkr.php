<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_PITRS_NWR extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function GetNewerItrArticles() {
        $this->KDIn["fit"];
        $itr_articles = NULL;
        
        $PA = new PROD_ACC();
        $datas = $PA->onread_load_more_itr_articles($this->KDIn["target"]["pdaccid"], FALSE, $this->KDIn["datas"]["fit"], ["VM_ART"]);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        } else if ( $datas && is_array($datas) && count($datas) ) {
            $ART = new ARTICLE();
            foreach ($datas as $article) {
                
                //On détemine l'évaluation du Compte courant s'il est connecté
                $me = "";
                if ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) {
                    $EV = new EVALUATION();
                    $E_E = $EV->exists(["actor" => $this->KDIn["oid"],"artid" => $article["artid"]]);
                    if ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) {
                        $me = $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]);
                    } else {
                        $me = "";
                    }
                    //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile
                }
                $TRD = new TREND();
                
                $PA = new PROD_ACC();
                
                $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
                
                $itr_articles[] = [
                        //Dans la page de developpement, ca n'arrive pas à 20. On peut donc utiliser >20
        //            "id" => 1022, //Tester la fonctionnalité de blocage de Articles en doublon
                        "id"        => $article["art_eid"],
                        "time"      => $article["art_creadate"],
                        "img"       => $article["art_pdpic_path"],
                        //Interessant pour SEO
    //                    "msg" => htmlentities($article["art_desc"]),
                        "msg"       => $article["art_desc"], //[DEPUIS 29-04-15] @BOR Pour encodage definitif
//                        "msg"       => html_entity_decode($article["art_desc"]),
                        "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid),
//                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]), //[DEPUIS 29-04-15]
                        //L'appreciation affichée correspond à la différence entre toutes les appréciations
                        "eval"      => $article["art_eval"], /* [-1,+2,+1,total]*/
                        //L'évaluation que j'ai donné pour cet article
                        "myel"      => $me, //""(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                        //Le nombre de commentaires 
                        "rnb"       => $article["art_rnb"],
                        "hashs"     => $article["art_list_hash"],
                        "ustgs"     => $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE),
                       /*
                        * [DEPUIS 29-03-16]
                        */
                        "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $article["art_eid"]) ) ? TRUE : FALSE,
                        "vidu"      => $article["art_vid_url"],
                       /*
                        * [DEPUIS 21-05-16]
                        *      Indique si l'ARTICLE doit être distribué en mode RESTRICTED
                        */
                        "isrtd"     => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                        /******** OWNER DATAS ********/
                        "ueid"      => $article["art_oeid"],
                        "uppic"     => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                        "uppic"     => $article["art_oppic"],
                        "ufn"       => $article["art_ofn"],
                        "upsd"      => $article["art_opsd"],
                        "uhref"     => "/@".$article["art_opsd"],
                        /******** TREND DATAS ********/
                        //L'id externe de la TENDANCE.
                        "trd_eid"   => $article["trd_eid"],
                        "trtitle"   => $article["trd_title"],
                        "trhref"    => $TRD->on_read_build_trdhref($article["trd_eid"],$article["trd_title_href"]),
//                    "trhref"    => $article["trd_href"], [28-04-15]
                       /*
                        * [DEPUIS 21-05-16]
                        */
                        "istrd"     => TRUE,
                    ];
            }
            
            return $itr_articles;
        }
        
        return;
        
    }
    
    private function GetPredateArticles() {
        //On détermine la cible
        /*
         * (29-11-14)
         *  (1) Pour déterminer la cible on se base sur l'url de la page envoyée par FE. Cette manière de faire n'est pas très fiable.
         *  Une méthode fiable serait de récupérer les valeurs dans la variable de SESSION. Cependant, cette dernière n'a pas été concu pour faire la distinction entre
         *  les requetes de page et AJax. Aussi, la derniere requete est forcement celle d'AJAX.
         *  Il faut faudrait modifier : WTO, URQTABLE pour permettre de séparer les deux et ainsi obtenir la page de référence.
         *  Par la meme occasion, on pourrait aussi ajouter une foncitonnalité pour l'historisation de la navigation de l'utilisateur.
         */
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $upieces = $TQR->explode_tqr_url($this->KDIn["datas"]["curl"]);
        
//        var_dump($this->KDIn["datas"]["curl"],$upieces);
//        exit();
        
        if ( $upieces && is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) {
            $PDACC = new PROD_ACC();
            $this->KDIn["target"] = $PDACC->exists_with_psd($upieces["user"],TRUE);
            if (! $this->KDIn["target"] ) {
                $this->Ajax_Return("err","__ERR_VOL_U_G");
            }
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
//        var_dump($this->KDIn["target"]);
//        exit();
        
        //Récupération des Articles
        $articles_itr = $this->GetNewerItrArticles();
        
        $this->KDOut["FE_DATAS"] = $articles_itr;
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
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
         
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["fit","curl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        //On vérifie en amont si on a bel et bien un identifiant. Sinon pas la peine de continuer
        if ( empty($_POST["datas"]["fit"]) ) {
            $this->Ajax_Return("return",NULL);
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
            
            $oid = $RSTOI->getAccid();
            $A = new PROD_ACC();
            $exists = $A->exists_with_id($oid, TRUE);

            if ( !$exists ) {
                $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
            }
            
            $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $RSTOI->getAcc_eid();
            $this->KDIn["datas"] = $in_datas;
            
        } else {
             $this->KDIn["datas"] = $in_datas;
        }
        
    }

    public function on_process_in() {
        $this->GetPredateArticles();
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