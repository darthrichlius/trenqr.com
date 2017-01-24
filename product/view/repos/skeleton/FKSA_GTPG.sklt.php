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
        
        
        $ustgs = unserialize(base64_decode("{wos/datx:art_ustgs}"));
        /*
         * [DEPUIS 18-04-16]
         */
        $vidu = "{wos/datx:art_vidu}";
        /*
         * [DEPUIS 18-04-16]
         */
        $isod = "{wos/datx:art_isod}";
        /*
         * [DEPUIS 18-07-16]
         */
        $ihstd = "{wos/datx:art_ihstd}";
            
        $ori_ustgs = $ustgs;
        $hashs = unserialize(base64_decode("{wos/datx:art_list_hash}"));
        $evlds = unserialize(base64_decode("{wos/datx:art_eval}"));
        $me = "{wos/datx:myel}";
        
//        var_dump($captivate_sgg_upsd,$captivate_show);
//        var_dump($so,$ia,$pgid,$pgvr);
//        var_dump($evlds);
//        exit();
        
        restore_error_handler();
    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
    
    $hasfv = ( "{wos/datx:hasfv}" === '1' ) ? TRUE : FALSE;
    
//    var_dump(__LINE__,$hasfv);
//    var_dump(__LINE__,$ustgs,"{wos/datx:art_ustgs}");
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


