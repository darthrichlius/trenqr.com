<!--<div class="TEST-ZONE"></div>-->
<div s-id="ontrenqr">
    <span id="view" class="jb-pg-vw" data-view="{wos/datx:view}"></span>
    {wos/dvt:nolang}
    <div class='theater'></div>
    <noscript>
        <div class='jsw_main_warning'>
            <div class='jsw_title'>Trenqr a besoin de JavaScript pour fonctionner correctement</div>
            <div class='jsw_sub'>Si vous ne l'activez pas dans les préférences de votre navigateur, vous ne pourrez pas profiter du site dans son int&eacute;gralit&eacute;</div>
        </div>
    </noscript>
    <div id="home1_scrolllock">
        <!--<div class="overlay" id="home_overlay">Lorem ipsum dolor sit amet.<span class="clear"></span></div>-->
        <div id="home_theater"></div>
        <div id="home1">
            <div id="home1_headbackground">
                <div id="home1_doodles">
                    <div id="doodle_arrow" class="jb-ddle-arrw">
                        <span id="doodle-arrow-alt">De quoi il s'agit ?<br> Cliquez pour tout savoir</span>
                        <!--<span id="doodle-arrow-alt">Qu'est ce que Trenqr ?<br> Cliquez pour tout savoir</span>-->
                        <!--<span id="doodle-arrow-alt">Cliquez ici pour en apprendre plus</span>-->
                        <!--<span id="doodle-arrow-alt">Cliquez sur le cadenas</span>-->
                    </div>
                    <!--<div id="doodle_left_big"></div>-->
                    <!--<div id="doodle_right_big"></div>-->
                    <div id="doodle_left_black"></div>
                    <!--<div id="doodle_left_blue"></div>-->
                    <!--<div id="doodle_left_green"></div>-->
                    <!--<div id="doodle_right_red"></div>-->
                    <!--<div id="doodle_right_yellow"></div>-->
                    <!--<div id="doodle_hash"></div>-->
                </div>
            </div>
            <div id="home1_content">
                <div id="home1_banner">
                    <div id="home1_trendlogo"></div>
                    <!-- Affiche le fil d'actualité du produit -->
                    <a class="hm1-menus-ch jb-hm1-menus-ch" data-menu="news" >News</a>
                    <!-- Propose une Tendance ou un Article des Angels excepté certains compte comme 'JSy' -->
                    <a class="hm1-menus-ch jb-hm1-menus-ch" data-menu="jump" href="{wos/datx:spme}">Surprenez-moi !</a>
                    <a id="home1_dropdownbtn" href="/login">Se connecter</a>
                    <div id="home1_sitelogo">
                        <!--<h1>TRENqR</h1>-->
                        <!--
                        <div >
                            <?php 
                                $so = mt_rand(1,38);
                                if ( $so === 1 || $so === 10 || $so === 16 ) :
                            ?>
                            <h2 id="home1-trenqr-devise">Une <strong>vie</strong> est plus <strong>cool</strong> en #<strong>images</strong> et ça se voit <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 2 ) : ?>
                            <h2 id="home1-trenqr-devise">Si vous voulez vraiment le faire, faites-le <strong>bien</strong> et faites-le en #<strong>image</strong>&nbsp;<span class='italic'>!</span></h2>
                            <?php elseif ( $so === 3 || $so === 9 ) : ?>
                            <h2 id="home1-trenqr-devise">Si t'as pas de bras et pas de chocolat, comment vas-tu séduire <strong>Rihanna</strong>&nbsp;<span class='italic'>!</span></h2>
                            <?php elseif ( $so === 4 ) : ?>
                            <h2 id="home1-trenqr-devise">Si tu n'as pas de compte sur #<strong>Trenqr</strong>, il faut changer de vie <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 5 ) : ?>
                            <h2 id="home1-trenqr-devise">1+1=10 car si 10 était divisible par 5², 10 serait un commun diviseur de 1 <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 6 ) : ?>
                            <h2 id="home1-trenqr-devise">Tu n'auras pas la clé, tant que la porte sera fermée <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 7 ) : ?>
                            <h2 id="home1-trenqr-devise">François Hollande n'avait qu'à ne pas se faire prendre <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 8 || $so === 23 ) : ?>
                            <h2 id="home1-trenqr-devise">Vous allez trouvé ça vraiment très coo <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 11 ) : ?>
                            <h2 id="home1-trenqr-devise">Ce n'est pas parfait, mais nous y travaillons <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 12 || $so === 13 ) : ?>
                            <h2 id="home1-trenqr-devise">Salut, Salām, Shalom, Avé, Heil, Mboté, Hi, Namasté, Wai ... Wesh <span class='italic'>!</span></h2>
                            <?php elseif ( $so >= 14 && $so <= 15 ) : ?>
                            <h2 id="home1-trenqr-devise">Être <strong>amis</strong> ce n'est pas qu'un mot, c'est un privilège <span class='italic'>!</span></h2>
                            <?php elseif ( in_array($so,[17,18,19,20])  ) : ?>
                            <h2 id="home1-trenqr-devise">Faites la même chose, mais en mieux et différemment <span class='italic'>!</span></h2>
                            <?php elseif ( $so >= 21 && $so <= 22 ) : ?>
                            <h2 id="home1-trenqr-devise">Si tu veux faire la différence, ta place est sur Trenqr <span class='italic'>!</span></h2>
                            <?php elseif ( $so >= 23 && $so <= 25 ) : ?>
                            <h2 id="home1-trenqr-devise">Si vous n'avez rien à diffuser, vous aurez quelque chose à regarder <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 26  ) : ?>
                            <h2 id="home1-trenqr-devise">Quelque soit le coté de la force qui vous anime, Trenqr votre choix sera <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 27  ) : ?>
                            <h2 id="home1-trenqr-devise">L'alternative européenne à Facebook, Twitter et Instagram <span class='italic'>!</span></h2>
                            <?php elseif ( $so === 28 || $so === 29 ) : ?>
                            <h2 id="home1-trenqr-devise">Osez la différence, n'osez pas ne pas oser vous amusez sur Trenqr <span class='italic'>!</span></h2>
                            <?php elseif ( $so >= 30 && $so <= 31 ) : ?>
                            <h2 id="home1-trenqr-devise">A quoi ça sert d'avoir 5000 amis ?</h2>
                            <?php elseif ( $so >= 32 && $so <= 33 ) : ?>
                            <h2 id="home1-trenqr-devise">Sans vous, Trenqr ne sera jamais assez cool et fun <span class='italic'>!</span></h2>
                            <?php elseif ( $so >= 35 && $so <= 36 ) : ?>
                            <h2 id="home1-trenqr-devise">S'informer autrement sur ce qui vous interesse en photos et plus</h2>
                            <?php else : ?>
                            <h2 id="home1-trenqr-devise">En vérité, si Dieu était un Homme, il aurait un compte, sur #<strong>Trenqr</strong>&nbsp;<span class='italic'>!</span></h2>
                            <?php endif; ?>
                        </div>
                        -->
                    </div>
                </div>
                <div id="home1_middle">
                    <a id="home1_locker" data-st="undone" title="En apprendre plus" ></a>
                    <div id="home1_middleleft"></div>
                    <div id="home1_middlecenter">
                        <div id="home1_middlecontent_left">
                            <div id="home1-trenqr-why">
                                <h2 class="home1-trenqr-why _x_1">
                                    <!--<span>Partagez votre <strong>vie</strong> et vos <strong>idées</strong>.</span><br/>-->
                                    <!--<span>Partagez les moments de votre <strong>vie</strong>.</span><br/>-->
                                    <!--<span>Partageons les moments de nos <strong>vie</strong>s.</span><br/>-->
                                    <!--<span>Faites de nouvelles <strong>découvertes</strong>.<br/> Sur Trenqr !</span><br>-->
                                    <!--<span>Faisons de nouvelles <strong>découvertes</strong>.<br/> Sur Trenqr !</span><br>-->
                                    <div>
                                        <div style="font-weight: bold; font-size: 100px;">3</div>
                                        <div>Bonnes raisons de partager</div>
                                        <div style="font-size: 28px;">Dans un environnement fun et engagé</div>
                                    </div>    
                                    <div class='this_hide'>
                                        <span id='ontop'>Partagez avec plus de <strong class="underline">fun</strong> et de <strong class="underline">simplicité</strong>.</span><br/>
                                        <span>Trenqr, la communauté pour vivre, s'amuser et partager de meilleures expériences</span><br>
                                    </div>    
                                        
                                </h2>
                                <h2 class="home1-trenqr-why _x_2">
                                    <!--<span>Abonnez-vous et contribuez aux <strong>Tendances</strong> qui vous intéressent.</span>-->
                                    <!--<a href=''>Le réseau social européen, qui redonne de la <u>valeur</u> à ce que vous faites</a>-->
                                    <!--<a href="go-to-article-dedie-blog">En savoir plus ...</a>-->
                                    <!--<span>Trenqr, la communauté pour <strong class="underline">vivre</strong>, <strong class="underline">s'amuser</strong> et <strong class="underline">partager</strong> de meilleures expériences</span>-->
                                    <!--<a href="go-to-article-dedie-blog">vb2.5 : "The wiser challenger"</a>-->
                                </h2>
                                <!--÷-->
                                <!--Le nouvel env fun et engagé où vous aurez 3 bonnes raisons partager-->
                            </div>
                            <div id="home1_discover_zone">
                                <div id="home1_discover_wrapper">
                                    <a id="hm1-dscvr-btn" href="/paradise" role="button">Explorer</a>
                                </div>
                                <!--<div id="home1-trenqr-diff" title="The Wiser Hunter">vb2.1</div>-->
                            </div>
                        </div>
                        <div id="home1_middlecontent_separator"></div>
                        <div id="home1_middlecontent_right">
                            <!--<h3>Inscris-toi en 3 min.<br/><span class='free'>C'est à jamais gratuit <span class="italic">!</span></span></h3>-->
                            <h3>Inscris-toi vite<br/><span class='free'>C'est à jamais gratuit <span class="italic">!</span></span></h3>
                            <!-- Formulaire de type 2: Inputs seulement -->
                            <div id="home1_overlay"><span id="form_errors"></span></div>
                            <form id="home1_preinscription" class="home1_form_item_labels" method="POST" action="/inscription" autocomplete="off">
                                <input id="fullname" class="preg_ins_com_check" data-st="ulock" type="text" name="fullname" placeholder="Nom complet" autocomplete="off" spellcheck="false" maxlength="25">
                                <input id="nickname" class="preg_ins_com_check" data-st="ulock" type="text" name="nickname" placeholder="Pseudo" autocomplete="off" spellcheck="false" maxlength="20">
                                <input id="email" class="preg_ins_com_check" data-st="ulock" type="email" name="email" placeholder="Adresse email" autocomplete="off" spellcheck="false" maxlength="255">
                                <input id="passwd" class="preg_ins_com_check" type="password" name="passwd" placeholder="Mot de passe" autocomplete="off" spellcheck="false">
                                <input id="home1_form_submit_labels" type="submit" value="S'inscrire">
                            </form>
                            <!-- Fin: Formuaire de type 2 -->
                        </div>
                    </div>
                    <div id="home1_middleright"></div>
                </div>
                <div id="home1_footer">
                    <div id="home1_footercontainer">
                        <div id="home1_footereffectivespace">
                            <div id='home1_copyright'>
                                <span>&copy;2015 Trenqr</span>
                            </div>
                            <div id='home1_footercontent'>
                                <div id='home1_footerlinks'>
                                    <ul class="ul_justify">
                                        <!--
                                            [DEPUIS 27-10-15]
                                                Permet d'obtenir de l'utilisateur qu'il se focalise sur la section "en apprendre plus" pour avoir des informations complémentaires
                                        -->
                                        <!-- <li><a href="/about">À propos</a></li> -->
                                        <li><a href="/best-of-the-week">Au hasard</a></li>
                                        <li><a href="//blog.trenqr.com" title="Actualités, tutoriels et le meilleur de Trenqr">Le Blog</a></li> 
                                        <li><a href="//studio.trenqr.com" title="Notre solution pour modifier et personnaliser vos photos gratuitement">Trenqr Studio</a></li> 
                                        <li><a href="//blog.trenqr.com/tutoriels">Les tutoriels</a></li>
                                        <li><a href="/terms">CGU</a></li>
                                        <li><a href="/cookies">Cookies</a></li>
                                        <li><a href="/privacy">Confidentialité</a></li>
                                    </ul>
                                </div>
                                <div id="home1_languageselector" class="jb-tqr-lgslt-mx">
                                    <div id="home1_lang" class="jb-tqr-lgslt-list-mx">
                                        <ul id="home1_langlist" class="jb-tqr-lgslt-list">
                                            <li class="tqr-wlc-lg-ch-mx">
                                                <a class="tqr-wlc-lg-ch jb-tqr-wlc-lg-ch" data-lang="en">English</a>
                                            </li>
                                            <li class="tqr-wlc-lg-ch-mx">
                                                <a class="tqr-wlc-lg-ch jb-tqr-wlc-lg-ch current home1_lang_current" data-lang="fr">Fran&ccedil;ais</a>
                                            </li>
                                        </ul>
                                        <div id="home1_langplus"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div id="tqr-unvrs-scrn">
            <header>
                <nav>
                    <ul id="tqr-u-menus">
                        <li class="tqr-u-menu-mx"><h1><a class="tqr-u-menu cursor-pointer active" >Qu'est ce que Trenqr ?</a></h1></li>
                        <li class="tqr-u-menu-mx"><h1><a class="tqr-u-menu" href="/apropos#tqr-facts-intro">Aperçu</a></h1></li>
                        <li class="tqr-u-menu-mx"><h1><a class="tqr-u-menu" href="/!/recommend-trenqr-image-trend-cool-community">Le manisfeste</a></h1></li>
                        <li class="tqr-u-menu-mx"><h1><a class="tqr-u-menu" href="/connexion">J'ai des questions</a></h1></li>
                    </ul>
                </nav>
