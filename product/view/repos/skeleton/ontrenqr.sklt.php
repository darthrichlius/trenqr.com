<?php
    $rnlg = "{wos/datx:runlang}";
    
    set_error_handler('exceptions_error_handler');
    try {
        restore_error_handler();
    } catch (Exception $exc) {
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');
        $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
    }
?>
<div s-id="ontrenqr">
    <span id="view" class="jb-pg-vw" data-view="{wos/datx:view}"></span>
    <div class='theater'></div>
    
    <div id="home1_scrolllock">
        <!--<div class="overlay" id="home_overlay">Lorem ipsum dolor sit amet.<span class="clear"></span></div>-->
        <div id="home_theater"></div>
        <div id="home1">
            <div id="home1_headbackground">
                <div id="home1_doodles">
                    <!--<div id="doodle_arrow" class="jb-ddle-arrw">-->
                        <!--<span id="doodle-arrow-alt">{wos/deco:_PG_HOME_TX015}<br> {wos/deco:_PG_HOME_TX016}</span>-->
                        <!--<span id="doodle-arrow-alt">Qu'est ce que Trenqr ?<br> Cliquez pour tout savoir</span>-->
                        <!--<span id="doodle-arrow-alt">Cliquez ici pour en apprendre plus</span>-->
                        <!--<span id="doodle-arrow-alt">Cliquez sur le cadenas</span>-->
                    <!--</div>-->
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
                    
                    <div id="tqr-home1-logo-slogan-bmx">
                        <!--<img id="tqr-home1-logo" src="{wos/sysdir:img_dir_uri}/r/fav3.png" width="50" height="50" alt="Trenqr" />-->
                        <!--<img id="tqr-home1-logo" src="{wos/sysdir:img_dir_uri}/r/logo_tqr_beta_blc.png" height="50" alt="Trenqr" />-->
                        <img id="tqr-home1-logo" src="{wos/sysdir:img_dir_uri}/r/logo_tqr_or.png" width="120" alt="Trenqr • Amis et passions en photos et vidéos" />
                        <!--<span id="tqr-home1-slogan">{wos/deco:_PG_HOME_TX001}</span>-->
                    </div>
                    
                    <!--<div id="home1_trendlogo"></div>-->
                    <!-- Affiche le fil d'actualité du produit -->
                    <!--<a class="hm1-menus-ch jb-hm1-menus-ch" data-menu="news" >{wos/deco:_PG_HOME_TX002}</a>-->
                    <!-- Propose une Tendance ou un Article des Angels excepté certains compte comme 'JSy' -->
                    <a class="hm1-menus-ch jb-hm1-menus-ch" data-menu="jump" href="{wos/datx:spme}">{wos/deco:_PG_HOME_TX0027}</a>
                    <a id="home1_dropdownbtn" href="/login">{wos/deco:_PG_HOME_TX004}</a>
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
                    <!--<a id="home1_locker" data-st="undone" title="En apprendre plus" ></a>-->
                    <div id="home1_middleleft"></div>
                    <div id="home1_middlecenter">
                        <div id="home1_middlecontent_left">
                            <div id="home1-trenqr-why">
                                <h2 class="home1-trenqr-why _x_1">
                                    <?php if ( $rnlg === "fr" ) : ?>
                                    <div>
                                        <div style="font-weight: bold; font-size: 70px;">Trenqr</div>
<!--                                        <h1>Partagez votre vie et vos passions</h1>
                                        <h1 style="font-size: 26px;">Faites de <u>nouveaux</u> amis</h1>-->
                                        <div>
                                            n'est pas un site de rencontre mais un réseau social 
                                            qui vous aide à trouver des amis, avec lesquels vous partagez des passions communes.
                                        </div>
                                    </div>   
<!--                                    <div class=''>
                                        <span id='ontop'>Partagez avec plus de <strong class="underline">fun</strong> et de <strong class="underline">simplicité</strong>.</span><br/>
                                        <span>Trenqr, la communauté pour vivre, s'amuser et partager de meilleures expériences</span><br>
                                    </div>    -->
                                    <?php elseif ( $rnlg === "en" ) : ?>
                                    <div>
                                        <h1>
                                            <div style="font-weight: bold; font-size: 70px;">Trenqr</div>
                                            <div>Share with your <u>new</u> friends</div>
                                            <div style="font-size: 28px;">your life and your passions</div>
                                        </h1> 
                                    </div>  
                                    <?php endif; ?>
                                </h2>
                                <h2 class="home1-trenqr-why _x_2"></h2>
                            </div>
                            <div id="home1_discover_zone">
                                <div id="home1_discover_wrapper">
                                    <?php 
                                        $so = mt_rand(1,50);
