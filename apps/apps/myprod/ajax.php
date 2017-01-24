<?php 

/*
 * ETAPE : On charge l'environnement.
 */
require_once "../env/env.php";

$_AJAX_OPER = $_GET["oper"];

function Ajax_Return ( $key, $value, $no_stop = FALSE ) {
    if ( !isset($key) || $key === "" ) {
        return;
    }

    $key = (string) $key;

    echo json_encode([ $key => $value ]);

    if (! $no_stop ) {
        exit();
    }
}

function tqsta_pull_datas ($tstamp) {
    
    $TQ = new TRENQR();
    $datas = $TQ->tqsta_get_from($tstamp);
    
    return $datas;
    
}
        
function main ($_AX_ID) {
    switch ($_AX_ID) {
        case "TQSTA_PL_DATAS" :
            $EXPTD = ["fd","ft"];
                if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
                    Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
                }

                $in_datas = $_POST["datas"];
                $in_datas_keys = array_keys($_POST["datas"]);

                foreach ($in_datas_keys as $k => $v) {
                    if (!( isset($v) && $v != "" )) {
                        Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
                    }
                }
                
                $timestamp;
                if ( !empty($in_datas["fd"]) && empty($in_datas["ft"]) ) {
                    
                    $date = $in_datas["fd"];
                    $date = str_replace('/', '-', $date);
                    $in_datas["fd"] = date('Y-m-d', strtotime($date));

                    $fd = strtotime($in_datas["fd"])*1000;
                    
                    $timestamp = $fd;
                } else if ( !empty($in_datas["fd"]) && !empty($in_datas["ft"]) ) {
                    
                    $date = $in_datas["fd"];
                    $date = str_replace('/', '-', $date);
                    $in_datas["fd"] = date('Y-m-d', strtotime($date));
                    
                    $fd = strtotime($in_datas["fd"])*1000;
                    $ft = intval($in_datas["ft"])*3600*1000;
                    $fd += $ft;
                    
                    $timestamp = $fd;
                }
                
                $FE_DATAS;
                $return = tqsta_pull_datas($timestamp);
//                var_dump("LINE => ",__LINE__,"; DATAS => ",$return);
//                exit();
                if ( $return ) {
                    $FE_DATAS = [
                        /* ERRORS */
                        "stats_err_gen_bgzy_cn"     => $return["p_s_e_b_count"],
                        "stats_err_gen_auto_cn"     => $return["p_s_e_a_count"],
                        /* ACCOUNT */
                        "stats_gen_acc_cn"          => $return["p_s_acc_count"],
                        "stats_atv_acc_cn"          => $return["p_s_acc_atv_count"],
                        "stats_ded_acc_cn"          => $return["p_s_acc_dead_count"],
                        "stats_zmb_acc_cn"          => $return["p_s_acc_zmb_count"],
                        /* TRENDS */
                        "stats_gen_trd_cn"          => $return["p_s_trd_count"],
                        "stats_atv_trd_cn"          => $return["p_s_trd_atv_count"],
                        "stats_ded_trd_cn"          => $return["p_s_trd_dead_count"],
                        "stats_zmb_trd_cn"          => $return["p_s_trd_zmb_count"],
                        /* ACTIVITIES */
                        "stats_acty_gen_rct_cn"     => $return["p_s_rct_count"],
                        "stats_acty_gen_art_cn"     => $return["p_s_art_count"],
                        "stats_acty_ustg_rct_cn"    => $return["p_s_utg_rct_count"],
                        "stats_acty_ustg_art_cn"    => $return["p_s_utg_art_count"],
                        "stats_acty_gen_evl_cn"     => $return["p_s_evl_count"],
                        "stats_acty_evl_actv_cn"    => $return["p_s_evl_actv_count"],
                        "stats_acty_gen_mi"         => $return["p_s_mi_count"],
                        "stats_acty_mi_miorph"      => $return["p_s_mi_miorph_count"],
                        "stats_acty_gen_rel_cn"     => $return["p_s_rel_count"],
                        "stats_acty_rel_frds_cn"    => $return["p_s_rel_frds_count"],
                        "stats_acty_rel_sfol_cn"    => $return["p_s_rel_sfolw_count"],
                        "stats_acty_rel_dfol_cn"    => $return["p_s_rel_dfolw_count"],
                        "stats_acty_rel_void_cn"    => $return["p_s_rel_void_count"]
                    ];
                } else {
                    $FE_DATAS = NULL;
                }
                
                echo json_encode([ "return" => $FE_DATAS ]);
            break;
        case "TQSTA_PL_DATAS_TMSTP" :
                $EXPTD = ["tm"];
                if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
                    Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
                }

                $in_datas = $_POST["datas"];
                $in_datas_keys = array_keys($_POST["datas"]);

                foreach ($in_datas_keys as $k => $v) {
                    if (!( isset($v) && $v != "" )) {
                        Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
                    }
                }
                
                $timestamp = $in_datas["tm"];
                if (! date("Y-m-d H:m:s",($timestamp/1000)) ) {
                    echo json_encode([ "err" => "__ERR_VOL_FAILED" ]);
                }
                
                $FE_DATAS;
                $return = tqsta_pull_datas($timestamp);
