<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TRPG_NW_ART extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
            //On vérifie s'il s'agit d'un cas de body
            if ( $k === "msg" ) {
                
                //On vérifie que les données pour la description sont valides selon les règles en vigueur au niveau du WORKER
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
        
    }
    
    private function CreateArticle () {
        //* On créé l'Article IML *//
        
        //PENSE-BETE : ["trid","trtitle","img","name","msg"];
        /* //[DEPUIS 20-05-16]
        $args = [
            "accid"             => $this->KDIn["oid"],
            "acc_eid"           => $this->KDIn["oeid"],
            "art_desc"          => $this->KDIn["datas"]["msg"],
            "art_locip"         => $this->KDIn["locip"],
            "pdpic_fn"          => $this->KDIn["datas"]["name"],
            "art_pdpic_string"  => urldecode($this->KDIn["datas"]["img"]), 
            "trd_eid"           => urldecode($this->KDIn["datas"]["trid"]) 
        ];
        //*/
        
        
       /*
        * [DEPUIS 02-08-16]
        */
        $ran = mt_rand(0,26);
        $alph = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','_'];
        $fn = preg_replace("/([^\w\.])/i",$alph[$ran],$this->KDIn["datas"]["fname"]);
        
        $args = [
            "accid"         => $this->KDIn["oid"],
            "acc_eid"       => $this->KDIn["oeid"],
            "art_desc"      => $this->KDIn["datas"]["msg"],
            "art_locip"     => $this->KDIn["locip"],
            /*
             * [DEPUIS 02-08-16]
             */
//            "file.name"     => $this->KDIn["datas"]["fname"],
            "file.name"     => $fn,
            "file.type"     => $this->KDIn["datas"]["ftype"],
            "file.data"     => urldecode($this->KDIn["datas"]["fdata"]),
            "file.options"  => $this->KDIn["datas"]["fdopt"],
            "trd_eid"       => urldecode($this->KDIn["datas"]["trid"]),
        ]; 
        
//        var_dump(__LINE__,$this->KDIn["datas"]["msg"]);
//        var_dump(__FUNCTION__,__LINE__,[$args]);
//        exit();
        
        $this->DoesItComply_Datas();
        
        /*
         * [NOTE 27-08-14 à 21:31] par L.C.
         * ATTENTION : 
         * Normalement accid dans args doit venir de FE. On s'en sert notamment pour détecter si l'utilisateur en FE essai de passer de fausses infos.
         * Cependant, à la vb1 on préfère ne prendre aucun risque et donner le OWNER en prenant l'identifiant dans la variable de SESSION. 
         */
        
        $ART = new ARTICLE_TR();
        $article = $ART->on_create($args,$this->KDIn["oid"]);
        
        if ( $article === 0 ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $article)  ) {
            $this->Ajax_Return("err",$article);
        }
        
        //On récupère l'HREF de la Tendance
        $TR = new TREND();
        //RAPPEL : Mettre le paramètre 'urqid' à NULL permet d'obliger l'Entity à sélectionner URQID par défaut.
        $urqid = ( $this->KDIn["oeid"] === $this->KDIn["datas"]["toid"] ) ? "manager" : "contributor";
