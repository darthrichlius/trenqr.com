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
            <section id="tqr-unv-more">
                <header>
                    <nav>
                        <ul id="tqr-u-menus">
                            <li class="tqr-u-menu-mx jb-tqr-u-menu-mx">
                                <h1>
                                    <a class="tqr-u-menu jb-tqr-u-menu active" data-action="nav-menu" data-target="home_tqr_wha" href="javascript:;">{wos/deco:_PG_HOME_TX024}</a>
                                </h1>
                            </li>
                            <li class="tqr-u-menu-mx jb-tqr-u-menu-mx">
                                <h1>
                                    <a class="tqr-u-menu jb-tqr-u-menu" data-action="nav-menu" data-target="home_tqr_mnfto" href="javascript:;" role="button">{wos/deco:_PG_HOME_TX025}</a>
                                </h1>
                            </li>
                            <li class="tqr-u-menu-mx jb-tqr-u-menu-mx">
                                <h1>
                                    <a class="tqr-u-menu jb-tqr-u-menu" data-action="nav-menu" data-target="home_tqr_faq" href="javascript:;" role="button">{wos/deco:_PG_HOME_TX026}</a>
                                </h1>
                            </li>
                            <!--<li class="tqr-u-menu-mx"><h1><a class="tqr-u-menu" href="/connexion">Questions fréquentes</a></h1></li>-->
                        </ul>
                    </nav>
    <!--                <div>
                        [SLIDER]
                    </div>-->
                </header>
                <div id="tqr-unvrs-scrn-bdy">
                <div class="tqr-unvrs-main-sec-bmx" data-scp="black-screen">
                    <a class="tqr-unvrs-scnd-nav-btn jb-tqr-unvrs-scnd-nav-btn this_hide" href="javascript:;" data-action="nav-prev" data-css="nav-prev" mode='1' role="button"></a>
                    <a class="tqr-unvrs-scnd-nav-btn jb-tqr-unvrs-scnd-nav-btn" href="javascript:;" data-action="nav-next" data-css="nav-next" mode='1' role="button"></a>
                    <section class="tqr-unvrs-scnd-sec-bmx jb-tqr-unvrs-scnd-sec-bmx" data-scp="home_tqr_wha">
                        <header class="tqr-unvrs-scnd-sec-hdr">

                        </header>
                        <div class="tqr-unvrs-scnd-sec-bdy">
                            <div id="tqr-unvrs-tqr-wha-vid-wpr">
                                <!--<iframe width="800" height="450" src="https://www.youtube.com/embed/SuFKZ6P4paE" frameborder="0" allowfullscreen></iframe>-->
                                <iframe width="800" height="450" src="https://www.youtube.com/embed/9O1EVRYTSLY" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <footer class="tqr-unvrs-scnd-sec-ftr"></footer>
                    </section>
<!--                    <section class="tqr-unvrs-scnd-sec-bmx jb-tqr-unvrs-scnd-sec-bmx this_hide" data-scp="home_tqr_prvw">
                        <header></header>
                        <div>

                            QU'EST CE QUE TRENQR ?
                            Trenqr est un réseau social européen qui s'inscrit comme une alternative en corps et en esprit aux réseaux sociaux tels que Facebook, Twitter ou autres ...

                            En bref un lieu ..

                            [VIDEO]

                        </div>
                        <footer></footer>
                    </section>-->
                    <section class="tqr-unvrs-scnd-sec-bmx jb-tqr-unvrs-scnd-sec-bmx this_hide" data-scp="home_tqr_mnfto">
                        <header class="tqr-unvrs-scnd-sec-hdr"></header>
                        <div class="tqr-unvrs-scnd-sec-bdy">
                            <div id="tqr-unvrs-tqr-mnfto-vid-wpr">
                                <p class="par title ">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX001}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX002}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX003}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX004}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX005}
                                </p>
