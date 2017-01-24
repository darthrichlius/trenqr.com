<div id="header" class="">
    <div id="header-container">
        <div id="header-right-bloc" class="<?php echo ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ? "this_hide" : ""; ?>">
            {wos/dvt:userbox}
        </div>
        <div id="header-left-bloc">
            <div id="header-logo-container">
                <div id="hdr-logo-subcntnr">
                    <a id="header-logo-discover" class="jb-tqr-dscvr-" data-action="open" href="javascript:;"></a> 
                    <a id="header-logo" style=" 
                        background: url('{wos/sysdir:img_dir_uri}/w/logo_tqr_final.png?v={wos/systx:now}') no-repeat; 
                        background-size: 60%;
                        background-position-x: 40%;
                        background-position-y: 65%;
                    " href="/"></a>
                </div>
            </div>
            <div id="header-center-bloc">
                <div id="header-center-b">
                    <form id="h-c-b-form">
                        <div id="tqr-bfr-srch" class="its-not-a-secret" >
                            <!--<img height="25" width="25" src="{wos/sysdir:img_dir_uri}/r/fav1.png" />-->
                            <a id="h-c-b-tuto" class="" href="javascript:;" role="button" title="Des tutoriels rapides pour apprendre à utiliser Trenqr efficacement">Tutoriels</a>
<!--                            <input style="
                                text-align: center;
                            " type='text' placeholder="Rechercher un contributeur ou un salon" />-->
                            <div id="h-c-b-explo-mx" class="" >
                                <a id="h-c-b-explo-btn" class="jb-h-c-b-explo-btn" data-action="" href="javascript:;" role="button" title="">Explorer</a>
                                <ul id="" class="h-c-b-explo-lst">
                                    <li><a class="h-c-b-explo-chc" data-action="" href="" title="">Au Hasard</a></li>
                                    <li><a class="h-c-b-explo-chc" data-action="" href="" title="">Match un Salon</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <div id="h-c-b-pancont" class="<?php echo ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ? "this_hide" : ""; ?>">
                        <div id="h-c-b-pc-menu">
                            <a class="jb-global-nav-elt css-global-nav-elt global-nav-nwfd cursor-pointer" data-target="nwfd" role="button">
                                <img id="jb-global-nav-nwfd" class="" height="30" width="30" src="{wos/sysdir:img_dir_uri}/r/nwfd.png" alt="NewsFeed" title="" />
                                <img id="jb-global-nav-nwfd_h" class="this_hide" height="30" width="30" src="{wos/sysdir:img_dir_uri}/r/nwfd_h.png" alt="NewsFeed" title="" />
                            </a>
                            <a class="jb-global-nav-elt css-global-nav-elt global-nav-pm cursor-pointer" data-target="pm" role="button">
                                <span class="_sig_ev_cn jb-sig-ev-cn this_hide" data-scp="psmn"></span>
                                <img class="menu-std" height="30" width="30" src="{wos/sysdir:img_dir_uri}/r/pm.png" alt="Postman" title="" />
                                <img class="menu-hvr this_hide" height="30" width="30" src="{wos/sysdir:img_dir_uri}/r/pm_h.png" alt="Postman" title="" />
                            </a>
                            <a id="tqr-frdrqt-nav-hdr" class="jb-tqr-f-nav-hdr cursor-pointer" data-target="friend" title="" role="button">
                                <span class="_sig_ev_cn jb-sig-ev-cn this_hide" data-scp="frd"></span>
                                <img class="tqr-frdrqt-nav-hdr-i jb-tqr-f-n-h-i" src="{wos/sysdir:img_dir_uri}/r/frd-ctr-w.png" width="30"/>
                                <img class="tqr-frdrqt-nav-hdr-i jb-tqr-f-n-h-i hover this_hide" src="{wos/sysdir:img_dir_uri}/r/frd-ctr-be.png" width="30"/>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="h-c-b-p">
                    <!--{wos/deco:_header_slogan}-->
<!--                    <span id="h-c-b-p">
                        <span class="h-c-b-p-txt white">The Social,&nbsp;</span>
                        <span class="h-c-b-p-txt">Just Fun&nbsp;</span>
                        <span class="h-c-b-p-txt white">Project</span>
                    </span>-->
                    <!--                    <span id="h-c-b-p-1st">Social, Real, </span>
                    <span id="h-c-b-p-2nd">Fun and +</span>-->
                </div>
            </div>
        </div>

    </div>
</div>