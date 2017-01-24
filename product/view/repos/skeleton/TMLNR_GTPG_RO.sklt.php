<?php
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    $sector = "{wos/datx:sector}";
    
    /*
     * EMAIL_CONFIRMATION
     */
    $ec_is_ecofirm = "{wos/datx:ec_is_ecofirm}";
    $ec_state = "{wos/datx:ec_state}";
    $ec_scope = "{wos/datx:ec_scope}";
    $ec_is_ecofirm = ( $ec_is_ecofirm === '1' ) ? TRUE : FALSE;
    
    /*
     * PREFERENCES
     */
    $prefdcs = NULL;
    set_error_handler('exceptions_error_handler');
    try {
        $t = "{wos/datx:cuprefdcs}";
        //$t = NULL; //DEV, TEST, DEBUG
        $prefdcs = unserialize(base64_decode($t));
        
//        $_PFOP_BRAIN_ALWZ_OPN = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_BRAIN_ALWZ_OPN"] ) 
//                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"] && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"]["prfodtp_lib"] === "_DEC_ENA" ) 
//            ) ? TRUE : FALSE;
        
//        var_dump(__LINE__,$prefdcs["_PFOP_BRAIN_ALWZ_OPN"]["prfodtp_lib"],$_PFOP_BRAIN_ALWZ_OPN);
//        var_dump(__LINE__,$sector);
//        var_dump(__LINE__,$prefdcs);
//        var_dump(empty($prefdcs),is_array($prefdcs),count($prefdcs),key_exists("_PFOP_TIABT_INR",$prefdcs), strtoupper($prefdcs["_PFOP_TIABT_INR"]["prfodtp_lib"]), strtoupper($prefdcs["_PFOP_TIABT_INR"]["prfodtp_lib"]) === "_DEC_DSMA");
//        exit();
        restore_error_handler();

    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
            
?>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1492102444425463',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<script src="{wos/sysdir:script_dir_uri}/r/c.c/rbootloader.js"></script>
<div s-id="TMLNR_GTPG_RO" class="">
<!--    <span id="apparence_focues_on_center">
        POUR MODULE APPARENCE : Permet de se concenter sur le center.
        Il faudra régler le z-index de certains éléments pour les faire passer au dessous comme les logos et autres.
        Un e implémentation permet de se faire rapidement un avis

        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        background-color: rgba(0,0,0,0.4);
    </span>-->
    <span id="rez-pg" data-pg="tmlnr" class="this_hide"></span>
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
    <div id="stop_playin">
        <div id="stop_playin_wbackgr" class="this_hide">
            <div id="s_p_dialog">
                <p id="s_p_d_title" class="">Titre</p>
                <p id="s_p_d_msg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla pulvinar semper luctus. Pellentesque justo felis, placerat sed lacus non, tempus maximus lacus. Morbi at libero sit amet justo cras amet.</p>
                <div id="s_p_d_ch">
                    <a class="this_hide">Tourner la roue</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="uhome" href="javascript:;">Repartir vers mon compte</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="phome" href="javascript:;">Repartir l'accueil</a>
                    <a id="s_p_d_ch_valid" href="javascript:;">Ok</a>
                </div>
            </div>
        </div>
    </div>
    {wos/dvt:header_ro}
    <?php 
        if ( strtolower($sector) === "ml" ) :
    ?>
        {wos/csam:fstdvry}
    <?php endif; ?>
    
    <div id="tqr-mnfm-btm-infbx-bmx" class="jb-tqr-mnfm-btm-infbx-bmx this_hide">
        <div id="tqr-mnfm-btm-infbx-mx" class="jb-tqr-mnfm-btm-infbx-mx">
            <a id="tqr-mnfm-btm-infbx-clz" class="jb-tqr-mnfm-btm-infbx-clz" data-action="close" data-stylvr="white" role="button" href="javascript:;"></a>
            <div id="tqr-mnfm-btm-infbx-bdy" class="jb-tqr-mnfm-btm-infbx-bdy" data-stylvr="warning">
                <div id="tqr-mnfm-btm-infbx-msg" class="jb-tqr-mnfm-btm-infbx-msg"></div>
            </div>
            <div id="tqr-mnfm-btm-infbx-ftr" class="jb-tqr-mnfm-btm-infbx-ftr">
                <label id="tqr-mnfm-btm-infbx-opt-mx" class="jb-tqr-mnfm-btm-infbx-opt-mx">
                    <input id="tqr-mnfm-btm-infbx-opt-chkbx" class="jb-tqr-mnfm-btm-infbx-opt-chkbx" type="checkbox" />
                    <span id="tqr-mnfm-btm-infbx-opt-txt" class="jb-tqr-mnfm-btm-infbx-opt-txt">Ne plus me prévenir</span>
                </label>
                <a id="tqr-mnfm-btm-infbx-dec-tgr" class="jb-tqr-mnfm-btm-infbx-dec-tgr" data-action="close-with-caution" role="button" href="javascript:;">OK</a>
            </div>
        </div>
    </div>
    
    <?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
    <div id="page" class="jb-page">
        <div id="aside-bmx">
            <div id="aside" class="jb-aside">
                {wos/csam:pflbioplus}
<!--            <div id="asR_wait_on_trade">
                Trenqr
            </div>-->
                <div id="aside-mods" class="jb-aside-mods">
                    <div id="chbx-crnr-info-bmx" class="jb-chbx-crnr-if-bmx this_hide">
                        <a id="chbx-crnr-info-mx" class="cursor-pointer jb-chbx-crnr-if-mx" data-action="exc">
                            <span id="chbx-crnr-info-ico"></span>
                            <span id="chbx-crnr-info-nb" class="jb-chbx-crnr-if-nb"></span>
                        </a>
                    </div>
                    <a id="asd-apps-pin-btn" class="cursor-pointer jb-asd-apps-pin-btn" data-state='lock' title="Appuyez pour maintenir dans l'état" role="button"></a>
                    <div id="asd-apps-ch-mx" class="jb-asd-apps-ch-mx">
                        <div id="asd-apps-ch-illus-mx">
                            <i class="fa fa-glass asd-apps-ch-illus jb-asd-apps-ch-illus selected" data-scp="roombox"></i>
                            <!--<i class="fa fa-lightbulb-o asd-apps-ch-illus jb-asd-apps-ch-illus this_hide" data-scp="guidebox"></i>-->
                            <i class="fa fa-search asd-apps-ch-illus jb-asd-apps-ch-illus this_hide" data-scp="searchbox"></i>
                            <i class="fa fa-weixin asd-apps-ch-illus jb-asd-apps-ch-illus this_hide" data-scp="chatbox"></i>
                        </div>
                        <a class="asd-apps-chc cursor-pointer jb-asd-apps-chc selected" data-action="goroombox" title="RoomBox" role="button"></a>
                        <!--<a class="asd-apps-chc cursor-pointer jb-asd-apps-chc" data-action="goguidebox" title="GuideBox" role="button"></a>-->
                        <a class="asd-apps-chc cursor-pointer jb-asd-apps-chc" data-action="gosearchbox" title="SearchBox" role="button"></a>
                        <a class="asd-apps-chc cursor-pointer jb-asd-apps-chc" data-action="gochatbox" title="ChatBox" role="button"></a>
                    </div>
                    {wos/csam:asd_room}
                    {wos/csam:asd_search}
                    {wos/csam:asd_chbx}
                </div>
                {wos/csam:aside_rich} 
                {wos/dvt:legals_removed}
            </div>
        </div>
        <div id="page_left">
            <div id="p-l-center">
                <div id="p-l-control-panel"> 
                    <p id="ps_hi"><span>{wos/datx:oupsd}</span></p>
                    <form id ="wrap_inputFile" style="visibility:hidden">
                        <input id="kgb_click_inputfile" type="file" autocomplete="off" title=""/>
                    </form>
                    <a id="start_npostTr_process" href="javascript:;" data-mode="intr"></a>
                    <a id="start_npostMl_process" href="javascript:;" data-mode="inml"></a>
                </div> 
                <div id="p-l-c-main" class="tmlnr sp-mdl-acc jb-p-l-c-main">
                    <span id="toptop"></span>
                    <div id="pfl-hdr-max">
                        <div id="pfl-hdr-box">
                            <div id="p-h-b-uimg" class="p-hdr-elt jb-p-h-b-uimg" data-dm-status="0">
                                <div id="p-h-ui-err-panel" class="jb-h-ui-err-pan this_hide"></div>
                                <div id="p-h-ui-help-panel" class="jb-p-h-ui-hlp-pan this_hide">
                                    <a id="ppr-clz-trg" class="jb-kxlib-close" data-target="p-h-ui-help-panel" href="javascript:;">x</a>
                                    <h3>Image de profil</h3>
                                    <p>Votre image ne sera prise en compte qu'aux conditions suivantes&nbsp;:&nbsp;</p>
                                    <ul>
                                        <li>Type autorisé : <b>jpg</b>,<b>&nbsp;png</b></li>
                                        <li>Ne doit pas trop être trop grande (en taille et en poids).</li>
                                        <li>Ne doit pas trop être trop petite (en taille - min. 70px - et en poids).</li>
                                    </ul>
                                </div>
                                <div id="p-h-b-ui-trg-box" class="">
                                    <span id="p-h-b-ui-wt" class="jb-p-h-b-ui-wt this_hide"> {wos/deco:_Wait}... </span>
                                    <a id="p-h-b-ui-trg" class="jb-p-h-b-ui-trg" data-lk="0" data-dft="{wos/datx:ouppisdf}">
                                        <span id="p-h-b-ui-img-fade" class="jb-p-h-b-ui-i-fade"></span>
                                        <img id="p-h-b-ui-img" class="jb-p-h-b-ui-img" height="45" width="45" src="{wos/datx:ouppic}?v={wos/systx:now}"/>
                                    </a>
                                </div>
                                <div id="p-h-b-ui-action-box" class="jb-p-h-b-ui-act-box this_hide">
                                    <a id="" class="p-h-b-ui-action jb-p-h-b-ui-action" data-action="change" href="javascript:;" role="button">
                                        <span>{wos/deco:_Change}</span>
                                        <input id="p-h-b-ui-a-file" class="jb-p-h-b-ui-a-file" type="file" autocomplete="off" />
                                    </a>
                                    <a id="" class="p-h-b-ui-action jb-p-h-b-ui-action this_hide" data-action="delete" href="javascript:;" role="button">{wos/deco:_Delete}</a>
                                    <div id="p-h-b-ui-final-action" class="jb-p-h-b-ui-fnl-act this_hide">
                                        <div id="p-h-b-ui-f-a-title" class="jb-p-h-b-ui-f-a-tle">{wos/deco:_save}</div>
                                        <div id="p-h-b-ui-f-a-box">
                                            <a class="p-h-b-ui-f-a-ch jb-p-h-b-ui-f-a-ch" data-action="save" data-always="y" title="{wos/deco:_save}" alt="{wos/deco:_save_uppdpic_alt}" href="javascript:;" role="button">{wos/deco:_Yes}</a>
                                            <span>-</span>
                                            <a class="p-h-b-ui-f-a-ch jb-p-h-b-ui-f-a-ch" data-action="cancel" title="{wos/deco:_cancel}" alt="Annuler le changement de photo de profil" href="javascript:;" role="button">{wos/deco:_No}</a>
                                        </div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                            <div id="p-h-b-upsd" class="p-hdr-elt p-h-b-upsd-zm">
                                <span id="p-h-b-upsd-psd" class="jb-tmlnr-hdr-psd">@{wos/datx:oupsd}</span>
                            </div>
                        </div>
                    </div>
                    <div id="acc-header" class="jb-acc-hdr">
                        <div id="acc-header-top" class="jb-acc-hdr-top">
                            <?php 
                                if ( strtolower($sector) === "ml" ) :
                            ?>
                                <a id="tmlnr-hdr-homebox" class="jb-tmlnr-hdr-hmbx disable cursor-pointer" data-action="open-menu" role="button" >
                                    <img id="tmlnr-hdr-hb-lg" src="{wos/sysdir:img_dir_uri}/r/home.png" height="30" width="30"/>
                                    <span id="tmlnr-hdr-hb-txt">{wos/deco:_tmlnr_Home}</span>
                                </a>
                                <ul id="tmlnr-athm-mn-bmx" class="jb-tmlnr-athm-mn-bmx this_hide">
                                    <li class="tmlnr-athm-mn-mx jb-tmlnr-athm-mn-mx">
                                        <a class="tmlnr-athm-mn cursor-pointer jb-tmlnr-athm-mn" data-action="firstvisit" role="button" >Lancer la visite guidée</a>
                                    </li>
                                </ul>
                            <?php else : ?>
                                <a id="tmlnr-hdr-homebox" class="disable">
                                    <img id="tmlnr-hdr-hb-lg" src="{wos/sysdir:img_dir_uri}/r/home.png" height="30" width="30"/>
                                    <span id="tmlnr-hdr-hb-txt">{wos/deco:_tmlnr_Home}</span>
                                </a>
                            <?php endif; ?>
                            
<!--                            <span id="tmlnr-hdr-homebox" class="">
                                <img height="30" width="30" src="{wos/sysdir:img_dir_uri}/r/home.png"/>
                                <span id="tmlnr-hdr-hb-txt">{wos/deco:_tmlnr_Home}</span>
                            </span>-->
<!--                            <span id="tmlnr-urel-more-box" class="center jb-tmlnr-urel-m-box this_hide">
                                <a id="tmlnr-urel-m-mrbox" class=" jb-tmlnr-urel-m-mrbox jb-tmlnr-urel-chs" href="">
                                    <img id="tmlnr-urel-m-mlogo" class="css-tmlnr-urel-m-img" src="{wos/sysdir:img_dir_uri}/r/go_down.png" width="15" height="7" />
                                </a>
                                <a id="tmlnr-urel-m-frdbox" class="jb-tmlnr-urel-m-frdbox jb-tmlnr-urel-chs this_hide" href="">
                                    <img id="tmlnr-urel-m-frdlogo" class="css-tmlnr-urel-m-img"  width="36" height="16" src="{wos/sysdir:img_dir_uri}/r/frd-logo-n.png" />
                                </a>
                            </span>   
                            <div id="folw_btn_menus" class="jb-folw-btn-mns this_hide">
                                <a id="..." class="css-flb_menu kgb_el_can_revs jb-flb_menu jb-frd-action" data-action="friend" data-zr="friend" data-rev="unfriend" data-revs="Unfriend" data-zrrevs="Ask as a Friend" data-target="" href="">Ask as a Friend</a>
                            </div>
                            <!-- A Retirer OnLoad 
                            <div id="frd-hdr-errbox" class="jb-frd-hdr-errbox this_hide">
                                <div id="frd-h-ebx-icobox">
                                    <img class="frd-h-ebx-ico" src="{wos/sysdir:img_dir_uri}/r/block.png"/>
                                    <a id="frd-h-ebx-close" class="jb-kxlib-close" data-target="frd-hdr-errbox" href=""></a>
                                </div>
                                <div id="frd-h-ebx-msgbox">
                                    <span id="frd-h-ebx-msg" class="jb-frd-h-ebx-msg"></span>
                                </div>
                            </div>
                            -->
                            <div id="a-h-t-top" class="jb-a-h-t-top">
                                <span id="a-h-t-top-wait" class="jb-a-h-t-top-wait this_hide"></span>
                                <div id="cov-err-panel" class="jb-cov-err-pan this_hide">
                                    <div id="cov-err-pan-ctr" class="jb-cov-err-pan-ctr">
                                        <span id="cov-err-inner" class="jb-cov-err-inner"></span>
                                    </div>
                                </div>
                                <div id="a-h-t-top-img-max" class="jb-a-h-t-top-img-mx">
                                    <div id="tmlnr-cov-delconf-mx" class="jb-tmlnr-cov-delconf-mx this_hide"> 
                                        <div id="tmlnr-cov-dlcf-geninfo-mx" class="jb-tmlnr-cov-dlcf-gninf-mx this_hide">
                                            <span id="tmlnr-cov-dlcf-gninf" class="jb-tmlnr-cov-dlcf-gninf"></span>
                                        </div>
                                        <div id="tmlnr-cov-dlcf-wait-mx" class="jb-tmlnr-cov-dlcf-wait-mx this_hide">
                                            <span id="tmlnr-cov-dlcf-wait">Patientez ...</span><i class="fa fa-cog fa-spin"></i>
                                        </div>
                                        <div id="tmlnr-cov-dlcf-dc-mx" class="jb-tmlnr-cov-dlcf-dc-mx this_hide">
                                            <div id="tmlnr-cov-dlcf-dc-hdr">
                                                <span id="tmlnr-cov-dlcf-dc-tle">Voulez-vous vraiment revenir à l'image par défaut ?</span>
                                            </div>
                                            <div id="tmlnr-cov-dlcf-dc-bdy">
                                                <ul id="tmlnr-cov-dlcf-dc-dcs-mx">
                                                    <li class="tmlnr-cov-dlcf-dc-dc-mx"><a class="tmlnr-cov-dlcf-dc-dc-tgr jb-tmlnr-cov-dlcf-dc-dc-tgr" data-action="confirm_del_cover" href="javascript:;" role="button">Oui</a></li>
                                                    <li class="tmlnr-cov-dlcf-dc-dc-mx"><a class="tmlnr-cov-dlcf-dc-dc-tgr jb-tmlnr-cov-dlcf-dc-dc-tgr" data-action="abort_del_cover" href="javascript:;" role="button">Non</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        $cv_rp = "{wos/datx:oucover_rpath}";
                                       
                                        if ( !empty($cv_rp) ) :
                                            $cv_t = "{wos/datx:oucover_top}";
                                    ?>
                                        <img id="a-h-t-top-img" class="a-h-t-top-img jb-a-h-t-top-img" height="{wos/datx:oucover_height}" width="840" style="top: <?php echo $cv_t; ?>" src="{wos/datx:oucover_rpath}" />
                                        <span id="a-h-t-top-fade" class="jb-a-h-t-top-fade"></span>
                                        <div id="a-h-t-top-noimg-itrctv-mx" class="jb-a-h-t-t-noimg-i-mx this_hide">
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-sun-o" data-type="sun"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="1"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="2"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="3"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="4"></i>
                                            <span class="a-h-t-top-cvr-empty-elts" data-type="lilman"><img src="{wos/sysdir:img_dir_uri}/w/doodles/left_black.png" height="70"/></span>
                                        </div>
                                    <?php else : ?> 
                                        <div id="a-h-t-top-noimg-itrctv-mx">
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-sun-o" data-type="sun"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="1"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="2"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="3"></i>
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-cloud" data-type="cloud" data-pos="4"></i>
                                            <span class="a-h-t-top-cvr-empty-elts" data-type="lilman"><img src="{wos/sysdir:img_dir_uri}/w/doodles/left_black.png" height="70"/></span>
                                        </div>
                                        <!--<span id="a-h-t-top-noimg"></span>-->
                                    <?php endif; ?>
                                </div>
                                
                                <div id="a-h-t-top-fullname">
                                    <span id="a-h-t-top-fn-span">{wos/datx:oufn}</span>
                                </div>
<!--                                <div id="a-h-tfollu-max">
                                    <a href="#" id="a-h-tfollu" class="this_hide">
                                        <span class="badge-follu">{wos/deco:_FOLS_YOU}</span>
                                    </a>
                                </div>-->
                                <div id="c_c_start" class="jb-c-c-start">
                                    <!-- 
                                        * Pour éviter qu'on soit obliger de déclencher l'input avec un click ou un trigger qui aurait pu ...
                                        * ... être considérer comme pop up - OU - de voir apparaitre le texte par défaut "Aucun fichier sélectionner".
                                        * J'ai décidé d'utiliser les techniques suivantes : 
                                        *   (1) Utiliser une police de 50px pour faire grossir le bouton de l'input pour qu'il fit l'espace reservé au bouton visible par l'utilisateur
                                        *   (2) Faire qu'il n'y ait que le bouton de l'input qui soit atteignable par l'utilisateur car c'est le seul qui n'affiche pas le message
                                        *   (3) Faire passer l'input en Avant-plan pour que l'utilisateur clique dessus sans le voir ou le savoir
                                    -->
                                    <?php 
                                        if (! empty($cv_rp) ) :
                                    ?>
                                        <a id="chg-cvr-del-bx" class="jb-chg-cvr-del-bx this_hide" data-action="start_del_cover" href="javascript:;" title="Supprimer la bannière" role="button">
                                            <i class="fa fa-trash-o"></i> 
                                        </a>
                                    <?php endif; ?>
                                    <a id="change_cover" class="jb-chg-cvr this_hide" href="javascript:;" role="button">
                                        <i id="chg-cvr-lg" class="fa fa-camera"></i>
                                        <span id="chg-cvr-txt" >{wos/deco:_Change_Cover}</span>
                                        <input id="cover-file-input" class="jb-cvr-file-ipt" type="file" autocomplete="off" title=""/>
                                    </a>
                                </div>
                                <div id="c_c_saveMax" class="jb-cvr-fnl-chs-mx this_hide">
                                    <a id="change_cover_ccl" class="jb-cvr-fnl-chs" data-action="cancel" href="javascript:;">
                                        <span id="change_cover_ccl_inner">{wos/deco:_Cancel}</span>
                                    </a>
                                    <a id="change_cover_save" class="jb-cvr-fnl-chs" data-action="save" href="javascript:;">
                                        <span id="change_cover_save_inner">{wos/deco:_Save}</span>
                                    </a>
                                </div>
                            </div>
                            <div id="a-h-t-down">
                                <ul id="acc-hdr-mns-mx" class=""> 
                                    <li id="menu-li-abme">
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "abme" ) ? "menu-selected" : ""?>" href="/{wos/datx:oupsd}/apropos" data-target="aboutme">
                                            <span>Moi</span>
                                        </a>
                                    </li>
                                    <li id="menu-li-post" >
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "ml" ) ? "menu-selected" : ""?>" href="/{wos/datx:oupsd}" data-target="page">
<!--                                            <span class="menu-li-mn-xyz" data-shape="triangle"></span>
                                            <span class="menu-li-mn-xyz" data-shape="circle"></span>
                                            <span class="menu-li-mn-xyz" data-shape="square"></span>-->