<div s-id="FKSA_GTPG_RU" style="height: 100%; width: 100%;">
   {wos/csam:notify_ua}
   <?php if (! $ia ) : ?>
   {wos/csam:cnxsgn_ovly}
   <?php endif; ?>
   <div id="fksa-header">
       <div id="fksa-h-logo-mx">
           <a id="header-logo" class="fksa" style="background: url('{wos/sysdir:img_dir_uri}/w/logo_tqr_final.png?v=<?php echo time(); ?>') no-repeat; background-size: 85%;" href="/"></a>
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
           </div>
       </div>-->
       <?php if ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) : ?>
            {wos/csam:email_confirm}  
        <?php endif; ?>
       <div id="fksa-article" class="jb-fksa-article jb-tqr-fav-bind-arml <?php echo ( $ec_is_ecofirm && $ec_state === "_EC_STT_LOCKNOW" ) ? "this_hide" : ""; ?>" 
            data-item="{wos/datx:art_eid}" 
            data-time="{wos/datx:art_creadate}" 
            data-rnb="{wos/datx:art_rnb}" 
            data-enb="{wos/datx:art_eval_nb}"
            data-evl="{wos/datx:art_eval_vl}"
            data-with="<?php echo $str__; ?>" 
            data-ajustgs='<?php echo json_encode( ["ustgs" => $ori_ustgs]); ?>'
            data-ajhashs='<?php echo json_encode( ["hashs" => $hashs] ); ?>'
            data-vidu="{wos/datx:art_vidu}"
            data-hasfv="{wos/datx:hasfv}"
            data-atype="on_fksa"
        >
           <div id="fksa-art-bdy-bmx" class="jb-#fksa-art-bdy-bmx clearfix">
                <div div id="fksa-art-big-center">
                    <?php if ( $so ) : ?>
                    <div id="fksa-art-header" class="jb-fksa-art-hdr">
                        <span class="fksa-art-qtr-bdg">Tendance<i>!</i></span>
     <!--               <span class="fksa-art-qtr-bdg">Trend<i>!</i></span>-->
                        <h2><a class="fksa-art-qtr-tle jb-fksa-art-qtr-tle" href="{wos/datx:trd_href}" title="{wos/datx:trd_title}">{wos/datx:trd_title}</a></h2>
                    <?php elseif ( $ihstd ) : ?>
                    <div id="fksa-art-header" class="jb-fksa-art-hdr _hosted"> 
                        <span id="fksa-art-hdr-hstd-mx" >
                            <span id="fksa-art-hdr-hstd-bdg" title="Photo ou vidéo non répertoriée">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                            <span id="fksa-art-hdr-hstd-tle">Seul(e) vous et les personnes qui connaissent cette URL peuvent réagir à cette publication</span>
                        </span>
                    <?php else : ?>
                    <div id="fksa-art-header" class="jb-fksa-art-hdr no-hdr">
                    <?php endif; ?>
                    </div>
                    <div id="fksa-art-center" class="jb-fksa-art-ctr">
                        <div id="fksa-art-ctr-menu">
                            <div>
         <!--                   <div class="fksa-art-porto-mx this_hide">
                                <div class="fksa-art-porto">
                                    <img class="fksa-art-porto-pic" src="http://www.lorempixel.com/60/60" />
                                </div>
                            </div>-->
                            </div>
                   
                            <div id="fksa-art-menu-choices">
                                
                                <?php if ( strtoupper($pgvr) === "WLC" ) : ?>
                                <a 
                                    class="fksa-a-mn-choice fav jb-irr" 
                                    data-action="favorite" 
                                    data-reva="unfavorite" 
                                    data-revt="Retirer des favoris" 
                                    href="/login" 
                                    title="Si vous l'aimez, gardez la !"
                                >
                                <?php elseif ( $hasfv ) : ?>
                                <a 
                                    class="fksa-a-mn-choice fav jb-tqr-art-abr-tgr" 
                                    data-art-mdl="on_fksa" 
                                    data-action="unfavorite" 
                                    data-reva="favorite" 
                                    data-revt="Si vous l'aimez, gardez la !" 
                                    href="javascript:;" 
                                    title="Retirer des favoris"
                                >
                                    <?php else : ?>
                                <a 
                                    class="fksa-a-mn-choice fav jb-tqr-art-abr-tgr" 
                                    data-art-mdl="on_fksa" 
                                    data-action="favorite" 
                                    data-reva="unfavorite" 
                                    data-revt="Retirer des favoris" 
                                    href="javascript:;" 
                                    title="Si vous l'aimez, gardez la !"
                                >
                                    <?php endif; ?>
                                </a>
                                <a class="fksa-a-mn-choice jb-fksa-a-mn-choice" href="javascript:;" title="Cliquez pour voir qui a aimé" data-action="fksa_evaluations">
                                    <span class="fksa-a-mn-ch-dga"></span>
                                    <!--<span class="fksa-a-mn-ch-selected-pin jb-fksa-a-mn-ch-selected-pin"></span>-->
                                    <img class="fksa-a-mn-ch-lg" width="30px" src="{wos/sysdir:img_dir_uri}/r/heart_w.png?v=<?php echo time(); ?>"/>
                                    <span class="fksa-a-mn-ch-nb jb-fksa-a-mn-ch-nb">{wos/datx:art_eval_vl}</span>
                                </a>
                                <a class="fksa-a-mn-choice jb-fksa-a-mn-choice" href="javascript:;" title="Cliquer pour voir les commentaires" data-action="fksa_reactions">
                                    <span class="fksa-a-mn-ch-dga"></span>
                                    <img class="fksa-a-mn-ch-lg" width="35px" src="{wos/sysdir:img_dir_uri}/r/bbl_w.png?v=<?php echo time(); ?>"/>
                                    <span class="fksa-a-mn-ch-nb jb-fksa-a-mn-ch-nb">{wos/datx:art_rnb}</span>
                                </a>
                                <?php if ( $so || $isod ) : ?>
                                <span id="fksa-a-mn-choice-sep"></span>
                                <a class="fksa-a-mn-choice sharon jb-fksa-a-mn-choice" data-action="fksa_sharon_fb" href="javascript:;" title="Partager sur Facebook">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a class="fksa-a-mn-choice sharon jb-fksa-a-mn-choice" data-action="fksa_sharon_twr" href="javascript:;" title="Partager sur Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>
                                <?php endif; ?>
                                <?php if ( FALSE ) : /*if ( $ia === TRUE ) : */ ?>
                                <a id="fksa-a-mn-ch-option" class="fksa-a-mn-choice jb-fksa-a-mn-choice" href="javascript:;" title="Cliquer pour accéder aux options" data-action="fksa_options">
                                    <span class="fksa-a-mn-ch-dga"></span>
                                    <img class="fksa-a-mn-ch-lg" src="{wos/sysdir:img_dir_uri}/r/3pt-w.png?v=<?php echo time(); ?>"/>
                                </a>
                                <div id="fksa-art-mn-opt-list-mx" class="jb-fksa-a-mn-opt-list this_hide">
                                    <ul id="fksa-art-mn-opt-list">
         <!--                               <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="share_article" href="javascript:;" alt="">Partager</a>
                                        </li>
                                        <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="download_article" href="javascript:;" alt="">Télécharger</a>
                                        </li>
                                        <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="report_article" href="javascript:;" alt="Lien pour lancer l'opération de signalement de la publication">Signaler</a>
                                        </li>-->
                                        <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="delete_article" href="javascript:;" alt="Lien pour lancer l'opération de suppression de la publication">Supprimer</a>
                                        </li>
         <!--                               <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="" href="javascript:;" alt="Lien pour copier le lien de l'adresse permanente dédiée de la publication">Copier le lien</a>
                                        </li>
                                        <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="" href="javascript:;" alt="Lien pour voir des images similaires à la publication affichée">Voir dans le contexte</a>
                                        </li>
                                        <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="" href="javascript:;" alt="">Changer de filtre</a>
                                        </li>
                                        <li class="fksa-art-mn-opt-l-elt-mx">
                                            <a class="fksa-art-mn-opt-l-elt" data-action="" href="javascript:;" alt="Lien pour voir des images similaires à la publication affichée">Voir plus d'images</a>
                                        </li>-->
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="tqr-art-actbar-fav-artdef-wpr">
                            <div class="tqr-art-actbar-fav-bmx fksa jb-tqr-art-abr-fav-bmx this_hide">
                                <div class="tqr-art-actbar-fav-chcs fav">
                                    <a class="tqr-art-actbar-fav-ccl cursor-pointer jb-tqr-art-actbar-fav-ccl" data-art-mdl="on_fksa" data-action="fav_cancel" title="Annuler" role="button"></a>
                                    <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-css="fav_public" data-art-mdl="on_fksa" data-action="fav_public">Public</a>
                                    <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-css="fav_private" data-art-mdl="on_fksa" data-action="fav_private">Privé</a>
                                </div>
                            </div>
                            <?php if (! $vidu ) : ?>
                            <div id="fksa-art-ctr-img-mx">
                                <?php if ( $isod ) : ?>
                                <div class="tqr-art-isod-lg" data-atype="onpg_fksa" >Photo du jour</div>
                                <?php endif; ?>
                                <span id="fksa-art-ctr-img-ga"></span>
                                <img id="fksa-art-ctr-img" height="640px" width="640px" src="{wos/datx:art_pdpic_path}?v={wos/systx:now}" alt="{wos/datx:art_desc}"/>
                            </div>
                            <?php else : 
                                preg_match('/^[\s\S]+\.([\w]{3,4})\?fmat=([\d]+)x([\d]+)\&dur=([\d]{1,2})$/', $vidu, $matches);
                                $type = $matches[1];
                                $width = intval($matches[2]);
                                $height = intval($matches[3]);
                                $duration = intval($matches[4]);
                                $isloop = ( $duration <= 10 ) ? TRUE : FALSE;
                                $ref = 640;

                                if ( $width/$height === 1 ) {
                                    $width = $ref;
                                    $height = $ref;
                                } else {
                                    $ratio = ( $width >= $height ) ? $width/$ref : $height/$ref ;
                                    $width = $width/$ratio;
                                    $height = $height/$ratio;
                                }
                            ?>
                            <div id="fksa-art-ctr-vid-mx" class="jb-fksa-art-ctr-vid-mx this_invi" >
                                <?php if ( $isod ) : ?>
                                <div class="tqr-art-isod-lg" data-atype="onpg_fksa" >Photo du jour</div>
                                <?php endif; ?>
                                <div id="fksa-art-ctr-lnch-vid-mx">
                                    <a id="fksa-art-ctr-lnch-vid" class="jb-fksa-art-ctr-lnch-vid paused" data-action="fksa-vid-play"></a>
                                </div>
                                <div id="fksa-art-ctr-vid-wpr" class="jb-fksa-art-ctr-vid-wpr">
                                    <video 
                                        id="fksa-art-ctr-vid" 
                                        class="jb-fksa-art-ctr-vid" 
                                        width="<?php echo $width; ?>" 
                                        height="<?php echo $height; ?>" src="<?php echo $vidu; ?>" <?php echo ( $isloop === TRUE ) ? "loop" : ""; ?> 
                                        preload="auto"
                                    >
                                        Unable to display the player.
                                    </video>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (! $ia ) : ?>
