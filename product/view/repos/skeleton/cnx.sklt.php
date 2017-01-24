<body>
    {wos/dvt:header_default}
    <div id="cnx_screensize">
        <div id="cnx_space_taken">
            <div id="cnx_content">
                <div id="cnx_title"><span>Connexion</span></div>
                <div id="cnx_msg" class="std jb-cn-hdr-mx">
                    <p class="jb-cnx-h-txt">Bienvenue sur Trenqr !</p>
                    <span class="clear"></span>
                </div>
                <div id="cnx_form_error"><span class="clear"></span></div>
                <div id="cnx_form_wrapper">
                    <form id="cnx_form" class="jb-cnx-form-mx" method="POST" action="index.php?page=signin&urqid=cp_cnxBuilder">
                        <div class="cnx_grp">
                            <!--<div id='cnx_login_spinner'></div>-->
                            <div class="cnx_label"><label for="cnx_form_login_input">Pseudo ou Email</label></div>
                            <input id="cnx_form_login_input" class="cnx_input jb-cnx-login" type="text" name="cnx_form_login_input" required>
                            <span class="jb-cnx-spnr this_hide"><img src="{wos/sysdir:img_dir_uri}/ld_16.gif" /></span>
                        </div>
                        <div class="cnx_grp">
                            <div class="cnx_label"><label name="cnx_form_passwd_input" for="cnx_form_passwd_input">Mot de passe</label></div>
                            <input id="cnx_form_passwd_input" class="cnx_input jb-cnx-pass" type="password" name="cnx_form_passwd_input" required>
                            <div id="cnx_forgotten_pw"><a href="http://www.trenqr.com/forrest/index.php?page=recuperation&urqid=rec">Mot de passe oubli&eacute; ?</a></div>
                        </div>
                        <input style='display: none;' type="text" name="cnx_locktype" id='cnx_locktype'>
                        <input style='display: none;' type="text" name="cnx_tod" id='cnx_tod'>
                        <div id="cnx_submit_wrapper">
                            <input id="cnx_submit" class="jb-cnx-submit" type="submit"value="Connexion">
                        </div>
                    </form>
                </div>
            </div>
            <div id="cnx_sidebar">
                <div id="cnx_signin">
                    <a id="cnx_signin_btn" href="http://www.trenqr.com/forrest/index.php?page=signup&urqid=ins">S&apos;inscrire</a>
                </div>          
            </div>
        </div>
        {wos/dvt:footer_default}
    </div>
    <!-- JS LOAD -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/csam/notify.csam.js?{wos/systx:now}"></script>

    <script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/s/cnx.s.js?{wos/systx:now}"></script>
    
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/cnx_validator.d.js?{wos/systx:now}"></script>-->
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/cnx_ajax_validation.d.js?{wos/systx:now}"></script>-->
</body>