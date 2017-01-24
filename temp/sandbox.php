<html>
    <head>
        <title>Activer son compte en validant l"email</title>
        <meta charset="utf-8">
    </head>
    <body style="
        margin: 0px 0px 50px 0px; 
        padding: 0; 
    ">
        <div id="new-acc-panel-mx" style="
            width: 100%; 
            min-width: 550px; 
            max-width: 650px; 
            height: 100%; 
            margin: 20px auto; 
            /*background-color: rgb(242,242,242);*/

            position: relative; 
            overflow: auto;
            color: #000;
            font-family: Arial, sans-serif;
        ">
            <div id="n-a-pan-right" style="
                color: #000;
            ">
                <div id="n-a-pan-r-body" style="
                     padding: 20px; 
                     background-color: #fff; 
                     color: #000;
                     font-size: 15px;"
                >
                    <section id="tqr-rcmd-scn-cfrm-bmx" style="
                        width: 100%;
                        margin: 30px auto 0;
                        text-align: center;
                    ">
                        <header style="
                             text-align: center;
                        ">
                            <a href="%{trenqr_http_root}%"><img height="50px" src="%{trenqr_prod_img_root}%/r/logo_tqr_beta_be.png" alt="trenqr" /></a>
                        </header>
                        <div id="tqr-rcmd-scn-cfrm-mx" style="
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            padding: 40px 30px;
                            background-color: rgba(230, 230, 230, 0.5);
                            font-family: Arial, sans-serif;
                            text-align: center;
                        ">
                            <div id="tqr-rcmd-scn-cfrm-hdr-bmx">
                                <div id="tqr-rcmd-scn-cfrm-hdr-mx">
                                    <div style="
                                        padding: 0 0 20px 0;
                                        color: #767D84;
                                        font-family:  'Open sans', Arial, sans-serif;
                                        font-size: 26px;
                                    ">Vous avez une nouvelle notification de</div>
                                    <div id="tqr-rcmd-scn-cfrm-ppic-mx">
                                        <a id="tqr-rcmd-scn-cfrm-nm-ix" href="%{act_uhrf}%">
                                            <img id="tqr-rcmd-scn-cfrm-nm-i" width="70" height="70" src="%{act_uppic}%" alt="%{act_ufn}% - %{act_upsd}%" style="
                                                 border: 1px solid #bcbcbc;
                                                border-radius: 15%;
                                            "/>
                                        </a>
                                    </div>
                                    <div id="tqr-rcmd-scn-cfrm-nm-mx">
                                        <a id="tqr-rcmd-scn-cfrm-nm" href="%{act_uhrf}%" style="text-decoration: none;">
                                            <div id="tqr-rcmd-scn-cfrm-psd" style="
                                                margin-top: 5px;

                                                color: #434850;
                                                font-size: 21px;
                                            ">%{act_ufn}%</div>
                                            <div id="tqr-rcmd-scn-cfrm-fn" style="
                                                display: block;
                                                color: #3f729b;
                                                font-size: 20px;
                                                font-weight: bold;
                                            ">@%{act_upsd}%</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div id="tqr-rcmd-scn-cfrm-bdy-mx" style="
                                margin-top: 18px;
                            ">
                                <div id="tqr-rcmd-scn-cfrm-txt" style="
                                    color: #31314C;
                                    font-size: 15px;
                                ">
                                    <div id="tqr-rcmd-scn-cfrm-txt-hi" style="
                                        margin-bottom: 5px;
                                        text-align: left;
                                    ">Bonjour @%{tag_upsd}%,</div>
                                    <div id="tqr-rcmd-scn-cfrm-txt-then" style="
                                        text-align: left; 
                                    ">
                                        <p>
                                            <a style="color: #3f729b; font-weight:bold;" href="%{act_uhrf}%">@%{act_upsd}%</a> %{action_sentence}%. 
                                        </p>
                                        <p
                                            style="
                                                border: 1px solid #ddd;
                                                border-radius: 6px;
                                                padding: 15px;
                                                box-sizing: border-box;
                                                background: #fff;

                                                color: #303742;
                                                font-size: 11px;
                                                font-style: italic;
                                                text-transform: uppercase;
                                        ">
                                            %{preview}%
                                        </p>
                                    </div>
                                </div>
                                <div id="tqr-rcmd-scn-cfrm-btns-mx" style="
                                    margin-top: 30px;
                                ">
                                    <div>
                                        <a class="tqr-rcmd-scn-cfrm-btn" data-action="ja" href="%{perma}%" style="
                                            display: block;
                                            border: 1px solid;
                                            border-radius: 6px;
                                            width: 235px;
                                            margin: 0px auto;
                                            padding: 10px 0px;
                                            background-color: #004589;

                                            color: #FFF;
                                            font-size: 18px;
                                            text-decoration: none;
                                        ">Afficher sur Trenqr</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div id="n-a-pan-r-footer" style="
                    padding: 10px; 
                    color: #000; 
               ">
                    <div id="" style="
                         text-align: center;
                    ">
                        <a href="%{trenqr_http_root}%"><img height="50px" src="%{trenqr_prod_img_root}%/r/fav2.png" alt="trenqr" /></a>
                    </div>
                    <div style="
                         margin-top: 30px;
                        padding: 0px 15px;
                    ">
                        <p style="
                            margin: 0;
                            font-size: 13px;
                        ">
                            Pour vous connecter, c"est par ici : <a style="color: rgb(40,148,255);" href="%{trenqr_login_link}%">Connectez-vous</a>
                        </p>
                        <p style="
                           margin: 0;
                           font-size: 13px;
                        ">
                            Vous avez oublié votre mot de passe ? <a style="color: rgb(40,148,255);" href="%{trenqr_start_rcvy_link}%">Réinitialisez votre mot de passe via ce lien</a>
                        </p>
                    </div>
                    <div>
                        <p style="
                            margin: 10px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            Ce message est destiné à %{tag_ufn}% - %{tag_upsd}%, dont l'email est %{tag_ueml}%.
                        </p>
                        <p style="
                            margin: 3px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            <a style="color: rgb(40,148,255);" href="%{trenqr_login_link}%">Connectez-vous</a> à votre compte et modifiez vos paramètres si vous ne voulez plus recevoir de notifications par email.
                        </p>
                        <p style="
                            margin: 10px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            Trenqr™ est une marque déposée de DEUSLYNN ENTREPRISE.
                        </p>
                        <p style="
                            margin: 3px 0 0 0;
                            padding: 0 15px;
                            color: #a1a5af;
                            font-size: 11px;
                        " >
                            © 2016 DEUSLYNN ENTREPRISE, BP 8415 69359 Lyon, FRANCE
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


    