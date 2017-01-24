<?php
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    $sector = "{wos/datx:sector}";
    $urel = "{wos/datx:urel}";
    
    /*
     * EMAIL_CONFIRMATION
     */
    $ec_is_ecofirm = "{wos/datx:ec_is_ecofirm}";
    $ec_state = "{wos/datx:ec_state}";
    $ec_scope = "{wos/datx:ec_scope}";
    $ec_is_ecofirm = ( $ec_is_ecofirm === '1' ) ? TRUE : FALSE;
    
    $prefdcs = NULL;
    set_error_handler('exceptions_error_handler');
    try {
        $t = "{wos/datx:cuprefdcs}";
        //$t = NULL; //DEV, TEST, DEBUG
        $prefdcs = unserialize(base64_decode($t));

//        var_dump($prefdcs);
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
<div s-id="TMLNR_GTPG_RU">
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
    {wos/dvt:header_ro}
    <?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
    <div id="page" class="jb-page">
        <div id="aside-bmx">
            <div id="aside" class="jb-aside">
                {wos/csam:pflbioplus}
<!--            <div id="user-socialprint-box">
               <div class="u-sp-sin-box">
                    <span class="u-sp-sin u-sp-sin-nb jb-u-sp-cap-nb">{wos/datx:oucapital}</span>
                    <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_capital}</span>
                </div>
                <div class="u-sp-sin-box">
                    <span class="u-sp-sin u-sp-sin-nb jb-u-sp-flwr-nb">{wos/datx:oufolsnb}</span>
                    <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowers}</span>
                </div>
                <div class="u-sp-sin-box">
                    <span class="u-sp-sin u-sp-sin-nb jb-u-sp-flg-nb">{wos/datx:oufolgsnb}</span>
                    <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowing}</span>
                </div>
            </div>-->
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
                        <a class="asd-apps-chc cursor-pointer jb-asd-apps-chc selected" data-action="gosearchbox" title="SearchBox" role="button"></a>
                        <a class="asd-apps-chc cursor-pointer jb-asd-apps-chc" data-action="gochatbox" title="ChatBox" role="button"></a>
                    </div>
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
<!--                    <form id ="wrap_inputFile" style="visibility:hidden">
                        <input id="kgb_click_inputfile" type="file" autocomplete="off" title=""/>
                    </form>-->
                    <a href="javascript:;" id="start_npostTr_process" data-mode="intr"></a>
                    <a href="javascript:;" id="start_npostMl_process" data-mode="inml"></a>
                </div> 
                <div id="p-l-c-main" class="tmlnr sp-mdl-acc">
                    <span id="toptop"></span>
                    <div id="pfl-hdr-max">
                        <div id="pfl-hdr-box">
                            <div id="p-h-b-uimg" class="p-hdr-elt" data-dm-status="0">
                                <div id="p-h-b-ui-trg-box" class="">
                                    <a id="p-h-b-ui-trg" class="jb-p-h-b-ui-trg" data-lk="0">
                                        <span id="p-h-b-ui-img-fade" class="jb-p-h-b-ui-i-fade"></span>
                                        <img id="p-h-b-ui-img" class="jb-pfl-uppic-notro-img" data-isdefault="1" height="45" width="45" src="{wos/datx:ouppic}?v={wos/systx:now}" />
                                    </a>
                                </div>
                            </div>
                            <div id="p-h-b-upsd" class="p-hdr-elt p-h-b-upsd-zm">
                                <span id="p-h-b-upsd-psd" class="jb-tmlnr-hdr-psd">@{wos/datx:oupsd}</span>
                            </div>
                        </div>
                    </div>
                    <div id="acc-header">
                        <div id="acc-header-top" class="jb-tmlnr-hdr-top">
                            <!-- flscn : FullScreen, modèle plein écran -->
                            <span id="tmlnr-hdr-ldg-mx" class="flscn">
                                <img id="tmlnr-hdr-ldg" class="jb-fph-ldg this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                            </span>
                            
                            <?php 
                            $rl = "{wos/datx:urel}";
                            //FRiendCaSes
                            $frcs = ["xr03","xr13","xr23"];
                            //FoLlowCaSes
                            $flcs = ["xr11","xr02","xr12","xr22"];
