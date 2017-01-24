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
<div s-id="TRPG_GTPG_RFOL">
    <div id="stop_playin">
        <div id="stop_playin_wbackgr" class="this_hide">
            <div id="s_p_dialog">
                <p id="s_p_d_title" class="this_hide">Titre</p>
                <p id="s_p_d_msg">Lorem ipsum volastism padre gorlor fiustum reosma tutoip</p>
                <div id="s_p_d_ch">
                    <a class="this_hide">Tourner la roue</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="TMLNR" href="">Repartir vers mon compte</a>
                    <a class="s_p_d_ch_redir this_hide" data-pg="phome" href="">Repartir l'accueil</a>
                    <a id="s_p_d_ch_valid" href="">Ok</a>
                </div>
            </div>
        </div>
    </div>
    <div id="warning_bar_max" class="this_hide">
        <p id="warning_bar_txt_max">
            <span id="warning_bar_text">
                <!--Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas leo nunc, placerat at rutrum amet.-->
                Vous utilisez actuellement une version dite <b><q>beta</q></b> du site. Elle est succeptible de comporter plusieurs bogues. Pour plus d'informations, cliquez ici :  <a href='#'>Qu'est ce qu'une Beta</a>.
            </span>
        </p>
        <p id="warning_bar_close">
            <a id="warning_bar_close_a" href="#">Close</a>
            <a id="warning_bar_dsag_a" href="#">Ne plus afficher</a>
        </p>
    </div>
    <div id="notify_event" class="this_hide">
        <!--Le message aparait ET reste x seconde. Le bloc a une croix.-->