<!--                        <div id="fksa-ads-asd-ban-mx" >
                            <div id="fksa-ads-asd-ban">
                                <a href="javascript:;">
                                    <img class="" src="http://www.placehold.it/160x600" />
                                </a>
                            </div>
                        </div>-->
                        <?php endif; ?>
                    </div>
                    <div id="fksa-art-rct-mx" class="jb-fksa-art-rct-mx jb-fksa-mod-mx this_hide" data-scp="fksa_reactions">
               <!--<div id="fksa-art-rct-mx" class="jb-fksa-art-rct-mx jb-fksa-mod-mx this_hide" data-scp="fksa_reactions">-->
                        <div id="fksa-a-r-m-header">
                            <span id="fksa-a-r-m-h-rnb-mx" class="jb-fksa-elt-exdnb-mx jb-fksa-art-rnb-mx" title="Le nombre total d'appréciations reçues par cette publication">
                                <span class="fksa-elt-nb jb-fksa-elt-nb jb-fksa-art-rnb">{wos/datx:art_rnb}</span>
                                <span class="fksa-elt-lib">commentaires</span>
                            </span>
                        </div>
                        <div id="fksa-a-r-m-list-bmx">
                            <div id="fksa-a-r-m-list-mx">
                                <div id="fksa-a-r-spnr-mx" class="jb-fksa-a-r-spnr-mx">
                                    <img id="fksa-a-r-spnr" class="jb-fksa-a-r-spnr jb-fksa-a-spnr this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                                </div>
     <!--                       <div id="fksa-a-r-noone-mx" class="jb-fksa-a-elt-noone-mx jb-fksa-a-r-noone-mx">
                                <span id="fksa-a-r-noone-txt" class="fksa-a-r-noone-txt jb-fksa-a-r-noone-txt">Aucun commentaire disponible</span>
                            </div>-->
                                <div id="fksa-a-r-m-list-list" class="jb-fksa-a-elt-m-list-list jb-fksa-a-r-m-list-list">
                                    <?php for($i=0;$i<0;$i++) :?>
                                         <div class="fksa-art-react-mdl jb-fksa-art-r-mdl" data-item="" data-cache="">
                                             <div class="fksa-a-r-m-header">
                                                 <a class="fksa-a-r-m-user" href="javascript:;" >
                                                     <img class="fksa-a-r-m-u-upic" src="http://www.lorempixel.com/50/50" />
                                                     <span class="fksa-a-r-m-u-upsd">@mouna</span>
                                                 </a>
                                                 <span class="fksa-a-r-m-time">hier</span>
                                             </div>
                                             <div class="fksa-a-r-m-contents-mx">
                                                 <span class="fksa-a-r-m-content">Texte</span>
                                             </div>
                                             <div class="fksa-a-r-m-footer jb-fksa-a-r-m-ftr">
                                                 <span class="fksa-a-r-m-ftr-del-mx">
                                                     <a class="fksa-a-r-m-f-dl-opt jb-fksa-a-r-m-f-dl-opt" data-action="fksa-del-r-start">Supprimer</a>
                                                     <span class="fksa-a-r-m-f-dl-opt-fnl-mx jb-fksa-a-r-m-f-dl-o-fnl-mx">
                                                         <span class="fksa-a-r-m-f-dl-fnl-o-lbl">Certain ?</span>
                                                         <a class="fksa-a-r-m-f-dl-fnl-o jb-fksa-a-r-m-f-dl-fnl-o" data-action="fksa-del-r-fnl-y">Oui</a>
                                                         <a class="fksa-a-r-m-f-dl-fnl-o jb-fksa-a-r-m-f-dl-fnl-o" data-action="fksa-del-r-fnl-n">Non</a>
                                                     </span>
                                                 </span>
                                             </div>
                                         </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div id="fksa-art-nw-rct-bmx">
                                <div id="fksa-art-nw-rct-mx">
                                    <div id="fksa-art-nw-rct-b-top">