//                            $flcs = ["xr01","xr11","xr21","xr02","xr12","xr22"]; //[02-12-14] Erroné
//                            var_dump($rl);
                            if ( !isset($rl) || $rl === "" || ( !in_array(strtolower($rl), $frcs) && ( !in_array(strtolower($rl), $flcs) ) ) ) :
                            ?>
                            <a id="tmlnr-user-fol-fol" class="tmlnr-user-fol cursor-pointer kgb_el_can_revs jb-tmlnr-ufol-chcs" data-action="follow" data-target="tmlnr-user-fol-ufol" data-obj="hdr" role="button">
                                <img id="tmlnr-u-f-img" src="{wos/sysdir:img_dir_uri}/r/fol.png"/>
                                {wos/deco:_Follow_me}
                            </a>
                            <a id="tmlnr-user-fol-ufol" class="tmlnr-user-fol cursor-pointer kgb_el_can_revs jb-tmlnr-ufol-chcs tmlnr-user-fol-wing this_hide" data-action="unfollow" data-target="tmlnr-user-fol-fol" data-obj="hdr" role="button">
                                <img id="tmlnr-u-uf-img" src="{wos/sysdir:img_dir_uri}/r/ufol.png"/>
                                {wos/deco:_Following}
                            </a>
                            <?php elseif ( isset($rl) && $rl !== "" && ( in_array(strtolower($rl), $flcs) || in_array(strtolower($rl), $frcs) ) ) : ?>
                            <a id="tmlnr-user-fol-fol" class="tmlnr-user-fol cursor-pointer kgb_el_can_revs jb-tmlnr-ufol-chcs this_hide" data-action="follow" data-target="tmlnr-user-fol-ufol" data-obj="hdr" role="button">
                                <img id="tmlnr-u-f-img" src="{wos/sysdir:img_dir_uri}/r/fol.png"/>
                                {wos/deco:_Follow_me}
                            </a>
                            <a id="tmlnr-user-fol-ufol" class="tmlnr-user-fol cursor-pointer kgb_el_can_revs jb-tmlnr-ufol-chcs tmlnr-user-fol-wing" data-action="unfollow" data-target="tmlnr-user-fol-fol" data-obj="hdr" role="button">
                                <img id="tmlnr-u-uf-img" src="{wos/sysdir:img_dir_uri}/r/ufol.png"/>
                                {wos/deco:_Following}
                            </a>
                            <?php else : ?>
                                <!-- 
                                    [NOTE 18-10-14] @author L.C.
                                    C'est normalement IMPOSSIBLE car on est dans RU.
                                    On le considère pour des raisons de fiabilité et de sécurité.
                                    En effet, aucun autre cas que les deux plus haut n'est NI attendu ou POSSIBLE.
                                -->
                            <?php endif; ?>
                            <span id="tmlnr-urel-more-box" class="center jb-tmlnr-urel-m-box">
                                <?php if ( isset($rl) && $rl !== "" && ( in_array(strtolower($rl), $flcs) ) ) : ?>
                                <a id="tmlnr-urel-m-mrbox" class=" jb-tmlnr-urel-m-mrbox jb-tmlnr-urel-chs" href="javascript:;">
                                    <img id="tmlnr-urel-m-mlogo" class="css-tmlnr-urel-m-img" src="{wos/sysdir:img_dir_uri}/r/go_down.png" width="15" height="7" />
                                </a>
                                <a id="tmlnr-urel-m-frdbox" class="jb-tmlnr-urel-m-frdbox jb-tmlnr-urel-chs this_hide" href="javascript:;">
                                    <img id="tmlnr-urel-m-frdlogo" class="css-tmlnr-urel-m-img"  width="36" height="16" src="{wos/sysdir:img_dir_uri}/r/frd-logo-n.png" />
                                </a>
                                <?php elseif ( isset($rl) && $rl !== "" && ( in_array(strtolower($rl), $frcs) ) ) : ?>
                                <a id="tmlnr-urel-m-mrbox" class=" jb-tmlnr-urel-m-mrbox jb-tmlnr-urel-chs this_hide" href="javascript:;">
                                    <img id="tmlnr-urel-m-mlogo" class="css-tmlnr-urel-m-img" src="{wos/sysdir:img_dir_uri}/r/go_down.png" width="15" height="7" />
                                </a>
                                <a id="tmlnr-urel-m-frdbox" class="jb-tmlnr-urel-m-frdbox jb-tmlnr-urel-chs" href="javascript:;">
                                    <img id="tmlnr-urel-m-frdlogo" class="css-tmlnr-urel-m-img"  width="36" height="16" src="{wos/sysdir:img_dir_uri}/r/frd-logo-n.png" />
                                </a>
                                <?php else : ?>
                                <a id="tmlnr-urel-m-mrbox" class=" jb-tmlnr-urel-m-mrbox jb-tmlnr-urel-chs this_hide" href="javascript:;">
                                    <img id="tmlnr-urel-m-mlogo" class="css-tmlnr-urel-m-img" src="{wos/sysdir:img_dir_uri}/r/go_down.png" width="15" height="7" />
                                </a>
                                <a id="tmlnr-urel-m-frdbox" class="jb-tmlnr-urel-m-frdbox jb-tmlnr-urel-chs this_hide" href="javascript:;">
                                    <img id="tmlnr-urel-m-frdlogo" class="css-tmlnr-urel-m-img"  width="36" height="16" src="{wos/sysdir:img_dir_uri}/r/frd-logo-n.png" />
                                </a>
                                <?php endif; ?>
                            </span>   
                            <div id="folw_btn_menus" class="jb-folw-btn-mns this_hide">
                                <?php if ( isset($rl) && $rl !== "" && ( in_array(strtolower($rl), $flcs) ) ) : ?>
                                <a class="css-flb_menu jb-flb-mn jb-frd-action" data-action="friend" data-zr="friend" data-rev="unfriend" data-revs="{wos/deco:_Go_unfriend}" data-zrrevs="{wos/deco:_GoFriend}" data-target="" href="javascript:;">{wos/deco:_GoFriend}</a>
                                <?php elseif ( isset($rl) && $rl !== "" && ( in_array(strtolower($rl), $frcs) ) ) : ?>
                                <a class="css-flb_menu jb-flb-mn jb-frd-action" data-action="unfriend" data-zr="friend" data-rev="friend" data-revs="{wos/deco:_Go_unfriend}" data-zrrevs="{wos/deco:_GoFriend}" data-target="" href="javascript:;">{wos/deco:_Go_unfriend}</a>
                                <a class="css-flb_menu jb-flb-mn jb-frd-action" data-action="frdmts-sprt-opn" data-target="" href="javascript:;">Demander une rencontre</a>
                                <!--<a class="css-flb_menu jb-flb-mn jb-frd-action" data-action="message" data-target="" href="javascript:;">Message</a>-->
                                <?php else : ?>
                                <a class="css-flb_menu jb-flb-mn jb-frd-action" data-action="friend" data-zr="friend" data-rev="unfriend" data-revs="{wos/deco:_Go_unfriend}" data-zrrevs="{wos/deco:_GoFriend}" data-target="" href="javascript:;">{wos/deco:_GoFriend}</a>
                                <?php endif; ?>
                            </div>
                            <!-- A Retirer OnLoad -->
                            <div id="frd-hdr-errbox" class="jb-frd-hdr-errbox this_hide">
                                <div id="frd-h-ebx-icobox">
                                    <img id="frd-h-ebx-ico" src="{wos/sysdir:img_dir_uri}/r/block.png"/>
                                </div>
                                <a id="frd-h-ebx-clz" class="jb-kxlib-close" data-target="frd-hdr-errbox" href="javascript:;">
                                    <img id="frd-h-ebx-clz-trg" src="{wos/sysdir:img_dir_uri}/r/crx-n.png" width="10" height="10" />
                                </a>
                                <div id="frd-h-ebx-msgbox">
                                    <span id="frd-h-ebx-msg" class="jb-frd-h-ebx-msg"></span>
                                </div>
                            </div>
                            <div id="a-h-t-top">
                                <div id="a-h-t-top-img-max">
                                    <?php 
                                        $cv_rp = "{wos/datx:oucover_rpath}";
                                       
                                        if ( !empty($cv_rp) ) :
                                            $cv_t = "{wos/datx:oucover_top}";
                                    ?>
                                        <img id="a-h-t-top-img" class="a-h-t-top-img " height="{wos/datx:oucover_height}" width="840" style="top: <?php echo $cv_t; ?>" src="{wos/datx:oucover_rpath}" />
                                        <span id="a-h-t-top-fade"></span>
                                    <?php else : ?> 
                                        <div id="a-h-t-top-noimg-itrctv-mx">
                                            <i class="a-h-t-top-cvr-empty-elts fa fa-sun-o" data-type="sun" data-pos=""></i>
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
                                <div id="a-h-tfollu-max">
                                    <?php 
                                        $rl2 = "{wos/datx:urel}";
                                        //Right cases
                                        $rtcs = ["xr21","xr02","xr12","xr22","xr03","xr13","xr23"];
                                        
                                        if ( isset($rl2) && $rl2 !== "" && in_array($rl2, $rtcs) ) : 
                                    ?>
                                            <a id="a-h-tfollu" class="" >
                                                <span class="tqr-bdg-flwsu">{wos/deco:_Fols_You}</span>
                                            </a> 
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div id="a-h-t-down">
                                <ul id="acc-hdr-mns-mx" class="">
                                    <li id="menu-li-abme">
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "abme" ) ? "menu-selected" : ""?>" href="/{wos/datx:oupsd}/apropos" data-target="aboutme">
                                            <span>Moi</span>
                                        </a>
                                    </li>
                                    <li id="menu-li-post">
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "ml" )? "menu-selected" : ""?>" href="/{wos/datx:oupsd}" data-target="page">
                                            <!--<span class="menu-li-mn-xyz" data-shape="triangle"></span>
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
                                        <a class="menu-li-menu <?php echo ( strtolower($sector) === "tr" )? "menu-selected" : ""?>" href="/{wos/datx:oupsd}/tendances" data-target="trends">
                                            <span>Salons</span>
                                        </a>
                                    </li>
                                </ul>
                                <div id="acc-spec-loc">
                                    <p id="a-s-l-city">{wos/datx:oucity}</p>
                                    <p id="a-s-l-cn">{wos/datx:oucn_fn}</p>
                                </div>
                            </div>
                        </div>
                        <div id="acc-header-down">
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
                                            <span class="jb-acc-spec-trnb" data-length="{wos/datx:outrnb}">{wos/datx:outrnb}</span>
                                            <span class="acc-spec-stop-plus"></span>
                                            <span id="acc-spec-abotr-nb" class="jb-acc-spec-abotr-nb" data-length="{wos/datx:ouabtrnb}">{wos/datx:ouabtrnb}</span>
                                        </p>
                                        <p class="acc-spec-sdown" data-scp='trends'>
                                            {wos/deco:_trends}
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <!--
                            <ul>
                                <li class="li-posts">
                                    <a >
                                        <p class="acc-spec-stop" title="{wos/datx:oupostnb}&nbsp;{wos/deco:_related_posts}">
                                            <span class="jb-acc-spec-artnb">{wos/datx:oupostnb}</span>
                                            <span class="acc-spec-stop-plus" title="{wos/datx:oupostnb}&nbsp;{wos/deco:_related_posts}"></span>
                                        </p>
                                        <p class="acc-spec-sdown blue" title="{wos/datx:oupostnb}&nbsp;{wos/deco:_related_posts}">{wos/deco:_Posts}</p>
                                    </a>
                                </li>
                                <li class="li-trends">
                                    <a >
                                        <p class="acc-spec-stop" title="{wos/datx:outrnb}&nbsp;{wos/deco:_related_trends}">{wos/datx:outrnb}</p>
                                        <p class="acc-spec-sdown purple" title="{wos/datx:outrnb}&nbsp;{wos/deco:_related_trends}">{wos/deco:_trends}</p>
                                    </a>
                                </li>
                            </ul>
                            -->
                        </div>

                    </div>
                    <?php 
                        if ( strtolower($sector) === "ml" ) :
                    ?>
                        {wos/dvt:pg_mypg}
                    <?php elseif ( strtolower($sector) === "tr" ) : ?>
                        {wos/dvt:pg_mytrds}
                    <?php else : ?>
                        {wos/dvt:pg_myfavs}
                    <?php endif; ?>
                        
                    <?php if (! ( isset($art_exist) && $art_exist === FALSE ) ) : ?>
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
    
    <div id="error_bar_max" class="this_hide">
        <p id="error_bar_txt_max">
            <span id="error_bar_text">
                {wos/deco:_beta_warning}&nbsp;<a href="javascript:;">{wos/deco:_beta_kezako}</a>.
            </span>
        </p>
        <p id="error_bar_close">
            <a id="error_bar_close_a" href="javascript:;">{wos/deco:_close}</a>
        </p>
    </div>
    {wos/csam:kxlib_dlg}
    {wos/csam:bugzy}
    
    {wos/csam:notify_ua}
    {wos/csam:nwfd}
    {wos/csam:postman}
    <!-- A Retirer OnLoad -->
    {wos/csam:unq_ru}
    <!-- A Retirer OnLoad -->
    {wos/dvt:frdcenter}
    <!-- A Retirer OnLoad -->
    {wos/dvt:frdrules}
    
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
    {wos/dvt:tqr_betawrng}
    
    <?php if ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) : ?>
    {wos/csam:email_confirm}  
    <?php endif; ?>
    
    <?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
    {wos/csam:shortcuts}
    <?php endif; ?>
    
    {wos/csam:nwfd_snitcher}
    
    {wos/csam:tia2}

    {wos/csam:tkbvwr}
    
    {wos/csam:rlc}
    
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
</div>