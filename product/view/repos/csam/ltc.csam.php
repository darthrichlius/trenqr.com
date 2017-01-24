
<section id="tqr-ltc-sprt" class="jb-tqr-ltc-sprt">
    <div id="tqr-ltc-scrn" class="jb-tqr-ltc-scrn">
        <div id="tqr-ltc-scrn-app-holdit" class="jb-tqr-ltc-s-ap-hoit">
            <a id="tqr-ltc-s-ap-hoit-fmr" class="jb-tqr-ltc-s-ap-hoit-fmr" data-action="hoit-close">&times;</a>
            <div id="tqr-ltc-s-ap-hoit-hdr">
                <div>Messages mis de côté</div>
                <div id="tqr-ltc-s-ap-hoit-hdr-desc">Ajoutez ici les messages auxquels vous voulez porter une attention particulière</div>
            </div>
            <div id="tqr-ltc-s-ap-hoit-bdy" class="jb-tqr-ltc-s-ap-hoit-bdy">
                <?php for($i=0;$i<0;$i++): ?>
                <article class="tqr-ltc-msg-art hoit jb-tqr-ltc-s-ap-hoit-amx " data-item="" data-tiem="" >
                    <div class="">
                        <a class="tqr-ltc-m-ubx hoit jb-tqr-ltc-m-ubx" href="">
                            <span class="tqr-ltc-m-ubx-imx">
                                <span class="tqr-ltc-m-ubx-i-fd"></span>
                                <img class="tqr-ltc-m-ubx-i jb-tqr-ltc-m-ubx-i" height="28" width="28" alt="Nom Complet (@Pseudo)" src="http://www.placehold.it/28/28" />
                            </span>
                            <span class="tqr-ltc-m-ubx-p jb-tqr-ltc-m-ubx-p cursor-pointer" title="Nom Complet">@Pseudo</span>
                        </a>
                        <!-- Ne sera affiché que si l'utilisateur se trouve dans un pays different de eclui de l'utilisateur connecté -->
                        <span class="tqr-ltc-m-cny jb-tqr-ltc-m-cny this_hide">[FR]</span>
                        <span class="tqr-ltc-m-msg jb-tqr-ltc-m-msg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et enim ipsum. Donec eget nullam.</span>
                    </div>
                    <!-- Extras : Signaler, Suivre -->
                    <div class="tqr-ltc-s-ap-hoit-amx-ftr">
                        <a class="tqr-ltc-s-ap-hoit-amx-opts jb-tqr-ltc-s-ap-hoit-amx-opts" data-css="hoit-remove" data-action="hoit-remove" title="Retirer de la liste">Retirer</a>
                    </div>
                </article>
                <?php endfor; ?>
            </div>
        </div>
        <header id="tqr-ltc-scrn-hdr">
            <div id="tqr-ltc-scrn-akx">
                <a id="tqr-ltc-sld-opnr" class="jb-tqr-ltc-action cursor-pointer" data-action="ltc-hide"></a>
            </div>
            <div id="tqr-ltc-scrn-front">
                <div id="tqr-ltc-scrn-pndg" class="jb-tqr-ltc-scrn-pndg">Connexion <span class="animation">...</span></div>
                <div id="tqr-ltc-scrn-cnxnb" class="jb-tqr-ltc-scrn-cnxnb this_hide">
                    <span class="_figure">-</span> <span>connecté(s)</span>
                </div>
            </div>
        </header>
        <div id="tqr-ltc-scrn-bdy" class="jb-tqr-ltc-scrn-bdy">
            <div id="tqr-ltc-scrn-wlc" class="jb-tqr-ltc-scrn-wlc">
                <div id="tqr-ltc-wlc-tle">Bienvenue sur le chat.</div> 
                <div id="tqr-ltc-wlc-vrboz">Retrouvez tous les abonnés à cette Tendance. Échangez et faites plus amples connaissances.</div>
            </div>
            <div id="tqr-ltc-list-lvms" class="jb-tqr-ltc-list-lvms this_hide">
                <?php for($i=0;$i<0;$i++): ?>
                <article class="tqr-ltc-msg-art jb-tqr-ltc-msg-art" data-item="" data-time="" data-user="">
                    <div>
                        <a class="tqr-ltc-m-ubx jb-tqr-ltc-m-ubx" href="">
                            <img class="tqr-ltc-m-ubx-i jb-tqr-ltc-m-ubx-i" height="28" width="28" alt="Nom Complet (@Pseudo)" src="http://www.placehold.it/28/28" />
                            <a class="tqr-ltc-m-ubx-p jb-tqr-ltc-m-ubx-p cursor-pointer" title="Nom Complet">@Pseudo</a>
                        </a>
                        <!-- Ne sera affiché que si l'utilisateur se trouve dans un pays different de eclui de l'utilisateur connecté -->
                        <span class="tqr-ltc-m-cny jb-tqr-ltc-m-cny this_hide">[FR]</span>
                        <span class="tqr-ltc-m-msg jb-tqr-ltc-m-msg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et enim ipsum. Donec eget nullam.</span>
                    </div>
                    <!-- Extras : Signaler, Suivre -->
                    <div class="tqr-ltc-msg-art-ftr">
                        <a class="tqr-ltc-amx-opts jb-tqr-ltc-amx-opts" data-action="hoit-add">Mettre de côté</a>
                    </div>
                </article>
                <?php endfor; ?>
            </div>
        </div>
        <footer id="tqr-ltc-scrn-ftr">
            <div id="tqr-ltc-s-f-txar-mx">
                <textarea id="tqr-ltc-s-f-txar" class="jb-tqr-ltc-s-f-txar" placeholder="Discutez de cette Tendance"></textarea>
            </div>
            <div id="tqr-ltc-s-f-opts-mx">
                <a id="tqr-ltc-s-f-hoit" class="jb-tqr-ltc-s-f-hoit jb-tqr-ltc-action" data-action="hoit-open">
                    Mis de côté
                    <span class="_figure"></span>
                </a>
                <button id="tqr-ltc-s-f-send" class="jb-tqr-ltc-action" data-action="send-msg">Envoyer</button>
            </div>
        </footer>
    </div>
</section>