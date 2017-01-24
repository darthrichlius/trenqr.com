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
<div s-id="TQR_FAQ_CGU">
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
                <div class='faq_middle_content' data-bc='Conditions d&apos;utilisation' data-bcs='none' data-current='cgu'>
                    <div class='faq_middle_content_title'><h1>Conditions d'utilisation</h1></div>
                    <div class='faq_index_text'>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Préambule, droits et responsabilités</h2>
                            <p class="faq-grp-text-bloc">
                                Cette déclaration s’applique à tout utilisateur de Trenqr, applications ou services liés. 
                                Cette déclaration est susceptible d’être modifiée à tout moment sans justification ou avertissement préalable. 
                                Toute modification vous sera signalée dans un délai de 7 jours ouvrés (sauf indication contraire) à compter de la date de publication de la version modifiée. 
                                L’annonce pourra se faire par email, sms, à la connexion ou à la déconnexion de votre compte. Les décisions prises dans cette déclaration peuvent avoir, dans certains cas, un caractère rétroactif.
                                Dans ce cas, une indication le mentionnera.
                            </p>
                            <p class="faq-grp-text-bloc">
                                La version d’origine de cette déclaration est le Français. Cette déclaration n’est disponible qu’en Français. 
                                A chaque fois qu’une version sera disponible dans une autre langue, nous vous le signalerons dans un délai de 7 jours ouvrés (sauf indications contraires) à compter de la date de publication.
                            </p>
                            <p class="faq-grp-text-bloc">
                                En utilisant Trenqr ou en accédant à Trenqr, aux applications ou aux services offerts par Trenqr, vous indiquez votre acceptation de cette Déclaration. 
                                Nous espérons que la lecture de cette déclaration vous aidera à mieux comprendre Trenqr, sa philosophie et son fonctionnement.
                            </p>
                            <p class="faq-grp-text-bloc">
                                Date de la dernière révision : <span id="faq-last-rev-date">14 Septembre 2015</span>
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Politique de Confidentialité</h2>
                            <p class="faq-grp-text-bloc">
                                Trenqr est une plateforme à caractère public. Cependant, nous accordons une grande place à la protection de vos données et à votre vie privée. 
                                A ce titre, nous vous invitons à prendre connaissance de notre <a href="/privacy">politique de confidentialité et d’utilisation des données</a>. 
                                Vous y apprendrez  comment nous collectons, utilisons ou publions vos données. 
                                Nous vous encourageons vivement à prendre connaissance de notre politique de confidentialité et d’utilisation de vos données afin de garantir que vous puissiez prendre des décisions averties. 
                                Ceci, afin de perpétuer notre philosophie qui est de créer, autant que possible, une relation honnête et de confiance avec nos utilisateurs.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Cookies</h2>
                            <p class="faq-grp-text-bloc">
                                Les cookies sont des informations placées sur votre équipement par un site Web lorsque vous visitez ce site. 
                                Ils collectent des informations relatives à votre navigation internet et permettent notamment de vous reconnaitre d’une visite à une autre, d’enregistrer vos préférences de langue ou de vous proposer de la publicité adaptée à vos centres d’intérêts.
                            </p>
                            <p class="faq-grp-text-bloc">
                                Les cookies peuvent être permanents ou de session. Les cookies permanents restent sur votre ordinateur d’une session de navigation à une autre alors que les cookies de session sont supprimés lorsque vous fermez votre navigateur.
                            </p>
                            <p class="faq-grp-text-bloc">
                                Trenqr utilise les cookies. Pour en savoir plus sur les cookies et sur la façon dont nous les utilisons, nous vous invitons à vous reporter à la page dédiée à la <a href="/cookies">politique sur les cookies et à leur utilisation</a>.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Inscription, sécurité et fiabilité des comptes</h2>
                            <p>En vous inscrivant sur Trenqr, vous vous engagez à comprendre, à respecter et à accepter les conditions suivantes :</p>
                            <ol class="faq-sp-ol">
                                <li>Trenqr est un service dont l’accès est gratuit à toute personne respectant les conditions d’utilisation fixées.</li>
                                <li>Vous ne créerez pas de profil pour le compte d’autrui sans son autorisation.</li>
                                <li>
                                    Trenqr est un service où les utilisateurs fournissent à l’inscription, les données véridiques sur leur identité et tout ce qui l’entoure (coordonnées comprises). 
                                    De plus, cette obligation est toute aussi valable en ce qui concerne la modification ultérieure des données fournies.
                                </li>
                                <li>
                                    Vous n’utiliserez pas l’identité d’une autre personne pour vous inscrire. Cela s’applique aussi bien pour les personnes morales que physiques, célèbres ou anonymes. 
                                    Cependant, nous comprenons qu’il soit possible qu’il existe des homonymes.
                                </li>
                                <li>
                                    Il est de votre responsabilité d’éviter à ce qu’aucune confusion d’identité n’existe entre vous et une personne de notoriété publique. 
                                    Nous pourrons modifier ou supprimer votre compte en cas de litige et cela sans préavis.
                                </li>
                                <li>Vous devez être capable de fournir des documents valables justifiant votre identité le cas échéant.</li>
                                <li>Vous devez avoir au moins 12 ans au moment de l’inscription. Dans le cas contraire, vous ne devez pas vous inscrire sur Trenqr.</li>
                                <li>Si vous êtes mineur (selon la réglementation de votre pays), vous ne vous inscrirez que si vous avez obtenu l’autorisation de vos parents ou de votre tuteur légal.</li>
                                <li>Vous fournirez votre ville de résidence au moment de l’inscription. Si votre ville n’est pas disponible, vous pouvez choisir la plus grande ville à proximité de la vôtre.</li>
                                <li>Trenqr est un service qui utilise les pseudonymes. Vous pouvez choisir le pseudonyme de votre choix tant qu’il respecte les conditions 3, 4, 5, 6. </li>
                                <li>Assurez de fournir une adresse email valide dont vous êtes le propriétaire. Vous vous assurerez aussi que cette dernière est sécurisée. </li>
                                <li>Vous n’utiliserez pas d’email « jetables ».</li>
                                <li>Vous ne devez en aucun cas communiquer votre mot de passe à qui ce soit (vos proches compris). De plus, vous devez vous assurez que personne ne peut y accéder.</li>
                                <li>Vous ne devez pas révéler à qui que ce soit vos paramètres de sécurité.</li>
                                <li>Vous vous interdisez de faire quoi que ce soit qui puisse compromettre la sécurité de votre compte.</li>
                                <li>Vous ne devez pas transmettre votre compte à un tiers sans notre autorisation.</li>
                                <li>
                                    Vous n’utiliserez par Trenqr si vous avez déjà fait l’objet d’un bannissement et que cette décision n’a pas été abrogée. 
                                    Si votre compte ne fait plus l’objet d’un bannissement, nous vous informerons afin de vous signifier son abrogation. Dans le cas contraire, le bannissement reste d’actualité.
                                </li>
                                <!--<li>Vous ne devez pas créer de compte sur Trenqr pour contourner le blocage qui a pu être appliqué à un autre compte dont vous êtes le propriétaire par un autre utilisateur que vous essayez de contacter directement ou indirectement quand ce dernier a mis en œuvre des mécanismes pour éviter tout contact avec vous.</li>-->
                                <li>Vous ne devez pas créer de nouveau compte sur Trenqr pour rentrer en contact avec une personne qui a précédemment bloqué le compte dont vous êtes le propriétaire.</li>
                                <li>
                                    Vous ne devez pas utiliser Trenqr pour contacter directement ou indirectement un autre utilisateur de Trenqr quand une procédure d’éloignement a été assignée à son bénéfice, à votre encontre. 
                                    Cela s’applique aussi si vous avez été condamné au bénéfice de cet utilisateur pour des faits d’harcèlement moral, violences physiques, injures, pressions psychologiques, dénigrement, viol, agressions sexuelles, pédophilie ou tout autres actes répréhensibles ayant nuit à la stabilité et au bien être mental ou physique de cette personne. 
                                    Cette disposition est caduque si l’utilisateur que vous essayez de contacter vous en a donné l’autorisation ou que la décision de justice est révoquée. 
                                </li>
                                <li>Si nous bannissons ou supprimons votre compte, vous n’en créerez pas d’autres sans notre autorisation.</li>
                                <li>Vous vous engagez à tenir à jour les données d’identification et vos coordonnées.</li>
                            </ol>
                        </div>
                         <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Sécurité</h2>
                            <p>
                                Notre but est de faire que Trenqr soit un lieu fiable avec un haut niveau de sécurité. 
                                Cependant, nous ne pouvons garantir une sécurité absolue. Nous vous invitons donc à œuvrer à ce que Trenqr soit un endroit sûr pour vous et votre entourage. 
                                Nous vous demandons donc votre engagement sur les points suivants :
                            </p>
                            <ol class="faq-sp-ol">
                                <li>Vous n’utiliserez en aucun cas Trenqr et votre compte sur Trenqr, comme un relais ou un outil pour vous livrer à des activités de piratage informatique.</li>
                                <li>Vous n’utiliserez en aucun cas des moyens automatisés (robots, araignées, scripts automatisés, etc. pour accéder ou utiliser les services offerts par Trenqr sans notre autorisation. </li>
                                <li>
                                    Vous n’utiliserez pas l’image de Trenqr pour obtenir tous types d'informations et de données sur les utilisateurs de Trenqr ou les applications et services qui lui sont liés. 
                                </li>
                                <li>Vous ne demanderez en aucun cas des informations personnelles et/ou non publiques telles que des informations de connexion auprès d’un autre utilisateur.</li>
                                <li>Vous n’accéderez en aucun cas au compte d’un utilisateur tiers sans son autorisation formelle.</li>
                                <li>Vous n’utiliserez en aucun cas le compte d’un utilisateur tiers à des fins de piratage informatique. </li>
                                <li>Vous ne devez en aucun cas modifier les paramètres de sécurité d’un compte dont vous n’êtes pas le propriétaire.</li>
                                <li>Vous ne tenterez pas d’accéder ou d’utiliser Trenqr en dehors des moyens que nous mettons à votre disposition.</li>
                                <li>
                                    Vous ne modifierez pas le contenu qui vous est fourni par Trenqr quel que soit le moyen utilisé. 
                                    Si nous détectons des modifications en ce qui concerne le contenu que nous vous fournissons ou l’apparence de Trenqr, nous nous réservons le droit d’appliquer des mesures disciplinaires à votre égard voire à engager des poursuites judiciaires.
                                </li>
                                <li>
                                    Vous ne perpétrerez pas une ou plusieurs actions qui pourraient nuire à la sécurité ou au bon fonctionnement de Trenqr. 
                                    Cela implique que vous ne ferez aucune action visant à désactiver, surcharger ou toute autre action destinée à nuire au bon fonctionnement ou à l’apparence de Trenqr (attaques par injection, attaques XSS, attaque par déni de service, attaque par brute force, interférence, etc.).
                                </li>
                                <li>Vous n’utiliserez pas Trenqr en parallèle d’un bloqueur de publicité ou tout autre module interférant avec le fonctionnement de Trenqr.</li>
                                <li>Vous ne publierez pas d’informations incitant à utiliser d’éventuelles failles de sécurité de Trenqr afin de nuire à sa sécurité ou à ceux de ses utilisateurs, applications ou services liés.</li>
                                <li>Vous ne téléchargerez pas de virus, de codes ou applications malveillants sur Trenqr pouvant nuire à sa sécurité, son fonctionnement, son image ou sa fiabilité.</li>
                                <li>
                                    Si vous êtes ou que vous vous étiez un employé, stagiaire ou toute personne ayant un contrat de travail avec Trenqr ou de toute autre entreprise propriétaire de Trenqr, vous vous refusez de diffuser, de partager, de commenter toutes informations relatives à la sécurité ou à la fiabilité de Trenqr ou de faire ou d’aider à faire  toutes autres actions qui pourraient constituer une menace à la sécurité et à l’intégrité de Trenqr, applications ou services liés. 
                                    Dans le cas contraire, votre responsabilité sera engagée et nous nous réservons le droit de procéder à une procédure disciplinaire ou judiciaire à votre encontre.
                                </li>
                                <li>Vous ne permettrez pas et n’encouragerez pas les infractions à cette Déclaration ou à nos règlements en dehors ou à l’extérieur de Trenqr.</li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Charte de bonnes conduites</h2>
                            <p>
                                Notre but est de créer un Trenqr sûr, fiable et agréable. Cependant, nous ne pouvons garantir atteindre ces objectifs sans votre pleine participation. 
                                Nous vous demandons donc votre engagement sur les points suivants :
                            </p>
                            <ol class="faq-sp-ol">
                                <li>Vous n’utiliserez pas Trenqr à des fins illégales, malveillantes ou discriminatoires.</li>
                                <li>Vous ne publierez pas de contenus incitant à la haine quel que soit sa nature (raciale, morale, ethnique, etc.) ou à la violence, menaçants, à caractère pornographique ou contenant de la nudité ou de la violence gratuite (y compris de la violence animalière).</li>
                                <li>Vous n’intimiderez pas, n’encouragerez pas l’intimidation et ne harcèlerez pas d’autres utilisateurs. Nous nous réservons le droit de supprimer ou de bannir votre compte pour une durée déterminée ou indéterminée le cas échéant et cela sans préavis. </li>
                                <li>Vous appliquerez le concept « Leave me alone » (« Laisser moi vivre tranquille » en français). Cela veut dire que tout utilisateur de Trenqr a le droit au respect de sa tranquillité et à sa liberté de faire ou de ne pas faire sans subir aucune pression ou harcèlement de tout genre. </li>
                                <li>Vous ne porterez pas de fausses informations à l’égard d’un autre utilisateur à des fins de lui nuire ou pour que nous puissions de manière erronée engager des procédures disciplinaires à son égard.</li>
                                <li>Vous ne publierez pas de contenus faisant l’apologie du terrorisme.</li>
                                <li>Vous n’utiliserez pas Trenqr pour perpétrer des actions ou des actes à caractère pédophile, de prostitution infantile, ni ne publierez de façon intentionnelle des contenus à caractère sexuels, pornographiques, pédopornographiques ou tous autres contenus obscènes en direction d’utilisateurs mineurs, ou en faisant l’apologie. </li>
                                <li>Vous ne publierez pas de fausses informations au nom de Trenqr.</li>
                                <li>Vous ne vous ferez pas passer injustement pour un employé de Trenqr ou de toute société liée ou propriétaire de Trenqr afin de jouir de privilèges, diffuser de fausses informations nuisant à Trenqr ou de récolter des informations confidentielles.</li>
                                <li>Les comptes Trenqr sont distribués à des fins d’usage domestique. Aussi, vous n’utiliserez pas votre compte dans le but d’afficher de la publicité commerciale pour vous ou pour le compte d’un tiers sans notre autorisation.</li>
                                <li>Vous vous engagez à ne pas enfreindre ou encourager à enfreindre cette charte.</li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Protection des droits d’autrui</h2>
                            <p>
                                Avec Trenqr nous nous efforçons de faire respecter les principes sur les droits d’auteur, la propriété intellectuelle et contre l'usurpation d’identité. 
                                Nous ne pouvons garantir aucun résultat, encore moins sans votre aide. Nous vous demandons donc de vous engager à comprendre et respecter les points suivants :
                            </p>
                            <ol class="faq-sp-ol">
                                <li>En utilisant Trenqr vous vous engagez à ne pas diffuser de contenus ou des informations protégés ou dont vous n’êtes pas l’auteur ou les propriétaires sans autorisation du ou des propriétaires de ce contenu.</li>
                                <li>Vous vous interdisez d’usurper l’identité d’une personne morale ou physique sans son autorisation.</li>
                                <li>
                                    Nous nous réservons le droit de retirer tout ou partie d’un contenu ou des contenus que vous avez publiés sur Trenqr à la demande du ou des ses auteurs ou ayants droit. 
                                    En cas de manquements répétés, nous nous réservons le droit de vous avertir, de bloquer ou de supprimer votre compte sans préavis ou avertissement. 
                                    Cependant, il est possible que vous soyez prévenu avant, pendant ou après la procédure disciplinaire à votre encontre. 
                                    Cela par courrier électronique ou lors de votre prochaine connexion/déconnexion à votre compte. La procédure de mise en garde n'est donc pas obligatoire.
                                </li>
                                <li>
                                    Nous nous réservons le droit de modifier, rectifier ou supprimer votre compte si nous constatons que vous usurpez l’identité d’une personne physique ou morale (marques comprises). 
                                    Cela, sans justification ou avertissement et sans délai. 
                                    Cependant, il est possible que vous soyez prévenu avant, pendant ou après la procédure disciplinaire à votre encontre. 
                                    Cela par courrier électronique ou lors de votre prochaine connexion/déconnexion à votre compte. La procédure de mise en garde n'est donc pas obligatoire.
                                </li>
                                <li>En cas de litiges, les règles listées à la section « Litiges » s’appliquent.</li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Publicté</h2>
                            <p>
                                De la publicité est disponible sur Trenqr. Le contenu publicitaire provient de partenaires, d’entreprises affiliées ou d’entreprises propriétaires de Trenqr. La publicité nous permet de nous financer sans avoir à vous exiger de payer une contribution afin d’accéder à tous les services proposés par Trenqr. La publicité nous permet de garantir un traitement neutre pour tous. Aussi, Trenqr est disponible à toute personne quel que soit son degré de ressources. Des informations supplémentaires seront disponibles prochainement sur la relation entre Trenqr et la publicité.
                            </p>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Résiliation</h2>
                            <ol class="faq-sp-ol">
                                <li>Les parties à cette déclaration sont libres de résilier à tout moment, sans préavis, sans avertissement, sans concertation et sans motif les accords créés dans cette déclaration. </li>
                                <li>Vous êtes libre de supprimer votre compte à tout moment. Toute suppression de votre compte par vous-même sera considérée  comme une résiliation des accords qui vous lient à Trenqr. Cette déclaration deviendra donc caduque.</li>
                                <li>Si vous enfreignez la lettre ou l’esprit de cette déclaration, ou que vous créez un risque de poursuites à l’encontre de Trenqr ou de toutes personnes morales ou physiques propriétaires de Trenqr, nous nous réservons le droit unilatéral d’arrêter de vous fournir tout ou partie de Trenqr, applications et services liés. Il est possible que vous soyez préalablement averti ou non. Dans le cas où nous décidions de vous avertir, cela pourra se faire par courrier électronique, courrier postal ou lors de votre prochaine connexion (ou déconnexion) à votre compte.</li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Limitations et exclusions de garanties</h2>
                            <p>
                                Trenqr est un service web offrant ses services web gratuitement à une population avertie. Nous stockons, traitons et diffusons des données selon un principe dit « TEL QUEL » ou « EN L’ETAT ». 
                                En effet, il est important de rappeler que le traitement des données informatiques n’est pas une science exacte. 
                                Nous vous demandons donc d’accepter les engagements suivants :
                            </p>
                            <ol class="faq-sp-ol">
                                <li>
                                    TRENQR PEUT SOUFFRIR DE BOGUES, D’ERREURS ENTRAINANT DES PROBLEMES DE NAVIGATION, RESTRICTIONS DE SERVICE VOIRE DES PERTES DE DONNEES. 
                                    NOTRE BUT EST DE RAMENER A ZERO LA PROBABILITE QUE CES EVENEMENTS SURVIENNENT. 
                                    CEPENDANT, CELA N’EST QU’UN VŒU ET NON UN ENGAGEMENT DE NOTRE PART. NOUS NE POUVONS GARANTIR ATTEINDRE CE RESULTAT. VOUS UTILISEREZ DONC TRENQR EN CONNAISSANCE DE CAUSE.
                                </li>
                                <li>NOS EQUIPES S’ATTELENT A DETECTER ET A COMBLER TOUTE FAILLE DE SECURITE OU DE FIABILITE AUSSITOT QUE NOUS EN AVONS CONNAISSANCE. CEPENDANT, VOUS NE TIENDREZ PAS POUR RESPONSABLE TRENQR, OU TOUTE ENTREPRISE OU PERSONNE PROPRIETAIRE, POUR UNE FAILLE QUE NOUS N’AVONS PAS ENCORE DECOUVERTE OU QUE NOUS N’AVONS PAS PU COMBLER, CONNUE OU INCONNUE, AYANT PORTE PREJUDICE OU NON A VOS DONNEES, A VOTRE REPUTATION, A VOTRE COMPTE OU A L’UTILISATION QUE VOUS EN FAITE. </li>
                                <li>NOUS NE GARANTISSONS PAS QUE TRENQR FONCTIONNE SANS INTERRUPTION OU SANS RETARD.</li>
                                <li>NOUS N’ASSUMONS AUCUNE RESPONSABILITÉ QUANT AUX ACTIONS, CONTENUS, INFORMATIONS OU DONNÉES DE TIERS, ET VOUS DÉGAGEZ TRENQR, SON OU SES PROPRIETAIRES, LES MEMBRES DE SA DIRECTION, LES EMPLOYÉS TRAVAILLANT POUR L’ENTREPRISE OU POUR LA OU LES PERSONNES PROPRIETAIRES DE TOUTE RESPONSABILITÉ EN CAS DE PLAINTES OU DOMMAGES, CONNUS ET INCONNUS, ÉMANANT DE OU AFFÉRENTS AUX PLAINTES OU DOMMAGES À L’ENCONTRE DE CES TIERS.</li>
                                <li>NOUS NE POURRONS ÊTRE TENUS RESPONSABLES DE LA PERTE DE BÉNÉFICES OU TOUT AUTRE DOMMAGE CONSÉCUTIF, SPÉCIAL, INDIRECT OU ACCESSOIRE, QU’ILS DÉCOULENT DE CETTE DÉCLARATION, DE TRENQR OU DE TOUTE ENTREPRISE PROPRIETAIRE DE TRENQR, QUAND BIEN MÊME NOUS AURIONS ÉTÉ INFORMÉS DE LA POSSIBILITÉ D’UN TEL DOMMAGE.</li>
                                <li>DANS LE CAS OÙ LE DROIT APPLICABLE NE PEUT PAS AUTORISER LA LIMITATION OU L’EXCLUSION DE RESPONSABILITÉ OU DE DOMMAGES INCIDENTS OU CONSÉCUTIFS, LA LIMITATION OU LES LIMITATIONS, EXCLUSIONS PRECEDEMMENT CITÉES PEUVENT NE PAS S’APPLIQUER À VOTRE CAS. DANS DE TELS CAS, LA RESPONSABILITÉ DE TRENQR SERA LIMITÉE AU MAXIMUM PERMIS PAR LE DROIT APPLICABLE.</li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Litiges</h2>
                            <ol class="faq-sp-ol">
                                <li>Trenqr est une marque de droit français. Vous porterez plainte, constesterez ou effectuerez toutes autres actions en justice afférente à cette Déclaration ou à Trenqr exclusivement devant un tribunal français ou devant un tribunal de la juridiction de Lyon, et vous acceptez de respecter la juridiction de ces tribunaux dans le cadre de telles actions.</li>
                                <li>En cas d’action porter à notre encontre par un tiers suite à vos agissements, à vos contenus ou à vos informations sur Trenqr, vous vous engagez à indemniser et à protéger Trenqr et toute entreprise propriétaire de Trenqr de tous les préjudices, pertes et frais, y compris les honoraires raisonnables d’avocat, afférents à cette action.</li>
                                <li>Vous comprenez que diffuser des règles de bonnes conduites ne signifie pas que nous contrôlons ni ne dirigeons les agissements  des utilisateurs de Trenqr, applications ou services liés. Mais encore, nous ne pouvons être tenus responsables des contenus et des informations ajoutées, transmises, partagées ou supprimées délibérément par les utilisateurs de Trenqr. Nous ne serions être responsables des contenus ou informations offensants, inappropriés, obscènes, illicites ou autrement choquants que vous pourriez trouver sur Trenqr. Héberger les données d’un utilisateur ne signifie pas que nous les approuvions. Enfin, nous ne sommes pas responsables des agissements et de la conduite des utilisateurs de Trenqr en ligne ou hors ligne. </li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Autres</h2>
                            <ol class="faq-sp-ol">
                                <li>Cette Déclaration constitue l’intégralité de l’accord entre les parties concernant Trenqr et son utilisation, et annule et remplace tout accord précédemment établi.</li>
                                <li>Toute invalidation d’une partie de cette déclaration n’entraine pas l’invalidation de toute la déclaration, qui reste elle valable et applicable.</li>
                                <li>Cette déclaration ne peut souffrir d’une dérogation sans que nous l’ayons explicitement établi et signé.</li>
                                <li>Tout manquement à faire appliquer tout ou partie de cette déclaration ne peut vous justifier de croire qu’il s’agit d’une renonciation de notre part.</li>
                                <li>Nous nous réservons le droit de transférer librement nos droits et obligations sans avertissement ou préavis dans le cadre d’une fusion, d’une acquisition, d’une vente de tout ou partie de nos actifs, d’une demande des tribunaux ou dans d’autres cas non mentionnés.  </li>
                                <li>Vous ne pouvez transférer à un tiers les droits ou obligations qui vous incombent dans le cadre de cette déclaration sans notre autorisation formelle.</li>
                                <li>Nous nous réservons tous les droits qui ne vous sont pas explicitement accordés.</li>
                                <li>Aucune disposition prise dans cette déclaration ne peut vous empêcher de respecter ou de faire appliquer la loi le cas échéant.</li>
                                <li>Cette déclaration est faite pour vous et ne s’adresse en aucun cas à un tiers bénéficiaire.</li>
                                <li>La présence de fautes d’orthographes, d’erreurs grammaticales, de fautes de frappe ou d’erreurs d’expression dans cette déclaration ou dans les déclarations liées (Politique de confidentialité, politique sur les cookies, etc.) ne peuvent suffire à ne pas respecter les droits et obligations énoncés dans cette déclaration.</li>
                                <li>Vous accéderez et utiliserez Trenqr en respectant toutes les lois applicables. Autrement dit, la loi s’applique sur Trenqr. </li>
                                <li>Vous comprenez que toute déclaration faite à titre personnelle par un employé de Trenqr ou de toute autre entreprise propriétaire de Trenqr, sur ou en dehors de Trenqr, ne constitue en aucun cas une déclaration officielle de la part de Trenqr. Cela quel que soit son rang ou son poste. Toute déclaration officielle portera la mention « officiel(le) » et sera faite par un porte-parole accrédité.  </li>
                                <li>Quel que soit votre lieu de résidence ou votre lieu d’activité, cette Déclaration constitue un accord entre vous et toute entreprise propriétaire de Trenqr. Pour en savoir plus, veuillez-vous reporter au paragraphe sur les <a href="#legals">mentions légales</a>.</li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 class="faq-grp-text-tle">Clauses spéciales applicables aux utilisateurs hors de France</h2>
                            <p>Internet nous permet de diffuser Trenqr à travers le monde. Nous nous efforçons d’établir des standards cohérents pour tous, prompts à respecter les lois locales. Dans le cas où vous êtes un utilisateur de Trenqr ou que vous interagissez avec Trenqr, applications et services liés et que vous vivez hors du territoire Français, nous vous demandons de respecter les clauses suivantes :</p>
                            <ol class="faq-sp-ol">
                                <li>
                                    Vous acceptez que vos données personnelles ainsi que vos contenus postés sur Trenqr, soient transférées et traitées en France ou dans tout autre pays où sont implantés les serveurs faisant fonctionner Trenqr, nos filiales ou succursales actuelles ou à venir.
                                </li>
                            </ol>
                        </div>
                        <div class="faq-grp-text-mx">
                            <h2 id="legals" class="faq-grp-text-tle">Mentions légales</h2>
                            <ol class="faq-sp-ol">
                                <li>Trenqr est une marque enregistrée propriétaire de CABINET INFORMATIQUE DIEUD LOUSSAKOU - DEUSLYNN ENTREPRISE EIRL</li>
                                <li>CABINET INFORMATIQUE DIEUD LOUSSAKOU - DEUSLYNN ENTREPRISE EIRL exploite la marque Trenqr et tous les produits et services liés</li>
                                <li>CABINET INFORMATIQUE DIEUD LOUSSAKOU - DEUSLYNN ENTREPRISE EIRL est une entreprise de droit français basée à Lyon en FRANCE.</li>
                                <li>Vous pouvez contacter CABINET INFORMATIQUE DIEUD LOUSSAKOU - DEUSLYNN ENTREPRISE EIRL via l’adresse : 163 RUE PROFESSEUR BEAUVISAGE 69008 LYON 8EME BP 8415</li>
                                <li>CABINET INFORMATIQUE DIEUD LOUSSAKOU - DEUSLYNN ENTREPRISE EIRL  est enregistrée sous RCS : 794 133 553</li>
                                <li>Directeur de la publication : RICHARD DIEUD</li>
                                <!--<li>Autorisation CNIL : </li>-->
                            </ol> 
                        </div>
<!--                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate luctus neque, quis rutrum mauris adipiscing nec</p>
                        <p>Sed ullamcorper eros sed nisi pulvinar tempor. Integer pretium accumsan mauris vitae gravida. Aliquam sagittis hendrerit neque eget sollicitudin. Fusce pellentesque ipsum mi, et pellentesque enim mattis id. Integer suscipit ipsum risus, convallis ultrices quam faucibus eu. Nullam et sagittis sem. Mauris ac orci augue. Suspendisse potenti. Sed sit amet placerat quam.</p>
                        <p>Proin egestas dapibus ultricies. Etiam mollis est sapien, ut vestibulum sem placerat at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur id lectus pretium turpis facilisis molestie. Vivamus odio orci, tempor vitae urna in, fermentum tristique dolor. Etiam sollicitudin mi ac diam imperdiet imperdiet.</p>
                        <p>Aliquam erat volutpat. Nunc vel massa lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec varius cursus ante eget fermentum. Morbi turpis dui, porta id turpis id, luctus vestibulum ante. Nunc et erat justo. Sed venenatis turpis velit, ac imperdiet nunc adipiscing pulvinar.</p>-->
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