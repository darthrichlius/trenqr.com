<?php


class PHOTOTHEQUE extends MOTHER { 
    
    private $sections;
    private $filters;
    private $_DFLT_LMT;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->sections = [
            "MYLIFE_ALL","MYLIFE_REST","MYLIFE_STORY","MYLIFE_HOSTED",
            "TREND_ALL","TREND_MINE","TREND_FOLLOWED",
            "FAV_ALL","FAV_PUBLIC","FAV_PRIVATE"
        ];
        
        $this->filters = [
            "DEFAULT",
            "BY_DATE",
            "BY_LIKES",
            "BY_REATCIONS"
        ];
        
        $this->_DFLT_LMT = 12;
    }
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** MY_PHOTOS ****************************************************************************/
    
    public function photocount ($uid, $sec = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        if ( $sec && !in_array($sec, $this->sections) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( !$sec ) {
            $sec = "MYLIFE_ALL";
        }
        
        if ( $fil && !in_array($fil, $this->filters) ) {
            return "__ERR_VOL_MSM_RULES";
        } else {
            $fil = "DEFAULT";
        }
        
        $lmt = ( $lmt ) ? $lmt : $this->_DFLT_LMT;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$sec,$fil);
//        exit();
        
        switch ($sec) {
            case "MYLIFE_ALL" : 
                    $QO = new QUERY("qryl4photkn10");
                    $params = array (
                        ":uid"      => $uid,
                    );
                    
                break; 
            case "MYLIFE_REST" : 
                    $QO = new QUERY("qryl4photkn10_iml");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break; 
            case "MYLIFE_STORY" : 
                    $QO = new QUERY("qryl4photkn10_pod");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break;
            case "MYLIFE_HOSTED" : 
                    $QO = new QUERY("qryl4photkn10_hstd");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break;
            /******************************************/
            case "TREND_ALL" : 
                    $QO = new QUERY("qryl4photkn11");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break; 
            case "TREND_MINE" :
                    $QO = new QUERY("qryl4photkn11_mine");
                    $params = array (
                        ":uid1"      => $uid,
                        ":uid2"      => $uid,
                    );
                break; 
            case "TREND_FOLLOWED" : 
                    $QO = new QUERY("qryl4photkn11_sub");
                    $params = array (
                        ":uid1"      => $uid,
                        ":uid2"      => $uid,
                    );
                break;
            /******************************************/
            case "FAV_ALL" : 
                    $QO = new QUERY("qryl4photkn12");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break; 
            case "FAV_PUBLIC" : 
                    $QO = new QUERY("qryl4photkn12_pub");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break; 
            case "FAV_PRIVATE" :
                    $QO = new QUERY("qryl4photkn12_pri");
                    $params = array (
                        ":uid"      => $uid,
                    );
                break;
            default: 
                return;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return ( $datas ) ? $datas[0]["anb"] : 0;
        
    }
    
    public function phototheque ($uid, $sec = NULL, $dir = "FST", $fil = NULL, $lmt = NULL, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        if ( $sec && !in_array($sec, $this->sections) ) {
            return "__ERR_VOL_MSM_RULES";
        } else if ( !$sec ) {
            $sec = "MYLIFE_ALL";
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
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$sec,$dir,$fil,$lmt);
//        exit();
        
        switch ($sec) {
            case "MYLIFE_ALL" : 
                    $datas = $this->select_mylife_all($uid,$dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "MYLIFE_REST" : 
                    $datas = $this->select_mylife_restricted($uid,$dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "MYLIFE_STORY" : 
                    $datas = $this->select_mylife_stories($uid,$dir,$fil,$lmt,$_OPTIONS);
                break;
            case "MYLIFE_HOSTED" : 
                    $datas = $this->select_mylife_hosted($uid,$dir,$fil,$lmt,$_OPTIONS);
                break;
            /* ****************************************************************************** */
            case "TREND_ALL" : 
                    $datas = $this->select_trend_all($uid,$dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "TREND_MINE" :
                    $datas = $this->select_trend_mine($uid,$dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "TREND_FOLLOWED" : 
                    $datas = $this->select_trend_followed($uid,$dir,$fil,$lmt,$_OPTIONS);
                break;
            case "FAV_ALL" : 
                    $datas = $this->select_favorites_all($uid,$dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "FAV_PUBLIC" : 
                    $datas = $this->select_favorites_public($uid,$dir,$fil,$lmt,$_OPTIONS);
                break; 
            case "FAV_PRIVATE" :
                    $datas = $this->select_favorites_private($uid,$dir,$fil,$lmt,$_OPTIONS);
                break;
            default: 
                return;
        }
        
        if ( $datas && !empty($_OPTIONS["fe_mode"]) && $_OPTIONS["fe_mode"] ) {
            $final_datas = $this->femode($uid,$datas);
        } else {
            $final_datas = $datas;
        }
        
        return $final_datas;
        
    }
    
    private function femode ($uid,$datas) {
        
        $ART = new ARTICLE();
        $TRD = new TREND();
        $EV = new EVALUATION();
        
        $formatted = [];
        foreach ($datas as $k => $atab) {
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
//                "msg"       => html_entity_decode($atab["art_desc"]),
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
        
        return $formatted;
    }
    
    
    /*********************************** MY_LIFE ***********************************/
    
    private function select_mylife_all ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn1");
                        $params = array (
                            ":uid"      => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn1_btm");
                        $params = array (
                            ":uid"      => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_mylife_restricted ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn2");
                        $params = array (
                            ":uid"      => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn2_btm");
                        $params = array (
                            ":uid"      => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_mylife_stories ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn3");
                        $params = array (
                            ":uid"      => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn3_btm");
                        $params = array (
                            ":uid"      => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_mylife_hosted ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn13");
                        $params = array (
                            ":uid"      => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn13_btm");
                        $params = array (
                            ":uid"      => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    /*********************************** IN_TRENDS ***********************************/
    
    private function select_trend_all ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn4");
                        $params = array (
                            ":uid"      => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn4_btm");
                        $params = array (
                            ":uid"      => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_trend_mine ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn5");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn5_btm");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_trend_followed ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn6");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn6_btm");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":refid"    => $_OPTIONS["rfid"],
                            ":reftm"    => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$dir,$datas);
//        exit();
        
        return $datas;
    }
    
    /*********************************** IN_FAVOROTES ***********************************/
    
    private function select_favorites_all ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn7");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn7_btm");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":refid1"   => $_OPTIONS["rfid"],
                            ":refid2"   => $_OPTIONS["rfid"],
                            ":reftm1"   => $_OPTIONS["rftm"],
                            ":reftm2"   => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_favorites_public ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn8");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn8_btm");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":refid1"   => $_OPTIONS["rfid"],
                            ":refid2"   => $_OPTIONS["rfid"],
                            ":reftm1"   => $_OPTIONS["rftm"],
                            ":reftm2"   => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    private function select_favorites_private ($uid, $dir, $fil, $lmt, $_OPTIONS = NULL) {
        switch ($fil) {
            case "DEFAULT" :
            case "BY_DATE" :
                    if ( $dir === "FST" ) {
                        $QO = new QUERY("qryl4photkn9");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":limit"    => $lmt
                        );
                    } else if ( $dir === "BTM" ) {
                        $QO = new QUERY("qryl4photkn9_btm");
                        $params = array (
                            ":uid1"     => $uid,
                            ":uid2"     => $uid,
                            ":refid1"   => $_OPTIONS["rfid"],
                            ":refid2"   => $_OPTIONS["rfid"],
                            ":reftm1"   => $_OPTIONS["rftm"],
                            ":reftm2"   => $_OPTIONS["rftm"],
                            ":limit"    => $lmt
                        );
                    }
                break;
            case "BY_LIKES" :
                break;
            case "BY_REATCIONS" :
                break;
        }
        $datas = $QO->execute($params);
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$datas);
//        exit();
        
        return $datas;
    }
    
    /******************************************************************************************************************************************************************/
    /**************************************************************************** COLLECTIONS *************************************************************************/
    
}