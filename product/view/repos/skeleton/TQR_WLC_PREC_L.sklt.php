<div s-id="TQR_WLC_REC_L">
        {wos/dvt:header_default}
        {wos/csam:notify_ua}
        <div id="rec_screensize">
            <div id="for-gen-ftr-wraper" style="width: 550px;">
                <div id="rec_content1" class="rec_content jb-rec-content">
                    <div class="rec_content_text">
                        <p>Vous avez oubli&eacute; votre mot de passe ? Pas de panique !</p>
                    </div>
                    <div id="rec_form_wrapper">
                        <div id="rec_form_error" class="jb-rec-form-err"></div>
                        <form id="rec_form" class="jb-rec-form" method="POST" action="">
                            <div id="rec_label_wrapper">
                                <label id="rec_form_label" class="" for="rec_form_email">Entrez votre adresse email :</label>
                                <span id="rec-spnr" class="jb-rec-spnr this_hide"><img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" /></span>
                            </div>
                            <div id="rec_email_wrapper">
                                <div id='rec_spinner'></div>
                                <input id="rec_form_email" class="jb-rec-form-email" type="text" name="email" placeholder="" required="true">
                            </div>
                            <div id="rec_submit_wrapper">
                                <input id="rec_form_submit" class="jb-rec-form-submit" type="submit" value="Envoyer">
                            </div>
                            <span class="clear"></span>
                        </form>
                    </div>
                </div>
                <div id="rec_content2" class="rec_content jb-rec-content-final this_hide">
                    <p class="rec_content_text">Ok, on s&apos;en charge !</p>
                    <div id="rec-ctt2-conf">
                        <p id="rec-ctt2-conf-text">
                            Un email contenant les instructions vient de vous être envoyé.
                        </p>
                        <p id="rec-ctt2-conf-notice">
                            Si vous n'avez pas re&ccedil;u l'email d&apos;ici un quart d'heure, pensez à vérifier dans votre dossier SPAMS sinon réessayez.
                        </p>
                    </div>
                    <div id="rec_mailSelector">
                        <a id="rec_mailRedirect" class="jb-rec-mailredir" href=""><b class="jb-rec-mv">Allez vers </b><span class="jb-rec-gomail"></span></a>
                        <span class="clear"></span>
                    </div>
                </div>
                {wos/dvt:footer_default}
            </div>
        </div>
        
        <!-- JS LOAD -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/notify.csam.js?{wos/systx:now}"></script>
        
        <script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/s/p_rec.s.js?{wos/systx:now}"></script>
</div>

    