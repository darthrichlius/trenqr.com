<?php
    set_error_handler('exceptions_error_handler');
    try {
        $ia = "{wos/datx:iauth}";
        $suggs = unserialize(base64_decode("{wos/datx:suggs}"));
//        var_dump($so,$ia,$pgvr);
//        exit();
        restore_error_handler();
    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
?>
<div s-id="TQR_FAQ_COOKIES">
    {wos/dvt:faq_header}
    {wos/csam:notify_ua}
    <div class="screensize">
        <div class='faq_content'>
            <div class='faq_backToTop'></div>
            {wos/dvt:faq_breadcrumb}
            <div class='faq_fontsize'><span id='faq_fontplus'>A+</span>&nbsp;|&nbsp;<span id='faq_fontminus'>A-</span></div>
            <div class='faq_middle_wrapper'>
                {wos/dvt:faq_xs_leftbar}
                <div id="faq-gofuther-bmx">
                    <?php if ( $ia !== "1" ) : ?>
                    <div id="faq-gofu-ins-cnx-mx">
                        <a id="" class="faq-gofu-i-c-chcs" data-action="signin" href="/login">Se connecter</a>
                        <a id="" class="faq-gofu-i-c-chcs" data-action="signup" href="/signup">S'inscrire</a>
                    </div>
                    <?php endif; ?>
                    <?php if ( $suggs && is_array($suggs) ) : ?>
                    <div id="faq-gofu-sugg-bmx">
                        <!--<div id="faq-gofu-sugg-tle">Suggestions</div>-->
                        <?php foreach ($suggs as $sg) : ?>
                        <div class="faq-gofu-sugg-ubx-bmx">
                            <a class="faq-gofu-sugg-ubx-mx" href="/<?php echo $sg["upsd"]; ?>">
                                <div>
                                    <img class="faq-gofu-sugg-ub-i" width="70" src="<?php echo $sg["uppc"]; ?>" />
                                </div>
                                <div class="faq-gofu-sugg-ub-p">@<?php echo $sg["upsd"]; ?></div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class='faq_middle_content' data-bc='Cookies' data-bcs='none' data-current='cookies'>
                    <div class='faq_middle_content_title'><h1>POLITIQUE SUR LES COOKIES</h1></div>
                    <div class='faq_index_text'>
                        <p>Cette page est destinée à vous donner des informations sur les cookies, leur utilisation et leur finalité. Ces informations ne sont pas exhaustives. Nous vous demandons donc de faire un effort de recherche pour compléter les informations qui vous sont fournies dans cette déclaration.</p>
                        <p>
                            Date de la dernière révision : <span id="faq-last-rev-date">14 Septembre 2015</span>
                        </p>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Que sont les cookies, les pixels et le stockage local ?</h2>
                            <p>
                                Les <b>cookies</b> sont des informations placées sur votre équipement par un site Web lorsque vous visitez ce site. 
                                Ils collectent des informations relatives à votre navigation internet et permettent notamment de vous reconnaitre d’une visite à une autre, d’enregistrer vos préférences de navigation ou de vous proposer de la publicité adaptée à vos centres d’intérêts.
                            </p>
                            <p>
                                Les cookies peuvent être permanents ou de session. 
                                Les cookies permanents restent sur votre ordinateur d’une session de navigation à une autre alors que les cookies de session sont supprimés lorsque vous fermez votre navigateur.<br/>
                                Vous pouvez en apprendre plus sur les cookies et leurs fonctions de façon générale en visitant un site d’information tel que : <a href="http://fr.wikipedia.org/wiki/Cookie_(informatique)">www.wikipedia.org</a> ou <a href="http://www.allaboutcookies.org/fr/">www.allaboutcookies.org</a>.
                            </p>
                            <p>
                                Un <b>pixel</b>  est une très petite image numérique transparente utilisée pour collecter des informations sur votre activité sur un site web.
                            </p>
                            <p>
                                Le <b>stockage local</b> est une technologie standard qui permet aux sites web ou aux applications de stocker des informations directement sur votre équipement. 
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Comment désactiver ou activer des cookies ?</h2>
                            <p>
                                Vous pouvez accepter ou refuser des cookies en modifiant les paramètres de votre navigateur, ou faire en sorte que votre navigateur vous demande confirmation avant d’accepter un cookie de la part des sites Web que vous visitez.
                                Sachez que, si vous choisissez de désactiver complètement les cookies, il se peut que vous ne puissiez pas utiliser toutes nos fonctionnalités interactives. 
                                Vous pouvez supprimer tous les cookies qui ont été installés dans le dossier des cookies de votre navigateur. 
                                Cliquez sur l’un des liens de navigateurs ci-dessous pour consulter leurs consignes.
                            </p>
                            <ul class="faq-sp-ul">
                                <li><a href="http://windows.microsoft.com/fr-fr/windows-vista/block-or-allow-cookies">Microsoft Windows Explorer</a></li>
                                <li><a href="https://support.google.com/chrome/answer/95647?hl=fr&p=cpn_cookies">Google Chrome</a></li>
                                <li><a href="https://support.mozilla.org/fr/kb/activer-desactiver-cookies?redirectlocale=en-US&redirectslug=Enabling+and+disabling+cookies">Mozilla Firefox</a></li>
                                <li><a href="http://support.apple.com/kb/PH5042">Apple Safari</a></li>
                                <li><a href="http://www.macromedia.com/support/documentation/fr/flashplayer/help/settings_manager02.html#118539">Désactiver les cookies Flash</a></li>
                            </ul>
                            <p style="margin-top: 20px;">
                                Les liens ci-dessus n’engagent pas Trenqr. 
                                Ils ne sont fournis qu’à titre indicatif. 
                                Trenqr ne pourrait être tenu pour responsable pour tous désagréments rencontrés lors de la visite de ces sites ou ceux découlant des manipulations qui y sont mentionnées.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx"> 
                            <h2 class="faq-grp-text-tle">Trenqr et les cookies</h2>
                            <p>
                                Comme de nombreux autres site web ou services, <b>nous utilisons les cookies, les pixels et le stockage local</b>.<br/> 
                                En accédant ou en utilisant Trenqr, vous acceptez sans restriction l’utilisation que nous faisons du stockage local, des cookies ou des pixels en les ajoutant, les traitant, les modifiant ou les supprimant. 
                                Nous vous rappelons que vous êtes libres de supprimer à tout moment les cookies que nous installons sur votre équipement. 
                                Cependant, si vous décidez de supprimer ou de bloquer les cookies, nous devons vous avertir sur le fait qu’il soit possible que tout ou partie des fonctionnalités de Trenqr deviennent inaccessibles, inopérants ou fonctionnent mal.
                            </p>
                            <p>
                                Nous utilisons plusieurs types de <b>cookies</b> et chacun d’entre eux remplit des fonctions différentes. 
                                Les cookies nous sont essentiels pour vous garantir l’accès et l’utilisation des services offerts par Trenqr. 
                                Sans leur utilisation, tout ou partie des services offerts par Trenqr seraient inopérants ou ne fonctionneraient pas correctement.  
                                De plus, nous utilisons aussi les cookies afin d’améliorer de manière substantielle notre qualité de service. Nous utilisons les cookies pour :
                            </p>
                            <ul class="faq-sp-ul-14" style="color: #555;">
                               <li>Analyser l’utilisation que vous faites de Trenqr</li>
                                <li>Analyser et mesurer l’audience de Trenqr</li>
                                <li>Améliorer le ciblage en ce qui concerne contenu que nous vous offrons</li>
                                <li>Conserver vos préférences d’utilisation et de localisation</li>
                                <li>Se souvenir de vous tout au long de votre session de navigation en mode « en ligne » ou « hors ligne ».</li>
                                <li>Améliorer la sécurité de votre compte en améliorant le contrôle sur qui et quand y accède.</li>
                            </ul>
                            <p style="margin-top: 20px;">
                                Comme de nombreux autres site web ou services, nous utilisons les <b>pixels</b> pour savoir si vous avez interagi avec certains contenus web ou email. Ces informations nous servent à la fois à évaluer et améliorer nos services, et également à vous proposer une meilleure expérience sur Trenqr.
                            </p>
                            <p>
                                En ce qui concerne le <b>stockage local</b>, c'est grâce aux informations stockées localement que nous pouvons personnaliser le contenu et votre expérience sur Trenqr.
                            </p>
                            <p>
                                Des informations complémentaires pourront être ajoutées à cette politique ou pointant vers d’autres ressources nous appartenant ou non. 
                                <b>Nous vous demandons donc de consulter régulièrement cette page afin d’obtenir les informations plus récentes sur ces technologies et leur utilisation</b>.
                            </p>
                        </div>
                        
<!--                        <p>Trenqr utilise des cookies et autres technologies similaires, comme les pixels et le stockage local, afin d'optimiser votre expérience, et la rendre plus agréable et de la sécuriser. Tous les services Trenqr (site internet, notifications par email, boutons, publicités etc.) utilisent ces technologies pour effectuer diverses opérations, comme par exemple vous connecter à Trenqr, enregistrer vos préférences, personnaliser le contenu auquel vous avez accès ou encore vous protéger contre les spams ou les abus et vous communiquer davantage de publicités pertinentes.</p>
                        <p>Un pixel correspond à une petite quantité de code de page web ou de notification par email. Comme de nombreux autres services, nous utilisons les pixels pour savoir si vous avez interagi avec certains contenus web ou email. Ces informations nous servent à la fois à évaluer et améliorer nos services, et également à vous proposer une meilleure expérience sur Trenqr.</p>
                        <p>Le stockage local est une technologie standard qui permet aux sites internet ou aux applications de stocker des informations directement sur votre ordinateur ou sur votre appareil mobile. C'est grâce aux informations stockées localement que nous pouvons personnaliser le contenu et votre expérience sur Trenqr.</p>
                        <h2>Pourquoi Trenqr utilise ces technologies ?</h2>
                        <p>Nous utilisons ces technologies pour vous permettre d'accéder aux services du site, et également pour évaluer et améliorer ces derniers. Généralement, les utilisations se limitent à l'une des catégories suivantes.</p>
                        <ul class='legalpage_list'>
                            <li>Authentification et sécurité</li>
                            <ul class='legalpage_sublist'>
                                <li>Votre connexion à Trenqr</li>
                                <li>Votre sécurité sur Trenqr</li>
                                <li>La lutte contre les spams et les abus de manière générale</li>
                                <p>L'utilisation de ces technologies nous permet de vous authentifier lorsque vous accédez à Trenqr, et d'empêche l'accès à votre compte et à vos informations par des tiers. En parallèle, ces technologies nous permettent de vous communiquer du contenu approprié à travers le site ou les autres services.</p>
                            </ul>
                            <p class='legallist_details'>Lorem Ipsum Dolor Sit Amet Tagada Tsoin</p>
                            <li>Préférences</li>
                            <p class='legalpage_list_p'>Lorem Ipsum Dolor Sit Amet</p>
                            <li>Statistiques et recherche</li>
                            <li>Contenu personnalisé</li>
                            <li>Publicité</li>
                        </ul>-->
                    </div>
                    <span class="clear"></span>
                </div>
                {wos/dvt:faq_footer}
            </div>
            <span class="clear"></span>
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

    <script src="{wos/sysdir:script_dir_uri}/w/s/faq.s.js?{wos/systx:now}"></script>
</div>