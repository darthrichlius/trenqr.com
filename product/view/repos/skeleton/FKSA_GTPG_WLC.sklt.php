<div s-id="FKSA_GTPG_WLC" style="height: 100%; width: 100%;">
   {wos/csam:notify_ua}
   <div id="fksa-header">
       <div id="fksa-h-logo-mx">
           <a id="header-logo" style="background: url('{wos/sysdir:img_dir_uri}/w/logo_tqr_final.png?v=<?php echo time(); ?>') no-repeat; background-size: 90%;" href="/"></a>
       </div>
       <div id="fksa-h-home">
           {wos/dvt:welcbox}
       </div>
   </div>
   <div id="fksa-screen">
       <div id="fksa-art-dialbox-sprt" class="this_hide">
           <div id="fksa-art-dialbox-mx">
               <a id="fksa-art-dlgbx-clz" href="javascript:;"></a>
               <div id="fksa-art-dlgbx-hdr">
                   <span id="fksa-art-dlgbx-hdr-ms">Etes-vous sûr de vouloir supprimer définitivement cette publication ?</span>
               </div>
               <div id="fksa-art-dlgbx-bdy">
                   <a class="fksa-art-dlgbx-bdy-chc" data-action="confirm_delart" href="javascript:;">Oui</a>
               </div>
           </div>
       </div>
       <div id="fksa-article" class="jb-fksa-article" data-item="{wos/datx:art_eid}" data-rnb="{wos/datx:art_rnb}" data-enb="{wos/datx:art_eval_nb}">
           <div id="fksa-art-header">
               <?php    
                    $so = NULL;
                    set_error_handler('exceptions_error_handler');
                    try {
                        $so = "{wos/datx:istr}";
                        restore_error_handler();
                    } catch (Exception $exc) {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);

                    }
                    
                    if ( $so ) :
                ?>
               <span class="fksa-art-qtr-bdg">Trend<i>!</i></span>
               <a class="fksa-art-qtr-tle jb-fksa-art-qtr-tle" href="{wos/datx:trd_href}" title="{wos/datx:trd_title}">{wos/datx:trd_title}</a>
               <?php endif; ?>
           </div>
           <div id="fksa-art-center" class="clearfix">
               <div id="fksa-art-infos-mx">
                   <div id="fksa-art-userbox-mx">
                       <div id="fksa-art-userbox">
                           <a id="fksa-art-ubx-click" href="/@{wos/datx:art_opsd}" title="{wos/datx:art_ofn}" alt="Lien pour visiter le compte de {wos/datx:art_ofn}">
                               <span id="fksa-art-ubx-upic-fade"></span>
                               <img id="fksa-art-ubx-upic" height="70" height="70" src="{wos/datx:art_oppic}" />
                               <span id="fksa-art-ubx-upsd">@{wos/datx:art_opsd}</span>
                           </a>
                       </div>
                       <div id="fksa-art-infos">
                           
                       </div>
                   </div>
                   <p id="fksa-art-desc">{wos/datx:art_desc}</p>
               </div>
               <div id="fksa-art-ctr-menu">
                   <div>
                       <?php for($i=0;$i<7;$i++) :?>