//                                        echo "<span style='color: #fff'>$so</span>";
                                        if ( $so >= 1 && $so <= 10 ) :
                                    ?>
                                    <a id="hm1-dscvr-btn" href="/looka" role="button">{wos/deco:_PG_HOME_TX006}</a>
                                    <?php elseif ( $so >= 10 && $so <= 20 ) : ?>
                                    <a id="hm1-dscvr-btn" href="/lyly" role="button">{wos/deco:_PG_HOME_TX006}</a>
                                    <?php elseif ( $so > 20 && $so <= 30 ) : ?>
                                    <a id="hm1-dscvr-btn" href="/tendance/9h4lc3aoa/Recettes-de-cuisine-en-photos-du-plaisir-culinaire-%C3%A0-partager" role="button">{wos/deco:_PG_HOME_TX006}</a>
                                    <?php elseif ( $so > 30 && $so <= 40 ) : ?>
                                    <a id="hm1-dscvr-btn" href="/tendance/9l78hiko12/Summer-Weight-Challenge-Perdre-20-kilos-en-deux-mois" role="button">{wos/deco:_PG_HOME_TX006}</a>
                                    <?php elseif ( $so > 40 ) : ?>
                                    <a id="hm1-dscvr-btn" href="/tendance/9l21gg5o11/BuzzVids-France-Cest-fort-en-%C3%A9motions" role="button">{wos/deco:_PG_HOME_TX006}</a>
                                    <?php endif; ?>
                                </div>
<!--                                <div id='home1-tqr-why-slog-1'>
                                    <h2>{wos/deco:_PG_HOME_TX001}</h2>
                                </div>   -->
                            </div>
                        </div>
                        <div id="home1_middlecontent_separator"></div>
                        <div id="home1_middlecontent_right">
                            <h3>{wos/deco:_PG_HOME_TX007}<br/><span class='free'>{wos/deco:_PG_HOME_TX008} <span class="italic">!</span></span></h3>
                            <!-- Formulaire de type 2: Inputs seulement -->
                            <div id="home1_overlay"><span id="form_errors"></span></div>
                            <form id="home1_preinscription" class="home1_form_item_labels" method="POST" action="/inscription" autocomplete="off">
                                <input id="fullname" class="preg_ins_com_check" data-st="ulock" type="text" name="fullname" placeholder="{wos/deco:_PG_HOME_TX009}" autocomplete="off" spellcheck="false" maxlength="25">
                                <input id="nickname" class="preg_ins_com_check" data-st="ulock" type="text" name="nickname" placeholder="{wos/deco:_PG_HOME_TX010}" autocomplete="off" spellcheck="false" maxlength="20">
                                <input id="email" class="preg_ins_com_check" data-st="ulock" type="email" name="email" placeholder="{wos/deco:_PG_HOME_TX011}" autocomplete="off" spellcheck="false" maxlength="255">
                                <input id="passwd" class="preg_ins_com_check" type="password" name="passwd" placeholder="{wos/deco:_PG_HOME_TX012}" autocomplete="off" spellcheck="false">
                                <input id="home1_form_submit_labels" type="submit" value="{wos/deco:_PG_HOME_TX013}">
                            </form>
                            <!-- Fin: Formuaire de type 2 -->
                            <div id="home1-signup-fb-mx">
                                <a id="home1-signup-fb-lk" href="/signup">{wos/deco:_PG_HOME_TX014} <i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="home1_middleright"></div>
                </div>
                <div id="home1_footer">
                    <div id="home1_footercontainer">
                        <div id="home1_footereffectivespace">
                            <div id='home1_copyright'>
                                <span>&copy;2016 Trenqr, passionnément !</span>
                            </div>
                            <div id='home1_footercontent'>
                                <div id='home1_footerlinks'>
                                    <ul class="ul_justify">
                                        <!--
                                            [DEPUIS 27-10-15]
                                                Permet d'obtenir de l'utilisateur qu'il se focalise sur la section "en apprendre plus" pour avoir des informations complémentaires
                                        -->
                                         <li><a href="/apropos" title="En apprendre plus sur Trenqr">À propos</a></li> 
                                        <!--<li><a href="/best-of-the-week">Aperçu</a></li>-->
                                        <li><a href="http://blog.trenqr.com/trenqr-reseau-social-francais-nouveaux-amis-passions/" title="Actualités, tutoriels et le meilleur de Trenqr">{wos/deco:_PG_HOME_TX018}</a></li> 
                                        <!--<li><a href="//studio.trenqr.com" title="Notre solution pour modifier et personnaliser vos photos gratuitement">{wos/deco:_PG_HOME_TX019}</a></li>--> 
                                        <li><a href="http://blog.trenqr.com/category/trenqr-tutorials/">{wos/deco:_PG_HOME_TX020}</a></li>
                                        <li><a href="/{wos/deco:_PG_HOME_TX021}">{wos/deco:_PG_HOME_TX021}</a></li>
                                        <li><a href="/{wos/deco:_PG_HOME_TX022}">{wos/deco:_PG_HOME_TX022}</a></li>
                                        <li><a href="/{wos/deco:_PG_HOME_TX023_LK}">{wos/deco:_PG_HOME_TX023}</a></li>
                                    </ul>
                                </div>
                                <div id="home1_languageselector" class="jb-tqr-lgslt-mx">
