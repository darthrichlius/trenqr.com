<?php ?>
<div s-id="TQR_STUDIO_GTPG" >
    <section id="tqs-screen">
        <header id="tqs-screen-header">
            <div id="tqs-scrn-hdr-home-mx">
                <a id="tqs-scrn-hdr-home-hrf" href="//studio.trenqr.com" title="Trenqr Studio">
                    <span id="tqs-scrn-hdr-home-txt">Studio</span>
                    <img id="tqs-scrn-hdr-home-i" src="/bart1/timg/files/img/r/tqr_mlti_b.png"/>
                </a>
                <span id="tqs-scrn-hdr-bytqr-mx">
                    <span id="tqs-scrn-hdr-bytqr-by">Par</span>
                    <a id="tqs-scrn-hdr-bytqr-hrf" href="//www.trenqr.com">
                        <img id="tqs-scrn-hdr-bytqr-i" width="60" src="/bart1/timg/files/img/r/logo_tqr_beta_be.png"/>
                    </a>
                </span>
            </div>
            <nav id="tqs-scrn-hdr-nav-mx" >
                <a class="tqs-scrn-hdr-nav-ch" href="//www.trenqr.com/ontrenqr&v=1m30" data-action="signup" href="">Qu'est ce que Trenqr ?</a>
                <!--<a class="tqs-scrn-hdr-nav-ch" href="/login" data-action="signup" href="">Connexion / Inscription</a>-->
                <!--<a class="tqs-scrn-hdr-nav-ch" data-action="login" href="">Se connecter</a>-->
            </nav>
        </header>
        <div id="tqs-screen-body">
            <section id="tqs-scrn-bdy-blcs-bmx">
                <div id="tqs-scrn-bdy-blcs-mx">
                    <div class="tqs-scrn-bdy-blcs jb-tqs-scrn-bdy-blcs" data-section="center">
                        <div id="tqs-scrn-bdy-ctr-hdr" data-section="left">
                            <ul id="tqs-top-options-mx">
                                <li class="tqs-top-opt-mx jb-tqs-top-opt-mx jb-tqs-grpact" data-gpaction="top_decision">
                                    <a class="tqs-top-opt-action cursor-pointer jb-tqs-top-opt-actn" data-action="upload" title="Importer une image depuis votre ordinateur" role="button">
                                        <span id="">Importer</span>
                                        <input id="tqs-top-opt-act-ipt" class="jb-tqs-top-opt-act-ipt" type="file" />
                                    </a>
                                </li>
                                <li class="tqs-top-opt-mx jb-tqs-top-opt-mx jb-tqs-grpact" data-gpaction="top_decision">
                                    <a class="tqs-top-opt-action cursor-pointer jb-tqs-top-opt-actn" data-action="download" title="Sauvegarder l'image modifiée" role="button">Télécharger</a>
                                </li>
                                <li class="tqs-top-opt-mx jb-tqs-top-opt-mx jb-tqs-grpact" data-gpaction="top_decision">
                                    <a class="tqs-top-opt-action cursor-pointer jb-tqs-top-opt-actn" data-action="back_raw" title="Revenir à l'image originale" role="button">Original</a>
                                </li>
                            </ul>
                            <a class="tqs-top-opt-action cursor-pointer jb-tqs-top-opt-actn this_hide" data-action="erase" title="Supprimer l'image" role="button"></a>
                        </div>
                        <div id="tqs-scrn-bdy-ctr-bdy" class="jb-tqs-scrn-bdy-ctr-bdy" data-section="left">
                            <div id="tqs-wait-pnl-mx" class="jb-tqs-wait-pnl-mx this_hide">
                                <div id="tqs-wait-pnl-txt-mx">
                                    <span id="tqs-wait-pnl-txt">Patientez ... <i class="fa fa-cog fa-spin"></i></span>
                                </div>
                            </div>
                            <div id="tqs-invitation-mx" class="jb-tqs-invitation-mx">
                                <div id="tqs-invit-cover"></div>
                                <div id="tqs-invit-dsc">Importez une image pour commencer</div>
                            </div>
                            <canvas id="tqs-canvas" class="jb-tqs-canvas this_hide"></canvas>
                        </div>
                    </div>
                    <div class="tqs-scrn-bdy-blcs jb-tqs-scrn-bdy-blcs this_hide" data-section="right">
                        <section id="tqs-scrn-control-bmx">
                            <header id="tqs-scrn-ctrl-hdr-mx">
                                <nav>
                                    <label id="tqs-scrn-ctrl-mn-lbl-mx">
                                        <!--<span id="tqs-scrn-ctrl-mn-lbl">Menu </span>-->
                                        <select id="tqs-scrn-ctrl-mn-slct" class="jb-tqs-scrn-ctrl-mn-slct" data-gpaction="menu_selector">
                                            <option value="custom_cropping" selected>Redimensionner</option>
                                            <option value="custom_color">Modifier les couleurs</option>
                                            <option value="custom_filter">Appliquer un filtre</option>
                                        </select>
                                    </label>
                                </nav>
                            </header>
                            <div id="tqs-scrn-ctrl-bdy-mx">
                                
                                <!-- CONTROLS : CUSTOM FILTER -->
                                <ul class="tqs-scrn-ctrl-grpact-bmx jb-tqs-scrn-ctrl-grpact-bmx this_hide" data-scp="custom_filter">
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="arashiyama">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="arashiyama">Arashiyama</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="baikal">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="baikal">Baïkal</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="brazzaville">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="brazzaville">Brazzaville</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="edisson">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="edisson">Edisson</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="koala">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="koala">Koala</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="lalibela">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="lalibela">Lalibela</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="le_caire">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="le_caire">Le Caire</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="londres">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="londres">Londres</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="gorges_diosso">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="gorges_diosso">Gorges de Diosso</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="gotham">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="gotham">Gotham</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="hdr_effect">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="hdr_effect">HDR Effect</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="hemingway">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="hemingway">Hemingway</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="hillier">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="hillier">Hillier</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="mars">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="mars">Mars</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="minas_tirith">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="minas_tirith">Minas Tirith</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="moscou">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="moscou">Moscou</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="notre_dame">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="notre_dame">Notre Dame</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="ouarzazate">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="ouarzazate">Ouarzazate</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="paris">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="paris">Paris</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="radial">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="radial">Radial</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="tarantino">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="tarantino">Tarantino</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="kanga_moussa">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="kanga_moussa">Kanga Moussa</a>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_filter" data-gpaction="yingyang">
                                        <a class="tqs-cstmfltr-action-btn jb-tqs-cstmfltr-actn-btn" data-action="custom_filter" data-option="yingyang">YingYang</a>
                                    </li>
                                </ul>
                                 
                                <!-- CONTROLS : CUSTOM COLOR -->
                                <ul class="tqs-scrn-ctrl-grpact-bmx jb-tqs-scrn-ctrl-grpact-bmx this_hide" data-scp="custom_color">
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="brightness">
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label">
                                            <span class="tqs-cstmclr-lbl">luminosité</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="-100" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="contrast" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label">
                                            <span class="tqs-cstmclr-lbl">contraste</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="-100" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="saturation" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label">
                                            <span class="tqs-cstmclr-lbl">saturation</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="-100" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="vibrance" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label">
                                            <span class="tqs-cstmclr-lbl">vibrance</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="-100" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="hue" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">teinte</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="gamma" data-min="" data-max="">
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label">
                                            <span class="tqs-cstmclr-lbl">gamma</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="10" step="0.1" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="clip" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">clip</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="stackBlur" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">flou</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="20" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="exposure" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">exposition</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="-100" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="sepia" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">sepia</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="noise" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">grain</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_color" data-gpaction="sharpen" >
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="minus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-label jb-tqs-cstmclr-label" >
                                            <span class="tqs-cstmclr-lbl">affiné</span>
                                            <input class="tqs-cstmclr-rng jb-tqs-cstmclr-rng" data-action="range" type="range" min="0" max="100" step="2" value="0" />
                                        </span>
                                        <button class="tqs-cstmclr-action-btn jb-tqs-cstmclr-actn-btn" data-action="plus" title="">
                                            <span class="tqs-cstmclr-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-cstmclr-rslt jb-tqs-cstmclr-rslt" >0</span>
                                    </li>
                                </ul>
                                
                                <!-- CONTROLS : CROPPER -->
                                <ul class="tqs-scrn-ctrl-grpact-bmx jb-tqs-scrn-ctrl-grpact-bmx" data-scp="custom_cropping">
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="dragmode" data-value="{v:'move'}">
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn left" data-action="dragmode" data-option="move" title="">
                                            <span class="tqs-crop-a-b-logo"></span> 
                                        </button>
                                        <span class="tqs-crop-label" >Mode selection</span>
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn right" data-action="dragmode" data-option="crop" title="">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="zoom" data-value="{v:0}">
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn left" data-action="zoom" data-option="-0.1" title="Zoom arrière">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-crop-label" >Zoom</span>
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn right" data-action="zoom" data-option="0.1" title="Zoomer avant">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="rotate" data-value="{v:0}">
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn left" data-action="rotate" data-option="-1" title="Tourner vers la gauche">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-crop-label" >Rotation 1°</span>
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn right" data-action="rotate" data-option="1" title="Tourner vers la droite">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="rotate" data-value="{v:0}">
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn left" data-action="rotate" data-option="-5" title="Tourner vers la gauche">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-crop-label" >Rotation 5°</span>
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn right" data-action="rotate" data-option="5" title="Tourner vers la droite">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="rotate" data-value="{v:0}">
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn left" data-action="rotate" data-option="-15" title="Tourner vers la gauche">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-crop-label" >Rotation 15°</span>
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn right" data-action="rotate" data-option="15" title="Tourner vers la droite">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="reverse" data-value="{x:1,y:1}">
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn left" data-action="reverse" data-option="x" title="Inverser sur l'axe horizontal">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                        <span class="tqs-crop-label" >Inverser</span>
                                        <button class="tqs-crop-action-btn jb-tqs-crop-actn-btn right" data-action="reverse" data-option="y" title="Inverser sur l'axe vertical">
                                            <span class="tqs-crop-a-b-logo"></span>
                                        </button>
                                    </li>
                                    <li class="tqs-scrn-ctrl-grpact-mx" data-scp="ratio">Choisir le ratio du cadre</li>
                                    <li class="tqs-scrn-ctrl-grpact-mx jb-tqs-scrn-ctrl-grpact-mx jb-tqs-grpact" data-scp="custom_cropping" data-gpaction="ratio" data-value="{x:1,y:1}">
                                        <button class="tqs-crop-action-btn ratio jb-tqs-crop-actn-btn" data-action="ratio" data-option="1.777777777777" title="">
                                            <span class="tqs-crop-a-b-logo ratio">16:9</span>
                                        </button>
                                        <button class="tqs-crop-action-btn ratio jb-tqs-crop-actn-btn" data-action="ratio" data-option="1.3333333" title="">
                                            <span class="tqs-crop-a-b-logo ratio">4:3</span>
                                        </button>
                                        <button class="tqs-crop-action-btn ratio jb-tqs-crop-actn-btn" data-action="ratio" data-option="0.666666666" title="">
                                            <span class="tqs-crop-a-b-logo ratio">2:3</span>
                                        </button>
                                        <button class="tqs-crop-action-btn ratio jb-tqs-crop-actn-btn selected" data-action="ratio" data-option="1" title="">
                                            <span class="tqs-crop-a-b-logo ratio">1:1</span>
                                        </button>
                                        <button class="tqs-crop-action-btn ratio jb-tqs-crop-actn-btn selected" data-action="ratio" data-option="null" title="">
                                            <span class="tqs-crop-a-b-logo ratio">Libre</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div id="tqs-scrn-ctrl-ftr-mx">
                                <div class="tqs-grpact jb-tqs-grpact" data-gpaction="final">
                                    <a class="tqs-pre-fnl-action cursor-pointer jb-tqs-pre-fnl-action" data-action="abort" title="Annuler les modifications non enregistrées">Annuler</a>
                                    <a class="tqs-pre-fnl-action cursor-pointer jb-tqs-pre-fnl-action" data-action="apply" title="Appliquer définitivement les modifications à l'image">Appliquer</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
            <div id="tqs-scrn-bdy-ftr" class="jb-tqs-scrn-bdy-ftr">
                <div id="tqs-scrn-ftr-tle">Redimensionner - Embellir - Personnaliser</div>
                <ul id="tqs-scrn-ftr-tools-lst-mx">
                    <li class="tqs-scrn-ftr-tool-mx" data-section="crop">
                        <div class="tqs-scrn-ftr-tool-tle">Redimensionnez vos photos</div>
                        <div class="tqs-scrn-ftr-tool-more">
                            Utilisez <strong>Trenqr Studio</strong> pour <strong>redimensionner</strong> facilement vos <strong>photos</strong> et les exporter à la taille souhaitée. 
                            Grâce aux cadres avec ratio prédéfini, <strong>rogner</strong> une <strong>image</strong> n'aura jamais été aussi simple.
                        </div>
                    </li>
                    <li class="tqs-scrn-ftr-tool-mx" data-section="filter">
                        <div class="tqs-scrn-ftr-tool-tle">Appliquez un filtre à vos <strong>photos</strong></div>
                        <div class="tqs-scrn-ftr-tool-more">
                            Vous disposez de <strong>filtres photo</strong> "prêt à l'emploi", pour améliorer l'aspect de vos <strong>images</strong> et les rendre encore plus belles.
                        </div>
                    </li>
                    <li class="tqs-scrn-ftr-tool-mx" data-section="color">
                        <div class="tqs-scrn-ftr-tool-tle">Ajustez les couleurs comme un pro</div>
                        <div class="tqs-scrn-ftr-tool-more">
                            En complément du <strong>filtre de couleur</strong>, vous avez la possibilité de modifier avec plus de précisions, les <strong>couleurs</strong> de votre <strong>image</strong>.
                            C'est simple mais pratique, lorsque l'on veut obtenir le meilleur rendu possible. Il ne vous reste plus qu'à l'utiliser, c'est <strong>gratuit</strong> !
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <div id="js-declare">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/jquery.ui.datepicker-fr.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxdate.enty.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/timegod.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/timegod.csam.js?{wos/systx:now}"></script>
        <!-- CARMAN FULL -->
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/cf.min.js?{wos/systx:now}"></script>
        <!-- CROPPER -->
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/crpr.min.js?{wos/systx:now}"></script>

        <script src="{wos/sysdir:script_dir_uri}/r/s/tqs.m.js?{wos/systx:now}"></script> 

    </div>
</div>