<!--                                            <span>{wos/deco:_Posts}</span>-->
                                            <span>Ma vie</span>
                                        </a>
                                    </li>
                                    <li id="menu-li-fav">
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "fv" ) ? "menu-selected" : ""?>" href="/{wos/datx:oupsd}/favoris" data-target="favorite">
                                            <span>Mes Favoris</span>
                                        </a>
                                    </li>
                                    <li id="menu-li-tr">
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "tr" ) ? "menu-selected" : ""?>" href="/{wos/datx:oupsd}/tendances" data-target="trends">
                                            <span>Salons</span>
                                        </a>
                                    </li>
                                </ul>
                                <div id="acc-spec-loc" class="jb-acc-spec-loc">
                                    <p id="a-s-l-city">{wos/datx:oucity}</p>
                                    <p id="a-s-l-cn">{wos/datx:oucn_fn}</p>
                                </div>
                            </div>
                        </div>
                        <div id="acc-header-down" class="jb-acc-hdr-down">
                            <ul>
                                <li class="li-posts">
                                    <a >
                                        <p class="acc-spec-stop">
                                            <span class="jb-acc-spec-artnb" data-length="{wos/datx:oupostnb}">{wos/datx:oupostnb}</span>
                                            <span class="acc-spec-stop-plus"></span>
                                        </p>
                                        <p class="acc-spec-sdown" data-scp='posts'>{wos/deco:_Posts}</p>
                                    </a>
                                </li>
                                <li class="li-trends">
                                    <a >
                                        <p class="acc-spec-stop">
                                            <span class="jb-acc-spec-trnb" data-length="{wos/datx:outrnb}" title="Le nombre de Salons que j'ai créés">{wos/datx:outrnb}</span>
                                            <span class="acc-spec-stop-plus"></span>
                                            <span id="acc-spec-abotr-nb" class="jb-acc-spec-abotr-nb" data-length="{wos/datx:ouabtrnb}" title="Le nombre de Salons auxquelles je suis abonnés">{wos/datx:ouabtrnb}</span>
                                        </p>
                                        <p class="acc-spec-sdown" data-scp='trends'>
                                            <!--{wos/deco:_trends}-->
                                            Salons
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {wos/dvt:econfirm}
<!--                    <div>
                        TESTIMONY : Témoignage piné
                    </div>-->
                    <?php if ( strtolower($sector) === "ml" ) : ?>
                        {wos/dvt:pg_mypg}
                    <?php elseif ( strtolower($sector) === "tr" ) : ?>
                        {wos/dvt:pg_mytrds}
                    <?php elseif ( strtolower($sector) === "abme" ) : ?>
                        {wos/dvt:pg_aboutme}
                    <?php else : ?>
                        {wos/dvt:pg_myfavs}
                    <?php endif; ?>
                        
                    <?php if ( !( isset($art_exist) && $art_exist === FALSE ) && ( strtolower($sector) !== "abme" ) ) : ?>
                    <div class="tqr-page-loadm-box tmlnr jb-nwfd-loadm-box">
                        <span class="jb-tqr-hmt this_hide"></span>
                        <span class="tqr-page-loadm-spnr tmlnr jb-nwfd-loadm-spnr this_hide">
                            <img class="" width="32" height="32" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                        </span>
                        <a class="tqr-page-loadm-trg tmlnr jb-tmlnr-loadm-trg" data-v="l" data-scp="<?php echo strtolower($sector); ?>" href="javascript:;">{wos/deco:_Load_more}</a>
                    </div>
                    <?php endif; ?>
                        
