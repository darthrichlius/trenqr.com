<?php  
    
    $so = $ia = $pgvr = NULL;
    set_error_handler('exceptions_error_handler');
    try {
        $so = "{wos/datx:istr}";
        $ia = "{wos/datx:iauth}";
        $pgid = "{wos/datx:pagid}";
        $pgvr = "{wos/datx:pgakxver}";
        
       /*
        * EMAIL_CONFIRMATION
        */
        $ec_is_ecofirm = "{wos/datx:ec_is_ecofirm}";
        $ec_state = "{wos/datx:ec_state}";
        $ec_scope = "{wos/datx:ec_scope}";
        $ec_is_ecofirm = ( $ec_is_ecofirm === '1' ) ? TRUE : FALSE;
        
        /*
         * CAPTIVATE OVERLAY
         */
        $captivate_sgg_upsd = "{wos/datx:captivate_sgg_upsd}";
        $captivate_show = "{wos/datx:captivate_show}";
        $captivate_show = ( $captivate_show === '1' ) ? TRUE : FALSE;
        
        
        $ustgs = unserialize(base64_decode("{wos/datx:ustgs}"));
        $ori_ustgs = $ustgs;
        $hashs = unserialize(base64_decode("{wos/datx:hashs}"));
        
        $article = unserialize(base64_decode("{wos/datx:tst_tab}"));
        
//        var_dump($captivate_sgg_upsd,$captivate_show);
//        var_dump($so,$ia,$pgid,$pgvr);
//        var_dump($evlds);
//        var_dump($article);
//        exit();
        
        restore_error_handler();
    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
    
//    var_dump(__LINE__,$ustgs,"{wos/datx:ustgs}");
//    exit();
    $str__ = "[]";
    if ( isset($ustgs) && !empty($ustgs) && is_array($ustgs) && count($ustgs) && is_array($ustgs[0]) ) {
        $istgs__ = [];
        foreach ($ustgs as $ustg) {
            $istgs__[] = implode("','", $ustg);
        }  
        
        if ( count($istgs__) > 1 ) {
            $str__ = implode("'],['", $istgs__);
        } else {
            $str__ = $istgs__[0];
        }
        $str__ = "['$str__']";
    }
?>
<div id="fb-root"></div>
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

<script>
    window.twttr = (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
          t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function(f) {
          t._e.push(f);
        };

        return t;
    }(document, "script", "twitter-wjs"));
</script>


<div s-id="FKSA_GTPG_TSTVER" style="height: 100%; width: 100%;">
    
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
    
   {wos/csam:notify_ua}
   <?php if (! $ia ) : ?>
   {wos/csam:cnxsgn_ovly}
   <?php endif; ?>
   <div id="fksa-header">
       <div id="fksa-h-logo-mx">
           <a id="header-logo" style="background: url('{wos/sysdir:img_dir_uri}/w/logo_tqr_final.png?v=<?php echo time(); ?>') no-repeat; background-size: 90%;" href="/"></a>
       </div>
       <div id="fksa-h-home">
           <?php if (! $ia ) : ?>
           {wos/dvt:welcbox}
           <?php else : ?>
           <a id="fksa-h-home-hrf" href="{wos/datx:cuhref}" title="Aller vers mon compte sur Trenqr">Chez Moi</a>
           <?php endif; ?>
       </div>
   </div>
   <div id="fksa-screen">