<!--                                    <div id="home1_lang" class="jb-tqr-lgslt-list-mx">
                                        <ul id="home1_langlist" class="jb-tqr-lgslt-list">-->
                                    <div class="tqr-lgslt-list-mx home jb-tqr-lgslt-list-mx">
                                        <ul class="tqr-lgslt-list home jb-tqr-lgslt-list">
                                            <li class="tqr-wlc-lg-ch-mx jb-tqr-wlc-lg-ch-mx" data-lang="en">
                                                <a class="tqr-wlc-lg-ch jb-tqr-wlc-lg-ch" data-lang="en">English</a>
                                            </li>
                                            <li class="tqr-wlc-lg-ch-mx jb-tqr-wlc-lg-ch-mx" data-lang="fr">
                                                <a class="tqr-wlc-lg-ch jb-tqr-wlc-lg-ch current css-lang-current" data-lang="fr">Fran&ccedil;ais</a>
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
            <section class="tqr-5steps-wdw-mx" data-sec='home'>
                <header class="tqr-5steps-hdr-mx">
                    <h2 class="tqr-5steps-hdr-tle">Trenqr en 5 étapes</h2>
                </header>
                <div class="tqr-5steps-bdy-mx">
                    <ul class="tqr-5steps-lst-mx">
                        <li class="tqr-5steps-l-lnbx">
                            <div class="tqr-5steps-l-lb-nb">1</div>
                            <div class="tqr-5steps-l-lb-r">
                                <h2 class="tqr-5steps-l-lb-r-tle">Je m'inscris et embellie mon profil</h2>
                                <div class="tqr-5steps-l-lb-r-bdy">
                                    Pour vous faire de <strong class="bold">nouveaux amis</strong>, il est impératif qu'ils en sache un peu plus sur vous, sur ce que vous voulez et 
                                    <strong class="bold">ce qui se passe dans votre vie</strong>.
                                </div>
                            </div>
                        </li>
                        <li class="tqr-5steps-l-lnbx">
                            <div class="tqr-5steps-l-lb-nb">2</div>
                            <div class="tqr-5steps-l-lb-r">
                                <h2 class="tqr-5steps-l-lb-r-tle">Je m'abonne et je contribue aux Salons de mon choix</h2>
                                <div class="tqr-5steps-l-lb-r-bdy">
                                    Les <strong class="bold">Salons</strong> sont les lieux incontournables pour faire de nouvelles connaissances, de nouveaux amis et faire de nouvelles <strong class="bold">découvertes</strong>.
                                    Trenqr compte de nombreux Salons. Que vous aimiez le <strong class="bold">sport</strong>, les <strong class="bold">jeux vidéos</strong>, la <strong class="bold">cuisine</strong> ou l'<strong class="bold">humour</strong>, vous trouverez un salon qui vous convient. 
                                    <a href="javascript:;">Voir la liste des Salons officels</a>
                                </div>
                            </div>
                        </li>
                        <li class="tqr-5steps-l-lnbx">
                            <div class="tqr-5steps-l-lb-nb">3</div>
                            <div class="tqr-5steps-l-lb-r">
                                <h2 class="tqr-5steps-l-lb-r-tle">Je discute avec les personnes qui m'interesse</h2>
                                <div class="tqr-5steps-l-lb-r-bdy">
                                    Chaque Salon a son Chat (<strong class="bold">messagerie instantannée</strong>). Vous y rencontrerez des personnes qui ont les mêmes passions que vous.
                                </div>
                            </div>
                        </li>
                        <li class="tqr-5steps-l-lnbx">
                            <div class="tqr-5steps-l-lb-nb">4</div>
                            <div class="tqr-5steps-l-lb-r">
                                <h2 class="tqr-5steps-l-lb-r-tle">Je visite leurs profils, je commente leurs publications et je m'abonne</h2>
                                <div class="tqr-5steps-l-lb-r-bdy">
                                    Maintenant que vous avez repéré et discuté avec les bonnes personnes, il est temps d'approfondir et de montrer un peu plus d'intéret.
                                    Rendez leur visite, impliquez-vous dans leur vie et <strong class="bold">suivez les</strong>.
                                </div>
                            </div>
                        </li>
                        <li class="tqr-5steps-l-lnbx">
                            <div class="tqr-5steps-l-lb-nb">5</div>
                            <div class="tqr-5steps-l-lb-r">
                                <h2 class="tqr-5steps-l-lb-r-tle">Je demande en ami le moment venu</h2>
                                <div class="tqr-5steps-l-lb-r-bdy">
                                    Les jours passent et vous vous découvrez beaucoup de points communs. Il est temps de franchir le pas, <strong class="bold">demandez le-la en ami</strong>.
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="tqr-5steps-inr-ftr">
                        <a class="tqr-5steps-inr-f-ins" href="/inscription">Commencez maintenant</a>
                    </div>
                </div>
            </section>
        </div>
        <div id="home1_backToTop"></div>
    </div>
    
    <div id="tqr-last-news-mx" class="jb-tqr-last-news-mx this_hide">
        <div id="tqr-last-news">
            <div id="tqr-last-news-tle">
                <span id="tqr-last-news-tle-tle">{wos/deco:_PG_HOME_SC_NEWS_TX002}</span>
                <a id="tqr-last-news-clz" class="cursor-pointer jb-tqr-last-news-clz">&times;</a>
            </div>
            <ul id="tqr-last-news-lst">
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>{wos/deco:_PG_HOME_SC_NEWS_TX001}</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">{wos/deco:_PG_HOME_SC_NEWS_TX003_001}</span>
                        <span class="tqr-last-n-n-msg">{wos/deco:_PG_HOME_SC_NEWS_TX003_002}</span>
                    </div>
                </li>