<!--                                        <div id="fksa-art-nw-rct-b-t-ubx" class="jb-fksa-art-nw-rct-b-t-ubx">
                                            <img src="http://www.placehold.it/40/40" />
                                        </div>-->
                                        <div id="fksa-art-nw-rct-b-t-txta-mx" class="jb-fksa-art-nw-rct-b-t-txta-mx">
                                            <textarea id="fksa-art-nw-rct-b-t-txta" class="jb-fksa-art-nw-rct-b-t-txta" maxlength="1000" placeholder="Écrire un commentaire ..."></textarea>
                                        </div>
                                    </div>
                                    <div id="fksa-art-nw-rct-b-btm" class="clearfix">
<!--                                        <label id="fksa-art-nw-rct-b-vwe-lbl">
                                            <input id="fksa-art-nw-rct-b-vwe-ipt" type="checkbox" checked="true">
                                            <span>Appuyez sur Entrée pour ajouter</span>
                                        </label>-->
                                        <?php if ( strtoupper($pgvr) === "WLC" ) : ?>
                                        <a id="fksa-art-nw-rct-b-b-send" href="/login">Réagir</a>
                                        <?php // elseif ( $hasfv ) : ?>
                                        <?php else : ?>
                                        <a id="fksa-art-nw-rct-b-b-send" class="jb-fksa-art-nw-rct-b-b-send" data-action="fksa_add_r" href="javascript:;">Réagir</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div id="fksa-art-evl-mx" class="jb-fksa-art-evl-mx jb-fksa-mod-mx this_hide" data-scp="fksa_evaluations">-->
                    <div id="fksa-art-evl-mx" class="jb-fksa-art-evl-mx jb-fksa-mod-mx " data-scp="fksa_evaluations">
                        <div id="fksa-a-ev-m-header">
                            <span id="fksa-a-ev-m-h-rnb-mx" class="jb-fksa-elt-exdnb-mx jb-fksa-art-evnb-mx">
                                <span class="fksa-elt-nb jb-fksa-elt-nb jb-fksa-art-evnb">{wos/datx:art_eval_nb}</span>
                                <span class="fksa-elt-lib">appréciations</span>
                            </span>
                        </div>
                        <div id="fksa-a-ev-m-evalbx" class="jb-fksa-a-ev-m-evalbx">
                            <?php if ( $ia ) : ?>
                            <a class="fksa-a-ev-m-evbx-ax jb-fksa-a-ev-m-evbx-ax" data-action="eval-supacool" data-state="<?php echo ( !empty($me) && $me === "p2" ) ? "me" : ""; ?>" data-sty-state="<?php echo ( !empty($me) && $me === "p2" ) ? "me" : ""; ?>" href="javascript:;">
                                <span>J'aime beaucoup</span>
                                <sup class="fksa-a-ev-m-evbx-ax-nb jb-fksa-a-ev-m-evbx-ax-nb">(<?php echo ( !empty($evlds[0]) && is_int(intval($evlds[0])) && intval($evlds[0]) > 0 ) ? $evlds[0] : 0; ?>)</sup>
                            </a>
                            <a class="fksa-a-ev-m-evbx-ax jb-fksa-a-ev-m-evbx-ax" data-action="eval-cool" data-state="<?php echo ( !empty($me) && $me === "p1" ) ? "me" : ""; ?>" data-sty-state="<?php echo ( !empty($me) && $me === "p1" ) ? "me" : ""; ?>" href="javascript:;">
                                <span>cool</span>
                                <sup class="fksa-a-ev-m-evbx-ax-nb jb-fksa-a-ev-m-evbx-ax-nb">(<?php echo ( !empty($evlds[1]) && is_int(intval($evlds[1])) && intval($evlds[1]) > 0 ) ? $evlds[1] : 0; ?>)</sup>
                            </a>
                            <a class="fksa-a-ev-m-evbx-ax jb-fksa-a-ev-m-evbx-ax" data-action="eval-dislike" data-state="<?php echo ( !empty($me) && $me === "m1" ) ? "me" : ""; ?>" data-sty-state="<?php echo ( !empty($me) && $me === "m1" ) ? "me" : ""; ?>" href="javascript:;">
                                <span>J'adhère pas</span>
                                <sup class="fksa-a-ev-m-evbx-ax-nb jb-fksa-a-ev-m-evbx-ax-nb">(<?php echo ( !empty($evlds[2]) && is_int(intval($evlds[2])) && intval($evlds[2]) > 0 ) ? $evlds[2] : 0; ?>)</sup>
                            </a>
                            <?php else : ?>
                            <a class="fksa-a-ev-m-evbx-ax jb-irr-wchlop" href="javascript:;" >
                                <span>J'aime beaucoup</span>
                                <sup class="fksa-a-ev-m-evbx-ax-nb jb-fksa-a-ev-m-evbx-ax-nb">(<?php echo ( !empty($evlds[0]) && is_int(intval($evlds[0])) && intval($evlds[0]) > 0 ) ? $evlds[0] : 0; ?>)</sup>
                            </a>
                            <a class="fksa-a-ev-m-evbx-ax jb-irr-wchlop" href="javascript:;" >
                                <span>cool</span>
                                <sup class="fksa-a-ev-m-evbx-ax-nb jb-fksa-a-ev-m-evbx-ax-nb">(<?php echo ( !empty($evlds[1]) && is_int(intval($evlds[1])) && intval($evlds[1]) > 0 ) ? $evlds[1] : 0; ?>)</sup>
                            </a>
                            <a class="fksa-a-ev-m-evbx-ax jb-irr-wchlop" href="javascript:;" >
                                <span>J'adhère pas</span>
                                <sup class="fksa-a-ev-m-evbx-ax-nb jb-fksa-a-ev-m-evbx-ax-nb">(<?php echo ( !empty($evlds[2]) && is_int(intval($evlds[2])) && intval($evlds[2]) > 0 ) ? $evlds[2] : 0; ?>)</sup>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div id="fksa-a-ev-m-list-mx" class="jb-fksa-a-ev-m-list-mx this_hide">
                            <!--
                            <div id="fksa-a-ev-m-list-charts-bmx" class="jb-fksa-a-ev-m-list-charts-bmx">
                                <div id="fksa-a-ev-m-lst-chrts-tgr-mx" class="jb-fksa-a-ev-m-lst-chrts-tgr-mx">
                                    <a id="fksa-a-ev-m-lst-chrts-tgr" class="jb-fksa-a-ev-m-lst-chrts-tgr" data-action="fksa_evaluations_charts" href="javascript:;"></a>
                                </div>
                                <div id="fksa-a-ev-m-lst-chrts-mx" class="jb-fksa-a-ev-m-lst-chrts-mx this_hide">
                                    <div id="fksa-a-ev-m-lst-chrts-clz-mx" class="jb-fksa-a-ev-m-lst-chrts-clz-mx">
                                        <a id="fksa-a-ev-m-lst-chrts-clz" class="jb-fksa-a-ev-m-lst-chrts-clz" data-action="fksa_evaluations_charts" href="javascript:;">&times;</a>
                                    </div>
                                    <div class="fksa-a-ev-m-lst-chrts-evlmnt jb-fksa-a-ev-m-lst-chrts-evlmnt" data-scp="spcl">
                                        <span class="fksa-a-ev-m-lst-chrts-evlmnt-lgo jb-fksa-a-ev-m-lst-chrts-evlmnt-lgo" data-scp="spcl"></span>
                                        <span class="fksa-a-ev-m-lst-chrts-evlmnt-nb jb-fksa-a-ev-m-lst-chrts-evlmnt-nb" data-scp="spcl"><?php echo ( !empty($evlds[0]) && is_int(intval($evlds[0])) && intval($evlds[0]) > 0 ) ? $evlds[0] : 0; ?></span>
                                    </div>
                                    <div class="fksa-a-ev-m-lst-chrts-evlmnt jb-fksa-a-ev-m-lst-chrts-evlmnt" data-scp="cl">
                                        <span class="fksa-a-ev-m-lst-chrts-evlmnt-lgo jb-fksa-a-ev-m-lst-chrts-evlmnt-lgo" data-scp="cl"></span>
                                        <span class="fksa-a-ev-m-lst-chrts-evlmnt-nb jb-fksa-a-ev-m-lst-chrts-evlmnt-nb" data-scp="cl"><?php echo ( !empty($evlds[1]) && is_int(intval($evlds[1])) && intval($evlds[1]) > 0 ) ? $evlds[1] : 0; ?></span>
                                    </div>
                                    <div class="fksa-a-ev-m-lst-chrts-evlmnt jb-fksa-a-ev-m-lst-chrts-evlmnt" data-scp="dslk">
                                        <span class="fksa-a-ev-m-lst-chrts-evlmnt-lgo jb-fksa-a-ev-m-lst-chrts-evlmnt-lgo" data-scp="dslk"></span>
                                        <span class="fksa-a-ev-m-lst-chrts-evlmnt-nb jb-fksa-a-ev-m-lst-chrts-evlmnt-nb" data-scp="dslk"><?php echo ( !empty($evlds[2]) && is_int(intval($evlds[2])) && intval($evlds[2]) > 0 ) ? $evlds[2] : 0; ?></span>
                                    </div>
                                </div>
                            </div>
                            -->
                            <div id="fksa-a-ev-spnr-mx" class="jb-fksa-a-ev-spnr-mx">
                                <img id="fksa-a-ev-spnr" class="jb-fksa-a-ev-spnr jb-fksa-a-spnr this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                            </div>
                            <div id="fksa-a-ev-m-list-list" class="jb-fksa-a-elt-m-list-list jb-fksa-a-ev-m-list-list">
                                <?php for($i=0;$i<0;$i++) :?>
     <!--                                <div class="fksa-art-react-mdl jb-fksa-art-eval-mdl">
                                         <div class="fksa-a-r-m-header">
                                             <a class="fksa-a-r-m-user" href="javascript:;">
                                                 <img class="fksa-a-r-m-u-upic fksa-a-ev-m-u-upic" src="http://www.lorempixel.com/30/30" />
                                                 <span class="fksa-a-r-m-u-upsd fksa-a-ev-m-u-upsd">@Mouna</span>
                                             </a>
                                             <span class="fksa-a-r-m-time fksa-a-ev-m-time">hier</span>
                                         </div>
                                         <div class="fksa-a-e-m-contents-mx">
                                             <span class="fksa-a-r-m-content"><b class="fksa-a-r-m-ctt-action">a beaucoup aimé</b> cette publication.</span>
                                         </div>
                                     </div>
                                     <div class="fksa-art-react-mdl">
                                         <div class="fksa-a-r-m-header">
                                             <a class="fksa-a-r-m-user" href="javascript:;">
                                                 <img class="fksa-a-r-m-u-upic" src="http://www.lorempixel.com/30/30" />
                                                 <span class="fksa-a-r-m-u-upsd">@Mouna</span>
                                             </a>
                                             <span class="fksa-a-r-m-time">hier</span>
                                         </div>
                                         <div class="fksa-a-e-m-contents-mx">
                                             <span class="fksa-a-r-m-content"><b>a apprécié</b> cette publication.</span>
                                         </div>
                                     </div>
                                     <div class="fksa-art-react-mdl">
                                         <div class="fksa-a-r-m-header">
                                             <a class="fksa-a-r-m-user" href="javascript:;">
                                                 <img class="fksa-a-r-m-u-upic" src="http://www.lorempixel.com/30/30" />
                                                 <span class="fksa-a-r-m-u-upsd">@Mouna</span>
                                             </a>
                                             <span class="fksa-a-r-m-time">hier</span>
                                         </div>
                                         <div class="fksa-a-e-m-contents-mx">
                                             <span class="fksa-a-r-m-content"><b>a évalué négativement</b> cette publication.</span>
                                         </div>
                                     </div>-->
                                <?php endfor; ?>
                            </div>    
                        </div>
                    </div>
                </div>
               <div id="fksa-art-infos-mx">
                    <div id="fksa-art-inf-up-wpr">
                        <div id="fksa-art-userbox-mx">
                            <div id="fksa-art-userbox">
                                <a id="fksa-art-ubx-click" href="/@{wos/datx:art_opsd}" title="{wos/datx:art_ofn}" alt="Lien pour visiter le compte de {wos/datx:art_ofn}">
                                    <span id="fksa-art-ubx-upic-fade"></span>
                                    <img id="fksa-art-ubx-upic" height="70" width="70" src="{wos/datx:art_oppic}" />
                                    <span id="fksa-art-ubx-upsd">@{wos/datx:art_opsd}</span>
                                </a>
                            </div>
                            <div id="fksa-art-infos">
                                <span class="kxlib_tgspy fksa-tm" data-tgs-crd='{wos/datx:art_creadate}' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                    <span class='tgs-frm'></span>
                                    <span class='tgs-val'></span>
                                    <span class='tgs-uni'></span>
                                </span>
                            </div>
                        </div>
                        <p id="fksa-art-desc" class="jb-fksa-art-desc this_invi">{wos/datx:art_desc}</p>
                    </div>
                    <div id="fksa-art-sample-mx">
                        <?php if ( $so ) : ?>
                        <!--<div id="fksa-art-sample-tle">Dans la même Tendance :</div>-->
                        <div id="fksa-art-sample-tle">{wos/deco:_PG_FKSA_TX005} :</div> 
                        <?php else : ?>
                        <div id="fksa-art-sample-tle">{wos/deco:_PG_FKSA_TX005} :</div>
                        <?php endif; ?>
                        <div id="fksa-a-s-mx-spn-mx" class="jb-fksa-a-s-mx-spn-mx">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <div id="fksa-art-smpl-list" class="jb-fksa-art-smpl-list this_hide">
                            <?php for($i=0;$i<0;$i++) :?>
                             <div class="fksa-arfksa-a-r-m-list-mxt-smpl-bx">
                                 <a class="fksa-art-smpl-bx-iwpr" href="">
                                      <span class="fksa-art-smpl-bx-i-fade"></span>
                                      <img class="fksa-art-smpl-bx-img" src="http://www.lorempixel.com/75/75/nature/<?php echo rand(1,10); ?>" height="75"/>
                                 </a>
                             </div>
                             <?php endfor; ?>
                        </div>
                        <?php if (! $ia ) : ?>
                        <div id="fksa-art-s-mr-mx" class="jb-fksa-art-s-mr-mx this_hide">
                            <a id="fksa-art-s-mr-hf" class="" href="{wos/datx:trd_href}" title="Accéder à plus de publications et d'actualités du même auteur ou de la même Tendance">
                            <!--<a id="fksa-art-s-mr-hf" class="jb-fksa-art-s-mr-hf" href="{wos/datx:trd_href}" title="Cliquez pour accéder à plus de publications et d'actualités">-->
                                <i class="fa fa-book"></i>
                                <span>{wos/deco:_PG_FKSA_TX006}</span>
                            </a>
                        </div>
                        <!-- [DEPUIS 18-06-16]
