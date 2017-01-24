<script src="{wos/sysdir:script_dir_uri}/r/c.c/rbootloader.js"></script>
<?php
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    $trstate = "{wos/datx:trstate}";
    $trstate_tm = "{wos/datx:trstate_tm}";
    $dy_ = "{wos/deco:_days}";
    $hy_ = "{wos/deco:_hours}";
    $my_ = "{wos/deco:_minutes}";
    
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

<div s-id="TRPG_GTPG_RU">
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
    <div id="stop_playin">
        <div id="stop_playin_wbackgr" class="this_hide">
            <div id="s_p_dialog">
                <p id="s_p_d_title" class="this_hide">Titre</p>
                <p id="s_p_d_msg"></p>
                <div id="s_p_d_ch">
                    <a class="this_hide">Tourner la roue</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="uhome" href="javascript:;">Repartir vers mon compte</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="phome" href="javascript:;">Repartir l'accueil</a>
                    <a id="s_p_d_ch_valid" href="javascript:;">Ok</a>
                </div>
            </div>
        </div>
    </div>
<!--    <div id="warning_bar_max" class="this_hide">
        <p id="warning_bar_txt_max">
            <span id="warning_bar_text">
                Vous utilisez actuellement une version dite <b><q>beta</q></b> du site. Elle est succeptible de comporter plusieurs bogues. Pour plus d'informations, cliquez ici :  <a href='#'>Qu'est ce qu'une Beta</a>.
            </span>
        </p>
        <p id="warning_bar_close">
            <a id="warning_bar_close_a" href="javascript:;">Close</a>
            <a id="warning_bar_dsag_a" href="javascript:;">Ne plus afficher</a>
        </p>
    </div>-->
    
    {wos/csam:bugzy}
    {wos/csam:notify_ua}
    <div id="trpg-ovly-max" class="this_hide">
        <div id="trpg-ovly-backgr">
            {wos/dvt:trpg_ovly_create}
            {wos/dvt:trpg_ovly_edit}
        </div>
    </div>
    {wos/csam:nwfd}
    {wos/csam:postman}
    {wos/csam:kxlib_dlg} <!-- [DEPUIS 09-11-15] -->
    <!-- A Retirer OnLoad -->
    {wos/csam:unq_ro}
    <!-- A Retirer OnLoad -->
    {wos/dvt:frdcenter}
    {wos/dvt:header_ro}
    
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
        {wos/dvt:bigfail}
        <div id="page-components" class="">
            <div id="aside-bmx">
                <div id="aside" class="jb-aside">
                    {wos/dvt:cockpit}
<!--                <div id="asR_wait_on_trade" class="this_hide">
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
                        <!--<p id="ps_hi"><span>@IamLouCarther</span></p>-->
                    </div> 
                    <div id="p-l-c-main" class="trpg sp-mdl-acc">

                        <!-- "p-l-c-main-std" permet de hide tous les contenus. Utile pour afficher les erreurs --> 
                        <div id='p-l-c-main-std'>
                            <!-- do0 indique au gestionnaire de quand même déclarer la valeur 0. Seules do suivi de 0 est autorisé. -->
                            <div id="err_bar_in_trpg" class="kxlib-dflt-error-bar error_bar_vtop_max this_hide" data-wloc="0,do0,0,do0,4" data-eref="">
                                <h3>Erreur</h3>
                                <p class="e-b-vtop-msg">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Aenean placerat id dolor sed hendrerit. Cras sapien tellus, blandit id sagittis ac, euismod vel sem. 
                                    Suspendisse quis malesuada arcu.
                                </p>
                                <div class="e-b-vtop-err-max this_hide">
                                    <a class="err-bar-close-trig jsbind-close-trig" data-tar="err_bar_in_trpg" href="javascript:;">Fermer</a>
                                </div>
                                <div class="e-b-vtop-list-err this_hide">
                                    <div class="e-b-v-l-e-max">
                                        <span class="e-b-v-l-e-nb">0</span>
                                        <span class="e-b-v-l-e-txt"> Error(s)</span>
                                    </div>
                                </div>
                            </div>
                            <!-- ONLY FOR ERROR_STACK DEBUG -->
                            <div id="list-id-esk">
                                <h3>ERROR STACK</h3>
                                <div>

                                </div>
                            </div>
                            <span id="toptop"></span>
                            <div id="main-header" class="">
                                <!-- [DEPUIS 21-11-15] -->
                                <span id="tr-h-t-d-ctg">{wos/datx:trcat_text}</span>
                                