//        $urqid = ( $this->KDIn["oeid"] === $this->KDIn["datas"]["toid"] ) ? "TRPG_GTPG_RO" : "explore"; //DEV, DEBUG, TEST
//        var_dump($urqid,$this->KDIn["oeid"],$this->KDIn["datas"]["toid"]);
//        exit();
        
        $trd_tab =  ( $urqid === "manager" ) ? 
            $TR->on_read_entity(["trd_eid" => $article["trd_eid"], "urqid" => $urqid]) :
            $TR->on_read_entity(["trd_eid" => $article["trd_eid"]]);
        
        $cov_datas = NULL;
        if ( $trd_tab["trd_cover"] ) {
            $cov_datas = [
                "cov_w"     => $trd_tab["trd_cover"]["trcov_width"],
                "cov_h"     => $trd_tab["trd_cover"]["trcov_height"],
                "cov_t"     => $trd_tab["trd_cover"]["trcov_top"],
                "cov_rp"    => $trd_tab["trd_cover"]["pdpic_realpath"],
            ];
        }
        
        $usertags = NULL;
        if ( key_exists("art_list_usertags", $article) && isset($article["art_list_usertags"]) && is_array($article["art_list_usertags"]) && count($article["art_list_usertags"]) ) {
            /*
             * ETAPE : 
             * On enregistre l'activité au niveau de UserActivity.
             * Pour chaque marquage, on ajoute dans la base de données.
             * NOTE : On vérifie bien qu'on enregistre pas quand la personne se tag elle même sur son propre Article.
             */
            //TODO !
            
            //On retire les données non necessaire. L'utilisation d'une fonction à part entiere permet la portabilité de la fonctionnalité vers qu'd'autres WORKERS
            $usertags = $this->OnlyMyDatas("art_list_usertags",$article["art_list_usertags"]);
        }
        
        $FE_ART = [
            /*
        "art" => [
                "id"        => $article["art_eid"],
                "img"       => $article["art_pdpic_path"],
                "time"      => $article["art_creadate"],    
                "utc"       => NULL,    
//                "msg"       => html_entity_decode($article["art_desc"]),
                "msg"       => $article["art_desc"],
//                "msg"       => htmlentities($article["art_desc"]),
                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]),
                "trid"      => $trd_tab["trd_eid"],
                "trtitle"   => $trd_tab["trd_title"],
                "trhref"    => $trd_tab["trd_href"],
                "rnb"       => $article["art_rnb"],
                "hashs"     => ( key_exists("art_list_hash",$article) && isset($article["art_list_hash"]) && is_array($article["art_list_hash"]) && count($article["art_list_hash"]) ) ? $article["art_list_hash"] : array(),
                "ustgs"     => $usertags,
                "eval"      => ( isset($article["art_eval"]) && is_array($article["art_eval"]) && count($article["art_eval"]) === 4 ) ? $article["art_eval"] : [0,0,0,0],
                "eval_lt"   => ['','','',''],
                "myel"      => "" //Choix : p2,p1,m1,0
            ],
            "user" => [
                "ueid"      => $article["art_oeid"],
                "ufn"       => $article["art_ofn"],
                "upsd"      => $article["art_opsd"],
                "uppic"     => $this->KDIn["oppic"],
                "uhref"     => $article["art_ohref"],
                "ucontb"    => NULL
            ],
            "tr" => [
                "trid"      => $trd_tab["trd_eid"],
                "trtle"     => $trd_tab["trd_title"],
//                "trtle" => "Fake Title", /DEV, DEBUG, TEST
//                "trtle_h" => $trd_tab["trd_title_href"],
                "trdesc"    => $trd_tab["trd_desc"],
                "trhref"    => $trd_tab["trd_href"],
                "trpnb"     => $trd_tab["trd_stats_posts"],
                "trfol"     => $trd_tab["trd_stats_subs"],
                "trcov"     => $cov_datas
                /* PROPRIETAIRE DE LA TENDANCE *
//                "troid" => $trd_tab["trd_eid"],
//                "tofn" => $trd_tab["trd_title"],
//                "tropsd" => $trd_tab["trd_desc"],
//                "trohref" => $trd_tab["trd_href"]
            ],
            "tr_stsg" => [
                "t" => $trd_tab["trd_title"],
                "d" => html_entity_decode($trd_tab["trd_desc"]),
                "c" => "_NTR_CATG_".strtoupper($trd_tab["catg_decocode"]), //Juste pour envoyer en attendant amélioration
                "p" => [
                    ( $trd_tab["trd_is_public"] ) ? "_NTR_PART_PUB" : "_NTR_PART_PRI",
                    ( $trd_tab["trd_is_public"] ) ? "Public" : "Privé"
                ], 
                "g" => 0, //Juste pour envoyer en attendant amélioration
            ]
            //*/
            "art" => [
                "id"        => $article["art_eid"],
                "img"       => $article["art_pdpic_path"],
                "time"      => $article["art_creadate"],    
                "utc"       => NULL,    
        //                "msg"       => html_entity_decode($article["art_desc"]),
                "msg"       => $article["art_desc"],
        //                "msg"       => htmlentities($article["art_desc"]),
                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"]),
                "trid"      => $trd_tab["trd_eid"],
                "trtitle"   => $trd_tab["trd_title"],
                "trhref"    => $trd_tab["trd_href"],
                "rnb"       => $article["art_rnb"],
                "hashs"     => ( key_exists("art_list_hash",$article) && isset($article["art_list_hash"]) && is_array($article["art_list_hash"]) && count($article["art_list_hash"]) ) ? $article["art_list_hash"] : array(),
                "ustgs"     => $usertags,
                "eval"      => ( isset($article["art_eval"]) && is_array($article["art_eval"]) && count($article["art_eval"]) === 4 ) ? $article["art_eval"] : [0,0,0,0],
                "eval_lt"   => ['','','',''],
                "myel"      => "", //Choix : p2,p1,m1,0
               /*
                * [DEPUIS 29-03-16]
                */
                "fvtp"      => NULL,
                "fvtm"      => NULL,
                "vidu"      => $article["art_vid_url"],
                "isod"      => ( intval($article["art_is_sod"]) === 1 ) ? TRUE : FALSE,
                //Indique si l'ARTICLE doit être distribué en mode RESTRICTED
                "isrtd"     => TRUE,
                /***** PROPRIETAIRE ARTICLE *****/
                "ueid"      => $article["art_oeid"],
                "ufn"       => $article["art_ofn"],
                "upsd"      => $article["art_opsd"],
                "uppic"     => $this->KDIn["oppic"],
                "uhref"     => $article["art_ohref"],
                "ucontb"    => NULL,
                /***** INFOS TENDANCE *****/
                "trid"      => $trd_tab["trd_eid"],
                "trtle"     => $trd_tab["trd_title"],
        //                "trtle" => "Fake Title", /DEV, DEBUG, TEST
        //                "trtle_h" => $trd_tab["trd_title_href"],
                "trdesc"    => $trd_tab["trd_desc"],
                "trhref"    => $trd_tab["trd_href"],
                "trpnb"     => $trd_tab["trd_stats_posts"],
                "trfol"     => $trd_tab["trd_stats_subs"],
                "trcov"     => $cov_datas,
                "istrd"     => TRUE,
            ],
            "tr_stsg" => [
                "t" => $trd_tab["trd_title"],
                "d" => html_entity_decode($trd_tab["trd_desc"]),
                "c" => "_NTR_CATG_".strtoupper($trd_tab["catg_decocode"]), //Juste pour envoyer en attendant amélioration
                "p" => [
                    ( $trd_tab["trd_is_public"] ) ? "_NTR_PART_PUB" : "_NTR_PART_PRI",
                    ( $trd_tab["trd_is_public"] ) ? "Public" : "Privé"
                ], 
                "g" => 0, //Juste pour envoyer en attendant amélioration
            ]
        ];
                
        $this->KDOut["FE_ART"] = $FE_ART;        
        $this->KDOut["EXTRAS_ART"] = $article;
        $this->KDOut["EXTRAS_TRD"] = $trd_tab;
        
    }
    
    
    private function OnlyMyDatas ($k,$d) {
        $ndt = [];
        switch ($k) {
            case "art_list_usertags" :
                foreach ($d as $ut) {
                    $ndt[] = [
                        'eid'   => $ut['ustg_eid'],
                        'ueid'  => $ut['tgtueid'],
                        'ufn'   => $ut['tgtufn'],
                        'upsd'  => $ut['tgtupsd']
                    ];
                }
                break;
            default :
                return;
        }
        return $ndt;
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
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        //* On vérifie que toutes les données sont présentes *//
//        $EXPTD = ["trid","toid","p","d","n"];
        $EXPTD = ["trid","trtle","toid","ftype","fdata","fname","fdopt","msg","curl"];
        
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
        $exists = $A->on_read_entity(["acc_eid" => $_SESSION["rsto_infos"]->getAcc_eid()]);
        
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_CU_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["oppic"] = $exists["pdacc_uppic"];
        /* Données sur la Tendance liée et autres */
        
        /* //[DEPUIS 20-05-16]
        $this->KDIn["datas"]["trid"] = $in_datas["trid"];
        $this->KDIn["datas"]["toid"] = $in_datas["toid"];
        $this->KDIn["datas"]["img"] = $in_datas["p"];
        $this->KDIn["datas"]["name"] = $in_datas["n"];
        $this->KDIn["datas"]["msg"] = $in_datas["d"];
        //*/
        
        $this->KDIn["datas"]["trid"] = $in_datas["trid"];
        $this->KDIn["datas"]["toid"] = $in_datas["toid"];
        $this->KDIn["datas"]["ftype"] = $in_datas["ftype"];
        $this->KDIn["datas"]["fdata"] = $in_datas["fdata"];
        $this->KDIn["datas"]["fname"] = $in_datas["fname"];
        $this->KDIn["datas"]["fdopt"] = $in_datas["fdopt"];
        $this->KDIn["datas"]["msg"] = $in_datas["msg"];
        
                
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        
        $this->KDOut["EXTRAS_USER"] = $exists;
    }

    public function on_process_in() {
        $this->CreateArticle();
    }

    public function on_process_out() {
        
        $this->Ajax_Return("return",$this->KDOut["FE_ART"],FALSE);
        
        /*
         * [NOTE 30-09-14] @author L.C.
         * A chaque ajout, on crée une occurence dans la table VM correspondante.
         * Cette table permet de meilleures performances dans les opérations de lecture.
         */
        $a_tab = $this->KDOut["EXTRAS_ART"];
        $t_tab = $this->KDOut["EXTRAS_TRD"];
        $u_tab = $this->KDOut["EXTRAS_USER"];
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$a_tab,$t_tab,$u_tab],'v_d');
//        exit();
        
        $args = [
            "artid"                 => $a_tab["artid"],
            "art_eid"               => $a_tab["art_eid"],
            "art_picid"             => $a_tab["art_picid"],
            "art_pic_rpath"         => $a_tab["art_pdpic_path"],
            "art_desc"              => $a_tab["art_desc"],
            "art_crea_tstamp"       => $a_tab["art_creadate"],
            "art_locip"             => $this->KDIn["locip"],
            "art_locip"             => $this->KDIn["locip"],
	    /*
             * [DEPUIS 20-05-16]
             */
            "art_is_video"          => $a_tab["art_is_video"],
            "art_vid_url"           => $a_tab["art_vid_url"],
            //Données sur la Tendance liée
            "art_trid"              => $t_tab["trid"],
            "art_trd_eid"           => $t_tab["trd_eid"],
            "art_trd_title"         => $t_tab["trd_title"],
            "art_trd_desc"          => $t_tab["trd_desc"],
            "art_trd_title_href"    => $t_tab["trd_title_href"],
            "art_trd_catgid"        => $t_tab["trd_catgid"],
            "art_trd_is_public"     => $t_tab["trd_is_public"],
            "art_trd_grat"          => $t_tab["trd_grat"],
            "art_trd_date_tstamp"   => $t_tab["trd_creadate_tstamp"],
            //Mots-clés
            "art_hashs"             => (! isset($a_tab["art_list_hash"]) ) ? "" : implode(",", $a_tab["art_list_hash"]),
            //Nombre de commentaires
            "art_rnb"               => $a_tab["art_rnb"],
            //Données sur les Evaluations liées à l'Article
//            "art_me" => "", //Il s'agit d'un nouvel Article
            "art_evals"             => implode(",", $a_tab["art_eval"]),
            "art_tot"               => $a_tab["art_eval"][3],
            //Données sur le propriétaire
            "art_oid"               => $u_tab["pdaccid"],
            "art_ogid"              => $u_tab["pdacc_gid"],
            "art_oeid"              => $u_tab["pdacc_eid"],
            "art_opsd"              => $u_tab["pdacc_upsd"],
            "art_ofn"               => $u_tab["pdacc_ufn"],
            "art_oppicid"           => $u_tab["pdacc_uppicid"],
            "art_oppic_rpath"       => $u_tab["pdacc_uppic"],
            "art_todel"             => $u_tab["pdacc_todelete"]
        ];
        
//        var_dump(__LINE__,$args["art_desc"]);
        
        $ART = new ARTICLE();
        $ART->oncreate_archive_itr($args);
        
        /*
         * [NOTE 25-08-15] @BOR
         * Mise à jour des données de la Tendance au niveau de SRH
         */
        $TRD = new TREND();
        $TRD->onalter_update_archv_trend(["trid" => $t_tab["trid"]]);
        
        exit();
        
        //TEMP TEST DATAS
        $ART->GenerateFakiesITR("70", "8n3i3n2n1l4n31", $t_tab["trd_eid"], 1);
        $ART->GenerateFakiesITR("71", "4n3g4n1n1l4n32", $t_tab["trd_eid"], 1);
        $ART->GenerateFakiesITR("46", "011701191446", $t_tab["trd_eid"], 2);
        $ART->GenerateFakiesITR("33333", "eidpour33333", $t_tab["trd_eid"], 3);
        $ART->GenerateFakiesITR("55", "101461051955", $t_tab["trd_eid"], 2);
        $ART->GenerateFakiesITR("53", "082701070253", $t_tab["trd_eid"], 1); 
        
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}



?>