<!--                    <div id="p-l-c-main-brand-ftr">
                        <p id="p-l-c-main-bd-ftr-text"><a id="p-l-c-main-bttf" href="">Trenqr</a><span id="p-l-c-m-ftr-yr">{wos/systx:fullyear}</span></p>
                    </div>-->
            </div>
        </div>
    </div>
    </div>
    <?php endif; ?>
        <section class="this_hide">
            Aside Discover
        </section>
    {wos/csam:kxlib_dlg}
    <!--
    <div id="error_bar_max" class="this_hide">
        <p id="error_bar_txt_max">
            <span id="error_bar_text">
                {wos/deco:_beta_warning}&nbsp;<a href='#'>{wos/deco:_beta_kezako}</a>.
            </span>
        </p>
        <p id="error_bar_close">
            <a id="error_bar_close_a" href="#">{wos/deco:_close}</a>
            <a id="error_bar_dsag_a" href="#">{wos/deco:_Dsma}</a>
        </p>
    </div>
    -->
    {wos/csam:bugzy}
    
    {wos/csam:notify_ua}
    {wos/csam:nwfd}
    {wos/csam:postman}
    <!-- A Retirer OnLoad -->
    {wos/csam:unq_ro}
    <!-- A Retirer OnLoad -->
    {wos/dvt:frdcenter}
    <!-- A Retirer OnLoad -->
    {wos/dvt:frdrules}
    <!--... ICI, (1) WRKR dit si c'est une beta (2) Sur toutes les pages-->
    {wos/dvt:tqr_betawrng}
    
    <section class="this_hide">
        <div>
            <div>
                
            </div>
        </div>
        Restez au contact de votre famille et de vos amis d'ailleurs. 
        De plus cela participe à l'effort collectif pour augmenter le nombre d'utilisateur et multiplier les opportunités et le dynasmise
    </section>
    
    <?php if ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) : ?>
    {wos/csam:email_confirm}  
    <?php endif; ?>
    