<!--                         <div id="fksa-art-infos-btm">
                             <a class="fksa-art-i-cnxsng-btn" href="/connexion" role="button">Se connecter</a>
                             <a class="fksa-art-i-cnxsng-btn" href="/inscription" role="button">S'inscrire</a>
                         </div>-->
                        <div id="fksa-left-asd-mx">
                            <div id="fksa-left-asd-tle">
                                {wos/deco:_PG_FKSA_TX007}
                            </div>
                            <div>
                                <img class="" src="http://www.placehold.it/300x250" alt="">
<!--                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:300px;height:250px"
                                     data-ad-client="ca-pub-7028578741126541"
                                     data-ad-slot="1874726916"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>-->
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
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
                                    <!--Trenqr ce sont des photos, des amis, du fun mais pas seulement. C'est la nouvelle communauté coo<i>!</i> et #Tendance d'internet-->
                                    {wos/deco:_PG_FKSA_TX010}
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
                       <h2 id="fksa-smr-hdr-tle">{wos/deco:_PG_FKSA_TX009}</h2>
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
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf jb-fksa-art-legal-hrf last" href="javascript:;">Retirer la Publicité ?</a></li>
                        <?php else : ?>
                        <li class="fksa-art-legal"><a class="fksa-art-legal-hrf last" href="/cookies">Cookies</a></li>
                       <?php endif; ?>
                        <!--<li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="javascript:;">Trenqr Vibe</a></li>-->
                   </ul>
               </div>
           </div>
       </div>
    </div>
           
    {wos/dvt:whyacc}
   
   <!-- DO NOT REMOVE : LISTENERS DE LIAISON POUR CERTAINS CAS PARICULIER  -->
    <span class="jb-tqr-lstnr-onev" data-scp="afv"></span>
    
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
   
    <div id="requirejs">
        <script data-main="{wos/sysdir:script_dir_uri}/r/ix/main.fksa.js" src="{wos/sysdir:script_dir_uri}/r/c.c/require.js"></script>
    </div>
   
</div>