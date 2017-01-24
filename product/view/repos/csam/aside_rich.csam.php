
<?php 
$ia = "{wos/datx:iauth}";
//[NOTE 10-12-14] L'opération de "parsage" transforme (malheuresement) notre TRUE en "1".
$ia = ( isset($ia) && $ia === "1" ) ? TRUE : FALSE;

?>

<div id="aside-rich-banner" class="jb-asd-r-bnr-mx">
    <?php if ( $ia !== TRUE ) : ?>
    <section class="asd-rch-sctn jb-asd-rch-sctn with_header" data-section="ad1">
        <header class="asd-rch-sctn-hdr"></header>
        <div class="asd-rch-sctn-bdy">
            <div class="asd-r-tqr-wpr">
                <img src="http://www.placehold.it/300x250" />
<!--                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-7028578741126541"
                     data-ad-slot="4722967717"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>-->
            </div>
            <div class="asd-r-tqr-rmv-mx"><a class="asd-r-tqr-rmv cursor-pointer jb-asdr-w-ads-wa-lk" >Désactiver la publicité</a></div>
        </div>
    </section>
    <?php endif; ?>
    <?php if ( $ia === TRUE ) : ?>
    <section class="asd-rch-sctn jb-asd-rch-sctn with_header" data-section="profil-sugg">
        <header class="asd-rch-sctn-hdr" title="De quoi parle-t-on sur Trenqr">Bla Bla Bla Bla</header>
        <div class="asd-rch-sctn-bdy">
            <div class="jb-asd-rch-s-bla-cache this_hide" data-live="" data-bsof=""></div>
            <ul id="asd-rch-s-bla-lst-mx" class="jb-asd-rch-s-bla-lst-mx">
                <?php for($a3=0;$a3<0;$a3++) : ?>
                <li class="asd-rch-s-bla-kw-mx jb-asd-rch-s-bla-kw-mx">
                    <a class="asd-rch-s-bla-kw jb-asd-rch-s-bla-kw" href="/hview/q=UnMotClé&amp;src=hash">#UnMotClé<?php echo $a3; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
            <div id="asd-rch-s-bla-fil-mx" class="jb-asd-rch-s-bla-fil-mx">
                <a class="asd-rch-s-bla-fil cursor-pointer jb-asd-rch-s-bla-fil active" data-css="live" data-action="go-live" title="Ce dont les gens parlent le plus en ce moment">Live</a>
                <a class="asd-rch-s-bla-fil cursor-pointer jb-asd-rch-s-bla-fil" data-css="bsof-h" data-action="go-bsof-h" title="Ce dont les gens ont le plus parlé">BestOf</a>
                <!--<a class="asd-rch-s-bla-fil cursor-pointer jb-asd-rch-s-bla-fil" data-css="bsof-c" data-action="go-bsof-p" title="Les discussions les plus populaires sur Trenqr">Chat</a>-->
            </div>
        </div>
    </section>
    <?php endif; ?>
    <section class="asd-rch-sctn jb-asd-rch-sctn with_header" data-section="profil-sugg">
        <header class="asd-rch-sctn-hdr">Suggestions de comptes</header>
        <div class="asd-rch-sctn-bdy">
            <div class="asd-rch-sctn-dvdr" sata-scp="trendy"><span class="title">Comptes populaires</span></div>
            <ul id="asd-rch-s-psg-lsts" class="jb-asd-rch-s-psg-lsts">
                <?php // for($a1=0;$a1<4;$a1++) : ?>
                <?php for($a1=0;$a1<0;$a1++) : ?>
                <li class="asd-rch-s-psg-lst jb-asd-rch-s-psg-lst" data-item="">
                    <div class="asd-rch-s-psg-pfl-bmx">
                        <div class="asd-rch-s-psg-pfl-l">
                            <a class="asd-rch-s-psg-pfl-hfr" href="">
                                <img class="asd-rch-s-psg-pfl-i jb-asd-rch-s-psg-pfl-i <?php echo ( $a1%2 === 0 ) ? "even": "odd"; ?>" src="http://www.lorempixel.com/50/50/people/<?php echo rand(1,9); ?>" alt="" height="50" width="50"/>
                                <span class="asd-rch-s-psg-pfl-i-fd"></span>
                            </a>
                        </div>
                        <div class="asd-rch-s-psg-pfl-r">
                            <div>
                                <a class="asd-r-s-psg-pfl-psd jb-asd-r-s-psg-pfl-psd" href="">@Pseudo</a>
                            </div>
                            <div>
                                <span class="asd-r-s-psg-pfl-fn jb-asd-r-s-psg-pfl-fn">Dupont Langelois</span>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endfor; ?>
            </ul>
            <!--<div class="asd-rch-sctn-dvdr" sata-scp="others-suggs"><span class="title">Devrait vous interesser</span></div>-->
        </div>
        <div id="asd-rch-invite-frd">
            <a id="asd-rch-inv-frd-tgr" href="/!/recommend-trenqr-image-trend-cool-community">Parrainer un ami</a>
        </div>
        <div id="asd-rch-invite-pts-inf">
            Parainer un nouvel utilisateur vous rapportera <b>10 points Karma</b>.
        </div>
    </section>
    <section class="asd-rch-sctn jb-asd-rch-sctn with_header" data-section="trend-sugg">
        <header class="asd-rch-sctn-hdr">Suggestions de Salons</header>
        <div class="asd-rch-sctn-bdy">
            <ul id="asd-rch-s-tsg-lsts" class="jb-asd-rch-s-tsg-lsts">
                <?php // for($a1=0;$a1<3;$a1++) : ?>
                <?php for($a1=0;$a1<0;$a1++) : ?>
                <li class="asd-rch-s-tsg-lst <?php if ( $a1 === 2 ) echo "last"; ?> jb-asd-rch-s-tsg-lst" data-item="">
                    <div>
                        <div class="asd-rch-s-tsg-hdr">
                            <a class="asd-rch-s-tsg-cov-mx jb-asd-rch-s-tsg-cov-mx" href="">
                                <img class="asd-rch-s-tsg-cov-i jb-asd-rch-s-tsg-cov-i" src="http://www.lorempixel.com/255/140/animals/<?php echo rand(2,8); ?>" alt=""  width="255" height="140" />
                                <span class="asd-rch-s-tsg-cov-i-fd"></span>
                                <div class="asd-rch-s-tsg-cov-x jb-asd-rch-s-tsg-cov-x"> 
                                    <div class="publications-mx jb-publications-mx this_hide"><span class="stats">0</span> publications</div>
                                    <div class="subscribers-mx jb-subscribers-mx this_hide"><span class="stats">0</span> abonnées</div>
                                </div>
                            </a>
                        </div>
                        <div class="asd-rch-s-tsg-bdy" >
                            <div class="asd-rch-s-tsg-tle-mx">
                                <a class="asd-rch-s-tsg-tle jb-asd-rch-s-tsg-tle" href="">Titre de la description</a>
                            </div>
                            <div class="asd-rch-s-tsg-dsc-mx">
                                <a class="asd-rch-s-tsg-dsc jb-asd-rch-s-tsg-dsc" href="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ullamcorper quis nibh at imperdiet. Vestibulum vulputate pulvinar magna, in congue sed.</a>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endfor; ?>
            </ul>
        </div>
    </section>
    <?php if ( $ia !== TRUE ) : ?>
    <section class="asd-rch-sctn jb-asd-rch-sctn with_header" data-section="ad2">
        <header class="asd-rch-sctn-hdr"></header>
        <div class="asd-rch-sctn-bdy">
            <div class="asd-r-tqr-wpr">
                <img src="http://www.placehold.it/300x250" />
<!--                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-7028578741126541"
                     data-ad-slot="7676434115"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>-->
            </div>
            <div class="asd-r-tqr-rmv-mx"><a class="asd-r-tqr-rmv cursor-pointer jb-asdr-w-ads-wa-lk" >Désactiver la publicité</a></div>
        </div>
    </section>
    <section class="asd-rch-sctn jb-asd-rch-sctn with_header" data-section="ad3">
        <header class="asd-rch-sctn-hdr"></header>
        <div class="asd-rch-sctn-bdy">
            <div class="asd-r-tqr-wpr">
                <img src="http://www.placehold.it/300x250" />
<!--                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-7028578741126541"
                     data-ad-slot="7921260510"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>-->
            </div>
            <div class="asd-r-tqr-rmv-mx"><a class="asd-r-tqr-rmv cursor-pointer jb-asdr-w-ads-wa-lk" >Désactiver la publicité</a></div>
        </div>
    </section>
    <?php endif; ?>
    {wos/dvt:legals}
</div>