<!--       <div id="fksa-art-dialbox-sprt" class="this_hide">
           <div id="fksa-art-dialbox-mx">
               <a id="fksa-art-dlgbx-clz" href="javascript:;"></a>
               <div id="fksa-art-dlgbx-hdr">
                   <span id="fksa-art-dlgbx-hdr-ms">Etes-vous sûr de vouloir supprimer définitivement cette publication ?</span>
               </div>
               <div id="fksa-art-dlgbx-bdy">
                   <a class="fksa-art-dlgbx-bdy-chc" data-action="confirm_delart" href="javascript:;">Oui</a>
               </div>
           </div>jb-tqr-fksa-tst-o-a
       </div>-->
        <?php if ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) : ?>
            {wos/csam:email_confirm}  
        <?php endif; ?>
        <div id="fksa-article" class="jb-fksa-article <?php echo ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ? "this_hide" : ""; ?>" 
        >
            <div id="fksa-art-bdy-bmx" class="clearfix">
                <div id="tqr-fksa-tst-art-wpr">
                    <article 
                        class="tqr-fksa-tst-art-bmx jb-tqr-fksa-tst-art-bmx this_hide" data-atype="fksa"
                        data-ajcache="<?php echo htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8'); ?>"
                    >
                    <div class="tqr-fksa-tst-art-tst-left">
                        <div class="tqr-fksa-tst-o-top">
                            <a class="tqr-fksa-tst-o-a jb-tqr-fksa-tst-o-a" href="">
                            <img class="tqr-fksa-tst-o-i jb-tqr-fksa-tst-o-i" src="" height="65" width="65" alt=""/>
                            <span class="tqr-fksa-tst-o-fd"></span>
                            </a>
                        </div>
                        <div class="tqr-fksa-tst-o-btm">
                            <a class="tqr-fksa-tst-o-psd jb-tqr-fksa-tst-o-psd" href=""></a>
                            <span class="tqr-fksa-tst-o-fn jb-tqr-fksa-tst-o-fn"></span>
                        </div>
                    </div>
                    <div class="tqr-fksa-tst-art-tst-right">
                        <div class="tqr-fksa-tst-r-top">
                            <a class="tqr-fksa-tst-tgt-a jb-tqr-fksa-tst-tgt-a" href=""></a>
                            <span class='css-tgpsy kxlib_tgspy tqr-fksa-tst' data-type="testy" data-tgs-crd='{wos/datx:tm}' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                <span class='tgs-frm'></span>
                                <span class='tgs-val'></span>
                                <span class='tgs-uni'></span>
                            </span>
                        </div>
                        <div class="tqr-fksa-tst-r-btm">
                            <div class="tqr-fksa-tst-m jb-tqr-fksa-tst-m"></div>
                            <div class="tqr-fksa-tst-xtdatas">
                                <span class="tqr-fksa-tst-xds-tsl jb-tqr-fksa-tst-xds-tsl">0</span>
                                <span class="tqr-fksa-tst-xds-tsr jb-tqr-fksa-tst-xds-tsr">0</span>
                            </div>
                        </div>
                    </div>
                    </article>
                </div>
                <section id="tqr-fksa-tst-go-further-mx" class="jb-tqr-fksa-tst-go-fur-mx this_hide">
                    <header id="tqr-fksa-tst-go-frthr-hdr">
                        <div id="tqr-fksa-tst-go-frthr-h-tle">
                            Les dernières publications de <a id="tqr-fksa-tst-go-frthr-h-t-psd" class="jb-tqr-fksa-tst-go-f-h-t-psd" href="">@Pseudo</a>
                        </div>
                    </header>
                    <div id="tqr-fksa-tst-go-frthr-bdy">
                        <ul id="tqr-fksa-tst-go-f-b-art-lst" class="jb-tqr-fksa-tst-go-f-b-a-lst">
                        <?php for($ii=0;$ii<0;$ii++) : ?>
                            <li class="tqr-fksa-tst-go-f-b-a-wpr jb-tqr-fksa-tst-go-f-b-a-wpr">
                                <article class="tqr-fksa-tst-go-f-b-amx jb-tqr-fksa-tst-go-f-b-amx" data-item="">
                                    <a class="tqr-fksa-tst-go-f-b-a-i-wpr jb-tqr-fksa-tst-go-f-b-a-i-wpr" href="">
                                        <span class="tqr-fksa-tst-go-f-b-a-i-fd jb-tqr-fksa-tst-go-f-b-a-i-fd"></span>
                                        <img class="tqr-fksa-tst-go-f-b-a-i jb-tqr-fksa-tst-go-f-b-a-i" width="70" src="http://placehold.it/70x70" alt="" title="" />
                                    </a>
                                </article>
                            </li>
                        <?php endfor; ?>
                        </ul>
                    </div>
                </section>
            </div>
            <div id="fksa-art-footer">
                <div id="fksa-seemore-bmx">
                    <div id="fksa-smore-inr-prom-mx">
                        <div id="fksa-smore-inr-prm-head">
                            <a id='fksa-smore-inr-prm-hd-hrf' href="/ontrenqr&v=1m30">
                                <img id='fksa-smore-inr-prm-hd-i' height='80px' src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr : La communauté la plus cool d'internet, en images"/>
                            </a>
                        </div>
                        <div id="fksa-smore-inr-prm-body">
                            <div  id="fksa-smore-inr-prm-bd-txt-mx">
                                <div id="fksa-smore-inr-prm-bd-txt">
                                    Trenqr ce sont des photos, des amis, du fun mais pas seulement. C'est la nouvelle communauté coo<i>!</i> et #Tendance d'internet
                                </div>
                                <div id="fksa-smore-inr-prm-bd-hrt"><i class="fa fa-heart"></i></div>
                            </div>
                            <div id="fksa-smore-inr-prm-bd-sgup">
                                <a id="inr-prm-signup" href="/signup">S'inscrire</a>
                                <a id="inr-prm-login" href="/login">Se Connecter</a>
                            </div>
                        </div>
                    </div>
                    <div id="fksa-smr-header">
                       <h2 id="fksa-smr-hdr-tle">Ailleurs sur Trenqr</h2>
                    </div>
                    <div id="fksa-smr-body" class="jb-fksa-smr-body">
                        <?php if (! $ia ) : ?>
                        <div id="fksa-smr-ads-bmx" class="">
                            <div id="fksa-smr-ads-mx">
                                <div id="fksa-smr-ads-hdr">
                                    <h3 id="fksa-smr-ads-tle">Publicité</h3>
                                    <a id="fksa-smr-ads-rmvads" class="cursor-pointer jb-fksa-smr-ads-rmvads" >Désactiver la publicité</a>
                                </div>
                                <div id="fksa-smr-ads-bdy">
                                    <div class="fksa-smr-ads-cntt">
                                        <div class="fksa-smr-ads-wrp">
                                            <img class="" src="http://www.placehold.it/300x250" alt="" />
                                            <!-- FOKUS-SUGGS-1 -->
