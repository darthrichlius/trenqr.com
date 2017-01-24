<?php
    $pgid = "{wos/datx:pagid}";
    $pgvr = "{wos/datx:pgakxver}";
    
    /*
     * PREFERENCES
     */
    $prefdcs = NULL;
    set_error_handler('exceptions_error_handler');
    try {
        $t = "{wos/datx:cuprefdcs}";
        //$t = NULL; //DEV, TEST, DEBUG
        $prefdcs = unserialize(base64_decode($t));
        
        
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

<div s-id="TQR_GTPG_HVIEW">
    <span class="tqr-snifr-wdw-scltp jb-tqr-snifr-wdw-scltp"></span>
    <section id="tqr-hview-screen" class="jb-tqr-hview-screen">
        <div class="pg-sts jb-pg-sts this_hide">
            <span class="jb-pg-sts-txt"></span>
        </div>
        <div id="tqr-hview-header">
            {wos/dvt:header_ro}
        </div>
        <div id="tqr-hview-center">
            <section id="tqr-hview-ctr-hdr-bmx">
                <header id="tqr-hview-ctr-hdr-hdr">
                    <div id="tqr-hview-c-h-h-ipt-bmx">
                        <div id="tqr-hview-c-h-h-ipt-mx">
                            <input id="tqr-hview-c-h-h-ipt" class="jb-tqr-hview-c-h-h-ipt" data-input="{wos/datx:qry}" type="text" maxlength="50" autocomplete="on" placeholder="Entrez un #motclé puis validez" value="#{wos/datx:qry}">
                        </div>
                    </div> 
                    <div id="tqr-hview-c-h-h-hlib-bmx">
                        <div id="tqr-hview-c-h-h-hlib-mx" class="jb-tqr-hview-c-h-h-hlib-mx" title="{wos/datx:qry}">#{wos/datx:qry}</div>
                    </div>
                    <div id="tqr-hview-c-h-h-xplain-bmx" class="jb-tqr-hview-c-h-h-x-bmx this_hide">
                        <div id="tqr-hview-c-h-h-xplain-mx" class="jb-tqr-hview-c-h-h-x-mx">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam in elit purus. Etiam mauris ligula, molestie vitae venenatis id, pulvinar et urna. Mauris auctor libero felis, et varius justo finibus sed.
                        </div>
                    </div>
                </header>
                <div id="tqr-hview-ctr-hdr-bdy">
                    
                </div>
            </section>
            <section id="tqr-hview-ctr-list-bmx">
                <header></header>
                <div id="tqr-hview-ctr-l-list-bmx">
                    <div id="tqr-hview-ctr-l-l-loadr" class="jb-tqr-hview-ctr-l-l-loadr this_hide">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <div id="tqr-hview-ctr-l-l-none" class="jb-tqr-hview-ctr-l-l-none this_hide">
                        <span>Aucune donnée disponible ...<br/>Pour l'instant !</span>
                    </div>
                    <div id=tqr-hview-ctr-l-list-mx" class="jb-tqr-hview-ctr-l-list-mx">
                        <?php for($i=1;$i<0;$i++) : ?>
                        <article class="tqr-hview-art-bmx jb-tqr-hview-art-bmx" data-type="photo" data-hid="" data-cnid="" data-itm="" data-itp="" data-jcache="">
                            <div class="tqr-hview-art-pho-top">
                                <a class="tqr-hview-a-pho-a jb-tqr-hview-a-pho-a" href="">
                                    <img class="tqr-hview-a-pho-i jb-tqr-hview-a-pho-i" width="600px" height="600px" src="http://lorempixel.com/600/600/nature/<?php echo $i; ?>" alt="" />
                                    <span class="tqr-hview-a-pho-fd jb-tqr-hview-a-pho-fd"></span>
                                    <span class='css-tgpsy kxlib_tgspy tqr-hview' data-type="photo" data-tgs-crd='1447530917000' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                        <span class='tgs-frm'></span>
                                        <span class='tgs-val'></span>
                                        <span class='tgs-uni'></span>
                                    </span>
                                    <span class="tqr-hview-a-pho-stats">
                                        <span class="tqr-hview-a-pho-s-mx jb-tqr-hview-a-pho-s-mx" data-type="eval">
                                            <span class="tqr-hview-a-pho-s-nb jb-tqr-hview-a-pho-s-nb" data-type="eval">10</span>
                                        </span>
                                        <span class="tqr-hview-a-pho-s-mx jb-tqr-hview-a-pho-s-mx" data-type="react">
                                            <span class="tqr-hview-a-pho-s-nb jb-tqr-hview-a-pho-s-nb" data-type="react">10</span>
                                            <span class="tqr-hview-a-pho-s-lg" data-type="react" style="
                                                  background: url('{wos/sysdir:img_dir_uri}/r/r3.png?v={wos/systx:now}') no-repeat;
                                                  background-size: 100%;
                                            "></span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                            <div class="tqr-hview-art-pho-btm">
                                <div class="tqr-hview-a-pho-b-trtle-mx">
                                    <a class="tqr-hview-a-pho-b-trtle jb-tqr-hview-a-pho-b-trtle" title="Un titre d'une Tendance dans le cas où l'article appartient à une Tendance" href="">Un titre d'une Tendance dans le cas où l'article appartient à une Tendance</a>
                                </div>
                                <div class="tqr-hview-a-pho-b-dsc-mx">
                                    <a class="tqr-hview-a-pho-b-d-o jb-tqr-hview-a-pho-b-d-o" href="/lou">@LouCarther</a>
                                    <span class="tqr-hview-a-pho-b-d-dsc jb-tqr-hview-a-pho-b-d-dsc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel orci pulvinar, ornare dolor ut, faucibus ante. In eu nisi libero. Fusce vitae mi a turpis tempor facilisis in ac velit turpis duis.</span>
                                </div>
                            </div>
                        </article>
                        <article class="tqr-hview-art-bmx jb-tqr-hview-art-bmx" data-type="testy" data-hid="" data-cnid="" data-itm="" data-itp="" data-jcache="">
                            <div class="tqr-hview-art-tst-left">
                                <div class="tqr-hview-a-tst-o-top">
                                    <a class="tqr-hview-a-tst-o-a jb-tqr-hview-a-tst-o-a" href="">
                                        <img class="tqr-hview-a-tst-o-i jb-tqr-hview-a-tst-o-i" src="http://lorempixel.com/65/65/people/<?php echo $i; ?>" height="65" alt="65" alt=""/>
                                        <span class="tqr-hview-a-tst-o-fd"></span>
                                    </a>
                                </div>
                                <div class="tqr-hview-a-tst-o-btm">
                                    <a class="tqr-hview-a-tst-o-psd jb-tqr-hview-a-tst-o-psd" href="">@LouCarther</a>
                                    <span class="tqr-hview-a-tst-o-fn jb-tqr-hview-a-tst-o-fn">Lou Carther</span>
                                </div>
                            </div>
                            <div class="tqr-hview-art-tst-right">
                                <div class="tqr-hview-a-tst-r-top">
                                    <!--<a class="tqr-hview-a-tst-tgt-a" href="">@Mouna</a>-->
                                    <span class='css-tgpsy kxlib_tgspy tqr-hview' data-type="testy" data-tgs-crd='1447530917000' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                        <span class='tgs-frm'></span>
                                        <span class='tgs-val'></span>
                                        <span class='tgs-uni'></span>
                                    </span>
                                </div>
                                <div class="tqr-hview-a-tst-r-btm">
                                    <div class="tqr-hview-a-tst-m jb-tqr-hview-a-tst-m"> 
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel orci pulvinar, ornare dolor ut, faucibus ante. In eu nisi libero. Fusce vitae mi a turpis tempor facilisis in ac velit turpis duis.
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php endfor; ?>
                        <div id="tqr-hview-ctr-l-l-EOF-none" class="jb-tqr-hview-ctr-l-l-EOF-none this_hide">C'est tout ce qu'il y a !</div>
                    </div>
                </div>
            </section>
        </div>
        {wos/csam:kxlib_dlg}
        {wos/csam:bugzy}
        
        {wos/csam:notify_ua}
        {wos/csam:nwfd}
        {wos/csam:postman}
        <!-- A Retirer OnLoad -->
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
        <?php if (! ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
        {wos/csam:shortcuts}
        <?php endif; ?>
        
        {wos/csam:tia2}

        {wos/csam:tkbvwr}
        
        {wos/csam:nwfd_snitcher}
        
        {wos/csam:unq_ro}
        
        <div id="tq-pg-env" class="this_hide">
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
        
    </section>
    
    <div id="requirejs">
        <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.hview.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
    </div>
    
<!--    <div id="js-declare">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/jquery.ui.datepicker-fr.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxdate.enty.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/tqrel.csam.js?{wos/systx:now}" defer></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/notify.csam.js?{wos/systx:now}"></script>
        
        <script src="{wos/sysdir:script_dir_uri}/r/d/header.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/keyboard.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/timegod.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/newsfeed.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/postman.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/bugzy.csam.js?{wos/systx:now}"></script> 

        <script src="{wos/sysdir:script_dir_uri}/r/csam/ec.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/asdrbnr.csam.js?{wos/systx:now}" defer></script>
        
        <script src="{wos/sysdir:script_dir_uri}/r/s/hview.m.js?{wos/systx:now}"></script> 

    </div>-->
</div>