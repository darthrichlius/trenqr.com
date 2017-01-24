<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_GTPDARTS extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        /*
         * [DEPUIS 24-11-15] @author BOR
         */
        if ( empty($this->KDIn["datas"]["lil"]) && empty($this->KDIn["datas"]["lit"]) ) {
            $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
        }
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            //On vérifie la conformité pour "lil" et "lit"
            if ( $k === "lil" || $k === "lit" ) {
                if ( !isset($v) ) {
                    continue;
                } else if (! is_string($v) ) {
//                    var_dump(__LINE__,$v);
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                } 
                
                //On ne vérifie que 'i', car c'est le seul qui est de type String, donc qui peut provoquer un problème si CALLER envoie qu'elle que chose que l'on peut interpréter comme "rien" !
                $v = $v["i"];
            }
            
            if ( in_array($k,["lil","lit"]) && !empty($v) ) {
                //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
                $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
                $rbody = $v;
    //            $rbody = $this->KDIn["datas"][$k]; //[NOTE 08-03-15] @BlackOwlRobot Peut mieux faire. De plus, cela compliquerait les traitements ci-dessus. 

                preg_match_all("/(\n)/", $rbody, $m_c1);
                preg_match_all("/(\r)/", $rbody, $m_c2);
                preg_match_all("/(\r\n)/", $rbody, $m_c3);
                preg_match_all("/(\t)/", $rbody, $m_c4);
                preg_match_all("/(\s)/", $rbody, $m_c5);

                //Parano : Je sais que j'aurais pu ne mettre que \s
    //            if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) ) {
                if ( ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) && !in_array($k, $SKIP) ) {
    //                var_dump(__LINE__,$v);
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                }
            }
            
            /*
             * [NOTE 24-11-15] @author BOR
             *      IMPORTANT : On met l'instruction à ce niveau pour permettre le traitement normal de "lil" et "lit".
             *      En effet, dans le contraire, si "lil" ou "lit" est NULL, on aboutit à une erreur.
             *      Enfin, j'ai effectué quelques modifications pour qu'il accepte qu'il ne traite pas les spéciaux "lil" et "lit"
             */
            $SKIP = ["hmt"];
            if ( !in_array($k,["lil","lit"]) && !( isset($v) && $v !== "" ) && !in_array($k, $SKIP) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            
            $istr = ["hmt","curl"];
            if ( !empty($v) && in_array($k, $istr) && !is_string($v) ) {
//                var_dump(__LINE__,$v);
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
           
        }
        
    }
    
    private function LockBtmCase() {
        /*
         * Permet de gérer l'opération de LOCK de la page dans le cas de WLC.
         * 
         * LES CAS DE BLOCAGE
         *  CAS 1 : L'utilisateur a déjà cliqué deux fois sur le bouton
         *  CAS 2 : Il a cliqué 1 fois sur le bouton et il n'y a plus d'articles disponibles
         */
        
        /*
         * ETAPE :
         *      On traite le cas des références de chargement s'ils existent
         */
        $hmt = $this->KDIn["datas"]["hmt"];
        if ( !$hmt || !explode(',',$hmt) ) {
            return TRUE; //On indique au script qu'il peut continuer son chemin normalement.
        }
        
        $ids = explode(',',$hmt);
        if (! $ids ) {
            /*
             * C'est considéré comme un HACK !
             */
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur a déjà cliqué au moins 2 fois sur le bouton.
         *      Je mets '>=2' par intuition car je ne pense pas que '===2' aurait été judicieux.
         */
        $last = NULL;
        if ( is_array($ids) && count($ids) >= 2 ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        } else if ( is_array($ids) && count($ids) ) {
            $last = end(array_values($ids));
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        /*
         * ICI : On suppose que l'utilisateur a déjà cliqué une fois sur le bouton.
         * Aussi $ids est un tableau contenant l'identifiant a traité. 
         */
        
        /*
         * ETAPE :
         *      On vérifie que l'article existe pour en récupérer la table
         */
        $ART = new ARTICLE();
        $atab = $ART->exists($last);
        if (! $atab ) {
            return TRUE; //On indique au script qu'il peut continuer son chemin normalement.
        }
        
        /*
         * ETAPE :
         *      On vérifie si l'utilisateur existe et on récupère la table.
         */
        $utab = $this->KDIn["target"];
        if (! $utab ) {
            $this->Ajax_Return("err","__ERR_VOL_U_G");
        }
        
        /*
         * ETAPE :
         *      On vérifie s'il y a des Articles plus bas
         */
//        qryl4pdaccn17neo0815001
        $QO = new QUERY("qryl4artn29");
        $params = array(
            ":ai"   => $atab["artid"],
            ":at"   => $atab["art_cdate_tstamp"],
            ":aoi"  => $utab["pdaccid"]
        );
        $datas = $QO->execute($params);
       
        /*
        * [DEPUIS 11-11-2015] @author BOR 
        *      Pour des soucis de créer un sentiment d'exclusivité, on ne charge pas s'il n'y a un nombre d'Articles restant suffisant.
        *      Aussi, on lock s'il n' y a pas plus de 20 articles.
        */
        if ( !$datas || ( $datas && $datas[0]["cn"] <= 20 ) ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX_AUTH");
        } else {
            return TRUE; //On indique au script qu'il peut continuer son chemin normalement.
        }
        
    }
    
    private function GetPredateImlArticles() {
        $this->KDIn["lil"];
        $iml_articles = NULL;
        
        $PA = new PROD_ACC();
        $RL = new RELATION();
    
        $datas = $PA->onread_load_more_iml_articles($this->KDIn["target"]["pdaccid"], TRUE, $this->KDIn["datas"]["lil"],["VM_ART"]);
//        $datas = $PA->onread_load_more_iml_articles($this->KDIn["target"]["pdaccid"], TRUE, $this->KDIn["datas"]["lil"]);
//        $datas = $PA->onread_load_more_iml_articles($this->KDIn["oid"], true, $this->KDIn["datas"]["lil"]);
//        var_dump(__LINE__,__FILE__,$datas);
//        exit();
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        } else if ( $datas && is_array($datas) && count($datas) ) {
            $ART = new ARTICLE();
            $cn = 0;
            foreach ($datas as $article) {
                
                //On détemine l'évaluation du Compte courant s'il est connecté
                $me = "";
                if ( key_exists("oid", $this->KDIn) && isset($this->KDIn["oid"]) ) {
                    $EV = new EVALUATION();
                    $E_E = $EV->exists(["actor" => $this->KDIn["oid"],"artid" => $article["artid"]]);
                    $me = ( is_array($E_E) && $E_E["evtype_fe"] !== strtoupper("_EVAL_VOID") ) 
                        ? $EV->onread_srvcode_to_fecode($E_E["evtype_fe"]) : "";
                    
                    //On ne controle pas plus que ça car dans ce contexte ça serait riducule et inutile
                }
                
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
                    | $article["art_is_sod"] //ARTICLE_IS_SOD
                )
                {
                    
                    /* //[DEPUIS 19-04-16]
                    $r__ = $RL->onread_relation_exists_fecase($this->KDIn["oid"],$article["art_oid"]);
                    $rl__ = $RL->encode_relcode($r__);
                    //*/
                            
                    if (
                            ( isset($this->KDIn["oid"]) && floatval($this->KDIn["oid"]) === floatval($article["art_oid"]) ) //PROPRIETAIRE
                            | ( isset($this->KDIn["oid"]) && is_array($RL->friend_theyre_friends($this->KDIn["oid"],$article["art_oid"])) ) //AMIS
//                            | in_array($rl__, ["xr02","xr12","xr22"]) //DFOLW
                            | $article["art_is_sod"] //ARTICLE_IS_SOD
                        ) 
                    {
                        $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
                        $prmlk = preg_replace('#^https?://#', '', WOS_MAIN_HTTP_HOST.$ART->onread_AcquierePrmlk($article["art_eid"],$ivid));
                        
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
                            "prmlk"     => $prmlk,
//                            "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"]), //[DEPUIS 21-09-15]
//                            "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]), //[DEPUIS 28-04-15]
                            //L'appreciation affichée correspond à la différence entre toutes les appréciations
                            "eval"      => $article["art_eval"], /* [-1,+2,+1,total]*/
                            //L'évaluation que j'ai donné pour cet article
                            "myel"      => $me, //0(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                            //Le nombre de commentaires 
                            "rnb"       => $article["art_rnb"],
                            "time"      => $article["art_creadate"],
                            "img"       => $article["art_pdpic_path"],
                            "hashs"     => $article["art_list_hash"],
                            "ustgs"     => $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE),
                            "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $article["art_eid"]) ) ? TRUE : FALSE,
                            /*
                             * [DEPUIS 29-03-16]
                             */
                            "vidu"      => $article["art_vid_url"],
                            "isod"      => ( $article["art_is_sod"] ) ? TRUE : FALSE,
                           /*
                            * [DEPUIS 29-03-16]
                            *      Indique si l'ARTICLE doit être distribué en mode RESTRICTED
                            */
                            "isrtd"     => ( $this->KDIn["oid"] ) ? TRUE : FALSE, 
                            /******** OWNER DATAS ********/
                            "ueid"      => $article["art_oeid"],
//                            "uppic"     => $article["art_oppic"],
                            "uppic"     => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
                            "ufn"       => $article["art_ofn"],
                            "upsd"      => $article["art_opsd"],
                            "uhref"     => "/@".$article["art_opsd"]
                        ];
                        
                        $LCM = FALSE;
                    } else {
                        $atab = [
                            "id"    => $article["art_eid"],
                            "ufn"   => $article["art_ofn"],
                            "upsd"  => $article["art_opsd"]
                        ];
                    }
                    
                } else {
                    $atab = [
                        "id"    => $article["art_eid"],
                        "ufn"   => $article["art_ofn"],
                        "upsd"  => $article["art_opsd"]
                    ];
                }
                
                /*
                 * ETAPE :
                 * On indique si l'Article est distribué en mode LOCKMODE.
                 */
                $atab["LCM"] = $LCM;
                
                /*
                 * [DEPUIS 09-11-15] @author BOR
                 */
                ++$cn;
                if ( $cn === count($datas) ) {
                    $this->KDOut["hmt_iml"] = [
                        "ai" => $article["art_eid"],
                        "at" => $article["art_creadate"]
                    ];
                }

                $iml_articles[] = $atab;
            }
            
            return $iml_articles;
        }
        
        return;
    }
    
    private function GetPredateItrArticles() {
        $this->KDIn["lit"];
        $itr_articles = NULL;
        
        $PA = new PROD_ACC();
        
//        $this->perfAtPoint($this->tm_start, __LINE__, TRUE);
//        $datas = $PA->onread_load_more_itr_articles($this->KDIn["target"]["pdaccid"], true, $this->KDIn["datas"]["lit"]);
        $datas = $PA->onread_load_more_itr_articles($this->KDIn["target"]["pdaccid"], true, $this->KDIn["datas"]["lit"], ["VM_ART"]);
//        $datas = $PA->onread_load_more_itr_articles($this->KDIn["oid"], true, $this->KDIn["datas"]["lit"]);
//        var_dump(__LINE__,__FILE__,$datas);
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
//        exit();
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        } else if ( $datas && is_array($datas) && count($datas) ) {
            $ART = new ARTICLE();
            $cn = 0;
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
                
                $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
//                $prmlk = preg_replace('#^https?://#', '', WOS_MAIN_HTTP_HOST.$ART->onread_AcquierePrmlk($article["art_eid"],$ivid));
                $prmlk = $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"],$ivid);
                
                $TRD = new TREND();
                $itr_articles[] = [
                        //Dans la page de developpement, ca n'arrive pas à 20. On peut donc utiliser >20
        //            "id" => 1022, //Tester la fonctionnalité de blocage de Articles en doublon
                    "id"        => $article["art_eid"],
                    "time"      => $article["art_creadate"],
                    "img"       => $article["art_pdpic_path"],
                    //Interessant pour SEO
//                    "msg" => htmlentities($article["art_desc"]),
                    "msg"       => $article["art_desc"],
//                        "msg"   => html_entity_decode($article["art_desc"]),
                    "prmlk"     => $prmlk,
//                        "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($article["art_eid"]), //[DEPUIS 21-09-15]
//                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]), //[DEPUIS 28-04-15]
                    //L'appreciation affichée correspond à la différence entre toutes les appréciations
                    "eval"      => $article["art_eval"], /* [-1,+2,+1,total]*/
                    //L'évaluation que j'ai donné pour cet article
                    "myel"      => $me, //""(aucune), p2 (supaCool), p1 (cool), m1 (-1)
                    //Le nombre de commentaires 
                    "rnb"       => $article["art_rnb"],
                    "hashs"     => $article["art_list_hash"],
                    "ustgs"     => $ART->onread_AcquiereUsertags_Article($article["art_eid"],TRUE),
                    "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $article["art_eid"]) ) ? TRUE : FALSE,
                    /*
                     * [DEPUIS 29-03-16]
                     */
                    "vidu"      => $article["art_vid_url"],
                    /******** OWNER DATAS ********/
                    "ueid"      => $article["art_oeid"],
                    "uppic"     => $PA->onread_acquiere_pp_datas($article["art_oid"])["pic_rpath"], //[DEPUIS 29-04-15]
//                        "uppic"     => $article["art_oppic"],
                    "ufn"       => $article["art_ofn"],
                    "upsd"      => $article["art_opsd"],
                    "uhref"     => "/@".$article["art_opsd"],
                    /*
                     * [DEPUIS 05-05-16]
                     *      Indique si l'ARTICLE doit être distribué en mode RESTRICTED
                     */
                    "isrtd"     => ( $this->KDIn["oid"] ) ? TRUE : FALSE,
                    /******** TREND DATAS ********/
                    //L'id externe de la TENDANCE.
                    "trd_eid"   => $article["trd_eid"],
                    "trtitle"   => $article["trd_title"],
                    "trhref"    => $TRD->on_read_build_trdhref($article["trd_eid"],$article["trd_title_href"]),
//                    "trhref"    => $article["trd_href"], [28-04-15]
                    "istrd"     => TRUE,
                ];
                
                /*
                 * [DEPUIS 09-11-15] @author BOR
                 */
                ++$cn;
                if ( $cn === count($datas) ) {
                    $this->KDOut["hmt_itr"] = [
                        "ai" => $article["art_eid"],
                        "at" => $article["art_creadate"]
                    ];
                }
                $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
            }
            
            return $itr_articles;
        }
        
        return;
        
    }
    
    private function GetPredateArticles() {
        
//        var_dump($this->KDIn["target"]);
//        exit();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        //Récupération des Articles
        $articles_iml = $this->GetPredateImlArticles();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        $articles_itr = $this->GetPredateItrArticles();
        
        $this->KDOut["FE_DATAS"]["iml"] = $articles_iml;
        $this->KDOut["FE_DATAS"]["itr"] = $articles_itr;
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
         
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
         
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
//        var_dump($STOI->getCurr_wto()->getUser());
//        var_dump($_POST["datas"]);
//        exit();
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * lil : Last Article pour IML
         * lit : Last Article pour ITR
         * [RAPPEL]
         *      Liste d'identifiants d'articles situés en fin de collection suivant les opérations précédentes. Permet de gérer le cas de LOCK_BTM.
         *      Les identifiants correspondent toujours au plus ancien affiché.
         * hmt : How Many Times
         */
        $XPTD = ["lil","lit","hmt","curl"];
        
        if ( count($XPTD) !== count(array_intersect(array_keys($_POST["datas"]),$XPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        //On vérifie en amont si on a bel et bien un identifiant. Sinon pas la peine de continuer
        if ( empty($_POST["datas"]["lit"]) && empty($_POST["datas"]["lil"]) ) {
            $this->Ajax_Return("return",NULL);
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if (! ( isset($v) && ( $v !== "" | in_array($k, ["lil","lit","hmt"]) ) ) ) {
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
            
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
            $this->KDIn["oid"] = $oid;
            $this->KDIn["oeid"] = $RSTOI->getAcc_eid();
            $this->KDIn["datas"] = $in_datas;
            
        } else {
            $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $this->KDIn["datas"] = $in_datas;
        }
        
    }

    public function on_process_in() {
        /*
         * [NOTE 28-03-15] @BlackOwlRobot
         * ETAPE : Sécurité & Fiabilité
         * On vérifie que les données envoyées par CALLER sont authentiques.
         * 
         * [DEPUIS 09-11-15]
         *      Déplacer au niveau de on_process_in()
         */
        $this->DoesItComply_Datas();
        
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
        
        if ( is_array($upieces) && key_exists("user", $upieces) && !empty($upieces["user"]) ) {
            $PDACC = new PROD_ACC();
            $this->KDIn["target"] = $PDACC->exists_with_psd($upieces["user"],TRUE);
            if (! $this->KDIn["target"] ) {
                $this->Ajax_Return("err","__ERR_VOL_U_G");
            }
        } else {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        /*
         * [DEPUIS 09-11-15] @author BOR
         *      Utilisée en premier lieu dans le cas de WLC
         */
        if (! ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) ) {
            $this->LockBtmCase();
        }
        
        $this->GetPredateArticles();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        /*
         * [DEPUIS 09-11-15] @author BOR
         *      On récupère l'élément ayant la date la plus antérieure
         */
        if ( $this->KDOut["hmt_iml"] && $this->KDOut["hmt_itr"] ) {
            $this->KDOut["hmti"] = ( floatval($this->KDOut["hmt_iml"]["at"]) <= floatval($this->KDOut["hmt_iml"]["at"]) ) ? $this->KDOut["hmt_iml"]["ai"] : $this->KDOut["hmt_itr"]["ai"];
        } else if ( $this->KDOut["hmt_iml"] ) {
            $this->KDOut["hmti"] = $this->KDOut["hmt_iml"]["ai"];
        } else if ( $this->KDOut["hmt_itr"] ) {
            $this->KDOut["hmti"] = $this->KDOut["hmt_itr"]["ai"];
        }
        $this->KDOut["FE_DATAS"]["hmti"] = $this->KDOut["hmti"];
    }

    public function on_process_out() {
//        var_dump(__LINE__,__FILE__,$this->KDOut["FE_DATAS"]["hmti"]);
//        exit();
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["curl"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["target"]["pdaccid"], 531, TRUE);
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}
?>