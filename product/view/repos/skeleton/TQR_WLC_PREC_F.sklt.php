<div s-id="TQR_WLC_PREC_F">
    {wos/dvt:header_default}
    {wos/csam:notify_ua}
    <div id="recch_screensize">
        <div id="for-gen-ftr-wraper" style="width: 550px;">
            <?php 
                $vmode = "{wos/datx:vmode}";
                if ( !isset($vmode) || ( isset($vmode) && strtoupper($vmode) !== "BAD_NEWS" ) ) :
            ?>
            <div id="recch_content" class="jb-recch-content">
                <div id="recch_content_text">
                    <p>Veuillez renseigner votre nouveau mot de passe</p>
                </div>
                <div id="recch_form_wrapper">
                    <div id="recch_form_error" class='jb-recch-err'></div>
                    <form id="recch_form" class="jb-recch-form" method="POST" action="">
                        <input id="recch_rd" type="text" name="recch_rd" style="display: none;">
                        <div class="recch_group_wrapper">
                            <div class="recch_label_wrapper">
                                <label class="recch_form_label" for="recch_passwd">Entrez votre nouveau mot de passe :</label>
                                <span id="recch-passwd-strengh"><b class="passwd_str_fill jb-pwd-strength"></b></span>
                            </div>
                            <div class="recch_input_wrapper">
                                <input id="recch_passwd" class="recch_form_input jb-recch-input jb-recch-pwd-input jb-recch-pwd-1st" type="password" required>
                            </div>
                        </div>
                        <div class="recch_group_wrapper">
                            <div class="recch_label_wrapper">
                                <label class="recch_form_label" for="recch_passwd_conf">Confirmez votre nouveau mot de passe :</label>
                            </div>
                            <div class="recch_input_wrapper">
                                <input id="recch_passwd_conf" class="recch_form_input jb-recch-input jb-recch-pwd-input jb-recch-pwd-2nd" type="password" required>
                            </div>
                        </div>
                        <div class="recch_group_wrapper">
                            <div class="recch_label_wrapper">
                                <label class="recch_form_label" for="recch_secret_code">Rentrez votre <b>code d'activation</b> :</label>
                            </div>
                            <div class="recch_input_wrapper">
                                <input id="recch-code" class="recch_form_input jb-recch-input  jb-recch-code" type="password" maxlength="6" required>
                            </div>
                        </div>
                        <div id="recch_submit_wrapper">
                            <input id="recch_form_submit" class="jb-recch-submit" type="submit" value="Envoyer">
                            <span id="recch-spnr" class="jb-recch-spnr this_hide"><img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" /></span>
                        </div>
                        <span class="clear"></span>
                    </form>
                    <!--<div id="recch_cancel_div">Vous avez retrouvé votre mot de passe ? <a id="recch_cancel_link" href="http://www.trenqr.com">Annuler</a> la réinitialisation.</div>-->
                </div>
            </div>
            <?php else : ?>
            <div id="recch-badnews-mx">
                <p id="recch-badnews-hdr">Outch !</p>
                <p id="recch-badnews-txt" class="">
                    Le code lié à ce lien a <b>expiré</b> ou il est tout simplement <b>invalide</b>.
                </p>
            </div>
            <?php endif; ?>
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