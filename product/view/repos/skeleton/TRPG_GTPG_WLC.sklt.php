<?php
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    
    $trstate = "{wos/datx:trstate}";
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

<div s-id="TRPG_GTPG_WLC">
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
    <div id="stop_playin">
        <div id="stop_playin_wbackgr" class="this_hide">
            <div id="s_p_dialog">
                <p id="s_p_d_title" class="this_hide">Titre</p>
                <p id="s_p_d_msg">Lorem ipsum volastism padre gorlor fiustum reosma tutoip</p>
                <div id="s_p_d_ch">
                    <a class="this_hide">Tourner la roue</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="TMLNR" href="javascript:;">Repartir vers mon compte</a>
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
            <a id="warning_bar_close_a" href="#">Close</a>
            <a id="warning_bar_dsag_a" href="#">Ne plus afficher</a>
        </p>
    </div>-->
    <div id="notify_event" class="this_hide">
        <!--Le message aparait ET reste x seconde. Le bloc a une croix.-->
<!--            <p id="notify_event_text">

        </p>-->
        <p id="notify_event_err" class="this_hide">
            <span id="ua_ntfmsgerr" >An error occured ! <a href="#">Report</a></span><br/>
            Your action has probably been processed. The current error only seems to affect the notification system.
        </p>
    </div>
    {wos/csam:kxlib_dlg} <!-- [DEPUIS 09-11-15] -->
    <!-- A Retirer OnLoad -->
    {wos/csam:unq}
    {wos/csam:cnxsgn_ovly}
    {wos/dvt:header_wu}
    
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
    
    <div id="page">
        {wos/dvt:bigfail}
        <div id="page-components" class="">
            <div id="aside-bmx">
                <div id="aside" class="jb-aside">
                    {wos/dvt:cockpit}
                    <div id="asR_wait_on_trade" class="this_hide">
                        Trenqr
                    </div>
                    {wos/csam:aside_rich}
                    {wos/dvt:asdr_w_removed}
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
                                <div id="tr-header-top">
                                    <div id="a-h-t-top-tr" class="resz-tr-hdr-hir">
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
                                        <h1 id="a-h-t-top-tr-title" data-maxln="70" spellcheck="false">{wos/datx:trtitle}</h1>
                                        <div id="a-h-t-top-anim_act" class="">
                                            <a id="tr-h-an-hi" class="tr-hdr-an-btn tr-hdr-an-full" href="javascript:;"></a>
                                            <a id="tr-h-an-sm" class="tr-hdr-an-btn tr-hdr-an-btn_r" href="javascript:;"></a>
                                        </div>
                                        <p id="a-h-t-top-tr-desc" class="" data-maxln="200" spellcheck="false">{wos/datx:trdesc}</p>
                                        <span class="a-h-t-top-tr-desc-alt this_hide">{wos/deco:_trpg_Desc_ph}</span>
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
                                    <!--AJOUTEZ UN ARTICLE POUR COMMENCER-->
                                    {wos/deco:_art_noone}
                                </p>
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
<!--    <div id="wos-console-output">
        <div id="wos-csl-opt-hdr">Application Output</div>
        <div id="wos-csl-opt-body">
            <div id="wos-csl-opt-steam" class="jb-wos-csl-opt-steam"></div>
        </div>
    </div>-->

    {wos/csam:dontmiss}
    {wos/csam:tqr_btm_lock}
    
    <div id="tq-pg-env" class="jb-tq-pg-env this_hide">
    {
        "baseUrl"   : "{wos/sysdir:script_dir_uri}",
        "pageid"    : "{wos/datx:pagid}",
        "pgvr"      : "{wos/datx:pgakxver}",
        "sector"    : "",
        "ec_is_ecofirm"  : "",
        "ec_state"       : "",
        "ec_scope"       : "",
        "ec_is_ecofirm"  : ""
    }
    </div>

    <div id="requirejs">
        <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.trpg.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
    </div>

</div>