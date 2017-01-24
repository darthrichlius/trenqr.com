<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_PIMLS_NWR extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    
    
    private function GetNewerImlArticles() {
        
//        $this->KDIn["fil"]; //?
        $iml_articles = NULL;
        
        $PA = new PROD_ACC();
        $RL = new RELATION();
        
        $datas = $PA->onread_load_more_iml_articles($this->KDIn["target"]["pdaccid"], FALSE, $this->KDIn["datas"]["fil"], ["VM_ART"]);
        
//        var_dump($datas);
//        exit();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        } else if ( $datas && is_array($datas) && count($datas) ) {
            $ART = new ARTICLE();
            foreach ($datas as $article) {
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                //On détemine l'évaluation du Compte courant s'il est connecté
                $me = "";
                if ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) {
                    $EV = new EVALUATION();
                    $E_E = $EV->exists(["actor" => $this->KDIn["oid"],"artid" => $article["artid"]]);
                    $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) 
                        ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"])
                        : "";
                    //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile
                }
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                /*
                 * [DEPUIS 24-04-15] @BOR
                 * ETAPE :
                 * On vérifie la sotuation dans laquelle nous nous trouvons pour vérifier que l'utilisateur a le droit d'accès à l'Article dans son introduction.
                 * Les données ne sont envoyées qu'en fonction du cas en présence. 
                 * 
                 * RAPPEL :
                 *      -> On envoie toutes les données dans le cas où CU est OWN 
                 *      -> On envoie toutes les données si la Relation entre les deux utilisateurs sont : DFOLW, FRIEND
                 *      -> On envoie qu'une infime partie des données 
                 *      -> DANS TOUS LES CAS : On indique la Relation dans les données pour aider FE à afficher correctement les données.
                 */
                
                $LCM = TRUE;
                if ( 
                    ( key_exists("oid",$this->KDIn) && isset($this->KDIn["oid"]) )
                    | $article["art_is_sod"] //ARTICLE_IS_SOD )
                ) {
                    $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//                    $r__ = $RL->onread_relation_exists_fecase($this->KDIn["oid"],$article["art_oid"]);
//                    $rl__ = $RL->encode_relcode($r__);
                    
                    if (
                            ( isset($this->KDIn["oid"]) && floatval($this->KDIn["oid"]) === floatval($article["art_oid"]) ) //PROPRIETAIRE
                            || ( isset($this->KDIn["oid"]) && is_array($RL->friend_theyre_friends($this->KDIn["oid"],$article["art_oid"])) ) //AMIS
//                            || in_array($rl__, ["xr02","xr12","xr22"]) //DFOLW
                            || $article["art_is_sod"] //ARTICLE_IS_SOD
                        ) 
                    {
                        $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
                        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                        $atab = [
                            //Dans la page de developpement, ca n'arrive pas à 20. On peut donc utiliser >20
                //            "id" => 1022, //Tester la fonctionnalité de blocage de Articles en doublon
                            "id"        => $article["art_eid"],
                            //Interessant pour SEO
                            /*
                             * [NOTE 01-09-14] @author <lou.carther@deuslynn-entreprise.com>
                             * Je n'ai pas pu trouver l'origine des différences dans l'affichage au niveau du FE. 
                             * Pour gagner du temps je decode pour IML. 
                             * On verra ce qu'il faudra faire pour la suite.
                             */
                            "msg"       => html_entity_decode($article["art_desc"]),
                            "time"      => $article["art_creadate"],
                            "img"       => $article["art_pdpic_path"],
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
                            "isod"      => ( $article["art_is_sod"] ) ? TRUE : FALSE,
                            /******** OWNER DATAS ********/
                            "ueid"      => $article["art_oeid"],
                            "uppic"     => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                            "uppic"     => $article["art_oppic"],
                            "ufn"       => $article["art_ofn"],
                            "upsd"      => $article["art_opsd"],
                            "uhref"     => "/@".$article["art_opsd"]
                        ];
                        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                        $LCM = FALSE;
                        
                        /*
                         * [DEPUIS 01-06-15] @BOR
                         * On indique ce qu'il en est en ce qui concerne l'accès aux boutons "Action"
                         */
//                        var_dump("LINE => ",floatval($this->KDIn["oid"]) === floatval($article["art_oid"]),in_array($rl__, ["xr02","xr12","xr22"]),is_array($RL->friend_theyre_friends($this->KDIn["oid"],$article["art_oid"])));
                        if (
                            floatval($this->KDIn["oid"]) === floatval($article["art_oid"]) //PROPRIETAIRE
                        ) {
                            $atab["af_ena"] = ["del","pml"];
                        } else if ( 
//                            in_array($rl__, ["xr02","xr12","xr22"]) 
                            $article["art_is_sod"]
                            || is_array($RL->friend_theyre_friends($this->KDIn["oid"],$article["art_oid"]))
                        ) {
                            //DFOLW
                            $atab["af_ena"] = ["pml"];
                        }
                        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
                    } else {
                        $atab = [
                            "id" => $article["art_eid"]
                        ];
                    }
                    
                } else {
                    $atab = [
                        "id" => $article["art_eid"]
                    ];
                }
                
                /*
                 * ETAPE :
                 * On indique si l'Article est distribué en mode LOCKMODE.
                 */
                $atab["LCM"] = $LCM;
                
                $iml_articles[] = $atab;
                
            }
            
            return $iml_articles;
        }
        
        return;
    }
    
    
    private function GetPredateArticles() {
        $articles_iml = $this->GetNewerImlArticles();
        
        $this->KDOut["FE_DATAS"] = $articles_iml;
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
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        //* On vérifie que toutes les données sont présentes *//
        $EXPTD = ["fil"];
        
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
            
            $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
            $this->KDIn["datas"] = $in_datas;
            
        } else {
            $this->KDIn["datas"] = $in_datas;
        }
        
    }

    public function on_process_in() {
        //On détermine la cible
        /*
         * (29-11-14)
         *  (1) Pour déterminer la cible on se base sur l'URL de la page envoyée par FE. Cette manière de faire n'est pas très fiable.
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