<!--    <div id="wos-console-output">
        <div id="wos-csl-opt-hdr">Application Output</div>
        <div id="wos-csl-opt-body">
            <div id="wos-csl-opt-steam" class="jb-wos-csl-opt-steam"></div>
        </div>
    </div>-->
</div>

{wos/csam:tia2}

{wos/csam:tkbvwr}

{wos/csam:nwfd_snitcher}

{wos/csam:rlc}

{wos/csam:expl_room}


<!-- 
    A RETIRER DE LA SOURCE POUR LES VERSIONS BETA
    PREVU TRENQR 1.0 
    ELEMENT DE DIFFERENCIATION POUR LE BZPLAN
-->
<div id="tiap-trigger-bx" class="jb-tiap-trigger-bx this_hide">
    <a id="tiap-trigger" class="cursor-pointer jb-tiap-trigger" >TIA</a>
</div>
{wos/csam:tia}

<?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
{wos/csam:shortcuts}
<?php endif; ?>

{wos/csam:skycrpr}

<!-- DO NOT REMOVE : LISTENERS DE LIAISON POUR CERTAINS CAS PARICULIER  -->
<span class="jb-tqr-lstnr-onev" data-scp="afv"></span>

<div id="tq-pg-env" class="jb-tq-pg-env this_hide">
    {
        "baseUrl"   : "{wos/sysdir:script_dir_uri}",
        "pageid"    : "{wos/datx:pagid}",
        "pgvr"      : "{wos/datx:pgakxver}",
        "sector"    : "{wos/datx:sector}",
        "ec_is_ecofirm"  : <?php echo ( $ec_is_ecofirm ) ? $ec_is_ecofirm : "false"; ?>,
        "ec_state"       : <?php echo ( $ec_state ) ? $ec_state : "false"; ?>,
        "ec_scope"       : <?php echo ( $ec_scope ) ? $ec_scope : "false"; ?>,
        "ec_is_ecofirm"  : <?php echo ( $ec_is_ecofirm === '1' ) ? 1 : 0; ?>
    }
</div>

<div id="requirejs">
    <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.tmlnr.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
</div>

<!--
    [DEPUIS 25-01-16]
        Retirer à chaque chargement
-->