<!--                                <ul id="main-header-ul">
                                    <li id="main-header-li-right">

                                    </li>
                                    <li id="main-header-li-left">
                                    </li>
                                </ul>-->
                            </div>
                            <div id="tr-header">
                                {wos/csam:trpg_warning}
                                <div id="tr-header-top">
                                    <!-- tao : Trend AbO Snitcher -->
                                    <span class="jb-tao-sn"></span>
                                    <img id="trpg_abo_ldg" class="jb-trpg_abo_ldg this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                                    <?php 
                                        $x = "{wos/datx:cuisabo}";
                                        if ( $x ) :
                                    ?>
                                    <a id="trpg_getctd_btn" class="fr trpg-hdr-action cursor-pointer jb-trpg_getctd_btn jb-trpg-action this_hide" data-action="co" title="" role="button">
                                        <img id="trpg_getctd_btn_lg" class="fr" src="{wos/sysdir:img_dir_uri}/r/plus.png" width="15" height="15"/>
                                        <span>S'abonner</span>
                                    </a>
                                    <a id="trpg_cntd_bdg" class="fr trpg-hdr-action cursor-pointer jb-trpg_cntd_bdg jb-trpg-action" data-action="disco" title="" role="button">
                                        <img id="trpg_cntd_bdg_lg" class="fr" src="{wos/sysdir:img_dir_uri}/r/tr_cntd.png" width="24" height="24"/>
                                        <span>Connecté</span>
                                    </a>
                                    <?php else : ?>
                                    <a id="trpg_getctd_btn" class="fr trpg-hdr-action cursor-pointer jb-trpg_getctd_btn jb-trpg-action" data-action="co" title="" role="button">
                                        <img id="trpg_getctd_btn_lg" class="fr" src="{wos/sysdir:img_dir_uri}/r/plus.png" width="15" height="15"/>
                                        <span>S'abonner</span>
                                    </a>
                                    <a id="trpg_cntd_bdg" class="fr trpg-hdr-action cursor-pointer jb-trpg_cntd_bdg jb-trpg-action this_hide" data-action="disco" title="" role="button">
                                        <img id="trpg_cntd_bdg_lg" class="fr" src="{wos/sysdir:img_dir_uri}/r/tr_cntd.png" width="24" height="24"/>
                                        <span>Connecté</span>
                                    </a>
                                    <?php endif; ?>
                                    <div id="a-h-t-top-tr" class="resz-tr-hdr-hir">
                                        <div id="cov-err-panel" class="jb-cov-err-pan this_hide">
                                            <div id="cov-err-pan-ctr" class="jb-cov-err-pan-ctr">
                                                <span id="cov-err-inner" class="jb-cov-err-inner">
                                                </span>
                                            </div>
                                        </div>
                                        <div id="a-h-t-top-tr-img-max">
                                            <?php 
                                                $cv_rp = "{wos/datx:trcov_rp}";

                                                if ( !empty($cv_rp) ) :
                                                    $cv_t = "{wos/datx:trcov_t}";
                                            ?>
                                                <img id="a-h-t-top-tr-img-img" class="a-h-t-top-img jb-a-h-t-top-img" height="{wos/datx:trcov_h}" width="{wos/datx:trcov_w}" style="top: <?php echo $cv_t; ?>" src="{wos/datx:trcov_rp}" />
                                            <?php else : ?> 
                                                <!--<span id="trcov-noimg"></span>-->
                                                <span id="trpg-cov-none">
                                                    <!--<img src="{wos/sysdir:img_dir_uri}/r/3pt-w.png" />-->
                                                </span>
                                            <?php endif; ?>
                                            <!--<img id="a-h-t-top-tr-img-img" src="{wos/datx:trcover}"/>-->
                                        </div>
                                        <span id="a-h-t-top-fade" class="tr-header-fade"></span>
                                        <h1 id="a-h-t-top-tr-title" class="jb-a-h-t-top-tr-tle {wos/datx:trtitle_lgt}" data-maxln="100" spellcheck="false">{wos/datx:trtitle}</h1>
                                        <div id="a-h-t-top-anim_act" class="">
                                            <a id="tr-h-an-hi" class="tr-hdr-an-btn tr-hdr-an-full" href="javascript:;"></a>
                                            <a id="tr-h-an-sm" class="tr-hdr-an-btn tr-hdr-an-btn_r" href="javascript:;"></a>
                                        </div>
                                        <p id="a-h-t-top-tr-desc" class="jb-a-h-t-top-tr-desc" data-maxln="200" spellcheck="false">{wos/datx:trdesc}</p>
                                        <span class="a-h-t-top-tr-desc-alt this_hide">{wos/deco:_trpg_Desc_ph}</span>
                                        <!-- ttr : ThisTRend -->
                                        <span class="jb-ttr-cache this_hide" data-c="{wos/datx:trcat}" data-p="{wos/datx:trpart},{wos/datx:trpart_lib}"></span>
                                    </div>
                                    <div id="tr-h-t-down">
                                        <ul>
                                            <li id="tr-h-t-d-1">
                                                <ul id="tr-h-t-d-1-ul" class="">
                                                    <li class="tr-h-t-d-10">
                                                        <p id="tr-h-t-d-10-psd" >
                                                            <a id="tr-h-t-d-10-psdpsd" class="jb-trpg-trd-owr" data-cache="[{wos/datx:oueid},{wos/datx:oufn},{wos/datx:oupsd}]" href="{wos/datx:ouhref}" title="{wos/datx:oufn}">
                                                                <span id='tr-h-t-d-10-psdi-fade'></span>
                                                                <img id="tr-h-t-d-10-psdimg" height="45" width="45" src="{wos/datx:ouppic}" />
                                                                <span>@{wos/datx:oupsd}</span>
                                                            </a>
                                                        </p>
                                                    </li>
                                                    <li class="tr-h-t-d-11"></li>
                                                </ul>
                                                <div id="chcov_choices" class="this_hide">
                                                    <a id="c_c_canc" class="chcov_choices_btn" href="javascript:;">Annuler</a>       
                                                    <a id="c_c_sauv" class="chcov_choices_btn" href="javascript:;">Sauvegarder</a>       
                                                </div>
                                            </li>
                                            <li id="tr-h-t-d-2">
                                                <!--<p id="tr-h-t-d-20" data-length="-1">260<span class="tr-h-t-d-xx1"> 978</span><span class="tr-h-t-d-xx2"></span></p>-->
                                                <p id="tr-h-t-d-20" data-length="{wos/datx:trnbposts}" title="">{wos/datx:trnbposts}</p>
                                                <p id="tr-h-t-d-21" >{wos/deco:_Posts}</p>
                                            </li>
                                            <li id="tr-h-t-d-3">
                                                <p id="tr-h-t-d-30" data-length="{wos/datx:trfolws}" >{wos/datx:trfolws}</p>
                                                <p id="tr-h-t-d-31">{wos/deco:_AboFollowers}</p>
                                            </li>
                                            <li class="tr-h-t-d-4 access_large">
                                                <!--<p class="tr-h-t-d-40"><span class="flag-blue">FR</span><span class="flag-white">AN</span><span class="flag-red">CE</span></p>-->
                                                <p class="tr-h-t-d-40">
                                                    <?php $x = "{wos/datx:trpart}";  if ( isset($x) && $x == "{wos/deco:_part_pub_code}" ) : ?>
                                                        <i class="fa fa-unlock-alt jb-trpg-cov-part-lg unlock"></i>
                                                        <!--<img height="15" width="15" src="{wos/sysdir:img_dir_uri}/r/ulock_b.png"/>-->
                                                        <span id="trpg_cov_part">{wos/deco:_Public}</span>
                                                    <?php elseif  ( isset($x) && $x == "{wos/deco:_part_pri_code}" ) : ?>
                                                        <i class="fa fa-lock jb-trpg-cov-part-lg lock"></i>
                                                        <!--<img height="15" width="15" src="{wos/sysdir:img_dir_uri}/r/lockr.png"/>--> 
                                                        <span id="trpg_cov_part">{wos/deco:_Private}</span>
                                                    <?php endif; ?>
                                                </p>
                                            </li>
                                        </ul>
                                        <span id="tr-h-t-d-10-time" class='kxlib_tgspy' data-tgs-crd='{wos/datx:trcrea}' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                            <span class='tgs-frm'></span>
                                            <span class='tgs-val'></span>
                                            <span class='tgs-uni'></span>
                                        </span>   
                                    </div>
                                </div>

                            </div>
                            <div class="folw-preview">
                                <div id="folw-prvw-bdy">
                                    <?php 
                                        $x;
                                        try {
                                            $x = unserialize(html_entity_decode("{wos/datx:trend_vip}"));

                                        } catch (Exception $exc) {
                                            //TODO : Déclencher une erreur fatale
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) == 3 ) ) : 
                                    ?>
                                    <p id="tr_home_whoFoll">
                                        {wos/deco:_trpg_Vip}&nbsp;
                                        <?php if ( !empty($x[0]["upsd"]) && !empty($x[0]["uhref"]) ) : ?><a href="<?php echo $x[0]["uhref"] ?>" class="psd-in-tr-home"><?php echo "@".$x[0]["upsd"] ?></a><?php endif; ?>
                                        <?php if ( !empty($x[1]["upsd"]) && !empty($x[1]["uhref"]) ) : ?>,&nbsp;<a href="<?php echo $x[1]["uhref"] ?>" class="psd-in-tr-home"><?php echo "@".$x[1]["upsd"] ?></a><?php endif; ?>
                                        <?php if ( !empty($x[2]["upsd"]) && !empty($x[2]["uhref"]) ) : ?>&nbsp;{wos/deco:_and}&nbsp;<a href="<?php echo $x[2]["uhref"] ?>" class="psd-in-tr-home"><?php echo "@".$x[2]["upsd"] ?></a>.<?php endif; ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                    /* 
                                     * On controlle si le choix en présence fait partie de trgrat_choices si des choix sont disponibles.
                                     */
                                    $l = "{wos/datx:trgrat_choices}";
                                    $x = intval("{wos/datx:trgrat}");
                                    $c = true;
                                    
                                    if ( !empty($c) ) {
                                        $l = explode(',', $l);
                                        $c = ( in_array($x, $l) ) ? true : false;
                                    }
                                        
                                    if ( !empty($x) && $x > 0 && $x <= 10 && $c ) : 
                                ?>
                                    <p id="tr_home_gratif">{wos/deco:_trpg_Grat_guide}&nbsp;<a href="#" class="pts-in-tr-home"><span id="p-i--t-h-nb">{wos/datx:trgrat}</span> <span>coo!</span></a>&nbsp;{wos/deco:_points}.</p>
                                <?php endif; ?>
                            </div>
                            <div class="list-posts-on-acc-contr oblige-tr-ver">
                                <!-- vBeta1 : Cela va recharger la page -->
                                <div id="new-art-bar" class="jb-new-art-bar this_hide">
                                    <a id="n-a-b-trig" class="jb-n-a-b-trig" href="javascript:;">
                                        <span id="n-a-b-nb" class="jb-n-a-b-nb"></span>
                                        <span id="n-a-b-lib" class="jb-n-a-b-lib"></span>
                                    </a>
                                </div>
                                <div id="trpg-art-nest" class="jb-trpg-art-nest">
                                    <?php 
                                        $iabo = "{wos/datx:cuisabo}";
                                        $tpart = "{wos/datx:trpart}";

                                        if ( $iabo && isset($tpart) && strtolower($tpart) === "{wos/deco:_part_pub_code}" ) :
                                    ?>
                                    <div id="na-box-nw-reset" class="jb-na-box-nw-rst this_hide">
                                        <a id="na-box-nw-rst-trg" class="jb-na-box-nw-rst-trg" href="javascript:;"></a>
                                        <!--<a id="na-box-nw-rst-trg" class="jb-na-box-nw-rst-trg" href="javascript:;">{wos/deco:_Cancel}</a>-->
                                        <a id="na-box-nw-clz-trg" class="jb-na-box-nw-clz-trg" href="javascript:;">&times;</a>
                                    </div>
                                    <div id="nwtrdart-box" class="this_hide">
                                        <section id="trpg-nwabx-bmx" class="jb-trpg-nwabx-bmx this_hide">
                                            <header id="trpg-nwabx-hdr">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 
                                                <span>Erreur</span>
                                            </header>
                                            <div id="trpg-nwabx-bdy">
                                                <div id="trpg-nwabx-errm" class="jb-trpg-nwabx-errm"></div>
                                            </div>
                                            <footer id="trpg-nwabx-ftr">
                                                <button id="trpg-nwabx-git" class="jb-trpg-nwabx-git" data-action="hid-err" >J'ai Compris</button>
                                            </footer>
                                        </section>
                                        <span class="jb-tqr-skycrpr-snit" data-target='trpgnewbx'></span>
                                        <!-- pwt : Please Wait -->
                                        <div id="na-box-nw-pwt" class="jb-na-box-pwt this_hide"></div>
                                        <form id="nwtrdart-box-form">
                                            <div id="na-box-img-max">
                                                <div id="na-box-img-explain" class="jb-na-box-img-xpln" data-scp="image">
                                                    <div id="nwtrart-sto-img" class="this_hide"></div>
                                                    <div id="nwtrart-bigillus">
                                                        <img id="na-box-img-expl-illus" class="na-box-img-expl-img" src="{wos/sysdir:img_dir_uri}/r/tprg_mtn.png" /> 
                                                        <span id="na-box-img-expl-txt">Ajouter une image</span>
                                                        <!--<span id="na-box-img-expl-txt-ie">Cliquez ici pour ajouter</span>-->
                                                        <span id="na-box-img-expl-txt-loaded" class="this_hide">Votre image est prête</span>
                                                    </div>
                                                </div>
                                                <div id="na-box-vid-max" class="jb-na-box-img-xpln this_hide" data-scp="video">Votre vidéo est prête</div>
                                                <input id="na-box-img-catcher" class="jb-nabx-i-c" type="file" autocomplete="off"/>
                                            </div>
                                            <div id="na-box-input">
                                                <div>
                                                    <textarea id="na-box-input-txt" class="jb-na-box-input-txt check_char skip_sharp" data-maxch="242" data-target="innwtrdart-char-cn" placeholder="Description"></textarea>
                                                </div>
                                                <div id="nwtrdart-txt-box">
                                                    <a id="nwtrdart-trg" class="jb-nwtrart" href='' title='Trenq it !' alt='Ajouter une publication'>Publier</a>
                                                    <span id="innwtrdart-char-cn" class="nwtrdart-cnt" data-init="242">242</span>
                                                </div>
