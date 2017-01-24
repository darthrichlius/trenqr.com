
<?php
    
    set_error_handler('exceptions_error_handler');
    try {
        
       /*
        * EMAIL_CONFIRMATION
        */
        $ec_is_ecofirm = "{wos/datx:ec_is_ecofirm}";
        $ec_state = "{wos/datx:ec_state}";
        $ec_scope = "{wos/datx:ec_scope}";
        $ec_is_ecofirm = ( $ec_is_ecofirm === '1' ) ? TRUE : FALSE;
        
//        var_dump(__LINE__,$ec_is_ecofirm,$ec_state,$ec_scope,$ec_is_ecofirm);
//        exit();
    
        restore_error_handler();

    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
            
?>

<div s-id="TQR_WLC_CNX">
    <div id="cnx-tdl-sprt" class="jb-cnx-tdl-sprt this_hide">
<!--        <div id="cnx-tdl-wait" class="jb-cnx-tdl-wait">
            <span class="jb-cnx-tdl-w-m">Patientez</span>
            <span>...</span>
        </div>-->
<!--        <div id="cnx-tdl-bdy-mx" class="jb-cnx-tdl-bdy-mx">
            <div>
                <div id="cnx-tdl-top">
                    <p id="cnx-tdl-msg">
                        Bonjour <b>%Nom Complet%</b>,<br/><br/>
                        Content de vous revoir ou presque ... Votre compte est actuellement en instance de suppression. 
                        Si vous décidez de vous reconnecter, <b>le processus de suppression sera définitvement annulé</b>.<br/>
                        Etes vous sur de vouloir poursuivre le processus ?
                    </p>
                </div>
                <div id="cnx-tdl-btm" class="clearfix2">
                    <div id="cnx-tdl-chcs-mx">
                        <a class="cnx-tdl-chcs cnx-tdl-chcs-ccl" data-action="cancel" href="">Annuler</a>
                        <a class="cnx-tdl-chcs cnx-tdl-chcs-git" data-action="wlcmback" href="">J'ai Compris</a>
                    </div>
                    <div id="cnx-tdl-chcs-mr-mx">
                        <a id="cnx-tdl-chcs-mr" href="">Plus d'informations</a>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
    {wos/dvt:header_default}
    <div id="cnx_screensize">
        <div id="for-gen-ftr-wraper" style="width: 750px;">
            {wos/dvt:econfirm}
            <div id="cnx-header-it-free">
                {wos/deco:_PG_LOGIN_TX001}
            </div>
            <div id="cnx_space_taken">
                <div id="cnx_content">
                    <div id="cnx_title"><span>{wos/deco:_PG_LOGIN_TX002}</span></div>
                    <div id="cnx_msg" class="std jb-cn-hdr-mx">
                        <p id="cnx-msg-wpr" class="jb-cnx-h-txt">{wos/deco:_PG_LOGIN_TX007}</p>
                        <span class="clear"></span>
                    </div>
                    <div id="cnx_form_error"><span class="clear"></span></div>
                    <div id="cnx_form_wrapper">
                        <!--<form id="cnx_form" class="jb-cnx-form-mx" method="POST" action="index.php?page=signin&urqid=cp_cnxBuilder">-->
                        <form id="cnx_form" class="jb-cnx-form-mx" method="POST" action="" autocomplete="off">
                            <div class="cnx_grp">
                                <!--<div id='cnx_login_spinner'></div>-->
                                <div class="cnx_label"><label for="cnx_form_login_input">{wos/deco:_PG_LOGIN_TX003}</label></div>
                                <input id="cnx_form_login_input" class="cnx_input jb-cnx-login" type="text" name="cnx_form_login_input" autocomplete="on" spellcheck="false" tabindex="1" required>
                                <span id="cnx-spnr" class="jb-cnx-spnr this_hide"><img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" /></span>
<!--                                <div id="cnx-rmbr-mx">
                                    <label id="cnx-rmbr-lbl">
                                        <input id="cnx-rmbr-ipt" class="jb-cnx-rmbr-ipt" type="checkbox" name="remember" />
                                        <span>Connexion automatique&nbsp;</span>
                                    </label>
                                </div>-->
                            </div>
                            <div class="cnx_grp">
                                <div class="cnx_label"><label name="cnx_form_passwd_input" for="cnx_form_passwd_input">{wos/deco:_PG_LOGIN_TX004}</label></div>
                                <input id="cnx_form_passwd_input" class="cnx_input jb-cnx-pass" type="password" name="cnx_form_passwd_input" autocomplete="off" spellcheck="false"tabindex="1" required>
                                <div id="cnx_forgotten_pw"><a href="/recovery/password">{wos/deco:_PG_LOGIN_TX005}</a></div>
                            </div>
                            <input style='display: none;' type="text" name="cnx_locktype" id='cnx_locktype'>
                            <input style='display: none;' type="text" name="cnx_tod" id='cnx_tod'>
                            <div id="cnx_submit_wrapper">
                                <input id="cnx_submit" class="jb-cnx-submit" type="submit"value="{wos/deco:_PG_LOGIN_TX002}">
                            </div>
                        </form>
                    </div>
                </div>
                <div id="cnx_sidebar">
                    <div id="cnx_signin">
                        <a id="cnx_signin_btn" href="/signup">{wos/deco:_PG_LOGIN_TX006}</a>
                    </div>          
                </div>
            </div>
            {wos/dvt:footer_default}
            
        </div>
    </div>
    {wos/csam:notify_ua}
    {wos/dvt:nolang}
    {wos/dvt:whyacc}
    
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

    <script src="{wos/sysdir:script_dir_uri}/r/csam/lgselect.csam.js?{wos/systx:now}"></script>
    <!--<script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>-->
    <script src="{wos/sysdir:script_dir_uri}/w/s/cnx.s.js?{wos/systx:now}"></script>
    
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/cnx_validator.d.js?{wos/systx:now}"></script>-->
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/cnx_ajax_validation.d.js?{wos/systx:now}"></script>-->
</div>