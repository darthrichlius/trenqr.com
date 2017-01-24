<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_NWFD_GARTS extends WORKER  {
    
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
            
            if ( ($k === "mn") && !in_array($v, ["comy","team","bzfeed","iml_frd","iml_pod","itr"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            } else if ( $k === "vw" && !in_array($v, ["list","moz"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            } else if ( $k === "w" && !in_array($v, ["std","new","old"]) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            } 
            
        }
        
    }
    
    
    private function GetArticles () {
        //* On créé le commentaire pour Article *//
        
        $this->DoesItComply_Datas();
        
        $NWFD = new NEWSFEED();
        
        $datas;
        switch ($this->KDIn["datas"]["w"]) {
            case "std":
                    $datas = $NWFD->GetFirstArticles($this->KDIn["oid"], $this->KDIn["datas"]["vw"], $this->KDIn["datas"]["mn"]);
                break;
            case "new":
                break;
            case "old":
                break;
            default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                break;
        }
//        utf8_decode($v["art_desc"]);
//    var_dump("AVANT => ",$datas["as"]);    
        
        $FE = [];
        $ART = new ARTICLE();
        $RL = new RELATION();
        
        $PA = new PROD_ACC();
        foreach ( $datas["as"] as $k => $v ) {
            $title = $tle_hrf = NULL;
            if ( key_exists("art_trd_eid", $v) && isset($v["art_trd_eid"]) ) {
                $TRD = new TREND();
                $tr_infos = $TRD->trend_get_trend_infos($v["art_trd_eid"]);
                if (! $tr_infos) {
                    continue;
//                    $this->Ajax_Return("err","__ERR_VOL_TRD_GONE"); //[DEPUIS 04-08-15] @BOR
                }
                $title = $tr_infos["trd_title"];
                $tle_hrf = $tr_infos["trd_title_href"];
            }
            
            $aoid = $PA->onread_get_accid_from_acceid($v["art_oeid"]);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $aoid) ) {
                $this->Ajax_Return("err","__ERR_VOL_FAILED");
            }
            
            $ivid = ( $v["art_vid_url"] ) ? TRUE : FALSE;
            
            /*
             * [DEPUIS 19-05-16]
             */
            $pod_rtm = [];
            $now = round(microtime(TRUE)*1000);
            $fut = floatval($v["art_time"])+(24*3600000);
            if ( intval($v["art_is_sod"]) === 1 && $now <= $fut ) {
                
                $delta = abs($fut - $now)/1000;
//                $days = floor($delta / 86400);
//                $delta -= $days * 86400;

                // HEURES
                $hours = floor($delta / 3600) % 24;
                $delta -= $hours * 3600;

                // MINUTES
                $minutes = floor($delta / 60) % 60;
                $delta -= $minutes * 60;

                // SECONDES
                $seconds = $delta % 60;  

                $pod_rtm = [
                    "now"   => $now,
                    "fut"   => $fut,
                    "h"     => $hours,
                    "m"     => $minutes,
                    "s"     => $seconds,
                ];
            }
            
        
            /*
             * [NOTE 01-05-16]
             *      J'ai modifié les KEYS pour les standardisé et faciliter le traitement au niveau de FE
             */
            $FE[] = [
                "id"        => $v["art_eid"],
                "time"      => $v["art_time"],
                "img"       => $v["art_pic_rpath"],
//                "adesc"     => html_entity_decode(html_entity_decode($v["art_desc"])),//[NOTE 26-04-15] @BOR A cause du fait qu'on utilise VM qui subbit une double HTMLENTITIES
//                "adesc"     => html_entity_decode($v["art_desc"]),
                /*
                 * [NOTE 05-05-15] @BOR
                 *      Chaque cas est différent
                 */
//                "adesc"     => $v["art_desc"],
//                "msg"       => html_entity_decode($v["art_desc"]), //[DEPUIS 09-04-16] Pour prende en compte le cas de la gestion de texte via RENDER
                "msg"       => $v["art_desc"], //[DEPUIS 09-04-16] Pour prende en compte le cas de la gestion de texte via RENDER
                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($v["art_eid"],$ivid),
                "eval"      => $v["art_evals"],
                "me"        => $v["art_me"],
                "tot"       => $v["art_tot"],
                "rnb"       => $v["art_rnb"],
                "aba"       => $v["aba"],
                /*
                 * [DEPUIS 30-04-16]
                 *      Pour des raisons de standardisation, j'ai retiré le préfixe "a" devant "ustgs" et "hashs"
                 */
                "hashs"     => (! isset($v["art_hashs"]) ) ? "" : explode(",", $v["art_hashs"]),
                "ustgs"     => $ART->onread_AcquiereUsertags_Article($v["art_eid"],TRUE),
                /*
                 * [DEPUIS 19-05-16]
                 */
                "pod_rtm"   => $pod_rtm,
                /********** TREND DATAS **********/
                "trd_eid"   => ( key_exists("art_trd_eid", $v) && isset($v["art_trd_eid"]) ) ? $v["art_trd_eid"] : NULL,
                "trtitle"   => ( key_exists("art_trd_title", $v) && isset($v["art_trd_eid"]) && !empty($title) ) ? $title : NULL,
//                "atrtle"    => ( key_exists("art_trd_title", $v) && isset($v["art_trd_title"]) ) ? $v["art_trd_title"] : NULL,
//                "atrtle"    => ( key_exists("art_trd_title", $v) && isset($v["art_trd_title"]) ) ? html_entity_decode($v["art_trd_title"]) : NULL,
                "trhref"    => ( key_exists("art_trd_href", $v) && isset($v["art_trd_href"]) ) ? $TRD->on_read_build_trdhref($v["art_trd_eid"],$tle_hrf) : NULL,
//                "atrhrf"    => ( key_exists("art_trd_href", $v) && isset($v["art_trd_href"]) ) ? $v["art_trd_href"] : NULL,
                "istrd"     => ( key_exists("art_trd_eid", $v) && isset($v["art_trd_eid"]) ) ? TRUE : FALSE,
                /********** ARTICLE OWNER DATAS **********/
                "uid"       => $v["art_oeid"],
                "ufn"       => $v["art_ofn"],
                "uhref"     => $v["art_ohref"],
                "uppic"     => $PA->onread_acquiere_pp_datas($aoid)["pic_rpath"], //[DEPUIS 29-04-15] 
//                "oppic"     => $v["art_oppic_rpath"],
                "upsd"      => $v["art_opsd"],
                /************/
                //hatr : HasAccessToReactions
                "hatr"      => ( !empty($v["art_trd_eid"]) | $RL->friend_theyre_friends($this->KDIn["oid"], $aoid) ) ? TRUE : FALSE, //[DEPUIS 11-07-15] @BOR
                /************/
                /*
                 * [DEPUIS 08-04-16]
                 */
                "hasfv"     => ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $ART->Favorite_hasFavorite($this->KDIn["oid"], $v["art_eid"]) ) ? TRUE : FALSE,
                "vidu"      => $v["art_vid_url"],
                "isod"      => ( intval($v["art_is_sod"]) === 1 ) ? TRUE : FALSE,
            ];
        }
        
        $TQR  = new TRENQR();
        $lads_datas = $TQR->lasta_GetLastActivities_Network($this->KDIn["oeid"],10,TRUE);
        
        $datas = [
            "las"   => $lads_datas,
            "as"    => $FE
        ];
        
//        var_dump("APRES => ",$datas["as"]);   
//        var_dump(__FILE__,__FUNCTION__,__FILE__,$datas["las"]);   
//        exit();
        
        /*
         * [NOTE 05-05-16]
         *      Je ne comprends pas les lignes ci-dessous
         */
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) ) {
            $this->Ajax_Return("err",$datas);
        }
        
//        $this->KDOut["FE_DATAS"] = NULL;        
        $this->KDOut["FE_DATAS"] = $datas;        
        
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
        $EXPTD = ["mn","vw","w","curl"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" && $v !== "''" ) )  {
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
        
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["oppic"] = $exists["pdacc_uppic"];
        
        $this->KDIn["datas"] = $in_datas;
    }

    public function on_process_in() {
        
        $this->GetArticles();
        
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