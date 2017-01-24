<?php


class EXPLORER extends MOTHER { 
    
    private $sections;
    private $filters;
    private $_DFLT_LMT;
    
    /* ALO */
    //Nombre (ou CAPITAL) de LIKES
    private $_DFLT_MUST_ART_LNB;
    //Nombre de REACTIONS
    private $_DFLT_MUST_ART_RNB;
    
    private $_DFLT_MUST_TST_LNB;
    private $_DFLT_MUST_TST_RNB;
    
    //Nombre de FOLLOWERS
    private $_DFLT_MUST_TRD_ABNB;
    //Nombre d'ARTICLES
    private $_DFLT_MUST_TRD_ARNB;
    
    private $_DFLT_DECOR_LMT;
    private $_DFLT_DECOR_MUST_LNB;
    private $_DFLT_DECOR_MUST_RNB;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->sections = [
            "SEC_TQR","SEC_TQR_ART","SEC_TQR_TST","SEC_TQR_TRD",
            "SEC_MYCNTY","SEC_MYCNTY_ART","SEC_MYCNTY_TST","SEC_MYCNTY_TRD",
            "SEC_MYLANG","SEC_MYLANG_ART","SEC_MYLANG_TST","SEC_MYLANG_TRD",
        ];
        $this->_DFLT_SEC = "SEC_TQR";
        
        $this->filters = [
            "DEFAULT",
            "BY_DATE",
            "BY_LIKES",
            "TODAY_BY_DATE",
            "TODAY_BY_LIKES",
        ];
        $this->_DFLT_FIL = "BY_DATE";
        
        $this->_DFLT_LMT = 9;
        
        /* ALO */
        $this->_DFLT_MUST_ART_LNB = 1;
        $this->_DFLT_MUST_ART_RNB = 1;
        $this->_DFLT_MUST_TST_LNB = 1;
        $this->_DFLT_MUST_TST_RNB = 1;
        $this->_DFLT_MUST_TRD_ABNB = 1;
        $this->_DFLT_MUST_TRD_ARNB = 1;
        
