<?php
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    $sector = "{wos/datx:sector}";
    
    $captivate_sgg_upsd = "{wos/datx:captivate_sgg_upsd}";
    $captivate_show = "{wos/datx:captivate_show}";
    $captivate_show = ( $captivate_show === '1' ) ? TRUE : FALSE;
//    var_dump($captivate_show,$captivate_sgg_upsd);
//    var_dump($pgvr,$sector);
//    exit();
    
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

<div s-id="TMLNR_GTPG_WLC">
    <span id="rez-pg" data-pg="tmlnr" class="this_hide"></span>
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
<!--    <div id="error_bar_max" class="this_hide">
        <p id="error_bar_txt_max">
            <span id="error_bar_text">
                Vous utilisez actuellement une version dite beta du site. Elle est succeptible de comporter plusieurs bogues. Pour plus d'informations, cliquez ici :  <a href='#'>Qu'est ce qu'une Beta</a>.
            </span>
        </p>
        <p id="error_bar_close">
            <a id="error_bar_close_a" href="#">Close</a>
        </p>
    </div>-->
    {wos/dvt:header_wu}
    <div id="page" class="">
        <div id="aside-bmx">
            <div id="aside" class="jb-aside">
                {wos/csam:pflbioplus}
<!--            <div id="user-socialprint-box">
                <div class="u-sp-sin-box">
                    <span class="u-sp-sin u-sp-sin-nb">{wos/datx:oucapital}</span>
                    <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_capital}</span>
                </div>
                <div class="u-sp-sin-box">
                    <span class="u-sp-sin u-sp-sin-nb">{wos/datx:oufolsnb}</span>
                    <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowers}</span>
                </div>
                <div class="u-sp-sin-box">
                    <span class="u-sp-sin u-sp-sin-nb">{wos/datx:oufolgsnb}</span>
                    <span class="u-sp-sin u-sp-sin-deco">{wos/deco:_AboFollowing}</span>
                </div>
            </div>
-->
<!--
            <div id="asR_wait_on_trade">
                Trenqr
            </div>
-->     
                {wos/csam:aside_rich} 
                {wos/dvt:asdr_w_removed} 
            </div>
        </div>
        <div id="page_left">

            <div id="p-l-center" class="wlc-sp-h">
                <div id="p-l-control-panel"> 
                    <p id="ps_hi"><span>{wos/datx:oupsd}</span></p>
<!--                    <form id ="wrap_inputFile" style="visibility:hidden">
                        <input id="kgb_click_inputfile" type="file" autocomplete="off" title=""/>
                    </form>-->
                    <a id="start_npostTr_process" data-mode="intr" href="javascript:;"></a>
                    <a id="start_npostMl_process" data-mode="inml" href="javascript:;"></a>
                </div> 
                <div id="p-l-c-main" class="tmlnr sp-mdl-acc wlc-sp-h jb-p-l-c-main">
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
                                <span id="p-h-b-upsd-psd">@{wos/datx:oupsd}</span>
                            </div>
                        </div>
                    </div>
                    <div id="acc-header" class="jb-acc-hdr">
                        <div id="acc-header-top" class="jb-acc-hdr-top">