<!--            <p id="notify_event_text">

        </p>-->
        <p id="notify_event_err" class="this_hide">
            <span id="ua_ntfmsgerr" >An error occured ! <a href="#">Report</a></span><br/>
            Your action has probably been processed. The current error only seems to affect the notification system.
        </p>
    </div>
    <div id="trpg-ovly-max" class="this_hide">
        <div id="trpg-ovly-backgr">
            {wos/dvt:trpg_ovly_create}
        </div>
    </div>
    {wos/csam:nwfd}
    {wos/csam:kxlib_dlg} <!-- [DEPUIS 09-11-15] -->
    <!-- A Retirer OnLoad -->
    {wos/csam:unq_ru}
    <!-- A Retirer OnLoad -->
    {wos/dvt:frdcenter}
    {wos/dvt:header_ro}
    <div id="page" class="jb-page <?php echo ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ? "this_hide" : ""; ?>">
        {wos/dvt:bigfail}
        <div id="page-components" class="">
            <div id="aside-bmx">
                <div id="aside" class="jb-aside">
                    {wos/dvt:cockpit}
                    <div id="asR_wait_on_trade" class="this_hide">
                        Trenqr
                    </div>
                    {wos/dvt:aside_ads}
                    {wos/dvt:legals}
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
                                    <a class="err-bar-close-trig jsbind-close-trig" data-tar="err_bar_in_trpg" href="">Fermer</a>
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
                                    <a id="trpg_getctd_btn" class="en jb-trpg_getctd_btn jb-trpg-action" data-action="co" href="" title="">
                                        <!--<span id="trpg_getctd_btn_lg" class="en"></span>-->
                                        Apply
                                    </a>
                                    <a id="trpg_cntd_bdg" class="this_hide en jb-trpg_cntd_bdg jb-trpg-action" data-action="disco" href="" title="" >
                                            <span id="trpg_cntd_bdg_lg" class="en"></span> 
                                            Registered
                                        </a>
                                    <div id="a-h-t-top-tr" class="resz-tr-hdr-hir">
                                        <p id="a-h-t-top-tr-img-max">
                                            <img id="a-h-t-top-tr-img-img" src="{wos/datx:trcover}"/>
                                        </p>
                                        <span id="a-h-t-top-fade" class="tr-header-fade"></span>
                                        <h1 id="a-h-t-top-tr-title" data-maxln="70" spellcheck="false">{wos/datx:trtitle}</h1>
                                        <div id="a-h-t-top-anim_act" class="">
                                            <a id="tr-h-an-hi" class="tr-hdr-an-btn tr-hdr-an-full" href=""></a>
                                            <a id="tr-h-an-sm" class="tr-hdr-an-btn tr-hdr-an-btn_r" href=""></a>
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
                                                            <a id="tr-h-t-d-10-psdpsd" href="{wos/datx:ouhref}" title="{wos/datx:oufn}">
                                                                <img id="tr-h-t-d-10-psdimg" height="45" width="45" src="{wos/datx:ouppic}" />
                                                                <span>{wos/datx:oupsd}</span>
                                                            </a>
                                                        </p>
                                                    </li>
                                                    <li class="tr-h-t-d-11"></li>
                                                </ul>
                                            </li>
                                            <li id="tr-h-t-d-2">
                                                <!--<p id="tr-h-t-d-20" data-length="-1">260<span class="tr-h-t-d-xx1"> 978</span><span class="tr-h-t-d-xx2"></span></p>-->
                                                <p id="tr-h-t-d-20" data-length="{wos/datx:trnbposts}" title="">{wos/datx:trnbposts}</p>
                                                <p id="tr-h-t-d-21" >{wos/deco:_Posts}</p>
                                            </li>
                                            <li id="tr-h-t-d-3">
                                                <p id="tr-h-t-d-30" data-length="{wos/datx:trgrat}" >{wos/datx:trgrat}</p>
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
                                <div>
                                    <div class="action_maximus action_trhome">
                                        <a href="#" class='action_a action_a_htr'><span class='brain_sp_k'>A</span><span class='brain_sp_action'>ction<span></a>
                                        <ul class='action_foll_choices this_hide'>
                                            <li><a href="javascript:;" class='afl_choice bind-tr-trpg-cr' data-action='op_trpg_cr'>{wos/deco:_trpg_Create_tr}</a></li>
                                        </ul>            
                                    </div>
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
                                <div id="new-art-bar" class="this_hide">
                                    <a id="n-a-b-trig" href="">
                                        <span id="n-a-b-nb">0</span>
                                        <span>New Publications</span>
                                    </a>
                                </div>
                                <div id="tr-w-list" class="list-posts-on-tr ul-left">
                                    <div id="nwtrdart-box" class="this_hide">
                                        <form id="nwtrdart-box-form">
                                            <div id="na-box-img-max">
                                                <div id="na-box-img-explain" class="this_hide">
                                                    <div id="nwtrart-sto-img" class="this_hide">

                                                    </div>
                                                    <div id="nwtrart-bigillus">
                                                        <img id="na-box-img-expl-illus" class="na-box-img-expl-img" src="../public/img/final/tprg_mtn.png" />
                                                        <span id="na-box-img-expl-txt">Ajouter une image</span>
                                                        <!--<span id="na-box-img-expl-txt-ie">Cliquez ici pour ajouter</span>-->
                                                        <span id="na-box-img-expl-txt-loaded" class="this_hide">Image chargée</span>
                                                    </div>
                                                </div>
                                                <div id="" class="">
                                                    
                                                </div>
                                                <input id="na-box-img-catcher" type="file" autocomplete="off"/>
                                            </div>
                                            <div id="na-box-input">
                                                <div>
                                                    <textarea id="na-box-input-txt" class="jb-na-box-input-txt check_char skip_sharp" data-maxch="242" data-target="innwtrdart-char-cn" placeholder="Description"></textarea>
                                                </div>
                                                <div id="nwtrdart-txt-box">
                                                    <a id="nwtrdart-trg" class="jb-nwtrart" href='' title='Trenq it !' alt='Ajouter une publication'>Publier</a>
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
                                    <div id="jssel-tr-w-list-list">
                                        <?php 
                                        $x;
                                        try {
                                            $x = unserialize(html_entity_decode("{wos/datx:articles_west}"));

                                        } catch (Exception $exc) {
                                            //TODO : Déclencher une erreur fatale
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $article) :
                                        ?>
                                            {wos/dvt:trpg_itr}
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="tr-e-list" class="list-posts-on-tr">
                                    
                                    <div id="jssel-tr-e-list-list">
                                    <?php 
                                        $x;
                                        try {
                                            $x = unserialize(html_entity_decode("{wos/datx:articles_east}"));

                                        } catch (Exception $exc) {
                                            //TODO : Déclencher une erreur fatale
                                        }
                                        
                                        if ( isset($x) && is_array($x) && ( count($x) ) ) : 
                                            foreach ($x as $article) :
                                        ?>
                                            {wos/dvt:trpg_itr}
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                </div>
                            </div>
                            <div id="where_have_you_been" class="this_hide">
                                <!-- Message afficher s'il n'y a aucun article -->
                                <p id="whub_txt">
                                    {wos/deco:_art_noone}
                                </p>
                                <div id="whub_specs" class="">
                                    <a id="whub_specs_ask_new" href=""><b>Ajouter un article</b></a>
                                </div>
                            </div>
                        </div>
                        <div id="" class="css-nwfd-loadm-box jb-trpg-loadm-box"><a id="" class="css-nwfd-loadm-trg jb-trpg-loadm-trg" href="">Load More</a></div>
                        <div id="p-l-c-main-brand-ftr">
                            <p id="p-l-c-main-bd-ftr-text"><a id="p-l-c-main-bttf" href="">Trenqr</a><span id="p-l-c-m-ftr-yr">2014</span></p>
                        </div>
                    </div>  
<!--                    ...-->

                </div>
            </div>
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
    
    <div id="js_declare">
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/animatescroll.noeasing.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/underscore-min.js"></script>
        <!-- [DEPUIS 22-08-15] @author BOR -->
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/ajax_rules.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/d/dashboard.d.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/kxdate.enty.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/com.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/c.c/feat_rules.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/csam/perm.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/csam/errorbar_vtop.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/csam/whiteblackboard.csam.js?{wos/systx:now}"></script>

        <script src="{wos/sysdir:script_dir_uri}/s/trend.js?{wos/systx:now}"></script>

        <script src="{wos/sysdir:script_dir_uri}/d/main-header2.d.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/csam/tooltip.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/d/stream.d.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/d/stream-cmts.d.js"></script> 
        <!--<script src="{wos/sysdir:script_dir_uri}/d/aside_right.d.js?{wos/systx:now}"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/d/dashboard.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/notify.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/resizable.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/d/tr-filter-res.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/trpg-trends.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/d/newtrdart.d.js?{wos/systx:now}"></script>

        <script src="../public/js/m/account.m.js?{wos/systx:now}"></script>

        <!--<script src="../public/js/hstg.js"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/d/header.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/keyboard.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/c.c/noone.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/timegod.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/newsfeed.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/friends.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/asdapps.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/chatbox.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/search.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/postman.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/evalbox.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/csam/unique.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/hlpmd.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/ix/trpg-index.ix.js?{wos/systx:now}"></script>
    </div>
</div>