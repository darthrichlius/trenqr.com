<?php
    $so = $ia = $pgvr = NULL;
    set_error_handler('exceptions_error_handler');
    try {
        $ia = "{wos/datx:iauth}";
        $pgvr = "{wos/datx:pgakxver}";
        
//        $ustgs = unserialize(base64_decode("{wos/datx:art_ustgs}"));
//        $evlds = unserialize(base64_decode("{wos/datx:art_eval}"));
        
//        var_dump($so,$ia,$pgvr);
//        var_dump($evlds);
//        exit();
        restore_error_handler();
    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
    
?>
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
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

<div s-id="TQR_RCMDTO_GTPG" style="height: 100%; width: 100%;">
    {wos/csam:notify_ua}
   <?php if (! $ia ) : ?>
   {wos/csam:cnxsgn_ovly}
   <?php endif; ?>
    <div id="tqr-rcmd-header">
        <div id="tqr-rcmd-h-logo-mx">
           <a id="header-logo" style="background: url('{wos/sysdir:img_dir_uri}/w/logo_tqr_final.png?v=<?php echo time(); ?>') no-repeat; background-size: 90%;" href="/"></a>
        </div>
        <div id="tqr-rcmd-h-home">
           <?php if (! $ia ) : ?>
           {wos/dvt:welcbox}
           <?php else : ?>
           <a id="tqr-rcmd-h-home-hrf" href="{wos/datx:cuhref}" title="Aller vers mon compte sur Trenqr">Chez Moi</a>
           <?php endif; ?>
        </div>
    </div>
    <div id="tqr-rcmd-screen">
        <section class="tqr-rcmd-scn-center jb-tqr-rcmd-scn-center" data-section="main">
            <header class="tqr-rcmd-scn-ctr-tle">
                <?php if (! $ia ) : ?>
                <h1>Recommandez Trenqr à vos amis et votre famille !</h1>
                <?php else : ?>
                <h1>Invitez vos amis et votre famille sur Trenqr !</h1>
                <?php endif; ?>
            </header>
            <div class="tqr-rcmd-scn-ctr-sects">
                <section class="tqr-rcmd-scn-ctr-sect jb-tqr-rcmd-scn-ctr-sect" data-section="via-facebook">
                    <header>
                        <?php if (! $ia ) : ?>
                        <h2 class="tqr-rcmd-scn-ctr-sect-tle" data-section="via-facebook">Recommandez Trenqr à vos amis depuis <span class="socialname" data-section="facebook">Facebook</span></h2>
                        <?php else : ?>
                        <h2 class="tqr-rcmd-scn-ctr-sect-tle" data-section="via-facebook">Invitez vos amis depuis <span class="socialname" data-section="facebook">Facebook</span></h2>
                        <?php endif; ?>
                    </header>
                    <div class="tqr-rcmd-scn-ctr-sect-body" data-section="via-facebook">
                        <div class="tqr-rcmd-sect-vapi-txt">
                            <?php if (! $ia ) : ?>
                            Cliquez sur le bouton pour recommander Trenqr à vos amis sur Facebook.
                            <?php else : ?>
                            Cliquez sur le bouton pour demander à vos amis sur Facebook de vous rejoindre sur Trenqr.
                            <?php endif; ?>
                        </div>
                        <div class="tqr-rcmd-sect-vapi-btn-mx">
                            <div class="tqr-rcmd-sect-vapi-btn">
                                <div class="fb-share-button" data-href="http://beta.trenqr.com/" data-layout="button"></div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="tqr-rcmd-scn-ctr-sect jb-tqr-rcmd-scn-ctr-sect" data-section="via-twitter">
                    <header>
                        <?php if (! $ia ) : ?>
                        <h2 class="tqr-rcmd-scn-ctr-sect-tle" data-section="via-twitter">Recommandez Trenqr à vos contacts depuis <span class="socialname" data-section="twitter">Twitter</span></h2>
                        <?php else : ?>
                        <h2 class="tqr-rcmd-scn-ctr-sect-tle" data-section="via-twitter">Invitez vos contacts depuis <span class="socialname" data-section="twitter">Twitter</span></h2>
                        <?php endif; ?>
                    </header>
                    <div class="tqr-rcmd-scn-ctr-sect-body" data-section="via-twitter">
                        <div class="tqr-rcmd-sect-vapi-txt">
                            <?php if (! $ia ) : ?>
                            Cliquez sur le bouton pour recommander Trenqr à vos contacts sur Twitter.
                            <?php else : ?>
                            Cliquez sur le bouton pour demander à vos contacts sur Twitter de vous rejoindre sur Trenqr .
                            <?php endif; ?>
                        </div>
                        <div class="tqr-rcmd-sect-vapi-btn-mx">
                            <div class="tqr-rcmd-sect-vapi-btn">
                                <a class="twitter-share-button" 
                                    href="https://twitter.com/intent/tweet"
                                    data-url="//trenqr.com"
                                    data-size="large" 
                                    data-count="none" 
                                    data-hashtags=""
                                    data-text="Hey ! Rejoins-moi sur @Trenqr - www.trenqr.me : la #communauté #cool et #tendance d'internet en #photos et en #images, pour tous."
                                ></a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="tqr-rcmd-scn-ctr-sect jb-tqr-rcmd-scn-ctr-sect this_hide" data-section="err_panel">
                    <p id="tqr-rcmd-errpnl" class="jb-tqr-rcmd-errpnl">Outch ... Votre demande contient des erreurs, elle a été rejettée par le serveur.<br/>Assurez-vous de suivre les indications du formulaire puis réessayez.</p>
                </section>
                <!-- -->
                <section class="tqr-rcmd-scn-ctr-sect jb-tqr-rcmd-scn-ctr-sect" data-section="via-email">
                    <header>
                        <h2 class="tqr-rcmd-scn-ctr-sect-tle">
                            <?php if (! $ia ) : ?>
                            Recommander Trenqr à votre entourage par email
                            <?php else : ?>
                            Envoyer une invitation par email
                            <?php endif; ?>
                        </h2>
                        <div id="tqr-rcmd-sect-vem-txt">
                            <div>
                                Nous enverons un email à votre ami ou au membre de votre famille en votre nom.<br>
                            </div>
                            <div>
                                Vous pouvez inscrire plusieurs adresses email différentes dans la limite de 10 adresses par envoi. 
                            </div>
                        </div>
                    </header>
                   
                    <div class="tqr-rcmd-scn-ctr-sect-body">
                        <form id="tqr-rcmd-form-mx" method="">
                             <?php if (! $ia ) : ?>
                                <fieldset class="form-fldst" data-section="sender">
                                    <legend class="form-fldst-tle"><h3>Vous</h3></legend>
                                    <div>
                                        <label class="form-o-label">
                                            <span class="form-o-lbl-lbl">Nom complet <sup>*</sup></span>
                                            <input class="form-lbl-ipt jb-form-lbl-ipt" data-field="sndr_fn" type="text" required/>
                                        </label>
                                        <a class="form-lbl-ipt-info" title="Le nom complet doit avoir entre 2 et 30 caractères" href="javascript:;"><i class="fa fa-info-circle"></i></a>
                                    </div>
                                    <div class="form-fld-err-mx jb-form-fld-err-mx this_hide" data-field="sndr_fn">
                                        <span class="form-fld-err jb-form-fld-err">Erreur</span>
                                    </div>
                                    <div>
                                        <label class="form-o-label">
                                            <span class="form-o-lbl-lbl">Votre Email</span>
                                            <input class="form-lbl-ipt jb-form-lbl-ipt" data-field="sndr_eml" type="text" />
                                            <input class="form-lbl-ipt jb-form-lbl-ipt" data-field="sndr_eml" type="email" />
                                        </label>
                                    </div>
                                    <div class="form-fld-err-mx jb-form-fld-err-mx this_hide" data-field="sndr_eml">
                                        <span class="form-fld-err jb-form-fld-err">Erreur</span>
                                    </div>
                                </fieldset>
                            <?php else : ?>
                                <input class="form-lbl-ipt jb-form-lbl-ipt this_hide" data-field="sndr_eml" type="text" value="{wos/datx:cueml}" />
                                <input class="form-lbl-ipt jb-form-lbl-ipt this_hide" data-field="sndr_fn" type="text" value="{wos/datx:cupsd}" />
                            <?php endif; ?>
                            <fieldset class="form-fldst" data-section="recipient">
                                <legend class="form-fldst-tle"><h3>Le destinataire</h3></legend>
                                <div>
                                    <label class="form-o-label">
                                        <span class="form-o-lbl-lbl">Nom complet <sup>*</sup></span>
                                        <input class="form-lbl-ipt jb-form-lbl-ipt" data-field="rcpt_fn" type="text" required/>
                                    </label>
                                    <a class="form-lbl-ipt-info" title="Le nom complet doit avoir entre 2 et 30 caractères" href="javascript:;"><i class="fa fa-info-circle"></i></a>
                                    <span class="form-fld-err jb-form-fld-err"></span>
                                </div>
                                <div class="form-fld-err-mx jb-form-fld-err-mx this_hide" data-field="rcpt_fn">
                                    <span class="form-fld-err jb-form-fld-err">Erreur</span>
                                </div>
                                <div>
                                    <label class="form-o-label">
                                        <span class="form-o-lbl-lbl">Email <sup>*</sup></span>
                                        <span>
                                            <input class="form-lbl-ipt jb-form-lbl-ipt" data-field="rcpt_eml" type="text" />
                                            <input class="form-lbl-ipt jb-form-lbl-ipt" data-field="rcpt_eml" type="email" />
                                        </span>
                                        <a id="form-add-nw-eml" class="jb-form-add-nw-eml" data-action="add_nw_eml" href="javascript:;">Ajouter</a>
                                    </label>
                                    <span class="form-fld-err jb-form-fld-err this_hide"></span>
                                </div>
                                <div class="form-fld-err-mx jb-form-fld-err-mx this_hide" data-field="rcpt_eml">
                                    <span class="form-fld-err jb-form-fld-err">Erreur</span>
                                </div>
                                <ul id="tqr-rcmd-frm-lst-eml-mx" class="jb-tqr-rcmd-frm-lst-eml-mx">
                                    <li class="tqr-rcmd-frm-lst-eml jb-tqr-rcmd-frm-lst-eml" data-email="une.adresse.email@email.com">
                                        <span class="tqr-rcmd-lst-eml-addr jb-tqr-rcmd-lst-eml-addr" title="adresse email">une.adresse.email@email.com</span>
                                        <a class="tqr-rcmd-lst-eml-rmv jb-tqr-rcmd-lst-eml-rmv" data-action="del_eml" href="javascript:;" title="Retirer de la liste">&times;</a>
                                    </li>
                                </ul>
                            </fieldset>
                            <div id="tqr-rcmd-form-manda">
                                <sup>*</sup> Ces champs sont obligatoires
                            </div>
                            <div id="tqr-rcmd-form-captcha-mx">
                                <div class="g-recaptcha" data-sitekey="6LeA8Q0TAAAAAPqg7YU02r1qzm3X_UFjHEAB2mbk"></div>
                            </div>
                            <div id="tqr-rcmd-form-legals-mx">
                                <div id="tqr-rcmd-frm-lgls-mn">
                                    <label id="">
                                        <input id="" class="jb-tqr-rcmd-frm-lgls-ipt" data-target="cgu" type="checkbox" required>
                                        <span>J'accepte que mes données soit traitées conformément aux <a href="/terms">conditions g&eacute;n&eacute;rales d'utilisation</a> et je certifie avoir pris connaissance de la <a href="/privacy">politique de confidentialit&eacute;.</a></span>
                                    </label>
                                </div>
                            </div>
                            <div id="tqr-rcmd-form-fnl-mx">
                                <label id="tqr-rcmd-shw-sample">
                                    <input id="tqr-rcmd-see-smpl-ipt" class="jb-tqr-rcmd-see-smpl-ipt" data-target="sample" type="checkbox">
                                    <span id="tqr-rcmd-see-smpl-txt" class="">Visualiser avant d'envoyer</span>
                                </label>
                                <a id="form-reset" class="jb-tqr-rcmd-form-rst" data-action="reset_form" href="">Reinitialiser</a>
                                <input id="form-submit" class="jb-tqr-rcmd-form-sbmt" data-action="submit" type="submit" value="Recommander">
                            </div>
                        </form>
                    </div>
                </section>
                <!-- -->
<!--                <section class="tqr-rcmd-scn-ctr-sect jb-tqr-rcmd-scn-ctr-sect this_hide" data-section="wait_panel">
                    <p id="tqr-rcmd-wtpnl">Patientez ...<i class="fa fa-cog fa-spin"></i></p>
                </section>-->
            </div>
        </section>
        <section class="tqr-rcmd-scn-center jb-tqr-rcmd-scn-center this_hide" data-section="sample">
            <header class="tqr-rcmd-scn-ctr-tle">
                <h1>Visualisez l'email avant son envoi</h1>
                <div class="plus">Un email similaire sera envoyé à chaque destinataire</div>
            </header>
            <div class="tqr-rcmd-scn-ctr-sects">
                <section id="tqr-rcmd-scn-cfrm-bmx">
                    <div id="tqr-rcmd-scn-cfrm-mx">
                        <header id="tqr-rcmd-scn-cfrm-hdr-bmx">
                            <?php if ( $ia ) : ?>
                            <div id="tqr-rcmd-scn-cfrm-hdr-mx">
                                <div id="tqr-rcmd-scn-cfrm-ppic-mx">
                                    <a id="tqr-rcmd-scn-cfrm-nm-ix" href="javascript:;">
                                        <img id="tqr-rcmd-scn-cfrm-nm-i" width="80" height="80" src="{wos/datx:cuppic}" />
                                    </a>
                                </div>
                                <div id="tqr-rcmd-scn-cfrm-nm-mx">
                                    <a id="tqr-rcmd-scn-cfrm-nm" href="javascript:;">
                                        <div id="tqr-rcmd-scn-cfrm-psd">@{wos/datx:cupsd}</div>
                                        <div id="tqr-rcmd-scn-cfrm-fn">{wos/datx:cufn}</div>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </header>
                        <div id="tqr-rcmd-scn-cfrm-bdy-mx">
                            <div id="tqr-rcmd-scn-cfrm-txt">
                                <div id="tqr-rcmd-scn-cfrm-txt-hi">Bonjour <span class="rcpt_fn">Louna</span>,</div>
                                <div id="tqr-rcmd-scn-cfrm-txt-then">
                                    <p>
                                        <?php if (! $ia ) : ?>
                                        <span class="sender_fn" style="font-weight:bold;">Dupont Agile</span> vous invite à essayer <a href="javascript:;" style="color: #3c96a5 !important;">trenqr.com</a>. 
                                        <?php else : ?>
                                        <span class="sender_fn" style="font-weight:bold;">Dupont Agile</span> vous invite à le rejoindre sur <a href="javascript:;" style="color: #3c96a5 !important;">trenqr.com</a>. 
                                        <?php endif; ?>
                                        Il s'agit de la nouvelle communauté cool et tendance d'internet, qui vous permet de partager différemment votre quotidien en images, avec ceux qui vous sont proches, dans un environnement beau, ludique et convivial.
                                    </p>
                                    <p>Vous pourrez en apprendre plus sur Trenqr en cliquant <a href="javascript:;">ici</a>.</p>
                                </div>
                                <div id="tqr-rcmd-scn-cfrm-txt-hi">- <span class="sender_fn">Dupont Agile</span> -</div>
                            </div>
                            <!-- A RETIRER POUR LE MODELE SAMPLE -->
<!--                            <div id="tqr-rcmd-scn-cfrm-btns-mx">
                                <div>
                                    <a class="tqr-rcmd-scn-cfrm-btn" data-action="ja" href="javascript:;">Accepter l'invitation</a>
                                    <a class="tqr-rcmd-scn-cfrm-btn" data-action="nein" href="javascript:;">Refuser l'invitation</a>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <footer id="tqr-rcmd-scn-cfrm-ftr-mx">
                        <div id="tqr-rcmd-scn-cfrm-chcs">
                            <a class="tqr-rcmd-scn-cfrm-chc jb-tqr-rcmd-scn-cfrm-chc" data-action="back" href="javascript:;">Retour</a>
                            <a class="tqr-rcmd-scn-cfrm-chc jb-tqr-rcmd-scn-cfrm-chc" data-action="send" href="javascript:;">Envoyer</a>
                        </div>
                    </footer>
                </section>
            </div>
        </section>
        <div id="tqr-rcmd-legals-mx">
                <div id="tqr-rcmd-legals-brand-mx">
                    <div id="tqr-rcmd-tqr">Trenqr 2016</div>
                    <div id="tqr-rcmd-lg">Français - France</div>
                </div>
                <ul id="tqr-rcmd-legals-list">
                    <li class="tqr-rcmd-legal"><a class="tqr-rcmd-legal-hrf" href="/about">A Propos</a></li>
                    <li class="tqr-rcmd-legal"><a class="tqr-rcmd-legal-hrf" href="/terms">CGU</a></li>
                    <li class="tqr-rcmd-legal"><a class="tqr-rcmd-legal-hrf" href="/privacy">Confidentialité</a></li>
                    <li class="tqr-rcmd-legal"><a class="tqr-rcmd-legal-hrf" href="/cookies">Cookies</a></li>
                    <!--<li class="tqr-rcmd-legal"><a class="tqr-rcmd-legal-hrf last" href="javascript:;">Signaler un problème</a></li>-->
                </ul>
            </div>
   </div>
   <div id="tqr-js-declare">
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

        <script src="{wos/sysdir:script_dir_uri}/r/s/rcmd.s.js?{wos/systx:now}"></script> 
   </div>
</div>