<!--                                                <div id="tqr-nwpst-tqs-bmx" class="trpg">
                                                    <a id="tqr-nwpst-tqs-hrf-mx" class="jb-tqr-nwpst-tqs-hrf-mx" href="//studio.trenqr.com" title="Redimensionnez, personnalisez et modifiez vos photos" target="_blank">
                                                        <span id="tqr-nwpst-tqs-hrf-txt">Accéder au studio</span>
                                                    </a>
                                                </div>-->
                                            </div>
                                        </form>
                                    </div>
                                    <?php  endif; ?>
                                    <?php 
                                        $x = NULL;
                                        set_error_handler('exceptions_error_handler');
                                        try {
                                            $t = "{wos/datx:trpg_articles}";
                                            $x = unserialize(base64_decode($t));
                                            
                                            restore_error_handler();
                                        } catch (Exception $exc) {
                                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                                            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
                                            
                                        }
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $as_x => $article) :
//                                            echo $article["art_id"];
                                    ?>
                                        {wos/dvt:trpg_itr}
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div id="tr-w-list" class="jb-tr-w-list list-posts-on-tr ul-left this_hide">
                                    <?php 
                                        $iabo = "{wos/datx:cuisabo}";
                                        $tpart = "{wos/datx:trpart}";

                                        if ( $iabo && isset($tpart) && strtolower($tpart) === "{wos/deco:_part_pub_code}" ) :
                                    ?>
                                    <div>
                                        <div id="na-box-nw-reset" class="jb-na-box-nw-rst this_hide">
                                            <a id="na-box-nw-rst-trg" class="jb-na-box-nw-rst-trg" href="javascript:;">{wos/deco:_Cancel}</a>
                                        </div>
                                        <div id="nwtrdart-box" class="this_hide">
                                            <!-- pwt : Please Wait -->
                                            <div id="na-box-nw-pwt" class="jb-na-box-pwt this_hide"></div>
                                            <form id="nwtrdart-box-form">
                                                <div id="na-box-img-max">
                                                    <div id="na-box-img-explain" class="this_hide">
                                                        <div id="nwtrart-sto-img" class="this_hide"></div>
                                                        <div id="nwtrart-bigillus">
                                                            <img id="na-box-img-expl-illus" class="na-box-img-expl-img" src="{wos/sysdir:img_dir_uri}/r/tprg_mtn.png" /> 
                                                            <span id="na-box-img-expl-txt">Ajouter une image</span>
                                                            <!--<span id="na-box-img-expl-txt-ie">Cliquez ici pour ajouter</span>-->
                                                            <span id="na-box-img-expl-txt-loaded" class="this_hide">Votre image est prête</span>
                                                        </div>
                                                    </div>
                                                    <input id="na-box-img-catcher" type="file" autocomplete="off"/>
                                                </div>
                                                <div id="na-box-input">
                                                    <div>
                                                        <textarea id="na-box-input-txt" class="jb-na-box-input-txt check_char skip_sharp" data-maxch="242" data-target="innwtrdart-char-cn" placeholder="Description"></textarea>
                                                    </div>
                                                    <div id="nwtrdart-txt-box">
                                                        <a id="nwtrdart-trg" class="jb-nwtrart" href='' title='Trenq it !' alt='Compose a new post for this trend'>Publier</a>
                                                        <span id="innwtrdart-char-cn" class="nwtrdart-cnt" data-init="242">242</span>
                                                    </div>
                                                    <div id="tqr-nwpst-tqs-bmx">
                                                        <a id="tqr-nwpst-tqs-hrf-mx" class="jb-tqr-nwpst-tqs-hrf-mx" href="//studio.trenqr.com" title="Redimensionnez, personnalisez et modifiez vos photos" target="_blank">
                                                            <span id="tqr-nwpst-tqs-hrf-txt">Accéder au studio</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php  endif; ?>
                                    <div id="jssel-tr-w-list-list">
                                        <?php 
                                        $x = NULL;
                                        set_error_handler('exceptions_error_handler');
                                        try {
                                            $t = "{wos/datx:articles_west}";
                                            $x = unserialize(base64_decode($t));
                                            
                                            restore_error_handler();
                                        } catch (Exception $exc) {
                                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                                            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
                                            
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $k => $article) :
                                        
                                        
                                        /*
                                        $x;
                                        try {
                                            $x = unserialize(html_entity_decode("{wos/datx:articles_west}"));

                                        } catch (Exception $exc) {
                                            //TODO : Déclencher une erreur fatale
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $article) :
                                        //*/
                                        ?>
                                            {wos/dvt:trpg_itr}
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="tr-e-list" class="jb-tr-e-list list-posts-on-tr this_hide">
                                    
                                    <div id="jssel-tr-e-list-list">
                                    <?php 
                                        
                                        $x = NULL;
                                        set_error_handler('exceptions_error_handler');
                                        try {
                                            $t = "{wos/datx:articles_east}";
                                            $x = unserialize(base64_decode($t));
                                            
                                            restore_error_handler();
                                        } catch (Exception $exc) {
                                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                                            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
                                            
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $k => $article) :
                                        
                                        /*
                                        $x;
                                        try {
                                            $x = unserialize(html_entity_decode("{wos/datx:articles_east}"));

                                        } catch (Exception $exc) {
                                            //TODO : Déclencher une erreur fatale
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $article) :
                                            
                                        //*/
                                        ?>
                                            {wos/dvt:trpg_itr}
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                </div>
                            </div>
                            <div id="where_have_you_been" class="jb-whub-mx this_hide">
                                <!-- Message afficher s'il n'y a aucun article -->
                                <p id="whub_txt">
                                    {wos/deco:_art_noone}
                                </p>
                                <!-- 
                                    Inutile et inesthétique
                                    <div id="whub_specs" class="">
                                        <a id="whub_specs_ask_new" href="javascipt:;" role="button"><b>Ajouter un article</b></a>
                                    </div>
                                -->
                            </div>
                        </div>
                        <div class="tqr-page-loadm-box tmlnr jb-trpg-loadm-box this_hide">
                            <span class="jb-tqr-hmt this_hide"></span>
                            <span class="tqr-page-loadm-spnr tmlnr jb-trpg-loadm-spnr this_hide">
                                <img class="" width="32" height="32" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                            </span>
                            <a class="tqr-page-loadm-trg tmlnr jb-trpg-loadm-trg" data-v="l" href="javascript:;">{wos/deco:_Load_more}</a>
                        </div>