<!--                                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                            <ins class="adsbygoogle"
                                                 style="display:inline-block;width:300px;height:250px"
                                                 data-ad-client="ca-pub-7028578741126541"
                                                 data-ad-slot="6829142912"></ins>
                                            <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>-->
                                        </div>
                                    </div>    
                                    <div class="fksa-smr-ads-cntt">
                                        <div class="fksa-smr-ads-wrp">
                                            <img class="" src="http://www.placehold.it/300x250" alt="" />
                                            <!-- FOKUS-SUGGS-2 -->
<!--                                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                            <ins class="adsbygoogle"
                                                 style="display:inline-block;width:300px;height:250px"
                                                 data-ad-client="ca-pub-7028578741126541"
                                                 data-ad-slot="8305876119"></ins>
                                            <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                        <?php endif; ?>
                        <?php // for($i=0;$i<4;$i++) : ?>
                        <?php for($i=0;$i<0;$i++) : ?>
                        <article class="fksa-smr-articles-bmx jb-fksa-smr-a-bmx" data-item="">
                            <div class="fksa-smr-art-trd-mx">
                                <div class="fksa-smr-art-trd-cvr">
                                    <a class="fksa-smr-art-trd-cvr-hrf jb-fksa-smr-art-trd-cvr-hrf" href="">
                                        <img class="fksa-smr-art-trd-cvr-i jb-fksa-smr-art-trd-cvr-i" src="http://www.lorempixel.com/280/160" width="280px" alt="TRD_DESCIPTION" />
                                        <!--<img src="http://www.placehold.it/280x160" alt="" />-->
                                    </a>
                                </div>
                                <div class="fksa-smr-art-trd-hdr">
                                    <header class="fksa-smr-art-trd-hdr-tle-mx">
                                        <h4><a class="fksa-smr-art-trd-hdr-tle jb-fksa-smr-art-trd-hdr-tle" href="">Titre de la tendance</a></h4>
                                    </header>
                                    <div class="fksa-smr-art-trd-hdr-dsc-mx">
                                        <a class="fksa-smr-art-trd-hdr-dsc jb-fksa-smr-art-trd-hdr-dsc" href="">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean elementum, augue fermentum elementum feugiat, diam nisi lacinia tortor, vitae dictum ante mi efficitur odio. Pellentesque facilisis sed.
                                        </a>
                                    </div>
                                    <footer class="fksa-smr-art-trd-hdr-xtra-mx">
                                        <a class="fksa-smr-art-trd-hdr-xt-ownr jb-fksa-smr-art-trd-hdr-xt-ownr" href="">
                                            <span class="fksa-smr-art-trd-hdr-xt-psd jb-fksa-smr-art-trd-hdr-xt-psd" >@pseudo</span>
                                        </a>
                                        <span class="fksa-smr-art-trd-hdr-xt-time jb-fksa-smr-art-trd-hdr-xt-tm">Il y a 2 heures</span>
                                    </footer>
                                </div>
                            </div>
                        </article>
                        <?php endfor; ?>
                   </div>
               </div>
                <div id="fksa-art-legals-mx">
                   <div id="fksa-art-legals-brand-mx">
                       <div id="fksa-art-tqr">Trenqr 2016</div>
                       <div id="fksa-art-lg">Français - France</div>
                   </div>
                   <ul id="fksa-art-legals-list">
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/about">A Propos</a></li>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/terms">CGU</a></li>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/privacy">Confidentialité</a></li>
                        <?php if (! $ia ) : ?>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/cookies">Cookies</a></li>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf jb-fksa-art-legal-hrf" href="javascript:;">Retirer la Publicité ?</a></li>
                        <?php else : ?>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/cookies">Cookies</a></li>
                        <?php endif; ?>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf last" href="/blog.trenqr.com">Trenqr Blog</a></li>
                   </ul>
               </div>
           </div>
       </div>
    </div>
    
   {wos/csam:tkbvwr}
   
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
   
   
    <div class="pg-sts jb-pg-sts this_hide">
        <span class="jb-pg-sts-txt"></span>
    </div>
   
    <?php if (! $ia ) : ?>
    {wos/csam:dontmiss}
    <?php endif; ?>
    <?php if ( $ia && !( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ) : ?>
    {wos/csam:shortcuts}
    <?php endif; ?>
   
    {wos/csam:kxlib_dlg}
   
    <div id="requirejs">
        <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.fksa.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
    </div>
   
</div>