<!--                <div>
                    [SLIDER]
                </div>-->
            </header>
            <div id="tqr-unvrs-scrn-bdy">
                <div id="tqr-unvrs-scrn-ctr">
                    <section class="tqr-u-section" data-section="what-trenqr">
                        <!--<h1 class="tqr-u-sctn-tle">Qu'est ce que Trenqr ?</h1>-->
                        <div class="tqr-u-sctn-body">
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="left" ><div>Vous</div></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="what-tqr-q" locdir="left">
                                    <!--<span>On m'a dit beaucoup de bien de votre site, mais j'aimerais en apprendre plus et me faire ma propre opinion. Que pouvez-vous me dire sur Trenqr ? Et surtout à quoi ça sert ?</span>-->
                                    <span>Bonjour, j'aimerais en apprendre plus sur Trenqr. Que pouvez-vous m'en dire ? Et surtout à quoi ça sert ?</span>
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="what-tqr-ans" locdir="right">
<!--                                    <span> 
                                        Bonjour "Vous" ! <strong>Trenqr</strong> est un réseau social alternatif de <strong>microblogging</strong> pour publier des <strong>photos</strong> sur sa <strong>vie</strong> et <strong>échanger</strong> sur ses <strong>passions</strong> de manière <strong>originale</strong>.
                                        Il vous <strong>rapproche</strong> des gens et des choses qui comptent pour vous, tout en vous permettant de vous faire de nouvelles <strong>connaissances</strong> et <strong>amis</strong>. 
                                        Tout cela dans un environnement <strong>dynamique</strong>, <strong>convivial</strong> et <strong>ludique</strong>.
                                        <a href="/apropos">En savoir plus</a>
                                    </span>-->
                                    <div> 
                                        Trenqr est le nouvel environnement fun et engagé qui vous propose de <strong class="bold">simplifier</strong>, de rendre plus <strong class="bold">fun</strong> et <strong class="bold">intéressant</strong> l'utilisation d'un réseau social.
                                    </div>
                                    <div>
                                        De plus, en venant sur Trenqr vous intégrer une <strong class="bold">communauté</strong> qui partage et défend des valeurs communes : <strong class="bold">liberté</strong>, <strong class="bold">esprit ludique</strong>, <strong class="bold">respect</strong> et <strong class="bold">proximité</strong>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="tqr-u-section" data-section="trenqr">
                        <!--<h1 class="tqr-u-sctn-tle">Pourquoi devrais-je <strong>rejoindre</strong> Trenqr ?</h1>-->
                        <div class="tqr-u-sctn-body">
                           <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="left" ><div>Vous</div></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-q" locdir="left">
                                    <!--C'est <strong>intéressant</strong>, surtout pour moi qui suis fan de <strong class="bold">photos</strong> et de <strong class="bold">nouvelles expériences</strong> !--> 
                                    Mais concrètement pourquoi devrais-je <strong>rejoindre</strong> cette <strong>plateforme</strong> ? En quoi est-elle différente des autres <strong>réseaux sociaux</strong> ?
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" /></div>
<!--                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    <div>
                                        Tout dépend de vos attentes, mais je peux vous apporter un début de réponse.
                                    </div>
                                     Un esprit communautaire qui permet aux gens de se parler sans trop d'appréhensions  
                                    <div class="par">
                                        Vous aimez faire de nouvelles rencontres et parler avec des gens qui partagent vos passions ? Trenqr vous apporte cette solution.
                                        <strong class="bold">Trenqr</strong> est un site qui rapproche et crée des <strong>liens</strong> au delà des appréhensions : <strong class="bold">vous allez adorer vous parlez</strong> ! 
                                        <strong class="bold">Trenqr</strong> est un site qui rapproche et crée des <strong>liens</strong> au delà des appréhensions : <strong class="bold">vous allez adorer vous parlez</strong> ! 
                                        Il s'agit d'une vraie <strong class="bold">alternative</strong>, avec beaucoup d'<strong class="bold">originalité</strong> et de <strong class="bold">créativité</strong>, à mi-chemin entre <strong>Facebook</strong>, <strong>Twitter</strong> et <strong>Pinterest</strong>.
                                        Croyez-moi, c’est <strong class="bold">différent</strong> et ça vous changera !
                                    </div>
                                    <div class="par">
                                        Mais encore, <strong>Trenqr</strong> ce sont de petites innovations qui font la différence. <strong class="bold">Partager</strong>, <strong class="bold">discuter</strong>, <strong class="bold">découvrir</strong>, prendre du <strong class="bold">plaisir</strong> et vous amuser, seront vos <strong class="bold">priorités</strong>.
                                        L'aventure vous tente ? Allez-y, <a href="/inscription">l'inscription est <strong>rapide</strong> et <strong>gratuite</strong></a>.
                                    </div>
                                    <div class="par">
                                        Si vous voulez avoir plus de réponses, lisez notre <strong>manifeste</strong> sans langue de bois, alias <a href="/apropos#tqr-facts-intro">#<strong>trenqrFacts</strong></a>.
                                    </div>
                                </div>-->
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    Un réseau social est un réseau social Trenqr, nous n'inventons rien. Mais nous proposons plus, mieux et différent.
                                    Nous vous proposons un univers différent, une mentalité différente, une utilité différente.
                                    
                                    <a href="">Consultez la liste des fonctionnalités et caractéristiques de Trenqr.</a>
                                </div>
                            </div>
                        </div>
                        <div class="tqr-u-sctn-bbl-wpr">
                            <a id="tqr-u-sctn-visit" href="/paradise">Visitez maintenant</a>
                        </div>
                    </section>
                    <section class="tqr-u-section" data-section="trends">
                        <div class="tqr-u-sctn-hdr">
                            <h1 class="tqr-u-sctn-tle">Le concept des Tendances</h1>
                        </div>
                        <div class="tqr-u-sctn-body">
                            <div class="tqr-u-sctn-trd-sec parg">
                                <h2 class="tqr-u-sctn-t-sc-tle">Un espace de créativité et d'échange en <strong>images</strong> et plus</h2>
                                <div class="tqr-u-sctn-t-sc-bdy jb-tqr-u-sctn-t-sc-bdy">
                                    <!-- C'est quoi une Tendance -->
                                    <div class="tqr-u-sctn-t-sc-txt">
                                        <div class="tqr-u-sctn-bbl-bbl">
                                            <div class="par">
                                                Une <strong class="bold">Tendance</strong> est en même temps un espace virtuel de <strong class="bold">découverte</strong> et d'<strong class="bold">échange</strong>, 
                                                et un moyen de vous <strong class="bold">rapprocher</strong> de gens qui <strong>partagent</strong> les mêmes <strong class="bold">centres d'intérêt</strong> que vous. 
                                                Grâce aux <strong>photos</strong> et plus, vos <strong>échanges</strong> deviennent plus riches.
                                            </div>
                                        </div>
                                    </div>
                                    <!-- A quoi sert Tendance -->
                                    <div class="tqr-u-sctn-t-sc-txt">
                                        <div class="tqr-u-sctn-bbl-bbl">
                                            <div class="par">
                                                <span class="bold">A quoi ça sert ?</span> Vous pouvez vous en servir pour lancer une "<strong>trend</strong>" et offrir la possibilité aux autres utilisateurs d'y contribuer.
                                                Ensemble, vous <strong class="bold">partagez</strong>, vous <strong class="bold">découvrez</strong>, vous vous <strong class="bold">amusez</strong> !
                                            </div>
                                            <div class="par">
                                                 Mieux, une <strong class="bold">Tendance</strong> vous permet de <strong class="bold">rencontrer</strong> facilement de nouvelles <strong class="bold">connaissances</strong> et <strong class="bold">amis</strong>. 
                                                 L'avantage, c'est qu'ils sont comme vous, ils sont là pour les mêmes raisons, ça facilite les choses.
                                            </div>
                                            <div class="par">
                                                Imaginez les possibilités qui s'offrent à vous, elles sont <strong class="bold">SANS LIMITES</strong> ! 
                                                Vous pouvez être maître de la <strong>tendance</strong>, <strong>contributeur</strong> ou simplement en profiter, en tant que spectateur.
                                                Voilà la véritable utilité d'un <strong>réseau social</strong> : faire des <strong class="bold">rencontres</strong>, limiter les <strong>barrières</strong> et élargir le champ des <strong class="bold">possibilités</strong>. 
                                                A vous de <strong>jouer</strong> !
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tqr-u-sctn-trd-sec smpl">
                                <h2 class="tqr-u-sctn-t-sc-tle">Quelques Tendances populaires</h2>
                                <div class="tqr-u-sctn-t-sc-bdy jb-tqr-u-sctn-t-sc-bdy" data-scp='sample'>
                                    <?php for($i=0;$i<0;$i++) : ?>
                                    <article class="tqr-u-smr-articles-bmx <?php if ( $i === 3 ) echo "last"; ?>">
                                        <div class="tqr-u-smr-art-trd-mx">
                                            <div class="tqr-u-smr-art-trd-cvr">
                                                <a class="tqr-u-smr-art-trd-cvr-hrf" href="">
                                                    <span class='tqr-u-smr-art-trd-cvr-fd'></span>
                                                    <img class="tqr-u-smr-art-trd-cvr-i" src="http://www.lorempixel.com/280/160/animals/<?php echo rand(1,10); ?>" width="280px" height="160px" alt="TRD_DESCIPTION" />
                                                    <!--<img src="http://www.placehold.it/280x160" alt="" />-->
                                                </a>
                                            </div>
                                            <div class="tqr-u-smr-art-trd-hdr">
                                                <header class="tqr-u-smr-art-trd-hdr-tle-mx">
                                                    <h4><a class="tqr-u-smr-art-trd-hdr-tle" href="">Titre de la tendance</a></h4>
                                                </header>
                                                <div class="tqr-u-smr-art-trd-hdr-dsc-mx">
                                                    <a class="tqr-u-smr-art-trd-hdr-dsc" href="">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean elementum, augue fermentum elementum feugiat, diam nisi lacinia tortor, vitae dictum ante mi efficitur odio. Pellentesque facilisis sed.
                                                    </a>
                                                </div>
                                                <footer class="tqr-u-smr-art-trd-hdr-xtra-mx">
                                                    <a class="tqr-u-smr-art-trd-hdr-xt-ownr" href="">
                                                        <span class="tqr-u-smr-art-trd-hdr-xt-psd" >@pseudo</span>
                                                    </a>
                                                    <span class="tqr-u-smr-art-trd-hdr-xt-time">Il y a 2 heures</span>
                                                </footer>
                                            </div>
                                        </div>
                                    </article>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="tqr-u-section" data-section="socl-mdl">
                        <header class="tqr-u-sctn-hdr">
                            <h1 class="tqr-u-sctn-tle">Le modèle social de Trenqr</h1>
                            <div class="tqr-u-sctn-intro">
                                <h2>Un modèle graduel. Un esprit communautaire.</h2>
                            </div>
                        </header>
                        <div class="tqr-u-sctn-body">
                            <div id="tqr-u-sctn-scmdl-ctr">
                                <ul id="tqr-u-sctn-scmdl-lsts">
                                    <li class="tqr-u-sctn-scmdl-lst">
                                        <div class="tqr-u-sctn-scmdl-lst-wpr">
                                            <div class="tqr-u-sctn-scmdl-lst-l" data-scp="relation"></div>
                                            <div class="tqr-u-sctn-scmdl-lst-r kapa1">
                                                <strong></strong>
                                                <strong>Trenqr</strong> vous permet de vous faire de nouveaux <strong>amis</strong>. Mais avant, c'est toujours mieux d'<span class="bold dip" style="margin-right: 5px;">apprendre à se connaitre</span> <i class="fa fa-smile-o"></i>
                                                Suivez des utilisateurs pour ne rien manquer de leurs <strong>actualités</strong>, afin de développer vos <strong>affinités</strong>.
                                            </div>
                                        </div>
                                    </li><strong></strong>
                                    <li class="tqr-u-sctn-scmdl-lst">
                                        <div class="tqr-u-sctn-scmdl-lst-wpr">
                                            <div class="tqr-u-sctn-scmdl-lst-l" data-scp="friend"><div class="background" style="background: url('{wos/sysdir:img_dir_uri}/r/frd-ctr-blk.png') no-repeat; background-size: 55%; background-position: 50% 50%;"></div></div>
                                            <div class="tqr-u-sctn-scmdl-lst-r kapa1">
                                                Vos <strong>échanges</strong> se font plus fréquents, pourquoi ne pas  <strong class="bold">devenir amis</strong> et faire plus ample <strong>connaissance</strong> ? 
                                                En devenant <strong>amis</strong>, vous <strong>partagez</strong> encore plus et vous devenez plus <strong>intime</strong> grâce à nos outils dédiés. 
                                                Être <strong>amis</strong> ce n'est pas qu'un mot !
                                            </div>
                                        </div>
                                    </li>
                                    <li class="tqr-u-sctn-scmdl-lst">
                                        <div class="tqr-u-sctn-scmdl-lst-wpr">
                                            <div class="tqr-u-sctn-scmdl-lst-l" data-scp="comy"><div class="background" style="background: url('{wos/sysdir:img_dir_uri}/r/team_blk.png') no-repeat; background-size: 50%; background-position: 50% 50%;"></div></div>
                                            <div class="tqr-u-sctn-scmdl-lst-r kapa1">
                                                <strong>Trenqr</strong> a été conçu pour être une <strong class="bold">communauté</strong> d'<strong>échange</strong>, dans un <strong>univers</strong> de <strong class="bold">proximité</strong>, de <strong>convivialité</strong> et de <strong>respect</strong> des <strong>différences</strong>.
                                                Grâce à ces buts communs, nous créons un <strong class=""bold>environnement ouvert à tous</strong>, <strong class="bold">ludique</strong> et <strong class="bold">convivial</strong>. On se tutoie ? 
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
<!--                    <section class="tqr-u-section" data-section="mi">
                        <div id="">
                            <div class="tqr-u-sctn-hdr">
                                <h1 class="tqr-u-sctn-tle">Messagerie instantannée</h1>
                            </div>
                            <div class="tqr-u-sctn-body">
                                <div class="tqr-u-sctn-mi-txt top kapa1">
                                    Vous avez envie de plus d'<strong class='bold'>intimité</strong> ? La <strong>messagerie instantannée</strong> intégrée à <strong>Trenqr</strong>, vous permet de <strong class='bold'>communiquer en privé</strong> avec vos <strong>amis</strong>.
                                </div>
                                <div class="tqr-u-sctn-mi-txt kapa1">
                                    Grâce à cette fonctionnalité, vous discuter sans compter avec vos <strong>amis</strong>, tout au long de votre navigation. 
                                    Raison de plus pour faire évoluer la <strong>relation</strong> que vous avez avec certains utilisateurs, en les demandant en <strong>ami</strong>.
                                </div>
                            </div>
                        </div>
                    </section>-->
                    <section class="tqr-u-section" data-section="more">
                        <header>
                            <h1 class="tqr-u-sctn-tle">Mais ce n'est pas tout...</h1>
                            <div class="tqr-u-sctn-intro">
                                <h2>De petites choses qui font une grande différence</h2>
                            </div>
                        </header>
                        <div class="tqr-u-mr-sct-mx">
                            <div class="tqr-u-mr-sct-l" data-scp="appr"><span class="extras">x 3</span></div>
                            <div class="tqr-u-mr-sct-r">
                                <h2 class="tqr-u-sctn-tle-2">Trenqr c'est 3 fois plus de moyens de s'exprimer !</h2>
                                <div class="kapa1">
                                    Vous en avez rêver ? Nous l'avons fait. Trenqr vous offre différents moyens de vous exprimer, pour une meilleure expérience d'utilisation. 
                                    Vous avez enfin le choix d'aimer ou de ne pas adhérer !
                                </div>
                            </div>
                        </div>
                        <div class="tqr-u-mr-sct-mx">
                            <div class="tqr-u-mr-sct-l" data-scp="cap"></div>
                            <div class="tqr-u-mr-sct-r">
                                <h2 class="tqr-u-sctn-tle-2">Votre dynamisme et votre créativité sont récompensés !</h2>
                                <div class="kapa1">
                                    Nous avons intégré des <strong class="bold">QuCoins</strong> pour rendre plus fun et ludique votre utilisation de Trenqr. 
                                    Les <strong class="bold">QuCoins</strong> ne sont pas indispensables à l'utilisation de Trenqr, mais c'est toujours sympa et utile d'en avoir !
                                </div>
                            </div>
                        </div>
                        <div class="tqr-u-mr-sct-mx">
                            <div class="tqr-u-mr-sct-l" data-scp="nwfd"></div>
                            <div class="tqr-u-mr-sct-r">
                                <h2 class="tqr-u-sctn-tle-2">Vous aurez le NewsFeed le plus cool d'internet !</h2>
                                <div class="kapa1">
                                    <strong class="bold">Newsfeed</strong> est un outil dynamique et facile d'utilisation, qui vous permet de suivre l'activité et les derniers évènements dans votre réseau.
                                    <strong class="bold">Pratique</strong>, il est disponible et accessible à tout moment !
                                </div>
                            </div>
                        </div>
                        <div class="tqr-u-mr-sct-mx">
                            <div class="tqr-u-mr-sct-l" data-scp="tools"></div>
                            <div class="tqr-u-mr-sct-r">
                                <h2 class="tqr-u-sctn-tle-2">Des outils qui vous simplifie la vie !</h2>
                                <div class="kapa1">
                                    Pour vous offrir une meilleure expérience d'utilisation, nous développons <strong>Trenqr</strong> pour qu'il soit toujours plus <strong class="bold">accessible</strong>, <strong class="bold">pratique</strong> et <strong class="bold">utile</strong> au quotidien. 
                                    Alors, attendez vous à toujours plus d'innovations et de solutions pour vous <strong class="bold">simplifier la vie</strong>. C'est une promesse !
                                </div>
                            </div>
                        </div>
                        <div id="tqr-u-fnl-bdy-ftr">
                            <h1>C'est <strong>beau</strong>, <strong>ludique</strong> et <strong>convivial</strong></h1>
                        </div>
                    </section>
                    <section class="tqr-u-section" data-section='final' >
                        <header>
                            <h1 id="tqr-u-fnl-tle">C'est tout, pour le moment !</h1>
                            <div id="tqr-u-fnl-intro">Pour le reste, il ne tient qu'à vous de le découvrir.</div>
                        </header>
                        <div id="tqr-u-fnl-body" style="background: url('http://beta.trenqr.com/marge1/tqim/article/229k702d332/9h4e57col_3d313i37351h_9h4e57co5e.jpg') no-repeat;; background-size: 100%;">
                            <div id="tqr-u-fnl-bdy-hdr">
                                <h2>S'inscrire ne vous prendra que quelques minutes</h2>
                            </div>
                            <form id="tqr-u-fnl-bdy-form" method="POST" action="/connexion" autocomplete="off">
                                <div class="tqr-u-fnl-frm-fld-wpr">
                                    <input id="fullname" class="tqr-u-fnl-frm-fld" data-st="ulock" type="text" name="fullname" placeholder="Nom complet" autocomplete="off" spellcheck="false" maxlength="25" required>
                                </div>
                                <div class="tqr-u-fnl-frm-fld-wpr">
                                    <input id="nickname" class="tqr-u-fnl-frm-fld" data-st="ulock" type="text" name="nickname" placeholder="Pseudo" autocomplete="off" spellcheck="false" maxlength="20" required>
                                </div>
                                <div class="tqr-u-fnl-frm-fld-wpr">
                                    <input id="email" class="tqr-u-fnl-frm-fld" data-st="ulock" type="email" name="email" placeholder="Adresse email" autocomplete="off" spellcheck="false" maxlength="255" required>
                                </div>
                                <div class="tqr-u-fnl-frm-fld-wpr">
                                    <input id="passwd" class="tqr-u-fnl-frm-fld" type="password" name="passwd" placeholder="Mot de passe" autocomplete="off" spellcheck="false" required>
                                </div>
                                <div id="tqr-u-fnl-frm-errbx" class="this_hide">
                                    Message d'erreur 
                                </div>
                                <div class="tqr-u-fnl-frm-fld-wpr last">
                                    <input id="tqr-u-fnl-frm-submit" type="submit" value="S'inscrire">
                                </div>
                            </form>
                            <div id="tqr-u-fnl-bdy-bckg-ownr">
                                <a id="tqr-u-fnl-bckg-ownr" class="" href="/paradise">
                                    <span>Publié par</span><span id="tqr-u-fnl-bckg-o-psd">@Paradise</span>
                                    <!--<img id="tqr-u-fnl-bckg-o-i" height="20px" width="20px" src="http://www.lorempixel.com/20/20" />-->
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div id="home1_backToTop"></div>
    </div>
    
    <div id="tqr-last-news-mx" class="jb-tqr-last-news-mx this_hide">
        <div id="tqr-last-news">
            <div id="tqr-last-news-tle">
                <span id="tqr-last-news-tle-tle">Dernières actualités de Trenqr</span>
                <a id="tqr-last-news-clz" class="cursor-pointer jb-tqr-last-news-clz">&times;</a>
            </div>
            <ul id="tqr-last-news-lst">
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>Annonce</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">25 Sept. 2015</span>
                        <span class="tqr-last-n-n-msg">Sortie officielle de la première version publique. La plateforme a été publiée sous la version beta vb1.0 (alias "Christophe Colomb").</span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>Annonce</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">6 Nov. 2015</span>
                        <span class="tqr-last-n-n-msg">
                            Lancement de la page officielle de Trenqr sur Facebook : <a href="http://fb.me/trenqr" target="_blank">fb.me/trenqr</a>. 
                            Abonnez-vous pour ne rien rater de notre actualité et de celles de nos utilisateurs.
                        </span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>Annonce</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">6 Nov. 2015</span>
                        <span class="tqr-last-n-n-msg">
                            Lancement de la page officielle de Trenqr sur Twitter : <a href="http://twitter.com/trenqr" target="_blank">twitter.com/trenqr</a>.
                            Suivez-nous pour ne rien rater de notre actualité et de celles de nos utilisateurs.
                        </span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>Annonce</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">25 Nov. 2015</span>
                        <span class="tqr-last-n-n-msg">Sortie de la version évoluée : vb2.0 (alias "The Boredom Killer"). <a target="_blank" href="//blowlbot.xyz">Qu'est-ce qu'il y a de nouveaux ?</a></span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>Annonce</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">2x Dec. 2015</span>
                        <span class="tqr-last-n-n-msg">Sortie de la version évoluée : vb2.1 (alias "The Wiser Hunter"). <a target="_blank" href="//blog.trenqr.com">Qu'est-ce qu'il y a de nouveaux ?</a></span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>Annonce</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">2x Dec. 2015</span>
                        <span class="tqr-last-n-n-msg">Sortie du blog officiel « Trenqr, the cool vide for all ». <a target="_blank" href="//blog.trenqr.com">En savoir plus</a>.</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    {wos/dvt:whyacc}

    <!-- JQUERY -->   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js?{wos/systx:now}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js?{wos/systx:now}"></script>

    <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script> 
    <script src="{wos/sysdir:script_dir_uri}/w/c.c/fr.dolphins.js?{wos/systx:now}"></script>