        $this->_DFLT_DECOR_LMT = 1;
        $this->_DFLT_DECOR_MUST_LNB = 1;
        $this->_DFLT_DECOR_MUST_RNB = 1;
    }
    
    
    /*************************************************************************************************************************************************************************/
    /************************************************************************** ALL PURPOSE SECTION **************************************************************************/
    
    public function explorer ($uid, $sec = NULL, $dir = "FST", $fil = NULL, $lmt = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        if ( $sec && !in_array($sec, $this->sections) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( !$sec ) {
            $sec = "SEC_TQR";
        }
        
        if ( $dir && !in_array($dir, ["FST","TOP","BTM"]) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if (! $dir ) {
            $dir = "FST";
        } else if ( in_array($dir, ["TOP","BTM"]) && !( $_OPTIONS && !empty($_OPTIONS["rfid"]) && !empty($_OPTIONS["rftm"]) ) ) {
            return "__ERR_VOL_MSM_RULES";
        }
        
        if ( $fil && !in_array($fil, $this->filters) ) {
            return "__ERR_VOL_MSM_RULES";
        } else {
            $fil = "DEFAULT";
        }
        
        $lmt = ( $lmt ) ? $lmt : $this->_DFLT_LMT;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$sec,$dir,$fil,$lmt,$_OPTIONS);
//        exit();
        
        switch ($sec) {
            case "SEC_TQR" :
                    $datas = $this->select_tqr_all($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_TQR_ART" :
                    $datas = $this->select_tqr_art($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_TQR_TST" :
                    $datas = $this->select_tqr_tst($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_TQR_TRD" :
                    $datas = $this->select_tqr_trd($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            /* Suugestions relatives à mon PAYS */
            case "SEC_MYCNTY" :
                    $datas = $this->select_mycnty_all($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_MYCNTY_ART" :
                    $datas = $this->select_mycnty_art($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_MYCNTY_TST" :
                    $datas = $this->select_mycnty_tst($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_MYCNTY_TRD" :
                    $datas = $this->select_mycnty_trd($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            /* Suugestions relatives à ma LANGUE */
            case "SEC_MYLANG" :
                    $datas = $this->select_mylang_all($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_MYLANG_ART" :
                    $datas = $this->select_mylang_art($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_MYLANG_TST" :
                    $datas = $this->select_mylang_tst($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            case "SEC_MYLANG_TRD" :
                    $datas = $this->select_mylang_trd($uid,$dir,$fil,$lmt,$_OPTIONS);
		break;
            default: 
                return;
        }
        
        if ( $datas && !empty($_OPTIONS["fe_mode"]) && $_OPTIONS["fe_mode"] ) {
            $final_datas = $this->femode($uid,$datas);
        } else {
            $final_datas = $datas;
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$final_datas);
//        exit();
        
        return $final_datas;
        
    }
    
    
    private function femode ($uid,$datas) {
        
        $ART = new ARTICLE();
        $TRD = new TREND();
        $EV = new EVALUATION();
        
        $final_datas = [];
        foreach ($datas as $k => $subdatas) {
            switch ($k) {
                case "XART" :
                        $PA = new PROD_ACC();
                    
                        $formatted = [];
                        foreach ($subdatas as $k => $atab) {
                            /*
                             * ETAPE :
                             *      Gestion du cas FAVORITE
                             */
                            $fvtab = $ART->Favorite_hasFavorite($uid, $atab["art_eid"],TRUE);
                             if ( $fvtab ) {
                                 $fvtp = $ART->Favorite_ConvertTypeID($fvtab["arfv_fvtid"]);
                            }
        
                            $ivid = ( $atab["art_vid_url"] ) ? TRUE : FALSE;
                            $formatted[] = [
                                "id"        => $atab["art_eid"],
                                "img"       => $atab["art_pic_rpath"],
                                "msg"       => $atab["art_desc"],
                                "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($atab["art_eid"],$ivid),
                                "time"      => $atab["art_crea_tstamp"], 
                                "eval"      => explode(",", $atab["art_evals"]),
                                "myel"      => $EV->getUserMyEval($atab["art_oid"],$atab["artid"]),
                                "evalu"     => null,
                                "hashs"     => (! isset($atab["art_hashs"]) ) ? "" : explode(",", $atab["art_hashs"]),
                                "ustgs"     => $ART->onread_AcquiereUsertags_Article($atab["art_eid"],TRUE),
                                "rnb"       => $atab["art_rnb"],
                                "vidu"      => $atab["art_vid_url"],
                                //HasAccessToReactions
                                "hatr"      => TRUE,
                                //TREND
                                "istrd"     => ( !empty($atab["art_trd_eid"]) ) ? TRUE : FALSE,
                                "trid"      => ( !empty($atab["art_trd_eid"]) ) ? $atab["art_trd_eid"] : null,
                                "trtitle"   => ( !empty($atab["art_trd_title"]) ) ? $atab["art_trd_title"] : null,
                                "trhref"    => ( !empty($atab["art_trd_eid"]) ) ? $TRD->on_read_build_trdhref($atab["art_trd_eid"],$atab["art_trd_title_href"]) : null,
                                //USER
                                "ueid"      => $atab["art_oeid"],
                                "ufn"       => $atab["art_ofn"],
                                "upsd"      => $atab["art_opsd"],
                                "uppic"     => $PA->onread_acquiere_pp_datas($atab["art_oid"])["pic_rpath"],
                                "uhref"     => "/".$atab["art_opsd"],
                                //FAVORITE
                                "hasfv"     => ( $fvtab ) ? TRUE : FALSE,
                                "fvtp"      => ( $fvtab ) ? $fvtp : null,
                                "fvtm"      => $fvtab["arfv_startdate_tstamp"],
                                "is_sod"    => $atab["art_is_sod"]
                            ];
                        }
                        $final_datas["XART"] = $formatted;
                    break;
                case "XTST" :
                        $TST = new TESTY();
                        $PA = new PROD_ACC();
                    
                        $formatted = [];
                        foreach ($subdatas as $k => $tst) {
                            
                            $tsotab = $PA->exists_with_id($tst["tst_ouid"]);
                            if (! $tsotab ) {
                                continue;
                            }
                            $tstgtab = $PA->exists_with_id($tst["tst_tguid"]);
                            $formatted[] = [
                                "i"         => $tst["tst_eid"],
                                "tm"        => $tst["tst_adddate_tstamp"],
                                "m"         => html_entity_decode($tst["tst_msg"]),
                                "au"        => [
                                    "oid"       => $tsotab["pdacc_eid"],
                                    "ofn"       => $tsotab["pdacc_ufn"],
                                    "opsd"      => $tsotab["pdacc_upsd"],
                                    "oppic"     => $PA->onread_acquiere_pp_datas($tsotab["pdaccid"])["pic_rpath"],
                                ],
                                "tg"        => [
                                    "oid"       => $tstgtab["pdacc_eid"],
                                    "ofn"       => $tstgtab["pdacc_ufn"],
                                    "opsd"      => $tstgtab["pdacc_upsd"],
                                    "oppic"     => $PA->onread_acquiere_pp_datas($tstgtab["pdaccid"])["pic_rpath"],
                                ],
                                /*
                                 * cdl : CanDelete
                                 *      (1) L'utilisateur connecté est le propriétaire
                                 *      (2) L'utilisateur connecté est la cible du message
                                 */
                                "cdl"       => ( floatval($uid) === floatval($tsotab["pdaccid"]) | floatval($uid) === floatval($tst["tst_tguid"]) ) ? TRUE : FALSE,
                                /*
                                 * QUESTION ? L'utilisateur peut-il accéder à la fonction de PIN ?
                                 * cgap : CanGetAccesstoPin
                                 */
                                "cgap"      => ( $uid && ( intval($uid) === intval($tsotab["pdaccid"]) || intval($uid) === intval($tst["tst_tguid"]) ) ) ? TRUE : FALSE,
                                //QUESTION ? Le TESTIMONY est-il PIN ?
                                "isp"       => $TST->Pin_IsPin($tst["tstid"]),
                                "rnb"       => $TST->React_Count($tst["tst_eid"]),
                                /*
                                 * QUESTION  : L'utilisateur peut-il accéder à la fonction de LIKE ?
                                 * NOTE :
                                 *      On vérifie seulement s'il est connecté car l'accès au bouton LIKE est conditionné à l'acces au TESTIMONY 
                                 */
                                "clk"       => ( $uid ) ? TRUE : FALSE,
                                //QUESTION ? L'utilisateur a t-il LIKE ?
                                "hslk"      => ( $uid && $TST->Like_HasLiked($uid, $tst["tstid"]) ) ? TRUE : FALSE,
                                //QUESTION ? Le nombre de LIKE ?
                                "cnlk"      => $TST->Like_Count($tst["tst_eid"]),
                                //Données sur les USERTAGS
                                "ustgs"     => $TST->onread_AcquiereUsertags_Testy($tst["tst_eid"],TRUE),
                                //Données sur les HASHTAGS
                                "hashs"     => $TST->onread_AcquiereHashs_Testy($tst["tst_eid"]),
                                /*
                                 * [DEPUIS 31-05-16]
                                 *      On récupère le lien permanent de TSM
                                 */
                                "prmlk"     => $_SERVER["HTTP_HOST"].$TST->onread_AcquierePrmlk($tst["tst_eid"]),
                            ];
                        }
                        $final_datas["XTST"] = $formatted;
                    break;
                case "XTRD" :
                        
                        $TH = new TEXTHANDLER();
                        
                        $formatted = [];
                        foreach ($subdatas as $k => $trtab) {
                            $tro_tab = $PA->exists_with_id($trtab["trd_owner"]);
                            $trcatg_text = $TH->get_deco_text("fr", "_NTR_CATG_".strtoupper($trtab["trd_catg"]));
                            $trd_cover = $TRD->onload_trend_get_trend_cover($trtab["trid"]);
                            $formatted[] = [
                                "trd_eid"       => $trtab["trd_eid"],
                                "trd_tle"       => $trtab["trd_title"],
                                "trd_desc"      => html_entity_decode($trtab["trd_desc"]),
                                "trd_href"      => $TRD->on_read_build_trdhref($trtab["trd_eid"], $trtab["trd_title_href"]),
                                "trd_posts_nb"  => $trtab["trd_arnb"],
                                "trd_abos_nb"   => $trtab["trd_abnb"],
                                "trd_time"      => $trtab["trd_date_tstamp"],
                                "tba"           => ( intval($trtab["trd_owner"]) === intval($uid) ) ? "mtrs" : "sbtrs",
                                "trd_catg"      => $trcatg_text,
                                /* COVER DATAS */
                                "trd_cov_w"     => ( $trd_cover ) ? $trd_cover["trcov_width"]."px" : NULL,
                                "trd_cov_h"     => ( $trd_cover ) ? $trd_cover["trcov_height"]."px" : NULL,
                                "trd_cov_t"     => ( $trd_cover ) ? $trd_cover["trcov_top"]."px" : NULL,
                                "trd_cov_rp"    => ( $trd_cover ) ? $trd_cover["pdpic_realpath"] : NULL,
                                /* OWNER DATAS */
                                "trd_oid"       => $tro_tab["pdacc_eid"],
                                "trd_ofn"       => $tro_tab["pdacc_ufn"],
                                "trd_opsd"      => $tro_tab["pdacc_upsd"],
                                "trd_ohref"     => "/".$tro_tab["pdacc_upsd"],
                                "trd_oppic"     => $PA->onread_acquiere_pp_datas($tro_tab["pdaccid"])["pic_rpath"],
                                /* FIRST ARTICLES IDS */
                                "trd_fartis"    => [],
                                /* TARGET DATAS*/
                                "trd_pofn"      => NULL,
                                "trd_popsd"     => NULL,
                                "trd_poctrib"   => $TRD->onread_usercontrib($uid,$trtab["trid"]),
                            ];
                        }
                        $final_datas["XTRD"] = $formatted;
                    break;
                default:
                    return;
            }
        }
        
        
        return $final_datas;
    }
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** ARTICLE SECTION ****************************************************************************/
     
    private function select_tqr_all ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        
        /*
         * [DEPUIS 19-06-16]
         *      Chaque section peut admettre une spécificité en ce qui concerne la limite en mode FST.
         */
        $art_custom_limit = ( $_OPTIONS && isset($_OPTIONS["art_custom_limit"]) && is_int($_OPTIONS["art_custom_limit"]) ) ?
            $_OPTIONS["art_custom_limit"] : $lmt;
        $tst_custom_limit = ( $_OPTIONS && isset($_OPTIONS["tst_custom_limit"]) && is_int($_OPTIONS["tst_custom_limit"]) ) ?
            $_OPTIONS["tst_custom_limit"] : $lmt;
        $trd_custom_limit = ( $_OPTIONS && isset($_OPTIONS["trd_custom_limit"]) && is_int($_OPTIONS["trd_custom_limit"]) ) ?
            $_OPTIONS["trd_custom_limit"] : $lmt;
        
        
        $must_art_lnb =( isset($_OPTIONS["must_art_lnb"]) ) ? $_OPTIONS["must_art_lnb"] : $this->_DFLT_MUST_ART_LNB;
        $must_art_rnb =( isset($_OPTIONS["must_art_rnb"]) ) ? $_OPTIONS["must_art_rnb"] : $this->_DFLT_MUST_ART_RNB;
        
        $must_tst_lnb =( isset($_OPTIONS["must_tst_lnb"]) ) ? $_OPTIONS["must_tst_lnb"] : $this->_DFLT_MUST_TST_LNB;
        $must_tst_rnb =( isset($_OPTIONS["must_tst_rnb"]) ) ? $_OPTIONS["must_tst_rnb"] : $this->_DFLT_MUST_TST_RNB;
        
        $must_trd_abnb =( isset($_OPTIONS["must_trd_abnb"]) ) ? $_OPTIONS["must_trd_abnb"] : $this->_DFLT_MUST_TRD_ABNB;
        $must_trd_arnb =( isset($_OPTIONS["must_trd_arnb"]) ) ? $_OPTIONS["must_trd_arnb"] : $this->_DFLT_MUST_TRD_ARNB;
        
        
        $final_datas = [];
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    /*
                     * ETAPE :
                     *      On récupère les données ARTICLE
                     */
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4explrn1_art");
                        $params = array (
                            ":must_lnb" => $must_art_lnb,
                            ":must_rnb" => $must_art_rnb,
                            ":limit"    => $art_custom_limit
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4explrn1_art_btm");
                        $params = array (
                            ":must_lnb" => $must_art_lnb,
                            ":must_rnb" => $must_art_rnb,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $art_custom_limit
                        );
                    }
                    $datas_art = $QO->execute($params);
                    $final_datas["XART"] = $datas_art;
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TESTY
                     */
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4explrn1_tst");
                        $params = array (
                            ":must_lnb" => $must_tst_lnb,
                            ":must_rnb" => $must_tst_rnb,
                            ":limit"    => $tst_custom_limit
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4explrn1_tst_btm");
                        $params = array (
                            ":must_lnb" => $must_tst_lnb,
                            ":must_rnb" => $must_tst_rnb,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $tst_custom_limit
                        );
                    }
                    $datas_tst = $QO->execute($params);
                    $final_datas["XTST"] = $datas_tst;
                    
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TREND
                     */
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4explrn1_trd");
                        $params = array (
                            ":must_abnb"    => $must_trd_abnb,
                            ":must_arnb"    => $must_trd_arnb,
                            ":limit"        => $trd_custom_limit
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4explrn1_trd_btm");
                        $params = array (
                            ":must_abnb"    => $must_trd_abnb,
                            ":must_arnb"    => $must_trd_arnb,
                            ":refid"        => $_OPTIONS["rfid"],
                            ":reftm"        => $_OPTIONS["rftm"],
                            ":limit"        => $trd_custom_limit
                        );
                    }
                    $datas_trd = $QO->execute($params);
                    $final_datas["XTRD"] = $datas_trd;
                    
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$final_datas);
//        exit();
        
        return $final_datas;
    }
    
    private function select_tqr_art ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        
        $must_art_lnb =( isset($_OPTIONS["must_art_lnb"]) ) ? $_OPTIONS["must_art_lnb"] : $this->_DFLT_MUST_ART_LNB;
        $must_art_rnb =( isset($_OPTIONS["must_art_rnb"]) ) ? $_OPTIONS["must_art_rnb"] : $this->_DFLT_MUST_ART_RNB;
        
        $final_datas = [];
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    /*
                     * ETAPE :
                     *      On récupère les données ARTICLE
                     */
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4explrn1_art");
                        $params = array (
                            ":must_lnb" => $must_art_lnb,
                            ":must_rnb" => $must_art_rnb,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4explrn1_art_btm");
                        $params = array (
                            ":must_lnb" => $must_art_lnb,
                            ":must_rnb" => $must_art_rnb,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                    $datas_art = $QO->execute($params);
                    $final_datas["XART"] = $datas_art;
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TESTY
                     */
                    $final_datas["XTST"] = [];
                    
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TREND
                     */
                    $final_datas["XTRD"] = [];
                    
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$final_datas);
//        exit();
        
        return $final_datas;
    }
    
    private function select_tqr_tst ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        
        $must_tst_lnb =( isset($_OPTIONS["must_tst_lnb"]) ) ? $_OPTIONS["must_tst_lnb"] : $this->_DFLT_MUST_TST_LNB;
        $must_tst_rnb =( isset($_OPTIONS["must_tst_rnb"]) ) ? $_OPTIONS["must_tst_rnb"] : $this->_DFLT_MUST_TST_RNB;
        
        $final_datas = [];
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    /*
                     * ETAPE :
                     *      On récupère les données ARTICLE
                     */
                    $final_datas["XART"] = [];
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TESTY
                     */
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4explrn1_tst");
                        $params = array (
                            ":must_lnb" => $must_tst_lnb,
                            ":must_rnb" => $must_tst_rnb,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4explrn1_tst_btm");
                        $params = array (
                            ":must_lnb" => $must_tst_lnb,
                            ":must_rnb" => $must_tst_rnb,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                    $datas_tst = $QO->execute($params);
                    $final_datas["XTST"] = $datas_tst;
                    
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TREND
                     */
                    $final_datas["XTRD"] = [];
                    
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$final_datas);
//        exit();
        
        return $final_datas;
    }
    
    private function select_tqr_trd ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        
        $must_trd_abnb =( isset($_OPTIONS["must_trd_abnb"]) ) ? $_OPTIONS["must_trd_abnb"] : $this->_DFLT_MUST_TRD_ABNB;
        $must_trd_arnb =( isset($_OPTIONS["must_trd_arnb"]) ) ? $_OPTIONS["must_trd_arnb"] : $this->_DFLT_MUST_TRD_ARNB;
        
        $final_datas = [];
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    /*
                     * ETAPE :
                     *      On récupère les données ARTICLE
                     */
                    $final_datas["XART"] = [];
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TESTY
                     */
                    $final_datas["XTST"] = [];
                    
                    /*
                     * ETAPE :
                     *      On récupère les données TREND
                     */
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4explrn1_trd");
                        $params = array (
                            ":must_abnb"    => $must_trd_abnb,
                            ":must_arnb"    => $must_trd_arnb,
                            ":limit"        => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4explrn1_trd_btm");
                        $params = array (
                            ":must_abnb"    => $must_trd_abnb,
                            ":must_arnb"    => $must_trd_arnb,
                            ":refid"        => $_OPTIONS["rfid"],
                            ":reftm"        => $_OPTIONS["rftm"],
                            ":limit"        => $lmt
                        );
                    }
                    $datas_trd = $QO->execute($params);
                    $final_datas["XTRD"] = $datas_trd;
                    
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$final_datas);
//        exit();
        
        return $final_datas;
    }
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** TESTIES SECTION ****************************************************************************/
     
    
    /*************************************************************************************************************************************************************************/
    /***************************************************************************** TRENDS SECTION ****************************************************************************/
    
    /*************************************************************************************************************************************************************************/
    /******************************************************************* COVER or DECORATIVE PHOTO SECTION *******************************************************************/
    
    
    public function GetDecoraPic ($cueid = NULL, $lmt = NULL, $_OPTIONS = NULL) {
        //cuid : L'identifiant externe de l'utilisateur connecté.
//        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cueid]);
        
        $lmt = ( $lmt ) ? : $this->_DFLT_DECOR_LMT;
        
        $must_lnb = ( isset($_OPTIONS["must_lnb"]) ) ? $_OPTIONS["must_lnb"] : $this->_DFLT_DECOR_MUST_LNB;
        $must_rnb = ( isset($_OPTIONS["must_rnb"]) ) ? $_OPTIONS["must_rnb"] : $this->_DFLT_DECOR_MUST_RNB;
        
        $cuid = NULL;
        if ( $cueid ) {
            $PA = new PROD_ACC();
            $utab = $PA->exists($cueid);
            if (! $utab ) {
                return "__ERR_VOL_U_G";
            } 
            $cuid = $utab["pdaccid"];
                    
            $QO = new QUERY("qryl4dscvrn1");
            $params = array (
                ":uid1"         => $cuid,
                ":uid2"         => $cuid,
                ":uid3"         => $cuid,
                ":must_lnb"     => $must_lnb,
                ":must_rnb"     => $must_rnb,
                ":limit"        => $lmt
            );
        } else {
            $QO = new QUERY("qryl4dscvrn2");
            $params = array (
                ":must_lnb"     => $must_lnb,
                ":must_rnb"     => $must_rnb,
                ":limit"        => $lmt
            );
        }           
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        $final_datas = $this->femode_simplified($datas,$cuid,$_OPTIONS);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$final_datas);
//        exit();
        
        return $final_datas;
    }

    private function femode_simplified ($datas, $uid = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$datas]);
        
        $ART = new ARTICLE();
        $TRD = new TREND();
        $EV = new EVALUATION();
        
        $formatted = [];
        foreach ($datas as $k => $atab) {
           /*
            * ETAPE :
            *      Gestion du cas FAVORITE
            */
            if ( $uid ) {
                $fvtab = $ART->Favorite_hasFavorite($uid, $atab["art_eid"],TRUE);
                if ( $fvtab ) {
                    $fvtp = $ART->Favorite_ConvertTypeID($fvtab["arfv_fvtid"]);
                }
            } else {
                $fvtab = NULL;
                $fvtp = NULL;
            }
            
            $ivid = ( $atab["art_vid_url"] ) ? TRUE : FALSE;
            if ( $_OPTIONS && $_OPTIONS["strict_mode"] === TRUE ) {
                $formatted[] = [
                    "img"       => $atab["art_pic_rpath"],
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($atab["art_eid"],$ivid),
                    //USER
                    "ueid"      => $atab["art_oeid"],
                    "ufn"       => $atab["art_ofn"],
                    "upsd"      => $atab["art_opsd"],
                    "uppic"     => $atab["art_oppic_rpath"],
                    "uhref"     => "/".$atab["art_opsd"],
                ];
            } else {
                $formatted[] = [
                    "id"        => $atab["art_eid"],
                    "img"       => $atab["art_pic_rpath"],
                    "msg"       => $atab["art_desc"],
                    "prmlk"     => $_SERVER["HTTP_HOST"].$ART->onread_AcquierePrmlk($atab["art_eid"],$ivid),
                    "time"      => $atab["art_crea_tstamp"], 
                    "eval"      => explode(",", $atab["art_evals"]),
                    "myel"      => $EV->getUserMyEval($atab["art_oid"],$atab["artid"]),
                    "evalu"     => null,
                    "hashs"     => (! isset($atab["art_hashs"]) ) ? "" : explode(",", $atab["art_hashs"]),
                    "ustgs"     => $ART->onread_AcquiereUsertags_Article($atab["art_eid"],TRUE),
                    "rnb"       => $atab["art_rnb"],
                    "vidu"      => $atab["art_vid_url"],
                    //HasAccessToReactions
                    "hatr"      => TRUE,
                    //TREND
                    "istrd"     => ( !empty($atab["art_trd_eid"]) ) ? TRUE : FALSE,
                    "trid"      => ( !empty($atab["art_trd_eid"]) ) ? $atab["art_trd_eid"] : null,
                    "trtitle"   => ( !empty($atab["art_trd_title"]) ) ? $atab["art_trd_title"] : null,
                    "trhref"    => ( !empty($atab["art_trd_eid"]) ) ? $TRD->on_read_build_trdhref($atab["art_trd_eid"],$atab["art_trd_title_href"]) : null,
                    //USER
                    "ueid"      => $atab["art_oeid"],
                    "ufn"       => $atab["art_ofn"],
                    "upsd"      => $atab["art_opsd"],
                    "uppic"     => $atab["art_oppic_rpath"],
                    "uhref"     => "/".$atab["art_opsd"],
                    //FAVORITE
                    "hasfv"     => ( $fvtab ) ? TRUE : FALSE,
                    "fvtp"      => ( $fvtab ) ? $fvtp : null,
                    "fvtm"      => $fvtab["arfv_startdate_tstamp"],
                    "is_sod"    => $atab["art_is_sod"]
                ];
            }
        }
        
        return $formatted;
    }
    
}