<!--                <li class="tqr-last-news-news">
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
                </li>-->
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>{wos/deco:_PG_HOME_SC_NEWS_TX001}</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">{wos/deco:_PG_HOME_SC_NEWS_TX004_001}</span>
<!--                        <span class="tqr-last-n-n-msg">Sortie de la version beta : vb2.0 (alias "The Boredom Killer"). <a target="_blank" href="//blowlbot.xyz">Qu'est-ce qu'il y a de nouveaux ?</a></span>-->
                        <span class="tqr-last-n-n-msg">{wos/deco:_PG_HOME_SC_NEWS_TX004_002}</span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>{wos/deco:_PG_HOME_SC_NEWS_TX001}</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">{wos/deco:_PG_HOME_SC_NEWS_TX005_001}</span>
                        <span class="tqr-last-n-n-msg">{wos/deco:_PG_HOME_SC_NEWS_TX005_002}</span>
                    </div>
                </li>
                <li class="tqr-last-news-news">
                    <div class="tqr-last-n-n-tle">
                        <h3>{wos/deco:_PG_HOME_SC_NEWS_TX001}</h3>
                    </div>
                    <div class="tqr-last-n-n-bdy">
                        <span class="tqr-last-n-n-dte">{wos/deco:_PG_HOME_SC_NEWS_TX006_001}</span>
                        <span class="tqr-last-n-n-msg">{wos/deco:_PG_HOME_SC_NEWS_TX006_002}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    {wos/dvt:expl_room}
    {wos/dvt:nolang}
    <noscript>
        <div class='jsw_main_warning'>
            <div class='jsw_title'>Trenqr a besoin de JavaScript pour fonctionner correctement</div>
            <div class='jsw_sub'>Si vous ne l'activez pas dans les préférences de votre navigateur, vous ne pourrez pas profiter du site dans son int&eacute;gralit&eacute;</div>
        </div>
    </noscript>
    
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
    <script src="{wos/sysdir:script_dir_uri}/r/c.c/perfect-scrollbar-0.4.6.with-mousewheel.min.js"></script>
    <script src="{wos/sysdir:script_dir_uri}/r/s/home.js?{wos/systx:now}"></script>
</div>