//                var_dump("LINE => ",__LINE__,"; DATAS => ",$return);
//                exit();
                if ( $return ) {
                    $FE_DATAS = [
                        /* ERRORS */
                        "stats_err_gen_bgzy_cn"     => $return["p_s_e_b_count"],
                        "stats_err_gen_auto_cn"     => $return["p_s_e_a_count"],
                        /* ACCOUNT */
                        "stats_gen_acc_cn"          => $return["p_s_acc_count"],
                        "stats_atv_acc_cn"          => $return["p_s_acc_atv_count"],
                        "stats_ded_acc_cn"          => $return["p_s_acc_dead_count"],
                        "stats_zmb_acc_cn"          => $return["p_s_acc_zmb_count"],
                        /* TRENDS */
                        "stats_gen_trd_cn"          => $return["p_s_trd_count"],
                        "stats_atv_trd_cn"          => $return["p_s_trd_atv_count"],
                        "stats_ded_trd_cn"          => $return["p_s_trd_dead_count"],
                        "stats_zmb_trd_cn"          => $return["p_s_trd_zmb_count"],
                        /* ACTIVITIES */
                        "stats_acty_gen_rct_cn"     => $return["p_s_rct_count"],
                        "stats_acty_gen_art_cn"     => $return["p_s_art_count"],
                        "stats_acty_ustg_rct_cn"    => $return["p_s_utg_rct_count"],
                        "stats_acty_ustg_art_cn"    => $return["p_s_utg_art_count"],
                        "stats_acty_gen_evl_cn"     => $return["p_s_evl_count"],
                        "stats_acty_evl_actv_cn"     => $return["p_s_evl_actv_count"],
                        "stats_acty_gen_mi"         => $return["p_s_mi_count"],
                        "stats_acty_mi_miorph"      => $return["p_s_mi_miorph_count"],
                        "stats_acty_gen_rel_cn"     => $return["p_s_rel_count"],
                        "stats_acty_rel_frds_cn"    => $return["p_s_rel_frds_count"],
                        "stats_acty_rel_sfol_cn"    => $return["p_s_rel_sfolw_count"],
                        "stats_acty_rel_dfol_cn"    => $return["p_s_rel_dfolw_count"],
                        "stats_acty_rel_void_cn"    => $return["p_s_rel_void_count"]
                    ];
                } else {
                    $FE_DATAS = NULL;
                }
                
                echo json_encode([ "return" => $FE_DATAS ]);
            break;
        default:
            break;
    }
}

main($_AJAX_OPER);

?>