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
<div s-id="TQR_FAQ_PRIVACY">
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
                <div class='faq_middle_content' data-bc='Politique de confidentialité' data-bcs='none' data-current='privacy'>
                    <div class='faq_middle_content_title'><h1>POLITIQUE DE CONFIDENTIALITÉ ET DE TRAITEMENT DES DONNÉES</h1></div>
                    <div class='faq_index_text'>
                        <div class="faq-grp-text-mx">
                            <p>
                                Trenqr protège la vie privée de ses utilisateurs en respectant la législation en vigueur. 
                                Ainsi, Trenqr a déclaré la collecte et le traitement de vos données personnelles auprès de la CNIL (Procédure en cours).
                                <!--Ainsi, Trenqr a déclaré la collecte et le traitement de vos données personnelles auprès de la CNIL (récépissé n° …).-->
                            </p>
                            <p>
                                La présente charte illustre l'engagement de Trenqr eu égard au respect de votre vie privée et à la protection des données personnelles vous concernant, collectées et traitées à l'occasion de votre accès et de votre utilisation de notre site dans les conditions visées au sein des <a href="/terms">Conditions d'Utilisation</a>.
                            </p>
                            <p>
                                Notez que nous pouvons être amenés à mettre à jour notre charte pour suivre l'évolution de notre service et de vos droits. 
                                Toute mise à jour vous sera signalée de manière visible sur cette page ou en vous informez par courrier électronique ou lors de votre prochaine connexion ou déconnexion. 
                                Sous serez informez (sauf indications contraires) sous un délai de 7 jours ouvrés à compter de la date de publication.
                            </p>
                            <p>
                                En accédant ou en utilisant Trenqr vous reconnaissez avoir pris connaissances de cette déclaration et que vous l’acceptez. 
                                Nous vous invitons à lire cette déclaration dans son ensemble afin de prendre des décisions averties.
                            </p>
                            <p>
                                Nous souhaitons instaurer une relation de confiance avec nos utilisateurs. Aussi, nous espérons que vous comprendrez mieux Trenqr, l’utilisation et le traitement que nous faisons des données et informations que vous y enregistrer.
                            </p>
                            <p>
                                <b>Cependant, nous vous demandons de consulter régulièrement cette page afin d’obtenir les informations plus récentes.</b>
                            </p>
                            <p>
                                Date de la dernière révision : <span id="faq-last-rev-date">01 Octobre 2015</span>
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Pour les utilisateurs de français</h2>
                            <p>
                                Conformément à la loi n° 78-17 du 6 janvier 1978, relative à l'Informatique, aux Fichiers et aux Libertés, vous disposez d'un droit d'accès et de rectification des données à caractère personnel vous concernant et faisant l’objet de traitements sous la responsabilité de Trenqr
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Collecte et utilisation des informations</h2>
                            <p>
                                <span class="faq-sp-submen">Informations collectées au moment de l'inscription</span> : Quand vous créez ou reconfigurez un compte Trenqr, vous nous fournissez des informations personnelles telles que votre nom complet, votre nom d'utilisateur (pseudonyme), votre ville de résidence, votre mot de passe et votre adresse email. 
                                Certaines de ces informations, par exemple votre nom complet, votre nom d'utilisateur ou votre lieu de résidence, sont listées publiquement dans nos Services, notamment sur votre page de profil et dans les résultats de recherche. 
                                Certains Services, tels que les services de recherche, les listes d'affichage et les profils publics des utilisateurs, ne requièrent pas d'enregistrement préalable pour être accessibles.
                            </p>
                            <p>
                                <span class="faq-sp-submen">Informations supplémentaires</span> : Vous pouvez nous fournir des informations de profil que nous rendrons publiques, par exemple une courte description,  votre site Web ou une ou plusieurs photos agrémentant votre page personnelle. 
                                Nous pourrons utiliser vos coordonnées afin de vous faire parvenir des informations sur nos Services ou vous proposer des offres. 
                                Vous pouvez également vous désinscrire en suivant les instructions fournies dans les notifications ou sur notre site Web. 
                                Nous pourrons utiliser vos informations de contact pour permettre à d'autres personnes de trouver votre compte Trenqr, notamment via des services tiers et des applications clientes. 
                                A la date d’écriture de cette déclaration nous ne fournissons aucun moyen qui permettrait de vous trouver directement à l'aide de votre adresse email ou votre numéro de téléphone mobile en faisant une recherche sur Trenqr. 
                                Cependant, vous pouvez télécharger votre carnet d'adresses afin que nous soyons en mesure de vous aider à trouver des connaissances sur Trenqr. 
                                Nous pourrons alors vous suggérer de suivre certaines personnes sur Trenqr à partir des contacts importés de votre carnet d'adresses ; à tout moment, vous pouvez supprimer ces contacts de Trenqr. 
                                Lorsque vous nous envoyez un email, nous utilisons l'email, l'adresse email et les informations associées pour répondre à votre sollicitation. 
                                La communication des informations supplémentaires répertoriées dans cette section est entièrement facultative.
                                
                            </p>
                            <p>
                                <span class="faq-sp-submen">Informations déduites</span> : Votre utilisation ou votre accès à Trenqr peut entrainer la génération d’informations anonymes ou non anonymes nous permettant d’effectuer des études statistiques. 
                                Ces données sont relatives à votre utilisation de Trenqr, votre fréquence d’utilisation, vos visites, votre activité à titre singulière ou en groupe ou vos habitudes. 
                                Elles ne sont donc pas fournies directement par vous. <b>Nous stockons, traitons ou diffusons ces informations dans le strict respect de la loi et dans un souci de ne pas nuire à votre vie privée</b>. Aussi, nous ne distribuons aucunes données déduites à des tiers quand celles-ci ne sont pas anonymes sans votre autorisation préalable. 
                                Les données anonymes, elles, persistent sur nos serveurs même après avoir supprimer votre compte Trenqr. Cependant, nous nous engageons à supprimer ses données dans un délai raisonnable de 3 à 5 ans après la suppression effective de votre compte. Pour être le plus claire possible, les données sont dites « non anonymes » quand on peut retrouver de manière certaine leur auteur ou leur source unique. 
                                Quand vous supprimez votre compte nous effaçons toute référence à votre identité sur certaines données afin de les rendre anonymes. Par exemple, la donnée suivante qui peut être représentée par la phrase suivante <span style="color:#666; font-style:italic;">« Dupont s’est connecté à 13h58 »</span> devient <span style="color:#666; font-style:italic;">« Quelqu’un s’est connecté à 13h58 »</span>. Nous appelons ce procédé « anonymisation ». Cependant, certaines données qui ne peuvent pas être anonymisé sont définitivement supprimées. 
                                Par exemple, la donnée représentée par la phrase suivante <span style="color:#666; font-style:italic;">« Dupont a utilisé 5 fois le compte email this.is.not.an.email@trenqr.com »</span> sera supprimée dans son intégralité. Enfin, sachez que l’utilisation de ce procédé d’anonysation n’est pas systématique sur toutes les données déduites. 
                                Pour en savoir plus sur la suppression de vos données, référez-vous au paragraphe <a href="#datas_deletion">Suppression de vos données personnelles</a>.
                            </p>
                            <p>
                                <span class="faq-sp-submen">Description, Page personnelle, les Tendances, commentaires et autres informations publiques</span> : Nos Services sont avant tout conçus pour vous aider à partager des informations avec le monde entier. La plupart des informations que vous nous fournissez sont des informations que vous souhaitez rendre publiques. 
                                Il peut s'agir non seulement des images que vous publiez et des métadonnées fournies avec celles-ci mais également de votre capital de points, des listes créées, des personnes que vous suivez, des images ou Tendances que vous créez ou que vous rediffusez, les discussions auxquelles vous participez, les commentaires aux publications, les personnes que vous marquez ainsi que des autres informations associées à votre utilisation des Services. 
                                Par défaut, les informations que vous fournissez restent presque tout le temps publiques tant que vous ne les effacez pas de Trenqr, mais il reste possible que nous fournissions, aujourd’hui ou dans le futur, des possibilités de paramétrage qui vous permettent de conserver la confidentialité des données de votre choix. Vos informations publiques sont instantanément et largement diffusées. 
                                Par exemple, les informations publiques de votre profil et vos images publics sont interrogeables par des moteurs de recherche et sont adressés via nos services liés à un large éventail d'utilisateurs et de services. 
                                Quand vous partagez des informations ou des contenus tels que des photos, des vidéos et des liens via les Services, vous devez réfléchir sérieusement à ce que vous rendez public. Vous comprenez donc que vous être seul(e) responsable de vos informations et contenus, de l’impact de leur diffusion ou divulgation pour les autres et pour vous-même.
                            </p>
                            <p>
                                <span class="faq-sp-submen">Localisation</span> : Trenqr utilise des services qui nous permettent de localiser géographiquement vos activités sur Trenqr. 
                                Nous collectons ces données dans plusieurs cas comme à votre inscription, connexion ou à chaque fois que vous postez une publication. 
                                Ces données nous sont nécessaires pour optimiser nos services mais aussi pour améliorer la sécurité de votre compte et de Trenqr en général. 
                                Ces données de localisation ne sont pas publiques. Quand ces données sont destinées à un usage public, vous en serez toujours informé. 
                                De plus, des moyens pourront être fournis pour limiter ou interdire leur publication le cas échéant.
                            </p>
                            <p>
                                <span class="faq-sp-submen">Liens</span> : Trenqr pourra conserver un historique de la façon dont vous interagissez avec les liens dans nos Services, notamment nos notifications par email, les services tiers et les applications clientes, en redirigeant les clics ou par d'autres moyens connus ou inconnus. 
                                Ceci est effectué dans un objectif d'amélioration de la qualité de nos Services, de ciblage efficace des publicités et de partage des statistiques agrégées sur les clics, par exemple pour déterminer le nombre de clics sur un lien.
                            </p>
                            <p>
                                <span class="faq-sp-submen">Données d’activité</span> : Nos serveurs enregistrent automatiquement les informations liées à votre utilisation des Services. 
                                Les informations de session peuvent inclure des données telles que le type et la version de votre navigateur, votre système d’exploitation, votre adresse IP, le domaine de référence, les pages visitées, les termes recherchés ou votre historique de navigation. 
                                D'autres actions, telles qu’un clic sur une annonce publicitaire, peuvent également être incluses dans vos informations de session.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Modification de vos informations personnelles</h2>
                            <p>
                                Vos informations personnelles vous appartiennent. Conformément à la loi Informatique et libertés vous disposez d'un droit d'accès aux données qui vous concernent, ainsi qu'un droit de modification ou de suppression de celles-ci (articles 39 et 40 de la loi Informatique et Libertés du 6 janvier 1978). 
                                Pour exercer ce droit, il vous suffit de nous adresser un courrier postal à l’adresse mentionnée dans la section mentions légales en nous indiquant vos nom, prénom, pseudonyme, adresse et éventuellement toutes copies (n’envoyez pas de pièces originales) de pièces d’identité valides et valables (carte d’identité, titre de séjour, passeport, permis de conduire, etc.) permettant d’établir sans aucune ambigüité la véracité de votre identité. 
                                Nous nous engageons à ce que toutes les informations que nous recueillerons lors de cette correspondance soient considérées comme des informations confidentielles. De plus, nous nous engageons à supprimer toutes informations et toutes copies de documents d’identité reçues. 
                                Nous nous réservons le droit d’engager des poursuites judiciaires en cas de tentative de tromperie, de menaces, d’intimidations ou toutes autres manifestations hostiles, illégales et répréhensibles à notre encontre. 
                                Nous nous engageons à traiter votre demande dans un délai raisonnable à compter de la réception de celle-ci.
                            </p>
                            <p>
                                Vous pouvez tout aussi exercer directement vos droits depuis Trenqr en accédant à votre compte >  « Gérer » > « Données de compte ou de profil » et en utilisant les outils mis à votre disposition.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 id="datas_deletion" class="faq-grp-text-tle">Suppression de votre compte</h2>
