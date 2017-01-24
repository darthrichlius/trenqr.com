<html>
    <head>
        <title>Page d'erreur - Trenqr</title>
        <meta charset="utf-8" />
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="cache-control" content="no-cache">
        
        <link rel="icon" type="image/png" href="/bart1/timg/files/img/r/logos/fav2.png" />

        <link rel="stylesheet" type="text/css" href="/bart1/ext/public/css/r/c.c/com.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/bart1/ext/public/css/w/d/content_header.d.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/bart1/ext/public/css/r/s/errorview.s.css?<?php echo time(); ?>">
    </head>
    <body>
        <!-- Vérifier si co => changer de Header -->
        <div class="header">
            <a href='/'>
                <div class="logo"></div>
            </a>
            <div class="lang_container">
            </div>
        </div>
        
        <div id="page">
            <div id="header"></div>
            <div id="body">
                <?php 
                    @session_start(); 
                    
                    $CXH = new CONX_HANDLER();
                    $isc = $CXH->is_connected();

                    if (! $isc ) :
                     
                ?>
                <div id="pan-right">
                    <div id="pan-right-chcs-mx">
                        <a id="" class="pan-r-c-chcs" data-action="signin" href="/login">Se connecter</a>
                        <a id="" class="pan-r-c-chcs" data-action="signup" href="/signup">S'inscrire</a>
                    </div>
                </div>
                <?php endif; ?>
                <div id="pan-left" class="pan-left-isc">
                    <div id="pan-err-max">
                        <div id="pan-err-ctr">
                            <div id="pan-err-logo">
                                <img class="pan-err-logo-img" width="275" height="275" src="/bart1/timg/files/img/r/tqr_err.svg" />
                            </div>
                            <?php if (! $isc ) : ?>
                            <div id="pan-err-msg">
                            <?php else : ?>
                            <div id="pan-err-msg" class="isc">
                            <?php endif; ?>
                                <!--<p id="pan-err-msg-tle">ERREUR !</p>-->
                                <!--<p>-->
                                    <span id="pan-err-msg-tle">ERREUR !</span>
<!--                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Cras eu suscipit ipsum. Nunc varius, ex non rhoncus dignissim, sem dui ultrices dui, id viverra eros ipsum a tellus. Nunc risus diam cras amet.-->
                                    <?php 
                                        if ( isset($ErrMessage) && $ErrMessage !== "" ) {
                                            echo $ErrMessage; }
                                        else {
                                            echo "That's all we know"; 
                                        }
                                    ?>
                                <!--</p>-->
<!--                                <p>
                                    <?php
//                                        if ( isset($errCode) && $errCode != "" ) 
//                                            echo $ErrCode; 
                                    ?>
                                </p>-->
                            </div>
                        </div>
                        <div id="pan-err-logo-rest-mx" class="clearfix">
                            <img id="pan-e-logo" src="/bart1/timg/files/img/r/tqr_err_brk.svg" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="footer">
                <div id="ftr-list">
                    <ul id="ftr-list-wpr" class="clearfix2">
                        <li><a href="/about">A propos</a></li>
                        <li><a href="/terms">Conditions d'utilisation</a></li>
                        <li><a href="/privacy">Confidentialité</a></li>
                        <li><a href="/cookies">Cookies</a></li>
                        <li><a href="//blog.trenqr.com">Blog</a></li>
                    </ul>
                </div>
                <div id="ftr-tqr">©2016 Trenqr</div>
            </div>
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/w/c.c/langselect.js?{wos/systx:now}"></script>
        
    </body>
</html>