<!--                            <a id="tmlnr-user-fol-fol" class="tmlnr-user-fol kgb_el_can_revs jb-tmlnr-ufol-chcs " data-action="follow" data-target="tmlnr-user-fol-ufol" href="">
                                <img id="tmlnr-u-f-img" src="{wos/sysdir:img_dir_uri}/r/Fol/fol.png"/>
                                {wos/deco:_Follow_me}
                            </a>-->
                            <div id="a-h-t-top" class="jb-a-h-t-top">
                                <div id="a-h-t-top-img-max" class="jb-a-h-t-top-img-mx">
                                    <?php 
                                        $cv_rp = "{wos/datx:oucover_rpath}";
                                       
                                        if ( !empty($cv_rp) ) :
                                            $cv_t = "{wos/datx:oucover_top}";
                                    ?> 
                                        <img id="a-h-t-top-img" class="a-h-t-top-img jb-a-h-t-top-img" height="{wos/datx:oucover_height}" width="840" style="top: <?php echo $cv_t; ?>" src="{wos/datx:oucover_rpath}" />
                                        <span id="a-h-t-top-fade" class="jb-a-h-t-top-fade"></span>
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
                                        <p class="acc-spec-stop" title="{wos/datx:oupostnb}&nbsp;{wos/deco:_related_posts}"><span class="jb-acc-spec-artnb">{wos/datx:oupostnb}</span><span class="acc-spec-stop-plus" title="{wos/datx:oupostnb}&nbsp;{wos/deco:_related_posts}"></span></p>
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
<!--    <div id="wos-console-output">
        <div id="wos-csl-opt-hdr">Application Output</div>
        <div id="wos-csl-opt-body">
            <div id="wos-csl-opt-steam" class="jb-wos-csl-opt-steam"></div>
        </div>
    </div>-->
    <div id="notify_event" class="this_hide">
        <p id="notify_event_err" class="this_hide">
            <span id="ua_ntfmsgerr" >An error occured ! <a href="javascript:;">Report</a></span><br/>
            Your action has probably been processed. The current error only seems to affect the notification system.
        </p>
    </div>
    {wos/csam:kxlib_dlg}    
    {wos/csam:unq}
    {wos/csam:cnxsgn_ovly}
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
    
    <?php if ( $captivate_show && $captivate_show === TRUE ) : ?>
    <div id="tqr-captv-sprt" class="jb-tqr-captv-sprt">
        <div id="tqr-captv-mx" class="jb-tqr-captv-mx">
            <?php 
                $t__ = "{wos/datx:oucover_top}";
                $t__ = intval(substr($t__, 0, -2));
                $nt__ = intval((235/260)*$t__)+3;
            ?>    
            <div id="tqr-captv-pano-bmx" style="background: url('{wos/datx:oucover_rpath}'); background-position: 0px <?php echo $nt__.'px'; ?>; background-repeat: no-repeat; background-size: 100% auto;">
                <div id="tqr-captv-pano-mx">
                    <div id="tqr-captv-pano-tle">Accédez à plus d'actualité en suivant ou devenant ami avec</div>
                    <div id="tqr-captv-pano-usrbx">
                        <div id="tqr-captv-pano-uppic-mx">
                            <a id="" class="">
                                <img id="tqr-captv-pano-uppic" width="80px" src="{wos/datx:ouppic}?v={wos/systx:now}"/>
                                <span id="tqr-captv-pano-fade"></span>
                            </a>
                        </div>
                        <div id="tqr-captv-pano-patro"><a id="" class="">{wos/datx:oufn} (@{wos/datx:oupsd})</a></div>
                    </div>
                    <div id="tqr-captv-pano-catch-mx">
                        <a class="tqr-captv-pano-catch" data-action="login" href="/login">Se connecter</a>
                        <a class="tqr-captv-pano-catch" data-action="signup" href="/signup">S'inscrire</a>
                    </div>
                </div>
            </div>
            <div id="tqr-captv-body-bmx">
                <div id="tqr-captv-opts">
                    <h1 id="tqr-captv-opts-intro">Vous pouvez aussi</h1>
                    <ul id="tqr-captv-opts-lst">
                        <li><a class="tqr-captv-opts-lst-opt" data-action href="/ontrenqr&v=1m30">Découvrir Trenqr en vidéo</a></li>
                        <li><a class="tqr-captv-opts-lst-opt" data-action href="/{wos/datx:captivate_sgg_upsd}">Visiter le compte d'un autre utilisateur</a></li>
                        <li><a class="tqr-captv-opts-lst-opt" data-action href="/!/recommend-trenqr-image-trend-cool-community">Recommander Trenqr à un ami</a></li>
                        <li><a class="tqr-captv-opts-lst-opt" data-action href="http://blog.trenqr.com" target="_blank">Aller sur Trenqr Blog</a></li>
<!--                        <li><a class="tqr-captv-opts-lst-opt" data-action href="">Faire des suggestions pour améliorer Trenqr</a></li>-->
                        <!--<li><a class="tqr-captv-opts-lst-opt" data-action href="">Nous signaler un dysfonctionnement</a></li>-->
                    </ul>
                </div>
                <div id="tqr-captv-ftr-mx">
                    <a id="tqr-captv-ftr-sltr" class="cursor-pointer jb-tqr-captv-ftr-sltr" data-action="close-sprt" role="button">Me redemander plus tard</a>
                </div>
            </div>
            
        </div>
    </div>
    <?php endif; ?>
    
    {wos/csam:tia2}
    
    {wos/csam:tkbvwr}
    
    {wos/csam:dontmiss}
    
    {wos/dvt:tqr_btm_lock}
    
<!--    <div id="wos-console-output">
        <div id="wos-csl-opt-hdr">Application Output</div>
        <div id="wos-csl-opt-body">
            <div id="wos-csl-opt-steam" class="jb-wos-csl-opt-steam"></div>
        </div>
    </div>-->

    <div id="tq-pg-env" class="jb-tq-pg-env this_hide">
        {
            "baseUrl"   : "{wos/sysdir:script_dir_uri}",
            "pageid"    : "{wos/datx:pagid}",
            "pgvr"      : "{wos/datx:pgakxver}",
            "sector"    : "{wos/datx:sector}",
            "ec_is_ecofirm"  : "",
            "ec_state"       : "",
            "ec_scope"       : "",
            "ec_is_ecofirm"  : ""
        }
    </div>
    
    <div id="requirejs">
        <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.tmlnr.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
    </div>

    <script>
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("js_declare"));
    </script>
     
</div>