<!--                        <div id="" class="css-nwfd-loadm-box jb-trpg-loadm-box"><a id="" class="css-nwfd-loadm-trg jb-trpg-loadm-trg" href="">Load More</a></div>
                        <div id="p-l-c-main-brand-ftr">
                            <p id="p-l-c-main-bd-ftr-text"><a id="p-l-c-main-bttf" href="">Trenqr</a><span id="p-l-c-m-ftr-yr">2014</span></p>
                        </div>-->
                    </div>  
<!--                    ...-->

                </div>
            </div>
        </div>

    </div>
    </div>
    <?php endif; ?>
    {wos/dvt:tqr_betawrng}
    
    <?php if ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) : ?>
    {wos/csam:email_confirm}  
    <?php endif; ?>
    
    <?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
    {wos/csam:shortcuts}
    <?php endif; ?>
    
    {wos/csam:skycrpr}
    
    {wos/csam:ltc}
    
    {wos/csam:tkbvwr}
    
    {wos/csam:tia2}
    
    {wos/csam:nwfd_snitcher}
    
    <!-- DO NOT REMOVE : LISTENERS DE LIAISON POUR CERTAINS CAS PARICULIER  -->
    <span class="jb-tqr-lstnr-onev" data-scp="afv"></span>
    
    <div id="tq-pg-env" class="jb-tq-pg-env this_hide">
    {
        "baseUrl"   : "{wos/sysdir:script_dir_uri}",
        "pageid"    : "{wos/datx:pagid}",
        "pgvr"      : "{wos/datx:pgakxver}",
        "sector"    : "",
        "ec_is_ecofirm"  : <?php echo ( $ec_is_ecofirm ) ? $ec_is_ecofirm : "false"; ?>,
        "ec_state"       : <?php echo ( $ec_state ) ? $ec_state : "false"; ?>,
        "ec_scope"       : <?php echo ( $ec_scope ) ? $ec_scope : "false"; ?>,
        "ec_is_ecofirm"  : <?php echo ( $ec_is_ecofirm === '1' ) ? 1 : 0; ?>
    }
    </div>

    <div id="requirejs">
        <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.trpg.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
    </div>
    
    
    
</div>