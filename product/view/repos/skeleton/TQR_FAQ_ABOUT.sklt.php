<?php
    set_error_handler('exceptions_error_handler');
    try {
        $ia = "{wos/datx:iauth}";
        $suggs = unserialize(base64_decode("{wos/datx:suggs}"));
//        var_dump($so,$ia,$pgvr);
//        var_dump($suggs);
//        exit();
        restore_error_handler();
    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
?>
<div s-id="FAQ_GTPG_ABOUT"> 
    {wos/dvt:faq_header}
    {wos/csam:notify_ua}
    <div class="screensize">
        <div class='faq_content'>
            <div class='faq_backToTop'></div>
            {wos/dvt:faq_breadcrumb}
            <div class='faq_fontsize'>
                <span id='faq_fontplus'>A+</span>&nbsp;|&nbsp;
                <span id='faq_fontminus'>A-</span></div>
            <div class='faq_middle_wrapper'>
                {wos/dvt:faq_xs_leftbar}
                <div id="faq-gofuther-bmx">
                    <div id="faq-left-ads-1">
                        <div class="faq-left-ads-tle">Publicité</div>
                        <div>
                            <span id="faq-left-ads-fil"></span>
                            <!-- ABOUT-ASD-LFT-NW1
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <ins class="adsbygoogle"
                                style="display:inline-block;width:300px;height:250px"
                                data-ad-client="ca-pub-7028578741126541"
                                data-ad-slot="7667237311"></ins>
                            <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                             -->
                        </div>
                    </div>
                    <?php if ( $suggs && is_array($suggs) ) : ?>
                    <div id="faq-gofu-sugg-bmx">
                        <div id="faq-gofu-sugg-tle">Ils sont sur Trenqr</div>
                        <?php foreach ($suggs as $sg) : ?>
                        <div class="faq-gofu-sugg-ubx-bmx">
                            <a class="faq-gofu-sugg-ubx-mx" href="/<?php echo $sg["upsd"]; ?>">
                                <div>
                                    <img class="faq-gofu-sugg-ub-i" width="70" src="<?php echo $sg["uppc"]; ?>" alt="Amis et passions en photos et vidéos" />
                                </div>
                                <div class="faq-gofu-sugg-ub-p">@<?php echo $sg["upsd"]; ?></div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ( $ia !== "1" ) : ?>
                    <div id="faq-gofu-ins-cnx-mx">
                        <a id="" class="faq-gofu-i-c-chcs" data-action="signin" href="/connexion">Se connecter</a>
                        <a id="" class="faq-gofu-i-c-chcs" data-action="signup" href="/inscription">S'inscrire</a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class='faq_middle_content' data-bc='Comprendre Trenqr' data-bcs='none' data-current='philosophy'>
                    <div class='faq_middle_content_title'><h1>À propos de Trenqr : Le réseau social des amis et des passions !</h1></div>
                    <div class='faq_index_text'>
                        <p id="faq-about-intro-mx">
                            <!--
                            Trenqr est une plateforme communautaire originale et conviviale, qui se distingue par : 
                            son océan d'<span class="faq-about-intro-hilit">images</span> (photos, dessins ou tout autre support visuel) pour des expériences visuelles enrichissantes, 
                            son <span class="faq-about-intro-hilit">environnement fun</span> résolument ludique et les <span class="faq-about-intro-hilit">Tendances</span> pour explorer, partager et discuter avec le monde. 
                            Trenqr est une alternative originale et innovante d'un point de vue social et fonctionnel à mi-chemin entre Facebook, Twitter et Instagram.
                           -->
                           <!--pas que pour les amis mais pour ceux qui aiment rencontrer-->
                           
                            <strong>Trenqr</strong> (aussi apppelé T) est une <strong>plateforme</strong>&nbsp;<strong>communautaire</strong>, 
                            qui vous aide à vous <span class="faq-about-intro-hilit">faire de nouveaux amis</span> 
                            et de nouvelles connaissances, par affinité, en se basant sur vos <span class="faq-about-intro-hilit"><strong>passions</strong></span> communes,
                            dans un <span class="faq-about-intro-hilit">environnement <strong>utile</strong></span>, <span class="faq-about-intro-hilit"><strong>fun</strong></span> et <span class="faq-about-intro-hilit"><strong>authentique</strong></span>.
                            <br/><br/>
                            <b class="faq-about-intro-hilit">Comment ?</b> Notamment grâce acux <span class="faq-about-intro-hilit"><strong>"Tendances"</strong></span> et leurs <span class="faq-about-intro-hilit"><strong>zone de discussion</strong></span> dédiées.
                            Les Tendances servent à vous retrouver entre passionnés, et curieux sur des sujets d'intérêt.
                            Quant aux zones de discussion, elles vous permettront d'échanger en temps réel, de faire connaissance et pourquoi pas, trouver de nouveaux amis. 
                            <br/><br/>
                            De plus, en <span class="faq-about-intro-hilit">partageant</span> les expériences de <span class="faq-about-intro-hilit">votre <strong>vie</strong></span> en
                            <span class="faq-about-intro-hilit"><strong>photos</strong></span> et <span class="faq-about-intro-hilit"><strong>vidéos</strong></span>,
                            vous permettez aux autres utilisateurs de mieux vous connaitre. Cela participe à une <span class="faq-about-intro-hilit">meilleure expérience sociale</span>.
                            <br/><br/>
                            En bref ! Trenqr est la <span class="faq-about-intro-hilit">meilleure <strong>alternative</strong></span> d'un point de vue social et fonctionnel, à mi-chemin entre <strong>Facebook</strong>, <strong>Twitter</strong> et <strong>Pinterest</strong>. 
                            Sur Trenqr il n'y a pas que des amis, il y a aussi et surtout de belles <strong>rencontres</strong> autour de sujets qui vous passionnent.
                        </p>
                        <!--<div>Dernière mise à jour : 05-11-2015</div>-->
                        <!--
                        <h2 class="faq-grp-text-tle">A propos de Trenqr</h2>
                        <div class="faq-grp-text-mx">
                            <p class="faq-about-bloc">
                                Nous avons créer Trenqr pour vous permettre d'évoluer dans un univers social répondant à des codes différents. 
                                Sur Trenqr, les distances sont réduites, les choses sont simplifiées et l'<strong>environnement</strong> est <strong>ludique</strong>.
                                Vous serez plonger dans un authentique <strong>univers</strong>, dans lequel vous serez au contact permanent de personnes guidées par les mêmes désirs que vous : <span class='bold'>juste le faire !</span>
                            </p>
                            <p class="faq-about-bloc">
                                Trenqr met en place l'<strong>environnement</strong> et les outils qui vous rapprochent, au-delà de vos différences, au-delà des barrières qui ont pu jusqu'ici, vous empêcher de vous ouvrir aux milliers d'opportunités que le monde vous offre, 
                                aux milliers de personnes que vous pourriez rencontrer, aux milliers de choses que vous pouvez <span class='bold'>juste faire, sans vous prendre la tête…</span>
                            </p>
                            <p class="faq-about-bloc">
                                C'est simple, prenons le cas de David de New-York, qui aime la cuisine, les jeux vidéo et qui a un faible pour la musique gothique ... 
                                Il a fait la connaissance de Becca sur une Tendance dédiée aux photos de concerts. Avant, ils ne se connaissaient pas. Depuis, ils sont amis et passent des nuits blanches à discuter via la messagerie privée (MP).
                            </p>
                            <p class="faq-about-bloc">
                                Les <span class="bold" style="color:#830ad3;"><strong>Tendances</strong></span> parlons-en ! Nous avons intégré le concept des Tendances pour que Trenqr joue pleinement son rôle de plateforme ouverte, qui vous rapprochent par affinités, tout en étant la source des nombreux contenus créatifs. 
                                Enfin, les Tendances démocratisent un <span class='bold'>mode de communication</span> que l’on pourrait désigné de <span class='bold'>collaboratif</span>. 
                                Les abonnés d'une Tendance apportent leurs contributions, en redoublant d’audace et de créativité, pour rendre populaire leur affinité commune. 
                                L’accent est donc mis sur l’esprit de groupe - ou communautaire - plutôt que sur l’exhibitionnisme singulier.
                            </p>
                        </div>
                        -->
                        <h2 id="tqr-faq-now-explore">
                            <?php 
                                $so = mt_rand(1,10);
//                                echo "<span class='' style='color: #f00'>$so</span>";
                                if ( $so >= 1 && $so <= 2 ) :
                            ?>
                            <a href="/looka" role="button">EXPLOREZ ET DÉCOUVREZ TRENQR</a>
                            <?php elseif ( $so > 2 && $so <= 4 ) : ?>
                            <a href="/lyly" role="button">EXPLOREZ ET DÉCOUVREZ TRENQR</a>
                            <?php elseif ( $so > 4 && $so <= 6 ) : ?>
                            <a href="/tendance/9h4lc3aoa/Recettes-de-cuisine-en-photos-du-plaisir-culinaire-%C3%A0-partager" role="button">EXPLOREZ ET DÉCOUVREZ TRENQR</a>
                            <?php elseif ( $so > 6 && $so <= 8 ) : ?>
                            <a href="/tendance/9l78hiko12/Summer-Weight-Challenge-Perdre-20-kilos-en-deux-mois" role="button">EXPLOREZ ET DÉCOUVREZ TRENQR</a>
                            <?php elseif ( $so > 8 ) : ?>
                            <a href="/tendance/9l21gg5o11/BuzzVids-France-Cest-fort-en-%C3%A9motions" role="button">EXPLOREZ ET DÉCOUVREZ TRENQR</a>
                            <?php endif; ?>
                            
                        </h2>
                        <h2 class="faq-grp-text-tle">Trenqr pour les nuls : Le Glossaire</h2>
                        <h2 id='tqr-glosy-intro'>
                            Quoi de mieux pour comprendre un univers, que de définir les mots qui le compose, avec humour. Fériez-vous mieux que nous ?
                        </h2>
                        <article class="faq-grp-text-mx">
                            <!-- A -->
                            <h3 class="tqr-facts-grp-tle">A</h3>
                            <h4 id="glossary_firend" class="tqr-glosy-word">Ami</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Personne avec laquelle vous partagez plus qu'un simple :
                                </p>
                                <ul class="tqr-glosy-def-ul italic">
                                    <li>« Salut, comment ça va ?»</li>
                                    <li>« Ça va bien et toi ?»</li>
                                    <li>« Oui, super...»</li>
                                    <li>« Ok...»</li>
                                    <li>(fin)</li>
                                    <li>(Ils furent amis)</li>
                                </ul>
                                <p>
                                    Pour demander un utilisateur en ami sur Trenqr :
                                </p>
                                <ul class="tqr-glosy-def-ul">
                                    <li>Vous et cette personne avez au moins <span class='bold'>3 amis en commun</span></li>
                                    <li>Vous et cet utilisateur, <span class='bold'>vous suivez mutuellement</span> depuis au moins 7 jours consécutives</li>
                                </ul>
                                <p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- B -->
                            <h3 class="tqr-facts-grp-tle">B</h3>
                            <h4 id="glossary_blindm" class="tqr-glosy-word">BlindM</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    BlindM est une application intégrée de Trenqr où vous postez des messages appelés "BlindMessages". 
                                    Ces messages sont anonymes et vous permettent de gagner des points grâce aux votes des utilisateurs. 
                                </p>
                                <p class='tqr-glosy-def-nota _caution'>
                                    <i>BlindM est une application ludique et sociale. 
                                    Tout message inapproprié, offensant, insultant ou tous autres sortes d'abus seront puni !</i>
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- F -->
                            <h3 class="tqr-facts-grp-tle">F</h3>
                            <h4 id="glossary_facebook" class="tqr-glosy-word">Facebook</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Alias « T'es qui toi ? »</br>
                                    Alias j'ai 5000 "amis" dont 10 qui me parlent.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_woman" class="tqr-glosy-word">Femme</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Celle que vous ne comprennez pas.<br/> 
                                    Alias, tout et son contraire.<br/> 
                                    Alias, celle qu'on a jamais vu dans Collombo. <br/> 
                                    Alias, la plus grande énigme de ma vie... :( 
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- H -->
                            <h3 class="tqr-facts-grp-tle">H</h3>
                            <h4 id="glossary_man" class="tqr-glosy-word">Homme</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Celui qui ne vous sert à rien ! Ou presque...
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- K -->
                            <h3 class="tqr-facts-grp-tle">K</h3>
                            <h4 id="glossary_karma" class="tqr-glosy-word">Karma (Points)</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Ce sont des points que vous recevez en récompense à chaque fois qu'une personne ajoute un <b>"J'aime beaucoup"</b> ou un <b>"coo<i>!</i>"</b> à votre photo ou vidéo.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- L -->
                            <h3 class="tqr-facts-grp-tle">L</h3>
                            <h4 id="glossary_looka" class="tqr-glosy-word">Lou Carther</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Le créateur de Trenqr.<br/>
                                    Accessoirement le créateur de réseau social le plus pauvre et le plus enveloppé au monde. Heureusement, <a href="//trenqr.com/tendance/9l78hiko12/Summer-Weight-Challenge-Perdre-20-kilos-en-deux-mois">il fait un régime</a> !
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- M -->
<!--                            <h3 class="tqr-facts-grp-tle">M</h3>
                            <h4 id="glossary_bestfirend" class="tqr-glosy-word">Meilleur ami</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    [DEFINITION]
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>-->
                            
                            <!-- P -->
                            <h3 class="tqr-facts-grp-tle">P</h3>
                            <h4 id="glossary_photo" class="tqr-glosy-word">Photo</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Ce truc où l'on vous voit en mode statique, mais qui vaut mieux que 1000 mots.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_pod" class="tqr-glosy-word">Photo du jour (ou Public Stories)</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Photo ou vidéo publique, généralement en rapport avec votre vie ou votre quotidien, que vous partagez pour en faire profiter à toute la Trenqosphère !
                                </p>
                                <p>
                                    Ce genre de publication ne reste visible que 24 heures dans le Newsfeed. Au-délà, elle sera accessible qu'on se rendant directement sur votre compte.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_politics" class="tqr-glosy-word">Politique (Opinion)</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Partie de vous ou de votre vie qui ne nous intéresse pas :)
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- T -->
                            <h3 class="tqr-facts-grp-tle">T</h3>
                            <h4 id="glossary_trend" class="tqr-glosy-word">Tendance</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Les <b>Tendances</b> sont des vitrines ou des <b>zone d'échanges</b> où la Trenqosphère se retrouve pour découvrir, échanger et <b>faire connaissance</b>, en fonction
                                    de ses centres d'intérêt. <b>Cuisine</b>, <b>jeux vidéos</b>, <b>sport</b>, <b>automobile</b>, <b>animaux</b>, <b>humour</b>, <b>cinéma</b>, etc ... Tout y est !
                                </p>
                                <p>
                                    Vos <b>passions</b>, ce que vous aimez et ceux qui vous ressemblent, <b>se trouvent</b> forcément <b>sur une Tendance</b>.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                                <p>
                                    Quelques exemples de Tendances : 
                                    <a href="https://trenqr.com/tendance/9h4lc3aoa/recettes-de-cuisine-en-photos-du-plaisir-culinaire-à-partager">Tendance 1</a>
                                    -
                                    <a href="https://trenqr.com/tendance/9l78hiko12/summer-weight-challenge-perdre-20-kilos-en-deux-mois">Tendance 2</a>
                                    -
                                    <a href="https://trenqr.com/tendance/9l21gg5o11/buzzvids-france-cest-fort-en-émotions">Tendance 3</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_trenqr" class="tqr-glosy-word">Trenqr</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Le réseau social pour se faire de nouveaux amis et de nouvelles connaissances par affinité, grâce à vos passions communes. 
                                    <br>Alias, celui qui banni tout ceux qui répondent à un simple « Bonjour » par « T'es qui toi ? ».
                                    <br>Alias, celui qui se fait appeler, en toute modestie, le réseau social le plus fun <strike>du Monde</strike>&nbsp;<strike>de France</strike>... du Monde !
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_trenqrrnchill" class="tqr-glosy-word">Trenqr and chill</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Action d'inviter un(e) ami(e) chez vous, quand vos parents ne sont pas là, pour balancer des <a class="tqr-glosy-def-a" href="//trenqr.com/hview/q=BlindM&src=hash">#BlindM</a>
                                    à mourir de rire et passer à autre chose ... si affinité !
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_trenqrblog" class="tqr-glosy-word">Trenqr Blog</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                   Votre principale source officielle d'information sur Trenqr et son univers. Retrouvez nos <a href="http://blog.trenqr.com/category/trenqr-tutorials/">tutoriels</a> 
                                   et nos guides pratiques pour vous aider à dompter Trenqr.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_twitter" class="tqr-glosy-word">Twitter</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    <b>Le réseau social plus superficiel que social.</b><br/>
                                    Alias le nouveau scandale "sexiste", "misogyne", "homophobe", "raciste", "grossophobe", "islamophobe", 
                                    "xenophobe", "pas assez...", "un peu trop...", "cathophobe", "antisémite", "twitterophobe", ... à la une.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- S -->
                            <h3 class="tqr-facts-grp-tle">S</h3>
                            <h4 id="glossary_selfie" class="tqr-glosy-word">Selfie</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Ce que vous faites pour mettre un peu plus en évidence vos imperfections cutanées, tout en espérant voir la reine d'Angleterre vous photobomber.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <h4 id="glossary_stories" class="tqr-glosy-word">Stories</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Photos ou vidéos en lien votre vie, que vous ajoutez sur Trenqr.<br/>
                                </p>
                                <p>
                                    Si vous l'ajoutez en <b>mode privé</b>, elle ne sera visible <b>que par vos amis</b>.<br/> 
                                </p>
                                <p>
                                    En <b>mode public</b>, elle sera visible par tout le monde. 
                                    Dans ce cas, on parle de <b><a href="#glossary_pod">Photo du jour</a></b>.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- U -->
                            <h3 class="tqr-facts-grp-tle">U</h3>
                            <h4 id="glossary_useful" class="tqr-glosy-word">Utile</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Un réseau social qui vous permet d'héberger discrètement vos photos et vidéos, sauvegarder vos liens favoris, personnaliser vos photos et bien d'autres choses.
                                    Un indice, il commence par « T » ;)
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- V -->
                            <h3 class="tqr-facts-grp-tle">V</h3>
                            <h4 id="glossary_video" class="tqr-glosy-word">Vidéo</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    <i>(D'après Larousse) </i><q>Se dit d'une application ou d'un appareil relatif à la formation, l'enregistrement, 
                                        le traitement ou la transmission d'images ou de signaux occupant une largeur de bande comparable à celle d'un signal de télévision</q>
                                    <br/><br/>
                                    <b>BREF, le truc où l'on vous voit, vous et d'autres choses, bouger.</b>
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                            
                            <!-- Y -->
                            <h3 class="tqr-facts-grp-tle">Y</h3>
                            <h4 id="glossary_youtube" class="tqr-glosy-word">Youtube</h4>
                            <div class="tqr-glosy-def faq-about-bloc">
                                <p>
                                    Un endroit où vous pouvez retrouver <a href="https://www.youtube.com/channel/UC-pg7rZOl0el_y5egdst5fQ">nos tutoriels</a>.
                                </p>
                                <p class="tqr-glosy-def-hash">
                                    Vous avez une meilleure définition ? Proposez nous en une en utilisant <a class="tqr-glosy-def-a-sp1" href="//trenqr.com/hview/q=TrenqrGlossary&src=hash">#TrenqrGlossary</a>
                                </p>
                            </div>
                        </article>
                        <h2 class="faq-grp-text-tle this_hide">Qu'est-ce qui différencie Trenqr des autres réseaux sociaux ?</h2>
                        <article class="faq-grp-text-mx this_hide">
                            <p id="tqr-facts-intro" class="faq-about-bloc">
                                Trenqr se veut être une alternative intéressante aux réseaux sociaux traditionnels tels que Facebook, Twitter ou Pinterest. 
                                Nous avons donc plusieurs poi ... sinon vous devriez tout réapprendre de zeor. Ce n'est pas l'idéal. 
                                Dans le même nous nous devons de vous apporter des  .. #Trenqrfacts
                            </p>
                            <!-- AU NIVEAU DE LA PHILOSOPHIE SOCIALE -->
                            <h3 class="tqr-facts-grp-tle">Le modèle social</h3>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p id="tqr-facts-new-friends" class="faq-about-bloc tqr-facts-txt">
                                Nous développons Trenqr de telle sorte qu'il soit naturellement possible de <span class='bold'>se faire de nouveaux <strong>amis</strong></span>. 
                                Contrairement à Facebook, Trenqr n’est pas conçu exclusivement pour ceux qui se connaissent déjà. 
                                Au contraire, nous vous encourageons, sans ambigüité à vous faire pleins de nouvelles connaissances et d'amis. C'est le but !
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Nous ne considérons pas le mot « ami » comme une unité valeur de différenciation sociale. 
                                Aussi, par défaut, personne ne connait réellement le nombre d’amis d’un autre utilisateur.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Les relations sociales sont hiérarchisées et simplifiées, afin d'en faciliter la compréhension et de se rapprocher d'un modèle plus logique que sur certains réseaux sociaux. 
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <div id="tqr-facts-frd-rules" class="faq-about-bloc tqr-facts-txt">
                                Nous croyons en l'amitié en à son <span class='bold'>authenticité</span>. Aussi, nous avons mis des règles simples concernant les demandes d'amis, sans les restreindre.
                                Vous recevrez des demandes d'ami de personnes respectons au moins une des conditions ci-dessous :
                                <ul id="faq-about-frd-rules">
                                    <li>Vous et cette personne avez au moins <span class='bold'>3 amis en commun</span></li>
                                    <li>Vous et cet utilisateur, <span class='bold'>vous suivez mutuellement</span> depuis au moins 24 heures consécutives</li>
                                </ul>
                            Comprenez bien que ces règles ne sont pas faites pour limiter les demandes mais pour les rendrent plus authentiques. 
                            Etre ami sera valorisant car
                            </div>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Le type de relation qu’on a avec un autre utilisateur (ami, connaissance, abonné, abonnement) détermine facilement les interactions qu’on a avec celle-ci. Cela simplifie la gestion des relations sociales.
                            </p>
                            <!-- AU NIVEAU DE LA PHILOSOPHIE LUDIQUE -->
                            <h3 class="tqr-facts-grp-tle">L'environnement et les fonctionnalités</h3>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Sur #Trenqr vous ne serez jamais bridé ou vous ne serez pas conformiste. Avoir le choix est notre marque de fabrique. 
                                Qaund les autres font évoluer leur produit de façon unilatérale nous, nous pensons qu'aucune idée ne prévaut, aucune vision ne prévaut, et que rien ne vaut le choix d'avoir le choix. 
                                Faut-il être pro gay, pro feministe, pro masculaniste, pro geek ou autres ? Non, nous en avons rien à faire ! 
                                La vision médiatique et conformiste n'est pas notre volonté car cela prive d'alternative. Sinon, autant vivre dans une dictature.
                                Nous ne sommes pas exemplaire, nous sommes déluré et fun.
                                Vous ne verrez pas à la télé que nous avons pris une décision du genre "for a better world" bla bla bla
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Sur Trenqr, tout est fait pour que l'expérience utilisateur sur Trenqr et ailleurs, reste cool, ludique et convivial via les fonctionnalités, les textes et nos community managers.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Au delà d'être un site, Trenqr est aussi une plateforme regroupant de multiples outils et services à disposition de ses utilisateurs.
                                Nous pouvons prendre l'exemple de Trenqr Studio.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Sur Trenqr, chaque utilisateur a un capital <srong>QuCoin</srong>. 
                                Cela ne vous rendra pas millionaire dans la vrai vie mais aura le mérite de dynamiser et rendre plus ludique votre expérience d'utilisation.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                                Vous avez la possibilité de donner une appréciation à une publication de 3 manières différentes. Vous verez vous ne vous en lacerez plus.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                               242 comme le nombre maximum de caractères autorisés pour la description de vos photos et images. 
                               Idéal pour se concentrer sur l’essentiel sans être trop restrictif.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                               Trenqr c'est une expérience d'utilisation enrichie avec une multide de modèles disponibles, pour afficher vos publications differement.
                               Vous navigation ne sera donc jamais monotone.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                               Le module Newsfeed sort tout droit d'une autre planète. Même le Docteur n'aurait pas pu le voir venir.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                               Trenqr vous offre le Newsfeed le plus cool du monde.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                               Trenqr est une plateforme colorée. C'est beau et nous en sommes fiers.
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p class="faq-about-bloc tqr-facts-txt">
                               Sur de nombreux points, Trenqr est similaire certains réseaux sociaux. C'est bien car vous ne changez pas trop vos habitudes. Cependant, si vous vous demandez pourquoi changer, la réponse est : parce qu'ont a pas les mêmes objectifs.
                               Sur Trenqr les objectifs sont de faire que les utilisateurs puissent se parler sans craindre l'étranger, il n'y a pas de clan. De plus, nous voulons qu'infine vous puissiez vous faire de nouveaux amis à défaut d'être proche de ceux présents.
                            </p>
                            <!-- ON DIT CA, ON DIT RIEN -->
                            <!-- NE RIEN PERDRE -->
                            <h3 class="tqr-facts-grp-tle">Ce que vous ne perdez pas</h3>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p id="tqr-facts-new-friends" class="faq-about-bloc tqr-facts-txt">
                                Exemple
                            </p>
                            <h4 class="tqr-facts">#TrenqrFacts</h4>
                            <p id="tqr-facts-new-friends" class="faq-about-bloc tqr-facts-txt">
                                Vous pouvez partager vos photos et images à caractères public sur un aure réseau social tel que Facebook ou Twitter.
                            </p>
                            
                            <!--
                            <p class="faq-about-bloc">
                                Les autres vous donnent les outils, mais pas l'envie. Ils vous donnent les locaux, mais pas la musique. 
                                Trenqr c'est une communauté orientée, à très grande échelle, plutôt qu'un simple réseau social généraliste.
                            </p>
                            <p class="faq-about-bloc">
                                Nous vous offrons un monde ouvert, où nous ne participons pas à construire des cloisons entre vous et le monde. Sur Trenqr, vous avez la possibilité d'aller beaucoup plus loin que d'être spectateur de la vie de 90% de vos "amis".<br/>
                            </p>
                            <p class="faq-about-bloc">
                                De plus notre philosophie est de tout faire pour que le concept de monde ouvert reste plaisant. Aussi nous sommes en réflexion constante pour qu'il ne soit ni anarchique, ni intrusif et encore moins nuisible. 
                                Au contraire, nous encourageons à aller au-delà des simples divergences et chamailleries communautaires ou politiques. 
                            </p> 
                            <p class="faq-about-bloc">
                                Trenqr c'est un large choix de photos et d'images que les utilisateurs postent tous les jours, afin de partager sur les quotidiens. 
                                Vous accédez à un océan d'images au sein d'un univers fun, riche et original.
                            </p>
                            <p class="faq-about-bloc">
                                Enfin, et c'est important, nous sommes <span class="bold">indépendants</span>. Nous n'appartenons ni au Nord, ni au Sud. 
                                Nous ne prenons ni partie, ni ne sommes affiliés à aucun gouvernement, ou réseau de renseignement.
                                Nous oeuvrons à ne blesser aucune sensibilité, ni n'imposons aucune vision culturelle ou politique.<br/>
                                Quelque soit votre nationalité , votre courant politique , vos idéaux , vous apprécierez de vivre en terrain neutre et fun.
                                Un endroit qui prône la liberté et le respect, où chaque membre, accepte le sacrifice de la tolérance, pour le respect réciproque.<br/>
                                La seule doctrine politique, les seules valeurs culturelles auxquelles nous nous identifions, sont celles de la vie et de notre communauté !
                            </p>
                            -->
                        </article>
                        <h2 class="faq-grp-text-tle">Et vous, comment l'utiliserez-vous ?</h2>
                        <div class="faq-grp-text-mx">
                            <p class="faq-about-bloc">
                                Trenqr ne peut se faire tout seul, <b>c'est à vous de le construire</b>. Nous serons là pour vous guider et vous y aider. Rejoignez-nous !
                            </p>
                        </div>
                        <h2 class="faq-grp-text-tle">Où en est le projet ?</h2>
                        <div>
                            <p class="faq-about-bloc">
                                Depuis le 25 Septembre 2015, Trenqr est distribué sous sa version fonctionnelle <b>beta 1.0</b>. Il devrait rapidement évoluer et livrer tout son potentiel.<br/> 
                            </p>
                            <p class="faq-about-bloc">
                                Depuis le 10 Novembre 2015, Trenqr est distribué sous sa version évoluée <b>beta 2.0</b>. 
                                <!--Le système est plus évolué et des nouvelles applications et services ont été ajoutés à l'environnement. <a href="www.blackowlrobot.com/trenqr-beta2">En savoir plus.</a>-->
                                Le système est plus évolué et des nouvelles applications et services ont été ajoutés à l'environnement.
                            </p>
                            <p class="faq-about-bloc">
                                Depuis le 04 Juillet 2016, Trenqr est distribué sous sa version évoluée <b>beta 3.0</b>. 
                                Le système est plus mature, fonctionnel et intéressant. Il est fin prêt pour livrer tout son potentiel. <a href="http://blog.trenqr.com/nouveautes-nouvelle-version-trenqr-vb3">En savoir plus.</a>
                            </p>
                        </div>
                        <!-- 
                        <p>
                            Si vous voulez utiliser Trenqr pour partager vos photos avec vos contacts de manière simple, nous avons les outils qu'il vous faut ! 
                            En trois clics de souris vous pouvez mettre votre image en ligne et la rendre visible pour tous les internautes qui visiteront votre page, ou simplement vos contacts, si vous préférez que votre image reste privée.
                        </p>
                        <p>
                            Ou alors, peut-être que vous voulez activement participer à la communauté ? Formidable ! 
                            Nous avons tout ce dont vous pourriez avoir besoin : un accès simple et rapide aux différentes tendances que vous suivez depuis votre page personnelle, la possibilité d'en découvrir de nouvelles via vos contacts ou le feed, ou encore vous mesurer aux autres utilisateurs au sein d'une même tendance, encourageant ainsi la création !
                        </p>
                        <p>
                            Peut-être que vous voulez simplement faire des connaissances ? 
                            Après tout, Trenqr est un réseau social. Là où les autres réseaux sociaux existants sur les personnes que vous conaissez déjà, pourquoi ne pas échanger avec des personnes suivant les mêmes tendances que vous et faire connaissance avec des utilisateurs du monde entier ? De plus, vous n'êtes pas tenus de devenir &laquo; amis &raquo; pour rester en contact, puisque vous pouvez n'être que des connaissances partageant les mêmes centres d'intérêt, ou plus simplement encore, vous pouvez décider de suivre un autre utilisateur pour voir ses publications.
                        </p>
                        <p class='faq_txtresize'>Si vous vous retrouvez dans un (ou plusieurs !) de ces cas, alors Trenqr est fait pour vous.</p>
                        -->
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