<!--                   <div class="fksa-art-porto-mx this_hide">
                       <div class="fksa-art-porto">
                           <img class="fksa-art-porto-pic" src="http://www.lorempixel.com/60/60" />
                       </div>
                   </div>-->
                        <?php endfor; ?>
                   </div>
                   
                   <div id="fksa-art-menu-choices">
                       <a class="fksa-a-mn-choice jb-fksa-a-mn-choice" href="javascript:;" title="Cliquez pour en savoir plus" data-action="fksa_evaluations">
                           <span class="fksa-a-mn-ch-dga"></span>
                           <!--<span class="fksa-a-mn-ch-selected-pin jb-fksa-a-mn-ch-selected-pin"></span>-->
                           <img class="fksa-a-mn-ch-lg" width="40px" src="{wos/sysdir:img_dir_uri}/r/heart_w.png?v=<?php echo time(); ?>"/>
                           <span class="fksa-a-mn-ch-nb">{wos/datx:art_eval_nb}</span>
                       </a>
                       <a class="fksa-a-mn-choice jb-fksa-a-mn-choice" href="javascript:;" title="Cliquer pour voir les commentaires" data-action="fksa_reactions">
                           <span class="fksa-a-mn-ch-dga"></span>
                           <img class="fksa-a-mn-ch-lg" width="45px" src="{wos/sysdir:img_dir_uri}/r/bbl_w.png?v=<?php echo time(); ?>"/>
                           <span class="fksa-a-mn-ch-nb">{wos/datx:art_rnb}</span>
                       </a>
                   </div>
               </div>
               <div id="fksa-art-ctr-img-mx">
                   <span id="fksa-art-ctr-img-ga"></span>
                   <img id="fksa-art-ctr-img" height="640px" width="640px" src="{wos/datx:art_pdpic_path}?v={wos/systx:now}" alt="{wos/datx:art_desc}"/>
               </div>
               <div id="fksa-ads-asd-ban-mx">
                   <div id="fksa-ads-asd-ban">
                       <a href="javascript:;">
                           <img class="" src="http://www.placehold.it/160x600" />
                       </a>
                   </div>
<!--                   <div id="fksa-ads-asd-ban">
                       <a href="javascript:;">
                           <img class="" src="http://www.placehold.it/300x600" />
                       </a>
                   </div>-->
               </div>
           </div>
           <div id="fksa-art-footer">
               <div id="fksa-art-rct-mx" class="jb-fksa-art-rct-mx jb-fksa-mod-mx this_hide" data-scp="fksa_reactions">
               <!--<div id="fksa-art-rct-mx" class="jb-fksa-art-rct-mx jb-fksa-mod-mx this_hide" data-scp="fksa_reactions">-->
                   <div id="fksa-a-r-m-header">
                       <span id="fksa-a-r-m-h-rnb-mx" class="jb-fksa-elt-exdnb-mx jb-fksa-art-rnb-mx">
                           <span class="fksa-elt-nb jb-fksa-elt-nb jb-fksa-art-rnb">0</span>
                           <span class="fksa-elt-lib">commentaires</span>
                       </span>
                   </div>
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
                                </div>
                           <?php endfor; ?>
                       </div>    
                   </div>
               </div>
               <div id="fksa-art-evl-mx" class="jb-fksa-art-evl-mx jb-fksa-mod-mx this_hide" data-scp="fksa_evaluations">
                   
                   <div id="fksa-a-ev-m-header">
                       <span id="fksa-a-ev-m-h-rnb-mx" class="jb-fksa-elt-exdnb-mx jb-fksa-art-evnb-mx">
                           <span class="fksa-elt-nb jb-fksa-elt-nb jb-fksa-art-evnb">0</span>
                           <span class="fksa-elt-lib">appreciations</span>
                       </span>
                   </div>
                   <div id="fksa-a-ev-m-list-mx">
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
               <div id="fksa-art-legals-mx">
                   <div id="fksa-art-legals-brand-mx">
                       <div id="fksa-art-tqr">Trenqr 2015</div>
                       <div id="fksa-art-lg">Français - France</div>
                   </div>
                   <ul id="fksa-art-legals-list">
                       <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/about">A Propos</a></li>
                       <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/terms">CGU</a></li>
                       <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/privacy">Confidentialité</a></li>
                       <li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="/cookies">Cookies</a></li>
                       <!--<li class="fksa-art-legal"><a class="fksa-art-legal-hrf" href="javascript:;">Trenqr Vibe</a></li>-->
                       <li class="fksa-art-legal"><a class="fksa-art-legal-hrf last" href="javascript:;">Retirer la Publicité ?</a></li>
                   </ul>
               </div>
           </div>
       </div>
   </div>
   
   <!-- JS LOAD -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/jquery.ui.datepicker-fr.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxdate.enty.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script> 
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/csam/timegod.csam.js?{wos/systx:now}"></script>
        
    <script src="{wos/sysdir:script_dir_uri}/r/s/fksa.s.js?{wos/systx:now}"></script> 
</div>