<!--                                <div class="this_hide">
                                    <p class="par">Par exemple,</p>
                                    <p class="par">
                                        En allant sur Facebook vous acceptez une vie sociale clanique où vous vous faites peu de « vrai » nouveaux amis. 
                                        De plus, vous savez qu’en vous connectant, il y a de fortes chances que vous tombiez soit sur le dernier statut, d’une longueur interminable au sujet de la dernière crise amoureuse d’Elodie, qui traite, encore, son petit ami des derniers des salauds, 
                                        soit sur la dernière réflexion très existentielle, profonde et philosophique de Joshua, 15 ans, sur la vie et la société ou peut-être la dernière pic d’Anna, mais qui tiens à préciser qu’elle « n’accuse personne ». 
                                        Sans oublier la photo du énième achat de cet inconnu dans votre liste « d’ami » qui vous fait sentir tellement minable... 😩 
                                        En clair, la messe est dite et ce n’est pas prêt de changer.
                                    </p>
                                    <p class="par">
                                        Sur Twitter, il n’est pas question d’ami, vous avez des « followers ». 
                                        Ici, vous acceptez d’avoir une vie privée quasi-inexistante, une ambiance au gout d’anarchie faite d’insultes, de reproches et de nouveaux abonnés qui disparaissent au bout de 2 jours… 
                                        Mais au moins c’est divertissant ! Surtout si vous êtes friand de débats stériles ou de polémiques à n’en plus finir, au sujet de la dernière image, vidéos ou discours supposé sexiste, misogyne, raciste, homophobe, xénophobe ou grossophobe … 
                                        qui va finir par avoir raison d’une marque ou forcer le site à revoir sa politique de censure pour plaire au premier politicien ou associations de droits des femmes, animaux, hommes, gros, etc … qui fera le buzz le temps d’une journée. 
                                        Eh oui, Twitter c’est avant tout un environnement ultra politisé  et très monolithique.
                                    </p>
                                    <p>
                                        De Google+, je ne sais pas grand-chose. Je pense y avoir un compte, mais je me suis souvient pas l’avoir créé, donc …
                                    </p>
                                </div>-->
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX006}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX007}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX008}
                                </p>
                                <p class="par">
                                    {wos/deco:_PG_HOME_SC_MANIF_TX009}
                                    <!--<a id="long-version" href="" title="Afficher la version longue du manifeste">Lire la version longue</a>-->
                                </p>
                            </div>
                        </div>
                        <footer class="tqr-unvrs-scnd-sec-ftr"></footer>
                    </section>
                    <section class="tqr-unvrs-scnd-sec-bmx jb-tqr-unvrs-scnd-sec-bmx this_hide" data-scp="home_tqr_faq">
                        <header class="tqr-unvrs-scnd-sec-hdr"></header>
                        <div class="tqr-unvrs-scnd-sec-bdy">
                            <div id="tqr-unvrs-tqr-faq-vid-wpr">
                                <ul>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX001}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX002}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX003}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX004}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX005}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX006}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX007}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX008}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX009}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX010}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX011}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX012}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX013}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX014}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX015}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX016}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX017}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX018}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX019}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX020}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX021}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX022}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX023}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX024}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX025}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX026}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX027}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX028}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX029}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX030}<br/>{wos/deco:_PG_HOME_SC_FAQ_TX031}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX032}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX033}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX034}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX035}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX036}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="faq-group">
                                        <div class="faq-grp-q">{wos/deco:_PG_HOME_SC_FAQ_TX037}</div>
                                        <div class="faq-grp-a">
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX038}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX039}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX040}
                                            </div>
                                            <div>
                                                {wos/deco:_PG_HOME_SC_FAQ_TX041}
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <footer class="tqr-unvrs-scnd-sec-ftr"></footer>
                    </section>
                </div>
                <div class="tqr-unvrs-main-sec-bmx" data-scp="white-screen">
                    <section class="tqr-u-section" data-section="what-trenqr">
                        <div class="tqr-u-sctn-body">
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="left" >
                                    <div>{wos/deco:_PG_HOME_SC_DISCUSS_TX007}</div>
                                </div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="what-tqr-q" locdir="left">
                                    <span>{wos/deco:_PG_HOME_SC_DISCUSS_TX001}</span>
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr • Amis et passions en photos et vidéos" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="what-tqr-ans" locdir="right">
                                    <div> 
                                        {wos/deco:_PG_HOME_SC_DISCUSS_TX002}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="tqr-u-section" data-section="trenqr">
                        <div class="tqr-u-sctn-body">
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="left" >
                                    <div>{wos/deco:_PG_HOME_SC_DISCUSS_TX007}</div>
                                </div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-q" locdir="left">
                                    {wos/deco:_PG_HOME_SC_DISCUSS_TX003}
                                </div>
                             </div>
                             <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr • Amis et passions en photos et vidéos" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    <div class="par">
                                        {wos/deco:_PG_HOME_SC_DISCUSS_TX004}
                                    </div>
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr • Amis et passions en photos et vidéos" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    <div class="par">
                                        {wos/deco:_PG_HOME_SC_DISCUSS_TX005}
                                    </div>
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr • Amis et passions en photos et vidéos" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    <div class="par">
                                        {wos/deco:_PG_HOME_SC_DISCUSS_TX006}
                                    </div>
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr • Amis et passions en photos et vidéos" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    <div class="par">
                                        {wos/deco:_PG_HOME_SC_DISCUSS_TX008}
                                    </div>
                                </div>
                            </div>
                            <div class="tqr-u-sctn-bbl-wpr">
                                <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" alt="Trenqr • Amis et passions en photos et vidéos" /></div>
                                <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                    <div class="par">
                                        {wos/deco:_PG_HOME_SC_DISCUSS_TX009}
                                    </div>
                                </div>
                            </div>
    <!--                                <div class="tqr-u-sctn-bbl-wpr">
                                        <div class="tqr-u-sctn-bbl-face" locdir="right"><img width="76px" src="{wos/sysdir:img_dir_uri}/r/tqr_mlti_b.png" /></div>
                                        <div class="tqr-u-sctn-bbl-bbl" data-obj="why-pic-ans" locdir="right">
                                            <div class="par">
                                                Trenqr est un réseau social authentique qui se positionne comme une 
                                                <strong class="bold plus_viz">alternative</strong> à la routine de <strong class="bold plus_viz">Facebook</strong>, aux chicaneries stérilles de <strong class="bold plus_viz">Twitter</strong> et à l'ultra esthétisme de <strong class="bold plus_viz">Pinterest</strong> voire d'Instragram.
                                            </div>
                                        </div>
                                    </div>-->
                        </div>
                    </section>
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