<!--    <script src="{wos/sysdir:script_dir_uri}/w/d/home1_dropdown.d.js?{wos/systx:now}"></script>
    <script src="{wos/sysdir:script_dir_uri}/w/d/home1_dropdownvalidator.d.js?{wos/systx:now}"></script>-->
    <!-- 
        [NOTE 03-07-15] @BOR
        Retiré et remplacé par home.js
    <script src="{wos/sysdir:script_dir_uri}/w/d/home1_formvalidator.d.js?{wos/systx:now}"></script>
    -->
    <script src="{wos/sysdir:script_dir_uri}/r/csam/lgselect.csam.js?{wos/systx:now}"></script>
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/home1_langselect.d.js?{wos/systx:now}"></script>-->
    <!-- 
        [NOTE 03-07-15] @BOR
        Retiré et remplacé par home.js
    <script src="{wos/sysdir:script_dir_uri}/w/d/home1_scrolling.d.js?{wos/systx:now}"></script>
    -->
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/home1_trialvalidator.d.js?{wos/systx:now}"></script>-->
    <script src="{wos/sysdir:script_dir_uri}/w/d/scrollTop.d.js?{wos/systx:now}"></script>
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/home1_ajax_validation.d.js?{wos/systx:now}"></script>-->
    <!--<script src="{wos/sysdir:script_dir_uri}/w/d/home_overlay.d.js?{wos/systx:now}"></script>-->
    <!-- 
        [NOTE 03-07-15] @BOR
        Retiré 
        <script src="{wos/sysdir:script_dir_uri}/w/c.c/browserDetect.js?{wos/systx:now}"></script>
    -->
    <script src="{wos/sysdir:script_dir_uri}/r/s/home.js?{wos/systx:now}"></script>
</div>