
<div id="bgzy-sprt" class="jb-bgzy-sprt this_hide">
    <div id="bgzy-mx">
        <div id="bgzy-header">
            <div id="bgzy-title">Signaler un dysfonctionnement</div>
            <div id="bgzy-intro">
                Merci de nous signaler tous types de <b>dysfonctionnements techniques</b> que vous aurez pu rencontrer lors de votre utilisation de Trenqr.
            </div>
        </div>
        <form id="bgzy-form" class="jb-bgzy-form" method="post" action="">
            <div id="bgzy-ipt-mx">
                <div id="bgzy-th-mx">
                    <select id="bgzy-th-chs" class="bgzy-fld jb-bgzy-fld" data-ft="type" required>
                        <option value="init" selected>Veuillez sélectionner le type de problème ...</option>
                        <option value="BGTYP_CNX">Problèmes liés à la connexion</option>
                        <option value="BGTYP_SSN">Problèmes liés aux sessions</option>
                        <option value="BGTYP_VW">Problèmes liés à l'affichage</option>
                        <option value="BGTYP_DT">Problèmes liés aux données et leur traitement</option>
                        <option value="BGTYP_SEC">Problèmes liés à la sécurité ou à la fiabilité</option>
                        <option value="BGTYP_PRF">Problèmes liés aux performances</option>
                        <option value="BGTYP_PFL">Problèmes techniques liés à mon profil</option>
                        <option value="BGTYP_NAV">Problèmes techniques liés à la navigation</option>
                        <option value="BGTYP_SRH">Problèmes techniques liés à la recherche</option>
                        <option value="BGTYP_ART">Problèmes techniques liés aux articles (images)</option>
                        <option value="BGTYP_TRD">Problèmes techniques liés aux Tendances</option>
                        <option value="BGTYP_BGZY">Problèmes techniques liés au signalement de bogues</option>
                        <option value="BGTYP_OTHER">Autres problèmes techniques</option>
                    </select>
                </div>  
                <div id="bgzy-bug-whr-mx" class="bgzy-bug-qst-mx bgzy-bug-whr">
                    <span class="bgzy-bug-qst-ipt-lbl">Page ou Module ?</span>
                    <input id="bgzy-bug-whr-ipt" class="bgzy-fld bgzy-bug-qst-ipt jb-bgzy-fld" data-ft="where" type="type" placeholder="(Exemple : Page de connexion, Photos du jour, etc.)" maxlength="70" title="Minimum 8 caractères" required/>
                </div>
                <div id="bgzy-bug-whn-mx" class="bgzy-bug-qst-mx jb-bgzy-bug-whn-mx">
                    <span class="bgzy-bug-qst-ipt-lbl">Quelle date ?</span>
                    <input id="bgzy-bug-whr-whn" class="bgzy-fld bgzy-bug-qst-ipt jb-bgzy-fld" data-ft="when" type="text" placeholder="(Exemple : Le 10/12/2014)" maxlength="70" required/>
                </div>
                <div id="bgzy-ipt-lbl">
                    Veuillez décrire avec un maximum de précisions, le problème technique rencontré, l'environnement et le contexte dans lequel celui-ci s'est déclaré (min. 100 caractères) :
                </div>
                <textarea id="bgzy-ipt" class="check_char bgzy-fld jb-bgzy-fld-ipt jb-bgzy-fld" data-ft="message" data-maxch="1000" data-target="bgzy-ipt-ln" maxlength="1000" required></textarea>
                <div id="bgzy-ipt-err" class="jb-bgzy-ipt-err"></div>
                <div id="bgzy-ipt-ln-mx">
                    <label id="bgzy-ipt-lang-lab" for="bgzy-ipt-lang-select">Langue de correspondance : </label>
                    <select id="bgzy-ipt-lang-select" class="bgzy-fld jb-bgzy-fld" data-ft="lang" required>
                        <option value="fr" selected>Francais</option>
                        <option value="en">English</option>
                    </select>
                    <span id="bgzy-ipt-ln" class="jb-bgzy-ipt-ln">1000</span>
                </div>
            </div>
            <div id="bgzy-caution">
                <b>Toute autre demande ne sera pas traitée</b>. En cas d'abus, les dispositions citées au niveau des <a id="" href="/terms">conditions d'utilisation</a> s'appliqueront.
                Notez que certains champs necessitent une limite minimum de caractères pour être considérés comme valides.
            </div>
            <div id="bgzy-submit-mx">
                <span id="bgzy-spnr" class="jb-bgzy-spnr this_hide">
                    <img src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                </span>
                <input id="bgzy-submit-trg" class="jb-bgzy-submit-trg" type="submit" value="Soumettre" />
            </div>
        </form>
    </div>
</div>

