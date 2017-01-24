<body>
        {wos/dvt:header_default}
        <div id="recch_screensize">
            <div id="recch_content">
                <div id="recch_content_text">
                    <p>Veuillez renseigner votre nouveau mot de passe.</p>
                </div>
                <div id="recch_form_wrapper">
                    <div id="recch_form_error"></div>
                    <form id="recch_form" method="POST" action="index.php?page=recovery_change&urqid=rec4">
                        <input type="text" style="display: none;" name="recch_rd" id="recch_rd">
                        <div class="recch_group_wrapper">
                            <div class="recch_label_wrapper">
                                <label class="recch_form_label" for="recch_passwd">Entrez votre nouveau mot de passe :</label>
                            </div>
                            <div class="recch_input_wrapper">
                                <input type="password" class="recch_form_input" id='recch_passwd'>
                            </div>
                        </div>
                        <div class="recch_group_wrapper">
                            <div class="recch_label_wrapper">
                                <label class="recch_form_label" for="recch_passwd_conf">Confirmez votre nouveau mot de passe :</label>
                            </div>
                            <div class="recch_input_wrapper">
                                <input type="password" class="recch_form_input" id='recch_passwd_conf'>
                            </div>
                        </div>
                        <div id="recch_submit_wrapper">
                            <input type="submit" id="recch_form_submit" value="Envoyer">
                        </div>
                        <span class="clear"></span>
                    </form>
                    <!--<div id="recch_cancel_div">Vous avez retrouvé votre mot de passe ? <a id="recch_cancel_link" href="http://www.trenqr.com">Annuler</a> la réinitialisation.</div>-->
                </div>
            </div>
            {wos/dvt:footer_default}
        </div>
        
        
        <!-- JS LOAD -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/d/recch_validator.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/d/recch_ajax_validation.d.js?{wos/systx:now}"></script>
    </body>