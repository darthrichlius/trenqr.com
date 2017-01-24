<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_TMLNR_ADD_IMLART extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /*********************************************************************/
    /********************** START SPECFIC METHODES ***********************/
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if (  !( isset($v) && $v !== "" ) && !in_array($k,["fdopt"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
//            //On vérifie s'il s'agit du cas FILE_OPTIONS
//            var_dump(__LINE__,$k === "fdopt", !empty($v), array_diff(array_keys($v),["istory","ihosted","edge","top","left"]),"\n");
            if ( $k === "fdopt" && !empty($v) && array_diff(array_keys($v),["istory","ihosted","edge","top","left","xtrabar","orien"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            if ( $k === "fdopt" && $v && $v["xtrabar"] && array_diff(array_keys($v["xtrabar"]),["tx","cd","top"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
            /*
             * [DEPUIS 18-08-16]
             */
            if ( $k === "fdopt" && $v && $v["orien"] ) {
                if ( array_diff(array_keys($v["orien"]),["ang"]) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
                } else {
                    $ang = intval($v["orien"]["ang"]) * -1;
                    $this->KDIn["datas"]["fdopt"]["orien"]["ang"] = $ang;
                }
//                var_dump($this->KDIn["datas"]["fdopt"]);
//                exit();
            } 
            
            /*
             * [DEPUIS 17-07-16]
             *      Une publication ne peut pas être et iSTORy et iHOSTED
             */
            if ( $k === "fdopt" && $v && isset($v["istory"]) && isset($v["ihosted"]) && $v["istory"] === $v["ihosted"] && intval($v["istory"]) === 1 ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }
            
                
            $istr = ["ftype","fdata","fname","msg","curl"];
            if ( $v && in_array($k,$istr) && !is_string($v) ) {
                //On vérifie que les données pour le "body" du commentaire sont valides selon les règles en vigueur au niveau du WORKER
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
    //            }

    //            $istr = ["ftype","fdata","fname","msg","curl"];
    //            if ( $v && in_array($k,$istr) && !is_string($v) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }    
            
        }
        
    }
    
    private function CreateArticle () {
        //* On créé l'Article IML *//
        //PENSE-BETE : ["accid","acc_eid","art_desc","art_locip","file.name","file.type", "file.data", "file.options"];
        
        /*
         * [DEPUIS 26-03-16]
         */
        /*
        $args = [
            "accid"     => $this->KDIn["oid"],
            "acc_eid"   => $this->KDIn["oeid"],
            "art_desc"  => $this->KDIn["datas"]["msg"],
            "art_locip" => $this->KDIn["locip"],
            "pdpic_fn"  => $this->KDIn["datas"]["name"],
            "art_pdpic_string" => urldecode($this->KDIn["datas"]["img"]) 
        ];
        //*/
        
        
        $this->DoesItComply_Datas();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
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
            "file.options"  => $this->KDIn["datas"]["fdopt"]
        ];
        
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$args);
//        exit();
        
        $ART = new ARTICLE();
        
        $article = $ART->on_create_entity($args);
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
//        var_dump(__LINE__,$article);
//        exit();
        
        if ( $article === 0 || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $article)  ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        }
        
        $usertags = NULL;
        if ( key_exists("art_list_usertags", $article) && isset($article["art_list_usertags"]) && is_array($article["art_list_usertags"]) && count($article["art_list_usertags"]) ) {
            /*
             * ETAPE : 
             * On signale l'activité (P.S : le tag, et non l'ajout de l'Article) étant donné qu'il faudra Notifier au destinataire qu'il a été tagué.
             * De plus, enregistrer l'activité est normalement une activté normale (sauf pour la version beta1 qu'il l'implémente au compte-goutte).
             */
            $this->LogUsertagActy($article["art_list_usertags"]);
            
            /*
             * ETAPE : 
             * On retire les données non necessaire. L'utilisation d'une fonction à part entiere permet la portabilité de la fonctionnalité vers qu'd'autres WORKERS
             */
            $usertags = $this->OnlyMyDatas("art_list_usertags",$article["art_list_usertags"]);
        }
        
        /*
         * [DEPUIS 19-06-16]
         */
        $ivid = ( $article["art_vid_url"] ) ? TRUE : FALSE;
        
        //FE = FrontEnd
        $FE_ART = [
            "id" => $article["art_eid"],
            
            //Interessant pour SEO (Le message n'etant pas afficher en mode non ARP
            "msg"       => html_entity_decode($article["art_desc"]),
            "img"       => $article["art_pdpic_path"],
            "time"      => $article["art_creadate"],
            "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_PrmlkToHref($article["art_prmlk"],$ivid),
            /*
             * [DEPUIS 28-03-16]
             */
            "ivid"      => $article["art_is_video"],
            "vidu"      => $article["art_vid_url"],
            "isod"      => $article["art_is_sod"],
            "ihstd"     => $article["art_is_hstd"],
            //RAPPEL : L'appreciation affichée correspond à la différence entre toutes les appréciations
            "eval"      => ( isset($article["art_eval"]) && is_array($article["art_eval"]) && count($article["art_eval"]) === 4 ) ? $article["art_eval"] : [0,0,0,0], 
            "myel"      => "",
            //Le nombre de commentaires 
            "rnb"       => $article["art_rnb"],
            "hashs"     => ( key_exists("art_list_hash",$article) && isset($article["art_list_hash"]) && is_array($article["art_list_hash"]) && count($article["art_list_hash"]) ) ? $article["art_list_hash"] : array(), 
            "ustgs"     => $usertags,
            /* OWNER DATAS */
            "ueid"      => $article["art_oeid"],
            "ufn"       => $article["art_ofn"],
            "upsd"      => $article["art_opsd"],
            "uppic"     => $article["art_oppic"],
            "uhref"     => "/@".$article["art_opsd"],
        ];
        
        $this->KDOut["FE_ART"] = $FE_ART;      
        $this->KDOut["EXTRAS_ART"] = $article;
        
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
    
    private function LogUsertagActy ($ustgs) {
        
        $PM = new POSTMAN();
        foreach ($ustgs as $ustg) {
            
            //On ajoute dans la table des Actions
            $args = [
                "uid"           => $this->KDIn["oid"],
                "ssid"          => session_id(),
                "locip_str"     => $_SESSION['sto_infos']->getCurrent_ipadd(),
                "locip_num"     => $this->KDIn["locip"],
                "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
                "wkr"           => __CLASS__,
                "fe_url"        => $this->KDIn["datas"]["curl"],
                "srv_url"       => $this->KDIn["srv_curl"],
                "url"           => $this->KDIn["srv_curl"],
                "isAx"          => 1,
                "refobj"        => $ustg["ustg_id"],
                "uatid"         => 1101,
                "uanid"         => 2
            ];
            $uai = $PM->UserActyLog_Set($args);
            
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
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "ftype"  : ,
         * "fdata"  : ,
         * "fdopt"  : ,
         * "img"    : ,
         * "name"   : ,
         * "msg"    : ,
         * "curl"   :
         */
//        $EXPTD = ["ftype","fdata","fdopt","img","name","msg","curl"]; //[DEPUIS 26/03/16]
        $EXPTD = ["ftype","fdata","fname","msg","fdopt","curl"];
        
//        var_dump(strlen(base64_decode($_POST["datas"]["img"])));
//        exit();
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            var_dump($_POST["datas"]);
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" ) && in_array($v,["fdopt"]) )  {
                echo __LINE__;
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
        
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["oppic"] = $exists["pdacc_uppic"];
        
        $this->KDIn["datas"] = $in_datas;
        
        $this->KDOut["EXTRAS_USER"] = $exists;
    }

    public function on_process_in() {
        /*
         * [DEPUIS 10-04-15] @BOR
         * Au cas où on aurait besoin de "target".
         * Peu paraitre redondant car on vérifie déjà que l'utilisateur est connecté et existe.
         * Cependant, si le WORKER est employé dans d'autres circonstances, on a une barriere de sécurité supplémentaire.
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
        $u_tab = $this->KDOut["EXTRAS_USER"];
        
        $args = [
            "artid"             => $a_tab["artid"],
            "art_eid"           => $a_tab["art_eid"],
            "art_picid"         => $a_tab["art_picid"],
            "art_pic_rpath"     => $a_tab["art_pdpic_path"],
            "art_desc"          => $a_tab["art_desc"],
            "art_crea_tstamp"   => $a_tab["art_creadate"],
            "art_locip"         => $this->KDIn["locip"],
            /*
             * [DEPUIS 28-03-16]
             */
            "art_is_video"      => $a_tab["art_is_video"],
            "art_vid_url"       => $a_tab["art_vid_url"],
            "art_is_sod"        => $a_tab["art_is_sod"],
            "art_is_hstd"       => $a_tab["art_is_hstd"],
            //Mots-clés
            "art_hashs"         => (! isset($a_tab["art_list_hash"]) ) ? "" : implode(",", $a_tab["art_list_hash"]),
            //Nombre de commentaires
            "art_rnb"           => $a_tab["art_rnb"],
            //Données sur les Evaluations liées à l'Article
//            "art_me" => "", //Il s'agit d'un nouvel Article
            "art_evals"         => implode(",", $a_tab["art_eval"]),
            "art_tot"           => $a_tab["art_eval"][3],
            //Données sur le propriétaire
            "art_oid"           => $u_tab["pdaccid"],
            "art_ogid"          => $u_tab["pdacc_gid"],
            "art_oeid"          => $u_tab["pdacc_eid"],
            "art_opsd"          => $u_tab["pdacc_upsd"],
            "art_ofn"           => $u_tab["pdacc_ufn"],
            "art_oppicid"       => $u_tab["pdacc_uppicid"],
            "art_oppic_rpath"   => $u_tab["pdacc_uppic"],
            "art_todel"         => $u_tab["pdacc_todelete"]
        ];
        
        $ART = new ARTICLE();
        $ART->oncreate_archive_iml($args);
//        exit();
        
        //TEMP TEST DATAS
//        $ART->GenerateFakiesIML("101", "219910320d84", 3);
//        $ART->GenerateFakiesIML("71", "4n3g4n1n1l4n32", 10);
//        $ART->GenerateFakiesIML("70", "8n3i3n2n1l4n31", 1);
//        $ART->GenerateFakiesIML("46", "011701191446", 2);
//        $ART->GenerateFakiesIML("33333", "eidpour33333", 2);
        
        
        
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
}
?>