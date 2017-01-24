<body>
        {wos/dvt:header_default}
        <div id="rec_screensize">
            <div class="rec_content" id="rec_content1">
                <div class="rec_content_text">
                    <p>Vous avez oubli&eacute; votre mot de passe ? Pas de panique !</p>
                </div>
                <div id="rec_form_wrapper">
                    <div id="rec_form_error"></div>
                    <form id="rec_form" method="POST" action="index.php?page=recovery2&urqid=rec2">
                        <div id="rec_label_wrapper">
                            <label id="rec_form_label" for="rec_form_email">Entrez votre adresse email :</label>
                        </div>
                        <div id="rec_email_wrapper">
                            <div id='rec_spinner'></div>
                            <input type="text" required="true" name="email" id="rec_form_email">
                        </div>
                        <div id="rec_submit_wrapper">
                            <input type="submit" id="rec_form_submit" value="Envoyer">
                        </div>
                        <span class="clear"></span>
                    </form>
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
        <script src="{wos/sysdir:script_dir_uri}/w/d/rec_validator.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/d/rec_ajax_validation.d.js?{wos/systx:now}"></script>
    </body>