<!--                            <p>
                                Meme après la demande de suppression, certaines données persisteront le temps qu'elles soient toutes retirées du service
                                30 jours d'attente => 7 jours pour supprimer effectivement d'où le fait que certaines données vont persistées un temps
                            </p>-->
                            <p>La suppression d'un compte ainsi que toutes ses données, est un processus long et complexe, qui suit des procédures successives pour qu’elle soit complète et effective. L’opération peut prendre jusqu’à 6 mois environ.</p>
                            <p>
                                Après la demande de suppression de votre compte, s’en suit une période de 30 jours où votre compte ainsi que les données que vous avez ajoutées, resteront inaccessibles. 
                                Pendant ce délai, il vous est encore possible de récupérer votre compte. 
                                Pour cela, il vous suffit de vous reconnecter. La reconnexion aura pour effet d’annuler définitivement le processus de suppression.</p>
                            <p>Passé ce délai de 30 jours, votre compte rentre dans une phase de suppression effective qui ne peut être annulée. Il faudra entre 1 à 5 mois pour que toutes les données soient effacées de nos serveurs.</p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Vos responsabilités</h2>
                            <p>
                                Les utilisateurs peuvent afficher certaines informations ou certains contenus sur Trenqr, y compris sans y être limité. 
                                Les utilisateurs sont les seuls responsables de la diffusion de certaines informations et contenus, y compris les informations concernant la race, l’origine ethnique, l’orientation sexuelle, les opinions politiques, les croyances religieuses ou philosophiques ou toute information relative à la santé.
                            </p>
                            <p>
                                En communiquant ces informations via Trenqr, vous reconnaissez et acceptez explicitement que ces informations soient traitées par Trenqr et vous attestez avoir agi de la sorte librement et en toute connaissance de cause
                                Nous ne vous demanderons en aucun cas, de nous fournir des informations ou des contenus quand la loi applicable à Trenqr et aux entreprises propriétaires de Trenqr nous l’interdisent.
                            </p>
                            <p>
                                Nous ne pourrons être tenus pour responsable si vous êtes victime d’une attaque visant à obtenir de manière délictueuse ou assumée des informations personnelles, confidentielles ou d’ordre privées que nous ne collectons pas.
                                Cela même si l’usurpateur dit agir au nom de Trenqr ou d’une entreprise affiliée ou propriétaire de Trenqr.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Sécurité des données</h2>
                            <p>
                                Nous prenons toutes les mesures raisonnables afin de protéger les informations contre toute perte, utilisation à mauvais escient et contre tout accès, divulgation, modification et destruction non autorisés.
                                Nous avons mis en place, dans la mesure du possible, des procédures physiques, électroniques et d’encadrement appropriées afin de protéger et de sécuriser les informations contre toute perte, utilisation à mauvais escient et contre tout accès, divulgation, modification et destruction non autorisés.
                                Cependant, cela ne constitue pas de notre part une obligation de résultat en ce qui concerne la sécurité des informations publiées sur, ou transmises via, Internet.
                                Trenqr utilise les services d’un ou de plusieurs hébergeurs pour stocker vos données et pour vous fournir nos services.
                                Nous avons choisi ces partenaires avec le plus grand soin. Cependant, nous ne pouvons être tenus responsables, ni nous ne pouvons garantir que la sécurité ni la fiabilité des données stockées par ces derniers souffrent d’une quelconque garantie.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Modification de la présente déclaration</h2>
                            <p>
                                Nous nous réservons le droit de modifier cette Politique de confidentialité à tout moment. Si nous modifions cette politique d'une manière jugée, à notre seule discrétion, substantielle, nous vous le notifierons via l’envoi d’un email à l'adresse associée à votre compte.
                                En continuant d'accéder ou en utilisant les Services après l'entrée en vigueur de ces changements, vous acceptez être lié aux conditions énoncées dans la nouvelle Politique de confidentialité.
                            </p>
                        </div>
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